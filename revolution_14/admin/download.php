<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2011 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='DownloadAdmin';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
include("lib/file.class.php");

global $language;
$hlpfile = "manuels/$language/downloads.html";

function groupe($groupe) {
   $les_groupes=explode(",",$groupe);
   $mX=liste_group();
   $nbg=0; $str="";
   while (list($groupe_id, $groupe_name)=each($mX)) {
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) {$selectionne=1;}
         }
      }
      if ($selectionne==1) {
         $str.="<option value=\"$groupe_id\" selected=\"selected\">$groupe_name</option>";
      } else {
         $str.="<option value=\"$groupe_id\">$groupe_name</option>";
      }
      $nbg++;
   }
   if ($nbg>5) {$nbg=5;}
   return ("<select class=\"textbox_standard\" name=\"Mprivs\" size=\"$nbg\" style=\"top: -4;\">".$str."</select>");
}
function droits($member) {
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td align=\"left\" valign=\"top\" width=\"10%\" nowrap=\"nowrap\">";
   echo "<span class=\"rouge\">".adm_translate("Administrateurs")."</span> :";
   if ($member==-127) {$checked=" checked=\"checked\"";} else {$checked="";}
   echo "<input type=\"radio\" name=\"privs\" value=\"-127\"$checked /></td><td align=\"center\" valign=\"top\" width=\"10%\" nowrap=\"nowrap\">";
   echo "<span class=\"rouge\">".adm_translate("Anonymes")."</span> :";
   if ($member==-1) {$checked=" checked=\"checked\"";} else {$checked="";}
   echo "<input type=\"radio\" name=\"privs\" value=\"-1\"$checked /></td><td align=\"right\" valign=\"top\" width=\"15%\" nowrap=\"nowrap\">";
   echo "<span class=\"rouge\">".adm_translate("Membres")."</span> :";
   if ($member>0) {
      echo "<input type=\"radio\" name=\"privs\" value=\"1\" checked=\"checked\" />&nbsp;&nbsp;<b>=> ".adm_translate("Groupes")." :&nbsp;&nbsp;</b>";
      echo "</td><td align=\"left\" valign=\"top\" width=\"15%\">";
      echo groupe($member);
      echo "</td><td align=\"left\" valign=\"top\" width=\"10%\">";
      echo "<input type=\"radio\" name=\"privs\" value=\"0\" /><b>".adm_translate("Tous")."</b></td></tr></table>";
   } else {
      if ($member==0) {$checked=" checked=\"checked\"";} else {$checked="";}
      echo "<input type=\"radio\" name=\"privs\" value=\"1\" />&nbsp;&nbsp;<b>=> ".adm_translate("Groupes")." :&nbsp;&nbsp;</b>";
      echo "</td><td align=\"left\" valign=\"top\" width=\"15%\">";
      echo groupe($member);
      echo "</td><td align=\"left\" valign=\"top\" width=\"10%\">";
      echo "<input type=\"radio\" name=\"privs\" value=\"0\"$checked /><b>".adm_translate("Tous")."</b></td></tr></table>";
   }
}
function DownloadAdmin() {
    global $hlpfile;
    global $NPDS_Prefix;
   include ("header.php");
    include_once ("lib/togglediv.class.php");
    GraphicAdmin($hlpfile);
    opentable();

    echo "<form action=\"admin.php\" method=\"post\">";
    $resultX = sql_query("select distinct dcategory from ".$NPDS_Prefix."downloads order by dcategory");
    $num_row=sql_num_rows($resultX);
    $toggle = new ToggleDiv($num_row);

    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo adm_translate("Téléchargements");
    echo "</td><td class=\"ongl\">".$toggle->All()."</td>";
    echo "</td></tr></table>";

    while(list($dcategory) = sql_fetch_row($resultX)) {
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">";
       echo "<tr><td class=\"ongl\">";
       echo $toggle->Img();
       echo adm_translate("Catégorie")." : ".aff_langue(stripslashes($dcategory));
       echo $toggle->Begin();
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
          <tr>
             <td class=\"ongl\">".adm_translate("ID")."</td>
             <td class=\"ongl\">".adm_translate("Compt.")."</td>
             <td class=\"ongl\">Typ.</td>
             <td class=\"ongl\">".adm_translate("URL")."</td>
             <td class=\"ongl\">".adm_translate("Nom de fichier")."</td>
             <td class=\"ongl\" align=\"center\">".adm_translate("Ver.")."</td>
             <td class=\"ongl\" align=\"center\">".adm_translate("Taille de fichier")."</td>
             <td class=\"ongl\" align=\"center\">".adm_translate("Date")."</td>
             <td class=\"ongl\" align=\"center\">".adm_translate("Fonctions")."</td>
          </tr>";
       $result = sql_query("select did, dcounter, durl, dfilename, dfilesize, ddate, dver, perms from ".$NPDS_Prefix."downloads where dcategory='".addslashes($dcategory)."' order by did ASC");
       while(list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dver, $dperm) = sql_fetch_row($result)) {
          $rowcolor = tablos();
          echo "<tr $rowcolor>
                <td align=\"center\">$did</td>
                <td align=\"center\">$dcounter</td>";
          if ($dperm==0) {$dperm="Al";}
          if ($dperm>=1) {$dperm="Mb";}
          if ($dperm==-127) {$dperm="Ad";}
          if ($dperm==-1) {$dperm="An";}
          echo "<td align=\"left\">$dperm</td>
                <td><a href=\"$durl\" class=\"noir\">".adm_translate("Téléchargements")."</a></td>
                <td>$dfilename</td>
                <td align=\"center\">&nbsp;$dver</td>
                <td align=\"center\">";
                $Fichier = new File($durl);
                if ($dfilesize!=0) {
                   echo $Fichier->Pretty_Size($dfilesize);
                } else {
                   echo $Fichier->Affiche_Size();
                }
                echo "</td>
                <td align=\"center\">$ddate</td>
                <td align=\"center\"><a href=\"admin.php?op=DownloadEdit&amp;did=$did\" class=\"noir\">".adm_translate("Editer")."</a> |
                <a href=\"admin.php?op=DownloadDel&amp;did=$did&amp;ok=0\" class=\"rouge\">".adm_translate("Effacer")."</a></td>
               </tr>";
       }
       echo "</table>";
       echo $toggle->End();
       echo "</td></tr></table>";
    }
    echo "</form>";

    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo adm_translate("Ajouter un Téléchargement");
    echo "</td></tr></table>\n";
    echo "<form action=\"admin.php\" method=\"post\" name=\"adminForm\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
    <tr>
       <td width=\"150\">".adm_translate("Télécharger URL : ")."  (255 cars)</td>
       <td width=\"20%\"><input class=\"textbox\" type=\"text\" name=\"durl\" size=\"30\" maxlength=\"255\" />
       &nbsp;<a href=\"javascript:void(0);\" onclick=\"window.open('admin.php?op=FileManagerDisplay', 'wdir', 'width=650, height=450, menubar=no, location=no, directories=no, status=no, copyhistory=no, toolbar=no, scrollbars=yes, resizable=yes');\">";
       echo "<span class=\"rouge\">[".adm_translate("Parcourir")."]</span></a></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Compteur : ")."</td>
       <td><input class=\"textbox\" type=\"text\" name=\"dcounter\" size=\"10\" maxlength=\"30\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Nom de fichier : ")." (255 cars)</td>
       <td><input class=\"textbox\" type=\"text\" name=\"dfilename\" size=\"30\" maxlength=\"255\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Version : ")."</td>
       <td><input class=\"textbox\" type=\"text\" name=\"dver\" size=\"5\" maxlength=\"6\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Taille de fichier : ")." (bytes)</td>
       <td><input class=\"textbox\" type=\"text\" name=\"dfilesize\" size=\"30\" maxlength=\"31\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Propriétaire de la page Web : ")." (255 cars)</td>
       <td><input class=\"textbox\" type=\"text\" name=\"dweb\" size=\"30\" maxlength=\"255\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Propriétaire : ")."</td>
       <td><input class=\"textbox\" type=\"text\" name=\"duser\" size=\"30\" maxlength=\"31\" /></td>
    </tr>
    <tr>
       <td width=\"20%\">".adm_translate("Categorie : ")."</td>
       <td><input class=\"textbox_standard\" type=\"text\" name=\"dcategory\" size=\"35\" maxlength=\"250\" /> - ";
    echo "<select class=\"textbox_standard\" name=\"sdcategory\">";
    $result = sql_query("select distinct dcategory from ".$NPDS_Prefix."downloads order by dcategory");
    while (list($dcategory) = sql_fetch_row($result)) {
       $dcategory=stripslashes($dcategory);
       echo "<option $sel value=\"$dcategory\">".aff_langue($dcategory)."</option>";
    }
    echo "</select></td></tr>
    <tr><td width=\"20%\">";
    echo adm_translate("Description:")."
    </td>
    <td><textarea class=\"textbox\" name=\"xtext\" cols=\"70\" rows=\"20\" style=\"width: 100%;\"></textarea>";
    echo aff_editeur("xtext","false");
    echo "</td></tr>";
    echo "<tr><td colspan=\"2\" align=\"left\" valign=\"top\">";
    droits("");
    echo "</td></tr>
    </table><br />
    <input type=\"hidden\" name=\"op\" value=\"DownloadAdd\" />
    <input class=\"bouton_standard\" type=\"submit\" value=\"".adm_translate("Ajouter")."\" />
    </form>";

    closetable();
    include("footer.php");
}

