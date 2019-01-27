<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : MyBlogNPDS 2017 by Bmag                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_princ + col_LB +|| col_RB                                  */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst;
switch ($pdst)
{
   case '-1':
      echo '
            </div>
         </div>
      </div>';
   break;
   default:
      echo '
      </div>
      <div id="col_LB" class="collapse show col-lg-3">';
         leftblocks();
         rightblocks();
      echo '
            </div>
         </div>
      </div>';
      echo colsyst('#col_LB');
   break;
}

// ContainerGlobal permet de transmettre · Theme-Dynamic un élément de personnalisation après
// le chargement de footer.html / Si vide alors rien de plus n'est affiché par TD
$ContainerGlobal='
</div>';

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>