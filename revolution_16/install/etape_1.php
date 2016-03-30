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

function etape_1() {
   global $stage,$cms_name, $langue;
   $stage = 0;
   $accueil = 'install/languages/'.$langue.'/bienvenue.txt';
   echo '
   <form name="welcome" method="post" action="install.php">
   <div class="row">
   <div class="col-md-12">
    <h3>'.ins_translate('Nouvelle installation').'&nbsp;'.$cms_name.'</h3>
    '.ins_translate('Bienvenue').',<br />';
      $id_fr = fopen("$accueil", "r");
      fpassthru($id_fr);
   echo '
    </div>
    </div>
    <input type="hidden" name="stage" value="2" />
    <input type="hidden" name="langue" value="'.$langue.'" />
    <button type="submit" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i>'.ins_translate(' Etape suivante ').'</button>
    </form></div>';
}
?>