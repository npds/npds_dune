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
/*                                                                      */
/* Module video_yt                                                      */
/* video_yt file 2007 by jpb                                            */
/*                                                                      */
/* version 2.2 10/07/2012 mod 28/4/15                                   */
/************************************************************************/

// For More security
   if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
   if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {die();}
// For More security 

if(!IsSet($mainfile)) { include ("mainfile.php"); }
global $language;
$start_index = 1 ;
$ModStart ='video_yt'; 

if (file_exists('modules/'.$ModPath.'/admin/pages.php')) {
   include ('modules/'.$ModPath.'/admin/pages.php');
}

include ('header.php');
include ('modules/'.$ModPath.'/video_yt_conf.php');
include ('modules/'.$ModPath.'/lang/video_yt.lang-'.$language.'.php');

//<== compatibilité old version of php
if (!function_exists('file_get_contents'))
{
  function file_get_contents($url)
  {
    $temp = "";
    $fp = @fopen ($url, "r");
    if ($fp)
    {
      while ($data=fgets($fp, 1024))
        $temp .= $data;
      fclose ($fp);
    }
    return $temp;
  }
}
//<== compatibilité old version of php

//==> proto lang en js pour les modules
/*
transforme et charge le fichier lang du module si dispo dans le fichier js pour beneficier du moteur de traduction quand javascript ecrit du contenu
# le dossier lang doit avoir des droits d'acces nécessaire chmod 777
*/
$pref_name_file = 'video_yt.lang-'; // à paramétrer pour un autre module
$file_lang = file_get_contents('modules/'.$ModPath.'/lang/'.$pref_name_file.$language.'.php');
clearstatcache();
$replacement = array('','+phrase+');
   $new_file_lang = preg_replace('#<\?php|\?>|\$#', $replacement[0], $file_lang);
   $new_file_lang = preg_replace('#\.phrase\.#', $replacement[1], $new_file_lang);
   $handle = fopen('modules/'.$ModPath.'/lang/'.$pref_name_file.$language.'.js', "w");
   fwrite($handle,$new_file_lang);
   fclose($handle);
//<== proto lang en js pour les modules


