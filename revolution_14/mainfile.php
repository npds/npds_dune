<?php
/************************************************************************/                                                         
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
include("grab_globals.php");
include("config.php");
include("lib/multi-langue.php");
include("language/lang-$language.php");
include("cache.class.php");
if ($mysql_i==1)
   include("lib/mysqli.php");
else 
   include("lib/mysql.php");
include("modules/meta-lang/adv-meta_lang.php");

#autodoc Mysql_Connexion() : Connexion plus détaillée ($mysql_p=true => persistente connexion) - Attention : le type de SGBD n'a pas de lien avec le nom de cette fontion
function Mysql_Connexion() {
   $ret_p=sql_connect();
   if (!$ret_p) {
      $Titlesitename="NPDS";
      if (file_exists("meta/meta.php"))
         include ("meta/meta.php");
      if (file_exists("static/database.txt"))
         include ("static/database.txt");
      die();
   }
   return ($ret_p);
}
/****************/
$dblink=Mysql_Connexion();
$mainfile=1;
require_once("auth.inc.php");
if (isset($user)) $cookie=cookiedecode($user);
session_manage();
$tab_langue=make_tab_langue();
global $meta_glossaire;
$meta_glossaire=charg_metalang();
if (function_exists("date_default_timezone_set")) date_default_timezone_set("Europe/Paris");
/****************/
#autodoc session_manage() : Mise &agrave; jour la table session
function session_manage() {
   global $NPDS_Prefix, $cookie, $REQUEST_URI;

   $guest=0;
   $ip=getip();
   $username=$cookie[1];
   if (!isset($username)) {
      $username="$ip";
      $guest=1;
   }

   $past = time()-300;
   sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE time < '$past'");
   $result = sql_query("SELECT time FROM ".$NPDS_Prefix."session WHERE username='$username'");
   if ($row = sql_fetch_assoc($result)) {
      if ($row['time'] < (time()-30)) {
         sql_query("UPDATE ".$NPDS_Prefix."session SET username='$username', time='".time()."', host_addr='$ip', guest='$guest', uri='$REQUEST_URI', agent='".getenv("HTTP_USER_AGENT")."' WHERE username='$username'");
         if ($guest==0) {
            global $gmt;
            sql_query("UPDATE ".$NPDS_Prefix."users SET user_lastvisit='".(time()+$gmt*3600)."' WHERE uname='$username'");
         }
      }
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."session (username, time, host_addr, guest, uri, agent) VALUES ('$username', '".time()."', '$ip', '$guest', '$REQUEST_URI', '".getenv("HTTP_USER_AGENT")."')");
   }
}

