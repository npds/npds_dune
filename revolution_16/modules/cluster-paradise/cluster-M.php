<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Cluster Paradise - Manage Data-Cluster  / Mod by Tribal-Dolphin      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function FindPartners_secur_cluster() {
   if (file_exists("modules/cluster-paradise/data-cluster-M.php")) {
      include("modules/cluster-paradise/data-cluster-M.php");
      $cpt=1;
      $part_cpt=0;
      while (each($part)) {
         if (strtoupper($part[$cpt]["OP"])=="EXPORT") {
            $Xpart[$part_cpt]["WWW"]=$part[$cpt]["WWW"];
            $Xpart[$part_cpt]["SUBSCRIBE"]=$part[$cpt]["SUBSCRIBE"];
            $Xpart[$part_cpt]["OP"]=$part[$cpt]["OP"];
            $Xpart[$part_cpt]["FROMTOPICID"]=$part[$cpt]["FROMTOPICID"];
            $Xpart[$part_cpt]["TOTOPIC"]=$part[$cpt]["TOTOPIC"];
            $Xpart[$part_cpt]["FROMCATID"]=$part[$cpt]["FROMCATID"];
            $Xpart[$part_cpt]["TOCATEG"]=$part[$cpt]["TOCATEG"];
            $Xpart[$part_cpt]["AUTHOR"]=$part[$cpt]["AUTHOR"];
            $Xpart[$part_cpt]["MEMBER"]=$part[$cpt]["MEMBER"];
            $part_cpt=$part_cpt+1;
         }
         $cpt=$cpt+1;
      }
      return ($Xpart);
   }
}

function key_secur_cluster() {
   if (file_exists("modules/cluster-paradise/data-cluster-M.php")) {
      include("modules/cluster-paradise/data-cluster-M.php");
      return (md5($part[0]["WWW"].$part[0]["KEY"]));
   }
}

function L_encrypt($txt) {
   if (file_exists("modules/cluster-paradise/data-cluster-M.php")) {
      include("modules/cluster-paradise/data-cluster-M.php");
      $key=$part[0]["KEY"];
   }
   return (encryptK($txt, $key));
}

if ($cluster_activate) {
   global $language;
   $local_key=key_secur_cluster();
   $tmp=FindPartners_secur_cluster();
   if (is_array($tmp)) {
      $cpt=0;
      while (each($tmp)) {
         if ( (empty($tmp[$cpt]["FROMTOPICID"]) && empty($tmp[$cpt]["FROMCATID"])) || ($tmp[$cpt]["FROMTOPICID"]==$topic || $tmp[$cpt]["FROMCATID"]==$catid) ) {
            echo "<script type=\"text/javascript\">\n//<![CDATA[\nvar cluster$cpt=window.open('', 'cluster$cpt', 'width=300, height=60, resizable=yes');\n//]]>\n</script>";
            $Zibid = "<html><head><title>NPDS - Cluster Paradise</title>";
            include("modules/upload/upload.conf.php");
            if ($url_upload_css) {
               $url_upload_cssX=str_replace("style.css","$language-style.css",$url_upload_css);
               if (is_readable($url_upload.$url_upload_cssX))
                  $url_upload_css=$url_upload_cssX;
               $Zibid .= "<link href=\"".$url_upload.$url_upload_css."\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />";
            }
            $Zibid.="</head><body topmargin=\"1\" leftmargin=\"1\">";
            $Zibid.="<form action=\"http://".$tmp[$cpt]["WWW"]."/modules.php\" method=\"post\">";
            $Zibid.="<input type=\"hidden\" name=\"ModPath\" value=\"cluster-paradise\" />";
            $Zibid.="<input type=\"hidden\" name=\"ModStart\" value=\"cluster-E\" />";
            $Zibid.="<input type=\"hidden\" name=\"Xop\" value=\"".$tmp[$cpt]["SUBSCRIBE"]."\" />";
            $Zibid.="<input type=\"hidden\" name=\"key\" value=\"".L_encrypt($local_key)."\" />";
            if ((strtoupper($tmp[$cpt]["SUBSCRIBE"])=="NEWS") and (strtoupper($tmp[$cpt]["OP"])=="EXPORT")) {
               if (isset($tmp[$cpt]["TOCATEG"])) {
                  $Xcatid = $tmp[$cpt]["TOCATEG"];
               } else {
                  list($Xcatid)=sql_fetch_row(sql_query("select title from ".$NPDS_Prefix."stories_cat where catid='$catid'"));
               }
               $Zibid.="<input type=\"hidden\" name=\"Xcatid\" value=\"".L_encrypt($Xcatid)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xaid\" value=\"".L_encrypt($tmp[$cpt]["AUTHOR"])."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xsubject\" value=\"".L_encrypt($subject)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xhometext\" value=\"".L_encrypt($hometext)."\" />";              
               $Zibid.="<input type=\"hidden\" name=\"Xbodytext\" value=\"".L_encrypt($bodytext)."\" />";
               if (isset($tmp[$cpt]["TOTOPIC"])) {
                  $Xtopic = $tmp[$cpt]["TOTOPIC"];
               } else {
                  list($Xtopic)=sql_fetch_row(sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$topic'"));
               }
               $Zibid.="<input type=\"hidden\" name=\"Xtopic\" value=\"".L_encrypt($Xtopic)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xauthor\" value=\"".L_encrypt($tmp[$cpt]["MEMBER"])."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xnotes\" value=\"".L_encrypt($notes)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xihome\" value=\"".L_encrypt($ihome)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xdate_debval\" value=\"".L_encrypt($date_debval)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xdate_finval\" value=\"".L_encrypt($date_finval)."\" />";
               $Zibid.="<input type=\"hidden\" name=\"Xepur\" value=\"".L_encrypt($epur)."\" />";
            }            
            $Zibid.="<input type=\"hidden\" name=\"Xurl_back\" value=\"cluster$cpt\" />";
            $Zibid.="<br /><p align=\"center\"><span class=\"noir\" style=\"font-size: 12px;\"><b>".translate("Mise à jour")." : ".$tmp[$cpt]["WWW"]."</b></span><br /><br />";
            $Zibid.="<input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Valider")."\" />&nbsp;&nbsp;";
            $Zibid.="<input type=\"button\" class=\"bouton_standard\" value=\"".translate("Annuler")."\" onclick=\"window.close()\" /><br />";

            $Zibid.="</p></form></body></html>";
            echo "<script type=\"text/javascript\">
                  //<![CDATA[
                  cluster$cpt.document.write('$Zibid');
                  //]]>
                  </script>";
            $cpt=$cpt+1;
         }
      }
   }
}
?>