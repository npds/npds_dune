<?php
/************************************************************************/
/* NPDS DUNE : Net Portal Dynamic System .                              */
/* ===========================                                          */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2012 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   // Ne pas modifier cette ligne -------
   $racine_fma=dirname($SCRIPT_FILENAME);
   // si n�cessaire, remplacer la ligne du dessus par
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
   //               => s'il existe un fichier de configuration portant le nom du groupe ALORS tous les membres du groupe partage le m�me fichier
   //               => Attention - cela s'arr�te au premier groupe qui rempli la condition
   // admin         => $access_fma="admin";
   $access_fma="membre";

   //$tri_fma permet de choisir le tri utilis� et son sens
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
   // Vous pouvez limiter la navigation � un sous-repertoire se trouvant sous la racine de votre site
   // => $basedir_fma=$racine_fma."/static";
   settype($groupe,'integer');
   $basedir_fma=$racine_fma."/users_private/groupe/$groupe";

   // $dirlimit_fma permet de controler la navigation dans des sous-repertoires
   // CETTE LIMITE s'etend � tous le syst�me de fichier !!
   // anonyme  => ce r�pertoire n'est visible que par les anonymes
   // membre   => ce r�pertoire n'est visible que par les membre
   // '2,5'    => ce r�pertoire n'est visible que par les membre du(des) groupes x,y,...
   // '-2,-5'  => ce r�pertoire sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
   // admin    => ce r�pertoire n'est visible que par les administrateur
   //
   // => $dirlimit_fma=
   // array (
   //       'ftp' => 'anonyme',
   //       'static' => 'membre',
   //       'documentations de d�veloppements' => '2,5',
   //       'admin' => 'admin'
   // );
   $dirlimit_fma=
   array (
         'mns' => '999',
   );

   // $dirsize_fma permet d'afficher la taille des r�pertoires
   // ATTENTION cette fonction peut-�tre consommatrice de CPU si vos r�pertoires contiennent de nombreux fichiers
   $dirsize_fma=false;

   // $dirpres_fma permet de controler les informations affich�es relatives aux repertoires (0 non affich� / 1 affich�)
   // position 1 = icone
   // position 2 = nom et lien sur le r�pertoire
   // position 3 = Date
   // position 4 = Taille
   // position 5 = Permissions
   // position 6 = Pic-Manager
   // => $dirpres_fma="111011";
   $dirpres_fma="111100";

   // $dircmd_fma permet de controler les actions autoris�es relatives aux repertoires (0 non-autoris� / 1 autoris�)
   // position 1 = create
   // position 2 = rename
   // position 3 = delete
   // position 4 = chmod
   // position 5 = not used
   // => $dircmd_fma="10000";
   $dircmd_fma="00000";


