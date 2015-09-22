<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='supercache';
$f_titre = adm_translate("SuperCache");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/overload.html";

function save_supercache($xsupercache, $xt_index, $xt_article, $xt_sections, $xt_faq, $xt_links, $xt_forum, $xt_memberlist, $xt_modules) {
    $line = "/************************************************************************/\n";
    $content = "<?php\n";
    $content .= "$line";
    $content .= "/* DUNE by NPDS / SUPER-CACHE engine                                    */\n";
    $content .= "/*                                                                      */\n";
    $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
    $content .= "/*                                                                      */\n";
    $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
    $content .= "/* it under the terms of the GNU General Public License as published by */\n";
    $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
    $content .= "$line";
    $content .= "#\$CACHE_TIMINGS['index.php'] = 300;  // 5 minutes\n";
    $content .= "#\$CACHE_QUERYS['index.php']  = \"^\";  // Query_String for this page : \"\" = All\n";
    $content .= "\n";
    $content .= "#\$CACHE_QUERYS['leprog.php']  = \"^opc=(visite|modification|commentaire)\"\;\n";
    $content .= "#\$CACHE_QUERYS['section.php'] = \"^offset=(10|20|30)&cat=[0-9]{1,2}\"\;\n";
    $content .= "#\$CACHE_QUERYS['news.php']    = \"^idn=[0-9]{1,2}\"\;\n";
    $content .= "\n";
    $content .= "\$SuperCache = $xsupercache;\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['index.php'] = $xt_index;\n";
    $content .= "\$CACHE_QUERYS['index.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['article.php'] = $xt_article;\n";
    $content .= "\$CACHE_QUERYS['article.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['sections.php'] = $xt_sections;\n";
    $content .= "\$CACHE_QUERYS['sections.php'] = \"^op\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['faq.php'] = $xt_faq;\n";
    $content .= "\$CACHE_QUERYS['faq.php'] = \"^myfaq\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['links.php'] = $xt_links;\n";
    $content .= "\$CACHE_QUERYS['links.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['forum.php'] = $xt_forum;\n";
    $content .= "\$CACHE_QUERYS['forum.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['memberslist.php'] = $xt_memberlist;\n";
    $content .= "\$CACHE_QUERYS['memberslist.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "\$CACHE_TIMINGS['modules.php'] = $xt_modules;\n";
    $content .= "\$CACHE_QUERYS['modules.php'] = \"^\";\n";
    $content .= "\n";
    $content .= "?>";
    $file = fopen("cache.timings.php", "w");
    fwrite($file, $content);
    fclose($file);
}

function main() {
   global $hlpfile, $radminsuper, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);

