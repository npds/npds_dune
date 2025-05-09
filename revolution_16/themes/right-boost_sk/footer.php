<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : right-boost_sk 2017 by jpb                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_princ + col_LB +|| col_RB                                  */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst, $theme_darkness;
$moreclass = 'col';
switch ($pdst) {
   case '-1':
      echo '
                     </div>
                  </div>
               </div>';
   break;
   default:
      echo '
               </div>
               <div id="col_LB" class="collapse show col-lg-4">
                  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-1">';
                     leftblocks($moreclass);
                     rightblocks($moreclass);
      echo '
                  </div>
               </div>';
         colsyst('#col_LB');
      echo '
            </div>
         </div>
';
   break;
}

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