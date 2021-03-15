<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
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
  case "Nouvelle installation": $tmp = "Nueva instalación"; break;
  case "Bienvenue": $tmp = "Bienvenida"; break;
  case "Langue": $tmp = "Idioma"; break;
  case "Licence": $tmp = "Licencia"; break;
  case "Fichier de licence indisponible !": $tmp = "No archivo de licencia disponible!"; break;
  case "Vérification des fichiers": $tmp = "Comprobación de archivos"; break;
  case "Paramètres de connexion": $tmp = "Configuración de conexión"; break;
  case "Autres paramètres": $tmp = "Otros parámetros"; break;
  case "Base de données": $tmp = "Base de datos"; break;
  case "Compte Admin": $tmp = "Cuenta de administrador"; break;
  case "Module UPload": $tmp = "Módulo UPload"; break;
  case "Fin": $tmp = "Fin"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "El uso de NDPS está sujeto a la aceptación de los términos de la GNU GPL"; break;
  case "J'accepte": $tmp = "Acepto"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "Nota: Esto puede ser más corto o más largo Por favor espere .."; break;
  case "Le fichier": $tmp = "Archivo"; break;
  case "est introuvable !": $tmp = "no se encuentra!"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Consejo: use su cliente favorito de FTP para hacer estos cambios y luego hacer 'Actualizar'."; break;
  case "Actualiser": $tmp = "Actualizar"; break;
  case "Version actuelle de PHP": $tmp = "La versión actual de PHP"; break;
  case "NPDS nécessite une version 5.3.0 ou supérieure !": $tmp = "NDPS requiere 5.3.0 o posterior"; break;
  case "Préfixe des tables sql": $tmp = "Prefijo de tablas de SQL"; break;
  case "Droits d'accès du fichier ": $tmp = "Permisos de archivo "; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "El archivo 'abla.log.php' no encontrado"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "El archivo 'cache.config.php' no encontrado"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "El archivo 'config.php' no encontrado"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "El archivo 'filemanager.conf' no encontrado"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "El archivo 'logs/security.log' no encontrado"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "El archivo 'meta/meta.php' no encontrado"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "El archivo 'static/edito.txt' no encontrado"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "El archivo 'static/edito_membres.txt' no encontrado'"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "El archivo 'abla.log.php' no encontrado"; break;
  case "corrects": $tmp = "correcto"; break;
  case "incorrects": $tmp = "erróneo"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "Debe cambiar los derechos de acceso (lectura / escritura) el archivo "; break;
  case "Etape suivante": $tmp = "Etapa siguiente"; break;
  case " Etape suivante >> ": $tmp = " Etapa siguiente >> "; break;
  case "Vos paramètres personnels": $tmp = "Su configuración personal"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "Nombre de host del servidor MySQL"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Nombre de usuario (identificador)"; break;
  case "Mot de passe": $tmp = "Contraseña"; break;
  case "Nom de la base de données": $tmp = "Nombre de la base de datos"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "Para evitar los conflictos de nombres de tabla sql..."; break;
  case "souvent identique à l'identifiant": $tmp = "menudo idéntico al identificador"; break;
  case "Type de connexion au serveur mySQL": $tmp = "Tipo de conexión al servidor MySQL"; break;
  case "Non permanente": $tmp = "No es permanente"; break;
  case "Permanente": $tmp = "Permanente"; break;
  case "Cryptage des mots de passe utilisateurs": $tmp = "Cifrado de contraseñas de usuarios"; break;
  case "Cryptage des mots de passe administrateur(s)/auteur(s)": $tmp = "Cifrar contraseña de administrador / autor"; break;
  case "Non": $tmp = "No"; break;
  case "Oui": $tmp = "Si"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Dirección de correo electrónico del administrador"; break;
  case "Valider": $tmp = "Aceptar"; break;
  case "Modifier": $tmp = "Cambiar"; break;
  case "Fichier de configuration": $tmp = "El archivo de configuración"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "El archivo de configuración se ha escrito correctamente!"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "El archivo de configuración no puede ser modificado. Compruebe los derechos de acceso al archivo 'config.php', y luego vuelve a intentarlo."; break;
  case "Autres paramètres": $tmp = "Otros parámetros"; break;
  case "Adresse (URL) de votre site": $tmp = "Dirección (URL) de su sitio web"; break;
  case "Nom de votre site": $tmp = "Nombre de su sitio web"; break;
  case "Intitulé de votre site": $tmp = "Título de su sitio web"; break;
  case "Slogan de votre site": $tmp = "Lema su sitio"; break;
  case "Thème graphique": $tmp = "Tema gráfico de su sitio web"; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Error: la base de datos no existe y no se pudo crear. Debe crear manualmente o consulte a su proveedor de alojamiento web!"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Error: la conexión con la base de datos falló. Verifica la configuracion!"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Ahora vamos a proceder a la creación de tablas en la base de datos "; break;
  case "sur le serveur d'hébergement": $tmp = "en el servidor de alojamiento"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "Si su base de datos ya contiene tablas, por favor haga una copia de seguridad antes de proceder!"; break;
  case "Si la base de données": $tmp = "Si la base de datos"; break;
  case " Tables préfixées avec : ": $tmp = "Tablas con el prefijo:"; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "No existe esta secuencia de comandos intentará crear para usted."; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Ahora procederemos a actualizar la base de datos. Se recomienda hacer una copia de seguridad antes de continuar!"; break;
  case "Créer": $tmp = "Crear"; break;
  case " Mettre à jour ": $tmp = "Actualizar"; break;
  case "La base de données a été créée avec succès !": $tmp = "La base de datos ha sido creada!"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La base de datos no se pudo crear. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "La base de datos se ha actualizado correctamente!"; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La base de datos no se podía cambiar. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "Remarque : veuillez valider les préférences dans l'interface d'administration pour achever la mise à jour.": $tmp = "Nota: Por favor confirme las preferencias en la interfaz de administración para completar la actualización.";
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs": $tmp = "Copiar el contenido de su archivo / logs en el directorio / slogs elimine el directorio / logs"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Su versión de NDPS es incorrecta, la versión requerida"; break;
  case "Administrateur": $tmp = "Administrador"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "Mot de passe": $tmp = "Contraseña"; break;
  case "Une seconde fois": $tmp = "Una segunda vez"; break;
  case "Premier utilisateur": $tmp = "Primer usuario"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "La cuenta de administrador se ha cambiado correctamente!"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La cuenta de administrador no se podía cambiar. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "Remarque": $tmp = "Observación"; break;
  case "caractères minimum": $tmp = "Mínima carácter"; break;
  case "Configuration du module UPload": $tmp = "La configuración del módulo UPload"; break;
  case "Taille maxi des fichiers en octets": $tmp = "Tamaño máximo de archivos en bytes."; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Absoluta ruta física de la raíz de su sitio web"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Ejemplos: /data/www/my_website o c:\web\my_website"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Autorizar para cargar en el directorio personal"; break;
  case "Répertoire de votre site": $tmp = "Directorio de su sitio web"; break;
  case "Exemples :": $tmp = "Ejemplos:"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Ejemplo predeterminado o si usted no sabe"; break;
  case "Exemples SI redirection": $tmp = "Ejemplos Si redirección"; break;
  case "Répertoire de téléchargement": $tmp = "Directorio de descarga"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Directorio de archivos temporales"; break;
  case "Fichier journal de sécurité": $tmp = "Archivo de registro de seguridad"; break;
  case "URL HTTP de votre site": $tmp = "HTTP URL de su sitio web"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Felicitaciones, ahora tienen su portal web NDPS."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Recuerde que debe eliminar, con su cliente FTP, el directorio 'install/' y el 'install.php' archivo!"; break;
  case "Quitter": $tmp = "Salida"; break;
  case "Installation rapide": $tmp = "Instalación rápida"; break;
  case "SI installation locale" : $tmp = "Si las instalaciones locales"; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "Esta opción valida la aceptación de la licencia GNU / GPL V3 y elimina la visualización de los resultados de algunas operaciones de instalación."; break;

  default: $tmp = "Necesita ser traducido [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>