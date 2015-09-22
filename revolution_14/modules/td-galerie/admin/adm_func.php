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

/**************************************************************************************************/
/* Fonctions d'administration du MODULE                                                           */
/**************************************************************************************************/
function PrintFormCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile;
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   if ($num[0] == 0) {
      echo "<span class=\"rouge\">".adm_gal_trans("Aucune catégorie trouvée")."</span>";
   } else {
      echo "<b>".adm_gal_trans("Nombre de catégories :")."</b> ".$num[0];
   }
   echo "<br /><br />";
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormCat\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"addcat\">";
   echo "<tr><td align=\"left\">".adm_gal_trans("Nom de la catégorie :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newcat\" size=\"50\" maxlength=\"150\"></td></tr>";
   echo "<tr><td align=\"left\">".adm_gal_trans("Accès pour :")."&nbsp;</td>";
   echo "<td><select class=\"textbox_standard\" type=\"select\" name=\"acces\" size=\"1\">".Fab_Option_Group()."</select></td>";
   echo "</tr><tr><td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Ajouter").">";
   echo "</td></tr></form></table>";
}

function AddACat($newcat,$acces) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;

   OpenTable();
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' AND nom='$newcat'"))) {
         echo "<p class=\"rouge\" align=\"center\">".adm_gal_trans("Cette catégorie existe déjà")."</span>";
      } else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('','0','$newcat','$acces')")) {
            redirect_url($ThisRedo);
         } else {
            echo "<p class=\"rouge\" align=\"center\">".adm_gal_trans("Erreur lors de l'ajout de la catégorie")."</p>";
         }
      }
   } else {
      redirect_url($ThisRedo."&subop=formcat");
   }
   CloseTable();
}

function PrintFormSSCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   if ($qnum == 0) { redirect_url($ThisRedo); }
   PrintJavaCodeGal();
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0"));
   if ($num[0] == 0) {
      echo "<p class=\"rouge\">".adm_gal_trans("Aucune sous-catégorie trouvée")."</p>";
   } else {
      echo "<b>".adm_gal_trans("Nombre de sous-catégories :")."</b> ".$num[0];
   }
   echo "<br /><br />";
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormCreer\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"addsscat\">";
   echo "<tr><td align=\"left\">".adm_gal_trans("Catégorie parente :")."&nbsp;</td>";
   echo "<td><select name=\"cat\" class=\"textbox_standard\" size=\"1\" onChange=\"remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);\">";
   echo "<option value=\"none\" selected>".adm_gal_trans("Choisissez")."</option>";
     
   $query = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo "<option value=".$row[0].">".stripslashes($row[1])." (".Get_Name_Group("",$row[2]).")</option>\n";
   }
   echo "</select></td></tr>";
   echo "<tr><td align=\"left\">".adm_gal_trans("Nom de la sous-catégorie :")."&nbsp;".$row[2]."</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newsscat\" size=\"50\" maxlength=\"150\"></td></tr>";
   echo "<tr><td align=\"left\">".adm_gal_trans("Accès pour :")."&nbsp;</td>";
   echo "<td><select class=\"textbox_standard\" type=\"select\" name=\"acces\" size=\"1\">";
   echo "<option value=\"none\" selected>".adm_gal_trans("Choisissez")."</option>";
   echo "</select></td>";
   echo "</tr><tr><td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Ajouter").">";
   echo "</td></tr></form></table>";
}

function AddSsCat($idparent,$newcat,$acces) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;

   OpenTable();
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$idparent' AND nom='$newcat'"))) {
         echo "<p class=\"rouge\" align=\"center\">".adm_gal_trans("Cette sous-catégorie existe déjà")."</p>";
      } else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('','$idparent','$newcat','$acces')")) {
            redirect_url($ThisRedo);
         } else {
            echo "<p class=\"rouge\" align=\"center\">".adm_gal_trans("Erreur lors de l'ajout de la sous-catégorie")."</p>";
         }
      }
   } else {
      redirect_url($ThisRedo."&subop=formsscat");
   }
   CloseTable();
}

function PrintCreerGalery() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0) {
      redirect_url($ThisRedo);
   }
   PrintJavaCodeGal();

   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_gal"));
   if ($num[0] == 0) {
      echo "<p class=\"rouge\">".adm_gal_trans("Aucune galerie trouvée")."</p>";
   } else {
      echo "<b>".adm_gal_trans("Nombre de galeries :")."</b> ".$num[0];
   }

   echo "<br /><br />";
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormCreer\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"creegal\">";
   echo "<tr><td align=\"left\">".adm_gal_trans("Catégorie :")."&nbsp;</td>";
   echo "<td><select name=\"galcat\" size=\"1\" class=\"textbox_standard\" onChange=\"remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);\">";
   echo "<option value=\"none\" selected>".adm_gal_trans("Choisissez")."</option>";
   echo cat_arbo("");
   echo "</select></td></tr>";
   echo "<tr><td align=\"left\">".adm_gal_trans("Nom de la galerie :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newgal\" size=\"50\" maxlength=\"150\"></td></tr>";
   echo "<tr><td align=\"left\">".adm_gal_trans("Accès pour :")."&nbsp;</td>";
   echo "<td><select class=\"textbox_standard\" type=\"select\" name=\"acces\" size=\"1\">";
   echo "<option value=\"none\" selected>".adm_gal_trans("Choisissez")."</option>";
   echo "</select></td>";
   echo "</tr><tr><td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Ajouter").">";
   echo "</td></tr></form></table>";
}

function AddNewGal($galcat,$newgal,$acces) {
   global $ModPath, $ModStart, $gmt, $NPDS_Prefix, $ThisRedo;
    
   OpenTable();
   if (!empty($newgal)) {
      $newgal = addslashes(removeHack($newgal));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$galcat' AND nom='$newgal'"))) {
         echo "<p class=\"rouge\" align=\"center\">".adm_gal_trans("Cette galerie existe déjà")."</p>";
      } else {
         $regdate = time()+($gmt*3600);
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_gal VALUES ('','$galcat','$newgal','$regdate','$acces')")) {
            $new_gal_id = sql_last_id();
            $rowcolor=tablos();
            echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
            echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"".$ThisFile."\" name=\"FormImgs\">";
            echo "<input type=\"hidden\" name=\"subop\" value=\"addimgs\">";
            echo "<input type=\"hidden\" name=\"imggal\" value=\"$new_gal_id\">";
            echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" name=\"newcard1\" type=\"file\" size=\"80\"></td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc1\" size=\"80\" maxlength=\"250\"></td></tr>";
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" name=\"newcard2\" type=\"file\" size=\"80\"></td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc2\" size=\"80\" maxlength=\"250\"></td></tr>";
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" name=\"newcard3\" type=\"file\" size=\"80\"></td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc3\" size=\"80\" maxlength=\"250\"></td></tr>";
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" name=\"newcard4\" type=\"file\" size=\"80\"></td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc4\" size=\"80\" maxlength=\"250\"></td></tr>";
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" name=\"newcard5\" type=\"file\" size=\"80\"></td></tr>";
            echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
            echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc5\" size=\"80\" maxlength=\"250\"></td></tr>";
            echo "<tr><td colspan=\"2\" align=\"center\">";
            echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Ajouter").">";
            echo "</td></tr></form></table>";
         } else {
            echo "<font class=\"rouge\" align=\"center\">".adm_gal_trans("Erreur lors de l'ajout de la galerie")."</font>";
         }
      }
   } else {
      redirect_url($ThisRedo."&subop=formcregal");
   }
   CloseTable();
}

