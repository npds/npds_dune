<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for NPDS - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

#autodoc blocnotes ($typeBlocNote="shared", $nomBlocNote="", $largeur="100%", $nblBlocNote="5", $gifbgcolor="") : Bloc blocnotes<br />=> syntaxe :
#autodoc : function#blocnotes<br />params#shared OU context (partagé ou contextuel), nom_du_bloc OU le texte : $username (nom du bloc=nom du membre ou de l'admin), largeur (en % ou en pixel), nb de ligne de la textarea, couleur du fond du gif (vide=transparent, sinon RVB)<br />
#autodoc : function#blocnotes<br />params#shared,TNT,150 (blocnote partagé s'appelant TNT de largeur 150 pixel)
function blocnotes ($typeBlocNote="shared", $nomBlocNote="", $largeur="100%", $nblBlocNote="5", $gifbgcolor="", $affiche=true) {
   global $REQUEST_URI;
   $aff='';
   settype($block_title,'string');

   if ($typeBlocNote=="shared") {
      if ($nomBlocNote=="\$username") {
         global $cookie;
         $nomBlocNote=$cookie[1];
      }
      $bnid=md5($nomBlocNote);
   } elseif ($typeBlocNote=="context") {
      if ($nomBlocNote=="\$username") {
         global $cookie, $admin;
         $nomBlocNote=$cookie[1];
         $cur_admin=explode(":",base64_decode($admin));
         if ($cur_admin) {
            $nomBlocNote=$cur_admin[0];
         }
      }
      if (stristr($REQUEST_URI,"article.php")) {
         $bnid=md5($nomBlocNote.substr($REQUEST_URI,0,strpos($REQUEST_URI,"&")));
      } else {
         $bnid=md5($nomBlocNote.$REQUEST_URI);
      }
   } else {
      $nomBlocNote='';
   }
   if ($nomBlocNote) {
      global $theme;
   if ($block_title=='')
      $title=$nomBlocNote;
   else
      $title=$block_title;
      $aff.= '
            <form method="post" action="modules.php?ModPath=bloc-notes&amp;ModStart=blocnotes" name="A'.$bnid.'">
            <div class="form-group">
               <textarea class="form-control" cols="20" rows="'.$nblBlocNote.'" name="texteBlocNote" ></textarea>
            </div>
            <div class="form-group">
               <input type="hidden" name="uriBlocNote" value="'.urlencode($REQUEST_URI).'" />
               <input type="hidden" name="typeBlocNote" value="'.$typeBlocNote.'" />
               <input type="hidden" name="nomBlocNote" value="'.$nomBlocNote.'" />
               <div class="row">
                  <div class="col-12">
                     <button type="submit" name="okBlocNote" class="btn btn-outline-primary btn-sm btn-block" > <i class="fa fa-check"></i>&nbsp;Valider</button>
                     <button type="submit" name="supBlocNote" class="btn btn-outline-danger btn-sm btn-block" value="RAZ"><i class="fa fa-remove"></i>&nbsp; Effacer</button>
                  </div>
               </div>
            </div>
         </form>
         <script type="text/javascript" src="modules.php?ModPath=bloc-notes&amp;ModStart=blocnotes-read&amp;bnid='.$bnid.'"></script>';
   }
   if ($affiche) {
       themesidebox($title, $aff);
   } else {
      return ($aff);
   }
}
?>