<?php
/************************************************************************/                                                         
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
include("grab_globals.php");
include("config.php");
include("lib/multi-langue.php");
include("language/lang-$language.php");
include("cache.class.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
include("lib/mysqli.php");
include("modules/meta-lang/adv-meta_lang.php");

#autodoc Mysql_Connexion() : Connexion plus détaillée ($mysql_p=true => persistente connexion) - Attention : le type de SGBD n'a pas de lien avec le nom de cette fonction
function Mysql_Connexion() {
   global $mysql_error, $dbhost, $dbname;
   $ret_p=sql_connect();
   if (!$ret_p) {
      $Titlesitename="NPDS";
      if (file_exists("meta/meta.php"))
         include ("meta/meta.php");
      if (file_exists("static/database.txt"))
         include ("static/database.txt");
      die();
   }
   return ($ret_p);
}
/****************/
$dblink=Mysql_Connexion();
$mainfile=1;
require_once("auth.inc.php");
if (isset($user)) $cookie=cookiedecode($user);
session_manage();
$tab_langue=make_tab_langue();
global $meta_glossaire;
$meta_glossaire=charg_metalang();
date_default_timezone_set("Europe/Paris");
/****************/

#autodoc file_contents_exist() : Controle de réponse// c'est pas encore assez fin not work with https probably
function file_contents_exist($url, $response_code = 200) {
    $headers = get_headers($url);
    if (substr($headers[0], 9, 3) == $response_code)
        return TRUE;
    else
        return FALSE;
}
#autodoc session_manage() : Mise à jour la table session
function session_manage() {
   global $NPDS_Prefix, $cookie, $REQUEST_URI, $nuke_url;
   $guest=0;
   $ip=getip();
   $username = isset($cookie[1]) ? $cookie[1] : $ip ;
   if($username==$ip)
      $guest=1;
   //==> geoloc
   include("modules/geoloc/geoloc.conf");
   if ($geo_ip == 1)
      include "modules/geoloc/geoloc_refip.php";
   //<== geoloc
   $past = time()-300;
   sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE time < '$past'");

//==> proto en test badbotcontrol ...
   // bad robot limited at x connections ...
   $gulty_robots = array('facebookexternalhit','Amazonbot','ClaudeBot','bingbot','Applebot','AhrefsBot','SemrushBot'); // to be defined in config.php ...
   foreach($gulty_robots as $robot) {
      if(!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], $robot) !== false) {
         $result = sql_query("SELECT agent FROM ".$NPDS_Prefix."session WHERE agent REGEXP '".$robot."'");
         if(sql_num_rows($result)>5) {
            header($_SERVER["SERVER_PROTOCOL"] . ' 429 Too Many Requests');
            echo 'Too Many Requests';
            die;
         }
      }
   }
