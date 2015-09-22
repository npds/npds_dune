<script type="text/javascript">
//<![CDATA[
var has_submitted = 0;

function checkForm(f) {
  if (has_submitted == 0) {
    sel=false;
    for (i = 0; i < f.elements.length; i++) {
       if ( (f.elements[i].name == 'del_att[]')&&(f.elements[i].checked) ){
        sel = true;
        break;
      }
    }
    if (sel) {
      if (window.confirm("<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>") ) {
         has_submitted = 1;
         setTimeout('has_submitted=0', 5000);
         return true;
     } else {
        return false;
     }
    } else {
       has_submitted = 1;
       setTimeout('has_submitted=0', 5000);
       return true;
    }
  } else {
    alert("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>");
    return false;
  }
}

function uniqueSubmit(f) {
  if (has_submitted == 0) {
    has_submitted = 1;
    setTimeout('has_submitted=0', 5000);
    f.submit();
  } else {
    alert("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>");
    return false;
  }
}

function deleteFile(f) {
  sel=false;
  for (i = 0; i < f.elements.length; i++) {
    if ( (f.elements[i].name == 'del_att[]')&&(f.elements[i].checked) ){
      sel = true;
      break;
    }
  }
  if (sel == false) {
    f.actiontype.value='';
    alert("<?php echo upload_translate("Vous devez tout d'abord choisir la Pièce jointe à supprimer")?>");
    return false;
  } else {
    if (window.confirm("<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>") ) {
      f.actiontype.value='delete';
      uniqueSubmit(f);
      return true;
    } else {
      return false;
    }
  }
}

function visibleFile(f) {
  f.actiontype.value='visible';
  f.submit();
}

function InlineType(f){
  f.actiontype.value='update';
  uniqueSubmit(f);
}

function uploadFile(f) {
  if(f.pcfile.value.length>0) {
    f.actiontype.value='upload';
    uniqueSubmit(f);
  } else {
    f.actiontype.value='';
    alert ("<?php echo upload_translate("Vous devez selectionner un fichier")?>");
   f.pcfile.focus();
  }
}

function confirmSendFile(f) {
  if (window.confirm("<?php echo upload_translate("Joindre le fichier maintenant ?")?>") ) {
    uploadFile(f);
  }
}
//]]>
</script>

<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2011 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
   global $ModPath,$ModStart,$IdPost,$IdForum,$apli, $Mmod;
   echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" enctype=\"multipart/form-data\"  name=\"form0\" onsubmit=\"return checkForm(this);\">";
   echo "<input type=\"hidden\" name=\"actiontype\" value=\"\" />\n<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />\n<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />\n<input type=\"hidden\" name=\"IdPost\" value=\"$IdPost\" />\n";
   echo "<input type=\"hidden\" name=\"IdForum\" value=\"$IdForum\" />\n<input type=\"hidden\" name=\"IdTopic\" value=\"$IdTopic\" />\n<input type=\"hidden\" name=\"apli\" value=\"$apli\" />";

   $tsz=0;
   $att=getAttachments ($apli,$IdPost,0 ,$Mmod);
   $visible_list="";
   $vizut="";

   if (is_array($att)) {
      $att_count=count($att);
      $display_att=true;
      if ($Mmod) {
         $vizut="<td class=\"header\">".upload_translate("Visible :")."</td>";
      }
      $att_table="<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td width=\"1%\">&nbsp;</td><td class=\"header\">".
      upload_translate("Fichier :")."</td><td class=\"header\">".upload_translate("Type :")."</td><td class=\"header\">".upload_translate("Taille :")."</td><td class=\"header\">".upload_translate("Affichage intégré :")."</td>".$vizut."</tr>";

      $Fichier = new File("");
      for ($i=0; $i<$att_count; $i++) {
         $rowcolor=tablos();
         $id=$att[$i]['att_id'];
         $tsz+=$att[$i]['att_size'];
         $sz = $Fichier->Pretty_Size($att[$i]['att_size']);
         if (getAttDisplayMode ($att[$i]['att_type'], 'A') == ATT_DSP_LINK) {
            // This mime-type can't be displayed inline
            echo "<input type=\"hidden\" name=\"inline_att[$id]\" value=\"0\" />\n";
            $inline_box='--';
         } else {
            $inline_box=getListBox("inline_att[$id]", $inline_list, $att[$i]["inline"]);
         }
         if ($Mmod) {
            $visu="<td align=\"center\">".getCheckBox ("visible_att[]", $id, ($att[$i]["visible"]==1)?$id:-1, "")."</td>";
            $visible_list.=$id.",";
         }
         $att_table.="<tr ".$rowcolor."><td>".getCheckBox('del_att[]', $id, 0, "")."</td><td>".$att[$i]['att_name']."</td><td>".$att[$i]['att_type']."</td><td align=\"center\">".$sz."</td><td align=\"center\">".$inline_box."</td>$visu</tr>\n";
      }
      $total_sz = $Fichier->Pretty_Size($tsz);
      echo "<input type=\"hidden\" name=\"visible_list\" value=\"$visible_list\">\n";
      $att_inline_button="<input type=\"button\" class=\"bouton_standard\" value=\"".upload_translate("Adapter")."\" onclick=\"InlineType(this.form);\" />";
      if ($Mmod) {
         $visu_button="<input type=\"button\" class=\"bouton_standard\" value=\"".upload_translate("Adapter")."\" onclick=\"visibleFile(this.form);\" />";
      }
      if ($ibid=theme_image("upload/arrow.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/upload/arrow.gif";}
      $att_table.= "<tr><td colspan=\"2\" align=\"left\"><img src=\"".$imgtmp."\" border=\"0\" alt=\"\" align=\"center\" />".
                   "<a href=\"#\" onclick=\"deleteFile(document.form0); return false;\">".upload_translate("Supprimer les fichier sélectionnés")."</a></td><td align=\"right\"><b>".upload_translate("Total :")."</b></td><td align=\"center\"><b>".$total_sz."</b></td><td align=\"center\">&nbsp;".$att_inline_button."</td><td align=\"center\">".$visu_button."</td></tr>";
   }
   $file_upload_button="<script type=\"text/javascript\">\n//<![CDATA[\n";
   $file_upload_button.=" document.write ('<input type=\"button\" class=\"bouton_standard\" value=\"".upload_translate("Joindre")."\" onclick=\"uploadFile(this.form);\" />');\n";
   $file_upload_button.=" //]]>\n</script>";
   $att_upload_table="<tr><td colspan=\"6\" align=\"left\">".upload_translate("Fichier joint :")."&nbsp;<input type=\"file\" class=\"textbox_standard\" name=\"pcfile\" width=\"260\" size=\"30\" onchange=\"confirmSendFile(this.form);\" />&nbsp; $file_upload_button</td></tr></table>";

   $att_form="<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td colspan=\"2\" align=\"center\"><hr noshade=\"noshade\" /></td></tr><tr><td align=\"left\" colspan=\"2\">".upload_translate("Pièces jointes :")." (<font size=\"1\">".$bn_allowed_extensions."</font>)</td></tr><tr><td colspan=\"2\" align=\"center\">";
   $att_form.=$att_table.$att_upload_table;
   $att_form.="</td></tr><tr><td colspan=\"2\" align=\"center\"><hr noshade=\"noshade\" /></td></tr></table>";
   echo $att_form."<br />".$thanks_msg;
   echo "</body></html>";
   ob_end_flush();
?>