<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 0 phr, jpb - 2016                                    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// ATTENTION ce script ne fonctionne parfaitement qu'avec une archive SQL avec des instructions SQL répétitives individualisées  !!

function build_sql_create($NPDS_Prefix) {

$filename='sql/revolution_16.sql';
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
foreach ( $cont as $key=>$value )
{$sql_com.= '$sql=\''.addslashes ( $value) ."';\n".'$result = @sql_query($sql);'."\n";}

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
$content .= "/*         : v.1.2.0 phr, jpb - 2017                                    */\n";
$content .= "/*                                                                      */\n";
$content .= "/* This program is free software. You can redistribute it and/or modify */\n";
$content .= "/* it under the terms of the GNU General Public License as published by */\n";
$content .= "/* the Free Software Foundation; either version 2 of the License.       */\n";
$content .= "/************************************************************************/\n";

$contents.='if (stristr($_SERVER[\'PHP_SELF\'],"sql-create.php")) { die(); }'."\n";
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
$filename='install/sql/sql-create.php';
$handle=fopen($filename,"w+");
fwrite($handle,$contents);
fclose($handle);
}
?>