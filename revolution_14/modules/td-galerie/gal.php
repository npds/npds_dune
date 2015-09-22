<?php
/**************************************************************************************************/
/* Module de gestion de galeries pour NPDS                                                        */
/* ===================================================                                            */
/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net                                   */
/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                                               */
/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010                            */
/* MAJ Dev - 2011                                                                                 */
/*                                                                                                */
/* This program is free software. You can redistribute it and/or modify it under the terms of     */
/* the GNU General Public License as published by the Free Software Foundation; either version 2  */
/* of the License.                                                                                */
/**************************************************************************************************/

/**************************************************************************************************/
/* Page Principale                                                                                */
/**************************************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
global $language, $NPDS_Prefix;
// For More security


if (file_exists("modules/$ModPath/admin/pages.php")) {
   include ("modules/$ModPath/admin/pages.php");
}

include_once("modules/$ModPath/gal_conf.php");
include_once("modules/$ModPath/gal_func.php");
include_once("modules/$ModPath/lang/$language.php");

// Paramètres utilisé par le script
$ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=$ModStart";
$ThisRedo = "modules.php?ModPath=$ModPath&ModStart=$ModStart";

include("header.php");
switch($op) {
  // Affichage des catégories et ses galeries
  case "cat":

     FabMenuCat($catid);

     echo "<br />";
     ListGalCat($catid);
     echo "<br />";
  break;
  // Affichage des sous-catégories et ses galeries
  case "sscat":

     FabMenuSsCat($catid, $sscid);

     echo "<br />";
     ListGalCat($sscid);
     echo "<br />";
  break;
  // Affichage d'une galerie
  case "gal":

     FabMenuGal($galid);

     echo "<br />";
     settype($page, "integer");
     if (empty($page)) { $page = 1; }

     ViewGal($galid, $page);

     echo "<br />";
  break;
  // Affichage d'une image
  case "img":
     if ($pos < 0) { $pos = GetPos($galid, $pos); }

     FabMenuImg($galid, $pos);

     echo "<br />";
     ViewImg($galid, $pos, "");
     echo "<br />";
  break;
  // Diaporama sur un album
  case "diapo":
     ViewDiapo($galid, $pos, $pid);
  break;
  // Ecard sur une image
  case "ecard":
     PrintFormEcard($galid, $pos, $pid);
  break;
  // Post d'un commentaire
  case "postcomment":
     PostComment($gal_id, $pos, $pic_id, $comm);
  break;
  // Top des commentaires
  case "topcomment":

     TopCV("comment",$nbtopcomment);

  break;
  // Top des commentaires
  case "topvote":

     TopCV("vote",$nbtopvote);

  break;
  // Vote pour une image
  case "vote":
     PostVote($gal_id, $pos, $pic_id, $value);
  break;
  case "sendcard":
     PostEcard($galid, $pos, $pid, $from_name, $from_mail, $to_name, $to_mail, $card_sujet, $card_msg);
  break;
  // Affichage d'une seule image sans sa galerie
  case "one-img":
     ViewImg($galid, $pos, "no");
  break;
  // Proposition d'images par les membres
  case "formimgs" :
     if(autorisation(1)) {
       PrintFormImgs();
    } else {
       redirect_url($nuke_url);
    }
     break;
  case "addimgs" :
     AddImgs($imggal,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5,$user_connecte);
     break;
  default :

     FabMenu();

     echo "<br />";
     if ($view_alea) {
 
        ViewAlea();

        echo "<br />";
     }
     if ($view_last) {

        ViewLastAdd();

        echo "<br />";
     }
  break;
}
include("footer.php");
?>