<?php

/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

	require_once('fonctions.php');
	if(isset($_GET['message'])){$message = urldecode ($_GET['message']);} else{$message = "";}
	if($message==""){
		header("Location: index.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="refresh" content="5;url=upload.php" />
<title>Galerie Photos - Success</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>
	<?php 
			
			if($message!=""){
			echo "<p class='good'>L'ajout de l'image $message s'est effectu&eacute;e avec succ&egrave;s.</p>";
		}
	?>
		
	<?php require_once('footer.php'); ?>
</body>
</html>