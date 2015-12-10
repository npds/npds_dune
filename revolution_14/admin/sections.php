<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Major changes from ALAT 2004-2005                                    */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"admin.php")) { Access_Error(); }
$f_meta_nom ='sections';
$f_titre = adm_translate("Rubriques actives");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/sections.html";

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
         $str.='<option value="'.$groupe_id.'" selected="selected">'.$groupe_name.'</option>';
      } else {
         $str.='<option value="'.$groupe_id.'">'.$groupe_name.'</option>';
      }
      $nbg++;
   }
   if ($nbg>5) {$nbg=5;}
   return ('<select class="form-control" name="Mmembers[]" multiple size="'.$nbg.'">'.$str.'</select>');
}

function droits($member) {
   echo '
   <fieldset>
   <legend>'.adm_translate("Droits").'</legend>
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
   </div>
   </fielset>';
   }
}

function sousrub_select($secid) {
   global $radminsuper, $aid;
   global $NPDS_Prefix;
   $ok_pub=false;

   $tmp='<select name="secid" class="form-control">';
   $result = sql_query("select distinct rubid, rubname from ".$NPDS_Prefix."rubriques order by ordre");
   while(list($rubid, $rubname) = sql_fetch_row($result)) {
      $rubname = aff_langue($rubname);
      $tmp.='<optgroup label="'.aff_langue($rubname).'">';
      if ($radminsuper==1) {
         $result2 = sql_query("select secid, secname from ".$NPDS_Prefix."sections where rubid='$rubid' order by ordre");
      } else {
         $result2 = sql_query("select distinct sections.secid, sections.secname from ".$NPDS_Prefix."sections, ".$NPDS_Prefix."publisujet where sections.rubid='$rubid' and sections.secid=publisujet.secid2 and publisujet.aid='$aid' and publisujet.type='1' order by ordre");
      }
      while(list($secid2, $secname) = sql_fetch_row($result2)) {
         $secname=aff_langue($secname);
         $secname = substr($secname, 0, 50);
         $tmp.='<option value="'.$secid2.'"';
         if ($secid2==$secid) $tmp.=' selected="selected"';
         $tmp.='>'.$secname.'</option>';
         $ok_pub=true;
      }
      sql_free_result($result2);
      $tmp.='</optgroup>';
   }
   $tmp.="</select>";
   sql_free_result($result);
   if (!$ok_pub) ($tmp="");
   return ($tmp);
}

function droits_publication($secid) {
   global $radminsuper, $aid;
   global $NPDS_Prefix;

   $droits=0; // 3=mod - 4=delete
   if ($radminsuper!=1) {
      $result = sql_query("select type from ".$NPDS_Prefix."publisujet where secid2='$secid' and aid='$aid' and type in(3,4) order by type");
      if (sql_num_rows($result)>0) {
         while(list($type) = sql_fetch_row($result)) {
            $droits=$droits+$type;
         }
      }
   } else {
      $droits=7;
   }
   return ($droits);
}

