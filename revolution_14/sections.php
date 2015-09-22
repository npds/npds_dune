<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2012 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

function autorisation_section($userlevel) {
   $okprint=false;
   $tmp_auto=explode(",",$userlevel);
   while (list(,$userlevel)=each($tmp_auto)) {
      $okprint=autorisation($userlevel);
      if ($okprint) break;
   }
   return ($okprint);
}

function listsections($rubric) {
   global $sitename, $admin;
   global $NPDS_Prefix;

   include ('header.php');

   if (file_exists("sections.config.php"))
      include ("sections.config.php");
/*   if ($togglesection)
      include_once ("lib/togglediv.class.php");*/

   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      
      settype($rubric,"integer");
      if ($rubric)
         $sqladd="and rubid='".$rubric."'";
      else
         $sqladd="";
      if ($admin) {
         $result=sql_query("select rubid, rubname, intro from ".$NPDS_Prefix."rubriques where rubname<>'Divers' and rubname<>'Presse-papiers' $sqladd order by ordre");
      } else {
         $result=sql_query("select rubid, rubname, intro from ".$NPDS_Prefix."rubriques where enligne='1' and rubname<>'Divers' and rubname<>'Presse-papiers' $sqladd order by ordre");
      }
      $aff='<h2>'.translate("Sections").'</h2>';
      if (sql_num_rows($result) > 0) {
         while (list($rubid, $rubname, $intro) = sql_fetch_row($result)) {
            $result2 = sql_query("SELECT secid, secname, image, userlevel, intro FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid' order by ordre");
            $nb_section=sql_num_rows($result2);
            $aff.="<h3><a href=\"sections.php?rubric=$rubid\">".aff_langue($rubname)."</a></h3>";
            if ($intro!="") {
               $aff.=aff_langue($intro);
            }
            while (list($secid, $secname, $image, $userlevel, $intro) = sql_fetch_row($result2)) {
               $okprintLV1=autorisation_section($userlevel);
               $aff1=""; $aff2="";
               if ($okprintLV1) {

                  $aff.='<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne">
									<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#'.$secid.'" aria-expanded="true" aria-controls="'.$secid.'">';
                  if ($image!="") {
                     if (file_exists("images/sections/$image")) {$imgtmp="images/sections/$image";} else {$imgtmp=$image;}
                     $suffix = strtoLower(substr(strrchr(basename($image), '.'), 1 ));
                        $aff.="<img src=\"$imgtmp\" border=\"0\" alt=\"".aff_langue($secname)."\" /><br />";
                  }
                  $aff1='<strong>'.aff_langue($secname).'</strong>#NEW#</a>&nbsp;<i class="fa fa-arrow-down"></i>';
                  if ($intro!="") {
                     $aff1.='<small>'.aff_langue($intro).'</small>';
                  }
                     $aff1.='
							</h4>
							</div>';
                     $aff2='<div id="'.$secid.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">';
                  $result3 = sql_query("select artid, title, counter, userlevel, timestamp from ".$NPDS_Prefix."seccont where secid='$secid' order by ordre");

                  $noartid=false;
                  while (list($artid, $title, $counter, $userlevel, $timestamp) = sql_fetch_row($result3)) {
                     $okprintLV2=autorisation_section($userlevel);
                     if ($okprintLV2) {
                        $noartid=true;
                        $nouveau="colspan=\"2\"";
                        if ((time()-$timestamp)<(86400*7)) {
                           $nouveau="";
                        }
                        $aff2.='<p><a href="sections.php?op=viewarticle&amp;artid='.$artid.'">'.aff_langue($title).'</a>&nbsp;<small>'.translate("read:").' '.$counter.' '.translate("times").'</small>';
                        if ($nouveau=="") {
                           $aff2.='<i class="fa fa-star"></i></i>';

                              $aff1=str_replace("#NEW#","",$aff1);
                        } else {
                           $aff1=str_replace("#NEW#","",$aff1);
                        }
                        $aff2.='</p>';
                     }
                  }
                  if (!$noartid) $aff1=str_replace("#NEW#","",$aff1);
                  $aff2.="\n";
                  $aff2.="</div></div></div></div>\n";
               }
               $aff.=$aff1.$aff2;
            $aff.='';		   
            }
            $aff.='';
         }
      }
      echo $aff;
      
      if ($rubric) {
         echo "<a class=\"btn btn-default\" href=\"sections.php\">".translate("Return to Sections Index")."</a>";
      }
      sql_free_result($result);
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include ('footer.php');
}






