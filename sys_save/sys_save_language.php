<?php
/* Provereni opravneni */
if ($_GET['action'] == "lang_add"){
	if (CheckPriv("groups_reserved_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "lang_edit"){
	if (CheckPriv("groups_reserved_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_reserved.php?action=lang_add&project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "lang_del"){
	if (CheckPriv("groups_reserved_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_reserved.php?action=lang_add&project=".$_SESSION['project']."&msg=nep");}
}

// Výčet povolených tagů
$allowtags = "";
$lang_name = strip_tags($_POST['lang_name'],$allowtags);
$lang_code = strip_tags($_POST['lang_code'],$allowtags);

/* Ulozeni oceneni po pridani */
if ($_GET['action'] == "lang_add"){
	
	$res = mysql_query("SELECT * FROM $db_language WHERE language_code='".mysql_real_escape_string($lang_code)."' OR language_name='".mysql_real_escape_string($lang_name)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($lang_code == $ar['language_code']){
		$msg = "lang_replycode";
	}elseif ($lang_name == $ar['language_name']){
		$msg = "lang_replyname";
	} else {
		mysql_query("INSERT INTO $db_language VALUES('','".mysql_real_escape_string($lang_name)."','".mysql_real_escape_string($lang_code)."','".mysql_real_escape_string($_POST['lang_image'])."',".(integer)$_POST['lang_active'].")") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("INSERT INTO $db_setup_lang VALUES('".mysql_real_escape_string($lang_code)."','','','','','','','','','','','','','','','','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("INSERT INTO $db_shop_setup VALUES('".mysql_real_escape_string($lang_code)."','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$_GET['id'] = mysql_insert_id();
	if ($res){
		$msg = "add_ok";
	} else {
		$msg = "add_no";
	}
}

/* Ulozeni oceneni po editaci */
if ($_GET['action'] == "lang_edit"){
	$res = mysql_query("UPDATE $db_language SET language_name='".mysql_real_escape_string($lang_name)."', language_code='".mysql_real_escape_string($lang_code)."', language_image='".mysql_real_escape_string($_POST['lang_image'])."', language_active=".(integer)$_POST['lang_active']." WHERE language_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "edit_ok";
	} else {
		$msg = "edit_no";
	}
}

/* Odstraneni oceneni */
if ($_GET['action'] == "lang_del"){
	$res = mysql_query("SELECT * FROM $db_language WHERE language_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar = mysql_fetch_array($res)){
		@mysql_query("DELETE FROM $db_setup_lang WHERE setup_lang='".$ar['language_code']."'");
		@mysql_query("DELETE FROM $db_shop_setup WHERE setup_lang='".$ar['language_code']."'");
	}
	$res2 = mysql_query("DELETE FROM $db_language WHERE language_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res2){
		$msg = "del_ok";
	} else {
		$msg = "del_no";
	}
}

header ("Location: ".$eden_cfg['url_cms']."sys_language.php?action=lang_add&&project=".$_SESSION['project']."&msg=".$msg);
exit;