//   if ($radminsuper==1) {
      include("cache.timings.php");
      echo '
      <form id="fad_cache" action="admin.php" method="post">
      <fieldset>
      <legend>'.adm_translate("Activation").'</legend>
         <div class="form-group">
            <div>';
      if ($SuperCache==true) {
         echo '
               <label class="radio-inline">
                  <input type="radio" name="xsupercache" value="true" checked="checked" />'.adm_translate("Oui").'
               </label>
               <label class="radio-inline">
                  <input type="radio" name="xsupercache" value="false" />'.adm_translate("Non").'
               </label>';
      } else {
         echo '
               <label class="radio-inline">
                  <input type="radio" name="xsupercache" value="true" />'.adm_translate("Oui").'
               </label>
               <label class="radio-inline">
                  <input type="radio" name="xsupercache" value="false" checked="checked" />'.adm_translate("Non").'
               </label>';
      }
      echo'
            </div>
         </div>
      </fieldset>';

      if (($CACHE_TIMINGS['index.php']=="") or (!isset($CACHE_TIMINGS['index.php'])))             {$CACHE_TIMINGS['index.php']=300;}
      if (($CACHE_TIMINGS['article.php']=="") or (!isset($CACHE_TIMINGS['article.php'])))         {$CACHE_TIMINGS['article.php']=60;}
      if (($CACHE_TIMINGS['sections.php']=="") or (!isset($CACHE_TIMINGS['sections.php'])))       {$CACHE_TIMINGS['sections.php']=300;}
      if (($CACHE_TIMINGS['faq.php']=="") or (!isset($CACHE_TIMINGS['faq.php'])))                 {$CACHE_TIMINGS['faq.php']=86400;}
      if (($CACHE_TIMINGS['links.php']=="") or (!isset($CACHE_TIMINGS['links.php'])))             {$CACHE_TIMINGS['links.php']=28800;}
      if (($CACHE_TIMINGS['forum.php']=="") or (!isset($CACHE_TIMINGS['forum.php'])))             {$CACHE_TIMINGS['forum.php']=3600;}
      if (($CACHE_TIMINGS['memberslist.php']=="") or (!isset($CACHE_TIMINGS['memberslist.php']))) {$CACHE_TIMINGS['memberslist.php']=1800;}
      if (($CACHE_TIMINGS['modules.php']=="") or (!isset($CACHE_TIMINGS['modules.php'])))         {$CACHE_TIMINGS['modules.php']=3600;}

      echo '
      <fieldset>
      <legend>'.adm_translate("Temps de Rétention en secondes").'</legend>
      <div class="form-group">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_index"><code>index.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_index" name="xt_index" value="'.$CACHE_TIMINGS['index.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 300</span>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_article"><code>article.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_article" name="xt_article" value="'.$CACHE_TIMINGS['article.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 300</span>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_sections"><code>sections.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_sections" name="xt_sections" value="'.$CACHE_TIMINGS['sections.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 300</span>
            </div>
            </div>
         <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_faq"><code>faq.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_faq" name="xt_faq" value="'.$CACHE_TIMINGS['faq.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 86400</span>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_links"><code>links.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_links" name="xt_links" value="'.$CACHE_TIMINGS['links.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 28800</span>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_forum"><code>forum.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_forum" name="xt_forum" value="'.$CACHE_TIMINGS['forum.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 3600</span>
            </div>
            </div>
         <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_memberlist"><code>memberlist.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_memberlist" name="xt_memberlist" value="'.$CACHE_TIMINGS['memberslist.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 1800</span>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <label for="xt_modules"><code>modules.php</code></label>
               <input class="form-control" type="number" min="0" id="xt_modules" name="xt_modules" value="'.$CACHE_TIMINGS['modules.php'].'" required="required" data-fv-row=".col-md-4" /><span class="help-block">Def : 3600</span>
            </div>
         </div>
      </div>
      </fieldset>
      <div class="form-group">
         <div class="row">
            <div class="col-xs-12">
               <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-check-square fa-lg">&nbsp;</i>'.adm_translate("Valider").'</button>
            </div>
         </div>
      </div>
      <input type="hidden" name="op" value="supercache_save" />
      </form>
      <form id="fad_cacheclean" action="admin.php" method="post">
         <div class="form-group">
            <div class="row">
               <div class="col-xs-12">
                  <button class="btn btn-danger btn-block" type="submit"><i class="fa fa-trash-o fa-lg">&nbsp;</i>'.adm_translate("Vider le répertoire cache").'</button>
               </div>
            </div>
         </div>
         <input type="hidden" name="op" value="supercache_empty" />
      </form>';
//   }
   adminfoot('fv','','','');
}

switch ($op) {
   case "supercache_save":
      if ($radminsuper==1) {
        save_supercache($xsupercache, $xt_index, $xt_article, $xt_sections, $xt_faq, $xt_links, $xt_forum, $xt_memberlist, $xt_modules);
      }
      global $aid; Ecr_Log("security", "ChangeSuperCache($xsupercache, $xt_index, $xt_article, $xt_sections, $xt_faq, $xt_links, $xt_forum, $xt_memberlist, $xt_modules) by AID : $aid", "");
      Header("Location: admin.php?op=supercache");
      break;

   case "supercache_empty":
      SC_clean();
      global $aid; Ecr_Log("security", "EmptySuperCache() by AID : $aid", "");
      Header("Location: admin.php?op=supercache");
      break;

   default:
      main();
      break;
}
?>