#autodoc NightDay() : Pour obtenir Nuit ou Jour ... Un grand Merci &agrave; P.PECHARD pour cette fonction
function NightDay() {
   global $lever, $coucher;
   $Maintenant = strtotime ("now");
   $Jour = strtotime($lever);
   $Nuit = strtotime($coucher);
   if ($Maintenant-$Jour<0 xor $Maintenant-$Nuit>0) return "Nuit"; else return "Jour";
}
#autodoc removeHack($Xstring) : Permet de rechercher et de remplacer "some bad words" dans une chaine // Preg_replace by Pascalp
function removeHack($Xstring) {
  if ($Xstring!="") {
     $npds_forbidden_words=array(
     // NCRs 2 premières séquence = NCR (dec|hexa) correspondant aux caractères latin de la table ascii (code ascii entre 33 et 126)
     //      2 dernières séquences = NCR (dec|hexa) correspondant aux caractères latin du bloc unicode Halfwidth and Fullwidth Forms.
     //        Leur signification est identique ˆ celle des caractres latin de la table ascii dont le code ascii est entre 33 et 126.
     // JPB for NPDS 2005
     "'&#(33|x21|65281|xFF01);'i"=>chr(33),
     "'&#(34|x22|65282|xFF02);'i"=>chr(34),
     "'&#(35|x23|65283|xFF03);'i"=>chr(35),
     "'&#(36|x24|65284|xFF04);'i"=>chr(36),
     "'&#(37|x25|65285|xFF05);'i"=>chr(37),
     "'&#(38|x26|65286|xFF06);'i"=>chr(38),
     "'&#(39|x27|65287|xFF07);'i"=>chr(39),
     "'&#(40|x28|65288|xFF08);'i"=>chr(40),
     "'&#(41|x29|65289|xFF09);'i"=>chr(41),
     "'&#(42|x2A|65290|xFF0A);'i"=>chr(42),
     "'&#(43|x2B|65291|xFF0B);'i"=>chr(43),
     "'&#(44|x2C|65292|xFF0C);'i"=>chr(44),
     "'&#(45|x2D|65293|xFF0D);'i"=>chr(45),
     "'&#(46|x2E|65294|xFF0E);'i"=>chr(46),
     "'&#(47|x2F|65295|xFF0F);'i"=>chr(47),
     "'&#(48|x30|65296|xFF10);'i"=>chr(48),
     "'&#(49|x31|65297|xFF11);'i"=>chr(49),
     "'&#(50|x32|65298|xFF12);'i"=>chr(50),
     "'&#(51|x33|65299|xFF13);'i"=>chr(51),
     "'&#(52|x34|65300|xFF14);'i"=>chr(52),
     "'&#(53|x35|65301|xFF15);'i"=>chr(53),
     "'&#(54|x36|65302|xFF16);'i"=>chr(54),
     "'&#(55|x37|65303|xFF17);'i"=>chr(55),
     "'&#(56|x38|65304|xFF18);'i"=>chr(56),
     "'&#(57|x39|65305|xFF19);'i"=>chr(57),
     "'&#(58|x3A|65306|xFF1A);'i"=>chr(58),
     "'&#(59|x3B|65307|xFF1B);'i"=>chr(59),
     "'&#(60|x3C|65308|xFF1C);'i"=>chr(60),
     "'&#(61|x3D|65309|xFF1D);'i"=>chr(61),
     "'&#(62|x3E|65310|xFF1E);'i"=>chr(62),
     "'&#(63|x3F|65311|xFF1F);'i"=>chr(63),
     "'&#(64|x40|65312|xFF20);'i"=>chr(64),
     "'&#(65|x41|65313|xFF21);'i"=>chr(65),
     "'&#(66|x42|65314|xFF22);'i"=>chr(66),
     "'&#(67|x43|65315|xFF23);'i"=>chr(67),
     "'&#(68|x44|65316|xFF24);'i"=>chr(68),
     "'&#(69|x45|65317|xFF25);'i"=>chr(69),
     "'&#(70|x46|65318|xFF26);'i"=>chr(70),
     "'&#(71|x47|65319|xFF27);'i"=>chr(71),
     "'&#(72|x48|65320|xFF28);'i"=>chr(72),
     "'&#(73|x49|65321|xFF29);'i"=>chr(73),
     "'&#(74|x4A|65322|xFF2A);'i"=>chr(74),
     "'&#(75|x4B|65323|xFF2B);'i"=>chr(75),
     "'&#(76|x4C|65324|xFF2C);'i"=>chr(76),
     "'&#(77|x4D|65325|xFF2D);'i"=>chr(77),
     "'&#(78|x4E|65326|xFF2E);'i"=>chr(78),
     "'&#(79|x4F|65327|xFF2F);'i"=>chr(79),
     "'&#(80|x50|65328|xFF30);'i"=>chr(80),
     "'&#(81|x51|65329|xFF31);'i"=>chr(81),
     "'&#(82|x52|65330|xFF32);'i"=>chr(82),
     "'&#(83|x53|65331|xFF33);'i"=>chr(83),
     "'&#(84|x54|65332|xFF34);'i"=>chr(84),
     "'&#(85|x55|65333|xFF35);'i"=>chr(85),
     "'&#(86|x56|65334|xFF36);'i"=>chr(86),
     "'&#(87|x57|65335|xFF37);'i"=>chr(87),
     "'&#(88|x58|65336|xFF38);'i"=>chr(88),
     "'&#(89|x59|65337|xFF39);'i"=>chr(89),
     "'&#(90|x5A|65338|xFF3A);'i"=>chr(90),
     "'&#(91|x5B|65339|xFF3B);'i"=>chr(91),
     "'&#(92|x5C|65340|xFF3C);'i"=>chr(92),
     "'&#(93|x5D|65341|xFF3D);'i"=>chr(93),
     "'&#(94|x5E|65342|xFF3E);'i"=>chr(94),
     "'&#(95|x5F|65343|xFF3F);'i"=>chr(95),
     "'&#(96|x60|65344|xFF40);'i"=>chr(96),
     "'&#(97|x61|65345|xFF41);'i"=>chr(97),
     "'&#(98|x62|65346|xFF42);'i"=>chr(98),
     "'&#(99|x63|65347|xFF43);'i"=>chr(99),
     "'&#(100|x64|65348|xFF44);'i"=>chr(100),
     "'&#(101|x65|65349|xFF45);'i"=>chr(101),
     "'&#(102|x66|65350|xFF46);'i"=>chr(102),
     "'&#(103|x67|65351|xFF47);'i"=>chr(103),
     "'&#(104|x68|65352|xFF48);'i"=>chr(104),
     "'&#(105|x69|65353|xFF49);'i"=>chr(105),
     "'&#(106|x6A|65354|xFF4A);'i"=>chr(106),
     "'&#(107|x6B|65355|xFF4B);'i"=>chr(107),
     "'&#(108|x6C|65356|xFF4C);'i"=>chr(108),
     "'&#(109|x6D|65357|xFF4D);'i"=>chr(109),
     "'&#(110|x6E|65358|xFF4E);'i"=>chr(110),
     "'&#(111|x6F|65359|xFF4F);'i"=>chr(111),
     "'&#(112|x70|65360|xFF50);'i"=>chr(112),
     "'&#(113|x71|65361|xFF51);'i"=>chr(113),
     "'&#(114|x72|65362|xFF52);'i"=>chr(114),
     "'&#(115|x73|65363|xFF53);'i"=>chr(115),
     "'&#(116|x74|65364|xFF54);'i"=>chr(116),
     "'&#(117|x75|65365|xFF55);'i"=>chr(117),
     "'&#(118|x76|65366|xFF56);'i"=>chr(118),
     "'&#(119|x77|65367|xFF57);'i"=>chr(119),
     "'&#(120|x78|65368|xFF58);'i"=>chr(120),
     "'&#(121|x79|65369|xFF59);'i"=>chr(121),
     "'&#(122|x7A|65370|xFF5A);'i"=>chr(122),
     "'&#(123|x7B|65371|xFF5B);'i"=>chr(123),
     "'&#(124|x7C|65372|xFF5C);'i"=>chr(124),
     "'&#(125|x7D|65373|xFF5D);'i"=>chr(125),
     "'&#(126|x7E|65374|xFF5E);'i"=>chr(126),
     // Fin des NCRs
     //"'&#'i"=>"&_#", // JPB remplacement pour l'extension chinoise en prenant
     // en compte => http://www.w3.org/TR/2003/NOTE-unicode-xml-20030613/#Suitable
     "'&#(8232|x2028);'i"=>"_",
     "'&#(8233|x2029);'i"=>"_",
     "'&#(8234|x202A);'i"=>"_",
     "'&#(8235|x202B);'i"=>"_",
     "'&#(8236|x202C);'i"=>"_",
     "'&#(8237|x202D);'i"=>"_",
     "'&#(8238|x202E);'i"=>"_",
     "'&#(8298|x206A);'i"=>"_",
     "'&#(8299|x206B);'i"=>"_",
     "'&#(8300|x206C);'i"=>"_",
     "'&#(8301|x206D);'i"=>"_",
     "'&#(8302|x206E);'i"=>"_",
     "'&#(8303|x206F);'i"=>"_",
     "'&#(65529|xFFF9);'i"=>"_",
     "'&#(65530|xFFFA);'i"=>"_",
     "'&#(65531|xFFFB);'i"=>"_",
     "'&#(65532|xFFFC);'i"=>"_",
     "'&#(65279|xFEFF);'i"=>"&#x2060;",
     "'&#(119155|x1D173);'i"=>"_",
     "'&#(119156|x1D174);'i"=>"_",
     "'&#(119157|x1D175);'i"=>"_",
     "'&#(119158|x1D176);'i"=>"_",
     "'&#(119159|x1D177);'i"=>"_",
     "'&#(119160|x1D178);'i"=>"_",
     "'&#(119161|x1D179);'i"=>"_",
     "'&#(119162|x1D17A);'i"=>"_",
     "'&#xE000(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE001(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE002(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE003(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE004(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE005(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE006(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#xE007(0|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F);'i"=>"_",
     "'&#91750(4|5|6|7|8|9);'i"=>"_",
     "'&#91751(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91752(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91753(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91754(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91755(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91756(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91757(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91758(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91759(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91760(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91761(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91762(0|1|2|3|4|5|6|7|8|9);'i"=>"_",
     "'&#91763(0|1|);'i"=>"_",
     // Fin
     "'from:'i"=>"!from:!",
     "'subject:'i"=>"!subject:!",
     "'bcc:'i"=>"!bcc:!",
     "'mime-version:'i"=>"!mime-version:!",
     "'base64'i"=>"base_64",
     "'content-type:'i"=>"!content-type:!",
     "'content-transfer-encoding:'i"=>"!content-transfer-encoding:!",
     "'content-disposition:'i"=>"!content-disposition:!",
     "'content-location:'i"=>"!content-location:!",
     "'include'i"=>"!include!",
     "'<script'i"=>"&lt;script",
     "'</script'i"=>"&lt;/script",
     "'javascript'i"=>"!javascript!",
     "'embed'i"=>"!embed!",
     "'iframe'i"=>"!iframe!",
     "'refresh'i"=>"!refresh!",
     "'document\.cookie'i"=>"!document.cookie!",
     "'onload'i"=>"!onload!",
     "'onstart'i"=>"!onstart!",
     "'onerror'i"=>"!onerror!",
     "'onkey'i"=>"!onkey!",
     "'onmouse'i"=>"!onmouse!",
     "'onclick'i"=>"!onclick!",
     "'ondblclick'i"=>"!ondblclick!",
     "'onhelp'i"=>"!onhelp!",
     "'onmousedown'i"=>"!onmousedown!",
     "'onmousemove'i"=>"!onmousemove!",
     "'onmouseout'i"=>"!onmouseout!",
     "'onmouseover'i"=>"!onmouseover!",
     "'onmouseup'i"=>"!onmouseup!",
     "'onblur'i"=>"!onblur!",
     "'onafterupdate'i"=>"!onafterupdate!",
     "'onbeforeupdate'i"=>"!onbeforeupdate!",
     "'onkeydown'i"=>"!onkeydown!",
     "'onkeypress'i"=>"!onkeypress!",
     "'onkeyup'i"=>"!onkeyup!",
     "'onfocus'i"=>"!onfocus!",
     "'onunload'i"=>"!onunload!",
     "'jscript'i"=>"!jscript!",
     "'vbscript'i"=>"!vbscript!",
     "'pearlscript'i"=>"!pearlscript!",
     "'&#(8216|x2018);'i"=>chr(39),
     "'&#(8217|x2019);'i"=>chr(39),
     "'&#39;'i"=>'\\\'',
     "'&#(8220|x201C);'i"=>chr(34),
     "'&#(8221|x201D);'i"=>chr(34),
     "'&#160;'i"=>'&nbsp;',
     "'<style'i"=>"&lt;style",
     "'<body'i"=>"&lt;body",
     "'<object'i"=>"&lt;object",
     "'\<\?php'i"=>"&lt;?php",
     "'\<\?'i"=>"&lt;?",
     "'\?\>'i"=>"?&gt;",
     "'\<\%'i"=>"&lt;%",
     "'\%\>'i"=>"%&gt;",
     "'url\('i"=>"!url(!",
     "'expression\('i"=>"!expression(!");
     $Xstring=preg_replace(array_keys($npds_forbidden_words),array_values($npds_forbidden_words), $Xstring);
  }
  return($Xstring);
}
#autodoc getmicrotime() : Retourne le temps en micro-seconde
function getmicrotime() {
   list($usec, $sec) = explode(" ",microtime());
   return (float)$usec + (float)$sec;
}
#autodoc send_email($email, $subject, $message, $from, $priority, $mime) : Pour envoyer un mail en texte ou html via les fonctions mail ou email  / $mime = 'text', 'html' 'html-nobr'-(sans application de nl2br) ou 'mixed'-(piece jointe)
function send_email($email, $subject, $message, $from="", $priority=false, $mime="text") {
   global $mail_fonction, $adminmail;
   $advance="";
   if ($priority) {
      $advance="X-Priority: 2\n";
   }
   if ($mime=="mixed") {
      // dans $message se trouve le nom du fichier à joindre (voir le module session-log pour un exemple)
      $boundary = "_".md5 (uniqid(mt_rand()));
      $attached_file = file_get_contents($message);
      $attached_file = chunk_split(base64_encode($attached_file));
      $message = "\n\n". "--" .$boundary . "\nContent-Type: application; name=\"".basename($message)."\" charset=".cur_charset."\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"".basename($message)."\"\r\n\n".$attached_file . "--" . $boundary . "--";
      $advance.= "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
   }
   if ($mime=="text") {
      $advance.="Content-Type: text/plain; charset=".cur_charset."\n";
   }
   if (($mime=="html") or ($mime=="html-nobr")) {
      $advance.="Content-Type: text/html; charset=".cur_charset."\n";
      if ($mime!="html-nobr")
         $message=nl2br($message);
      else
         $mime="html";
      $css="<html>\n<head>\n<style type='text/css'>\nbody {\nbackground: #FFFFFF;\nfont-family: Tahoma, Calibri, Arial;\nfont-size: 11px;\ncolor: #000000;\n}\na, a:visited, a:link, a:hover {\ntext-decoration: underline;\n}\n</style>\n</head>\n<body>\n";
      $message=$css.$message."\n</body>\n</html>";
   }
   if (($mail_fonction==1) or ($mail_fonction=="")) {
      if ($from!="") {
         $From_email=$from;
      } else {
         $From_email=$adminmail;
      }
      if (preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i',$From_email)) {
         $result=mail($email, $subject, $message, "From: $From_email\nReturn-Path: $From_email\nX-Mailer: NPDS\n$advance");
      }
   } else {
      $pos = strpos($adminmail, "@");
      $tomail=substr($adminmail,0,$pos);
      $result=email($tomail, $email, $subject, $message, $tomail, "Return-Path:\nX-Mailer: NPDS\n$advance");
   }   
   if ($result) {
      return (true);
   } else {
      return (false);
   }
}
#autodoc copy_to_email($to_userid,$sujet,$message) : Pour copier un subject+message dans un email ($to_userid)
function copy_to_email($to_userid,$sujet,$message) {
   global $NPDS_Prefix;
   $result = sql_query("select email,send_email from ".$NPDS_Prefix."users where uid='$to_userid'");
   list($mail,$avertir_mail) = sql_fetch_row($result);
   if (($mail) and ($avertir_mail==1)) {
      send_email($mail,$sujet,$message, "", true, "html");
   }
}
#autodoc Ecr_Log($fic_log, $req_log, $mot_log) : Pour &eacute;crire dans un log (security.log par exemple)
function Ecr_Log($fic_log, $req_log, $mot_log) {
   // $Fic_log= the file name :
   //  => "security" for security maters
   //  => ""
   // $req_log= a phrase describe the infos
   //
   // $mot_log= if "" the Ip is recorded, else extend status infos
   $logfile = "slogs/$fic_log.log";
   $fp = fopen($logfile, 'a');
   flock($fp, 2);
   fseek($fp, filesize($logfile));
   if ($mot_log=="") {$mot_log="IP=>".getip();}
   $ibid = sprintf("%-10s %-60s %-10s\r\n",date("m/d/Y H:i:s",time()),basename($_SERVER['PHP_SELF'])."=>".strip_tags(urldecode($req_log)),strip_tags(urldecode($mot_log)));
   fwrite($fp, $ibid);
   flock($fp, 3);
   fclose($fp);
}
#autodoc redirect_url($urlx) : Permet une redirection javascript / en lieu et place de header("location: ...");
function redirect_url($urlx) {
   echo "<script type=\"text/javascript\">\n";
   echo "//<![CDATA[\n";
   echo "document.location.href='".$urlx."';\n";
   echo "//]]>\n";
   echo "</script>";
}
#autodoc SC_infos() : Indique le status de SuperCache
function SC_infos() {
   global $SuperCache, $npds_sc;
   $infos="";
   if ($SuperCache) {
      if ($npds_sc) {
         $infos="<span style=\"font-size: .75em;\">".translate(".:Page &lt;&lt; Super-Cache:.")."</span>";
      } else {
         $infos="<span style=\"font-size: .75em;\">".translate(".:Page &gt;&gt; Super-Cache:.")."</span>";
      }
   }
   return $infos;
}
#autodoc req_stat() : Retourne un tableau contenant les nombres pour les statistiques du site (stats.php)
function req_stat() {
   global $NPDS_Prefix;
   // Les membres
   $result = sql_query("select uid from ".$NPDS_Prefix."users");
   if ($result) {$xtab[0]=sql_num_rows($result);} else {$xtab[0]="0";}
   // Les Nouvelles (News)
   $result = sql_query("select sid from ".$NPDS_Prefix."stories");
   if ($result) {$xtab[1]=sql_num_rows($result);} else {$xtab[1]="0";}
   // Les Critiques (Reviews))
   $result = sql_query("select id from ".$NPDS_Prefix."reviews");
   if ($result) {$xtab[2]=sql_num_rows($result);} else {$xtab[2]="0";}
   // Les Forums
   $result = sql_query("select forum_id from ".$NPDS_Prefix."forums");
   if ($result) {$xtab[3]=sql_num_rows($result);} else {$xtab[3]="0";}
   // Les Sujets (topics)
   $result = sql_query("select topicid from ".$NPDS_Prefix."topics");
   if ($result) {$xtab[4]=sql_num_rows($result);} else {$xtab[4]="0";}
   // Nombre de pages vues
   $result = sql_query("SELECT count FROM ".$NPDS_Prefix."counter WHERE type='total'");
   if ($result) {list($totalz)=sql_fetch_row($result);}
   $totalz++;
   $xtab[5]=$totalz++;
   sql_free_result($result);
   return($xtab);
}
#autodoc Mess_Check_Mail($username) : Appel la fonction d'affichage du groupe check_mail (theme principal de NPDS) sans class
function Mess_Check_Mail($username) {
   Mess_Check_Mail_interface($username, "");
}
#autodoc Mess_Check_Mail_interface($username, $class) : Affiche le groupe check_mail (theme principal de NPDS)
function Mess_Check_Mail_interface($username, $class) {
   global $anonymous;
   if ($ibid=theme_image("fle_b.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;}
   if ($class!="") $class="class=\"$class\"";
   if ($username==$anonymous) {
      if ($imgtmp) {
         echo "<img alt=\"\" src=\"$imgtmp\" align=\"center\" border=\"0\" />$username - <a href=\"user.php\" $class>".translate("Your account")."</a>";
      } else {
         echo "[$username - <a href=\"user.php\" $class>".translate("Your account")."</a>]";
      }
   } else {
      if ($imgtmp) {
         echo "<a href=\"user.php\" $class><img alt=\"\" src=\"$imgtmp\" align=\"center\" border=\"0\" />".translate("Your account")."</a>&nbsp;".Mess_Check_Mail_Sub($username,$class);
      } else {
         echo "[<a href=\"user.php\" $class>".translate("Your account")."</a>&nbsp;&middot;&nbsp;".Mess_Check_Mail_Sub($username,$class)."]";
      }
   }
}
#autodoc Mess_Check_Mail_Sub($username, $class) : Affiche le groupe check_mail (theme principal de NPDS) / SOUS-Fonction
function Mess_Check_Mail_Sub($username, $class) {
   global $NPDS_Prefix;
   global $user;
   if ($username) {
      $userdata = explode(":", base64_decode($user));
      $total_messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userdata[0]' and type_msg='0'"));
      $new_messages = sql_num_rows(sql_query("SELECT msg_id FROM ".$NPDS_Prefix."priv_msgs WHERE to_userid = '$userdata[0]' AND read_msg='0' and type_msg='0'"));
      if ($total_messages > 0) {
         if ($new_messages > 0) {
            $Xcheck_Nmail=$new_messages;
         } else {
            $Xcheck_Nmail="0";
         }
         $Xcheck_mail=$total_messages;
      } else {
         $Xcheck_Nmail="0";
         $Xcheck_mail="0";
      }
   }
   $YNmail="$Xcheck_Nmail";
   $Ymail="$Xcheck_mail";
   $Mel="<a href=\"viewpmsg.php\" $class>Mel</a>";
   if ($Xcheck_Nmail >0) {
      $YNmail="<a href=\"viewpmsg.php\" $class>$Xcheck_Nmail</a>";
      $Mel="Mel";
   }
   if ($Xcheck_mail >0) {
      $Ymail="<a href=\"viewpmsg.php\" $class>$Xcheck_mail</a>";
      $Mel="Mel";
   }
   return ("$Mel : $YNmail / $Ymail");
}
#autodoc Who_Online() : Qui est en ligne ? + message de bienvenue
function Who_Online() {
   list($content1, $content2)=Who_Online_Sub();
   return array($content1, $content2);
}
#autodoc Who_Online() : Qui est en ligne ? + message de bienvenue / SOUS-Fonction / Utilise Site_Load
function Who_Online_Sub() {
   global $user, $cookie;
   list($member_online_num, $guest_online_num)=site_load();
   $content1 = "$guest_online_num ".translate("guest(s) and")." $member_online_num ".translate("member(s) that are online.");
   if ($user) {
      $content2 = translate("You are logged as")." <b>".$cookie[1]."</b>";
   } else {
      $content2 = translate("You can register for free by clicking")." <a href=\"user.php?op=only_newuser\">".translate("here")."</a>";
   }
   return array($content1, $content2);
}
#autodoc Site_Load() : Maintient les informations de NB connexion (membre, anonyme) - globalise la variable $who_online_num et maintient le fichier cache/site_load.log &agrave; jour<br />Indispensable pour la gestion de la 'clean_limit' de SuperCache
function Site_Load() {
   global $NPDS_Prefix;
   global $SuperCache;
   // globalise la variable
   global $who_online_num;
   $guest_online_num = 0;
   $member_online_num = 0;
   $result = sql_query("SELECT count(username) as TheCount, guest FROM ".$NPDS_Prefix."session GROUP BY guest");
   while ($TheResult = sql_fetch_assoc($result)) {
      if ($TheResult['guest']==0)
         $member_online_num = $TheResult['TheCount'];
      else
         $guest_online_num = $TheResult['TheCount'];
   }
   $who_online_num = $guest_online_num + $member_online_num;
   if ($SuperCache) {
      $file=fopen("cache/site_load.log", "w");
         fwrite($file, $who_online_num);
      fclose($file);
   }
   return array($member_online_num, $guest_online_num);
}
#autodoc AutoReg() : Si AutoRegUser=true et que le user ne dispose pas du droit de connexion : RAZ du cookie NPDS<br />retourne False ou True
function AutoReg() {
   global $NPDS_Prefix;
   global $AutoRegUser, $user;
   if (!$AutoRegUser) {
      if (isset($user)) {
         $cookie = explode(":", base64_decode($user));
         list($test) = sql_fetch_row(sql_query("select open from ".$NPDS_Prefix."users_status where uid='$cookie[0]'"));
         if (!$test) {
            setcookie("user","",0);
            return false;
         } else {
            return true;
         }
      } else {
         return true;
      }
   } else {
      return true;
   }
}
#autodoc secur_static($sec_type) : Pour savoir si le visiteur est un : membre ou admin (static.php et banners.php par exemple)
function secur_static($sec_type) {
   global $user, $admin;
   switch ($sec_type) {
      case "member":
           if (isset($user)) {
              return true;
           } else {
              return false;
           }
           break;
      case "admin":
           if (isset($admin)) {
              return true;
           } else {
              return false;
           }
           break;
   }
}
// Opentable - closetable
#autodoc sub_opentable() : Ouverture de tableaux pour le th&egrave;me : return
function sub_opentable() {
   if (function_exists("opentable_theme")) {
      $content=opentable_theme();
   } else {
      $content ="<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"ligna\"><tr><td>\n";
      $content.="<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"6\" class=\"lignb\"><tr><td>\n";
   }
   return ($content);
}
#autodoc opentable() : Ouverture de tableaux pour le th&egrave;me : echo
function opentable() {
   echo sub_opentable();
}
#autodoc sub_closetable() : Fermeture de tableaux pour le th&egrave;me : return
function sub_closetable() {
   if (function_exists("closetable_theme")) {
      return(closetable_theme());
   } else {
      return("</td></tr></table></td></tr></table>\n");
   }
}
#autodoc closetable() : Fermeture de tableaux pour le th&egrave;me : echo
function closetable() {
   echo sub_closetable();
}
#autodoc opentable2() : Ouverture de tableaux pour le th&egrave;me : return
function sub_opentable2() {
   if (function_exists("opentable2_theme")) {
      $content=opentable2_theme();
   } else {
      $content ="<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" class=\"ligna\"><tr><td>\n";
      $content.="<table border=\"0\" cellspacing=\"1\" cellpadding=\"6\" class=\"lignb\"><tr><td>\n";
   }
   return ($content);
}
#autodoc opentable2() : Ouverture de tableaux pour le th&egrave;me : echo
function opentable2() {
   echo sub_opentable2();
}
#autodoc closetable2() : Fermeture de tableaux pour le th&egrave;me : return
function sub_closetable2() {
   if (function_exists("opentable2_theme")) {
      $content=closetable2_theme();
   } else {
      return("</td></tr></table></td></tr></table>\n");
   }
   return ($content);
}
#autodoc closetable2() : Fermeture de tableaux pour le th&egrave;me : echo
function closetable2() {
   echo sub_closetable2();
}
// Opentable - closetable
#autodoc ultramode() : G&egrave;n&egrave;ration des fichiers ultramode.txt et net2zone.txt dans /cache
function ultramode() {
   global $NPDS_Prefix;
   global $nuke_url, $storyhome;
   $ultra = "cache/ultramode.txt";
   $netTOzone = "cache/net2zone.txt";
   $file = fopen("$ultra", "w");
   $file2 = fopen("$netTOzone", "w");
   fwrite($file, "General purpose self-explanatory file with news headlines\n");
   $storynum = $storyhome;
   $xtab=news_aff("index","where ihome='0' and archive='0'",$storyhome,"");
   $story_limit=0;
   while (($story_limit<$storynum) and ($story_limit<sizeof($xtab))) {
      list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
      $story_limit++;
      $rfile2=sql_query("select topictext, topicimage from ".$NPDS_Prefix."topics where topicid='$topic'");
      list($topictext, $topicimage) = sql_fetch_row($rfile2);
      $hometext=meta_lang(strip_tags($hometext));
      fwrite($file, "%%\n$title\n$nuke_url/article.php?sid=$sid\n$time\n$aid\n$topictext\n$hometext\n$topicimage\n");
      fwrite($file2, "<NEWS>\n<NBX>$topictext</NBX>\n<TITLE>".stripslashes($title)."</TITLE>\n<SUMMARY>$hometext</SUMMARY>\n<URL>$nuke_url/article.php?sid=$sid</URL>\n<AUTHOR>".$aid."</AUTHOR>\n</NEWS>\n\n");
   }
   fclose($file);
   fclose($file2);
}
#autodoc cookiedecode($user) : D&eacute;code le cookie membre et v&eacute;rifie certaines choses (password)
function cookiedecode($user) {
   global $NPDS_Prefix;
   global $language;
   $stop=false;

   if (array_key_exists("user",$_GET)) {
      if ($_GET['user']!="") { $stop=true; $user="BAD-GET";}
   } else if (isset($HTTP_GET_VARS)) {
      if (array_key_exists("user",$HTTP_GET_VARS) and ($HTTP_GET_VAR['user']!="")) { $stop=true; $user="BAD-GET";}
   }
   if ($user) {
      $cookie = explode(":", base64_decode($user));
      settype($cookie[0],"integer");
      if (trim($cookie[1])!="") {
         $result = sql_query("select pass, user_langue from ".$NPDS_Prefix."users where uname='$cookie[1]'");
         if (sql_num_rows($result)==1) {
            list($pass, $user_langue) = sql_fetch_row($result);
            if (($cookie[2] == md5($pass)) AND ($pass != "")) {
               if ($language!=$user_langue) {
                  sql_query("UPDATE ".$NPDS_Prefix."users set user_langue='$language' where uname='$cookie[1]'");
               }
               return $cookie;
            } else {
               $stop=true;
            }
         } else {
            $stop=true;
         }
      } else {
         $stop=true;
      }
      if ($stop) {
         setcookie("user","",0);
         unset($user);
         unset($cookie);
         header("Location: index.php");
      }
   }
}
#autodoc getusrinfo($user) : Renvoi le contenu de la table users pour le user uname
function getusrinfo($user) {
   global $NPDS_Prefix;
   $cookie = explode(":", base64_decode($user));
   $result = sql_query("select pass FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
   list($pass) = sql_fetch_row($result);
   $userinfos="";
   if (($cookie[2] == md5($pass)) AND ($pass != "")) {
      $result = sql_query("select uid, name, uname, email, femail, url, user_avatar, user_icq, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, user_aim, user_yim, user_msnm, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax, user_journal, send_email, is_visible, mns, user_lnl FROM ".$NPDS_Prefix."users WHERE uname='$cookie[1]'");
      if (sql_num_rows($result)==1) {
         $userinfo = sql_fetch_assoc($result);
      } else {
         echo "<b>".translate("A problem ocurred").".</b><br />";
      }
   }
   return $userinfo;
}
#autodoc FixQuotes($what) : Quote une chaîne contenant des '
function FixQuotes($what = "") {
   $what = str_replace("&#39;","'",$what);
   $what = str_replace("'","''",$what);
   while (preg_match("#\\\\'#", $what)) {
      $what=preg_replace("#\\\\'#", "'", $what);
   }
   return $what;
}
#autodoc check_html ($str, $strip) : Fonction obsol&egrave;te / maintenue pour des raisons de compatibilité
function check_html ($str, $strip="nohtml") {
   return strip_tags($str);
}
#autodoc unhtmlentities($string) : Fonction obsol&egrave;te / maintenue pour des raisons de compatibilité
function unhtmlentities($string) {
   return html_entity_decode($string);
}
#autodoc formatTimestamp($time) : Formate un timestamp en fonction de la valeur de $locale (config.php) / si "nogmt" est concat&eacute;n&eacute; devant la valeur de $time, le d&eacute;calage gmt n'est pas appliqu&eacute;
function formatTimestamp($time) {
   global $datetime, $locale, $gmt;
   $local_gmt=$gmt;
   setlocale (LC_TIME, aff_langue($locale));
   if (substr($time,0,5)=="nogmt") {
      $time=substr($time,5);
      $local_gmt=0;
   }

   preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
   $datetime = strftime(translate("datestring"), mktime($datetime[4]+$local_gmt,$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
   if (cur_charset=="utf-8") {
      $datetime = utf8_encode($datetime);
   }
   return(ucfirst($datetime));
}
#autodoc formatAidHeader($aid) : Affiche URL et Email d'un auteur
function formatAidHeader($aid) {
   global $NPDS_Prefix;
   $holder = sql_query("SELECT url, email FROM ".$NPDS_Prefix."authors where aid='$aid'");
   if ($holder) {
      list($url, $email) = sql_fetch_row($holder);
      if (isset($url)) {
         echo "<a href=\"$url\" class=\"noir\">$aid</a>";
      } elseif (isset($email)) {
         echo "<a href=\"mailto:$email\" class=\"noir\">$aid</a>";
      } else {
         echo $aid;
      }
   }
}
#autodoc ctrl_aff($ihome, $catid) : Gestion + fine des destinataires (-1, 0, 1, 2 -> 127, -127)
function ctrl_aff($ihome, $catid=0) {
   global $user;
   $affich=false;
   if ($ihome==-1 and (!$user)) {
      $affich=true;
   } elseif ($ihome==0) {
      $affich=true;
   } elseif ($ihome==1) {
      if ($catid>0) {
         $affich=false;
      } else {
         $affich=true;
      }
   } elseif (($ihome>1) and ($ihome<=127)) {
      $tab_groupe=valid_group($user);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue==$ihome) {
               $affich=true;
               break;
            }
         }
      }
   } else {
      if ($user) $affich=true;
   }
   return ($affich);
}
#autodoc news_aff($type_req, $sel, $storynum, $oldnum) : Une des fonctions fondamentales de NPDS / assure la gestion de la selection des News en fonctions des crit&egrave;res de publication
function news_aff($type_req, $sel, $storynum, $oldnum) {
   global $NPDS_Prefix;
   // Astuce pour affiché le nb de News correct même si certaines News ne sont pas visibles (membres, groupe de membres)
   // En fait on * le Nb de News par le Nb de groupes
   $row_Q2 = Q_select("select count(groupe_id) as total from ".$NPDS_Prefix."groupes",86400);
   list(,$NumG)=each($row_Q2);
   if ($NumG['total']<2) $coef=2; else $coef=$NumG['total'];
   settype($storynum,"integer");
   if ($type_req=="index") {
      $Xstorynum=$storynum*$coef;
      $result = Q_select("SELECT sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel order by sid DESC limit $Xstorynum",3600);
      $Znum=$storynum;
   }
   if ($type_req=="old_news") {
      $Xstorynum=$oldnum*$coef;
      $result = Q_select("select sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel order by time DESC limit $storynum,$Xstorynum",3600);
      $Znum=$oldnum;
   }
   if (($type_req=="big_story") or ($type_req=="big_topic")) {
      $Xstorynum=$oldnum*$coef;
      $result = Q_select("select sid, catid, ihome from ".$NPDS_Prefix."stories $sel order by counter DESC limit $storynum,$Xstorynum",3600);
      $Znum=$oldnum;
   }
   if ($type_req=="libre") {
      $Xstorynum=$oldnum*$coef;
      $result=Q_select("SELECT sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel",3600);
      $Znum=$oldnum;
   }
   if ($type_req=="archive") {
      $Xstorynum=$oldnum*$coef;
      $result=Q_select("SELECT sid, catid, ihome FROM ".$NPDS_Prefix."stories $sel",3600);
      $Znum=$oldnum;
   }
   $ibid=0; settype($tab,'array');
   while(list(,$myrow) = each($result)) {
      $s_sid=$myrow['sid'];
      $catid=$myrow['catid'];
      $ihome=$myrow['ihome'];
      if ($ibid==$Znum) {break;}
      if ($type_req=="libre") {$catid=0;}
      if ($type_req=="archive") {$ihome=0;}
      if (ctrl_aff($ihome, $catid)) {
         if (($type_req=="index") or ($type_req=="libre")) {
            $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM ".$NPDS_Prefix."stories where sid='$s_sid' and archive='0'");
         }
         if ($type_req=="archive") {
            $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM ".$NPDS_Prefix."stories where sid='$s_sid' and archive='1'");
         }
         if ($type_req=="old_news") {
            $result2 = sql_query("select sid, title, time, comments, counter from ".$NPDS_Prefix."stories where sid='$s_sid' and archive='0'");
         }
         if (($type_req=="big_story") or ($type_req=="big_topic")) {
            $result2 = sql_query("select sid, title from ".$NPDS_Prefix."stories where sid='$s_sid' and archive='0'");
         }
         $tab[$ibid]=sql_fetch_row($result2);
         if (is_array($tab[$ibid])) {
            $ibid++;
        }
      }
   }
   @sql_free_result($result);
   return ($tab);
}
#autodoc themepreview($title, $hometext, $bodytext, $notes) : Permet de pr&eacute;-visualiser la présentation d'un NEW
function themepreview($title, $hometext, $bodytext="", $notes="") {
   echo "<span class=\"titrea\">$title</span><br />".meta_lang($hometext)."<br />".meta_lang($bodytext)."<br />".meta_lang($notes);
}
#autodoc prepa_aff_news($op,$catid) : Pr&eacute;pare, serialize et stock dans un tableau les news r&eacute;pondant aux crit&egrave;res<br />$op="" ET $catid="" : les news // $op="categories" ET $catid="catid" : les news de la cat&eacute;gorie catid //  $op="article" ET $catid=ID_X : l'article d'ID X // Les news des sujets : $op="topics" ET $catid="topic"
function prepa_aff_news($op,$catid,$marqeur) {
   global $NPDS_Prefix;
   global $storyhome, $topicname, $topicimage, $topictext, $datetime, $cookie;
   if (isset($cookie[3])) {
       $storynum = $cookie[3];
   } else {
       $storynum = $storyhome;
   }
   if ($op=="categories") {
      sql_query("update ".$NPDS_Prefix."stories_cat set counter=counter+1 where catid='$catid'");
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","where catid='$catid' and archive='0' order by sid DESC limit $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="topics") {
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","where topic='$catid' and archive='0' order by sid DESC limit $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="news") {
      settype($marqeur, "integer");
      if (!isset($marqeur)) {$marqeur=0;}
      $xtab=news_aff("libre","where ihome!='1' and archive='0' order by sid DESC limit $marqeur,$storynum","","-1");
      $storynum=sizeof($xtab);
   } elseif ($op=="article") {
      $xtab=news_aff("index","where ihome!='1' and sid='$catid'",1,"");
   } else {
      $xtab=news_aff("index","where ihome!='1' and archive='0'",$storynum,"");
   }
   $story_limit=0;
   while (($story_limit<$storynum) and ($story_limit<sizeof($xtab))) {
       list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
       $story_limit++;
// trop brutal faut faire plus fin et laisser la possibilitŽ des images !!!!
//       if (!$imgtmp=theme_image("box/print.gif")) { $imgtmp="images/print.gif"; }
       $printP = '<a href="print.php?sid='.$s_sid.'" title="'.translate("Printer Friendly Page").'" data-toggle="tooltip" ><i class="fa fa-lg fa-print"></i></a>&nbsp;';
//       if (!$imgtmp=theme_image("box/friend.gif")) { $imgtmp="images/friend.gif"; }
       $sendF = '<a href="friend.php?op=FriendSend&amp;sid='.$s_sid.'" title="'.translate("Send this Story to a Friend").'" data-toggle="tooltip" ><i class="fa fa-lg fa-envelope-o"></i></a>';
       getTopics($s_sid);
       $title = aff_langue(stripslashes($title));
       $hometext = aff_langue(stripslashes($hometext));
       $notes = aff_langue(stripslashes($notes));
       $bodycount = strlen(strip_tags(aff_langue($bodytext),"<img>"));
       if ($bodycount > 0) {
          $bodycount = strlen(strip_tags(aff_langue($bodytext)));
          if ($bodycount > 0 )
             $morelink[0]=wrh($bodycount)." ".translate("bytes more");
          else
             $morelink[0]=" ";
          $morelink[1]=" <a href=\"article.php?sid=$s_sid\" class=\"noir\">".translate("Read More...")."</a>";
       } else {
          $morelink[0]="";
          $morelink[1]="";
       }
       if ($comments==0) {
           $morelink[2]=0;
//           $morelink[3]="<a href=\"article.php?sid=$s_sid\" class=\"noir\">".translate("comments?")."</a>";
           $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="noir"><i class="fa fa-commenting-o fa-lg" title="'.translate("comments?").'" data-toggle="tooltip"></i></a>';

       } elseif ($comments==1) {
           $morelink[2]=$comments;
//           $morelink[3]="<a href=\"article.php?sid=$s_sid\" class=\"noir\">".translate("comment")."</a>";
           $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="noir"><i class="fa fa-comment-o fa-lg" title="'.translate("comment").'" data-toggle="tooltip"></i></a>';
       } else {
           $morelink[2]=$comments;
//           $morelink[3]="<a href=\"article.php?sid=$s_sid\" class=\"noir\">".translate("comments")."</a>";
           $morelink[3]='<a href="article.php?sid='.$s_sid.'" class="" ><span class="com fa-stack fa-3x"><i class="fa fa-comment-o fa-lg fa-stack-2x" title="'.translate("comments").'" data-toggle="tooltip"></i><strong class="fa-stack-1x-com fa-stack-text ">'.$comments.'</strong></span></a>';
       }
       $morelink[4]=$printP;
       $morelink[5]=$sendF;
       $sid = $s_sid;
       if ($catid != 0) {
          $resultm = sql_query("select title from ".$NPDS_Prefix."stories_cat where catid='$catid'");
          list($title1) = sql_fetch_row($resultm);
          $title = "<a href=\"index.php?op=newcategory&amp;catid=$catid\" class=\"noir\">".aff_langue($title1)."</a> : $title";
          // Attention à cela aussi
          $morelink[6]="<a href=\"index.php?op=newcategory&amp;catid=$catid\" class=\"noir\">".aff_langue($title1)."</a>";
       } else {
          $morelink[6]="";
       }
       $news_tab[$story_limit]['aid']=serialize($aid);
       $news_tab[$story_limit]['informant']=serialize($informant);
       $news_tab[$story_limit]['datetime']=serialize($time);
       $news_tab[$story_limit]['title']=serialize($title);
       $news_tab[$story_limit]['counter']=serialize($counter);
       $news_tab[$story_limit]['topic']=serialize($topic);
       $news_tab[$story_limit]['hometext']=serialize(meta_lang(aff_code($hometext)));
       $news_tab[$story_limit]['notes']=serialize(meta_lang(aff_code($notes)));
       $news_tab[$story_limit]['morelink']=serialize($morelink);
       $news_tab[$story_limit]['topicname']=serialize($topicname);
       $news_tab[$story_limit]['topicimage']=serialize($topicimage);
       $news_tab[$story_limit]['topictext']=serialize($topictext);
       $news_tab[$story_limit]['id']=serialize($s_sid);
   }
   return($news_tab);
}
#autodoc valid_group($xuser) : Retourne un tableau contenant la liste des groupes d'appartenance d'un membre
function valid_group($xuser) {
   global $NPDS_Prefix;
   if ($xuser) {
      $userdata = explode(":",base64_decode($xuser));
      $user_temp=Q_select("select groupe from ".$NPDS_Prefix."users_status where uid='$userdata[0]'",3600);
      list(,$groupe) = each($user_temp);
      $tab_groupe=explode(",",$groupe['groupe']);
   } else {
      $tab_groupe="";
   }
   return ($tab_groupe);
}
#autodoc liste_group() : Retourne une liste des groupes disponibles dans un tableau
function liste_group() {
   global $NPDS_Prefix;
   $r = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_id ASC");
   $tmp_groupe[0]="-> ".adm_translate("Supprimer")."/".adm_translate("Choisir un groupe")." <-";
   while($mX = sql_fetch_assoc($r)) {
      $tmp_groupe[$mX['groupe_id']]=aff_langue($mX['groupe_name']);
   }
   sql_free_result($r);
   return ($tmp_groupe);
}
#autodoc groupe_forum($forum_groupeX, $tab_groupeX) : Retourne true ou false en fonction de l'autorisation d'un membre sur 1 (ou x) forum de type groupe
function groupe_forum($forum_groupeX, $tab_groupeX) {
   $ok_affich=groupe_autorisation($forum_groupeX, $tab_groupeX);
   return ($ok_affich);
}
#autodoc groupe_autorisation($groupeX, $tab_groupeX) : Retourne true ou false en fonction de l'autorisation d'un membre sur 1 (ou x) groupe
function groupe_autorisation($groupeX, $tab_groupeX) {
   $tab_groupe=explode(",",$groupeX);
   $ok=false;
   if ($tab_groupeX) {
      foreach($tab_groupe as $groupe) {
         foreach($tab_groupeX as $groupevalue) {
            if ($groupe==$groupevalue) {
               $ok=true;
               break;
            }
         }
         if ($ok) break;
      }
   }
   return ($ok);
}
#autodoc block_fonction($title, $contentX) : Assure la gestion des include# et function# des blocs de NPDS / le titre du bloc est export&eacute; (global) )dans $block_title
function block_fonction($title, $contentX) {
   global $block_title;
   $block_title=$title;
   //For including PHP functions in block
   if (stristr($contentX,"function#")) {
      $contentX=str_replace("<br />","",$contentX);
      $contentX=str_replace("<BR />","",$contentX);
      $contentX=str_replace("<BR>","",$contentX);
      $contentY=trim(substr($contentX,9));
      if (stristr($contentY,"params#")) {
         $pos = strpos($contentY,"params#");
         $contentII=trim(substr($contentY,0,$pos));
         $params=substr($contentY,$pos+7);
         $prm=explode(",",$params);
         // Remplace le param "False" par la valeur false (idem pour True)
         for ($i=0; $i<=count($prm)-1; $i++) {
            if ($prm[$i]=="false") {$prm[$i]=false;}
            if ($prm[$i]=="true") {$prm[$i]=true;}
         }
         // En fonction du nombre de params de la fonction : limite actuelle : 8
         if (function_exists($contentII)) {
            switch(count($prm)) {
               case 1:
                  $contentII($prm[0]); break;
               case 2:
                  $contentII($prm[0],$prm[1]); break;
               case 3:
                  $contentII($prm[0],$prm[1],$prm[2]); break;
               case 4:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3]); break;
               case 5:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4]); break;
               case 6:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5]); break;
               case 7:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5],$prm[6]); break;
               case 8:
                  $contentII($prm[0],$prm[1],$prm[2],$prm[3],$prm[4],$prm[5],$prm[6],$prm[7]); break;
            }
            return (true);
         } else {
            return (false);
         }
      } else {
         if (function_exists($contentY)) {
            $contentY();
            return (true);
         } else {
            return (false);
         }
      }
   } else {
      return (false);
   }
}
#autodoc fab_block($title, $member, $content, $Xcache) : Assure la fabrication r&eacute;elle et le Cache d'un bloc
function fab_block($title, $member, $content, $Xcache) {
   global $SuperCache, $CACHE_TIMINGS;
   // Multi-Langue
   $title=aff_langue($title);
   // Bloc caché
   $hidden=false;
   if (substr($content,0,7)=="hidden#") {
      $content=str_replace("hidden#","",$content);
      $hidden=true;
   }
   // Si on cherche à charger un JS qui a déjà été chargé par pages.php alors on ne le charge pas ...
   global $pages_js;
   if ($pages_js!="") {
      preg_match('#src="([^"]*)#',$content,$jssrc);
      if (is_array($pages_js)) {
         foreach($pages_js as $jsvalue) {
            if (array_key_exists('1',$jssrc)) {
               if ($jsvalue==$jssrc[1]) {
                  $content="";
                  break;
               }
            }
         }
      } else {
         if (array_key_exists('1',$jssrc)) {
            if ($pages_js==$jssrc[1]) $content="";
         }
      }
   }
   $content=aff_langue($content);
   if (($SuperCache) and ($Xcache!=0)) {
      $cache_clef=md5($content);
      $CACHE_TIMINGS[$cache_clef]=$Xcache;
      $cache_obj = new cacheManager();
      $cache_obj->startCachingBlock($cache_clef);
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache) or ($Xcache==0)) {
      global $user, $admin;
      // For including CLASS AND URI in Block
      global $B_class_title, $B_class_content;
      $B_class_title=""; $B_class_content=""; $R_uri="";
      if (stristr($content,"class-") or stristr($content,"uri")) {
         $tmp=explode("\n",$content);
         $content="";
         while(list($id,$class)=each($tmp)) {
            $temp=explode("#",$class);
            if ($temp[0]=="class-title") {
               $B_class_title=str_replace("\r","",$temp[1]);
            } else if ($temp[0]=="class-content") {
               $B_class_content=str_replace("\r","",$temp[1]);
            } else if ($temp[0]=="uri") {
               $R_uri=str_replace("\r","",$temp[1]);
            } else {
               if ($content!="") {$content.="\n ";}
               $content.=str_replace("\r","",$class);
            }
         }
      }
      // For BLOC URIs
      if ($R_uri) {
         global $REQUEST_URI;
         $page_ref=basename($REQUEST_URI);
         $tab_uri=explode(" ",$R_uri);
         $R_content=false;
         $tab_pref=parse_url($page_ref);
         $racine_page=$tab_pref['path'];
         $tab_pref=explode("&",$tab_pref['query']);
         while (list(,$RR_uri)=each($tab_uri)) {
            $tab_puri=parse_url($RR_uri);
            $racine_uri=$tab_puri['path'];
            if ($racine_page==$racine_uri) {
               $tab_puri=explode("&",$tab_puri['query']);
               while (list($idx,$RRR_uri)=each($tab_puri)) {
                  if (substr($RRR_uri,-1)=="*") {
                     // si le token contient *
                     if (substr($RRR_uri,0,strpos($RRR_uri,"="))==substr($tab_pref[$idx],0,strpos($tab_pref[$idx],"=")))
                        $R_content=true;
                  } else {
                     // sinon
                     if ($RRR_uri!=$tab_pref[$idx]) {
                        $R_content=false;
                     } else {
                        $R_content=true;
                     }
                  }
               }
            }
         }
         if (!$R_content) $content="";
      }
      // For Javascript in Block
      if (!stristr($content,"javascript")) {
         $content = nl2br($content);
      }
      // For including externale file in block / the return MUST BE in $content
      if (stristr($content,"include#")) {
         $Xcontent=false;
         // You can now, include AND cast a fonction with params in the same bloc !
         if (stristr($content,"function#")) {
            $content=str_replace("<br />","",$content);
            $content=str_replace("<BR />","",$content);
            $content=str_replace("<BR>","",$content);
            $pos = strpos($content,"function#");
            $Xcontent=substr(trim($content),$pos);
            $content=substr(trim($content),8,$pos-10);
         } else {
            $content=substr(trim($content),8);
         }
         include_once($content);
         if ($Xcontent) {$content=$Xcontent;}
      }
      if (!empty($content)) {
         if (($member==1) and (isset($user))) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif ($member==0) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif (($member>1) and (isset($user))) {
            $tab_groupe=valid_group($user);
            if (groupe_autorisation($member,$tab_groupe)) {
               if (!block_fonction($title,$content)) {
                  if (!$hidden)
                     themesidebox($title, $content);
                  else
                     echo $content;
               }
            }
         } elseif (($member==-1) and (!isset($user))) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         } elseif (($member==-127) and (isset($admin)) and ($admin)) {
            if (!block_fonction($title,$content)) {
               if (!$hidden)
                  themesidebox($title, $content);
               else
                  echo $content;
            }
         }
      }
      if (($SuperCache) and ($Xcache!=0)) {
         $cache_obj->endCachingBlock($cache_clef);
      }
   }
}
#autodoc leftblocks() : Meta-Fonction / Blocs de Gauche
function leftblocks() {
   Pre_fab_block("","LB");
}
#autodoc rightblocks() : Meta-Fonction / Blocs de Droite
function rightblocks() {
   Pre_fab_block("","RB");
}
#autodoc oneblock($Xid, $Xblock) : Alias de Pre_fab_block pour meta-lang
function oneblock($Xid, $Xblock) {
   ob_start();
      Pre_fab_block($Xid, $Xblock);
      $tmp=ob_get_contents();
   ob_end_clean();
   return ($tmp);
}
#autodoc Pre_fab_block($Xid, $Xblock) : Assure la fabrication d'un ou de tous les blocs Gauche et Droite
function Pre_fab_block($Xid, $Xblock) {
    global $NPDS_Prefix;
    if ($Xid) {
      if ($Xblock=="RB") {
         $result = sql_query("select title, content, member, cache, actif, id, css from ".$NPDS_Prefix."rblocks where id='$Xid'");
      } else {
         $result = sql_query("select title, content, member, cache, actif, id, css from ".$NPDS_Prefix."lblocks where id='$Xid'");
      }
    } else {
      if ($Xblock=="RB") {
         $result = sql_query("select title, content, member, cache, actif, id, css from ".$NPDS_Prefix."rblocks order by Rindex ASC");
      } else {
         $result = sql_query("select title, content, member, cache, actif, id, css from ".$NPDS_Prefix."lblocks order by Lindex ASC");
      }
    }
    global $bloc_side;
    if ($Xblock=="RB") {
      $bloc_side="RIGHT";
    } else {
      $bloc_side="LEFT";
    }
    while (list($title, $content, $member, $cache, $actif, $id, $css)=sql_fetch_row($result)) {
      if (($actif) or ($Xid)) {
         if ($css==1){
            echo "\n<div id=\"".$Xblock."_".$id."\">";
         } else {
            echo "\n<div class=\"".strtolower($bloc_side)."bloc\">";
         }
         fab_block($title, $member, $content, $cache);
         echo "\n</div>";
      }
    }
    sql_free_result($result);
}
#autodoc niv_block($Xcontent) : Retourne le niveau d'autorisation d'un block (et donc de certaines fonctions) / le paramètre est le contenu du bloc (function#....)
function niv_block($Xcontent) {
   global $NPDS_Prefix;
   $result = sql_query("select content, member, actif from ".$NPDS_Prefix."rblocks where (content like '%$Xcontent%')");
   if (sql_num_rows($result)) {
      list($content, $member, $actif) = sql_fetch_row($result);
      return ($member.",".$actif);
   }
   $result = sql_query("select content, member, actif from ".$NPDS_Prefix."lblocks where (content like '%$Xcontent%')");
   if (sql_num_rows($result)) {
      list($content, $member, $actif) = sql_fetch_row($result);
      return ($member.",".$actif);
   }
   sql_free_result($result);
}
#autodoc autorisation_block($Xcontent) : Retourne une chaine contenant la liste des autorisations (-127,-1,0,1,2...126)) SI le bloc est actif SINON "" / le param&egrave;tre est le contenu du bloc (function#....)
function autorisation_block($Xcontent) {
   $auto=explode(",", niv_block($Xcontent));
   // le dernier indice indique si le bloc est actif
   $actif=$auto[count($auto)-1];
   // on dépile le dernier indice
   array_pop($auto);
   foreach($auto as $autovalue) {
      if (autorisation($autovalue))
         $autoX[]=$autovalue;
   }
   if ($actif) {
      return ($autoX);
   } else {
      return("");
   }
}
#autodoc autorisation($auto) : Retourne true ou false en fonction des param&egrave;tres d'autorisation de NPDS (Administrateur, anonyme, Membre, Groupe de Membre, Tous)
function autorisation($auto) {
   global $user, $admin;
   $affich=false;
   if (($auto==-1) and (!$user)) {$affich=true;}
   if (($auto==1) and (isset($user))) {$affich=true;}
   if ($auto>1) {
      $tab_groupe=valid_group($user);
      if ($tab_groupe) {
         foreach($tab_groupe as $groupevalue) {
            if ($groupevalue==$auto) {
               $affich=true;
               break;
            }
         }
      }
   }
   if ($auto==0) {$affich=true;}
   if (($auto==-127) and ($admin)) {$affich=true;}
   return ($affich);
}
#autodoc getTopics($s_sid) : Retourne le nom, l'image et le texte d'un topic ou False
function getTopics($s_sid) {
   global $NPDS_Prefix;
   global $topicname, $topicimage, $topictext;
   $sid = $s_sid;
   $result=sql_query("SELECT topic FROM ".$NPDS_Prefix."stories where sid='$sid'");
   if ($result) {
      list($topic) = sql_fetch_row($result);
      $result=sql_query("SELECT topicid, topicname, topicimage, topictext FROM ".$NPDS_Prefix."topics where topicid='$topic'");
      if ($result) {
         list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result);
         return (true);
      } else {
         return (false);
      }
   } else {
      return (false);
   }
}
#autodoc subscribe_mail($Xtype, $Xtopic,$Xforum, $Xresume, $Xsauf) : Assure l'envoi d'un mail pour un abonnement
function subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf) {
   // $Xtype : topic, forum ... / $Xtopic clause where / $Xforum id of forum / $Xresume Text passed / $Xsauf not this userid
   global $NPDS_Prefix;
   global $sitename, $nuke_url;
   if ($Xtype=="topic") {
      $result=sql_query("select topictext from ".$NPDS_Prefix."topics where topicid='$Xtopic'");
      list($abo)=sql_fetch_row($result);
      $result=sql_query("select uid from ".$NPDS_Prefix."subscribe where topicid='$Xtopic'");
   }
   if ($Xtype=="forum")  {
      $result=sql_query("select forum_name, arbre from ".$NPDS_Prefix."forums where forum_id='$Xforum'");
      list($abo, $arbre)=sql_fetch_row($result);
      if ($arbre)
         $hrefX="viewtopicH.php";
      else
         $hrefX="viewtopic.php";
      $resultZ=sql_query("SELECT topic_title FROM ".$NPDS_Prefix."forumtopics WHERE topic_id='$Xtopic'");
      list($title_topic)=sql_fetch_row($resultZ);
      $result=sql_query("select uid from ".$NPDS_Prefix."subscribe where forumid='$Xforum'");
   }
   include_once("language/lang-multi.php");
   while(list($uid) = sql_fetch_row($result)) {
      if ($uid!=$Xsauf) {
         $resultX=sql_query("select email, user_langue from ".$NPDS_Prefix."users where uid='$uid'");
         list($email, $user_langue)=sql_fetch_row($resultX);
         if ($Xtype=="topic") {
            $entete=translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ").translate_ml($user_langue, "Sujet")." => ".strip_tags($abo)."\n\n";
            $resume=translate_ml($user_langue, "Le titre de la dernière publication est")." => $Xresume\n\n";
            $url=translate_ml($user_langue, "L'URL pour cet article est : ")."<a href=\"$nuke_url/search.php?query=&topic=$Xtopic\">$nuke_url/search.php?query=&topic=$Xtopic</a>\n\n";
         }
         if ($Xtype=="forum") {
            $entete=translate_ml($user_langue, "Vous recevez ce Mail car vous vous êtes abonné à : ").translate_ml($user_langue, "Forum")." => ".strip_tags($abo)."\n\n";
            $url=translate_ml($user_langue, "L'URL pour cet article est : ")."<a href=\"$nuke_url/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999#last-post\">$nuke_url/$hrefX?topic=$Xtopic&forum=$Xforum&start=9999</a>\n\n";
            $resume=translate_ml($user_langue, "Le titre de la dernière publication est")." => ";
            if ($Xresume!="") {
               $resume.=$Xresume."\n\n";
            } else {
               $resume.=$title_topic."\n\n";
            }
         }
         $subject = translate_ml($user_langue, "Abonnement")." / $sitename";
         $message = $entete;
         $message .= $resume;
         $message .= $url;
         include("signat.php");
         send_email($email, $subject, $message, "", true, "html");
      }
   }
}
#autodoc subscribe_query($Xuser,$Xtype, $Xclef) : Retourne true si le membre est abonn&egrave; à un topic ou forum
function subscribe_query($Xuser,$Xtype, $Xclef) {
   global $NPDS_Prefix;
   if ($Xtype=="topic") {
      $result=sql_query("select topicid from ".$NPDS_Prefix."subscribe where uid='$Xuser' and topicid='$Xclef'");
   }
   if ($Xtype=="forum") {
      $result=sql_query("select forumid from ".$NPDS_Prefix."subscribe where uid='$Xuser' and forumid='$Xclef'");
   }
   list($Xtemp) = sql_fetch_row($result);
   if ($Xtemp!="") {
      return (true);
   } else {
      return (false);
   }
}
#autodoc pollSecur($pollID) : Assure la gestion des sondages membres
function pollSecur($pollID) {
   global $NPDS_Prefix;
   global $user;
   $pollIDX=false;
   $result = sql_query("SELECT pollType FROM ".$NPDS_Prefix."poll_data where pollID='$pollID'");
   if (sql_num_rows($result)) {
      list($pollType)=sql_fetch_row($result);
      $pollClose = (($pollType / 128) >= 1 ? 1 : 0);
      $pollType = $pollType%128;
      if (($pollType==1) and !isset($user)) {
         $pollClose=99;
      }
   }
   return ( array($pollID, $pollClose));
}
#autodoc pollMain($pollID,$pollClose) : Construit le blocs sondages
/*function pollMain($pollID,$pollClose) {
   global $NPDS_Prefix;
   global $maxOptions, $boxTitle, $boxContent, $userimg, $language, $pollcomm;
   global $cookie;
   if (!isset($pollID))
      $pollID = 1;
   if (!isset($url))
      $url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
   $boxContent = "<form action=\"pollBooth.php\" method=\"post\">\n
   <input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\" />\n
   <input type=\"hidden\" name=\"forwarder\" value=\"".$url."\" />\n";
   $result = sql_query("SELECT pollTitle, voters FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
   list($pollTitle, $voters) = sql_fetch_row($result);
   global $block_title;
   if ($block_title=="")
      $boxTitle=translate("Survey");
   else
      $boxTitle=$block_title;
   $boxContent .= "<div class=\"titboxcont\">".aff_langue($pollTitle)."</div>\n";
   $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID' and optionText<>'') order by voteID");
   $sum = 0;
   if (!$pollClose) {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "<input type=\"radio\" name=\"voteID\" value=\"".$object['voteID']."\" />&nbsp;".aff_langue($object['optionText'])."<br />\n";
         $sum = $sum + $object['optionCount'];
      }
   } else {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "&nbsp;".aff_langue($object['optionText'])."<br />\n";
         $sum = $sum + $object['optionCount'];
      }
   }
   if (!$pollClose) {
      if ($ibid=theme_image("box/vote.gif")) {$imgtmp=$ibid;} elseif ($ibid=theme_image("box/$language/vote.gif")) {$imgtmp=$ibid;}
      else {$imgtmp="images/menu/$language/vote.gif";}
      $inputvote = "<input type=\"image\" src=\"$imgtmp\" style=\"background: transparent;\" title=\"Vote\" alt=\"Vote\" style=\"border:0px;\" />&nbsp;&nbsp;";
   }
   if ($ibid=theme_image("box/result.gif")) {$imgtmp=$ibid;} elseif ($ibid=theme_image("box/$language/result.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/menu/$language/result.gif";}
   $boxContent .= "<p align=\"center\">\n".$inputvote
                 ."<a href=\"pollBooth.php?op=results&amp;pollID=$pollID\"><img src=\"$imgtmp\" title=\"R&eacute;sultats, Results\" alt=\"Results, R&eacute;sultats\" border=\"0\" /></a>\n</p>\n";
   $boxContent .= "</form>";
   $boxContent .= "<ul>\n<li><a href=\"pollBooth.php\">".translate("Past Surveys")."</a></li>\n</ul>\n";
   if ($pollcomm) {
      if (file_exists("modules/comments/pollBoth.conf.php")) {
         include ("modules/comments/pollBoth.conf.php");
      }
      list($numcom) = sql_fetch_row(sql_query("select count(*) from ".$NPDS_Prefix."posts where forum_id='$forum' and topic_id='$pollID' and post_aff='1'"));
      $boxContent .= "<ul>\n<li>".translate("Votes: ")." ".$sum."</li>\n<li>".translate("comments:")." ".$numcom."</li>\n</ul>\n";
   } else {
      $boxContent .= "<ul>\n<li>".translate("Votes: ")." ".$sum."</li>\n</ul>\n";
   }
   themesidebox($boxTitle, $boxContent);
}*/



