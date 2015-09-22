/* video_yt.json
 * file for NPDS
 * functions for browsing and searching YouTube 
 * data API feeds, (use JSON output from the API.) in the NPDS CMS context :
 * module video_yt
 * jpb 2009
 * version 2.2 10/07/2012
 *source : from @author api.rboyd@google.com (Ryan Boyd)
 */

var yt = {};
yt.feed_url = 'http://gdata.youtube.com/feeds/api/';
yt.com ='';
yt.thesearchmot = '';
yt.searchmot_onair = function(mot){yt.thesearchmot = mot;};
//navigation
yt.mr = 7;//valeur maxi de video affichée dans videotheque avant pagination set by serveur
yt.msr = 25;//valeur maxi de video affichée dansrechercheset by serveur
yt.mrmsr = yt.mr+yt.msr;//longueur maxi des arrays
yt.myaccount = '';// set by serveur
yt.rep_account = '';// set by serveur
yt.nextpage = 2;
yt.lastvidpage = 0;
yt.infochanel ='';
//le lecteur
yt.h_vi = 265; //hauteur de l'objet
yt.w_vi = 320; //largeur de l'objet
//infos chaine
yt.vu_chanel ='';// nombre visionage de la chaine
yt.nbvi_chanel ='';// nombre video dans la chaine
yt.crea_chanel ='';// date de creation de la chaine
yt.modi_chanel ='';// date de modification de la chaine
yt.abon_chanel ='';// nombre abonne de la chaine
yt.avat_chanel ='';// avatar utilisateur
//infos video
yt.id_vid='';// id de la video
yt.titr_vid ='';// titre de la video
yt.auteur_vid ='';//auteur de la video
yt.desc_vid ='';//description de la video
yt.duree_vid ='';// duree de la video
yt.cat_vid ='';// categorie de la video
yt.vote_vid ='';// nombre vote de la video
yt.moy_vote_vid ='';// moyenne vote de la video
yt.vu_vid ='';// nombre de visionage de la video 
yt.ajou_vid='';// date et heure de publication de la video
  yt.ajou_vid_d='';// date de publication de la video
  yt.ajou_vid_t='';// heure de publication de la video
yt.modi_vid='';// date et heure de modification de la video
  yt.modi_vid_d='';// date de modification de la video
  yt.modi_vid_t='';// heure de modification de la video

yt.comm_vid='';// nombre commentaire de la video
yt.keyword_vid =[''];// motcles de la video
yt.motclefs_list ='';
yt.coord_vid = [''];// tableau des coordonnees de la video
  yt.lat_vid ='';// latitude de la video
  yt.long_vid ='';// longitude de la video
  yt.loc_vid='';// georeferencement=>1 ou pas =>0
yt.thumb_vid ='';//appercu de la video

//commentaires
yt.comment_tit ='';// titre commentaire
yt.comment_content='';// contenu commentaire
yt.comment_auteur='';// auteur commentaire
yt.comment_ajou='';// date et heure de publication du commentaire
  yt.comment_ajou_d='';// date de publication du commentaire
  yt.comment_ajou_t='';// heure de publication du commentaire
yt.comment_modi='';// date et heure de modification du commentaire
  yt.comment_modi_d='';// date de modification du commentaire
  yt.comment_modi_t='';// heure de modification du commentaire
// tableau info video
yt.id_html =[];
yt.titr_html =[];
yt.desc_html =[];
yt.aute_html =[];
yt.dure_html =[];
yt.cate_html =[];
yt.vote_html =[];
yt.moy_vote_html =[];
yt.vu_html =[];
yt.ajou_html =[];
yt.ajou_t_html =[];
yt.modi_html =[];
yt.modi_t_html =[];
yt.comm_html =[];
yt.motcle_html =[];
yt.lat_html =[];
yt.long_html =[];

yt.vide_array = function() {
//==>remise à zero des tableau
yt.id_html =[];
yt.titr_html =[];
yt.desc_html =[];
yt.aute_html =[];
yt.dure_html =[];
yt.cate_html =[];
yt.vote_html =[];
yt.moy_vote_html =[];
yt.vu_html =[];
yt.ajou_html =[];
yt.ajou_t_html =[];
yt.modi_html =[];
yt.modi_t_html =[];
yt.comm_html =[];
yt.motcle_html =[];
yt.lat_html =[];
yt.long_html =[];
yt.coord_vid =[];
};
//<== remise à zero des tableau

yt.lang ='';
var cur_charset='';//met la constante en variable vide
var timer;
var i = 0;
var lastvidpage;

