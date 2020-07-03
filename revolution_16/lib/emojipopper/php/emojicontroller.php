<?php

$emojiData = new EmojiController();
$emojiData->parseFile($emojiData->filePath);
$emojiData->sendResult();

class EmojiController
{
    /**
     * @var string The type of response. Either json or jsonp.
     */
    private $responseDataType = 'json';

    /**
     * @var array Contains the data of the parsed emoji dataFile.
     */
    private $emojiArray = [];

    /**
     * @var string FileName of official emoji dataFile (http://www.unicode.org/Public/emoji/13.0/).
     */
    public $filePath = 'emoji-test.txt';
    /**
     * @var string Name of the callback function which is sent back as part of the jsonp response.
     */
    public $responseCallback = 'parseEmoji';

    /**
     * Class Constructor.
     *
     * Parses the emoji data file defined by the parameter.
     * If the parameter isn't defined, file path defaults to __DIR__ . './emoji-test.txt';
     *
     * @param string $filePath (Optional) Path to the emoji data file.
     */
    public function __construct($filePath = null)
    {
        $this->filePath = $filePath ?? $this->filePath;
    }

    /**
     * Send the emoji data to the client in either JSON or JSONP format.
     *
     * Either a json or jsonp result is returned which contains the emoji data, parsed by this class.
     * @see EmojiController::parseFile
     *
     * In case of a jsonp response, the name of a callback function is included to the response.
     * This name is defined as a class property.
     *
     * Once the data is sent to the client, php stops executing any following code.
     *
     */
    public function sendResult()
    {
        header('Content-Type: application/json');
        if ($this->responseDataType == 'json') {
            echo json_encode($this->emojiArray);
        } else {
            echo "{$this->responseCallback}(" . json_encode($this->emojiArray) . ')';
        }
        exit;
    }

    /**
     * Parse the emoji data file.
     *
     * Parses the emoji data file is parsed to extract emoji data into an array.
     * The following details are extracted:
     * GroupName
     * Character hexCodes
     * Formatted Character hexCodes
     * Description
     *
     * Data files are available at https://unicode.org/Public/emoji/12.0/
     *
     * @param string $fileName Emoji Data File.
     * @throws Exception When an error occures.
     */
    public function parseFile($filePath)
    {
//         if (!is_readable($filePath) || is_dir($filePath)) {
//             throw new Exception("An error occured while opening file '$filePath'!");
//         }

        $i          = 0;
        $emoji      = $group = $hexCodes = $description = $qualification = [];
        $fileHandle = fopen($filePath, 'r');

        if ($fileHandle === false) {
            throw new Exception("An error occured while opening file '$filePath'!");
        }

        while (($buffer = fgets($fileHandle, 4096)) !== false) {
            //Extract groupName.
            preg_match('/ group: (.*)/', $buffer, $group, PREG_OFFSET_CAPTURE, 0);

            if (isset($group[1])) {
                $groupName = trim($group[1][0]);
            }

            //Extract emoji hexCodes, description and qualifications.
            preg_match('/^([0-9A-F]).*(?=;)/',      $buffer, $hexCodes,         PREG_OFFSET_CAPTURE, 0);
            preg_match('/# .*? ([a-zA-Z0-9].*)$/',  $buffer, $description,      PREG_OFFSET_CAPTURE, 0);
            preg_match('/; (.*qualified)/',         $buffer, $qualification,    PREG_OFFSET_CAPTURE, 0);

            if (isset($qualification[0]) && (strpos($qualification[0][0], 'full') > 0)) {
                //Emoji is fully qualified.
                if (isset($hexCodes[0])) {
                    //HexCodes found, add emoji to array with groupName and unformatted hexCodes.
                    $emoji[$i]['groupName']   = $groupName;
                    $hexCodes[0][0]             = trim($hexCodes[0][0]);
                    $emoji[$i]['hexCodes']    = $hexCodes[0][0];

                    //Add formatted hexCodes to array.
                    $singleHexCodes             = explode(' ', $hexCodes[0][0]);
                    $emoji[$i]['formatted']   = '';
                    foreach ($singleHexCodes as $code) {
                        $emoji[$i]['formatted'] .= "&#x{$code};";
                    }

                    //Add description to array.
                    if (isset($description[1])) {
                        $description[1][0]          = trim($description[1][0]);
                        $emoji[$i]['description'] = $description[1][0];
                    } else {
                        trigger_error("No description found for emoji {$emoji[$i]['formatted']};", E_USER_WARNING);
                    }
                } else {
                    trigger_error("No hexCodes found at line $i", E_USER_WARNING);
                }
            }
            $i++;
        }

        if (!feof($fileHandle)) {
            throw new Exception("Parsing file '$filePath' did not complete!");
        }
        fclose($fileHandle);

        $this->emojiArray = array_values($emoji);
    }

    /**
     * Set the dataType of the response which is send when the class is invoked.
     *
     * The dataType defaults to json.
     *
     * @param string $responseDataType json or jsonp (case insensitive).
     * @throws \InvalidArgumentException When the paramter has an invalid value.
     */
    public function setResponseDataType($responseDataType)
    {
        $responseDataType = strtolower($responseDataType);
        if ($responseDataType == 'json' || $responseDataType == 'jsonp') {
            $this->responseDataType = $responseDataType;
        } else {
            throw new InvalidArgumentException('The response dataType in invalid');
        }
    }

}
