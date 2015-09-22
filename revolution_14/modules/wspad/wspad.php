<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Collab WS-Pad 1.35 by Developpeur an dJpb                             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* pour définir le PAD courant via l'URL                                  */
/* member='' ou '1' => PAD commun a tous les membres si $pad_membre=true  */
/* member='2 . 126' => PAD du groupe (si le membre appartient au groupe)  */
/* member='-1'      => PAD des admins (si un admin est connecté)          */
/**************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}
global $title, $language, $NPDS_Prefix, $user, $admin;
// For More security

if (file_exists("modules/$ModPath/pages.php")) {
   include ("modules/$ModPath/pages.php");
}

include_once("modules/$ModPath/lang/$language.php");
include_once("modules/$ModPath/config.php");

// limite l'utilisation aux membres et admin
settype($member, 'integer');
if ($user or $admin) {
   $tab_groupe=valid_group($user);
   if (groupe_autorisation($member,$tab_groupe)) {
      $groupe=$member;
      $auteur=$cookie[1];
   } else {
      if ($pad_membre) {
         $groupe=1;
         $auteur=$cookie[1];
      } elseif ($admin) {
         $groupe=-127;
         $auteur=$aid;
      } else {
         header("location: index.php");
      }
   }
} else {
   header("location: index.php");
}

$surlignage=$couleur[hexfromchr($auteur)];

// Paramètres utilisé par le script
$ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=$ModStart";

function Liste_Page() {
   global $NPDS_Prefix, $ModPath, $ModStart, $ThisFile, $gmt, $auteur, $groupe, $couleur;

   echo "<script type=\"text/javascript\" src=\"lib/yui/build/yui/yui-min.js\"></script>";

   echo '<script type="text/javascript">
   //<![CDATA[
   function confirm_deletedoc(page, gp) {
      var xhr_object = null;
      if (window.XMLHttpRequest) // FF
         xhr_object = new XMLHttpRequest();
      else if(window.ActiveXObject) // IE
         xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
      if (confirm("'.wspad_trans("Vous allez supprimer le document").' : "+page)) {
         xhr_object.open("GET", location.href="modules.php?ModPath='.$ModPath.'&ModStart='.$ModStart.'&op=suppdoc&page="+page+"&member="+gp, false);
         //xhr_object.send(null); used only if POST method
      }
   }
   //]]>
   </script>'."\n";
   echo "<script type=\"text/javascript\">
   //<![CDATA[
   tog =function(lst,sho,hid){
     YUI().use('transition', 'node-event-delegate', function (Y) {
       Y.delegate('click', function(e) {
        var buttonID = e.currentTarget.get('id'),
        lst_id = Y.one('#'+lst);
        btn_show=Y.one('#'+sho);
        btn_hide=Y.one('#'+hid);
        if (buttonID === sho) {
           lst_id.show(true);
           btn_show.set('id',hid);
           btn_show.set('title','".wspad_trans("Replier la liste")."');
           btn_show.setContent('<img src=\"images/admin/ws/toggle_minus.gif\" style=\"vertical-align:bottom;\" alt=\"".wspad_trans("Replier la liste")."\" />');
        } else if (buttonID == hid) {
           lst_id.transition({
             duration: 0.2,
             easing: 'ease-out',
             opacity: 0
           });
           btn_hide=Y.one('#'+hid);
           lst_id.hide(true);
           btn_hide.set('id',sho);
           btn_hide.set('title','".wspad_trans("Déplier la liste")."');
           btn_hide.setContent('<img src=\"images/admin/ws/toggle_plus.gif\" style=\"vertical-align:bottom;\" alt=\"".wspad_trans("Déplier la liste")."\" />');
        }
       }, document, 'span');
     });
   }
   //]]>
   </script>";

   opentable();
   $aff='<span id="show_cre_page" title="'.wspad_trans("Déplier la liste").'"><img src="images/admin/ws/toggle_plus.gif" style="vertical-align:bottom;" alt="'.wspad_trans("Déplier la liste").'" /></span>&nbsp;&nbsp;';
   $aff.="<b>".wspad_trans("Créer un document")."</b><br />";
   $aff.="<div id=\"cre_page\" style =\"display:none; padding-left:10px;\">";
   $aff.="<table width=\"75%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" class=\"ligna\"><tr><td>";
   $aff.="<form action=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;member=$groupe\" method=\"post\" name=\"wspadformfic\">";
   $aff.=wspad_trans("Nom du document")." : <input class=\"textbox_standard\" type=\"text\" name=\"page\" size=\"30\" maxlength=\"255\" value=\"\" />";
   $aff.="&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"creer\" value=\"".wspad_trans("Créer")."\" />";
   $aff.="<br /><span style=\"font-size:10px;\">".wspad_trans("Caractères autorisés : a-z, A-Z, 0-9, -_.")."</span>";
   $aff.="<input type=\"hidden\" name=\"op\" value=\"creer\" />";
   $aff.="</form>";
   $aff.="</td></tr></table>";
   $aff.="</div>";
   //==> pliage repliage création d'une page
   $aff.="\n
   <script type=\"text/javascript\">
   //<![CDATA[
   tog('cre_page','show_cre_page','hide_cre_page');
   //]]>
   </script>\n";
   //<== pliage repliage création d'une page
   echo $aff."<br />";

   $aff='<span id="show_paddoc" title="'.wspad_trans("Déplier la liste").'"><img src="images/admin/ws/toggle_plus.gif" style="vertical-align:bottom;" alt="'.wspad_trans("Déplier la liste").'" /></span>&nbsp;&nbsp;';
   $nb_pages=sql_num_rows(sql_query("SELECT count(page) FROM ".$NPDS_Prefix."wspad where member='$groupe' GROUP BY page"));
   if ($groupe>0) {
      $gp=sql_fetch_assoc(sql_query("SELECT groupe_name FROM ".$NPDS_Prefix."groupes where groupe_id='$groupe'"));
      $aff.="<strong>".$nb_pages." ".wspad_trans("Document(s) et révision(s) disponible(s) pour le groupe")." ".aff_langue($gp['groupe_name'])." [$groupe]</strong><br />";
   } else {
      $aff.="<strong>".$nb_pages." ".wspad_trans("Document(s) et révision(s) disponible(s) pour les administrateurs")."</strong><br />";
   }

   $aff.="<div id=\"lst_paddoc\" style =\"display:none; padding-left:10px;\">";
   if ($nb_pages>0) {
      $ibid=0; $pgibid=0;
      $result=sql_query("SELECT DISTINCT page FROM ".$NPDS_Prefix."wspad where member='$groupe' ORDER BY page ASC");
      while (list($page)=sql_fetch_row($result)) {

         // Supression des verrous de mon groupe
         clearstatcache();
         $refresh=15;
         $filename="modules/$ModPath/locks/$page-vgp-$groupe.txt";
         if (file_exists($filename)) {
            if ((time()-$refresh)>filemtime($filename)) {
               sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE page='$page' and member='$groupe'");
               @unlink($filename);
               $verrou="";
            }
         }
         // Supression des verrous de mon groupe

         $pgibid=$pgibid+1;
         $aff.='<span id="show_lst_page_'.$pgibid.'" title="'.wspad_trans("Déplier la liste").'"><img src="images/admin/ws/toggle_plus.gif" style="vertical-align:bottom;" alt="'.wspad_trans("Déplier la liste").'" /></span>&nbsp;&nbsp;'.$page.'<br />';
         $aff.="<div id=\"lst_page_$pgibid\" style =\"display:none; padding-left:10px;\">";
         $result2=sql_query("SELECT modtime, editedby, ranq, verrou FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$groupe' ORDER BY ranq ASC");
         $aff.="<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td width=\"75%\">";
         $aff.="<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\" class=\"ligna\">";
         $aff.="<tr class=\"header\" style=\"font-size:10px;\"><td width=\"2%\">&nbsp;</td><td width=\"10%\">".wspad_trans("Rev.")."</td><td width=\"35%\">".wspad_trans("Auteur")."</td><td width=\"25%\">".wspad_trans("Date")."</td>";
         $act=0;
         while (list($modtime,$editedby,$ranq,$verrou)=sql_fetch_row($result2)) {
            if ($act==0) {
               if (($auteur==$verrou) or ($verrou=="")) {
                  $aff.="<td colspan=\"2\">".wspad_trans("Actions")."</td>";
                  // Supprimer un document
                  $aff.="<td align=\"middle\"><a href=\"javascript:\" onclick=\"confirm_deletedoc('$page','$groupe');\" title=\"".wspad_trans("Supprimer le document et toutes ses révisions")."\"><img src=\"modules/$ModPath/images/delete.gif\" border=\"0\" /></a></td>";
                  // renommer un document
                  $divid=uniqid(mt_rand());
                  $aff.="<td align=\"middle\"><a href=\"javascript:\" onclick=\"";
                  $aff.="if (document.getElementById('rendoc$divid').style.visibility=='hidden') document.getElementById('rendoc$divid').style.visibility='visible'; else document.getElementById('rendoc$divid').style.visibility='hidden';\" ";
                  $aff.="title=\"".wspad_trans("Renommer le document et toutes ses révisions")."\"><img src=\"modules/$ModPath/images/rename.gif\" border=\"0\" /></a></td><td>&nbsp;</td></tr>";
               } else {
                  $aff.="<td colspan=\"5\">&nbsp;</td></tr>";
               }
               $act=1;
            }
            
            if ($ranq>=100)
               $ibid="";
            elseif ($ranq<100 and $ranq>=10)
               $ibid="0";
            else
               $ibid="00";

            $aff.="<tr style=\"font-size:10px;\"><td></td><td>".$ibid.$ranq."</td><td><div style=\"float: left; margin-top: 2px; width: 10px; height: 12px; background-color: ".$couleur[hexfromchr($editedby)].";\"></div>&nbsp;$editedby</td><td>".date(translate("dateinternal"),$modtime+($gmt*3600))."</td>";
            // voir la révision du ranq x
            $PopUp=JavaPopUp("modules.php?ModPath=$ModPath&amp;ModStart=preview&amp;pad=".encrypt($page."#wspad#".$groupe."#wspad#".$ranq),"NPDS_wspad",500,400);
            $aff.="<td><a href=\"javascript:void(0);\" onclick=\"window.open($PopUp);\" title=\"".wspad_trans("Prévisualiser")."\"><img src=\"modules/$ModPath/images/preview.gif\" border=\"0\" /></a></td>";
            if (($auteur==$verrou) or ($verrou=="")) {
               // recharger la révision du ranq x
               $aff.="<td align=\"middle\"><a href=$ThisFile&amp;op=relo&amp;page=".urlencode($page)."&amp;member=$groupe&amp;ranq=$ranq title=\"".wspad_trans("Choisir")."\"><img src=\"modules/$ModPath/images/reload.gif\" border=\"0\" /></a></td>";
               // supprimer la révision du ranq x
               $aff.="<td align=\"middle\"><a href=$ThisFile&amp;op=supp&amp;page=".urlencode($page)."&amp;member=$groupe&amp;ranq=$ranq title=\"".wspad_trans("Supprimer la révision")."\"><img src=\"modules/$ModPath/images/delete.gif\" border=\"0\" /></a></td>";
               // exporter la révision du ranq x
               $PopUp=JavaPopUp("modules.php?ModPath=$ModPath&amp;ModStart=export&amp;type=doc&amp;pad=".encrypt($page."#wspad#".$groupe."#wspad#".$ranq),"NPDS_wspad",5,5);
               $aff.="<td align=\"middle\"><a href=\"javascript:void(0);\" onclick=\"window.open($PopUp);\" title=\"".wspad_trans("Exporter .doc")."\"><img src=\"modules/$ModPath/images/export.gif\" border=\"0\" /></a></td>";
               // exporter en article 
               $aff.="<td align=\"middle\"><a href=$ThisFile&amp;op=conv_new&amp;page=".urlencode($page)."&amp;member=$groupe&amp;ranq=$ranq title=\"".wspad_trans("Transformer en New")."\"><img src=\"modules/$ModPath/images/news.gif\" border=\"0\" /></a></td>";
            } else {
               $aff.="<td colspan=\"4\">".wspad_trans("Verrouillé par : ").$verrou."</td>";
            }
            $aff.="</tr>";
         }
         $aff.="</table></td>";
         $aff.="<td valign=\"top\">";
         $aff.="<div id=\"rendoc$divid\" style=\"visibility: hidden;\"><form action=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;member=$groupe\" method=\"post\" name=\"wspadformfic\">";
         $aff.="&nbsp;<input class=\"textbox_standard\" style=\"font-size:10px;\" type=\"text\" name=\"newpage\" size=\"22\" maxlength=\"255\" value=\"$page\" />";
         $aff.="&nbsp;<input class=\"bouton_standard\" style=\"font-size:10px;\" type=\"submit\" name=\"creer\" value=\"".wspad_trans("Renommer")."\" />";
         $aff.="<input type=\"hidden\" name=\"page\" value=\"$page\" />";
         $aff.="<input type=\"hidden\" name=\"op\" value=\"renomer\" />";
         $aff.="<br />&nbsp;<span style=\"font-size:10px;\">".wspad_trans("Caractères autorisés : a-z, A-Z, 0-9, -_.")."</span>";
         $aff.="</form>";
         $aff.="</div></td></tr></table><br />";
         $aff.="</div>";
         //==> pliage repliage listes des pages
        $aff.="\n
        <script type=\"text/javascript\">
        //<![CDATA[
        tog('lst_page_".$pgibid."','show_lst_page_".$pgibid."','hide_lst_page_".$pgibid."');
        //]]>
        </script>\n";
        //<== pliage repliage listes des pages
      }
      //==> pliage repliage création d'une page
      $aff.="\n
      <script type=\"text/javascript\">
      //<![CDATA[
      tog('lst_paddoc','show_paddoc','hide_paddoc');
      //]]>
      </script>\n";
      //<== pliage repliage création d'une page
   }
   echo $aff."</div><br />";
   closetable();
}

function Page($page, $ranq) {
   global $NPDS_Prefix, $ModPath, $ModStart, $gmt, $auteur, $groupe, $mess;

   $tmp= "
   <script type='text/javascript'>
   //<![CDATA[

   // timerID=10 secondes (verrou) : timerTTL=20 minutes (force la deconnexion)
   var timerID = null;
   var timerTTL = null;
   function TimerInit() {
       timerID = setTimeout('TimerAct()',10000);
       timerTTL= setTimeout('TimerDes()',1200000);
   }
   function TimerAct() {
       clearTimeout(timerID);
          ws_verrou('".$auteur."', '$page', '$groupe');
       TimerInit();
   }
   function TimerDes() {
       if (timerID != 0) {
          window.alert('".wspad_trans("note : Enregistrer votre travail")."');
       }
       clearTimeout(timerID);
       timerID = 0;
       clearTimeout(timerTTL);
       timerTTL = 0;
   }
   function ws_verrou(xuser, xpage, xgroupe) {
      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp=new XMLHttpRequest();
      } else {
         xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
      }
      var url='modules/$ModPath/ws_verrou.php?verrou_user='+xuser+'&verrou_page='+xpage+'&verrou_groupe='+xgroupe+'&random='+Math.random();
      xmlhttp.open('GET', url, true);
      xmlhttp.send(); 
      document.getElementById('verrous').src='modules/$ModPath/images/ajax_waiting.gif';
      document.getElementById('mess').innerHTML='';
   }

   document.getElementsByTagName('body')[0].setAttribute('onload','TimerInit();');
   //]]>
   </script>";

   // Analyse des verrous
   $filename="modules/$ModPath/locks/$page-vgp-$groupe.txt";
   $refresh=15;
   clearstatcache();
   if (file_exists($filename)) {
      if (filemtime($filename) > (time()-$refresh)) {
         // propriétaire de ce verrou ?
         $cont=file($filename);
         if ($cont[0]==$auteur) {
            $edition=true;
            echo $tmp;
         } else {
            $edition=false;
         }
      } else {
         // pose le verrou
         $fp=fopen($filename,"w");
         fwrite($fp,$auteur);
         fclose($fp);
         sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
         sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='$auteur' WHERE page='$page' and member='$groupe'");
         $edition=true;
         echo $tmp;
      }
   } else {
      // pose le verrou
      $fp=fopen($filename,"w");
      fwrite($fp,$auteur);
      fclose($fp);
      sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
      sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='$auteur' WHERE page='$page' and member='$groupe'");
      $edition=true;
      echo $tmp;
   }
   // Analyse des verrous

   $row=sql_fetch_assoc(sql_query("SELECT content, modtime, editedby, ranq FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$groupe' and ranq='$ranq'"));
   if (!$edition) {
      $mess=wspad_trans("Mode lecture seulement");
   }
   opentable();
   echo "<form action=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;member=$groupe\" method=\"post\" name=\"wspadformcont\">";
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   if (!is_array($row)) {
      $row['ranq']=1;
      $row['editedby']=$auteur;
      $row['modtime']=time();
   } else {
      $row['ranq']+=1;
   }
   echo wspad_trans("Document : ")."$page&nbsp;&nbsp;[ ".wspad_trans("révision")." : ".$row['ranq']." - ".$row['editedby']." / ".date(translate("dateinternal"),$row['modtime']+($gmt*3600))." ] <span id=\"mess\" style=\"color:green;font-size:.8em;\">$mess</span> <span style=\"float: right;\"><img src=\"modules/$ModPath/images/ajax_waiting.gif\" id=\"verrous\" title=\"wspad locks\" /></span>";
   echo "</td></tr></table>\n";

   global $surlignage;
   echo "<textarea class=\"textbox_standard\" cols=\"70\" rows=\"30\" name=\"content\" style=\"width: 100%;\"><div class=\"mceNonEditable\">".$row['content']."</div></textarea>";
   echo aff_editeur("content", "true");
   echo "<hr noshade=\"noshade\" class=\"ongl\" />";
   if ($edition) {
      echo "<input class=\"bouton_standard\" type=\"submit\" name=\"sauve\" value=\"".wspad_trans("Sauvegarder")."\" /> - ";
      echo "<a href=\"modules.php?ModPath=$ModPath&amp;ModStart=$ModStart&amp;member=$groupe\" class=\"noir\">".wspad_trans("Abandonner")."</a>";
      echo "<input type=\"hidden\" name=\"page\" value=\"$page\" />";
      echo "<input type=\"hidden\" name=\"op\" value=\"sauve\" />";
   }
   echo "</form>";
   closetable();
   
}

settype($op,'string');
settype($page, 'string');
// Filtre les caractères interdits dans les noms de pages
$page=preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i','_', removeHack(stripslashes(urldecode($page))));
settype($ranq, 'integer');
settype($groupe, 'integer');

switch($op) {
  case "sauve":
     $content=removeHack(stripslashes(FixQuotes($content)));
     $auteur=removeHack(stripslashes(FixQuotes($auteur)));
     $row=sql_fetch_assoc(sql_query("SELECT MAX(ranq) as ranq FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$groupe'"));
     $result = sql_query("INSERT INTO ".$NPDS_Prefix."wspad VALUES ('', '$page', '$content', '".time()."', '$auteur', '".($row['ranq']+1)."', '$groupe','')");
     sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
     $mess=wspad_trans("révision")." ".($row['ranq']+1)." ".wspad_trans("sauvegardée");    
  break;
  case "supp":
     $auteur=removeHack(stripslashes(FixQuotes($auteur)));
     $result = sql_query("DELETE FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$groupe' and ranq='$ranq'");
     sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
  break;
  case "suppdoc":
     settype($member, 'integer');
     $result = sql_query("DELETE FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$member'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
  break;
  case "renomer":
     // Filtre les caractères interdits dans les noms de pages
     $newpage=preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i','_', removeHack(stripslashes(urldecode($newpage))));
     settype($member, 'integer');
     $result = sql_query("UPDATE ".$NPDS_Prefix."wspad SET page='$newpage', verrou='' WHERE page='$page' and member='$member'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
  break;
  
  case "conv_new":
     $row = sql_fetch_assoc(sql_query("SELECT content FROM ".$NPDS_Prefix."wspad WHERE page='$page' and member='$groupe' and ranq='$ranq'"));
     $date_debval=date("Y-d-m H:i:s",time());
     $deb_year=substr($date_debval,0,4);
     $date_finval=($deb_year+99)."-01-01 00:00:00";
     $result = sql_query("insert into ".$NPDS_Prefix."queue values (NULL, $cookie[0], '$auteur', '$page', '".FixQuotes($row['content'])."', '', now(), '','$date_debval','$date_finval','0')");
  break;
}

// For IE ----------------------
header("X-UA-Compatible: IE=8");
// For IE ----------------------
include ('header.php');
// Head banner de présentation
if (file_exists("modules/$ModPath/html/head.html")) {
   $Xcontent=join("",file("modules/$ModPath/html/head.html"));
   $Xcontent=meta_lang(aff_langue($Xcontent));
   echo $Xcontent;
}

switch($op) {
  case "sauve":
     Liste_Page();
     Page($page, ($row['ranq']+1));
  break;
  case "creer":
     Liste_Page();
     Page($page, 1);
  break;
  case "relo":
     Liste_Page();
     Page($page, $ranq);
  break;

  default :
     Liste_Page();
  break;
}

// Foot banner de présentation
if (file_exists("modules/$ModPath/html/foot.html")) {
   $Xcontent=join("",file("modules/$ModPath/html/foot.html"));
   $Xcontent.="<p align=\"right\">NPDS WsPad $version by Dev&nbsp;&&nbsp;Jpb&nbsp;</p>";
   $Xcontent=meta_lang(aff_langue($Xcontent));
   echo $Xcontent;
}
include ('footer.php');
?>