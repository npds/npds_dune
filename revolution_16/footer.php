<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

function footmsg() {
    global $foot1, $foot2, $foot3, $foot4;
    $foot='<p align="center">';
    if ($foot1) $foot.=stripslashes($foot1).'<br />';
    if ($foot2) $foot.=stripslashes($foot2).'<br />';
    if ($foot3) $foot.=stripslashes($foot3).'<br />';
    if ($foot4) $foot.=stripslashes($foot4);
    $foot.='</p>';
    echo aff_langue($foot);
}

function foot() {
   global $user, $Default_Theme, $cookie9;
   if ($user) {
      $user2 = base64_decode($user);
      $cookie = explode(':', $user2);
      if ($cookie[9]=='') $cookie[9]=$Default_Theme;

      $ibix=explode('+', urldecode($cookie[9]));
      if (!$file=@opendir("themes/$ibix[0]")) 
         include("themes/$Default_Theme/footer.php");
      else
         include("themes/$ibix[0]/footer.php");
   } else 
      include("themes/$Default_Theme/footer.php");
   if ($user) $cookie9 = $ibix[0];
}

   global $tiny_mce, $cookie9, $Default_Theme;
   if ($tiny_mce)
      echo aff_editeur('tiny_mce', 'end');
   // include externe file from modules/include for functions, codes ...
   if (file_exists("modules/include/footer_before.inc")) include ("modules/include/footer_before.inc");
   foot();
   // include externe file from modules/themes include for functions, codes ...
      if (isset($user)) {
         if (file_exists("themes/$cookie9/include/footer_after.inc")) include ("themes/$cookie9/include/footer_after.inc");
         else
         if (file_exists("modules/include/footer_after.inc")) include ("modules/include/footer_after.inc");
      }
      else {
         if (file_exists("themes/$Default_Theme/include/footer_after.inc")) include ("themes/$Default_Theme/include/footer_after.inc"); 
         else
         if (file_exists("modules/include/footer_after.inc")) include ("modules/include/footer_after.inc");
      }
   echo '
   </body>
</html>';

   include("sitemap.php");
   sql_close();
?>