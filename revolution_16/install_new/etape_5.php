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
 global $langue, $stage;
 $stage = 5;

  include_once('config.php');
 echo '<h3>'.ins_translate('Autres paramètres').'</h3>

   <form class="" id="others_parameters" name="others_parameters" method="post" action="install.php">
   <fieldset class="form-group">
      <label>'.ins_translate('Adresse (URL) de votre site').'</label>
      <input class="form-control" type="text" name="new_nuke_url" size="35" maxlength="80" value="'.$nuke_url.'" />
      <small>'.ins_translate('Exemples :').'<br />http://www.monsite.com<br />http://www.monsite.com/npds</small>
   </fieldset>

   <fieldset class="form-group">
      <label>'.ins_translate('Nom de votre site').'</label>
      <input class="form-control" type="text" name="new_sitename" size="35" maxlength="80" value="'.$sitename.'" />
   </fieldset>
   
   <fieldset class="form-group">
      <label>'.ins_translate('Intitulé de votre site').'</label>
      <input class="form-control" type="text" name="new_Titlesitename" size="60" maxlength="80" value="'.$Titlesitename.'" />
   </fieldset>

   <fieldset class="form-group">
      <label>'.ins_translate('Slogan de votre site').'</label>
      <input class="form-control" type="text" name="new_slogan" size="35" maxlength="80" value="'.$slogan.'" />
   </fieldset>
';

   echo '
   <fieldset class="form-group">
      <label>'.ins_translate('Thème graphique').'</label>
      <select class="c-select form-control" name="new_Default_Theme" />';
   include('themes/list.php');
   $themelist = explode(' ', $themelist);
   for($i = 0; $i < sizeof($themelist); $i++)
   {
    if($themelist[$i] != '')
    {
     echo '<option value="'.$themelist[$i].'">'.$themelist[$i].'</option>';
    }
   }
   echo '
      </select>
   </fieldset>
';
  
   $today = getdate();
   $tday = $today[mday];
   $tmon = $today[mon];
   $tyear = $today[year];
   if($tday < 10) { $tday = "0".$tday; }
   if($tmon < 10) { $tmon = "0".$tmon; }
   if($langue == 'english') {$startdate = $tmon.'/'.$tday.'/'.$tyear;}
   else {$startdate = $tday.'/'.$tmon.'/'.$tyear;}
   echo '

   <input type="hidden" name="langue" value="'.$langue.'" />
   <input type="hidden" name="stage" value="'.$stage.'" />
   <input type="hidden" name="new_startdate" value="'.$startdate.'" />
   <input type="hidden" name="op" value="write_others" />
   <button type="submit" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i>'.ins_translate(' Modifier ').'</button>
   </form></div>';
}
?>