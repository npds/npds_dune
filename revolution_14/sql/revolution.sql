
-- --------------------------------------------------------

-- 
-- Structure de la table `access`
-- 

CREATE TABLE access (
  access_id int(10) NOT NULL auto_increment,
  access_title varchar(20) default NULL,
  PRIMARY KEY  (access_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `access`
-- 

INSERT INTO access VALUES (1, 'User');
INSERT INTO access VALUES (2, 'Moderator');
INSERT INTO access VALUES (3, 'Super Moderator');

-- --------------------------------------------------------

-- 
-- Structure de la table `adminblock`
-- 

CREATE TABLE adminblock (
  title varchar(250) default NULL,
  content text
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `adminblock`
-- 

INSERT INTO adminblock VALUES ('Administration', '<ul><li><a href="admin.php">Administration</a></li><li><a href="admin.php?op=logout">Logout</a></li></ul><ul><li><a href="abla.php">Admin BlackBoard</a></li></ul>');

-- --------------------------------------------------------

-- 
-- Structure de la table `appli_log`
-- 

CREATE TABLE appli_log (
  al_id int(11) NOT NULL default '0',
  al_name varchar(255) default NULL,
  al_subid int(11) NOT NULL default '0',
  al_date datetime NOT NULL default '0000-00-00 00:00:00',
  al_uid int(11) NOT NULL default '0',
  al_data text,
  al_ip varchar(19) NOT NULL default '',
  al_hostname varchar(255) default NULL,
  KEY al_id (al_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `appli_log`
-- 

INSERT INTO appli_log VALUES (1, 'Poll', 2, '2012-07-15 13:35:32', 1, '2', '1.1.76.115', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `authors`
-- 

CREATE TABLE authors (
  aid varchar(30) NOT NULL default '',
  name varchar(50) default NULL,
  url varchar(60) default NULL,
  email varchar(60) default NULL,
  pwd varchar(40) default NULL,
  counter int(11) NOT NULL default '0',
  radminarticle tinyint(2) NOT NULL default '0',
  radmintopic tinyint(2) NOT NULL default '0',
  radminleft tinyint(2) NOT NULL default '0',
  radminright tinyint(2) NOT NULL default '0',
  radminuser tinyint(2) NOT NULL default '0',
  radminmain tinyint(2) NOT NULL default '0',
  radminsurvey tinyint(2) NOT NULL default '0',
  radminsection tinyint(2) NOT NULL default '0',
  radminlink tinyint(2) NOT NULL default '0',
  radminephem tinyint(2) NOT NULL default '0',
  radminfilem tinyint(2) NOT NULL default '0',
  radminhead tinyint(2) NOT NULL default '0',
  radminfaq tinyint(2) NOT NULL default '0',
  radmindownload tinyint(2) NOT NULL default '0',
  radminforum tinyint(2) NOT NULL default '0',
  radminreviews tinyint(2) NOT NULL default '0',
  radminsdv tinyint(2) NOT NULL default '0',
  radminlnl tinyint(2) NOT NULL default '0',
  radminsuper tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (aid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `authors`
-- 

INSERT INTO authors VALUES ('Root', 'Root', '', '', 'd.8V.L9nSMMvE', 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `autonews`
-- 

CREATE TABLE autonews (
  anid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(255) default NULL,
  time varchar(19) NOT NULL default '',
  hometext text NOT NULL,
  bodytext mediumtext,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  date_debval datetime default NULL,
  date_finval datetime default NULL,
  auto_epur tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (anid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `autonews`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `banner`
-- 

CREATE TABLE banner (
  bid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  imptotal int(11) NOT NULL default '0',
  impmade int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  imageurl varchar(200) NOT NULL default '',
  clickurl varchar(200) NOT NULL default '',
  userlevel int(1) NOT NULL default '0',
  date datetime default NULL,
  PRIMARY KEY  (bid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `banner`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannerclient`
-- 

CREATE TABLE bannerclient (
  cid int(11) NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  contact varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  login varchar(10) NOT NULL default '',
  passwd varchar(10) NOT NULL default '',
  extrainfo text NOT NULL,
  PRIMARY KEY  (cid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `bannerclient`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannerfinish`
-- 

CREATE TABLE bannerfinish (
  bid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  impressions int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  datestart datetime default NULL,
  dateend datetime default NULL,
  PRIMARY KEY  (bid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `bannerfinish`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `catagories`
-- 

CREATE TABLE catagories (
  cat_id int(10) NOT NULL auto_increment,
  cat_title text,
  PRIMARY KEY  (cat_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `catagories`
-- 

INSERT INTO catagories VALUES (1, 'Demo');

-- --------------------------------------------------------

-- 
-- Structure de la table `chatbox`
-- 

CREATE TABLE chatbox (
  username text,
  ip varchar(20) NOT NULL default '',
  message text,
  date int(15) NOT NULL default '0',
  id int(10) default '0',
  dbname tinyint(4) default '0',
  PRIMARY KEY  (date)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `chatbox`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `compatsujet`
-- 

CREATE TABLE compatsujet (
  id1 varchar(30) NOT NULL default '',
  id2 int(30) NOT NULL default '0'
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `compatsujet`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `config`
-- 

CREATE TABLE config (
  allow_html int(2) default NULL,
  allow_bbcode int(2) default NULL,
  allow_sig int(2) default NULL,
  posts_per_page int(10) default NULL,
  hot_threshold int(10) default NULL,
  topics_per_page int(10) default NULL,
  allow_upload_forum int(2) unsigned NOT NULL default '0',
  allow_forum_hide int(2) unsigned NOT NULL default '0',
  upload_table varchar(50) NOT NULL default 'forum_attachments',
  rank1 varchar(255) default NULL,
  rank2 varchar(255) default NULL,
  rank3 varchar(255) default NULL,
  rank4 varchar(255) default NULL,
  rank5 varchar(255) default NULL,
  anti_flood char(3) default NULL,
  solved int(2) unsigned NOT NULL default '0'
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `config`
-- 

INSERT INTO config VALUES (1, 1, 1, 10, 10, 10, 0, 0, 'forum_attachments', NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `counter`
-- 

CREATE TABLE counter (
  type varchar(80) NOT NULL default '',
  var varchar(80) NOT NULL default '',
  count int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `counter`
-- 

INSERT INTO counter VALUES ('total', 'hits', 122);
INSERT INTO counter VALUES ('browser', 'WebTV', 0);
INSERT INTO counter VALUES ('browser', 'Lynx', 0);
INSERT INTO counter VALUES ('browser', 'MSIE', 1);
INSERT INTO counter VALUES ('browser', 'Opera', 0);
INSERT INTO counter VALUES ('browser', 'Konqueror', 0);
INSERT INTO counter VALUES ('browser', 'Netscape', 121);
INSERT INTO counter VALUES ('browser', 'Chrome', 0);
INSERT INTO counter VALUES ('browser', 'Safari', 0);
INSERT INTO counter VALUES ('browser', 'Bot', 0);
INSERT INTO counter VALUES ('browser', 'Other', 0);
INSERT INTO counter VALUES ('os', 'Windows', 122);
INSERT INTO counter VALUES ('os', 'Linux', 0);
INSERT INTO counter VALUES ('os', 'Mac', 0);
INSERT INTO counter VALUES ('os', 'FreeBSD', 0);
INSERT INTO counter VALUES ('os', 'SunOS', 0);
INSERT INTO counter VALUES ('os', 'IRIX', 0);
INSERT INTO counter VALUES ('os', 'BeOS', 0);
INSERT INTO counter VALUES ('os', 'OS/2', 0);
INSERT INTO counter VALUES ('os', 'AIX', 0);
INSERT INTO counter VALUES ('os', 'Other', 0);
INSERT INTO counter VALUES ('browser', 'Chrome', 0);
INSERT INTO counter VALUES ('browser', 'Safari', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `downloads`
-- 

CREATE TABLE downloads (
  did int(10) NOT NULL auto_increment,
  dcounter int(10) NOT NULL default '0',
  durl varchar(255) default NULL,
  dfilename varchar(255) default NULL,
  dfilesize bigint(15) default NULL,
  ddate date NOT NULL default '0000-00-00',
  dweb varchar(255) default NULL,
  duser varchar(30) default NULL,
  dver varchar(6) default NULL,
  dcategory varchar(250) default NULL,
  ddescription text,
  perms tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (did)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `downloads`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `ephem`
-- 

CREATE TABLE ephem (
  eid int(11) NOT NULL auto_increment,
  did int(2) NOT NULL default '0',
  mid int(2) NOT NULL default '0',
  yid int(4) NOT NULL default '0',
  content text NOT NULL,
  PRIMARY KEY  (eid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `ephem`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `faqanswer`
-- 

CREATE TABLE faqanswer (
  id int(11) NOT NULL auto_increment,
  id_cat tinyint(4) default NULL,
  question varchar(255) default NULL,
  answer text,
  PRIMARY KEY  (id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `faqanswer`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `faqcategories`
-- 

CREATE TABLE faqcategories (
  id_cat tinyint(3) NOT NULL auto_increment,
  categories varchar(255) default NULL,
  PRIMARY KEY  (id_cat)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `faqcategories`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `forum_attachments`
-- 

CREATE TABLE forum_attachments (
  att_id int(11) NOT NULL auto_increment,
  post_id int(11) NOT NULL default '0',
  topic_id int(11) NOT NULL default '0',
  forum_id int(11) NOT NULL default '0',
  unixdate int(11) NOT NULL default '0',
  att_name varchar(255) NOT NULL default '',
  att_type varchar(64) NOT NULL default '',
  att_size int(11) NOT NULL default '0',
  att_path varchar(255) NOT NULL default '',
  inline char(1) NOT NULL default '',
  apli varchar(10) NOT NULL default '',
  compteur int(11) NOT NULL default '0',
  visible tinyint(1) NOT NULL default '0',
  KEY att_id (att_id),
  KEY post_id (post_id),
  KEY topic_id (topic_id),
  KEY apli (apli),
  KEY visible (visible),
  KEY forum_id (forum_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `forum_attachments`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `forum_read`
-- 

CREATE TABLE forum_read (
  rid int(11) NOT NULL auto_increment,
  forum_id int(10) NOT NULL default '0',
  topicid int(3) NOT NULL default '0',
  uid int(11) NOT NULL default '0',
  last_read int(15) NOT NULL default '0',
  status tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (rid),
  KEY topicid (topicid),
  KEY forum_id (forum_id),
  KEY uid (uid),
  KEY forum_read_mcl (forum_id,uid,topicid,status)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `forum_read`
-- 

INSERT INTO forum_read VALUES (1, 2, 2, 2, 1383416155, 1);
INSERT INTO forum_read VALUES (2, 1, 1, 2, 1383418761, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `forums`
-- 

CREATE TABLE forums (
  forum_id int(10) NOT NULL auto_increment,
  forum_name varchar(150) default NULL,
  forum_desc text,
  forum_access int(10) default '1',
  forum_moderator text,
  cat_id int(10) default NULL,
  forum_type int(10) default '0',
  forum_pass varchar(60) default NULL,
  arbre tinyint(1) unsigned NOT NULL default '0',
  attachement tinyint(1) unsigned NOT NULL default '0',
  forum_index int(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (forum_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `forums`
-- 

INSERT INTO forums VALUES (1, 'Demo', '', 0, '2', 1, 0, '', 0, 0, 0);
INSERT INTO forums VALUES (2, 'Arbre', 'un forum &agrave; l''ancienne forme', 0, '2', 1, 0, '', 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `forumtopics`
-- 

CREATE TABLE forumtopics (
  topic_id int(10) NOT NULL auto_increment,
  topic_title varchar(100) default NULL,
  topic_poster int(10) default NULL,
  topic_time datetime default NULL,
  topic_views int(10) NOT NULL default '0',
  forum_id int(10) default NULL,
  topic_status int(10) NOT NULL default '0',
  topic_notify int(2) default '0',
  current_poster int(10) default NULL,
  topic_first tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (topic_id),
  KEY forum_id (forum_id),
  KEY topic_first (topic_first,topic_time)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `forumtopics`
-- 

INSERT INTO forumtopics VALUES (1, 'Demo', 2, '2012-03-05 22:36:00', 27, 1, 0, 0, 2, 1);
INSERT INTO forumtopics VALUES (2, 'Message 1', 1, '2013-05-14 22:55:00', 8, 2, 0, 0, 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `groupes`
-- 

CREATE TABLE groupes (
  groupe_id int(3) default NULL,
  groupe_name varchar(30) NOT NULL default '',
  groupe_description varchar(255) NOT NULL default '',
  groupe_forum int(1) unsigned NOT NULL default '0',
  groupe_mns int(1) unsigned NOT NULL default '0',
  groupe_chat int(1) unsigned NOT NULL default '0',
  groupe_blocnote int(1) unsigned NOT NULL default '0',
  groupe_pad int(1) unsigned NOT NULL default '0',
  UNIQUE KEY groupe_id (groupe_id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `groupes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `headlines`
-- 

CREATE TABLE headlines (
  hid int(11) NOT NULL auto_increment,
  sitename varchar(30) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  headlinesurl varchar(200) NOT NULL default '',
  status tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (hid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `headlines`
-- 

INSERT INTO headlines VALUES (1, 'NPDS', 'http://www.npds.org', 'http://www.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (2, 'Modules', 'http://modules.npds.org', 'http://modules.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (3, 'Styles', 'http://styles.npds.org', 'http://styles.npds.org/backend.php', 0);
INSERT INTO headlines VALUES (4, 'Global', 'http://global.npds.org', 'http://global.npds.org/backend.php', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `lblocks`
-- 

CREATE TABLE lblocks (
  id tinyint(3) unsigned NOT NULL auto_increment,
  title varchar(255) default NULL,
  content text,
  member varchar(60) NOT NULL default '0',
  Lindex tinyint(4) NOT NULL default '0',
  cache mediumint(8) unsigned NOT NULL default '0',
  actif smallint(5) unsigned NOT NULL default '1',
  css tinyint(1) NOT NULL default '0',
  aide mediumtext,
  PRIMARY KEY  (id),
  KEY Lindex (Lindex)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `lblocks`
-- 

INSERT INTO lblocks VALUES (1, 'Un Bloc ...', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO lblocks VALUES (2, 'Menu', 'function#mainblock', '0', 1, 86400, 1, 0, 'Ce menu contient presque toutes les fonctions de base disponibles dans NPDS');
INSERT INTO lblocks VALUES (3, 'Msg &agrave; un Membre', 'function#instant_members_message', '0', 4, 0, 1, 0, '');
INSERT INTO lblocks VALUES (4, 'Chat Box', 'function#makeChatBox', '0', 2, 10, 1, 0, '');
INSERT INTO lblocks VALUES (5, 'Forums Infos', 'function#RecentForumPosts\r\nparams#Forums Infos,15,0,false,10,false,-:\r\n', '0', 5, 60, 1, 0, '');
INSERT INTO lblocks VALUES (6, 'Les plus t&eacute;l&eacute;charg&eacute;s', 'function#topdownload', '0', 6, 3600, 1, 0, '');
INSERT INTO lblocks VALUES (7, 'Administration', 'function#adminblock', '0', 3, 0, 1, 0, '');
INSERT INTO lblocks VALUES (8, 'Eph&eacute;m&eacute;rides', 'function#ephemblock', '0', 7, 28800, 1, 0, '');
INSERT INTO lblocks VALUES (9, 'headlines', 'function#headlines', '0', 9, 3600, 0, 0, '');
INSERT INTO lblocks VALUES (10, 'Activit&eacute; du Site', 'function#Site_Activ', '0', 8, 10, 1, 0, '');
INSERT INTO lblocks VALUES (11, 'Sondage', 'function#pollNewest', '0', 1, 60, 1, 0, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `links_categories`
-- 

CREATE TABLE links_categories (
  cid int(11) NOT NULL auto_increment,
  title varchar(250) default NULL,
  cdescription text NOT NULL,
  PRIMARY KEY  (cid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_categories`
-- 

INSERT INTO links_categories VALUES (1, 'Mod&eacute;le', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `links_editorials`
-- 

CREATE TABLE links_editorials (
  linkid int(11) NOT NULL default '0',
  adminid varchar(60) NOT NULL default '',
  editorialtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL default '',
  PRIMARY KEY  (linkid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_editorials`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `links_links`
-- 

CREATE TABLE links_links (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  date datetime default NULL,
  name varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  hits int(11) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  linkratingsummary double(6,4) NOT NULL default '0.0000',
  totalvotes int(11) NOT NULL default '0',
  totalcomments int(11) NOT NULL default '0',
  topicid_card tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (lid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_links`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `links_modrequest`
-- 

CREATE TABLE links_modrequest (
  requestid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL default '',
  brokenlink int(3) NOT NULL default '0',
  topicid_card tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (requestid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_modrequest`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `links_newlink`
-- 

CREATE TABLE links_newlink (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  name varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  topicid_card tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (lid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_newlink`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `links_subcategories`
-- 

CREATE TABLE links_subcategories (
  sid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  title varchar(250) default NULL,
  PRIMARY KEY  (sid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `links_subcategories`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lnl_body`
-- 

CREATE TABLE lnl_body (
  ref int(11) NOT NULL auto_increment,
  html char(1) NOT NULL default '1',
  text text,
  status char(3) NOT NULL default 'stb',
  PRIMARY KEY  (ref)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `lnl_body`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lnl_head_foot`
-- 

CREATE TABLE lnl_head_foot (
  ref int(11) NOT NULL auto_increment,
  type char(3) NOT NULL default '',
  html char(1) NOT NULL default '1',
  text text,
  status char(3) NOT NULL default 'OK',
  PRIMARY KEY  (ref)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `lnl_head_foot`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lnl_outside_users`
-- 

CREATE TABLE lnl_outside_users (
  email varchar(60) NOT NULL default '',
  host_name varchar(60) default NULL,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  status char(3) NOT NULL default 'OK',
  PRIMARY KEY  (email)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `lnl_outside_users`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lnl_send`
-- 

CREATE TABLE lnl_send (
  ref int(11) NOT NULL auto_increment,
  header int(11) NOT NULL default '0',
  body int(11) NOT NULL default '0',
  footer int(11) NOT NULL default '0',
  number_send int(11) NOT NULL default '0',
  type_send char(3) NOT NULL default 'ALL',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  status char(3) NOT NULL default 'OK',
  PRIMARY KEY  (ref)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `lnl_send`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `mainblock`
-- 

CREATE TABLE mainblock (
  title varchar(255) default NULL,
  content text
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `mainblock`
-- 

INSERT INTO mainblock VALUES ('Menu', '<ul><li><a href="modules.php?ModPath=archive-stories&ModStart=archive-stories">Nouvelles</a></li><li><a href="forum.php">Forums</a></li><li><a href="sections.php">Rubriques</a></li><li><a href="topics.php">Sujets actifs</a></li><li><a href="modules.php?ModPath=links&ModStart=links">Liens Web</a></li><li><a href="download.php">Downloads</a></li><li><a href="faq.php">FAQ</a></li><li><a href="static.php?op=statik.txt&npds=1">Page statique</a></li><li><a href="reviews.php">Critiques</a></li><li><a href="memberslist.php">Annuaire</a></li><li><a href="map.php">Plan du site</a></li></ul><ul><li><a href="friend.php">Faire notre pub</a></li><li><a href="user.php">Votre compte</a></li><li><a href="submit.php">Nouvel article</a></li></ul><ul><li><a href="admin.php">Administration</a></li></ul>');

-- --------------------------------------------------------

-- 
-- Structure de la table `metalang`
-- 

CREATE TABLE metalang (
  def varchar(50) NOT NULL default '',
  content text NOT NULL,
  type_meta varchar(4) NOT NULL default 'mot',
  type_uri char(1) NOT NULL default '-',
  uri varchar(255) default NULL,
  description text,
  obligatoire char(3) NOT NULL default '0',
  PRIMARY KEY  (def),
  KEY type_meta (type_meta)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `metalang`
-- 

INSERT INTO metalang VALUES ('Dev', 'Developpeur', 'mot', '-', NULL, NULL, '0');
INSERT INTO metalang VALUES ('NPDS', '<a href="http://www.npds.org" target="_blank" title="www.npds.org">NPDS</a>', 'mot', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-)', '$cmd=MM_img("forum/smilies/icon_smile.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-]', '$cmd=MM_img("forum/smilies/icon_smile.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (';-)', '$cmd=MM_img("forum/smilies/icon_wink.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (';-]', '$cmd=MM_img("forum/smilies/icon_wink.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-(', '$cmd=MM_img("forum/smilies/icon_frown.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-[', '$cmd=MM_img("forum/smilies/icon_frown.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES ('8-)', '$cmd=MM_img("forum/smilies/icon_cool.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES ('8-]', '$cmd=MM_img("forum/smilies/icon_cool.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-P', '$cmd=MM_img("forum/smilies/icon_razz.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-D', '$cmd=MM_img("forum/smilies/icon_biggrin.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':=!', '$cmd=MM_img("forum/smilies/yaisse.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':b', '$cmd=MM_img("forum/smilies/icon_tongue.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':D', '$cmd=MM_img("forum/smilies/icon_grin.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':#', '$cmd=MM_img("forum/smilies/icon_ohwell.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-o', '$cmd=MM_img("forum/smilies/icon_eek.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-?', '$cmd=MM_img("forum/smilies/icon_confused.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':-|', '$cmd=MM_img("forum/smilies/icon_mad.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':|', '$cmd=MM_img("forum/smilies/icon_mad2.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES (':paf', '$cmd=MM_img("forum/smilies/pafmur.gif");', 'smil', '-', NULL, NULL, '1');
INSERT INTO metalang VALUES ('dateL', '$cmd=date("d/m/Y");', 'meta', '-', NULL, '[french]Date longue JJ/MM/YYYY[/french]', '1');
INSERT INTO metalang VALUES ('heureC', '$cmd=date("H:i");', 'meta', '-', NULL, '[french]Heure courte HH:MM[/french]', '1');
INSERT INTO metalang VALUES ('heureL', '$cmd=date("H:i:s");', 'meta', '-', NULL, '[french]Heure longue HH:MM:SS[/french]', '1');
INSERT INTO metalang VALUES ('dateC', '$cmd=date("d/m/y");', 'meta', '-', NULL, '[french]Date longue JJ/MM[/french]', '1');
INSERT INTO metalang VALUES ('!a/!', '&#92;', 'meta', '-', NULL, '[french]anti-slash[/french]', '1');
INSERT INTO metalang VALUES ('!sc_infos_else!', '&nbsp;', 'meta', '-', NULL, '[french]affiche les informations de SuperCache[/french]', '1');
INSERT INTO metalang VALUES ('!sc_infos!', '$cmd=SC_infos();', 'meta', '-', NULL, '[french]affiche les informations de SuperCache[/french]', '1');
INSERT INTO metalang VALUES ('!slogan!', '$cmd=$GLOBALS[''slogan''];', 'meta', '-', NULL, '[french]variable global $slogan[/french]', '1');
INSERT INTO metalang VALUES ('!bargif!', '$cmd=$GLOBALS[''bargif''];', 'meta', '-', NULL, '[french]variable global $bargif[/french]', '1');
INSERT INTO metalang VALUES ('!theme!', '$cmd=$GLOBALS[''theme''];', 'meta', '-', NULL, '[french]variable global $theme[/french]', '1');
INSERT INTO metalang VALUES ('!sitename!', '$cmd=$GLOBALS[''sitename''];', 'meta', '-', NULL, '[french]variable global $sitename[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor1!', '$cmd=$GLOBALS[''bgcolor1''];', 'meta', '-', NULL, '[french]variable global $bgcolor1 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor2!', '$cmd=$GLOBALS[''bgcolor2''];', 'meta', '-', NULL, '[french]variable global $bgcolor2 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor3!', '$cmd=$GLOBALS[''bgcolor3''];', 'meta', '-', NULL, '[french]variable global $bgcolor3 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor4!', '$cmd=$GLOBALS[''bgcolor4''];', 'meta', '-', NULL, '[french]variable global $bgcolor4 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor5!', '$cmd=$GLOBALS[''bgcolor5''];', 'meta', '-', NULL, '[french]variable global $bgcolor5 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!bgcolor6!', '$cmd=$GLOBALS[''bgcolor6''];', 'meta', '-', NULL, '[french]variable global $bgcolor6 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!textcolor1!', '$cmd=$GLOBALS[''textcolor1''];', 'meta', '-', NULL, '[french]variable global $textcolor1 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!textcolor2!', '$cmd=$GLOBALS[''textcolor2''];', 'meta', '-', NULL, '[french]variable global $textcolor2 (ancien theme)[/french]', '1');
INSERT INTO metalang VALUES ('!opentable!', '$cmd=sub_opentable();', 'meta', '-', '', '[french]Appel la fonction OpenTable de NPDS[/french]', '1');
INSERT INTO metalang VALUES ('!closetable!', '$cmd=sub_closetable();', 'meta', '-', '', '[french]Appel la fonction CloseTable de NPDS[/french]', '1');
INSERT INTO metalang VALUES ('Scalcul', 'function MM_Scalcul($opex,$premier,$deuxieme) {\n   if ($opex=="+") {$tmp=$premier+$deuxieme;}\n   if ($opex=="-") {$tmp=$premier-$deuxieme;}\n   if ($opex=="*") {$tmp=$premier*$deuxieme;}\n   if ($opex=="/") {\n      if ($deuxieme==0) {\n         $tmp="Division by zero !";\n      } else {\n         $tmp=$premier/$deuxieme;\n      }\n   }\n   return ($tmp);\n}', 'meta', '-', NULL, '[french]Retourne la valeur du calcul : syntaxe Scalcul(op,nombre,nombre) ou op peut &ecirc;tre : + - * /[/french]', '1');
INSERT INTO metalang VALUES ('!anti_spam!', 'function MM_anti_spam ($arg) {\n   return("<a href=\\"mailto:".anti_spam($arg, 1)."\\" class=\\"noir\\" target=\\"_blank\\">".anti_spam($arg, 0)."</a>");\n}', 'meta', '-', NULL, '[french]Encode un email et cr&eacute;e un &lta href=mailto ...&gtEmail&lt/a&gt[/french]', '1');
INSERT INTO metalang VALUES ('!msg_foot!', 'function MM_msg_foot () {\n   global $foot1, $foot2, $foot3, $foot4;\n\n   if ($foot1) $MT_foot=stripslashes($foot1)."<br />";\n   if ($foot2) $MT_foot.=stripslashes($foot2)."<br />";\n   if ($foot3) $MT_foot.=stripslashes($foot3)."<br />";\n   if ($foot4) $MT_foot.=stripslashes($foot4);\n   return (aff_langue($MT_foot));\n}', 'meta', '-', NULL, '[french]Gestion des messages du footer du theme (les 4 pieds de page dans Admin / Pr&eacute;f&eacute;rences)[/french]', '1');
INSERT INTO metalang VALUES ('!date!', 'function MM_date () {\n   global $locale, $gmt;\n\n   setlocale (LC_TIME, aff_langue($locale));\n   $MT_date=strftime(translate("daydate"),time()+($gmt*3600));\n   return ($MT_date);\n}', 'meta', '-', NULL, '[french]Date du jour - se base sur le format de daydate (fichier de traduction)[/french]', '1');
INSERT INTO metalang VALUES ('!banner!', 'function MM_banner () {\n   global $banners, $hlpfile;\n\n   if (($banners) and (!$hlpfile)) {\n      ob_start();\n      include("banners.php");\n      $MT_banner=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $MT_banner="";\n   }\n   return ($MT_banner);\n}', 'meta', '-', NULL, '[french]Syst&egrave;me de banni&egrave;re[/french]', '1');
INSERT INTO metalang VALUES ('!search_topics!', 'function MM_search_topics() {\n   global $NPDS_Prefix;\n\n   $MT_search_topics="<form action=\\"search.php\\" method=\\"post\\"><b>".translate("Topics")." </b>";\n   $MT_search_topics.="<select class=\\"textbox_standard\\" name=\\"topic\\"onChange=''submit()''>" ;\n   $MT_search_topics.="<option value=\\"\\">".translate("All Topics")."</option>\\n";\n\n   $rowQ=Q_select("select topicid, topictext from ".$NPDS_Prefix."topics order by topictext", 86400);\n   while(list(,$myrow) = each($rowQ)) {\n     $MT_search_topics.="<option value=\\"".$myrow[''topicid'']."\\">".aff_langue($myrow[''topictext''])."</option>\\n";\n   }\n   $MT_search_topics.="</select></form>";\n   return ($MT_search_topics);\n}', 'meta', '-', NULL, '[french]Liste des Topic => Moteur de recherche interne (Combo)[/french]', '1');
INSERT INTO metalang VALUES ('!search!', 'function MM_search() {\n   $MT_search="<form action=\\"search.php\\" method=\\"post\\"><b>".translate("Search")."</b>\n   <input class=\\"textbox_standard\\" type=\\"name\\" name=\\"query\\" size=\\"10\\"></form>";\n   return ($MT_search);\n}', 'meta', '-', NULL, '[french]Ligne de saisie => Moteur de recherche[/french]', '1');
INSERT INTO metalang VALUES ('!member!', 'function MM_member() {\n   global $cookie, $anonymous;\n\n   $username = $cookie[1];\n   if ($username=="") {$username=$anonymous;}\n   ob_start();Mess_Check_Mail($username);\n      $MT_member=ob_get_contents();\n   ob_end_clean();\n   return ($MT_member);\n}', 'meta', '-', NULL, '[french]Ligne Anonyme / membre gestion du compte / Message Interne (MI)[/french]', '1');
INSERT INTO metalang VALUES ('!nb_online!', 'function MM_nb_online() {\n   list($MT_nb_online, $MT_whoim)=Who_Online();\n   return ($MT_nb_online);\n}', 'meta', '-', NULL, '[french]Nombre de session active[/french]', '1');
INSERT INTO metalang VALUES ('!whoim!', 'function MM_whoim() {\r\n   list($MT_nb_online, $MT_whoim)=Who_Online();\r\n   return ($MT_whoim);\r\n}', 'meta', '-', NULL, '[french]Affiche Qui est en ligne ? + message de bienvenue[/french]', '1');
INSERT INTO metalang VALUES ('!membre_nom!', 'function MM_membre_nom() {\n   global $NPDS_Prefix,$cookie;\n\n   $uname=arg_filter($cookie[1]);\n   $MT_name="";\n   $rowQ = Q_select("select name from ".$NPDS_Prefix."users where uname=''$uname''", 3600);\n   list(,$myrow) = each($rowQ);\n   $MT_name=$myrow[''name''];\n   return ($MT_name);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du nom du membre ou rien[/french]', '1');
INSERT INTO metalang VALUES ('!membre_pseudo!', 'function MM_membre_pseudo() {\n   global $cookie;\n\n   return ($cookie[1]);\n}', 'meta', '-', NULL, '[french]Alias de !membre_nom![/french]', '1');
INSERT INTO metalang VALUES ('blocID', 'function MM_blocID($arg) {\n  return(@oneblock(substr($arg,1), substr($arg,0,1)."B"));\n}', 'meta', '-', NULL, '[french]Fabrique un bloc R (droite) ou L (gauche) en s''appuyant sur l''ID (voir gestionnaire de blocs) pour incorporation / syntaxe : blocID(R1) ou blocID(L2)[/french]', '1');
INSERT INTO metalang VALUES ('!block!', 'function MM_block($arg) {\n   return (meta_lang("blocID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de blocID()[/french]', '1');
INSERT INTO metalang VALUES ('!leftblocs!', 'function MM_leftblocs() {\n   ob_start();\n      leftblocks();\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de gauche[/french]', '1');
INSERT INTO metalang VALUES ('!rightblocs!', 'function MM_rightblocs() {\n   ob_start();\n      rightblocks();\n      $M_Lblocs=ob_get_contents();\n   ob_end_clean();\n   return ($M_Lblocs);\n}', 'meta', '-', NULL, '[french]Fabrique tous les blocs de droite[/french]', '1');
INSERT INTO metalang VALUES ('articleID', 'function MM_articleID($arg) {\n   global $NPDS_Prefix;\n   global $nuke_url;\n\n   $arg = arg_filter($arg);\n   $rowQ = Q_select("SELECT title FROM ".$NPDS_Prefix."stories where sid=''$arg''", 3600);\n   list(,$myrow) = each($rowQ);\n   return ("<a href=\\"$nuke_url/article.php?sid=$arg\\" class=\\"noir\\">".$myrow[''title'']."</a>");\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du titre et fabrication d''une url pointant sur l''article (ID)[/french]', '1');
INSERT INTO metalang VALUES ('!article!', 'function MM_article($arg) {\n   return (meta_lang("articleID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias d''articleID[/french]', '1');
INSERT INTO metalang VALUES ('article_completID', 'function MM_article_completID($arg) {\n   if ($arg>0) {\n      $story_limit=1;\n      $news_tab=prepa_aff_news("article",$arg,"");\n   } else {\n      $news_tab=prepa_aff_news("index","","");\n      $story_limit=abs($arg)+1;\n   }\n   $aid=unserialize($news_tab[$story_limit][''aid'']);\n   $informant=unserialize($news_tab[$story_limit][''informant'']);\n   $datetime=unserialize($news_tab[$story_limit][''datetime'']);\n   $title=unserialize($news_tab[$story_limit][''title'']);\n   $counter=unserialize($news_tab[$story_limit][''counter'']);\n   $topic=unserialize($news_tab[$story_limit][''topic'']);\n   $hometext=unserialize($news_tab[$story_limit][''hometext'']);\n   $notes=unserialize($news_tab[$story_limit][''notes'']);\n   $morelink=unserialize($news_tab[$story_limit][''morelink'']);\n   $topicname=unserialize($news_tab[$story_limit][''topicname'']);\n   $topicimage=unserialize($news_tab[$story_limit][''topicimage'']);\n   $topictext=unserialize($news_tab[$story_limit][''topictext'']);\n   $s_id=unserialize($news_tab[$story_limit][''id'']);\n   if ($aid) {\n      ob_start();\n         themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);\n         $remp=ob_get_contents();\n      ob_end_clean();\n   } else {\n      $remp="";\n   }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration de l''article complet (ID) et themisation pour incorporation<br />si ID > 0   : l''article publi&eacute; avec l''ID indiqu&eacute;e<br />si ID = 0   : le dernier article publi&eacute;<br />si ID = -1  : l''avant dernier ... jusqu''&agrave; -9 (limite actuelle)[/french]', '1');
INSERT INTO metalang VALUES ('!article_complet!', 'function MM_article_complet($arg) {\n   return (meta_lang("article_completID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de article_completID[/french]', '1');
INSERT INTO metalang VALUES ('headlineID', 'function MM_headlineID($arg) {\n   return (@headlines($arg,""));\n}', 'meta', '-', NULL, '[french]R&eacute;cup&eacute;ration du canal RSS (ID) et fabrication d''un retour pour affichage[/french]', '1');
INSERT INTO metalang VALUES ('!headline!', 'function MM_headline($arg) {\n   return (meta_lang("headlineID($arg)"));\n}', 'meta', '-', NULL, '[french]Alias de headlineID[/french]', '1');
INSERT INTO metalang VALUES ('!list_mns!', 'function MM_list_mns() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users WHERE mns=''1''");\n   $MT_mns="<table width=\\"100%\\">";\n   while (list($uname)=sql_fetch_row($query)) {\n      $rowcolor=tablos();\n      $MT_mns.="<tr $rowcolor><td><a href=\\"minisite.php?op=$uname\\" target=\\"_blank\\" class=\\"noir\\">$uname</a></td></tr>";\n   }\n   $MT_mns.="</table>";\n   return ($MT_mns);\n}', 'meta', '-', NULL, '[french]Affiche une liste de tout les membres poss&eacute;dant un minisite avec un lien vers ceux-ci[/french]', '1');
INSERT INTO metalang VALUES ('!LastMember!', 'function MM_LastMember() {\n   global $NPDS_Prefix;\n\n   $query=sql_query("SELECT uname FROM ".$NPDS_Prefix."users ORDER BY uid DESC LIMIT 0,1");\n   $result=sql_fetch_row($query);\n   return ($result[0]);\n}', 'meta', '-', NULL, '[french]Renvoie le pseudo du dernier membre inscrit[/french]', '1');
INSERT INTO metalang VALUES ('!edito!', 'function MM_edito() {\n   list($affich,$M_edito)=fab_edito();\n   if ((!$affich) or ($M_edito=="")) {\n      $M_edito="";\n   }\n   return ($M_edito);\n}', 'meta', '-', NULL, '[french]Fabrique et affiche l''EDITO[/french]', '1');
INSERT INTO metalang VALUES ('!edito-notitle!', '$cmd="!edito-notitle!";', 'meta', '-', NULL, '[french]Supprime le Titre EDITO et le premier niveau de tableau dans l''edito (ce meta-mot n''est actif que dans l''Edito)[/french]', '1');
INSERT INTO metalang VALUES ('!langue!', '$cmd=aff_local_langue("","index.php", "choice_user_language");', 'meta', '-', NULL, '[french]Fabrique une zone de selection des langues disponibles[/french]', '1');
INSERT INTO metalang VALUES ('groupe_text', 'function MM_groupe_text($arg) {\n   global $user;\n\n   $affich=false;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=true;\n   } else {\n      if ($user)\n         $affich=true;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Test si le membre appartient aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par groupe_textID(ID_group) ... !/!<br />Si groupe_ID est nul, la v&eacute;rification portera simplement sur la qualit&eacute; de membre<br />Syntaxe : groupe_text(), groupe_text(10) ou groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang VALUES ('no_groupe_text', 'function MM_no_groupe_text($arg) {\n   global $user;\n\n   $affich=true;\n   $remp="";\n   if ($arg!="") {\n      if (groupe_autorisation($arg, valid_group($user)))\n         $affich=false;\n      if (!$user)\n         $affich=false;\n   } else {\n      if ($user)\n         $affich=false;\n   }\n   if (!$affich) { $remp="!delete!"; }\n   return ($remp);\n}', 'meta', '-', NULL, '[french]Forme de ELSE de groupe_text / Test si le membre n''appartient pas aux(x) groupe(s) et n''affiche que le texte encadr&eacute; par no_groupe_textID(ID_group) ... !/!<br />Si no_groupe_ID est nul, la v&eacute;rification portera sur qualit&eacute; d''anonyme<br />Syntaxe : no_groupe_text(), no_groupe_text(10) ou no_groupe_textID("gp1,gp2,gp3") ... !/![/french]', '1');
INSERT INTO metalang VALUES ('!note!', 'function MM_note() {\n   return ("!delete!");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera jamais affich&eacute;e !note! .... !/![/french]', '1');
INSERT INTO metalang VALUES ('!note_admin!', 'function MM_note_admin() {\n   global $admin;\n\n   if (!$admin)\n      return ("!delete!");\n   else\n      return("<b>nota</b> : ");\n}', 'meta', '-', NULL, '[french]Permet de stocker une note en ligne qui ne sera affich&eacute;e que pour les administrateurs !note_admin! .... !/![/french]', '1');
INSERT INTO metalang VALUES ('!/!', '!\\/!', 'meta', '-', NULL, '[french]Termine LES meta-mot ENCADRANTS (!groupe_text!, !note!, !note_admin!, ...) : le fonctionnement est assez similaire &agrave; [langue] ...[/french]', '1');
INSERT INTO metalang VALUES ('!debugON!', 'function MM_debugON() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $NPDS_debug_cycle=1;\n   $NPDS_debug=true;\n   $NPDS_debug_str="<br />";\n   $NPDS_debug_time=getmicrotime();\n   return ("");\n}', 'meta', '-', NULL, '[french]Active le mode debug[/french]', '1');
INSERT INTO metalang VALUES ('!debugOFF!', 'function MM_debugOFF() {\n   global $NPDS_debug, $NPDS_debug_str, $NPDS_debug_time, $NPDS_debug_cycle;\n\n   $time_end = getmicrotime();\n   $NPDS_debug_str.="=> !DebugOFF!<br /><b>=> exec time for meta-lang : ".round($time_end - $NPDS_debug_time, 4)." / cycle(s) : $NPDS_debug_cycle</b><br />";\n   $NPDS_debug=false;\n   echo $NPDS_debug_str;\n   return ("");\n}', 'meta', '-', NULL, '[french]D&eacute;sactive le mode debug[/french]', '1');
INSERT INTO metalang VALUES ('forum_all', 'function MM_forum_all() {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories ORDER BY cat_id", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche toutes les categories et tous les forums (en fonction des droits)[/french]', '1');
INSERT INTO metalang VALUES ('forum_categorie', 'function MM_forum_categorie($arg) {\n   include_once("functions.php");\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $bid_tab=explode(",",$arg); $sql="";\n   foreach($bid_tab as $cat) {\n      $sql.="cat_id=''$cat'' OR ";\n   }\n   $sql=substr($sql,0,-4);\n   $rowQ1=Q_Select ("SELECT * FROM ".$NPDS_Prefix."catagories WHERE $sql", 3600);\n   $Xcontent=@forum($rowQ1);\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]affiche la (les) categorie(s) XX (en fonction des droits) / liste de categories : "XX,YY,ZZ" [/french]', '1');
INSERT INTO metalang VALUES ('forum_message', 'function MM_forum_message() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   if (($subscribe) and ($user)) {\n      $colspanX=8;\n   } else {\n      $colspanX=7;\n   }\n   if (!$user) {\n      $ibid="<tr align=\\"left\\" class=\\"lignb\\">";\n      $ibid.="<td colspan=\\"$colspanX\\" align=\\"center\\" style=\\"font-size: 10px;\\">".translate("Join us ! As a registered user, cool stuff like : forum''subscribing, special forums (hidden, members ...), post and read status, ... are avaliable.")."</td></tr>";\n   }\n   if (($subscribe) and ($user)) {\n      $ibid="<tr align=\\"left\\" class=\\"lignb\\">";\n      $ibid.="<td colspan=\\"$colspanX\\" align=\\"center\\" style=\\"font-size: 10px;\\">".translate("Check a forum and click on button for receive an Email when a new submission is made in it.")."</td></tr>";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les messages en pied de forum (devenez membre, abonnement ...)[/french]', '1');
INSERT INTO metalang VALUES ('forum_recherche', 'function MM_forum_recherche() {\n   include_once("functions.php");\n\n   $Xcontent=@searchblock();\n   return ($Xcontent);\n}', 'meta', '-', NULL, '[french]Affiche la zone de saisie du moteur de recherche des forums[/french]', '1');
INSERT INTO metalang VALUES ('forum_icones', 'function MM_forum_icones() {\n   include_once("functions.php");\n\n   if ($ibid=theme_image("forum/icons/red_folder.gif")) {$imgtmpR=$ibid;} else {$imgtmpR="images/forum/icons/red_folder.gif";}\n   if ($ibid=theme_image("forum/icons/folder.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/icons/folder.gif";}\n   $ibid="<img src=\\"$imgtmpR\\" border=\\"\\" alt=\\"\\" /> = ".translate("New Posts since your last visit.")."<br />";\n   $ibid.="<img src=\\"$imgtmp\\" border=\\"\\" alt=\\"\\" /> = ".translate("No New Posts since your last visit.");\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Affiche les icones + legendes decrivant les marqueurs des forums[/french]', '1');
INSERT INTO metalang VALUES ('forum_subscribeON', 'function MM_forum_subscribeON() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $ibid="<form action=\\"forum.php\\" method=\\"post\\">";\n      $ibid.="<input type=\\"hidden\\" name=\\"op\\" value=\\"maj_subscribe\\">";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang VALUES ('forum_bouton_subscribe', 'function MM_forum_bouton_subscribe() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   if (($subscribe) and ($user)) {\n      return ("<td align=\\"center\\" width=\\"5%\\" class=\\"header\\"><input class=\\"bouton_standard\\" type=\\"submit\\" name=\\"Xsub\\" value=\\"".translate("Ok")."\\"></td>");\n   } else {\n      return ("");\n   }\n}', 'meta', '-', NULL, '[french]Affiche la colonne avec le bouton de gestion des abonnements[/french]', '1');
INSERT INTO metalang VALUES ('forum_subscribeOFF', 'function MM_forum_subscribeOFF() {\n   include_once("functions.php");\n   global $subscribe, $user;\n\n   $ibid="";\n   if (($subscribe) and ($user)) {\n      $ibid="</form>";\n   }\n   return ($ibid);\n}', 'meta', '-', NULL, '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french]', '1');
INSERT INTO metalang VALUES ('forum_subfolder', 'function MM_forum_subfolder($arg) {\r\n\r\n   $forum=arg_filter($arg);\r\n   $content=sub_forum_folder($forum);\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Renvoie le gif permettant de savoir si de nouveaux messages sont disponibles dans le forum X<br />Syntaxe : sub_folder(X) ou X est le num&eacute;ro du forum[/french][english][/english]', '1');
INSERT INTO metalang VALUES ('insert_flash', 'function MM_insert_flash($name,$width,$height,$bgcol) {\n   return ("<object codebase=\\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflas\n   classid=\\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\\"\n   h.cab#version=6,0,0,0\\" width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   id=\\"".$name."\\" align=\\"middle\\">\n \n   <param name=\\"allowScriptAccess\\"\n   value=\\"sameDomain\\" />\n \n   <param name=\\"movie\\"\n   value=\\"flash/".$name."\\" />\n \n   <param name=\\"quality\\" value=\\"high\\" />\n   <param name=\\"bgcolor\\"\n   value=\\"".$bgcol."\\" />\n\n   <embed src=\\"flash/".$name."\\"\n   quality=\\"high\\" bgcolor=\\"".$bgcol."\\"\n   width=\\"".$width."\\"\n   height=\\"".$height."\\"\n   name=\\"".$name."\\" align=\\"middle\\"\n   allowScriptAccess=\\"sameDomain\\"\n   type=\\"application/x-shockwave-flash\\"\n   pluginspage=\\"http://www.macromedia.com/go/getflashplayer\\" />\n\n   </object>");\n}', 'meta', '-', NULL, '[french]Insert un fichier flash (.swf) se trouvant dans un dossier "flash" de la racine du site. Syntaxe : insert_flash (nom du fichier.swf, largeur, hauteur, couleur fond : #XXYYZZ).[/french]', '1');
INSERT INTO metalang VALUES ('!mailadmin!', '$cmd="<a href=\\"mailto:".anti_spam($GLOBALS[''adminmail''], 1)."\\" class=\\"noir\\" target=\\"_blank\\">".anti_spam($GLOBALS[''adminmail''], 0)."</a>";', 'meta', '-', NULL, '[french]Affiche un lien vers l''adresse mail de l''administrateur.[/french]', '1');
INSERT INTO metalang VALUES ('!login!', 'function MM_login() {\n   $boxstuff  = "<form action=\\"user.php\\" method=\\"post\\">";\n   $boxstuff .= translate("Nickname")."<br />";\n   $boxstuff .= "<input class=\\"textbox_standard\\" type=\\"text\\" name=\\"uname\\" size=\\"12\\" maxlength=\\"25\\" /><br />";\n   $boxstuff .= translate("Password")."<br />";\n   $boxstuff .= "<input class=\\"textbox_standard\\" type=\\"password\\" name=\\"pass\\" size=\\"12\\" maxlength=\\"20\\" /><br />";\n   $boxstuff .= "<input type=\\"hidden\\" name=\\"op\\" value=\\"login\\" />";\n   $boxstuff .= "<input class=\\"bouton_standard\\" type=\\"submit\\" value=\\"".translate("Submit")."\\" />";\n   $boxstuff .= "</form>";\n   return ($boxstuff);\n}', 'meta', '-', NULL, '[french]Affiche les champs de connexion au site.[/french]', '1');
INSERT INTO metalang VALUES ('!connexion!', '$cmd=meta_lang("!login!");', 'meta', '-', NULL, '[french]Alias de !login![/french]', '1');
INSERT INTO metalang VALUES ('!administration!', 'function MM_administration() {\n   global $admin;\n   if ($admin) {\n      return("<a href=\\"admin.php\\">".translate("Administration Tools")."</a>");\n   } else {\n      return("");\n   }\n}', 'meta', '-', NULL, '[french]Affiche un lien vers l''administration du site uniquement si l''on est connect&eacute; en tant qu''admin[/french]', '1');
INSERT INTO metalang VALUES ('admin_infos', 'function MM_admin_infos($arg) {\n   global $NPDS_Prefix;\n\n   $arg = arg_filter($arg);\n   $rowQ1 = Q_select ("SELECT url, email FROM ".$NPDS_Prefix."authors where aid=''$arg''", 86400);\n   list(,$myrow) = each($rowQ1);\n   if (isset($myrow[''url''])) {\n      $auteur="<a href=\\"".$myrow[''url'']."\\">$arg</a>";\n   } elseif (isset($myrow[''email''])) {\n      $auteur="<a href=\\"mailto:".$myrow[''email'']."\\">$arg</a>";\n   } else {\n      $auteur=$arg;\n   }\n   return ($auteur);\n}', 'meta', '-', NULL, '[french]Affiche le Nom ou le WWW ou le Mail de l''administrateur / syntaxe : admin_infos(nom_de_admin)[/french]', '1');
INSERT INTO metalang VALUES ('theme_img', 'function MM_theme_img($arg) {\n   return (MM_img($arg));\n}', 'meta', '-', NULL, '[french]Localise l''image et affiche une ressource de type &lt;img src= / syntaxe : theme_img(forum/onglet.gif)[/french]', '1');
INSERT INTO metalang VALUES ('!logo!', '$cmd="<img src=\\"".$GLOBALS[''site_logo'']."\\" border=\\"0\\" alt=\\"\\">";', 'meta', '-', NULL, '[french]Affiche le logo du site (admin/preferences).[/french]', '1');
INSERT INTO metalang VALUES ('rotate_img', 'function MM_rotate_img($arg) {\r\n   mt_srand((double)microtime()*1000000);\r\n   $arg = arg_filter($arg);\r\n   $tab_img=explode(",",$arg);\r\n\r\nif (count($tab_img)>1) {\r\n   $imgnum = mt_rand(0, count($tab_img)-1);\r\n} else if (count($tab_img)==1) {\r\n   $imgnum = 0;\r\n} else {\r\n   $imgnum = -1;\r\n}\r\nif ($imgnum!=-1) {\r\n   $Xcontent="<img src=\\"".$tab_img[$imgnum]."\\" border=\\"0\\" alt=\\"".$tab_img[$imgnum]."\\" title=\\"".$tab_img[$imgnum]."\\" />";\r\n}\r\n   return ($Xcontent);\r\n}', 'meta', '-', NULL, '[french]Affiche une image al&eacute;atoire - les images de la liste sont s&eacute;par&eacute;e par une virgule / syntaxe rotate_img("http://www.npds.org/users_private/user/1.gif,http://www.npds.org/users_private/user/2.gif, ...")[/french]', '1');
INSERT INTO metalang VALUES ('!sql_nbREQ!', 'function MM_sql_nbREQ() {\r\n   global $sql_nbREQ;\r\n\r\n   return ("SQL REQ : $sql_nbREQ");\r\n}', 'meta', '-', NULL, '[french]Affiche le nombre de requ&ecirc;te SQL pour la page courante[/french]', '1');
INSERT INTO metalang VALUES ('comment_system', 'function MM_comment_system ($file_name,$topic) {\r\n\r\nglobal $NPDS_Prefix,$anonpost,$moderate,$admin,$user;\r\nob_start();   \r\n   if (file_exists("modules/comments/$file_name.conf.php")) {\r\n      include ("modules/comments/$file_name.conf.php");\r\n      include ("modules/comments/comments.php");\r\n   }\r\n   $output = ob_get_contents();\r\nob_end_clean();\r\nreturn ($output);\r\n}', 'meta', '-', '', '[french]Permet de mettre en oeuvre un syst&egrave;me de commentaire complet / la mise en oeuvre n&eacute;cessite :<br /> - un fichier dans modules/comments/xxxx.conf.php de la m&ecirc;me structure que les autres<br /> - un appel coh&eacute;rent avec la configuration de ce fichier<br /><br />L''appel est du type : comments($file_name, $topic) - exemple comment_system(edito,1) - le fichier s''appel donc edito.conf.php[/french]', '1');
INSERT INTO metalang VALUES ('top_stories', 'function MM_top_stories ($arg) {\r\n   $lugar=1;\r\n   $content="";\r\n\r\n   $arg = arg_filter($arg);\r\n\r\n   $xtab=news_aff("libre","order by counter DESC limit 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      $rowcolor = tablos();\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($counter>0) {\r\n        $content.="<tr $rowcolor><td>$lugar: <a href=\\"article.php?sid=$sid\\" class=\\"noir\\">".aff_langue($title)."</a></td><td align=\\"right\\">".wrh($counter)." ".translate("times")."</td></tr>";\r\n         $lugar++;\r\n     }\r\n   }\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles / syntaxe : top_stories(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_commented_stories', 'function MM_top_commented_stories ($arg) {\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $xtab=news_aff("libre","order by comments DESC  limit 0, ".$arg*2,0,$arg*2);\r\n   $story_limit=0;\r\n   while (($story_limit<$arg) and ($story_limit<sizeof($xtab))) {\r\n      $rowcolor = tablos();\r\n      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments) = $xtab[$story_limit];\r\n      $story_limit++;\r\n      if($comments>0) {\r\n         $content.="<tr $rowcolor><td>$lugar: <a href=\\"article.php?sid=$sid\\" class=\\"noir\\">".aff_langue($title)."</a></td><td align=\\"right\\">".wrh($comments)."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n\r\n  return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles les plus comment&eacute;s / syntaxe : top_commented_stories(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_categories', 'function MM_top_categories($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select catid, title, counter from ".$NPDS_Prefix."stories_cat order by counter DESC limit 0,$arg");\r\n   while (list($catid, $title, $counter) = sql_fetch_row($result)) {\r\n      $rowcolor = tablos();\r\n      if ($counter>0) {\r\n         $content.="<tr $rowcolor><td>$lugar: <a href=\\"index.php?op=newindex&amp;catid=$catid\\" class=\\"noir\\">".aff_langue($title)."</a></td><td align=\\"right\\">".wrh($counter)."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x cat&eacute;gories des articles / syntaxe : top_categories(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_sections', 'function MM_top_sections ($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select artid, title, counter from ".$NPDS_Prefix."seccont order by counter DESC limit 0,$arg");\r\n   while (list($artid, $title, $counter) = sql_fetch_row($result)) {\r\n      $rowcolor = tablos();\r\n      $content.="<tr $rowcolor><td>$lugar: <a href=\\"sections.php?op=viewarticle&amp;artid=$artid\\" class=\\"noir\\">".aff_langue($title)."</a></td><td align=\\"right\\">".wrh($counter)." ".translate("times")."</td></tr>";\r\n      $lugar++;\r\n   }\r\n   sql_free_result($result);\r\n  \r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x articles des rubriques / syntaxe : top_sections(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_reviews', 'function MM_top_reviews ($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select id, title, hits from reviews order by hits DESC limit 0,$arg");\r\n   while (list($id, $title, $hits) = sql_fetch_row($result)) {\r\n      $rowcolor = tablos();\r\n      if ($hits>0) {\r\n         $content.= "<tr $rowcolor><td>$lugar: <a href=\\"reviews.php?op=showcontent&amp;id=$id\\" class=\\"noir\\">$title</a></td><td align=right>".wrh($hits)." ".translate("times")."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des critiques / syntaxe : top_reviews(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_authors', 'function MM_top_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select aid, counter from authors order by counter DESC limit 0,$arg");\r\n   while (list($aid, $counter) = sql_fetch_row($result)) {\r\n      $rowcolor = tablos();\r\n      if ($counter>0) {\r\n         $content.="<tr $rowcolor><td>$lugar: <a href=\\"search.php?query=&amp;author=$aid\\" class=\\"noir\\">$aid</a></td><td align=\\"right\\">".wrh($counter)."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs / syntaxe : top_authors(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_polls', 'function MM_top_polls ($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select pollID, pollTitle, voters from poll_desc order by voters DESC limit 0,$arg");\r\n   while (list($pollID, $pollTitle, $voters) = sql_fetch_row($result)) {\r\n      if ($voters>0) {\r\n         $rowcolor = tablos();\r\n         $content.="<tr $rowcolor><td>$lugar: <a href=\\"pollBooth.php?op=results&amp;pollID=$pollID\\" class=\\"noir\\">".aff_langue($pollTitle)."</a></td><td align=\\"right\\">".wrh($voters)."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des sondages / syntaxe : top_polls(x)[/french]', '1');
INSERT INTO metalang VALUES ('top_storie_authors', 'function MM_top_storie_authors ($arg) {\r\n   global $NPDS_Prefix;\r\n   $lugar=1;\r\n   $content="";\r\n   $arg = arg_filter($arg);\r\n\r\n   $result = sql_query("select uname, counter from users order by counter DESC limit 0,$arg");\r\n   while (list($uname, $counter) = sql_fetch_row($result)) {\r\n      if ($counter>0) {\r\n         $rowcolor = tablos();\r\n         $content.="<tr $rowcolor><td>$lugar: <a href=\\"user.php?op=userinfo&amp;uname=$uname\\" class=\\"noir\\">$uname</a></td><td align=\\"right\\">".wrh($counter)."</td></tr>";\r\n         $lugar++;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n\r\n   return($content);\r\n}', 'meta', '-', '', '[french]Affiche le titre et un lien sur les Top x des auteurs de news (membres) / syntaxe : top_storie_authors(x)[/french]', '1');
INSERT INTO metalang VALUES ('topic_all', 'function MM_topic_all($arg) {\r\n   global $NPDS_Prefix, $tipath;\r\n   $segment = arg_filter($arg);\r\n\r\n   $count=0; $aff="";\r\n   $result = sql_query("select topicid, topicname, topicimage, topictext from ".$NPDS_Prefix."topics order by topicname");\r\n   while(list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {\r\n      $aff.="<td align=\\"center\\">";\r\n      if ((($topicimage) or ($topicimage!="")) and (file_exists("$tipath$topicimage"))) {\r\n         $aff.="<a href=\\"index.php?op=newtopic&amp;topic=$topicid\\"><img src=\\"$tipath$topicimage\\" border=\\"0\\" alt=\\"\\" />";\r\n         $aff.="<br />".aff_langue($topictext);\r\n      } else {\r\n         $aff.="<a href=\\"index.php?op=newtopic&amp;topic=$topicid\\">".aff_langue($topictext);\r\n      }\r\n      $aff.="</a></td>";\r\n      $count++;\r\n      if ($count==$segment) {\r\n         $aff.="</tr><tr>";\r\n         $count=0;\r\n      }\r\n   }\r\n   sql_free_result($result);\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les sujets avec leurs images.<br />Syntaxe : topic_all(X) ou X indique le niveau de rupture dans la liste[/french][english]...[/english]', '1');
INSERT INTO metalang VALUES ('topic_subscribeOFF', 'function MM_topic_subscribeOFF() {\r\n   $aff= "<input type=\\"hidden\\" name=\\"op\\" value=\\"maj_subscribe\\">";\r\n   $aff.="<input class=\\"bouton_standard\\" type=\\"submit\\" name=\\"ok\\" value=\\"".translate("Submit")."\\">";\r\n   $aff.="</form>";\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Ferme la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang VALUES ('topic_subscribeON', 'function MM_topic_subscribeON() {\r\n   return ("<form action=\\"topics.php\\" method=\\"post\\">");\r\n}\r\n', 'meta', '-', '', '[french]Ouvre la FORM de gestion des abonnements (attention a l''imbrication de FORM)[/french][english]...[/english]', '1');
INSERT INTO metalang VALUES ('topic_subscribe', 'function MM_topic_subscribe($arg) {\r\n   global $NPDS_Prefix, $subscribe, $user, $cookie;\r\n   $segment = arg_filter($arg);\r\n\r\n   $count=0; $aff="";\r\n   if ($subscribe) {\r\n      if ($user) {\r\n         $result = sql_query("select topicid, topictext from ".$NPDS_Prefix."topics order by topicname");\r\n         while(list($topicid, $topictext) = sql_fetch_row($result)) {\r\n            $resultX = sql_query("select topicid from ".$NPDS_Prefix."subscribe where uid=''$cookie[0]'' and topicid=''$topicid''");\r\n            if (sql_num_rows($resultX)=="1")\r\n               $checked="checked";\r\n            else\r\n               $checked="";\r\n            $aff.="<td nowrap=\\"nowrap\\"><input class=\\"texbox\\" type=\\"checkbox\\" name=\\"Subtopicid[$topicid]\\" $checked> ".aff_langue($topictext)."</td>";\r\n            $count++;\r\n            if ($count==$segment) {\r\n               $aff.="</tr><tr>";\r\n               $count=0;\r\n            }\r\n         }\r\n         sql_free_result($result);\r\n      }\r\n   }\r\n   return ($aff);\r\n}', 'meta', '-', '', '[french]Affiche les noms des sujets avec la situation de l''abonnement du membre. Permet au membre de g&eacute;rer ces abonnements (aux sujets).\r\nSyntaxe : topic_subscribe(X) ou X indique le niveau de rupture dans la liste[/french][english]...[/english]', '1');
INSERT INTO metalang VALUES ('yt_video', 'function MM_yt_video($id_yt_video,$bloc_width,$bloc_height) {\r\n   $content="";\r\n   $id_yt_video = arg_filter($id_yt_video);\r\n   $bloc_width = arg_filter($bloc_width);\r\n   $bloc_height = arg_filter($bloc_height);\r\n\r\n   $content .=''<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="''.$bloc_width.''" height="''.$bloc_height.''" id="bloc_FlashContent">\r\n    <param name="movie" value="http://www.youtube.com/v/''.$id_yt_video.''" />\r\n    <param name="play" value="false" />\r\n    <param name="allowfullscreen" value="true" />\r\n    <!--[if !IE]>-->\r\n     <object type="application/x-shockwave-flash" data="http://www.youtube.com/v/''.$id_yt_video.''" width="''.$bloc_width.''" height="''.$bloc_height.''">\r\n     <param name="play" value="false" />\r\n     <param name="allowfullscreen" value="true" />\r\n    <!--<![endif]-->\r\n    <a href="http://www.adobe.com/go/getflashplayer">\r\n    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />\r\n    </a>\r\n    <!--[if !IE]>-->\r\n     </object>\r\n    <!--<![endif]-->\r\n    </object>'';\r\n   \r\n   return ($content);\r\n}', 'meta', '-', '', '[french]Inclusion video YouTube.  Syntaxe : yt_video(ID de la vid&eacute;o,largeur,hauteur).[/french][english]...[/english]', '1');
INSERT INTO metalang VALUES ('espace_groupe', 'function MM_espace_groupe($gr, $t_gr, $i_gr) {\r\n$gr = arg_filter($gr);\r\n$t_gr = arg_filter($t_gr);\r\n$i_gr = arg_filter($i_gr);\r\n\r\nreturn (fab_espace_groupe($gr, $t_gr, $i_gr));\r\n}', 'meta', '-', NULL, '[french]Fabrique un WorkSpace / syntaxe : espace_groupe(groupe_id, aff_name_groupe, aff_img_groupe) ou groupe_id est l''ID du groupe - aff_name_groupe(0 ou 1) permet d''afficher le nom du groupe - aff_img_groupe(0 ou 1) permet d''afficher l''image associ&eacute;e au groupe.[/french]', '1');
INSERT INTO metalang VALUES ('^', '', 'docu', '-', NULL, '[french]Dans un texte quelconque, ^ &agrave; la fin d&#39;un mot permet de le prot&eacute;ger contre meta-lang / Ex : Dev Dev^ ne donne pas un r&eacute;sultat identique[/french]', '1');
INSERT INTO metalang VALUES ('!N_publicateur!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nom de l&#39;administrateur ayant publi&eacute; l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_emetteur!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nom de l&#39;auteur de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_date!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la date de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_date_y!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par l&#39;ann&eacute;e de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_date_m!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le mois de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_date_d!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le jour de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_date_h!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par l&#39;heure de publication de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_print!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien pour imprimer l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_friend!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien pour envoyer l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_titre!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le titre de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_texte!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le texte de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_id!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le num&eacute;ro de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_sujet!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par un lien HTML et l&#39;image du sujet de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_note!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le de la note de l&#39;article si elle existe / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_nb_lecture!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de lecture effective de l&#39;article / actif dans index-news.html et detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_suite!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML permettant de lire la suite de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_nb_carac!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de caract&egrave;re suppl&eacute;mentaire de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_read_more!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien &#39;lire la suite&#39; de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_nb_comment!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le nombre de commentaire de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_link_comment!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien vers les commentaires de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_categorie!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par lien vers la cat&eacute;gorie de l&#39;article / actif dans index-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_previous_article!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML pointant sur l&#39;article pr&eacute;c&eacute;dent / actif detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_next_article!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le lien HTML sur l&#39;article suivant / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_boxrel_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par titre du bloc &#39;lien relatif&#39; / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!N_boxrel_stuff!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le contenu du bloc &#39;lien relatif&#39; / actif dans detail-news.html[/french]', '1');
INSERT INTO metalang VALUES ('!B_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le titre du bloc / actif dans bloc.html[/french]', '1');
INSERT INTO metalang VALUES ('!B_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par par le contenu du bloc / actif dans bloc.html[/french]', '1');
INSERT INTO metalang VALUES ('!B_class_title!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la class CSS du titre - voir le gestionnaire de bloc de NPDS / actif dans bloc.html[/french]', '1');
INSERT INTO metalang VALUES ('!B_class_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par la class CSS du contenu - voir le gestionnaire de bloc de NPDS  / actif dans bloc.html[/french]', '1');
INSERT INTO metalang VALUES ('!editorial_content!', '', 'them', '-', NULL, '[french]Sera remplac&eacute; par le contenu de l&#39;Edito / actif que si editorial.html existe dans votre theme[/french]', '1');
INSERT INTO metalang VALUES ('!PHP!', '', 'them', '-', NULL, '[french]Int&eacute;gration de code PHP "noy&eacute;" dans vos fichiers html de th&egrave;mes :<br />\r\n=> !PHP! commentaire vous permettant de trouver le php noy&eacute; -> in fine sera remplac&eacute; par ""<br />\r\n=> &lt;!--meta  doit pr&eacute;c&eacute;der votre code php -> in fine sera remplac&eacute; par ""<br />\r\n   => meta-->   doit suivre votre code php -> in fine sera remplac&eacute; par ""<br />\r\n<br />\r\n&nbsp;Exemple :<br />\r\n&nbsp;&nbsp;!PHP!&lt;!--meta<br />\r\n&nbsp;&nbsp;&lt;?php<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;global $cookie;<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;$username = $cookie[1];<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;if ($username == "") {<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo "Create an account";<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;} else {<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo "Welcome : $username";<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;}<br />\r\n&nbsp;&nbsp;?><br />\r\n&nbsp;&nbsp;meta-->[/french]', '1');

-- --------------------------------------------------------

-- 
-- Structure de la table `modules`
-- 

CREATE TABLE modules (
  mid int(10) NOT NULL auto_increment,
  mnom varchar(255) NOT NULL default '',
  minstall int(1) NOT NULL default '0',
  PRIMARY KEY  (mid),
  KEY mnom (mnom)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `modules`
-- 

INSERT INTO modules VALUES (1, 'archive-stories', 1);
INSERT INTO modules VALUES (2, 'wspad', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `optimy`
-- 

CREATE TABLE optimy (
  optid int(11) NOT NULL auto_increment,
  optgain decimal(10,3) default NULL,
  optdate varchar(11) default NULL,
  opthour varchar(8) default NULL,
  optcount int(11) default '0',
  PRIMARY KEY  (optid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `optimy`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `poll_data`
-- 

CREATE TABLE poll_data (
  pollID int(11) NOT NULL default '0',
  optionText varchar(255) NOT NULL default '',
  optionCount int(11) NOT NULL default '0',
  voteID int(11) NOT NULL default '0',
  pollType int(1) NOT NULL default '0'
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `poll_data`
-- 

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

-- --------------------------------------------------------

-- 
-- Structure de la table `poll_desc`
-- 

CREATE TABLE poll_desc (
  pollID int(11) NOT NULL auto_increment,
  pollTitle char(100) NOT NULL default '',
  timeStamp int(11) NOT NULL default '0',
  voters mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (pollID)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `poll_desc`
-- 

INSERT INTO poll_desc VALUES (2, 'NPDS', 1004108978, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `posts`
-- 

CREATE TABLE posts (
  post_id int(10) NOT NULL auto_increment,
  post_idH int(10) NOT NULL default '0',
  image varchar(100) NOT NULL default '',
  topic_id int(10) NOT NULL default '0',
  forum_id int(10) NOT NULL default '0',
  poster_id int(10) default NULL,
  post_text text,
  post_time varchar(20) default NULL,
  poster_ip varchar(16) default NULL,
  poster_dns text,
  post_aff tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (post_id),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY post_aff (post_aff)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `posts`
-- 

INSERT INTO posts VALUES (1, 0, 'icon4.gif', 1, 1, 2, 'Demo', '2011-10-26 17:00', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (2, 0, 'icon8.gif', 1, 1, 2, 'R&eacute;ponse', '2012-03-05 22:36', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (3, 0, 'icon1.gif', 2, 2, 1, 'Message 1', '2013-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (4, 3, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse au Message 1', '2003-05-14 22:54', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (5, 4, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse &agrave; la r&eacute;ponse du Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (6, 0, 'icon1.gif', 2, 2, 1, 'R&eacute;ponse au Message 1', '2013-05-14 22:55', '1.1.76.115', '', 1);
INSERT INTO posts VALUES (7, 0, '         ', 2, -2, 2, 'Bien, bien et m&ecirc;me mieux encore', '2012-07-22 13:42:22', '1.1.76.115', '', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `priv_msgs`
-- 

CREATE TABLE priv_msgs (
  msg_id int(10) NOT NULL auto_increment,
  msg_image varchar(100) default NULL,
  subject varchar(100) default NULL,
  from_userid int(10) NOT NULL default '0',
  to_userid int(10) NOT NULL default '0',
  msg_time varchar(20) default NULL,
  msg_text text,
  read_msg tinyint(10) NOT NULL default '0',
  type_msg int(1) NOT NULL default '0',
  dossier varchar(50) NOT NULL default '...',
  PRIMARY KEY  (msg_id),
  KEY to_userid (to_userid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `priv_msgs`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `publisujet`
-- 

CREATE TABLE publisujet (
  aid varchar(30) NOT NULL default '',
  secid2 int(30) NOT NULL default '0',
  type int(1) NOT NULL default '0'
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `publisujet`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `queue`
-- 

CREATE TABLE queue (
  qid smallint(5) unsigned NOT NULL auto_increment,
  uid mediumint(9) NOT NULL default '0',
  uname varchar(40) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  story text,
  bodytext mediumtext,
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL default 'Linux',
  date_debval datetime default NULL,
  date_finval datetime default NULL,
  auto_epur tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (qid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `queue`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rblocks`
-- 

CREATE TABLE rblocks (
  id tinyint(3) unsigned NOT NULL auto_increment,
  title varchar(255) default NULL,
  content text,
  member varchar(60) NOT NULL default '0',
  Rindex tinyint(4) NOT NULL default '0',
  cache mediumint(8) unsigned NOT NULL default '0',
  actif smallint(5) unsigned NOT NULL default '1',
  css tinyint(1) NOT NULL default '0',
  aide mediumtext,
  PRIMARY KEY  (id),
  KEY Rindex (Rindex)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `rblocks`
-- 

INSERT INTO rblocks VALUES (1, 'Un Bloc ...', 'Vous pouvez ajouter, &eacute;diter et supprimer des Blocs &agrave; votre convenance.', '0', 99, 0, 1, 0, '');
INSERT INTO rblocks VALUES (2, 'Information', '<p align="center"><a href="http://www.npds.org" target="_blank"><img src="images/powered/miniban-bleu.png" border="0" alt="npds_logo" /></a></p>', '0', 0, 0, 1, 0, '');
INSERT INTO rblocks VALUES (3, 'Bloc membre', 'function#userblock', '0', 5, 0, 1, 0, '');
INSERT INTO rblocks VALUES (4, 'Lettre d''information', 'function#lnlbox', '0', 6, 86400, 1, 0, '');
INSERT INTO rblocks VALUES (5, 'Anciens Articles', 'function#oldNews\r\nparams#$storynum', '0', 4, 3600, 1, 0, '');
INSERT INTO rblocks VALUES (7, 'Cat&eacute;gories', 'function#category', '0', 2, 28800, 1, 0, '');
INSERT INTO rblocks VALUES (8, 'Article du Jour', 'function#bigstory', '0', 3, 60, 1, 0, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `referer`
-- 

CREATE TABLE referer (
  rid int(11) NOT NULL auto_increment,
  url varchar(100) NOT NULL default '',
  PRIMARY KEY  (rid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `referer`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `related`
-- 

CREATE TABLE related (
  rid int(11) NOT NULL auto_increment,
  tid int(11) NOT NULL default '0',
  name varchar(30) NOT NULL default '',
  url varchar(200) NOT NULL default '',
  PRIMARY KEY  (rid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `related`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `reviews`
-- 

CREATE TABLE reviews (
  id int(10) NOT NULL auto_increment,
  date date NOT NULL default '0000-00-00',
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) default NULL,
  email varchar(60) default NULL,
  score int(10) NOT NULL default '0',
  cover varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  hits int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `reviews`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `reviews_add`
-- 

CREATE TABLE reviews_add (
  id int(10) NOT NULL auto_increment,
  date date default NULL,
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  score int(10) NOT NULL default '0',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `reviews_add`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `reviews_main`
-- 

CREATE TABLE reviews_main (
  title text default NULL,
  description text
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `reviews_main`
-- 

INSERT INTO reviews_main VALUES ('Votre point de vue nous int&eacute;resse', 'Participez &agrave; la vie du site en apportant vos critiques mais restez toujours positif.');

-- --------------------------------------------------------

-- 
-- Structure de la table `rubriques`
-- 

CREATE TABLE rubriques (
  rubid int(4) NOT NULL auto_increment,
  rubname varchar(255) NOT NULL default '',
  intro text NOT NULL,
  enligne tinyint(1) NOT NULL default '0',
  ordre int(2) NOT NULL default '0',
  UNIQUE KEY rubid (rubid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `rubriques`
-- 

INSERT INTO rubriques VALUES (1, 'Divers', '', 1, 9998);
INSERT INTO rubriques VALUES (2, 'Presse-papiers', '', 0, 9999);
INSERT INTO rubriques VALUES (3, 'Mod&egrave;le', '', 1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `seccont`
-- 

CREATE TABLE seccont (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content longtext NOT NULL,
  counter int(11) NOT NULL default '0',
  author varchar(50) NOT NULL default '',
  ordre int(2) NOT NULL default '0',
  userlevel varchar(34) NOT NULL default '0',
  crit1 text,
  crit2 text,
  crit3 text,
  crit4 text,
  crit5 text,
  crit6 text,
  crit7 text,
  crit8 text,
  crit9 text,
  crit10 text,
  crit11 text,
  crit12 text,
  crit13 text,
  crit14 text,
  crit15 text,
  crit16 text,
  crit17 text,
  crit18 text,
  crit19 text,
  crit20 text,
  timestamp varchar(14) NOT NULL default '0',
  PRIMARY KEY  (artid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `seccont`
-- 

INSERT INTO seccont VALUES (1, 3, 'Rajouts &agrave; faire dans la CSS', '<p>Sur les anciens themes, 4 manipulations doivent &ecirc;tre r&eacute;alis&eacute;es sur la feuille de style : <br /><br /><strong>1) Supprimer la balise span de l''edito <br /></strong><code><span style="color: #000000;">/*****************/<br />#editorial span { <br />&nbsp;&nbsp;&nbsp;&nbsp;display: none; <br />}<br /></span></code><br /><strong>2) Ajouter le complement pour vos formulaires sous peine d''affichage cahotique</strong><code><span style="color: #000000;"> /************************************************************************ <br />CSS Compl&eacute;mentaire pour vos Formulaires <br />*************************************************************************/ <br />form {&nbsp; /* D&eacute;limitation de votre Formulaire*/<br />&nbsp;&nbsp; &nbsp;padding: 2px;<br />&nbsp;&nbsp; &nbsp;width: 99%;<br />}<br />form p {<br />&nbsp;&nbsp; &nbsp;line-height:100%;<br />}<br />fieldset { /* Regroupe les diff&eacute;rentes zones de votre Formulaire */<br />&nbsp;&nbsp; &nbsp;padding: 15px;&nbsp; /* padding in fieldset support spotty in IE *//*margin: 0;*/<br />&nbsp;&nbsp; &nbsp;border: 1px solid #000000;<br />}<br />legend { /* Titre pour identifier chaque fieldset */<br />&nbsp;&nbsp; &nbsp;font-size:1.1em;<br />&nbsp;&nbsp; &nbsp;color: #183a55;<br />&nbsp;&nbsp; &nbsp;font-weight: bold;<br />&nbsp;&nbsp; &nbsp;padding-bottom:3px;<br />}<br />label {<br />&nbsp;&nbsp; &nbsp;display: block;<br />&nbsp;&nbsp; &nbsp;float: left;<br />&nbsp;&nbsp; &nbsp;width: 180px;<br />&nbsp;&nbsp; &nbsp;margin-top: 3px;<br />&nbsp;&nbsp; &nbsp;padding-right: 10px;<br />&nbsp;&nbsp; &nbsp;text-align: right;<br />}<br />input[type=Submit], [type=Reset], [type=Return] {<br />&nbsp;&nbsp; &nbsp;background-color: #f4912c;<br />&nbsp;&nbsp; &nbsp;padding:1px 5px 1px 1px;<br />&nbsp;&nbsp; &nbsp;border: #ffffff 1px solid;<br />&nbsp;&nbsp; &nbsp;color: #ffffff;<br />&nbsp;&nbsp; &nbsp;text-decoration: none;<br />}<br />input[type=radio] {<br />&nbsp;&nbsp; &nbsp;border: 0;<br />&nbsp;&nbsp; &nbsp;background:transparent<br />}<br />input[type=Image] {<br />&nbsp;&nbsp; &nbsp;border: 0;<br />}<br />input, select, radio, checkbox, textarea {<br />&nbsp;&nbsp; &nbsp;border: #000000 1px solid;<br />&nbsp;&nbsp; &nbsp;color: #000000;<br />&nbsp;&nbsp; &nbsp;background-color: #ffffff;<br />&nbsp;&nbsp; &nbsp;margin-top: 2px;<br />&nbsp;&nbsp; &nbsp;padding: 0;<br />}<br />input[type=text]:focus, textarea:focus{<br />&nbsp;&nbsp; &nbsp;background-color: #dbeaf2;<br />}<br />form label span {<br />&nbsp;&nbsp; &nbsp;color: red;<br />}<br />/* Utilis&eacute; dans user ! */<br />form br {<br />&nbsp;&nbsp; &nbsp;clear:left;<br />}</span></code><br /><br /><strong>3) Ajouter le code n&eacute;cessaire pour faire fonctionner les infoBulles</strong> <code><span style="color: #000000;">/************************************************************************ <br />Liens Tooltip ==&gt; Info Bulles <br />Initialement pr&eacute;vu pour Download / fonctionnel ou vous le souhaitez <br />*************************************************************************/ <br />a.tooltip {<br />&nbsp;&nbsp; &nbsp;/*color: gray;*/<br />&nbsp;&nbsp;&nbsp; border-bottom: 1px dotted gray;<br />}<br />a.tooltip em {<br />&nbsp;&nbsp; &nbsp;display:none;<br />}<br />a.tooltip:hover {<br />&nbsp;&nbsp; &nbsp;border: 0;<br />&nbsp;&nbsp; &nbsp;position: relative;<br />&nbsp;&nbsp; &nbsp;z-index: 500;<br />&nbsp;&nbsp; &nbsp;text-decoration:none;<br />}<br />a.tooltip:hover em {<br />&nbsp;&nbsp; &nbsp;font-style: normal;<br />&nbsp;&nbsp; &nbsp;display: block;<br />&nbsp;&nbsp; &nbsp;position: absolute;<br />&nbsp;&nbsp; &nbsp;top: 20px;<br />&nbsp;&nbsp; &nbsp;left: -10px;<br />&nbsp;&nbsp; &nbsp;padding: 5px;<br />&nbsp;&nbsp; &nbsp;color: #000;<br />&nbsp;&nbsp; &nbsp;border: 1px solid #bbb;<br />&nbsp;&nbsp; &nbsp;background: #ffc;<br />&nbsp;&nbsp; &nbsp;width:170px;<br />&nbsp;&nbsp; &nbsp;text-align: left;<br />}<br />a.tooltip:hover em span {<br />&nbsp;&nbsp; &nbsp;position: absolute;<br />&nbsp;&nbsp; &nbsp;top: -7px;<br />&nbsp;&nbsp; &nbsp;left: 15px;<br />&nbsp;&nbsp; &nbsp;height: 7px;<br />&nbsp;&nbsp; &nbsp;width: 11px;<br />&nbsp;&nbsp; &nbsp;margin:0;<br />&nbsp;&nbsp; &nbsp;padding: 0;<br />&nbsp;&nbsp; &nbsp;border: 0;<br />}</span></code><br /><br /><strong>4) Ajouter la class .textbox_no_mceEditor &agrave; .textbox </strong>comme ci dessous :<br /><code><span style="color: #000000;">.textbox <span style="color: #003300;">.textbox_no_mceEditor</span> { /* Champs de formulaire */<br />&nbsp;&nbsp;&nbsp; ...<br />&nbsp; &nbsp; ...<br />}</span></code><br /><br />Normalement que ce soit avec un ancien th&egrave;me en php pur ou autre tout devrait fonctionner.</p>', 26, 'Root', 1, '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1310934998');

-- --------------------------------------------------------

-- 
-- Structure de la table `seccont_tempo`
-- 

CREATE TABLE seccont_tempo (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content longtext NOT NULL,
  counter int(11) NOT NULL default '0',
  author varchar(50) NOT NULL default '',
  ordre int(2) NOT NULL default '0',
  userlevel varchar(34) NOT NULL default '0',
  crit1 text,
  crit2 text,
  crit3 text,
  crit4 text,
  crit5 text,
  crit6 text,
  crit7 text,
  crit8 text,
  crit9 text,
  crit10 text,
  crit11 text,
  crit12 text,
  crit13 text,
  crit14 text,
  crit15 text,
  crit16 text,
  crit17 text,
  crit18 text,
  crit19 text,
  crit20 text,
  PRIMARY KEY  (artid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `seccont_tempo`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sections`
-- 

CREATE TABLE sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(255) NOT NULL default '',
  image varchar(255) NOT NULL default '',
  userlevel varchar(34) NOT NULL default '0',
  rubid int(5) NOT NULL default '3',
  intro text,
  ordre int(2) NOT NULL default '0',
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (secid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `sections`
-- 

INSERT INTO sections VALUES (1, 'Pages statiques', '', '0', 1, NULL, 0, 0);
INSERT INTO sections VALUES (2, 'En instance', '', '0', 2, NULL, 0, 0);
INSERT INTO sections VALUES (3, 'Modifications des th&egrave;mes', '', '', 3, '', 1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `session`
-- 

CREATE TABLE session (
  username varchar(25) NOT NULL default '',
  time varchar(14) NOT NULL default '',
  host_addr varchar(20) NOT NULL default '',
  guest int(1) NOT NULL default '0',
  uri varchar(255) NOT NULL default '',
  agent varchar(255) default NULL,
  KEY username (username),
  KEY time (time),
  KEY guest (guest)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `session`
-- 

INSERT INTO session VALUES ('user', '1384102103', '127.0.0.1', 0, '/index.php', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0');

-- --------------------------------------------------------

-- 
-- Structure de la table `sform`
-- 

CREATE TABLE sform (
  cpt int(11) NOT NULL auto_increment,
  id_form text NOT NULL,
  id_key text NOT NULL,
  key_value varchar(255) NOT NULL default '',
  passwd text,
  content longtext NOT NULL,
  PRIMARY KEY  (cpt)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `sform`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `stories`
-- 

CREATE TABLE stories (
  sid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(255) default NULL,
  time datetime default NULL,
  hometext mediumtext,
  bodytext mediumtext,
  comments int(11) default '0',
  counter mediumint(8) unsigned default NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  archive tinyint(1) unsigned NOT NULL default '0',
  date_finval datetime default NULL,
  auto_epur tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (sid),
  KEY catid (catid),
  KEY topic (topic),
  KEY informant (informant),
  KEY aid (aid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `stories`
-- 

INSERT INTO stories VALUES (1, 0, 'Root', 'Comment modifier et / ou supprimer EDITO', '2012-09-15 05:01:52', '<font face="arial"><b>L''EDITO </b>est l<b>a premi&egrave;re chose que les visiteurs visualiseront</b> en arrivant sur votre nouveau <b>site NPDS</b>.<br />\r\n<br />Vous pouvez l''<b>&eacute;diter</b> pour le personnaliser, ainsi que choisir de l''afficher ou non. <br />\r\nPour toute modification, l''<b>&eacute;diteur int&eacute;gr&eacute; &agrave; NPDS</b> vous simplifiera &eacute;norm&eacute;ment la t&acirc;che !<br />\r\n<br />\r\nEnfin, vous pouvez d&eacute;cider dans les <i>pr&eacute;f&eacute;rences administrateur</i>\r\nde la page que vous souhaitez utiliser <b>comme index de votre site</b>:\r\nce n''est donc pas forc&eacute;ment l''EDITO, et votre imagination laissera\r\nentrevoir bien d''autres possibilit&eacute;s !<br />\r\n</font>', 'Vous pouvez, par exemple:<br />\r\n<ul>\r\n  <li>faire arriver vos visiteurs sur la <b>page des forums</b></li>\r\n  <li>faire arriver vos visiteurs sur <b>une page d&eacute;crivant votre site en utilisant les rubriques</b></li>\r\n  <li>....<br />\r\n  </li>\r\n</ul>', 0, 2, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (2, 0, 'Root', 'NPDS contient un excellent &eacute;diteur HTML !', '2012-09-19 01:08:39', 'L''<span style="font-weight: bold;">&eacute;diteur HTML</span> int&eacute;gr&eacute; dans <span style="font-weight: bold;">NPDS</span> est vraiment <span style="font-style: italic;">tr&egrave;s puissant</span> ! <span style="font-weight: bold; color: rgb(0, 0, 204);">Tiny MCE</span>, c''est son nom, vous permet de taper et de mettre en forme le texte directement depuis votre navigateur.<br /><p style="text-align: justify;"><br /><span style="font-weight: bold;">L''envoi d''images</span> sur votre site est <span style="font-style: italic;">tr&egrave;s simple</span> si vous souhaitez illustrer vos textes, et vous pouvez aussi faire des <span style="font-weight: bold;">copier/coller</span> depuis nimporte quel logiciel de <span style="font-weight: bold;">traitement de texte</span> !</p>', '', 0, 4, 1, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (3, 0, 'Root', 'Vous cherchez des modules compl&eacute;mentaires', '2013-10-15 15:11:32', '<center><font color="#660000"><font size="3"><font face="arial"><b><font size="2" color="#0000ff">Vous cherchez des modules pour NPDS :</font><br /><a href="http://modules.npds.org/" target="_blank">http://modules.npds.org</a><br />\r\n<br /></b></font></font></font><div style="text-align: left;">Ce site, v&eacute;ritable <span style="font-weight: bold;">vitrine de l''activit&eacute; d&eacute;bordante de la communaut&eacute; NPDS</span>, vous pr&eacute;sente de <span style="font-weight: bold;">nombreux modules</span> ajoutant des fonctionnalit&eacute;s tr&egrave;s diverses &agrave; votre site.<br /><br />N''h&eacute;sitez pas &agrave; lui rendre visite: les <span style="font-weight: bold;">nombreux t&eacute;l&eacute;chargements</span> &agrave; disposition ainsi que des<span style="font-weight: bold;"> forums d''aide</span> ou encore <span style="font-weight: bold;">les tutoriels</span> sont l&agrave; pour vous guider dans la d&eacute;couverte de ces nouvelles possibilit&eacute;s !<br /></div>\r\n</center>', '', 0, 1, 2, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);
INSERT INTO stories VALUES (5, 0, 'Root', 'Vous cherchez des th&egrave;mes compl&eacute;mentaires', '2013-11-02 18:59:36', '<center><span style="color: #660000;"><span style="font-size: medium;"><span style="font-family: arial;"><strong><span style="color: #0000ff; font-size: small;">Vous cherchez des th&egrave;mes pour NPDS :</span><br /><a title="Tous les THEMES pour NPDS" href="http://styles.npds.org/" target="_blank">http://styles.npds.org</a><br /> <br /></strong></span></span></span>\r\n<div style="text-align: left;">Ce site avec plus de 100 th&egrave;mes disponibles vous permettra certainement de trouver la bonne personnalit&eacute; pour votre site.<br /><br />N''h&eacute;sitez pas &agrave; lui rendre visite: les <span style="font-weight: bold;">nombreux th&egrave;mes </span>&agrave; disposition ainsi que des<span style="font-weight: bold;"> forums d''aide</span> ou encore <span style="font-weight: bold;">les tutoriels</span> sont l&agrave; pour vous guider dans la d&eacute;couverte de ces nouvelles possibilit&eacute;s !</div>\r\n</center>', '', 0, 1, 3, 'user', '', 0, 0, '2112-01-01 00:00:00', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `stories_cat`
-- 

CREATE TABLE stories_cat (
  catid int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (catid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `stories_cat`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `subscribe`
-- 

CREATE TABLE subscribe (
  topicid tinyint(3) default NULL,
  forumid int(10) default NULL,
  lnlid text,
  uid int(11) NOT NULL default '0',
  KEY uid (uid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `subscribe`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `topics`
-- 

CREATE TABLE topics (
  topicid int(3) NOT NULL auto_increment,
  topicname varchar(20) default NULL,
  topicimage varchar(20) default NULL,
  topictext varchar(250) default NULL,
  counter int(11) NOT NULL default '0',
  topicadmin text,
  PRIMARY KEY  (topicid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `topics`
-- 

INSERT INTO topics VALUES (1, 'npds', 'npds.gif', 'NPDS', 0, NULL);
INSERT INTO topics VALUES (2, 'modules', 'modules.gif', 'Modules', 0, NULL);
INSERT INTO topics VALUES (3, 'styles', 'styles.gif', 'Styles', 0, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

CREATE TABLE users (
  uid int(11) NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  uname varchar(25) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  femail varchar(60) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  user_avatar varchar(100) default NULL,
  user_regdate varchar(20) NOT NULL default '',
  user_icq varchar(15) default NULL,
  user_occ varchar(100) default NULL,
  user_from varchar(100) default NULL,
  user_intrest varchar(150) default NULL,
  user_sig varchar(255) default NULL,
  user_viewemail tinyint(2) default NULL,
  user_theme int(3) default NULL,
  user_aim varchar(18) default NULL,
  user_yim varchar(50) default NULL,
  user_msnm varchar(50) default NULL,
  user_journal text NOT NULL,
  pass varchar(40) NOT NULL default '',
  storynum tinyint(4) NOT NULL default '10',
  umode varchar(10) NOT NULL default '',
  uorder tinyint(1) NOT NULL default '0',
  thold tinyint(1) NOT NULL default '0',
  noscore tinyint(1) NOT NULL default '0',
  bio tinytext NOT NULL,
  ublockon tinyint(1) NOT NULL default '0',
  ublock tinytext NOT NULL,
  theme varchar(255) NOT NULL default '',
  commentmax int(11) NOT NULL default '4096',
  counter int(11) NOT NULL default '0',
  send_email tinyint(1) unsigned NOT NULL default '0',
  is_visible tinyint(1) unsigned NOT NULL default '1',
  mns tinyint(1) unsigned NOT NULL default '0',
  user_langue varchar(20) default NULL,
  user_lastvisit varchar(14) default NULL,
  user_lnl tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (uid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO users VALUES (1, '', 'Anonyme', '', '', '', 'blank.gif', '989445600', '', '', '', '', '', 0, 0, '', '', '', '', '', 10, '', 0, 0, 0, '', 0, '', '', 4096, 0, 0, 1, 0, NULL, NULL, 1);
INSERT INTO users VALUES (2, 'user', 'user', 'user@user.land', '', 'http://www.userland.com', '014.gif', '989445600', '', '', '', '', 'User of the Land', 0, 0, '', '', '', '', 'd.q1Wcp0KUqsk', 10, '', 0, 0, 0, '', 1, '<ul><li><a href=http://www.npds.org target=_blank>NPDS.ORG</a></li></ul>', 'Mouse-IT2', 4096, 4, 0, 1, 1, 'french', '1384102103', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `users_extend`
-- 

CREATE TABLE users_extend (
  uid int(11) NOT NULL auto_increment,
  C1 varchar(255) default NULL,
  C2 varchar(255) default NULL,
  C3 varchar(255) default NULL,
  C4 varchar(255) default NULL,
  C5 varchar(255) default NULL,
  C6 varchar(255) default NULL,
  C7 varchar(255) default NULL,
  C8 varchar(255) default NULL,
  M1 mediumtext,
  M2 mediumtext,
  T1 varchar(10) default NULL,
  T2 varchar(14) default NULL,
  B1 blob,
  PRIMARY KEY  (uid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `users_extend`
-- 

INSERT INTO users_extend VALUES (2, '', '', '', '', '', '', '', '', '', '', '15/07/2005', '', 'none');

-- --------------------------------------------------------

-- 
-- Structure de la table `users_status`
-- 

CREATE TABLE users_status (
  uid int(11) NOT NULL auto_increment,
  posts int(10) default '0',
  attachsig int(2) default '0',
  rank int(10) default '0',
  level int(10) default '1',
  open tinyint(1) NOT NULL default '1',
  groupe varchar(34) default NULL,
  PRIMARY KEY  (uid)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `users_status`
-- 

INSERT INTO users_status VALUES (1, 19, 0, 0, 1, 1, '');
INSERT INTO users_status VALUES (2, 3, 0, 0, 2, 1, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `wspad`
-- 

CREATE TABLE wspad (
  ws_id int(11) NOT NULL auto_increment,
  page varchar(255) NOT NULL default '',
  content mediumtext NOT NULL,
  modtime int(15) NOT NULL,
  editedby varchar(40) NOT NULL default '',
  ranq smallint(6) NOT NULL default '1',
  member int(11) NOT NULL default '1',
  verrou varchar(60) default NULL,
  PRIMARY KEY  (ws_id),
  KEY page (page)
) ENGINE=MyISAM ;

-- 
-- Contenu de la table `wspad`
-- 

