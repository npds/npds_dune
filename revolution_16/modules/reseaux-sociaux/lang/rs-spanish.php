<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* reseaux-sociaux : jpb 2016                                           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

function rs_translate($phrase) {
 switch ($phrase) {
   case "Liste des réseaux sociaux mis à disposition par l'administrateur." : $tmp = "Lista de redes sociales a su disposición por el administrador."; break;
   case "Ajouter ou supprimer votre identifiant à ces réseaux sociaux." : $tmp = "Añadir o quitar su nombre de usuario para estas redes sociales."; break;
   case "Identifiant" : $tmp = "Nombre de usuario"; break;
   case "Réseaux sociaux" : $tmp = "Redes sociales"; break;
   case "Ceci créera ou supprimera le lien d'accès dans toutes les pages du portail où ils sont disponibles (forums, articles, commentaires, liste des membres, etc) pour tous les utilisateurs." : $tmp = "Esto creará o eliminar el enlace de acceso en todas las páginas del portal donde están disponibles para todos los usuarios (foros, artículos, comentarios, lista de miembros, etc.)."; break;
   case "Editer" : $tmp = "Editar"; break;
   case "Fonctions" : $tmp = "Funciones"; break;
   case "Nom" : $tmp = "Nombre"; break;
   case "Icône" : $tmp = "Icono"; break;
   case "Sauvegarder" : $tmp = "Guardar"; break;

   default: $tmp = "Necesita una traducción [** $phrase **]"; break;
 }
  return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>