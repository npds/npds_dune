<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
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
  case "Nouvelle installation": $tmp = "Neue Anlag"; break;
  case "Bienvenue": $tmp = "Willkommen"; break;
  case "Langue": $tmp = "Sprache"; break;
  case "Licence": $tmp = "Lizenz"; break;
  case "Fichier de licence indisponible !": $tmp = "Lizenz-Datei ist verfügbar!"; break;
  case "Vérification des fichiers": $tmp = "Dateien überprüfen"; break;
  case "Paramètres de connexion": $tmp = "Verbindungsparameter"; break;
  case "Autres paramètres": $tmp = "Andere Parameter"; break;
  case "Base de données": $tmp = "Datenbank"; break;
  case "Compte Admin": $tmp = "Benutzer Zugang"; break;
  case "Module UPload": $tmp = "Upload Modul"; break;
  case "Fin": $tmp = "Ende"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "Die Nutzung von NPDS setzt Ihre Zustimmung zur GNU GPL Lizenz voraus "; break;
  case "J'accepte": $tmp = "Ich stimme zu"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "Der Vorgang kann länger dauern. Bitte haben Sie Geduld."; break;
  case "Le fichier": $tmp = "Die Datei"; break;
  case "est introuvable !": $tmp = "ist nicht auffindbar"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Tipp : Benutzen Sie Ihr FTP Programm um die Änderungen vorzunehmen und klicken Sie dann auf 'Aktualisieren'."; break;
  case "Actualiser": $tmp = "Aktualisieren"; break;
  case "Version actuelle de PHP": $tmp = "Aktuelle PHP Version"; break;
  case "NPDS nécessite une version 5.3.0 ou supérieure !": $tmp = "PHP Version 5.3.0 oder höher ist notwendig für NPDS !"; break;
  case "Préfixe des tables sql": $tmp = "Sql Tabellen Prefix"; break;
  case "Droits d'accès du fichier ": $tmp = "Dateizugriffsrechte für "; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "Die Datei 'abla.log.php' fehlt !"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "Die Datei 'cache.config.php' fehlt !"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "Die Datei 'config.php' fehlt !"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "Die Datei 'filemanager.conf' fehlt !"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "Die Datei 'logs/security.log' fehlt !"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "Die Datei 'meta/meta.php' fehlt !"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "Die Datei 'static/edito.txt' fehlt !"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "Die Datei 'static/edito_membres.txt' fehlt !"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "Die Datei 'modules/upload/upload.conf.php' fehlt !"; break;
  case "corrects": $tmp = "OK"; break;
  case "incorrects": $tmp = "schlecht"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "Sie müssen die Zugriffsrechte (lesen/schreiben) der Datei ändern "; break;
  case "Etape suivante": $tmp = "Nächster Schritt"; break;
  case " Etape suivante >> ": $tmp = " Nächster Schritt >> "; break;
  case "Vos paramètres personnels": $tmp = "Ihre persönlichen Parameter"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "MySQL Server Hostnamen"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Benutzername (login)"; break;
  case "Mot de passe": $tmp = "Passwort"; break;
  case "Nom de la base de données": $tmp = "Datenbank Name"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "Um SQL Datenbanknamenskonflikte zu vermeiden..."; break;
  case "souvent identique à l'identifiant": $tmp = "Oft gleich der Identifikation"; break;
  case "Type de connexion au serveur mySQL": $tmp = "MySQL Server Verbindung"; break;
  case "Non permanente": $tmp = "Nicht permanent"; break;
  case "Permanente": $tmp = "Permanent"; break;
  case "Cryptage des mots de passe utilisateurs": $tmp = "Verschlüsselung von Benutzerkennwörtern"; break;
  case "Cryptage des mots de passe administrateur(s)/auteur(s)": $tmp = "Verschlüssele Administrator(en)/Autor(en) Passwort(e)"; break;
  case "Non": $tmp = "Nein"; break;
  case "Oui": $tmp = "Ja"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Administratoren E-mail"; break;
  case "Valider": $tmp = "OK"; break;
  case "Modifier": $tmp = "Ändern"; break;
  case "Fichier de configuration": $tmp = "Konfigurationsdatei"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "Die Konfigurationsdatei wurde erfolgreich geschrieben !"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "Die Konfigurationsdatei konnte nicht geändert werden. Bitte überprüfen Sie die Rechte der 'config.php' und versuchen Sie es erneut."; break;
  case "Autres paramètres": $tmp = "Andere Parameter"; break;
  case "Adresse (URL) de votre site": $tmp = "Addresse (URL) Ihrer Webseite"; break;
  case "Nom de votre site": $tmp = "Name Ihrer Website"; break;
  case "Intitulé de votre site": $tmp = "Titel Ihrer Website"; break;
  case "Slogan de votre site": $tmp = "Slogan Ihrer Website"; break;
  case "Thème graphique": $tmp = "Template Ihrer Webseite"; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Fehler : Die Datenbank ist nicht vorhanden und die Tabellen können nicht angelegt werden. Sie müssen diese manuell anlegen, oder fragen Sie Ihren Hoster !"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Fehler : keine Verbindung zur Datenbank. Überprüfen Sie die Einstellungen !"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Jetzt werden die Datenbanktabellen angelegt "; break;
  case "sur le serveur d'hébergement": $tmp = "auf dem Hostserver"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "Wenn Ihre Datenbank nicht leer ist, machen Sie ein Backup, bevor Sie fortfahren !"; break;
  case "Si la base de données": $tmp = "Wenn die Datenbank"; break;
  case " Tables préfixées avec : ": $tmp = "Tabellen mit dem Prefix: "; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "existiert nicht und wird für Sie angelegt."; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Nun wird ein Datenbank Update gemacht. Tipp : Sichern Sie vorher die vorhandene Datenbank !"; break;
  case "Créer": $tmp = "Erstellen"; break;
  case " Mettre à jour ": $tmp = " Updaten "; break;
  case "La base de données a été créée avec succès !": $tmp = "Die Datenbank wurde erfolgreich angelegt !"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Anlegen der Datenbank nicht erfolgreich. Überprüfen Sie die Parameter und versuchen Sie es erneut."; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "Datenbankupdate erfolgreich !"; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Datenbankupdate nicht erfolgreich. Überprüfen Sie die Einstellungen und versuchen Sie es erneut."; break;
  case "Remarque : veuillez valider les préférences dans l'interface d'administration pour achever la mise à jour.": $tmp = "Notiz : Sie müssen die Administrationseinstellungen verifizieren um sie zu speichern.";
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs":$tmp = "Kopieren Sie den Inhalt des /logs Ordners in den /slogs Ordner und löschen Sie dann den /logs Ordner"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Falsche NPDS Version, Sie benötigen"; break;
  case "Administrateur": $tmp = "Administrator"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "Mot de passe": $tmp = "Passwort"; break;
  case "Une seconde fois": $tmp = "Nochmal"; break;
  case "Premier utilisateur": $tmp = "Erster Benutzer"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "Der Adminzugang wurde erfolgreich geändert !"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Der Adminzugang konnte nicht geändert werden. Überprüfen Sie die Einstellungen und versuchen Sie es erneut."; break;
  case "Remarque": $tmp = "Notiz"; break;
  case "caractères minimum": $tmp = "Buchstaben minimal"; break;
  case "Configuration du module UPload": $tmp = "Upload Modul Konfiguration"; break;
  case "Taille maxi des fichiers en octets": $tmp = "Max. Dateigrösse in bytes"; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Physikalische Adresse von / Zu Ihrer Webseite"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Beispiel: /data/www/my_website OR c:\web\my_website"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Den Upload ins eigene Verzeichnis autorisieren"; break;
  case "Répertoire de votre site": $tmp = "Pfad Ihrer Webseite"; break;
  case "Exemples :": $tmp = "Beispiel :"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Beispiel Standart oder wenn Sie nicht wissen"; break;
  case "Exemples SI redirection": $tmp = "Beispiel WENN Umleitung"; break;
  case "Répertoire de téléchargement": $tmp = "Upload Pfad"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Pfad für temporäre Dateien"; break;
  case "Fichier journal de sécurité": $tmp = "Sicherheits Log Datei"; break;
  case "URL HTTP de votre site": $tmp = "URL HTTP Ihrer Webseite"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Glückwunsch, Ihre NPDS Webseite wurde erstellt."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Vergessen Sie nicht den 'install/' Ordner und die 'install.php' Datei zu löschen!"; break;
  case "Quitter": $tmp = "Beenden"; break;
  case "Installation rapide": $tmp = "Schnelle Einrichtung"; break;
  case "SI installation locale" : $tmp = "Wenn lokale Einrichtung"; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "Diese Option validiert die Akzeptanz der GNU / GPL V3-Lizenz und entfernt die Anzeige der Ergebnisse einiger Installationsvorgänge."; break;

  default: $tmp = "Muss übersetzt werden [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>