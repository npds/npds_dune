<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    TD-Galerie Language File Copyright (c) 2004 by Tribal-Dolphin     */
/*                                                                      */
/************************************************************************/

function gal_trans($phrase) {
   if (cur_charset=="utf-8") {
    return utf8_encode($phrase);
 } else {
    return ($phrase);
 }  
}
?>