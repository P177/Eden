<?php
/* Provereni opravneni */
if ($_GET['action'] == "ban_add"){
	if (CheckPriv("groups_ban_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_ban.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "ban_edit"){
	if (CheckPriv("groups_ban_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_ban.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "ban_del"){
	if (CheckPriv("groups_ban_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_ban.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$allowtags = "";
// Z obsahu promenné body vyjmout nepovolené tagy
$ban_comment = strip_tags($_POST['ban_comment'],$allowtags);
$ban_ip = strip_tags($_POST['ban_ip'],$allowtags);
$ban_user = strip_tags($_POST['ban_user'],$allowtags);
$ban_date = PrepareDateForSpiffyCalendar($_POST['ban_date'],"date_only");
if ($_GET['action'] == "ban_add"){
	$res = mysql_query("INSERT INTO $db_ban VALUES ('0','".$ban_date."',INET_ATON('".mysql_real_escape_string($ban_ip)."'),'".mysql_real_escape_string($ban_user)."','".mysql_real_escape_string($ban_comment)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "bana_ok";
}
if ($_GET['action'] == "ban_edit"){
	$res = mysql_query("UPDATE $db_ban SET ban_date='".$ban_date."', ban_ip=INET_ATON('".mysql_real_escape_string($ban_ip)."'), ban_user='".mysql_real_escape_string($ban_user)."' WHERE ban_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "bane_ok";
}
if ($_GET['action'] == "ban_del"){
	$res = mysql_query("DELETE FROM $db_ban WHERE ban_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "band_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_ban.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg);
exit;