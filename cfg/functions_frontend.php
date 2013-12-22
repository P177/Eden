<?php
require_once("eden_league.php");
require_once("functions_Common.php");			  				/*	Common functions for FrontEnd and Back End */
require_once("functions_frontend_Calendar.php");				/*	Calendar, VyberRoku, VyberMesice, VyberDne, VyberHodiny */
require_once("functions_frontend_Clanwars.php");				/*	Clanwars				-	CLANWARS */
require_once("functions_frontend_Comments.php");		   		/*	Comments 			-	KOMENTARE */
require_once("functions_frontend_Download.php");				/*	Download				-	DOWNLOAD */
require_once("functions_frontend_Dictionary.php");				/*	Dictionary, DictionaryWhatMeans - Slovnik cizich slov, Zobrazeni nahodneho slova ze slovniku */
require_once("functions_frontend_FlagName.php");				/*	NazevVlajky */
require_once("functions_frontend_GuestBook.php");				/*	Guestbook, GuestBookAdmin */
require_once("functions_frontend_Msg.php");						/*	Msg						-	CHYBOVE HLASKY  */
require_once("functions_frontend_MtG.php");						/*	Magic the Gathering */
require_once("functions_frontend_Poll.php");					/*	Poll, OlderPoll */
require_once("functions_frontend_PostEditor.php");				/*	BB_to_HTML_Code (translate BB code to HTML), PostEditor (BB editor for Forum)  */
require_once("functions_frontend_RSS.php");						/*	MakeRSS, MakeRSSLink, MakeRSSiTunes  */
require_once("functions_frontend_Search.php");					/*	Search (Vyhledavani - Formular), SearchRes (Vyhledavani - Vysledek) */
require_once("functions_frontend_Streams.php");					/*	Streams - Check online/offline streams */
require_once("functions_frontend_Tournament.php");					/*	Streams - Check online/offline streams */
require_once("functions_frontend_UserEdit.php");				/*	UserEdit				-	EDITACE UZIVATELU */
/*********************************************************************************************************
*
*		SEZNAM FUNKCI
*
*		CheckPriv				-	KONTROLA OPRAVNENI K VYKONANI RUZNYCH PRIKAZU
*		FormatDatetime			-	ZFORMATOVANI CASU Z Datetime
*		FormatTimestamp			-	NASTAVENI DATUMU Z FORMATU 20040701305959
*		FormatTime				-	ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*		FormatDate				-	
*		EdenGetMkTime			-	Ziskani mktime z Timestampu nebo Datetime
*		FormatDateOnLang		-	ZMENA NAZVU DNE V DATUMU
*		Odkazy					-	ODKAZY
*		OdkazyKat				-	ODKAZY KATEGORIE PODLE ID
*		OdkazySamotne			-	ODKAZY SAMOTNE
*		OdkazySamotneId			-	ODKAZY SAMOTNE PODLE ID
*		Reklama					-	REKLAMA
*		Cislo					-	NEZAMENITELNE CISLO - SEKUNDY A MILISEKUNDY
*		Login					-	LOGIN
*		CustomLogin				-	CUSTOM LOGIN - Login ktery se vrati na pozadovanou stranku pomoci parametru $mode
*		ForgottenPass			-	FORGOTEN PASSWORD
*		Logout					-	LOGOUT
*		ZobrazeniPoslKomentaru	-	ZOBRAZENI NEJNOVEJSICH KOMENTARU
*		ZobrazeniPoslKomentaru2	-	ZOBRAZENI NEJNOVEJSICH KOMENTARU
*		NejctenejsiClanek		-	NEJCTENEJSI CLANEK
*		ShowBest				-	ZOBRAZENI NEJLEPSICH CLANKU
*		Zobrazeni				-	ZOBRAZENI CLANKU
*		ZobrazeniSer			-	ZOBRAZENI PRISPEVKU S MOZNOSTI URCENI POSLOUPNOSTI
*		ZobrazeniNov			-	ZOBRAZENI CLANKU
*		ZobrazeniNovFirst		-	ZOBRAZENI FIRST CLANKU
*		ZobrazeniNovFirstSeznam	-	ZOBRAZENI SEZNAMU FIRST CLANKU
*		ZobrazeniNovArchiv		-	ZOBRAZENI CLANKY ARCHIV
*		ZobrazeniClankyArchiv	-	ZOBRAZENI CLANKY ARCHIV
*		ZobrazeniClankyArchiv2	-	ZOBRAZENI CLANKY ARCHIV
*		News				-	NEWS
*		ZobrazeniAktArchiv		-	ZOBRAZENI NEWS ARCHIV
*		ZobrazeniA				-	ZOBRAZENI PRISPEVKU
*		ZobrazeniCl				-	ZOBRAZENI CLANKU V DANE KATEGORII
*		Clanek					-	ZOBRAZENI CLANKU
*		Aktualita				-	ZOBRAZENI AKTUALITA
*		AktulityList			-	SEZNAM AKTUALIT
*		ClanekIframe			-	ZOBRAZENI CLANKU
*		Nadpis					-	NADPIS
*		ZobrazeniKategorii		-	ZOBRAZENI KATEGORII
*		PridejOdkaz				-	PRIDANI ODKAZU
*		Archiv					-	ARCHIV
*		ArchivKalendar			-	KALENDAR A ARCHIV
*		ShowArchivKalendar		-	ZOBRAZENI VYPISU
*		DateLink				-	ODKAZ V KALENDARI
*		DateLinkArchiv			-	ODKAZ V KALENDARI
*		UserBan					-	USER BAN
*		AllowReg				-	ZOBRAZENI OZNAMENI O AKTIVACI UCTU
*		AllowChangeEmail		-	ZOBRAZENI OZNAMENI O ZMENE EMAILU
*		ArticlesList			-	SEZNAM CLANKU
*		ChannelList				-	SEZNAM CLANKU VE VYBRANEM KANALE
*		ZobrazeniAdminTeam		-	ZOBRAZENI TEAMU
*		CheckUser				-	KONTROLA OPRAVNENI
*		TransToASCII			-	FUNCTION - TRANSFORM TO ASCII
*		GameServers				-	GAME SERVERS
*		WhoIsOnline				-	FUNCTION - WHO IS ONLINE
*		ShowPodcasts			-	Zobrazi podcasty
*		WrappedText				-	CLASS - WRAPPED TEXT
*		MyCeil					-	ZAOKROUHENI NAHORU
*		CheckEmail				-	CHECK EMAIL
*		CheckActiveStreamChannel-	Check if streamed channel is ON or OFF
*		ContactForm				-	CONTACT FORM
*		GetNick					-	GET NICKNAME FROM ADMIN_ID
*		IsFriend				-	Zjisteni zda je dany uzivatel obsazen v pratelich
*		ClanAwards				-	Zobrazeni oceneni
*		PokerHandsAdd			-	Pridani pokerove hry
*		PokerHandsShow			-	Zobrazeni pokerove hry
*		RecommendToFriend		-	Doporucit priteli
*		CheckSkin				-	Zobrazeni aktualniho skinu
*		StripInetService		-	ODSTRANENI SLUZEB Z ODKAZU
*		WebTermsAgreemed		-	Zobrazi formular pro souhlas s pravidly a podminkami na webu
*   	textHighlighter       	-	Zvyrazneni hledaneho slova
*		GetFilesizeInKB 		-	Vrati velikost souboru v KB je li vetsi nez 1024 B
*		SimpleImage 			-	Trida pro zmenu velikosti obrazku
*		MetaOG					-	Zobrazi metainformace pro FaceBook (musi byt umisteno v hlavicce a aktivovano jen u samostatneho zobrazeni clanku
*		CupReg					-	Zobrazi registracni formular do Cupu
*		EmailOptOut				-	Odhlaseni z rozesilani emailu (nesouhlas s prijmem reklamnich emailu)
*
***********************************************************************************************************/
/***********************************************************************************************************
*
*		VYTVORENI SPOJENI NA SQL SERVER
*
***********************************************************************************************************/
$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_cl_uname'], $eden_cfg['db_cl_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
mysql_select_db($eden_cfg['db_name']);
if ($eden_cfg['db_collate'] != ""){mysql_query($eden_cfg['db_collate']);}
if ($eden_cfg['db_collate_connection'] != ""){mysql_query($eden_cfg['db_collate_connection']);}
if ($eden_cfg['db_encode'] != ""){mysql_query($eden_cfg['db_encode']);}
/***********************************************************************************************************
*
*		KONTROLA OPRAVNENI K VYKONANI RUZNYCH PRIKAZU
*
***********************************************************************************************************/
function CheckPriv($mytable,$uname = ''){
	
	global $db_admin,$db_groups;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if($uname == ""){if ($_SESSION['loginid'] == "") {return 0;} else {$loginid = $_SESSION['loginid'];}} else {$loginid = $uname;}
	$chu = mysql_query("SELECT admin_priv FROM $db_admin WHERE admin_id=".(integer)$loginid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$usr = mysql_fetch_array($chu);
	$chk = mysql_query("SELECT * FROM $db_groups WHERE groups_id=".$usr['admin_priv']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$prv = mysql_fetch_array($chk);
	return $prv[$mytable];
}

/***********************************************************************************************************
*
*		 ZFORMATOVANI CASU Z Datetime
*
***********************************************************************************************************/
function FormatDatetime($time,$format = "d.m.Y H:i:s"){

	global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		NASTAVENI DATUMU Z FORMATU 20040701305959
*
***********************************************************************************************************/
function FormatTimestamp($time,$format = "d.m.Y"){

	global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", $time, $datetime);
		$datetime = date($format, mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z FUNKCE time() NA POUZITI V EDITORU
*
***********************************************************************************************************/
function FormatTime($time, $format = "YmdHis"){

	global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		$datetime = date($format, $time);
		return($datetime);
	}
}
/***********************************************************************************************************
*
*		ZFORMATOVANI CASU Z formatu Date (1999-12-29)
*
***********************************************************************************************************/
function FormatDate($time, $format = "d.m.Y"){

	 global $datetime;

	if ($time == 0){
		return(_NONEDIT);
	} else {
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $time, $datetime);
		$datetime = date($format, mktime(0,0,0,$datetime[2],$datetime[3],$datetime[1]));
		return($datetime);
	}
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
		preg_match ("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", $date, $datetime);
		$datetime = mktime(AGet($datetime,4),AGet($datetime,5),AGet($datetime,6),AGet($datetime,2),AGet($datetime,3),AGet($datetime,1));
	}
	if ($format == "Datetime"){
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $datetime);
		$datetime = mktime(AGet($datetime,4),AGet($datetime,5),AGet($datetime,6),AGet($datetime,2),AGet($datetime,3),AGet($datetime,1));
	}
	return $datetime;
}
/***********************************************************************************************************
*
*		ZMENA NAZVU DNE V DATUMU
*
***********************************************************************************************************/
function FormatDateOnLang($day_name){

	$day_name = str_ireplace("Monday", _PONDELI, $day_name);
	$day_name = str_ireplace("Tuesday", _UTERY, $day_name);
	$day_name = str_ireplace("Wednesday", _STREDA, $day_name);
	$day_name = str_ireplace("Thursday", _CTVRTEK, $day_name);
	$day_name = str_ireplace("Friday", _PATEK, $day_name);
	$day_name = str_ireplace("Saturday", _SOBOTA, $day_name);
	$day_name = str_ireplace("Sunday", _NEDELE, $day_name);
	return($day_name);
}
/***********************************************************************************************************
*
*		ODKAZY
*
***********************************************************************************************************/
function Odkazy(){
	
	global $db_category,$db_links;
	global $url_links;
	global $eden_cfg;
	
	$res = mysql_query("SELECT links_name, links_link, links_description FROM $db_links") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res2 = mysql_query("SELECT category_name, category_shows FROM $db_category WHERE category_links=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar2 = mysql_fetch_array($res2)){
		// Nacteni sablony
		include "templates/tpl.odkazy.php";
	}
}
/***********************************************************************************************************
*
*		ODKAZY KATEGORIE PODLE ID
*
*		$cat_id 	= ID kategorie
*		$cat_ver	= verze sablony
*
***********************************************************************************************************/
function OdkazyKat($cat_id, $cat_ver = 1){
	
	global $db_category,$db_links;
	global $eden_cfg;
	global $url_links;
	
	$res2 = mysql_query("SELECT category_id, category_name, category_shows FROM $db_category WHERE category_parent=".(integer)$cat_id." AND category_links=1 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar2 = mysql_fetch_array($res2)){
		$res = mysql_query("SELECT links_id, links_name, links_link, links_description, links_gfx, links_picture, links_picture2 FROM $db_links WHERE links_category_id=".$ar2['category_id']." AND links_main=0 AND links_publish=1 ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		// Nacteni sablony
		include "templates/tpl.odkazy_kat_".$cat_id."_".$cat_ver.".php";
	}
}
/***********************************************************************************************************
*
*		ODKAZY SAMOTNE
*
***********************************************************************************************************/
function OdkazySamotne(){
	
	global $db_category,$db_links;
	global $url_links;
	global $project;
	
	$res = mysql_query("SELECT links_id, links_gfx, links_picture, links_link, links_name FROM $db_links WHERE links_gfx=1 AND links_list=1 ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$links_id = $ar['links_id'];
		$links_gfx = $ar['links_gfx'];
		$links_picture = $ar['links_picture'];
		$links_link = $ar['links_link'];
		$links_name = $ar['links_name'];
		/* Nacteni sablony */
		include "templates/tpl.odkazy_samotne.php";
	}
}
/***********************************************************************************************************
*
*		ODKAZY SAMOTNE PODLE ID
*
*		$link_d 	= ID kategorie
*		$ver	= verze sablony
*
***********************************************************************************************************/
function OdkazySamotneId($link_id, $cat_ver = 1){
	
	global $db_category,$db_links;
	global $eden_cfg;
	global $url_links;
	global $project;
	
	$res = mysql_query("SELECT links_gfx, links_main, links_picture, links_picture2, links_link, links_id, links_name FROM $db_links WHERE links_category_id=".(integer)$link_id." AND links_publish=1 ORDER BY links_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$links_gfx = $ar['links_gfx'];
		$links_main = $ar['links_main'];
		$links_picture = $ar['links_picture'];
		$links_picture2 = $ar['links_picture2'];
		$links_link = $ar['links_link'];
		$links_id = $ar['links_id'];
		$links_name = $ar['links_name'];
		/* Nacteni sablony */
		include "templates/tpl.odkazy_samotne_".$link_id."_".$cat_ver.".php";
	}
}
/***********************************************************************************************************
*
*		REKLAMA
*
*		$adds_id		- ID Kategorie (sablony)
*		$adds_iframe	- 1 pokud je pouzito v iframe
*
***********************************************************************************************************/
function Reklama($adds_id,$adds_iframe = 0){
	
	global $db_category,$db_adds;
	global $url_adds;
	global $eden_cfg;
	global $project;
	
	if ($adds_iframe == 0){$dir_prefix = "";} else {$dir_prefix = "../";}
	
	$res2 = mysql_query("SELECT adds_id, adds_show_unlimited, adds_show_by_count, adds_show_by_date, adds_count, adds_start, adds_end FROM $db_adds WHERE adds_category=".(integer)$adds_id." AND adds_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$showtime = formatTime(time(),"YmdHis");
	$i = 1;
	while($ar2 = mysql_fetch_array($res2)){
		if ($ar2['adds_show_unlimited'] == 1){$cislo[$i] = $ar2['adds_id'];}
		if ($ar2['adds_show_by_count'] == 1 && $ar2['adds_count'] > 0){$cislo[$i] = $ar2['adds_id'];}
		if ($ar2['adds_show_by_date'] == 1 && ($ar2['adds_start'] < $showtime && $ar2['adds_end'] > $showtime)){$cislo[$i] = $ar2['adds_id'];}
		$i++;
	}
	$pocet = count($cislo);
	// Nacteni sablony
	$rid = rand ( 1, $pocet);
	$res = mysql_query("SELECT adds_id, adds_gfx, adds_picture, adds_link, adds_link_onclick, adds_name, adds_show_by_count, adds_views, adds_count FROM $db_adds WHERE adds_id=".(integer)AGet($cislo,$rid)) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$views = $ar['adds_views']+1;
	if ($ar['adds_show_by_count'] == 1){$s_count = $ar['adds_count']-1;} else {$s_count = $ar['adds_count'];}
	$res = mysql_query("UPDATE $db_adds SET adds_views=".(integer)$views.", adds_count=".(integer)$s_count." WHERE adds_id=".(integer)$ar['adds_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$jump_mode = "adds";
	include $dir_prefix."templates/tpl.reklama_".$adds_id.".php";
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
*		ZOBRAZENI ZDROJE
*
*		$source			- Source z databaze
*
***********************************************************************************************************/
function ShowSource($source){
	
	$src = explode(",",$source);
	$src_num = count($src);
	
	$source_link = FALSE;
	
	for ($i=0;$src_num > $i;$i++){
		$src_url_name = explode("|",$src[$i]);
		if (count($src_url_name) == 2){
			$src_url = $src_url_name[0]; 
			$src_name = $src_url_name[1];
			$source_link .= "<a href=\"http://".$src_url."\" target=\"_self\">".$src_name."</a><br>";
		} elseif (count($src_url_name) == 1){
			$src_url = $src_url_name[0]; 
			$src_name = $src_url_name[0];
			$source_link .= "<a href=\"http://".$src_url."\" target=\"_self\">".$src_name."</a><br>";
		} else {
			$source_link .= "";
		}
	}
	return $source_link;
}
/***********************************************************************************************************
*
*		LOGIN
*
***********************************************************************************************************/
function Login($odkaz_admin, $odkaz_user, $odkaz_vizitor, $odkaz_seller){
	
	global $db_admin,$db_shop_basket,$db_sessions;
	global $eden_cfg;
	global $mod,$project;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['mode'] = AGet($_GET,'mode');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	/* Zkontrolujeme zda jsou potrebne udaje jiz ulozeny v cookies - pokud ano, vezmeme si je odtamtud */
	if ((AGet($_COOKIE,$project."_autologin") == 1) AND ($_COOKIE[$project."_name"] != "") AND ($_COOKIE[$project."_pass"] != "")){
		$sn = "autorizace";
		session_name("$sn");
		if( !isset( $_SESSION ) ) { session_start(); }
		$sid = session_id();
		$p = $_COOKIE[$project."_pass"];
		$login = strtoupper($_COOKIE[$project."_name"]);
		$_SESSION['login'] = $login;
		$_SESSION['loginid'] = $_COOKIE[$project."_loginid"];
		$_SESSION['sidd'] = $sid;
		$cookie = $_COOKIE[$project."_session_id"];
		mysql_query("UPDATE $db_sessions SET sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' WHERE sessions_id='".mysql_real_escape_string($cookie)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if (AGet($_POST,'action_shop') == "login"){
			mysql_query("UPDATE $db_shop_basket SET shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' WHERE shop_basket_session_id='".mysql_real_escape_string($cookie)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	/* Login z dopisu zaslaneho po vyzadani hesla */
	} elseif (AGet($_GET,'pass_code') != "" && AGet($_GET,'pass_login') != ""){
		$result = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_uname='".mysql_real_escape_string(AGet($_GET,'pass_login'))."' AND admin_password='".mysql_real_escape_string(AGet($_GET,'pass_code'))."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($result);
		
		$_SESSION['login'] = AGet($_GET,'pass_login');
		$login = AGet($_GET,'pass_login');
		$loginid = $ar['admin_id'];
		$p = AGet($_GET,'pass_code');
		$_SESSION['loginid'] = $loginid;
		$_SESSION['sidd'] = $_GET['sessid'];
		$_SESSION['forg_pass'] = "true";
		$odkaz_user = $eden_cfg['url'].'index.php?action=user_edit&lang='.$_GET['lang'].'&filter='.$_GET['filter'].'&mode=edit_user';
		$odkaz_seller = $eden_cfg['url'].'index.php?action=user_edit&lang='.$_GET['lang'].'&filter='.$_GET['filter'].'&mode=edit_user';
		$odkaz_admin = $eden_cfg['url'].'index.php?action=user_edit&lang='.$_GET['lang'].'&filter='.$_GET['filter'].'&mode=edit_user';
	/* Login z dopisu zaslany hraci po pridani do teamu */
	} elseif ($_GET['mode'] == "team_player_confirm" && strlen($_GET['reg_code']) == 47){
		$arr = str_split($_GET['reg_code'], 32);
		$result = mysql_query("SELECT admin_id, admin_uname FROM $db_admin WHERE admin_reg_code='".mysql_real_escape_string($arr[1])."' AND admin_password='".mysql_real_escape_string($arr[0])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($result);
		$_SESSION['login'] = $ar['admin_uname'];
		$login = $ar['admin_uname'];
		$loginid = $ar['admin_id'];
		$p = $arr[0];
		$_SESSION['loginid'] = $loginid;
		$_SESSION['sidd'] = $_GET['sessid'];
		$_SESSION['forg_pass'] = "true";
		$odkaz_user = $eden_cfg['url'].'index.php?action=user_edit&mode=team_player_confirm&lrid='.$_GET['lrid'].'&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
		$odkaz_seller = $eden_cfg['url'].'index.php?action=user_edit&mode=team_player_confirm&lrid='.$_GET['lrid'].'&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
		$odkaz_admin = $eden_cfg['url'].'index.php?action=user_edit&mode=team_player_confirm&lrid='.$_GET['lrid'].'&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
	/* Normalni login */
	} else {
		/* Login pro wholesale */
		if ($_GET['mode'] == "wholesale"){
			$odkaz_user = $eden_cfg['url'].'index.php?action=wholesale&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
			$odkaz_seller = $eden_cfg['url'].'index.php?action=wholesale&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
			$odkaz_admin = $eden_cfg['url'].'index.php?action=wholesale&lang='.$_GET['lang'].'&filter='.$_GET['filter'];
		}
		$allowtags = "";
		$login = strip_tags($_POST['login'],$allowtags);
		$login = mysql_real_escape_string($login);
		$login = strtoupper($login);
		$_SESSION['login'] = $login;
		$p = MD5(MD5($_POST['pass']).$login);
		$result = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_uname='".$login."' AND admin_password='".mysql_real_escape_string($p)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($result);
		$_SESSION['loginid'] = $ar['admin_id'];
		$sn = "autorizace";
		session_name("$sn");
		if( !isset( $_SESSION ) ) { session_start(); }
		$sid = session_id();
	}
	
	/* Zkonrolujeme zda zadane Uzivatelske jmeno a heslo je skutecne ulozeno v databazi */
	$result = mysql_query("SELECT admin_id, admin_forgpass_check, admin_status, admin_nick, admin_reg_allow, admin_autologin FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($_SESSION['login'])."' AND admin_password='".mysql_real_escape_string($p)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($result);
	$num = mysql_num_rows($result);
	
	/* Kontrola, zda pass_code a pass_login zaslany pri zapomenutem heslu skutecne byly vyvolany od daneho uzivatele */
	/* Pokud ano - admin_forgpass_check musi mit hodnotu 1 */
	if (AGet($_GET,'pass_code') != "" && AGet($_GET,'pass_login') != "" && $ar['admin_forgpass_check'] == 1){
		mysql_query("UPDATE $db_admin SET admin_forgpass_check=0 WHERE admin_id=".(integer)$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* Pokud admin_forgpass_check ma hodnotu 0, znamena to, ze jiz tento odkaz byl jednou pouzit */
	}elseif (AGet($_GET,'pass_code') != "" && AGet($_GET,'pass_login') != "" && $ar['admin_forgpass_check'] == 0){
		unset($login);
		$_SESSION = array();
		session_destroy();
		header ("Location: ".$eden_cfg['url']."index.php?msg=forgpass_allready_used&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
		exit;
	}
	/* Pokud je nalezen uzivatel zkontrolujeme jaky ma status */
	if ($num == 1){
		/* Pokud je to admin, zvoli se jako vychozi stranky stranky pro admina a do sessions se zapise jeho status */
		if ($ar['admin_status'] == "admin"){
			$_SESSION['u_status'] = "admin";
			$_SESSION['login_status'] = "true";
			$_SESSION['loginid'] = $ar['admin_id'];
			/* Abychom zabranili cykleni pri loginu, musime odstranit promennou action */
			$odkaz = str_replace( "action=login","action=",$odkaz_admin);
		/* Pokud je to uzivatel, zvoli se jako vychozi stranky stranky pro uzivatele a do sessions se zapise jeho status */
		}elseif ($ar['admin_status'] == "user"){
			$_SESSION['u_status'] = "user";
			$_SESSION['login_status'] = "true";
			$_SESSION['loginid'] = $ar['admin_id'];
			/* Abychom zabranili cykleni pri loginu, musime odstranit promennou action */
			$odkaz = str_replace( "action=login","action=",$odkaz_user);
		/* Pokud je to prodejce, zvoli se jako vychozi stranky stranky pro prodejce a do sessions se zapise jeho status */
		}elseif ($ar['admin_status'] == "seller"){
			$_SESSION['u_status'] = "seller";
			$_SESSION['login_status'] = "true";
			$_SESSION['loginid'] = $ar['admin_id'];
			/* Abychom zabranili cykleni pri loginu, musime odstranit promennou action */
			$odkaz = str_replace( "action=login","action=",$odkaz_seller);
		/* Pokud neni ani jedno predchozi, zvoli se jako vychozi stranky stranky pro neregistrovaneho navstevnika a do sessions se zapise jeho status */
		} else {
			$_SESSION['u_status'] = "vizitor";
			$_SESSION['login_status'] = "true";
			/* Abychom zabranili cykleni pri loginu, musime odstranit promennou action */
			$odkaz = str_replace( "action=login","action=",$odkaz_vizitor);
		}
		$_SESSION['nick'] = $ar['admin_nick'];
		
		if ($ar['admin_reg_allow'] == 0){
			$_SESSION = array();
			session_destroy();
			if ($_POST['action_shop'] == "login"){
				header ("Location: ".$eden_cfg['url']."index.php?action=01&msg=notallow&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
				exit;
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=notallow&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
				exit;
			}
		}
		if ($ar['admin_reg_allow'] == 3){
			$_SESSION = array();
			session_destroy();
			if ($_POST['action_shop'] == "login"){
				header ("Location: ".$eden_cfg['url']."index.php?action=01&msg=banned&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
				exit;
			} else {
				header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=banned&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
				exit;
			}
		}
		if ($ar['admin_autologin'] == 1){
			setcookie($project.'_autologin', '', time() - 604800);
			setcookie($project.'_name', '', time() - 604800);
			setcookie($project.'_loginid', '', time() - 604800);
			setcookie($project.'_pass', '', time() - 604800);
			setcookie($project.'_autologin', 1, time() + 604800);
			setcookie($project.'_loginid', $ar['admin_id'], time() + 604800);
			setcookie($project.'_name', $login, time() + 604800);
			setcookie($project.'_pass', $p, time() + 604800);
		}
		/* Ulozime datum posledniho zalogovani */
		mysql_query("UPDATE $db_admin SET admin_last_login=NOW() WHERE admin_id=".(integer)$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Musim zajistit, aby se vedelo, kdy se overuje heslo */
		/* Nez se heslo overuje, projde nejprve pres sessions a az pak pres funkci Login() */
		$mod = "overeni";
		require "./edencms/sessions.php";
		exit;
	} else {
		setcookie($project.'_autologin', '', time() - 604800);
		setcookie($project.'_name', '', time() - 604800);
		setcookie($project.'_loginid', '', time() - 604800);
		setcookie($project.'_pass', '', time() - 604800);
		setcookie($project.'_autologin', 0, time() + 604800);
		setcookie($project.'_loginid', '', time() + 604800);
		setcookie($project.'_name', '', time() + 604800);
		setcookie($project.'_pass', '', time() + 604800);
		unset($login);
		$_SESSION = array();
		session_destroy();
		if ($_POST['action_shop'] == "login"){
			header ("Location: ".$eden_cfg['url']."index.php?action=01&msg=badlogin&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
			exit;
		} else {
			header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=badlogin&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
			exit;
		}
	}
}
/***********************************************************************************************************
*
*		CUSTOM LOGIN
*
*		Login ktery se vrati na pozadovanou stranku pomoci parametru $mode
*		$mode - wholesale
*
***********************************************************************************************************/
function CustomLogin($mode = ""){
	
	global $project;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$login = "	<div align=\"center\"><form action=\"index.php?action=login&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$pp."&amp;hits=".$hits."&amp;mode=".$mode."\" method=\"post\">";
	$login .= "		<input type=\"text\" name=\"login\" id=\"login_name\" value=\"username\" onFocus=\"if (this.value=='username') this.value='';\" onBlur=\"if (this.value=='') this.value='username';\"  onMouseDown=\"this.value='';\"><br>";
	$login .= "		<input type=\"password\" name=\"pass\" id=\"login_pass\" value=\"password\" onFocus=\"if (this.value=='password') this.value='';\" onBlur=\"if (this.value=='') this.value='password';\"><br>";
	$login .= "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">";
	$login .= "		<input type=\"submit\" value=\""._SHOP_LOGIN."\" class=\"eden_button\">";
	$login .= "	</form></div>";
	
	return $login;
}
/***********************************************************************************************************
*
*		FORGOTEN PASSWORD
*
***********************************************************************************************************/
function ForgottenPass($width = 300){
	
	global $project;
	global $eden_cfg;
	
	echo "	<table width=\"".$width."\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\"\">";
	echo "		<tr>";
	echo "			<td width=\"".$width."\" colspan=\"2\"><strong>Zaslání loginu a hesla</strong><br>";
	echo "				<form enctype=\"multipart/form-data\" action=\"".$eden_cfg['url_edencms']."eden_save.php?lang=".$_GET["lang"]."&amp;filter=".$_GET["filter"]."\" method=\"post\">";
	echo "				"._LOGIN_FORGOTTEN_PASS_HINT;
	echo "			</td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td><strong>Login:</strong></td>";
	echo "			<td><input type=\"text\" name=\"admin_uname\" size=\"30\" value=\"\"></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td><strong>Email:</strong></td>";
	echo "			<td><input type=\"text\" name=\"admin_email\" size=\"30\" value=\"\"></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td width=\"".$width."\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\""._BUTTON_SEND." &gt;&gt;\" class=\"eden_button\">";
	echo "				<input type=\"hidden\" name=\"mode\" value=\"forgotten_pass\">";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$project."\">";
	echo "				</form>";
	echo "			</td>";
 	echo "		</tr>";
	echo "	</table>";
}
/***********************************************************************************************************
*
*		LOGOUT
*
***********************************************************************************************************/
function Logout(){
	
	global $db_sessions;
	global $eden_cfg;
	global $project;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	setcookie($project.'_autologin', '', time() - 186400);
	setcookie($project.'_name', '', time() - 186400);
	setcookie($project.'_pass', '', time() - 186400);
	mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	session_destroy();
	header ("Location: ".$eden_cfg['url']."index.php?action=&lang=".$_GET['lang']."&filter=".$_GET['filter']."");
	exit;
}
/***********************************************************************************************************
*
*		ZOBRAZENI NEJNOVEJSICH KOMENTARU
*
*		Tato funkce zobrazi poslednich x komentářů
*
***********************************************************************************************************/
function ZobrazeniPoslKomentaru($pocet, $letters = 20){
	
	global $db_comments;
	
	$res = mysql_query("SELECT comment_text, comment_pid, comment_author FROM $db_comments WHERE comment_show=1 ORDER BY comment_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	
	$i = 0;
	echo "<ul>";
	while ($ar = mysql_fetch_array($res)){
		if ($pocet > $i){
			$comment_text = TreatText($ar['comment_text'],$letters,1);
			$comment_pid = $ar['comment_pid'];
			$comment_author = $ar['comment_author'];
			/* Nacteni sablony */
			include "templates/tpl.posl_comment.php";
		}
		$i++;
	}
	echo "</ul>";
}
/***********************************************************************************************************
*
*		ZOBRAZENI NEJNOVEJSICH KOMENTARU
*
*		Tato funkce zobrazi poslednich x komentářů
*
***********************************************************************************************************/
function ZobrazeniPoslKomentaru2($pocet){
	
	global $db_articles,$db_comments;
	
	$res = mysql_query("SELECT comment_pid, comment_date, comment_text FROM $db_comments WHERE comment_modul='article' AND comment_show=1 ORDER BY comment_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	
	$i = 0;
	while ($ar = mysql_fetch_array($res)){
		$res2 = mysql_query("SELECT article_headline FROM $db_articles WHERE article_id=".(integer)$ar['comment_pid']." ORDER BY article_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar2 = mysql_fetch_array($res2);
		if ($pocet > $i){
			$comment_date = FormatTimestamp($ar['comment_date'],"d.m H:i");
			$article_headline = TreatText($ar2['article_headline'],30,1);
			$comment_text = TreatText($ar['comment_text'],20,1);
			/* Nacteni sablony */
			include "templates/tpl.posl_comment2.php";
		}
		$i++;
	}
}
/***********************************************************************************************************
*
*		NEJCTENEJSI CLANEK
*
***********************************************************************************************************/
function NejctenejsiClanek($pocet){
	
	global $db_articles;
	
	$date = date("YmdHis");
	$res = mysql_query("SELECT article_id, article_headline FROM $db_articles WHERE article_date_on < ".mysql_real_escape_string($date)." AND article_publish=1 ORDER BY article_views DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	$i = 0;
	echo "<ul>";
	while ($ar = mysql_fetch_array($res)){
		if ($pocet > $i){
			$article_id = $ar['article_id'];
			$article_headline = $ar['article_headline'];
			/* Nacteni sablony */
			include "templates/tpl.posl_clanek.php";
		}
		$i++;
	}
	echo "</ul>";
}
/***********************************************************************************************************
*
*		ZOBRAZENI NEJLEPSICH CLANKU
*
*		$c1		-	Kategorie
*		$num	-	Pocet zobrazenych clanku
*
***********************************************************************************************************/
function ShowBest($c1,$num){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_comments_log,$db_setup;
	global $url_category,$url_articles;
	global $article_id;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	
	$num1 = count($pieces);
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_top_article_mod, setup_article_number, setup_article_number_2, setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($ar_setup['setup_top_article_mod'] == 0){ $tn = " AND n.article_top_article=0";}
	if ($num == 1){$hits = $ar_setup['setup_article_number'];} else {$hits = $ar_setup['setup_article_number_2'];}
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	$vysledek = mysql_query("
	SELECT n.article_id, n.article_author_id, n.article_category_id, n.article_date, n.article_headline, n.article_perex, n.article_text, n.article_date_on, n.article_ftext, n.article_img_1, n.article_img_2, n.article_link, n.article_comments, n.article_source, n.article_views, a.admin_id, a.admin_firstname, a.admin_name, a.admin_nick, c.category_name, c.category_parent, c.category_image 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_parent_id=0 AND n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 AND n.article_best_article=1 $tn AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC 
	LIMIT ".(integer)$num.", 1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$ar = mysql_fetch_array($vysledek);
	$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
	$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
	
	$article_id = $ar['article_id'];
	$article_date = FormatTimestamp($ar['article_date']);
	$article_headline = TreatText($ar['article_headline'],50,1);
	$article_perex = TreatText($ar['article_perex'],0,1);
	$article_text = TreatText($ar['article_text'],0,1);
	$article_date_on = $ar['article_date_on'];
	$article_ftext = $ar['article_ftext'];
	$article_img_1 = $ar['article_img_1'];
	$article_img_2 = $ar['article_img_2'];
	$article_link = $ar['article_link'];
	$article_author_id = $ar['article_author_id'];
	$article_comments = $ar['article_comments'];
	$article_source = $ar['article_source'];
	$article_category_sub_id = $ar['article_category_id'];
	$article_views = $ar['article_views'];
	$article_text = str_replace("&acute;","'",$article_text);
	$article_text = str_replace("&acute;","'",$article_text);
	$category_name = $ar['category_name']; // Nastaveni zobrazeni kategorie u datumu
	$category_image = $ar['category_image'];
	
	/* Nastaveni datumu */
	$datum_clanku = FormatTimestamp($article_date_on,"l d.m.Y, H:i");
	$admin_id = $ar['admin_id'];
	if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname']." ".$ar['admin_name'];}
	
	/* Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich */
	$vysledek4 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$ar['article_id']." AND comments_log_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar4 = mysql_fetch_array($vysledek4);
	
	$comments_log_comments = $ar4['comments_log_comments'];
	
	/* Nacteni sablony */
	include "templates/tpl.show_best.php";
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
***********************************************************************************************************/
function Zobrazeni($category, $order = "article_date_on", $order2 = "DESC"){
	
	global $db_articles,$db_category,$db_comments;
	global $url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$vysledek = mysql_query("SELECT
	article_id,
	article_headline,
	article_perex,
	article_text,
	article_date_on,
	article_date_off,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_date,
	article_views
	FROM $db_articles WHERE article_category_id=".(integer)$category." AND article_publish=1 AND article_public=0 AND $showtime BETWEEN article_date_on AND article_date_off ORDER BY ".mysql_real_escape_string($order)." ".mysql_real_escape_string($order2)."");
	$num = mysql_num_rows($vysledek);
	$vysledek2 = mysql_query("SELECT category_hits FROM $db_category WHERE category_id=".(integer)$category."");
	$ar2 = mysql_fetch_array($vysledek2);
	$hits = $ar2['category_hits'];
	$m=0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page']=1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	//$hits=3; //Zde se nastavuje pocet prispevku
	$stw2 = ($num/$hits);
	$stw2 = (integer)$stw2;
	if ($num % $hits > 0) {$stw2++;}
	$np = $_GET['page']+1;
	$pp = $_GET['page']-1;
	if ($_GET['page'] == 1) { $pp=1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp=($_GET['page']-1)*$hits;
	$ep=($_GET['page']-1)*$hits+$hits;
	$cislo = 0;
	while ($ar = mysql_fetch_array($vysledek)){
		$m++;
		if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
			
			/* Aktualizace zobrazeni novinky */
			if ($ar['article_ftext']!= 1){
				$views = $ar['article_views']+1;
				$datum = date("YmdHis");
				mysql_query("UPDATE $db_articles SET article_views=".(integer)$views.", article_date_last_vizit=".(float)$datum." WHERE article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".$ar['article_id']." AND comment_modul='article'"); // Nastaveni ukazatele na komentare v danem clanku
			$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
			
			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			
			if (AGet($_GET,'team_detail') == ""){$_GET['team_detail'] = "close";}
			
			include "templates/tpl.zobrazeni.".$category.".php";
			$cislo++;
		}
	}
	/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
	if ($stw2 > 1){
		/* Nacteni sablony */
		include "templates/tpl.zobrazeni.dalsi.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI PRISPEVKU S MOZNOSTI URCENI POSLOUPNOSTI
*
*		podle:
*				id
*				category_name
*
***********************************************************************************************************/
function ZobrazeniSer($category,$seradit,$podle){
	
	global $db_articles,$db_category,$db_comments;
	global $url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$vysledek = mysql_query("SELECT
	article_id,
	article_date,
	article_headlinem,
	article_perex,
	article_text,
	article_date_on,
	article_date_off,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_views
	FROM $db_articles WHERE article_category_id=".(integer)$category." AND article_publish=1 AND article_public=0 AND $showtime BETWEEN article_date_on AND article_date_off 
	ORDER BY ".mysql_real_escape_string($seradit)." ".mysql_real_escape_string($podle)) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($vysledek);
	$vysledek2 = mysql_query("SELECT category_hits FROM $db_category WHERE category_id=".(integer)$category) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($vysledek2);
	$hits = $ar2['category_hits'];
	$m = 0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page']=1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	//$hits=3; //Zde se nastavuje pocet prispevku
	$stw2 = ($num/$hits);
	$stw2 = (integer)$stw2;
	if ($num%$hits > 0) {$stw2++;}
	$np = $_GET['page']+1;
	$pp = $_GET['page']-1;
	if ($_GET['page'] == 1) { $pp=1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page'] - 1) * $hits;
	$ep = ($_GET['page'] - 1) * $hits + $hits;
	
	while ($ar = mysql_fetch_array($vysledek)){
		$m++;
		if ($m>$sp and $m<=$ep){ //Spravne nastaveni poctu zobrazeni na strance
			
			/* Aktualizace zobrazeni novinky */
			if ($ar['article_ftext']!= 1){
				$views = $ar['article_views']+1;
				$datum = date("YmdHis");
				mysql_query("UPDATE $db_articles SET article_views=".(integer)$views.", article_date_last_vizit=".(float)$datum." WHERE article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			$vysledek2 = mysql_query("SELECT * FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'"); // Nastaveni ukazatele na komentare v danem clanku
			$num2 = mysql_num_rows($vysledek2); // Zjisteni poctu prispevku k danemu clanku
			
			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			
			if ($_GET['team_detail'] == ""){$_GET['team_detail'] = "close";}
			/* Nastaveni datumu */
			include "templates/tpl.zobrazeni_ser.".$category.".php";
		}
	}
	/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
	if ($stw2 > 1){
		/* Nacteni sablony */
		include "templates/tpl.zobrazeni.dalsi.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
*		$n	=	Number for template
*		c1	=	Kategorie oddelene :
*
***********************************************************************************************************/
function ZobrazeniNov($n,$c1,$hits = 30){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_comments_log,$db_setup;
	global $eden_cfg;
	global $url_category,$url_articles;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	
	$num1 = count($pieces);
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_top_article_mod, setup_article_show_best_article, setup_article_number, setup_article_number_2, setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$tn = FALSE;
	if ($ar_setup['setup_top_article_mod'] == 0){ $tn = " AND n.article_top_article=0" ;}
	if ($ar_setup['setup_article_show_best_article'] == 0){ $tn .= " AND n.article_best_article=0" ;}
	if ($n == 1){
		$hits = $ar_setup['setup_article_number'];
	} elseif ($n == 2) {
		$hits = $ar_setup['setup_article_number_2'];
	} else {
		// Hits are atken from var
	}
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	$vysledek_num = mysql_query("SELECT COUNT(*) 
	FROM $db_articles AS n 
	WHERE n.article_parent_id=0 AND n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 $tn AND $showtime BETWEEN n.article_date_on AND n.article_date_off") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($vysledek_num);
	
	$m = 0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page'] = 1;} else {$_GET['page'] = $_GET['page'];} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $_GET['page'] + 1;
	$pp = $_GET['page'] - 1;
	if ($_GET['page'] == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page'] - 1) * $hits;
	$ep = ($_GET['page'] - 1) * $hits + $hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	$vysledek = mysql_query("
	SELECT	n.article_id, n.article_date, n.article_headline, n.article_perex, n.article_text, n.article_date_on, n.article_ftext, n.article_img_1, n.article_link, n.article_author_id, n.article_comments, n.article_source, n.article_category_id, n.article_views, n.article_poll,
	c.category_name, c.category_parent, c.category_image, a.admin_id, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_parent_id=0 AND n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 $tn AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$i = 1;
	while ($ar = mysql_fetch_array($vysledek)){
		if ($i % 2 == 0){$css_class = "suda";} else {$css_class = "licha";}
		$m++;
		$vysledek2 = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article' AND comment_show=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_num_rows($vysledek2); // Zjisteni poctu prispevku k danemu clanku
		
		$article_id = $ar['article_id'];
		$article_date = FormatTimestamp($ar['article_date']);
		$article_headline = TreatText($ar['article_headline'],0,1);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_text = TreatText($ar['article_text'],0,1);
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_source = $ar['article_source'];
		$article_poll = $ar['article_poll'];
		$article_category_sub_id = $ar['article_category_id'];
		$article_views = $ar['article_views'];
		$article_text = str_replace( "&acute;","'",$article_text);
		$category_name = $ar['category_name']; // Nastaveni zobrazeni kategorie u datumu
		$category_image = $ar['category_image'];
		$admin_email = $ar['admin_email'];
		/***************************************************
		*	Novinky
		***************************************************/
		$admin_id = $ar['admin_id'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname']." ".$ar['admin_name'];}
		
		/* Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich */
		$vysledek7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$ar['article_id']." AND comments_log_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar7 = mysql_fetch_array($vysledek7);
		$comments_log_comments = $ar7['comments_log_comments'];
		
		/* Nacteni sablony */
		include "templates/tpl.zobrazeni_nov_".$n.".php";
		$i++;
	}
	/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
	if ($stw2 > 1){
		/* Nacteni sablony */
		include "templates/tpl.zobrazeni_nov.dalsi.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
*		$n	=	Number for template
*		c1	=	Kategorie oddelene :
*
***********************************************************************************************************/
function ZobrazeniNovNette($n,$c1){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_comments_log,$db_setup;
	global $eden_cfg;
	global $url_category,$url_articles;
	
	//$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	
	$num1 = count($pieces);
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_top_article_mod, setup_article_show_best_article, setup_article_number, setup_article_number_2, setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$tn = FALSE;
	if ($ar_setup['setup_top_article_mod'] == 0){ $tn = " AND n.article_top_article=0" ;}
	if ($ar_setup['setup_article_show_best_article'] == 0){ $tn .= " AND n.article_best_article=0" ;}
	if ($n == "1"){$hits = $ar_setup['setup_article_number'];} else {$hits = $ar_setup['setup_article_number_2'];}
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	$vysledek_num = mysql_query("SELECT COUNT(*) 
	FROM $db_articles AS n 
	WHERE n.article_parent_id=0 AND n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 $tn AND $showtime BETWEEN n.article_date_on AND n.article_date_off") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($vysledek_num);
	
	$m = 0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page'] = 1;} else {$_GET['page'] = $_GET['page'];} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $_GET['page'] + 1;
	$pp = $_GET['page'] - 1;
	if ($_GET['page'] == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page'] - 1) * $hits;
	$ep = ($_GET['page'] - 1) * $hits + $hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	$vysledek = mysql_query("
	SELECT	n.article_id, n.article_date,	n.article_headline, n.article_perex, n.article_text, n.article_date_on, n.article_ftext, n.article_img_1, n.article_link, n.article_author_id, n.article_comments, n.article_source, n.article_category_id, n.article_views, n.article_poll,
	c.category_name, c.category_parent, c.category_image, a.admin_id, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_parent_id=0 AND n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 $tn AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$i = 1;
	while ($ar = mysql_fetch_array($vysledek)){
		$m++;
		$vysledek2 = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_num_rows($vysledek2); // Zjisteni poctu prispevku k danemu clanku
		
		$article[$i]['article_id'] = $ar['article_id'];
		$article[$i]['article_date'] = FormatTimestamp($ar['article_date']);
		$article[$i]['article_headline'] = TreatText($ar['article_headline'],0,1);
		$article[$i]['article_perex'] = TreatText($ar['article_perex'],0,1);
		$article[$i]['article_text'] = TreatText($ar['article_text'],0,1);
		$article[$i]['article_date_on'] = $ar['article_date_on'];
		$article[$i]['article_ftext'] = $ar['article_ftext'];
		$article[$i]['article_img_1'] = $ar['article_img_1'];
		$article[$i]['article_link'] = $ar['article_link'];
		$article[$i]['article_author_id'] = $ar['article_author_id'];
		$article[$i]['article_comments'] = $ar['article_comments'];
		$article[$i]['article_source'] = $ar['article_source'];
		$article[$i]['article_poll'] = $ar['article_poll'];
		$article[$i]['article_category_sub_id'] = $ar['article_category_id'];
		$article[$i]['article_views'] = $ar['article_views'];
		$article[$i]['article_text'] = str_replace( "&acute;","'",$article[$i]['article_text']);
		$article[$i]['category_name'] = $ar['category_name']; // Nastaveni zobrazeni kategorie u datumu
		$article[$i]['category_image'] = $ar['category_image'];
		$article[$i]['admin_email'] = $ar['admin_email'];
		/***************************************************
		*	Novinky
		***************************************************/
		$article[$i]['admin_id'] = $ar['admin_id'];
		if ($ar_setup['setup_show_author_nick'] == 1){$article[$i]['admin_nickname'] = $ar['admin_nick'];} else {$article[$i]['admin_nickname'] = $ar['admin_firstname']." ".$ar['admin_name'];}
		
		/* Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich */
		$vysledek7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=1 AND comments_log_item_id=".(integer)$ar['article_id']." AND comments_log_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar7 = mysql_fetch_array($vysledek7);
		$article[$i]['comments_log_comments'] = $ar7['comments_log_comments'];
		
		// Nacteni sablony
		// include "templates/tpl.zobrazeni_nov_".$n.".php";
		$i++;
	}
	return $article;
	/*
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
	if ($stw2 > 1){
		// Nacteni sablony
		include "templates/tpl.zobrazeni_nov.dalsi.php";
	}
	*/
}
/***********************************************************************************************************
*
*		ZOBRAZENI FIRST CLANKU
*
***********************************************************************************************************/
function ZobrazeniNovFirst($c1, $article_lang = "cz"){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_comments_log,$db_setup;
	global $url_category,$url_articles;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	/* Nacteni nastaveni poctu zobrazovanych novinek */
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	$vysledek = mysql_query("SELECT
	n.article_id,
	n.article_date,
	n.article_headline,
	n.article_perex,
	n.article_text,
	n.article_date_on,
	n.article_ftext,
	n.article_img_1,
	n.article_link,
	n.article_comments,
	n.article_views,
	n.article_source, c.category_name, c.category_image, a.admin_id, a.admin_firstname, a.admin_name, a.admin_email, a.admin_nick 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_category_id IN ($categories) AND n.article_public = 0 AND n.article_publish = 1 AND n.article_top_article = 1 AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($vysledek);
	$num = mysql_num_rows($vysledek);
	if ($num > 0){
		$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
		
		$article_id = $ar['article_id'];
		$article_date = FormatTimestamp($ar['article_date']);
		$article_headline = TreatText($ar['article_headline'],0,1);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_text = TreatText($ar['article_text'],0,1);
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_views = $ar['article_views'];
		$article_source = $ar['article_source'];
		$article_text = str_replace( "&acute;","'",$article_text);
		$category_name = $ar['category_name']; // Nastaveni zobrazeni kategorie u datumu
		$category_image = $ar['category_image'];
		$admin_id = $ar['admin_id'];
		$admin_email = $ar['admin_email'];
		$admin_nick = $ar['admin_nick'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname'].' '.$ar['admin_name'];}
		/* Nastaveni datumu */
		$datum_clanku = FormatTimestamp($article_date_on,"l d.m.Y, H:i");
		
		if ($_SESSION['loginid'] != ""){
			$ar8_admin_id = $_SESSION['loginid'];
		} else {
			$ar8_admin_id = 0;
		}
		/* Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich */
		
		$vysledek7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$ar8_admin_id." AND comments_log_item_id=".(integer)$ar['article_id']." AND comments_log_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar7 = mysql_fetch_array($vysledek7);
		$comments_log_comments = $ar7['comments_log_comments'];
		/* Nacteni sablony */
		include "templates/tpl.zobrazeni_nov_first.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU FIRST CLANKU
*
***********************************************************************************************************/
function ZobrazeniNovFirstSeznam($c1){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_setup;
	global $url_category,$url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$i=0;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$res_setup = mysql_query("SELECT setup_top_article_archive FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("SELECT n.article_id,
	n.article_id,
	n.article_date,
	n.article_headline,
	n.article_perex,
	n.article_text,
	n.article_date_on,
	n.article_ftext,
	n.article_img_1,
	n.article_link,
	n.article_comments,
	n.article_views, c.category_name, c.category_parent, a.admin_id, a.admin_nick, a.admin_firstname, a.admin_name 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_category_id IN ($categories)  AND n.article_public=0 AND n.article_publish=1 AND n.article_top_article=1 AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar = mysql_fetch_array($vysledek)){
		if ($ar_setup['setup_top_article_archive'] >= $i){
			/* Nastaveni ukazatele na komentare v danem clanku */
			$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
			
			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			$article_views = $ar['article_views'];
			$article_text = str_replace( "&acute;","'",$article_text);
			$category_name = $ar['category_name']; // Nastaveni zobrazeni kategorie u datumu
			$admin_id = $ar['admin_id'];
			$admin_nick = $ar['admin_nick'];
			$admin_firstname = $ar['admin_firstname'];
			$admin_name = $ar['admin_name'];
			// Nastaveni datumu
			$datum_clanku = FormatTimestamp($article_date_on,"l d.m.Y, H:i");
  			// Nacteni sablony
			include "templates/tpl.zobrazeni_nov_first_seznam.php";
		}
		$i++;
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI ARTICLES ARCHIV
*
*		Tato funkce zobrazi seznam x poctu clanku kde x muzeme nastavit v nastaveni
*		$c1 az $c10 jsou kategorie pro vyber zobrazeni clanku
*
***********************************************************************************************************/
function ZobrazeniNovArchiv($c1){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_setup;
	global $url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$res_setup = mysql_query("SELECT setup_article_archive, setup_article_number FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("SELECT article_id,
	article_date,
	article_headline,
	article_perex,
	article_text,
	article_date_on,
	article_date_off,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_views
	FROM $db_articles WHERE article_category_id IN ($categories) AND article_public=0 AND article_publish=1 AND article_parent_id=0 AND $showtime BETWEEN article_date_on AND article_date_off 
	ORDER BY article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($vysledek);
	$i = 0;
	$x = 0;
	while ($ar = mysql_fetch_array($vysledek)){
		if ( $showtime > $ar['article_date_on'] && $showtime < $ar['article_date_off']){
			if ($ar_setup['setup_article_number'] <= $i && $ar_setup['setup_article_archive'] > $x){ // Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni.
				$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
				$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
				
				$article_id = $ar['article_id'];
				$article_date = FormatTimestamp($ar['article_date']);
				$article_headline = TreatText($ar['article_headline'],50,1);
				$article_perex = TreatText($ar['article_perex'],0,1);
				$article_text = TreatText($ar['article_text'],0,1);
				$article_date_on = $ar['article_date_on'];
				$article_ftext = $ar['article_ftext'];
				$article_img_1 = $ar['article_img_1'];
				$article_link = $ar['article_link'];
				$article_author_id = $ar['article_author_id'];
				$article_comments = $ar['article_comments'];
				$article_views = $ar['article_views'];
				
				// Nacteni sablony
				include "templates/tpl.zobrazeni_nov_archiv.php";
				$x++;
			}
			$i++;
		}
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKY ARCHIV
*
*		Tato funkce zobrazi seznam x poctu clanku
*
*		$limit		=	pocet clanku, ktere chceme zobrazit
*		$c1			=	je pole kategorii pro vyber zobrazeni clanku
*		$letters 	=	je pocet maximalni pismen, ktere chceme zobrazit
*
***********************************************************************************************************/
function ZobrazeniClankyArchiv($limit,$c1,$letters = 50){
	
	global $db_articles,$db_admin,$url_articles,$db_category,$db_comments;
	global $url_category;
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	$res = mysql_query("
	SELECT article_id, article_author_id, article_headline, article_category_id, category_image, category_name 
	FROM $db_articles  
	JOIN $db_category ON category_id = article_category_id 
	WHERE article_category_id IN ($categories) AND article_public = 0 AND article_publish = 1 AND article_parent_id = 0 AND $showtime BETWEEN article_date_on AND article_date_off 
	ORDER BY article_date_on DESC 
	LIMIT ".(integer)$limit) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		// Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni.
		//Nacteni vsech novinek v dane kategorii
		$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
		
		$article_id = $ar['article_id'];
		$article_headline = TreatText($ar['article_headline'],50,1);
		$article_headline_long = $article_headline;
		$article_headline = ShortText($article_headline,$letters);
		$category_image = $ar['category_image'];
		$category_name = $ar['category_name'];
		// Nacteni sablony
		include "templates/tpl.zobrazeni_clanky_archiv.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKY ARCHIV
*
*		Tato funkce zobrazi seznam x poctu clanku kde x muzeme nastavit v nastaveni
*
*		$c1 az $c10 jsou kategorie pro vyber zobrazeni clanku
*
***********************************************************************************************************/
function ZobrazeniClankyArchiv2($c1){
	
	global $db_articles,$db_admin,$url_articles,$db_category,$db_comments,$db_setup;
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$i=0;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_article_archive FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res = mysql_query("
	SELECT n.article_id, n.article_date, n.article_headline, n.article_perex, n.article_text, n.article_date_on, n.article_ftext, n.article_img_1, n.article_link, n.article_author_id, n.article_comments, n.article_views, a.admin_id, a.admin_nick 
	FROM $db_articles 
	WHERE n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 AND n.article_parent_id=0 AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	$x = 0;
	while ($ar = mysql_fetch_array($res)){
		if ($ar_setup['setup_article_archive'] > $x){ // Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni.
			//Nacteni vsech novinek v dane kategorii
			$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
			$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			$article_views = $ar['article_views'];
			$admin_id = $ar['admin_id'];
			$admin_nick = $ar['admin_nick'];
			
			// Nacteni sablony
			include "templates/tpl.zobrazeni_clanky_archiv2.php";
			$x++;
		}
	}
}
/***********************************************************************************************************
*
*		NEWS
*
*		$c1			= 	kategorie oddelene :
*		$akt_hits	= 	nastaveni poctu zobrazeni
*						(prikazem 'setup' se nastavi zobrazeni podle nastaveni stranek)
*		$akt_next	=	nastaveni zobrazeni seznamu aktualit
*						volby: true, false
*
***********************************************************************************************************/
function News($c1,$akt_hits = 'setup',$akt_next = true){
	
	global $db_news,$db_category,$db_comments,$db_comments_log,$db_admin,$db_setup;
	global $url_category;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	/* Zpracujeme promenne tak aby sly ulozit do dotazu */
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_news_number, setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	
	$vysledek_num = mysql_query("SELECT COUNT(*) FROM $db_news AS act WHERE act.news_category_id IN ($categories) AND act.news_publish=1 AND $showtime BETWEEN act.news_date_on AND act.news_date_off") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$akt_num = mysql_fetch_array($vysledek_num);
	
	/* Nacteni nastaveni poctu zobrazovanych novinek */
	if($akt_hits == 'setup'){ $akt_hits = $ar_setup['setup_news_number'];}
	$m = 0;// nastaveni iterace
	if (empty($_GET['akt_page'])) {$_GET['akt_page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($akt_hits == 0){$akt_hits = 30;}
	$akt_stw2 = ($akt_num[0]/$akt_hits);
	$akt_stw2 = (integer) $akt_stw2;
	if ($akt_num[0]%$akt_hits > 0) {$akt_stw2++;}
	$akt_np = $_GET['akt_page']+1;
	$akt_pp = $_GET['akt_page']-1;
	if ($_GET['akt_page'] == 1) { $akt_pp=1; }
	if ($akt_np > $akt_stw2) { $akt_np = $akt_stw2;}
	$akt_sp = ($_GET['akt_page']-1)*$akt_hits;
	$akt_ep = ($_GET['akt_page']-1)*$akt_hits+$akt_hits;
	$limit = "LIMIT ".(integer)$akt_sp.", ".(integer)$akt_hits."";
	$vysledek = mysql_query("
	SELECT act.news_id, act.news_text, act.news_headline, act.news_source, act.news_comments, act.news_date, act.news_date_on, act.news_category_id, c.category_name, c.category_image, a.admin_id, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email 
	FROM $db_news AS act 
	JOIN $db_admin AS a ON a.admin_id=act.news_author_id 
	JOIN $db_category AS c ON c.category_id=act.news_category_id 
	WHERE act.news_category_id IN ($categories) AND act.news_publish=1 AND $showtime BETWEEN act.news_date_on AND act.news_date_off 
	ORDER BY act.news_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	$y = 0;
	while ($ar = mysql_fetch_array($vysledek)){
		$m++;
		$vysledek6 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['news_id']." AND comment_modul='news'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$nc_num = mysql_fetch_array($vysledek6); // Zjisteni poctu prispevku k danemu clanku
		$num2 = $nc_num[0];
		$datum = FormatTimestamp($ar['news_date']);
		
		// Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich
		$vysledek7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$ar['news_id']." AND comments_log_modul='news'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar7 = mysql_fetch_array($vysledek7);
		
		$admin_id = $ar['admin_id'];
		$admin_nick = $ar['admin_nick'];
		$admin_email = $ar['admin_email'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname']." ".$ar['admin_name'];}
		
		$category_image = $ar['category_image'];
		$category_name = $ar['category_name'];
		$news_id = $ar['news_id'];
		$news_text = TreatText($ar['news_text'],0,1);
		$news_headline = TreatText($ar['news_headline'],50,1);
		$news_source = $ar['news_source'];
		$news_category_id = $ar['news_category_id'];
		$news_comments = $ar['news_comments'];
		$news_date_on = $ar['news_date_on'];
		
		$comments_log_comments = $ar7['comments_log_comments'];
		
		// Nastaveni datumu
		$datum_clanku = FormatTimestamp($ar['news_date_on'],"l d.m.Y, H:i");
		$cislo = $y;
		include "templates/tpl.news.php";
		$i++;
		$y++;
	}
	/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
	if ($akt_stw2 > 1 && $akt_next == true){
		// Nacteni sablony
		include "templates/tpl.zobrazeni_akt.dalsi.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI NEWS ARCHIV
*
*		Tato funkce zobrazi seznam x poctu clanku kde x muzeme nastavit v nastaveni
*
*		$c1 az $c10 jsou kategorie pro vyber zobrazeni clanku
*
***********************************************************************************************************/
function ZobrazeniAktArchiv($c1){
	
	global $db_news,$db_admin,$url_articles,$db_category,$db_comments,$db_setup;
	global $url_category;
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$res_setup = mysql_query("SELECT setup_news_archive, setup_news_archivation_start FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$akt_hits = $ar_setup['setup_news_archive'];
	$m = $ar_setup['setup_news_archivation_start'];// nastaveni iterace
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	
	$num1 = count($pieces);
	
	// Nacteni nastaveni poctu zobrazovanych novinek
	$i=0;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	$vysledek_num = mysql_query("SELECT COUNT(*) FROM $db_news WHERE news_category_id IN ($categories) AND news_publish=1 AND $showtime BETWEEN news_date_on AND news_date_off") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$akt_num = mysql_fetch_array($vysledek_num);
	
	if (empty($_GET['akt_page'])) {$_GET['akt_page']=1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($akt_hits == 0){$akt_hits = 30;}
	$akt_stw2 = ($akt_num[0]/$akt_hits);
	$akt_stw2 = (integer) $akt_stw2;
	if ($akt_num[0]%$akt_hits > 0) {$akt_stw2++;}
	$akt_np = $_GET['akt_page'] + 1;
	$akt_pp = $_GET['akt_page'] - 1;
	if ($_GET['akt_page'] == 1) { $akt_pp = 1; }
	if ($akt_np > $akt_stw2) { $akt_np = $akt_stw2;}
	
	$akt_sp = ($_GET['akt_page'] -1 ) * $akt_hits;
	$akt_ep = ($_GET['akt_page'] - 1) * $akt_hits + $akt_hits;
	
	$limit = "LIMIT ".(integer)$akt_sp.", ".(integer)$akt_hits."";
	$vysledek = mysql_query("SELECT news_id, news_category_id, news_headline, news_text, news_date, news_date_on FROM $db_news WHERE news_category_id IN ($categories) AND news_publish=1 AND $showtime BETWEEN news_date_on AND news_date_off	ORDER BY news_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 0;
	while ($ar = mysql_fetch_array($vysledek)){
		$vysledek3 = mysql_query("SELECT category_image FROM $db_category WHERE category_id=".(integer)$ar['news_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($vysledek3);
		
		$category_image = $ar3['category_image'];
		
		$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['news_id']." AND comment_modul='news'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
		$datum = FormatTimestamp($ar['news_date']);
		// Nastaveni datumu
		$news_date_on = $ar['news_date_on'];
		$news_id = $ar['news_id'];
		$hlavicka = TreatText($ar['news_headline'],0,1);
		$text = TreatText($ar['news_text'],0,1);
		$news_comments = $ar['news_comments'];
		// Nacteni sablony
		include "templates/tpl.zobrazeni_akt_archiv.php";
		$i++;
	}
	/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
	if ($akt_stw2 > 1){
		// Nacteni sablony
		include "templates/tpl.zobrazeni_akt.dalsi.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI PRISPEVKU
*
***********************************************************************************************************/
function ZobrazeniA($category,$orderby,$ser){
	
	global $db_articles,$db_category,$db_comments,$db_admin;
	global $eden_cfg;
	global $url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$vysledek = mysql_query("SELECT
	article_id,
	article_views,
	article_ftext,
	article_date,
	article_date_on,
	article_date_off,
	article_headline,
	article_perex,
	article_text,
	article_img_1,
	article_link,
	article_author_id,
	article_comments
	FROM $db_articles WHERE article_public=0 AND article_publish=1 AND	article_category_id=".(integer)$category." ORDER BY '".mysql_real_escape_string($orderby)."' ".mysql_real_escape_string($ser)) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($vysledek);
	//$vysledek2 = mysql_query("SELECT * FROM $db_category WHERE category_id=".(integer)$category) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//$ar2 = mysql_fetch_array($vysledek2);
	while ($ar = mysql_fetch_array($vysledek)){
		if ( $showtime > $ar['article_date_on'] AND $showtime < $ar['article_date_off']){
			// Aktualizace zobrazeni novinky
			if ($ar['article_ftext']!= 1){
				$views = $ar['article_views']+1;
				$datum = date("YmdHis");
				mysql_query("UPDATE $db_articles SET article_views=".(integer)$views.", article_date_last_vizit=".(float)$datum." WHERE article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
			$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
			$datum = FormatTimestamp($ar['article_date']);
			
			// Zpracovani textu do pouzitelne podoby
			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			$article_views = $ar['article_views'];
			
			// Nastaveni datumu
			$vysledek3 = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_id=".(integer)$ar['article_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
			$ar3 = mysql_fetch_array($vysledek3);
			// Nacteni sablony
			include "templates/tpl.zobrazeni_a.".$category.".php";
		}
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU V DANE KATEGORII
*
*		Tato funkce zobrazi z dane kategorie
*
*		$category	=	ID kategorie
*		$cid		=	ID clanku
*
***********************************************************************************************************/
function ZobrazeniCl($category,$cid){
	
	global $db_articles,$db_category,$db_comments;
	global $url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$vysledek = mysql_query("SELECT
	article_id,
	article_date,
	article_headline,
	article_perex,
	article_text,
	article_date_on,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_views
	FROM $db_articles WHERE article_id=".(integer)$cid." AND article_publish=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($vysledek);
	$num = mysql_num_rows($vysledek);
	
	/* Aktualizace zobrazeni novinky */
	if ($ar['article_ftext']!= 1){
		$views = $ar['article_views']+1;
		$datum = date("YmdHis");
		mysql_query("UPDATE $db_articles SET article_views=".(integer)$views.", article_date_last_vizit=".(float)$datum." WHERE article_id=".(integer)$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
	$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku
	
	/* Zpracovani textu do pouzitelne podoby */
	$article_id = $ar['article_id'];
	$article_date = FormatTimestamp($ar['article_date']);
	$article_headline = TreatText($ar['article_headline'],50,1);
	$article_perex = TreatText($ar['article_perex'],0,1);
	$article_text = TreatText($ar['article_text'],0,1);
	$article_text = str_replace( "&acute;","'",$article_text);
	$article_date_on = $ar['article_date_on'];
	$article_ftext = $ar['article_ftext'];
	$article_img_1 = $ar['article_img_1'];
	$article_link = $ar['article_link'];
	$article_author_id = $ar['article_author_id'];
	$article_comments = $ar['article_comments'];
	$article_views = $ar['article_views'];
	
	/* Nacteni sablony */
	include "templates/tpl.zobrazeni_cl.php";
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
*		$cid	-	ID clanku
*		$par	-	ID parent clanku
*
***********************************************************************************************************/
function Clanek($cid,$par = 0){
	
	global $db_articles,$db_comments,$db_admin,$db_category,$db_setup;
	global $url_articles;
	global $eden_cfg;
	global $category_sub;
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("SELECT
	article_id,
	article_parent_id,
	article_date,
	article_headline,
	article_perex,
	article_publish,
	article_text,
	article_date_on,
	article_ftext,
	article_img_1,
	article_category_id,
	article_link,
	article_author_id,
	article_comments,
	article_views,
	article_source,
	article_poll,
	article_hash 
	FROM $db_articles WHERE article_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($vysledek);
	
	// Pokud neni clanek publikovan, nebo nema admin odpovidajici opravneni, clanek se nezobrazi
	if ($ar['article_publish'] == 1 || CheckPriv("groups_article_add") == 1 || $ar['article_hash'] == $_GET['nhash']){	
		
		$article_headline = TreatText($ar['article_headline'],0,1);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_headline = $ar['article_headline'];
		$article_perex = $ar['article_perex'];
		if ($ar['article_parent_id'] != 0){
			$res = mysql_query("SELECT article_id, article_headline, article_perex FROM $db_articles WHERE article_id=".(integer)$ar['article_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$arr = mysql_fetch_array($res);
			$article_headline = TreatText($arr['article_headline'],0,1);
			$article_perex = TreatText($arr['article_perex'],0,1);
		}
		/* Aktualizace zobrazeni novinky */
		if ($ar['article_ftext']== 1 || $ar['article_link']== "TRUE" || $ar['article_parent_id'] == $par){
			$views = $ar['article_views']+1;
			$datum = date("YmdHis");
			mysql_query("UPDATE $db_articles SET article_views=".(integer)$views.", article_date_last_vizit=".(float)$datum." WHERE article_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($par == "" || $par == 0){$par = $ar['article_id'];}
		$reskap1 = mysql_query("SELECT article_id, article_author_id, article_chapter_name, article_comments, article_date_on, article_prevoff, article_chapter_name FROM $db_articles WHERE article_id=".(integer)$par) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$arkap1 = mysql_fetch_array($reskap1);
		
		$category_sub = $ar['article_category_id'];
		$vysledek3 = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".(integer)$category_sub) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($vysledek3);
		$category_name = $ar3['category_name']; // Nastaveni zobrazeni kategorie u datumu
		
		/* Zpracovani textu do pouzitelne podoby */
		$article_id = $ar['article_id'];
		$article_parent_id = $ar['article_parent_id'];
		$article_date = FormatTimestamp($ar['article_date']);
		$article_text = TreatText($ar['article_text'],0,1);
		
		// Exception for embeded iframe
		$article_text = str_replace("action=league&amp;amp;id=","action=league&id=",$article_text);
		
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_views = $ar['article_views'];
		$article_source = $ar['article_source'];
		
		/* Nacteni udaju o autorovi */
		$res_adm = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$arkap1['article_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
		$ar_adm = mysql_fetch_array($res_adm);
		
		$admin_id = $ar_adm['admin_id'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar_adm['admin_nick'];} else {$admin_nickname = $ar_adm['admin_firstname'].' '.$ar_adm['admin_name'];}
		
		/* Nacteni sablony */
		include "templates/tpl.clanek.php";
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI AKTUALITA
*
***********************************************************************************************************/
function Aktualita($aid){
	
	global $db_news,$db_admin,$db_category,$db_comments,$db_setup;
	
	// Nacteni nastaveni
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("SELECT
	news_publish,
	news_views,
	news_headline,
	news_text,
	news_author_id,
	news_date_on,
	FROM $db_news	WHERE news_id=".(integer)$aid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($vysledek);
	
	/* Aktualizace zobrazeni novinky */
	if ($ar['news_publish'] == 1){
		$views = $ar['news_views']+1;
		$datum = date("YmdHis");
		mysql_query("UPDATE $db_news SET news_views=".(integer)$views." WHERE news_id=".(integer)$aid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	
	/* Zpracovani textu do pouzitelne podoby */
	$news_id = $ar['news_id'];
	$hlavicka = TreatText($ar['news_headline'],0,1);
	$text = TreatText($ar['news_text'],0,1);
	$news_date_on = $ar['news_date_on'];
	$autor = wordwrap( $autor, 100, "\n", 1);
	$news_author_id = $ar['news_author_id'];
	$news_comments = $ar['news_comments'];
	
	/* Nacteni udaju o autorovi */
	$res_adm = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_id=".(integer)$news_author_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
	$ar_adm = mysql_fetch_array($res_adm);
	
	$admin_id = $ar_adm['admin_id'];
	if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar_adm['admin_nick'];} else {$admin_nickname = $ar_adm['admin_firstname'].' '.$ar_adm['admin_name'];}
	
	/* Nacteni sablony */
	include "templates/tpl.news_single.php";
}
/***********************************************************************************************************
*
*		SEZNAM AKTUALIT
*
*		$c1			-	Kategorie
*		$temp_num	-	Cislo pro template
*
***********************************************************************************************************/
function NewsList($c1,$temp_num = 1,$limit = 10){
	
	global $db_news,$db_admin,$db_comments,$db_category,$db_setup;
	global $url_category,$url_games;
	
	// Nacteni nastaveni 
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	/* Nacteni nastaveni poctu zobrazovanych novinek */
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	$res = mysql_query("SELECT news_id, news_date_on, news_headline, news_author_id, news_comments, news_views, news_category_id 
	FROM $db_news 
	WHERE news_category_id IN ($categories) AND news_publish=1 AND $showtime BETWEEN news_date_on AND news_date_off 
	ORDER BY news_date_on DESC 
	LIMIT ".(integer)$limit) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$cislo = 0;
	while ($ar = mysql_fetch_array($res)){
		/* Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni. */
		/* Nacteni vsech novinek v dane kategorii */
		$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['news_id']." AND comment_modul='news'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
		/* Nacteni Autora */
		$res3 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$ar['news_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($res3);
		
		$admin_id = $ar3['admin_id'];
		$admin_email = $ar3['admin_email'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar3['admin_nick'];} else {$admin_nickname = $ar3['admin_firstname'].' '.$ar3['admin_name'];}
		
		$news_id = $ar['news_id'];
		$news_headline = TreatText($ar['news_headline'],50,1);
		$news_date_on = $ar['news_date_on'];
		$news_author_id = $ar['news_author_id'];
		$news_comments = $ar['news_comments'];
		$news_views = $ar['news_views'];
		
		$res4 = mysql_query("SELECT category_image, category_name FROM $db_category WHERE category_id=".(integer)$ar['news_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar4 = mysql_fetch_array($res4);
		$category_img = $ar4['category_image'];
		$category_name = $ar4['category_name'];
		/* Nacteni sablony */
		include "templates/tpl.news.list.".$temp_num.".php";
		$cislo++;
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
***********************************************************************************************************/
function ClanekIframe($nid,$par){
	
	global $db_articles,$db_comments,$db_category,$db_admin,$db_setup;
	global $eden_cfg;
	global $url_category;
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$vysledek = mysql_query("SELECT
	n.article_id,
	n.article_date,
	n.article_headline,
	n.article_perex,
	n.article_text,
	n.article_date_on,
	n.article_ftext,
	n.article_img_1,
	n.article_link,
	n.article_author_id,
	n.article_comments,
	n.article_views,
	n.article_prevoff,
	c.category_image
	FROM $db_articles AS n, $db_category AS c WHERE n.article_id=".(integer)$nid." AND article_publish=1 AND c.category_id=n.article_category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($vysledek);
	// Aktualizace zobrazeni novinky
	/*if ($ar[ftext]== 1){
		$views = $ar[views]+1;
		$datum = date("YmdHis");
		mysql_query("UPDATE $db_articles SET views='$views', datum_last_vizit='$datum'	WHERE id='$nid'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}*/
	if ($par == "" || $par == 0){$par = $ar['article_id'];}
	$reskap1 = mysql_query("SELECT article_prevoff FROM $db_articles WHERE article_id=".(integer)$par) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$arkap1 = mysql_fetch_array($reskap1);
	
	$res_adm = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick FROM $db_admin WHERE admin_id=".(integer)$ar['article_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
	$ar_adm = mysql_fetch_array($res_adm);
	
	$category_image = $ar['category_image'];
	
	$admin_id = $ar_adm['admin_id'];
	$admin_nick = $ar_adm['admin_nick'];
	if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar_adm['admin_nick'];} else {$admin_nickname = $ar_adm['admin_firstname'].' '.$ar_adm['admin_name'];}
	
	/* Zpracovani textu do pouzitelne podoby */
	$article_id = $ar['article_id'];
	$article_date = FormatTimestamp($ar['article_date']);
	$article_headline = TreatText($ar['article_headline'],100,1);
	$article_perex = TreatText($ar['article_perex'],0,1);
	$article_text = TreatText($ar['article_text'],0,1);
	$article_date_on = $ar['article_date_on'];
	$article_ftext = $ar['article_ftext'];
	$article_img_1 = $ar['article_img_1'];
	$article_link = $ar['article_link'];
	$article_author_id = $ar['article_author_id'];
	$article_comments = $ar['article_comments'];
	$article_views = $ar['article_views'];
	$article_prevoff = $arkap1['article_prevoff'];
	
	/* Nacteni sablony */
	include "../templates/tpl.clanek_iframe.php";
}
/***********************************************************************************************************
*
*		NADPIS
*
***********************************************************************************************************/
function Nadpis($nid){
	
	global $db_comments,$db_articles;
	
	$vysledek = mysql_query("SELECT article_headline FROM $db_articles WHERE article_id=".(integer)$nid." AND article_publish=1");
	$ar = mysql_fetch_array($vysledek);
	$article_headline = TreatText($ar['article_headline'],100,1);
	
	/* Nacteni sablony */
	include "templates/tpl.nadpis.php";
}
/***********************************************************************************************************
*
*		ZOBRAZENI KATEGORII
*
*		Tato funkce zobrazi seznam kategorii
*		$cat_parent		= ID parent categorie
*		$cat_mode		= Mod kategorie (articles, news, links, download, adds, shop)
*
***********************************************************************************************************/
function ZobrazeniKategorii($cat_parent=0,$cat_mode='articles'){
	
	global $db_category,$db_admin;
	
	$showtime = formatTime(time(),"YmdHis");
	if ($cat_parent == 0){$where = " category_parent=0 ";}else{$where = " category_parent=".(integer)$cat_parent." ";}
	switch ($cat_mode){
		case "articles":
			$where .= "AND category_articles=1"; 
		break;
		case "news":
			$where .= "AND category_news=1"; 
		break;
		case "links":
			$where .= "AND category_links=1"; 
		break;
		case "download":
			$where .= "AND category_download=1"; 
		break;
		case "adds":
			$where .= "AND category_adds=1"; 
		break;
		case "shop":
			$where .= "AND category_shop=1"; 
		break;
		default:
			echo "";
	}
	$res = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shows=1 AND $where ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	while ($ar = mysql_fetch_array($res)){
		$category_id = $ar['category_id'];
		$category_name = TreatText($ar['category_name'],50,1);
		include "templates/tpl.zobrazeni_kategorii.php";
	}
}

/***********************************************************************************************************
*
*		PRIDANI ODKAZU
*
*		$oid		=	Cislo kategorie pod kterou se ma odkaz zaradit
*		$img		= 	Pokud je $img 1 - muze byt pridan i obrazek, v setupu zadane velikosti
*
***********************************************************************************************************/
function AddLink($cid,$img = 0){
	
	global $db_setup_images;
	global $eden_cfg;
	
	$res_setup = mysql_query("SELECT eden_setup_image_width, eden_setup_image_height, eden_setup_image_filesize FROM $db_setup_images WHERE eden_setup_image_for='link_2'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	include "templates/tpl.pridej_odkaz.php";
}
/***********************************************************************************************************
*
*		ARCHIV
*
*		$cal_lang	=	Nazvy dnu a mesicu z eden_lang.cz
*
***********************************************************************************************************/
function Archiv(){
  
	global $db_category,$db_articles,$db_setup;
	global $cal_lang;
  
	$res3 = mysql_query("SELECT setup_article_archivation_start FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar3 = mysql_fetch_array($res3);
  
	if (!isset($_GET['month'])){ $_GET['month'] = date("n");}
	if (!isset($_GET['year'])){ $_GET['year'] = date("Y");}
  
	// prevod na korektní hodnoty
	$_GET['year'] = date("Y", mktime(0,0,0, $_GET['month'], 1, $_GET['year']));
	$_GET['month'] = date("n", mktime(0,0,0, $_GET['month'], 1, $_GET['year']));
  
	$_GET['month'] = Zerofill($_GET['month'],10);
  
	while($_GET['year'].$_GET['month'] >= $ar3['setup_article_archivation_start']){
    
		// Nastaveni dotazu na kategorie ktere jsou urcene pro archivovani
		$res2 = mysql_query("SELECT category_id FROM $db_category WHERE category_archive=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num2 = mysql_num_rows($res2);
		$dotaz2 = "article_public=0 AND article_publish=1 AND article_parent_id=0";
		while($ar2 = mysql_fetch_array($res2)){
			$dotaz2 = $dotaz2." AND article_category_id='".$ar2['category_id']."'";
		}
		$dotaz = "article_date_on BETWEEN ".(integer)$_GET['year'].(integer)$_GET['month']."01000000 AND ".(integer)$_GET['year'].(integer)$null.(integer)$_GET['month']."31595959";
		$res = mysql_query("SELECT COUNT(*) FROM $db_articles WHERE $dotaz2 AND $dotaz ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_array($res);
		if ($num[0] >= 1){
			DateLinkArchiv($_GET['month'], $_GET['year'], $cal_lang['months'][$_GET['month']-1].".".$_GET['year'], "kal_archiv", "index.php");echo "<br>";
		}
		$_GET['month'] = $_GET['month']-1;
		$_GET['month'] = Zerofill($_GET['month'],10);
		if ($_GET['month'] == "00"){$_GET['month'] = "12"; $_GET['year'] = $_GET['year']-1;}
	}
}
/***********************************************************************************************************
*
*		KALENDAR A ARCHIV
*
*		$cal_lang	=	Nazvy dnu a mesicu z eden_lang.cz
*
***********************************************************************************************************/
function ArchivKalendar(){
	
	global $actual,$db_articles,$db_category;
	global $cal_lang;
	
	// pokud nejsou promenné zinicializovány, vloží aktuální hodnoty
	if (!isset($_GET['month'])){ $_GET['month'] = date("m");}
	if (!isset($_GET['year'])){ $_GET['year'] = date("Y");}
	if (!isset($actual)){ $actual = date("d");}
	
	// prevod na korektní hodnoty
	$_GET['year'] = date("Y", mktime(0,0,0, $_GET['month'], 1, $_GET['year']));
	$_GET['month'] = date("m", mktime(0,0,0, $_GET['month'], 1, $_GET['year']));
	
	$count_days = date("t", mktime(0,0,0, $_GET['month'], 1, $_GET['year'])); // pocet dnu v mesíci
	
	// ke každému dni priradí jeho císlo v týdnu (1 = pondelí, ...)
	for($i=1;$i<=$count_days;$i++){
		$date[$i] = date("w", mktime(0,0,0,$_GET['month'],$i,$_GET['year']));
		if ($date[$i]==0){$date[$i] = 7;}
	}
	
	$first = $date[1];	// císlo prvního dne v mesíci (1 = pondelí, ...)
	
	echo "<table cellspacing=\"1\" class=\"calendar\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" class=\"calendar\" colspan=\"8\">";
	// predchozí, aktuální a následující mesíc
	//echo '<strong><a href="'.$PHP_SELF.'?action=kal_archiv&amp;month='.($_GET['month']-1).'&amp;year='.$_GET['year'].'">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;';
	echo "	<strong>";DateLinkArchiv($_GET['month']-1, $_GET['year'], "&lt;&lt;", "kal_archiv", "index.php"); echo "</strong>";
	echo "	<strong>";DateLink(1, $count_days, $_GET['month']." / ".$_GET['year'], "kal_archiv", $actual, "index.php"); echo "</strong>";// odkaz na mesícní statistiku
	echo "	<strong>";DateLinkArchiv($_GET['month']+1, $_GET['year'], "&gt;&gt;", "kal_archiv", "index.php"); echo "</strong>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"calendardateheaders\">\n";
	echo "		<td class=\"calendar\"><strong>T</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][0]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][1]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][2]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][3]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][4]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][5]."</strong></td>\n";
	echo "		<td class=\"calendar\"><strong>".$cal_lang['abrvdays'][6]."</strong></td>\n";
	echo "	</tr>";
	$day = 0;
	for($x=0;$x<=5;$x++){
		echo "<tr>\n<td class=\"calendar\" align=\"center\" style=\"font-weight: bold;\">";
		// poslední den v týdnu
		if ($x==0){	// první týden
			$end = 7-$first+1;
		}elseif ($day+7>=$count_days){	// poslední týden
			$end = $count_days;
		} else {	// zbylé týdny
			$end = $day+7;
		}
		// odkaz na týdenní statistiku
		DateLink($day+1, $end, $x+1, "kal_archiv", $actual, "index.php");
		echo "</td>\n";
		for($i=1;$i<=7;$i++){
			echo '<td class="calendar" align="center">';
			$day = $x*7+$i-$first+1; // na základe obou cyklu postupne pocítá den
			$days = Zerofill($day,10); // Pridani nuly pred cislo dne, pokud tam neni
			$_GET['month'] = Zerofill($_GET['month'],10);
			// Nastaveni dotazu na kategorie ktere jsou urcene pro archivovani
			$res2 = mysql_query("SELECT * FROM $db_category WHERE category_archive=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2 = mysql_num_rows($res2);
			$dotaz2 = "article_public=0 AND article_publish=1 AND article_parent_id=0";
			while($ar2 = mysql_fetch_array($res2)){
				$dotaz2 = $dotaz2." AND ".$ar2['category_id'];
			}
			
			$dotaz = "article_date_on BETWEEN ".(integer)$_GET['year'].(integer)$_GET['month'].(integer)$days."000000 AND ".(integer)$_GET['year'].(integer)$_GET['month'].(integer)$days."595959";
			$res = mysql_query("SELECT COUNT(*) FROM $db_articles WHERE $dotaz2 AND $dotaz ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num = mysql_fetch_array($res);
			
			if ($date[$day] == $i){
				if ($num[0] >= 1){
					DateLink($day, $day, $day, "kal_archiv", $actual, "index.php"); // odkaz na denní vypis
				} else {
					echo $day;
				}
			} else {
				echo "&nbsp;";
			}
			echo "</td>\n";
		}
		echo "</tr>\n";
		if (!checkdate($_GET['month'], $day+1, $_GET['year'])){ break;} // pokud neexistuje následující datum, ukoncí cyklus
	}
	echo "</table><br><br>";
}
/***********************************************************************************************************
*
*		ZOBRAZENI VYPISU
*
***********************************************************************************************************/
function ShowArchivKalendar(){

	global $db_admin,$db_category,$db_articles,$db_comments,$db_setup;
	global $url_articles;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_article_archive FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);

	$showtime = formatTime(time(),"YmdHis");
	$dotaz = "article_date_on BETWEEN ".(integer)$_GET['from_date']."000000 AND ".(integer)$_GET['to_date']."595959";
	$res = mysql_query("SELECT
	article_id,
	article_category_id,
	article_date,
	article_headline,
	article_perex,
	article_text,
	article_date_on,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_views
	FROM $db_articles WHERE article_public=0 AND article_publish=1  AND article_parent_id=0 AND $dotaz ORDER BY article_date_on DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	while ($ar = mysql_fetch_array($res)){
		$vysledek2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'"); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($vysledek2); // Zjisteni poctu prispevku k danemu clanku

		$vysledek3 = mysql_query("SELECT category_name, category_archive FROM $db_category WHERE category_id=".(integer)$ar['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($vysledek3);
		$category_name = $ar3['category_name']; // Nastaveni zobrazeni kategorie u datumu
		if ($ar3['category_archive'] == 1){
			/* Zobrazeni nadrazene kategorie */
			//$vysledek4 = mysql_query("SELECT * FROM $db_category WHERE category_id=".(integer)$ar3['category_parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			//$ar4 = mysql_fetch_array($vysledek4);

			$article_id = $ar['article_id'];
			$article_date = FormatTimestamp($ar['article_date']);
			$article_headline = TreatText($ar['article_headline'],50,1);
			$article_perex = TreatText($ar['article_perex'],0,1);
			$article_text = TreatText($ar['article_text'],0,1);
			$article_date_on = $ar['article_date_on'];
			$article_ftext = $ar['article_ftext'];
			$article_img_1 = $ar['article_img_1'];
			$article_link = $ar['article_link'];
			$article_author_id = $ar['article_author_id'];
			$article_comments = $ar['article_comments'];
			$article_views = $ar['article_views'];

			$article_text = str_replace( "&acute;","'",$article_text);

			/* Nastaveni datumu */
			$datum_clanku = FormatTimestamp($article_date_on,"l d.m.Y, H:i");

			/* Nacteni informaci o autorovi*/
			$vysledek3 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id='".(integer)$ar['article_author_id']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Vybere jmeno autora
			$ar3 = mysql_fetch_array($vysledek3);

			if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar3['admin_nick'];} else {$admin_nickname = $ar3['admin_firstname'].' '.$ar3['admin_name'];}

			/* Nacteni sablony */
			include "templates/tpl.zobrazeni_nov_archiv2.php";
		}
		/* <a href=\"index.php?action=clanek&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$article_id."\">".$article_headline."</a><br>".$ar[preview]." */
    }
}
/***********************************************************************************************************
*
*		ODKAZ V KALENDARI
*
*		vypisuje odkazy pro zobrazení statistiky za dané období
*
*		$from	=	Od jakeho data
*		$to		=	Po jake datum
*		$text	=	text, ktery ma byt zobrazen jako odkaz
*		$laction	=	promenna pro stranky, ktera akce se ma zobrazit
*		$actual	=	Aktualni vybrane datum
*		$link	=	odkaz na stranku, na kterou ma odkaz ukazovat
*
***********************************************************************************************************/
function DateLink($from,$to,$text,$laction,$actual,$link){

	$from = Zerofill($from,10);
	$to = Zerofill($to,10);
	$actual = Zerofill($actual,10);

	if (mktime(0,0,0,$_GET['month'],$from,$_GET['year']) <= mktime(0,0,0, date("m"), date("d"), date("Y"))){
		echo "<a href=\"".$link."?action=".$laction."&amp;month=".$_GET['month']."&amp;year=".$_GET['year']."&amp;from_date=".$_GET['year'].$_GET['month'].$from."&amp;to_date=".$_GET['year'].$_GET['month'].$to."&amp;actual=".$from."\">";
		if ($text == $actual){echo "<strong>".$text."</strong>";} else {echo $text;}
		echo "</a>";
	} else {
		echo $text;
	}
}
/***********************************************************************************************************
*
*		ODKAZ V KALENDARI
*
*		vypisuje odkazy pro zobrazení statistiky za dané období
*
*		$month	=	Mesic
*		$year	=	Rok
*		$text	=	text, ktery ma byt zobrazen jako odkaz
*		$laction	=	promenna pro stranky, ktera akce se ma zobrazit
*		$link	=	odkaz na stranku, na kterou ma odkaz ukazovat
*
***********************************************************************************************************/
function DateLinkArchiv($lmonth,$lyear,$ltext,$laction,$llink){

	if (mktime(0,0,0,$lmonth,$from,$lyear) <= mktime(0,0,0, date("m"), date("d"), date("Y"))){
		if ((preg_match ("/^0/", $lmonth) != true) && $lmonth < 10){$lmonth = "0".$lmonth;}
		echo "<a href=\"".$llink."?action=".$laction."&amp;month=".$lmonth."&amp;year=".$lyear."&amp;from_date=".$lyear.$lmonth."01&amp;to_date=".$lyear.$lmonth."31\">".$ltext."</a>";
	} else {
		echo $ltext;
	}
}
/***********************************************************************************************************
*
*		USER BAN
*
*		vypisuje odkazy pro zobrazení statistiky za dané období
*
***********************************************************************************************************/
function UserBan(){
	
	global $db_ban,$db_setup,$db_setup_lang;
	global $project;
	global $eden_cfg;
	
	$today = date("Y-m-d");
	$res = mysql_query("SELECT ban_date FROM $db_ban WHERE INET_NTOA(ban_ip) = '".mysql_real_escape_string($eden_cfg['ip'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	if ($today <= $ar['ban_date']){
		$res_setup = mysql_query("SELECT s.setup_ban_color, sl.setup_lang_ban_text FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang = s.setup_basic_lang");
		$ar_setup = mysql_fetch_array($res_setup);
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $ar['ban_date'], $datetime);
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "	<title>BAN INFO</title>\n";
		echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
		echo "</head>\n";
		echo "<body bgcolor=\"".$ar_setup['setup_ban_color']."\">\n";
		echo "<div align=\"center\"><strong><h2>".$ar_setup['setup_lang_ban_text']."</h2>\n";
		echo "	<h1><span style=\"color: #FF0000;\">".$datetime[3].".".$datetime[2].".".$datetime[1]."</span></h1></strong></div>\n";
		echo "</body>\n";
		echo "</html>";
		exit;
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI OZNAMENI O AKTIVACI UCTU
*
*		Zobrazi oznameni o tom, ze ucet byl aktivovan
*
*		admin_reg_allow
*		0	=	Neaktivovany
*		1	=	Aktivovany
*		2	=	Muted
*		3	=	Neaktivovany Adminem (v pripade ze je potreba aktivace adminem)
*
***********************************************************************************************************/
function AllowReg($rg_code, $reg_from = "main"){
	
	global $db_admin,$db_setup,$db_setup_lang;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	include "./edencms/class.mail.php";
	
	$res_setup = mysql_query("SELECT s.setup_reg_from, s.setup_reg_from_name, s.setup_reg_mailer, s.setup_reg_over_email, sl.setup_lang_reg_subject FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($ar_setup['setup_reg_over_email'] == 1){$reg_method = 1;}elseif ($ar_setup['setup_reg_over_email'] == 2){$reg_method = 3;}
	
	$res = mysql_query("SELECT admin_id, admin_uname, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_reg_code='".mysql_real_escape_string($rg_code)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	/* Vymazeme regcode */
	$update = mysql_query("UPDATE $db_admin SET admin_reg_allow=".(integer)$reg_method.", admin_reg_code='' WHERE admin_reg_code='".mysql_real_escape_string($rg_code)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	if ($update){
		if ($ar_setup['setup_reg_over_email'] == 2){
			$mail_to_admin = new PHPMailer();
			$mail_to_admin->From = $ar_setup['setup_reg_from'];
			$mail_to_admin->FromName = $ar_setup['setup_reg_from_name'];
			$mail_to_admin->AddAddress($ar_setup['setup_reg_from']);
			$mail_to_admin->CharSet = "utf-8";
			$mail_to_admin->IsHTML(true);
			$mail_to_admin->Mailer = $ar_setup['setup_reg_mailer'];
			
			$mail_to_admin->Subject = $ar_setup['setup_lang_reg_subject']." - ".$admin_nick;
			// Pokud se registruje z progress obrazovky zaneseme do odkazu promennou, ktera poukazuje odkud bylo registrovano
			
			$mail_to_admin->Body = "<html><head title=\"".$ar_setup['setup_lang_reg_subject']."\"/><body>";
			$mail_to_admin->Body .= "<p><strong>ID:</strong> ".$ar['admin_id'];
			$mail_to_admin->Body .= "<strong>IP:</strong> ".$eden_cfg['ip'];
			$mail_to_admin->Body .= "<strong>"._REG_USERNAME."</strong> ".$ar['admin_uname']."</p>";
			$mail_to_admin->Body .= "<p><strong>Firstname:</strong> ".$ar['admin_firstname']."<br>";
			$mail_to_admin->Body .= "<strong>Surname:</strong> ".$ar['admin_name']."<br>";
			$mail_to_admin->Body .= "<strong>Nickname:</strong> ".$ar['admin_nick']."</p>";
			$mail_to_admin->Body .= "<p><strong>Email:</strong> ".$ar['admin_email']."</p>";
			$mail_to_admin->Body .= "</body></html>";
			
			$mail_to_admin->AltBody = "\n";
			$mail_to_admin->AltBody .= "ID: ".$ar['admin_id'];
			$mail_to_admin->AltBody .= "IP: ".$eden_cfg['ip'];
			$mail_to_admin->AltBody .= "Username: ".$ar['admin_uname']."\n";
			$mail_to_admin->AltBody .= "Firstname: ".$ar['admin_firstname'];
			$mail_to_admin->AltBody .= "Surename: ".$ar['admin_name'];
			$mail_to_admin->AltBody .= "Nickname: ".$ar['admin_nick']."\n";
			$mail_to_admin->AltBody .= "Email: ".$ar['admin_email']."\n";
			
			$mail_to_admin->WordWrap = 100;
			
			$mail_to_admin->Send();
		}
		$msg = "allow_ok";
	} else {
		$msg = "allow_no";
	}
	if ($reg_from == "shop"){$ar_action = "action=01&action_shop=user_login&msg=".$msg;}elseif ($ar_setup['setup_reg_over_email'] == 2){$ar_action = "action=msg&msg=allow_ok_admin";} else {$ar_action = "action=msg&msg=".$msg;}
	header ("Location: ".$eden_cfg['url']."index.php?".$ar_action."&lang=".$_GET['lang']."&filter=".$_GET['filter']."");
	exit;
}
/***********************************************************************************************************
*
*		ZOBRAZENI OZNAMENI O ZMENE EMAILU
*
*		Zobrazi oznameni o tom, ze byla zmenena emailova adresa
*
***********************************************************************************************************/
function AllowChangeEmail($rg_code){
	
	global $db_admin,$db_admin_info,$db_setup,$db_setup_lang;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	include "./edencms/class.mail.php";
	
	$res_setup = mysql_query("SELECT s.setup_reg_from, s.setup_reg_from_name, s.setup_reg_mailer, s.setup_reg_over_email, sl.setup_lang_changed_subject, sl.setup_lang_changed_email FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
    
	$res_admin = mysql_query("SELECT a.admin_id, a.admin_uname, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email, ai.admin_info_email_new FROM $db_admin AS a, $db_admin_info AS ai WHERE a.admin_reg_code='".mysql_real_escape_string($rg_code)."' AND ai.aid=a.admin_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_admin = mysql_fetch_array($res_admin);
	
	$body = $ar_setup['setup_lang_changed_email'];
	
	$body = str_replace("[{admin_uname}]",$ar_admin['admin_uname'] ,$body);
	$body = str_replace("[{admin_new_email}]",$ar_admin['admin_info_email_new'] ,$body);
	$body = str_replace("[{admin_old_email}]",strtolower($ar_admin['admin_email']) ,$body);
	
	mysql_query("UPDATE $db_admin SET admin_email='".strtolower($ar_admin['admin_info_email_new'])."', admin_reg_code='NULL' WHERE admin_id=".(integer)$ar_admin['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	mysql_query("UPDATE $db_admin_info SET admin_info_email_new='NULL' WHERE aid=".(integer)$ar_admin['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$mail_to_admin = new PHPMailer();
	$mail_to_admin->CharSet = "utf-8";
	$mail_to_admin->From = $ar_setup['setup_reg_from'];
	$mail_to_admin->FromName = $ar_setup['setup_reg_from_name'];
	$mail_to_admin->AddAddress(strtolower($ar_admin['admin_email']));
	$mail_to_admin->AddAddress(strtolower($ar_admin['admin_info_email_new']));
	$mail_to_admin->CharSet = "utf-8";
	$mail_to_admin->IsHTML(true);
	$mail_to_admin->Mailer = $ar_setup['setup_reg_mailer'];
	$mail_to_admin->Subject = $ar_setup['setup_lang_changed_subject'];
	
	// Pokud se registruje z progress obrazovky zaneseme do odkazu promennou, ktera poukazuje odkud bylo registrovano
	$mail_to_admin->Body = "<html><head title=\"".$ar_setup['setup_lang_changed_subject']."\"/><body>";
	$mail_to_admin->Body .= $body;
	$mail_to_admin->Body .= "</body></html>";
	
	$mail_to_admin->AltBody = $body;
	
	$mail_to_admin->WordWrap = 100;
	
	$mail_to_admin->Send();
	
	header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=change_email_ok&lang=".$_GET['lang'].'&filter='.$_GET['filter']);
	exit;
}
/***********************************************************************************************************
*
*		SEZNAM CLANKU
*
*		$c1					-	Kategorie
*		$temp_num			-	Cislo pro template
*		$limit				-	Pocet zobrazenych novinek
*		$include_top_articles	-	Zahrnovat top articles? 0 / 1
*
***********************************************************************************************************/
function ArticlesList($c1,$temp_num = 1,$limit = 10,$include_top_articles = 1){
	
	global $db_articles,$db_admin,$db_comments,$db_category,$db_setup;
	global $url_category,$url_games,$url_articles;
	
	$showtime = formatTime(time(),"YmdHis");
	$pieces = explode (":", $c1);
	$num1 = count($pieces);
	/* Nacteni nastaveni poctu zobrazovanych novinek */
	$i=0;
	$categories = FALSE;
	while($num1 > $i){
		$act_category_pieces = $pieces[$i];
		if ($include_top_articles == 0){$top_articles = "AND n.article_top_article=0";} else {$top_articles = "";}
		if ($i>0){$divider = ",";} else {$divider = "";}
		$categories .= $divider.(integer)$act_category_pieces;
		$i++;
	}
	// Nacteni nastaveni
	$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res = mysql_query("
	SELECT n.article_id, n.article_author_id, n.article_date_on, n.article_headline, n.article_perex, n.article_text, n.article_ftext, n.article_img_1, n.article_link, n.article_comments, n.article_views, a.admin_id, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email, c.category_image, c.category_name 
	FROM $db_articles AS n 
	JOIN $db_admin AS a ON a.admin_id=n.article_author_id 
	JOIN $db_category AS c ON c.category_id=n.article_category_id 
	WHERE n.article_category_id IN ($categories) AND n.article_public=0 AND n.article_publish=1 AND n.article_parent_id=0 $top_articles AND $showtime BETWEEN n.article_date_on AND n.article_date_off 
	ORDER BY n.article_date_on DESC 
	LIMIT $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$cislo = 0;
	while ($ar = mysql_fetch_array($res)){
		/* Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni. */
		/* Nacteni vsech novinek v dane kategorii */
		$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
		
		$admin_id = $ar['admin_id'];
		$admin_email = $ar['admin_email'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname']." ".$ar['admin_name'];}
		
		$article_id = $ar['article_id'];
		$article_date = FormatTimestamp($ar['article_date_on']);
		$article_headline = TreatText($ar['article_headline'],50,1);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_text = TreatText($ar['article_text'],0,1);
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_views = $ar['article_views'];
		$category_img = $ar['category_image'];
		$category_name = $ar['category_name'];
		/* Nacteni sablony */
		include "templates/tpl.articles.list.".$temp_num.".php";
		$cislo++;
	}
}
/***********************************************************************************************************
*
*		SEZNAM CLANKU VE VYBRANEM KANALE
*
*		$c1			-	Kategorie
*		$temp_num	-	Cislo pro template
*		$limit		-	Pocet zobrazenych novinek
*
***********************************************************************************************************/
function ChannelList($channel,$temp_num = 1,$limit_num = 10){
	
	global $db_articles,$db_admin,$db_comments,$db_category;
	global $url_category,$url_games,$url_articles;
	
	if ($limit == 0){$limit == "";}else{$limit = "LIMIT ".$limit_num;}
	$showtime = formatTime(time(),"YmdHis");
	$categories = "article_channel_id=".(integer)$channel." AND article_public=0 AND article_publish=1 AND article_parent_id=0 AND $showtime BETWEEN article_date_on AND article_date_off";
	$res = mysql_query("SELECT article_id, article_date_on, article_headline, article_perex, article_text, article_ftext, article_img_1, article_link, article_author_id, article_comments, article_views, article_category_id FROM $db_articles WHERE $categories ORDER BY article_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$cislo = 0;
	while ($ar = mysql_fetch_array($res)){
		/* Zobrazi se jen odkazy na clanky, ktere jsou v poradi za clanky, zobrazenymi celkove a v poctu urcenem v nastaveni. */
		/* Nacteni vsech novinek v dane kategorii */
		$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_fetch_array($res2); // Zjisteni poctu prispevku k danemu clanku
		/* Nacteni Autora */
		$res3 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick, admin_email FROM $db_admin WHERE admin_id=".(integer)$ar['article_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($res3);
		
		$admin_id = $ar3['admin_id'];
		$admin_email = $ar3['admin_email'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar3['admin_nick'];} else {$admin_nickname = $ar3['admin_firstname'].' '.$ar3['admin_name'];}
		
		$article_id = $ar['article_id'];
		$article_date = FormatTimestamp($ar['article_date_on']);
		$article_headline = TreatText($ar['article_headline'],50);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_text = TreatText($ar['article_text'],0,1);
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_views = $ar['article_views'];
		
		$res4 = mysql_query("SELECT category_image FROM $db_category WHERE category_id=".(integer)$ar['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar4 = mysql_fetch_array($res4);
		$category_img = $ar4['category_image'];
		/* Nacteni sablony */
		include "templates/tpl.channel.list.".$temp_num.".php";
		$cislo++;
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI CLANKU
*
*		$n	=	Number for template
*		c1	=	Kategorie oddelene :
*
***********************************************************************************************************/
function ShowChannel($channel,$temp_num = 1,$limit_num = 10){
	
	global $db_articles,$db_admin,$db_category,$db_comments,$db_comments_log,$db_setup;
	global $url_category,$url_articles;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$showtime = formatTime(time(),"YmdHis");
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_top_article_mod, setup_article_number, setup_article_number_2, setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($limit == 0){$limit == "";}else{$limit = "LIMIT ".$limit_num;}
	$categories = "article_channel_id=".(integer)$channel." ";
	
	$vysledek = mysql_query("SELECT
	article_id,
	article_date,
	article_headline,
	article_perex,
	article_text,
	article_date_on,
	article_ftext,
	article_img_1,
	article_link,
	article_author_id,
	article_comments,
	article_source,
	article_category_id,
	article_views
	FROM $db_articles WHERE $categories  AND article_public=0 AND article_publish=1 AND article_parent_id=0 AND $showtime BETWEEN article_date_on AND article_date_off 
	ORDER BY article_date_on DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$i = 1;
	while ($ar = mysql_fetch_array($vysledek)){
		$vysledek2 = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_pid=".(integer)$ar['article_id']." AND comment_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Nastaveni ukazatele na komentare v danem clanku
		$num2 = mysql_num_rows($vysledek2); // Zjisteni poctu prispevku k danemu clanku
		
		$article_id = $ar['article_id'];
		$article_date = FormatTimestamp($ar['article_date']);
		$article_headline = TreatText($ar['article_headline'],50,1);
		$article_perex = TreatText($ar['article_perex'],0,1);
		$article_text = TreatText($ar['article_text'],0,1);
		$article_date_on = $ar['article_date_on'];
		$article_ftext = $ar['article_ftext'];
		$article_img_1 = $ar['article_img_1'];
		$article_link = $ar['article_link'];
		$article_author_id = $ar['article_author_id'];
		$article_comments = $ar['article_comments'];
		$article_source = $ar['article_source'];
		$article_category_id = $ar['article_category_id'];
		$article_views = $ar['article_views'];
		
		$vysledek5 = mysql_query("SELECT category_name, category_image FROM $db_category WHERE category_id=".(integer)$article_category_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar5 = mysql_fetch_array($vysledek5);
		$category_name = $ar5['category_name']; // Nastaveni zobrazeni kategorie u datumu
		$category_image = $ar5['category_image'];
		
		/***************************************************
		*	Novinky
		***************************************************/
		
		$vysledek3 = mysql_query("SELECT admin_id, admin_firstname, admin_name, admin_nick FROM $db_admin WHERE admin_id=".(integer)$ar['article_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($vysledek3);
		
		$admin_id = $ar3['admin_id'];
		if ($ar_setup['setup_show_author_nick'] == 1){$admin_nickname = $ar3['admin_nick'];} else {$admin_nickname = $ar3['admin_firstname'].' '.$ar3['admin_name'];}
		
		/* Zjisteni datumu posledniho posledni navstevy uzivatele v danych komentarich */
		$vysledek7 = mysql_query("SELECT comments_log_comments FROM $db_comments_log WHERE comments_log_admin_id=".(integer)$_SESSION['loginid']." AND comments_log_item_id=".(integer)$ar['article_id']." AND comments_log_modul='article'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar7 = mysql_fetch_array($vysledek7);
		
		$comments_log_comments = $ar7['comments_log_comments'];
		
		/* Nacteni sablony */
		include "templates/tpl.show.channel.".$temp_num.".php";
		$i++;
	}
}
/***********************************************************************************************************
*
*		ZOBRAZENI TEAMU
*
***********************************************************************************************************/
function ZobrazeniAdminTeam($category){
	
	global $db_admin,$db_country,$db_admin_clan,$db_admin_contact,$db_admin_game,$db_admin_hw;
	global $url_admins,$url_flags;
	
	$res = mysql_query("SELECT admin_id, admin_cat1_order, admin_cat2_order, admin_cat3_order, admin_cat1, admin_cat2, admin_cat3 FROM $db_admin WHERE admin_status='admin' AND (admin_cat1=".(integer)$category." OR admin_cat2=".(integer)$category." OR admin_cat3=".(integer)$category.")") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	//if ($ar['admin_cat1'] != 0){$admin_cat = $ar['admin_cat1_order'];} elseif ($ar['admin_cat2'] != 0){$admin_cat = $ar['admin_cat2_order'];} elseif ($ar['admin_cat2'] != 0){$admin_ca2 = $ar['admin_cat1_order'];}
	while ($ar = mysql_fetch_array($res)){
		$team_array[$i] = array($ar['admin_cat1_order'],$ar['admin_id']);
		$i++;
	}
	/*
	$num = count($team_array);
	$i=$num+1;
	$res = mysql_query("SELECT admin_id, admin_cat2_order FROM $db_admin WHERE admin_status='admin' AND admin_cat2=".(integer)$category) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$team_array[$i] = array($ar['admin_cat2_order'],$ar['admin_id']);
		$i++;
	}
	$num = count($team_array);
	$i=$num+1;
	$res = mysql_query("SELECT admin_id, admin_cat3_order FROM $db_admin WHERE admin_status='admin' AND admin_cat3=".(integer)$category) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$team_array[$i] = array($ar['admin_cat3_order'],$ar['admin_id']);
		$i++;
	}*/
	if ($team_array){
		sort($team_array);
		$num = count($team_array);
		$i=0;
		while ($i<$num){
			$aid_admin = $team_array[$i][1];
			$res = mysql_query("SELECT adm.*, aco.*, acl.*, aga.*, ahw.*, c.*
				FROM $db_admin AS adm
				JOIN $db_admin_contact AS aco ON aco.aid = adm.admin_id
				JOIN $db_admin_clan AS acl ON acl.aid = adm.admin_id
				JOIN $db_admin_game AS aga ON aga.aid = adm.admin_id
				JOIN $db_admin_hw AS ahw ON ahw.aid = adm.admin_id
				JOIN $db_country AS c ON c.country_id = aco.admin_contact_country
				WHERE adm.admin_id=".(integer)$aid_admin) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			if (AGet($_GET,'team_detail') == ""){$_GET['team_detail'] = "close";}
			if ($ar['admin_userimage2'] == ""){$admin_userimage2 = "0000000002.gif";} else {$admin_userimage2 = $ar['admin_userimage2'];}
			
			$day = substr($ar['admin_contact_birth_day'], 6, 2);		// vrátí "den"
			$month = substr($ar['admin_contact_birth_day'], 4, 2); // vrátí "mesic"
			$year = substr($ar['admin_contact_birth_day'], 0, 4); // vrátí "rok"
			
			$admin_day_of_birth = $day.'.'.$month.'.'.$year;
			$admin_id = $ar['admin_id'];
			$admin_nick = $ar['admin_nick'];
			$admin_firstname = $ar['admin_firstname'];
			$admin_name = $ar['admin_name'];
			$admin_player_status = ""; //$ar['admin_clan_player_status'];
			$country_shortname = $ar['country_shortname'];
			$admin_email = $ar['admin_email'];
			$admin_contact_icq = $ar['admin_contact_icq'];
			$admin_contact_country = $ar['admin_contact_country'];
			$admin_contact_city = $ar['admin_contact_city'];
			$admin_hw_cpu = PrepareFromDB($ar['admin_hw_cpu'],1);
			$admin_hw_mb = PrepareFromDB($ar['admin_hw_mb'],1);
			$admin_hw_ram = PrepareFromDB($ar['admin_hw_ram'],1);
			$admin_hw_vga = PrepareFromDB($ar['admin_hw_vga'],1);
			$admin_hw_hdd = PrepareFromDB($ar['admin_hw_hdd'],1);
			$admin_hw_cd = PrepareFromDB($ar['admin_hw_cd'],1);
			$admin_hw_soundcard = PrepareFromDB($ar['admin_hw_soundcard'],1);
			$admin_hw_monitor = PrepareFromDB($ar['admin_hw_monitor'],1);
			$admin_hw_mouse = PrepareFromDB($ar['admin_hw_mouse'],1);
			$admin_hw_mousepad = PrepareFromDB($ar['admin_hw_mousepad'],1);
			$admin_hw_headset = PrepareFromDB($ar['admin_hw_headset'],1);
			$admin_hw_key = PrepareFromDB($ar['admin_hw_key'],1);
			$admin_hw_gamepad = PrepareFromDB($ar['admin_hw_gamepad'],1);
			$admin_hw_os = PrepareFromDB($ar['admin_hw_os'],1);
			$admin_hw_conection = PrepareFromDB($ar['admin_hw_conection'],1);
			$admin_game_resolution = $ar['admin_game_resolution'];
			$admin_game_mouse_sens = $ar['admin_game_mouse_sens'];
			$admin_game_mouse_accel = $ar['admin_game_mouse_accel'];
			$admin_game_mouse_invert = $ar['admin_game_mouse_invert'];
			$admin_game_fav_weapon = $ar['admin_game_fav_weapon'];
			$admin_game_fav_team = $ar['admin_game_fav_team'];
			$admin_game_fav_map = $ar['admin_game_fav_map'];
			include "templates/tpl.zobrazeni.admin.team.php";
			$i++;
		}
	}
}
/***********************************************************************************************************
*
*		KONTROLA OPRAVNENI
*
***********************************************************************************************************/
function CheckUser(){
	
	global $db_setup;
	
	// Nacteme nastaveni diskuze
	$res2 = mysql_query("SELECT setup_forum_anonym FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar2 = mysql_fetch_array($res2);
	return $ar2[0];
}
/***********************************************************************************************************
*
*		FUNCTION - TRANSFORM TO ASCII
*
*		Transformuje zadany text do ASCII - Vhodne k zobrazovani email adres proti zneuziti Spamboty
*
/**********************************************************************************************************/
function TransToASCII($word){
	
	$num = strlen($word);
	$i=0;
	$y=$num;
	$word_all = FALSE;
	while($i<$num){
		$char = substr($word, -$y, 1);
		$ord_char = ord($char);
		$word_all .= '&#'.$ord_char.';';
		$y--;
		$i++;
	}
	return $word_all;
}
/***********************************************************************************************************
*
*		GAME SERVERS
*
*		Funkce pro zobrazení herních serveru
*
/**********************************************************************************************************/
function GameServers(){
	
	global $db_gamesrv,$db_clan_games,$db_country;
	global $url_flags;
	
	$_GET['lang'] = AGet($_GET,'lang');
	
	$res = mysql_query("SELECT cg.clan_games_id, cg.clan_games_game FROM $db_gamesrv AS gs, $db_clan_games AS cg WHERE cg.clan_games_id = gs.clans_gameservers_game_id GROUP BY gs.clans_gameservers_game_id ORDER BY cg.clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		echo "<div class=\"eden_gamesrv_content\"><br><h1>".$ar['clan_games_game']."</h1></div>\n";
		echo "<div class=\"eden_gamesrv_content\">\n";
		echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_01_hdl\">"._GAMESRV_COUNTRY."</div>\n";
		echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_02_hdl\">"._GAMESRV_NAME."</div>\n";
		echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_03_hdl\">"._GAMESRV_IP."</div>\n";
		echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_04_hdl\">"._GAMESRV_MODE."</div>\n";
		echo "</div>";
		$res2 = mysql_query("SELECT gs.clans_gameservers_name, gs.clans_gameservers_ip, gs.clans_gameservers_mode, c.country_shortname, c.country_name	FROM $db_gamesrv AS gs, $db_country AS c WHERE c.country_id = gs.clans_gameservers_country_id AND gs.clans_gameservers_game_id = ".(integer)$ar['clan_games_id']." ORDER BY gs.clans_gameservers_ip ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar2 = mysql_fetch_array($res2)){
			echo "<div class=\"eden_gamesrv_content\">\n";
			echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_01\"><img src=\"".$url_flags.$ar2['country_shortname'].".gif\" alt=\""; echo NazevVlajky($ar2['country_shortname'],$_GET['lang']); echo "\" title=\""; echo NazevVlajky($ar2['country_shortname'],$_GET['lang']); echo "\" width=\"18\" height=\"12\" border=\"0\"></div>\n";
			echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_02\">".$ar2['clans_gameservers_name']."</div>\n";
			echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_03\">".$ar2['clans_gameservers_ip']."</div>\n";
			echo "	<div class=\"eden_gamesrv_col\" id=\"eden_gamesrv_col_04\">".$ar2['clans_gameservers_mode']."</div>\n";
			echo "</div>";
		}
	}
}
/***********************************************************************************************************
*
*		FUNCTION - WHO IS ONLINE
*
*		Zobrazuje online uzivatele na serveru
*
/**********************************************************************************************************/
function WhoIsOnline(){
	
	global $db_sessions,$db_admin,$db_admin_contact,$db_country,$db_setup;
	global $url_flags;
	global $eden_cfg;
	
	/* Nacteni nastaveni */
	$res_setup = mysql_query("SELECT setup_article_archive, setup_reg_admin_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res = mysql_query("SELECT a.admin_id, a.admin_nick, a.admin_firstname, a.admin_name, ac.admin_contact_country, ac.admin_contact_icq, ac.admin_contact_xfire, s.sessions_user, c.country_shortname 
	FROM $db_admin AS a, 
	$db_sessions AS s, 
	$db_country AS c, 
	$db_admin_contact AS ac 
	WHERE s.sessions_user = a.admin_uname AND s.sessions_pages = '".mysql_real_escape_string($eden_cfg['misc_web'])."' AND ac.aid = a.admin_id AND ac.admin_contact_country = c.country_id GROUP BY s.sessions_user ORDER BY a.admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "<table>\n";
	while ($ar = mysql_fetch_array($res)){
		$admin_id = $ar['admin_id'];
		if ($ar_setup['setup_reg_admin_nick'] == 1){$admin_nickname = $ar['admin_nick'];} else {$admin_nickname = $ar['admin_firstname'].' '.$ar['admin_name'];}
		$admin_contact_icq = $ar['admin_contact_icq'];
		$admin_contact_xfire = $ar['admin_contact_xfire'];
		$admin_contact_country = $ar['admin_contact_country'];
		$country_shortname = $ar['country_shortname'];
		include "templates/tpl.users.whoisonline.php";
	}
	echo "</table>\n";
}

/***********************************************************************************************************
*
*		FUNCTION - ShowPodcasts
*
*		Zobrazi podcasty
*
*		Staci dat na stranku odkaz jako:
*		<a href="http://www.esuba.eu/eden_rss.php?id=1&amp;project=esuba" target="_blank">RSS</a>
*		Povinne polozky:
*		rchid 		= id RSS kanalu
*		project		= nazev projektu pro nacteni spravneho konfigu
*
*
/**********************************************************************************************************/
function ShowPodcasts($id = 0){
	
	global $db_podcast_channel,$db_podcast;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($id == 0){
		$res_rss_ch = mysql_query("SELECT podcast_channel_id, podcast_channel_title, podcast_channel_items_num FROM $db_podcast_channel WHERE podcast_channel_block=0 ORDER BY podcast_channel_title ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		$res_rss_ch = mysql_query("SELECT podcast_channel_id, podcast_channel_title, podcast_channel_items_num FROM $db_podcast_channel WHERE podcast_channel_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	
	echo "<table class=\"eden_podcasts\" cellspacing=\"2\" cellpadding=\"2\">";
	while ($ar_rss_ch = mysql_fetch_array($res_rss_ch)){
		echo "	<tr class=\"eden_podcasts_title_channels\">";
		echo "		<td colspan=\"2\"><h1>".$ar_rss_ch['podcast_channel_title']."<h1></td>";
		echo "		<td colspan=\"3\" align=\"right\"><a href=\"index.php?action=podcasts"; if ($id == 0){ echo "&amp;id=".$ar_rss_ch['podcast_channel_id'];} echo "&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"; if ($id == 0){echo _PODCASTS_ITEM_SHOW_ALL_ITEMS;}else{echo _PODCASTS_ITEM_SHOW_ALL_CHANNELS;} echo "</a></td>";
		echo "	</tr>";
		echo "	<tr class=\"eden_podcasts_title_items\">";
		echo "		<td class=\"eden_poodcasts_title_id\">ID</td>";
		echo "		<td class=\"eden_poodcasts_title_title\">"._PODCASTS_ITEM_TITLE."</td>";
		echo "		<td class=\"eden_poodcasts_title_date\">"._PODCASTS_ITEM_DATE."</td>";
		echo "		<td class=\"eden_poodcasts_title_duration\">"._PODCASTS_ITEM_DURATION."</td>";
		echo "	</tr>";
		$res_rss_item = mysql_query("SELECT COUNT(*) FROM $db_podcast WHERE podcast_channel_id=".(integer)$ar_rss_ch['podcast_channel_id']." AND podcast_block=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$item_num = mysql_fetch_array($res_rss_item);
		if (!isset($item_hits) || $item_hits == 0){$item_hits = $ar_rss_ch['podcast_channel_items_num'];}
		if (AGet($_GET,'item_page') == ""){$_GET['item_page'] = 1;}
		$item_stw2 = ($item_num[0]/$item_hits);
		$item_stw2 = (integer)$item_stw2;
		if ($item_num[0]%$item_hits > 0) {$item_stw2++;}
		$item_np = $_GET['item_page'] + 1;
		$item_pp = $_GET['item_page'] - 1;
		if ($_GET['item_page'] == 1) { $item_pp = 1; }
		if ($item_np > $item_stw2) { $item_np = $item_stw2;}
		
		$item_sp = ($_GET['item_page'] - 1) * $item_hits;
		$item_ep = ($_GET['item_page'] - 1) * $item_hits + $item_hits;
		
		$limit = "LIMIT ".(integer)$item_sp.", ".(integer)$item_hits;
		$res_rss_item = mysql_query("SELECT podcast_id, podcast_title, podcast_author, podcast_pub_date, podcast_duration FROM $db_podcast WHERE podcast_channel_id=".(integer)$ar_rss_ch['podcast_channel_id']." AND podcast_block=0 ORDER BY podcast_pub_date DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$y=1;
		while ($ar_rss_item = mysql_fetch_array($res_rss_item)){
			echo "<tr "; if ($y % 2 == 0){echo "class=\"suda\"";} echo ">";
			echo "	<td class=\"eden_poodcasts_id\">".$ar_rss_item['podcast_id']."</td>";
			echo "	<td class=\"eden_poodcasts_title\"><a href=\"".$eden_cfg['url_edencms']."eden_download.php?id=".$ar_rss_item['podcast_id']."&amp;mirr=podcasts&amp;project=".$_SESSION['project']."\" target=\"_blank\">".$ar_rss_item['podcast_title']."</a></td>";
			echo "	<td class=\"eden_poodcasts_date\">".FormatDatetime($ar_rss_item['podcast_pub_date'],"d.m.Y")."</td>";
			echo "	<td class=\"eden_poodcasts_duration\">".$ar_rss_item['podcast_duration']."</td>";
			echo "</tr>";
			$y++;
		}
		/* Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima */
		if ($item_stw2 > 1 && $id =! 0){
			echo "<tr><td colspan=\"5\">";
			//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
			if ($_GET['item_page'] > 1){echo "<br><a href=\"index.php?action=podcasts&amp;id=".$id."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;item_page=".$item_pp."\">"._CMN_PREVIOUS."</a>";} else {echo "<br>"._CMN_PREVIOUS;} echo" <--|--> "; if ($_GET['item_page'] == $item_stw2){echo _CMN_NEXT;} else {echo "<a href=\"index.php?action=podcasts&amp;id=".$id."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;item_page=".$item_np."\">"._CMN_NEXT."</a>";}
			echo "</td>";
		}
	}
	echo "</table>";
}
/*******************************************************************************************************
*
*	CLASS - WRAPPED TEXT

*	How to use:
*
*	Your text: $text;
*
*	To print it wordwrapped:
*
*	include('wrappedtext.php');
*
*	$WRAPPEDTEKST=new wrappedtext($text,$maxlength,$length,$endline,$safe_returns,$striphtml,$allowed_tags,$maillink,$urllink,$target);
*
*	$maxlength, maxium length of the words you will allow.
*	$length, _split word long then $length in to piece of $length.
*	$length, separator, b.e. <br> or \n
*	$safe_returns, set to 1 if you want to change \n to <br>
*	$striphtml, set to 1 if you want to strip (some) htmlcode from your text
*	$allowed_tags, string of allowed tags if set $striphtml, b.e. '<a><font>'
*	$maillink, set to 1 if you want every emailadress in $text changed to <a href="mailto:
*	$urllink, set to 1 if you want every url in $text change to a hyperlink
*	$target taget for $urllink, be '_new'
*	NOTICE if you set maillink or urllink to 1 you have to set $striphtml to 1 also. If so don't add <a> to $allowed_tags!!
*
*
*******************************************************************************************************/
class WrappedText {
	
	var $string;
	var $maxlength;
	var $length;
	var $endline;
	
	function WrappedText($string,$maxlength,$length,$endline="\n",$safe_returns=0,$striphtml=1,$allowed_tags='',$maillink=0,$urllink=0,$target=''){
		$this->string = trim($string)."\n";
		$this->maxlength = $maxlength;
		$this->length = $length;
		$this->endline = $endline;
		$target = empty($target)?'':' target="'.$target.'"';
		if ($striphtml)$this->string = strip_tags($this->string,$allowed_tags);
		if ($maillink)$this->string = preg_replace('/([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))/i','<a href="mailto:'."\\1".'">'."\\1".'</a>',$this->string);
		if ($urllink)$this->string = preg_replace('/((http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(\/*)(:(\d+))?([A-Z0-9_\/.?~-]*))/i','<a href="'."\\1".'"'.$target.'>'."\\1".'</a>',$this->string);
		if ($safe_returns){
			$this->string = str_replace("\n",'<br>',$this->string);
		}
		$this->WordwrapNonhtmlAndTallWordsOnly($this->string);
		$this->string = str_replace(array($this->endline.'|'.$this->endline.'|','|'.$this->endline.'|'),$this->endline,$this->string);
	}
	
	function WrapOnlyTallWords($string,$start,$for=0){
		$tempstring = '';
		if (strstr($string,' ')){
			$temparray = explode(' ',$string);
		} else {
			$temparray = array($string);
		}
		$temparray_num = count($temparray);
		for($i=0;$i < $temparray_num;$i++){
			if ($i>0){$tempstring .= ' ';}
			if (strlen($temparray[$i])>$this->maxlength){
				if ($for){
					$tempstring .= '|'.$this->endline.'|';
					$tempstring .= wordwrap($temparray[$i],$this->length,$this->endline,1);
				}
			} else {
				$tempstring .= $temparray[$i];
			}
		}
		return $tempstring;
	}
	
	function WordwrapNonhtmlAndTallWordsOnly(&$string){
		$start = 0;
		$temp = '';
		$totaal = strlen($string);
		$for = 0;
		while($start <= $totaal){
			$begin = strpos($string,'<',$start);
			if ($begin === false){
				if (substr($string,$start-strlen($this->endline),strlen($this->endline)) != $this->endline)$temp .= $this->endline;
				$temp .= $this->WrapOnlyTallWords(substr($string,$start,$for),$start);
				break;
			} else {
				if ($start != $begin){
					$temp .= $this->WrapOnlyTallWords(substr($string,$start,$begin-$start),$start,$for);
				}
				$end = strpos($string,'>',$begin);
				$temp .= substr($string,$begin,$end-$begin+1);
				$start = $end+1;
			}
			$for = 0;
		}
		$string = $temp;
	}
	
	function PrintIt(){
		echo $this->string;
	}
	
	function GetIt(){
		return $this->string;
	}
}
/***********************************************************************************************************
*
*		ZAOKROUHENI NAHORU
*
*		Zaokrouhluje VZDY nahoru na pozadovany pocet desetinnych mist (max 10)
*		$number - cislo
*		$precis - pocet desetinnych mist
*
***********************************************************************************************************/
function MyCeil($number,$precision = 2){
	
	$ex	= pow(10,$precision);
	return(ceil($number*$ex)/$ex);
}
/***********************************************************************************************************
*
*		CHECK EMAIL
*
*		Tato funkce vrati 1 pokud je emailova adresa syntakticky spravne
*
* 		$email	=	Emailova adresa, kterou chceme zkontrolovat
*
***********************************************************************************************************/
function CheckEmail($email) {
	
	$atom = '[-a-z0-9!#$%&\'*+\/=?^_`{|}~]'; // znaky tvorící uživatelské jméno
	$domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // jedna komponenta domény
	return preg_match("/^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$/", $email);
}

/***********************************************************************************************************
*
*		CONTACT FORM
*
*		Tato funkce zobrazi kontaktni formular pro odeslani dotazu na zadany email
*
*		$_GET['cfn']	=	contact_form_name
*		$_GET['cfe']	=	contact_form_email
* 		$_GET['cfc']	=	contact_form_comment
*
***********************************************************************************************************/
function ContactForm($table_width = 500) {
	
	global $db_admin;
	global $project;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['cfc'] = AGet($_GET,'cfc');
	$_GET['cfn'] = AGet($_GET,'cfn');
	$_GET['cfe'] = AGet($_GET,'cfe');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($_SESSION['loginid']){
		$res_adm = mysql_query("SELECT admin_firstname, admin_name, admin_email FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_adm = mysql_fetch_array($res_adm);
	}
	
	echo "	<script type=\"text/javascript\">\n";
	echo "	<!--\n";
	echo "	function CheckContactForm(formular) {\n";
	echo "		re = new RegExp(\"^[^@\\.]([\\.]?[^@\\.]+)*@([^@\\.]+[\\.]{1}[^@\\.]+)+\$\");\n";
	echo "		if (formular.contact_form_name.value == \"\"){\n";
	echo "			alert (\""._ERR_CONTACT_EMPTY_NAME."\");\n";
	echo "			return false;\n";
	echo "		} else if (formular.contact_form_email.value == \"\"){\n";
	echo "			alert (\""._ERR_CONTACT_EMPTY_EMAIL."\");\n";
	echo "			return false;\n";
	echo "		} else if (!re.test(formular.contact_form_email.value)){\n";
	echo "			alert (\""._ERR_CONTACT_BAD_EMAIL."\");\n";
	echo "			return false;\n";
	echo "		} else if (formular.contact_form_comment.value == \"\"){\n";
	echo "			alert (\""._ERR_CONTACT_EMPTY_COMMENT."\");\n";
	echo "			return false;\n";
	echo "		} else {\n";
	echo "			return true;\n";
	echo "		}\n";
	echo "	}\n";
	echo "	//-->\n";
	echo "	</script>";
	
	echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return CheckContactForm(this)\">";
	echo "<table width=\"".$table_width."\" cellpadding=\"2\" cellspacing=\"0\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\"><strong>"._CMN_CONTACT_FORM_NAME."</strong></td>\n";
	echo "			<td>\n";
						if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){echo "<strong>".$ar_adm['admin_firstname']." ".$ar_adm['admin_name']."</strong><input type=\"hidden\" name=\"contact_form_name\" value=\"".$ar_adm['admin_firstname']." ".$ar_adm['admin_name']."\">"; } else { echo "<input type=\"text\" name=\"contact_form_name\" value=\"".$_GET['cfn']."\" size=\"30\" maxlength=\"32\">"; } 
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\"><strong>"._CMN_CONTACT_FORM_EMAIL."</strong></td>\n";
	echo "			<td>\n";
						if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){echo "<strong>".$ar_adm['admin_email']."</strong><input type=\"hidden\" name=\"contact_form_email\" value=\"".$ar_adm['admin_email']."\">"; } else {echo "<input type=\"text\" name=\"contact_form_email\" value=\"".$_GET['cfe']."\" size=\"30\" maxlength=\"32\">"; } 
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\"><strong>"._CMN_CONTACT_FORM_COMMENT."</strong></td>\n";
	echo "			<td><textarea name=\"contact_form_comment\" cols=\"50\" rows=\"8\">".AGet($_GET,'cfc')."</textarea></td>\n";
	echo "		</tr>\n";
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		
	} else {
		echo "		<tr>\n";
		echo "			<td align=\"right\" valign=\"top\"><strong>Captcha:</strong></td>";
		echo "			<td>";
		require_once('eden_captcha.php');
        $publickey = "6Ld6AtsSAAAAAFtfSXnYjL1HrxWb5lPGKaD-Yv7x"; // you got this from the signup page
        echo recaptcha_get_html($publickey);
		echo "			</td>";
		echo "		</tr>\n";
	}
	echo "		<tr>\n";
	echo "			<td>&nbsp;</td>\n";
	echo "			<td><br><br><input type=\"submit\" name=\"send\" class=\"eden_button\" value=\""._CMN_CONTACT_FORM_SEND."\">\n";
	echo "				<input type=\"hidden\" name=\"mode\" value=\"contact_form\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "</form>\n";
}
/***********************************************************************************************************
*
*		GET NICKNAME
*
*		Ziskani uzivatelova Nicku	podle jeho ID
*
* 		$nid		=	ID admina/usera
*		$mode		=	1 - Nick
*						2 - Name (Jan Novak)
*						3 - Auto - (Podle nastaveni zobrazovani nicku/jmen)
*
***********************************************************************************************************/
function GetNickName($nid,$mode = 1){
	
	global $db_admin,$db_setup;
	
	$res_adm = mysql_query("SELECT admin_nick, admin_firstname, admin_name FROM $db_admin WHERE admin_id=".(integer)$nid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_adm = mysql_fetch_array($res_adm);
	/* Pokud je $mode 2 - Zobrazi se jmeno */
	if ($mode == 2){
		return stripslashes($ar_adm['admin_firstname'])." ".stripslashes($ar_adm['admin_name']);
	} elseif ($mode == 3) {
		/* Pokud je $mode 3 - vybere se podle nastaveni zobrazovani nicku/jmen */
		$res_setup = mysql_query("SELECT setup_show_author_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_setup = mysql_fetch_array($res_setup);
		if ($ar_setup['setup_show_author_nick'] == 2){
			return stripslashes($ar_adm['admin_firstname'])." ".stripslashes($ar_adm['admin_name']);
		}else{
			return stripslashes($ar_adm['admin_nick']);
		}
	} else {
		/* Pokud je $mode 1 (nebo cokoliv jineho) - Zobrazi se Nick */
		return stripslashes($ar_adm['admin_nick']);
	}
}
/***********************************************************************************************************
*
*		IS FRIEND?
*
*		Zjisteni zda je dany uzivatel obsazen v pratelich
*
* 		$uid		=	ID admina/usera
*		$fid		=	ID udajneho pritele
*
*		VRACI
*		true/false
*
***********************************************************************************************************/
function IsFriend($uid,$fid) {
	
	global $db_admin;
	
	$res_adm = mysql_query("SELECT admin_friends FROM $db_admin WHERE admin_id=".(integer)$uid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_adm = mysql_fetch_array($res_adm);
	$friends = array();
	$friends = explode (" ", $ar_adm['admin_friends']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
	if (in_array((integer)$fid,$friends)){return "true";} else {return "false";}
}
/***********************************************************************************************************
*
*		Browse Articles
*
*		Zobrazeni ruzneho poctu novnek podle kanalu
*
*
***********************************************************************************************************/
function BrowseArticles(){
	
	global $db_articles,$db_articles_channel,$db_comments,$db_category;
	global $eden_cfg;
	global $url_articles_channels,$url_games;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	if ($_GET['lang'] == ""){$_GET['lang'] = "cz";}
	
	$res_lang = mysql_query("SELECT language_id FROM "._DB_LANGUAGES." WHERE language_code = '".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_lang = mysql_fetch_array($res_lang);
	
	$res_article_channel = mysql_query("
	SELECT article_channel_id, article_channel_title, article_channel_image 
	FROM $db_articles_channel 
	WHERE article_channel_active = 1 AND article_channel_lang_id = ".$ar_lang['language_id']."
	ORDER BY article_channel_importance DESC, article_channel_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_article_channel = mysql_num_rows($res_article_channel);
	
	echo "<table id=\"eden_browse_articles\">";
	$i=1;
	while($ar_article_channel = mysql_fetch_array($res_article_channel)){
		$showtime = formatTime(time(),"YmdHis");
		$res_articles = mysql_query("SELECT article_id, article_headline, article_category_id FROM $db_articles WHERE article_channel_id=".(integer)$ar_article_channel['article_channel_id']." AND article_parent_id=0 AND article_publish=1 AND $showtime BETWEEN article_date_on AND article_date_off ORDER BY article_date_on DESC LIMIT 4") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if ($i == 1){echo "<tr valign=\"top\">";}
			echo "<td width=\"50%\">\n";
			echo "	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"eden_browse_articles_list_table\">\n";
			echo "		<tr>\n";
			echo "			<th><span class=\"eden_browse_articles_channel_title\"><a href=\"".$eden_cfg['url']."index.php?action=browse_channel&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$ar_article_channel['article_channel_id']."\">"._ARTICLES_VIEW_CHANNEL."</a></span><img src=\"".$url_articles_channels.$ar_article_channel['article_channel_image']."\" width=\"12\" height=\"12\" title=\"".$ar_article_channel['article_channel_title']."\" align=\"middle\"><h2>".PrepareFromDB($ar_article_channel['article_channel_title'],1)."</h2></th>\n";
			echo "			</tr>"; 
				$y=1;
				while ($ar_articles = mysql_fetch_array($res_articles)){
					$res_category = mysql_query("SELECT category_name, category_image FROM $db_category WHERE category_id=".(integer)$ar_articles['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_category = mysql_fetch_array($res_category);
					$res_comm = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar_articles['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num_comm = mysql_fetch_array($res_comm);
					echo "<tr "; if ($y % 2 == 0){echo "class=\"suda\"";} echo ">";
					echo "	<td class=\"eden_browse_articles_title\"><span class=\"eden_browse_articles_comm\">".$num_comm[0]."</span><img src=\"".$url_games.$ar_category['category_image']."\" width=\"12\" height=\"12\" title=\"".$ar_category['category_name']."\"><a href=\"".$eden_cfg['url']."index.php?action=clanek&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$ar_articles['article_id']."&amp;page_mode=\" title=\"".$ar_articles['article_headline']."\">".ShortText($ar_articles['article_headline'],40)."</a></td>";
					echo "</tr>";
					$y++;
				}
			echo "	</table>";
			echo "</td>";
			if ($i == 1){$i++;}else{$i=1;echo "</tr>";}
	}
	if ($i % 2 == 0){echo "</tr>";}
	echo "</table>";
}
/***********************************************************************************************************
*
*		AdminCustom
*
*		Zobrazeni prvku podle vyberu uzivatele
*
*		$cid	-	ID custom prvku
*
*
***********************************************************************************************************/
function AdminCustom($cid){
	
	global $db_admin_customize;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($_SESSION['loginid']){
		$res = mysql_query("SELECT admin_customize_value FROM $db_admin_customize WHERE admin_customize_admin_id=".(integer)$_SESSION['loginid']." AND admin_customize_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if (mysql_num_rows($res) == 0){
			mysql_query("INSERT INTO $db_admin_customize VALUES('".(integer)$_SESSION['loginid']."','".(integer)$cid."','1')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			return 1;
		} else {
			return $ar['admin_customize_value'];
		}
	} else {
		return 1;
	}
}
/***********************************************************************************************************
*
*		PokerHandsAdd
*
*		Pridani pokerove hry
*
***********************************************************************************************************/
function PokerHandsAdd(){
	
	global $db_poker_hands;
}
/***********************************************************************************************************
*
*		PokerHandsShow
*
*		Zobrazeni pokerove hry
*
***********************************************************************************************************/
function PokerHandsShow(){
	
	global $db_poker_hands;
	global $eden_cfg;
	global $url_poker_cards_big;
	
	$res_hands = mysql_query("SELECT * FROM $db_poker_hands WHERE poker_hands_id=".(integer)$GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_hands = mysql_fetch_array($res_hands);
}
/***********************************************************************************************************
*
*		CheckSkin
*
*		Zobrazeni aktualniho skinu
*
***********************************************************************************************************/
function CheckSkin(){
	
	global $db_admin_info;
	global $eden_cfg;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_admin = mysql_query("SELECT admin_info_customize_skin FROM $db_admin_info WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_admin = mysql_fetch_array($res_admin);
	if ($_SESSION['loginid'] != ""){
		if ($ar_admin['admin_info_customize_skin'] != ""){
			return $ar_admin['admin_info_customize_skin'];
		} else {
			return $eden_cfg['misc_skins_basic'];
		}
	} else {
		return $eden_cfg['misc_skins_basic'];
	}
}
/***********************************************************************************************************
*
*		ODSTRANENI SLUZEB Z ODKAZU
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
*		SHOW COUNTRY - Podle ID zeme zobrazi jeji nazev v danem jazyku
*
***********************************************************************************************************/
function ShowCountryName($id){
	
	global $db_country;
	
	$res = mysql_query("SELECT country_name FROM $db_country WHERE country_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	return($ar['country_name']);
}
/***********************************************************************************************************
*
*		SHOW PROFILE - Nahodne zobrazi profil
*
***********************************************************************************************************/
function ShowProfile(){
	
	global $db_profiles,$db_flags,$db_country,$db_clan_games;
	global $eden_cfg;
	global $url_profiles,$url_flags;
	
	$res_profile = mysql_query("
	SELECT profile_id 
	FROM $db_profiles 
	WHERE profile_allow = 1
	") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$profile = Array();
	while ($ar_profile = mysql_fetch_array($res_profile)){
		$profile[] .= $ar_profile['profile_id'];
	}
	$num = count($profile) - 1; // -1 je prevence proti zobrazeni cisla minus rozsah pole
	$random_num = rand (0,$num);
	$random_profile = $profile[$random_num];
	
	$res_profile = mysql_query("
	SELECT p.*, g.clan_games_game, c.country_name, c.country_shortname 
	FROM $db_profiles AS p, $db_clan_games AS g, $db_country AS c 
	WHERE p.profile_id=".(integer)$random_profile." AND g.clan_games_id=p.profile_game_id AND c.country_id=p.profile_country_id
	") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_profile = mysql_fetch_array($res_profile);
	
	/* Nacteni sablony */
	include "templates/tpl.profile.php";
}
/***********************************************************************************************************
*
*		Ip2Country
*
*		Return Country name/code from IP address
*
***********************************************************************************************************/
class ip2country {
	
	var $IP;					// IP to looking for
	
	var $Prefix1;				// Country prefix (2char) ex.: US
	var $Prefix2;				// Country prefix (3char) ex.: USA
	var $Country;				// Country name  ex.: UNITED STATE
	
	// db values
	var $db_ip_from_colname;	// Your own ip_from column name
	var $db_ip_to_colname;		// Your own ip_to column name
	var $db_prefix1_colname;	// Your own prefix1 column name
	var $db_prefix2_colname;	// Your own prefix2 column name
	var $db_country_colname;	// Your own country column name
	
	var $_IPn;					// Private - network address
	
	// Constructor
	function ip2country($ip){
		
		if ($ip) {
			$this->_IPn = InetAton($ip);
			$this->IP	= $ip;
		}
	}
	
	// Look in file or database
	function LookUp(){
		
		global $db_ip_to_country;
		
		$result = mysql_query("SELECT ip_to_country_country_code2, ip_to_country_country_code3, ip_to_country_country_name FROM $db_ip_to_country WHERE ".$this->_IPn.">=ip_to_country_ip_from AND ".$this->_IPn."<=ip_to_country_ip_to") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$row = mysql_fetch_row($result);
		if ($row){
			$this->Prefix1 = $row[0];
			$this->Prefix2 = $row[1];
			$this->Country = $row[2];
			return true;
		} else {
			return false;
		}
	}
}
/***********************************************************************************************************
*
*		WebTermsAgreemed
*
*		Zobrazi formular pro souhlas s pravidly a podminkami na webu
*
***********************************************************************************************************/
function WebTermsAgreement(){
	
	global $db_setup_lang;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$res_setup_lang = mysql_query("SELECT setup_lang_reg_terms FROM $db_setup_lang WHERE setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup_lang = mysql_fetch_array($res_setup_lang); 
	echo "	<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">\n";
	echo "		<tr>\n";
	echo "			<td colspan=\"2\"><form action=\"".$eden_cfg['url_edencms']."eden_save.php\" method=\"post\">".PrepareFromDB($ar_setup_lang['setup_lang_reg_terms'])."</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\" align=\"right\"><input type=\"checkbox\" name=\"web_terms_agreement\" value=\"1\" /></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><strong>"._ADMIN_AGREEMENT."</strong></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\">&nbsp;</td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"submit\" class=\"eden_button\" value=\""._CMN_SUBMIT."\">\n";
	echo "				<input type=\"hidden\" name=\"filter\" value=\"".$_GET['filter']."\">\n";
	echo "				<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"mode\" value=\"web_terms_agreement\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
}
/***********************************************************************************************************
*
*		LeagueAddPlayer
*
*		Pridani hrace na vyzadani
*		$mode		=	Mod zalozeni hracova uctu
*						0 = zalozit normalni hracsky ucet bez prihlaseni do tymu - v pripade ze jiz ucet hrac ma - zadna akce
*						1 = zalozit hracsky ucet s prihlasenim do tymu a urceni pozice v tymu - v pripade ze hrac jiz ucet ma - editovat
*		$aid		=	Admin ID
*		$gid		=	Game ID
*		$ltid		=	League Team ID
*		$ltsid		=	League Team Sub ID
*		$pp			=	Player Position (0 = player, 1 = captain, 2 = asistant)
*		$tc			=	Team Confirmed
*		$pc			=	Player Confirmed
*
*		Vraci		0 - Hracksky ucet pro danou hru nemohl byt zalozen (hrac jiz existuje nebo $gid nebylo zadano)
*					1 - Hracksky ucet pro danou hru zalozen
*
***********************************************************************************************************/
function LeagueAddPlayer($aid = 0, $mode = 0, $gid = 0, $ltid = 0, $ltsid = 0, $pp = 0, $tc = 0, $pc = 0){
	
	global $db_league_players;
	
	if ($gid == 0 || $aid == 0){return 0; exit;}
	
	$res_player = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_game_id=".(integer)$gid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	switch ($mode){
		case 0:
			$player_position_player = 1;
			$player_position_assistant = 0;
			$player_position_captain = 0;
			$date_join = "1000-01-01 00:00:00";
		break;
		case 1:
			switch ($pp){
				case 0;
					$player_position_player = 1;
					$player_position_assistant = 0;
					$player_position_captain = 0;
				break;
				case 1;
					$player_position_player = 0;
					$player_position_assistant = 0;
					$player_position_captain = 1;
				break;
				case 2;
					$player_position_player = 0;
					$player_position_assistant = 1;
					$player_position_captain = 0;
				break;
			}
			if ($ltid == 0){$date_join = "1000-01-01 00:00:00";} else {$date_join = "NOW()";}
		break;
	}
	if ($ar_player[0] == 0){
		// Zalozime novy hracsky ucet
		$res = mysql_query("INSERT INTO $db_league_players VALUES(
		'',
		'".(integer)$aid."',
		'".(integer)$gid."',
		'".(integer)$ltid."',
		'".(integer)$ltsid."',
		'".(integer)$player_position_captain."',
		'".(integer)$player_position_assistant."',
		'".(integer)$player_position_player."',
		'".(integer)$tc."',
		'".(integer)$pc."',
		'".$date_join."',
		'0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if ($res){
			LeagueDraftDel(0,$gid,$aid);
			LeagueDraftDel($ltsid,$gid,0);
			LeagueAddToLOG (0,(integer)$ltid,(integer)$lstid,(integer)$aid,$gid,1,"","","");  // Zalozili jsme novy hracsky ucet
			return 1; exit;
		} else {
			return 0; exit;
		}
	} else if ($ar_player[0] >= 1 && $mode == 1){
		// Smazeme vsechny hrace, kteri jsou duplicitni (pro jednu hru muze mit zalozen hrac jen jeden ucet)
		if ($ar_player[0] > 1){
			for ($i=1;$i<$ar_player[0];$i++){
				mysql_query("DELETE FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_game_id=".(integer)$gid." LIMIT 1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
		$res = mysql_query("UPDATE $db_league_players SET 
		league_player_team_id=".(integer)$ltid.", 
		league_player_team_sub_id=".(integer)$ltsid.", 
		league_player_position_captain=".(integer)$player_position_captain.", 
		league_player_position_assistant=".(integer)$player_position_assistant.", 
		league_player_position_player=".(integer)$player_position_player.", 
		league_player_team_confirm=".(integer)$tc.", 
		league_player_player_confirm=".(integer)$pc.", 
		league_player_join_date='".$date_join."'
		WHERE league_player_admin_id=".(integer)$aid." AND league_player_game_id=".(integer)$gid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if ($res){
			LeagueDraftDel(0,$gid,$aid);
			LeagueDraftDel($ltsid,$gid,0);
			return 1; exit;
		} else {
			return 0; exit;
		}
	} else {
		return 0; exit;
	}
}
/***********************************************************************************************************
*
*		LeagueAddPlayer
*
*		Pridani hrace na vyzadani
*		$ltsid		=	League Team Sub ID
*		$gid		=	Game ID
*		$aid		=	Admin ID
*
***********************************************************************************************************/
function LeagueDraftDel($ltsid = 0, $gid = 0, $aid = 0){
	
	global $db_league_players,$db_league_draft;
	
	if ($ltsid != 0){$where = "league_draft_team_sub_id=".(integer)$ltsid;}
	if ($aid != 0){
		$res_player = mysql_query("SELECT league_player_id FROM $db_league_players WHERE league_player_admin_id=".(integer)$aid." AND league_player_game_id=".(integer)$gid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		$where = "league_draft_player_id=".$ar_player['league_player_id'];
	}
	// Pokud hrac vstoupi do tymu, ktery ma request na Draft boardu, tento se upravi
	$res_draft = mysql_query("SELECT league_draft_id, league_draft_positions FROM $db_league_draft WHERE $where AND league_draft_game_id=".(integer)$gid);
	if ($res_draft){
		$ar_draft = mysql_fetch_array($res_draft);
	}
	// TEAM options
	if ($ltsid != 0){
		// Kdyz je pocet pozadovanych pozic do teamu 1 - smazeme pozadavek z Draft boardu
		if ($ar_draft['league_draft_positions'] == 1){
			mysql_query("DELETE FROM $db_league_draft WHERE league_draft_id=".(integer)$ar_draft['league_draft_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		// Kdyz je pocet pozadovanych pozic do teamu vetsi nez jedna, odecteme 1 a ulozime
		} elseif ($ar_draft['league_draft_positions'] > 1){
			$league_draft_positions = $ar_draft['league_draft_positions'] - 1;
			mysql_query("UPDATE $db_league_draft SET league_draft_positions=".(integer)$league_draft_positions." WHERE league_draft_id=".(integer)$ar_draft['league_draft_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	// PLAYER options
	if ($aid != 0){
		mysql_query("DELETE FROM $db_league_draft WHERE league_draft_admin_id=".(integer)$aid." AND league_draft_game_id=".(integer)$gid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}
/***********************************************************************************************************
*
*		textHighlighter
*
* Text highlighter without affecting HTML tags. This class supports highlighting
* string in the double quotes of keyword for compatibility with some search methods such as
* MySQL Full-text search, Google, Yahoo...
*
* Sample usage:
* - Method 1:
*      $highlightedString = Fete_Util_Text_Highlighter::createInstance('<b>', '</b>')->highlight('PHP rules', 'rules');
* - Method 2:
*      $highlighter = new Fete_Util_Text_Highlighter();
*      $highlightedString = $highlighter->setBeforeMatch('<b>')
*                  ->setAfterMatch('</b>')
*                  ->highlight('PHP rules the world', '"PHP rules"');
*/
class textHighlighter{
    /**
     * @var string
     */
    protected $_beforeMatch = '<span class="zvyrazneni">';
    
    /**
     * @var string
     */
    protected $_afterMatch = '</span>';
    
    /**
     *
     * @param string $beforeMatch
     * @param string $afterMatch
     */
    public function __construct($beforeMatch = null, $afterMatch = null){
        if (null !== $beforeMatch) {
            $this->_beforeMatch = $beforeMatch;
        }
        
        if (null !== $afterMatch) {
            $this->_afterMatch = $afterMatch;
        }
    }
    
    /**
     *
     * @param string $beforeMatch
     * @param string $afterMatch
     * @return Fete_Util_Text_Highlighter
     */
    static public function createInstance($beforeMatch = null, $afterMatch = null){
        return new self($beforeMatch, $afterMatch);
    }
    
    /**
     *
     * @param string $beforeMatch
     * @return Fete_Util_Text_Highlighter
     */
    public function &setBeforeMatch($beforeMatch){
        $this->_beforeMatch = $beforeMatch;
        return $this;
    }
    
    /**
     *
     * @param string $afterMatch
     * @return Fete_Util_Text_Highlighter
     */
    public function &setAfterMatch($afterMatch){
        $this->_afterMatch = $afterMatch;
        return $this;
    }
    
    /**
     *
     * @param string $text
     * @param string $keyword
     * @return string highlighted string
     */
    public function highlight($text, $keyword){
        $output = '';
        $words = array();
        preg_match_all('#(?:"([^"]+)"|(?:[^\s\+\-"\(\)><~\*\'\|\\`\!@\#\$%^&_=\[\]\{\}:;,\./\?]+))#si', $keyword, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match){
            if (2 === count($match)) {
                $words[] = $match[1];
            } else {
                $words[] = $match[0];
            }
        }
        
        $words = implode('|', $words);
        $textParts = preg_split('#(<script[^>]*>.*?</script>|<style[^>]*>.*?</style>|<.+?>)#si', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        foreach ($textParts as $byHtmlPart){
            if (!empty($byHtmlPart) && $byHtmlPart{0} != '<') {
                $byHtmlPart = preg_replace('#(' . $words . ')#si', $this->_beforeMatch . '\1' . $this->_afterMatch, $byHtmlPart);
            }
            $output .= $byHtmlPart;
        }
        return $output;
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
	var $eden_cfg;
	
	function load($filename) {
		$this->image_info = getimagesize($filename);
		$this->image_type = $this->image_info[2];
		if( $this->image_type == 2){ // JPG
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == 1){ // GIF
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == 3){ // PNG
			$this->image = imagecreatefrompng($filename);
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
	function saveByFTP($thumbnail = 0, $compression = 75){
		
		global $db_admin_images;
		global $ftp_path_images_upl,$eden_cfg;
		global $conn_id;
		
		$img_name = Cislo();
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
		$upload = ftp_put($conn_id, $destination_file, $eden_cfg['dir_temp'].$userfile_name, FTP_BINARY);
		
		// Kdyz se upload obrazku podari
		if ($upload){
			if ($_POST['mode'] == "main"){
				$image_mode = 2;
			} else {
				$image_mode = 1;
			}
			
			// Ulozime zaznam o souboru do databaze
			$file_size = ftp_size($conn_id,$ftp_path_images_upl.$userfile_name);
			$res = mysql_query("INSERT INTO $db_admin_images VALUES('1','','".mysql_real_escape_string($userfile_name)."','".$image_mode."','".$this->getWidth()."','".$this->getHeight()."','".$file_size."','".mysql_real_escape_string($extenze)."','0','".mysql_real_escape_string($image_description)."',NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
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
					// Obrazek se zmensi na avolenou vysku thumbnailu (pri zachovani pomeru stran)
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
*		MetaFB
*
*		Zobrazi metainformace pro FaceBook (musi byt umisteno v hlavicce a aktivovano jen u samostatneho zobrazeni clanku
*
*		$cid		- ID clanku
*		$mode		- 1 = clanek, 2 = vyrobek
*		$doctype	- html, xhtml
*		$lang		- cz, en
*
***********************************************************************************************************/
function MetaOG($cid = 0, $mode = 1, $doctype = "html", $lang = "cz", $meta_site = ""){
	
	global $db_articles,$db_shop_clothes_design,$url_shop_clothes_design;
	global $url_articles;
	
	if ($cid != 0){
		// Vyber Modu
		if ($mode == 1){
			$res_meta = mysql_query("SELECT	article_headline, article_perex, article_img_1 FROM $db_articles WHERE article_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_meta = mysql_fetch_array($res_meta);
			$meta_mode = "article";
			$meta_title = $ar_meta['article_headline'];
			$meta_description = strip_tags($ar_meta['article_perex']);
			$meta_description = htmlspecialchars($meta_description,ENT_COMPAT);
			$meta_img_url = $url_articles;
			$meta_img = $ar_meta['article_img_1'];
		} else {
			$res_meta = mysql_query("SELECT	shop_clothes_design_title, shop_clothes_design_description_short, shop_clothes_design_img_1 FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_meta = mysql_fetch_array($res_meta);
			$meta_mode = "article";
			$meta_title = $ar_meta['shop_clothes_design_title'];
			$meta_description = strip_tags(htmlspecialchars($ar_meta['shop_clothes_design_description_short'],ENT_COMPAT));
			$meta_img_url = $url_shop_clothes_design;
			$meta_img = $ar_meta['shop_clothes_design_img_1'];
		}
		
		// Vyber Doctype
		if ($doctype == "xhtml"){$meta_end = " /";} else {$meta_end = FALSE;}
		
		// Lang
		switch ($lang){
			case "en":
				$meta_lang = "en";
			break;
			default:
				$meta_lang = "cs";
		}
		
		// Render
		$output = "<meta property=\"og:site_name\" content=\"".$meta_site."\" ".$meta_end.">\n";
		$output .= "<meta property=\"og:image\" content=\"".$meta_img_url.$meta_img."\" ".$meta_end.">\n";
		$output .= "<meta property=\"og:type\" content=\"article\" ".$meta_end.">\n";
		$output .= "<meta property=\"og:title\" content=\"".$meta_title."\" ".$meta_end.">\n";
		$output .= "<meta property=\"og:description\" lang=\"".$meta_lang."\" content=\"".$meta_description."\" ".$meta_end.">\n";
		
		return $output;
	}
}
/***********************************************************************************************************
*
*		CupReg
*
*		Zobrazi registracni formular do Cupu
*
***********************************************************************************************************/
function CupReg($cupid){
	
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "	<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">\n";
	echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php\" method=\"post\">\n";
	echo "		<br>\n";
	echo "		<tr>\n";
	echo "			<td>&nbsp;</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Name</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_name\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Tag</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_tag\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Country</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><select name=\"cups_team_country\" class=\"cup_form\"><?php\n";
						$res3 = mysql_query("SELECT country_id, country_name FROM $db_country ORDER BY name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar3 = mysql_fetch_array($res3)){
					   		echo "<option name=\"cups_team_country\" value=\"".$ar3['country_id']."\" "; if ($ar['country'] == $ar3['country_id']) {echo " selected";} if ($_SESSION['useredit_mod'] == "reg" && $ar3['country_id'] == "57"){echo " selected";} echo ">".$ar3['country_name']."</option>\n";
						}
	echo "				</select></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Leader Nick</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_cl_nick\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Leader ICQ</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_cl_icq\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Leader Email</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_cl_email\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan Roster</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><textarea type=\"text\" lass=\"cup_form\" cols=\"30\" rows=\"5\" name=\"cups_team_roster\"></textarea></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\"><strong>Clan War Server</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" class=\"cup_form\" name=\"cups_team_cw_server\" size=\"30\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td width=\"130\">&nbsp;</td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"Submit\" class=\"cup_form\" value=\"Register\"></td>\n";
	echo "		</tr>\n";
	echo "		<input type=\"hidden\" name=\"filter\" value=\"".$_GET['filter']."\">\n";
	echo "		<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
	echo "		<input type=\"hidden\" name=\"mode\" value=\"cups_reg\">\n";
	echo "		</form>\n";
	echo "	</table>";
}
/***********************************************************************************************************
*
*		ODHLASENI Z PRIJIMANI EMAILU
*
*		Znamena nesouhlas s prijmem reklamnich emailu
*		Priklad: http://www.esuba.eu/index.php?action=email_opt_out&email=pittbull@esuba.eu
*
*		$email	- email uzivatele
*
***********************************************************************************************************/
function EmailOptOut($email = ""){
	
	global $db_admin;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if (CheckEmail($email) == 1){
		$update = mysql_query("UPDATE $db_admin SET admin_agree_email=0 WHERE admin_email='".mysql_real_escape_string($email)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_action = "action=msg&msg=email_opt_out_ok";
	} else {
		$ar_action = "action=msg&msg=email_opt_out_er";
	}
	header ("Location: ".$eden_cfg['url']."index.php?".$ar_action."&lang=".$_GET['lang']."&filter=".$_GET['filter']."");
	exit;
}
/***********************************************************************************************************
*
*		CAPTCHA
*
*
***********************************************************************************************************/
class EdenCaptcha {
	
	/**
	 * Construct
	 */
	public function __construct($eden_cfg) {
		
		require_once("eden_captcha.php");
		
		// Get captcha type
		$this->captcha_type = $eden_cfg['captcha_type'];
		
		// Get EDEN cfg
		$this->eden_cfg = $eden_cfg;
		
	}
	
	/**
	 * Show Captcha
	 */
	public function CaptchaShow(){
		switch ($this->captcha_type){
			case "google":
				$result = $this->GoogleReCaptchaShow();
			break;
			case "s3":
				$result = $this->S3CaptchaShow();
			break;
			
		}
		return $result;
	}
	
	/**
	 * Check Captcha
	 */
	public function CaptchaCheck(){
		
		switch ($this->captcha_type){
			case "google":
				$result = $this->GoogleReCaptchaCheck();
			break;
			case "s3":
				$result = $this->S3CaptchaCheck();
			break;
			
		}
		return $result;
	}
	
	/**
	 * Show Google Captcha
	 */
	private function GoogleReCaptchaShow(){
		return recaptcha_get_html(_EDEN_GOOGLE_CAPTCHA_PUBLIC_KEY);
	}
	
	/**
	 * Check Google Captcha
	 */
	private function GoogleReCaptchaCheck(){
		$resp = recaptcha_check_answer (_EDEN_GOOGLE_CAPTCHA_PRIVATE_KEY,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
		if ($resp->is_valid){
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Show S3 Captcha
	 */
	private function S3CaptchaShow(){
		$output = "";
		$output .= "<style type=\"text/css\">
			#capcha div {
				float: left;
			}
		</style>\n";
		$output .= "<script type=\"text/javascript\">
			$(document).ready(function() {
				$('#capcha').s3Capcha();
			});
		</script>";
		
		$output .= "<div id=\"capcha\">";
		session_start();
		$rand = mt_rand(0,(count($this->eden_cfg['captcha_s3_values'],0)-1));
		$items = $this->twodshuffle($this->eden_cfg['captcha_s3_values']);
		$item = $items[$rand];
		$item_name = $item[1];
		$output .= _CAPTCHA_S3_VERIFY." <strong>".$item_name."</strong><br>\n";
		for($i=0; $i < count($items,0); $i++) {
			$value = $items[$i];
		    $value2[$i] = mt_rand();
		    $output .= "<div>";
			$output .= "<span>".$value[1]." <input type=\"radio\" name=\"s3capcha\" value=\"".$value2[$i]."\"></span>";
			$output .= "<div style=\"background: url(".$this->eden_cfg['captcha_s3_image_path'].$value[0].".".$this->eden_cfg['captcha_s3_image_ext'].") bottom left no-repeat; width:".$this->eden_cfg['captcha_s3_image_width']."px; height:".$this->eden_cfg['captcha_s3_image_height']."px;cursor:pointer;display:none;\" class=\"img\" /></div>";
			$output .= "</div>"."\n";
		}
		$_SESSION['s3capcha'] = $value2[$rand];
		$output .= "</div>";
		
		return $output;
	}
	
	/**
	 * Check S3 Captcha
	 */
	private function S3CaptchaCheck(){
		session_start();
		if($_POST['s3capcha'] == $_SESSION['s3capcha'] && $_POST['s3capcha'] != '') {
		    unset($_SESSION['s3capcha']);
		    return TRUE;
		} else {
		    return FALSE;
		}
	}
	
	private function twodshuffle($array){
	    // Get array length
	    $count = count($array);
	    // Create a range of indicies
	    $indi = range(0,$count-1);
	    // Randomize indicies array
	    shuffle($indi);
	    // Initialize new array
	    $newarray = array($count);
	    // Holds current index
	    $i = 0;
	    // Shuffle multidimensional array
	    foreach ($indi as $index)
	    {
	        $newarray[$i] = $array[$index];
	        $i++;
	    }
	    return $newarray;
	}
}

class EdenHelper {
	
	/**
	 * @param string		Part of the filename
	 * @return string
     */
	public static function prepareInclude($str){
		$str = ltrim($str);
		$str = rtrim($str);
		$str = strip_tags($str,"");
		$str = stripslashes($str);
		
		return $str;
	}

}

