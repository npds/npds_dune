<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* Sur une idée originale de PSTL                                       */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2019 by Philippe Brunier   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function code_aff($subject, $story, $bodytext, $notes) {
    global $local_user_language;
    $subjectX=aff_code(preview_local_langue($local_user_language, $subject));
    $storyX=aff_code(preview_local_langue($local_user_language, $story));
    $bodytextX=aff_code(preview_local_langue($local_user_language, $bodytext));
    $notesX=aff_code(preview_local_langue($local_user_language, $notes));
    themepreview($subjectX, $storyX, $bodytextX, $notesX);
}

function publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur) {
   global $gmt;
   $today = getdate(time()+((integer)$gmt*3600));
   settype($dd_pub,'string');
   settype($fd_pub,'string');
   settype($dh_pub,'string');
   settype($fh_pub,'string');
   if (!$dd_pub) {
      $dd_pub .= $today['year'].'-';
      if($today['mon']< 10) $dd_pub.='0'.$today['mon'].'-'; else $dd_pub .= $today['mon'].'-';
      if($today['mday']< 10) $dd_pub.='0'.$today['mday']; else $dd_pub .= $today['mday'];
   }
   if (!$fd_pub) {
      $fd_pub .= ($today['year']+99).'-';
      if($today['mon']< 10) $fd_pub.='0'.$today['mon'].'-'; else $fd_pub .= $today['mon'].'-';
      if($today['mday']< 10) $fd_pub.='0'.$today['mday']; else $fd_pub .= $today['mday'];
   }
   if (!$dh_pub) {
      if($today['hours']< 10) $dh_pub.='0'.$today['hours'].':'; else $dh_pub .= $today['hours'].':';
      if($today['minutes']< 10) $dh_pub.='0'.$today['minutes']; else $dh_pub .= $today['minutes'];
   }
   if (!$fh_pub) {
      if($today['hours']< 10) $fh_pub.='0'.$today['hours'].':'; else $fh_pub .= $today['hours'].':';
      if($today['minutes']< 10) $fh_pub.='0'.$today['minutes']; else $fh_pub .= $today['minutes'];
   }
   echo '
   <hr />
   <p class="small text-right">
   '.translate(date("l")).date(" ".translate("dateinternal"),time()+((integer)$gmt*3600)).'
   </p>';

   if($dd_pub!=-1 and $dh_pub!=-1)
      echo '
   <div class="form-row">
      <label class="col-form-label col-sm-4">'.translate("Start Date for this New").'</label>
      <div class="col-sm-5 mb-3">
         <input type="text" class="form-control flatpi" id="dd_pub" name="dd_pub" value="'.$dd_pub.'" />
      </div>
      <div class="input-group col-sm-3 mb-3">
         <div class="input-group-prepend">
             <span class="input-group-text"><i class="far fa-clock fa-lg"></i></span>
         </div>
      <input type="text" class="form-control" placeholder="Heure" id="dh_pub" name="dh_pub" value="'.$dh_pub.'" />
      </div>
   </div>';
   echo '
   <div class="form-row">
      <label class="col-form-label col-sm-4">'.translate("End Date for this New").'</label>
      <div class="col-sm-5 mb-3">
         <input type="text" class="form-control flatpi" id="fd_pub" name="fd_pub" value="'.$fd_pub.'" />
      </div>
      <div class="input-group col-sm-3 mb-3">
         <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-clock fa-lg"></i></span>
         </div>
         <input type="text" class="form-control" placeholder="Heure" id="fh_pub" name="fh_pub" value="'.$fh_pub.'" />
      </div>
   </div>
   <script type="text/javascript" src="lib/flatpickr/dist/flatpickr.min.js"></script>
   <script type="text/javascript" src="lib/flatpickr/dist/l10n/'.language_iso(1,'','').'.js"></script>
   <script type="text/javascript" src="lib/js/bootstrap-clockpicker.min.js" async="async"></script>
   <script type="text/javascript">
   //<![CDATA[
      $(document).ready(function() {
         $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "lib/flatpickr/dist/themes/npds.css"});
         $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "lib/css/bootstrap-clockpicker.min.css"});
         $("#dh_pub,#fh_pub").clockpicker({
         placement: "bottom",
         align: "right",
         autoclose: "true"
         });
      })
      const fp = flatpickr(".flatpi", {
         altInput: true,
         altFormat: "l j F Y",
         dateFormat:"Y-m-d",
         "locale": "'.language_iso(1,'','').'",
      });
   //]]>
   </script>

   <div class="form-group row">
      <label class="col-form-label col-sm-4">'.translate("Auto Delete the New at End Date").'</label>';
      $sel1=''; $sel2='';
      if (!$epur) $sel2='checked="checked"';
      else $sel1='checked="checked"';
      echo '
      <div class="col-sm-8 my-2">
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="epur_y" name="epur" value="1" '.$sel1.' />
            <label class="custom-control-label" for="epur_y">'.translate("Yes").'</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
            <input class="custom-control-input" type="radio" id="epur_n" name="epur" value="0" '.$sel2.' />
            <label class="custom-control-label" for="epur_n">'.translate("No").'</label>
         </div>
      </div>
   </div>
   <hr />';
}
?>