
CREATE TABLE access (
  access_id int(10) NOT NULL AUTO_INCREMENT,
  access_title varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (access_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO access (access_id, access_title) VALUES(1, 'User');
INSERT INTO access (access_id, access_title) VALUES(2, 'Moderator');
INSERT INTO access (access_id, access_title) VALUES(3, 'Super Moderator');

CREATE TABLE block (
  id tinyint(3) NOT NULL AUTO_INCREMENT,
  title varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  content text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO block (id, title, content) VALUES (1, 'Menu', '<ul class="list-group list-group-flush"><li class="my-2"><a href="modules.php?ModPath=archive-stories&ModStart=archive-stories">[french]Archives[/french][english]Archives[/english][chinese]&#x6863;&#x6848;&#x8D44;&#x6599;[/chinese][spanish]Archivos[/spanish][german]Archives[/german]</a></li><li class="my-2"><a href="forum.php">[french]Forums[/french][english]Forums[/english][chinese]&#x7248;&#x9762;&#x7BA1;&#x7406;[/chinese][spanish]Foros[/spanish][german]Foren[/german]</a></li><li class="my-2"><a href="sections.php">[french]Rubriques[/french][english]Sections[/english][chinese]&#x7CBE;&#x534E;&#x533A;[/chinese][spanish]Secciones[/spanish][german]Rubriken[/german]</a></li><li class="my-2"><a href="topics.php">[french]Sujets actifs[/french][english]Topics[/english][chinese]&#x4E3B;&#x9898;[/chinese][spanish]Asuntos[/spanish][german]Themen[/german]</a></li><li class="my-2"><a href="modules.php?ModPath=links&ModStart=links">[french]Liens[/french][english]Links[/english][chinese]&#x7F51;&#x9875;&#x94FE;&#x63A5;[/chinese][spanish]Enlaces web[/spanish][german]Internetlinks[/german]</a></li><li class="my-2"><a href="download.php">[french]T&eacute;l&eacute;chargements[/french][english]Downloads[/english][chinese]Downloads[/chinese][spanish]Descargas[/spanish][german]Downloads[/german]</a></li><li class="my-2"><a href="faq.php">FAQ</a></li><li class="my-2"><a href="static.php?op=statik.txt&npds=1">[french]Page statique[/french][english]Static page[/english][chinese]&#38745;&#24577;&#39029;&#38754;[/chinese][spanish]P&aacute;gina est&aacute;tica[/spanish][german]Statische Seite[/german]</a></li><li class="my-2"><a href="reviews.php">[french]Critiques[/french][english]Reviews[/english][chinese]&#x8BC4;&#x8BBA;[/chinese][spanish]Criticas[/spanish][german]Kritiken[/german]</a></li><li class="my-2"><a href="memberslist.php">[french]Annuaire[/french][english]Members List[/english][chinese]&#x4F1A;&#x5458;&#x5217;&#x8868;[/chinese][spanish]Lista de miembros[/spanish][german]Liste der registrierten Benutzer[/german]</a></li><li class="my-2"><a href="map.php">[french]Plan du site[/french][english]Site Map[/english][chinese]&#31449;&#28857;&#22320;&#22270;[/chinese][spanish]Mapa del sitio[/spanish][german]Sitemap[/german]</a></li><li class="my-2"><a href="friend.php">[french]Faire notre pub[/french][english]Recommend us[/english][chinese]&#25512;&#33616;&#25105;&#20204;[/chinese][spanish]Recomiendanos[/spanish][german]Empfehlen uns[/german]</a></li><li class="my-2"><a href="user.php">[french]Votre compte[/french][english]Your account[/english][chinese]&#x60A8;&#x7684;&#x5E10;&#x53F7;[/chinese][spanish]Su cuenta[/spanish][german]Ihr Account[/german]</a></li><li class="my-2"><a href="submit.php">[french]Nouvel article[/french][english]Submit News[/english][chinese]&#x63D0;&#x4EA4;&#x6587;&#x7AE0;&#x8BBE;&#x7F6E;[/chinese][spanish]Someter una noticia[/spanish][german]Beitrag freigeben[/german]</a></li><li class="my-2"><a href="admin.php">[french]Administration[/french][english]Administration[/english][chinese]&#31649;&#29702;[/chinese][spanish]Administraci&oacute;n[/spanish][german]Verwaltung[/german]</a></li></ul>');
INSERT INTO block (id, title, content) VALUES (2, 'Administration', '<ul><li><a href="admin.php"><i class="fas fa-sign-in-alt fa-2x align-middle"></i> Administration</a></li><li><a href="admin.php?op=logout" class=" text-danger"><i class="fas fa-sign-out-alt fa-2x align-middle"></i> [french]D&eacute;connexion[/french][english]Logout[/english][chinese]&#30331;&#20986;[/chinese][spanish]Cerrar sesi&oacute;n[/spanish][german]Ausloggen[/german]</a></li></ul>');

CREATE TABLE appli_log (
  al_id int(11) NOT NULL DEFAULT '0',
  al_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  al_subid int(11) NOT NULL DEFAULT '0',
  al_date datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  al_uid int(11) NOT NULL DEFAULT '0',
  al_data text COLLATE utf8mb4_unicode_ci,
  al_ip varchar(54) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  al_hostname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  KEY al_id (al_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO appli_log VALUES (1, 'Poll', 2, '2012-07-15 13:35:32', 1, '2', '1.1.76.115', '');

CREATE TABLE authors (
  aid varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  name varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  url varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  pwd varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  hashkey tinyint(1) NOT NULL DEFAULT '0',
  counter int(11) NOT NULL DEFAULT '0',
  radminsuper tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (aid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO authors (aid, name, url, email, pwd, hashkey, counter, radminsuper) VALUES ('Root', 'Root', '', 'root@npds.org', 'd.8V.L9nSMMvE', 0, 0, 1);

CREATE TABLE autonews (
  anid int(11) NOT NULL AUTO_INCREMENT,
  catid int(11) NOT NULL DEFAULT '0',
  aid varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  time varchar(19) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  hometext text COLLATE utf8mb4_unicode_ci NOT NULL,
  bodytext mediumtext COLLATE utf8mb4_unicode_ci,
  topic int(3) NOT NULL DEFAULT '1',
  informant varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  notes text COLLATE utf8mb4_unicode_ci NOT NULL,
  ihome int(1) NOT NULL DEFAULT '0',
  date_debval datetime DEFAULT NULL,
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (anid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE banner (
  bid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  imptotal int(11) NOT NULL DEFAULT '0',
  impmade int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  imageurl varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  clickurl varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  userlevel int(1) NOT NULL DEFAULT '0',
  date datetime DEFAULT NULL,
  PRIMARY KEY (bid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bannerclient (
  cid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  contact varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  login varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  passwd varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  extrainfo text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (cid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bannerfinish (
  bid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  impressions int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  datestart datetime DEFAULT NULL,
  dateend datetime DEFAULT NULL,
  PRIMARY KEY (bid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE catagories (
  cat_id int(10) NOT NULL AUTO_INCREMENT,
  cat_title text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (cat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO catagories VALUES (1, 'Demo');

CREATE TABLE chatbox (
  username text COLLATE utf8mb4_unicode_ci,
  ip varchar(54) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  message text COLLATE utf8mb4_unicode_ci,
  date int(15) NOT NULL DEFAULT '0',
  id int(10) DEFAULT '0',
  dbname tinyint(4) DEFAULT '0',
  PRIMARY KEY (date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE compatsujet (
  id1 varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  id2 int(30) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE config (
  allow_html int(2) DEFAULT NULL,
  allow_bbcode int(2) DEFAULT NULL,
  allow_sig int(2) DEFAULT NULL,
  posts_per_page int(10) DEFAULT NULL,
  hot_threshold int(10) DEFAULT NULL,
  topics_per_page int(10) DEFAULT NULL,
  allow_upload_forum int(2) unsigned NOT NULL DEFAULT '0',
  allow_forum_hide int(2) unsigned NOT NULL DEFAULT '0',
  upload_table varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'forum_attachments',
  rank1 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  rank2 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  rank3 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  rank4 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  rank5 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  anti_flood char(3) DEFAULT NULL,
  solved int(2) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO config VALUES (1, 1, 1, 10, 10, 10, 0, 0, 'forum_attachments', NULL, NULL, NULL, NULL, NULL, NULL, 0);

CREATE TABLE counter (
  id_stat int(10) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  var varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  count int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_stat)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO counter (id_stat, type, var, count) VALUES(1, 'total', 'hits', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(2, 'browser', 'WebTV', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(3, 'browser', 'Lynx', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(4, 'browser', 'MSIE', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(5, 'browser', 'Opera', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(6, 'browser', 'Konqueror', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(7, 'browser', 'Netscape', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(8, 'browser', 'Chrome', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(9, 'browser', 'Safari', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(10, 'browser', 'Bot', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(11, 'browser', 'Other', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(12, 'os', 'Windows', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(13, 'os', 'Linux', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(14, 'os', 'Mac', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(15, 'os', 'FreeBSD', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(16, 'os', 'SunOS', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(17, 'os', 'IRIX', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(18, 'os', 'BeOS', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(19, 'os', 'OS/2', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(20, 'os', 'AIX', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(21, 'os', 'Other', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(25, 'os', 'Android', 0);
INSERT INTO counter (id_stat, type, var, count) VALUES(22, 'os', 'iOS', 0);

CREATE TABLE downloads (
  did int(10) NOT NULL AUTO_INCREMENT,
  dcounter int(10) NOT NULL DEFAULT '0',
  durl varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  dfilename varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  dfilesize bigint(15) unsigned DEFAULT NULL,
  ddate date NOT NULL DEFAULT '1000-01-01',
  dweb varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  duser varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  dver varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  dcategory varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  ddescription text COLLATE utf8mb4_unicode_ci,
  perms varchar(480) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (did)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE droits (
  d_aut_aid varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'id administrateur',
  d_fon_fid tinyint(3) unsigned NOT NULL COMMENT 'id fonction',
  d_droits varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dune_proto';

CREATE TABLE ephem (
  eid int(11) NOT NULL AUTO_INCREMENT,
  did int(2) NOT NULL DEFAULT '0',
  mid int(2) NOT NULL DEFAULT '0',
  yid int(4) NOT NULL DEFAULT '0',
  content text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (eid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE faqanswer (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_cat tinyint(4) DEFAULT NULL,
  question varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  answer text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE faqcategories (
  id_cat tinyint(3) NOT NULL AUTO_INCREMENT,
  categories varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id_cat)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE fonctions (
  fid mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id unique auto incrémenté',
  fnom varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  fdroits1 tinyint(3) unsigned DEFAULT NULL,
  fdroits1_descr varchar(40) COLLATE utf8mb4_unicode_ci,
  finterface tinyint(1) unsigned NOT NULL COMMENT '1 ou 0 : la fonction dispose ou non d''une interface',
  fetat tinyint(1) NOT NULL COMMENT '0 ou 1  9 : non active ou installé, installé',
  fretour text COLLATE utf8mb4_unicode_ci COMMENT 'utiliser par les fonctions de categorie Alerte : nombre, ou ',
  fretour_h text COLLATE utf8mb4_unicode_ci NOT NULL,
  fnom_affich text COLLATE utf8mb4_unicode_ci NOT NULL,
  ficone varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  furlscript varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'attribut et contenu  de balise A : href="xxx", onclick="xxx"  etc',
  fcategorie tinyint(3) unsigned NOT NULL,
  fcategorie_nom varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  fordre tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (fid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dune_proto';

INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(1, 'edito', 1, '', 1, 1, '', '', 'Edito', 'edito', 'href="admin.php?op=Edito"', 1, 'Contenu', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(2, 'adminStory', 2, '', 1, 1, '', '', 'Nouvel Article', 'postnew', 'href="admin.php?op=adminStory"', 1, 'Contenu', 1);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(3, 'sections', 3, '', 1, 1, '', '', 'Rubriques', 'sections', 'href="admin.php?op=sections"', 1, 'Contenu', 2);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(4, 'topicsmanager', 4, '', 1, 1, '', '', 'Gestion des Sujets', 'topicsman', 'href="admin.php?op=topicsmanager"', 1, 'Contenu', 3);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(5, 'links', 5, '', 1, 1, '', '', 'Liens Web', 'links', 'href="admin.php?op=links"', 1, 'Contenu', 5);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(6, 'FaqAdmin', 6, '', 1, 1, '1', '', 'FAQ', 'faq', 'href="admin.php?op=FaqAdmin"', 1, 'Contenu', 6);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(7, 'Ephemerids', 7, '', 1, 1, '1', '', 'Ephémérides', 'ephem', 'href="admin.php?op=Ephemerids"', 1, 'Contenu', 7);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(8, 'HeadlinesAdmin', 8, '', 1, 1, '', '', 'News externes', 'headlines', 'href="admin.php?op=HeadlinesAdmin"', 1, 'Contenu', 8);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(9, 'DownloadAdmin', 9, '', 1, 1, '', '', 'Téléchargements', 'download', 'href="admin.php?op=DownloadAdmin"', 1, 'Contenu', 9);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(10, 'mod_users', 10, '', 1, 1, '', '', 'Utilisateurs', 'users', 'href="admin.php?op=mod_users"', 2, 'Utilisateurs', 1);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(11, 'groupes', 11, '', 1, 1, '', '', 'Groupes', 'groupes', 'href="admin.php?op=groupes"', 2, 'Utilisateurs', 2);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(12, 'mod_authors', 12, '', 1, 1, '', '', 'Administrateurs', 'authors', 'href="admin.php?op=mod_authors"', 2, 'Utilisateurs', 3);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(13, 'MaintForumAdmin', 13, '', 1, 1, '', '', 'Maintenance Forums', 'forum', 'href="admin.php?op=MaintForumAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(14, 'ForumConfigAdmin', 14, '', 1, 1, '', '', 'Configuration Forums', 'forum', 'href="admin.php?op=ForumConfigAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(15, 'ForumAdmin', 15, '', 1, 1, '', '', 'Edition Forums', 'forum', 'href="admin.php?op=ForumAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(16, 'lnl', 16, '', 1, 1, '', '', 'Lettre D''info', 'lnl', 'href="admin.php?op=lnl"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(17, 'email_user', 17, '', 1, 1, '', '', 'Message Interne', 'email_user', 'href="admin.php?op=email_user"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(18, 'BannersAdmin', 18, '', 1, 1, '', '', 'Bannières', 'banner', 'href="admin.php?op=BannersAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(19, 'create', 19, '', 1, 1, '', '', 'Sondages', 'newpoll', 'href="admin.php?op=create"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(20, 'reviews', 20, '', 1, 1, '', '', 'Critiques', 'reviews', 'href="admin.php?op=reviews"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(21, 'hreferer', 21, '', 1, 1, '', '', 'Sites Référents', 'referer', 'href="admin.php?op=hreferer"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(22, 'blocks', 22, '', 1, 1, '', '', 'Blocs', 'block', 'href="admin.php?op=blocks"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(23, 'mblock', 23, '', 1, 1, '', '', 'Bloc Principal', 'blockmain', 'href="admin.php?op=mblock"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(24, 'ablock', 24, '', 1, 1, '', '', 'Bloc Administration', 'blockadm', 'href="admin.php?op=ablock"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(25, 'Configure', 25, '', 1, 1, '', '', 'Préférences', 'preferences', 'href="admin.php?op=Configure"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(26, 'ConfigFiles', 26, '', 1, 1, '', '', 'Fichiers configurations', 'preferences', 'href="admin.php?op=ConfigFiles"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(27, 'FileManager', 27, '', 1, 1, '', '', 'Gestionnaire Fichiers', 'filemanager', 'href="admin.php?op=FileManager"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(28, 'supercache', 28, '', 1, 1, '', '', 'SuperCache', 'overload', 'href="admin.php?op=supercache"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(29, 'OptimySQL', 29, '', 1, 1, '', '', 'OptimySQL', 'optimysql', 'href="admin.php?op=OptimySQL"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(30, 'SavemySQL', 30, '', 1, 1, '', '', 'SavemySQL', 'savemysql', 'href="admin.php?op=SavemySQL"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(31, 'MetaTagAdmin', 31, '', 1, 1, '', '', 'MétaTAGs', 'metatags', 'href="admin.php?op=MetaTagAdmin"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(32, 'MetaLangAdmin', 32, '', 1, 1, '', '', 'META-LANG', 'metalang', 'href="admin.php?op=Meta-LangAdmin"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(33, 'setban', 33, '', 1, 1, '', '', 'IP', 'ipban', 'href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ipban&amp;ModStart=setban"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(34, 'session_log', 34, '', 1, 1, '', '', 'Logs', 'logs', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=session-log&ModStart=session-log"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(35, 'reviews', 20, '', 1, 1, '0', 'Critique en atttente de validation.', 'Critiques', 'reviews', 'href="admin.php?op=reviews"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(36, 'mes_npds_versus', 36, '', 1, 1, '', 'Une nouvelle version est disponible ! Cliquez pour acc&#xE9;der &#xE0; la zone de t&#xE9;l&#xE9;chargement de NPDS.', '', 'message_npds', '', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(37, 'autoStory', 2, '', 1, 1, '1', 'articles sont programm&eacute;s pour la publication.', 'Auto-Articles', 'autonews', 'href="admin.php?op=autoStory"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(38, 'submissions', 2, '', 1, 1, '10', 'Article en attente de validation !', 'Articles', 'submissions', 'href="admin.php?op=submissions"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(39, 'hreferer_al', 21, '', 1, 1, '!!!', 'Limite des r&#xE9;f&#xE9;rants atteinte : pensez &#xE0; archiver vos r&#xE9;f&#xE9;rants.', 'Sites Référents', 'referer', 'href="admin.php?op=hreferer"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(40, 'abla', 40, '', 1, 1, '', '', 'Blackboard', 'abla', 'href="admin.php?op=abla"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(41, 'newlink', 5, '', 1, 1, '1', 'Lien &#xE0; valider', 'Lien', 'links', 'href="admin.php?op=links"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(42, 'brokenlink', 5, '', 1, 1, '6', 'Lien rompu &#xE0; valider', 'Lien rompu', 'links', 'href="admin.php?op=LinksListBrokenLinks"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(43, 'archive-stories', 43, '', 1, 1, '', '', 'Archives articles', 'archive-stories', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=archive-stories&ModStart=admin/archive-stories_set"', 1, 'Contenu', 4);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(44, 'mod_users', 10, '', 1, 1, '', 'Utilisateur en attente de validation !', 'Utilisateurs', 'users', 'href="admin.php?op=nonallowed_users"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(45, 'upConfigure', 45, '', 1, 1, '', '', 'Upload', 'upload', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=upload&ModStart=admin/upload"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(46, 'groupe_member_ask', 11, '', 1, 0, '', 'Utilisateur en attente de groupe !', 'Groupes', 'groupes', 'href="admin.php?op=groupe_member_ask"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(49, 'npds_twi', 49, '', 1, 1, '', '', 'Npds_Twitter', 'npds_twi', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=npds_twi&ModStart=admin/npds_twi_set"', 6, 'Modules', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(50, 'publications', 3, '', 1, 1, '', 'Publication(s) en attente de validation', 'Rubriques', 'sections', 'href="admin.php?op=sections#publications en attente"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(51, 'modules', 51, '', 1, 1, '', '', 'Gestion modules', 'modules', 'href="admin.php?op=modules"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(74, 'reseaux-sociaux', 74, '', 1, 1, '', '', 'Réseaux sociaux', 'reseaux-sociaux', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=reseaux-sociaux&ModStart=admin/reseaux-sociaux_set"', 2, 'Utilisateurs', 4);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(75, 'geoloc', 75, '', 1, 1, '', '', 'geoloc', 'geoloc', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=geoloc&ModStart=admin/geoloc_set"', 6, 'Modules', 0);

CREATE TABLE forums (
  forum_id int(10) NOT NULL AUTO_INCREMENT,
  forum_name varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  forum_desc text COLLATE utf8mb4_unicode_ci,
  forum_access int(10) DEFAULT '1',
  forum_moderator text COLLATE utf8mb4_unicode_ci,
  cat_id int(10) DEFAULT NULL,
  forum_type int(10) DEFAULT '0',
  forum_pass varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  arbre tinyint(1) unsigned NOT NULL DEFAULT '0',
  attachement tinyint(1) unsigned NOT NULL DEFAULT '0',
  forum_index int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO forums VALUES (1, 'Demo', '', 0, '2', 1, 0, '', 0, 0, 0);
INSERT INTO forums VALUES (2, 'Arbre', 'un forum &agrave; l''ancienne forme', 0, '2', 1, 0, '', 1, 0, 0);

CREATE TABLE forumtopics (
  topic_id int(10) NOT NULL AUTO_INCREMENT,
  topic_title varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  topic_poster int(10) DEFAULT NULL,
  topic_time datetime DEFAULT NULL,
  topic_views int(10) NOT NULL DEFAULT '0',
  forum_id int(10) DEFAULT NULL,
  topic_status int(10) NOT NULL DEFAULT '0',
  topic_notify int(2) DEFAULT '0',
  current_poster int(10) DEFAULT NULL,
  topic_first tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (topic_id),
  KEY forum_id (forum_id),
  KEY topic_first (topic_first,topic_time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO forumtopics VALUES (1, 'Demo', 2, '2012-03-05 22:36:00', 27, 1, 0, 0, 2, 1);
INSERT INTO forumtopics VALUES (2, 'Message 1', 1, '2013-05-14 22:55:00', 8, 2, 0, 0, 1, 1);

CREATE TABLE forum_attachments (
  att_id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL DEFAULT '0',
  topic_id int(11) NOT NULL DEFAULT '0',
  forum_id int(11) NOT NULL DEFAULT '0',
  unixdate int(11) NOT NULL DEFAULT '0',
  att_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  att_type varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  att_size int(11) NOT NULL DEFAULT '0',
  att_path varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  inline char(1) NOT NULL DEFAULT '',
  apli varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  compteur int(11) NOT NULL DEFAULT '0',
  visible tinyint(1) NOT NULL DEFAULT '0',
  KEY att_id (att_id),
  KEY post_id (post_id),
  KEY topic_id (topic_id),
  KEY apli (apli),
  KEY visible (visible),
  KEY forum_id (forum_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE forum_read (
  rid int(11) NOT NULL AUTO_INCREMENT,
  forum_id int(10) NOT NULL DEFAULT '0',
  topicid int(3) NOT NULL DEFAULT '0',
  uid int(11) NOT NULL DEFAULT '0',
  last_read int(15) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (rid),
  KEY topicid (topicid),
  KEY forum_id (forum_id),
  KEY uid (uid),
  KEY forum_read_mcl (forum_id,uid,topicid,status)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO forum_read VALUES (1, 2, 2, 2, 1383416155, 1);
INSERT INTO forum_read VALUES (2, 1, 1, 2, 1383418761, 1);

CREATE TABLE groupes (
  groupe_id int(3) DEFAULT '0',
  groupe_name varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  groupe_description text COLLATE utf8mb4_unicode_ci NOT NULL,
  groupe_forum int(1) unsigned NOT NULL DEFAULT '0',
  groupe_mns int(1) unsigned NOT NULL DEFAULT '0',
  groupe_chat int(1) unsigned NOT NULL DEFAULT '0',
  groupe_blocnote int(1) unsigned NOT NULL DEFAULT '0',
  groupe_pad int(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY groupe_id (groupe_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE headlines (
  hid int(11) NOT NULL AUTO_INCREMENT,
  sitename varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  headlinesurl varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (hid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO headlines VALUES (1, 'NPDS', 'https://www.npds.org', 'https://www.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (2, 'Github', 'https://github.com/npds/npds_dune', 'https://github.com/npds/npds_dune/commits/master.atom', 0);

CREATE TABLE lblocks (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  content text COLLATE utf8mb4_unicode_ci,
  member varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  Lindex tinyint(4) NOT NULL DEFAULT '0',
  cache mediumint(8) unsigned NOT NULL DEFAULT '0',
  actif smallint(5) unsigned NOT NULL DEFAULT '1',
  css tinyint(1) NOT NULL DEFAULT '0',
  aide mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id),
  KEY Lindex (Lindex)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lblocks VALUES (1, '[french]Un Bloc ...[/french][english]One Block ...[/english][chinese]&#x4E00;&#x5757;...[/chinese][spanish]Un Bloque...[/spanish][german]Ein Block[/german]', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO lblocks VALUES (2, '[french]Menu[/french][english]Menu[/english][chinese]&#x83DC;&#x5355;[/chinese][spanish]Men&#xFA;[/spanish][german]Men&#xFC;[/german]', 'function#mainblock', '0', 1, 86400, 1, 0, 'Ce menu contient presque toutes les fonctions de base disponibles dans NPDS');
INSERT INTO lblocks VALUES (3, '[french]Message &#xE0; un membre[/french][english]Message to Member[/english][chinese]&#x7ED9;&#x4F1A;&#x5458;&#x53D1;&#x9001;&#x7684;&#x6D88;&#x606F;[/chinese][spanish]Mensaje a un miembro[/spanish][german]Nachricht an einen Benutzer[/german]', 'function#instant_members_message', '0', 4, 0, 1, 0, '');
INSERT INTO lblocks VALUES (4, 'Chat Box', 'function#makeChatBox\r\nparams#chat_tous', '0', 2, 10, 1, 0, '');
INSERT INTO lblocks VALUES (5, '[french]Forums Infos[/french][english]Forums Infos[/english][chinese]&#x8BBA;&#x575B;&#x4FE1;&#x606F;[/chinese][spanish]Foros infos[/spanish][german]Foreninfos[/german]', 'function#RecentForumPosts\r\nparams#Forums Infos,15,0,false,10,false,-:\r\n', '0', 5, 60, 1, 0, '');
INSERT INTO lblocks VALUES (6, '[french]Les plus t&eacute;l&eacute;charg&eacute;s[/french][english]Most downloaded[/english][chinese]&#x4E2A;&#x88AB;&#x4E0B;&#x8F7D;&#x6700;&#x591A;&#x7684;&#x6587;&#x4EF6;[/chinese][spanish]Los mas descargados[/spanish][german]Am meisten heruntergeladen[/german]', 'function#topdownload', '0', 6, 3600, 0, 0, '');
INSERT INTO lblocks VALUES (7, '[french]Administration[/french][english]Administration[/english][chinese]&#x7F51;&#x7AD9;&#x6CBB;&#x7406;[/chinese][spanish]Administraci&#xF3;n[/spanish][german]Verwaltung[/german]', 'function#adminblock', '0', 3, 0, 1, 0, '');
INSERT INTO lblocks VALUES (8, '[french]Eph&eacute;m&eacute;rides[/french][english]Ephemerids[/english][chinese]&#x5386;&#x53F2;&#x4E0A;&#x7684;&#x4ECA;&#x5929;[/chinese][spanish]Efem&#xE9;rides[/spanish][german]Ephemeriden[/german]', 'function#ephemblock', '0', 7, 28800, 0, 0, '');
INSERT INTO lblocks VALUES (9, '[french]Grands Titres de sites de News[/french][english]headlines[/english][chinese]&#x65B0;&#x95FB;&#x7AD9;&#x70B9;&#x5934;&#x6761;&#x6807;&#x9898;[/chinese][spanish]Grandes titulos[/spanish][german]Informations Kan&#xE4;le[/german]', 'function#headlines', '0', 9, 3600, 0, 0, '');
INSERT INTO lblocks VALUES (10, '[french]Activit&eacute; du Site[/french][english]Website Activity[/english][chinese]&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x6D3B;&#x52A8;&#x4FE1;&#x606F;[/chinese][spanish]Actividad del sitio web[/spanish][german]T&#xE4;tigkeit auf der Website[/german]', 'function#Site_Activ', '0', 8, 10, 1, 0, '');
INSERT INTO lblocks VALUES (11, '[french]Sondage[/french][english]Survey[/english][chinese]&#x8C03;&#x67E5;[/chinese][spanish]Encuesta[/spanish][german]Umfrage[/german]', 'function#pollNewest', '0', 1, 60, 1, 0, '');
INSERT INTO lblocks VALUES (12, '[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Landkarte[/german]', 'include#modules/geoloc/geoloc_bloc.php', '0', 0, 86400, 0, 0, '');

CREATE TABLE links_categories (
  cid int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  cdescription text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (cid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO links_categories VALUES (1, 'Mod&egrave;le', '');

CREATE TABLE links_editorials (
  linkid int(11) NOT NULL DEFAULT '0',
  adminid varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  editorialtimestamp datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  editorialtext text COLLATE utf8mb4_unicode_ci NOT NULL,
  editorialtitle varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (linkid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE links_links (
  lid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  date datetime DEFAULT NULL,
  name varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  hits int(11) NOT NULL DEFAULT '0',
  submitter varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  linkratingsummary double(6,4) NOT NULL DEFAULT '0.0000',
  totalvotes int(11) NOT NULL DEFAULT '0',
  totalcomments int(11) NOT NULL DEFAULT '0',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE links_modrequest (
  requestid int(11) NOT NULL AUTO_INCREMENT,
  lid int(11) NOT NULL DEFAULT '0',
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  modifysubmitter varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  brokenlink int(3) NOT NULL DEFAULT '0',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (requestid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE links_newlink (
  lid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  name varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  submitter varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE links_subcategories (
  sid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  title varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (sid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lnl_body (
  ref int(11) NOT NULL AUTO_INCREMENT,
  html char(1) NOT NULL DEFAULT '1',
  text text COLLATE utf8mb4_unicode_ci,
  status char(3) NOT NULL DEFAULT 'stb',
  PRIMARY KEY (ref)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lnl_head_foot (
  ref int(11) NOT NULL AUTO_INCREMENT,
  type char(3) NOT NULL DEFAULT '',
  html char(1) NOT NULL DEFAULT '1',
  text text COLLATE utf8mb4_unicode_ci,
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (ref)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lnl_outside_users (
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  host_name varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  date datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (email(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lnl_send (
  ref int(11) NOT NULL AUTO_INCREMENT,
  header int(11) NOT NULL DEFAULT '0',
  body int(11) NOT NULL DEFAULT '0',
  footer int(11) NOT NULL DEFAULT '0',
  number_send int(11) NOT NULL DEFAULT '0',
  type_send char(3) NOT NULL DEFAULT 'ALL',
  date datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (ref)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE metalang (
  def varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  content text COLLATE utf8mb4_unicode_ci NOT NULL,
  type_meta varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mot',
  type_uri char(1) NOT NULL DEFAULT '-',
  uri varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  obligatoire char(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (def),
  KEY type_meta (type_meta)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('Dev', 'Developpeur', 'mot', '-', NULL, NULL, '0');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('NPDS', '<a href="http://www.npds.org" target="_blank" title="www.npds.org">NPDS</a>', 'mot', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-)', '$cmd=MM_img("forum/smilies/icon_smile.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-]', '$cmd=MM_img("forum/smilies/icon_smile.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(';-)', '$cmd=MM_img("forum/smilies/icon_wink.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(';-]', '$cmd=MM_img("forum/smilies/icon_wink.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-(', '$cmd=MM_img("forum/smilies/icon_frown.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-[', '$cmd=MM_img("forum/smilies/icon_frown.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('8-)', '$cmd=MM_img("forum/smilies/icon_cool.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('8-]', '$cmd=MM_img("forum/smilies/icon_cool.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-P', '$cmd=MM_img("forum/smilies/icon_razz.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-D', '$cmd=MM_img("forum/smilies/icon_biggrin.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':=!', '$cmd=MM_img("forum/smilies/yaisse.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':b', '$cmd=MM_img("forum/smilies/icon_tongue.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':D', '$cmd=MM_img("forum/smilies/icon_grin.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':#', '$cmd=MM_img("forum/smilies/icon_ohwell.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-o', '$cmd=MM_img("forum/smilies/icon_eek.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-?', '$cmd=MM_img("forum/smilies/icon_confused.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':-|', '$cmd=MM_img("forum/smilies/icon_mad.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':|', '$cmd=MM_img("forum/smilies/icon_mad2.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES(':paf', '$cmd=MM_img("forum/smilies/pafmur.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('dateL', '$cmd=date("d/m/Y");', 'meta', '-', NULL, '[french]Date longue JJ/MM/YYYY[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('heureC', '$cmd=date("H:i");', 'meta', '-', NULL, '[french]Heure courte HH:MM[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('heureL', '$cmd=date("H:i:s");', 'meta', '-', NULL, '[french]Heure longue HH:MM:SS[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('dateC', '$cmd=date("d/m/y");', 'meta', '-', NULL, '[french]Date longue JJ/MM[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!a/!', '&#92;', 'meta', '-', NULL, '[french]anti-slash[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sc_infos_else!', '&nbsp;', 'meta', '-', NULL, '[french]affiche les informations de SuperCache[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sc_infos!', '$cmd=SC_infos();', 'meta', '-', NULL, '[french]affiche les informations de SuperCache[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!slogan!', '$cmd=$GLOBALS[''slogan''];', 'meta', '-', NULL, '[french]variable global $slogan[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!theme!', '$cmd=$GLOBALS[''theme''];', 'meta', '-', NULL, '[french]variable global $theme[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sitename!', '$cmd=$GLOBALS[''sitename''];', 'meta', '-', NULL, '[french]variable global $sitename[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('Scalcul', 'function MM_Scalcul($opex,$premier,$deuxieme) {\n   if ($opex=="+") {$tmp=$premier+$deuxieme;}\n   if ($opex=="-") {$tmp=$premier-$deuxieme;}\n   if ($opex=="*") {$tmp=$premier*$deuxieme;}\n   if ($opex=="/") {\n      if ($deuxieme==0) {\n         $tmp="Division by zero !";\n      } else {\n         $tmp=$premier/$deuxieme;\n      }\n   }\n   return ($tmp);\n}', 'meta', '-', NULL, '[french]Retourne la valeur du calcul : syntaxe Scalcul(op,nombre,nombre) ou op peut &ecirc;tre : + - * /[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!anti_spam!', 'function MM_anti_spam ($arg) {\n   return("<a href=\\"mailto:".anti_spam($arg, 1)."\\" target=\\"_blank\\">".anti_spam($arg, 0)."</a>");\n}', 'meta', '-', NULL, '[french]Encode un email et cr&eacute;e un &lta href=mailto ...&gtEmail&lt/a&gt[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!msg_foot!', 'function MM_msg_foot () {\n   global $foot1, $foot2, $foot3, $foot4;\n\n   if ($foot1) $MT_foot=stripslashes($foot1)."<br />";\n   if ($foot2) $MT_foot.=stripslashes($foot2)."<br />";\n   if ($foot3) $MT_foot.=stripslashes($foot3)."<br />";\n   if ($foot4) $MT_foot.=stripslashes($foot4);\n   return (aff_langue($MT_foot));\n}', 'meta', '-', NULL, '[french]Gestion des messages du footer du theme (les 4 pieds de page dans Admin / Pr&eacute;f&eacute;rences)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!date!', 'function MM_date () {return formatTimes(time(), IntlDateFormatter::FULL, IntlDateFormatter::MEDIUM);\r\n}', 'meta', '-', NULL, '[french]Date du jour[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!banner!', 'function MM_banner () {\n   global $banners, $hlpfile;\n\n   if (($banners) and (!$hlpfile)) {\n      ob_start();\n      include("banners.php");\n      $MT_banner=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $MT_banner="";\n   }\n   return ($MT_banner);\n}', 'meta', '-', NULL, '[french]Syst&egrave;me de banni&egrave;re[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!search_topics!', 'function MM_search_topics() {\n   global $NPDS_Prefix;\n\n   $MT_search_topics="<form action=\\"search.php\\" method=\\"post\\"><label class=\\"col-form-label\\">".translate("Sujets")." </label>";\n   $MT_search_topics.="<select class=\\"form-select\\" name=\\"topic\\"onChange=''submit()''>" ;\n   $MT_search_topics.="<option value=\\"\\">".translate("Tous les sujets")."</option>\\n";\n\n   $rowQ=Q_select("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext", 86400);\n   foreach($rowQ as $myrow) {\n     $MT_search_topics.="<option value=\\"".$myrow[''topicid'']."\\">".aff_langue($myrow[''topictext''])."</option>\\n";\n   }\n   $MT_search_topics.="</select></form>";\n   return ($MT_search_topics);\n}', 'meta', '-', NULL, '[french]Liste des Topic => Moteur de recherche interne (Combo)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!search!', 'function MM_search() {\n   $MT_search="<form action=\\"search.php\\" method=\\"post\\"><label>".translate("Recherche")."</label>\n   <input class=\\"form-control\\" type=\\"text\\" name=\\"query\\" size=\\"10\\"></form>";\n   return ($MT_search);\n}', 'meta', '-', NULL, '[french]Ligne de saisie => Moteur de recherche[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!member!', 'function MM_member() {\n   global $cookie, $anonymous;\n   $username = (isset($cookie)) ? $cookie[1] : $anonymous;\n   ob_start();\n      Mess_Check_Mail($username);\n      $MT_member=ob_get_contents();\n   ob_end_clean();\n   return $MT_member;\n}', 'meta', '-', NULL, '[french]Ligne Anonyme / membre gestion du compte / Message Interne (MI)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!nb_online!', 'function MM_nb_online() {\n   list($MT_nb_online, $MT_whoim)=Who_Online();\n   return ($MT_nb_online);\n}', 'meta', '-', NULL, '[french]Nombre de session active[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!whoim!', 'function MM_whoim() {\r\n   list($MT_nb_online, $MT_whoim)=Who_Online();\r\n   return ($MT_whoim);\r\n}', 'meta', '-', NULL, '[french]Affiche Qui est en ligne ? + message de bienvenue[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!membre_nom!', 'function MM_membre_nom() {\n   global $NPDS_Prefix,$cookie;\n\n   if(isset($cookie[1])) {\n\n      $uname=arg_filter($cookie[1]);\n      $MT_name="";\n      $rowQ = Q_select("SELECT name FROM ".$NPDS_Prefix."users WHERE uname=''$uname''", 3600);\n      $myrow = $rowQ[0];\n      $MT_name=$myrow[''name''];\n      return ($MT_name);\n   }\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du nom du membre ou rien[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!membre_pseudo!', 'function MM_membre_pseudo() {\n   global $cookie;\n\n   return ($cookie[1]);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du pseudo du membre ou rien[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('blocID', 'function MM_blocID($arg) {\n  return(@oneblock(substr($arg,1), substr($arg,0,1)."B"));\n}', 'meta', '-', NULL, '[french]Fabrique un bloc R (droite) ou L (gauche) en s''appuyant sur l''ID (voir gestionnaire de blocs) pour incorporation / syntaxe : blocID(R1) ou blocID(L2)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!block!', 'function MM_block($arg) {\n   return (meta_lang("blocID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de blocID()[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('leftblocs', 'function MM_leftblocs($arg) {\n   ob_start();\n      leftblocks($arg);\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de gauche[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('rightblocs', 'function MM_rightblocs($arg) {\n   ob_start();\n      rightblocks($arg);\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de droite[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('articleID', 'function MM_articleID($arg) {\n   global $NPDS_Prefix;\n   global $nuke_url;\n\n   $arg = arg_filter($arg);\n   $rowQ = Q_select("SELECT title FROM ".$NPDS_Prefix."stories WHERE sid=''$arg''", 3600);\n   $myrow = $rowQ[0];\n   return ("<a href=\\"$nuke_url/article.php?sid=$arg\\">".$myrow[''title'']."</a>");\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du titre et fabrication d''une url pointant sur l''article (ID)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!article!', 'function MM_article($arg) {\n   return (meta_lang("articleID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias d''articleID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('article_completID', 'function MM_article_completID($arg) {\n   if ($arg>0) {\n      $story_limit=1;\n      $news_tab=prepa_aff_news("article",$arg,"");\n   } else {\n      $news_tab=prepa_aff_news("index","","");\n      $story_limit=abs($arg)+1;\n   }\n   $aid=unserialize($news_tab[$story_limit][''aid'']);\n   $informant=unserialize($news_tab[$story_limit][''informant'']);\n   $datetime=unserialize($news_tab[$story_limit][''datetime'']);\n   $title=unserialize($news_tab[$story_limit][''title'']);\n   $counter=unserialize($news_tab[$story_limit][''counter'']);\n   $topic=unserialize($news_tab[$story_limit][''topic'']);\n   $hometext=unserialize($news_tab[$story_limit][''hometext'']);\n   $notes=unserialize($news_tab[$story_limit][''notes'']);\n   $morelink=unserialize($news_tab[$story_limit][''morelink'']);\n   $topicname=unserialize($news_tab[$story_limit][''topicname'']);\n   $topicimage=unserialize($news_tab[$story_limit][''topicimage'']);\n   $topictext=unserialize($news_tab[$story_limit][''topictext'']);\n   $s_id=unserialize($news_tab[$story_limit][''id'']);\n   if ($aid) {\n      ob_start();\n         themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);\n         $remp=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $remp="";\n   }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration de l''article complet (ID) et themisation pour incorporation<br />si ID > 0   : l''article publi&eacute; avec l''ID indiqu&eacute;e<br />si ID = 0   : le dernier article publi&eacute;<br />si ID = -1  : l''avant dernier ... jusqu''&agrave; -9 (limite actuelle)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!article_complet!', 'function MM_article_complet($arg) {\n   return (meta_lang("article_completID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de article_completID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('headlineID', 'function MM_headlineID($arg) {\n   return (@headlines($arg,""));\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du canal RSS (ID) et fabrication d''un retour pour affichage[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!headline!', 'function MM_headline($arg) {\n   return (meta_lang("headlineID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de headlineID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!list_mns!', 'function MM_list_mns() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users WHERE mns=''1''");\n   $MT_mns="<ul class=\\"list-group list-group-flush\\">";\n   while (list($uname)=sql_fetch_row($query)) {\n      $MT_mns.="<li class=\\"list-group-item\\"><a href=\\"minisite.php?op=$uname\\" target=\\"_blank\\">$uname</a></li>";\n   }\n   $MT_mns.="</ul>";\n   return ($MT_mns);\n}', 'meta', '-', NULL, '[french]Affiche une liste de tout les membres poss&eacute;dant un minisite avec un lien vers ceux-ci[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!LastMember!', 'function MM_LastMember() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users ORDER BY uid DESC LIMIT 0,1");\n   $result=sql_fetch_row($query);\n   return ($result[0]);\n}', 'meta', '-', NULL, '[french]Renvoie le pseudo du dernier membre inscrit[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!edito!', 'function MM_edito() {\n   list($affich,$M_edito)=fab_edito();\n   if ((!$affich) or ($M_edito=="")) {\n      $M_edito="";\n   }\n   return ($M_edito);\n}', 'meta', '-', NULL, '[french]Fabrique et affiche l''EDITO[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!edito-notitle!', '$cmd="!edito-notitle!";', 'meta', '-', NULL, '[french]Supprime le Titre EDITO et le premier niveau de tableau dans l''edito (ce meta-mot n''est actif que dans l''Edito)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!langue!', '$cmd=aff_local_langue("index.php", "choice_user_language","");', 'meta', '-', NULL, '[french]Fabrique une zone de selection des langues disponibles[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('groupe_text', 'function MM_groupe_text($arg) {\n   global $user;\n\n   $affich=false;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=true;\n   } else {\n      if ($user)\n         $affich=true;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Test si le membre appartient aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par groupe_textID(ID_group) ... !/!<br />Si groupe_ID est nul, la v&eacute;rification portera simplement sur la qualit&eacute; de membre<br />Syntaxe : groupe_text(), groupe_text(10) ou groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('no_groupe_text', 'function MM_no_groupe_text($arg) {\n   global $user;\n\n   $affich=true;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=false;\n      if (!$user)\n         $affich=false;\n   } else {\n      if ($user)\n         $affich=false;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Forme de ELSE de groupe_text / Test si le membre n''appartient pas aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par no_groupe_textID(ID_group) ... !/!<br />Si no_groupe_ID est nul, la v&eacute;rification portera sur qualit&eacute; d''anonyme<br />Syntaxe : no_groupe_text(), no_groupe_text(10) ou no_groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!note!', 'function MM_note() {\n   return ("!delete!");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera jamais affich&eacute;e !note! .... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!note_admin!', 'function MM_note_admin() {\n   global $admin;\n\n   if (!$admin)\n      return ("!delete!");\n   else\n      return("<b>nota</b> : ");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera affich&eacute;e que pour les administrateurs !note_admin! .... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!/!', '!\\/!', 'meta', '-', NULL, '[french]Termine LES meta-mot ENCADRANTS (!groupe_text!, !note!, !note_admin!, ...) : le fonctionnement est assez similaire &agrave; [langue] ...[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!debugON!', 'function MM_debugON() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $NPDS_debug_cycle=1;\n   $NPDS_debug=true;\n   $NPDS_debug_str="<br />";\n   $NPDS_debug_time=microtime(true);\n   return ("");\n}', 'meta', '-', NULL, '[french]Active le mode debug[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!debugOFF!', 'function MM_debugOFF() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $time_end = microtime(true);\n   $NPDS_debug_str.="=> !DebugOFF!<br /><b>=> exec time for meta-lang : ".round($time_end - $NPDS_debug_time, 4)." / cycle(s) : $NPDS_debug_cycle</b><br />";\n   $NPDS_debug=false;\n   echo $NPDS_debug_str;\n   return ("");\n}', 'meta', '-', NULL, '[french]D&eacute;sactive le mode debug[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_all', 'function MM_forum_all() {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories ORDER BY cat_id", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche toutes les categories et tous les forums (en fonction des droits)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_categorie', 'function MM_forum_categorie($arg) {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $bid_tab=explode(",",$arg); $sql="";\n   foreach($bid_tab as $cat) {\n      $sql.="cat_id=''$cat'' OR ";\n   }\n   $sql=substr($sql,0,-4);\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories WHERE $sql", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]affiche la (les) categorie(s) XX (en fonction des droits) / liste de categories : "XX,YY,ZZ" [/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_message', 'function MM_forum_message() {\n   include_once("functions.php");\n   global $subscribe, $user;\n   $ibid="";\n   if (!$user) {\n      $ibid= translate("Devenez membre et vous disposerez de fonctions spécifiques : abonnements, forums spéciaux (cachés, membres, ..), statut de lecture, ...");\n   }\n   if (($subscribe) and ($user)) {\n      $ibid= translate("Cochez un forum et cliquez sur le bouton pour recevoir un Email lors d''une nouvelle soumission dans celui-ci.");\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les messages en pied de forum (devenez membre, abonnement ...)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_recherche', 'function MM_forum_recherche() {\n   include_once("functions.php");\n\n   $Xcontent=@searchblock();\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche la zone de saisie du moteur de recherche des forums[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_icones', 'function MM_forum_icones() {\n   include_once("functions.php");\n\n   if ($ibid=theme_image("forum/icons/red_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_folder.gif";}\n   if ($ibid=theme_image("forum/icons/folder.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/folder.gif";}\n   $ibid="<img src=\\"$imgtmpR\\" border=\\"\\" alt=\\"\\" /> = ".translate("Les nouvelles contributions depuis votre dernière visite.")."<br />";\n   $ibid.="<img src=\\"$imgtmp\\" border=\\"\\" alt=\\"\\" /> = ".translate("Aucune nouvelle contribution depuis votre dernière visite.");\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les icones + legendes decrivant les marqueurs des forums[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subscribeON', 'function MM_forum_subscribeON() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $userX = base64_decode($user);\n      $userR = explode('':'', $userX);\r\n      if(isbadmailuser($userR[0])===false) {\r\n         $ibid="<form action=\\"forum.php\\" method=\\"post\\">\n      <input type=\\"hidden\\" name=\\"op\\" value=\\"maj_subscribe\\" />";\n      }\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_bouton_subscribe', 'function MM_forum_bouton_subscribe() {\r\n   include_once("functions.php");\r\n   global $subscribe, $user;\r\n   if (($subscribe) and ($user)) {\n      $userX = base64_decode($user);\n      $userR = explode('':'', $userX);\r\n      if(isbadmailuser($userR[0])===false) {\r\n         return (''<input class="btn btn-secondary" type="submit" name="Xsub" value="''.translate("OK").''" />'');\r\n      }\r\n   } else {\r\n      return ('''');\r\n   }\r\n}', 'meta', '-', NULL, '[french]Affiche le bouton de gestion des abonnements[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subscribeOFF', 'function MM_forum_subscribeOFF() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $userX = base64_decode($user);\n      $userR = explode('':'', $userX);\r\n      if(isbadmailuser($userR[0])===false) {\r\n         $ibid="\n   </form>";\n      }\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subfolder', 'function MM_forum_subfolder($arg) {\r\n\r\n   $forum=arg_filter($arg);\r\n   $content=sub_forum_folder($forum);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Renvoie le gif permettant de savoir si de nouveaux messages sont disponibles dans le forum X<br />Syntaxe : sub_folder(X) ou X est le num&eacute;ro du forum[/french][english][/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('insert_flash', 'function MM_insert_flash($name,$width,$height,$bgcol) {\n   return ("<object codebase=\\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflas\n   classid=\\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\\"\n   h.cab#version=6,0,0,0\\" width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   id=\\"".$name."\\" align=\\"middle\\">\n \n   <param name=\\"allowScriptAccess\\"\n   value=\\"sameDomain\\" />\n \n   <param name=\\"movie\\"\n   value=\\"flash/".$name."\\" />\n \n   <param name=\\"quality\\" value=\\"high\\" />\n   <param name=\\"bgcolor\\"\n   value=\\"".$bgcol."\\" />\n\n   <embed src=\\"flash/".$name."\\"\n   quality=\\"high\\" bgcolor=\\"".$bgcol."\\"\n   width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   name=\\"".$name."\\" align=\\"middle\\"\n   allowScriptAccess=\\"sameDomain\\"\n   type=\\"application/x-shockwave-flash\\"\n   pluginspage=\\"http://www.macromedia.com/go/getflashplayer\\" />\n\n   </object>");\n}', 'meta', '-', NULL, '[french]Insert un fichier flash (.swf) se trouvant dans un dossier "flash" de la racine du site. Syntaxe : insert_flash (nom du fichier.swf, largeur, hauteur, couleur fond : #XXYYZZ).[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!mailadmin!', '$cmd="<a href=\\"mailto:".anti_spam($GLOBALS[''adminmail''], 1)."\\" target=\\"_blank\\">".anti_spam($GLOBALS[''adminmail''], 0)."</a>";', 'meta', '-', NULL, '[french]Affiche un lien vers l''adresse mail de l''administrateur.[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!login!', 'function MM_login() {\r\n   global $user;\r\n   $boxstuff = ''\r\n   <div class="card card-body m-3">\r\n      <h5><a href="user.php?op=only_newuser" role="button" title="''.translate("Nouveau membre").''"><i class="fa fa-user-plus"></i>&nbsp;''.translate("Nouveau membre").''</a></h5>\r\n   </div>\r\n   <div class="card card-body m-3">\r\n      <h5 class="mb-3"><i class="fas fa-sign-in-alt fa-lg"></i>&nbsp;''.translate("Connexion").''</h5>\r\n      <form action="user.php" method="post" name="userlogin_b">\r\n         <div class="row g-2">\r\n            <div class="col-12">\r\n               <div class="mb-3 form-floating">\r\n                  <input type="text" class="form-control" name="uname" id="inputuser_b" placeholder="''.translate("Identifiant").''" required="required" />            \r\n                  <label for="inputuser_b" >''.translate("Identifiant").''</label>\r\n              </div>\r\n           </div>\r\n           <div class="col-12">\r\n              <div class="mb-0 form-floating">\r\n                 <input type="password" class="form-control" name="pass" id="inputPassuser_b" placeholder="''.translate("Mot de passe").''" required="required" />\r\n                 <label for="inputPassuser_b">''.translate("Mot de passe").''</label>\r\n                 <span class="help-block small"><a href="user.php?op=forgetpassword" role="button" title="''.translate("Vous avez perdu votre mot de passe ?").''">''.translate("Vous avez perdu votre mot de passe ?").''</a></span>\r\n               </div>\r\n            </div>\r\n         </div>\r\n         <input type="hidden" name="op" value="login" />\r\n         <div class="mb-3 row">\r\n            <div class="ms-sm-auto">\r\n               <button class="btn btn-primary" type="submit" title="''.translate("Valider").''">''.translate("Valider").''</button>\r\n            </div>\r\n         </div>\r\n      </form>\r\n   </div>'';\r\n   if(isset($user))\r\n      $boxstuff = ''<h5><a class="text-danger" href="user.php?op=logout"><i class="fas fa-sign-out-alt fa-lg align-middle text-danger me-2"></i>''.translate("Déconnexion").''</a></h5>'';\r\n\r\n   return ($boxstuff);\r\n}', 'meta', '-', NULL, '[french]Affiche les champs de connexion et d''inscription au site, ou le lien de d&eacute;connexion si vous &ecirc;tes connect&eacute; en tant que membre.[/french][english]Shows the site login and registration fields, or the logout link if you are logged in as a member.[/english][chinese]&#x663E;&#x793A;&#x7AD9;&#x70B9;&#x767B;&#x5F55;&#x548C;&#x6CE8;&#x518C;&#x5B57;&#x6BB5;&#xFF0C;&#x5982;&#x679C;&#x60A8;&#x4EE5;&#x4F1A;&#x5458;&#x8EAB;&#x4EFD;&#x767B;&#x5F55;&#xFF0C;&#x5219;&#x663E;&#x793A;&#x6CE8;&#x9500;&#x94FE;&#x63A5;&#x3002;[/chinese][spanish]Muestra los campos de inicio de sesi&oacute;n y registro del sitio, o el enlace de cierre de sesi&oacute;n si ha iniciado sesi&oacute;n como miembro.[/spanish][german]Zeigt die Anmelde- und Registrierungsfelder der Website oder den Abmeldelink an, wenn Sie als Mitglied angemeldet sind.[/german]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!connexion!', '$cmd=meta_lang("!login!");', 'meta', '-', NULL, '[french]Alias de !login![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!administration!', 'function MM_administration() {\n   global $admin;\n   if ($admin) {\n      return("<a href=\\"admin.php\\">".translate("Outils administrateur")."</a>");\n   } else {\n      return("");\n   }\n}', 'meta', '-', NULL, '[french]Affiche un lien vers l''administration du site uniquement si l''on est connect&eacute; en tant qu''admin[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('admin_infos', 'function MM_admin_infos($arg) {\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $rowQ1 = Q_select ("SELECT url, email FROM ".$NPDS_Prefix."authors WHERE aid=''$arg''", 86400);\n   $myrow=$rowQ1[0];\n   if ($myrow[''url''] !='''') {\n      $auteur="<a href=\\"".$myrow[''url'']."\\">$arg</a>";\n   } elseif ($myrow[''email''] !='''') {\n      $auteur="<a href=\\"mailto:".$myrow[''email'']."\\">$arg</a>";\n   } else {\n      $auteur=$arg;\n   }\n   return ($auteur);\n}', 'meta', '-', NULL, '[french]Affiche le Nom ou le WWW ou le Mail de l''administrateur / syntaxe : admin_infos(nom_de_admin)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('theme_img', 'function MM_theme_img($arg) {\n   return (MM_img($arg));\n}', 'meta', '-', NULL, '[french]Localise l''image et affiche une ressource de type &lt;img src= / syntaxe : theme_img(forum/onglet.gif)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!logo!', '$cmd="<img src=\\"".$GLOBALS[''site_logo'']."\\" border=\\"0\\" alt=\\"\\">";', 'meta', '-', NULL, '[french]Affiche le logo du site (admin/preferences).[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('rotate_img', 'function MM_rotate_img($arg) {\r\n   mt_srand((double)microtime()*1000000);\r\n   $arg = arg_filter($arg);\r\n   $tab_img=explode(",",$arg);\r\n\r\nif (count($tab_img)>1) {\r\n   $imgnum = mt_rand(0, count($tab_img)-1);\r\n} else if (count($tab_img)==1) {\r\n   $imgnum = 0;\r\n} else {\r\n   $imgnum = -1;\r\n}\r\nif ($imgnum!=-1) {\r\n   $Xcontent="<img src=\\"".$tab_img[$imgnum]."\\" border=\\"0\\" alt=\\"".$tab_img[$imgnum]."\\" title=\\"".$tab_img[$imgnum]."\\" />";\r\n}\r\n   return ($Xcontent);\r\n}', 'meta', '-', NULL, '[french]Affiche une image al&eacute;atoire - les images de la liste sont s&eacute;par&eacute;e par une virgule / syntaxe rotate_img("http://www.npds.org/users_private/user/1.gif,http://www.npds.org/users_private/user/2.gif, ...")[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sql_nbREQ!', 'function MM_sql_nbREQ() {\r\n   global $sql_nbREQ;\r\n\r\n   return ("SQL REQ : $sql_nbREQ");\r\n}', 'meta', '-', NULL, '[french]Affiche le nombre de requ&ecirc;te SQL pour la page courante[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('comment_system', 'function MM_comment_system ($file_name,$topic) {\r\n\r\nglobal $NPDS_Prefix,$anonpost,$moderate,$admin,$user;\r\nob_start();   \r\n   if (file_exists("modules/comments/$file_name.conf.php")) {\r\n      include ("modules/comments/$file_name.conf.php");\r\n      include ("modules/comments/comments.php");\r\n   }\r\n   $output = ob_get_contents();\r\nob_end_clean();\r\nreturn ($output);\r\n}', 'meta', '-', '', '[french]Permet de mettre en oeuvre un syst&egrave;me de commentaire complet / la mise en oeuvre n&eacute;cessite :<br /> - un fichier dans modules/comments/xxxx.conf.php de la m&ecirc;me structure que les autres<br /> - un appel coh&eacute;rent avec la configuration de ce fichier<br /><br />L''appel est du type : comments($file_name, $topic) - exemple comment_system(edito,1) - le fichier s''appel donc edito.conf.php[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_stories', 'function MM_top_stories ($arg) {\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $xtab=news_aff("libre","ORDER BY counter DESC LIMIT 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($counter>0) {\r\n        $content.=''<li class="ms-4 my-1"><a href="article.php?sid=''.$sid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($counter).'' ''.translate("Fois").''</span></li>'';\r\n     }\r\n   }\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles / syntaxe : top_stories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_commented_stories', 'function MM_top_commented_stories ($arg) {\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $xtab=news_aff("libre","ORDER BY comments DESC  LIMIT 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($comments>0) {\r\n         $content.= ''<li class="ms-4 my-1"><a href="article.php?sid=''.$sid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($comments).''</span></li>'';\r\n      }\r\n   }\r\n  return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles les plus comment&eacute;s / syntaxe : top_commented_stories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_categories', 'function MM_top_categories($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT catid, title, counter FROM ".$NPDS_Prefix."stories_cat order by counter DESC limit 0,$arg");\r\n   while (list($catid, $title, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $content.= ''<li class="ms-4 my-1"><a href="index.php?op=newindex&amp;catid=''.$catid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x cat&eacute;gories des articles / syntaxe : top_categories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_sections', 'function MM_top_sections ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT artid, title, counter FROM ".$NPDS_Prefix."seccont ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($artid, $title, $counter) = sql_fetch_row($result)) {\r\n      $content.=''<li class="ms-4 my-1"><a href="sections.php?op=viewarticle&amp;artid=''.$artid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($counter).'' ''.translate("Fois").''</span></li>'';\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles des rubriques / syntaxe : top_sections(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_reviews', 'function MM_top_reviews ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT id, title, hits FROM ".$NPDS_Prefix."reviews ORDER BY hits DESC LIMIT 0,$arg");\r\n   while (list($id, $title, $hits) = sql_fetch_row($result)) {\r\n      if ($hits>0) {\r\n         $content.= ''<li class="ms-4 my-1"><a href="reviews.php?op=showcontent&amp;id=''.$id.''" >''.$title.''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($hits).'' ''.translate("Fois").''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des critiques / syntaxe : top_reviews(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_authors', 'function MM_top_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT aid, counter FROM ".$NPDS_Prefix."authors ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($aid, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $content.= ''<li class="ms-4 my-1"><a href="search.php?query=&amp;author=''.$aid.''" >''.$aid.''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs / syntaxe : top_authors(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_polls', 'function MM_top_polls ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT pollID, pollTitle, voters FROM ".$NPDS_Prefix."poll_desc ORDER BY voters DESC LIMIT 0,$arg");\r\n   while (list($pollID, $pollTitle, $voters) = sql_fetch_row($result)) {\r\n      if ($voters>0) {\r\n         $content.=''<li class="ms-4 my-1"><a href="pollBooth.php?op=results&amp;pollID=''.$pollID.''" >''.aff_langue($pollTitle).''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($voters).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des sondages / syntaxe : top_polls(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_storie_authors', 'function MM_top_storie_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT uname, counter FROM ".$NPDS_Prefix."users ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($uname, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $content.=''<li class="ms-4 my-1"><a href="user.php?op=userinfo&amp;uname=''.$uname.''" >''.$uname.''</a>&nbsp;<span class="badge bg-secondary float-end">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs de news (membres) / syntaxe : top_storie_authors(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_all', 'function MM_topic_all() {\r\n   global $NPDS_Prefix, $tipath;\r\n   $aff='''';\r\n   $aff=''<div class="">'';\r\n   $result = sql_query("SELECT topicid, topicname, topicimage, topictext FROM ".$NPDS_Prefix."topics ORDER BY topicname");\r\n   while(list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {\r\n      $resultn = sql_query("SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."stories WHERE topic=''$topicid''");\r\n      $total_news= sql_fetch_assoc($resultn);\r\n      $aff.=''\r\n      <div class="col-sm-6 col-lg-4 mb-2 griditem px-2">\r\n         <div class="card my-2">'';\r\n      if ((($topicimage) or ($topicimage!='''')) and (file_exists("$tipath$topicimage")))\r\n         $aff.=''\r\n         <img class="mt-3 ms-3 n-sujetsize" src="''.$tipath.$topicimage.''" alt="topic_icon" />'';\r\n      $aff.=''\r\n            <div class="card-body">'';\r\n      if($total_news[''total'']!=''0'')\r\n         $aff.=''\r\n               <a href="index.php?op=newtopic&amp;topic=''.$topicid.''"><h4 class="card-title">''.aff_langue($topicname).''</h4></a>'';\r\n      else\r\n         $aff.=''\r\n               <h4 class="card-title">''.aff_langue($topicname).''</h4>'';\r\n      $aff.=''\r\n               <p class="card-text">''.aff_langue($topictext).''</p>\r\n               <p class="card-text text-end"><span class="small">''.translate("Nb. d''articles").''</span> <span class="badge bg-secondary">''.$total_news[''total''].''</span></p>\r\n            </div>'';\r\n      $aff.=''\r\n         </div>\r\n      </div>'';\r\n   }\r\n   $aff.=''\r\n      </div>'';\r\n   sql_free_result($result);\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les sujets avec leurs images.<br />Syntaxe : topic_all()[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribeOFF', 'function MM_topic_subscribeOFF() {\r\n   $aff= ''<div class="mb-3"><input type="hidden" name="op" value="maj_subscribe" />'';\r\n   $aff.=''<button class="btn btn-primary" type="submit" name="ok">''.translate("Valider").''</button>'';\r\n   $aff.=''</div></fieldset></form>'';\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribeON', 'function MM_topic_subscribeON() {\n   global $subscribe, $user, $cookie;\n   include_once(''functions.php'');\n   if ($subscribe and $user) {\n      if(isbadmailuser($cookie[0])===false) {\n         return (''<form action="topics.php" method="post"><fieldset>'');\n      }\n   }\n}', 'meta', '-', '', '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribe', 'function MM_topic_subscribe($arg) {\r\n   global $NPDS_Prefix, $subscribe, $user, $cookie;\r\n   $segment = arg_filter($arg);\r\n   $aff='''';\r\n   if ($subscribe) {\r\n      if ($user) {\r\n         $aff=''\r\n         <div class="mb-3 row">'';\r\n         $result = sql_query("SELECT topicid, topictext, topicname FROM ".$NPDS_Prefix."topics ORDER BY topicname");\r\n         while(list($topicid, $topictext, $topicname) = sql_fetch_row($result)) {\r\n            $resultX = sql_query("SELECT topicid FROM ".$NPDS_Prefix."subscribe WHERE uid=''$cookie[0]'' AND topicid=''$topicid''");\r\n            if (sql_num_rows($resultX)=="1")\r\n               $checked=''checked'';\r\n            else\r\n               $checked='''';\r\n            $aff.=''\r\n               <div class="''.$segment.''">\r\n                  <div class="form-check">\r\n                     <input type="checkbox" class="form-check-input" name="Subtopicid[''.$topicid.'']" id="subtopicid''.$topicid.''" ''.$checked.'' />\r\n                     <label class="form-check-label" for="subtopicid''.$topicid.''">''.aff_langue($topicname).''</label>\r\n                  </div>\r\n               </div>'';\r\n         }\r\n         $aff.=''</div>'';\r\n         sql_free_result($result);\r\n      }\r\n   }\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les noms des sujets avec la situation de l''abonnement du membre. Permet au membre de g&eacute;rer ces abonnements (aux sujets).\r\nSyntaxe : topic_subscribe(X) ou X indique le niveau de rupture dans la liste[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('yt_video', 'function MM_yt_video($id_yt_video) {\r\n   $content='''';\r\n   $id_yt_video = arg_filter($id_yt_video);\r\n   if(!defined(''CITRON''))\r\n      $content .=''\r\n      <div class="ratio ratio-16x9">\r\n         <iframe src="https://www.youtube.com/embed/''.$id_yt_video.''" allowfullscreen="" frameborder="0"></iframe>\r\n      </div>'';\r\n   else\r\n      $content .=''<div class="youtube_player" videoID="''.$id_yt_video.''"></div>'';\r\n   return ($content);\r\n}', 'meta', '-', '', '[french]Inclusion video Youtube. Syntaxe : yt_video(ID de la vid&eacute;o)[/french][english]Include a Youtube video. Syntax : yt_video(video ID)[/english][chinese]&#x5305;&#x542B;Youtube&#x89C6;&#x9891;&#x3002;&#x53E5;&#x6CD5; : yt_video(&#x89C6;&#x9891; ID)[/chinese][spanish]Incluye un video de Youtube. Sintaxis : yt_video(video ID)[/spanish][german]F&uuml;gen Sie ein Youtube-Video hinzu. Syntax : yt_video(video ID)[/german]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('espace_groupe', 'function MM_espace_groupe($gr, $t_gr, $i_gr) {\r\n$gr = arg_filter($gr);\r\n$t_gr = arg_filter($t_gr);\r\n$i_gr = arg_filter($i_gr);\r\n\r\nreturn (fab_espace_groupe($gr, $t_gr, $i_gr));\r\n}', 'meta', '-', NULL, '[french]Fabrique un WorkSpace / syntaxe : espace_groupe(groupe_id, aff_name_groupe, aff_img_groupe) ou groupe_id est l''ID du groupe - aff_name_groupe(0 ou 1) permet d''afficher le nom du groupe - aff_img_groupe(0 ou 1) permet d''afficher l''image associ&eacute;e au groupe.[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('^', '', 'docu', '-', NULL, '[french]Dans un texte quelconque, ^ &agrave; la fin d&#39;un mot permet de le prot&eacute;ger contre meta-lang / Ex : Dev Dev^ ne donne pas un r&eacute;sultat identique[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_publicateur!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nom de l&#39;administrateur ayant publi&eacute; l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_emetteur!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nom de l&#39;auteur de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_date!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la date de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_date_y!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par l&#39;ann&eacute;e de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_date_m!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le mois de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_date_d!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le jour de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_date_h!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par l&#39;heure de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_print!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien pour imprimer l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_friend!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien pour envoyer l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_titre!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le titre de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_texte!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le texte de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_id!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le num&eacute;ro de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_sujet!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par un lien HTML et l&#39;image du sujet de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_note!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le de la note de l&#39;article si elle existe / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_nb_lecture!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de lecture effective de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_suite!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML permettant de lire la suite de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_nb_carac!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de caract&egrave;re suppl&eacute;mentaire de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_read_more!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien &#39;lire la suite&#39; de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_nb_comment!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de commentaire de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_link_comment!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien vers les commentaires de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_categorie!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien vers la cat&eacute;gorie de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_previous_article!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML pointant sur l&#39;article pr&eacute;c&eacute;dent / actif detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_next_article!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML sur l&#39;article suivant / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_boxrel_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par titre du bloc &#39;lien relatif&#39; / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!N_boxrel_stuff!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le contenu du bloc &#39;lien relatif&#39; / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!B_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le titre du bloc / actif dans bloc.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!B_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par par le contenu du bloc / actif dans bloc.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!B_class_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la class CSS du titre - voir le gestionnaire de bloc de NPDS / actif dans bloc.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!B_class_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la class CSS du contenu - voir le gestionnaire de bloc de NPDS  / actif dans bloc.html[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!editorial_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le contenu de l&#39;Edito / actif que si editorial.html existe dans votre theme[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!PHP!', '', 'them', '-', NULL, '[french]Int&eacute;gration de code PHP "noy&eacute;" dans vos fichiers html de th&egrave;mes :<br />\r\n=> !PHP! commentaire vous permettant de trouver le php noy&eacute; -> in fine sera remplac&eacute; par ""<br />\r\n=> &lt;!--meta  doit pr&eacute;c&eacute;der votre code php -> in fine sera remplac&eacute; par ""<br />\r\n   => meta-->   doit suivre votre code php -> in fine sera remplac&eacute; par ""<br />\r\n<br />\r\n&nbsp;Exemple :<br />\r\n&nbsp;&nbsp;!PHP!&lt;!--meta<br />\r\n&nbsp;&nbsp;&lt;?php<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;global $cookie;<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;$username = $cookie[1];<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;if ($username == "") {<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo "Create an account";<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;} else {<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo "Welcome : $username";<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;}<br />\r\n&nbsp;&nbsp;?><br />\r\n&nbsp;&nbsp;meta-->[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!blocnote!', 'function MM_blocnote($arg) {\r\n      global $REQUEST_URI;\r\n      if (!stristr($REQUEST_URI,"admin.php")) {\r\n         return(@oneblock($arg,"RB"));\r\n      } else {\r\n         return("");\r\n      }\r\n}', 'meta', '-', NULL, '[french]Fabrique un blocnote contextuel en lieu et place du meta-mot / syntaxe : !blocnote!ID - ID = Id du bloc de droite dans le gestionnaire de bloc de NPDS[/french]', '0');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!forumP!', 'function MM_forumP()\r\n{\r\n\r\n	global $NPDS_Prefix, $cookie, $user;\r\n\r\n\r\n	/*Sujet chaud*/\r\n	$hot_threshold = 10;\r\n\r\n	/*Nbre posts a afficher*/\r\n	$maxcount = "15";\r\n\r\n	$MM_forumP = ''<table cellspacing="3" cellpadding="3" width="top" border="0">''\r\n	.''<tr align="center" class="ligna">''\r\n	.''<th width="5%">''.aff_langue(''[french]Etat[/french][english]State[/english]'').''</th>''\r\n	.''<th width="20%">''.aff_langue(''[french]Forum[/french][english]Forum[/english]'').''</th>''\r\n	.''<th width="30%">''.aff_langue(''[french]Sujet[/french][english]Topic[/english]'').''</th>''\r\n	.''<th width="5%">''.aff_langue(''[french]RÃ©ponse[/french][english]Replie[/english]'').''</th>''\r\n	.''<th width="20%">''.aff_langue(''[french]Dernier Auteur[/french][english]Last author[/english]'').''</th>''\r\n	.''<th width="20%">''.aff_langue(''[french]Date[/french][english]Date[/english]'').''</th>''\r\n	.''</tr>'';\r\n\r\n	/*Requete liste dernier post*/\r\n	$result = sql_query("SELECT MAX(post_id) FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 GROUP BY topic_id ORDER BY MAX(post_id) DESC LIMIT 0,$maxcount");\r\n	while (list($post_id) = sql_fetch_row($result))\r\n	{\r\n\r\n		/*Requete detail dernier post*/\r\n		$res = sql_query("SELECT \r\n				us.topic_id, us.forum_id, us.poster_id, us.post_time, \r\n				uv.topic_title, \r\n				ug.forum_name, ug.forum_type, ug.forum_pass, \r\n				ut.uname \r\n			FROM \r\n				".$NPDS_Prefix."posts us, \r\n				".$NPDS_Prefix."forumtopics uv, \r\n				".$NPDS_Prefix."forums ug, \r\n				".$NPDS_Prefix."users ut \r\n			WHERE \r\n				us.post_id = $post_id \r\n				AND uv.topic_id = us.topic_id \r\n				AND uv.forum_id = ug.forum_id \r\n				AND ut.uid = us.poster_id LIMIT 1");\r\n		list($topic_id, $forum_id, $poster_id, $post_time, $topic_title, $forum_name, $forum_type, $forum_pass, $uname) = sql_fetch_row($res);\r\n\r\n		if (($forum_type == "5") or ($forum_type == "7"))\r\n		{\r\n\r\n			$ok_affich = false;\r\n			$tab_groupe = valid_group($user);\r\n			$ok_affich = groupe_forum($forum_pass, $tab_groupe);\r\n\r\n		}\r\n		else\r\n		{\r\n\r\n			$ok_affich = true;\r\n\r\n		}\r\n\r\n		if ($ok_affich)\r\n		{\r\n\r\n			/*Nbre de postes par sujet*/\r\n			$TableRep = sql_query("SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 AND topic_id = ''$topic_id''");\r\n			$replys = sql_num_rows($TableRep)-1;\r\n\r\n			/*Gestion lu / non lu*/\r\n			$sqlR = "SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE topicid = ''$topic_id'' AND uid = ''$cookie[0]'' AND status != ''0''";\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_red_folder.gif")){$imgtmpHR = $ibid;}else{$imgtmpHR = "images/forum/icons/hot_red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_folder.gif")){$imgtmpH = $ibid;}else{$imgtmpH = "images/forum/icons/hot_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/red_folder.gif")){$imgtmpR = $ibid;}else{$imgtmpR = "images/forum/icons/red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/folder.gif")){$imgtmpF = $ibid;}else{$imgtmpF = "images/forum/icons/folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/lock.gif")){$imgtmpL = $ibid;}else{$imgtmpL="images/forum/icons/lock.gif";}\r\n\r\n			if ($replys >= $hot_threshold)\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpHR;\r\n				else\r\n					$image = $imgtmpH;\r\n\r\n			}\r\n			else\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpR;\r\n				else\r\n					$image = $imgtmpF;\r\n\r\n			}\r\n\r\n			if ($myrow[topic_status]!=0)\r\n			$image = $imgtmpL;\r\n\r\n			$MM_forumP .= ''<tr class="lignb">''\r\n			.''<td align="center"><img src="''.$image.''"></td>''\r\n			.''<td><a href="viewforum.php?forum=''.$forum_id.''">''.$forum_name.''</a></td>''\r\n			.''<td><a href="viewtopic.php?topic=''.$topic_id.''&forum=''.$forum_id.''">''.$topic_title.''</a></td>''\r\n			.''<td align="center">''.$replys.''</td>''\r\n			.''<td><a href="user.php?op=userinfo&uname=''.$uname.''">''.$uname.''</a></td>''\r\n			.''<td align="center">''.$post_time.''</td>''\r\n			.''</tr>'';\r\n\r\n		}\r\n\r\n	}\r\n\r\n	$MM_forumP .= ''</table>'';\r\n\r\n	return ($MM_forumP);\r\n\r\n}', 'meta', '-', '', '[french].les derniers posts sur les forums.[/french][english]...[/english]', '0');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!forumL!', 'function MM_forumL()\r\n{\r\n\r\n	global $NPDS_Prefix, $cookie, $user;\r\n\r\n	/*Sujet chaud*/\r\n	$hot_threshold = 10;\r\n\r\n	/*Nbre posts a afficher*/\r\n	$maxcount = "10";\r\n\r\n	$MM_forumL = ''<table cellspacing="3" cellpadding="3" width="top" border="0">''\r\n	.''<tr align="center" class="ligna">''\r\n	.''<td width="8%">''.aff_langue(''[french]Etat[/french][english]State[/english]'').''</td>''\r\n	.''<td width="35%">''.aff_langue(''[french]Forum[/french][english]Forum[/english]'').''</td>''\r\n	.''<td width="50%">''.aff_langue(''[french]Sujet[/french][english]Topic[/english]'').''</td>''\r\n	.''<td width="7%">''.aff_langue(''[french]RÃ©ponses[/french][english]Replies[/english]'').''</td>''\r\n	.''</tr>'';\r\n\r\n	/*Requete liste dernier post*/\r\n	$result = sql_query("SELECT MAX(post_id) FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 GROUP BY topic_id ORDER BY MAX(post_id) DESC LIMIT 0,$maxcount");\r\n	while (list($post_id) = sql_fetch_row($result))\r\n	{\r\n\r\n		/*Requete detail dernier post*/\r\n		$res = sql_query("SELECT \r\n				us.topic_id, us.forum_id, us.poster_id, \r\n				uv.topic_title, \r\n				ug.forum_name, ug.forum_type, ug.forum_pass \r\n			FROM \r\n				".$NPDS_Prefix."posts us, \r\n				".$NPDS_Prefix."forumtopics uv, \r\n				".$NPDS_Prefix."forums ug \r\n			WHERE \r\n				us.post_id = $post_id \r\n				AND uv.topic_id = us.topic_id \r\n				AND uv.forum_id = ug.forum_id LIMIT 1");\r\n		list($topic_id, $forum_id, $poster_id, $topic_title, $forum_name, $forum_type, $forum_pass) = sql_fetch_row($res);\r\n\r\n		if (($forum_type == "5") or ($forum_type == "7"))\r\n		{\r\n\r\n			$ok_affich = false;\r\n			$tab_groupe = valid_group($user);\r\n			$ok_affich = groupe_forum($forum_pass, $tab_groupe);\r\n\r\n		}\r\n		else\r\n		{\r\n\r\n			$ok_affich = true;\r\n\r\n		}\r\n\r\n		if ($ok_affich)\r\n		{\r\n\r\n			/*Nbre de postes par sujet*/\r\n			$TableRep = sql_query("SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 AND topic_id = ''$topic_id''");\r\n			$replys = sql_num_rows($TableRep)-1;\r\n\r\n			/*Gestion lu / non lu*/\r\n			$sqlR = "SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE topicid = ''$topic_id'' AND uid = ''$cookie[0]'' AND status != ''0''";\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_red_folder.gif")){$imgtmpHR = $ibid;}else{$imgtmpHR = "images/forum/icons/hot_red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_folder.gif")){$imgtmpH = $ibid;}else{$imgtmpH = "images/forum/icons/hot_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/red_folder.gif")){$imgtmpR = $ibid;}else{$imgtmpR = "images/forum/icons/red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/folder.gif")){$imgtmpF = $ibid;}else{$imgtmpF = "images/forum/icons/folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/lock.gif")){$imgtmpL = $ibid;}else{$imgtmpL="images/forum/icons/lock.gif";}\r\n\r\n			if ($replys >= $hot_threshold)\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpHR;\r\n				else\r\n					$image = $imgtmpH;\r\n\r\n			}\r\n			else\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpR;\r\n				else\r\n					$image = $imgtmpF;\r\n\r\n			}\r\n\r\n			if ($myrow[topic_status]!=0)\r\n			$image = $imgtmpL;\r\n\r\n			$MM_forumL .= ''<tr class="lignb">''\r\n			.''<td align="center"><img src="''.$image.''"></td>''\r\n			.''<td><a href="viewforum.php?forum=''.$forum_id.''">''.$forum_name.''</a></td>''\r\n			.''<td><a href="viewtopic.php?topic=''.$topic_id.''&forum=''.$forum_id.''">''.$topic_title.''</a></td>''\r\n			.''<td align="center">''.$replys.''</td>''\r\n			.''</tr>'';\r\n\r\n		}\r\n\r\n	}\r\n\r\n	$MM_forumL .= ''</table>'';\r\n\r\n	return ($MM_forumL);\r\n\r\n}', 'meta', '-', '', '[french].Retourne les derniers posts des forums en tenant compte des groupes\r\nVariables que vous devez configurer :\r\nmaxcount : nombre de posts que vous voulez afficher...[/french][english]...[/english]', '0');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('vm_video', 'function MM_vm_video($id_vm_video) {\r\n   $content='''';\r\n   $id_vm_video = arg_filter($id_vm_video);\r\n   if(!defined(''CITRON''))\r\n      $content .=''\r\n      <div class="ratio ratio-16x9">\r\n         <iframe src="https://player.vimeo.com/video/''.$id_vm_video.''" allowfullscreen="" frameborder="0"></iframe>\r\n      </div>'';\r\n   else\r\n      $content .=''<div class="vimeo_player" videoID="''.$id_vm_video.''"></div>'';\r\n   return ($content);\r\n}', 'meta', '-', '', '[french]Inclusion video Vimeo. Syntaxe : vm_video(ID de la vid&eacute;o)[/french][english]Include a Vimeo video. Syntax : vm_video(video ID)[/english][chinese]&#x5305;&#x542B;Vimeo&#x89C6;&#x9891;&#x3002;&#x53E5;&#x6CD5; : vm_video(&#x89C6;&#x9891; ID)[/chinese][spanish]Incluye un video de Vimeo. Sintaxis : vm_video(video ID)[/spanish][german]F&uuml;gen Sie ein Vimeo-Video hinzu. Syntax : vm_video(video ID)[/german]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('dm_video', 'function MM_dm_video($id_dm_video) {\r\n   $content='''';\r\n   $id_dm_video = arg_filter($id_dm_video);\r\n   if(!defined(''CITRON''))\r\n      $content .=''\r\n      <div class="ratio ratio-16x9">\r\n         <iframe src="https://www.dailymotion.com/embed/video/''.$id_dm_video.''" allowfullscreen="" frameborder="0"></iframe>\r\n      </div>'';\r\n   else\r\n      $content .=''<div class="dailymotion_player" videoID="''.$id_dm_video.''"></div>'';\r\n   return ($content);\r\n}\r\n', 'meta', '-', NULL, '[french]Inclusion video Dailymotion. Syntaxe : dm_video(ID de la vid&eacute;o)[/french][english]Include a Dailymotion video. Syntax : dm_video(video ID)[/english][chinese]&#x5305;&#x542B;Dailymotion&#x89C6;&#x9891;&#x3002;&#x53E5;&#x6CD5; : dm_video(&#x89C6;&#x9891; ID)[/chinese][spanish]Incluye un video de Dailymotion. Sintaxis : dm_video(video ID)[/spanish][german]F&uuml;gen Sie ein Dailymotion-Video hinzu. Syntax : dm_video(video ID)[/german]\r\n', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('noforbadmail', 'function MM_noforbadmail() {\r\n   global $subscribe, $user, $cookie;\r\n   include_once(''functions.php'');\r\n   $remp='''';\r\n   if ($subscribe and $user) {\r\n      if(isbadmailuser($cookie[0])===true)\r\n         $remp=''!delete!'';\r\n   }\r\n   return ($remp);\r\n}', 'meta', '-', NULL, '[french]Test si le membre est dans la liste des mails incorrects.\r\n Syntaxe : noforbadmail() ... !/![/french]\r\n', '1');

CREATE TABLE modules (
  mid int(10) NOT NULL AUTO_INCREMENT,
  mnom varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  minstall int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (mid),
  KEY mnom (mnom(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE optimy (
  optid int(11) NOT NULL AUTO_INCREMENT,
  optgain decimal(10,3) DEFAULT NULL,
  optdate varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  opthour varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  optcount int(11) DEFAULT '0',
  PRIMARY KEY (optid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE poll_data (
  pollID int(11) NOT NULL DEFAULT '0',
  optionText varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  optionCount int(11) NOT NULL DEFAULT '0',
  voteID int(11) NOT NULL DEFAULT '0',
  pollType int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO poll_data VALUES (2, '', 0, 12, 0);
INSERT INTO poll_data VALUES (2, '', 0, 11, 0);
INSERT INTO poll_data VALUES (2, '', 0, 10, 0);
INSERT INTO poll_data VALUES (2, '', 0, 9, 0);
INSERT INTO poll_data VALUES (2, '', 0, 8, 0);
INSERT INTO poll_data VALUES (2, '', 0, 7, 0);
INSERT INTO poll_data VALUES (2, '', 0, 6, 0);
INSERT INTO poll_data VALUES (2, '', 0, 5, 0);
INSERT INTO poll_data VALUES (2, 'Passable', 0, 4, 0);
INSERT INTO poll_data VALUES (2, 'Moyen', 0, 3, 0);
INSERT INTO poll_data VALUES (2, 'Bien', 1, 2, 0);
INSERT INTO poll_data VALUES (2, 'Super', 0, 1, 0);

CREATE TABLE poll_desc (
  pollID int(11) NOT NULL AUTO_INCREMENT,
  pollTitle char(100) NOT NULL DEFAULT '',
  timeStamp int(11) NOT NULL DEFAULT '0',
  voters mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (pollID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO poll_desc VALUES (2, 'NPDS', 1004108978, 1);

CREATE TABLE posts (
  post_id int(10) NOT NULL AUTO_INCREMENT,
  post_idH int(10) NOT NULL DEFAULT '0',
  image varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  topic_id int(10) NOT NULL DEFAULT '0',
  forum_id int(10) NOT NULL DEFAULT '0',
  poster_id int(10) DEFAULT NULL,
  post_text mediumtext COLLATE utf8mb4_unicode_ci,
  post_time varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  poster_ip varchar(54) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  poster_dns text COLLATE utf8mb4_unicode_ci,
  post_aff tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (post_id),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY post_aff (post_aff)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO posts VALUES (1, 0, '00.png', 1, 1, 2, 'Demo', '2011-10-26 17:00', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (2, 0, '01.png', 1, 1, 2, 'R&eacute;ponse', '2012-03-05 22:36', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (3, 0, '00.png', 2, 2, 1, 'Message 1', '2013-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (4, 3, '01.png', 2, 2, 1, 'R&eacute;ponse au Message 1', '2003-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (5, 4, '02.png', 2, 2, 1, 'R&eacute;ponse &agrave; la r&eacute;ponse du Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (6, 0, '03.png', 2, 2, 1, 'R&eacute;ponse au Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (7, 0, '04.png', 2, -2, 2, 'Bien, bien et m&ecirc;me mieux encore', '2012-07-22 13:42:22', '1.1.76.115', '', 1);

CREATE TABLE priv_msgs (
  msg_id int(10) NOT NULL AUTO_INCREMENT,
  msg_image varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  subject varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  from_userid int(10) NOT NULL DEFAULT '0',
  to_userid int(10) NOT NULL DEFAULT '0',
  msg_time varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  msg_text text COLLATE utf8mb4_unicode_ci,
  read_msg tinyint(10) NOT NULL DEFAULT '0',
  type_msg int(1) NOT NULL DEFAULT '0',
  dossier varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '...',
  PRIMARY KEY (msg_id),
  KEY to_userid (to_userid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE publisujet (
  aid varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  secid2 int(30) NOT NULL DEFAULT '0',
  type int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE queue (
  qid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(9) NOT NULL DEFAULT '0',
  uname varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  subject varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  story mediumtext COLLATE utf8mb4_unicode_ci,
  bodytext mediumtext COLLATE utf8mb4_unicode_ci,
  timestamp datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  topic varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Linux',
  date_debval datetime DEFAULT NULL,
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (qid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rblocks (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  content text COLLATE utf8mb4_unicode_ci,
  member varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  Rindex tinyint(4) NOT NULL DEFAULT '0',
  cache mediumint(8) unsigned NOT NULL DEFAULT '0',
  actif smallint(5) unsigned NOT NULL DEFAULT '1',
  css tinyint(1) NOT NULL DEFAULT '0',
  aide mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id),
  KEY Rindex (Rindex)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO rblocks VALUES (1, '[french]Un Bloc ...[/french][english]One Block ...[/english][chinese]&#x4E00;&#x5757;...[/chinese][spanish]Un Bloque...[/spanish][german]Ein Block[/german]', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO rblocks VALUES (2, 'Information', '<p align="center"><a href="http://www.npds.org" target="_blank"><img src="images/powered/miniban-bleu.png" border="0" alt="npds_logo" /></a></p>', '0', 0, 0, 1, 0, '');
INSERT INTO rblocks VALUES (3, 'Bloc membre', 'function#userblock', '0', 5, 0, 1, 0, '');
INSERT INTO rblocks VALUES (4, 'Lettre d''information', 'function#lnlbox', '0', 6, 86400, 1, 0, '');
INSERT INTO rblocks VALUES (5, 'Anciens Articles', 'function#oldNews\r\nparams#$storynum', '0', 4, 3600, 1, 0, '');
INSERT INTO rblocks VALUES (7, 'Cat&eacute;gories', 'function#category', '0', 2, 28800, 1, 0, '');
INSERT INTO rblocks VALUES (8, 'Article du Jour', 'function#bigstory', '0', 3, 60, 1, 0, '');

CREATE TABLE referer (
  rid int(11) NOT NULL AUTO_INCREMENT,
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (rid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE related (
  rid int(11) NOT NULL AUTO_INCREMENT,
  tid int(11) NOT NULL DEFAULT '0',
  name varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (rid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews (
  id int(10) NOT NULL AUTO_INCREMENT,
  date date NOT NULL,
  title varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  text text COLLATE utf8mb4_unicode_ci NOT NULL,
  reviewer varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  score int(10) NOT NULL DEFAULT '0',
  cover varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url_title varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  hits int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews_add (
  id int(10) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  title varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  text text COLLATE utf8mb4_unicode_ci NOT NULL,
  reviewer varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  score int(10) NOT NULL DEFAULT '0',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url_title varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews_main (
  title text COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO reviews_main VALUES ('[french]Votre point de vue nous int&eacute;resse[/french][english]Your point of view interests us[/english][chinese]&#x60A8;&#x7684;&#x89C2;&#x70B9;&#x4F7F;&#x6211;&#x4EEC;&#x611F;&#x5174;&#x8DA3;[/chinese][spanish]Tu punto de vista nos interesa[/spanish][german]Ihr Standpunkt interessiert uns[/german]', '[french]Participez &agrave; la vie du site en apportant vos critiques mais restez toujours positif.[/french][english]Participate in the life of the website by bringing your criticisms but always remain positive.[/english][chinese]&#x901A;&#x8FC7;&#x63D0;&#x51FA;&#x6279;&#x8BC4;&#x6765;&#x53C2;&#x4E0E;&#x7F51;&#x7AD9;&#x7684;&#x751F;&#x6D3B;&#xFF0C;&#x4F46;&#x59CB;&#x7EC8;&#x4FDD;&#x6301;&#x79EF;&#x6781;&#x6001;&#x5EA6;&#x3002;[/chinese][spanish]Participe en la vida del sitio web aportando sus cr&iacute;ticas, pero siempre sea positivo.[/spanish][german]Nehmen Sie am Leben der Website teil, indem Sie Ihre Kritik einbringen, aber immer positiv bleiben.[/german]');

CREATE TABLE rubriques (
  rubid int(4) NOT NULL AUTO_INCREMENT,
  rubname varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  intro text COLLATE utf8mb4_unicode_ci NOT NULL,
  enligne tinyint(1) NOT NULL DEFAULT '0',
  ordre int(2) NOT NULL DEFAULT '0',
  UNIQUE KEY rubid (rubid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO rubriques VALUES (1, 'Divers', '', 1, 9998);
INSERT INTO rubriques VALUES (2, 'Presse-papiers', '', 0, 9999);
INSERT INTO rubriques VALUES (3, 'Mod&egrave;le', '', 1, 0);

CREATE TABLE seccont (
  artid int(11) NOT NULL AUTO_INCREMENT,
  secid int(11) NOT NULL DEFAULT '0',
  title text COLLATE utf8mb4_unicode_ci NOT NULL,
  content longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  author varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  ordre int(2) NOT NULL DEFAULT '0',
  userlevel varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  timestamp varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (artid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE seccont_tempo (
  artid int(11) NOT NULL AUTO_INCREMENT,
  secid int(11) NOT NULL DEFAULT '0',
  title text COLLATE utf8mb4_unicode_ci NOT NULL,
  content longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  author varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  ordre int(2) NOT NULL DEFAULT '0',
  userlevel varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (artid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sections (
  secid int(11) NOT NULL AUTO_INCREMENT,
  secname varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  image varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  userlevel varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  rubid int(5) NOT NULL DEFAULT '3',
  intro text COLLATE utf8mb4_unicode_ci,
  ordre int(2) NOT NULL DEFAULT '0',
  counter int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (secid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO sections VALUES (1, 'Pages statiques', '', '0', 1, NULL, 0, 0);
INSERT INTO sections VALUES (2, 'En instance', '', '0', 2, NULL, 0, 0);
INSERT INTO sections VALUES (3, 'Modifications des th&egrave;mes', '', '', 3, '', 1, 0);

CREATE TABLE session (
  username varchar(54) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  time varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  host_addr varchar(54) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  guest int(1) NOT NULL DEFAULT '0',
  uri varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  agent varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  KEY username (username),
  KEY time (time),
  KEY guest (guest)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO session VALUES ('user', '1384102103', '127.0.0.1', 0, '/index.php', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0');

CREATE TABLE sform (
  cpt int(11) NOT NULL AUTO_INCREMENT,
  id_form text COLLATE utf8mb4_unicode_ci NOT NULL,
  id_key text COLLATE utf8mb4_unicode_ci NOT NULL,
  key_value varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  passwd text COLLATE utf8mb4_unicode_ci,
  content longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (cpt)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stories (
  sid int(11) NOT NULL AUTO_INCREMENT,
  catid int(11) NOT NULL DEFAULT '0',
  aid varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  time datetime DEFAULT NULL,
  hometext mediumtext COLLATE utf8mb4_unicode_ci,
  bodytext mediumtext COLLATE utf8mb4_unicode_ci,
  comments int(11) DEFAULT '0',
  counter mediumint(8) unsigned DEFAULT NULL,
  topic int(3) NOT NULL DEFAULT '1',
  informant varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  notes text COLLATE utf8mb4_unicode_ci NOT NULL,
  ihome int(1) NOT NULL DEFAULT '0',
  archive tinyint(1) unsigned NOT NULL DEFAULT '0',
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (sid),
  KEY catid (catid),
  KEY topic (topic),
  KEY informant (informant),
  KEY aid (aid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO stories VALUES (1, 0, 'Root', 'Comment modifier et / ou supprimer EDITO', '2023-02-28 05:01:52', '<b>L''EDITO </b>est la <b>premi&egrave;re chose que les visiteurs visualiseront</b> en arrivant sur votre nouveau <b>site NPDS</b>.<br /><br />Vous pouvez l''<b>&eacute;diter</b> pour le personnaliser, ainsi que choisir de l''afficher ou non. <br />Pour toute modification, l''<b>&eacute;diteur int&eacute;gr&eacute; &agrave; NPDS</b> vous simplifiera &eacute;norm&eacute;ment la t&acirc;che !<br /><br />Enfin, vous pouvez d&eacute;cider dans les <i>pr&eacute;f&eacute;rences administrateur</i> de la page que vous souhaitez utiliser <b>comme index de votre site</b>:\r\nce n''est donc pas forc&eacute;ment l''EDITO, et votre imagination laissera entrevoir bien d''autres possibilit&eacute;s !<br />', 'Vous pouvez, par exemple:<br /><ul>\r\n  <li>faire arriver vos visiteurs sur la <b>page des forums</b></li>\r\n  <li>faire arriver vos visiteurs sur <b>une page d&eacute;crivant votre site en utilisant les rubriques</b></li>\r\n  <li>....<br />\r\n  </li>\r\n</ul>', 0, 1, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (2, 0, 'Root', 'NPDS embarque un excellent &eacute;diteur HTML !', '2023-02-28 01:08:39', '<div class="mceTmpl"><br /><div class="row my-2"><br /><div class="col-sm-6"><br /><p>L''<span style="font-weight: bold;">&eacute;diteur HTML</span> int&eacute;gr&eacute; dans <span style="font-weight: bold;">NPDS</span> est vraiment <span style="font-style: italic;">tr&egrave;s puissant</span> ! <a href="https://www.tiny.cloud" target="_blank" rel="noopener">TinyMCE</a>, c''est son nom, vous permet de saisir et de mettre en forme le texte directement depuis votre navigateur.</p><br /></div><br /><div class="col-sm-6"><br /><p><span style="font-weight: bold;">L''envoi d''images</span> sur votre site est <span style="font-style: italic;">tr&egrave;s simple</span> si vous souhaitez illustrer vos textes, et vous pouvez aussi faire des <span style="font-weight: bold;">copier/coller</span> depuis nimporte quel logiciel de <span style="font-weight: bold;">traitement de texte</span> !</p><br /></div><br /><div class="col-sm-12"><br /><p style="text-align: justify;">Combin&eacute; au fonctions sp&eacute;cifique de NPDS : mod&egrave;les de pages, banques d''images, metalang, &nbsp;upload ... etc &nbsp;cet <span style="font-weight: bold;">&eacute;diteur HTML</span> vous permettra vraiment de publier un contenu richement mis en forme que ce soit depuis votre ordinateur ou votre smartphone !</p><br /></div><br /></div><br /></div>', '', 0, 1, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (3, 0, 'Root', 'Les modules de NPDS', '2023-02-28 15:11:32', '<div style="text-align: left;"><br /><p>NPDS dispose de nombreux <strong>modules</strong> ajoutant des fonctionnalit&eacute;s tr&egrave;s diverses &agrave; votre site.</p><br /><p>Certain sont embarqu&eacute;s avec l''archive : les <strong>modules du core&nbsp;</strong>(pr&ecirc;t &agrave; l''emploi et non d&eacute;sinstallables car ils sont directement utilis&eacute;s par le core ) ; les <strong>modules externes&nbsp;</strong>installables et d&eacute;sinstallables &agrave; tout moment...</p><br /><p><strong>- Les modules core</strong></p><br /><ul><li>module contact</li><li>module upload</li><li>module de g&eacute;olocalisation</li><li>module de liens</li><li>module twitter</li><li>etc ...</li></ul><br /><p><strong>- Les modules externes disponibles dans l''archive</strong></p><br /><ul><li>module d''archive d''article</li><li>module de marque page</li><li>module de bloc note</li><li>etc ...</li></ul><br /><strong>- Les modules externes non disponible dans l''archive </strong></div><br /><div style="text-align: left;"><br />Actuellement seulement 5 modules ont &eacute;t&eacute; mis &agrave; niveau (responsive design et compatibilit&eacute; 16.8) ils b&eacute;n&eacute;ficient tous d''une installation automatique facile ...<br /><ul><li>npds_galerie</li><li>npds_glossaire</li><li>npds_annonce</li><li>npds_agenda</li><li>quizz</li></ul><br /><p>Vous pouvez les trouver en t&eacute;l&eacute;chargement ici <a href="https://github.com/npds" target="_blank" rel="noopener">https://github.com/npds</a></p><br /><p>Beaucoup d''autre modules (compatible avec la version 13) restent &agrave; mettre &agrave; jour pour qu''ils fonctionnent avec les versions =&gt; 16 &nbsp;de NPDS. Ils sont disponibles ici <a href="http://modules.npds.org/" target="_blank" rel="noopener">http://modules.npds.org</a></p><br /></div>', '', 0, 1, 2, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (4, 0, 'Root', 'Les th&egrave;mes de NPDS', '2023-02-28 18:59:36', '<p style="text-align: left;">NPDS 16.8 dispose de 9 th&egrave;mes graphiques dont 4 skinable (26 skins disponibles *)&nbsp;ce qui donne donc 109 visualisations diff&eacute;rentes du portail (et bien plus encore en utilisant les possibilit&eacute;s de configuration du fichier pages.php) !</p><br /><p style="text-align: left;">* Vous pouvez visualiser ces diff&eacute;rents skins ici &nbsp;<a href="/themes/_skins/default"><span class="fa fa-paint-brush fa-2x">&nbsp;</span></a></p><br /><p>Vous pouvez aussi visiter <a title="Tous les THEMES pour NPDS" href="http://styles.npds.org/" target="_blank" rel="noopener">http://styles.npds.org</a>, bien que non &agrave; jour ce site avec plus de 100 th&egrave;mes disponibles pour les versions inf&eacute;rieures &agrave; 16 vous permettra certainement de trouver des id&eacute;es, conseils et tutoriels encore utiles pour cr&eacute;er votre propre th&egrave;me.</p>', '', 0, 1, 3, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);

CREATE TABLE stories_cat (
  catid int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscribe (
  topicid tinyint(3) DEFAULT NULL,
  forumid int(10) DEFAULT NULL,
  lnlid text COLLATE utf8mb4_unicode_ci,
  uid int(11) NOT NULL DEFAULT '0',
  KEY uid (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE topics (
  topicid int(3) NOT NULL AUTO_INCREMENT,
  topicname varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  topicimage varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  topictext varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  topicadmin text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (topicid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO topics VALUES (1, 'npds', 'npds.gif', 'NPDS', 0, NULL);
INSERT INTO topics VALUES (2, 'modules', 'modules.gif', 'Modules', 0, NULL);
INSERT INTO topics VALUES (3, 'styles', 'styles.gif', 'Styles', 0, NULL);

CREATE TABLE users (
  uid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  uname varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  email varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  femail varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  url varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  user_avatar varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_regdate varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  user_occ varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_from varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_intrest varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_sig varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_viewemail tinyint(2) DEFAULT NULL,
  user_theme varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_journal text COLLATE utf8mb4_unicode_ci,
  pass varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  hashkey tinyint(1) NOT NULL DEFAULT '0',
  storynum tinyint(4) NOT NULL DEFAULT '10',
  umode varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  uorder tinyint(1) NOT NULL DEFAULT '0',
  thold tinyint(1) NOT NULL DEFAULT '0',
  noscore tinyint(1) NOT NULL DEFAULT '0',
  bio tinytext COLLATE utf8mb4_unicode_ci,
  ublockon tinyint(1) NOT NULL DEFAULT '0',
  ublock tinytext COLLATE utf8mb4_unicode_ci,
  theme varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  commentmax int(11) NOT NULL DEFAULT '4096',
  counter int(11) NOT NULL DEFAULT '0',
  send_email tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_visible tinyint(1) unsigned NOT NULL DEFAULT '1',
  mns tinyint(1) unsigned NOT NULL DEFAULT '0',
  user_langue varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_lastvisit varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_lnl tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users VALUES (1, '', 'Anonyme', '', '', '', 'blank.gif', '989445600', '', '', '', '', 0, 0, '', '',0, 10, '', 0, 0, 0, '', 0, '', '', 4096, 0, 0, 1, 0, NULL, NULL, 1);
INSERT INTO users VALUES (2, 'user', 'user', 'user@user.land', '', 'http://www.userland.com', '014.gif', '989445600', '', '', '', 'User of the Land', 0, 0, '', 'd.q1Wcp0KUqsk', 0, 10, '', 0, 0, 0, '', 1, '<ul><li><a href=http://www.npds.org target=_blank>NPDS.ORG</a></li></ul>', 'npds-boost_sk+default', 4096, 4, 0, 1, 1, 'french', '1384102103', 1);

CREATE TABLE users_extend (
  uid int(11) NOT NULL AUTO_INCREMENT,
  C1 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C2 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C3 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C4 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C5 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C6 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C7 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  C8 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  M1 mediumtext COLLATE utf8mb4_unicode_ci,
  M2 mediumtext COLLATE utf8mb4_unicode_ci,
  T1 varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  T2 varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  B1 blob,
  PRIMARY KEY (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users_extend VALUES (2, '', '', '', '', '', '', '45.728712', '4.818514', '', '', '15/07/2015', '', 'none');

CREATE TABLE users_status (
  uid int(11) NOT NULL AUTO_INCREMENT,
  posts int(10) DEFAULT '0',
  attachsig int(2) DEFAULT '0',
  rang int(10) DEFAULT '0',
  level int(10) DEFAULT '1',
  open tinyint(1) NOT NULL DEFAULT '1',
  groupe varchar(34) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users_status VALUES (1, 19, 0, 0, 1, 1, '');
INSERT INTO users_status VALUES (2, 3, 0, 0, 2, 1, '');

CREATE TABLE wspad (
  ws_id int(11) NOT NULL AUTO_INCREMENT,
  page varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  content mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  modtime int(15) NOT NULL,
  editedby varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  ranq smallint(6) NOT NULL DEFAULT '1',
  member int(11) NOT NULL DEFAULT '1',
  verrou varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (ws_id),
  KEY page (page(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ip_loc (
  ip_id smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  ip_long float NOT NULL DEFAULT '0',
  ip_lat float NOT NULL DEFAULT '0',
  ip_visi_pag varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  ip_visite mediumint(9) UNSIGNED NOT NULL DEFAULT '0',
  ip_ip varchar(54) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  ip_country varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  ip_code_country varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  ip_city varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (ip_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;