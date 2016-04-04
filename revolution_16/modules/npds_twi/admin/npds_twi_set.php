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
/* module npds_twi version v.1.0                                        */
/* npds-twi_set.php file 2015 by Jean Pierre Barbary (jpb)              */
/* dev team :                                                           */
/************************************************************************/
if (!function_exists("Access_Error")) { die(); }
if (!strstr($_SERVER['PHP_SELF'],'admin.php')) { die(); }

include ('modules/'.$ModPath.'/lang/twi.lang-'.$language.'.php');
$f_meta_nom ='npds_twi';
$f_titre='npds_twi';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

function Configuretwi($subop, $ModPath, $ModStart, $class_sty_2, $npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height) {
   if (file_exists('modules/'.$ModPath.'/twi_conf.php'))
      include ('modules/'.$ModPath.'/twi_conf.php');
   global $f_meta_nom, $f_titre, $adminimg;
   $checkarti_y='';$checkarti_n='';$checkpost_y='';$checkpost_n='';$urshort_mr='';$urshort_ft='';$urshort_c='';
   if ($npds_twi_arti===1) {$checkarti_y='checked="checked"';} else {$checkarti_n='checked="checked"';};
   if ($npds_twi_post===1) {$checkpost_y='checked="checked"';} else {$checkpost_n='checked="checked"';};
   if ($npds_twi_urshort===1) {$urshort_mr='checked="checked"';};
   if ($npds_twi_urshort===2) {$urshort_ft='checked="checked"';};
   if ($npds_twi_urshort===3) {$urshort_c='checked="checked"';}
   else {$checkpost_n='checked="checked"';};
   
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   if ($npds_twi!=1) echo'<div class="alert alert-danger">Pour la publication de vos news sur twitter vous devez activer</div>';
   echo'     <span class="text-danger">*</span> '.twi_trad('requis').'

   <h3>'.twi_trad('Configuration du module npds_twi').'</h3>
   <form action="admin.php" method="post">


      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="npds_twi_arti">'.twi_trad('Activation de la publication auto des articles').'</label>
         <div class="col-sm-6">
            <label><input type="radio" name="npds_twi_arti" value="1" '.$checkarti_y.' />&nbsp;'.twi_trad('Oui').'</label>
            <label><input type="radio" name="npds_twi_arti" value="0" '.$checkarti_n.' />&nbsp;'.twi_trad('Non').'</label>
         </div>
      </div>
    
    
    <!-- En attente implementation 
    <tr>
     <td width="30%">'.twi_trad('Activation de la publication auto des posts').'</td>
     <td>&nbsp;<input type="radio" name="npds_twi_post" value="1" '.$checkpost_y.' />&nbsp;'.twi_trad('Oui').'&nbsp;&nbsp;<input type="radio" name="npds_twi_post" value="0" '.$checkpost_n.' />&nbsp;'.twi_trad('Non').'</td>
    </tr>
    -->
      <div class="form-group row">
         <label class="form-control-label col-sm-6" for="npds_twi_urshort">'.twi_trad("Méthode pour le raccourciceur d'URL").'</label>
         <div class="col-sm-6">
            <label><input type="radio" name="npds_twi_urshort" value="1" '.$urshort_mr.' />&nbsp;'.twi_trad("Réécriture d'url avec mod_rewrite").'</label>
            <label><input type="radio" name="npds_twi_urshort" value="2" '.$urshort_ft.' />&nbsp;'.twi_trad("Réécriture d'url avec ForceType").'</label>
            <label><input type="radio" name="npds_twi_urshort" value="3" '.$urshort_c.' />&nbsp;'.twi_trad("Réécriture d'url avec controleur Npds").'</label>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="consumer_key">'.twi_trad('Votre clef de consommateur').'&nbsp;<span class="text-danger">*</span></label>
         <div class="col-sm-12">
            <input type="text" class="form-control" name="consumer_key" value="'.$consumer_key.'" required="required" />
            <span class="help-block small">'.$consumer_key.'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="consumer_secret">'.twi_trad('Votre clef secrète de consommateur').'&nbsp;<span class="text-danger">*</span></label>
         <div class="col-sm-12">
            <input type="text" class="form-control" name="consumer_secret" value="'.$consumer_secret.'" required="required" />
            <span class="help-block small">'.$consumer_secret.'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="oauth_token" >'.twi_trad("Jeton d'accès pour Open Authentification (oauth_token)").'&nbsp;<span class="text-danger">*</span></label>
         <div class="col-sm-12">
            <input type="text" class="form-control" name="oauth_token" value="'.$oauth_token.'" required="required" />
            <span class="help-block small">'.$oauth_token.'</span>
         </div>
      </div>
      <div class="form-group row">
         <label class="form-control-label col-sm-12" for="oauth_token_secret" >'.twi_trad("Jeton d'accès secret pour Open Authentification (oauth_token_secret)").' <span class="text-danger">*</span></label>
         <div class="col-sm-12">
            <input type="text" class="form-control" name="oauth_token_secret" value="'.$oauth_token_secret.'" />
            <span class="help-block small">'.$oauth_token_secret.'</span>
         </div>
      </div>
    <!--
    <tr>
    <td colspan="2"><strong>'.twi_trad('Interface bloc').'</strong></td>
    </tr>
     <td width="30%">
     '.twi_trad('Largeur de la tweet box').' <span class="text-danger">*</span> : '.$tbox_width.'
     </td>
     <td>
     <input type="text" " size="25" maxlength="3" name="tbox_width" value="'.$tbox_width.'" />
     </td>
    </tr>
    <tr>
     <td width="30%">
     '.twi_trad('Hauteur de la tweet box').'</span>  <span class="text-danger">*</span> : '.$tbox_height.'
     </td>
     <td>
     <input type="text" " size="25" maxlength="3" name="tbox_height" value="'.$tbox_height.'" />
     </td>
    </tr>
    <tr>
    <td colspan="2"><strong>Styles</strong></td>
    </tr>
    <tr>
     <td width="30%">
     <span class="'.$class_sty_2.'">'.twi_trad('Classe de style titre').'</span> </td><td><input type="text" size="25" maxlength="255" name="class_sty_1" value="'.$class_sty_1.'">
     </td>
    </tr>
    <tr>
     <td width="30%">
     <span class="'.$class_sty_2.'">'.twi_trad("Classe de style sous-titre").'</span>
     </td>
     <td>
     <input type="text" size="25" maxlength="255" name="class_sty_2" value="'.$class_sty_2.'" />
     </td>
    </tr>
    -->';
   
   echo '
      <div class="form-group row">
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="'.twi_trad('Enregistrez').'" />
            <input type="hidden" name="op" value="Extend-Admin-SubModule" />
            <input type="hidden" name="ModPath" value="'.$ModPath.'" />
            <input type="hidden" name="ModStart" value="'.$ModStart.'" />
            <input type="hidden" name="subop" value="SaveSettwi" />
         </div>
      </div>
   </form>
   <div class="text-xs-right">Version : '.$npds_twi_versus.'</div>';
   adminfoot('fv','','','');
}

