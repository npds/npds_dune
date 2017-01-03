<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Ce fichier permet de demander à SuperCache de procéder à une modif   */
/* sur les pages dont il assure le cache. ce traitement peut opérer     */
/* des modifications dans le résultat HTML et doit agir sur             */
/* la variable $output                                                  */
/*                                                                      */
/* par exemple : $output=preg_replace(' class="noir"', "", $output)     */
/************************************************************************/
?>
