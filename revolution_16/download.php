<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include_once("lib/file.class.php");
include('functions.php');

function geninfo($did,$out_template) {
   global $sitename, $NPDS_Prefix;
   settype($did, 'integer');
   settype($out_template, 'integer');
   $result = sql_query("SELECT dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
   list($dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $dperm) = sql_fetch_row($result);

   $okfile=false;
   if(!stristr($dperm,',')) $okfile=autorisation($dperm);
   else {
      $ibidperm=explode(',',$dperm);
      foreach($ibidperm as $v) {
         if (autorisation($v)) {$okfile=true; break;}
      }
   }

   if ($okfile) {
      $title=$dfilename;
      if ($out_template==1) {
         include('header.php');
         echo '
         <h2 class="mb-3">'.translate("Chargement de fichiers").'</h2>
         <div class="card">
            <div class="card-header"><h4>'.$dfilename.'<span class="ms-3 text-body-secondary small">@'.$durl.'</h4></div>
            <div class="card-body">';
      }
      echo '
               <p><strong>'.translate("Taille du fichier").' : </strong>';
      $Fichier = new File($durl);
      $objZF = new FileManagement;
      echo ($dfilesize!=0) ? $objZF->file_size_format($dfilesize, 1) : $objZF->file_size_auto($durl, 2) ;
      echo '</p>
               <p><strong>'.translate("Version").'&nbsp;:</strong>&nbsp;'.$dver.'</p>
               <p><strong>'.translate("Date de chargement sur le serveur").'&nbsp;:</strong>&nbsp;'.formatTimes($ddate, IntlDateFormatter::SHORT, IntlDateFormatter::NONE).'</p>
               <p><strong>'.translate("Chargements").'&nbsp;:</strong>&nbsp;'.wrh($dcounter).'</p>
               <p><strong>'.translate("Catégorie").'&nbsp;:</strong>&nbsp;'.aff_langue(stripslashes($dcategory)).'</p>
               <p><strong>'.translate("Description").'&nbsp;:</strong>&nbsp;'.aff_langue(stripslashes($ddescription)).'</p>
               <p><strong>'.translate("Auteur").'&nbsp;:</strong>&nbsp;'.$duser.'</p>
               <p><strong>'.translate("Page d'accueil").'&nbsp;:</strong>&nbsp;<a href="http://'.$dweb.'" target="_blank">'.$dweb.'</a></p>';
      if ($out_template==1) {
         echo '
               <a class="btn btn-primary" href="download.php?op=mydown&amp;did='.$did.'" target="_blank" title="'.translate("Charger maintenant").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-lg fa-download"></i></a>
            </div>
         </div>';
         include('footer.php');
      }
   }
else
   Header("Location: download.php");
}

function tlist() {
   global $sortby, $dcategory, $download_cat, $NPDS_Prefix;
   if ($dcategory == '') { $dcategory = addslashes($download_cat); }
   $cate = stripslashes($dcategory);
   echo '
   <p class="lead">'.translate("Sélectionner une catégorie").'</p>
   <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-between my-3 border rounded">
      <p class="p-2 mb-0 ">';
   $acounter = sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads");
   list($acount) = sql_fetch_row($acounter);
   if (($cate == translate("Tous")) OR ($cate == ''))
      echo '<i class="fa fa-folder-open fa-2x text-body-secondary align-middle me-2"></i><strong><span class="align-middle">'.translate("Tous").'</span>
<span class="badge bg-secondary ms-2 float-end my-2">'.$acount.'</span></strong>';
   else
      echo '<a href="download.php?dcategory='.translate("Tous").'&amp;sortby='.$sortby.'"><i class="fa fa-folder fa-2x align-middle me-2"></i><span class="align-middle">'.translate("Tous").'</span></a><span class="badge bg-secondary ms-2 float-end my-2">'.$acount.'</span>';
   $result = sql_query("SELECT DISTINCT dcategory, COUNT(dcategory) FROM ".$NPDS_Prefix."downloads GROUP BY dcategory ORDER BY dcategory");
   echo '</p>';
   while (list($category, $dcount) = sql_fetch_row($result)) {
      $category=stripslashes($category);
      echo '<p class="p-2 mb-0">';
      if ($category == $cate)
         echo '<i class="fa fa-folder-open fa-2x text-body-secondary align-middle me-2"></i><strong class="align-middle">'.aff_langue($category).'<span class="badge bg-secondary ms-2 float-end my-2">'.$dcount.'</span></strong>';
      else {
         $category2 = urlencode($category);
         echo '<a href="download.php?dcategory='.$category2.'&amp;sortby='.$sortby.'"><i class="fa fa-folder fa-2x align-middle me-2"></i><span class="align-middle">'.aff_langue($category).'</span></a><span class="badge bg-secondary ms-2 my-2 float-end">'.$dcount.'</span>';
      }
      echo '</p>';
   }
   echo '
   </div>';
}

function act_dl_tableheader($dcategory, $sortby, $fieldname, $englishname) {
   echo '
         <a class="d-none d-sm-inline" href="download.php?dcategory='.$dcategory.'&amp;sortby='.$fieldname.'" title="'.translate("Croissant").'" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-down"></i></a>&nbsp;
         '.translate("$englishname").'&nbsp;
         <a class="d-none d-sm-inline" href="download.php?dcategory='.$dcategory.'&amp;sortby='.$fieldname.'&amp;sortorder=DESC" title="'.translate("Décroissant").'" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-up"></i></a>';
}

function inact_dl_tableheader($dcategory, $sortby, $fieldname, $englishname) {
   echo '
         <a class="d-none d-sm-inline" href="download.php?dcategory='.$dcategory.'&amp;sortby='.$fieldname.'" title="'.translate("Croissant").'" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-down" ></i></a>&nbsp;
         '.translate("$englishname").'&nbsp;
         <a class="d-none d-sm-inline" href="download.php?dcategory='.$dcategory.'&amp;sortby='.$fieldname.'&amp;sortorder=DESC" title="'.translate("Décroissant").'" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-up" ></i></a>';
}

function dl_tableheader () {
   echo '</td>
   <td>';
}

function popuploader($did, $ddescription, $dcounter, $dfilename, $aff) {
   global $dcategory, $sortby;
   $out_template = 0;
   if ($aff) {
      echo '
         <a class="me-3" href="#" data-bs-toggle="modal" data-bs-target="#mo'.$did.'" title="'.translate("Information sur le fichier").'" data-bs-toggle="tooltip"><i class="fa fa-info-circle fa-2x"></i></a>
         <a href="download.php?op=mydown&amp;did='.$did.'" target="_blank" title="'.translate("Charger maintenant").'" data-bs-toggle="tooltip"><i class="fa fa-download fa-2x"></i></a>
        <div class="modal fade" id="mo'.$did.'" tabindex="-1" role="dialog" aria-labelledby="my'.$did.'" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                  <h4 class="modal-title text-start" id="my'.$did.'">'.translate("Information sur le fichier").' - '.$dfilename.'</h4>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title=""></button>
                  </div>
                  <div class="modal-body text-start">';
               geninfo($did,$out_template);
      echo '
                  </div>
                  <div class="modal-footer">
                     <a class="" href="download.php?op=mydown&amp;did='.$did.'" title="'.translate("Charger maintenant").'"><i class="fa fa-2x fa-download"></i></a>
                  </div>
               </div>
            </div>
         </div>';
   }
}

function SortLinks($dcategory, $sortby) {
   global $user;
   $dcategory=stripslashes($dcategory);
   echo '
      <thead>
         <tr>
            <th class="text-center">'.translate("Fonctions").'</th>
            <th class="text-center n-t-col-xs-1" data-sortable="true" data-sorter="htmlSorter">'.translate("Type").'</th>
            <th class="text-center">';
   if ($sortby == 'dfilename' OR !$sortby)
      act_dl_tableheader($dcategory, $sortby, "dfilename", "Nom");
   else
     inact_dl_tableheader($dcategory, $sortby, "dfilename", "Nom");
   echo '</th>
            <th class="text-center">';
   if ($sortby == "dfilesize")
      act_dl_tableheader($dcategory, $sortby, "dfilesize", "Taille");
   else
      inact_dl_tableheader($dcategory, $sortby, "dfilesize", "Taille");
   echo '</th>
            <th class="text-center">';
   if ($sortby == "dcategory")
     act_dl_tableheader($dcategory, $sortby, "dcategory", "Catégorie");
   else
     inact_dl_tableheader($dcategory, $sortby, "dcategory", "Catégorie");
   echo '</th>
            <th class="text-center">';
   if ($sortby == "ddate")
     act_dl_tableheader($dcategory, $sortby, "ddate", "Date");
   else
     inact_dl_tableheader($dcategory, $sortby, "ddate", "Date");
   echo '</th>
            <th class="text-center">';
   if ($sortby == "dver")
     act_dl_tableheader($dcategory, $sortby, "dver", "Version");
   else
     inact_dl_tableheader($dcategory, $sortby, "dver", "Version");
   echo '</th>
            <th class="text-center">';
   if ($sortby == "dcounter")
      act_dl_tableheader($dcategory, $sortby, "dcounter", "Compteur");
   else
     inact_dl_tableheader($dcategory, $sortby, "dcounter", "Compteur");
   echo '</th>';
   if($user or autorisation(-127))
         echo '
            <th class="text-center n-t-col-xs-1"></th>';
   echo '
         </tr>
      </thead>';
}

function listdownloads ($dcategory, $sortby, $sortorder) {
   global $perpage, $page, $download_cat, $user, $NPDS_Prefix;

   if ($dcategory == '') $dcategory = addslashes($download_cat);
   if (!$sortby) $sortby = 'dfilename';
   if (($sortorder!="ASC") && ($sortorder!="DESC"))
      $sortorder = "ASC";
   echo '
<p class="lead">';
   echo translate("Affichage filtré pour")."&nbsp;<i>";
   if ($dcategory==translate("Tous"))
      echo '<b>'.translate("Tous").'</b>';
   else
      echo '<b>'.aff_langue(stripslashes($dcategory)).'</b>';
   echo '</i>&nbsp;'.translate("trié par ordre").'&nbsp;';

   // Shiney SQL Injection 11/2011
   $sortby2='';
   if ($sortby == 'dfilename')
      $sortby2 = translate("Nom")."";
   if ($sortby == 'dfilesize')
      $sortby2 = translate("Taille du fichier")."";
   if ($sortby == 'dcategory')
      $sortby2 = translate("Catégorie")."";
   if ($sortby == 'ddate')
      $sortby2 = translate("Date de création")."";
   if ($sortby == 'dver')
      $sortby2 = translate("Version")."";
   if ($sortby == 'dcounter')
      $sortby2 = translate("Chargements")."";
   // Shiney SQL Injection 11/2011
   if ($sortby2=='')
      $sortby = 'dfilename';
   echo translate("de").'&nbsp;<i><b>'.$sortby2.'</b></i>
</p>';

   echo '
   <table class="table table-hover mb-3 table-sm" id ="lst_downlo" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true"
data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">';
   sortlinks($dcategory, $sortby);
   echo '
      <tbody>';
   if ($dcategory==translate("Tous"))
      $sql="SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads";
   else
      $sql="SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."'";
   $result = sql_query($sql);
   list($total) =  sql_fetch_row($result);
//
  if ($total>$perpage) {
    $pages=ceil($total/$perpage);
    if ($page > $pages) $page = $pages;
    if (!$page) $page=1;
    $offset=($page-1)*$perpage;
  } else {
    $offset=0;
    $pages=1;
    $page=1;
  }
//  
   $nbPages = ceil($total/$perpage);
   $current = 1;
   if ($page >= 1)
      $current=$page;
   else if ($page < 1)
      $current=1;
   else
      $current = $nbPages;

   settype($offset, 'integer');
   settype($perpage, 'integer');
   if ($dcategory==translate("Tous"))
      $sql="SELECT * FROM ".$NPDS_Prefix."downloads ORDER BY $sortby $sortorder LIMIT $offset,$perpage";
   else
      $sql="SELECT * FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."' ORDER BY $sortby $sortorder LIMIT $offset,$perpage";

   $result = sql_query($sql);
   while(list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcat, $ddescription, $dperm) = sql_fetch_row($result)) {
      $Fichier = new File($durl);// keep for extension
      $FichX = new FileManagement;
      $okfile='';
      if(!stristr($dperm,',')) $okfile=autorisation($dperm);
      else {
         $ibidperm=explode(',',$dperm);
         foreach($ibidperm as $v) {
            if(autorisation($v)==true) {$okfile=true; break;}
         }
      };
      echo '
         <tr>
            <td class="text-center">';
      if ($okfile==true)
         echo popuploader($did, $ddescription, $dcounter, $dfilename,true);
      else {
         echo popuploader($did, $ddescription, $dcounter, $dfilename,false);
         echo '<span class="text-danger"><i class="fa fa-ban fa-lg me-1"></i>'.translate("Privé").'</span>';
      }
      echo '</td>
            <td class="text-center">'.$Fichier->Affiche_Extention('webfont').'</td>
            <td>';
      if ($okfile==true)
         echo '<a href="download.php?op=mydown&amp;did='.$did.'" target="_blank">'.$dfilename.'</a>';
      else
         echo '<span class="text-danger"><i class="fa fa-ban fa-lg me-1"></i>...</span>';
      echo '</td>
            <td class="small text-center">';
      echo ($dfilesize!=0) ? 
         $FichX->file_size_format($dfilesize, 1) : 
         $FichX->file_size_auto($durl, 2) ;
      echo '</td>
            <td>'.aff_langue(stripslashes($dcat)).'</td>
            <td class="small text-center">'.formatTimes($ddate, IntlDateFormatter::SHORT, IntlDateFormatter::NONE).'</td>
            <td class="small text-center">'.$dver.'</td>
            <td class="small text-center">'.wrh($dcounter).'</td>';
      if ($user!='' or autorisation(-127)) {
         echo '
            <td>';
         if ( ($okfile==true and $user!='') or autorisation(-127))
            echo '<a href="download.php?op=broken&amp;did='.$did.'" title="'.translate("Rapporter un lien rompu").'" data-bs-toggle="tooltip"><i class="fas fa-lg fa-unlink"></i></a>';
         echo '
            </td>';
      }
      echo '
         </tr>';
   }
   echo '
      </tbody>
   </table>';

   $dcategory = StripSlashes($dcategory);
   echo '<div class="mt-3"></div>'.paginate_single('download.php?dcategory='.$dcategory.'&amp;sortby='.$sortby.'&amp;sortorder='.$sortorder.'&amp;page=', '', $nbPages, $current, $adj=3, '', $page);
}

