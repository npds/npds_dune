<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* For Collab WS-Pad 1.0 by Developpeur                                 */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Attention Ce fichier doit contenir du javascript compatible tiny_mce */
/* qui doit obligatoirement se trouver concaténer dans la variable $tmp */
/************************************************************************/

global $surlignage, $font_size, $auteur, $groupe;
$tmp.="
theme_advanced_path : false,
theme_advanced_buttons3 : 'image,media,|,tablecontrols,visualaid,|,insertdate,inserttime,|,npds_img,npds_gperso,npds_gmns,npds_gupl,npds_plug,npds_langue',
tiny_mce_groupe : '&groupe=$groupe',

setup: function (ed) {
   ed.onKeyDown.add(function(ed, e) {
      // faisons une 'static' en javascript
      if ( typeof this.counter == 'undefined' ) this.counter = 0;

      // On capte les touches de directions
      if (e.keyCode >= 37 && e.keyCode <= 40) {
         this.counter=0;
         return true;
      }
      // On capte la touche backspace
      if ((e.keyCode == 8) || (e.keyCode == 13)) {
         this.counter=0;
         return true;
      }

      //ed.windowManager.alert('key : ' + e.keyCode);

      if (this.counter==0) {
         tinymce.activeEditor.formatter.register('wspadformat', {
            inline     : 'span',
            styles     : {'background-color' : '$surlignage', 'font-size' : '$font_size'},
            classes    : '$auteur'
         });
         tinymce.activeEditor.formatter.apply('wspadformat');
         this.counter=1;
      }
   });

   // déplacement dans le RTE via la sourie
   ed.onMouseDown.add(function(ed, e) {
      this.counter=0;
   });
}\n";
?>