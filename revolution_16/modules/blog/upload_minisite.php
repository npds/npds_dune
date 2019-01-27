<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function win_upload($typeL) {
   if ($typeL=='win') {
      echo "
      <script type=\"text/javascript\">
      //<![CDATA[
         window.open('modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-ges','wtmpMinisite', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=780, height=500');
      //]]>
      </script>";
   }
   else
      return ("'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-ges','wtmpMinisite', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=780, height=500'");
}
?>