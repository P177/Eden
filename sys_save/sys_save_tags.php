<?php
/* Provereni opravneni */
if ($_GET['action'] == "tag_add"){
	if (CheckPriv("groups_article_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "tag_edit"){
	if (CheckPriv("groups_article_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "tag_del"){
	if (CheckPriv("groups_article_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$tag_name = PrepareForDB($_POST['tag_name']);

$res_tags = mysql_query("SELECT COUNT(*) AS num FROM "._DB_TAGS." WHERE tag_name = '".mysql_real_escape_string($tag_name)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_tags = mysql_fetch_array($res_tags);
if ($ar_tags["num"] > 0){
	$msg = "tag_exist";
	$_GET['action'] = "";
}

if ($_GET['action'] == "tag_add"){
	mysql_query("INSERT INTO "._DB_TAGS." VALUES('','".$tag_name."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tag_add_ok";
}
if ($_GET['action'] == "tag_edit"){
	mysql_query("UPDATE "._DB_TAGS." SET tag_name='".$tag_name."' WHERE tag_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tag_edit_ok";
}
if ($_GET['action'] == "tag_del"){
	mysql_query("DELETE FROM "._DB_TAGS." WHERE tag_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "tag_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_tags.php?action=tag_add&project=".$_SESSION['project']."&msg=".$msg);
exit;