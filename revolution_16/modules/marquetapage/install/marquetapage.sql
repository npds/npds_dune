#
# Structure de la table `marquetapage`
#

CREATE TABLE marquetapage (
  uid int(11) NOT NULL default '0',
  uri varchar(255) NOT NULL default '',
  topic varchar(255) NOT NULL default '',
  PRIMARY KEY  (uid,uri),
  KEY uid (uid)
) type=MyISAM;