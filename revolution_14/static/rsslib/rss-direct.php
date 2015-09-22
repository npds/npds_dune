<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Charger directement un flux RSS et l'afficher</title></head>
<link type="text/css" href="rss-style.css" rel="stylesheet">
	
<body bgcolor="#FFFFFF">
<h1>RSS 2.0  Direct Démo</h1>
<hr>
<div id="zone" > Charger directement un flux RSS et afficher la liste des articles récents.</div>

<br>
<fieldset class="rsslib">
<?php
	require_once("rsslib.php");
	$url = "https://www.premar-mediterranee.gouv.fr/avis-urgents-aux-navigateurs/100-toulon.html?frame=rss-avurnav.php";
	echo RSS_Display($url, 15, false, true);
?>
</fieldset>
</body>
</html>
