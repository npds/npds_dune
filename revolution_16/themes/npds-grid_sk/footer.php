<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-grid_sk 2022 by jpb                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_LB +|| col_princ +|| col_RB                                */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst, $theme_darkness;
$moreclass = 'col-12';

switch ($pdst) {
   case '-1': case '3': case '5':
      echo '
            </div>';
   break;
   
   case '1': case '2':
      echo '
            </div>
            <div id="col_RB" class="col-d">';
      rightblocks($moreclass);
         echo'
            </div>';
   break;
   
   case '4':
      echo '
            </div>
            <div id="col_LB" class="col-g">';
      leftblocks($moreclass);
      echo '
            </div>
            <div id="col_RB" class="col-d">';
      rightblocks($moreclass);
      echo '
            </div>';
   break;
   
   case '6':
      echo '
            </div>
            <div id="col_LB" class="col-g">';
      leftblocks($moreclass);
      echo '
            </div>';
   break;
   default:
      echo '
            </div>';
   break;
}
echo '
         </div>';

// ContainerGlobal permet de transmettre · Theme-Dynamic un élément de personnalisation après
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