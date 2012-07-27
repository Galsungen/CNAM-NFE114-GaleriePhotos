<?php

/*
 * 	@author	Galsungen - http://blog.galsungen.net
 * 	Année 2010-11
 *	CNAM - NFE114 - Système d'information web
 */

Class Date {
	
	//variables
	private $annee;
	private $mois;
	private $jours;
	private $heures;
	private $minutes;
	private $secondes;
	
	//constructeur
	public function Date(){
		$date = explode(" ", date('Y-m-d H:i:s'));
		$date_1 = explode("-", $date[0]);
		$this->annee = $date_1[0];
		$this->mois = $date_1[1];
		$this->jours = $date_1[2];
		$date_1 = explode(":", $date[1]);
		$this->heures = $date_1[0];
		$this->minutes = $date_1[1];
		$this->secondes = $date_1[2];
		
		return $this;
	}
	
	//Obtenir une date SQL
	public function GetDateSQL(){
		return $this->annee."-".$this->mois."-".$this->jours;
	}
	
	//Obtenir une date full pour id
	public function GetDateId(){
		return $this->annee.$this->mois.$this->jours.$this->heures.$this->minutes.$this->secondes;
	}
	
	//methode date SQL américaine vers affichage Français
	public static function DateSQLToFrench($date){
		$date=explode("-",$date);
		$date_s=$date[2]."/".$date[1]."/".$date[0];
		return $date_s;
	}

	//Méthode Date Française vers SQL/Américaine
	public static function DateFrenchToSQL($date){
		$date=explode("/",$date);
		$date_s=$date[2]."-".$date[1]."-".$date[0];
		return $date_s;
	}
	
	//méthode pour passer de l'affichage d'une date en EXIF à une date plus française
	public static function DateExifToFrench($date){
		$date=explode(" ",$date);
		$date_s=array('heure' => $date[1], 'jour' => "");
		$date_temp=explode(":", $date[0]);
		$date_s['jour']=$date_temp[2]."/".$date_temp[1]."/".$date_temp[0];
		return $date_s;
	}
	
}

?>