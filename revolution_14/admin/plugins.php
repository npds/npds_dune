<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }

global $language;
//$hlpfile = "manuels/$language/plugins.html";
include ("header.php");
GraphicAdmin($hlpfile);

/*if ($op!="Extend-Admin-SubModule") {
   if (file_exists("admin/extend-modules.txt")) {
      $fp=fopen("admin/extend-modules.txt","r");
      if (filesize("admin/extend-modules.txt")>0)
         $Xcontent=fread($fp,filesize("admin/extend-modules.txt"));
      fclose($fp);
      opentable();
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr>\n";
      $segment=4;
      $tmp = explode("[/module]",$Xcontent);
      array_pop($tmp);
      foreach ($tmp as $ibid) {
         $Tnom=explode("[/nom]",$ibid);
         if ($ibid) {
            $Q = sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$aid' LIMIT 1");
            $Q = sql_fetch_assoc($Q);
            $Tlevel=explode("[/niveau]",$ibid);
            if (strpos($ibid,"[/niveau]")==0) {
               if ($Q['radminsuper']==1) {$affich=true;}
            } else {
               if ($Q['radminsuper']!=1) {
                  if (substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8)=="no-right") {
                     $affich=true;
                  } else if ($Q[substr($Tlevel[0], strpos($Tlevel[0],"[niveau]")+8)]==1) {
                     $affich=true;
                  } else {
                     $affich=false;
                  }
               } else {
                  $affich=true;
               }
            }
            if ($affich) {
               echo "<td align=\"center\" width=\"".floor(100/$segment)."%\">";
               $TModPath=explode("[/ModPath]",$ibid);
               $TModStart=explode("[/ModStart]",$ibid);
               $chemin=substr($TModPath[0], strpos($TModPath[0],"[ModPath]")+9);
               if ($chemin!="")
                  echo "<a href=\"admin.php?op=Extend-Admin-SubModule&amp;ModPath=".$chemin."&amp;ModStart=".substr($TModStart[0], strpos($TModStart[0],"[ModStart]")+10);
               else
                  echo "<a href=\"admin.php?op=Extend-Admin-SubModule&amp;ModPath=&amp;ModStart=".urlencode(substr($TModStart[0], strpos($TModStart[0],"[ModStart]")+10));

               echo "\" class=\"noir\">";
               if (file_exists("modules/$chemin/$chemin.$admf_ext")) {
                  echo "<img src=\"modules/$chemin/$chemin.$admf_ext\" border=\"0\" alt=\"$chemin\" />";
               } else if (file_exists($adminimg."optimysql.".$admf_ext)) {
                  echo "<img src=\"".$adminimg."optimysql.".$admf_ext."\" border=\"0\" alt=\"$chemin\" />";
               }
               echo "<br />";
               echo "<b>".substr($Tnom[0], strpos($Tnom[0],"[nom]")+5)."</b></a><br /><br />";
               echo $chemin."&nbsp;::&nbsp;";
               echo substr($TModStart[0], strpos($TModStart[0],"[ModStart]")+10)."</td>";
               $count++;
               if ($count==$segment) {
                  echo "</tr><tr>";
                  $count = 0;
               }
            }
         }
      }
      echo "</tr></table>";
      closetable();
   }
} else {*/
   if ($ModPath!="") {
      if (file_exists("modules/$ModPath/$ModStart.php"))
         include("modules/$ModPath/$ModStart.php");
   } else
      redirect_url(urldecode($ModStart));
/*}
include ("footer.php");
?>