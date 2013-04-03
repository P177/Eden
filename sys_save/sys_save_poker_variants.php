<?php
/* Provereni opravneni */
if ($_GET['action'] == "add_variant"){
	if (CheckPriv("groups_poker_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_variant"){
	if (CheckPriv("groups_poker_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_variant"){
	if (CheckPriv("groups_poker_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$allowtags = "";
$variant_name = PrepareForDB($_POST['poker_variant_name']);
$variant_description = PrepareForDB($_POST['poker_variant_description']);

if ($_GET['action'] == "add_variant"){
	mysql_query("INSERT INTO $db_poker_variants VALUES('','".$variant_name."','".$variant_description."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "af_ok";
}
if ($_GET['action'] == "edit_variant"){
	mysql_query("UPDATE $db_poker_variants SET poker_variant_name='".$variant_name."', poker_variant_description='".$variant_description."' WHERE poker_variant_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "ef_ok";
}
if ($_GET['action'] == "del_variant"){
	mysql_query("DELETE FROM $db_poker_variants WHERE poker_variant_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "df_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=".$msg);
exit;
?>