<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2023 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='blocks';
$f_titre = adm_translate('Gestion des blocs');
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/blocks.html";

function groupe($groupe) {
   $les_groupes=explode(',',$groupe);
   $mX=liste_group();
   $nbg=0; $str='';
   foreach($mX as $groupe_id => $groupe_name) {
      $selectionne=0;
      if ($les_groupes) {
         foreach ($les_groupes as $groupevalue) {
            if (($groupe_id==$groupevalue) and ($groupe_id!=0)) $selectionne=1;
         }
      }
      if ($selectionne==1)
         $str.='
         <option value="'.$groupe_id.'" selected="selected">'.$groupe_name.'</option>';
      else
         $str.='
         <option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      $nbg++;
   }
   if ($nbg>5) $nbg=5;
   return ('
   <select multiple="multiple" class="form-control" name="Mmember[]" size="'.$nbg.'">
   '.$str.'
   </select>');
}

function droits_bloc($member,$j,$lb) {
   echo '
   <div class="mb-3">
      <div class="form-check form-check-inline">';
   $checked = $member==-127 ? ' checked="checked"' : '' ;
   echo '
         <input type="radio" id="adm'.$j.$lb.'" name="members" value="-127" '.$checked.' class="form-check-input" />
         <label class="form-check-label" for="adm'.$j.$lb.'">'.adm_translate("Administrateurs").'</label>
      </div>
      <div class="form-check form-check-inline">';
   $checked = $member==-1 ? ' checked="checked"' : '' ;
   echo '
         <input type="radio" id="ano'.$j.$lb.'" name="members" value="-1" '.$checked.' class="form-check-input" />
         <label class="form-check-label" for="ano'.$j.$lb.'">'.adm_translate("Anonymes").'</label>
      </div>';
   echo '
      <div class="form-check form-check-inline">';
   if ($member>0) {
      echo '
         <input type="radio" id="mem'.$j.$lb.'" name="members" value="1" checked="checked" class="form-check-input"/>
         <label class="form-check-label" for="mem'.$j.$lb.'">'.adm_translate("Membres").'</label>
      </div>
      <div class="form-check form-check-inline">
         <input type="radio" id="tous'.$j.$lb.'" name="members" value="0" class="form-check-input" />
         <label class="form-check-label" for="tous'.$j.$lb.'">'.adm_translate("Tous").'</label>
      </div>
   </div>
   <div class="mb-3 row">
      <label for="Mmember[]" class="col-form-label col-sm-12">'.adm_translate("Groupes").'</label>
      <div class="col-sm-12">
         '.groupe($member).'
      </div>
   </div>';
   } else {
      $checked = $member==0 ? ' checked="checked"' : '' ;
      echo '
         <input type="radio" id="mem'.$j.$lb.'" name="members" value="1" class="form-check-input" />
         <label class="form-check-label" for="mem'.$j.$lb.'">'.adm_translate("Membres").'</label>
      </div>
      <div class="form-check form-check-inline">
         <input type="radio" id="tous'.$j.$lb.'" name="members" value="0"'.$checked.' class="form-check-input" />
         <label class="form-check-label" for="tous'.$j.$lb.'">'.adm_translate("Tous").'</label>
      </div>
   </div>
   <div class="mb-3 row">
      <label for="Mmember[]" class="col-form-label col-sm-12">'.adm_translate("Groupes").'</label>
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
         if ( $("#all_g").attr("title") !== "'.adm_translate("Replier la liste").'" ) {
            $("#all_g").attr("title","'.adm_translate("Replier la liste").'")
            $("#tad_blocgauc td.togxg").attr("style","display: block-inline")
            $("#tad_blocgauc a.tog i").attr("class","fa fa-caret-up fa-lg text-primary me-2")
            $("#tad_blocgauc a.tog").attr("title","'.adm_translate("Replier la liste").'")
            $( "#tad_blocgauc a.tog" ).each(function(index) {
               var idi= $(this).attr("id")
               var idir = idi.replace("show", "hide");
               $(this).attr("id",idir)
            });
            } 
         else {
            $("#all_g").attr("title","'.adm_translate("Déplier la liste").'")
            $("#tad_blocgauc td.togxg").attr("style","display: none")
            $("#tad_blocgauc a.tog i").attr("class","fa fa-caret-down fa-lg text-primary me-2")
            $("#tad_blocgauc a.tog").attr("title","'.adm_translate("Déplier la liste").'")
            $( "#tad_blocgauc a.tog" ).each(function(index) {
               var idi= $(this).attr("id")
               var idir = idi.replace("hide", "show");
               $(this).attr("id",idir)
            });
            }
         });
         //]]>
   </script>
   <div class="">
   <table id="tad_blocgauc" class="table table-hover table-striped" >
      <thead>
         <tr>
            <th><a class="togxyg"><i id="all_g" class="fa fa-navicon" title="'.adm_translate("Déplier la liste").'"></i></a>&nbsp;'.adm_translate("Titre").'</th>
            <th class="d-none d-sm-table-cell text-center">'.adm_translate("Actif").'</th>
            <th class="d-none d-sm-table-cell text-end">Index</th>
            <th class="d-none d-sm-table-cell text-end">'.adm_translate("Rétention").'</th>
            <th class="text-end">ID</th>
         </tr>
      </thead>
      <tbody>';
      $j=0;
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
         echo $Sactif ? '
         <tr class="table-success">' : '
         <tr class="table-danger">';
         echo '
            <td align="left">
               <a class="tog" id="show_bloga_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_bloga_'.$id.'" class="fa fa-caret-down fa-lg text-primary me-2" ></i></a>';
         echo aff_langue($title).' '.$funct.'</td>';
         echo $Sactif ? '
            <td class="d-none d-sm-table-cell text-center">'.adm_translate("Oui").'</td>' : '
            <td class="text-danger d-none d-sm-table-cell text-center">'.adm_translate("Non").'</td>';
         echo '
            <td class="d-none d-sm-table-cell" align="right">'.$Lindex.'</td>
            <td class="d-none d-sm-table-cell" align="right">'.$Scache.'</td>
            <td class="text-end">'.$id.'</td>
         </tr>
         <tr>
            <td id="bloga_'.$id.'" class="togxg" style="display:none;" colspan="5">
               <form id="fad_bloga_'.$id.'" action="admin.php" method="post">
                  <div class="row g-3">
                     <div class="col-md-8">
                        <fieldset>
                           <legend>'.adm_translate("Contenu").'</legend>
                           <div class="form-floating mb-3">
                              <input class="form-control" type="text" id="titlega_'.$id.'" name="title" maxlength="1000" value="'.$title.'" />
                              <label for="titlega_'.$id.'">'.adm_translate("Titre").'</label>
                           </div>
                           <div class="form-floating mb-3">
                              <textarea class="form-control" id="contentga_'.$id.'" name="content" style="height:140px">'.$content.'</textarea>
                              <label for="contentga_'.$id.'">'.adm_translate("Contenu").'</label>
                              <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                           </div>
                           <div class="form-floating mb-3">
                              <textarea class="form-control" rows="2" id="BLaidega_'.$id.'" name="BLaide" style="height:100px">'.$BLaide.'</textarea>
                              <label for="BLaidega_'.$id.'">'.adm_translate("Aide en ligne de ce bloc").'</label>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend>'.adm_translate("Droits").'</legend>';
                        echo droits_bloc($member,$j,'L');
                        echo '
                        </fieldset>
                        <div class="mb-3 row">
                           <div class="col-sm-12">
                              <select class="form-select" name="op">
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
                           <div class="form-floating mb-3">
                              <input class="form-control" type="number" id="Lindexga_'.$id.'" name="Lindex" max="9999" value="'.$Lindex.'" />
                              <label for="Lindexga_'.$id.'">Index</label>
                           </div>
                           <div class="form-floating mb-3">
                              <input class="form-control" type="number" id="Scachega_'.$id.'" name="Scache" min="0" max="99999" value="'.$Scache.'" />
                              <label for="Scachega_'.$id.'">'.adm_translate("Rétention").'</label>
                              <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                           </div>
                           <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="Sactif'.$j.'L" name="Sactif" value="ON" ';
                           if ($Sactif) echo 'checked="checked" ';
                           echo '/>
                              <label class="form-check-label" for="Sactif'.$j.'L">'.adm_translate("Activer le Bloc").'</label>
                           </div>
                           <div class="form-check mb-3">
                              <input type="checkbox" class="form-check-input" id="css'.$j.'L" name="css" value="1" ';
                              if ($css=='1') echo 'checked="checked" ';
                              echo '/>
                              <label class="form-check-label" for="css'.$j.'L">'.adm_translate("CSS Specifique").'</label>
                           </div>
                        </fieldset>
                     </div>
                     <input type="hidden" name="id" value="'.$id.'" />
                  </div>
                  <button class="btn btn-primary mb-2" type="submit">'.adm_translate("Ok").'</button>
               </form>
               <script type="text/javascript">
               //<![CDATA[
                  tog("bloga_'.$id.'","show_bloga_'.$id.'","hide_bloga_'.$id.'");
               //]]>
               </script>
            </td>
         </tr>';
      $j++;
      }
      echo '
      </tbody>
   </table>
   </div>';
   }

   echo '
   <hr />
   <h3>'.adm_translate("Edition des Blocs de droite").'</h3>';
   $result = sql_query("SELECT id, title, content, member, Rindex, cache, actif, aide, css FROM ".$NPDS_Prefix."rblocks ORDER BY Rindex ASC");
   $num_row=sql_num_rows($result);
   if ($num_row>0) {
      echo '
   <script type="text/javascript">
      //<![CDATA[
         $("#adm_workarea").on("click", "a.togxyd",function() {
            $(".fa.fa-navicon").attr("title","'.adm_translate("Replier la liste").'")
            $("#tad_blocdroi a.tog i").attr("class","fa fa-caret-down fa-lg me-1 text-primary me-2")
            $("#tad_blocdroi a.tog").attr("data-bs-original-title","'.adm_translate("Déplier la liste").'")
            $( "#tad_blocdroi a.tog" ).each(function(index) {
               var idi= $(this).attr("id")
               var idir = idi.replace("hide", "show");
               $(this).attr("id",idir)
            });
         });
         //]]>
   </script>
   <table id="tad_blocdroi" class="table table-hover table-striped " >
      <thead class="w-100">
         <tr class="w-100">
            <th><a class="togxyd"><i class="fa fa-navicon fa-lg tooltipbyclass" title="'.adm_translate("Déplier la liste la liste").'"></i></a>&nbsp;'.adm_translate("Titre").'</th>
            <th class="d-none d-sm-table-cell text-center">'.adm_translate("Actif").'</th>
            <th class="d-none d-sm-table-cell text-end">Index</th>
            <th class="d-none d-sm-table-cell text-end">'.adm_translate("Rétention").'</th>
            <th class="text-end">ID</th>
         </tr>
      </thead>
      <tbody>';
      $j=0;
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
         echo $Sactif ? '
         <tr class="table-success w-100 mw-100">': '
         <tr class="table-danger w-100 mw-100">';
         echo '
            <td align="left">
               <a data-bs-toggle="collapse" data-bs-target="#blodr_'.$id.'" aria-expanded="false" aria-controls="blodr_'.$id.'" class="tog tooltipbyclass" id="show_blodr_'.$id.'" title="'.adm_translate("Déplier la liste").'"><i id="i_blodr_'.$id.'" class="fa fa-caret-down fa-lg text-primary me-2" ></i></a>';
         echo aff_langue($title).' '.$funct.'</td>';
         echo $Sactif ? '
            <td class="d-none d-sm-table-cell text-center" >'.adm_translate("Oui").'</td>' : '
            <td class="text-danger d-none d-sm-table-cell text-center">'.adm_translate("Non").'</td>';
         echo '
            <td class="d-none d-sm-table-cell text-end">'.$Rindex.'</td>
            <td class="d-none d-sm-table-cell text-end">'.$Scache.'</td>
            <td class="text-end">'.$id.'</td>
         </tr>
         <tr class="w-100">
            <td id="blodr_'.$id.'" class="togxd collapse" colspan="5">
               <form id="fad_blodr_'.$id.'" action="admin.php" method="post">
                  <div class="row g-3">
                     <div class="col-md-8">
                        <fieldset>
                           <legend>'.adm_translate("Contenu").'</legend>
                           <div class="form-floating mb-3">
                              <input class="form-control" type="text" id="titledr_'.$id.'" name="title" maxlength="1000" value="'.$title.'" />
                              <label for="titledr_'.$id.'">'.adm_translate("Titre").'</label>
                           </div>
                           <div class="form-floating mb-3">
                              <textarea class="form-control" style="height:140px;" id="contentdr_'.$id.'" name="content">'.$content.'</textarea>
                              <label for="contentdr_'.$id.'">'.adm_translate("Contenu").'</label>
                              <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
                           </div>
                           <div class="form-floating mb-3">
                              <textarea class="form-control" style="height:100px;" id="BRaidedr_'.$id.'" name="BRaide">'.$BRaide.'</textarea>
                              <label class="col-form-label col-sm-12" for="BRaidedr_'.$id.'">'.adm_translate("Aide en ligne de ce bloc").'</label>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend>'.adm_translate("Droits").'</legend>';
         echo droits_bloc($member,$j,'R');
         echo '
                        </fieldset>
                        <div class="mb-3 row">
                           <div class="col-sm-12">
                              <select class="form-select" name="op">
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
                           <div class="form-floating mb-3">
                              <input class="form-control" type="number" id="Rindexdr_'.$id.'" name="Rindex" min="0" max="9999" value="'.$Rindex.'" />
                              <label for="Rindexdr_'.$id.'">Index</label>
                           </div>
                           <div class="form-floating mb-3"">
                              <input class="form-control" type="number" name="Scache" id="Scache" min="0" max="99999" value="'.$Scache.'" />
                              <label for="Scache">'.adm_translate("Rétention").'</label>
                              <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
                           </div>
                           <div class="mb-3">
                              <div class="form-check" >
                                 <input type="checkbox" class="form-check-input" id="Sactif'.$j.'R" name="Sactif" value="ON" ';
         if ($Sactif) echo 'checked="checked" ';
         echo '/>
                                 <label class="form-check-label" for="Sactif'.$j.'R">'.adm_translate("Activer le Bloc").'</label>
                              </div>
                              <div class="form-check" >
                                 <input type="checkbox" class="form-check-input" id="css'.$j.'R" name="css" value="1" ';
         if ($css=="1") echo 'checked="checked" ';
         echo '/>
                                 <label class="form-check-label" for="css'.$j.'R"> '.adm_translate("CSS Specifique").'</label>
                              </div>
                           </div>
                        </fieldset>
                     </div>
                     <input type="hidden" name="id" value="'.$id.'" />
                  </div>
                  <button class="btn btn-primary mb-3" type="submit">'.adm_translate("Ok").'</button>
               </form>
               <script type="text/javascript">
               //<![CDATA[
                  tog("blodr_'.$id.'","show_blodr_'.$id.'","hide_blodr_'.$id.'");
               //]]>
               </script>
            </td>
         </tr>';
      $j++;
      }
      echo '
      </tbody>
   </table>';
   }
   echo '
   <hr />
   <h3 class="my-3">'.adm_translate("Créer un nouveau Bloc").'</h3>
   <form id="blocknewblock" action="admin.php" method="post" name="adminForm">
      <div class="row g-3">
         <div class="col-md-8">
            <fieldset>
               <legend>'.adm_translate("Contenu").'</legend>
               <div class="form-floating mb-3">
                  <input class="form-control" type="text" id="nblock_title" name="title" maxlength="1000" />
                  <label for="nblock_title">'.adm_translate("Titre").'</label>
                  <span class="help-block text-end" id="countcar_nblock_title"></span>
               </div>
               <div class="form-floating mb-3">
                  <textarea class="form-control" name="xtext" id="nblock_xtext" style="height:140px;"></textarea>
                  <label for="nblock_xtext">'.adm_translate("Contenu").'</label>
                  <span class="help-block"><a href="javascript:void(0);" onclick="window.open(\'autodoc.php?op=blocs\', \'windocu\', \'width=720, height=400, resizable=yes,menubar=no,location=no,directories=no,status=no,copyhistory=no,toolbar=no,scrollbars=yes\');">'.adm_translate("Manuel en ligne").'</a></span>
               </div>
               <div class="form-floating mb-3">
                  <textarea class="form-control" rows="2" name="Baide" id="nblock_Baide"></textarea>
                  <label for="nblock_Baide">'.adm_translate("Aide en ligne").'</label>
               </div>
            </fieldset>
            <fieldset>
               <legend>'.adm_translate("Droits").'</legend>';
   echo droits_bloc('0','','');
   echo '
            </fieldset>
            <fieldset>
               <legend>'.adm_translate("Position").'</legend>
               <div class="mb-3">
                  <div class="form-check">
                     <input type="radio" id="nblock_opL" name="op" value="makelblock" checked="checked" class="form-check-input"/>
                     <label class="form-check-label" for="nblock_opL">'.adm_translate("Créer un Bloc gauche").'</label>
                  </div>
                  <div class="form-check">
                     <input type="radio" id="nblock_opR" name="op" value="makerblock" class="form-check-input"/>
                     <label class="form-check-label" for="nblock_opR">'.adm_translate("Créer un Bloc droite").'</label>
                  </div>
               </div>
            </fieldset>
         </div>
         <div class="col-md-4">
            <fieldset>
               <legend>'.adm_translate("Paramètres").'</legend>
               <div class="form-floating mb-3">
                  <input class="form-control" type="number" name="index" id="nblock_index" min="0" max="9999" />
                  <label for="nblock_index">Index</label>
               </div>
               <div class="form-floating mb-3">
                  <input class="form-control" type="number" name="Scache" id="nblock_Scache" min="0" max="99999" value="60" />
                  <label for="nblock_Scache">'.adm_translate("Rétention").'</label>
                  <span class="help-block">'.adm_translate("Chaque bloc peut utiliser SuperCache. La valeur du délai de rétention 0 indique que le bloc ne sera pas caché (obligatoire pour le bloc function#adminblock).").'</span>
               </div>
               <div class="mb-3">
                  <div class="form-check">
                     <input class="form-check-input" type="checkbox" name="SHTML" id="nblock_shtml" value="ON" />
                     <label class="form-check-label" for="nblock_shtml">HTML</label>
                  </div>
                  <div class="form-check">
                     <input class="form-check-input" type="checkbox" name="CSS" id="nblock_css" value="ON" />
                     <label class="form-check-label" for="nblock_css">CSS</label>
                  </div>
               </div>
            </fieldset>
         </div>
      </div>
      <button class="btn btn-primary mb-2" type="submit">'.adm_translate("Valider").'</button>
   </form>';
   $arg1='
      var formulid = ["blocknewblock"];
      inpandfieldlen("nblock_title",1000);
';
   adminfoot('fv','',$arg1,'');
}

switch ($op) {
   case 'blocks':
      blocks();
   break;
}
?>