function clearList(ul){
  var list = document.getElementById(ul);
  while (list.firstChild) 
   {
      list.removeChild(list.firstChild);
   }
}

yt.show_comment = function show_comment(){
  var yt_com = document.getElementById('yt_comment');
  yt_com.style.display = 'inline';
  var btn_ouvr = document.getElementById('yt_bouton_com');
  btn_ouvr.innerHTML = '<a href="javascript:yt.hide_comment();"><img src="modules/video_yt/images/fl_b.gif" height="14px" width="14px" alt="triangle pointe en bas" title="'+video_yt_translate('Masquer les commentaires de cette vid&#xE9;o')+'" border="0" /></a>';
}
yt.hide_comment = function hide_comment(){
  var yt_com = document.getElementById('yt_comment');
  yt_com.style.display = 'none';
  var btn_ouvr = document.getElementById('yt_bouton_com');
  btn_ouvr.innerHTML = '<a href="javascript:yt.show_comment();"><img src="modules/video_yt/images/fl_d.gif" height="14px" width="14px" alt="triangle pointe a droite" title="'+video_yt_translate('Voir les commentaires de cette vid&#xE9;o')+'" border="0" /></a>';
}
function hide_tool(){
  var ov = document.getElementById('yt_tool');
  ov.style.display = 'none';
  var btn_ouvr = document.getElementById('yt_bouton_tool');
  btn_ouvr.innerHTML = '<a href="javascript:show_tool();"><img src="modules/video_yt/images/fl_d.gif" alt="triangle pointe a droite" title="'+video_yt_translate('Voir le panneau des outils')+'" border="0" /></a>';
}
function show_tool(){
  var ov = document.getElementById('yt_tool');
  ov.style.display = 'block';
  var btn_ferm = document.getElementById('yt_bouton_tool');
  btn_ferm .innerHTML = '<a href="javascript:hide_tool();"><img src="modules/video_yt/images/fl_b.gif" alt="triangle pointe en bas" title="'+video_yt_translate('Masquer le panneau des outils')+'" border="0" /></a>';
}
function hide_visua(){
  var ov = document.getElementById('yt_visua');
  ov.style.display = 'none';
  var btn_ouvr = document.getElementById('yt_bouton_visua');
  btn_ouvr.innerHTML = '<a href="javascript:show_visua();"><img src="modules/video_yt/images/fl_d.gif" alt="triangle pointe a droite" title="'+video_yt_translate('Voir le panneau de visualisation')+'" border="0" /></a>';
}
function show_visua(){
  var ov = document.getElementById('yt_visua');
  ov.style.display = 'block';
  var btn_ferm = document.getElementById('yt_bouton_visua');
  btn_ferm .innerHTML = '<a href="javascript:hide_visua();"><img src="modules/video_yt/images/fl_b.gif" alt="triangle pointe en bas" title="'+video_yt_translate('Masquer le panneau de visualisation')+'" border="0" /></a>';
}
function hide_ent(){
  var ov = document.getElementById('yt_ent');
  ov.style.display = 'none';
  var btn_ouvr = document.getElementById('yt_bouton');
  btn_ouvr.innerHTML = '<a href="javascript:show_ent();"><img src="modules/video_yt/images/fl_d.gif" alt="triangle pointe droite" title="Afficher le panneau de navigation." border="0" /></a>';
}
function show_ent(){
  var ov = document.getElementById('yt_ent');
  ov.style.display = 'block';
  var btn_ferm = document.getElementById('yt_bouton');
  btn_ferm .innerHTML = '<a href="javascript:hide_ent();"><img src="modules/video_yt/images/fl_b.gif" alt="triangle pointe en bas" title="Masquer le panneau de navigation." border="0" /></a>';
}
function hide_search(){
  var ov = document.getElementById('yt_search');
  ov.style.display = 'none';
  var ov_ = document.getElementById('yt_search_res');
  ov_.style.display = 'none';
}
function show_search(){
  var ov = document.getElementById('yt_search');
  ov.style.display = 'block';
  var ov_ = document.getElementById('yt_search_res');
  ov_.style.display = 'block';
}

function mousOverImage(name,id,nr){
  if(name) imname = name;
  imname.style.border = '4px solid gray';
  imname.src = "http://i.ytimg.com/vi/"+id+"/"+nr+".jpg";
  nr++;
  if(nr > 3) nr = 1;
  timer =  setTimeout("mousOverImage(false,'"+id+"',"+nr+");",1000);
}
function mouseOutImage(name){
  if(name) imname = name;
  imname.style.border = '4px solid #333333';
  if(timer) clearTimeout(timer);
}

