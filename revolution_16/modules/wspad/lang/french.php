<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    WS-PAD Language File Copyright (c) 2015 by Developpeur            */
/*                                                                      */
/************************************************************************/

function wspad_trans($phrase) {
//  if (cur_charset=="utf-8") {
//     return utf8_encode($phrase);
//  } else {
//     return ($phrase);
//  }
 
   return (htmlentities($phrase,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401));

}
?>