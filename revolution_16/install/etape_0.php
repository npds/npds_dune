<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2017                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],'install.php')) die();

function etape_0() {
   global $stage;
   $stage = 0;
   echo '
         <div class="row">
            <div class="col-sm-3"><img class="img-fluid " src="install/images/carte_monde.png" alt="carte_monde" /></div>
            <div class="col-sm-9">
            <div class="col-sm-12 mt-3">
               <form name="langue" method="post" action="install.php">
                  <div class="mb-3 row">
                     <div class="form-check">
                        <div class="form-check">
                           <input class="form-check-input" type="radio" id="langue_fr" name="langue" value="french" checked="checked" />
                           <label class="form-check-label" for="langue_fr">Fran&#231;ais</label>
                        </div>
                     </div>
                     <div class="form-check">
                        <div class="form-check">
                           <input class="form-check-input" type="radio" id="langue_en" name="langue" value="english" />
                           <label class="form-check-label" for="langue_en">English</label>
                        </div>
                     </div>
                     <div class="form-check">
                        <div class="form-check">
                           <input class="form-check-input" type="radio" id="langue_de" name="langue" value="german" />
                           <label class="form-check-label" for="langue_de">Deutsch</label>
                        </div>
                     </div>
                     <div class="form-check">
                        <div class="form-check">
                           <input class="form-check-input" type="radio" id="langue_sp" name="langue" value="spanish" />
                           <label class="form-check-label" for="langue_sp">Espa&#241;ol</label>
                        </div>
                     </div>
                  </div>
                  <div class="mb-3">
                     <input type="hidden" name="stage" value="1" />
                     <button type="submit" class="btn btn-success">Ok</button>
                  </div>
               </form>
            </div>
         </div>';
}
?>