function select_arbo($sel) {
   global $NPDS_Prefix;

   $ibid="<option value=\"-1\">".adm_gal_trans("Galerie temporaire")."</option>\n";
   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.="<optgroup label=\"".stripslashes($row_cat[2])."\">\n";
         $queryX = sql_query("SELECT id, nom  FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            if ($rowX_gal[0] == $sel) { $IsSelected = " selected"; } else { $IsSelected = ""; }
            $ibid.="<option value=\"".$rowX_gal[0]."\"$IsSelected>".stripslashes($rowX_gal[1])." </option>\n";
         } // Fin Galerie Catégorie

         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.="<optgroup label=\"&nbsp;&nbsp;".stripslashes($row_sscat[2])."\">\n";
            $querx = sql_query("SELECT id, nom FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               if ($row_gal[0] == $sel) { $IsSelected = " selected"; } else { $IsSelected = ""; }
               $ibid.="<option value=\"".$row_gal[0]."\"$IsSelected>".stripslashes($row_gal[1])." </option>\n";
            } // Fin Galerie Sous Catégorie
            $ibid.="</optgroup>\n";
         } // Fin Sous Catégorie
         $ibid.="</optgroup>\n";
      } // Fin Catégorie
   }
   return ($ibid);
}
function cat_arbo($sel) {
   global $NPDS_Prefix;

      $ibid="";
      $queryX = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
      while ($rowX = sql_fetch_row($queryX)) {
         if ($sel==$rowX[0]) $selected="selected"; else $selected="";
         $ibid.="<option value=\"".$rowX[0]."\" $selected>".stripslashes($rowX[1])." (".Get_Name_Group("",$rowX[2]).")</option>";
         $queryY = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$rowX[0]."' ORDER BY nom ASC");
         while ($rowY = sql_fetch_row($queryY)) {
            if ($sel==$rowY[0]) $selected="selected"; else $selected="";
            $ibid.="<option value=\"".$rowY[0]."\" $selected>&nbsp;&nbsp;".stripslashes($rowY[1])." (".Get_Name_Group("",$rowY[2]).")</option>";
         }
      }
      return ($ibid);
}

function PrintFormImgs() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0) {
      redirect_url($ThisRedo);
   }
   echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"".$ThisFile."\" name=\"FormImgs\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"addimgs\">";
   echo "<tr><td align=\"right\">".adm_gal_trans("Galerie")."&nbsp;</td>";
   echo "<td><select name=\"imggal\" size=\"1\" class=\"textbox_standard\">";
   $rowcolor=tablos();
   echo select_arbo("");
   echo "</select></td></tr>";
   echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" name=\"newcard1\" type=\"file\" size=\"80\"></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc1\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" name=\"newcard2\" type=\"file\" size=\"80\"></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc2\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" name=\"newcard3\" type=\"file\" size=\"80\"></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc3\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" name=\"newcard4\" type=\"file\" size=\"80\"></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc4\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr $rowcolor><td colspan=\"2\">&nbsp;</td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" name=\"newcard5\" type=\"file\" size=\"80\"></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc5\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr><td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Ajouter").">";
   echo "</td></tr></form></table>";
}

function AddImgs($imgscat,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5) {
   global $language, $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/clsUpload.php");

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");
   
   $i=1;
   while($i <= 5) {
      $img = "newcard$i";
      $tit = "newdesc$i";
      if (!empty($$img)) {
         $newimg = stripslashes(removeHack($$img));
         if (!empty($$tit)) {
            $newtit = addslashes(removeHack($$tit));
         } else {
            $newtit = "";
         }
         $upload = new Upload();
         $upload->maxupload_size=200000*100;
         $origin_filename = trim($upload->getFileName("newcard".$i));
         $filename_ext = strtolower(substr(strrchr($origin_filename, "."),1));

         if ( ($filename_ext=="jpg") or ($filename_ext=="gif") ) {
            $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
            if ($upload->saveAs($newfilename,"modules/$ModPath/imgs/", "newcard".$i,true)) {
               if ((function_exists('gd_info')) or extension_loaded('gd')) {
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
               }

               if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imgscat','$newfilename','$newtit','','0','0')")) {
                  echo "<b>".adm_gal_trans("Image ajoutée avec succès")."</b><br />";
               } else {
                  echo "<span class=\"rouge\">".adm_gal_trans("Impossible d'ajouter l'image en BDD")."</span><br />";
                  @unlink ("modules/$ModPath/imgs/$newfilename");
                  @unlink ("modules/$ModPath/mini/$newfilename");
               }
            } else {
               echo "<span class=\"rouge\">".$upload->errors."</span><br />";
            }
         } else {
            if ($filename_ext!="")
               echo "<span class=\"rouge\">".adm_gal_trans("Ce fichier n'est pas un fichier jpg ou gif")."</span><br />";
         }
      }
      $i++;
   }
}

function PrintFormConfig() {
   global $ModPath, $ModStart, $ThisFile, $MaxSizeImg, $MaxSizeThumb, $imglign, $imgpage, $nbtopcomment, $nbtopvote, $view_alea, $view_last, $vote_anon, $comm_anon, $post_anon, $aff_vote, $aff_comm, $notif_admin;

   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormConfig\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"wrtconfig\">";
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\" width=\"100%\">";
   echo "<tr class=\"header\"><td colspan=\"2\">".adm_gal_trans("Configuration")."</td>";
   $rowcolor = tablos();
   echo "<tr $rowcolor><td align=\"left\" width=\"60%\">".adm_gal_trans("Dimension maximale de l'image en pixels :")."&nbsp;(1024px Max)</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"maxszimg\" size=\"10\" maxlength=\"4\" value=\"".$MaxSizeImg."\"></td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Dimension maximale de la miniature en pixels :")."&nbsp;(240px Max)</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"maxszthb\" size=\"10\" maxlength=\"3\" value=\"".$MaxSizeThumb."\"></td>";
   echo "</tr><tr><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Nombre d'images par ligne :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"nbimlg\" size=\"10\" maxlength=\"1\" value=\"".$imglign."\"></td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Nombre d'images par page :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"nbimpg\" size=\"10\" maxlength=\"2\" value=\"".$imgpage."\"></td>";
   echo "</tr><tr><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Nombre d'images à afficher dans le top commentaires").":"."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"nbimcomment\" size=\"10\" maxlength=\"2\" value=\"".$nbtopcomment."\"></td>";
   echo "</tr><tr><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Nombre d'images à afficher dans le top votes").":"."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"nbimvote\" size=\"10\" maxlength=\"2\" value=\"".$nbtopvote."\"></td>";
   echo "</tr><tr><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($view_alea) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Afficher des photos aléatoires ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"viewalea\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"viewalea\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($view_last) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Afficher les derniers ajouts ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"viewlast\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"viewlast\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   echo "</tr><tr><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($aff_vote) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Afficher les votes ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"votegal\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"votegal\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($aff_comm) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Afficher les commentaires ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"commgal\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"commgal\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor><td colspan=\"2\">&nbsp;</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($vote_anon) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Les anonymes peuvent voter ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"votano\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"votano\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($comm_anon) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Les anonymes peuvent poster un commentaire ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"comano\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"comano\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   if ($post_anon) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Les anonymes peuvent envoyer des E-Cartes ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"postano\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"postano\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";

   if ($notif_admin) { $rad1 = " checked"; $rad2 = ""; } else { $rad1 = ""; $rad2 = " checked"; }
   echo "<td align=\"left\">".adm_gal_trans("Notifier par email l'administrateur de la proposition de photos ?")."&nbsp;</td>";
   echo "<td><input type=\"radio\" name=\"notifadmin\" value=\"true\"".$rad1."> ".adm_translate("Oui")." ";
   echo "<input type=\"radio\" name=\"notifadmin\" value=\"false\"".$rad2."> ".adm_translate("Non")."</td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";

   echo "<td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Valider").">";
   echo "</td></tr></table></form>";
}

