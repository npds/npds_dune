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

function etape_4() {
   global $langue, $stage, $qi, $stopngo, $stopphp;
   $stage = 4;
   include_once('config.php');
   echo '
               <h3 class="m-b-2">'.ins_translate("Paramètres de connexion").'</h3>
               <div class="col-sm-12">
                  <form id="parameters" name="parameters" method="post" action="install.php">
                     <div class="form-group row">
                        <label class="form-control-label" for="new_dbhost">'.ins_translate("Nom d'hôte du serveur mySQL").'</label>    
                        <input class="form-control" type="text" name="new_dbhost" id="new_dbhost" maxlength="80" value="'.$dbhost.'" required="required" />
                        <span class="help-block">'.ins_translate("Exemples :").' ==> sql.domaine.com ==> localhost</span>
                        <span class="help-block text-xs-right" id="countcar_new_dbhost"></span>
                     </div>
                     <div class="form-group row">
                        <label class="form-control-label" for="new_dbuname">'.ins_translate("Nom d'utilisateur (identifiant)").'</label>
                        <input class="form-control" type="text" name="new_dbuname" id="new_dbuname" maxlength="80" value="'.$dbuname.'" required="required" />
                        <span class="help-block text-xs-right" id="countcar_new_dbuname"></span>
                     </div>
                     <div class="form-group row">
                        <label class="form-control-label" for="new_dbpass">'.ins_translate("Mot de passe").'</label>
                        <input class="form-control" type="password" name="new_dbpass" id="new_dbpass" maxlength="80" value="'.$dbpass.'" required="required" />
                        <span class="help-block text-xs-right" id="countcar_new_dbpass"></span>
                     </div>
                     <div class="form-group row">
                        <label class="form-control-label" for="new_dbname">'.ins_translate("Nom de la base de données").'</label>
                        <input class="form-control" type="text" name="new_dbname" id="new_dbname" maxlength="80" value="'.$dbname.'" required="required" />
                        <span class="help-block text-xs-right" id="countcar_new_dbname"></span>
                     </div>
                     <div class="form-group row">
                        <label class="form-control-label" for="new_NPDS_Prefix">'.ins_translate("Préfixe des tables sql").'</label>
                        <small>('.ins_translate("Pour éviter les conflits de nom de table sql...").')</small>
                        <input class="form-control" type="text" name="new_NPDS_Prefix" id="new_NPDS_Prefix" maxlength="10" value="'.$NPDS_Prefix.'" />
                        <span class="help-block text-xs-right" id="countcar_new_NPDS_Prefix"></span>
                     </div>';
   if($mysql_p == 0) {$sel1 = 'selected="selected"';$sel2 = '';}
   else {$sel1 = '';$sel2 = 'selected="selected"';}
   echo '
                     <div class="form-group row">
                        <label class="form-control-label" for="new_mysql_p">'.ins_translate("Type de connexion au serveur mySQL").'</label>
                        <select class="custom-select form-control" name="new_mysql_p">
                           <option value="0" '.$sel1.'>'.ins_translate("Non permanente").'</option>
                           <option value="1" '.$sel2.'>'.ins_translate("Permanente").'</option>
                        </select>
                     </div>';
   if($system == 0) {$sel3 = 'selected="selected"';$sel4 = '';}
   else {$sel3 = '';$sel4 = 'selected="selected"';}
   echo '
                     <div class="form-group row">
                        <label class="form-control-label" for="new_system">'.ins_translate('Système hôte').'</label>
                        <select class="custom-select form-control" name="new_system">
                           <option value="0" '.$sel3.'>Unix / Linux</option>
                           <option value="1" '.$sel4.'>Windows</option>
                        </select>
                     </div>';
   if($system_md5 == 0) {$sel5 = 'selected="selected"';$sel6 = '';}
   else {$sel5 = '';$sel6 = 'selected="selected"';}
   echo '
                     <div class="form-group row">
                        <label class="form-control-label" for="new_system_md5">'.ins_translate("Cryptage des mots de passe administrateur(s)/auteur(s)").' (MD5)</label>
                        <select class="custom-select form-control" name="new_system_md5">
                           <option value="0" '.$sel5.'>'.ins_translate('Non').'</option>
                           <option value="1" '.$sel6.'>'.ins_translate('Oui').'</option>
                        </select>
                     </div>
                     <div class="form-group row">
                        <label class="form-control-label" for="new_adminmail">'.ins_translate("Adresse e-mail de l'administrateur").'</label>
                        <input class="form-control" type="email" name="new_adminmail" id="new_adminmail" value="'.$adminmail.'" required="required" />
                        <span class="help-block text-xs-right" id="countcar_new_adminmail"></span>
                     </div>
                     <div class="form-group row">
                        <input type="hidden" name="langue" value="'.$langue.'" />
                        <input type="hidden" name="stage" value="'.$stage.'" />
                        <input type="hidden" name="op" value="write_parameters" />
                        <input type="hidden" name="qi" value="'.$qi.'" />
                        <button type="submit" class="btn btn-success">'.ins_translate(' Modifier ').'</button>
                     </div>
                  </form>
               </div>
            </div>';
   $fieldlength = '
            inpandfieldlen("new_dbhost",80);
            inpandfieldlen("new_dbuname",80);
            inpandfieldlen("new_dbpass",80);
            inpandfieldlen("new_dbname",80);
            inpandfieldlen("new_NPDS_Prefix",10);
            ';
            formval('fv','',$fieldlength,'1');
}
?>