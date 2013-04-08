<?php
// Pokud se k functions.php pristupuje z tinymce pluginu, nastavi se i spravne cesty
if (!isset($eden_editor_tinymce_plugin_path)){$eden_editor_tinymce_plugin_path = "";} // Inicializujeme prommenou pokud jeste inicializovana neni
if ($eden_editor_tinymce_plugin_path){
	$file_prefix = $eden_editor_tinymce_plugin_path;
} else {
	$file_prefix = ".";
}
include($file_prefix."/cfg/functions_Common.php");				/*	Common functions for FrontEnd and Back End */
/***********************************************************************************************************
*
*		SEZNAM FUNKCI
*
*		PrepareDateForSpiffyCalendar	-	Priprava datumu ze SpiffyCalendar pro vlozeni do DB jako DATETIME
*		PrepareForDBXML					-	Priprava pro ulozeni do databaze a naslednem pouziti v XML souboru
*		PrepTextDB						-	CLASS - PRIPRAVA PRO VLOZENI DO DATABAZE A Z DATABAZE
*		StripInetService				-	ODSTRANENI SLUZEB Z ODKAZU
*		UnHtmlEntities					-	PREVEDENI HTML TAGU NA ENTITY
*		LogOut							-	LOGOUT
*		FormatDatetime					-	ZFORMATOVANI CASU Z DATETIME
*		FormatTimestamp					-	NASTAVENI DATUMU Z FORMATU
*		FormatPaypalTimestamp			-	NASTAVENI DATUMU Z PAYPAL NVP API
*		FormatTournamentFormat					-	ZFORMATOVANI FORMATU TURNAJE
*		FormatTime						-	ZFORMATOVANI CASU
*		FormatTimeS						-	ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*		FormatTimeP						-	ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*		FormatTimeW						-	ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*		FormatTimeD						-	ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*		CheckPriv						-	KONTROLA OPRAVNENI K VYKONANI RUZNYCH PRIKAZU
*		makeRSS							-	VYTVORENI RSS SOUBORU
*		LastUpdate						-	Last update (article, act)
*		KillUse							-	ODSTRANENI ZAZNAMU O UZIVANI NOVINKY
*		KillUseById						-	ODSTRANENI ZAZNAMU O UZIVANI NOVINKY
*		VyberRoku						-	CALENDAR - Vyber roku
*		VyberMesice						-	CALENDAR - Vyber mesice
*		VyberDne						-	CALENDAR - Vyber dne
*		VyberHodiny						-	CALENDAR - Vyber hodiny
*		VyberMinuty						-	CALENDAR - Vyber minuty
*		Cislo							-	NEZAMENITELNE CISLO - SEKUNDY A MILISEKUNDY
*		NazevVlajky						-	NAZEV VLAJKY
*		CurrencySymbol					-	ZNAK MENY
*		HashCall						-	HashCall - Function to perform the API call to PayPal using API signature
*		DeformatNVP						-	This function will take NVPString and convert it to an Associative Array
*		GetUserName						-	USERNAME - Ziskani uzivatelova Nicku/Jmena podle jeho ID
*		SysMsg							-	Zobrazeni systemovych zprav a chyb
*		ClanAwardsPlaceExt 				- 	Zobrazeni spravne koncovky u cislic
*		LeagueGenerateListAllowedPlayers - Vygeneruje seznam povolenych hracu, co smeji hrat ligu a ulozi ho
*		CheckExtension 					- 	Proveri zda nahravany soubor je obrazek - Podporovane typy obrazku - jpg, gif, png
*		GetFilesizeInKB 				- 	Vrati velikost souboru v KB je li vetsi nez 1024 B
*		SimpleImage 					- 	Trida pro zmenu velikosti obrazku
*		FtpCheckDirArray				-	Proveruje zda je adresar vytvoren, pokud ne vytvori se. Pri problemech vraci chybovou hlasku
*		EdenSysImageManager				-	Manager obrazku podle urceni (kategorie, smiles, awards...)
*		EdenCategorySelect				-	Show all categories for given mode (adds, articles, downloads, links, news, shop, streems)
*
***********************************************************************************************************/
//pokud je soubor functions.php includovan do nejake stranky tak aby se nevyvolala chyba je treba nastavit promennou $_SESSION[project]
if (isset($_POST['project'])) {$_SESSION['project'] = $_POST['project'];} else {$_SESSION['project'] = $_GET['project'];}
//Jestlize neni zvolen soubor s konfiguraci - zobrazi se prihlasovaci skript
if ($_SESSION['project'] == ""){
	header ("Location: index.php?action=msg");
} else {
	if ($_SESSION['lang'] == ""){$_SESSION['lang'] = "cz";}
	require_once(dirname(__FILE__)."/cfg/db.".$_SESSION['project'].".inc.php");
	if (!isset($eden_editor_add_include_lang)){$eden_editor_add_include_lang = "";} // Inicializujeme prommenou pokud jeste inicializovana neni
	if ($eden_editor_add_include_lang != true){
		require_once (dirname(__FILE__)."/lang/lang-".$_SESSION['lang'].".php");
	}
	mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
	mysql_select_db($eden_cfg['db_name']);
	//Nastaveni prevodu z Databaze
	if ($eden_cfg['db_encode_allow'] = "1"){mysql_query($eden_cfg['db_encode']);}
	// Privilegia
	$chu = mysql_query("SELECT admin_priv FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']."");
	$usr = mysql_fetch_array($chu);
	$chk = mysql_query("SELECT * FROM $db_groups WHERE groups_id=".(integer)$usr['admin_priv']."");
	$prv = mysql_fetch_array($chk);
}

// Nacteme Logovaci funkci jen pokud se nezpracovava v pluginu TinyMCE
	require_once($file_prefix."/class/class.edenlog.php");
/***********************************************************************************************************
*
*		Priprava datumu ze SpiffyCalendar pro vlozeni do DB jako DATETIME (2011-06-30 01:02:03)
*
*		$date		- datum ze SpiffyCalendar ve formatu 29.07.1975
*		$time		- cas, pokud neni nic zadano zobrazi se aktualni cas
*					- actual
*					- date_only
*
***********************************************************************************************************/
function PrepareDateForSpiffyCalendar($date,$time = "00:00:01"){
	
	if ($time == "actual"){
		$time = " ".date("H:i:s",time());
	} elseif ($time == "date_only"){
		$time = "";
	} elseif ($time != "00:00:01"){
		$pieces = explode(":",$time);
		$pc1 = substr($pieces[0], 0, 2);
		$pc2 = substr($pieces[1], 0, 2);
		$pc3 = substr($pieces[2], 0, 2);
		preg_match("/[0-9]{2}/",Zerofill($pc1,10),$piece0);
		preg_match("/[0-9]{2}/",Zerofill($pc2,10),$piece1);
		preg_match("/[0-9]{2}/",Zerofill($pc3,10),$piece2);
		if (is_numeric($piece0[0]) && $piece0[0] >= 0 && $piece0[0] < 24){ $h = $piece0[0];} else { $h = "00";}
		if (is_numeric($piece1[0]) && $piece1[0] >= 0 && $piece1[0] < 60){ $m = $piece1[0];} else { $m = "00";}
		if (is_numeric($piece2[0]) && $piece2[0] >= 0 && $piece2[0] < 60){ $s = $piece2[0];} else { $s = "01";}
		$time = " ".$h.":".$m.":".$s;
	} else {
		$time = " 00:00:01";
	}
	return $date[6].$date[7].$date[8].$date[9]."-".$date[3].$date[4]."-".$date[0].$date[1].$time;
}
/***********************************************************************************************************
*
*			PREPARE FOR DB XML
*
*			Priprava pro ulozeni do databaze a naslednem pouziti v XML souboru
*
***********************************************************************************************************/
function PrepareForDBXML($text){
	
	$text = strip_tags($text,"");
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_ireplace( "©","&#xA9;",$text);
	$text = str_ireplace( "™","&#x2122;",$text);
	$text = str_ireplace( "µ","&#956;",$text);
	$text = str_ireplace( "‰","&#137;",$text);
	return $text;
}
/***********************************************************************************************************
*
*		CLASS - PRIPRAVA PRO VLOZENI DO DATABAZE A Z DATABAZE
*
***********************************************************************************************************/
class PrepTextDB{
	
	var $for_allowtags = 1;
	var $for_allowtags_tags = "";
	var $for_addslashes = 1;
	var $from_striplsashes = 1;
	var $text;
	
	/*
	*	Priprava textu pro databazi
	*/
	function PrepareForDB($text){
	
		$this->text = $text;
		if ($this->for_allowtags == 1){ $this->text = strip_tags($this->text,$this->for_allowtags_tags);}
		if ($this->for_addslashes == 1){ $this->text = addslashes($this->text);}
		$this->text = str_ireplace( "'", "&acute;",$this->text);
		$this->text = str_ireplace( "\"", "&quot;",$this->text);
		//return $this->text;
	}
	/*
	*	Priprava textu z databaze
	*/
	function PrepareFromDB($text){
	
		$this->text = $text;
		if ($this->from_stripslashes == 1){ $this->text = stripslashes($this->text);}
		$this->text = str_ireplace( "&acute;", "'",$this->text);
		$this->text = str_ireplace( "&quot;", "\"",$this->text);
		//return $this->text;
	}
}
/***********************************************************************************************************
*
*			 ODSTRANENI SLUZEB Z ODKAZU
*
***********************************************************************************************************/
function StripInetService($link){
	
	$link = str_replace('http://', '', $link);
	$link = str_replace('https://', '', $link);
	$link = str_replace('ftp://', '', $link);
	$link = str_replace('file://', '', $link);
	$link = str_replace('irc://', '', $link);
	$link = str_replace('gopher://', '', $link);
	$link = str_replace('mailto:', '', $link);
	$link = str_replace('telnet://', '', $link);
	$link = str_replace('wais://', '', $link);
	return($link);
}
/***********************************************************************************************************
*
*		PREVEDENI HTML TAGU NA ENTITY
*
***********************************************************************************************************/
function UnHtmlEntities ($string){
	
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}
/***********************************************************************************************************
*
*		ODHLASENI
*
***********************************************************************************************************/
function LogOut(){
	
	global $db_sessions;
	global $eden_cfg;
	
	$SN = "autorizace";
	session_name("$SN");
	session_start();
	$sid = session_id();
	include_once (dirname(__FILE__)."/cfg/db.".$_SESSION['project'].".inc.php");
	mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
	mysql_select_db($eden_cfg['db_name']);
	mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($sid)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_SESSION = array();
	session_destroy();
	header ("Location: index.php?action=msg");
	echo _FUNCTIONLOGOUT;
}
/***********************************************************************************************************
*
*		ZFORMATOVANI FORMATU TURNAJE
*
***********************************************************************************************************/
function FormatTournamentFormat($format) {
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
/***********************************************************************************************************
*
*		ZFORMATOVANI PRAVIDELNOSTI TURNAJE
*
***********************************************************************************************************/
function FormatTournamentRegularity($regularity) {
    $formattedRegularity = "Nedefinovaná";
    switch($regularity) {
        case "onetime":
            break;
        case "weekly":
            break;
        default:
            break;
    }
    return $formattedRegularity;
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z DATETIME
*
*		puvodni format	- 2004-07-01 30:59:59
*
***********************************************************************************************************/
function FormatDatetime($time,$format = "d.m.Y H:i:s"){
	
	global $datetime;
	
	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/i", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z DATE
*
*		puvodni format	- 2004-07-01
*
***********************************************************************************************************/
function FormatDate($time,$format = "d.m.Y H:i:s"){
	
	global $datetime;
	
	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})/i", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		NASTAVENI DATUMU Z FORMATU
*
*		puvodni format	- 20040701305959
*
***********************************************************************************************************/
function FormatTimestamp($time,$format = "d.m.Y\nH:i:s"){
	
	global $datetime;
	
	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/i", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		NASTAVENI DATUMU Z PAYPAL NVP API
*
*		puvodni format	- 2006-12-26T24:00:00Z
*
***********************************************************************************************************/
function FormatPaypalTimestamp($time,$format = "d.m.y - H:i"){

	global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})Z/i", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU
*
***********************************************************************************************************/
function FormatTime($time){

	global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		$datetime = date("d.m.Y", $time);
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*
***********************************************************************************************************/
function FormatTimeS($time){

	global $datetime;

	$datetime = date("d.m.Y - H:i:s", $time);
	preg_match ("/^([0-9]{1,2}).([0-9]{1,2}).([0-9]{4}) - ([0-9]{2}):([0-9]{2}):([0-9]{2})/i", $datetime, $ardatetime);
	return($ardatetime);
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*
***********************************************************************************************************/
function FormatTimeP($time) {

	global $datetime;

	$datetime = date("Y-m-d H:i:s", $time);
	return($datetime);
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*
***********************************************************************************************************/
function FormatTimeW($time) {

	global $datetime;

	$datetime = date("d.m.Y H:i:s", $time);
	return($datetime);
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*
***********************************************************************************************************/
function FormatTimeD($time) {

	preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/i", $time, $ardatetime);
	return($ardatetime);
}
/***********************************************************************************************************
*
*		Ziskani mktime z Timestampu nebo Datetime
*
*		20090101000001 			= 	Timestamp
*		2009-01-01 00:00:01		=	Datetime
*		2009-01-01				=	Date
*
***********************************************************************************************************/
function EdenGetMkTime($date, $format = "Timestamp"){
	if ($format == "Timestamp"){
		preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/i", $date, $datetime);
		$datetime = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
	}
	if ($format == "Datetime"){
		preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/i", $date, $datetime);
		$datetime = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
	}
	return $datetime;
}
/***********************************************************************************************************
*
*		KONTROLA OPRAVNENI K VYKONANI RUZNYCH PRIKAZU
*
*		Funkce vraci hodnotu 0 nebo 1 podle toho co je ulozeno v poli $prv. Podle toho se pozna jestli
*		ma dany uzivatel prava k dane akci.
*
*		$prv		=	pole ze zacatku tohoto souboru ve kterem jsou ulozena vsechny eden_groups zaznamy
*		$mytable	=	nazev bunky z tabulky eden_groups
*
***********************************************************************************************************/
function CheckPriv($mytable){
	
	global $db_admin,$db_groups;
	global $project;
	
	$chu = mysql_query("SELECT admin_priv FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$usr = mysql_fetch_array($chu);
	$chk = mysql_query("SELECT ".mysql_real_escape_string($mytable)." FROM $db_groups WHERE groups_id=".(integer)$usr['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$prv = mysql_fetch_array($chk);
	
	return $prv[$mytable];
}
/***********************************************************************************************************
*
*		LastUpdate
*		$mode = article,act
*
***********************************************************************************************************/
function LastUpdate($mode = ""){
	
	global $db_articles,$db_news;
	
	if ($mode == "article"){
		$vysledek = mysql_query("SELECT MAX(article_date) FROM $db_articles") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$vysledek2 = mysql_query("SELECT MAX(article_date_edit) FROM $db_articles") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} elseif ($mode == "act"){
		$vysledek = mysql_query("SELECT MAX(news_date) FROM $db_news") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$vysledek2 = mysql_query("SELECT MAX(news_date_edit) FROM $db_news") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		return false;
	}
	if ($vysledek > $vysledek2){
		$ar = mysql_fetch_array($vysledek);
	} else {
		$ar = mysql_fetch_array($vysledek2);
	}
	/* Datum posledni aktualizace se vytahuje podle zaznamu z databaze */
	$datum = FormatTimestamp($ar[0],"d.m.Y H:i:s");
	return $datum;
}

/***********************************************************************************************************
*
*		ODSTRANENI ZAZNAMU O UZIVANI NOVINKY
*
***********************************************************************************************************/
function KillUse($user){

	global $db_articles,$db_news,$db_cups_bracket;

	$resu = mysql_query("SELECT article_id, article_date FROM $db_articles WHERE article_user_use=".(float)$user) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($aru = mysql_fetch_array($resu)){
		// Smazani jmena uzivatele ktery otevrel novinku
		mysql_query("UPDATE $db_articles SET article_user_use=0 WHERE article_id=".(float)$aru['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$resu = mysql_query("SELECT news_id, news_date FROM $db_news WHERE news_user_use=".(float)$user) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($aru = mysql_fetch_array($resu)){
		// Smazani jmena uzivatele ktery otevrel aktualitu
		mysql_query("UPDATE $db_news SET news_user_use=0 WHERE news_id=".(float)$aru['news_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$resu = mysql_query("SELECT cups_bracket_id FROM $db_cups_bracket WHERE cups_bracket_user_use=".(float)$user) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($aru = mysql_fetch_array($resu)){
		// Smazani jmena uzivatele ktery otevrel aktualitu
		mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=0 WHERE cups_bracket_id=".(float)$aru['cups_bracket_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}
/***********************************************************************************************************
*
*		ODSTRANENI ZAZNAMU O UZIVANI NOVINKY
*
***********************************************************************************************************/
function KillUseById($id,$action){

	global $db_articles,$db_news,$db_cups_bracket;

	if ($action == "kill_use_article"){
		$resu = mysql_query("SELECT article_id, article_date FROM $db_articles WHERE article_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$aru = mysql_fetch_array($resu);
		// Smazani jmena uzivatele ktery otevrel novinku
		mysql_query("UPDATE $db_articles SET article_user_use=0, article_date=".(float)$aru['article_date']." WHERE article_id=".(float)$aru['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	if ($action == "kill_use_news"){
		$resu = mysql_query("SELECT news_id, news_date FROM $db_news WHERE news_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$aru = mysql_fetch_array($resu);
		// Smazani jmena uzivatele ktery otevrel aktualitu
		mysql_query("UPDATE $db_news SET news_user_use=0, news_date=".(float)$aru['news_date']." WHERE news_id=".(float)$aru['news_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	if ($action == "kill_use_bracket"){
		$resu = mysql_query("SELECT cups_bracket_id FROM $db_cups_bracket WHERE cups_bracket_id=".(float)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$aru = mysql_fetch_array($resu);
		// Smazani jmena uzivatele ktery otevrel aktualitu
		mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=0 WHERE cups_bracket_id=".(float)$aru['cups_bracket_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber roku
*
***********************************************************************************************************/
function VyberRoku($year){

	echo "<select name=\"y\">\n";
		$z = 3;
		for($i=1;$i < 8; $i++) {
			if ($z == 0){
				echo "<option value=\"".($year - $z)."\" selected>".($year - $z)."</option>";
				echo "\n";
			} else {
				echo "<option value=\"".($year - $z)."\">".($year - $z)."</option>";
				echo "\n";
			}
			$z--;
		}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber mesice
*
***********************************************************************************************************/
function VyberMesice($month, $montharray){

	echo "\n<select name=\"m\">\n";
	for($i=0;$i < 12; $i++) {
		if ($i != ($month - 1)){
			echo "<option value=\"".($i + 1)."\">".$montharray[$i]."</option>";
			echo "\n";
		} else {
			echo "<option value=\"".($i + 1)."\" selected>".$montharray[$i]."</option>";
			echo "\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber dne
*
***********************************************************************************************************/
function VyberDne($day){

	echo "<select name=\"d\">\n";
	for($i=1;$i <= 31; $i++) {
		if ($i == $day){
			echo "	<option value=\"$i\" selected>$i</option>\n";
		} else {
			echo "	<option value=\"$i\">$i</option>\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber hodiny
*
***********************************************************************************************************/
function VyberHodiny($hour, $namepre){

	echo "\n<select name=\"".$namepre."_hour\">\n";
	for($i=0;$i <= 23; $i++) {
		if ($i == $hour){
			echo "	<option value=\"$i\" selected>$i</option>\n";
		} else {
			echo "	<option value=\"$i\">$i</option>\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber minuty
*
***********************************************************************************************************/
function VyberMinuty($min, $namepre){

	echo "\n<select name=\"" . $namepre . "_min\">\n";
	for($i=0;$i <= 55; $i+=5) {
		if ($i < 10){
			$disp = "0".$i;
		} else {
			$disp = $i;
		}
		if ($i == $min){
			echo "	<option value=\"$i\" selected>$disp</option>\n";
		} else {
			echo "	<option value=\"$i\">$disp</option>\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		NEZAMENITELNE CISLO - SEKUNDY A MILISEKUNDY
*
***********************************************************************************************************/
function Cislo(){

	list($msec, $sec) = explode(' ', microtime());
	$restmsec = substr ($msec, 2, 6);
	$restsec = substr ($sec, 7, 10);
	//echo $restsec.$restmsec;
	//$random = $restsec.$restmsec;
	return $sec.$restmsec;
}
/***********************************************************************************************************
*
*		NAZEV VLAJKY
*
***********************************************************************************************************/
function NazevVlajky($flag,$lang){
	
	global $file_prefix;
	
	include_once $file_prefix."/cfg/eden_lang_".$lang.".php";
	
	if ($flag == "BALTIC"){return _BALTIC;}
	if ($flag == "ALPY"){return _ALPS;}
	if ($flag == "AMERIKA"){return _AMERICA;}
	if ($flag == "BENELUX"){return _BENELUX;}
	if ($flag == "SKANDINAVIE"){return _SKANDINAVIA;}
	if ($flag == "VYCHOD"){return _EAST;}
	if ($flag == "TIBET"){return _TIBET;}

	if ($flag == "01"){return _ALPS;}
	if ($flag == "02"){return _BALTIC;}
	if ($flag == "03"){return _AMERICA;}
	if ($flag == "04"){return _BENELUX;}
	if ($flag == "05"){return _SKANDINAVIA;}
	if ($flag == "06"){return _EAST;}
	if ($flag == "07"){return _TIBET;}
	if ($flag == "08"){return _SCOTLAND;}
	if ($flag == "09"){return _ENGLAND;}
	if ($flag == "10"){return _WAKE_ISLAND;}
	if ($flag == "11"){return _WALES;}
	if ($flag == "12"){return _NORTHERN_IRELAND;}
	if ($flag == "13"){return _NORTHERN_CYPRUS;}
	if ($flag == "14"){return _MIDWAY_ISLANDS;}
	if ($flag == "15"){return _JERSEY;}
	if ($flag == "16"){return _ISLE_OF_MAN;}
	if ($flag == "17"){return _FLAG_WORLD;}

	if ($flag == "EU"){return _EU;}

	if ($flag == "AF"){return _AFGHANISTAN;}
	if ($flag == "AL"){return _ALBANIA;}
	if ($flag == "DZ"){return _ALGERIA;}
	if ($flag == "AS"){return _AMERICAN_SAMOA;}
	if ($flag == "AD"){return _ANDORRA;}
	if ($flag == "AO"){return _ANGOLA;}
	if ($flag == "AI"){return _ANGUILLA;}
	if ($flag == "AQ"){return _ANTARCTICA;}
	if ($flag == "AG"){return _ANTIGUA_AND_BARBUDA;}
	if ($flag == "AR"){return _ARGENTINA;}
	if ($flag == "AM"){return _ARMENIA;}
	if ($flag == "AW"){return _ARUBA;}
	if ($flag == "AU"){return _AUSTRALIA;}
	if ($flag == "AT"){return _AUSTRIA;}
	if ($flag == "AZ"){return _AZERBAIJAN;}
	if ($flag == "BS"){return _BAHAMAS;}
	if ($flag == "BH"){return _BAHRAIN;}
	if ($flag == "BD"){return _BANGLADESH;}
	if ($flag == "BB"){return _BARBADOS;}
	if ($flag == "BY"){return _BELARUS;}
	if ($flag == "BE"){return _BELGIE;}
	if ($flag == "BZ"){return _BELIZE;}
	if ($flag == "BJ"){return _BENIN;}
	if ($flag == "BM"){return _BERMUDA;}
	if ($flag == "BT"){return _BHUTAN;}
	if ($flag == "BO"){return _BOLIVIA;}
	if ($flag == "BA"){return _BOSNA;}
	if ($flag == "BW"){return _BOTSWANA;}
	if ($flag == "BV"){return _BOUVET_ISLAND;}
	if ($flag == "BR"){return _BRAZIL;}
	if ($flag == "IO"){return _BRITISH_INDIAN_TERR;}
	if ($flag == "BN"){return _BRUNEI;}
	if ($flag == "BG"){return _BULGARIA;}
	if ($flag == "BF"){return _BURKINA_FASO;}
	if ($flag == "BI"){return _BURUNDI;}
	if ($flag == "KH"){return _CAMBODIA;}
	if ($flag == "CM"){return _CAMEROON;}
	if ($flag == "CA"){return _CANADA;}
	if ($flag == "CV"){return _CAPE_VERDE;}
	if ($flag == "KY"){return _CAYMAN_ISLANDS;}
	if ($flag == "CF"){return _CENTRAL_AFRICA_REP;}
	if ($flag == "TD"){return _CHAD;}
	if ($flag == "CL"){return _CHILE;}
	if ($flag == "CN"){return _CHINA;}
	if ($flag == "CX"){return _CHRISTMAS_ISLAND;}
	if ($flag == "CC"){return _COCOS_ISLANDS;}
	if ($flag == "CO"){return _COLUMBIA;}
	if ($flag == "KM"){return _COMOROS;}
	if ($flag == "CG"){return _CONGO;}
	if ($flag == "CD"){return _CONGO_DPR;}
	if ($flag == "CK"){return _COOK_ISLANDS;}
	if ($flag == "CR"){return _COSTA_RICA;}
	if ($flag == "CI"){return _IVORY_COAST;}
	if ($flag == "HR"){return _CROATIA;}
	if ($flag == "CU"){return _CUBA;}
	if ($flag == "CY"){return _CYPRUS;}
	if ($flag == "CZ"){return _CZECH_REPUBLIC;}
	if ($flag == "DK"){return _DENMARK;}
	if ($flag == "DJ"){return _DJIBOUTI;}
	if ($flag == "DM"){return _DOMINICA;}
	if ($flag == "DO"){return _DOMINICAN_REPUBLIC;}
	if ($flag == "TP"){return _EAST_TIMOR;}
	if ($flag == "EC"){return _ECUADOR;}
	if ($flag == "EG"){return _EGYPT;}
	if ($flag == "SV"){return _EL_SALVADOR;}
	if ($flag == "GQ"){return _EQUATORIA_GUINEA;}
	if ($flag == "ER"){return _ERITREA;}
	if ($flag == "EE"){return _ESTONIA;}
	if ($flag == "ET"){return _ETHIOPIA;}
	if ($flag == "FK"){return _FALKLAND_ISLANDS;}
	if ($flag == "FO"){return _FAROE_ISLANDS;}
	if ($flag == "FJ"){return _FIJI;}
	if ($flag == "FI"){return _FINLAND;}
	if ($flag == "FR"){return _FRANCE;}
	if ($flag == "GF"){return _FRENCH_GUIANA;}
	if ($flag == "PF"){return _FRENCH_POLUNESIA;}
	if ($flag == "TF"){return _FRENCH_SOUTHERN_TERR;}
	if ($flag == "GA"){return _GABON;}
	if ($flag == "GM"){return _GAMBIA;}
	if ($flag == "GE"){return _GEORGIA;}
	if ($flag == "DE"){return _GERMANY;}
	if ($flag == "GH"){return _GHANA;}
	if ($flag == "GI"){return _GIBRALTAR;}
	if ($flag == "GR"){return _GREECE;}
	if ($flag == "GL"){return _GREENLAND;}
	if ($flag == "GD"){return _GRENADA;}
	if ($flag == "GP"){return _GUADELOUPE;}
	if ($flag == "GU"){return _GUAM;}
	if ($flag == "GT"){return _GUATEMALA;}
	if ($flag == "GN"){return _GUINEA;}
	if ($flag == "GW"){return _GUINEA_BISSAU;}
	if ($flag == "GY"){return _GUYANA;}
	if ($flag == "HT"){return _HAITI;}
	if ($flag == "HM"){return _HEARD_AND_MCDONALD;}
	if ($flag == "HN"){return _HONDURAS;}
	if ($flag == "HK"){return _HONG_KONG;}
	if ($flag == "HU"){return _HUNGARY;}
	if ($flag == "IS"){return _ICELAND;}
	if ($flag == "IN"){return _INDIA;}
	if ($flag == "ID"){return _INDONESIA;}
	if ($flag == "IR"){return _IRAN;}
	if ($flag == "IQ"){return _IRAQ;}
	if ($flag == "IE"){return _IRELAND;}
	if ($flag == "IT"){return _ITALY;}
	if ($flag == "IL"){return _ISRAEL;}
	if ($flag == "JM"){return _JAMAICA;}
	if ($flag == "JP"){return _JAPAN;}
	if ($flag == "JO"){return _JORDAN;}
	if ($flag == "KZ"){return _KAZAKSTAN;}
	if ($flag == "KE"){return _KENYA;}
	if ($flag == "KI"){return _KIRIBATI;}
	if ($flag == "KR"){return _KOREA_REPUBLIC;}
	if ($flag == "KP"){return _KOREA_DPR;}
	if ($flag == "KW"){return _KUWAIT;}
	if ($flag == "KG"){return _KYRGYZSTAN;}
	if ($flag == "LA"){return _LAO;}
	if ($flag == "LV"){return _LATVIA;}
	if ($flag == "LB"){return _LEBANON;}
	if ($flag == "LS"){return _LESOTHO;}
	if ($flag == "LR"){return _LIBERIA;}
	if ($flag == "LY"){return _LYBIA;}
	if ($flag == "LI"){return _LICHTENSTEIN;}
	if ($flag == "LT"){return _LITHUANIA;}
	if ($flag == "LU"){return _LUXEMBOURG;}
	if ($flag == "MO"){return _MACAU;}
	if ($flag == "MK"){return _MACEDONIA;}
	if ($flag == "MG"){return _MADAGASCAR;}
	if ($flag == "MW"){return _MALAWI;}
	if ($flag == "MY"){return _MALAYSIA;}
	if ($flag == "MV"){return _MALDIVES;}
	if ($flag == "ML"){return _MALI;}
	if ($flag == "MT"){return _MALTA;}
	if ($flag == "MH"){return _MARSHALL_ISLANDS;}
	if ($flag == "MQ"){return _MARTINIQUE;}
	if ($flag == "MR"){return _MAURITANIA;}
	if ($flag == "MU"){return _MAURITIUS;}
	if ($flag == "YT"){return _MAYOTTE;}
	if ($flag == "MX"){return _MEXICO;}
	if ($flag == "FM"){return _MICRONESIA;}
	if ($flag == "MD"){return _MOLDOVA_REP;}
	if ($flag == "MC"){return _MONACO;}
	if ($flag == "MN"){return _MONGOLIA;}
	if ($flag == "MS"){return _MONTSERRAT;}
	if ($flag == "MA"){return _MOROCCO;}
	if ($flag == "MZ"){return _MOZAMBIQUE;}
	if ($flag == "MM"){return _MYANMAR;}
	if ($flag == "NA"){return _NAMIBIA;}
	if ($flag == "NR"){return _NAURU;}
	if ($flag == "NP"){return _NEPAL;}
	if ($flag == "NL"){return _NETHERLANDS;}
	if ($flag == "AN"){return _NETHERLANDS_ANTILLES;}
	if ($flag == "NC"){return _NEW_CALEDONIA;}
	if ($flag == "NZ"){return _NEW_ZEALAND;}
	if ($flag == "NI"){return _NICARAGUA;}
	if ($flag == "NE"){return _NIGER;}
	if ($flag == "NG"){return _NIGERIA;}
	if ($flag == "NU"){return _NIUE;}
	if ($flag == "NF"){return _NORFOLK_ISLANDS;}
	if ($flag == "MP"){return _NORTHERN_MARIANA_ISLANDS;}
	if ($flag == "NO"){return _NORWAY;}
	if ($flag == "OM"){return _OMAN;}
	if ($flag == "PK"){return _PAKISTAN;}
	if ($flag == "PW"){return _PALAU;}
	if ($flag == "PS"){return _PALESTINE;}
	if ($flag == "PA"){return _PANAMA;}
	if ($flag == "PG"){return _PAPUA_NEW_GUINEA;}
	if ($flag == "PY"){return _PARAGUAY;}
	if ($flag == "PE"){return _PERU;}
	if ($flag == "PH"){return _PHILIPINES;}
	if ($flag == "PN"){return _PITCAIRN;}
	if ($flag == "PL"){return _POLAND;}
	if ($flag == "PT"){return _PORTUGAL;}
	if ($flag == "PR"){return _PUERTO_RICO;}
	if ($flag == "QA"){return _QUATAR;}
	if ($flag == "RE"){return _REUNION;}
	if ($flag == "RO"){return _ROMANIA;}
	if ($flag == "RU"){return _RUSSIAN_FEDERATION;}
	if ($flag == "RW"){return _RWANDA;}
	if ($flag == "SH"){return _SAINT_HELENA;}
	if ($flag == "KN"){return _SAINT_KITTS_AND_NEVIS;}
	if ($flag == "LC"){return _SAINT_LUCIA;}
	if ($flag == "PM"){return _SAINT_PIERRE_AND_MIQUELON;}
	if ($flag == "WS"){return _SAMOA;}
	if ($flag == "SM"){return _SAN_MARINO;}
	if ($flag == "ST"){return _SAINT_TOME_AND_PRINCIPE;}
	if ($flag == "SA"){return _SAUDI_ARABIA;}
	if ($flag == "SN"){return _SENEGAL;}
	if ($flag == "SC"){return _SEYCHELLES;}
	if ($flag == "SL"){return _SIERRA_LEONE;}
	if ($flag == "SG"){return _SINGAPORE;}
	if ($flag == "SK"){return _SLOVAKIA;}
	if ($flag == "SI"){return _SLOVENIA;}
	if ($flag == "SB"){return _SOLOMON_ISLANDS;}
	if ($flag == "SO"){return _SOMALIA;}
	if ($flag == "ZA"){return _SOUTH_AFRICA;}
	if ($flag == "GS"){return _SOUTH_GEORGIA_SANDWICH_IS;}
	if ($flag == "ES"){return _SPAIN;}
	if ($flag == "LK"){return _SRI_LANKA;}
	if ($flag == "VC"){return _ST_VINCENT;}
	if ($flag == "SD"){return _SUDAN;}
	if ($flag == "SR"){return _SURINAME;}
	if ($flag == "SJ"){return _SVALBARD_AND_JAN_MAYEN;}
	if ($flag == "SZ"){return _SWAZILAND;}
	if ($flag == "SE"){return _SWEDEN;}
	if ($flag == "CH"){return _SWITZERLAND;}
	if ($flag == "SY"){return _SYRIAN_REPUBLIC;}
	if ($flag == "TW"){return _TAIWAN;}
	if ($flag == "TJ"){return _TAJIKISTAN;}
	if ($flag == "TZ"){return _TANZANIA;}
	if ($flag == "TH"){return _THAILAND;}
	if ($flag == "TG"){return _TOGO;}
	if ($flag == "TK"){return _TOKELAU;}
	if ($flag == "TO"){return _TONGA;}
	if ($flag == "TT"){return _TRINIDAD_AND_TOBAGO;}
	if ($flag == "TN"){return _TUNISIA;}
	if ($flag == "TR"){return _TURKEY;}
	if ($flag == "TM"){return _TURKMENISTAN;}
	if ($flag == "TC"){return _TURKS_AND_CAICOS_ISLANDS;}
	if ($flag == "TV"){return _TUVALU;}
	if ($flag == "UG"){return _UGANDA;}
	if ($flag == "UA"){return _UKRAINE;}
	if ($flag == "AE"){return _UNITED_ARAB_EMIRATES;}
	if ($flag == "GB"){return _UNITED_KINGDOM;}
	if ($flag == "US"){return _UNITED_STATES;}
	if ($flag == "UY"){return _URUGUAY;}
	if ($flag == "UM"){return _US_MINOR_OUTLYING_ISLANDS;}
	if ($flag == "UZ"){return _UZBEKISTAN;}
	if ($flag == "VU"){return _VANUATU;}
	if ($flag == "VA"){return _VATICAN_CITY;}
	if ($flag == "VE"){return _VENEZUELA;}
	if ($flag == "VN"){return _VIETNAM;}
	if ($flag == "VG"){return _VIRGIN_ISLANDS_BRITISH;}
	if ($flag == "VI"){return _VIRGIN_ISLANDS_US;}
	if ($flag == "WF"){return _WALLIS_AND_FUTUNA;}
	if ($flag == "EH"){return _WESTERN_SAHARA;}
	if ($flag == "YE"){return _YEMEN;}
	if ($flag == "YU"){return _SERBIA;}
	if ($flag == "ZM"){return _ZAMBIA;}
	if ($flag == "ZW"){return _ZIMBABWE;}

	// Stare rozvrzeni
	if ($flag == "ALBANIE"){return _ALBANIE;}
	if ($flag == "ARGENTINA"){return _ARGENTINA;}
	if ($flag == "BOSNA"){return _BOSNA;}
	if ($flag == "BELGIE"){return _BELGIE;}
	if ($flag == "BELORUSKO"){return _BELORUSKO;}
	if ($flag == "BRAZILIE"){return _BRAZILIE;}
	if ($flag == "BULHARSKO"){return _BULHARSKO;}
	if ($flag == "CESKO"){return _CESKO;}
	if ($flag == "CINA"){return _CINA;}
	if ($flag == "COLUMBIE"){return _COLUMBIE;}
	if ($flag == "DANSKO"){return _DANSKO;}
	if ($flag == "ENGLAND"){return _ENGLAND;}
	if ($flag == "ESTONSKO"){return _ESTONSKO;}
	if ($flag == "FILIPINY"){return _FILIPINY;}
	if ($flag == "FINSKO"){return _FINSKO;}
	if ($flag == "FRANCIE"){return _FRANCIE;}
	if ($flag == "HOLANDSKO"){return _HOLANDSKO;}
	if ($flag == "CHILE"){return _CHILE;}
	if ($flag == "CHORVATSKO"){return _CHORVATSKO;}
	if ($flag == "INDIE"){return _INDIE;}
	if ($flag == "INDONESIE"){return _INDONESIE;}
	if ($flag == "IRAK"){return _IRAK;}
	if ($flag == "IRAN"){return _IRAN;}
	if ($flag == "IRSKO"){return _IRSKO;}
	if ($flag == "ISLAND"){return _ISLAND;}
	if ($flag == "ITALIE"){return _ITALIE;}
	if ($flag == "IZRAEL"){return _IZRAEL;}
	if ($flag == "JAPONSKO"){return _JAPONSKO;}
	if ($flag == "KANADA"){return _KANADA;}
	if ($flag == "KOREA"){return _KOREA;}
	if ($flag == "KYPR"){return _KYPR;}
	if ($flag == "LITVA"){return _LITVA;}
	if ($flag == "MADARSKO"){return _MADARSKO;}
	if ($flag == "MALAISIE"){return _MALAISIE;}
	if ($flag == "MALTA"){return _MALTA;}
	if ($flag == "MAROKO"){return _MAROKO;}
	if ($flag == "MEXIKO"){return _MEXIKO;}
	if ($flag == "MOLDAVSKO"){return _MOLDAVSKO;}
	if ($flag == "MONGOLSKO"){return _MONGOLSKO;}
	if ($flag == "NEMECKO"){return _NEMECKO;}
	if ($flag == "NORSKO"){return _NORSKO;}
	if ($flag == "NOVYZELAND"){return _NOVYZELAND;}
	if ($flag == "PERU"){return _PERU;}
	if ($flag == "POLSKO"){return _POLSKO;}
	if ($flag == "PORTUGALSKO"){return _PORTUGALSKO;}
	if ($flag == "RAKOUSKO"){return _RAKOUSKO;}
	if ($flag == "RECKO"){return _RECKO;}
	if ($flag == "RUMUNSKO"){return _RUMUNSKO;}
	if ($flag == "RUSKO"){return _RUSKO;}
	if ($flag == "SEICHILY"){return _SEICHILY;}
	if ($flag == "SINGAPUR"){return _SINGAPUR;}
	if ($flag == "SLOVENSKO"){return _SLOVENSKO;}
	if ($flag == "SLOVINSKO"){return _SLOVINKSO;}
	if ($flag == "SPANELSKO"){return _SPANELSKO;}
	if ($flag == "SRBSKO"){return _SRBSKO;}
	if ($flag == "STREDOAFRICKA"){return _STREDOAFRICKA;}
	if ($flag == "SVEDSKO"){return _SVEDSKO;}
	if ($flag == "SVYCARSKO"){return _SVYCARSKO;}
	if ($flag == "SYRIE"){return _SYRIE;}
	if ($flag == "TAIWAN"){return _TAIWAN;}
	if ($flag == "TIBET"){return _TIBET;}
	if ($flag == "TUNIS"){return _TUNIS;}
	if ($flag == "TURECKO"){return _TURECKO;}
	if ($flag == "UKRAINA"){return _UKRAINA;}
	if ($flag == "URUGUAY"){return _URUGUAY;}
	if ($flag == "USA"){return _USA;}
	if ($flag == "VENEZUELA"){return _VENEZUELA;}
}
/***********************************************************************************************************
*
*			ZNAK MENY
*
*			Formatuje menu z nastaveni na znak meny
*
***********************************************************************************************************/
function CurrencySymbol($currency){
	
	if ($currency == "CZK"){
		return "K¿";
	}
	if ($currency == "EUR"){
		return "&euro;";
	}
	if ($currency == "GBP"){
		return "&pound;";
	}
	if ($currency == "USD"){
		return "$";
	}
}
/***********************************************************************************************************
*
*		HashCall - Function to perform the API call to PayPal using API signature
*
*		Function to perform the API call to PayPal using API signature
*		@methodName is name of API  method.	(TransactionSearch, gettransactionDetails)
*		@nvpStr is nvp string.
*		returns an associtive array containing the response from the server.
*
***********************************************************************************************************/
function HashCall($methodName,$nvpStr){
	
	//declaring of global variables
	global $eden_cfg;
	
	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$eden_cfg['paypal_nvp_api_endpoint']);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	//if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	//Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
	if($eden_cfg['paypal_nvp_api_use_proxy']){
		curl_setopt ($ch, CURLOPT_PROXY, $eden_cfg['paypal_nvp_api_proxy_host'].":".$eden_cfg['paypal_nvp_api_proxy_port']);
	}
	//NVPRequest for submitting to server
	$nvpreq = "METHOD=".urlencode($methodName)."&VERSION=".urlencode($eden_cfg['paypal_nvp_api_version'])."&PWD=".urlencode($eden_cfg['paypal_nvp_api_passport'])."&USER=".urlencode($eden_cfg['paypal_nvp_api_username'])."&SIGNATURE=".urlencode($eden_cfg['paypal_nvp_api_signature']).$nvpStr;
	
	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
	//getting response from server
	$response = curl_exec($ch);
	
	//convrting NVPResponse to an Associative Array
	$nvpResArray = DeformatNVP($response);
	$nvpReqArray = DeformatNVP($nvpreq);
	$_SESSION['nvpReqArray'] = $nvpReqArray;
	
	if (curl_errno($ch)) {
		// moving to display page to display curl errors
		$_SESSION['curl_error_no'] = curl_errno($ch) ;
		$_SESSION['curl_error_msg'] = curl_error($ch);
		$location = $eden_cfg['url']."modul_shop_paypal_test_api.php?action=api_error";
		header("Location: $location");
		exit;
	} else {
		 //closing the curl
		curl_close($ch);
	}
	
	return $nvpResArray;
}
/***********************************************************************************************************
*
*		DeformatNVP
*
*		This function will take NVPString and convert it to an Associative Array and it will
*		decode the response.
* 		It is usefull to search for a particular key and displaying arrays.
*		@nvpstr is NVPString.
*		@nvpArray is Associative Array.
*
***********************************************************************************************************/
function DeformatNVP($nvpstr){
	
	$intial = 0;
	$nvpArray = array();
	
	while(strlen($nvpstr)){
		//postion of Key
		$keypos = strpos($nvpstr,'=');
		//position of value
		$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
		
		/*getting the Key and Value values and storing in a Associative Array*/
		$keyval = substr($nvpstr,$intial,$keypos);
		$valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		//decoding the respose
		$nvpArray[urldecode($keyval)] = urldecode( $valval);
		$nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
	}
	return $nvpArray;
}
/***********************************************************************************************************
*
*		GetUserName
*
*		Ziskani uzivatelova Nicku/Jmena podle jeho ID
*
* 		$uid		=	ID admina/usera
*
***********************************************************************************************************/
function GetUserName($uid){
	
	global $db_admin,$db_setup;
	
	$res_adm = mysql_query("SELECT admin_firstname, admin_name, admin_nick FROM $db_admin WHERE admin_id=".(float)$uid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_adm = mysql_fetch_array($res_adm);
	
	$res_setup = mysql_query("SELECT setup_reg_admin_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($ar_setup['setup_reg_admin_nick'] == 1){
		return $ar_adm['admin_nick'];
	} else {
		return $ar_adm['admin_firstname']." ".$ar_adm['admin_name'];
	}
}
/***********************************************************************************************************
*
*		SysMsg
*
*		Zobrazeni systemovych zprav a chyb
*
***********************************************************************************************************/
function SysMsg($sys_msg){
	
	switch ($sys_msg){
		case "aa_ok":
			return _MSG_ADMINS_ADD_OK;
			break;
		case "ad_ok":
			return _MSG_ADMINS_DEL_OK;
			break;
		case "add_no":
				return _MSG_ADD_NO;
			break;
		case "add_ok":
				return _MSG_ADD_OK;
			break;
		case "ae_ok":
			return _MSG_ADMINS_EDIT_OK;
			break;
		case "aca_ok":
			return _MSG_ADMINS_CAT_ADD_OK;
			break;
		case "ace_ok":
			return _MSG_ADMINS_CAT_EDIT_OK;
			break;
		case "acd_ok":
			return _MSG_ADMINS_CAT_DEL_OK;
			break;
		case "article_channel_add_ok":
			return _MSG_ARTICLES_CHANNEL_ADD_OK;
			break;
		case "article_channel_del_ok":
			return _MSG_ARTICLES_CHANNEL_DEL_OK;
			break;
		case "article_channel_edit_ok":
			return _MSG_ARTICLES_CHANNEL_EDIT_OK;
			break;
		case "article_channel_upload_img_ok":
			return _MSG_ARTICLES_CHANNEL_UPLOAD_IMG_OK;
			break;
		case "article_img_1_upl_er":
			return _MSG_ARTICLE_IMG_1_UP_NO;
			break;
		case "article_img_1_upl_ok":
			return _MSG_ARTICLE_IMG_1_UP_OK;
			break;
		case "article_img_2_upl_er":
			return _MSG_ARTICLE_IMG_2_UP_NO;
			break;
		case "article_img_2_upl_ok":
			return _MSG_ARTICLE_IMG_2_UP_OK;
			break;
		case "article_img_1_upl_erarticle_img_2_upl_er":
			return _MSG_ARTICLE_IMG_1_UP_NO."<br>"._MSG_ARTICLE_IMG_2_UP_NO;
			break;
		case "article_img_1_upl_erarticle_img_2_upl_ok":
			return _MSG_ARTICLE_IMG_1_UP_NO."<br>"._MSG_ARTICLE_IMG_2_UP_OK;
			break;
		case "article_img_1_upl_okarticle_img_2_upl_er":
			return _MSG_ARTICLE_IMG_1_UP_OK."<br>"._MSG_ARTICLE_IMG_2_UP_NO;
			break;
		case "article_img_1_upl_okarticle_img_2_upl_ok":
			return _MSG_ARTICLE_IMG_1_UP_OK."<br>"._MSG_ARTICLE_IMG_2_UP_OK;
			break;
		case "awards_del_ch":
			return _CLAN_AWARD_DELCHECK;
			break;
		case "bana_ok":
			return _MSG_BAN_ADD_OK;
			break;
		case "bane_ok":
			return _MSG_BAN_EDIT_OK;
			break;
		case "band_ok":
			return _MSG_BAN_DEL_OK;
			break;
		case "caa_ok":
			return _CLAN_AWARD_MSG_ADD_OK;
			break;
		case "cae_ok":
			return _CLAN_AWARD_MSG_EDIT_OK;
			break;
		case "cad_ok":
			return _CLAN_AWARD_MSG_DEL_OK;
			break;
		case "clan_game_del_ch":
			return _CLAN_GAME_DELCHECK;
			break;
		case "clan_maps_del_ch":
			return _CLAN_MAPS_DELCHECK;
			break;
		case "clan_setup_update_er":
			return _MSG_CLAN_SETUP_UPDATE_ER;
			break;
		case "clan_setup_update_ok":
			return _MSG_CLAN_SETUP_UPDATE_OK;
			break;
		case "compare_cat_add_ok":
			return _MSG_COMPARE_CAT_ADD_OK;
			break;
		case "compare_cat_edit_ok":
			return _MSG_COMPARE_CAT_EDIT_OK;
			break;
		case "compare_cat_del_ok":
			return _MSG_COMPARE_CAT_DEL_OK;
			break;
		case "compare_cat_in_db":
			return _MSG_COMPARE_CAT_IN_DB;
			break;
		case "compare_maker_add_ok":
			return _MSG_COMPARE_MAKER_ADD_OK;
			break;
		case "compare_maker_edit_ok":
			return _MSG_COMPARE_MAKER_EDIT_OK;
			break;
		case "compare_maker_del_ok":
			return _MSG_COMPARE_MAKER_DEL_OK;
			break;
		case "compare_part_add_ok":
			return _MSG_COMPARE_PART_ADD_OK;
			break;
		case "compare_part_edit_ok":
			return _MSG_COMPARE_PART_EDIT_OK;
			break;
		case "compare_part_del_ok":
			return _MSG_COMPARE_PART_DEL_OK;
			break;
		case "compare_part_in_db":
			return _MSG_COMPARE_PART_IN_DB;
			break;
		case "del_no":
			return _MSG_DEL_NO;
			break;
		case "del_ok":
			return _MSG_DEL_OK;
			break;
		case "dict_del_ch":
			return _DICTIONARY_DEL_CHECK;
			break;
		case "eden_img_edb":
			return _ERROR_DB;
			break;
		case "eden_img_eup":
			return _ERROR_UPLOAD;
			break;
		case "eden_img_upok":
			return _MSG_FTP_UP_OK;
			break;
		case "edit_no":
			return _MSG_EDIT_NO;
			break;
		case "edit_ok":
			return _MSG_EDIT_OK;
			break;
		case "editor_img_1_bad_ext":
			return _MSG_IMG_1_BAD_EXT;
			break;
		case "editor_img_2_bad_ext":
			return _MSG_IMG_2_BAD_EXT;
			break;
		case "editor_img_1_bad_size":
			return _MSG_IMG_1_BAD_SIZE;
			break;
		case "editor_img_2_bad_size":
			return _MSG_IMG_2_BAD_SIZE;
			break;
		case "editor_img_1_bad_file":
			return _MSG_IMG_1_BAD_FILE;
			break;
		case "editor_img_2_bad_file":
			return _MSG_IMG_2_BAD_FILE;
			break;
		case "fa_ok":
			return _MSG_FILTERS_ADD_OK;
			break;
		case "fe_ok":
			return _MSG_FILTERS_EDIT_OK;
			break;
		case "feu":
			return _ERROR_UPLOAD;
			break;
		case "fd_ok":
			return _MSG_FILTERS_DEL_OK;
			break;
		case "fni":
			return _MSG_FILE_NO_IMG;
			break;
		case "ftb":
			return _MSG_FILESIZE_TOO_BIG;
			break;
		case "gametype_del_ch":
			return _CLAN_GAMETYPE_DELCHECK;
			break;
		case "img_del_er":
			return _MSG_IMG_DEL_ER;
			break;
		case "img_del_ok":
			return _MSG_IMG_DEL_OK;
			break;
		case "img_del_oks":
			return _MSG_IMG_DEL_OKS;
			break;
		case "img_different_size":
			return _MSG_IMG_DIFF_SIZE;
			break;
		case "img_too_big":
			return _MSG_IMG_TOO_BIG;
			break;
		case "img_too_small":
			return _MSG_IMG_TOO_SMALL;
			break;
		case "img_up_er":
			return _MSG_IMG_UP_ER;
			break;
		case "img_up_er_no_name":
			return _MSG_IMG_UP_ER_NO_NAME;
			break;
		case "img_up_ok":
			return _MSG_IMG_UP_OK;
			break;
		case "lang_del_ch":
			return _LANG_DEL_CHECK;
			break;
		case "lang_replycode":
			return _LANG_ERR_REPLYCODE;
			break;
		case "lang_replyname":
			return _LANG_ERR_REPLNAME;
			break;
		case "league_allowed_players_generated_ok":
			return _MSG_LEAGUE_ALLOWED_PLAYERS_GEN_OK;
			break;
		case "league_allowed_players_generated_time_late":
			return _MSG_LEAGUE_ALLOWED_PLAYERS_GEN_TIME_LATE;
			break;
		case "league_award_add_er":
			return _MSG_LEAGUE_AWARD_ADD_ER;
			break;
		case "league_award_add_ok":
			return _MSG_LEAGUE_AWARD_ADD_OK;
			break;
		case "league_award_del_er":
			return _MSG_LEAGUE_AWARD_DEL_ER;
			break;
		case "league_award_del_ok":
			return _MSG_LEAGUE_AWARD_DEL_OK;
			break;
		case "league_award_edit_er":
			return _MSG_LEAGUE_AWARD_EDIT_ER;
			break;
		case "league_award_edit_ok":
			return _MSG_LEAGUE_AWARD_EDIT_OK;
			break;
		case "league_award_give_to_players_er":
			return _MSG_LEAGUE_AWARD_GIVE_TO_PLAYERS_ER;
			break;
		case "league_award_give_to_players_ok":
			return _MSG_LEAGUE_AWARD_GIVE_TO_PLAYERS_OK;
			break;
		case "league_award_give_to_teams_er":
			return _MSG_LEAGUE_AWARD_GIVE_TO_PLAYERS_ER;
			break;
		case "league_award_give_to_teams_ok":
			return _MSG_LEAGUE_AWARD_GIVE_TO_PLAYERS_OK;
			break;
		case "league_player_ban_add_all_ok":
			return _MSG_LEAGUE_PLAYER_BAN_ADD_ALL_OK;
			break;
		case "league_player_ban_ok":
			return _MSG_LEAGUE_PLAYER_BAN_OK;
			break;
		case "league_player_ban_er":
			return _MSG_LEAGUE_PLAYER_BAN_ER;
			break;
		case "league_player_ban_unban_ok":
			return _MSG_LEAGUE_PLAYER_BAN_UNBAN_OK;
			break;
		case "league_player_ban_ubnan_all_ok":
			return _MSG_LEAGUE_PLAYER_BAN_UNBAN_ALL_OK;
			break;
		case "league_player_ban_edit_ok":
			return _MSG_LEAGUE_PLAYER_BAN_EDIT_OK;
			break;
		case "league_player_ban_edit_er":
			return _MSG_LEAGUE_PLAYER_BAN_EDIT_ER;
			break;
		case "league_round_results_add_err":
			return _MSG_LEAGUE_ROUND_RESULTS_ADD_ERR;
			break;
		case "league_round_results_add_ok":
			return _MSG_LEAGUE_ROUND_RESULTS_ADD_OK;
			break;
		case "league_round_results_add_not_all":
			return _MSG_LEAGUE_ROUND_RESULTS_ADD_NOT_ALL;
			break;
		case "league_team_edit_er":
			return _MSG_LEAGUE_TEAM_EDIT_ER;
			break;
		case "league_team_edit_ok":
			return _MSG_LEAGUE_TEAM_EDIT_OK;
			break;
		case "league_team_hibernate_check":
			return _MSG_LEAGUE_TEAM_HIBERNATE_CHECK;
			break;
		case "league_team_hibernate_er":
			return _MSG_LEAGUE_TEAM_HIBERNATE_ER;
			break;
		case "league_team_hibernate_ok":
			return _MSG_LEAGUE_TEAM_HIBERNATE_OK;
			break;
		case "league_hibernate_players_in_team":
			return _MSG_LEAGUE_TEAM_HIBERNATE_PLAYERS_IN_TEAM;
			break;
		case "nep":
			return _NOTENOUGHPRIV;
			break;
		case "news_add_ok":
			return _MSG_NEWS_ADD_OK;
			break;
		case "news_del_ok":
			return _MSG_NEWS_DEL_OK;
			break;
		case "news_edit_ok":
			return _MSG_NEWS_EDIT_OK;
			break;
		case "no_ftp":
			return _MSG_NO_FTP;
			break;
		case "poll_add_ok":
			return _MSG_POLL_ADD_OK;
			break;
		case "poll_edit_ok":
			return _MSG_POLL_EDIT_OK;
			break;
		case "poll_del_ok":
			return _MSG_POLL_DEL_OK;
			break;
		case "res_words_del_ch":
			return _RES_WORDS_DEL_CHECK;
			break;
		case "rss_del_er":
			return _MSG_RSS_DEL_ER;
			break;
		case "rss_del_ok":
			return _MSG_RSS_DEL_OK;
			break;
		case "setup_update_er":
			return _MSG_SETUP_UPDATE_ER;
			break;
		case "setup_update_ok":
			return _MSG_SETUP_UPDATE_OK;
			break;
		case "shop_seller_act_db_er":
			return _MSG_SHOP_SELLER_ACT_DB_ER;
			break;
		case "shop_seller_act_er":
			return _MSG_SHOP_SELLER_ACT_ER;
			break;
		case "shop_seller_act_ok":
			return _MSG_SHOP_SELLER_ACT_OK;
			break;
		case "tag_add_ok":
			return _MSG_TAGS_ADD_OK;
			break;
		case "tag_del_ok":
			return _MSG_TAGS_DEL_OK;
			break;
		case "tag_edit_ok":
			return _MSG_TAGS_EDIT_OK;
			break;
		case "tag_exist":
			return _MSG_TAGS_EXIST;
			break;
		case "ue":
			return _ERROR_UPLOAD;
			break;
		case "up_ok":
			return _MSG_UP_OK;
			break;
		case "up_no":
	  		return _MSG_UP_NO;
			break;
		case "wft":
			return _MSG_WRONG_FILETYPE;
			break;
		default:
			return "";
	}
}
/***********************************************************************************************************
*
*		LeagueGenerateListAllowedPlayers
*
*		Vygeneruje seznam povolenych hracu, co smeji hrat ligu a ulozi ho
*		Funkce vraci - league_allowed_players_generated_ok / league_allowed_players_generated_time_late
*		$lid		= League ID
*		$sid		= League Season ID
*		$rid		= League Season Round ID
*
***********************************************************************************************************/
function LeagueGenerateListAllowedPlayers($lid = 0, $sid = 0, $rid = 0) {
	
	global $db_admin,$db_admin_guids,$db_league_seasons_rounds,$db_league_seasons_round_allowed_players,$db_league_teams_sub_leagues,$db_league_leagues,$db_league_teams,$db_league_players;
	
	if ($lid == 0 || $sid == 0 || $rid == 0){ return "generated_no_data"; exit;}
	// Nejdrive smazeme vsechny zaznamy, ktere byly vygenerovany pro danou ligu a sezonu a kolo drive
	$res_round = mysql_query("SELECT league_season_round_date FROM $db_league_seasons_rounds WHERE league_season_round_id=".(float)$_GET['rid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_round = mysql_fetch_array($res_round);
	$today = date("Y-m-d H:i:s");
	// Proverime zda uz neni po uzaverce kola - pokud je, nic se uz neuklada
	if (EdenGetMkTime($ar_round['league_season_round_date'], "Datetime") > EdenGetMkTime($today, "Datetime")){
			mysql_query("DELETE FROM $db_league_seasons_round_allowed_players WHERE league_season_round_allowed_player_league_id=".(float)$_GET['lid']." AND league_season_round_allowed_player_season_id=".(float)$_GET['sid']." AND league_season_round_allowed_player_season_round_id=".(float)$_GET['rid']." AND league_season_round_allowed_player_date<'".mysql_real_escape_string($ar_round['league_season_round_date'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
		$res_team = mysql_query("
		SELECT lt.league_team_id, ll.league_league_id, ll.league_league_game_id, ll.league_league_team_sub_max_players, ll.league_league_team_sub_min_players, ltsl.league_teams_sub_league_team_sub_id, ltsl.league_teams_sub_league_players  
		FROM $db_league_teams_sub_leagues AS ltsl 
		JOIN $db_league_leagues AS ll ON ll.league_league_id=ltsl.league_teams_sub_league_league_id 
		JOIN $db_league_teams AS lt ON lt.league_team_id=ltsl.league_teams_sub_league_team_id 
		WHERE ltsl.league_teams_sub_league_league_id=".(float)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar_team = mysql_fetch_array($res_team)){
			/* Spocitame kolik je aktivnich hracu v teamu pro danou ligu */
			$league_allowed_player_id = explode("#",$ar_team['league_teams_sub_league_players'],-1);
			$league_allowed_player_num = count($league_allowed_player_id);
			if (($ar_team['league_league_team_sub_max_players'] == 0 && $ar_team['league_league_team_sub_min_players'] == 0) || ($league_allowed_player_num <= $ar_team['league_league_team_sub_max_players'] && $league_allowed_player_num >= $ar_team['league_league_team_sub_min_players'])){
				$res_player = mysql_query("
				SELECT a.admin_id, ag.admin_guid_guid, lp.league_player_id 
				FROM $db_league_players AS lp 
				JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
				JOIN $db_admin_guids AS ag ON ag.aid=lp.league_player_admin_id AND ag.admin_guid_league_guid_id=".(float)$ar_team['league_league_id']." 
				WHERE lp.league_player_team_id=".(float)$ar_team['league_team_id']." AND lp.league_player_game_id=".(float)$ar_team['league_league_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_player = mysql_fetch_array($res_player)){
					if (in_array($ar_player['league_player_id'],$league_allowed_player_id)){
						mysql_query("INSERT INTO $db_league_seasons_round_allowed_players VALUES(
						'', 
						'".(float)$_GET['lid']."', 
						'".(float)$_GET['sid']."', 
						'".(float)$_GET['rid']."', 
						'".(float)$ar_player['admin_id']."', 
						'".(float)$ar_player['league_player_id']."', 
						'".(float)$ar_team['league_team_id']."', 
						'".(float)$ar_team['league_teams_sub_league_team_sub_id']."', 
						'".mysql_real_escape_string($ar_player['admin_guid_guid'])."', 
						NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
				}
			}
		}
		return "league_allowed_players_generated_ok";
		exit;
	} else {
		return "league_allowed_players_generated_time_late";
		exit;
	}
}
/***********************************************************************************************************
*
*		CheckExtension
*
*		Proveri zda nahravany soubor je obrazek
*		Podporovane typy obrazku - jpg, gif, png
*
***********************************************************************************************************/
function CheckImgExtension($source_file){
	
	$size = getimagesize($source_file);
	/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
	if ($size[2] == 1){
		return ".gif";
	} elseif ($size[2] == 2){
		return ".jpg";
	} elseif ($size[2] == 3){
		return ".png";
	} else {
		return false;
	}
}
/***********************************************************************************************************
*
*		GetFilesizeInKB
*
*		Vrati velikost souboru v KB je li vetsi nez 1024 B
*
***********************************************************************************************************/
function GetFilesizeInKB($file_size = false){
	if ($file_size == true){
		if ($file_size < 1024){
			return $file_size." B";
		} else {
			$kb = $file_size / 1024;
			return round($kb, 2)." KB";
		}
	}
}
/***********************************************************************************************************
*
*		SimpleImage
*
*		Trida pro zmenu velikosti obrazku
*
***********************************************************************************************************/
class SimpleImage {
	
	var $image;
	var $image_info;
	var $image_type;
 	var $conn_id;
	var $ftp_path_images_upl;
	var $db_admin_images;
	var $adminid;
	
	function load($filename) {
		$this->image_info = getimagesize($filename);
		$this->image_type = $this->image_info[2];
		if( $this->image_type == 2){ // JPG
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == 1){ // GIF
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == 3){ // PNG
			$this->image = imagecreatefrompng($filename);
			// Load a png image with alpha channels
			// Do required operations
			// Turn off alpha blending and set alpha flag
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
		}
	}
	function save($filename, $compression=75, $permissions=null) {
		
	 	if( $image_type == 2){
			imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == 1){
			imagegif($this->image,$filename);
		} elseif( $image_type == 3){
			imagepng($this->image,$filename);
		}
		if( $permissions != null) {
			chmod($filename,$permissions);
		}
	}
	/*
		$thumbnail		- Zpracovani thumbnailu (pro katalog)
		$compression	- Hodnota komprese obrazku
		$img_name		- Nazev obrazku (pokud uz ho mame)
		$db				- Ulozit do databaze ano/ne
	*/
	function saveByFTP($thumbnail = 0, $compression = 75, $img_name = 0, $db = 1){
		
		global $db_admin_images;
		global $ftp_path_images_upl,$eden_cfg;
		global $conn_id,$adminid;
		
		if ($img_name == 0){$img_name = Cislo();}
		if ($this->image_type == 2){
			$extenze = "jpg";
			$userfile_name = $img_name.".".$extenze;
			imagejpeg($this->image,$eden_cfg['dir_temp'].$userfile_name);
		} elseif ($this->image_type == 1){
			$extenze = "gif";
			$userfile_name = $img_name.".".$extenze;
			imagegif($this->image,$eden_cfg['dir_temp'].$userfile_name);
		} elseif ($this->image_type == 3){
			$extenze = "png";
			$userfile_name = $img_name.".".$extenze;
			imagepng($this->image,$eden_cfg['dir_temp'].$userfile_name);
		}
		// Vlozi nazev souboru a cestu do konkretniho adresare a uploadne obrazek
		$destination_file = $ftp_path_images_upl.$userfile_name;
		//echo "-".$ftp_path_images_upl." - ".$destination_file." - ".$eden_cfg['dir_temp'].$userfile_name;exit;
		//echo "<script type=\"text/javascript\">";
		//echo "alert('".$ftp_path_images_upl." - ".$destination_file." - ".$eden_cfg['dir_temp'].$userfile_name."');";
		//echo "</script>";
		$upload = ftp_put($conn_id, $destination_file, $eden_cfg['dir_temp'].$userfile_name, FTP_BINARY);
		
		// Kdyz se upload obrazku podari
		if ($upload && $db == 1){
			if ($_POST['mode'] == "main"){
				$image_mode = 2;
			} else {
				$image_mode = 1;
			}
			
			// Ulozime zaznam o souboru do databaze
			$file_size = ftp_size($conn_id,$ftp_path_images_upl.$userfile_name);
			$res = mysql_query("INSERT INTO $db_admin_images VALUES('".(integer)$adminid."','','".mysql_real_escape_string($userfile_name)."','".(float)$image_mode."','".(float)$this->getWidth()."','".(float)$this->getHeight()."','".(float)$file_size."','".mysql_real_escape_string($extenze)."','0','".mysql_real_escape_string($image_description)."',NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
			if ($thumbnail == 1){
				$width = 150;
				$height = 150;
				if ($this->image_info[0] < $width && $this->image_info[1] < $height){
					// Obrazek je mensi nez sirka a vyska
					// Nestane se nic
				} elseif ($this->image_info[0] > $width && $this->image_info[1] > $height) {
					if ($this->image_info[0] > $this->image_info[1]){
						// Kdyz je sirka vetsi nez vyska zmensi se na max sirku
						$this->resizeToWidth($width);
					} else {
						// Jinak se zmensi na max vysku
						$this->resizeToHeight($height);
					}
				} elseif ($this->image_info[0] > $width) {
					// Obrazek je sirsi nez zvolena sirka thumbnailu
					// Obrazek se zmensi na zvolenou sirku thumbnailu (pri zachovani pomeru stran)
					$this->resizeToWidth($width);
				} elseif ($this->image_info[1] > $height) {
					// Obrazek je vyssi nez zvolena vyska thumbnailu
					// Obrazek se zmensi na zvolenou vysku thumbnailu (pri zachovani pomeru stran)
					$this->resizeToHeight($height);
				}
				if ($this->image_type == 2){
					$userfile_name_thumbnail = $img_name.".".$extenze;
					imagejpeg($this->image,$eden_cfg['dir_temp'].$userfile_name_thumbnail);
				} elseif ($this->image_type == 1){
					$userfile_name_thumbnail = $img_name.".".$extenze;
					imagegif($this->image,$eden_cfg['dir_temp'].$userfile_name_thumbnail);
				} elseif ($this->image_type == 3){
					$userfile_name_thumbnail = $img_name.".".$extenze;
					imagepng($this->image,$eden_cfg['dir_temp'].$userfile_name_thumbnail);
				}
				// Vlozi nazev souboru a cestu do konkretniho adresare
				$destination_file_thumbnail = $ftp_path_images_upl."_thumb/".$userfile_name_thumbnail;
				$upload_t = ftp_put($conn_id, $destination_file_thumbnail, $eden_cfg['dir_temp'].$userfile_name_thumbnail, FTP_BINARY);
				
				if ($upload_t){
					// Pokud se upload thumbnailu podari
					$res_t = mysql_query("UPDATE $db_admin_images SET admin_image_thumb=1 WHERE admin_image_name='".mysql_real_escape_string($userfile_name)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				} else {
					// Pokud se upload thumbnailu nepodari
				}
			}
			//unlink($eden_cfg['dir_temp'].$userfile_name);
		} else {
			//Zatim nic
		}
	}
	function output() {
		if($this->image_type == 2){
			imagejpeg($this->image);
		} elseif($this->image_type == 1){
			imagegif($this->image);
		} elseif($this->image_type == 3){
			imagepng($this->image);
		}
	}
	function getWidth() {
		return imagesx($this->image);
	}
	function getHeight() {
		return imagesy($this->image);
	}
	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
			$this->resize($width,$height);
	}
	function scale($scale) { // Zmeni rozmery procentualne
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}
	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}
/***********************************************************************************************************
*
*		FtpCheckDirArray
*
*		Proveruje zda je adresar vytvoren, pokud ne vytvori se. Pri problemech vraci chybovou hlasku
*
***********************************************************************************************************/
function FtpCheckDirArray($ftp_array = false){
	
	global $eden_cfg;
	
	if ($ftp_array == false || is_array($ftp_array) == false){ echo _FTP_CHECK_MISSING_DIRS;exit;}
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { _ERROR_FTP;}
	
	$ftp_array_num = count($ftp_array);
	$i=0;
	while ($i < $ftp_array_num){
		//echo "<br><br>(1) ".$ftp_array[$i][0]." - ".$ftp_array[$i][1]."<br>";
		if ($ftp_array[$i][0] == "ftp_up"){
			@ftp_cdup($conn_id);
		} elseif ($ftp_array[$i][0] != "ftp_up"){
			// echo $conn_id."(2-1) ".$ftp_array[$i][0]."<br>";
			@ftp_chdir($conn_id, $ftp_array[$i][0]);
			// echo "(2) Current directory: " . ftp_pwd($conn_id) . "\n<br />";
			// try to change the directory to somedir
			if (@ftp_chdir($conn_id, $ftp_array[$i][1])) {
				// echo "(3) Current directory is now: " . ftp_pwd($conn_id) . "\n<br />";
				@ftp_cdup($conn_id);
			} else { 
				// echo "(4) Couldn't change directory\n<br />";
				// try to create the directory $dir
				if (@ftp_mkdir($conn_id, $ftp_array[$i][1])) {
					echo _FTP_CHECK_DIR.$ftp_array[$i][1]._FTP_CHECK_DIR_CREATED."<br><br>";
				} else {
					echo _FTP_CHECK_DIR_PROB.$ftp_array[$i][1]."<br><br>";
				}
			}
		}
		$i++;
	}
	ftp_close($conn_id);
}
/***********************************************************************************************************
*																											
*		IMAGE MANAGER																						
*																											
*		$mode	-	avatar,category,link_1,link_2,article_1,article_2,profile_1,profile_2,league_team_logo,		
*					league_award,smiles,todo,games_1,games_2,smiles,rss										
*																											
***********************************************************************************************************/
function EdenSysImageManager(){
	
	global $url_category,$url_league_awards,$url_games,$url_smiles;
	global $eden_cfg;
	global $ftp_path_category,$ftp_path_games,$ftp_path_league_awards,$ftp_path_smiles;
	
	echo Menu();
	
	switch($_GET['action']){
		case "clan_game_img_upload":
		case "clan_game_img_del":
			$priv = CheckPriv("groups_clan_games_add");
			$priv_del = CheckPriv("groups_clan_games_del");
			$ftp_path = $ftp_path_games;
			$url = $url_games;
			$mode = "games_1";
			$file_help = GetSetupImageInfo($mode,"width")." x ".GetSetupImageInfo($mode,"height")."px "._CMN_AND." ".GetSetupImageInfo($mode,"filesize")."B - "._CLAN_GAMES_ONLY_GIF."<br />"._CLAN_GAMES_IMAGES_INFO;
			$img_title = _CLAN_GAMES_IMAGES;
			break;
		case "category_img_upload":
		case "category_img_del":
			$priv = CheckPriv("groups_cat_ul");
			$priv_del = CheckPriv("groups_cat_del");
			$ftp_path = $ftp_path_category;
			$url = $url_category;
			$mode = "category";
			$file_help = GetSetupImageInfo($mode,"width")." x ".GetSetupImageInfo($mode,"height")."px "._CMN_AND." ".GetSetupImageInfo($mode,"filesize")."B<br />"._CAT_IMAGES_SHARE_INFO;
			$img_title = _CAT_IMAGES;
			break;
		case "league_award_img_upload":
		case "league_award_img_del":
			$priv = CheckPriv("groups_league_add");
			$priv_del = CheckPriv("groups_league_del");
			$ftp_path = $ftp_path_league_awards;
			$url = $url_league_awards;
			$mode = "league_award";
			$file_help = GetSetupImageInfo($mode,"width")." x ".GetSetupImageInfo($mode,"height")."px "._CMN_AND." ".GetSetupImageInfo($mode,"filesize")."B";
			$img_title = _LEAGUE_AWARDS_IMAGES;
			break;
		case "smiles_img_upload":
		case "smiles_img_del":
			$priv = CheckPriv("groups_smiles_ul");
			$priv_del = CheckPriv("groups_smiles_del");
			$ftp_path = $ftp_path_smiles;
			$url = $url_smiles;
			$mode = "smiles";
			$file_help = _CMN_MAX.": ".GetSetupImageInfo($mode,"width")." x ".GetSetupImageInfo($mode,"height")."px "._CMN_AND." ".GetSetupImageInfo($mode,"filesize")."B";
			$img_title = _SMILES_IMAGES;
			break;
		default:
			$priv = 0;
			$priv_del = 0;
			echo _NOTENOUGHPRIV;
			exit;
	}
	
	if ($priv != 1){echo _NOTENOUGHPRIV;exit;}
	
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	/* Odstraneni vybranych obrazku */
	if ($_POST['confirm'] == "true" && $_GET['action'] == "category_img_del"){
		if ($priv_del <> 1){echo _NOTENOUGHPRIV;exit;}
		$img_num = count($_POST['img_data']);
		$i = 0;
		while ($i < $img_num){
			$img = $_POST['img_data'][$i];
			if (ftp_delete($conn_id, $ftp_path.$img)) {
				echo _CAT_IMAGE." ".$img." "._CAT_IMAGE_DEL_OK."<br>\n";
			} else {
				echo _CAT_IMAGE_DEL_ER." ".$img.".<br>\n";
			}
			$i++;
		}
	}
	
	$d = ftp_nlist($conn_id, $ftp_path);
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action=eden_img_upload&amp;mode=".$_GET['action']."&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\"><strong>"._CMN_IMAGE."</strong></td>\n";
	echo "		<td><input type=\"file\" name=\"eden_image\" size=\"50\"> <br>".$file_help."\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
	echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>".$img_title."</strong></td>\n";
	echo "		<td>"; if (CheckPriv("groups_cat_del") == 1){ echo "<form action=\"sys_save.php?action=eden_img_del&amp;mode=".$mode."_img_del&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\">"; }
		$x = 0;
		while($entry = $d[$x]) {
			$x++;
			$entry = str_ireplace ($ftp_path,"",$entry);//Odstrani cestu k ftp adresari
			if ($entry != "." && $entry != ".." && $entry != "AllTopics.gif") {
				echo "<img src=\"".$url.$entry."\" style=\"margin:5px;\" title=\"".$entry."\" align=\"middle\">"; if ($priv == 1){echo "<input type=\"checkbox\" name=\"img_data[]\" value=\"".$entry."\">";} echo "&nbsp;".$entry."<br>";
			}
		}
		if (CheckPriv("groups_cat_del") == 1){
			echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
			echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
			echo "	<input type=\"submit\" value=\""._CMN_DEL_CHOOSEN."\">\n";
			echo "</form>\n";
		}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		EdenCategorySelect																			
*																											
*		$mode	-	Show all categories for given mode (adds, articles, download, links, news, shop, stream)
*																											
***********************************************************************************************************/
function EdenCategorySelect ($cat_id, $cat_mode, $cat_name, $cat_first_value = 0){
	switch ($cat_mode){
		case "adds":
			$mode = "category_adds";
			break;
		case "articles":
			$mode = "category_articles";
			break;
		case "download":
			$mode = "category_download";
			break;
		case "links":
			$mode = "category_links";
			break;
		case "news":
			$mode = "category_news";
			break;
		case "shop":
			$mode = "category_shop";
			break;
		case "stream":
			$mode = "category_stream";
			break;
		default:
			return "";
			exit;
	}
	$res2 = mysql_query("SELECT category_id, category_name FROM "._DB_CATEGORIES." WHERE ".$mode."=1 AND category_parent=0 AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$output = "<select name=\"".$cat_name."\">";
	if ($cat_first_value != ""){
		$output .= "<option value=\"\" selected=\"selected\">".$cat_first_value."</option>";
	}
	while ($ar2 = mysql_fetch_array($res2))	{
		$cat = $ar2['category_name'];
		$output .= "<option value=\"".$ar2['category_id']."\" ";
		if ($ar2['category_id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
		$output .= ">".$cat."</option>";
		$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM "._DB_CATEGORIES." WHERE category_parent=".(float)$ar2['category_id']." AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($arr2 = mysql_fetch_array($ress2)){
			if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
			$output .= "<option value=\"".$arr2['category_id']."\" ";
			if ($arr2['category_id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
			$output .= ">".$cat2."</option>";
			$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM "._DB_CATEGORIES." WHERE category_parent=".(float)$arr2['category_id']." AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($arr3 = mysql_fetch_array($ress3)){
				if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
				$output .= "<option value=\"".$arr3['category_id']."\" ";
				if ($arr3['category_id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
				$output .= ">".$cat3."</option>";
				$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM "._DB_CATEGORIES." WHERE category_parent=".(float)$arr3['category_id']." AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($arr4 = mysql_fetch_array($ress4)){
					if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
					$output .= "<option value=\"".$arr4['category_id']."\" ";
					if ($arr4['id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
					$output .= ">".$cat4."</option>";
					$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM "._DB_CATEGORIES." WHERE category_parent=".(float)$arr4['category_id']." AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($arr5 = mysql_fetch_array($ress5)){
						if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
						$output .= "<option value=\"".$arr5['category_id']."\" ";
						if ($arr5['category_id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
						$output .= ">".$cat5."</option>";
						$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM "._DB_CATEGORIES." WHERE category_parent=".(float)$arr5['category_id']." AND category_active=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($arr6 = mysql_fetch_array($ress6)){
							if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
							$output .= "<option value=\"".$arr6['category_id']."\" ";
							if ($arr6['category_id'] == (integer)$cat_id) { $output .= "selected=\"selected\"";}
							$output .= ">".$cat6."</option>";
						}
					}
				}
			}
		}
	}
	$output .= "</select>";
	return $output;
}