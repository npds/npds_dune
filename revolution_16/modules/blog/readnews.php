<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function readnews ($blog_dir, $op, $perpage, $startpage, $action, $adminblog) {
   global $tiny_mce;

   $content='';
   settype($contentT,'string');
   $blog_file=$blog_dir.'news.txt';
   if (!file_exists($blog_file)) {
      $fp=fopen($blog_file,'w');
      fclose($fp);
   }
   $xnews=file($blog_file);
   $xnews=array_reverse($xnews);
   $startpage-=1;
   $ubound=count($xnews);
   if ($startpage<0 || $startpage>=$ubound/$perpage) $startpage=0;
   if ($ubound>$perpage) {
      $contentT.='
      <nav>
         <ul class="pagination pagination-sm d-flex flex-wrap my-2">';
      for ($j=1;$j<=ceil($ubound/$perpage);$j++) {
          if ($j==$startpage+1)
             $contentT.='
             <li class=" page-item active"><a class="page-link" href="#">'.$j.'</a></li>';
          else
             $contentT.='
             <li class="page-item"><a href="minisite.php?op='.$op.'&amp;startpage='.$j.'" class="page-link blog_lien">'.$j.'</a></li>';
      }
       $contentT.='
         </ul>
      </nav>';
   }
   if ($adminblog) {
      // Suppression
      if (substr($action,0,1)=='D') {
         @copy ($blog_file,$blog_file.'.bak');
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
      if (substr($action,0,3)=='AOK') {
         global $title, $story;
         @copy ($blog_file,$blog_file.'.bak');
         $fp=fopen($blog_file,"a");
         if (!$tiny_mce) {
            $formatted=str_replace("\r\n",'<br />',$story);
            $formatted=str_replace('<img','<img class="img-fluid" ',$story);
            $formatted=str_replace("\n",'<br />',$formatted);
         } else {
            $formatted=str_replace("\r\n",'',$story);
            $formatted=str_replace('<img','<img class="img-fluid" ',$story);
            $formatted=str_replace("\n",'',$formatted);
         }
         $newsto=date("d m Y").'!;!'.$title.'!;!'.$formatted;
         fwrite($fp,StripSlashes($newsto)."\n");
         fclose($fp);
         redirect_url("minisite.php?op=$op");
      }
      // Ajouter
      if (substr($action,0,1)=='A') {
         $content.='
         <form name="adminForm" method="post" action="minisite.php?op='.$op.'&action=AOK">
            <div class="form-group row">
               <label class="form-control-label col-sm-12" for="title">'.translate("Titre").'</label>
               <div class="col-sm-12">
                  <input class="form-control" type="text" name="title" />
               </div>
            </div>
            <div class="form-group row">
               <label class="form-control-label col-sm-12" for="story">'.translate("Texte complet").'</label>
               <div class="col-sm-12">
                  <textarea class="tin form-control" name="story" rows="25"></textarea>';
            $content.="&nbsp;!blog_editeur!";
            $content.='
               </div>
            </div>
            <div class="form-group row">
               <div class="col-sm-12">
                  <input class="btn btn-primary" type="submit" name="submit" value="'.translate("Valider").'" />
               </div>
            </div>
         </form>';
      }
      // Modifier - Ecriture
      if (substr($action,0,3)=='MOK') {
         global $title, $story, $index;
         @copy ($blog_file,$blog_file.".bak");
         if (!$tiny_mce) {
            $formatted=str_replace("\r\n",'<br />',$story);
            $formatted=str_replace('<img','<img class="img-fluid" ',$story); // a revoir ??
            $formatted=str_replace("\n",'<br />',$formatted);
         } else {
            $formatted=str_replace("\r\n",'',$story);
            $formatted=str_replace("<img",'<img class="img-fluid" ',$story); // a revoir ??
            $formatted=str_replace("\n",'',$formatted);
         }
         $newsto=date("d m Y").'!;!'.$title.'!;!'.$formatted;
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
      if (substr($action,0,1)=='M') {
         $index=substr($action,1);
         $crtsplit=explode("!;!",$xnews[$index]);
         $videoprovider=array('yt','vm','dm');
         foreach($videoprovider as $v) {
            $crtsplit[2]= preg_replace('#('.$v.')_(video)\((.*[^\)])\)#m', '[\2_\1]\3[/\2_\1]', $crtsplit[2]);
         }
         $content.='
         <form name="adminForm" method="post" action="minisite.php?op='.$op.'&action=MOK&index='.$index.'">
            <div class="form-group">
               <label class="form-control-label" for="title">'.translate("Titre").'</label>
               <input class="form-control" type="text" name="title" value="'.$crtsplit[1].'" />
            </div>
            <div class="form-group">
               <label class="form-control-label" for="story" >'.translate("Texte complet").'</label>
               <textarea class="tin form-control" name="story" rows="25">'.str_replace("\n","",$crtsplit[2]).'</textarea>';
      $content.="&nbsp;!blog_editeur!";
      $content.='
            </div>
            <div class="form-group">
               <input class="btn btn-primary" type="submit" name="submit" value="'.translate("Valider").'" />
            </div>
         </form>
         #v_yt#';
      }
   }

   // Output
   $new_pages=false;
   for ($i=$startpage*$perpage;$i<$startpage*$perpage+$perpage && $i<$ubound;$i++) {
       $crtsplit=explode('!;!',$xnews[$i]);
       $actionM='<a class="" href="minisite.php?op='.$op.'&amp;action=M'.$i.'" title="'.translate("Modifier").'" data-toggle="tooltip" ><i class="fa fa-edit fa-lg mr-1"></i></a>';
       $actionD='<a class="" href="minisite.php?op='.$op.'&amp;action=D'.$i.'" title="'.translate("Effacer").'" data-toggle="tooltip"><i class="far fa-trash-alt fa-lg text-danger"></i></a>';
       $content.= '
      <div class="card mb-3">
         <div class="card-body">
            <h2 class="card-title">'.aff_langue($crtsplit[1]).'</h2>
            <h6 class="card-subtitle text-muted">'.translate("Posté le ").' '.$crtsplit[0].'</h6>
         </div>
         <div class=" card-body">'.convert_ressources($crtsplit[2]).'</div>';
      if ($adminblog) {
       $content.='
          <div class="card-footer">
            '.$actionM.'&nbsp;'.$actionD.'
         </div>';
       }
       $content.= '
       </div>';
   }
   settype($contentT,'string');
   if (substr($contentT,13)!='') {$content.=substr($contentT,13);};
   $content.="\n";
   return ($content);
}
?>