<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for NPDS - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (strstr($bnid,'..') || strstr($bnid,'./') || stristr($bnid, 'script') || stristr($bnid, 'cookie') || stristr($bnid, 'iframe') || stristr($bnid, 'applet') || stristr($bnid, 'object') || stristr($bnid, 'meta') )
   die();
global $NPDS_Prefix;
$result = sql_query("SELECT texte FROM ".$NPDS_Prefix."blocnotes WHERE bnid='$bnid'");
if (sql_num_rows($result) > 0) {
   list($texte)=sql_fetch_row($result);
   $texte=stripslashes($texte);
   $texte = str_replace(chr(13).chr(10),"\\n",str_replace("'","\'",$texte));
   echo '$(function(){ $("#texteBlocNote_'.$bnid.'").val(unescape("'.str_replace('"','\\"',$texte).'")); })';
}
?>