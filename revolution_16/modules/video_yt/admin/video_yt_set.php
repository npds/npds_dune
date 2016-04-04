<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2008 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* Module video_yt                                                      */
/* video_yt_set file 2007 by jpb                                        */
/*                                                                      */
/* version 2.2 10/07/2012                                               */
/************************************************************************/
// For More security
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='video_yt';
//$f_titre = adm_translate("Gestion des Logs");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit


//if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
   if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {die();}
// For More security 
function ConfigureVideo($ModPath, $ModStart, $class_sty_2) {
   opentable();
   echo '<table width="100%" cellspacing="2" cellpadding="2" border="0">
   <tr><td class="header">'.video_yt_translate('Configuration du module vid&#xE9;o').'</td>
   </tr><tr><td><font color=red>* </font>'.video_yt_translate('requis') .'</td></tr>
   </table>';
   opentable();
   echo'<form action="admin.php" method="post">';   
   if (file_exists("modules/$ModPath/video_yt_conf.php"))
      include ("modules/$ModPath/video_yt_conf.php");
   
   echo '<table width="100%" cellspacing="2" cellpadding="2" border="1"><tr>
   <td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Votre ID developpeur youtube').'</span> <font color=red>*</font> </td><td><input type="text" size="33" maxlength="255" name="dev_id" value="'.$dev_id.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Votre clef developpeur youtube').'</span> </td><td><input type="text" size="33" maxlength="255" name="dev_key" value="'.$dev_key.'"> </td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Votre username id').'</span> <font color=red>*</font></td><td><input type="text" size="33" maxlength="255" name="account" value="'.$use_id.'"> </td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Votre username youtube').'</span> <font color=red>*</font></td><td><input type="text" size="33" maxlength="255" name="account" value="'.$account.'"> </td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Username alternatif').'</span> <font color=red>*</font></td><td><input type="text" size="33" maxlength="255" name="rep_account" value="'.$rep_account.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Largeur de la vid&#xE9;o').'</span> </td><td><input type="text" size="33" maxlength="255" name="video_width" value="'.$video_width.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Hauteur de la vid&#xE9;o').'</span> </td><td><input type="text" size="33" maxlength="255" name="video_height" value="'.$video_height.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Largeur de la vid&#xE9;o dans le bloc').'</span> </td><td><input type="text" size="33" maxlength="255" name="bloc_width" value="'.$bloc_width.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Hauteur de la vid&#xE9;o dans le bloc').'</span> </td><td><input type="text" size="33" maxlength="255" name="bloc_height" value="'.$bloc_height.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Nombre de vid&#xE9;o par page').'</span> <font color=red>*</font> </td><td><input type="text" size="33" maxlength="2" name="incrementby" value="'.$incrementby.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Nombre de vid&#xE9;o dans recherche').'</span> <font color=red>*</font> </td><td><input type="text" size="33" maxlength="2" name="search_incrementby" value="'.$search_incrementby.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Couleur de fond zone recherche').'</span> <font color=red>*</font> </td><td><input type="text" size="33" maxlength="6" name="bg_yt_search" value="'.$bg_yt_search.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Classe de style titre').'</span> </td><td><input type="text" size="33" maxlength="255" name="class_sty_1" value="'.$class_sty_1.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Classe de style sous-titre').'</span> </td><td><input type="text" size="33" maxlength="255" name="class_sty_2" value="'.$class_sty_2.'"></td></tr><tr><td width="25%">
   <span class="'.$class_sty_2.'">'.video_yt_translate('Classe de style commentaire').'</span> </td><td><input type="text" size="33" maxlength="255" name="class_sty_3" value="'.$class_sty_3.'"></td></tr></table>
   <br /><input class="bouton_standard" type="submit" value="'.video_yt_translate('Sauver').'" />
   <input type="hidden" name="op" value="Extend-Admin-SubModule" />
   <input type="hidden" name="ModPath" value="'.$ModPath.'" />
   <input type="hidden" name="ModStart" value="'.$ModStart.'" />
   <input type="hidden" name="subop" value="SaveSetVideo_yt" />
   </form>';
   closetable();
   closetable();
}