function WriteConfig($maxszimg,$maxszthb,$nbimlg,$nbimpg,$nbimcomment,$nbimvote,$viewalea,$viewlast,$vote,$comm,$votano,$comano,$postano,$notifadmin) {
   global $ModPath, $ModStart, $ThisRedo;

   if (!is_integer($maxszimg) && ($maxszimg > 1024)) {
      $msg_erreur = adm_gal_trans("Dimension maximale de l'image incorrecte");
      $erreur=true;
   }
   
   if (!is_integer($maxszthb) && ($maxszthb > 240) && !isset($erreur)) {
      $msg_erreur = adm_gal_trans("Dimension maximale de la miniature incorrecte");
      $erreur=true;
   }

   if (isset($erreur)) {
      OpenTable2();
      echo "<p align=\"center\" class=\"rouge\">".$msg_erreur."</p>";
      CloseTable2();
      exit;
   }
   
   if ($nbimpg < $nbimlg) { $nbimpg = $nbimlg; }
   $filename = "modules/".$ModPath."/gal_conf.php";
   $content = "<?php\n";
   $content.= "/************************************************************************/\n";
   $content.= "/* Module de gestion de galeries d'images pour NPDS                     */\n";
   $content.= "/* ===========================                                          */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* TD-Galerie Config File Copyright (c) 2004-2011 by Tribal-Dolphin     */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/************************************************************************/\n\n";
   $content.= "// Dimension max des images\n";
   $content.= "\$MaxSizeImg = ".$maxszimg.";\n\n";
   $content.= "// Dimension max des images mignatures\n";
   $content.= "\$MaxSizeThumb = ".$maxszthb.";\n\n";
   $content.= "// Nombre d'images par ligne\n";
   $content.= "\$imglign = ".$nbimlg.";\n\n";
   $content.= "// Nombre de photos par page\n";
   $content.= "\$imgpage = ".$nbimpg.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top commentaires\n";
   if (!$nbimcomment) $nbimcomment=5;
   $content.= "\$nbtopcomment = ".$nbimcomment.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top votes\n";
   if (!$nbimvote) $nbimvote=5;
   $content.= "\$nbtopvote = ".$nbimvote.";\n\n";   
   $content.= "// Personnalisation de l'affichage\n";
   $content.= "\$view_alea = ".$viewalea.";\n";
   $content.= "\$view_last = ".$viewlast.";\n";
   $content.= "\$aff_vote = ".$vote.";\n";
   $content.= "\$aff_comm = ".$comm.";\n\n";
   $content.= "// Autorisations pour les anonymes\n";
   $content.= "\$vote_anon = ".$votano.";\n";
   $content.= "\$comm_anon = ".$comano.";\n";
   $content.= "\$post_anon = ".$postano.";\n\n";
   $content.= "// Notifier par email l'administrateur de la proposition de nouvelles photos\n";
   $content.= "\$notif_admin = ".$notifadmin.";\n";

   // Version de TDGAL
   $content.= "\$TDGAL_version = \"2.5\";\n";

   $content.= "?>";
     
   if ($myfile = fopen("$filename", "wb")) {
      fwrite($myfile, "$content");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else {
      redirect_url($ThisRedo."&subop=config");
   }
}

function PrintArbo() {
   global $ModPath, $ModStart, $ThisFile, $NPDS_Prefix;

   // Retro compatibilité SABLE
   if (!function_exists("MM_img")) {
      include_once ("modules/$ModPath/retro-compat/togglediv.class.php");
      echo "\n<script type=\"text/javascript\" src=\"modules/$ModPath/retro-compat/cookies.js\"></script>\n";
   } else {
      include_once ("lib/togglediv.class.php");
   }
   // Retro compatibilité SABLE

   echo "<script type=\"text/javascript\">\n//<![CDATA[\n";
   echo "   function aff_image(img_id, img_src) {\n";
   echo "   var image_open = new Image();\n";
   echo "   image_open.src = img_src;\n";
   echo "   var image_closed = new Image();\n";
   echo "   image_closed.src = 'modules/$ModPath/data/img.gif'\n";
   echo "      if (document.all) {\n";
   echo "         if (document.all[img_id].src == image_closed.src) {\n";
   echo "            document.all[img_id].src = image_open.src;\n";
   echo "         } else {\n";
   echo "            document.all[img_id].src = image_closed.src;\n";
   echo "         }\n";
   echo "      } else {\n";
   echo "         if (document.getElementById(img_id).src == image_closed.src) {\n";
   echo "            document.getElementById(img_id).src = image_open.src;\n";
   echo "         } else {\n";
   echo "            document.getElementById(img_id).src = image_closed.src;\n";
   echo "         }\n";
   echo "      }\n";
   echo "   }";
   echo "   \n//]]>\n</script>\n";

   $toggle_import = new ToggleDiv(1);
   echo $toggle_import->Img();
   echo "<b>".adm_gal_trans("Galerie temporaire")."</b>";
   echo $toggle_import->Begin();
   $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='-1' ORDER BY id");
   // Image de la galerie temporaire
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
   while ($rowZ_img = sql_fetch_row($queryZ)) {
      $rowcolor = tablos();
      echo "<tr $rowcolor>\n<td width=\"24\">&nbsp;</td>\n";
      echo "<td width=\"15%\"><a href=\"modules.php?ModPath=$ModPath&amp;ModStart=gal&amp;op=one-img&amp;galid=-1&amp;pos=$rowZ_img[0]\" target=\"_blank\"><img src=\"modules/$ModPath/mini/$rowZ_img[2]\" border=\"0\" alt=\"".$rowZ_img[3]."\" title=\"".$rowZ_img[3]."\" /></a>&nbsp;</td>\n";
      echo "<td align=\"left\">&nbsp;ref : $rowZ_img[2]<br /><br />&nbsp;".stripslashes($rowZ_img[3])."</td>\n<td width=\"40\" align=\"right\">";
      if ($rowZ_img[6]==1)
         echo "<a href=\"".$ThisFile."&amp;subop=validimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/valid.gif\" alt=\"".adm_gal_trans("Valider")."\" title=\"".adm_gal_trans("Valider")."\" border=\"0\" /></a>&nbsp;&nbsp;";
      else
         echo "<a href=\"".$ThisFile."&amp;subop=editimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>&nbsp;&nbsp;";
      echo "<a href=\"".$ThisFile."&amp;subop=delimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a></td></tr>\n";
   }
   echo "</table>\n";
   echo $toggle_import->End();
   echo $toggle_import->Cookies_all();
   echo "<br /><br />\n";

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat == 0) {
      echo "<span class=\"rouge\">".adm_gal_trans("Aucune catégorie trouvée")."</span>";
   } else {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0";
      $num_sscat = sql_num_rows(sql_query($sql_sscat));
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      $num_gal = sql_num_rows(sql_query($sql_gal));

      $num_toggle = $num_cat + $num_sscat + $num_gal -1;
      $toggle = new ToggleDiv($num_toggle);
      echo "<hr noshade class=\"ongl\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr>\n<td>\n";
      echo $toggle->All();
      echo "</td>\n</tr>\n</table>\n<hr noshade class=\"ongl\">";
      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
        $rowcolor = tablos();
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr $rowcolor>\n<td>\n";
        echo $toggle->Img();
        echo stripslashes($row_cat[2])."</td>\n<td width=\"40\">";
        echo "<a href=\"".$ThisFile."&amp;subop=editcat&amp;catid=".$row_cat[0]."\">";
        echo "<img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>";
        echo "&nbsp;&nbsp;<a href=\"".$ThisFile."&amp;subop=delcat&amp;catid=".$row_cat[0]."\">";
        echo "<img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a>";
        echo "</td>\n</tr>\n</table>\n";
        echo $toggle->Begin();

        $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
        // Image de la galerie
        while ($rowX_gal = sql_fetch_row($queryX)) {
           $rowcolor = tablos();
           echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
           echo "<tr $rowcolor><td width=\"12\">&nbsp;</td><td>";
           echo $toggle->Img();
           echo stripslashes($rowX_gal[2])."&nbsp;<span style=\"font-size: 9px;\">(&nbsp;".adm_gal_trans("Galerie")."&nbsp;)</span></td>\n<td width=\"40\" align=\"right\">";
           echo "<a href=\"".$ThisFile."&amp;subop=editgal&amp;galid=".$rowX_gal[0]."\">";
           echo "<img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>";
           echo "&nbsp;&nbsp;<a href=\"".$ThisFile."&amp;subop=delgal&amp;galid=".$rowX_gal[0]."\">";
           echo "<img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a>";
           echo "</td>\n</tr>\n</table>\n";
           echo $toggle->Begin();
     
           $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id,noaff");
            // Image de la galerie
           echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">";
           echo "<form action=\"".$ThisFile."&amp;subop=ordre\" method=\"post\" name=\"FormArbo$rowX_gal[0]\">";
           echo "<input type=\"hidden\" name=\"subop\" value=\"ordre\">";
           $i=1;
           while ($rowZ_img = sql_fetch_row($queryZ)) {
              $rowcolor = tablos();
              echo "<tr $rowcolor>\n<td width=\"35\">&nbsp;</td>\n";
              if ($rowZ_img[6]==1) {
                 echo "<td width=\"150\" align=\"center\"><a href=\"modules.php?ModPath=$ModPath&amp;ModStart=gal&amp;op=one-img&amp;galid=$rowX_gal[0]&amp;pos=$rowZ_img[0]\" target=\"_blank\"><img src=\"modules/$ModPath/mini/$rowZ_img[2]\" border=\"0\" alt=\"mini/$rowZ_img[2]\" title=\"mini/$rowZ_img[2]\" /></a>&nbsp;</td>\n";
              } else {
                 echo "<td width=\"150\" align=\"center\"><a href=\"javascript: void(0);\" onMouseDown=\"aff_image('image$rowX_gal[0]_$i','modules/$ModPath/mini/$rowZ_img[2]');\"><img src=\"modules/$ModPath/data/img.gif\" id=\"image$rowX_gal[0]_$i\" border=\"0\" alt=\"mini/$rowZ_img[2]\" title=\"mini/$rowZ_img[2]\" /></a>";
              }
              echo "<td align=\"left\">&nbsp;".stripslashes($rowZ_img[3])."</td>\n";
              echo "<td width=\"40\" align=\"right\"><input class=\"textbox_standard\" type=\"text\" name=\"ordre[$i]\" value=\"$rowZ_img[5]\" size=\"3\" maxlength=\"11\">";
              echo "<input type=\"hidden\" name=\"img_id[$i]\" value=\"$rowZ_img[0]\">";
              echo "</td>";
              $i++;
              echo "<td width=\"40\" align=\"right\">";
              if ($rowZ_img[6]==1)
                 echo "<a href=\"".$ThisFile."&amp;subop=validimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/valid.gif\" alt=\"".adm_gal_trans("Valider")."\" title=\"".adm_gal_trans("Valider")."\" border=\"0\" /></a>&nbsp;&nbsp;";
              else
                 echo "<a href=\"".$ThisFile."&amp;subop=editimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>&nbsp;&nbsp;";
              echo "<a href=\"".$ThisFile."&amp;subop=delimg&amp;imgid=".$rowZ_img[0]."\"><img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a></td></tr>\n";
           }   // Fin Image De La Galerie
           if ($i!=1) {
              echo "<tr class=\"header\"><td colspan=\"5\" align=\"right\"><input class=\"bouton_standard\" type=\"submit\" value=\"".adm_gal_trans("MAJ ordre")."\"></td></tr>";
           }
           echo "<tr><td colspan=\"5\">&nbsp;</td></tr>";
           echo "</form>";
           echo "</table>\n";
           echo $toggle->End(); // Fin Toggle Galerie
        } // Fin Galerie Catégorie

        $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
        // SOUS-CATEGORIE
        while ($row_sscat = sql_fetch_row($query)) {
           $rowcolor = tablos();
           echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
           echo "<tr $rowcolor>\n<td width=\"12\">&nbsp;</td><td>\n";
           echo $toggle->Img();
           echo stripslashes($row_sscat[2])."&nbsp;<span style=\"font-size: 9px;\">(&nbsp;".adm_gal_trans("Sous-catégorie")."&nbsp;)</span></td>\n<td width=\"40\" align=\"right\">";
           echo "<a href=\"".$ThisFile."&amp;subop=editcat&amp;catid=".$row_sscat[0]."\">";
           echo "<img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>";
           echo "&nbsp;&nbsp;<a href=\"".$ThisFile."&amp;subop=delsscat&amp;sscatid=".$row_sscat[0]."\">";
           echo "<img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a>";
           echo "</td>\n</tr>\n</table>\n";
           echo $toggle->Begin();
           $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
           // SOUS-CATEGORIE
           while ($row_gal = sql_fetch_row($querx)) {
              $rowcolor = tablos();
              echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
              echo "<tr $rowcolor>\n<td width=\"24\">&nbsp;</td><td>\n";
              echo $toggle->Img();
              echo stripslashes($row_gal[2])."</td>\n<td width=\"40\" align=\"right\">";
              echo "<a href=\"".$ThisFile."&amp;subop=editgal&amp;galid=".$row_gal[0]."\">";
              echo "<img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>";
              echo "&nbsp;&nbsp;<a href=\"".$ThisFile."&amp;subop=delgal&amp;galid=".$row_gal[0]."\">";
              echo "<img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" />";
              echo "</td>\n</tr>\n</table>\n";
              echo $toggle->Begin();
        
              $querz = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id,noaff");
              // Image de la galerie
              echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">";
              echo "<form action=\"".$ThisFile."&amp;subop=ordre\" method=\"post\" name=\"FormArbo$row_gal[0]\">";
              echo "<input type=\"hidden\" name=\"subop\" value=\"ordre\">";
              $i=1;
              while($row_img = sql_fetch_row($querz)) {
                 $rowcolor = tablos();
                 echo "<tr $rowcolor>\n<td width=\"35\">&nbsp;</td>\n";
                 if ($row_img[6]==1) {
                    echo "<td width=\"150\" align=\"center\"><a href=\"modules.php?ModPath=$ModPath&amp;ModStart=gal&amp;op=one-img&amp;galid=$row_gal[0]&amp;pos=$row_img[0]\" target=\"_blank\"><img src=\"modules/$ModPath/mini/$row_img[2]\" border=\"0\" alt=\"mini/$row_img[2]\" title=\"mini/$row_img[2]\" /></a>&nbsp;</td>\n";
                 } else {
                    echo "<td width=\"150\" align=\"center\"><a href=\"javascript: void(0);\" onMouseDown=\"aff_image('image$row_gal[0]_$i','modules/$ModPath/mini/$row_img[2]');\"><img src=\"modules/$ModPath/data/img.gif\" id=\"image$row_gal[0]_$i\" border=\"0\" alt=\"mini/$row_img[2]\" title=\"mini/$row_img[2]\" /></a>";
                 }
                 echo "<td align=\"left\">&nbsp;".stripslashes($row_img[3])."</td>\n";
                 echo "<td width=\"40\" align=\"right\"><input class=\"textbox_standard\" type=\"text\" name=\"ordre[$i]\" value=\"$row_img[5]\" size=\"3\" maxlength=\"11\">";
                 echo "<input type=\"hidden\" name=\"img_id[$i]\" value=\"$row_img[0]\">";
                 echo "</td>";
                 $i++;
                 echo "<td width=\"40\" align=\"right\">";
                 if ($row_img[6]==1)
                    echo "<a href=\"".$ThisFile."&amp;subop=validimg&amp;imgid=".$row_img[0]."\"><img src=\"modules/$ModPath/data/valid.gif\" alt=\"".adm_gal_trans("Valider")."\" title=\"".adm_gal_trans("Valider")."\" border=\"0\" /></a>&nbsp;&nbsp;";
                 else
                    echo "<a href=\"".$ThisFile."&amp;subop=editimg&amp;imgid=".$row_img[0]."\"><img src=\"modules/$ModPath/data/edit.gif\" alt=\"".adm_gal_trans("Modifier")."\" title=\"".adm_gal_trans("Modifier")."\" border=\"0\" /></a>&nbsp;&nbsp;";
                 echo "<a href=\"".$ThisFile."&amp;subop=delimg&amp;imgid=".$row_img[0]."\"><img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a></td></tr>\n";
              }   // Fin Image De La Galerie
              if ($i!=1) {
                 echo "<tr class=\"header\"><td colspan=\"5\" align=\"right\"><input class=\"bouton_standard\" type=\"submit\" value=\"".adm_gal_trans("MAJ ordre")."\"></td></tr>";
              }
              echo "<tr><td colspan=\"5\">&nbsp;</td></tr>";
              echo "</form>";
              echo "</table>\n";
              echo $toggle->End(); // Fin Toggle Galerie
           } // Fin Galerie Sous Catégorie
           echo $toggle->End(); // Fin Toggle Sous-Catégorie
        } // Fin Sous Catégorie
        echo $toggle->End(); // Fin Toggle Catégorie
     } // Fin Catégorie
     echo "<hr noshade class=\"ongl\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr>\n<td>\n";
     echo $toggle->All();
     echo $toggle->Cookies_all();
     echo "</td>\n</tr>\n</table>\n<hr noshade class=\"ongl\">";
   }
}

function DelCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;

   if (empty($go)) {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td align=\"center\" class=\"rouge\">".adm_gal_trans("Vous allez supprimer la catégorie")." : $r_cat[0]</td></tr>";
      echo "<tr><td align=\"center\"><br /><a href=\"".$ThisFile."&amp;subop=delcat&amp;catid=".$id."&amp;go=true\" class=\"rouge\">";
      echo " ".adm_gal_trans("Confirmer")."</a> | <a href=\"".$ThisFile."\" class=\"noir\">".adm_gal_trans("Annuler")."</a>";
      echo "</td></tr></table>";
   } else {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      $q_sscat = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'");

      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><b>&nbsp;".$r_cat[0]."</b></td></tr>";

      // Il peut ne pas y avoir de sous-catégories
      $r_sscat = sql_fetch_row($q_sscat);
      do {
         $rowcolor=tablos();
         echo "<tr $rowcolor><td colspan=\"2\">&nbsp;>>".$r_sscat[0]."</td></tr>";
         $q_gal = sql_query("SELECT nom,id,cid FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$r_sscat[1]' OR cid='$id'");
         while ($r_gal = sql_fetch_row($q_gal)) {
            $rowcolor=tablos();
            if ($r_gal[2]==$r_sscat[1]) {
               $remp="&nbsp;&nbsp;&nbsp;&nbsp;";
            } else {
               $remp="";
            }
            echo "<tr $rowcolor><td colspan=\"2\">$remp&nbsp;>>".$r_gal[0]."</td></tr>";
            $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
            while ($r_img = sql_fetch_row($q_img)) {
               $m_img = "modules/$ModPath/mini/$r_img[0]";
               $g_img = "modules/$ModPath/imgs/$r_img[0]";
               $rowcolor=tablos();
               echo "<tr $rowcolor><td colspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>>".$r_img[0]."</td></tr>";
               if (@unlink($m_img)) {
                  echo "<tr $rowcolor><td width=\"40%\"></td><td>".adm_gal_trans("Miniature supprimée")."</td></tr>";
               } else {
                  echo "<tr $rowcolor><td width=\"40%\"></td><td class=\"rouge\">".adm_gal_trans("Miniature non supprimée")."</td></tr>";
               }
               if (@unlink($g_img)) {
                  echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Image supprimée")."</td></tr>";
               } else {
                  echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Image non supprimée")."</td></tr>";
               }
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'")) {
                  echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Votes supprimés")."</td></tr>";
               } else {
                  echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Votes non supprimés")."</td></tr>";
               }
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'")) {
                  echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Commentaires supprimés")."</td></tr>";
               } else {
                  echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Commentaires non supprimés")."</td></tr>";
               }
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'")) {
                  echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Enregistrement supprimé")."</td></tr>";
               } else {
                  echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Enregistrement non supprimé")."</td></tr>";
               }
            } // Fin du while img
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
               echo "<tr $rowcolor><td colspan=\"2\">$remp&nbsp;>> ".adm_gal_trans("Galerie supprimée")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">$remp&nbsp;>> ".adm_gal_trans("Galerie non supprimée")."</td></tr>";
            }
         } // Fin du while galerie
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'")) {
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;>> ".adm_gal_trans("Sous-catégorie supprimée")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">&nbsp;>> ".adm_gal_trans("Sous-catégorie non supprimée")."</td></tr>";
         }
      } while ($r_sscat = sql_fetch_row($q_sscat));
       // SousCat
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo "<tr $rowcolor><td colspan=\"2\">&nbsp;".adm_gal_trans("Catégorie supprimée")."</td></tr>";
      } else {
         echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">&nbsp;".adm_gal_trans("Catégorie non supprimée")."</td></tr>";
      }
      echo "</table>";
   }
}

function DelSsCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td align=\"center\" class=\"rouge\">".adm_gal_trans("Vous allez supprimer la sous-catégorie")." : $r_sscat[0]</td></tr>";
      echo "<tr><td align=\"center\"><br /><a href=\"".$ThisFile."&amp;subop=delsscat&amp;sscatid=".$id."&amp;go=true\" class=\"rouge\">";
      echo " ".adm_gal_trans("Confirmer")."</a> | <a href=\"".$ThisFile."\" class=\"noir\">".adm_gal_trans("Annuler")."</a>";
      echo "</td></tr></table>";  
   } else {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      $q_gal = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$id'");

      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><b>&nbsp;".$r_sscat[0]."</b></td></tr>";
      while ($r_gal = sql_fetch_row($q_gal)) {
         $rowcolor=tablos();
         echo "<tr $rowcolor><td colspan=\"2\">&nbsp;>>".$r_gal[0]."</td></tr>";
         $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
         while ($r_img = sql_fetch_row($q_img)) {
            $m_img = "modules/$ModPath/mini/$r_img[0]";
            $g_img = "modules/$ModPath/imgs/$r_img[0]";
            $rowcolor=tablos();
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>>".$r_img[0]."</td></tr>";
            if (@unlink($m_img)) {
               echo "<tr $rowcolor><td width=\"40%\"></td><td>".adm_gal_trans("Miniature supprimée")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td width=\"40%\"></td><td class=\"rouge\">".adm_gal_trans("Miniature non supprimée")."</td></tr>";
            }
            if (@unlink($g_img)) {
               echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Image supprimée")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Image non supprimée")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'")) {
               echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Votes supprimés")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Votes non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'")) {
               echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Commentaires supprimés")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Commentaires non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'")) {
               echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Enregistrement supprimé")."</td></tr>";
            } else {
               echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Enregistrement non supprimé")."</td></tr>";
            }
         } // Fin du while img
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
            echo "<tr $rowcolor><td colspan=\"2\">&nbsp;>>".adm_gal_trans("Galerie supprimée")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">&nbsp;>>".adm_gal_trans("Galerie non supprimée")."</td></tr>";
         }
      } // Fin du while galerie
      $rowcolor=tablos();
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo "<tr $rowcolor><td colspan=\"2\"><b>".adm_gal_trans("Sous-catégorie supprimée")."</b></td></tr>";
      } else {
         echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">".adm_gal_trans("Sous-catégorie non supprimée")."</td></tr>";
      }
      echo "</table>";   
   }
}

