<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='setban';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
include ("modules/$ModPath/lang/ipban.lang-$language.php");
$f_titre = ipban_translate("Administration de l'IpBan");

function ConfigureBan($ModPath, $ModStart) {
global $f_meta_nom, $f_titre, $adminimg,$language;
   if (file_exists("slogs/spam.log")) {
      $fd = fopen ("slogs/spam.log", "r");
      while (!feof($fd)) {
         $ip_ban.= fgets($fd, 4096);
      }
      fclose ($fd);
   }
adminhead ($f_meta_nom, $f_titre, $adminimg);
echo '
   <hr />
      <div class="card card-block">
         '.ipban_translate("Chaque ligne ne doit contenir qu'une adresse IP de la forme : a.b.c.d:X (ex : 168.192.1.1:5).").'<br />
         <span class="text-danger">'.ipban_translate("Si X >= 5 alors l'accès sera refusé !").'</span><br />
         '.ipban_translate("Ce fichier est mis à jour automatiquement par l'anti-spam de NPDS.").'
      </div>
      <form id="ipban_mod" action="admin.php" method="post">
         <div class="form-group row">
            <label class="form-control-label col-sm-12" for="ip_ban">'.ipban_translate("Liste des IP").'</label>
            <div class="col-sm-12">
               <textarea id="ip_ban" class="form-control" name="ipban" rows="15">'.$ip_ban.'</textarea>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-12">
               <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
               <input type="hidden" name="op" value="Extend-Admin-SubModule" />
               <input type="hidden" name="ModPath" value="'.$ModPath.'" />
               <input type="hidden" name="ModStart" value="'.$ModStart.'" />
               <input type="hidden" name="subop" value="SaveSetBan" />
            </div>
         </div>
      </form>';
   adminfoot('','','','');
}

function SaveSetBan($Xip_ban) {
   $file = fopen("slogs/spam.log", "w");
      fwrite($file, $Xip_ban);
   fclose($file);
   SC_clean();
}

switch ($subop) {
   case 'SaveSetBan':
   SaveSetBan($ipban);
   default:
   ConfigureBan($ModPath, $ModStart);
   break;
}
?>