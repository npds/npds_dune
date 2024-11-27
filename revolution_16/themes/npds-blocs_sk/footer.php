<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-blocs_sk 2019 by jpb                                    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_princ col_LB +|| +|| col_RB                                */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst, $theme_darkness;
$moreclass = 'griditem col-12 col-md-6 col-lg-4 col-xl-3';
echo '
         </div>';
echo $pdst != -1 ? '
         <div class="col-12" id="allblocs" style="padding-top:3.8rem;">
            <div class="text-end" >
               <a class="btn btn-primary btn-sm rounded-circle" href="#col_princ"><i class="fa fa-angle-up fa-lg"></i></a>
            </div>
            <div id="col_LB" class="row g-3" style="padding-top:2.8rem;" data-masonry=\'{"columnWidth": 0, "itemSelector": ".griditem" }\'>': '';

switch ($pdst) {
   case '0':
      leftblocks($moreclass);
      echo '
            </div>
         </div>';
   break;
   case '-1':
      echo '';
   break;
   case '1':
      leftblocks($moreclass);
      rightblocks($moreclass);
      echo '
            </div>
         </div>';
   break;
   case '2':
      rightblocks($moreclass);
      echo '
            </div>
         </div>';
   break;
   default:
      leftblocks($moreclass);
      rightblocks($moreclass);
      echo '
            </div>
         </div>';
   break;
}

echo '
      </div>
   </div>
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
echo '
      <script type="text/javascript" src="lib/js/masonry.pkgd.min.js"></script>';
?>