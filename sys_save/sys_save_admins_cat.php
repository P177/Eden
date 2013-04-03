<?php
/* Provereni opravneni */
if ($_GET['action'] == "admins_cat_add"){
	if (CheckPriv("groups_cat_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=showcategory&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "admins_cat_edit"){
	if (CheckPriv("groups_cat_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=showcategory&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "admins_cat_del"){
	if (CheckPriv("groups_cat_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=showcategory&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
// Výčet povolených tagů
$allowtags = ""; 

// Z obsahu proměnné body vyjmout nepovolené tagy
$category_name = strip_tags($_POST['category_name'],$allowtags);
$comment = strip_tags($_POST['comment'],$allowtags);
$shortname = strip_tags($_POST['shortname'],$allowtags);
// Z obsahu proměnné body vyjmout nepovolené tagy
$category_name = str_replace( "'", "&acute;",$category_name);
$category_name = str_replace( "\"", "&quot;",$category_name);

if ($_GET['action'] == "admins_cat_add"){
	$category_name = str_ireplace( "\n", "<br>",$category_name);
	$comment = str_ireplace( "\n", "<br>",$comment);
	if (!isset($picture) || $picture == ""){$picture = "AllTopics.gif";}
	$res = mysql_query("INSERT INTO $db_admin_category VALUES('','".mysql_real_escape_string($_POST['picture'])."','".mysql_real_escape_string($shortname)."','".mysql_real_escape_string($category_name)."','".mysql_real_escape_string($comment)."','0','0','".(float)$_POST['shows']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "aca_ok";
}
if ($_GET['action'] == "admins_cat_edit"){
	$res = mysql_query("SELECT admin_category_topicimage FROM $db_admin_category WHERE admin_category_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($picture == ""){$picture = $ar['admin_category_topicimage'];}
	$res = mysql_query("UPDATE $db_admin_category SET admin_category_topicimage='".mysql_real_escape_string($_POST['picture'])."', admin_category_shortname='".mysql_real_escape_string($shortname)."', admin_category_topictext='".mysql_real_escape_string($category_name)."', admin_category_comment='".mysql_real_escape_string($comment)."', admin_category_parent=".(float)$parent.", admin_category_shows=".(float)$_POST['shows']."  WHERE admin_category_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "ace_ok";
}
if ($_GET['action'] == "admins_cat_del"){
	$res = mysql_query("DELETE FROM $db_admin_category WHERE admin_category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "acd_ok";
}
header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=showcategory&project=".$_SESSION['project']."&msg=".$msg);
exit;