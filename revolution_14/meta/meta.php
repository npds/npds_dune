<?php
/* Do not change anything in this file manually. Use the administration interface*/
settype($meta_doctype,'string');
settype($nuke_url,'string');
settype($meta_op,'string');
if ($meta_doctype=="")
   $l_meta="<!DOCTYPE html>\n<head>\n<title>\n\n";
else
   $l_meta=$meta_doctype."\n<head><title>\n\n";
$l_meta.="<meta http-equiv=\"content-type\" content=\"text/html\" />\n";
$l_meta.="<meta charset=\"utf-8\" />\n";
$l_meta.="<meta http-equiv=\"content-script-type\" content=\"text/javascript\" />\n";
$l_meta.="<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n";
$l_meta.="<meta http-equiv=\"expires\" content=\"0\" />\n";
$l_meta.="<meta http-equiv=\"pragma\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"cache-control\" content=\"no-cache\" />\n";
$l_meta.="<meta http-equiv=\"identifier-url\" content=\"$nuke_url\" />\n";
$l_meta.="<meta name=\"author\" content=\"JPB-PHR\" />\n";
$l_meta.="<meta name=\"owner\" content=\"labo.infocapagde.com\" />\n";
$l_meta.="<meta name=\"reply-to\" content=\"previlliod@yahoo.fr\" />\n";
$l_meta.="<meta name=\"language\" content=\"fr\" />\n";
$l_meta.="<meta http-equiv=\"content-language\" content=\"fr, fr-be, fr-ca, fr-lu, fr-ch\" />\n";
$l_meta.="<meta name=\"description\" content=\"fr\" />\n";
$l_meta.="<meta name=\"keywords\" content=\"labo, npds,responsive,bootstrap,\" />\n";
$l_meta.="<meta name=\"rating\" content=\"general\" />\n";
$l_meta.="<meta name=\"distribution\" content=\"global\" />\n";
$l_meta.="<meta name=\"copyright\" content=\"npds.org 2001-2015\" />\n";
$l_meta.="<meta name=\"revisit-after\" content=\"15 days\" />\n";
$l_meta.="<meta name=\"resource-type\" content=\"document\" />\n";
$l_meta.="<meta name=\"robots\" content=\"none\" />\n";
$l_meta.="<meta name=\"generator\" content=\"NPDS 11 REvolution WS\" />\n";
$l_meta=str_replace("<title>","<title>$Titlesitename</title>",$l_meta);
if ($meta_op=="") echo $l_meta; else $l_meta=str_replace("\n","",str_replace("\"","'",$l_meta));
?>