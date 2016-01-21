<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* module npds_twi version beta 1.0                                     */
/* npds-twi_set.php file 2011 by Jean Pierre Barbary (jpb)              */
/* dev team :                                                           */
/************************************************************************/
if (!function_exists("Access_Error")) { die(""); }
if (!strstr($_SERVER['PHP_SELF'],'admin.php')) { die(); }

include ('modules/'.$ModPath.'/lang/twi.lang-'.$language.'.php');

function Configuretwi($subop, $ModPath, $ModStart, $class_sty_2, $npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height) {
   if (file_exists('modules/'.$ModPath.'/twi_conf.php'))
      include ('modules/'.$ModPath.'/twi_conf.php');

   $checkarti_y='';$checkarti_n='';$checkpost_y='';$checkpost_n='';$urshort_mr='';$urshort_ft='';$urshort_c='';
   if ($npds_twi_arti===1) {$checkarti_y='checked="checked"';} else {$checkarti_n='checked="checked"';};
   if ($npds_twi_post===1) {$checkpost_y='checked="checked"';} else {$checkpost_n='checked="checked"';};
   if ($npds_twi_urshort===1) {$urshort_mr='checked="checked"';};
   if ($npds_twi_urshort===2) {$urshort_ft='checked="checked"';};
   if ($npds_twi_urshort===3) {$urshort_c='checked="checked"';}
   else {$checkpost_n='checked="checked"';};

   opentable();
   echo'<form action="admin.php" method="post">
   <table width="100%" cellspacing="2" cellpadding="2" border="1">
   <thead>
    <tr>
    <td class="header" colspan="2">
     <img alt="logo module npds_twi" title="cui cui cui !" src="modules/npds_twi/npds_twi.png" style="float:left;" /><h2>'.twi_trad('Configuration du module npds_twi').'</h2>
    </td>
    </tr>
   </thead>
   <tfoot>
    <tr>
     <td colspan="2"><span class="rouge">*</span> '.twi_trad('requis') .'</td>
    </tr>
   </tfoot>
   <tbody>
    <tr>
     <td width="30%">'.twi_trad('Activation de la publication auto des articles').'</td>
     <td>&nbsp;<input type="radio" name="npds_twi_arti" value="1" '.$checkarti_y.' />&nbsp;'.twi_trad('Oui').'&nbsp;&nbsp;<input type="radio" name="npds_twi_arti" value="0" '.$checkarti_n.' />&nbsp;'.twi_trad('Non').'</td>
    </tr>
    
    <!-- En attente implementation 
    <tr>
     <td width="30%">'.twi_trad('Activation de la publication auto des posts').'</td>
     <td>&nbsp;<input type="radio" name="npds_twi_post" value="1" '.$checkpost_y.' />&nbsp;'.twi_trad('Oui').'&nbsp;&nbsp;<input type="radio" name="npds_twi_post" value="0" '.$checkpost_n.' />&nbsp;'.twi_trad('Non').'</td>
    </tr>
    -->
    <tr>
     <td width="30%">'.twi_trad('M&#xE9;thode pour le raccourciceur d\'URL').'</td>
     <td>
     &nbsp;<input type="radio" name="npds_twi_urshort" value="1" '.$urshort_mr.' />&nbsp;'.twi_trad('R&#xE9;&#xE9;criture d\'url avec mod_rewrite').'<br />
     &nbsp;<input type="radio" name="npds_twi_urshort" value="2" '.$urshort_ft.' />&nbsp;'.twi_trad('R&#xE9;&#xE9;criture d\'url avec ForceType').'<br />
     &nbsp;<input type="radio" name="npds_twi_urshort" value="3" '.$urshort_c.' />&nbsp;'.twi_trad('R&#xE9;&#xE9;criture d\'url avec contr&#xF4;leur Npds').'
     </td>
    </tr>

    <tr>
     <td width="30%">
     '.twi_trad('Votre clef de consommateur').' <span class="rouge">*</span> : '.$consumer_key.'
     </td>
     <td>
      <input type="text" " size="40" name="consumer_key" value="'.$consumer_key.'" />
     </td>
    </tr>
    <tr>
     <td width="30%">
     '.twi_trad('Votre clef secr&#xE8;te de consommateur').' <span class="rouge">*</span> : '.$consumer_secret.'
     </td>
     <td>
     <input type="text" " size="40" name="consumer_secret" value="'.$consumer_secret.'" />
     </td>
    </tr>
    <tr>
     <td width="30%">
     '.twi_trad('Jeton d\'acc&#xE8;s pour Open Authentification (oauth_token)').' <span class="rouge">*</span> : '.$oauth_token.'
     </td>
     <td>
     <input type="text" " size="40" name="oauth_token" value="'.$oauth_token.'" />
     </td>
    </tr>
    <tr>
     <td width="30%">
     '.twi_trad('Jeton d\'acc&#xE8;s secret pour Open Authentification (oauth_token_secret)').' <span class="rouge">*</span> : '.$oauth_token_secret.'
     </td>
     <td>
     <input type="text" " size="40" name="oauth_token_secret" value="'.$oauth_token_secret.'" />
     </td>
    </tr>
    <tr>
    <!--
    <tr>
    <td colspan="2"><strong>'.twi_trad('Interface bloc').'</strong></td>
    </tr>
     <td width="30%">
     '.twi_trad('Largeur de la tweet box').' <span class="rouge">*</span> : '.$tbox_width.'
     </td>
     <td>
     <input type="text" " size="25" maxlength="3" name="tbox_width" value="'.$tbox_width.'" />
     </td>
    </tr>
    <tr>
     <td width="30%">
     '.twi_trad('Hauteur de la tweet box').'</span>  <span class="rouge">*</span> : '.$tbox_height.'
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
    -->
    </tbody>
   </table>';
   
   echo 'Version : '.$npds_twi_versus.'<br /> <br />
   <input class="bouton_standard" type="submit" value="'.twi_trad('Enregistrez').'" />
   <input type="hidden" name="op" value="Extend-Admin-SubModule" />
   <input type="hidden" name="ModPath" value="'.$ModPath.'" />
   <input type="hidden" name="ModStart" value="'.$ModStart.'" />
   <input type="hidden" name="subop" value="SaveSettwi" />
   </form>';
   closetable();
}

function SaveSettwi($npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height, $class_sty_1, $class_sty_2, $ModPath, $ModStart) {
   //==> modifie le fichier de configuration
   $file_conf = fopen("modules/$ModPath/twi_conf.php", "w+");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* module npds_twi version beta 1.0                                     */\n";
   $content .= "/* twi_conf.php file 2011 by Jean Pierre Barbary (jpb)                  */\n";
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
   $content .= "\$npds_twi_versus = \"beta 1.0\";\n";
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
     $content .= "/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
     $content .= "/* it under the terms of the GNU General Public License as published by */\n";
     $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* module npds_twi version beta 1.0                                     */\n";
     $content .= "/* a.php file 2011 by Jean Pierre Barbary (jpb)                         */\n";
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
     $content .= "/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
     $content .= "/* it under the terms of the GNU General Public License as published by */\n";
     $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
     $content .= "/*                                                                      */\n";
     $content .= "/* module npds_twi version beta 1.0                                     */\n";
     $content .= "/* a file 2011 by Jean Pierre Barbary (jpb)                             */\n";
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
   echo '<strong>Votre module est param&#xE9;tr&#xE9; ou modifiez ci-dessous les param&#xE8;tres n&#xE9;cessaires</strong>';
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