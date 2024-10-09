<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// For More security
if (!function_exists('admindroits'))
   include($_SERVER['DOCUMENT_ROOT'].'/admin/die.php');
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta')) 
   die();
// For More security
$f_meta_nom ='upConfigure';
$f_titre = adm_translate("Configuration Upload");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
$hlpfile='manuels/'.$language.'/upload.html';

global $language;
include("modules/upload/lang/upload.lang-$language.php");

function upConfigure($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg) {
   global $hlpfile, $filemanager, $f_meta_nom, $f_titre, $adminimg, $subop;
   include ("modules/upload/upload.conf.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
 echo (isset($subop) and $subop=='uploadSave') ? 
 '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>'.upload_translate("Modifications enregistrées dans").'</strong> <code class="code">/modules/upload/upload.conf.php</code>.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>' : '';
   echo '
   <hr />
   <form id="settingsupload" action="admin.php" method="post">
   <fieldset>
      <legend>'.adm_translate("Paramètres").'</legend>
      <div id="info_gene" class="adminsidefield card card-body mb-3">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xmax_size">'.adm_translate("Taille maxi des fichiers").'</label>
            <div class="col-sm-8">
               <div class="input-group mb-2">
                  <div id="humread_size" class="input-group-text">'.$max_size.'</div>
                  <input onkeyup="convertoct(\'xmax_size\',\'humread_size\')" class="form-control " id="xmax_size" type="number" name="xmax_size" value="'.$max_size.'" min="1" maxlength="8" required="required" />
               </div>
               <span class="help-block">Taille maxi des fichiers en octets<span class="float-end ms-1" id="countcar_xmax_size"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xdocumentroot">'.adm_translate("Chemin physique").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xdocumentroot" type="text" name="xdocumentroot" value="'.$DOCUMENTROOT.'" />
               <span class="help-block">Si votre variable $DOCUMENT_ROOT n\'est pas bonne (notamment en cas de redirection) vous pouvez en spécifier une ici (c\'est le chemin physique d\'accès à la racine de votre site en partant de / ou C:\) par exemple /data/web/mon_site OU c:\web\mon_site SINON LAISSER cette variable VIDE<span class="float-end ms-1" id="countcar_xdocumentroot"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xautorise_upload_p">Activer upload</label>
            <div class="col-sm-8 my-2">';
   $cky='';$ckn='';
   if ($autorise_upload_p=="true") {$cky='checked="checked"'; $ckn='';} else {$cky=''; $ckn='checked="checked"';}
   echo '
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xautorise_upload_p_y" name="xautorise_upload_p" value="true" '.$cky.' />
                  <label class="form-check-label" for="xautorise_upload_p_y">'.adm_translate("Oui").'</label>
               </div>
               <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="xautorise_upload_p_n" name="xautorise_upload_p" value="false" '.$ckn.' />
                  <label class="form-check-label" for="xautorise_upload_p_n">'.adm_translate("Non").'</label>
               </div>
               <span class="help-block">Autorise l\'upload DANS le répertoire personnel du membre (true ou false)</span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xracine">'.adm_translate("Racine du site").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xracine" type="text" name="xracine" value="'.$racine.'" />
               <span class="help-block">Sous répertoire : n\'utiliser QUE SI votre NPDS n\'est pas directement dans la racine de votre site par exemple si : www.mon_site/npds/.... ALORS /npds (avec le / DEVANT) sinon RIEN;<span class="float-end ms-1" id="countcar_xracine"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xrep_upload">'.adm_translate("Répertoire de téléchargement").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xrep_upload" type="text" name="xrep_upload" value="'.$rep_upload.'" />
               <span class="help-block">Répertoire de téléchargement (avec le / terminal)<span class="float-end ms-1" id="countcar_xrep_upload"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xrep_cache">'.adm_translate("Répertoire de cache").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xrep_cache" type="text" name="xrep_cache" value="'.$rep_cache.'" />
               <span class="help-block">Répertoire de stockage des fichiers temporaires (avec le / terminal)<span class="float-end ms-1" id="countcar_xrep_cache"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xrep_log">'.adm_translate("Répertoire des log").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xrep_log" type="text" name="xrep_log" value="'.$rep_log.'" />
               <span class="help-block">Répertoire/fichier de stockage de la log de téléchargement (par défaut /slogs/security.log)<span class="float-end ms-1" id="countcar_xrep_log"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xurl_upload">'.adm_translate("Url site").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xurl_upload" type="text" name="xurl_upload" value="'.$url_upload.'" />
               <span class="help-block">URL HTTP(S) de votre site (exemple : http(s)://www.monsite.org)<span class="float-end ms-1" id="countcar_xurl_upload"></span></span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xurl_upload_css">'.adm_translate("Url css").'</label>
            <div class="col-sm-8">
               <input class="form-control" id="xurl_upload_css" type="text" name="xurl_upload_css" value="'.$url_upload_css.'" />
            </div>
         </div>';
   include('modules/upload/include/mimetypes.php');
   $opt='';
   $tab_ext = explode(' ',$extension_autorise);
   foreach($mimetypes as $ext_name => $ext_def) {
      $sel = (in_array($ext_name, $tab_ext)) ? 'selected="selected"' : '' ;
      $opt.='
         <option '.$sel.' value="'.$ext_name.'">.'.$ext_name.'</option>';
   };
   echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xextension_autorise">Extensions</label>
            <div class="col-sm-8">
               <select multiple="multiple" class="form-select" id="xextension_autorise" name="xextension_autorise[]" size="8">'
                  .$opt.'
               </select>
               <span class="help-block">Extensions des fichiers autorisés</span>
            </div>
         </div>';
   $opt=''; $v='';
   $hrchoice = array('0'=>'afficher les images de !divers','1'=>'afficher les images de !mime','2'=>'afficher les images de la racine du répertoire','3'=>'afficher les documents');
   foreach($hrchoice as $k => $af) {
      $sel = ($ed_profil[$k]=="1") ? 'selected="selected"' : '';
      $opt.='
                  <option '.$sel.' value="'.$k.'">'.$af.'</option>';
   };
   echo '
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xed_profil">Affichage</label>
            <div class="col-sm-8">
               <select multiple="multiple" class="form-select" id="xed_profil" name="xed_profil[]">'
                  .$opt.'
               </select>
               <span class="help-block">Gére l\'affichage de la Banque Images et Documents</span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xed_nb_images">'.adm_translate("Nombre d'images").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xed_nb_images" type="text" name="xed_nb_images" min="1" maxlength="3" value="'.$ed_nb_images.'" />
               <span class="help-block">Nombre d\'image par ligne dans l\'afficheur d\'image de l\'editeur HTML</span>
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xwidth_max">'.adm_translate("Largeur maxi").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xwidth_max" type="text" name="xwidth_max" value="'.$width_max.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xheight_max">'.adm_translate("Hauteur maxi").'</label>
            <div class="col-sm-8">
               <input class="form-control " id="xheight_max" type="text" name="xheight_max" value="'.$height_max.'" />
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="xquota">'.adm_translate("Espace disque").'</label>
            <div class="col-sm-8">
               <div class="input-group mb-2">
                  <div id="humread_quota" class="input-group-text">'.$quota.'</div>
                  <input  onkeyup="convertoct(\'xquota\',\'humread_quota\')" class="form-control " id="xquota" type="text" name="xquota" min="1" maxlength="9" value="'.$quota.'" />
               </div>
               <span class="help-block">Limite de l\'espace disque alloué pour l\'upload (en octects)<span class="float-end ms-1" id="countcar_xquota"></span></span>
            </div>
         </div>
      </div>
   </fieldset>
   <input type="hidden" name="op" value="Extend-Admin-SubModule" />
   <input type="hidden" name="ModPath" value="'.$ModPath.'" />
   <input type="hidden" name="ModStart" value="'.$ModStart.'" />
   <input type="hidden" name="subop" value="uploadSave" />
   <input type="hidden" name="adm_img_mod" value="1" />
   <div class="mb-3">
      <button class="btn btn-primary" type="submit">'.adm_translate("Sauver les modifications").'</button>
   </div>
   </form>';
echo '
   <script type="text/javascript">
      //<![CDATA[
         $("#humread_quota").text(fileSize(Number($("#xquota").val())));
         $("#humread_size").text(fileSize(Number($("#xmax_size").val())));
      function fileSize(b) {
          var u = 0, s=1024;
          while (b >= s || -b >= s) {
              b /= s;
              u++;
          }
          return (u ? b.toFixed(1) + " " : b) + " KMGTPEZY"[u] + "o";
      }
      function convertoct(e,f) {
         $("#"+f).text(fileSize(Number($("#"+e).val())));
      }
      //]]
   </script>';
   $fv_parametres = '
   xmax_size: {
      validators: {
         regexp: {
            regexp:/^\d{1,8}$/,
            message: "0 ... 9"
         },
         between: {
            min: 1,
            max: 99999999,
            message: "1 ... 99999999"
         }
      }
   },
   xquota: {
      validators: {
         regexp: {
            regexp:/^\d{1,9}$/,
            message: "0 ... 9"
         },
         between: {
            min: 1,
            max: 999999999,
            message: "1 ... 999999999"
         }
      }
   },
   xheight_max: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{1,4})$/,
            message: "0 ... 9"
         },
         between: {
            min: 1,
            max: 9999,
            message: "1 ... 9999"
         }
      }
   },
   xwidth_max: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{1,4})$/,
            message: "0 ... 9"
         },
         between: {
            min: 1,
            max: 9999,
            message: "1 ... 9999"
         }
      }
   },
   xed_nb_images: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{0,2})$/,
            message: "0 ... 9"
         },
         between: {
            min: 1,
            max: 120,
            message: "1 ... 120"
         }
      }
   },
   ';
  $arg1='
   var formulid = ["settingsupload"];
   inpandfieldlen("xmax_size",8);
   inpandfieldlen("xdocumentroot",200);
   inpandfieldlen("xracine",40);
   inpandfieldlen("xrep_upload",200);
   inpandfieldlen("xrep_cache",200);
   inpandfieldlen("xrep_log",200);
   inpandfieldlen("xurl_upload",200);
   inpandfieldlen("xed_profil",100);
   inpandfieldlen("xed_nb_images",3);
   inpandfieldlen("xwidth_max",3);
   inpandfieldlen("xheight_max",3);
   inpandfieldlen("xquota",9);
