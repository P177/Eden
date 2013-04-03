<?php

/* Pokud se vybere nektera mena jako hlavni nastavi se konverze na 1, main na 1 a u ostatnich se nastavi main na 0 */
if ($_POST['currency_main'] == 1){
	$res = mysql_query("SELECT shop_currency_id FROM $db_shop_currency") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		mysql_query("UPDATE $db_shop_currency SET shop_currency_main=0 WHERE shop_currency_id=".(float)$ar['shop_currency_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$currency_conversion = 1;
} else {
	$currency_conversion = $_POST['currency_conversion'];
}

$_POST['currency_code'] = strtoupper($_POST['currency_code']);

if ($_GET['action'] == "shop_currency_add"){
	mysql_query("INSERT INTO $db_shop_currency VALUES('','".mysql_real_escape_string($_POST['currency_name'])."','".mysql_real_escape_string($_POST['currency_code'])."','".mysql_real_escape_string($_POST['currency_code_local'])."','".(float)$_POST['currency_conversion']."','".(int)$_POST['currency_main']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "shop_currency_edit"){
	mysql_query("UPDATE $db_shop_currency SET shop_currency_name='".mysql_real_escape_string($_POST['currency_name'])."', shop_currency_code='".mysql_real_escape_string(strtoupper($_POST['currency_code']))."', shop_currency_code_local='".mysql_real_escape_string($_POST['currency_code_local'])."', shop_currency_conversion=".(float)$_POST['currency_conversion'].", shop_currency_main=".(int)$_POST['currency_main']." WHERE shop_currency_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['action'] = "shop_currency_add";
}
if ($_GET['action'] == "shop_currency_del"){
	mysql_query("DELETE FROM $db_shop_currency WHERE shop_currency_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action'] = "shop_currency_add";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop_setup.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
exit;