function loadVideo(playerUrl, autoplay,i,ar_ind) {
if (ar_ind ==1) i=i+yt.mr;//!choisit les secteurs du tableau
if ((yt.aute_html[i] == yt.myaccount) && (yt.rep_account !=='')) yt.aute_html[i]= yt.rep_account;

//==> avec swfobject embedSWF... ne fonctionne pas sous ie ?...
/*  swfobject.embedSWF(
      playerUrl + '&rel=1&fs=1&autoplay=' + 
      (autoplay?1:0), 'yt_player', yt.w_vi, yt.h_vi, '9.0.0', false, 
      false, {allowfullscreen: 'true'});
*/      
//==> avec swfobject embedSWF... ne fonctionne pas sous ie ?...

//==> avec swfobject registerObject (static)... ecrit par js ne fonctionne pas sous safari double objet la condition ne fonctionne pas ?...

//swfobject.registerObject("main_FlashContent", "9.0.0");
// var vidplay = document.getElementById('yt_playercontainer')
// vidplay.innerHTML ='  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+yt.w_vi+'" height="'+yt.h_vi+'" id="main_FlashContent">';
// vidplay.innerHTML +='    <param name="movie" value="http://www.youtube.com/v/'+yt.id_html[i]+'" />';
// 
// vidplay.innerHTML +='    <param name="play" value="false" />';
// vidplay.innerHTML +='    <param name="allowfullscreen" value="true" />';
// vidplay.innerHTML +='    <!--[if !IE]>-->';
// vidplay.innerHTML +='    <object type="application/x-shockwave-flash" data="http://www.youtube.com/v/'+yt.id_html[i]+'" width="'+yt.w_vi+'" height="'+yt.h_vi+'">';
// vidplay.innerHTML +='     <param name="play" value="false" />';
// vidplay.innerHTML +='     <param name="allowfullscreen" value="true" />';
// vidplay.innerHTML +='    <!--<![endif]-->';
// // vidplay.innerHTML +='     <a href="http://www.adobe.com/go/getflashplayer">';
// // vidplay.innerHTML +='      <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />';
// // vidplay.innerHTML +='     </a>';
// vidplay.innerHTML +='    <!--[if !IE]>-->';
// vidplay.innerHTML +='    </object>';
// vidplay.innerHTML +='    <!--<![endif]-->';
// vidplay.innerHTML +='   </object>';
//==> avec swfobject registerObject (static)... ecrit par js ne fonctionne pas sous safari double objet la condition ne fonctionne pas ?...

//==> avec swfobject registerObject... ecrit par php ne fonctionne pas sous safari pas d'affichage la condition  fonctionne ?... les attributs sont changés ici
/*
   var newAttr = document.createAttribute("data");
    newAttr.nodeValue = 'http://www.youtube.com/v/'+yt.id_html[i];
    document.getElementById("yt_movie").setAttributeNode(newAttr); 
    var newAttr = document.createAttribute("value");
    newAttr.nodeValue = 'http://www.youtube.com/v/'+yt.id_html[i];
    document.getElementById("yt_movie_ie").setAttributeNode(newAttr);
*/
//<== avec swfobject registerObject... ecrit par php ne fonctionne pas sous safari pas d'affichage la condition  fonctionne ?... les attributs sont changés ici

//==>vite fait mal fait avec embed ....fonctionne de partout mais non conforme
 var vidplay = document.getElementById('yt_playercontainer')
vidplay.innerHTML ='<object width="'+yt.w_vi+'" height="'+yt.h_vi+'"><param name="movie" value="http://www.youtube.com/v/'+yt.id_html[i]+'&color1=0xb1b1b1&color2=0xcfcfcf&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/'+yt.id_html[i]+'&color1=0xb1b1b1&color2=0xcfcfcf&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="'+yt.w_vi+'" height="'+yt.h_vi+'"></embed></object>';
//<==vite fait mal fait avec embed



///////////////////////////////
      var detail_vid = document.getElementById('detail_video');
      detail_vid.innerHTML = '<p><span class="yt_soustitre">'+video_yt_translate('Titre :')+' </span>'+yt.titr_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Auteur :')+' </span>'+yt.aute_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Description :')+' </span>'+yt.desc_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Dur&#xE9;e :')+' </span>'+yt.dure_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Cat&#xE9;gorie :')+' </span>'+yt.cate_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Votes :')+' </span>'+yt.vote_html[i]+'<span class="yt_soustitre"> | '+video_yt_translate('Moyenne des votes :')+' </span>'+yt.moy_vote_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Vu :')+' </span>'+yt.vu_html[i]+'</p>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Ajout&#xE9;e le :')+' </span><span title="'+yt.ajou_t_html[i]+'">'+yt.ajou_html[i]+'</span><span class="yt_soustitre"> | '+video_yt_translate('Modifi&#xE9;e le :')+' </span><span title="'+yt.modi_t_html[i]+'">'+yt.modi_html[i]+'</span></p>';
      if(yt.comm_html[i]!== 0)
      detail_vid.innerHTML += '<p><span id="yt_bouton_com" style="float:left;"><a href="javascript:yt.show_comment();"><img src="modules/video_yt/images/fl_d.gif" height="14px" width="14px" alt="triangle pointe en bas" title="'+video_yt_translate('Voir les commentaires de cette vid&#xE9;o')+'" border="0" /></a></span><span class="yt_soustitre" style="float:left;">'+video_yt_translate('Commentaire(s) :')+' '+yt.comm_html[i]+'</span></p><br style="clear:left;" /><div id ="yt_comment" style="display:none;"></div>';
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Mots-clefs :')+' </span>'+yt.motcle_html[i]+'</p>';

      if(yt.lat_html[i] != '' && yt.long_html[i] !='')
      detail_vid.innerHTML += '<p><span class="yt_soustitre">'+video_yt_translate('Localisation :')+' </span></p><p><span class="yt_soustitre">'+video_yt_translate('Latitude :')+' </span>'+yt.lat_html[i]+'<span class="yt_soustitre"> | '+video_yt_translate('Longitude :')+' </span>'+yt.long_html[i]+'</p>';

      if(yt.comm_html[i]!== 0) yt.appendScriptTag(yt.feed_url,'videos/'+yt.id_html[i]+'/comments','','showVideoComment','json_comment','1','25','','flyingscript_2');
      }
