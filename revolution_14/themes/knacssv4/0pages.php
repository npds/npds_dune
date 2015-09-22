<?php
/************************************************************************/
/* HTML5 pour NPDS                                                      */
/* ===========================                                          */
/* REALISE PAR JIRECK 2012   ( Jireck@gmail.com )                       */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

//   TITRE
//   Se Referencer au pages.php � la racine du repertoire themes

//   Blocs
//   Se Referencer au pages.php � la racine du repertoire themes

//   TinyMCE
//   Se Referencer au pages.php � la racine du repertoire themes

//   CSS
//   $PAGES['index.php']['css']="plugin.css+";
//   => Permet de charger la css des differents plugins jquery suivant 

//   JS
//   $PAGES['index.php']['js']="javascript"; OU $PAGES['index.php']['js']=array("javascript","","... ...");
//   => Permet de charger un ou plusieurs plugin Jquery (aussi bien local que distant)
//   => Le JS LOCAL DOIT IMPERATIVEMENT se trouver dans le repertoire js de votre theme (theme/votre_theme/js)
//   => Le JS DISTANT DOIT IMPERATIVEMENT se charger via http:// et l'URL ne doit pas contenir d'erreur

//   => CONSEIL : Je preconise le chargement pseudo-distant et tres flexible si le site npds est bien configurer
//   $PAGES['index.php']['js']="$nuke_url/themes/$theme/js/plugin.js";
//   OU
//   $PAGES['index.php']['js']=array("$nuke_url/themes/$theme/js/plugin.js","$nuke_url/themes/$theme/js/start.js","... ...");
// ---------------

// DEFINITION et CAST VARIABLES
settype($title,'string');
settype($post,'string');
settype($nuke_url,'string');
// ----------------------------

$PAGES['index.php']['title']="[french]Index[/french]+";
$PAGES['index.php']['blocs']="1";
$PAGES['index.php']['run']="yes";

$PAGES['index.php?op=newindex']['title']="[french]Blog[/french]+";
$PAGES['index.php?op=newindex']['blocs']="2";
$PAGES['index.php?op=newindex']['run']="yes";

$PAGES['user.php']['title']="[french]Section membre pour personnaliser le site[/french]+";
$PAGES['user.php']['blocs']="0";
$PAGES['user.php']['run']="yes";
$PAGES['user.php']['TinyMce']=1;
$PAGES['user.php']['TinyMce-theme']="short";

$PAGES['memberslist.php']['title']="[french]Liste des membres[/french]+";
$PAGES['memberslist.php']['blocs']="0";
$PAGES['memberslist.php']['run']="yes";

$PAGES['searchbb.php']['title']="[french]Recherche dans les forums[/french]+";
$PAGES['searchbb.php']['blocs']="0";
$PAGES['searchbb.php']['run']="yes";

$PAGES['article.php']['title']="$title+";
$PAGES['article.php']['blocs']="0";
$PAGES['article.php']['run']="yes";

$PAGES['submit.php']['title']="[french]Soumettre un nouvel article[/french]+";
$PAGES['submit.php']['blocs']="0";
$PAGES['submit.php']['run']="yes";
$PAGES['submit.php']['TinyMce']=1;
$PAGES['submit.php']['TinyMce-theme']="full";

$PAGES['sections.php']['title']="[french]Les articles de fond[/french]+|$title+";
$PAGES['sections.php']['blocs']="1";
$PAGES['sections.php']['run']="yes";

$PAGES['faq.php']['title']="[french]FAQs / Questions Fr&eacute;quentes[/french]+|$title+";
$PAGES['faq.php']['blocs']="0";
$PAGES['faq.php']['run']="yes";

$PAGES['download.php']['title']="[french]Les t&eacute;l&eacute;chargements[/french]+|$title+";
$PAGES['download.php']['run']="yes";

$PAGES['topics.php']['title']="[french]Les sujets actifs[/french]+";
$PAGES['topics.php']['blocs']="1";
$PAGES['topics.php']['run']="yes";

$PAGES['search.php']['title']="[french]Rechercher dans les sujets[/french][english]Search in the topics[/english][spanish]Buscar en este Temas[/spanish][german]Suche in diesem Themen[/german][chinese]&#x5728;&#x4E3B;&#x9898;&#x4E2D;&#x67E5;&#x627E;[/chinese]+";
$PAGES['search.php']['blocs']="1";
$PAGES['search.php']['run']="yes";

