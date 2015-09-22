<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    WS-PAD Language File Copyright (c) 2012 by Developpeur            */
/*                                                                      */
/************************************************************************/

function wspad_trans($phrase) {
 if (cur_charset=="utf-8") {
    return utf8_encode($phrase);
 } else {
    return ($phrase);
 }
}
?>