function sections() {
   global $hlpfile, $NPDS_Prefix, $aid, $radminsuper, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   
   adminhead($f_meta_nom, $f_titre, $adminimg);
   if ($radminsuper==1) {
      $result = sql_query("select rubid, rubname, enligne, ordre from ".$NPDS_Prefix."rubriques order by ordre");
   } else {
      $result = sql_query("select distinct rubriques.rubid, rubriques.rubname, rubriques.enligne, rubriques.ordre from ".$NPDS_Prefix."rubriques, ".$NPDS_Prefix."sections, ".$NPDS_Prefix."publisujet where rubriques.rubid=sections.rubid and sections.secid=publisujet.secid2 and publisujet.aid='$aid' order by ordre");
   }
   $nb_rub=@sql_num_rows($result);


   if ($ibid=theme_image("sections/prev.gif")) {$imgprev=$ibid;} else {$imgprev="images/sections/prev.gif";}
//   echo "&nbsp;&nbsp;( <img src=\"$imgprev\" border=\"0\" alt=\"\" /> ".adm_translate("Pour prÈvisualiser le contenu dans son environnement d'exploitation.")." )</td>";
echo '
<ul class="nav nav-pills">
  <li><a href="admin.php?op=new_rub_section&amp;type=rub"><i class="fa fa-plus"></i>&nbsp;'.adm_translate("Ajouter une nouvelle Rubrique").'</a></li>';
if ($nb_rub > 0) {
  echo '
  <li><a href="admin.php?op=new_rub_section&amp;type=sec" ><i class="fa fa-plus"></i>&nbsp;'.adm_translate("Ajouter une nouvelle Sous-Rubrique").'</a></li>';
};
   if ($radminsuper==1) { echo '<li><a href="admin.php?op=ordremodule"><i class="fa fa-sort-amount-desc"></i>&nbsp;'.adm_translate("Changer l'ordre").' '.adm_translate("des").' '.adm_translate("rubriques").'</a></li>'; }
echo'
</ul>
';


//    echo "<a href=\"admin.php?op=new_rub_section&amp;type=rub\">".adm_translate("Ajouter une nouvelle Rubrique")."</a>";
//    if ($nb_rub > 0) {
//       echo '<a href="admin.php?op=new_rub_section&amp;type=sec" >'.adm_translate("Ajouter une nouvelle Sous-Rubrique").'</a>';
//    }
   if ($nb_rub > 0) {
   $i=-1;
      echo '
      <h3>'.adm_translate("Liste des rubriques").'</h3>
      <table id="tad_rubri" data-toggle="table" data-striped="true" data-detail-view="true" data-show-toggle="true" data-icons-prefix="fa" data-icons="icons" >
         <thead>
            <tr>
               <th data-sortable="true" class="">Rubrique</th>
               <th data-sortable="true" class="">Etat</th>
               <th class="">Fonctions</th>
            </tr>
         </thead>
         <tbody>';

      while (list($rubid, $rubname, $enligne, $ordre) = sql_fetch_row($result)) {$i++;
         if ($radminsuper==1) {
            $href1='<a href="admin.php?op=rubriquedit&amp;rubid='.$rubid.'"><i class="fa fa-edit fa-lg"></i>&nbsp;';
            $href2='</a>';
            $href3='<a href="admin.php?op=rubriquedelete&amp;rubid='.$rubid.'" class="text-danger" title="'.adm_translate("Supprimer").'" data-toggle="tooltip"><i class="fa fa-trash-o fa-lg"></i></a>';
         } else {
            $href1=""; $href2=""; $href3="";
         }
         $rubname = aff_langue($rubname);
         if ($rubname=="") {$rubname=adm_translate("Sans nom");}
         if ($enligne==0) { $online='<span class="text-danger">'.adm_translate("Hors Ligne").'</span>'; } else if ($enligne==1) { $online = '<span class="text-success">'.adm_translate("En Ligne").'</span>'; }
         echo '
            <tr>
               <td>'.$rubname.'</td>
               <td>'.$online.'</td>
               <td>'.$href1.$href2.$href3.'</td>
            </tr>';

         if ($radminsuper==1) {
            $result2 = sql_query("select distinct secid, secname from ".$NPDS_Prefix."sections where rubid='$rubid' order by ordre");
         } else {
            $result2 = sql_query("select distinct sections.secid, sections.secname from ".$NPDS_Prefix."sections, ".$NPDS_Prefix."publisujet where sections.rubid='$rubid' and sections.secid=publisujet.secid2 and publisujet.aid='$aid' order by ordre");
         }
         if ($ibid=theme_image("sections/ordrecours.gif")) {$imgordcours=$ibid;} else {$imgordcours="images/sections/ordrecours.gif";}
         if ($ibid=theme_image("sections/ordrechapitre.gif")) {$imgordchapitre=$ibid;} else {$imgordchapitre="images/sections/ordrechapitre.gif";}
         if (sql_num_rows($result2) > 0) {
            echo '
            <div id="srub_'.$i.'" class="" style="display:none;">
               '.adm_translate("Sous-rubriques");
               if ($radminsuper==1) {
                  // modifier l'ordre des sous-rubriques au sein de la rubrique
                  echo ' <a class="" href="admin.php?op=ordrechapitre&amp;rubid='.$rubid.'&amp;rubname='.$rubname.'" title="'.adm_translate("Changer l'ordre").' '.adm_translate("des").' '.adm_translate("sous-rubriques").'" data-toggle="tooltip" data-placement="left" ><i class="fa fa-sort-amount-desc"></i></a>';
               }
            echo '
               ';

           while (list($secid, $secname) = sql_fetch_row($result2)) {
              $droit_pub=droits_publication($secid);
              $secname=aff_langue($secname);
               echo '
               <div class="list-group-item active">
               <a class="btn btn-primary" href="#" title="'.adm_translate("DÈplier la liste").'" data-toggle="collapse" data-target="#lst_sect_'.$secid.'"><i class="fa fa-navicon" ></i></a>&nbsp;
               '.$secname.'&nbsp;
               <span class="btn btn-secondary pull-right">
               <a class="" href="sections.php?op=listarticles&amp;secid='.$secid.'&amp;prev=1" ><i class="fa fa-eye fa-lg"></i>&nbsp;</a>
               <a class="" href="admin.php?op=sectionedit&amp;secid='.$secid.'" title="'.adm_translate("Editer la sous rubrique").'" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit fa-lg"></i></a>&nbsp;';
              if (($droit_pub==7) or ($droit_pub==4)) {
                 echo '<a class="" href="admin.php?op=sectiondelete&amp;secid='.$secid.'" class="" title="'.adm_translate("Supprimer la sous rubrique").'" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash-o fa-lg text-danger"></i></a>';
              }
              echo '</span>
              </div>';

               $result3 = sql_query("select artid, title from ".$NPDS_Prefix."seccont where secid='$secid' order by ordre");
               if (sql_num_rows($result3) > 0) {
                  $ibid=true;
                  echo '<ul id="lst_sect_'.$secid.'" class="list-group collapse in">';
                  while (list($artid, $title) = sql_fetch_row($result3)) {
                     if ($title=="") $title=adm_translate("Sans titre");
                     echo '
                     
                     <li class="list-group-item">'.aff_langue($title);
                     echo '
                     <span class=" pull-right">
                     <a href="sections.php?op=viewarticle&amp;artid='.$artid.'&amp;prev=1"><i class="fa fa-eye fa-lg"></i></a>&nbsp;
                     <a href="admin.php?op=secartedit&amp;artid='.$artid.'" ><i class="fa fa-edit fa-lg"></i></a>&nbsp;';
                     if (($droit_pub==7) or ($droit_pub==4)) {
                        echo '
                     <a href="admin.php?op=secartdelete&amp;artid='.$artid.'" class="text-danger" title="'.adm_translate("Supprimer").'" data-toggle="tooltip"><i class="fa fa-trash-o fa-lg"></i></a>&nbsp';
                     }
                     echo '
                     </span>
                     </li>';
                  }
                  if ($radminsuper==1) {
                     // modifier l'ordre des publications au sein de la sous-rubrique
//                     echo "<tr><td colspan=\"2\">&nbsp;&nbsp;<img src=\"$imgordcours\" alt=\"\" />&nbsp;<a href=\"admin.php?op=ordrecours&secid=$secid&amp;secname=$secname\" >".adm_translate("Changer l'ordre")." ".adm_translate("des")." ".adm_translate("publications")."</a></td></tr>";
//                     echo "<tr><td colspan=\"2\"></td></tr>";
                  }
                  echo '</ul>';
               }
            }
            echo '</div>';
         }
      }

//      if ($ibid=theme_image("sections/ordremodule.gif")) {$imgordmodule=$ibid;} else {$imgordmodule="images/sections/ordremodule.gif";}
      // le super administrateur peut modifier l'ordre des rubriques elles-mÍmes
//      if ($radminsuper==1) { echo "<img src=\"$imgordmodule\" alt=\"\" />&nbsp;<a href=\"admin.php?op=ordremodule\">".adm_translate("Changer l'ordre")." ".adm_translate("des")." ".adm_translate("rubriques")."</a>"; }

      echo '
         </tbody>
      </table>';
      echo '
      <h3>'.adm_translate("Editer une publication").'</h3>
      <form action="admin.php" method="post">
         <div class="form-group">
            <label class="form-control-label col-sm-4 col-md-4" for="artid">ID</label>
            <div class="col-sm-8 col-md-8">
               <input type="number" class="form-control" name="artid" min="0" max="999999999" />
            </div>
         <input type="hidden" name="op" value="secartedit" />
      </form>';
     // Ajout d'une publication
      $autorise_pub=sousrub_select("");
      if ($autorise_pub) {
         echo '
         <h3>'.adm_translate("Ajouter une publication").'</h3>
            <form action="admin.php" method="post" name="adminForm">
            <div class="form-group">
               <label class="form-control-label col-sm-4 col-md-4" for="secid">'.adm_translate("Sous-rubrique").'</label>
               <div class="col-sm-8 col-md-8">';
         echo $autorise_pub;
         echo '
            </div>
            <div class="form-group">
               <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
               <textarea class="form-control" name="title" rows="2"></textarea>
            </div>
            <div class="form-group">
               <label class="form-control-label" for="content">'.adm_translate("Contenu").'</label>
               <textarea class="textbox form-control" name="content" rows="30"></textarea>
            </div>';
         echo aff_editeur("content","false");
         echo '
         <input type="hidden" name="op" value="secarticleadd" />
         <input type="hidden" name="autho" value="'.$aid.'" />';
         ################################# personnalisation des critËres ######################################
         echo '
         <input type="hidden" name="crit1" value="" />
         <input type="hidden" name="crit2" value="" />
         <input type="hidden" name="crit3" value="" />
         <input type="hidden" name="crit4" value="" />
         <input type="hidden" name="crit5" value="" />
         <input type="hidden" name="crit6" value="" />
         <input type="hidden" name="crit7" value="" />
         <input type="hidden" name="crit8" value="" />
         <input type="hidden" name="crit9" value="" />
         <input type="hidden" name="crit10" value="" />
         <input type="hidden" name="crit11" value="" />
         <input type="hidden" name="crit12" value="" />
         <input type="hidden" name="crit13" value="" />
         <input type="hidden" name="crit14" value="" />
         <input type="hidden" name="crit15" value="" />
         <input type="hidden" name="crit16" value="" />
         <input type="hidden" name="crit17" value="" />
         <input type="hidden" name="crit18" value="" />
         <input type="hidden" name="crit19" value="" />
         <input type="hidden" name="crit20" value="" />';
         #######################################################################################################
         droits("0");
         echo '
         <div class="form-group">
         <input class="btn btn-primary" type="submit" value="'.adm_translate("Ajouter").'" />
         </div>
         </form>';

         if ($radminsuper!=1) {
            echo '<p>'.adm_translate("Une fois que vous aurez validÈ cette publication, elle sera intÈgrÈe en base temporaire, et l'administrateur sera prÈvenu. Il visera cette publication et la mettra en ligne dans les meilleurs dÈlais.<br />Il est normal que pour l'instant, cette publication n'apparaisse pas dans l'arborescence.").'</p>';
         }
      }
   }

   echo '<h3>'.adm_translate("Publication(s) en attente de validation").'</h3>';
   echo "<ul>\n";
   if ($radminsuper==1) {
      $result = sql_query("select distinct artid, secid, title, content, author from ".$NPDS_Prefix."seccont_tempo order by artid");
      while(list($artid, $secid, $title, $content, $author) = @sql_fetch_row($result)) {
         echo "<li>".aff_langue($title)."&nbsp;($author) [ <a href=\"admin.php?op=secartupdate&amp;artid=$artid\">".adm_translate("Editer")."</a> ]";
      }
   } else {
      $result = sql_query("select distinct seccont_tempo.artid, seccont_tempo.title, seccont_tempo.author from ".$NPDS_Prefix."seccont_tempo, ".$NPDS_Prefix."publisujet where seccont_tempo.secid=publisujet.secid2 and publisujet.aid='$aid' and (publisujet.type='1' or publisujet.type='2')");
      while(list($artid, $title, $author) = sql_fetch_row($result)) {
         echo "<li>".aff_langue($title)."&nbsp;($author) [ <a href=\"admin.php?op=secartupdate&amp;artid=$artid\">".adm_translate("Editer")."</a> ]";
      }
   }
   echo "</ul>\n";

   if ($radminsuper==1) {
      echo  '<h3>'.adm_translate("Droits des auteurs").'</h3>';
      $result = sql_query("select aid, name, radminsuper from authors");
      echo '<table>';
      while(list($Xaid, $name, $Xradminsuper) = sql_fetch_row($result)) {
         if (!$Xradminsuper) {
            echo '
            <tr>
               <td width="50%"><i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;'.$Xaid.'&nbsp;&nbsp;/&nbsp;&nbsp;'.$name.'</td>
               <td align="right"><a href="admin.php?op=droitauteurs&amp;author='.$Xaid.'">'.adm_translate("Modifier l'information").'</a></td>
            </tr>';
         }
      }
      echo '</table>';

      if (file_exists("sections.config.php"))
         include ("sections.config.php");
      else {
         $sections_chemin = 0;
         $$togglesection = 0;
      }
      echo '
      <h3>'.adm_translate("ParamËtres liÈs ‡ l'illustration").'</h3>
      <form action="admin.php" method="post" name="form2">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-5 col-md-5" for="togglesection">'.adm_translate("Activer Toggle-Div :").'</label>
               <div class="col-sm-7 col-md-7">
                  <label class="radio-inline">';
      if ($togglesection==1) {
         echo '
                     <input type="radio" name="togglesection" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="togglesection" value="0" />'.adm_translate("Non");
      } else {
         echo '
                     <input type="radio" name="togglesection" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="togglesection" value="0" checked="checked" />'.adm_translate("Non");
      }
      echo '
                  </label>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-5 col-md-5" for="sections_chemin">'.adm_translate("Afficher le chemin dans le titre de la page :").'</label>
               <div class="col-sm-7 col-md-7">
                  <label class="radio-inline">';
      if ($sections_chemin==1) {
         echo '
                     <input type="radio" name="sections_chemin" value="1" checked="checked" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="sections_chemin" value="0" />'.adm_translate("Non");
      } else {
         echo '
                     <input type="radio" name="sections_chemin" value="1" />'.adm_translate("Oui").'
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="sections_chemin" value="0" checked="checked" />'.adm_translate("Non");
      }
      echo '
                  </label>
               </div>
            </div>
         </div>
         <input type="hidden" name="op" value="menu_dyn" />
         <div class="form-group">
            <input class="btn btn-primary" type="submit" value="'.adm_translate("Valider").'" />
         </div>
      </form>';
   }
   echo '
   <script type="text/javascript">
   //<![CDATA[
   $(document).ready(function() {
   var $table = $("#tad_rubri");
   $table.on("expand-row.bs.table", function (e, index, row, $detail) {
    var res = $("#srub_" + index).html();
    $detail.html(res);
   });
   });
   //]]>
   </script>';
   adminfoot('','','','');
}

