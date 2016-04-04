<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='blocks';// à voir si on réimplémente les droits spécifique droit et gauche
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language;
$hlpfile = "manuels/$language/leftblocks.html";

function makelblock($title, $content, $members, $Mmember, $Lindex, $Scache, $BLaide, $SHTML, $css) {
   global $NPDS_Prefix;

   if (is_array($Mmember) and ($members==1)) {
      $members=implode(',',$Mmember);
      if ($members==0) $members=1;
   }
   if (empty($Lindex)) {$Lindex=0;}
   $title=stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   if ($SHTML!='ON')
      $content = strip_tags(str_replace('<br />','\n',$content));
   sql_query("INSERT INTO ".$NPDS_Prefix."lblocks VALUES (NULL,'$title','$content','$members', '$Lindex', '$Scache', '1','$css', '$BLaide')");

   global $aid; Ecr_Log('security', "MakeLeftBlock($title) by AID : $aid", "");
   Header("Location: admin.php?op=blocks");
}

function changelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css) {
   global $NPDS_Prefix;

   if (is_array($Mmember) and ($members==1)) {
      $members=implode(',',$Mmember);
      if ($members==0) $members=1;
   }
   if (empty($Lindex)) {$Lindex=0;}
   $title=stripslashes(FixQuotes($title));
   if ($Sactif=='ON') {$Sactif=1;} else {$Sactif=0;}
   if ($css){ $css=1;}else{$css=0;}
   $content = stripslashes(FixQuotes($content));
   $BLaide = stripslashes(FixQuotes($BLaide));
   sql_query("UPDATE ".$NPDS_Prefix."lblocks SET title='$title', content='$content', member='$members', Lindex='$Lindex', cache='$Scache', actif='$Sactif', aide='$BLaide', css='$css' WHERE id='$id'");

   global $aid; Ecr_Log("security", "ChangeLeftBlock($title - $id) by AID : $aid", "");
   Header("Location: admin.php?op=blocks");
}

function changedroitelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css) {
   global $NPDS_Prefix;

   if (is_array($Mmember) and ($members==1)) {
      $members=implode(',',$Mmember);
      if ($members==0) $members=1;
   }
   if (empty($Lindex)) {$Lindex=0;}
   $title=stripslashes(FixQuotes($title));
   if ($Sactif=='ON') {$Sactif=1;} else {$Sactif=0;}
   if ($css){ $css=1;}else{$css=0;}
   $content = stripslashes(FixQuotes($content));
   $BLaide = stripslashes(FixQuotes($BLaide));
   sql_query("INSERT INTO ".$NPDS_Prefix."rblocks VALUES (NULL,'$title','$content', '$members', '$Lindex', '$Scache', '$Sactif', '$css', '$BLaide')");
   sql_query("DELETE FROM ".$NPDS_Prefix."lblocks WHERE id='$id'");

   global $aid; Ecr_Log('security', "MoveLeftBlockToRight($title - $id) by AID : $aid", '');
   Header("Location: admin.php?op=blocks");
}

function deletelblock($id) {
   global $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."lblocks WHERE id='$id'");
   global $aid; Ecr_Log('security', "DeleteLeftBlock($id) by AID : $aid", '');
   Header("Location: admin.php?op=blocks");
}

switch ($op) {
   case 'makelblock':
        makelblock($title, $xtext, $members, $Mmember, $index, $Scache, $Baide, $SHTML, $css);
        break;
   case 'deletelblock':
        deletelblock($id);
        break;
   case 'changelblock':
        changelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css);
        break;
   case 'droitelblock':
        changedroitelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css);
        break;
}
?>