<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   include ("mainfile.php");
   
       if (isset($user)) {
          if ($cookie[9]=="") $cookie[9]=$Default_Theme;
          if (isset($theme)) $cookie[9]=$theme;
          $tmp_theme=$cookie[9];
          if (!$file=@opendir("themes/$cookie[9]")) {
             $tmp_theme=$Default_Theme;
          }
       } else {
          $tmp_theme=$Default_Theme;
       }


if( $_REQUEST["them"] )
{
//include('themes/listskin.php');
$depotskin ='themes/'.$tmp_theme.'/skin/';
$response = array();
$skins = array();

   $handle=opendir('themes/'.$tmp_theme.'/skin');
   while (false!==($file = readdir($handle))) {
      if ( (!strstr($file,'.')) and (!strstr($file,'bower_components')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
         $skins[] = array('name'=> $file, 'description'=> '', 'thumbnail'=> $depotskin.$file.'/thumbnail','preview'=> $depotskin.$file.'/','css'=> $depotskin.$file.'/bootstrap.css','cssMin'=> $depotskin.$file.'/bootstrap.min.css','cssCdn'=> '','less'=> $depotskin.$file.'/bootswatch.less','lessVariables'=> $depotskin.$file.'/variables.less','scss'=> $depotskin.$file.'/_bootswatch.scss','scssVariables'=> $depotskin.$file.'/_variables.scss');
      }
   }
   $response['skins'] = $skins;
   closedir($handle);

$fp = fopen('api/3.json', 'w');
fwrite($fp, stripslashes (json_encode($response)));
fclose($fp);

$nameskin = strtolower($_REQUEST['them']);
$baseskin ='#<link id="bsth" rel="stylesheet" href="lib/bootstrap-4.0.0-alpha.2/dist/css/bootstrap.min.css" />#';
$newskin ='<link id="bsth" rel="stylesheet" href="'.$depotskin.$nameskin.'/bootstrap.css" />';
$headthem = file_get_contents('themes/'.$tmp_theme.'/include/header_head.inc');
$replskin ="\1$nameskin\3";


if (preg_match($baseskin,$headthem)) {
$newcontent = preg_replace ( $baseskin, $newskin,$headthem);
} else{
$newcontent = preg_replace('#(<link id="bsth" rel="stylesheet" href="themes/npds-boost/skin/)(.*)(/bootstrap.css" />)#', "\\1$nameskin\\3", $headthem);
}

    $file = fopen('themes/'.$tmp_theme.'/include/header_head.inc', 'w');
    $content = $newcontent;
    fwrite($file, $content);
    fclose($file);

   echo " : ".$tmp_theme.'; skin : '.$nameskin;
}

?>