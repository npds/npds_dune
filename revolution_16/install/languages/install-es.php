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
  case "Actualiser": $tmp = "Actualizar"; break;
  case "Administrateur": $tmp = "Administrador"; break;
  case "Adresse (URL) de votre site": $tmp = "Dirección (URL) de su sitio web"; break;
  case "Adresse e-mail de l'administrateur": $tmp = "Dirección de correo electrónico del administrador"; break;
  case "Autoriser l'upload dans le répertoire personnel": $tmp = "Autorizar para cargar en el directorio personal"; break;
  case "Autres paramètres": $tmp = "Otros parámetros"; break;
  case "Base de données": $tmp = "Base de datos"; break;
  case "Bienvenue": $tmp = "Bienvenida"; break;
  case "caractères au minimum": $tmp = "caracteres como mínimo"; break;
  case "Cette mise à jour est uniquement compatible avec ces versions": $tmp = "Esta actualización sólo es compatible con estas versiones."; break;
  case "Cette option valide l'acceptation de la licence GNU/GPL V3 et supprime l'affichage des résultats de certaines opérations d'installation." : $tmp = "Esta opción valida la aceptación de la licencia GNU / GPL V3 y elimina la visualización de los resultados de algunas operaciones de instalación."; break;
  case "Cette version de npds définie dans votre fichier config.php est incompatible": $tmp = "Esta versión de npds definida en su archivo config.php es incompatible"; break;
  case "Chemin physique absolu d'accès depuis la racine de votre site": $tmp = "Absoluta ruta física de la raíz de su sitio web"; break;
  case "Compte Admin": $tmp = "Cuenta de administrador"; break;
  case "Configuration du module UPload": $tmp = "La configuración del módulo UPload"; break;
  case "Conseil : utilisez votre client FTP favori pour effectuer ces modifications puis faites 'Actualiser'.": $tmp = "Consejo: use su cliente favorito de FTP para hacer estos cambios y luego hacer 'Actualizar'."; break;
  case "Copier le contenu de votre dossier /logs dans le dossier /slogs puis supprimer le dossier /logs": $tmp = "Copiar el contenido de su archivo / logs en el directorio / slogs elimine el directorio / logs"; break;
  case "corrects": $tmp = "correcto"; break;
  case "Créer": $tmp = "Crear"; break;
  case "Droits d'accès du fichier ": $tmp = "Permisos de archivo "; break;
  case "Erreur : la base de données est inexistante et n'a pas pu être créée. Vous devez la créer manuellement ou demander à votre hébergeur !": $tmp = "Error: la base de datos no existe y no se pudo crear. Debe crear manualmente o consulte a su proveedor de alojamiento web!"; break;
  case "Erreur : la connexion à la base de données a échoué. Vérifiez vos paramètres !": $tmp = "Error: la conexión con la base de datos falló. Verifica la configuracion!"; break;
  case "est introuvable !": $tmp = "no se encuentra!"; break;
  case "Etape suivante": $tmp = "Etapa siguiente"; break;
  case "Exemple par défaut ou SI vous ne savez pas": $tmp = "Ejemplo predeterminado o si usted no sabe"; break;
  case "Exemples : /data/www/mon_site OU c:\web\mon_site": $tmp = "Ejemplos: /data/www/my_website o c:\web\my_website"; break;
  case "Exemples :": $tmp = "Ejemplos:"; break;
  case "Exemples SI redirection": $tmp = "Ejemplos Si redirección"; break;
  case "Félicitations, vous avez à présent votre portail NPDS.": $tmp = "Felicitaciones, ahora tienen su portal web NDPS."; break;
  case "Fichier de configuration": $tmp = "El archivo de configuración"; break;
  case "Fichier de licence indisponible !": $tmp = "No archivo de licencia disponible!"; break;
  case "Fichier journal de sécurité": $tmp = "Archivo de registro de seguridad"; break;
  case "Fin": $tmp = "Fin"; break;
  case "Identifiant": $tmp = "Login"; break;
  case "incorrects": $tmp = "erróneo"; break;
  case "Installation automatique": $tmp = "Instalación automática"; break;
  case "Installation rapide": $tmp = "Instalación rápida"; break;
  case "Intitulé de votre site": $tmp = "Título de su sitio web"; break;
  case "J'accepte": $tmp = "Acepto"; break;
  case "L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ": $tmp = "El uso de NDPS está sujeto a la aceptación de los términos de la GNU GPL"; break;
  case "La base de données a été créée avec succès !": $tmp = "La base de datos ha sido creada!"; break;
  case "La base de données a été mise à jour avec succès !": $tmp = "La base de datos se ha actualizado correctamente!"; break;
  case "La base de données n'a pas pu être créée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La base de datos no se pudo crear. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "La base de données n'a pas pu être modifiée. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La base de datos no se podía cambiar. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "Langue": $tmp = "Idioma"; break;
  case "Le compte Admin a été modifié avec succès !": $tmp = "La cuenta de administrador se ha cambiado correctamente!"; break;
  case "Le compte Admin n'a pas pu être modifié. Vérifiez les paramètres ainsi que vos fichiers, puis réessayez à nouveau.": $tmp = "La cuenta de administrador no se podía cambiar. Compruebe los ajustes y archivos, y luego vuelve a intentarlo."; break;
  case "Le fichier 'abla.log.php' est introuvable !": $tmp = "El archivo 'abla.log.php' no encontrado"; break;
  case "Le fichier 'cache.config.php' est introuvable !": $tmp = "El archivo 'cache.config.php' no encontrado"; break;
  case "Le fichier 'config.php' est introuvable !": $tmp = "El archivo 'config.php' no encontrado"; break;
  case "Le fichier 'filemanager.conf' est introuvable !": $tmp = "El archivo 'filemanager.conf' no encontrado"; break;
  case "Le fichier 'logs/security.log' est introuvable !": $tmp = "El archivo 'logs/security.log' no encontrado"; break;
  case "Le fichier 'meta/meta.php' est introuvable !": $tmp = "El archivo 'meta/meta.php' no encontrado"; break;
  case "Le fichier 'modules/upload/upload.conf.php' est introuvable !": $tmp = "El archivo 'abla.log.php' no encontrado"; break;
  case "Le fichier 'static/edito_membres.txt' est introuvable !": $tmp = "El archivo 'static/edito_membres.txt' no encontrado'"; break;
  case "Le fichier 'static/edito.txt' est introuvable !": $tmp = "El archivo 'static/edito.txt' no encontrado"; break;
  case "Le fichier de configuration a été écrit avec succès !": $tmp = "El archivo de configuración se ha escrito correctamente!"; break;
  case "Le fichier de configuration n'a pas pu être modifié. Vérifiez les droits d'accès au fichier 'config.php', puis réessayez à nouveau.": $tmp = "El archivo de configuración no puede ser modificado. Compruebe los derechos de acceso al archivo 'config.php', y luego vuelve a intentarlo."; break;
  case "Le fichier": $tmp = "Archivo"; break;
  case "Le mot de passe doit contenir au moins un caractère en majuscule.": $tmp = "La contraseña debe contener al menos un carácter en mayúsculas."; break;
  case "Le mot de passe doit contenir au moins un caractère en minuscule.": $tmp = "La contraseña debe contener al menos un carácter en minúscula."; break;
  case "Le mot de passe doit contenir au moins un caractère non alphanumérique.": $tmp = "La contraseña debe contener al menos un carácter no alfanumérico."; break;
  case "Le mot de passe doit contenir au moins un chiffre.": $tmp = "La contraseña debe contener al menos un número."; break;
  case "Le mot de passe doit contenir": $tmp = "La contraseña debe contener"; break;
  case "les changements de nom de classes et attributs du framework bs 5.2 ne sont corrigées que dans les fichiers ou tables de la base de données affectés par cette mise à jour. Ce qui signifie que quelques classes et attributs resteront à corriger." : $tmp = "Los cambios de nombres de clases y atributos en bs 5.2 framework solo se corrigen en los archivos o tablas de bases de datos afectados por esta actualización. Lo que significa que aún será necesario corregir algunas clases y atributos."; break;
  case "Les deux mots de passe ne sont pas identiques.": $tmp = "Las dos contraseñas no son idénticas."; break;
  case "Licence": $tmp = "Licencia"; break;
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes une mise à jour de votre site.": $tmp = "Ahora que acaba de transferir los archivos NPDS a su servidor de alojamiento de Internet, este script lo guiará a través de varios pasos para obtener una actualización de su sitio en solo unos minutos."; break; 
  case "Maintenant que vous venez de transférer les fichiers de NPDS vers votre serveur d'hébergement Internet, ce script va vous guider en plusieurs étapes afin d'obtenir en quelques minutes votre nouveau portail NPDS.": $tmp = "Ahora que acaba de transferir los archivos NPDS a su servidor de alojamiento de Internet, este script lo guiará a través de varios pasos para obtener su nuevo portal NPDS en unos minutos."; break; 
  case "Merci encore d'avoir choisi": $tmp = "Gracias de nuevo por elegir"; break;
  case "Mettre à jour": $tmp = "Actualizar"; break;
  case "Mise à jour": $tmp = "Actualizado"; break;
  case "Mise à jour interrompue": $tmp = "Actualización interrumpida"; break;
  case "Mise à jour terminée": $tmp = "Actualización completada"; break;
  case "Modification": $tmp = "modificación"; break;
  case "Modifier": $tmp = "Cambiar"; break;
  case "Module UPload": $tmp = "Módulo UPload"; break;
  case "Mot de passe": $tmp = "Contraseña"; break;
  case "n'existait pas ce script tentera de la créer pour vous.": $tmp = "No existe esta secuencia de comandos intentará crear para usted."; break;
  case "N'oubliez pas de supprimer depuis votre client FTP le répertoire 'install/' ainsi que le fichier 'install.php' !": $tmp = "Recuerde que debe eliminar, con su cliente FTP, el directorio 'install/' y el 'install.php' archivo!"; break;
  case "Nom d'hôte du serveur mySQL": $tmp = "Nombre de host del servidor MySQL"; break;
  case "Nom d'utilisateur (identifiant)": $tmp = "Nombre de usuario (identificador)"; break;
  case "Nom de la base de données": $tmp = "Nombre de la base de datos"; break;
  case "Nom de votre site": $tmp = "Nombre de su sitio web"; break;
  case "Non permanente": $tmp = "No es permanente"; break;
  case "Non": $tmp = "No"; break;
  case "Nous allons maintenant procéder à la création des tables de la base de données ": $tmp = "Ahora vamos a proceder a la creación de tablas en la base de datos "; break;
  case "Nous allons maintenant procéder à la mise à jour de la base de données. Il est recommandé de faire une sauvegarde de celle-ci avant de poursuivre !": $tmp = "Ahora procederemos a actualizar la base de datos. Se recomienda hacer una copia de seguridad antes de continuar!"; break;
  case "Nous allons maintenant procéder à la modification des tables de la base de données " : $tmp = "Ahora procederemos a modificar las tablas de la base de datos "; break;
  case "Nouvelle installation": $tmp = "Nueva instalación"; break;
  case "NPDS nécessite une version 5.6.0 ou supérieure !": $tmp = "NDPS requiere 5.6.0 o posterior"; break;
  case "Oui": $tmp = "Si"; break;
  case "Paramètres de connexion": $tmp = "Configuración de conexión"; break;
  case "Permanente": $tmp = "Permanente"; break;
  case "Pour cet utilisateur SQL": $tmp = "Para este usuario de SQL"; break;
  case "Pour éviter les conflits de nom de table sql...": $tmp = "Para evitar los conflictos de nombres de tabla sql..."; break;
  case "Préfixe des tables sql": $tmp = "Prefijo de tablas de SQL"; break;
  case "Premier utilisateur": $tmp = "Primer usuario"; break;
  case "Quitter": $tmp = "Salida"; break;
  case "Remarque : cette opération peut être plus ou moins longue. Merci de patienter.": $tmp = "Nota: Esto puede ser más corto o más largo Por favor espere .."; break;
  case "Remarque": $tmp = "Observación"; break;
  case "Répertoire de téléchargement": $tmp = "Directorio de descarga"; break;
  case "Répertoire de votre site": $tmp = "Directorio de su sitio web"; break;
  case "Répertoire des fichiers temporaires": $tmp = "Directorio de archivos temporales"; break;
  case "SI installation locale" : $tmp = "Si las instalaciones locales"; break;
  case "Si la base de données": $tmp = "Si la base de datos"; break;
  case "Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !": $tmp = "Si su base de datos ya contiene tablas, por favor haga una copia de seguridad antes de proceder!"; break;
  case "Slogan de votre site": $tmp = "Lema su sitio"; break;
  case "souvent identique à l'identifiant": $tmp = "menudo idéntico al identificador"; break;
  case "Suppression": $tmp = "Supresión"; break;
  case "sur le serveur d'hébergement": $tmp = "en el servidor de alojamiento"; break;
  case "Tables préfixées avec : ": $tmp = "Tablas con el prefijo: "; break;
  case "Taille maxi des fichiers en octets": $tmp = "Tamaño máximo de archivos en bytes."; break;
  case "Thème graphique": $tmp = "Tema gráfico de su sitio web"; break;
  case "Type de connexion au serveur mySQL": $tmp = "Tipo de conexión al servidor MySQL"; break;
  case "Une seconde fois": $tmp = "Una segunda vez"; break;
  case "URL HTTP(S) de votre site": $tmp = "HTTP(S) URL de su sitio web"; break;
  case "Valider": $tmp = "Aceptar"; break;
  case "Vérification des fichiers": $tmp = "Comprobación de archivos"; break;
  case "vers": $tmp = "a"; break;
  case "Version actuelle de PHP": $tmp = "La versión actual de PHP"; break;
  case "veuillez valider les préférences et les metatags dans l'interface d'administration pour parfaire la mise à jour.": $tmp = "valide las preferencias y metaetiquetas en la interfaz de administración para completar la actualización."; break;
  case "Vos paramètres personnels": $tmp = "Su configuración personal"; break;
  case "Votre version de NPDS est incorrecte, version requise": $tmp = "Su versión de NDPS es incorrecta, la versión requerida"; break;
  case "Vous devez modifier les droits d'accès (lecture/écriture) du fichier ": $tmp = "Debe cambiar los derechos de acceso (lectura / escritura) el archivo "; break;

  default: $tmp = "Necesita ser traducido [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,'UTF-8'));
}
?>