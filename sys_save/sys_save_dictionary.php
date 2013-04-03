<?php
/* Provereni opravneni */
if ($_GET['action'] == "add_word"){
	if (CheckPriv("groups_dictionary_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_word"){
	if (CheckPriv("groups_dictionary_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_word"){
	if (CheckPriv("groups_dictionary_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=nep");}
}
if ($_GET['letter'] == ""){
	$_GET['letter'] = ltrim($_POST['dictionary_word']);
	$_GET['letter'] = substr($_GET['letter'], 0, 1);
	$_GET['letter'] = strtoupper($_GET['letter']);
}
if ($_GET['action'] == "add_word"){
	/* Pokud ma nekdo prava jen pro pridavani, musi to nekdo s pravy pro editaci povolit */
	if (CheckPriv("groups_dictionary_edit") <> 1){$dictionary_allow = 0;}else{$dictionary_allow = (float)$_POST['dictionary_allow'];}
	mysql_query("INSERT INTO $db_dictionary VALUES('','','".(float)$_SESSION['loginid']."','".mysql_real_escape_string($_POST['dictionary_word'])."','".mysql_real_escape_string($_POST['dictionary_word_description'])."',NOW(),'".$dictionary_allow."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "add_ok";
}
if ($_GET['action'] == "edit_word"){
	mysql_query("UPDATE $db_dictionary SET dictionary_word='".mysql_real_escape_string($_POST['dictionary_word'])."', dictionary_word_description='".mysql_real_escape_string($_POST['dictionary_word_description'])."', dictionary_date=NOW(), dictionary_allow=".(integer)$_POST['dictionary_allow']." WHERE dictionary_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "del_word"){
	mysql_query("DELETE FROM $db_dictionary WHERE dictionary_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=".$msg."&letter=".$_GET['letter']);
exit;