';
   adminfoot('fv',$fv_parametres,$arg1,'');
}

function uploadSave($xmax_size, $xdocumentroot, $xautorise_upload_p, $xracine, $xrep_upload, $xrep_cache, $xrep_log, $xurl_upload, $xurl_upload_css, $xed_profil, $xed_nb_images, $xextension_autorise, $xwidth_max, $xheight_max, $xquota) {
   $file = file("modules/upload/upload.conf.php");
   $file[16] = "\$max_size = $xmax_size;\n";
   $file[21] = "\$DOCUMENTROOT = '$xdocumentroot';\n";
   $file[24] = "\$autorise_upload_p = '$xautorise_upload_p';\n";
   $file[28] = "\$racine = '$xracine';\n";
   $file[31] = "\$rep_upload = '".$xrep_upload."';\n";
   $file[34] = "\$rep_cache = '".$xrep_cache."';\n";
   $file[37] = "\$rep_log = '".$xrep_log."';\n";
   $file[40] = "\$url_upload = '$xurl_upload';\n";
   $file[57] = "\$url_upload_css = '$xurl_upload_css';\n";
   $profil=array('0','0','0','0');
   if($xed_profil) {
      foreach($profil as $k => $v) {
         if(in_array($k, $xed_profil)) $profil[$k]=1;
      }
   }
   $xed_profil = str_replace('|','',implode('|', $profil));
   $file[67] = "\$ed_profil = '$xed_profil';\n";
   $file[70] = "\$ed_nb_images = $xed_nb_images;\n";
   $xextension_autorise = implode(' ', $xextension_autorise);
   $file[73] = "\$extension_autorise = '$xextension_autorise';\n";
   $file[76] = "\$width_max = $xwidth_max;\n";
   $file[77] = "\$height_max = $xheight_max;\n";
   $file[80] = "\$quota = $xquota;\n";

   $fic = fopen("modules/upload/upload.conf.php", "w");
   foreach($file as $n => $ligne) {
      fwrite($fic, $ligne);
   }
   fclose($fic);
}

settype($subop,'string');
switch ($subop) {
   case "uploadSave":
      uploadSave($xmax_size, $xdocumentroot, $xautorise_upload_p, $xracine, $xrep_upload, $xrep_cache, $xrep_log, $xurl_upload, $xurl_upload_css, $xed_profil, $xed_nb_images, $xextension_autorise, $xwidth_max, $xheight_max, $xquota);
   default:
      upConfigure($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
   break;
}
?>