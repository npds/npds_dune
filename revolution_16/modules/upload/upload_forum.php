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

function win_upload($apli,$IdPost,$IdForum,$IdTopic,$typeL) {
   if ($typeL=='win')
      echo "
      <script type=\"text/javascript\">
      //<![CDATA[
      window.open('modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli=$apli&IdPost=$IdPost&IdForum=$IdForum&IdTopic=$IdTopic','wtmpForum', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=640, height=480');
      //]]>
      </script>";
   else
      return ("'modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli=$apli&IdPost=$IdPost&IdForum=$IdForum&IdTopic=$IdTopic','wtmpForum', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=640, height=480'");
}
?>