<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

// chatbox avec salon privatif - on utilise id pour filtrer les messages -> id = l'id du groupe au sens autorisation de NPDS (-127,-1,0,1,2...126))
settype ($id,'integer');
if (unserialize(decrypt($auto))!=$id) die();

if (!function_exists("makeChatBox")) {include ("powerpack_f.php");}
include("functions.php");
settype ($repere,'integer');
settype($aff_entetes,'integer');
settype($connectes,'integer');

   // Savoir si le 'connecté' a le droit à ce chat ?
   if (!autorisation($id)) die();

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
   global $site_font, $NPDS_Prefix;

   $result = sql_query("SELECT username, message, dbname, date FROM ".$NPDS_Prefix."chatbox WHERE id='$id' AND date>'$repere' ORDER BY date ASC");
   $thing='';
   if ($result){
      while(list($username, $message, $dbname, $date_message) = sql_fetch_row($result)) {
         $thing.="<div class='chatmessage'><div class='chatheure'>".date(translate("Chatdate"),$date_message+((integer)$gmt*3600))."</div>";
         if ($dbname==1) {
            if ((!$user) and ($member_list==1) and (!$admin))
               $thing.="<div class='chatnom'>$username</div>";
            else
               $thing.="<div class='chatnom'><a href='user.php?op=userinfo&amp;uname=$username' target='_blank'>$username</a></div>";
         } else
            $thing.="<div class='chatnom'>$username</div>";

         $message=smilie($message);
         $chat_forbidden_words=array(
         "'\"'i"=>'&quot;',
         "'OxOA'i"=>'',
         "'OxOD'i"=>'',
         "'\n'i"=>'',
         "'\r'i"=>'',
         "'\t'i"=>'');
         $message=preg_replace(array_keys($chat_forbidden_words),array_values($chat_forbidden_words), $message);
         $message=str_replace('"','\"',make_clickable($message));
         $thing.="<div class='chattexte'>".removeHack($message)."</div></div>";
         $repere=$date_message;
      }
      $thing="\"".$thing."\"";
   }

   if ($aff_entetes=='1') {
      $meta_op=true;
      settype($Xthing,'string');
      include("meta/meta.php");
      $Xthing.=$l_meta;
      $Xthing.=str_replace("\n","",import_css_javascript($tmp_theme, $language, $site_font, basename($_SERVER['PHP_SELF']),""));
      $Xthing.="</head><body id='chat'>";
      $Xthing="\"".str_replace("'","\'",$Xthing)."\"";
   }
   $result = sql_query("SELECT DISTINCT ip FROM ".$NPDS_Prefix."chatbox WHERE id='$id' and date >= ".(time()-(60*2))."");
   $numofchatters = sql_num_rows($result);

   $rafraich_connectes=0;
   if (intval($connectes)!=$numofchatters) {
      $rafraich_connectes=1;
      if (($numofchatters==1) or ($numofchatters==0)) {
         $nbre_connectes="'".$numofchatters." ".utf8_java(translate("personne connectée."))." GP [$id]'";
      } else {
         $nbre_connectes="'".$numofchatters." ".utf8_java(translate("personnes connectées."))." GP [$id]'";
      }
   }
   $commande="self.location='chatrafraich.php?repere=$repere&aff_entetes=0&connectes=$numofchatters&id=$id&auto=$auto'";
   include("meta/meta.php");
   echo "</head>\n<body style=\"background-color: #F8F8F8;\" id='chat'>
   <script type='text/javascript'>
   //<![CDATA[
   function scroll_messages() {
      if (typeof(scrollBy) != 'undefined') {
         parent.frames[1].scrollBy(0, 20000);
         parent.frames[1].scrollBy(0, 20000);
      }
      else if (typeof(scroll) != 'undefined') {
         parent.frames[1].scroll(0, 20000);
         parent.frames[1].scroll(0, 20000);
      }
   }

   function rafraichir() {
      $commande;
   }

   function sur_chargement() {
      setTimeout(\"rafraichir();\", 5000);";
      if ($aff_entetes=="1")  echo "parent.frames[1].document.write($Xthing);";
      if ($thing!="\"\"") {
         echo "parent.frames[1].document.write($thing);
               setTimeout(\"scroll_messages();\", 300);
               ";
      }
      if ($rafraich_connectes==1) {
         echo "top.document.title=$nbre_connectes;";
      }
   echo "}
   window.onload=sur_chargement();
   //]]>
   </script>
   </body>
   </html>";
?>