<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
$userdatat=$userdata;
//$messageP=split_string_without_space($message, 80);
$messageP=$message;
$time=date(translate("dateinternal"),time()+($gmt*3600));

switch ($acc) {
   case "newtopic":
      $forum_type=$myrow['forum_type'];
      if ($forum_type==8) {
         $formulaire=$myrow['forum_pass'];
         include ("modules/sform/forum/forum_extender.php");
      }
      if ($allow_html == 0 || isset($html))
         $messageP = htmlspecialchars($messageP,ENT_COMPAT|ENT_HTML401,cur_charset);

      if (isset($sig) && $userdata['0'] != 1 && $myrow['forum_type']!=6 && $myrow['forum_type']!=5) {
         $messageP .= " [addsig]";
      }
      if (($forum_type!=6) and ($forum_type!=5)) {
         $messageP = aff_code($messageP);
         $messageP = str_replace("\n", '<br />', $messageP);
      }
      if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
         $messageP = smile($messageP);
      }
      if (($forum_type!=6) and ($forum_type!=5)) {
         $messageP = make_clickable($messageP);
         $messageP = removeHack($messageP);
         if ($allow_bbcode) $messageP = aff_video_yt($messageP);
      }
      if (!isset($Mmod)) {
         $subject = removeHack(strip_tags($subject));
      }
      $subject = htmlspecialchars($subject,ENT_COMPAT|ENT_HTML401,cur_charset);
   break;

   case "reply":
      if (array_key_exists(1,$userdata))// why make an error ? car le tableau est déclaré après le array key exist ...
         $userdata = get_userdata($userdata[1]);
      if ($allow_html == 0 || isset($html)) $messageP = htmlspecialchars($messageP,ENT_COMPAT|ENT_HTML401,cur_charset);
      if (isset($sig) && $userdata['uid'] != 1) $messageP .= " [addsig]";
      if (($forum_type!="6") and ($forum_type!="5")) {
         $messageP = aff_code($messageP);
         $messageP = str_replace("\n", "<br />", $messageP);
      }
      if (($allow_bbcode) and ($forum_type!="6") and ($forum_type!="5")) {
         $messageP = smile($messageP);
      }
      if (($forum_type!=6) and ($forum_type!=5)){
         $messageP = make_clickable($messageP);
         $messageP = removeHack($messageP);
         if ($allow_bbcode) $messageP = aff_video_yt($messageP);
      }
      $messageP = addslashes($messageP);
   break;

   case 'editpost' :
      $userdata = get_userdata($userdata[1]);
      settype($post_id,"integer");
      $sql = "SELECT poster_id, topic_id FROM ".$NPDS_Prefix."posts WHERE (post_id = '$post_id')";
      $result = sql_query($sql);
      if (!$result) {
         forumerror('0022');
      }
      $row2 = sql_fetch_assoc($result);

      $userdata['uid'] = $row2['poster_id'];
      // IF we made it this far we are allowed to edit this message
      settype($forum,"integer");
      $myrow2 = sql_fetch_assoc(sql_query("SELECT forum_type FROM ".$NPDS_Prefix."forums WHERE (forum_id = '$forum')"));
      $forum_type = $myrow2['forum_type'];

      if ($allow_html == 0 || isset($html)) {
         $messageP = htmlspecialchars($messageP,ENT_COMPAT|ENT_HTML401,cur_charset);
      }
      if (($allow_bbcode) and ($forum_type!=6) and ($forum_type!=5)) {
         $messageP = smile($messageP);
      }
      if (($forum_type!=6) and ($forum_type!=5)) {
         $messageP = aff_code($messageP);
         $messageP = str_replace("\n", '<br />', removeHack($messageP));
         $messageP .= "<br /><p class=\"lignb\">".translate("This message was edited by").' : '.$userdata['uname']."</p>";
         if ($allow_bbcode) $messageP = aff_video_yt($messageP);
      } else {
         $messageP .= "\n\n".translate("This message was edited by").' : '.$userdata['uname'];
      }
      $messageP = addslashes($messageP);
   break;
}
         
      $theposterdata = get_userdata_from_id($userdatat[0]);
      echo '
      <h4>'.translate("Preview").'</h4>
      <div class="row">
         <div class="col-xs-12">
            <div class="card">
               <div class="card-header">';
         if ($smilies) {
            if ($theposterdata['user_avatar'] != '') {
               if (stristr($theposterdata['user_avatar'],"users_private")) {
                  $imgtmp=$theposterdata['user_avatar'];
               } else {
                  if ($ibid=theme_image("forum/avatar/".$theposterdata['user_avatar'])) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/".$theposterdata['user_avatar'];}
               }
                echo '
                   <a style="position:absolute; top:1rem;" tabindex="0" data-toggle="popover" data-html="true" data-title="'.$theposterdata['uname'].'" data-content=\''.member_qualif($theposterdata['uname'], $theposterdata['posts'],$theposterdata['rank']).'\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="'.$imgtmp.'" alt="'.$theposterdata['uname'].'" /></a>';
            }
         }
               echo'
                  &nbsp;<span style="position:absolute; left:6rem;" class="text-muted"><strong>'.$userdatat[1].'</strong></span>
                  <span class="float-xs-right">';
//      if (($forum_type=='6') or ($forum_type=='5'))
      if (isset($image_subject)) {
         if ($ibid=theme_image("forum/subject/$image_subject")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/subject/$image_subject";}
         echo '<img class="n-smil" src="'.$imgtmp.'" alt="" />';
      } else {
         if ($ibid=theme_image("forum/icons/posticon.gif")) {$imgtmpP=$ibid;} else {$imgtmpP="images/forum/icons/posticon.gif";}
         echo '<img class="n-smil" src="'.$imgtmpP.'" alt="" />';
      }
      echo '</span>
      </div>
      <div class="card-block">
         <span class="text-muted float-xs-right small" style="margin-top:-1rem;">'.translate("Posted: ").$time.'</span>
         <div id="post_preview" class="card-text pt-2">';

      $messageP=stripslashes($messageP);
      if (($forum_type=='6') or ($forum_type=='5')) {
         highlight_string(stripslashes($messageP));
      } else {
         if ($allow_bbcode) $messageP=smilie($messageP);
         $messageP=str_replace('[addsig]', '<div class="n-signature">'.nl2br($theposterdata['user_sig']).'</div>', $messageP);
         echo $messageP.'
         </div>';
      }
      echo '
            </div>
         </div>
      </div>
   </div>';

if ($acc=='reply'||$acc=='editpost')
   $userdata=$userdatat;
?>