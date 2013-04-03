<?php
/* Provereni opravneni */
if ($_GET['action'] == "add_cardroom"){
	if (CheckPriv("groups_poker_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_cardroom"){
	if (CheckPriv("groups_poker_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_cardroom"){
	if (CheckPriv("groups_poker_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$allowtags = "";
$cardroom_name = PrepareForDB($_POST['poker_cardroom_name']);
$cardroom_url = PrepareForDB($_POST['poker_cardroom_url']);

if ($_GET['action'] == "add_cardroom"){
	mysql_query("INSERT INTO $db_poker_cardrooms VALUES('','".$cardroom_name."','".$cardroom_url."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "af_ok";
}
if ($_GET['action'] == "edit_cardroom"){
	mysql_query("UPDATE $db_poker_cardrooms SET poker_cardroom_name='".$cardroom_name."', poker_cardroom_url='".$cardroom_url."' WHERE poker_cardroom_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "ef_ok";
}
if ($_GET['action'] == "del_cardroom"){
	mysql_query("DELETE FROM $db_poker_cardrooms WHERE poker_cardroom_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "df_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=".$msg);
exit;
?>