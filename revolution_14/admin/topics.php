<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2014 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='topicsmanager';
$f_titre = adm_translate("Gestion des sujets");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/topics.html";
function topicsmanager() {
   global $hlpfile, $tipath, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("select topicid, topicname, topicimage, topictext from ".$NPDS_Prefix."topics order by topicname");
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   if (sql_num_rows($result) > 0) {
      echo '
   <h3>'.adm_translate("Sujets actifs").'</h3>';
      while (list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result)) {
         echo '
   <div class="col-sm-12 thumbnail btn-secondary" id="top_'.$topicid.'">
      <div class="media topi">
         <div class="media-left media-middle">';
         if (($topicimage) or ($topicimage!="")) {
            echo '<a href="admin.php?op=topicedit&amp;topicid='.$topicid.'"><img class="img-thumbnail" style="height:80px; max-height:80px; max-width:80px" src="'.$tipath.$topicimage.'" data-toggle="tooltip" title="ID : '.$topicid.'" alt="'.$topicname.'" /></a>';
         } else {
            echo '<a href="admin.php?op=topicedit&amp;topicid='.$topicid.'"><img class="img-thumbnail" style="height:80px; max-height:80px; max-width:80px" src="'.$tipath.'topics.png" data-toggle="tooltip" title="ID : '.$topicid.'" alt="'.$topicname.'" /></a>';
         };
         echo '
         </div>
         <div class="media-body">
            <h4 class="media-heading"><a href="admin.php?op=topicedit&amp;topicid='.$topicid.'" class="noir">'.aff_langue($topicname).'</a></h4>
            <p>'.aff_langue($topictext).'</p>
         </div>
      </div>
   </div>';
         
//            if ($topictext=="") {$topictext=adm_translate("Sans titre");}
//            echo '<a href="admin.php?op=topicedit&amp;topicid='.$topicid.'" title="ID : '.$topicid.'">'.aff_langue($topictext).'"</a>';
       }
    }
    echo '
   <h3>'.adm_translate("Ajouter un nouveau Sujet").'</h3>
   <form class="form-horizontal" role="form" action="admin.php" method="post">
      <fieldset>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="topicname">'.adm_translate("Intitulé").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="topicname" class="form-control" type="text" name="topicname" maxlength="20" value="'.$topicname.'" placeholder="'.adm_translate("cesiteestgénial").'" />
               <span class="help-block">'.adm_translate("(un simple nom sans espaces)").' - '.adm_translate("max caractères").' : <span id="countcar_topicname"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="topictext">'.adm_translate("Texte").'</label>
            <div class="col-sm-8 col-md-8">
               <textarea id="topictext" class="form-control" rows="3" name="topictext" maxlength="250" placeholder="'.adm_translate("ce site est génial").'" >'.$topictext.'</textarea>
               <span class="help-block infl">'.adm_translate("(description ou nom complet du Sujet)").' - '.adm_translate("max caractères").' : <span id="countcar_topictext"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="topicimage">'.adm_translate("Image").'</label>
            <div class="col-sm-8 col-md-8">
               <input id="topicimage" class="form-control" type="text" name="topicimage" maxlength="20" value="'.$topicimage.'" placeholder="genial.png" />
               <span class="help-block">'.adm_translate("(nom de l'image + extension)").' ('.$tipath.'). - '.adm_translate("max caractères").' : <span id="countcar_topicimage"></span></span>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4 col-md-4" for="topicadmin">'.adm_translate("Administrateur(s)").'</label>
            <div class="col-sm-8 col-md-8">
               <span class="help-block"><span id="countcar_topicadmin"></span></span>
               <input id="topicadmin" class="form-control" type="text" name="topicadmin" maxlength="255" value="'.$topicadmin.'" />
            </div>
         </div>
         <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
               <input type="hidden" name="op" value="topicmake" />
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;&nbsp;'.adm_translate("Ajouter un Sujet").'</button>
            </div>
         </div>
      </fieldset>
   </form>';
    echo'
