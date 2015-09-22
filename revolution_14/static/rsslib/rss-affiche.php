<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" dir="ltr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	require_once("rsslib.php");
?>

</head>

	
<body bgcolor="#FFFFFF">
<h1>Démo d'affichage RSS</h1>
<hr>
<div id="zone" > <p>Charger un fichier  RSS file et afficher la liste des articles. </p>

	<?php

		if (isset( $_POST ))
			$postArray = &$_POST ;			
		else
			$postArray = &$HTTP_POST_VARS ;	

		$url= $postArray["dyn"];

		echo RSS_display($url, 10);
	?>

</div>


</body>
</html>
