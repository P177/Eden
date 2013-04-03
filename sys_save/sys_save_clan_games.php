<?php
/* Provereni opravneni */
if ($_GET['action'] == "clan_game_add"){
	if (CheckPriv("groups_clan_games_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "clan_game_edit"){
	if (CheckPriv("groups_clan_games_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_games.php?action=clan_game_add&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "clan_game_del"){
	if (CheckPriv("groups_clan_games_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_games.php?action=clan_game_add&project=".$_SESSION['project']."&msg=nep");}
}

// Výčet povolených tagů
$allowtags = "";
$game = strip_tags($_POST['game'],$allowtags);
$shortname = strip_tags($_POST['shortname'],$allowtags);
if ($_GET['action'] == "clan_game_add"){
	mysql_query("INSERT INTO $db_clan_games VALUES('','".(integer)$_POST['clan_game_main_id']."','".mysql_real_escape_string($game)."','".mysql_real_escape_string($shortname)."','".(integer)$_POST['clan_game_repre']."','".(integer)$_POST['clan_game_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "add_ok";
}
if ($_GET['action'] == "clan_game_edit"){
	mysql_query("UPDATE $db_clan_games SET  clan_games_game='".mysql_real_escape_string($game)."', clan_games_game_main_id=".(integer)$_POST['clan_game_main_id'].", clan_games_shortname='".mysql_real_escape_string($shortname)."', clan_games_repre=".(integer)$_POST['clan_game_repre'].", clan_games_active=".(integer)$_POST['clan_game_active']." WHERE clan_games_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "clan_game_del"){
	mysql_query("DELETE FROM $db_clan_games WHERE clan_games_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "del_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_games.php?action=clan_game_add&project=".$_SESSION['project']."&msg=".$msg);
exit;