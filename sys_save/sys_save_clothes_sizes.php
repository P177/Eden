<?php
// Výčet povolených tagů
$allowtags = "";
$clothes_size_size = strip_tags($_POST['clothes_size_size'],$allowtags);
$clothes_size_description = strip_tags($_POST['clothes_size_description'],$allowtags);

if ($_GET['action'] == "clothes_add_size"){
	mysql_query("INSERT INTO $db_shop_clothes_size VALUES('','".mysql_real_escape_string($clothes_size_size)."','".mysql_real_escape_string($clothes_size_description)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "clothes_edit_size"){
	mysql_query("UPDATE $db_shop_clothes_size SET  shop_clothes_size_size='".mysql_real_escape_string($clothes_size_size)."', shop_clothes_size_description='".mysql_real_escape_string($clothes_size_description)."' WHERE shop_clothes_size_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action']= "clothes_add_size";
}
if ($_GET['action'] == "clothes_del_size"){
	mysql_query("DELETE FROM $db_shop_clothes_size WHERE shop_clothes_size_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action']= "clothes_add_size";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
exit;
?>