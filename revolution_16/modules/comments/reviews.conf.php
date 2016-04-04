<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2010 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// Don't remove this line !
global $C_start;

// $file_name : racine du nom de ce fichier  (article, pollBoth, ...)
$file_name="reviews";

// $forum : permet d'allouer un num�ro de forum pour chaque 'type de commentaires' (article, sondage, ...) - le num�ro de forum doit imp�rativement �tre NEGATIF
$forum=-3;

// $topic : permet d'allouer un num�ro UNIQUE pour chaque publication sur laquelle un commentaire peut �tre r�alis� (article num�ro X, sondage num�ro Y, ...)
settype($id,'integer');
if ($id!="") $topic=$id;

// $url_ret : URL de retour lorsque la soumission du commentaire est OK
$url_ret="reviews.php?op=showcontent&id=$topic";

// $formulaire : Formulaire SFORM si vous souhaitez avoir une grille de saisie en lieu et place de l'interface standard de saisie - sinon ""
$formulaire="reviews.form.php";

// $comments_per_page : Nombre de commentaire sur chaque page
$comments_per_page=3;

// Mise � jour de champ d'une table externe � la table des commentaires
// $req_add = op�ration � effectuer lorsque je rajoute un commentaire
// $req_del = op�ration � effectuer lorsque je cache un commentaire
// $req_raz = op�ration � effectuer lorsque je supprime tous les commentaires
$comments_req_add="";
$comments_req_del="";
$comments_req_raz="";
?>