$PAGES['friend.php']['title']="[french]Envoyer un Article / Pr&eacute;venir un ami[/french][english]Send Story to a Friend[/english][spanish]Enviar el art&iacute;culo[/spanish][german]Artikel versenden[/german][chinese]&#x53D1;&#x9001;&#x4E00;&#x7BC7;&#x6587;&#x7AE0; / &#x901A;&#x77E5;&#x53CB;&#x4EBA;[/chinese]+|$title+";
$PAGES['friend.php']['blocs']="1";
$PAGES['friend.php']['run']="yes";

$PAGES['top.php']['title']="[french]Le top du site[/french][english]Top[/english][spanish]Top[/spanish][german]Top[/german][chinese]&#x4F18;&#x79C0;&#x7AD9;&#x70B9;[/chinese]+";
$PAGES['top.php']['blocs']="0";
$PAGES['top.php']['run']="yes";

$PAGES['stats.php']['title']="[french]Statistiques du site[/french][english]Web site statistics[/english][spanish]Estad&iacute;sticas del sitio[/spanish][german]Site-Statistik[/german][chinese]&#x884C;&#x7EDF;&#x8BA1;[/chinese]+";
$PAGES['stats.php']['blocs']="0";
$PAGES['stats.php']['run']="yes";

$PAGES['admin.php']['title']=""; // obligatoirement � vide
$PAGES['admin.php']['blocs']="-1";
$PAGES['admin.php']['run']="yes";
$PAGES['admin.php']['TinyMce']=1;
$PAGES['admin.php']['TinyMce-theme']="full";

// Extension admin.php pour REvolution
$PAGES['admin.php?op=lnl_Add_Header']['title']="notitle";
$PAGES['admin.php?op=lnl_Add_Header']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Add_Header']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Add_Header']['TinyMceRelurl']="false";
$PAGES['admin.php?op=lnl_Shw_Header']['title']="notitle";
$PAGES['admin.php?op=lnl_Shw_Header']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Shw_Header']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Shw_Header']['TinyMceRelurl']="false";
$PAGES['admin.php?op=lnl_Add_Body']['title']="notitle";
$PAGES['admin.php?op=lnl_Add_Body']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Add_Body']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Add_Body']['TinyMceRelurl']="false";
$PAGES['admin.php?op=lnl_Shw_Body']['title']="notitle";
$PAGES['admin.php?op=lnl_Shw_Body']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Shw_Body']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Shw_Body']['TinyMceRelurl']="false";
$PAGES['admin.php?op=lnl_Add_Footer']['title']="notitle";
$PAGES['admin.php?op=lnl_Add_Footer']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Add_Footer']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Add_Footer']['TinyMceRelurl']="false";
$PAGES['admin.php?op=lnl_Shw_Footer']['title']="notitle";
$PAGES['admin.php?op=lnl_Shw_Footer']['TinyMce']=1;
$PAGES['admin.php?op=lnl_Shw_Footer']['TinyMce-theme']="full";
$PAGES['admin.php?op=lnl_Shw_Footer']['TinyMceRelurl']="false";

$PAGES['forum.php']['title']="[french]Les forums de discussion[/french][english]Forums[/english][spanish]Foros de discusi&oacute;n[/spanish][german]Diskussionsforen[/german][chinese]&#x7248;&#x9762;&#x7BA1;&#x7406;[/chinese]+";
$PAGES['forum.php']['run']="yes";

$PAGES['viewforum.php']['title']="[french]Forum[/french][english]Forum[/english][spanish]Foro[/spanish][german]Forum[/german][chinese]&#x7248;&#x9762;&#x7BA1;&#x7406;[/chinese] : $title+";
$PAGES['viewforum.php']['run']="yes";

$PAGES['viewtopic.php']['title']="[french]Forum[/french][english]Forum[/english][spanish]Foro[/spanish][german]Forum[/german][chinese]&#x7248;&#x9762;&#x7BA1;&#x7406;[/chinese] : $title / $post+";
$PAGES['viewtopic.php']['run']="yes";

$PAGES['viewtopicH.php']['title']="[french]Forum[/french][english]Forum[/english][spanish]Foro[/spanish][german]Forum[/german][chinese]&#x7248;&#x9762;&#x7BA1;&#x7406;[/chinese] : $title / $post+";
$PAGES['viewtopicH.php']['run']="yes";

$PAGES['reply.php']['title']="[french]R&eacute;pondre � un post sur le forum[/french][english]Forum : reply to a post[/english][spanish]Responder a un mensaje en el foro[/spanish][german]Antwort auf einen Beitrag im Forum[/german][chinese]&#x56DE;&#x590D;&#x8BBA;&#x575B;&#x4E2D;&#x7684;&#x4E00;&#x4E2A;&#x5E16;&#x5B50;[/chinese]+";
$PAGES['reply.php']['run']="yes";

