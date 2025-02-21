<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");
include("language/lang-adm-$language.php");
function Access_Error () {
   include("admin/die.php");
}

function admindroits($aid,$f_meta_nom) {
   global $NPDS_Prefix, $radminsuper;
   $res = sql_query("SELECT fnom, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fdroits1 WHERE a.aid='$aid'");
   $foncts=array();$supers=array();
   while ($data = sql_fetch_row($res)) {
      $foncts[] = $data[0];
      $supers[] = $data[1];
   }
   if ((!in_array('1', $supers)) AND (!in_array($f_meta_nom, $foncts)))
      Access_Error();
   $radminsuper = $supers[0];
}

function adminhead($f_meta_nom, $f_titre, $adminimg) {
   global $admf_ext, $NPDS_Prefix, $f_meta_nom, $ModPath, $adm_img_mod;
   list($furlscript, $ficone)=sql_fetch_row(sql_query("SELECT furlscript, ficone FROM ".$NPDS_Prefix."fonctions WHERE fnom='$f_meta_nom'"));
   if (file_exists($adminimg.$ficone.'.'.$admf_ext))
      $img_adm ='<img src="'.$adminimg.$ficone.'.'.$admf_ext.'" class="me-2" alt="'.$f_titre.'" loading="lazy" />';
   elseif (stristr($_SERVER['QUERY_STRING'],"Extend-Admin-SubModule")||$adm_img_mod==1) {
      $img_adm = (file_exists('modules/'.$ModPath.'/'.$ModPath.'.'.$admf_ext)) ?
         '<img src="modules/'.$ModPath.'/'.$ModPath.'.'.$admf_ext.'" class="me-2" alt="'.$f_titre.'" loading="lazy" />' :
         '';
   }
   else $img_adm ='';
   $entete_adm ='
            <div id="adm_workarea" class="adm_workarea">
               <h2><a '.$furlscript.' >'.$img_adm.$f_titre.'</a></h2>';
   echo $entete_adm;
}

$filemanager=false;
if (file_exists("filemanager.conf"))
   include_once("filemanager.conf");

function login() {
   global $adminimg;
   include ("header.php");
   echo '
   <h1>'.adm_translate("Administration").'</h1>
   <div id ="adm_men">
      <h2 class="mb-3"><i class="fas fa-sign-in-alt fa-lg align-middle me-2"></i>'.adm_translate("Connexion").'</h2>
      <form action="admin.php" method="post" id="adminlogin" name="adminlogin">
         <div class="row g-3">
            <div class="col-sm-6">
               <div class="mb-3 form-floating">
                  <input id="aid" class="form-control" type="text" name="aid" maxlength="20" placeholder="'.adm_translate("Administrateur ID").'" required="required" />
                  <label for="aid">'.adm_translate("Administrateur ID").'</label>
               </div>
               <span class="help-block text-end"><span id="countcar_aid"></span></span>
            </div>
            <div class="col-sm-6">
               <div class="mb-3 form-floating">
                  <input id="pwd" class="form-control" type="password" name="pwd" maxlength="18" placeholder="'.adm_translate("Mot de Passe").'" required="required" />
                  <label for="pwd">'.adm_translate("Mot de Passe").'</label>
               </div>
               <span class="help-block text-end"><span id="countcar_pwd"></span></span>
            </div>
         </div>
         <button class="btn btn-primary btn-lg" type="submit">'.adm_translate("Valider").'</button>
         <input type="hidden" name="op" value="login" />
      </form>
      <script type="text/javascript">
         //<![CDATA[
            document.adminlogin.aid.focus();
            $(document).ready(function() {
               inpandfieldlen("pwd",18);
               inpandfieldlen("aid",20);
            });
         //]]>
      </script>';
      $arg1='
      var formulid =["adminlogin"];
      ';
   adminfoot('fv','',$arg1,'');
}

function GraphicAdmin($hlpfile) {
   global $aid, $admingraphic, $adminimg, $language, $admin, $banners, $filemanager, $Version_Sub, $Version_Num, $httprefmax, $httpref, $short_menu_admin, $admf_ext, $NPDS_Prefix, $adm_ent, $nuke_url, $Default_Theme;
   $bloc_foncts ='';
   $bloc_foncts_A ='';
   $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$aid' LIMIT 1"));

   //==> recupérations des états des fonctions d'ALERTE ou activable et maj (faire une fonction avec cache court dev ..)
   //article à valider
   $newsubs=sql_num_rows(sql_query("SELECT qid FROM ".$NPDS_Prefix."queue"));
   if($newsubs) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$newsubs."',fretour_h='".adm_translate("Articles en attente de validation !")."' WHERE fid='38'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='38'");
   //news auto
   $newauto=sql_num_rows(sql_query("SELECT anid FROM ".$NPDS_Prefix."autonews"));
   if($newauto) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$newauto."',fretour_h='".adm_translate("Articles programmés pour la publication.")."' WHERE fid=37"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0',fretour_h='' WHERE fid=37");
   //etat filemanager
   if ($filemanager) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1' WHERE fid='27'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0' WHERE fid='27'");