//--> Meta info sur la vidéothèque et l'utilisateur
$stream_m = file_get_contents('http://gdata.youtube.com/feeds/api/users/'.$account);
preg_match('#<media:thumbnail url=\'([^\']*)\'/>#',$stream_m,$regs);
$ar_channel_thum=$regs;//avatar de la chaine
preg_match('#<(published)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_m,$regs);
$ar_channel_date = $regs;//date création de la chaine
preg_match('#<(updated)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_m,$regs);
$ar_channel_update = $regs;//date modification de la chaine
preg_match('#<yt:statistics[^\']*\'([^\']*)\'[^\']*\'(\d+)\'[^\']*\'(\d+)\'[^\']*\'(\d+)#s',$stream_m,$regs);
$ar_channel_stat = $regs;//statistiques(2=>abonnes,3=>videoWatchCount,4=>viewCount)
preg_match_all('#<gd:feedLink(.*?)(countHint=\'(\d+)|)\'/>#s',$stream_m,$regs);
$ar_channel_info = $regs[3];//informations : favoris[0], contacts[1], inbox[2],playlist[3], souscriptions[4], nb video[5]//faux à redocumenter
$total_found = (($ar_channel_info[4])-1);//nombre total de video dans le compte
//<-- Meta info sur la vidéothèque et l'utilisateur

// controle l'increment ne doit pas etre superieur au nombre de video
if ((($ar_channel_info[4])-1) < $incrementby) $incrementby = (($ar_channel_info[4])-1);

//--> construit l'entete de la videotheque
function entvideo() {
global $ModPath, $ar_channel_thum, $ar_channel_date, $class_sty_1,$class_sty_2, $ar_channel_update, $account, $ar_channel_stat, $ar_channel_info, $start_index, $nav_block, $incrementby, $total_found, $rep_account;
$ent_affi .= '<!--<img class="yt_avat_chanel" src ="'.$ar_channel_thum[1].'" alt="avatar" />--><p class="yt_title">
<span class="'.$class_sty_1.'">'.video_yt_translate('Vid&#xE9;oth&#xE8;que de').' '.$account.'</span><!--&nbsp;&nbsp;<a href="http://gdata.youtube.com/feeds/api/users/'.$account.'/uploads?&amp;alt=rss" target="blank"><img src ="modules/'.$ModPath.'/images/standard_rss.png" alt="icone rss" border="0" /></a>--></p>
<p class="yt_title">
<span class="'.$class_sty_2.'">'.video_yt_translate('Cr&#xE9;&#xE9;e le').' </span><span class="help" title="'.$ar_channel_date[3].'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\5/\3/\1',$ar_channel_date[2]).'</span>, 
<span class="'.$class_sty_2.'">'.video_yt_translate('modifi&#xE9; le').' </span><span class="help" title="'.$ar_channel_update[3].'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\5/\3/\1',$ar_channel_update[2]).'</span>, '.$ar_channel_stat[2].' 
<span class="'.$class_sty_2.'">'.video_yt_translate('abonn&#xE9;(s)').' </span> , '.$ar_channel_stat[4].' 
<span class="'.$class_sty_2.'">'.video_yt_translate('vues').' </span>, '.(($ar_channel_info[4])-1).' <span class="'.$class_sty_2.'">'.video_yt_translate('vid&#xE9;os').'.</span>
</p>
<p id="yt_video_chan_s" class="yt_title"></p>
<hr noshade="noshade" style="clear:left;"/>
<div id="yt_bouton">
 <a href="javascript:hide_ent();"><img src="modules/'.$ModPath.'/images/fl_b.gif" alt="triangle pointe a droite" title="'.video_yt_translate('Masquer le panneau de navigation').'" border="0" /></a>
</div>
<div >
<span class="yt_menu">Navigation</span>
</div>
<div id="yt_ent" style="display: block;">
 <div id="videos2"></div>
 <div id="yt_navbloc"> <span class="yt_soustitre">'.video_yt_translate('Vid&#xE9;os').'</span> 1 <span class="yt_soustitre">'.video_yt_translate('&#xE0;').'</span> '.$incrementby.' <span class="yt_soustitre">'.video_yt_translate('total').' :</span> '.$ar_channel_info[4];

if ($total_found > $incrementby) { $ent_affi .= 
'&nbsp;&nbsp;<a class="e" href=" javascript:yt.appendScriptTag(\'http://gdata.youtube.com/feeds/api/\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\','.($incrementby+1).','.$incrementby.',\'&amp;author='.$account.'\',\'flyingscript\')">['.video_yt_translate('Suivantes').']</a><a class="e" href="javascript:yt.appendScriptTag(\'http://gdata.youtube.com/feeds/api/\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\',\'20\',\'7\',\'&amp;author='.$account.'\',\'flyingscript\')">['.video_yt_translate('Derni&#xE8;res').']</a>';}

$ent_affi .='</div></div><hr noshade="noshade" />';
echo $ent_affi;
}
//<-- construit l'entete de la videotheque

//==> Affichage page
entvideo();
echo '
<div id="yt_bouton_visua">
<a href="javascript:show_visua();"><img src="modules/'.$ModPath.'/images/fl_d.gif"  alt="triangle pointe a droite" title="'.video_yt_translate('Voir le panneau de visualisation').'" border="0" /></a>
</div> <span class="yt_menu">Visualisation</span>
<div id="yt_visua" style="display:none;" >
<table>
 <tbody>
  <tr>
   <td id="media_video">
    <div id="yt_playercontainer"></div>
   </td>
   <td id="detail_video"></td>
  </tr>
 </tbody>
</table>
</div>

<script id="param_conf" type="text/javascript">
yt.myaccount=\''.$account.'\';
yt.rep_account=\''.$rep_account.'\';
yt.mr='.$incrementby.';
yt.msr='.$search_incrementby.';
yt.h_vi='.$video_height.';
yt.w_vi='.$video_width.';
yt.lang=\''.$user_language.'\';
'.$new_file_lang.'
</script>

<div id="flyingscript_2">
<script type="text/javascript"></script>
</div>
<script type="text/javascript" id="json_compte" src="http://gdata.youtube.com/feeds/api/users/'.$account.'?&amp;alt=json-in-script&amp;callback=showMychanel"></script>
<div id="flyingscript">
<script type="text/javascript" id="jsonscript" src="http://gdata.youtube.com/feeds/api/videos?alt=json-in-script&amp;callback=showMyVideos2&amp;start-index=1&amp;max-results='.$incrementby.'&amp;author='.$account.'"></script>
</div>
';

yt_tool();

function yt_tool() {
global $language, $bg_yt_search, $ModPath;
$affi_tool .='
<div style="clear:left;">
 <hr noshade="noshade" />
 <div id="yt_bouton_tool">
  <a href="javascript:show_tool();"><img src="modules/'.$ModPath.'/images/fl_d.gif" alt="triangle pointe a droite" title="'.video_yt_translate('Voir le panneau des outils').'" border="0" /></a>
 </div>
 <span class="yt_menu">Tools</span>
 <div id="yt_tool" style="display: none;"><br />
  <div id="yt_search_res" style="display: block;"></div>
  <div id="yt_search" style="display: block; clear: both; background-color:#'.$bg_yt_search.'" ></div>
  <br />
  <form id="searchForm" onsubmit="return false">
    <input type="text" value="" onblur=" yt.searchmot_onair(this.value); " />   <input type="button" value="  Recherche  " onclick="yt.appendScriptTag(yt.feed_url,\'videos\',\'vq=\'+yt.thesearchmot+\'&amp;\',\'searchVideo\',\'searchscript\',\'1\',yt.msr,\'\',\'flyingscript\')" />
  </form>
 </div>
 <hr noshade="noshade" />
</div>';
echo $affi_tool;
}
include ('footer.php');
?>