#autodoc pollMain($pollID,$pollClose) : Construit le blocs sondages par phr
function pollMain($pollID,$pollClose) {
   global $NPDS_Prefix;
   global $maxOptions, $boxTitle, $boxContent, $userimg, $language, $pollcomm;
   global $cookie;
   if (!isset($pollID))
      $pollID = 1;
   if (!isset($url))
      $url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
   $boxContent = "
   <form class=\"form\" role=\"form\" action=\"pollBooth.php\" method=\"post\">\n
   <input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\" />\n
   <input type=\"hidden\" name=\"forwarder\" value=\"".$url."\" />\n";
   $result = sql_query("SELECT pollTitle, voters FROM ".$NPDS_Prefix."poll_desc WHERE pollID='$pollID'");
   list($pollTitle, $voters) = sql_fetch_row($result);
   global $block_title;
   if ($block_title=="")
      $boxTitle=translate("Survey");
   else
      $boxTitle=$block_title;
   $boxContent .= "<legend>".aff_langue($pollTitle)."</legend>\n";
   $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$NPDS_Prefix."poll_data WHERE (pollID='$pollID' and optionText<>'') order by voteID");
   $sum = 0;
   if (!$pollClose) {
         $boxContent .= "<div class=\"form-group\">";
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "<div class=\"radio\"><label><input type=\"radio\" name=\"voteID\" value=\"".$object['voteID']."\" /> ".aff_langue($object['optionText'])."</label></div>\n";
         $sum = $sum + $object['optionCount']; 
      }
         $boxContent .= "</div>";
   } else {
      while($object=sql_fetch_assoc($result)) {
         $boxContent .= "&nbsp;".aff_langue($object['optionText'])."<br />\n";
         $sum = $sum + $object['optionCount'];
      }
   }
   if (!$pollClose) {
      $inputvote = '<button class="btn btn-primary-outline btn-sm btn-block" type="submit" value="'.translate("Vote").'" title="'.translate("Vote").'" /><i class="fa fa-check fa-lg"></i> '.translate("Vote").'</button>';
   }
   $boxContent .= '
   <div class="form-group">'.$inputvote.'</div>
   </form>';
   $boxContent .= '<a href="pollBooth.php?op=results&amp;pollID='.$pollID.'" title="'.translate("Results").'">'.translate("Results").'</a>';
   $boxContent .= "&nbsp;&nbsp;<a href=\"pollBooth.php\">".translate("Past Surveys")."</a>\n";
   if ($pollcomm) {
      if (file_exists("modules/comments/pollBoth.conf.php")) {
         include ("modules/comments/pollBoth.conf.php");
      }
      list($numcom) = sql_fetch_row(sql_query("select count(*) from ".$NPDS_Prefix."posts where forum_id='$forum' and topic_id='$pollID' and post_aff='1'"));
      $boxContent .= '
      <ul>
         <li>'.translate("Votes: ").' <span class="label label-pill label-default pull-right">'.$sum.'</span></li>
         <li>'.translate("comments:").' <span class="label label-pill label-default pull-right">'.$numcom.'</span></li>
      </ul>';
   } else {
      $boxContent .= '
      <ul>
         <li>'.translate("Votes: ").' <span class="label label-pill label-default pull-right">'.$sum.'</span></li>
      <ul>';
   }
   
   themesidebox($boxTitle, $boxContent);
}

