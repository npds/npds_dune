<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (preg_match("#upload\.func\.forum\.php#", $PHP_SELF)) { die(); }

if (!isset($upload_conf)) {
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/include_forum/upload.conf.forum.php");
   include_once("lib/file.class.php");
}

/************************************************************************/
/* Fonction pour charger en memoire les mimetypes                       */
/************************************************************************/
function load_mimetypes () {
   global $mimetypes, $mimetype_default, $mime_dspinl, $mime_dspfmt, $mime_renderers, $att_icons, $att_icon_default, $att_icon_multiple;
   if (defined ('ATT_DSP_LINK')) {
      return;
   }

   if (file_exists("modules/upload/include/mimetypes.php") ) {
      include ("modules/upload/include/mimetypes.php");
   }
}
/************************************************************************/
/* Fonction qui retourne ou la liste ou l'attachement voulu             */
/************************************************************************/
function getAttachments ($apli, $post_id, $att_id=0, $Mmod=0 ) {
   global $upload_table;
   $query = "SELECT att_id, att_name, att_type, att_size, att_path, inline, compteur, visible FROM $upload_table WHERE apli='$apli' && post_id='$post_id'";
   if ($att_id>0) {
       $query .= " AND att_id=$att_id";
   }
   if (!$Mmod) {
       $query .= " AND visible=1";
   }
   $query .= " ORDER BY att_type,att_name";
   $result = sql_query($query);
   $i=0;
   while ($attach=sql_fetch_assoc($result)) {
      $att[$i] = $attach;
      $i++;
   }
   return ($i==0 ) ? "" : $att;
}

/************************************************************************/
/* Fonction permettant de cree une checkbox                             */
/************************************************************************/
function getCheckBox ($name, $value=1, $current, $text="") {
   $p =  sprintf ("<input type=\"checkbox\" name=\"%s\" value=\"%s\"%s />%s\n",
         $name,
         $value, ("$current"=="$value")? " checked" : "",
         (empty ($text)) ? "" : " $text" );
   return $p;
}
/************************************************************************/
/* Fonction permettant une liste de choix                               */
/************************************************************************/
function getListBox ($name, $items, $selected="", $multiple=0, $onChange="") {
   $oc = empty ($onChange) ? "" : " onchange=\"$onChange\"";
   $p = sprintf ("<select class=\"c-select form-control-sm\" name=\"%s%s\"%s%s>\n", $name, ($multiple == 1)?"[]":"",
                ($multiple == 1)?" multiple":"", $oc);
   if (is_array($items)) {
      reset ($items);
      while (list($k, $v) = each($items)) {
         $p .= sprintf(" <option value=\"%s\"%s>%s\n", $k, strcmp($selected,$k)?"":" selected", $v);
      }
   }
   return $p . "</select>\n";
}
/************************************************************************/
/* Pour la class                                                        */
/************************************************************************/
/************************************************************************/
/* Ajoute l'attachement dans la base de donnÈ                           */
/************************************************************************/
function insertAttachment ($apli, $IdPost, $IdTopic, $IdForum, $name, $path, $inline="A", $size=0, $type="") {
   global $upload_table, $visible_forum;
   $size = empty ($size) ? filesize($path) : $size;
   $type = empty ($type) ? "application/octet-stream" : $type;
   $stamp = time();
   $sql = "INSERT INTO $upload_table VALUES ('', '$IdPost', '$IdTopic','$IdForum', '$stamp', '$name', '$type', '$size', '$path', '1', '$apli', '0', '$visible_forum')";
   $ret = sql_query($sql);
   if (!$ret) {
      return -1;
   }
   return sql_last_id ();
}
/************************************************************************/
/* Suprime l'attachement dans la base de donné en cas d erreur d'upload */
/************************************************************************/
function deleteAttachment ($apli, $IdPost, $upload_dir, $id, $att_name){
   global $upload_table;
   @unlink("$upload_dir/$id.$apli.$att_name");
   $sql = "DELETE FROM $upload_table WHERE att_id= '$id'";
   sql_query($sql);
}
/************************************************************************/
/* Pour la visualisation dans les forum                                 */
/************************************************************************/
/* Fonction de snipe pour l'affichage des fichier uploader dans forums  */
/************************************************************************/
function display_upload($apli,$post_id,$Mmod){
   $att_size = '';
   $att_type = '';
   $att_name = '';
   $att_url = '';
   $att_link = '';
   $attachments = '';
   $att_icon = '';
   $num_cells = 5;
   $att = getAttachments ($apli,$post_id,0,$Mmod);
   if (is_array($att)) {
      $att_count = count($att);
//      $attachments = "\n<table id=\"ooo\" class=\"fo-post-mes\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">\n<tr>";
      $attachments = '
      <div class="list-group">
         <div class="list-group-item">
            <img class="smil" src="themes/npds-boost_sk/images/forum/subject/1F4CE.png" border="0" alt="icon_post" />
            <span class="text-muted">'.upload_translate("Pièces jointes").'</span>
            <span class="label label-default label-pill pull-right">'.$att_count.'</span>
         </div>';

      $ncell = 0;
      for ($i=0; $i<$att_count; $i++) {
         $att_id        = $att[$i]["att_id"];
         $att_name      = $att[$i]["att_name"];
         $att_path      = $att[$i]["att_path"];
         $att_type      = $att[$i]["att_type"];
         $att_size      = (integer) $att[$i]["att_size"];
         $compteur      = $att[$i]["compteur"];
         $visible       = $att[$i]["visible"];
         $att_inline    = $att[$i]["inline"];
         if (!$visible) {$marqueurV="@";} else {$marqueurV='';}
         $att_link      = getAttachmentUrl ($apli, $post_id, $att_id, "$att_path/$att_id.$apli.".$marqueurV."$att_name", $att_type, $att_size, $att_inline, $compteur, $visible, $Mmod);
         // check for full row
         if (++$ncell > $num_cells) {
//            $attachments .= "</tr>\n<tr>";
            $ncell = 1;
         }
         if (getAttDisplayMode($att_type, $att_inline) != ATT_DSP_LINK) {
            if ($ncell > 1)   {
               while ($ncell++ <= $num_cells)
//                  $attachments .= "<td>&nbsp;</td>";
               $attachments .= "</tr>\n<tr>";
            }
            $ncell = $num_cells + 1;
//            $td = "<td colspan=\"".$num_cells."\">";
         } else {
//            $td = "<td>";
         }
//         $attachments .= $td.$att_link."</td>";
         $attachments .= $att_link;
         $att_list[$att_id] = $att_name;
      }
//      $attachments .= "</tr></table>";
      $attachments .= '</div>';

      return $attachments;
   }
}

