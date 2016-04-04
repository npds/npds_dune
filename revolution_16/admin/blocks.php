<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
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
//<== controle droit

global $NPDS_Prefix, $language;

$hlpfile = "manuels/$language/blocks.html";

function groupe($groupe) {
   $les_groupes=explode(',',$groupe);
   $mX=liste_group();
   $nbg=0; $str='';
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
   <select multiple="multiple" class="c-select form-control" name="Mmember[]" size="'.$nbg.'">
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
   <div class="form-group row">
      <label for="Mmember[]" class="form-control-label col-sm-12">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">
         '.groupe($member).'
      </div>
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
   <div class="form-group row">
      <label for="Mmember[]" class="form-control-label col-sm-12">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">
         '.groupe($member).'
      </div>
   </div>';
   }
}

function blocks() {
  global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg, $aid;
  include("header.php");
  GraphicAdmin($hlpfile);
  adminhead ($f_meta_nom, $f_titre, $adminimg);

   echo '
   <hr />
   <h3>'.adm_translate("Edition des Blocs de gauche").'</h3>';
   $result = sql_query("SELECT id, title, content, member, Lindex, cache, actif, aide, css FROM ".$NPDS_Prefix."lblocks ORDER BY Lindex ASC");
   $num_row=sql_num_rows($result);
   if ($num_row>0) {
      echo '
   <script type="text/javascript">
      //<![CDATA[
         $("#adm_workarea").on("click", "a.togxyg",function() {
            $(".fa.fa-navicon").attr("title","'.adm_translate("Replier la liste").'")
            $("#tad_blocgauc td.togxg").attr("style","display: none")
            $("#tad_blocgauc a.tog i").attr("class","fa fa-caret-down fa-lg")
            $("#tad_blocgauc a.tog").attr("title","'.adm_translate("Déplier la liste").'")
            $( "#tad_blocgauc a.tog" ).each(function(index) {
               var idi= $(this).attr("id")
               var idir = idi.replace("hide", "show");
               $(this).attr("id",idir)
            });
         });
         //]]>
   </script>
   <table id="tad_blocgauc" class="table table-hover table-striped " >
      <thead>
         <tr>
            <th><a class="togxyg"><i class="fa fa-navicon" title="'.adm_translate("Déplier la liste").'"></i></a>&nbsp;'.adm_translate("Titre").'</th>
            <th class="hidden-sm-down">'.adm_translate("Actif").'</th>
            <th>Index</th>
            <th>'.adm_translate("Rétention").'</th>
            <th>ID</th>
         </tr>
      </thead>
      <tbody>';
      while (list($id, $title, $content, $member, $Lindex, $Scache, $Sactif, $BLaide, $css) = sql_fetch_row($result)) {
         $funct='';
         if ($title=='') {
            //$title=adm_translate("Sans nom");
            $pos_func=strpos($content,'function#');
            $pos_nl=strpos($content,chr(13),$pos_func);
            if ($pos_func!==false) {
               $funct='<span style="font-size: 0.65rem;"> (';
               if ($pos_nl!==false)
                  $funct.=substr($content,$pos_func, $pos_nl-$pos_func);
               else
                  $funct.=substr($content,$pos_func);
               $funct.=')</span>';
            }
            $funct=adm_translate("Sans nom").$funct;
         }
         if ($Sactif) 
         echo '
         <tr class="table-success">'; 
         else 
         echo '
         <tr class="table-danger">';
         echo '
            <td align="left">';
         echo '<a class="tog" id="show_bloga_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_bloga_'.$id.'" class="fa fa-caret-down fa-lg" ></i></a>&nbsp;';
         echo aff_langue($title).' '.$funct.'</td>';
         if ($Sactif)
            echo '
            <td align="right" class="hidden-sm-down" >'.adm_translate("Oui").'</td>';
         else
            echo '
            <td class="text-danger hidden-sm-down" align="right">'.adm_translate("Non").'</td>';
         echo '
            <td align="right">'.$Lindex.'</td>
            <td align="right">'.$Scache.'</td>
            <td align="right">'.$id.'</td>
         </tr>
         <tr>
            <td id="bloga_'.$id.'" class="togxg" style="display:none;" colspan="5">
               <form id="fad_bloga_'.$id.'" action="admin.php" method="post">
                  <div class="row">
                     <div class="col-md-8">
                        <fieldset>
                           <legend>'.adm_translate("Contenu").'</legend>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="title">'.adm_translate("Titre").'</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="text" name="title" maxlength="255" value="'.$title.'" />
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="content">'.adm_translate("Contenu").'</label>
                              <div class="col-sm-12">
                                 <textarea class="form-control" rows="5" name="content">'.$content.'</textarea>
                                 <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="BLaide">'.adm_translate("Aide en ligne de ce bloc").'</label>
                              <div class="col-sm-12">
                                 <textarea class="form-control" rows="2" name="BLaide">'.$BLaide.'</textarea>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend>'.adm_translate("Droits").'</legend>';
                        echo droits_bloc($member);
                        echo '
                        </fieldset>
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <select class="c-select form-control" name="op">
                                 <option value="changelblock" selected="selected">'.adm_translate("Modifier un Bloc gauche").'</option>
                                 <option value="deletelblock">'.adm_translate("Effacer un Bloc gauche").'</option>
                                 <option value="droitelblock">'.adm_translate("Transférer à Droite").'</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <fieldset>
                           <legend>'.adm_translate("Paramètres").'</legend>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="Lindex">Index</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="number" name="Lindex" max="9999" value="'.$Lindex.'" />
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="Scache">'.adm_translate("Rétention").'</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="'.$Scache.'" />
                                 <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <label class="checkbox-inline" for="Sactif">
                                    <input type="checkbox" name="Sactif" value="ON" ';
                              if ($Sactif) echo 'checked="checked" ';
                              echo '/>'.adm_translate("Activer le Bloc").'
                                 </label>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <label class="checkbox-inline" for="css">
                                 <input type="checkbox" name="css" value="1" ';
                              if ($css=='1') echo 'checked="checked" ';
                              echo '/>'.adm_translate("CSS Specifique").'
                                 </label>
                              </div>
                           </div>
                        </fieldset>
                     </div>
                     <input type="hidden" name="id" value="'.$id.'" />
                  </div>
                  <div class="form-group row">
                     <div class="col-xs-12">
                        <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Ok").'</button>
                     </div>
                  </div>
               </form>
               <script type="text/javascript">
               //<![CDATA[
                  tog("bloga_'.$id.'","show_bloga_'.$id.'","hide_bloga_'.$id.'");
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
   <hr />
   <h3>'.adm_translate("Edition des Blocs de droite").'</h3>';
   $result = sql_query("SELECT id, title, content, member, Rindex, cache, actif, aide, css  FROM ".$NPDS_Prefix."rblocks ORDER BY Rindex ASC");
   $num_row=sql_num_rows($result);
   if ($num_row>0) {
      echo '
   <script type="text/javascript">
      //<![CDATA[
         $("#adm_workarea").on("click", "a.togxyd",function() {
            $(".fa.fa-navicon").attr("title","'.adm_translate("Replier la liste").'")
            $("#tad_blocdroi td.togxd").attr("style","display: none")
            $("#tad_blocdroi a.tog i").attr("class","fa fa-caret-down fa-lg")
            $("#tad_blocdroi a.tog").attr("title","'.adm_translate("Déplier la liste").'")
            $( "#tad_blocdroi a.tog" ).each(function(index) {
               var idi= $(this).attr("id")
               var idir = idi.replace("hide", "show");
               $(this).attr("id",idir)
            });
         });
         //]]>
   </script>
   <table id="tad_blocdroi" class="table table-hover table-striped " >
      <thead>
         <tr>
            <th><a class="togxyd"><i class="fa fa-navicon fa-lg" title="'.adm_translate("Déplier la liste la liste").'"></i></a>&nbsp;'.adm_translate("Titre").'</th>
            <th class="hidden-sm-down">'.adm_translate("Actif").'</th>
            <th>Index</th>
            <th>'.adm_translate("Rétention").'</th>
            <th>ID</th>
         </tr>
      </thead>
      <tbody>';
      while (list($id, $title, $content, $member, $Rindex, $Scache, $Sactif, $BRaide, $css) = sql_fetch_row($result)) {
         $funct='';
         if ($title=='') {
            //$title=adm_translate("Sans nom");
            $pos_func=strpos($content,'function#');
            $pos_nl=strpos($content,chr(13),$pos_func);
            if ($pos_func!==false) {
               $funct='<span style="font-size: 0.65rem"> (';
               if ($pos_nl!==false)
                  $funct.=substr($content,$pos_func, $pos_nl-$pos_func);
               else
                  $funct.=substr($content,$pos_func);
               $funct.=')</span>';
            }
            $funct=adm_translate("Sans nom").$funct;
         }
         if ($Sactif) 
         echo '
         <tr class="table-success">'; 
         else 
         echo '
         <tr class="table-danger">';
         echo '
            <td align="left">';
         echo '<a class="tog" id="show_blodr_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_blodr_'.$id.'" class="fa fa-caret-down fa-lg" ></i></a>&nbsp;';
         echo aff_langue($title).' '.$funct.'</td>';
         if ($Sactif)
            echo '
            <td align="right" class="hidden-sm-down" >'.adm_translate("Oui").'</td>';
         else
            echo '
            <td class="text-danger hidden-sm-down" align="right">'.adm_translate("Non").'</td>';
         echo '
            <td align="right">'.$Rindex.'</td>
            <td align="right">'.$Scache.'</td>
            <td align="right">'.$id.'</td>
         </tr>
         <tr>
            <td id="blodr_'.$id.'" class="togxd" style="display:none;" colspan="5">
               <form id="fad_blodr_'.$id.'" action="admin.php" method="post">
                  <div class="row">
                     <div class="col-md-8">
                        <fieldset>
                           <legend>'.adm_translate("Contenu").'</legend>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="title">'.adm_translate("Titre").'</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="text" name="title" maxlength="255" value="'.$title.'" />
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="content">'.adm_translate("Contenu").'</label>
                              <div class="col-sm-12">
                                 <textarea class="form-control" cols="70" rows="5" name="content">'.$content.'</textarea>
                                 <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label col-sm-12" for="BRaide">'.adm_translate("Aide en ligne de ce bloc").'</label>
                              <div class="col-sm-12">
                                 <textarea class="form-control" rows="2" name="BRaide">'.$BRaide.'</textarea>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend>'.adm_translate("Droits").'</legend>';
         echo droits_bloc($member);
         echo '
                        </fieldset>
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <select class="c-select form-control" name="op">
                                 <option value="changerblock" selected="selected">'.adm_translate("Modifier un Bloc droit").'</option>
                                 <option value="deleterblock">'.adm_translate("Effacer un Bloc droit").'</option>
                                 <option value="gaucherblock">'.adm_translate("Transférer à Gauche").'</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <fieldset>
                           <legend>'.adm_translate("Paramètres").'</legend>
                           <div class="form-group row">
                              <label class="form-control-label" for="Rindex">Index</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="number" name="Rindex" min="0" max="9999" value="'.$Rindex.'" />
                              </div>
                           </div>
                           <div class="form-group row">
                              <label class="form-control-label" for="Scache">'.adm_translate("Rétention").'</label>
                              <div class="col-sm-12">
                                 <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="'.$Scache.'" />
                                 <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <label class="checkbox-inline" for="Sactif">
                                    <input type="checkbox" name="Sactif" value="ON" ';
         if ($Sactif) echo 'checked="checked" ';
         echo '/>'.adm_translate("Activer le Bloc").'
                                 </label>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <label class="checkbox-inline" for="css">
                                    <input type="checkbox" name="css" value="1" ';
         if ($css=="1") echo 'checked="checked" ';
         echo '/>'.adm_translate("CSS Specifique").'
                                 </label>
                              </div>
                           </div>
                        </fieldset>
                     </div>
                     <input type="hidden" name="id" value="'.$id.'" />
                  </div>
                  <div class="form-group row">
                     <div class="col-xs-12">
                        <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Ok").'</button>
                     </div>
                  </div>
               </form>
               <script type="text/javascript">
               //<![CDATA[
                  tog("blodr_'.$id.'","show_blodr_'.$id.'","hide_blodr_'.$id.'");
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
   <hr />
   <h3>'.adm_translate("Créer un nouveau Bloc").'</h3>
   <form id="fad_newblock" action="admin.php" method="post" name="adminForm">
      <div class="row">
         <div class="col-md-8">
            <fieldset>
               <legend>'.adm_translate("Contenu").'</legend>
               <div class="form-group row">
                  <label class="form-control-label col-sm-12" for="title">'.adm_translate("Titre").'</label>
                  <div class="col-sm-12">
                     <input class="form-control" type="text" name="title" id="title" maxlength="255" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="form-control-label col-sm-12" for="xtext">'.adm_translate("Contenu").'</label>
                  <div class="col-sm-12">
                     <textarea class="form-control" name="xtext" id="xtext" rows="5"></textarea>
                     <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="form-control-label col-sm-12" for="Baide">'.adm_translate("Aide en ligne").'</label>
                  <div class="col-sm-12">
                     <textarea class="form-control" rows="2" name="Baide" id="Baide"></textarea>
                  </div>
               </div>
            </fieldset>
            <fieldset>
               <legend>'.adm_translate("Droits").'</legend>';
   echo droits_bloc("0");
   echo '
            </fieldset>
            <div class="form-group row">
               <label class="form-control-label col-sm-12" for="op">'.adm_translate("Position").'</label>
               <div class="col-sm-12">
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
               <legend>'.adm_translate("Paramètres").'</legend>
               <div class="form-group row">
                  <label class="form-control-label col-sm-12" for="index">Index</label>
                  <div class="col-sm-12">
                     <input class="form-control" type="number" name="index" id="index" min="0" max="9999" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="form-control-label col-sm-12" for="Scache">'.adm_translate("Rétention").'</label>
                  <div class="col-sm-12">
                     <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="60" />
                     <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-12">
                     <label class="checkbox-inline text-danger" for="SHTML">
                        <input class="" type="checkbox" name="SHTML" id="SHTML" value="ON" />HTML
                     </label>
                     <label class="checkbox-inline text-danger" for="CSS">
                        <input class="" type="checkbox" name="CSS" id="CSS" value="ON" />CSS
                     </label>
                  </div>
               </div>
            </fieldset>
         </div>
      </div>
      <div class="form-groupe row">
         <div class="col-sm-12">
            <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Valider").'</button>
        </div>
     </div>
   </form>';
   adminfoot('fv','','','');
}

switch ($op) {
   case 'blocks':
        blocks();
        break;
}
?>