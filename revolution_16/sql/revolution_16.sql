
CREATE TABLE access (
  access_id int(10) NOT NULL AUTO_INCREMENT,
  access_title varchar(20) DEFAULT NULL,
  PRIMARY KEY (access_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO access (access_id, access_title) VALUES(1, 'User');
INSERT INTO access (access_id, access_title) VALUES(2, 'Moderator');
INSERT INTO access (access_id, access_title) VALUES(3, 'Super Moderator');

CREATE TABLE adminblock (
  title varchar(250) DEFAULT NULL,
  content text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO adminblock (title, content) VALUES
('Administration', '<ul><li><a href="admin.php"><i class="fa fa-sign-in fa-2x align-middle"></i> Administration</a></li><li><a href="admin.php?op=logout" class=" text-danger"><i class="fa fa-sign-out fa-2x align-middle"></i> Logout</a></li></ul>');

CREATE TABLE appli_log (
  al_id int(11) NOT NULL DEFAULT '0',
  al_name varchar(255) DEFAULT NULL,
  al_subid int(11) NOT NULL DEFAULT '0',
  al_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  al_uid int(11) NOT NULL DEFAULT '0',
  al_data text,
  al_ip varchar(19) NOT NULL DEFAULT '',
  al_hostname varchar(255) DEFAULT NULL,
  KEY al_id (al_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO appli_log VALUES (1, 'Poll', 2, '2012-07-15 13:35:32', 1, '2', '1.1.76.115', '');

CREATE TABLE authors (
  aid varchar(30) NOT NULL DEFAULT '',
  name varchar(50) DEFAULT NULL,
  url varchar(60) DEFAULT NULL,
  email varchar(60) DEFAULT NULL,
  pwd varchar(40) DEFAULT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  radminfilem tinyint(2) NOT NULL DEFAULT '0',
  radminsuper tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (aid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO authors (aid, name, url, email, pwd, counter, radminfilem, radminsuper) VALUES ('Root', 'Root', '', 'root@npds.org', 'd.8V.L9nSMMvE', 0, 0, 1);

CREATE TABLE autonews (
  anid int(11) NOT NULL AUTO_INCREMENT,
  catid int(11) NOT NULL DEFAULT '0',
  aid varchar(30) NOT NULL DEFAULT '',
  title varchar(255) DEFAULT NULL,
  time varchar(19) NOT NULL DEFAULT '',
  hometext text NOT NULL,
  bodytext mediumtext,
  topic int(3) NOT NULL DEFAULT '1',
  informant varchar(20) NOT NULL DEFAULT '',
  notes text NOT NULL,
  ihome int(1) NOT NULL DEFAULT '0',
  date_debval datetime DEFAULT NULL,
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (anid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE banner (
  bid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  imptotal int(11) NOT NULL DEFAULT '0',
  impmade int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  imageurl varchar(200) NOT NULL DEFAULT '',
  clickurl varchar(200) NOT NULL DEFAULT '',
  userlevel int(1) NOT NULL DEFAULT '0',
  date datetime DEFAULT NULL,
  PRIMARY KEY (bid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE bannerclient (
  cid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(60) NOT NULL DEFAULT '',
  contact varchar(60) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  login varchar(10) NOT NULL DEFAULT '',
  passwd varchar(10) NOT NULL DEFAULT '',
  extrainfo text NOT NULL,
  PRIMARY KEY (cid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE bannerfinish (
  bid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  impressions int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  datestart datetime DEFAULT NULL,
  dateend datetime DEFAULT NULL,
  PRIMARY KEY (bid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE catagories (
  cat_id int(10) NOT NULL AUTO_INCREMENT,
  cat_title text,
  PRIMARY KEY (cat_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO catagories VALUES (1, 'Demo');

CREATE TABLE chatbox (
  username text,
  ip varchar(20) NOT NULL DEFAULT '',
  message text,
  date int(15) NOT NULL DEFAULT '0',
  id int(10) DEFAULT '0',
  dbname tinyint(4) DEFAULT '0',
  PRIMARY KEY (date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE compatsujet (
  id1 varchar(30) NOT NULL DEFAULT '',
  id2 int(30) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE config (
  allow_html int(2) DEFAULT NULL,
  allow_bbcode int(2) DEFAULT NULL,
  allow_sig int(2) DEFAULT NULL,
  posts_per_page int(10) DEFAULT NULL,
  hot_threshold int(10) DEFAULT NULL,
  topics_per_page int(10) DEFAULT NULL,
  allow_upload_forum int(2) unsigned NOT NULL DEFAULT '0',
  allow_forum_hide int(2) unsigned NOT NULL DEFAULT '0',
  upload_table varchar(50) NOT NULL DEFAULT 'forum_attachments',
  rank1 varchar(255) DEFAULT NULL,
  rank2 varchar(255) DEFAULT NULL,
  rank3 varchar(255) DEFAULT NULL,
  rank4 varchar(255) DEFAULT NULL,
  rank5 varchar(255) DEFAULT NULL,
  anti_flood char(3) DEFAULT NULL,
  solved int(2) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO config VALUES (1, 1, 1, 10, 10, 10, 0, 0, 'forum_attachments', NULL, NULL, NULL, NULL, NULL, NULL, 0);

CREATE TABLE counter (
  id_stat int(10) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(80) NOT NULL DEFAULT '',
  var varchar(80) NOT NULL DEFAULT '',
  count int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_stat)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
  durl varchar(255) DEFAULT NULL,
  dfilename varchar(255) DEFAULT NULL,
  dfilesize bigint(15) unsigned DEFAULT NULL,
  ddate date NOT NULL DEFAULT '0000-00-00',
  dweb varchar(255) DEFAULT NULL,
  duser varchar(30) DEFAULT NULL,
  dver varchar(6) DEFAULT NULL,
  dcategory varchar(250) DEFAULT NULL,
  ddescription text,
  perms varchar(480) NOT NULL DEFAULT '0',
  PRIMARY KEY (did)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE droits (
  d_aut_aid varchar(40) NOT NULL COMMENT 'id administrateur',
  d_fon_fid tinyint(3) unsigned NOT NULL COMMENT 'id fonction',
  d_droits varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Dune_proto';

CREATE TABLE ephem (
  eid int(11) NOT NULL AUTO_INCREMENT,
  did int(2) NOT NULL DEFAULT '0',
  mid int(2) NOT NULL DEFAULT '0',
  yid int(4) NOT NULL DEFAULT '0',
  content text NOT NULL,
  PRIMARY KEY (eid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE faqanswer (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_cat tinyint(4) DEFAULT NULL,
  question varchar(255) DEFAULT NULL,
  answer text,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE faqcategories (
  id_cat tinyint(3) NOT NULL AUTO_INCREMENT,
  categories varchar(255) DEFAULT NULL,
  PRIMARY KEY (id_cat)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE fonctions (
  fid mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id unique auto incrémenté',
  fnom varchar(40) NOT NULL,
  fdroits1 tinyint(3) unsigned NOT NULL,
  fdroits1_descr varchar(40) NOT NULL,
  finterface tinyint(1) unsigned NOT NULL COMMENT '1 ou 0 : la fonction dispose ou non d''une interface',
  fetat tinyint(1) NOT NULL COMMENT '0 ou 1  9 : non active ou installé, installé',
  fretour varchar(500) NOT NULL COMMENT 'utiliser par les fonctions de categorie Alerte : nombre, ou ',
  fretour_h varchar(500) NOT NULL,
  fnom_affich varchar(200) NOT NULL,
  ficone varchar(40) NOT NULL,
  furlscript varchar(4000) NOT NULL COMMENT 'attribut et contenu  de balise A : href="xxx", onclick="xxx"  etc',
  fcategorie tinyint(3) unsigned NOT NULL,
  fcategorie_nom varchar(200) NOT NULL,
  fordre tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (fid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Dune_proto';

INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(1, 'edito', 1, '', 1, 1, '', '', 'Edito', 'edito', 'href="admin.php?op=Edito"', 1, 'Contenu', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(2, 'adminStory', 1, '', 1, 1, '', '', 'Nouvel Article', 'postnew', 'href="admin.php?op=adminStory"', 1, 'Contenu', 1);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(3, 'sections', 1, '', 1, 1, '', '', 'Rubriques', 'sections', 'href="admin.php?op=sections"', 1, 'Contenu', 2);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(4, 'topicsmanager', 1, '', 1, 1, '', '', 'Gestion des Sujets', 'topicsman', 'href="admin.php?op=topicsmanager"', 1, 'Contenu', 3);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(5, 'links', 1, '', 1, 1, '', '', 'Liens Web', 'links', 'href="admin.php?op=links"', 1, 'Contenu', 5);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(6, 'FaqAdmin', 1, '', 1, 1, '1', '', 'FAQ', 'faq', 'href="admin.php?op=FaqAdmin"', 1, 'Contenu', 6);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(7, 'Ephemerids', 1, '', 1, 1, '1', '', 'Ephémérides', 'ephem', 'href="admin.php?op=Ephemerids"', 1, 'Contenu', 7);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(8, 'HeadlinesAdmin', 1, '', 1, 1, '', '', 'News externes', 'headlines', 'href="admin.php?op=HeadlinesAdmin"', 1, 'Contenu', 8);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(9, 'DownloadAdmin', 1, '', 1, 1, '', '', 'Téléchargements', 'download', 'href="admin.php?op=DownloadAdmin"', 1, 'Contenu', 9);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(10, 'mod_users', 1, '', 1, 1, '', '', 'Utilisateurs', 'users', 'href="admin.php?op=mod_users"', 2, 'Utilisateurs', 1);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(11, 'groupes', 1, '', 1, 1, '', '', 'Groupes', 'groupes', 'href="admin.php?op=groupes"', 2, 'Utilisateurs', 2);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(12, 'mod_authors', 1, '', 1, 1, '', '', 'Administrateurs', 'authors', 'href="admin.php?op=mod_authors"', 2, 'Utilisateurs', 3);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(13, 'MaintForumAdmin', 1, '', 1, 1, '', '', 'Maintenance Forums', 'forum', 'href="admin.php?op=MaintForumAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(14, 'ForumConfigAdmin', 1, '', 1, 1, '', '', 'Configuration Forums', 'forum', 'href="admin.php?op=ForumConfigAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(15, 'ForumAdmin', 1, '', 1, 1, '', '', 'Edition Forums', 'forum', 'href="admin.php?op=ForumAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(16, 'lnl', 1, '', 1, 1, '', '', 'Lettre D''info', 'lnl', 'href="admin.php?op=lnl"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(17, 'email_user', 1, '', 1, 1, '', '', 'Message Interne', 'email_user', 'href="admin.php?op=email_user"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(18, 'BannersAdmin', 1, '', 1, 1, '', '', 'Bannières', 'banner', 'href="admin.php?op=BannersAdmin"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(19, 'create', 1, '', 1, 1, '', '', 'Sondages', 'newpoll', 'href="admin.php?op=create"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(20, 'reviews', 1, '', 1, 1, '', '', 'Critiques', 'reviews', 'href="admin.php?op=reviews"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(21, 'hreferer', 1, '', 1, 1, '', '', 'Sites Référents', 'referer', 'href="admin.php?op=hreferer"', 3, 'Communication', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(22, 'blocks', 1, '', 1, 1, '', '', 'Blocs', 'block', 'href="admin.php?op=blocks"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(23, 'mblock', 1, '', 1, 1, '', '', 'Bloc Principal', 'blockmain', 'href="admin.php?op=mblock"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(24, 'ablock', 1, '', 1, 1, '', '', 'Bloc Administration', 'blockadm', 'href="admin.php?op=ablock"', 4, 'Interface', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(25, 'Configure', 1, '', 1, 1, '', '', 'Préférences', 'preferences', 'href="admin.php?op=Configure"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(26, 'ConfigFiles', 1, '', 1, 1, '', '', 'Fichiers configurations', 'preferences', 'href="admin.php?op=ConfigFiles"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(27, 'FileManager', 1, '', 1, 1, '', '', 'Gestionnaire Fichiers', 'filemanager', 'href="admin.php?op=FileManager"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(28, 'supercache', 1, '', 1, 1, '', '', 'SuperCache', 'overload', 'href="admin.php?op=supercache"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(29, 'OptimySQL', 1, '', 1, 1, '', '', 'OptimySQL', 'optimysql', 'href="admin.php?op=OptimySQL"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(30, 'SavemySQL', 1, '', 1, 1, '', '', 'SavemySQL', 'savemysql', 'href="admin.php?op=SavemySQL"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(31, 'MetaTagAdmin', 1, '', 1, 1, '', '', 'MétaTAGs', 'metatags', 'href="admin.php?op=MetaTagAdmin"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(32, 'MetaLangAdmin', 1, '', 1, 1, '', '', 'META-LANG', 'metalang', 'href="admin.php?op=Meta-LangAdmin"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(33, 'setban', 1, '', 1, 1, '', '', 'IP', 'ipban', 'href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ipban&amp;ModStart=setban"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(34, 'session_log', 1, '', 1, 1, '', '', 'Logs', 'logs', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=session-log&ModStart=session-log"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(36, 'mes_npds_versus', 1, '', 1, 1, '', 'Une nouvelle version est disponible ! Cliquez pour acc&#xE9;der &#xE0; la zone de t&#xE9;l&#xE9;chargement de NPDS.', '', 'message_npds', '', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(37, 'autoStory', 1, '', 1, 1, '1', 'articles sont programm&eacute;s pour la publication.', 'Auto-Articles', 'autonews', 'href="admin.php?op=autoStory"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(38, 'submissions', 1, '', 1, 1, '10', 'Article en attente de validation !', 'Articles', 'submissions', 'href="admin.php?op=submissions"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(39, 'hreferer_al', 1, '', 1, 0, '!!!', 'Limite des r&#xE9;f&#xE9;rants atteinte : pensez &#xE0; archiver vos r&#xE9;f&#xE9;rants.', 'Sites Référents', 'referer', 'href="admin.php?op=hreferer"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(49, 'npds_twi', 0, '', 1, 1, '', '', 'Npds_Twitter', 'npds_twi', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=npds_twi&ModStart=admin/npds_twi_set"', 6, 'Modules', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(62, 'mes_npds_3', 0, '', 1, 1, '', ' Alerte rouge Ã§a bug ..', '', 'flag_red', '', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(59, 'mes_npds_2', 0, '', 1, 1, '', 'Ceci est une note d''information provenant de NPDS.', '', 'flag_red', '', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(51, 'modules', 1, '', 1, 1, '', '', 'Gestion modules', 'modules', 'href="admin.php?op=modules"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(40, 'abla', 1, '', 1, 1, '', '', 'Blackboard', 'abla', 'href="admin.php?op=abla"', 5, 'Système', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(35, 'reviews', 1, '', 1, 0, '0', 'Critique en atttente de validation.', 'Critiques', 'reviews', 'href="admin.php?op=reviews"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(41, 'newlink', 1, '', 1, 1, '1', 'Lien &#xE0; valider', 'Lien', 'links', 'href="admin.php?op=links"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(42, 'brokenlink', 1, '', 1, 1, '6', 'Lien rompu &#xE0; valider', 'Lien rompu', 'links', 'href="admin.php?op=LinksListBrokenLinks"', 9, 'Alerte', 0);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(43, 'archive-stories', 1, '', 1, 1, '', '', 'Archives articles', 'archive-stories', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=archive-stories&ModStart=admin/archive-stories_set"', 1, 'Contenu', 4);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(74, 'reseaux-sociaux', 1, '', 1, 1, '', '', 'Réseaux sociaux', 'reseaux-sociaux', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=reseaux-sociaux&ModStart=admin/reseaux-sociaux_set"', 2, 'Utilisateurs', 4);
INSERT INTO fonctions (fid, fnom, fdroits1, fdroits1_descr, finterface, fetat, fretour, fretour_h, fnom_affich, ficone, furlscript, fcategorie, fcategorie_nom, fordre) VALUES(75, 'geoloc', 1, '', 1, 1, '', '', 'geoloc', 'geoloc', 'href="admin.php?op=Extend-Admin-SubModule&ModPath=geoloc&ModStart=admin/geoloc_set"', 6, 'Modules', 0);


CREATE TABLE forums (
  forum_id int(10) NOT NULL AUTO_INCREMENT,
  forum_name varchar(150) DEFAULT NULL,
  forum_desc text,
  forum_access int(10) DEFAULT '1',
  forum_moderator text,
  cat_id int(10) DEFAULT NULL,
  forum_type int(10) DEFAULT '0',
  forum_pass varchar(60) DEFAULT NULL,
  arbre tinyint(1) unsigned NOT NULL DEFAULT '0',
  attachement tinyint(1) unsigned NOT NULL DEFAULT '0',
  forum_index int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (forum_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO forums VALUES (1, 'Demo', '', 0, '2', 1, 0, '', 0, 0, 0);
INSERT INTO forums VALUES (2, 'Arbre', 'un forum &agrave; l''ancienne forme', 0, '2', 1, 0, '', 1, 0, 0);

CREATE TABLE forumtopics (
  topic_id int(10) NOT NULL AUTO_INCREMENT,
  topic_title varchar(100) DEFAULT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO forumtopics VALUES (1, 'Demo', 2, '2012-03-05 22:36:00', 27, 1, 0, 0, 2, 1);
INSERT INTO forumtopics VALUES (2, 'Message 1', 1, '2013-05-14 22:55:00', 8, 2, 0, 0, 1, 1);

CREATE TABLE forum_attachments (
  att_id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL DEFAULT '0',
  topic_id int(11) NOT NULL DEFAULT '0',
  forum_id int(11) NOT NULL DEFAULT '0',
  unixdate int(11) NOT NULL DEFAULT '0',
  att_name varchar(255) NOT NULL DEFAULT '',
  att_type varchar(64) NOT NULL DEFAULT '',
  att_size int(11) NOT NULL DEFAULT '0',
  att_path varchar(255) NOT NULL DEFAULT '',
  inline char(1) NOT NULL DEFAULT '',
  apli varchar(10) NOT NULL DEFAULT '',
  compteur int(11) NOT NULL DEFAULT '0',
  visible tinyint(1) NOT NULL DEFAULT '0',
  KEY att_id (att_id),
  KEY post_id (post_id),
  KEY topic_id (topic_id),
  KEY apli (apli),
  KEY visible (visible),
  KEY forum_id (forum_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO forum_read VALUES (1, 2, 2, 2, 1383416155, 1);
INSERT INTO forum_read VALUES (2, 1, 1, 2, 1383418761, 1);

CREATE TABLE groupes (
  groupe_id int(3) DEFAULT NULL,
  groupe_name varchar(30) NOT NULL DEFAULT '',
  groupe_description varchar(255) NOT NULL DEFAULT '',
  groupe_forum int(1) unsigned NOT NULL DEFAULT '0',
  groupe_mns int(1) unsigned NOT NULL DEFAULT '0',
  groupe_chat int(1) unsigned NOT NULL DEFAULT '0',
  groupe_blocnote int(1) unsigned NOT NULL DEFAULT '0',
  groupe_pad int(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY groupe_id (groupe_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE headlines (
  hid int(11) NOT NULL AUTO_INCREMENT,
  sitename varchar(30) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  headlinesurl varchar(200) NOT NULL DEFAULT '',
  status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (hid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO headlines VALUES (1, 'NPDS', 'http://www.npds.org', 'http://www.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (2, 'Modules', 'http://modules.npds.org', 'http://modules.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (3, 'Styles', 'http://styles.npds.org', 'http://styles.npds.org/backend.php', 0);

CREATE TABLE lblocks (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  content text,
  member varchar(60) NOT NULL DEFAULT '0',
  Lindex tinyint(4) NOT NULL DEFAULT '0',
  cache mediumint(8) unsigned NOT NULL DEFAULT '0',
  actif smallint(5) unsigned NOT NULL DEFAULT '1',
  css tinyint(1) NOT NULL DEFAULT '0',
  aide mediumtext,
  PRIMARY KEY (id),
  KEY Lindex (Lindex)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO lblocks VALUES (1, 'Un Bloc ...', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO lblocks VALUES (2, 'Menu', 'function#mainblock', '0', 1, 86400, 1, 0, 'Ce menu contient presque toutes les fonctions de base disponibles dans NPDS');
INSERT INTO lblocks VALUES (3, 'Msg &agrave; un Membre', 'function#instant_members_message', '0', 4, 0, 1, 0, '');
INSERT INTO lblocks VALUES (4, 'Chat Box', 'function#makeChatBox\r\nparams#chat_tous', '0', 2, 10, 1, 0, '');
INSERT INTO lblocks VALUES (5, 'Forums Infos', 'function#RecentForumPosts\r\nparams#Forums Infos,15,0,false,10,false,-:\r\n', '0', 5, 60, 1, 0, '');
INSERT INTO lblocks VALUES (6, 'Les plus t&eacute;l&eacute;charg&eacute;s', 'function#topdownload', '0', 6, 3600, 0, 0, '');
INSERT INTO lblocks VALUES (7, 'Administration', 'function#adminblock', '0', 3, 0, 1, 0, '');
INSERT INTO lblocks VALUES (8, 'Eph&eacute;m&eacute;rides', 'function#ephemblock', '0', 7, 28800, 0, 0, '');
INSERT INTO lblocks VALUES (9, 'headlines', 'function#headlines', '0', 9, 3600, 0, 0, '');
INSERT INTO lblocks VALUES (10, 'Activit&eacute; du Site', 'function#Site_Activ', '0', 8, 10, 1, 0, '');
INSERT INTO lblocks VALUES (11, 'Sondage', 'function#pollNewest', '0', 1, 60, 1, 0, '');
INSERT INTO lblocks VALUES (12, '[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese]', 'include#modules/geoloc/geoloc_bloc.php', '0', 0, 86400, 0, 0, '');

CREATE TABLE links_categories (
  cid int(11) NOT NULL AUTO_INCREMENT,
  title varchar(250) DEFAULT NULL,
  cdescription text NOT NULL,
  PRIMARY KEY (cid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO links_categories VALUES (1, 'Mod&eacute;le', '');

CREATE TABLE links_editorials (
  linkid int(11) NOT NULL DEFAULT '0',
  adminid varchar(60) NOT NULL DEFAULT '',
  editorialtimestamp datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (linkid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE links_links (
  lid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  description text NOT NULL,
  date datetime DEFAULT NULL,
  name varchar(60) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  hits int(11) NOT NULL DEFAULT '0',
  submitter varchar(60) NOT NULL DEFAULT '',
  linkratingsummary double(6,4) NOT NULL DEFAULT '0.0000',
  totalvotes int(11) NOT NULL DEFAULT '0',
  totalcomments int(11) NOT NULL DEFAULT '0',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE links_modrequest (
  requestid int(11) NOT NULL AUTO_INCREMENT,
  lid int(11) NOT NULL DEFAULT '0',
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL DEFAULT '',
  brokenlink int(3) NOT NULL DEFAULT '0',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (requestid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE links_newlink (
  lid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  sid int(11) NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  description text NOT NULL,
  name varchar(60) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  submitter varchar(60) NOT NULL DEFAULT '',
  topicid_card tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE links_subcategories (
  sid int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT '0',
  title varchar(250) DEFAULT NULL,
  PRIMARY KEY (sid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE lnl_body (
  ref int(11) NOT NULL AUTO_INCREMENT,
  html char(1) NOT NULL DEFAULT '1',
  text text,
  status char(3) NOT NULL DEFAULT 'stb',
  PRIMARY KEY (ref)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE lnl_head_foot (
  ref int(11) NOT NULL AUTO_INCREMENT,
  type char(3) NOT NULL DEFAULT '',
  html char(1) NOT NULL DEFAULT '1',
  text text,
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (ref)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE lnl_outside_users (
  email varchar(60) NOT NULL DEFAULT '',
  host_name varchar(60) DEFAULT NULL,
  date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE lnl_send (
  ref int(11) NOT NULL AUTO_INCREMENT,
  header int(11) NOT NULL DEFAULT '0',
  body int(11) NOT NULL DEFAULT '0',
  footer int(11) NOT NULL DEFAULT '0',
  number_send int(11) NOT NULL DEFAULT '0',
  type_send char(3) NOT NULL DEFAULT 'ALL',
  date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status char(3) NOT NULL DEFAULT 'OK',
  PRIMARY KEY (ref)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE mainblock (
  title varchar(255) DEFAULT NULL,
  content text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO mainblock VALUES ('Menu', '<ul><li><a href="modules.php?ModPath=archive-stories&ModStart=archive-stories">Nouvelles</a></li><li><a href="forum.php">Forums</a></li><li><a href="sections.php">Rubriques</a></li><li><a href="topics.php">Sujets actifs</a></li><li><a href="modules.php?ModPath=links&ModStart=links">Liens Web</a></li><li><a href="download.php">Downloads</a></li><li><a href="faq.php">FAQ</a></li><li><a href="static.php?op=statik.txt&npds=1">Page statique</a></li><li><a href="reviews.php">Critiques</a></li><li><a href="memberslist.php">Annuaire</a></li><li><a href="map.php">Plan du site</a></li></ul><ul><li><a href="friend.php">Faire notre pub</a></li><li><a href="user.php">Votre compte</a></li><li><a href="submit.php">Nouvel article</a></li></ul><ul><li><a href="admin.php">Administration</a></li></ul>');

CREATE TABLE metalang (
  def varchar(50) NOT NULL DEFAULT '',
  content text NOT NULL,
  type_meta varchar(4) NOT NULL DEFAULT 'mot',
  type_uri char(1) NOT NULL DEFAULT '-',
  uri varchar(255) DEFAULT NULL,
  description text,
  obligatoire char(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (def),
  KEY type_meta (type_meta)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bargif!', '$cmd=$GLOBALS[''bargif''];', 'meta', '-', NULL, '[french]variable global $bargif[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!theme!', '$cmd=$GLOBALS[''theme''];', 'meta', '-', NULL, '[french]variable global $theme[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sitename!', '$cmd=$GLOBALS[''sitename''];', 'meta', '-', NULL, '[french]variable global $sitename[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor1!', '$cmd=$GLOBALS[''bgcolor1''];', 'meta', '-', NULL, '[french]variable global $bgcolor1 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor2!', '$cmd=$GLOBALS[''bgcolor2''];', 'meta', '-', NULL, '[french]variable global $bgcolor2 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor3!', '$cmd=$GLOBALS[''bgcolor3''];', 'meta', '-', NULL, '[french]variable global $bgcolor3 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor4!', '$cmd=$GLOBALS[''bgcolor4''];', 'meta', '-', NULL, '[french]variable global $bgcolor4 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor5!', '$cmd=$GLOBALS[''bgcolor5''];', 'meta', '-', NULL, '[french]variable global $bgcolor5 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!bgcolor6!', '$cmd=$GLOBALS[''bgcolor6''];', 'meta', '-', NULL, '[french]variable global $bgcolor6 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!textcolor1!', '$cmd=$GLOBALS[''textcolor1''];', 'meta', '-', NULL, '[french]variable global $textcolor1 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!textcolor2!', '$cmd=$GLOBALS[''textcolor2''];', 'meta', '-', NULL, '[french]variable global $textcolor2 (ancien theme)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!opentable!', '$cmd=sub_opentable();', 'meta', '-', '', '[french]Appel la fonction OpenTable de NPDS[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!closetable!', '$cmd=sub_closetable();', 'meta', '-', '', '[french]Appel la fonction CloseTable de NPDS[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('Scalcul', 'function MM_Scalcul($opex,$premier,$deuxieme) {\n   if ($opex=="+") {$tmp=$premier+$deuxieme;}\n   if ($opex=="-") {$tmp=$premier-$deuxieme;}\n   if ($opex=="*") {$tmp=$premier*$deuxieme;}\n   if ($opex=="/") {\n      if ($deuxieme==0) {\n         $tmp="Division by zero !";\n      } else {\n         $tmp=$premier/$deuxieme;\n      }\n   }\n   return ($tmp);\n}', 'meta', '-', NULL, '[french]Retourne la valeur du calcul : syntaxe Scalcul(op,nombre,nombre) ou op peut &ecirc;tre : + - * /[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!anti_spam!', 'function MM_anti_spam ($arg) {\n   return("<a href=\\"mailto:".anti_spam($arg, 1)."\\" class=\\"noir\\" target=\\"_blank\\">".anti_spam($arg, 0)."</a>");\n}', 'meta', '-', NULL, '[french]Encode un email et cr&eacute;e un &lta href=mailto ...&gtEmail&lt/a&gt[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!msg_foot!', 'function MM_msg_foot () {\n   global $foot1, $foot2, $foot3, $foot4;\n\n   if ($foot1) $MT_foot=stripslashes($foot1)."<br />";\n   if ($foot2) $MT_foot.=stripslashes($foot2)."<br />";\n   if ($foot3) $MT_foot.=stripslashes($foot3)."<br />";\n   if ($foot4) $MT_foot.=stripslashes($foot4);\n   return (aff_langue($MT_foot));\n}', 'meta', '-', NULL, '[french]Gestion des messages du footer du theme (les 4 pieds de page dans Admin / Pr&eacute;f&eacute;rences)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!date!', 'function MM_date () {\n   global $locale, $gmt;\n\n   setlocale (LC_TIME, aff_langue($locale));\n   $MT_date=strftime(translate("daydate"),time()+((integer)$gmt*3600));\n   return ($MT_date);\n}', 'meta', '-', NULL, '[french]Date du jour - se base sur le format de daydate (fichier de traduction)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!banner!', 'function MM_banner () {\n   global $banners, $hlpfile;\n\n   if (($banners) and (!$hlpfile)) {\n      ob_start();\n      include("banners.php");\n      $MT_banner=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $MT_banner="";\n   }\n   return ($MT_banner);\n}', 'meta', '-', NULL, '[french]Syst&egrave;me de banni&egrave;re[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!search_topics!', 'function MM_search_topics() {\n   global $NPDS_Prefix;\n\n   $MT_search_topics="<form action=\\"search.php\\" method=\\"post\\"><b>".translate("Topics")." </b>";\n   $MT_search_topics.="<select class=\\"textbox_standard\\" name=\\"topic\\"onChange=''submit()''>" ;\n   $MT_search_topics.="<option value=\\"\\">".translate("All Topics")."</option>\\n";\n\n   $rowQ=Q_select("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext", 86400);\n   while(list(,$myrow) = each($rowQ)) {\n     $MT_search_topics.="<option value=\\"".$myrow[''topicid'']."\\">".aff_langue($myrow[''topictext''])."</option>\\n";\n   }\n   $MT_search_topics.="</select></form>";\n   return ($MT_search_topics);\n}', 'meta', '-', NULL, '[french]Liste des Topic => Moteur de recherche interne (Combo)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!search!', 'function MM_search() {\n   $MT_search="<form action=\\"search.php\\" method=\\"post\\"><b>".translate("Search")."</b>\n   <input class=\\"textbox_standard\\" type=\\"name\\" name=\\"query\\" size=\\"10\\"></form>";\n   return ($MT_search);\n}', 'meta', '-', NULL, '[french]Ligne de saisie => Moteur de recherche[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!member!', 'function MM_member() {\n   global $cookie, $anonymous;\n\n   $username = $cookie[1];\n   if ($username=="") {$username=$anonymous;}\n   ob_start();Mess_Check_Mail($username);\n      $MT_member=ob_get_contents();\n   ob_end_clean();\n   return ($MT_member);\n}', 'meta', '-', NULL, '[french]Ligne Anonyme / membre gestion du compte / Message Interne (MI)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!nb_online!', 'function MM_nb_online() {\n   list($MT_nb_online, $MT_whoim)=Who_Online();\n   return ($MT_nb_online);\n}', 'meta', '-', NULL, '[french]Nombre de session active[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!whoim!', 'function MM_whoim() {\r\n   list($MT_nb_online, $MT_whoim)=Who_Online();\r\n   return ($MT_whoim);\r\n}', 'meta', '-', NULL, '[french]Affiche Qui est en ligne ? + message de bienvenue[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!membre_nom!', 'function MM_membre_nom() {\n   global $NPDS_Prefix,$cookie;\n\n   $uname=arg_filter($cookie[1]);\n   $MT_name="";\n   $rowQ = Q_select("select name from ".$NPDS_Prefix."users where uname=''$uname''", 3600);\n   list(,$myrow) = each($rowQ);\n   $MT_name=$myrow[''name''];\n   return ($MT_name);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du nom du membre ou rien[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!membre_pseudo!', 'function MM_membre_pseudo() {\n   global $cookie;\n\n   return ($cookie[1]);\n}', 'meta', '-', NULL, '[french]Alias de !membre_nom![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('blocID', 'function MM_blocID($arg) {\n  return(@oneblock(substr($arg,1), substr($arg,0,1)."B"));\n}', 'meta', '-', NULL, '[french]Fabrique un bloc R (droite) ou L (gauche) en s''appuyant sur l''ID (voir gestionnaire de blocs) pour incorporation / syntaxe : blocID(R1) ou blocID(L2)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!block!', 'function MM_block($arg) {\n   return (meta_lang("blocID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de blocID()[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!leftblocs!', 'function MM_leftblocs() {\n   ob_start();\n      leftblocks();\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de gauche[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!rightblocs!', 'function MM_rightblocs() {\n   ob_start();\n      rightblocks();\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de droite[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('articleID', 'function MM_articleID($arg) {\n   global $NPDS_Prefix;\n   global $nuke_url;\n\n   $arg = arg_filter($arg);\n   $rowQ = Q_select("SELECT title FROM ".$NPDS_Prefix."stories where sid=''$arg''", 3600);\n   list(,$myrow) = each($rowQ);\n   return ("<a href=\\"$nuke_url/article.php?sid=$arg\\" class=\\"noir\\">".$myrow[''title'']."</a>");\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du titre et fabrication d''une url pointant sur l''article (ID)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!article!', 'function MM_article($arg) {\n   return (meta_lang("articleID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias d''articleID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('article_completID', 'function MM_article_completID($arg) {\n   if ($arg>0) {\n      $story_limit=1;\n      $news_tab=prepa_aff_news("article",$arg,"");\n   } else {\n      $news_tab=prepa_aff_news("index","","");\n      $story_limit=abs($arg)+1;\n   }\n   $aid=unserialize($news_tab[$story_limit][''aid'']);\n   $informant=unserialize($news_tab[$story_limit][''informant'']);\n   $datetime=unserialize($news_tab[$story_limit][''datetime'']);\n   $title=unserialize($news_tab[$story_limit][''title'']);\n   $counter=unserialize($news_tab[$story_limit][''counter'']);\n   $topic=unserialize($news_tab[$story_limit][''topic'']);\n   $hometext=unserialize($news_tab[$story_limit][''hometext'']);\n   $notes=unserialize($news_tab[$story_limit][''notes'']);\n   $morelink=unserialize($news_tab[$story_limit][''morelink'']);\n   $topicname=unserialize($news_tab[$story_limit][''topicname'']);\n   $topicimage=unserialize($news_tab[$story_limit][''topicimage'']);\n   $topictext=unserialize($news_tab[$story_limit][''topictext'']);\n   $s_id=unserialize($news_tab[$story_limit][''id'']);\n   if ($aid) {\n      ob_start();\n         themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);\n         $remp=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $remp="";\n   }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration de l''article complet (ID) et themisation pour incorporation<br />si ID > 0   : l''article publi&eacute; avec l''ID indiqu&eacute;e<br />si ID = 0   : le dernier article publi&eacute;<br />si ID = -1  : l''avant dernier ... jusqu''&agrave; -9 (limite actuelle)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!article_complet!', 'function MM_article_complet($arg) {\n   return (meta_lang("article_completID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de article_completID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('headlineID', 'function MM_headlineID($arg) {\n   return (@headlines($arg,""));\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du canal RSS (ID) et fabrication d''un retour pour affichage[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!headline!', 'function MM_headline($arg) {\n   return (meta_lang("headlineID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de headlineID[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!list_mns!', 'function MM_list_mns() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users WHERE mns=''1''");\n   $MT_mns="<table width=\\"100%\\">";\n   while (list($uname)=sql_fetch_row($query)) {\n      $rowcolor=tablos();\n      $MT_mns.="<tr $rowcolor><td><a href=\\"minisite.php?op=$uname\\" target=\\"_blank\\" class=\\"noir\\">$uname</a></td></tr>";\n   }\n   $MT_mns.="</table>";\n   return ($MT_mns);\n}', 'meta', '-', NULL, '[french]Affiche une liste de tout les membres poss&eacute;dant un minisite avec un lien vers ceux-ci[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!LastMember!', 'function MM_LastMember() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users ORDER BY uid DESC LIMIT 0,1");\n   $result=sql_fetch_row($query);\n   return ($result[0]);\n}', 'meta', '-', NULL, '[french]Renvoie le pseudo du dernier membre inscrit[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!edito!', 'function MM_edito() {\n   list($affich,$M_edito)=fab_edito();\n   if ((!$affich) or ($M_edito=="")) {\n      $M_edito="";\n   }\n   return ($M_edito);\n}', 'meta', '-', NULL, '[french]Fabrique et affiche l''EDITO[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!edito-notitle!', '$cmd="!edito-notitle!";', 'meta', '-', NULL, '[french]Supprime le Titre EDITO et le premier niveau de tableau dans l''edito (ce meta-mot n''est actif que dans l''Edito)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!langue!', '$cmd=aff_local_langue("","index.php", "choice_user_language");', 'meta', '-', NULL, '[french]Fabrique une zone de selection des langues disponibles[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('groupe_text', 'function MM_groupe_text($arg) {\n   global $user;\n\n   $affich=false;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=true;\n   } else {\n      if ($user)\n         $affich=true;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Test si le membre appartient aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par groupe_textID(ID_group) ... !/!<br />Si groupe_ID est nul, la v&eacute;rification portera simplement sur la qualit&eacute; de membre<br />Syntaxe : groupe_text(), groupe_text(10) ou groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('no_groupe_text', 'function MM_no_groupe_text($arg) {\n   global $user;\n\n   $affich=true;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=false;\n      if (!$user)\n         $affich=false;\n   } else {\n      if ($user)\n         $affich=false;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Forme de ELSE de groupe_text / Test si le membre n''appartient pas aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par no_groupe_textID(ID_group) ... !/!<br />Si no_groupe_ID est nul, la v&eacute;rification portera sur qualit&eacute; d''anonyme<br />Syntaxe : no_groupe_text(), no_groupe_text(10) ou no_groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!note!', 'function MM_note() {\n   return ("!delete!");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera jamais affich&eacute;e !note! .... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!note_admin!', 'function MM_note_admin() {\n   global $admin;\n\n   if (!$admin)\n      return ("!delete!");\n   else\n      return("<b>nota</b> : ");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera affich&eacute;e que pour les administrateurs !note_admin! .... !/![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!/!', '!\\/!', 'meta', '-', NULL, '[french]Termine LES meta-mot ENCADRANTS (!groupe_text!, !note!, !note_admin!, ...) : le fonctionnement est assez similaire &agrave; [langue] ...[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!debugON!', 'function MM_debugON() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $NPDS_debug_cycle=1;\n   $NPDS_debug=true;\n   $NPDS_debug_str="<br />";\n   $NPDS_debug_time=getmicrotime();\n   return ("");\n}', 'meta', '-', NULL, '[french]Active le mode debug[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!debugOFF!', 'function MM_debugOFF() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $time_end = getmicrotime();\n   $NPDS_debug_str.="=> !DebugOFF!<br /><b>=> exec time for meta-lang : ".round($time_end - $NPDS_debug_time, 4)." / cycle(s) : $NPDS_debug_cycle</b><br />";\n   $NPDS_debug=false;\n   echo $NPDS_debug_str;\n   return ("");\n}', 'meta', '-', NULL, '[french]D&eacute;sactive le mode debug[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_all', 'function MM_forum_all() {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories ORDER BY cat_id", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche toutes les categories et tous les forums (en fonction des droits)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_categorie', 'function MM_forum_categorie($arg) {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $bid_tab=explode(",",$arg); $sql="";\n   foreach($bid_tab as $cat) {\n      $sql.="cat_id=''$cat'' OR ";\n   }\n   $sql=substr($sql,0,-4);\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories WHERE $sql", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]affiche la (les) categorie(s) XX (en fonction des droits) / liste de categories : "XX,YY,ZZ" [/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_message', 'function MM_forum_message() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   if (($subscribe) and ($user)) {\n      $colspanX=8;\n   } else {\n      $colspanX=7;\n   }\n   if (!$user) {\n      $ibid="<tr align=\\"left\\" class=\\"lignb\\">";\n      $ibid.="<td colspan=\\"$colspanX\\" align=\\"center\\" style=\\"font-size: 10px;\\">".translate("Join us ! As a registered user, cool stuff like : forum''subscribing, special forums (hidden, members ...), post and read status, ... are avaliable.")."</td></tr>";\n   }\n   if (($subscribe) and ($user)) {\n      $ibid="<tr align=\\"left\\" class=\\"lignb\\">";\n      $ibid.="<td colspan=\\"$colspanX\\" align=\\"center\\" style=\\"font-size: 10px;\\">".translate("Check a forum and click on button for receive an Email when a new submission is made in it.")."</td></tr>";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les messages en pied de forum (devenez membre, abonnement ...)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_recherche', 'function MM_forum_recherche() {\n   include_once("functions.php");\n\n   $Xcontent=@searchblock();\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche la zone de saisie du moteur de recherche des forums[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_icones', 'function MM_forum_icones() {\n   include_once("functions.php");\n\n   if ($ibid=theme_image("forum/icons/red_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_folder.gif";}\n   if ($ibid=theme_image("forum/icons/folder.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/folder.gif";}\n   $ibid="<img src=\\"$imgtmpR\\" border=\\"\\" alt=\\"\\" /> = ".translate("New Posts since your last visit.")."<br />";\n   $ibid.="<img src=\\"$imgtmp\\" border=\\"\\" alt=\\"\\" /> = ".translate("No New Posts since your last visit.");\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les icones + legendes decrivant les marqueurs des forums[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subscribeON', 'function MM_forum_subscribeON() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $ibid="<form action=\\"forum.php\\" method=\\"post\\">";\n      $ibid.="<input type=\\"hidden\\" name=\\"op\\" value=\\"maj_subscribe\\">";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_bouton_subscribe', 'function MM_forum_bouton_subscribe() {\r\n   include_once("functions.php");\r\n   global $subscribe, $user;\r\n   if (($subscribe) and ($user)) {\r\n      return (''<th align="center" width="5%" class="header"><input class="btn btn-secondary" type="submit" name="Xsub" value="''.translate("Ok").''"></th>'');\r\n   } else {\r\n      return ('''');\r\n   }\r\n}', 'meta', '-', NULL, '[french]Affiche la colonne avec le bouton de gestion des abonnements[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subscribeOFF', 'function MM_forum_subscribeOFF() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $ibid="</form>";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('forum_subfolder', 'function MM_forum_subfolder($arg) {\r\n\r\n   $forum=arg_filter($arg);\r\n   $content=sub_forum_folder($forum);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Renvoie le gif permettant de savoir si de nouveaux messages sont disponibles dans le forum X<br />Syntaxe : sub_folder(X) ou X est le num&eacute;ro du forum[/french][english][/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('insert_flash', 'function MM_insert_flash($name,$width,$height,$bgcol) {\n   return ("<object codebase=\\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflas\n   classid=\\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\\"\n   h.cab#version=6,0,0,0\\" width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   id=\\"".$name."\\" align=\\"middle\\">\n \n   <param name=\\"allowScriptAccess\\"\n   value=\\"sameDomain\\" />\n \n   <param name=\\"movie\\"\n   value=\\"flash/".$name."\\" />\n \n   <param name=\\"quality\\" value=\\"high\\" />\n   <param name=\\"bgcolor\\"\n   value=\\"".$bgcol."\\" />\n\n   <embed src=\\"flash/".$name."\\"\n   quality=\\"high\\" bgcolor=\\"".$bgcol."\\"\n   width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   name=\\"".$name."\\" align=\\"middle\\"\n   allowScriptAccess=\\"sameDomain\\"\n   type=\\"application/x-shockwave-flash\\"\n   pluginspage=\\"http://www.macromedia.com/go/getflashplayer\\" />\n\n   </object>");\n}', 'meta', '-', NULL, '[french]Insert un fichier flash (.swf) se trouvant dans un dossier "flash" de la racine du site. Syntaxe : insert_flash (nom du fichier.swf, largeur, hauteur, couleur fond : #XXYYZZ).[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!mailadmin!', '$cmd="<a href=\\"mailto:".anti_spam($GLOBALS[''adminmail''], 1)."\\" class=\\"noir\\" target=\\"_blank\\">".anti_spam($GLOBALS[''adminmail''], 0)."</a>";', 'meta', '-', NULL, '[french]Affiche un lien vers l''adresse mail de l''administrateur.[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!login!', 'function MM_login() {\r\n   $boxstuff = ''\r\n   <div class="card card-body m-3">\r\n      <h3><a href="user.php?op=only_newuser" role="button" title="''.translate("New User").''"><i class="fa fa-user-plus"></i>&nbsp;''.translate("New User").''</a></h3>\r\n   </div>\r\n   <div class="card card-body m-3">\r\n      <h3><i class="fa fa-sign-in fa-lg"></i>&nbsp;''.translate("Connection").''</h3>\r\n      <form action="user.php" method="post" name="userlogin">\r\n         <div class="form-group row">\r\n            <label for="inputuser" class="form-control-label col-sm-4">''.translate("Nickname").''</label>\r\n            <div class="col-sm-8">\r\n               <input type="text" class="form-control" name="uname" id="inputuser" placeholder="''.translate("Nickname").''">\r\n            </div>\r\n         </div>\r\n         <div class="form-group row">\r\n            <label for="inputPassuser" class="form-control-label col-sm-4">''.translate("Password").''</label>\r\n            <div class="col-sm-8">\r\n               <input type="password" class="form-control" name="pass" id="inputPassuser" placeholder="''.translate("Password").''">\r\n               <span class="help-block small"><a href="user.php?op=forgetpassword" role="button" title="''.translate("Lost your Password?").''">''.translate("Lost your Password?").''</a></span>\r\n            </div>\r\n         </div>\r\n         <input type="hidden" name="op" value="login" />\r\n         <div class="form-group row">\r\n         <div class="col-sm-8 ml-sm-auto">\r\n            <button class="btn btn-primary" type="submit" title="''.translate("Submit").''">''.translate("Submit").''</button>\r\n         </div>\r\n         </div>\r\n      </form>\r\n   </div>'';\r\n   return ($boxstuff);\r\n}', 'meta', '-', NULL, '[french]Affiche les champs de connexion au site.[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!connexion!', '$cmd=meta_lang("!login!");', 'meta', '-', NULL, '[french]Alias de !login![/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!administration!', 'function MM_administration() {\n   global $admin;\n   if ($admin) {\n      return("<a href=\\"admin.php\\">".translate("Administration Tools")."</a>");\n   } else {\n      return("");\n   }\n}', 'meta', '-', NULL, '[french]Affiche un lien vers l''administration du site uniquement si l''on est connect&eacute; en tant qu''admin[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('admin_infos', 'function MM_admin_infos($arg) {\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $rowQ1 = Q_select ("SELECT url, email FROM ".$NPDS_Prefix."authors where aid=''$arg''", 86400);\n   list(,$myrow) = each($rowQ1);\n   if (isset($myrow[''url''])) {\n      $auteur="<a href=\\"".$myrow[''url'']."\\">$arg</a>";\n   } elseif (isset($myrow[''email''])) {\n      $auteur="<a href=\\"mailto:".$myrow[''email'']."\\">$arg</a>";\n   } else {\n      $auteur=$arg;\n   }\n   return ($auteur);\n}', 'meta', '-', NULL, '[french]Affiche le Nom ou le WWW ou le Mail de l''administrateur / syntaxe : admin_infos(nom_de_admin)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('theme_img', 'function MM_theme_img($arg) {\n   return (MM_img($arg));\n}', 'meta', '-', NULL, '[french]Localise l''image et affiche une ressource de type &lt;img src= / syntaxe : theme_img(forum/onglet.gif)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!logo!', '$cmd="<img src=\\"".$GLOBALS[''site_logo'']."\\" border=\\"0\\" alt=\\"\\">";', 'meta', '-', NULL, '[french]Affiche le logo du site (admin/preferences).[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('rotate_img', 'function MM_rotate_img($arg) {\r\n   mt_srand((double)microtime()*1000000);\r\n   $arg = arg_filter($arg);\r\n   $tab_img=explode(",",$arg);\r\n\r\nif (count($tab_img)>1) {\r\n   $imgnum = mt_rand(0, count($tab_img)-1);\r\n} else if (count($tab_img)==1) {\r\n   $imgnum = 0;\r\n} else {\r\n   $imgnum = -1;\r\n}\r\nif ($imgnum!=-1) {\r\n   $Xcontent="<img src=\\"".$tab_img[$imgnum]."\\" border=\\"0\\" alt=\\"".$tab_img[$imgnum]."\\" title=\\"".$tab_img[$imgnum]."\\" />";\r\n}\r\n   return ($Xcontent);\r\n}', 'meta', '-', NULL, '[french]Affiche une image al&eacute;atoire - les images de la liste sont s&eacute;par&eacute;e par une virgule / syntaxe rotate_img("http://www.npds.org/users_private/user/1.gif,http://www.npds.org/users_private/user/2.gif, ...")[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!sql_nbREQ!', 'function MM_sql_nbREQ() {\r\n   global $sql_nbREQ;\r\n\r\n   return ("SQL REQ : $sql_nbREQ");\r\n}', 'meta', '-', NULL, '[french]Affiche le nombre de requ&ecirc;te SQL pour la page courante[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('comment_system', 'function MM_comment_system ($file_name,$topic) {\r\n\r\nglobal $NPDS_Prefix,$anonpost,$moderate,$admin,$user;\r\nob_start();   \r\n   if (file_exists("modules/comments/$file_name.conf.php")) {\r\n      include ("modules/comments/$file_name.conf.php");\r\n      include ("modules/comments/comments.php");\r\n   }\r\n   $output = ob_get_contents();\r\nob_end_clean();\r\nreturn ($output);\r\n}', 'meta', '-', '', '[french]Permet de mettre en oeuvre un syst&egrave;me de commentaire complet / la mise en oeuvre n&eacute;cessite :<br /> - un fichier dans modules/comments/xxxx.conf.php de la m&ecirc;me structure que les autres<br /> - un appel coh&eacute;rent avec la configuration de ce fichier<br /><br />L''appel est du type : comments($file_name, $topic) - exemple comment_system(edito,1) - le fichier s''appel donc edito.conf.php[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_stories', 'function MM_top_stories ($arg) {\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $xtab=news_aff("libre","ORDER BY counter DESC LIMIT 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($counter>0) {\r\n        $content.=''<li class=""><a href="article.php?sid=''.$sid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($counter).'' ''.translate("times").''</span></li>'';\r\n     }\r\n   }\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles / syntaxe : top_stories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_commented_stories', 'function MM_top_commented_stories ($arg) {\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $xtab=news_aff("libre","ORDER BY comments DESC  LIMIT 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($comments>0) {\r\n         $content.= ''<li class=""><a href="article.php?sid=''.$sid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($comments).''</span></li>'';\r\n      }\r\n   }\r\n  return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles les plus comment&eacute;s / syntaxe : top_commented_stories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_categories', 'function MM_top_categories($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("select catid, title, counter from ".$NPDS_Prefix."stories_cat order by counter DESC limit 0,$arg");\r\n   while (list($catid, $title, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $content.= ''<li class=""><a href="index.php?op=newindex&amp;catid=''.$catid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x cat&eacute;gories des articles / syntaxe : top_categories(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_sections', 'function MM_top_sections ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT artid, title, counter FROM ".$NPDS_Prefix."seccont ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($artid, $title, $counter) = sql_fetch_row($result)) {\r\n      $content.=''<li class=""><a href="sections.php?op=viewarticle&amp;artid=''.$artid.''" >''.aff_langue($title).''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($counter).'' ''.translate("times").''</span></li>'';\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles des rubriques / syntaxe : top_sections(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_reviews', 'function MM_top_reviews ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT id, title, hits FROM reviews ORDER BY hits DESC LIMIT 0,$arg");\r\n   while (list($id, $title, $hits) = sql_fetch_row($result)) {\r\n      if ($hits>0) {\r\n         $content.= ''<li class=""><a href="reviews.php?op=showcontent&amp;id=''.$id.''" >''.$title.''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($hits).'' ''.translate("times").''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des critiques / syntaxe : top_reviews(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_authors', 'function MM_top_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT aid, counter FROM authors ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($aid, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $content.= ''<li class=""><a href="search.php?query=&amp;author=''.$aid.''" >''.$aid.''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs / syntaxe : top_authors(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_polls', 'function MM_top_polls ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT pollID, pollTitle, voters FROM poll_desc ORDER BY voters DESC LIMIT 0,$arg");\r\n   while (list($pollID, $pollTitle, $voters) = sql_fetch_row($result)) {\r\n      if ($voters>0) {\r\n         $content.=''<li class=""><a href="pollBooth.php?op=results&amp;pollID=''.$pollID.''" >''.aff_langue($pollTitle).''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($voters).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des sondages / syntaxe : top_polls(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('top_storie_authors', 'function MM_top_storie_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $content='''';\r\n   $arg = arg_filter($arg);\r\n   $result = sql_query("SELECT uname, counter FROM users ORDER BY counter DESC LIMIT 0,$arg");\r\n   while (list($uname, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $rowcolor = tablos();\r\n         $content.=''<li class=""><a href="user.php?op=userinfo&amp;uname=''.$uname.''" >''.$uname.''</a>&nbsp;<span class="badge badge-secondary float-right">''.wrh($counter).''</span></li>'';\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs de news (membres) / syntaxe : top_storie_authors(x)[/french]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_all', 'function MM_topic_all() {\r\n   global $NPDS_Prefix, $tipath;\r\n\r\n   $count=0; $aff='''';\r\n   $aff=''<div id="lst_topi" class="list-group" >'';\r\n   $result = sql_query("SELECT topicid, topicname, topicimage, topictext FROM ".$NPDS_Prefix."topics ORDER BY topicname");\r\n   while(list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {\r\n      if ((($topicimage) or ($topicimage!="")) and (file_exists("$tipath$topicimage"))) {\r\n         $aff.="<a class=\\"list-group-item\\" href=\\"index.php?op=newtopic&amp;topic=$topicid\\"><img class=\\"img-fluid\\" src=\\"$tipath$topicimage\\" alt=\\"topic_icon\\" />";\r\n         $aff.= ''<h4>''.aff_langue($topictext).''</h4>'';\r\n      } else {\r\n         $aff.="<a class=\\"list-group-item\\" href=\\"index.php?op=newtopic&amp;topic=$topicid\\"><h4>".aff_langue($topictext).''</h4>'';\r\n      }\r\n      $aff.=''</a>'';\r\n      $count++;\r\n   }\r\n   $aff.=''</div>'';\r\n   sql_free_result($result);\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les sujets avec leurs images.<br />Syntaxe : topic_all()[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribeOFF', 'function MM_topic_subscribeOFF() {\r\n   $aff= ''<div class=\\"form-group row\\"><input type="hidden" name="op" value="maj_subscribe" />'';\r\n   $aff.=''<button class="btn btn-primary" type="submit" name="ok">''.translate("Submit").''</button>'';\r\n   $aff.=''</div></fieldset></form>'';\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribeON', 'function MM_topic_subscribeON() {\r\n   return (''<form action="topics.php" method="post"><fieldset>'');\r\n}', 'meta', '-', '', '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('topic_subscribe', 'function MM_topic_subscribe($arg) {\r\n   global $NPDS_Prefix, $subscribe, $user, $cookie;\r\n   $segment = arg_filter($arg);\r\n   $count=0; $aff='''';\r\n   if ($subscribe) {\r\n      if ($user) {\r\n         $aff=''<div class="form-group row">'';\r\n         $result = sql_query("SELECT topicid, topictext, topicname FROM ".$NPDS_Prefix."topics ORDER BY topicname");\r\n         while(list($topicid, $topictext, $topicname) = sql_fetch_row($result)) {\r\n            $resultX = sql_query("SELECT topicid FROM ".$NPDS_Prefix."subscribe WHERE uid=''$cookie[0]'' AND topicid=''$topicid''");\r\n            if (sql_num_rows($resultX)=="1")\r\n               $checked=''checked'';\r\n            else\r\n               $checked='''';\r\n            $aff.="<div class=\\"".$segment."\\"><label class=\\"custom-control custom-checkbox\\"><input type=\\"checkbox\\" class=\\"custom-control-input\\" name=\\"Subtopicid[$topicid]\\" $checked /><span class=\\"custom-control-indicator\\"></span><span class=\\"custom-control-description\\">".aff_langue($topicname)."</span></label></div>";\r\n            $count++;\r\n            if ($count==$segment) {\r\n               //$aff.="</tr><tr>";\r\n               $count=0;\r\n            }\r\n         }\r\n         $aff.=''</div>'';\r\n         sql_free_result($result);\r\n      }\r\n   }\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les noms des sujets avec la situation de l''abonnement du membre. Permet au membre de g&eacute;rer ces abonnements (aux sujets).\r\nSyntaxe : topic_subscribe(X) ou X indique le niveau de rupture dans la liste[/french][english]...[/english]', '1');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('yt_video', 'function MM_yt_video($id_yt_video) {\r\n   $content="";\r\n   $id_yt_video = arg_filter($id_yt_video);\r\n\r\n   $content .=''<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/''.$id_yt_video.''" allowfullscreen="" frameborder="0"></iframe></div>'';\r\n   return ($content);\r\n}', 'meta', '-', '', '[french]Inclusion video YouTube.  Syntaxe : yt_video(ID de la vid&eacute;o).[/french][english]...[/english]', '1');
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
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('np_twi_Us', 'function MM_np_twi_Us($twi_Us_us,$twi_Us_nb,$twi_Us_time,$twi_Us_color,$twi_Us_dim,$twi_Us_other){\r\n  $twi_Us_us = arg_filter($twi_Us_us);\r\n  $twi_Us_nb = arg_filter($twi_Us_nb);\r\n  $twi_Us_time = arg_filter($twi_Us_time);\r\n  $twi_Us_color = arg_filter($twi_Us_color);\r\n  $twi_Us_dim = arg_filter($twi_Us_dim);\r\n  $twi_Us_other = arg_filter($twi_Us_other);\r\n\r\n  global $language;\r\n  if (file_exists("modules/npds_twi/lang/twi.lang-$language.php")) {\r\n   include_once ("modules/npds_twi/lang/twi.lang-$language.php");\r\n}\r\n  $dim = explode ( ''|'',$twi_Us_dim);\r\n  $col = explode ( ''|'',$twi_Us_color);\r\n  $twi_Us_other=str_replace (''1'',''true'',$twi_Us_other);\r\n  $twi_Us_other=str_replace (''0'',''false'',$twi_Us_other);\r\n  $oth = explode ( ''|'',$twi_Us_other);\r\n\r\n  $content='''';\r\n  $content .=''\r\n   <a class="twitter-timeline" href="https://twitter.com/labonpds" data-widget-id="694244113345044482">Tweets de @labonpds</a> \r\n   <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>\r\n\r\n'';\r\n  return ($content);\r\n}', 'meta', '-', NULL, NULL, '0');
INSERT INTO metalang (def, content, type_meta, type_uri, uri, description, obligatoire) VALUES('!forumL!', 'function MM_forumL()\r\n{\r\n\r\n	global $NPDS_Prefix, $cookie, $user;\r\n\r\n	/*Sujet chaud*/\r\n	$hot_threshold = 10;\r\n\r\n	/*Nbre posts a afficher*/\r\n	$maxcount = "10";\r\n\r\n	$MM_forumL = ''<table cellspacing="3" cellpadding="3" width="top" border="0">''\r\n	.''<tr align="center" class="ligna">''\r\n	.''<td width="8%">''.aff_langue(''[french]Etat[/french][english]State[/english]'').''</td>''\r\n	.''<td width="35%">''.aff_langue(''[french]Forum[/french][english]Forum[/english]'').''</td>''\r\n	.''<td width="50%">''.aff_langue(''[french]Sujet[/french][english]Topic[/english]'').''</td>''\r\n	.''<td width="7%">''.aff_langue(''[french]RÃ©ponses[/french][english]Replies[/english]'').''</td>''\r\n	.''</tr>'';\r\n\r\n	/*Requete liste dernier post*/\r\n	$result = sql_query("SELECT MAX(post_id) FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 GROUP BY topic_id ORDER BY MAX(post_id) DESC LIMIT 0,$maxcount");\r\n	while (list($post_id) = sql_fetch_row($result))\r\n	{\r\n\r\n		/*Requete detail dernier post*/\r\n		$res = sql_query("SELECT \r\n				us.topic_id, us.forum_id, us.poster_id, \r\n				uv.topic_title, \r\n				ug.forum_name, ug.forum_type, ug.forum_pass \r\n			FROM \r\n				".$NPDS_Prefix."posts us, \r\n				".$NPDS_Prefix."forumtopics uv, \r\n				".$NPDS_Prefix."forums ug \r\n			WHERE \r\n				us.post_id = $post_id \r\n				AND uv.topic_id = us.topic_id \r\n				AND uv.forum_id = ug.forum_id LIMIT 1");\r\n		list($topic_id, $forum_id, $poster_id, $topic_title, $forum_name, $forum_type, $forum_pass) = sql_fetch_row($res);\r\n\r\n		if (($forum_type == "5") or ($forum_type == "7"))\r\n		{\r\n\r\n			$ok_affich = false;\r\n			$tab_groupe = valid_group($user);\r\n			$ok_affich = groupe_forum($forum_pass, $tab_groupe);\r\n\r\n		}\r\n		else\r\n		{\r\n\r\n			$ok_affich = true;\r\n\r\n		}\r\n\r\n		if ($ok_affich)\r\n		{\r\n\r\n			/*Nbre de postes par sujet*/\r\n			$TableRep = sql_query("SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 AND topic_id = ''$topic_id''");\r\n			$replys = sql_num_rows($TableRep)-1;\r\n\r\n			/*Gestion lu / non lu*/\r\n			$sqlR = "SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE topicid = ''$topic_id'' AND uid = ''$cookie[0]'' AND status != ''0''";\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_red_folder.gif")){$imgtmpHR = $ibid;}else{$imgtmpHR = "images/forum/icons/hot_red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/hot_folder.gif")){$imgtmpH = $ibid;}else{$imgtmpH = "images/forum/icons/hot_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/red_folder.gif")){$imgtmpR = $ibid;}else{$imgtmpR = "images/forum/icons/red_folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/folder.gif")){$imgtmpF = $ibid;}else{$imgtmpF = "images/forum/icons/folder.gif";}\r\n\r\n			if ($ibid = theme_image("forum/icons/lock.gif")){$imgtmpL = $ibid;}else{$imgtmpL="images/forum/icons/lock.gif";}\r\n\r\n			if ($replys >= $hot_threshold)\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpHR;\r\n				else\r\n					$image = $imgtmpH;\r\n\r\n			}\r\n			else\r\n			{\r\n\r\n				if (sql_num_rows(sql_query($sqlR))==0)\r\n					$image = $imgtmpR;\r\n				else\r\n					$image = $imgtmpF;\r\n\r\n			}\r\n\r\n			if ($myrow[topic_status]!=0)\r\n			$image = $imgtmpL;\r\n\r\n			$MM_forumL .= ''<tr class="lignb">''\r\n			.''<td align="center"><img src="''.$image.''"></td>''\r\n			.''<td><a href="viewforum.php?forum=''.$forum_id.''">''.$forum_name.''</a></td>''\r\n			.''<td><a href="viewtopic.php?topic=''.$topic_id.''&forum=''.$forum_id.''">''.$topic_title.''</a></td>''\r\n			.''<td align="center">''.$replys.''</td>''\r\n			.''</tr>'';\r\n\r\n		}\r\n\r\n	}\r\n\r\n	$MM_forumL .= ''</table>'';\r\n\r\n	return ($MM_forumL);\r\n\r\n}', 'meta', '-', '', '[french].Retourne les derniers posts des forums en tenant compte des groupes\r\nVariables que vous devez configurer :\r\nmaxcount : nombre de posts que vous voulez afficher...[/french][english]...[/english]', '0');

CREATE TABLE modules (
  mid int(10) NOT NULL AUTO_INCREMENT,
  mnom varchar(255) NOT NULL DEFAULT '',
  minstall int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (mid),
  KEY mnom (mnom)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE optimy (
  optid int(11) NOT NULL AUTO_INCREMENT,
  optgain decimal(10,3) DEFAULT NULL,
  optdate varchar(11) DEFAULT NULL,
  opthour varchar(8) DEFAULT NULL,
  optcount int(11) DEFAULT '0',
  PRIMARY KEY (optid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE poll_data (
  pollID int(11) NOT NULL DEFAULT '0',
  optionText varchar(255) NOT NULL DEFAULT '',
  optionCount int(11) NOT NULL DEFAULT '0',
  voteID int(11) NOT NULL DEFAULT '0',
  pollType int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO poll_desc VALUES (2, 'NPDS', 1004108978, 1);

CREATE TABLE posts (
  post_id int(10) NOT NULL AUTO_INCREMENT,
  post_idH int(10) NOT NULL DEFAULT '0',
  image varchar(100) NOT NULL DEFAULT '',
  topic_id int(10) NOT NULL DEFAULT '0',
  forum_id int(10) NOT NULL DEFAULT '0',
  poster_id int(10) DEFAULT NULL,
  post_text text,
  post_time varchar(20) DEFAULT NULL,
  poster_ip varchar(16) DEFAULT NULL,
  poster_dns text,
  post_aff tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (post_id),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY post_aff (post_aff)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO posts VALUES (1, 0, '1F577.png', 1, 1, 2, 'Demo', '2011-10-26 17:00', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (2, 0, '1F310.png', 1, 1, 2, 'R&eacute;ponse', '2012-03-05 22:36', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (3, 0, 'icon1.gif', 2, 2, 1, 'Message 1', '2013-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (4, 3, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse au Message 1', '2003-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (5, 4, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse &agrave; la r&eacute;ponse du Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (6, 0, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse au Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (7, 0, '         ', 2, -2, 2, 'Bien, bien et m&ecirc;me mieux encore', '2012-07-22 13:42:22', '1.1.76.115', '', 1);

CREATE TABLE priv_msgs (
  msg_id int(10) NOT NULL AUTO_INCREMENT,
  msg_image varchar(100) DEFAULT NULL,
  subject varchar(100) DEFAULT NULL,
  from_userid int(10) NOT NULL DEFAULT '0',
  to_userid int(10) NOT NULL DEFAULT '0',
  msg_time varchar(20) DEFAULT NULL,
  msg_text text,
  read_msg tinyint(10) NOT NULL DEFAULT '0',
  type_msg int(1) NOT NULL DEFAULT '0',
  dossier varchar(50) NOT NULL DEFAULT '...',
  PRIMARY KEY (msg_id),
  KEY to_userid (to_userid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE publisujet (
  aid varchar(30) NOT NULL DEFAULT '',
  secid2 int(30) NOT NULL DEFAULT '0',
  type int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE queue (
  qid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(9) NOT NULL DEFAULT '0',
  uname varchar(40) NOT NULL DEFAULT '',
  subject varchar(255) NOT NULL DEFAULT '',
  story text,
  bodytext mediumtext,
  timestamp datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL DEFAULT 'Linux',
  date_debval datetime DEFAULT NULL,
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (qid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE rblocks (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  content text,
  member varchar(60) NOT NULL DEFAULT '0',
  Rindex tinyint(4) NOT NULL DEFAULT '0',
  cache mediumint(8) unsigned NOT NULL DEFAULT '0',
  actif smallint(5) unsigned NOT NULL DEFAULT '1',
  css tinyint(1) NOT NULL DEFAULT '0',
  aide mediumtext,
  PRIMARY KEY (id),
  KEY Rindex (Rindex)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO rblocks VALUES (1, 'Un Bloc ...', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO rblocks VALUES (2, 'Information', '<p align="center"><a href="http://www.npds.org" target="_blank"><img src="images/powered/miniban-bleu.png" border="0" alt="npds_logo" /></a></p>', '0', 0, 0, 1, 0, '');
INSERT INTO rblocks VALUES (3, 'Bloc membre', 'function#userblock', '0', 5, 0, 1, 0, '');
INSERT INTO rblocks VALUES (4, 'Lettre d''information', 'function#lnlbox', '0', 6, 86400, 1, 0, '');
INSERT INTO rblocks VALUES (5, 'Anciens Articles', 'function#oldNews\r\nparams#$storynum', '0', 4, 3600, 1, 0, '');
INSERT INTO rblocks VALUES (7, 'Cat&eacute;gories', 'function#category', '0', 2, 28800, 1, 0, '');
INSERT INTO rblocks VALUES (8, 'Article du Jour', 'function#bigstory', '0', 3, 60, 1, 0, '');

CREATE TABLE referer (
  rid int(11) NOT NULL AUTO_INCREMENT,
  url varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (rid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE related (
  rid int(11) NOT NULL AUTO_INCREMENT,
  tid int(11) NOT NULL DEFAULT '0',
  name varchar(30) NOT NULL DEFAULT '',
  url varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (rid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE reviews (
  id int(10) NOT NULL AUTO_INCREMENT,
  date date NOT NULL DEFAULT '0000-00-00',
  title varchar(150) NOT NULL DEFAULT '',
  text text NOT NULL,
  reviewer varchar(20) DEFAULT NULL,
  email varchar(60) DEFAULT NULL,
  score int(10) NOT NULL DEFAULT '0',
  cover varchar(100) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  url_title varchar(50) NOT NULL DEFAULT '',
  hits int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE reviews_add (
  id int(10) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  title varchar(150) NOT NULL DEFAULT '',
  text text NOT NULL,
  reviewer varchar(20) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  score int(10) NOT NULL DEFAULT '0',
  url varchar(100) NOT NULL DEFAULT '',
  url_title varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE reviews_main (
  title text,
  description text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO reviews_main VALUES ('Votre point de vue nous int&eacute;resse', 'Participez &agrave; la vie du site en apportant vos critiques mais restez toujours positif.');

CREATE TABLE rubriques (
  rubid int(4) NOT NULL AUTO_INCREMENT,
  rubname varchar(255) NOT NULL DEFAULT '',
  intro text NOT NULL,
  enligne tinyint(1) NOT NULL DEFAULT '0',
  ordre int(2) NOT NULL DEFAULT '0',
  UNIQUE KEY rubid (rubid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO rubriques VALUES (1, 'Divers', '', 1, 9998);
INSERT INTO rubriques VALUES (2, 'Presse-papiers', '', 0, 9999);
INSERT INTO rubriques VALUES (3, 'Mod&egrave;le', '', 1, 0);

CREATE TABLE seccont (
  artid int(11) NOT NULL AUTO_INCREMENT,
  secid int(11) NOT NULL DEFAULT '0',
  title text NOT NULL,
  content longtext NOT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  author varchar(50) NOT NULL DEFAULT '',
  ordre int(2) NOT NULL DEFAULT '0',
  userlevel varchar(34) NOT NULL DEFAULT '0',
  timestamp varchar(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (artid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE seccont_tempo (
  artid int(11) NOT NULL AUTO_INCREMENT,
  secid int(11) NOT NULL DEFAULT '0',
  title text NOT NULL,
  content longtext NOT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  author varchar(50) NOT NULL DEFAULT '',
  ordre int(2) NOT NULL DEFAULT '0',
  userlevel varchar(34) NOT NULL DEFAULT '0',
  PRIMARY KEY (artid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE sections (
  secid int(11) NOT NULL AUTO_INCREMENT,
  secname varchar(255) NOT NULL DEFAULT '',
  image varchar(255) NOT NULL DEFAULT '',
  userlevel varchar(34) NOT NULL DEFAULT '0',
  rubid int(5) NOT NULL DEFAULT '3',
  intro text,
  ordre int(2) NOT NULL DEFAULT '0',
  counter int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (secid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO sections VALUES (1, 'Pages statiques', '', '0', 1, NULL, 0, 0);
INSERT INTO sections VALUES (2, 'En instance', '', '0', 2, NULL, 0, 0);
INSERT INTO sections VALUES (3, 'Modifications des th&egrave;mes', '', '', 3, '', 1, 0);

CREATE TABLE session (
  username varchar(25) NOT NULL DEFAULT '',
  time varchar(14) NOT NULL DEFAULT '',
  host_addr varchar(20) NOT NULL DEFAULT '',
  guest int(1) NOT NULL DEFAULT '0',
  uri varchar(255) NOT NULL DEFAULT '',
  agent varchar(255) DEFAULT NULL,
  KEY username (username),
  KEY time (time),
  KEY guest (guest)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO session VALUES ('user', '1384102103', '127.0.0.1', 0, '/index.php', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0');

CREATE TABLE sform (
  cpt int(11) NOT NULL AUTO_INCREMENT,
  id_form text NOT NULL,
  id_key text NOT NULL,
  key_value varchar(255) NOT NULL DEFAULT '',
  passwd text,
  content longtext NOT NULL,
  PRIMARY KEY (cpt)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE stories (
  sid int(11) NOT NULL AUTO_INCREMENT,
  catid int(11) NOT NULL DEFAULT '0',
  aid varchar(30) NOT NULL DEFAULT '',
  title varchar(255) DEFAULT NULL,
  time datetime DEFAULT NULL,
  hometext mediumtext,
  bodytext mediumtext,
  comments int(11) DEFAULT '0',
  counter mediumint(8) unsigned DEFAULT NULL,
  topic int(3) NOT NULL DEFAULT '1',
  informant varchar(20) NOT NULL DEFAULT '',
  notes text NOT NULL,
  ihome int(1) NOT NULL DEFAULT '0',
  archive tinyint(1) unsigned NOT NULL DEFAULT '0',
  date_finval datetime DEFAULT NULL,
  auto_epur tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (sid),
  KEY catid (catid),
  KEY topic (topic),
  KEY informant (informant),
  KEY aid (aid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO stories VALUES (1, 0, 'Root', 'Comment modifier et / ou supprimer EDITO', '2012-09-15 05:01:52', '<font face="arial"><b>L''EDITO </b>est l<b>a premi&egrave;re chose que les visiteurs visualiseront</b> en arrivant sur votre nouveau <b>site NPDS</b>.<br />\r\n<br />Vous pouvez l''<b>&eacute;diter</b> pour le personnaliser, ainsi que choisir de l''afficher ou non. <br />\r\nPour toute modification, l''<b>&eacute;diteur int&eacute;gr&eacute; &agrave; NPDS</b> vous simplifiera &eacute;norm&eacute;ment la t&acirc;che !<br />\r\n<br />\r\nEnfin, vous pouvez d&eacute;cider dans les <i>pr&eacute;f&eacute;rences administrateur</i>\r\nde la page que vous souhaitez utiliser <b>comme index de votre site</b>:\r\nce n''est donc pas forc&eacute;ment l''EDITO, et votre imagination laissera\r\nentrevoir bien d''autres possibilit&eacute;s !<br />\r\n</font>', 'Vous pouvez, par exemple:<br />\r\n<ul>\r\n  <li>faire arriver vos visiteurs sur la <b>page des forums</b></li>\r\n  <li>faire arriver vos visiteurs sur <b>une page d&eacute;crivant votre site en utilisant les rubriques</b></li>\r\n  <li>....<br />\r\n  </li>\r\n</ul>', 0, 2, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (2, 0, 'Root', 'NPDS contient un excellent &eacute;diteur HTML !', '2012-09-19 01:08:39', 'L''<span style="font-weight: bold;">&eacute;diteur HTML</span> int&eacute;gr&eacute; dans <span style="font-weight: bold;">NPDS</span> est vraiment <span style="font-style: italic;">tr&egrave;s puissant</span> ! <span style="font-weight: bold; color: rgb(0, 0, 204);">Tiny MCE</span>, c''est son nom, vous permet de taper et de mettre en forme le texte directement depuis votre navigateur.<br /><p style="text-align: justify;"><br /><span style="font-weight: bold;">L''envoi d''images</span> sur votre site est <span style="font-style: italic;">tr&egrave;s simple</span> si vous souhaitez illustrer vos textes, et vous pouvez aussi faire des <span style="font-weight: bold;">copier/coller</span> depuis nimporte quel logiciel de <span style="font-weight: bold;">traitement de texte</span> !</p>', '', 0, 4, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (3, 0, 'Root', 'Vous cherchez des modules compl&eacute;mentaires', '2013-10-15 15:11:32', '<center><font color="#660000"><font size="3"><font face="arial"><b><font size="2" color="#0000ff">Vous cherchez des modules pour NPDS :</font><br /><a href="http://modules.npds.org/" target="_blank">http://modules.npds.org</a><br />\r\n<br /></b></font></font></font><div style="text-align: left;">Ce site, v&eacute;ritable <span style="font-weight: bold;">vitrine de l''activit&eacute; d&eacute;bordante de la communaut&eacute; NPDS</span>, vous pr&eacute;sente de <span style="font-weight: bold;">nombreux modules</span> ajoutant des fonctionnalit&eacute;s tr&egrave;s diverses &agrave; votre site.<br /><br />N''h&eacute;sitez pas &agrave; lui rendre visite: les <span style="font-weight: bold;">nombreux t&eacute;l&eacute;chargements</span> &agrave; disposition ainsi que des<span style="font-weight: bold;"> forums d''aide</span> ou encore <span style="font-weight: bold;">les tutoriels</span> sont l&agrave; pour vous guider dans la d&eacute;couverte de ces nouvelles possibilit&eacute;s !<br /></div>\r\n</center>', '', 0, 1, 2, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (5, 0, 'Root', 'Vous cherchez des th&egrave;mes compl&eacute;mentaires', '2013-11-02 18:59:36', '<center><span style="color: #660000;"><span style="font-size: medium;"><span style="font-family: arial;"><strong><span style="color: #0000ff; font-size: small;">Vous cherchez des th&egrave;mes pour NPDS :</span><br /><a title="Tous les THEMES pour NPDS" href="http://styles.npds.org/" target="_blank">http://styles.npds.org</a><br /> <br /></strong></span></span></span>\r\n<div style="text-align: left;">Ce site avec plus de 100 th&egrave;mes disponibles vous permettra certainement de trouver la bonne personnalit&eacute; pour votre site.<br /><br />N''h&eacute;sitez pas &agrave; lui rendre visite: les <span style="font-weight: bold;">nombreux th&egrave;mes </span>&agrave; disposition ainsi que des<span style="font-weight: bold;"> forums d''aide</span> ou encore <span style="font-weight: bold;">les tutoriels</span> sont l&agrave; pour vous guider dans la d&eacute;couverte de ces nouvelles possibilit&eacute;s !</div>\r\n</center>', '', 0, 1, 3, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);

CREATE TABLE stories_cat (
  catid int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE subscribe (
  topicid tinyint(3) DEFAULT NULL,
  forumid int(10) DEFAULT NULL,
  lnlid text,
  uid int(11) NOT NULL DEFAULT '0',
  KEY uid (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE topics (
  topicid int(3) NOT NULL AUTO_INCREMENT,
  topicname varchar(20) DEFAULT NULL,
  topicimage varchar(20) DEFAULT NULL,
  topictext varchar(250) DEFAULT NULL,
  counter int(11) NOT NULL DEFAULT '0',
  topicadmin text,
  PRIMARY KEY (topicid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO topics VALUES (1, 'npds', 'npds.gif', 'NPDS', 0, NULL);
INSERT INTO topics VALUES (2, 'modules', 'modules.gif', 'Modules', 0, NULL);
INSERT INTO topics VALUES (3, 'styles', 'styles.gif', 'Styles', 0, NULL);

CREATE TABLE users (
  uid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(60) NOT NULL DEFAULT '',
  uname varchar(25) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  femail varchar(60) NOT NULL DEFAULT '',
  url varchar(100) NOT NULL DEFAULT '',
  user_avatar varchar(100) DEFAULT NULL,
  user_regdate varchar(20) NOT NULL DEFAULT '',
  user_occ varchar(100) DEFAULT NULL,
  user_from varchar(100) DEFAULT NULL,
  user_intrest varchar(150) DEFAULT NULL,
  user_sig varchar(255) DEFAULT NULL,
  user_viewemail tinyint(2) DEFAULT NULL,
  user_theme int(3) DEFAULT NULL,
  user_journal text NOT NULL,
  pass varchar(40) NOT NULL DEFAULT '',
  storynum tinyint(4) NOT NULL DEFAULT '10',
  umode varchar(10) NOT NULL DEFAULT '',
  uorder tinyint(1) NOT NULL DEFAULT '0',
  thold tinyint(1) NOT NULL DEFAULT '0',
  noscore tinyint(1) NOT NULL DEFAULT '0',
  bio tinytext NOT NULL,
  ublockon tinyint(1) NOT NULL DEFAULT '0',
  ublock tinytext NOT NULL,
  theme varchar(255) NOT NULL DEFAULT '',
  commentmax int(11) NOT NULL DEFAULT '4096',
  counter int(11) NOT NULL DEFAULT '0',
  send_email tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_visible tinyint(1) unsigned NOT NULL DEFAULT '1',
  mns tinyint(1) unsigned NOT NULL DEFAULT '0',
  user_langue varchar(20) DEFAULT NULL,
  user_lastvisit varchar(14) DEFAULT NULL,
  user_lnl tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (uid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO users VALUES (1, '', 'Anonyme', '', '', '', 'blank.gif', '989445600', '', '', '', '', 0, 0, '', '', 10, '', 0, 0, 0, '', 0, '', '', 4096, 0, 0, 1, 0, NULL, NULL, 1);
INSERT INTO users VALUES (2, 'user', 'user', 'user@user.land', '', 'http://www.userland.com', '014.gif', '989445600', '', '', '', 'User of the Land', 0, 0, '', 'd.q1Wcp0KUqsk', 10, '', 0, 0, 0, '', 1, '<ul><li><a href=http://www.npds.org target=_blank>NPDS.ORG</a></li></ul>', 'npds-boost_sk', 4096, 4, 0, 1, 1, 'french', '1384102103', 1);

CREATE TABLE users_extend (
  uid int(11) NOT NULL AUTO_INCREMENT,
  C1 varchar(255) DEFAULT NULL,
  C2 varchar(255) DEFAULT NULL,
  C3 varchar(255) DEFAULT NULL,
  C4 varchar(255) DEFAULT NULL,
  C5 varchar(255) DEFAULT NULL,
  C6 varchar(255) DEFAULT NULL,
  C7 varchar(255) DEFAULT NULL,
  C8 varchar(255) DEFAULT NULL,
  M1 mediumtext,
  M2 mediumtext,
  T1 varchar(10) DEFAULT NULL,
  T2 varchar(14) DEFAULT NULL,
  B1 blob,
  PRIMARY KEY (uid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO users_extend VALUES (2, '', '', '', '', '', '', '', '', '', '', '15/07/2015', '', 'none');

CREATE TABLE users_status (
  uid int(11) NOT NULL AUTO_INCREMENT,
  posts int(10) DEFAULT '0',
  attachsig int(2) DEFAULT '0',
  rank int(10) DEFAULT '0',
  level int(10) DEFAULT '1',
  open tinyint(1) NOT NULL DEFAULT '1',
  groupe varchar(34) DEFAULT NULL,
  PRIMARY KEY (uid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO users_status VALUES (1, 19, 0, 0, 1, 1, '');
INSERT INTO users_status VALUES (2, 3, 0, 0, 2, 1, '');

CREATE TABLE wspad (
  ws_id int(11) NOT NULL AUTO_INCREMENT,
  page varchar(255) NOT NULL DEFAULT '',
  content mediumtext NOT NULL,
  modtime int(15) NOT NULL,
  editedby varchar(40) NOT NULL DEFAULT '',
  ranq smallint(6) NOT NULL DEFAULT '1',
  member int(11) NOT NULL DEFAULT '1',
  verrou varchar(60) DEFAULT NULL,
  PRIMARY KEY (ws_id),
  KEY page (page)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE ip_loc (
  ip_id smallint(8) UNSIGNED NOT NULL,
  ip_long float NOT NULL DEFAULT '0',
  ip_lat float NOT NULL DEFAULT '0',
  ip_visi_pag varchar(100) NOT NULL DEFAULT '',
  ip_visite mediumint(9) UNSIGNED NOT NULL DEFAULT '0',
  ip_ip varchar(54) NOT NULL DEFAULT '',
  ip_country varchar(100) NOT NULL DEFAULT '0',
  ip_code_country varchar(4) NOT NULL,
  ip_city varchar(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (ip_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

