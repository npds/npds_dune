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

// For More security
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
// For More security

// Retro compatibilité SABLE
if (!function_exists("sql_connect")) {
   include ("modules/$ModPath/retro-compat/mysql.php");
}
// Retro compatibilité SABLE

/**************************************************************************************************/
/* Administration du MODULE                                                                       */
/**************************************************************************************************/
if ($admin) {
   global $language, $ModPath, $ModStart, $NPDS_Prefix;

   include_once("modules/$ModPath/gal_conf.php");
   include_once("modules/$ModPath/admin/adm_func.php");
   include_once("modules/".$ModPath."/admin/lang/adm-".$language.".php");

   //update Tables for 2.2 release
   $result=sql_query("SELECT noaff from ".$NPDS_Prefix."tdgal_img");
   if (sql_num_rows($result)==0) {
      sql_query("ALTER TABLE ".$NPDS_Prefix."tdgal_img ADD `noaff` int(1) unsigned default '0'");
   }
   //update Tables for 2.1 release

   // Paramètres utilisé par le script
   $ThisFile = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=$ModPath&amp;ModStart=$ModStart";
   $ThisRedo = "admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart";

   OpenTable();
   // En-Tête
   echo "<br \><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo adm_gal_trans("Administration des galeries");
   echo "</td></tr>\n";

   echo "<tr><td align=\"center\"><br />\n";
   echo "<a href=\"".$ThisFile."\" class=\"noir\">".adm_gal_trans("Accueil")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=viewarbo\" class=\"noir\">".adm_gal_trans("Voir l'arborescence")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=import\" class=\"noir\">".adm_gal_trans("Importer des images")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=export\" class=\"noir\">".adm_gal_trans("Exporter une catégorie")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=config\" class=\"noir\">".adm_gal_trans("Configuration")."</a><br /><br />";
   echo "</td></tr>\n";
    
   echo "<tr><td align=\"center\">\n";
   echo "[ <a href=\"".$ThisFile."&amp;subop=formcat\" class=\"noir\">".adm_gal_trans("Ajouter une catégorie")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=formsscat\" class=\"noir\">".adm_gal_trans("Ajouter une sous-catégorie")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=formcregal\" class=\"noir\">".adm_gal_trans("Ajouter une galerie")."</a>&nbsp;|&nbsp;";
   echo "<a href=\"".$ThisFile."&amp;subop=formimgs\" class=\"noir\">".adm_gal_trans("Ajouter des images")."</a>&nbsp; ]";
   echo "</td></tr>\n";
   echo "</table>";
   CloseTable();
   echo "<hr noshade class=\"ongl\" />";

   OpenTable();
   switch($subop) {
   case "formcat" :
     PrintFormCat();
     break;
   case "addcat" :
     AddACat($newcat,$acces);
     break;
   case "formsscat" :
     PrintFormSSCat();
     break;
   case "addsscat" :
     AddSsCat($cat,$newsscat,$acces);
     break;
   case "formcregal" :
     PrintCreerGalery();
     break;
   case "creegal" :
     AddNewGal($galcat,$newgal,$acces);
     break;
   case "formimgs" :
     PrintFormImgs();
     break;
   case "addimgs" :
     AddImgs($imggal,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5);
     break;
   case "viewarbo" :
     PrintArbo();
     break;
   case "delcat" :
     DelCat($catid,$go);
     break;
   case "editcat" :
     Edit("Cat",$catid);
     break;
   case "delsscat" :
     DelSsCat($sscatid,$go);
     break;
   case "delgal" :
     DelGal($galid,$go);
     break;
   case "editgal" :
     Edit("Gal",$galid);
     break;
   case "editimg" :
     EditImg($imgid);
     break;
   case "doeditimg" :
     DoEditImg($imgid,$imggal,$newdesc);
     break;
   case "delimg" :
     DelImg($imgid,$go);
     break;
   case "validimg" :
     DoValidImg($imgid);
     break;
   case "delcomimg" :
     DelComImg($id,$picid);
     break;
   case "rename" :
     if ($actualname == $newname) { redirect_url($ThisRedo); }
     ChangeName($type,$gcid,$newname,$newgalcat,$newacces);
     break;
   case "config" :
     PrintFormConfig();
     break;
   case "wrtconfig" :
     WriteConfig($maxszimg,$maxszthb,$nbimlg,$nbimpg,$nbimcomment,$nbimvote,$viewalea,$viewlast,$votegal,$commgal,$votano,$comano,$postano,$notifadmin);
     break;
   case "import" :
     import();
     break;
   case "massimport" :
     massimport($imggal, $descri);
     break;
   case "export" :
     PrintExportCat();
     break;
   case "massexport" :
     MassExportCat($cat);
     break;
   case "ordre" :
     ordre($img_id, $ordre);
     break;

   default :
     $ncateg = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
     $nsscat = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!='0'"));
     $numgal = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_gal"));
     $ncards = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_img"));
     $ncomms = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_com"));
     $nvotes = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_vot"));
     $nviews = sql_fetch_row(sql_query("SELECT SUM(view) FROM ".$NPDS_Prefix."tdgal_img"));

     echo "<p align=\"center\">";
     OpenTableGal();
     $rowcolor=tablos();
     echo "<tr $rowcolor><td>".adm_gal_trans("Nombre de catégories :")."</td><td><b>".$ncateg[0]."</b></td>";
     echo "<td>".adm_gal_trans("Nombre de sous-catégories :")."</td><td><b>".$nsscat[0]."</b></td>";
     echo "<td>".adm_gal_trans("Nombre de galeries :")."</td><td><b>".$numgal[0]."</b></td></tr>";
     $rowcolor=tablos();
     echo "<tr $rowcolor><td>".adm_gal_trans("Nombre d'images :")."</td><td><b>".$ncards[0]."</b></td>";
     echo "<td>".adm_gal_trans("Nombre de commentaires :")."</td><td><b>".$ncomms[0]."</b></td>";
     echo "<td>".adm_gal_trans("Nombre de votes :")."</td><td><b>".$nvotes[0]."</b></td></tr>";
     $rowcolor=tablos();
     echo "<tr $rowcolor><td colspan=\"6\" align=\"center\">".adm_gal_trans("Images vues :")." <b>".$nviews[0]."</b></td>";
     CloseTableGal();
     echo "</p>";
     opentable();
     echo "</td><td align=\"right\">Version : ".$TDGAL_version."\n";
     closetable();
     break;
   }
   CloseTable();
}
?>