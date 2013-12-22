<?php
if ($_GET['project'] != ""){$project = $_GET['project'];} else {$project = "";}
if ($_GET['lang'] != ""){$lang = $_GET['lang'];} else {$lang = "cz";}
include "db.".$project.".inc.php";
include "eden_lang_".$lang.".php";
include "functions_frontend.php";
/**
 * Second ID must be the same as the first one, just add _hidden
 * <input type="text" id="card_name" name="card_name" value="" autocomplete="off" onkeyup="ajax_showOptions(this,'getMtGCardByLetters=1&amp;project=".$_SESSION['project']."',event)">
 * <input type="hidden" id="card_name_hidden" name="card_id">
 *
 * ID MUST be identical for the page!!! Othervise you can have two IDs on the page and script don't return variable!
 */

/**
 * Dictionary
 */
if(isset($_GET['getDictionaryByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT dictionary_id, dictionary_word FROM $db_dictionary WHERE dictionary_parent_id=0 AND dictionary_allow=1 AND dictionary_word LIKE '".$letters."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	#echo "1###select ID,countryName from ajax_countries where countryName like '".$letters."%'|";
	while($inf = mysql_fetch_array($res)){
		echo $inf["dictionary_id"]."###".$inf["dictionary_word"]."|";
	}
}

/**
 * League teams
 */
if(isset($_GET['getTeamNameByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT league_team_id, league_team_name FROM $db_league_teams WHERE league_team_name LIKE '".$letters."%' AND league_team_owner_id>0 AND league_team_hibernate=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["league_team_id"]."###".stripslashes($inf["league_team_name"])." (ID ".$inf["league_team_id"].")|";
	}
}

/**
 * Admins
 */
if(isset($_GET['getPlayerNickByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_id LIKE '".$letters."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["admin_id"]."###ID ".$inf["admin_id"]." - ".stripslashes($inf["admin_nick"])."|";
	}
}

/**
 * Users
 */
if(isset($_GET['getPlayerEmailByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT admin_id, admin_nick, admin_email FROM $db_admin WHERE admin_email LIKE '".$letters."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["admin_id"]."###".$inf["admin_email"]."|";
	}
}

/**
 * MTG Cards
 */
if(isset($_GET['getMtGCardByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	//$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$like = " LIKE '".$letters."%' ";
	$like2 = "";
	if (substr_count($letters, "ae") > 0){ 
		$ligature = str_ireplace("ae", "Æ", $letters);
		$like2 = " OR mtg_card_name LIKE '".$ligature."%' ";
	}
	if (substr_count($letters, "oe") > 0){ 
		$ligature = str_ireplace("oe", "œ", $letters);
		$like2 = " OR mtg_card_name LIKE '".$ligature."%' ";
	}
	
	$res = mysql_query("SELECT mtg_card_id, mtg_card_mtg_id, mtg_card_name, mtg_card_set_code FROM "._DB_MTG_CARDS." WHERE mtg_card_name $like $like2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["mtg_card_id"]."###".$inf["mtg_card_name"]." (".$inf["mtg_card_set_code"].")|";
	}
}

/**
 * MTG Cards Hintbox
 */
if($_GET['mode'] == "mtgcard_full"){
	echo "<div class=\"eden_hintbox_result\">";
	echo "<div class=\"result\">";
	$mtg_card = new MtGShowCard($eden_cfg);
	$mtg_card->showCard($_GET['id'],"full");
	echo "<span style=\"color:#ffffff;\">".$_GET['id']."</span>";
	echo "</div>";
	echo "</div>";
}

/**
 * MTG Cards Hintbox
 */
if($_GET['mode'] == "mtgcard"){
	echo "<div class=\"eden_hintbox_result\">";
	echo "<div class=\"result\">";
	$mtg_card = new MtGShowCard($eden_cfg);
	$mtg_card->showCard($_GET['id'],"lite");
	echo "<span style=\"color:#ffffff;\">".$_GET['id']."</span>";
	echo "</div>";
	echo "</div>";
}

/**
 * MTG Add Decklist 
 */
if ($_GET['action'] == "mtg_decklist_add"){
	$decklist = new MtGDecklists($eden_cfg);
	$decklist->saveDecklistCard($_POST, 1);
	echo $decklist->showDecklist($_POST['decklist_id'], "add");
}

/**
 * MTG deelete card from decklist
 */
if ($_POST['action'] == "mtg_decklist_card_del"){
	$decklist = new MtGDecklists($eden_cfg);
	$decklist->saveDecklistCard($_POST, 2);
	echo $decklist->showDecklist($_POST['decklist_id'], "add");
}