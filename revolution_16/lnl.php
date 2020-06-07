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

function SuserCheck($email) {
   global $NPDS_Prefix, $stop;
   include_once('functions.php');
   $stop='';
   if ((!$email) || ($email=='') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$email))) 
      $stop = translate("Erreur : Email invalide");
   if (strrpos($email,' ') > 0) 
      $stop = translate("Erreur : une adresse Email ne peut pas contenir d'espaces");
   if(checkdnsmail($email) === false)
      $stop = translate("Erreur : DNS ou serveur de mail incorrect");
    if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."users WHERE email='$email'")) > 0)
       $stop = translate("Erreur : adresse Email déjà utilisée");
    if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email'")) > 0) {
       if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email' AND status='NOK'")) >0)
          sql_query("DELETE FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$email'");
       else
          $stop = translate("Erreur : adresse Email déjà utilisée");
    }
    return($stop);
}

function error_handler($ibid) {
   echo '
   <h2>'.translate("La lettre").'</h2>
   <hr />
   <p class="lead mb-2">'.translate("Merci d'entrer l'information en fonction des spécifications").'</p>
   <div class="alert alert-danger">'.$ibid.'</div>
   <a href="index.php" class="btn btn-outline-secondary">'.translate("Retour en arrière").'</a>';
}

function subscribe($var) {
   if ($var!='') {
      include("header.php");
      echo '
      <h2>'.translate("La lettre").'</h2>
      <hr />
      <p class="lead mb-2">'.translate("Gestion de vos abonnements").' : <strong>'.$var.'</strong></p>
      <form action="lnl.php" method="POST">
         '.Q_spambot().'
         <input type="hidden" name="email" value="'.$var.'" />
         <input type="hidden" name="op" value="subscribeOK" />
         <input type="submit" class="btn btn-outline-primary mr-2" value="'.translate("Valider").'" />
         <a href="index.php" class="btn btn-outline-secondary">'.translate("Retour en arrière").'</a>
      </form>';
      include("footer.php");
   }  else
      header("location: index.php");
}

function subscribe_ok($xemail) {
   global $NPDS_Prefix, $stop;

   include("header.php");
   if ($xemail!='') {
      SuserCheck($xemail);
      if ($stop=='') {
         $host_name=getip();
         $timeX=strftime("%Y-%m-%d %H:%M:%S",time());
         // Troll Control
         list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
         if ($troll < 6) {
            sql_query("INSERT INTO ".$NPDS_Prefix."lnl_outside_users VALUES ('$xemail', '$host_name', '$timeX', 'OK')");
            // Email validation + url to unsubscribe
            global $sitename, $nuke_url;
            $subject = "".translate("La lettre")." / $sitename";
            $message = "".translate("Merci d'avoir consacré du temps pour vous enregistrer.")."\n\n";
            $message .= "".translate("Pour supprimer votre abonnement à notre lettre, merci d'utiliser")." :\n $nuke_url/lnl.php?op=unsubscribe&email=$xemail\n\n";
            include("signat.php");
            send_email($xemail, $subject, $message, '', true, 'text');
            opentable();
            echo translate("Merci d'avoir consacré du temps pour vous enregistrer.")."<br /><br />";
            echo "<a href=\"index.php\" class=\"noir\">".translate("Retour en arrière")."</a>";
            closetable();
        } else {
            $stop=translate("Compte ou adresse IP désactivée. Cet émetteur a participé plus de x fois dans les dernières heures, merci de contacter le webmaster pour déblocage.")."<br />";
            error_handler($stop);
        }
      } else {
         error_handler($stop);
      }
   } else {
     error_handler(translate("Cette donnée ne doit pas être vide.")."<br />");
   }
   include("footer.php");
}

function unsubscribe($xemail) {
   global $NPDS_Prefix;

   if ($xemail!="") {
      if ((!$xemail) || ($xemail=="") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$xemail))) header("location: index.php");
      if (strrpos($xemail,' ') > 0) header("location: index.php");
      if (sql_num_rows(sql_query("SELECT email FROM ".$NPDS_Prefix."lnl_outside_users WHERE email='$xemail'")) > 0) {
         $host_name=getip();
         $timeX=strftime("%Y-%m-%d %H:%M:%S",time());
         // Troll Control
         list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
         if ($troll < 6) {
            sql_query("UPDATE ".$NPDS_Prefix."lnl_outside_users SET status='NOK'  WHERE email='$xemail'");
            include("header.php");
            opentable();
            echo translate("Merci")."<br /><br />";
            echo "<a href=\"index.php\" class=\"noir\">".translate("Retour en arrière")."</a>";
            closetable();
            include("footer.php");
         } else {
            include("header.php");
            $stop=translate("Compte ou adresse IP désactivée. Cet émetteur a participé plus de x fois dans les dernières heures, merci de contacter le webmaster pour déblocage.")."<br />";
            error_handler($stop);
            include("footer.php");
         }
      } else {
         redirect_url("index.php");
      }
   } else {
      redirect_url("index.php");
   }
}

settype($op,'string');
switch ($op) {
   case 'subscribe':
      subscribe($email);
   break;
   case 'subscribeOK':
      //anti_spambot
      if (!R_spambot($asb_question, $asb_reponse,"")) {
         Ecr_Log("security", "LNL Anti-Spam : email=".$email, "");
         redirect_url("index.php");
         die();
      }
      subscribe_ok($email);
   break;
   case 'unsubscribe':
      unsubscribe($email);
   break;
}
?>
