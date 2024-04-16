/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/* NPDS Copyright (c) 2002-2018 by Philippe Brunier                     */
/* file : checkfieldinp.js                                              */
/* version : beta                                                       */
/* jpb 2015                                                             */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/*
controle et ajuste les entrées (input, textarea) des form en fonction 
de la longueur du champ de la BD correspondant.
dependence : jquery, bootbox
to do : translate
*/
inpandfieldlen = function(inpid,fieldlen) {
   // ressources : fixedCharCodeAt() and countUtf8(str) after from @autor Frank Neff <fneff89@gmail.com>
   function fixedCharCodeAt(str, idx) {
      idx = idx || 0;
      var code = str.charCodeAt(idx);
      var hi, low;
      if (0xD800 <= code && code <= 0xDBFF) {
         hi = code;
         low = str.charCodeAt(idx + 1);
         if (isNaN(low)) {
            throw 'Kein gültiges Schriftzeichen oder Speicherfehler!';
         }
         return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
      }
      if (0xDC00 <= code && code <= 0xDFFF) {return false;}
      return code;
   }
   function countUtf8(str) {
       var nby = 0;
       for (var n = 0; n < str.length; n++) {
           var charCode = fixedCharCodeAt(str, n);
           if (typeof charCode === "number") {
               if (charCode < 128) {nby = nby + 1;}
                else if (charCode < 2048) {nby = nby + 2;}
                else if (charCode < 65536) {nby = nby + 3;}
                else if (charCode < 2097152) {nby = nby + 4;}
                else if (charCode < 67108864) {nby = nby + 5;} 
                else {result = result + 6;}
           }
       }
       return nby;
   }

   $("#"+inpid).on("input", function() {
      var str= $("#"+inpid).val(), carleng = str.length, strl= countUtf8(str);
//      if(strl===fieldlen) {$("#"+inpid).attr("maxlength",carleng);}
      if(strl>fieldlen) {
         bootbox.alert("Your text is to long i cut it for you ;-) ==> Caracteres : " + carleng + " soit " + strl +"bytes");
         for (i = strl; i > (fieldlen+1); i--) { 
            if (strl === fieldlen) { break; }
            $("#"+inpid).val(str.slice(0,-1));
            str= $("#"+inpid).val();
            strl= countUtf8(str);
         }
      }
      $("#countcar_"+inpid).text(fieldlen-strl);

//      if(strl<fieldlen) {$("#"+inpid).attr("maxlength",fieldlen);}
   // debug
   console.log("car dans input ==> "+carleng);
   console.log("car en byte ==> "+strl);
   });
} 