function listarticles($secid) {
   global $user, $prev;
   global $NPDS_Prefix;

   if (file_exists("sections.config.php"))
      include ("sections.config.php");

   $result = sql_query("select secname, rubid, image, intro, userlevel from ".$NPDS_Prefix."sections where secid='$secid'");
   list($secname, $rubid, $image, $intro, $userlevel) = sql_fetch_row($result);
   list($rubname) = sql_fetch_row(sql_query("select rubname from ".$NPDS_Prefix."rubriques where rubid='$rubid'"));
   if ($sections_chemin) {
      $title =  aff_langue($rubname)." - ".aff_langue($secname);
   } else {
      $title =  aff_langue($secname);
   }
   include ('header.php');

   global $SuperCache;
   if ($SuperCache) {
      $cache_obj = new cacheManager();
      $cache_obj->startCachingPage();
   } else {
      $cache_obj = new SuperCacheEmpty();
   }
   if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
      $okprint1=autorisation_section($userlevel);
      if ($okprint1) {
         if ($prev==1) {echo "<input class=\"btn btn-primary\" type=\"button\" value=\"".translate("Back to console")."\" onclick=\"javascript:history.back()\" /><br /><br />";}
         if (function_exists("themesection_title")) {
            themesection_title($title);
         } else {          
            echo '<h3>'.$title.'</h3>';          
         }        
         if ($intro!="") {
            echo aff_langue($intro);
         }
         if ($image!="") {
            if (file_exists("images/sections/$image")) {$imgtmp="images/sections/$image";} else {$imgtmp=$image;}
            $suffix = strtoLower(substr(strrchr(basename($image), '.'), 1 ));
               echo "<p class=\"text-center\"><img src=\"$imgtmp\" border=\"0\" alt=\"\" /></p>";
         } else {
         }
         echo "<p>".translate("Following are the articles published under this section.")."</p>";
         $result = sql_query("select artid, secid, title, content, userlevel, counter, timestamp from ".$NPDS_Prefix."seccont where secid='$secid' order by ordre");
         while (list($artid, $secid, $title, $content, $userlevel, $counter, $timestamp) = sql_fetch_row($result)) {
            $okprint2=autorisation_section($userlevel);
            if ($okprint2) {
               
               $nouveau="colspan=\"2\"";
               if ((time()-$timestamp)<(86400*7)) {
                  $nouveau="";
               }
               echo "<p class=\"lead\"><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">".aff_langue($title)."</a><small>
               ".translate("read:")."$counter ".translate("times")."</small>&nbsp;<a href=\"sections.php?op=printpage&amp;artid=$artid\" title=\"".translate("Printer Friendly Page")."\"><i class=\"fa fa-print\"></i></a>";
               if ($nouveau=="") {
                  echo "&nbsp;<i class=\"fa fa-star\"></i></i>";
               }
               echo "</p>";
            }
         }
         echo "<a class=\"btn btn-default\" href=\"sections.php\">".translate("Return to Sections Index")."</a>";
         
      } else {
         redirect_url("sections.php");
      }
      sql_free_result($result);
   }
   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include ('footer.php');
}