function new_rub_section($type) {
   global $hlpfile, $NPDS_Prefix, $aid, $radminsuper, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   if ($type=="sec") {
      echo '
      <h3>'.adm_translate("Ajouter une nouvelle Sous-Rubrique").'</h3>
      <form action="admin.php" method="post" name="adminForm">
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4" for="rubref">'.adm_translate("Rubriques").'</label>
               <div class="col-sm-8">
                  <select class="form-control" name="rubref">';
      if ($radminsuper==1) {
         $result = sql_query("select rubid, rubname from ".$NPDS_Prefix."rubriques order by ordre");
      } else {
         $result = sql_query("select distinct rubriques.rubid, rubriques.rubname from ".$NPDS_Prefix."rubriques, ".$NPDS_Prefix."publisujet where publisujet.aid='$aid' order by ordre");
      }
      while (list($rubid, $rubname) = sql_fetch_row($result)) {
         echo '
                  <option value="'.$rubid.'">'.aff_langue($rubname).'</option>';
      }
      echo '
               </select>
            </div>
         </div>
      </div>
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="image">'.adm_translate("Image pour la Sous-Rubrique").'</label>
               <div class="col-sm-8 col-md-8">
                  <input type="text" class="form-control" name="image" />
               </div>
            </div>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="secname">'.adm_translate("Titre").'</label>
            <textarea id="secname" class="form-control" name="secname" maxlength="255" rows="2" required="required"></textarea>
            <span class="help-block text-xs-right"><span id="countcar_secname"></span></span>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="introd">'.adm_translate("Texte d'introduction").'</label>
            <textarea class="form-control" name="introd" rows="30"></textarea>';
            echo aff_editeur("introd","false");
      echo '
         </div>';
      droits("0");
      echo '
      <div class="form-group">
         <input type="hidden" name="op" value="sectionmake" />
         <button class="btn btn-primary col-sm-6 col-xs-12 col-md-4" type="submit" /><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").'</button>
         <button class="btn btn-secondary col-sm-6 col-xs-12 col-md-4" type="button" onclick="javascript:history.back()">'.adm_translate("Retour en arriËre").'</button>
      </div>
      </form>';
   } else if ($type=="rub") {
      if ($radminsuper==1) {
         echo '
         <h3>'.adm_translate("Ajouter une nouvelle Rubrique").'</h3>
         <form action="admin.php" method="post" name="adminForm">
            <div class="form-group">
               <label class="form-control-label" for="rubname">'.adm_translate("Nom de la Rubrique").'</label>
               <textarea id="rubname" class="textbox_no_mceEditor form-control" name="rubname" rows="2" maxlength="255" required="required"></textarea>
               <span class="help-block text-xs-right"><span id="countcar_rubname"></span></span>
            </div>
            <div class="form-group">
               <label class="form-control-label" for="introc">'.adm_translate("Texte d'introduction").'</label>
               <textarea class="textbox form-control" name="introc" rows="30" ></textarea>
            </div>';
         echo aff_editeur("introc","false");
         echo '
            <div class="form-group">
               <input type="hidden" name="op" value="rubriquemake" />
               <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;'.adm_translate("Ajouter").'</button>
               <input class="btn btn-secondary" type="button" onclick="javascript:history.back()" value="'.adm_translate("Retour en arriËre").'" />
            </div>
         </form>';
      } else {
         redirect_url("admin.php?op=sections");
      }
   }
echo '
      <script type="text/javascript">
      //<![CDATA[
         inpandfieldlen("rubname",255);
         inpandfieldlen("secname",255);
      //]]>
      </script>';
   adminfoot('fv','','','');
}

