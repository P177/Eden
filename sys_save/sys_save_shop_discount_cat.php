<?php
/********************************************
*	ADD
********************************************/
if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_add_sub"){
	if ($_GET['action'] == "discount_cat_add"){
		$discount_cat_parent = 0;
		$discount_type = (int)$_POST['discount_cat_type'];
	} else {
		$discount_cat_parent = (int)$_POST['discount_cat_parent'];
		$res_discount_parent = mysql_query("SELECT shop_discount_category_type FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_POST['discount_cat_parent']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_discount_parent = mysql_fetch_array($res_discount_parent);
		$discount_type = $ar_discount_parent['shop_discount_category_type'];
	}
	
	mysql_query("INSERT INTO $db_shop_discount_category  VALUES(
	'',
	'".(int)$discount_cat_parent."',
	'".mysql_real_escape_string($_POST['discount_cat_name'])."',
	'".(int)$_POST['discount_cat_status']."',
	'".$discount_type."',
	'".(float)$_POST['discount_cat_discount_price']."',
	'',
	'',
	'".(int)$_POST['discount_cat_disc_from_amount']."',
	'".(int)$_POST['discount_cat_disc_to_amount']."',
	'".(float)$_POST['discount_cat_disc_price_from']."',
	'".(float)$_POST['discount_cat_disc_price_to']."',
	NOW(),
	'".mysql_real_escape_string($_POST['discount_cat_disc_date_start'])."',
	'".mysql_real_escape_string($_POST['discount_cat_disc_date_start'])."',
	NOW(),
	NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
/********************************************
*	EDIT
********************************************/
if ($_GET['action'] == "discount_cat_edit" || $_GET['action'] == "discount_cat_edit_sub"){
	$res_discount = mysql_query("SELECT shop_discount_category_parent_id, shop_discount_category_status FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_discount = mysql_fetch_array($res_discount);
	if ($_GET['action'] == "discount_cat_edit"){
		$discount_type = (int)$_POST['discount_cat_type'];
	} else {
		$res_discount_parent = mysql_query("SELECT shop_discount_category_type FROM $db_shop_discount_category WHERE shop_discount_category_id=".$ar_discount['shop_discount_category_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_discount_parent = mysql_fetch_array($res_discount_parent);
		$discount_type = $ar_discount_parent['shop_discount_category_type'];
	}
	/* Pokud byl zmenen status ulozi se daum zmeny */
	if ($_POST['discount_cat_status'] <> $ar_discount['shop_discount_category_status']){ $discount_status_changed = ", NOW()"; }
	mysql_query("UPDATE $db_shop_discount_category  SET 
	shop_discount_category_parent_id = ".(int)$_POST['discount_cat_parent'].",
	shop_discount_category_name = '".mysql_real_escape_string($_POST['discount_cat_name'])."',
	shop_discount_category_status = ".(int)$_POST['discount_cat_status'].",
	shop_discount_category_type = ".(int)$discount_type.",
	shop_discount_category_discount_price = ".(float)$_POST['discount_cat_discount_price'].",
	shop_discount_category_categories_selected = '',
	shop_discount_category_categories_all = '',
	shop_discount_category_discounted_from_amount = ".(int)$_POST['discount_cat_disc_from_amount'].",
	shop_discount_category_discounted_to_amount = ".(int)$_POST['discount_cat_disc_to_amount'].",
	shop_discount_category_pricerange_from  = ".(float)$_POST['discount_cat_disc_price_from'].",
	shop_discount_category_pricerange_to = ".(float)$_POST['discount_cat_disc_price_to'].",
	shop_discount_category_discounted_date_start = '".mysql_real_escape_string($_POST['discount_cat_disc_date_start'])."',
	shop_discount_category_discounted_date_end = '".mysql_real_escape_string($_POST['discount_cat_disc_date_start'])."'
	$discount_status_changed 
	WHERE shop_discount_category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
/********************************************
*	DEL
********************************************/
if ($_GET['action'] == "discount_cat_del"){
	mysql_query("DELETE FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	mysql_query("DELETE FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "discount_cat_del_sub"){
	mysql_query("DELETE FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub" || $_GET['action'] == "discount_cat_del_sub"){$action = "discount_cat_open";} else {$action = "discount_cats";}
header ("Location: ".$eden_cfg['url_cms']."modul_shop_sellers.php?action=".$action."&id=".$_POST['discount_cat_parent']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
exit;