function main() {
   global $dcategory, $sortby, $sortorder, $sitename;
   $dcategory  = removeHack(stripslashes(htmlspecialchars(urldecode($dcategory),ENT_QUOTES,'UTF-8'))); // electrobug
   $dcategory=str_replace("&#039;","\'",$dcategory);
   $sortby  = removeHack(stripslashes(htmlspecialchars(urldecode($sortby),ENT_QUOTES,'UTF-8'))); // electrobug

   include("header.php");
   echo '
   <h2>'.translate("Chargement de fichiers").'</h2>
   <hr />';
   tlist();
   if ($dcategory!=translate("Aucune catégorie"))
     listdownloads($dcategory, $sortby, $sortorder);
   if (file_exists("static/download.ban.txt"))
      include("static/download.ban.txt");
   include("footer.php");
}

function transferfile($did) {
   global $NPDS_Prefix;
   settype($did, 'integer');
   $result = sql_query("SELECT dcounter, durl, perms FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
   list($dcounter, $durl, $dperm) = sql_fetch_row($result);
   if (!$durl) {
      include("header.php");
      echo '
   <h2>'.translate("Chargement de fichiers").'</h2>
   <hr />
   <div class="lead alert alert-danger">'.translate("Ce fichier n'existe pas ...").'</div>';
      include("footer.php");
   } else {
      if(stristr($dperm,',')) {
         $ibid=explode(',',$dperm);
         foreach($ibid as $v) {
            $aut=true;
            if(autorisation($v)==true) {
               $dcounter++; 
               sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter' WHERE did='$did'");
               header("location: ".str_replace(basename($durl),rawurlencode(basename($durl)), $durl));
               break;
            } else $aut=false;
         }
         if($aut==false) Header("Location: download.php");
      } else {
         if (autorisation($dperm)) {
            $dcounter++;
            sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter' WHERE did='$did'");
            header("location: ".str_replace(basename($durl),rawurlencode(basename($durl)), $durl));
         } else
            Header("Location: download.php");
      }
   }
}

function broken($did) {
   global $user, $cookie;

   settype($did, 'integer');
   if ($user) {
      if ($did) {
         global $notify_email, $notify_message, $notify_from, $nuke_url;
         settype ($did, "integer");
         $message=$nuke_url."\n".translate("Téléchargements")." ID : $did\n".translate("Auteur")." $cookie[1] / IP : ".getip()."\n\n";
         include 'signat.php';
         send_email($notify_email, html_entity_decode(translate("Rapporter un lien rompu"),ENT_COMPAT | ENT_HTML401,'UTF-8'), nl2br($message), $notify_from , false, "html", '');
         include("header.php");
         echo '
        <div class="alert alert-success">
           <p class="lead">'.translate("Pour des raisons de sécurité, votre nom d'utilisateur et votre adresse IP vont être momentanément conservés.").'<br />'.translate("Merci pour cette information. Nous allons l'examiner dès que possible.").'</p>
        </div>';
         include("footer.php");
      } else
         Header("Location: download.php");
   } else
    Header("Location: download.php");
}

settype($op,'string');
switch ($op) {
   case 'main':
      main();
   break;
   case 'mydown':
      transferfile($did);
   break;
   case 'geninfo':
      geninfo($did,$out_template);
   break;
   case 'broken':
      broken($did);
   break;
   default:
      main();
   break;
}
?>