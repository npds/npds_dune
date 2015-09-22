<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* BLOC-NOTES engine for NPDS - Philippe Brunier & Arnaud Latourrette   */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
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
   if ($typeBlocNote=="shared") {
      if ($nomBlocNote=="\$username") {
         global $cookie;
         $nomBlocNote=$cookie[1];
      }
      $bouton="";
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
      $nomBlocNote="";
   }
   if ($nomBlocNote) {
      global $theme;
      if ($affiche) {
         $aff="<style type=\"text/css\">";
         if (@file_exists("themes/$theme/style/bloc-note.css")) {
            $aff.=file_get_contents("themes/$theme/style/bloc-note.css");
         } else {
            $aff.=file_get_contents("modules/bloc-notes/bloc-note.css");
         }
         $aff.= "</style>";
      } else {
         $aff="";
      }

      $aff.= "<table width=\"".$largeur."\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\"><tr align=\"center\" valign=\"middle\"><td>";
      $aff.= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";

      if ($affiche) {
         $aff.= "
         <tr>
            <td class=\"bn_head_g\" valign=\"middle\">&nbsp;$nomBlocNote</td>
            <td width=\"51\" height=\"20\">";
         if ($ibid=theme_image("modules/bloc-note.gif")) {$imgtmpPI=$ibid;} else {$imgtmpPI="modules/bloc-notes/bloc-note.gif";}
         $aff.= "<img src=\"$imgtmpPI\" alt=\"\"></td>
         </tr>";
      }
      $aff.= "
         <tr valign=\"top\">
            <td colspan=\"2\" class=\"bn_corps\">
            <form method=\"post\" action=\"modules.php?ModPath=bloc-notes&amp;ModStart=blocnotes\" name=\"A".$bnid."\">".
            "<textarea class=\"bn_textbox_no_mceEditor\" cols=\"20\" rows=\"".$nblBlocNote."\" name=\"texteBlocNote\" ></textarea><br />
            <input type=\"hidden\" name=\"uriBlocNote\" value=\"".urlencode($REQUEST_URI)."\" />
            <input type=\"hidden\" name=\"typeBlocNote\" value=\"".$typeBlocNote."\" />
            <input type=\"hidden\" name=\"nomBlocNote\" value=\"".$nomBlocNote."\" />
            <input type=\"submit\" name=\"okBlocNote\" value=\"Ok\" class=\"bouton_standard bn_bouton_standard\" />&nbsp;<input type=\"submit\" name=\"supBlocNote\" value=\"RAZ\" class=\"bouton_standard bn_bouton_standard\" />
            </form><script type=\"text/javascript\" src=\"modules.php?ModPath=bloc-notes&amp;ModStart=blocnotes-read&amp;bnid=".$bnid."\"></script>
            </td>
         </tr><tr><td colspan=\"2\" class=\"bn_foot\">&nbsp;</td></tr>
         </table>\n";
      $aff.= "</td></tr></table>";
   }
   if ($affiche) {
       themesidebox("no-title", $aff);
   } else {
      return ($aff);
   }
}
?>