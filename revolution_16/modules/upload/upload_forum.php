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
   else if($typeL=='themodal')
      echo '
      <div class="modal fade" id="'.$typeL.'" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">'.translate("Fichiers").'</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      
      <a class="me-3" href="#themodal" data-remote="modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli='.$apli.'&IdPost='.$IdPost.'&IdForum='.$IdForum.'&IdTopic='.$IdTopic.'" data-bs-toggle="modal" data-bs-target="#themodal" title="'.translate("Fichiers").'" data-bs-toggle="tooltip"><i class="fa fa-download fa-lg"></i></a>

      
      <script type="text/javascript">
      //<![CDATA[
         $("#'.$typeL.'").on("show.bs.modal", function (e) {
             var button = $(e.relatedTarget);
             var modal = $(this);
             modal.find(".modal-body").load(button.data("remote"));
         });
      //]]>
      </script>';
   else
      return ("'modules.php?ModPath=upload&ModStart=include_forum/upload_forum2&apli=$apli&IdPost=$IdPost&IdForum=$IdForum&IdTopic=$IdTopic','wtmpForum', 'menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes,resizable=yes, width=640, height=480'");
}
?>