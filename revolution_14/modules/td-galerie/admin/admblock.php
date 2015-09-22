<?php
/**************************************************************************************************/
/* Module de gestion de galeries pour NPDS                                                        */
/* ===================================================                                            */
/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net                                   */
/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                                               */
/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010                            */
/* MAJ Dev - 2011                                                                                 */
/*                                                                                                */
/* This program is free software. You can redistribute it and/or modify it under the terms of     */
/* the GNU General Public License as published by the Free Software Foundation; either version 2  */
/* of the License.                                                                                */
/**************************************************************************************************/
global $language, $NPDS_Prefix;
$ModPath="td-galerie";
include_once("modules/$ModPath/admin/lang/adm-$language.php");

$content.="<ul><li><a href=\"admin.php?op=Extend-Admin-SubModule&amp;ModPath=$file&amp;ModStart=admin/adm&amp;subop=viewarbo\">TD-Galerie</a> : ";
$query=sql_query("SELECT gal_id FROM ".$NPDS_Prefix."tdgal_img WHERE noaff='1' ORDER by gal_id");
$td_total=sql_num_rows($query);
$content.=$td_total;

if ($td_total>0) {
   $content.="<ul>";
   while ($row = sql_fetch_row($query)) {
      $querygal=sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$row[0]."'");
      $rowgal  =sql_fetch_row($querygal);
      if ($row[0]>0) {
         $content.="<li style=\"font-size:9px;\">".$rowgal[0]."</li>\n";
      } else {
         $content.="<li style=\"font-size:9px;\">".adm_gal_trans("Galerie temporaire")."</li>\n";
      }
   }
   $content.="</ul>";
}
$content.="</li></ul>";
?>