<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function readnews ($blog_dir, $op, $perpage, $startpage, $action, $adminblog) {
   global $tiny_mce;

   $content=""; $contentT="!l_new_pages!";
   $blog_file=$blog_dir."news.txt";
   if (!file_exists($blog_file)) {
      $fp=fopen($blog_file,"w");
      fclose($fp);
   }
   $xnews=file($blog_file);
   $xnews=array_reverse($xnews);
   $startpage-=1;
   $ubound=count($xnews);
   if ($startpage<0 || $startpage>=$ubound/$perpage) $startpage=0;
   if ($ubound>$perpage) {
      for ($j=1;$j<=ceil($ubound/$perpage);$j++) {
          if ($j==$startpage+1)
             $contentT.="<b>$j</b>&nbsp;";
          else
             $contentT.="<a href=\"minisite.php?op=$op&amp;startpage=$j\">$j</a>&nbsp;";
      }
   }
   if ($adminblog) {
      // Suppression
      if (substr($action,0,1)=="D") {
         @copy ($blog_file,$blog_file.".bak");
         $index=substr($action,1);
         unset ($xnews[$index]);
         $xnews=array_reverse($xnews);
         $fp=fopen($blog_file,"w");
         for ($j=0;$j<count($xnews);$j++) {
             fwrite($fp,$xnews[$j]);
         }
         fclose($fp);
         redirect_url("minisite.php?op=$op");
      }
      // Ajouter - Ecriture
      if (substr($action,0,3)=="AOK") {
         global $title, $story;
         @copy ($blog_file,$blog_file.".bak");
         $fp=fopen($blog_file,"a");
         if (!$tiny_mce) {
            $formatted=str_replace("\r\n","<br />",$story);
            $formatted=str_replace("<img","<img class=\"img-responsive\"",$story);			
            $formatted=str_replace("\n","<br />",$formatted);			
         } else {
            $formatted=str_replace("\r\n","",$story);
            $formatted=str_replace("<img","<img class=\"img-responsive\"",$story);			
            $formatted=str_replace("\n","",$formatted);			
         }
         $newsto=date("d m Y")."!;!".$title."!;!".$formatted;
         fwrite($fp,StripSlashes($newsto)."\n");
         fclose($fp);
         redirect_url("minisite.php?op=$op");
      }
      // Ajouter
      if (substr($action,0,1)=="A") {
         $content.='<form class="form-horizontal" role="form" name="adminForm" method="post" action="minisite.php?op='.$op.'&action=AOK">';

         $content.='<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">'.translate("Title").' :</label>
						</div>
						<div class="col-sm-10"><input class="form-control" type="text" name="title" /></div></div>';
         $content.='<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">'.translate("Full Text").' :</label>
						</div>
						<div class="col-sm-10"><textarea class="form-control" name="story" rows="25"></textarea>';
         $content.="&nbsp;!blog_editeur!";
         $content.='</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10"><input class="btn btn-primary" type="submit" name="submit" value="'.translate("Submit").'" /></div>
					</div>';
         $content.="</form>\n";
      }
      // Modifier - Ecriture
      if (substr($action,0,3)=="MOK") {
         global $title, $story, $index;
         @copy ($blog_file,$blog_file.".bak");
         if (!$tiny_mce) {
            $formatted=str_replace("\r\n","<br />",$story);
            $formatted=str_replace("<img","<img class=\"img-responsive\"",$story);
            $formatted=str_replace("\n","<br />",$formatted);			
         } else {
            $formatted=str_replace("\r\n","",$story);
            $formatted=str_replace("<img","<img class=\"img-responsive\"",$story);			
            $formatted=str_replace("\n","",$formatted);			
         }
         $newsto=date("d m Y")."!;!".$title."!;!".$formatted;
         $xnews[$index]=StripSlashes($newsto)."\n";
         $xnews=array_reverse($xnews);
         $fp=fopen($blog_file,"w");
         for ($j=0;$j<count($xnews);$j++) {
             fwrite($fp,$xnews[$j]);
         }
         fclose($fp);
         redirect_url("minisite.php?op=$op");
      }
      // Modifier
      if (substr($action,0,1)=="M") {
         $index=substr($action,1);
         $crtsplit=explode("!;!",$xnews[$index]);
         $content.='<form class="form-horizontal" role="form" name="adminForm" method="post" action="minisite.php?op='.$op.'&action=MOK&index='.$index.'">';

         $content.='<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">'.translate("Title").' :</label>
						</div>
						<div class="col-sm-10"><input class="form-control" type="text" name="title" value="'.$crtsplit[1].'" /></div></div>';
         $content.='<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">'.translate("Full Text").' :</label>
						</div>
						<div class="col-sm-10"><textarea class="form-control" name="story" rows="25">'.str_replace("\n","",$crtsplit[2]).'</textarea>';
         $content.="&nbsp;!blog_editeur!";
         $content.='</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10"><input class="btn btn-primary" type="submit" name="submit" value="'.translate("Submit").'" /></div>
					</div>';
         $content.="</form>\n";
		 
      }
   }
   if ($contentT!="") {$colspan=1;};
   if ($adminblog) {
      $colspan=$colspan+2;
   } else {
      $colspan=$colspan+1;
   }

   // Output
   $new_pages=false;
   $content.="!v_yt!";
   if ($adminblog) {
      $content.="!l_blog_ajouterOK!";
	  $content.='<br /><br />';
   }
   for ($i=$startpage*$perpage;$i<$startpage*$perpage+$perpage && $i<$ubound;$i++) {
   
       $crtsplit=explode("!;!",$xnews[$i]);   
       $actionM="<a class=\"btn btn-warning\" href=\"minisite.php?op=$op&amp;action=M$i\">".translate("Modify")."</a>";
       $actionD="<a class=\"btn btn-danger\" href=\"minisite.php?op=$op&amp;action=D$i\">".translate("Delete")."</a>";
       $content.="<h2>".aff_langue($crtsplit[1])." <small><i class=\"fa fa-clock-o\"></i> ".translate("Posted on ")."".$crtsplit[0]."</small></h2>";
       if (substr($contentT,13)!="") {$content.="".substr($contentT,13)."";};
       if ($adminblog) {
//		  $content.='<br /><br />';
          $content.="&nbsp;$actionM&nbsp;&nbsp;$actionD";
		  $content.='<br /><br />';
       } else {
          $content.="";
       }
       $content.="".convert_ressources($crtsplit[2])."";
       $content.="&nbsp;";
   }
   $content.="\n";
   if (!$new_pages) {$content.=$contentT;}
   return ($content);
}
?>