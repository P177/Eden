<?php
/* Provereni opravneni */
if ($_GET['action'] == "filter_add"){
	if (CheckPriv("groups_filter_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "filter_edit"){
	if (CheckPriv("groups_filter_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "filter_del"){
	if (CheckPriv("groups_filter_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$allowtags = "";
$filter_shortname = PrepareForDB($_POST['filter_shortname']);
$filter_name = PrepareForDB($_POST['filter_name']);
$filter_description = PrepareForDB($_POST['filter_desc']);

$filter_shortname = str_replace( ",", "",$filter_shortname);

if ($_GET['action'] == "filter_add"){
	mysql_query("INSERT INTO $db_filters VALUES('','".$filter_shortname."','".$filter_name."','".$filter_description."','".(integer)$_POST['filter_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "af_ok";
}
if ($_GET['action'] == "filter_edit"){
	mysql_query("UPDATE $db_filters SET filter_shortname='".$filter_shortname."', filter_name='".$filter_name."', filter_description='".$filter_description."', filter_active=".(integer)$_POST['filter_active']." WHERE filter_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "ef_ok";
}
if ($_GET['action'] == "filter_del"){
	mysql_query("DELETE FROM $db_filters WHERE filter_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "df_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=filter_add&project=".$_SESSION['project']."&msg=".$msg);
exit;