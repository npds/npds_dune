<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2010 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/* Ce fichier permet de demander � SuperCache de proc�der � une modif   */
/* sur les pages dont il assure le cache. ce traitement peut op�rer     */
/* des modifications dans le r�sultat HTML et doit agir sur             */
/* la variable $output                                                  */
/*                                                                      */
/* par exemple : $output=preg_replace(' class="noir"', "", $output)     */
/************************************************************************/
?>