<script type="text/javascript">
   //<![CDATA[
      var topid="";
      $(".topi").hover(function(){
         topid = $(this).parent().attr("id");
         topid=topid.substr (topid.search(/\d/))
         $button = $(\'<div id="shortcut-tools" class=""><a class="text-danger btn" href="admin.php?op=topicdelete&amp;topicid=\'+topid+\'&amp;ok=0" ><i class="fa fa-trash-o fa-2x"></i></a></div>\')
         $(this).append($button);
         $button.show();
      }, function(){
       $button.hide();
     });
   //]]>
</script>';
   $fv_parametres = '
   topicadmin: {
      validators: {
         callback: {
            message: "Please choose an administrator from the provided list.",
            callback: function(value, validator, $field) {
            diff="";
            var value = $field.val();
            if (value === "") {return true;}
            function split( n ) {
               return n.split( /,\s*/ );
            }
            diff = $(split(value)).not(admin).get();
            console.log(diff);
            if (diff!="") {return false;}
            return true;
            }
         }
      }
   },
   topicimage: {
      validators: {
         regexp: {
            regexp: /^[\w]+\\.(jpg|jpeg|png|gif)$/,
            message: "This must be a valid file name with one of this extension jpg, jpeg, png, gif."
         }
      }
   },
   topicname: {
      validators: {
         regexp: {
            regexp: /^[\w]$/,
            message: "This must be a simple word without space."
         }
      }
   },   
';
   echo auto_complete_multi('admin','aid','authors','topicadmin','');
   adminfieldinp($result);
   sql_free_result($result);
   adminfoot('fv',$fv_parametres,'','');
}

function topicedit($topicid) {
    global $hlpfile, $tipath, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg, $radminsuper;
    include("header.php");
    GraphicAdmin($hlpfile);
    $result = sql_query("select topicid, topicname, topicimage, topictext, topicadmin from ".$NPDS_Prefix."topics where topicid='$topicid'");
    list($topicid, $topicname, $topicimage, $topictext, $topicadmin) = sql_fetch_row($result);
    adminhead ($f_meta_nom, $f_titre, $adminimg);
    echo '<h3>'.adm_translate("Editer le Sujet :").' '.aff_langue($topictext).'</h3>';
    if ($topicimage!="") {
        echo '
        <div class="thumbnail">
            <img class="img-responsive " src="'.$tipath.$topicimage.'" alt="" />
        </div>';
    }
    echo '
    <form class="form-horizontal" role="form" action="admin.php" method="post">
    <fieldset>
        <div class="form-group">
            <label class="col-sm-4" for="topicname">'.adm_translate("Intitulé").'</label>
            <div class="col-sm-8">
                <input id="topicname" class="form-control" type="text" name="topicname" maxlength="20" value="'.$topicname.'" placeholder="'.adm_translate("cesiteestgénial").'" />
                <span class="help-block">'.adm_translate("(un simple nom sans espaces.)").' - '.adm_translate("max caractères").' : <span id="countcar_topicname"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4" for="topictext">'.adm_translate("Texte").'</label>
            <div class="col-sm-8">
                <textarea id="topictext" class="form-control" rows="3" name="topictext" maxlength="250" placeholder="'.adm_translate("ce site est génial").'">'.$topictext.'</textarea>
                <span class="help-block">'.adm_translate("(description ou nom complet du Sujet)").' - '.adm_translate("max caractères").' : <span id="countcar_topictext"></span></span>
            </div>
        </div>
        <div class="form-group">
        <label class="col-sm-4" for="topicimage">'.adm_translate("Image").'</label>
            <div class="col-sm-8">
                <input id="topicimage" class="form-control" type="text" name="topicimage" size="21" maxlength="20" value="'.$topicimage.'" placeholder="genial.png" />
                <span class="help-block">'.adm_translate("(nom de l'image + extension)").' ('.$tipath.'). - '.adm_translate("max caractères").' : <span id="countcar_topicimage"></span></span>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-4" for="topicadmin">'.adm_translate("Administrateur(s) du Sujet").'</label>
            <div class="col-sm-8 col-md-8">
               <div class="input-group">
                     <span class="input-group-btn">
                       <button class="btn btn-primary"><i class="fa fa-user fa-lg"></i></button>
                     </span>
                   <input id="topicadmin" class="form-control" type="text" name="topicadmin" maxlength="255" value="'.$topicadmin.'" />
               </div>
            </div>
        </div>
        
    </fieldset>
    <fieldset>
        <h4>'.adm_translate("Ajouter des Liens relatifs au Sujet : ").'</h4>
        <div class="form-group">
            <label class="col-sm-4" for="name">'.adm_translate("Nom du site").'</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="name" id="name" maxlength="30" />
                <span class="help-block">'.adm_translate("max caractères").' : <span id="countcar_name"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4" for="url">'.adm_translate("URL").'</label>
            <div class="col-sm-8">
               <input class="form-control" type="url" name="url" id="url" maxlength="200" placeholder="http://www.valideurl.org" />
               <span class="help-block">'.adm_translate("max caractères").' : <span id="countcar_url"></span></span>
            </div>
        </div>
    </fieldset>
    
    <input type="hidden" name="topicid" value="'.$topicid.'" />
    <input type="hidden" name="op" value="topicchange" />
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
         <div class="btn-group" role="group" aria-label="...">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;&nbsp;'.adm_translate("Sauver les modifications").'</button>
        <button class="btn btn-default" onclick="javascript:document.location.href=\'admin.php?op=topicsmanager\'">'.adm_translate("Retour en arrière").'</button>
        <button class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i>&nbsp;&nbsp;'.adm_translate("Effacer le Sujet !").'</button>
         </div>
        </div>
    </div>

   </form>
   <form id="fad_deltop" action="admin.php" method="post">
       <input type="hidden" name="topicid" value="'.$topicid.'" />
       <input type="hidden" name="op" value="topicdelete" />
   </form>';

    echo '<h3>'.adm_translate("Gérer les Liens Relatifs : ").' '.aff_langue($topictext).'</h3>';
    $res=sql_query("select rid, name, url from ".$NPDS_Prefix."related where tid='$topicid'");
    echo '
    <table id="tad_linkrel" data-toggle="table" data-striped="true">
        <thead>
            <th class="col-md-5" data-sortable="true" >'.adm_translate('Nom').'</th>
            <th class="col-md-5" data-sortable="true">'.adm_translate('Url').'</th>
            <th class="col-md-2">'.adm_translate('Fonctions').'</th>
       </thead>
       <tbody>
    ';
    while (list($rid, $name, $url) = sql_fetch_row($res)) {
       echo '
            <tr>
                <td>'.$name.'</td>
                <td><a href="'.$url.'" target="_blank">'.$url.'</a></td>
                <td>
                   <a href="admin.php?op=relatededit&amp;tid='.$topicid.'&amp;rid='.$rid.'" class="noir"><i class="fa fa-edit fa-lg" data-toggle="tooltip" title="'.adm_translate("Editer").'"></i></a>&nbsp;
                   <a href="'.$url.'" target="_blank"><i class="fa fa-external-link fa-lg"></i></a>&nbsp;
                   <a href="admin.php?op=relateddelete&amp;tid='.$topicid.'&amp;rid='.$rid.'" class=""><i class="fa fa-trash-o fa-lg text-danger" data-toggle="tooltip" title="'.adm_translate("Effacer").'"></i></a>
                </td>
            </tr>';
    }
    echo '
        </tbody>
    </table>';
