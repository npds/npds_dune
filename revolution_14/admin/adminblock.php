<?php
/************************************************************************/
/* DUNE by NPDS - admin prototype                                       */
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
$f_meta_nom ='ablock';
$f_titre = adm_translate("Bloc Administration");
//==> controle droit
admindroits($aid,$f_meta_nom);
//<== controle droit
global $language;
$hlpfile = "manuels/$language/adminblock.html";

function ablock() {
   global $hlpfile, $NPDS_Prefix, $f_meta_nom, $f_titre, $adminimg;
   include("header.php");
   GraphicAdmin($hlpfile);
   adminhead ($f_meta_nom, $f_titre, $adminimg);
   echo '
      <h3>'.adm_translate("Editer le Bloc Administration").'</h3>';
   $result = sql_query("SELECT title, content FROM ".$NPDS_Prefix."adminblock");
   if (sql_num_rows($result) > 0) {
      while (list($title, $content) = sql_fetch_row($result)) {
         echo '
         <script type="text/javascript" src="lib/js/checkfieldinp.js"></script>
         <form id="fad_ablock" action="admin.php" method="post">
            <div class="form-group">
                <label class="form-control-label" for="title">'.adm_translate("Titre").'</label>
                <textarea class="form-control" type="text" name="title" id="title" maxlength="250">'.$title.'</textarea>
                <span class="help-block text-xs-right"><span id="countcar_title"></span></span>
            </div>
            <div class="form-group">
                <label class="form-control-label" for="content">'.adm_translate("Contenu").'</label>
                <textarea class="form-control" type="text" rows="25" name="content" id="content">'.$content.'</textarea>
            </div>
            <input type="hidden" name="op" value="changeablock" />
            <div class="form-group">
               <button class="btn btn-primary-outline btn-block" type="submit"><i class ="fa fa-check fa-lg"></i>&nbsp;'.adm_translate("Valider").'</button>
            </div>
         </form>
         <script type="text/javascript">
         //<![CDATA[
            inpandfieldlen("title",250);
         //]]>
         </script>';
      }
   }
//   adminfieldinp($result);//waiting for better mysqlilib
   adminfoot('fv','','','');
}

function changeablock($title, $content) {
   global $NPDS_Prefix;
   $title = stripslashes(FixQuotes($title));
   $content = stripslashes(FixQuotes($content));
   sql_query("UPDATE ".$NPDS_Prefix."adminblock SET title='$title', content='$content'");
   global $aid; Ecr_Log("security", "ChangeAdminBlock() by AID : $aid", "");
   Header("Location: admin.php?op=adminMain");
}

switch ($op) {
   case "ablock":
        ablock();
        break;
   case "changeablock":
        changeablock($title, $content);
        break;
}
?>