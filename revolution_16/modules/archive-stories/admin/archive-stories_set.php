<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {die();}
// For More security
$f_meta_nom ='archive-stories';
$f_titre = adm_translate("Module").' : '.$ModPath;
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

function ConfigureArchive($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg) {
   if (file_exists("modules/$ModPath/archive-stories.conf.php"))
      include ("modules/$ModPath/archive-stories.conf.php");
      adminhead($f_meta_nom, $f_titre, $adminimg);
   echo'
   <hr />
   <a href= "modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModPath.'" ><i class="fa fa-external-link fa-lg" title="Voir le module en mode utilisation." data-toggle="tooltip" data-placement="right"></i></a>
   <h3>'.adm_translate("Paramètres").'</h3>
   <form id="archive_adm" class="form-horizontal" action="admin.php" method="post">
      <fieldset>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="arch_titre">'.adm_translate("Titre de la page").'</label>
            <div class="col-sm-8">
               <textarea id="arch_titre" class="form-control" type="text" name="arch_titre"  maxlength="400" rows="5" placeholder="'.adm_translate("Titre de votre page").'" >'.$arch_titre.'</textarea>
               <span class="help-block text-xs-right"><span id="countcar_arch_titre"></span></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="arch">'.adm_translate("Affichage").'</label>
            <div class="col-sm-8">
               <select class="custom-select form-control" name="arch">';
   if ($arch == 1) {
      $sel_a = 'selected="selected"';
   } else {
      $sel_i = 'selected="selected"';
   }
   echo '
                  <option name="status" value="1" '.$sel_a.'>'.adm_translate("Les articles en archive").'</option>
                  <option name="status" value="0" '.$sel_i.'>'.adm_translate("Les articles en ligne").'</option>
               </select>
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="maxcount">'.adm_translate("Nombre d'article par page").'</label>
            <div class="col-sm-8">
              <input id="maxcount" class="form-control" type="number" name="maxcount" value="'.$maxcount.'" min="0" max="500" required="required" />
            </div>
         </div>
         <div class="form-group row">
            <label class="form-control-label col-sm-4" for="retcache">'.adm_translate("Rétention").'</label>
            <div class="col-sm-8">
              <input id="retcache" class="form-control" type="number" name="retcache" value="'.$retcache.'" min="0" required="required" />
               <span class="help-block text-xs-right">'.adm_translate("Temps de rétention en secondes").'</span>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-8">
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver").'</button>
               <input type="hidden" name="op" value="Extend-Admin-SubModule" />
               <input type="hidden" name="ModPath" value="'.$ModPath.'" />
               <input type="hidden" name="ModStart" value="'.$ModStart.'" />
               <input type="hidden" name="subop" value="SaveSetArchive_stories" />
               <input type="hidden" name="adm_img_mod" value="1" />
            </div>
         </div>
      </fieldset>
   </form>';
   $arg1='inpandfieldlen("arch_titre",400);';
   adminfoot('fv','',$arg1,'');
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
   $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* archives-stories                                                     */\n";
   $content .= "/* archives-stories_conf 2015 by                                        */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* version 3.0 30/08/2015                                               */\n";
   $content .= "/************************************************************************/\n";
   $content .= "// Nombre de Stories par page \n";
   $content .= "\$maxcount = $maxcount;\n";
   $content .= "// Les news en ligne ($arch=0;) ou les archive ($arch=1;) ? \n";
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
   $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
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
   switch ($subop) {
       case "SaveSetArchive_stories":
       SaveSetArchive_stories($maxcount, $arch, $arch_titre, $retcache, $ModPath, $ModStart);
       default:
       ConfigureArchive($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
    break;
   }
?>