/*    
   $vars = array_diff(get_defined_vars(),$vars);
   echo '<pre>';
   print_r ( $vars );
   echo '</pre>';
   */
   
   $fv_parametres = '
   topicadmin: {
      validators: {
         callback: {
            message: "Please choose an administrator from the provided list.",
            callback: function(value, validator, $field) {
            diff="";
            var value = $field.val();
            if (value === "") {return true;}
            function split( n ) {
               return n.split( /,\s*/ );
            }
            diff = $(split(value)).not(admin).get();
            console.log(diff);
            if (diff!="") {return false;}
            return true;
            }
         }
      }
   },
   topicimage: {
      validators: {
         regexp: {
            regexp: /^[\w]+\\.(jpg|jpeg|png|gif)$/,
            message: "This must be a valid file name with one of this extension jpg, jpeg, png, gif."
         }
      }
   },
   topicname: {
      validators: {
         regexp: {
            regexp: /^[a-z]+$/i,
            message: "This must be a simple word without space."
         }
      }
   },
   ';

   echo auto_complete_multi('admin','aid','authors','topicadmin','');
   adminfieldinp($result);
   adminfieldinp($res);
   adminfoot('fv',$fv_parametres,'','');
}

function relatededit($tid, $rid) {
   global $hlpfile, $tipath, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

   include("header.php");
   GraphicAdmin($hlpfile);
   $result=sql_query("select name, url from ".$NPDS_Prefix."related where rid='$rid'");
   list($name, $url) = sql_fetch_row($result);
   $result2=sql_query("select topictext, topicimage from ".$NPDS_Prefix."topics where topicid='$tid'");
   list($topictext, $topicimage) = sql_fetch_row($result2);

   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Sujet : ").' '.$topictext.'</h3>
   <h4>'.adm_translate("Editer les Liens Relatifs").'</h4>';
   if ($topicimage!="") {
      echo '
   <div class="thumbnail">
      <img class="img-responsive " src="'.$tipath.$topicimage.'" alt="'.$topictext.'" />
   </div>';}
   echo'
   <form class="form-horizontal" action="admin.php" method="post">
       <fieldset>
       <div class="form-group">
           <label class="control-label col-sm-4 col-md-4" for="name">'.adm_translate("Nom du site").'</label>
           <div class="col-sm-8 col-md-8">
               <input type="text" class="form-control" name="name" id="name" value="'.$name.'" maxlength="30" />
              <span class="help-block text-right"><span id="countcar_name"></span></span>
           </div>
       </div>
       <div class="form-group">
           <label class="control-label col-sm-4 col-md-4" for="url">'.adm_translate("URL").'</label>
           <div class="col-sm-8 col-md-8">
              <div class="input-group">
                 <span class="input-group-btn">
                      <button class="btn"><a href="'.$url.'" target="_blank"><i class="fa fa-external-link fa-lg"></i></a></button>
                 </span>
                 <input type="url" class="form-control" name="url" id="url" value="'.$url.'" maxlength="200" />
               </div>
               <span class="help-block text-right"><span id="countcar_url"></span></span>
       </div>
       <input type="hidden" name="op" value="relatedsave" />
       <input type="hidden" name="tid" value="'.$tid.'" />
       <input type="hidden" name="rid" value="'.$rid.'" />
       </fieldset>
       <div class="form-group">
           <div class="col-sm-offset-4 col-sm-8">
               <button class="btn btn-primary col-xs-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;'.adm_translate("Sauver les modifications").'</button>
           </div>
       </div>
   </form>';
   adminfieldinp($result);
   adminfoot('fv','','','');
}

function relatedsave($tid, $rid, $name, $url) {
    global $NPDS_Prefix;

    sql_query("update ".$NPDS_Prefix."related set name='$name', url='$url' where rid='$rid'");
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function relateddelete($tid, $rid) {
    global $NPDS_Prefix;

    sql_query("delete from ".$NPDS_Prefix."related where rid='$rid'");
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function topicmake($topicname, $topicimage, $topictext, $topicadmin) {
    global $NPDS_Prefix;

    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    sql_query("INSERT INTO ".$NPDS_Prefix."topics VALUES (NULL,'$topicname','$topicimage','$topictext','0', '$topicadmin')");
    global $aid; Ecr_Log("security", "topicMake ($topicname) by AID : $aid", "");
    
    $topicadminX = explode(",",$topicadmin);
    array_pop($topicadminX);
      for ($iX = 0; $iX < count($topicadminX); $iX++) {
         $nres = sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."droits WHERE d_aut_aid='$topicadminX[$iX]' and d_droits=11112"));
         if($nres == 0){
            sql_query("INSERT INTO ".$NPDS_Prefix."droits VALUES ('$topicadminX[$iX]', '2', '11112')");
         }
      }
    
    Header("Location: admin.php?op=topicsmanager#Add");
}

function topicchange($topicid, $topicname, $topicimage, $topictext, $topicadmin, $name, $url) {
   global $NPDS_Prefix;
   $topicadminX = explode(",",$topicadmin);
   array_pop($topicadminX);


   $res = sql_query("SELECT * FROM ".$NPDS_Prefix."droits WHERE d_droits=11112 and d_fon_fid=2");
   $d=array();$topad=array();
   while ($d = sql_fetch_row($res)) {$topad[] = $d[0];}

   foreach ($topicadminX as $value){
      if (!in_array($value, $topad)) sql_query("INSERT INTO ".$NPDS_Prefix."droits VALUES ('$value', '2', '11112')");
   }
        print_r ( $topad );
   
   foreach ($topad as $value){//pour chaque droit adminsujet on regarde le nom de l'adminsujet
      if (!in_array($value, $topicadminX)) {//si le nom de l'adminsujet n'est pas dans les nouveaux adminsujet
      //on cherche si il administre un aure sujet
      $resu=sql_query("SELECT * FROM ".$NPDS_Prefix."topics WHERE topicadmin REGEXP '[[:<:]]".$value."[[:>:]]'");
      $nbrow = sql_num_rows($resu);
      echo 'nb ligne'.$nbrow; //si > 1 il administre un autre sujet
      list($tid) = sql_fetch_row($resu);
      echo $tid.'$tid';
      if( ($nbrow==1) and ($topicid==$tid) ) {sql_query("DELETE FROM ".$NPDS_Prefix."droits WHERE d_aut_aid='$value' and d_droits=11112 and d_fon_fid=2");echo 'zarma';}
      }
   }

    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    $name = stripslashes(FixQuotes($name));
    $url = stripslashes(FixQuotes($url));
    sql_query("update ".$NPDS_Prefix."topics set topicname='$topicname', topicimage='$topicimage', topictext='$topictext', topicadmin='$topicadmin' where topicid='$topicid'");
    global $aid; Ecr_Log("security", "topicChange ($topicname, $topicid) by AID : $aid", "");
    if ($name) {
       sql_query("insert into ".$NPDS_Prefix."related VALUES (NULL, '$topicid','$name','$url')");
    }

//    Header("Location: admin.php?op=topicedit&topicid=$topicid");//commenter juste pour debug
}

function topicdelete($topicid, $ok=0) {
   global $NPDS_Prefix;

   if ($ok==1) {
      global $aid;
      $result=sql_query("select sid from ".$NPDS_Prefix."stories where topic='$topicid'");
      list($sid) = sql_fetch_row($result);
      sql_query("delete from ".$NPDS_Prefix."stories where topic='$topicid'");
      Ecr_Log("security", "topicDelete (stories, $topicid) by AID : $aid", "");
      sql_query("delete from ".$NPDS_Prefix."topics where topicid='$topicid'");
      Ecr_Log("security", "topicDelete (topic, $topicid) by AID : $aid", "");
      sql_query("delete from ".$NPDS_Prefix."related where tid='$topicid'");
      Ecr_Log("security", "topicDelete (related, $topicid) by AID : $aid", "");
      // commentaires
      if (file_exists("modules/comments/article.conf.php")) {
         include ("modules/comments/article.conf.php");
         sql_query("DELETE FROM ".$NPDS_Prefix."posts WHERE forum_id='$forum' and topic_id='$topic'");
         Ecr_Log("security", "topicDelete (comments, $topicid) by AID : $aid", "");
      }
      Header("Location: admin.php?op=topicsmanager");
   } else {
   global $hlpfile, $tipath, $topicimage, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   $result2=sql_query("select topicimage, topictext from ".$NPDS_Prefix."topics where topicid='$topicid'");
   list($topicimage, $topictext) = sql_fetch_row($result2);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3 class="text-danger">'.adm_translate("Effacer le Sujet").' : '.aff_langue($topictext).'</h3>';
   if ($topicimage!="") {echo '
   <div class="thumbnail">
      <img class="img-responsive" src="'.$tipath.$topicimage.'" alt="" />
   </div>';
   }
   echo'
   <div class="alert alert-danger lead" role="alert">
      <p>'.adm_translate("Etes-vous sûr de vouloir effacer ce sujet ?").' : '.aff_langue($topictext).'</p>
      <p>'.adm_translate("Ceci effacera tous ses articles et ses commentaires !").'</p>
      <p><a class="btn btn-danger" href="admin.php?op=topicdelete&amp;topicid='.$topicid.'&amp;ok=1">'.adm_translate("Oui").'</a>&nbsp;<a class="btn btn-primary"href="admin.php?op=topicsmanager">'.adm_translate("Non").'</a></p>
   </div>';
   adminfoot('','','','');
   }
}

switch ($op) {
   case "topicsmanager":
        topicsmanager();
        break;

   case "topicedit":
        topicedit($topicid);
        break;

   case "topicmake":
        topicmake($topicname, $topicimage, $topictext, $topicadmin);
        break;

   case "topicdelete":
        topicdelete($topicid, $ok);
        break;

   case "topicchange":
        topicchange($topicid, $topicname, $topicimage, $topictext, $topicadmin, $name, $url);
        break;

   case "relatedsave":
        relatedsave($tid, $rid, $name, $url);
        break;

   case "relatededit":
        relatededit($tid, $rid);
        break;

   case "relateddelete":
        relateddelete($tid, $rid);
        break;
}
?>