<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2009 - Tribal-Dolphin           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) Access_Error();
$f_meta_nom ='session_log';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $ModPath, $ModStart;
$old_language=$language;
include_once("modules/upload/upload.conf.php");

include('modules/geoloc/geoloc_locip.php');

if ($DOCUMENTROOT=='') {
   global $DOCUMENT_ROOT;
   if ($DOCUMENT_ROOT)
      $DOCUMENTROOT=$DOCUMENT_ROOT;
   else
      $DOCUMENTROOT=$_SERVER['DOCUMENT_ROOT'];
}
$FileSecure = $DOCUMENTROOT.$racine.'/slogs/security.log';
$FileUpload = $DOCUMENTROOT.$rep_log;
$RepTempFil = $DOCUMENT_ROOT.$rep_cache;

$language=$old_language;
include ("modules/$ModPath/lang/session-log-$language.php");
$ThisFile="admin.php?op=Extend-Admin-SubModule&amp;ModPath=".$ModPath."&amp;ModStart=".$ModStart;
$f_titre = SessionLog_translate("Gestion des Logs");
settype($subop,'string');

function action_log($ThisFile,$logtype) {
   echo '<p align="center"><br />
   <a class="btn btn-danger btn-sm mt-2" href="'.$ThisFile.'&amp;subop=vidlog&amp;log='.$logtype.'" >'.SessionLog_translate("Vider le fichier").'</a>
   <a class="btn btn-primary btn-sm mt-2" href="'.$ThisFile.'&amp;subop=mailog&amp;log='.$logtype.'">'.SessionLog_translate("Recevoir le fichier par mail").'</a>
   <a class="btn btn-danger btn-sm mt-2" href="'.$ThisFile.'&amp;subop=vidtemp">'.SessionLog_translate("Effacer les fichiers temporaires").'</a>
   </p>';
}
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $cl_a_ses=''; if ($subop=="session") $cl_a_ses='active';
   $cl_a_sec=''; if ($subop=="security") $cl_a_sec='active';
   
echo '
<hr />
<ul class="nav nav-tabs">
   <li class="nav-item"><a href="'.$ThisFile.'&subop=session" class="nav-link '.$cl_a_ses.'">'.SessionLog_translate("Liste des Sessions").'</a></li>
   <li class="nav-item"><a href="'.$ThisFile.'&subop=security" class="nav-link '.$cl_a_sec.'">'.SessionLog_translate("Liste des Logs").'</a></li>
