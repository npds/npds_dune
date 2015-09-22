<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* Les classes CSS Copyright (c) 2005 par Modules.NPDS.org              */
/* Version 1.0 du 26/10/2005                                            */
/*                                                                      */
/************************************************************************/ 

// On vérifie si le fichier mainfile.php est déjà inclus.
// Si non, on l'inclus maintenant
if (!function_exists("Mysql_Connexion")) {include ("mainfile.php");}
// On déclare les variables globales utiles pour le fonctionnement de NPDS
global $pdst,$ModPath,$ModStart;

// Nous allons maintenant coder l'affichage de notre module
// Tout d'abord, le header affichant le haut de page et les blocs de gauche si demandé
include("header.php");
// Ouverture d'un tableau au couleur du thème
OpenTable();
echo "<h3>Les styles sur les liens</h3>";
echo "<em><strong>Lien de style NOIR :</strong></em> en g&eacute;n&eacute;ral les actions &laquo; positives &raquo; quand se ne sont pas des boutons<br />";
echo "<a href=\"#\" class=\"noir\">Lien de classe noir</a><br />";
echo "<br />";
echo "<strong><em>Lien de style ROUGE :</em></strong> en g&eacute;n&eacute;ral les actions de type : annulation, suppression, &hellip;<br />";
echo "<a href=\"#\" class=\"rouge\">Lien de classe rouge</a><br />";
echo "<br />";
echo "<strong><em>Lien de style BOX :</em></strong><br />";
echo "<a href=\"#\" class=\"box\">Lien de classe box</a><br />";
echo "<br />";
echo "<em><strong>Lien de style HEADA :</strong></em> administration, t&eacute;l&eacute;chargement, annuaire notamment<br />";
echo "<a href=\"#\" class=\"heada\">Lien de classe heada</a><br />";
echo "<br />";
echo "<strong><em>Lien de style ONGL :</em></strong> fortement utilis&eacute; par NPDS<br />";
echo "<a href=\"#\" class=\"ongl\">Lien de classe ongl</a><br />";
// Fermeture du tableau au couleur du thème
CloseTable();
echo "<br />";
// Ouverture d'un tableau au couleur du thème
OpenTable();
echo "<h3>Les styles sur les tableaux</h3>";
echo "<strong><em>Tableaux :</em></strong> utilisation des classe HEADER pour les ent&ecirc;tes et LIGNA / LIGNB pour le contenu<br />";
echo "<table width\"100%\">";
echo "<tr class=\"header\"><td width\"100%\" align=\"center\">En-T&ecirc;te de tableau.</td></tr>";
// Pour le contenu du tableau, on peu utilisé les classes LIGNA et LIGNB directememnt dans le code :
echo "<tr class=\"ligna\"><td align=\"center\">Ligne 1</td></tr>";
echo "<tr class=\"lignb\"><td align=\"center\">Ligne 2</td></tr>";
// Ou bien utiliser la fonction tablos(); du noyeau NPDS :
$rowcolor = tablos();
echo "<tr $rowcolor><td align=\"center\">Ligne 3</td></tr>";
$rowcolor = tablos();
echo "<tr $rowcolor><td align=\"center\">Ligne 4</td></tr>";
// La fonction tablos(); est très utile lorsque l'on utilise une boucle pour créer un tableau.
echo "</table><br />";
// Fermeture du tableau au couleur du thème
CloseTable();
echo "<br />";
// Ouverture d'un tableau au couleur du thème
OpenTable();
echo "<h3>Les styles sur les champs de formulaires</h3>";
echo "<form><table width\"100%\">";
echo "<tr><td width\"50%\"><strong><em>INPUTA :</em></strong> champ de saisie, LNL, moteur de recherche notamment</td>";
echo "<td width\"50%\" align=\"center\"><input type=\"text\" class=\"inputa\" size=\"50\"></td></tr>";
echo "<tr><td width\"50%\"><strong><em>TEXTBOX :</em></strong> champ de saisie, le plus souvent utilis&eacute; pour les textarea</td>";
echo "<td align=\"center\"><textarea class=\"textbox\" wrap=\"virtual\" cols=\"50\" rows=\"10\"></TEXTAREA></td></tr>";
echo "<tr><td width\"50%\"><strong><em>TEXTBOX_STANDARD :</em></strong> champ de saisie, champ select</td>";
echo "<td align=\"center\"><input type=\"text\" class=\"textbox_standard\" size=\"50\"></td></tr>";
echo "<tr><td width\"50%\"><strong><em>BOUTON_STANDARD :</em></strong> boutons</td>";
echo "<td align=\"center\"><input type=\"button\" class=\"bouton_standard\" value=\"Bouton\"></td></tr>";
echo "</table></form><br />";
// Fermeture du tableau au couleur du thème
CloseTable();
echo "<br />";
// Ouverture d'un tableau au couleur du thème
OpenTable();
echo "<h3>Les styles de texte</h3>";
echo "<strong><em>titre</em></strong><br />";
echo "<font class=\"TITRE\">Exemple</font><br />";
echo "<br />";
echo "<strong><em>titrea</em></strong><br />";
echo "<font class=\"titrea\">Exemple</font><br />";
echo "<br />";
echo "<strong><em>TITREB</em></strong><br />";
echo "<font class=\"titreb\">Exemple</font><br />";
echo "<br />";
echo "<strong><em>TITREC</em></strong><br />";
echo "<font class=\"titrec\">Exemple</font><br />";
echo "<br />";
echo "<strong><em>TITBOXC :</em></strong> Souvent visible dans les Blocs<br />";
echo "<font class=\"titboxc\">Exemple</font><br />";
echo "<br />";
echo "<strong><em>TITBOXCONT :</em></strong> Souvent visible dans les Blocs<br />";
echo "<font class=\"titboxcont\">Exemple</font><br />";
// Fermeture du tableau au couleur du thème
CloseTable();
echo "<br />";
// Ouverture d'un tableau au couleur du thème
OpenTable();
echo "<h3>Les séparateurs</h3>";
echo "<strong><em>SEPAR :</em></strong> Visible pour les balises HR<br />";
echo "<hr width=\"75%\" align=\"center\" class=\"separ\" />";
// Fermeture du tableau au couleur du thème
CloseTable();
// Et pour finir, le footer affichant le pied de page et les blocs de droite si demandé
include("footer.php");
?>