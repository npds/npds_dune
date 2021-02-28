<?php
/* Do not change anything in this file manually. Use the administration interface*/
settype($meta_doctype,'string');
settype($nuke_url,'string');
settype($meta_op,'string');
settype($m_description,'string');
settype($m_keywords,'string');
if ($meta_doctype=="")
   $l_meta="<!DOCTYPE html>\n<html>\n<head>\n<title>\n\n";
else
   $l_meta=$meta_doctype."\n<html>\n<head><title>\n\n";
$l_meta.="<meta http-equiv=\"content-type\" content=\"text/html\" />\n";
$l_meta.="<meta charset=\"utf-8\" />\n";
$l_meta.="<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />\n";
$l_meta.="<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n";
$l_meta.="<meta http-equiv=\"content-script-type\" content=\"text/javascript\" />\n";
$l_meta.="<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n";
$l_meta.="<meta http-equiv=\"expires\" content=\"0\" />\n";
$l_meta.="<meta http-equiv=\"pragma\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"cache-control\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"identifier-url\" content=\"$nuke_url\" />\n";
$l_meta.="<meta name=\"author\" content=\"Developpeur\" />\n";
$l_meta.="<meta name=\"owner\" content=\"npds.org\" />\n";
$l_meta.="<meta name=\"reply-to\" content=\"developpeur@npds.org\" />\n";
$l_meta.="<meta name=\"language\" content=\"fr\" />\n";
$l_meta.="<meta http-equiv=\"content-language\" content=\"fr, fr-be, fr-ca, fr-lu, fr-ch\" />\n";
if ($m_description!="")
   $l_meta.="<meta name=\"description\" content=\"$m_description\" />\n";
else
   $l_meta.="<meta name=\"description\" content=\"G&#xE9;n&#xE9;rateur de portail Fran&#xE7;ais en Open-Source sous licence Gnu-Gpl utilisant Php et MySql\" />\n";
if ($m_keywords!="")
   $l_meta.="<meta name=\"keywords\" content=\"$m_keywords\" />\n";
else
   $l_meta.="<meta name=\"keywords\" content=\"solution,solutions,portail,portails,generateur,g&#xC8;n&#xC8;rateur,nouveau,Nouveau,Technologie,technologie,npds,NPDS,Npds,nuke,Nuke,PHP-Nuke,phpnuke,php-nuke,nouvelle,Nouvelle,nouvelles,histoire,Histoire,histoires,article,Article,articles,Linux,linux,Windows,windows,logiciel,Logiciel,t&#xC8;l&#xC8;chargement,t&#xC8;l&#xC8;chargements,T&#xC8;l&#xC8;chargement,T&#xC8;l&#xC8;chargements,gratuit,Gratuit,Communaut&#xC8;,communaut&#xC8;,Forum,forum,Forums,forums,Bulletin,bulletin,application,Application,dynamique,Dynamique,PHP,Php,php,sondage,Sondage,Commentaire,commentaire,Commentaires,commentaires,annonce,annonces,petite,Petite,petite annonce,mailling,mail,faq,Faq,faqs,lien,Lien,liens,france,francais,fran&#xC1;ais,France,Francais,Fran&#xC1;ais,libre,Libre,Open,open,Open Source,OpenSource,Opensource,GNU,gnu,GPL,gpl,License,license,Unix,UNIX,unix,MySQL,mysql,SQL,sql,Database,DataBase,database,Red Hat,RedHat,red hat,Web Site,web site,site,sites,web,Web\" />\n";
$l_meta.="<meta name=\"rating\" content=\"general\" />\n";
$l_meta.="<meta name=\"distribution\" content=\"global\" />\n";
$l_meta.="<meta name=\"copyright\" content=\"npds.org 2001-2021\" />\n";
$l_meta.="<meta name=\"revisit-after\" content=\"15 days\" />\n";
$l_meta.="<meta name=\"resource-type\" content=\"document\" />\n";
$l_meta.="<meta name=\"robots\" content=\"all\" />\n";
$l_meta.="<meta name=\"generator\" content=\"NPDS 16 REvolution\" />\n";
$l_meta=str_replace("<title>","<title>$Titlesitename</title>",$l_meta);
if ($meta_op=="") echo $l_meta; else $l_meta=str_replace("\n","",str_replace("\"","'",$l_meta));
?>
