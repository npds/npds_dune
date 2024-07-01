<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
// chatbox avec salon privatif - on utilise id pour filtrer les messages -> id = l'id du groupe au sens autorisation de NPDS (-127,-1,0,1,2...126))

settype ($id,'integer');
if ($id==='' || unserialize(decrypt($auto))!=$id) die();

if (!function_exists("makeChatBox")) include ("powerpack_f.php");
include("functions.php");

   // Savoir si le 'connecté' a le droit à ce chat ?
   // le problème c'est que tous les groupes qui existent on le droit au chat ... donc il faut trouver une solution pour pouvoir l'interdire
   // soit on vient d'un bloc qui par définition autorise en fabricant l'interface
   // soit on viens de WS et là ....

   if (!autorisation($id)) die();
   global $Default_Theme, $Default_Skin, $user;
   if (isset($user) and $user!='') {
      global $cookie;
      if($cookie[9] !='') {
         $ibix=explode('+', urldecode($cookie[9]));
         if (array_key_exists(0, $ibix)) $theme=$ibix[0]; else $theme=$Default_Theme;
         if (array_key_exists(1, $ibix)) $skin=$ibix[1]; else $skin=$Default_Skin; 
         $tmp_theme=$theme;
         if (!$file=@opendir("themes/$theme")) $tmp_theme=$Default_Theme;
      } else 
         $tmp_theme=$Default_Theme;
   } else {
      $theme=$Default_Theme;
      $skin=$Default_Skin;
      $tmp_theme=$theme;
   }
   $skin = $skin =='' ? 'default' : $skin ;

   $Titlesitename='NPDS';
   include("meta/meta.php");
   echo import_css($tmp_theme, $language, $skin, basename($_SERVER['PHP_SELF']), '');
   include("lib/formhelp.java.php");
   echo '</head>';

   // cookie chat_info (1 par groupe)
   echo '
   <script type="text/javascript" src="lib/cookies.js"></script>';
   echo "
      <body id=\"chat\" onload=\"setCookie('chat_info_$id', '1', '');\" onUnload=\"deleteCookie('chat_info_$id');\">";
   echo '
         <script type="text/javascript" src="lib/js/jquery.min.js"></script>
         <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
         <link rel="stylesheet" href="lib/font-awesome/css/all.min.css">
         <form name="coolsus" action="chatinput.php" method="post">
         <input type="hidden" name="op" value="set" />
         <input type="hidden" name="id" value="'.$id.'" />
         <input type="hidden" name="auto" value="'.$auto.'" />';

   if (!isset($cookie[1]))
      $pseudo = isset($name) ? $name : getip() ;
   else
      $pseudo = $cookie[1];
   $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';

   echo translate("Vous êtes connecté en tant que :").' <strong>'.$pseudo.'</strong>&nbsp;';
   echo '
         <input type="hidden" name="name" value="'.$pseudo.'" />
         <textarea id="chatarea" class="form-control my-3" type="text" rows="2" '.$xJava.' placeholder="🖋"></textarea>
         <div class="float-end">';
         putitems("chatarea");
   echo '
         </div>
         <input class="btn btn-primary btn-sm" type="submit" tabindex="1" value="'.translate("Valider").'" />
         </form>
         <script src="lib/js/npds_adapt.js"></script>
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