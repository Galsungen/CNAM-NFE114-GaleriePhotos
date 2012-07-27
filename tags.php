<?php
/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

//error_reporting(E_ALL);
//ini_set("display_errors", 1); 

//inclusion des fichiers de fonctions et pour la bdd
require_once('fonctions.php');
require_once('abdd.php');

//d�claration de variabes
$Erreurs = array();
$good="";

if(isset($_POST['Soumettre'])){$Soumettre = $_POST['Soumettre'];} else{$Soumettre = "";}
if(isset($_POST['nom_tag'])){$nom_tag = $_POST['nom_tag'];} else{$nom_tag = "";}
//debug($_POST);

if(isset($_GET['id'])){$id = $_GET['id'];} else{$id = "";}

$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
    or die("Impossible de se connecter : " . mysql_error());

$db_selected = mysql_select_db($nombdd, $link);
if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}

//si la variable id est renseignée on supprime puis on redirige
if($id!=""){
	$sql = "DELETE FROM gd_tags WHERE id='$id';";	
	
	//on exécute la requête pour supprimer l'enregistremet puis on va supprimer les relations avec des photos
	$result = mysql_query($sql);
	if (!$result){
		die('Requete invalide : ' . mysql_error());
	}
	else {
		//on supprime les associations qui peuvent exister avec des photos pour nettoyer la table de liaisons
		$sql="DELETE FROM gd_liensphta WHERE id_ta='$id';";
		$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		
		mysql_close($link);
		header("Location: tags.php");
	}
}

//quelque soit le cas, on affiche un tableau des tags existants

$sql = "SELECT * FROM `gd_tags`;";
$result = mysql_query($sql);
if (!$result){
	die('Requete invalide : ' . mysql_error());
}
else {
	//on traite le retour et on remplit un tableau
	while ($row =mysql_fetch_assoc($result)){
		//debug($row);
        $tableau_tags[$row['id']]=$row['tag'];
	}
}

//création tableau des tags ayant des photos
$sql="SELECT distinct(id_ta) FROM `gd_liensphta`;";
$result = mysql_query($sql);
if (!$result){
	die('Requete invalide : ' . mysql_error());
}
else {
	//on traite le retour et on remplit un tableau
	while ($row =mysql_fetch_assoc($result)){
		//debug($row);
        $tags_photos[]=$row['id_ta'];
	}
}

if($Soumettre != ""){
	if(($nom_tag != "") && (strlen($nom_tag)<=50)){
		if(!in_array($nom_tag, $tableau_tags)){
			//création requête d'update
			$sql = "INSERT INTO `gd_tags` (`tag`) VALUES ('".mysql_escape_string($nom_tag)."');";
			$result = mysql_query($sql);
			if (!$result){
				die('Requete invalide : ' . mysql_error());
			}
			else {
				//on renseigne le message de bon upload qui déclenchera le compte à rebourd vers view
				$tableau_tags[]=$nom_tag;
				$good="Ajout du tag $nom_tag r&eacute;alis&eacute; avec succ&egrave;s.";
			}
		}
		else{$Erreurs[]="Ce tag existe déjà.";}

	}
	else{
		if($nom_tag == ""){
			$Erreurs[]="Le champ tag est vide.";
		}
		elseif(strlen($nom_tag)>50){
			$Erreurs[]="Le tag est trop long. 50 caract&egrave;res maximum pour ".strlen($nom_tag)." ici.";
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Galerie Photos - Gestion des tags</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>
	<h1>Gestion des tags</h1>
	<div class="form_tags">
		<p>Autres actions : <a href="index.php"><img src="pictures/pictures.png" alt="Index" title="Retour à l'index" /></a></p>
	
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
		<p>Nom du tag : <input type="text" name="nom_tag" maxlength="50" value="<?php echo $nom_tag; ?>" /></p>
		<p><input type="submit" value="Soumettre" name="Soumettre" /></p>
		</form>
	</div>
	<?php 
		//affichage des erreurs
		if(count($Erreurs)>0){
			echo "<p class='Erreurs1'>Affichage des erreurs : </p><p class='Erreurs2'>";
			foreach ($Erreurs as $key => $value){
				echo "$value <br />";
			}
			echo "</p>";
		}
		
		if($good != ""){echo "<p class='good'>$good</p>";}
	?>
	
	<?php 
	
		//Affichage du tableau pour les tags
		if(count($tableau_tags)>0){
			echo "<h2 class=\"mil\">Tags existants</h2><table class=\"tags\"><tr class=\"tags\">";
			$i=1;
			foreach($tableau_tags as $key => $value){
				//on affiche
				if($i<=10){
					echo "<td class=\"tags\">";
					if(in_array($key, $tags_photos)) { echo "<a href=\"index.php?tag=$key\" title=\"Voir les photos associ&eacute;es à $value\">$value</a>"; }
					else { echo $value; }
					echo "</td><td class=\"tags\"><a href=\"tags.php?id=$key\"><img src=\"pictures/cross.png\" alt=\"Supprimer\" title=\"Supprimer le tag\" /></a></td>"; $i++;
				}
				else{ 
					$i=1;
					echo "</tr><tr class=\"tags\"><td class=\"tags\">";
					if(in_array($key, $tags_photos)) { echo "<a href=\"index.php?tag=$key\" title=\"Voir les photos associ&eacute;es à $value\">$value</a>"; }
					else { echo $value; }
					echo "</td><td class=\"tags\"><a href=\"tags.php?id=$key\"><img src=\"pictures/cross.png\" alt=\"Supprimer\" title=\"Supprimer le tag\" /></a></td>";
					$i++;
				 }					
			}
			echo "</tr></table>";
		}
	?>
	
	<?php require_once('footer.php'); ?>
</body>
</html>