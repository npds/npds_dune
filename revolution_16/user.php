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
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
function message_error($ibid,$op) {
   include("header.php");
   echo '<h2>'.translate("User").'</h2>';
   echo "<p class=\"lead text-warning text-xs-center\">";
   echo $ibid;
   if (($op=="only_newuser") or ($op=="new user") or ($op=="finish")) {
       hidden_form();
      echo "<input type=\"hidden\" name=\"op\" value=\"only_newuser\" />
      <p class=\"text-xs-center\">
      <button class=\"btn btn-primary\" type=\"submit\" value=\"".translate("Go Back")."\" /><i class=\"fa fa-lg fa-undo\"></i></button>
      </p>
      </form>";
   } else {
      echo "<a class=\"btn btn-primary\" href=\"javascript:history.go(-1)\"title=".translate("Go Back")."><i class=\"fa fa-lg fa-undo\"></i></a>";
   }
   echo "</p>";
   include("footer.php");
}
function message_pass($ibid) {
   include("header.php");
   echo '<span class="text-success">'.$ibid.'</span>';
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
   <ul class="nav nav-tabs"> 
      <li class="nav-item"><a class="nav-link '.$ed_u.'" href="user.php?op=edituser" title="'.translate("Edit User").'" data-toggle="tooltip" ><i class="fa fa-user fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Edit User").'</span></a></li>
      <li class="nav-item"><a class="nav-link '.$ed_j.' " href="user.php?op=editjournal" title="'.translate("Edit Journal").'" data-toggle="tooltip"><i class="fa fa-edit fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Journal").'</span></a></li>';
   include ("modules/upload/upload.conf.php");
   if (($mns) and ($autorise_upload_p)) {
      include ("modules/blog/upload_minisite.php");
      $PopUp=win_upload("popup");
      echo '
      <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="window.open('.$PopUp.')" title="'.translate("Manage my Mini-Web site").'"  data-toggle="tooltip"><i class="fa fa-desktop fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Mini-Web site").'</span></a></li>';
   }
   echo '
      <li class="nav-item"><a class="nav-link '.$ed_h.'" href="user.php?op=edithome" title="'.translate("Change the home").'" data-toggle="tooltip" ><i class="fa fa-edit fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Page").'</span></a></li>
      <li class="nav-item"><a class="nav-link '.$ch_t.'" href="user.php?op=chgtheme" title="'.translate("Change Theme").'"  data-toggle="tooltip" ><i class="fa fa-paint-brush fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Theme").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="modules.php?ModPath=reseaux-sociaux&amp;ModStart=reseaux-sociaux" title="'.translate("Social networks").'"  data-toggle="tooltip" ><i class="fa fa-share-alt-square fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Social networks").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="viewpmsg.php" title="'.translate("Private Message").'"  data-toggle="tooltip" ><i class="fa fa-envelope fa-2x hidden-xl-up"></i><span class="hidden-lg-down">&nbsp;'.translate("Message").'</span></a></li>
      <li class="nav-item"><a class="nav-link " href="user.php?op=logout" title="'.translate("Logout").'" data-toggle="tooltip" ><i class="fa fa-sign-out fa-2x text-danger hidden-xl-up"></i><span class="hidden-lg-down text-danger">&nbsp;'.translate("Logout").'</span></a></li>
   </ul>
   <div class="m-t-1"></div>';
}

function userCheck($uname, $email) {
    global $NPDS_Prefix;
    $stop='';
    if ((!$email) || ($email=='') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email))) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Invalid email")."";
    if (strrpos($email,' ') > 0) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Email addresses do not contain spaces.")."";
    if ((!$uname) || ($uname=='') || (preg_match('#[^a-zA-Z0-9_-]#',$uname))) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Invalid Nickname")."";
    if (strlen($uname) > 25) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("Nickname is too long. It must be less than 25 characters.")."";
    if (preg_match('#^(root|adm|linux|webmaster|admin|god|administrator|administrador|nobody|anonymous|anonimo|an€nimo|operator|dune|netadm)$#i', $uname)) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Name is reserved.")."";
    if (strrpos($uname,' ') > 0) $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("There cannot be any spaces in the Nickname.")."";
    if (sql_num_rows(sql_query("SELECT uname FROM ".$NPDS_Prefix."users WHERE uname='$uname'")) > 0) {
       $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Nickname taken")."";
    }
    if ($uname!="edituser") {
       if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE email='$email'")) > 0) {
          $stop = "<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("ERROR: Email address already registered")."";
       }
    }
    return($stop);
}

