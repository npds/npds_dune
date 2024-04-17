<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
   include('die.php');
$f_meta_nom ='mblock';
$f_titre = adm_translate("Bloc Principal");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/mainblock.html";

function mblock() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
   <hr />
   <h3>'.adm_translate("Edition du Bloc Principal").'</h3>';
   $result = sql_query("SELECT title, content FROM ".$NPDS_Prefix."block WHERE id=1");
   if (sql_num_rows($result) > 0) {
      while(list($title, $content) = sql_fetch_row($result)) {
         echo '
         <form id="fad_mblock" action="admin.php" method="post">
            <div class="form-floating mb-3">
               <textarea class="form-control" type="text" id="title" name="title" maxlength="1000" placeholder="'.adm_translate("Titre :").'" style="height:70px;">'.$title.'</textarea>
               <label for="title">'.adm_translate("Titre").'</label>
               <span class="help-block text-end"><span id="countcar_title"></span></span>
            </div>
            <div class="form-floating mb-3">
               <textarea class="form-control" id="content" name="content" style="height:170px;">'.$content.'</textarea>
               <label for="content">'.adm_translate("Contenu").'</label>
            </div>
            <input type="hidden" name="op" value="changemblock" />
            <button class="btn btn-primary btn-block" type="submit">'.adm_translate("Valider").'</button>
         </form>
         <script type="text/javascript">
         //<![CDATA[
            $(document).ready(function() {
               inpandfieldlen("title",1000);
            });
         //]]>
         </script>';
      }
   }
   adminfoot('fv','','','');
}

function changemblock($title, $content) {
   global $NPDS_Prefix;
   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   sql_query("UPDATE ".$NPDS_Prefix."block SET title='$title', content='$content' WHERE id='1'");
   global $aid; Ecr_Log('security', "ChangeMainBlock(".aff_langue($title).") by AID : $aid", '');
   Header("Location: admin.php?op=adminMain");
}

switch ($op) {
   case 'mblock':
      mblock();
   break;
    case 'changemblock':
      changemblock($title, $content);
   break;
}
?>