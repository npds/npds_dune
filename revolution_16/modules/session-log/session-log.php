<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2009 - Tribal-Dolphin           */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include($_SERVER['DOCUMENT_ROOT'].'/admin/die.php');
$f_meta_nom ='session_log';
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit

global $language, $ModPath, $ModStart;
$hlpfile ='manuels/'.$language.'/logs.html';
include('modules/upload/upload.conf.php');
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

include ("modules/$ModPath/lang/session-log-$language.php");
$ThisFile="admin.php?op=Extend-Admin-SubModule&amp;ModPath=".$ModPath."&amp;ModStart=".$ModStart;
$f_titre = SessionLog_translate("Gestion des Logs");
settype($subop,'string');

function action_log($ThisFile,$logtype) {
   global $FileSecure, $FileUpload, $RepTempFil, $rep_cache;
   $whatlog='security';
   if($FileUpload!=$FileSecure) $whatlog='upload';
   $task= '
      <a class="dropdown-item" href="'.$ThisFile.'&amp;subop=mailog&amp;log='.$whatlog.'"><i class="fa fa-at me-1 fa-lg text-primary"></i>'.SessionLog_translate("Recevoir le fichier par mail").'</a>
      <a class="dropdown-item" href="'.$ThisFile.'&amp;subop=vidlog&amp;log='.$whatlog.'"><i class="fa fa-times me-1 fa-lg text-danger"></i>'.SessionLog_translate("Vider le fichier").'<br /><small>'.$FileSecure.'</small></a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="'.$ThisFile.'&amp;subop=vidtemp"><i class="fas fa-trash me-1 fa-lg text-danger"></i>'.SessionLog_translate("Effacer les fichiers temporaires").'<br /><small>'.$rep_cache.'</small></a>';
   return $task;
}

   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   $cl_a_ses = $subop=="session" ? 'active' : '' ;
   $cl_a_sec = $subop=="security" ? 'active' : '' ;
echo '
<hr />
<ul class="nav nav-tabs">
   <li class="nav-item"><a href="'.$ThisFile.'&subop=session" class="nav-link '.$cl_a_ses.'">'.SessionLog_translate("Liste des Sessions").'</a></li>
   <li class="nav-item"><a href="'.$ThisFile.'&subop=security" class="nav-link '.$cl_a_sec.'">'.SessionLog_translate("Liste des Logs").'</a>
   <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench fa-lg"></i></a>
      <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
      '.action_log($ThisFile,'').'
      </div>
   </li>