function makePass() {
    $makepass="";
    $syllables="er,in,tia,dun,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,";
    $syllables.="mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
    $syllable_array=explode(",", $syllables);
    srand((double)microtime()*1000000);
    for ($count=1;$count<=4;$count++) {
       if (rand()%10 == 1) {
          $makepass .= sprintf("%0.0f",(rand()%50)+1);
       } else {
          $makepass .= sprintf("%s",$syllable_array[rand()%62]);
       }
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
   //]]
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
   <h2>'.translate("User").'</h2>
   <div class="card card-block">
      <h3>'.translate("Notice").'</h3>
      <p>
      '.translate("Account preferences are cookie based.").' '.translate("We don't sell/give to others your personal info.").' '.translate("As a registered user you can").' : 
         <ul>
         <blockquote>
            <li>'.translate("Post comments with your name").'</li>
            <li>'.translate("Send news with your name").'</li>
            <li>'.translate("Have a personal box in the Home").'</li>
            <li>'.translate("Upload personal avatar").'</li>
            <li>'.translate("Select how many news you want in the Home").'</li>
            <li>'.translate("Customize the comments").'</li>
            <li>'.translate("Select different themes").'</li>
            <li>'.translate("some other cool stuff...").'</li>
         </blockquote>
         </ul>
      </p>';
      if (!$memberpass) {
         echo '
      <p class="lead text-danger"><i class="fa fa-exclamation"></i>&nbsp;'.translate("Password will be sent to the email address you enter.").'</p>';
      }
      echo '
   </div>';
      include ("modules/sform/extend-user/extend-user.php");

   $fv_parametres = '
   add_aid: {
      validators: {
         callback: {
            message: "Ce surnom n\'est pas disponible",
            callback: function(value, validator, $field) {
            return $.inArray(value, admin) == -1;
            }
         }
      }
   },
   add_name: {
      validators: {
         callback: {
            message: "Ce nom n\'est pas disponible",
            callback: function(value, validator, $field) {
               return $.inArray(value, adminname) == -1;
            }
         }
      }
   },
   add_email: {
   },
   add_url: {
   },
   pass: {
      validators: {
         notEmpty: {
            message: "The password is required and cannot be empty"
         },
         callback: {
            callback: function(value, validator, $field) {
               var score = 0;
               if (value === "") {
                  return {
                     valid: true,
                     score: null
                  };
               }
               // Check the password strength
               score += ((value.length >= 8) ? 1 : -1);
               // The password contains uppercase character
               if (/[A-Z]/.test(value)) {score += 1;}
               // The password contains uppercase character
               if (/[a-z]/.test(value)) {score += 1;}
               // The password contains number
               if (/[0-9]/.test(value)) {score += 1;}
               // The password contains special characters
               if (/[!#$%&^~*_]/.test(value)) {score += 1;}
               return {
               valid: true,
               score: score    // We will get the score later
               };
            }
         }
      }
   },
   ';

   adminfoot('fv',$fv_parametres,'','1');
   } else {
      header("location: user.php");
   }
}
function hidden_form() {
    global $uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1,$charte,$user_lnl;
    echo "<form action=\"user.php\" method=\"post\">
          <input type=\"hidden\" name=\"uname\" value=\"$uname\" />
          <input type=\"hidden\" name=\"name\" value=\"".removeHack($name)."\" />
          <input type=\"hidden\" name=\"email\" value=\"$email\" />";
    if (!$user_avatar) {$user_avatar="blank.gif";}
    echo "<input type=\"hidden\" name=\"user_avatar\" value=\"$user_avatar\" />
          <input type=\"hidden\" name=\"user_from\" value=\"".StripSlashes(removeHack($user_from))."\" />
          <input type=\"hidden\" name=\"user_occ\" value=\"".StripSlashes(removeHack($user_occ))."\" />
          <input type=\"hidden\" name=\"user_intrest\" value=\"".StripSlashes(removeHack($user_intrest))."\" />
          <input type=\"hidden\" name=\"user_sig\" value=\"".StripSlashes(removeHack($user_sig))."\" />
          <input type=\"hidden\" name=\"user_viewemail\" value=\"$user_viewemail\" />
          <input type=\"hidden\" name=\"pass\" value=\"".removeHack($pass)."\" />
          <input type=\"hidden\" name=\"user_lnl\" value=\"".removeHack($user_lnl)."\" />";
    echo "<input type=\"hidden\" name=\"C1\" value=\"".StripSlashes(removeHack($C1))."\" />
          <input type=\"hidden\" name=\"C2\" value=\"".StripSlashes(removeHack($C2))."\" />
          <input type=\"hidden\" name=\"C3\" value=\"".StripSlashes(removeHack($C3))."\" />
          <input type=\"hidden\" name=\"C4\" value=\"".StripSlashes(removeHack($C4))."\" />
          <input type=\"hidden\" name=\"C5\" value=\"".StripSlashes(removeHack($C5))."\" />
          <input type=\"hidden\" name=\"C6\" value=\"".StripSlashes(removeHack($C6))."\" />
          <input type=\"hidden\" name=\"C7\" value=\"".StripSlashes(removeHack($C7))."\" />
          <input type=\"hidden\" name=\"C8\" value=\"".StripSlashes(removeHack($C8))."\" />
          <input type=\"hidden\" name=\"M1\" value=\"".StripSlashes(removeHack($M1))."\" />
          <input type=\"hidden\" name=\"M2\" value=\"".StripSlashes(removeHack($M2))."\" />
          <input type=\"hidden\" name=\"T1\" value=\"".StripSlashes(removeHack($T1))."\" />
          <input type=\"hidden\" name=\"T2\" value=\"".StripSlashes(removeHack($T2))."\" />
          <input type=\"hidden\" name=\"B1\" value=\"".StripSlashes(removeHack($B1))."\" />";
}
function confirmNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass, $vpass,$user_lnl,$C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1) {
    global $smilies, $short_user, $minpass, $memberpass;
    $uname=strip_tags($uname);
    if ($user_viewemail!=1) {$user_viewemail='0';}
    $stop=userCheck($uname, $email);
    if ($memberpass) {
       if ((isset($pass)) and ("$pass" != "$vpass")) {
          $stop="<p class=\"text-xs-center\"><i class=\"fa fa-exclamation\"></i>&nbsp;".translate("Both passwords are different. They need to be identical.")."</p><br />";
       } elseif (strlen($pass) < $minpass) {
          $stop="<p class=\"text-xs-center\"><i class=\"fa fa-exclamation\"></i>&nbsp;".translate("Sorry, your password must be at least")." <strong>$minpass</strong> ".translate("characters long")."</p><br />";
       }
    }
    if (!$stop) {
       include("header.php");
      echo '<h2>'.translate("User").'</h2>';
          echo '<h2><i class="fa fa-user"></i>&nbsp;Votre fiche d\'inscription</h2>';
          include ("modules/sform/extend-user/aff_extend-user.php");
          hidden_form();
          global $charte;
          if (!$charte) {
             echo "<p class=\"lead text-warning text-xs-center\"><i class=\"fa fa-exclamation\"></i>&nbsp;".translate("You must accept the terms of use of this website")."</p>";
             echo "<input type=\"hidden\" name=\"op\" value=\"only_newuser\">
            <input class=\"btn btn-secondary\" type=\"submit\" value=\"".translate("Go Back")."\" />
            </form>";
          } else {
             echo '
            <input type="hidden" name="op" value="finish">
            <input class="btn btn-primary" type="submit" value="'.translate("Finish").'" />
         </form>';
         }
       
       include("footer.php");
    } else {
       message_error($stop,"new user");
    }
}
function finishNewUser($uname, $name, $email, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $pass,$user_lnl, $C1,$C2,$C3,$C4,$C5,$C6,$C7,$C8,$M1,$M2,$T1,$T2,$B1) {
    global $NPDS_Prefix;
    global $makepass, $system, $adminmail, $sitename, $AutoRegUser, $memberpass, $gmt;
    $stop=userCheck($uname, $email);
    $user_regdate = time()+($gmt*3600);
    $stop=userCheck($uname, $email);
    if (!$stop) {
       include("header.php");
       if (!$memberpass) {
          $makepass=makepass();
       } else {
          $makepass=$pass;
       }
       if (!$system)
          $cryptpass=crypt($makepass,$makepass);
       else
          $cryptpass=$makepass;

       $result = sql_query("INSERT INTO ".$NPDS_Prefix."users VALUES (NULL,'$name','$uname','$email','','','$user_avatar','$user_regdate','$user_occ','$user_from','$user_intrest','$user_sig','$user_viewemail','','','$cryptpass','10','','0','0','0','','0','','','10','0','0','1','0','','','$user_lnl')");
       list($usr_id) = sql_fetch_row(sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$uname'"));
       $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_extend VALUES ('$usr_id','$C1','$C2','$C3','$C4','$C5','$C6','$C7','$C8','$M1','$M2','$T1','$T2', '$B1')");
       if ($user_sig) {
          $attach = 1;
       } else {
          $attach = 0;
       }
       if (($AutoRegUser==1) or (!isset($AutoRegUser))) {
          $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_status VALUES ('$usr_id','0','$attach','0','1','1','')");
       } else {
          $result = sql_query("INSERT INTO ".$NPDS_Prefix."users_status VALUES ('$usr_id','0','$attach','0','1','0','')");
       }
       if ($result) {
          if (($system==1) or ($memberpass)) {
	   echo '<h2>'.translate("User").'</h2>';
          echo '<h2><i class="fa fa-user"></i>&nbsp;Inscription</h2>';
                echo "<p class=\"lead\">".translate("Your Password is: ")."<strong>$makepass</strong></p>";
                echo "<p class=\"lead\">".translate("You can change it after you login at")." : <a href=\"user.php?op=login&uname=$uname&pass=$makepass\"><strong>$sitename</strong></a></p>";
             
          } else {
             $message = "".translate("Welcome to")." $sitename !\n\n".translate("You or someone else has used your email account")." ($email) ".translate("to register an account at")." $sitename.\n\n".translate("The following is the member information:")."\n".translate("-Nickname: ")." $uname\n".translate("-Password: ")." $makepass\n\n";
             include ("signat.php");
             $subject="".translate("User Password for")." $uname";
             send_email($email, $subject, $message, "", true, "html");

      echo '<h2>'.translate("User").'</h2>';
          echo '<h2><i class="fa fa-user"></i>&nbsp;Inscription</h2>';	   
                echo '<p class="lead text-info"><i class="fa fa-exclamation"></i>&nbsp;'.translate("You are now registered. You should receive your password at the email account you provided.").'</p>';

          }
          //------------------------------------------------
          if (file_exists("modules/include/new_user.inc")) {
             include ("modules/include/new_user.inc");
             global $gmt;
             $time = date(translate("dateinternal"),time()+($gmt*3600));
             $message = meta_lang(AddSlashes(str_replace("\n","<br />", $message)));
             $sql = "INSERT INTO ".$NPDS_Prefix."priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
             $sql .= "VALUES ('', '$sujet', '$emetteur_id', '$usr_id', '$time', '$message')";
             sql_query($sql);
          }
          //------------------------------------------------
//modif debut envoyer un mel à l'admin
   send_email($adminmail,"Inscription sur $sitename","Infos :
      Nom : $name
      ID : $uname
      Password : $makepass
      Email : $email", false,"text");
//modif pour envoyer un mel à l'admin
       }
       include("footer.php");
    } else {
       message_error($stop, "finish");
    }
}

function userinfo($uname) {
   global $NPDS_Prefix;
   global $user, $sitename, $smilies, $short_user, $site_font;
   global $name, $email, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal;

   $uname=removeHack($uname);
   $result = sql_query("SELECT uid, name, femail, url, bio, user_avatar, user_from, user_occ, user_intrest, user_sig, user_journal, mns FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
   list($uid, $name, $femail, $url, $bio, $user_avatar, $user_from, $user_occ, $user_intrest, $user_sig, $user_journal, $mns) = sql_fetch_row($result);
   if (!$uid) {
      header ("location: index.php");
   }
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
    $op="userinfo";

   if (stristr($user_avatar,"users_private")) {
      $direktori='';
   } else {
      global $theme;
      $direktori="images/forum/avatar/";
      if (function_exists("theme_image")) {
         if (theme_image("forum/avatar/blank.gif"))
            $direktori="themes/$theme/images/forum/avatar/";
      }
   }

      $my_rsos=array();
      $socialnetworks=array(); $posterdata_extend=array();$res_id=array();$my_rs='';
      if (!$short_user) {
         $posterdata_extend = get_userdata_extend_from_id($uid);
         include('modules/reseaux-sociaux/reseaux-sociaux.conf.php');
         if ($posterdata_extend['M2']!='') {
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
                     $my_rs.='<a class="m-r-1" href="';
                     if($v1[2]=='skype') $my_rs.= $v1[1].$y1[1].'?chat'; else $my_rs.= $v1[1].$y1[1];
                     $my_rs.= '" target="_blank"><i class="fa fa-'.$v1[2].' fa-2x text-primary"></i></a> ';
                     break;
                  } 
                  else $my_rs.='';
               }
            }
            $my_rsos[]=$my_rs;
         }
         else $my_rsos[]='';
      }

   $posterdata = get_userdata_from_id($uid);
   $useroutils = '';
      if ($user) {
         $useroutils .= '<a class=" text-primary m-r-1" href="powerpack.php?op=instant_message&amp;to_userid='.$posterdata["uname"].'" ><i class="fa fa-2x fa-envelope-o" title="'.translate("Send internal Message").'" data-toggle="tooltip"></i></a>&nbsp;';
      }
      if ($posterdata['femail']!='') {
         $useroutils .= '<a class=" text-primary m-r-1" href="mailto:'.anti_spam($posterdata['femail'],1).'" target="_blank" ><i class="fa fa-at fa-2x" title="'.translate("Email").'" data-toggle="tooltip"></i></a>&nbsp;';
      }
      if ($posterdata['url']!='') {
         if (strstr('http://', $posterdata['url']))
            $posterdata['url'] = 'http://' . $posterdata['url'];
         $useroutils .= '<a class=" text-primary m-r-1" href="'.$posterdata['url'].'" target="_blank" ><i class="fa fa-2x fa-external-link" title="'.translate("Visit this Website").'" data-toggle="tooltip"></i></a>&nbsp;';
      }
      if ($posterdata['mns']) {
          $useroutils .= '<a class=" text-primary m-r-1" href="minisite.php?op='.$posterdata['uname'].'" target="_blank" target="_blank" ><i class="fa fa-2x fa-desktop" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip"></i></a>&nbsp;';
      }

   echo '
   <div class="row">
      <div class="col-sm-2"><img src="'.$direktori.$user_avatar.'" class=" img-circle center-block" /></div>
      <div class="col-sm-10">
         <h2>'.translate("User").'&nbsp;<span class="text-muted">'.$uname.'</span></h2>';
   if ($uname !== $cookie[1])
      echo $useroutils;
      echo $my_rsos[0];
   if ($uname == $cookie[1])
//            <h3>'.translate("Welcome to").' '.$sitename.'</h3>

      echo '
         <p class="lead">'.translate("This is your personal page").'</p>';
   echo '
      </div>
   </div>
   <hr />';

   if ($uname == $cookie[1])
      nav($mns);

   echo '
   <div class="card card-block">
      <div class="row">';
      if ($posterdata_extend['C7']!='') echo '
         <div class="col-md-6">'; else
         echo '
         <div class="col-md-12">';
   include("modules/sform/extend-user/aff_extend-user.php");
   echo '
         </div>';
   
if ($posterdata_extend['C7']!='') {
$content = '';
include('modules/geoloc/geoloc_conf.php'); 
$content .='
<div class="col-md-6">
<div id="map_user" style="width:100%; height:400px;"></div>';
if (((!stristr($_SERVER['QUERY_STRING'],"geoloc")) || (stristr($_SERVER['PHP_SELF'],"admin.php")) || (stristr($_SERVER['PHP_SELF'],"user.php"))))  {
$content .='
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.exp&amp;sensor=false&amp;language=fr"></script>
<script type="text/javascript" src="modules/geoloc/include/fontawesome-markers.min.js"></script>
<script type="text/javascript">
//<![CDATA[
   var 
   map_u,
   mapdivu = document.getElementById("map_user"),
   icon_u = {
      path: fontawesome.markers.USER,
      scale: '.$acg_sc.',
      strokeWeight: '.$acg_t_ep.',
      strokeColor: "'.$acg_t_co.'",
      strokeOpacity: '.$acg_t_op.',
      fillColor: "'.$acg_f_co.'",
      fillOpacity: '.$acg_f_op.',
   };

   function geoloc_loaduser() {
   
   //==> carte du bloc
   if (document.getElementById("map_bloc")) {
      var 
      map_b,
      mapdivbl = document.getElementById("map_bloc"),
      icon_bl = {
         url: "'.$ch_img.$img_mbgb.'",
         size: new google.maps.Size('.$w_ico_b.','.$h_ico_b.'),
         origin: new google.maps.Point(0, 0),
         anchor: new google.maps.Point(0, 0),
         scaledSize: new google.maps.Size('.$w_ico_b.', '.$h_ico_b.')
      };

      map_b = new google.maps.Map(mapdivbl,{
         center: new google.maps.LatLng(45, 0),
         zoom :3,
         zoomControl:false,
         streetViewControl:false,
         mapTypeControl: false,
         disableDoubleClickZoom: true 
      });
      map_b.setMapTypeId(google.maps.MapTypeId.'.$cartyp_b.');
      function createMarkerB(point_b) {
         var marker_b = new google.maps.Marker({
            position: point_b,
            map: map_b,
            icon: icon_bl
         })
         return marker_b;
     }
        //== Fonction qui traite le fichier JSON ==
      $.getJSON("modules/geoloc/include/data.json", {}, function(data){
         $.each(data.markers, function(i, item){
            var point_b = new google.maps.LatLng(item.lat,item.lng);
            var marker_b = createMarkerB(point_b);
         });
      });
   };
   //<== carte du bloc
   
      map_u = new google.maps.Map(mapdivu,{
         center: new google.maps.LatLng('.$posterdata_extend['C7'].', '.$posterdata_extend['C8'].'),
         zoom :7,
         zoomControl:true,
         streetViewControl:true,
         mapTypeControl: true,
         scrollwheel: false,
         disableDoubleClickZoom: true 
      });
      map_u.setMapTypeId(google.maps.MapTypeId.'.$cartyp_b.');
      function createMarkerU(point_u) {
         var marker_u = new google.maps.Marker({
            position: point_u,
            map: map_u,
            title: "'.$uname.'",
            icon: icon_u
         })
         return marker_u;
      }
      var point_u = new google.maps.LatLng('.$posterdata_extend['C7'].','.$posterdata_extend['C8'].');
      var marker_u = createMarkerU(point_u);
   }
   $(document.body).attr("onload", "geoloc_loaduser()");
//]]>
</script>
';
}
$content .='<div class="m-t-1"><a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg"></i>&nbsp;[french]Carte[/french][english]Map[/english][chinese]&#x5730;&#x56FE;[/chinese]</a>';
if($admin)
$content .= '&nbsp;&nbsp;<a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=geoloc&amp;ModStart=admin/geoloc_set"><i class="fa fa-cogs fa-lg"></i>&nbsp;[french]Admin[/french] [english]Admin[/english] [chinese]Admin[/chinese]</a>';
$content .= '</div></div>';
$content = aff_langue($content);
echo $content;
}

   echo '
      </div>
   </div>';

/*   if ($uname == $cookie[1]) {
   echo '
   <div class="card text-xs-center">
      <div class="card-header">
         <img src="'.$direktori.$user_avatar.'" class="n-ava  thumbnail" />
         <p class="card-text card-title "></p>
      </div>
      <div class="card-block">
         <h3 class="card-title">'.$name.' <span class="text-muted">alias</span> '.$uname.'</h3>
         <p class="card-text">You can contact me @ '.$email.'</p>
         <p class="card-text">Don not forget to visit <a href="'.$url.'" class="oo">my web-site</a>';
   if ($mns) {echo ' OR my <a href="minisite.php?op='.$uname.'" target="_blank">'.translate("Mini-Web site").'</a>';}
   echo '
         </p>
      </div>
      <div class="card-footer text-muted">
         '.$user_sig.'
      </div>
   </div>';
   }; */

    echo '
    <br />
    <h4>'.translate("Online journal for").' '.$uname.'.</h4>
    <div id="online_user_journal" class="card card-block">'.$user_journal.'</div>';
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
   <h4>'.translate("Last 10 comments by").' '.$uname.'.</h4>
   <div id="last_ten_comment" class="card card-block">';
   $url='';
   $result=sql_query("SELECT topic_id, forum_id, post_text, post_time FROM ".$NPDS_Prefix."posts WHERE forum_id<0 and poster_id='$uid' ORDER BY post_time DESC LIMIT 0,10");
   while(list($topic_id, $forum_id, $post_text, $post_time) = sql_fetch_row($result)) {
      $url=str_replace("#topic#",$topic_id,$filelist[$forum_id]);
      echo "<p><a href=\"".$url."\">".translate("Posted: ").convertdate($post_time)."</a></p>";
      $message=smilie(stripslashes($post_text));
      $message = aff_video_yt($message);
      $message = str_replace('[addsig]','',$message);
      if (stristr($message,"<a href")) {
         $message=preg_replace('#_blank(")#i','_blank\1 class=\1noir\1',$message);
      }
      echo $message.'<hr />';
   }
   echo '
    </div>
    <h4>'.translate("Last 10 news submissions sent by").' '.$uname.'.</h4>
    <div id="last_ten_comment" class="card card-block">';

   $xtab=news_aff("libre", "WHERE informant='$uname' ORDER BY sid DESC LIMIT 10", '', 10);
   $story_limit=0;
   while (($story_limit<10) and ($story_limit<sizeof($xtab))) {
      list($sid, $catid, $aid, $title) = $xtab[$story_limit];
      $story_limit++;
      echo '<p><a href="article.php?sid='.$sid.'">'.aff_langue($title).'</a></p>';
   }
   echo '
   </div>
   <hr />
   <p class="text-xs-right text-muted font-italic">'.$user_sig.'</p>';
   include("footer.php");
}

function main($user) {
   global $stop, $smilies;
   if (!isset($user)) {
      include("header.php");
      echo '<h2>'.translate("User").'</h2>';
       if ($stop==99) {
          echo '<p class="lead text-danger text-xs-center"><i class="fa fa-exclamation"></i>&nbsp;'.translate("User not yet allowed by Administrator").'</p>';
       } elseif ($stop) {
          echo '<p class="lead text-danger text-xs-center"><i class="fa fa-exclamation"></i>&nbsp;'.translate("Incorrect Login!").'</p>';
       }
       if (!$user) {
          echo '
          <h3><a href="user.php?op=only_newuser" role="button" title="'.translate("New User").'"><i class="fa fa-user-plus"></i>&nbsp;'.translate("New User").'</a></h3>
          <h3><i class="fa fa-sign-in fa-lg"></i>&nbsp;'.translate("Connection").'</h3>
          <div class="card card-block">
          <form class="" role="form" action="user.php" method="post" name="userlogin">
             <div class="form-group row">
               <label for="inputuser" class="form-control-label col-sm-4">'.translate("Nickname").'</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="uname" id="inputuser" placeholder="'.translate("Nickname").'">
               </div>
            </div>
            <div class="form-group row">
               <label for="inputPassuser" class="form-control-label col-sm-4">'.translate("Password").'</label>
               <div class="col-sm-8">
                  <input type="password" class="form-control" name="pass" id="inputPassuser" placeholder="'.translate("Password").'">
                  <span class="help-block small"><a href="user.php?op=forgetpassword" role="button" title="'.translate("Lost your Password?").'">'.translate("Lost your Password?").'</a></span>
               </div>
            </div>
            <input type="hidden" name="op" value="login" />
            <div class="form-group row">
               <div class="col-sm-offset-4 col-sm-8">
                  <button class="btn btn-primary" type="submit" title="'.translate("Submit").'"><i class="fa fa-lg fa-check"></i>&nbsp;'.translate("Submit").'</button>
               </div>
            </div>
         </form>
         </div>';

          echo "<script type=\"text/javascript\">\n//<![CDATA[\ndocument.userlogin.uname.focus();\n//]]>\n</script>";

          // include externe file from modules/include for functions, codes ...
 /*         if (file_exists("modules/include/user.inc")) {
             
             include ("modules/include/user.inc");
             
          }*/
       }
       include("footer.php");
    } elseif (isset($user)) {
       $cookie=cookiedecode($user);
       userinfo($cookie[1]);
    }
}

function logout() {
    global $NPDS_Prefix;
    global $user, $cookie;
    if ($cookie[1]!="") {
       sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE username='$cookie[1]'");
    }
    setcookie("user","",0);
    unset($user);
    setcookie("user_language","",0);
    unset($user_language);
    Header("Location: index.php");
}

function ForgetPassword() {
   include("header.php");
   echo '
   <h2>'.translate("User").'</h2>
   <h3 class="lead text-warning text-xs-center">'.translate("Lost your Password?").'</h3>
   <p class="lead">'.translate("No problem. Just type your Nickname, the new password you want and click on send button to recieve a email with the confirmation code.").'</p>
   <form action="user.php" method="post">
      <div class="form-group row">
         <div class="col-sm-2">
            <label for="inputuser" class="control-label">'.translate("Nickname: ").'</label>
         </div>
         <div class="col-sm-4">
            <input type="text" class="form-control"  name="uname" id="inputuser" placeholder="'.translate("Nickname").'" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-2">
            <label for="inputPassuser" class="control-label">'.translate("Password: ").'</label>
         </div>
         <div class="col-sm-4">
            <input type="password" class="form-control" name="code" id="inputPassuser" placeholder="'.translate("Password").'" />
         </div>
      </div>
         <input type="hidden" name="op" value="mailpasswd" />
         <div class="form-group">
         <div class="col-sm-offset-2 col-sm-1">
            <input class="btn btn-primary" type="submit" value ="'.translate("Send").'"  />
         </div>
      </div>
   </form>';
   include ("footer.php");
}

function mail_password($uname, $code) {
    global $NPDS_Prefix;
    global $sitename, $nuke_url;
    $uname=removeHack(stripslashes(htmlspecialchars(urldecode($uname),ENT_QUOTES,cur_charset)));
    $result = sql_query("SELECT uname,email,pass FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
    $tmp_result=sql_fetch_row($result);
    if (!$tmp_result) {
       message_error(translate("Sorry, no corresponding user info was found")."<br /><br />","");
    } else {
       $host_name = getip();
       list($uname,$email, $pass) = $tmp_result;
       // On envoie une URL avec dans le contenu : username, email, le MD5 du passwd retenu et le timestamp
       $url="$nuke_url/user.php?op=validpasswd&code=".urlencode(encrypt($uname)."#fpwd#".encryptK($email."#fpwd#".$code."#fpwd#".time(),$pass));

       $message = "".translate("The user account")." '$uname' ".translate("at")." $sitename ".translate("has this email associated with it.")."\n\n";
       $message.= translate("A web user from")." $host_name ".translate("has just requested a Confirmation to change the password.")."\n\n".translate("Your Confirmation URL is:")." <a href=\"$url\">$url</a> \n\n".translate("If you didn't ask for this, don't worry. Just delete this Email.")."\n\n";
       include("signat.php");

       $subject="".translate("Confirmation Code for")." $uname";

       send_email($email, $subject, $message, "", true, "html");

       message_pass('<p class="lead text-xs-center"><i class="fa fa-exclamation"></i>&nbsp;'.translate("Confirmation Code for").' '.$uname.' '.translate("mailed.").'');

       Ecr_Log("security", "Lost_password_request : ".$uname, '');
    }
}

function valid_password ($code) {
   global $NPDS_Prefix;

   $ibid=explode("#fpwd#",$code);
   $result = sql_query("SELECT email,pass FROM ".$NPDS_Prefix."users WHERE uname='".decrypt($ibid[0])."'");
   list($email, $pass) = sql_fetch_row($result);
   if ($email!="") {
      $ibid=explode("#fpwd#",decryptK($ibid[1],$pass));
      if ($email==$ibid[0]) {
         include("header.php");
         echo '
      <p class="lead">'.translate("Lost your Password?").'</p>
      <p>'.translate("To valid your new password request, just re-type it.").'</p>
      <form action="user.php" method="post">
         <div class="form-group">
         <div class="col-sm-2">
         <label class="form-control-label">'.translate("Password: ").'</label>
         </div>
         <div class="col-sm-2">
         <input type="password" class="form-control" name="passwd" placeholder="'.translate("Password").'">
         </div>
         </div>
         <input type="hidden" name="op" value="updatepasswd" />
         <input type="hidden" name="code" value="'.$code.'" />
         <div class="form-group">
         <div class="col-sm-1">
         <input class="btn btn-primary" type="submit" value="'.translate("Submit").'" />
         </div>
         </div>
      </form>';
         include ("footer.php");
      } else {
         message_pass(translate("Error"));
         Ecr_Log("security", "Lost_password_valid NOK Mail not match : ".$ibid[0], "");
      }
   } else {
      message_pass(translate("Error"));
      Ecr_Log("security", "Lost_password_valid NOK Bad hash : ".$ibid[0], "");
   }
}

function update_password ($code, $passwd) {
    global $system;
    global $NPDS_Prefix;

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
                if (!$system) {
                   $cryptpass=crypt($ibid[1],$ibid[1]);
                } else {
                   $cryptpass=$ibid[1];
                }
                sql_query("UPDATE ".$NPDS_Prefix."users SET pass='$cryptpass' WHERE uname='$uname'");
                message_pass("<p class=\"lead text-xs-center\"><i class=\"fa fa-exclamation\"></i>&nbsp;".translate ("Password update, please re-connect you.")."</p>");
                Ecr_Log("security", "Lost_password_update OK : ".$uname, "");
             } else {
                message_pass(translate("Error"));
                Ecr_Log("security", "Lost_password_update Password not match : ".$uname, "");
             }
          } else {
             message_pass(translate("Error"));
             Ecr_Log("security", "Lost_password_update NOK Time > 24H00 : ".$uname, "");
          }
       } else {
          message_pass(translate("Error"));
          Ecr_Log("security", "Lost_password_update NOK Mail not match : ".$uname, "");
       }
    } else {
       message_pass(translate("Error"));
       Ecr_Log("security", "Lost_password_update NOK Empty Mail or bad user : ".$uname, "");
    }
}

