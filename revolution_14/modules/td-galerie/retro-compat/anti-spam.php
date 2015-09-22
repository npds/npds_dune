<?php
/************************************************************************/
/* NPDS                                                                 */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2007 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/************************************************************************************/
/* A n'utiliser que si votre config.php contient la variable $NPDS_Key              */
/* $NPDS_Key="Une valeur complexe de votre choix (Azer125Gtuiop::345 par exemple)"; */
/************************************************************************************/

function spambot_trans($phrase) {
    global $language;

    switch ($phrase) {
       case "Anti-Spam / Merci de r&eacute;pondre &agrave; la question :":
            if ($language=="english") {
               $tmp = "Anti-Spam / Thank to reply to the question :"; break;
            } else
               $tmp=$phrase; break;

       default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}

#autodoc Q_spambot() : forge un champ de formulaire (champ de saisie : $asb_reponse / champ hidden : asb_question) permettant de déployer une fonction anti-spambot
function Q_spambot() {
   // idée originale, développement et intégration
   // Gérald MARINO alias neo-machine
   global $user;          

   $asb_question = array (
   '7 - 6'    => 1,
   '4 - 2'    => 2,
   '5 - 2'    => 3,
   '1 + 1 + 2'   => 4,
   '3 + 2'    => 5,
   '3 * 2'    => 6,
   '4 + 3'    => 7,
   '2 + 4 + 2'   => 8,
   '6 + 3'    => 9,
   '5 * 2'    => 10,
   '6 + 5'    => 11,
   '3 + 3 + 6'   => 12,
   '7 + 6'    => 13,
   '2 * 7'    => 14,
   '5 * 3'    => 15,
   '10 + 5 + 1'  => 16,
   '18 - 1'   => 17,
   '6 * 3'    => 18,
   '17 + 2'   => 19,
   '5 + 5 + 10'  => 20);

   mt_srand((double)microtime()*1000000);
   $asb_index = mt_rand(0,count($asb_question)-1);
   $tmp="";
   $ibid=array_keys($asb_question);
   if (!$user) {
      $tmp="<span style=\"font-size=9px;\">".spambot_trans("Anti-Spam / Merci de r&eacute;pondre &agrave; la question :")." </span><span class=\"ROUGE\">".$ibid[$asb_index]." = </span><input class=\"TEXTBOX_STANDARD\" type=\"text\" name=\"asb_reponse\" size=\"3\" maxlength=\"2\" onclick=\"this.value=''\">";
      $tmp.="<input type=\"hidden\" name=\"asb_question\" value=\"".encrypt($ibid[$asb_index].",".time())."\">\n";
   }
   return ($tmp);
}

#autodoc R_spambot($asb_question, $asb_reponse) : valide le champ $asb_question avec la valeur de $asb_reponse (anti-spambot)
function R_spambot($asb_question, $asb_reponse) {
   // idée originale, développement et intégration
   // Gérald MARINO alias neo-machine
   global $user;

   global $REQUEST_METHOD;
   if ($REQUEST_METHOD=="POST") {
      if (!$user) {
         if ($asb_question!="") {
            $ibid=decrypt($asb_question);
            $ibid=explode(",",$ibid);
            $result="\$arg=($ibid[0]);";
            // submit intervient dans l'heure - il faudra que le robot fasse une MAJ de sa règle une fois par heure ...
            if ((time()-$ibid[1])<3600) {
               eval($result);
            } else {
               $arg=uniqid(mt_rand());
            }
         } else {
            $arg=uniqid(mt_rand());
         }
         if ($arg==$asb_reponse) {
            return (true);
         } else {
            return (false);
         }
      } else {
         return (true);
      }
   } else {
      return (false);
   }
}

#autodoc keyED($txt,$encrypt_key) : Composant des fonctions encrypt et decrypt
function keyED($txt,$encrypt_key) {
   $encrypt_key = md5($encrypt_key);
   $ctr=0;
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       if ($ctr==strlen($encrypt_key) ) $ctr=0;
       $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
       $ctr++;
   }
   return $tmp;
}
#autodoc encrypt($txt) : retourne une chaine encryptée en utilisant la valeur de $NPDS_Key
function encrypt($txt) {
   global $NPDS_Key;

   return (encryptK($txt,$NPDS_key));
}
#autodoc encryptK($txt, $C_key) : retourne une chaine encryptée en utilisant la clef : $C_key
function encryptK($txt, $C_key) {
   srand( (double)microtime()*1000000);
   $encrypt_key = md5(rand(0,32000) );
   $ctr=0;
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       if ($ctr==strlen($encrypt_key) ) $ctr=0;
       $tmp.= substr($encrypt_key,$ctr,1) .
       (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1) );
       $ctr++;
   }
   return base64_encode(keyED($tmp,$C_key));
}
#autodoc decrypt($txt) : retourne une chaine décryptée en utilisant la valeur de $NPDS_Key
function decrypt($txt) {
   global $NPDS_Key;

   return (decryptK($txt, $NPDS_key));
}
#autodoc decryptK($txt, $C_key) : retourne une décryptée en utilisant la clef de $C_Key
function decryptK($txt, $C_key) {
   $txt = keyED(base64_decode($txt),$C_key);
   $tmp = "";
   for ($i=0;$i<strlen($txt);$i++) {
       $md5 = substr($txt,$i,1);
       $i++;
       $tmp.= (substr($txt,$i,1) ^ $md5);
   }
   return ($tmp);
}
?>