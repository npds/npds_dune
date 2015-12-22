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
/*                      Revision 22/12/2015                             */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
include_once("lib/file.class.php");
include('functions.php');

function geninfo($did) {
  global $sitename;
  global $NPDS_Prefix;

  settype($did, 'integer');
  $result = sql_query("SELECT dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
  list($dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $dperm) = sql_fetch_row($result);
  $okfile=autorisation($dperm);
  if ($okfile) {
     $title=$dfilename;

     echo '<p><strong>'.translate("File Size").' : </strong>';
     $Fichier = new File($durl);
     $objZF    =    new FileManagement;
     if ($dfilesize!=0) {
     echo $objZF->file_size_auto($durl, 2);
        echo $Fichier->Pretty_Size($dfilesize);
     } else {
        echo $Fichier->Affiche_Size();
             echo $objZF->file_size_auto($durl, 2);

     }
   echo '
   </p>
   <p><strong>'.translate("Version").'&nbsp;:</strong>&nbsp;'.$dver.'</p>
   <p><strong>'.translate("Upload Date").'&nbsp;:</strong>&nbsp;'.convertdate($ddate).'</p>
   <p><strong>'.translate("Downloads").'&nbsp;:</strong>&nbsp;'.wrh($dcounter).'</p>
   <p><strong>'.translate("Category").'&nbsp;:</strong>&nbsp;'.aff_langue(stripslashes($dcategory)).'</p>
   <p><strong>'.translate("Description").'&nbsp;:</strong>&nbsp;'.aff_langue(stripslashes($ddescription)).'</p>
   <strong>'.translate("Author").'&nbsp;:</strong>&nbsp;'.$duser.'</p>
   <strong>'.translate("HomePage").'&nbsp;:</strong>&nbsp;<a href="http://'.$dweb.'" target="_blank">'.$dweb.'</a></p>';
  } else {
     Header("Location: download.php");
  }
}

function tlist() {
   global $sortby, $dcategory, $download_cat;
   global $NPDS_Prefix;
   if ($dcategory == "") { $dcategory = addslashes($download_cat); }
   $cate = stripslashes($dcategory);
   echo '
   <p class="lead">'.translate("Select Category Folder").'</p>
   <div class="row">
      <div class="col-sm-3">';
   $acounter = sql_query("SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads");
   list($acount) = sql_fetch_row($acounter);
   if (($cate == translate("All")) OR ($cate == "")) {
     echo "<i class=\"fa fa-2x fa-folder-open\"></i><strong>".translate("All")."&nbsp;($acount)</strong>\n";
   } else {
     echo "<a href=\"download.php?dcategory=".translate("All")."&amp;sortby=$sortby\"><i class=\"fa fa-2x fa-folder\"></i>&nbsp;".translate("All")."</a>&nbsp;($acount)\n";
   }
  $result = sql_query("SELECT DISTINCT dcategory, COUNT(dcategory) FROM ".$NPDS_Prefix."downloads GROUP BY dcategory ORDER BY dcategory");
echo '</div>';
  $rup=2;
  while (list($category, $dcount) = sql_fetch_row($result)) {
    $rup++;
    $category=stripslashes($category);
  echo '<div class="col-sm-3">';
    if ($category == $cate) {
        echo "<i class=\"fa fa-2x fa-folder-open\"></i>&nbsp;<strong>".aff_langue($category)."&nbsp;($dcount)</strong>\n";
    } else {
        $category2 = urlencode($category);
        echo "<a href=\"download.php?dcategory=$category2&amp;sortby=$sortby\"><i class=\"fa fa-2x fa-folder\"></i>&nbsp;".aff_langue($category)."</a>&nbsp;($dcount)\n";
    }
      echo '</div>';

    if ($rup>=5) {
       $rup=1;
    }
  }
echo '
   </div>
   <br />';
}

function act_dl_tableheader($dcategory, $sortby, $fieldname, $englishname) {
  echo "<a href=\"download.php?dcategory=$dcategory&amp;sortby=$fieldname\" title=\"".translate("Ascending")."\"><i class=\"fa fa-sort-amount-asc\"></i></a>&nbsp;";
  echo translate("$englishname");
  echo "&nbsp;<a href=\"download.php?dcategory=$dcategory&amp;sortby=$fieldname&amp;sortorder=DESC\" title=\"".translate("Descending")."\"><i class=\"fa fa-sort-amount-desc\"></i></a>";
}

function inact_dl_tableheader($dcategory, $sortby, $fieldname, $englishname) {
  echo "<a href=\"download.php?dcategory=$dcategory&amp;sortby=$fieldname\"><i class=\"fa fa-sort-amount-asc\"  title=\"".translate("Ascending")."\"></i></a>&nbsp;";
  echo translate("$englishname");  
  echo "&nbsp;<a href=\"download.php?dcategory=$dcategory&amp;sortby=$fieldname&amp;sortorder=DESC\"><i class=\"fa fa-sort-amount-desc\" title=\"".translate("Descending")."\"></i></a>";
}

function dl_tableheader () {
  echo '</td><td>';
}

function popuploader($did, $ddescription, $dcounter, $dfilename,$aff) {
  global $dcategory, $sortby;
  if ($aff) {

   echo '<a data-toggle="modal" data-target="#'.$did.'" title="'.translate("File Information").'">
         <i class="fa fa-lg fa-info-circle"></i></a>&nbsp;&nbsp;&nbsp;';

   echo '<div class="modal fade" id="'.$did.'" tabindex="-1" role="dialog" aria-labelledby="my'.$did.'" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" title=""><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title text-left" id="my'.$did.'">'.translate("File Information").' - '.$dfilename.'</h4>
               </div>
            <div class="modal-body text-left" style="max-height: 1000px;">';
               geninfo($did);
   echo '</div>
      <div class="modal-footer">
         <a class="btn btn-primary" href="download.php?op=mydown&amp;did='.$did.'" title="'.translate("Download Now!").'"><i class="fa fa-lg fa-download"></i></a>
      </div>
   </div>
   </div>
</div>';
   echo "<a href=\"download.php?op=mydown&amp;did=$did\" title=\"".translate("Download Now!")."\"><i class=\"fa fa-download\"></i></a>";
   }
}

function SortLinks($dcategory, $sortby) {
  $dcategory=stripslashes($dcategory);
  echo '
      <thead>
         <tr>
            <th class="text-xs-center">'.translate("Info").'</th>
            <th class="text-xs-center">'.translate("Type").'</th>
            <th class="text-xs-center">';
  if ($sortby == 'dfilename' OR !$sortby) {
     act_dl_tableheader($dcategory, $sortby, "dfilename", "Name");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "dfilename", "Name");
  }
  echo '</th>
            <th class="text-xs-center">';
  if ($sortby == "dfilesize") {
     act_dl_tableheader($dcategory, $sortby, "dfilesize", "Size");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "dfilesize", "Size");
  }
  echo "</th><th class=\"text-xs-center\">";
  if ($sortby == "dcategory") {
     act_dl_tableheader($dcategory, $sortby, "dcategory", "Category");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "dcategory", "Category");
  }
  echo "</th><th class=\"text-xs-center\">";
  if ($sortby == "ddate") {
     act_dl_tableheader($dcategory, $sortby, "ddate", "Date");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "ddate", "Date");
  }
  echo "</th><th class=\"text-xs-center\">";
  if ($sortby == "dver") {
     act_dl_tableheader($dcategory, $sortby, "dver", "Version");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "dver", "Version");
  }
  echo "</th><th class=\"text-xs-center\">";
  if ($sortby == "dcounter") {
     act_dl_tableheader($dcategory, $sortby, "dcounter", "Counter");
  } else {
     inact_dl_tableheader($dcategory, $sortby, "dcounter", "Counter");
  }
  echo "</th><th class=\"text-xs-center\">&nbsp;</th></tr></thead>";
}

