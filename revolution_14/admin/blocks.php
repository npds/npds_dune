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
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='blocks';
$f_titre = adm_translate('Gestion des blocs');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droitglobal $NPDS_Prefix;

global $language;
$hlpfile = "manuels/$language/blocks.html";

function groupe($groupe) {
   $les_groupes=explode(",",$groupe);
   $mX=liste_group();
   $nbg=0; $str="";
   while (list($groupe_id, $groupe_name)=each($mX)) {
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) {$selectionne=1;}
         }
      }
      if ($selectionne==1) {
         $str.='
         <option value="'.$groupe_id.'" selected="selected">'.$groupe_name.'</option>';
      } else {
         $str.='
         <option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      }
      $nbg++;
   }
   if ($nbg>5) {$nbg=5;}
   return ('
   <select multiple="multiple" class="form-control" name="Mmember[]" size="'.$nbg.'">
   '.$str.'
   </select>');
}

function droits_bloc($member) {
   echo '
   <div class="form-group">
      <label class="radio-inline text-danger">';
   if ($member==-127) {$checked=' checked="checked"';} else {$checked='';}
   echo '
         <input type="radio" name="members" value="-127" '.$checked.' />'.adm_translate("Administrateurs").'
      </label>
      <label class="radio-inline text-danger">';
   if ($member==-1) {$checked=' checked="checked"';} else {$checked='';}
   echo '
         <input type="radio" name="members" value="-1" '.$checked.' />'.adm_translate("Anonymes").'
      </label>';
   echo '
      <label class="radio-inline text-danger">';
   if ($member>0) {
      echo '
         <input type="radio" name="members" value="1" checked="checked" />'.adm_translate("Membres").'
      </label>
      <label class="radio-inline">
         <input type="radio" name="members" value="0" />'.adm_translate("Tous").'
      </label>
   </div>
   <div class="form-group">
      <label for="Mmember[]">'.adm_translate("Groupes").'</label>';
      echo groupe($member).'
   </div>';
   } else {
      if ($member==0) {$checked=' checked="checked"';} else {$checked='';}
      echo '
      <input type="radio" name="members" value="1" />'.adm_translate("Membres").'
      </label>
      <label class="radio-inline">
         <input type="radio" name="members" value="0"'.$checked.' />'.adm_translate("Tous").'
      </label>
   </div>
   <div class="form-group">
   <label for="Mmember[]">'.adm_translate("Groupes").'</label>';
      echo groupe($member).'
   </div>';
   }
}

