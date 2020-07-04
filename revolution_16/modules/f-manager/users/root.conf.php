<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   // Ne pas modifier cette ligne -------
   $racine_fma=dirname($SCRIPT_FILENAME);
   // si nécessaire, remplacer la ligne du dessus par
   // $racine_fma=dirname($_SERVER['SCRIPT_FILENAME']);
   // -----------------------------------

// ----------------
// GENERAL --------
// ----------------
   // $access_fma permet de limiter l'utilisation de F-manager
   // Tous le monde => $access_fma="";
   // anonyme       => $access_fma="anonyme";
   // membre        => $access_fma="membre";
   // groupe '2,5'  => $access_fma="2,5";
   //               => s'il existe un fichier de configuration portant le nom du groupe ALORS tous les membres du groupe partagent le même fichier
   //               => Attention - cela s'arrête au premier groupe qui rempli la condition
   // admin         => $access_fma="admin";
   $access_fma="admin";

   //$tri_fma permet de choisir le tri utilisé et son sens
   // D : Date
   // S : Size
   // N : Name (defaut)
   // ASC  : Sens ascendant
   // DESC : Sens descendant (defaut)
   //
   // => $tri_fma=
   // array (
   //       'tri'  => 'S',
   //       'sens' => 'DESC',
   // );

   $tri_fma= array (
   'tri'  => 'N',
   'sens' => 'ASC',
   );

// ----------------
// REPERTOIRES ----
// ----------------
   // Vous pouvez limiter la navigation à un sous-répertoire se trouvant sous la racine de votre site
   // => $basedir_fma=$racine_fma."/static";
   $basedir_fma=$racine_fma;

   // $dirlimit_fma permet de controler la navigation dans des sous-répertoires
   // CETTE LIMITE s'etend à tout le système de fichier !!
   // anonyme  => ce répertoire n'est visible que par les anonymes
   // membre   => ce répertoire n'est visible que par les membre
   // '2,5'    => ce répertoire n'est visible que par les membre du(des) groupes x,y,...
   // '-2,-5'  => ce répertoire sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
   // admin    => ce répertoire n'est visible que par les administrateurs
   //
   // => $dirlimit_fma=
   // array (
   //       'ftp' => 'anonyme',
   //       'static' => 'membre',
   //       'documentations de développements' => '2,5',
   //       'admin' => 'admin'
   // );

   // $dirsize_fma permet d'afficher la taille des répertoires
   // ATTENTION cette fonction peut-être consommatrice de CPU si vos répertoires contiennent de nombreux fichiers
   $dirsize_fma=false;

   // $dirpres_fma permet de controler les informations affichées relatives aux répertoires (0 non affiché / 1 affiché)
   // position 1 = icone
   // position 2 = nom et lien sur le répertoire
   // position 3 = Date
   // position 4 = Taille
   // position 5 = Permissions
   // position 6 = Pic-Manager
   // => $dirpres_fma="111011";
   $dirpres_fma="111011";

   // $dircmd_fma permet de controler les actions autorisées relatives aux répertoires (0 non-autorisé / 1 autorisé)
   // position 1 = create
   // position 2 = rename
   // position 3 = delete
   // position 4 = chmod
   // position 5 = not used
   // => $dircmd_fma="10000";
   $dircmd_fma="11110";


