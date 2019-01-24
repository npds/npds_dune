<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : jpb Jireck Bmag     NBOR                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $pdst;
switch ($pdst)
{
case '-1':case '3':case '5':
   echo '
         </div>
      </div>
   </div>';
break;

case '1':case '2':
   echo '
   </div>
    <div id="col_RB" class="n-c col-lg-3 w18">';
        rightblocks();
   echo '
         </div>
      </div>
   </div>';
break;

case "4":
   echo '
         </div>
         <div id="col_LB" class="n-c col-lg-3 w18">';
            leftblocks();
   echo '
         </div>
         <div id="col_RB"  class="n-c col-lg-3 w18">';
        rightblocks();
   echo '
         </div>
      </div>
   </div>';
break;

case '6':
   echo '
         </div>
         <div id="col_LB" class="n-c col-lg-3 w18">';
            leftblocks();
    echo '
         </div>
      </div>
   </div>';
break;
default :
   echo '
         </div>
      </div>
   </div>';
break;

}
$ContainerGlobal='</div>';

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>