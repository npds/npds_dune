<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='MetaTagAdmin';
$f_titre = adm_translate("Administration des MétaTags");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

function MetaTagAdmin(bool $meta_saved = false) {
   global $hlpfile, $f_meta_nom, $f_titre, $adminimg;
   $tags = GetMetaTags("meta/meta.php");
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $sel=' selected="selected"';
   echo '
   <hr />';
   if ($meta_saved)
      echo '
      <div class="alert alert-success">
         '.adm_translate("Vos MétaTags ont été modifiés avec succès !").'
         <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
   echo '
   <form id="metatagsadm" action="admin.php" method="post">
      <div class="form-floating mb-3">
         <input class="form-control" id="newtagauthor" type="text" name="newtag[author]" value="'.$tags['author'].'" maxlength="100">
         <label for="newtagauthor">'.adm_translate("Auteur(s)").'</label>
         <span class="help-block">'.adm_translate("(Ex. : nom du webmaster)").'<span class="float-end ms-1" id="countcar_newtagauthor"></span></span>
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
               <select class="form-select" id="newtagdoctype" name="newtag[doctype]">
                  <option value="XHTML 1.0 Transitional"'.(!strcasecmp($tags['doc_type'], 'XHTML 1.0 Transitional') ? $sel : '').'>XHTML 1.0 '.adm_translate("Transitional").'</option>
                  <option value="XHTML 1.0 Strict"'.(!strcasecmp($tags['doc_type'], 'XHTML 1.0 Strict') ? $sel : '').'>XHTML 1.0 '.adm_translate("Strict").'</option>
                  <option value="HTML 5.1"'.(!strcasecmp($tags['doc_type'], 'HTML 5.1') ? $sel : '').'>HTML 5.1</option>
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

function GetMetaTags($filename) {
   if (file_exists($filename)) {
      $temp = file($filename);
      $tags = array();
      foreach($temp as $line) {
         $aline = trim(stripslashes($line));
         if (preg_match('#<!DOCTYPE\s+html\s+PUBLIC\s+"-//W3C//DTD\s+(XHTML\s+1\.0\s+(?:Strict|Transitional))//EN"#i', $aline, $regs))
            $tags['doc_type'] = $regs[1];
         if (preg_match('#<!DOCTYPE\s+html>#', $aline, $regs))
            $tags['doc_type'] = 'HTML 5.1';
         elseif (preg_match("#<meta (name|http-equiv|property)=\"([^\"]*)\" content=\"([^\"]*)\"#i",$aline,$regs)) {
            $regs[2] = strtolower($regs[2]);
            $tags[$regs[2]] = $regs[3];
         } elseif(preg_match("#<meta (charset)=\"([^\"]*)\"#i", $aline, $regs)) {
            $regs[1] = strtolower($regs[1]);
            $tags[$regs[1]] = $regs[2];
         } elseif(preg_match("#<meta (content-type)=\"([^\"]*)\" content=\"([^\"]*)\"#i", $aline, $regs)) {
            $regs[2] = strtolower($regs[2]);
            $tags[$regs[2]] = $regs[3];
         } elseif (preg_match("#<html (lang)=\"([^\"]*)\"#i", $aline, $regs)) {
            $regs[1] = strtolower($regs[1]);
            $tags[$regs[1]] = $regs[2];
         } elseif (preg_match("#<doctype (lang)=\"([^\"]*)\"#i", $aline, $regs)) {//je pense qu'elle ne sert à rien ..et qu'elle ne doit rien trouver ...
            $regs[1] = strtolower($regs[1]);
            $tags[$regs[1]] = $regs[2];
         }
      }
   }
   return $tags;
}

function MetaTagMakeSingleTag($name, $content, $type='name') {
   if ($content!="humans.txt") {
      if ($content!="")
         return "\$l_meta.=\"      <meta $type=\\\"".$name."\\\" content=\\\"".$content."\\\" />\\n\";\n";
      else
         return "\$l_meta.=\"      <meta $type=\\\"".$name."\\\" />\\n\";\n";
   } else
      return "\$l_meta.=\"      <link type=\"text/plain\" rel=\"author\" href=\"http://humanstxt.org/humans.txt\" />\";\n";
}

function MetaTagSave($filename, $tags) {
   if (!is_array($tags)) return false;
   global $adminmail, $Version_Id, $Version_Num, $Version_Sub;
   $fh = fopen($filename, "w");
   if ($fh) {
      $content = "<?php\n/* Do not change anything in this file manually. Use the administration interface. */\n";
      $content .= "/* généré le : ".date("d-m-Y H:i:s")." */\n";
      $content .= "global \$nuke_url;\n";
      $content .= "\$meta_doctype = isset(\$meta_doctype) ? \$meta_doctype : '' ;\n";
      $content .= "\$nuke_url = isset(\$nuke_url) ? \$nuke_url : '' ;\n";
      $content .= "\$meta_op = isset(\$meta_op) ? \$meta_op : '' ;\n";
      $content .= "\$m_description = isset(\$m_description) ? \$m_description : '' ;\n";
      $content .= "\$m_keywords = isset(\$m_keywords) ? \$m_keywords : '' ;\n";
      $content .= "\$lang = language_iso(1, '', 0);\n";
      $content .= "if (\$meta_doctype==\"\")\n";
      if (!empty($tags['doctype'])) {
         if ($tags['doctype'] == "XHTML 1.0 Transitional")
            $content .= "   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Transitional//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\\">\\n<html lang=\\\"\$lang\\\" xml:lang=\\\"\$lang\\\" xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n   <head>\\n\";\n";
         if ($tags['doctype'] == "XHTML 1.0 Strict")
            $content .= "   \$l_meta=\"<!DOCTYPE html PUBLIC \\\"-//W3C//DTD XHTML 1.0 Strict//EN\\\" \\\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\\\">\\n<html lang=\\\"\$lang\\\" xml:lang=\\\"\$lang\\\" xmlns=\\\"http://www.w3.org/1999/xhtml\\\">\\n   <head>\\n\";\n";
         if ($tags['doctype'] == "HTML 5.1")
            $content .= "   \$l_meta=\"<!DOCTYPE html>\\n<html lang=\\\"\$lang\\\">\\n   <head>\\n\";\n";
      } else {
         $tags['doctype'] = "HTML 5.1";
         $content .= "   \$l_meta=\"<!DOCTYPE html>\\n<html lang=\\\"\$lang\\\">\\n   <head>\\n\";\n";
      }
      $content .= "else\n";
      $content .= "   \$l_meta=\$meta_doctype.\"\\n<html lang=\\\"\$lang\\\">\\n   <head>\\n\";\n";
      if (!empty($tags['content-type'])) {
         $tags['content-type'] = htmlspecialchars(stripslashes($tags['content-type']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         if ($tags['doctype'] == "HTML 5.1") 
            $content .= MetaTagMakeSingleTag('utf-8', '', 'charset');
         else
            $content .= MetaTagMakeSingleTag('content-type', $tags['content-type'], 'http-equiv');
      } else {
         if ($tags['doctype'] == "XHTML 1.0 Transitional" || $tags['doctype'] == "XHTML 1.0 Strict") {
            $content .= MetaTagMakeSingleTag('content-type', 'text/html; charset=utf-8', 'http-equiv');
         } else {
            $content .= MetaTagMakeSingleTag('utf-8', '', 'charset');
         }
      }
      $content .= "\$l_meta.=\"      <title>\$Titlesitename</title>\\n\";\n";
      $content .= MetaTagMakeSingleTag('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');
      $content .= MetaTagMakeSingleTag('content-script-type', 'text/javascript', 'http-equiv');
      $content .= MetaTagMakeSingleTag('content-style-type', 'text/css', 'http-equiv');
      $content .= MetaTagMakeSingleTag('expires', '0', 'http-equiv');
      $content .= MetaTagMakeSingleTag('pragma', 'no-cache', 'http-equiv');
      $content .= MetaTagMakeSingleTag('cache-control', 'no-cache', 'http-equiv');
      $content .= MetaTagMakeSingleTag('identifier-url', '$nuke_url', 'http-equiv');
      if (!empty($tags['author'])) {
         $tags['author'] = htmlspecialchars(stripslashes($tags['author']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('author', $tags['author']);
      }
      if (!empty($tags['owner'])) {
         $tags['owner'] = htmlspecialchars(stripslashes($tags['owner']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('owner', $tags['owner']);
      }
      if (!empty($tags['reply-to'])) {
         $tags['reply-to'] = htmlspecialchars(stripslashes($tags['reply-to']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('reply-to', $tags['reply-to']);
      } else
         $content .= MetaTagMakeSingleTag('reply-to', $adminmail);
      if (!empty($tags['description'])) {
         $tags['description'] = htmlspecialchars(stripslashes($tags['description']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= "if (\$m_description!=\"\")\n";
         $content .= "   \$l_meta.=\"      <meta name=\\\"description\\\" content=\\\"\$m_description\\\" />\\n\";\n";
         $content .= "else\n";
         $content .= "   ".MetaTagMakeSingleTag('description', $tags['description']);
      }
      if (!empty($tags['keywords'])) {
         $tags['keywords'] = htmlspecialchars(stripslashes($tags['keywords']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= "if (\$m_keywords!=\"\")\n";
         $content .= "   \$l_meta.=\"      <meta name=\\\"keywords\\\" content=\\\"\$m_keywords\\\" />\\n\";\n";
         $content .= "else\n";
         $content .= "   ".MetaTagMakeSingleTag('keywords', $tags['keywords']);
      }
      if (!empty($tags['rating'])) {
         $tags['rating'] = htmlspecialchars(stripslashes($tags['rating']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('rating', $tags['rating']);
      }
      if (!empty($tags['distribution'])) {
         $tags['distribution'] = htmlspecialchars(stripslashes($tags['distribution']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('distribution', $tags['distribution']);
      }
      if (!empty($tags['copyright'])) {
         $tags['copyright'] = htmlspecialchars(stripslashes($tags['copyright']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('copyright', $tags['copyright']);
      }
      if (!empty($tags['revisit-after'])) {
         $tags['revisit-after'] = htmlspecialchars(stripslashes($tags['revisit-after']),ENT_COMPAT|ENT_HTML401,'UTF-8');
         $content .= MetaTagMakeSingleTag('revisit-after', $tags['revisit-after']);
      } else
         $content .= MetaTagMakeSingleTag('revisit-after', "14 days");
      $content .= MetaTagMakeSingleTag('resource-type', "document");
      $content .= MetaTagMakeSingleTag('robots', $tags['robots']);
      $content .= MetaTagMakeSingleTag('generator', "$Version_Id $Version_Num $Version_Sub");
      //==> OpenGraph Meta Tags
      $content .= MetaTagMakeSingleTag('og:type', 'website', 'property');
      $content .= MetaTagMakeSingleTag('og:url', '$nuke_url', 'property');
      $content .= MetaTagMakeSingleTag('og:title', '$Titlesitename', 'property');
      $content .= MetaTagMakeSingleTag('og:description', $tags['description'], 'property');
      $content .= MetaTagMakeSingleTag('og:image', '$nuke_url/images/ogimg_rect.png', 'property');
      $content .= MetaTagMakeSingleTag('og:image:type', 'image/png', 'property');
      $content .= MetaTagMakeSingleTag('og:image:width', '1200', 'property');
      $content .= MetaTagMakeSingleTag('og:image:height', '630', 'property');
      $content .= MetaTagMakeSingleTag('og:image:alt', 'logo site', 'property');
      $content .= MetaTagMakeSingleTag('og:image', '$nuke_url/images/ogimg_square.png', 'property');
      $content .= MetaTagMakeSingleTag('og:image:type', 'image/png', 'property');
      $content .= MetaTagMakeSingleTag('og:image:width', '630', 'property');
      $content .= MetaTagMakeSingleTag('og:image:height', '630', 'property');
      $content .= MetaTagMakeSingleTag('og:image:alt', 'logo site', 'property');
      $content .= MetaTagMakeSingleTag('twitter:card', 'summary', 'property');
      //<== OpenGraph Meta Tags
      $content .= "if (\$meta_op==\"\") echo \$l_meta; else \$l_meta=str_replace(\"\\n\",\"\",str_replace(\"\\\"\",\"'\",\$l_meta));\n?>";
      fwrite($fh, $content);
      fclose($fh);
      global $aid; Ecr_Log('security', "MetaTagsave() by AID : $aid", '');
      return true;
   }
   return false;
}

if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
global $language;
$hlpfile = "manuels/$language/metatags.html";

settype($meta_saved,'bool');
switch ($op) {
   case 'MetaTagSave':
      $meta_saved = MetaTagSave("meta/meta.php", $newtag);
      header("location: admin.php?op=MetaTagAdmin&meta_saved=$meta_saved");
   break;
   case 'MetaTagAdmin':
      MetaTagAdmin($meta_saved);
   break;
}
?>