function docookie($setuid, $setuname, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax, $user_langue, $skin) {
    $info = base64_encode("$setuid:$setuname:".md5($setpass).":$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax:$skin");
    global $user_cook_duration;
    if ($user_cook_duration<=0) {$user_cook_duration=1;}
    $timeX=time()+(3600*$user_cook_duration);
    setcookie("user","$info",$timeX);
    if ($user_langue!='') {
       setcookie('user_language',"$user_langue",$timeX);
    }
}

function login($uname, $pass) {
    global $NPDS_Prefix;
    global $setinfo, $system;

    $result = sql_query("SELECT pass, uid, uname, storynum, umode, uorder, thold, noscore, ublockon, theme, commentmax, user_langue FROM ".$NPDS_Prefix."users WHERE uname='$uname'");
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
          if (!$system) {
             $passwd=crypt($pass,$dbpass);
          } else {
             $passwd=$pass;
          }
          if (strcmp($dbpass,$passwd)!=0) {
             $pass=utf8_decode($pass);
             if (!$system) {
                $passwd=crypt($pass,$dbpass);
             } else {
                $passwd=$pass;
             }
             if (strcmp($dbpass,$passwd)!=0) {
                Header("Location: user.php?stop=1");
                return;
             }
          }
          docookie($setinfo['uid'], $setinfo['uname'], $passwd, $setinfo['storynum'], $setinfo['umode'], $setinfo['uorder'], $setinfo['thold'], $setinfo['noscore'], $setinfo['ublockon'], $setinfo['theme'], $setinfo['commentmax'], $setinfo['user_langue'],$skin);
       } else {
          if (!$system) {
             $passwd=crypt($pass,$dbpass);
          } else {
             $passwd=$pass;
          }
          if (strcmp($dbpass,$passwd)==0) {
             docookie($setinfo['uid'], $setinfo['uname'], $passwd, $setinfo['storynum'], $setinfo['umode'], $setinfo['uorder'], $setinfo['thold'], $setinfo['noscore'], $setinfo['ublockon'], $setinfo['theme'], $setinfo['commentmax'], $setinfo['user_langue'],$skin);
          } else {
             Header("Location: user.php?stop=1");
             return;
          }
       }

       $ip = getip();
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."session WHERE host_addr='$ip' AND guest='1'");
       if (sql_num_rows($result)==1) {
          sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE host_addr='$ip' AND guest='1'");
       }

       Header("Location: index.php");
    } else {
       Header("Location: user.php?stop=1");
    }
}

