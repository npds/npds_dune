<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2020 by Philippe Brunier   */
/*                                                                      */
/* New Links.php Module with SFROM extentions : Create Table            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
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
     cid int(11) NOT NULL auto_increment,
     title varchar(250) NOT NULL default '',
     cdescription text NOT NULL,
     PRIMARY KEY  (cid)
   )";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_editorials (
     linkid int(11) NOT NULL default '0',
     adminid varchar(60) NOT NULL default '',
     editorialtimestamp datetime NOT NULL default '1000-01-01 00:00:00',
     editorialtext text NOT NULL,
     editorialtitle varchar(100) NOT NULL default '',
     PRIMARY KEY  (linkid)
   )";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_links (
     lid int(11) NOT NULL auto_increment,
     cid int(11) NOT NULL default '0',
     sid int(11) NOT NULL default '0',
     title varchar(100) NOT NULL default '',
     url varchar(255) NOT NULL default '',
     description text NOT NULL,
     date datetime default NULL,
     name varchar(60) NOT NULL default '',
     email varchar(60) NOT NULL default '',
     hits int(11) NOT NULL default '0',
     submitter varchar(60) NOT NULL default '',
     linkratingsummary double(6,4) NOT NULL default '0.0000',
     totalvotes int(11) NOT NULL default '0',
     totalcomments int(11) NOT NULL default '0',
     topicid_card int(3) NOT NULL default '0',
     PRIMARY KEY  (lid)
   )";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_modrequest (
     requestid int(11) NOT NULL auto_increment,
     lid int(11) NOT NULL default '0',
     cid int(11) NOT NULL default '0',
     sid int(11) NOT NULL default '0',
     title varchar(100) NOT NULL default '',
     url varchar(255) NOT NULL default '',
     description text NOT NULL,
     modifysubmitter varchar(60) NOT NULL default '',
     brokenlink int(3) NOT NULL default '0',
     topicid_card int(3) NOT NULL default '0',
     PRIMARY KEY  (requestid),
     UNIQUE KEY requestid (requestid)
   )";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_newlink (
     lid int(11) NOT NULL auto_increment,
     cid int(11) NOT NULL default '0',
     sid int(11) NOT NULL default '0',
     title varchar(100) NOT NULL default '',
     url varchar(255) NOT NULL default '',
     description text NOT NULL,
     name varchar(60) NOT NULL default '',
     email varchar(60) NOT NULL default '',
     submitter varchar(60) NOT NULL default '',
     topicid_card int(3) NOT NULL default '0',
     PRIMARY KEY  (lid)
   )";
   $result = sql_query($sql_query);
   echo '.';

   $sql_query="CREATE TABLE IF NOT EXISTS ".$links_DB."links_subcategories (
     sid int(11) NOT NULL auto_increment,
     cid int(11) NOT NULL default '0',
     title varchar(250) NOT NULL default '',
     PRIMARY KEY  (sid)
   )";
   $result = sql_query($sql_query);
   echo '.<br /><br />
   .: Cr&eacute;ation des tables termin&eacute; / Tables Creation Ended :.<br /><br />
   <a href="modules.php?ModStart=links&amp;ModPath='.substr($ModPath,0,$pos).'" class="btn btn-secondary">'.translate("Retour en arrière").'</a>
   </p>';

include("footer.php");
?>