/************************************************************************/
/* Retourne Le mode d affichage pour un attachement                     */
/* 1   display as icon (link)                                           */
/* 2   display as image                                                 */
/* 3   display as embedded HTML text or the source                      */
/* 4   display as embedded text, PRE-formatted                          */
/* 5   display as flash animation                                       */
/************************************************************************/
function getAttDisplayMode ($att_type, $att_inline="A") {
   global $mime_dspfmt, $mimetype_default, $ext;

   load_mimetypes();

   if ($att_inline) {
      if (isset($mime_dspfmt[$att_type])) {
          $display_mode = $mime_dspfmt[$att_type];
      } else {
          $display_mode = $mime_dspfmt[$mimetype_default];
      }
   } else {
       $display_mode = ATT_DSP_LINK;
   }

   return $display_mode;
}

/************************************************************************/
/* Retourne l'icon                                                      */
/************************************************************************/
function att_icon ($filename) {
   global $att_icons, $att_icon_default,  $att_icon_multiple;

   load_mimetypes();

   $suffix = strtoLower(substr(strrchr( $filename, '.' ), 1 ));
   return (isset($att_icons[$suffix]) ) ? $att_icons[$suffix] : $att_icon_default;
}
/************************************************************************/
/* Partie Graphique                                                     */
/************************************************************************/
/* Controle la taille de l image a afficher                             */
/************************************************************************/
function verifsize ($size) {
   $width_max = 500;
   $height_max = 500;

   if ($size[0]==0) {$size[0]=ceil($width_max/3);}
   if ($size[1]==0) {$size[1]=ceil($height_max/3);}
   $width = $size[0];
   $height = $size[1];

   if($width > $width_max){
      $imageProp = ($width_max * 100) / $width;
      $height = ceil(($height * $imageProp) / 100);
      $width = $width_max;
   }
   if($height > $height_max){
      $imageProp = ($height_max * 100) / $height;
      $width = ceil(($width * $imageProp) / 100);
      $height = $height_max;
   }
   return ('width="'.$width.'" height="'.$height.'"');
}
/************************************************************************/
/* Retourne l'attachement                                               */
/************************************************************************/
function getAttachmentUrl ($apli, $post_id, $att_id, $att_path, $att_type, $att_size, $att_inline=0, $compteur, $visible=0, $Mmod) {
   global $icon_dir, $img_dir, $forum;
   global $mimetype_default, $mime_dspfmt, $mime_renderers;
   global $DOCUMENTROOT;

   load_mimetypes();
   $att_name = substr(strstr (basename($att_path), '.'), 1);
   $att_name = substr(strstr (basename($att_name), '.'), 1);
   $att_path = $DOCUMENTROOT.$att_path;
   if (!is_file($att_path)) {
       return '&nbsp;<span class="text-danger" style="font-size: .65rem;">'.upload_translate("Fichier non trouvé").' : '.$att_name.'</span>';
   }

   if ($att_inline) {
      if (isset($mime_dspfmt[$att_type])) {
         $display_mode = $mime_dspfmt[$att_type];
      } else {
         $display_mode = $mime_dspfmt[$mimetype_default];
      }
   } else {
      $display_mode = ATT_DSP_LINK;
   }
   if ($Mmod) {
      global $userdata;
      $marqueurM="&amp;Mmod=".substr($userdata[2],8,6);
   } else {
      $marqueurM="";
   }
   $att_url= "getfile.php?att_id=$att_id&amp;apli=$apli".$marqueurM."&amp;att_name=".rawurlencode($att_name);

   if ($visible!=1) {
      $visible_wrn = '&nbsp;<span class="text-danger" style="font-size: .65rem;">'.upload_translate("Fichier non visible").'</span>';
   }

   switch ($display_mode) {
      case ATT_DSP_IMG: // display as an embedded image
         $size = @getImageSize ("$att_path");
//         $img_size = verifsize( $size );
         $img_size = 'style="max-width: 100%; height:auto;"';
         $text = str_replace('"','\"', $mime_renderers[ATT_DSP_IMG]);
         eval ("\$ret=stripSlashes(\"$text\");");
         break;
      case ATT_DSP_PLAINTEXT: // display as embedded text, PRE-formatted
         $att_contents = str_replace ("\\", "\\\\", htmlSpecialChars (join('',file ($att_path)),ENT_COMPAT|ENT_HTML401,cur_charset));
         $att_contents = word_wrap ($att_contents);
         $text = str_replace('"','\"', $mime_renderers[ATT_DSP_PLAINTEXT]);
         eval ("\$ret=\"$text\";");
         break;
      case ATT_DSP_HTML: // display as embedded HTML text
         //au choix la source ou la page
         $att_contents = word_wrap (nl2br(scr_html (join ("", file ($att_path)))));
         //$att_contents = removeHack (join ("", file ($att_path)));
         $text = str_replace('"','\"', $mime_renderers[ATT_DSP_HTML]);
         eval ("\$ret=stripSlashes(\"$text\");");
         break;
      case ATT_DSP_SWF: // Embedded Macromedia Shockwave Flash
         $size = @getImageSize ("$att_path");
         $img_size = verifsize( $size );
         $text = str_replace('"','\"', $mime_renderers[ATT_DSP_SWF]);
         eval ("\$ret=stripSlashes(\"$text\");");
         break;
      default: // display as link
         $Fichier = new FileManagement;
         $att_size = $Fichier->file_size_format($att_size, 1);
         $att_icon = att_icon($att_name);
         $text = str_replace('"','\"', $mime_renderers[ATT_DSP_LINK]);
         eval ("\$ret=stripSlashes(\"$text\");");
         break;
   }
   return $ret;
}

