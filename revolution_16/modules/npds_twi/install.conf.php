<?php
/************************************************************************/
/* NMIG : NPDS Module Installer Generator                               */
/* --------------------------------------                               */
/* Version 2.0 - 2015                                                   */
/* --------------------------                                           */
/* Générateur de fichier de configuration pour Module-Install 1.1       */
/* Développé par Boris - http://www.lordi-depanneur.com                 */
/* Module-Install est un installeur inspiré du programme d'installation */
/* d'origine du module Hot-Projet développé par Hotfirenet              */
/*                                                                      */
/* NPDS : Net Portal Dynamic System                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* v.2.0 for NPDS 16 jpb 2015                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/

global $ModInstall;
#autodoc $name_module: Nom du module
$name_module = "npds_twi";

#autodoc $path_adm_module: chemin depuis $ModInstall #required si admin avec interface
$path_adm_module = 'admin/npds_twi_set';

$req_adm='';
if ($path_adm_module!='')
$req_adm="INSERT INTO fonctions (fid,fnom,fdroits1,fdroits1_descr,finterface,fetat,fretour,fretour_h,fnom_affich,ficone,furlscript,fcategorie,fcategorie_nom,fordre) VALUES ('', '".$ModInstall."', 1, '', 1, 1, '', '', '".$name_module."', '".$ModInstall."', 'href=\"admin.php?op=Extend-Admin-SubModule&ModPath=".$ModInstall."&ModStart=".$path_adm_module."\"', 6, 'Modules', 0);";

#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fchier1","contenu_fichier2"));

$list_fich = array(array("admin/extend-modules.txt"), array("".chr(13)."[module]".chr(13)."   [nom]npds_twi[/nom]".chr(13)."   [ModPath]npds_twi[/ModPath]".chr(13)."   [ModStart]admin/npds_twi_set[/ModStart]".chr(13)."[/module]".chr(13)));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
$sql = array("");
if($path_adm_module!='') $sql[]=$req_adm;

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rÈtention  actif      aide       description
#autodoc Configuration des blocs

//$blocs = array(array("[french]Twitter[/french][english]Twitter[/english][chinese]Twitter[/chinese]"), array("include#modules/npds_twi/twi_bloc.php"), array(""), array(""), array(""), array(""), array("1"), array(""), array("[french]Je tweet[/french][english]I tweet[/english][chinese]&#x6211; tweet[/chinese]"));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au dÈbut de l'install
#autodoc Si rien n'est mis, le texte par dÈfaut sera automatiquement affichÈ

$txtdeb = "<img alt=\"logo module npds_twi\" src=\"modules/npds_twi/npds_twi.png\" style=\"float:left;\"/><p style=\"height:20px;\">[french]&nbsp;<strong>Installation semi_automatique du module npds_twi</strong></p>
<br />
Ce module n&eacute;cessite l'ouverture d'un compte twitter et la cr&eacute;ation d'une application Twitter pour l'utilisation des APIs sur votre site.<br />
&nbsp;&nbsp;- Si vous avez ces &eacute;l&eacute;ments passez directement au point n&deg; 6.<br />
&nbsp;&nbsp;- Si vous n'avez <b>PAS</b> ces &eacute;l&eacute;ments, suivez la proc&eacute;dure ci-dessous.<br />
<br />
Proc&eacute;dure Twitter :<br />
&nbsp;&nbsp;&nbsp;1. Inscrivez vous <a href=\"http://twitter.com/\">ICI</a>.<br />
<br />
&nbsp;&nbsp;&nbsp;2. Cr&eacute;ez l'application :<br />
&nbsp;&nbsp;&nbsp;&nbsp;2.1 Cr&eacute;ez l'application (passerelle entre NPDS et Twitter) <a href=\"https://dev.twitter.com/apps/new\">ICI</a> :<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendant la cr&eacute;ation de l'application, pour les non-anglophones, les infos &agrave; renseigner sont les suivantes :<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Name : Nom au choix de votre application (Exemple, \"Passerelle NPDS-Twi\". Seule restriction : Ne doit pas contenir le mot \"twitter\")<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Description : Idem, saisie libre. Par exemple : \"Passerelle NPDS-Twitter\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- WebSite : l'url du site sur lequel vous installez npds_twi<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Callback URL : Facultatif. Vous pouvez mettre la m&ecirc;me url que pour WebSite.<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;2.2 Param&eacute;trage de l'application Twitter :<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Une fois l'application cr&eacute;&eacute;e, allez dans l'onglet \"Settings\" de l'application.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Dans la section \"Application type\", pour le param&egrave;tre \"Access\", cochez \"Read and write\".<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Cliquez sur \"Update this Twitter application's settings\" pour valider.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Cliquez sur l'onglet \"Details\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Cliquez sur \"Create my access token\". Si c'est d&eacute;j&agrave; le cas, cliquez sur \"Recreate my access token\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Attention! Cela va modifier les clefs \"Access token\". Si vous les avez dÈj‡ saisies prÈcÈdemment dans npds_twi, il faut les ressaisir.<br />
<br />
&nbsp;&nbsp;&nbsp;3. R&eacute;cup&eacute;rez vos clefs et jetons (requis pour le paramr&eacute;trage de votre module).<br />
<br />
&nbsp;&nbsp;&nbsp;4. Vous avez maintenant les &eacute;l&eacute;ment n&eacute;cessaires au fonctionnement de cette passerelle :<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Votre clef de consommateur (Consumer key)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Votre code secret de consommateur (Consumer secret)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Jeton d'acc&egrave;s pour Open Authentification (Access token)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Jeton d'acc&egrave;s secret pour Open Authentification (Access token secret)<br />
<br />
&nbsp;&nbsp;&nbsp;5. Continuez l'installation automatique de la passerelle.<br />
<br />
&nbsp;&nbsp;&nbsp;6. Une fois l'installation termin&eacute;e, rendez-vous dans les prÈfÈrences de NPDS, et v&eacute;rifiez <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dans les r&eacute;glages de R&eacute;seaux Sociaux que twitter est bien activ&eacute;.<br />
<br />
Notes :<br /> 
Si vous souhaitez utiliser npds_twi sur plusieurs sites NPDS, mais avec un seul compte twitter,<br />
nous vous conseillons de cr&eacute;er une application twitter par site NPDS. Cela vous permettra d'avoir &agrave;<br />
chaque fois les url WebSite et Callback correctement renseign&eacute;es.<br />
<br />[/french]
[english]&nbsp;<strong>Semiautomatic installation of the module npds_twi</strong></p>
<br />
This module requires to open a twitter account and to create a Twitter application for the use of the APIs on your site.<br />
&nbsp;&nbsp;- If you already have these elements, pass directly to the point 6.<br />
&nbsp;&nbsp;- If you have <b>not</b> these elements, follow the procedure below.<br />
<br />
Twitter procedure:<br />
&nbsp;&nbsp;&nbsp;1. Register <a href=\"http://twitter.com/\">HERE</a><br />
<br />
&nbsp;&nbsp;&nbsp;2. Create the application:<br />
&nbsp;&nbsp;&nbsp;&nbsp;2.1 Create the application (bridge between NPDS and Twitter) <a href=\"https://dev.twitter.com/apps/new\">HERE</a>:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;During the creation of the application, informations to enter are:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Name: Name of your choice for your application (example, \"NDPS-Twi bridge.\" Only one restriction: Must not contain the word \"twitter\")<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Description: Same, free entry. For example: \"bridge NPDS-Twitter\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- WebSite: the url of the site on which you install npds_twi<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Callback URL: Optional. You can put the same url as WebSite.<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;2.2 Setting the twitter application:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Once the application is created, go to the \"Settings\" tab of the application.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- In the \"Application type\" section, for the parameter \"Access\", check \"Read and write.\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Click on \"Update this Twitter application's settings\" to confirm.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Cliquez on the \"Details\" tab.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Click on \"Create my access token\". If this is already done, then click on \"Recreate my access token\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warning! This will change the keys \"Access token\". If you have already entered them earlier in npds_twi, you must have to re-enter.<br />
<br />
&nbsp;&nbsp;&nbsp;3. Grab your keys and tokens (required for setting up your module).<br />
<br />
&nbsp;&nbsp;&nbsp;4. You now have the elements necessary for the operation of the bridge:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Your Consumer key (Consumer key)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Your consumer secret (Consumer Secret)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Your token for Open Authentication (Access token)<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Your secret token for Open Authentication (Access token secret)<br />
<br />
&nbsp;&nbsp;&nbsp;5. Continue the semiautomatic installation of the bridge.<br />
<br />
&nbsp;&nbsp;&nbsp;6. Once installation is complete, go to the NPDS preferences, check in the Social Networks settings that twitter is enabled.<br />
<br />
notes:<br />
If you want to use npds_twi on several NPDS sites, but with a single twitter account, you should create a twitter application<br />
for each NPDS site. This will allow you to properly inform the callback url and WebSite url each time.<br />
<br />[/english]";

#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera ‡ la fin de l'install

$txtfin = "[french]Vous pouvez maintenant aller &#xE0; <a href= \"admin.php?op=Extend-Admin-SubModule&amp;ModPath=npds_twi&amp;ModStart=admin/npds_twi_set\"> l'administration </a> du module pour le param&#xE9;trer.[/french][english]Now you can go to the <a href= \"admin.php?op=Extend-Admin-SubModule&amp;ModPath=npds_twi&amp;ModStart=admin/npds_twi_set\">settings page</a> of this module.[/english]";

#autodoc $link: Lien sur lequel sera redirigÈ l'utilisateur ‡ la fin de l'install (si laissÈ vide, redirigÈ sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!

$end_link = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=npds_twi&amp;ModStart=admin/npds_twi_set";
?>