// Fonction publications connexes
function publishcompat($article) {
   include_once ("lib/togglediv.class.php");
   global $hlpfile, $NPDS_Prefix, $aid, $radminsuper, $f_meta_nom, $f_titre, $adminimg;

   $result2 = sql_query("select title from ".$NPDS_Prefix."seccont where artid='$article'");
   list($titre) = sql_fetch_row($result2);

   include("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("select rubid, rubname, enligne, ordre from ".$NPDS_Prefix."rubriques order by ordre");
   $toggle = new ToggleDiv(sql_num_rows($result));
   opentable();
   echo "<form action=\"admin.php\" method=\"post\">";
   echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
   echo adm_translate("Publications connexes")." : ".aff_langue($titre);
   echo "</td></tr></table>\n";
   echo "<br />";
   echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td>\n";
   echo $toggle->All();
   echo "</td></tr>\n</table>\n<hr noshade=\"noshade\" class=\"ongl\" />";
   $i = 0;
   while (list($rubid, $rubname, $enligne, $ordre) = sql_fetch_row($result)) {
      echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td class=\"header\">\n";
      if ($enligne == 0) { $online = adm_translate("Hors Ligne"); } else if ($enligne == 1) { $online = adm_translate("En Ligne"); }
      echo $toggle->Img();
      echo aff_langue($rubname);
      echo "</td><td class=\"header\" width=\"10%\" align=\"center\">$online</td></tr>\n</table>\n";
      echo $toggle->Begin();
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td align=\"right\">\n";
      echo "<table width=\"98%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">\n";
      if ($radminsuper==1) {
         $result2 = sql_query("select secid, secname from ".$NPDS_Prefix."sections where rubid='$rubid' order by ordre");
      } else {
         $result2 = sql_query("select distinct sections.secid, sections.secname from ".$NPDS_Prefix."sections, ".$NPDS_Prefix."publisujet where sections.rubid='$rubid' and sections.secid=publisujet.secid2 and publisujet.aid='$aid' and publisujet.type='1' order by ordre");
      }
      if (sql_num_rows($result2) > 0) {
         while (list($secid, $secname) = sql_fetch_row($result2)) {
            echo "<tr><td class=\"header\" colspan=\"2\"><b>&nbsp;&nbsp;".adm_translate("sous-rubrique")." ".aff_langue($secname)."</b></td></tr>";
            $result3 = sql_query("select artid, title from ".$NPDS_Prefix."seccont where secid='$secid' order by ordre");
            if (sql_num_rows($result3) > 0) {
               $rowcolor=tablos();
               while (list($artid, $title) = sql_fetch_row($result3)) {
                  $i++;
                  $result4 = sql_query("select id2 from ".$NPDS_Prefix."compatsujet where id2='$artid' and id1='$article'");
                  echo "<tr $rowcolor><td width=\"90%\">&nbsp;&nbsp;&nbsp;&nbsp;".aff_langue($title)."</td><td width=\"10%\">";
                  if (sql_num_rows($result4) > 0) {
                     echo "<input type=\"checkbox\" name=\"admin_rub[$i]\" value=\"$artid\" checked=\"checked\" /></td></tr>";
                  } else {
                     echo "<input type=\"checkbox\" name=\"admin_rub[$i]\" value=\"$artid\" /></td></tr>";
                  }
               }
            }
         }
      }
      echo "</table>";
      echo "</td></tr></table><br />";
      echo $toggle->End();
   }
   echo "<br /><input type=\"hidden\" name=\"article\" value=\"$article\" />
         <input type=\"hidden\" name=\"op\" value=\"updatecompat\" />
         <input type=\"hidden\" name=\"idx\" value=\"$i\" />
         <input class=\"bouton_standard\" type=\"submit\" value=\"".adm_translate("Valider")."\" />&nbsp;&nbsp;<input class=\"bouton_standard\" type=\"button\" onclick=\"javascript:history.back()\" value=\"".adm_translate("Retour en arriËre")."\" /></form>";
   closetable();
   include("footer.php");
}
function updatecompat($article, $admin_rub, $idx) {
   global $NPDS_Prefix;

   $result=sql_query("delete from ".$NPDS_Prefix."compatsujet where id1='$article'");
   for ($j = 1; $j < ($idx+1); $j++) {
      if ($admin_rub[$j]!="") { $result=sql_query("insert into ".$NPDS_Prefix."compatsujet values ('$article','$admin_rub[$j]')"); }
   }

   global $aid; Ecr_Log("security", "UpdateCompatSujets($article) by AID : $aid", "");
   Header("Location: admin.php?op=secartedit&artid=$article");
}
// Fonction publications connexes

// Fonctions RUBRIQUES
function rubriquedit($rubid) {
   global $hlpfile, $NPDS_Prefix, $radminsuper, $f_meta_nom, $f_titre, $adminimg;

   if ($radminsuper!=1) {
      Header("Location: admin.php?op=sections");
   }
   
   $result = sql_query("select rubid, rubname, intro, enligne, ordre from ".$NPDS_Prefix."rubriques where rubid='$rubid'");
   list($rubid, $rubname, $intro, $enligne, $ordre) = sql_fetch_row($result);
   if (!sql_num_rows($result)) {
      Header("Location: admin.php?op=sections");
   }
   
   include("header.php");
   GraphicAdmin($hlpfile);
   
   $result2 = sql_query("select secid from ".$NPDS_Prefix."sections where rubid='$rubid'");
   $number = sql_num_rows($result2);
   $rubname = stripslashes($rubname);
   $intro = stripslashes($intro);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '<h3>'.adm_translate("Editer une Rubrique : ")." ".aff_langue($rubname).'</h3>';
   if ($number)
      echo '<span class="text-danger">'.$number.' </span>'.adm_translate("sous-rubrique(s) attachÈe(s)");
   echo '
         <form action="admin.php" method="post" name="adminForm">
         <div class="form-group">
            <label class="form-control-label" for="rubname">'.adm_translate("Rubrique").'</label>
            <textarea id="rubname" class="textbox_no_mceEditor form-control" name="rubname" maxlength ="255" rows="2" required="required">'.$rubname.'</textarea>
            <span class="help-block text-xs-right"><span id="countcar_rubname"></span></span>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="introc">'.adm_translate("Texte d'introduction").'</label>
            <textarea name="introc" class="textbox form-control" rows="30" >'.$intro.'</textarea>
         </div>';
   echo aff_editeur("introc","false");
   echo '
         <div class="form-group">
            <label class="form-control-label" for="enligne">'.adm_translate("En Ligne").'</label>';
   if ($radminsuper==1) {
      if ($enligne==1) {
         $sel1 = 'checked="checked"'; $sel2 = '';
      } else {
         $sel1 = ''; $sel2 = 'checked="checked"';
      }
   }
   echo '
            <label class="radio-inline">
               <input type="radio" name="enligne" value="0" '.$sel2.' />'.adm_translate("Non").'
            </label>
            <label class="radio-inline">
               <input type="radio" name="enligne" value="1" '.$sel1.' />'.adm_translate("Oui").'
            </label>
         </div>
         <div class="form-group">
            <input type="hidden" name="rubid" value="'.$rubid.'" />
            <input type="hidden" name="op" value="rubriquechange" />
            <button class="btn btn-primary" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Enregistrer").'</button>&nbsp;
            <input class="btn btn-secondary" type="button" value="'.adm_translate("Retour en arriËre").'" onclick="javascript:history.back()" />
         </div>
      </form>
      <script type="text/javascript">
      //<![CDATA[
            inpandfieldlen("rubname",255);
      //]]>
      </script>';
      adminfoot('fv','','','');
}

function rubriquemake($rubname, $introc) {
   global $NPDS_Prefix;

   $rubname = stripslashes(FixQuotes($rubname));
   $introc = stripslashes(FixQuotes($introc));
   sql_query("INSERT INTO ".$NPDS_Prefix."rubriques VALUES (NULL,'$rubname','$introc','0','0')");

   global $aid; Ecr_Log("security", "CreateRubriques($rubname) by AID : $aid", "");
   Header("Location: admin.php?op=ordremodule");
}
function rubriquechange($rubid,$rubname,$introc,$enligne) {
   global $NPDS_Prefix;

   $rubname = stripslashes(FixQuotes($rubname));
   $introc = stripslashes(FixQuotes($introc));
   sql_query("update ".$NPDS_Prefix."rubriques set rubname='$rubname', intro='$introc', enligne='$enligne' where rubid='$rubid'");

   global $aid; Ecr_Log("security", "UpdateRubriques($rubid, $rubname) by AID : $aid", "");
   Header("Location: admin.php?op=sections");
}
// Fonctions RUBRIQUES

// Fonctions SECTIONS
function sectionedit($secid) {
   global $hlpfile, $radminsuper, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;

   include("header.php");
   GraphicAdmin($hlpfile);
   $result = sql_query("select secid, secname, image, userlevel, rubid, intro from ".$NPDS_Prefix."sections where secid='$secid'");
   list($secid, $secname, $image, $userlevel, $rubref, $intro) = sql_fetch_row($result);
   $secname = stripslashes($secname);
   $intro = stripslashes($intro);
   adminhead($f_meta_nom, $f_titre, $adminimg);

   echo '<h3>'.adm_translate("Sous-rubrique").' : '.aff_langue($secname).'</h3>';
   $result2 = sql_query("select artid from ".$NPDS_Prefix."seccont where secid='$secid'");
   $number = sql_num_rows($result2);
   if ($number)
      echo '<span class="label label-pill label-default">'.$number.' </span>&nbsp;'.adm_translate("publication(s) attachÈe(s)");
   echo '
         <form action="admin.php" method="post" name="adminForm">
         <div class="form-group">
            <label class="form-control-label" for="rubref">'.adm_translate("Rubrique").'</label>';
   if ($radminsuper==1) {
      echo '
      <select class="form-control" name="rubref">';
         $result = sql_query("SELECT rubid, rubname FROM ".$NPDS_Prefix."rubriques ORDER BY ordre");
         while(list($rubid, $rubname) = sql_fetch_row($result)) {
            if ($rubref==$rubid) {$sel='selected="selected"';} else {$sel='';}
               echo '<option value="'.$rubid.'" '.$sel.'>'.aff_langue($rubname).'</option>';
            }
      echo '
      </select>
      </div>';
   } else {
      echo '<input type="hidden" name="rubref" value="'.$rubref.'" />';
      $result = sql_query("select rubname from ".$NPDS_Prefix."rubriques where rubid='$rubref'");
      list($rubname) = sql_fetch_row($result);
      echo "".aff_langue($rubname)."";
   }
   echo '
   <div class="form-group">
      <label class="form-control-label" for="secname">'.adm_translate("Sous-rubrique").'</label>
      <textarea class="form-control textbox_no_mceEditor" id="secname" name="secname" rows="4" maxlength="255" required="required">'.$secname.'</textarea>
      <span class="help-block text-xs-right"><span id="countcar_secname"></span></span>
   </div>
   <div class="form-group">
      <label class="form-control-label" for="image">'.adm_translate("Images").'</label>
      <input type="text" class="form-control" id="image" name="image" maxlength="255" value="'.$image.'" />
      <span class="help-block text-xs-right"><span id="countcar_image"></span></span>
   </div>
   <div class="form-group">
      <label class="form-control-label" for="introd">'.adm_translate("Texte d'introduction").'</label>
      <textarea class="form-control textbox" id="introd" name="introd" rows="20">'.$intro.'</textarea>
   </div>';
   echo aff_editeur("introd","false");
   droits($userlevel);
   $droit_pub=droits_publication($secid);
   if ($droit_pub==3 or $droit_pub==7) {
      echo '<input type="hidden" name="secid" value="'.$secid.'" />
            <input type="hidden" name="op" value="sectionchange" />
            <button class="btn btn-primary" type="submit">'.adm_translate("Enregistrer").'</button>';
   }
   echo '
   <input class="btn btn-secondary" type="button" value="'.adm_translate("Retour en arriËre").'" onclick="javascript:history.back()" />
   </form>
   <script type="text/javascript">
   //<![CDATA[
         inpandfieldlen("secname",255);
         inpandfieldlen("image",255);
   //]]>
   </script>';
   adminfoot('fv','','','');
}
function sectionmake($secname, $image, $members, $Mmembers, $rubref, $introd) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
      if ($members==0) $members=1;
   }

   $secname = stripslashes(FixQuotes($secname));
   $rubref = stripslashes(FixQuotes($rubref));
   $image = stripslashes(FixQuotes($image));
   $introd = stripslashes(FixQuotes($introd));
   sql_query("INSERT INTO ".$NPDS_Prefix."sections VALUES (NULL,'$secname', '$image', '$members', '$rubref', '$introd','99','0')");

   global $aid; Ecr_Log("security", "CreateSections($secname) by AID : $aid", "");
   Header("Location: admin.php?op=sections");
}
function sectionchange($secid, $secname, $image, $members, $Mmembers, $rubref, $introd) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
      if ($members==0) $members=1;
   }

   $secname = stripslashes(FixQuotes($secname));
   $image = stripslashes(FixQuotes($image));
   $introd = stripslashes(FixQuotes($introd));
   sql_query("update ".$NPDS_Prefix."sections set secname='$secname', image='$image', userlevel='$members', rubid='$rubref', intro='$introd' where secid='$secid'");

   global $aid; Ecr_Log("security", "UpdateSections($secid, $secname) by AID : $aid", "");
   Header("Location: admin.php?op=sections");
}
// Fonctions SECTIONS

// Fonction ARTICLES
function secartedit($artid) {
   global $radminsuper, $radminsection, $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($radminsuper!=1 and $radminsection!=1) {
      Header("Location: admin.php?op=sections");
   }
   $result2 = sql_query("select author, artid, secid, title, content, userlevel, crit1, crit2, crit3, crit4, crit5, crit6, crit7, crit8, crit9, crit10, crit11, crit12, crit13, crit14, crit15, crit16, crit17, crit18, crit19, crit20 from ".$NPDS_Prefix."seccont where artid='$artid'");
   list($author, $artid, $secid, $title, $content, $userlevel, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) = sql_fetch_row($result2);
   if (!$artid) {
      Header("Location: admin.php?op=sections");
   }

   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Editer une publication").'</h3>';
   $title = stripslashes($title);
   $content = stripslashes($content);
   echo '
      <form action="admin.php" method="post" name="adminForm">
         <input type="hidden" name="artid" value="'.$artid.'" />
         <input type="hidden" name="op" value="secartchange" />
         <div class="form-group">
            <div class="row">
               <label class="form-control-label col-sm-4 col-md-4" for="secid">'.adm_translate("Sous-rubriques").'</label>
               <div class="col-sm-8 col-md-8">';

   $tmp_autorise=sousrub_select($secid);
   if ($tmp_autorise) {
      echo $tmp_autorise;
   } else {
      $result = sql_query("select secname from ".$NPDS_Prefix."sections where secid='$secid'");
      list($secname) = sql_fetch_row($result);
      echo "<b>".aff_langue($secname)."</b>";
      echo '<input type="hidden" name="secid" value="'.$secid.'" />';
   }
   echo '
               </div>
            </div>
         </div>';
   if ($tmp_autorise)
      echo "<a href=\"admin.php?op=publishcompat&amp;article=$artid\" class=\"noir\">".adm_translate("Publications connexes")."</a>";

  echo'
         <div class="form-group">
            <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
            <textarea class="textbox_no_mceEditor form-control" name="title" rows="2">'.$title.'</textarea>
         </div>
         <div class="form-group">
            <label class="form-control-label" for="content">'.adm_translate("Contenu").'</label>
            <textarea class="textbox form-control" name="content" rows="30" >'.$content.'</textarea>
         </div>';
   echo aff_editeur("content","false");
   echo '
   <input type="hidden" name="crit1" value="'.$crit1.'" />
   <input type="hidden" name="crit2" value="'.$crit2.'" />
   <input type="hidden" name="crit3" value="'.$crit3.'" />
   <input type="hidden" name="crit4" value="'.$crit4.'" />
   <input type="hidden" name="crit5" value="'.$crit5.'" />
   <input type="hidden" name="crit6" value="'.$crit6.'" />
   <input type="hidden" name="crit7" value="'.$crit7.'" />
   <input type="hidden" name="crit8" value="'.$crit8.'" />
   <input type="hidden" name="crit9" value="'.$crit9.'" />
   <input type="hidden" name="crit10" value="'.$crit10.'" />
   <input type="hidden" name="crit11" value="'.$crit11.'" />
   <input type="hidden" name="crit12" value="'.$crit12.'" />
   <input type="hidden" name="crit13" value="'.$crit13.'" />
   <input type="hidden" name="crit14" value="'.$crit14.'" />
   <input type="hidden" name="crit15" value="'.$crit15.'" />
   <input type="hidden" name="crit16" value="'.$crit16.'" />
   <input type="hidden" name="crit17" value="'.$crit17.'" />
   <input type="hidden" name="crit18" value="'.$crit18.'" />
   <input type="hidden" name="crit19" value="'.$crit19.'" />
   <input type="hidden" name="crit20" value="'.$crit20.'" />
   <div class="form-group">
   ';

   droits($userlevel);
   $droits_pub=droits_publication($secid);
   if ($droits_pub==3 or $droits_pub==7) { echo '<input class="btn btn-primary" type="submit" value="'.adm_translate("Enregistrer").'" />'; }
   echo '&nbsp;<input class="btn btn-secondary" type="button" value="'.adm_translate("Retour en arriËre").'" onclick="javascript:history.back()" />
   </div>
   </form>';
   adminfoot('','','','');
}

