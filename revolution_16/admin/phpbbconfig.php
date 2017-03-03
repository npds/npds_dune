<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/* Based on Parts of phpBB                                              */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='ForumConfigAdmin';
$f_titre = adm_translate('Configuration des Forums');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $adminimg, $admf_ext;
$hlpfile = "manuels/$language/forumconfig.html";

function ForumConfigAdmin() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include ("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."config");
   list($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page,$allow_upload_forum,$allow_forum_hide,$forum_attachments,$rank1,$rank2,$rank3,$rank4,$rank5,$anti_flood,$solved) = sql_fetch_row($result);
   echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Configuration des Forums").'</h3>
   <form id="fad_forumconf" action="admin.php" method="post">
      <div class="row">
         <label class="form-control-label col-sm-5" for="allow_html">'.adm_translate("Autoriser le HTML").'</label>
         <div class="col-sm-7">';
   if ($allow_html==1) {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_html" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_html" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   } else {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_html" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_html" value="0" checked="checked" /> 
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   }
   echo '
         </div>
      </div>
      <div class="row">
         <label class="form-control-label col-sm-5 " for="allow_bbcode">'.adm_translate("Autoriser les Smilies").'</label>
         <div class="col-sm-7">';
   if ($allow_bbcode==1) {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_bbcode" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_bbcode" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   } else {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_bbcode" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_bbcode" value="0" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   }
   echo '
         </div>
      </div>
      <div class="row">
         <label class="form-control-label col-sm-5" for="allow_sig">'.adm_translate("Autoriser les Signatures").'</label>
         <div class="col-sm-7">';

   if ($allow_sig==1) {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_sig" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_sig" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   } else {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_sig" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_sig" value="0" checked="checked" /> 
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   }
   echo '
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-5" for="hot_threshold">'.adm_translate("Seuil pour les Sujet 'chauds'").'</label>
         <div class="col-sm-7">
            <input class="form-control" type="number" min="0" id="hot_threshold" name="hot_threshold" value="'.$hot_threshold.'" />
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-5" for="posts_per_page">'.adm_translate("Nombre de contributions par page").'</label>
         <div class="col-sm-7">
            <input class="form-control" type="number" min="0" id="posts_per_page" name="posts_per_page" value="'.$posts_per_page.'" />
         </div>
         <div class="col-sm-7 offset-sm-5">
            <span class="help-block">'.adm_translate("(C'est le nombre de contributions affichées pour chaque page relative à un Sujet)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-5" for="topics_per_page">'.adm_translate("Sujets par forum :").'</label>
         <div class="col-sm-7">
            <input class="form-control" type="number" min="0" id="topics_per_page" name="topics_per_page" size="4" value="'.$topics_per_page.'" />
         </div>
         <div class="col-sm-7 offset-sm-5">
            <span class="help-block">'.adm_translate("(C'est le nombre de Sujets affichés pour chaque page relative à un Forum)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-5" for="anti_flood">'.adm_translate("Nombre maximum de contributions par IP et par période de 30 minutes (0=système inactif)").'</label>
         <div class="col-sm-7">
            <input class="form-control" type="number" min="0" id="anti_flood" name="anti_flood" value="'.$anti_flood.'" />
         </div>
      </div>
      <div class="row">
         <label class="form-control-label col-sm-5" for="solved">'.adm_translate("Activer le tri des contributions 'résolues'").'</label>
         <div class="col-sm-7">';
   if ($solved==1) {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="solved" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="solved" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   } else {
      echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="solved" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="solved" value="0" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   }
   echo '
         </div>
      </div>
      <div class="row">
         <label class="form-control-label col-sm-5" for="allow_upload_forum">'.adm_translate("Activer l'upload dans les forums ?").'</label>
         <div class="col-sm-7">';
   if ($allow_upload_forum) {
       echo '
            <label class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" name="allow_upload_forum" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_upload_forum" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   } else {
       echo '
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_upload_forum" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_upload_forum" value="0" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>
            </label>';
   }
   echo '
         </div>
      </div>
      <div class="row">
         <label class="form-control-label col-sm-5" for="allow_forum_hide">'.adm_translate("Activer les textes cachés").'</label>
         <div class="col-sm-7">
            <label class="custom-control custom-radio">';
   if ($allow_forum_hide==1) {
       echo '
               <input class="custom-control-input" type="radio" name="allow_forum_hide" value="1" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_forum_hide" value="0" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>';
   } else {
       echo '
               <input class="custom-control-input" type="radio" name="allow_forum_hide" value="1" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Oui").'</span>
            </label>
            <label class="custom-control custom-radio">
               <input class="custom-control-input" type="radio" name="allow_forum_hide" value="0" checked="checked" />
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">'.adm_translate("Non").'</span>';

   }
   echo '
            </label>
         </div>
      </div>
      <div class="form-group">
         <label class="form-control-label" for="rank1">'.adm_translate("Texte pour le rôle").' 1 </label>
         <textarea class="form-control" name="rank1" rows="3" maxlength="255">'.$rank1.'</textarea>
      </div>
      <div class="form-group">
         <label class="form-control-label" for="rank2">'.adm_translate("Texte pour le rôle").' 2 </label>
         <textarea class="form-control" name="rank2" rows="3" maxlength="255">'.$rank2.'</textarea>
      </div>
      <div class="form-group">
         <label class="form-control-label" for="rank3">'.adm_translate("Texte pour le rôle").' 3 </label>
         <textarea class="form-control" name="rank3" rows="3" maxlength="255">'.$rank3.'</textarea>
      </div>
      <div class="form-group">
         <label class="form-control-label" for="rank4">'.adm_translate("Texte pour le rôle").' 4 </label>
         <textarea class="form-control" name="rank4" rows="3" maxlength="255">'.$rank4.'</textarea>
      </div>
      <div class="form-group">
         <label class="form-control-label" for="rank5">'.adm_translate("Texte pour le rôle").' 5 </label>
         <textarea class="form-control" name="rank5" rows="3" maxlength="255">'.$rank5.'</textarea>
      </div>
      <input type="hidden" name="op" value="ForumConfigChange" />
      <div class="form-group">
         <button class="btn btn-primary" type="submit">'.adm_translate("Changer").'</button>
      </div>
   </form>';
   adminfoot('fv','','','');
}

function ForumConfigChange($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page,$allow_upload_forum,$allow_forum_hide,$rank1,$rank2,$rank3,$rank4,$rank5,$anti_flood,$solved) {
    global $NPDS_Prefix;

    sql_query("UPDATE ".$NPDS_Prefix."config SET allow_html='$allow_html', allow_bbcode='$allow_bbcode', allow_sig='$allow_sig', posts_per_page='$posts_per_page', hot_threshold='$hot_threshold', topics_per_page='$topics_per_page', allow_upload_forum='$allow_upload_forum', allow_forum_hide='$allow_forum_hide', rank1='$rank1', rank2='$rank2', rank3='$rank3', rank4='$rank4', rank5='$rank5', anti_flood='$anti_flood', solved='$solved'");
    Q_Clean();
    Header("Location: admin.php?op=ForumConfigAdmin");
}
?>