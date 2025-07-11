<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include ("functions.php");
settype($gr_name,'string');
settype($new_pages,'string');
define('CITRON','tarteaucitron');

function MNSremoveHack($Xstring) {
   global $op, $userdata, $tiny_mce, $tiny_mce_theme, $tiny_mce_relurl;

   static $blog_editor;
   if ($tiny_mce) {
      if (!$blog_editor) {
         $tiny_mce_theme='full';
         $blog_editor=aff_editeur('tiny_mce','begin').aff_editeur('story','false').aff_editeur('tiny_mce','end');
      }
   }

   $npds_forbidden_words=array(
     // NCRs 2 premières séquences = NCR (dec|hexa) correspondant aux caractères latin de la table ascii (code ascii entre 33 et 126)
     //      2 dernières séquences = NCR (dec|hexa) correspondant aux caractères latin du bloc unicode Halfwidth and Fullwidth Forms.
     //        Leur signification est identique à celle des caractères latin de la table ascii dont le code ascii est entre 33 et 126.
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
     "'.htaccess'i"=>"",
     "'!blog_editeur!'i"=>$blog_editor,
     "'!l_blog_ajouterOK!'i"=>'<a class="list-group-item list-group-item-action" href="minisite.php?op='.$op.'&amp;action=A"><i class="fas fa-pencil-alt fa-lg me-2"></i> '.translate("Ajouter un article").'</a>',
     "'\<\?php'i"=>"&lt;?php",
     "'\<\?'i"=>"&lt;?",
     "'\?\>'i"=>"?&gt;",
     "'\<\%'i"=>"&lt;%",
     "'\%\>'i"=>"%&gt;");
     $Xstring=preg_replace(array_keys($npds_forbidden_words),array_values($npds_forbidden_words), $Xstring);
   return($Xstring);
}