// -------------
// FICHIERS ----
// -------------
   // $extension_fma permet de d�finir la liste des extensions valide
   // $extension_fma="doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf mp3";
   // => Si $extension_fma="*"; : tous les types de fichiers sont autoris�s
   $extension_fma="doc xls pps ppt sxw xls sxi sxd sxg stw rtf txt pdf zip rar tar tgz gif jpg jpeg png swf mp3";

   // $extension_Edit_fma permet de d�finir la liste des extensions qui seront �ditables
   // $extension_Edit_fma="txt php js html htm";
   $extension_Edit_fma="";

   // $extension_Wysiwyg_fma permet de d�finir la liste des extensions Editables qui supporterons un editeur Wysiwyg (TinyMce par exemple)
   // $extension_Wysiwyg_fma="html htm";
   $extension_Wysiwyg_fma="";

   // $ficlimit_fma permet de controler l'affichage de certains fichiers (.htaccess, config.php ...)
   // CETTE LIMITE s'etend � tous le syst�me de fichier !!
   // anonyme  => ce fichier n'est visible que par les anonymes
   // membre   => ce fichier n'est visible que par les membre
   // '2,5'    => ce fichier n'est visible que par les membre du(des) groupes x,y,...
   // '-2,-5'  => ce fichier sera visible par Tous les membres sauf ceux du(des) groupes x,y,...
   // admin    => ce fichier n'est visible que par les administrateur
   //
   // => $ficlimit_fma=
   // array (
   //       'license.txt' => 'anonyme',
   //       'developpement-modules-V1.2.pdf' => 'membre',
   //       'edito.txt' => '2,5',
   //       'config.php' => 'admin'
   // );
   $ficlimit_fma= array (
   '.htaccess'           => '999',
   'config.php'          => '999',
   'pic-manager.txt'     => '999',
   'index.html'          => '999',
   'upload.conf.php'     => '999'
   );

   // $infos_fma permet d'inclure automatiquement un fichier particulier (par exemple une banni�re ...) s'il se trouve dans le r�pertoire courant
   $infos_fma="infos.txt";
   $ficlimit_fma[$infos_fma]="999"; // permet de ne pas afficher le fichier dans la liste des fichiers ... car il est affect� � un groupe qui n'existe pas !

   // $ficpres_fma permet de controler les informations affich�es relatives aux fichiers (0 non affich� / 1 affich�)
   // position 1 = icone
   // position 2 = nom et lien sur le fichier
   // position 3 = Date
   // position 4 = Taille
   // position 5 = Permissions
   // => $ficpres_fma="11101";
   $ficpres_fma="11110";

   // $ficcmd_fma permet de controler les actions autoris�es relatives aux fichiers (0 non-autoris� / 1 autoris�)
   // position 1 = create / upload
   // position 2 = rename
   // position 3 = delete
   // position 4 = chmod
   // position 5 = edit
   // position 6 = move
   // => $ficcmd_fma="100011";
   $ficcmd_fma="000000";

   // $url_fma_modifier permet d'adjoindre un fichier de type xxxxx.mod.php associ� � celui-ci et contenant une variable ($url_modifier) qui permet de modifier le comportement du lien se trouvant sur les fichiers affich�s par FMA
   // voir le comportement du fichier download.conf.php ET download.mod.php
   $url_fma_modifier=true;

// ----------
// THEME ----
// ----------
   // Vous pouvez sp�cifier les fichiers de theme utilis�s par ce fichier de configuration
   // fichier du theme g�n�ral
   $themeG_fma="f-manager-banque.html";
   // fichier utilis� lors des actions (delete, edit, ...)
   $themeC_fma="f-manager-cmd.html";

   // Vous pouvez sp�cifier la repr�sentation de la racine
   //$home_fma="";          => repr�sentation standard
   //$home_fma="Home";      => Un texte
   //$home_fma="<img ...>"; => Une image
   $home_fma="";

   // $NPDS_fma permet d'inclure le files-manager dans le theme de NPDS
   $NPDS_fma=false;

   // $css_fma permet d'inclure la css d'un theme / Cette option n'a de sens que si $NPDS_fma=false
   if (($NPDS_fma===false) and (file_exists("themes/$Default_Theme/style/f-manager.css")))
      $css_fma= "themes/$Default_Theme/style/f-manager.css";
   else
      $css_fma= "themes/$Default_Theme/style/style.css";

   // $wopen_fma permet de sp�cifier si une seule fenetre fille est utilis�e (0 : Non / 1 : Oui) lors d'une demande d'affichage
   // Attention cette option peut �tre incompatible avec certaines utilisation du File-Manager
   // $wopenH_fma permet de sp�cifier la hauteur de la fenetre fille (par d�faut 500)
   // $wopenW_fma permet de sp�cifier la largeur de la fenetre fille (par d�faut 400)
   // $wopenH_fma=500;
   // $wopenW_fma=400;
   // ==> $wopenH_fma et $wopenW_fma ne servent que si $wopen_fma=true ...
   $wopen_fma=false;

   // $uniq_fma permet de passer de F-manager � Pic-manager (vis et versa) dans une seule fen�tre
   $uniq_fma=false;

   // $urlext_fma permet de passer une variable compl�mentaire d�finie localement dans le fichier de configuration
   $urlext_fma="&amp;groupe=$groupe";
?>