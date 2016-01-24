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

function etape_7() {
 include_once ('config.php');

   global $langue, $stage, $minpass, $NPDS_Prefix;
   $stage = 7;
   echo '<h3>'.ins_translate('Compte Admin').'</h3>

   <form id="admin_password" name="admin_password" method="post" action="install.php">

   <fieldset class="form-group">
      <label>'.ins_translate('Identifiant').'</label>
      <input class="form-control" type="text" name="adminlogin" size="35" maxlength="40" value="Root">
   </fieldset>

   <fieldset class="form-group">
      <label>'.ins_translate('Mot de passe').' *</label>
      <input class="form-control" type="password" name="adminpass1" size="35" maxlength="40">
      <small>* '.ins_translate('Remarque').' : '.$minpass.' '.ins_translate('caractères minimum').'</small>
      </fieldset>

   <fieldset class="form-group">
      <label>'.ins_translate("Une seconde fois").' *</label>
      <input class="form-control" type="password" name="adminpass2" size="35" maxlength="40" />
      <small>* '.ins_translate('Remarque').' : '.$minpass.' '.ins_translate('caractères minimum').'</small>
   </fieldset>

      <input type="hidden" name="langue" value="'.$langue.'" />
      <input type="hidden" name="stage" value="'.$stage.'" />
      <input type="hidden" name="op" value="write_users" />
      <button type="submit" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i>'.ins_translate(' Créer ').'</button>
</form></div>';
}
?>