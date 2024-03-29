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
   global $NPDS_Prefix;
   $aff ='';
   $table164 = array("authors","bannerclient","groupes","metalang");
   // # mise à jour structure
   $t='authors'; $c='radminfilem';
   $sql="ALTER TABLE ".$NPDS_Prefix.$t." DROP COLUMN radminfilem";
   $result = sql_query($sql);
   $aff.= '<small class="text-success"><strong>'.$NPDS_Prefix.$t.'</strong> : suppression colonne '.$c.' </small><i class="fa fa-check text-success ml-2" title="ALTER TABLE '.$NPDS_Prefix.$t.' DROP COLUMN radminfilem;" data-toggle="tooltip"></i><br />';

   $t='bannerclient'; $c='passwd';
   $sql="ALTER TABLE ".$NPDS_Prefix.$t." MODIFY ".$c." varchar(60)";
   $result = sql_query($sql);
   $aff.= '<br />'.$t.'<br /><small class="text-success"><strong>'.$NPDS_Prefix.$t.' : '.$c.'</strong> : modification taille (varchar(60))</small><i class="fa fa-check text-success ml-2"></i><br />';

   $t='groupes'; $c='groupe_name';
   $sql="ALTER TABLE ".$NPDS_Prefix.$t." MODIFY ".$c." varchar(1000)";
   $result = sql_query($sql);
   $aff.= '<br />'.$t.'<br /><small class="text-success"><strong>'.$NPDS_Prefix.$t.' : '.$c.'</strong> : modification taille (varchar(1000))</small><i class="fa fa-check text-success ml-2"></i><br />';

   $t='groupes'; $c='groupe_description';
   $sql="ALTER TABLE ".$NPDS_Prefix.$t." MODIFY ".$c." text";
   $result = sql_query($sql);
   $aff.= '<br />'.$t.'<br /><small class="text-success"><strong>'.$NPDS_Prefix.$t.' : '.$c.'</strong> : modification type text</small><i class="fa fa-check text-success ml-2"></i><br />';

   $t='metalang'; $c='def';
   $metatodelete = array("!bargif!","!bgcolor1!","!bgcolor2!","!bgcolor3!","!bgcolor4!","!bgcolor5!","!bgcolor6!","!textcolor1!","!textcolor2!","!opentable!","!closetable!");
   $metatomodif = array("!anti_spam!","!search_topics!","!leftblocs!","!rightblocs!","!list_mns!","!mailadmin!","!login!","admin_infos","top_stories","top_commented_stories","top_categories","top_sections","top_reviews","top_authors","top_polls","top_storie_authors","topic_all","topic_subscribeOFF","topic_subscribe","yt_video","vm_video","dm_video");

   foreach($metatodelete as $v){
      $sql='DELETE FROM '.$NPDS_Prefix.$t.' WHERE def="'.$v.'"';
      $result = sql_query($sql);
      $aff.= '<br /><small class="text-success"><strong>'.$NPDS_Prefix.$t.' : '.$c.'</strong> : suppression '.$v.'</small><i class="fa fa-check text-success ml-2"></i><br />';
   }

   foreach($metatomodif as $v){
      $sql='DELETE FROM '.$NPDS_Prefix.'metalang WHERE def="'.$v.'"';
      $result = sql_query($sql);
      $aff.= '<br /><small class="text-success"><strong>'.$NPDS_Prefix.$t.' : '.$c.'</strong> : suppression '.$v.'</small><i class="fa fa-check text-success ml-2"></i><br />';
   }
}

function build_sql_maj($NPDS_Prefix) {
   $filename='sql/maj_163_164.sql';
   $handle=fopen($filename,'r');
   $sql_contents=fread($handle, filesize ($filename));
   fclose ($handle);
   $sql_com='';
   $list_tab='';
   $content='';
   $sql_com.='sql_query("SET character_set_results = \'utf8\', character_set_client = \'utf8\', character_set_connection = \'utf8\', character_set_database = \'utf8\', character_set_server = \'utf8\'");';

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
   foreach ( $reg[2] as $key=>$value )
   {$sql_com.='$sql = \'DROP TABLE IF EXISTS '.$NPDS_Prefix.$value.';\';'."\n".'$result = @sql_query($sql);
   '."\n";
   $list_tab.= $value.' ';
   }
   //==> implementation commande sql avec prefixe des tables
   $cont= preg_replace ( '#^(CREATE TABLE\s|INSERT INTO\s)(\b[^\s]*\b)(\s[^;]*[^\r]*;)#m', '\1'.$NPDS_Prefix.'\2\3', $reg[0] );

//==> construction de commande php pour sql avec protect des '\" seuls
   foreach ( $cont as $key=>$value ) {
      $sql_com.= '$sql=\''.addslashes ( $value) ."';\n".'$result = @sql_query($sql);'."\n";
   }

   //==> construction contenu fichier
   $contents = "<?php \n";
   $content .= "/************************************************************************/\n";
   $content .= "/* DUNE by NPDS                                                         */\n";
   $content .= "/* ===========================                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* NPDS Copyright (c) 2002-".date("Y")." by Philippe Brunier                     */\n";
   $content .= "/* IZ-Xinstall version : 1.2.0                                          */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */\n";
   $content .= "/*         : v.1.1.1 jpb, phr                                           */\n";
   $content .= "/*         : v.1.1.2 jpb, phr, dev, boris                               */\n";
   $content .= "/*         : v.1.1.3 dev - 2013                                         */\n";
   $content .= "/*         : v.1.2.0 phr, jpb - 2017-2024                               */\n";
   $content .= "/*                                                                      */\n";
   $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content .= "/* it under the terms of the GNU General Public License as published by */\n";
   $content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content .= "/************************************************************************/\n";
   
   $contents.='if (stristr($_SERVER[\'PHP_SELF\'],"sql-maj.php")) { die(); }'."\n";
   $contents.='function write_database()'."\n";
   $contents.=' {'."\n";
   $contents.= $sql_com;
   $contents.= "\n";
   $contents.='   global $stage6_ok;'."\n";
   $contents.='   $stage6_ok = 1;'."\n";
   $contents.='   if(!$result)'."\n";
   $contents.='    {'."\n";
   $contents.='    $stage6_ok = 0;'."\n";
   $contents.='    }'."\n";
   $contents.='    return($stage6_ok);'."\n";
   $contents.=' }'."\n";
   $contents.='?>';
   
   //==> écriture contenu fichier
   $filename='install/sql/sql-maj.php';
   $handle=fopen($filename,"w+");
   fwrite($handle,$contents);
   fclose($handle);
}
?>