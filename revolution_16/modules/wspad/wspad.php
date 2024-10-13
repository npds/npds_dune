<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Collab WS-Pad 1.5 by Developpeur and Jpb                             */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* pour définir le PAD courant via l'URL                                  */
/* member='' ou '1' => PAD commun à tous les membres si $pad_membre=true  */
/* member='2 . 126' => PAD du groupe (si le membre appartient au groupe)  */
/* member='-1'      => PAD des admins (si un admin est connecté)          */
/**************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();

global $title, $language, $NPDS_Prefix, $user, $admin, $nuke_url;
// For More security

if (file_exists("modules/$ModPath/pages.php"))
   include ("modules/$ModPath/pages.php");

include_once("modules/$ModPath/lang/$language.php");
include_once("modules/$ModPath/config.php");

// limite l'utilisation aux membres et admin
settype($member, 'integer');
if ($user or $admin) {
   $tab_groupe=valid_group($user);
   if (groupe_autorisation($member,$tab_groupe)) {
      $groupe=$member;
      $auteur=$cookie[1];
   } else {
      if ($pad_membre) {
         $groupe=1;
         $auteur=$cookie[1];
      } elseif ($admin) {
         $groupe=-127;
         $auteur=$aid;
      }
      else
         header("location: index.php");
   }
}
else
   header("location: index.php");

$surlignage=$couleur[hexfromchr($auteur)];

// Paramètres utilisé par le script
$ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=$ModStart";

function Liste_Page() {
   global $NPDS_Prefix, $ModPath, $ModStart, $ThisFile, $gmt, $auteur, $groupe, $couleur;
   echo '
   <script type="text/javascript">
   //<![CDATA[
   function confirm_deletedoc(page, gp) {
      var xhr_object = null;
      if (window.XMLHttpRequest) // FF
         xhr_object = new XMLHttpRequest();
      else if(window.ActiveXObject) // IE
         xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
      if (confirm("'.wspad_trans("Vous allez supprimer le document").' : "+page)) {
         xhr_object.open("GET", location.href="modules.php?ModPath='.$ModPath.'&ModStart='.$ModStart.'&op=suppdoc&page="+page+"&member="+gp, false);
      }
   }
   //]]>
   </script>';
   $aff='
   <h3 class="mb-3"><a class="arrow-toggle text-primary" id="show_cre_page" data-bs-toggle="collapse" data-bs-target="#cre_page" title="'.wspad_trans("Déplier la liste").'"><i id="i_cre_page" class="toggle-icon fa fa-caret-down fa-lg" ></i></a>&nbsp;'.wspad_trans("Créer un document").'</h3>
   <div id="cre_page" class="collapse" style ="padding-left:10px;">
      <form action="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;member='.$groupe.'" method="post" name="wspadformfic">
         <div class="mb-3 row">
            <label class="col-form-label col-sm-4" for="page">'.wspad_trans("Nom du document").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="text" id="page" name="page" maxlength="255" required="required" />
               <span class="help-block small">'.wspad_trans("Caractères autorisés : a-z, A-Z, 0-9, -_.").'</span>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-sm-8 ms-sm-auto">
               <input class="btn btn-primary" type="submit" name="creer" value="'.wspad_trans("Créer").'" />
               <input type="hidden" name="op" value="creer" />
            </div>
         </div>
      </form>
   </div>';
   echo $aff;

   $aff='
   <h3 class="mb-3"><a class="arrow-toggle text-primary" id="show_paddoc" data-bs-toggle="collapse" data-bs-target="#lst_paddoc" title="'.wspad_trans("Déplier la liste").'"><i id="i_lst_paddoc" class="toggle-icon fa fa-caret-down fa-lg" ></i></a>&nbsp;';
   $nb_pages=sql_num_rows(sql_query("SELECT COUNT(page) FROM ".$NPDS_Prefix."wspad WHERE member='$groupe' GROUP BY page"));
   if ($groupe>0) {
      $gp=sql_fetch_assoc(sql_query("SELECT groupe_name FROM ".$NPDS_Prefix."groupes WHERE groupe_id='$groupe'"));
      $aff.='<span class="badge bg-secondary me-2">'.$nb_pages.'</span>'.wspad_trans("Document(s) et révision(s) disponible(s) pour le groupe").' <span class="text-body-secondary">'.aff_langue($gp['groupe_name'])." [$groupe]</span></h3>";
   } else
      $aff.='<span class="badge bg-secondary me-2">'.$nb_pages.'</span>'.wspad_trans("Document(s) et révision(s) disponible(s) pour les administrateurs").'</h3>';
   $aff.='<div id="lst_paddoc" class="collapse" style =" padding-left:10px;">';
   if ($nb_pages>0) {
      $ibid=0; $pgibid=0;
      $result=sql_query("SELECT DISTINCT page FROM ".$NPDS_Prefix."wspad WHERE member='$groupe' ORDER BY page ASC");
      while (list($page)=sql_fetch_row($result)) {

         // Supression des verrous de mon groupe
         clearstatcache();
         $refresh=15;
         $filename="modules/$ModPath/locks/$page-vgp-$groupe.txt";
         if (file_exists($filename)) {
            if ((time()-$refresh)>filemtime($filename)) {
               sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE page='$page' AND member='$groupe'");
               @unlink($filename);
               $verrou='';
            }
         }
         // Supression des verrous de mon groupe

         $pgibid=$pgibid+1;

         $aff.='
         <div class="modal fade" id="renomeModal_'.$page.'" tabindex="-1" role="dialog" aria-labelledby="'.$page.'" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">'.$page.'</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <form id="renameForm" method="post" name="wspadformfic">
                        <div class="mb-3 row">
                           <label class="col-form-label col-12" for="newpage">Nouveau nom</label>
                           <div class="col-12">
                              <input type="text" class="form-control" id="newpage" name="newpage" />
                              <span class="help-block" >'.wspad_trans("Caractères autorisés : a-z, A-Z, 0-9, -_.").'</span>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <div class="col-sm-9 ms-sm-auto">
                              <input type="hidden" name="page" value="'.$page.'" />
                              <input type="hidden" name="op" value="renomer" />
                              <button type="submit" class="btn btn-primary" name="creer">'.wspad_trans("Renommer").'</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.wspad_trans("Abandonner").'</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <hr />
         <h4><a class="arrow-toggle text-primary" id="show_lst_page_'.$pgibid.'" data-bs-toggle="collapse" data-bs-target="#lst_page_'.$pgibid.'" title="'.wspad_trans("Déplier la liste").'"><i id="i_lst_page_'.$pgibid.'" class="fa fa-caret-down fa-lg" ></i></a>&nbsp;'.$page.'
            <span class="float-end">
               <a class="me-3" href="#" data-bs-toggle="modal" data-bs-target="#renomeModal_'.$page.'" ><i class="bi bi-pencil-square" title="'.wspad_trans("Renommer le document et toutes ses révisions").'" data-bs-toggle="tooltip"></i></a>
               <a class="text-danger" href="javascript:" onclick="confirm_deletedoc(\''.$page.'\',\''.$groupe.'\');" title="'.wspad_trans("Supprimer le document et toutes ses révisions").'" data-bs-toggle="tooltip" data-bs-custom-class="n-danger-tooltip"><i class="bi bi-trash2-fill"></i></a>
            </span>
         </h4>
         <div id="lst_page_'.$pgibid.'" class="collapse" style ="padding-left:10px;">';
         $result2=sql_query("SELECT modtime, editedby, ranq, verrou FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$groupe' ORDER BY ranq ASC");
         
         $aff.='
         <table class=" table-sm" data-toggle="table" data-striped="true" data-mobile-responsive="true" >
            <thead>
               <tr>
                  <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">'.wspad_trans("Rev.").'</th>
                  <th class="n-t-col-xs-4" data-sortable="true" data-halign="center">'.wspad_trans("Auteur").'</th>
                  <th data-sortable="true" data-halign="center" data-align="right">'.wspad_trans("Date").'</th>';
         $act=0;
         while (list($modtime,$editedby,$ranq,$verrou)=sql_fetch_row($result2)) {
            if ($act==0) {
               if (($auteur==$verrou) or ($verrou=='')) {
                  $aff.='
                  <th data-halign="center" data-align="right">'.wspad_trans("Actions").'</th>';
//                  $divid=uniqid(mt_rand()); //usefull ???
                  $aff.='
               </tr>
            </thead>
            <tbody>';
               }

               else {
                  $aff.='
                  <th>'.wspad_trans("Actions").'</th>
               </tr>
            </thead>
            <tbody>';
               }

               $act=1;
            }
            if ($ranq>=100) $ibid='';
            elseif ($ranq<100 and $ranq>=10) $ibid='0';
            else $ibid='00';

            $aff.='
               <tr>
                  <td>'.$ibid.$ranq.'</td>
                  <td><div class="me-1" style="float: left; margin-top: 0.5rem; width: 1.5rem; height: 1.5rem; border-radius:50%; background-color: '.$couleur[hexfromchr($editedby)].';"></div>'.userpopover($editedby,'40',2).'&nbsp;'.$editedby.'</td>
                  <td class="small">'.formatTimes($modtime, IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).'</td>';
            // voir la révision du ranq x
            $PopUp=JavaPopUp("modules.php?ModPath=$ModPath&amp;ModStart=preview&amp;pad=".encrypt($page."#wspad#".$groupe."#wspad#".$ranq),"NPDS_wspad",500,400);
            $aff.='
                  <td>
                     <a class="me-2 fs-5" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.wspad_trans("Prévisualiser").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-eye"></i></a>';
            if (($auteur==$verrou) or ($verrou=='')) {
               // recharger la révision du ranq x
               $aff.='
                     <a class="ms-2 fs-5" href="'.$ThisFile.'&amp;op=relo&amp;page='.urlencode($page).'&amp;member='.$groupe.'&amp;ranq='.$ranq.'" title="'.wspad_trans("Choisir").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-hand-index-thumb"></i></a>';
               // supprimer la révision du ranq x
               $aff.='
                     <a class="ms-2 fs-5 text-danger" href="'.$ThisFile.'&amp;op=supp&amp;page='.urlencode($page).'&amp;member='.$groupe.'&amp;ranq='.$ranq.'" title="'.wspad_trans("Supprimer la révision").'" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="n-danger-tooltip"><i class="bi bi-trash2-fill"></i></a>';
               // exporter la révision du ranq x
               $PopUp=JavaPopUp("modules.php?ModPath=$ModPath&amp;ModStart=export&amp;type=doc&amp;pad=".encrypt($page."#wspad#".$groupe."#wspad#".$ranq),"NPDS_wspad",5,5);
               $aff.='
                     <a class="ms-2 fs-5" href="javascript:void(0);" onclick="window.open('.$PopUp.');" title="'.wspad_trans("Exporter .doc").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-filetype-doc"></i></a>';
               // exporter en article 
               $aff.='
                     <a class="ms-2 fs-5" href="'.$ThisFile.'&amp;op=conv_new&amp;page='.urlencode($page).'&amp;member='.$groupe.'&amp;ranq='.$ranq.'" title="'.wspad_trans("Transformer en New").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-newspaper"></i></a>
                  </td>';
            } else
               $aff.= '<i class="text-danger fs-5 me-2 bi bi-lock-fill"></i>'.wspad_trans("Verrouillé par : ").userpopover($verrou,'40',2).'</td>';
            $aff.='
               </tr>';
         }
         $aff.='
            </tbody>
         </table>';
         $aff.='
         </div>';
      }
   }
   echo $aff.'
   </div>';
}

function Page($page, $ranq) {
   global $NPDS_Prefix, $ModPath, $ModStart, $gmt, $auteur, $groupe, $mess;
   $tmp= "
   <script type='text/javascript'>
      //<![CDATA[
         // timerID=10 secondes (verrou) : timerTTL=20 minutes (force la deconnexion)
         var timerID = null;
         var timerTTL = null;
         function TimerInit() {
            timerID = setTimeout('TimerAct()',10000);
            timerTTL= setTimeout('TimerDes()',1200000);
         }
         function TimerAct() {
            clearTimeout(timerID);
            ws_verrou('$auteur', '$page', '$groupe');
            TimerInit();
         }
         function TimerDes() {
            if (timerID != 0) {
               bootbox.alert('".wspad_trans("note : Enregistrer votre travail")."', function() {});
            }
            clearTimeout(timerID);
            timerID = 0;
            clearTimeout(timerTTL);
            timerTTL = 0;
         }
         function ws_verrou(xuser, xpage, xgroupe) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
               xmlhttp=new XMLHttpRequest();
            } else {
               xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
            }
            var url='modules/$ModPath/ws_verrou.php?verrou_user='+xuser+'&verrou_page='+xpage+'&verrou_groupe='+xgroupe+'&random='+Math.random();
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
            document.getElementById('verrous').src='modules/$ModPath/images/ajax_waiting.gif';
            document.getElementById('mess').innerHTML='';
         }
         document.getElementsByTagName('body')[0].setAttribute('onload','TimerInit();');
      //]]>
   </script>";

   // Analyse des verrous
   $filename="modules/$ModPath/locks/$page-vgp-$groupe.txt";
   $refresh=15;
   clearstatcache();
   if (file_exists($filename)) {
      if (filemtime($filename) > (time()-$refresh)) {
         // propriétaire de ce verrou ?
         $cont=file($filename);
         if ($cont[0]==$auteur) {
            $edition=true;
            echo $tmp;
         } else
            $edition=false;
      } else {
         // pose le verrou
         $fp=fopen($filename,"w");
         fwrite($fp,$auteur);
         fclose($fp);
         sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
         sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='$auteur' WHERE page='$page' AND member='$groupe'");
         $edition=true;
         echo $tmp;
      }
   } else {
      // pose le verrou
      $fp=fopen($filename,"w");
      fwrite($fp,$auteur);
      fclose($fp);
      sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
      sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='$auteur' WHERE page='$page' AND member='$groupe'");
      $edition=true;
      echo $tmp;
   }
   // Analyse des verrous

   $row=sql_fetch_assoc(sql_query("SELECT content, modtime, editedby, ranq FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'"));
   if (!$edition)
      $mess=wspad_trans("Mode lecture seulement");
   if (!is_array($row)) {
      $row['ranq']=1;
      $row['editedby']=$auteur;
      $row['modtime']=time();
      $row['content']='';
   }
   else
      $row['ranq']+=1;
   global $surlignage;
   echo '
   <hr /><h3>'.wspad_trans("Document : ").'</h3><h4>'.$page.'<span class="text-body-secondary">&nbsp;[ '.wspad_trans("révision").' : '.$row['ranq'].' - '.$row['editedby'].' / '.formatTimes($row['modtime'], IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT).' ] </span> <span class="float-end"><img src="modules/'.$ModPath.'/images/ajax_waiting.gif" id="verrous" title="wspad locks" /></span></h4>
   <div id="" class="alert alert-success" role="alert">
      <div id="mess">'.$mess.'</div>
   </div>
   <form action="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;member='.$groupe.'" method="post" name="wspadformcont">
      <div class="mb-3">
         <textarea class="tin form-control" rows="30" name="content" ><div class="mceEditable">'.$row['content'].'</div></textarea>
      </div>';
   echo aff_editeur('content', '');
   if ($edition)
      echo '
      <div class="mb-3">
         <input class="btn btn-primary" type="submit" name="sauve" value="'.wspad_trans("Sauvegarder").'" />
         <a class="btn btn-secondary" href="modules.php?ModPath='.$ModPath.'&amp;ModStart='.$ModStart.'&amp;member='.$groupe.'" >'.wspad_trans("Abandonner").'</a>
         <input type="hidden" name="page" value="'.$page.'" />
         <input type="hidden" name="op" value="sauve" />
      </div>';
   echo '
   </form>';
}

settype($op,'string');
settype($page, 'string');
// Filtre les caractères interdits dans les noms de pages
$page=preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i','_', removeHack(stripslashes(urldecode($page))));
settype($ranq, 'integer');
settype($groupe, 'integer');

switch($op) {
  case "sauve":
     $content=removeHack(stripslashes(FixQuotes(dataimagetofileurl($content,'modules/upload/upload/ws'))));
     $auteur=removeHack(stripslashes(FixQuotes($auteur)));
     $row=sql_fetch_assoc(sql_query("SELECT MAX(ranq) AS ranq FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$groupe'"));
     $result = sql_query("INSERT INTO ".$NPDS_Prefix."wspad VALUES ('0', '$page', '$content', '".time()."', '$auteur', '".($row['ranq']+1)."', '$groupe','')");
     sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
     $mess=wspad_trans("révision")." ".($row['ranq']+1)." ".wspad_trans("sauvegardée");
  break;
  case "supp":
     $auteur=removeHack(stripslashes(FixQuotes($auteur)));
     $result = sql_query("DELETE FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'");
     sql_query("UPDATE ".$NPDS_Prefix."wspad SET verrou='' WHERE verrou='$auteur'");
  break;
  case "suppdoc":
     settype($member, 'integer');
     $result = sql_query("DELETE FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$member'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
  break;
  case "renomer":
     // Filtre les caractères interdits dans les noms de pages
     $newpage=preg_replace('#[^a-zA-Z0-9\\s\\_\\.\\-]#i','_', removeHack(stripslashes(urldecode($newpage))));
     settype($member, 'integer');
     $result = sql_query("UPDATE ".$NPDS_Prefix."wspad SET page='$newpage', verrou='' WHERE page='$page' AND member='$member'");
     @unlink("modules/$ModPath/locks/$page-vgp-$groupe.txt");
  break;
  case "conv_new":
     $row = sql_fetch_assoc(sql_query("SELECT content FROM ".$NPDS_Prefix."wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'"));
     $date_debval=date("Y-d-m H:i:s",time());
     $deb_year=substr($date_debval,0,4);
     $date_finval=($deb_year+99)."-01-01 00:00:00";
     $result = sql_query("INSERT INTO ".$NPDS_Prefix."queue VALUES (NULL, $cookie[0], '$auteur', '$page', '".FixQuotes($row['content'])."', '', now(), '','$date_debval','$date_finval','0')");
  break;
}

// For IE ----------------------
header("X-UA-Compatible: IE=8");
// For IE ----------------------
include ('header.php');
// Head banner de présentation
if (file_exists("modules/$ModPath/html/head.html")) {
   $Xcontent=join('',file("modules/$ModPath/html/head.html"));
   $Xcontent=meta_lang(aff_langue($Xcontent));
   echo $Xcontent;
}

switch($op) {
   case 'sauve':
      Liste_Page();
      Page($page, ($row['ranq']+1));
   break;
   case 'creer':
      Liste_Page();
      Page($page, 1);
   break;
   case 'relo':
     Liste_Page();
     Page($page, $ranq);
   break;
   default :
      Liste_Page();
   break;
}

// Foot banner de présentation
if (file_exists("modules/$ModPath/html/foot.html")) {
   $Xcontent=join("",file("modules/$ModPath/html/foot.html"));
   $Xcontent.='<p class="text-end">NPDS WsPad '.$version.' by Dev&nbsp;&&nbsp;Jpb&nbsp;</p>';
   $Xcontent=meta_lang(aff_langue($Xcontent));
   echo $Xcontent;
}
include ('footer.php');
?>