/*
scriptSrc => url du script ex http://gdata.youtube.com/feeds/api/ =>avec le slash
scriptPref=> suite de l'url avant le ?
scriptSuf=> premier argument => si il existe ildoit se terminer par un &amp;
scriptCallback => nom de la fonction callback
scriptId => id du script
stind => start index des resultats
mr => maximun de resultat
scriptMore =>
scriptFly => nom de l'element html ou est attache le script
*/
yt.appendScriptTag = function(scriptSrc, scriptPref, scriptSuf, scriptCallback, scriptId, stind, mr, scriptMore, scriptFly) {
  // Remove any old existance of a script tag by the same name
  var oldScriptTag = document.getElementById(scriptId);
  if (oldScriptTag) {
    oldScriptTag.parentNode.removeChild(oldScriptTag);
  }
  // Cree le nouveau script 
  var script = document.createElement('script');
  script.setAttribute('src', scriptSrc+scriptPref+'?'+scriptSuf+'alt=json-in-script&callback='+scriptCallback+'&start-index='+stind+'&max-results='+mr+scriptMore);
  script.setAttribute('id', scriptId);
  script.setAttribute('type', 'text/javascript');
if (scriptCallback =='showMyVideos2')
document.getElementsByTagName('head')[0].appendChild(script);

  newscri = document.getElementById(scriptFly);
  newscri.appendChild(script);
  // mise à jour bloc navigation
if (scriptCallback =='showMyVideos2')
  yt.updateNavigation(stind);
}

yt.updateNavigation = function(page) {
  yt.nextpage = page;
  if((yt.nextpage+yt.mr) <= yt.nbvi_chanel) {
  yt.lastvidpage = (yt.nextpage+yt.mr)-1
  }else{
  yt.lastvidpage = yt.nbvi_chanel
  }
  navbl = document.getElementById("yt_navbloc");
  if (page > 1) {
  navbl.innerHTML ='<a class="w" href="javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\',1,'+yt.mr+',\'&amp;author='+yt.myaccount+'\',\'flyingscript\')">['+video_yt_translate('Premi&#xE8;res')+']</a><a class="w" href="javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\','+(page-yt.mr)+','+yt.mr+',\'&amp;author='+yt.myaccount+'\',\'flyingscript\')">['+video_yt_translate('Pr&#xE9;c&#xE9;dentes')+']</a>';
  } else {  navbl.innerHTML =''+video_yt_translate('Premi&#xE8;res')+' | '+video_yt_translate('Pr&#xE9;c&#xE9;dentes')+'';
  yt.lastvidpage = yt.mr;
  }
  navbl.innerHTML +='&nbsp;&nbsp;<span class="yt_soustitre">'+video_yt_translate('Vid&#xE9;os')+'</span> '+page+' <span class="yt_soustitre">'+video_yt_translate('&#xE0;')+'</span> '+yt.lastvidpage+' <span class="yt_soustitre">'+video_yt_translate('total')+' :</span> '+yt.nbvi_chanel+'&nbsp;&nbsp;';
  if((yt.nextpage+yt.mr) <= yt.nbvi_chanel) {
  navbl.innerHTML +=' <a class="e" href=" javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\','+(yt.nextpage+yt.mr)+','+yt.mr+',\'&amp;author='+yt.myaccount+'\',\'flyingscript\')">['+video_yt_translate('Suivantes')+']</a><a class="e" href="javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'\',\'showMyVideos2\',\'jsonscript\','+((yt.nbvi_chanel-yt.mr)+2)+','+yt.mr+',\'&amp;author='+yt.myaccount+'\',\'flyingscript\')">['+video_yt_translate('Derni&#xE8;res')+']</a>';
  }else{
  navbl.innerHTML +=' '+video_yt_translate('Suivantes')+' | '+video_yt_translate('Derni&#xE8;res')+'';
  }
};