/************************************************************************/
/* Fonction d'affichage des fichier text directement                    */
/************************************************************************/
/* Copyright 1999 Dominic J. Eidson, use as you wish, but give credit   */
/* where credit due.                                                    */
/************************************************************************/
function word_wrap ($string, $cols = 80, $prefix = "") {
   $t_lines = explode("\n", $string);
   $outlines = "";
   while (list(, $thisline) = each($t_lines)) {
      if (strlen($thisline) > $cols) {
         $newline = "";
         $t_l_lines = explode(" ", $thisline);
         while (list(, $thisword) = each($t_l_lines)) {
            while ((strlen($thisword) + strlen($prefix)) > $cols) {
               $cur_pos = 0;
               $outlines .= $prefix;
               for ($num=0; $num < $cols-1; $num++) {
                  $outlines .= $thisword[$num];
                  $cur_pos++;
               }
               $outlines .= "\n";
               $thisword = substr($thisword, $cur_pos, (strlen($thisword)-$cur_pos));
            }
            if ((strlen($newline) + strlen($thisword)) > $cols) {
               $outlines .= $prefix.$newline."\n";
               $newline = $thisword." ";
            } else {
               $newline .= $thisword." ";
            }
         }
         $outlines .= $prefix.$newline."\n";
      } else {
         $outlines .= $prefix.$thisline."\n";
      }
   }
   return $outlines;
}

