<?php
/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

require_once('fonctions.php');
require_once('abdd.php');
require_once('Date.class.php');

if(isset($_GET['tag'])){$tag = $_GET['tag'];} else{$tag = "";}
$Erreur="";
$nom_tag="";

//on lit la bdd pour r�cup�rer les images
$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
    or die("Impossible de se connecter : " . mysql_error());

$db_selected = mysql_select_db($nombdd, $link);
if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}

//on construit la requ�te
if($tag == ""){
	$sql = "SELECT id, nom, titre_photo, auteur, lieu, date_up, date_modify FROM gd_photos;";
}
else{
	$sql = "SELECT id, nom, titre_photo, auteur, lieu, date_up, date_modify FROM gd_photos 
			WHERE id IN (SELECT id_ph FROM gd_liensphta WHERE id_ta='".mysql_escape_string($tag)."');";
}

//on exécute la requête
$result = mysql_query($sql);
if (!$result){
	die('Requete invalide : ' . mysql_error());
}
else {
	if(mysql_num_rows($result) > 0){
		//on traite le retour et on remplit un tableau
		while ($row =mysql_fetch_assoc($result)){
	        $tableau_photos[$row['id']]=$row;
		}
		mysql_close($link);
	}
	else {
		$Erreur="Pas de photos à afficher.";
	}
}

//récupération du nom du tag si il y en a un
if($tag != ""){
	//on va lire la bdd pour récupérer son nom
	$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
	    or die("Impossible de se connecter : " . mysql_error());
	
	$db_selected = mysql_select_db($nombdd, $link);
	if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
	
	//on construit la requ�te
	$sql = "SELECT tag FROM gd_tags WHERE id='".mysql_escape_string($tag)."';";

	$result = mysql_query($sql);
	if (!$result){
		die('Requete invalide : ' . mysql_error());
	}
	else {
		if(mysql_num_rows($result) == 1){
			//on traite le retour et on remplit un tableau
			while ($row =mysql_fetch_row($result)){
		        $nom_tag=$row[0];
			}
			mysql_close($link);
		}
	}
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Galerie Photos</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>
	<h1>Galerie Photos</h1>
	
	<table class="index">
	<?php 
		if($nom_tag!=""){
			echo "<tr class=\"index\"><td colspan=\"7\" class=\"index\">Photographies associées au tag $nom_tag.</td></tr> ";
		}
	?>
		<tr  class="index">
		<td colspan="7" class="menu_table">Actions : <a href="upload.php"><img src="pictures/picture_add.png" alt="Upload" title="Ajouter une image" /></a>
			<a href="tags.php"><img src="pictures/book.png" alt="Gestion tags" title="Gestion des tags" /></a>
			<a href="index.php"><img src="pictures/pictures.png" alt="Index" title="Recharger cette page sans option." /></a>
			</td>
		</tr>
		<tr class="titre">
			<td  class="index">Nom</td>
			<td  class="index">Titre</td>
			<td  class="index">Auteur</td>
			<td  class="index">Lieu</td>
			<td  class="index">Date d'ajout</td>
			<td  class="index">Derni&egrave;re modification</td>
			<td  class="index">Actions</td>
		</tr>
	<?php
		if($Erreur==""){
			foreach($tableau_photos as $key => $value){
				echo "
					<tr  class=\"index\">
						<td  class=\"index\"><a href=\"picture.php?picture=view_".$value['id']."\">".$value['nom']."</a></td>
						<td  class=\"index\">".$value['titre_photo']."</td>
						<td  class=\"index\">".$value['auteur']."</td>
						<td  class=\"index\">".$value['lieu']."</td>
						<td  class=\"index\">".Date::DateSQLToFrench($value['date_up'])."</td>
						<td  class=\"index\">".Date::DateSQLToFrench($value['date_modify'])."</td>
						<td  class=\"index\">
							<a href=\"picture.php?picture=view_".$value['id']."\" ><img src=\"pictures/picture.png\" alt=\"Visualiser Photo\" title=\"Visualiser la photo\" /></a>
							<a href=\"picture.php?picture=dele_".$value['id']."\" ><img src=\"pictures/picture_delete.png\" alt=\"Suppression Photo\" title=\"Supprimer la photo\" /></a>
							<a href=\"edit.php?id=".$value['id']."\" ><img src=\"pictures/picture_edit.png\" alt=\"Edition Photo\" title=\"Editer la photo\" /></a>
						</td>
					</tr>
				";
			}
		}
		else{
			echo "<tr  class=\"index\"><td colspan=\"7\" class=\"index\"><p class=\"Erreurs1\">$Erreur</p></td></tr>";
		}
	?>
	</table>
	<?php require_once('footer.php'); ?>
</body>
</html>