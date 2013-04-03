<?php


	//error_reporting(E_ALL);
	if (isset($_POST['project'])){$project = $_POST['project'];} else {$project = $_GET['project'];}
	require_once("./db.".$project.".inc.php");
	
	if($_GET['action'] != "complete"){
		// Rekne ze sessions bylo volano z eden_save.php a pokud bude vyprseno, neodhlasi klienta
		$edensave = 1;
		require_once("./sessions.php");
	}
	require_once("./functions_frontend.php");
	require_once("./main_shop.php");
	include_once("../lang_en.php"); 
	require_once("./eden_lang_en.php");
	require_once("./class.mail.php");
	
/***********************************************************************************************************
*																											
*	Pridavani zbozi do kosiku																				
*	Add To Basket																							
*																											
***********************************************************************************************************/
	if ($_POST['action'] == "atb"){
		if ($_SESSION['u_status'] == "seller"){
			$product_data = $_POST['product_data'];
			$i=1;
			while ($i <= $_POST['product_num']){
				if ($product_data['quantity_'.$i] > 0){
					$ar_product = GetProduct($_POST['did'],$product_data['style_id_'.$i],$product_data['colour_'.$i],$product_data['size_'.$i]);
					$product_id = $ar_product['shop_product_id'];
					$product_quantity = $product_data['quantity_'.$i];
					$product_price = $ar_product['shop_product_selling_price']; /* Tady je treba dodat vypocet ceny pro prodejce !!!!! */
					AddToBasket($product_id,$product_quantity,$product_price);
				}
			$i++;
			}
		}
		if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "admin" || $_SESSION['u_status'] == "vizitor"){
			$product_id = (integer)$_POST['product_id'];
			$product_quantity = (integer)$_POST['product_quantity'];
			$product_price = (float)$_POST['product_price'];
			AddToBasket($product_id,$product_quantity,$product_price);
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=vb&lang=".$_GET['lang']."&filter=".$_GET['filter']);
	}
