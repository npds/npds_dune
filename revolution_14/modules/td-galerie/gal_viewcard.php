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
/* Page de visualisation d'une e-carte                                                            */
/**************************************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
// For More security

   global $language;
   include_once("modules/$ModPath/gal_conf.php");
   include_once("modules/$ModPath/gal_func.php");
   include_once("modules/$ModPath/lang/$language.php");
   if (!isset($data)) { redirect_url("modules.php?ModPath=$ModPath&ModStart=gal"); }

   $card_data = array();
   $card_data = @unserialize(@base64_decode($data));
   list($width, $height, $type, $attr) = getimagesize($card_data['pf']);

   $message = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
   $message.= "<html dir=\"ltr\"><head>\n";
   $message.= "<title>".gal_trans("Une e-carte pour vous")."</title>\n";
   $message.= "<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\" />\n";
   $message.= "</head>\n";
   $message.= "<body>\n";
   $message.= "<br />\n";
   $message.= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">\n";
   $message.= "<tr><td bgcolor=\"#000000\">\n";
   $message.= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" bgcolor=\"#ffffff\">\n";
   $message.= "<tr><td valign=\"top\">\n";
   $message.= "<img src=\"".$card_data['pf']."\" border=\"1\" $attr /><br />\n";
   $message.= "</td><td valign=\"top\" width=\"200\" height=\"250\">\n";
   $message.= "<br />\n";
   $message.= "<b><font face=\"arial\" color=\"#000000\" size=\"4\">".$card_data['su']."</font></b>\n";
   $message.= "<br /><br /><font face=\"arial\" color=\"#000000\" size=\"2\">".$card_data['ms']."</font>\n";
   $message.= "<br /><br /><font face=\"arial\" color=\"#000000\" size=\"2\">".$card_data['sn']."</font>\n";
   $message.= "( <a href=\"mailto:".$card_data['se']."\"><font face=\"arial\" color=\"#000000\" size=\"2\">".$card_data['se']."</font></a> )\n";
   $message.= "</td></tr></table></td></tr></table>\n";
   $message.= "</body></html>\n";
   echo $message;
?>