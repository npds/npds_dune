<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2024 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions : Create Table            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists('Access_Error')) die();
if (!stristr($_SERVER['PHP_SELF'],'modules.php')) Access_Error();

global $ModPath, $ModStart, $NPDS_Prefix;
$pos = strpos($ModPath, '/admin');
global $links_DB; include_once('modules/'.substr($ModPath,0,$pos).'/links.conf.php');
if ($links_DB=='')
   $links_DB = $NPDS_Prefix;

include("header.php");
   echo '
   <p class="text-center">Cr&eacute;ation des tables en cours pour / Tables Creation running for : <b>'.$links_DB.'</b><br /><br />.';
   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_categories (
   cid int(11) NOT NULL AUTO_INCREMENT,
   title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   cdescription text COLLATE utf8mb4_unicode_ci NOT NULL,
   PRIMARY KEY (cid)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_editorials (
      linkid int(11) NOT NULL DEFAULT '0',
      adminid varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      editorialtimestamp datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
      editorialtext text COLLATE utf8mb4_unicode_ci NOT NULL,
      editorialtitle varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      PRIMARY KEY (linkid)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_links (
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
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_modrequest (
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
      PRIMARY KEY (requestid),
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_newlink (
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
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_subcategories (
      sid int(11) NOT NULL AUTO_INCREMENT,
      cid int(11) NOT NULL DEFAULT '0',
      title varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      PRIMARY KEY (sid)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
   $result = sql_query($sql_query);
   echo '.<br /><br />
   .: Cr&eacute;ation des tables termin&eacute; / Tables Creation Ended :.<br /><br />
   <a href="modules.php?ModStart=links&amp;ModPath='.substr($ModPath,0,$pos).'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>
   </p>';

include("footer.php");
?>