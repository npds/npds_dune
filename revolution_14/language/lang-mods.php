<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* ce fichier permettra � partir de la version 5.0 de NPDS, la          */
/* personnalisation par le webmestre des MESSAGES (sauf ceux de l'admin)*/
/************************************************************************/

switch($phrase) {
   // EXEMPLES :
   // case "Pr�visualiser": $tmp="V�rifier mes informations"; break;
   // case "Preview":   $tmp="Verify my informations"; break;
   // case "Valider":   $tmp="\"No probl�me, valide mon gar�on !\""; break;
   // case "Submit":   $tmp="\"Yo, submit Man\""; break;
   
    case "One Day like Today...": $tmp=""; break;  
   
   
   
   case "Your account":
   case "Votre compte":
   case "Su cuenta":
      global $cookie;
      if ($cookie) {
         $tmp=$cookie[1];
      } else {
         $tmp="Connexion";
      }
   break;
}
?>