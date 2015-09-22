#
# Structure de la table `tdgal_cat`
#
CREATE TABLE tdgal_cat (
  id int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  nom varchar(150) NOT NULL default '',
  acces tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Structure de la table `tdgal_com`
#
CREATE TABLE tdgal_com (
  id int(11) NOT NULL auto_increment,
  pic_id int(11) NOT NULL default '0',
  user varchar(60) NOT NULL default '',
  comment text NOT NULL,
  comhostname varchar(60) NOT NULL default '',
  comtimestamp varchar(14) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Structure de la table `tdgal_gal`
#
CREATE TABLE tdgal_gal (
  id int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  nom varchar(150) NOT NULL default '',
  date varchar(14) default NULL,
  acces tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
#
INSERT INTO tdgal_gal VALUES (1, -1, 'Import', NULL, -127);
# --------------------------------------------------------

#
# Structure de la table `tdgal_img`
#
CREATE TABLE tdgal_img (
  id int(11) NOT NULL auto_increment,
  gal_id int(11) NOT NULL default '0',
  name varchar(40) NOT NULL default '',
  comment varchar(255) NOT NULL default '',
  view int(11) NOT NULL default '0',
  ordre int(11) NOT NULL default '0',
  noaff int(1) unsigned default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Structure de la table `tdgal_vot`
#
CREATE TABLE tdgal_vot (
  id int(11) NOT NULL auto_increment,
  pic_id int(11) NOT NULL default '0',
  user varchar(60) NOT NULL default '',
  rating tinyint(4) NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp varchar(14) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