#autodoc fab_edito() : Construit l'edito
function fab_edito() {
   global $cookie;
   if (isset($cookie[3])) {
      if (file_exists("static/edito_membres.txt")) {
         $fp=fopen("static/edito_membres.txt","r");
         if (filesize("static/edito_membres.txt")>0)
            $Xcontents=fread($fp,filesize("static/edito_membres.txt"));
         fclose($fp);
      } else {
         if (file_exists("static/edito.txt")) {
            $fp=fopen("static/edito.txt","r");
            if (filesize("static/edito.txt")>0)
               $Xcontents=fread($fp,filesize("static/edito.txt"));
            fclose($fp);
         }
      }
   } else {
      if (file_exists("static/edito.txt")) {
         $fp=fopen("static/edito.txt","r");
         if (filesize("static/edito.txt")>0)
            $Xcontents=fread($fp,filesize("static/edito.txt"));
         fclose($fp);
      }
   }
   $affich=false;
   $Xibid=strstr($Xcontents,"aff_jours");
   if ($Xibid) {
      parse_str($Xibid);
      if (($aff_date+($aff_jours*86400))-time()>0) {
         $affichJ=false; $affichN=false;
         if ((NightDay()=="Jour") and ($aff_jour=="checked")) {$affichJ=true;}
         if ((NightDay()=="Nuit") and ($aff_nuit=="checked")) {$affichN=true;}
      }
      $XcontentsT=substr($Xcontents,0,strpos($Xcontents,"aff_jours"));
      $contentJ=substr($XcontentsT,strpos($XcontentsT,"[jour]")+6,strpos($XcontentsT,"[/jour]")-6);
      $contentN=substr($XcontentsT,strpos($XcontentsT,"[nuit]")+6,strpos($XcontentsT,"[/nuit]")-19-strlen($contentJ));
      $Xcontents="";
      if ($affichJ) {
         $Xcontents=$contentJ;
      }
      if ($affichN) {
         if ($contentN!="")
            $Xcontents=$contentN;
         else
            $Xcontents=$contentJ;
      }
      if ($Xcontents!="") $affich=true;
   } else {
      $affich=true;
   }
   $Xcontents=meta_lang(aff_langue($Xcontents));
   return array($affich, $Xcontents);
}
#autodoc aff_langue($ibid) : Analyse le contenu d'une chaine et converti la section correspondante ([langue] OU [!langue] ...[/langue]) &agrave; la langue / [transl] ... [/transl] permet de simuler un appel translate("xxxx")
function aff_langue($ibid) {
   global $language, $tab_langue;
   // copie du tabelau + rajout de transl pour gestion de l'appel à translate(...); - Theme Dynamic
   $tab_llangue=$tab_langue;
   $tab_llangue[]="transl";
   reset ($tab_llangue);
   $ok_language=false;
   $trouve_language=false;
   while (list($bidon, $lang)=each($tab_llangue)) {
      $pasfin=true; $pos_deb=false; $abs_pos_deb=false; $pos_fin=false;
      while ($pasfin) {
         // tags [langue] et [/langue]
         $pos_deb=strpos($ibid,"[$lang]",0);
         $pos_fin=strpos($ibid,"[/$lang]",0);
         if ($pos_deb===false) {$pos_deb=-1;}
         if ($pos_fin===false) {$pos_fin=-1;}
         // tags [!langue]
         $abs_pos_deb=strpos($ibid,"[!$lang]",0);
         if ($abs_pos_deb!==false) {
            $ibid=str_replace("[!$lang]", "[$lang]", $ibid);
            $pos_deb=$abs_pos_deb;
            if ($lang!=$language) {$trouve_language=true;}
         }
         $decal=strlen($lang)+2;
         if (($pos_deb>=0) and ($pos_fin>=0)) {
            $fragment=substr($ibid,$pos_deb+$decal,($pos_fin-$pos_deb-$decal));
            if ($trouve_language==false) {
               if ($lang!="transl")
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", $fragment, $ibid);
               else
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", translate($fragment), $ibid);
               $ok_language=true;
            } else {
               if ($lang!="transl")
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", "", $ibid);
               else
                  $ibid=str_replace("[$lang]".$fragment."[/$lang]", translate($fragment), $ibid);
            }
         } else {
            $pasfin=false;
         }
      }
      if ($ok_language) {
         $trouve_language=true;
      }
   }
   return ($ibid);
}
#autodoc make_tab_langue() : Charge le tableau TAB_LANGUE qui est utilis&eacute; par les fonctions multi-langue
function make_tab_langue() {
   global $language, $languageslist;
   $languageslocal=$language." ".str_replace($language,"",$languageslist);
   $languageslocal=trim(str_replace("  "," ",$languageslocal));
   $tab_langue=explode(" ",$languageslocal);
   return ($tab_langue);
}
#autodoc aff_localzone_langue($ibid) : Charge une zone de formulaire de selection de la langue
function aff_localzone_langue($ibid) {
   global $tab_langue;
   reset ($tab_langue);
   $M_langue= "<ul><li><select name=\"$ibid\" class=\"form-control textbox_standard\">";
   $M_langue.="<option value=\"\">".translate("Select a language")."</option>";
   while (list($bidon, $langue)=each($tab_langue)) {
      $M_langue.="<option value=\"$langue\">".$langue."</option>";
   }
   $M_langue.="<option value=\"\">- ".translate("No language")."</option>";
   $M_langue.="</select></li><li><input class=\"btn btn-primary\" type=\"submit\" name=\"local_sub\" value=\"".translate("Submit")."\" /></li></ul>";
   return ($M_langue);
}
#autodoc aff_local_langue($mess, $ibid_index, $ibid) : Charge une FORM de selection de langue $ibid_index = URL de la Form, $ibid = nom du champ
function aff_local_langue($mess="" ,$ibid_index, $ibid) {
   if ($ibid_index=="") {
      global $REQUEST_URI;
      $ibid_index=$REQUEST_URI;
   }
   $M_langue ="<form action=\"$ibid_index\" name=\"local_user_language\" method=\"post\">";
   $M_langue.=$mess.aff_localzone_langue($ibid);
   $M_langue.="</form>";
   return($M_langue);
}
#autodoc preview_local_langue($local_user_language,$ibid) : appel la fonction aff_langue en modifiant temporairement la valeur de la langue
function preview_local_langue($local_user_language,$ibid) {
   if ($local_user_language) {
      global $language, $tab_langue;
      $old_langue=$language;
      $language=$local_user_language;
         $tab_langue=make_tab_langue();
         $ibid=aff_langue($ibid);
      $language=$old_langue;
   }
   return ($ibid);
}
#autodoc aff_code($ibid) : Analyse le contenu d'une chaine et converti les balises [code]...[/code]
function aff_code($ibid) {
   $pasfin=true;
   while ($pasfin) {
      $pos_deb=strpos($ibid,"[code]",0);
      $pos_fin=strpos($ibid,"[/code]",0);
      // ne pas confondre la position ZERO et NON TROUVE !
      if ($pos_deb===false) {$pos_deb=-1;}
      if ($pos_fin===false) {$pos_fin=-1;}
      if (($pos_deb>=0) and ($pos_fin>=0)) {
         ob_start();
            highlight_string(substr($ibid,$pos_deb+6,($pos_fin-$pos_deb-6)));
            $fragment=ob_get_contents();
         ob_end_clean();
         $ibid=str_replace(substr($ibid,$pos_deb,($pos_fin-$pos_deb+7)),$fragment,$ibid);
      } else {
         $pasfin=false;
      }
   }
   return ($ibid);
}
#autodoc is_admin($xadmin) : Phpnuke compatibility functions
function is_admin($xadmin) {
   global $admin;
   if (isset($admin) and ($admin!="")) {
      return (true);
   } else {
      return (false);
   }
}
#autodoc is_user($xuser) : Phpnuke compatibility functions
function is_user($xuser) {
   global $user;
   if (isset($user) and ($user!="")) {
      return (true);
   } else {
      return (false);
   }
}
#autodoc split_string_without_space($msg, $split) : Découpe la chaine en morceau de $slpit longueur si celle-ci ne contient pas d'espace / Snipe 2004
function split_string_without_space($msg, $split) {
   $Xmsg=explode(" ",$msg);
   array_walk($Xmsg,'wrapper_f', $split);
   $Xmsg=implode(" ",$Xmsg);
   return ($Xmsg);
}
#autodoc wrapper_f (&$string, $key, $cols) : Fonction Wrapper pour split_string_without_space / Snipe 2004
function wrapper_f (&$string, $key, $cols) {
   if (!(stristr($string,'IMG src=') or stristr($string,'A href=') or stristr($string,'HTTP:') or stristr($string,'HTTPS:') or stristr($string,'MAILTO:') or stristr($string,'[CODE]'))) {
      $outlines = '';
      if (strlen($string) > $cols) {
         while(strlen($string) > $cols) {
            $cur_pos = 0;
            for($num=0; $num < $cols-1; $num++) {
               $outlines .= $string[$num];
               $cur_pos++;
               if ($string[$num]=="\n") {
                  $string = substr($string, $cur_pos, (strlen($string)-$cur_pos));
                  $cur_pos=0;
                  $num = 0;
               }
            }
            $outlines .= " ";
            $string = substr($string, $cur_pos, (strlen($string)-$cur_pos));
         }
         $string=$outlines.$string;
      }
   }
}
#autodoc preg_anti_spam($str) : Permet l'utilisation de la fonction anti_spam via preg_replace
function preg_anti_spam($ibid) {
   // Adaptation - David MARTINET alias Boris (2011)
   return("<a href=\"mailto:".anti_spam($ibid, 1)."\" target=\"_blank\" class=\"noir\">".anti_spam($ibid, 0)."</a>");  
}  
#autodoc anti_spam($str [, $highcode]) : Encode une chaine en mélangeant caractères normaux, codes décimaux et hexa. Si $highcode == 1, utilise également le codage ASCII (compatible uniquement avec des mailto et des URL, pas pour affichage)
function anti_spam($str, $highcode = 0) {
   // Idée originale : Pomme (2004). Nouvelle version : David MARTINET alias Boris (2011)
   $str_encoded = "";  
   mt_srand((double)microtime()*1000000);
   for($i = 0; $i < strlen($str); $i++) {
      if ($highcode==1) {
         $alea=mt_rand(1, 400);
         $modulo=4;
      } else { 
         $alea=mt_rand(1, 300);
         $modulo=3;
      }
      switch (($alea % $modulo)) {
         case 0: 
            $str_encoded.=$str[$i];
            break;  
         case 1: 
            $str_encoded.="&#".ord($str[$i]).";";
            break;  
         case 2: 
            $str_encoded.="&#x".bin2hex($str[$i]).";";
            break;  
         case 3: 
            $str_encoded.="%".bin2hex($str[$i])."";
            break;  
         default: 
            $str_encoded="Error";
            break;  
      }  
   }
   return $str_encoded;
}
#autodoc aff_editeur($Xzone, $Xactiv) : Charge l'éditeur ... ou non : $Xzone = nom du textarea / $Xactiv = deprecated <br /> si $Xzone="custom" on utilise $Xactiv pour passer des param&egrave;tres spécifiques
function aff_editeur($Xzone, $Xactiv) {
   global $language, $tiny_mce,$tiny_mce_theme,$tiny_mce_relurl;
   if ($language=="french") $tiny_lang="fr";
   if ($language=="english") $tiny_lang="en";
   if ($language=="chinese") $tiny_lang="zh-cn";
   if ($language=="spanish") $tiny_lang="es";
   if ($language=="german") $tiny_lang="de";
   $tmp="";
   if ($tiny_mce) {
      static $tmp_Xzone;
      if ($Xzone=="tiny_mce") {
         if ($Xactiv=="end") {
            if (substr($tmp_Xzone,-1)==",")
               $tmp_Xzone=substr_replace($tmp_Xzone,"",-1);
            if ($tmp_Xzone) {
               $tmp="<script type=\"text/javascript\">\n";
               $tmp.="//<![CDATA[\n";
               $tmp.="tinymce.init({\n";
               $tmp.="theme : \"advanced\",\n";
               $tmp.="language : \"".$tiny_lang."\",\n";
               $tmp.="mode : \"specific_textareas\",\n";
               include ("editeur/tiny_mce/themes/advanced/npds.conf.php");
               $tmp.="});\n";
               $tmp.="//]]>\n";
               $tmp.="</script>\n";
            }
         } else {
            $tmp.="<script type=\"text/javascript\" src=\"editeur/tiny_mce/tiny_mce.js\"></script>\n";
         }
	// début d'implémentation tiny brute phr 020515	 
/*				$tmp="<script type=\"text/javascript\">\n";
				$tmp.="//<![CDATA[\n";
				$tmp.="tinymce.init({\n";
				$tmp.="selector : \"textarea\",\n";
				$tmp.="plugins: [
				\"advlist autolink lists link image charmap print preview hr anchor pagebreak\",
				\"searchreplace wordcount visualblocks visualchars code fullscreen\",
				\"insertdatetime media nonbreaking save table contextmenu directionality\",
				\"emoticons template paste textcolor colorpicker textpattern\"
				],\n";
	
				$tmp.="toolbar1: \"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image\",\n";
				
				$tmp.="toolbar2: \"print preview media | forecolor backcolor emoticons\",\n";
				$tmp.="image_advtab: true\n";
				
				$tmp.="});\n";
				$tmp.="//]]>\n";
				$tmp.="</script>\n";
            }
         } else {
            $tmp.="<script type=\"text/javascript\" src=\"editeur/tinymce/tinymce.min.js\"></script>\n";
         }		 
//fin d'implémentation tiny brute phr 020515		*/ 
		 
      } else {
         if ($Xzone!="custom") {
            $tmp_Xzone.=$Xzone.",";
         } else {
            $tmp_Xzone.=$Xactiv.",";
         }
      }
   } else {
      $tmp="";
   }
   return ($tmp);
}
#autodoc utf8_java($ibid) : Encode une chaine UF8 au format javascript - JPB 2005
function utf8_java($ibid) {
   // UTF8 = &#x4EB4;&#x6B63;&#7578; / javascript = \u4EB4\u6B63\u.dechex(7578)
   $tmp=explode ("&#",$ibid);
   while(list(,$bidon)=each($tmp)) {
      if ($bidon) {
         $bidon=substr($bidon,0,strpos($bidon,";"));
         $hex=strpos($bidon,"x");
         if ($hex===false) {
            $ibid=str_replace("&#".$bidon.";","\\u".dechex($bidon),$ibid);
         } else {
            $ibid=str_replace("&#".$bidon.";","\\u".substr($bidon,1),$ibid);
         }
      }
   }
   return ($ibid);
}
#autodoc wrh($ibid) : Formate une chaine num&eacute;rique avec un espace tous les 3 chiffres / cheekybilly 2005
function wrh($ibid) {
   $tmp=number_format($ibid,0,","," ");
   $tmp=str_replace(" ","&nbsp;",$tmp);
   return ($tmp);
}
#autodoc Q_spambot() : forge un champ de formulaire (champ de saisie : $asb_reponse / champ hidden : asb_question) permettant de déployer une fonction anti-spambot
function Q_spambot() {
   // Idée originale, développement et intégration - Gérald MARINO alias neo-machine
   // Rajout brouillage anti_spam() : David MARTINET, alias Boris (2011)
   // Other stuff : Dev 2012
   global $user;
   $asb_question = array (
   '4 - (3 / 1)'       => 1,
   '7 - 5 - 0'         => 2,
   '2 + (1 / 1)'       => 3,
   '2 + (1 + 1)'       => 4,
   '3 + (0) + 2'       => 5,
   '3 + (9 / 3)'       => 6,
   '4 + 3 - 0'         => 7,
   '6 + (0) + 2'       => 8,
   '8 + (5 - 4)'       => 9,
   '0 + (6 + 4)'       => 10,
   '(5 * 2) + 1'       => 11,
   '6 + (3 + 3)'       => 12,
   '1 + (6 * 2)'       => 13,
   '(8 / 1) + 6 '      => 14,
   '6 + (5 + 4)'       => 15,
   '8 + (4 * 2)'       => 16,
   '1 + (8 * 2)'       => 17,
   '9 + (3 + 6)'       => 18,
   '(7 * 2) + 5'       => 19,
   '(8 * 3) - 4'       => 20,
   '7 + (2 * 7)'       => 21,
   '9 + 5 + 8'         => 22,
   '(5 * 4) + 3'       => 23,
   '0 + (8 * 3)'       => 24,
   '1 + (4 * 6)'       => 25,
   '(6 * 5) - 4'       => 26,
   '3 * (9 + 0)'       => 27,
   '4 + (3 * 8)'       => 28,
   '(6 * 4) + 5'       => 29,
   '0 + (6 * 5)'       => 30);
   
   // START ALEA
   mt_srand((double)microtime()*1000000);
      // choix de la question
      $asb_index = mt_rand(0,count($asb_question)-1);
      $ibid=array_keys($asb_question);
      $aff = $ibid[$asb_index];

      // translate
      $tab=explode(" ", str_replace(")","",str_replace("(","",$aff))); 
      $al1=mt_rand(0,count($tab)-1);
      $aff=str_replace($tab[$al1],translate($tab[$al1]),$aff);
      
      // mis en majuscule
      if ($asb_index%2)
         $aff = ucfirst($aff);
   // END ALEA   
   
   //Captcha - si GD
   if (function_exists("imagepng")) {
      $aff="<img src=\"getfile.php?att_id=".rawurlencode(encrypt($aff." = "))."&amp;apli=captcha\" style=\"vertical-align: middle;\" />";
   } else {
      $aff="".anti_spam($aff." = ",0)."";
   }
   
   $tmp="";
   if (!isset($user)) {
//      $tmp='<span class="rouge">'.translate("Anti-Spam / Thank to reply to the question :").'</span> '.$aff.' <input class="textbox_standard" type="text" name="asb_reponse" size="3" maxlength="2" onclick="this.value" />';
//      $tmp.='<input type="hidden" name="asb_question" value="'.encrypt($ibid[$asb_index].','.time()).'" />';
/*début remplacement phr*/

   $tmp='
      <div class="form-group">
         <div class="col-sm-9">
            <label class="control-label text-danger" for="asb_reponse">'.translate("Anti-Spam / Thank to reply to the question :").'&nbsp;&nbsp;'.$aff.'</label>
         </div>
         <div class="col-sm-2 col-md-2 text-xs-right">
            <input class="form-control" type="text" name="asb_reponse" onclick="this.value" />
            <input type="hidden" name="asb_question" value="'.encrypt($ibid[$asb_index].','.time()).'" />
         </div>
      </div>';
/*fin remplacement phr*/
   } else {
      $tmp='<input type="hidden" name="asb_question" value="" /><input type="hidden" name="asb_reponse" value="" />';
   }
   return ($tmp);
}
#autodoc L_spambot($ip, $status) : Log spambot activity : $ip="" => getip of the current user OR $ip="x.x.x.x" / $status = Op to do : true => not log or suppress log - false => log+1 - ban => Ban an IP 
function L_spambot($ip, $status) {
   $cpt_sup=0;
   $maj_fic=false;
   if ($ip=="")
      $ip=getip();
   if (file_exists("slogs/spam.log")) {
      $tab_spam=str_replace("\r\n","",file("slogs/spam.log"));
      if (in_array($ip.":1",$tab_spam))
         $cpt_sup=2;
      if (in_array($ip.":2",$tab_spam))
         $cpt_sup=3;
      if (in_array($ip.":3",$tab_spam))
         $cpt_sup=4;
      if (in_array($ip.":4",$tab_spam))
         $cpt_sup=5;
   }
   if ($cpt_sup) {
      if ($status=="false") {
         $tab_spam[array_search($ip.":".($cpt_sup-1),$tab_spam)]=$ip.":".$cpt_sup;
      } else if ($status=="ban") {
         $tab_spam[array_search($ip.":".($cpt_sup-1),$tab_spam)]=$ip.":5";
      } else {
         $tab_spam[array_search($ip.":".($cpt_sup-1),$tab_spam)]="";
      }
      $maj_fic=true;
   } else {
      if ($status=="false") {
         $tab_spam[]=$ip.":1";
         $maj_fic=true;
      } else if ($status=="ban") {
         if (!in_array($ip.":5",$tab_spam)) {
            $tab_spam[]=$ip.":5";
            $maj_fic=true;
         }
      }
   }
   if ($maj_fic) {
      $file = fopen("slogs/spam.log", "w");
      while (list ($key, $val) = each ($tab_spam)) {
         if ($val)
            fwrite($file, $val."\r\n");
      }
      fclose($file);
   }
}
#autodoc R_spambot($asb_question, $asb_reponse, $message) : valide le champ $asb_question avec la valeur de $asb_reponse (anti-spambot) et filtre le contenu de $message si nécessaire
function R_spambot($asb_question, $asb_reponse, $message="") {
   // idée originale, développement et intégration - Gérald MARINO alias neo-machine
   global $user;
   global $REQUEST_METHOD;
   if ($REQUEST_METHOD=="POST") {
      if (!isset($user)) {
         if ( ($asb_reponse!="") and (is_numeric($asb_reponse)) and (strlen($asb_reponse)<=2) ) {
            $ibid=decrypt($asb_question);
            $ibid=explode(",",$ibid);
            $result="\$arg=($ibid[0]);";
            // submit intervient en moins de 5 secondes (trop vite) ou plus de 30 minutes (trop long)
            $temp=time()-$ibid[1];
            if (($temp<1800) and ($temp>5)) {
               eval($result);
            } else {
               $arg=uniqid(mt_rand());
            }
         } else {
            $arg=uniqid(mt_rand());
         }
         if ($arg==$asb_reponse) {
            // plus de 2 http:// dans le texte
            preg_match_all('#http://#',$message,$regs);
            if (count($regs[0])>2) {
               L_spambot("","false");
               return (false);
            } else {
               L_spambot("","true");
               return (true);
            }
         } else {
            L_spambot("","false");
            return (false);
         }
      } else {
         L_spambot("","true");
         return (true);
      }
   } else {
      L_spambot("","false");
      return (false);
   }
}
#autodoc keyED($txt,$encrypt_key) : Composant des fonctions encrypt et decrypt
function keyED($txt,$encrypt_key) {
   $encrypt_key = md5($encrypt_key);
   $ctr=0;
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       if ($ctr==strlen($encrypt_key) ) $ctr=0;
       $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
       $ctr++;
   }
   return $tmp;
}
#autodoc encrypt($txt) : retourne une chaine encryptée en utilisant la valeur de $NPDS_Key
function encrypt($txt) {
   global $NPDS_Key;
   return (encryptK($txt,$NPDS_Key));
}
#autodoc encryptK($txt, $C_key) : retourne une chaine encryptée en utilisant la clef : $C_key
function encryptK($txt, $C_key) {
   srand( (double)microtime()*1000000);
   $encrypt_key = md5(rand(0,32000) );
   $ctr=0;
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       if ($ctr==strlen($encrypt_key) ) $ctr=0;
       $tmp.= substr($encrypt_key,$ctr,1) .
       (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1) );
       $ctr++;
   }
   return base64_encode(keyED($tmp,$C_key));
}
#autodoc decrypt($txt) : retourne une chaine décryptée en utilisant la valeur de $NPDS_Key
function decrypt($txt) {
   global $NPDS_Key;
   return (decryptK($txt, $NPDS_Key));
}
#autodoc decryptK($txt, $C_key) : retourne une décryptée en utilisant la clef de $C_Key
function decryptK($txt, $C_key) {
   $txt = keyED(base64_decode($txt),$C_key);
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       $md5 = substr($txt,$i,1);
       $i++;
       $tmp.= (substr($txt,$i,1) ^ $md5);
   }
   return ($tmp);
}
#autodoc conv2br($txt) : convertie \r \n  BR ... en br XHTML
function conv2br($txt) {
   $Xcontent=str_replace("\r\n","<br />",$txt);
   $Xcontent=str_replace("\r","<br />",$Xcontent);
   $Xcontent=str_replace("\n","<br />",$Xcontent);
   $Xcontent=str_replace("<BR />","<br />",$Xcontent);
   $Xcontent=str_replace("<BR>","<br />",$Xcontent);
   return ($Xcontent);
}
#autodoc hexfromchr($txt) : Les 8 premiers caracteres sont converties en UNE valeur Hexa unique 
function hexfromchr($txt) {
   $surlignage=substr(md5($txt),0,8);
   $tmp=0;
   for ($ix = 0; $ix <= 5; $ix++) {
      $tmp+=hexdec($surlignage[$ix])+1;
   }
   return ($tmp%=16);
}