function edituser() {
    global $NPDS_Prefix;
    global $user, $smilies, $short_user, $subscribe, $member_invisible, $avatar_size;
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
    global $NPDS_Prefix;
    global $user, $userinfo, $system, $minpass;
    $cookie=cookiedecode($user);
    $check = $cookie[1];
    $result = sql_query("SELECT uid, email FROM ".$NPDS_Prefix."users WHERE uname='$check'");
    list($vuid, $vemail) = sql_fetch_row($result);
    if (($check == $uname) AND ($uid == $vuid)) {
        if ((isset($pass)) && ("$pass" != "$vpass")) {
           message_error("<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("Both passwords are different. They need to be identical.")."<br /><br />","");
        } elseif (($pass != '') && (strlen($pass) < $minpass)) {
           message_error("<i class=\"fa fa-exclamation\"></i>&nbsp;".translate("Sorry, your password must be at least")." <strong>$minpass</strong> ".translate("characters long")."<br /><br />","");
        } else {
           $stop=userCheck('edituser', $email);
           if (!$stop)  {
              if ($bio) { $bio=FixQuotes(strip_tags($bio)); }
              if ($attach) {$t = 1;} else {$t = 0;}
              if ($user_viewemail) {$a = 1;} else {$a = 0;}
              if ($usend_email) {$u = 1;} else {$u = 0;}
              if ($uis_visible) {$v = 0;} else {$v = 1;}
              if ($user_lnl) {$w = 1;} else {$w = 0;}
              if ($url!="") {
                 if (!substr_count($url,'http://')) {$url='http://'.$url;}
                 if (trim($url)=='http://') {$url='';}
              }

              include_once("modules/upload/upload.conf.php");
              global $avatar_size;
              if (!$avatar_size) {$avatar_size='80*100';}
              $avatar_limit=explode("*",$avatar_size);
              if ($DOCUMENTROOT!='') {
                 $rep=$DOCUMENTROOT;
              } else {
                 global $DOCUMENT_ROOT;
                 if ($DOCUMENT_ROOT) {
                    $rep=$DOCUMENT_ROOT;
                 } else {
                    $rep=$_SERVER['DOCUMENT_ROOT'];
                 }
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
                             } else {
                                $user_dir=$racine.'/users_private/';
                             }
                          }
                       } else {
                          $user_dir=$racine.'/users_private/';
                       }
                       if ($upload->saveAs($uname.'.'.$suffix ,$rep.$user_dir, 'B1',true)) {
                          $old_user_avatar=$user_avatar;
                          $user_avatar=$user_dir.$uname.'.'.$suffix;
                          $img_size = @getimagesize($rep.$user_avatar);
                          if (($img_size[0]>$avatar_limit[0]) or ($img_size[1]>$avatar_limit[1])) {
                             $raz_avatar=true;
                          }
                          if ($racine=="") $user_avatar=substr($user_avatar,1);
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
    } else {
       Header("Location: index.php");
    }
}

function edithome() {
   global $user, $Default_Theme;
   include ("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);
   if ($userinfo['theme']=='') {
      $userinfo['theme'] = "$Default_Theme";
   }
   echo '
   <h2>'.translate("Change the home").'</h2>
   <form action="user.php" method="post">
   <div class="form-group row">
      <label class="form-control-label col-sm-7" for="storynum">'.translate("News number in the Home").' (max. 127) :</label>
      <div class="col-sm-5">
         <input class="form-control" type="number" min="0" max="127" name="storynum" value="'.$userinfo['storynum'].'" />
      </div>
   </div>';
   if ($userinfo['ublockon']==1) $sel = 'checked="checked"';
   else $sel = '';
   echo '
   <div class="form-group row">
      <div class="col-sm-10">
         <div class="checkbox">
            <label>
               <input type="checkbox" name="ublockon" value="1" '.$sel.' />&nbsp;'.translate("Activate Personal Menu").'
            </label>
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
   include ("footer.php");
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
        docookie($userinfo['uid'],$userinfo['uname'],$userinfo['pass'],$userinfo['storynum'],$userinfo['umode'],$userinfo['uorder'],$userinfo['thold'],$userinfo['noscore'],$userinfo['ublockon'],$userinfo['theme'],$userinfo['commentmax'], '',$skin);
        // Include cache manager for purge cache Page
        $cache_obj = new cacheManager();
        $cache_obj->UsercacheCleanup();
        Header("Location: user.php?op=edithome");
    } else {
        Header("Location: index.php");
    }
}

function chgtheme() {
   global $user;
   include ("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);
   echo '
   <h2>'.translate("Change Theme").'</h2>
   <form class="" role="form" action="user.php" method="post">
      <div class="form-group row">
         <label class="control-label col-lg-5" for="theme">'.translate("Select One Theme").'</label>
         <div class="col-lg-7">
            <select class="c-select form-control" name="theme">';
   include("themes/list.php");
   $themelist = explode(' ', $themelist);
   $thl= sizeof($themelist);
   for ($i=0; $i < $thl; $i++) {
      if ($themelist[$i]!='') {
         echo '
               <option value="'.$themelist[$i].'" ';
         if ((($userinfo['theme']=='') && ($themelist[$i]==$Default_Theme)) || ($userinfo['theme']==$themelist[$i])) echo 'selected="selected"';
         echo '>'.$themelist[$i].'</option>';
      }
   }
   if ($userinfo['theme']=='') $userinfo['theme'] = 'Default_Theme';
   echo '
            </select>
            <p class="help-block">
               <span>'.translate("This option will change the look for the whole site.").'</span> 
               <span>'.translate("The changes will be valid only to you.").'</span> 
               <span>'.translate("Each user can view the site with different theme.").'</span>
            </p>
         </div>
      </div>';
      
   $skinable = substr($userinfo['theme'], -3);//no need ?

   $handle=opendir('themes/_skins');
   while (false!==($file = readdir($handle))) {
      if ( ($file[0]!=='_') and (!strstr($file,'.')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
         $skins[] = array('name'=> $file, 'description'=> '', 'thumbnail'=> $depotskin.$file.'/thumbnail','preview'=> $depotskin.$file.'/','css'=> $depotskin.$file.'/bootstrap.css','cssMin'=> $depotskin.$file.'/bootstrap.min.css','cssxtra'=> $depotskin.$file.'/extra.css','scss'=> $depotskin.$file.'/_bootswatch.scss','scssVariables'=> $depotskin.$file.'/_variables.scss');
      }
   }
   closedir($handle);

      echo '
      <div id="skin_choice" class="form-group row">
         <label class="control-label col-lg-5" for="skin">'.translate("Select one skin").'</label>
         <div class="col-lg-7">
            <select class="c-select form-control" name="skin">';
   $cookie=cookiedecode($user);
   foreach ($skins as $k => $v) {
      echo '
               <option value="'.$skins[$k]['name'].'" ';
      if ($skins[$k]['name'] == $cookie[11]) echo 'selected="selected"';
      echo '>'.$skins[$k]['name'].'</option>';
   }
      echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-lg-offset-5 col-lg-7 ">
            <input type="hidden" name="uname" value="'.$userinfo['uname'].'" />
            <input type="hidden" name="uid" value="'.$userinfo['uid'].'" />
            <input type="hidden" name="op" value="savetheme" />
            <input class="btn btn-primary" type="submit" value="'.translate("Save Changes!").'" />
         </div>
      </div>
   </form>';
   include ("footer.php");
}

function savetheme($uid, $theme, $skin) {
   global $NPDS_Prefix, $user;
   $skinable = substr($theme, -3);
   if($skinable!=='_sk') $skin='';
   $cookie=cookiedecode($user);
   $result = sql_query("SELECT uid FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
   list($vuid) = sql_fetch_row($result);
   if ($uid == $vuid) {
      sql_query("UPDATE ".$NPDS_Prefix."users SET theme='$theme' WHERE uid='$uid'");
      $userinfo=getusrinfo($user);
      docookie($userinfo['uid'],$userinfo['uname'],$userinfo['pass'],$userinfo['storynum'],$userinfo['umode'],$userinfo['uorder'],$userinfo['thold'],$userinfo['noscore'],$userinfo['ublockon'],$userinfo['theme'],$userinfo['commentmax'],'',$skin);
      // Include cache manager for purge cache Page
      $cache_obj = new cacheManager();
      $cache_obj->UsercacheCleanup();
      Header("Location: user.php");
   } else {
      Header("Location: index.php");
   }
}

function editjournal(){
   global $user;
   include("header.php");
   $userinfo=getusrinfo($user);
   nav($userinfo['mns']);
   echo '
   <h2>'.translate("Edit your journal").'</h2>
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
         <div class="col-xs-12">
            <div class="checkbox">
               <label>
               <input type="checkbox" name="datetime" value="1" />&nbsp;'.translate("Add date and time stamp").'
               </label>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-xs-12">
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
         $journalentry .= date(translate("dateinternal"),time()+($gmt*3600));
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
    case "updatepasswd":
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
         savetheme($uid, $theme,$skin);
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
         if ($CloseRegUser==0) {
            Only_NewUser();
         } else {
            include("header.php");
            if (file_exists("static/closed.txt"))
               include("static/closed.txt");
            include("footer.php");
         }
         break;
    default:
         if (!AutoReg()) { unset($user); }
         main($user);
         break;
}
?>