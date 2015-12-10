<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2014 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}
include("language/lang-adm-$language.php");
function Access_Error () {
   include("admin/die.php");
}

function admindroits($aid,$f_meta_nom) {
   global $NPDS_Prefix, $radminsuper;
   $res = sql_query("SELECT fnom, radminsuper FROM ".$NPDS_Prefix."authors a LEFT JOIN ".$NPDS_Prefix."droits d ON a.aid = d.d_aut_aid LEFT JOIN ".$NPDS_Prefix."fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$aid'");
   $foncts=array();$supers=array();
   while ($data = sql_fetch_row($res)) {
      $foncts[] = $data[0];
      $supers[] = $data[1];
   }
   if ((!in_array('1', $supers)) AND (!in_array($f_meta_nom, $foncts))) {
      Access_Error();
   }
   $radminsuper = $supers[0];
}

function adminhead($f_meta_nom, $f_titre, $adminimg) {
   global $admf_ext, $NPDS_Prefix, $f_meta_nom, $ModPath, $adm_img_mod;
   list($furlscript, $ficone)=sql_fetch_row(sql_query("SELECT furlscript, ficone FROM ".$NPDS_Prefix."fonctions WHERE fnom='$f_meta_nom'"));
   if (file_exists($adminimg.$ficone.'.'.$admf_ext)) {
      $img_adm ='<img src="'.$adminimg.$ficone.'.'.$admf_ext.'" class="vam faa-pulse animated faa-slow" border="0" alt="'.$f_titre.'" />';
   } 
   elseif (stristr($_SERVER['QUERY_STRING'],"Extend-Admin-SubModule")||$adm_img_mod==1) {
      if (file_exists('modules/'.$ModPath.'/'.$ModPath.'.'.$admf_ext)) {
         $img_adm ='<img src="modules/'.$ModPath.'/'.$ModPath.'.'.$admf_ext.'" class="vam" border="0" alt="'.$f_titre.'" />';
      } else $img_adm ='';
   }
   else $img_adm ='';
   $entete_adm ='<div id="adm_workarea" class="adm_workarea">'."\n".'   <h2><a '.$furlscript.' >'.$img_adm.'&nbsp;'.$f_titre.'</a></h2>';
   echo $entete_adm;
}

function adminfieldinp($result) {
   $fields = mysql_num_fields($result);
   $idle = array();
   for ($i=0; $i < $fields; $i++) {
      if (mysql_field_type($result, $i) == 'string') {
         $idle[mysql_field_name($result, $i)] = mysql_field_len($result, $i);
      }
   }
   echo '
   <script type="text/javascript">
   //<![CDATA[
   ';
   foreach ($idle as $k=>$v) {
      echo '
      inpandfieldlen("'.$k.'",'.$v.')';
   }
   echo '
   //]]>
   </script>';
}

$filemanager=false;
if (file_exists("filemanager.conf")) {
   include_once("filemanager.conf");
}

function login() {
   global $adminimg;
   include ("header.php");
   echo '
   <h1>'.adm_translate("Administration").'</h1>
   <div id ="adm_men">
      <div class="men_tit">
         <h2><img class="adm_img vam" src="'.$adminimg.'login.png" />&nbsp;<a href="admin.php" class="">'.adm_translate("Connexion").'</a></h2>
      </div>
      <form action="admin.php" method="post" name="adminlogin">
         <fieldset>
            <div class="form-group ">
               <div class="row">
                  <label class="control-label col-sm-3 col-md-3" for="aid">'.adm_translate("Administrateur ID").'</label>
                  <div class="col-sm-8 col-md-8">
                     <input id="aid" class="form-control" type="text" name="aid" maxlength="20" placeholder="'.adm_translate("Administrateur ID").'" required="required" />
                     <span class="help-block text-xs-right"><span id="countcar_aid"></span></span>
                  </div>
               </div>
            </div>
            <div class="form-group ">
               <div class="row">
                  <label class="control-label col-sm-3 col-md-3" for="pwd">'.adm_translate("Mot de Passe").'</label>
                  <div class="col-sm-8 col-md-8">
                     <input id="pwd" class="form-control" type="password" name="pwd" maxlength="18" placeholder="'.adm_translate("Mot de Passe").'" required="required" />
                     <span class="help-block text-xs-right"><span id="countcar_pwd"></span></span>
                  </div>
               </div>
            </div>
            <div class="form-group row">
               <div class="col-sm-offset-3 col-sm-9">
                  <button class="btn btn-primary-outline" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Valider").'</button>
               </div>
            </div>
            <input type="hidden" name="op" value="login" />
         </fieldset>
      </form>
      <script type="text/javascript">
      //<![CDATA[
      document.adminlogin.aid.focus();
      inpandfieldlen("pwd",18);
      inpandfieldlen("aid",20);
      //]]>
      </script>';
   adminfoot('fv','','','');
}

