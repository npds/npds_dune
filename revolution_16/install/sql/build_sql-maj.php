<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 0 phr, jpb - 2016-2024                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

// ATTENTION ce script ne fonctionne parfaitement qu'avec une archive SQL avec des instructions SQL répétitives individualisées  !!
// basé sur la comparaison et l'extraction des différences entre les archives sql d'une 16.3 et d'une 16.4
function maj_db_163to164() {
   global $NPDS_Prefix, $aff_log;
   $aff_log ='';
   // # modification de structure
   $table164 = array("authors","bannerclient","groupes","metalang");
   $t=$NPDS_Prefix.'authors'; $c='radminfilem';
   sql_query("ALTER TABLE ".$t." DROP COLUMN radminfilem");
   $aff_log.= '<br />'.$t.'<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> : suppression </small><i class="fa fa-check text-success ms-2" title="ALTER TABLE '.$t.' DROP COLUMN radminfilem;" data-bs-toggle="tooltip"></i>';

   $t=$NPDS_Prefix.'bannerclient'; $c='passwd';
   sql_query("ALTER TABLE ".$t." MODIFY ".$c." varchar(60)");
   $aff_log.= '<br />'.$t.'<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> : modification taille (varchar(60))</small><i class="fa fa-check text-success ms-2"></i>';

   $t=$NPDS_Prefix.'groupes'; $c='groupe_name';
   $aff_log .= '<br />'.$t;
   sql_query("ALTER TABLE ".$t." MODIFY ".$c." varchar(1000)");
   $aff_log.= '<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> : '.ins_translate("Modification").' taille (varchar(1000))</small><i class="fa fa-check text-success ms-2"></i>';
   $t=$NPDS_Prefix.'groupes'; $c='groupe_description';
   sql_query("ALTER TABLE ".$t." MODIFY ".$c." text");
   $aff_log.= '<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> : '.ins_translate("Modification").' type text</small><i class="fa fa-check text-success ms-2"></i>';

   $t=$NPDS_Prefix.'metalang'; $c='def';
   $metatodelete = array("!bargif!","!bgcolor1!","!bgcolor2!","!bgcolor3!","!bgcolor4!","!bgcolor5!","!bgcolor6!","!textcolor1!","!textcolor2!","!opentable!","!closetable!");
   $metatomodif = array("!anti_spam!","!search_topics!","!leftblocs!","!rightblocs!","!list_mns!","!mailadmin!","!login!","admin_infos","top_stories","top_commented_stories","top_categories","top_sections","top_reviews","top_authors","top_polls","top_storie_authors","topic_all","topic_subscribeOFF","topic_subscribe","yt_video","vm_video","dm_video");
   $aff_log .= '<br />'.$t;
   // suppression définitive de données
   foreach($metatodelete as $v){
      $sql='DELETE FROM '.$t.' WHERE def="'.$v.'"';
      $result = sql_query($sql);
      $aff_log.= '<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> : '.$v.' : '.ins_translate("Suppression").' </small><i class="fa fa-check text-success ms-2"></i>';
   }
   // suppression de données pour réinsertion avec modification
   foreach($metatomodif as $v){
      $sql='DELETE FROM '.$t.' WHERE def="'.$v.'"';
      $result = sql_query($sql);
      $aff_log.= '<br /><small class="text-success"><strong>'.$t.' : '.$c.'</strong> :  '.$v.' : '.ins_translate("Suppression").'/'.ins_translate("Modification").'</small><i class="fa fa-check text-success ms-2"></i>';
   }
   return $aff_log;
}

function build_sql_maj($NPDS_Prefix) {
   $filename='sql/maj_163_164.sql';
   $handle=fopen($filename,'r');
   $sql_contents=fread($handle, filesize ($filename));
   fclose ($handle);
   $sql_com='';
   $list_tab='';
   $content='';
//   $sql_com.='sql_query("SET character_set_results = \'utf8\', character_set_client = \'utf8\', character_set_connection = \'utf8\', character_set_database = \'utf8\', character_set_server = \'utf8\'");'."\n";

   preg_match_all("#^(CREATE TABLE\s|INSERT INTO\s)(\b[^\s]*\b)\s[^;]*[^\r|\n]*(;)#m", $sql_contents, $reg);
   /*ou
   Array $reg
   (
       [0] => Array requête sql
           (...
       [1] => Array commande sql
           (...
       [2] => Array nom de la table
           (...
       [3] => Array le ; terminant la requête
           (...
   */
   $reg[2]=array_unique($reg[2]);
   //==> construction de commande php pour sql avec prefixe des tables
/*
   foreach ( $reg[2] as $key=>$value ) {
      $sql_com.='$sql = \'DROP TABLE IF EXISTS '.$NPDS_Prefix.$value.';\';'."\n".'$result = @sql_query($sql);'."\n";
   $list_tab.= $value.' ';
   }
*/
   //==> implementation commande sql avec prefixe des tables
   $cont= preg_replace ( '#^(CREATE TABLE\s|INSERT INTO\s)(\b[^\s]*\b)(\s[^;]*[^\r]*;)#m', '\1'.$NPDS_Prefix.'\2\3', $reg[0] );

//==> construction de commande php pour sql avec protect des '\" seuls
   foreach ( $cont as $key=>$value ) {
      $sql_com.= '   $sql=\''.addslashes ( $value) ."';\n".'   $result = @sql_query($sql);'."\n";
   }

   //==> construction contenu fichier
   $contents = "<?php \n";
   $contents .= "/************************************************************************/\n";
   $contents .= "/* DUNE by NPDS                                                         */\n";
   $contents .= "/* ===========================                                          */\n";
   $contents .= "/*                                                                      */\n";
   $contents .= "/* NPDS Copyright (c) 2002-".date("Y")." by Philippe Brunier                     */\n";
   $contents .= "/* IZ-Xinstall version : 1.2.0                                          */\n";
   $contents .= "/*                                                                      */\n";
   $contents .= "/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */\n";
   $contents .= "/*         : v.1.1.1 jpb, phr                                           */\n";
   $contents .= "/*         : v.1.1.2 jpb, phr, dev, boris                               */\n";
   $contents .= "/*         : v.1.1.3 dev - 2013                                         */\n";
   $contents .= "/*         : v.1.2.0 phr, jpb - 2017-2024                               */\n";
   $contents .= "/*                                                                      */\n";
   $contents .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $contents .= "/* it under the terms of the GNU General Public License as published by */\n";
   $contents .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $contents .= "/************************************************************************/\n";
   
   $contents.='if (stristr($_SERVER[\'PHP_SELF\'],"sql-maj.php")) die();'."\n";
   $contents.='function write_database() {'."\n";
   $contents.= $sql_com;
   $contents.= "\n";
   $contents.='   global $stage6_ok;'."\n";
   $contents.='   $stage6_ok = 1;'."\n";
   $contents.='   if(!$result)'."\n";
   $contents.='      $stage6_ok = 0;'."\n";
   $contents.='   return($stage6_ok);'."\n";
   $contents.='}'."\n";
   $contents.='?>';
   
   //==> écriture contenu fichier
   $filename='install/sql/sql-maj.php';
   $handle=fopen($filename,"w+");
   fwrite($handle,$contents);
   fclose($handle);
}
?>