function showMyVideos2(data) {
yt.vide_array(); //==>remise à zero des tableau

  var feed = data.feed;
  var entries = feed.entry || [];
  var html = ['<ul class="videos">'];

  for (var i = 0; i < entries.length; i++) {
  if(i>0){
    var entry = entries[i];
    var idd = entry.id.$t;
    var idvideo = idd.lastIndexOf('/');
    if (idvideo) 
    var id = idd.substring(idvideo+1);
    yt.thumb_vid = entries[i].media$group.media$thumbnail[0].url;
    var playerUrl = entries[i].media$group.media$content[0].url;

    yt.titr_vid = entry.title.$t;
    yt.short_titr_vid = entry.title.$t.substr(0, 15);
    yt.auteur_vid = entry.author[0].name.$t;
    yt.desc_vid = entry.content.$t;
    yt.duree_vid = entry.media$group.media$content[0].duration;
    yt.cat_vid = entry.media$group.media$category[0].$t;
    if(entry.gd$rating) {
    yt.vote_vid = entry.gd$rating.numRaters;
    yt.moy_vote_vid = entry.gd$rating.average;
    }
    else 
    {
    yt.vote_vid = 0;
    yt.moy_vote_vid = 0;
    }
    if (entry.yt$statistics)
    {yt.vu_vid = entry.yt$statistics.viewCount}else{yt.vu_vid = '?'}

//==>traitement date et heures publication et modification
    yt.ajou_vid = entry.published.$t.split('T');
    yt.ajou_vid_d = yt.ajou_vid[0].split('-');
    yt.ajou_vid_d = yt.ajou_vid_d.reverse();
    yt.ajou_vid_d = yt.ajou_vid_d.join('/');
    yt.ajou_vid_t = yt.ajou_vid[1].split('.');
    yt.ajou_vid_t = yt.ajou_vid_t[0];

    yt.modi_vid = entry.updated.$t.split('T');
    yt.modi_vid_d = yt.modi_vid[0].split('-');
    yt.modi_vid_d = yt.modi_vid_d.reverse();
    yt.modi_vid_d = yt.modi_vid_d.join('/');
    yt.modi_vid_t = yt.modi_vid[1].split('.');
    yt.modi_vid_t = yt.modi_vid_t[0];
//<==traitement date et heures publication et modification

//==>traitement des commentaires
if (entry.gd$comments)
    {yt.comm_vid = entry.gd$comments.gd$feedLink.countHint;}
//<==traitement des commentaires

//==>traitement des mots clefs
    var expreg = new RegExp(',','gi');
    yt.keyword_vid = entry.media$group.media$keywords.$t;
    if(yt.keyword_vid) yt.motclefs_list = yt.keyword_vid.split(expreg);
    var keyword_link=[];
    for (var y = 0; y < yt.motclefs_list.length; y++) {
    yt.motclefs_list[y].replace(/,/,' ');
    yt.motclefs_list[y]='<a href="javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'vq='+yt.motclefs_list[y]+'&amp;\',\'searchVideo\',\'searchscript\',\'1\','+yt.msr+',\'\',\'flyingscript\');hide_visua();hide_ent();yt.searchmot_onair(\''+yt.motclefs_list[y]+'\');" title="'+video_yt_translate('Cherche vid&#xE9;os avec ce mot clef')+'">'+yt.motclefs_list[y]+'</a>';
    keyword_link.push(yt.motclefs_list[y]);
    keyword_link.join(' ');
    }
//<==traitement des mots clefs

    if (entry.georss$where) {
    yt.coord_vid = entry.georss$where.gml$Point.gml$pos.$t.split(' ');
    yt.lat_vid = yt.coord_vid[0];
    yt.long_vid = yt.coord_vid[1];
    yt.lat_html.push(yt.lat_vid);
    yt.long_html.push(yt.long_vid);
    } else {yt.lat_html.push('');
    yt.long_html.push('');
    }

    yt.id_html.push(id);
    yt.titr_html.push(yt.titr_vid);
    yt.aute_html.push(yt.auteur_vid);
    yt.desc_html.push(yt.desc_vid);
    yt.dure_html.push(yt.duree_vid);
    yt.cate_html.push(yt.cat_vid);
    yt.vote_html.push(yt.vote_vid);
    yt.moy_vote_html.push(yt.moy_vote_vid);
    yt.vu_html.push(yt.vu_vid);
    yt.modi_html.push(yt.modi_vid_d);
    yt.modi_t_html.push(yt.modi_vid_t);
    yt.ajou_html.push(yt.ajou_vid_d);
    yt.ajou_t_html.push(yt.ajou_vid_t);

    yt.comm_html.push(yt.comm_vid);
    yt.motcle_html.push(keyword_link);
    
    html.push('<li class="youtubebox" onclick="loadVideo(\'', playerUrl, '\', true,'+(i-1)+');show_visua();">',
              '<span class="titlec">', yt.short_titr_vid, '...</span><br /><img class="youtubethumb_mycha" src="', 
              yt.thumb_vid, '" onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,\''+id+'\',2)" alt="'+yt.titr_vid+'" title="'+video_yt_translate('Voir')+' : '+yt.titr_vid+'" />', '</li>');
  }
  }
  html.push('</ul><br style="clear: left;"/>');

  document.getElementById('videos2').innerHTML = html.join('');
}
function showMychanel(data) {
  var entries = data.entry
  yt.nbvi_chanel = entries.gd$feedLink[4].countHint;
  yt.crea_chanel = entries.published.$t;
  yt.modi_chanel = entries.updated.$t;
  yt.vu_chanel = entries.yt$statistics.viewCount;
  yt.abon_chanel = entries.yt$statistics.subscriberCount;
  yt.avat_chanel = entries.media$thumbnail.url;
}
function showVideoComment(data) {
  var feed = data.feed;
  var id_com = feed.id.$t.split(':');
  var entries = feed.entry || [];
  var html = ['<ul class="yt_comment">'];

  for (var i = 0; i < entries.length; i++) {
  var entry = entries[i];
  yt.comment_tit = entries[i].title.$t;
  yt.comment_content = entries[i].content.$t;
  yt.comment_auteur = entries[i].author[0].name.$t;
  
//==>traitement date et heures publication et modification du commentaire
    yt.comment_ajou = entry.published.$t.split('T');
    yt.comment_ajou_d = yt.comment_ajou[0].split('-');
    yt.comment_ajou_d = yt.comment_ajou_d.reverse();
    yt.comment_ajou_d = yt.comment_ajou_d.join('/');
    yt.comment_ajou_t = yt.comment_ajou[1].split('.');
    yt.comment_ajou_t = yt.comment_ajou_t[0];

    yt.comment_modi = entry.updated.$t.split('T');
    yt.comment_modi_d = yt.comment_modi[0].split('-');
    yt.comment_modi_d = yt.comment_modi_d.reverse();
    yt.comment_modi_d = yt.comment_modi_d.join('/');
    yt.comment_modi_t = yt.comment_modi[1].split('.');
    yt.comment_modi_t = yt.comment_modi_t[0];
//<==traitement date et heures publication et modification du commentaire
  
  html.push('<li class="yt_com_ent"><span title="'+yt.comment_ajou_t+'">'+yt.comment_ajou_d+'</span> : <a href="">'+yt.comment_auteur+'</a><span class="yt_soustitre"> '+video_yt_translate('&#xE9;crit')+' : </span>'+yt.comment_tit+'</li><li class="yt_com_cont">'+yt.comment_content+'</li>')
  }
  html.push('</ul><br style="clear: left;"/>');
  var ov = document.getElementById('yt_comment');
  ov.innerHTML = html.join('');
}
function searchVideo(data){
  hide_visua();
  function addslashes(ch) {
  ch = ch.replace(/\\/g,"\\\\")
  ch = ch.replace(/\'/g,"\\'")
  ch = ch.replace(/\"/g,"\\\"")
  return ch
  }
//==>nettoyage du tableau
  if (yt.id_html.length > yt.mr) {
    yt.id_html.splice(yt.mr,yt.mrmsr);
    yt.titr_html.splice(yt.mr,yt.mrmsr);
    yt.aute_html.splice(yt.mr,yt.mrmsr);
    yt.desc_html.splice(yt.mr,yt.mrmsr);
    yt.dure_html.splice(yt.mr,yt.mrmsr);
    yt.cate_html.splice(yt.mr,yt.mrmsr);
    yt.vote_html.splice(yt.mr,yt.mrmsr);
    yt.moy_vote_html.splice(yt.mr,yt.mrmsr);
    yt.vu_html.splice(yt.mr,yt.mrmsr);
    yt.modi_html.splice(yt.mr,yt.mrmsr);
    yt.modi_t_html.splice(yt.mr,yt.mrmsr);
    yt.ajou_html.splice(yt.mr,yt.mrmsr);
    yt.ajou_t_html.splice(yt.mr,yt.mrmsr);

    yt.comm_html.splice(yt.mr,yt.mrmsr);
    yt.motcle_html.splice(yt.mr,yt.mrmsr);
    yt.lat_html.splice(yt.mr,yt.mrmsr);
    yt.long_html.splice(yt.mr,yt.mrmsr);
  }
//<==nettoyage du tableau

  var htmlsV = ['<ul class="videos">'];
  var feed = data.feed;

//pagination  
  var incrementby = feed.openSearch$itemsPerPage.$t;
  var startindex = feed.openSearch$startIndex.$t;
  var totalresult = feed.openSearch$totalResults.$t;
  var titleresult = feed.title.$t;
  var lay_search = document.getElementById('yt_search');
  lay_search.innerHTML='';
  var lay = document.getElementById('yt_search_res');
 if(feed.entry){  
  for (var i = 0; i < feed.entry.length; i++) {
    var entry = data.feed.entry[i];
    var idd = entry.id.$t;
    var idvideo = idd.lastIndexOf('/');
    if (idvideo) var id = idd.substring(idvideo+1);
    yt.thumb_vid = entry.media$group.media$thumbnail[0].url;
    var playerUrl = entry.media$group.media$content[0].url;

     yt.titr_vid = entry.title.$t;
     yt.short_titr_vid = entry.title.$t.substr(0, 15);
     yt.auteur_vid = entry.author[0].name.$t;
     yt.desc_vid = entry.content.$t;
     yt.duree_vid = entry.media$group.media$content[0].duration;
     yt.cat_vid = entry.media$group.media$category[0].$t;
     if(entry.gd$rating) {
     yt.vote_vid = entry.gd$rating.numRaters;
     yt.moy_vote_vid = entry.gd$rating.average;
     }
    else 
    {
    yt.vote_vid = 0;
    yt.moy_vote_vid = 0;
    }
    if (entry.yt$statistics)
    {yt.vu_vid = entry.yt$statistics.viewCount}else{yt.vu_vid = '?'}

//==>traitement date et heures publication et modification
    yt.ajou_vid = entry.published.$t.split('T');
    yt.ajou_vid_d = yt.ajou_vid[0].split('-');
    yt.ajou_vid_d = yt.ajou_vid_d.reverse();
    yt.ajou_vid_d = yt.ajou_vid_d.join('/');
    yt.ajou_vid_t = yt.ajou_vid[1].split('.');
    yt.ajou_vid_t = yt.ajou_vid_t[0];

    yt.modi_vid = entry.updated.$t.split('T');
    yt.modi_vid_d = yt.modi_vid[0].split('-');
    yt.modi_vid_d = yt.modi_vid_d.reverse();
    yt.modi_vid_d = yt.modi_vid_d.join('/');
    yt.modi_vid_t = yt.modi_vid[1].split('.');
    yt.modi_vid_t = yt.modi_vid_t[0];
//<==traitement date et heures publication et modification
//==>traitement des mots clefs
    var expreg = new RegExp(',','gi');
    yt.keyword_vid = entry.media$group.media$keywords.$t;
    if(yt.keyword_vid) yt.motclefs_list = yt.keyword_vid.split(expreg);
    var keyword_link=[];
    for (var y = 0; y < yt.motclefs_list.length; y++) {
    yt.motclefs_list[y].replace(/,/,' ');
    yt.motclefs_list[y]='<a href="javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'vq='+yt.motclefs_list[y]+'&amp;\',\'searchVideo\',\'searchscript\',\'1\','+yt.msr+',\'\',\'flyingscript\');hide_visua();hide_ent();yt.searchmot_onair(\''+yt.motclefs_list[y]+'\');" title="'+video_yt_translate('Cherche vid&#xE9;os avec ce mot clef')+'">'+yt.motclefs_list[y]+'</a>';
    keyword_link.push(yt.motclefs_list[y]);
    keyword_link.join(' ');
    }
//<==traitement des mots clefs

    if (entry.georss$where) {
    yt.coord_vid = entry.georss$where.gml$Point.gml$pos.$t.split(' ');
    yt.lat_vid = yt.coord_vid[0];
    yt.long_vid = yt.coord_vid[1];
    yt.lat_html.push(yt.lat_vid);
    yt.long_html.push(yt.long_vid);
    } else {yt.lat_html.push('');yt.long_html.push('');}

//==>remplissage des tableaux de donnees
     yt.id_html.push(id);
     yt.titr_html.push(yt.titr_vid);
     yt.aute_html.push(yt.auteur_vid);
     yt.desc_html.push(yt.desc_vid);
     yt.dure_html.push(yt.duree_vid);
     yt.cate_html.push(yt.cat_vid);
     yt.vote_html.push(yt.vote_vid);
     yt.moy_vote_html.push(yt.moy_vote_vid);
     yt.vu_html.push(yt.vu_vid);
     yt.modi_html.push(yt.modi_vid_d);
     yt.modi_t_html.push(yt.modi_vid_t);
     yt.ajou_html.push(yt.ajou_vid_d);
     yt.ajou_t_html.push(yt.ajou_vid_t);
     yt.comm_html.push(yt.comm_vid);
     yt.motcle_html.push(keyword_link);
//<==remplissage des tableaux de donnees

    htmlsV.push('<li class="youtubebox" onclick="loadVideo(\'', playerUrl, '\', true,'+i+',1);show_visua();">',
    '<span class="titlec">', yt.short_titr_vid, '...</span><br /><img class="youtubethumb_mycha" src="', 
    yt.thumb_vid, '" onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,\''+id+'\',2)" alt="'+yt.titr_vid+'" title="'+video_yt_translate('Voir')+' : '+yt.titr_vid+'" />', '</li>');
  }
  lay.innerHTML = '<div id="yt_bouton_search" style="float :left;"><a href="javascript:hide_search();"><img src="modules/video_yt/images/fl_b.gif" border="0" height="12px" width="12px" alt="triangle pointe en bas" /></a></div><span class="yt_soustitre">'+video_yt_translate('R&eacute;sultat de recherche pour :')+' </span>'+yt.thesearchmot+'<span class="yt_soustitre"> [</span>'+totalresult+'<span class="yt_soustitre">]</span>';

//==>pagination resultat de recherche
  if((startindex+yt.msr) <= totalresult) {
  lastvidpage = (startindex+yt.msr)-1
  }else{lastvidpage = totalresult}
   
  lay.innerHTML += '<div id="nav_bloc_rech">';
  if (startindex > 1) {
  lay.innerHTML += '<a class="w" href=" javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'vq='+addslashes(yt.thesearchmot)+'&amp;\',\'searchVideo\',\'searchscript\','+(startindex-yt.msr)+','+yt.msr+',\'\',\'flyingscript\')">['+video_yt_translate('Pr&#xE9;c&#xE9;dentes')+']</a>';
  } else {lastvidpage = yt.msr;}

  lay.innerHTML +='&nbsp;&nbsp;<span class="yt_soustitre">'+video_yt_translate('Vid&#xE9;os')+'</span> '+startindex+' <span class="yt_soustitre">'+video_yt_translate('&#xE0;')+'</span> '+lastvidpage+' <span class="yt_soustitre">'+video_yt_translate('total')+' :</span> '+totalresult+'&nbsp;&nbsp;';

  if((startindex+yt.msr) <= totalresult) {
  lay.innerHTML += '<a class="e" href=" javascript:yt.appendScriptTag(\''+yt.feed_url+'\',\'videos\',\'vq='+addslashes(yt.thesearchmot)+'&amp;\',\'searchVideo\',\'searchscript\','+(startindex+yt.msr)+','+yt.msr+',\'\',\'flyingscript\')">['+video_yt_translate('Suivantes')+']</a>';
  }
  lay.innerHTML += '</div>'
//<==pagination resultat de recherche

  htmlsV.push('</ul><br style="clear: left;"/>');
  document.getElementById('yt_search').innerHTML = htmlsV.join('');

 } else {lay.innerHTML = '<div id="yt_bouton_search" style="float :left;"><a href="javascript:hide_search();"><img src="modules/video_yt/images/fl_b.gif" border="0" height="12px" width="12px" alt="triangle pointe en bas" /></a></div><span class="yt_soustitre">'+video_yt_translate('R&eacute;sultat de recherche pour :')+' </span>'+yt.thesearchmot+'<span class="yt_soustitre"> [</span>'+totalresult+'<span class="yt_soustitre">]</span>';
 }
  show_tool();
  show_search();
}