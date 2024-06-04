
<script type="text/javascript" src="lib/js/jquery.min.js"></script>
<script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script type="text/javascript" src="lib/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
<script type="text/javascript" src="lib/js/bootbox.min.js"></script>
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
            if ( (f.elements[i].name == 'del_att[]')&&(f.elements[i].checked) ) {
               sel = true;
               break;
            }
         }
         if (sel) {
            if (window.confirm(htmlDecode('"<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>"')) ) {
               has_submitted = 1;
               setTimeout('has_submitted=0', 5000);
               return true;
               } else
               return false;
            } else {
               has_submitted = 1;
               setTimeout('has_submitted=0', 5000);
               return true;
            }
         } else {
         bootbox.alert(htmlDecode("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>"));
         return false;
      }
   }

   function uniqueSubmit(f) {
      if (has_submitted == 0) {
         has_submitted = 1;
         setTimeout('has_submitted=0', 5000);
         f.submit();
      } else {
         bootbox.alert(htmlDecode("<?php echo upload_translate("Cette page a déjà été envoyée, veuillez patienter")?>"));
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
         bootbox.alert(htmlDecode("<?php echo upload_translate("Vous devez tout d'abord choisir la Pièce jointe à supprimer")?>"));
         return false;
      } else {
         bootbox.confirm(htmlDecode("<?php echo upload_translate("Supprimer les fichiers sélectionnés ?")?>"), function(result){ 
            if (result=== true) {
               f.actiontype.value='delete';
               uniqueSubmit(f);
               return true;
            } else 
               return false;
         });
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
         bootbox.alert (htmlDecode("<?php echo upload_translate('Vous devez sélectionner un fichier')?>"));
         f.pcfile.focus();
      }
   }

   function confirmSendFile(f) {
      bootbox.confirm("<?php echo upload_translate('Joindre le fichier maintenant ?')?>",
         function(result) {
         if (result === true) {
            uploadFile(f);
            return true;
         }
      }); 
   }

   //]]>
</script>

<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* Copyright Snipe 2003  base sources du forum w-agora de Marc Druilhe  */
/************************************************************************/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
   global $ModPath,$ModStart,$IdPost,$IdForum,$apli,$Mmod;
   settype($att_table,'string');
   settype($thanks_msg,'string');
   echo '
         <form method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" name="form0" onsubmit="return checkForm(this);" lang="'.language_iso(1,'','').'">
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
      if ($Mmod)
         $vizut='<th data-width="15" data-width-unit="%" data-halign="right" data-align="right">'.upload_translate("Visibilité").'</th>';
      $att_table='
            <table data-toggle="table" data-classes="table table-sm table-no-bordered table-hover table-striped" data-mobile-responsive="true">
               <thead>
                  <tr>
                     <th data-width="5" data-width-unit="%"><i class="fas fa-trash fa-lg text-danger"></i></th>
                     <th data-width="40" data-width-unit="%" data-halign="center" data-align="center" data-sortable="true">'.upload_translate("Fichier").'</th>
                     <th data-width="15" data-width-unit="%" data-halign="center" data-align="center" data-sortable="true">'.upload_translate("Type").'</th>
                     <th data-width="10" data-width-unit="%" data-halign="center" data-align="right">'.upload_translate("Taille").'</th>
                     <th data-width="15" data-width-unit="%" data-halign="center" data-align="center">'.upload_translate("Affichage intégré").'</th>
                  '.$vizut.'
                  </tr>
               </thead>
               <tbody>';
      $Fichier = new FileManagement;
      $visu='';
      for ($i=0; $i<$att_count; $i++) {
         $id=$att[$i]['att_id'];
         $tsz+=$att[$i]['att_size'];

         $sz = $Fichier->file_size_format($att[$i]['att_size'],2);
         if (getAttDisplayMode ($att[$i]['att_type'], 'A') == ATT_DSP_LINK) {
            // This mime-type can't be displayed inline
            echo '<input type="hidden" name="inline_att['.$id.']" value="0" />';
            $inline_box='--';
         } else
            $inline_box=getListBox("inline_att[$id]", $inline_list, $att[$i]["inline"]);
         if ($Mmod) {
            $visu='<td>'.getCheckBox ("visible_att[]", $id, ($att[$i]["visible"]==1)?$id:-1, '', '').'</td>';
            $visible_list.=$id.',';
         }
         $att_table.='
                  <tr>
                     <td>'.getCheckBox("del_att[]", $id, 0, '', ' is-invalid').'</td>
                     <td>'.$att[$i]['att_name'].'</td>
                     <td>'.$att[$i]['att_type'].'</td>
                     <td>'.$sz.'</td>
                     <td>'.$inline_box.'</td>
                     '.$visu.'
                  </tr>';
      }
      $total_sz = $Fichier->file_size_format($tsz,1);
      $visu_button='';
      echo '<input type="hidden" name="visible_list" value="'.$visible_list.'" />';
      $att_inline_button='<button class="btn btn-primary btn-sm" onclick="InlineType(this.form);">'.upload_translate("Adapter").'<span class="d-sm-none d-xl-inline"> '.upload_translate("Affichage intégré").'</span></button>';
      if ($Mmod)
         $visu_button='<button class="btn btn-primary btn-sm" onclick="visibleFile(this.form);">'.upload_translate("Adapter").'<span class="d-sm-none d-xl-inline"> '.upload_translate("Visibilité").'</span></button>';

      $att_table.='
                  <tr class="mt-2">
                     <td colspan="2"><i class="fas fa-level-up-alt fa-2x fa-flip-horizontal text-danger me-1"></i><a class="text-danger" href="#" onclick="deleteFile(document.form0); return false;"><span class="d-sm-none" title="'.upload_translate("Supprimer les fichiers sélectionnés").'" data-bs-toggle="tooltip" data-bs-placement="right" ><i class="fas fa-trash fa-2x ms-1"></i></span><span class="d-none d-sm-inline">'.upload_translate("Supprimer les fichiers sélectionnés").'</span></a></td>
                     <td></td>
                     <td  class="text-end"><strong> '.$total_sz.'</strong></td>
                     <td class="text-end">'.$att_inline_button.'</td>
                     <td>'.$visu_button.'</td>
                  </tr>
               </tbody>
            </table>';
   }
   $tf = new FileManagement;
   $oo= $tf->file_size_format($MAX_FILE_SIZE,1);
   $att_upload_table='
   <div class="card card-body my-2">
      <div class="mb-2 row">
         <label class="col-form-label col-sm-3" for="pcfile">'.upload_translate("Fichier joint").'</label>
         <div class="col-sm-9">
            <div class="input-group mb-2 me-sm-2">
               <button class="btn btn-secondary" type="button" onclick="reset2($(\'#pcfile\'),\'\');"><i class="bi bi-arrow-clockwise"></i></button>
               <label class="input-group-text n-ci" id="lab" for="pcfile"></label>
               <input type="file" class="form-control custom-file-input" name="pcfile" id="pcfile" onchange="confirmSendFile(this.form);"/>
            </div>
         </div>
      </div>
      <div class="mb-3 row">
         <div class="col-sm-9 ms-sm-auto">
            <button type="button" class="btn btn-primary" onclick="uploadFile(this.form);">'.upload_translate("Joindre").'</button>
         </div>
      </div>
      <p class="mb-0">'.upload_translate("Taille maxi du fichier").' : '.$oo.'</p>
      <p class="mb-0">'.upload_translate("Extensions autorisées").' : <small class="text-success">'.$bn_allowed_extensions.'</small></p>
   </div>';
   $att_form ='
         <div class="container-fluid p-3">
            <div class="text-end">
               <button class="btn btn-close btn-sm" onclick="self.close()"></button>
            </div>
         '.$thanks_msg;
   $att_form.=$att_upload_table.$att_table;
   echo $att_form.'
         </div>
         </form>
         <script type="text/javascript">
            //<![CDATA[
               window.reset2 = function (e,f) {
                  e.wrap("<form>").closest("form").get(0).reset();
                  e.unwrap();
                  event.preventDefault();
               };
            //]]>
         </script>
      </body>
   </html>';
   ob_end_flush();
?>