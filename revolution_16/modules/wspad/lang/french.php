<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/*    WS-PAD Language File Copyright (c) 2013-2024 by Developpeur       */
/*                                                                      */
/************************************************************************/

function wspad_trans($phrase) {
   return (htmlentities($phrase,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>