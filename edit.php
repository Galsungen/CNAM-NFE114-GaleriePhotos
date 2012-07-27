<?php

/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

	require_once('fonctions.php');
	require_once('abdd.php');
	require_once('Date.class.php');
	
	if(isset($_GET['id'])){$id = $_GET['id'];} else{if(isset($_POST['id'])){$id = $_POST['id'];} else{$id = "";}}
	
	if(isset($_POST['Soumettre'])){$Soumettre = $_POST['Soumettre'];} else{$Soumettre = "";}
	$Erreurs=array();
	$Err=false;
	$good="";
	//debug($_POST);
	
	if($id != ""){
		//on récupère les tags non associés
		$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
		    or die("Impossible de se connecter : " . mysql_error());
		
		$db_selected = mysql_select_db($nombdd, $link);
		if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
		
		//construction de la requête
		$sql="SELECT t1.id, t1.tag FROM gd_tags AS t1 WHERE t1.id NOT IN (SELECT tags.id FROM gd_tags AS tags, gd_photos AS photos, gd_liensphta AS liens WHERE photos.id = liens.id_ph AND tags.id = liens.id_ta AND photos.id='".mysql_escape_string($id)."')";
			$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		else {
			//on traite le retour et on remplit un tableau
			if(mysql_num_rows($result) > 0){
				while ($row =mysql_fetch_assoc($result)){
			        $tableau_tags[$row['id']]=$row['tag'];
				}
			}
			mysql_close($link);
		}
	}
	
	if(($id != "") && ($Soumettre == "")){
		$infos_photo=array();
		$Erreurs=array();
		$thumbnail="";
		
		//on lit la bdd pour récupérer les informations sur l'image et tout afficher
		$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
		    or die("Impossible de se connecter : " . mysql_error());
		
		$db_selected = mysql_select_db($nombdd, $link);
		if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
		
		//on construit la requ�te
		$sql = "SELECT * FROM gd_photos WHERE id='".mysql_escape_string($id)."';";
		
		//on exécute la requête
		$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		else {
			if(mysql_num_rows($result) > 0){
				//on traite le retour et on remplit un tableau
				$infos_photo=mysql_fetch_assoc($result);
				mysql_close($link);
			}
			else {
				$Erreurs[]="Aucune image ne correspondant à cet ID.";
				$Err=true;
			}		
		}
		
		if($Err == false){
			$titre_photo = $infos_photo['titre_photo'];
			$chemin_thumbnail = "thumbnails/".$infos_photo['chemin_thumbnail'];
			$nom = $infos_photo['nom'];
			$auteur = $infos_photo['auteur'];
			$lieu = $infos_photo['lieu'];
			$date_up = $infos_photo['date_up'];
			$date_modify = $infos_photo['date_modify'];	
		}

	}
	elseif($Soumettre != ""){
		//on vérifie que les champs ne sont pas vides
		if(isset($_POST['id'])){$id = $_POST['id'];} else{$id = ""; $Erreur[]="Un champ est vide.";}
		if(isset($_POST['titre_photo'])){$titre_photo = $_POST['titre_photo'];} else{$titre_photo = ""; $Erreur[]="Champ titre photo vide.";}
		if(isset($_POST['nom_auteur'])){$auteur = $_POST['nom_auteur'];} else{$auteur = ""; $Erreur[]="Champ auteur vide.";}
		if(isset($_POST['lieu'])){$lieu = $_POST['lieu'];} else{$lieu = ""; $Erreur[]="Champ lieu vide.";}
		if(isset($_POST['date_up'])){$date_up = $_POST['date_up'];} else{$date_up = ""; $Erreur[]="Un champ est vide.";}
		if(isset($_POST['chemin_thumbnail'])){$chemin_thumbnail = $_POST['chemin_thumbnail'];} else{$chemin_thumbnail = ""; $Erreur[]="Un champ est vide.";}
		if(isset($_POST['nom'])){$nom = $_POST['nom'];} else{$nom = ""; $Erreur[]="Un champ est vide.";}
		$date = new Date();
		$date_modify = $date->GetDateSQL();
		
		//on récupère les informations modifiables en base pour vérifier qu'elles ont changées
		$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
		    or die("Impossible de se connecter : " . mysql_error());
		
		$db_selected = mysql_select_db($nombdd, $link);
		if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
		
		//on parcours _POST pour repérer si on a des tag de cochés
		$tags=array();
		foreach($_POST as $key => $value){
			if(isset($_POST[$key])){
				if($value == "on"){
					$tags[]=$key;
				}
			}
		}
				
		//on construit la requ�te
		$sql = "SELECT * FROM gd_photos WHERE id='".mysql_escape_string($id)."';";
		
		//on exécute la requête
		$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		else {
			//on traite le retour et on remplit un tableau
			$infos_photo=mysql_fetch_assoc($result);
		}

		//vérification des champs
		if(($infos_photo['auteur'] == $auteur) && ($infos_photo['titre_photo'] == $titre_photo) && ($infos_photo['lieu'] == $lieu) && (count($tags) == 0)){
			$Erreurs[]="Aucun des champs n'a été modifié.";
		}
		else{
			if(count($Erreurs) == 0){
				//création requête d'update
				$sql = "UPDATE gd_photos SET titre_photo='".mysql_escape_string($titre_photo)."', auteur='".mysql_escape_string($auteur)."', lieu='".mysql_escape_string($lieu)."' WHERE id='".mysql_escape_string($id)."';";
				$result = mysql_query($sql);
				if (!$result){
					die('Requete invalide : ' . mysql_error());
				}
				else {
					if(count($tags)>0){
						foreach($tags as $key => $value){
							$sql="INSERT INTO `gd_liensphta` (id_ta, id_ph) VALUES ('".mysql_escape_string($value)."', '".mysql_escape_string($id)."');";

							$result = mysql_query($sql);
							if (!$result){
								die('Requete invalide : ' . mysql_error());
							}
						}
					}
					//on renseigne le message de bon upload qui déclenchera le compte à rebourd vers view
					$good="Mise &agrave des informations r&eacute;alis&eacute;e avec succ&egrave;s.";
				}
			}
		}
	}
	else{
		header("Location: index.php");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php 
	if($Err == true){echo "<meta http-equiv=\"refresh\" content=\"5;url=index.php\" />";}
	if($good != ""){echo "<meta http-equiv=\"refresh\" content=\"5;url=picture.php?picture=view_$id\" />";};
?>
<title>Galerie Photos - Edition d'une image</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>

	<h1>Edition <?php if($nom == ""){echo "d'une photo";} else{echo "de ".$nom;} ?></h1>

	<?php 
		if($good != ""){
			echo"<p class='good'>$good</p>";
			echo"<p class='good'>Vous serez redirig&eacute; dans cinq secondes.</p>";
		}
	
		if(count($Erreurs) > 0){
		//affichage des erreurs si on en a rencontré lors de la suppression d'un fichier. 
			echo "<h2 class=\"mil\">Probl&egrave;me &agrave; l'édition</h2>";
			foreach($Erreurs as $key => $value){
				echo"<p class=\"Erreurs2\">$value</p>";
			}
		}
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	<table class="edit">
	<tr>
		<td colspan="2" class="menu_table"><span class="titre">Autres actions : </span>
		<?php echo "<a href=\"picture.php?picture=view_$id\"><img src=\"pictures/picture.png\" alt=\"Voir l'image\"  title=\"Voir la photo\" /></a> 
			<a href=\"index.php\"><img src=\"pictures/pictures.png\" alt=\"Index\" title=\"Retour à l'index\" /></a>
			<a href=\"upload.php\"><img src=\"pictures/picture_add.png\" alt=\"Upload\" title=\"Ajouter une autre photo\" /></a>
			<a href=\"picture.php?picture=dele_$id\" ><img src=\"pictures/picture_delete.png\" alt=\"Suppression Photo\" title=\"Supprimer la photo\" /></a>
			<a href=\"tags.php\"><img src=\"pictures/book.png\" alt=\"Gestion tags\" title=\"Gestion des tags\" /></a>
			";
		?>
		</td>
	</tr>
	<tr>
		<td class="photo_edit"><img src="<?php echo $chemin_thumbnail; ?>" alt="<?php echo $nom; ?>" /></td>
		<td class="form_edit">	
				<p><input type="hidden" name="id" value="<?php echo $id; ?>" /></p>
				<p><input type="hidden" name="chemin_thumbnail" value="<?php echo $chemin_thumbnail; ?>" /></p>
				<p><input type="hidden" name="nom" value="<?php echo $nom; ?>" /></p>
				<p class="menu_table">Titre de la photo : <input type="text" name="titre_photo" maxlength="100" value="<?php echo $titre_photo; ?>" /></p>
				<p class="menu_table">Auteur : <input type="text" name="nom_auteur" maxlength="50" value="<?php echo $auteur; ?>" /></p>
				<p class="menu_table">Lieu : <input type="text" name="lieu" maxlength="100" value="<?php echo $lieu; ?>" /></p>
				<p>Date d'ajout : <?php echo Date::DateSQLToFrench($date_up); ?><input type="hidden" name="date_up" value="<?php echo $date_up; ?>" /></p>
				<p>Derni&egrave;re date de modification : <?php echo Date::DateSQLToFrench($date_modify); ?></p>
				<p class="menu_table"><input type="submit" value="Soumettre" name="Soumettre" /></p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php 
				if($Err == false){
					if(count($tableau_tags)>0){
						echo "<h2 class=\"mil\">Tags non-associ&eacute;s &agrave; l'image</h2>";
						echo "<table><tr>";
						$i=1;
						foreach($tableau_tags as $key => $value){
							if($i<=5){ 
								echo "<td class=\"tags\"><input type=\"checkbox\" name=\"$key\" /></td><td class=\"tags\">$value</td>";
								$i++; }
							else{ 
								$i=1;
								echo "</tr><tr class=\"tags\"><td class=\"tags\"><input type=\"checkbox\" name=\"$key\" /></td><td class=\"tags\">$value</td>";
								$i++;
							 }
						}
						echo "</tr></table>";					
					}
					else {
						echo"<h2 class=\"mil\">Pas de tags non-associ&eacute;s à cette photo.</h2>";
					}
				}
			?>
		</td>
	</tr>
	</table>
	</form>
		<?php require_once('footer.php'); ?>
</body>
</html>