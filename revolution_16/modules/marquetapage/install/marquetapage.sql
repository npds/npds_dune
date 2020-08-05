#
# Structure de la table `marquetapage`
#

CREATE TABLE marquetapage (
  uid int(11) NOT NULL default '0',
  uri varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  topic varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  PRIMARY KEY  (uid,uri(100)),
  KEY uid (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;