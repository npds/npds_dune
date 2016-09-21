<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : npds-boost 2015 by jpb                                       */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_LB +|| col_princ +|| col_RB                                */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst;
switch ($pdst)
{
   case "-1":
      echo '</div>'."\n".'</div>'."\n".'</div>'."\n";
   break;
   case "2":
      echo '
      </div>
         <div id="col_LB" class="col-md-3">';
            leftblocks();
            rightblocks();
      echo '
            </div>
         </div>
      </div>';
   break;
   default:
      echo '</div>'."\n".'</div>'."\n".'</div>'."\n";
   break;
}

// ContainerGlobal permet de transmettre · Theme-Dynbamic un ÈlÈment de personnalisation aprËs
// le chargement de footer.html / Si vide alors rien de plus n'est affichÈ par TD
$ContainerGlobal="\n</div>\n";

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>