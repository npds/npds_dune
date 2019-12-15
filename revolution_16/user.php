<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
function message_error($ibid,$op) {
   include("header.php");
   echo '
   <h2>'.translate("User").'</h2>
   <div class="alert alert-danger lead">';
   echo $ibid;
   if (($op=='only_newuser') or ($op=='new user') or ($op=='finish')) {
       hidden_form();
      echo '
         <input type="hidden" name="op" value="only_newuser" />
         <button class="btn btn-secondary mt-2" type="submit">'.translate("Go Back").'</button>
      </form>';
   } else
      echo '<a class="btn btn-secondary mt-4" href="javascript:history.go(-1)" title="'.translate("Go Back").'">'.translate("Go Back").'</a>';
   echo '
   </div>';
   include("footer.php");
}
function message_pass($ibid) {
   include("header.php");
   echo $ibid;
   include("footer.php");
}
function nav($mns) {
   global $op;
   $ed_u='';$ed_j='';$ed_h='';$ch_t='';
   if($op=='edituser') $ed_u='active';
   if($op=='editjournal') $ed_j='active';
   if($op=='edithome') $ed_h='active';
   if($op=='chgtheme') $ch_t='active';

   echo '
   <ul class="nav nav-tabs d-flex flex-wrap"> 
      <li class="nav-item"><a class="nav-link '.$ed_u.'" href="user.php?op=edituser" title="'.translate("Edit User").'" data-toggle="tooltip" ><i class="fas fa-user fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Edit User").'</span></a></li>
      <li class="nav-item"><a class="nav-link '.$ed_j.' " href="user.php?op=editjournal" title="'.translate("Edit Journal").'" data-toggle="tooltip"><i class="fas fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Journal").'</span></a></li>';
   include ("modules/upload/upload.conf.php");
   if (($mns) and ($autorise_upload_p)) {
      include ("modules/blog/upload_minisite.php");
      $PopUp=win_upload("popup");
      echo '
      <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="window.open('.$PopUp.')" title="'.translate("Manage my Mini-Web site").'"  data-toggle="tooltip"><i class="fas fa-desktop fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Mini-Web site").'</span></a></li>';
   }
   echo '
      <li class="nav-item"><a class="nav-link '.$ed_h.'" href="user.php?op=edithome" title="'.translate("Change the home").'" data-toggle="tooltip" ><i class="fas fa-edit fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Page").'</span></a></li>
      <li class="nav-item"><a class="nav-link '.$ch_t.'" href="user.php?op=chgtheme" title="'.translate("Change Theme").'"  data-toggle="tooltip" ><i class="fas fa-paint-brush fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Theme").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="'.translate("Social networks").'"  data-toggle="tooltip" ><i class="fas fa-share-alt-square fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Social networks").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="viewpmsg.php" title="'.translate("Private Message").'"  data-toggle="tooltip" ><i class="far fa-envelope fa-2x d-xl-none"></i><span class="d-none d-xl-inline">&nbsp;'.translate("Message").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="'.translate("Logout").'" data-toggle="tooltip" ><i class="fas fa-sign-out-alt fa-2x text-danger d-xl-none"></i><span class="d-none d-xl-inline text-danger">&nbsp;'.translate("Logout").'</span></a></li>
   </ul>
   <div class="mt-3"></div>';
}

function userCheck($uname, $email) {
   global $NPDS_Prefix;
   include_once('functions.php');
   $stop='';
   if ((!$email) || ($email=='') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email)))
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Invalid email");
   if (strrpos($email,' ') > 0)
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Email addresses do not contain spaces.");
   if(checkdnsmail($email) === false)
      $stop = translate("ERROR: wrong DNS or mail server") .'!<br />';
   if ((!$uname) || ($uname=='') || (preg_match('#[^a-zA-Z0-9_-]#',$uname))) 
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Invalid Nickname");
   if (strlen($uname) > 25)
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("Nickname is too long. It must be less than 25 characters.");
   if (preg_match('#^(root|adm|linux|webmaster|admin|god|administrator|administrador|nobody|anonymous|anonimo|an€nimo|operator|dune|netadm)$#i', $uname))
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Name is reserved.");
   if (strrpos($uname,' ') > 0)
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("There cannot be any spaces in the Nickname.");
   if (sql_num_rows(sql_query("SELECT uname FROM ".$NPDS_Prefix."users WHERE uname='$uname'")) > 0) {
      $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Nickname taken");
   }
   if ($uname!='edituser') {
      if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE email='$email'")) > 0) {
         $stop = '<i class="fa fa-exclamation mr-2"></i>'.translate("ERROR: Email address already registered");
      }
   }
   return($stop);
}

function makePass() {
   $makepass='';
   $syllables='er,in,tia,dun,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,';
   $syllables.='mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se';
   $syllable_array=explode(',', $syllables);
   srand((double)microtime()*1000000);
   for ($count=1;$count<=4;$count++) {
      if (rand()%10 == 1)
         $makepass .= sprintf("%0.0f",(rand()%50)+1);
      else
         $makepass .= sprintf("%s",$syllable_array[rand()%62]);
   }
   return($makepass);
}

function showimage() {
   echo "
   <script type=\"text/javascript\">
   //<![CDATA[
   function showimage() {
   if (!document.images)
      return
      document.images.avatar.src=\n";
   if ($ibid=theme_image("forum/avatar/blank.gif")) {$imgtmp=substr($ibid,0,strrpos($ibid,"/")+1);} else {$imgtmp="images/forum/avatar/";}
   echo "'$imgtmp' + document.Register.user_avatar.options[document.Register.user_avatar.selectedIndex].value\n";
   echo "}
   //]]>
   </script>";
}

function Only_NewUser() {
   global $user, $memberpass;
   if (!$user) {
      global $smilies, $short_user, $memberpass;
      global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1;
      include("header.php");
      showimage();
      echo '
   <div>
   <h2 class="mb-3">'.translate("User").'</h2>
   <div class="card card-body mb-3">
      <h3>'.translate("Notice").'</h3>
      <p>
      '.translate("Account preferences are cookie based.").' '.translate("We don't sell/give to others your personal info.").' '.translate("As a registered user you can").' : 
         <ul>
            <li>'.translate("Post comments with your name").'</li>
            <li>'.translate("Send news with your name").'</li>
            <li>'.translate("Have a personal box in the Home").'</li>
            <li>'.translate("Upload personal avatar").'</li>
            <li>'.translate("Select how many news you want in the Home").'</li>
            <li>'.translate("Customize the comments").'</li>
            <li>'.translate("Select different themes").'</li>
            <li>'.translate("some other cool stuff...").'</li>
         </ul>
      </p>';
      if (!$memberpass) {
         echo '
      <div class="alert alert-success lead"><i class="fa fa-exclamation mr-2"></i>'.translate("Password will be sent to the email address you enter.").'</div>';
      }
      echo '
   </div>
   <div class="card card-body mb-3">';
      include ("modules/sform/extend-user/extend-user.php");
      echo '
   </div>';
   adminfoot('fv',$fv_parametres,$arg1,'');
   } else
      header("location: user.php");
}
function hidden_form() {
   global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$charte,$user_lnl;
   if (!$user_avatar) {$user_avatar="blank.gif";}
   echo '
   <form action="user.php" method="post">
      <input type="hidden" name="uname" value="'.$uname.'" />
      <input type="hidden" name="name" value="'.removeHack($name).'" />
      <input type="hidden" name="email" value="'.$email.'" />
      <input type="hidden" name="user_avatar" value="'.$user_avatar.'" />
      <input type="hidden" name="user_from" value="'.StripSlashes(removeHack($user_from)).'" />
      <input type="hidden" name="user_occ" value="'.StripSlashes(removeHack($user_occ)).'" />
      <input type="hidden" name="user_intrest" value="'.StripSlashes(removeHack($user_intrest)).'" />
      <input type="hidden" name="user_sig" value="'.StripSlashes(removeHack($user_sig)).'" />
      <input type="hidden" name="user_viewemail" value="'.$user_viewemail.'" />
      <input type="hidden" name="pass" value="'.removeHack($pass).'" />
      <input type="hidden" name="user_lnl" value="'.removeHack($user_lnl).'" />
      <input type="hidden" name="C1" value="'.StripSlashes(removeHack($C1)).'" />
      <input type="hidden" name="C2" value="'.StripSlashes(removeHack($C2)).'" />
      <input type="hidden" name="C3" value="'.StripSlashes(removeHack($C3)).'" />
      <input type="hidden" name="C4" value="'.StripSlashes(removeHack($C4)).'" />
      <input type="hidden" name="C5" value="'.StripSlashes(removeHack($C5)).'" />
      <input type="hidden" name="C6" value="'.StripSlashes(removeHack($C6)).'" />
      <input type="hidden" name="C7" value="'.StripSlashes(removeHack($C7)).'" />
      <input type="hidden" name="C8" value="'.StripSlashes(removeHack($C8)).'" />
      <input type="hidden" name="M1" value="'.StripSlashes(removeHack($M1)).'" />
      <input type="hidden" name="M2" value="'.StripSlashes(removeHack($M2)).'" />
      <input type="hidden" name="T1" value="'.StripSlashes(removeHack($T1)).'" />
      <input type="hidden" name="T2" value="'.StripSlashes(removeHack($T2)).'" />
      <input type="hidden" name="B1" value="'.StripSlashes(removeHack($B1)).'" />';
}
function confirmNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass,$user_lnl,$C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1) {
   global $smilies, $short_user, $minpass, $memberpass;
   $uname=strip_tags($uname);
   if ($user_viewemail!=1) $user_viewemail='0';
   $stop=userCheck($uname, $email);
   if ($memberpass) {
      if ((isset($pass)) and ($pass != $vpass))
         $stop='<i class="fa fa-exclamation mr-2"></i>'.translate("Both passwords are different. They need to be identical.");
      elseif (strlen($pass) < $minpass)
         $stop='<i class="fa fa-exclamation mr-2"></i>'.translate("Sorry, your password must be at least").' <strong>'.$minpass.'</strong> '.translate("characters long");
   }
   if (!$stop) {
      include("header.php");
      echo '
      <h2>'.translate("User").'</h2>
      <hr />
      <h3 class="mb-3"><i class="fa fa-user mr-2"></i>Votre fiche d\'inscription</h3>
      <div class="card">
         <div class="card-body">';
      include ("modules/sform/extend-user/aff_extend-user.php");
      echo '
         </div>
      </div>';
      hidden_form();
      global $charte;
      if (!$charte)
         echo '
               <div class="alert alert-danger lead mt-3">
                  <i class="fa fa-exclamation mr-2"></i>'.translate("You must accept the terms of use of this website").'
               </div>
               <input type="hidden" name="op" value="only_newuser" />
               <input class="btn btn-secondary mt-1" type="submit" value="'.translate("Go Back").'" />
            </form>';
      else
         echo '
               <input type="hidden" name="op" value="finish" /><br />
               <input class="btn btn-primary mt-2" type="submit" value="'.translate("Finish").'" />
            </form>';
      include("footer.php");
   } else
      message_error($stop,"new user");
}

function finishNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass,$user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1) {
   global $NPDS_Prefix;
   global $makepass, $system, $adminmail, $sitename, $AutoRegUser, $memberpass, $gmt;
   $stop=userCheck($uname, $email);
   $user_regdate = time()+((integer)$gmt*3600);
   $stop=userCheck($uname, $email);
   if (!$stop) {
      include("header.php");
      if (!$memberpass)
         $makepass=makepass();
      else
         $makepass=$pass;
      if (!$system)
         $cryptpass=crypt($makepass,$makepass);
      else
         $cryptpass=$makepass;

      $result = sql_query("INSERT INTO ".$NPDS_Prefix."users VALUES (NULL,'$name','$uname','$email','','','$user_avatar','$user_regdate','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','','$cryptpass','10','','0','0','0','','0','','','10','0','0','1','0','','','$user_lnl')");
      list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$uname'"));
      $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
      if ($user_sig)
         $attach = 1;
      else
         $attach = 0;
      if (($AutoRegUser==1) or (!isset($AutoRegUser)))
         $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_status VALUES ('$usr_id','0','$attach','0','1','1','')");
      else
         $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_status VALUES ('$usr_id','0','$attach','0','1','0','')");
      if ($result) {
         if (($system==1) or ($memberpass)) {
            echo '
            <h2>'.translate("User").'</h2>
            <hr />
            <h2><i class="fa fa-user mr-2"></i>'.translate("Registration").'</h2>
            <p class="lead">'.translate("Your Password is: ").'<strong>'.$makepass.'</strong></p>
            <p class="lead">'.translate("You can change it after you login at").' : <br /><a href="user.php?op=login&amp;uname='.$uname.'&amp;pass='.$makepass.'"><i class="fas fa-sign-in-alt fa-lg mr-2"></i><strong>'.$sitename.'</strong></a></p>';

            $message = translate("Welcome to")." $sitename !\n\n".translate("You or someone else has used your email account")." ($email) ".translate("to register an account at")." $sitename.\n\n".translate("The following is the member information:")." : \n\n";
            $message .=
            translate("User ID").' : '.$uname."\n".
            translate("Real Email").' : '.$email."\n";
            if($name!='') 
               $message .= translate("Real Name").' : '.$name."\n";
            if($user_from!='') 
               $message .= translate("Your Location").' : '.$user_from."\n";
            if($user_occ!='') 
               $message .= translate("Your Occupation").' : '.$user_occ."\n";
            if($user_intrest!='') 
               $message .= translate("Your Interest").' : '.$user_intrest."\n";
            if($user_sig!='') 
               $message .= translate("Signature").' : '.$user_sig."\n";
            if(isset($C1) and $C1!='')
               $message .= aff_langue('[french]Activit&#x00E9; professionnelle[/french][english]Professional activity[/english][spanish]Actividad profesional[/spanish][german]Berufliche T&#xE4;tigkeit[/german]').' : '.$C1."\n";
            if(isset($C2) and $C2!='')
               $message .= aff_langue('[french]Code postal[/french][english]Postal code[/english][spanish]C&#xF3;digo postal[/spanish][german]Postleitzahl[/german]').' : '.$C2."\n";
            if(isset($T1) and $T1!='')
               $message .= aff_langue('[french]Date de naissance[/french][english]Birth date[/english][spanish]Fecha de nacimiento[/spanish][german]Geburtsdatum[/german]').' : '.$T1."\n";
            $message .= "\n\n\n".aff_langue("[french]Conform&eacute;ment aux articles 38 et suivants de la loi fran&ccedil;aise n&deg; 78-17 du 6 janvier 1978 relative &agrave; l'informatique, aux fichiers et aux libert&eacute;s, tout membre dispose d&rsquo; un droit d&rsquo;acc&egrave;s, peut obtenir communication, rectification et/ou suppression des informations le concernant.[/french][english]In accordance with Articles 38 et seq. Of the French law n &deg; 78-17 of January 6, 1978 relating to data processing, files and freedoms, any member has a right of access, can obtain communication, rectification and / or deletion of information about him.[/english][chinese]&#26681;&#25454;1978&#24180;1&#26376;6&#26085;&#20851;&#20110;&#25968;&#25454;&#22788;&#29702;&#65292;&#26723;&#26696;&#21644;&#33258;&#30001;&#30340;&#27861;&#22269;78-17&#21495;&#27861;&#24459;&#65292;&#20219;&#20309;&#25104;&#21592;&#37117;&#26377;&#26435;&#36827;&#20837;&#65292;&#21487;&#20197;&#33719;&#24471;&#36890;&#20449;&#65292;&#32416;&#27491;&#21644;/&#25110; &#21024;&#38500;&#26377;&#20851;&#20182;&#30340;&#20449;&#24687;&#12290;[/chinese][spanish]De conformidad con los art&iacute;culos 38 y siguientes de la ley francesa n &deg; 78-17 del 6 de enero de 1978, relativa al procesamiento de datos, archivos y libertades, cualquier miembro tiene derecho de acceso, puede obtener comunicaci&oacute;n, rectificaci&oacute;n y / o supresi&oacute;n de informaci&oacute;n sobre &eacute;l.[/spanish][german]Gem&auml;&szlig; den Artikeln 38 ff. Des franz&ouml;sischen Gesetzes Nr. 78-17 vom 6. Januar 1978 in Bezug auf Datenverarbeitung, Akten und Freiheiten hat jedes Mitglied ein Recht auf Zugang, kann Kommunikation, Berichtigung und / oder L&ouml;schung von Informationen &uuml;ber ihn.[/german]");
            $message .= "\n\n\n".aff_langue("[french]Ce message et les pi&egrave;ces jointes sont confidentiels et &eacute;tablis &agrave; l'attention exclusive de leur destinataire (aux adresses sp&eacute;cifiques auxquelles il a &eacute;t&eacute; adress&eacute;). Si vous n'&ecirc;tes pas le destinataire de ce message, vous devez imm&eacute;diatement en avertir l'exp&eacute;diteur et supprimer ce message et les pi&egrave;ces jointes de votre syst&egrave;me.[/french][english]This message and any attachments are confidential and intended to be received only by the addressee. If you are not the intended recipient, please notify immediately the sender by reply and delete the message and any attachments from your system.[/english][chinese]&#27492;&#28040;&#24687;&#21644;&#20219;&#20309;&#38468;&#20214;&#37117;&#26159;&#20445;&#23494;&#30340;&#65292;&#24182;&#19988;&#25171;&#31639;&#30001;&#25910;&#20214;&#20154;&#25509;&#25910;&#12290; &#22914;&#26524;&#24744;&#19981;&#26159;&#39044;&#26399;&#25910;&#20214;&#20154;&#65292;&#35831;&#31435;&#21363;&#36890;&#30693;&#21457;&#20214;&#20154;&#24182;&#22238;&#22797;&#37038;&#20214;&#21644;&#31995;&#32479;&#20013;&#30340;&#25152;&#26377;&#38468;&#20214;&#12290;[/chinese][spanish]Este mensaje y cualquier adjunto son confidenciales y est&aacute;n destinados a ser recibidos por el destinatario. Si no es el destinatario deseado, notif&iacute;quelo al remitente de inmediato y responda al mensaje y cualquier archivo adjunto de su sistema.[/spanish][german]Diese Nachricht und alle Anh&auml;nge sind vertraulich und sollen vom Empf&auml;nger empfangen werden. Wenn Sie nicht der beabsichtigte Empf&auml;nger sind, benachrichtigen Sie bitte sofort den Absender und antworten Sie auf die Nachricht und alle Anlagen von Ihrem System.[/german]")."\n\n\n";
            include ("signat.php");
            $subject= translate("Registration")." $uname";
            send_email($email, $subject, $message, '', true, 'html');
          } else {
             $message = translate("Welcome to")." $sitename !\n\n".translate("You or someone else has used your email account")." ($email) ".translate("to register an account at")." $sitename.\n\n".translate("The following is the member information:")."\n".translate("-Nickname: ")." $uname\n".translate("-Password: ")." $makepass\n\n";
             include ("signat.php");
             $subject= translate("User Password for")." $uname";
             send_email($email, $subject, $message, '', true, 'html');

            echo '
            <h2>'.translate("User").'</h2>
            <h2><i class="fa fa-user mr-2"></i>Inscription</h2>
            <div class="alert alert-success lead"><i class="fa fa-exclamation mr-2"></i>'.translate("You are now registered. You should receive your password at the email account you provided.").'</div>';
          }
          //------------------------------------------------
          if (file_exists("modules/include/new_user.inc")) {
             include ("modules/include/new_user.inc");
             global $gmt;
             $time = date(translate("dateinternal"),time()+((integer)$gmt*3600));
             $message = meta_lang(AddSlashes(str_replace("\n","<br />", $message)));
             $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
             $sql .= "VALUES ('', '$sujet', '$emetteur_id', '$usr_id', '$time', '$message')";
             sql_query($sql);
          }
          //------------------------------------------------
         send_email($adminmail,"Inscription sur $sitename","Infos :
            Nom : $name
            ID : $uname
            Email : $email", false,"text");
         }
         include("footer.php");
   } else
      message_error($stop, 'finish');
}

function userinfo($uname) {
   global $NPDS_Prefix;
   global $user, $sitename, $smilies, $short_user, $site_font;
   global $name, $email, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal;

   $uname=removeHack($uname);
   $result = sql_query("SELECT uid, name, femail, url, bio, user_avatar, user_from, user_occ, user_intrest, user_sig, user_journal, mns FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
   list($uid, $name, $femail, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal, $mns) = sql_fetch_row($result);
   if (!$uid)
      header ("location: index.php");
   global $cookie;
   include("header.php");
   include_once("functions.php");

   $email=removeHack($femail);
   $name=stripslashes(removeHack($name));
   $url=removeHack($url);
   $bio=stripslashes(removeHack($bio));
   $user_from=stripslashes(removeHack($user_from));
   $user_occ=stripslashes(removeHack($user_occ));
   $user_intrest=stripslashes(removeHack($user_intrest));
   $user_sig=nl2br(removeHack($user_sig));
   $user_journal=stripslashes(removeHack($user_journal));
   $op='userinfo';

   if (stristr($user_avatar,"users_private"))
      $direktori='';
   else {
      global $theme;
      $direktori='images/forum/avatar/';
      if (function_exists("theme_image")) {
         if (theme_image("forum/avatar/blank.gif"))
            $direktori="themes/$theme/images/forum/avatar/";
      }
   }

   $socialnetworks=array(); $posterdata_extend=array(); $res_id=array(); $my_rs='';
   $posterdata_extend = get_userdata_extend_from_id($uid);
   if (!$short_user) {
      include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
      if (array_key_exists('M2', $posterdata_extend)) {
         $socialnetworks= explode(';',$posterdata_extend['M2']);
         foreach ($socialnetworks as $socialnetwork) {
            $res_id[] = explode('|',$socialnetwork);
         }
         sort($res_id);
         sort($rs);
         foreach ($rs as $v1) {
            foreach($res_id as $y1) {
               $k = array_search( $y1[0],$v1);
               if (false !== $k) {
                  $my_rs.='<a class="mr-3" href="';
                  if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                  $my_rs.= '" target="_blank"><i class="fab fa-'.$v1[2].' fa-2x"></i></a> ';
                  break;
               } 
               else $my_rs.='';
            }
         }
      }
   }

   $posterdata = get_userdata_from_id($uid);
   $useroutils = '';
   if (($user) and ($uid!=1))
      $useroutils .= '<a class=" text-primary mr-3" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" ><i class="far fa-envelope fa-2x" title="'.translate("Send internal Message").'" data-toggle="tooltip"></i></a>&nbsp;';
   if ($posterdata['femail']!='')
      $useroutils .= '<a class=" text-primary mr-3" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" ><i class="fa fa-at fa-2x" title="'.translate("Email").'" data-toggle="tooltip"></i></a>&nbsp;';
   if ($posterdata['url']!='')
      $useroutils .= '<a class=" text-primary mr-3" href="'.$posterdata['url'].'" target="_blank" ><i class="fas fa-external-link-alt fa-2x" title="'.translate("Visit this Website").'" data-toggle="tooltip"></i></a>&nbsp;';
   if ($posterdata['mns'])
       $useroutils .= '<a class=" text-primary mr-3" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" ><i class="fa fa-desktop fa-2x" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip"></i></a>&nbsp;';

   echo '
   <div class="d-flex flex-row flex-wrap">
      <div class="mr-2"><img src="'.$direktori.$user_avatar.'" class=" rounded-circle center-block" /></div>
      <div class="align-self-center">
         <h2>'.translate("User").'<span class="d-inline-block text-muted ml-1">'.$uname.'</span></h2>';
   if ($uname !== $cookie[1])
      echo $useroutils;
   echo $my_rs;
   if ($uname == $cookie[1])
      echo '
         <p class="lead">'.translate("This is your personal page").'</p>';
   echo '
      </div>
   </div>
   <hr />';

   if ($uname == $cookie[1])
      nav($mns);

   include('modules/geoloc/geoloc_conf.php'); 
   echo '
   <div class="card card-body">
      <div class="row">';
   if(array_key_exists($ch_lat, $posterdata_extend) and array_key_exists($ch_lon, $posterdata_extend))
      if ($posterdata_extend[$ch_lat]!='' and $posterdata_extend[$ch_lon] !='') 
         echo '
         <div class="col-md-6">';
      else
         echo '
         <div class="col-md-12">';
   include("modules/sform/extend-user/aff_extend-user.php");
   echo '
         </div>';

   //==> openlayers implementation
   if(array_key_exists($ch_lat, $posterdata_extend) and array_key_exists($ch_lon, $posterdata_extend))
      if ($posterdata_extend[$ch_lat]!='' and $posterdata_extend[$ch_lon] !='') {
         $content = '';
         $content .='
         <div class="col-md-6">
            <div id="map_user" tabindex="300" style="width:100%; height:400px;">
               <div id="ol_popup"></div>
            </div>
            <script type="module">
            //<![CDATA[
               $("head").append($("<script />").attr({"type":"text/javascript","src":"lib/ol/ol.js"}));
               var iconFeature = new ol.Feature({
                  geometry: new ol.geom.Point(
                  ol.proj.fromLonLat(['.$posterdata_extend[$ch_lon].','.$posterdata_extend[$ch_lat].'])
                  ),
                  name: "'.$uname.'"
               });

               var iconStyle = new ol.style.Style({
                  image: new ol.style.Icon(({
                  src: "'.$ch_img.$nm_img_mbcg.'"
                  }))
               });
               iconFeature.setStyle(iconStyle);
               var vectorSource = new ol.source.Vector({
                  features: [iconFeature]
               });
               var vectorLayer = new ol.layer.Vector({
                  source: vectorSource
               });

               var map = new ol.Map({
                  interactions: new ol.interaction.defaults({
                     constrainResolution: true, onFocusOnly: true
                  }),
                 target: "map_user",
                 layers: [
                   new ol.layer.Tile({
                     source: new ol.source.OSM()
                   })
                 ],
                 view: new ol.View({
                   center: ol.proj.fromLonLat(['.$posterdata_extend[$ch_lon].', '.$posterdata_extend[$ch_lat].']),
                   zoom: 12
                 })
               });
               
               //Adding a marker on the map
               map.addLayer(vectorLayer);


      var element = document.getElementById("ol_popup");
      var popup = new ol.Overlay({
        element: element,
        positioning: "bottom-center",
        stopEvent: false,
        offset: [0, -20]
      });
      map.addOverlay(popup);

 // display popup on click
      map.on("click", function(evt) {
        var feature = map.forEachFeatureAtPixel(evt.pixel,
          function(feature) {
            return feature;
          });
        if (feature) {
          var coordinates = feature.getGeometry().getCoordinates();
          popup.setPosition(coordinates);
          $(element).popover({
            placement: "top",
            html: true,
            content: feature.get("name")
          });
          $(element).popover("show");
        } else {
          $(element).popover("hide");
        }
      });
      // change mouse cursor when over marker
      map.on("pointermove", function(e) {
        if (e.dragging) {
          $(element).popover("hide");
          return;
        }
        var pixel = map.getEventPixel(e.originalEvent);
      });
               // Create the graticule component
                  var graticule = new ol.Graticule();
                  graticule.setMap(map);

            //]]>
            </script>';
         $content .='
         <div class="mt-3">
            <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg"></i>&nbsp;[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese][spanish]Mapa[/spanish][german]Karte[/german]</a>';
         if($admin)
            $content .= '
            <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i class="fa fa-cogs fa-lg ml-3"></i>&nbsp;[french]Admin[/french][english]Admin[/english][chinese]Admin[/chinese][spanish]Admin[/spanish][german]Admin[/german]</a>';
         $content .= '
            </div>
         </div>';
         $content = aff_langue($content);
         echo $content;
   }
   //<== openlayers implementation

   echo '
      </div>
   </div>';
   if($uid!=1)
      echo '
      <br />
      <h4>'.translate("Online journal for").' '.$uname.'.</h4>
      <div id="online_user_journal" class="card card-body mb-3">'.meta_lang($user_journal).'</div>';
   $file='';
   $handle=opendir('modules/comments');
   while (false!==($file = readdir($handle))) {
      if (!preg_match('#\.conf\.php$#i',$file)) continue;
      $topic="#topic#";
      include("modules/comments/$file");
      $filelist[$forum] = $url_ret;
   }
   closedir($handle);

   echo '
   <h4 class="my-3">'.translate("Last comments by").' '.$uname.'.</h4>
   <div id="last_comment_by" class="card card-body mb-3">';
   $url='';
   $result=sql_query("SELECT topic_id, forum_id, post_text, post_time FROM ".$NPDS_Prefix."posts WHERE forum_id<0 and poster_id='$uid' ORDER BY post_time DESC LIMIT 0,10");
   while(list($topic_id, $forum_id, $post_text, $post_time) = sql_fetch_row($result)) {
      $url=str_replace("#topic#",$topic_id,$filelist[$forum_id]);
      echo '<p><a href="'.$url.'">'.translate("Posted: ").convertdate($post_time).'</a></p>';
      $message=smilie(stripslashes($post_text));
      $message = aff_video_yt($message);
      $message = str_replace('[addsig]','',$message);
      if (stristr($message,"<a href")) {
         $message=preg_replace('#_blank(")#i','_blank\1 class=\1noir\1',$message);
      }
      echo nl2br($message).'<hr />';
   }
   echo '
    </div>
    <h4 class="my-3">'.translate("Last articles sent by").' '.$uname.'.</h4>
    <div id="last_article_by" class="card card-body mb-3">';
   $xtab=news_aff("libre", "WHERE informant='$uname' ORDER BY sid DESC LIMIT 10", '', 10);
   $story_limit=0;
   while (($story_limit<10) and ($story_limit<sizeof($xtab))) {
      list($sid, $catid, $aid, $title,$time) = $xtab[$story_limit];
      $story_limit++;
      echo '
      <div class="d-flex">
        <div class="p-2"><a href="article.php?sid='.$sid.'">'.aff_langue($title).'</a></div>
        <div class="ml-auto p-2">'.$time.'</div>
      </div>';
   }
   echo '
   </div>
   <h4 class="my-3">'.translate("Last news submissions sent by").' '.$uname.'</h4>';
   $nbp = 10;
   $content ='';
   $result = sql_query("SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 AND poster_id=$uid ORDER BY post_time DESC LIMIT 0,50");
   $j=1;
   while (list($post_id,$post_text) = sql_fetch_row($result) and $j<=$nbp)
   {
      /*Requete detail dernier post*/
      $res = sql_query("SELECT 
            us.topic_id, us.forum_id, us.poster_id, us.post_time, 
            uv.topic_title, 
            ug.forum_name, ug.forum_type, ug.forum_pass, 
            ut.uname 
         FROM 
            ".$NPDS_Prefix."posts us, 
            ".$NPDS_Prefix."forumtopics uv, 
            ".$NPDS_Prefix."forums ug, 
            ".$NPDS_Prefix."users ut 
         WHERE 
            us.post_id = $post_id 
            AND uv.topic_id = us.topic_id 
            AND uv.forum_id = ug.forum_id 
            AND ut.uid = us.poster_id LIMIT 1");
      list($topic_id, $forum_id, $poster_id, $post_time, $topic_title, $forum_name, $forum_type, $forum_pass, $uname) = sql_fetch_row($res);
      if (($forum_type == '5') or ($forum_type == '7')) {
         $ok_affich = false;
         $tab_groupe = valid_group($user);
         $ok_affich = groupe_forum($forum_pass, $tab_groupe);
      }
      else $ok_affich = true;
      if ($ok_affich) {
         /*Nbre de postes par sujet*/
         $TableRep = sql_query("SELECT * FROM ".$NPDS_Prefix."posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
         $replys = sql_num_rows($TableRep)-1;
         $sqlR = "SELECT rid FROM ".$NPDS_Prefix."forum_read WHERE topicid = '$topic_id' AND uid = '$cookie[0]' AND status != '0'";
            if (sql_num_rows(sql_query($sqlR))==0)
               $image = '<a href="" title="'.translate("Not Read").'" data-toggle="tooltip"><i class="fa fa-file fa-lg "></i></a>';
            else
               $image = '<a title="'.translate("Read").'" data-toggle="tooltip"><i class="fa fa-file-o fa-lg "></i></a>';
         $content .='
         <p class="mb-0 list-group-item list-group-item-action flex-column align-items-start" >
            <span class="d-flex w-100 mt-1">
            <span>'.$post_time.'</span>
            <span class="ml-auto">
               <span class="badge badge-secondary ml-1" title="'.translate("Replies").'" data-toggle="tooltip" data-placement="left">'.$replys.'</span>
            </span>
         </span>
         <span class="d-flex w-100"><br /><a href="viewtopic.php?topic='.$topic_id.'&forum='.$forum_id.'" data-toggle="tooltip" title="'.$forum_name.'">'.$topic_title.'</a><span class="ml-auto">'.$image.'</span></span>
         </p>';
      $j++;
      }
   }
   echo $content;
   echo'
   <hr />';
   if($posterdata['attachsig']==1)
      echo'
   <p class="n-signature">'.$user_sig.'</p>';
   include("footer.php");
}

function main($user) {
   global $stop, $smilies;
   if (!isset($user)) {
      include("header.php");
      echo '<h2>'.translate("User").'</h2>';
      if ($stop==99)
         echo '<p class="alert alert-danger"><i class="fa fa-exclamation"></i>&nbsp;'.translate("User not yet allowed by Administrator").'</p>';
      elseif ($stop)
         echo '<p class="alert alert-danger"><i class="fa fa-exclamation"></i>&nbsp;'.translate("Incorrect Login!").'</p>';
      if (!$user) {
         echo '
         <div class="card card-body mb-3">
            <h3><a href="user.php?op=only_newuser" role="button" title="'.translate("New User").'"><i class="fa fa-user-plus"></i>&nbsp;'.translate("New User").'</a></h3>
         </div>
          <div class="card card-body">
          <h3><i class="fas fa-sign-in-alt fa-lg"></i>&nbsp;'.translate("Connection").'</h3>
          <form action="user.php" method="post" name="userlogin">
             <div class="form-group row">
               <label for="inputuser" class="col-form-label col-sm-4">'.translate("Nickname").'</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="uname" id="inputuser" placeholder="'.translate("Nickname").'" required="required" />
               </div>
            </div>
            <div class="form-group row">
               <label for="inputPassuser" class="col-form-label col-sm-4">'.translate("Password").'</label>
               <div class="col-sm-8">
                  <input type="password" class="form-control" name="pass" id="inputPassuser" placeholder="'.translate("Password").'" required="required" />
                  <span class="help-block small"><a href="user.php?op=forgetpassword" title="'.translate("Lost your Password?").'">'.translate("Lost your Password?").'</a></span>
               </div>
            </div>
            <input type="hidden" name="op" value="login" />
            <div class="form-group row">
               <div class="col-sm-8 ml-sm-auto">
                  <button class="btn btn-primary" type="submit" title="'.translate("Submit").'">'.translate("Submit").'</button>
               </div>
            </div>
         </form>
         </div>';

          echo "<script type=\"text/javascript\">\n//<![CDATA[\ndocument.userlogin.uname.focus();\n//]]>\n</script>";

         // include externe file from modules/include for functions, codes ...
         if (file_exists("modules/include/user.inc"))
            include ("modules/include/user.inc");
      }
      include("footer.php");
   } elseif (isset($user)) {
      $cookie=cookiedecode($user);
      userinfo($cookie[1]);
   }
}

function logout() {
   global $NPDS_Prefix, $user, $cookie;
   if ($cookie[1]!='')
      sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE username='$cookie[1]'");
   setcookie('user','',0);
   unset($user);
   setcookie('user_language','',0);
   unset($user_language);
   Header('Location: index.php');
}

function ForgetPassword() {
   include("header.php");
   echo '
   <h2 class="mb-3">'.translate("User").'</h2>
   <div class="card card-body">
   <div  class="alert alert-danger text-center">'.translate("Lost your Password?").'</div>
   <div  class="alert alert-success text-center">'.translate("No problem. Just type your Nickname, the new password you want and click on send button to recieve a email with the confirmation code.").'</div>
   <form id="forgetpassword" action="user.php" method="post">
      <div class="form-group row">
         <label for="inputuser" class="col-sm-3 col-form-label">'.translate("Nickname").'</label>
         <div class="col-sm-9">
            <input type="text" class="form-control" name="uname" id="inputuser" placeholder="'.translate("Nickname").'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label for="inputpassuser" class="col-sm-3 col-form-label">'.translate("Password").'</label>
         <div class="col-sm-9">
            <input type="password" class="form-control" name="code" id="inputpassuser" placeholder="'.translate("Password").'" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-9 ml-sm-auto" >
            <div class="progress" style="height: 0.2rem;">
               <div id="passwordMeter_cont" class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
         </div>
      </div>
      <input type="hidden" name="op" value="mailpasswd" />
      <div class="form-group row">
         <div class="col-sm-9 ml-sm-auto">
            <input class="btn btn-primary" type="submit" value ="'.translate("Send").'" />
         </div>
      </div>
   </form>
   </div>';
   $fv_parametres ='
      code: {
         validators: {
            checkPassword: {
               message: "Le mot de passe est trop simple."
            },
         }
      },';
   $arg1 ='
      var formulid = ["forgetpassword"];';
   adminfoot('fv',$fv_parametres,$arg1,'foo');
   include ('footer.php');
}

function mail_password($uname, $code) {
    global $NPDS_Prefix, $sitename, $nuke_url;
    $uname=removeHack(stripslashes(htmlspecialchars(urldecode($uname),ENT_QUOTES,cur_charset)));
    $result = sql_query("SELECT uname,email,pass FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
    $tmp_result=sql_fetch_row($result);
    if (!$tmp_result)
       message_error(translate("Sorry, no corresponding user info was found")."<br /><br />",'');
    else {
       $host_name = getip();
       list($uname,$email, $pass) = $tmp_result;
       // On envoie une URL avec dans le contenu : username, email, le MD5 du passwd retenu et le timestamp
       $url="$nuke_url/user.php?op=validpasswd&code=".urlencode(encrypt($uname)."#fpwd#".encryptK($email."#fpwd#".$code."#fpwd#".time(),$pass));

       $message = translate("The user account").' '.$uname.' '.translate("at").' '.$sitename.' '.translate("has this email associated with it.")."\n\n";
       $message.= translate("A web user from")." $host_name ".translate("has just requested a Confirmation to change the password.")."\n\n".translate("Your Confirmation URL is:")." <a href=\"$url\">$url</a> \n\n".translate("If you didn't ask for this, don't worry. Just delete this Email.")."\n\n";
       include("signat.php");

       $subject=translate("Confirmation Code for").' '.$uname;

       send_email($email, $subject, $message, '', true, 'html');
       message_pass('<div class="alert alert-success lead text-center"><i class="fa fa-exclamation"></i>&nbsp;'.translate("Confirmation Code for").' '.$uname.' '.translate("mailed.").'</div>');
       Ecr_Log('security', 'Lost_password_request : '.$uname, '');
    }
}

function valid_password ($code) {
   global $NPDS_Prefix;

   $ibid=explode("#fpwd#",$code);
   $result = sql_query("SELECT email,pass FROM ".$NPDS_Prefix."users WHERE uname='".decrypt($ibid[0])."'");
   list($email, $pass) = sql_fetch_row($result);
   if ($email!='') {
      $ibid=explode("#fpwd#",decryptK($ibid[1],$pass));
      if ($email==$ibid[0]) {
         include("header.php");
         echo '
      <p class="lead">'.translate("Lost your Password?").'</p>
      <div class="card border rounded p-3">
         <div class="row">
            <div class="col-sm-7">
               <div class="blockquote">'.translate("To valid your new password request, just re-type it.").'<br />'.translate("Your Password is: ").' <strong>'.$ibid[1].'</strong></div>
            </div>
            <div class="col-sm-5">
               <form id="lostpassword" action="user.php" method="post">
                  <div class="form-group row">
                     <label class="col-form-label col-sm-12" for="passwd">'.translate("Password").'</label>
                     <div class="col-sm-12">
                        <input type="password" class="form-control" name="passwd" placeholder="'.$ibid[1].'" required="required" />
                     </div>
                  </div>
                  <input type="hidden" name="op" value="updatepasswd" />
                  <input type="hidden" name="code" value="'.$code.'" />
                  <div class="form-group row">
                     <div class="col-sm-12">
                        <input class="btn btn-primary" type="submit" value="'.translate("Submit").'" />
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>';
         include ("footer.php");
      } else {
         message_pass('<div class="alert alert-danger lead text-center">'.translate("Error").'</div>');
         Ecr_Log('security', 'Lost_password_valid NOK Mail not match : '.$ibid[0], '');
      }
   } else {
      message_pass('<div class="alert alert-danger lead text-center">'.translate("Error").'</div>');
      Ecr_Log('security', 'Lost_password_valid NOK Bad hash : '.$ibid[0], '');
   }
}

function update_password ($code, $passwd) {
    global $system, $NPDS_Prefix;
    $ibid=explode("#fpwd#",$code);
    $uname=urlencode(decrypt($ibid[0]));
    $result = sql_query("SELECT email,pass FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
    list($email, $pass) = sql_fetch_row($result);
    if ($email!='') {
       $ibid=explode("#fpwd#",decryptK($ibid[1],$pass));
       if ($email==$ibid[0]) {
          // Le lien doit avoir été généré dans les 24H00
          if ((time()-$ibid[2])<86400) {
             // le mot de passe est-il identique
             if ($ibid[1]==$passwd) {
                if (!$system)
                   $cryptpass=crypt($ibid[1],$ibid[1]);
                else
                   $cryptpass=$ibid[1];
                sql_query("UPDATE ".$NPDS_Prefix."users SET pass='$cryptpass' WHERE uname='$uname'");
                message_pass('<div class="alert alert-success lead text-center"><a class="alert-link" href="user.php"><i class="fa fa-exclamation mr-2"></i>'.translate ("Password update, please re-connect you.").'<i class="fas fa-sign-in-alt fa-lg ml-2"></i></a></div>');
                Ecr_Log('security', 'Lost_password_update OK : '.$uname, '');
             } else {
                message_pass('<div class="alert alert-danger lead text-center">'.translate("Error").' : '.translate("Both passwords are different. They need to be identical.").'</div>');
                Ecr_Log('security', 'Lost_password_update Password not match : '.$uname, '');
             }
          } else {
             message_pass('<div class="alert alert-danger lead text-center">'.translate("Error").' : '.translate("Your Confirmation URL is expired").' > 24 h</div>');
             Ecr_Log('security', 'Lost_password_update NOK Time > 24H00 : '.$uname, '');
          }
       } else {
          message_pass('<div class="alert alert-danger lead text-center">'.translate("ERROR: Invalid email").'</div>');
          Ecr_Log('security', 'Lost_password_update NOK Mail not match : '.$uname, '');
       }
    } else {
       message_pass('<div class="alert alert-danger lead text-center">'.translate("Error").'</div>');
       Ecr_Log('security', 'Lost_password_update NOK Empty Mail or bad user : '.$uname, '');
    }
}

function docookie($setuid, $setuname, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax, $user_langue) {
   $info = base64_encode("$setuid:$setuname:".md5($setpass).":$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax");
   global $user_cook_duration;
   if ($user_cook_duration<=0) $user_cook_duration=1;
   $timeX=time()+(3600*$user_cook_duration);
   setcookie("user","$info",$timeX);
   if ($user_langue!='')
       setcookie('user_language',"$user_langue",$timeX);
}

function login($uname, $pass) {
   global $NPDS_Prefix, $setinfo, $system;

   $result = sql_query("SELECT pass, uid, uname, storynum, umode, uorder, thold, noscore, ublockon, theme, commentmax, user_langue FROM ".$NPDS_Prefix."users WHERE uname = '$uname'");
   if (sql_num_rows($result)==1) {
      $setinfo = sql_fetch_assoc($result);
      $result = sql_query("SELECT open FROM ".$NPDS_Prefix."users_status WHERE uid='".$setinfo['uid']."'");
      list($open_user) = sql_fetch_row($result);
      if ($open_user==0) {
         Header("Location: user.php?stop=99");
         return;
      }
      $dbpass=$setinfo['pass'];
      if (cur_charset=="utf-8") {
         if (!$system)
            $passwd=crypt($pass,$dbpass);
         else
            $passwd=$pass;
         if (strcmp($dbpass,$passwd)!=0) {
            $pass=utf8_decode($pass);
            if (!$system)
               $passwd=crypt($pass,$dbpass);
            else
               $passwd=$pass;
            if (strcmp($dbpass,$passwd)!=0) {
               Header("Location: user.php?stop=1");
               return;
            }
         }
         docookie($setinfo['uid'], $setinfo['uname'], $passwd, $setinfo['storynum'], $setinfo['umode'], $setinfo['uorder'], $setinfo['thold'], $setinfo['noscore'], $setinfo['ublockon'], $setinfo['theme'], $setinfo['commentmax'], $setinfo['user_langue']);
      } else {
          if (!$system)
             $passwd=crypt($pass,$dbpass);
          else
             $passwd=$pass;
          if (strcmp($dbpass,$passwd)==0)
             docookie($setinfo['uid'], $setinfo['uname'], $passwd, $setinfo['storynum'], $setinfo['umode'], $setinfo['uorder'], $setinfo['thold'], $setinfo['noscore'], $setinfo['ublockon'], $setinfo['theme'], $setinfo['commentmax'], $setinfo['user_langue']);
          else {
             Header("Location: user.php?stop=1");
             return;
         }
      }

       $ip = getip();
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."session WHERE host_addr='$ip' AND guest='1'");
       if (sql_num_rows($result)==1)
          sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE host_addr='$ip' AND guest='1'");

       Header("Location: index.php");
   } else
      Header("Location: user.php?stop=1");
}

function edituser() {
   global $NPDS_Prefix, $user, $smilies, $short_user, $subscribe, $member_invisible, $avatar_size;
   include("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);

   global $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2,$B1;
   $result = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2, B1 FROM ".$NPDS_Prefix."users_extend WHERE uid='".$userinfo['uid']."'");
   list($C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1) = sql_fetch_row($result);
   showimage();
   include ("modules/sform/extend-user/mod_extend-user.php");
   include("footer.php");
}

function saveuser($uid, $name, $uname, $email, $femail, $url, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $attach, $usend_email, $uis_visible,$user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$MAX_FILE_SIZE,$raz_avatar) {
   global $NPDS_Prefix, $user, $userinfo, $system, $minpass;
   $cookie=cookiedecode($user);
   $check = $cookie[1];
   $result = sql_query("SELECT uid, email FROM ".$NPDS_Prefix."users WHERE uname='$check'");
   list($vuid, $vemail) = sql_fetch_row($result);
   if (($check == $uname) AND ($uid == $vuid)) {
      if ((isset($pass)) && ("$pass" != "$vpass"))
         message_error('<i class="fa fa-exclamation mr-2"></i>'.translate("Both passwords are different. They need to be identical.").'<br />','');
      elseif (($pass != '') && (strlen($pass) < $minpass))
         message_error('<i class="fa fa-exclamation mr-2"></i>'.translate("Sorry, your password must be at least").' <strong>'.$minpass.'</strong> '.translate("characters long").'<br />','');
      else {
         $stop=userCheck('edituser', $email);
         if (!$stop) {
            $contents='';
            $filename = "users_private/usersbadmail.txt";
            $handle = fopen($filename, "r");
            if(filesize($filename)>0)
               $contents = fread($handle, filesize($filename));
            fclose($handle);
            $re = '/#'.$uid.'\|(\d+)/m';
            $maj=preg_replace($re, '', $contents);
            $file = fopen("users_private/usersbadmail.txt", 'w');
            fwrite($file,$maj);
            fclose($file);
            if ($bio) $bio=FixQuotes(strip_tags($bio));
            if ($attach) $t = 1; else $t = 0;
            if ($user_viewemail) $a = 1; else $a = 0;
            if ($usend_email) $u = 1; else $u = 0;
            if ($uis_visible) $v = 0; else $v = 1;
            if ($user_lnl) $w = 1; else $w = 0;

              include_once("modules/upload/upload.conf.php");
              global $avatar_size;
              if (!$avatar_size) {$avatar_size='80*100';}
              $avatar_limit=explode("*",$avatar_size);
              if ($DOCUMENTROOT!='')
                 $rep=$DOCUMENTROOT;
              else {
                 global $DOCUMENT_ROOT;
                 if ($DOCUMENT_ROOT)
                    $rep=$DOCUMENT_ROOT;
                 else
                    $rep=$_SERVER['DOCUMENT_ROOT'];
              }
              if ($B1!='none') {
                 global $language;
                 include_once("modules/upload/lang/upload.lang-$language.php");
                 include_once("modules/upload/clsUpload.php");

                 $upload = new Upload();
                 $upload->maxupload_size=$MAX_FILE_SIZE;
                 $field1_filename = trim($upload->getFileName("B1"));
                 $suffix = strtoLower(substr(strrchr($field1_filename,'.'),1));
                 if (($suffix=='gif') or ($suffix=='jpg') or ($suffix=='png')) {
                    $field1_filename=removeHack(preg_replace('#[/\\\:\*\?"<>|]#i','', rawurldecode($field1_filename)));
                    $field1_filename=preg_replace('#\.{2}|config.php|/etc#i','', $field1_filename);
                    if ($field1_filename) {
                       if ($autorise_upload_p) {
                          $user_dir=$racine.'/users_private/'.$uname.'/';
                          if (!is_dir($rep.$user_dir)) {
                             @umask("0000");
                             if (@mkdir($rep.$user_dir,0777)) {
                                $fp = fopen($rep.$user_dir.'index.html', 'w');
                                fclose($fp);
                             }
                             else
                                $user_dir=$racine.'/users_private/';
                          }
                       }
                       else
                          $user_dir=$racine.'/users_private/';
                       if ($upload->saveAs($uname.'.'.$suffix ,$rep.$user_dir, 'B1',true)) {
                          $old_user_avatar=$user_avatar;
                          $user_avatar=$user_dir.$uname.'.'.$suffix;
                          $img_size = @getimagesize($rep.$user_avatar);
                          if (($img_size[0]>$avatar_limit[0]) or ($img_size[1]>$avatar_limit[1])) {
                             $raz_avatar=true;
                          }
                          if ($racine=='') $user_avatar=substr($user_avatar,1);
                       }
                    }
                 }
              }
              if ($raz_avatar) {
                 if (strstr($user_avatar,'/users_private')) {
                    @unlink($rep.$user_avatar);
                    @unlink($rep.$old_user_avatar);
                 }
                 $user_avatar='blank.gif';
              }
              if ($pass!='') {
                 cookiedecode($user);
                 if (!$system)
                    $pass=crypt($pass,$pass);
                 sql_query("UPDATE ".$NPDS_Prefix."users SET name='$name', email='$email', femail='".removeHack($femail)."', url='".removeHack($url)."', pass='$pass', bio='".removeHack($bio)."', user_avatar='$user_avatar', user_occ='".removeHack($user_occ)."', user_from='".removeHack($user_from)."', user_intrest='".removeHack($user_intrest)."', user_sig='".removeHack($user_sig)."', user_viewemail='$a', send_email='$u', is_visible='$v', user_lnl='$w' WHERE uid='$uid'");
                 $result = sql_query("SELECT uid, uname, pass, storynum, umode, uorder, thold, noscore, ublockon, theme FROM ".$NPDS_Prefix."users WHERE uname='$uname' AND pass='$pass'");
                 if (sql_num_rows($result)==1) {
                    $userinfo = sql_fetch_assoc($result);
                    docookie($userinfo['uid'],$userinfo['uname'],$userinfo['pass'],$userinfo['storynum'],$userinfo['umode'],$userinfo['uorder'],$userinfo['thold'],$userinfo['noscore'],$userinfo['ublockon'],$userinfo['theme'],$userinfo['commentmax'], "",$skin);
                 }
              } else {
                 sql_query("UPDATE ".$NPDS_Prefix."users SET name='$name', email='$email', femail='".removeHack($femail)."', url='".removeHack($url)."', bio='".removeHack($bio)."', user_avatar='$user_avatar', user_occ='".removeHack($user_occ)."', user_from='".removeHack($user_from)."', user_intrest='".removeHack($user_intrest)."', user_sig='".removeHack($user_sig)."', user_viewemail='$a', send_email='$u', is_visible='$v', user_lnl='$w' WHERE uid='$uid'");
              }
              sql_query("UPDATE ".$NPDS_Prefix."users_status SET attachsig='$t' WHERE uid='$uid'");
              $result=sql_query("SELECT uid FROM ".$NPDS_Prefix."users_extend WHERE uid='$uid'");
              if (sql_num_rows($result)==1) {
                 sql_query("UPDATE ".$NPDS_Prefix."users_extend SET C1='".removeHack($C1)."', C2='".removeHack($C2)."', C3='".removeHack($C3)."', C4='".removeHack($C4)."', C5='".removeHack($C5)."', C6='".removeHack($C6)."', C7='".removeHack($C7)."', C8='".removeHack($C8)."', M1='".removeHack($M1)."', M2='".removeHack($M2)."', T1='".removeHack($T1)."', T2='".removeHack($T2)."', B1='$B1' WHERE uid='$uid'");
              } else {
                 $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_extend VALUES ('$uid','".removeHack($C1)."', '".removeHack($C2)."', '".removeHack($C3)."', '".removeHack($C4)."', '".removeHack($C5)."', '".removeHack($C6)."', '".removeHack($C7)."', '".removeHack($C8)."', '".removeHack($M1)."', '".removeHack($M2)."', '".removeHack($T1)."', '".removeHack($T2)."', '$B1')");
              }
              if ($pass!='') {
                 logout();
              } else {
                 header("location: user.php?op=edituser");
              }
           } else {
               message_error($stop, '');
           }
        }
    } else
       Header("Location: index.php");
}

function edithome() {
   global $user, $Default_Theme, $Default_skin;
   include ("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);
   if ($userinfo['theme']=='') {
      $userinfo['theme'] = "$Default_Theme+$Default_skin";
   }
   echo '
   <h2 class="mb-3">'.translate("Change the home").'</h2>
   <form id="changehome" action="user.php" method="post">
   <div class="form-group row">
      <label class="col-form-label col-sm-7" for="storynum">'.translate("News number in the Home").' (max. 127) :</label>
      <div class="col-sm-5">
         <input class="form-control" type="text" min="0" max="127" id="storynum" name="storynum" maxlength="3" value="'.$userinfo['storynum'].'" />
      </div>
   </div>';
   if ($userinfo['ublockon']==1) $sel = 'checked="checked"';
   else $sel = '';
   echo '
   <div class="form-group row">
      <div class="col-sm-10">
         <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="ublockon" name="ublockon" value="1" '.$sel.' />
            <label class="custom-control-label" for="ublockon">'.translate("Activate Personal Menu").'</label>
         </div>
      </div>
   </div>
   <ul>
      <li>'.translate("Check this option and the following text will appear in the Home").'</li>
      <li>'.translate("You can use HTML code to put links, for example").'</li>
   </ul>
   <div class="form-group row">
      <div class="col-sm-12">
         <textarea class="form-control" rows="20" name="ublock">'.$userinfo['ublock'].'</textarea>
      </div>
   </div>
      <div class="form-group row">
         <input type="hidden" name="theme" value="'.$userinfo['theme'].'" />
         <input type="hidden" name="uname" value="'.$userinfo['uname'].'" />
         <input type="hidden" name="uid" value="'.$userinfo['uid'].'" />
         <input type="hidden" name="op" value="savehome" />
         <div class="col-sm-12">
            <input class="btn btn-primary" type="submit" value="'.translate("Save Changes!").'" />
         </div>
      </div>
   </form>';
   $fv_parametres='
   storynum: {
      validators: {
         regexp: {
            regexp:/^[1-9](\d{0,2})$/,
            message: "0-9"
         },
         between: {
            min: 1,
            max: 127,
            message: "1 ... 127"
         }
      }
   },';
   $arg1='
   var formulid=["changehome"];';
   adminfoot('fv',$fv_parametres,$arg1,'foo');
}
function savehome($uid, $uname, $theme, $storynum, $ublockon, $ublock) {
   global $NPDS_Prefix, $user;
   $cookie=cookiedecode($user);
   $check = $cookie[1];
   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$check'");
   list($vuid) = sql_fetch_row($result);
   if (($check == $uname) AND ($uid == $vuid)) {
      if ($ublockon) $ublockon=1; else $ublockon=0;
      $ublock = FixQuotes($ublock);
      sql_query("UPDATE ".$NPDS_Prefix."users SET storynum='$storynum', ublockon='$ublockon', ublock='$ublock' WHERE uid='$uid'");
      $userinfo=getusrinfo($user);
      docookie($userinfo['uid'],$userinfo['uname'],$userinfo['pass'],$userinfo['storynum'],$userinfo['umode'],$userinfo['uorder'],$userinfo['thold'],$userinfo['noscore'],$userinfo['ublockon'],$userinfo['theme'],$userinfo['commentmax'], '');
      // Include cache manager for purge cache Page
      $cache_obj = new cacheManager();
      $cache_obj->UsercacheCleanup();
      Header("Location: user.php?op=edithome");
   } else
   Header("Location: index.php");
}

function chgtheme() {
   global $user;
   include ("header.php");
   $userinfo=getusrinfo($user);
   
   // nouvel version de la gestion des Themes et Skins
   $ibid=explode('+', $userinfo['theme']);
   $theme=$ibid[0];
   if (array_key_exists(1, $ibid)) $skin=$ibid[1]; else $skin='';

   nav($userinfo['mns']);
   echo '
   <h2>'.translate("Change Theme").'</h2>
   <form role="form" action="user.php" method="post">
      <div class="form-group row">
         <label class="col-form-label col-sm-5" for="theme_local">'.translate("Select One Theme").'</label>
         <div class="col-sm-7">
            <select class="custom-select form-control" id="theme_local" name="theme_local">';
   include("themes/list.php");
   $themelist = explode(' ', $themelist);
   $thl= sizeof($themelist);
   for ($i=0; $i < $thl; $i++) {
      if ($themelist[$i]!='') {
         echo '
               <option value="'.$themelist[$i].'" ';
         if ((($theme=='') && ($themelist[$i]==$Default_Theme)) || ($theme==$themelist[$i])) echo 'selected="selected"';
         echo '>'.$themelist[$i].'</option>';
      }
   }
   echo '
            </select>
            <p class="help-block">
               <span>'.translate("This option will change the look for the whole site.").'</span> 
               <span>'.translate("The changes will be valid only to you.").'</span> 
               <span>'.translate("Each user can view the site with different theme.").'</span>
            </p>
         </div>
      </div>';

   $handle=opendir('themes/_skins');
   while (false!==($file = readdir($handle))) {
      if ( ($file[0]!=='_') and (!strstr($file,'.')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
         $skins[] = array('name'=> $file, 'description'=> '', 'thumbnail'=> $file.'/thumbnail','preview'=> $file.'/','css'=> $file.'/bootstrap.css','cssMin'=> $file.'/bootstrap.min.css','cssxtra'=> $file.'/extra.css','scss'=> $file.'/_bootswatch.scss','scssVariables'=> $file.'/_variables.scss');
      }
   }
   closedir($handle);
   asort($skins);
      echo '
      <div class="form-group row" id="skin_choice">
         <label class="col-form-label col-sm-5" for="skins">'.translate("Select one skin").'</label>
         <div class="col-sm-7">
            <select class="custom-select form-control" id="skins" name="skins">';
   foreach ($skins as $k => $v) {
      echo '
               <option value="'.$skins[$k]['name'].'" ';
      if ($skins[$k]['name'] == $skin) echo 'selected="selected"';
      else if($skin=='' and $skins[$k]['name'] == 'default') echo 'selected="selected"';
      echo '>'.$skins[$k]['name'].'</option>';
   }
      echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div id="skin_thumbnail" class="col-sm-7 ml-sm-auto"></div>
      </div>
      <div class="form-group row">
         <div class="col-sm-7 ml-sm-auto">
            <input type="hidden" name="uname" value="'.$userinfo['uname'].'" />
            <input type="hidden" name="uid" value="'.$userinfo['uid'].'" />
            <input type="hidden" name="op" value="savetheme" />
            <input class="btn btn-primary" type="submit" value="'.translate("Save Changes!").'" />
         </div>
      </div>
   </form>
   <script type="text/javascript">
   //<![CDATA[
   $(function () {
      $("#theme_local").change(function () {
         sk = $("#theme_local option:selected").text().substr(-3);
         if(sk=="_sk") {
            $("#skin_choice").removeClass("collapse");
            $("#skins").change(function () {
               sl = $("#skins option:selected").text();
               $("#skin_thumbnail").html(\'<a href="themes/_skins/\'+sl+\'" class="btn btn-outline-primary"><img class="img-fluid img-thumbnail" src="themes/_skins/\'+sl+\'/thumbnail.png" /></a>\');
            }).change();
         } else {
            $("#skin_choice").addClass("collapse");
            $("#skin_thumbnail").html(\'\');
         }
      })
     .change();
   });
   //]]
   </script>';
   include ("footer.php");
}

function savetheme($uid, $theme) {
   global $NPDS_Prefix, $user;
     
   $cookie=cookiedecode($user);
   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
   list($vuid) = sql_fetch_row($result);
   
   if ($uid == $vuid) {
      sql_query("UPDATE ".$NPDS_Prefix."users SET theme='$theme' WHERE uid='$uid'");
      $userinfo=getusrinfo($user);
      docookie($userinfo['uid'],$userinfo['uname'],$userinfo['pass'],$userinfo['storynum'],$userinfo['umode'],$userinfo['uorder'],$userinfo['thold'],$userinfo['noscore'],$userinfo['ublockon'],$theme,$userinfo['commentmax'],'');
      // Include cache manager for purge cache Page
      $cache_obj = new cacheManager();
      $cache_obj->UsercacheCleanup();
      Header("Location: user.php");
   } else
      Header("Location: index.php");
}

function editjournal(){
   global $user;
   include("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);
   echo '
   <h2 class="mb-3">'.translate("Edit your journal").'</h2>
   <form action="user.php" method="post" name="adminForm">
      <div class="form-group row">
         <div class="col-sm-12">
            <textarea class="tin form-control" rows="25" name="journal">'.$userinfo['user_journal'].'</textarea>'
         .aff_editeur('journal', '').'
         </div>
      </div>
      <input type="hidden" name="uname" value="'.$userinfo['uname'].'" />
      <input type="hidden" name="uid" value="'.$userinfo['uid'].'" />
      <input type="hidden" name="op" value="savejournal" />
      <div class="form-group row">
         <div class="col-12">
            <div class="custom-control custom-checkbox">
               <input class="custom-control-input" type="checkbox" id="datetime" name="datetime" value="1" />
               <label class="custom-control-label" for="datetime">'.translate("Add date and time stamp").'</label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-12">
            <input class="btn btn-primary" type="submit" value="'.translate("Save Journal").'" />
         </div>
      </div>
   </form>';
   include("footer.php");
}

function savejournal($uid, $journal, $datetime){
   global $NPDS_Prefix, $user;
   $cookie=cookiedecode($user);
   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
   list($vuid) = sql_fetch_row($result);
   if ($uid == $vuid) {
      $journal = removeHack(stripslashes(FixQuotes($journal)));
      if ($datetime) {
         $journalentry = $journal;
         $journalentry .= '<br /><br />';
         global $gmt;
         $journalentry .= date(translate("dateinternal"),time()+((integer)$gmt*3600));
         sql_query("UPDATE ".$NPDS_Prefix."users SET user_journal='$journalentry' WHERE uid='$uid'");
      } else {
         sql_query("UPDATE ".$NPDS_Prefix."users SET user_journal='$journal' WHERE uid='$uid'");
      }
      $userinfo=getusrinfo($user);
      Header("Location: user.php");
   } else {
      Header("Location: index.php");
   }
}

settype($op,'string');
switch ($op) {
   case 'logout':
      logout();
   break;
   case 'new user':
      // CheckBox
      settype($user_viewemail,'integer');
      settype($user_lnl,'integer');
      settype($pass,'string');
      settype($vpass,'string');
      confirmNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1);
   break;
   case 'finish':
      finishNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1);
   break;
   case 'forgetpassword':
      ForgetPassword();
   break;
   case "mailpasswd":
      if ($uname!='' and $code!='') {
         if (strlen($code)>=$minpass)
            mail_password($uname, $code);
         else
            message_error("<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("You did not enter the correct password, please go back and try again.")."<br /><br />","");
      } else {
         main($user);
      }
   break;
   case 'validpasswd':
      if ($code!='') {
         valid_password($code);
      } else {
         main($user);
      }
   break;
   case 'updatepasswd':
      if ($code!='' and $passwd!='') {
         update_password($code, $passwd);
      } else {
         main($user);
      }
   break;
   case 'userinfo':
      if (($member_list==1) AND ((!isset($user)) AND (!isset($admin)))) {
         Header("Location: index.php");
      }
      if ($uname!='') {
         userinfo($uname);
      } else {
         main($user);
      }
   break;
   case 'login':
      login($uname, $pass);
   break;
   case 'edituser':
      if ($user)
         edituser();
      else
         Header("Location: index.php");
   break;
   case 'saveuser':
      $past = time()-300;
      sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE time < $past");
      $result = sql_query("SELECT time FROM ".$NPDS_Prefix."session WHERE username='$cookie[1]'");
      if (sql_num_rows($result)==1) {
         // CheckBox
         settype($attach,'integer');
         settype($user_viewemail,'integer');
         settype($usend_email,'integer');
         settype($uis_visible,'integer');
         settype($user_lnl,'integer');
         settype($raz_avatar,'integer');
         saveuser($uid, $name, $uname, $email, $femail, $url, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $attach, $usend_email, $uis_visible, $user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$MAX_FILE_SIZE,$raz_avatar);
      } else {
         Header("Location: user.php");
      }
   break;
   case 'edithome':
      if ($user)
         edithome();
      else
         Header("Location: index.php");
   break;
   case 'savehome':
      settype($ublockon,'integer');
      savehome($uid, $uname, $theme, $storynum, $ublockon, $ublock);
   break;
   case 'chgtheme':
      if ($user)
         chgtheme();
      else
         Header("Location: index.php");
   break;
   case 'savetheme':
      if (substr($theme,-3)!="_sk")
         $skin='';
      savetheme($uid, $theme_local."+".$skins);
   break;
   case 'editjournal':
      if ($user)
         editjournal();
      else
         Header("Location: index.php");
   break;
   case 'savejournal':
      settype($datetime,'integer');
      savejournal($uid, $journal, $datetime);
   break;
   case 'only_newuser':
      global $CloseRegUser;
      if ($CloseRegUser==0)
         Only_NewUser();
      else {
         include("header.php");
         if (file_exists("static/closed.txt"))
            include("static/closed.txt");
         include("footer.php");
      }
   break;
   default:
      if (!AutoReg()) unset($user);
      main($user);
   break;
}
?>