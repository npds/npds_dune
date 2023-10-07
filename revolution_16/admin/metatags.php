<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2023 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='MetaTagAdmin';
$f_titre = adm_translate("Administration des MétaTags");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

function MetaTagAdmin($saved = false) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   $tags = GetMetaTags("meta/meta.php");
///   var_dump($tags);////
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $sel=' selected="selected"';
   echo '
   <hr />';
   if ($saved) // this not work
      echo '<div class="alert alert-success">'.adm_translate("Vos MétaTags ont été modifiés avec succès !").'</div>';
   echo '
   <form id="metatagsadm" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagauthor" type="text" name="newtag[author]" value="'.$tags['author'].'" maxlength="100">
         <label for="newtagauthor">'.adm_translate("Auteur(s)").'</label>
         <span class="help-block">'. adm_translate("(Ex. : nom du webmaster)").'<span class="float-end ms-1" id="countcar_newtagauthor"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagowner" type="text" name="newtag[owner]" value="'.$tags['owner'].'" maxlength="100" />
         <label for="newtagowner">'.adm_translate("Propriétaire").'</label>
         <span class="help-block">'.adm_translate("(Ex. : nom de votre compagnie/service)").'<span class="float-end ms-1" id="countcar_newtagowner"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagreplyto" type="email" name="newtag[reply-to]" value="'.$tags['reply-to'].'" maxlength="100" />
         <label for="newtagreplyto">'.adm_translate("Adresse e-mail principale").'</label>
         <span class="help-block">'.adm_translate("(Ex. : l'adresse e-mail du webmaster)").'<span class="float-end ms-1" id="countcar_newtagreplyto"></span></span>
      </div>
      <div class="form-floating mb-3">
         <select class="form-select" id="newtaglanguage" name="newtag[language]">
            <option value="zh"'.($tags['lang']=='zh' ? $sel : '').'>'.adm_translate("chinese").'</option>
            <option value="en"'.($tags['lang']=='en' ? $sel : '').'>'.adm_translate("english").'</option>
            <option value="fr"'.($tags['lang']=='fr' ? $sel : '').'>'.adm_translate("french").'</option>
            <option value="de"'.($tags['lang']=='de' ? $sel : '').'>'.adm_translate("german").'</option>
            <option value="es"'.($tags['lang']=='es' ? $sel : '').'>'.adm_translate("spanish").'</option>
         </select>
         <label for="newtaglanguage">'.adm_translate("Langue principale").'</label>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagdescription" type="text" name="newtag[description]" value="'.$tags['description'].'" maxlength="200" />
         <label for="newtagdescription">'.adm_translate("Description").'</label>
         <span class="help-block">'.adm_translate("(Brève description des centres d'intérêt du site. 200 caractères maxi.)").'<span class="float-end ms-1" id="countcar_newtagdescription"></span></span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagkeywords" type="text" name="newtag[keywords]" value="'.$tags['keywords'].'" maxlength="1000" />
         <label for="newtagkeywords">'.adm_translate("Mot(s) clé(s)").'</label>
         <span class="help-block">'.adm_translate("(Définissez un ou plusieurs mot(s) clé(s). 1000 caractères maxi. Remarques : une lettre accentuée équivaut le plus souvent à 8 caractères. La majorité des moteurs de recherche font la distinction minuscule/majuscule. Séparez vos mots par une virgule)").'<span class="float-end ms-1" id="countcar_newtagkeywords"></span></span>
      </div>
      <div class="form-floating mb-3">
         <select class="form-select" id="newtagrating" name="newtag[rating]">
            <option value="general"'.(!strcasecmp($tags['rating'], 'general') ? $sel : '').'>'.adm_translate("Tout public").'</option>
            <option value="mature"'.(!strcasecmp($tags['rating'], 'mature') ? $sel : '').'>'.adm_translate("Adulte").'</option>
            <option value="restricted"'.(!strcasecmp($tags['rating'], 'restricted') ? $sel : '').'>'.adm_translate("Accés restreint").'</option>
            <option value="14 years"'.(!strcasecmp($tags['rating'], '14 years') ? $sel : '').'>'.adm_translate("14 ans").'</option>
         </select>
         <label for="newtagrating">'.adm_translate("Audience").'</label>
         <span class="help-block">'.adm_translate("(Définissez le public intéressé par votre site)").'</span>
      </div>
      <div class="form-floating mb-3">
         <select class="form-select" id="newtagdistribution" name="newtag[distribution]">
            <option value="global"'.(!strcasecmp($tags['distribution'], 'global') ? $sel : '').'>'.adm_translate("Large").'</option>
            <option value="local"'.(!strcasecmp($tags['distribution'], 'local') ? $sel : '').'>'.adm_translate("Restreinte").'</option>
         </select>
         <label for="newtagdistribution">'.adm_translate("Distribution").'</label>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagcopyright" type="text" name="newtag[copyright]" value="'.$tags['copyright'].'" maxlength="100" />
         <label for="newtagcopyright">'.adm_translate("Copyright").'</label>
         <span class="help-block">'.adm_translate("(Informations légales)").'<span class="float-end ms-1" id="countcar_newtagcopyright"></span></span>
      </div>
      <div class="form-floating mb-3">
         <select class="form-select" id="newtagrobots" name="newtag[robots]">
            <option value="all"'.(!strcasecmp($tags['robots'], 'all') ? $sel : '').'>'.adm_translate("Tout contenu (page/liens/etc)").'</option>
            <option value="none"'.(!strcasecmp($tags['robots'], 'none') ? $sel : '').'>'.adm_translate("Aucune indexation").'</option>
            <option value="index,nofollow"'.(!strcasecmp($tags['robots'], 'index,nofollow') ? $sel : '').'>'.adm_translate("Page courante sans liens locaux").'</option>
            <option value="noindex,follow"'.(!strcasecmp($tags['robots'], 'noindex,follow') ? $sel : '').'>'.adm_translate("Liens locaux sauf page courante").'</option>
            <option value="noarchive"'.(!strcasecmp($tags['robots'], 'noarchive') ? $sel : '').'>'.adm_translate("Pas d'affichage du cache").'</option>
            <option value="noodp,noydir"'.(!strcasecmp($tags['robots'], 'noodp,noydir') ? $sel : '').'>'.adm_translate("Pas d'utilisation des descriptions ODP ou YDIR").'</option>
         </select>
         <label for="newtagrobots">'.adm_translate("Robots/Spiders").'</label>
         <span class="help-block">'.adm_translate("(Définissez la méthode d'analyse que doivent adopter les robots des moteurs de recherche)").'</span>
      </div>
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagrevisitafter" type="text" name="newtag[revisit-after]" value="'.$tags['revisit-after'].'" maxlength="30" />
         <label for="newtagrevisitafter">'.adm_translate("Fréquence de visite des Robots/Spiders").'</label>
         <span class="help-block">'.adm_translate("(Ex. : 16 days. Remarque : ne définissez pas de fréquence inférieure à 14 jours !)").'<span class="float-end ms-1" id="countcar_newtagrevisitafter"></span></span>
      </div>
      <div class="row g-3">
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <select class="form-select" id="newtagcontenttype" name="newtag[content-type]">
                  <option value="text/html; charset=iso-8859-1"'.(!strcasecmp($tags['content-type'], 'text/html; charset=iso-8859-1') ? $sel : '').'>charset=ISO-8859-1</option>
                  <option value="text/html; charset=utf-8"'.(!(strcasecmp($tags['charset'], 'utf-8')) ? $sel : '').'>charset=utf-8</option>
               </select>
               <label for="newtagcontenttype">'.adm_translate("Encodage").'</label>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-floating mb-3">
               <select class="form-select" id="newtagdoctype" name="newtag[doctype]">
                  <option value="XHTML 1.0 Transitional"'.(!strcasecmp(doctype, 'XHTML 1.0 Transitional') ? $sel : '').'>XHTML 1.0 '.adm_translate("Transitional").'</option>
                  <option value="XHTML 1.0 Strict"'.(!strcasecmp(doctype, 'XHTML 1.0 Strict') ? $sel : '').'>XHTML 1.0 '.adm_translate("Strict").'</option>
                  <option value="HTML 5.1"'.(!strcasecmp(doctype, 'HTML 5.1') ? $sel : '').'>HTML 5.1</option>
               </select>
               <label for="newtagdoctype">DOCTYPE</label>
            </div>
         </div>
      </div>
      <input type="hidden" name="op" value="MetaTagSave" />
      <button class="btn btn-primary my-3" type="submit">'.adm_translate("Enregistrer").'</button>
   </form>';
   $arg1='
   var formulid = ["metatagsadm"];
   inpandfieldlen("newtagauthor",100);
   inpandfieldlen("newtagowner",100);
   inpandfieldlen("newtagreplyto",100);
   inpandfieldlen("newtagdescription",200);
   inpandfieldlen("newtagkeywords",1000);
   inpandfieldlen("newtagcopyright",100);
   inpandfieldlen("newtagrevisitafter",30);';
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