function viewarticle($artid, $page) {
   global $NPDS_Prefix;
   global $prev, $user;

   if (file_exists("sections.config.php"))
      include ("sections.config.php");

   if ($page=="")
      sql_query("update ".$NPDS_Prefix."seccont set counter=counter+1 where artid='$artid'");

   $result_S = sql_query("select artid, secid, title, content, counter, userlevel from ".$NPDS_Prefix."seccont where artid='$artid'");
   list($artid, $secid, $title, $Xcontent, $counter, $userlevel) = sql_fetch_row($result_S);
   list($secid, $secname, $rubid) = sql_fetch_row(sql_query("select secid, secname, rubid from ".$NPDS_Prefix."sections where secid='$secid'"));
   list($rubname) = sql_fetch_row(sql_query("select rubname from ".$NPDS_Prefix."rubriques where rubid='$rubid'"));
   $tmp_auto=explode(",",$userlevel);
   while (list(,$userlevel)=each($tmp_auto)) {
      $okprint=autorisation_section($userlevel);
      if ($okprint) break;
   }
   if ($okprint) {
      $old_title=$title;
         $pindex=substr($page,5,1);
         if ($pindex!="") {$pindex=" - ".translate("Next Page")." ".$pindex;}
         if ($sections_chemin) {
            $title=aff_langue($rubname)." - ".aff_langue($secname)." - ".aff_langue($title)." ".$pindex;
         } else {
            $title=aff_langue($title)." ".$pindex;
         }
         include("header.php");
      $title=aff_langue($old_title);

      global $SuperCache;
      if ($SuperCache) {
         $cache_obj = new cacheManager();
         $cache_obj->startCachingPage();
      } else {
         $cache_obj = new SuperCacheEmpty();
      }
      if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {
         $words = sizeof(explode(" ", $Xcontent));
         if ($prev==1) {echo "<input type=\"button\" value=\"".translate("Back to console")."\" onclick=\"javascript:history.back()\" /><br /><br />";}
         if (function_exists("themesection_title")) {
            themesection_title($title);
         } else {
            
            echo '<h3>'.$title.'&nbsp;';
            
         }
         
         echo "<small>($words ".translate("total words in this text)")."&nbsp;&nbsp;-&nbsp;&nbsp;
         ".translate("read:")." $counter ".translate("times")."&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"sections.php?op=printpage&amp;artid=$artid\" title=\"".translate("Printer Friendly Page")."\"><i class=\"fa fa-print\"></i></a></small></h3>";

         if ($page!="") {
            $Xcontent=substr($Xcontent,strpos($Xcontent,$page)+7);
            $multipage=true;
         } else
            $multipage=false;
         $pos_page=strpos($Xcontent,"[page");
         if ($pos_page) {
            $pageS=substr($Xcontent,$pos_page,7);
            $Xcontent=substr($Xcontent,0,$pos_page)."<br /><p class=\"text-center\"><a href=\"sections.php?op=viewarticle&amp;artid=$artid&amp;page=$pageS\">".translate("Next Page")."</a></p>";
         } else if($multipage) {
            $Xcontent.="<br /><p class=\"text-center\"><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">".translate("Top of the article")."</a></p>";
         }
         $Xcontent=aff_code(aff_langue($Xcontent));
         echo meta_lang($Xcontent);

         if ($togglesection)
            include_once ("lib/togglediv.class.php");

         $artidtempo=$artid;
         if ($rubname!="Divers") {
            
         echo "<a class=\"btn btn-default\" href=\"sections.php\">".translate("Return to Sections Index")."</a>"; 

		 
 /*         echo "<h4>***<strong>".translate("Back to chapter:")."</strong></h4>";
            echo "<ul class=\"list-group\"><li class=\"list-group-item\"><a href=\"sections.php?op=listarticles&amp;secid=$secid\">".aff_langue($secname)."</a></li></ul>";

            $result3 = sql_query("select artid, secid, title, userlevel from ".$NPDS_Prefix."seccont where (artid<>'$artid' and secid='$secid') order by ordre");
            $nb_article = sql_num_rows($result3);
            if ($togglesection)
               $toggle = new ToggleDiv(2);
            if ($nb_article > 0) {
               if ($togglesection)
                  echo $toggle->Img();
               echo "<h4>*<strong>".translate("Other courses in chapter:")."</strong></h4>";
               if ($togglesection)
                  echo $toggle->Begin();
               echo "<ul class=\"list-group\">";
               while (list($artid, $secid, $title, $userlevel) = sql_fetch_row($result3)) {
                  $okprint2=autorisation_section($userlevel);
                  if ($okprint2) {
                     echo "<li class=\"list-group-item\"><a href=\"sections.php?op=viewarticle&amp;artid=$artid\">".aff_langue($title)."</a></li>";
                  }
              }
              echo "</ul>";
              if ($togglesection)
                 echo $toggle->End();
            }*/
         }
         $artid=$artidtempo;
         $resultconnexe = sql_query("select id2 from ".$NPDS_Prefix."compatsujet where id1='$artid'");
         if (sql_num_rows($resultconnexe) > 0) {
            echo "<br />";
            if ($togglesection)
               echo "<br />".$toggle->Img();
            echo "<strong>".translate("You may be interested in:")."</strong>";
            if ($togglesection)
               echo $toggle->Begin();
            echo "<table width=\"100%\" border=\"1\" cellpadding=\"1\" cellspacing=\"1\" $rowcolor><tr><td><ul>";
            while(list($connexe) = sql_fetch_row($resultconnexe)) {
               $resultpdtcompat = sql_query("select artid, title, userlevel from ".$NPDS_Prefix."seccont where artid='$connexe'");
               list($artid2, $title, $userlevel) = sql_fetch_row($resultpdtcompat);
               $okprint2=autorisation_section($userlevel);
               if ($okprint2) {
                  echo "<li><a href=\"sections.php?op=viewarticle&amp;artid=$artid2\">".aff_langue($title)."</a></li>";
               }
            }
            echo "</ul></td></tr></table>";
            if ($togglesection)
               echo $toggle->End();
         }
         
      }
      sql_free_result($result_S);
      if ($SuperCache) {
         $cache_obj->endCachingPage();
      }
      include ('footer.php');
   } else {
      header("Location: sections.php");
   }
}

