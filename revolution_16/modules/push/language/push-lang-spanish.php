<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
function push_translate($phrase) {
   $tmp=push_translate_pass1($phrase);
   return ($tmp);
}

function push_translate_pass1($phrase) {
 switch($phrase) {
   case "Latest Poll Results": $tmp = "Ultima encuesta"; break;
   case "Latest Articles": $tmp = "Ultimos articulos"; break;
   case "Total Votes:": $tmp = "Total de votos :"; break;
   case "Poll": $tmp = "Encuesta"; break;
   case "Home": $tmp = "Index"; break;
   case "Posted by": $tmp = "Emitido por"; break;
   case "Member(s)": $tmp = "Miembros"; break;
   case "Internal": $tmp = "Interno"; break;
   case "Next": $tmp = "Siguiente"; break;
   case "Web links": $tmp = "V&iacute;nculos"; break;

   default: $tmp = "necesita ser traducido <b>[** $phrase **]</b>"; break;
 }
 return $tmp;
}
?>