function secartupdate($artid) {
   global $hlpfile, $aid, $radminsuper;
   global $NPDS_Prefix;

   $result = sql_query("select author, artid, secid, title, content, userlevel, crit1, crit2, crit3, crit4, crit5, crit6, crit7, crit8, crit9, crit10, crit11, crit12, crit13, crit14, crit15, crit16, crit17, crit18, crit19, crit20 from ".$NPDS_Prefix."seccont_tempo where artid='$artid'");
   list($author, $artid, $secid, $title, $content, $userlevel, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) = sql_fetch_row($result);

   $testpubli = sql_query("select type from ".$NPDS_Prefix."publisujet where secid2='$secid' and aid='$aid' and type='1'");
   list($test_publi)=sql_fetch_row($testpubli);
   if ($test_publi==1) {
      $debut = "<span class=\"text-danger\">".adm_translate("Vos droits de publications vous permettent de mettre ‡ jour ou de supprimer ce contenu mais pas de la mettre en ligne sur le site.")."<br /></span>";
      $fin = "<select class=\"textbox_standard form-control \" name=\"op\">
      <option value=\"secartchangeup\" selected=\"selected\">".adm_translate("Mettre ‡ jour")."</option>
      <option value=\"secartdelete2\">".adm_translate("Supprimer")."</option>
      </select>&nbsp;&nbsp;<input type=\"submit\" class=\"bouton_standard\" name=\"submit\" value=\"".adm_translate("Ok")."\" />";
   }
   $testpubli = sql_query("select type from ".$NPDS_Prefix."publisujet where secid2='$secid' and aid='$aid' and type='2'");
   list($test_publi)=sql_fetch_row($testpubli);
   if (($test_publi==2) or ($radminsuper==1))  {
      $debut = "<span class=\"text-danger\">".adm_translate("Vos droits de publications vous permettent de mettre ‡ jour, de supprimer ou de le mettre en ligne sur le site ce contenu.")."<br /></span>";
      $fin = "<select class=\"textbox_standard form-control\" name=\"op\">
      <option value=\"secartchangeup\" selected=\"selected\">".adm_translate("Mettre ‡ jour")."</option>
      <option value=\"secartdelete2\">".adm_translate("Supprimer")."</option>
      <option value=\"secartpublish\">".adm_translate("Publier")."</option>
      </select>&nbsp;&nbsp;<input type=\"submit\" class=\"bouton_standard\" name=\"submit\" value=\"".adm_translate("Ok")."\" />";
   }
   $fin.="&nbsp;&nbsp;<input class=\"bouton_standard\" type=\"button\" value=\"".adm_translate("Retour en arriËre")."\" onclick=\"javascript:history.back()\" />";
   include("header.php");
   GraphicAdmin($hlpfile);
   opentable();
      echo '<h3>'.adm_translate("Editer une publication").'</h3>';
      echo "<br />\n";
      echo $debut;
      echo "<br />\n";

      $title = stripslashes($title);
      $content = stripslashes($content);
      echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"1\">
      <form action=\"admin.php\" method=\"post\" name=\"adminForm\"><input type=\"hidden\" name=\"artid\" value=\"$artid\" />";
      $rowcolor=tablos();
      echo "<tr $rowcolor><td width=\"20%\"><b>".adm_translate("sous-rubrique")."</b></td>";
      echo "<td width=\"80%\">";
      $tmp_autorise=sousrub_select($secid);
      if ($tmp_autorise) {
         echo $tmp_autorise;
      } else {
         $result = sql_query("select secname from ".$NPDS_Prefix."sections where secid='$secid'");
         list($secname) = sql_fetch_row($result);
         echo "<b>".aff_langue($secname)."</b>";
         echo "<input type=\"hidden\" name=\"secid\" value=\"$secid\" />";
      }
      echo "</td></tr>";
      echo "<tr $rowcolor><td><b>".adm_translate("Titre")."</b></td>";
      echo "<td><textarea class=\"textbox_no_mceEditor\" name=\"title\" cols=\"60\" rows=\"2\">$title</textarea></td></tr>";

      echo "<tr $rowcolor><td><b>".adm_translate("Contenu")."</b></td>";
      echo "<td><textarea class=\"textbox\" name=\"content\" cols=\"60\" rows=\"30\" style=\"width: 100%;\">$content</textarea>";
      echo aff_editeur("content","false");
      echo '</td></tr>';
      echo "<input type=\"hidden\" name=\"crit1\" value=\"$crit1\" /><input type=\"hidden\" name=\"crit2\" value=\"$crit2\" />
            <input type=\"hidden\" name=\"crit3\" value=\"$crit3\" /><input type=\"hidden\" name=\"crit4\" value=\"$crit4\" />
            <input type=\"hidden\" name=\"crit5\" value=\"$crit5\" /><input type=\"hidden\" name=\"crit6\" value=\"$crit6\" />
            <input type=\"hidden\" name=\"crit7\" value=\"$crit7\" /><input type=\"hidden\" name=\"crit8\" value=\"$crit8\" />
            <input type=\"hidden\" name=\"crit9\" value=\"$crit9\" /><input type=\"hidden\" name=\"crit10\" value=\"$crit10\" />
            <input type=\"hidden\" name=\"crit11\" value=\"$crit11\" /><input type=\"hidden\" name=\"crit12\" value=\"$crit12\" />
            <input type=\"hidden\" name=\"crit13\" value=\"$crit13\" /><input type=\"hidden\" name=\"crit14\" value=\"$crit14\" />
            <input type=\"hidden\" name=\"crit15\" value=\"$crit15\" /><input type=\"hidden\" name=\"crit16\" value=\"$crit16\" />
            <input type=\"hidden\" name=\"crit17\" value=\"$crit17\" /><input type=\"hidden\" name=\"crit18\" value=\"$crit18\" />
            <input type=\"hidden\" name=\"crit19\" value=\"$crit19\" /><input type=\"hidden\" name=\"crit20\" value=\"$crit20\" />";

      echo "<tr $rowcolor><td></td><td>";
      droits($userlevel);
      echo "</td></tr><tr $rowcolor><td colspan=\"2\">".$fin."</td><tr>";
      echo '</form></table>';
   closetable();
   include("footer.php");
}
function secarticleadd($secid, $title, $content, $autho, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
   }
   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   $crit1 = stripslashes(FixQuotes($crit1));    $crit11 = stripslashes(FixQuotes($crit11));
   $crit2 = stripslashes(FixQuotes($crit2));    $crit12 = stripslashes(FixQuotes($crit12));
   $crit3 = stripslashes(FixQuotes($crit3));    $crit13 = stripslashes(FixQuotes($crit13));
   $crit4 = stripslashes(FixQuotes($crit4));    $crit14 = stripslashes(FixQuotes($crit14));
   $crit5 = stripslashes(FixQuotes($crit5));    $crit15 = stripslashes(FixQuotes($crit15));
   $crit6 = stripslashes(FixQuotes($crit6));    $crit16 = stripslashes(FixQuotes($crit16));
   $crit7 = stripslashes(FixQuotes($crit7));    $crit17 = stripslashes(FixQuotes($crit17));
   $crit8 = stripslashes(FixQuotes($crit8));    $crit18 = stripslashes(FixQuotes($crit18));
   $crit9 = stripslashes(FixQuotes($crit9));    $crit19 = stripslashes(FixQuotes($crit19));
   $crit10 = stripslashes(FixQuotes($crit10));  $crit20 = stripslashes(FixQuotes($crit20));

   global $radminsection, $radminsuper;
   if ($radminsuper==1) {
      $timestamp=time();
      if ($secid!="0") {
         sql_query("INSERT INTO ".$NPDS_Prefix."seccont VALUES (NULL,'$secid','$title','$content','0','$autho','99','$members', '$crit1', '$crit2', '$crit3', '$crit4', '$crit5', '$crit6', '$crit7', '$crit8', '$crit9', '$crit10', '$crit11', '$crit12', '$crit13', '$crit14', '$crit15', '$crit16', '$crit17', '$crit18', '$crit19', '$crit20', '$timestamp')");
         global $aid; Ecr_Log("security", "CreateArticleSections($secid, $title) by AID : $aid", "");
      }
   } else if ($radminsection==1) {
      if ($secid!="0") {
         sql_query("INSERT INTO ".$NPDS_Prefix."seccont_tempo VALUES (NULL,'$secid','$title','$content','0','$autho','99','$members', '$crit1', '$crit2', '$crit3', '$crit4', '$crit5', '$crit6', '$crit7', '$crit8', '$crit9', '$crit10', '$crit11', '$crit12', '$crit13', '$crit14', '$crit15', '$crit16', '$crit17', '$crit18', '$crit19', '$crit20')");
         global $aid; Ecr_Log("security", "CreateArticleSectionsTempo($secid, $title) by AID : $aid", "");
      }
   }
   Header("Location: admin.php?op=sections");
}
function secartchange($artid, $secid, $title, $content, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
   }
   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   $crit1 = stripslashes(FixQuotes($crit1));    $crit11 = stripslashes(FixQuotes($crit11));
   $crit2 = stripslashes(FixQuotes($crit2));    $crit12 = stripslashes(FixQuotes($crit12));
   $crit3 = stripslashes(FixQuotes($crit3));    $crit13 = stripslashes(FixQuotes($crit13));
   $crit4 = stripslashes(FixQuotes($crit4));    $crit14 = stripslashes(FixQuotes($crit14));
   $crit5 = stripslashes(FixQuotes($crit5));    $crit15 = stripslashes(FixQuotes($crit15));
   $crit6 = stripslashes(FixQuotes($crit6));    $crit16 = stripslashes(FixQuotes($crit16));
   $crit7 = stripslashes(FixQuotes($crit7));    $crit17 = stripslashes(FixQuotes($crit17));
   $crit8 = stripslashes(FixQuotes($crit8));    $crit18 = stripslashes(FixQuotes($crit18));
   $crit9 = stripslashes(FixQuotes($crit9));    $crit19 = stripslashes(FixQuotes($crit19));
   $crit10 = stripslashes(FixQuotes($crit10));  $crit20 = stripslashes(FixQuotes($crit20));
   $timestamp=time();
   if ($secid!="0") {
      sql_query("update ".$NPDS_Prefix."seccont set secid='$secid', title='$title', content='$content', userlevel='$members', crit1='$crit1', crit2='$crit2', crit3='$crit3', crit4='$crit4', crit5='$crit5', crit6='$crit6', crit7='$crit7', crit8='$crit8', crit9='$crit9', crit10='$crit10', crit11='$crit11', crit12='$crit12', crit13='$crit13', crit14='$crit14', crit15='$crit15', crit16='$crit16', crit17='$crit17', crit18='$crit18', crit19='$crit19', crit20='$crit20', timestamp='$timestamp' where artid='$artid'");
      global $aid; Ecr_Log("security", "UpdateArticleSections($artid, $secid, $title) by AID : $aid", "");
   }
   Header("Location: admin.php?op=secartedit&artid=$artid");
}
function secartchangeup($artid, $secid, $title, $content, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
   }

   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   $crit1 = stripslashes(FixQuotes($crit1));     $crit11 = stripslashes(FixQuotes($crit11));
   $crit2 = stripslashes(FixQuotes($crit2));    $crit12 = stripslashes(FixQuotes($crit12));
   $crit3 = stripslashes(FixQuotes($crit3));    $crit13 = stripslashes(FixQuotes($crit13));
   $crit4 = stripslashes(FixQuotes($crit4));    $crit14 = stripslashes(FixQuotes($crit14));
   $crit5 = stripslashes(FixQuotes($crit5));    $crit15 = stripslashes(FixQuotes($crit15));
   $crit6 = stripslashes(FixQuotes($crit6));    $crit16 = stripslashes(FixQuotes($crit16));
   $crit7 = stripslashes(FixQuotes($crit7));    $crit17 = stripslashes(FixQuotes($crit17));
   $crit8 = stripslashes(FixQuotes($crit8));    $crit18 = stripslashes(FixQuotes($crit18));
   $crit9 = stripslashes(FixQuotes($crit9));    $crit19 = stripslashes(FixQuotes($crit19));
   $crit10 = stripslashes(FixQuotes($crit10));     $crit20 = stripslashes(FixQuotes($crit20));
   if ($secid!="0") {
      sql_query("update ".$NPDS_Prefix."seccont_tempo set secid='$secid', title='$title', content='$content', userlevel='$members', crit1='$crit1', crit2='$crit2', crit3='$crit3', crit4='$crit4', crit5='$crit5', crit6='$crit6', crit7='$crit7', crit8='$crit8', crit9='$crit9', crit10='$crit10', crit11='$crit11', crit12='$crit12', crit13='$crit13', crit14='$crit14', crit15='$crit15', crit16='$crit16', crit17='$crit17', crit18='$crit18', crit19='$crit19', crit20='$crit20' where artid='$artid'");
      global $aid; Ecr_Log("security", "UpdateArticleSectionsTempo($artid, $secid, $title) by AID : $aid", "");
   }
   Header("Location: admin.php?op=secartupdate&artid=$artid");
}
function secartpublish($artid, $secid, $title, $content, $author, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20) {
   global $NPDS_Prefix;

   if (is_array($Mmembers) and ($members==1)) {
      $members=implode(",",$Mmembers);
   }
   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   $crit1 = stripslashes(FixQuotes($crit1));    $crit11 = stripslashes(FixQuotes($crit11));
   $crit2 = stripslashes(FixQuotes($crit2));    $crit12 = stripslashes(FixQuotes($crit12));
   $crit3 = stripslashes(FixQuotes($crit3));    $crit13 = stripslashes(FixQuotes($crit13));
   $crit4 = stripslashes(FixQuotes($crit4));    $crit14 = stripslashes(FixQuotes($crit14));
   $crit5 = stripslashes(FixQuotes($crit5));    $crit15 = stripslashes(FixQuotes($crit15));
   $crit6 = stripslashes(FixQuotes($crit6));    $crit16 = stripslashes(FixQuotes($crit16));
   $crit7 = stripslashes(FixQuotes($crit7));    $crit17 = stripslashes(FixQuotes($crit17));
   $crit8 = stripslashes(FixQuotes($crit8));    $crit18 = stripslashes(FixQuotes($crit18));
   $crit9 = stripslashes(FixQuotes($crit9));    $crit19 = stripslashes(FixQuotes($crit19));
   $crit10 = stripslashes(FixQuotes($crit10));  $crit20 = stripslashes(FixQuotes($crit20));
   if ($secid!="0") {
      sql_query("delete from ".$NPDS_Prefix."seccont_tempo where artid='$artid'");
      $timestamp=time();
      sql_query("INSERT INTO ".$NPDS_Prefix."seccont VALUES (NULL,'$secid','$title','$content', '0', '$author', '99', '$members', '$crit1', '$crit2', '$crit3', '$crit4', '$crit5', '$crit6', '$crit7', '$crit8', '$crit9', '$crit10', '$crit11', '$crit12', '$crit13', '$crit14', '$crit15', '$crit16', '$crit17', '$crit18', '$crit19', '$crit20', '$timestamp')");
      global $aid; Ecr_Log("security", "PublicateArticleSections($artid, $secid, $title) by AID : $aid", "");

      $result = sql_query("select email from authors where aid='$author'");
      list($lemail) = sql_fetch_row($result);
      $sujet = adm_translate("Validation de votre publication");
      $message = adm_translate("La publication que vous aviez en attente vient d'Ítre validÈe");
      global $notify_from;
      send_email($lemail, $sujet, $message, $notify_from, true, "html");
   }
   Header("Location: admin.php?op=sections");
}
// Fonction ARTICLES

