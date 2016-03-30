<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2016 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"install.php")) { die(); }

function etape_3() {
 global $stage, $langue, $stopngo, $table_rep;
 $stage = 3;
 echo '<form name="path" method="post" action="install.php">
   <h3>'.ins_translate('Vérification des fichiers').'</h3>

<ul>';
  verif_php();
  verif_chmod();
  if($stopngo == 1)
  {
   echo '</ul>
   </form>
   <div class="alert alert-warning" role="alert">'.ins_translate("Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.").'</div>
   <form name="reload" method="post">
   <button onclick="window.location.reload()" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i>'.ins_translate(' Actualiser ').'</button>
   
   </form></div>';
pied_depage();
   exit;
 }
 else
 {
  echo '</ul>

   <input type="hidden" name="langue" value="'.$langue.'" />
   <input type="hidden" name="stage" value="4" />
   <button type="submit" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i>'.ins_translate(' Etape suivante ').'</button>
   </form></div>';
 }
}
?>