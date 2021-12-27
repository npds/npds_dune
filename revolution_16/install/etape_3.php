<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'install.php')) die();

function etape_3() {
   global $menu, $stage, $langue, $qi, $stopngo, $table_rep, $stopphp, $phpver, $listfich, $colorst1, $colorst2, $colorst3, $colorst4;
   $stage = 3;
   verif_php();
   verif_chmod();
   $colorst1 = '-success';
   $colorst2 = '-success';
   $colorst3 = '-success';
   $colorst4 = ' active';
   if ($stopphp == 1) $colorst4 = '-danger';
   if ($stopngo == 1) $colorst4 = '-danger';
   if ($stopngo != 1 and $stopphp != 1 and $qi==1) $stage = 4; 
   else {
      entete();
      menu();
      echo $menu;
      echo '
               <h3 class="mb-3">'.ins_translate('Vérification des fichiers').'</h3>';
      if ($stopphp != 0) {
         echo '
               <div>'.ins_translate("Version actuelle de PHP").' : '.$phpver.'</div>
               <div class="alert alert-danger">'.ins_translate("NPDS nécessite une version 5.3.0 ou supérieure !").'</div>
               <form name="reload" method="post">
                  <button onclick="window.location.reload()" class="btn btn-danger">'.ins_translate('Actualiser').'</button>
               </form>';
         pied_depage();
      }
      echo '
               <form name="path" method="post" action="install.php">
                  <ul class="list-group mb-3 mt-1">';
      if ($stopphp != 1) echo $listfich;
      if ($stopngo == 1) {
         echo '
                  </ul>
               </form>
               <div class="alert alert-danger" role="alert">'.ins_translate("Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.").'</div>
               <form name="reload" method="post">
                  <button onclick="window.location.reload()" class="btn btn-danger">'.ins_translate('Actualiser').'</button>
               </form>
            </div>';
         pied_depage();
         exit;
      }
      else {
         echo '
               </ul>
               <div class="mb-3 mt-3">
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <input type="hidden" name="stage" value="4" />
                  <button type="submit" class="btn btn-success">'.ins_translate('Etape suivante').'</button>
               </div>
            </form>
         </div>';
      }
      pied_depage();
   }
}
?>