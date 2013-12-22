<?php
/***********************************************************************************************************
*
*	This file contains functions used in EDEN and in the FrontEnd applicatio
*
***********************************************************************************************************/



/***********************************************************************************************************
*
*		ALPHABETH
*
*		Slouzi k vypisu abecedniho seznamu a prirazeni odkazu pro dane znaky
*		Priklad:
*		Alphabeth('index.php?action=users&amp;lang='.$_GET['lang'].'&amp;filter='.$_GET['filter'].'&sa=form&ul_letter=','');
*
***********************************************************************************************************/
function Alphabeth($link, $order, $other = 1, $all = 1){
	
	$alphabeth = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9","Other","All");
	$num = count($alphabeth);
	if ($other == 0) {
		$num = $num - 1;
	}
	if ($all == 0) {
		$num = $num - 1;
	}
	for($i=0;$i<$num;$i++){
		echo "&nbsp;&nbsp;<a href=\"".$link.$alphabeth[$i]."\" target=\"_self\">".$alphabeth[$i]."</a>".$order;
	}
}
/***********************************************************************************************************
*
*		ALPHABETH SELECT
*
*		Slouzi k prevodu pismene na regulerni vyraz pro MySQL
*
***********************************************************************************************************/
function AlphabethSelect($letter, $column = ""){
	
	if ($column != "") {
		$column = mysql_real_escape_string($column);
		$like =  "$column LIKE ";
	} else {
		$like = "";
	}
	
	if ($letter == "A"){return "'a%' OR $like 'á%' OR $like 'Æ%'";}
	if ($letter == "B"){return "'b%'";}
	if ($letter == "C"){return "'c%' OR $like 'c%'";}
	if ($letter == "D"){return "'d%' OR $like 'd%'";}
	if ($letter == "E"){return "'e%' OR $like 'é%' OR $like 'ě%'";}
	if ($letter == "F"){return "'f%'";}
	if ($letter == "G"){return "'g%'";}
	if ($letter == "H"){return "'h%'";}
	if ($letter == "I"){return "'i%' OR $like 'í%'";}
	if ($letter == "J"){return "'j%'";}
	if ($letter == "K"){return "'k%'";}
	if ($letter == "L"){return "'l%' OR $like 'l%'";}
	if ($letter == "M"){return "'m%'";}
	if ($letter == "N"){return "'n%' OR $like 'ÿ%'";}
	if ($letter == "O"){return "'o%' OR $like 'ó%'";}
	if ($letter == "P"){return "'p%'";}
	if ($letter == "Q"){return "'q%'";}
	if ($letter == "R"){return "'r%' OR $like 'ř%'";}
	if ($letter == "S"){return "'s%' OR $like 'š%'";}
	if ($letter == "T"){return "'t%' OR $like 'ť%'";}
	if ($letter == "U"){return "'u%' OR $like 'ú%' OR $like 'ů%'";}
	if ($letter == "V"){return "'v%'";}
	if ($letter == "W"){return "'w%'";}
	if ($letter == "X"){return "'x%'";}
	if ($letter == "Y"){return "'y%' OR $like 'ý%'";}
	if ($letter == "Z"){return "'z%' OR $like 'ž%'";}
	if ($letter == "0"){return "'0%'";}
	if ($letter == "1"){return "'1%'";}
	if ($letter == "2"){return "'2%'";}
	if ($letter == "3"){return "'3%'";}
	if ($letter == "4"){return "'4%'";}
	if ($letter == "5"){return "'5%'";}
	if ($letter == "6"){return "'6%'";}
	if ($letter == "7"){return "'7%'";}
	if ($letter == "8"){return "'8%'";}
	if ($letter == "9"){return "'9%'";}
	if ($letter == "Other"){return "'^[[:punct:][:cntrl:]]+'";}
	if ($letter == "All"){}
}
/***********************************************************************************************************
*
*		ARRAY GET
*
*		Initialize Variables GET, POST, SESSION
*
***********************************************************************************************************/
if ( !function_exists('aGet') ){
	function aGet($array, $key, $default = NULL){
	    return isset($array[$key]) ? $array[$key] : $default;
	}
}

/***********************************************************************************************************
*
*		ClanAwardsPlaceExt - Nachazi se i v functions.php
*
*		Zobrazeni spravne koncovky u cislic
*
* 		$place		=	umisteni
*
***********************************************************************************************************/
function ClanAwardsPlaceExt($place){
	switch ($place){
		case "1":
			return _CLAN_AWARDS_PLACE_EXT_1;
		break;
		case "2":
			return _CLAN_AWARDS_PLACE_EXT_2;
		break;
		case "3":
			return _CLAN_AWARDS_PLACE_EXT_3;
		break;
		default:
			return _CLAN_AWARDS_PLACE_EXT_4;
	}
}
/***********************************************************************************************************
*
*		 ODSTRANENI PREBYTECNYCH ZNAKU
*
***********************************************************************************************************/
function CleanAdminUsername($admin_username){
	$admin_username = strtoupper($admin_username);
	$replacement = "_";
	$pattern = "/ /";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$replacement = "";
	$pattern = "/]/";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = "";
	$pattern = "/\)/";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = "";
	$pattern = "/[\\\]/e";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = "";
	$pattern = "/[ěščřžýáíéúůňóťď`´~?|@#$%^}{&\[*\(=;:'\",.!+<>\/§]/i";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = "_";
	$pattern = "/_{2,}/";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$replacement = "";
	$pattern = "/^_/";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$admin_username = substr($admin_username,0,30);
	return $admin_username;
}
/***********************************************************************************************************
*
*		 VRATI PREPARSOVANY LINK
*
***********************************************************************************************************/
function ConvertBracketLinks($text, $ver = 1){
	switch ($ver){
		case 1:
			$link_v = "/\[(http:\/\/|https:\/\/|ftp:\/\/|mailto:)([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+)\]/i";
			$link_n = "<a href=\"\\1\\2\" target=\"_blank\">\\2</a>";
			return preg_replace($link_v, $link_n, $text);
		break;
		case 2:
			$link_v2 = "/\[2(http:\/\/|https:\/\/|ftp:\/\/|mailto:)([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+)\]\[2(.+)\]/i";
			$link_n2 = "<a href=\"\\1\\2\" target=\"_blank\">\\3</a>";
			return preg_replace($link_v2, $link_n2, $text);
		break;
		case 3:
			$link_v3 = "/\[3(http:\/\/|https:\/\/|ftp:\/\/|irc:\/\/)([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+)\]\[3(_blank|_self|_parent)\]\[3([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+)\]/i";
			$link_n3 = "<a href=\"\\1\\2\" target=\"\\3\">\\4</a>";
			return preg_replace($link_v3, $link_n3, $text);
		break;
		default:
			return false;
	}
}
/***********************************************************************************************************
*
*				VGENEROVANI HESLA
*
*				Generuje heslo se zadanym poctem znaku
*
***********************************************************************************************************/
function GeneratePass($length = 6){
	
	$options = "abcdefghijklmnopqrstuvwxyz".
	"ABCDEFGHIJKLMNOPQRSTUVWXYZ".
	"0123456789";
	
	$pass = "";
	while(strlen($pass) < $length ) {
		$pass .= substr($options, mt_rand(0, strlen($options) - 1), 1);
	}
	return($pass);
}
/***********************************************************************************************************
*
*		CLASS - VYCISTENI HTML
*
*		description :
*		a script aimed at cleaning up after mshtml. use it in your wysiwyg html-editor,
*		to strip messy code resulting from a copy-paste from word.
*		this script doesnt come anything near htmltidy, but its pure. if you have
*		access to install binaries on your server, you might want to try using htmltidy.
*		note :
*		you might want to allow fonttags or even style tags. in that case, modify the
*		function htmlcleaner::cleanup()
*		usage :
*		$body = htmlcleaner::cleanup($_POST['htmlCode']);
*
*		disclaimer :
*		this piece of code is freely usable by anyone. if it makes your life better,
*		remember me in your eveningprayer. if it makes your life worse, try doing it any
*		better yourself.
***********************************************************************************************************/
define ('HTML_CLEANER_NODE_CLOSINGSTYLE_NORMAL',0);
define ('HTML_CLEANER_NODE_CLOSINGSTYLE_NONE',1);
define ('HTML_CLEANER_NODE_CLOSINGSTYLE_XHTMLSINGLE',2);
define ('HTML_CLEANER_NODE_NODETYPE_NODE',0);
define ('HTML_CLEANER_NODE_NODETYPE_CLOSINGNODE',1);
define ('HTML_CLEANER_NODE_NODETYPE_TEXT',2);
define ('HTML_CLEANER_NODE_NODETYPE_SPECIAL',3);
class htmlcleanertag {
	var $nodeType;
	var $nodeName;
	var $nodeValue;
	var $attributes;
	var $closingStyle;