// Fonctions de DELETE
function rubriquedelete($rubid, $ok=0) {
   global $NPDS_Prefix;

   // protection
   global $radminsuper;
   if (!$radminsuper) {
      Header("Location: admin.php?op=sections");
   }

   if ($ok==1) {
      $result=sql_query("SELECT secid FROM ".$NPDS_Prefix."sections WHERE rubid='$rubid'");
      if (sql_num_rows($result)>0) {
         while(list($secid)=sql_fetch_row($result)) {
            $result2=sql_query("SELECT artid FROM ".$NPDS_Prefix."seccont WHERE secid='$secid'");
            if (sql_num_rows($result2)>0) {
               while(list($artid)=sql_fetch_row($result2)) {
                  sql_query("delete from ".$NPDS_Prefix."seccont where artid='$artid'");
                  sql_query("delete from ".$NPDS_Prefix."compatsujet where id1='$artid'");
               }
            }
         }
      }
      sql_query("delete from ".$NPDS_Prefix."sections where rubid='$rubid'");
      sql_query("delete from ".$NPDS_Prefix."rubriques where rubid='$rubid'");

      global $aid; Ecr_Log("security", "DeleteRubriques($rubid) by AID : $aid", "");
      Header("Location: admin.php?op=sections");
   } else {
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      $result=sql_query("select rubname from ".$NPDS_Prefix."rubriques where rubid='$rubid'");
      list($rubname) = sql_fetch_row($result);
      opentable();
      echo "<p align=\"center\"><b>".adm_translate("Effacer la Rubrique : ").aff_langue($rubname)."</b><br /><br />
      ".adm_translate("Etes-vous s˚r de vouloir effacer cette Rubrique ?")."<br /><br />
      [ <a href=\"admin.php?op=rubriquedelete&amp;rubid=$rubid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=sections\" class=\"noir\">".adm_translate("Non")."</a> ]<br /></p>";
      closetable();
      include("footer.php");
   }
}
function sectiondelete($secid, $ok=0) {
   global $NPDS_Prefix;

   // protection
   $tmp=droits_publication($secid);
   if (($tmp!=7) and ($tmp!=4)) {
      Header("Location: admin.php?op=sections");
   }

   if ($ok==1) {
      $result=sql_query("SELECT artid FROM ".$NPDS_Prefix."seccont WHERE secid='$secid'");
      if (sql_num_rows($result)>0) {
         while(list($artid)=sql_fetch_row($result)) {
            sql_query("delete from ".$NPDS_Prefix."compatsujet where id1='$artid'");
         }
      }
      sql_query("delete from ".$NPDS_Prefix."seccont where secid='$secid'");
      sql_query("delete from ".$NPDS_Prefix."sections where secid='$secid'");

      global $aid; Ecr_Log("security", "DeleteSections($secid) by AID : $aid", "");
      Header("Location: admin.php?op=sections");
   } else {
      global $hlpfile;
      include("header.php");
      GraphicAdmin($hlpfile);
      $result=sql_query("select secname from ".$NPDS_Prefix."sections where secid='$secid'");
      list($secname) = sql_fetch_row($result);
      opentable();
      echo "<p align=\"center\"<b>".adm_translate("Effacer la sous-rubrique : ").aff_langue($secname)."</b><br /><br />
      ".adm_translate("Etes-vous s˚r de vouloir effacer cette sous-rubrique ?")."<br /><br />
      [ <a href=\"admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=sections\" class=\"noir\">".adm_translate("Non")."</a> ]<br /></p>";
      closetable();
      include("footer.php");
   }
}
function secartdelete($artid,$ok=0) {
   global $NPDS_Prefix;

   // protection
   $result = sql_query("select secid from ".$NPDS_Prefix."seccont where artid='$artid'");
   list($secid) = sql_fetch_row($result);
   $tmp=droits_publication($secid);
   if (($tmp!=7) and ($tmp!=4)) {
      Header("Location: admin.php?op=sections");
   }

   if ($ok==1) {
      sql_query("delete from ".$NPDS_Prefix."seccont where artid='$artid'");
      sql_query("delete from ".$NPDS_Prefix."compatsujet where id1='$artid'");

      global $aid; Ecr_Log("security", "DeleteArticlesSections($artid) by AID : $aid", "");
      Header("Location: admin.php?op=sections");
   } else {
       global $hlpfile;
       include ('header.php');
       GraphicAdmin($hlpfile);
       opentable();
       $result = sql_query("select title from ".$NPDS_Prefix."seccont where artid='$artid'");
       list($title) = sql_fetch_row($result);
       echo "<p align=\"center\">".adm_translate("Etes-vous certain de vouloir effacer cette publication ?")." : ".aff_langue($title);
       echo "<br /><br />[ <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=sections\" class=\"noir\">".adm_translate("Non")."</a> ]</p><br />";
       closetable();
       include("footer.php");
   }
}
function secartdelete2($artid, $ok=0) {
   global $NPDS_Prefix;

   if ($ok==1) {
      sql_query("delete from ".$NPDS_Prefix."seccont_tempo where artid='$artid'");

      global $aid; Ecr_Log("security", "DeleteArticlesSectionsTempo($rubid) by AID : $aid", "");
      Header("Location: admin.php?op=sections");
   } else {
      global $hlpfile;
      include ('header.php');
      GraphicAdmin($hlpfile);
      opentable();
      $result = sql_query("select title from ".$NPDS_Prefix."seccont_tempo where artid='$artid'");
      list($title) = sql_fetch_row($result);
      echo "<p align=\"center\">".adm_translate("Etes-vous certain de vouloir effacer cette publication ?")." : ".aff_langue($title);
      echo "<br /><br />[ <a href=\"admin.php?op=secartdelete2&amp;artid=$artid&amp;ok=1\" class=\"rouge\">".adm_translate("Oui")."</a> | <a href=\"admin.php?op=sections\" class=\"noir\">".adm_translate("Non")."</a> ]</p><br />";
      closetable();
      include("footer.php");
   }
}
// Fonctions de DELETE

// Fonctions de classement
function ordremodule() {
   global $hlpfile, $radminsuper, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   if ($radminsuper <> 1) {
      Header("Location: admin.php?op=sections");
   }
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Changer l'ordre")." ".adm_translate("des")." ".adm_translate("rubriques").'</h3>
   <form action="admin.php" method="post" name="adminForm">
      <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
         <thead class="thead-inverse">
            <tr>
               <th data-sortable="true">'.adm_translate("Rubriques").'</th>
               <th data-sortable="true">'.adm_translate("Index").'</th>
            </tr>
         </thead>
         <tbody>';
   $result = sql_query("select rubid, rubname, ordre from ".$NPDS_Prefix."rubriques order by ordre");
   $i = 0;
   while(list($rubid, $rubname, $ordre) = sql_fetch_row($result)) {
      $i++;
      echo '<tr>
               <td width="80%"><label for="ordre['.$i.']">'.aff_langue($rubname).'</label></td>
               <td width="20%"><input type="hidden" name="rubid['.$i.']" value="'.$rubid.'" />
                  <input type="number" class="form-control" name="ordre['.$i.']" value="'.$ordre.'" min="0" max="999" />
               </td>
           </tr>';
      }
   echo '
         </tbody>
      </table>
      <br />
      <div class="form-group">
         <input type="hidden" name="i" value="'.$i.'" />
         <input type="hidden" name="op" value="majmodule" />
         <button type="submit" class="btn btn-primary" ><i class="fa fa-check"></i>'.adm_translate("Valider").'</button>
         <button class="btn btn-secondary" onclick="javascript:history.back()" >'.adm_translate("Retour en arriËre").'</button>
      </div>
   </form>';
   adminfoot('fv','','','');
}
function ordrechapitre() {
   global $rubname, $rubid, $NPDS_Prefix, $hlpfile, $radminsuper, $f_meta_nom, $f_titre, $adminimg;
   if ($radminsuper <> 1) {
      Header("Location: admin.php?op=sections");
   }
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Changer l'ordre").' '.adm_translate("des").' '.adm_translate("sous-rubriques").' '.adm_translate("dans").' / '.$rubname.'</h3>
   <form action="admin.php" method="post" name="adminForm">
      <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
         <thead>
            <tr>
               <th data-sortable="true">'.adm_translate("Sous-rubriques").'</th>
               <th data-sortable="true">'.adm_translate("Index").'</th>
            </tr>
         </thead>
         <tbody>';

   $result = sql_query("select secid, secname, ordre from ".$NPDS_Prefix."sections where rubid='$rubid' order by ordre");
   $i=0;
   while(list($secid, $secname, $ordre) = sql_fetch_row($result)) {
      $i++;
      echo '<tr>
           <td width="80%">'.aff_langue($secname).'</td>
           <td width="20%"><input type="hidden" name="secid['.$i.']" value="'.$secid.'" />
           <input type="number" class="form-control" name="ordre['.$i.']" value="'.$ordre.'" min="0" max="9999" /></td>
           </tr>';
   }
   echo '
         </tbody>
      </table>
      <br />
      <div class="form-group">
         <input type="hidden" name="op" value="majchapitre" />
         <input type="submit" class="btn btn-primary" value="'.adm_translate("Valider").'" />
         <button class="btn btn-secondary" onclick="javascript:history.back()" >'.adm_translate("Retour en arriËre").' </button>
      </div>
   </form>';
   adminfoot('fv','','','');
}
function ordrecours() {
   global $secid, $hlpfile, $radminsuper;
   global $NPDS_Prefix;

   if ($radminsuper <> 1) {
      Header("Location: admin.php?op=sections");
   }

   include("header.php");
   GraphicAdmin($hlpfile);
   opentable();
   $result = sql_query("select secname from ".$NPDS_Prefix."sections where secid='$secid'");
   list($secname) = sql_fetch_row($result);
   echo '<h3>'.adm_translate("Changer l'ordre").' '.adm_translate("des").' '.adm_translate("publications").' / '.aff_langue($secname).'</h3>';

   echo '<form action="admin.php" method="post" name="adminForm">';
   echo '<table>
         <tr>
         <td class="header">'.adm_translate("Publications").'</td>
         <td class="header">'.adm_translate("Index").'</td>
         </tr>';

   $result = sql_query("select artid, title, ordre from ".$NPDS_Prefix."seccont where secid='$secid' order by ordre");
   $i=0;
   while(list($artid, $title, $ordre) = sql_fetch_row($result)) {
      $rowcolor=tablos();
      $i++;
      echo "<tr>
            <td width=\"95%\">".aff_langue($title)."</td>
            <td width=\"5%\"><input type=\"hidden\" name=\"artid[$i]\" value=\"$artid\" />
            <input type=\"text\" class=\"textbox\" name=\"ordre[$i]\" value=\"$ordre\" size=\"3\" /></td>
            </tr>";
   }
   echo "</table>";
   echo "<input type=\"hidden\" name=\"op\" value=\"majcours\" />
         <br /><input type=\"submit\" class=\"btn btn-primary\" value=\"".adm_translate("Valider")."\" />
         &nbsp;&nbsp;<input type=\"button\" class=\"bnt btn-secondary\" value=\"".adm_translate("Retour en arriËre")."\" onclick=\"javascript:history.back()\" />
         </form>";
   closetable();
   include("footer.php");
}
function updateordre($rubid, $artid, $secid, $op, $ordre) {
   global $NPDS_Prefix;

   global $radminsuper;
   if ($radminsuper!=1) {
      Header("Location: admin.php?op=sections");
   }

   if ($op=="majchapitre") {
      $i=count($secid);
      for ($j = 1; $j < ($i+1); $j++) {
         $sec = $secid[$j];
         $ord = $ordre[$j];
         $result=sql_query("update ".$NPDS_Prefix."sections set ordre='$ord' where secid='$sec'");
      }
   }
   if ($op=="majmodule") {
      $i=count($rubid);
      for ($j = 1; $j < ($i+1); $j++) {
         $rub = $rubid[$j];
         $ord = $ordre[$j];
         $result=sql_query("update ".$NPDS_Prefix."rubriques set ordre='$ord' where rubid='$rub'");
      }
   }
   if ($op=="majcours") {
      $i=count($artid);
      for ($j = 1; $j < ($i+1); $j++) {
         $art = $artid[$j];
         $ord = $ordre[$j];
         $result=sql_query("update ".$NPDS_Prefix."seccont set ordre='$ord' where artid='$art'");
      }
   }
   Header("Location: admin.php?op=sections");
}
// Fonctions de classement

// Fonctions DROIT des AUTEURS
function publishrights($author) {
   global $NPDS_Prefix, $hlpfile, $radminsuper, $f_meta_nom, $f_titre, $adminimg;

   if ($radminsuper!=1) {
      Header("Location: admin.php?op=sections");
   }
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead($f_meta_nom, $f_titre, $adminimg);
   echo '
   <h3>'.adm_translate("Droits des auteurs").' :: '.$author.'</h3>
   <form action="admin.php" method="post">';
   include_once ("lib/togglediv.class.php");
   $result1 = sql_query("select rubid, rubname from ".$NPDS_Prefix."rubriques order by ordre");
   $numrow=sql_num_rows($result1);
   $toggle = new ToggleDiv($numrow);
   echo $toggle->All();
   echo "<hr noshade=\"noshade\" class=\"ongl\" />";

   $i = 0;
   while(list($rubid, $rubname) = sql_fetch_row($result1)) {
      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"0\" border=\"0\">
            <tr>
            <td class=\"header\" width=\"40%\">";
            echo $toggle->Img();
            echo aff_langue($rubname)."</td>
            <td class=\"header\"align=\"center\" width=\"15%\">".adm_translate("CrÈer")."</td>
            <td class=\"header\"align=\"center\" width=\"15%\">".adm_translate("Publier")."</td>
            <td class=\"header\"align=\"center\" width=\"15%\">".adm_translate("Modifier")."</td>
            <td class=\"header\"align=\"center\" width=\"15%\">".adm_translate("Supprimer")."</td>
            </tr></table>";

      echo $toggle->Begin();
      $result2 = sql_query("select secid, secname from ".$NPDS_Prefix."sections where rubid='$rubid' order by ordre");
      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\">";
      $rowcolor = tablos();
      while(list($secid, $secname) = sql_fetch_row($result2)) {
         $result3 = sql_query("select type from ".$NPDS_Prefix."publisujet where secid2='$secid' and aid='$author'");
         $i++;
         $crea="";$publi="";$modif="";$supp="";
         if (sql_num_rows($result3) > 0) {
            while(list($type) = sql_fetch_row($result3)) {
               if ($type==1) {$crea="checked=\"checked\"";}
               else if ($type==2) {$publi="checked=\"checked\"";}
               else if ($type==3) {$modif="checked=\"checked\"";}
               else if ($type==4) {$supp="checked=\"checked\"";}
            }
         }
         echo "<tr>
               <td width=\"40%\">".aff_langue($secname)."</td>
               <td align=\"center\" width=\"15%\"><input type=\"checkbox\" name=\"creation[$i]\" value=\"$secid\" $crea /></td>
               <td align=\"center\" width=\"15%\"><input type=\"checkbox\" name=\"publication[$i]\" value=\"$secid\" $publi /></td>
               <td align=\"center\" width=\"15%\"><input type=\"checkbox\" name=\"modification[$i]\" value=\"$secid\" $modif /></td>
               <td align=\"center\" width=\"15%\"><input type=\"checkbox\" name=\"suppression[$i]\" value=\"$secid\" $supp /></td>
               </tr>";
      }
      echo '</table>';
      echo $toggle->End();
      echo '<br />';
   }
   echo '<input type="hidden" name="chng_aid" value="'.$author.'" />
         <input type="hidden" name="op" value="updatedroitauteurs" />
         <input type="hidden" name="maxindex" value="'.$i.'" />
         <input class="btn btn-primary" type="submit" value="'.adm_translate("Valider").'" />&nbsp;&nbsp;
         <input class="btn btn-secondary" type="button" onclick="javascript:history.back()" value="'.adm_translate("Retour en arriËre").'" />
         </form>';

   closetable();
   include("footer.php");
}

function updaterights($chng_aid, $maxindex, $creation, $publication, $modification, $suppression) {
   global $NPDS_Prefix;

   global $radminsuper;
   if ($radminsuper!=1) {
      Header("Location: admin.php?op=sections");
   }

   $result=sql_query("delete from ".$NPDS_Prefix."publisujet where aid='$chng_aid'");
   for ($j = 1; $j < ($maxindex+1); $j++) {
      if ($creation[$j]!="") { $result=sql_query("insert into ".$NPDS_Prefix."publisujet values ('$chng_aid','$creation[$j]','1')"); }
      if ($publication[$j]!="") { $result=sql_query("insert into ".$NPDS_Prefix."publisujet values ('$chng_aid','$publication[$j]','2')"); }
      if ($modification[$j]!="") { $result=sql_query("insert into ".$NPDS_Prefix."publisujet values ('$chng_aid','$modification[$j]','3')"); }
      if ($suppression[$j]!="") { $result=sql_query("insert into ".$NPDS_Prefix."publisujet values ('$chng_aid','$suppression[$j]','4')"); }
   }

   global $aid; Ecr_Log("security", "UpdateRightsPubliSujet($chng_aid) by AID : $aid", "");
   Header("Location: admin.php?op=sections");
}
// Fonctions DROIT des AUTEURS

// Fonctions Param du menu barre des sections
function menudyn_save($sections_chemin, $togglesection) {
   $file = fopen("sections.config.php", "w");
   $content = "<?php\n";
   $content .= "$line";
   $content .= "# # DUNE by NPDS : Net Portal Dynamic System\n";
   $content .= "# ===================================================\n";
   $content .= "#\n";
   $content .= "# This version name NPDS Copyright (c) 2001-2012 by Philippe Brunier\n";
   $content .= "#\n";
   $content .= "# This module is to configure the Sections \n";
   $content .= "#\n";
   $content .= "# This program is free software. You can redistribute it and/or modify\n";
   $content .= "# it under the terms of the GNU General Public License as published by\n";
   $content .= "# the Free Software Foundation; either version 2 of the License.\n";
   $content .= "\n";
   $content .= "\$sections_chemin=$sections_chemin;\n";
   $content .= "\$togglesection=$togglesection;\n";
   $content .= "?>";
   fwrite($file, $content);
   fclose($file);
   Header("Location: admin.php?op=sections");
}
// Fonctions Param du menu barre des sections

switch ($op) {
      case "new_rub_section":    new_rub_section($type); break;
      case "sections":           sections(); break;
      case "sectionedit":        sectionedit($secid); break;
      case "sectionmake":        sectionmake($secname, $image, $members, $Mmembers, $rubref, $introd); break;
      case "sectiondelete":      sectiondelete($secid, $ok); break;
      case "sectionchange":      sectionchange($secid, $secname, $image, $members, $Mmembers, $rubref, $introd); break;

      case "rubriquedit":        rubriquedit($rubid); break;
      case "rubriquemake":       rubriquemake($rubname, $introc); break;
      case "rubriquedelete":     rubriquedelete($rubid, $ok); break;
      case "rubriquechange":     rubriquechange($rubid,$rubname,$introc,$enligne); break;

      case "secarticleadd":      secarticleadd($secid, $title, $content, $autho, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20); break;
      case "secartedit":         secartedit($artid); break;
      case "secartchange":       secartchange($artid, $secid, $title, $content, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20); break;
      case "secartchangeup":     secartchangeup($artid, $secid, $title, $content, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20); break;
      case "secartdelete":       secartdelete($artid, $ok); break;
      case "secartpublish":      secartpublish($artid, $secid, $title, $content, $author, $members, $Mmembers, $crit1, $crit2, $crit3, $crit4, $crit5, $crit6, $crit7, $crit8, $crit9, $crit10, $crit11, $crit12, $crit13, $crit14, $crit15, $crit16, $crit17, $crit18, $crit19, $crit20); break;
      case "secartupdate":       secartupdate($artid); break;
      case "secartdelete2":      secartdelete2($artid, $ok); break;

      case "ordremodule":        ordremodule(); break;
      case "ordrechapitre":      ordrechapitre(); break;
      case "ordrecours":         ordrecours(); break;

      case "majmodule":          updateordre($rubid, $artid, $secid, $op, $ordre); break;
      case "majchapitre":        updateordre($rubid, $artid, $secid, $op, $ordre); break;
      case "majcours":           updateordre($rubid, $artid, $secid, $op, $ordre); break;

      case "publishcompat":      publishcompat($article); break;
      case "updatecompat":       updatecompat($article, $admin_rub, $idx); break;

      case "droitauteurs":       publishrights($author); break;
      case "updatedroitauteurs": updaterights($chng_aid, $maxindex, $creation, $publication, $modification, $suppression); break;

      case "menu_dyn":           menudyn_save($sections_chemin, $togglesection); break;
}
?>