function GraphicAdmin($hlpfile) {
   global $aid, $admingraphic, $adminimg, $language, $admin, $banners, $filemanager, $Version_Sub, $Version_Num, $httprefmax;
   global $short_menu_admin, $admf_ext;
   global $NPDS_Prefix, $adm_ent;
   $bloc_foncts ='';
   $bloc_foncts_A ='';

   //==> recuperation traitement des messages de NPDS
   $QM=sql_query("SELECT * from ".$NPDS_Prefix."fonctions where fnom REGEXP'mes_npds_[[:digit:]]'");
   settype($f_mes, "array");
   while ($SQM=sql_fetch_assoc($QM)) {
   $f_mes[]=$SQM['fretour_h'];
   };
   $messagerie_npds= file_get_contents('http://labo.infocapagde.com/npds_versus_n');
   $messages_npds = explode("\n", $messagerie_npds);
   // traitement specifique car fonction permanente versus
   $versus_info = explode('|', $messages_npds[0]);
   $vs=$versus_info[1];
   $vn=$versus_info[2];

   if ($messages_npds[1]) {
      settype($mes_x, "array");
// a revoir je n'arrive pas à changer l'icone du message....
      $fico ='';
      for ($i=1;$i<count($messages_npds);$i++) {
         $zob = explode('|',$messages_npds[$i]);//print_r ( $f_mes );
         $mes_x[]=$zob;//print_r ( $mes_x );
         if($mes_x[($i-1)][0] = 'Note') {$fico='flag_red';}
         else {$fico='flag_green';}
         //echo $mes_x[($i-1)]['0'];//debug

         if (in_array ($mes_x[($i-1)][1],$f_mes,true)) {
            $k=(array_search ($mes_x[($i-1)][1], $f_mes)); unset ($f_mes[$k]);
         } else {
         sql_query('REPLACE '.$NPDS_Prefix.'fonctions set fnom="mes_npds_'.$i.'",fretour_h="'.$mes_x[($i-1)]['1'].'",fcategorie="9", fcategorie_nom="Alerte", ficone="'.$fico.'",fetat="1", finterface="1"');
         };
      }
      if(count ($f_mes)!==0) {
         foreach ( $f_mes as $v ) {
           //        sql_query('delete from '.$NPDS_Prefix.'fonctions where fretour_h="'.$v.'" and fcategorie="9"');
         }
      }
   }
   //<== recuperation traitement des messages de NPDS

   //==> recupérations des états des fonctions d'ALERTE ou activable et maj (faire une fonction avec cache court dev ..)
   //article à valider
   $newsubs=sql_num_rows(sql_query("select qid from ".$NPDS_Prefix."queue"));
   if($newsubs) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1',fretour='".$newsubs."' where fid='38'"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0',fretour='0' where fid='38'");
   //news auto
   $newauto=sql_num_rows(sql_query("select anid from ".$NPDS_Prefix."autonews"));
   if($newauto) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1',fretour='".$newauto."',fretour_h='".adm_translate("articles sont programmés pour la publication.")."' where fid=37"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0',fretour='0',fretour_h='' where fid=37");
   //etat filemanager
   if ($filemanager) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1' where fid='27'"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0' where fid='27'");
   //version npds
   if (($vs != $Version_Sub) or ($vn != $Version_Num)) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1' where fid=36"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0' where fid='36'");
   //referant à gérer
   if($httpref = 1) {
   $result=sql_fetch_assoc(sql_query("select count(*) as total from ".$NPDS_Prefix."referer"));
   if ($result['total']>=$httprefmax) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1', fretour='!!!' where fid='39'");else sql_query("update fonctions ".$NPDS_Prefix." set fetat='0' where fid='39'");
   }
   //critique en attente
   $critsubs= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."reviews_add"));
   if($critsubs) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1',fretour='".$critsubs."' where fid='35'"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0',fretour='0' where fid='35'");
   //nouveau lien à valider
   $newlink= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."links_newlink"));
   if($newlink) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1',fretour='".$newlink."' where fid='41'"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0',fretour='0' where fid='41'");
   //lien rompu à valider
   $brokenlink= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."links_modrequest where brokenlink='1'"));
   if($brokenlink) sql_query("update ".$NPDS_Prefix."fonctions set fetat='1',fretour='".$brokenlink."' where fid='42'"); else sql_query("update ".$NPDS_Prefix."fonctions set fetat='0',fretour='0' where fid='42'");
   //<== etc...etc recupérations des états des fonctions d'ALERTE et maj

   //==> construction de la zone de téléchargement des versions de NPDS
   if (($vs != $Version_Sub) or ($vn != $Version_Num)) {
   $scri='
   <script type="text/javascript">
   //<![CDATA[
   (function() {
   var html="";
   var target = $("#adm_men_info");
   var npdsAPI = "http://labo.infocapagde.com/npds_api.php?op=api_getdownload&dna=version";
   $.getJSON( npdsAPI, {
//    tags: "mount rainier",
//    tagmode: "any",
//    format: "json"
   })
    .done(function( data ) {
       html += \'<div id="versus">\n\';
       html += \'   <div class="yui3-g">\n\';
       html += \'        <div class="info yui3-u-2-3">\n\';
       html += \'           <h2>\n\';
       html += \'                <span class="avatar logo">\n\';
       html += \'                    <img src="images/topics/npds.gif" alt="logo" />\n\';
       html += \'                </span>\n\';
       html += \'                <span class="npds_text">NPDS</span>\n\';
       html += \'           </h2>\n\';
       html += \'        </div>\n\';
       html += \'        <div class="stats yui3-u-1-3">\n\';
       html += \'            <ul class="yui3-g">\n\';
       html += \'                <li class="yui3-u-1-2">\n\';
       html += \'                    <b>\'+data.length+\'</b> '.adm_translate("Versions").'\n\';
       html += \'                </li>\n\';
       html += \'                <li class="yui3-u-1-2">\n\';
       html += \'                    <b>1291</b> '.adm_translate("TÈlÈchargements").'\n\';
       html += \'                </li>\n\';
       html += \'            </ul>\n\';
       html += \'        </div>\n\';
       html += \'   </div>\n\';
       html += \'   <p class="yui3-u-1 versus_st">'.adm_translate("TÈlÈcharger une version courante.").'</p>\n\';
       html += \'   <ul class="yui3-g">\n\';

      for (i=0, l=data.length; i < l; ++i) {
       html += \'    <li class="versus" onclick="location=\\\'http://lab.grottes-et-karsts-de-chine.org/download.php?op=mydown&did=\' + data[i].did + \'\\\'" id="down_\' + data[i].did + \'">\n\';
       html += \'      <div class="yui3-u-2-3">\n\';
       html += \'        <p class="dfilename"><strong>\' + data[i].dfilename + \'</strong></p>\n\';
       html += \'      </div>\n\';
       html += \'      <div class="versus_stats yui3-u-1-3">\n\';
       html += \'        <ul class="yui3-g">\n\';
       html += \'          <li class="yui3-u-1-2"><b>\' + data[i].dcounter + \'</b><span>'.adm_translate("TÈlÈchargements").'</span></li>\n\';
       html += \'          <li class="yui3-u-1-2"><b>\' + data[i].dfilesize + \'</b><span>Mo</span></li>\n\';
       html += \'        </ul>\n\';
       html += \'      </div>\n\';
       html += \'      <div class="yui3-u-1">\n\';
       html += \'        <p>\' + data[i].ddescription + \'</p>\n\';
       html += \'      </div>\n\';
       html += \'     </li>\n\';
      }
/*
      $.each( data, function( i, item ) {
        $( "<img>" ).attr( "src", item.media.m ).appendTo( "#images" );
        if ( i === 3 ) {
          return false;
        }
      });
*/
        html +=\'   </ul>\n\';
        html +=\'</div>\n\';
        target.html(html);
        target.hide();
    });
})();
   $( document ).ready(function() {
      tog(\'adm_men_info\',\'show_inf\',\'hide_inf\');
   })

   //]]>
   </script>'."\n";
}
   //<== construction de la zone de téléchargement des versions de NPDS

   //==> construction des blocs menu : selection de fonctions actives ayant une interface graphique de premier niveau et dont l'administrateur connecté en possède les droits d'accès
   $Q = sql_fetch_assoc(sql_query("SELECT * FROM ".$NPDS_Prefix."authors WHERE aid='$aid' LIMIT 1"));
//   $Q = sql_fetch_assoc($Q);
   if ($Q['radminsuper']==1) {
   // on prend tout ce qui a une interface 
      $R = sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f WHERE f.finterface =1 and f.fetat != '0' order by f.fcategorie, f.fordre");}
   else {
      $R = sql_query("SELECT * FROM ".$NPDS_Prefix."fonctions f LEFT JOIN droits d ON f.fid = d.d_fon_fid LEFT JOIN authors a ON d.d_aut_aid =a.aid WHERE f.finterface =1 and fetat!=0 and d.d_aut_aid='$aid' AND d.d_droits REGEXP'^1' order by f.fcategorie, f.fordre");
   }

   $j=0;
   while($SAQ=sql_fetch_assoc($R)) {
      $cat[]=$SAQ['fcategorie'];
      $cat_n[]=$SAQ['fcategorie_nom'];
      $fid_ar[]=$SAQ['fid'];
      if ($SAQ['fcategorie'] == 6)  {
         if (file_exists('modules/'.$SAQ['fnom'].'/'.$SAQ['fnom'].'.'.$admf_ext)) $adminico='modules/'.$SAQ['fnom'].'/'.$SAQ['fnom'].'.'.$admf_ext; else $adminico=$adminimg.'module.'.$admf_ext;
      } else {
      $adminico=$adminimg.$SAQ['ficone'].'.'.$admf_ext;
      };
   
      if ($SAQ['fcategorie'] == 9) {
         //==<euh je ne sais plus comment j'avais envisager l'arrivée des messages dans la base ???? arghhhhhh 
         if(preg_match ( '#^mes_npds_#', $SAQ['fnom']))
         $li_c ='<li class=" btn btn-secondary" title="'.$SAQ['fretour_h'].'" data-toggle="tooltip">';
         else 
         $li_c ='<li class="alerte btn btn-secondary" title="'.$SAQ['fretour_h'].'" data-toggle="tooltip">';
         $li_c .='<a '.$SAQ['furlscript'].' class="adm_img"><img class="adm_img" src="'.$adminico.'" alt="icon_'.$SAQ['fnom_affich'].'" />'."\n";
         $li_c .='<span class="alerte-para label label-pill label-danger">'.$SAQ['fretour'].'</span>'."\n";
         $li_c .='</a></li>'."\n";
         $bloc_foncts_A .= $li_c;
         array_pop($cat_n);
      } 
      else {
         $ul_o = '
         <h4 class="text-muted"><span class="tog" id="hide_'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" title="'.adm_translate("Replier la liste").'" style="clear:left;"><i id="i_'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" class="fa fa-minus-square-o" ></i></span>&nbsp;'.$SAQ['fcategorie_nom'].'</h4>
         <ul id="'.strtolower(substr($SAQ['fcategorie_nom'],0,3)).'" class="list" style="clear:left;">';
         $li_c = '
         <li id="'.$SAQ['fid'].'" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="'.adm_translate($SAQ['fnom_affich']).'"><a '.$SAQ['furlscript'].'>';
         if ($admingraphic==1) {
            $li_c .='<img class="adm_img" src="'.$adminico.'" alt="icon_'.$SAQ['fnom_affich'].'" />';
         } else{
            $li_c .= adm_translate($SAQ['fnom_affich']);
         }
         $li_c .='</a></li>';
         $ul_f ='
         </ul>
         <script type="text/javascript">
         //<![CDATA[
         $( document ).ready(function() {
         tog(\''.strtolower(substr($cat_n[$j-1],0,3)).'\',\'show_'.strtolower(substr($cat_n[$j-1],0,3)).'\',\'hide_'.strtolower(substr($cat_n[$j-1],0,3)).'\');
         })
         //]]>
         </script>'."\n";

         if ($j==0) {
            $bloc_foncts .= $ul_o.$li_c;} 
         else { if ($j>0 and $cat[$j]>$cat[$j-1]) $bloc_foncts.=$ul_f.$ul_o.$li_c; else
         $bloc_foncts .= $li_c;
         }
      }
      $j++;
   }
   if($cat_n) $ca=array_pop(array_unique($cat_n));

   $bloc_foncts .= '
   <script type="text/javascript" style="clear:left;">
      //<![CDATA[
       $( document ).ready(function() {
         tog(\''.strtolower(substr($ca,0,3)).'\',\'show_'.strtolower(substr($ca,0,3)).'\',\'hide_'.strtolower(substr($ca,0,3)).'\');
      })
      //]]>
   </script>';

   echo "
   <script type=\"text/javascript\">
   //<![CDATA[
   
   $( document ).ready(function () {
      $( '#lst_men_main ul' ).each(function() {
         var idi= $(this).attr('id'),
              eu= Cookies.get('eu_'+idi),
              eb= Cookies.get('eb_'+idi),
              es= Cookies.get('es_'+idi),
              et= Cookies.get('et_'+idi);
         $('#i_'+idi).attr('class',eb);
         $(this).attr('style',eu);
         $('span.tog[id$=\"'+idi+'\"]').attr('id',es);
         $('span.tog[id$=\"'+idi+'\"]').attr('title',et);
      });
   });
   

   $( window ).unload(function() {
      $( '#lst_men_main ul' ).each(function( index ) {
         var idi= $(this).attr('id'),
             sty= $(this).attr('style'),
            idsp= $('span.tog[id$=\"'+idi+'\"]').attr('id'),
            tisp= $('span.tog[id$=\"'+idi+'\"]').attr('title'),
             cla= $('#i_'+idi).attr('class');
         Cookies.set('et_'+idi,tisp);
         Cookies.set('es_'+idi,idsp);
         Cookies.set('eu_'+idi,sty);
         Cookies.set('eb_'+idi,cla);
       });
   });

   function openwindow(){
       window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");
    }

   $( document ).ready(function () {
//       if($('[data-toggle=\"tooltip\"]').length) console.log('true'); 
       
//     $('body').tooltip({ selector: '[data-toggle=tooltip]' });// USEFUL FOR FF ???
       $('[data-toggle=\"tooltip\"]').tooltip();
       $('[data-toggle=\"popover\"]').popover();
       $('table').on('all.bs.table', function (e, name, args) {
        $('[data-toggle=\"tooltip\"]').tooltip();
        $('[data-toggle=\"popover\"]').popover();  
    });
   });

   //==>date d'expiration connection admin
   $(function () {
      var dae = Cookies.get('adm_exp')*1000,
       dajs = new Date(dae);

   $('#adm_connect_status').attr('title', 'Connexion ouverte jusqu\'au : '+dajs.getDate()+'/'+ (dajs.getMonth()+1) +'/'+ dajs.getFullYear() +'/'+ dajs.getHours() +':'+ dajs.getMinutes()+':'+ dajs.getSeconds()+' GMT');
  
   
   deCompte= function() {
   var date1 = new Date(),
       sec = (dae - date1) / 1000,
       n = 24 * 3600;
     if (sec > 0) {
       j = Math.floor (sec / n);
       h = Math.floor ((sec - (j * n)) / 3600);
       mn = Math.floor ((sec - ((j * n + h * 3600))) / 60);
       sec = Math.floor (sec - ((j * n + h * 3600 + mn * 60)));
       $('#car').text(j +'j '+ h +':'+ mn +':'+sec);
     }
   t_deCompte=setTimeout (deCompte, 1000);
   }
   deCompte();
   
    })
   //<== date d'expiration connection admin
   
   tog = function(lst,sho,hid){
      $('#adm_men, #adm_workarea').on('click', 'span.tog', function() {
         var buttonID = $(this).attr('id');
         lst_id = $('#'+lst);
         i_id=$('#i_'+lst);
         btn_show=$('#'+sho);
         btn_hide=$('#'+hid);
         if (buttonID == sho) {
            lst_id.fadeIn(1000);//show();
            btn_show.attr('id',hid)
            btn_show.attr('title','".adm_translate("Replier la liste")."');
            i_id.attr('class','fa fa-minus-square-o');
         } else if (buttonID == hid) {
            lst_id.fadeOut(1000);//hide();
            btn_hide=$('#'+hid);
            btn_hide.attr('id',sho);
            btn_hide.attr('title','".adm_translate("DÈplier la liste")."');
            i_id.attr('class','fa fa-plus-square-o');
        }
       });
   };
   //]]>
   </script>\n";

   $adm_ent ='';
   $adm_ent .='
   <div id="adm_tit" class="row">
      <div id="adm_tit_l" class="col-xs-12">
         <h1>'.adm_translate("Administration").'&nbsp;<!-- <span id="adm_connect_status" class="glyphicons glyphicons-unlock x2 drop" data-toggle="tooltip"> --></h1>
      </div>
   </div>
   <div id ="adm_men" class="row">
      <div id="adm_header" class="row">
         <div class="col-xs-3 men_tit">
            <h2><a href="admin.php">'.adm_translate("Menu").'</a></h2>
         </div>
         <div id="adm_men_man" class="col-xs-9 men_man">
            <ul class="liste" id="lst_men_top">
               <li class="btn btn-default" data-toggle="tooltip" title="'.adm_translate("DÈconnexion").'" ><a href="admin.php?op=logout" >&nbsp;<i class="fa fa-sign-out fa-2x text-danger"></i></a></li>'."\n";
   if ($hlpfile) {
      $adm_ent .='
              <li class="btn btn-default" data-toggle="tooltip" title="'.adm_translate("Manuel en ligne").'"><a href="javascript:openwindow();">&nbsp;<i class="fa fa-question-circle fa-2x text-info"></i></a></li>'."\n";
   }
   $adm_ent .='
            </ul>
         </div>
      </div>
      <div id="adm_men_dial">
         <div id="adm_men_alert" >
            '.$scri.'
                <ul id="Alerte">
                '.aff_langue($bloc_foncts_A).'
                </ul>
            </div>
            <div id="adm_men_info"></div>
            <div class="contenair-fluid" id ="mes_perm" >
                <span class="car">'.$Version_Sub.' '.$Version_Num.' '.$aid.' </span><span id="car" class="car"></span>
            </div>
        </div>';
//<img style="float:left" src="'.$adminimg.'message_npds.'.$admf_ext.'" width="32" height="32" title="'.adm_translate("Nouvelle version !").'" alt="icon_'.adm_translate("logo_npds").'" />
//<li id ="mes_perm" ><img style="float:left" src="'.$adminimg.'message_npds.'.$admf_ext.'" width="32" height="32" title="'.adm_translate("Nouvelle version !").'" alt="icon_'.adm_translate("logo_npds").'" /><span class="car">'.$Version_Sub.' '.$Version_Num.'<br />'.$aid.'<br /></span><span id="car" class="car" ></span></li>

      echo $adm_ent;
     if ($short_menu_admin!=false) {
     echo"</div>\n";
        return;
     }
     echo '
    <div id="adm_men_corps">
      <div id="lst_men_main">
         '.$bloc_foncts.'
      </div>
     </div>
    </div>';
    return ($Q['radminsuper']);
}

function adminMain($deja_affiches) {
   global $language, $admart, $hlpfile, $aid, $admf_ext;
   global $NPDS_Prefix;
   $hlpfile = "manuels/$language/admin.html";
   include("header.php");
   global $short_menu_admin;
   $short_menu_admin=false;
   $radminsuper=GraphicAdmin($hlpfile);///????????
   
   echo '<div id="adm_men_art" class="adm_workarea">
   <h2><img src="images/admin/submissions.'.$admf_ext.'" class="adm_img" title="'.adm_translate("Articles").'" alt="icon_'.adm_translate("Articles").'" />&nbsp;'.adm_translate("Derniers").' '.$admart.' '.adm_translate("Articles").'</h2>'."\n";

   $resul = sql_query("select sid from ".$NPDS_Prefix."stories");
   $nbre_articles = sql_num_rows($resul);
   settype($deja_affiches,"integer");
   settype($admart,"integer");
   $result = sql_query("select sid, title, hometext, topic, informant, time, archive from ".$NPDS_Prefix."stories order by time desc LIMIT $deja_affiches,$admart");

   if ($nbre_articles) {
      echo '<table text-xs-right>
                <thead>
                    <tr>
                        <th data-sortable="true">ID</th>
                        <th data-sortable="true">'.adm_translate("Titre").'</th>
                        <th data-sortable="true">'.adm_translate("Sujet").'</th>
                        <th>'.adm_translate("Fonctions").'</th>
                    </tr>
                </thead>
                <tbody>'."\n";
      $i=0;
      while( (list($sid, $title, $hometext, $topic, $informant, $time, $archive) = sql_fetch_row($result)) and ($i<$admart) ) {
         $affiche = false;
         $result2 = sql_query("select topicadmin, topictext, topicimage from ".$NPDS_Prefix."topics where topicid='$topic'");
         list ($topicadmin, $topictext, $topicimage) = sql_fetch_row($result2);
         if ($radminsuper) {
            $affiche=true;
         } else {
            $topicadminX = explode(",",$topicadmin);
            for ($iX = 0; $iX < count($topicadminX); $iX++) {
               if (trim($topicadminX[$iX])==$aid) $affiche=true;
            }
         }
         $hometext = strip_tags ( $hometext ,'<br><br />');
         $lg_max = 200;
         if(strlen($hometext)>$lg_max) $hometext =substr ( $hometext, 0 , $lg_max).' ...';
         echo '
         <tr>
            <td>'.$sid.'</td>
            <td>';

         $title=aff_langue($title);
         if ($archive) {
            echo $title.' <i>(archive)</i>';
         } else {
            if ($affiche) {
               echo '<a data-toggle="popover" data-placement="bottom" data-trigger="hover" href="article.php?sid='.$sid.'" data-content=\'   <div class="thumbnail"><img class="img-rounded" src="images/topics/'.$topicimage.'" height="80" width="80" alt="topic_logo" /><div class="caption">'.htmlentities($hometext,ENT_QUOTES).'</div></div>\' title="'.$sid.'" data-html="true">'.$title.'</a>';
            } else {
               echo '<i>'.$title.'</i>';
            }
         }
         if ($topictext=="") {
            echo '</td>
            <td>';
         } else {
            echo '</td>
            <td>'.$topictext.'<a href="index.php?op=newtopic&amp;topic='.$topic.'" class="tooltip">'.aff_langue($topictext).'</a>';
         }
         
         if ($affiche) {
            echo '</td>
            <td>
            <a href="admin.php?op=EditStory&amp;sid='.$sid.'" ><i class="fa fa-edit fa-lg" title="'.adm_translate("Editer").'" data-toggle="tooltip"></i></a>
            <a href="admin.php?op=RemoveStory&amp;sid='.$sid.'" ><i class="fa fa-trash-o fa-lg text-danger" title="'.adm_translate("Effacer").'" data-toggle="tooltip"></i></a>';
         } else {
            echo '</td>
            <td>';
         }
         
         echo '</td>
         </tr>';
         $i++;
      }
      echo '
         </tbody>
      </table>
      <ul class="pagination pagination-sm">
      <li class="active"><a href="#">'.$nbre_articles.' Articles</a></li>
      <li><a href="admin.php?op=suite_articles&amp;deja_affiches=0" class="noir">'.adm_translate("Les plus rÈcents").'</a></li>';


      if ($deja_affiches>=$admart) echo "
      <li><a href=\"admin.php?op=suite_articles&amp;deja_affiches=".($deja_affiches-$admart)."\" >".adm_translate("PrÈcÈdent")."</a></li>";
      if (($deja_affiches + $i) < $nbre_articles) {
         $deja_affiches+=$admart;
         echo "
      <li><a href=\"admin.php?op=suite_articles&amp;deja_affiches=".$deja_affiches."\" >".adm_translate("Suivant")."</a></li>";
      }
      echo '
      </ul>';
      echo '
      <form class="" action="admin.php" method="post">
      <div class="form-group">
        <div class="row form-inline">
            <div class="col-xs-4">
                <label class="control-label">'.adm_translate("ID Article:").'</label>
                <input class="form-control" type="number" name="sid" size="10" />
            </div>
            <div class="col-xs-4">
                <select class="form-control" name="op">
                    <option value="EditStory" selected="selected">'.adm_translate(" Editer un Article ").'</option>
                    <option value="RemoveStory">'.adm_translate(" Effacer l'Article").'</option>
                </select>
            </div>
            <div class="col-xs-4">
               <button class="btn btn-primary" type="submit">'.adm_translate("Ok").' </button>
            </div>
        </div>
    </div>
    </form>';
   }
   echo "</div>\n";
   include("footer.php");
}

if ($admintest) {
   settype($op,'string');
   switch ($op) {
        case "GraphicAdmin":
             GraphicAdmin($hlpfile);
             break;

        case "logout":
             setcookie("admin");
             setcookie("adm_exp");
             unset($admin);
             Header("Location: index.php");
             break;

        // FILES MANAGER
        case "FileManager":
            if ($admintest and $filemanager) {
               header("location: modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=$aid");
            }
            break;
        case "FileManagerDisplay":
            if ($admintest and $filemanager) {
               header("location: modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=download");
            }
            break;
        // FILES MANAGER

        // CRITIQUES
        case "reviews":
        case "mod_main":
        case "add_review":
             include("admin/reviews.php");
             break;
        case "deleteNotice":
             sql_query("delete from ".$NPDS_Prefix."reviews_add WHERE id='$id'");
             Header("Location: admin.php?op=$op_back");
             break;
        // CRITIQUES

        // FORUMS
        case "ForumConfigAdmin":
             include ("admin/phpbbconfig.php");
             ForumConfigAdmin();
             break;
        case "ForumConfigChange":
             include ("admin/phpbbconfig.php");
             ForumConfigChange($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page,$allow_upload_forum,$allow_forum_hide,$rank1,$rank2,$rank3,$rank4,$rank5,$anti_flood,$solved);
             break;

        case "MaintForumAdmin":
             include ("admin/phpbbmaint.php");
             ForumMaintAdmin();
             break;
        case "MaintForumMarkTopics":
             include ("admin/phpbbmaint.php");
             ForumMaintMarkTopics();
             break;
        case "MaintForumTopics":
             include ("admin/phpbbmaint.php");
             ForumMaintTopics($before, $forum_name);
             break;
        case "MaintForumTopicDetail":
             include ("admin/phpbbmaint.php");
             ForumMaintTopicDetail($topic, $topic_title);
             break;
        case "SynchroForum":
             include ("admin/phpbbmaint.php");
             SynchroForum();
             break;
        case "ForumMaintTopicSup":
             include ("admin/phpbbmaint.php");
             ForumMaintTopicSup($topic);
             break;
        case "ForumMaintTopicMassiveSup":
             include ("admin/phpbbmaint.php");
             ForumMaintTopicMassiveSup($topics);
             break;
        case "MergeForum":
             include ("admin/phpbbmaint.php");
             MergeForum();
             break;
        case "MergeForumAction":
             include ("admin/phpbbmaint.php");
             MergeForumAction($oriforum,$destforum);
             break;

        case "ForumGoAdd":
             include ("admin/phpbbforum.php");
             ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg);
             break;
        case "ForumGoSave":
             include ("admin/phpbbforum.php");
             ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg);
             break;
        case "ForumCatDel":
             include ("admin/phpbbforum.php");
             ForumCatDel($cat_id, $ok);
             break;
        case "ForumGoDel":
             include ("admin/phpbbforum.php");
             ForumGoDel($forum_id, $ok);
             break;
        case "ForumCatSave":
             include ("admin/phpbbforum.php");
             ForumCatSave($old_cat_id, $cat_id, $cat_title);
             break;
        case "ForumCatEdit":
             include ("admin/phpbbforum.php");
             ForumCatEdit($cat_id);
             break;
        case "ForumGoEdit":
             include ("admin/phpbbforum.php");
             ForumGoEdit($forum_id,$ctg);
             break;
        case "ForumGo":
             include ("admin/phpbbforum.php");
             ForumGo($cat_id);
             break;
        case "ForumCatAdd":
             include ("admin/phpbbforum.php");
             ForumCatAdd($catagories);
             break;
        case "ForumAdmin":
             include ("admin/phpbbforum.php");
             ForumAdmin();
             break;
        // FORUMS

        // DOWNLOADS
        case "DownloadDel":
             include ("admin/download.php");
             DownloadDel($did, $ok);
             break;
        case "DownloadAdd":
             include ("admin/download.php");
             DownloadAdd($dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $dver, $dcategory, $sdcategory, $xtext, $privs, $Mprivs);
             break;
        case "DownloadSave":
             include ("admin/download.php");
             DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $xtext, $privs, $Mprivs);
             break;
        case "DownloadAdmin":
             include ("admin/download.php");
             DownloadAdmin();
             break;
        case "DownloadEdit":
             include ("admin/download.php");
             DownloadEdit($did);
             break;
        // DOWNLOADS

        // FAQ
        case "FaqCatSave":
             include ("admin/adminfaq.php");
             FaqCatSave($old_id_cat, $id_cat, $categories);
             break;
        case "FaqCatGoSave":
             include ("admin/adminfaq.php");
             FaqCatGoSave($id, $question, $answer);
             break;
        case "FaqCatAdd":
             include ("admin/adminfaq.php");
             FaqCatAdd($categories);
             break;
        case "FaqCatGoAdd":
             include ("admin/adminfaq.php");
             FaqCatGoAdd($id_cat, $question, $answer);
             break;
        case "FaqCatEdit":
             include ("admin/adminfaq.php");
             FaqCatEdit($id_cat);
             break;
        case "FaqCatGoEdit":
             include ("admin/adminfaq.php");
             FaqCatGoEdit($id);
             break;
        case "FaqCatDel":
             include ("admin/adminfaq.php");
             FaqCatDel($id_cat, $ok);
             break;
        case "FaqCatGoDel":
             include ("admin/adminfaq.php");
             FaqCatGoDel($id, $ok);
             break;
        case "FaqAdmin":
             include ("admin/adminfaq.php");
             FaqAdmin();
             break;
        case "FaqCatGo":
             include ("admin/adminfaq.php");
             FaqCatGo($id_cat);
             break;
        // FAQ

        // AUTOMATED
        case "autoStory":
        case "autoEdit":
        case "autoDelete":
        case "autoSaveEdit":
             include("admin/automated.php");
             break;
        // AUTOMATED

        // NEWS
        case "submissions":
             include("admin/submissions.php");
             break;
        // NEWS

        // REFERANTS
        case "HeadlinesDel":
        case "HeadlinesAdd":
        case "HeadlinesSave":
        case "HeadlinesAdmin":
        case "HeadlinesEdit":
             include("admin/headlines.php");
             break;
        // REFERANTS

        // PREFERENCES
        case "Configure":
        case "ConfigSave":
            include("admin/settings.php");
            break;
        // PREFERENCES

        // EPHEMERIDS
        case "Ephemeridsedit":
        case "Ephemeridschange":
        case "Ephemeridsdel":
        case "Ephemeridsmaintenance":
        case "Ephemeridsadd":
        case "Ephemerids":
            include("admin/ephemerids.php");
            break;
        // EPHEMERIDS

        // LINKS
        case "links":
        case "LinksDelNew":
        case "LinksAddCat":
        case "LinksAddSubCat":
        case "LinksAddLink":
        case "LinksAddEditorial":
        case "LinksModEditorial":
        case "LinksDelEditorial":
        case "LinksCleanVotes":
        case "LinksListBrokenLinks":
        case "LinksDelBrokenLinks":
        case "LinksIgnoreBrokenLinks":
        case "LinksListModRequests":
        case "LinksChangeModRequests":
        case "LinksChangeIgnoreRequests":
        case "LinksDelCat":
        case "LinksModCat":
        case "LinksModCatS":
        case "LinksModLink":
        case "LinksModLinkS":
        case "LinksDelLink":
        case "LinksDelVote":
        case "LinksDelComment":
        case "suite_links":
            include("admin/links.php");
            break;
        // LINKS

        // BANNERS
        case "BannersAdmin":
        case "BannersAdd":
        case "BannerAddClient":
        case "BannerFinishDelete":
        case "BannerDelete":
        case "BannerEdit":
        case "BannerChange":
        case "BannerClientDelete":
        case "BannerClientEdit":
        case "BannerClientChange":
             include("admin/banners.php");
             break;
        // BANNERS

        // HTTP Referer
        case "hreferer":
        case "delreferer":
        case "archreferer":
             include("admin/referers.php");
             break;
        // HTTP Referer

        // TOPIC Manager
        case "topicsmanager":
        case "topicedit":
        case "topicmake":
        case "topicdelete":
        case "topicchange":
        case "relatedsave":
        case "relatededit":
        case "relateddelete":
             include("admin/topics.php");
             break;
        // TOPIC Manager

        // SECTIONS - RUBRIQUES
        case "new_rub_section":
        case "sections":
        case "sectionedit":
        case "sectionmake":
        case "sectiondelete":
        case "sectionchange":
        case "rubriquedit":
        case "rubriquemake":
        case "rubriquedelete":
        case "rubriquechange":
        case "secarticleadd":
        case "secartedit":
        case "secartchange":
        case "secartchangeup":
        case "secartdelete":
        case "secartpublish":
        case "secartupdate":
        case "secartdelete2":
        case "ordremodule":
        case "ordrechapitre":
        case "ordrecours":
        case "majmodule":
        case "majchapitre":
        case "majcours":
        case "publishcompat":
        case "updatecompat":
        case "droitauteurs":
        case "updatedroitauteurs":
        case "menu_dyn":
             include("admin/sections.php");
             break;
        // SECTIONS - RUBRIQUES

        // BLOCKS
        case "blocks":
             include("admin/blocks.php");
             break;

             case "makerblock":
             case "deleterblock":
             case "changerblock":
             case "gaucherblock":
                  include("admin/rightblocks.php");
                  break;

             case "makelblock":
             case "deletelblock":
             case "changelblock":
             case "droitelblock":
                  include("admin/leftblocks.php");
                  break;

        case "ablock":
        case "changeablock":
             include("admin/adminblock.php");
             break;

        case "mblock":
        case "changemblock":
             include("admin/mainblock.php");
             break;
        // BLOCKS

        // STORIES
        case "DisplayStory":
        case "PreviewAgain":
        case "PostStory":
        case "DeleteStory":
        case "EditStory":
        case "ChangeStory":
        case "RemoveStory":
        case "adminStory":
        case "PreviewAdminStory":
        // CATEGORIES des NEWS
        case "EditCategory":
        case "DelCategory":
        case "YesDelCategory":
        case "NoMoveCategory":
        case "SaveEditCategory":
        case "AddCategory":
        case "SaveCategory":
             include("admin/stories.php");
             break;
        // CATEGORIES des NEWS
        // STORIES

        // AUTHORS
        case "mod_authors":
        case "modifyadmin":
        case "UpdateAuthor":
        case "AddAuthor":
        case "deladmin":
        case "deladminconf":
             include("admin/authors.php");
             break;
        // AUTHORS

        // USERS
        case "mod_users":
        case "modifyUser":
        case "updateUser":
        case "delUser":
        case "delUserConf":
        case "addUser":
        case "extractUserCSV":
        case "unsubUser":
             include("admin/users.php");
             break;
        // USERS

        // SONDAGES
        case "create":
        case "createPosted":
        case "remove":
        case "removePosted":
        case "editpoll":
        case "editpollPosted":
        case "SendEditPoll":
             include("admin/polls.php");
             break;
        // SONDAGES

        // DIFFUSION MI ADMIN
        case "email_user":
        case "send_email_to_user":
             include("admin/email_user.php");
             break;
        // DIFFUSION MI ADMIN

        // LNL
        case "lnl":
            include("admin/lnl.php");
            break;
            case "lnl_Sup_Header":
               $op="Sup_Header";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_Body":
               $op="Sup_Body";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_Footer":
               $op="Sup_Footer";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_HeaderOK":
               $op="Sup_HeaderOK";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_BodyOK":
               $op="Sup_BodyOK";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_FooterOK":
               $op="Sup_FooterOK";
               include("admin/lnl.php");
               break;
            case "lnl_Shw_Header":
               $op="Shw_Header";
               include("admin/lnl.php");
               break;
            case "lnl_Shw_Body":
               $op="Shw_Body";
               include("admin/lnl.php");
               break;
            case "lnl_Shw_Footer":
               $op="Shw_Footer";
               include("admin/lnl.php");
               break;
            case "lnl_Add_Header":
               $op="Add_Header";
               include("admin/lnl.php");
               break;
               case "lnl_Add_Header_Submit":
                  $op="Add_Header_Submit";
                  include("admin/lnl.php");
                  break;
               case "lnl_Add_Header_Mod":
                  $op="Add_Header_Mod";
                  include("admin/lnl.php");
                  break;
            case "lnl_Add_Body":
               $op="Add_Body";
               include("admin/lnl.php");
               break;
               case "lnl_Add_Body_Submit":
                  $op="Add_Body_Submit";
                  include("admin/lnl.php");
                  break;
               case "lnl_Add_Body_Mod":
                  $op="Add_Body_Mod";
                  include("admin/lnl.php");
                  break;
            case "lnl_Add_Footer":
               $op="Add_Footer";
               include("admin/lnl.php");
               break;
               case "lnl_Add_Footer_Submit":
                  $op="Add_Footer_Submit";
                  include("admin/lnl.php");
                  break;
               case "lnl_Add_Footer_Mod":
                  $op="Add_Footer_Mod";
                  include("admin/lnl.php");
                  break;
            case "lnl_Test":
               $op="Test";
               include("admin/lnl.php");
               break;
            case "lnl_Send":
               $op="Send";
               include("admin/lnl.php");
               break;
            case "lnl_List":
               $op="List";
               include("admin/lnl.php");
               break;
            case "lnl_User_List":
               $op="User_List";
               include("admin/lnl.php");
               break;
            case "lnl_Sup_User":
               $op="Sup_User";
               include("admin/lnl.php");
               break;
        // LNL

        // SUPERCACHE
        case "supercache":
        case "supercache_save":
        case "supercache_empty":
             include("admin/overload.php");
             break;
        // SUPERCACHE

        // OPTIMYSQL
        case "OptimySQL":
             include("admin/optimysql.php");
             break;
        // OPTIMYSQL

        // SAVEMYSQL
        case "SavemySQL":
             include("admin/savemysql.php");
             break;
        // SAVEMYSQL

        // EDITO
        case "Edito":
        case "Edito_save":
        case "Edito_load":
             include("admin/adminedito.php");
             break;
        // EDITO

        // METATAGS
        case "MetaTagAdmin":
        case "MetaTagSave":
             include("admin/metatags.php");
             break;
        // METATAGS

        // META-LANG
        case "Meta-LangAdmin":
        case "List_Meta_Lang":
        case "Creat_Meta_Lang":
        case "Edit_Meta_Lang":
        case "Kill_Meta_Lang":
        case "Valid_Meta_Lang":
             include("admin/meta_lang.php");
             break;
        // META-LANG

        // ConfigFiles
        case "ConfigFiles":
        case "ConfigFiles_load":
        case "ConfigFiles_save":
        case "ConfigFiles_create":
        case "delete_configfile":
        case "ConfigFiles_delete":
             include("admin/configfiles.php");
             break;
        // ConfigFiles

        // NPDS-Admin-Plugins
        case "Extend-Admin-Module":
        case "Extend-Admin-SubModule":
             include("admin/plugins.php");
             break;
        // NPDS-Admin-Plugins

        // NPDS-Admin-Groupe
        case "groupes";
        case "groupe_edit":
        case "groupe_maj":
        case "groupe_add":
        case "groupe_add_finish":
        case "bloc_groupe_create":
        case "retiredugroupe":
        case "retiredugroupe_all":
        case "membre_add":
        case "membre_add_finish":
        case "pad_create":
        case "pad_remove":
        case "note_create":
        case "note_remove":
        case "workspace_create":
        case "workspace_archive":
        case "forum_groupe_delete":
        case "forum_groupe_create":
        case "moderateur_update":
        case "groupe_mns_create":
        case "groupe_mns_delete":
        case "groupe_chat_create":
        case "groupe_chat_delete":
        include("admin/groupes.php");
             break;
        // NPDS-Admin-Groupe

        // NPDS-Instal-Modules
        case "modules":
             include("admin/modules.php");
             break;
        case "Module-Install":
             include("admin/module-install.php");
             break;
        // NPDS-Instal-Modules

        // NPDS-Admin-Main
        case "suite_articles":
             adminMain($deja_affiches);
             break;

        case "adminMain":
        default:
             adminMain(0);
             break;
        // NPDS-Admin-Main
   }
} else {
   login();
}
?>