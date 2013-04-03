<?php


/*
Proto aby vsechno jelo jak melo musi byt:
 - Zde - 
 Spravne nastavena cesta k eden_sec.php
 - Ajax JS - 
 soubory Ajax.js a ajax-dynamic-list.js zkopirovany do adresare na ktery ukazujeme z hlavicky stranky
 v souboru ajax-dynamic-list.js spravne nastavena cesta na tento soubor
 v CSS definici nastaveny 
	#eden_ajax_listOfOptions
	#eden_ajax_listOfOptions div
	#eden_ajax_listOfOptions .eden_optionDiv
	#eden_ajax_listOfOptions .eden_optionDivSelected
	#eden_ajax_listOfOptions_iframe
*/

if ($_GET['project'] != ""){$project = $_GET['project'];} else {$project = "";}
include_once "./cfg/db.".$project.".inc.php";
mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
mysql_select_db($eden_cfg['db_name']);
//Nastaveni prevodu z Databaze
if ($eden_cfg['db_encode_allow'] = "1"){mysql_query($eden_cfg['db_encode']);}

if(isset($_GET['getDictionaryByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT dictionary_id, dictionary_word FROM $db_dictionary WHERE dictionary_parent_id=0 AND dictionary_allow=1 AND dictionary_word LIKE '".mysql_real_escape_string($letters)."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	#echo "1###select ID,countryName from ajax_countries where countryName like '".$letters."%'|";
	while($inf = mysql_fetch_array($res)){
		echo $inf["dictionary_id"]."###".$inf["dictionary_word"]."|";
	}	
}
if(isset($_GET['getTeamNameByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT league_team_id, league_team_name FROM $db_league_teams WHERE league_team_name LIKE '".mysql_real_escape_string($letters)."%' AND league_team_owner_id > 0 AND league_team_hibernate=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["league_team_id"]."###".stripslashes($inf["league_team_name"])." (ID ".$inf["league_team_id"].")|";
	}
}
if(isset($_GET['getPlayerNickByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_id LIKE '".mysql_real_escape_string($letters)."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["admin_id"]."###ID ".$inf["admin_id"]." - ".stripslashes($inf["admin_nick"])."|";
	}
}
if(isset($_GET['getPlayerEmailByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT admin_id, admin_nick, admin_email FROM $db_admin WHERE admin_email LIKE '".mysql_real_escape_string($letters)."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["admin_id"]."###".$inf["admin_email"]."|";
	}
}
if(isset($_GET['getPlayerGuidByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters); //AND admin_guid_league_guid_id=".(float)$_GET['lid']
	$res = mysql_query("SELECT aid, admin_guid_guid FROM $db_admin_guids WHERE admin_guid_guid LIKE '".mysql_real_escape_string($letters)."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["aid"]."###".stripslashes($inf["admin_guid_guid"])."|";
	}
}
if(isset($_GET['getAllowedPlayerGuidByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT league_season_round_allowed_player_player_id, league_season_round_allowed_player_guid FROM $db_league_seasons_round_allowed_players WHERE league_season_round_allowed_player_guid LIKE '".mysql_real_escape_string($letters)."%' AND league_season_round_allowed_player_season_round_id=".(float)$_GET['rid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["league_season_round_allowed_player_player_id"]."###".stripslashes($inf["league_season_round_allowed_player_guid"])." (PID ".$inf["league_season_round_allowed_player_player_id"].")|";
	}
}