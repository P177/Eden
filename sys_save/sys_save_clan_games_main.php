<?php
/* Provereni opravneni */
if ($_GET['action'] == "clan_game_main_add"){
	if (CheckPriv("groups_clan_games_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "clan_game_main_edit"){
	if (CheckPriv("groups_clan_games_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_games_main.php?action=clan_game_add&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "clan_game_main_del"){
	if (CheckPriv("groups_clan_games_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_clan_games_main.php?action=clan_game_add&project=".$_SESSION['project']."&msg=nep");}
}

// Výčet povolených tagů
$allowtags = "";
$game = strip_tags($_POST['main_game'],$allowtags);
$shortname = strip_tags($_POST['main_shortname'],$allowtags);
if ($_GET['action'] == "clan_game_main_add"){
	mysql_query("INSERT INTO $db_clan_games_main VALUES('','".mysql_real_escape_string($game)."','".mysql_real_escape_string($shortname)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "add_ok";
}
if ($_GET['action'] == "clan_game_main_edit"){
	mysql_query("UPDATE $db_clan_games_main SET  clan_games_main_game='".mysql_real_escape_string($game)."', clan_games_main_shortname='".mysql_real_escape_string($shortname)."' WHERE clan_games_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "clan_game_main_del"){
	mysql_query("DELETE FROM $db_clan_games_main WHERE clan_games_main_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "del_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_games_main.php?action=clan_game_main_add&project=".$_SESSION['project']."&msg=".$msg);
exit;