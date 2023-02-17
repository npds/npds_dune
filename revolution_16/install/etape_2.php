<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'install.php')) die();

function etape_2() {
   global $stage, $langue, $qi;
   $stage = 2;
   $lang_symb = substr($langue, 0, 3);
   if(file_exists('install/languages/'.$langue.'/licence-'.$lang_symb.'.txt')) {
      $licence_file = 'install/languages/'.$langue.'/licence-'.$lang_symb.'.txt';
      $myfile = @fopen($licence_file,"r");
      $licence_text = fread($myfile, filesize($licence_file));
      fclose($myfile);
      $nohalt = true;
   } else {
      $licence_text = ins_translate('Fichier de licence indisponible !');
      $nohalt = false;
   }
   echo '
               <h3 class="mb-2">'.ins_translate('Licence').'</h3>
               <form name="gpl" method="post" action="install.php">
                  <fieldset class="mb-3">
                     <label for="licence">'.ins_translate("L'utilisation de NPDS est soumise à l'acceptation des termes de la licence GNU GPL ").' :</label>
                     <textarea name="licence" class="form-control" id="licence" rows="15" readonly="readonly">'.$licence_text.'</textarea>
                  </fieldset>
                  <input type="hidden" name="langue" value="'.$langue.'" />
                  <input type="hidden" name="stage" value="3" />';
   if($nohalt)
      echo '
                  <button type="submit" class="btn btn-success">'.ins_translate("J'accepte").'</button>';
   else
      echo '
                  <div class="alert alert-danger">stop !</div>';
   echo '
               </form>
            </div>';
}
?>
