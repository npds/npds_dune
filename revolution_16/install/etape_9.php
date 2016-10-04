<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2016 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"install.php")) { die(); }

function etape_9() {
   global $langue, $stage, $izx;
   $stage = 9;
   echo '
      <h3>'.ins_translate('Fin').'</h3>
         <p>'.ins_translate('Félicitations, vous avez à présent votre portail NPDS.').'</p>
         <form name="ended" method="post" action="install.php">
            <input type="hidden" name="langue" value="'.$langue.'" />
            <input type="hidden" name="stage" value="'.$stage.'" />
            <input type="hidden" name="op" value="write_ok" />
            <button type="submit" class="btn btn-success"><i class="fa fa-lg fa-check"></i>'.ins_translate(" Quitter ").'</button>
         </form>
      </div>';
}
?>