function SaveSetVideo_yt($dev_id, $dev_key, $use_id, $account, $rep_account, $incrementby, $video_width, $video_height, $bloc_width, $bloc_height, $search_incrementby, $bg_yt_search, $class_sty_1, $class_sty_2, $class_sty_3, $ModPath, $ModStart) {
   $file = fopen("modules/$ModPath/video_yt_conf.php", "w");
   $content = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-2008 by Philippe Brunier                     */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* Module video_yt                                                      */\n";
   $content .= "/* video_yt_set 2007 by jpb                                             */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* version 2.2 10/07/2012                                               */\n";
   $content .= "/************************************************************************/\n";
   $content .= "// param&#xE8;tres Youtube \n";
   $content .= "\$dev_id = \"$dev_id\"; // obtenez votre DEV_ID at http://www.youtube.com/signup?next=my_profile_dev \n";
   $content .= "\$dev_key = \"$dev_key\"; // clef necessaire uniquement pour les futures opérations d'ecriture \n";
   $content .= "\$account = \"$use_id\";// YouTube User id \n";
   $content .= "\$account = \"$account\";// YouTube Username \n";
   $content .= "// mise en forme \n";
   $content .= "\$rep_account = \"$rep_account\";// youtube name \n";
   $content .= "\$incrementby = $incrementby;// nombre de vid&#xE9;os par page dans vid&#xE9;oth&#xE8;que \n";
   $content .= "\$search_incrementby = \"$search_incrementby\";// nombre de vid&#xE9;os dans la recherche \n";
   $content .= "\$bg_yt_search = \"$bg_yt_search\";// couleur du background de la zone d'affichage des recherches\n";
   $content .= "\$video_width = \"$video_width\"; //largeur de l'objet \n"; 
   $content .= "\$video_height = \"$video_height\"; //hauteur de l'objet \n";
   $content .= "\$bloc_width = \"$bloc_width\"; //largeur de l'objet dans le bloc\n"; 
   $content .= "\$bloc_height = \"$bloc_height\"; //hauteur de l'objet dans le bloc \n";
   $content .= "\$class_sty_1 = \"$class_sty_1\"; //titre de la page \n";
   $content .= "\$class_sty_2 = \"$class_sty_2\"; //sous-titre de la page \n";
   $content .= "\$class_sty_3 = \"$class_sty_3\"; //commentaire \n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   chmod("modules/$ModPath/video_yt_conf.php",0666);
echo $content;
   function AfterSaveSetVideo_yt($dev_id, $dev_key, $use_id, $account, $rep_account, $incrementby, $video_width, $video_height,$bloc_width, $bloc_height, $search_incrementby, $bg_yt_search, $class_sty_1, $class_sty_2, $class_sty_3, $ModPath, $ModStart) {
   echo '<strong>Votre module est param&#xE9;tr&#xE9; allez &#xE0; la <a href = "modules.php?ModPath='.$ModPath.'&amp;ModStart=video_yt">vid&#xE9;oth&#xE8;que</a> ou modifiez; ci dessous les param&#xE8;tres n&#xE9;cessaires</strong>';
}
}


if ($admin) {
   include ('modules/'.$ModPath.'/lang/video_yt.lang-'.$language.'.php');
   switch ($subop) {
       case "SaveSetVideo_yt":
       SaveSetVideo_yt($dev_id, $dev_key, $use_id, $account, $rep_account, $incrementby, $video_width, $video_height, $bloc_width, $bloc_height, $search_incrementby, $bg_yt_search, $class_sty_1, $class_sty_2, $class_sty_3, $ModPath, $ModStart);
       case "AfterSaveSetVideo_yt":
       AfterSaveSetVideo_yt($dev_id, $dev_key, $use_id, $account, $rep_account, $incrementby, $video_width, $video_height, $bloc_width, $bloc_height, $search_incrementby, $bg_yt_search, $class_sty_1, $class_sty_2, $class_sty_3, $ModPath, $ModStart);
       default:
       ConfigureVideo($ModPath, $ModStart, $class_sty_2);
    break;
   }
}
?>