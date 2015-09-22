<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* Original Copyright (c) 2001 by Francisco Burzi (fburzi@ncc.org.ve)   */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2007 by Philippe Brunier   */
/*                                                                      */
/*                                                                      */
/* video_yt file 2007 by Jean Pierre Barbary                            */
/*version 2.0 21/02/08                                                  */
/************************************************************************/

if(!IsSet($mainfile)) { include ("mainfile.php"); }

// $vilain = array("..","script","cookie","iframe","applet","object","meta");
//    foreach ($vilain as $key => $value)
//    {if (strstr($ModPath,$value) || strstr($ModStart,$value)) {die()} }
// //    
//    if (!function_exists('Mysql_Connexion')) {include ('mainfile.php');} 
//    

global $pdsts , $language, $ar_meta_inf, $ar_meta_thum, $nb_thum_vid;
$pdst=1;
include ('header.php');
include ('modules/'.$ModPath.'/video_yt_conf.php');
include ('modules/'.$ModPath.'/lang/video_yt.lang-'.$language.'.php');

$author = $rep_account;

//--> Meta info sur la vidéothèque et l'utilisateur
$stream_m = file_get_contents("http://gdata.youtube.com/feeds/api/users/$account");
preg_match('#<media:thumbnail url=\'([^\']*)\'/>#',$stream_m,$regs);
$ar_channel_thum=$regs;//avatar
preg_match('#<(published)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_m,$regs);
$ar_channel_date = $regs;//date création
preg_match('#<(updated)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_m,$regs);
$ar_channel_update = $regs;//date modification
preg_match('#<yt:statistics[^\']*\'(\d+)\'[^\']*\'(\d+)\'[^\']*\'(\d+)\'[^\']*\'([^\']*)#',$stream_m,$regs);
$ar_channel_stat = $regs;//statistiques
preg_match_all('#<gd:feedLink(.*?)(countHint=\'(\d+)|)\'/>#s',$stream_m,$regs);
$ar_channel_info = $regs[3];//informations : favoris[0], contacts[1], inbox[2],playlist[3], souscriptions[4], nb video[5]
$total_found = $ar_channel_info[5];//nombre total de video dans le compte
//<--Meta info sur la vidéothèque et l'utilisateur

//--> construit l'entete de la videotheque
function entvideo() {
global $ModPath, $ar_channel_thum, $ar_channel_date, $class_sty_1,$class_sty_2, $ar_channel_update, $account, $ar_channel_stat, $ar_channel_info, $nb_thum_vid, $start_index, $nav_block;

$ent_affi .= '
 <span class="'.$class_sty_1.'">'.video_yt_translate('Vid&#xE9;oth&#xE8;que de').' '.$account.'</span>
  <hr />
   <div id="yt_bouton" style="float :left;">
<a href="javascript:hide_ent();"><img src="modules/'.$ModPath.'/images/fl_b.gif" border="0" /></a>
   </div>
   <div >
&nbsp;&nbsp;<a href="http://gdata.youtube.com/feeds/api/users/'.$account.'/uploads" target="blank"><img src ="modules/'.$ModPath.'/images/standard_rss.png" border="0" /></a>
   </div>
<div id="yt_ent" style="display: block;">
<table border="1">
  <tr>
   <td width="20%"><img src ="'.$ar_channel_thum[1].'"><br />
   <span class="'.$class_sty_2.'">'.video_yt_translate('Nombre de vid&#xE9;os : ').' </span>'.$ar_channel_info[5].'<br />
   <span class="'.$class_sty_2.'">'.video_yt_translate('Cr&#xE9;&#xE9;e le : ').' </span><span class="help" title="'.$ar_channel_date[3].'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\5/\3/\1',$ar_channel_date[2]).'</span><br />
   <span class="'.$class_sty_2.'">'.video_yt_translate('Modifi&#xE9; le : ').' </span><span class="help" title="'.$ar_channel_update[3].'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\5/\3/\1',$ar_channel_update[2]).'</span><br />
   <span class="'.$class_sty_2.'">'.video_yt_translate('Vu :').' </span>'.$ar_channel_stat[1].'<br />
   <span class="'.$class_sty_2.'">'.video_yt_translate('Abonn&#xE9;(s) : ').' </span>'.$ar_channel_stat[3].'<br /><br />
</td>
<td>
<div id="yt_user" style="display: block;"></div>
<script>
insertVideos(\'yt_user\',\'user\',\''.$account.'\',\''.$nb_thum_vid.'\',2,\'french\',\''.$start_index.'\');
</script>
<div id="yt_navig" style="display: block; color: red">'.$nav_block.'</div>
</td>
  </tr>
</table>
</div>
<hr />';
echo $ent_affi;

}
//<-- construit l'entete de la videotheque

//////////////////////////////////////////////////////////////
if (!isset($_GET['start_index'])) 
	{
		$start_index = 1;
		$max_results = 0 + $incrementby;
	}
	else {$start_index = $_GET['start_index'];}
if ($start_index + $incrementby < $total_found )
	{
		$next_index = $start_index + $incrementby -1;
		$max_results = $next_index + $incrementby;
	} else {
		$next_index = $total_found;
		$max_results = $total_found;
	}
if ($start_index == 1) {
		$prev_index = 1;
	} else {
		$prev_index = $start_index + 1;
	}
//////////////////////////////////////////////////////////////

if(isset($_GET['video_id'])) 
{
$incrementby = 1;
$video_id = $_GET['video_id'];
$stream = file_get_contents("http://gdata.youtube.com/feeds/api/videos/$video_id");
}
else
{
$stream=file_get_contents("http://gdata.youtube.com/feeds/api/users/$account/uploads?start-index=$start_index&max-results=$max_results");
}

//mise en tableau
preg_match_all('#<entry><id>((.*?)(videos/)(.*?))</id>#s',$stream,$regs);
if(isset($_GET['video_id'])) 
 {$ar_id=explode(" ",$video_id);}
else
 {$ar_id=$regs[4];}

//--> pour chaque video récuperation fichier commentaires 
foreach($ar_id as $key => $value)
{
$stream_1.="\n".file_get_contents("http://gdata.youtube.com/feeds/api/videos/$value/comments");
}
//<-- pour chaque video récuperation fichier commentaires 

//preg_match_all('#<gd:comments><gd:feedLink(.*?)countHint=\'(\d+)\'/>#s',$stream,$regs);//nombre de lien

//infos générales
// preg_match_all('#<entry>(.*?)<author><(name)>(.*?)</\2>#s',$stream,$regs);
// $ar_author=$regs[3];//auteur
preg_match_all('#<(media:title)[^>]*>([^<]*)</\1>#s',$stream,$regs);
  $ar_title=$regs[2];//titre
preg_match_all('#<(media:description)[^>]*>([^<]*)</\1>#s',$stream,$regs);
  $ar_description=$regs[2];//description
preg_match_all('#<(media:keywords)[^>]*>([^<]*)</\1>#s',$stream,$regs);
  $ar_tags=$regs[2];//mot clef
preg_match_all('#<yt:duration seconds=\'(\d+)\'/>#s',$stream,$regs);
  $ar_length_seconds=$regs[1];//durée
preg_match_all('#<yt:statistics viewCount=\'(\d+)\'([^/>]*)/>#s',$stream,$regs);
  $ar_view_count=$regs[1];//nb de vue
preg_match_all('#<(media:category)[^>]*>([^<]*)</\1>#s',$stream,$regs);
  $ar_category=$regs[2];//category
preg_match_all('#<gd:rating[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+|\d+\.\d+).*?>#s',$stream,$regs);//les votes
  $ar_rating_count=$regs[3];//nombre
  $ar_rating_avg=$regs[4];//moyenne
  $ar_rating_min=$regs[1];//mini
  $ar_rating_max=$regs[2];//maxi
preg_match_all('#<(published)>([^T]*)T([^\.]*)([^<]*)</\1>#s',$stream,$regs);
$ar_upload_time=$regs[2];//date de publication


//modification du fichier youtube rajout des balises gml
$stream_new = preg_replace('#(/>)(<gd:comments>)#s','\1<georss:where><gml:Point><gml:pos></gml:pos></gml:Point></georss:where>\2',$stream);
preg_match_all('#<(gml:pos)>([^<]*)(</\1>)#s',$stream_new,$regs);
  $ar_localisation=$regs[2];//geolocalisation 

preg_match_all('#<media:thumbnail url=\'(.*?)\'#s',$stream,$regs);
  $ar_thumbnail_url=$regs[1];
  $ar_thumbnail_media = array_chunk($ar_thumbnail_url,4);

//info de détail
preg_match_all('#<(updated)>([^T]*)T([^\.]*)([^<]*)</\1>#s',$stream,$regs);
  $ar_update_time=$regs[2];//mise à jour
preg_match_all('#<(openSearch:totalResults)>(\d+)</\1>#s',$stream_1,$regs);
  $ar_comment_count=$regs[2];//nb de commentaire
  $nb_comment = $ar_comment_count[0];//nb de commentaire
preg_match_all('#<(published)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_1,$regs);
  $ar_comment_date=$regs[2];//date de publication commentaire
  $ar_comment_time=$regs[3];//heure de publication commentaire
preg_match_all('#<(updated)>([^T]*)T([^\.]*)[^<]*</\1>#',$stream_1,$regs);
  $ar_comment_up_time=$regs[2];//date de publication commentaire
preg_match_all('#<entry>(.*?)<author><(name)>([^<]*)</\2>#s',$stream_1,$regs);
  $ar_comment_author=$regs[3];//auteur du commentaire
preg_match_all('#<(content) type=([^>]*)>([^<]*)</\1>#s',$stream_1,$regs);
  $ar_comment_text=$regs[3];//texte du commentaire

//--> Construction du bloc navigation
if ($start_index != 1) 
   {
	$prev_index = $start_index - $incrementby;
	$max_results= $start_index;
	$nav_block .= "<a class=\"w\" href=\"modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;start_index=1&amp;total_found=$total_found&amp;max_results=$incrementby\">[".video_yt_translate("Premi&#xE8;res")."]</a>";
	$nav_block .= "<a class=\"w\"  href=\"modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;start_index=$prev_index&amp;total_found=$total_found&amp;max_results=$max_results\">[".video_yt_translate("Pr&#xE9;c&#xE9;dentes")."]</a>";
   }
else
   {
   $nav_block .= video_yt_translate('Premi&#xE8;res').' | '.video_yt_translate('Pr&#xE9;c&#xE9;dentes');
   }
if ($start_index == 1) 
   { $begin_index = 1;
   $max_results = $incrementby;
   } 
else 
   { $begin_index = $start_index; };
	$nav_block .= '&nbsp;&nbsp;'.video_yt_translate('Fiches').' '.$begin_index.' '.video_yt_translate('&#xE0;').' '.$next_index.' ('.video_yt_translate('total').' : '.$total_found.' )&nbsp;&nbsp;';

if ($start_index +$incrementby< $total_found) {
    $next_page = $start_index +$incrementby;
    $max_results = $next_page+$incrementby;
    $nav_block .= " <a class=\"e\" href=\"modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;start_index=$next_page&amp;total_found=$total_found&amp;max_results=$max_results\">[".video_yt_translate('Suivantes')."]</a>";
    $last_records = $total_found-$incrementby;
    $max_results = $total_found;
    $nav_block .= " <a class=\"e\" href=\"modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;start_index=$last_records&amp;total_found=$total_found&amp;max_results=$max_results\">[".video_yt_translate('Derni&#xE8;res')."]</a>";
}else {
		$nav_block .= ' '.video_yt_translate('Suivantes').' | '.video_yt_translate('Derni&#xE8;res');
	}

function listvideo() {
global $author, $account, $rep_account, $class_sty_1, $class_sty_2, $ar_author, $ar_title, $ar_description, $ar_tags, $ar_length_seconds, $ar_view_count, $ar_category, $ar_thumbnail_media, $ar_rating_count,$ar_rating_min, $ar_rating_max, $ar_rating_avg, $ar_description, $ar_view_count, $ar_update_time, $ar_upload_time, $ar_comment_count, $pat, $video_width, $video_height, $ar_id, $op, $nb_comment,$ar_comment_time, $ar_comment_date, $ar_comment_author, $ar_comment_text, $incrementby, $ar_meta_inf, $ar_localisation, $nav_block, $language, $bg_yt_search;

entvideo();//affiche l'entete de la page videotheque
$affichage ='<table border="1">';
for ($u=0;$u<1 ; $u++)
{
$lat_vi = preg_replace('#(\d+\.\d+)( )(\d+\.\d+)#', '\1', $ar_localisation[$u]);
$long_vi= preg_replace('#(\d+\.\d+)( )(\d+\.\d+)#', '\3', $ar_localisation[$u]);

$affichage .= '<tr><td><a href="javascript:videoOverlay(\''.$ar_id[$u].'\','.$video_width.','.$video_height.');"><img src ="'.reset($ar_thumbnail_media[$u]).'" title="'.$ar_title[$u].'" alt="'.$ar_title[$u].' : '.$ar_description[$u].'" onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,'.$ar_id[$u].',2)"></a><br /><br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Titre :').' </span>'.$ar_title[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Auteur :').' </span>'.$author.'<br/>
    <span class="'.$class_sty_2.'">'.video_yt_translate('Description :').' </span>'.$ar_description[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Dur&#xE9;e :').' </span>'.$ar_length_seconds[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Cat&#xE9;gorie :').' </span>'.$ar_category[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Votes :').' </span>'.$ar_rating_count[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Moyenne des votes :').' </span><span title="'.$ar_rating_min[$u].'|'.$ar_rating_max[$u].'">'.$ar_rating_avg[$u].'</span><br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Vu :').' </span>'.$ar_view_count[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Ajout&#xE9;e le :').' </span><span class="help" title ="'.video_yt_translate('Modifi&#xE9; le').' '.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\\5/\\3/\\1',$ar_update_time[$u]).'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\\5/\\3/\\1',$ar_upload_time[$u]).'</span><br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Commentaire(s) :').' </span>'.$ar_comment_count[$u].'<br />
    <span class="'.$class_sty_2.'">'.video_yt_translate('Mots-clefs :').' </span>'.preg_replace('#\b([\w]*)\b(\s|,|)#','<a href= "javascript:  insertVideos(\'yt_search\',\'search\',\'\1\',\'20\',1,\''.$language.'\');"   title="'.video_yt_translate('Cherche le mot-clef @ YouTube.com').'">\1</a>\2',$ar_tags[$u]).'<br /><br />';
    
    if (!empty($ar_localisation[$u]))//affichage coordonnées si existent
    $affichage.='<span class="'.$class_sty_2.'">'.video_yt_translate('Localisation :').' </span><br />'.video_yt_translate('Latitude :').' '.preg_replace('#(\d+\.\d+)( )(\d+\.\d+)#', '\1', $ar_localisation[$u]).'<br />'.video_yt_translate("Longitude :").' '.preg_replace('#(\d+\.\d+)( )(\d+\.\d+)#', '\3', $ar_localisation[$u]).'<br /><br />';
    if ($op!= 'detailvideo')
    $affichage.='<br /><hr/><a href="javascript:show_tool();"><img src="modules/video_yt/images/fl_d.gif" border="0" height="10px" width="10px" /></a><a href="modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;op=detailvideo&amp;video_id='.$ar_id[$u].'" title="'.video_yt_translate('Plus de d&#xE9;tail sur cette vid&#xE9;o').'">'.video_yt_translate('D&#xE9;tail').'</a><br /><br />';

    if ($op == 'detailvideo' and $nb_comment > 0) 
			{
    $affichage.='<br /><span class="'.$class_sty_2.'">'.video_yt_translate('Commentaire(s) :').' </span><br />';
			for ( $ii = 0; $ii < $nb_comment; $ii++ )
				{  $affichage.='<span title="'.$ar_comment_time[$ii].'">'.preg_replace('#(\d+)(-)(\d+)(-)(\d+)#','\\5/\\3/\\1',$ar_comment_date[$ii]).'</span><br /><span><a href=" http://youtube.com/profile?user='.$ar_comment_author[$ii].'" title="'.video_yt_translate('Voir le profil @ YouTube.com').'">'.$ar_comment_author[$ii].'</a> : </span><span class="'.$class_sty_3.'"> '.$ar_comment_text[$ii].'</span><br />';
				};
			}
    if ($op == 'detailvideo' and !empty($ar_localisation[$u]))
{
    $affichage.='<br /><div id="map_vid">Map loading...Or Google servers are down...</div>';
    //affichage localisation google
    $affichage.='
<script type="text/javascript">
//<![CDATA[
      var sidebar_html = "";
      var gmarkers = [];
      var htmls = [];
      var i = 0;

     //création du marker et construction de la fenetre info
      function createMarker(point,name,html) {
        var marker = new GMarker(point,icon);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        // save the info we need to use later for the sidebar
        gmarkers[i] = marker;
        htmls[i] = html;
        // add a line to the sidebar html
        sidebar_html += \'<a href="javascript:myclick(\' + i + \')" onmouseover="myclick(\'+i+\')">\' + name + \'</a>&nbsp;\';
        i++;
        return marker;
      }

// Création de icone vidéo
var icon = new GIcon();
icon.image = "modules/video_yt/images/webcam.png";
// icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
icon.iconSize = new GSize(32, 32);
icon.shadowSize = new GSize(22, 20);
icon.iconAnchor = new GPoint(6, 20);
icon.infoWindowAnchor = new GPoint(5, 1);

      // This function picks up the click and opens the corresponding info window
      function myclick(i) {
        gmarkers[i].openInfoWindowHtml(htmls[i]);
      }
var map_vid = new GMap2(document.getElementById("map_vid"));
map_vid.setCenter(new GLatLng('.$lat_vi.','.$long_vi.'), 4,G_NORMAL_MAP);
map_vid.addControl(new GScaleControl());
var point = new GLatLng('.$lat_vi.','.$long_vi.');'."\n".'
var marker = createMarker(point,"", \'\');'."\n".'
map_vid.addOverlay(marker);'."\n".'
      //]]>
</script>
   ';
   }
    $affichage.='
    </td>
    <td>
    <div id="youtubeoverlay">
    <script>videoOverlay(\''.$ar_id[$u].'\','.$video_width.','.$video_height.');</script>
    </div>
    </td>
</tr>
</table>';
    }
   //<div id=\"yt_search\" style=\"display: block; clear: both\"></div><object width=\"600\" height=\"400\"></div> <param name=\"movie\" value=\"http://www.youtube.com/v/$ar_id[$u]\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/$ar_id[$u]\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"$video_width\" height=\"$video_height\"></embed></object><br />
    

// if(!isset($_GET['video_id'])) 
// {$affichage .='<br />'.$nav_block;}
// else
// {$affichage .='<br /><a href="modules.php?ModPath=video_yt&amp;ModStart=video_yt01">'.video_yt_translate("Vid&#xE9;oth&#xE8;que").'</a><hr />';}

echo $affichage;
}

function yt_tool() {
global $language, $bg_yt_search;
$affi_tool .='<hr />
<div id="yt_bouton_tool" style="float :left;">
<a href="javascript:show_tool();"><img src="modules/video_yt/images/fl_d.gif" border="0" /></a>
</div>
Tools
 <div id="yt_tool" style="display: none";><br />
  <div id="yt_search_res" style="display: block";>
  </div>
   <div id="yt_search" style="display: block; clear: both; background-color:#'.$bg_yt_search.'" >
  </div>
<br /><input type="text" onblur="insertVideos(\'yt_search\',\'search\',this.value,\'20\',1,\''.$language.'\');" />   <input type="button" value="  Recherche  " />
</div>
<hr />';
echo $affi_tool;
}

switch ($op)
{
  case "listvideo": listvideo();yt_tool(); break;
  case "detailvideo": listvideo(); yt_tool(); break;
  default: listvideo(); yt_tool(); break;
}

include ('footer.php');
?>
###### Bout de code ancienne version
<a href="modules.php?ModPath=video_yt&amp;ModStart=video_yt&amp;video_id='.$ar_id[$id_ran].'">[french]Voir[/french][english]Watch[/english][chinese]&#x89C2;&#x770B;&#x8FD9;&#x5F55;&#x5F71;[/chinese]</a> | 


function videoOverlay(id,w_vi,h_vi){
  var objBody = document.getElementById('youtubeoverlay');
  objBody.innerHTML = '<div id="youtubecontent"><a href="javascript:hideOverlay()" id="close"><img src="modules/video_yt/images/close.gif" border="0" /></a><br /><object width="'+w_vi+'" height="'+h_vi+'"><param name="movie" value="http://www.youtube.com/v/'+id+'"></param><param name="autoplay" value="1"><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'+id+'&amp;autoplay=1" type="application/x-shockwave-flash" wmode="transparent" width="'+w_vi+'" height="'+h_vi+'"></embed></object></div>';
}








function getId(string){
    var match = string.lastIndexOf("'s Videos");
    if (match != -1) {
      id = string.substring(0,match);
      return id.toLowerCase();
    }

    var match = string.lastIndexOf("query");
    if (match != -1) {
      id = string.substring(match+7);
      return id.toLowerCase();
    }

}
function listVideos(json,divid) {
  divid.innerHTML = '';
  var ul = document.createElement('ul');
  ul.setAttribute('id', 'youtubelist');
  if(json.feed.entry){
  for (var i = 0; i < json.feed.entry.length; i++) {
    var entry = json.feed.entry[i];

    for (var k = 0; k < entry.link.length; k++) {
      if (entry.link[k].rel == 'alternate') {
        url = entry.link[k].href;
        break;
      }
    }

   var thumb = entry['media$group']['media$thumbnail'][1].url;
   var idd_tool= entry.id.$t;
   var idvideo_tool = idd_tool.lastIndexOf('/');
   id_tool = idd_tool.substring(idvideo_tool+1);

    var li = document.createElement('li');
    li.setAttribute('id', 'youtubebox_'+id_tool);
    li.setAttribute('class', 'youtubebox');

    if(cleanReturn == 1) {
     if(inlineVideo == 1) {
      li.innerHTML = '<a href="javascript:videoOverlay(\''+id_tool+'\','+w_vi+','+h_vi+');"><img src="'+thumb+'" class="youtubethumb" id="youtubethumb'+id_tool+'" alt="'+entry.title.$t+'" title="'+entry.title.$t+'" onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,\''+id_tool+'\',2)" /></a>';
      }else
      {
      li.innerHTML = '<a href=" http://www.grottes-et-karsts-de-chine.org/npds/modules.php?ModPath=video_yt&amp;ModStart=video_yt01&amp;op=detailvideo&amp;video_id='+id_tool+'"><img src="'+thumb+'" id="youtubethumb" alt="'+entry.title.$t+'" title="'+entry.title.$t+'" onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,\''+id_tool+'\',2)" /></a>';
      }
     }
     else
     {
      li.innerHTML = entry.content.$t;
     }
    ul.appendChild(li);
 }
  }else{
  divid.innerHTML = 'No Results Found';
  }

  document.getElementById(divid).appendChild(ul);
}

function youtubeInit(root) {
  //this hacks the layer for mutiple json queries
  id = getId(root.feed.title.$t);
  listVideos(root, youtubediv[id]);
}

function insertVideos(div,typ,q,results,overlay,lang,startindex){
  inlineVideo = overlay;
  youtubediv[q.toLowerCase()] = div;

  var script = document.createElement('script');
  if(typ == "search"){
   script.setAttribute('src', 'http://gdata.youtube.com/feeds/videos?vq='+q+'&max-results='+results+'&alt=json-in-script&callback=youtubeInit');
   var lay_search = document.getElementById('yt_search');
   lay_search.innerHTML='';
   var lay = document.getElementById('yt_search_res');
   lay.innerHTML = '<div id="yt_bouton_search" style="float :left;"><a href="javascript:hide_search();"><img src="modules/video_yt/images/fl_b.gif" border="0" height="12px" width="12px" /></a></div>';

switch (yt.lang)
	{ 
case "french":lay.innerHTML = lay.innerHTML+'<b>R&eacute;sultat de recherche pour :</b>';break;
case "english":lay.innerHTML = lay.innerHTML+'<b>Search results with :</b>';break;
case "chinese":lay.innerHTML = lay.innerHTML+'<b>Search results with :</b>';break;
case "spanish":lay.innerHTML = lay.innerHTML+'<b>Search results with :</b>';break;
default :lay.innerHTML = lay.innerHTML+'<b>Search results with :</b>';break;
	}
  
  lay.innerHTML = lay.innerHTML+'&nbsp;'+q+'<br /><br />';
  show_tool();
  show_search();
  };

  if(typ == "user"){
  script.setAttribute('src', 'http://gdata.youtube.com/feeds/users/'+q+'/uploads?max-results='+results+'&alt=json-in-script&callback=youtubeInit');
  var lay = document.getElementById('yt_user');
  lay.innerHTML = lay.innerHTML+'&nbsp;'+q+'<br /><br />';
}
  script.setAttribute('id', 'jsonScript');
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);
}



