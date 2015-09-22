<?php
################################################################################################
// Simple Form generator  SFORM / version 1.6 for DUNE
// Class to manage several Form in a single database(MySql) in XML Format
// P.Brunier 2001 - 2013
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
//
// Based on Form_Handler 19-4-01 Copyright Drs. Jelte 'YeeHaW' Werkhoven
//
// Mod by Didier (Jireck) Hoen Xhtml + form_id
// Mod by Dev 2011 - Rajout d'un textarea de type 'textarea_no_mceEditor' pour pouvoir associer
// dans un même FORMULAIRE des champs avec ET sans TinyMce / Rajout de l'anti_spambot
################################################################################################
// Constante
   define('CRLF', "\n", TRUE);
   if (!isset($sform_path)) {
      $sform_path="";
   }
################################################################################################
class form_handler {
  var $form_fields=array();// form fields
  var $title;// form title
  var $mess; // obligatoire message
  var $form_title; // form title
  var $form_id;  // Form id (for custom css) Jireck add
  var $form_method; // form method (Post or Get)
  var $form_key; // form key (for mysql stockage)
  var $form_key_value; // value of the form key (for mysql stockage)
  var $form_key_status="open"; // Status of the key (open or close)
  var $submit_value=""; // the name off all submit buttons of the form
  var $form_password_access=""; // Protect the data with a password
  var $answer=""; // answer table
  var $form_check="true"; // sring which will be inserted into javascript check function
  var $url; // path at 'action' option of form
  var $field_size=50; // Value of the size attribute of a form-field
  /**************************************************************************************/
  // Interrogate the object for identify the position of an item
  // public void
  function interro_fields($ibid) {
    $number="no";
    for(Reset($this->form_fields),$node=0;$node<count($this->form_fields);Next($this->form_fields),$node++) {

       // modif - rajout de key_exist

       if (array_key_exists('name',$this->form_fields[$node])) {
          if ($ibid==$this->form_fields[$node]['name']) {
             $number=$node;
             break;
          }
       }
    }
    return ($number);
  }

  function interro_array($ibid0,$ibid1) {
    $number="no";
    while(list($key,$val)=each($ibid0) ){
       if ($ibid1==$val['en']) {
          $number=$key;
          break;
       }
    }
    return ($number);
  }

  /**************************************************************************************/
  // Change the default (50) value for the html attribute SIZE of form-field
  // public void
  function add_form_field_size($en) {
    $this->field_size=$en;
  }

  /**************************************************************************************/
  // add title of <form> / This is also the id_form field in the database (unique)
  // public void
  function add_form_title($en) {
    $this->form_title=$en;
  }

  /**************************************************************************************/
  // add id of <form> // Jireck add
  // public void
  function add_form_id($en) {

    $this->form_id=$en;
  }
  /**************************************************************************************/
  // add method of <form action=> Get or Post
  // public void
  function add_form_method($en) {
    $this->form_method=$en;
  }

  /**************************************************************************************/
  // add form check after submit for obligatory fields
  // public void
  function add_form_check($en) {
    $this->form_check=$en;
  }

  /**************************************************************************************/
  // add the return url after action
  // public void
  function add_url($en) {
    $this->url=$en;
  }

  /**************************************************************************************/
  // designate a specfific field off the form as key in the DB
  // public void
  function add_key($en) {
    $this->form_key=$en;
  }

  /**************************************************************************************/
  // add the name for all submit buttons of <form>
  // public void
  function add_submit_value($en) {
    $this->submit_value=$en;
  }

  /**************************************************************************************/
  // Lock the Key of <form> for disable edit
  // public void
  function key_lock($en) {
     if ($en=="open") {
        $this->form_key_status="open";
     } else {
        $this->form_key_status="close";
     }
  }

  /**************************************************************************************/
  // add mess
  // public void
  function add_mess($en) {
    $this->mess=$en;
  }