function DelGal($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;

   if (empty($go)) {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td align=\"center\" class=\"rouge\">".adm_gal_trans("Vous allez supprimer la galerie")." : $r_gal[0]</td></tr>";
      echo "<tr><td align=\"center\"><br /><a href=\"".$ThisFile."&amp;subop=delgal&amp;galid=".$id."&amp;go=true\" class=\"rouge\">";
      echo " ".adm_gal_trans("Confirmer")."</a> | <a href=\"".$ThisFile."\" class=\"noir\">".adm_gal_trans("Annuler")."</a>";
      echo "</td></tr></table>";  
   } else {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$id'");
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><b>&nbsp;".$r_gal[0]."</b></td></tr>";
      while ($r_img = sql_fetch_row($q_img)) {
         $m_img = "modules/$ModPath/mini/$r_img[0]";
         $g_img = "modules/$ModPath/imgs/$r_img[0]";
         $rowcolor=tablos();
         echo "<tr $rowcolor><td colspan=\"2\">&nbsp;&nbsp;>>".$r_img[0]."</td></tr>";
         if (@unlink($m_img)) {
            echo "<tr $rowcolor><td width=\"40%\"></td><td>".adm_gal_trans("Miniature supprimée")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td width=\"40%\"></td><td class=\"rouge\">".adm_gal_trans("Miniature non supprimée")."</td></tr>";
         }
         if (@unlink($g_img)) {
            echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Image supprimée")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Image non supprimée")."</td></tr>";
         }
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'")) {
            echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Votes supprimés")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Votes non supprimés")."</td></tr>";
         }
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'")) {
            echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Commentaires supprimés")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Commentaires non supprimés")."</td></tr>";
         }
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'")) {
            echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Enregistrement supprimé")."</td></tr>";
         } else {
            echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Enregistrement non supprimé")."</td></tr>";
         }
      }
      $rowcolor=tablos();
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'")) {
         echo "<tr $rowcolor><td colspan=\"2\"><b>".adm_gal_trans("Galerie supprimée")."</b></td></tr>";
      } else {
         echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">&nbsp;".adm_gal_trans("Galerie non supprimée")."</td></tr>";
      }
      echo "</table>";
   }
}

function EditImg($id) {
   global $ThisFile, $NPDS_Prefix, $ModPath;

   $queryA = sql_query("SELECT name,comment,gal_id FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
   $rowA = sql_fetch_row($queryA);
   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormModifImg\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"doeditimg\">";
   echo "<input type=\"hidden\" name=\"imgid\" value=\"$id\">";  
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   echo "<tr><td colspan=\"2\" class=\"header\">".adm_gal_trans("Informations")."</td></tr>";
   $rowcolor=tablos();
   echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Catégorie :")."&nbsp;</td>\n";
   echo "<td><select name=\"imggal\" size=\"1\" class=\"textbox_standard\">\n";
   echo select_arbo($rowA[2]);

   $rowcolor=tablos();
   echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Image :")."&nbsp;</td>";
   echo "<td><img src=\"modules/$ModPath/mini/".$rowA[0]."\" alt=\"".$rowA[0]."\" title=\"".$rowA[0]."\"></td></tr>";
   $rowcolor=tablos();
   echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newdesc\" value=\"".stripslashes($rowA[1])."\" size=\"80\" maxlength=\"250\"></td></tr>";
   echo "<tr><td colspan=\"2\" align=\"center\">\n";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=\"".adm_gal_trans("Modifier")."\">\n";
   echo "</td></tr></table></form>\n";

   $qcomment = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id' ORDER BY comtimestamp DESC");
   $num_comm = sql_num_rows($qcomment);
   
   OpenTable();
   echo "<table width=\"95%\" cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   while ($rowC = sql_fetch_row($qcomment)) {
      $rowcolor = tablos();
      echo "<tr class=\"header\"><td>".$rowC[2]."</td><td align=\"right\">".date(translate("dateinternal"),$rowC[5])."</td></tr>";
      echo "<tr $rowcolor><td>".stripslashes($rowC[3])."</td><td width=\"15%\" align=\"right\"><a href=\"".$ThisFile."&amp;subop=delcomimg&amp;id=".$rowC[0]."&amp;picid=".$rowC[1]."\"><img src=\"modules/$ModPath/data/del.gif\" alt=\"".adm_gal_trans("Effacer")."\" title=\"".adm_gal_trans("Effacer")."\" border=\"0\" /></a></td></tr>";
      echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";
   }
   echo "</table>";
   closetable();
}

function DoEditImg($id,$imggal,$newdesc) {
   global $ThisRedo, $NPDS_Prefix;

   $newtit = addslashes(removeHack($newdesc));
   if ($imggal=="") $imggal="-1";
   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET gal_id='$imggal', comment='$newtit' WHERE id='$id'")) {
      redirect_url($ThisRedo."&subop=viewarbo");
   } else {
      echo "<script type=\"text/javascript\">\n//<![CDATA[\nalert('Erreur lors de la modification de l'image');\n//]]>\n</script>";
      redirect_url($ThisRedo."&subop=editimg&imgid=$id");
   }
}

function DelImg($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td align=\"center\" class=\"rouge\">".adm_gal_trans("Vous allez supprimer une image")." : $r_img[0]</td></tr>";
      echo "<tr><td align=\"center\"><br /><a href=\"".$ThisFile."&amp;subop=delimg&amp;imgid=".$id."&amp;go=true\" class=\"rouge\">";
      echo " ".adm_gal_trans("Confirmer")."</a> | <a href=\"".$ThisFile."\" class=\"noir\">".adm_gal_trans("Annuler")."</a>";
      echo "</td></tr></table>";  
   } else {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      $m_img = "modules/$ModPath/mini/$r_img[0]";
      $g_img = "modules/$ModPath/imgs/$r_img[0]";
   
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><b>&nbsp;$r_img[0]</b></td></tr>";
      $rowcolor=tablos();
      if (@unlink($m_img)) {
         echo "<tr $rowcolor><td width=\"40%\"></td><td>".adm_gal_trans("Miniature supprimée")."</td></tr>";
      } else {
         echo "<tr $rowcolor><td width=\"40%\"></td><td class=\"rouge\">".adm_gal_trans("Miniature non supprimée")."</td></tr>";
      }
      if (@unlink($g_img)) {
         echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Image supprimée")."</td></tr>";
      } else {
         echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Image non supprimée")."</td></tr>";
      }
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$id'")) {
         echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Votes supprimés")."</td></tr>";
      } else {
         echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Votes non supprimés")."</td></tr>";
      }
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id'")) {
         echo "<tr $rowcolor><td></td><td>".adm_gal_trans("Commentaires supprimés")."</td></tr>";
      } else {
         echo "<tr $rowcolor><td></td><td class=\"rouge\">".adm_gal_trans("Commentaires non supprimés")."</td></tr>";
      }
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'")) {
         echo "<tr $rowcolor><td colspan=\"2\"><b>&nbsp;".adm_gal_trans("Enregistrement supprimé")."</b></td></tr>";
      } else {
         echo "<tr $rowcolor><td colspan=\"2\" class=\"rouge\">&nbsp;".adm_gal_trans("Enregistrement non supprimé")."</td></tr>";
      }
      echo "</table>";
   }
}

