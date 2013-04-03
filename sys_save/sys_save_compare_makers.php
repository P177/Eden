<?php
/* Provereni opravneni */
if ($_GET['action'] == "compare_maker_add"){
	if (CheckPriv("groups_compare_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_maker_edit"){
	if (CheckPriv("groups_compare_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_maker_del"){
	if (CheckPriv("groups_compare_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
// Z obsahu promenné body vyjmout nepovolené tagy
$maker_name = PrepareForDB($_POST['compare_maker_name'],1,"",1);
$maker_url = PrepareForDB($_POST['compare_maker_url'],1,"",1);

if ($_GET['action'] == "compare_maker_add"){
	$res = mysql_query("INSERT INTO $db_compare_makers VALUES('','".(integer)$_POST['compare_maker_cat_id']."','".$maker_name."','".$maker_url."','".(integer)$_POST['compare_maker_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_maker_add_ok";
}
if ($_GET['action'] == "compare_maker_edit"){
	$res = mysql_query("UPDATE $db_compare_makers SET compare_maker_category_id=".(integer)$_POST['compare_maker_cat_id'].", compare_maker_name='".$maker_name."', compare_maker_url='".$maker_url."', compare_maker_active=".(integer)$_POST['compare_maker_active']." WHERE compare_maker_id=".(integer)$_POST['mid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_maker_edit_ok";
}
if ($_GET['action'] == "compare_maker_del"){
	$res = mysql_query("DELETE FROM $db_compare_makers WHERE compare_maker_id=".(integer)$_POST['mid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_maker_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_maker_add&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;