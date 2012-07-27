<?php

/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

// simple fonction pour afficher des variables
function debug($var)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

//fonction pour afficher une taille humainement et non en octets
function byteConvert($bytes)
{
    $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($bytes)/log(1024));

    return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
} 

/*
//fonction pour transformer une date SQL, Américaine en un affichage plus Français
function DateFrench($date_entry){
	$date=explode("-",$date_entry);
	$date_out=$date[2]."/".$date[1]."/".$date[0];
	return($date_out);
}

//fonction pour passer de l'affichage d'une date en EXIF à une date plus française
function DateExifFrench($date_entry){
	$date_heure=explode(" ",$date_entry);
	$date=array('heure' => $date_heure[1], 'jour' => "");
	$date_temp=explode(":", $date_heure[0]);
	$date['jour']=$date_temp[2]."/".$date_temp[1]."/".$date_temp[0];
	return($date);
}

//fonction pour transformer date French vers SQL
function DateSQL($date_entry){
	$date=explode("/",$date_entry);
	$date_out=$date[2]."-".$date[1]."-".$date[0];
	return($date_out);
}
*/
?>