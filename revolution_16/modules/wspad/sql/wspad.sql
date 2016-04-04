-- 
-- Structure de la table `wspad`
-- 

CREATE TABLE `wspad` (
  `ws_id` int(11) NOT NULL auto_increment,
  `page` varchar(255) collate latin1_general_cs NOT NULL default '',
  `content` mediumtext collate latin1_general_cs NOT NULL,
  `modtime` int(15) NOT NULL,
  `editedby` varchar(40) collate latin1_general_cs NOT NULL default '',
  `ranq` smallint(6) NOT NULL default '1',
  `member` int(11) NOT NULL default '1',
  `verrou` varchar(60) collate latin1_general_cs default NULL,
  PRIMARY KEY  (`ws_id`),
  KEY `page` (`page`)
) ENGINE=MyISAM;
