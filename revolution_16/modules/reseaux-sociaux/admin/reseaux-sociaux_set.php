<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module core reseaux-sociaux                                          */
/* reseaux-sociaux_set file 2015 by jpb                                 */
/*                                                                      */
/* version 1.0 17/02/2016                                               */
/************************************************************************/
// For More security
if (!stristr($_SERVER['PHP_SELF'],'admin.php')) Access_Error();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();
// For More security
$f_meta_nom ='reseaux-sociaux';
$f_titre = adm_translate("Module").' : '.$ModPath;
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
$ModStart='admin/reseaux-sociaux_set';
GraphicAdmin($hlpfile);

function ListReseaux($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg) {
   if (file_exists("modules/$ModPath/reseaux-sociaux.conf.php"))
      include ("modules/$ModPath/reseaux-sociaux.conf.php");
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=AddReseaux"><i class="fa fa-plus-square"></i></a>&nbsp;'.adm_translate("Ajouter").'</h3>
   <table id ="lst_rs_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
      <thead>
         <tr>
            <th class="n-t-col-xs-3" data-sortable="true" data-halign="center" data-align="right">'.adm_translate("Nom").'</th>
            <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">'.adm_translate("URL").'</th>
            <th class="n-t-col-xs-1" data-halign="center" data-align="center">'.adm_translate("Icône").'</th>
            <th class="n-t-col-xs-2" data-halign="center" data-align="center">'.adm_translate("Fonctions").'</th>
         </tr>
      </thead>
      <tbody>';
      
   foreach ($rs as $v1) {
        echo '
         <tr>
            <td>'.$v1[0].'</td>
            <td>'.$v1[1].'</td>
            <td><i class="fab fa-'.$v1[2].' fa-2x text-muted align-middle"></i></td>
            <td>
               <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=EditReseaux&amp;rs_id='.urlencode($v1[0]).'&amp;rs_url='.urlencode($v1[1]).'&amp;rs_ico='.urlencode($v1[2]).'" ><i class="fa fa-edit fa-lg mr-2 align-middle" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>
               <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;subop=DeleteReseaux&amp;rs_id='.urlencode($v1[0]).'&amp;rs_url='.urlencode($v1[1]).'&amp;rs_ico='.urlencode($v1[2]).'" ><i class="far fa-trash-alt fa-lg text-danger align-middle" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a>
            </td>
        </tr>';
   }
   echo '
      </tbody>
   </table>';
   adminfoot('','','','');
}

function EditReseaux($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg, $rs_id, $rs_url, $rs_ico, $subop, $old_id) {
   if (file_exists("modules/$ModPath/reseaux-sociaux.conf.php"))
      include ("modules/$ModPath/reseaux-sociaux.conf.php");
   adminhead($f_meta_nom, $f_titre, $adminimg);
   if($subop=='AddReseaux')
      echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Ajouter").'</h3>'; 
   else 
      echo '
   <hr />
   <h3 class="mb-3">'.adm_translate("Editer").'</h3>';
   echo '
   <form id="reseauxadm" action="admin.php" method="post">
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="rs_id">'.adm_translate("Nom").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="rs_id" name="rs_id"  maxlength="50"  placeholder="'.adm_translate("").'" value="'.urldecode($rs_id).'" required="required" />
            <span class="help-block text-right"><span id="countcar_rs_id"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="rs_url">'.adm_translate("URL").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="url" id="rs_url" name="rs_url"  maxlength="100" placeholder="'.adm_translate("").'" value="'.urldecode($rs_url).'" required="required" />
            <span class="help-block text-right"><span id="countcar_rs_url"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-form-label col-sm-3" for="rs_ico">'.adm_translate("Icône").'</label>
         <div class="col-sm-9">
            <input class="form-control" type="text" id="rs_ico" name="rs_ico"  maxlength="40" placeholder="'.adm_translate("").'" value="'.stripcslashes(urldecode($rs_ico)).'" required="required" />
            <span class="help-block text-right"><span id="countcar_rs_ico"></span></span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-9 ml-sm-auto">
            <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver").'</button>
            <input type="hidden" name="op" value="Extend-Admin-SubModule" />
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="subop" value="SaveSetReseaux" />
            <input type="hidden" name="adm_img_mod" value="1" />
            <input type="hidden" name="old_id" value="'.urldecode($rs_id).'" />
         </div>
      </div>
   </form>';
   $arg1='
      var formulid = ["reseauxadm"];
      inpandfieldlen("rs_id",50);
      inpandfieldlen("rs_url",100);
      inpandfieldlen("rs_ico",40);';
   adminfoot('fv','',$arg1,'');
}

function SaveSetReseaux($ModPath, $ModStart, $rs_id, $rs_url, $rs_ico, $subop, $old_id) {
   if (file_exists("modules/$ModPath/reseaux-sociaux.conf.php"))
      include ("modules/$ModPath/reseaux-sociaux.conf.php");
   $newar = array($rs_id,$rs_url,$rs_ico);
   $newrs = array();
   $j=0;
   foreach ($rs as $v1) {
      if(in_array($old_id,$v1,true)) unset($rs[$j]);
      $j++;
   }
   foreach ($rs as $v1) {
      if(!in_array($rs_id,$v1,true)) $newrs[]=$v1;
   }
   if($subop!=='DeleteReseaux') $newrs[]=$newar;

   $file = fopen("modules/$ModPath/reseaux-sociaux.conf.php", "w+");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* Reseaux-sociaux Add-On ... ver. 1.0                                  */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-".date('Y')." by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* reseaux-sociaux                                                      */\n";
   $content .= "/* reseaux-sociaux_conf 2016 by jpb                                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* version 1.0 17/02/2016                                               */\n";
   $content .= "/************************************************************************/\n";
   $content .= "// Do not change if you dont know what you do ;-)\n";
   $content .= "// \$rs=[['rs name','rs url',rs class fontawesome for rs icon],[...]]\n";
   $content .= "\$rs = [\n";
   $li='';
   foreach ($newrs as $v1) {
      $li.='[\''.$v1[0].'\',\''.$v1[1].'\',\''.$v1[2].'\'],'."\n";
   }
   $li=substr_replace($li,'',-2,1);
   $content .= $li;
   $content .= "];\n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   @chmod("modules/$ModPath/reseaux-sociaux.conf.php",0666);
}

   settype($subop,'string');
   settype($rs_id,'string');
   settype($rs_url,'string');
   settype($rs_ico,'string');
   settype($old_id,'string');

   switch ($subop) {
      case "SaveSetReseaux":
         SaveSetReseaux($ModPath, $ModStart, $rs_id, $rs_url, $rs_ico, $subop, $old_id);
         ListReseaux($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
      break;
      case "DeleteReseaux":
         SaveSetReseaux($ModPath, $ModStart, $rs_id, $rs_url, $rs_ico, $subop, $old_id);
         ListReseaux($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
      break;
      case "AddReseaux":
         EditReseaux($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg, $rs_id, $rs_url, $rs_ico, $subop,$old_id);
      break;
      case "EditReseaux":
         EditReseaux($ModPath, $ModStart, $f_meta_nom, $f_titre, $adminimg, $rs_id, $rs_url, $rs_ico, $subop, $old_id);break;
         ListReseaux($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
      default:
         ListReseaux($ModPath, $ModStart,$f_meta_nom, $f_titre, $adminimg);
      break;
   }
?>