$PAGES['replyH.php']['title']="[french]R&eacute;pondre � un post sur le forum[/french][english]Forum : reply to a post[/english][spanish]Responder a un mensaje en el foro[/spanish][german]Antwort auf einen Beitrag im Forum[/german][chinese]&#x56DE;&#x590D;&#x8BBA;&#x575B;&#x4E2D;&#x7684;&#x4E00;&#x4E2A;&#x5E16;&#x5B50;[/chinese]+";
$PAGES['replyH.php']['run']="yes";

$PAGES['newtopic.php']['title']="[french]Poster un nouveau sujet[/french][english]Post a new topic[/english][spanish]Publicar nuevo tema[/spanish][german]Neues Thema er�ffnen[/german][chinese]&#x5F20;&#x8D34;&#x4E00;&#x4E2A;&#x65B0;&#x4E3B;&#x9898;[/chinese]+";
$PAGES['newtopic.php']['run']="yes";

$PAGES['topicadmin.php']['title']="[french]Gestion des forums[/french][english]Forum admin[/english][spanish]Gesti&oacute;n de los foros[/spanish][german]Management-Foren[/german][chinese]&#x5BF9;&#x8BBA;&#x575B;&#x7684;&#x7BA1;&#x7406;[/chinese]+";
$PAGES['topicadmin.php']['run']="yes";

$PAGES['editpost.php']['title']="";
$PAGES['editpost.php']['run']="yes";

$PAGES['reviews.php']['title']="[french]Les critiques[/french][english]Reviews[/english][spanish]los cr&iacute;ticos[/spanish][german]Kritik[/german][chinese]&#x8BC4;&#x8BBA;[/chinese]+";
$PAGES['reviews.php']['blocs']="1";
$PAGES['reviews.php']['run']="yes";

$PAGES['abla.php']['title']="[french]Admin Blackboard[/french][english]Admin Blackboard[/english][spanish]Admin Blackboard[/spanish][german]Admin Blackboard[/german][chinese]Admin Blackboard[/chinese]+";
$PAGES['abla.php']['run']="yes";
$PAGES['abla.php']['blocs']="1";

$PAGES['replypmsg.php']['title']="[french]R�pondre � un MP[/french][english]Reply to a MP[/english][spanish]Responder a un MP[/spanish][german]Antwort auf eine MP[/german][chinese]Reply to a MP[/chinese]+";
$PAGES['replypmsg.php']['run']="yes";
$PAGES['replypmsg.php']['blocs']="1";

$PAGES['readpmsg.php']['title']="[french]Lire un MP[/french][english]Read a MP[/english][spanish]Leer un MP[/spanish][german]Lesen Sie einen MP[/german][chinese]Read a MP[/chinese]+";
$PAGES['readpmsg.php']['run']="yes";
$PAGES['readpmsg.php']['blocs']="1";

$PAGES['map.php']['title']="[french]Plan du Site[/french][english]SiteMap[/english][spanish]Mapa del Sitio[/spanish][german]Site Map[/german][chinese]SiteMap[/chinese]";
$PAGES['map.php']['blocs']="1";
$PAGES['map.php']['run']="yes";

$PAGES['pollBooth.php']['title']="[french]Les Sondages[/french][english]Opinion poll[/english][spanish]las encuestas[/spanish][german]die Umfragen[/german][chinese]Opinion poll[/chinese]";
$PAGES['pollBooth.php']['blocs']="1";
$PAGES['pollBooth.php']['run']="yes";
// Page static
$PAGES['static.php?op=statik.txt']['title']="[french]Page de d&eacute;monstration[/french][english]Demo page[/english][spanish]Demostraci&oacute;n p&aacute;gina[/spanish][german]Demo-Seite[/german][chinese]Demo page[/chinese]+";
$PAGES['static.php?op=statik.txt']['blocs']="1";
$PAGES['static.php?op=statik.txt']['run']="yes";

