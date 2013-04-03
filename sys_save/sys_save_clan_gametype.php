<?php
/* Provereni opravneni */
if (CheckPriv("groups_clanwars_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}

// Výčet povolených tagů
$allowtags = "";
$game_type = strip_tags($_POST['game_type'],$allowtags);
$game_type = str_replace("&amp;","&",$game_type);
if ($_GET['action'] == "gametype_add"){
	mysql_query("INSERT INTO $db_clan_gametype VALUES('','".(integer)$_POST['game_id']."','".mysql_real_escape_string($game_type)."','".(integer)$_POST['gametype_main']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "add_ok";
}
if ($_GET['action'] == "gametype_edit"){
	mysql_query("UPDATE $db_clan_gametype SET clan_gametype_game_id=".(integer)$_POST['game_id'].", clan_gametype_game_type='".mysql_real_escape_string($game_type)."', clan_gametype_main=".(integer)$_POST['gametype_main']." WHERE clan_gametype_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['action'] = "gametype_add";
	$msg = "edit_ok";
}
if ($_GET['action'] == "gametype_del"){
	mysql_query("DELETE FROM $db_clan_gametype WHERE clan_gametype_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['action'] = "gametype_add";
	$msg = "del_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_clanwars.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&msg=".$msg);
exit;