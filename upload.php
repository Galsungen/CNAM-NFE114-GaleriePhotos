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
require_once('Date.class.php');

//d�claration de variabes
$repertoire_images="images";
$repertoire_thumbnails="thumbnails";
$Erreurs = array();
$extensions = array('jpg', 'jpeg', 'JPG', 'JPEG', 'gif', 'GIF', 'TIFF', 'tiff', 'bmp', 'BMP', 'png', 'PNG');
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if(isset($_POST['Soumettre'])){$Soumettre = $_POST['Soumettre'];} else{$Soumettre = "";}
if(isset($_POST['lieu'])){$lieu = $_POST['lieu'];} else{$lieu = "";}
if(isset($_POST['nom_auteur'])){$nom_auteur = $_POST['nom_auteur'];} else{$nom_auteur = "";}
if(isset($_POST['titre_photo'])){$titre_photo = $_POST['titre_photo'];} else{$titre_photo = "";}


if($Soumettre != ""){
	$fichier_image = $_FILES['userfile']['name'];
	$fichier_type = explode('/', $_FILES['userfile']['type']);
	$fichier_extension = strrchr($fichier_image, ".");
	$fichier_tmp = $_FILES['userfile']['tmp_name'];
	
	//utilisation de l'objet Date pour gérer les dates
	$date = new Date();
	$date_jour = $date->GetDateSQL();
	$var_date = $date->GetDateId();
	
	$nom_final_fichier = $var_date."_".$fichier_image;
	$nom_thumbnails = $var_date."_tb_".$fichier_image;
	$ErrTest=false;
	
	//si un des trois champs est vide, on remplit les champs connus mais on exécute pas et on informe
	if($lieu == "") {
		$Erreurs[]="Le champ \"Lieu\" est vide. Merci de le renseigner.";
		$ErrTest=true;
	}
	if($nom_auteur == "") {
		$Erreurs[]="Le champ \"Auteur\" est vide. Merci de le renseigner.";
		$ErrTest=true;
	}
	if($titre_photo == "") {
		$Erreurs[]="Le champ \"Titre de la photo\" est vide. Merci de le renseigner.";
		$ErrTest=true;
	}
	
	//on test le fichier et si il est au dessus de la limite de 20 Mo on le refuse avec affichage
	if ($_FILES['nom_du_fichier']['error']) {    
		switch ($_FILES['nom_du_fichier']['error']){    
			case 1: // UPLOAD_ERR_INI_SIZE    
				echo"Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";    
				$Erreurs[]="Le fichier d&eacute;passe la limite autoris&eacute;e par le serveur (fichier php.ini).";
				$ErrTest=true;
				break;    
			case 2: // UPLOAD_ERR_FORM_SIZE    
				echo "Le fichier dépasse la limite autorisée dans le formulaire HTML";
				$Erreurs[]="Le fichier d&eacute;passe la limite autoris&eacute;e dans le formulaire HTML.";
				$ErrTest=true;
				break;    
			case 3: // UPLOAD_ERR_PARTIAL    
				echo "L'envoi du fichier a été interrompu pendant le transfert.";
				$Erreurs[]="L'envoi du fichier a &eacute;t&eacute; interrompu pendant le transfert.";
				$ErrTest=true;    
				break;    
			case 4: // UPLOAD_ERR_NO_FILE    
				echo "Le fichier que vous avez envoyé a une taille nulle";
				$Erreurs[]="Le fichier que vous avez envoy&eacute; a une taille nulle.";
				$ErrTest=true;
				break;    
		}    
	}

	if ($ErrTest == false){
		//on vérifie certains propriétés pour que ce soit bien une image
		if($fichier_type[0] == 'image'){
			if(in_array($fichier_type[1], $extensions)){
				//si on a passé les vérifications on déplace le fichier image
				//on concatene une chaine avec date, heure et minute en début de nom de fichier pour éviter des noms en double
				$destination = dirname(__FILE__).DIRECTORY_SEPARATOR.$repertoire_images.DIRECTORY_SEPARATOR.$nom_final_fichier;
					if(move_uploaded_file($fichier_tmp, $destination)){
						//copie effectuée on calcul dimensions pour thumbnail
						$image_copiee = $repertoire_images.DIRECTORY_SEPARATOR.$nom_final_fichier;
						//récupération extension pour fonction de redimensionnement
						$extension_image = strrchr($image_copiee, ".");
						if(($extension_image == ".jpg") || ($extension_image == ".jpeg") || ($extension_image == ".JPG") || ($extension_image == ".JPEG"))
						{ $ext='jpeg'; }
						elseif(($extension_image == ".gif") || ($extension_image == ".GIF"))
						{ $ext='gif'; }
						elseif(($extension_image == ".png") || ($extension_image == ".PNG"))
						{ $ext='png'; }
						elseif(($extension_image == ".tiff") || ($extension_image == ".TIFF"))
						{ $ext='tiff'; }
						elseif(($extension_image == ".bmp") || ($extension_image == ".BMP"))
						{ $ext='bmp'; }
	
						//on calcul les proportions pour que le thumbnail les conserve avec la réduction.
						$hauteur = 300;
						$largeur = 300;
						list($largeur_image, $hauteur_image) = getimagesize($image_copiee);
						$ratio_image = $largeur_image/$hauteur_image;
						
						if(($largeur_image != "") || ($hauteur_image != "")){
							if ($largeur/$hauteur > $ratio_image) {
   								$largeur = $hauteur*$ratio_image;
							}
							else {
								$hauteur = $largeur/$ratio_image;
							}
						}
						else {
							$ErrTest=true;
							$Erreurs[]="Probl&egrave;me pour les dimensions de $nom_final_fichier.";
						}
						
						//on crée le thumbnail
						if($ErrTest==false){
							$destination_thumbnail = $repertoire_thumbnails.DIRECTORY_SEPARATOR.$nom_thumbnails;
							$image_destination = imagecreatetruecolor($largeur,$hauteur);
							$fonction = "imagecreatefrom".$ext;
							$thumbnail = $fonction($image_copiee);
							if (imagecopyresampled($image_destination, $thumbnail, 0, 0, 0, 0, $largeur, $hauteur, $largeur_image, $hauteur_image)){
								$fonction2="image".$ext;
								if ($fonction2($image_destination, $destination_thumbnail)){
									imagedestroy($thumbnail);
									imagedestroy($image_destination);
								}
								else {
									$ErrTest=true;
									$Erreurs[]="Probl&egrave;me lors de la cr&eacute;ation du thumbnail.";
								}
							}
							else {
								$ErrTest=true;
								$Erreurs[]="Probl&egrave;me lors de la cr&eacute;ation du thumbnail.";
							}
						}
						else {
							$ErrTest=true;
							$Erreurs[] = "Probl&egrave;me lors de l'upload de $fichier_image.";
						}
		
						// Si tout s'est bien passé, on insére l'enregistrement en bdd
						if ($ErrTest == false ) {
							//on ouvre l'accès à la base de données
							$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
						    or die("Impossible de se connecter : " . mysql_error());
		
							$db_selected = mysql_select_db($nombdd, $link);
							if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
	
							//on construit la requête
							$sql = "INSERT INTO `gd_photos` (`chemin_photo`, `chemin_thumbnail`, `nom`, `titre_photo`, `auteur`, `lieu`, `date_up`,  `date_modify`, `ip_add`, `ip_modify`) 
								VALUES ('".mysql_escape_string($nom_final_fichier)."', '".mysql_escape_string($nom_thumbnails)."', '".mysql_escape_string($fichier_image)."', '".mysql_escape_string($titre_photo)."',
								 '".mysql_escape_string($nom_auteur)."', '".mysql_escape_string($lieu)."', '".mysql_escape_string($date_jour)."', '".mysql_escape_string($date_jour)."',
								  '".mysql_escape_string($ip_visiteur)."', '".mysql_escape_string($ip_visiteur)."');";;
							
							//on exécute la requête
							$result = mysql_query($sql);
							if (!$result){
								die('Requete invalide : ' . mysql_error());
							}
							else {
								mysql_close($link);
								$good=urlencode($fichier_image);
								header("Location: success.php?message=$good");
							}
						}
				}
				else {
					$Erreurs[] = "l'extension de $fichier_image n'est pas accept&eacute;e.";
				}
			}
			else {
				$Erreurs[] = "$fichier_image n'est pas un fichier image accept&eacute;.";
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Galerie Photos - Ajout d'une image</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>
	<h1>Ajout d'une image</h1>
	<div class="upload">
		<p>Autres actions : <a href="index.php"><img src="pictures/pictures.png" alt="Index" title="Retour à l'index" /></a>
		<a href="tags.php"><img src="pictures/book.png" alt="Gestion tags" title="Gestion des tags" /></a></p>
	
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
		<p><input type="file" name="userfile" /></p>
		<p>Titre de la photo : <input type="text" name="titre_photo" maxlength="100" value="<?php echo $titre_photo; ?>" /></p>
		<p>Auteur : <input type="text" name="nom_auteur" maxlength="50" value="<?php echo $nom_auteur; ?>" /></p>
		<p>Lieu : <input type="text" name="lieu" maxlength="100" value="<?php echo $lieu; ?>" /></p>
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
	?>
	
	<?php require_once('footer.php'); ?>
</body>
</html>