//==> récuperation traitement des messages de NPDS
   $QM=sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions WHERE fnom REGEXP'mes_npds_[[:digit:]]'");
   settype($f_mes, 'array');
   while ($SQM=sql_fetch_assoc($QM)) {
      $f_mes[]=$SQM['fretour_h'];
   }
   //==> récuperation
   $messagerie_npds= file_get_contents('https://raw.githubusercontent.com/npds/npds_dune/master/versus.txt');
   $messages_npds = explode("\n", $messagerie_npds);
   array_pop($messages_npds);
   // traitement spécifique car message de version permanent "Versus"
   $versus_info = explode('|', $messages_npds[0]);
   if($versus_info[1] == $Version_Sub and $versus_info[2] == $Version_Num)
      sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1', fretour='', fretour_h='".adm_translate("Version NPDS")." ".$Version_Sub." ".$Version_Num."', furlscript='' WHERE fid='36'");
   else
      sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1', fretour='N', furlscript='data-bs-toggle=\"modal\" data-bs-target=\"#versusModal\"', fretour_h='".adm_translate("Une nouvelle version NPDS est disponible !")."<br />".$versus_info[1]." ".$versus_info[2]."<br />".adm_translate("Cliquez pour télécharger.")."' WHERE fid='36'"); 
   $mess=array_slice($messages_npds, 1);//on enleve le message de version
   //si pas de message on nettoie la table
   if(empty($mess)) {
      sql_query("DELETE FROM ".$NPDS_Prefix."fonctions WHERE fnom REGEXP'mes_npds_[[:digit:]]'");
      sql_query("ALTER TABLE ".$NPDS_Prefix."fonctions AUTO_INCREMENT = (SELECT MAX(fid)+1 FROM ".$NPDS_Prefix."fonctions)");
   }
   else {
      $o=0;
      foreach($mess as $v) {
         $ibid = explode('|',$v);
         $fico = $ibid[0] != 'Note' ? 'message_npds_a' : 'message_npds_i' ;
         $QM=sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions WHERE fnom='mes_npds_".$o."'"));
         if($QM==0)
            sql_query("INSERT INTO ".$NPDS_Prefix."fonctions (fnom,fretour_h,fcategorie,fcategorie_nom,ficone,fetat,finterface,fnom_affich,furlscript) VALUES ('mes_npds_".$o."','".addslashes($ibid[1])."','9','Alerte','".$fico."','1','1','".addslashes($ibid[2])."','data-bs-toggle=\"modal\" data-bs-target=\"#messageModal\"');\n");
         $o++;
      }
   }
  // si message on compare avec la base
   if ($mess) {
      for ($i=0;$i<count($mess);$i++) {
         $ibid = explode('|',$mess[$i]);
         $fico = $ibid[0] != 'Note'? 'message_npds_a' : 'message_npds_i';
         //si on trouve le contenu du fichier dans la requete
         if (in_array($ibid[1],$f_mes,true)) {
            $k=(array_search ($ibid[1], $f_mes));
            unset ($f_mes[$k]);
            $result=sql_query("SELECT fnom_affich FROM ".$NPDS_Prefix."fonctions WHERE fnom='mes_npds_$i'");
            if (sql_num_rows($result)==1) {
               $alertinfo = sql_fetch_assoc($result);
               if ($alertinfo['fnom_affich'] != $ibid[2])
                  sql_query('UPDATE '.$NPDS_Prefix.'fonctions SET fdroits1_descr="", fnom_affich="'.addslashes($ibid[2]).'" WHERE fnom="mes_npds_'.$i.'"');
            }
         }
      }
      if(count ($f_mes)!==0) {
         foreach ( $f_mes as $v ) {
            sql_query('DELETE from '.$NPDS_Prefix.'fonctions where fretour_h="'.$v.'" and fcategorie="9"');
         }
      }
   }
   //<== recuperation traitement des messages de NPDS

   //utilisateur à valider
   $newsuti=sql_num_rows(sql_query("SELECT uid FROM ".$NPDS_Prefix."users_status WHERE uid!='1' AND open='0'"));
   if($newsuti) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$newsuti."',fretour_h='".adm_translate("Utilisateur en attente de validation !")."' WHERE fid='44'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='44'");
   //référants à gérer
   if($httpref == 1) {
   $result=sql_fetch_assoc(sql_query("SELECT COUNT(*) AS total FROM ".$NPDS_Prefix."referer"));
   if ($result['total']>=$httprefmax) sql_query("UPDATE ".$NPDS_Prefix."fonctions set fetat='1', fretour='!!!' WHERE fid='39'");else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0' WHERE fid='39'");
   }
   //critique en attente
   $critsubs= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."reviews_add"));
   if($critsubs) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$critsubs."', fretour_h='".adm_translate("Critique en attente de validation.")."' WHERE fid='35'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='35'");
   //nouveau lien à valider
   $newlink= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."links_newlink"));
   if($newlink) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$newlink."', fretour_h='".adm_translate("Liens à valider.")."' WHERE fid='41'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='41'");
   //lien rompu à valider
   $brokenlink= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest where brokenlink='1'"));
   if($brokenlink) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$brokenlink."', fretour_h='".adm_translate("Liens rompus à valider.")."' WHERE fid='42'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='42'");
   //nouvelle publication
   $newpubli = $Q['radminsuper']==1?
      sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."seccont_tempo")):
      sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."seccont_tempo WHERE author='$aid'"));
   if($newpubli) sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$newpubli."', fretour_h='".adm_translate("Publication(s) en attente de validation")."' WHERE fid='50'"); else sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='50'");
   //utilisateur(s) en attente de groupe
   $directory = "users_private/groupe";
   $iterator = new DirectoryIterator($directory);
   $j=0;
   foreach ($iterator as $fileinfo) {
      if ($fileinfo->isFile() and strpos($fileinfo->getFilename(),'ask4group') !== false)
         $j++;
   }
   if($j>0)
      sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$j."',fretour_h='".adm_translate("Utilisateur en attente de groupe !")."' WHERE fid='46'");
   else
      sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='0' WHERE fid='46'");
   //<== etc...etc recupérations des états des fonctions d'ALERTE et maj

   //==> Pour les modules installés produisant des notifications
   $alert_modules=sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f LEFT JOIN ".$NPDS_Prefix."modules m ON m.mnom = f.fnom WHERE m.minstall=1 AND fcategorie=9"); 
   if($alert_modules) {
      while($am=sql_fetch_array($alert_modules)) {
         include("modules/".$am['fnom']."/admin/adm_alertes.php");
         $nr=count($reqalertes);
         $i=0;
         while($i<$nr){
            $ibid = sql_num_rows(sql_query($reqalertes[$i][0]));
            if($ibid) {
               $fr=$reqalertes[$i][1]!= 1 ? $reqalertes[$i][1] : $ibid;
               sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='1',fretour='".$fr."', fretour_h='".$reqalertes[$i][2]."' WHERE fid=".$am['fid']."");
            } 
            else
               sql_query("UPDATE ".$NPDS_Prefix."fonctions SET fetat='0',fretour='' WHERE fid=".$am['fid']."");
            $i++;
         }
      }
   }
   //<== Pour les modules installés produisant des notifications

   //==> construction des blocs menu : selection de fonctions actives ayant une interface graphique de premier niveau et dont l'administrateur connecté en posséde les droits d'accès
   // on prend tout ce qui a une interface 
   $R = $Q['radminsuper']==1 ?
      sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 AND f.fetat != '0' ORDER BY f.fcategorie, f.fordre") :
      sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f LEFT JOIN ".$NPDS_Prefix."droits d ON f.fdroits1 = d.d_fon_fid LEFT JOIN ".$NPDS_Prefix."authors a ON d.d_aut_aid =a.aid WHERE f.finterface =1 AND fetat!=0 AND d.d_aut_aid='$aid' AND d.d_droits REGEXP'^1' ORDER BY f.fcategorie, f.fordre") ;
   $j=0;

   while($SAQ=sql_fetch_assoc($R)) {
      $cat[]=$SAQ['fcategorie'];
      $cat_n[]=$SAQ['fcategorie_nom'];
      $fid_ar[]=$SAQ['fid'];
      $adm_lecture = explode('|',$SAQ['fdroits1_descr'] ?? '');
      if ($SAQ['fcategorie'] == 6 or ($SAQ['fcategorie'] == 9 and strstr($SAQ['furlscript'],"op=Extend-Admin-SubModule"))) {
         if (file_exists('modules/'.$SAQ['fnom'].'/'.$SAQ['ficone'].'.'.$admf_ext)) 
            $adminico='modules/'.$SAQ['fnom'].'/'.$SAQ['ficone'].'.'.$admf_ext;
         else
            $adminico=$adminimg.'module.'.$admf_ext;
      } else
         $adminico=$adminimg.$SAQ['ficone'].'.'.$admf_ext;
      if ($SAQ['fcategorie'] == 9) {
         if(preg_match('#mes_npds_\d#', $SAQ['fnom'])) {
            if(!in_array($aid, $adm_lecture, true))
               $bloc_foncts_A .='
                  <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="'.$SAQ['fretour_h'].'" data-id="'.$SAQ['fid'].'" data-bs-html="true" '.$SAQ['furlscript'].' >
                     <img class="adm_img" src="'.$adminico.'" alt="icon_message" loading="lazy" />
                     <span class="badge bg-danger ms-1">'.$SAQ['fretour'].'</span>
                  </a>';
         }
         else
            $bloc_foncts_A .='
                  <a class=" btn btn-outline-primary btn-sm me-2 my-1 tooltipbyclass" title="'.$SAQ['fretour_h'].'" data-id="'.$SAQ['fid'].'" data-bs-html="true" '.$SAQ['furlscript'].' >
                     <img class="adm_img" src="'.$adminico.'" alt="icon_message" loading="lazy" />
                     <span class="badge rounded-pill bg-danger ms-1">'.$SAQ['fretour'].'</span>
                  </a>';
         array_pop($cat_n);
      } 
      else {
         $ul_o = '
         <h4 class="text-body-secondary"><a class="tog" id="hide_'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" title="'.adm_translate("Replier la liste").'" style="clear:left;"><i id="i_'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" class="fa fa-caret-up fa-lg text-primary" ></i></a>&nbsp;'.adm_translate($SAQ['fcategorie_nom']).'</h4>
         <ul id="'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" class="list" style="clear:left;">';
         $li_c = '
            <li id="'.$SAQ['fid'].'"  data-bs-toggle="tooltip" data-bs-placement="top" title="';
         $li_c .= $SAQ['fcategorie'] == 6 ? $SAQ['fnom_affich'] : adm_translate($SAQ['fnom_affich']) ;
         // lancement du FileManager 
         $blank=''; 
         if ($SAQ['fnom']=="FileManager") {
            if (file_exists("modules/f-manager/users/".strtolower($aid).".conf.php")) {
                include ("modules/f-manager/users/".strtolower($aid).".conf.php");
                if (!$NPDS_fma)
                   $blank=' target="_blank"';
            }
         }
         $li_c .='"><a class="btn btn-outline-primary" '.$SAQ['furlscript'].$blank.'>';
         if ($admingraphic==1)
            $li_c .='<img class="adm_img" src="'.$adminico.'" alt="icon_'.$SAQ['fnom_affich'].'" loading="lazy" />';
         else
            $li_c .= $SAQ['fcategorie'] == 6 ? $SAQ['fnom_affich'] : adm_translate($SAQ['fnom_affich']) ;
         $li_c .='</a></li>';
         $ul_f='';
         if ($j!==0)
            $ul_f ='
         </ul>
         <script type="text/javascript">
         //<![CDATA[
            $( document ).ready(function() {
               tog(\''.strtolower(substr($cat_n[($j-1)],0,3)).'\',\'show_'.strtolower(substr($cat_n[$j-1],0,3)).'\',\'hide_'.strtolower(substr($cat_n[$j-1],0,3)).'\');
            })
         //]]>
         </script>';

         if ($j==0) 
            $bloc_foncts .= $ul_o.$li_c;
         else
            $bloc_foncts .= ($j>0 and $cat[$j]>$cat[$j-1]) ? $ul_f.$ul_o.$li_c : $li_c ;
      }
      $j++;
   }

   if(isset($cat_n)) {
      $ca=array();
      $ca=array_unique($cat_n);
      $ca=array_pop($ca);
      $bloc_foncts .= '
   </ul>
   <script type="text/javascript">
      //<![CDATA[
       $( document ).ready(function() {
         tog(\''.strtolower(substr($ca,0,3)).'\',\'show_'.strtolower(substr($ca,0,3)).'\',\'hide_'.strtolower(substr($ca,0,3)).'\');
      })
      //]]>
   </script>';
   }

   echo "
                  <script type=\"text/javascript\">
                  //<![CDATA[
                     function openwindow(){
                        window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");
                     }
                     $(function () {
                        $('[data-bs-toggle=\"tooltip\"]').tooltip();
                        $('[data-bs-toggle=\"popover\"]').popover();
                        $('table').on('all.bs.table', function (e, name, args) {
                           $('[data-bs-toggle=\"tooltip\"]').tooltip();
                           $('[data-bs-toggle=\"popover\"]').popover();
                        });
                     });

                     //==>date d'expiration connection admin
                     $(function () {
                        var dae = Cookies.get('adm_exp')*1000,
                            dajs = new Date(dae);
                        $('#adm_connect_status').attr('title', 'Connexion ouverte jusqu\'au : '+dajs.getDate()+'/'+ (dajs.getMonth()+1) +'/'+ dajs.getFullYear() +'/'+ dajs.getHours() +':'+ dajs.getMinutes()+':'+ dajs.getSeconds()+' GMT');
               
                        deCompte= function() {
                           var date1 = new Date(), sec = (dae - date1) / 1000, n = 24 * 3600;
                           if (sec > 0) {
                              j = Math.floor (sec / n);
                              h = Math.floor ((sec - (j * n)) / 3600);
                              mn = Math.floor ((sec - ((j * n + h * 3600))) / 60);
                              sec = Math.floor (sec - ((j * n + h * 3600 + mn * 60)));
                              $('#tempsconnection').text(j +'j '+ h +':'+ mn +':'+sec);
                           }
                           t_deCompte=setTimeout (deCompte, 1000);
                        }
                        deCompte();
                     })
                     //<== date d'expiration connection admin

                     tog = function(lst,sho,hid) {
                        $('#adm_men, #adm_workarea').on('click', 'a.tog', function() {
                           var buttonID = $(this).attr('id');
                           lst_id = $('#'+lst);
                           i_id=$('#i_'+lst);
                           btn_show=$('#'+sho);
                           btn_hide=$('#'+hid);
                           if (buttonID == sho) {
                              lst_id.fadeIn(1300);//show();
                              btn_show.attr('id',hid)
                              btn_show.attr('title','".html_entity_decode(adm_translate("Replier la liste"),ENT_QUOTES|ENT_HTML401,'UTF-8')."');
                              i_id.attr('class','fa fa-caret-up fa-lg text-primary me-1');
                           } else if (buttonID == hid) {
                              lst_id.fadeOut(1300);//hide();
                              btn_hide=$('#'+hid);
                              btn_hide.attr('id',sho);
                              btn_hide.attr('title','".html_entity_decode(adm_translate("Déplier la liste"),ENT_QUOTES|ENT_HTML401,'UTF-8')."');
                              i_id.attr('class','fa fa-caret-down fa-lg text-primary me-1');
                          }
                        });
                     };

                     $(function () {
                       $('#messageModal').on('show.bs.modal', function (event) {
                           var button = $(event.relatedTarget); 
                           var id = button.data('id');
                           $('#messageModalId').val(id);
                           $('#messageModalForm').attr('action', '".$nuke_url."/admin.php?op=alerte_update');
                           $.ajax({
                              url:\"".$nuke_url."/admin.php?op=alerte_api\",
                              method: \"POST\",
                              data:{id:id},
                              dataType:\"JSON\",
                              success:function(data) {
                                 var fnom_affich = JSON.stringify(data['fnom_affich']),
                                     fretour_h = JSON.stringify(data['fretour_h']),
                                     ficone = JSON.stringify(data['ficone']);
                                 $('#messageModalLabel').html(JSON.parse(fretour_h));
                                 $('#messageModalContent').html(JSON.parse(fnom_affich));
                                 $('#messageModalIcon').html('<img src=\"images/admin/'+JSON.parse(ficone)+'.png\" />');
                              }
                           });
                        });
                     });
                  //]]>
                  </script>\n";
   $adm_ent ='';
   $adm_ent .='
                  <h1 class="mb-3">'.adm_translate("Administration").'</h1>
                  <div id ="adm_men" class="mb-4">
                     <div id="adm_header" class="row justify-content-end">
                        <div class="col-6 col-lg-6 men_tit align-self-center">
                           <h2><a href="admin.php">'.adm_translate("Menu").'</a></h2>
                        </div>
                        <div id="adm_men_man" class="col-6 col-lg-6 men_man text-end">
                           <ul class="liste" id="lst_men_top">
                              <li data-bs-toggle="tooltip" title="'.adm_translate("Déconnexion").'" ><a class="btn btn-outline-danger btn-sm" href="admin.php?op=logout" ><i class="fas fa-sign-out-alt fa-2x"></i></a></li>';
   if ($hlpfile)
      $adm_ent .='
                             <li class="ms-2" data-bs-toggle="tooltip" title="'.adm_translate("Manuel en ligne").'"><a class="btn btn-outline-primary btn-sm" href="javascript:openwindow();"><i class="fa fa-question-circle fa-2x"></i></a></li>';
   $adm_ent .='
                           </ul>
                        </div>
                     </div>
                     <div id="adm_men_dial" class="border rounded px-2 py-2" >
                        <div id="adm_men_alert" >
                           <div id="alertes">
                           '.aff_langue($bloc_foncts_A).'
                           </div>
                        </div>
                    </div>
                     <div id ="mes_perm" class="contenair-fluid text-body-secondary" >
                         <span class="car">'.$Version_Sub.' '.$Version_Num.' '.$aid.' </span><span id="tempsconnection" class="car"></span>
                     </div>
                     <div class="modal fade" id="versusModal" tabindex="-1" aria-labelledby="versusModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="versusModalLabel"><img class="adm_img me-2" src="images/admin/message_npds.png" alt="icon_" />'.adm_translate("Version").' NPDS</h5>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                 <p>Vous utilisez NPDS '.$Version_Sub.' '.$Version_Num.'</p>
                                 <p>'.adm_translate("Une nouvelle version de NPDS est disponible !").'</p>
                                 <p class="lead mt-3">'.$versus_info[1].' '.$versus_info[2].'</p>
                                 <p class="my-3">
                                    <a class="me-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'.$versus_info[2].'.zip" target="_blank" title="" data-bs-toggle="tooltip" data-bs-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.zip</a>
                                    <a class="mx-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'.$versus_info[2].'.tar.gz" target="_blank" title="" data-bs-toggle="tooltip" data-bs-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.tar.gz</a>
                                 </p>
                              </div>
                              <div class="modal-footer"></div>
                           </div>
                        </div>
                     </div>
                     <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id=""><span id="messageModalIcon" class="me-2"></span><span id="messageModalLabel"></span></h5>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                 <p id="messageModalContent"></p>
                                 <form class="mt-3" id="messageModalForm" action="" method="POST">
                                    <input type="hidden" name="id" id="messageModalId" value="0" />
                                    <button type="submit" class="btn btn btn-primary btn-sm">'.adm_translate("Confirmer la lecture").'</button>
                                 </form>
                              </div>
                              <div class="modal-footer">
                              <span class="small text-body-secondary">Information de npds.org</span><img class="adm_img me-2" src="images/admin/message_npds.png" alt="icon_" />
                              </div>
                           </div>
                        </div>
                     </div>';
      echo $adm_ent;
     if ($short_menu_admin!=false) {
        echo '</div>';
        return;
     }
     echo '
      <div id="adm_men_corps" class="my-3" >
         <div id="lst_men_main">
            '.$bloc_foncts.'
         </div>
      </div>
   </div>
';
   return ($Q['radminsuper']);
}

function adminMain($deja_affiches) {
   global $language, $admart, $hlpfile, $aid, $admf_ext, $NPDS_Prefix;
   $hlpfile = "manuels/$language/admin.html";
   include("header.php");
   include_once('functions.php');
   global $short_menu_admin;
   $short_menu_admin=false;
   $radminsuper=GraphicAdmin($hlpfile);///????????

   echo '
   <div id="adm_men_art" class="adm_workarea">
      <h2><img src="images/admin/submissions.'.$admf_ext.'" class="adm_img" title="'.adm_translate("Articles").'" alt="icon_'.adm_translate("Articles").'" />&nbsp;'.adm_translate("Derniers").' '.$admart.' '.adm_translate("Articles").'</h2>';

   $resul = sql_query("SELECT sid FROM ".$NPDS_Prefix."stories");
   $nbre_articles = sql_num_rows($resul);
   settype($deja_affiches,"integer");
   settype($admart,"integer");
   $result = sql_query("SELECT sid, title, hometext, topic, informant, time, archive, catid, ihome FROM ".$NPDS_Prefix."stories ORDER BY sid DESC LIMIT $deja_affiches,$admart");

   $nbPages = ceil($nbre_articles/$admart);
   $current = 1;
   if ($deja_affiches >= 1)
      $current=$deja_affiches/$admart;
   else if ($deja_affiches < 1)
      $current=0;
   else
      $current = $nbPages;
   $start=($current*$admart);

   if ($nbre_articles) {
      echo '
      <table id ="lst_art_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-buttons-class="outline-secondary" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead>
            <tr>
               <th data-sortable="true" data-halign="center" data-align="right" class="n-t-col-xs-1">ID</th>
               <th data-halign="center" data-sortable="true" data-sorter="htmlSorter" class="n-t-col-xs-5">'.adm_translate("Titre").'</th>
               <th data-sortable="true" data-halign="center" class="n-t-col-xs-4">'.adm_translate("Sujet").'</th>
               <th data-halign="center" data-align="center" class="n-t-col-xs-2">'.adm_translate("Fonctions").'</th>
            </tr>
         </thead>
         <tbody>';
      $i=0;
      while( (list($sid, $title, $hometext, $topic, $informant, $time, $archive, $catid, $ihome) = sql_fetch_row($result)) and ($i<$admart) ) {
         $affiche = false;
         $result2 = sql_query("SELECT topicadmin, topictext, topicimage FROM ".$NPDS_Prefix."topics WHERE topicid='$topic'");
         list ($topicadmin, $topictext, $topicimage) = sql_fetch_row($result2);
         $result3 = sql_query("SELECT title FROM ".$NPDS_Prefix."stories_cat WHERE catid='$catid'");
         list ($cat_title) = sql_fetch_row($result3);

         if ($radminsuper)
            $affiche=true;
         else {
            $topicadminX = explode(',',$topicadmin);
            for ($iX = 0; $iX < count($topicadminX); $iX++) {
               if (trim($topicadminX[$iX])==$aid) $affiche=true;
            }
         }
         $hometext = strip_tags ( $hometext ,'<br><br />');
         $lg_max = 200;
         if(strlen($hometext)>$lg_max) $hometext = substr($hometext, 0 , $lg_max).' ...';
         echo '
         <tr>
            <td>'.$sid.'</td>
            <td>';

         $title=aff_langue($title);
         if ($archive)
            echo $title.' <i>(archive)</i>';
         else {
            if ($affiche) {
               echo '<a data-bs-toggle="popover" data-bs-placement="left" data-bs-trigger="hover" href="article.php?sid='.$sid.'" data-bs-content=\'   <div class="thumbnail"><img class="img-rounded" src="images/topics/'.$topicimage.'" height="80" width="80" alt="topic_logo" /><div class="caption">'.htmlentities($hometext,ENT_QUOTES).'</div></div>\' title="'.$sid.'" data-bs-html="true">'.ucfirst($title).'</a>';
               if($ihome==1)
                  echo '<br /><small><span class="badge bg-secondary" title="'.adm_translate("Catégorie").'" data-bs-toggle="tooltip">'.aff_langue($cat_title).'</span> <span class="text-danger">non publié en index</span></small>';
               else
               if($catid>0)
                  echo '<br /><small><span class="badge bg-secondary" title="'.adm_translate("Catégorie").'" data-bs-toggle="tooltip"> '.aff_langue($cat_title).'</span> <span class="text-success"> publié en index</span></small>';
            } else
               echo '<i>'.$title.'</i>';
         }
         if ($topictext=='') {
            echo '</td>
            <td>';
         } else {
            echo '</td>
            <td>'.$topictext.'<a href="index.php?op=newtopic&amp;topic='.$topic.'" class="tooltip">'.aff_langue($topictext).'</a>';
         }
         if ($affiche)
            echo '</td>
            <td>
            <a href="admin.php?op=EditStory&amp;sid='.$sid.'" ><i class="fas fa-edit fa-lg me-2" title="'.adm_translate("Editer").'" data-bs-toggle="tooltip"></i></a>
            <a href="admin.php?op=RemoveStory&amp;sid='.$sid.'" ><i class="fas fa-trash fa-lg text-danger" title="'.adm_translate("Effacer").'" data-bs-toggle="tooltip"></i></a>';
         else
            echo '</td>
            <td>';
         echo '</td>
         </tr>';
         $i++;
      }
      echo '
         </tbody>
      </table>
      <div class="d-flex my-2 justify-content-between flex-wrap">
      <ul class="pagination pagination-sm">
         <li class="page-item disabled"><a class="page-link" href="#">'.$nbre_articles.' '.adm_translate("Articles").'</a></li>
         <li class="page-item disabled"><a class="page-link" href="#">'.$nbPages.' '.adm_translate("Page(s)").'</a></li>
      </ul>';
      echo paginate('admin.php?op=suite_articles&amp;deja_affiches=', '', $nbPages, $current, 1, $admart, $start);
      echo '
      </div>';

      if ($affiche) echo '
      <form id="fad_articles" class="form-inline" action="admin.php" method="post">
         <label class="me-2 mt-sm-1">'.adm_translate("ID Article:").'</label>
         <input class="form-control  me-2 mt-sm-3 mb-2" type="number" name="sid" />
         <select class="form-select me-2 mt-sm-3 mb-2" name="op">
            <option value="EditStory" selected="selected">'.adm_translate("Editer un Article").'</option>
            <option value="RemoveStory">'.adm_translate("Effacer l'Article").'</option>
         </select>
         <button class="btn btn-primary ms-sm-2 mt-sm-3 mb-2" type="submit">'.adm_translate("Ok").' </button>
      </form>';
   }
   echo '</div>';
   include("footer.php");
}

if ($admintest) {
   settype($op,'string');
   switch ($op) {
      case 'GraphicAdmin':
         GraphicAdmin($hlpfile);
      break;
      case 'logout':
         setcookie("admin");
         setcookie("adm_exp");
         unset($admin);
         Header("Location: index.php");
      break;
      // FILES MANAGER
      case 'FileManager':
         if ($admintest and $filemanager)
            header("location: modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=$aid");
      break;
      case 'FileManagerDisplay':
         if ($admintest and $filemanager)
            header("location: modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=download");
      break;
      //BLACKBOARD
      case 'abla':
         include('abla.php');
      break;
      // CRITIQUES
      case 'reviews':
      case 'mod_main':
      case 'add_review':
         include('admin/reviews.php');
      break;
      case 'deleteNotice':
         sql_query("DELETE FROM ".$NPDS_Prefix."reviews_add WHERE id='$id'");
         Header("Location: admin.php?op=$op_back");
      break;
      // FORUMS
      case 'ForumConfigAdmin':
         include ('admin/phpbbconfig.php');
         ForumConfigAdmin();
      break;
      case 'ForumConfigChange':
         include ('admin/phpbbconfig.php');
         ForumConfigChange($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page,$allow_upload_forum,$allow_forum_hide,$rank1,$rank2,$rank3,$rank4,$rank5,$anti_flood,$solved);
      break;
      case 'MaintForumAdmin':
         include ('admin/phpbbmaint.php');
         ForumMaintAdmin();
      break;
      case 'MaintForumMarkTopics':
         include ('admin/phpbbmaint.php');
         ForumMaintMarkTopics();
      break;
      case 'MaintForumTopics':
         include ('admin/phpbbmaint.php');
         ForumMaintTopics($before, $forum_name);
      break;
      case 'MaintForumTopicDetail':
         include ('admin/phpbbmaint.php');
         ForumMaintTopicDetail($topic, $topic_title);
      break;
      case 'SynchroForum':
         include ('admin/phpbbmaint.php');
         SynchroForum();
      break;
      case 'ForumMaintTopicSup':
         include ('admin/phpbbmaint.php');
         ForumMaintTopicSup($topic);
      break;
      case 'ForumMaintTopicMassiveSup':
         include ('admin/phpbbmaint.php');
         ForumMaintTopicMassiveSup($topics);
      break;
      case 'MergeForum':
         include ('admin/phpbbmaint.php');
         MergeForum();
      break;
      case 'MergeForumAction':
         include ('admin/phpbbmaint.php');
         MergeForumAction($oriforum,$destforum);
      break;
      case 'ForumGoAdd':
         settype($forum_pass,'string');
         include ('admin/phpbbforum.php');
         ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg);
      break;
      case 'ForumGoSave':
         include ('admin/phpbbforum.php');
         ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg);
      break;
      case 'ForumCatDel':
         include ('admin/phpbbforum.php');
         ForumCatDel($cat_id, $ok);
      break;
      case 'ForumGoDel':
         include ('admin/phpbbforum.php');
         ForumGoDel($forum_id, $ok);
      break;
      case 'ForumCatSave':
         include ('admin/phpbbforum.php');
         ForumCatSave($old_cat_id, $cat_id, $cat_title);
      break;
      case 'ForumCatEdit':
         include ('admin/phpbbforum.php');
         ForumCatEdit($cat_id);
      break;
      case 'ForumGoEdit':
         include ('admin/phpbbforum.php');
         ForumGoEdit($forum_id,$ctg);
      break;
      case 'ForumGo':
         include ('admin/phpbbforum.php');
         ForumGo($cat_id);
      break;
      case 'ForumCatAdd':
         include ('admin/phpbbforum.php');
         ForumCatAdd($catagories);
      break;
      case 'ForumAdmin':
         include ('admin/phpbbforum.php');
         ForumAdmin();
      break;
      // DOWNLOADS
      case 'DownloadDel':
         include ('admin/download.php');
         DownloadDel($did, $ok);
      break;
      case 'DownloadAdd':
         include ('admin/download.php');
         DownloadAdd($dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $dver, $dcategory, $sdcategory, $xtext, $privs, $Mprivs);
      break;
      case 'DownloadSave':
         include ('admin/download.php');
         DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $xtext, $privs, $Mprivs);
      break;
      case 'DownloadAdmin':
         include ('admin/download.php');
         DownloadAdmin();
      break;
      case 'DownloadEdit':
         include ('admin/download.php');
         DownloadEdit($did);
      break;
      // FAQ
      case 'FaqCatSave':
         include ('admin/adminfaq.php');
         FaqCatSave($old_id_cat, $id_cat, $categories);
      break;
      case 'FaqCatGoSave':
         include ('admin/adminfaq.php');
         FaqCatGoSave($id, $question, $answer);
      break;
      case 'FaqCatAdd':
         include ('admin/adminfaq.php');
         FaqCatAdd($categories);
      break;
      case 'FaqCatGoAdd':
         include ('admin/adminfaq.php');
         FaqCatGoAdd($id_cat, $question, $answer);
      break;
      case 'FaqCatEdit':
         include ('admin/adminfaq.php');
         FaqCatEdit($id_cat);
      break;
      case 'FaqCatGoEdit':
         include ('admin/adminfaq.php');
         FaqCatGoEdit($id);
      break;
      case 'FaqCatDel':
         include ('admin/adminfaq.php');
         FaqCatDel($id_cat, $ok);
      break;
      case 'FaqCatGoDel':
         include ('admin/adminfaq.php');
         FaqCatGoDel($id, $ok);
      break;
      case 'FaqAdmin':
         include ('admin/adminfaq.php');
         FaqAdmin();
      break;
      case 'FaqCatGo':
         include ('admin/adminfaq.php');
         FaqCatGo($id_cat);
      break;
      // AUTOMATED
      case 'autoStory':
      case 'autoEdit':
      case 'autoDelete':
      case 'autoSaveEdit':
         include('admin/automated.php');
      break;
      // NEWS
      case 'submissions':
         include('admin/submissions.php');
      break;
      // REFERANTS
      case 'HeadlinesDel':
      case 'HeadlinesAdd':
      case 'HeadlinesSave':
      case 'HeadlinesAdmin':
      case 'HeadlinesEdit':
         include('admin/headlines.php');
      break;
      // PREFERENCES
      case 'Configure':
      case 'ConfigSave':
         include('admin/settings.php');
      break;
      // EPHEMERIDS
      case 'Ephemeridsedit':
      case 'Ephemeridschange':
      case 'Ephemeridsdel':
      case 'Ephemeridsmaintenance':
      case 'Ephemeridsadd':
      case 'Ephemerids':
         include('admin/ephemerids.php');
      break;
      // LINKS
      case 'links':
      case 'LinksDelNew':
      case 'LinksAddCat':
      case 'LinksAddSubCat':
      case 'LinksAddLink':
      case 'LinksAddEditorial':
      case 'LinksModEditorial':
      case 'LinksDelEditorial':
      case 'LinksCleanVotes':
      case 'LinksListBrokenLinks':
      case 'LinksDelBrokenLinks':
      case 'LinksIgnoreBrokenLinks':
      case 'LinksListModRequests':
      case 'LinksChangeModRequests':
      case 'LinksChangeIgnoreRequests':
      case 'LinksDelCat':
      case 'LinksModCat':
      case 'LinksModCatS':
      case 'LinksModLink':
      case 'LinksModLinkS':
      case 'LinksDelLink':
      case 'LinksDelVote':
      case 'LinksDelComment':
      case 'suite_links':
         include('admin/links.php');
      break;
      // BANNERS
      case 'BannersAdmin':
      case 'BannersAdd':
      case 'BannerAddClient':
      case 'BannerFinishDelete':
      case 'BannerDelete':
      case 'BannerEdit':
      case 'BannerChange':
      case 'BannerClientDelete':
      case 'BannerClientEdit':
      case 'BannerClientChange':
         include('admin/banners.php');
      break;
      // HTTP Referer
      case 'hreferer':
      case 'delreferer':
      case 'archreferer':
         include('admin/referers.php');
      break;
      // TOPIC Manager
      case 'topicsmanager':
      case 'topicedit':
      case 'topicmake':
      case 'topicdelete':
      case 'topicchange':
      case 'relatedsave':
      case 'relatededit':
      case 'relateddelete':
         include('admin/topics.php');
      break;
      // SECTIONS - RUBRIQUES
      case 'new_rub_section':
      case 'sections':
      case 'sectionedit':
      case 'sectionmake':
      case 'sectiondelete':
      case 'sectionchange':
      case 'rubriquedit':
      case 'rubriquemake':
      case 'rubriquedelete':
      case 'rubriquechange':
      case 'secarticleadd':
      case 'secartedit':
      case 'secartchange':
      case 'secartchangeup':
      case 'secartdelete':
      case 'secartpublish':
      case 'secartupdate':
      case 'secartdelete2':
      case 'ordremodule':
      case 'ordrechapitre':
      case 'ordrecours':
      case 'majmodule':
      case 'majchapitre':
      case 'majcours':
      case 'publishcompat':
      case 'updatecompat':
      case 'droitauteurs':
      case 'updatedroitauteurs':
         include('admin/sections.php');
      break;
      // BLOCKS
      case 'blocks':
         include('admin/blocks.php');
      break;
      case 'makerblock':
      case 'deleterblock':
      case 'changerblock':
      case 'gaucherblock':
         include('admin/rightblocks.php');
      break;
      case 'makelblock':
      case 'deletelblock':
      case 'changelblock':
      case 'droitelblock':
         include('admin/leftblocks.php');
      break;
      case 'ablock':
      case 'changeablock':
         include('admin/adminblock.php');
      break;
      case 'mblock':
      case 'changemblock':
         include('admin/mainblock.php');
      break;
      // STORIES
      case 'DisplayStory':
      case 'PreviewAgain':
      case 'PostStory':
      case 'DeleteStory':
      case 'EditStory':
      case 'ChangeStory':
      case 'RemoveStory':
      case 'adminStory':
      case 'PreviewAdminStory':
      // CATEGORIES des NEWS
      case 'EditCategory':
      case 'DelCategory':
      case 'YesDelCategory':
      case 'NoMoveCategory':
      case 'SaveEditCategory':
      case 'AddCategory':
      case 'SaveCategory':
         include('admin/stories.php');
      break;
      // AUTHORS
      case 'mod_authors':
      case 'modifyadmin':
      case 'UpdateAuthor':
      case 'AddAuthor':
      case 'deladmin':
      case 'deladminconf':
         include('admin/authors.php');
      break;
      // USERS
      case 'mod_users':
      case 'modifyUser':
      case 'updateUser':
      case 'delUser':
      case 'delUserConf':
      case 'addUser':
      case 'extractUserCSV':
      case 'unsubUser':
      case 'nonallowed_users':
      case 'checkdnsmail_users':
         include('admin/users.php');
      break;
      // SONDAGES
      case 'create':
      case 'createPosted':
      case 'remove':
      case 'removePosted':
      case 'editpoll':
      case 'editpollPosted':
      case 'SendEditPoll':
         include('admin/polls.php');
      break;
      // DIFFUSION MI ADMIN
      case 'email_user':
      case 'send_email_to_user':
         include('admin/email_user.php');
      break;
      // LNL
      case 'lnl':
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_Header':
         $op='Sup_Header';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_Body':
         $op='Sup_Body';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_Footer':
         $op='Sup_Footer';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_HeaderOK':
         $op='Sup_HeaderOK';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_BodyOK':
         $op='Sup_BodyOK';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_FooterOK':
         $op='Sup_FooterOK';
         include('admin/lnl.php');
      break;
      case 'lnl_Shw_Header':
         $op='Shw_Header';
         include('admin/lnl.php');
      break;
      case 'lnl_Shw_Body':
         $op='Shw_Body';
         include('admin/lnl.php');
      break;
      case 'lnl_Shw_Footer':
         $op='Shw_Footer';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Header':
         $op='Add_Header';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Header_Submit':
         $op='Add_Header_Submit';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Header_Mod':
         $op='Add_Header_Mod';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Body':
         $op='Add_Body';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Body_Submit':
         $op='Add_Body_Submit';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Body_Mod':
         $op='Add_Body_Mod';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Footer':
         $op='Add_Footer';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Footer_Submit':
         $op='Add_Footer_Submit';
         include('admin/lnl.php');
      break;
      case 'lnl_Add_Footer_Mod':
         $op='Add_Footer_Mod';
         include('admin/lnl.php');
      break;
      case 'lnl_Test':
         $op='Test';
         include('admin/lnl.php');
      break;
      case 'lnl_Send':
         $op='Send';
         include('admin/lnl.php');
      break;
      case 'lnl_List':
         $op='List';
         include('admin/lnl.php');
      break;
      case 'lnl_User_List':
         $op='User_List';
         include('admin/lnl.php');
      break;
      case 'lnl_Sup_User':
         $op='Sup_User';
         include('admin/lnl.php');
      break;
      // SUPERCACHE
      case 'supercache':
      case 'supercache_save':
      case 'supercache_empty':
         include('admin/overload.php');
      break;
      // OPTIMYSQL
      case 'OptimySQL':
         include('admin/optimysql.php');
      break;
      // SAVEMYSQL
      case 'SavemySQL':
         include('admin/savemysql.php');
      break;
      // EDITO
      case 'Edito':
      case 'Edito_save':
      case 'Edito_load':
         include('admin/adminedito.php');
      break;
      // METATAGS
      case 'MetaTagAdmin':
      case 'MetaTagSave':
         include('admin/metatags.php');
      break;
      // META-LANG
      case 'Meta-LangAdmin':
      case 'List_Meta_Lang':
      case 'Creat_Meta_Lang':
      case 'Edit_Meta_Lang':
      case 'Kill_Meta_Lang':
      case 'Valid_Meta_Lang':
         include('admin/meta_lang.php');
      break;
      // ConfigFiles
      case 'ConfigFiles':
      case 'ConfigFiles_load':
      case 'ConfigFiles_save':
      case 'ConfigFiles_create':
      case 'delete_configfile':
      case 'ConfigFiles_delete':
         include('admin/configfiles.php');
      break;
      // NPDS-Admin-Plugins
      case 'Extend-Admin-Module':
      case 'Extend-Admin-SubModule':
         include('admin/plugins.php');
      break;
      // NPDS-Admin-Groupe
      case 'groupes';
      case 'groupe_edit':
      case 'groupe_maj':
      case 'groupe_add':
      case 'groupe_add_finish':
      case 'bloc_groupe_create':
      case 'retiredugroupe':
      case 'retiredugroupe_all':
      case 'membre_add':
      case 'membre_add_finish':
      case 'pad_create':
      case 'pad_remove':
      case 'note_create':
      case 'note_remove':
      case 'workspace_create':
      case 'workspace_archive':
      case 'forum_groupe_delete':
      case 'forum_groupe_create':
      case 'moderateur_update':
      case 'groupe_mns_create':
      case 'groupe_mns_delete':
      case 'groupe_chat_create':
      case 'groupe_chat_delete':
      case 'groupe_member_ask':
         include('admin/groupes.php');
      break;
      // NPDS-Instal-Modules
      case 'modules':
         include('admin/modules.php');
      break;
      case 'Module-Install':
         include('admin/module-install.php');
      break;
      case 'alerte_api':
      case 'alerte_update':
         include('npds_api.php');
      break;
      // NPDS-Admin-Main
      case 'suite_articles':
         adminMain($deja_affiches);
      break;
      case 'adminMain':
      default:
         adminMain(0);
      break;
   }
} else
   login();
?>