</ul>';

   if ($FileUpload!=$FileSecure) {
      echo " | <a href=\"".$ThisFile."&subop=upload\" class=\"box\">".SessionLog_translate("Liste des Logs")." : ".SessionLog_translate("TELECHARGEMENT")."</a>";
   }

   // Voir les sessions
   if ($subop=='session') {
      echo '
      <br />
      <h3>'.SessionLog_translate("Liste des Sessions").' <code>TABLE session</code></h3>
      <table id="tad_ses" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th class="n-t-col-xs-2" data-sortable="true">'.SessionLog_translate("Nom").'</th>
               <th class="n-t-col-xs-2" data-sortable="true">@ IP</th>
               <th data-sortable="true">'.SessionLog_translate("@ IP résolue").'</th>
               <th data-sortable="true">URI</th>
               <th class="n-t-col-xs-2" data-sortable="true">'.SessionLog_translate("Agent").'</th>
            </tr>
         </thead>
         <tbody>';
      $result=sql_query("SELECT username, host_addr, guest, uri, agent FROM ".$NPDS_Prefix."session");
      while (list($username, $host_addr, $guest, $uri, $agent)=sql_fetch_row($result)) {
         if ($username==$host_addr) {global $anonymous; $username=$anonymous;}
         if (preg_match('#(crawl|bot|spider|yahoo)#',strtolower($agent))) $agent="Bot"; else $agent="Browser";
         echo '
            <tr>
               <td class="small">'.$username.'</td>
               <td class="small">'.$host_addr.'</td>
               <td class="small">'.gethostbyaddr($host_addr).'</td>
               <td class="small">'.$uri.'</td>
               <td class="small">'.$agent.'</td>
           </tr>';
      }
      echo '
         </tbody>
      </table>';
   }

   // Détails @IP
   if ($subop=='info') {
      echo '
      <br />
      <h3>'.SessionLog_translate("Informations sur l'IP").'</h3>';
      $hostname = gethostbyaddr($theip);
      if ($theip != $hostname) {
         $domfai = explode('.',$hostname);
         $prov = $domfai[count($domfai)-2].'.'.$domfai[count($domfai)-1];
         if ($prov == 'co.jp' or $prov == 'co.uk' )
            $provider = $domfai[sizeof($domfai)-3].'.'.$prov;
         else
            $provider = $prov;
      }
      else
         $hostname = $theip;
      echo '
      <div class="card card-body">
         <div class="row">
            <div class="col mb-3">
              <span class="text-muted">'.SessionLog_translate("@ IP").'</span> : <span>'.$theip.'</span><br />
              <span class="text-muted">'.SessionLog_translate("@ IP résolue").'</span> : <span>'.$hostname.'</span><br />
              <span class="text-muted">'.SessionLog_translate("Fournisseur").'</span> : <span>'.$provider.'</span><br />
            </div>';
      echo localiser_ip($iptoshow=$theip);
      echo '
         </div>
      </div>';
      $subop='security';
   }

   // Vider les Logs
   if ($subop=='vidlog') {
      if ($log=='security') {
         if (file_exists($FileSecure)) {
            @fopen($FileSecure, "w");
            @fclose($FileSecure);
         }
      }
      if ($log=='upload') {
         if (file_exists($FileUpload)) {
            @fopen($FileUpload, "w");
            @fclose($FileUpload);
         }
      }
   }

   // Email du contenu des Logs
   if ($subop=='mailog') {
      if ($log=="security") {
         if (file_exists($FileSecure)) {
            $Mylog=$FileSecure;
         }
      }
      if ($log=='upload') {
         if (file_exists($FileUpload)) {
            $Mylog=$FileUpload;
         }
      }
      $subject = SessionLog_translate("Fichier de Log de")." ".$sitename;
      send_email($adminmail, $subject, $Mylog, '', true, 'mixed');
   }

   // Vider le répertoire temporaire
   if ($subop=='vidtemp') {
      if (is_dir($RepTempFil)) {
         $dh = opendir($RepTempFil);
         $i = 0;
         while(false!==($filename = readdir($dh))) {
            if ($filename === '.' OR $filename === '..' OR $filename === 'index.html') continue;
            @unlink($RepTempFil.$filename);
         }
      }
   }

   // Voir le contenu du fichier security.log
   if ($subop=='security') {
      $UpLog='';
      $SecLog='';
      if (file_exists($FileSecure)) {
         if (filesize($FileSecure) != 0) {
            $fd = fopen($FileSecure, "r");
            while (!feof ($fd)) {
               $buffer = fgets($fd, 4096);
               if (strlen($buffer)>10) {
                  if (stristr($buffer,'Upload')) {
                     $UpLog.='<tr><td style="font-size:10px;">'.$buffer.'</td></tr>'."\n";
                  } else {
                    $ip=substr(strrchr($buffer,"=>"),2);
                    $SecLog.='
                    <tr>
                    <td class="small">'.$buffer.'</td>
                    <td><a href="'.$ThisFile.'&amp;subop=info&amp;theip='.$ip.'" >'.SessionLog_translate("Infos").'</a></td>
                    </tr>'."\n";
                  }
               }
            }
            fclose($fd);
         }
      }
      echo '
      <hr />
      <h3 class="mb-3"><a class="btn" data-toggle="collapse" href="#tog_tad_slog" aria-expanded="false" aria-controls="tog_tad_slog"><i class="fa fa-bars fa-lg align-top"></i></a>'.SessionLog_translate("Liste des Logs").' '.SessionLog_translate("SECURITE").' : <code>security.log</code></h3>
      <div id="tog_tad_slog" class="collapse">
         <table id="tad_slog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true">
            <thead>
               <tr>
                  <th class="n-t-col-xs-10" data-sortable="true">Logs</th>
                  <th data-align="center" class="n-t-col-xs-2">Fonctions</th>
               </tr>
            </thead>
            <tbody>';
      echo $SecLog;
      echo '
            </tbody>
         </table>
      </div>
      <h3 class="mb-3"><a class="btn" data-toggle="collapse" href="#tog_tad_tlog" aria-expanded="false" aria-controls="tog_tad_tlog"><i class="fa fa-bars fa-lg align-top"></i></a>'.SessionLog_translate("Liste des Logs").' '.SessionLog_translate("TELECHARGEMENT").' : <code>security.log</code></h3>
      <div id="tog_tad_tlog" class="collapse">
         <table id="tad_tlog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
               <tr>
                  <th>Logs</th>
               </tr>
            </thead>
            <tbody>';
      echo $UpLog;
      echo '
            </tbody>
         </table>
      </div>';
      action_log($ThisFile,"security");
   }

   // Voir le contenu du fichier d'upload si différent de security.log (upload.conf.php)
   if ($subop=="upload") {
      if ($FileUpload!=$FileSecure) {
         if (file_exists($FileUpload)) {
            if (filesize($FileUpload) != 0) {
               $fd = @fopen($FileUpload, "r");
               while (!feof ($fd)) {
                  $buffer = fgets($fd, 4096);
                  $UpLog.='
                  <tr>
                  <td style="font-size:0.65rem;">'.$buffer.'</td></tr>';
               }
               @fclose($fd);
            }
         }
         echo SessionLog_translate("Liste des Logs").' '.SessionLog_translate("TELECHARGEMENT").' : <code class="code">'.$FileUpload.'</code>';
         echo '<table>';
         echo $UpLog;
         echo '</table>';
         action_log($ThisFile,"upload");
      }
   }
adminfoot('','','','');
?>