function listdownloads ($dcategory, $sortby, $sortorder) {
  global $perpage, $page, $download_cat, $user;
  global $NPDS_Prefix;

  if ($dcategory == "") { $dcategory = addslashes($download_cat); }
  if (!$sortby) { $sortby = "dfilename"; }
  if (($sortorder!="ASC") && ($sortorder!="DESC")) {
     $sortorder = "ASC";
  }
echo '<p class="lead">';
  echo translate("Display filtered with")."&nbsp;<i>";
  if ($dcategory==translate("All"))
     echo translate("All");
  else
     echo aff_langue(stripslashes($dcategory));
  echo "</i>&nbsp;&nbsp;".translate("sorted by")."&nbsp;";

  // Shiney SQL Injection 11/2011
  $sortby2="";
  if ($sortby == 'dfilename') {
    $sortby2 = translate("Name")."";
  }
  if ($sortby == 'dfilesize') {
    $sortby2 = translate("File Size")."";
  }
  if ($sortby == 'dcategory') {
    $sortby2 = translate("Category")."";
  }
  if ($sortby == 'ddate') {
    $sortby2 = translate("Creation Date")."";
  }
  if ($sortby == 'dver') {
    $sortby2 = translate("Version")."";
  }
  if ($sortby == 'dcounter') {
    $sortby2 = translate("Downloads")."";
  }
  // Shiney SQL Injection 11/2011
  if ($sortby2=='') {
     $sortby = 'dfilename';
  }
  echo '&nbsp;'.translate("of").'&nbsp;<i>'.$sortby2.'</i>
  </p>';
 
  echo '<table class="table table-hover" id ="lst_downlo" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">';
  sortlinks($dcategory, $sortby);
  echo '<tbody>';
  if ($dcategory==translate("All")) {
    $sql="SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads";
  } else {
    $sql="SELECT COUNT(*) FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."'";
  }
  $result = sql_query($sql);
  list($total) =  sql_fetch_row($result);
  if ($total>$perpage) {
    $pages=ceil($total/$perpage);
    if ($page > $pages) { $page = $pages; }
    if (!$page) { $page=1; }
    $offset=($page-1)*$perpage;
  } else {
    $offset=0;
    $pages=1;
    $page=1;
  }
  settype($offset, "integer");
  settype($perpage, "integer");
  if ($dcategory==translate("All")) {
    $sql="SELECT * FROM ".$NPDS_Prefix."downloads ORDER BY $sortby $sortorder LIMIT $offset,$perpage";
  } else {
    $sql="SELECT * FROM ".$NPDS_Prefix."downloads WHERE dcategory='".addslashes($dcategory)."' ORDER BY $sortby $sortorder LIMIT $offset,$perpage";
  }

   $result = sql_query($sql);
   while(list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcat, $ddescription, $dperm) = sql_fetch_row($result)) {
      $Fichier = new File($durl);
      $objZF    =    new FileManagement;// essai class

      
      $okfile=autorisation($dperm);
      echo '
         <tr>
            <td class="text-xs-center">';
      if ($okfile==true) {
         echo popuploader($did, $ddescription, $dcounter, $dfilename,true);
      } else {
         echo popuploader($did, $ddescription, $dcounter, $dfilename,false);
         echo '<span class="text-warning">'.translate("Private").'</span>';
      }
      echo"</td><td class=\"text-xs-center\"><img src=\"".$Fichier->Affiche_Extention()."\" alt=\"".$Fichier->Affiche_Extention()."\" border=\"0\" /></td>
           <td>";
      if ($okfile==true) {
         echo "<a href=\"download.php?op=mydown&amp;did=$did\" target=\"_blank\">$dfilename</a>";
      } else {
         echo '...';
      }
      echo '</td>
            <td>';
            if ($dfilesize!=0) {
               echo $Fichier->Pretty_Size($dfilesize);
                    echo $objZF->file_size_auto($durl, 2);

            } else {
               echo $Fichier->Affiche_Size();
                    echo $objZF->file_size_auto($durl, 2);

            }
            echo '</td>
            <td>'.aff_langue(stripslashes($dcat)).'</td>
            <td>'.convertdate($ddate).'</td>
            <td class="text-xs-center">'.$dver.'</td>
            <td class="text-xs-center">'.wrh($dcounter).'</td>
            <td>';
      if (($okfile==true) and $user) {
         echo"<a href=\"download.php?op=broken&amp;did=$did\" title=\"".translate("Report Broken Link")."\"><i class=\"fa fa-lg fa-chain-broken\"></i></a>";
      }
      echo '
            </td>
         </tr>';
   }
   echo '
      </tbody>
   </table>';