function convert_ressources ($Xcontent) {
   global $op;
   for ($i=0;$i<strlen($Xcontent);$i++) {
      if (strtoupper(substr($Xcontent,$i,4))=="src=") {
         if ( (strtoupper(substr($Xcontent,$i+4,3))!="HTT") and (strtoupper(substr($Xcontent,$i+4,4))!="\"HTT") )  {
            $Xcontent=substr_replace($Xcontent,"src=getfile.php?att_id=$op&amp;apli=minisite&amp;att_type=&amp;att_size=&amp;att_name=",$i,4);
         }
         $i=$i+4;
      }
   }
   return (aff_langue($Xcontent));
}

   // NPDS copyright ... don't remove !
   $copyright= '<span class="blog_sname">'.$sitename.'</span>&nbsp;<span class="blog_npds">NPDS&nbsp;HUB-BLOG&nbsp;<a href="http://www.npds.org">NPDS</a></span>';

   // Troll Control for security
   $affich=false;
   if (($op!='') and ($op)) {
      if (preg_match('#^[a-z0-9_\.-]#i',$op) and !stristr($op,".*://") and !stristr($op,"..") and !stristr($op,"../") and !stristr($op, "script") and !stristr($op, "cookie") and !stristr($op, "iframe") and  !stristr($op, "applet") and !stristr($op, "object") and !stristr($op, "meta"))  {
         global $user, $super_admintest;
         $adminblog = ($super_admintest) ? true : false;
         $dir="users_private/$op/mns/";

         if (dirname($op)!='groupe') {
            // single user
            $userdata=get_userdata($op);
            if (array_key_exists('mns', $userdata) and $userdata['mns']==true) {
               $affich=true;
               if (stristr($userdata['user_avatar'],"users_private"))
                  $direktori='';
               else {
                  global $theme;
                  $direktori='images/forum/avatar/';
                  if (function_exists("theme_image")) {
                     if (theme_image("forum/avatar/blank.gif"))
                        $direktori="themes/$theme/images/forum/avatar/";
                  }
               }
               $avatar_mns=$direktori.$userdata['user_avatar'];
            }
            $userX = base64_decode($user);
            $userdataX = explode(':', $userX);
            if(array_key_exists(1, $userdataX)) {
               if ($userdataX[1]==$op)
                  $adminblog=true;
            }
         } else {
            // groupe
            if (is_dir($dir)) {
               $affich=true;
               $avatar_mns='users_private/'.$op.'/groupe.png';
               function adm_translate(){};
               $gX=liste_group();
               foreach($gX as $g_id => $g_name) {
                  if ($g_id==basename($op)) $gr_name= $g_name;
               }
            }
            $tabgp=valid_group($user);
            if (is_array($tabgp)) {
               foreach($tabgp as $auto) {
                  if ($auto==basename($op))
                     $adminblog=true;
               }
            }
         }
      }
   }
   if ($affich) {
      $fic=$dir.'index.html';
      if (file_exists($fic)) {
         $Titlesitename="Minisite - $op";
         $nuke_url=$nuke_url.'/minisite.php?op='.$op;
         include("meta/meta.php");
         echo '
         <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
         <script type="text/javascript" src="lib/js/jquery.min.js"></script>
         <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>';
         echo '<style type="text/css">';
            readfile($dir."style.css");
         echo '</style>';
         if(defined('CITRON')){
            echo '
            <script type="text/javascript"> var tarteaucitronForceLanguage = "'.language_iso(1,'','').'"; </script>
            <script type="text/javascript" src="lib/tarteaucitron/tarteaucitron.min.js"></script>
            <script type="text/javascript" src="lib/js/npds_tarteaucitron.js"></script>';
         }
         $Xcontent= '
         </head>
         <body>';

         $fp=fopen($fic,"r");
         if (filesize($fic)>0)
            $Xcontent.=fread($fp,filesize($fic));
         fclose($fp);

         //compteur
         $compteur=$dir."compteur.txt";
         if (!file_exists($compteur)) {
            $fp=fopen($compteur,"w");
            fwrite($fp,"1");
            fclose($fp);
         } else {
            $cpt=file($compteur);
            $cpt=$cpt[0]+1;
            $fp=fopen($compteur,"w");
            fwrite($fp,$cpt);
            fclose($fp);
         }

         // Analyse et convertion des liens et images, blog, header, footer ...

         $perpage = strstr($Xcontent,'!blog_page!') ?
            substr($Xcontent,strpos($Xcontent,"!blog_page!",0)+11,2) : 4 ;
         if (strstr($Xcontent,'!blog!')) {
            include ("modules/blog/readnews.php");
            settype($startpage,'integer');
            settype($perpage,'integer');
            settype($action,'string');
            $content=readnews($dir, $op, $perpage, $startpage, $action, $adminblog);
            if (strstr($content,'!l_new_pages!')) {
               $new_pages=substr($content,strpos($content,"!l_new_pages!")+13);
               $content=substr($content,0,strpos($content,"!l_new_pages!"));
            }
         }
         $Hcontent='';
         if (strstr($Xcontent,'!l_header!')) {
            $l_fic=$dir.'header.html';
            if (file_exists($l_fic)) {
               $fp=fopen($l_fic,'r');
               if (filesize($l_fic)>0)
                  $Hcontent=convert_ressources(fread($fp,filesize($l_fic)));
               fclose($fp);
            }
         }
         $Fcontent='';
         if (strstr($Xcontent,'!l_footer!')) {
            $l_fic=$dir.'footer.html';
            if (file_exists($l_fic)) {
               $fp=fopen($l_fic,'r');
               if (filesize($l_fic)>0)
                  $Fcontent=convert_ressources(fread($fp,filesize($l_fic)));
               fclose($fp);
            }
         }
         $blog_ajouter = (($adminblog) and (strstr($Xcontent,'!l_blog_ajouter!'))) ? '!l_blog_ajouterOK!' : '' ;
         $Xcontent=convert_ressources($Xcontent);
         // Meta-lang et removehack local
         $MNS_METALANG_words=array(
         "'!l_header!'i"=>"$Hcontent",
         "'!l_footer!'i"=>"$Fcontent",
         "'!blog_page!$perpage'i"=>'',
         "'!l_compteur!'i"=>$cpt,
         "'!l_new_pages!'i"=>$new_pages,
         "'!l_blog_ajouter!'i"=>$blog_ajouter,
         "'!blog!'i"=> $content,
         "'!copyright!'i"=> $copyright,
         "'!avatar!'i"=> $avatar_mns,
         "'!id_mns!'i"=> $op,
         "'!gr_name!'i"=> aff_langue($gr_name)
         );
         $Xcontent=preg_replace(array_keys($MNS_METALANG_words),array_values($MNS_METALANG_words), $Xcontent);
         $Xcontent=meta_lang(MNSremoveHack($Xcontent));
         //applique aff_video que sur la partie affichage
         $rupt=strpos($Xcontent, '#v_yt#');
         echo substr($Xcontent, 0, $rupt);
         echo aff_video_yt(substr($Xcontent,$rupt+6));
         if($adminblog)
            echo '
               <script type="text/javascript">
                  //<![CDATA[
                     $(".modal-body").load("modules/blog/matrice/readme.'.$language.'.txt"
                     , function(dataaide, textStatus, jqxhr) {
                        $("#aide_mns").html(dataaide.replace(/(\r\n|\n\r|\r|\n)/g, "<br />"));
                     });
                  //]]>
               </script>';
         echo (defined('CITRON')) ? '
               <script type="text/javascript" src="lib/js/npds_tarteaucitron_service.js"></script>' : '' ;
         echo '
               <script type="text/javascript" src="lib/js/npds_adapt.js"></script>
            </body>
         </html>';
      }
   } 
   else 
      include("admin/die.php");
?>