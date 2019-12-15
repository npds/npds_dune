<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module core archive-stories                                          */
/* archive-stories_set file 2015 by jpb                                 */
/*                                                                      */
/* version 3.0 30/08/2015                                               */
/************************************************************************/
// For More security
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) 
   Access_Error();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta')) 
   die();
// For More security
$f_meta_nom ='archive-stories';
$f_titre = adm_translate("Module").' : '.$ModPath;
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
$hlpfile='';
function ConfigureArchive($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg) {
   global $hlpfile;
   if (file_exists("modules/$ModPath/archive-stories.conf.php"))
      include ("modules/$ModPath/archive-stories.conf.php");
      GraphicAdmin($hlpfile);
      adminhead($f_meta_nom, $f_titre, $adminimg);
   echo'
   <hr />
   <h3 mb-3>'.adm_translate("Paramètres").'</h3>
   <form id="archiveadm" class="form-horizontal" action="admin.php" method="post">
      <fieldset>
         <div class="form-group row">
            <label class="col-form-label col-sm-4" for="arch_titre">'.adm_translate("Titre de la page").'</label>
            <div class="col-sm-8">
               <textarea id="arch_titre" class="form-control" type="text" name="arch_titre"  maxlength="400" rows="5" placeholder="'.adm_translate("Titre de votre page").'" >'.$arch_titre.'</textarea>
               <span class="help-block text-right"><span id="countcar_arch_titre"></span></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-form-label col-sm-4" for="arch">'.adm_translate("Affichage").'</label>
            <div class="col-sm-8">
               <select class="custom-select form-control" name="arch">';
   if ($arch == 1)
      $sel_a = 'selected="selected"';
   else
      $sel_i = 'selected="selected"';
   echo '
                  <option name="status" value="1" '.$sel_a.'>'.adm_translate("Les articles en archive").'</option>
                  <option name="status" value="0" '.$sel_i.'>'.adm_translate("Les articles en ligne").'</option>
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-form-label col-sm-4" for="maxcount">'.adm_translate("Nombre d'article par page").'</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" id="maxcount" name="maxcount" value="'.$maxcount.'" min="0" max="500" maxlength="3" required="required" />
            </div>
         </div>
         <div class="form-group row">
            <label class="col-form-label col-sm-4" for="retcache">'.adm_translate("Rétention").'</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" id="retcache" name="retcache" value="'.$retcache.'" min="0" maxlength="7" required="required" />
               <span class="help-block text-right">'.adm_translate("Temps de rétention en secondes").'</span>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-8 ml-sm-auto">
               <button class="btn btn-primary col-12" type="submit">'.adm_translate("Sauver").'</button>
               <input type="hidden" name="op" value="Extend-Admin-SubModule" />
               <input type="hidden" name="ModPath" value="'.$ModPath.'" />
               <input type="hidden" name="ModStart" value="'.$ModStart.'" />
               <input type="hidden" name="subop" value="SaveSetArchive_stories" />
               <input type="hidden" name="adm_img_mod" value="1" />
            </div>
         </div>
      </fieldset>
   </form>
   <hr />
   <a href= "modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModPath.'" ><i class="fas fa-external-link-alt fa-lg mr-1" title="Voir le module en mode utilisation." data-toggle="tooltip" data-placement="right"></i>Voir le module en mode utilisation.</a>';
   $fv_parametres='
   maxcount: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{0,2})$/,
            message: "0-9"
         },
         between: {
            min: 0,
            max: 500,
            message: "1 ... 500"
         }
      }
   },
   retcache: {
      validators: {
         regexp: {
            regexp:/^[1-9]\d{0,6}$/,
            message: "0-9"
         }
      }
   },';
   $arg1='
   var formulid=["archiveadm"];
   inpandfieldlen("arch_titre",400);';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function SaveSetArchive_stories($maxcount, $arch, $arch_titre, $retcache, $ModPath, $ModStart) {
   $file = fopen("modules/$ModPath/archive-stories.conf.php", "w");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* From ALL STORIES Add-On ... ver. 1.4.1a                              */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-".date('Y')." by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* archives-stories                                                     */\n";
   $content .= "/* archives-stories_conf 2015                                           */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* version 3.0 30/08/2015                                               */\n";
   $content .= "/************************************************************************/\n";
   $content .= "// Nombre de Stories par page \n";
   $content .= "\$maxcount = $maxcount;\n";
   $content .= "// Les news en ligne ($arch=0;) ou les archives ($arch=1;) ? \n";
   $content .= "\$arch = $arch;\n";
   $content .= "// Titre de la liste des news (par exemple : \"<h2>Les Archives</h2>\") / si \$arch_titre est vide rien ne sera affiché \n";
   $content .= "\$arch_titre = \"$arch_titre\";\n";
   $content .= "// Temps de rétention en secondes\n";
   $content .= "\$retcache = $retcache;\n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   @chmod("modules/$ModPath/archive-stories.conf.php",0666);
   
   $file = fopen("modules/$ModPath/cache.timings.php", "w");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* From ALL STORIES Add-On ... ver. 1.4.1a                              */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-".date('Y')." by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/************************************************************************/\n";
   $content .= "\n";
   $content .= "// Temps de rétention cache en secondes \n";
   $content .= "\$CACHE_TIMINGS['modules.php'] = $retcache;\n";
   $content .= "\$CACHE_QUERYS['modules.php'] = \"^ModPath=archive-stories&ModStart=archive-stories\";\n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   @chmod("modules/$ModPath/cache.timings.php",0666);
}
settype($subop,'string');
   switch ($subop) {
      case "SaveSetArchive_stories":
         SaveSetArchive_stories($maxcount, $arch, $arch_titre, $retcache, $ModPath, $ModStart);
      default:
         ConfigureArchive($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
      break;
   }
?>