  /**************************************************************************************/
  // add fields text,hidden,textarea,password,submit,reset
  // public void
  function add_field($name,$en, $value='', $type='text', $obligation=false, $size='50', $diviseur='5', $ctrl='') {
    if ($type=="submit") {$name=$this->submit_value;}
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'type'=>$type,
      'en'=>$en,
      'value'=>$value,
      'size'=>$size,
      'diviseur'=>$diviseur,
      'obligation'=>$obligation,
      'ctrl'=>$ctrl
    );
  }

  /**************************************************************************************/
  // add field checkbox
  // public void
  function add_checkbox($name, $en, $value='', $obligation=false, $checked=false) {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'en'=>$en,
      'value'=>$value,
      'type'=>"checkbox",
      'checked'=>$checked,
      'obligation'=>$obligation
    );
  }

  /**************************************************************************************/
  // add field select
  // public void
  function add_select($name, $en, $values, $obligation=false, $size=1, $multiple=false) {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'en'=>$en,
      'type'=>"select",
      'value'=>$values,
      'size'=>$size,
      'multiple'=>$multiple,
      'obligation'=>$obligation
    );
  }

  /**************************************************************************************/
  // add field radio
  // public void
  function add_radio($name, $en, $values, $obligation=false) {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'en'=>$en,
      'type'=>"radio",
      'value'=>$values,
      'obligation'=>$obligation
    );
  }

  /**************************************************************************************/
  // add fields date or stamp : date field of type date, stamp hidden field value timestamp
  // public void
  function add_date($name, $en, $value, $type='date', $modele='m/d/Y', $obligation=false, $size='10') {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'type'=>$type,
      'model'=>$modele,
      'en'=>$en,
      'value'=>$value,
      'size'=>$size,
      'obligation'=>$obligation,
      'ctrl'=>'date'
    );
  }

  /**************************************************************************************/
  // add title of the HTML tab
  // public void
  function add_title($en) {
    $this->title=$en;
  }

  /**************************************************************************************/
  // add comment into HTML tab
  // public void
  function add_comment($en) {
    $this->form_fields[count($this->form_fields)]=array(
      'en'=>$en,
      'type'=>"comment"
    );
  }

  /**************************************************************************************/
  // add extra into HTML tab (link html tags ...)
  // public void
  function add_extra($en) {
    $this->form_fields[count($this->form_fields)]=array(
      'en'=>$en,
      'type'=>"extra"
    );
  }
  /**************************************************************************************/
  // add extra into HTML tab (link html tags ...) print in form but not in response
  // public void
  function add_extra_hidden($en) {
    $this->form_fields[count($this->form_fields)]=array(
      'en'=>$en,
      'type'=>"extra-hidden"
    );
  }

  /**************************************************************************************/
  // add Q_spambot mainfile fonction
  // public void
  function add_Qspam() {
    $this->form_fields[count($this->form_fields)]=array(
      'en'=>"",
      'type'=>"Qspam"
    );
  }

  /**************************************************************************************/
  // add field EXTENDER javas only for select field, html for all fields except radio
  // public void
  function add_extender($name, $javas, $html) {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name."extender",
      'javas'=>$javas,
      'html'=>$html
    );
  }

  /**************************************************************************************/
  // add upload field (only for design, no upload mechanism is inside sform)
  // public void
  function add_upload($name, $en, $size='50', $file_size) {
    $this->form_fields[count($this->form_fields)]=array(
      'name'=>$name,
      'en'=>$en,
      'value'=>"",
      'type'=>"upload",
      'size'=>$size,
      'file_size'=>$file_size
    );
  }

  /**************************************************************************************/
  // print <form> into html output / IF no method (form_method) is affected : the <form> … </form> is not write (useful to insert SFORM in existing form)
  // public string
  function print_form($bg) {
    if (isset($this->form_id)){
        $id_form = "id=\"".$this->form_id."\"";
    } else {
        $id_form = "";
    }
    $str="";
    if ($this->form_method!="") {
       $str.="\n<form action=\"".$this->url."\" ".$id_form."  method=\"".$this->form_method."\" name=\"".$this->form_title."\" enctype=\"multipart/form-data\"";
       if ($this->form_check=="true") {
           $str.=" onsubmit='return check();'>\n";
       } else {
           $str.=">\n";
       }
    }
    // todo utilisation de tabindex dans les input
    $str.="<fieldset id=\"".$this->form_title."\">
           <legend>".$this->title."</legend>\n";

    for($i=0;$i<count($this->form_fields);$i++){
      if (array_key_exists('size',$this->form_fields[$i])) {
         if ($this->form_fields[$i]['size']>=$this->field_size) {$csize=$this->field_size;} else {$csize=$this->form_fields[$i]['size']+1;}
      }
      if (array_key_exists('name',$this->form_fields[$i])) {
         $num_extender=$this->interro_fields($this->form_fields[$i]['name']."extender");
      } else {
         $num_extender="no";
      }

      if (array_key_exists('type',$this->form_fields[$i])) {
        switch($this->form_fields[$i]['type']){
        case 'text':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en'];
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);

          if ($this->form_fields[$i]['obligation']){
             $this->form_check.=" && (f.elements['".$this->form_fields[$i]['name']."'].value!='')";
             $str.="&nbsp;<span class=\"rouge\">*</span></label>\n";
          } else $str.="&nbsp;</label>\n";

          // Charge la valeur et analyse la clef
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$this->form_fields[$i]['value'];
             if ($this->form_key_status=="close") {
                $str.="<input readonly type=\"".$this->form_fields[$i]['type']."\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\"";
             } else {
                $str.="<input type=\"".$this->form_fields[$i]['type']."\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\"";
             }
          } else {
             $str.="<input type=\"".$this->form_fields[$i]['type']."\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\"";
          }
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['javas'].">";
             $str.=$this->form_fields[$num_extender]['html'];
          } else {
             $str.=" /> ";
          }
          $str.="<br />\n";
          break;

        case 'password-access':
          $this->form_fields[$i]['value']=$this->form_password_access;
        case 'password':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en'];
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);

          if ($this->form_fields[$i]['obligation']){
             $this->form_check.=" && (f.elements['".$this->form_fields[$i]['name']."'].value!='')";
             $str.="&nbsp;<span class=\"rouge\">*</span></label>\n";
          } else $str.="</label>\n";
          $str.="<input type=\"".$this->form_fields[$i]['type']."\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\" />";
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'checkbox':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en'];
          $str.=($this->form_fields[$i]['obligation'])? "&nbsp;<span class=\"rouge\">*</span></label>\n" : "</label>\n";
          $str.="<input type=\"checkbox\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\"";
          $str.=" value=\"".$this->form_fields[$i]['value']."\"";
          $str.=($this->form_fields[$i]['checked'])? " checked=\"checked\" />" : " />";
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'textarea':
        case 'textarea_no_mceEditor':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en']."\n";
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);

          if ($this->form_fields[$i]['obligation']){
             $this->form_check.=" && (f.elements['".$this->form_fields[$i]['name']."'].value!='')";
             $str.="&nbsp;<span class=\"rouge\">*</span>";
          }
          $str.="</label>";
          $txt_row=$this->form_fields[$i]['diviseur'];
          $txt_col=( ($this->form_fields[$i]['size'] - ($this->form_fields[$i]['size'] % $txt_row)) / $txt_row);
          $str.="<textarea name=\"".$this->form_fields[$i]['name']."\"";
          if ($this->form_fields[$i]['type']=="textarea_no_mceEditor") $str.="class=\"textbox_no_mceEditor\"";
          $str.="cols=\"$txt_col\" rows=\"$txt_row\" style=\"width: 60%;\">".$this->form_fields[$i]['value']."</textarea>";
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'show-hidden':
        $str.=$this->form_fields[$i]['en']."<br />";
          if ($num_extender!="no") {
             $str.="".$this->form_fields[$num_extender]['html'];
          }
        $str.="\n";

        case 'hidden':
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);
          $str.="<input type=\"hidden\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" />\n";
          break;

        case 'select':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en'];
          $str.="</label>";
          $str.="<select id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name'];
          $str.=($this->form_fields[$i]['multiple'])? "[]\" multiple" : "\"";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['javas']." ";
          }
          $str.=($this->form_fields[$i]['size'] > 1)? " size=\"".$this->form_fields[$i]['size']."\">" : ">";
          while(list($key,$val)=each($this->form_fields[$i]['value']) ){
            $str.="<option value=\"".$key."\"";
            $str.=($val['selected'])? " selected=\"selected\">" : ">";
            $str.=str_replace('\'','&#039;',$val['en'])."</option>";
          }
          $str.="</select>";
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'radio':
          $first_radio=true;
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en']."</label>";
          while(list($key,$val)=each($this->form_fields[$i]['value']) ){
            $str.="<input type=\"radio\" ";
            if ($first_radio) {
               $str.="id=\"".$this->form_fields[$i]['name']."\" ";
               $first_radio=false;
            }
            $str.="name=\"".$this->form_fields[$i]['name']."\" value=\"".$key."\"";
            $str.=($val['checked'])? " checked=\"checked\" />" : " />&nbsp;";
            $str.=$val['en']."&nbsp;&nbsp;";
          }
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'comment':
          $str.="<p>";
          $str.=$this->form_fields[$i]['en'];
          $str.="</p>\n";
          break;

        case 'Qspam':
          $str.="<p>";
          $str.=Q_spambot();
          $str.="</p>\n";
          break;

        case 'extra':
        case 'extra-hidden':
          $str.=$this->form_fields[$i]['en'];
          break;

        case 'submit':
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);
          $str.="<input id=\"".$this->form_fields[$i]['name']."\" type=\"submit\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" />\n";
          break;

        case 'reset':
          $this->form_fields[$i]['value']=str_replace('\'','&#039;',$this->form_fields[$i]['value']);
          $str.=$this->form_fields[$i]['en'];
          $str.="<input id=\"".$this->form_fields[$i]['name']."\" type=\"reset\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" />\n";
          break;

        case 'stamp':
          if ($this->form_fields[$i]['value']=="") $this->form_fields[$i]['value']=strtotime("now");
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$this->form_fields[$i]['value'];
          }
          $str.="<input type=\"hidden\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" />";
          break;

        case 'date':
          if ($this->form_fields[$i]['value']=="") $this->form_fields[$i]['value']=date($this->form_fields[$i]['model']);
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en'];
          if ($this->form_fields[$i]['obligation']){
             $this->form_check.=" && (f.elements['".$this->form_fields[$i]['name']."'].value!='')";
             $str.="&nbsp;<span class=\"rouge\">*</span></label>";
          } else $str.="</label>";
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$this->form_fields[$i]['value'];
             if ($this->form_key_status=="close") {
                $str.="<input type=\"hidden\" id=\"".$this->form_fields[$i]['name']."\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" />";
                $str.="<b>".$this->form_fields[$i]['value']."</b>";
             } else {
                $str.="<input id=\"".$this->form_fields[$i]['name']."\" type=\"text\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\" />";
             }
          } else {
             $str.="<input id=\"".$this->form_fields[$i]['name']."\" type=\"text\" name=\"".$this->form_fields[$i]['name']."\" value=\"".$this->form_fields[$i]['value']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\" />";
          }
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        case 'upload':
          $str.="<label for=\"".$this->form_fields[$i]['name']."\">".$this->form_fields[$i]['en']."</label>\n";
          $str.="<input id=\"".$this->form_fields[$i]['name']."\" type=\"file\" name=\"".$this->form_fields[$i]['name']."\" size=\"".$csize."\" maxlength=\"".$this->form_fields[$i]['size']."\" />";
          $str.="<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$this->form_fields[$i]['file_size']."\" />";
          if ($num_extender!="no") {
             $str.=$this->form_fields[$num_extender]['html'];
          }
          $str.="<br />\n";
          break;

        default:
          break;
        }
      }
    }
    $str.="</fieldset>\n";

    if ($this->form_method!="") {
       $str.="</form>\n";
    }
    if ($this->form_check!="false"){
       $str.="<script type=\"text/javascript\">//<![CDATA[".CRLF;
       $str.="var f=document.forms['".$this->form_title."'];".CRLF;
       $str.="function check(){".CRLF;
       $str.=" if(".$this->form_check."){".CRLF;
       $str.="   f.submit();".CRLF;
       $str.="   return true;".CRLF;
       $str.=" } else {".CRLF;
       $str.="   alert('".$this->mess."');".CRLF;
       $str.="   return false;".CRLF;
       $str.="}}".CRLF;
       $str.="//]]></script>\n";
    }
    return $str;
  }

  /**************************************************************************************/
  // return ALL FIELDS as HIDDEN
  // public string
  function print_form_hidden() {
    $str="";
    for ($i=0;$i<count($this->form_fields);$i++) {
       if (array_key_exists('name',$this->form_fields[$i])) {
          $str.="<input type=\"hidden\" name=\"".$this->form_fields[$i]['name']."\" value=\"".stripslashes(str_replace('\'','&#039;',$this->form_fields[$i]['value']))."\" />";
       }
    }
    return $str;
  }

  /**************************************************************************************/
  // make the anwer array
  // private string
  function make_response(){

    for($i=0;$i<count($this->form_fields);$i++) {
      $this->answer[$i]="";
      switch($this->form_fields[$i]['type']){
        case 'text':
          // Charge la valeur de la clef
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$GLOBALS[$this->form_fields[$i]['name']];
          }
        case 'password':
          if ($this->form_fields[$i]['ctrl']!="") {
             $this->control($this->form_fields[$i]['name'],$this->form_fields[$i]['en'],$GLOBALS[$this->form_fields[$i]['name']],$this->form_fields[$i]['ctrl']);
          }
          $this->answer[$i].="<TEXT>\n";
          $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$GLOBALS[$this->form_fields[$i]['name']]."</".$this->form_fields[$i]['name'].">\n";
          $this->answer[$i].="</TEXT>";
          break;

        case 'password-access':
          if ($this->form_fields[$i]['ctrl']!="") {
             $this->control($this->form_fields[$i]['name'],$this->form_fields[$i]['en'],$GLOBALS[$this->form_fields[$i]['name']],$this->form_fields[$i]['ctrl']);
          }
          $this->form_password_access=$GLOBALS[$this->form_fields[$i]['name']];
          break;

        case 'textarea':
        case 'textarea_no_mceEditor':
          $this->answer[$i].="<TEXT>\n";
          $this->answer[$i].="<".$this->form_fields[$i]['name'].">".str_replace(chr(13).chr(10),"&lt;br /&gt;",$GLOBALS[$this->form_fields[$i]['name']])."</".$this->form_fields[$i]['name'].">\n";
          $this->answer[$i].="</TEXT>";
          break;

        case 'select':
          $this->answer[$i].="<SELECT>\n";
          if( is_array($GLOBALS[$this->form_fields[$i]['name']]) ){
            for($j=0;$j<count($GLOBALS[$this->form_fields[$i]['name']]);$j++){
              $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$this->form_fields[$i]['value'][ $GLOBALS[$this->form_fields[$i]['name']][$j] ]['en']."</".$this->form_fields[$i]['name'].">\n";
            }
          }else{
            $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$this->form_fields[$i]['value'][ $GLOBALS[$this->form_fields[$i]['name']] ]['en']."</".$this->form_fields[$i]['name'].">";
          }
          $this->answer[$i].="</SELECT>";
          break;

        case 'radio':
          $this->answer[$i].="<RADIO>\n";
          $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$this->form_fields[$i]['value'][ $GLOBALS[$this->form_fields[$i]['name']] ]['en']."</".$this->form_fields[$i]['name'].">\n";
          $this->answer[$i].="</RADIO>";
          break;

        case 'checkbox':
          $this->answer[$i].="<CHECK>\n";
          if($GLOBALS[$this->form_fields[$i]['name']]!=""){
            $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$this->form_fields[$i]['value']."</".$this->form_fields[$i]['name'].">\n";
          } else {
            $this->answer[$i].="<".$this->form_fields[$i]['name']."></".$this->form_fields[$i]['name'].">\n";
          }
          $this->answer[$i].="</CHECK>";
          break;

        case 'date':
          if ($this->form_fields[$i]['ctrl']!="") {
             $this->control($this->form_fields[$i]['name'],$this->form_fields[$i]['en'],$GLOBALS[$this->form_fields[$i]['name']],$this->form_fields[$i]['ctrl']);
          }
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$GLOBALS[$this->form_fields[$i]['name']];
          }
          $this->answer[$i].="<DATUM>\n";
          $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$GLOBALS[$this->form_fields[$i]['name']]."</".$this->form_fields[$i]['name'].">\n";
          $this->answer[$i].="</DATUM>";
          break;

        case 'stamp':
          if ($this->form_fields[$i]['name']==$this->form_key) {
             $this->form_key_value=$GLOBALS[$this->form_fields[$i]['name']];
          }
          $this->answer[$i].="<TIMESTAMP>\n";
          $this->answer[$i].="<".$this->form_fields[$i]['name'].">".$GLOBALS[$this->form_fields[$i]['name']]."</".$this->form_fields[$i]['name'].">\n";
          $this->answer[$i].="</TIMESTAMP>";
          break;

        case 'hidden':
        case 'submit':
        case 'reset':
        default:
          $this->answer[$i].="no_reg";
          break;
      }
    }
  }

  /**************************************************************************************/
  // Read Data structure and build a plain-text response
  function write_sform_data($response) {
   $content = "<CONTENTS>\n";
   for(Reset($response),$node=0;$node<count($response);Next($response),$node++) {
      if ($response[$node]!="no_reg") {
        $content.=$response[$node]."\n";
      }
   }
   $content .= "</CONTENTS>";
   return (addslashes($content));
  }

  /**************************************************************************************/
  // Read Data structure and build the Internal Data Structure
  function read_load_sform_data($line,$op) {
   if ((!stristr($line,"<CONTENTS>")) and (!stristr($line,"</CONTENTS>"))) {
      // Premier tag
      $nom=substr($line,1,strpos($line,">")-1);
      // jusqu'a </xxx
      $valeur=substr($line,strpos($line,">")+1,(strpos($line,"<",1)-strlen($nom)-2));
      if ($valeur=="") {$op=$nom;}
      switch ($op) {
         case "TEXT":
            $op="TEXT_S";
            break;
         case "TEXT_S":
            $num=$this->interro_fields($nom);
            if ($num!="no" or $num=="0") {
               $valeur=str_replace("&lt;BR /&gt;", chr(13).chr(10), $valeur);
               $valeur=str_replace("&lt;br /&gt;", chr(13).chr(10), $valeur);
               $this->form_fields[$num]['value']=$valeur;
            }
            break;
         case "/TEXT":
            break;

         case "SELECT":
            $op="SELECT_S";
            break;
         case "SELECT_S":
            $num=$this->interro_fields($nom);
            if ($num!="no" or $num=="0") {
               $tmp=$this->interro_array($this->form_fields[$num]['value'],$valeur);
               $this->form_fields[$num]['value'][$tmp]['selected']=true;
            }
            break;
         case "/SELECT":
            break;

         case "RADIO":
            $op="RADIO_S";
            break;
         case "RADIO_S":
            $num=$this->interro_fields($nom);
            if ($num!="no" or $num=="0") {
               $tmp=$this->interro_array($this->form_fields[$num]['value'],$valeur);
               $this->form_fields[$num]['value'][$tmp]['checked']=true;
            }
            break;
         case "/RADIO":
            break;

         case "CHECK":
            $op="CHECK_S";
            break;
         case "CHECK_S":
            $num=$this->interro_fields($nom);
            if ($num!="no" or $num=="0") {
               if ($valeur) {$valeur=true;}else{$valeur=false;}
               $this->form_fields[$num]['checked']=$valeur;
            }
            break;
         case "/CHECK":
            break;

         case "TIMESTAMP":
         case "DATUM":
            $op="DATUM_S";
            break;
         case "DATUM_S":
            $num=$this->interro_fields($nom);
            if ($num!="no" or $num=="0") {
               $this->form_fields[$num]['value']=$valeur;
            }
            break;
         case "/DATUM":
            break;

         default:
            break;
      }
   }
   return ($op);
  }

  /**************************************************************************************/
  // print html response
  // $bg      => Class for TR or TD
  // $retour  => Comment for the link at the end of the page OR ="not_echo" for not 'echo' the reply but return in a string !
  // $action  => url to go
  function aff_response($bg,$retour="",$action="") {
    $str="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"ligna\"><tr><td>";
    $str.="<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" class=\"lignb\">";
    $str.="<tr class=\"ligna\"><td width=\"40%\" align=\"center\"><b>".$this->title."</b></td><td>&nbsp;</td></tr>";

    // modif Field en lieu et place des $GLOBALS ....

    for($i=0;$i<count($this->form_fields);$i++){
      if (array_key_exists('name',$this->form_fields[$i])) {
         $num_extender=$this->interro_fields($this->form_fields[$i]['name']."extender");
         if (array_key_exists($this->form_fields[$i]['name'], $GLOBALS)) {
            $field=$GLOBALS[$this->form_fields[$i]['name']];
         } else {
            $field="";
         }
      } else {
         $num_extender="no";
      }
      if (array_key_exists('type',$this->form_fields[$i])) {
         switch($this->form_fields[$i]['type']) {
         case 'text':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          $str.="<b>".stripslashes($field)."&nbsp;</b>";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'password':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          $str.="<b>".str_repeat("*", strlen($field))."&nbsp;</b>";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'checkbox':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          if ($field!="") {
            $str.="<b>".$this->form_fields[$i]['value']."&nbsp;</b>";
          }
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'textarea':
         case 'textarea_no_mceEditor':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          $str.="<b>".stripslashes(str_replace(chr(13).chr(10),"<br />",$field))."&nbsp;</b>";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'select':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          if ( is_array($field) ){
             for ($j=0;$j<count($field);$j++){
                $str.="<b>".$this->form_fields[$i]['value'][ $field[$j] ]['en']."&nbsp;</b><br />";
            }
          } else {
            $str.="<b>".$this->form_fields[$i]['value'][ $field ]['en']."&nbsp;</b>";
          }
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'radio':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          $str.="<b>".$this->form_fields[$i]['value'][ $field ]['en']."&nbsp;</b>";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         case 'comment':
          $str.="<tr $bg><td colspan=\"2\">";
          $str.=$this->form_fields[$i]['en'];
          $str.="</td></tr>";
          break;

         case 'extra':
          $str.=$this->form_fields[$i]['en'];
          break;

         case 'date':
          $str.="<tr $bg><td>".$this->form_fields[$i]['en'];
          $str.="</td><td>";
          $str.="<b>".$field."&nbsp;</b>";
          if ($num_extender!="no") {
             $str.=" ".$this->form_fields[$num_extender]['html'];
          }
          $str.="</td></tr>";
          break;

         default:
          break;
         }
      }
    }
    if (($retour!="") and ($retour!="not_echo")) {
       $str.="<tr $bg><td colspan=\"2\" align=\"center\"><a href=\"$action\" class=\"noir\">[ $retour ]</a></td></tr>";
    }
    $str.="</table>";
    $str.="</td></tr></table>";

    if ($retour!="not_echo") {
       echo $str;
    } else {
       return $str;
    }
  }

  /**************************************************************************************/
  // Control the respect of Data Type
  function control($name,$nom, $valeur, $controle) {

    $i=$this->interro_fields($name);
    if (($this->form_fields[$i]['obligation']!=true) and ($valeur=="")) {
       $controle="";
    }

     switch ($controle) {
        case 'a-9':
          if (preg_match_all("/([^a-zA-Z0-9 ])/i", $valeur,$trouve)) {
             $this->error($nom, implode(" ",$trouve[0]));
             exit();
          }
          break;
        case 'A-9':
          if (preg_match_all("([^A-Z0-9 ])", $valeur,$trouve)) {
             return(false);
             exit();
          }
          break;

        case 'email':
          $valeur = strtolower($valeur);
          if (preg_match_all("/([^a-z0-9_@.-])/i", $valeur, $trouve)) {
             $this->error($nom, implode(" ",$trouve[0]));
             exit();
          }
          if (!preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i", $valeur)) {
             $this->error($nom, "Format email invalide");
             exit();
          }
          break;

        case '0-9':
          if (preg_match_all("/([^0-9])/i", $valeur,$trouve)) {
             $this->error($nom, implode(" ",$trouve[0]));
             exit();
          }
          break;
        case '0-9extend':
          if (preg_match_all("/([^0-9_\+\-\*\/\)\]\(\[\& ])/i", $valeur,$trouve)) {
             $this->error($nom, implode(" ",$trouve[0]));
             exit();
          }
          break;
        case '0-9number':
          if (preg_match_all("/([^0-9+-., ])/i", $valeur,$trouve)) {
             $this->error($nom, implode(" ",$trouve[0]));
             exit();
          }
          break;

        case 'date':
          $date = explode("/",$valeur);
          if (count($date)==3) {
             settype($date[0], 'integer');
             settype($date[1], 'integer');
             settype($date[2], 'integer');         
             if (!checkdate($date[1],$date[0],$date[2])) {
                $this->error($nom,"Date non valide");
                exit();
             }
          } else {
             $this->error($nom,"Date non valide");
             exit();
          }          
          break;

        default:
          break;
      }
  }

  /**************************************************************************************/
  function error($ibid, $car) {
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"ligna\"><tr><td>";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" class=\"lignb\">";
    echo "<tr class=\"ligna\"><td>";
    echo aff_langue($ibid)." => <span class=\"rouge\">".stripslashes($car)."</span><br /><br />";
    if ($this->form_method=="") {$this->form_method="post";}
    echo "<form action=\"".$this->url."\" method=\"".$this->form_method."\" name=\"".$this->form_title."\" enctype=\"multipart/form-data\">";
    echo $this->print_form_hidden();
    echo "&nbsp;<input class=\"bouton_standard\" type=\"submit\" name=\"sformret\" value=\"Retour\" /></form>";
    echo "</td></tr></table>";
    echo "</td></tr></table>";
    include("footer.php");
  }

  /**************************************************************************************/
  // Mysql Interface
  // If the first char of $mess_ok is : ! => the button is hidden
  function sform_browse_mysql($pas,$mess_passwd,$mess_ok,$presentation='') {
     global $NPDS_Prefix;

     $result=sql_query("select key_value, passwd from ".$NPDS_Prefix."sform where id_form='".$this->form_title."' and id_key='".$this->form_key."' ORDER BY key_value ASC");
     echo "<form action=\"".$this->url."\" method=\"post\" name=\"browse\" enctype=\"multipart/form-data\">";
     echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"ligna\"><tr><td>";
     echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"lignb\">";
     $hidden=false;
     if (substr($mess_ok,0,1)=="!") {
        $mess_ok=substr($mess_ok,1);
        $hidden=true;
     }
     $ibid=0;
     while (list($key_value, $passwd) = sql_fetch_row($result)) {
        if ($ibid==0) {
           echo "<tr class=\"ligna\">";
        }
        $ibid++;
        if ($passwd!="") {$red="<span class=\"rouge\">$key_value (v)</span>";}else{$red="$key_value";}
        if ($presentation=="liste") {
           echo "<td><a href=\"".$this->url."&amp;".$this->submit_value."=$mess_ok&amp;browse_key=".urlencode($key_value)."\" class=\"noir\">$key_value</a></td>";
        } else {
           echo "<td><input type=\"radio\" name=\"browse_key\" value=\"".urlencode($key_value)."\"> $red</td>";
        }
        if ($ibid>=$pas) {
           echo "</tr>";
           $ibid=0;
        }
     }
     echo "</table><br />";
     if ($this->form_password_access!="") {
        echo "$mess_passwd : <input class=\"textbox_standard\" type=\"password\" name=\"password\" value=\"\"> - ";
     }
     if (!$hidden) {
        echo "<input class=\"bouton_standard\" type=\"submit\" name=\"".$this->submit_value."\" value=\"$mess_ok\">";
     }
     echo "</td></tr></table></form>";
  }

  /**************************************************************************************/
  function sform_read_mysql($clef) {
     global $NPDS_Prefix;

     if ($clef!="") {
        $clef=urldecode($clef);
        $result=sql_query("select content from ".$NPDS_Prefix."sform where id_form='".$this->form_title."' and id_key='".$this->form_key."' and key_value='".addslashes($clef)."' and passwd='".$this->form_password_access."' ORDER BY key_value ASC");
        $tmp = sql_fetch_assoc($result);
        if ($tmp) {
           $ibid=explode("\n",$tmp['content']);
           settype($op,'string');
           while (list($num,$line)=each($ibid)) {
              $op=$this->read_load_sform_data(stripslashes($line),$op);
           }
           return(true);
        } else {
           return(false);
        }
     }
  }

  /**************************************************************************************/
  function sform_insert_mysql($response) {
     global $NPDS_Prefix;

     $content=$this->write_sform_data($response);
     $sql = "INSERT INTO ".$NPDS_Prefix."sform (id_form, id_key, key_value, passwd, content) ";
     $sql .= "VALUES ('".$this->form_title."', '".$this->form_key."', '".$this->form_key_value."', '".$this->form_password_access."', '$content')";
     if (!$result = sql_query($sql)) {
        return ("Error Sform : Insert DB");
     }
  }

  /**************************************************************************************/
  function sform_delete_mysql() {
     global $NPDS_Prefix;
     $sql = "DELETE FROM ".$NPDS_Prefix."sform WHERE id_form='".$this->form_title."' and id_key='".$this->form_key."' and key_value='".$this->form_key_value."'";
     if (!$result = sql_query($sql)) {
        return ("Error Sform : Delete DB");
     }
  }

  /**************************************************************************************/
  function sform_modify_mysql($response) {
     global $NPDS_Prefix;
     $content=$this->write_sform_data($response);
     $sql = "UPDATE ".$NPDS_Prefix."sform SET passwd='".$this->form_password_access."', content='$content' WHERE (id_form='".$this->form_title."' and id_key='".$this->form_key."' and key_value='".$this->form_key_value."')";
     if (!$result = sql_query($sql)) {
        return ("Error Sform : Update DB");
     }
  }

  /**************************************************************************************/
  function sform_read_mysql_XML($clef) {
    global $NPDS_Prefix;

    if ($clef!="") {
        $clef=urldecode($clef);
        $result=sql_query("select content from ".$NPDS_Prefix."sform where id_form='".$this->form_title."' and id_key='".$this->form_key."' and key_value='$clef' and passwd='".$this->form_password_access."' ORDER BY key_value ASC");
        $tmp=sql_fetch_assoc($result);

        $analyseur_xml = xml_parser_create();

        xml_parser_set_option($analyseur_xml,XML_OPTION_CASE_FOLDING,0);
        xml_parse_into_struct($analyseur_xml,$tmp['content'],$value,$tag);

        $this->sform_XML_tag($value);

        xml_parser_free($analyseur_xml);
        return(true);
    }   else return(false);
  }
  /**************************************************************************************/
  function sform_XML_tag($value) {
    foreach ($value as $num=>$val) {
       if ($val['type']=='complete') { // open, complete, close
          $nom    = $val['tag'];       // Le nom du tag
          $valeur = $val['value'];     // La valeur du champs
          $idchamp= $this->interro_fields($nom);

          switch ( $value[$num-1]['tag'] ) {
              case "TEXT":
                   $valeur=str_replace("&lt;BR /&gt;", chr(13).chr(10), $valeur);
                   $valeur=str_replace("&lt;br /&gt;", chr(13).chr(10), $valeur);
                   $this->form_fields[$idchamp]['value']=$valeur;
                   break;
              case "SELECT":
                   $tmp=$this->interro_array($this->form_fields[$idchamp]['value'],$valeur);
                   $this->form_fields[$idchamp]['value'][$tmp]['selected']=true;
              break;
              case "RADIO":
                   $tmp=$this->interro_array($this->form_fields[$idchamp]['value'],$valeur);
                   $this->form_fields[$idchamp]['value'][$tmp]['checked']=true;
              break;
              case "CHECK":
                   if ($valeur) {$valeur=true;}else{$valeur=false;}
                   $this->form_fields[$idchamp]['checked']=$valeur;
              break;
              case "DATUM":
                   $this->form_fields[$idchamp]['value']=$valeur;
              break;
              case "TIMESTAMP":
                   $this->form_fields[$idchamp]['value']=$valeur;
              break;
          }
       }
    }
  }

}
?>