/***********************************************/
/* Affiche la source d une page html           */
/***********************************************/
function scr_html ($text) {
   $text = str_replace ("<", "&lt;", $text);
   $text = str_replace (">", "&gt;", $text);
   return $text;
}

/*****************************************************/
/* Effacer les fichier joint demander                */
/*****************************************************/
function delete($del_att){
   global $upload_table, $rep_upload_forum, $apli;
   global $DOCUMENTROOT;

   $rep=$DOCUMENTROOT;
   if (is_array($del_att) ) {
      $del_att = implode ($del_att, ",");
      $sql = "SELECT att_id, att_name, att_path from $upload_table WHERE att_id IN ($del_att)";
      $result=sql_query($sql);
      while(list($att_id, $att_name, $att_path)=sql_fetch_row($result)){
         @unlink($rep."$att_path/$att_id.$apli.$att_name");
      }
      $sql = "DELETE FROM $upload_table WHERE att_id IN ($del_att)";
      sql_query($sql);
   }
}

/*****************************************************/
/* Update le type d affichage                        */
/*****************************************************/
function update_inline($inline_att) {
   global $upload_table;
   if (is_array ($inline_att) ) {
      reset($inline_att);
      while (list($id, $mode) = each($inline_att)) {
         $sql = "UPDATE $upload_table SET inline='$mode' WHERE att_id=$id";
         sql_query($sql);
      }
   }
}
/*****************************************************/
/* Update la visibilitÈe                             */
/*****************************************************/
function renomme_fichier($listeV, $listeU) {
   global $upload_table, $apli, $DOCUMENTROOT;
   $query = "SELECT att_id, att_name, att_path FROM $upload_table WHERE att_id in ($listeV) and visible=1";
   $result = sql_query($query);
   while ($attach=sql_fetch_assoc($result)) {
      if (!file_exists($DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".".$attach['att_name'])) {
         rename($DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".@".$attach['att_name'],$DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".".$attach['att_name']);
      }
   }
   $query = "SELECT att_id, att_name, att_path FROM $upload_table WHERE att_id in ($listeU) and visible=0";
   $result = sql_query($query);
   while ($attach=sql_fetch_assoc($result)) {
      if (!file_exists($DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".@".$attach['att_name'])) {
         rename($DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".".$attach['att_name'],$DOCUMENTROOT.$attach['att_path'].$attach['att_id'].".".$apli.".@".$attach['att_name']);
      }
   }
}
function update_visibilite($visible_att,$visible_list) {
   global $upload_table;
   if (is_array ($visible_att) ) {
      $visible = implode ($visible_att, ',');
      $sql = "UPDATE $upload_table SET visible='1' WHERE att_id in ($visible)";
      sql_query($sql);

      $visible_lst = explode(',',substr($visible_list,0,strlen($visible_list)-1));
      $result=array_diff($visible_lst,$visible_att);
      $unvisible=implode($result, ",");
      $sql = "UPDATE $upload_table SET visible='0' WHERE att_id in ($unvisible)";
      sql_query($sql);
   } else {
      $visible_lst = explode(',',substr($visible_list,0,strlen($visible_list)-1));
      $unvisible=implode($visible_lst, ",");
      $sql = "UPDATE $upload_table SET visible='0' WHERE att_id in ($unvisible)";
      sql_query($sql);
   }
   renomme_fichier($visible,$unvisible);
}
?>