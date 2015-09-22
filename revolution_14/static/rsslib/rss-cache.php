<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Démo de flux RSS 2.0 Affiché avec une mémoire tampon</title></head>
<link type="text/css" href="rss-style.css" rel="stylesheet">
	
<body bgcolor="#FFFFFF">
<h1>Démo de flux RSS 2.0  affiché avec une mémoire tampon</h1>
<hr>
<div id="zone" > Le flux RSS est converti en HTML et stocké dans un fichier temporaire qui est intégré à une page Web.<br />
A intervalles réguliers, le fichier HTML est actualisé. </div>

<br>
<fieldset class="rsslib">
<?php
$cachename = "rss-cache-tmp.php";
$url = "http://www.scriptol.fr/rss.xml"; 
if(file_exists($cachename))
{
  $now = date("G");
  $time = date("G", filemtime($cachename));
  if($time == $now)
  {
     include($cachename);
     exit();
  }
}
require_once("rsslib.php");
$cache = RSS_Display($url, 15, false, true);
file_put_contents($cachename, $cache);
echo $cache;
?>
</fieldset>
</body>
</html>
