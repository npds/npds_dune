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
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/************************************************************************/
/* Fermeture ou ouverture et fermeture according with $pdst :           */
/*       col_princ col_LB +|| +|| col_RB                                */
/* Fermeture : div > div"#corps"> $ContainerGlobal>                     */
/*                    ouverts dans le Header.php                        */
/* =====================================================================*/ 
global $pdst;
switch ($pdst) {
   case '0':
      echo '
         </div>
         <div class="col-12" id="allblocs" style="padding-top:3.8rem;">
            <div class="text-right" style="position:absolute;right:1rem;">
               <a class="btn btn-outline-primary btn-sm rounded-circle" href="#col_princ"><i class="fa fa-angle-up fa-lg"></i></a>
            </div>
            <div id="col_LB" class="card-columns px-3" style="padding-top:2.8rem;">';
      leftblocks();
      echo '
            </div>
         </div>
      </div>
   </div>
</div>';
   break;
   case '-1':
      echo '
         </div>
      </div>
   </div>
</div>';
   break;
   case '1':
      echo '
         </div>
         <div id="allblocs" style="padding-top:3.8rem;">
            <div class="text-right" style="position:absolute;right:1rem;">
               <a class="btn btn-outline-primary btn-sm rounded-circle" href="#col_princ"><i class="fa fa-angle-up fa-lg"></i></a>
            </div>
            <div id="col_LB" class="card-columns px-3" style="padding-top:2.8rem;">';
         leftblocks();
         rightblocks();
         echo '
            </div>
         </div>
      </div>
   </div>
</div>';
   break;
   case '2':
      echo '
         </div>
         <div id="allblocs" style="padding-top:3.8rem;">
            <div class="text-right" style="position:absolute;right:1rem;">
               <a class="btn btn-outline-primary btn-sm rounded-circle" href="#col_princ"><i class="fa fa-angle-up fa-lg"></i></a>
            </div>
            <div id="col_LB" class="card-columns px-3" style="padding-top:2.8rem;">';
         rightblocks();
         echo '
            </div>
         </div>
      </div>
   </div>
</div>';
   break;
   default:
      echo '
         </div>
         <div id="allblocs" style="padding-top:3.8rem;">
            <div class="text-right" style="position:absolute;right:1rem;">
               <a class="btn btn-outline-primary btn-sm rounded-circle" href="#col_princ"><i class="fa fa-angle-up fa-lg"></i></a>
            </div>
            <div id="col_LB" class="card-columns px-3" style="padding-top:2.8rem;">';
         leftblocks();
         rightblocks();
         echo '
            </div>
         </div>
      </div>
   </div>
</div>';
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