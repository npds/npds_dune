<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" dir="ltr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	require_once("rsslib.php");
?>

<title>Démo  RSS - Charger un flux dans une autre page</title></head>

	
<body bgcolor="#FFFFFF">
<h1>Démo RSS  - Charger un flux dans une autre page </h1>
<hr>
Cette démo charge un flux RSS et fournit le contenu en paramètre à une autre page Web.. 
<br>
L'autre page utilise PHP et  rsslib.php pour afficher le contenu du flux.<br>
Taper l'URL du fichier RSS: 
<FORM name="rss" method="POST" action="rss-affiche.php">
<p>
	<INPUT type="submit" value="Envoyer">
</p>
<p>
	
    <input type="text" name="dyn" size="32" value="http://www.scriptol.fr/rss.xml">
</p>

</FORM>


</body>
</html>
