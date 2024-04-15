<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.3                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'install.php')) die();

function etape_1() {
   global $stage, $cms_name, $langue;
   $stage = 0;
   echo '
               <div class="row">
                  <div class="col-sm-12">
                     <h3 class="mb-3">'.ins_translate('Nouvelle installation').'&nbsp;'.$cms_name.'</h3>
    '.ins_translate('Bienvenue').',<br />'.ins_translate("Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes votre nouveau portail NPDS.").'<br /><br />'.ins_translate("Merci encore d'avoir choisi").' <a href="http://www.npds.org" target="_blank">NPDS</a><br /><br />Dev<br />developpeur@npds.org
                  </div>
               </div>
               <form name="welcome" method="post" action="install.php" class="mt-3">
                  <div class="mb-3">
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="qi" name="qi" value="1" />
                        <label class="form-check-label" for="qi">'.ins_translate("Installation rapide").'</label>
                        <span class="help-block small">'.ins_translate("Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation.").'</span>
                    </div>
                  </div>
                  <input type="hidden" name="stage" value="2" />
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <button type="submit" class="btn btn-success">'.ins_translate('Etape suivante').'</button>
               </form>
            </div>';
}
?>