</ul>';
// Voir les sessions
   if ($subop=='session') {
      echo '
      <br />
      <h3>'.SessionLog_translate("Liste des Sessions").' : <code>TABLE session</code><span class="float-end"><a href="'.$ThisFile.'&amp;subop=videsession" title="'.SessionLog_translate("Vide la table des sessions et interrompt les connexions.").'" data-bs-toggle="tooltip" data-bs-placement="left"><i class=" fas fa-database text-danger"></i></a></span></h3>
      <table id="tad_ses" data-classes="table table-sm table-striped table-borderless" data-toggle="table" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th class="n-t-col-xs-1" data-halign="center" data-align="center" data-sortable="true">'.SessionLog_translate("Nom").'</th>
               <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">@ IP</th>
               <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">'.SessionLog_translate("@ IP résolue").'</th>
               <th data-halign="center" data-sortable="true">URI</th>
               <th class="n-t-col-xs-1" data-halign="center" data-align="center" data-sortable="true">'.SessionLog_translate("Agent").'</th>
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
               <td class="small">'.$host_addr.' <br /><a href="'.$ThisFile.'&amp;subop=banthisip&amp;iptoban='.urlencode($host_addr).'" data-bs-toggle="tooltip" data-bs-placement="right" title="'.SessionLog_translate("Déconnecter et bannir cette adresse IP !").'"><i class="fas fa-ban text-danger"></i></a></td>
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
      settype($provider,'string');
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
         <div class="row align-items-center">
            <div class="col mb-3">
              <span class="text-body-secondary">'.SessionLog_translate("@ IP").'</span> : <span>'.$theip.'</span><br />
              <span class="text-body-secondary">'.SessionLog_translate("@ IP résolue").'</span> : <span>'.$hostname.'</span><br />
              <span class="text-body-secondary">'.SessionLog_translate("Fournisseur").'</span> : <span>'.$provider.'</span><br />
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
            $File_Secure = fopen($FileSecure, "w");
            fclose($File_Secure);
         }
      }
      if ($log=='upload') {
         if (file_exists($FileUpload)) {
            $File_Upload = fopen($FileUpload, "w");
            fclose($File_Upload);
         }
      }
   }

   // Email du contenu des Logs
   if ($subop=='mailog') {
      if ($log=='security')
         if (file_exists($FileSecure))
            $Mylog=$FileSecure;
      if ($log=='upload') 
         if (file_exists($FileUpload))
            $Mylog=$FileUpload;
      $file = [
         'file' => $Mylog,
         'name' => 'security.log',
      ];
      $subject = html_entity_decode(SessionLog_translate("Fichier de Log de"),ENT_COMPAT | ENT_HTML401,'UTF-8').' '.$sitename;
      $message = SessionLog_translate("Fichier de Log de").' '.$sitename."<br /><br />";
      send_email($adminmail, $subject, $message, $adminmail, true, 'mixed', $file);
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
                  if (stristr($buffer,'Upload'))
                     $UpLog.='
               <tr>
                  <td style="font-size:10px;">'.$buffer.'</td>
               </tr>';
                  else {
                    $ip=substr(strrchr($buffer,"=>"),2);
                    $SecLog.='
               <tr>
                  <td class="small">'.$buffer.'</td>
                  <td><a href="'.$ThisFile.'&amp;subop=info&amp;theip='.$ip.'" >'.SessionLog_translate("Infos").'</a></td>
               </tr>';
                  }
               }
            }
            fclose($fd);
         }
      }
      echo '
      <h3 class="my-3"><a data-bs-toggle="collapse" href="#tog_tad_slog" aria-expanded="false" aria-controls="tog_tad_slog"><i class="toggle-icon fa fa-caret-down"></i></a><span class="ms-2">'.SessionLog_translate("Liste des Logs").' '.SessionLog_translate("SECURITE").' : <code>security.log</code></span></h3>
      <div id="tog_tad_slog" class="collapse">
         <table id="tad_slog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true">
            <thead>
               <tr>
                  <th class="n-t-col-xs-10" data-sortable="true">Logs</th>
                  <th data-align="center" class="n-t-col-xs-2">Fonctions</th>
               </tr>
            </thead>
            <tbody>
            '.$SecLog.'
            </tbody>
         </table>
      </div>
      <h3 class="mt-3"><a data-bs-toggle="collapse" href="#tog_tad_tlog" aria-expanded="false" aria-controls="tog_tad_tlog"><i class="toggle-icon fa fa-caret-down"></i></a><span class="ms-2">'.SessionLog_translate("Liste des Logs").' '.SessionLog_translate("TELECHARGEMENT").' : <code>security.log</code></span></h3>
      <div id="tog_tad_tlog" class="collapse">
         <table id="tad_tlog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
               <tr>
                  <th>Logs</th>
               </tr>
            </thead>
            <tbody>
            '.$UpLog.'
            </tbody>
         </table>
      </div>';
      action_log($ThisFile,'security');
   }

   // Voir le contenu du fichier d'upload si différent de security.log (upload.conf.php)
   if ($subop=="upload") {
      $UpLog='';
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
         echo '
         <table>
         '.$UpLog.'
         </table>';
         action_log($ThisFile,"upload");
      }
   }

   // bannir cette IP
   if ($subop=="banthisip") {
      $iptoban = urldecode($iptoban);
      sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE host_addr='$iptoban'");
      L_spambot($iptoban,"ban");
      echo '<div class="alert alert-danger my-3"><strong>'.$iptoban.'</strong> '.SessionLog_translate("cette adresse IP a été déconnectée et bannie !").'</div>';
      redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=".$ModPath."&ModStart=".$ModStart."&subop=session");
   }

   // vider la table des sessions
   if ($subop=="videsession") {
      sql_query("DELETE FROM ".$NPDS_Prefix."session WHERE host_addr >=0");
      echo '<div class="alert alert-danger my-3">'.SessionLog_translate("Table session vidée. Connexions interrompues !").'</div>';
      redirect_url("admin.php?op=Extend-Admin-SubModule&ModPath=".$ModPath."&ModStart=".$ModStart."&subop=session");
   }

adminfoot('','','','');
?>