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
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $pdst;
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
           leftblocks();
           rightblocks();
      echo '
       </div>
    </div>';
   break;
   default :
      echo '
    </div>
       <div id="col_RB" class="col-md-3 col-sm-12">';
           leftblocks();
           rightblocks();
      echo '
       </div>
    </div>';
   break;
}
// ContainerGlobal permet de transmettre à Theme-Dynamic un élément de personnalisation après
// le chargement de footer.html / Si vide alors rien de plus n'est affiché par TD
$ContainerGlobal='
   </div>';

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>