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

function etape_5() {
   global $langue, $stage, $qi;
   $stage = 5;
   include_once('config.php');
   echo '
               <h3 class="m-b-2">'.ins_translate('Autres paramètres').'</h3>
               <div class="col-sm-12">
                  <form id="others_parameters" name="others_parameters" method="post" action="install.php">
                  <div class="form-group row">
                     <label class="form-control-label" for="new_nuke_url">'.ins_translate('Adresse (URL) de votre site').'</label>
                     <input class="form-control" type="url" name="new_nuke_url" id="new_nuke_url" maxlength="80" value="'.$nuke_url.'" required="required" />
                     <small>'.ins_translate('Exemples :').'<br />http://www.monsite.com<br />http://www.monsite.com/npds</small>
                     <span class="help-block text-xs-right" id="countcar_new_nuke_url"></span>
                  </div>
                  <div class="form-group row">
                     <label class="form-control-label" for="new_sitename">'.ins_translate('Nom de votre site').'</label>
                     <input class="form-control" type="text" name="new_sitename" id="new_sitename" maxlength="80" value="'.$sitename.'" />
                     <span class="help-block text-xs-right" id="countcar_new_sitename"></span>
                  </div>
                  <div class="form-group row">
                     <label class="form-control-label" for="new_Titlesitename">'.ins_translate('Intitulé de votre site').'</label>
                     <input class="form-control" type="text" name="new_Titlesitename" id="new_Titlesitename" maxlength="80" value="'.$Titlesitename.'" />
                     <span class="help-block text-xs-right" id="countcar_new_Titlesitename"></span>
                  </div>
                  <div class="form-group row">
                     <label class="form-control-label" for="new_slogan">'.ins_translate('Slogan de votre site').'</label>
                     <input class="form-control" type="text" name="new_slogan" id="new_slogan" maxlength="80" value="'.$slogan.'" />
                     <span class="help-block text-xs-right" id="countcar_new_slogan"></span>
                  </div>
                  <div class="form-group row">
                     <label class="form-control-label" for="new_Default_Theme">'.ins_translate('Thème graphique').'</label>
                     <select class="custom-select form-control" name="new_Default_Theme" />';
   include('themes/list.php');
   $themelist = explode(' ', $themelist);
   for($i = 0; $i < sizeof($themelist); $i++) {
      if($themelist[$i] != '') {
         echo '
                        <option value="'.$themelist[$i].'">'.$themelist[$i].'</option>';
      }
   }
   echo '
                     </select>
                  </div>';
   $today = getdate();
   $tday = $today[mday];
   $tmon = $today[mon];
   $tyear = $today[year];
   if($tday < 10) { $tday = '0'.$tday; }
   if($tmon < 10) { $tmon = '0'.$tmon; }
   if($langue == 'english') {$startdate = $tmon.'/'.$tday.'/'.$tyear;}
   else {$startdate = $tday.'/'.$tmon.'/'.$tyear;}
   echo '
                  <div class="form-group row">
                     <input type="hidden" name="langue" value="'.$langue.'" />
                     <input type="hidden" name="stage" value="'.$stage.'" />
                     <input type="hidden" name="new_startdate" value="'.$startdate.'" />
                     <input type="hidden" name="op" value="write_others" />
                     <input type="hidden" name="qi" value="'.$qi.'" />
                     <button type="submit" class="btn btn-success">'.ins_translate(' Modifier ').'</button>
                  </div>
               </form>
            </div>';
   $fieldlength = '
            inpandfieldlen("new_nuke_url",80);
            inpandfieldlen("new_sitename",80);
            inpandfieldlen("new_Titlesitename",80);
            inpandfieldlen("new_slogan",80);';
            formval('fv','',$fieldlength,'1');

}
?>