$dcategory = StripSlashes($dcategory);
   echo '<ul class="pagination pagination-sm">';
   if ($pages > 1) {
      $pcnt=1;
   if ($page > 1) {
      echo '
      <li class="page-item">
      <a class="page-link" href="download.php?dcategory='.$dcategory.'&amp;sortby='.$sortby.'&amp;sortorder='.$sortorder.'&amp;page='.($page-1).'" aria-label="Previous" title="'.translate("Previous Page").'">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>';
      }
   while($pcnt < $page) {
      echo "<li class=\"page-item\"><a class=\"page-link\" href=\"download.php?dcategory=$dcategory&amp;sortby=$sortby&amp;sortorder=$sortorder&amp;page=$pcnt\">$pcnt</a></li>";
        $pcnt++;
      }
   echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
      $pcnt++;
   while($pcnt <= $pages) {
      echo "<li class=\"page-item\"><a class=\"page-link\" href=\"download.php?dcategory=$dcategory&amp;sortby=$sortby&amp;sortorder=$sortorder&amp;page=$pcnt\">$pcnt</a></li>";
      $pcnt++;
      }
   if ($page < $pages) {
      echo "<li class=\"page-item\">
      <a class=\"page-link\" href=\"download.php?dcategory=$dcategory&amp;sortby=$sortby&amp;sortorder=$sortorder&amp;page=".($page+1)."\" aria-label=\"Next\" title=\"".translate("Next Page")."\">
         <span aria-hidden=\"true\">&raquo;</span>
         <span class=\"sr-only\">Next</span>
      </a>
      </li>\n";
      }
   }
   echo '</ul>';
}

