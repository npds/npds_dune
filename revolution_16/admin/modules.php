<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2018 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/************************************************************************/
/* Module-Install Version 1.1 - Mai 2005                                */
/* --------------------------                                           */
/* Copyright (c) 2005 Boris L'Ordi-Dépanneur & Hotfirenet               */
/*                                                                      */
/* Version 1.2 - 22 Avril 2009                                          */
/* --------------------------                                           */
/*                                                                      */
/* Modifié par jpb et phr pour le rendre compatible avec Evolution      */
/* Version 1.3 - 2015                                                   */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
$f_meta_nom ='modules';
$f_titre = adm_translate("Gestion, Installation Modules");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
$hlpfile = "manuels/$language/modules.html";

global $language,$adminimg, $admf_ext;

   include("header.php");
   GraphicAdmin($hlpfile);

   $handle=opendir('modules');
   $modlist='';
   while (false!==($file=readdir($handle))) {
      if (!@file_exists("modules/$file/kernel")) {
        if (is_dir("modules/$file") and ($file!='.') and ($file!='..'))
           $modlist.="$file ";
      }
   }
   closedir($handle);
   $modlist=explode(' ',rtrim($modlist));

   $whatondb = sql_query("SELECT mnom FROM ".$NPDS_Prefix."modules" );
   while ($row=sql_fetch_row($whatondb)) {
      if(!in_array($row[0],$modlist)){sql_query("DELETE FROM ".$NPDS_Prefix."modules WHERE mnom='".$row[0]."'");};
   }
   foreach ($modlist as $value) {
      $queryexiste=sql_query("SELECT mnom FROM ".$NPDS_Prefix."modules WHERE mnom='".$value."'");
      $moexiste=sql_num_rows($queryexiste);
      if ($moexiste!==1)
         sql_query("INSERT INTO ".$NPDS_Prefix."modules VALUES (NULL, '".$value."', '0')");
   }

   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Les modules").'</h3>
   <table id="tad_modu" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
      <thead>
         <tr>
            <th class="n-t-col-xs-1"><img class="adm_img" src="images/admin/module.png" alt="icon_module" /></th>
            <th data-sortable="true">'.adm_translate('Nom').'</th>
            <th class="n-t-col-xs-2" data-align="center">'.adm_translate('Fonctions').'</th>
         </tr>
      </thead>
      <tbody>';

   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."modules ORDER BY mid");
   while ($row = sql_fetch_assoc($result)) {
      $icomod='';
      if (file_exists("modules/".$row["mnom"]."/".$row["mnom"].".png"))
         $icomod='<img class="adm_img" src="modules/'.$row["mnom"].'/'.$row["mnom"].'.png" alt="icon_'.$row["mnom"].'" title="" />';
      else
         $icomod='<img class="adm_img" src="images/admin/module.png" alt="icon_module" title="" />';

      if ($row["minstall"] == 0) {
         if (file_exists("modules/".$row["mnom"]."/install.conf.php"))
            $status_chngac = '<a href="admin.php?op=Module-Install&amp;ModInstall='.$row["mnom"].'" ><i class="fa fa-spinner fa-2x text-success" title="'.adm_translate("Installer le module").'" data-toggle="tooltip"></i></a>';
         else
            $status_chngac = '<a href="admin.php?op=Module-Install&amp;ModInstall='.$row["mnom"].'&amp;subop=install"><i class="fa fa-check-square-o fa-2x" title="'.adm_translate("Pas d'installeur disponible").' '.adm_translate("Marquer le module comme installé").'" data-toggle="tooltip"></i></a>';
      }
      else
         $status_chngac = '<a href="admin.php?op=Module-Install&amp;ModDesinstall='.$row["mnom"].'" ><i class="fa fa-ban fa-2x text-danger" title="'.adm_translate("Désinstaller le module").'" data-toggle="tooltip"></i></a>';
      echo '
          <tr>
             <td align="center">'.$icomod.'</td>
             <td align="left">'.$row["mnom"].'</td>
             <td align="left">'.$status_chngac.'</td>
          </tr>';
   }
   echo '
      </tbody>
   </table>';
   adminfoot('','','','');
?>