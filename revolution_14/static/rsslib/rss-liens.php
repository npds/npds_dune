<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" dir="ltr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	require_once("rsslib.php");
?>

</head>

	
<body bgcolor="#FFFFFF">
<h1>Afficher seulement les liens d'un flux RSS</h1>
<hr>
<p>Cette démonstration charge un flux RSS sur le site ou un autre site et affiche les titres seuls des pages avec les liens sur celles-ci.<br>
Elle utilise la librairie PHP  rsslib.php pour extraire et afficher l'information.</p>
<p> Taper l'URL d'un fichier RSS: </p>
<FORM name="rss" method="POST" action="rss-liens.php">
<p>
	<INPUT type="submit" value="Envoyer">
</p>
  <p> 
    <input type="text" name="dyn" size="48" value="http://www.scriptol.fr/rss.xml">
  </p>
</FORM><?php

if (isset( $_POST ))
	$posted= &$_POST ;			
else
	$posted= &$HTTP_POST_VARS ;	


if($posted!= false && count($posted) > 0)
{	
	$url= $posted["dyn"];
	if($url != false)
	{
		echo RSS_Links($url, 15);
	}
}
?>



<div id="pasf">
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6574971-1");
pageTracker._trackPageview();
} catch(err) {}
</script></div>
</body>
</html>