// Modules
// Pour les modules il existe deux forme d'�criture :
// la syntaxe : modules.php?ModPath=links&ModStart=links ==> qui permet d'affecter un titre, un run et un type de bloc pour chaque 'sous-url' du module
// la syntaxe : mdoules.php?ModPath=links&ModStart=links* (rajout d'une * � la fin) ==> qui permet de faire la m�me chose mais en indiquant que TOUTES les pages du module seront trait�es de la m�me mani�re
$PAGES['modules.php?ModPath=links&ModStart=links*']['title']="[french]Liens et annuaires[/french][english]Web Links[/english][spanish]Enlaces y Directorios[/spanish][german]Links und Verzeichnisse[/german][chinese]&#x7F51;&#x7AD9;&#x94FE;&#x63A5;[/chinese]+|$title+";
$PAGES['modules.php?ModPath=links&ModStart=links*']['run']="yes";
$PAGES['modules.php?ModPath=links&ModStart=links*']['blocs']="0";
$PAGES['modules.php?ModPath=links&ModStart=links*']['TinyMce']=1;
$PAGES['modules.php?ModPath=links&ModStart=links*']['TinyMce-theme']="short";

$PAGES['modules.php?ModPath=links/admin&ModStart=links*']['title']="[french]Administration des liens et annuaires[/french][english]Web Links[/english][spanish]Gesti&oacute;n de enlaces y directorios[/spanish][german]Verwaltung Links und Verzeichnisse[/german][chinese]&#x7F51;&#x7AD9;&#x94FE;&#x63A5;[/chinese]+|$title+";
$PAGES['modules.php?ModPath=links/admin&ModStart=links*']['run']="yes";
$PAGES['modules.php?ModPath=links/admin&ModStart=links*']['blocs']="0";
$PAGES['modules.php?ModPath=links/admin&ModStart=links*']['TinyMce']=1;
$PAGES['modules.php?ModPath=links/admin&ModStart=links*']['TinyMce-theme']="full";

$PAGES['modules.php?ModPath=f-manager&ModStart=f-manager*']['title']="[french]Gestionnaire de fichiers[/french][english]Files manager[/english][spanish]Administrador de Ficheros[/spanish][german]Datei-Manager[/german][chinese]Files manager[/chinese]";
$PAGES['modules.php?ModPath=f-manager&ModStart=f-manager*']['run']="yes";
$PAGES['modules.php?ModPath=f-manager&ModStart=f-manager*']['blocs']="0";
$PAGES['modules.php?ModPath=f-manager&ModStart=f-manager*']['TinyMce']=1;
$PAGES['modules.php?ModPath=f-manager&ModStart=f-manager*']['TinyMce-theme']="short";

$PAGES['modules.php?ModPath=comments&ModStart=reply*']['title']="[french]Commentaires[/french][english]Comments[/english][spanish]Comentarios[/spanish][german]Kommentare[/german][chinese]Comments[/chinese]";
$PAGES['modules.php?ModPath=comments&ModStart=reply*']['run']="yes";
$PAGES['modules.php?ModPath=comments&ModStart=reply*']['blocs']="0";
$PAGES['modules.php?ModPath=comments&ModStart=reply*']['TinyMce']=0;
$PAGES['modules.php?ModPath=comments&ModStart=reply*']['TinyMce-theme']="short";

$PAGES['modules.php?ModPath=contact&ModStart=contact']['title']="[french]Nous Contacter[/french][english]Contact us[/english][spanish]Contacte con nosotros[/spanish][german]Kontakt[/german][chinese]Contact us[/chinese]";
$PAGES['modules.php?ModPath=contact&ModStart=contact']['run']="yes";
$PAGES['modules.php?ModPath=contact&ModStart=contact']['blocs']="0";

$PAGES['modules.php?ModPath=archive-stories&ModStart=archive-stories*']['title']="[french]Les Nouvelles[/french][english]News[/english][spanish]Noticias[/spanish][german]Nachrichten[/german][chinese]News[/chinese]+";
$PAGES['modules.php?ModPath=archive-stories&ModStart=archive-stories*']['run']="yes";
$PAGES['modules.php?ModPath=archive-stories&ModStart=archive-stories*']['blocs']="0";

$PAGES['modules.php?ModPath=f-manager&ModStart=pic-manager*']['title']="[french]Afficheur de fichiers multim�dia[/french][english]Multimedia files viewer[/english][spanish]Visualizaci&oacute;n de Ficheros multimedia[/spanish][german]Anzeige von Multimedia-Dateien[/german][chinese]Multimedia files viewer[/chinese]";
$PAGES['modules.php?ModPath=f-manager&ModStart=pic-manager*']['run']="yes";
$PAGES['modules.php?ModPath=f-manager&ModStart=pic-manager*']['blocs']="0";

// CSS sur fichiers particuliers car n'utilisant pas header.php
$PAGES['chatrafraich.php']['css']="chat.css-";
$PAGES['chatinput.php']['css']="chat.css-";

// Filtre sur l'URI
// $PAGES['forum=1']['title']="forum.php";
// $PAGES['forum=1']['run']="user";
?>