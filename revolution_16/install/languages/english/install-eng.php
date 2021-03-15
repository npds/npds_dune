<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2021 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function ins_translate($phrase) {
 switch($phrase) {
  case "Nouvelle installation": $tmp = "New installation"; break;
  case "Bienvenue": $tmp = "Welcome"; break;
  case "Langue": $tmp = "Language"; break;
  case "Licence": $tmp = "License"; break;
  case "Fichier de licence indisponible !": $tmp = "License file unavailable !"; break;
  case "Vérification des fichiers": $tmp = "Checking of the files"; break;
  case "Paramètres de connexion": $tmp = "Connection parameters"; break;
  case "Autres paramètres": $tmp = "Others parameters"; break;
  case "Base de données": $tmp = "Database"; break;
  case "Compte Admin": $tmp = "Users access"; break;
  case "Module UPload": $tmp = "Upload module"; break;
  case "Fin": $tmp = "End"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "The use of NPDS is submitted to the acceptance of the terms of the GNU GPL license "; break;
  case "J'accepte": $tmp = "I agree"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "This operation can be more or less long. Thanks to have patience."; break;
  case "Le fichier": $tmp = "The file"; break;
  case "est introuvable !": $tmp = "is missing"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Tip : use your favorite FTP client for made this changes and do 'Reload'."; break;
  case "Actualiser": $tmp = "Reload"; break;
  case "Version actuelle de PHP": $tmp = "Actual PHP version"; break;
  case "NPDS nécessite une version 5.3.0 ou supérieure !": $tmp = "PHP version 5.3.0 or greater is recommended for NPDS !"; break;
  case "Préfixe des tables sql": $tmp = "Sql table prefixes"; break;
  case "Droits d'accès du fichier ": $tmp = "Rights access of "; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "The file 'abla.log.php' is missing !"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "The file 'cache.config.php' is missing !"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "The file 'config.php' is missing !"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "The file 'filemanager.conf' is missing !"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "The file 'logs/security.log' is missing !"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "The file 'meta/meta.php' is missing !"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "The file 'static/edito.txt' is missing !"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "The file 'static/edito_membres.txt' is missing !"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "The file 'modules/upload/upload.conf.php' is missing !"; break;
  case "corrects": $tmp = "ok"; break;
  case "incorrects": $tmp = "bad"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "You must modify the rights of access (read/write) on the file "; break;
  case "Etape suivante": $tmp = "Next stage"; break;
  case " Etape suivante >> ": $tmp = " Next stage >> "; break;
  case "Vos paramètres personnels": $tmp = "Your personals parameters"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "MySQL server host name"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Username (login)"; break;
  case "Mot de passe": $tmp = "Password"; break;
  case "Nom de la base de données": $tmp = "Database name"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "To avoid the sql table names conflicts..."; break;
  case "souvent identique à l'identifiant": $tmp = "ofen same as login"; break;
  case "Type de connexion au serveur mySQL": $tmp = "MySQL server connection"; break;
  case "Non permanente": $tmp = "Not persistante"; break;
  case "Permanente": $tmp = "Persistante"; break;
  case "Cryptage des mots de passe utilisateurs": $tmp = "Crypt users passwords"; break;
  case "Cryptage des mots de passe administrateur(s)/auteur(s)": $tmp = "Crypt administrator(s)/author(s) passwords"; break;
  case "Non": $tmp = "No"; break;
  case "Oui": $tmp = "Yes"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Administrator e-mail"; break;
  case "Valider": $tmp = "Ok"; break;
  case "Modifier": $tmp = "Change"; break;
  case "Fichier de configuration": $tmp = "Configuration file"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "The config file was written successfully !"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "The config file can not be modicated. Please, verify the rights of access of the file 'config.php', and submit again."; break;
  case "Autres paramètres": $tmp = "Other parameters"; break;
  case "Adresse (URL) de votre site": $tmp = "Address (URL) of your website"; break;
  case "Nom de votre site": $tmp = "Name of your website"; break;
  case "Intitulé de votre site": $tmp = "Title of your website"; break;
  case "Slogan de votre site": $tmp = "Slogan of your website"; break;
  case "Thème graphique": $tmp = "Graphic theme of your website"; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Error : the database is missing and the script can not created it itself. You must created it manually or ask to your web hosting administrator !"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Error : could not connect to the database. Verify your parameters !"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Now, we proceed to the tables database creation "; break;
  case "sur le serveur d'hébergement": $tmp = "on the host server"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "If your database is not empty, make a backup before continue !"; break;
  case "Si la base de données": $tmp = "If the database"; break;
  case " Tables préfixées avec : ": $tmp = "Tables with this prefix: "; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "not exists this script will try to create for you."; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Now, we will proceed to the database update. Tips : save your database before continuing !"; break;
  case "Créer": $tmp = "Create"; break;
  case " Mettre à jour ": $tmp = " Make update "; break;
  case "La base de données a été créée avec succès !": $tmp = "The database was created successfully !"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The database creation failed. Verify your parameters and files and proceed again."; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "The database was updated successfully !"; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The database update failed. Verify your parameters and files and proceed again."; break;
  case "Remarque : veuillez valider les préférences dans l'interface d'administration pour achever la mise à jour.": $tmp = "Notice : you must validate the preferences in administration interface to terminate the update process.";
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs": $tmp = "Copy the content of /logs folder into /slogs folder and delete the /logs folder"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Bad NPDS version, you must have version"; break;
  case "Administrateur": $tmp = "Administrator"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "Mot de passe": $tmp = "Password"; break;
  case "Une seconde fois": $tmp = "Twice again"; break;
  case "Premier utilisateur": $tmp = "First user"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "The admin access was modified successfully !"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "The Admin access was not modified. Verify your parameters and files before proceed again."; break;
  case "Remarque": $tmp = "Notice"; break;
  case "caractères minimum": $tmp = "characters minimum"; break;
  case "Configuration du module UPload": $tmp = "UPload module configuration"; break;
  case "Taille maxi des fichiers en octets": $tmp = "Max. files size in bytes"; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Physical path of your website"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Examples: /data/www/my_website OR c:\web\my_website"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Autorize to upload in personnal directory"; break;
  case "Répertoire de votre site": $tmp = "Directory of your website"; break;
  case "Exemples :": $tmp = "Examples:"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Example default or if you do not know"; break;
  case "Exemples SI redirection": $tmp = "Example IF redirection"; break;
  case "Répertoire de téléchargement": $tmp = "Upload directory"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Temporary files directory"; break;
  case "Fichier journal de sécurité": $tmp = "Security log file"; break;
  case "URL HTTP de votre site": $tmp = "URL HTTP of your website"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Congratulations, you have now your NPDS portal."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Do not forget to remove with your favorite FTP client the directory 'install/' as well as the file 'install.php' !"; break;
  case "Quitter": $tmp = "Exit"; break;
  case "Installation rapide": $tmp = "Quick setup"; break;
  case "SI installation locale" : $tmp = "IF local install"; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "This option validates the acceptance of the GNU / GPL V3 license and removes the display of the results of some installation operations."; break;

  default: $tmp = "Need to be translated [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>