function main() {
  global $dcategory, $sortby, $sortorder, $sitename;
  $dcategory  = removeHack(stripslashes(htmlspecialchars(urldecode($dcategory),ENT_QUOTES,cur_charset))); // electrobug
  $dcategory=str_replace("&#039;","\'",$dcategory);
  $sortby  = removeHack(stripslashes(htmlspecialchars(urldecode($sortby),ENT_QUOTES,cur_charset))); // electrobug

  include("header.php");
  echo '<h3>'.translate("Download Section").'</h3>';
  tlist();
  if ($dcategory!=translate("No category")) {
     listdownloads($dcategory, $sortby, $sortorder);
  }
  if (file_exists("static/download.ban.txt")) {
   include("static/download.ban.txt");}
   include("footer.php");
}

function transferfile($did) {
  global $NPDS_Prefix;
  
  settype($did, 'integer');
  $result = sql_query("SELECT dcounter, durl, perms FROM ".$NPDS_Prefix."downloads WHERE did='$did'");
  list($dcounter, $durl, $dperm) = sql_fetch_row($result);
  if (!$durl) {
     include("header.php");
     echo "<p class=\"lead text-xs-center\">$durl : ".translate("There is no such file...")."</p>\n";
     include("footer.php");
  } else {
     if (autorisation($dperm)) {
        $dcounter++;
        sql_query("UPDATE ".$NPDS_Prefix."downloads SET dcounter='$dcounter' WHERE did='$did'");
        header("location: ".str_replace(basename($durl),rawurlencode(basename($durl)), $durl));
     } else {
        Header("Location: download.php");
     }
  }
}

function broken($did) {
  global $user, $cookie;

  settype($did, 'integer');
  if ($user) {
     if ($did) {
        global $notify_email, $notify_message, $notify_from;
        settype ($did, "integer");
        $message=translate("downloads")." ID : $did\n\n".translate("Submitter")." $cookie[1] / IP : ".getip();
        send_email($notify_email, translate("Report Broken Link"), $message, $notify_from , false, "text");
        include("header.php");
        echo "<br /><p class=\"lead text-info text-xs-center\">".translate("For security reasons your user name and IP address will also be temporarily recorded.");
        echo "<br /><br />".translate("Thanks for this information. We'll look into your request shortly.")."</p>";
        include("footer.php");
     } else {
        Header("Location: download.php");
     }
  } else {
    Header("Location: download.php");
  }
}

settype($op,'string');
switch ($op) {
  case "main":
       main();
       break;
  case "mydown":
       transferfile($did);
       break;
  case "geninfo":
       geninfo($did);
       break;
  case "broken":
       broken($did);
       break;
  default:
       main();
       break;
}
?>