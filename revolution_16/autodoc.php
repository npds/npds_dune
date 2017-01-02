<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2017 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
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
if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {include ("grab_globals.php");}

function Access_Error () {
  include("admin/die.php");
}
function dochead() {
   if (file_exists("meta/meta.php")) {
      $Titlesitename="NPDS - Doc";
      include ("meta/meta.php");
      include ("modules/include/header_head.inc");
      echo '
      </head>
      <body class="my-1 mx-1">
      ';
   }
}

function docfoot() {
echo '
   </body>
';
}

function autodoc($fichier, $paragraphe) {
   $fcontents = @file($fichier);

   if ($fcontents=='') {Access_Error();}
   $pasfin=false;
   $meta='';
   dochead();
   echo '
   <table class="table table-striped">
       <caption>Documentation</caption>
       <thead>
       <tr>
         <th>Fonction</th>
         <th>Documentation</th>
       </tr>
       </thead>
       <tbody>';
   while ( list($line_num, $line)=each($fcontents) ) {
      if ($paragraphe!="") {
         if (strstr($line,"#autodoc:<$paragraphe>")) {
            $line='';
            $pasfin=true;
         }
         if (strstr($line,"#autodoc:</$paragraphe>")) {
            $line='';
            $pasfin=false;
         }
      } else {
         $pasfin=true;
      }
      $line=trim($line);
      if ((strstr($line,"#autodoc")) and ($pasfin)) {
         $posX=strpos($line,':');
         $morceau1=trim(substr($line,strpos($line,"#autodoc")+8,$posX-8));
         $morceau2=rtrim(substr($line,$posX+1));
//         if ($morceau1=='' AND $morceau2=='') {$rowcolor="style=\"background-color: #FFFFFF;\"";} else {$rowcolor="style=\"background-color: #F0F0F0;\"";}
         $meta.='
         <tr>
            <td><code>'.$morceau1.'<code></td>
            <td>'.$morceau2.'</span></td>
         </tr>';
      } else if ((strstr($line,"# autodoc")) and ($pasfin)) {
         $posX=strpos($line,':');
         $morceau1=ltrim(substr($line,strpos($line,"# autodoc")+9,$posX-9));
         $morceau2=rtrim(substr($line,$posX+1));
//         if ($morceau1=='' AND $morceau2=='') {$rowcolor="style=\"background-color: #FFFFFF;\"";} else {$rowcolor="style=\"background-color: #F0F0F0;\"";}
         $meta.="
         <tr $rowcolor>
         <td nowrap=\"nowrap\" align=\"left\"><code>$morceau1<code></td>
         <td><span style=\"font-size: 10px; font-family: Tahoma, Arial;\">$morceau2</span>&nbsp;</td>
         </tr>";
      }
   }
   echo $meta;
   echo '</tbody</table>
   <p align="right" style="font-size: 10px; font-family: Tahoma, Arial;">AutoDoc pour <a href="http://www.npds.org">NPDS</a></p>';
}

function docu() {
   if (file_exists("meta/meta.php")) {
      $Titlesitename="NPDS - Meta-Lang";
      include ("meta/meta.php");
   }
   echo "</head>\n";
   echo "<body topmargin=\"2\" leftmargin=\"0\" rightmargin=\"0\" style=\"background-color: #FFFFFF;\">";
   echo "<table cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" align=\"left\" border=\"0\"><tr><td>";
   echo "<span style=\"font-size: 10px; font-family: Tahoma, Arial;\"><b>&nbsp;Mainfile.php</b></span>";
      autodoc("mainfile.php", "Mainfile.php");
   echo "</td></tr><tr><td>";
   echo "<hr noshade=\"noshade\" class=\"ongl\" />";
   echo "<span style=\"font-size: 10px; font-family: Tahoma, Arial;\"><b>&nbsp;Powerpack_f.php</b></span>";
      autodoc("powerpack_f.php", "Powerpack_f.php");
   echo "</td></tr></table>";
   echo "</body></html>";
   die();
}

settype ($op, 'string');
if ($op=="blocs") {
   docu();
}
if ($op=="main") {
   autodoc('mainfile.php','');
}
?>