function DownloadEdit($did) {
    global $hlpfile;
    global $NPDS_Prefix;
   include ("header.php");
    GraphicAdmin($hlpfile);
    $result = sql_query("select did, dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms from ".$NPDS_Prefix."downloads where did='$did'");
    list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $privs) = sql_fetch_row($result);
    opentable();
    echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
    echo adm_translate("Editer un Téléchargement");
    echo "</td></tr></table>\n";
    echo "<form action=\"admin.php\" method=\"post\" name=\"adminForm\">
       <input type=\"hidden\" name=\"did\" value=\"$did\" />
       <input type=\"hidden\" name=\"dcounter\" value=\"$dcounter\" />
       <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
       <tr>
          <td width=\"20%\">".adm_translate("Télécharger URL : ")." (255 cars)</td>
          <td><input class=\"textbox\" type=\"text\" name=\"durl\" size=\"30\" maxlenght=\"255\" value=\"$durl\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Nom de fichier : ")." (255 cars)</td>
          <td><input class=\"textbox\" type=\"text\" name=\"dfilename\" size=\"30\" maxlength=\"255\" value=\"$dfilename\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Version : ")."</td>
          <td><input class=\"textbox\" type=\"text\" name=\"dver\" size=\"5\" maxlength=\"6\" value=\"$dver\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Taille de fichier : ")." (bytes)</td>
          <td><input class=\"textbox\" type=\"text\" name=\"dfilesize\" size=\"30\" maxlength=\"31\" value=\"$dfilesize\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Propriétaire de la page Web : ")." (255 cars)</td>
          <td><input class=\"textbox\" type=\"text\" name=\"dweb\" size=\"30\" maxlenght=\"255\" value=\"$dweb\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Propriétaire : ")."</td>
          <td><input class=\"textbox\" type=\"text\" name=\"duser\" size=\"30\" maxlength=\"31\" value=\"$duser\" /></td>
       </tr>
       <tr>
          <td width=\"20%\">".adm_translate("Categorie : ")."</td>
          <td><input class=\"textbox_standard\" type=\"text\" name=\"dcategory\" size=\"35\" maxlength=\"250\" value=\"".stripslashes($dcategory)."\" /> - ";
               echo "<select class=\"textbox_standard\" name=\"sdcategory\" onchange=\"adminForm.dcategory.value=options[selectedIndex].value\">";
               $result = sql_query("select distinct dcategory from ".$NPDS_Prefix."downloads order by dcategory");
               while (list($Xdcategory) = sql_fetch_row($result)) {
                 if ($Xdcategory==$dcategory)
                    $sel="selected";
                 else
                    $sel="";
                 $Xdcategory=stripslashes($Xdcategory);
                  echo "<option $sel value=\"$Xdcategory\">".aff_langue($Xdcategory)."</option>";
               }
               echo "</select>
          </td>
       </tr>
       <tr><td width=\"20%\">".adm_translate("Description:")."</td>";
       $ddescription=stripslashes($ddescription);
       echo "<td><textarea class=\"textbox\" name=\"xtext\" cols=\"70\" rows=\"20\" style=\"width: 100%;\">$ddescription</textarea>";
       echo aff_editeur("xtext","false");
       echo "</td></tr>
       <tr>
          <td width=\"20%\">".adm_translate("Changer la date ? : ")."</td>
          <td>".adm_translate("Oui")." <input type=\"checkbox\" name=\"ddate\" value=\"yes\" /></td>
       </tr>";
       echo "<tr><td colspan=\"2\" align=\"center\" valign=\"top\">";
       droits($privs);
       echo "</td>
       </tr>
      </table><br />
      <input type=\"hidden\" name=\"op\" value=\"DownloadSave\" />
      <input class=\"bouton_standard\" type=\"submit\" value=\"".adm_translate("Sauver les modifications")."\" />
      </form>";
    closetable();
    include("footer.php");
}

function DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
    global $NPDS_Prefix;
   if ($privs==1) {
       if ($Mprivs>1 and $Mprivs<=127 and $Mprivs!="") {$privs=$Mprivs;}
    }
    $sdcategory=addslashes($sdcategory);
    if (!$dcategory) {
       $dcategory = $sdcategory;
    } else {
       $dcategory=addslashes($dcategory);
    }
    $description=addslashes($description);
    if ($ddate=="yes") {
       $time = date("Y-m-d");
       sql_query("update ".$NPDS_Prefix."downloads set dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', ddate='$time', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' where did='$did'");
    } else {
       sql_query("update ".$NPDS_Prefix."downloads set dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' where did='$did'");
    }
    Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadAdd($dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs) {
    global $NPDS_Prefix;
   if ($privs==1) {
       if ($Mprivs>1 and $Mprivs<=127 and $Mprivs!="") {$privs=$Mprivs;}
    }
    $sdcategory=addslashes($sdcategory);
    if (!$dcategory) {
       $dcategory = $sdcategory;
    } else {
       $dcategory=addslashes($dcategory);
    }
    $description=addslashes($description);
    $time = date("Y-m-d");
    if (($durl) and ($dfilename))
       sql_query("insert into ".$NPDS_Prefix."downloads values (NULL, '$dcounter', '$durl', '$dfilename', '$dfilesize', '$time', '$dweb', '$duser', '$dver', '$dcategory', '$description', '$privs')");
    Header("Location: admin.php?op=DownloadAdmin");
}

function DownloadDel($did, $ok=0) {
    global $NPDS_Prefix;
   if ($ok==1) {
       sql_query("delete from ".$NPDS_Prefix."downloads where did='$did'");
       Header("Location: admin.php?op=DownloadAdmin");
    } else {
       global $hlpfile;
       include("header.php");
       GraphicAdmin($hlpfile);
       opentable();
       echo "<br /><p align=\"center\">";
       echo "<span class=\"rouge\">";
       echo "<b>".adm_translate("ATTENTION :  êtes-vous sûr de vouloir supprimer ce fichier téléchargeable ?")."</b></span><br /><br />";
       echo "[ <a href=\"admin.php?op=DownloadDel&amp;did=$did&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=DownloadAdmin\" class=\"noir\">".adm_translate("Non")."</a> ]</p><br /><br />";
       closetable();
       include("footer.php");
    }
}
?>