function PrintSecPage($artid) {
   global $NPDS_Prefix;
   global $user,$cookie, $theme,$Default_Theme, $site_logo, $sitename, $nuke_url, $language, $site_font, $Titlesitename;

   include("meta/meta.php");
   if (isset($user)) {
      if ($cookie[9]=="") $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) {
         $tmp_theme=$Default_Theme;
      }
   } else {
      $tmp_theme=$Default_Theme;
   }
   echo import_css($tmp_theme, $language, $site_font, "","");
   echo "</head>\n<body style=\"background-color: #FFFFFF; background-image: none;\">";
   echo "<p class=\"text-center\">";
   $pos = strpos($site_logo, "/");
   if ($pos)
      echo "<img src=\"$site_logo\" border=\"0\" alt=\"\" />";
   else
      echo "<img src=\"images/$site_logo\" border=\"0\" alt=\"\" />";

   $result=sql_query("select title, content from ".$NPDS_Prefix."seccont where artid='$artid'");
   list($title, $content) = sql_fetch_row($result);

   echo "<br /><br /><strong>".aff_langue($title)."</strong><br /><br /></p>";
   $content=aff_code(aff_langue($content));
   $pos_page=strpos($content,"[page");
   if ($pos_page) {
      $content=str_replace("[page",str_repeat("-",80)."&nbsp;[page",$content);
   }
   echo meta_lang($content);
   echo "<hr />
   <p class=\"text-center\">
   ".translate("This article comes from")." $sitename<br /><br />
   ".translate("The URL for this story is:")."
   <a href=\"$nuke_url/sections.php?op=viewarticle&amp;artid=$artid\">$nuke_url/sections.php?op=viewarticle&amp;artid=$artid</a>
   </p>
   </body>
   </html>";
}

function verif_aff($artid) {
   global $NPDS_Prefix;

    $result = sql_query("select secid from ".$NPDS_Prefix."seccont where artid='$artid'");
    list($secid) = sql_fetch_row($result);

    $result = sql_query("select userlevel from ".$NPDS_Prefix."sections where secid='$secid'");
    list($userlevel) = sql_fetch_row($result);
    $okprint=false;
    $okprint=autorisation_section($userlevel);
    return ($okprint);
}

settype($op,'string');
switch ($op) {
    case "viewarticle":
       if (verif_aff($artid)) {
          settype($page,'string');
          viewarticle($artid, $page);
       } else
          header ("location: sections.php");
       break;

    case "listarticles":
       listarticles($secid);
       break;

    case "printpage":
       if (verif_aff($artid))
          PrintSecPage($artid);
       else
          header ("location: sections.php");
       break;

    default:
       settype($rubric,'string');
       listsections($rubric);
       break;
}
?>