	function htmlcleanertag($str){

		if ($str[0] == '<'){
			$this->nodeType = HTML_CLEANER_NODE_NODETYPE_NODE;
		} else {
			$this->nodeType = HTML_CLEANER_NODE_NODETYPE_TEXT;
		}

		if ((mb_strlen($str) > 1) && ($str[1] == '?' || $str[1] == '!')){
			$this->nodeType = HTML_CLEANER_NODE_NODETYPE_SPECIAL;
		}
		if ($this->nodeType == HTML_CLEANER_NODE_NODETYPE_NODE){
			$this->parseFromString($str);
		} else if ($this->nodeType == HTML_CLEANER_NODE_NODETYPE_TEXT || $this->nodeType == HTML_CLEANER_NODE_NODETYPE_SPECIAL){
			$this->nodeValue = $str;
		}
	}

	function parseFromString($str){

		$str = str_replace("\n"," ", $str);
		$offset = 1;
		$endset = mb_strlen($str)-2;
		if ($str[0] != '<' || $str[mb_strlen($str)-1] != '>'){
			trigger_error('tag syntax error', E_USER_ERROR);
		}
		if ($str[mb_strlen($str)-2] == '/') {
			$endset = $endset -1;
			$this->closingStyle = HTML_CLEANER_NODE_CLOSINGSTYLE_XHTMLSINGLE;
		}
		if ($str[1] == '/') {
			$offset = 2;
			$this->nodeType = HTML_CLEANER_NODE_NODETYPE_CLOSINGNODE;
		}
		for ($tagname = ""; preg_match("/([a-zA-Z0-9\_\:]{1})/",$str[$offset]);$offset++){
			$tagname .= $str[$offset];
		}
		for ($tagattr = "";$offset<=$endset;$offset++){
			$tagattr .= $str[$offset];
		}
		$this->nodeName = mb_strtolower($tagname);
		$this->attributes = $this->parseAttributes($tagattr);
	}

	function parseAttributes($str){

		$i = 0;
		$return = array();
		$_state = -1;
                $_value = "";
		while ($i < mb_strlen($str)) {
			$chr = $str[$i];
			if ($_state == -1) {		// reset buffers
				$_name = "";
				$_quote = "";
				$_value = "";
				$_state = 0;		// parse from here
			}
			if ($_state == 0) {		// state 0 : looking for name
				if (preg_match("/([a-zA-Z]{1})/",$chr)) {
					$_name = $chr;
					$_state = 1;
				}
			} else if ($_state == 1) {	// state 1 : looking for equal
				if (preg_match("/([a-zA-Z]{1})/",$chr)) {
					$_name .= $chr;
				} else if ($chr == "=") {
					$_state = 2;
				}
			} else if ($_state == 2) {	// state 2 : looking for quote
				if (preg_match("/([\'\"]{1})/",$chr)) {
					$_quote = $chr;
					$_value = "";
					$_state = 3;
				} else {
					$_quote = "";
					$_value = $chr;
					$_state = 3;
				}
			} else if ($_state == 3) {	// state 3 : looking for endquote
				if ($_quote != "") {
					if ($chr == $_quote) {
						// end of attribute
						$return[mb_strtolower($_name)] = $_value;
						$_state = -1;
					} else {
						$_value .= $chr;
					}
				} else {
					if (preg_match("/([a-zA-Z0-9\.\,\_\-\/\#\@\%]{1})/",$chr)) {
						$_value .= $chr;
					} else {
						// end of attribute
						$return[mb_strtolower($_name)] = $_value;
						$_state = -1;
					}
				}
			}
			$i++;
		}
		if ($_value != '') {
			$return[mb_strtolower($_name)] = $_value;
		}
		return $return;
	}

