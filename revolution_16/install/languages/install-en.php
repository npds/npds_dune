<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall-MAJ v.1.3                                                */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*         : v.1.3 jpb - 2024                                           */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function ins_translate($phrase) {
 switch($phrase) {
  case "Actualiser": $tmp = "Reload"; break;
  case "Administrateur": $tmp = "Administrator"; break;
  case "Adresse (URL) de votre site": $tmp = "Address (URL) of your website"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Administrator e-mail"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Autorize to upload in personnal directory"; break;
  case "Autres paramètres": $tmp = "Others parameters"; break;
  case "Base de données": $tmp = "Database"; break;
  case "Bienvenue": $tmp = "Welcome"; break;
  case "caractères au minimum": $tmp = "characters minimum"; break;
  case "Cette mise à jour est uniquement compatible avec ces versions": $tmp = "This update is only compatible with these versions"; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "This option validates the acceptance of the GNU / GPL V3 license and removes the display of the results of some installation operations."; break;
  case "Cette version de npds définie dans votre fichier config.php est incompatible": $tmp = "This version of npds defined in your config.php file is incompatible"; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Physical path of your website"; break;
  case "Compte Admin": $tmp = "Users access"; break;
  case "Configuration du module UPload": $tmp = "UPload module configuration"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Tip : use your favorite FTP client for made this changes and do 'Reload'."; break;
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs": $tmp = "Copy the content of /logs folder into /slogs folder and delete the /logs folder"; break;
  case "corrects": $tmp = "ok"; break;
  case "Créer": $tmp = "Create"; break;
  case "Droits d'accès du fichier ": $tmp = "Rights access of "; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Error : the database is missing and the script can not created it itself. You must created it manually or ask to your web hosting administrator !"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Error : could not connect to the database. Verify your parameters !"; break;
  case "est introuvable !": $tmp = "is missing"; break;
  case "Etape suivante": $tmp = "Next stage"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Example default or if you do not know"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Examples: /data/www/my_website OR c:\web\my_website"; break;
  case "Exemples :": $tmp = "Examples:"; break;
  case "Exemples SI redirection": $tmp = "Example IF redirection"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Congratulations, you have now your NPDS portal."; break;
  case "Fichier de configuration": $tmp = "Configuration file"; break;
  case "Fichier de licence indisponible !": $tmp = "License file unavailable !"; break;
  case "Fichier journal de sécurité": $tmp = "Security log file"; break;
  case "Fin": $tmp = "End"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "incorrects": $tmp = "bad"; break;
  case "Installation automatique": $tmp = "Automatic installation"; break;
  case "Installation rapide": $tmp = "Quick setup"; break;
  case "Intitulé de votre site": $tmp = "Title of your website"; break;
  case "J'accepte": $tmp = "I agree"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "The use of NPDS is submitted to the acceptance of the terms of the GNU GPL license "; break;
  case "La base de données a été créée avec succès !": $tmp = "The database was created successfully !"; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "The database was updated successfully !"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The database creation failed. Verify your parameters and files and proceed again."; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The database update failed. Verify your parameters and files and proceed again."; break;
  case "Langue": $tmp = "Language"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "The admin access was modified successfully !"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The Admin access was not modified. Verify your parameters and files before proceed again."; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "The file 'abla.log.php' is missing !"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "The file 'cache.config.php' is missing !"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "The file 'config.php' is missing !"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "The file 'filemanager.conf' is missing !"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "The file 'logs/security.log' is missing !"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "The file 'meta/meta.php' is missing !"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "The file 'modules/upload/upload.conf.php' is missing !"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "The file 'static/edito_membres.txt' is missing !"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "The file 'static/edito.txt' is missing !"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "The config file was written successfully !"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "The config file can not be modicated. Please, verify the rights of access of the file 'config.php', and submit again."; break;
  case "Le fichier": $tmp = "The file"; break;
  case "Le mot de passe doit contenir au moins un caractère en majuscule.": $tmp = "The password must contain at least one uppercase character."; break;
  case "Le mot de passe doit contenir au moins un caractère en minuscule.": $tmp = "The password must contain at least one lowercase character."; break;
  case "Le mot de passe doit contenir au moins un caractère non alphanumérique.": $tmp = "The password must contain at least one non-alphanumeric character."; break;
  case "Le mot de passe doit contenir au moins un chiffre.": $tmp = "The password must contain at least one digit."; break;
  case "Le mot de passe doit contenir": $tmp = "The password must contain"; break;
  case "les changements de nom de classes et attributs du framework bs 5.2 ne sont corrigées que dans les fichiers ou tables de la base de données affectés par cette mise à jour. Ce qui signifie que quelques classes et attributs resteront à corriger." : $tmp = "Class and attribute name changes in the bs 5.2 framework are only corrected in the database files or tables affected by this update. Which means that a few classes and attributes will still need to be corrected."; break;
  case "Les deux mots de passe ne sont pas identiques.": $tmp = "The two passwords are not identical."; break;
  case "Licence": $tmp = "License"; break;
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes une mise à jour de votre site.": $tmp = "Now that you have just transferred the NPDS files to your Internet hosting server, this script will guide you through several steps to obtain an update to your site in just a few minutes."; break; 
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes votre nouveau portail NPDS.": $tmp = "Now that you have just transferred the NPDS files to your Internet hosting server, this script will guide you through several steps to obtain your new NPDS site in just a few minutes."; break; 
  case "Merci encore d'avoir choisi": $tmp = "Thanks again for choosing"; break;
  case "Mettre à jour": $tmp = "Make update"; break;
  case "Mise à jour": $tmp = "Update"; break;
  case "Mise à jour interrompue": $tmp = "Update aborted"; break;
  case "Mise à jour terminée": $tmp = "Update completed"; break;
  case "Modification": $tmp = "Modification"; break;
  case "Modifier": $tmp = "Change"; break;
  case "Module UPload": $tmp = "Upload module"; break;
  case "Mot de passe": $tmp = "Password"; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "not exists this script will try to create for you."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Do not forget to remove with your favorite FTP client the directory 'install/' as well as the file 'install.php' !"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "MySQL server host name"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Username (login)"; break;
  case "Nom de la base de données": $tmp = "Database name"; break;
  case "Nom de votre site": $tmp = "Name of your website"; break;
  case "Non permanente": $tmp = "Not persistante"; break;
  case "Non": $tmp = "No"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Now, we proceed to the tables database creation "; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Now, we will proceed to the database update. Tips : save your database before continuing !"; break;
  case "Nous allons maintenant procéder à la modification des tables de la base de données " : $tmp = "We will now proceed to modify the database tables "; break;
  case "Nouvelle installation": $tmp = "New installation"; break;
  case "NPDS nécessite une version 5.6.0 ou supérieure !": $tmp = "PHP version 5.6.0 or greater is recommended for NPDS !"; break;
  case "Oui": $tmp = "Yes"; break;
  case "Paramètres de connexion": $tmp = "Connection parameters"; break;
  case "Permanente": $tmp = "Persistante"; break;
  case "Pour cet utilisateur SQL": $tmp = "For this SQL user"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "To avoid the sql table names conflicts..."; break;
  case "Préfixe des tables sql": $tmp = "Sql table prefixes"; break;
  case "Premier utilisateur": $tmp = "First user"; break;
  case "Quitter": $tmp = "Exit"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "This operation can be more or less long. Thanks to have patience."; break;
  case "Remarque": $tmp = "Notice"; break;
  case "Répertoire de téléchargement": $tmp = "Upload directory"; break;
  case "Répertoire de votre site": $tmp = "Directory of your website"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Temporary files directory"; break;
  case "SI installation locale" : $tmp = "IF local install"; break;
  case "Si la base de données": $tmp = "If the database"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "If your database is not empty, make a backup before continue !"; break;
  case "Slogan de votre site": $tmp = "Slogan of your website"; break;
  case "souvent identique à l'identifiant": $tmp = "ofen same as login"; break;
  case "Suppression": $tmp = "Deletion"; break;
  case "sur le serveur d'hébergement": $tmp = "on the host server"; break;
  case "Tables préfixées avec : ": $tmp = "Tables with this prefix: "; break;
  case "Taille maxi des fichiers en octets": $tmp = "Max. files size in bytes"; break;
  case "Thème graphique": $tmp = "Graphic theme of your website"; break;
  case "Type de connexion au serveur mySQL": $tmp = "MySQL server connection"; break;
  case "Une seconde fois": $tmp = "Twice again"; break;
  case "URL HTTP(S) de votre site": $tmp = "URL HTTP(S) of your website"; break;
  case "Valider": $tmp = "Ok"; break;
  case "Vérification des fichiers": $tmp = "Checking of the files"; break;
  case "vers": $tmp = "to"; break;
  case "Version actuelle de PHP": $tmp = "Actual PHP version"; break;
  case "veuillez valider les préférences et les metatags dans l'interface d'administration pour parfaire la mise à jour.": $tmp = "you must validate the preferences and metatags in administration interface to terminate the update process."; break;
  case "Vos paramètres personnels": $tmp = "Your personals parameters"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Bad NPDS version, you must have version"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "You must modify the rights of access (read/write) on the file "; break;

  default: $tmp = "Need to be translated [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>