<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2021 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
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
   echo '
   <hr />';
   if ($saved)
      echo '<div class="alert alert-success">'.adm_translate("Vos MétaTags ont été modifiés avec succès !").'</div>';
   echo '
   <form id="metatagsadm" action="admin.php" method="post">
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagauthor">'.adm_translate("Auteur(s)").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagauthor" type="text" name="newtag[author]" value="'.$tags['author'].'" maxlength="100">
            <span class="help-block">'. adm_translate("(Ex. : nom du webmaster)").'<span class="float-right ml-1" id="countcar_newtagauthor"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagowner">'.adm_translate("Propriétaire").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagowner" type="text" name="newtag[owner]" value="'.$tags['owner'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Ex. : nom de votre compagnie/service)").'<span class="float-right ml-1" id="countcar_newtagowner"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagreplyto">'.adm_translate("Adresse e-mail principale").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagreplyto" type="email" name="newtag[reply-to]" value="'.$tags['reply-to'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Ex. : l'adresse e-mail du webmaster)").'<span class="float-right ml-1" id="countcar_newtagreplyto"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtaglanguage">'.adm_translate("Langue principale").'</label>
         <div class="col-12">
            <input class="form-control" id="newtaglanguage" type="text" name="newtag[language]" value="'.$tags['language'].'" maxlength="5" />
            <span class="help-block">'.adm_translate("(Ex. : fr(Français), en(Anglais), en-us(Américain), de(Allemand), it(Italien), pt(Portugais), etc)").'<span class="float-right ml-1" id="countcar_newtaglanguage"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagdescription">'.adm_translate("Description").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagdescription" type="text" name="newtag[description]" value="'.$tags['description'].'" maxlength="200" />
            <span class="help-block">'.adm_translate("(Brève description des centres d'intérêt du site. 200 caractères maxi.)").'<span class="float-right ml-1" id="countcar_newtagdescription"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagkeywords">'.adm_translate("Mot(s) clé(s)").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagkeywords" type="text" name="newtag[keywords]" value="'.$tags['keywords'].'" maxlength="1000" />
            <span class="help-block">'.adm_translate("(Définissez un ou plusieurs mot(s) clé(s). 1000 caractères maxi. Remarques : une lettre accentuée équivaut le plus souvent à 8 caractères. La majorité des moteurs de recherche font la distinction minuscule/majuscule. Séparez vos mots par une virgule)").'<span class="float-right ml-1" id="countcar_newtagkeywords"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagrating">'.adm_translate("Audience").'</label>
         <div class="col-12">
            <select class="custom-select form-control" id="newtagrating" name="newtag[rating]">
               <option value="general"'.(!strcasecmp($tags['rating'], 'general') ? ' selected="selected"' : '').'>'.adm_translate("Tout public").'</option>
               <option value="mature"'.(!strcasecmp($tags['rating'], 'mature') ? ' selected="selected"' : '').'>'.adm_translate("Adulte").'</option>
               <option value="restricted"'.(!strcasecmp($tags['rating'], 'restricted') ? ' selected="selected"' : '').'>'.adm_translate("Accés restreint").'</option>
               <option value="14 years"'.(!strcasecmp($tags['rating'], '14 years') ? ' selected="selected"' : '').'>'.adm_translate("14 ans").'</option>
            </select>
            <span class="help-block">'.adm_translate("(Définissez le public intéressé par votre site)").'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagdistribution">'.adm_translate("Distribution").'</label>
         <div class="col-12">
            <select class="custom-select form-control" id="newtagdistribution" name="newtag[distribution]">
               <option value="global"'.(!strcasecmp($tags['distribution'], 'global') ? ' selected="selected"' : '').'>'.adm_translate("Large").'</option>
               <option value="local"'.(!strcasecmp($tags['distribution'], 'local') ? ' selected="selected"' : '').'>'.adm_translate("Restreinte").'</option>
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagcopyright">'.adm_translate("Copyright").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagcopyright" type="text" name="newtag[copyright]" value="'.$tags['copyright'].'" maxlength="100" />
            <span class="help-block">'.adm_translate("(Informations légales)").'<span class="float-right ml-1" id="countcar_newtagcopyright"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagrobots">'.adm_translate("Robots/Spiders").'</label>
         <div class="col-12">
            <select class="custom-select form-control" id="newtagrobots" name="newtag[robots]">
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
         <label class="col-form-label col-12" for="newtagrevisitafter">'.adm_translate("Fréquence de visite des Robots/Spiders").'</label>
         <div class="col-12">
            <input class="form-control" id="newtagrevisitafter" type="text" name="newtag[revisit-after]" value="'.$tags['revisit-after'].'" maxlength="30" />
            <span class="help-block">'.adm_translate("(Ex. : 16 days. Remarque : ne définissez pas de fréquence inférieure à 14 jours !)").'<span class="float-right ml-1" id="countcar_newtagrevisitafter"></span></span>
         </div>
      </div>';
