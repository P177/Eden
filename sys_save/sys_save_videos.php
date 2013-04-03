<?php
/* Provereni opravneni */
if ($_GET['action'] == "video_add"){
	if (CheckPriv("groups_video_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");exit;}
} elseif ($_GET['action'] == "video_edit"){
	if (CheckPriv("groups_video_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_videos.php?action=clan_awards_add&y=".$_GET['y']."&project=".$_SESSION['project']."&msg=nep");exit;}
} elseif ($_GET['action'] == "video_del"){
	if (CheckPriv("groups_video_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_videos.php?action=clan_awards_add&y=".$_GET['y']."&project=".$_SESSION['project']."&msg=nep");exit;}
}

// Výčet povolených tagů
$allowtags = "";
$video_name = strip_tags($_POST['video_name'],"");
$video_desc = strip_tags($_POST['video_desc'],"");

$video_date_from = PrepareDateForSpiffyCalendar($_POST['video_date_from'],Zerofill($_POST['video_date_from_h'][0].$_POST['video_date_from_h'][1],10).":".Zerofill($_POST['video_date_from_m'][0].$_POST['video_date_from_m'][1],10).":00");
$video_date_to = PrepareDateForSpiffyCalendar($_POST['video_date_to'],Zerofill($_POST['video_date_to_h'][0].$_POST['video_date_to_h'][1],10).":".Zerofill($_POST['video_date_to_m'][0].$_POST['video_date_to_m'][1],10).":00");

/* Ulozeni videa */
if ($_GET['action'] == "video_add"){
	
	$res = mysql_query("INSERT INTO $db_videos VALUES(
	'',
	'".(integer)$_SESSION['loginid']."',
	'".(integer)$_POST['video_game_id']."',
	'".mysql_real_escape_string($video_name)."',
	'".mysql_real_escape_string($video_desc)."',
	'".mysql_real_escape_string($_POST['video_code'])."',
	'".(integer)$_POST['video_width']."',
	'".(integer)$_POST['video_height']."',
	NOW(),
	'".mysql_real_escape_string($video_date_from)."',
	'".mysql_real_escape_string($video_date_to)."',
	'".(integer)$_POST['video_show']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
	if ($res){
		$msg = "add_ok";
	} else {
		$msg = "add_no";
	}
}

/* Ulozeni videa po editaci */
if ($_GET['action'] == "video_edit"){
	$res = mysql_query("UPDATE $db_videos SET
	video_game_id=".(integer)$_POST['video_game_id'].",
	video_name='".mysql_real_escape_string($video_name)."',
	video_description='".mysql_real_escape_string($video_desc)."',
	video_code='".mysql_real_escape_string($_POST['video_code'])."',
	video_width=".(integer)$_POST['video_width'].",
	video_height=".(integer)$_POST['video_height'].",
	video_date_from='".mysql_real_escape_string($video_date_from)."',
	video_date_to='".mysql_real_escape_string($video_date_to)."',
	video_show=".(integer)$_POST['video_show']." WHERE video_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "edit_ok";
	} else {
		$msg = "edit_no";
	}
}

/* Odstraneni videa */
if ($_GET['action'] == "video_del"){
	$res = mysql_query("DELETE FROM $db_videos WHERE video_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "del_ok";
	} else {
		$msg = "del_no";
	}
}

header ("Location: ".$eden_cfg['url_cms']."modul_videos.php?action=video_add&project=".$_SESSION['project']."&msg=".$msg);
exit;