<?php
/* Provereni opravneni */
if ($_GET['action'] == "compare_cat_add"){
	if (CheckPriv("groups_compare_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_cat_edit"){
	if (CheckPriv("groups_compare_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_cat_del"){
	if (CheckPriv("groups_compare_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
// Z obsahu promenné body vyjmout nepovolené tagy
$cat_shortname = PrepareForDB($_POST['compare_cat_shortname'],1,"",1);
$cat_name = PrepareForDB($_POST['compare_cat_name'],1,"",1);

if ($_GET['action'] == "compare_cat_add"){
	/* Zkontrolujeme zda zkratka komponenty uz existuje v DB */
	$res_cat = mysql_query("SELECT COUNT(*) AS num, compare_cat_id FROM $db_compare_categories WHERE compare_cat_shortname='".$cat_shortname."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_cat = mysql_fetch_array($res_cat);
	if ($num_cat['num'] > 0) {
		header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_cat_add&project=".$_SESSION['project']."&msg=compare_cat_in_db&page=".$_GET['page']."&hits=".$_GET['hits']);
		exit;
	}
	$res = mysql_query("INSERT INTO $db_compare_categories VALUES('','".$cat_shortname."','".$cat_name."','".(integer)$_POST['compare_cat_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_cat_add_ok";
}
if ($_GET['action'] == "compare_cat_edit"){
	$res = mysql_query("UPDATE $db_compare_categories SET compare_cat_shortname='".$cat_shortname."', compare_cat_name='".$cat_name."', compare_cat_active=".(integer)$_POST['compare_cat_active']." WHERE compare_cat_id=".(integer)$_POST['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_cat_edit_ok";
}
if ($_GET['action'] == "compare_cat_del"){
	$res = mysql_query("DELETE FROM $db_compare_categories WHERE compare_cat_id=".(integer)$_POST['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_cat_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_cat_add&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;