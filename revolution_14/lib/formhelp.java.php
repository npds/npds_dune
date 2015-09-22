<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
#
# Modification by GaWaX @ g2001.net (c) Jun 2002
# Intégré dans NPDS V par snipe 04/2003
?>

<script type="text/javascript">
//<![CDATA[
function addText(instextD, instextF) {
   var mess = document.coolsus.message;
   //IE support
   if (document.selection) {
      mess.focus();
      sel = document.selection.createRange();
      sel.text = instextD + sel.text + instextF;
      mess.focus();
   }
   //MOZILLA/NETSCAPE support
   else if (mess.selectionStart || mess.selectionStart == "0") {
      var startPos = mess.selectionStart;
      var endPos = mess.selectionEnd;
      var chaine = mess.value;
      mess.value = chaine.substring(0, startPos) + instextD + chaine.substring(startPos, endPos) + instextF  + chaine.substring(endPos, chaine.length);
      mess.selectionStart=startPos + instextD.length;
      mess.selectionEnd=startPos + instextD.length;
      mess.focus();
   }
}

function DoAdd(externe, champ, text) {
   if (externe)
      var mess = opener.document.coolsus[champ];
   else
      return;

   if (opener.document.selection) {
      mess.focus();
      sel = opener.document.selection.createRange();
      sel.text = text + sel.text;
      mess.focus();
   } //MOZILLA/NETSCAPE support
   else if (mess.selectionStart || mess.selectionStart == "0") {
      var startPos = mess.selectionStart;
      var endPos = mess.selectionEnd;
      var chaine = mess.value;
      mess.value = chaine.substring(0, startPos) + text + chaine.substring(startPos, endPos) + chaine.substring(endPos, chaine.length);
      mess.selectionStart=startPos + text.length;
      mess.selectionEnd=startPos + text.length;
      mess.focus();
   }
}

function emoticon(text) {
   text = ' ' + text;
   addText(text, ' ');
}

function storeCaret(text) {
   if (text.createTextRange) text.caretPos = document.selection.createRange().duplicate();
}

function storeForm(textEl) {
   document.currentForm = textEl;
}
//]]>
</script>