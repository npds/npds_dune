<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2024 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/*********************************************************************************************************************************/
/* AutoDoc permet de de lire le contenu d'un fichier php et d'extraire la documentation intégrée (sous forme de commentaire php) */
/* Le format d'une ligne AutoDoc de documentation doit-être :                                                                    */
/*    #autodoc La_documentation_de_la_fonction                                                                                   */
/*    par exemple : #autodoc !date! : Date du jour en Javascript (le : entre le nom du meta-mot ou de la fonction et             */
/*                  son explication est OBLIGATOIRE)                                                                             */
/*    mais aussi #autodoc: pour faire un saut de ligne HTML dans votre documentation (<br />)                                    */
/*                                                                                                                               */
/* Autodoc assure aussi la selection d'un ensemble cohérent dans un fichier via #autodoc:<paragraphe> ... #autodoc:</paragraphe> */
/* Le mainfile.php est un bon exemple (extraction des fonctions de type BLOCS)                                                   */
/*                                                                                                                               */
/*********************************************************************************************************************************/
if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) include ("mainfile.php");

function Access_Error () {
  include("admin/die.php");
}

function dochead($a,$b) {
   if (file_exists("meta/meta.php")) {
      $Titlesitename="NPDS - Doc";
      include ("meta/meta.php");
      include ("modules/include/header_head.inc");
      echo '
   </head>
   <body class="my-3 mx-3">
      <h1 class="mb-3">Documentation des fonctions NPDS</h1>
      <p class="h4 my-3"><i class="me-1 far fa-file-alt"></i>'.$a.' '.$b.' <span class="text-body-secondary">[ Documentation ]</span></p>';
   }
}

function docfoot() {
   echo '
      <p class="text-end small my-3 text-body-secondary">Autodoc by NPDS</p>
   </body>
</html>';
}

function autodoc($fichier, $paragraphe) {
   $fcontents = @file($fichier);
   if ($fcontents=='') Access_Error();
   $pasfin=false;
   $tabdoc='';
   echo '
      <table class="table table-striped table-bordered table-responsive">
         <thead>
          <tr>
            <th>Fonction</th>
            <th>Documentation</th>
          </tr>
         </thead>
         <tbody>';
   foreach($fcontents as $line_num => $line ) {
      if ($paragraphe!='') {
         if (strstr($line,"#autodoc:<$paragraphe>")) {
            $line='';
            $pasfin=true;
         }
         if (strstr($line,"#autodoc:</$paragraphe>")) {
            $line='';
            $pasfin=false;
         }
      } else 
         $pasfin=true;

      $line=trim($line);
      if ((strstr($line,'#autodoc')) and ($pasfin)) {
         $posX=strpos($line,':');
         $morceau1=trim(substr($line,strpos($line,"#autodoc")+8,$posX-8));
         $morceau2=rtrim(substr($line,$posX+1));
         $tabdoc.='
            <tr>
               <td><code>'.$morceau1.'</code></td>
               <td>'.$morceau2.'</td>
            </tr>';
      } else if ((strstr($line,'# autodoc')) and ($pasfin)) {
         $posX=strpos($line,':');
         $morceau1=ltrim(substr($line,strpos($line,'# autodoc')+9,$posX-9));
         $morceau2=rtrim(substr($line,$posX+1));
         $tabdoc.= '
            <tr>
               <td nowrap="nowrap"><code>'.$morceau1.'</code></td>
               <td>'.$morceau2.'</td>
            </tr>';
      }
   }
   echo $tabdoc;
   echo '
         </tbody>
      </table>';
}

function docu() {
   echo '
      <p class="h5 my-3">Mainfile.php</p>';
      autodoc("mainfile.php", "Mainfile.php");
   echo '
      <p class="h5 my-3">Powerpack_f.php</p>';
      autodoc("powerpack_f.php", "Powerpack_f.php");
   echo '
      <div class="alert alert-success mt-3">Rappels :<br />Si votre thème est adapté, chaque bloc peut contenir :<br />- class-title#nom de la classe de la CSS pour le titre du bloc<br />- class-content#nom de la classe de la CSS pour le corps du bloc<br />- uri#uris séparée par un espace</div>
      <p class="text-end small my-3 text-body-secondary">Autodoc by NPDS</p>
   </body>
</html>';
   die();
}
settype ($op, 'string');
if ($op=='blocs') {
   dochead('mainfile.php','powerpack_f.php');
   docu();
}
if ($op=='main') {
   dochead('mainfile.php','');
   autodoc('mainfile.php','');
   docfoot() ;
}
if ($op=='func') {
   dochead('functions.php','');
   autodoc('functions.php','');
   docfoot() ;
}
?>