	function toString(){

		if ($this->nodeName == 'img' || $this->nodeName == 'br' || $this->nodeName == 'hr'){
			$this->closingStyle = HTML_CLEANER_NODE_CLOSINGSTYLE_XHTMLSINGLE;
		}
		if ($this->nodeType == HTML_CLEANER_NODE_NODETYPE_TEXT || $this->nodeType == HTML_CLEANER_NODE_NODETYPE_SPECIAL){
			return $this->nodeValue;
		}
		if ($this->nodeType == HTML_CLEANER_NODE_NODETYPE_NODE){
			$str = "<".$this->nodeName;
		} else if ($this->nodeType == HTML_CLEANER_NODE_NODETYPE_CLOSINGNODE){
			/*
			*	Pokud zprovoznim funkci nize, bude se za odkazy umistovat mezera
			*	return '</".$this->nodeName.">\n";
			*/
			return "</".$this->nodeName.">";
		}
		foreach ($this->attributes as $attkey => $attvalue) {
			$str .= " ".$attkey."=\"".$attvalue."\"";
		}
		if ($this->closingStyle == HTML_CLEANER_NODE_CLOSINGSTYLE_XHTMLSINGLE){
			$str .= " />";
		} else {
			$str .= ">";
	//		if ($this->nodeName != "td")
	//			$str .= "\n";
		}
		return $str;
	}

}

class HTMLcleaner{

	function dessicate($str){
		$i = 0;
		$parts = array();
		$_state = -1;
		$str_len = mb_strlen($str);
		while ($i < $str_len) {
			$chr = $str[$i];
			if ($_state == -1) {	// reset buffers
				$_buffer = "";
				$_state = 0;
			}
			if ($_state == 0) {	// state 0 : looking for <
				if ($chr == '<') {
					if (($i+3 < $str_len) && $str[$i+1] == '!' && $str[$i+2] == '-' && $str[$i+3] == '-') {
						// comment
						$_state = 2;
					} else {
						// start buffering
						if ($_buffer != '') {
							// store part
							array_push($parts,new htmlcleanertag($_buffer));
						}
						$_buffer = "<";
						$_state = 1;
					}
				} else {
					$_buffer .= $chr;
				}
			} else if ($_state == 1) {	// state 1 : in tag looking for >
				$_buffer .= $chr;
				if ($chr == '>') {
					array_push($parts,new htmlcleanertag($_buffer));
					$_state = -1;
				}
			} else if ($_state == 2) {	// state 2 : in comment looking for -->
				if ($str[$i-2] == "-" && $str[$i-1] == "-" && $str[$i] == ">") {
					$_state = -1;
				}
			}
			$i++;
		}
		return $parts;
	}

