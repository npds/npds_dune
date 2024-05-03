<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

function L_encrypt($txt) {
   global $userdata;

   $key=substr($userdata[2],8,8);
   return (encryptK($txt, $key));
}

   global $user, $Default_Theme;
   if (!$user)
        Header("Location: user.php");
   else {
      $userX = base64_decode($user);
      $userdata = explode(':', $userX);
      if ($userdata[9]!='') {
         if (!$file=@opendir("themes/$userdata[9]"))
            $tmp_theme=$Default_Theme;
         else
            $tmp_theme=$userdata[9];
      } else
         $tmp_theme=$Default_Theme;

      include("themes/$tmp_theme/theme.php");
      $Titlesitename=translate("Carnet d'adresses");
      include("meta/meta.php");
      
      echo '
      <link id="bsth" rel="stylesheet" href="themes/_skins/default/bootstrap.min.css" />';

      echo import_css($tmp_theme, $language, "", "","");
      include("lib/formhelp.java.php");

      $fic="users_private/".$userdata[1]."/mns/carnet.txt";
      echo '
   </head>
   <body class="p-4">';
      if (file_exists($fic)) {
         $fp=fopen($fic,"r");
            if (filesize($fic)>0)
               $contents=fread($fp, filesize($fic));
         fclose($fp);
         if (substr($contents,0,5)!="CRYPT") {
            $fp=fopen($fic,"w");
               fwrite($fp, "CRYPT".L_encrypt($contents));
            fclose($fp);
         } else {
            $contents=decryptK(substr($contents,5),substr($userdata[2],8,8));
         }
         echo '
         <div class="row">';
         $contents=explode("\n",$contents);
         foreach($contents as $tab) {
            $tabi=explode(';',$tab);
            if ($tabi[0]!='') {
               echo '
               <div class="border col-md-4 mb-1 p-3">
                  <a href="javascript: DoAdd(1,\'to_user\',\''.$tabi[0].',\')";><b>'.$tabi[0].'</b></a><br />
                  <a href="mailto:'.$tabi['1'].'" >'.$tabi['1'].'</a><br />
                  '.$tabi['2'].'
               </div>';
            }
         }
         echo '
         </div>';
      } else
         echo '
         <div class="alert alert-secondary text-break">
            <span>'.translate("Vous pouvez charger un fichier carnet.txt dans votre miniSite").'.</span><br />
            <span>'.translate("La structure de chaque ligne de ce fichier : nom_du_membre; adresse Email; commentaires").'</span>
         </div>';
      echo '
   </body>
</html>';
}
?>