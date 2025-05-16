<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
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
   $content .= "/* NPDS Copyright (c) 2002-".date("Y")." by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
   $content .= "$line";
   $content .= "/* \n";
   $content .= "Notes : \n";
   $content .= "- les valeurs des éléments du tableau \$CACHE_TIMINGS sont des integer représentant des secondes\n";
   $content .= "- les valeurs des éléments du tableau \$CACHE_QUERYS sont des chaines de caractères représentant un masque (pattern) valide d'une expression régulière (preg_match)\n";
   $content .= "exemples :\n";
   $content .= "#\$CACHE_TIMINGS['index.php'] = 300; //==> soit 5 minutes\n";
   $content .= "#\$CACHE_QUERYS['index.php']  = \"^\"; //==> soit toujours vrai dans cet exemple donc /index.php suivi de n'importe quel paramètres\n\n";
   $content .= "#\$CACHE_QUERYS['leprog.php']  = \"^opc=(visite|modification|commentaire)\"; //==> soit vrai SI le premier paramètre de /leprog.php est opc=visite ou opc=modification ou opc=commentaire (quelques soient les paramètres suivants)\n";
   $content .= "#\$CACHE_QUERYS['section.php'] = \"^offset=(10|20|30)&cat=[0-9]{1,2}\"; //==> soit vrai SI le premier paramètre de /section.php est offset=10 ou offset=20 ou offset=30 suivi du paramètre cat ayant une valeur de 0 à 99\n";
   $content .= "#\$CACHE_QUERYS['news.php']    = \"^idn=[0-9]{1,2}\"; //==> soit vrai SI le premier paramètre de /news.php est idn avec une valeur de 0 à 99\n\n";
   $content .= "*/\n";
   $content .= "\$SuperCache = $xsupercache;\n\n";
   $content .= "\$CACHE_TIMINGS['index.php'] = $xt_index;\n";
   $content .= "\$CACHE_QUERYS['index.php'] = \"^\";\n\n";
   $content .= "\$CACHE_TIMINGS['article.php'] = $xt_article;\n";
   $content .= "\$CACHE_QUERYS['article.php'] = \"^\";\n\n";
   $content .= "\$CACHE_TIMINGS['sections.php'] = $xt_sections;\n";
   $content .= "\$CACHE_QUERYS['sections.php'] = \"^op\";\n\n";
   $content .= "\$CACHE_TIMINGS['faq.php'] = $xt_faq;\n";
   $content .= "\$CACHE_QUERYS['faq.php'] = \"^myfaq\";\n\n";
   $content .= "\$CACHE_TIMINGS['links.php'] = $xt_links;\n";
   $content .= "\$CACHE_QUERYS['links.php'] = \"^\";\n\n";
   $content .= "\$CACHE_TIMINGS['forum.php'] = $xt_forum;\n";
   $content .= "\$CACHE_QUERYS['forum.php'] = \"^\";\n\n";
   $content .= "\$CACHE_TIMINGS['memberslist.php'] = $xt_memberlist;\n";
   $content .= "\$CACHE_QUERYS['memberslist.php'] = \"^\";\n\n";
   $content .= "\$CACHE_TIMINGS['modules.php'] = $xt_modules;\n";
   $content .= "\$CACHE_QUERYS['modules.php'] = \"^\";\n";
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

   include("cache.timings.php");
   echo '
      <hr />
      <form id="overloadcacheadm" action="admin.php" method="post">
      <fieldset>
      <legend>'.adm_translate("Activation").'</legend>
         <div class="mb-3">
            <div>';
   $cky=''; $ckn='';
   if ($SuperCache==1) {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" id="xsupercache_y" name="xsupercache" value="true" '.$cky.' />
                  <label class="form-check-label" for="xsupercache_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" id="xsupercache_n" name="xsupercache" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xsupercache_n">'.adm_translate("Non").'</label>
               </div>
            </div>
         </div>
      </fieldset>';
      $default_timings = [
         'index.php' => 300,
         'article.php' => 60,
         'sections.php' => 300,
         'faq.php' => 86400,
         'links.php' => 28800,
         'forum.php' => 3600,
         'memberslist.php' => 1800,
         'modules.php' => 3600
      ];
      foreach ($default_timings as $page => $time) {
          $CACHE_TIMINGS[$page] = $CACHE_TIMINGS[$page] ?? $time;
      }

      echo '
      <fieldset>
      <legend>'.adm_translate("Temps de rétention en secondes").'</legend>
      <div class="mb-3 row">
         <div class=" col-sm-4 col-12">
            <label for="xt_index"><code>index.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_index" name="xt_index" value="'.$CACHE_TIMINGS['index.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 300<span class="float-end ms-1" id="countcar_xt_index"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_article"><code>article.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_article" name="xt_article" value="'.$CACHE_TIMINGS['article.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 300<span class="float-end ms-1" id="countcar_xt_article"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_sections"><code>sections.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_sections" name="xt_sections" value="'.$CACHE_TIMINGS['sections.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 300<span class="float-end ms-1" id="countcar_xt_sections"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_faq"><code>faq.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_faq" name="xt_faq" value="'.$CACHE_TIMINGS['faq.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 86400<span class="float-end ms-1" id="countcar_xt_faq"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_links"><code>links.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_links" name="xt_links" value="'.$CACHE_TIMINGS['links.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 28800<span class="float-end ms-1" id="countcar_xt_links"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_forum"><code>forum.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_forum" name="xt_forum" value="'.$CACHE_TIMINGS['forum.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 3600<span class="float-end ms-1" id="countcar_xt_forum"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_memberlist"><code>memberlist.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_memberlist" name="xt_memberlist" value="'.$CACHE_TIMINGS['memberslist.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 1800<span class="float-end ms-1" id="countcar_xt_memberlist"></span></span>
         </div>
         <div class=" col-sm-4 col-12">
            <label for="xt_modules"><code>modules.php</code></label>
            <input class="form-control js-cachetime" type="text" id="xt_modules" name="xt_modules" value="'.$CACHE_TIMINGS['modules.php'].'" required="required" maxlength="6" data-fv-row=".col-md-4" />
            <span class="help-block">Def : 3600<span class="float-end ms-1" id="countcar_xt_modules"></span></span>
         </div>
      </div>
      </fieldset>
      <div class="mb-3 row">
         <input type="hidden" name="op" value="supercache_save" />
         <div class="col-12">
            <button class="btn btn-outline-primary col-12 text-start" type="submit"><i class="fa fa-check fa-lg">&nbsp;</i>'.adm_translate("Valider").'</button>
         </div>
      </div>
      </form>
      <hr />
      <form id="fad_cacheclean" action="admin.php" method="post">
         <div class="mb-3 row">
            <div class="col-12">
               <button class="btn btn-outline-danger col-12 float-end text-end" type="submit">'.adm_translate("Vider le répertoire cache").'&nbsp;<i class="fas fa-trash fa-lg"></i></button>
            </div>
         </div>
         <input type="hidden" name="op" value="supercache_empty" />
      </form>';
   $fv_parametres='
      xt_index: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_article: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_sections: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_faq: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_links: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_forum: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_memberlist: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
      xt_modules: {
         validators: {
            regexp: {
               regexp:/^\d{1,6}$/,
               message: "0-9"
            }
         }
      },
   ';
  $arg1='
   var formulid = ["overloadcacheadm"];
   inpandfieldlen("xt_index",6);
   inpandfieldlen("xt_article",6);
   inpandfieldlen("xt_sections",6);
   inpandfieldlen("xt_faq",6);
   inpandfieldlen("xt_links",6);
   inpandfieldlen("xt_forum",6);
   inpandfieldlen("xt_memberlist",6);
   inpandfieldlen("xt_modules",6);
   ';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

switch ($op) {
   case 'supercache_save':
      save_supercache($xsupercache, $xt_index, $xt_article, $xt_sections, $xt_faq, $xt_links, $xt_forum, $xt_memberlist, $xt_modules);
      global $aid; Ecr_Log('security', "ChangeSuperCache($xsupercache, $xt_index, $xt_article, $xt_sections, $xt_faq, $xt_links, $xt_forum, $xt_memberlist, $xt_modules) by AID : $aid", '');
      Header("Location: admin.php?op=supercache");
   break;
   case 'supercache_empty':
      SC_clean();
      global $aid; Ecr_Log('security', "EmptySuperCache() by AID : $aid", '');
      Header("Location: admin.php?op=supercache");
   break;
   default:
      main();
   break;
}
?>