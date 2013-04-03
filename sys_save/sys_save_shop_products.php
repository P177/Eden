<?php
$prod_name = PrepareForDB($_POST['prod_name']);
$prod_model = PrepareForDB($_POST['prod_model']);
$prod_short = PrepareForDB($_POST['prod_short'],0,"<table>, <tr>, <td>, <embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <font>, <strong>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>, <strike>, <img>, <blockquote>, <h1>, <h2>, <h3>, <div>, <span>");
$prod_long = PrepareForDB($_POST['prod_long'],0,"<table>, <tr>, <td>, <embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <font>, <strong>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>, <strike>, <img>, <blockquote>, <h1>, <h2>, <h3>, <div>, <span>");
$prod_code = PrepareForDB($_POST['prod_code']);
$prod_code_man = PrepareForDB($_POST['prod_code_man']);
$prod_availability = $_POST['prod_date_year'].'-'.$_POST['prod_date_month'].'-'.$_POST['prod_date_day'].' '."00:00:00";
// Zamezime aby podkategorie mely stejne cislo jako hlavni kategorie
if ($_POST['prod_sub_category1'] == $_POST['prod_master_category']){$prod_sub_category1 = 0;} else {$prod_sub_category1 = $_POST['prod_sub_category1'];}
if ($_POST['prod_sub_category2'] == $_POST['prod_master_category']){$prod_sub_category2 = 0;} else {$prod_sub_category2 = $_POST['prod_sub_category2'];}
if ($_POST['prod_sub_category3'] == $_POST['prod_master_category']){$prod_sub_category3 = 0;} else {$prod_sub_category3 = $_POST['prod_sub_category3'];}
if ($_POST['prod_sub_category4'] == $_POST['prod_master_category']){$prod_sub_category4 = 0;} else {$prod_sub_category4 = $_POST['prod_sub_category4'];}
if ($_POST['prod_sub_category5'] == $_POST['prod_master_category']){$prod_sub_category5 = 0;} else {$prod_sub_category5 = $_POST['prod_sub_category5'];}
if ($_GET['action'] == "add_prod"){
	if (is_numeric($_POST['prod_selling_price']))	{$prod_selling_price 	= str_replace("-", "", $_POST['prod_selling_price']);	if ($prod_selling_price == 0){ $shop_error[] = "prod_selling_price_0";}}			else{$prod_selling_price = 0; $shop_error[] = "prod_selling_price_0";}
	if (is_numeric($_POST['prod_purchasing_price'])){$prod_purchasing_price = str_replace("-", "", $_POST['prod_purchasing_price']);if ($prod_purchasing_price == 0){ $shop_error[] = "prod_purchasing_price_0";}}	else{$prod_purchasing_price = 0; $shop_error[] = "prod_purchasing_price_0";}
	if (is_numeric($_POST['prod_weight']))			{$prod_weight 			= str_replace("-", "", $_POST['prod_weight']);			if ($prod_weight == 0){ $shop_error[] = "prod_weight_0";}}						else{$prod_weight = 0; $shop_error[] = "prod_weight_0";}
	if (is_numeric($_POST['prod_warranty']))		{$prod_warranty 		= str_replace("-", "", $_POST['prod_warranty']);		if ($prod_warranty == 0){ $shop_error[] = "prod_warranty_0";}}					else{$prod_warranty = 0; $shop_error[] = "prod_warranty_0";}
	if (is_numeric($_POST['prod_quantity']))		{$prod_quantity 		= str_replace("-", "", $_POST['prod_quantity']);		$prod_quantity 		= str_replace(".", "", $prod_quantity); 	$prod_quantity 		= str_replace(",", "", $prod_quantity);	if ($prod_quantity == 0){ $shop_error[] = "prod_quantity_0";}} else {$prod_quantity 	= 0; $shop_error[] = "prod_quantity_0";}
	if (is_numeric($_POST['prod_order_min']))		{$prod_order_min 		= str_replace("-", "", $_POST['prod_order_min']); 		$prod_order_min 	= str_replace(".", "", $prod_order_min); 	$prod_order_min 	= str_replace(",", "", $prod_order_min);	} else {$prod_order_min 	= 1;}
	if (is_numeric($_POST['prod_order_units']))		{$prod_order_units 		= str_replace("-", "", $_POST['prod_order_units']); 	$prod_order_units 	= str_replace(".", "", $prod_order_units); 	$prod_order_units 	= str_replace(",", "", $prod_order_units);	} else {$prod_order_units = 1;}
	if (is_numeric($_POST['prod_discount']))		{$prod_discount 		= str_replace("-", "", $_POST['prod_discount']); 		$prod_discount 		= str_replace(".", "", $prod_discount); 	$prod_discount 		= str_replace(",", "", $prod_discount); 	$prod_discount 	= ($prod_discount / 100);	} else {$prod_discount 	= 0.10;}
	if (is_numeric($_POST['prod_margin']))			{$prod_margin 			= str_replace("-", "", $_POST['prod_margin']); 			$prod_margin 		= str_replace(".", "", $prod_margin); 		$prod_margin 		= str_replace(",", "", $prod_margin); 		$prod_margin 	= ($prod_margin / 100);		} else {$prod_margin 		= 0;}

	$prod_avg_purchasing_price = $prod_purchasing_price;
	$prod_avg_selling_price = $prod_selling_price;
	mysql_query("INSERT INTO $db_shop_product
	VALUES('',
	'".mysql_real_escape_string($prod_name)."',
	'".mysql_real_escape_string($prod_model)."',
	'".(float)$prod_purchasing_price."',
	'".(float)$prod_avg_purchasing_price."',
	'".(float)$prod_selling_price."',
	'".(float)$prod_avg_selling_price."',
	'".(float)$_POST['prod_price_choose']."',
	'".(float)$_POST['prod_margin_choose']."',
	'".(float)$prod_margin."',
	'".(integer)$_POST['prod_discount_cat_seller']."',
	'".(integer)$_POST['prod_discount_cat_cust']."',
	'".mysql_real_escape_string($prod_short)."',
	'".mysql_real_escape_string($prod_long)."',
	'".(float)$_POST['prod_man_id']."',
	'".(float)$prod_weight."',
	'".(float)$_POST['prod_status']."',
	'".(float)$_POST['prod_master_category']."',
	'".(float)$prod_sub_category1."',
	'".(float)$prod_sub_category2."',
	'".(float)$prod_sub_category3."',
	'".(float)$prod_sub_category4."',
	'".(float)$prod_sub_category5."',
	NOW(),
	NOW(),
	'".(float)$prod_availability."',
	'".(float)$prod_order_min."',
	'".(float)$prod_order_units."',
	'',
	'',
	'',
	'".(float)$prod_quantity."',
	'',
	'',
	'".(float)$_POST['prod_vat_class_id']."',
	'".(float)$prod_discount."',
	'',
	'',
	'".mysql_real_escape_string($prod_code)."',
	'".mysql_real_escape_string($prod_code_man)."',
	'".(float)$prod_warranty."',
	'".(float)$_POST['prod_is_free']."',
	'".(float)$_POST['prod_is_virtual']."',
	'0',
	'".(float)$_POST['prod_qty_box_status']."',
	'".(float)$_POST['shop_product_allow_order_if_stock_is_0']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$product_id_add = mysql_insert_id();
}
if ($_GET['action'] == "edit_prod"){
	$res = mysql_query("SELECT * FROM $db_shop_product WHERE shop_product_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($_POST['del_product_picture_a'] == 1){$product_picture_a = "shop_product_picture_a='',";}
	if ($_POST['del_product_picture_b'] == 1){$product_picture_b = "shop_product_picture_b='',";}
	if (is_numeric($_POST['prod_selling_price']))	{$prod_selling_price 	= str_replace("-", "", $_POST['prod_selling_price']);		if ($prod_selling_price == 0){ $shop_error[] = "prod_selling_price_0";}}	else{$prod_selling_price = $ar['shop_product_selling_price']; 		if ($prod_selling_price == 0){ $shop_error[] = "prod_selling_price_0";}}
	if (is_numeric($_POST['prod_purchasing_price'])){$prod_purchasing_price = str_replace("-", "", $_POST['prod_purchasing_price']);	if ($prod_purchasing_price== 0){ $shop_error[] = "prod_purchasing_price_0";}}	else{$prod_purchasing_price = $ar['shop_product_purchasing_price']; if ($prod_purchasing_price == 0){ $shop_error[] = "prod_purchasing_price_0";}}
	if (is_numeric($_POST['prod_weight']))			{$prod_weight 			= str_replace("-", "", $_POST['prod_weight']);				if ($prod_weight == 0){ $shop_error[] = "prod_weight_0";}}	else{$prod_weight = $ar['shop_product_weight']; 					if ($prod_weight == 0){ $shop_error[] = "prod_weight_0";}}
	if (is_numeric($_POST['prod_warranty']))		{$prod_warranty 		= str_replace("-", "", $_POST['prod_warranty']);			if ($prod_warranty == 0){ $shop_error[] = "prod_warranty_0";}}	else{$prod_warranty = $ar['shop_product_warranty']; 				if ($prod_warranty == 0){ $shop_error[] = "prod_warranty_0";}}
	if (is_numeric($_POST['prod_quantity']))		{$prod_quantity 		= str_replace("-", "", $_POST['prod_quantity']); 		$prod_quantity 		= str_replace(".", "", $prod_quantity); 	$prod_quantity 		= str_replace(",", "", $prod_quantity);				if ($prod_quantity == 0){ $shop_error[] = "prod_quantity_0";}} else {$prod_quantity 	= $ar['shop_product_quantity'];if ($prod_quantity == 0){ $shop_error[] = "prod_quantity_0";}}
	if (is_numeric($_POST['prod_order_min']))		{$prod_order_min 		= str_replace("-", "", $_POST['prod_order_min']); 		$prod_order_min 	= str_replace(".", "", $prod_order_min); 	$prod_order_min 	= str_replace(",", "", $prod_order_min);	} else {$prod_order_min 	= $ar['shop_product_quantity_order_min'];}
	if (is_numeric($_POST['prod_order_units']))		{$prod_order_units 		= str_replace("-", "", $_POST['prod_order_units']); 	$prod_order_units 	= str_replace(".", "", $prod_order_units); 	$prod_order_units 	= str_replace(",", "", $prod_order_units);	} else {$prod_order_units = $ar['shop_product_quantity_order_units'];}
	if (is_numeric($_POST['prod_discount']))		{$prod_discount 		= str_replace("-", "", $_POST['prod_discount']); 		$prod_discount 		= str_replace(".", "", $prod_discount); 	$prod_discount 		= str_replace(",", "", $prod_discount); 	$prod_discount 	= ($prod_discount / 100);	} else {$prod_discount 	= $ar['shop_product_discount'];}
	if (is_numeric($_POST['prod_margin']))			{$prod_margin 			= str_replace("-", "", $_POST['prod_margin']); 			$prod_margin 		= str_replace(".", "", $prod_margin); 		$prod_margin 		= str_replace(",", "", $prod_margin); 		$prod_margin 	= ($prod_margin / 100);		} else {$prod_margin 		= $ar['shop_product_margin'];}

	mysql_query("UPDATE $db_shop_product SET shop_product_name='".mysql_real_escape_string($prod_name)."',
	shop_product_model='".mysql_real_escape_string($prod_model)."',
	shop_product_purchasing_price=".(float)$prod_purchasing_price.",
	shop_product_selling_price=".(float)$prod_selling_price.",
	shop_product_price_choose=".(float)$_POST['prod_price_choose'].",
	shop_product_margin_choose=".(float)$_POST['prod_margin_choose'].",
	shop_product_margin=".(float)$prod_margin.",
	shop_product_discount_cat_seller_id=".(integer)$_POST['prod_discount_cat_seller'].",
	shop_product_discount_cat_cust_id=".(integer)$_POST['prod_discount_cat_cust'].",
	shop_product_description_short='".mysql_real_escape_string($prod_short)."',
	shop_product_description='".mysql_real_escape_string($prod_long)."',
	shop_product_manufacturers_id=".(float)$_POST['prod_man_id'].",
	shop_product_weight=".(float)$prod_weight.",
	shop_product_status=".(float)$_POST['prod_status'].",
	shop_product_master_category=".(float)$_POST['prod_master_category'].",
	shop_product_subcategory1=".(float)$prod_sub_category1.",
	shop_product_subcategory2=".(float)$prod_sub_category2.",
	shop_product_subcategory3=".(float)$prod_sub_category3.",
	shop_product_subcategory4=".(float)$prod_sub_category4.",
	shop_product_subcategory5=".(float)$prod_sub_category5.",
	shop_product_last_modified=NOW(),
	$product_picture_a
	$product_picture_b
	shop_product_date_available='$prod_availability',
	shop_product_quantity_order_min=".(float)$prod_order_min.",
	shop_product_quantity_order_units=".(float)$prod_order_units.",
	shop_product_quantity=".(float)$prod_quantity.",
	shop_product_vat_class_id=".(float)$_POST['prod_vat_class_id'].",
	shop_product_discount=".(float)$prod_discount.",
	shop_product_product_code='".mysql_real_escape_string($prod_code)."',
	shop_product_product_code_man='".mysql_real_escape_string($prod_code_man)."',
	shop_product_warranty=".(float)$prod_warranty.",
	shop_product_is_free=".(float)$_POST['prod_is_free'].",
	shop_product_is_virtual=".(float)$_POST['prod_is_virtual'].",
	shop_product_qty_box_status=".(float)$_POST['prod_qty_box_status'].",
	shop_product_allow_order_if_stock_is_0=".(float)$_POST['shop_product_allow_order_if_stock_is_0']." WHERE shop_product_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "del_prod"){
	mysql_query("DELETE FROM $db_shop_product WHERE shop_product_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action'] = "add_prod";
}
if ($_FILES['shop_prod_file_a']['name'] != "" || $_FILES['shop_prod_file_b']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// ziskam extenzi souboru
   	$extenze_a = strtok($_FILES['shop_prod_file_a']['name'] ,".");
   	$extenze_a = strtok(".");
	$extenze_b = strtok($_FILES['shop_prod_file_b']['name'] ,".");
   	$extenze_b = strtok(".");
	// Ulozi jmeno obrazku jako
	$picture_a = Cislo().".".$extenze_a;
	$picture_b = Cislo().".".$extenze_b;
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file_a = $_FILES['shop_prod_file_a']['tmp_name'];
	$source_file_b = $_FILES['shop_prod_file_b']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file_a = $ftp_path_shop_prod.$picture_a;
	$destination_file_b = $ftp_path_shop_prod.$picture_b;

	if ($_GET['action'] == "add_prod"){$prod_id = $product_id_add;} else {$prod_id = $_GET['id'];}

	if ($_FILES['shop_prod_file_a']['name'] != ""){
		$upload_a = ftp_put($conn_id, $destination_file_a, $source_file_a, FTP_BINARY);
		if (!$upload_a) {
			$sys_save_message = _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message = _OK_FTP_FILE_1.' '.$destination_file_a.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_product SET shop_product_picture_a='".mysql_real_escape_string($picture_a)."' WHERE shop_product_id=".(float)$prod_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['shop_prod_file_b']['name'] != ""){
		$upload_b = ftp_put($conn_id, $destination_file_b, $source_file_b, FTP_BINARY);
		if (!$upload_b) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_b.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_product SET shop_product_picture_b='".mysql_real_escape_string($picture_b)."' WHERE shop_product_id=".(float)$prod_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}

	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
}
if ($_POST['save'] == 1){
	if ($_GET['action'] == "add_prod"){$_GET['id'] = $product_id_add;}
	$_POST['confirm'] = "false";
	$_GET['action'] = "edit_prod";
}
if ($_POST['save'] == 2){
	$_GET['action'] = "prod";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&confirm=".$_POST['confirm']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
exit;