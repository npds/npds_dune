<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2015 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='MetaTagAdmin';
$f_titre = adm_translate("Administration des MétaTags");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

function MetaTagAdmin($saved = false) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   $tags = GetMetaTags("meta/meta.php");
   include("header.php");
   GraphicAdmin($hlpfile);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
   if ($saved){
      echo '<p align="center " class="text-success">'.adm_translate("Vos MétaTags ont été modifiés avec succès !").'</p>';
   }
   echo '
   <form id="fad_metatags" action="admin.php" method="post">
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[author]">'.adm_translate("Auteur(s)").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[author]" value="'.$tags['author'].'" maxlength="100">
            <span class="help-block">'. adm_translate("(Ex. : nom du webmaster)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[owner]">'.adm_translate("Propriétaire").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[owner]" value="'.$tags['owner'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Ex. : nom de votre compagnie/service)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[reply-to]">'.adm_translate("Adresse e-mail principale").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="email" name="newtag[reply-to]" value="'.$tags['reply-to'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Ex. : l'adresse e-mail du webmaster)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[language]">'.adm_translate("Langue principale").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[language]" value="'.$tags['language'].'" size="6" maxlength="5" />
            <span class="help-block">'.adm_translate("(Ex. : fr(Français), en(Anglais), en-us(Américain), de(Allemand), it(Italien), pt(Portugais), etc)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[description]">'.adm_translate("Description").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[description]" value="'.$tags['description'].'" maxlength="200" />
            <span class="help-block">'.adm_translate("(Brève description des centres d'intérêt du site. 200 caractères maxi.)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[keywords]">'.adm_translate("Mot(s) clé(s)").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[keywords]" value="'.$tags['keywords'].'" maxlength="1000" />
            <span class="help-block">'.adm_translate("(Définissez un ou plusieurs mot(s) clé(s). 1000 caractères maxi. Remarques : une lettre accentuée équivaut le plus souvent à 8 caractères. La majorité des moteurs de recherche font la distinction minuscule/majuscule. Séparez vos mots par une virgule)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[rating]">'.adm_translate("Audience").'</label>
         <div class="col-xs-12">
            <select class="custom-select form-control" name="newtag[rating]">
               <option value="general"'.(!strcasecmp($tags['rating'], 'general') ? ' selected="selected"' : '').'>'.adm_translate("Tout public").'</option>
               <option value="mature"'.(!strcasecmp($tags['rating'], 'mature') ? ' selected="selected"' : '').'>'.adm_translate("Adulte").'</option>
               <option value="restricted"'.(!strcasecmp($tags['rating'], 'restricted') ? ' selected="selected"' : '').'>'.adm_translate("Accés restreint").'</option>
               <option value="14 years"'.(!strcasecmp($tags['rating'], '14 years') ? ' selected="selected"' : '').'>'.adm_translate("14 ans").'</option>
            </select>
            <span class="help-block">'.adm_translate("(Définissez le public intéressé par votre site)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[distribution]">'.adm_translate("Distribution").'</label>
         <div class="col-xs-12">
            <select class="custom-select form-control" name="newtag[distribution]">
               <option value="global"'.(!strcasecmp($tags['distribution'], 'global') ? ' selected="selected"' : '').'>'.adm_translate("Large").'</option>
               <option value="local"'.(!strcasecmp($tags['distribution'], 'local') ? ' selected="selected"' : '').'>'.adm_translate("Restreinte").'</option>
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[copyright]">'.adm_translate("Copyright").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[copyright]" value="'.$tags['copyright'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Informations légales)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[robots]">'.adm_translate("Robots/Spiders").'</label>
         <div class="col-xs-12">
            <select class="custom-select form-control" name="newtag[robots]">
               <option value="all"'.(!strcasecmp($tags['robots'], 'all') ? ' selected="selected"' : '').'>'.adm_translate("Tout contenu (page/liens/etc)").'</option>
               <option value="none"'.(!strcasecmp($tags['robots'], 'none') ? ' selected="selected"' : '').'>'.adm_translate("Aucune indexation").'</option>
               <option value="index,nofollow"'.(!strcasecmp($tags['robots'], 'index,nofollow') ? ' selected="selected"' : '').'>'.adm_translate("Page courante sans liens locaux").'</option>
               <option value="noindex,follow"'.(!strcasecmp($tags['robots'], 'noindex,follow') ? ' selected="selected"' : '').'>'.adm_translate("Liens locaux sauf page courante").'</option>
               <option value="noarchive"'.(!strcasecmp($tags['robots'], 'noarchive') ? ' selected="selected"' : '').'>'.adm_translate("Pas d'affichage du cache").'</option>
               <option value="noodp,noydir"'.(!strcasecmp($tags['robots'], 'noodp,noydir') ? ' selected="selected"' : '').'>'.adm_translate("Pas d'utilisation des descriptions ODP ou YDIR").'</option>
            </select>
            <span class="help-block">'.adm_translate("(Définissez la méthode d'analyse que doivent adopter les robots des moteurs de recherche)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[revisit-after]">'.adm_translate("Fréquence de visite des Robots/Spiders").'</label>
         <div class="col-xs-12">
            <input class="form-control" type="text" name="newtag[revisit-after]" value="'.$tags['revisit-after'].'" maxlength="30" />
            <span class="help-block">'.adm_translate("(Ex. : 16 days. Remarque : ne définissez pas de fréquence inférieure à 14 jours !)").'</span>
         </div>
      </div>';

   if (function_exists("utf8_encode")) {
      echo '
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[content-type]">'.adm_translate("Encodage").'</label>
         <div class="col-xs-12">
            <select class="custom-select form-control" name="newtag[content-type]">
               <option value="text/html; charset=iso-8859-1"'.(!strcasecmp($tags['content-type'], 'text/html; charset=iso-8859-1') ? ' selected="selected"' : '').'>charset=ISO-8859-1</option>
               <option value="text/html; charset=utf-8"'.(!(strcasecmp($tags['content-type'], 'text/html; charset=utf-8') and strcasecmp($tags['content-type'], 'text/html')) ? ' selected="selected"' : '').'>charset=UTF-8</option>
            </select>
         </div>
      </div>';
   } else {
      echo '
         <label class="form-control-label" >'.adm_translate("Encodage").'</label>
         <span class="text-danger">utf8_encode() '.adm_translate("non disponible").'</span>';
   }
   echo '
      <div class="form-group row">
         <label class="form-control-label col-xs-12" for="newtag[content-type]">DOCTYPE</label>
         <div class="col-xs-12">
            <select class="custom-select form-control" name="newtag[doctype]">
               <option value="HTML 4.01 Transitional"'.(!strcasecmp(doctype, 'HTML 4.01 Transitional') ? ' selected="selected"' : '').'>HTML 4.01 '.adm_translate("Transitional").' (deprecated)</option>
               <option value="HTML 4.01 Strict"'.(!strcasecmp(doctype, 'HTML 4.01 Strict') ? ' selected="selected"' : '').'>HTML 4.01 '.adm_translate("Strict").' (deprecated)</option>
               <option value="XHTML 1.0 Transitional"'.(!strcasecmp(doctype, 'XHTML 1.0 Transitional') ? ' selected="selected"' : '').'>XHTML 1.0 '.adm_translate("Transitional").'</option>
               <option value="XHTML 1.0 Strict"'.(!strcasecmp(doctype, 'XHTML 1.0 Strict') ? ' selected="selected"' : '').'>XHTML 1.0 '.adm_translate("Strict").'</option>
               <option value="HTML 5.0"'.(!strcasecmp(doctype, 'HTML 5.0') ? ' selected="selected"' : '').'>HTML 5.0 (experimental)</option>
            </select>
         </div>
      </div>
      <input type="hidden" name="op" value="MetaTagSave" />
      <div class="form-group row">
         <div class="col-xs-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Enregistrer").'</button>
         </div>
      </div>
   </form>';
    adminfoot('fv','','','');
}

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
include ("admin/settings_save.php");

global $language;
$hlpfile = "manuels/$language/metatags.html";

   settype($meta_saved,'string');
   switch ($op) {
      case "MetaTagSave":
         $meta_saved = MetaTagSave("meta/meta.php", $newtag);
         header("location: admin.php?op=MetaTagAdmin");
         break;
       case "MetaTagAdmin":
         MetaTagAdmin($meta_saved);
         break;
    }
?>