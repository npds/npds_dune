<?php
/************************************************************************/
/* Module de gestion de galeries pour NPDS                              */
/* ===========================                                          */
/*                                                                      */
/* TD-Galerie Language File Copyright (c) 2004-2005 by Tribal-Dolphin   */
/*                                                                      */
/************************************************************************/
function adm_gal_trans($phrase) {
   if (cur_charset=="utf-8") {
    return utf8_encode($phrase);
 } else {
    return ($phrase);
 }  
}
?>