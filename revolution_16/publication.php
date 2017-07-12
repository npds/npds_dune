<?php
/************************************************************************/
/* NPDS V : Net Portal Dynamic System .                                 */
/* ===========================                                          */
/*                                                                      */
/* Sur une idée originale de PSTL                                       */
/*                                                                      */
/* This version name NPDS Copyright (c) 2001-2017 by Philippe Brunier   */
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

function publication ($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur) {
   global $gmt;
   echo '<hr />';
/*
   $today = getdate(time()+((integer)$gmt*3600));
   if (!$deb_day) {
      $deb_day = $today['mday'];
      if ($deb_day < 10)
         $deb_day = '0'.$deb_day;
   }
   if (!$deb_month) {
      $deb_month = $today['mon'];
      if ($deb_month < 10)
         $deb_month = '0'.$deb_month;
   }
   if (!$deb_year) 
      $deb_year = $today['year'];
   if (!$deb_hour) {
      $deb_hour = $today['hours'];
      if ($deb_hour < 10)
         $deb_hour = '0'.$deb_hour;
   }
   if (!$deb_min) {
      $deb_min = $today['minutes'];
      if ($deb_min < 10)
         $deb_min = '0'.$deb_min;
   }
*/
   
   //==> new implementation datepub
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
   //<== new implementation datepub
   
   echo '
   <p class="small text-right">
   '.translate(date("l")).date(" ".translate("dateinternal"),time()+($gmt*3600)).'
   </p>';
   $day = 1;

   //==> new implementation datepub
   if($dd_pub!=-1 and $dh_pub!=-1)
      echo '
   <div class="form-group row">
      <label class="form-control-label col-sm-4">'.translate("Start Date for this New").'</label>
      <div class="input-group input-daterange col-sm-8">
         <div class="input-group-addon"><i class="fa fa-calendar-o fa-lg"></i></div>
          <input type="text" class="form-control" id="dd_pub" name="dd_pub" value="'.$dd_pub.'" />
          <div class="input-group-addon"><i class="fa fa-clock-o fa-lg"></i></div>
          <input type="text" class="form-control" placeholder="Heure" id="dh_pub" name="dh_pub" value="'.$dh_pub.'" />
      </div>
   </div>';
   echo '
   <div class="form-group row">
      <label class="form-control-label col-sm-4">'.translate("End Date for this New").'</label>
      <div class="input-group input-daterange col-sm-8">
         <div class="input-group-addon"><i class="fa fa-calendar-o fa-lg"></i></div>
          <input type="text" class="form-control" id="fd_pub" name="fd_pub" value="'.$fd_pub.'"/>
          <div class="input-group-addon"><i class="fa fa-clock-o fa-lg"></i></div>
          <input type="text" class="form-control" placeholder="Heure" id="fh_pub" name="fh_pub" value="'.$fh_pub.'" />
      </div>
   </div>
   <script type="text/javascript">
   //<![CDATA[
      $(document).ready(function() {
         $("#dd_pub,#fd_pub").datepicker({ 
               format: "yyyy-mm-dd",
               autoclose: "true",
               language:"'.language_iso(1,'','').'"
         })
         $("#dh_pub,#fh_pub").clockpicker({
            placement: "bottom",
            align: "right",
            autoclose: "true"
         });
      });
   //]]>
   </script>';
   //<== new implementation datepub

/*
   if ($deb_day!=-1 and $deb_month!=-1 and $deb_year!=-1 and $deb_hour!=-1 and $deb_min!=-1) {
      echo '
   <label class="form-control-label">'.translate("Start Date for this New").'</label>
   <div class="form-group row">
      <div class="col-sm-2">
         <label class="form-control-label" for="deb_day">'.translate("Day").'</label>
         <select class="custom-select form-control" name="deb_day">';
      while ($day <= 31) {
         if ($deb_day==$day) {
            $sel = 'selected="selected"';
         } else {
            $sel = '';
         }
         echo '
         <option '.$sel.'>'.$day.'</option>';
         $day++;
      }
      echo '
         </select>
     </div>';
        $month = 1;
      echo '
     <div class="col-sm-2">
        <label class="form-control-label" for="deb_month">'.translate("Month").'</label>
           <select class="custom-select form-control" name="deb_month">';
      while ($month <= 12) {
         if ($deb_month==$month) {
            $sel = 'selected="selected"';
         } else {
            $sel = "";
         }
         echo '
         <option '.$sel.'>'.$month.'</option>';
         $month++;
      }
      echo '
           </select>
     </div>
     <div class="col-sm-2">
        <label class="form-control-label" for="deb_year">'.translate("Year").'</label>
        <input class="form-control" type="text" name="deb_year" value="'.$deb_year.'" />
     </div>
     <div class="col-sm-2">
        <label class="form-control-label" for="deb_hour">'.translate("Hour(s)").'</label>
        <select class="custom-select form-control" name="deb_hour">';
        $hour = 0;
      while ($hour <= 23) {
         if ($hour < 10) {
            $hour = "0$hour";
         }
         if ($deb_hour==$hour) {
            $sel="selected=\"selected\"";
         } else {
            $sel="";
         }
         echo "<option $sel>$hour</option>";
         $hour++;
      }
      echo '
        </select>
      </div>
      <div class="col-sm-2">
         <label class="form-control-label" for="deb_min">'.translate("Minut(s)").'</label>
         <select class="custom-select form-control" name="deb_min">';
        $min = 0;
      while ($min <= 59) {
         if ($min < 10) {
            $min = "0$min";
         }
         if ($deb_min==$min) {
            $sel='selected="selected"';
         } else {
            $sel="";
         }
         echo '
         <option '.$sel.'>'.$min.'</option>';
         $min++;
      }
      echo '
         </select>
      </div>
      <div class="col-sm-2"></div>
   </div>';
   }
      $day = 1;
*/
/*   echo '
      <label class="form-control-label">'.translate("End Date for this New").'</label>
      <div class="form-group row">
         <div class="col-sm-2">
            <label class="form-control-label" for="fin_day">'.translate("Day").'</label>
            <select class="custom-select form-control" id="fin_day" name="fin_day">';
      while ($day <= 31) {
         if ($fin_day==$day) $sel='selected="selected"';
         else $sel='';
         echo '
               <option '.$sel.'>'.$day.'</option>';
         $day++;
      }
   echo '
            </select>
         </div>';
      $month = 1;
   echo '
         <div class="col-sm-2">
            <label class="form-control-label" for="fin_month">'.translate("Month").'</label>
            <select class="custom-select form-control" name="fin_month">';
      while ($month <= 12) {
         if ($fin_month==$month) $sel='selected="selected"';
         else $sel='';
         echo '
               <option '.$sel.'>'.$month.'</option>';
         $month++;
      }
   echo '
            </select>
         </div>
         <div class="col-sm-2">';
      if (!$fin_year) $fin_year=$deb_year+99;
   echo '
            <label class="form-control-label" for="fin_year">'.translate("Year").'</label>
            <input class="form-control" type="text" name="fin_year" value="'.$fin_year.'" />
         </div>
         <div class="col-sm-2">
            <label class="form-control-label" for="fin_hour">'.translate("Hour(s)").'</label>
            <select class="custom-select form-control" name="fin_hour">';
      $hour = 0;
      while ($hour <= 23) {
         if ($hour < 10) $hour = '0'.$hour;
         if ($fin_hour==$hour) $sel='selected="selected"';
         else $sel='';
         echo '
               <option '.$sel.'>'.$hour.'</option>';
         $hour++;
      }
   echo '
            </select>
         </div>
         <div class="col-sm-2">
            <label class="form-control-label" for="fin_min">'.translate("Minut(s)").'</label>
            <select class="custom-select form-control" name="fin_min">';
      $min = 0;
      while ($min <= 59) {
         if ($min <10) $min = "0$min";
         if ($fin_min==$min) $sel='selected="selected"';
         else $sel='';
         echo '
               <option '.$sel.'>'.$min.'</option>';
         $min = $min + 5;
      }
   echo '
            </select>
         </div>
         <div class="col-sm-2"></div>
      </div>';
*/
   echo '
      <div class="form-group row">
         <div class="col-md-6">
               <label class="form-control-label">'.translate("Auto Delete the New at End Date").' ?</label>
               </div>';
         $sel1='';
         $sel2='';
         if (!$epur) $sel2='checked="checked"';
         else $sel1='checked="checked"';
         echo '
         <div class="col-md-6">
            <div class="">
               <label class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" name="epur" value="1" '.$sel1.' />
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.translate("Yes").'</span>
               </label>
               <label class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" name="epur" value="0" '.$sel2.' />
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">'.translate("No").'</span>
               </label>
            </div>
         </div>
      </div>';
}
?>