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
/************************************************************************/
/* You can secur the access to static page by using the methode         */
/* describe in model.txt, simply add phpcode to call secur_static       */
/* new function (in mainfile.php).                                      */
/* this function accept one param with three values :                   */
/* - member / admin                                                     */
/*                                                                      */
/* static.php?op=test.txt&npds=-1&metalang=1&nl=1                       */
/*                                                                      */
/* PARAMS :                                                             */
/* op : nom du fichier qui sera chargé                                  */
/*                                                                      */
/* npds :                                                               */
/*    -1 : pas de blocs de Gauche ET pas de blocs de Droite (no blocks) */
/*     0 : blocs de Gauche ET pas de blocs Droite (no right blocks)     */
/*     1 : blocs de Gauche ET blocs de Droite (the two)                 */
/*     2 : pas de blocs Gauche ET blocs de Droite (no left blocks)      */
/*     et plus ou moins : suivant la capacité de la variable $pdst      */
/*     dans le thème utilisé                                            */
/*                                                                      */
/* metalang (0=inactif - défaut | 1=actif) :                            */
/*    l'interprétation meta-lang, [code] ... [/code] et Multi-langue    */
/*    sera  réalisée                                                    */
/*                                                                      */
/* nl (0=inactif - défaut | 1=actif)                                    */
/*    execute nl2br(str_replace(" ","&nbsp;",htmlentities($remp)))      */
/*    avant d'afficher le fichier                                       */
/************************************************************************/
if (!function_exists("Mysql_Connexion"))
   include ("mainfile.php");

   settype($npds,'integer');
   settype($op,'string');
   settype($metalang,'integer');
   settype($nl,'integer');
   $pdst=$npds;
   $remp='';
   include ("header.php");
   echo '
                  <div id="static_cont">';
   if (($op!='') and ($op)) {
      // Troll Control for security
      if (preg_match('#^[a-z0-9_\.-]#i',$op) and !stristr($op,".*://") and !stristr($op,"..") and !stristr($op,"../") and !stristr($op, "script") and !stristr($op, "cookie") and !stristr($op, "iframe") and  !stristr($op, "applet") and !stristr($op, "object") and !stristr($op, "meta")) {
         if (file_exists("static/$op")) {
            if (!$metalang and !$nl)
               include ("static/$op");
            else {
               ob_start();
                 include ("static/$op");
                 $remp=ob_get_contents();
               ob_end_clean();
               if ($metalang)
                  $remp=meta_lang(aff_code(aff_langue($remp)));
               if ($nl)
                  $remp=nl2br(str_replace(' ','&nbsp;',htmlentities($remp,ENT_QUOTES,'UTF-8')));
               echo $remp;
            }
            echo '
                     <div class=" my-3"><a href="print.php?sid=static:'.$op.'&amp;metalang='.$metalang.'&amp;nl='.$nl.'" data-bs-toggle="tooltip" data-bs-placement="right" title="'.translate("Page spéciale pour impression").'"><i class="fa fa-2x fa-print"></i></a></div>';

            // Si vous voulez tracer les appels au pages statiques : supprimer les // devant la ligne ci-dessous
            // Ecr_Log("security", "static/$op", "");
         }
         else
            echo '
                     <div class="alert alert-danger">'.translate("Merci d'entrer l'information en fonction des spécifications").'</div>';
      } 
      else
         echo '
                     <div class="alert alert-danger">'.translate("Merci d'entrer l'information en fonction des spécifications").'</div>';
   }
   echo '
                  </div>';
   include ("footer.php");
?>