	// removes the worst mess from word.
	function cleanup($body, $str_font = 0, $str_style = 0, $str_class = 0, $str_span = 0, $str_p = 0, $str_ul = 0, $str_table = 0, $str_object = 0, $str_embed = 0){
		// in case you want to let some tags get through, just remove them from this array 'style',
		$disallowed_tags = array('cleanup:', 'o:', 'xml:', 'script', 'iframe', 'applet', 'meta', 'link');
		if ($str_font == 1){$disallowed_tags[] = "font";}
		if ($str_style == 1){$disallowed_tags[] = "style";}
		if ($str_class == 1){$disallowed_tags[] = "class";}
		if ($str_span == 1){$disallowed_tags[] = "span";}
		if ($str_p == 1){$disallowed_tags[] = "p";}
		if ($str_ul == 1){$disallowed_tags[] = "ul";}
		if ($str_ul == 1){$disallowed_tags[] = "li";}
		if ($str_table == 1){$disallowed_tags[] = "table";}
		if ($str_table == 1){$disallowed_tags[] = "tbody";}
		if ($str_table == 1){$disallowed_tags[] = "tr";}
		if ($str_table == 1){$disallowed_tags[] = "td";}
		if ($str_object == 1){$disallowed_tags[] = "object";}
		if ($str_embed == 1){$disallowed_tags[] = "embed";}
		// to protect textnodes and other mumbojumbo in the root area
		$body = "<cleanup:document>".$body."</cleanup:document>";
	   	$return = "";
		foreach (htmlcleaner::dessicate($body) as  $part) {
		   if ($str_style == 1){
				if (isset($part->attributes['style'])){
					unset($part->attributes['style']);
				}
			}
			if ($str_class == 1){
				if (isset($part->attributes['class'])){
					unset($part->attributes['class']);
				}
			}
			for ($i = 0, $check = true; $check && ($i < count($disallowed_tags)); $i++) {
				$check = $check && (mb_strstr($part->nodeName, $disallowed_tags[$i]) === false);
			}
			if ($check) {
				$return .= $part->toString();
			}
		}
		return $return;
	}
}
/***********************************************************************************************************
*
*		INET ATON
*
*		Recreate inet_aton function like in mySQL
*		convert Internet dot address to network address
*
***********************************************************************************************************/
function InetAton($ip){
	
	$ip_array = explode(".",$ip);
	return ($ip_array[0] * pow(256,3)) + ($ip_array[1] * pow(256,2)) + ($ip_array[2] * 256) + $ip_array[3]; 
}
/***********************************************************************************************************
*
*		LeagueAddToLOG
*
*		Prida zaznam do LOGu
*
*		$league_id			=	ID Ligy
*		$team_id			=	ID Teamu
*		$team_sub_id		=	ID Sub teamu
*		$admin_id			=	ID Uzivatele (nejedna se o ID hrace)
*		$old_value			=	Old value (old nick, old guid, old team name)
*		$log_action			0 - No action
*							1 - Players account was estabilished
*							2 - Player joined the team
*							3 - Player left the team
*							4 - Player was kicked from team
*							5 - Player account was blocked
*							6 - Player changed nickname
*							7 - Player added GUID
*							8 - Player changed GUID
*							9 - Player added new personality
*							10 - Player changed personality
*							11 - Player changed the team 
*							12 - Player become owner
*							13 - Player become captain
*							14 - Player become assistant
*							15 - Player left ownership
*							16 - Players captain position was taken
*							17 - Player gave captain position to someone else
*							18 - Players assistant position was taken
*							19 - Player refuse to join team
*							20 - Player join league
*							21 - Player left league
*							22 - Player was kicked from league
*							23 - Player was banned from league
*							24 - Player was un-banned for league
*							
*							50 - Team was estabilished
*							51 - Team joined league
*							52 - Team left league
*							53 - Team was kicked from league
*							54 - Team account was blocked
*							55 - Team changed name
*							56 - Team add sub team (add game)
*							57 - Team was joined by player
*							58 - Team was left by player
*							59 - Team kicked player
*							60 - Team player changed nickname
*							61 - Team get new owner
*							62 - Team get new captain
*							63 - Team get new assistant
*							64 - Team ownership was given to...
*							65 - Team took captain position
*							66 - Team took assistant position
*							67 - Team has been accepted to the league
*							68 - Team refused to add player
*							69 - Team was hibernated
*
***********************************************************************************************************/
function LeagueAddToLOG($league_id = 0, $team_id = 0, $team_sub_id = 0, $admin_id = 0, $game_id = 0, $log_action = 0, $new_value = "", $old_value = "", $reason = ""){
	
	global $db_league_log;
	
	if ($log_action != 0){
		mysql_query("INSERT INTO $db_league_log VALUES(
		'',
		'".(integer)$league_id."',
		'".(integer)$team_id."',
		'".(integer)$team_sub_id."',
		'".(integer)$admin_id."',
		'".(integer)$game_id."',
		'".(integer)$log_action."',
		NOW(),
		'".PrepareForDB($new_value)."',
		'".PrepareForDB($old_value)."',
		'".PrepareForDB($reason)."')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	}
}
/***********************************************************************************************************
*
*		LeagueCheckAwards
*
*		Check what award gets player or team in given league season
*		return - Array(league_award_place, league_award_name, league_award_img)
*		$mode		= Award mode (1 = player awards, 2 = team awards)
*		$sid		= League season ID
*		$pid		= Player ID
*		$tsid		= Team Sub ID
*
***********************************************************************************************************/
function LeagueCheckAwards($mode = 0,$sid = 0,$pid = 0,$tsid = 0){
	
	global $db_league_awards;
	
	// Check if $mode is correct
	if ($mode == 1){
		// $pid must be more then 0
		if ($pid == 0){return false; exit;}
		$where = "league_award_player_id = ".(integer)$pid;
	} elseif ($mode == 2){
		// $tsid must be more then 0
		if ($tsid == 0){return false; exit;}
		$where = "league_award_team_sub_id = ".(integer)$tsid;
	} else {
		return false; exit;
	}
	// $sid must be more then 0
	if ($sid == 0){return false; exit;}
	$res_award = mysql_query("
	SELECT league_award_place, league_award_name, league_award_img 
	FROM $db_league_awards 
	WHERE $where 
	AND league_award_season_id = ".(integer)$sid."
	AND league_award_mode = ".(integer)$mode) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_award = mysql_fetch_array($res_award);
	
	return $ar_award;
}
/***********************************************************************************************************
*
*		LeagueCheckHowManyPlayersInSubTeam
*
*		Returns how many players are in sub teams
*
*		$ltid		= League Team ID
*
***********************************************************************************************************/
function LeagueCheckHowManyPlayersInSubTeam($ltid = 0){
	
	global $db_league_teams_sub,$db_league_players;
	
	$players = 0;
	
	$res_team = mysql_query("SELECT league_team_sub_id 
	FROM $db_league_teams_sub 
	WHERE league_team_sub_team_id=".(integer)$ltid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	while($ar_team = mysql_fetch_array($res_team)){
		// We look how many players are in each sub team
		$res_players = mysql_query("SELECT COUNT(*) 
		FROM $db_league_players 
		WHERE league_player_team_sub_id=".(integer)$ar_team['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_players = mysql_fetch_array($res_players);
		// Save the number to the variable
		$players += $ar_players[0];
	}
	return $players;
}
/***********************************************************************************************************
*
*		LeagueCheckPrivileges
*
*		Provereni jakou ma hrac pozici v teamu a zda je opravnen provadet zmeny
*		Funkce vraci - True / False / ID teamu jehoz je uzivatel vlastnik
*		$mode		= Mod hrace (O = owner/ C = captain/ A = assistant/ P = player)
*		$aid		= Admin ID hrace (vetsinou brane z $_SESSION['loginid']
*		$ltid		= League Team ID
*		$lstid		= League Team Sub ID
*
***********************************************************************************************************/
function LeagueCheckPrivileges($mode = "",$aid = 0,$ltid = 0,$ltsid = 0){
	
	global $db_admin,$db_league_players,$db_league_teams,$db_league_teams_sub;
	
	/* Zkontrolujeme zda je zadan Admin ID */
	if ($aid == 0){return false; exit;}
	if ($ltid == 0){return false; exit;}
	if ($mode == "C" || $mode == "A" || $mode == "P") {
		if ($ltsid == 0){return false; exit;}
		$res_team = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".(integer)$ltid." AND league_team_sub_id=".(integer)$ltsid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_team = mysql_num_rows($res_team);
		if ($num_team == 0 || $num_team == ""){return false; exit;}
	}
	switch ($mode){
	 	case "O": // Return team ID own by user
	 		$select_what = "admin_team_own_id";
			$query = "SELECT ".$select_what." FROM $db_admin WHERE admin_id=".(integer)$aid." AND admin_team_own_id=".(integer)$ltid;
	 	break;
		case "C":
			$select_what = "league_player_position_captain";
			$query = "SELECT ".$select_what." FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_team_sub_id=".(integer)$ltsid;
		break;
		case "A":
			$select_what = "league_player_position_assistant";
			$query = "SELECT ".$select_what." FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_team_sub_id=".(integer)$ltsid;
		break;
		case "P":
			$select_what = "league_player_position_player";
			$query = "SELECT ".$select_what." FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_team_sub_id=".(integer)$ltsid;
		break;
		default:
			return false;
		exit;
	}
	$res = mysql_query($query) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	return $ar[$select_what];
}
/***********************************************************************************************************
*
*			PREPARE FOR DB
*
*			Priprava textu pro vlozeni do databaze
*
***********************************************************************************************************/
function PrepareForDB($text,$for_allowtags = 1,$for_allowtags_tags = "", $for_mysql_real_escape_string = 1){
	
	if ($for_allowtags == 1){ $text = strip_tags($text,$for_allowtags_tags);}
	$text = stripcslashes($text);
	$text = str_ireplace( "&amp;#163;","&#163;",$text);
	$text = str_ireplace( "&amp;#165;","&#165;",$text);
	$text = str_ireplace( "&amp;#956;","&#956;",$text);
	$text = str_ireplace( "&amp;nbsp;","&nbsp;",$text);
	$text = str_ireplace( "&amp;sect;","&sect;",$text);
	$text = str_ireplace( "’","&rsquo;",$text);
	$text = str_ireplace( "‘","&lsquo;",$text);
	$text = str_ireplace( "’","&rsquo;",$text);
	$text = str_ireplace( "'","&acute;",$text);
	$text = str_ireplace( "´","&acute;",$text);
	$text = str_ireplace( "\"","&quot;",$text);
	$text = str_ireplace( "<","&lt;",$text);
	$text = str_ireplace( ">","&gt;",$text);
	$text = str_ireplace( "€","&euro;",$text);
	$text = str_ireplace( "©","&copy;",$text);
	$text = str_ireplace( "®","&reg;",$text);
	$text = str_ireplace( "§","&sect;",$text);
	//$text = str_ireplace( "A","&#165;",$text);
	//$text = str_ireplace( "L","&pound;",$text);
	$text = str_ireplace( "«","&laquo;",$text);
	$text = str_ireplace( "»","&raquo;",$text);
	$text = str_ireplace( "µ","&#956;",$text);
	$text = str_ireplace( "¶","&para;",$text);
	$text = str_ireplace( "‰","&#137;",$text);
	$text = str_ireplace( "™","&#153;",$text);
	if ($for_mysql_real_escape_string == 1){ $text = mysql_real_escape_string($text);}
	return $text;
}
/***********************************************************************************************************
*
*		PREPARE FROM DB
*
*		Priprava textu po nacteni z databaze
*
***********************************************************************************************************/
function PrepareFromDB($text,$from_stripslashes = 1){
	
	if ($from_stripslashes == 1){ $text = stripslashes($text);}
	$text = str_ireplace( "&acute;", "'",$text);
	$text = str_ireplace( "&#039;", "'",$text);
	$text = str_ireplace( "&quot;", "\"",$text);
	$text = str_ireplace( "&lt;","<",$text);
	$text = str_ireplace( "&gt;",">",$text);
	$text = str_ireplace( "&euro;","€",$text);
	$text = str_ireplace( "&copy;","©",$text);
	$text = str_ireplace( "&reg;","®",$text);
	return $text;
}
/***********************************************************************************************************
*
*			FORMAT MENY (existuje i v functions.php)
*
*			Formatuje cenu na pozadovany tvar
*
***********************************************************************************************************/
function PriceFormat($price){
	
	global $db_shop_setup;
	
	$res = mysql_query("SELECT shop_setup_currency FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	if ($ar['shop_setup_currency'] == "CZK"){
		$price = number_format($price, 2, ',', ' ');
		return $price." Kc";
	}
	if ($ar['shop_setup_currency'] == "EUR"){
		$price = number_format($price, 2, '.', ' ');
		return $price." &euro;";
	}
	if ($ar['shop_setup_currency'] == "GBP"){
		$price = number_format($price, 2, '.', ',');
		return "&pound;".$price;
	}
	if ($ar['shop_setup_currency'] == "USD"){
		$price = number_format($price, 2, '.', ',');
		return '$'.$price;
	}
}
/***********************************************************************************************************
*
*	EMAIL zakaznikovi po kazde akci
*
*	$order_id	- 	ID obednavky
*	$email_mode	- 	Rika nam z jake faze je email odesilan (1-7)
*					1 - Order in process
*					2 - Pending
*					3 - Payment authorised
*					4 - Order picked / Awaiting despatch
*					5 - Order despatched
*					6 - Canceled before pay
*					7 - Canceled after pay
*	$show_mode	- 	full 	- odesle email dle zadanych parametru
*					test	- zobrazi zformatovany obsah ale neodesle se (pro ucely testovani)
*
***********************************************************************************************************/
function SendEmailToCustomer($order_id,$email_mode,$show_mode = "full"){
	
	global $db_shop_orders,$db_shop_orders_product,$db_setup,$db_shop_setup,$db_admin,$db_shop_product;
	
	$res_orders = mysql_query("SELECT
	shop_orders_admin_id,
	shop_orders_id,
	shop_orders_carriage_price,
	shop_orders_invoice_id,
	shop_orders_date_ordered,
	shop_orders_date_sended_for_payment,
	shop_orders_date_purchased,
	shop_orders_date_picked,
	shop_orders_date_despatched,
	shop_orders_date_cancelled,
	shop_orders_admin_firstname,
	shop_orders_admin_name,
	shop_orders_admin_company,
	shop_orders_admin_address1,
	shop_orders_admin_address2,
	shop_orders_admin_suburb,
	shop_orders_admin_city,
	shop_orders_admin_postcode,
	shop_orders_admin_telephone,
	shop_orders_admin_mobile,
	shop_orders_admin_email_address
	FROM $db_shop_orders WHERE shop_orders_id=".(integer)$order_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_orders = mysql_fetch_array($res_orders);
	
	$res_adm = mysql_query("SELECT admin_lang FROM $db_admin WHERE admin_id=".(integer)$ar_orders['shop_orders_admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_adm = mysql_fetch_array($res_adm);
	
	$res_setup = mysql_query("SELECT setup_basic_lang, setup_reg_mailer FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	// Nastaveni jazyku
	if ($ar_adm['admin_lang'] != ""){$admin_lang = $ar_adm['admin_lang'];} else {$admin_lang = $ar_setup['setup_basic_lang'];}
	
	$res_shop_setup = mysql_query("SELECT 
	shop_setup_email_subject,
	shop_setup_show_vat_subtotal,
	shop_setup_email_order,
	shop_setup_email_order_status_1,
	shop_setup_email_order_status_2,
	shop_setup_email_order_status_3,
	shop_setup_email_order_status_4,
	shop_setup_email_order_cancel_before_payment,
	shop_setup_email_order_cancel_after_payment,
	shop_setup_email_order_subject,
	shop_setup_email_charset,
	shop_setup_email_from,
	shop_setup_email_from_name 
	FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($admin_lang)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_shop_setup = mysql_fetch_array($res_shop_setup);
	
	$res_order_products = mysql_query("SELECT 
	shop_orders_shop_product_id,
	shop_orders_shop_product_tax,
	shop_orders_shop_product_price,
	shop_orders_shop_product_quantity,
	shop_orders_shop_product_name 
	FROM $db_shop_orders_product WHERE shop_orders_orders_id=".(integer)$ar_orders['shop_orders_id']." ORDER BY shop_orders_product_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//Product Summary
	$product_summary = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"600\"><tr class=\"ps\"><td class=\"ps\">"._SHOP_SAVE_PRODUCT_CODE."</td><td class=\"ps\">"._SHOP_SAVE_PRODUCT."</td><td class=\"ps\">"._SHOP_SAVE_QUANTITY."</td><td align=\"right\" class=\"ps\">"._SHOP_SAVE_PRICE."</td></tr>";
	while($ar_order_products = mysql_fetch_array($res_order_products)){
		$res_product = mysql_query("SELECT shop_product_product_code FROM $db_shop_product WHERE shop_product_id=".(integer)$ar_order_products['shop_orders_shop_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_product = mysql_fetch_array($res_product);
		
		$vat_for_include_price = ($ar_order_products['shop_orders_shop_product_tax'] / 100) + 1;
		$price_inc_vat = $ar_order_products['shop_orders_shop_product_price'] * $ar_order_products['shop_orders_shop_product_quantity'];
		$price_vat = $price_inc_vat - ($price_inc_vat / $vat_for_include_price);
		$price_ex_vat = ($price_inc_vat - $price_vat);
		
		$basket_ex_vat = TepRound($price_ex_vat,2);
		$basket_inc_vat = TepRound($price_inc_vat,2);
		if ($ar_shop_setup['shop_setup_show_vat_subtotal'] == 1){
			$order_product_final_price = $basket_ex_vat;
		} else {
			$order_product_final_price = $basket_inc_vat;
		}
		$product_summary .= "<tr><td>".$ar_product['shop_product_product_code']."</td><td>".$ar_order_products['shop_orders_shop_product_name']."</td><td>".$ar_order_products['shop_orders_shop_product_quantity']."</td><td class=\"op\">".PriceFormat($order_product_final_price)." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		
		$total_nett_price = $price_ex_vat + $total_nett_price;
		$total_vat = (($ar_order_products['shop_orders_shop_product_price'] * $ar_order_products['shop_orders_shop_product_quantity']) - ($price_inc_vat / $vat_for_include_price)) + $total_vat;
		$subtotal = $price_inc_vat + $subtotal;
	}
	$total_total = $total_nett_price + $total_vat + $ar_orders['shop_orders_carriage_price'];
	$basket_total_vat = TepRound($total_vat,2);
	
	if ($ar_shop_setup['shop_setup_show_vat_subtotal'] == 1){
		$product_summary .= "<tr><td colspan=\"3\" class=\"ont_n\">"._SHOP_SAVE_SUB_NETT_TOTAL."</td><td class=\"ont\">".PriceFormat(TepRound($total_nett_price))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		$product_summary .= "<tr><td colspan=\"3\" class=\"otv_n\">"._SHOP_SAVE_TOTAL_VAT."</td><td class=\"otv\">".PriceFormat($basket_total_vat)." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		$product_summary .= "<tr><td colspan=\"3\" class=\"oc_n\">"._SHOP_SAVE_CARRIAGE."</td><td> class=\"oc\"".PriceFormat(TepRound($ar_orders['shop_orders_carriage_price']))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		$product_summary .= "<tr><td colspan=\"3\" class=\"ot_n\">"._SHOP_SAVE_TOTAL."</td><td class=\"ot\">".PriceFormat(TepRound($total_total))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
	} else {
		$product_summary .= "<tr><td colspan=\"3\" class=\"ost_n\">"._SHOP_SAVE_SUB_TOTAL."</td><td class=\"ost\">".PriceFormat(TepRound($subtotal,2))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		$product_summary .= "<tr><td colspan=\"3\" class=\"oc_n\">"._SHOP_SAVE_CARRIAGE."</td><td class=\"oc\">".PriceFormat(TepRound($ar_orders['shop_orders_carriage_price']))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
		$product_summary .= "<tr><td colspan=\"3\" class=\"ot_n\">"._SHOP_SAVE_TOTAL."</td><td class=\"ot\">".PriceFormat(TepRound($total_total))." ".$ar_shop_setup['shop_setup_currency']."</td></tr>";
	}
	$product_summary .= "</table>";
	
	/* Pridame $product_summary a $estimate_date do pole $ar_orders abychom je mohli jednoduse pouzit v nasledujici funkci */
	$ar_orders['product_summary'] = $product_summary;
	$date_now = strtotime($ar_orders['shop_orders_date_ordered']);
	$date_estimate = strtotime("+1 week",$date_now);
	$ar_orders['estimate_delivery'] = date('d/m/Y', $date_estimate);
	
	if ($email_mode == 1){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order']);
	}elseif ($email_mode == 2){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_status_1']);
	}elseif ($email_mode == 3){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_status_2']);
	}elseif ($email_mode == 4){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_status_3']);
	}elseif ($email_mode == 5){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_status_4']);
	}elseif ($email_mode == 6){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_cancel_before_payment']);
	}elseif ($email_mode == 7){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_order_cancel_after_payment']);
	}
	/* Nastavime predmet emailu */
	$shop_setup_email_order_subject = str_replace("[{shop_orders_invoice_id}]",$ar_orders['shop_orders_invoice_id'],$ar_shop_setup['shop_setup_email_order_subject']);
	/* Pokud je to jeden ze statusu 1 az 4 tak se prida do predmetu */
	if ($email_mode > 0 && $email_mode < 5 ){$shop_setup_email_order_subject .= " - ".$email_mode."/4";}
	
	if ($show_mode == "full"){
		$body = str_replace("&nbsp;"," " ,$body);
		$body = str_replace("&lt;","<" ,$body);
		$body = str_replace("&gt;",">" ,$body);
		$body = str_replace("&pound;","ÿ" ,$body);
		$body = str_replace("&quot;","\"" ,$body);
		$body = str_replace("<br>","" ,$body);
		
		$mail = new PHPMailer();
		$mail->CharSet 		= $ar_shop_setup['shop_setup_email_charset'];
		$mail->ContentType	= "text/html";
		$mail->From			= $ar_shop_setup['shop_setup_email_from'];
		$mail->FromName 	= $ar_shop_setup['shop_setup_email_from_name'];
		$mail->AddAddress($ar_orders['shop_orders_admin_email_address']);
		$mail->Mailer 		= $ar_setup['setup_reg_mailer'];
		$mail->Subject 		= $shop_setup_email_order_subject;
		$mail->Body 		= SendEmailToCustomerBody($body,$ar_orders);
		$mail->IsHTML(true);
		$mail->AltBody = "\n";
		$mail->AltBody .= SendEmailToCustomerBody($body,$ar_orders);
		$mail->AltBody .= "\n";
		$mail->Send();
		
	} else {
		echo "<table>";
		echo "<tr><td><strong>Charset:</strong></td><td>".$ar_shop_setup['shop_setup_email_charset']."</td></tr>";
		echo "<tr><td><strong>From:</strong></td><td>".$ar_shop_setup['shop_setup_email_from']."</td></tr>";
		echo "<tr><td><strong>FromName:</strong></td><td>".$ar_shop_setup['shop_setup_email_from_name']."</td></tr>";
		echo "<tr><td><strong>To:</strong></td><td>".$ar_orders['shop_orders_admin_email_address']."</td></tr>";
		echo "<tr><td><strong>Mailer:</strong></td><td>".$ar_setup['setup_reg_mailer']."</td></tr>";
		echo "<tr><td><strong>Subject:</strong></td><td>".$shop_setup_email_order_subject."</td></tr>";
		echo "<tr><td colspan=\"2\"><strong>Body:</strong><br>".$body."</td></tr>";
		echo "</table>";
	}
	/* V pripade ze byl produkt zaplacen, odejde email i Obchodu */
	if ($email_mode == 3){
		$body = str_replace("\n", "<br>",$ar_shop_setup['shop_setup_email_confirm_of_payment']);
		$shop_setup_email_order_subject = _SHOP_SAVE_EMAIL_NEW_ORDER_SUB.$ar_orders['shop_orders_invoice_id'];
		$mail = new PHPMailer();
		$mail->CharSet 		= $ar_shop_setup['shop_setup_email_charset'];
		$mail->ContentType	= "text/plain";
		$mail->From			= $ar_shop_setup['shop_setup_email_from'];
		$mail->FromName 	= $ar_shop_setup['shop_setup_email_from_name'];
		$mail->AddAddress($ar_shop_setup['shop_setup_email_from']);
		$mail->Mailer 		= $ar_setup['setup_reg_mailer'];
		$mail->Subject 		= $shop_setup_email_order_subject;
		$mail->Body 		= SendEmailToCustomerBody($body,$ar_orders);
		$mail->IsHTML(true);
		$mail->AltBody = "\n";
		$mail->AltBody .= SendEmailToCustomerBody($body,$ar_orders);
		$mail->AltBody .= "\n";
		$mail->Send();
	}
}
/***********************************************************************************************************
*
*		SendEmailToCustomerBody
*
*		Priprava tela zpravy podle sablony z nastaveni
*
* 		$body	=	Text ktery ma projit funkci
*
*		Vraci	- upraveny text
*
***********************************************************************************************************/
function SendEmailToCustomerBody($body,$ar_orders){
	$body = str_replace("[{shop_orders_admin_id}]",$ar_orders['shop_orders_admin_id'] ,$body);
	$body = str_replace("[{shop_orders_admin_firstname}]",$ar_orders['shop_orders_admin_firstname'] ,$body);
	$body = str_replace("[{shop_orders_admin_name}]",$ar_orders['shop_orders_admin_name'] ,$body);
	$body = str_replace("[{shop_orders_admin_company}]",$ar_orders['shop_orders_admin_company'] ,$body);
	$body = str_replace("[{shop_orders_admin_address1}]",$ar_orders['shop_orders_admin_address1'] ,$body);
	$body = str_replace("[{shop_orders_admin_address2}]",$ar_orders['shop_orders_admin_address2'] ,$body);
	$body = str_replace("[{shop_orders_admin_suburb}]",$ar_orders['shop_orders_admin_suburb'] ,$body);
	$body = str_replace("[{shop_orders_admin_city}]",$ar_orders['shop_orders_admin_city'] ,$body);
	$body = str_replace("[{shop_orders_admin_postcode}]",$ar_orders['shop_orders_admin_postcode'] ,$body);
	$body = str_replace("[{shop_orders_admin_telephone}]",$ar_orders['shop_orders_admin_telephone'] ,$body);
	$body = str_replace("[{shop_orders_admin_mobile}]",$ar_orders['shop_orders_admin_mobile'] ,$body);
	$body = str_replace("[{shop_orders_admin_email_address}]",$ar_orders['shop_orders_admin_email_address'] ,$body);
	$body = str_replace("[{shop_orders_id}]",$ar_orders['shop_orders_id'] ,$body);
	$body = str_replace("[{shop_orders_invoice_id}]",$ar_orders['shop_orders_invoice_id'] ,$body);
	$body = str_replace("[{shop_orders_date_ordered}]",FormatDatetime($ar_orders['shop_orders_date_ordered']) ,$body);
	$body = str_replace("[{shop_orders_date_sended_for_payment}]",FormatDatetime($ar_orders['shop_orders_date_sended_for_payment']) ,$body);
	$body = str_replace("[{shop_orders_date_purchased}]",FormatDatetime($ar_orders['shop_orders_date_purchased']) ,$body);
	$body = str_replace("[{shop_orders_date_picked}]",FormatDatetime($ar_orders['shop_orders_date_picked']) ,$body);
	$body = str_replace("[{shop_orders_date_despatched}]",FormatDatetime($ar_orders['shop_orders_date_despatched']) ,$body);
	$body = str_replace("[{shop_orders_date_cancelled}]",FormatDatetime($ar_orders['shop_orders_date_cancelled']) ,$body);
	$body = str_replace("[{PRODUCTS_SUMMARY}]",$ar_orders['product_summary'] ,$body);
	$body = str_replace("[{ESTIMATE_DELIVERY}]",$ar_orders['estimate_delivery'],$body);
	return $body;
}
/***********************************************************************************************************
*
*		SHORT TEXT
*
*		Zajisteni zobrazeni jen potrebneho poctu znaku
*
***********************************************************************************************************/
function ShortText($input,$word_lenght = 15){
	
	$input = strip_tags($input,"");
	$count = mb_strlen($input,"UTF-8");
	$output = mb_substr($input, 0, $word_lenght, "UTF-8");
	//if ($count > $word_lenght){$output = "<span title=\"".$output."\">".$output."...</span>";}
	if ($count > $word_lenght){
		$output = $output."...";
	}
	return $output;
}
/***********************************************************************************************************
*
*			ZAOKROUHLENI
*
*			Zaokrouhluje podl
*			$number - cislo
*			$precis - pocet desetinnych mist
*
***********************************************************************************************************/
// Wrapper function for round()
function TepRound($number, $precision = 2) {
	if (strpos($number, ".") && (strlen(substr($number, strpos($number, ".")+1)) > $precision)) {
		$number = substr($number, 0, strpos($number, ".") + 1 + $precision + 1);
		
		if (substr($number, -1) >= 5) {
			if ($precision > 1) {
				$number = substr($number, 0, -1) + ("0." . str_repeat(0, $precision-1) . '1');
			} elseif ($precision == 1) {
				$number = substr($number, 0, -1) + 0.1;
			} else {
				$number = substr($number, 0, -1) + 1;
			}
		} else {
			$number = substr($number, 0, -1);
		}
	}
	return $number;
}
/***********************************************************************************************************
*
*		 ZPRACOVANI HLAVICKY, NAHLEDU A TEXTU	 (existuje i v functions.php)
*
***********************************************************************************************************/
function TreatText($text,$chars = 0, $stripslashes = 0){

	$text = htmlspecialchars_decode($text,ENT_QUOTES);
	$text = ConvertBracketLinks ($text,1);
	$text = ConvertBracketLinks ($text,2);
	$text = ConvertBracketLinks ($text,3);
	$text = str_replace("&", "&amp;", $text);
	$text = str_replace("&amp;nbsp;", "&nbsp;", $text);
	$text = str_replace("&amp;euro;", "&euro;", $text);
	$text = str_replace("&amp;rsquo;", "&rsquo;", $text);
	$text = str_replace("&amp;sect;", "&sect;", $text);
	if ($chars > 0){ $text = wordwrap( $text, $chars, "\n", 1);} // Zalomi text
    if ($stripslashes == 1){$text = stripslashes($text);}
	return($text);
}
/***********************************************************************************************************
*
*		ZEROFILL - Nachazi se i v functions.php
*
*		Tato funkce prida nuly pred cislo
*
* 		$num	=	Cislo pred ktere chceme pridat nuly
*		$zero	=	Cislo ktere chceme nulama dorovnat
*
***********************************************************************************************************/
function Zerofill($num,$zero = 10){

	$num = (float)$num;
	$zero = (float)$zero;

	if ($zero == 10 || $zero == 100 || $zero == 1000 || $zero == 10000 || $zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000){
		if ($zero == 10 || $zero == 100 || $zero == 1000 || $zero == 10000 || $zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 10){$num = "0".$num;}}
		if ($zero == 100 || $zero == 1000 || $zero == 10000 || $zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 100){$num = "0".$num;}}
		if ($zero == 1000 || $zero == 10000 || $zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 1000){$num = "0".$num;}}
		if ($zero == 10000 || $zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 10000){$num = "0".$num;}}
		if ($zero == 100000 || $zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 100000){$num = "0".$num;}}
		if ($zero == 1000000 || $zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 1000000){$num = "0".$num;}}
		if ($zero == 10000000 || $zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 10000000){$num = "0".$num;}}
		if ($zero == 100000000 || $zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 100000000){$num = "0".$num;}}
		if ($zero == 1000000000 || $zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 1000000000){$num = "0".$num;}}
		if ($zero == 10000000000 || $zero == 100000000000 || $zero == 1000000000000) {if ($num < 10000000000){$num = "0".$num;}}
		if ($zero == 100000000000 || $zero == 1000000000000) {if ($num < 100000000000){$num = "0".$num;}}
		if ($zero == 1000000000000) {if ($num < 1000000000000){$num = "0".$num;}}
		return $num;
	} else {
		echo "Bad number";
	}
}
/***********************************************************************************************************
*																											
*		GET IMAGE INFO FROM SETUP																			
*																											
*		$mode		- avatar, category, link_1, link_2, article_1, article_2, profile_1, profile_2				
*					- league_team_logo, league_award, smiles, todo, games_1, games_2, smiles				
*																											
*		$info		width																					
*					height																					
*					filesize																				
*																											
***********************************************************************************************************/
function GetSetupImageInfo($mode,$info){
	
	global $db_setup_images;
	
	/* Kontorla Modu*/
	if (
		$mode == "avatar" || $mode == "category" || $mode == "link_1" || $mode == "link_2" || $mode == "article_1" || 
		$mode == "article_2" || $mode == "profile_1" || $mode == "profile_2" || $mode == "league_team_logo" || 
		$mode == "league_award" || $mode == "smiles" || $mode == "todo" || $mode == "games_1" || $mode == "games_2" || 
		$mode == "rss"
	){
		
		/* Kontrola pozadovaneho infa */
		switch ($info){
			case "width":
				$inf = "eden_setup_image_width";
				break;
			case "height":
				$inf = "eden_setup_image_height";
				break;
			case "filesize":
				$inf = "eden_setup_image_filesize";
				break;
			default:
			return FALSE;
			exit;
		}
		$res_img = mysql_query("SELECT * FROM $db_setup_images WHERE eden_setup_image_for='".mysql_real_escape_string($mode)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_img = mysql_fetch_array($res_img);
		
		return $ar_img[$inf];
	} else {
		return FALSE;
		exit;
	}
}

/**
 * Converts to ASCII.
 * @param  string  UTF-8 encoding
 * @return string  ASCII
 */
function toAscii($s){
	$s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u', '', $s);
	$s = strtr($s, '`\'"^~', "\x01\x02\x03\x04\x05");
	if (ICONV_IMPL === 'glibc') {
		$s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
		$s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
			. "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
			. "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
			. "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
			"ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
	} else {
		$s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
	}
	$s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
	return strtr($s, "\x01\x02\x03\x04\x05", '`\'"^~');
}

/**
 * Converts to web safe characters [a-z0-9-] text.
 * @param  string  UTF-8 encoding
 * @param  string  allowed characters
 * @param  bool
 * @return string
 */
function webalize($s, $charlist = NULL, $lower = TRUE){
	$s = toAscii($s);
	if ($lower) {
		$s = strtolower($s);
	}
	$s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $s);
	$s = trim($s, '-');
	return $s;
}


/**
 * Truncates string to maximal length.
 * @param  string  UTF-8 encoding
 * @param  int
 * @param  string  UTF-8 encoding
 * @return string
 */
function truncate($s, $maxLen, $append = "\xE2\x80\xA6") {
	if (length($s) > $maxLen) {
		$maxLen = $maxLen - length($append);
		if ($maxLen < 1) {
			return $append;
		} elseif ($matches = match($s, '#^.{1,'.$maxLen.'}(?=[\s\x00-/:-@\[-`{-~])#us')) {
			return $matches[0] . $append;
		} else {
			return substring($s, 0, $maxLen) . $append;
		}
	}
	return $s;
}

/**
 * Returns UTF-8 string length.
 * @param  string
 * @return int
 */
function length($s){
	return strlen(utf8_decode($s)); // fastest way
}

/**
 * Performs a regular expression match.
 * @param  string
 * @param  string
 * @param  int  can be PREG_OFFSET_CAPTURE (returned in bytes)
 * @param  int  offset in bytes
 * @return mixed
 */
function match($subject, $pattern, $flags = 0, $offset = 0){
	if ($offset > strlen($subject)) {
		return NULL;
	}
	$res = preg_match($pattern, $subject, $m, $flags, $offset);
	if ($res) {
		return $m;
	}
}

/**
 * Returns a part of UTF-8 string.
 * @param  string
 * @param  int
 * @param  int
 * @return string
 */
function substring($s, $start, $length = NULL){
	if ($length === NULL) {
		$length = length($s);
	}
	return function_exists('mb_substr') ? mb_substr($s, $start, $length, 'UTF-8') : iconv_substr($s, $start, $length, 'UTF-8'); // MB is much faster
}

/**
 * Returns a timestamp as an integer with milliseconds
 * @return int
 */
function getTimestamp(){ 
    $seconds = microtime(true); // false = int, true = float 
    return round( ($seconds * 1000) ); 
}
