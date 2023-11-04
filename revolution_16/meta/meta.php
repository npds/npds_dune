<?php
/* Do not change anything in this file manually. Use the administration interface. */
/* généré le : 04-11-2023 12:01:30 */
global $nuke_url;
$meta_doctype = isset($meta_doctype) ? $meta_doctype : '' ;
$nuke_url = isset($nuke_url) ? $nuke_url : '' ;
$meta_doctype = isset($meta_doctype) ? $meta_doctype : '' ;
$meta_op = isset($meta_op) ? $meta_op : '' ;
$m_description = isset($m_description) ? $m_description : '' ;
$m_keywords = isset($m_keywords) ? $m_keywords : '' ;
$lang = language_iso(1, '', 0);
if ($meta_doctype=="")
   $l_meta="<!DOCTYPE html>\n<html lang=\"$lang\">\n<head>\n";
else
   $l_meta=$meta_doctype."\n<html lang=\"$lang\">\n<head>\n";
$l_meta.="<meta charset=\"utf-8\" />\n";
$l_meta.="<title>$Titlesitename</title>\n";
$l_meta.="<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />\n";
$l_meta.="<meta http-equiv=\"content-script-type\" content=\"text/javascript\" />\n";
$l_meta.="<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n";
$l_meta.="<meta http-equiv=\"expires\" content=\"0\" />\n";
$l_meta.="<meta http-equiv=\"pragma\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"cache-control\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"identifier-url\" content=\"$nuke_url\" />\n";
$l_meta.="<meta name=\"author\" content=\"Developpeur\" />\n";
$l_meta.="<meta name=\"owner\" content=\"npds.org\" />\n";
$l_meta.="<meta name=\"reply-to\" content=\"developpeur@npds.org\" />\n";
if ($m_description!="")
   $l_meta.="<meta name=\"description\" content=\"$m_description\" />\n";
else
   $l_meta.="<meta name=\"description\" content=\"Générateur de portail Français en Open-Source sous licence Gnu-Gpl utilisant Php et MySql\" />\n";
if ($m_keywords!="")
   $l_meta.="<meta name=\"keywords\" content=\"$m_keywords\" />\n";
else
   $l_meta.="<meta name=\"keywords\" content=\"solution,solutions,portail,portails,generateur,générateur,nouveau,Nouveau,Technologie,technologie,npds,NPDS,Npds,nuke,Nuke,PHP-Nuke,phpnuke,php-nuke,nouvelle,Nouvelle,nouvelles,histoire,Histoire,histoires,article,Article,articles,Linux,linux,Windows,windows,logiciel,Logiciel,téléchargement,téléchargements,Téléchargement,Téléchargements,gratuit,Gratuit,Communauté,communauté;,Forum,forum,Forums,forums,Bulletin,bulletin,application,Application,dynamique,Dynamique,PHP,Php,php,sondage,Sondage,Commentaire,commentaire,Commentaires,commentaires,annonce,annonces,petite,Petite,petite annonce,mailling,mail,faq,Faq,faqs,lien,Lien,liens,france,francais,français,France,Francais,Français,libre,Libre,Open,open,Open Source,OpenSource,Opensource,GNU,gnu,GPL,gpl,License,license,Unix,UNIX,unix,MySQL,mysql,SQL,sql,Database,DataBase,database,Red Hat,RedHat,red hat,Web Site,web site,site,sites,web,Web\" />\n";
$l_meta.="<meta name=\"rating\" content=\"general\" />\n";
$l_meta.="<meta name=\"distribution\" content=\"global\" />\n";
$l_meta.="<meta name=\"copyright\" content=\"npds.org 2001-2023\" />\n";
$l_meta.="<meta name=\"revisit-after\" content=\"15 days\" />\n";
$l_meta.="<meta name=\"resource-type\" content=\"document\" />\n";
$l_meta.="<meta name=\"robots\" content=\"all\" />\n";
$l_meta.="<meta name=\"generator\" content=\"NPDS v.16.8 REvolution\" />\n";
$l_meta.="<meta property=\"og:type\" content=\"website\" />\n";
$l_meta.="<meta property=\"og:url\" content=\"$nuke_url\" />\n";
$l_meta.="<meta property=\"og:title\" content=\"$Titlesitename\" />\n";
$l_meta.="<meta property=\"og:description\" content=\"Générateur de portail Français en Open-Source sous licence Gnu-Gpl utilisant Php et MySql\" />\n";
$l_meta.="<meta property=\"og:image\" content=\"$nuke_url/images/ogimg.jpg\" />\n";
$l_meta.="<meta property=\"twitter:card\" content=\"summary\" />\n";
if ($meta_op=="") echo $l_meta; else $l_meta=str_replace("\n","",str_replace("\"","'",$l_meta));
?>