// -------------
// FICHIERS ----
// -------------
   // $extension_fma permet de définir la liste des extensions valide
   // $extension_fma="doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf mp3";
   // => Si $extension_fma="*"; : tous les types de fichiers sont autorisés
   $extension_fma="*";

   // $extension_Edit_fma permet de définir la liste des extensions qui seront éditables
   // $extension_Edit_fma="txt php js html htm";
   $extension_Edit_fma="txt php js html htm";

   // $extension_Wysiwyg_fma permet de définir la liste des extensions Editables qui supporteront un editeur Wysiwyg (TinyMce par exemple)
   // $extension_Wysiwyg_fma="html htm";
   $extension_Wysiwyg_fma="html htm";

   // $ficlimit_fma permet de controler l'affichage de certains fichiers (.htaccess, config.php ...)
   // CETTE LIMITE s'etend à tout le système de fichier !!
   // anonyme  => ce fichier n'est visible que par les anonymes
   // membre   => ce fichier n'est visible que par les membres
   // '2,5'    => ce fichier n'est visible que par les membres du(des) groupes x,y,...
   // '-2,-5'  => ce fichier sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
   // admin    => ce fichier n'est visible que par les administrateurs
   //
   // => $ficlimit_fma=
   // array (
   //       'license.txt' => 'anonyme',
   //       'developpement-modules-V1.2.pdf' => 'membre',
   //       'edito.txt' => '2,5',
   //       'config.php' => 'admin'
   // );
   $ficlimit_fma=array ();

   // $infos_fma permet d'inclure automatiquement un fichier particulier (par exemple une bannière ...) s'il se trouve dans le répertoire courant
   $infos_fma="infos.txt";

   // $ficpres_fma permet de controler les informations affichées relatives aux fichiers (0 non affiché / 1 affiché)
   // position 1 = icone
   // position 2 = nom et lien sur le fichier
   // position 3 = Date
   // position 4 = Taille
   // position 5 = Permissions
   // => $ficpres_fma="11101";
   $ficpres_fma="11111";

   // $ficcmd_fma permet de controler les actions autorisées relatives aux fichiers (0 non-autorisé / 1 autorisé)
   // position 1 = create / upload
   // position 2 = rename
   // position 3 = delete
   // position 4 = chmod
   // position 5 = edit
   // position 6 = move
   // => $ficcmd_fma="100011";
   $ficcmd_fma="111111";

   // $url_fma_modifier permet d'adjoindre un fichier de type xxxxx.mod.php associé à celui-ci et contenant une variable ($url_modifier) qui permet de modifier le comportement du lien se trouvant sur les fichiers affichés par FMA
   // voir le comportement du fichier download.conf.php ET download.mod.php
   $url_fma_modifier=false;

// ----------
// THEME ----
// ----------
   // Vous pouvez spécifier les fichiers de theme utilisés par ce fichier de configuration
   // fichier du theme général
   $themeG_fma="f-manager.html";
   // fichier utilisé lors des actions (delete, edit, ...)
   $themeC_fma="f-manager-cmd.html";

   // Vous pouvez spécifier la représentation de la racine
   // $home_fma="";          => représentation standard
   // $home_fma="Home";      => Un texte
   // $home_fma="<img ...>"; => Une image
   $home_fma="";

   // $NPDS_fma permet d'inclure le files-manager dans le theme de NPDS
   $NPDS_fma=false;

   // $css_fma permet d'inclure la css d'un theme / Cette option n'a de sens que si $NPDS_fma=false
   if (($NPDS_fma===false) and (file_exists("themes/$Default_Theme/style/f-manager.css")))
      $css_fma= "themes/$Default_Theme/style/f-manager.css";
   else
      $css_fma= "themes/$Default_Theme/style/style.css";

   // $wopen_fma permet de spécifier si une seule fenêtre fille est utilisée (0 : Non / 1 : Oui) lors d'une demande d'affichage
   // Attention cette option peut être incompatible avec certaines utilisation du File-Manager
   // $wopenH_fma permet de spécifier la hauteur de la fenêtre fille (par défaut 500)
   // $wopenW_fma permet de spécifier la largeur de la fenêtre fille (par défaut 400)
   // $wopenH_fma=500;
   // $wopenW_fma=400;
   // ==> $wopenH_fma et $wopenW_fma ne servent que si $wopen_fma=true ...
   $wopen_fma=true;

   // $uniq_fma permet de passer de F-manager à Pic-manager (vis et versa) dans une seule fenêtre
   $uniq_fma=true;

   // $urlext_fma permet de passer une variable complémentaire définie localement dans le fichier de configuration
   // $urlext_fma="&amp;groupe=$groupe";
   $urlext_fma="";
?>