#autodoc:<Mainfile.php>
#autodoc:
#autodoc <font color=green>BLOCS NPDS</font>:
#autodoc Site_Activ() : Bloc activit&eacute; du site <br />=> syntaxe : function#Site_Activ
function Site_Activ() {
   global $startdate, $top;
   list($membres,$totala,$totalb,$totalc,$totald,$totalz)=req_stat();
   $who_online="<p align=\"center\">".translate("Pages showed since")." $startdate : ".wrh($totalz)."</p>\n
   <ul>
     <li>".translate("Nb of members")." : ".wrh($membres)."</li>\n
     <li>".translate("Nb of articles")." : ".wrh($totala)."</li>\n
     <li>".translate("Nb of forums")." : ".wrh($totalc)."</li>\n
     <li>".translate("Nb of topics")." : ".wrh($totald)."</li>\n
     <li>".translate("Nb of reviews")." : ".wrh($totalb)."</li>\n
   </ul>\n";
   if ($ibid=theme_image("box/top.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;}
   if ($imgtmp) {
      $who_online .= "<p align=\"center\"><a href=\"top.php\"><img alt=\"".translate("Top")." $top\" src=\"$imgtmp\" border=\"0\" /></a>&nbsp;&nbsp;";
      if ($ibid=theme_image("box/stat.gif")) {$imgtmp=$ibid;} else {$imgtmp=false;}
      $who_online .= "<a href=\"stats.php\"><img alt=\"".translate("Statistics")."\" src=\"$imgtmp\" border=\"0\" /></a></p>\n";
   } else {
      $who_online .= "<p align=\"center\"><a href=\"top.php\" class=\"noir\">".translate("Top")." $top</a>&nbsp;&nbsp;";
      $who_online .= "<a href=\"stats.php\" class=\"noir\">".translate("Statistics")."</a></p>\n";
   }
   global $block_title;
   if ($block_title=="")
      $title=translate("Web Activity");
   else
      $title=$block_title;
   themesidebox($title, $who_online);
}
#autodoc online() : Bloc Online (Who_Online) <br />=> syntaxe : function#online
function online() {
   global $NPDS_Prefix;
   global $user,$cookie;
   $ip = getip();
   $username = $cookie[1];
   if (!isset($username)) {
      $username = "$ip";
      $guest = 1;
   }
   $past = time()-300;
   sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE time < '$past'");
   $result = sql_query("SELECT time FROM ".$NPDS_Prefix."session WHERE username='$username'");
   $ctime = time();
   if ($row = sql_fetch_row($result)) {
      sql_query("UPDATE ".$NPDS_Prefix."session SET username='$username', time='$ctime', host_addr='$ip', guest='$guest' WHERE username='$username'");
   } else {
      sql_query("INSERT INTO ".$NPDS_Prefix."session (username, time, host_addr, guest) VALUES ('$username', '$ctime', '$ip', '$guest')");
   }
   $result = sql_query("SELECT username FROM ".$NPDS_Prefix."session where guest=1");
   $guest_online_num = sql_num_rows($result);
   $result = sql_query("SELECT username FROM ".$NPDS_Prefix."session where guest=0");
   $member_online_num = sql_num_rows($result);
   $who_online_num = $guest_online_num + $member_online_num;
   $who_online = "<p align=\"center\">".translate("There are currently,")." $guest_online_num ".translate("guest(s) and")." $member_online_num ".translate("member(s) that are online.")."<br />";
   $content = "$who_online";
   if ($user) {
      $content .= "<br />".translate("You are logged as")." <b>$username</b>.<br />";
      $result = Q_select("select uid from ".$NPDS_Prefix."users where uname='$username'", 86400);
      list(,$uid) = each($result);
      $result2 = sql_query("select to_userid from ".$NPDS_Prefix."priv_msgs where to_userid='".$uid['uid']."' and type_msg='0'");
      $numrow = sql_num_rows($result2);
      $content .= translate("You have")." <a href=\"viewpmsg.php\">$numrow</a> ".translate("private message(s).")."</p>";
   } else {
      $content .= "<br />".translate("You can register for free by clicking")." <a href=\"user.php?op=only_newuser\">".translate("here")."</a></p>";
   }
   global $block_title;
   if ($block_title=="")
      $title=translate("Who's Online");
   else
      $title=$block_title;
   themesidebox($title, $content);
}
#autodoc lnlbox() : Bloc Little News-Letter <br />=> syntaxe : function#lnlbox
/*function lnlbox() {
   global $block_title;
   if ($block_title=="")
      $title=translate("NewsLetter");
   else
      $title=$block_title;
   $boxstuff  = "<form action=\"lnl.php\" method=\"get\">\n
   <p align=\"center\">\n
   <select name=\"op\" class=\"inputa\" style=\"width: 80%\">\n<option value=\"subscribe\">".translate("Subscribe")."</option>\n
   <option value=\"unsubscribe\">".translate("Unsubscribe")."</option>\n
   </select>\n<br />\n
   ".translate("Your email")."<br />\n
   <input type=\"text\" name=\"email\" size=\"12\" maxlength=\"60\" class=\"inputa\" style=\"width: 90%\" />\n
   <br /><br />\n
   <input type=\"submit\" class=\"bouton_standard\" value=\"".translate("Submit")."\" /><br />\n
   <strong>".translate("Sign up now to receive our lastest infos.")."</strong>\n</p>\n
   </form>\n";
   themesidebox($title, $boxstuff);
}*/

#autodoc lnlbox() : Bloc Little News-Letter <br />=> syntaxe : function#lnlbox revu phr mai 2015/revu jpb09/15
function lnlbox() {
   global $block_title;
   if ($block_title=="")
      $title=translate("NewsLetter");
   else
      $title=$block_title;
      $boxstuff = '
         <form action="lnl.php" method="get">
            <div class="form-group">
               <select name="op" class=" c-select form-control">
                  <option value="subscribe">'.translate("Subscribe").'</option>
                  <option value="unsubscribe">'.translate("Unsubscribe").'</option>
               </select>
            </div>
            <div class="form-group">
               <label for="email">'.translate("Your email").'</label>
               <input type="email" name="email" maxlength="60" class="form-control" />
            </div>
            <p><span class="help-block">'.translate("Sign up now to receive our lastest infos.").'</span></p>
            <div class="form-group">
               <div class="row">
                  <div class="col-sm-12">
                     <button type="submit" class="btn btn-primary-outline btn-block btn-sm"><i class ="fa fa-check fa-lg"></i>&nbsp;'.translate("Submit").'</button>
                  </div>
               </div>
            </div>
         </form>'
         .adminfoot('fv','','','0');
   themesidebox($title, $boxstuff);
}
#autodoc searchbox() : Bloc Search-engine <br />=> syntaxe : function#searchbox
function searchbox() {
   global $block_title;
   if ($block_title=="")
      $title=translate("Search");
   else
      $title=$block_title;
   $content ="<form action=\"search.php\" method=\"get\">";
   $content.="<br /><p align=\"center\"><input class=\"inputa\" style=\"width: 90%\" type=\"text\" name=\"query\" size=\"14\" /></p>";
   $content.="</form>";
   themesidebox($title, $content);
}
#autodoc mainblock() : Bloc principal <br />=> syntaxe : function#mainblock
function mainblock() {
   global $NPDS_Prefix;
   $result = sql_query("select title, content from ".$NPDS_Prefix."mainblock");
   list($title, $content) = sql_fetch_row($result);
   global $block_title;
   if ($title=="")
      $title=$block_title;
//   themesidebox(aff_langue($title), nl2br(aff_langue(preg_replace('#<a href=[^>]*(&)[^>]*>#e','str_replace("&","&amp;","\0")',$content))));
   themesidebox(aff_langue($title), nl2br(aff_langue(preg_replace_callback('#<a href=[^>]*(&)[^>]*>#',function (&$r) {return str_replace('&','&amp;',$r[0]);},$content)))); //php7
}
#autodoc adminblock() : Bloc Admin <br />=> syntaxe : function#adminblock
function adminblock() {
   global $NPDS_Prefix;
   global $admin, $aid, $admingraphic, $adminimg, $admf_ext;
   if ($admin) {
   $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$aid' LIMIT 1"));
   if ($Q['radminsuper']==1) {
      $R = sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 AND f.fetat != '0' ORDER BY f.fcategorie");}
   else {
      $R = sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f LEFT JOIN droits d ON f.fid = d.d_fon_fid LEFT JOIN authors a ON d.d_aut_aid =a.aid WHERE f.finterface =1 AND fetat!=0 AND d.d_aut_aid='$aid' AND d.d_droits REGEXP'^1' ORDER BY f.fcategorie");
   }
   while($SAQ=sql_fetch_assoc($R)) {
      $cat[]=$SAQ['fcategorie'];
      $cat_n[]=$SAQ['fcategorie_nom'];
      $fid_ar[]=$SAQ['fid'];
      $adminico=$adminimg.$SAQ['ficone'].'.'.$admf_ext;
      if ($SAQ['fcategorie'] == 9) {
         //==<euh je ne sais plus comment j'avais envisager l'arrivŽe des messages dans la base ???? arghhhhhh 
         if(preg_match ( '#^mes_npds_#', $SAQ['fnom']))
         $li_c ='<li class=" btn btn-secondary" title="'.$SAQ['fretour_h'].'" data-toggle="tooltip">';
         else 
         $li_c ='<li class="alerte btn btn-secondary" title="'.$SAQ['fretour_h'].'" data-toggle="tooltip">';
         $li_c .='<a '.$SAQ['furlscript'].' class="adm_img"><img class="adm_img" src="'.$adminico.'" alt="icon_'.$SAQ['fnom_affich'].'" />'."\n";
         $li_c .='<span class="alerte-para label label-pill label-danger">'.$SAQ['fretour'].'</span>'."\n";
         $li_c .='</a></li>'."\n";
         $bloc_foncts_A .= $li_c;
      } 
   }
   
       $result = sql_query("SELECT title, content FROM ".$NPDS_Prefix."adminblock");
       list($title, $content) = sql_fetch_row($result);
       global $block_title;
       if ($title=="") $title=$block_title;
       else $title=aff_langue($title);
       $content = nl2br(aff_langue(preg_replace_callback('#<a href=[^>]*(&)[^>]*>#',function (&$r) {return str_replace('&','&amp;',$r[0]);},$content)));
       $content .= '
       <ul id="adm_block">
          '.$bloc_foncts_A.'
           <li><a href="powerpack.php?op=admin_chatbox_write&amp;chatbox_clearDB=OK">'.translate("Clear Chat DB").'</a></li>
       </ul>
       <ul>
          <li><small class="text-muted"><i class="fa fa-user fa-lg"></i> '.$aid.'</small></li>
       </ul>';

       /*
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."queue");
       $num = sql_num_rows($result);
       $content .= "<li><a href=\"admin.php?op=submissions\">".translate("Submissions")."</a> : <span class=\"titboxcont\">$num</span></li>\n";
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."reviews_add");
       $num = sql_num_rows($result);
       $content .= "<li><a href=\"admin.php?op=reviews\">".translate("Waiting Reviews")."</a> : <span class=\"titboxcont\">$num</span></li>\n";
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink=1");
       $totalbrokenlinks = sql_num_rows($result);
       $result2 = sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest WHERE brokenlink=0");
       $totalmodrequests = sql_num_rows($result2);
       $result = sql_query("SELECT * FROM ".$NPDS_Prefix."links_newlink");
       $num = sql_num_rows($result);
       $content.= "<li><a href=\"admin.php?op=links\">".translate("Waiting Links")."</a> : <span class=\"titboxcont\">$num / $totalbrokenlinks / $totalmodrequests</span></li>\n</ul>\n";
       $content.= "<ul>\n<li><a href=\"powerpack.php?op=admin_chatbox_write&amp;chatbox_clearDB=OK\">".translate("Clear Chat DB")."</a></li>\n</ul>\n";
       // Jireck 11-2007
       $modu=false;
       $handle=opendir("modules");
       while (false!==($file=readdir($handle))) {
          if ((is_dir("modules/$file")) and file_exists("modules/$file/admin/admblock.php")) {
             if ($modu==false)
                $content.="<hr noshade=\"noshade\" width=\"90%\" class=\"ongl\" />";
             include("modules/$file/admin/admblock.php");
             $modu=true;
          }
       }
       closedir($handle);
       */
       themesidebox($title, $content);
    }
}
#autodoc ephemblock() : Bloc ephemerid <br />=> syntaxe : function#ephemblock
function ephemblock() {
   global $NPDS_Prefix;
   global $gmt;
   $eday=date("d",time()+($gmt*3600));
   $emonth =date("m",time()+($gmt*3600));
   $result = sql_query("select yid, content from ".$NPDS_Prefix."ephem where did='$eday' AND mid='$emonth' order by yid ASC");
   $boxstuff = "<div>".translate("One Day like Today...")."</div>";
   while (list($yid, $content) = sql_fetch_row($result)) {
      if ($cnt==1)
         $boxstuff .= "\n<br />\n";
         $boxstuff .= "<b>$yid</b>\n<br />\n";
         $boxstuff .= aff_langue($content);
         $cnt = 1;
   }
   $boxstuff .= "<br />\n";
   global $block_title;
   if ($block_title=="")
      $title=translate("Ephemerids");
   else
      $title=$block_title;
   themesidebox($title, $boxstuff);
}
#autodoc loginbox() : Bloc Login <br />=> syntaxe : function#loginbox
function loginbox() {
   global $user;
   if (!$user) {
      $boxstuff  = "<form action=\"user.php\" method=\"post\">";
      $boxstuff .= "<p align=\"center\">".translate("Nickname")."<br />";
      $boxstuff .= "<input class=\"inputa\" style=\"width: 90%\" type=\"text\" name=\"uname\" size=\"12\" maxlength=\"25\" /><br />";
      $boxstuff .= "".translate("Password")."<br />";
      $boxstuff .= "<input class=\"inputa\" style=\"width: 90%\" type=\"password\" name=\"pass\" size=\"12\" maxlength=\"20\" /><br />";
      $boxstuff .= "<input type=\"hidden\" name=\"op\" value=\"login\" />";
      $boxstuff .= "<input class=\"bouton_standard\" type=\"submit\" value=\"".translate("Submit")."\" /><hr class=\"ongl\" noshade=\"noshade\" /></p>";
      $boxstuff .= translate("Don't have an account yet? You can");
      $boxstuff .= " <a href=\"user.php\">".translate("create one")."</a>.";
      $boxstuff .= " ".translate("As registered")."";
      $boxstuff .= " ".translate("user you have some advantages like theme manager,")."";
      $boxstuff .= " ".translate("comments configuration and post comments with your name.")."";
      $boxstuff .= "</form>";
      global $block_title;
      if ($block_title=="")
         $title=translate("Login box");
      else
         $title=$block_title;
      themesidebox($title, $boxstuff);
   }
}
#autodoc userblock() : Bloc membre <br />=> syntaxe : function#userblock
function userblock() {
   global $NPDS_Prefix;
   global $user,$cookie;
   if (($user) AND ($cookie[8])) {
      $getblock = Q_select("select ublock from ".$NPDS_Prefix."users where uid='$cookie[0]'",86400);
      list(,$ublock) = each($getblock);
      global $block_title;
      if ($block_title=="")
         $title=translate("Menu for")." $cookie[1]";
      else
         $title=$block_title;
      themesidebox($title, $ublock['ublock']);
   }
}
#aautodo topdownload() : Bloc topdownload <br />=> syntaxe : function#topdownload
function topdownload() {
   global $block_title;
   if ($block_title=="")
      $title=translate("most downloaded");
   else
      $title=$block_title;
   $boxstuff = topdownload_data("short","dcounter");
   themesidebox($title, $boxstuff);
}
#autodoc lastdownload() : Bloc lastdownload <br />=> syntaxe : function#lastdownload
function lastdownload() {
   global $block_title;
   if ($block_title=="")
      $title=translate("last downloadable files");
   else
      $title=$block_title;
   $boxstuff = topdownload_data("short","ddate");
   themesidebox($title, $boxstuff);
}
#autodoc topdownload_data($form, $ordre) : Bloc topdownload et lastdownload / SOUS-Fonction
function topdownload_data($form, $ordre) {
   global $NPDS_Prefix;
   global $top, $long_chain;
   if (!$long_chain) {$long_chain=13;}
   settype($top,"integer");
   $result = sql_query("select did, dcounter, dfilename, dcategory, ddate, perms from ".$NPDS_Prefix."downloads order by '$ordre' DESC limit 0,$top");
   $lugar=1; $ibid="";
   while(list($did, $dcounter, $dfilename, $dcategory, $ddate, $dperm) = sql_fetch_row($result)) {
      $rowcolor = tablos();
      if ($dcounter>0) {
         $okfile=autorisation($dperm);
         if ($ordre=="dcounter") {
            $dd="( ".wrh($dcounter)." )";
         }
         if ($ordre=="ddate") {
            $dd=translate("dateinternal");
            $day=substr($ddate,8,2);
            $month=substr($ddate,5,2);
            $year=substr($ddate,0,4);
            $dd=str_replace("d",$day,$dd);
            $dd=str_replace("m",$month,$dd);
            $dd=str_replace("Y",$year,$dd);
            $dd="(".str_replace("H:i","",$dd).")";
         }
         $ori_dfilename=$dfilename;
         if (strlen($dfilename)>$long_chain) {
            $dfilename = (substr($dfilename, 0, $long_chain))." ...";
         }
         if ($form=="short") {
            if ($okfile) { $ibid.="".$lugar." <a href=\"download.php?op=geninfo&amp;did=$did\" title=\"".$ori_dfilename." ".$dd."\" >".$dfilename."</a><br />";}
         } else {
            if ($okfile) { $ibid.="<tr ".$rowcolor."><td>".$lugar.": <a href=\"download.php?op=geninfo&amp;did=$did\" class=\"noir\">".$dfilename."</a> (".translate("Category"). " : ".aff_langue(stripslashes($dcategory)).")</td><td align=\"right\">".wrh($dcounter)."<br /></td></tr>";}
         }
         if ($okfile)
            $lugar++;
      }
   }
   sql_free_result($result);
   return $ibid;
}
#autodoc oldNews($storynum) : Bloc Anciennes News <br />=> syntaxe
#autodoc : function#oldNews<br />params#$storynum,lecture (affiche le NB de lecture) - facultatif
function oldNews($storynum, $typ_aff="") {
   global $locale, $oldnum, $storyhome, $categories, $cat;
   global $user,$cookie;
   $boxstuff = "<ul>";
   if (isset($cookie[3])) {
      $storynum=$cookie[3];
   } else {
      $storynum=$storyhome;
   }
   if (($categories==1) and ($cat!="")) {
      if ($user) { $sel="WHERE catid='$cat'"; }
      else { $sel="WHERE catid='$cat' AND ihome=0"; }
   } else {
      if ($user) { $sel=""; }
      else { $sel="WHERE ihome=0"; }
   }
   $vari=0;
   $xtab=news_aff("old_news", $sel, $storynum, $oldnum);
   $story_limit=0; $time2=0; $a=0;
   while (($story_limit<$oldnum) and ($story_limit<sizeof($xtab))) {
      list($sid, $title, $time, $comments, $counter) = $xtab[$story_limit];
      $story_limit++;
      setlocale (LC_TIME, aff_langue($locale));
      preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime2);
      $datetime2 = strftime("".translate("datestring2")."", @mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
      if (cur_charset!="utf-8") {
         $datetime2 = ucfirst($datetime2);
      }

      if ($typ_aff=="lecture") $comments=$counter;

      if ($time2==$datetime2) {
         $boxstuff .= "<li><a href=\"article.php?sid=$sid\">".aff_langue($title)."</a> ($comments)</li>\n";
      } else {
         if ($a==0) {
            $boxstuff .= "<b>$datetime2</b><br /><li><a href=\"article.php?sid=$sid\">".aff_langue($title)."</a> ($comments)</li>\n";
            $time2 = $datetime2;
            $a = 1;
         } else {
            $boxstuff .= "<br /><b>$datetime2</b><br /><li><a href=\"article.php?sid=$sid\">".aff_langue($title)."</a> ($comments)</li>\n";
            $time2 = $datetime2;
         }
      }
      $vari++;
      if ($vari==$oldnum) {
         if (isset($cookie[3])) {
            $storynum = $cookie[3];
         } else {
            $storynum = $storyhome;
         }
         $min = $oldnum + $storynum;
         $boxstuff .= "<br /><p align=\"center\"><a href=\"search.php?min=$min&amp;type=stories&amp;category=$cat\"><b>".translate("Older Articles")."</b></a></p>\n";
      }
   }
   $boxstuff .="</ul>";
   if ($boxstuff=="<ul></ul>") $boxstuff="";
   global $block_title;
   if ($block_title=="")
      $boxTitle=translate("Past Articles");
   else
      $boxTitle=$block_title;
   themesidebox($boxTitle, $boxstuff);
}
#autodoc bigstory() : Bloc BigStory <br />=> syntaxe : function#bigstory
function bigstory() {
   global $cookie;
   $today = getdate();
   $day = $today['mday'];
   if ($day < 10)
      $day = "0$day";
   $month = $today['mon'];
   if ($month < 10)
      $month = "0$month";
   $year = $today['year'];
   $tdate = "$year-$month-$day";
   $xtab=news_aff("big_story","where (time LIKE '%$tdate%')",0,1);
   if (sizeof($xtab)) {
      list($fsid, $ftitle) = $xtab[0];
   } else {
      $fsid=""; $ftitle="";
   }
   if ((!$fsid) AND (!$ftitle)) {
      $content = translate("There isn't a Biggest Story for Today, yet.");
   } else {
      $content = translate("Today's most read Story is:")."<br /><br />";
      $content .= "<a href=\"article.php?sid=$fsid\">".aff_langue($ftitle)."</a>";
   }
   global $block_title;
   if ($block_title=="")
      $boxtitle=translate("Today's Big Story");
   else
      $boxtitle=$block_title;
   themesidebox($boxtitle, $content);
}
#autodoc category() : Bloc de gestion des cat&eacute;gories <br />=> syntaxe : function#category
function category() {
   global $NPDS_Prefix;
   global $cat, $language;
   $result = sql_query("select catid, title from ".$NPDS_Prefix."stories_cat order by title");
   $numrows = sql_num_rows($result);
   if ($numrows == 0) {
      return;
   } else {
      $boxstuff = "<ul>";
      while (list($catid, $title) = sql_fetch_row($result)) {
         $result2 = sql_query("select sid from ".$NPDS_Prefix."stories where catid='$catid' limit 0,1");
         $numrows = sql_num_rows($result2);
         if ($numrows > 0) {
            $res = sql_query("select time from ".$NPDS_Prefix."stories where catid='$catid' order by sid DESC limit 0,1");
            list($time) = sql_fetch_row($res);
            if ($cat == $catid) {
               $boxstuff .= "<li><b>".aff_langue($title)."</b></li>";
            } else {
               $boxstuff .= "<li><a href=\"index.php?op=newcategory&amp;catid=$catid\" title=\"".formatTimestamp($time)."\">".aff_langue($title)."</a></li>";
            }
         }
      }
      $boxstuff .= "</ul>";
      global $block_title;
      if ($block_title=="")
         $title=translate("Categories");
      else
         $title=$block_title;
      themesidebox($title, $boxstuff);
   }
}
#autodoc headlines() : Bloc HeadLines <br />=> syntaxe :
#autodoc : function#headlines<br />params#ID_du_canal
function headlines($hid="", $block=true) {
  global $NPDS_Prefix;
  global $Version_Num, $Version_Id, $system, $rss_host_verif, $long_chain;

  if (file_exists("proxy.conf.php")) {
     include("proxy.conf.php");
  }
  if ($hid=="") {
     $result = sql_query("select sitename, url, headlinesurl, hid from ".$NPDS_Prefix."headlines where status=1");
  } else {
     $result = sql_query("select sitename, url, headlinesurl, hid from ".$NPDS_Prefix."headlines where hid='$hid' and status=1");
  }
  while (list($sitename, $url, $headlinesurl, $hid) = sql_fetch_row($result)) {
    $boxtitle     = "$sitename";
    $cache_file   = "cache/$sitename.cache";
    $cache_time   = 3600;
    $items        = 0;
    $max_items    = 10;
    $rss_timeout  = 15;
    $rss_font     = "<span style=\"font-size: 10px;\">";

    if ( (!(file_exists($cache_file))) or (filemtime($cache_file)<(time()-$cache_time)) or (!(filesize($cache_file))) ) {
       $rss=parse_url($url);
       if ($rss_host_verif==true) {
          $verif = fsockopen($rss['host'], 80, $errno, $errstr, $rss_timeout);
          if ($verif) {
             fclose($verif);
             $verif=true;
          }
       } else {
          $verif=true;
       }

       if (!$verif) {
          $cache_file_sec=$cache_file.".security";
          if (file_exists($cache_file)) {
             $ibid=rename($cache_file, $cache_file_sec);
          }
          themesidebox($boxtitle, "Security Error");
          return;
       } else {
          if (isset($proxy_url[$hid])) {
             $fpread=fsockopen($proxy_url[$hid],$proxy_port[$hid],$errno,$errstr,$rss_timeout);
             fputs($fpread,"GET $headlinesurl/ HTTP/1.0\n\n");
          } else {
             $fpread = fopen($headlinesurl, 'r');
          }
          if (!$long_chain) {$long_chain=15;}
          if ($fpread) {
             $fpwrite = fopen($cache_file, 'w');
             if ($fpwrite) {
                fputs($fpwrite, "<ul>\n");
                while (!feof($fpread)) {
                   $buffer = ltrim(Chop(fgets($fpread, 512)));
                   if (($buffer == "<item>") && ($items < $max_items)) {
                      $title = ltrim(Chop(fgets($fpread, 256)));
                      $link = ltrim(Chop(fgets($fpread, 256)));
                      $title = str_replace( "<title>", "", $title );
                      $title = str_replace( "</title>", "", $title );
                      $link = str_replace( "<link>", "", $link );
                      $link = str_replace( "</link>", "", $link );

                      if (function_exists("mb_detect_encoding")) {
                         $encoding=mb_detect_encoding($title);
                      } else {
                         $encoding="UTF-8";
                      }
                      $title=$look_title=iconv($encoding,cur_charset."//TRANSLIT", $title);
                      if ($block) {
                         if (strlen($look_title)>$long_chain) {
                            $title=(substr($look_title, 0, $long_chain))." ...";
                         }
                      }

                      fputs($fpwrite, "<li><a href=\"$link\" alt=\"$look_title\" title=\"$look_title\" target=\"_blank\">$title</a></li>\n");
                      $items++;
                   }
                }
                fputs($fpwrite, "</ul>");
                fclose($fpwrite);
             }
             fclose($fpread);
          }
       }
    }
    if (file_exists($cache_file)) {
        ob_start();
        $ibid=readfile($cache_file);
        $boxstuff=$rss_font.ob_get_contents()."</span>";
        ob_end_clean();
    }
    $boxstuff .= "<br /><div align=\"right\"><a href=\"$url\" target=\"_blank\"><b>".translate("read more...")."</b></a></div>";
    if ($block) {
       themesidebox($boxtitle, $boxstuff);
       $boxstuff="";
    } else {
        return ($boxstuff);
    }
  }
}
#autodoc PollNewest() : Bloc Sondage <br />=> syntaxe :
#autodoc : function#pollnewest<br />params#ID_du_sondage OU vide (dernier sondage cr&eacute;&eacute;)
function PollNewest($id='') {
   global $NPDS_Prefix;
   // snipe : multi-poll evolution
   if ($id!=0) {
      settype($id, "integer");
      list($ibid,$pollClose)=pollSecur($id);
      if ($ibid) {pollMain($ibid,$pollClose);}
   } elseif ($result = sql_query("SELECT pollID FROM ".$NPDS_Prefix."poll_data ORDER BY pollID DESC limit 1")) {
      list($pollID)=sql_fetch_row($result);
      list($ibid,$pollClose)=pollSecur($pollID);
      if ($ibid) {pollMain($ibid,$pollClose);}
   }
}
#autodoc bloc_langue() : Bloc langue <br />=> syntaxe : function#bloc_langue
function bloc_langue() {
   global $block_title;
   if ($block_title=="")
      $title=translate("Select a language");
   else
      $title=$block_title;
   themesidebox($title,"<br />".aff_local_langue("" ,"index.php", "choice_user_language"));
}
#autodoc bloc_rubrique() : Bloc des Rubriques <br />=> syntaxe : function#bloc_rubrique
function bloc_rubrique() {
   global $NPDS_Prefix;
   global $language, $user;
   $result = sql_query("SELECT rubid, rubname FROM ".$NPDS_Prefix."rubriques WHERE enligne='1' AND rubname<>'divers' ORDER BY ordre");
   $boxstuff = '<ul>';
   while (list($rubid, $rubname) = sql_fetch_row($result)) {
      $title=aff_langue($rubname);
      $result2 = sql_query("SELECT secid, secname, userlevel FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid' ORDER BY ordre");
      $boxstuff.='<li><strong>'.$title.'</strong></li>';
      $ibid++;
      while (list($secid, $secname, $userlevel) = sql_fetch_row($result2)) {
         $query3 = "SELECT artid FROM ".$NPDS_Prefix."seccont WHERE secid='$secid'";
         $result3 = sql_query($query3);
         $nb_article = sql_num_rows($result3);
         if ($nb_article>0) {
            $boxstuff.='<ul>';
            $tmp_auto=explode(",",$userlevel);
            while (list(,$userlevel)=each($tmp_auto)) {
               $okprintLV1=autorisation($userlevel);
               if ($okprintLV1) break;
            }
            if ($okprintLV1) {
               $sec=aff_langue($secname);
               $boxstuff.= '<li><a href="sections.php?op=listarticles&amp;secid='.$secid.'">'.$sec.'</a></li>';
            }
           $boxstuff.='</ul>';
         }
      }
   }
   $boxstuff .="</ul>";
   global $block_title;
   if ($block_title=="")
      $title=translate("Sections");
   else
      $title=$block_title;
   themesidebox($title, $boxstuff);
}

#autodoc espace_groupe() : Bloc du WorkSpace <br />=> syntaxe :
#autodoc : function#bloc_espace_groupe<br />params#ID_du_groupe, Aff_img_groupe(0 ou 1) / Si le bloc n'a pas de titre, Le nom du groupe sera utilis&eacute;
function bloc_espace_groupe($gr, $i_gr) {
   global $NPDS_Prefix, $block_title;
   if ($block_title=="") {
      $rsql=sql_fetch_assoc(sql_query("SELECT groupe_name FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gr'"));
      $title=$rsql['groupe_name'];
   } else
      $title=$block_title;
   themesidebox($title, fab_espace_groupe($gr, "0", $i_gr));
}

function fab_espace_groupe($gr, $t_gr, $i_gr) {
   global $NPDS_Prefix, $chat_info;

   $rsql=sql_fetch_assoc(sql_query("SELECT groupe_id, groupe_name, groupe_description, groupe_forum, groupe_mns, groupe_chat, groupe_blocnote, groupe_pad FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$gr'"));

   $content='<script type="text/javascript">
   //<![CDATA[
   //==> chargement css
   if (!document.getElementById(\'bloc_ws_css\')) {
      var l_css = document.createElement(\'link\');
      l_css.href = "modules/groupe/bloc_ws.css";
      l_css.rel = "stylesheet";
      l_css.id = "bloc_ws_css";
      l_css.type = "text/css";
      document.getElementsByTagName("head")[0].appendChild(l_css);
   }
   //]]>
   </script>';
   $content.="
   <script type=\"text/javascript\">
   //<![CDATA[
   tog = function(lst,sho,hid){
      $(document).on('click', 'span.tog', function() {
         var buttonID = $(this).attr('id');
         lst_id = $('#'+lst);
         i_id=$('#i_'+lst);
         btn_show=$('#'+sho);
         btn_hide=$('#'+hid);
         if (buttonID == sho) {
            lst_id.fadeIn(1000);//show();
            btn_show.attr('id',hid)
            btn_show.attr('title','".translate("Hide list")."');
            i_id.attr('class','fa fa-minus-square-o');
         } else if (buttonID == hid) {
            lst_id.fadeOut(1000);//hide();
            btn_hide=$('#'+hid);
            btn_hide.attr('id',sho);
            btn_hide.attr('title','".translate("Show list")."');
            i_id.attr('class','fa fa-plus-square-o');
        }
       });
   };
   //]]>
   </script>";

   $content.="\n".'<div id="bloc_ws_'.$gr.'" class="di_bloc_ws">'."\n";
   if ($t_gr==1) 
      $content.= '<img src="images/admin/ws/groupe.gif" class="vam_bo_0" title="ID:'.$gr.'" alt="'.translate("Group").'" />  <span style="font-size: 120%; font-weight:bolder;">'.aff_langue($rsql['groupe_name']).'</span>'."\n";
   $content.='<p>'.aff_langue($rsql['groupe_description']).'</p>'."\n";
   if (file_exists('users_private/groupe/'.$gr.'/groupe.png') and ($i_gr==1)) 
      $content.='<img src="users_private/groupe/'.$gr.'/groupe.png" class="img-responsive img-fluid center-block" border="0" alt="'.translate("Group").'" />';
   $content.='<ul class="list-group ul_bloc_ws">'."\n";

   //=> liste des membres
   $li_mb='';
   $result = sql_query("SELECT uid, groupe FROM ".$NPDS_Prefix."users_status WHERE groupe REGEXP '[[:<:]]".$gr."[[:>:]]' ORDER BY uid ASC");
   $nb_mb=sql_num_rows ($result);
   $li_mb.='<li class=" list-group-item li_18"><span class="tog" id="show_lst_mb_ws_'.$gr.'" title="'.translate("Show list").'"><i id="i_lst_mb_ws_'.$gr.'" class="fa fa-plus-square-o" ></i></span>&nbsp;<i class="fa fa-users fa-lg text-muted" title="'.translate("Group members list.").'" data-toggle="tooltip"></i>&nbsp;<a href="memberslist.php?gr_from_ws='.$gr.'" >'.translate("Members").'</a><span class="label label-pill label-default pull-right">'.$nb_mb.'</span>';
   $tab=online_members();
  
   $li_mb.="\n".'<ul id="lst_mb_ws_'.$gr.'" class=" list-group ul_bloc_ws" style="display:none;">'."\n";
   while(list($uid, $groupe) = sql_fetch_row($result)) {
      list($uname, $user_avatar, $mns, $url)=sql_fetch_row(sql_query("select uname, user_avatar, mns, url from ".$NPDS_Prefix."users where uid='$uid'"));
      $conn= '<img src="images/admin/ws/disconnect.gif" class="vam_bo_0" title="'.$uname.' '.translate('is not connected !').'" alt="'.$uname.' '.translate('is not connected !').'" />';
      if (!$user_avatar) {
         $imgtmp="images/forum/avatar/blank.gif";
      } else if (stristr($user_avatar,"users_private")) {
         $imgtmp=$user_avatar;
      } else {
         if ($ibid=theme_image("forum/avatar/$user_avatar")) {$imgtmp=$ibid;} else {$imgtmp="images/forum/avatar/$user_avatar";}
         if (!file_exists($imgtmp)) {$imgtmp="images/forum/avatar/blank.gif";}
      }
      
      $timex=false;
      for ($i = 1; $i <= $tab[0]; $i++) {
         if ($tab[$i]['username']==$uname) {
            $timex=time()-$tab[$i]['time'];
            $ok_conn=true;
         }
      }
      if ($timex<60) {
         $conn= '<img src="images/admin/ws/connect.gif" class="vam_bo_0" title="'.$uname.' '.translate('is connected !').'" alt="'.$uname.' '.translate('is connected !').'" />';
      }
      
      $li_ic.='<img src="'.$imgtmp.'" style="vertical-align:middle;" height="24px" width="24px" alt="avatar" />&nbsp;';
      $li_mb.= '<li class="list-group-item li_mb">
      <div id="li_mb">'.$conn.'   <a href="user.php?op=userinfo&uname='.$uname.'" class="tooltip_ws"><em style="width:90px"><img src="'.$imgtmp.'" height="80px" width="80px" border="0" /></em><img src="'.$imgtmp.'" style="vertical-align:middle;" height="24px" width="24px" alt="avatar" title="'.$uname.'" data-toggle="tooltip" data-placement="right" />&nbsp;</a>
      </div>
      <span class="pull-right">
      <a href="powerpack.php?op=instant_message&amp;to_userid='.$uname.'" title="'.translate("Send internal Message").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-envelope-o"></i></a>'."\n";
      if ($url!='')
         $li_mb.='&nbsp;<a href="'.$url.'" target="_blank" title="'.translate("Visit this Website").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-external-link"></i></a>';
      if ($mns==1)
         $li_mb.='&nbsp;<a href="minisite.php?op='.$uname.'" target="_blank" title="'.translate("Visit the Mini Web Site !").'" data-toggle="tooltip" data-placement="right" ><i class="fa fa-desktop"></i></a>';
      $li_mb.='
      </span><span class="clearfix"></span></li>';
   }
   $li_mb.='<li style="clear:left;line-height:6px; background:none;">&nbsp;</li><li style="clear:left;line-height:24px;padding:6px; margin-top:0px; background:none; border-style: dotted; border-width: 1px; border-color: gray;">'.$li_ic.'</li> <li style="line-height:12px; background:none;">&nbsp;</li>';
   $li_mb.='</ul>'."\n".'</li>';
   $li_mb.="\n
   <script type=\"text/javascript\">
   //<![CDATA[
   tog('lst_mb_ws_".$gr."','show_lst_mb_ws_".$gr."','hide_lst_mb_ws_".$gr."');
   //]]>
   </script>\n";
   $content.=$li_mb;
   //<== liste des membres

   //=> Forum
   if ($rsql['groupe_forum'] == 1) {
      $res_forum=sql_query("select forum_id, forum_name from ".$NPDS_Prefix."forums where forum_pass regexp '$gr'");
      $nb_foru=sql_num_rows ($res_forum);
      if ($nb_foru >= 1) {
         $lst_for_tog='<span class="tog" id="show_lst_for_'.$gr.'" title="'.translate("Show list").'"><i id="i_lst_for_gr_'.$gr.'" class="fa fa-plus-square-o" ></i></span>';
         $lst_for.='<ul id="lst_for_gr_'.$gr.'" class="ul_bloc_ws" style ="list-style-type:none; display:none; ">';
         $nb_for_gr='  <span class="label label-pill label-default pull-right">'.$nb_foru.'</span>';
         while(list($id_fo,$fo_name) = sql_fetch_row($res_forum)) {
            $lst_for.='<li style="line-height:18px;margin-top:0; background:none; padding: 0px 1px 0px 14px;"><a href="viewforum.php?forum='.$id_fo.'">'.$fo_name.'</a>';
            $lst_for.= '</li>';
         }
         $lst_for.='</ul>';
         $lst_for.="\n<script type=\"text/javascript\">
         //<![CDATA[
         tog('lst_for_gr_".$gr."','show_lst_for_".$gr."','hide_lst_for_".$gr."');
         //]]>
         </script>\n";
      }
      $content.='<li class="list-group-item li_18">'.$lst_for_tog.'&nbsp;<i class="fa fa-list-alt fa-lg text-muted" title="'.translate("Group").'('.$gr.'): '.translate("forum").'."></i>&nbsp;<a href="forum.php">'.translate("Forum").'</a>'.$nb_for_gr.$lst_for.'</li>'."\n";
   }
   //<= Forum



   //=> wspad
   if ($rsql['groupe_pad'] == 1) {
      settype($lst_doc,'string');
      settype($nb_doc_gr,'string');
      settype($lst_doc_tog,'string');

      include("modules/wspad/config.php");

      $docs_gr=sql_query("SELECT page, editedby, modtime, ranq FROM ".$NPDS_Prefix."wspad WHERE (ws_id) IN (SELECT MAX(ws_id) FROM ".$NPDS_Prefix."wspad WHERE member='$gr' GROUP BY page) ORDER BY page ASC");
      $nb_doc=sql_num_rows ($docs_gr);
      if ($nb_doc >= 1) {
         $lst_doc_tog ='<span class="tog" id="show_lst_doc_'.$gr.'" title="'.translate("Show list").'"><i id="i_lst_doc_gr_'.$gr.'" class="fa fa-plus-square-o" ></i></span>';
         $lst_doc.='<ul id="lst_doc_gr_'.$gr.'" class="ul_bloc_ws m-t-md" style ="list-style-type:none; display:none; ">';
         $nb_doc_gr='  <span class="label label-pill label-default pull-right">'.$nb_doc.'</span>';
         while (list($p,$e,$m,$r)=sql_fetch_row($docs_gr)) {
            $surlignage=$couleur[hexfromchr($e)];
            $lst_doc.='<li style="line-height:14px;margin-top:0; background:none; padding: 0px 2px 0px 0px;"><div id="last_editor" title="'.translate("Last editor").' : '.$e.' '.date (translate("dateinternal"),$m ).'" style="float:left; width:12px; height:12px; margin-top:4px; background-color:'.$surlignage.'"></div><img src="images/admin/ws/document_edit.gif" class="vam_bo_0" alt="'.translate("Multi-writers document").'." title="'.translate("Multi-writers document").'." />  <a href="modules.php?ModPath=wspad&ModStart=wspad&op=relo&page='.$p.'&member='.$gr.'&ranq='.$r.'">'.$p.'</a>';
            $lst_doc.= '</li>';
         }
         $lst_doc.='</ul>';
         $lst_doc.="<script type=\"text/javascript\">
         //<![CDATA[
         tog('lst_doc_gr_".$gr."','show_lst_doc_".$gr."','hide_lst_doc".$gr."');
         //]]>
         </script>\n";
      }
      $content.='<li class="list-group-item li_18">'. $lst_doc_tog.'&nbsp;<i class="fa fa-edit fa-lg text-muted" title="'.translate("Co-writing").'" data-toggle="tooltip" data-placement="right"></i>&nbsp;<a href="modules.php?ModPath=wspad&ModStart=wspad&member='.$gr.'" >'.translate("Co-writing").'</a>'.$nb_doc_gr.$lst_doc.'</li>'."\n";
   }
   //<= wspad
   
   
   //=> bloc-notes
   if ($rsql['groupe_blocnote'] == 1) {
      settype($lst_blocnote_tog,'string');
      settype($lst_blocnote,'string');
      include_once("modules/bloc-notes/bloc-notes.php");
      $lst_blocnote_tog ='<span class="tog" id="show_lst_blocnote" title="'.translate("Show list").'"><i id="i_lst_blocnote" class="fa fa-plus-square-o" ></i></span>&nbsp;<i class="fa fa-sticky-note-o fa-lg text-muted"></i>&nbsp; Bloc note';
      $lst_blocnote ='<div id="lst_blocnote" class="m-t-md" style =" display:none; ">';
      $lst_blocnote .= blocnotes("shared", "WS-BN".$gr,"100%","7","",false);
      $lst_blocnote .= '</div>';
      $lst_blocnote.='<script type="text/javascript">
      //<![CDATA[
      tog("lst_blocnote","show_lst_blocnote","hide_lst_blocnote");
      //]]>
      </script>';
      $content.='<li class="list-group-item li_18">'.$lst_blocnote_tog.$lst_blocnote.'</li>';
   }
   //=> bloc-notes
   
$content.='<li class="list-group-item li_18 text-xs-center">';
   //=> Filemanager
   if (file_exists('modules/f-manager/users/groupe_'.$gr.'.conf.php')) {
      $content.='&nbsp;<a href="modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=groupe_'.$gr.'" title="'.translate("File manager").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-folder fa-lg"></i></a>'."\n";
   }
   //<= Filemanager
   //=> Minisite
   if ($rsql['groupe_mns'] == 1) {
      $content.='&nbsp;<a href="minisite.php?op=groupe/'.$gr.'" target="_blank" title= "'.translate("Mini-Web site").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-desktop fa-lg"></i></a>';
   }
   //<= Minisite
   //=> Chat
   if ($rsql['groupe_chat'] == 1) {
      $PopUp = JavaPopUp("chat.php?id=$gr&amp;auto=".encrypt(serialize ($gr)),"chat".$gr,380,480);
      if ($chat_info)
         $chat_img='images/admin/ws/comment_reply.gif';
      else
         $chat_img='images/admin/ws/comment_user.gif';
      $content.='&nbsp;<a href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.translate("Open a chat for the group.").'" data-toggle="tooltip" data-placement="right" ><i class="fa fa-comments fa-lg"></i></a>';
   }
   //<= Chat
   //=> admin
   if (autorisation(-127)) {
      $content.='&nbsp;<a href="admin.php?op=groupes" title="'.translate("Groups setting.").'" data-toggle="tooltip"><i class="fa fa-cogs fa-lg"></i></a>';
   }
   //<= admin
   
   $content.="\n".'</li></ul>'."\n";
   $content.='</div>'."\n";

   return ($content);
}

#autodoc:
#autodoc <font color=red>Rappels</font> : Si votre th&egrave;me est adapt&eacute;, chaque bloc peut contenir :<br />- class-title#nom de la classe de la CSS pour le titre du bloc<br />- class-content#nom de la classe de la CSS pour le corp du bloc<br />- uri#uris s&eacute;par&eacute;e par un espace
#autodoc:</Mainfile.php>
#autodoc:
#autodoc <font color=green>NPDS 5.0</font>:
#autodoc tablos() : Permet d'alterner entre les CLASS (CSS) LIGNA et LIGNB
function tablos() {
   static $colorvalue;
   if ($colorvalue == "class=\"ligna\"") {
      $colorvalue="class=\"lignb\"";
   } else {
      $colorvalue="class=\"ligna\"";
   }
   return ($colorvalue);
}
#autodoc theme_image($theme_img) : Retourne le chemin complet si l'image est trouv&eacute;e dans le répertoire image du th&eacute;me sinon false
function theme_image($theme_img) {
    global $theme;
    if (@file_exists("themes/$theme/images/$theme_img")) {
       return ("themes/$theme/images/$theme_img");
    } else {
       return (false);
    }
}
#autodoc import_css_javascript($tmp_theme, $language, $site_font, $css_pages_ref, $css) : recherche et affiche la CSS (site, langue courante ou par d&eacute;efaut) / Charge la CSS complementaire / le HTML ne contient que de simple quote pour être compatible avec javascript
function import_css_javascript($tmp_theme, $language, $site_font, $css_pages_ref="", $css="") {
   // CSS standard 
   $tmp="";
   if (file_exists("themes/$tmp_theme/style/$language-style.css")) {
      $tmp.="<link href='themes/$tmp_theme/style/$language-style.css' title='default' rel='stylesheet' type='text/css' media='all' />\n";
      if (file_exists("themes/$tmp_theme/style/$language-style-AA.css"))
         $tmp.="<link href='themes/$tmp_theme/style/$language-style-AA.css' title='alternate stylesheet' rel='alternate stylesheet' type='text/css' media='all' />\n";
      if (file_exists("themes/$tmp_theme/style/$language-print.css"))
         $tmp.="<link href='themes/$tmp_theme/style/$language-print.css' rel='stylesheet' type='text/css' media='print' />\n";
   } else if (file_exists("themes/$tmp_theme/style/style.css")) {
      $tmp.="<link href='themes/$tmp_theme/style/style.css' title='default' rel='stylesheet' type='text/css' media='all' />\n";
      if (file_exists("themes/$tmp_theme/style/style-AA.css"))
         $tmp.="<link href='themes/$tmp_theme/style/style-AA.css' title='alternate stylesheet' rel='alternate stylesheet' type='text/css' media='all' />\n";
      if (file_exists("themes/$tmp_theme/style/print.css"))
         $tmp.="<link href='themes/$tmp_theme/style/print.css' rel='stylesheet' type='text/css' media='print' />\n";
   } else {
      $tmp.="<link href='themes/default/style/style.css' title='default' rel='stylesheet' type='text/css' media='all' />\n";
   }
   // Chargeur CSS specifique
   if ($css!="") {
      settype($css, 'array');
      foreach ($css as $k=>$tab_css) {
         $admtmp="";
         $op=substr($tab_css,-1);
         if ($op=="+" or $op=="-")
            $tab_css=substr($tab_css,0,-1);
         if (stristr($tab_css, "http://")) {
            $admtmp="<link href='$tab_css' rel='stylesheet' type='text/css' media='all' />\n";
         } else {
            if (file_exists("themes/$tmp_theme/style/$tab_css") and ($tab_css!="")) {
               $admtmp="<link href='themes/$tmp_theme/style/$tab_css' rel='stylesheet' type='text/css' media='all' />\n";
            } elseif (file_exists("$tab_css") and ($tab_css!="")) {
               $admtmp="<link href='$tab_css' rel='stylesheet' type='text/css' media='all' />\n";
            }
         }
         if ($op=="-")
            $tmp =$admtmp;
         else
            $tmp.=$admtmp;
      }
   } else {
      if ($css_pages_ref) {
         include ("themes/pages.php");
         $op=substr($PAGES[$css_pages_ref]['css'],-1);
         $css=substr($PAGES[$css_pages_ref]['css'],0,-1);
         if (($css!="") and (file_exists("themes/$tmp_theme/style/$css"))) {
            if ($op=="-")
               $tmp ="<link href='themes/$tmp_theme/style/$css' title='' rel='stylesheet' type='text/css' media='all' />\n";
            else
               $tmp.="<link href='themes/$tmp_theme/style/$css' title='' rel='stylesheet' type='text/css' media='all' />\n";
         }
      }
   }
   return($tmp);
}
#autodoc import_css($tmp_theme, $language, $site_font, $css_pages_ref, $css) : Fonctionnement identique à import_css_javascript sauf que le code HTML en retour ne contient que de double quote
function import_css ($tmp_theme, $language, $site_font, $css_pages_ref, $css) {
   return (str_replace("'","\"",import_css_javascript($tmp_theme, $language, $site_font, $css_pages_ref, $css)));
}

#autodoc auto_complete ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache) : fabrique un pseudo array js à partir de la requete sql et implente un auto complete pour l'input (dependence : jquery-2.1.3.min.js ,jquery-ui.js) $nom_array_js=> nom du tableau javascript; $nom_champ=>nom de champ bd; $nom_tabl=>nom de table bd,$id_inpu=> id de l'input,$temps_cache=>temps de cache de la requete
function auto_complete ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache) {
   global $NPDS_Prefix;

   $list_json='';
   $list_json.='var '.$nom_array_js.' = [';
   $res = Q_select("select ".$nom_champ." from ".$NPDS_Prefix.$nom_tabl,$temps_cache);
   while (list(,$ar_data)=each($res)) {
      foreach ($ar_data as $val_champ) {
         $list_json.='"'.$val_champ.'",';
      }
   }
   $list_json= rtrim($list_json,',');
   $list_json.='];';
   $scri_js ='';
   $scri_js.="
   <script type=\"text/javascript\">
   //<![CDATA[
   ".$list_json."\n
   $( '#".$id_inpu."' ).autocomplete({
      source: ".$nom_array_js."
    });
   //]]>
   </script>\n";
   return ($scri_js);
}

#autodoc auto_complete_multi ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req) : fabrique un pseudo array json à partir de la requete sql et implente un auto complete pour le champ input (dependence : jquery-2.1.3.min.js ,jquery-ui.js)
function auto_complete_multi ($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req) {
   global $NPDS_Prefix;

   $list_json='';
   $list_json.= $nom_array_js.' = [';
   $res = sql_query("select ".$nom_champ." from ".$NPDS_Prefix.$nom_tabl." ".$req);
   while (list($nom_champ) = sql_fetch_row($res)) {
      $list_json.='\''.$nom_champ.'\',';
   }
   $list_json= rtrim($list_json,',');
   $list_json.='];';
   $scri_js ='';
   $scri_js.='
   <script type="text/javascript">
   //<![CDATA[
   var '.$nom_array_js.';
   $(function() {
    '.$list_json.'
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
    $( "#'.$id_inpu.'" )
      // dont navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter(
            '.$nom_array_js.', extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
   });
   //]]>
   </script>'."\n";
   return ($scri_js);
}
#autodoc language_iso($l,$s,$c) : renvoi le code language iso 639-1 et code pays ISO 3166-2  $l=> 0 ou 1(requis), $s, $c=> 0 ou 1 (requis)
function language_iso($l,$s,$c) {
    global $language;
    $iso_lang='';$iso_country='';$ietf='';
    switch ($language) {
        case "french": $iso_lang ='fr';$iso_country='FR'; break;
        case "english":$iso_lang ='en';$iso_country='US'; break;
        case "spanish":$iso_lang ='es';$iso_country='ES'; break;
        case "german":$iso_lang ='de';$iso_country='DE'; break;
        case "chinese":$iso_lang ='zh';$iso_country='CN'; break;
        default:
        break;
    }
    if ($c!==1) $ietf= $iso_lang;
    if (($l==1) and ($c==1)) $ietf=$iso_lang.$s.$iso_country;
    if (($l!==1) and ($c==1)) $ietf=$iso_country;
    if (($l!==1) and ($c!==1)) $ietf='';
    if (($l==1) and ($c!==1)) $ietf=$iso_lang;
    return ($ietf);
}

#autodoc adminfoot($fv,$fv_parametres,$arg1,$foo) : fin d'affichage avec form validateur ou pas, ses parametres, fermeture div admin et inclusion footer.php  $fv=> fv : inclusion du validateur de form , $fv_parametres=> parametres particuliers pour differents input (objet js ex :   xxx: {},...), $arg1=>inutilisŽ,  $foo =='' ==> </div> et inclusion footer.php
function adminfoot($fv,$fv_parametres,$arg1,$foo) {
if ($fv=='fv') {
echo '
<script type="text/javascript">
//<![CDATA[
var diff;
$(document).ready(function() {
   $("form")
   .attr("autocomplete", "off")
   
   .on("init.field.fv", function(e, data) {
            var $parent = data.element.parents(".form-group"),
                $icon   = $parent.find(\'.form-control-feedback[data-fv-icon-for="\' + data.field + \'"]\');
            $icon.on("click.clearing", function() {
                if ($icon.hasClass("fa fa-ban fa-lg")) {
                    data.fv.resetField(data.element);
                }
            })   
            })   
            
   .formValidation({
      locale: "'.language_iso(1,"_",1).'",
      framework: "bootstrap",
      icon: {
         required: "glyphicon glyphicon-asterisk",

         valid: "fa fa-check fa-lg",
         invalid: "fa fa-ban fa-lg",
         validating: "glyphicon glyphicon-refresh"
      },
      fields: {
         alpha: {
         },';
echo '
         '.$fv_parametres;
echo '
         dzeta: {
         }
      }
   })
   
   .on("success.validator.fv", function(e, data) {
   // The password passes the callback validator
   // voir si on a plus de champs mot de passe : changer par un array de champs ...
   if ((data.field === "add_pwd" || data.field === "chng_pwd") && data.validator === "callback") {
      // Get the score
      var score = data.result.score,
          $bar = $("#passwordMeter").find(".progress-bar");
      switch (true) {
        case (score === null):
            $bar.html("").css("width", "0%").removeClass().addClass("progress-bar");
            break;
        case (score <= 0):
            $bar.html("Tr&#xE8;s faible").css("width", "25%").removeClass().addClass("progress progress-danger");
            break;
        case (score > 0 && score <= 2):
            $bar.html("Faible").css("width", "50%").removeClass().addClass("progress progress-warning");
            break;
        case (score > 2 && score <= 4):
            $bar.html("Moyen").css("width", "75%").removeClass().addClass("progress progress-info");
            break;
        case (score > 4):
            $bar.html("Fort").css("width", "100%").removeClass().addClass("progress progress-success");
            break;
        default:
            break;
      }
      }
   });
   
})

//]]>
</script>'."\n";
}
if ($foo=='') {
echo '
</div>';
include ('footer.php');
}
}
?>