//<== proto

   $result = sql_query("SELECT time FROM ".$NPDS_Prefix."session WHERE username='$username'");
   if ($row = sql_fetch_assoc($result)) {
      if ($row['time'] < (time()-30)) {
         sql_query("UPDATE ".$NPDS_Prefix."session SET username='$username', time='".time()."', host_addr='$ip', guest='$guest', uri='$REQUEST_URI', agent='".getenv("HTTP_USER_AGENT")."' WHERE username='$username'");
         if ($guest==0) {
            global $gmt;
            sql_query("UPDATE ".$NPDS_Prefix."users SET user_lastvisit='".(time()+(integer)$gmt*3600)."' WHERE uname='$username'");
         }
      }
   } else
      sql_query("INSERT INTO ".$NPDS_Prefix."session (username, time, host_addr, guest, uri, agent) VALUES ('$username', '".time()."', '$ip', '$guest', '$REQUEST_URI', '".getenv("HTTP_USER_AGENT")."')");
}
#autodoc NightDay() : Pour obtenir Nuit ou Jour ... Un grand Merci à P.PECHARD pour cette fonction
function NightDay() {
   global $lever, $coucher;
   $Maintenant = strtotime ("now");
   $Jour = strtotime($lever);
   $Nuit = strtotime($coucher);
   if ($Maintenant-$Jour<0 xor $Maintenant-$Nuit>0) return "Nuit"; else return "Jour";
}
#autodoc removeHack($Xstring) : Permet de rechercher et de remplacer "some bad words" dans une chaine // Preg_replace by Pascalp
function removeHack($Xstring) {
  if ($Xstring!='') {
     $npds_forbidden_words=array(
     // NCRs 2 premières séquence = NCR (dec|hexa) correspondant aux caractères latin de la table ascii (code ascii entre 33 et 126)
     //      2 dernières séquences = NCR (dec|hexa) correspondant aux caractères latin du bloc unicode Halfwidth and Fullwidth Forms.
     //        Leur signification est identique à celle des caractères latin de la table ascii dont le code ascii est entre 33 et 126.
     // JPB for NPDS 2005
     "'&#(33|x21|65281|xFF01);'i"=>chr(33),
     "'&#(34|x22|65282|xFF02);'i"=>chr(34),
     "'&#(35|x23|65283|xFF03);'i"=>chr(35),
     "'&#(36|x24|65284|xFF04);'i"=>chr(36),
     "'&#(37|x25|65285|xFF05);'i"=>chr(37),
     "'&#(38|x26|65286|xFF06);'i"=>chr(38),
     "'&#(39|x27|65287|xFF07);'i"=>chr(39),
     "'&#(40|x28|65288|xFF08);'i"=>chr(40),
     "'&#(41|x29|65289|xFF09);'i"=>chr(41),
     "'&#(42|x2A|65290|xFF0A);'i"=>chr(42),
     "'&#(43|x2B|65291|xFF0B);'i"=>chr(43),
     "'&#(44|x2C|65292|xFF0C);'i"=>chr(44),
     "'&#(45|x2D|65293|xFF0D);'i"=>chr(45),
     "'&#(46|x2E|65294|xFF0E);'i"=>chr(46),
     "'&#(47|x2F|65295|xFF0F);'i"=>chr(47),
     "'&#(48|x30|65296|xFF10);'i"=>chr(48),
     "'&#(49|x31|65297|xFF11);'i"=>chr(49),
     "'&#(50|x32|65298|xFF12);'i"=>chr(50),
     "'&#(51|x33|65299|xFF13);'i"=>chr(51),
     "'&#(52|x34|65300|xFF14);'i"=>chr(52),
     "'&#(53|x35|65301|xFF15);'i"=>chr(53),
     "'&#(54|x36|65302|xFF16);'i"=>chr(54),
     "'&#(55|x37|65303|xFF17);'i"=>chr(55),
     "'&#(56|x38|65304|xFF18);'i"=>chr(56),
     "'&#(57|x39|65305|xFF19);'i"=>chr(57),
     "'&#(58|x3A|65306|xFF1A);'i"=>chr(58),
     "'&#(59|x3B|65307|xFF1B);'i"=>chr(59),
     "'&#(60|x3C|65308|xFF1C);'i"=>chr(60),
     "'&#(61|x3D|65309|xFF1D);'i"=>chr(61),
     "'&#(62|x3E|65310|xFF1E);'i"=>chr(62),
     "'&#(63|x3F|65311|xFF1F);'i"=>chr(63),
     "'&#(64|x40|65312|xFF20);'i"=>chr(64),
     "'&#(65|x41|65313|xFF21);'i"=>chr(65),
     "'&#(66|x42|65314|xFF22);'i"=>chr(66),
     "'&#(67|x43|65315|xFF23);'i"=>chr(67),
     "'&#(68|x44|65316|xFF24);'i"=>chr(68),
     "'&#(69|x45|65317|xFF25);'i"=>chr(69),
     "'&#(70|x46|65318|xFF26);'i"=>chr(70),
     "'&#(71|x47|65319|xFF27);'i"=>chr(71),
     "'&#(72|x48|65320|xFF28);'i"=>chr(72),
     "'&#(73|x49|65321|xFF29);'i"=>chr(73),
     "'&#(74|x4A|65322|xFF2A);'i"=>chr(74),
     "'&#(75|x4B|65323|xFF2B);'i"=>chr(75),
     "'&#(76|x4C|65324|xFF2C);'i"=>chr(76),
     "'&#(77|x4D|65325|xFF2D);'i"=>chr(77),
     "'&#(78|x4E|65326|xFF2E);'i"=>chr(78),
     "'&#(79|x4F|65327|xFF2F);'i"=>chr(79),
     "'&#(80|x50|65328|xFF30);'i"=>chr(80),
     "'&#(81|x51|65329|xFF31);'i"=>chr(81),
     "'&#(82|x52|65330|xFF32);'i"=>chr(82),
     "'&#(83|x53|65331|xFF33);'i"=>chr(83),
     "'&#(84|x54|65332|xFF34);'i"=>chr(84),
     "'&#(85|x55|65333|xFF35);'i"=>chr(85),
     "'&#(86|x56|65334|xFF36);'i"=>chr(86),
     "'&#(87|x57|65335|xFF37);'i"=>chr(87),
     "'&#(88|x58|65336|xFF38);'i"=>chr(88),
     "'&#(89|x59|65337|xFF39);'i"=>chr(89),
     "'&#(90|x5A|65338|xFF3A);'i"=>chr(90),
     "'&#(91|x5B|65339|xFF3B);'i"=>chr(91),
     "'&#(92|x5C|65340|xFF3C);'i"=>chr(92),
     "'&#(93|x5D|65341|xFF3D);'i"=>chr(93),
     "'&#(94|x5E|65342|xFF3E);'i"=>chr(94),
     "'&#(95|x5F|65343|xFF3F);'i"=>chr(95),
     "'&#(96|x60|65344|xFF40);'i"=>chr(96),
     "'&#(97|x61|65345|xFF41);'i"=>chr(97),
     "'&#(98|x62|65346|xFF42);'i"=>chr(98),
     "'&#(99|x63|65347|xFF43);'i"=>chr(99),
     "'&#(100|x64|65348|xFF44);'i"=>chr(100),
     "'&#(101|x65|65349|xFF45);'i"=>chr(101),
     "'&#(102|x66|65350|xFF46);'i"=>chr(102),
     "'&#(103|x67|65351|xFF47);'i"=>chr(103),
     "'&#(104|x68|65352|xFF48);'i"=>chr(104),
     "'&#(105|x69|65353|xFF49);'i"=>chr(105),
     "'&#(106|x6A|65354|xFF4A);'i"=>chr(106),
     "'&#(107|x6B|65355|xFF4B);'i"=>chr(107),
     "'&#(108|x6C|65356|xFF4C);'i"=>chr(108),
     "'&#(109|x6D|65357|xFF4D);'i"=>chr(109),
     "'&#(110|x6E|65358|xFF4E);'i"=>chr(110),
     "'&#(111|x6F|65359|xFF4F);'i"=>chr(111),
     "'&#(112|x70|65360|xFF50);'i"=>chr(112),
     "'&#(113|x71|65361|xFF51);'i"=>chr(113),
     "'&#(114|x72|65362|xFF52);'i"=>chr(114),
     "'&#(115|x73|65363|xFF53);'i"=>chr(115),
     "'&#(116|x74|65364|xFF54);'i"=>chr(116),
     "'&#(117|x75|65365|xFF55);'i"=>chr(117),
     "'&#(118|x76|65366|xFF56);'i"=>chr(118),
     "'&#(119|x77|65367|xFF57);'i"=>chr(119),
     "'&#(120|x78|65368|xFF58);'i"=>chr(120),
     "'&#(121|x79|65369|xFF59);'i"=>chr(121),
     "'&#(122|x7A|65370|xFF5A);'i"=>chr(122),
     "'&#(123|x7B|65371|xFF5B);'i"=>chr(123),
     "'&#(124|x7C|65372|xFF5C);'i"=>chr(124),
     "'&#(125|x7D|65373|xFF5D);'i"=>chr(125),
     "'&#(126|x7E|65374|xFF5E);'i"=>chr(126),
     // Fin des NCRs
     //"'&#'i"=>"&_#", // JPB remplacement pour l'extension chinoise en prenant
     // en compte => http://www.w3.org/TR/2003/NOTE-unicode-xml-20030613/#Suitable
     "'&#(8232|x2028);'i"=>"_",
     "'&#(8233|x2029);'i"=>"_",
     "'&#(8234|x202A);'i"=>"_",
     "'&#(8235|x202B);'i"=>"_",
     "'&#(8236|x202C);'i"=>"_",
     "'&#(8237|x202D);'i"=>"_",
     "'&#(8238|x202E);'i"=>"_",
     "'&#(8298|x206A);'i"=>"_",
     "'&#(8299|x206B);'i"=>"_",
     "'&#(8300|x206C);'i"=>"_",
     "'&#(8301|x206D);'i"=>"_",
     "'&#(8302|x206E);'i"=>"_",
     "'&#(8303|x206F);'i"=>"_",
     "'&#(65529|xFFF9);'i"=>"_",
     "'&#(65530|xFFFA);'i"=>"_",
     "'&#(65531|xFFFB);'i"=>"_",
     "'&#(65532|xFFFC);'i"=>"_",
     "'&#(65279|xFEFF);'i"=>"&#x2060;",
     "'&#(119155|x1D173);'i"=>"_",
     "'&#(119156|x1D174);'i"=>"_",
     "'&#(119157|x1D175);'i"=>"_",
     "'&#(119158|x1D176);'i"=>"_",
     "'&#(119159|x1D177);'i"=>"_",
     "'&#(119160|x1D178);'i"=>"_",
     "'&#(119161|x1D179);'i"=>"_",
     "'&#(119162|x1D17A);'i"=>"_",
     "'&#xE000(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE001(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE002(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE003(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE004(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE005(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE006(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE007(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#91750(4|5|6|7|8|9);'i"=>"_",
     "'&#91751(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91752(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91753(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91754(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91755(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91756(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91757(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91758(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91759(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91760(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91761(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91762(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91763(0|1|);'i"=>"_",
     // Fin
     "'from:'i"=>"!from:!",
     "'subject:'i"=>"!subject:!",
     "'bcc:'i"=>"!bcc:!",
     "'mime-version:'i"=>"!mime-version:!",
     "'base64'i"=>"base_64",
     "'content-type:'i"=>"!content-type:!",
     "'content-transfer-encoding:'i"=>"!content-transfer-encoding:!",
     "'content-disposition:'i"=>"!content-disposition:!",
     "'content-location:'i"=>"!content-location:!",
     "'include'i"=>"!include!",
     "'<script'i"=>"&lt;script",
     "'</script'i"=>"&lt;/script",
     "'javascript'i"=>"!javascript!",
     "'embed\s'i"=>"!embed!",
     "'iframe\s'i"=>"!iframe!",
     "'\srefresh\s'i"=>"!refresh!",
     "'document\.cookie'i"=>"!document.cookie!",
     "'onload'i"=>"!onload!",
     "'onstart'i"=>"!onstart!",
     "'onerror'i"=>"!onerror!",
     "'onkey'i"=>"!onkey!",
     "'onmouse'i"=>"!onmouse!",
     "'onclick'i"=>"!onclick!",
     "'ondblclick'i"=>"!ondblclick!",
     "'onhelp'i"=>"!onhelp!",
     "'onmousedown'i"=>"!onmousedown!",
     "'onmousemove'i"=>"!onmousemove!",
     "'onmouseout'i"=>"!onmouseout!",
     "'onmouseover'i"=>"!onmouseover!",
     "'onmouseup'i"=>"!onmouseup!",
     "'onblur'i"=>"!onblur!",
     "'onafterupdate'i"=>"!onafterupdate!",
     "'onbeforeupdate'i"=>"!onbeforeupdate!",
     "'onkeydown'i"=>"!onkeydown!",
     "'onkeypress'i"=>"!onkeypress!",
     "'onkeyup'i"=>"!onkeyup!",
     "'onfocus'i"=>"!onfocus!",
     "'onunload'i"=>"!onunload!",
     "'jscript'i"=>"!jscript!",
     "'vbscript'i"=>"!vbscript!",
     "'pearlscript'i"=>"!pearlscript!",
     "'&#(8216|x2018);'i"=>chr(39),
     "'&#(8217|x2019);'i"=>chr(39),
     "'&#39;'i"=>'\\\'',
     "'&#(8220|x201C);'i"=>chr(34),
     "'&#(8221|x201D);'i"=>chr(34),
     "'&#160;'i"=>'&nbsp;',
     "'<style'i"=>"&lt;style",
     "'<body'i"=>"&lt;body",
     "'<object'i"=>"&lt;object",
     "'\<\?php'i"=>"&lt;?php",
     "'\<\?'i"=>"&lt;?",
     "'\?\>'i"=>"?&gt;",
     "'\<\%'i"=>"&lt;%",
     "'\%\>'i"=>"%&gt;",
     "'url\('i"=>"!url(!",
     "'expression\('i"=>"!expression(!");
     $Xstring=preg_replace(array_keys($npds_forbidden_words),array_values($npds_forbidden_words), $Xstring);
  }
  return($Xstring);
}
#autodoc send_email($email, $subject, $message, $from, $priority, $mime, $file) : Pour envoyer un mail en texte ou html avec ou sans pieces jointes  / $mime = 'text', 'html' 'html-nobr'-(sans application de nl2br) ou 'mixed'-(avec piece(s) jointe(s) : génération ou non d'un DKIM suivant option choisie) 
function send_email($email, $subject, $message, $from = "", $priority = false, $mime = "text", $file = null) { 
   global $mail_fonction, $adminmail, $sitename, $NPDS_Key, $nuke_url; 

   $From_email = $from != '' ? $from : $adminmail; 

   if (preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $From_email)) { 
      include 'lib/PHPMailer/PHPmailer.conf.php'; 
      if ($dkim_auto == 2) {
         //Private key filename for this selector 
         $privatekeyfile = 'lib/PHPMailer/key/' . $NPDS_Key . '_dkim_private.pem'; 
         //Public key filename for this selector 
         $publickeyfile = 'lib/PHPMailer/key/' . $NPDS_Key . '_dkim_public.pem'; 
         if (!file_exists($privatekeyfile)) { 
            //Create a 2048-bit RSA key with an SHA256 digest 
            $pk = openssl_pkey_new( 
               [ 
                  'digest_alg' => 'sha256', 
                  'private_key_bits' => 2048, 
                  'private_key_type' => OPENSSL_KEYTYPE_RSA, 
               ] 
            ); 
            //Save private key 
            openssl_pkey_export_to_file($pk, $privatekeyfile); 
            //Save public key 
            $pubKey = openssl_pkey_get_details($pk); 
            $publickey = $pubKey['key']; 
            file_put_contents($publickeyfile, $publickey); 
         }
      } 
      $debug = false; 
      $mail = new PHPMailer($debug); 
      try { 
         //Server settings config smtp 
         if ($mail_fonction == 2) { 
            $mail->isSMTP(); 
            $mail->Host       = $smtp_host; 
            $mail->SMTPAuth   = $smtp_auth; 
            $mail->Username   = $smtp_username; 
            $mail->Password   = $smtp_password; 
            if ($smtp_secure) { 
               if ($smtp_crypt === 'tls') 
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
               elseif ($smtp_crypt === 'ssl') 
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            } 
            $mail->Port       = $smtp_port; 
         } 
         $mail->CharSet = 'UTF-8'; 
         $mail->Encoding = 'base64'; 
         if ($priority)
            $mail->Priority = 2; 
         //Recipients 
         $mail->setFrom($adminmail, $sitename); 
         $mail->addAddress($email, $email); 
         //Content 
         if ($mime == 'mixed') { 
            $mail->isHTML(true); 
            // pièce(s) jointe(s)) 
            if (!is_null($file)) { 
               if (is_array($file))
                  $mail->addAttachment($file['file'], $file['name']); 
               else
                  $mail->addAttachment($file); 
            } 
         } 
         if (($mime == 'html') or ($mime == 'html-nobr')) { 
            $mail->isHTML(true); 
            if ($mime != 'html-nobr') 
               $message = nl2br($message); 
         } 
         $mail->Subject = $subject; 
         $stub_mail = "<html>\n<head>\n<style type='text/css'>\nbody {\nbackground: #FFFFFF;\nfont-family: Tahoma, Calibri, Arial;\nfont-size: 1 rem;\ncolor: #000000;\n}\na, a:visited, a:link, a:hover {\ntext-decoration: underline;\n}\n</style>\n</head>\n<body>\n %s \n</body>\n</html>"; 
         if ($mime == 'text'){
            $mail->isHTML(false);
            $mail->Body = $message;
         } else
            $mail->Body = sprintf($stub_mail, $message);
         if ($dkim_auto == 2) { 
            $mail->DKIM_domain = str_replace(['http://', 'https://'], ['', ''], $nuke_url); 
            $mail->DKIM_private = $privatekeyfile;; 
            $mail->DKIM_selector = $NPDS_Key; 
            $mail->DKIM_identity = $mail->From;
         } 
         if ($mail_fonction == 2) { 
            if ($debug) { 
               // on génère un journal détaillé après l'envoi du mail 
               $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
            }
         }
         $mail->send(); 

         if ($debug) { 
            // stop l'exécution du script pour affichage du journal sur la page 
            die(); 
         }
         $result = true; 
      } catch (Exception $e) { 
         Ecr_Log('smtpmail', "send Smtp mail by $email", "Message could not be sent. Mailer Error: $mail->ErrorInfo"); 
         $result = false; 
      }
   }

   return $result ? true : false; 
} 
#autodoc copy_to_email($to_userid,$sujet,$message) : Pour copier un subject+message dans un email ($to_userid)
function copy_to_email($to_userid,$sujet,$message) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT email,send_email FROM ".$NPDS_Prefix."users WHERE uid='$to_userid'");
   list($mail,$avertir_mail) = sql_fetch_row($result);
   if (($mail) and ($avertir_mail==1)) {
      send_email($mail,$sujet,$message, '', true, 'html', '');
   }
}
#autodoc Ecr_Log($fic_log, $req_log, $mot_log) : Pour &eacute;crire dans un log (security.log par exemple)
function Ecr_Log($fic_log, $req_log, $mot_log) {
   // $Fic_log= the file name :
   //  => "security" for security maters
   //  => ""
   // $req_log= a phrase describe the infos
   //
   // $mot_log= if "" the Ip is recorded, else extend status infos
   $logfile = "slogs/$fic_log.log";
   $fp = fopen($logfile, 'a');
   flock($fp, 2);
   fseek($fp, filesize($logfile));
   if ($mot_log=="") $mot_log="IP=>".getip();
   $ibid = sprintf("%-10s %-60s %-10s\r\n",date("d/m/Y H:i:s",time()),basename($_SERVER['PHP_SELF'])."=>".strip_tags(urldecode($req_log)),strip_tags(urldecode($mot_log)));//pourquoi urldecode ici ?
   fwrite($fp, $ibid);
   flock($fp, 3);
   fclose($fp);
}
#autodoc redirect_url($urlx) : Permet une redirection javascript / en lieu et place de header("location: ...");
function redirect_url($urlx) {
   echo "<script type=\"text/javascript\">\n";
   echo "//<![CDATA[\n";
   echo "document.location.href='".$urlx."';\n";
   echo "//]]>\n";
   echo "</script>";
}
#autodoc SC_infos() : Indique le status de SuperCache
function SC_infos() {
   global $SuperCache, $npds_sc;
   $infos='';
   if ($SuperCache) {
/*
      $infos = $npds_sc ? '<span class="small">'.translate(".:Page >> Super-Cache:.").'</span>':'';
*/
      if ($npds_sc) {
         $infos='<span class="small">'.translate(".:Page >> Super-Cache:.").'</span>';
      } else {
         $infos='<span class="small">'.translate(".:Page >> Super-Cache:.").'</span>';
      }
   }
   return $infos;
}
#autodoc req_stat() : Retourne un tableau contenant les nombres pour les statistiques du site (stats.php)
function req_stat() {
   global $NPDS_Prefix;
   // Les membres
   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users");
   $xtab[0] = $result ? (sql_num_rows($result)-1) : '0' ;
   if ($result) sql_free_result($result);
   // Les Nouvelles (News)
   $result = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories");
   $xtab[1] = $result ? sql_num_rows($result) : '0' ;
   if ($result) sql_free_result($result);
   // Les Critiques (Reviews))
   $result = sql_query("SELECT id FROM ".$NPDS_Prefix."reviews");
   $xtab[2] = $result ? sql_num_rows($result) : '0' ;
   if ($result) sql_free_result($result);
   // Les Forums
   $result = sql_query("SELECT forum_id FROM ".$NPDS_Prefix."forums");
   $xtab[3] = $result ? sql_num_rows($result) : '0' ;
   if ($result) sql_free_result($result);
   // Les Sujets (topics)
   $result = sql_query("SELECT topicid FROM ".$NPDS_Prefix."topics");
   $xtab[4] = $result ? sql_num_rows($result) : '0' ;
   if ($result) sql_free_result($result);
   // Nombre de pages vues
   $result = sql_query("SELECT count FROM ".$NPDS_Prefix."counter WHERE type='total'");
   list($totalz)=sql_fetch_row($result);
   $xtab[5]=$totalz++;
   sql_free_result($result);
   return($xtab);
}
#autodoc Mess_Check_Mail($username) : Appel la fonction d'affichage du groupe check_mail (theme principal de NPDS) sans class
function Mess_Check_Mail($username) {
   Mess_Check_Mail_interface($username, '');
}
#autodoc Mess_Check_Mail_interface($username, $class) : Affiche le groupe check_mail (theme principal de NPDS)
function Mess_Check_Mail_interface($username, $class) {
   global $anonymous;
   if ($ibid=theme_image("fle_b.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;}
   if ($class!="") $class="class=\"$class\"";
   if ($username==$anonymous) {
      if ($imgtmp) {
         echo "<img alt=\"\" src=\"$imgtmp\" align=\"center\" />$username - <a href=\"user.php\" $class>".translate("Votre compte")."</a>";
      } else {
         echo "[$username - <a href=\"user.php\" $class>".translate("Votre compte")."</a>]";
      }
   } else {
      if ($imgtmp) {
         echo "<a href=\"user.php\" $class><img alt=\"\" src=\"$imgtmp\" align=\"center\" />".translate("Votre compte")."</a>&nbsp;".Mess_Check_Mail_Sub($username,$class);
      } else {
         echo "[<a href=\"user.php\" $class>".translate("Votre compte")."</a>&nbsp;&middot;&nbsp;".Mess_Check_Mail_Sub($username,$class)."]";
      }
   }
}
#autodoc Mess_Check_Mail_Sub($username, $class) : Affiche le groupe check_mail (theme principal de NPDS) / SOUS-Fonction
function Mess_Check_Mail_Sub($username, $class) {
   global $NPDS_Prefix, $user;
   if ($username) {
      $userdata = explode(':', base64_decode($user));
      $total_messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userdata[0]' AND type_msg='0'"));
      $new_messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userdata[0]' AND read_msg='0' AND type_msg='0'"));
      if ($total_messages > 0) {
         if ($new_messages > 0) {
            $Xcheck_Nmail=$new_messages;
         } else {
            $Xcheck_Nmail='0';
         }
         $Xcheck_mail=$total_messages;
      } else {
         $Xcheck_Nmail='0';
         $Xcheck_mail='0';
      }
   }
   $YNmail="$Xcheck_Nmail";
   $Ymail="$Xcheck_mail";
   $Mel="<a href=\"viewpmsg.php\" $class>Mel</a>";
   if ($Xcheck_Nmail >0) {
      $YNmail="<a href=\"viewpmsg.php\" $class>$Xcheck_Nmail</a>";
      $Mel='Mel';
   }
   if ($Xcheck_mail >0) {
      $Ymail="<a href=\"viewpmsg.php\" $class>$Xcheck_mail</a>";
      $Mel='Mel';
   }
   return ("$Mel : $YNmail / $Ymail");
}
#autodoc Who_Online() : Qui est en ligne ? + message de bienvenue
function Who_Online() {
   list($content1, $content2)=Who_Online_Sub();
   return array($content1, $content2);
}
#autodoc Who_Online() : Qui est en ligne ? + message de bienvenue / SOUS-Fonction / Utilise Site_Load
function Who_Online_Sub() {
   global $user, $cookie;
   list($member_online_num, $guest_online_num)=site_load();
   $content1 = "$guest_online_num ".translate("visiteur(s) et")." $member_online_num ".translate("membre(s) en ligne.");
   if ($user) {
      $content2 = translate("Vous êtes connecté en tant que")." <b>".$cookie[1]."</b>";
   } else {
      $content2 = translate("Devenez membre privilégié en cliquant")." <a href=\"user.php?op=only_newuser\">".translate("ici")."</a>";
   }
   return array($content1, $content2);
}
#autodoc Site_Load() : Maintient les informations de NB connexion (membre, anonyme) - globalise la variable $who_online_num et maintient le fichier cache/site_load.log &agrave; jour<br />Indispensable pour la gestion de la 'clean_limit' de SuperCache
function Site_Load() {
   global $NPDS_Prefix, $SuperCache, $who_online_num;
   $guest_online_num = 0;
   $member_online_num = 0;
   $result = sql_query("SELECT COUNT(username) AS TheCount, guest FROM ".$NPDS_Prefix."session GROUP BY guest");
   while ($TheResult = sql_fetch_assoc($result)) {
      if ($TheResult['guest']==0)
         $member_online_num = $TheResult['TheCount'];
      else
         $guest_online_num = $TheResult['TheCount'];
   }
   $who_online_num = $guest_online_num + $member_online_num;
   if ($SuperCache) {
      $file=fopen("cache/site_load.log", "w");
         fwrite($file, $who_online_num);
      fclose($file);
   }
   return array($member_online_num, $guest_online_num);
}
#autodoc AutoReg() : Si AutoRegUser=true et que le user ne dispose pas du droit de connexion : RAZ du cookie NPDS<br />retourne False ou True
function AutoReg() {
   global $NPDS_Prefix, $AutoRegUser, $user;
   if (!$AutoRegUser) {
      if (isset($user)) {
         $cookie = explode(':', base64_decode($user));
         list($test) = sql_fetch_row(sql_query("SELECT open FROM ".$NPDS_Prefix."users_status WHERE uid='$cookie[0]'"));
         if (!$test) {
            setcookie('user','',0);
            return false;
         } else {
            return true;
         }
      } else {
         return true;
      }
   } else {
      return true;
   }
}
#autodoc secur_static($sec_type) : Pour savoir si le visiteur est un : membre ou admin (static.php et banners.php par exemple)
function secur_static($sec_type) {
   global $user, $admin;
   switch ($sec_type) {
      case 'member':
         return isset($user) ;
      break;
      case 'admin':
         return isset($admin) ;
      break;
   }
}
#autodoc ultramode() : Génération des fichiers ultramode.txt et net2zone.txt dans /cache
function ultramode() {
   global $NPDS_Prefix, $nuke_url, $storyhome;
   $ultra = "cache/ultramode.txt";
   $netTOzone = "cache/net2zone.txt";
   $file = fopen("$ultra", "w");
   $file2 = fopen("$netTOzone", "w");
   fwrite($file, "General purpose self-explanatory file with news headlines\n");
   $storynum = $storyhome;
   $xtab=news_aff('index',"WHERE ihome='0' AND archive='0'",$storyhome,'');
   $story_limit=0;
   while (($story_limit<$storynum) and ($story_limit<sizeof($xtab))) {
      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
      $story_limit++;
      $rfile2=sql_query("SELECT topictext, topicimage FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
      list($topictext, $topicimage) = sql_fetch_row($rfile2);
      $hometext=meta_lang(strip_tags($hometext));
      fwrite($file, "%%\n$title\n$nuke_url/article.php?sid=$sid\n$time\n$aid\n$topictext\n$hometext\n$topicimage\n");
      fwrite($file2, "<NEWS>\n<NBX>$topictext</NBX>\n<TITLE>".stripslashes($title)."</TITLE>\n<SUMMARY>$hometext</SUMMARY>\n<URL>$nuke_url/article.php?sid=$sid</URL>\n<AUTHOR>".$aid."</AUTHOR>\n</NEWS>\n\n");
   }
   fclose($file);
   fclose($file2);
}
#autodoc cookiedecode($user) : Décode le cookie membre et vérifie certaines choses (password)
function cookiedecode($user) {
   global $NPDS_Prefix, $language;
   $stop=false;

   if (array_key_exists("user",$_GET)) {
      if ($_GET['user']!='') { $stop=true; $user="BAD-GET";}
   } else if (isset($HTTP_GET_VARS)) {
      if (array_key_exists("user",$HTTP_GET_VARS) and ($HTTP_GET_VAR['user']!='')) { $stop=true; $user="BAD-GET";}
   }
   if ($user) {
      $cookie = explode(':', base64_decode($user));
      settype($cookie[0],"integer");
      if (trim($cookie[1])!='') {
         $result = sql_query("SELECT pass, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
         if (sql_num_rows($result)==1) {
            list($pass, $user_langue) = sql_fetch_row($result);
            if (($cookie[2] == md5($pass)) AND ($pass != '')) {
               if ($language!=$user_langue) {
                  sql_query("UPDATE ".$NPDS_Prefix."users SET user_langue='$language' WHERE uname='$cookie[1]'");
               }
               return $cookie;
            } else {
               $stop=true;
            }
         } else {
            $stop=true;
         }
      } else {
         $stop=true;
      }
      if ($stop) {
         setcookie('user','',0);
         unset($user);
         unset($cookie);
         header("Location: index.php");
      }
   }
}
#autodoc getusrinfo($user) : Renvoi le contenu de la table users pour le user uname
function getusrinfo($user) {
   global $NPDS_Prefix;
   $cookie = explode(':', base64_decode($user));
   $result = sql_query("SELECT pass FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
   list($pass) = sql_fetch_row($result);
   $userinfo='';
   if (($cookie[2] == md5($pass)) AND ($pass != '')) {
      $result = sql_query("SELECT uid, name, uname, email, femail, url, user_avatar, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax, user_journal, send_email, is_visible, mns, user_lnl FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      if (sql_num_rows($result)==1) {
         $userinfo = sql_fetch_assoc($result);
      } else {
         echo '<strong>'.translate("Un problème est survenu").'.</strong>';
      }
   }
   return $userinfo;
}
#autodoc FixQuotes($what) : Quote une chaîne contenant des '
function FixQuotes($what = '') {
   $what = str_replace("&#39;","'",$what);
   $what = str_replace("'","''",$what);
   while (preg_match("#\\\\'#", $what)) {
      $what=preg_replace("#\\\\'#", "'", $what);
   }
   return $what;
}
#autodoc formatTimes($time) : Formate un timestamp ou une chaine de date formatée correspondant à l'argument obligatoire $time - le décalage $gmt défini dans les préférences n'est pas appliqué
function formatTimes($time, $dateStyle = IntlDateFormatter::SHORT, $timeStyle = IntlDateFormatter::NONE, $timezone = 'Europe/Paris') {
   $locale = language_iso(1, '_', 1); // Utilise la langue de l'affichage du site
   $fmt = datefmt_create($locale, $dateStyle, $timeStyle, $timezone, IntlDateFormatter::GREGORIAN);
   $timestamp = is_numeric($time) ? $time : strtotime($time);
   $date_au_format = ucfirst(htmlentities($fmt->format($timestamp), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8'));
   return $date_au_format;
}
#autodoc getPartOfTime($time) : découpe/extrait/formate et plus grâce au paramètre $format.... un timestamp ou une chaine de date formatée correspondant à l'argument obligatoire $time -
function getPartOfTime($time, $format, $timezone = 'Europe/Paris') {
   $locale = language_iso(1, '_', 1);
   $timestamp = is_numeric($time) ? $time : strtotime($time);
   $fmt = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone, IntlDateFormatter::GREGORIAN, $format);
   $date_au_format = $fmt->format($timestamp);
   return ucfirst(htmlentities($date_au_format, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8'));
}
#autodoc formatAidHeader($aid) : Affiche URL et Email d'un auteur
function formatAidHeader($aid) {
   global $NPDS_Prefix;
   $holder = sql_query("SELECT url, email FROM ".$NPDS_Prefix."authors WHERE aid='$aid'");
   if ($holder) {
      list($url, $email) = sql_fetch_row($holder);
      if (isset($url)) {
         echo '<a href="'.$url.'" >'.$aid.'</a>';
      } elseif (isset($email)) {
         echo '<a href="mailto:'.$email.'" >'.$aid.'</a>';
      } else {
         echo $aid;
      }
   }
}
#autodoc ctrl_aff($ihome, $catid) : Gestion + fine des destinataires (-1, 0, 1, 2 -> 127, -127)
function ctrl_aff($ihome, $catid=0) {
   global $user;
   $affich=false;
   if ($ihome==-1 and (!$user))
      $affich=true;
   elseif ($ihome==0)
      $affich=true;
   elseif ($ihome==1)
      $affich = $catid>0 ? false : true ;
   elseif (($ihome>1) and ($ihome<=127)) {
      $tab_groupe=valid_group($user);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue==$ihome) {
               $affich=true;
               break;
            }
         }
      }
   } else
      if ($user) $affich=true;
   return ($affich);
}
#autodoc news_aff($type_req, $sel, $storynum, $oldnum) : Une des fonctions fondamentales de NPDS / assure la gestion de la selection des News en fonctions des critères de publication
function news_aff($type_req, $sel, $storynum, $oldnum) { // pas stabilisé ...!
   global $NPDS_Prefix;
   // Astuce pour afficher le nb de News correct même si certaines News ne sont pas visibles (membres, groupe de membres)
   // En fait on * le Nb de News par le Nb de groupes
   $row_Q2 = Q_select("SELECT COUNT(groupe_id) AS total FROM ".$NPDS_Prefix."groupes",86400);
   $NumG=$row_Q2[0];

   if ($NumG['total']<2) $coef=2; else $coef=$NumG['total'];
   settype($storynum,"integer");
   if ($type_req=='index') {
      $Xstorynum=$storynum*$coef;
      $result = Q_select("SELECT sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel ORDER BY sid DESC LIMIT $Xstorynum",3600);
      $Znum=$storynum;
   }
   if ($type_req=='old_news') {
//      $Xstorynum=$oldnum*$coef;
      $result = Q_select("SELECT sid, catid, ihome, time FROM ".$NPDS_Prefix."stories $sel ORDER BY time DESC LIMIT $storynum",3600);
      $Znum=$oldnum;
   }
   if (($type_req=='big_story') or ($type_req=='big_topic')) {
//      $Xstorynum=$oldnum*$coef;
      $result = Q_select("SELECT sid, catid, ihome, counter FROM ".$NPDS_Prefix."stories $sel ORDER BY counter DESC LIMIT $storynum",0);
      $Znum=$oldnum;
   }
   if ($type_req=='libre') {
      $Xstorynum=$oldnum*$coef; //need for what ?
      $result=Q_select("SELECT sid, catid, ihome, time FROM ".$NPDS_Prefix."stories $sel",3600);
      $Znum=$oldnum;
   }
   if ($type_req=='archive') {
      $Xstorynum=$oldnum*$coef; //need for what ?
      $result=Q_select("SELECT sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel",3600);
      $Znum=$oldnum;
   }
   $ibid=0; settype($tab,'array');

  foreach($result as $myrow) {
      $s_sid=$myrow['sid'];
      $catid=$myrow['catid'];
      $ihome=$myrow['ihome'];
      if(array_key_exists('time', $myrow))
         $time=$myrow['time'];
      
      if ($ibid==$Znum) {break;}
      if ($type_req=="libre") $catid=0;
      if ($type_req=="archive") $ihome=0;
      if (ctrl_aff($ihome, $catid)) {
         if (($type_req=="index") or ($type_req=="libre"))
            $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM ".$NPDS_Prefix."stories WHERE sid='$s_sid' AND archive='0'");
         if ($type_req=="archive")
            $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM ".$NPDS_Prefix."stories WHERE sid='$s_sid' AND archive='1'");
         if ($type_req=="old_news")
            $result2 = sql_query("SELECT sid, title, time, comments, counter FROM ".$NPDS_Prefix."stories WHERE sid='$s_sid' AND archive='0'");
         if (($type_req=="big_story") or ($type_req=="big_topic"))
            $result2 = sql_query("SELECT sid, title FROM ".$NPDS_Prefix."stories WHERE sid='$s_sid' AND archive='0'");

         $tab[$ibid]=sql_fetch_row($result2);
         if (is_array($tab[$ibid]))
            $ibid++;
         sql_free_result($result2);
      }
   }
   @sql_free_result($result);
   return ($tab);
}
#autodoc themepreview($title, $hometext, $bodytext, $notes) : Permet de prévisualiser la présentation d'un NEW
function themepreview($title, $hometext, $bodytext='', $notes='') {
   echo "$title<br />".meta_lang($hometext)."<br />".meta_lang($bodytext)."<br />".meta_lang($notes);
}
#autodoc prepa_aff_news($op,$catid) : Prépare, serialize et stock dans un tableau les news répondant aux critères<br />$op="" ET $catid="" : les news // $op="categories" ET $catid="catid" : les news de la catégorie catid //  $op="article" ET $catid=ID_X : l'article d'ID X // Les news des sujets : $op="topics" ET $catid="topic"
function prepa_aff_news($op,$catid,$marqeur) {
   global $NPDS_Prefix, $storyhome, $topicname, $topicimage, $topictext, $datetime, $cookie;
   if (isset($cookie[3]))
       $storynum = $cookie[3];
   else
       $storynum = $storyhome;
   if ($op=="categories") {
      sql_query("UPDATE ".$NPDS_Prefix."stories_cat SET counter=counter+1 WHERE catid='$catid'");
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","WHERE catid='$catid' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="topics") {
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","WHERE topic='$catid' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="news") {
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","WHERE ihome!='1' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="article") {
      $xtab=news_aff("index","WHERE ihome!='1' AND sid='$catid'",1,"");
   } else {
      $xtab=news_aff("index","WHERE ihome!='1' AND archive='0'",$storynum,"");
   }
   $story_limit=0;
   while (($story_limit<$storynum) and ($story_limit<sizeof($xtab))) {
      list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
      $story_limit++;
      $printP = '<a href="print.php?sid='.$s_sid.'" class="me-3" title="'.translate("Page spéciale pour impression").'" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-print"></i></a>&nbsp;';
      $sendF = '<a href="friend.php?op=FriendSend&amp;sid='.$s_sid.'" class="me-3" title="'.translate("Envoyer cet article à un ami").'" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-at"></i></a>';
      getTopics($s_sid);
      $title = aff_langue(stripslashes($title));
      $hometext = aff_langue(stripslashes($hometext));
      $notes = aff_langue(stripslashes($notes));
      $bodycount = strlen(strip_tags(aff_langue($bodytext),'<img>'));
      if ($bodycount > 0) {
         $bodycount = strlen(strip_tags(aff_langue($bodytext)));
         if ($bodycount > 0 )
            $morelink[0]=wrh($bodycount).' '.translate("caractères de plus");
         else
            $morelink[0]=' ';
         $morelink[1]=' <a href="article.php?sid='.$s_sid.'" >'.translate("Lire la suite...").'</a>';
      } else {
         $morelink[0]='';
         $morelink[1]='';
      }
      if ($comments==0) {
         $morelink[2]=0;
         $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="me-3"><i class="far fa-comment fa-lg" title="'.translate("Commentaires ?").'" data-bs-toggle="tooltip"></i></a>';
       } elseif ($comments==1) {
         $morelink[2]=$comments;
         $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="me-3"><i class="far fa-comment fa-lg" title="'.translate("Commentaire").'" data-bs-toggle="tooltip"></i></a>';
       } else {
         $morelink[2]=$comments;
         $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="me-3" ><i class="far fa-comment fa-lg" title="'.translate("Commentaires").'" data-bs-toggle="tooltip"></i></a>';
       }
       $morelink[4]=$printP;
       $morelink[5]=$sendF;
       $sid = $s_sid;
         if ($catid != 0) {
          $resultm = sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'");
          list($title1) = sql_fetch_row($resultm);
         $title= $title;
          // Attention à cela aussi
          $morelink[6]=' <a href="index.php?op=newcategory&amp;catid='.$catid.'">&#x200b;'.aff_langue($title1).'</a>';
       } else
          $morelink[6]='';
       $news_tab[$story_limit]['aid']=serialize($aid);
       $news_tab[$story_limit]['informant']=serialize($informant);
       $news_tab[$story_limit]['datetime']=serialize($time);
       $news_tab[$story_limit]['title']=serialize($title);
       $news_tab[$story_limit]['counter']=serialize($counter);
       $news_tab[$story_limit]['topic']=serialize($topic);
       $news_tab[$story_limit]['hometext']=serialize(meta_lang(aff_code($hometext)));
       $news_tab[$story_limit]['notes']=serialize(meta_lang(aff_code($notes)));
       $news_tab[$story_limit]['morelink']=serialize($morelink);
       $news_tab[$story_limit]['topicname']=serialize($topicname);
       $news_tab[$story_limit]['topicimage']=serialize($topicimage);
       $news_tab[$story_limit]['topictext']=serialize($topictext);
       $news_tab[$story_limit]['id']=serialize($s_sid);
   } if (isset($news_tab))
   return($news_tab);
}
#autodoc valid_group($xuser) : Retourne un tableau contenant la liste des groupes d'appartenance d'un membre
function valid_group($xuser) {
   global $NPDS_Prefix;
   if ($xuser) {
      $userdata = explode(':',base64_decode($xuser));
      $user_temp=Q_select("SELECT groupe FROM ".$NPDS_Prefix."users_status WHERE uid='$userdata[0]'",3600);
      $groupe=$user_temp[0];
      $tab_groupe=explode(',',$groupe['groupe']);
   } else
      $tab_groupe='';
   return ($tab_groupe);
}
#autodoc liste_group() : Retourne une liste des groupes disponibles dans un tableau
function liste_group() {
   global $NPDS_Prefix;
   $r = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_id ASC");
   $tmp_groupe[0]='-> '.adm_translate("Supprimer").'/'.adm_translate("Choisir un groupe").' <-';
   while($mX = sql_fetch_assoc($r)) {
      $tmp_groupe[$mX['groupe_id']]=aff_langue($mX['groupe_name']);
   }
   sql_free_result($r);
   return ($tmp_groupe);
}
#autodoc groupe_forum($forum_groupeX, $tab_groupeX) : Retourne true ou false en fonction de l'autorisation d'un membre sur 1 (ou x) forum de type groupe
function groupe_forum($forum_groupeX, $tab_groupeX) {
   $ok_affich=groupe_autorisation($forum_groupeX, $tab_groupeX);
   return ($ok_affich);
}
#autodoc groupe_autorisation($groupeX, $tab_groupeX) : Retourne true ou false en fonction de l'autorisation d'un membre sur 1 (ou x) groupe
function groupe_autorisation($groupeX, $tab_groupeX) {
   $tab_groupe=explode(',',$groupeX);
   $ok=false;
   if ($tab_groupeX) {
      foreach($tab_groupe as $groupe) {
         foreach($tab_groupeX as $groupevalue) {
            if ($groupe==$groupevalue) {
               $ok=true;
               break;
            }
         }
         if ($ok) break;
      }
   }
   return ($ok);
}
#autodoc block_fonction($title, $contentX) : Assure la gestion des include# et function# des blocs de NPDS / le titre du bloc est exporté (global) )dans $block_title
function block_fonction($title, $contentX) {
   global $block_title;
   $block_title=$title;
   //For including PHP functions in block
   if (stristr($contentX,"function#")) {
      $contentX=str_replace('<br />','',$contentX);
      $contentX=str_replace('<BR />','',$contentX);
      $contentX=str_replace('<BR>','',$contentX);
      $contentY=trim(substr($contentX,9));
      if (stristr($contentY,"params#")) {
         $pos = strpos($contentY,"params#");
         $contentII=trim(substr($contentY,0,$pos));
         $params=substr($contentY,$pos+7);
         $prm=explode(',',$params);
         // Remplace le param "False" par la valeur false (idem pour True)
         for ($i=0; $i<=count($prm)-1; $i++) {
            if ($prm[$i]=="false") {$prm[$i]=false;}
            if ($prm[$i]=="true") {$prm[$i]=true;}
         }
         // En fonction du nombre de params de la fonction : limite actuelle : 8
         if (function_exists($contentII)) {
            switch(count($prm)) {
               case 1:
                  $contentII($prm[0]); break;
               case 2:
                  $contentII($prm[0],$prm[1]); break;
               case 3:
                  $contentII($prm[0],$prm[1],$prm[2]); break;
               case 4:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3]); break;
               case 5:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4]); break;
               case 6:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5]); break;
               case 7:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5],$prm[6]); break;
               case 8:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5],$prm[6],$prm[7]); break;
            }
            return (true);
         } else {
            return (false);
         }
      } else {
         if (function_exists($contentY)) {
            $contentY();
            return (true);
         } else {
            return (false);
         }
      }
   } else {
      return (false);
   }
}
#autodoc fab_block($title, $member, $content, $Xcache) : Assure la fabrication réelle et le Cache d'un bloc
function fab_block($title, $member, $content, $Xcache) {
   global $SuperCache, $CACHE_TIMINGS;
   // Multi-Langue
   $title=aff_langue($title);
   // Bloc caché
   $hidden=false;
   if (substr($content,0,7)=="hidden#") {
      $content=str_replace("hidden#",'',$content);
      $hidden=true;
   }
   // Si on cherche à charger un JS qui a déjà été chargé par pages.php alors on ne le charge pas ...
   global $pages_js;
   if ($pages_js!='') {
      preg_match('#src="([^"]*)#',$content,$jssrc);
      if (is_array($pages_js)) {
         foreach($pages_js as $jsvalue) {
            if (array_key_exists('1',$jssrc)) {
               if ($jsvalue==$jssrc[1]) {
                  $content='';
                  break;
               }
            }
         }
      } else {
         if (array_key_exists('1',$jssrc)) {
            if ($pages_js==$jssrc[1]) $content="";
         }
      }
   }
   $content=aff_langue($content);
   if (($SuperCache) and ($Xcache!=0)) {
      $cache_clef=md5($content);
      $CACHE_TIMINGS[$cache_clef]=$Xcache;
      $cache_obj = new cacheManager();
      $cache_obj->startCachingBlock($cache_clef);
   } else
      $cache_obj = new SuperCacheEmpty();
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache) or ($Xcache==0)) {
      global $user, $admin;
      // For including CLASS AND URI in Block
      global $B_class_title, $B_class_content;
      $B_class_title=''; $B_class_content=''; $R_uri='';
      if (stristr($content,'class-') or stristr($content,'uri')) {
         $tmp=explode("\n",$content);
         $content='';
         foreach($tmp as $id => $class) {
            $temp=explode("#",$class);
            if ($temp[0]=="class-title")
               $B_class_title=str_replace("\r","",$temp[1]);
            else if ($temp[0]=="class-content")
               $B_class_content=str_replace("\r","",$temp[1]);
            else if ($temp[0]=="uri")
               $R_uri=str_replace("\r",'',$temp[1]);
            else {
               if ($content!='') $content.="\n ";
               $content.=str_replace("\r",'',$class);
            }
         }
      }
      // For BLOC URIs
      if ($R_uri) {
         global $REQUEST_URI;
         $page_ref=basename($REQUEST_URI);
         $tab_uri=explode(" ",$R_uri);
         $R_content=false;
         $tab_pref=parse_url($page_ref);
         $racine_page=$tab_pref['path'];
         if(array_key_exists('query', $tab_pref))
            $tab_pref=explode('&',$tab_pref['query']);
         foreach($tab_uri as $RR_uri) {
            $tab_puri=parse_url($RR_uri);
            $racine_uri=$tab_puri['path'];
            if ($racine_page==$racine_uri) {
               if(array_key_exists('query', $tab_puri))
                  $tab_puri=explode('&',$tab_puri['query']);
               foreach($tab_puri as $idx => $RRR_uri) {
                  if (substr($RRR_uri,-1)=="*") {
                     // si le token contient *
                     if (substr($RRR_uri,0,strpos($RRR_uri,"="))==substr($tab_pref[$idx],0,strpos($tab_pref[$idx],"=")))
                        $R_content=true;
                  } else 
                     $R_content = array_key_exists($RRR_uri, $tab_pref) ? false : true ;
               }
            }
            if ($R_content==true) break;
         }
         if (!$R_content) $content='';
      }
      // For Javascript in Block
      if (!stristr($content,'javascript'))
         $content = nl2br($content);
      // For including externale file in block / the return MUST BE in $content
      if (stristr($content,'include#')) {
         $Xcontent=false;
         // You can now, include AND cast a fonction with params in the same bloc !
         if (stristr($content,"function#")) {
            $content=str_replace('<br />','',$content);
            $content=str_replace('<BR />','',$content);
            $content=str_replace('<BR>','',$content);
            $pos = strpos($content,'function#');
            $Xcontent=substr(trim($content),$pos);
            $content=substr(trim($content),8,$pos-10);
         } else {
            $content=substr(trim($content),8);
         }
         include_once($content);
         if ($Xcontent) {$content=$Xcontent;}
      }
      if (!empty($content)) {
         if (($member==1) and (isset($user))) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif ($member==0) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif (($member>1) and (isset($user))) {
            $tab_groupe=valid_group($user);
            if (groupe_autorisation($member,$tab_groupe)) {
               if (!block_fonction($title,$content)) {
                  if (!$hidden)
                     themesidebox($title, $content);
                  else
                     echo $content;
               }
            }
         } elseif (($member==-1) and (!isset($user))) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif (($member==-127) and (isset($admin)) and ($admin)) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         }
      }
      if (($SuperCache) and ($Xcache!=0)) {
         $cache_obj->endCachingBlock($cache_clef);
      }
   }
}
#autodoc leftblocks() : Meta-Fonction / Blocs de Gauche
function leftblocks($moreclass) {
   Pre_fab_block('','LB',$moreclass);
}
#autodoc rightblocks() : Meta-Fonction / Blocs de Droite
function rightblocks($moreclass) {
   Pre_fab_block('','RB',$moreclass);
}
#autodoc oneblock($Xid, $Xblock) : Alias de Pre_fab_block pour meta-lang
function oneblock($Xid, $Xblock) {
   ob_start();
      Pre_fab_block($Xid, $Xblock,'');
      $tmp=ob_get_contents();
   ob_end_clean();
   return ($tmp);
}
#autodoc Pre_fab_block($Xid, $Xblock, $moreclass) : Assure la fabrication d'un ou de tous les blocs Gauche et Droite
function Pre_fab_block($Xid, $Xblock, $moreclass) {
    global $NPDS_Prefix, $htvar; // modif Jireck
    if ($Xid)
      $result = $Xblock=='RB' ?
         sql_query("SELECT title, content, member, cache, actif, id, css FROM ".$NPDS_Prefix."rblocks WHERE id='$Xid'"):
         sql_query("SELECT title, content, member, cache, actif, id, css FROM ".$NPDS_Prefix."lblocks WHERE id='$Xid'");
    else
      $result = $Xblock=='RB' ?
         sql_query("SELECT title, content, member, cache, actif, id, css FROM ".$NPDS_Prefix."rblocks ORDER BY Rindex ASC"):
         sql_query("SELECT title, content, member, cache, actif, id, css FROM ".$NPDS_Prefix."lblocks ORDER BY Lindex ASC");
    global $bloc_side;
    $bloc_side = $Xblock=='RB' ? 'RIGHT' : 'LEFT';
    while (list($title, $content, $member, $cache, $actif, $id, $css)=sql_fetch_row($result)) {
      if (($actif) or ($Xid)) {
         $htvar = ($css==1) ?
         '
                     <div class="'.$moreclass.'" id="'.$Xblock.'_'.$id.'">' :
         '
                     <div class="'.$moreclass.' '.strtolower($bloc_side).'bloc">' ;
         fab_block($title, $member, $content, $cache);
         // echo "</div>"; // modif Jireck
      }
    }
    sql_free_result($result);
}
#autodoc niv_block($Xcontent) : Retourne le niveau d'autorisation d'un block (et donc de certaines fonctions) / le paramètre (une expression régulière) est le contenu du bloc (function#....)
function niv_block($Xcontent) {
   global $NPDS_Prefix;
   $result = sql_query("SELECT member, actif FROM ".$NPDS_Prefix."rblocks WHERE content REGEXP '$Xcontent'");
   if (sql_num_rows($result)) {
      list($member, $actif) = sql_fetch_row($result);
      return ($member.','.$actif);
   }
   $result = sql_query("SELECT member, actif FROM ".$NPDS_Prefix."lblocks WHERE content REGEXP '$Xcontent'");
   if (sql_num_rows($result)) {
      list($member, $actif) = sql_fetch_row($result);
      return ($member.','.$actif);
   }
   sql_free_result($result);
}
#autodoc autorisation_block($Xcontent) : Retourne une chaine?? // array ou vide contenant la liste des autorisations (-127,-1,0,1,2...126)) SI le bloc est actif SINON "" / le paramètre est le contenu du bloc (function#....)
function autorisation_block($Xcontent) {
   $autoX=array();//notice .... to follow
   $auto=explode(',', niv_block($Xcontent));
   // le dernier indice indique si le bloc est actif
   $actif=$auto[count($auto)-1];
   // on dépile le dernier indice
   array_pop($auto);
   foreach($auto as $autovalue) {
      if (autorisation($autovalue))
         $autoX[]=$autovalue;
   }
   if ($actif)
      return ($autoX);
   else
      return('');
}
#autodoc autorisation($auto) : Retourne true ou false en fonction des paramètres d'autorisation de NPDS (Administrateur, anonyme, Membre, Groupe de Membre, Tous)
function autorisation($auto) {
   global $user, $admin;
   $affich=false;
   if (($auto==-1) and (!$user)) $affich=true;
   if (($auto==1) and (isset($user))) $affich=true;
   if ($auto>1) {
      $tab_groupe=valid_group($user);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue==$auto) {
               $affich=true;
               break;
            }
         }
      }
   }
   if ($auto==0) $affich=true;
   if (($auto==-127) and ($admin)) $affich=true;
   return ($affich);
}
#autodoc getTopics($s_sid) : Retourne le nom, l'image et le texte d'un topic ou False
function getTopics($s_sid) {
   global $NPDS_Prefix;
   global $topicname, $topicimage, $topictext;
   $sid = $s_sid;
   $result=sql_query("SELECT topic FROM ".$NPDS_Prefix."stories WHERE sid='$sid'");
   if ($result) {
      list($topic) = sql_fetch_row($result);
      $result=sql_query("SELECT topicid, topicname, topicimage, topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
      if ($result) {
         list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result);
         return (true);
      } else {
         return (false);
      }
   } else {
      return (false);
   }
}
#autodoc subscribe_mail($Xtype, $Xtopic,$Xforum, $Xresume, $Xsauf) : Assure l'envoi d'un mail pour un abonnement
function subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf) {
   // $Xtype : topic, forum ... / $Xtopic clause WHERE / $Xforum id of forum / $Xresume Text passed / $Xsauf not this userid
   global $NPDS_Prefix, $sitename, $nuke_url;
   if ($Xtype=='topic') {
      $result=sql_query("SELECT topictext FROM ".$NPDS_Prefix."topics WHERE topicid='$Xtopic'");
      list($abo)=sql_fetch_row($result);
      $result=sql_query("SELECT uid FROM ".$NPDS_Prefix."subscribe WHERE topicid='$Xtopic'");
   }
   if ($Xtype=='forum') {
      $result=sql_query("SELECT forum_name, arbre FROM ".$NPDS_Prefix."forums WHERE forum_id='$Xforum'");
      list($abo, $arbre)=sql_fetch_row($result);
      if ($arbre)
         $hrefX='viewtopicH.php';
      else
         $hrefX='viewtopic.php';
      $resultZ=sql_query("SELECT topic_title FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$Xtopic'");
      list($title_topic)=sql_fetch_row($resultZ);
      $result=sql_query("SELECT uid FROM ".$NPDS_Prefix."subscribe WHERE forumid='$Xforum'");
   }
   include_once("language/lang-multi.php");
   while(list($uid) = sql_fetch_row($result)) {
      if ($uid!=$Xsauf) {
         $resultX=sql_query("SELECT email, user_langue FROM ".$NPDS_Prefix."users WHERE uid='$uid'");
         list($email, $user_langue)=sql_fetch_row($resultX);
         if ($Xtype=='topic') {
            $entete=translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ").translate_ml($user_langue, "Sujet")." => ".strip_tags($abo)."\n\n";
            $resume=translate_ml($user_langue, "Le titre de la dernière publication est")." => $Xresume\n\n";
            $url=translate_ml($user_langue, "L'URL pour cet article est : ")."<a href=\"$nuke_url/search.php?query=&topic=$Xtopic\">$nuke_url/search.php?query=&topic=$Xtopic</a>\n\n";
         }
         if ($Xtype=='forum') {
            $entete=translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ").translate_ml($user_langue, "Forum")." => ".strip_tags($abo)."\n\n";
            $url=translate_ml($user_langue, "L'URL pour cet article est : ")."<a href=\"$nuke_url/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999#lastpost\">$nuke_url/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999</a>\n\n";
            $resume=translate_ml($user_langue, "Le titre de la dernière publication est")." => ";
            if ($Xresume!='') {
               $resume.=$Xresume."\n\n";
            } else {
               $resume.=$title_topic."\n\n";
            }
         }
         $subject = html_entity_decode(translate_ml($user_langue, "Abonnement"),ENT_COMPAT | ENT_HTML401,'UTF-8')." / $sitename";
         $message = $entete;
         $message .= $resume;
         $message .= $url;
         include("signat.php");
         send_email($email, $subject, $message, '', true, 'html');
      }
   }
}
#autodoc subscribe_query($Xuser,$Xtype, $Xclef) : Retourne true si le membre est abonné; à un topic ou forum
function subscribe_query($Xuser,$Xtype, $Xclef) {
   global $NPDS_Prefix;
   if ($Xtype=='topic') {
      $result=sql_query("SELECT topicid FROM ".$NPDS_Prefix."subscribe WHERE uid='$Xuser' AND topicid='$Xclef'");
   }
   if ($Xtype=='forum') {
      $result=sql_query("SELECT forumid FROM ".$NPDS_Prefix."subscribe WHERE uid='$Xuser' AND forumid='$Xclef'");
   }
   list($Xtemp) = sql_fetch_row($result);
   if ($Xtemp!='') {
      return (true);
   } else {
      return (false);
   }
}
#autodoc pollSecur($pollID) : Assure la gestion des sondages membres
function pollSecur($pollID) {
   global $NPDS_Prefix, $user;
   $pollIDX=false; $pollClose='';
   $result = sql_query("SELECT pollType FROM ".$NPDS_Prefix."poll_data WHERE pollID='$pollID'");
   if (sql_num_rows($result)) {
      list($pollType)=sql_fetch_row($result);
      $pollClose = (($pollType / 128) >= 1 ? 1 : 0);
      $pollType = $pollType%128;
      if (($pollType==1) and !isset($user))
         $pollClose=99;
   }
   return (array($pollID, $pollClose));
}
#autodoc pollMain($pollID,$pollClose) : Construit le bloc sondage
function pollMain($pollID,$pollClose) {
   global $NPDS_Prefix, $maxOptions, $boxTitle, $boxContent, $userimg, $language, $pollcomm, $cookie;
   if (!isset($pollID))
      $pollID = 1;
   if (!isset($url))
      $url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
   $boxContent = '
                              <form action="pollBooth.php" method="post">
                                 <input type="hidden" name="pollID" value="'.$pollID.'" />
                                 <input type="hidden" name="forwarder" value="'.$url.'" />';
   $result = sql_query("SELECT pollTitle, voters FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
   list($pollTitle, $voters) = sql_fetch_row($result);
   global $block_title;
   $boxTitle = $block_title=='' ? translate("Sondage") :  $block_title ;
   $boxContent .= '<legend>'.aff_langue($pollTitle).'</legend>';
   $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID' AND optionText<>'') ORDER BY voteID");
   $sum = 0; $j=0;
   if (!$pollClose) {
      $boxContent .= '
                                 <div class="mb-3">';
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= '
                                    <div class="form-check">
                                       <input class="form-check-input" type="radio" id="voteID'.$j.'" name="voteID" value="'.$object['voteID'].'" />
                                       <label class="form-check-label d-block" for="voteID'.$j.'" >'.aff_langue($object['optionText']).'</label>
                                    </div>';
         $sum = $sum + $object['optionCount'];
         $j++; 
      }
         $boxContent .= '
                                 </div>';
   } else {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= '&nbsp;'.aff_langue($object['optionText']).'<br />';
         $sum = $sum + $object['optionCount'];
      }
   }
   settype($inputvote,'string');
   if (!$pollClose)
      $inputvote = '<button class="btn btn-outline-primary btn-sm btn-block" type="submit" value="'.translate("Voter").'" title="'.translate("Voter").'" ><i class="fa fa-check fa-lg"></i> '.translate("Voter").'</button>';
   $boxContent .= '
                                 <div class="mb-3">'.$inputvote.'</div>
                              </form>
                              <a href="pollBooth.php?op=results&amp;pollID='.$pollID.'" title="'.translate("Résultats").'">'.translate("Résultats").'</a>&nbsp;&nbsp;<a href="pollBooth.php">'.translate("Anciens sondages").'</a>
                              <ul class="list-group mt-3">
                                 <li class="list-group-item">'.translate("Votes : ").' <span class="badge rounded-pill bg-secondary float-end">'.$sum.'</span></li>';
   if ($pollcomm) {
      if (file_exists("modules/comments/pollBoth.conf.php"))
         include ("modules/comments/pollBoth.conf.php");
      list($numcom) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' AND topic_id='$pollID' AND post_aff='1'"));
      $boxContent .= '
                                 <li class="list-group-item">'.translate("Commentaire(s) : ").' <span class="badge rounded-pill bg-secondary float-end">'.$numcom.'</span></li>';
   }
   $boxContent .= '
                              </ul>
                           ';
   themesidebox($boxTitle, $boxContent);
}
#autodoc fab_edito() : Construit l'edito
function fab_edito() {
   global $cookie;
   if (isset($cookie[3])) {
      if (file_exists("static/edito_membres.txt")) {
         $fp=fopen("static/edito_membres.txt","r");
         if (filesize("static/edito_membres.txt")>0)
            $Xcontents=fread($fp,filesize("static/edito_membres.txt"));
         fclose($fp);
      } else {
         if (file_exists("static/edito.txt")) {
            $fp=fopen("static/edito.txt","r");
            if (filesize("static/edito.txt")>0)
               $Xcontents=fread($fp,filesize("static/edito.txt"));
            fclose($fp);
         }
      }
   } else {
      if (file_exists("static/edito.txt")) {
         $fp=fopen("static/edito.txt","r");
         if (filesize("static/edito.txt")>0)
            $Xcontents=fread($fp,filesize("static/edito.txt"));
         fclose($fp);
      }
   }
   $affich=false;
   $Xibid=strstr($Xcontents,'aff_jours');
   if ($Xibid) {
      parse_str($Xibid,$Xibidout);
      if (($Xibidout['aff_date']+($Xibidout['aff_jours']*86400))-time()>0) {
         $affichJ=false; $affichN=false;
         if ((NightDay()=='Jour') and ($Xibidout['aff_jour']=='checked')) $affichJ=true;
         if ((NightDay()=='Nuit') and ($Xibidout['aff_nuit']=='checked')) $affichN=true;
      }
      $XcontentsT=substr($Xcontents,0,strpos($Xcontents,'aff_jours'));
      $contentJ=substr($XcontentsT,strpos($XcontentsT,"[jour]")+6,strpos($XcontentsT,"[/jour]")-6);
      $contentN=substr($XcontentsT,strpos($XcontentsT,"[nuit]")+6,strpos($XcontentsT,"[/nuit]")-19-strlen($contentJ));
      $Xcontents='';
      if (isset($affichJ) and $affichJ===true)
         $Xcontents=$contentJ;
      if (isset($affichN) and $affichN===true)
         $Xcontents = $contentN!='' ? $contentN : $contentJ ;
      if ($Xcontents!='') $affich=true;
   } else
      $affich=true;
   $Xcontents=meta_lang(aff_langue($Xcontents));
   return array($affich, $Xcontents);
}
#autodoc aff_langue($ibid) : Analyse le contenu d'une chaine et converti la section correspondante ([langue] OU [!langue] ...[/langue]) &agrave; la langue / [transl] ... [/transl] permet de simuler un appel translate("xxxx")
function aff_langue($ibid) {
   global $language, $tab_langue;
   // copie du tableau + rajout de transl pour gestion de l'appel à translate(...); - Theme Dynamic
   $tab_llangue=$tab_langue;
   $tab_llangue[]='transl';
   reset ($tab_llangue);
   $ok_language=false;
   $trouve_language=false;

   foreach($tab_llangue as $key => $lang) {
      $pasfin=true; $pos_deb=false; $abs_pos_deb=false; $pos_fin=false;
      while ($pasfin) {
         // tags [langue] et [/langue]
         $pos_deb=strpos($ibid ?? '',"[$lang]",0);
         $pos_fin=strpos($ibid ?? '',"[/$lang]",0);
         if ($pos_deb===false) $pos_deb=-1;
         if ($pos_fin===false) $pos_fin=-1;
         // tags [!langue]
         $abs_pos_deb=strpos($ibid ?? '',"[!$lang]",0);
         if ($abs_pos_deb!==false) {
            $ibid=str_replace("[!$lang]", "[$lang]", $ibid);
            $pos_deb=$abs_pos_deb;
            if ($lang!=$language) 
               $trouve_language=true;
         }
         $decal=strlen($lang)+2;
         if (($pos_deb>=0) and ($pos_fin>=0)) {
            $fragment=substr($ibid,$pos_deb+$decal,($pos_fin-$pos_deb-$decal));
            if ($trouve_language==false) {
               if ($lang!='transl')
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", $fragment, $ibid);
               else
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", translate($fragment), $ibid);
               $ok_language=true;
            } else {
               if ($lang!='transl')
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", "", $ibid);
               else
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", translate($fragment), $ibid);
            }
         } else
            $pasfin=false;
      }
      if ($ok_language)
         $trouve_language=true;
   }
   return ($ibid);
}
#autodoc make_tab_langue() : Charge le tableau TAB_LANGUE qui est utilisé par les fonctions multi-langue
function make_tab_langue() {
   global $language, $languageslist;
   $languageslocal=$language.' '.str_replace($language,'',$languageslist);
   $languageslocal=trim(str_replace('  ',' ',$languageslocal));
   $tab_langue=explode(' ',$languageslocal);
   return ($tab_langue);
}
#autodoc aff_localzone_langue($ibid) : Charge une zone de formulaire de selection de la langue
function aff_localzone_langue($ibid) {
   global $tab_langue;
   $flag = array('french'=>'🇫🇷','spanish'=>'🇪🇸','german'=>'🇩🇪','english'=>'🇺🇸','chinese'=>'🇨🇳');
   $M_langue= '
                                 <div class="mb-3">
                                    <select name="'.$ibid.'" class="form-select" onchange="this.form.submit()" aria-label="'.translate("Choisir une langue").'">
                                       <option value="">'.translate("Choisir une langue").'</option>';
   foreach($tab_langue as $bidon => $langue) {
      $M_langue.='
                                       <option value="'.$langue.'">'.$flag[$langue].' '.translate("$langue").'</option>';
   }
   $M_langue.='
                                       <option value="">- '.translate("Aucune langue").'</option>
                                    </select>
                                 </div>
                                 <noscript>
                                    <input class="btn btn-primary" type="submit" name="local_sub" value="'.translate("Valider").'" />
                                 </noscript>';
   return ($M_langue);
}
#autodoc aff_local_langue($ibid_index, $ibid, $mess) : Charge une FORM de selection de langue $ibid_index = URL de la Form, $ibid = nom du champ
function aff_local_langue($ibid_index, $ibid, $mess='') {
   if ($ibid_index=='') {
      global $REQUEST_URI;
      $ibid_index=$REQUEST_URI;
   }
   $M_langue ='
                              <form action="'.$ibid_index.'" name="local_user_language" method="post">';
   $M_langue.=$mess.aff_localzone_langue($ibid);
   $M_langue.='
                              </form>
                           ';
   return($M_langue);
}
#autodoc preview_local_langue($local_user_language,$ibid) : appel la fonction aff_langue en modifiant temporairement la valeur de la langue
function preview_local_langue($local_user_language,$ibid) {
   if ($local_user_language) {
      global $language, $tab_langue;
      $old_langue=$language;
      $language=$local_user_language;
         $tab_langue=make_tab_langue();
         $ibid=aff_langue($ibid);
      $language=$old_langue;
   }
   return ($ibid);
}
#autodoc af_cod($ibid) : Analyse le contenu d'une chaîne et converti les pseudo-balises [code]...[/code] et leur contenu en html
function change_cod($r) {
   return '<'.$r[2].' class="language-'.$r[3].'">'.htmlentities($r[5],ENT_COMPAT|ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8').'</'.$r[2].'>';
}
function af_cod($ibid) {
   $pat='#(\[)(\w+)\s+([^\]]*)(\])(.*?)\1/\2\4#s';
   $ibid=preg_replace_callback($pat, "change_cod", $ibid, -1, $nb);
//   $ibid= str_replace(array("\r\n", "\r", "\n"), "<br />",$ibid);
   return $ibid;
}
#autodoc desaf_cod($ibid) : Analyse le contenu d'une chaîne et converti les balises html <code>...</code> en pseudo-balises [code]...[/code]
function desaf_cod($ibid) {
   $pat='#(<)(\w+)\s+(class="language-)([^">]*)(">)(.*?)\1/\2>#';
   function rechange_cod($r) {
     return '['.$r[2].' '.$r[4].']'.$r[6].'[/'.$r[2].']';
   }
   $ibid=preg_replace_callback($pat, 'rechange_cod', $ibid, -1);
   return $ibid;
}
#autodoc aff_code($ibid) : Analyse le contenu d'une chaîne et converti les balises [code]...[/code]
function aff_code($ibid) {
   $pasfin=true;
   while ($pasfin) {
      $pos_deb=strpos($ibid,"[code]",0);
      $pos_fin=strpos($ibid,"[/code]",0);
      // ne pas confondre la position ZERO et NON TROUVE !
      if ($pos_deb===false) {$pos_deb=-1;}
      if ($pos_fin===false) {$pos_fin=-1;}
      if (($pos_deb>=0) and ($pos_fin>=0)) {
         ob_start();
            highlight_string(substr($ibid,$pos_deb+6,($pos_fin-$pos_deb-6)));
            $fragment=ob_get_contents();
         ob_end_clean();
         $ibid=str_replace(substr($ibid,$pos_deb,($pos_fin-$pos_deb+7)),$fragment,$ibid);
      } else
         $pasfin=false;
   }
   return ($ibid);
}
#####// ces deux fonctions suivantes génèrent des erreurs multiples à corriger ou supprimer Warning: Uninitialized string offset 16 in mainfile.php on line 1655
#autodoc split_string_without_space($msg, $split) : Découpe la chaine en morceau de $slpit longueur si celle-ci ne contient pas d'espace / Snipe 2004
function split_string_without_space($msg, $split) {
   $Xmsg=explode(' ',$msg);
   array_walk($Xmsg,'wrapper_f', $split);
   $Xmsg=implode(' ',$Xmsg);
   return ($Xmsg);
}
#autodoc wrapper_f (&$string, $key, $cols) : Fonction Wrapper pour split_string_without_space / Snipe 2004
function wrapper_f (&$string, $key, $cols) {
//   if (!(stristr($string,'IMG src=') or stristr($string,'A href=') or stristr($string,'HTTP:') or stristr($string,'HTTPS:') or stristr($string,'MAILTO:') or stristr($string,'[CODE]'))) {
$outlines = '';
      if (strlen($string) > $cols) {
         while(strlen($string) > $cols) {
            $cur_pos = 0;
            for($num=0; $num < $cols-1; $num++) {
               $outlines .= $string[$num];
               $cur_pos++;
               if ($string[$num]=="\n") {
                  $string = substr($string, $cur_pos, (strlen($string)-$cur_pos));
                  $cur_pos=0;
                  $num = 0;
               }
            }
            $outlines .= '<i class="fa fa-cut fa-lg"> </i>';
            $string = substr($string, $cur_pos, (strlen($string)-$cur_pos));
         }
         $string=$outlines.$string;
      }
//   }
}
#autodoc preg_anti_spam($str) : Permet l'utilisation de la fonction anti_spam via preg_replace
function preg_anti_spam($ibid) {
   // Adaptation - David MARTINET alias Boris (2011)
   return("<a href=\"mailto:".anti_spam($ibid, 1)."\" target=\"_blank\">".anti_spam($ibid, 0)."</a>");  
}  
#autodoc anti_spam($str [, $highcode]) : Encode une chaine en mélangeant caractères normaux, codes décimaux et hexa. Si $highcode == 1, utilise également le codage ASCII (compatible uniquement avec des mailto et des URL, pas pour affichage)
function anti_spam($str, $highcode = 0) {
   // Idée originale : Pomme (2004). Nouvelle version : David MARTINET alias Boris (2011)
   $str_encoded = "";
   mt_srand((double)microtime()*1000000);
   for($i = 0; $i < strlen($str); $i++) {
      if ($highcode==1) {
         $alea=mt_rand(1, 400);
         $modulo=4;
      } else { 
         $alea=mt_rand(1, 300);
         $modulo=3;
      }
      switch (($alea % $modulo)) {
         case 0: $str_encoded.=$str[$i]; break;
         case 1: $str_encoded.="&#".ord($str[$i]).";"; break;
         case 2: $str_encoded.="&#x".bin2hex($str[$i]).";"; break;
         case 3: $str_encoded.="%".bin2hex($str[$i]).""; break;
         default: $str_encoded="Error"; break;
      }
   }
   return $str_encoded;
}
#autodoc aff_editeur($Xzone, $Xactiv) : Charge l'éditeur ... ou non : $Xzone = nom du textarea / $Xactiv = deprecated <br /> si $Xzone="custom" on utilise $Xactiv pour passer des paramètres spécifiques
function aff_editeur($Xzone, $Xactiv) {
   global $language, $tmp_theme, $tiny_mce, $tiny_mce_theme, $tiny_mce_relurl;
   $tmp='';
   if ($tiny_mce) {
      static $tmp_Xzone;
      if ($Xzone=='tiny_mce') {
         if ($Xactiv=='end') {
            if (substr((string) $tmp_Xzone,-1)==',')
               $tmp_Xzone=substr_replace((string) $tmp_Xzone,'',-1);
            if ($tmp_Xzone) {
               $tmp="
      <script type=\"text/javascript\">
      //<![CDATA[
         document.addEventListener(\"DOMContentLoaded\", function(e) {
            tinymce.init({
               selector: 'textarea.tin',
               mobile: {menubar: true},
               language : '".language_iso(1,'','')."',";
                  include ("editeur/tinymce/themes/advanced/npds.conf.php");
                  $tmp.='
               });
         });
      //]]>
      </script>';
            }
         } else
            $tmp.='<script type="text/javascript" src="editeur/tinymce/tinymce.min.js"></script>';
      } else
         $tmp_Xzone.= $Xzone!='custom' ? $Xzone.',' : $Xactiv.',';
   } else
      $tmp='';
   return ($tmp);
}
#autodoc utf8_java($ibid) : Encode une chaine UF8 au format javascript - JPB 2005
function utf8_java($ibid) {
   // UTF8 = &#x4EB4;&#x6B63;&#7578; / javascript = \u4EB4\u6B63\u.dechex(7578)
   $tmp=explode ('&#',$ibid);
   foreach($tmp as $bidon) {
      if ($bidon) {
         $bidon=substr($bidon,0,strpos($bidon,";"));
         $hex=strpos($bidon,'x');
         $ibid = ($hex===false) ?
            str_replace('&#'.$bidon.';','\\u'.dechex((int)$bidon),$ibid) :
            str_replace('&#'.$bidon.';','\\u'.substr($bidon,1),$ibid);
      }
   }
   return ($ibid);
}
#autodoc wrh($ibid) : Formate une chaine numérique avec un espace tous les 3 chiffres / cheekybilly 2005
function wrh($ibid) {
   $tmp=number_format($ibid,0,',',' ');
   $tmp=str_replace(' ','&nbsp;',$tmp);
   return ($tmp);
}
#autodoc Q_spambot() : forge un champ de formulaire (champ de saisie : $asb_reponse / champ hidden : asb_question) permettant de déployer une fonction anti-spambot
function Q_spambot() {
   // Idée originale, développement et intégration - Gérald MARINO alias neo-machine
   // Rajout brouillage anti_spam() : David MARTINET, alias Boris (2011)
   // Other stuff : Dev 2012
   global $user;
   $asb_question = array (
   '4 - (3 / 1)'       => 1,
   '7 - 5 - 0'         => 2,
   '2 + (1 / 1)'       => 3,
   '2 + (1 + 1)'       => 4,
   '3 + (0) + 2'       => 5,
   '3 + (9 / 3)'       => 6,
   '4 + 3 - 0'         => 7,
   '6 + (0) + 2'       => 8,
   '8 + (5 - 4)'       => 9,
   '0 + (6 + 4)'       => 10,
   '(5 * 2) + 1'       => 11,
   '6 + (3 + 3)'       => 12,
   '1 + (6 * 2)'       => 13,
   '(8 / 1) + 6 '      => 14,
   '6 + (5 + 4)'       => 15,
   '8 + (4 * 2)'       => 16,
   '1 + (8 * 2)'       => 17,
   '9 + (3 + 6)'       => 18,
   '(7 * 2) + 5'       => 19,
   '(8 * 3) - 4'       => 20,
   '7 + (2 * 7)'       => 21,
   '9 + 5 + 8'         => 22,
   '(5 * 4) + 3'       => 23,
   '0 + (8 * 3)'       => 24,
   '1 + (4 * 6)'       => 25,
   '(6 * 5) - 4'       => 26,
   '3 * (9 + 0)'       => 27,
   '4 + (3 * 8)'       => 28,
   '(6 * 4) + 5'       => 29,
   '0 + (6 * 5)'       => 30);
   
   // START ALEA
   mt_srand((double)microtime()*1000000);
      // choix de la question
      $asb_index = mt_rand(0,count($asb_question)-1);
      $ibid=array_keys($asb_question);
      $aff = $ibid[$asb_index];

      // translate
      $tab=explode(' ', str_replace(')','',str_replace('(','',$aff))); 
      $al1=mt_rand(0,count($tab)-1);
      if (function_exists("imagepng"))
         $aff=str_replace($tab[$al1],html_entity_decode(translate($tab[$al1]), ENT_QUOTES | ENT_HTML401, 'UTF-8'),$aff);
      else
         $aff=str_replace($tab[$al1],html_entity_decode(translate($tab[$al1]), ENT_QUOTES | ENT_HTML401, 'UTF-8'),$aff);

##################################################################
/* infernal ....
après de nombreux essai ... avec ISO-8859-1 ou UTF-8
$aff=str_replace($tab[$al1],html_entity_decode(translate($tab[$al1]), ENT_QUOTES | ENT_HTML401, 'ISO-8859-1'),$aff);

encrypt puis decrypt renvoient un résultats corrects stable
mais parfois la fonction encrypt peut renvoyer une chaine 
- se terminant par == ou /
- contenant de + des / 
dans ces cas là le résultat de rawurldecode(decrypt()) est corrompu !

dans les autres cas le résultat de rawurldecode(decrypt()) traité dans cette page est correct
MAIS PAS quand ça repasse par le serveur et traité dans getfile.php (et je ne comprends pas pourquoi)

donc pour l'instant on garde UTF-8 et on redécode dans getfile.php
mais dans tout les cas le captcha en chinois est faux
//je pense qu' il faut plutôt utiliser imagettftext dans le captcha (adapté à utf-8) !
*/
/*
var_dump('sortie de trad en utf-8 ou iso ==>'.$aff.'<br />');/////////

$encryt_aff = encrypt($aff." = ");/////
var_dump("fonction encrypt ==> ".$encryt_aff.'<br />');/////
$decryt_aff = decrypt($encryt_aff." = ");/////
var_dump("fonction decrypt ==> ".$decryt_aff.'<br />');/////

$rawurlencode= rawurlencode($encryt_aff);/////
var_dump("fonction rawurlencode ==> ".$rawurlencode.'<br />');/////
$rawurldecode= rawurldecode($rawurlencode);/////
var_dump("fonction rawurldecode ==> ".$rawurldecode.'<br />');/////

$b = rawurldecode(decrypt($rawurlencode));
var_dump('fonction rawurldecode (decrypt()) ==> '.$b.'<br />');////

function mb_rawurlencode($url){
$encoded='';
$length=mb_strlen($url);
for($i=0;$i<$length;$i++){
$encoded.='%'.wordwrap(bin2hex(mb_substr($url,$i,1)),2,'%',true);
}
return $encoded;
}
*/
##################################################################
      // mis en majuscule
      if ($asb_index%2)
         $aff = ucfirst($aff);
   // END ALEA
   //Captcha - si GD
   if (function_exists("imagepng"))
      $aff="<img src=\"getfile.php?att_id=".rawurlencode(encrypt($aff." = "))."&amp;apli=captcha\" style=\"vertical-align: middle;\" />";
   else
      $aff= anti_spam($aff." = ",0);
   $tmp='';
   if ($user=='') {
      $tmp='
      <div class="mb-3 row">
         <div class="col-sm-9 text-end">
            <label class="form-label text-danger" for="asb_reponse">'.translate("Anti-Spam / Merci de répondre à la question suivante : ").'&nbsp;'.$aff.'</label>
         </div>
         <div class="col-sm-3 text-end">
            <input class="form-control" type="text" id="asb_reponse" name="asb_reponse" maxlength="2" onclick="this.value" />
            <input type="hidden" name="asb_question" value="'.encrypt($ibid[$asb_index].','.time()).'" />
         </div>
      </div>';
   } else {
      $tmp='
      <input type="hidden" name="asb_question" value="" />
      <input type="hidden" name="asb_reponse" value="" />';
   }
   return ($tmp);
}
#autodoc L_spambot($ip, $status) : Log spambot activity : $ip="" => getip of the current user OR $ip="x.x.x.x" / $status = Op to do : true => not log or suppress log - false => log+1 - ban => Ban an IP 
function L_spambot($ip, $status) {
   $cpt_sup=0;
   $maj_fic=false;
   if ($ip=='')
      $ip=getip();
   if (file_exists("slogs/spam.log")) {
      $tab_spam=str_replace("\r\n",'',file("slogs/spam.log"));
      if (in_array($ip.'|1',$tab_spam))
         $cpt_sup=2;
      if (in_array($ip.'|2',$tab_spam))
         $cpt_sup=3;
      if (in_array($ip.'|3',$tab_spam))
         $cpt_sup=4;
      if (in_array($ip.'|4',$tab_spam))
         $cpt_sup=5;
   }
   if ($cpt_sup) {
      if ($status=="false") {
         $tab_spam[array_search($ip.'|'.($cpt_sup-1),$tab_spam)]=$ip.'|'.$cpt_sup;
      } else if ($status=="ban") {
         $tab_spam[array_search($ip.'|'.($cpt_sup-1),$tab_spam)]=$ip.'|5';
      } else {
         $tab_spam[array_search($ip.'|'.($cpt_sup-1),$tab_spam)]='';
      }
      $maj_fic=true;
   } else {
      if ($status=="false") {
         $tab_spam[]=$ip.'|1';
         $maj_fic=true;
      } else if ($status=='ban') {
         if (!in_array($ip.'|5',$tab_spam)) {
            $tab_spam[]=$ip.'|5';
            $maj_fic=true;
         }
      }
   }
   if ($maj_fic) {
      $file = fopen("slogs/spam.log", "w");
      foreach($tab_spam as $key => $val) {
         if ($val)
            fwrite($file, $val."\r\n");
      }
      fclose($file);
   }
}
#autodoc R_spambot($asb_question, $asb_reponse, $message) : valide le champ $asb_question avec la valeur de $asb_reponse (anti-spambot) et filtre le contenu de $message si nécessaire
function R_spambot($asb_question, $asb_reponse, $message='') {
   // idée originale, développement et intégration - Gérald MARINO alias neo-machine
   global $user;
   global $REQUEST_METHOD;
   if ($REQUEST_METHOD=="POST") {
      if ($user=='') {
         if ( ($asb_reponse!='') and (is_numeric($asb_reponse)) and (strlen($asb_reponse)<=2) ) {
            $ibid=decrypt($asb_question);
            $ibid=explode(',',$ibid);
            $result="\$arg=($ibid[0]);";
            // submit intervient en moins de 5 secondes (trop vite) ou plus de 30 minutes (trop long)
            $temp=time()-$ibid[1];
            if (($temp<1800) and ($temp>5)) {
               eval($result);
            } else {
               $arg=uniqid(mt_rand());
            }
         } else {
            $arg=uniqid(mt_rand());
         }
         if ($arg==$asb_reponse) {
            // plus de 2 http:// dans le texte
            preg_match_all('#http://#',$message,$regs);
            if (count($regs[0])>2) {
               L_spambot('',"false");
               return (false);
            } else {
               L_spambot('',"true");
               return (true);
            }
         } else {
            L_spambot('',"false");
            return (false);
         }
      } else {
         L_spambot('',"true");
         return (true);
      }
   } else {
      L_spambot('',"false");
      return (false);
   }
}
#autodoc keyED($txt,$encrypt_key) : Composant des fonctions encrypt et decrypt
function keyED($txt,$encrypt_key) {
   $encrypt_key = md5($encrypt_key);
   $ctr=0;
   $tmp = '';
   for ($i=0;$i<strlen($txt);$i++) {
       if ($ctr==strlen($encrypt_key) ) $ctr=0;
       $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
       $ctr++;
   }
   return $tmp;
}
#autodoc encrypt($txt) : retourne une chaine encryptée en utilisant la valeur de $NPDS_Key
function encrypt($txt) {
   global $NPDS_Key;
   return (encryptK($txt,$NPDS_Key));
}
#autodoc encryptK($txt, $C_key) : retourne une chaine encryptée en utilisant la clef : $C_key
function encryptK($txt, $C_key) {
   // Génération de la graine de manière compatible
   $microtime = microtime(true) * 1000000;
   // Pour PHP 8.1+ qui est strict sur les conversions de types
   if (PHP_VERSION_ID >= 80100) {
       srand((int)round($microtime));
   } else {
      // Pour les versions antérieures à PHP 8.1
      srand((double)$microtime);
   }
   $encrypt_key = md5(rand(0,32000));
   $ctr = 0;
   $tmp = '';
   for ($i = 0; $i < strlen($txt); $i++) {
       if ($ctr == strlen($encrypt_key)) $ctr = 0;
       $tmp .= substr($encrypt_key, $ctr, 1) . 
               (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
       $ctr++;
   }
   return base64_encode(keyED($tmp, $C_key));
}
#autodoc decrypt($txt) : retourne une chaine décryptée en utilisant la valeur de $NPDS_Key
function decrypt($txt) {
   global $NPDS_Key;
   return (decryptK($txt, $NPDS_Key));
}
#autodoc decryptK($txt, $C_key) : retourne une décryptée en utilisant la clef de $C_Key
function decryptK($txt, $C_key) {
   $txt = keyED(base64_decode($txt),$C_key);
   $tmp = '';
   for ($i=0;$i<strlen($txt);$i++) {
       $md5 = substr($txt,$i,1);
       $i++;
       $tmp.= (substr($txt,$i,1) ^ $md5);
   }
   return ($tmp);
}
#autodoc conv2br($txt) : convertie \r \n  BR ... en br XHTML
function conv2br($txt) {
   $Xcontent=str_replace("\r\n","<br />",$txt);
   $Xcontent=str_replace("\r","<br />",$Xcontent);
   $Xcontent=str_replace("\n","<br />",$Xcontent);
   $Xcontent=str_replace("<BR />","<br />",$Xcontent);
   $Xcontent=str_replace("<BR>","<br />",$Xcontent);
   return ($Xcontent);
}
#autodoc hexfromchr($txt) : Les 8 premiers caractères sont convertis en UNE valeur Hexa unique 
function hexfromchr($txt) {
   $surlignage=substr(md5($txt),0,8);
   $tmp=0;
   for ($ix = 0; $ix <= 5; $ix++) {
      $tmp+=hexdec($surlignage[$ix])+1;
   }
   return ($tmp%=16);
}
#autodoc:<Mainfile.php>
#autodoc <span class="text-success">BLOCS NPDS</span>:
#autodoc Site_Activ() : Bloc activité du site <br />=> syntaxe : function#Site_Activ
function Site_Activ() {
   global $startdate, $top;
   list($membres,$totala,$totalb,$totalc,$totald,$totalz)=req_stat();
   $aff ='
                              <p class="text-center">'.translate("Pages vues depuis").' '.$startdate.' : <span class="fw-semibold">'.wrh($totalz).'</span></p>
                              <ul class="list-group mb-3" id="site_active">
                                 <li class="my-1">'.translate("Nb. de membres").' <span class="badge rounded-pill bg-secondary float-end">'.wrh(($membres)).'</span></li>
                                 <li class="my-1">'.translate("Nb. d'articles").' <span class="badge rounded-pill bg-secondary float-end">'.wrh($totala).'</span></li>
                                 <li class="my-1">'.translate("Nb. de forums").' <span class="badge rounded-pill bg-secondary float-end">'.wrh($totalc).'</span></li>
                                 <li class="my-1">'.translate("Nb. de sujets").' <span class="badge rounded-pill bg-secondary float-end">'.wrh($totald).'</span></li>
                                 <li class="my-1">'.translate("Nb. de critiques").' <span class="badge rounded-pill bg-secondary float-end">'.wrh($totalb).'</span></li>
                              </ul>';
   if ($ibid=theme_image("box/top.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;}// no need
   if ($imgtmp) {
      $aff .= '
                              <p class="text-center"><a href="top.php"><img src="'.$imgtmp.'" alt="'.translate("Top").' '.$top.'" /></a>&nbsp;&nbsp;';
      if ($ibid=theme_image("box/stat.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;} // no need
      $aff .= '<a href="stats.php"><img src="'.$imgtmp.'" alt="'.translate("Statistiques").'" /></a></p>';
   } else
      $aff .= '
                              <p class="text-center"><a href="top.php">'.translate("Top").' '.$top.'</a>&nbsp;&nbsp;<a href="stats.php" >'.translate("Statistiques").'</a></p>
                           ';
   global $block_title;
   $title = $block_title =='' ? translate("Activité du site") : $block_title ;
   themesidebox($title, $aff);
}
#autodoc online() : Bloc Online (Who_Online) <br />=> syntaxe : function#online
function online() {
   global $NPDS_Prefix, $user, $cookie;
   $ip = getip();
   $username = isset($cookie[1]) ? $cookie[1] : '';
   if ($username=='') {
      $username = $ip;
      $guest = 1;
   }
   else
      $guest = 0;
   $past = time()-300;
   sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE time < '$past'");
   $result = sql_query("SELECT time FROM ".$NPDS_Prefix."session WHERE username='$username'");
   $ctime = time();
   if ($row = sql_fetch_row($result))
      sql_query("UPDATE ".$NPDS_Prefix."session SET username='$username', time='$ctime', host_addr='$ip', guest='$guest' WHERE username='$username'");
   else
      sql_query("INSERT INTO ".$NPDS_Prefix."session (username, time, host_addr, guest) VALUES ('$username', '$ctime', '$ip', '$guest')");
   $result = sql_query("SELECT username FROM ".$NPDS_Prefix."session WHERE guest=1");
   $guest_online_num = sql_num_rows($result);
   $result = sql_query("SELECT username FROM ".$NPDS_Prefix."session WHERE guest=0");
   $member_online_num = sql_num_rows($result);
   $who_online_num = $guest_online_num + $member_online_num;
   $who_online = '
                              <p class="text-center">'.translate("Il y a actuellement").' <span class="badge bg-secondary">'.$guest_online_num.'</span> '.translate("visiteur(s) et").' <span class="badge bg-secondary">'.$member_online_num.' </span> '.translate("membre(s) en ligne.").'<br />';
   $content = $who_online;
   if ($user) {
      $content .= '<br />'.translate("Vous êtes connecté en tant que").' <strong>'.$username.'</strong>.<br />';
      $result = Q_select("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$username'", 86400);
      $uid = $result[0];
      $result2 = sql_query("SELECT to_userid FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid='".$uid['uid']."' AND type_msg='0'");
      $numrow = sql_num_rows($result2);
      $content .= translate("Vous avez").' <a href="viewpmsg.php"><span class="badge bg-primary">'.$numrow.'</span></a> '.translate("message(s) personnel(s).").'</p>
                           ';
   } else
      $content .= '<br />'.translate("Devenez membre privilégié en cliquant").' <a href="user.php?op=only_newuser">'.translate("ici").'</a></p>
                           ';
   global $block_title;
   $title = $block_title=='' ? translate("Qui est en ligne ?") : $block_title;
   themesidebox($title, $content);
}
#autodoc lnlbox() : Bloc Little News-Letter <br />=> syntaxe : function#lnlbox
function lnlbox() {
   global $block_title;
   $title= $block_title=='' ? translate("La lettre") : $block_title;
   $boxstuff = '
         <form id="lnlblock" action="lnl.php" method="get">
            <div class="mb-3">
               <select name="op" class=" form-select">
                  <option value="subscribe">'.translate("Abonnement").'</option>
                  <option value="unsubscribe">'.translate("Désabonnement").'</option>
               </select>
            </div>
            <div class="form-floating mb-3">
               <input type="email" id="email_block" name="email" maxlength="254" class="form-control" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required="required"/>
               <label for="email_block">'.translate("Votre adresse Email").'</label>
               <span class="help-block">'.translate("Recevez par mail les nouveautés du site.").'</span>
            </div>
            <button type="submit" class="btn btn-outline-primary btn-block btn-sm"><i class ="fa fa-check fa-lg me-2"></i>'.translate("Valider").'</button>
         </form>';
   themesidebox($title, $boxstuff);
}
#autodoc searchbox() : Bloc Search-engine <br />=> syntaxe : function#searchbox
function searchbox() {
   global $block_title;
   $title = $block_title=='' ? translate("Recherche") : $block_title ;
   $content ='
                              <form id="searchblock" action="search.php" method="get">
                                 <input class="form-control" type="text" name="query" />
                              </form>
                           ';
   themesidebox($title, $content);
}
function changetoamp($r) { return str_replace('&','&amp;',$r[0]);}//must work from php 4 to 7 !..?..
#autodoc mainblock() : Bloc principal <br />=> syntaxe : function#mainblock
function mainblock() {
   global $NPDS_Prefix;
   $result = sql_query("SELECT title, content FROM ".$NPDS_Prefix."block WHERE id=1");
   list($title, $content) = sql_fetch_row($result);
   global $block_title;
   if ($title=='') $title=$block_title;
   //must work from php 4 to 7 !..?..
   themesidebox(aff_langue($title), aff_langue(preg_replace_callback('#<a href=[^>]*(&)[^>]*>#','changetoamp',$content)));
}
function changetoampadm($r) { return str_replace('&','&amp;',$r[0]);}
#autodoc adminblock() : Bloc Admin <br />=> syntaxe : function#adminblock
function adminblock() {
   $bloc_foncts_A='';
   global $NPDS_Prefix, $admin, $aid, $admingraphic, $adminimg, $admf_ext, $Version_Sub, $Version_Num, $nuke_url;
   if ($admin) {
      $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$aid' LIMIT 1"));
      $R = $Q['radminsuper']==1 ?
         sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 AND f.fetat != '0' ORDER BY f.fcategorie") :
         sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f LEFT JOIN ".$NPDS_Prefix."droits d ON f.fdroits1 = d.d_fon_fid LEFT JOIN ".$NPDS_Prefix."authors a ON d.d_aut_aid =a.aid WHERE f.finterface =1 AND fetat!=0 AND d.d_aut_aid='$aid' AND d.d_droits REGEXP'^1' ORDER BY f.fcategorie");
      while($SAQ=sql_fetch_assoc($R)) {
         $arraylecture = array();
         if (isset($SAQ['fdroits1_descr']) && is_string($SAQ['fdroits1_descr']))
            $arraylecture = explode('|', $SAQ['fdroits1_descr']);
         $cat[]=$SAQ['fcategorie'];
         $cat_n[]=$SAQ['fcategorie_nom'];
         $fid_ar[]=$SAQ['fid'];
         if($SAQ['fcategorie'] == 9)
            $adminico=$adminimg.$SAQ['ficone'].'.'.$admf_ext;
         if ($SAQ['fcategorie'] == 9 and strstr($SAQ['furlscript'],"op=Extend-Admin-SubModule"))
            if (file_exists('modules/'.$SAQ['fnom'].'/'.$SAQ['fnom'].'.'.$admf_ext)) $adminico='modules/'.$SAQ['fnom'].'/'.$SAQ['fnom'].'.'.$admf_ext; else $adminico=$adminimg.'module.'.$admf_ext;
         if ($SAQ['fcategorie'] == 9) {
           if(preg_match('#messageModal#', $SAQ['furlscript']))
             $furlscript = 'data-bs-toggle="modal" data-bs-target="#bl_messageModal"';

         if(preg_match('#mes_npds_\d#', $SAQ['fnom'])) {
            if(!in_array($aid, $arraylecture, true)) {
               $bloc_foncts_A .='
                                 <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="'.$SAQ['fretour_h'].'" data-id="'.$SAQ['fid'].'" data-bs-html="true" '.$furlscript.' >
                                    <img class="adm_img" src="'.$adminico.'" alt="icon_message" loading="lazy" />
                                    <span class="badge bg-danger ms-1">'.$SAQ['fretour'].'</span>
                                 </a>';
            } 
         } else {
            $furlscript = preg_match('#versusModal#', $SAQ['furlscript']) ?
               'data-bs-toggle="modal" data-bs-target="#bl_versusModal"' :
               $SAQ['furlscript'] ;
            if(preg_match('#NPDS#', $SAQ['fretour_h']))
               $SAQ['fretour_h'] = str_replace('NPDS', 'NPDS^', $SAQ['fretour_h']);
             $bloc_foncts_A .='
                                 <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="'.$SAQ['fretour_h'].'" data-id="'.$SAQ['fid'].'" data-bs-html="true" '.$furlscript.' >
                                    <img class="adm_img" src="'.$adminico.'" alt="icon_'.$SAQ['fnom_affich'].'" loading="lazy" />
                                    <span class="badge bg-danger ms-1">'.$SAQ['fretour'].'</span>
                                 </a>';
            }
         }
      }

      $result = sql_query("SELECT title, content FROM ".$NPDS_Prefix."block WHERE id=2");
      list($title, $content) = sql_fetch_row($result);
      global $block_title;
      $title = $title=='' ? $block_title : aff_langue($title) ;
      $content = aff_langue(preg_replace_callback('#<a href=[^>]*(&)[^>]*>#','changetoampadm',$content));

      //==> recuperation
      $messagerie_npds= file_get_contents('https://raw.githubusercontent.com/npds/npds_dune/master/versus.txt');
      $messages_npds = explode("\n", $messagerie_npds);
      array_pop($messages_npds);

      // traitement spécifique car fonction permanente versus
      $versus_info = explode('|', $messages_npds[0]);
      if($versus_info[1] == $Version_Sub and $versus_info[2] == $Version_Num)
         sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1', fretour='', fretour_h='Version NPDS ".$Version_Sub." ".$Version_Num."', furlscript='' WHERE fid='36'");
      else
         sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1', fretour='N', furlscript='data-bs-toggle=\"modal\" data-bs-target=\"#versusModal\"', fretour_h='Une nouvelle version NPDS est disponible !<br />".$versus_info[1]." ".$versus_info[2]."<br />Cliquez pour télécharger.' WHERE fid='36'"); 
      $content .= '
                              <div class="d-flex justify-content-start flex-wrap" id="adm_block">
      '.$bloc_foncts_A;
      if ($Q['radminsuper']==1)
         $content .= '
                                 <a class="btn btn-outline-primary btn-sm me-2 my-1" title="'.translate("Vider la table chatBox").'" data-bs-toggle="tooltip" href="powerpack.php?op=admin_chatbox_write&amp;chatbox_clearDB=OK" ><img src="images/admin/chat.png" class="adm_img" alt="icon clear chat" loading="lazy" />&nbsp;<span class="badge bg-danger ms-1">X</span></a>';
      $content .= '
                              </div>
                              <div class="mt-3">
                                 <small class="text-body-secondary"><i class="fas fa-user-cog fa-2x align-middle"></i> '.$aid.'</small>
                              </div>
                              <div class="modal fade" id="bl_versusModal" tabindex="-1" aria-labelledby="bl_versusModalLabel" aria-hidden="true">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <h5 class="modal-title" id="bl_versusModalLabel"><img class="adm_img me-2" src="images/admin/message_npds.png" alt="icon_" loading="lazy" />'.translate("Version").' NPDS^</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                       </div>
                                       <div class="modal-body">
                                          <p>Vous utilisez NPDS^ '.$Version_Sub.' '.$Version_Num.'</p>
                                          <p>'.translate("Une nouvelle version de NPDS^ est disponible !").'</p>
                                          <p class="lead mt-3">'.$versus_info[1].' '.$versus_info[2].'</p>
                                          <p class="my-3">
                                             <a class="me-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'.$versus_info[2].'.zip" target="_blank" title="" data-bs-toggle="tooltip" data-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.zip</a>
                                             <a class="mx-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'.$versus_info[2].'.tar.gz" target="_blank" title="" data-bs-toggle="tooltip" data-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.tar.gz</a>
                                          </p>
                                       </div>
                                       <div class="modal-footer">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="modal fade" id="bl_messageModal" tabindex="-1" aria-labelledby="bl_messageModalLabel" aria-hidden="true">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <h5 class="modal-title" id=""><span id="bl_messageModalIcon" class="me-2"></span><span id="bl_messageModalLabel"></span></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                       </div>
                                       <div class="modal-body">
                                          <p id="bl_messageModalContent"></p>
                                          <form class="mt-3" id="bl_messageModalForm" action="" method="POST">
                                             <input type="hidden" name="id" id="bl_messageModalId" value="0" />
                                             <button type="submit" class="btn btn btn-primary btn-sm">'.translate("Confirmer la lecture").'</button>
                                          </form>
                                       </div>
                                       <div class="modal-footer">
                                       <span class="small text-body-secondary">Information de npds.org</span><img class="adm_img me-2" src="images/admin/message_npds.png" alt="icon_" loading="lazy" />
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <script>
                                 $(function () {
                                   $("#bl_messageModal").on("show.bs.modal", function (event) {
                                       var button = $(event.relatedTarget); 
                                       var id = button.data("id");
                                       $("#bl_messageModalId").val(id);
                                       $("#bl_messageModalForm").attr("action", "'.$nuke_url.'/admin.php?op=alerte_update");
                                       $.ajax({
                                          url:"'.$nuke_url.'/admin.php?op=alerte_api",
                                          method: "POST",
                                          data:{id:id},
                                          dataType:"JSON",
                                          success:function(data) {
                                             var fnom_affich = JSON.stringify(data["fnom_affich"]),
                                                 fretour_h = JSON.stringify(data["fretour_h"]),
                                                 ficone = JSON.stringify(data["ficone"]);
                                             $("#bl_messageModalLabel").html(JSON.parse(fretour_h));
                                             $("#bl_messageModalContent").html(JSON.parse(fnom_affich));
                                             $("#bl_messageModalIcon").html("<img src=\"images/admin/"+JSON.parse(ficone)+".png\" />");
                                          }
                                       });
                                    });
                                 });
                              </script>
                           ';
      themesidebox($title, $content);
   }
}
#autodoc ephemblock() : Bloc ephemerid <br />=> syntaxe : function#ephemblock
function ephemblock() {
   global $NPDS_Prefix, $gmt;
   $cnt=0;
   $eday=date("d",time()+((integer)$gmt*3600));
   $emonth =date("m",time()+((integer)$gmt*3600));
   $result = sql_query("SELECT yid, content FROM ".$NPDS_Prefix."ephem WHERE did='$eday' AND mid='$emonth' ORDER BY yid ASC");
   $boxstuff = '<div>'.translate("En ce jour...").'</div>';
   while (list($yid, $content) = sql_fetch_row($result)) {
      if ($cnt==1)
         $boxstuff .= "\n<br />\n";
      $boxstuff .= "<b>$yid</b>\n<br />\n";
      $boxstuff .= aff_langue($content);
      $cnt = 1;
   }
   $boxstuff .= "<br />\n";
   global $block_title;
   $title= $block_title=='' ? translate("Ephémérides") : $block_title;
   themesidebox($title, $boxstuff);
}
#autodoc loginbox() : Bloc Login <br />=> syntaxe : function#loginbox
function loginbox() {
   global $user;
   $boxstuff='';
   if (!$user) {
      $boxstuff = '
      <form action="user.php" method="post">
         <div class="mb-3">
            <label for="uname">'.translate("Identifiant").'</label>
            <input class="form-control" type="text" name="uname" maxlength="25" />
         </div>
         <div class="mb-3">
            <label for="pass">'.translate("Mot de passe").'</label>
            <input class="form-control" type="password" name="pass" maxlength="20" />
         </div>
         <div class="mb-3">
            <input type="hidden" name="op" value="login" />
            <button class="btn btn-primary" type="submit">'.translate("Valider").'</button>
         </div>
         <div class="help-block">
         '.translate("Vous n'avez pas encore de compte personnel ? Vous devriez").' <a href="user.php">'.translate("en créer un").'</a>. '.translate("Une fois enregistré").' '.translate("vous aurez certains avantages, comme pouvoir modifier l'aspect du site,").' '.translate("ou poster des commentaires signés...").'
         </div>
      </form>';
      global $block_title;
      $title= $block_title=='' ? translate("Se connecter") : $block_title;
      themesidebox($title, $boxstuff);
   }
}
#autodoc userblock() : Bloc membre <br />=> syntaxe : function#userblock
function userblock() {
   global $NPDS_Prefix, $user,$cookie;
   if (($user) AND ($cookie[8])) {
      $getblock = Q_select("SELECT ublock FROM ".$NPDS_Prefix."users WHERE uid='$cookie[0]'",86400);
      $ublock = $getblock[0];
      global $block_title;
      $title= $block_title=='' ? translate("Menu de").' '.$cookie[1] : $block_title;
      themesidebox($title, $ublock['ublock']);
   }
}
#autodoc topdownload() : Bloc topdownload <br />=> syntaxe : function#topdownload
function topdownload() {
   global $block_title;
   $title= $block_title=='' ? translate("Les plus téléchargés") : $block_title;
   $boxstuff = '
                              <ul>';
   $boxstuff .= topdownload_data('short','dcounter');
   $boxstuff .= '
                              </ul>
                           ';
   if(strpos($boxstuff,'<li') === false) $boxstuff='';
   themesidebox($title, $boxstuff);
}
#autodoc lastdownload() : Bloc lastdownload <br />=> syntaxe : function#lastdownload
function lastdownload() {
   global $block_title;
   $title = $block_title=='' ? translate("Fichiers les + récents") : $block_title;
   $boxstuff = '
                              <ul>';
   $boxstuff .= topdownload_data('short','ddate');
   $boxstuff .= '
                              </ul>
                           ';
   if(strpos($boxstuff,'<li') === false) $boxstuff='';
   themesidebox($title, $boxstuff);
}
#autodoc topdownload_data($form, $ordre) : Bloc topdownload et lastdownload / SOUS-Fonction
function topdownload_data($form, $ordre) {
   global $NPDS_Prefix, $top, $long_chain;
   if (!$long_chain) $long_chain=13;
   settype($top,'integer');
   $result = sql_query("SELECT did, dcounter, dfilename, dcategory, ddate, perms FROM ".$NPDS_Prefix."downloads ORDER BY $ordre DESC LIMIT 0,$top");
   $lugar=1; $ibid='';
   while(list($did, $dcounter, $dfilename, $dcategory, $ddate, $dperm) = sql_fetch_row($result)) {
      if ($dcounter>0) {
         $okfile=autorisation($dperm);
         if ($ordre=='dcounter')
            $dd = wrh($dcounter);
         if ($ordre=='ddate')
            $dd = formatTimes($ddate, IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
         $ori_dfilename=$dfilename;
         if (strlen($dfilename)>$long_chain)
            $dfilename = (substr($dfilename, 0, $long_chain))." ...";
         if ($form=='short') {
            if ($okfile) $ibid.='
                                 <li class="list-group-item list-group-item-action d-flex justify-content-start p-2 flex-wrap">'.$lugar.' <a class="ms-2" href="download.php?op=geninfo&amp;did='.$did.'&amp;out_template=1" title="'.$ori_dfilename.' '.$dd.'" data-bs-toggle="tooltip" >'.$dfilename.'</a><span class="badge bg-secondary ms-auto align-self-center">'.$dd.'</span></li>';
         } else {
            if ($okfile) $ibid.='
                                 <li class="ms-4 my-1"><a href="download.php?op=mydown&amp;did='.$did.'" >'.$dfilename.'</a> ('.translate("Catégorie"). ' : '.aff_langue(stripslashes($dcategory)).')&nbsp;<span class="badge bg-secondary float-end align-self-center">'.wrh($dcounter).'</span></li>';
         }
         if ($okfile)
            $lugar++;
      }
   }
   sql_free_result($result);
   return $ibid;
}
#autodoc oldNews($storynum) : Bloc Anciennes News <br />=> syntaxe <br />function#oldNews<br />params#$storynum,lecture (affiche le NB de lecture) - facultatif
function oldNews($storynum, $typ_aff='') {
   global $oldnum, $storyhome, $categories, $cat, $user, $cookie, $language;
   $boxstuff = '<ul class="list-group">';
   $storynum = isset($cookie[3]) ? $cookie[3] : $storyhome ;

   if (($categories==1) and ($cat!=''))
      $sel = $user ? "WHERE catid='$cat'" : "WHERE catid='$cat' AND ihome=0" ;
   else
      $sel = $user ? '' : "WHERE ihome=0" ;

$sel =  "WHERE ihome=0";// en dur pour test
   $vari=0;
   $xtab=news_aff('old_news', $sel, $storynum, $oldnum);
   $story_limit=0; 
   $time2=0; $a=0;
   while (($story_limit<$oldnum) and ($story_limit<sizeof($xtab))) {
      list($sid, $title, $time, $comments, $counter) = $xtab[$story_limit];
      $story_limit++;
      $date_au_format = formatTimes($time,IntlDateFormatter::FULL);
      $comments = $typ_aff=='lecture' ?
         '<span class="badge rounded-pill bg-secondary ms-1" title="'.translate("Lu").'" data-bs-toggle="tooltip">'.$counter.'</span>' : '' ;
      if ($time2==$date_au_format)
         $boxstuff .= '
                                 <li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center"><a class="n-ellipses" href="article.php?sid='.$sid.'">'.aff_langue($title).'</a>'.$comments.'</li>';
      else {
         if ($a==0) {
            $boxstuff .= '
                                 <li class="list-group-item fs-6">'.$date_au_format.'</li>
                                 <li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center"><a href="article.php?sid='.$sid.'">'.aff_langue($title).'</a>'.$comments.'</li>';
            $time2 = $date_au_format;
            $a = 1;
         } else {
            $boxstuff .= '
                                 <li class="list-group-item fs-6">'.$date_au_format.'</li>
                                 <li class="list-group-item list-group-item-action d-inline-flex justify-content-between align-items-center"><a href="article.php?sid='.$sid.'">'.aff_langue($title).'</a>'.$comments.'</li>';
            $time2 = $date_au_format;
         }
      }
      $vari++;
      if ($vari==$oldnum) {
         //$storynum = isset($cookie[3]) ? $cookie[3] : $storyhome ;
         $min = $oldnum;// + $storynum;
         $boxstuff .= '
                                 <li class="text-center mt-3"><a href="search.php?min='.$min.'&amp;type=stories&amp;category='.$cat.'"><strong>'.translate("Articles plus anciens").'</strong></a></li>';
      }
   }
   $boxstuff .='
                              </ul>';
   if(strpos($boxstuff,'<li') === false) $boxstuff='';
   global $block_title;
   $boxTitle = $block_title=='' ? translate("Anciens articles") : $block_title ;
   themesidebox($boxTitle, $boxstuff);
}
#autodoc bigstory() : Bloc BigStory <br />=> syntaxe : function#bigstory
function bigstory() {
   global $cookie;//no need ?
   $content ='';
   $tdate = getPartOfTime(time(), 'yyyy-MM-dd');
   $xtab = news_aff("big_story","WHERE (time LIKE '%$tdate%')",1,1);
   if (sizeof($xtab))
      list($fsid, $ftitle) = $xtab[0];
   else {
      $fsid=''; $ftitle='';
   }
   $content .= ($fsid =='' and $ftitle =='') ?
      '<span class="fw-semibold">'.translate("Il n'y a pas encore d'article du jour.").'</span>' :
      '<span class="fw-semibold">'.translate("L'article le plus consulté aujourd'hui est :").'</span><br /><br /><a href="article.php?sid='.$fsid.'">'.aff_langue($ftitle).'</a>' ;
   global $block_title;
   $boxtitle = $block_title=='' ? translate("Article du Jour") : $block_title;
   themesidebox($boxtitle, $content);
}
#autodoc category() : Bloc de gestion des catégories <br />=> syntaxe : function#category
function category() {
   global $NPDS_Prefix, $cat, $language;
   $result = sql_query("SELECT catid, title FROM ".$NPDS_Prefix."stories_cat ORDER BY title");
   $numrows = sql_num_rows($result);
   if ($numrows == 0)
      return;
   else {
      $boxstuff = '<ul>';
      while (list($catid, $title) = sql_fetch_row($result)) {
         $result2 = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories WHERE catid='$catid' LIMIT 0,1");
         $numrows = sql_num_rows($result2);
         if ($numrows > 0) {
            $res = sql_query("SELECT time FROM ".$NPDS_Prefix."stories WHERE catid='$catid' ORDER BY sid DESC LIMIT 0,1");
            list($time) = sql_fetch_row($res);
            $boxstuff .= $cat == $catid ?
               '<li class="my-2"><strong>'.aff_langue($title).'</strong></li>' :
               '<li class="list-group-item list-group-item-action hyphenate my-2"><a href="index.php?op=newcategory&amp;catid='.$catid.'" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="'.translate("Dernière contribution").' <br />'.formatTimes($time).' ">'.aff_langue($title).'</a></li>' ;
         }
      }
      $boxstuff .= '</ul>';
      global $block_title;
      $title = $block_title=='' ? translate("Catégories") : $block_title;
      themesidebox($title, $boxstuff);
   }
}
#autodoc headlines() : Bloc HeadLines <br />=> syntaxe :<br />function#headlines<br />params#ID_du_canal
function headlines($hid='', $block=true) {
   global $NPDS_Prefix, $Version_Num, $Version_Id, $rss_host_verif, $long_chain;

   if (file_exists("proxy.conf.php"))
      include("proxy.conf.php");
   if ($hid=='')
      $result = sql_query("SELECT sitename, url, headlinesurl, hid FROM ".$NPDS_Prefix."headlines WHERE status=1");
   else
      $result = sql_query("SELECT sitename, url, headlinesurl, hid FROM ".$NPDS_Prefix."headlines WHERE hid='$hid' AND status=1");

   while (list($sitename, $url, $headlinesurl, $hid) = sql_fetch_row($result)) {
      $boxtitle = $sitename;
      $cache_file = 'cache/'.preg_replace('[^a-z0-9]','',strtolower($sitename)).'_'.$hid.'.cache';
      $cache_time = 1200;//3600 origine
      $items = 0;
      $max_items = 6;
      $rss_timeout = 15;
      $rss_font = '<span class="small">';
      if ( (!(file_exists($cache_file))) or (filemtime($cache_file)<(time()-$cache_time)) or (!(filesize($cache_file))) ) {
         $rss=parse_url($url);
         if ($rss_host_verif==true) {
            $verif = fsockopen($rss['host'], 80, $errno, $errstr, $rss_timeout);
            if ($verif) {
               fclose($verif);
               $verif=true;
            }
         } else
            $verif=true;
         if (!$verif) {
            $cache_file_sec=$cache_file.".security";
            if (file_exists($cache_file))
               $ibid=rename($cache_file, $cache_file_sec);
            themesidebox($boxtitle, "Security Error");
            return;
         } else {
            if (!$long_chain) $long_chain=15;
            $fpwrite = fopen($cache_file, 'w');
            if ($fpwrite) {
               fputs($fpwrite, "<ul>\n");
               $flux = simplexml_load_file($headlinesurl,'SimpleXMLElement', LIBXML_NOCDATA);
               $namespaces = $flux->getNamespaces(true); // get namespaces
               $ic='';
               //ATOM//
               if($flux->entry) {
                  $j=0;
                  $cont='';
                  foreach ($flux->entry as $entry) {
                     if($entry->content) $cont=(string) $entry->content;
                     fputs($fpwrite,'<li><a href="'.(string)$entry->link['href'].'" target="_blank" >'.(string) $entry->title.'</a><br />'.$cont.'</li>');
                     if($j==$max_items) break;
                     $j++;
                  }
               }

               if($flux->{'item'}) {
                  $j=0;
                  $cont='';
                  foreach ($flux->item as $item) {
                     if($item->description) $cont=(string) $item->description;
                     fputs($fpwrite,'<li><a href="'.(string)$item->link['href'].'"  target="_blank" >'.(string) $item->title.'</a><br /></li>');
                     if($j==$max_items) break;
                     $j++;
                  }
               }
               //RSS
               if($flux->{'channel'}) {
               $j=0;
               $cont='';
                  foreach ($flux->channel->item as $item) {
                     if($item->description) $cont=(string) $item->description;
                     fputs($fpwrite,'<li><a href="'.(string)$item->link.'"  target="_blank" >'.(string) $item->title.'</a><br />'.$cont.'</li>');
                     if($j==$max_items) break;
                     $j++;
                  }
               }

               $j=0;
               if($flux->image) $ico='<img class="img-fluid" src="'.$flux->image->url.'" />&nbsp;'; 
               foreach ($flux->item as $item) {
                  fputs($fpwrite,'<li>'.$ico.'<a href="'.(string) $item->link.'" target="_blank" >'.(string) $item->title.'</a></li>');
                  if($j==$max_items) break;
                  $j++;
               }

               fputs($fpwrite, "\n".'</ul>');
               fclose($fpwrite);
            }
         }
      }
      if (file_exists($cache_file)) {
         ob_start();
         $ibid=readfile($cache_file);
         $boxstuff=$rss_font.ob_get_contents().'</span>';
         ob_end_clean();
      }
      $boxstuff .= '
            <div class="text-end"><a href="'.$url.'" target="_blank">'.translate("Lire la suite...").'</a></div>';
      if ($block) {
         themesidebox($boxtitle, $boxstuff);
         $boxstuff='';
      } else
         return ($boxstuff);
   }
}
#autodoc PollNewest() : Bloc Sondage <br />=> syntaxe : <br />function#pollnewest<br />params#ID_du_sondage OU vide (dernier sondage créé)
function PollNewest(?int $id=null) : void {
   global $NPDS_Prefix;
   // snipe : multi-poll evolution
   if ($id!=0) {
      settype($id, "integer");
      list($ibid,$pollClose)=pollSecur($id);
      if ($ibid) pollMain($ibid,$pollClose);
   } elseif ($result = sql_query("SELECT pollID FROM ".$NPDS_Prefix."poll_data ORDER BY pollID DESC LIMIT 1")) {
      list($pollID)=sql_fetch_row($result);
      list($ibid,$pollClose)=pollSecur($pollID);
      if ($ibid) pollMain($ibid,$pollClose);
   }
}
#autodoc bloc_langue() : Bloc langue <br />=> syntaxe : function#bloc_langue
function bloc_langue() {
   global $block_title, $multi_langue ;
   if($multi_langue) {
      $title = $block_title=='' ? translate("Choisir une langue") : $block_title;
      themesidebox($title,aff_local_langue("index.php", "choice_user_language", ''));
   }
}
#autodoc bloc_rubrique() : Bloc des Rubriques <br />=> syntaxe : function#bloc_rubrique
function bloc_rubrique() {
   global $NPDS_Prefix, $language, $user;
   $result = sql_query("SELECT rubid, rubname, ordre FROM ".$NPDS_Prefix."rubriques WHERE enligne='1' AND rubname<>'divers' ORDER BY ordre");
   $boxstuff = '<ul>';
   while (list($rubid, $rubname) = sql_fetch_row($result)) {
      $title=aff_langue($rubname);
      $result2 = sql_query("SELECT secid, secname, userlevel, ordre FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid' ORDER BY ordre");
      $boxstuff.='<li><strong>'.$title.'</strong></li>';
      //$ibid++;//??? only for notice ???
      while (list($secid, $secname, $userlevel) = sql_fetch_row($result2)) {
         $query3 = "SELECT artid FROM ".$NPDS_Prefix."seccont WHERE secid='$secid'";
         $result3 = sql_query($query3);
         $nb_article = sql_num_rows($result3);
         if ($nb_article>0) {
            $boxstuff.='<ul>';
            $tmp_auto=explode(',',$userlevel);
            foreach($tmp_auto as $userlevel) {
               $okprintLV1=autorisation($userlevel);
               if ($okprintLV1) break;
            }
            if ($okprintLV1) {
               $sec=aff_langue($secname);
               $boxstuff.= '<li><a href="sections.php?op=listarticles&amp;secid='.$secid.'">'.$sec.'</a></li>';
            }
           $boxstuff.='</ul>';
         }
      }
   }
   $boxstuff .='</ul>';
   global $block_title;
   $title = $block_title=='' ? translate("Rubriques") : $block_title;
   themesidebox($title, $boxstuff);
}
#autodoc espace_groupe() : Bloc du WorkSpace <br />=> syntaxe :<br />function#bloc_espace_groupe<br />params#ID_du_groupe, Aff_img_groupe(0 ou 1) / Si le bloc n'a pas de titre, Le nom du groupe sera utilisé
function bloc_espace_groupe($gr, $i_gr) {
   global $NPDS_Prefix, $block_title ;
   if ($block_title=='') {
      $rsql=sql_fetch_assoc(sql_query("SELECT groupe_name FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gr'"));
      $title=$rsql['groupe_name'];
   } else
      $title=$block_title;
   themesidebox($title, fab_espace_groupe($gr, "0", $i_gr));
}
function fab_espace_groupe($gr, $t_gr, $i_gr) {
   global $NPDS_Prefix, $short_user, $dblink;
   $rsql=sql_fetch_assoc(sql_query("SELECT groupe_id, groupe_name, groupe_description, groupe_forum, groupe_mns, groupe_chat, groupe_blocnote, groupe_pad FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gr'"));
   $content='
                              <script type="text/javascript">
                              //<![CDATA[
                                 //==> chargement css
                                 if (!document.getElementById(\'bloc_ws_css\')) {
                                    var l_css = document.createElement(\'link\');
                                    l_css.href = "modules/groupe/bloc_ws.css";
                                    l_css.rel = "stylesheet";
                                    l_css.id = "bloc_ws_css";
                                    l_css.type = "text/css";
                                    document.getElementsByTagName("head")[0].appendChild(l_css);
                                 }
                              //]]>
                              </script>';

   $content.='
                              <div id="bloc_ws_'.$gr.'">';
   if ($t_gr==1) 
      $content.= '
                                 <span style="font-size: 120%; font-weight:bolder;">'.aff_langue($rsql['groupe_name']).'</span>'."\n";
   $content.='
                                 <p>'.aff_langue($rsql['groupe_description']).'</p>';
   if (file_exists('users_private/groupe/'.$gr.'/groupe.png') and ($i_gr==1)) 
      $content.='
                                 <img src="users_private/groupe/'.$gr.'/groupe.png" class="img-fluid mx-auto d-block rounded" alt="'.translate("Groupe").'" loading="lazy" />';
   //=> liste des membres
   $mysql_version = mysqli_get_server_info($dblink);
   $query = "SELECT uid, groupe FROM ".$NPDS_Prefix."users_status WHERE ";
   $query .= (version_compare($mysql_version, '8.0.4', '>=')) ?
      "groupe REGEXP '\\\\b$gr\\\\b'" :
      "groupe REGEXP '[[:<:]]".$gr."[[:>:]]'";
   $query .= " ORDER BY uid ASC";
   $result = sql_query($query);
   $li_mb=''; $li_ic='';
   $nb_mb=sql_num_rows ($result);
   $count=0;
   $li_mb.='
                                 <div class="my-4">
                                    <a data-bs-toggle="collapse" data-bs-target="#lst_mb_ws_'.$gr.'" class="text-primary" id="show_lst_mb_ws_'.$gr.'" title="'.translate("Déplier la liste").'"><i id="i_lst_mb_ws_'.$gr.'" class="toggle-icon fa fa-caret-down fa-2x" >&nbsp;</i></a><i class="fa fa-users fa-2x text-body-secondary ms-3 align-middle" title="'.translate("Liste des membres du groupe.").'" data-bs-toggle="tooltip"></i>&nbsp;<a href="memberslist.php?gr_from_ws='.$gr.'" class="text-uppercase">'.translate("Membres").'</a><span class="badge bg-secondary float-end">'.$nb_mb.'</span>';
   $tab=online_members();
   $li_mb.='
                                    <ul id="lst_mb_ws_'.$gr.'" class="list-group ul_bloc_ws collapse">';
   while(list($uid, $groupe) = sql_fetch_row($result)) {
      $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
      if (!$short_user) {
         include_once('functions.php');
         $posterdata_extend = get_userdata_extend_from_id($uid);
         include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
         if ($posterdata_extend['M2']!='') {
            $socialnetworks= explode(';',$posterdata_extend['M2']);
            foreach ($socialnetworks as $socialnetwork) {
               $res_id[] = explode('|',$socialnetwork);
            }
            sort($res_id);
            sort($rs);
            foreach ($rs as $v1) {
               foreach($res_id as $y1) {
                  $k = array_search( $y1[0],$v1);
                  if (false !== $k) {
                     $my_rs.='<a class="me-2" href="';
                     if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                     $my_rs.= '" target="_blank"><i class="fab fa-'.$v1[2].' fa-lg fa-fw mb-2"></i></a> ';
                     break;
                  } 
                  else $my_rs.='';
               }
            }
            $my_rsos[]=$my_rs;
         }
         else $my_rsos[]='';
      }
   
      list($uname, $user_avatar, $mns, $url, $femail)=sql_fetch_row(sql_query("SELECT uname, user_avatar, mns, url, femail FROM ".$NPDS_Prefix."users WHERE uid='$uid'"));

      include('modules/geoloc/geoloc.conf');
      settype($ch_lat,'string');
      $useroutils = '';
      if ($uid!= 1 and $uid!='')
         $useroutils .= '<a class="list-group-item text-primary" href="user.php?op=userinfo&amp;uname='.$uname.'" target="_blank" title="'.translate("Profil").'" data-bs-toggle="tooltip"><i class="fa fa-2x fa-user align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Profil").'</span></a>';
      if ($uid!= 1)
         $useroutils .= '<a class="list-group-item text-primary" href="powerpack.php?op=instant_message&amp;to_userid='.$uname.'" title="'.translate("Envoyer un message interne").'" data-bs-toggle="tooltip"><i class="far fa-2x fa-envelope align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Message").'</span></a>';
      if ($femail!='')
         $useroutils .= '<a class="list-group-item text-primary" href="mailto:'.anti_spam($femail,1).'" target="_blank" title="'.translate("Email").'" data-bs-toggle="tooltip"><i class="fas fa-at fa-2x align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Email").'</span></a>';
      if ($url!='')
         $useroutils .= '<a class="list-group-item text-primary" href="'.$url.'" target="_blank" title="'.translate("Visiter ce site web").'" data-bs-toggle="tooltip"><i class="fas fa-2x fa-external-link-alt align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Visiter ce site web").'</span></a>';
      if ($mns)
         $useroutils .= '<a class="list-group-item text-primary" href="minisite.php?op='.$uname.'" target="_blank" target="_blank" title="'.translate("Visitez le minisite").'" data-bs-toggle="tooltip"><i class="fa fa-2x fa-desktop align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Visitez le minisite").'</span></a>';
      if (!$short_user)
         if ($posterdata_extend[$ch_lat] !='')
            $useroutils .= '<a class="list-group-item text-primary" href="modules.php?ModPath=geoloc&amp;ModStart=geoloc&op=u'.$uid.'" title="'.translate("Localisation").'" ><i class="fas fa-map-marker-alt fa-2x align-middle fa-fw"></i><span class="ms-2 d-none d-sm-inline">'.translate("Localisation").'</span></a>';

      $conn= '<i class="fa fa-plug text-body-secondary" title="'.$uname.' '.translate("n'est pas connecté").'" data-bs-toggle="tooltip" ></i>';
      if (!$user_avatar)
         $imgtmp="images/forum/avatar/blank.gif";
      else if (stristr($user_avatar,"users_private"))
         $imgtmp=$user_avatar;
      else {
         if ($ibid=theme_image("forum/avatar/$user_avatar")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/$user_avatar";}
         if (!file_exists($imgtmp)) {$imgtmp="images/forum/avatar/blank.gif";}
      }
      $timex=false;
      for ($i = 1; $i <= $tab[0]; $i++) {
         if ($tab[$i]['username']==$uname)
            $timex=time()-$tab[$i]['time'];
      }
      if (($timex!==false) and ($timex<60))
         $conn= '<i class="fa fa-plug faa-flash animated text-primary" title="'.$uname.' '.translate("est connecté").'" data-bs-toggle="tooltip" ></i>';
      $li_ic.='<img class="n-smil" src="'.$imgtmp.'" alt="avatar" loading="lazy" />';
      $li_mb.= '
                                       <li class="list-group-item list-group-item-action d-flex flex-row p-2">
                                          <div id="li_mb_'.$uname.'_'.$gr.'" class="n-ellipses">
                                             '.$conn.'<a class="ms-2" tabindex="0" data-bs-title="'.$uname.'" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-content=\'<div class="list-group mb-3">'.$useroutils.'</div><div class="mx-auto text-center" style="max-width:170px;">';
      if (!$short_user)
         $li_mb.= $my_rsos[$count];
      $li_mb.= '</div>\'>
                                             <img class=" btn-outline-primary img-thumbnail img-fluid n-ava-small " src="'.$imgtmp.'" alt="avatar" title="'.$uname.'" loading="lazy" /></a>
                                             <span class="ms-2">'.$uname.'</span>
                                          </div>
                                       </li>';
      $count++;
   }
   $li_mb.='
                                       <li style="clear:left;line-height:6px; background:none;">&nbsp;</li>
                                       <li class="list-group-item" style="clear:left;line-height:24px;padding:6px; margin-top:0px;">'.$li_ic.'</li>
                                    </ul>
                                 </div>';
   $content.= $li_mb;
   //<== liste des membres

   //=> Forum
   $lst_for='';$lst_for_tog='';$nb_for_gr='';
   if ($rsql['groupe_forum'] == 1) {
      $res_forum=sql_query("SELECT forum_id, forum_name FROM ".$NPDS_Prefix."forums WHERE forum_pass REGEXP '$gr'");
      $nb_foru=sql_num_rows ($res_forum);
      if ($nb_foru >= 1) {
         $lst_for_tog='<a data-bs-toggle="collapse" data-bs-target="#lst_for_gr_'.$gr.'" class="text-primary" id="show_lst_for_'.$gr.'" title="'.translate("Déplier la liste").'" ><i id="i_lst_for_gr_'.$gr.'" class="toggle-icon fa fa-caret-down fa-2x" >&nbsp;</i></a>';
         $lst_for.='
                                    <ul id="lst_for_gr_'.$gr.'" class="list-group ul_bloc_ws collapse" style ="list-style-type:none;">';
         $nb_for_gr='  <span class="badge bg-secondary float-end">'.$nb_foru.'</span>';
         while(list($id_fo,$fo_name) = sql_fetch_row($res_forum)) {
            $lst_for.='
                                       <li class="list-group-item list-group-item-action"><a href="viewforum.php?forum='.$id_fo.'">'.$fo_name.'</a></li>';
         }
         $lst_for.='
                                    </ul>';
      }
      $content.='
                                 <hr />
                                 <div>
                                    '.$lst_for_tog.'<i class="fa fa-list-alt fa-2x text-body-secondary ms-3 align-middle" title="'.translate("Groupe").'('.$gr.'): '.translate("forum").'." data-bs-toggle="tooltip" ></i>&nbsp;<a class="text-uppercase" href="forum.php">'.translate("Forum").'</a>'.$nb_for_gr.$lst_for.'
                                 </div>';
   }
   //=> wspad
   if ($rsql['groupe_pad'] == 1) {
      settype($lst_doc,'string');
      settype($nb_doc_gr,'string');
      settype($lst_doc_tog,'string');
      include("modules/wspad/config.php");
      $docs_gr=sql_query("SELECT page, editedby, modtime, ranq FROM ".$NPDS_Prefix."wspad WHERE (ws_id) IN (SELECT MAX(ws_id) FROM ".$NPDS_Prefix."wspad WHERE member='$gr' GROUP BY page) ORDER BY page ASC");
      $nb_doc=sql_num_rows ($docs_gr);
      if ($nb_doc >= 1) {
         $lst_doc_tog ='<a data-bs-toggle="collapse" data-bs-target="#lst_doc_gr_'.$gr.'" class="text-primary" id="show_lst_doc_'.$gr.'" title="'.translate("Déplier la liste").'"><i id="i_lst_doc_gr_'.$gr.'" class="toggle-icon fa fa-caret-down fa-2x" >&nbsp;</i></a>';
         $lst_doc.='
                                    <ul id="lst_doc_gr_'.$gr.'" class="list-group ul_bloc_ws mt-3 collapse">';
         $nb_doc_gr='  <span class="badge bg-secondary float-end">'.$nb_doc.'</span>';
         while (list($p,$e,$m,$r)=sql_fetch_row($docs_gr)) {
            $surlignage=$couleur[hexfromchr($e)];
            $lst_doc.='
                                       <li class="list-group-item list-group-item-action px-1 py-3" style="line-height:14px;"><div id="last_editor_'.$p.'" data-bs-toggle="tooltip" data-bs-placement="right" title="'.translate("Dernier éditeur").' : '.$e.' '.formatTimes($m, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT).'" style="float:left; width:1rem; height:1rem; background-color:'.$surlignage.'"></div><i class="fa fa-edit text-body-secondary mx-1" data-bs-toggle="tooltip" title="'.translate("Document co-rédigé").'." ></i><a href="modules.php?ModPath=wspad&amp;ModStart=wspad&amp;op=relo&amp;page='.$p.'&amp;member='.$gr.'&amp;ranq='.$r.'">'.$p.'</a></li>';
         }
         $lst_doc.='
                                    </ul>';
      }
      $content.='
                                 <hr />
                                 <div>
                                    '. $lst_doc_tog.'<i class="fa fa-edit fa-2x text-body-secondary ms-3 align-middle" title="'.translate("Co-rédaction").'" data-bs-toggle="tooltip" data-bs-placement="right"></i>&nbsp;<a class="text-uppercase" href="modules.php?ModPath=wspad&ModStart=wspad&member='.$gr.'" >'.translate("Co-rédaction").'</a>'.$nb_doc_gr.$lst_doc.'
                                 </div>';
   }
   //<= wspad
   
   //=> bloc-notes
   if ($rsql['groupe_blocnote'] == 1) {
      settype($lst_blocnote_tog,'string');
      settype($lst_blocnote,'string');
      include_once("modules/bloc-notes/bloc-notes.php");
      $lst_blocnote_tog ='
                                    <a data-bs-toggle="collapse" data-bs-target="#lst_blocnote_'.$gr.'" class="text-primary" id="show_lst_blocnote" title="'.translate("Déplier la liste").'"><i id="i_lst_blocnote" class="toggle-icon fa fa-caret-down fa-2x" >&nbsp;</i></a><i class="far fa-sticky-note fa-2x text-body-secondary ms-3 align-middle"></i>&nbsp;<span class="text-uppercase">Bloc note</span>';
      $lst_blocnote ='
                                    <div id="lst_blocnote_'.$gr.'" class="mt-3 collapse">
                                    '.blocnotes("shared", 'WS-BN'.$gr,'','7','bg-dark text-light',false).'
                                    </div>';
      $content.='
                                 <hr />
                                 <div class="mb-2">'.$lst_blocnote_tog.$lst_blocnote.'
                                 </div>';
   }
   //<= bloc-notes

   $content.='
                              <div class="px-1 card card-body d-flex flex-row mt-3 flex-wrap text-center">';
   //=> Filemanager
   if (file_exists('modules/f-manager/users/groupe_'.$gr.'.conf.php'))
      $content.='
                                 <a class="mx-2" href="modules.php?ModPath=f-manager&amp;ModStart=f-manager&amp;FmaRep=groupe_'.$gr.'" title="'.translate("Gestionnaire fichiers").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-folder fa-2x"></i></a>';
   //=> Minisite
   if ($rsql['groupe_mns'] == 1)
      $content.='
                                 <a class="mx-2" href="minisite.php?op=groupe/'.$gr.'" target="_blank" title= "'.translate("MiniSite").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-desktop fa-2x"></i></a>';
   //=> Chat
   settype($chat_img,'string');
   if ($rsql['groupe_chat'] == 1) {
      $PopUp = JavaPopUp("chat.php?id=$gr&amp;auto=".encrypt(serialize ($gr)),"chat".$gr,380,480);
      if (array_key_exists('chat_info_'.$gr, $_COOKIE))
         if ($_COOKIE['chat_info_'.$gr]) $chat_img='faa-pulse animated faa-slow';
      $content.='
                                 <a class="mx-2" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Ouvrir un salon de chat pour le groupe.").'" data-bs-toggle="tooltip" data-bs-placement="right" ><i class="fa fa-comments fa-2x '.$chat_img.'"></i></a>';
   }
   //=> admin
   if (autorisation(-127))
      $content.='
                                 <a class="mx-2" href="admin.php?op=groupes" ><i title="'.translate("Gestion des groupes.").'" data-bs-toggle="tooltip" class="fa fa-cogs fa-2x"></i></a>';
   $content.='
                              </div>
                           </div>
                        ';
   return ($content);
}
#autodoc bloc_groupes() : Bloc des groupes <br />=> syntaxe :<br />function#bloc_groupes<br />params#Aff_img_groupe(0 ou 1) / Si le bloc n'a pas de titre, 'Les groupes' sera utilisé. Liste des groupes AVEC membres et lien pour demande d'adhésion pour l'utilisateur.
function bloc_groupes($im) {
   global $block_title, $user;
   $title = $block_title=='' ? 'Les groupes' : $block_title ; 
   themesidebox($title, fab_groupes_bloc($user,$im));
}
function fab_groupes_bloc($user,$im) {
   global $NPDS_Prefix; $user;
   $lstgr = array();
   $userdata = explode(':', base64_decode($user));
   $result=sql_query("SELECT DISTINCT `groupe` FROM ".$NPDS_Prefix."`users_status` WHERE `groupe` > 1;");
   while(list($groupe) = sql_fetch_row($result)) {
      $pos = strpos($groupe, ',');
      if ($pos === false)
        $lstgr[] = $groupe;
      else {
         $arg = explode(',', $groupe);
         foreach($arg as $v){
            if(!in_array($v, $lstgr, true))
               $lstgr[] = $v;
         }
      }
   }
   $ids_gr = join("','",$lstgr);
   sql_free_result($result);
   $result=sql_query("SELECT groupe_id, groupe_name, groupe_description FROM `".$NPDS_Prefix."groupes` WHERE groupe_id IN ('$ids_gr')");
   $nb_groupes = sql_num_rows($result);
   $content = '
      <div id="bloc_groupes" class="">
         <ul id="lst_groupes" class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-start px-0">
               <div class="me-auto">
                  <div class="fw-bold"><i class="fa fa-users fa-2x text-body-secondary me-2"></i>'.translate('Groupes').'</div>';
   $content .= $nb_groupes>0 ? translate('Groupe ouvert') : translate('Pas de groupe ouvert') ;
   $content .= '
               </div>
               <span class="badge bg-primary rounded-pill">'.$nb_groupes.'</span>
            </li>';
   while(list($groupe_id, $groupe_name, $groupe_description) = sql_fetch_row($result)) {
      $content .= '
            <li class="list-group-item px-0">'.$groupe_name.'<div class="small">'.$groupe_description.'</div>';
      $content .= $im == 1 ? '<div class="text-center my-2"><img class="img-fluid" src="users_private/groupe/'.$groupe_id.'/groupe.png" loading="lazy"></div>' : '' ;
      if(!file_exists('users_private/groupe/ask4group_'.$userdata[0].'_'.$groupe_id.'_.txt') and !autorisation($groupe_id))
         if(!autorisation(-1))
            $content .= '<div class="text-end small"><a href="user.php?op=askforgroupe&amp;askedgroup='.$groupe_id.'" title="'.translate('Envoi une demande aux administrateurs pour rejoindre ce groupe. Un message privé vous informera du résultat de votre demande.').'" data-bs-toggle="tooltip">'.translate('Rejoindre ce groupe').'</a></div>';
      $content .= '</li>';
   }
   $content .= '
         </ul>';
   if (autorisation(-127))
      $content.='
         <div class="text-end"><a class="mx-2" href="admin.php?op=groupes" ><i title="'.translate("Gestion des groupes.").'" data-bs-toggle="tooltip" data-bs-placement="left" class="fa fa-cogs fa-lg"></i></a></div>';
   $content.='
      </div>';
   sql_free_result($result);
   return($content);
}
#autodoc:</Mainfile.php>

#autodoc theme_image($theme_img) : Retourne le chemin complet si l'image est trouvée dans le répertoire image du thème sinon false
function theme_image($theme_img) {
    global $theme;
    if (@file_exists("themes/$theme/images/$theme_img"))
       return ("themes/$theme/images/$theme_img");
    return false;
}
#autodoc import_css_javascript($tmp_theme, $language, $fw_css, $css_pages_ref, $css) : recherche et affiche la CSS (site, langue courante ou par défaut) / Charge la CSS complémentaire / le HTML ne contient que de simple quote pour être compatible avec javascript
function import_css_javascript($tmp_theme, $language, $fw_css, $css_pages_ref='', $css='') {
   $tmp='';
   // CSS framework
   if (file_exists("themes/_skins/$fw_css/bootstrap.min.css"))
      $tmp.="      <link href='themes/_skins/$fw_css/bootstrap.min.css' rel='stylesheet' type='text/css' media='all' />";
   // CSS standard 
   if (file_exists("themes/$tmp_theme/style/$language-style.css")) {
      $tmp.="
      <link href='themes/$tmp_theme/style/$language-style.css' title='default' rel='stylesheet' type='text/css' media='all' />";
      if (file_exists("themes/$tmp_theme/style/$language-style-AA.css"))
         $tmp.="
      <link href='themes/$tmp_theme/style/$language-style-AA.css' title='alternate stylesheet' rel='alternate stylesheet' type='text/css' media='all' />";
      if (file_exists("themes/$tmp_theme/style/$language-print.css"))
         $tmp.="
      <link href='themes/$tmp_theme/style/$language-print.css' rel='stylesheet' type='text/css' media='print' />";
   } else if (file_exists("themes/$tmp_theme/style/style.css")) {
      $tmp.="
      <link href='themes/$tmp_theme/style/style.css' title='default' rel='stylesheet' type='text/css' media='all' />";
      if (file_exists("themes/$tmp_theme/style/style-AA.css"))
         $tmp.="
     <link href='themes/$tmp_theme/style/style-AA.css' title='alternate stylesheet' rel='alternate stylesheet' type='text/css' media='all' />";
      if (file_exists("themes/$tmp_theme/style/print.css"))
         $tmp.="
     <link href='themes/$tmp_theme/style/print.css' rel='stylesheet' type='text/css' media='print' />";
   } else
      $tmp.="
     <link href='themes/default/style/style.css' title='default' rel='stylesheet' type='text/css' media='all' />";
   // Chargeur CSS spécifique
   if ($css_pages_ref) {
      include ("themes/pages.php");
      if (is_array($PAGES[$css_pages_ref]['css'])) {
         foreach ($PAGES[$css_pages_ref]['css'] as $tab_css) {
            $admtmp='';
            $op=substr($tab_css,-1);
            if ($op=='+' or $op=='-')
               $tab_css=substr($tab_css,0,-1);
            if (stristr($tab_css, 'http://')||stristr($tab_css, 'https://'))
               $admtmp="
      <link href='$tab_css' rel='stylesheet' type='text/css' media='all' />";
            else {
               if (file_exists("themes/$tmp_theme/style/$tab_css") and ($tab_css!=''))
                  $admtmp="
      <link href='themes/$tmp_theme/style/$tab_css' rel='stylesheet' type='text/css' media='all' />";
               elseif (file_exists("$tab_css") and ($tab_css!=''))
                  $admtmp="
      <link href='$tab_css' rel='stylesheet' type='text/css' media='all' />";
            }
            if ($op=='-')
               $tmp =$admtmp;
            else
               $tmp.=$admtmp;
         }
      }
      else {
      $oups=$PAGES[$css_pages_ref]['css'];
         settype($oups, 'string');
         $op=substr($oups,-1);
         $css=substr($oups,0,-1);
         if (($css!='') and (file_exists("themes/$tmp_theme/style/$css"))) {
            if ($op=='-')
               $tmp ="
      <link href='themes/$tmp_theme/style/$css' rel='stylesheet' type='text/css' media='all' />";
            else
               $tmp.="
      <link href='themes/$tmp_theme/style/$css' rel='stylesheet' type='text/css' media='all' />";
         }
      }
   }
   return($tmp);
}
#autodoc import_css($tmp_theme, $language, $fw_css, $css_pages_ref, $css) : Fonctionnement identique à import_css_javascript sauf que le code HTML en retour ne contient que de double quote
function import_css($tmp_theme, $language, $fw_css, $css_pages_ref, $css) {
   return (str_replace("'","\"",import_css_javascript($tmp_theme, $language, $fw_css, $css_pages_ref, $css)));
}
#autodoc auto_complete ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache) : fabrique un array js à partir de la requete sql et implente un auto complete pour l'input (dependence : jquery.min.js ,jquery-ui.js) $nom_array_js=> nom du tableau javascript; $nom_champ=>nom de champ bd; $nom_tabl=>nom de table bd,$id_inpu=> id de l'input,$temps_cache=>temps de cache de la requête. Si $id_inpu n'est pas défini retourne un array js.
function auto_complete($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache) {
   global $NPDS_Prefix;

   $list_json='';
   $list_json.='var '.$nom_array_js.' = [';
   $res = Q_select("SELECT ".$nom_champ." FROM ".$NPDS_Prefix.$nom_tabl,$temps_cache);
   foreach($res as $ar_data) {
      foreach ($ar_data as $val_champ) {
         if($id_inpu =='')
            $list_json.='"'.base64_encode($val_champ).'",';
         else
            $list_json.='"'.$val_champ.'",';
      }
   }
   $list_json= rtrim($list_json,',');
   $list_json.='];';
   $scri_js ='';
   if($id_inpu =='')
      $scri_js .= $list_json;
   else {
      $scri_js.='
   <script type="text/javascript">
   //<![CDATA[
      $(function() {
      '.$list_json;
      if($id_inpu !='')
         $scri_js .= '
      $( "#'.$id_inpu.'" ).autocomplete({
         source: '.$nom_array_js.'
       });';
      $scri_js .= '
      });
   //]]>
   </script>';
   }
   return ($scri_js);
}
#autodoc auto_complete_multi ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req) : fabrique un pseudo array json à partir de la requete sql et implente un auto complete pour le champ input (dependence : jquery-2.1.3.min.js ,jquery-ui.js)
function auto_complete_multi($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req) {
   global $NPDS_Prefix;

   $list_json='';
   $list_json.= $nom_array_js.' = [';
   $res = sql_query("SELECT ".$nom_champ." FROM ".$NPDS_Prefix.$nom_tabl." ".$req);
   while (list($nom_champ) = sql_fetch_row($res)) {
      $list_json.='\''.$nom_champ.'\',';
   }
   $list_json= rtrim($list_json,',');
   $list_json.='];';
   $scri_js ='';
   $scri_js.='
   <script type="text/javascript">
   //<![CDATA[
   var '.$nom_array_js.';
   $(function() {
    '.$list_json.'
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
    $( "#'.$id_inpu.'" )
      // dont navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter(
            '.$nom_array_js.', extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
   });
   //]]>
   </script>'."\n";
   return ($scri_js);
}
#autodoc language_iso($l,$s,$c) : renvoi le code language iso 639-1 et code pays ISO 3166-2 $l=> 0 ou 1(requis), $s (séparateur - | _) , $c=> 0 ou 1 (requis)
function language_iso($l,$s,$c) {
   global $language, $user_language;
   $iso_lang='';$iso_country='';$ietf=''; $select_lang='';
   $select_lang = !empty($user_language) ? $user_language : $language ;
   switch ($select_lang) {
      case "french": $iso_lang ='fr';$iso_country='FR'; break;
      case "english": $iso_lang ='en';$iso_country='US'; break;
      case "spanish": $iso_lang ='es';$iso_country='ES'; break;
      case "german": $iso_lang ='de';$iso_country='DE'; break;
      case "chinese": $iso_lang ='zh';$iso_country='CN'; break;
      default:
      break;
   }
   if ($c!==1) $ietf= $iso_lang;
   if (($l==1) and ($c==1)) $ietf=$iso_lang.$s.$iso_country;
   if (($l!==1) and ($c==1)) $ietf=$iso_country;
   if (($l!==1) and ($c!==1)) $ietf='';
   if (($l==1) and ($c!==1)) $ietf=$iso_lang;
   return ($ietf);
}
#autodoc adminfoot($fv,$fv_parametres,$arg1,$foo) : fin d'affichage avec form validateur ou pas, ses parametres (js), fermeture div admin et inclusion footer.php  $fv=> fv : inclusion du validateur de form , $fv_parametres=> éléments de l'objet fields differents input (objet js ex :   xxx: {},...) si !###! est trouvé dans la variable la partie du code suivant sera inclu à la fin de la fonction d'initialisation, $arg1=>js pur au début du script js, $foo =='' ==> </div> et inclusion footer.php $foo =='foo' ==> inclusion footer.php
function adminfoot($fv,$fv_parametres,$arg1,$foo) {
   global $minpass;
   if ($fv=='fv') {
      if($fv_parametres!='') $fv_parametres = explode('!###!',$fv_parametres);
      echo '
   <script type="text/javascript" src="lib/js/es6-shim.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/FormValidation.full.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/locales/'.language_iso(1,"_",1).'.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
   <script type="text/javascript" src="lib/formvalidation/dist/js/plugins/L10n.min.js"></script>
   <script type="text/javascript" src="lib/js/checkfieldinp.js"></script>
   <script type="text/javascript">
   //<![CDATA[
   '.$arg1.'
   var diff;
   document.addEventListener("DOMContentLoaded", function(e) {
      // validateur pour mots de passe
      const strongPassword = function() {
        return {
            validate: function(input) {
               let score=0;
               const value = input.value;
               if (value === "") {
                  return {
                     valid: true,
                     meta:{score:null},
                  };
               }
               if (value === value.toLowerCase()) {
                  return {
                     valid: false,
                     message: "'.translate("Le mot de passe doit contenir au moins un caractère en majuscule.").'",
                     meta:{score: score-1},
                   };
               }
               if (value === value.toUpperCase()) {
                  return {
                     valid: false,
                     message: "'.translate("Le mot de passe doit contenir au moins un caractère en minuscule.").'",
                     meta:{score: score-2},
                  };
               }
               if (value.search(/[0-9]/) < 0) {
                  return {
                     valid: false,
                     message: "'.translate("Le mot de passe doit contenir au moins un chiffre.").'",
                     meta:{score: score-3},
                  };
               }
               if (value.search(/[@\+\-!#$%&^~*_]/) < 0) {
                  return {
                     valid: false,
                     message: "'.translate("Le mot de passe doit contenir au moins un caractère non alphanumérique.").'",
                     meta:{score: score-4},
                  };
               }
               if (value.length < 8) {
                  return {
                     valid: false,
                     message: "'.translate("Le mot de passe doit contenir").' '.$minpass.' '.translate("caractères au minimum").'",
                     meta:{score: score-5},
                  };
               }

               score += ((value.length >= '.$minpass.') ? 1 : -1);
               if (/[A-Z]/.test(value)) score += 1;
               if (/[a-z]/.test(value)) score += 1; 
               if (/[0-9]/.test(value)) score += 1;
               if (/[@\+\-!#$%&^~*_]/.test(value)) score += 1; 
               return {
                  valid: true,
                  meta:{score: score},
               };
            },
         };
      };
      FormValidation.validators.checkPassword = strongPassword;
      formulid.forEach(function(item, index, array) {
         const fvitem = FormValidation.formValidation(
            document.getElementById(item),{
               locale: "'.language_iso(1,"_",1).'",
               localization: FormValidation.locales.'.language_iso(1,"_",1).',
               fields: {';
   if($fv_parametres!='')
      echo '
            '.$fv_parametres[0];
   echo '
               },
               plugins: {
                  declarative: new FormValidation.plugins.Declarative({
                     html5Input: true,
                  }),
                  trigger: new FormValidation.plugins.Trigger(),
                  submitButton: new FormValidation.plugins.SubmitButton(),
                  defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                  bootstrap5: new FormValidation.plugins.Bootstrap5({rowSelector: ".mb-3"}),
                  icon: new FormValidation.plugins.Icon({
                     valid: "fa fa-check",
                     invalid: "fa fa-times",
                     validating: "fa fa-sync",
                     onPlaced: function(e) {
                        e.iconElement.addEventListener("click", function() {
                           fvitem.resetField(e.field);
                        });
                     },
                  }),
               },
            })
            .on("core.validator.validated", function(e) {
               if ((e.field === "add_pwd" || e.field === "chng_pwd" || e.field === "pass" || e.field === "add_pass" || e.field === "code" || e.field === "passwd") && e.validator === "checkPassword") {
                  var score = e.result.meta.score;
                  const barre = document.querySelector("#passwordMeter_cont");
                  const width = (score < 0) ? score * -18 + "%" : "100%";
                  barre.style.width = width;
                  barre.classList.add("progress-bar","progress-bar-striped","progress-bar-animated","bg-success");
                  barre.setAttribute("aria-valuenow", width);
                  if (score === null) {
                     barre.style.width = "100%";
                     barre.setAttribute("aria-valuenow", "100%");
                     barre.classList.replace("bg-success","bg-danger");
                  } else 
                     barre.classList.replace("bg-danger","bg-success");
               }
               if (e.field === "B1" && e.validator === "promise") {
                  if (e.result.valid && e.result.meta && e.result.meta.source) {
                      $("#ava_perso").removeClass("border-danger").addClass("border-success")
                  } else if (!e.result.valid) {
                     $("#ava_perso").addClass("border-danger")
                  }
               }
            });';
      if($fv_parametres!='')
         if(array_key_exists(1, $fv_parametres))
            echo '
               '.$fv_parametres[1];
   echo '
         })
      });
   //]]>
   </script>';
   }
   switch($foo) {
      case '' :
         echo '
      </div>';
         include ('footer.php');
      break;
      case 'foo' :
         include ('footer.php');
      break;
   }
}
#autodoc getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms=100) : permet de calculer le coût algorythmique optimum pour la procédure de hashage ($AlgoCrypt) d'un mot de pass ($pass) avec un temps minimum alloué ($min_ms)
function getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms=100) {
   for ($i = 8; $i < 13; $i++) {
      $calculCost = [ 'cost' => $i ];
      $time_start = microtime(true);
      password_hash($pass, $AlgoCrypt, $calculCost);
      $time_end = microtime(true);
      if (($time_end - $time_start) * 1000 > $min_ms)
         return $i;
   }
}
#autodoc dataimagetofileurl($base_64_string, $output_path) : Analyse la chaine $base_64_string pour touver "src data:image" SI oui : fabrication de fichiers (gif | png | jpeg) (avec $output_path) - redimensionne l'image si supérieure aux dimensions maxi fixées et remplacement de "src data:image" par "src url", et retourne $base_64_string modifié ou pas
function dataimagetofileurl($base_64_string, $output_path) {
   $rechdataimage = '#src=\\\"(data:image/[^"]+)\\\"#m';
   preg_match_all($rechdataimage, $base_64_string, $dataimages);
   $j=0;$timgw=800;$timgh=600;
   $ra = rand(1, 999);
   foreach($dataimages[1] as $imagedata) {
      $datatodecode = explode(',',$imagedata);
      $bin = base64_decode($datatodecode[1]);
      $im = imageCreateFromString($bin);
      if (!$im)
        die('Image non valide');
      $size = getImageSizeFromString($bin);
      $ext = substr($size['mime'], 6);
      if (!in_array($ext, ['png', 'gif', 'jpeg']))
         die('Image non supportée');
      $output_file = $output_path.$j."_".$ra."_".time().".".$ext;
      $base_64_string = preg_replace($rechdataimage, 'class="img-fluid" src="'.$output_file.'" loading="lazy"', $base_64_string,1);
      if ($size[0]>$timgw or $size[1]>$timgh){
         $timgh = round(($timgw / $size[0]) * $size[1]);
         $timgw = round(($timgh / $size[1]) * $size[0]);
         $th=imagecreatetruecolor($timgw,$timgh);
         imagecopyresampled($th,$im,0,0,0,0,$timgw,$timgh, $size[0],$size[1]);
         $args = [$th, $output_file];
      } else 
         $args = [$im, $output_file];
      if ($ext == 'png')
         $args[] = 0;
      else if ($ext == 'jpeg')
         $args[] = 100;
      $fonc = "image{$ext}";
      call_user_func_array($fonc, $args);
      $j++;
   }
   return $base_64_string;
}
?>