function DelComImg($id, $picid) {
   global $ThisRedo, $NPDS_Prefix;

   sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$picid' and id='$id'");
   redirect_url($ThisRedo."&subop=editimg&imgid=$picid");
}

function DoValidImg($id) {
   global $ThisRedo, $NPDS_Prefix;

   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET noaff='0' WHERE id='$id'")) {
      redirect_url($ThisRedo."&subop=viewarbo");
   }
}

function Edit($type,$id) {
   global $ThisFile, $NPDS_Prefix, $ThisRedo;
   if ($type=="Cat") {$query = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'";}
   if ($type=="Gal") {$query = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'";}
  
   $result = sql_query($query);
   if (!$row=sql_fetch_row($result)) {
      redirect_url($ThisRedo);
   } else {
      $actualname = stripslashes($row[2]);
   }

   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormRename\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"rename\">";
   echo "<input type=\"hidden\" name=\"type\" value=\"$type\">";
   echo "<input type=\"hidden\" name=\"gcid\" value=\"$id\">";
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   echo "<tr><td colspan=\"2\" class=\"header\">".adm_gal_trans("Informations")."</td></tr>";

   //déplacement d'une galerie
   if ($type=="Gal") {
      $rowcolor = tablos();
      echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Catégorie parente :")."&nbsp;</td>";
      echo "<td><select name=\"newgalcat\" size=\"1\" class=\"textbox_standard\">";
      echo cat_arbo($row[1]);
      echo "</select></td></tr>";
   }

   $rowcolor = tablos();
   echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Accès pour :")."&nbsp;</td>";
   if ($type=="Cat") {
      echo "<td><select class=\"textbox_standard\" type=\"select\" name=\"newacces\" size=\"1\">".Fab_Option_Group($row[3])."</select></td></tr>";
   }
   if ($type=="Gal") {
      echo "<td><select class=\"textbox_standard\" type=\"select\" name=\"newacces\" size=\"1\">".Fab_Option_Group($row[4])."</select></td></tr>";
   }
   $rowcolor = tablos();
   echo "<tr $rowcolor><td align=\"left\">".adm_gal_trans("Nom actuel :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"actualname\" size=\"50\" value=\"".$actualname."\" disabled=\"true\"></td>";
   $rowcolor = tablos();
   echo "</tr><tr $rowcolor>";
   echo "<td align=\"left\">".adm_gal_trans("Nouveau nom :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"newname\" size=\"50\" maxlength=\"150\" value=\"".$actualname."\"></td>";
   echo "</tr><tr>";
   echo "<td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Modifier").">";
   echo "</td></tr></table></form>";
}

function ChangeName($type,$id,$valeur,$galcat,$acces) {
   global $NPDS_Prefix, $ThisRedo;

   if ($type=="Cat") {$query = "UPDATE ".$NPDS_Prefix."tdgal_cat SET nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";}
   if ($type=="Gal") {$query = "UPDATE ".$NPDS_Prefix."tdgal_gal SET cid=\"$galcat\", nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";}
   $update = sql_query($query);
   redirect_url($ThisRedo);
}

function OpenTableGal() {
   echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"ligna\"><tr><td>\n";
   echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"6\" class=\"lignb\">\n";
}

function CloseTableGal() {
   echo "</table></td></tr></table>\n";
}

function PrintJavaCodeGal() {
   global $NPDS_Prefix;
   $query = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_name");
   $nbgrp = sql_num_rows($query);

   while ($mX = sql_fetch_row($query)) {
      $tmp_groupe[$mX['groupe_id']]=$mX['groupe_name'];
   }

   echo "<script type=\"text/javascript\">\n//<![CDATA[\n";
   echo "var cde_all = new Array;\n";
   echo "var txt_all = new Array;\n";
   echo "var cde_usr = new Array;\n";
   echo "var txt_usr = new Array;\n";
   echo "cde_all[0] = '0'; txt_all[0] = '".adm_translate("Public")."';\n";
   echo "cde_usr[0] = '1'; txt_usr[0] = '".adm_translate("Utilisateur enregistré")."';\n";
   echo "cde_all[1] = '1'; txt_all[1] = '".adm_translate("Utilisateur enregistré")."';\n";
   echo "cde_usr[1] = '-127'; txt_usr[1] = '".adm_gal_trans("Administrateurs")."';\n";
   echo "cde_all[2] = '-127'; txt_all[2] = '".adm_gal_trans("Administrateurs")."';\n";
   if (count($tmp_groupe) != 0) {
      $i = 3;
      while (list($val, $nom) = each($tmp_groupe)) {
         echo "cde_usr[".($i-1)."] = '".$val."'; txt_usr[".($i-1)."] = '".$nom."';\n";
         echo "cde_all[".$i."] = '".$val."'; txt_all[".$i."] = '".$nom."';\n";
         $i++;
      }
   }
   echo "\n";
   echo "function verif() {\n";
   echo "  if (document.layers) {\n";
   echo "    formulaire = document.forms.FormCreer;\n";
   echo "  } else {\n";
   echo "    formulaire = document.FormCreer;\n";
   echo "  }\n";
   echo "  formulaire.acces.options.length = 1;\n";
   echo "}\n\n";
   echo "function remplirAcces(index,code) {\n";
   echo "  verif();\n";
   echo "  if(code.substring(code.lastIndexOf('(')+1) == '".adm_translate("Public").")') { //All\n";
   echo "    formulaire.acces.options.length = cde_all.length;\n";
   echo "    for(i=0; i<cde_all.length; i++) {\n";
   echo "      formulaire.acces.options[i].value = cde_all[i];\n";
   echo "      formulaire.acces.options[i].text = txt_all[i];\n";
   echo "    }\n";
   echo "  } else if(code.substring(code.lastIndexOf('(')+1) == '".adm_translate("Utilisateur enregistré").")') { //User\n";
   echo "    formulaire.acces.options.length = cde_usr.length;\n";
   echo "    for(i=0; i<cde_usr.length; i++) {\n";
   echo "      formulaire.acces.options[i].value = cde_usr[i];\n";
   echo "      formulaire.acces.options[i].text = txt_usr[i];\n";
   echo "    }\n";
   echo "  } else {\n";
   echo "    formulaire.acces.options.length = 1;\n";
   echo "    for(i=0; i<cde_all.length; i++) {\n;";
   echo "      if(code.substring(code.lastIndexOf('(')+1) == txt_all[i]+')') {\n";
   echo "        formulaire.acces.options[0].value = cde_all[i];\n";
   echo "        formulaire.acces.options[0].text = txt_all[i];\n";
   echo "      }\n";
   echo "    }\n";
   echo "  }\n";
   echo "}";
   echo "\n//]]>\n</script>\n";
}

function Fab_Option_Group($GrpActu="0") {
   $tmp_group = Get_Name_Group("list", $GrpActu);
   while (list($val, $nom) = each($tmp_group)) {
      if ($val == $GrpActu) {
         $txt.= "<option value=\"".$val."\" selected>&nbsp;".$nom."&nbsp;</option>";
      } else {
         $txt.= "<option value=\"".$val."\">&nbsp;".$nom."&nbsp;</option>";
      }
   }
   return $txt;
}

function Get_Name_Group($ordre, $GrpActu) {
   $tmp_groupe = liste_group("");
   $tmp_groupe[127] = adm_gal_trans("Administrateurs");
   $tmp_groupe[0] = adm_translate("Public");
   $tmp_groupe[1] = adm_translate("Utilisateur enregistré");
   if ($ordre=="list") {
      asort($tmp_groupe);
      return ($tmp_groupe);
   } else {
      return ($tmp_groupe[abs($GrpActu)]);
   }
}

function GetGalCat($galcid) {
   global $NPDS_Prefix;

   $query = sql_query("SELECT nom,cid FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$galcid."'");
   $row = sql_fetch_row($query);
  
   if ($row[1] == 0) {
      return stripslashes($row[0]);
   } else {
      $queryX = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$row[1]."'");
      $rowX = sql_fetch_row($queryX);
      return stripslashes($rowX[0])." - ".stripslashes($row[0]);
   }
}

// CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
function CreateThumb($Image, $Source, $Destination, $Max, $ext) {
   if ($ext=="gif") {
      if (function_exists("imagecreatefromgif"))
         $src=@imagecreatefromgif($Source.$Image);
   } else {
      $src=@imagecreatefromjpeg($Source.$Image);
   }
   if ($src) {
      $size = getimagesize($Source.$Image);
      $h_i = $size[1]; //hauteur
      $w_i = $size[0]; //largeur

      if (($h_i > $Max) || ($w_i > $Max)) {
         if ($h_i > $w_i) {
            $convert = $Max/$h_i;
            $h_i = $Max;
            $w_i = ceil($w_i*$convert);
         } else {
            $convert = $Max/$w_i;
            $w_i = $Max;
            $h_i = ceil($h_i*$convert);
         }
       }

      if (function_exists("imagecreatetruecolor")) {
         $im = @imagecreatetruecolor($w_i, $h_i);
      } else {
         $im = @imagecreate($w_i, $h_i);
      }
  
      @imagecopyresized($im, $src, 0, 0, 0, 0, $w_i, $h_i, $size[0], $size[1]);
      @imageinterlace ($im,1);
      if ($ext=="gif") {
         @imagegif($im, $Destination.$Image);
      } else {
         @imagejpeg($im, $Destination.$Image, 100);
      }
      @chmod($Dest.$Image,0766);
   }
}

function import() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
   echo "<form method=\"post\" action=\"".$ThisFile."\" name=\"MassImport\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"massimport\">";

   echo "<tr><td align=\"right\">".adm_gal_trans("Galerie :")."&nbsp;</td>";
   echo "<td><select name=\"imggal\" size=\"1\" class=\"textbox_standard\">";
   echo select_arbo("");
   echo "</select></td></tr>";
   echo "<tr><td align=\"right\">".adm_gal_trans("Description :")."&nbsp;</td>";
   echo "<td><input class=\"textbox_standard\" type=\"text\" name=\"descri\" size=\"80\" maxlength=\"250\"></td></tr>";

   echo "<tr><td colspan=\"2\" align=\"center\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Importer").">";
   echo "</td></tr></form></table>";
}
function massimport($imggal, $descri) {
   global $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");

   $handle=opendir("modules/$ModPath/import");
   while ($file = readdir($handle)) $filelist[] = $file;
   closedir($handle);
   asort($filelist);

   $i=1;
   while (list ($key, $file) = each ($filelist)) {
      if (preg_match('#\.gif|\.jpg$#i', strtolower($file))) {
         $filename_ext = strtolower(substr(strrchr($file, "."),1));
         $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
         rename("modules/$ModPath/import/$file","modules/$ModPath/import/$newfilename");
         if ((function_exists('gd_info')) or extension_loaded('gd')) {
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
         }
         if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imggal','$newfilename','$descri','','0','0')")) {
            echo "<b>".adm_gal_trans("Image ajoutée avec succès")." : ".$file."</b><br />";
            $i++;
         } else {
            echo "<span class=\"rouge\">".adm_gal_trans("Impossible d'ajouter l'image en BDD")."</span><br />";
            @unlink ("modules/$ModPath/imgs/$newfilename");
            @unlink ("modules/$ModPath/mini/$newfilename");
         }
         @unlink ("modules/$ModPath/import/$newfilename");
      }
   }
}

