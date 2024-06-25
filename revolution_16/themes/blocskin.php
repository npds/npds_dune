<?php
/************************************************************************/
/* DUNE by NPDS -                                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* 
destination : un bloc
syntaxe : include#themes/blocskin.php
sortie : une liste de sélection des skins disponible pour le thème courant
objet : visualisation de la page courante avec le skin choisi
*/

global $Default_Skin, $Default_Theme, $nuke_url;
$skinOn = '';
if ($user) {
   $user2 = base64_decode($user);
   $cookie = explode(':', $user2);
   $ibix=explode('+', urldecode($cookie[9]));
   $skinOn = substr($ibix[0],-3)!='_sk' ? '' : $ibix[1];
}
else
   $skinOn = substr($Default_Theme,-3)!='_sk' ? '' : $Default_Skin;

$content = '';
if($skinOn != '')
   $content.='
<div class="form-floating">
   <select class="form-select" id="blocskinchoice"><option>'.$skinOn.'</option></select>
   <label for="blocskinchoice">Choisir un skin</label>
</div>
<ul class="nav navbar-nav ms-auto">
 <li class="nav-item dropdown" data-bs-theme="light">
     <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="theme-darkness" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme">
       <i class="bi bi-circle-half"></i>
       <span class="d-lg-none ms-2">[french]Choisir tonalit&#xE9;[/french][english]Choose tone[/english][spanish]Elige tono[/spanish][german]Ton w&#xE4;hlen[/german][chinese]&#x9009;&#x62E9;&#x8272;&#x8C03;[/chinese]</span>
     </a>
     <ul class="dropdown-menu dropdown-menu-end">
       <li>
         <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
           <i class="bi bi-sun-fill"></i><span class="ms-2">[french]Clair[/french][english]Light[/english][chinese]&#x6E05;&#x9664;[/chinese][spanish]Claro[/spanish][german]klar[/german]</span>
         </button>
       </li>
       <li>
         <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="true">
           <i class="bi bi-moon-stars-fill"></i><span class="ms-2">[french]Sombre[/french][english]Dark[/english][chinese]&#x9ED1;&#x6697;&#x7684;[/chinese][spanish]Oscuro[/spanish][german]dunkel[/german]</span>
         </button>
       </li>
     </ul>
   </li>
</ul>

<script type="text/javascript">
   //<![CDATA[
   var el = document.querySelector(\'link[href="'.$nuke_url.'/lib/bootstrap/dist/css/bootstrap-icons.css"]\')
   if (!el) {
      el = document.createElement("link");
      el.rel= "stylesheet";
      el.href = "'.$nuke_url.'/lib/bootstrap/dist/css/bootstrap-icons.css";
      el.type = "text/css"
      document.getElementsByTagName("head")[0].appendChild(el);
   }

   fetch("api/skins.json")
      .then(response => response.json())
      .then(data => load(data));
   function load(data) {
      const skins = data.skins;
      const select = document.querySelector("#blocskinchoice");
      skins.forEach((value, index) => {
         const option = document.createElement("option");
         option.value = index;
         option.textContent = value.name;
         select.append(option);
      });
      select.addEventListener("change", (e) => {
         const skin = skins[e.target.value];
         if (skin) {
            document.querySelector("#bsth").setAttribute("href", skin.css);
            document.querySelector("#bsthxtra").setAttribute("href", skin.cssxtra);
         }
      });
      const changeEvent = new Event("change");
      select.dispatchEvent(changeEvent);
   }

   (function () {
     "use strict";
         function toggledarkness() {
         let themeMenu = document.querySelector("#theme-darkness");
         if (!themeMenu) return;
         document.querySelectorAll("[data-bs-theme-value]").forEach(value => {
            value.addEventListener("click", () => {
            const theme_darkness = value.getAttribute("data-bs-theme-value");
            document.body.setAttribute("data-bs-theme", theme_darkness);
            });
         });
      }
      toggledarkness();
   })();

   //]]>
</script>';
else
   $content.='<div class="alert alert-danger">Thème non skinable</div>';
$content = aff_langue($content);
?>