<?php
/* Provereni opravneni */
if ($_GET['action'] == "res_words_add"){
	if (CheckPriv("groups_reserved_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "res_words_edit"){
	if (CheckPriv("groups_reserved_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_reserved.php?action=res_words_add&project=".$_SESSION['project']."&msg=nep");}
} elseif ($_GET['action'] == "res_words_del"){
	if (CheckPriv("groups_reserved_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_reserved.php?action=res_words_add&project=".$_SESSION['project']."&msg=nep");}
}

// Výčet povolených tagů
$allowtags = "";
$res_word = strip_tags($_POST['res_word'],"");

/* Ulozeni oceneni po pridani */
if ($_GET['action'] == "res_words_add"){
	
	$res = mysql_query("INSERT INTO $db_reserved_words VALUES(
	'',
	'".mysql_real_escape_string($res_word)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
	if ($res){
		$msg = "add_ok";
	} else {
		$msg = "add_no";
	}
}

/* Ulozeni oceneni po editaci */
if ($_GET['action'] == "res_words_edit"){
	$res = mysql_query("UPDATE $db_reserved_words SET reserved_words_word='".mysql_real_escape_string($res_word)."' WHERE reserved_words_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "edit_ok";
	} else {
		$msg = "edit_no";
	}
}

/* Odstraneni oceneni */
if ($_GET['action'] == "res_words_del"){
	$res = mysql_query("DELETE FROM $db_reserved_words WHERE reserved_words_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "del_ok";
	} else {
		$msg = "del_no";
	}
}

header ("Location: ".$eden_cfg['url_cms']."sys_reserved.php?action=res_words_add&&project=".$_SESSION['project']."&msg=".$msg);
exit;