/***********************************************************************************************************
*																											
*	Prepocet ceny - v pripade nulove hodnoty u zbozi, odstraneni zbozi z kosiku								
*																											
***********************************************************************************************************/
	if ($_POST['action'] == "recalculate"){
		/* Pokud je uzivatel prihlaseny bere se jako ID basketu jeho admin_id jinak se bere v potaz session_id */
		if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
			$shop_basket = "shop_basket_admin_id='".$_SESSION['loginid']."'";
		} else {
			$shop_basket = "shop_basket_session_id='".$_SESSION['sidd']."'";
		}
		$res = mysql_query("SELECT shop_basket_products_id FROM $db_shop_basket WHERE $shop_basket") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){
			$quantity_number = $_POST['products'][$ar['shop_basket_products_id']];
			if (is_numeric($quantity_number)){
				if ($quantity_number == 0){
					mysql_query("DELETE FROM $db_shop_basket WHERE $shop_basket AND shop_basket_products_id=".(integer)$ar['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				} else {
					mysql_query("UPDATE $db_shop_basket SET shop_basket_quantity=".(integer)$quantity_number." WHERE $shop_basket AND shop_basket_products_id=".(integer)$ar['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=vb&lang=".$_GET['lang']."&filter=".$_GET['filter']);
	}
/***********************************************************************************************************
*																											
*	Odstraneni vybraneho zbozi z kosiku																		
*	(Remove Item From Basket)																				
*																											
***********************************************************************************************************/
	if ($_GET['action'] == "rifb"){
		if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
			$res_admin = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_admin = mysql_fetch_array($res_admin);
			$where = "shop_basket_admin_id=".(integer)$ar_admin['admin_id']."";
		} else {
			$where = "shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."'";
		}
		mysql_query("DELETE FROM $db_shop_basket WHERE $where AND shop_basket_products_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		header ("Location: ".$eden_cfg['url']."index.php?action=vb&lang=".$_GET['lang']."&filter=".$_GET['filter']);
		exit;
	}
/***********************************************************************************************************
*																											
*	Zalozeni order zaznamu																					
*																											
***********************************************************************************************************/
	if ($_POST['action'] == "add_order"){
		$res_basket = mysql_query("SELECT * FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']." ORDER BY shop_basket_products_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		while ($ar_basket = mysql_fetch_array($res_basket)){
			/*
			*	Kontrola zda pocet zbozi v kosiku neni vetsi nez pocet produktu na sklade
			*/
			$res_product = mysql_query("SELECT shop_product_id, shop_product_quantity, shop_product_quantity_in_orders, shop_product_allow_order_if_stock_is_0  FROM $db_shop_product WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_product = mysql_fetch_array($res_product)){
				if ($ar_product['shop_product_allow_order_if_stock_is_0'] == 0 && (($ar_product['shop_product_quantity'] - $ar_product['shop_product_quantity_in_orders']) < $ar_basket['shop_basket_quantity'])){
					/*
					*	Pokud je na sklade mene produktu nez v kosiku, vrati shop uzivatele na stranku s kosikem pro upravu poctu kusu daneho produktu
					*/
					header ("Location: ".$eden_cfg['url']."index.php?action=vb&b_action=nep&b_nep=".$ar_basket['shop_basket_products_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']);
					exit;
				} else {
					/*
					*	Pokud je vse v poradku zapise se k produktu docasny pocet
					*/
					mysql_query("UPDATE $db_shop_product SET shop_product_quantity_in_orders = shop_product_quantity_in_orders + ".(integer)$ar_basket['shop_basket_quantity']." WHERE shop_product_id=".(integer)$ar_product['shop_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
		}
		mysql_data_seek($res_basket, 0);
		
		$res1 = mysql_query("SELECT a.*, ac.*, c.country_name 
		FROM $db_admin AS a 
		JOIN $db_admin_contact AS ac ON ac.aid=".(integer)$_POST['oaid']." 
		JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
		WHERE a.admin_id=".(integer)$_POST['oaid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar1 = mysql_fetch_array($res1);
		$res3 = mysql_query("SELECT acs.*, c.* FROM $db_admin_contact_shop AS acs, $db_country AS c WHERE acs.aid=".(integer)$_POST['oaid']." AND c.country_id=acs.admin_contact_shop_country") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar3 = mysql_fetch_array($res3);
		$res4 = mysql_query("SELECT spm.*, scar.* FROM $db_shop_payment_methods AS spm, $db_shop_carriage AS scar WHERE spm.shop_payment_methods_id=".(integer)$_POST['shop_payment_methods_id']." AND scar.shop_carriage_id=".(integer)$_POST['shop_carriage_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar4 = mysql_fetch_array($res4);
		$res5 = mysql_query("SELECT shop_orders_id FROM $db_shop_orders WHERE shop_orders_admin_id=".(integer)$_SESSION['loginid']." AND shop_orders_orders_status=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar5 = mysql_fetch_array($res5);
		$num5 = mysql_num_rows($res5);
		
		$res_setup = mysql_query("SELECT * FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_setup = mysql_fetch_array($res_setup);
		
		// Nastaveni jazyku
		if ($ar1['admin_lang'] != ""){$admin_lang = $ar1['admin_lang'];} else {$admin_lang = $ar_setup['setup_basic_lang'];}
		
		$res_shop_setup = mysql_query("SELECT * FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($admin_lang)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_shop_setup = mysql_fetch_array($res_shop_setup);
		
		$shop_orders_admin_firstname = $ar1['admin_firstname'];
		$shop_orders_admin_name = $ar1['admin_name'];
		$shop_orders_admin_title = $ar1['admin_title'];
		$shop_orders_admin_telephone = $ar1['admin_contact_telefon'];
		$shop_orders_admin_mobile = $ar1['admin_contact_mobile'];
		$shop_orders_admin_email_address = $ar1['admin_email'];
		$shop_orders_admin_address_format_id = 0;
		if ($_SESSION['u_status'] == "seller"){
			$res_seller = mysql_query("SELECT ss.*, c.country_name AS delivery_country, cc.country_name AS invoice_country 
			FROM $db_shop_sellers AS ss 
			JOIN $db_country as cc ON cc.country_id=ss.shop_seller_invoice_country_id 
			JOIN $db_country AS c ON c.country_id=ss.shop_seller_delivery_country_id 
			WHERE ss.shop_seller_admin_id=".$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_seller = mysql_fetch_array($res_seller);
			$shop_orders_admin_company = $ar_seller['shop_seller_company_name'];
			$shop_orders_admin_address1 = $ar_seller['shop_seller_delivery_address_1'];
			$shop_orders_admin_address2 = $ar_seller['shop_seller_delivery_address_2'];
			$shop_orders_admin_suburb = "";
			$shop_orders_admin_city = $ar_seller['shop_seller_delivery_city'];
			$shop_orders_admin_postcode = $ar_seller['shop_seller_delivery_postcode'];
			$shop_orders_admin_state = "";
			$shop_orders_admin_country = $ar_seller['delivery_country'];
			$shop_orders_delivery_firstname = $ar3['admin_contact_shop_firstname'];
			$shop_orders_delivery_name = $ar3['admin_contact_shop_name'];
			$shop_orders_delivery_title = "";
			$shop_orders_delivery_company = $ar_seller['shop_seller_company_name'];
			$shop_orders_delivery_address1 = $ar_seller['shop_seller_delivery_address_1'];
			$shop_orders_delivery_address2 = $ar_seller['shop_seller_delivery_address_2'];
			$shop_orders_delivery_suburb = "";
			$shop_orders_delivery_city = $ar_seller['shop_seller_delivery_city'];
			$shop_orders_delivery_postcode = $ar_seller['shop_seller_delivery_postcode'];
			$shop_orders_delivery_state = "";
			$shop_orders_delivery_country = $ar_seller['delivery_country'];
			$shop_orders_delivery_address_format_id = 0;
			$shop_orders_billing_firstname = $ar3['admin_contact_shop_firstname'];
			$shop_orders_billing_name = $ar3['admin_contact_shop_name'];
			$shop_orders_billing_title = "";
			$shop_orders_billing_company = $ar_seller['shop_seller_company_name'];
			$shop_orders_billing_address1 = $ar_seller['shop_seller_invoice_address_1'];
			$shop_orders_billing_address2 = $ar_seller['shop_seller_invoice_address_2'];
			$shop_orders_billing_suburb = "";
			$shop_orders_billing_city = $ar_seller['shop_seller_invoice_city'];
			$shop_orders_billing_postcode = $ar_seller['shop_seller_invoice_postcode'];
			$shop_orders_billing_state = "";
			$shop_orders_billing_country = $ar_seller['invoice_country'];
			$shop_orders_wholesale = 1;
		} else {
			$shop_orders_admin_address1 = $ar1['admin_contact_address_1'];
			$shop_orders_admin_address2 = $ar1['admin_contact_address_2'];
			$shop_orders_admin_suburb = '';
			$shop_orders_admin_city = $ar1['admin_contact_city'];
			$shop_orders_admin_postcode = $ar1['admin_contact_postcode'];
			$shop_orders_admin_state = '';
			$shop_orders_admin_country = $ar1['country_name'];
			$shop_orders_delivery_firstname = $ar3['admin_contact_shop_firstname'];
			$shop_orders_delivery_name = $ar3['admin_contact_shop_name'];
			$shop_orders_delivery_title = $ar3['admin_contact_shop_title'];
			$shop_orders_delivery_company = "";
			$shop_orders_delivery_address1 = $ar3['admin_contact_shop_address_1'];
			$shop_orders_delivery_address2 = $ar3['admin_contact_shop_address_2'];
			$shop_orders_delivery_suburb = "";
			$shop_orders_delivery_city = $ar3['admin_contact_shop_city'];
			$shop_orders_delivery_postcode = $ar3['admin_contact_shop_postcode'];
			$shop_orders_delivery_state = "";
			$shop_orders_delivery_country = $ar3['country_name'];
			$shop_orders_delivery_address_format_id = 0;
			$shop_orders_billing_firstname = $shop_orders_admin_firstname;
			$shop_orders_billing_name = $shop_orders_admin_name;
			$shop_orders_billing_title = $shop_orders_admin_title;
			$shop_orders_billing_company = $shop_orders_admin_company;
			$shop_orders_billing_address1 = $shop_orders_admin_address1;
			$shop_orders_billing_address2 = $shop_orders_admin_address2;
			$shop_orders_billing_suburb = $shop_orders_admin_suburb;
			$shop_orders_billing_city = $shop_orders_admin_city;
			$shop_orders_billing_postcode = $shop_orders_admin_postcode;
			$shop_orders_billing_state = $shop_orders_admin_state;
			$shop_orders_billing_country = $shop_orders_admin_country;
			$shop_orders_wholesale = 0;
		}
		$shop_orders_payment_method = $ar4['shop_payment_methods_title'];
		$shop_orders_carriage_method = $ar4['shop_carriage_title'];
	   	$shop_orders_carriage_price = $ar4['shop_carriage_price'];
		$shop_orders_billing_address_format_id = '';
		$shop_orders_payment_module_code = '';
		$shop_orders_coupon_code = '';
		$shop_orders_cc_type = '';
		$shop_orders_cc_owner = '';
		$shop_orders_cc_number = '';
		$shop_orders_cc_expires = '';
		$shop_orders_cc_cvv = '';
		$shop_orders_orders_status = 1;
		$shop_orders_orders_date_finished = '';
		$shop_orders_currency = $ar_shop_setup['shop_setup_currency'];
		$shop_orders_currency_value = '';
		$shop_orders_order_tax = '';
		$shop_orders_ip_address = $_POST['orders_shop_ip'];
		
		/*
		*	Kdyz databaze najde neuzavrenou obednavku, updatuje se tato obednavka,
		*	jinak se zalozi nova
		*/
		if ($num5 > 0){
			mysql_query("UPDATE $db_shop_orders SET
				shop_orders_admin_firstname='".mysql_real_escape_string($shop_orders_admin_firstname)."',
				shop_orders_admin_name='".mysql_real_escape_string($shop_orders_admin_name)."',
				shop_orders_admin_title='".mysql_real_escape_string($shop_orders_admin_title)."',
				shop_orders_admin_company='".mysql_real_escape_string($shop_orders_admin_company)."',
				shop_orders_admin_address1='".mysql_real_escape_string($shop_orders_admin_address1)."',
				shop_orders_admin_address2='".mysql_real_escape_string($shop_orders_admin_address2)."',
				shop_orders_admin_suburb='".mysql_real_escape_string($shop_orders_admin_suburb)."',
				shop_orders_admin_city='".mysql_real_escape_string($shop_orders_admin_city)."',
				shop_orders_admin_postcode='".mysql_real_escape_string($shop_orders_admin_postcode)."',
				shop_orders_admin_state='".mysql_real_escape_string($shop_orders_admin_state)."',
				shop_orders_admin_country='".mysql_real_escape_string($shop_orders_admin_country)."',
				shop_orders_admin_telephone='".mysql_real_escape_string($shop_orders_admin_telephone)."',
				shop_orders_admin_mobile='".mysql_real_escape_string($shop_orders_admin_mobile)."',
				shop_orders_admin_email_address='".mysql_real_escape_string($shop_orders_admin_email_address)."',
				shop_orders_admin_address_format_id=".(float)$shop_orders_admin_address_format_id.",
				shop_orders_delivery_firstname='".mysql_real_escape_string($shop_orders_delivery_firstname)."',
				shop_orders_delivery_name='".mysql_real_escape_string($shop_orders_delivery_name)."',
				shop_orders_delivery_title='".mysql_real_escape_string($shop_orders_delivery_title)."',
				shop_orders_delivery_company='".mysql_real_escape_string($shop_orders_delivery_company)."',
				shop_orders_delivery_address1='".mysql_real_escape_string($shop_orders_delivery_address1)."',
				shop_orders_delivery_address2='".mysql_real_escape_string($shop_orders_delivery_address2)."',
				shop_orders_delivery_suburb='".mysql_real_escape_string($shop_orders_delivery_suburb)."', 
				shop_orders_delivery_city='".mysql_real_escape_string($shop_orders_delivery_city)."',
				shop_orders_delivery_postcode='".mysql_real_escape_string($shop_orders_delivery_postcode)."',
				shop_orders_delivery_state='".mysql_real_escape_string($shop_orders_delivery_state)."',
				shop_orders_delivery_country='".mysql_real_escape_string($shop_orders_delivery_country)."',
				shop_orders_delivery_address_format_id=".(float)$shop_orders_delivery_address_format_id.",
				shop_orders_billing_firstname='".mysql_real_escape_string($shop_orders_billing_firstname)."',
				shop_orders_billing_name='".mysql_real_escape_string($shop_orders_billing_name)."',
				shop_orders_billing_title='".mysql_real_escape_string($shop_orders_billing_title)."',
				shop_orders_billing_company='".mysql_real_escape_string($shop_orders_billing_company)."',
				shop_orders_billing_address1='".mysql_real_escape_string($shop_orders_billing_address1)."',
				shop_orders_billing_address2='".mysql_real_escape_string($shop_orders_billing_address2)."',
				shop_orders_billing_suburb='".mysql_real_escape_string($shop_orders_billing_suburb)."',
				shop_orders_billing_city='".mysql_real_escape_string($shop_orders_billing_city)."',
				shop_orders_billing_postcode='".mysql_real_escape_string($shop_orders_billing_postcode)."',
				shop_orders_billing_state='".mysql_real_escape_string($shop_orders_billing_state)."',
				shop_orders_billing_country='".mysql_real_escape_string($shop_orders_billing_country)."',
				shop_orders_billing_address_format_id=".(float)$shop_orders_billing_address_format_id.",
				shop_orders_payment_method='".mysql_real_escape_string($shop_orders_payment_method)."',
				shop_orders_payment_module_code='".mysql_real_escape_string($shop_orders_payment_module_code)."',
				shop_orders_carriage_method='".mysql_real_escape_string($shop_orders_carriage_method)."',
				shop_orders_carriage_price=".(float)$shop_orders_carriage_price.",
				shop_orders_coupon_code='".mysql_real_escape_string($shop_orders_coupon_code)."',
				shop_orders_cc_type='".mysql_real_escape_string($shop_orders_cc_type)."',
				shop_orders_cc_owner='".mysql_real_escape_string($shop_orders_cc_owner)."',
				shop_orders_cc_number='".mysql_real_escape_string($shop_orders_cc_number)."',
				shop_orders_cc_expires='".mysql_real_escape_string($shop_orders_cc_expires)."',
				shop_orders_last_modified = NOW(),
				shop_orders_date_ordered = NOW(),
				shop_orders_currency='".mysql_real_escape_string($shop_orders_currency)."',
				shop_orders_currency_value=".(float)$shop_orders_currency_value.",
				shop_orders_order_tax=".(float)$shop_orders_order_tax.",
				shop_orders_ip_address='".mysql_real_escape_string($shop_orders_ip_address)."',
				shop_orders_wholesale=".(integer)$shop_orders_wholesale." 
				WHERE shop_orders_id=".(integer)$ar5['shop_orders_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$id = $ar5['shop_orders_id'];
			
		} else {
			mysql_query("INSERT INTO $db_shop_orders VALUES(
			'',
			'',
			'".(integer)$_POST['oaid']."',
			'',
			'".mysql_real_escape_string($shop_orders_admin_firstname)."',
			'".mysql_real_escape_string($shop_orders_admin_name)."',
			'".mysql_real_escape_string($shop_orders_admin_title)."',
			'".mysql_real_escape_string($shop_orders_admin_company)."',
			'".mysql_real_escape_string($shop_orders_admin_address1)."',
			'".mysql_real_escape_string($shop_orders_admin_address2)."',
			'".mysql_real_escape_string($shop_orders_admin_suburb)."',
			'".mysql_real_escape_string($shop_orders_admin_city)."',
			'".mysql_real_escape_string($shop_orders_admin_postcode)."',
			'".mysql_real_escape_string($shop_orders_admin_state)."',
			'".mysql_real_escape_string($shop_orders_admin_country)."',
			'".mysql_real_escape_string($shop_orders_admin_telephone)."',
			'".mysql_real_escape_string($shop_orders_admin_mobile)."',
			'".mysql_real_escape_string($shop_orders_admin_email_address)."',
			'".(integer)$shop_orders_admin_address_format_id."',
			'".mysql_real_escape_string($shop_orders_delivery_firstname)."',
			'".mysql_real_escape_string($shop_orders_delivery_name)."',
			'".mysql_real_escape_string($shop_orders_delivery_title)."',
			'".mysql_real_escape_string($shop_orders_delivery_company)."',
			'".mysql_real_escape_string($shop_orders_delivery_address1)."',
			'".mysql_real_escape_string($shop_orders_delivery_address2)."',
			'".mysql_real_escape_string($shop_orders_delivery_suburb)."', 
			'".mysql_real_escape_string($shop_orders_delivery_city)."',
			'".mysql_real_escape_string($shop_orders_delivery_postcode)."',
			'".mysql_real_escape_string($shop_orders_delivery_state)."',
			'".mysql_real_escape_string($shop_orders_delivery_country)."',
			'".(integer)$shop_orders_delivery_address_format_id."',
			'".mysql_real_escape_string($shop_orders_billing_firstname)."',
			'".mysql_real_escape_string($shop_orders_billing_name)."',
			'".mysql_real_escape_string($shop_orders_billing_title)."',
			'".mysql_real_escape_string($shop_orders_billing_company)."',
			'".mysql_real_escape_string($shop_orders_billing_address1)."',
			'".mysql_real_escape_string($shop_orders_billing_address2)."',
			'".mysql_real_escape_string($shop_orders_billing_suburb)."',
			'".mysql_real_escape_string($shop_orders_billing_city)."',
			'".mysql_real_escape_string($shop_orders_billing_postcode)."',
			'".mysql_real_escape_string($shop_orders_billing_state)."',
			'".mysql_real_escape_string($shop_orders_billing_country)."',
			'".(integer)$shop_orders_billing_address_format_id."',
			'".mysql_real_escape_string($shop_orders_payment_method)."',
			'".mysql_real_escape_string($shop_orders_payment_module_code)."',
			'".mysql_real_escape_string($shop_orders_carriage_method)."',
			'".(float)$shop_orders_carriage_price."',
			'".mysql_real_escape_string($shop_orders_coupon_code)."',
			'".mysql_real_escape_string($shop_orders_cc_type)."',
			'".mysql_real_escape_string($shop_orders_cc_owner)."',
			'".mysql_real_escape_string($shop_orders_cc_number)."',
			'".mysql_real_escape_string($shop_orders_cc_expires)."',
			NOW(),
			NOW(),
			'',
			'',
			'',
			'',
			'',
			'".(integer)$shop_orders_orders_status."',
			'',
			'".mysql_real_escape_string($shop_orders_currency)."',
			'".(float)$shop_orders_currency_value."',
			'',
			'',
			'".(float)$shop_orders_order_tax."',
			'0',
			'".mysql_real_escape_string($shop_orders_ip_address)."',
			'".(integer)$shop_orders_wholesale."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_id = mysql_fetch_array($res_id);
			$id = $ar_id[0];
			$shop_orders_invoice_id = $ar_shop_setup['shop_setup_invoice_prefix'].$id;
			mysql_query("UPDATE $db_shop_orders SET	shop_orders_invoice_id='".mysql_real_escape_string($shop_orders_invoice_id)."' WHERE shop_orders_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		/*
		*	Smazeme produkty z objednavky
		*/
		mysql_query("DELETE FROM $db_shop_orders_product WHERE shop_orders_orders_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		if ($_SESSION['u_status'] == "seller"){
			/* Nacteme vsechny slevove kategorie pro prodejce */
			$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=0;
			/* Ty pak ulozime do vicerozmerneho pole spolu s mnozstvim - na zacatku je mnozstvi 0 */
			while ($ar_discount = mysql_fetch_array($res_discount)){
				/* array (discount kategorie, mnozstvi vyrobku) */
				$discount[$i] = array($ar_discount['shop_discount_category_id'],0);
				$i++;
			}
			/* Spocitame mnozstvi slevovych kategorii v poli - nize pouzijeme pro iteraci */
			$pocet_disc = count($discount);
			/* Projdeme vsechny polozky v kosiku */
			mysql_data_seek($res_basket, 0);
			while($ar_basket = mysql_fetch_array($res_basket)){
				$res_product = mysql_query("SELECT shop_product_discount_cat_seller_id FROM $db_shop_product WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." GROUP BY shop_product_discount_cat_seller_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				/* Projdeme vsehchny produkty v kosiku a patrame po tom jestli maji ulozen zaznam o slevove kategorii */
				while ($ar_product = mysql_fetch_array($res_product)){
					$y = 0;
					/* Projdeme vsechny slevove kategorie pro obchodniky */
					while ($y < $pocet_disc){
						/* A kdyz nalezneme zaznam pripocteme mnozstvi kusu daneho produktu do vicerozmerneho pole se slevovymi kategoriemi */
						if ($discount[$y][0] == $ar_product['shop_product_discount_cat_seller_id']){$discount[$y][1] = $discount[$y][1] + (integer)$ar_basket['shop_basket_quantity'];}
						$y++;
					}
				}
			}
			
			/* Do vicerozmerneho pole vlozime ID discount kategorie, mnozstvi vyrobku a cenu za jeden vyrobek*/
			$y = 0;
			while ($y < $pocet_disc){
				//echo "Discount Cat ID = ".$discount[$y][0]." - Qty = ".$discount[$y][1]."<br>";
				$res_dc_price = mysql_query("SELECT shop_discount_category_discount_price FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=".$discount[$y][0]." AND ".$discount[$y][1]." BETWEEN shop_discount_category_discounted_from_amount AND shop_discount_category_discounted_to_amount") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_dc_price = mysql_fetch_array($res_dc_price);
				/* $discount_cat[ID discount kategorie][0 = mnozstvi vyrobku/1 = cena] */
				$discount_cat[$discount[$y][0]] = array($discount[$y][1],$ar_dc_price['shop_discount_category_discount_price']);
				$y++;
			}
		}
		mysql_data_seek($res_basket, 0);
		while($ar_basket = mysql_fetch_array($res_basket)){
			$res_product = mysql_query("SELECT p.*, t.*, c.* FROM $db_shop_product AS p, $db_shop_tax_rates AS t, $db_category AS c WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." AND t.shop_tax_rates_class_id = p.shop_product_vat_class_id AND c.category_id = p.shop_product_master_category") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_product = mysql_fetch_array($res_product);
			if ($_SESSION['u_status'] == "seller"){
			   	/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1] * $ar_basket['shop_basket_quantity'];
				$price = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1];
			} else {
				/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $ar_product['shop_product_selling_price'] * $ar_basket['shop_basket_quantity'];
				$price = $ar_product['shop_product_selling_price'];
			}
			$price_ex_vat = $price_inc_vat / ($ar_product_tax_rate['shop_tax_rates_rate']/100+1);
			$price_vat = ($price_inc_vat - $price_ex_vat);
			
			$total_nett_price = $price_ex_vat + $total_nett_price;
			$total_vat = $price_vat + $total_vat;
			$subtotal = $price_inc_vat + $subtotal;
			
			$shop_orders_is_free = '';
			$shop_orders_discount_type = '';
			$shop_orders_discount_type_from = 0;
			$shop_orders_onetime_charges = 0.0000; 
			$shop_orders_product_prid = '';
			$shop_orders_final_price = $price_ex_vat;
			mysql_query("INSERT INTO $db_shop_orders_product VALUES(
				'',
				'".(integer)$id."',
				'".(integer)$ar_product['shop_product_id']."',
				'".mysql_real_escape_string($ar_product['shop_product_model'])."',
				'".mysql_real_escape_string($ar_product['shop_product_name'])."',
				'".(float)$price."',
				'".(float)$ar_product['shop_tax_rates_rate']."',
				'".(float)$ar_basket['shop_basket_quantity']."',
				'".(integer)$shop_orders_is_free."',
				'".(integer)$shop_orders_discount_type."',
				'".(integer)$shop_orders_discount_type_from."',
				'".(float)$shop_orders_onetime_charges."',
				'".mysql_real_escape_string($shop_orders_product_prid)."',
				'".(float)$shop_orders_final_price."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			/* Spatny vypocet dane!!!
			$shop_orders_order_total = $shop_orders_order_total + $shop_orders_final_price;
			$shop_orders_order_tax = $total_vat;
			$shop_orders_order_total = MyCeil($shop_orders_order_total,2);
			$shop_orders_order_tax = MyCeil($shop_orders_order_tax,2);
			*/
			$shop_orders_order_total = TepRound($total_nett_price,2);
			$shop_orders_order_tax = TepRound($total_vat,2);
		}
		$total_total = $total_nett_price + $total_vat + $shop_orders_carriage_price;
		$shop_orders_order_total_netto = TepRound($total_total);
		if ($_SESSION['u_status'] == "seller"){
			mysql_query("UPDATE $db_shop_sellers SET shop_seller_last_order=NOW() shop_seller_total_amount = shop_seller_total_amount + ".(float)$shop_orders_order_total." WHERE shop_seller_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			mysql_query("
			UPDATE $db_shop_orders 
			SET shop_orders_order_total_netto=".(float)$shop_orders_order_total_netto.", shop_orders_order_total=".(float)$shop_orders_order_total.", shop_orders_order_tax=".(float)$shop_orders_order_tax." 
			WHERE shop_orders_id=".(integer)$id
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		header ("Location: ".$eden_cfg['url']."index.php?action=04&id=".$id."&lang=".$_GET['lang']."&filter=".$_GET['filter']);
	}
/***********************************************************************************************************
*																											
*	Ulozeni zaznamu o odeslani na PayPal																	
*																											
***********************************************************************************************************/
	if ($_GET['action'] == "paypal"){
		
		//$res_shop_setup = mysql_query("SELECT shop_setup_paypal_test_api_url FROM $db_shop_setup WHERE shop_setup_lang='en'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		//$ar_shop_setup = mysql_fetch_array($res_shop_setup);
		
		error_reporting(0);
		$res = mysql_query("SELECT shop_basket_products_id FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){
			mysql_query("DELETE FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']." AND shop_basket_products_id=".(integer)$ar['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		// Nastavi ze obednavka byla odeslana k zaplaceni
		mysql_query("UPDATE $db_shop_orders SET shop_orders_last_modified = NOW(), shop_orders_date_sended_for_payment = NOW(), shop_orders_orders_status=2 WHERE shop_orders_id=".(integer)$_POST['orders_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		// Odeslani emailu zakaznikovi s potvrzenim, ze jeho obednavka byla predana k zaplaceni a nyni se ceka na zaplaceni
		SendEmailToCustomer($_POST['orders_id'],1,"full");
		SendEmailToCustomer($_POST['orders_id'],2,"full");
		
		header('HTTP/1.1 307 Temporary Redirect'); 
		header('Location: https://www.paypal.com/uk/cgi-bin/webscr');
	}
/***********************************************************************************************************
*																											
*	Kompletni transakce																						
*																											
***********************************************************************************************************/
	
	if ($_GET['action'] == "complete"){
	
		// IPN validation modes, choose: 1 or 2
		
		//* 1 = Live Via PayPal Network
		//* 2 = Test Via PayPal Sandbox
		$postmode = 1;
				
		//* 1 = Via PayPal
		//* 2 = Via PayPal UK
		$pp_country = 2;
		
		// Convert super globals on older builds
		if (phpversion() <= '4.0.6'){
			$_SERVER = ($HTTP_SERVER_VARS);
			$_POST = ($HTTP_POST_VARS); 
		}
		
		// No ipn post means this script does not exist
		if (!@$_POST['txn_type']){
			@header("Status: 404 Not Found"); exit;
		} else {
			@header("Status: 200 OK");  // Prevents ipn reposts on some servers
			
			// Add "cmd" to prepare for post back validation
			// Read the ipn post from paypal or eliteweaver uk
			// Fix issue with magic quotes enabled on gpc
			// Apply variable antidote (replaces array filter)
			// Destroy the original ipn post (security reason)
			// Reconstruct the ipn string ready for the post
			$postipn = 'cmd=_notify-validate'; // Notify validate
			
			foreach ($_POST as $ipnkey => $ipnval) {
				if (get_magic_quotes_gpc()){
					$ipnval = stripslashes ($ipnval); // Fix issue with magic quotes
				}
				if (!preg_match("/^[_0-9a-z-]{1,30}$/i",$ipnkey) || !strcasecmp ($ipnkey, 'cmd')) { // ^ Antidote to potential variable injection and poisoning
					unset ($ipnkey); unset ($ipnval); 
				} // Eliminate the above
				if (@$ipnkey != '') { // Remove empty keys (not values)
					@$_PAYPAL[$ipnkey] = $ipnval; // Assign data to new global array
					unset ($_POST); // Destroy the original ipn post array, sniff...
					$postipn.='&'.@$ipnkey.'='.urlencode(@$ipnval); 
				}
			} // Notify string
			$error = 0; // No errors let's hope it's going to stays like this!
			
				// IPN validation mode 1: Live Via PayPal Network
			if ($postmode == 1)	{
				$domain = "www.paypal.com";
				// IPN validation mode 2: Test Via PayPal Sandbox
			} elseif ($postmode == 2) {
				$domain = "www.sandbox.paypal.com"; 
			}
			
				// IPN validation mode 1: Via PayPal
			if ($pp_country == 1) {
				$paypal_country = "/cgi-bin/webscr";
				// IPN validation mode 2: Via PayPal Uk
			} elseif ($pp_country == 2) {
				$paypal_country = "/uk/cgi-bin/webscr"; 
			}
			
			@set_time_limit(60); // Attempt to double default time limit incase we switch to Get
			
			// Post back the reconstructed instant payment notification
			$socket = @fsockopen($domain,80,$errno,$errstr,30);
			$header = "POST ".$paypal_country." HTTP/1.0\r\n";
			$header.= "User-Agent: PHP/".phpversion()."\r\n";
			$header.= "Referer: ".$_SERVER['HTTP_HOST'].
			$_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']."\r\n";
			$header.= "Server: ".$_SERVER['SERVER_SOFTWARE']."\r\n";
			$header.= "Host: ".$domain.":80\r\n";
			$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header.= "Content-Length: ".strlen($postipn)."\r\n";
			$header.= "Accept: */*\r\n\r\n";
			
			//* Note: "Connection: Close" is not required using HTTP/1.0
			
			// Problem: Now is this your firewall or your ports?
	        if (!$socket && !$error) {
				// Switch to a Get request for a last ditch attempt!
				$getrq=1;
				
				if (phpversion() >= '4.3.0' && function_exists('file_get_contents')){
					// Checking for a new function
				} else	{ // No? We'll create it instead
					function file_get_contents($ipnget) {
						$ipnget = @file($ipnget);
						return $ipnget[0];
					}
				}
			}
			
	        $response = @file_get_contents('http://'.$domain.':80'.$paypal_country.'?'.$postipn);
			
			if (!$response){
				$error=1;
				$getrq=0;
				
			// If no problems have occured then we proceed with the processing
			} else {
				@fputs ($socket,$header.$postipn."\r\n\r\n"); // Required on some environments
				while (!feof($socket)) {
					$response = fgets ($socket,1024); 
				}
			}
			$response = trim ($response); // Also required on some environments
			
			// uncomment '#' to assign posted variables to local variables
			#extract($_PAYPAL); // if globals is on they are already local
			
			// and/or >>>
			
			// refer to each ipn variable by reference (recommended)
			// $_PAYPAL['receiver_id']; etc... (see: ipnvars.txt)
			
			// IPN was confirmed as both genuine and VERIFIED
			if (strcmp ($response, 'VERIFIED') == 0){
				// The following example loop is for 'cart' notifications only!
				//for ($i=0; $i < $_PAYPAL['num_cart_items']; $i++){    // ^ This is set to 0 for a reason related to platform compatibility!
					//$x=$i+1; // < In loop incrementation (please see above comment)
					// Anything related to item specific cart data can be dealt with inside of this loop.
					// Example: $item_name = ($_PAYPAL['item_name'.$x]);
					// Must NOT be referenced outside this loop using this type of variable declaration!
				//}
				
				// Check that the "payment_status" variable is: Completed
				// If it is Pending you may want to inform your customer?
				// Check your db to ensure this "txn_id" is not a duplicate
				// You may want to check "payment_gross" or "mc_gross" matches listed prices?
				// You definately want to check the "receiver_email", "receiver_id" or "business" is yours
				// Update your db and process this payment accordingly
				
				//***************************************************************//
				//* Tip: Use the internal auditing function to do some of this! *//
				//* **************************************************************************************//
				//* Help: if(variableAudit('mc_gross','0.01') &&					 *//
				//* 	     variableAudit('receiver_email','paypal@domain.com') && 			 *//
				//* 	     variableAudit('payment_status','Completed')){ $do_this; } else { do_that; } *//
				//****************************************************************************************//
				$res_ord = mysql_query("SELECT COUNT(shop_orders_paypal_ipn_id) FROM $db_shop_orders WHERE shop_orders_paypal_ipn_id='".mysql_real_escape_string($_PAYPAL['txn_id'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$res_ord_num = mysql_fetch_array($res_ord);
				$res_shop_setup = mysql_query("SELECT shop_setup_paypal_business_account FROM $db_shop_setup WHERE shop_setup_lang='en'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_shop_setup = mysql_fetch_array($res_shop_setup);
				$res_ord_2 = mysql_query("SELECT shop_orders_carriage_price, shop_orders_order_total, shop_orders_order_tax FROM $db_shop_orders WHERE shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_ord_2 = mysql_fetch_array($res_ord_2);
				// check the payment_status is Completed
				if (strcmp ($_PAYPAL['payment_status'], "Completed") != 0){
					$eden_ipn_check = 3;
				// check that txn_id has not been previously processed
				} elseif ($res_ord_num[0] != 0){
					$eden_ipn_check = 4;
				// check that receiver_email is your Primary PayPal email
				} elseif ($_PAYPAL['receiver_email'] != $ar_shop_setup['shop_setup_paypal_business_account']){
					$eden_ipn_check = 5;
				// check that payment_currency are correct
				} elseif (strcmp ($_PAYPAL['settle_currency'], $ar_shop_setup['shop_setup_currency']) != 0){
					$eden_ipn_check = 6;
				// check that mc_gross are correct
				} elseif ($ar_ord_2['shop_orders_carriage_price'] + $ar_ord_2['shop_orders_order_total'] + $ar_ord_2['shop_orders_order_tax'] != $_PAYPAL['mc_gross']){
					$eden_ipn_check = 7;
				// process payment
				} else {
					$eden_ipn_check = 1;
					$res_prod = mysql_query("SELECT shop_orders_shop_product_quantity, shop_orders_shop_product_id FROM $db_shop_orders_product WHERE shop_orders_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar_prod = mysql_fetch_array($res_prod)){
						mysql_query("UPDATE $db_shop_product SET shop_product_quantity_in_orders = shop_product_quantity_in_orders - ".(float)$ar_prod['shop_orders_shop_product_quantity'].", shop_product_quantity_sold = shop_product_quantity_sold + ".(float)$ar_prod['shop_orders_shop_product_quantity']." WHERE shop_product_id=".(float)$ar_prod['shop_orders_shop_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
					mysql_query("UPDATE $db_shop_orders SET shop_orders_orders_status=3, shop_orders_last_modified=NOW(), shop_orders_date_purchased=NOW(), shop_orders_paypal_ipn_id='".mysql_real_escape_string($_PAYPAL['txn_id'])."' WHERE shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					
					// Odeslani emailu zakaznikovi s potvrzenim, ze jeho obednavka byla zaplacena a ceka se na zabaleni
					SendEmailToCustomer($_GET['oid'],3,"full");
				}
			// IPN was not validated as genuine and is INVALID
			}elseif (strcmp ($response, 'INVALID') == 0) {
				// Check your code for any post back validation problems
				// Investigate the fact that this could be a spoofed IPN
				// If updating your db, ensure this "txn_id" is not a duplicate
				// log for manual investigation
				$res_ord = mysql_query("SELECT shop_orders_paypal_ipn_id FROM $db_shop_orders WHERE shop_orders_paypal_ipn_id='".mysql_real_escape_string($_PAYPAL['txn_id'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$res_ord_num = mysql_num_rows($res_ord);
				if ($res_ord_num != 0){
					$eden_ipn_check = 21;
				} else {
					$eden_ipn_check = 2;
				}
			} else { // Just incase something serious should happen!
				// Kdyz 
				if ($_PAYPAL['txn_id'] != ""){
					mysql_query("UPDATE $db_shop_orders SET shop_orders_paypal_ipn_id='".mysql_real_escape_string($_PAYPAL['txn_id'])."' WHERE shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					
					$paypal_ipn_variables = "receiver_email = ".$_PAYPAL['receiver_email']."\r\n<br>";
					$paypal_ipn_variables .= "receiver_id = ".$_PAYPAL['receiver_id']."\r\n<br>";
					$paypal_ipn_variables .= "business = ".$_PAYPAL['business']."\r\n<br>";
					$paypal_ipn_variables .= "item_name = ".$_PAYPAL['item_name']."\r\n<br>";
					$paypal_ipn_variables .= "item_number = ".$_PAYPAL['item_number']."\r\n<br>";
					$paypal_ipn_variables .= "quantity = ".$_PAYPAL['quantity']."\r\n<br>";
					$paypal_ipn_variables .= "invoice = ".$_PAYPAL['invoice']."\r\n<br>";
					$paypal_ipn_variables .= "custom = ".$_PAYPAL['custom']."\r\n<br>";
					$paypal_ipn_variables .= "option_name1 = ".$_PAYPAL['option_name1']."\r\n<br>";
					$paypal_ipn_variables .= "option_selection1 = ".$_PAYPAL['option_selection1']."\r\n<br>";
					$paypal_ipn_variables .= "option_name2 = ".$_PAYPAL['option_name2']."\r\n<br>";
					$paypal_ipn_variables .= "option_selection2 = ".$_PAYPAL['option_selection2']."\r\n<br>";
					$paypal_ipn_variables .= "num_cart_items = ".$_PAYPAL['num_cart_items']."\r\n<br>";
					$paypal_ipn_variables .= "payment_status = ".$_PAYPAL['payment_status']."\r\n<br>";
					$paypal_ipn_variables .= "pending_reason = ".$_PAYPAL['pending_reason']."\r\n<br>";
					$paypal_ipn_variables .= "payment_date = ".$_PAYPAL['payment_date']."\r\n<br>";
					$paypal_ipn_variables .= "settle_amount = ".$_PAYPAL['settle_amount']."\r\n<br>";
					$paypal_ipn_variables .= "settle_currency = ".$_PAYPAL['settle_currency']."\r\n<br>";
					$paypal_ipn_variables .= "exchange_rate = ".$_PAYPAL['exchange_rate']."\r\n<br>";
					$paypal_ipn_variables .= "payment_gross = ".$_PAYPAL['payment_gross']."\r\n<br>";
					$paypal_ipn_variables .= "payment_fee = ".$_PAYPAL['payment_fee']."\r\n<br>";
					$paypal_ipn_variables .= "mc_gross = ".$_PAYPAL['mc_gross']."\r\n<br>";
					$paypal_ipn_variables .= "mc_fee = ".$_PAYPAL['mc_fee']."\r\n<br>";
					$paypal_ipn_variables .= "mc_currency = ".$_PAYPAL['mc_currency']."\r\n<br>";
					$paypal_ipn_variables .= "tax = ".$_PAYPAL['tax']."\r\n<br>";
					$paypal_ipn_variables .= "txn_id = ".$_PAYPAL['txn_id']."\r\n<br>";
					$paypal_ipn_variables .= "txn_type = ".$_PAYPAL['txn_type']."\r\n<br>";
					$paypal_ipn_variables .= "reason_code = ".$_PAYPAL['reason_code']."\r\n<br>";
					$paypal_ipn_variables .= "for_auction = ".$_PAYPAL['for_auction']."\r\n<br>";
					$paypal_ipn_variables .= "auction_buyer_id = ".$_PAYPAL['auction_buyer_id']."\r\n<br>";
					$paypal_ipn_variables .= "auction_close_date = ".$_PAYPAL['auction_close_date']."\r\n<br>";
					$paypal_ipn_variables .= "auction_multi_item = ".$_PAYPAL['auction_multi_item']."\r\n<br>";
					$paypal_ipn_variables .= "memo = ".$_PAYPAL['memo']."\r\n<br>";
					$paypal_ipn_variables .= "first_name = ".$_PAYPAL['first_name']."\r\n<br>";
					$paypal_ipn_variables .= "last_name = ".$_PAYPAL['last_name']."\r\n<br>";
					$paypal_ipn_variables .= "address_street = ".$_PAYPAL['address_street']."\r\n<br>";
					$paypal_ipn_variables .= "address_city = ".$_PAYPAL['address_city']."\r\n<br>";
					$paypal_ipn_variables .= "address_state = ".$_PAYPAL['address_state']."\r\n<br>";
					$paypal_ipn_variables .= "address_zip = ".$_PAYPAL['address_zip']."\r\n<br>";
					$paypal_ipn_variables .= "address_country = ".$_PAYPAL['address_country']."\r\n<br>";
					$paypal_ipn_variables .= "address_status = ".$_PAYPAL['address_status']."\r\n<br>";
					$paypal_ipn_variables .= "payer_email = ".$_PAYPAL['payer_email']."\r\n<br>";
					$paypal_ipn_variables .= "payer_id = ".$_PAYPAL['payer_id']."\r\n<br>";
					$paypal_ipn_variables .= "payer_business_name = ".$_PAYPAL['payer_business_name']."\r\n<br>";
					$paypal_ipn_variables .= "payer_status = ".$_PAYPAL['payer_status']."\r\n<br>";
					$paypal_ipn_variables .= "payment_type = ".$_PAYPAL['payment_type']."\r\n<br>";
					$paypal_ipn_variables .= "notify_version = ".$_PAYPAL['notify_version']."\r\n<br>";
					$paypal_ipn_variables .= "verify_sign = ".$_PAYPAL['verify_sign']."\r\n<br>";
					
					$paypal_ipn_variables .= "\r\n<br>\r\n<br>\r\n<br>\r\n<br>".$response;
					
					$res_ord_log = mysql_query("SELECT COUNT(*) FROM $db_shop_orders_log WHERE shop_orders_log_shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$res_ord_num_log = mysql_fetch_array($res_ord_log);
					if ($res_ord_num_log[0] > 0){
						mysql_query("UPDATE $db_shop_orders_log SET shop_orders_log_date=NOW(), shop_orders_log_paypal_ipn_data='".mysql_real_escape_string($paypal_ipn_variables)."' WHERE shop_orders_log_shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					} else {
						mysql_query("INSERT INTO $db_shop_orders_log VALUES('','".(integer)$_GET['oid']."',NOW(),'".mysql_real_escape_string($paypal_ipn_variables)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
					$eden_ipn_check = 81;
				} else {
					$eden_ipn_check = 8;
				}
			}
			mysql_query("UPDATE $db_shop_orders SET shop_orders_ipn_check_status=".(float)$eden_ipn_check." WHERE shop_orders_id=".(integer)$_GET['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			fclose ($socket);
		}
	}
/***********************************************************************************************************
*																											
*	Cancel order zaznamu																					
*																											
***********************************************************************************************************/
if ($_POST['action'] == "cancel_order"){
	mysql_query("UPDATE $db_shop_orders SET shop_orders_orders_status=6, shop_orders_last_modified=NOW(), shop_orders_date_cancelled=NOW() WHERE shop_orders_id=".(integer)$_POST['oid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$res = mysql_query("SELECT * FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar = mysql_fetch_array($res)){
		mysql_query("UPDATE $db_shop_product SET shop_product_quantity_in_orders = shop_product_quantity_in_orders - ".(float)$ar['shop_basket_quantity']." WHERE shop_product_id=".(integer)$ar['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_query("DELETE FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']." AND shop_basket_products_id=".(integer)$ar['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	// Odeslani emailu zakaznikovi s potvrzenim, ze jeho obednavka byla stornovana
	SendEmailToCustomer($_POST['oid'],6,"full");
	header ("Location: ".$eden_cfg['url']."index.php?action=vb&id=".$_POST['oid']."&lang=".$_GET['lang']."&filter=".$_GET['filter']);
}