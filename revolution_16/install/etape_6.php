<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2016 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"install.php")) { die(); }

include ('config.php');
   if($NPDS_Prefix!='') {
      $pre_tab = ins_translate(' Tables préfixées avec : ').'<code class="code">'.$NPDS_Prefix.'. </code>';
   }
   else
   {$pre_tab='';}

function etape_6() {
   global $list_tab, $langue, $stage,$dbhost,$dbname,$dbuname,$dbpass,$NPDS_Prefix,$pre_tab;
   $stage = 6;
   echo '
               <h3>'.ins_translate('Base de données').'</h3>
                  <p id="mess_bd">'.ins_translate('Nous allons maintenant procéder à la création des tables de la base de données ').' (&nbsp;<code class="code">'.$dbname.'</code>&nbsp;) '.ins_translate('sur le serveur d\'hébergement').' (&nbsp;<code class="code">'.$dbhost.'</code>&nbsp;). '.$pre_tab.'<br />'. ins_translate('Si votre base de données comporte déjà des tables, veuillez en faire une sauvegarde avant de poursuivre !').'<br />'.ins_translate('Si la base de données').' (&nbsp;<code class="code">'.$dbname.'</code>&nbsp;) '.ins_translate('n\'existait pas ce script tentera de la créer pour vous.').'</p><br />
                  <form name="database" method="post" action="install.php">
                     <input type="hidden" name="langue" value="'.$langue.'" />
                     <input type="hidden" name="stage" value="'.$stage.'" />
                     <input type="hidden" name="op" value="write_database" />
                     <button type="submit" class="btn btn-success">'.ins_translate(' Créer ').'</button>
                  </form>
               </div>';
}
?>