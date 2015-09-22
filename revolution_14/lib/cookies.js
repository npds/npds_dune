<!--
function getCookie(name) {
   var offset;
   var end = "";
   var search = name + "=";
   if (document.cookie.length > 0) {
      offset = document.cookie.indexOf(search);
      if (offset != -1) {
         offset += search.length;
         end = document.cookie.indexOf(";", offset);
         if (end == -1)
            end = document.cookie.length;
         return unescape(document.cookie.substring(offset, end));
      } else {
         return end;
      }
   } else {
      return end;
   }
}
function setCookie(name, value, duree) {
   var date_exp = new Date();
   // duree de vie du cookie en jour / si duree='' => cookie de session
   if (duree=='')
      document.cookie = name + "=" + escape(value);
   else {
     date_exp.setTime(date_exp.getTime()+(duree*3600*1000));
     document.cookie = name + "=" + escape(value)+"; expires=" + date_exp.toGMTString();
   }
}
function deleteCookie(name) {
   if (getCookie(name)) {
      document.cookie = name + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
   }
}
//-->