function ordre($ximg, $xordre) {
   global $ThisRedo, $NPDS_Prefix;

   while(list($ibid,$img_id)=each($ximg)) {
      echo $img_id, $xordre[$ibid]."<br>";
      sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET ordre='$xordre[$ibid]' WHERE id='$img_id'");
   }
   redirect_url($ThisRedo."&subop=viewarbo");
}

function PrintExportCat() {
   global $NPDS_Prefix, $ThisFile;

   opentable();
   echo "<table cellspacing=\"0\" cellpading=\"2\" border=\"0\">";
   echo "<form action=\"".$ThisFile."\" method=\"post\" name=\"FormCat\">";
   echo "<input type=\"hidden\" name=\"subop\" value=\"massexport\">";
   echo "<tr><td align=\"left\">".adm_gal_trans("Nom de la catégorie :")."&nbsp;</td>";
   echo "<td><select name=\"cat\" class=\"textbox_standard\" size=\"1\">";
   echo "<option value=\"none\" selected>".adm_gal_trans("Choisissez")."</option>";
   $query = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo "<option value=".$row[0].">".stripslashes($row[1])."</option>\n";
   }
   echo "</select></td>";
   echo "</tr><tr><td colspan=\"2\" align=\"left\">";
   echo "<br /><input class=\"bouton_standard\" type=\"submit\" value=".adm_gal_trans("Exporter").">";
   echo "</td></tr></form></table>";
   closetable();
}

function MassExportCat($cat) {
   global $NPDS_Prefix, $ThisRedo, $ModPath;

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$cat'");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid=$cat";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      $nb_gal=0;
      $nb_img=0;
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.="INSERT INTO tdgal_cat VALUES ($row_cat[0], $row_cat[1], '".htmlentities($row_cat[2])."',$row_cat[3]);\n";
         $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            $ibid.="INSERT INTO tdgal_gal VALUES ($rowX_gal[0], $rowX_gal[1], '".htmlentities($rowX_gal[2])."', $rowX_gal[3], $rowX_gal[4]);\n";
            $nb_gal++;
            // trouver les images
            $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id");
            while ($rowZ_img = sql_fetch_row($queryZ)) {
               copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
               copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
               $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $rowX_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0);\n";
               $nb_img++;
            }
         }
         $ibid.="\n";
         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.="INSERT INTO tdgal_cat VALUES ($row_sscat[0], $row_sscat[1], '".htmlentities($row_sscat[2])."',$row_sscat[3]);\n";
            $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               $ibid.="INSERT INTO tdgal_gal VALUES ($row_gal[0], $row_gal[1], '".htmlentities($row_gal[2])."', $row_gal[3], $row_gal[4]);\n";
               $nb_gal++;
               // trouver les images
               $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id");
               while ($rowZ_img = sql_fetch_row($queryZ)) {
                  copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
                  copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
                  $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $row_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0);\n";
                  $nb_img++;
               }
            }
         }
      }
   }
   $ibid.="\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Nombre de galeries exportées : $nb_gal\n";
   $ibid.="# Nombre d'images exportées : $nb_img\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Attention les numeros de catégories et  \n";
   $ibid.="# de galeries peuvent être en conflit avec\n";
   $ibid.="# ceux de votre TD-Galerie.  \n";
   $ibid.="# ----------------------------------------\n";

   if ($myfile = fopen("modules/$ModPath/export/sql/export.sql", "wb")) {
      fwrite($myfile, "$ibid");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else {
      redirect_url($ThisRedo);
   }
}
?>