function SaveSettwi($npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height, $class_sty_1, $class_sty_2, $ModPath, $ModStart) {
   //==> modifie le fichier de configuration
   $file_conf = fopen("modules/$ModPath/twi_conf.php", "w+");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* module npds_twi version v.1.0                                        */\n";
   $content .= "/* twi_conf.php file 2015 by Jean Pierre Barbary (jpb)                  */\n";
   $content .= "/* dev team :                                                           */\n";
   $content .= "/************************************************************************/\n";
   if (!$npds_twi_arti) {$npds_twi_arti=0;}
   $content .= "\$npds_twi_arti = $npds_twi_arti; // activation publication auto des news sur twitter \n";
   if (!$npds_twi_post) {$npds_twi_post=0;}
   $content .= "\$npds_twi_post = $npds_twi_post; // activation publication auto des posts sur twitter \n";
   if (!$npds_twi_urshort) {$npds_twi_urshort=0;}
   $content .= "\$npds_twi_urshort = $npds_twi_urshort; // activation du raccourciceur d'url \n";
   $content .= "\$consumer_key = \"$consumer_key\"; //  \n";
   $content .= "\$consumer_secret = \"$consumer_secret\"; //  \n";
   $content .= "\$oauth_token = \"$oauth_token\"; //  \n";
   $content .= "\$oauth_token_secret = \"$oauth_token_secret\"; //  \n";
   $content .= "// interface bloc \n";
   $content .= "\$tbox_width = \"$tbox_width\"; // largeur de la tweet box \n";
   $content .= "\$tbox_height = \"$tbox_height\"; // hauteur de la tweet box \n";
   $content .= "// style \n";
   $content .= "\$class_sty_1 = \"$class_sty_1\"; //titre de la page \n";
   $content .= "\$class_sty_2 = \"$class_sty_2\"; //sous-titre de la page \n";
   $content .= "\$npds_twi_versus = \"v.1.0\";\n";
   $content .= "?>";
   fwrite($file_conf, $content);
   fclose($file_conf);
   //<== modifie le fichier de configuration
   
  //==> modifie le fichier controleur
  $file_controleur='';
  //     if (file_exists('modules/'.$ModPath.'/twi_conf.php'))
  //   include ('modules/'.$ModPath.'/twi_conf.php');
  if ($npds_twi_urshort<>1) {
     $file_controleur = fopen("s.php", "w+");
     $content = "<?php \n";
     $content .= "/************************************************************************/\n";
     $content .= "/* DUNE by NPDS                                                         */\n";
     $content .= "/* ===========================                                          */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
     $content .= "/* it under the terms of the GNU General Public License as published by */\n";
     $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* module npds_twi version v.1.0                                        */\n";
     $content .= "/* a.php file 2015 by Jean Pierre Barbary (jpb)                         */\n";
     $content .= "/* dev team :                                                           */\n";
     $content .= "/************************************************************************/\n";
     $content .= "\n";
     $content .= "\$fol=preg_replace ('#s\.php/(\d+)\$#','',\$_SERVER['PHP_SELF']);\n";
     $content .= "preg_match ('#/s\.php/(\d+)\$#', \$_SERVER['PHP_SELF'],\$res);\n";
     $content .= "header('Location: http://'.\$_SERVER['HTTP_HOST'].\$fol.'article.php?sid='.\$res[1]);\n";
     $content .= "?>";
     fwrite($file_controleur, $content);
     fclose($file_controleur);

     $file_controleur = fopen("s", "w+");
     $content = "<?php \n";
     $content .= "/************************************************************************/\n";
     $content .= "/* DUNE by NPDS                                                         */\n";
     $content .= "/* ===========================                                          */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
     $content .= "/* it under the terms of the GNU General Public License as published by */\n";
     $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* module npds_twi version v.1.0                                        */\n";
     $content .= "/* a file 2015 by Jean Pierre Barbary (jpb)                             */\n";
     $content .= "/* dev team :                                                           */\n";
     $content .= "/************************************************************************/\n";
     $content .= "\n";
     $content .= "\$fol=preg_replace ('#s/(\d+)\$#','',\$_SERVER['PHP_SELF']);\n";
     $content .= "preg_match ('#/s/(\d+)\$#', \$_SERVER['PHP_SELF'],\$res);\n";
     $content .= "header('Location: http://'.\$_SERVER['HTTP_HOST'].\$fol.'article.php?sid='.\$res[1]);\n";
     $content .= "?>";
     fwrite($file_controleur, $content);
     fclose($file_controleur);
  }
  //<== modifie le fichier controleur
}

function AfterSaveSettwi($ModPath, $ModStart) {
   echo '<strong>Votre module est paramétré ou modifiez ci-dessous les paramètres nécessaires</strong>';
}

if ($admin) {
   switch ($subop) {
      case "SaveSettwi":
      SaveSettwi($npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height, $class_sty_1, $class_sty_2, $ModPath, $ModStart);
      case "AfterSaveSettwi":
      AfterSaveSettwi($ModPath, $ModStart);
      default:
      Configuretwi($subop, $ModPath, $ModStart, $class_sty_2, $npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height);
      break;
   }
}
?>