// no need as this fonction is available since php4 !
//   if (function_exists("utf8_encode")) {
      echo '
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagcontenttype">'.adm_translate("Encodage").'</label>
         <div class="col-12">
            <select class="custom-select form-control" id="newtagcontenttype" name="newtag[content-type]">
               <option value="text/html; charset=iso-8859-1"'.(!strcasecmp($tags['content-type'], 'text/html; charset=iso-8859-1') ? ' selected="selected"' : '').'>charset=ISO-8859-1</option>
               <option value="text/html; charset=utf-8"'.(!(strcasecmp($tags['content-type'], 'text/html; charset=utf-8') and strcasecmp($tags['content-type'], 'text/html')) ? ' selected="selected"' : '').'>charset=UTF-8</option>
            </select>
         </div>
      </div>';
// no need as this fonction is available since php4 !
/*
   } else {
      echo '
         <label class="col-form-label" >'.adm_translate("Encodage").'</label>
         <span class="text-danger">utf8_encode() '.adm_translate("non disponible").'</span>';
   }
*/
   echo '
      <div class="form-group row">
         <label class="col-form-label col-12" for="newtagdoctype">DOCTYPE</label>
         <div class="col-12">
            <select class="custom-select form-control" id="newtagdoctype" name="newtag[doctype]">
               <option value="XHTML 1.0 Transitional"'.(!strcasecmp(doctype, 'XHTML 1.0 Transitional') ? ' selected="selected"' : '').'>XHTML 1.0 '.adm_translate("Transitional").'</option>
               <option value="XHTML 1.0 Strict"'.(!strcasecmp(doctype, 'XHTML 1.0 Strict') ? ' selected="selected"' : '').'>XHTML 1.0 '.adm_translate("Strict").'</option>
               <option value="HTML 5.0"'.(!strcasecmp(doctype, 'HTML 5.0') ? ' selected="selected"' : '').'>HTML 5.0</option>
            </select>
         </div>
      </div>
      <input type="hidden" name="op" value="MetaTagSave" />
      <div class="form-group row">
         <div class="col-12">
            <button class="btn btn-primary" type="submit">'.adm_translate("Enregistrer").'</button>
         </div>
      </div>
   </form>';
   $arg1='
   var formulid = ["metatagsadm"];
   inpandfieldlen("newtagauthor",100);
   inpandfieldlen("newtagowner",100);
   inpandfieldlen("newtagreplyto",100);
   inpandfieldlen("newtaglanguage",5);
   inpandfieldlen("newtagdescription",200);
   inpandfieldlen("newtagkeywords",1000);
   inpandfieldlen("newtagcopyright",100);
   inpandfieldlen("newtagrevisitafter",30);
   ';
   adminfoot('fv','',$arg1,'');
}

if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
include ("admin/settings_save.php");

global $language;
$hlpfile = "manuels/$language/metatags.html";

   settype($meta_saved,'string');
   switch ($op) {
   case 'MetaTagSave':
      $meta_saved = MetaTagSave("meta/meta.php", $newtag);
      header("location: admin.php?op=MetaTagAdmin");
   break;
   case 'MetaTagAdmin':
      MetaTagAdmin($meta_saved);
   break;
   }
?>