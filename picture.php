<?php
/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

require_once('fonctions.php');
require_once('abdd.php');
require_once('Date.class.php');

if(isset($_GET['picture'])){
	$action = substr($_GET['picture'], 0, 4);
	if($action != 'tags'){
		$id=substr($_GET['picture'], 5);
		$tag = "";
	}
	else{
		$tag = substr($_GET['picture'], 5);
		$id = $_GET['pid'];
	}
}
else{
 	$action = "";
 	$id = "";
 	$tag = "";
}

//initialisation des variables
$infos_photo=array();
$Erreurs=array();
$image="";
$thumbnail="";
$Err=false;
$tableau_tags=array();

if(($action == "dele") && ($id != "")){
	//on lit la bdd pour récupérer les informations sur l'image et tout afficher
	$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
	    or die("Impossible de se connecter : " . mysql_error());
	
	$db_selected = mysql_select_db($nombdd, $link);
	if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
	
	//on construit la requête pour trouve rles noms de fichiers et les supprimer
	$sql = "SELECT chemin_photo, chemin_thumbnail FROM gd_photos WHERE id='".mysql_escape_string($id)."';";
	
	$result = mysql_query($sql);
	if (!$result){
		die('Requete invalide : ' . mysql_error());
	}
	else {
		if(mysql_num_rows($result) > 0){
			$row =mysql_fetch_row($result);
			$image=$row[0];
			$thumbnail=$row[1];
		}
		else {
			$Erreurs[]="Aucune image ne correspondant à cet ID.";
			$Err=true;
		}	
	}
	
	//on supprime le fichier image et son thumbnails
	if($Err==false){
		if(!unlink("images".DIRECTORY_SEPARATOR.$image)) {
			$Erreurs[]="Problème pour la suppression de $image.";
			$Err=true;
		}
		else{
			if(!unlink("thumbnails".DIRECTORY_SEPARATOR.$thumbnail)){
				$Erreurs[]="Problème pour la suppression de $thumbnail.";
				$Err=true;
			}
		}
	}
	
	//on construit la requête pour supprimer l'enregistrement en base de données
	$sql = "DELETE FROM gd_photos WHERE id='$id';";	
	
	//on ne supprime l'enregistrement en BDD que si on a pu supprimer les fichiers
	if($Err==false){
		//on exécute la requête pour supprimer l'enregistremet puis on renvoit sur l'index
		$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		else {
			//Nous supprimons le srelations ayant pu exister entre cette photo et des tags.	
			$sql="DELETE FROM gd_liensphta WHERE id_ph='$id';";
			$result = mysql_query($sql);
			if (!$result){
				die('Requete invalide : ' . mysql_error());
			}
			
			mysql_close($link);
			header("Location: index.php");
		}
	}
}
elseif(($action == "view") && ($id != "")){
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
	
	//si pas d'erreur et donc affichage, on récupère la liste des tags associés à la photo
	$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
	    or die("Impossible de se connecter : " . mysql_error());
	
	$db_selected = mysql_select_db($nombdd, $link);
	if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
	$sql = "SELECT tags.id, tags.tag FROM gd_tags AS tags, gd_photos AS photos, gd_liensphta AS liens WHERE photos.id = liens.id_ph AND tags.id = liens.id_ta AND photos.id='".mysql_escape_string($id)."';";
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
elseif(($action=="tags") && ($id != "") && ($tag != "")){
	$link = mysql_connect($adrbdd, $usrbdd, $pwdusr)
	    or die("Impossible de se connecter : " . mysql_error());
	
	$db_selected = mysql_select_db($nombdd, $link);
	if (!$db_selected) {die ('Impossible de selectionner la base de donnees : ' . mysql_error());}
	
	//on veut juste retirer l'association d'un tag avec une image
	$sql="DELETE FROM gd_liensphta WHERE `id_ph`='$id' AND `id_ta`='$tag';";

	//on ne supprime l'enregistrement en BDD que si on a pu supprimer les fichiers
	if($Err==false){
		//on exécute la requête pour supprimer l'enregistremet puis on renvoit sur l'index
		$result = mysql_query($sql);
		if (!$result){
			die('Requete invalide : ' . mysql_error());
		}
		else {
			mysql_close($link);
			header("Location: picture.php?picture=view_$id");
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
//si on a eu une erreur on prévoit de rediriger vers l'index avec affichage des erreurs ci-dessous
	if($Err == true){
		echo"<meta http-equiv=\"refresh\" content=\"5;url=index.php\" />";
	}
?>
<title>Galerie Photos - Détail d'une image</title>
<link rel="stylesheet" href="style.css" type="text/css"></link>
</head>

<body>
	<?php
		//affichage des erreurs si on en a rencontré lors de la suppression d'un fichier. 
		if(($Err==true) && (count($Erreurs)>0)){
			echo "<h1>Gestion d'une photo</h1>";
			foreach($Erreurs as $key => $value){
				echo"<p class=\"Erreurs2\">$value</p>";
			}
		}
		
		//gestion de l'affichage d'une photo
		if(($action=="view") && $Err==false){
			echo "<h1>Photo : ".$infos_photo['titre_photo']."</h1>";
			echo "<table class=\"image\"><tr class=\"image\">";
			echo "<td colspan=\"2\"  class=\"menu_table\">Autres actions : <a href=\"index.php\"><img src=\"pictures/pictures.png\" alt=\"Index\" title=\"Retour à l'index\" /></a>
			<a href=\"upload.php\"><img src=\"pictures/picture_add.png\" alt=\"Upload\" title=\"Ajouter une autre photo\" /></a>
			<a href=\"tags.php\"><img src=\"pictures/book.png\" alt=\"Gestion tags\" title=\"Gestion des tags\" /></a></td></tr>";
			$chemin_photo="images/".$infos_photo['chemin_photo'];
			list($largeur, $hauteur) = getimagesize($chemin_photo);
			//affichage photo
			echo "<tr class=\"image\"><td  class=\"image\"><a href=\"$chemin_photo\" ><img src=\"thumbnails/".$infos_photo['chemin_thumbnail']."\" alt=\"".$infos_photo['nom']."\" /></a></td>";
			//affichage infos bdd photos à droite
			echo "<td  class=\"image\">
				<p><span class=\"titre\">Nom de l'image : </span>".$infos_photo['nom']."</p>
				<p><span class=\"titre\">Auteur de l'image : </span>".$infos_photo['auteur']."</p>
				<p><span class=\"titre\">Lieu : </span>".$infos_photo['lieu']."</p>
				<p><span class=\"titre\">Poids : </span>".byteConvert(filesize($chemin_photo))."</p>
				<p><span class=\"titre\">Taille de l'image :</span></p>
				<span class=\"size\">Hauteur : $hauteur pixels<br/></span>
				<span class=\"size\">Largeur : $largeur pixels<br/></span>
				<p><span class=\"titre\">Date d'ajout : </span>".Date::DateSQLToFrench($infos_photo['date_up'])."</p>
				<p><span class=\"titre\">Date de derni&egrave;re modification : </span>".Date::DateSQLToFrench($infos_photo['date_modify'])."</p>
				<p><span class=\"titre\">Actions : </span>
					<a href=\"picture.php?picture=dele_$id\" ><img src=\"pictures/picture_delete.png\" alt=\"Suppression Photo\" title=\"Supprimer la photo\" /></a>
					<a href=\"edit.php?id=$id\" ><img src=\"pictures/picture_edit.png\" alt=\"Edition Photo\" title=\"Editer les informations de la photo\" /></a>
				</p>
			</td>
			</tr>";
			//affichage informations EXIF
			$tableau_exif=array();
			//le @ est pour masquer certains warning lors de la lecture d'informations EXIF erronn�es 
			//$tableau_exif=@exif_read_data($chemin_photo, 0, true);
			$oldErrorLevel = error_reporting();
			error_reporting($oldErrorLevel & ~E_WARNING);
			$tableau_exif = exif_read_data($chemin_photo, 0, true);
			error_reporting($oldErrorLevel);
			
			
			//gestion si informations EXIF absentes
			if(array_key_exists('COMPUTED', $tableau_exif)){
				if(array_key_exists('ApertureFNumber', $tableau_exif['COMPUTED'])){
					if($tableau_exif['COMPUTED']['ApertureFNumber'] != ""){$ApertureFNumber = $tableau_exif['COMPUTED']['ApertureFNumber'];} else{$ApertureFNumber = "";}
				}else{$ApertureFNumber="";}
			}
			else {
				$ApertureFNumber="";
			}
			
			if(array_key_exists('IFD0', $tableau_exif)){
				if(array_key_exists('DateTime', $tableau_exif['IFD0'])){
					if($tableau_exif['IFD0']['DateTime'] != ""){
							$date1 = Date::DateExifToFrench($tableau_exif['IFD0']['DateTime']);
							$date_md="le ".$date1['jour']." &agrave; ".$date1['heure'];
					}else{$date_md = "";}
				}else{$date_md = "";}
				
				if(array_key_exists('Model', $tableau_exif['IFD0'])){
					if($tableau_exif['IFD0']['Model'] != ""){$IFD0_Model = $tableau_exif['IFD0']['Model'];} else{$IFD0_Model = "";}
				}else{$IFD0_Model = "";}
				
				if(array_key_exists('Software', $tableau_exif['IFD0'])){
					if($tableau_exif['IFD0']['Software'] != ""){$Software = $tableau_exif['IFD0']['Software'];} else{$Software = "";}
				}else{$Software = "";}
			}
			else {
				$date_md="";
				$IFD0_Model = "";
				$Software = "";
			}
			
			if(array_key_exists('EXIF', $tableau_exif)){
				
				if(array_key_exists('DateTimeOriginal', $tableau_exif['EXIF'])){
					if($tableau_exif['EXIF']['DateTimeOriginal'] != ""){
						$date2 = Date::DateExifToFrench($tableau_exif['EXIF']['DateTimeOriginal']);
						$date_pv="le ".$date2['jour']." &agrave; ".$date2['heure'];
					} else{$date_pv = "";}				
				} else{$date_pv = "";}
							
				$tab_colorspace=array('1' => "sRGB", '2' => "Adobe RGB", '65535' => "Uncalibrated");
				if(array_key_exists('ColorSpace', $tableau_exif['EXIF'])){
					if($tableau_exif['EXIF']['ColorSpace'] != ""){
						if(array_key_exists($tableau_exif['EXIF']['ColorSpace'], $tab_colorspace)){$colorspace=$tab_colorspace[$tableau_exif['EXIF']['ColorSpace']];}
						else{$colorspace="Unknow";}	
					} else{$colorspace = "Unknow";}
				}else{$colorspace = "Unknow";}	
				
				if(array_key_exists('ISOSpeedRatings', $tableau_exif['EXIF'])){
					if($tableau_exif['EXIF']['ISOSpeedRatings'] != ""){$ISOSpeedRatings = $tableau_exif['EXIF']['ISOSpeedRatings'];} else{$ISOSpeedRatings = "";}
				}else{$ISOSpeedRatings = "";}
				
				if(array_key_exists('ExposureTime', $tableau_exif['EXIF'])){
					if($tableau_exif['EXIF']['ExposureTime'] != ""){$ExposureTime = $tableau_exif['EXIF']['ExposureTime'];} else{$ExposureTime = "";}
				}else{$ExposureTime = "";}
				
				if(array_key_exists('$FocalLength = "";', $tableau_exif['EXIF'])){
					if($tableau_exif['EXIF']['FocalLength'] != ""){$FocalLength = $tableau_exif['EXIF']['FocalLength'];} else{$FocalLength = "";}
				}else{$FocalLength = "";}
			}
			else {$date_pv=""; $colorspace=""; $ISOSpeedRatings=""; $ExposureTime=""; $FocalLength="";}
			
			echo "<tr  class=\"image\">";
			echo "<td colspan=\"2\" class=\"image\">";
			echo "<h2>Informations EXIF :</h2>";
			echo "<p><span class=\"titre\">Mod&egrave;le appareil : </span>$IFD0_Model</p>";
			echo "<p><span class=\"titre\">Date de la prise de vue : </span>$date_pv</p>";
			echo "<p><span class=\"titre\">Sensibilit&eacute;e ISO : </span>$ISOSpeedRatings</p>";
			echo "<p><span class=\"titre\">Temps d'exposition : </span>$ExposureTime</p>";
			echo "<p><span class=\"titre\">Ouverture diaphragmme : </span>$ApertureFNumber</p>";
			echo "<p><span class=\"titre\">Distance focale : </span>$FocalLength</p>";
			echo "<p><span class=\"titre\">Espace colorim&eacute;trique : </span>$colorspace</p>";
			echo "<p><span class=\"titre\">Date de modification par un logiciel : </span>$date_md</p>";
			echo "<p><span class=\"titre\">Nom du logiciel : </span>$Software</p>";
			echo "</td></tr>";
			
			echo "<tr><td colspan=\"2\"><h2  class=\"mil\">Tags de l'image</h2></td></tr>";
			echo "<tr><td colspan=\"2\">";
			if(count($tableau_tags)>0){
				echo "<table><tr>";
				$i=1;
				foreach($tableau_tags as $key => $value){
					if($i<=10){ echo "<td class=\"tags\"><a href=\"index.php?tag=$key\" title=\"Voir les photos associ&eacute;es à $value\">$value</a></td><td class=\"tags\"><a href=\"picture.php?picture=tags_$key&amp;pid=$id\"><img src=\"pictures/cross.png\" alt=\"Supprimer\" title=\"Supprimer l'association\" /></a></td>"; $i++; }
					else{ 
						$i=1;
						echo "</tr><tr class=\"tags\"><td class=\"tags\"><a href=\"index.php?tag=$key\" title=\"Voir les photos associ&eacute;es à $value\">$value</a></td><td class=\"tags\"><a href=\"picture.php?picture=tags_$key&amp;pid=$id\"><img src=\"pictures/cross.png\" alt=\"Supprimer\" title=\"Supprimer l'association\" /></a></td>";
						$i++;
					 }
				}
				echo "</tr></table>";					
			}
			else {
				echo"<h2  class=\"mil\">Pas de tags associ&eacute;s &agrave; cette photo.</h2>";
			}
			echo "</td>";
			echo "</tr></table>";
		}
	
		require_once('footer.php'); ?>

</body>
</html>