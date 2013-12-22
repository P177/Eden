<?php
/* Provereni opravneni */
if ($_GET['action'] == "clan_awards_add"){
	if (CheckPriv("groups_clan_awards_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "clan_awards_edit"){
	if (CheckPriv("groups_clan_awards_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_awards.php?action=clan_awards_add&y=".$_GET['y']."&project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "clan_awards_del"){
	if (CheckPriv("groups_clan_awards_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_awards.php?action=clan_awards_add&y=".$_GET['y']."&project=".$_SESSION['project']."&msg=nep");}
}

// Výčet povolených tagů
$allowtags = "";
$clan_award_name = strip_tags($_POST['clan_award_name'],"");
$clan_award_link = strip_tags($_POST['clan_award_link'],"");
$clan_award_place = strip_tags($_POST['clan_award_place'],"");

$clan_award_date = PrepareDateForSpiffyCalendar($_POST['clan_award_date'],"01:00:00");
/* Ulozeni oceneni po pridani */
if ($_GET['action'] == "clan_awards_add"){
	
	$res = mysql_query("INSERT INTO $db_clan_awards VALUES(
	NULL,
	'".(integer)$_POST['clan_award_country_id']."',
	'".(integer)$_POST['clan_award_game_id']."',
	'".mysql_real_escape_string($clan_award_name)."',
	'".mysql_real_escape_string($clan_award_link)."',
	'".mysql_real_escape_string($clan_award_date)."',
	'".mysql_real_escape_string($clan_award_place)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
		$msg = "add_ok";
}

/* Ulozeni oceneni po editaci */
if ($_GET['action'] == "clan_awards_edit"){
	//$clan_award_date = ;
	$res = mysql_query("UPDATE $db_clan_awards SET
	clan_award_country_id=".(integer)$_POST['clan_award_country_id'].",
	clan_award_game_id=".(integer)$_POST['clan_award_game_id'].",
	clan_award_name='".mysql_real_escape_string($clan_award_name)."',
	clan_award_link='".mysql_real_escape_string($clan_award_link)."',
	clan_award_date='".mysql_real_escape_string($clan_award_date)."',
	clan_award_place='".mysql_real_escape_string($clan_award_place)."' WHERE clan_award_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}

/* Odstraneni oceneni */
if ($_GET['action'] == "clan_awards_del"){
	$res = mysql_query("DELETE FROM $db_clan_awards WHERE clan_award_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "del_ok";
}

$res = mysql_query("SELECT clan_award_date FROM $db_clan_awards WHERE clan_award_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar = mysql_fetch_array($res);
$y = FormatDatetime($ar['clan_award_date'],"Y");

header ("Location: ".$eden_cfg['url_cms']."modul_clan_awards.php?action=clan_awards_add&y=".$_GET['y']."&project=".$_SESSION['project']."&msg=".$msg);
exit;