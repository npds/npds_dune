
<script type="text/javascript" src="lib/js/jquery.min.js"></script>
<script type="text/javascript" src="lib/js/tether.min.js"></script>
<script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
<script type="text/javascript" src="lib/js/npds_adapt.js"></script>

<script type="text/javascript">
//<![CDATA[
function htmlDecode(value) {
  return $("<textarea/>").html(value).text();
}
function htmlEncode(value) {
  return $('<textarea/>').text(value).html();
}
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
      if (window.confirm(htmlDecode('"<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>"')) ) {
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
    alert(htmlDecode("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>"));
    return false;
  }
}
function uniqueSubmit(f) {
  if (has_submitted == 0) {
    has_submitted = 1;
    setTimeout('has_submitted=0', 5000);
    f.submit();
  } else {
    alert(htmlDecode("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>"));
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
    alert(htmlDecode("<?php echo upload_translate("Vous devez tout d'abord choisir la Pièce jointe à supprimer")?>"));
    return false;
  } else {
    if (window.confirm(htmlDecode("<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>")) ) {
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
    alert (htmlDecode("<?php echo upload_translate('Vous devez sélectionner un fichier')?>"));
   f.pcfile.focus();
  }
}
function confirmSendFile(f) {
  if (window.confirm("<?php echo upload_translate('Joindre le fichier maintenant ?')?>") ) {
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
/* NPDS Copyright (c) 2002-2017 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
   global $ModPath,$ModStart,$IdPost,$IdForum,$apli,$Mmod;
   settype($att_table,'string');
   settype($thanks_msg,'string');
   echo '
   <form method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" name="form0" onsubmit="return checkForm(this);">
      <input type="hidden" name="actiontype" value="" />
      <input type="hidden" name="ModPath" value="'.$ModPath.'" />
      <input type="hidden" name="ModStart" value="'.$ModStart.'" />
      <input type="hidden" name="IdPost" value="'.$IdPost.'" />
      <input type="hidden" name="IdForum" value="'.$IdForum.'" />
      <input type="hidden" name="IdTopic" value="'.$IdTopic.'" />
      <input type="hidden" name="apli" value="'.$apli.'" />';

   $tsz=0;
   $att=getAttachments ($apli,$IdPost,0 ,$Mmod);
   $visible_list='';
   $vizut='';

   if (is_array($att)) {
      $att_count=count($att);
      $display_att=true;
      if ($Mmod) {
         $vizut='<th data-halign="center" data-align="center">'.upload_translate("Visibilité").'</th>';
      }
      $att_table='
      <table data-toggle="table" data-classes="table table-sm table-no-bordered table-hover table-striped" data-mobile-responsive="true">
         <thead>
            <tr>
               <th><i class="fa fa-trash-o fa-lg text-muted"></i></th>
               <th data-halign="center" data-align="center" data-sortable="true">'.upload_translate("Fichier").'</th>
               <th data-halign="center" data-align="center" data-sortable="true">'.upload_translate("Type").'</th>
               <th data-halign="center" data-align="right">'.upload_translate("Taille").'</th>
               <th data-halign="center" data-align="center">'.upload_translate("Affichage intégré").'</th>
            '.$vizut.'
            </tr>
         </thead>
         <tbody>';
      $Fichier = new FileManagement; // essai class PHP7
      $visu='';
      for ($i=0; $i<$att_count; $i++) {
         $id=$att[$i]['att_id'];
         $tsz+=$att[$i]['att_size'];

         $sz = $Fichier->file_size_format($att[$i]['att_size'],2);
         if (getAttDisplayMode ($att[$i]['att_type'], 'A') == ATT_DSP_LINK) {
            // This mime-type can't be displayed inline
            echo '<input type="hidden" name="inline_att['.$id.']" value="0" />';
            $inline_box='--';
         } else {
            $inline_box=getListBox("inline_att[$id]", $inline_list, $att[$i]["inline"]);
         }
         if ($Mmod) {
            $visu='<td align="center">'.getCheckBox ("visible_att[]", $id, ($att[$i]["visible"]==1)?$id:-1, "").'</td>';
            $visible_list.=$id.',';
         }
         $att_table.='
         <tr>
            <td>'.getCheckBox("del_att[]", $id, 0, '').'</td>
            <td>'.$att[$i]['att_name'].'</td>
            <td>'.$att[$i]['att_type'].'</td>
            <td>'.$sz.'</td>
            <td>'.$inline_box.'</td>
            '.$visu.'
         </tr>';
      }
//      $total_sz = $Fichier->Pretty_Size($tsz);
      $total_sz = $Fichier->file_size_format($tsz,1);
      $visu_button='';
      echo '<input type="hidden" name="visible_list" value="'.$visible_list.'">';
      $att_inline_button='<button class="btn btn-outline-primary btn-sm btn-block" onclick="InlineType(this.form);">'.upload_translate("Adapter").'<span class="hidden-sm-up"> '.upload_translate("Affichage intégré").'</span></button>';
      if ($Mmod) {
         $visu_button='<button class="btn btn-outline-primary btn-sm btn-block" onclick="visibleFile(this.form);">'.upload_translate("Adapter").'<span class="hidden-sm-up"> '.upload_translate("Visibilité").'</span></button>';
      }
      if ($ibid=theme_image("upload/arrow.gif")) {$imgtmp=$ibid;} else {$imgtmp="images/upload/arrow.gif";}
      $att_table.='
         </tbody>
      </table>
      <div class="row p-2">
         <div class="col-sm-4 col-6 mb-2"><i class="fa fa-level-up fa-2x fa-flip-horizontal text-danger"></i><a class="text-danger" href="#" onclick="deleteFile(document.form0); return false;"><span class="hidden-sm-up" title="'.upload_translate("Supprimer les fichiers sélectionnés").'" data-toggle="tooltip" data-placement="right" ><i class="fa fa-trash-o fa-2x ml-1"></i></span><span class="hidden-xs-down">'.upload_translate("Supprimer les fichiers sélectionnés").'</span></a></div>
         <div class="col-sm-4 text-right col-6 mb-2"><strong>'.upload_translate("Total :").' '.$total_sz.'</strong></div>
         <div class="col-sm-2 text-center-sm mb-2 col-12 ">'.$att_inline_button.'</div>
         <div class="col-sm-2 text-center-sm mb-2 col-12">'.$visu_button.'</div>
      </div>';
   }

   $att_upload_table='
   <div class="card card-block my-2">
      <p>'.upload_translate("Extensions autorisées").' : <small class="text-success">'.$bn_allowed_extensions.'</small></p>
      <div class="form-group row">
         <label class="form-control-label col-sm-3">'.upload_translate("Fichier joint").'</label>
         <div class="col-sm-9">
            <input type="file" class="form-control" name="pcfile" size="30" onchange="confirmSendFile(this.form);" />
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-9 offset-sm-3">
            <button type="button" class="btn btn-primary" onclick="uploadFile(this.form);">'.upload_translate("Joindre").'</button>
         </div>
      </div>
   </div>';

   $att_form='
         <div class="container-fluid p-3">
         '.$thanks_msg;
   $att_form.=$att_upload_table.$att_table;
   echo $att_form.'
         </div>
         </form>
      </body>
   </html>';
   ob_end_flush();
?>