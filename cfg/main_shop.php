<?php
/*********************************************************************************************************
*
*		SEZNAM FUNKCI
*
*		EdenShopBasket			-	EDEN SHOP BASKET
*		EdenShop01				-	EDEN SHOP PROGRESS 01
*		EdenShop02				-	EDEN SHOP PROGRESS 02
*		EdenShop03				-	EDEN SHOP PROGRESS 03
*		EdenShop04				-	EDEN SHOP PROGRESS 04
*		EdenShop05				-	EDEN SHOP PROGRESS 05
*		PrevOrders				-	EDEN SHOP PREVIOUS ORDERS
*		RoyalMailWorldZone		-	Zjisteni ve ktere zone se nachazi dana zeme (UK, EU, WO)
***********************************************************************************************************/
/***********************************************************************************************************
*
*			EDEN SHOP BASKET
*			$b_action 	= "nep" - Not Enough Products - Nedostatek produktu na sklade
*			$b_nep		= Cislo nedostatkoveho produktu pro zvyrazneni v kosiku
*
***********************************************************************************************************/
function EdenShopBasket(){
	
	global $db_articles,$db_shop_setup,$db_admin,$db_shop_basket,$db_shop_product,$db_shop_product_clothes,$db_shop_clothes_style,$db_shop_clothes_style_parents,$db_shop_clothes_colors,$db_shop_tax_rates,$db_category,$db_shop_carriage,$db_shop_discount_category;
	global $eden_cfg;
	global $project;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_setup = mysql_query("
	SELECT shop_setup_show_vat_subtotal, shop_setup_carriage_id, shop_setup_delivery_free_amount_active, shop_setup_delivery_free_amount, shop_setup_delivery_free_num_active, shop_setup_delivery_free_num, shop_setup_wholesale_price_article_id 
	FROM $db_shop_setup 
	WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$project_session_id = $project."_session_id";
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		$where = "shop_basket_admin_id=".(integer)$_SESSION['loginid']."";
	} else {
		$where = "shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."'";
	}
	$res_pricelist = mysql_query("
	SELECT article_text 
	FROM $db_articles 
	WHERE article_id=".$ar_setup['shop_setup_wholesale_price_article_id']
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_pricelist = mysql_fetch_array($res_pricelist);
	$res_basket = mysql_query("
	SELECT shop_basket_products_id, shop_basket_quantity 
	FROM $db_shop_basket 
	WHERE ".$where." 
	ORDER BY shop_basket_date_added ASC"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_basket = mysql_num_rows($res_basket);
	if ($num_basket > 0){
		echo "	<div align=\"center\">\n";
		echo "	<div id=\"edenshop_prev_order_border_top\"></div>\n";
		echo "	<div id=\"edenshop_prev_order_border_mid\">\n";
		echo "	<div id=\"edenshop_prev_order_title\"><h1>"._SHOP_BASKET."</h1></div><br>";
		echo "	<table id=\"edenshop_basket_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">";
		echo "	<form action=\"".$eden_cfg['url_edencms']."eden_shop_save.php?action=atb&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;page=".$pp."&amp;hits=".$hits."&amp;prod_id=".$ar['shop_product_id']."\" method=\"post\">";
		if ($_GET['b_action'] == "nep"){
			echo "<tr>";
			echo "	<td colspan=\"6\" style=\"color:#ff0000;\">"._SHOP_NEP."<br><br></td>";
			echo "</tr>";
		}
		echo "	<tr id=\"edenshop_basket_name\">\n";
		echo "		<td id=\"edenshop_basket_name_qty\">"._SHOP_QTY."</td>\n";
		echo "		<td id=\"edenshop_basket_name_title\">"._SHOP_TITLE."</td>\n";
		if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_basket_name_ex_vat\">"._SHOP_PRICE_EX_VAT."</td>";}
		echo "		<td id=\"edenshop_basket_name_inc_vat\" width=\"100\">"._SHOP_PRICE_INC_VAT."</td>\n";
		echo "		<td id=\"edenshop_basket_name_del\">&nbsp;</td>\n";
		echo "	</tr>";
		if ($_SESSION['u_status'] == "seller"){
			/* Nacteme vsechny slevove kategorie pro prodejce */
			$res_discount = mysql_query("
			SELECT shop_discount_category_id, shop_discount_category_name 
			FROM $db_shop_discount_category 
			WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
			while($ar_basket = mysql_fetch_array($res_basket)){
				$res_product = mysql_query("
				SELECT shop_product_discount_cat_seller_id 
				FROM $db_shop_product 
				WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." 
				GROUP BY shop_product_discount_cat_seller_id"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
				$res_dc_price = mysql_query("
				SELECT shop_discount_category_discount_price 
				FROM $db_shop_discount_category 
				WHERE shop_discount_category_parent_id=".$discount[$y][0]." AND ".$discount[$y][1]." BETWEEN shop_discount_category_discounted_from_amount AND shop_discount_category_discounted_to_amount"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_dc_price = mysql_fetch_array($res_dc_price);
				/* $discount_cat[ID discount kategorie][0 = mnozstvi vyrobku/1 = cena] */
				$discount_cat[$discount[$y][0]] = array($discount[$y][1],$ar_dc_price['shop_discount_category_discount_price']);
				$y++;
			}
		}
		$i_basket = 0;
		$quantity = 0;
		mysql_data_seek($res_basket, 0);
		while($ar_basket = mysql_fetch_array($res_basket)){
			$res_product = mysql_query("
			SELECT p.shop_product_id, p.shop_product_name, p.shop_product_selling_price, p.shop_product_qty_box_status, p.shop_product_product_code, p.shop_product_vat_class_id, p.shop_product_discount_cat_seller_id, 
			p.shop_product_discount_cat_cust_id, c.category_name, cstp.shop_clothes_style_parents_title, cc.shop_clothes_colors_title, pc.shop_product_clothes_size 
			FROM $db_shop_product AS p 
			JOIN $db_shop_product_clothes AS pc ON shop_product_clothes_product_id=".(integer)$ar_basket['shop_basket_products_id']." 
			JOIN $db_shop_clothes_style AS cst ON cst.shop_clothes_style_parent_id=pc.shop_product_clothes_style_id 
			JOIN $db_shop_clothes_style_parents AS cstp ON cstp.shop_clothes_style_parents_id=cst.shop_clothes_style_parent_id 
			JOIN $db_shop_clothes_colors AS cc ON cc.shop_clothes_colors_id=pc.shop_product_clothes_color_id 
			JOIN $db_category AS c ON c.category_id=p.shop_product_master_category 
			WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_product = mysql_fetch_array($res_product);
			
			$res_product_tax_rate = mysql_query("
			SELECT shop_tax_rates_rate 
			FROM $db_shop_tax_rates 
			WHERE shop_tax_rates_class_id=".(integer)$ar_product['shop_product_vat_class_id']." 
			ORDER BY shop_tax_rates_priority DESC"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_product_tax_rate = mysql_fetch_array($res_product_tax_rate);
			
			if ($_SESSION['u_status'] == "seller"){
				$res_discount = mysql_query("
				SELECT shop_discount_category_name 
				FROM $db_shop_discount_category 
				WHERE shop_discount_category_id=".$ar_product['shop_product_discount_cat_seller_id']
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_discount = mysql_fetch_array($res_discount);
			   	/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1] * $ar_basket['shop_basket_quantity'];
			} else {
				/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $ar_product['shop_product_selling_price'] * $ar_basket['shop_basket_quantity'];
			}
				/* Vypocet ceny bez DPH (vsechny prodejni ceny jsou zadavany s DPH)*/
				/* WRONG!!! $price_ex_vat = $price_inc_vat - (($price_inc_vat / 100) * $ar_product_tax_rate['shop_tax_rates_rate']); */
				$price_ex_vat = $price_inc_vat / ($ar_product_tax_rate['shop_tax_rates_rate']/100+1);
				/* Vypocet DPH */
				$price_vat = ($price_inc_vat - $price_ex_vat);
				echo "<tr>";
				echo "	<td id=\"edenshop_basket_qty\""; if ($_GET['b_nep'] == $ar_product['shop_product_id']){echo "style=\"background-color: #ff0000;\"";} echo ">"; if ($ar_product['shop_product_qty_box_status'] == 1){ echo "<input name=\"products[".$ar_product['shop_product_id']."]\" type=\"text\" maxlength=\"4\" size=\"3\" value=\"".$ar_basket['shop_basket_quantity']."\">"; } else { echo "<input name=\"products[".$ar_product['shop_product_id']."]\" type=\"hidden\" value=\"".$ar_basket['shop_basket_quantity']."\">".$ar_basket['shop_basket_quantity']."<a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"._SHOP_HINT_QUANT_BOX1."', this, event, '200px')\"> ? </a>"; } echo "</td>";
				echo "	<td id=\"edenshop_basket_title\"><strong><a href=\"index.php?action=shop&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;prod_id=".$ar_product['shop_product_id']."&amp;spec=1\" title=\"".$ar_product['shop_product_product_code']."\">".$ar_product['shop_product_name']."</a></strong><br>"; if ($_SESSION['u_status'] == "seller"){ echo $ar_discount['shop_discount_category_name'].", ";} echo $ar_product['shop_clothes_style_parents_title'].", ".$ar_product['shop_clothes_colors_title'].", ".$ar_product['shop_product_clothes_size']."</td>\n";
						 if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){echo "<td id=\"edenshop_basket_ex_vat\">".PriceFormat($price_ex_vat)."</td>";}
				echo "	<td id=\"edenshop_basket_inc_vat\" width=\"100\">".PriceFormat($price_inc_vat)."</td>\n";
				echo "	<td id=\"edenshop_basket_del\"><a href=\"".$eden_cfg['url_edencms']."eden_shop_save.php?action=rifb&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;pid=".$ar_product['shop_product_id']."&amp;project=".$project."\" target=\"_self\"><img src=\"edencms/shop/images/edenshop_del.gif\" width=\"18\" height=\"18\" alt=\""._SHOP_REM_ITEM."\"></a></td>\n";
				echo "</tr>";
			/* Mnozstvi kusu */
			$quantity = $quantity + $ar_basket['shop_basket_quantity'];
			
			/* Celkova cena s DPH (bez dopravy)*/
			$total_nett_price = $price_ex_vat + $total_nett_price;
			
			/* Soucet DPH */
			$total_vat = $price_vat + $total_vat;
			
			/* Mezisoucet */
			$subtotal = $price_inc_vat + $subtotal;
			
			$i_basket++;
		}
		$res_carr = mysql_query("
		SELECT shop_carriage_price 
		FROM $db_shop_carriage 
		WHERE shop_carriage_id=".(integer)$ar_setup['shop_setup_carriage_id']
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_carr = mysql_fetch_array($res_carr);
		
		/* Kdyz je pocet nebo castka vetsi nez aktivni zadane veliciny tak je doprava FREE */
		if ($ar_setup['shop_setup_delivery_free_amount_active'] == 1 && $total_nett_price > $ar_setup['shop_setup_delivery_free_amount']){
			$carriage_price = 0;
		} elseif ($ar_setup['shop_setup_delivery_free_num_active'] == 1 && $quantity > $ar_setup['shop_setup_delivery_free_num']){
			$carriage_price = 0;
		} else {
			$carriage_price = $ar_carr['shop_carriage_price'];
		}
		/* Koncova cena s DPH a dopravou */
		$total_total = $total_nett_price + $total_vat + $carriage_price;
		echo "	</table>\n";
		echo "<br clear=\"all\">";
		if ($_SESSION['u_status'] == "seller"){
		/* Zobrazeni rozpoctu na styly */
			echo "<table  id=\"edenshop_basket_ws_headline\">";
			echo "	<tr id=\"edenshop_basket_ws_name\">";
			echo "		<td id=\"edenshop_basket_ws_name_style\">Style</td>";
			echo "		<td id=\"edenshop_basket_ws_name_qty\">Quantity</td>";
			echo "		<td id=\"edenshop_basket_ws_name_price\">Price</td>";
			echo "		<td id=\"edenshop_basket_ws_name_total\">Total</td>";
			echo "	</tr>";
			$y = 0;
			while ($y < $pocet_disc){
				echo "<tr>";
				$res_dc_price = mysql_query("
				SELECT sdc.shop_discount_category_discount_price, sdcp.shop_discount_category_name 
				FROM $db_shop_discount_category AS sdc 
				JOIN $db_shop_discount_category AS sdcp ON sdcp.shop_discount_category_id=sdc.shop_discount_category_parent_id 
				WHERE sdc.shop_discount_category_parent_id=".$discount[$y][0]." AND ".$discount[$y][1]." BETWEEN sdc.shop_discount_category_discounted_from_amount AND sdc.shop_discount_category_discounted_to_amount"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_dc_price = mysql_fetch_array($res_dc_price);
				/* $discount_cat[ID discount kategorie][0 = mnozstvi vyrobku/1 = cena] */
				if ($discount[$y][1] > 0){
					echo "<td id=\"edenshop_basket_ws_style\">".$ar_dc_price['shop_discount_category_name']."</td>";
					echo "<td id=\"edenshop_basket_ws_qty\">".$discount[$y][1]."</td>";
					echo "<td id=\"edenshop_basket_ws_price\">".PriceFormat(TepRound($ar_dc_price['shop_discount_category_discount_price'],2))."</td>";
					echo "<td id=\"edenshop_basket_ws_total\">".PriceFormat(TepRound($discount[$y][1] * $ar_dc_price['shop_discount_category_discount_price'],2))."</td>";
				}
				echo "</tr>";
				$y++;
			}
		}
		echo "	<table id=\"edenshop_basket_headline\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
		echo "		<tr id=\"edenshop_basket_total\">\n";
		echo "			<td id=\"edenshop_basket_recalculate\"><br>\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"recalculate\">\n";
		echo "				<input type=\"hidden\" name=\"items\" value=\"".$items."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
		echo "				<input type=\"submit\" value=\""._SHOP_BUTTON_RECALCULATE."\" class=\"eden_button\" id=\"edenshop_button_recalculate\">\n";
		echo "				</form>\n";
		echo "			</td>\n";
		echo "			<td id=\"edenshop_basket_total_box\">\n";
		echo "				<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
							if ($ar_setup['shop_setup_show_vat_subtotal'] == 1 || $_SESSION['u_status'] == "seller"){
								echo "	<tr>\n";
								echo "		<td id=\"edenshop_basket_total\">"._SHOP_NETT_TOTAL."</td>\n";
								echo "		<td id=\"edenshop_basket_total_price\">".PriceFormat(TepRound($total_nett_price,2))."</td>\n";
								echo "	</tr>\n";
								echo "	<tr>\n";
								echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
								echo "	</tr>\n";
								echo "	<tr>\n";
								echo "		<td id=\"edenshop_basket_total\">"._SHOP_TOTAL_VAT."</td>\n";
								echo "		<td id=\"edenshop_basket_total_price\">".PriceFormat(TepRound($total_vat,2))."</td>\n";
								echo "	</tr>\n";
								echo "	<tr>\n";
								echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
								echo "	</tr>\n";
							} else {
								echo "	<tr>\n";
								echo "		<td id=\"edenshop_basket_total\">"._SHOP_SUBTOTAL."</td>\n";
								echo "		<td id=\"edenshop_basket_total_price\">".PriceFormat(TepRound($subtotal,2))."</td>\n";
								echo "	</tr>\n";
								echo "	<tr>\n";
								echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
								echo "	</tr>\n";
							}
		echo "					<tr>\n";
		echo "						<td id=\"edenshop_basket_total\">"._SHOP_CARRIAGE."</td>\n";
		echo "						<td id=\"edenshop_basket_total_price\">\n";
									if ($carriage_price == 0){
										echo "FREE";
									} else {
										echo PriceFormat(TepRound($carriage_price,2));
									}
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td id=\"edenshop_basket_total\">"._SHOP_TOTAL."</td>\n";
		echo "						<td id=\"edenshop_basket_total_total\">".PriceFormat(TepRound($total_total,2))."</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "			<td id=\"edenshop_basket_total_del\">&nbsp;</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td id=\"edenshop_basket_buttons\" colspan=\"3\">\n";
		echo "			<br>\n";
		echo "			<div id=\"edenshop_but_pos\"><a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_cont_shop_en.gif\" width=\"159\" height=\"25\" alt=\"\"></a>\n";
							if ($i_basket > 0){ echo "<a href=\"index.php?action="; if (($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin") && $shop_echo != "true"){echo "02";} else {echo "01";} echo "&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_checkout_en.gif\" width=\"128\" height=\"25\" alt=\"\"></a>"; } echo "</div></td>\n";
		echo "		</tr>\n";
		echo "	</table>";
		if ($_SESSION['u_status'] == "seller"){
			echo "	<div>".TreatText($ar_pricelist['article_text'],0)."</div>";
		}
		echo "	</div>\n";
		echo "	<div id=\"edenshop_prev_order_border_bottom\"></div>\n";
		echo "	</div>\n";
	} else {
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_prev_order_border_top\"></div>\n";
		echo "		<div id=\"edenshop_prev_order_border_mid\" style=\"\">\n";
		echo "			<div id=\"edenshop_prev_order_title\"><h1>"._SHOP_BASKET."</h1></div><br>\n";
		echo "			<span style=\"color:#FF0000; font-weight:bold;\">"._SHOP_BASKET_EMPTY."<br><br>\n";
		echo "			"._SHOP_BASKET_EMPTY_PLEASE."<br><br></span>\n";
		echo "			<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_cont_shop_en.gif\" width=\"159\" height=\"25\" alt=\"\"></a>\n";
		if ($_SESSION['u_status'] == "seller"){
			echo "	<div>".TreatText($ar_pricelist['article_text'],0)."</div>";
		}
		echo "		</div>\n";
		echo "		<div id=\"edenshop_prev_order_border_bottom\"></div>\n";
		echo "	</div>";
 	}
}
/***********************************************************************************************************
*
*			EDEN SHOP PROGRESS 01
*
***********************************************************************************************************/
function EdenShop01($eden_shop_action){
	
	global $db_shop_basket;
	global $project;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	/*
	$res_setup = mysql_query("SELECT * FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	*/
	
	$project_session_id = $project."_session_id";
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		$where = "shop_basket_admin_id=".(integer)$_SESSION['loginid']."";
	} else {
		$where = "shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."'";
	}
	$res = mysql_query("SELECT COUNT(*) FROM $db_shop_basket WHERE $where ORDER BY shop_basket_date_added ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res);
	if ($num[0] > 0){
		if ($action_shop == ""){$action_shop = "shop_user_edit";}
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "			<img src=\"images/edenshop_pbar_01_login_en.gif\" width=\"500\" height=\"100\" alt=\"\">\n";
		echo "		</div>\n";
		echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div>\n";
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>";
			if ($_SESSION['u_status'] == "" || $_SESSION['u_status'] == "vizitor"){
			if ($_GET['action_shop'] != "user_reg"){
				echo "	<div id=\"edenshop_progress_border_mid\" style=\"min-height:150px;\">\n";
				echo "	<div id=\"edenshop_progress_title\">"._SHOP_01_CUSTOMER_LOGIN."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"index.php?action="; if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){echo "02";} else {echo "01";} echo "&amp;action_shop=user_reg&amp;mode=reg&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._SHOP_REG."</a></div><br><br>\n";
				echo "	<div id=\"edenshop_progress_login_login\">\n";
				echo "		<form action=\"index.php?action=01&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"post\">\n";
				echo "		<input type=\"text\" name=\"login\" id=\"login_name\" value=\"username\" onFocus=\"if (this.value=='username') this.value='';\" onBlur=\"if (this.value=='') this.value='username';\"  onMouseDown=\"this.value=''\"><br>\n";
				echo "		<input type=\"password\" name=\"pass\" id=\"login_pass\" value=\"password\" onFocus=\"if (this.value=='password') this.value='';\" onBlur=\"if (this.value=='') this.value='password';\"><br>\n";
				echo "		<input type=\"hidden\" name=\"action\" value=\"login\">\n";
				echo "		<input type=\"hidden\" name=\"action_shop\" value=\"login\">\n";
				echo "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
				echo "		<input type=\"submit\" value=\""._SHOP_LOGIN."\" class=\"eden_button\">\n";
				echo "		</form>\n";
				echo "	</div>";
			} else {
				echo "	<div id=\"edenshop_progress_border_mid\" style=\"min-height:900px;\">\n";
				echo "	<div id=\"edenshop_progress_title\">"._SHOP_01_CUSTOMER_REG."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"index.php?action=01&amp;action_shop=login&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._SHOP_01_CUSTOMER_LOGIN."</a></div><br><br>\n";
				echo "	<div id=\"edenshop_progress_login_reg\">".UserEdit("reg")."</div><br>";
			}
		} else {
			//$res  = mysql_query("SELECT a.*, ac.* FROM $db_admin AS a, $db_admin_contact AS ac WHERE a.admin_id = ac.aid") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			//$ar = mysql_fetch_array($res);
			echo "	<div id=\"edenshop_progress_border_mid\">";
			echo "	<div id=\"edenshop_progress_title\">"._SHOP_01_CUSTOMER_LOGIN."</div><br><br>";
			echo "	<div align=\"center\">".UserEdit("shop_show")."<br></div>";
		}
		echo "	</div>\n";
		echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div><br><br>";
 		if ($_GET['action_shop'] != "user_reg"){
			echo "<div id=\"edenshop_but_pos\">";
			echo "<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_forg_some_en.gif\" width=\"175\" height=\"25\" alt=\"\"></a>";
 			if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
				if ($shop_echo == "true"){ echo "<a href=\"index.php?lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;action=01&amp;msg=reg_fill_all_cells&amp;action_shop=shop_user_edit\" target=\"_self\">"; } else { echo "<a href=\"index.php?action="; if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){echo "02";} else {echo "01";} echo "&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;action_shop=shop_user_edit\" target=\"_self\">"; } echo "<img src=\"images/edenshop_butt_checkout_en.gif\" width=\"128\" height=\"25\" alt=\"\"></a>";
 			}
			echo "</div>";
 		}
	} else {
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_prev_order_border_top\"></div>\n";
		echo "			<div id=\"edenshop_prev_order_border_mid\" style=\"\">\n";
		echo "				<div id=\"edenshop_prev_order_title\">"._SHOP_BASKET."</div><br>\n";
		echo "				<span style=\"color:#FF0000; font-weight:bold;\">"._SHOP_BASKET_EMPTY."<br><br>\n";
		echo "				"._SHOP_BASKET_EMPTY_PLEASE."<br><br></span>\n";
		echo "				<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_cont_shop_en.gif\" width=\"159\" height=\"25\" alt=\"\"></a>\n";
		echo "			</div>\n";
		echo "			<div id=\"edenshop_prev_order_border_bottom\"></div>\n";
		echo "	</div>";
 	}
}
/***********************************************************************************************************
*
*			EDEN SHOP PROGRESS 02
*
***********************************************************************************************************/
function EdenShop02($eden_shop_action){
	
	global $db_shop_setup,$db_admin,$db_shop_basket;
	global $eden_cfg;
	global $project;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_setup = mysql_query("
	SELECT shop_setup_02_terms, shop_setup_wholesale_terms 
	FROM $db_shop_setup 
	WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$project_session_id = $project."_session_id";
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		$where = "shop_basket_admin_id=".(integer)$_SESSION['loginid']."";
	}
	
	$res = mysql_query("SELECT COUNT(*) FROM $db_shop_basket WHERE $where") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res);
	if ($num[0] > 0){
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "		<img src=\"images/edenshop_pbar_02_terms_en.gif\" width=\"500\" height=\"100\" alt=\"\">\n";
		echo "		</div>\n";
		echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div>\n";
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "		<div id=\"edenshop_progress_title\">"._SHOP_02_TERMS."</div><br><br>\n";
		echo "			<form name=\"forma\" action=\"index.php?action=03&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=post>\n";
		echo "			<textarea cols=\"60\" rows=\"15\" name=\"terms\" id=\"edenshop_terms_txtarea\">"; if ($_SESSION['u_status'] == "seller"){echo $ar_setup['shop_setup_wholesale_terms'];} else {echo $ar_setup['shop_setup_02_terms'];} echo "</textarea><br>\n";
		echo "			<div align=\"left\"><input name=\"edenshop_terms_checkbox\" id=\"edenshop_terms_checkbox\" type=\"checkbox\" value=\"1\">"._SHOP_02_AGREE."<br><br></div>\n";
		echo "			</form>\n";
		echo "		</div>\n";
		echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div><br><br>\n";
		echo "	<div id=\"edenshop_but_pos\">\n";
		echo "		<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_forg_some_en.gif\" width=\"175\" height=\"25\" alt=\"\"></a>\n";
		echo "		<a href=\"javascript:go();\" hraef=\"#\"><img src=\"images/edenshop_butt_checkout_en.gif\" width=\"128\" height=\"25\" alt=\"\"></a>\n";
		echo "	</div>";
	} else {
		echo "<div align=\"center\">"._SHOP_BASKET_EMPTY."<br>";
		echo _SHOP_BASKET_EMPTY_PLEASE."<br>";
		echo "<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_cont_shop_en.gif\" width=\"159\" height=\"25\" alt=\"\"></a></div>";
	}
}
/***********************************************************************************************************
*
*			EDEN SHOP PROGRESS 03
*
***********************************************************************************************************/
function EdenShop03($eden_shop_action){
	
	global $db_shop_setup,$db_admin,$db_shop_basket,$db_admin_contact,$db_admin_contact_shop,$db_shop_carriage,$db_shop_product,$db_shop_product_clothes,$db_shop_clothes_style,$db_shop_clothes_style_parents,$db_shop_clothes_colors;
	global $db_shop_tax_rates,$db_category,$db_shop_payment_methods,$db_shop_sellers,$db_shop_discount_category;
	global $url_shop_payments;
	global $project;
	global $eden_cfg;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_setup = mysql_query("
	SELECT shop_setup_show_vat_subtotal, shop_setup_carriage_id, shop_setup_delivery_free_amount_active, shop_setup_delivery_free_amount, shop_setup_delivery_free_num_active, shop_setup_delivery_free_num 
	FROM $db_shop_setup 
	WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res = mysql_query("SELECT COUNT(*) FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res);
	if ($num[0] > 0){
		/* Kdyz je zakaznik user/admin */
		if ($_SESSION['u_status'] != "seller"){
			$res = mysql_query("SELECT a.admin_firstname,
			a.admin_name,
			a.admin_email,
			a.admin_title,
			ac.admin_contact_address_1,
			ac.admin_contact_address_2,
			ac.admin_contact_city,
			ac.admin_contact_postcode,
			ac.admin_contact_country,
			ac.admin_contact_companyname,
			ac.admin_contact_telefon,
			ac.admin_contact_mobil,
			acs.admin_contact_shop_use,
			acs.admin_contact_shop_firstname,
			acs.admin_contact_shop_name,
			acs.admin_contact_shop_companyname,
			acs.admin_contact_shop_title,
			acs.admin_contact_shop_address_1,
			acs.admin_contact_shop_address_2,
			acs.admin_contact_shop_city,
			acs.admin_contact_shop_country,
			acs.admin_contact_shop_postcode
			FROM $db_admin AS a, $db_admin_contact AS ac, $db_admin_contact_shop AS acs 
			WHERE a.admin_id=".(integer)$_SESSION['loginid']." AND ac.aid=".(integer)$_SESSION['loginid']." AND acs.aid=".(integer)$_SESSION['loginid']
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$company_name = "none"; // Zabrani zobrazeni vyzvy k doplneni nazvu firmy - zobrazeni firmy je dulezite jen pro prodejce
			$contact_firstname = $ar['admin_firstname'];
			$contact_surname = $ar['admin_name'];
			$contact_address_1 = $ar['admin_contact_address_1'];
			$contact_address_2 = $ar['admin_contact_address_2'];
			$contact_city = $ar['admin_contact_city'];
			$contact_postcode = $ar['admin_contact_postcode'];
			$contact_country = $ar['admin_contact_country'];
			$contact_email = $ar['admin_email'];
			$contact_telefon = $ar['admin_contact_telefon'];
			$contact_mobil = $ar['admin_contact_mobil'];
			$company_shop_name = "none";
			$contact_shop_title = $ar['admin_contact_shop_title'];
			$contact_shop_firstname  = $ar['admin_contact_shop_firstname'];
			$contact_shop_name = $ar['admin_contact_shop_name'];
			$contact_shop_address_1 = $ar['admin_contact_shop_address_1'];
			$contact_shop_address_2 = $ar['admin_contact_shop_address_2'];
			$contact_shop_city = $ar['admin_contact_shop_city'];
			$contact_shop_postcode = $ar['admin_contact_shop_postcode'];
			$contact_shop_country = $ar['admin_contact_shop_country'];
		} else {
		/* Kdyz je zakaznik prodejce */
			$res = mysql_query("SELECT a.admin_firstname,
			a.admin_name,
			a.admin_email,
			a.admin_title,
			ss.shop_seller_phone_1,
			ss.shop_seller_mobile,
			ss.shop_seller_delivery_country_id,
			ss.shop_seller_delivery_city,
			ss.shop_seller_delivery_address_1,
			ss.shop_seller_delivery_address_2,
			ss.shop_seller_delivery_postcode,
			ss.shop_seller_invoice_country_id,
			ss.shop_seller_invoice_city,
			ss.shop_seller_invoice_address_1,
			ss.shop_seller_invoice_address_2,
			ss.shop_seller_invoice_postcode 
			FROM $db_admin AS a, $db_shop_sellers AS ss 
			WHERE a.admin_id=".(integer)$_SESSION['loginid']." AND ss.shop_seller_admin_id=".(integer)$_SESSION['loginid']
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$company_name = $ar['shop_seller_company_name'];
			$contact_firstname = $ar['admin_firstname'];
			$contact_surname = $ar['admin_name'];
			$contact_address_1 = $ar['shop_seller_invoice_address_1'];
			$contact_address_2 = $ar['shop_seller_invoice_address_2'];
			$contact_city = $ar['shop_seller_invoice_city'];
			$contact_postcode = $ar['shop_seller_invoice_postcode'];
			$contact_country = $ar['shop_seller_invoice_country_id'];
			$contact_email = $ar['admin_email'];
			$contact_telefon = $ar['shop_seller_phone_1'];
			$contact_mobil = $ar['shop_seller_mobile'];
			$company_shop_name = $ar['shop_seller_company_name'];
			$contact_shop_title = $ar['admin_title'];
			$contact_shop_firstname  = $ar['admin_firstname'];
			$contact_shop_name = $ar['admin_name'];
			$contact_shop_address_1 = $ar['shop_seller_delivery_address_1'];
			$contact_shop_address_2 = $ar['shop_seller_delivery_address_2'];
			$contact_shop_city = $ar['shop_seller_delivery_city'];
			$contact_shop_postcode = $ar['shop_seller_delivery_postcode'];
			$contact_shop_country = $ar['shop_seller_delivery_country_id'];
		}
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "			<img src=\"images/edenshop_pbar_03_delivery_en.gif\" width=\"500\" height=\"100\" alt=\"\">\n";
		echo "		</div>\n";
		echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div>\n";
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "			<div id=\"edenshop_progress_title\">"._SHOP_03_CUSTOMER_AND_DELIVERY."</div><br><br>\n";
		echo "			<table id=\"edenshop_progress_03\">";
		// Pokud se edituje uzivatel, nezobrazi se cela tato cast
		if ($_GET['action_shop'] != "shop_check_del_address"){
			// Pokud chybi nejaka polozka z adresy uzivatele zobrazi se vyzva
			if ($company_name = "" || $contact_firstname == "" || $contact_surname == "" || $contact_address_1 == "" || $contact_city == "" || $contact_postcode == "" || $contact_email == ""){
				echo "	<tr>\n";
				echo "		<td colspan=\"3\">\n";
				echo "			<table id=\"edenshop_progress_address_error\">\n";
				echo "				<tr id=\"edenshop_progress_address_error\">\n";
				echo "					<td id=\"edenshop_progress_address_error\">"._SHOP_CUSTOMER_FILL_FIELD."</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td id=\"edenshop_progress_03_customer\" valign=\"top\">\n";
			echo "			<table id=\"edenshop_progress_address\">\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_main_title\" colspan=\"2\"><strong>"._SHOP_03_CUSTOMER_ADDRESS."</strong>  <a href=\"index.php?action=03&amp;action_shop=shop_check_del_address&amp;mode=edit_user&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><< "._SHOP_PROGRESS_CHANGE."</a></td>\n";
			echo "				</tr>\n";
								if ($_SESSION['u_status'] == "seller"){
									echo " 	<tr id=\"edenshop_progress_address\">\n";
									echo " 		<td id=\"edenshop_progress_address_sub_title\">"._SHOP_COMPANY_NAME."</td>\n";
									echo " 		<td id=\"edenshop_progress_address_address\">".$company_name."</td>\n";
									echo " 	</tr>\n";
								}
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; if($contact_firstname == "" || $contact_surname == ""){echo "<span class=\"edenshop_error\">*</span>"; } else { if ($ar['admin_title'] != ""){echo $ar['admin_title']." ";}  echo $contact_firstname." ".$contact_surname;} echo "</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; 
										if ($contact_address_1 == ""){
											echo "<span class=\"edenshop_error\">*</span>"; 
										} else { 
											echo $contact_address_1."<br>\n";
									 		if ($contact_address_2 != ""){
									 			echo $contact_address_2."<br>";
									 		}
									 	}
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; if($contact_city == ""){echo "<span class=\"edenshop_error\">*</span>"; } else { echo $contact_city;} echo "</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; if ($contact_postcode == ""){echo "<span class=\"edenshop_error\">*</span>"; } else { echo $contact_postcode;} echo "</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; if ($contact_country == ""){echo "<span class=\"edenshop_error\">*</span>"; } else { echo ShowCountryName($contact_country);} echo "</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_EMAIL."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">"; if ($contact_email == ""){echo "<span class=\"edenshop_error\">*</span>"; } else { echo $contact_email;} echo "</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_TELEPHONE."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">".$contact_telefon."</td>\n";
			echo "				</tr>\n";
			echo "				<tr id=\"edenshop_progress_address\">\n";
			echo "					<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_MOBILE."</td>\n";
			echo "					<td id=\"edenshop_progress_address_address\">".$contact_mobil."</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "		<td id=\"edenshop_progress_03_delivery\" valign=\"top\">";
			if ($ar['admin_contact_shop_use'] == 0){
				echo "<a href=\"index.php?action=03&amp;action_shop=shop_check_del_address&amp;mode=edit_user&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._SHOP_PROGRESS_ADD_DELIVERY."</a>";
			} elseif ($ar['admin_contact_shop_firstname'] == "" && $ar['admin_contact_shop_name'] == ""){
				echo "<a href=\"index.php?action=03&amp;action_shop=shop_check_del_address&amp;mode=edit_user&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._SHOP_PROGRESS_ADD_DELIVERY."</a><br>";
				echo _ADMIN_INFO_CONTACT_SHOP_HELP;
			} else {
				echo "		<table id=\"edenshop_progress_address\">\n";
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_title\" colspan=\"2\"><strong>"._SHOP_03_DELIVERY_ADDRESS."</strong> <a href=\"index.php?action=03&amp;action_shop=shop_check_del_address&amp;mode=edit_user&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><< "._SHOP_PROGRESS_CHANGE."</a></td>\n";
				echo "			</tr>\n";
								if ($_SESSION['u_status'] == "seller"){
									echo "	<tr id=\"edenshop_progress_address\">\n";
									echo "		<td id=\"edenshop_progress_address_sub_title\">"._SHOP_COMPANY_NAME."</td>\n";
									echo "		<td id=\"edenshop_progress_address_address\">".$company_shop_name."</td>\n";
									echo "	</tr>\n";
								}
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
				echo "				<td id=\"edenshop_progress_address_address\">"; if ($ar['admin_title'] != ""){echo $ar['admin_title']." ";}  echo $contact_firstname." ".$contact_surname."</td>\n";
				echo "			</tr>\n";
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
				echo "				<td id=\"edenshop_progress_address_address\">".$contact_shop_address_1."<br>\n";
										if ($contact_shop_address_2 != ""){echo $contact_shop_address_2."<br>"; }
				echo "				</td>\n";
				echo "			</tr>\n";
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
				echo "				<td id=\"edenshop_progress_address_address\">".$contact_shop_city."</td>\n";
				echo "			</tr>\n";
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
				echo "				<td id=\"edenshop_progress_address_address\">".$contact_shop_postcode."</td>\n";
				echo "			</tr>\n";
				echo "			<tr id=\"edenshop_progress_address\">\n";
				echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
				echo "				<td id=\"edenshop_progress_address_address\">".ShowCountryName($contact_shop_country)."</td>\n";
				echo "			</tr>\n";
				echo "		</table>\n";
			}
			echo "		</td>\n";
			echo "	</tr>";
		}
		if ($_GET['action_shop'] == "shop_check_del_address"){
			echo "<tr>";
			echo "	<td>";
			echo "		<a href=\"index.php?action=03&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><< "._SHOP_PROGRESS_BACK."</a><br>";
 			echo UserEdit("shop_check_del_address")."<br>";
			echo "	</td>";
			echo "	</tr>";
		}
		echo "			</table>\n";
		echo "		</div>\n";
		echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "	</div>";
	// Pokud se edituje uzivatel, nezobrazi se cela tato cast
	if ($_GET['action_shop'] != "shop_check_del_address"){
		echo "	<div align=\"center\">\n";
		echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "		<div id=\"edenshop_progress_border_mid\">\n";
		echo "		<div id=\"edenshop_progress_title\">"._SHOP_03_BASKET_CONTENTS."</div><br><br>";
 		$res_basket = mysql_query("
		SELECT shop_basket_products_id, shop_basket_quantity 
		FROM $db_shop_basket 
		WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']." 
		ORDER BY shop_basket_date_added ASC"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_basket = mysql_num_rows($res_basket);
		echo "	<table id=\"edenshop_progress_basket_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
		echo "	<tr id=\"edenshop_progress_basket_name\">\n";
		echo "		<td id=\"edenshop_progress_basket_name_qty\">"._SHOP_QTY."</td>\n";
		echo "		<td id=\"edenshop_progress_basket_name_code\">"._SHOP_CODE."</td>\n";
		echo "		<td id=\"edenshop_progress_basket_name_title\">"._SHOP_TITLE."</td>\n";
					if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_progress_basket_name_ex_vat\">"._SHOP_PRICE_EX_VAT_S."</td>"; }
		echo "		<td id=\"edenshop_progress_basket_name_inc_vat\">"._SHOP_PRICE_INC_VAT_S."</td>\n";
		echo "	</tr>";
		if ($_SESSION['u_status'] == "seller"){
			/* Nacteme vsechny slevove kategorie pro prodejce */
			$res_discount = mysql_query("
			SELECT shop_discount_category_id, shop_discount_category_name 
			FROM $db_shop_discount_category 
			WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
			while($ar_basket = mysql_fetch_array($res_basket)){
				$res_product = mysql_query("
				SELECT shop_product_discount_cat_seller_id 
				FROM $db_shop_product 
				WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." 
				GROUP BY shop_product_discount_cat_seller_id"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
				$res_dc_price = mysql_query("
				SELECT shop_discount_category_discount_price 
				FROM $db_shop_discount_category 
				WHERE shop_discount_category_parent_id=".$discount[$y][0]." AND ".$discount[$y][1]." BETWEEN shop_discount_category_discounted_from_amount AND shop_discount_category_discounted_to_amount"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_dc_price = mysql_fetch_array($res_dc_price);
				/* $discount_cat[ID discount kategorie][0 = mnozstvi vyrobku/1 = cena] */
				$discount_cat[$discount[$y][0]] = array($discount[$y][1],$ar_dc_price['shop_discount_category_discount_price']);
				$y++;
			}
		}
		$i_basket = 0;
		mysql_data_seek($res_basket, 0);
		while($ar_basket = mysql_fetch_array($res_basket)){
			$res_product = mysql_query("
			SELECT p.shop_product_selling_price, p.shop_product_id, p.shop_product_product_code, p.shop_product_name, p.shop_product_vat_class_id, p.shop_product_discount_cat_seller_id, 
			p.shop_product_discount_cat_cust_id, c.category_name, cstp.shop_clothes_style_parents_title, cc.shop_clothes_colors_title, pc.shop_product_clothes_size 
			FROM $db_shop_product AS p 
			JOIN $db_shop_product_clothes AS pc ON shop_product_clothes_product_id=".(integer)$ar_basket['shop_basket_products_id']." 
			JOIN $db_shop_clothes_style AS cst ON cst.shop_clothes_style_parent_id=pc.shop_product_clothes_style_id 
			JOIN $db_shop_clothes_style_parents AS cstp ON cstp.shop_clothes_style_parents_id=cst.shop_clothes_style_parent_id 
			JOIN $db_shop_clothes_colors AS cc ON cc.shop_clothes_colors_id=pc.shop_product_clothes_color_id 
			JOIN $db_category AS c ON c.category_id=p.shop_product_master_category 
			WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
			$ar_product = mysql_fetch_array($res_product);
			$res_product_tax_rate = mysql_query("
			SELECT shop_tax_rates_rate 
			FROM $db_shop_tax_rates 
			WHERE shop_tax_rates_class_id=".(integer)$ar_product['shop_product_vat_class_id']." 
			ORDER BY shop_tax_rates_priority DESC"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_product_tax_rate = mysql_fetch_array($res_product_tax_rate);
			if ($_SESSION['u_status'] == "seller"){
			   	/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1] * $ar_basket['shop_basket_quantity'];
			} else {
				/* Cena za jednotku vynasobena mnozstvim */
				$price_inc_vat = $ar_product['shop_product_selling_price'] * $ar_basket['shop_basket_quantity'];
			}
			$price_ex_vat = $price_inc_vat / ($ar_product_tax_rate['shop_tax_rates_rate']/100+1);
			$price_vat = ($price_inc_vat - $price_ex_vat);
			echo "<tr id=\"edenshop_progress_basket\">\n";
			echo "	<td id=\"edenshop_progress_basket_qty\">".$ar_basket['shop_basket_quantity']."</td>\n";
			echo "	<td id=\"edenshop_progress_basket_code\"><a href=\"index.php?action=".strtolower($ar_product['category_name'])."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;prod_id=".$ar_product['shop_product_id']."&amp;spec=1\">".$ar_product['shop_product_product_code']."</a></td>\n";
			echo "	<td id=\"edenshop_progress_basket_title\"><strong>".$ar_product['shop_product_name']."</strong><br>".$ar_product['shop_clothes_style_parents_title'].", ".$ar_product['shop_clothes_colors_title'].", ".$ar_product['shop_product_clothes_size']."</td>\n";
					 if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_progress_basket_ex_vat\">".PriceFormat($price_ex_vat)."</td>";}
			echo "	<td id=\"edenshop_progress_basket_inc_vat\">".PriceFormat($price_inc_vat)."</td>\n";
			echo "</tr>";
			$quantity = $quantity + $ar_basket['shop_basket_quantity'];
			$total_nett_price = $price_ex_vat + $total_nett_price;
			$total_vat = $price_vat + $total_vat;
			$subtotal = $price_inc_vat + $subtotal;
			$i_basket++;
		}
		
		if ($_SESSION['u_status'] == "seller"){
			$res_carr = mysql_query("SELECT shop_carriage_price FROM $db_shop_carriage WHERE shop_carriage_wholesale=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_carr = mysql_fetch_array($res_carr);
			$carriage_price = $ar_carr['shop_carriage_price'];
		} else {
			$res_carr = mysql_query("SELECT shop_carriage_price FROM $db_shop_carriage WHERE shop_carriage_id=".(integer)$ar_setup['shop_setup_carriage_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_carr = mysql_fetch_array($res_carr);
			/* Kdyz je pocet nebo castka vetsi nez aktivni zadane veliciny tak je doprava FREE */
			if ($ar_setup['shop_setup_delivery_free_amount_active'] == 1 && $total_nett_price > $ar_setup['shop_setup_delivery_free_amount']){
				$carriage_price = 0;
			} elseif ($ar_setup['shop_setup_delivery_free_num_active'] == 1 && $quantity > $ar_setup['shop_setup_delivery_free_num']){
				$carriage_price = 0;
			} else {
				$carriage_price = $ar_carr['shop_carriage_price'];
			}
		}
		$total_total = $total_nett_price + $total_vat + $carriage_price;
			echo "				</table>\n";
			echo "				<table id=\"edenshop_progress_basket_headline\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
			echo "					<tr>\n";
			echo "						<td id=\"edenshop_progress_basket_recalculate\"><br></td>\n";
			echo "						<td id=\"edenshop_progress_basket_total\">\n";
			echo "							<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
												if ($ar_setup['shop_setup_show_vat_subtotal'] == 1 || $_SESSION['u_status'] == "seller"){
													echo " 	<tr>\n";
													echo " 		<td id=\"edenshop_progress_basket_total\">"._SHOP_NETT_TOTAL."</td>\n";
													echo "		<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($total_nett_price,2))."</td>\n";
													echo "	</tr>\n";
													echo "	<tr>\n";
													echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
													echo "	</tr>\n";
													echo "	<tr>\n";
													echo "		<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL_VAT."</td>\n";
													echo "		<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($total_vat,2))."</td>\n";
													echo "	</tr>\n";
													echo "	<tr>\n";
													echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
													echo "	</tr>\n";
												} else {
													echo "	<tr>\n";
													echo "		<td id=\"edenshop_progress_basket_total\">"._SHOP_SUBTOTAL."</td>\n";
													echo "		<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($subtotal,2))."</td>\n";
													echo "	</tr>\n";
													echo "	<tr>\n";
													echo "		<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
													echo "	</tr>\n";
												}
			echo "								<tr>\n";
			echo "									<td id=\"edenshop_progress_basket_total\">"._SHOP_CARRIAGE."</td>\n";
			echo "									<td id=\"edenshop_progress_basket_total_price\">\n";
		   											if ($carriage_price == 0){
														echo "FREE";
													} else {
														echo PriceFormat(TepRound($carriage_price,2));
													}
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL."</td>\n";
			echo "									<td id=\"edenshop_progress_basket_total_total\">".PriceFormat(TepRound($total_total))."</td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</td>\n";
			echo "					</tr>\n";
			echo "				</table>\n";
			echo "		</div>\n";
			echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
			echo "	</div>\n";
			echo "	<div align=\"center\">\n";
			echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
			echo "		<div id=\"edenshop_progress_border_mid\">\n";
			echo "		<div id=\"edenshop_progress_title\">"._SHOP_03_DELIVERY_OPTIONS."</div><br><br>\n";
			echo "			<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
							// Zabezpeceni aby nebyl zobrazen formular pokud chybi nektery z kontaktnich udaju
						  	if ($contact_firstname != "" && $contact_surname != "" && $contact_address_1 != "" && $contact_city != "" && $contact_postcode != "" && $contact_email != ""){
								echo "<form action=\"".$eden_cfg['url_edencms']."eden_shop_save.php?lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"post\">";
							}
							if ($carriage_price == 0){
								if ($_SESSION['u_status'] == "seller"){
									$where = "shop_carriage_wholesale=1";
								} else {
									$where = "shop_carriage_price=0";
								}
								$res_carr = mysql_query("SELECT shop_carriage_id, shop_carriage_title, shop_carriage_description FROM $db_shop_carriage WHERE $where") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$ar_carr = mysql_fetch_array($res_carr);
								echo "	<tr>\n";
								echo "		<td id=\"edenshop_progress_delivery_radio\"><input type=\"hidden\" name=\"shop_carriage_id\" id=\"edenshop_progress_carriage\" value=\"".$ar_carr['shop_carriage_id']."\"></td>\n";
								echo "		<td id=\"edenshop_progress_delivery_title\">".$ar_carr['shop_carriage_description']." - <strong>FREE</strong></td>\n";
								echo "		<td id=\"edenshop_progress_delivery_price\">"._SHOP_PROGRESS_TOTAL_PRICE; $price = $total_nett_price + $total_vat; echo "<strong>".PriceFormat($price)."</strong>&nbsp;</td>\n";
								echo "	</tr>\n";
							} else {
								// V pripade ze Delivery adresa neni vyplnena pouzije se jako delivery adresa adresa normalni
								if ($ar['admin_contact_shop_country'] == ""){$delivery_zone = RoyalMailWorldZone($ar['admin_contact_country']);} else {$delivery_zone = RoyalMailWorldZone($ar['admin_contact_shop_country']);}
								$res_carr = mysql_query("
								SELECT shop_carriage_id, shop_carriage_title, shop_carriage_price 
								FROM $db_shop_carriage 
								WHERE shop_carriage_category='".$delivery_zone."' 
								ORDER BY shop_carriage_price ASC, shop_carriage_category ASC"
								) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$i = 1;
								while($ar_carr = mysql_fetch_array($res_carr)){
									echo "	<tr>\n";
									echo "		<td id=\"edenshop_progress_delivery_radio\"><input type=\"radio\" name=\"shop_carriage_id\" id=\"edenshop_progress_carriage\" value=\"".$ar_carr['shop_carriage_id']."\" "; if ($ar_carr['shop_carriage_id'] == $ar_setup['shop_setup_carriage_id']){ echo 'checked=\"checked\"';} echo "></td>\n";
									echo "		<td id=\"edenshop_progress_delivery_title\">".$ar_carr['shop_carriage_title']." - <strong>".PriceFormat($ar_carr['shop_carriage_price'])."</strong></td>\n";
									echo "		<td id=\"edenshop_progress_delivery_price\">"._SHOP_PROGRESS_TOTAL_PRICE; $price = $total_nett_price + $total_vat + $ar_carr['shop_carriage_price']; echo "<strong>".PriceFormat($price)."</strong>&nbsp;</td>\n";
									echo "	</tr>\n";
									$i++;
								}
							}
			echo "			</table>\n";
			echo "		</div>\n";
			echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
			echo "	</div>\n";
			echo "	<div align=\"center\">\n";
			echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
			echo "		<div id=\"edenshop_progress_border_mid\">\n";
			echo "			<div id=\"edenshop_progress_title\">"._SHOP_03_PAYMENT_METHOD."</div><br><br>\n";
			echo "			<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
							$res_pay = mysql_query("
							SELECT shop_payment_methods_id, shop_payment_methods_publish, shop_payment_methods_title, shop_payment_methods_descriptions, shop_payment_methods_picture 
							FROM $db_shop_payment_methods 
							WHERE shop_payment_methods_publish=1 
							ORDER BY shop_payment_methods_id ASC"
							) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num_pay = mysql_num_rows($res_pay);
							while($ar_pay = mysql_fetch_array($res_pay)){
								echo " 	<tr>\n";
								echo " 		<td align=\"left\" valign=\"top\" width=\"20\">"; if ($num_pay < 2){ echo "<input type=\"hidden\" name=\"shop_payment_methods_id\" value=\"".$ar_pay['shop_payment_methods_id']."\">"; } else { echo "<input type=\"radio\" name=\"shop_payment_methods_id\" id=\"edenshop_progress_carriage\" value=\"".$ar_pay['shop_payment_methods_id']."\" "; if ($ar_pay['shop_payment_methods_publish'] == 0){echo " disabled=\"disabled\" ";} echo ">"; } echo "</td>\n";
								echo " 		<td align=\"left\" valign=\"top\" width=\"700\" class=\"edenshop_progress_03_payment\">\n";
								if ($ar_pay['shop_payment_methods_publish'] == 0){ echo "<strong class=\"edenshop_disabled\">".$ar_pay['shop_payment_methods_title']."</strong>"; } else { echo "<strong>".$ar_pay['shop_payment_methods_title']."</strong>"; } echo "<br>\n";
								echo "	 	".$ar_pay['shop_payment_methods_descriptions']."<br>\n";
								echo "	 	<img src=\"".$url_shop_payments.$ar_pay['shop_payment_methods_picture']."\" alt=\"".$ar_pay['shop_payment_methods_title']."\"><br><br>\n";
								echo "	 	</td>\n";
								echo "	 </tr>\n";
							}
			echo "			</table>\n";
			echo "		</div>\n";
			echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
			echo "	</div><br>\n";
			echo "	<div id=\"edenshop_but_pos\">\n";
			// Zabezpeceni aby nebyl zobrazen formular pokud chybi nektery z kontaktnich udaju
				if ($contact_firstname != "" && $contact_surname != "" && $contact_address_1 != "" && $contact_city != "" && $contact_postcode != "" && $contact_email != ""){
					echo "		<input type=\"hidden\" name=\"action\" value=\"add_order\">\n";
					echo "		<input type=\"hidden\" name=\"oaid\" value=\"".$_SESSION['loginid']."\">\n";
					echo "		<input type=\"hidden\" name=\"orders_shop_ip\" value=\"".$eden_cfg['ip']."\">\n";
					echo "		<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
					echo "		<input type=\"Submit\" id=\"edenshop_progress_proceed\" value=\"\">\n";
					echo "	</form>\n";
				}
				echo "	</div>\n";
	   	}
	} else {
		echo "	<div align=\"center\">\n";
		echo _SHOP_BASKET_EMPTY."<br>\n";
		echo _SHOP_BASKET_EMPTY_PLEASE."<br>\n";
		echo "		<a href=\"index.php?action=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><img src=\"images/edenshop_butt_cont_shop_en.gif\" width=\"159\" height=\"25\" alt=\"\"></a>\n";
		echo "	</div>";
	}
}
/***********************************************************************************************************
*
*			EDEN SHOP PROGRESS 04
*
***********************************************************************************************************/
function EdenShop04($eden_shop_action){
	
	global $db_shop_setup,$db_shop_orders,$db_shop_orders_product,$db_category,$db_shop_product,$db_shop_basket,$db_shop_tax_rates,$db_shop_discount_category;
	global $db_country;
	global $eden_cfg;
	global $project;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_setup = mysql_query("SELECT shop_setup_paypal_business_account, shop_setup_currency, shop_setup_show_vat_subtotal, shop_setup_04_important FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res_order = mysql_query("SELECT * FROM $db_shop_orders WHERE shop_orders_id=".(integer)$_GET['id']." AND shop_orders_orders_status=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_order = mysql_fetch_array($res_order);
	$res_basket = mysql_query("SELECT shop_basket_products_id, shop_basket_quantity FROM $db_shop_basket WHERE shop_basket_admin_id=".(integer)$_SESSION['loginid']." ORDER BY shop_basket_date_added ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_basket = mysql_num_rows($res_basket);
	$res_country = mysql_query("SELECT country_shortname FROM $db_country WHERE country_name='".mysql_real_escape_string($ar_order['shop_orders_admin_country'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_country = mysql_fetch_array($res_country);
	echo "<form action=\"".$eden_cfg['url_edencms']."eden_shop_save.php?action=paypal&amp;project=".$project."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"post\">\n";
	echo "		<!-- PayPal Configuration -->\n";
	echo "		<input type=\"hidden\" name=\"return\" value=\"".$eden_cfg['url']."index.php?action=05&amp;state=ok&amp;id=".$ar_order['shop_orders_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">\n";
	echo "		<input type=\"hidden\" name=\"rm\" value=\"0\">\n";
	echo "		<input type=\"hidden\" name=\"notify_url\" value=\"".$eden_cfg['url_edencms']."eden_shop_save.php?action=complete&amp;project=".$project."&amp;oid=".$ar_order['shop_orders_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"cancel_return\" value=\"".$eden_cfg['url']."index.php?action=05&amp;state=cancel&amp;id=".$ar_order['shop_orders_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">\n";
	echo "		<input type=\"hidden\" name=\"business\" value=\"".$ar_setup['shop_setup_paypal_business_account']."\">\n";
	echo "		<input type=\"hidden\" name=\"currency_code\" value=\"".$ar_setup['shop_setup_currency']."\">\n";
				/* <input type=\"hidden\" name=\"cmd\" value=\"_cart\"> */
	echo "		<input type=\"hidden\" name=\"cmd\" value=\"_ext-enter\">\n";
	echo "		<input type=\"hidden\" name=\"redirect_cmd\" value=\"_cart\">\n";
	echo "		<input type=\"hidden\" name=\"upload\" value=\"1\">\n";
	
	echo "		<!-- Shipping and Misc Information -->\n";
				/* <input type=\"hidden\" name=\"shipping\" value=\"\">\n";
				<input type=\"hidden\" name=\"shipping2\" value=\"\">\n";
				<input type=\"hidden\" name=\"handling\" value=\"\">\n";
				<input type=\"hidden\" name=\"tax\" value=\"\">\n";
				<input type=\"hidden\" name=\"custom\" value=\"\"> */
	echo "		<input type=\"hidden\" name=\"invoice\" value=\"".$ar_order['shop_orders_invoice_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"handling_cart\" value=\"".number_format($ar_order['shop_orders_carriage_price'], 2, '.', ',')."\">\n";
	
	echo "		<!-- Customer Information -->\n";
	echo "		<input type=\"hidden\" name=\"email\" value=\"".$ar_order['shop_orders_admin_email_address']."\">\n";
	echo "		<input type=\"hidden\" name=\"first_name\" value=\"".$ar_order['shop_orders_admin_firstname']."\">\n";
	echo "		<input type=\"hidden\" name=\"last_name\" value=\"".$ar_order['shop_orders_admin_name']."\">\n";
	echo "		<input type=\"hidden\" name=\"address1\" value=\"".$ar_order['shop_orders_admin_address1']."\">\n";
	echo "		<input type=\"hidden\" name=\"address2\" value=\"".$ar_order['shop_orders_admin_address2']."\">\n";
	echo "		<input type=\"hidden\" name=\"city\" value=\"".$ar_order['shop_orders_admin_city']."\">\n";
	echo "		<input type=\"hidden\" name=\"zip\" value=\"".$ar_order['shop_orders_admin_postcode']."\">\n";
	echo "		<input type=\"hidden\" name=\"state\" value=\"".$ar_order['shop_orders_admin_state']."\">\n";
	echo "		<input type=\"hidden\" name=\"country\" value=\"".$ar_country['country_shortname']."\">\n";
	echo "		<input type=\"hidden\" name=\"night_phone_a\" value=\"".$ar_order['shop_orders_admin_telephone']."\">\n";
	echo "		<div align=\"center\">\n";
	echo "			<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "			<div id=\"edenshop_progress_border_mid\">\n";
	echo "				<img src=\"images/edenshop_pbar_04_confirm_en.gif\" width=\"500\" height=\"100\" alt=\"\">\n";
	echo "			</div>\n";
	echo "			<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "		</div>\n";
	echo "		<div align=\"center\">\n";
	echo "			<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "			<div id=\"edenshop_progress_border_mid\">\n";
	echo "				<div id=\"edenshop_progress_title\">"._SHOP_04_NOT_YET_CONFIRMED."</div><br><br>\n";
	echo "				<table id=\"edenshop_progress_04\">\n";
	if ($_GET['action_shop'] != "shop_check_del_address"){
		echo "<tr>\n";
		echo "	<td id=\"edenshop_progress_04_customer\" valign=\"top\">\n";
		echo "		<table id=\"edenshop_progress_address\">\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_main_title\" colspan=\"2\"><strong>"._SHOP_04_CUSTOMER_ADDRESS."</strong></td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">";	if ($ar_order['shop_orders_admin_title'] != ""){echo $ar_order['shop_orders_admin_title']." ";} echo $ar_order['shop_orders_admin_firstname']." ".$ar_order['shop_orders_admin_name']."</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_address1']."<br>\n";
			  					if ($ar_order['shop_orders_admin_address2'] != ""){echo $ar_order['shop_orders_admin_address2']."<br>";}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_city']."</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_postcode']."</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_country']."</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_EMAIL."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_email_address']."</td>\n";
		echo "			</tr>\n";
		echo "				<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_TELEPHONE."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_telephone']."</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "	<td id=\"edenshop_progress_04_customer\" valign=\"top\">\n";
		echo "		<table id=\"edenshop_progress_address\">\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_main_title\" colspan=\"2\"><strong>"._SHOP_04_DELIVERY_ADDRESS."</strong></td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">\n";
								if ($ar_order['admin_contact_shop_use'] == 0){
									if ($ar_order['shop_orders_admin_title'] != ""){echo $ar_order['shop_orders_admin_title']." ";} 
									echo $ar_order['shop_orders_admin_firstname']." ".$ar_order['shop_orders_admin_name'];
								} else {
									if ($ar_order['admin_contact_shop_title'] != ""){echo $ar_order['admin_contact_shop_title']." ";} 
									echo $ar_order['admin_contact_shop_firstname']." ".$ar['admin_contact_shop_name'];
								}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">\n";
								if ($ar_order['admin_contact_shop_use'] == 0){
									echo $ar_order['shop_orders_admin_address1']."<br>";
									if ($ar_order['shop_orders_admin_address2'] != ""){echo $ar_order['shop_orders_admin_address2']."<br>";}
								} else {
									echo $ar_order['admin_contact_shop_address_1']."<br>";
									if ($ar_order['admin_contact_shop_address_2'] != ""){echo $ar_order['admin_contact_shop_address_2']."<br>";}
								}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">\n";
								if ($ar_order['admin_contact_shop_use'] == 0){
									echo $ar_order['shop_orders_admin_city'];
								} else {
									echo $ar_order['admin_contact_shop_city'];
								}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">\n";
								if ($ar_order['admin_contact_shop_use'] == 0){
									echo $ar_order['shop_orders_admin_postcode'];
								} else {
									echo $ar_order['admin_contact_shop_postcode'];
								}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr id=\"edenshop_progress_address\">\n";
		echo "				<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
		echo "				<td id=\"edenshop_progress_address_address\">\n";
								if ($ar_order['admin_contact_shop_use'] == 0){
									echo $ar_order['shop_orders_admin_country'];
								} else {
									echo $ar_order['admin_contact_shop_country'];
								}
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
	if ($_GET['action_shop'] == "shop_check_del_address"){
		echo "<tr>\n";
		echo "	<td colspan=\"2\">\n";
		echo "		<a href=\"index.php?action=03&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><< "._SHOP_PROGRESS_BACK."</a><br>\n";
		echo 		UserEdit("shop_check_del_address")."<br>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
	echo "				</table>\n";
	echo "			</div>\n";
	echo "			<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "		</div>\n";
	echo "		<div align=\"center\">\n";
	echo "			<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "			<div id=\"edenshop_progress_border_mid\">\n";
	echo "				<div id=\"edenshop_progress_title\">"._SHOP_04_DETAILS."</div><br><br>\n";
	echo "				<table id=\"\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\"><strong>"._SHOP_CONFIRM_INV_NUM."</strong></td>\n";
	echo "					<td align=\"center\"><strong>"._SHOP_CONFIRM_ACC_NUM."</strong></td>\n";
	echo "					<td align=\"center\"><strong>"._SHOP_CONFIRM_INV_DATE."</strong></td>\n";
	echo "					<!-- <td align=\"center\"><strong>"._SHOP_CONFIRM_EST_DEL_DATE."</strong></td> -->\n";
	echo "					<td align=\"center\"><strong>"._SHOP_CONFIRM_IP."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\">".$ar_order['shop_orders_invoice_id']."</td>\n";
	echo "					<td align=\"center\">".$ar_order['shop_orders_admin_id']."</td>\n";
	echo "					<td align=\"center\">".date("d.m.Y")."</td>\n";
	echo "					<!-- <td align=\"center\">d</td> -->\n";
	echo "					<td align=\"center\">".$eden_cfg['ip']."</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "				</div>\n";
	echo "			<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "		</div>\n";
	echo "		<div align=\"center\">\n";
	echo "			<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "			<div id=\"edenshop_progress_border_mid\">\n";
	echo "			<div id=\"edenshop_progress_title\">"._SHOP_04_BASKET_CONTENTS."</div><br><br>\n";
	echo "				<table id=\"edenshop_progress_basket_headline\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
	echo "				<tr id=\"edenshop_progress_basket_name\">\n";
	echo "					<td id=\"edenshop_progress_basket_name_qty\">"._SHOP_QTY."</td>\n";
	echo "					<td id=\"edenshop_progress_basket_name_code\">"._SHOP_CODE."</td>\n";
	echo "					<td id=\"edenshop_progress_basket_name_title\">"._SHOP_TITLE."</td>\n";
							if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_progress_basket_name_ex_vat\">"._SHOP_PRICE_EX_VAT_S."</td>";}
	echo "					<td id=\"edenshop_progress_basket_name_inc_vat\">"._SHOP_PRICE_INC_VAT_S."</td>\n";
	echo "				</tr>";
	if ($_SESSION['u_status'] == "seller"){
		/* Nacteme vsechny slevove kategorie pro prodejce */
		$res_discount = mysql_query("
		SELECT shop_discount_category_id, shop_discount_category_name 
		FROM $db_shop_discount_category 
		WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
		while($ar_basket = mysql_fetch_array($res_basket)){
			$res_product = mysql_query("
			SELECT shop_product_discount_cat_seller_id 
			FROM $db_shop_product 
			WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." 
			GROUP BY shop_product_discount_cat_seller_id"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
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
			$res_dc_price = mysql_query("
			SELECT shop_discount_category_discount_price 
			FROM $db_shop_discount_category 
			WHERE shop_discount_category_parent_id=".$discount[$y][0]." AND ".$discount[$y][1]." BETWEEN shop_discount_category_discounted_from_amount AND shop_discount_category_discounted_to_amount"
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_dc_price = mysql_fetch_array($res_dc_price);
			/* $discount_cat[ID discount kategorie][0 = mnozstvi vyrobku/1 = cena] */
			$discount_cat[$discount[$y][0]] = array($discount[$y][1],$ar_dc_price['shop_discount_category_discount_price']);
			$y++;
		}
	}
	$i_basket = 0;
	$i = 1;
	mysql_data_seek($res_basket, 0);
	while($ar_basket = mysql_fetch_array($res_basket)){
		$res_product = mysql_query("
		SELECT p.shop_product_id, p.shop_product_name, p.shop_product_selling_price, p.shop_product_product_code, p.shop_product_vat_class_id, p.shop_product_discount_cat_seller_id, c.category_name 
		FROM $db_shop_product AS p, $db_category AS c 
		WHERE shop_product_id=".(integer)$ar_basket['shop_basket_products_id']." AND c.category_id = p.shop_product_master_category"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_product = mysql_fetch_array($res_product);
		
		$res_product_tax_rate = mysql_query("
		SELECT shop_tax_rates_rate 
		FROM $db_shop_tax_rates 
		WHERE shop_tax_rates_class_id=".(integer)$ar_product['shop_product_vat_class_id']." 
		ORDER BY shop_tax_rates_priority DESC"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_product_tax_rate = mysql_fetch_array($res_product_tax_rate);
		if ($_SESSION['u_status'] == "seller"){
		   	/* Cena za jednotku vynasobena mnozstvim */
			$price_inc_vat = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1] * $ar_basket['shop_basket_quantity'];
			$selling_price = $discount_cat[$ar_product['shop_product_discount_cat_seller_id']][1];
		} else {
			/* Cena za jednotku vynasobena mnozstvim */
			$price_inc_vat = $ar_product['shop_product_selling_price'] * $ar_basket['shop_basket_quantity'];
			$selling_price = $ar_product['shop_product_selling_price'];
		}
		$price_ex_vat = $price_inc_vat / ($ar_product_tax_rate['shop_tax_rates_rate']/100+1);
		$price_vat = ($price_inc_vat - $price_ex_vat);
		
		$basket_ex_vat = TepRound($price_ex_vat,2);
		$basket_inc_vat = TepRound($price_inc_vat,2);
		echo "	<tr id=\"edenshop_progress_basket\">\n";
		echo "		<td id=\"edenshop_progress_basket_qty\">".$ar_basket['shop_basket_quantity']."</td>\n";
		echo "		<td id=\"edenshop_progress_basket_code\"><a href=\"index.php?action=".strtolower($ar_product['category_name'])."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;prod_id=".$ar_product['shop_product_id']."&amp;spec=1\">".$ar_product['shop_product_product_code']."</a></td>\n";
		echo "		<td id=\"edenshop_progress_basket_title\">".$ar_product['shop_product_name']."</td>\n";
					if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){echo "<td id=\"edenshop_progress_basket_ex_vat\">".PriceFormat($basket_ex_vat)."</td>"; }
		echo "		<td id=\"edenshop_progress_basket_inc_vat\">".PriceFormat($basket_inc_vat)."</td>\n";
		echo "	</tr>";
		$total_nett_price = $price_ex_vat + $total_nett_price;
		$total_vat = $price_vat + $total_vat;
		$i_basket++;
		$subtotal = $price_inc_vat + $subtotal;
		echo "<input type=\"hidden\" name=\"item_name_".$i."\" value=\"".$ar_product['shop_product_name']."\">";
		echo "<input type=\"hidden\" name=\"item_number_".$i."\" value=\"".$i."\">";
		echo "<input type=\"hidden\" name=\"amount_".$i."\" value=\"".number_format($selling_price, 2, '.', ',')."\" "; /*$vat = ($ar2[shop_orders_shop_product_price] * ($ar2[shop_orders_shop_product_tax] / 100)); $amount = $ar2[shop_orders_shop_product_price] - $vat; echo  number_format($amount, 2, '.', ',');*/ echo ">";
		echo "<input type=\"hidden\" name=\"quantity_".$i."\" value=\"".$ar_basket['shop_basket_quantity']."\">";
		echo "<input type=\"hidden\" name=\"tax_".$i."\" value=\"".$ar_product_tax_rate['shop_tax_rates_rate']."\">";;
		$i++;
	}
	$total_total = $total_nett_price + $total_vat + $ar_order['shop_orders_carriage_price'];
	echo "		</table>\n";
	echo "		<table id=\"edenshop_progress_basket_headline\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
	echo "			<tr>\n";
	echo "				<td id=\"edenshop_progress_basket_recalculate\"><br></td>\n";
	echo "				<td id=\"edenshop_progress_basket_total\">\n";
	echo "					<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
								if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){
									echo "<tr>\n";
									echo "	<td id=\"edenshop_progress_basket_total\">"._SHOP_NETT_TOTAL."</td>\n";
									echo "	<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($total_nett_price))."</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL_VAT."</td>\n";
									echo "	<td id=\"edenshop_progress_basket_total_price\">"; $basket_total_vat = TepRound($total_vat,2); echo PriceFormat($basket_total_vat)."</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
									echo "</tr>\n";
								} else {
									echo "<tr>\n";
									echo "	<td id=\"edenshop_progress_basket_total\">"._SHOP_SUBTOTAL."</td>\n";
									echo "	<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($subtotal,2))."</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
									echo "</tr>\n";
								}
	echo "						<tr>\n";
	echo "							<td id=\"edenshop_progress_basket_total\">"._SHOP_CARRIAGE."</td>\n";
	echo "							<td id=\"edenshop_progress_basket_total_price\">\n";
										if ($ar_order['shop_orders_carriage_price'] == 0){
											echo "FREE";
										} else {
											echo PriceFormat(TepRound($ar_order['shop_orders_carriage_price'],2));
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL."</td>\n";
	echo "							<td id=\"edenshop_progress_basket_total_total\">".PriceFormat(TepRound($total_total))."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "</div>\n";
	echo "<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "</div>\n";
	echo "<div align=\"center\">\n";
	echo "	<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "	<div id=\"edenshop_progress_border_mid\">\n";
	echo "		<div id=\"edenshop_progress_title\">"._SHOP_04_CONFIRM_ORDER."</div><br><br>\n";
	echo "		<table id=\"edenshop_progress_04\" border=\"0\">\n";
	echo "			<tr>\n";
	echo "				<td id=\"edenshop_progress_04_info\">\n";
							if ($ar_setup['shop_setup_04_important'] != ""){ echo "<strong>"._SHOP_04_IMPORTANT_INFO."</strong><br>".$ar_setup['shop_setup_04_important']; }
	echo "				</td>\n";
	echo "				<td id=\"edenshop_progress_04_confirm\">\n";
	echo "						<input type=\"hidden\" name=\"orders_id\" value=\"".$ar_order['shop_orders_id']."\">\n";
	echo "						<input type=\"submit\" id=\"edenshop_progress_04_confirm\" value=\"\">\n";
	echo "					</form><br><br>\n";
	echo "					<form action=\"".$eden_cfg['url_edencms']."eden_shop_save.php?lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"cancel_order\">\n";
	echo "						<input type=\"hidden\" name=\"oid\" value=\"".$ar_order['shop_orders_id']."\">\n";
	echo "						<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
	echo "						<input type=\"submit\" id=\"edenshop_progress_04_cancel\" value=\"\">\n";
	echo "					</form>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";
	echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "</div>";
}
/***********************************************************************************************************
*
*			EDEN SHOP PROGRESS 05
*
***********************************************************************************************************/
function EdenShop05($eden_shop_action){
	
	global $db_shop_setup,$db_shop_orders,$db_shop_orders_product,$db_shop_product,$db_category;
	global $project;
	
	$res_setup = mysql_query("
	SELECT shop_setup_05_toc_ok, shop_setup_05_toc_no, shop_setup_show_vat_subtotal, shop_setup_05_additional 
	FROM $db_shop_setup 
	WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res_order = mysql_query("
	SELECT * 
	FROM $db_shop_orders 
	WHERE shop_orders_id=".(integer)$_GET['id']
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_order = mysql_fetch_array($res_order);
	echo "<div align=\"center\">\n";
	echo "	<div align=\"center\">\n";
	echo "		<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "		<div id=\"edenshop_progress_border_mid\">\n";
	echo "			<img src=\"images/edenshop_pbar_05_complete_en.gif\" width=\"500\" height=\"100\" alt=\"\">\n";
	echo "		</div>\n";
	echo "		<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "	</div>\n";
	echo "	<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "	<div id=\"edenshop_progress_border_mid\">\n";
	echo "		<div id=\"edenshop_progress_title\">"; if ($_GET['state'] == "ok"){echo _SHOP_05_ORDER_COMPLETE;} elseif ($_GET['state'] == "cancel"){echo _SHOP_05_ORDER_CANCELED;} echo "</div><br><br>\n";
	echo "		<table id=\"edenshop_progress_05\">\n";
	echo "			<tr>\n";
	echo "				<td id=\"edenshop_progress_05_toc\" colspan=\"2\">\n";
							if ($_GET['state'] == "ok"){echo $ar_setup['shop_setup_05_toc_ok'];} elseif ($_GET['state'] == "cancel"){echo $ar_setup['shop_setup_05_toc_no'];} echo "<br><br>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td id=\"edenshop_progress_05_customer\">\n";
	echo "					<table id=\"edenshop_progress_address\">\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_main_title\" colspan=\"2\"><strong>"._SHOP_04_CUSTOMER_ADDRESS."</strong></td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">"; if ($ar_order['shop_orders_admin_title'] != ""){echo $ar_order['shop_orders_admin_title']." ";}  echo $ar_order['shop_orders_admin_firstname']." ".$ar_order['shop_orders_admin_name']."</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_address1']."<br>";
										if ($ar_order['shop_orders_admin_address2'] != ""){echo $ar_order['shop_orders_admin_address2']."<br>";}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_city']."</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_postcode']."</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_country']."</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_EMAIL."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_email_address']."</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_TELEPHONE."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">".$ar_order['shop_orders_admin_telephone']."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "				<td id=\"edenshop_progress_05_delivery\">\n";
	echo "					<table id=\"edenshop_progress_address\">\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_main_title\" colspan=\"2\"><strong>"._SHOP_04_DELIVERY_ADDRESS."</strong></td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_NAME."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
										if ($ar_order['admin_contact_shop_use'] == 0){
											if ($ar_order['shop_orders_admin_title'] != ""){echo $ar_order['shop_orders_admin_title']." ";}  echo $ar_order['shop_orders_admin_firstname']." ".$ar_order['shop_orders_admin_name'];
										} else {
											if ($ar_order['admin_contact_shop_title'] != ""){echo $ar_order['admin_contact_shop_title']." ";} echo $ar_order['admin_contact_shop_firstname']." ".$ar['admin_contact_shop_name'];
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_ADDRESS."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
										if ($ar_order['admin_contact_shop_use'] == 0){
											echo $ar_order['shop_orders_admin_address1']."<br>";
											if ($ar_order['shop_orders_admin_address2'] != ""){echo $ar_order['shop_orders_admin_address2']."<br>";}
										} else {
											echo $ar_order['admin_contact_shop_address_1']."<br>";
											if ($ar_order['admin_contact_shop_address_2'] != ""){echo $ar_order['admin_contact_shop_address_2'].'<br>';}
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
										if ($ar_order['admin_contact_shop_use'] == 0){
											echo $ar_order['shop_orders_admin_city'];
										} else {
											echo $ar_order['admin_contact_shop_city'];
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_CITY."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
										if ($ar_order['admin_contact_shop_use'] == 0){
											echo $ar_order['shop_orders_admin_city'];
										} else {
											echo $ar_order['admin_contact_shop_city'];
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_POSTCODE."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
									  	if ($ar_order['admin_contact_shop_use'] == 0){
											echo $ar_order['shop_orders_admin_postcode'];
										} else {
											echo $ar_order['admin_contact_shop_postcode'];
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr id=\"edenshop_progress_address\">\n";
	echo "							<td id=\"edenshop_progress_address_sub_title\">"._SHOP_CUSTOMER_COUNTRY."</td>\n";
	echo "							<td id=\"edenshop_progress_address_address\">\n";
									  	if ($ar_order['admin_contact_shop_use'] == 0){
											echo $ar_order['shop_orders_admin_country'];
										} else {
											echo $ar_order['admin_contact_shop_country'];
										}
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";
	echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "</div>\n";
	echo "<div align=\"center\">\n";
	echo "	<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "	<div id=\"edenshop_progress_border_mid\">\n";
	echo "		<div id=\"edenshop_progress_title\">"._SHOP_05_DETAILS."</div><br><br>\n";
	echo "		<table id=\"\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
	echo "			<tr>\n";
	echo "				<td align=\"center\"><strong>"._SHOP_CONFIRM_INV_NUM."</strong></td>\n";
	echo "				<td align=\"center\"><strong>"._SHOP_CONFIRM_ACC_NUM."</strong></td>\n";
	echo "				<td align=\"center\"><strong>"._SHOP_CONFIRM_INV_DATE."</strong></td>\n";
   						 /* <td align=\"center\"><strong>  echo _SHOP_CONFIRM_EST_DEL_DATE;</strong></td> --> */
	echo "				<td align=\"center\"><strong>"._SHOP_CONFIRM_IP."</strong></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"center\">".$ar_order['shop_orders_invoice_id']."</td>\n";
	echo "				<td align=\"center\">".$ar_order['shop_orders_admin_id']."</td>\n";
	echo "				<td align=\"center\">".date("d.m.Y")."</td>\n";
						/* <!-- <td align=\"center\">d</td> -->  */
	echo "				<td align=\"center\">".$ar_order['shop_orders_ip_address']."</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";
	echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "</div>\n";
	echo "<div align=\"center\">\n";
	echo "	<div id=\"edenshop_progress_border_top\"></div>\n";
	echo "	<div id=\"edenshop_progress_border_mid\">\n";
	echo "		<div id=\"edenshop_progress_title\">"._SHOP_05_ORDER."</div><br><br>\n";
	echo "			<table id=\"edenshop_progress_basket_headline\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
	echo "				<tr id=\"edenshop_progress_basket_name\">\n";
	echo "					<td id=\"edenshop_progress_basket_name_qty\">"._SHOP_QTY."</td>\n";
	echo "					<td id=\"edenshop_progress_basket_name_code\">"._SHOP_CODE."</td>\n";
	echo "					<td id=\"edenshop_progress_basket_name_title\">"._SHOP_TITLE."</td>\n";
							if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_progress_basket_name_ex_vat\">"._SHOP_PRICE_EX_VAT_S."</td>"; }
	echo "					<td id=\"edenshop_progress_basket_name_inc_vat\">"._SHOP_PRICE_INC_VAT_S."</td>\n";
	echo "				</tr>";
	$res_order_products = mysql_query("
	SELECT shop_orders_shop_product_id, shop_orders_shop_product_name, shop_orders_shop_product_tax, shop_orders_shop_product_price, shop_orders_shop_product_quantity 
	FROM $db_shop_orders_product 
	WHERE shop_orders_orders_id=".(integer)$ar_order['shop_orders_id']." ORDER BY shop_orders_product_id ASC"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar_order_products = mysql_fetch_array($res_order_products)){
		$res_product = mysql_query("
		SELECT p.shop_product_id, p.shop_product_product_code, c.category_name 
		FROM $db_shop_product AS p, $db_category AS c 
		WHERE p.shop_product_id=".(integer)$ar_order_products['shop_orders_shop_product_id']." AND c.category_id = p.shop_product_master_category"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_product = mysql_fetch_array($res_product);
		
		$price_inc_vat = $ar_order_products['shop_orders_shop_product_price'] * $ar_order_products['shop_orders_shop_product_quantity'];
		$price_ex_vat = $price_inc_vat / ($ar_order_products['shop_orders_shop_product_tax']/100+1);
		$price_vat = ($price_inc_vat - $price_ex_vat);
		
		$basket_ex_vat = TepRound($price_ex_vat,2);
		$basket_inc_vat = TepRound($price_inc_vat,2);
		echo "<tr>\n";
		echo "	<td id=\"edenshop_progress_basket_qty\">".$ar_order_products['shop_orders_shop_product_quantity']."</td>\n";
		echo "	<td id=\"edenshop_progress_basket_code\"><a href=\"index.php?action=".strtolower($ar_product['category_name'])."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;prod_id=".$ar_product['shop_product_id']."&amp;spec=1\">".$ar_product['shop_product_product_code']."</a></td>\n";
		echo "	<td id=\"edenshop_progress_basket_title\">".$ar_order_products['shop_orders_shop_product_name']."</td>\n";
				if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){ echo "<td id=\"edenshop_progress_basket_ex_vat\">".PriceFormat($basket_ex_vat)."</td>"; }
		echo "	<td id=\"edenshop_progress_basket_inc_vat\">".PriceFormat($basket_inc_vat)."</td>\n";
		echo "</tr>\n";
		$total_nett_price = $price_ex_vat + $total_nett_price;
		$total_vat = $price_vat + $total_vat;
		$subtotal = $price_inc_vat + $subtotal;
	}
	$total_total = $total_nett_price + $total_vat + $ar_order['shop_orders_carriage_price'];
	echo "</table>\n";
	echo "<table id=\"edenshop_progress_basket_headline\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td id=\"edenshop_progress_basket_recalculate\"><br><span id=\"edenshop_progress_05_order_status\">\n";
					switch ($ar_order['shop_orders_orders_status']) {
					case "1":
						echo _SHOP_ORDER_STATUS_1;
					break;
					case "2":
						echo _SHOP_ORDER_STATUS_2;
					break;
					case "3":
						echo _SHOP_ORDER_STATUS_3;
					break;
					case "4":
						echo _SHOP_ORDER_STATUS_4;
					break;
					case "5":
						echo _SHOP_ORDER_STATUS_5;
					break;
					case "6":
						echo _SHOP_ORDER_STATUS_6;
					break;
					case "7":
						echo _SHOP_ORDER_STATUS_7;
					break;
				}
	echo "			</span>";
	echo "				</td>\n";
	echo "				<td id=\"edenshop_progress_basket_total\">\n";
	echo "					<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
								if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){
									echo "<tr>\n";
									echo " 	<td id=\"edenshop_progress_basket_total\">"._SHOP_NETT_TOTAL."</td>\n";
									echo " 	<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($total_nett_price))."</td>\n";
									echo "</tr>\n";
									echo "	<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL_VAT."</td>\n";
									echo "	<td id=\"edenshop_progress_basket_total_price\">".$basket_total_vat = TepRound($total_vat,2); PriceFormat($basket_total_vat)."</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
									echo "</tr>\n";
								} else {
									echo "<tr>\n";
									echo "	<td id=\"edenshop_progress_basket_total\">"._SHOP_SUBTOTAL."</td>\n";
									echo "	<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($subtotal,2))."</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td colspan=\"2\" id=\"edenshop_basket_dotted\"></td>\n";
									echo "</tr>\n";
								}
	echo "						<tr>\n";
	echo "							<td id=\"edenshop_progress_basket_total\">"._SHOP_CARRIAGE."</td>\n";
	echo "							<td id=\"edenshop_progress_basket_total_price\">".PriceFormat(TepRound($ar_order['shop_orders_carriage_price']))."</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td colspan=\"2\" id=\"edenshop_progress_basket_dotted\"></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td id=\"edenshop_progress_basket_total\">"._SHOP_TOTAL."</td>\n";
	echo "							<td id=\"edenshop_progress_basket_total_total\">".PriceFormat(TepRound($total_total))."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";
	echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
	echo "</div>\n";
	if ($_GET['state'] == "ok"){
		echo "<div align=\"center\">\n";
		echo "	<div id=\"edenshop_progress_border_top\"></div>\n";
		echo "	<div id=\"edenshop_progress_border_mid\">\n";
		echo "		<div id=\"edenshop_progress_title\">"._SHOP_05_ADDITIONAL_INFO."</div><br><br>\n";
		echo "		<table id=\"edenshop_progress_05\">\n";
		echo "			<tr>\n";
		echo "				<td id=\"edenshop_progress_05_ai\">\n";
		echo "					".$ar_setup['shop_setup_05_additional']."\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</div>\n";
		echo "	<div id=\"edenshop_progress_border_bottom\"></div>\n";
		echo "</div><br>";
	}
}
/***********************************************************************************************************
*
*			EDEN SHOP PREVIOUS ORDERS
*
***********************************************************************************************************/
function PrevOrders(){
	
	global $db_shop_setup,$db_shop_orders,$db_shop_orders_product,$db_category,$db_shop_product;
	global $eden_cfg;
	global $project;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_setup = mysql_query("
	SELECT shop_setup_show_vat_subtotal 
	FROM $db_shop_setup 
	WHERE shop_setup_lang='".mysql_real_escape_string($_GET['lang'])."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	echo "	<div align=\"center\">\n";
	echo "		<div id=\"edenshop_prev_order_border_top\"></div>\n";
	echo "		<div id=\"edenshop_prev_order_border_mid\">\n";
	echo "		<div id=\"edenshop_prev_order_title\">"._SHOP_PREV_ORDER."</div><br>\n";
	echo "			<table id=\"edenshop_prev_order_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
	echo "				<tr class=\"edenshop_prev_order_name\">\n";
	echo "					<td id=\"edenshop_prev_order_name_id\">"._SHOP_PREV_ORDER_ID."</td>\n";
	echo "					<td id=\"edenshop_prev_order_name_date\">"._SHOP_PREV_ORDER_DATE."</td>\n";
	echo "					<td id=\"edenshop_prev_order_name_status\">"._SHOP_PREV_ORDER_STATUS."</td>\n";
	echo "					<td id=\"edenshop_prev_order_name_price\">"._SHOP_PREV_ORDER_PRICE."</td>\n";
	echo "				</tr>";
				$res = mysql_query("
				SELECT shop_orders_id, shop_orders_orders_status, shop_orders_order_total, shop_orders_order_tax, shop_orders_date_ordered, shop_orders_carriage_price 
				FROM $db_shop_orders 
				WHERE shop_orders_admin_id=".(integer)$_SESSION['loginid']." 
				ORDER BY shop_orders_id DESC"
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar = mysql_fetch_array($res)){
					switch ($ar['shop_orders_orders_status']) {
						case "1":
							$prev_order_status = _SHOP_ORDER_STATUS_1;
						break;
						case "2":
							$prev_order_status = _SHOP_ORDER_STATUS_2;
						break;
						case "3":
							$prev_order_status = _SHOP_ORDER_STATUS_3;
						break;
						case "4":
							$prev_order_status = _SHOP_ORDER_STATUS_4;
						break;
						case "5":
							$prev_order_status = _SHOP_ORDER_STATUS_5;
						break;
						case "6":
							$prev_order_status = _SHOP_ORDER_STATUS_6;
						break;
						case "7":
							$prev_order_status = _SHOP_ORDER_STATUS_7;
						break;
					}
					$prev_order_price = $ar['shop_orders_order_total'] + $ar['shop_orders_order_tax'];
					echo "<tr"; if ($_GET['state'] == "open" && $_GET['poid'] == $ar['shop_orders_id']){echo 'id="edenshop_prev_order_mark"';} echo ">";
					echo "	<td id=\"edenshop_prev_order_id\"><a href=\"".$eden_cfg['url']."index.php?action=prev_orders&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;poid=".$ar['shop_orders_id']."&amp;state=open\">".$ar['shop_orders_id']."</a></td>\n";
					echo "	<td id=\"edenshop_prev_order_date\">".FormatDatetime($ar['shop_orders_date_ordered'],"d.m.Y")."</a></td>\n";
					echo "	<td id=\"edenshop_prev_order_status\">".$prev_order_status."</td>\n";
					echo "	<td id=\"edenshop_prev_order_price\">"; if ($ar['shop_orders_orders_status'] == "6" || $ar['shop_orders_orders_status'] == "7"){ echo "<span style=\"text-decoration: line-through;\">".PriceFormat($prev_order_price)."</span>";} else {echo PriceFormat($prev_order_price);} echo "</td>\n";
					echo "</tr>";
					if ($_GET['state'] == "open" && $_GET['poid'] == $ar['shop_orders_id']){
						echo "	<tr>\n";
						echo "		<td colspan=\"4\">\n";
						echo "			<table id=\"edenshop_prev_order_prod_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">\n";
						echo "				<tr class=\"edenshop_prev_order_prod_name\">\n";
						echo "					<td id=\"edenshop_prev_order_prod_name_qty\">"._SHOP_QTY."</td>\n";
						echo "					<td id=\"edenshop_prev_order_prod_name_code\">"._SHOP_CODE."</td>\n";
						echo "					<td id=\"edenshop_prev_order_prod_name_title\">"._SHOP_TITLE."</td>\n";
						echo "					<td id=\"edenshop_prev_order_prod_name_inc_vat\">"._SHOP_PRICE_INC_VAT_S."</td>\n";
						echo "				</tr>";
						$res_order_products = mysql_query("
						SELECT shop_orders_shop_product_id, shop_orders_shop_product_name, shop_orders_shop_product_tax, shop_orders_shop_product_price, shop_orders_shop_product_quantity 
						FROM $db_shop_orders_product 
						WHERE shop_orders_orders_id=".(integer)$ar['shop_orders_id']." 
						ORDER BY shop_orders_product_id ASC"
						) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar_order_products = mysql_fetch_array($res_order_products)){
							$res_product = mysql_query("
							SELECT p.shop_product_id, p.shop_product_product_code, c.category_name 
							FROM $db_shop_product AS p, $db_category AS c 
							WHERE p.shop_product_id=".(integer)$ar_order_products['shop_orders_shop_product_id']." AND c.category_id = p.shop_product_master_category"
							) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_product = mysql_fetch_array($res_product);
							$price_inc_vat = $ar_order_products['shop_orders_shop_product_price'] * $ar_order_products['shop_orders_shop_product_quantity'];
							$price_ex_vat = $price_inc_vat / ($ar_order_products['shop_orders_shop_product_tax']/100+1);
							$price_vat = ($price_inc_vat - $price_ex_vat);
							$basket_ex_vat = TepRound($price_ex_vat,2);
							$basket_inc_vat = TepRound($price_inc_vat,2);
							echo "	<tr>\n";
							echo "		<td id=\"edenshop_prev_order_prod_qty\">".$ar_order_products['shop_orders_shop_product_quantity']."</td>\n";
							echo "		<td id=\"edenshop_prev_order_prod_code\"><a href=\"index.php?action=".strtolower($ar_product['category_name'])."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;prod_id=".$ar_product['shop_product_id']."&amp;spec=1\">".$ar_product['shop_product_product_code']."</a></td>\n";
							echo "		<td id=\"edenshop_prev_order_prod_title\">".$ar_order_products['shop_orders_shop_product_name']."</td>\n";
							echo "		<td id=\"edenshop_prev_order_prod_inc_vat\">".PriceFormat($basket_inc_vat)."</td>\n";
							echo "	</tr>";
							$total_nett_price = $price_ex_vat + $total_nett_price;
							$total_vat = $price_vat + $total_vat;
							$subtotal = $price_inc_vat + $subtotal;
						}
						$total_total = $total_nett_price + $total_vat + $ar['shop_orders_carriage_price'];
						echo "	</table>\n";
						echo "	<table id=\"edenshop_prev_order_prod_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
						echo "	<tr>\n";
						echo "		<td id=\"edenshop_prev_order_prod_recalculate\"><div id=\"edenshop_prev_order_prod_close\"><a href=\"".$eden_cfg['url']."index.php?action=prev_orders&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;state=close\"><img src=\"images/sys_close.gif\" width=\"10\" height=\"10\" alt=\""._CMN_CLOSE."\" border=\"0\"></a></div><br><span id=\"edenshop_progress_05_order_status\">";
			 							echo _SHOP_ORDER_STATUS;
										switch ($ar['shop_orders_orders_status']) {
											case "1":
												echo _SHOP_ORDER_STATUS_1;
											break;
											case "2":
												echo _SHOP_ORDER_STATUS_2;
											break;
											case "3":
												echo _SHOP_ORDER_STATUS_3;
											break;
											case "4":
												echo _SHOP_ORDER_STATUS_4;
											break;
											case "5":
												echo _SHOP_ORDER_STATUS_5;
											break;
											case "6":
												echo _SHOP_ORDER_STATUS_6;
											break;
											case "7":
												echo _SHOP_ORDER_STATUS_7;
											break;
										}
			echo "						</span>\n";
			echo "					</td>\n";
			echo "					<td id=\"edenshop_prev_order_prod_total_box\">\n";
			echo "						<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
			if ($ar_setup['shop_setup_show_vat_subtotal'] == 1){
				echo "						<tr>\n";
				echo "							<td id=\"edenshop_prev_order_prod_total\">"._SHOP_NETT_TOTAL."</td>\n";
				echo "							<td id=\"edenshop_prev_order_prod_total_price\">".PriceFormat(TepRound($total_nett_price))."</td>\n";
				echo "						</tr>\n";
				echo "						<tr>\n";
				echo "							<td colspan=\"2\" id=\"edenshop_prev_order_prod_dotted\"></td>\n";
				echo "						</tr>\n";
				echo "						<tr>\n";
				echo "							<td id=\"edenshop_prev_order_prod_total\">"._SHOP_TOTAL_VAT."</td>\n";
				echo "							<td id=\"edenshop_prev_order_prod_total_price\">".$basket_total_vat = TepRound($total_vat,2); PriceFormat($basket_total_vat)."</td>\n";
				echo "						</tr>\n";
				echo "						<tr>\n";
		  		echo "							<td colspan=\"2\" id=\"edenshop_prev_order_prod_dotted\"></td>\n";
		   		echo "						</tr>\n";
			} else {
		   		echo "						<tr>\n";
		   		echo "							<td id=\"edenshop_prev_order_prod_total\">"._SHOP_SUBTOTAL."</td>\n";
		  		echo "							<td id=\"edenshop_prev_order_prod_total_price\">".PriceFormat(TepRound($subtotal,2))."</td>\n";
		   		echo "						</tr>\n";
		  		echo "						<tr>\n";
		  		echo "							<td colspan=\"2\" id=\"edenshop_prev_order_prod_dotted\"></td>\n";
		  		echo "						</tr>\n";
			}
			echo "							<tr>\n";
			echo "								<td id=\"edenshop_prev_order_prod_total\">"._SHOP_CARRIAGE."</td>\n";
			echo "								<td id=\"edenshop_prev_order_prod_total_price\">".PriceFormat(TepRound($ar['shop_orders_carriage_price']))."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td colspan=\"2\" id=\"edenshop_prev_order_prod_dotted\"></td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td id=\"edenshop_prev_order_prod_total\">"._SHOP_TOTAL."</td>\n";
			echo "								<td id=\"edenshop_prev_order_prod_total_total\">".PriceFormat(TepRound($total_total))."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td colspan=\"2\">&nbsp;</td>\n";
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>";
		}
		/* Nastaveni spravneho pocitani celkove sumy, do ktere se nezapocitavaji stornovane objednavky*/
		if ($ar['shop_orders_orders_status'] == "6" || $ar['shop_orders_orders_status'] == "7"){
		
		} else {
			$prev_order_total = $prev_order_total + $prev_order_price;
		}
	}
	echo "				</table>\n";
	echo "				<table id=\"edenshop_prev_order_total_headline\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "					<tr>\n";
	echo "						<td id=\"edenshop_prev_order_recalculate\"><br><br></td>\n";
	echo "						<td id=\"edenshop_prev_order_total\"><br><br>\n";
	echo "							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "								<tr>\n";
	echo "									<td id=\"edenshop_prev_order_total\">"._SHOP_TOTAL."</td>\n";
	echo "									<td id=\"edenshop_prev_order_total_total\">".PriceFormat(TepRound($prev_order_total))."</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "		</div>\n";
	echo "		<div id=\"edenshop_prev_order_border_bottom\"></div>\n";
	echo "	</div><br>";
	//$res_ord_prod = mysql_query("SELECT * FROM $db_shop_orders_product WHERE shop_orders_admin_id='$_SESSION[loginid]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//$ar_ord_prod = mysql_fetch_array($res_ord_prod);
}
/***********************************************************************************************************
*
*		RoyalMailWorldZone
*
*		Zjisteni ve ktere zone se nachazi dana zeme podle Royal Mail
*		vraci		-	UK (United Kingdom)
*					-	EU (Evropa)
*					-	WO (Rest of World)
*
*		$country	-	ID zeme
*
***********************************************************************************************************/
function RoyalMailWorldZone($country){
	
	if (
		$country == 223 /* UK */ ||
		$country == 8 /* Scotland */ ||
		$country == 9 /* England */ ||
		$country == 11 /* Wales */ ||
		$country == 12 /* Northern Ireland */ ||
		$country == 15 /* Jersey */ ||
		$country == 16 /* Isle of Man */){
		return "UK";
	} elseif (
		$country == 2 /* Albania */ ||
		$country == 5 /* Andorra */ ||
		$country == 11 /* Armenia */||
		$country == 14 /* Austria (EU) */ ||
		$country == 15 /* Azerbaijan */ ||
		/* $country == Azores (EU)  || */
		/* $country == Balearic Islands (EU) || */
		$country == 20 /* Belarus */ ||
		$country == 21 /* Belgium (EU) */ ||
		$country == 27 /* Bosnia Herzegovina */ ||
		$country == 33 /* Bulgaria (EU) */ ||
		/* $country == Canary Islands  || */
		/* $country == Corsica (EU) || */
		$country == 54 /* Croatia */ ||
		$country == 56 /* Cyprus (EU) */ ||
		$country == 57 /* Czech Republic (EU) */ ||
		$country == 58 /* Denmark (EU) */ ||
		$country == 68 /* Estonia (EU) */ ||
		$country == 71 /* Faroe Islands */ ||
		$country == 73 /* Finland (EU) */ ||
		$country == 74 /* France (EU) */ ||
		$country == 80 /* Georgia */ ||
		$country == 81 /* Germany (EU) */ ||
		$country == 83 /* Gibraltar (EU) */ ||
		$country == 84 /* Greece (EU) */ ||
		$country == 85 /* Greenland */ ||
		$country == 97 /* Hungary (EU) */ ||
		$country == 98 /* Iceland */ ||
		$country == 103 /* Irish Republic (EU) */ ||
		$country == 105 /* Italy (EU) */ ||
		$country == 109 /* Kazakhstan */ ||
		$country == 258 /* Kosovo */ ||
		$country == 115 /* Kyrgyzstan */ ||
		$country == 117 /* Latvia (EU) */ ||
		$country == 122 /* Liechtenstein */ ||
		$country == 123 /* Lithuania (EU) */ ||
		$country == 124 /* Luxembourg (EU) */ ||
		$country == 126 /* Macedonia */ ||
		/* $country == Madeira (EU) ||  */
		$country == 132 /* Malta (EU) */ ||
		$country == 140 /* Moldova */ ||
		$country == 141 /* Monaco (EU) */ ||
		$country == 259 /* Montenegro */ ||
		$country == 150 /* Netherlands (EU) */ ||
		$country == 160 /* Norway */ ||
		$country == 171 /* Poland (EU) */ ||
		$country == 172 /* Portugal (EU) */ ||
		$country == 176 /* Romania (EU) */ ||
		$country == 177 /* Russia */ ||
		$country == 184 /* San Marino */ ||
		$country == 260 /* Serbia */ ||
		$country == 191 /* Slovakia (EU) */ ||
		$country == 192 /* Slovenia (EU) */ ||
		$country == 197 /* Spain (EU) */ ||
		$country == 204 /* Sweden (EU) */ ||
		$country == 205 /* Switzerland */ ||
		$country == 208 /* Tajikistan */ ||
		$country == 216 /* Turkey */ ||
		$country == 217 /* Turkmenistan */ ||
		$country == 221 /* Ukraine */ ||
		$country == 227 /* Uzbekistan */ ||
		$country == 229 /* Vatican City State */){
		return "EU";
	} else {
		return "WO";
	}
}
/***********************************************************************************************************
*																											
*	Wholesale Order Form																					
*																											
***********************************************************************************************************/

function WholesaleStyleForm($scsp_id,$next_num){
	
	global $db_shop_clothes_style,$db_shop_clothes_size,$db_shop_clothes_colors;
	global $url_shop_clothes_style_parent,$design_list,$ar_design;
	
	/* Nacteme styl */
	$res_style = mysql_query("
	SELECT shop_clothes_style_id, shop_clothes_style_color_id, shop_clothes_style_sizes 
	FROM $db_shop_clothes_style 
	WHERE shop_clothes_style_parent_id=".(integer)$scsp_id
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	/* COLOURS */
	$i=0;
	while ($ar_style_color = mysql_fetch_array($res_style)){
		$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
		$i++;
	}
	if (count($style_color_list) == 0){$style_color_list = array();}
	$color_list = explode("#", $ar_style_parent['shop_clothes_style_parents_colors']);
	if (count($color_list) == 0){$color_list = array();}
	$res_color = mysql_query("
	SELECT shop_clothes_colors_id, shop_clothes_colors_prefix, shop_clothes_colors_title, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3 
	FROM $db_shop_clothes_colors 
	ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title ASC"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$style_num = $next_num;
	$style_num_next = $style_num + 1;
	$last_num = $style_num + 25;
	while ($style_num < $last_num){
		$result .= "<div id=\"StyleBlock[".$style_num."]\" style=\"margin:"; if ($style_num > 4){$result .= "0px 0px 10px 120px;";} else {$result .= "0px 0px 10px 10px;";} if ($style_num == 1 || $style_num == 26 || $style_num == 51 || $style_num == 76 || $style_num == 101 || $style_num == 126 || $style_num == 151 || $style_num == 176 || $style_num == 201){ /* zobrazi se v pohode */} else {$result .= "display:none;";} $result .= "\">\n";
		$result .= "	<input type=\"hidden\" name=\"product_data[style_id_".$style_num."]\" value=\"".$scsp_id."\">\n";
		$result .= "	<input type=\"hidden\" name=\"product_num\" value=\"".$style_num."\">\n";
		$result .= "	<select name=\"product_data[colour_".$style_num."]\" id=\"product_colour_id_[".$style_num."]\" size=\"1\" style=\"width:150px;\">\n";
			mysql_data_seek($res_color,0);
			while($ar_color = mysql_fetch_array($res_color)){
				$parent_color = $scsp_id."-".$ar_color['shop_clothes_colors_id'];
				if (in_array($parent_color, $design_list)){
					$result .= "<option value=\"".$ar_color['shop_clothes_colors_id']."\">".$ar_color['shop_clothes_colors_title']."</option>\n";
				}
			}
		$result .= "</select>\n";
		/* SIZES */
		mysql_data_seek($res_style,0);
		$ar_style_size = mysql_fetch_array($res_style);
		/* Do pole $sizes_list ulozime velikosti pro dny Styl */
		$sizes_list = explode("#", $ar_style_size['shop_clothes_style_sizes']);
		/* Odstranime posledni bunku z tohoto pole, protoze je prazdna */
		array_pop ($sizes_list);
		/* Pokud je pole $sizes_list prazdne musime z nej udelat pole abychom predesli chybove hlasce */
		if (count($sizes_list) == 0){$sizes_list = array();}
		/* Spocitame pocet zaznamu v poli $design_list */
		$sizes_num = count($sizes_list);
		$y = 0;
		$result .= "<select name=\"product_data[size_".$style_num."]\" id=\"product_size_id_[".$style_num."]\" size=\"1\" style=\"width:100px;\">\n";
			while ($y < $sizes_num){
				$res_sizes = mysql_query("
				SELECT shop_clothes_size_size 
				FROM $db_shop_clothes_size 
				WHERE shop_clothes_size_id=".(integer)$sizes_list[$y]
				) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_sizes = mysql_fetch_array($res_sizes)){
					/* Kdyz neni zvolena velikost, vybere se prvni z dostupnych */
					$result .= "<option value=\"".$ar_sizes['shop_clothes_size_size']."\">".$ar_sizes['shop_clothes_size_size']."</option>\n";
			   	}
				$y++;
			}
		$result .= "</select>\n";
		$result .= "<input name=\"product_data[quantity_".$style_num."]\" id=\"product_quantity_id_[".$style_num."]\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"\" onClick=\"sellerProductLineSelector(".$style_num_next.")\" onChange=\"sellerProductLineSelector(".$style_num_next.")\">";
		$result .= "</div>\n";
		
		$style_num++;
		$style_num_next++;
	}
	return $result;
}
/***********************************************************************************************************
*																											
*	Get Product																								
*																											
***********************************************************************************************************/
function GetProduct($design_id,$style_id,$color_id,$size){
	
	global $db_shop_product_clothes,$db_shop_product;
	
	$res_product_clothes = mysql_query("
	SELECT shop_product_clothes_product_id 
	FROM $db_shop_product_clothes 
	WHERE shop_product_clothes_design_id=".(integer)$design_id." AND shop_product_clothes_style_id=".(integer)$style_id." AND shop_product_clothes_color_id=".(integer)$color_id." AND shop_product_clothes_size='".mysql_real_escape_string($size)."'"
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_product_clothes = mysql_fetch_array($res_product_clothes);
	$res_product = mysql_query("
	SELECT shop_product_id, shop_product_product_code, shop_product_selling_price 
	FROM $db_shop_product 
	WHERE shop_product_id=".$ar_product_clothes['shop_product_clothes_product_id']
	) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_product = mysql_fetch_array($res_product);
	
	return $ar_product;
}
/***********************************************************************************************************
*																											
*	Add To Basket																							
*																											
***********************************************************************************************************/
function AddToBasket($product_id = 0,$product_quantity = 0,$product_price = ""){
	
	global $db_admin,$db_shop_basket;
	
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($product_id == 0 || $product_quantity == 0 || $product_price == ""){exit;}
	
	/* Updatujeme basket */
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		$res = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admin_id = $ar['admin_id'];
		
		$res2 = mysql_query("
		SELECT shop_basket_session_id, shop_basket_products_id 
		FROM $db_shop_basket 
		WHERE shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND shop_basket_admin_id=0"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		while($ar2 = mysql_fetch_array($res2)){
			mysql_query("UPDATE $db_shop_basket 
			SET shop_basket_admin_id=".(integer)$ar['admin_id']." 
			WHERE shop_basket_session_id='".mysql_real_escape_string($ar2['shop_basket_session_id'])."' AND shop_basket_products_id=".(integer)$ar2['shop_basket_products_id']
			) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	} else {
		$admin_id = 0;
	}
	/* Pokud se jedna o user/admin provedem jen jednou, pro prodejce musime provest v zavislosti na tom kolik vyrobku si vybral */
	setcookie($_POST['project'].'_product', '', time() - 604800);
	setcookie($_POST['project'].'_product', $product_id, time() + 604800);
	
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" || $_SESSION['u_status'] == "admin"){
		$where = "shop_basket_admin_id=".(integer)$_SESSION['loginid'];
	} else {
		$where = "shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."'";
	}
	$res = mysql_query("SELECT COUNT(*) FROM $db_shop_basket WHERE $where AND shop_basket_products_id=".(integer)$product_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res);
	
	/* Zkontrolujeme zda uz dany produk neni v kosiku */
	if ($num[0] != 0){
		/* Pokud je - pripocteme jen dany pocet dalsich produktu - predchazime tak duplicite zaznamu */
		mysql_query("UPDATE $db_shop_basket 
		SET shop_basket_quantity = shop_basket_quantity + ".$product_quantity.", shop_basket_date_added = NOW() 
		WHERE $where AND shop_basket_products_id=".(integer)$product_id
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		/* Pokud neni - zalozime novy znaznam */
		mysql_query("INSERT INTO $db_shop_basket 
		VALUES('".mysql_real_escape_string($_SESSION['sidd'])."','".(integer)$admin_id."','".(integer)$product_id."','".(integer)$product_quantity."','".(float)$product_price."',NOW())"
		) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}