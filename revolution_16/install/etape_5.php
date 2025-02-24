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

function etape_5() {
   global $langue, $stage, $qi;
   $stage = 5;
   settype($nuke_url, 'string');
   settype($sitename, 'string');
   settype($Titlesitename, 'string');
   settype($slogan, 'string');
   include_once('config.php');
   echo '
               <h3 class="mb-3">'.ins_translate('Autres paramètres').'</h3>
               <div class="col-sm-12">
                  <form id="others_parameters" name="others_parameters" method="post" action="install.php">
                  <div class="form-floating mb-3">
                     <input class="form-control" type="url" name="new_nuke_url" id="new_nuke_url" maxlength="80" value="'.$nuke_url.'" required="required" data-fv-uri___allow-local="true" />
                     <label class="col-form-label" for="new_nuke_url">'.ins_translate('Adresse (URL) de votre site').'</label>
                     <div class="d-flex justify-content-start w-100 small text-help py-1"><div>'.ins_translate('Exemples :').' http://www.monsite.com | http://www.monsite.com/npds | http://127.0.0.1</div><div class="ms-auto" id="countcar_new_nuke_url"></div></div>
                  </div>
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="new_sitename" id="new_sitename" maxlength="80" value="'.$sitename.'" required="required" />
                     <label for="new_sitename">'.ins_translate('Nom de votre site').'</label>
                     <div class="d-flex justify-content-end w-100 small text-help py-1" id="countcar_new_sitename"></div>
                  </div>
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="new_Titlesitename" id="new_Titlesitename" maxlength="80" value="'.$Titlesitename.'" />
                     <label for="new_Titlesitename">'.ins_translate('Intitulé de votre site').'</label>
                     <div class="d-flex justify-content-end w-100 small text-help py-1" id="countcar_new_Titlesitename"></div>
                  </div>
                  <div class="form-floating mb-3">
                     <input class="form-control" type="text" name="new_slogan" id="new_slogan" maxlength="80" value="'.$slogan.'" />
                     <label for="new_slogan">'.ins_translate('Slogan de votre site').'</label>
                     <div class="d-flex justify-content-end w-100 small text-help py-1" id="countcar_new_slogan"></div>
                  </div>
                  <div class="form-floating mb-3">
                     <select class="form-select" id="new_Default_Theme" name="new_Default_Theme" />';
   include('themes/list.php');
   $themelist = explode(' ', $themelist);
   $sel='';
   $themelistsize = sizeof($themelist);
   for($i = 0; $i < $themelistsize; $i++) {
      if($themelist[$i] != '') {
         if($themelist[$i] == 'npds-boost_sk') $sel=' selected="selected"'; else $sel='' ;
            echo '
                        <option value="'.$themelist[$i].'"'.$sel.'>'.$themelist[$i].'</option>';
      }
   }
   echo '
                     </select>
                     <label for="new_Default_Theme">'.ins_translate('Thème graphique').'</label>
                  </div>';
   $today = getdate();
   $tday = $today['mday'];
   $tmon = $today['mon'];
   $tyear = $today['year'];
   if($tday < 10) $tday = '0'.$tday;
   if($tmon < 10) $tmon = '0'.$tmon;
   if($langue == 'english') $startdate = $tmon.'/'.$tday.'/'.$tyear;
   else $startdate = $tday.'/'.$tmon.'/'.$tyear;
   echo '
                  <div class="mb-3">
                     <input type="hidden" name="langue" value="'.$langue.'" />
                     <input type="hidden" name="stage" value="'.$stage.'" />
                     <input type="hidden" name="new_startdate" value="'.$startdate.'" />
                     <input type="hidden" name="op" value="write_others" />
                     <input type="hidden" name="qi" value="'.$qi.'" />
                     <button type="submit" class="btn btn-success">'.ins_translate('Modifier').'</button>
                  </div>
               </form>
            </div>
         </div>';
   $arg1 = '
   var formulid = ["others_parameters"]
   inpandfieldlen("new_nuke_url",80);
   inpandfieldlen("new_sitename",80);
   inpandfieldlen("new_Titlesitename",80);
   inpandfieldlen("new_slogan",80);';
   formval('fv','',$arg1,'1');
}
?>