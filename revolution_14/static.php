<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* Based on PhpNuke 4.x source code                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2013 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
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
/*                                                                      */
/* metalang (0=inactif - défaut | 1=actif) :                            */
/*    l'interprétation meta-lang, [code] ... [/code] et Multi-langue    */
/*    sera  réalisée                                                    */
/*                                                                      */
/* nl (0=inactif - défaut | 1=actif)                                    */
/*    execute nl2br(str_replace(" ","&nbsp;",htmlentities($remp)))      */
/*    avant d'afficher le fichier                                       */
/* Revu phr 31/05/2015                                                  */
/************************************************************************/
if (!function_exists("Mysql_Connexion")) {
   include ("mainfile.php");
}

   settype($npds,'integer');
   settype($op,'string');
   settype($metalang,'integer');
   settype($nl,'integer');
   $pdst=$npds;
   $remp="";
   include ("header.php");

   if (($op!="") and ($op)) {
      // Troll Control for security
      if (preg_match('#^[a-z0-9_\.-]#i',$op) and !stristr($op,".*://") and !stristr($op,"..") and !stristr($op,"../") and !stristr($op, "script") and !stristr($op, "cookie") and !stristr($op, "iframe") and  !stristr($op, "applet") and !stristr($op, "object") and !stristr($op, "meta"))  {
         if (file_exists("static/$op")) {
            if (!$metalang and !$nl) {
               include ("static/$op");
            } else {
               ob_start();
                 include ("static/$op");
                 $remp=ob_get_contents();
               ob_end_clean();
               if ($metalang)
                  $remp=meta_lang(aff_code(aff_langue($remp)));
               if ($nl)
                  $remp=nl2br(str_replace(" ","&nbsp;",htmlentities($remp,ENT_QUOTES,cur_charset)));
               echo $remp;
            }
            if (!$imgtmp=theme_image("box/print.gif")) { $imgtmp="images/print.gif"; }
            echo "<a href=\"print.php?sid=static:$op&amp;metalang=$metalang&amp;nl=$nl\" title=\"".translate("Printer Friendly Page")."\"><i class=\"fa fa-2x fa-print\"></i></a>";

            // Si vous voulez tracer les appels au pages statiques : supprimer les // devant la ligne ci-dessous
            // Ecr_Log("security", "static/$op", "");
         } else {
            echo "<p class=\"text-danger text-center\">".translate("Please enter information according to the specifications")."</p>";
         }
      } else {
         echo "<p class=\"text-danger text-center\">".translate("Please enter information according to the specifications")."</p>";
      }
   }
   include ("footer.php");
?>