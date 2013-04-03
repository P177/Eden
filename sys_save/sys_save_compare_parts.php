<?php
/* Provereni opravneni */
if ($_GET['action'] == "compare_part_add"){
	if (CheckPriv("groups_compare_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_part_edit"){
	if (CheckPriv("groups_compare_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "compare_par_tdel"){
	if (CheckPriv("groups_compare_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Z obsahu promenné body vyjmout nepovolené tagy */
$part_name = PrepareForDB($_POST['compare_part_name'],1,"",1);

/* Nacteni povolenych jazyku */
$res_lang = mysql_query("SELECT language_id FROM $db_language WHERE language_active=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

/* Pridani komponenty */
if ($_GET['action'] == "compare_part_add"){
	
	/* Overime zda komponenta neni uz v databazi */
	$res_part = mysql_query("SELECT COUNT(*) AS num FROM $db_compare_parts WHERE compare_part_category_id=".(integer)$_POST['compare_part_cat_id']." AND compare_part_name='".$part_name."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_part = mysql_fetch_array($res_part);
	
	if ($ar_part['num'] > 0) {
		header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_part_add&project=".$_SESSION['project']."&msg=compare_part_in_db&page=".$_GET['page']."&hits=".$_GET['hits']);
		exit;
	}
	
	$res = mysql_query("INSERT INTO $db_compare_parts VALUES('','".(integer)$_POST['compare_part_cat_id']."','".(integer)$_POST['compare_part_maker_id']."','".$part_name."','".(integer)$_POST['compare_part_rank']."','".(integer)$_POST['compare_part_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* Ziskame last insert ID */
	$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_id = mysql_fetch_array($res_id);
	while ($ar_lang = mysql_fetch_array($res_lang)){
		$part_desc = PrepareForDB($_POST['compare_part_desc'][$ar_lang['language_id']],1,"",1);
		$res_desc = mysql_query("INSERT INTO $db_compare_txt VALUES('','".(integer)$ar_lang['language_id']."','part','".(integer)$ar_id[0]."','".$part_desc."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$msg = "compare_part_add_ok";
}

/* Editace komponenty */
if ($_GET['action'] == "compare_part_edit"){
	
	/* Overime zda komponenta neni uz v databazi */
	$res_part = mysql_query("SELECT COUNT(*) AS num FROM $db_compare_parts WHERE compare_part_category_id=".(integer)$_POST['compare_part_cat_id']." AND compare_part_name='".$part_name."' AND compare_part_id<>".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_part = mysql_fetch_array($res_part);
	
	if ($ar_part['num'] > 0) {
		header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_part_add&project=".$_SESSION['project']."&msg=compare_part_in_db&page=".$_GET['page']."&hits=".$_GET['hits']);
		exit;
	}
	
	$res = mysql_query("UPDATE $db_compare_parts SET compare_part_category_id=".(integer)$_POST['compare_part_cat_id'].", compare_part_maker_id=".(integer)$_POST['compare_part_maker_id'].", compare_part_name='".$part_name."', compare_part_rank=".(integer)$_POST['compare_part_rank'].", compare_part_active=".(integer)$_POST['compare_part_active']." WHERE compare_part_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar_lang = mysql_fetch_array($res_lang)){
		$part_desc = PrepareForDB($_POST['compare_part_desc'][$ar_lang['language_id']],1,"",1);
		$res_desc = mysql_query("UPDATE $db_compare_txt SET compare_txt_description='".$part_desc."' WHERE compare_txt_lang_id=".$ar_lang['language_id']." AND compare_txt_mode='part' AND compare_txt_mode_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$msg = "compare_part_edit_ok";
}

/* Odstraneni komponenty */
if ($_GET['action'] == "compare_part_del"){
	$res = mysql_query("DELETE FROM $db_compare_parts WHERE compare_part_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "compare_part_del_ok";
}

header ("Location: ".$eden_cfg['url_cms']."modul_compare.php?action=compare_part_add&cid=".$_POST['cid']."&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;