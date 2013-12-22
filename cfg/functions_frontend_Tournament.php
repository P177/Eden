<?php

/**
* MtG Decklists
*/
class Tournament {

	/**
	* @param array Eden configuration
	*/
	public function __construct($eden_cfg) {

		$this->eden_cfg = $eden_cfg;
	}


	/**
	* Show Tournaments
	* @return string
	*/
	public function showTournaments(){
		
		$res = mysql_query("SELECT tournament_id, tournament_name, tournament_format, tournament_date, tournament_time, tournament_registration_start, tournament_buyin, tournament_prizes
		FROM "._DB_TOURNAMENTS) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		ob_start();
		include "templates/tpl.tournament_show_tournaments.php";
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}


	/**
	* Show Decklist - One
	* @param integer	Decklist ID
	* @return string
	*/
	public function showTournament($tid = false){
		
		$res = mysql_query("SELECT tournament_name, tournament_format, tournament_date, tournament_time, tournament_registration_start, tournament_buyin, tournament_prizes , tournament_description
		FROM "._DB_TOURNAMENTS." WHERE tournament_id=".(integer)$tid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		ob_start();
		include "templates/tpl.tournament_show_tournament.php";
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	/**
	* Show Najada Tournaments
	* @return string
	*/
	public function showNajadaTournaments(){
		
		$tournaments = simplexml_load_file("http://herna.najada.cz/xml/tournaments/");
		$output = "";
		
		ob_start();
		include "templates/tpl.tournament_show_tournaments_najada.php";
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	
	/**
	* Show Najada Tournaments
	* @param integer		Number of tournaments
	* @return string
	*/
	public function showNajadaTournamentsSmall($num){
		
		$tournaments = simplexml_load_file("http://herna.najada.cz/xml/tournaments/");
		$output = "";
		
		ob_start();
		include "templates/tpl.tournament_show_tournaments_najada_small.php";
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	
	/**
	* Asign right tournament format
	* @param string		format
	* @return string	format
	*/
	public function tournamentFormat($format) {
		
	    $formattedFormat = "Nedefinovaný";
	    switch($format) {
	        case "doubledraft":
	            $formattedFormat = "Double Draft";
	            break;
	        case "draft":
	            $formattedFormat = "Draft";
	            break;
	        case "extended":
	            $formattedFormat = "Extended";
	            break;
	        case "highlander":
	            $formattedFormat = "Highlander";
	            break;
	        case "legacy":
	            $formattedFormat = "Legacy";
	            break;
	        case "modern":
	            $formattedFormat = "Modern";
	            break;
	        case "standard":
	            $formattedFormat = "Standard";
	            break;
	        case "vintage":
	            $formattedFormat = "Vintage";
	            break;
	    }
	    return $formattedFormat;
	}
}
