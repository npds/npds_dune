<?php
/************************************************************************/
/* Theme for NPDS / Net Portal Dynamic System                           */
/*======================================================================*/
/* This theme use the NPDS theme-dynamic engine (Meta-Lang)             */
/*                                                                      */
/* Theme : jpb Jireck Bmag     npds-roc2016                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

global $pdst;
switch ($pdst)
{
case "-1":
case "0":
case "3":
case "5":
    echo "</section>";/* Fermeture de Col_princ ouvert dans le header.php */
    echo "</section>";/* Fermeture du corps ouvert dans le header.php  */
break;

case "1":
case "2":
    echo "</section>";/* Fermeture de Col_princ ouvert dans le header.php */
    echo '<aside  class="col w18 notablet nomobile  aside">';
        rightblocks();
    echo "</aside>";
    echo "</section>";/* Fermeture du corps ouvert dans le header.php  */
break;

case "4":
    echo "</section>";/* Fermeture de Col_princ ouvert dans le header.php */
    echo '<aside  class="col w18 notablet nomobile  aside">';
        leftblocks();
    echo "</aside>";
    echo '<aside  class="col w18 notablet nomobile  aside">';
        rightblocks();
    echo "</aside>";
    echo "</section>";/* Fermeture du corps ouvert dans le header.php  */
break;

case "6":
    echo "</section>";/* Fermeture de Col_princ ouvert dans le header.php */
    echo '<aside  class="col w18 notablet nomobile aside">';
        leftblocks();
    echo "</aside>";
    echo "</section>";/* Fermeture du corps ouvert dans le header.php  */
break;
default :
    echo "</section>";/* Fermeture de Col_princ ouvert dans le header.php */
    echo "</section>";/* Fermeture du corps ouvert dans le header.php  */
break;

}
// ContainerGlobal permet de transmettre à Theme-Dynbamic un élément de personnalisation après
// le chargement de footer.html / Si vide alors rien de plus n'est affiché par TD
$ContainerGlobal="\n</div>\n";

// Ne supprimez pas cette ligne / Don't remove this line
  require_once("themes/themes-dynamic/footer.php");
// Ne supprimez pas cette ligne / Don't remove this line
?>