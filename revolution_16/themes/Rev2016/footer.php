<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme :  Bmag jpb Jireck      Rev2016                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

global $pdst, $theme_darkness;
$moreclass = '';
switch ($pdst) {
   case '-1':
      echo '
   </div>
   </div>';
   break;
   case '2':
      echo '
    </div>
       <div id="col_RB" class="col-md-3 col-sm-12">';
           leftblocks($moreclass);
           rightblocks($moreclass);
      echo '
       </div>
    </div>';
   break;
   default :
      echo '
    </div>
       <div id="col_RB" class="col-md-3 col-sm-12">';
           leftblocks($moreclass);
           rightblocks($moreclass);
      echo '
       </div>
    </div>';
   break;
}
// ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation après
// le chargement de footer.html / Si vide alors rien de plus n'est affiché par TD
$ContainerGlobal='
   </div>';
// pilotage du mode dark/light du thème ...
echo '
   <script type="text/javascript">
   //<![CDATA[
      (() => {
        "use strict"
         const theme = localStorage.setItem("theme", "'.$theme_darkness.'");
         var getStoredTheme = localStorage.getItem("theme");
         if (getStoredTheme === "auto") {
            document.querySelector("body").setAttribute("data-bs-theme", (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"))
          } else {
            document.querySelector("body").setAttribute("data-bs-theme", "'.$theme_darkness.'");
          }
      })()
   //]]>
   </script>';

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>