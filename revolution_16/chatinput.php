<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

// chatbox avec salon privatif - on utilise id pour filtrer les messages -> id = l'id du groupe au sens autorisation de NPDS (-127,-1,0,1,2...126))
settype ($id,'integer');
if (unserialize(decrypt($auto))!=$id) die();

if (!function_exists("makeChatBox")) {include ("powerpack_f.php");}
include("functions.php");

   // Savoir si le 'connecté' a le droit à ce chat ?
   // le problème c'est que tous les groupes qui existent on le droit au chat ... donc il faut trouver une solution pour pouvoir l'interdire
   // soit on vient d'un bloc qui par définition autorise en fabricant l'interface
   // soit on viens de WS et là ....

   if (!autorisation($id)) { die(); }

   if (isset($user)) {
      if ($cookie[9]=='') $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) {
         $tmp_theme=$Default_Theme;
         include("themes/$Default_Theme/theme.php");
      } else {
         include("themes/$cookie[9]/theme.php");
      }
   } else {
      $tmp_theme=$Default_Theme;
      include("themes/$Default_Theme/theme.php");
   }
   $Titlesitename='NPDS';
   include("meta/meta.php");
   echo import_css($tmp_theme, $language, $site_font, basename($_SERVER['PHP_SELF']), '');
   include("lib/formhelp.java.php");
   echo '</head>';

   // cookie chat_info
   echo '
   <script type="text/javascript" src="lib/cookies.js"></script>';
   echo "
      <body id=\"chat\" onload=\"setCookie('chat_info', '1', '');\" onUnload=\"deleteCookie('chat_info');\">";
   putitems();
   echo '
         <form name="coolsus" action="chatinput.php" method="post">
            <input type="hidden" name="op" value="set" />
            <input type="hidden" name="id" value="'.$id.'" />
            <input type="hidden" name="auto" value="'.$auto.'" />';

   if (!isset($cookie[1])) {
      $pseudo = ((isset($name))?($name):getip());
   } else {
      $pseudo = $cookie[1];
   }
   $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';

   echo translate("You are logged in as").' <strong>'.$pseudo.'</strong>&nbsp;';
   echo '
            <input type="hidden" name="name" value="'.$pseudo.'" />
            <textarea class="form-control mb-1" type="text" rows="2" '.$xJava.' ></textarea>
            <input class="btn btn-primary btn-sm" type="submit" tabindex="1" value="'.translate("Submit").'" />
         </form>
         <script type="text/javascript">
         //<![CDATA[
            document.coolsus.message.focus();
         //]]>
         </script>
      </body>
   </html>';

   settype($op,'string');
   switch ($op) {
   case 'set':
      if (!isset($cookie[1]) && isset($name)) {
         $uname = $name;
         $dbname = 0;
      } else {
         $uname = $cookie[1];
         $dbname = 1;
      }
      insertChat($uname, $message, $dbname, $id);
   break;
   }
?>