function blocks() {
  global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg, $aid;
  
  //==> controle droit
//admindroits($aid,$f_meta_nom);
//<== controle droit
  include("header.php");
  include_once ("lib/togglediv.class.php");
  GraphicAdmin($hlpfile);
  adminhead ($f_meta_nom, $f_titre, $adminimg);

   echo '
   <h3>'.adm_translate("Edition des Blocs de gauche").'</h3>';
   $result = sql_query("select id, title, content, member, Lindex, cache, actif, aide, css from ".$NPDS_Prefix."lblocks order by Lindex ASC");
   $num_row=sql_num_rows($result);
   $toggle = new ToggleDiv($num_row);
   if ($num_row>0) {
      echo '
   <table id="tad_blocgauc" class="table table-hover table-striped" >
      <thead>
         <tr>
            <th><span class="togxy"><i class="fa fa-navicon" title="'.adm_translate("Déplier la liste la liste").'"></i></span>&nbsp;'.adm_translate("Titre").'</th>
            <th>'.adm_translate("Actif").'</th>
            <th>Index</th>
            <th>'.adm_translate("Rétention").'</th>
            <th>ID</th>
         </tr>
      </thead>
      <tbody>';
      while (list($id, $title, $content, $member, $Lindex, $Scache, $Sactif, $BLaide, $css) = sql_fetch_row($result)) {
         $funct="";
         if ($title=="") {
            //$title=adm_translate("Sans nom");
            $pos_func=strpos($content,"function#");
            $pos_nl=strpos($content,chr(13),$pos_func);
            if ($pos_func!==false) {
               $funct="<span style=\"font-size: 10px;\"> (";
               if ($pos_nl!==false)
                  $funct.=substr($content,$pos_func, $pos_nl-$pos_func);
               else
                  $funct.=substr($content,$pos_func);
               $funct.=")</span>";
            }
            $funct=adm_translate("Sans nom").$funct;
         }
         if ($Sactif) 
         echo '
         <tr class="success">'; 
         else 
         echo '
         <tr class="danger">';
         echo '
            <td align="left">';
         echo '<span class="tog" id="show_bloga_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_bloga_'.$id.'" class="fa fa-plus-square-o" ></i></span>&nbsp;';
         echo aff_langue($title).' '.$funct.'</td>';
         if ($Sactif)
            echo '
         <td align="right">'.adm_translate("Oui").'</td>';
         else
            echo '
         <td class="text-danger" align="right">'.adm_translate("Non").'</td>';
         echo '
         <td align="right">'.$Lindex.'</td>
         <td align="right">'.$Scache.'</td>
         <td align="right">'.$id.'</td>
         </tr>
         <tr>
         <td id="bloga_'.$id.'" class="togx" style="display:none;" colspan="5">
            <form id="fad_bloga_'.$id.'" action="admin.php" method="post">
               <div class="row">
                  <div class="col-md-8">
                     <fieldset>
                        <legend>'.adm_translate("Contenu").'</legend>
                        <div class="form-group">
                           <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
                           <input class="form-control" type="text" name="title" maxlength="255" value="'.$title.'" />
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="content">'.adm_translate("Contenu").'</label>
                           <textarea class="form-control" rows="5" name="content">'.$content.'</textarea>
                           <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="BLaide">'.adm_translate("Aide en ligne de ce bloc").'</label>
                           <textarea class="form-control" rows="2" name="BLaide">'.$BLaide.'</textarea>
                        </div>
                     </fieldset>
                     <fieldset>
                        <legend>'.adm_translate("Droits").'</legend>';
                     echo droits_bloc($member);
                     echo '
                     </fieldset>
                     <div class="form-group">
                        <select class="form-control" name="op">
                           <option value="changelblock" selected="selected">'.adm_translate("Modifier un Bloc gauche").'</option>
                           <option value="deletelblock">'.adm_translate("Effacer un Bloc gauche").'</option>
                           <option value="droitelblock">'.adm_translate("Transférer à Droite").'</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <fieldset>
                        <legend>'.adm_translate("Param&#xE8;tres").'</legend>
                        <div class="form-group">
                           <label class="form-control-label" for="Lindex">Index</label>
                           <input class="form-control" type="number" name="Lindex" max="9999" value="'.$Lindex.'" />
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="Scache">'.adm_translate("Rétention").'</label>
                           <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="'.$Scache.'" />
                           <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                        </div>
                        <div class="form-group">
                           <label class="checkbox-inline" for="Sactif">
                           <input type="checkbox" name="Sactif" value="ON" ';
                           if ($Sactif) echo 'checked="checked" ';
                           echo '/>'.adm_translate("Activer le Bloc").'
                           </label>
                        </div>
                        <div class="form-group">
                           <label class="checkbox-inline" for="css">
                           <input type="checkbox" name="css" value="1" ';
                           if ($css=="1") echo 'checked="checked" ';
                           echo '/>'.adm_translate("CSS Specifique").'
                           </label>
                        </div>
                     </fieldset>
                  </div>
                  <input type="hidden" name="id" value="'.$id.'" />
               </div>
               <div class="row">
                  <div class="col-xs-12"
                     <div class="form-group">
                        <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Ok").'</button>
                     </div>
                  </div>
               </div>
            </form>
            <script type="text/javascript">
            //<![CDATA[
               tog(\'bloga_'.$id.'\',\'show_bloga_'.$id.'\',\'hide_bloga_'.$id.'\');
            //]]>
            </script>
            </td>
         </tr>';
      }
      echo '
      </tbody>
   </table>';
   }

   echo '
   <h3>'.adm_translate("Edition des Blocs de droite").'</h3>';
   $result = sql_query("select id, title, content, member, Rindex, cache, actif, aide, css  from ".$NPDS_Prefix."rblocks order by Rindex ASC");
   $num_row=sql_num_rows($result);
   if ($num_row>0) {
      echo '
   <script type="text/javascript">
      //<![CDATA[
         $("#adm_workarea").on("click", "span.togxy",function() {
            $(".fa.fa-navicon").attr("title","'.adm_translate("Replier la liste").'")
            $("#tad_blocdroi td.togx").attr("style","display: none")
            $("#tad_blocdroi span.tog i").attr("class","fa fa-plus-square-o")
            $("#tad_blocdroi span.tog").attr("title","'.adm_translate("Déplier la liste").'")
            $( "#tad_blocdroi span.tog" ).each(function( index ) {
               var idi= $(this).attr("id")
               var idir = idi.replace("hide", "show");
               $(this).attr("id",idir)
                 console.log( index + ": " + $( this ).text() + idir );
            });
         });
         //]]>
   </script>';

      echo '
   <table id="tad_blocdroi" class="table table-hover table-striped" >
      <thead>
         <tr>
            <th><span class="togxy"><i class="fa fa-navicon" title="'.adm_translate("Déplier la liste la liste").'"></i></span>&nbsp;'.adm_translate("Titre").'</th>
            <th>'.adm_translate("Actif").'</th>
            <th>Index</th>
            <th>'.adm_translate("Rétention").'</th>
            <th>ID</th>
         </tr>
      </thead>
      <tbody>';
      while (list($id, $title, $content, $member, $Rindex, $Scache, $Sactif, $BRaide, $css) = sql_fetch_row($result)) {
         $funct="";
         if ($title=="") {
            //$title=adm_translate("Sans nom");
            $pos_func=strpos($content,"function#");
            $pos_nl=strpos($content,chr(13),$pos_func);
            if ($pos_func!==false) {
               $funct="<span style=\"font-size: 10px;\"> (";
               if ($pos_nl!==false)
                  $funct.=substr($content,$pos_func, $pos_nl-$pos_func);
               else
                  $funct.=substr($content,$pos_func);
               $funct.=")</span>";
            }
            $funct=adm_translate("Sans nom").$funct;
         }
         if ($Sactif) 
         echo '
      <tr class="success">'; 
         else 
         echo '
      <tr class="danger">';
         echo '
         <td align="left">';
         echo '<span class="tog" id="show_blodr_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_blodr_'.$id.'" class="fa fa-plus-square-o" ></i></span>&nbsp;';
         echo aff_langue($title).' '.$funct.'</td>';
         if ($Sactif)
            echo '
         <td align="right">'.adm_translate("Oui").'</td>';
         else
            echo '
         <td class="text-danger" align="right">'.adm_translate("Non").'</td>';
         echo '
         <td align="right">'.$Rindex.'</td>
         <td align="right">'.$Scache.'</td>
         <td align="right">'.$id.'</td>
      </tr>
      <tr>
         <td id="blodr_'.$id.'" class="togx" style="display:none;" colspan="5">
            <form id="fad_blodr_'.$id.'" action="admin.php" method="post">
               <div class="row">
                  <div class="col-md-8">
                     <fieldset>
                        <legend>'.adm_translate("Contenu").'</legend>
                        <div class="form-group">
                           <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
                           <input class="form-control" type="text" name="title" maxlength="255" value="'.$title.'" />
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="content">'.adm_translate("Contenu").'</label>
                           <textarea class="form-control" cols="70" rows="5" name="content">'.$content.'</textarea>
                           <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="BRaide">'.adm_translate("Aide en ligne de ce bloc").'</label>
                           <textarea class="form-control" rows="2" name="BRaide">'.$BRaide.'</textarea>
                        </div>
                     </fieldset>
                     <fieldset>
                        <legend>'.adm_translate("Droits").'</legend>';
                     echo droits_bloc($member);
                     echo '
                     </fieldset>
                     <div class="form-group">
                        <select class="form-control" name="op">
                           <option value="changerblock" selected="selected">'.adm_translate("Modifier un Bloc droit").'</option>
                           <option value="deleterblock">'.adm_translate("Effacer un Bloc droit").'</option>
                           <option value="gaucherblock">'.adm_translate("Transférer à Gauche").'</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <fieldset>
                        <legend>'.adm_translate("Param&#xE8;tres").'</legend>
                        <div class="form-group">
                           <label class="form-control-label" for="Rindex">Index</label>
                           <input class="form-control" type="number" name="Rindex" min="0" max="9999" value="'.$Rindex.'" />
                        </div>
                        <div class="form-group">
                           <label class="form-control-label" for="Scache">'.adm_translate("Rétention").'</label>
                           <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="'.$Scache.'" />
                           <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                        </div>
                        <div class="form-group">
                           <label class="checkbox-inline" for="Sactif">
                           <input type="checkbox" name="Sactif" value="ON" ';
                           if ($Sactif) echo 'checked="checked" ';
                           echo '/>'.adm_translate("Activer le Bloc").'
                           </label>
                        </div>
                        <div class="form-group">
                           <label class="checkbox-inline" for="css">
                           <input type="checkbox" name="css" value="1" ';
                           if ($css=="1") echo 'checked="checked" ';
                           echo '/>'.adm_translate("CSS Specifique").'
                           </label>
                        </div>
                     </fieldset>
                  </div>
                  <input type="hidden" name="id" value="'.$id.'" />
               </div>
               <div class="row">
                  <div class="col-xs-12"
                     <div class="form-group">
                        <button id="" class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Ok").'</button>
                     </div>
                  </div>
               </div>
            </form>
            <script type="text/javascript">
            //<![CDATA[
               tog(\'blodr_'.$id.'\',\'show_blodr_'.$id.'\',\'hide_blodr_'.$id.'\');
            //]]>
            </script>
            </td>
         </tr>';
      }
      echo '
      </tbody>
   </table>';
   }
   echo '
   <h3>'.adm_translate("Créer un nouveau Bloc").'</h3>
   <form id="fad_newblock" action="admin.php" method="post" name="adminForm">
      <div class="row">
         <div class="col-md-8">
            <fieldset>
               <legend>'.adm_translate("Contenu").'</legend>
               <div class="form-group">
                  <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
                  <input class="form-control" type="text" name="title" id="title" maxlength="255" />
               </div>
               <div class="form-group">
                  <label class="form-control-label" for="xtext">'.adm_translate("Contenu").'</label>
                  <textarea class="form-control" name="xtext" id="xtext" rows="5"></textarea>
                  <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
               </div>
               <div class="form-group">
                  <label class="form-control-label" for="Baide">'.adm_translate("Aide en ligne").'</label>
                  <textarea class="form-control" rows="2" name="Baide" id="Baide"></textarea>
               </div>
            </fieldset>
            <fieldset>
               <legend>'.adm_translate("Droits").'</legend>';
               echo droits_bloc("0");
               echo '
            </fieldset>
            <div class="form-group">
               <label class="form-control-label" for="op">'.adm_translate("Position").'</label>
               <div>
                  <label class="radio-inline">
                     <input type="radio" name="op" value="makelblock" checked="checked" />'.adm_translate("Créer un Bloc gauche").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="op" value="makerblock" /> '.adm_translate("Créer un Bloc droite").'
                  </label>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <fieldset>
               <legend>'.adm_translate("Param&#xE8;tres").'</legend>
                  <div class="form-group">
                     <label class="form-control-label" for="index">Index</label>
                     <input class="form-control" type="number" name="index" id="index" min="0" max="9999" />
                  </div>
                  <div class="form-group">
                     <label class="form-control-label" for="Scache">'.adm_translate("Rétention").'</label>
                     <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="60" />
                     <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                  </div>
                  <div class="form-group">
                     <label class="checkbox-inline text-danger" for="SHTML">
                        <input class="" type="checkbox" name="SHTML" id="SHTML" value="ON" />HTML
                     </label>
                     <label class="checkbox-inline text-danger" for="CSS">
                        <input class="" type="checkbox" name="CSS" id="CSS" value="ON" />CSS
                     </label>
                  </div>
            </fieldset>
         </div>
      </div>
      <div class="form-group">
         <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Valider").'</button>
     </div>
   </form>';
  include("footer.php");
}

switch ($op) {
   case "blocks":
        blocks();
        break;
}
?>