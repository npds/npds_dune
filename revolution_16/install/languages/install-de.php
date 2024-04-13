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
  case "Actualiser": $tmp = "Aktualisieren"; break;
  case "Administrateur": $tmp = "Administrator"; break;
  case "Adresse (URL) de votre site": $tmp = "Addresse (URL) Ihrer Webseite"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Administratoren E-mail"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Den Upload ins eigene Verzeichnis autorisieren"; break;
  case "Autres paramètres": $tmp = "Andere Parameter"; break;
  case "Base de données": $tmp = "Datenbank"; break;
  case "Bienvenue": $tmp = "Willkommen"; break;
  case "caractères au minimum": $tmp = "Buchstaben minimal"; break;
  case "Cette mise à jour est uniquement compatible avec ces versions": $tmp = "Dieses Update ist nur mit diesen Versionen kompatibel"; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "Diese Option validiert die Akzeptanz der GNU / GPL V3-Lizenz und entfernt die Anzeige der Ergebnisse einiger Installationsvorgänge."; break;
  case "Cette version de npds définie dans votre fichier config.php est incompatible": $tmp = "Diese in Ihrer config.php-Datei definierte Version von npds ist nicht kompatibel"; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Physikalische Adresse von / Zu Ihrer Webseite"; break;
  case "Compte Admin": $tmp = "Benutzer Zugang"; break;
  case "Configuration du module UPload": $tmp = "Upload Modul Konfiguration"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Tipp : Benutzen Sie Ihr FTP Programm um die Änderungen vorzunehmen und klicken Sie dann auf 'Aktualisieren'."; break;
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs":$tmp = "Kopieren Sie den Inhalt des /logs Ordners in den /slogs Ordner und löschen Sie dann den /logs Ordner"; break;
  case "corrects": $tmp = "OK"; break;
  case "Créer": $tmp = "Erstellen"; break;
  case "Droits d'accès du fichier ": $tmp = "Dateizugriffsrechte für "; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Fehler : Die Datenbank ist nicht vorhanden und die Tabellen können nicht angelegt werden. Sie müssen diese manuell anlegen, oder fragen Sie Ihren Hoster !"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Fehler : keine Verbindung zur Datenbank. Überprüfen Sie die Einstellungen !"; break;
  case "est introuvable !": $tmp = "ist nicht auffindbar"; break;
  case "Etape suivante": $tmp = "Nächster Schritt"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Beispiel Standart oder wenn Sie nicht wissen"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Beispiel: /data/www/my_website OR c:\web\my_website"; break;
  case "Exemples :": $tmp = "Beispiel :"; break;
  case "Exemples SI redirection": $tmp = "Beispiel WENN Umleitung"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Glückwunsch, Ihre NPDS Webseite wurde erstellt."; break;
  case "Fichier de configuration": $tmp = "Konfigurationsdatei"; break;
  case "Fichier de licence indisponible !": $tmp = "Lizenz-Datei ist verfügbar!"; break;
  case "Fichier journal de sécurité": $tmp = "Sicherheits Log Datei"; break;
  case "Fin": $tmp = "Ende"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "incorrects": $tmp = "schlecht"; break;
  case "Installation automatique": $tmp = "Automatische Installation"; break;
  case "Installation rapide": $tmp = "Schnelle Einrichtung"; break;
  case "Intitulé de votre site": $tmp = "Titel Ihrer Website"; break;
  case "J'accepte": $tmp = "Ich stimme zu"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "Die Nutzung von NPDS setzt Ihre Zustimmung zur GNU GPL Lizenz voraus "; break;
  case "La base de données a été créée avec succès !": $tmp = "Die Datenbank wurde erfolgreich angelegt !"; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "Datenbankupdate erfolgreich !"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Anlegen der Datenbank nicht erfolgreich. Überprüfen Sie die Parameter und versuchen Sie es erneut."; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Datenbankupdate nicht erfolgreich. Überprüfen Sie die Einstellungen und versuchen Sie es erneut."; break;
  case "Langue": $tmp = "Sprache"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "Der Adminzugang wurde erfolgreich geändert !"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "Der Adminzugang konnte nicht geändert werden. Überprüfen Sie die Einstellungen und versuchen Sie es erneut."; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "Die Datei 'abla.log.php' fehlt !"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "Die Datei 'cache.config.php' fehlt !"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "Die Datei 'config.php' fehlt !"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "Die Datei 'filemanager.conf' fehlt !"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "Die Datei 'logs/security.log' fehlt !"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "Die Datei 'meta/meta.php' fehlt !"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "Die Datei 'modules/upload/upload.conf.php' fehlt !"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "Die Datei 'static/edito_membres.txt' fehlt !"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "Die Datei 'static/edito.txt' fehlt !"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "Die Konfigurationsdatei wurde erfolgreich geschrieben !"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "Die Konfigurationsdatei konnte nicht geändert werden. Bitte überprüfen Sie die Rechte der 'config.php' und versuchen Sie es erneut."; break;
  case "Le fichier": $tmp = "Die Datei"; break;
  case "Le mot de passe doit contenir au moins un caractère en majuscule.": $tmp = "Das Kennwort muss mindestens ein Großbuchstaben-Zeichen enthalten."; break;
  case "Le mot de passe doit contenir au moins un caractère en minuscule.": $tmp = "Das Kennwort muss mindestens ein Kleinzeichen enthalten."; break;
  case "Le mot de passe doit contenir au moins un caractère non alphanumérique.": $tmp = "Das Kennwort muss mindestens ein nicht alphanumerisches Zeichen enthalten."; break;
  case "Le mot de passe doit contenir au moins un chiffre.": $tmp = "Das Passwort muss mindestens eine Zahl enthalten."; break;
  case "Le mot de passe doit contenir": $tmp = "Das Passwort muss enthalten sein"; break;
  case "les changements de nom de classes et attributs du framework bs 5.2 ne sont corrigées que dans les fichiers ou tables de la base de données affectés par cette mise à jour. Ce qui signifie que quelques classes et attributs resteront à corriger." : $tmp = "Änderungen an Klassen- und Attributnamen im BS 5.2-Framework werden nur in den von diesem Update betroffenen Datenbankdateien oder -tabellen korrigiert. Das bedeutet, dass noch einige Klassen und Attribute korrigiert werden müssen."; break;
  case "Les deux mots de passe ne sont pas identiques.": $tmp = "Die beiden Passwörter sind nicht identisch."; break;
  case "Licence": $tmp = "Lizenz"; break;
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes une mise à jour de votre site.": $tmp = "Nachdem Sie nun gerade die NPDS-Dateien auf Ihren Internet-Hosting-Server übertragen haben, führt Sie dieses Skript durch mehrere Schritte, um in nur wenigen Minuten ein Update für Ihre Site zu erhalten."; break;
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes votre nouveau portail NPDS.": $tmp = "Nachdem Sie nun gerade die NPDS-Dateien auf Ihren Internet-Hosting-Server übertragen haben, führt Sie dieses Skript durch mehrere Schritte, um in wenigen Minuten Ihr neues NPDS-Portal zu erhalten."; break;
  case "Merci encore d'avoir choisi": $tmp = "Nochmals vielen Dank, dass Sie sich entschieden haben"; break;
  case "Mettre à jour": $tmp = " Updaten "; break;
  case "Mise à jour": $tmp = "Aktualisierte"; break;
  case "Mise à jour interrompue": $tmp = "Update unterbrochen"; break;
  case "Mise à jour terminée": $tmp = "Update abgeschlossen"; break;
  case "Modifier": $tmp = "Ändern"; break;
  case "Modification": $tmp = "Änderung"; break;
  case "Module UPload": $tmp = "Upload Modul"; break;
  case "Mot de passe": $tmp = "Passwort"; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "existiert nicht und wird für Sie angelegt."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Vergessen Sie nicht den 'install/' Ordner und die 'install.php' Datei zu löschen!"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "MySQL Server Hostnamen"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Benutzername (login)"; break;
  case "Nom de la base de données": $tmp = "Datenbank Name"; break;
  case "Nom de votre site": $tmp = "Name Ihrer Website"; break;
  case "Non permanente": $tmp = "Nicht permanent"; break;
  case "Non": $tmp = "Nein"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Jetzt werden die Datenbanktabellen angelegt "; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Nun wird ein Datenbank Update gemacht. Tipp : Sichern Sie vorher die vorhandene Datenbank !"; break;
  case "Nous allons maintenant procéder à la modification des tables de la base de données " : $tmp = "Wir werden nun mit der Änderung der Datenbanktabellen fortfahren "; break;
  case "Nouvelle installation": $tmp = "Neue Anlag"; break;
  case "NPDS nécessite une version 5.6.0 ou supérieure !": $tmp = "PHP Version 5.6.0 oder höher ist notwendig für NPDS !"; break;
  case "Oui": $tmp = "Ja"; break;
  case "Paramètres de connexion": $tmp = "Verbindungsparameter"; break;
  case "Permanente": $tmp = "Permanent"; break;
  case "Pour cet utilisateur SQL": $tmp = "Für diesen SQL-Benutzer"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "Um SQL Datenbanknamenskonflikte zu vermeiden..."; break;
  case "Préfixe des tables sql": $tmp = "Sql Tabellen Prefix"; break;
  case "Premier utilisateur": $tmp = "Erster Benutzer"; break;
  case "Quitter": $tmp = "Beenden"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "Der Vorgang kann länger dauern. Bitte haben Sie Geduld."; break;
  case "Remarque": $tmp = "Notiz"; break;
  case "Répertoire de téléchargement": $tmp = "Upload Pfad"; break;
  case "Répertoire de votre site": $tmp = "Pfad Ihrer Webseite"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Pfad für temporäre Dateien"; break;
  case "SI installation locale" : $tmp = "Wenn lokale Einrichtung"; break;
  case "Si la base de données": $tmp = "Wenn die Datenbank"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "Wenn Ihre Datenbank nicht leer ist, machen Sie ein Backup, bevor Sie fortfahren !"; break;
  case "Slogan de votre site": $tmp = "Slogan Ihrer Website"; break;
  case "souvent identique à l'identifiant": $tmp = "Oft gleich der Identifikation"; break;
  case "Suppression": $tmp = "Streichung"; break;
  case "sur le serveur d'hébergement": $tmp = "auf dem Hostserver"; break;
  case "Tables préfixées avec : ": $tmp = "Tabellen mit dem Prefix: "; break;
  case "Taille maxi des fichiers en octets": $tmp = "Max. Dateigrösse in bytes"; break;
  case "Thème graphique": $tmp = "Template Ihrer Webseite"; break;
  case "Type de connexion au serveur mySQL": $tmp = "MySQL Server Verbindung"; break;
  case "Une seconde fois": $tmp = "Nochmal"; break;
  case "URL HTTP(S) de votre site": $tmp = "URL HTTP(S) Ihrer Webseite"; break;
  case "Valider": $tmp = "OK"; break;
  case "Vérification des fichiers": $tmp = "Dateien überprüfen"; break;
  case "vers": $tmp = "nach"; break;
  case "Version actuelle de PHP": $tmp = "Aktuelle PHP Version"; break;
  case "veuillez valider les préférences et les metatags dans l'interface d'administration pour parfaire la mise à jour.": $tmp = "Bitte validieren Sie die Einstellungen und Metatags in der Administrationsoberfläche, um das Update abzuschließen."; break;
  case "Vos paramètres personnels": $tmp = "Ihre persönlichen Parameter"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Falsche NPDS Version, Sie benötigen"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "Sie müssen die Zugriffsrechte (lesen/schreiben) der Datei ändern "; break;

  default: $tmp = "Muss übersetzt werden [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>