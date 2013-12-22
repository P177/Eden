<?php
/***********************************************************************************************************
*																											
*		NASTAVENI SHOPU																						
*																											
***********************************************************************************************************/
function ShopSetupMenu(){

	$menu = '<img src="images/sys_manage.gif" height="18" width="18" border="0">
	<a href="modul_shop.php?action=&amp;project='.$_SESSION['project'].'">'._CMN_MAINMENU.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=shop_setup&amp;project='.$_SESSION['project'].'">'._SHOP_SETUP.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_tax_rates&amp;project='.$_SESSION['project'].'">'._SHOP_TAX_RATES.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_tax_zones&amp;project='.$_SESSION['project'].'">'._SHOP_TAX_ZONES.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_tax_class&amp;project='.$_SESSION['project'].'">'._SHOP_TAX_CLASS.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=shop_currency_add&amp;project='.$_SESSION['project'].'">'._SHOP_CURRENCY.'<br>
	<a href="modul_shop_setup.php?action=add_carriage&amp;project='.$_SESSION['project'].'">'._SHOP_CARRIAGE.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_carriage_category&amp;project='.$_SESSION['project'].'">'._SHOP_CARRIAGE_CATEGORY.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_payment&amp;project='.$_SESSION['project'].'">'._SHOP_SETUP_PAYMENT.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="modul_shop_setup.php?action=add_gender&amp;project='.$_SESSION['project'].'">'._SHOP_GENDER.'</a>';
	return $menu;
}
/***********************************************************************************************************
*                                                                                                        	
*		NASTAVENI SHOPU																						
*																											
*		!!! POZOR !!!																						
*		Pokud se vlozijakakoliv nova bunka do tabulky $db_shop_setup, je treba pridat potrebny pocet poli	
*		take do souboru sys_language.php kde se zaklada novy zaznam v pripade pridani jazyku				
*                                                                                                        	
***********************************************************************************************************/
function ShopSetup(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_tax_class,$db_shop_tax_rates,$db_shop_zones,$db_shop_setup,$db_shop_carriage,$db_language,$db_shop_currency;
	
	if ($_GET['action'] == "shop_setup_update" && $_POST['confirm'] == "TRUE"){
		$res = mysql_query("SELECT * FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_SESSION['web_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		$allowtags = "<ul>, <li>, <ol>, <br>, <a>"; 
		
		$shop_setup_margin_main = $_POST['shop_setup_margin_main'] / 100;
		$shop_setup_invoice_prefix = PrepareForDB($_POST['shop_setup_invoice_prefix'],1,$allowtags,1);
		$shop_setup_paypal_business_account = PrepareForDB($_POST['shop_setup_paypal_business_account'],1,$allowtags,1);
		$shop_setup_email_charset = PrepareForDB($_POST['shop_setup_email_charset'],1,$allowtags,1);
		$shop_setup_email_from = PrepareForDB($_POST['shop_setup_email_from'],1,$allowtags,1);
		$shop_setup_email_from_name = PrepareForDB($_POST['shop_setup_email_from_name'],1,$allowtags,1);
		$shop_setup_email_subject = PrepareForDB($_POST['shop_setup_email_subject'],1,$allowtags,1);
		if ($_POST['shop_setup_email_order_checkbox'] == 1){$shop_setup_email_order = PrepareForDB($_POST['shop_setup_email_order'],1,$allowtags,1);} else {$shop_setup_email_order = $ar['shop_setup_email_order'];}
		$shop_setup_email_order_subject = PrepareForDB($_POST['shop_setup_email_order_subject'],1,$allowtags,1);
		if ($_POST['shop_setup_email_order_status_1_checkbox'] == 1){$shop_setup_email_order_status_1 = PrepareForDB($_POST['shop_setup_email_order_status_1'],1,$allowtags,1);} else {$shop_setup_email_order_status_1 = $ar['shop_setup_email_order_status_1'];}
		if ($_POST['shop_setup_email_order_status_2_checkbox'] == 1){$shop_setup_email_order_status_2 = PrepareForDB($_POST['shop_setup_email_order_status_2'],1,$allowtags,1);} else {$shop_setup_email_order_status_2 = $ar['shop_setup_email_order_status_2'];}
		if ($_POST['shop_setup_email_order_status_3_checkbox'] == 1){$shop_setup_email_order_status_3 = PrepareForDB($_POST['shop_setup_email_order_status_3'],1,$allowtags,1);} else {$shop_setup_email_order_status_3 = $ar['shop_setup_email_order_status_3'];}
		if ($_POST['shop_setup_email_order_status_4_checkbox'] == 1){$shop_setup_email_order_status_4 = PrepareForDB($_POST['shop_setup_email_order_status_4'],1,$allowtags,1);} else {$shop_setup_email_order_status_4 = $ar['shop_setup_email_order_status_4'];}
		if ($_POST['shop_setup_email_order_cancel_before_payment_checkbox'] == 1){$shop_setup_email_order_cancel_before_payment = PrepareForDB($_POST['shop_setup_email_order_cancel_before_payment'],1,$allowtags,1);} else {$shop_setup_email_order_cancel_before_payment = $ar['shop_setup_email_order_cancel_before_payment'];}
		if ($_POST['shop_setup_email_order_cancel_after_payment_checkbox'] == 1){$shop_setup_email_order_cancel_after_payment = PrepareForDB($_POST['shop_setup_email_order_cancel_after_payment'],1,$allowtags,1);} else {$shop_setup_email_order_cancel_after_payment = $ar['shop_setup_email_order_cancel_before_payment'];}
		if ($_POST['shop_setup_email_confirm_of_payment_checkbox'] == 1){$shop_setup_email_confirm_of_payment = PrepareForDB($_POST['shop_setup_email_confirm_of_payment'],1,$allowtags,1);} else {$shop_setup_email_confirm_of_payment = $ar['shop_setup_email_confirm_of_payment'];}
		if ($_POST['shop_setup_02_terms_checkbox'] == 1){$shop_setup_02_terms = PrepareForDB($_POST['shop_setup_02_terms'],1,$allowtags,1);} else {$shop_setup_02_terms = $ar['shop_setup_02_terms'];}
		if ($_POST['shop_setup_04_important_checkbox'] == 1){$shop_setup_04_important = PrepareForDB($_POST['shop_setup_04_important'],1,$allowtags,1);} else {$shop_setup_04_important = $ar['shop_setup_04_important'];}
		if ($_POST['shop_setup_05_toc_ok_checkbox'] == 1){$shop_setup_05_toc_ok = PrepareForDB($_POST['shop_setup_05_toc_ok'],1,$allowtags,1);} else {$shop_setup_05_toc_ok = $ar['shop_setup_05_toc_ok'];}
		if ($_POST['shop_setup_05_toc_no_checkbox'] == 1){$shop_setup_05_toc_no = PrepareForDB($_POST['shop_setup_05_toc_no'],1,$allowtags,1);} else {$shop_setup_05_toc_no = $ar['shop_setup_05_toc_no'];}
		if ($_POST['shop_setup_05_additional_checkbox'] == 1){$shop_setup_05_additional = PrepareForDB($_POST['shop_setup_05_additional'],1,$allowtags,1);} else {$shop_setup_05_additional = $ar['shop_setup_05_additional'];}
		if ($_POST['shop_setup_wholesale_email_act_checkbox'] == 1){$shop_setup_wholesale_email_act = PrepareForDB($_POST['shop_setup_wholesale_email_act'],1,$allowtags,1);} else {$shop_setup_wholesale_email_act = $ar['shop_setup_wholesale_email_act'];}
		if ($_POST['shop_setup_wholesale_terms_checkbox'] == 1){$shop_setup_wholesale_terms = PrepareForDB($_POST['shop_setup_wholesale_terms'],1,$allowtags,1);} else {$shop_setup_wholesale_terms = $ar['shop_setup_wholesale_terms'];}
		
		$allowtags = ""; 
		$shop_setup_paypal_test_api_username = strip_tags($_POST['shop_setup_paypal_test_api_username'],$allowtags);
		$shop_setup_paypal_test_api_password = strip_tags($_POST['shop_setup_paypal_test_api_password'],$allowtags);
		$shop_setup_paypal_test_api_signature = strip_tags($_POST['shop_setup_paypal_test_api_signature'],$allowtags);
		$shop_setup_paypal_test_api_endpoint = strip_tags($_POST['shop_setup_paypal_test_api_endpoint'],$allowtags);
		$shop_setup_paypal_test_api_url = strip_tags($_POST['shop_setup_paypal_test_api_url'],$allowtags);
		mysql_query("UPDATE $db_shop_setup SET 
			shop_setup_lang='".mysql_real_escape_string($_POST['shop_setup_lang'])."', 
			shop_setup_currency='".mysql_real_escape_string($_POST['shop_setup_currency'])."', 
			shop_setup_carriage_id=".(float)$_POST['shop_setup_carriage'].", 
			shop_setup_margin_main=".(float)$shop_setup_margin_main.",
			shop_setup_invoice_prefix='".$shop_setup_invoice_prefix."',
			shop_setup_paypal_business_account='".$shop_setup_paypal_business_account."',
			shop_setup_email_charset='".$shop_setup_email_charset."',
			shop_setup_email_from='".$shop_setup_email_from."',
			shop_setup_email_from_name='".$shop_setup_email_from_name."',
			shop_setup_email_subject='".$shop_setup_email_subject."',
			shop_setup_email_order='".$shop_setup_email_order."',
			shop_setup_email_order_subject='".$shop_setup_email_order_subject."',
			shop_setup_email_order_status_1='".$shop_setup_email_order_status_1."',
			shop_setup_email_order_status_2='".$shop_setup_email_order_status_2."',
			shop_setup_email_order_status_3='".$shop_setup_email_order_status_3."',
			shop_setup_email_order_status_4='".$shop_setup_email_order_status_4."',
			shop_setup_email_order_cancel_before_payment='".$shop_setup_email_order_cancel_before_payment."',
			shop_setup_email_order_cancel_after_payment='".$shop_setup_email_order_cancel_after_payment."',
			shop_setup_email_confirm_of_payment='".$shop_setup_email_confirm_of_payment."',
			shop_setup_02_terms='".$shop_setup_02_terms."',
			shop_setup_04_important='".$shop_setup_04_important."',
			shop_setup_05_toc_ok='".$shop_setup_05_toc_ok."',
			shop_setup_05_toc_no='".$shop_setup_05_toc_no."',
			shop_setup_05_additional='".$shop_setup_05_additional."',
			shop_setup_show_zero_quantity=".(float)$_POST['shop_setup_show_zero_quantity'].",
			shop_setup_show_vat_subtotal=".(float)$_POST['shop_setup_show_vat_subtotal'].",
			shop_setup_show_prev_orders=".(float)$_POST['shop_setup_show_prev_orders'].",
			shop_setup_paypal_test_api_username='".mysql_real_escape_string($shop_setup_paypal_test_api_username)."',
			shop_setup_paypal_test_api_password='".mysql_real_escape_string($shop_setup_paypal_test_api_password)."',
			shop_setup_paypal_test_api_signature='".mysql_real_escape_string($shop_setup_paypal_test_api_signature)."',
			shop_setup_paypal_test_api_endpoint='".mysql_real_escape_string($shop_setup_paypal_test_api_endpoint)."',
			shop_setup_paypal_test_api_url='".mysql_real_escape_string($shop_setup_paypal_test_api_url)."',
			shop_setup_clothes_img_1_width=".(integer)$_POST['shop_setup_clothes_img_1_width'].",
			shop_setup_clothes_img_1_height=".(integer)$_POST['shop_setup_clothes_img_1_height'].",
			shop_setup_clothes_img_2_width=".(integer)$_POST['shop_setup_clothes_img_2_width'].",
			shop_setup_clothes_img_2_height=".(integer)$_POST['shop_setup_clothes_img_2_height'].",
			shop_setup_clothes_img_3_width=".(integer)$_POST['shop_setup_clothes_img_3_width'].",
			shop_setup_clothes_img_3_height=".(integer)$_POST['shop_setup_clothes_img_3_height'].",
			shop_setup_clothes_img_5_width=".(integer)$_POST['shop_setup_clothes_img_5_width'].",
			shop_setup_clothes_img_5_height=".(integer)$_POST['shop_setup_clothes_img_5_height'].",
			shop_setup_delivery_free_amount_active=".(integer)$_POST['shop_setup_delivery_free_amount_active'].",
			shop_setup_delivery_free_amount=".(float)$_POST['shop_setup_delivery_free_amount'].",
			shop_setup_delivery_free_num_active=".(integer)$_POST['shop_setup_delivery_free_num_active'].",
			shop_setup_delivery_free_num=".(float)$_POST['shop_setup_delivery_free_num'].",
			shop_setup_wholesale_email_from='".mysql_real_escape_string($_POST['shop_setup_wholesale_email_from'])."',
			shop_setup_wholesale_email_from_name='".mysql_real_escape_string($_POST['shop_setup_wholesale_email_from_name'])."',
			shop_setup_wholesale_email_subject='".mysql_real_escape_string($_POST['shop_setup_wholesale_email_subject'])."',
			shop_setup_wholesale_email_subject_act='".mysql_real_escape_string($_POST['shop_setup_wholesale_email_subject_act'])."',
			shop_setup_wholesale_email_act='".$shop_setup_wholesale_email_act."',
			shop_setup_wholesale_terms='".$shop_setup_wholesale_terms."',
			shop_setup_wholesale_price_article_id=".(integer)$_POST['shop_setup_wholesale_price_article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
	}
	
	$res = mysql_query("SELECT * FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_SESSION['web_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($ar = mysql_fetch_array($res)){
		/* Tabulka existuje */
	} else {
		mysql_query("INSERT INTO $db_shop_setup VALUES( 
			'".mysql_real_escape_string($_SESSION['web_lang'])."', 
			'',
			'',
			'',
			'',
			'',
			'utf-8',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'0',
			'0',
			'0',
			'',
			'',
			'',
			'',
			'',
			'220',
			'230',
			'220',
			'230',
			'300',
			'400',
			'600',
			'600',
			'0',
			'0.00',
			'0',
			'0',
			'',
			'',
			'',
			'',
			'',
			'')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$res = mysql_query("SELECT * FROM $db_shop_setup WHERE shop_setup_lang='".mysql_real_escape_string($_SESSION['web_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_SETUP."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<script src=\"./js/tab-contentdivider.js\" type=\"text/javascript\"></script>\n";
	echo "<div id=\"cyclelinks\"></div>\n";
	echo "<p class=\"dropcontent\">\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<form action=\"modul_shop_setup.php?action=shop_setup_update\" method=\"post\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_1\" class=\"nadpis_sekce\">"._SHOP_SETUP_SHOP."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_LANGUAGE."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\">";
				$res_lng = mysql_query("SELECT * FROM $db_language WHERE language_code='".mysql_real_escape_string($_SESSION['web_lang'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_lng = mysql_fetch_array($res_lng);
				echo $ar_lng['language_name'];
				echo '<input type="hidden" name="shop_setup_lang"  value="'.$ar_lng['language_code'].'">';
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"left\" valign=\"top\">\n";
		echo "	<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_PAYPAL_BUSINESS."</strong><br></td>\n";
		echo "	<td width=\"500\" align=\"left\"><input name=\"shop_setup_paypal_business_account\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_paypal_business_account']."\"></td>\n";
		echo "</tr>\n";
		echo "<tr align=\"left\" valign=\"top\">\n";
		echo "	<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_CURRENCY."</strong><br></td>\n";
		echo "	<td width=\"500\" align=\"left\"><select name=\"shop_setup_currency\" class=\"input\">\n";
			$res_currency = mysql_query("SELECT shop_currency_code, shop_currency_name FROM $db_shop_currency ORDER BY shop_currency_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());;
			while ($ar_currency = mysql_fetch_array($res_currency)){
				echo "<option value=\"".$ar_currency['shop_currency_code']."\" "; if ($ar['shop_setup_currency'] == $ar_currency['shop_currency_code']){echo "selected=\"selected\"";} echo ">".$ar_currency['shop_currency_name']."</option>\n";
			}
		echo "		</select></td>\n";
		echo "</tr>\n";
		echo "<tr align=\"left\" valign=\"top\">\n";
		echo "	<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_CARRIAGE_BASIC."</strong><br></td>\n";
		echo "	<td width=\"500\" align=\"left\"><select name=\"shop_setup_carriage\" class=\"input\">";
 			$res_carr = mysql_query("SELECT * FROM $db_shop_carriage ORDER BY shop_carriage_title") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_carr = mysql_fetch_array($res_carr)){
				echo "<option value=\"".$ar_carr['shop_carriage_id']."\""; 
					if ($ar['shop_setup_carriage_id'] == $ar_carr['shop_carriage_id']){echo "selected=\"selected\"";}
				echo ">".$ar_carr['shop_carriage_title']."</option>";
			}
	echo "			</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_DELIVERY_FREE."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_delivery_free_amount_active\" type=\"checkbox\" value=\"1\" "; if ($ar['shop_setup_delivery_free_amount_active'] == 1){echo "checked";} echo ">\n";
	echo "		"._SHOP_SETUP_DELIVERY_FREE_AMOUNT." <input name=\"shop_setup_delivery_free_amount\" type=\"text\" style=\"text-align:right;\" size=\"10\" maxlength=\"9\" value=\"".$ar['shop_setup_delivery_free_amount']."\">"; if ($ar['shop_setup_currency'] == "USD"){echo "$";} if ($ar['shop_setup_currency'] == "EUR"){echo "&euro;";} if ($ar['shop_setup_currency'] == "CZK"){echo "Kc";} if ($ar['shop_setup_currency'] == "GBP"){echo "&pound;";} echo "<br><br>\n";
	echo "		<input name=\"shop_setup_delivery_free_num_active\" type=\"checkbox\" value=\"1\" "; if ($ar['shop_setup_delivery_free_num_active'] == 1){echo "checked";} echo ">\n";
	echo "		"._SHOP_SETUP_DELIVERY_FREE_NUM." <select name=\"shop_setup_delivery_free_num\" class=\"input\">\n";
				for($i=0;11>$i;$i++){
					echo "<option value=\"".$i."\"";
					if ($ar['shop_setup_delivery_free_num'] == $i){echo "selected=\"selected\"";}
					echo ">".$i."</option>";
				}
	echo "			</select>"._SHOP_SETUP_DELIVERY_FREE_NUM_SIGN."\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_MARGIN_MAIN; $shop_main_margin = $ar['shop_setup_margin_main'] * 100; echo "</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_margin_main\" type=\"text\" size=\"4\" maxlength=\"3\" value=\"".$shop_main_margin."\">%</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_INVOICE_PREFIX."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_invoice_prefix\" type=\"text\" size=\"10\" maxlength=\"10\" value=\"".$ar['shop_setup_invoice_prefix']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_SHOW_ZERO_QUANTITY."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_show_zero_quantity\" type=\"checkbox\" value=\"1\" "; if ($ar['shop_setup_show_zero_quantity'] == 1){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_SHOW_VAT_SUBTOTAL."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_show_vat_subtotal\" type=\"checkbox\" value=\"1\" "; if ($ar['shop_setup_show_vat_subtotal'] == 1){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_SHOW_PREV_ORDERS."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_show_prev_orders\" type=\"checkbox\" value=\"1\" "; if ($ar['shop_setup_show_prev_orders'] == 1){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_TERMS."</strong><br><input name=\"shop_setup_02_terms_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_02_terms\" cols=\"70\" rows=\"15\">".$ar['shop_setup_02_terms']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_IMPORTANT."</strong><br><input name=\"shop_setup_04_important_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_04_important\" cols=\"70\" rows=\"15\">".$ar['shop_setup_04_important']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_TOC_OK."</strong><br><input name=\"shop_setup_05_toc_ok_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_05_toc_ok\" cols=\"70\" rows=\"8\">".$ar['shop_setup_05_toc_ok']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_TOC_NO."</strong><br><input name=\"shop_setup_05_toc_no_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_05_toc_no\" cols=\"70\" rows=\"8\">".$ar['shop_setup_05_toc_no']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_SHOP_ADDITIONAL."</strong><br><input name=\"shop_setup_05_additional_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_05_additional\" cols=\"70\" rows=\"15\">".$ar['shop_setup_05_additional']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</p>\n";
	echo "<p class=\"dropcontent\">\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_2\" class=\"nadpis_sekce\">"._SHOP_SETUP_PAYPAL_TEST_API."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\">&nbsp;</td>\n";
	echo "		<td width=\"500\" align=\"left\">"._SHOP_SETUP_PAYPAL_TEST_API_HELP."</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_PAYPAL_TEST_API_USERNAME."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_paypal_test_api_username\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_paypal_test_api_username']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_PAYPAL_TEST_API_PASSWORD."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_paypal_test_api_password\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_paypal_test_api_password']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_PAYPAL_TEST_API_SIGNATURE."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_paypal_test_api_signature\" type=\"text\" size=\"80\" maxlength=\"80\" value=\"".$ar['shop_setup_paypal_test_api_signature']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_PAYPAL_TEST_API_ENDPOINT."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><select name=\"shop_setup_paypal_test_api_endpoint\" class=\"input\">\n";
	echo "			<option value=\"https://api-3t.sandbox.paypal.com/nvp\" "; if ($ar['shop_setup_paypal_test_api_endpoint'] == "https://api-3t.sandbox.paypal.com/nvp"){echo "selected=\"selected\"";} echo ">https://api-3t.sandbox.paypal.com/nvp</option>\n";
	echo "			<option value=\"https://api-3t.paypal.com/nvp\" "; if ($ar['shop_setup_paypal_test_api_endpoint'] == "https://api-3t.paypal.com/nvp"){echo "selected=\"selected\"";} echo ">https://api-3t.paypal.com/nvp</option>\n";
	echo "			</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_PAYPAL_TEST_API_URL."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><select name=\"shop_setup_paypal_test_api_url\" class=\"input\">\n";
	echo "			<option value=\"https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=\" "; if ($ar['shop_setup_paypal_test_api_url'] == "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token="){echo "selected=\"selected\"";} echo ">https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=</option>\n";
	echo "			<option value=\"https://www.paypal.com/webscr&cmd=_express-checkout&token=\" "; if ($ar['shop_setup_paypal_test_api_url'] == "https://www.paypal.com/webscr&cmd=_express-checkout&token="){echo "selected=\"selected\"";} echo ">https://www.paypal.com/webscr&cmd=_express-checkout&token=</option>\n";
	echo "			</select></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</p>\n";
	echo "<p class=\"dropcontent\">\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_3\" class=\"nadpis_sekce\">"._SHOP_SETUP_EMAIL."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_CHARSET."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><select name=\"shop_setup_email_charset\" class=\"input\">\n";
	echo "			<option name=\"shop_setup_email_charset\" value=\"utf-8\" "; if ($ar['shop_setup_email_charset'] == "utf-8"){echo "selected=\"selected\"";} echo ">utf-8</option>	\n";
	echo "			<option name=\"shop_setup_email_charset\" value=\"windows-1250\" "; if ($ar['shop_setup_email_charset'] == "windows-1250"){echo "selected=\"selected\"";} echo ">windows-1250</option>\n";
	echo "			<option name=\"shop_setup_email_charset\" value=\"iso-8859-1\" "; if ($ar['shop_setup_email_charset'] == "iso-8859-1"){echo "selected=\"selected\"";} echo ">iso-8859-1</option>\n";
	echo "		</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_FROM."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_email_from\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_email_from']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_FROM_NAME."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_email_from_name\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_email_from_name']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_SUBJECT."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_email_subject\" type=\"text\" size=\"80\" maxlength=\"80\" value=\"".$ar['shop_setup_email_subject']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER."</strong><br>\n";
	echo "		<input name=\"shop_setup_email_order_checkbox\" type=\"checkbox\" value=\"1\"><br><br>\n";
	echo "		<div style=\"text-align:left;\">[{shop_orders_admin_id}]<br>\n";
	echo "		[{shop_orders_admin_firstname}]<br>\n";
	echo "		[{shop_orders_admin_name}]<br>\n";
	echo "		[{shop_orders_admin_company}]<br>\n";
	echo "		[{shop_orders_admin_address1}]<br>\n";
	echo "		[{shop_orders_admin_address2}]<br>\n";
	echo "		[{shop_orders_admin_suburb}]<br>\n";
	echo "		[{shop_orders_admin_city}]<br>\n";
	echo "		[{shop_orders_admin_postcode}]<br>\n";
	echo "		[{shop_orders_admin_telephone}]<br>\n";
	echo "		[{shop_orders_admin_mobile}]<br>\n";
	echo "		[{shop_orders_admin_email_address}]<br>\n";
	echo "		[{shop_orders_id}]<br>\n";
	echo "		[{shop_orders_invoice_id}]<br>\n";
	echo "		[{shop_orders_date_ordered}]<br>\n";
	echo "		[{shop_orders_date_sended_for_payment}]<br>\n";
	echo "		[{shop_orders_date_purchased}]<br>\n";
	echo "		[{shop_orders_date_picked}]<br>\n";
	echo "		[{shop_orders_date_despatched}]<br>\n";
	echo "		[{shop_orders_date_cancelled}]<br>\n";
	echo "		[{PRODUCTS_SUMMARY}]<br>\n";
	echo "		[{ESTIMATE_DELIVERY}]<br></div></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_STATUS_SUBJECT."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_email_order_subject\" type=\"text\" size=\"80\" maxlength=\"80\" value=\"".$ar['shop_setup_email_order_subject']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_STATUS_1."</strong><br><input name=\"shop_setup_email_order_status_1_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_status_1\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_status_1']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_STATUS_2."</strong><br><input name=\"shop_setup_email_order_status_2_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_status_2\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_status_2']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_STATUS_3."</strong><br><input name=\"shop_setup_email_order_status_3_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_status_3\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_status_3']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_STATUS_4."</strong><br><input name=\"shop_setup_email_order_status_4_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_status_4\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_status_4']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_CANCEL_BEFORE."</strong><br><input name=\"shop_setup_email_order_cancel_before_payment_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_cancel_before_payment\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_cancel_before_payment']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_CANCEL_AFTER."</strong><br><input name=\"shop_setup_email_order_cancel_after_payment_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_order_cancel_after_payment\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_order_cancel_after_payment']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_ORDER_CANCEL_AFTER."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\">"._SHOP_SETUP_EMAIL_TEST_ORDER_ID." <input name=\"test_email_order_id\" value=\"".$_POST['test_email_order_id']."\" type=\"text\" size=\"5\" maxlength=\"5\"><br>\n";
	echo "			"._SHOP_SETUP_EMAIL_TEST_ORDER_MODE." <select name=\"test_email_mode\" class=\"input\">\n";
	echo "				<option value=\"1\" "; if($_POST['test_email_mode'] == 1){ echo "selected=\"selected\"";} echo ">Order in process</option>\n";
	echo "				<option value=\"2\" "; if($_POST['test_email_mode'] == 2){ echo "selected=\"selected\"";} echo ">Pending</option>\n";
	echo "				<option value=\"3\" "; if($_POST['test_email_mode'] == 3){ echo "selected=\"selected\"";} echo ">Payment authorised</option>\n";
	echo "				<option value=\"4\" "; if($_POST['test_email_mode'] == 4){ echo "selected=\"selected\"";} echo ">Order picked / Awaiting despatch</option>\n";
	echo "				<option value=\"5\" "; if($_POST['test_email_mode'] == 5){ echo "selected=\"selected\"";} echo ">Order despatched</option>\n";
	echo "				<option value=\"6\" "; if($_POST['test_email_mode'] == 6){ echo "selected=\"selected\"";} echo ">Canceled before pay</option>\n";
	echo "				<option value=\"7\" "; if($_POST['test_email_mode'] == 7){ echo "selected=\"selected\"";} echo ">Canceled after pay</option>\n";
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_EMAIL_CONFIRM_OF_PAYMENT."</strong><br><input name=\"shop_setup_email_confirm_of_payment_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_email_confirm_of_payment\" cols=\"70\" rows=\"15\">".$ar['shop_setup_email_confirm_of_payment']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</p>\n";
	echo "<p class=\"dropcontent\">\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_4\" class=\"nadpis_sekce\">"._SHOP_SETUP_CLOTHES."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_CLOTHES_IMG_1."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_clothes_img_1_width\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_1_width']."\"> x <input name=\"shop_setup_clothes_img_1_height\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_1_height']."\">px</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_CLOTHES_IMG_2."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_clothes_img_2_width\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_2_width']."\"> x <input name=\"shop_setup_clothes_img_2_height\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_2_height']."\">px</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_CLOTHES_IMG_3."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_clothes_img_3_width\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_3_width']."\"> x <input name=\"shop_setup_clothes_img_3_height\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_3_height']."\">px</td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_CLOTHES_IMG_5."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_clothes_img_5_width\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_5_width']."\"> x <input name=\"shop_setup_clothes_img_5_height\" type=\"text\" size=\"5\" maxlength=\"4\" value=\"".$ar['shop_setup_clothes_img_5_height']."\">px</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</p>\n";
	echo "<p class=\"dropcontent\">\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\" style=\"background-color:FFDEDF\">\n";
	echo "		<td width=\"850\" colspan=\"2\"><span id=\"tab_5\" class=\"nadpis_sekce\">"._SHOP_SETUP_WHOLESALE."</span><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_EMAIL_FROM."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_wholesale_email_from\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_wholesale_email_from']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_EMAIL_FROM_NAME."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_wholesale_email_from_name\" type=\"text\" size=\"50\" maxlength=\"80\" value=\"".$ar['shop_setup_wholesale_email_from_name']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_EMAIL_SUBJECT."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_wholesale_email_subject\" type=\"text\" size=\"80\" maxlength=\"80\" value=\"".$ar['shop_setup_wholesale_email_subject']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_EMAIL_SUBJECT_ACT."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_wholesale_email_subject_act\" type=\"text\" size=\"80\" maxlength=\"80\" value=\"".$ar['shop_setup_wholesale_email_subject_act']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_EMAIL_ACT."</strong><br><input name=\"shop_setup_wholesale_email_act_checkbox\" type=\"checkbox\" value=\"1\"><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_wholesale_email_act\" cols=\"70\" rows=\"15\">".$ar['shop_setup_wholesale_email_act']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_TERMS."</strong><br><input name=\"shop_setup_wholesale_terms_checkbox\" type=\"checkbox\" value=\"1\"></td>\n";
	echo "		<td width=\"500\" align=\"left\"><textarea name=\"shop_setup_wholesale_terms\" cols=\"70\" rows=\"15\">".$ar['shop_setup_wholesale_terms']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_SETUP_WHOLESALE_PRICE_ARTICLE_ID."</strong><br></td>\n";
	echo "		<td width=\"500\" align=\"left\"><input name=\"shop_setup_wholesale_price_article_id\" type=\"text\" size=\"5\" maxlength=\"10\" value=\"".$ar['shop_setup_wholesale_price_article_id']."\"></td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "</p>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"750\" colspan=\"2\"><br><br>\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" Value=\""._SETUP_SET."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		DANOVE TRIDY																						
*																											
***********************************************************************************************************/
function TaxClass(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_tax_class;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$tax_class_title = strip_tags($_POST['tax_class_title'],$allowtags);
		$tax_class_description = strip_tags($_POST['tax_class_description'],$allowtags);
		if ($_GET['action'] == "add_tax_class"){
			mysql_query("INSERT INTO $db_shop_tax_class VALUES('','".mysql_real_escape_string($tax_class_title)."','".mysql_real_escape_string($tax_class_description)."','',NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_tax_class"){
			mysql_query("UPDATE $db_shop_tax_class 
			SET shop_tax_class_title='".mysql_real_escape_string($tax_class_title)."', shop_tax_class_description='".mysql_real_escape_string($tax_class_description)."', shop_tax_class_last_modified = NOW() 
			WHERE shop_tax_class_id=".$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_GET['action'] = "add_tax_class";
		}
		if ($_GET['action'] == "del_tax_class"){
			mysql_query("DELETE FROM $db_shop_tax_class WHERE shop_tax_class_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$_GET['action'] = "add_tax_class";
		}
	}
	if ($_GET['action'] != "add_tax_class"){	
		$res = mysql_query("SELECT * FROM $db_shop_tax_class WHERE shop_tax_class_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_TAX_CLASS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	 if ($_GET['action'] == "del_tax_class"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_class"){echo "add_tax_class";} elseif ($_GET['action'] == "edit_tax_class"){echo "edit_tax_class";} else {echo "del_tax_class";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_TAX_CLASS_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_class"){echo "add_tax_class";} else {echo "edit_tax_class";} echo "\" method=\"post\">\n";
	echo "			<strong>"._SHOP_TAX_CLASS_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"tax_class_title\" maxlength=\"32\" size=\"50\" "; if ($_GET['action'] == "edit_tax_class"){echo "value=\"".$ar['shop_tax_class_title']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_TAX_CLASS_DESC."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"30\" rows=\"4\" name=\"tax_class_description\">"; if ($_GET['action'] == "edit_tax_class"){echo $ar['shop_tax_class_description'];} echo "</textarea><br>\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_tax_class" || $action == "add_tax_class"){echo _SHOP_TAX_CLASS_ADD;} else {echo _SHOP_TAX_CLASS_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"200\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_CLASS_TITLE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_CLASS_DESC."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_ADDDATE."</span></td>\n";
	echo "	</tr>";
		$res = mysql_query("SELECT * FROM $db_shop_tax_class ORDER BY shop_tax_class_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){
			$add_date = FormatDatetime($ar['shop_tax_class_date_added']);
				echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">";
				echo "	<td width=\"80\" align=\"center\">";
 						if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=edit_tax_class&amp;id=".$ar['shop_tax_class_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
						if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=del_tax_class&amp;id=".$ar['shop_tax_class_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
				echo "	</td>\n";
				echo "	<td width=\"20\" align=\"left\">".$ar['shop_tax_class_id']."</td>\n";
				echo "	<td width=\"200\" align=\"left\">".$ar['shop_tax_class_title']."</td>\n";
				echo "	<td align=\"left\">".$ar['shop_tax_class_description']."</td>\n";
				echo "	<td align=\"left\">".$add_date."</td>\n";
				echo "</tr>";
 		}
		echo "</table>";
}
/***********************************************************************************************************
*																											
*		DANOVE ZONY																							
*																											
***********************************************************************************************************/
function TaxZones(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_tax_zones,$db_country;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$zone_name = strip_tags($_POST['zone_name'],$allowtags);
		$res = mysql_query("SELECT * FROM $db_country WHERE country_id=".(float)$_POST['zone_country_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		if ($_GET['action'] == "add_tax_zones"){
			mysql_query("INSERT 
			INTO $db_shop_tax_zones 
			VALUES('','".(float)$_POST['zone_country_id']."','".mysql_real_escape_string($ar['country_shortname'])."','".mysql_real_escape_string($zone_name)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_tax_zones"){
			mysql_query("UPDATE $db_shop_tax_zones 
			SET shop_zone_country_id=".(float)$_POST['zone_country_id'].", shop_zone_code='".mysql_real_escape_string($ar['country_shortname'])."', shop_zone_name='".mysql_real_escape_string($zone_name)."' 
			WHERE shop_zone_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_tax_zones";
		}
		if ($_GET['action'] == "del_tax_zones"){
			mysql_query("DELETE FROM $db_shop_tax_zones WHERE shop_zone_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_tax_zones";
		}
	}
	if ($action != "add_tax_zones"){	
		$res = mysql_query("SELECT * FROM $db_shop_tax_zones WHERE shop_zone_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_TAX_ZONES."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "del_tax_zones"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_zones" || $action == "add_tax_zones"){echo "add_tax_zones";} elseif ($_GET['action'] == "edit_tax_zones"){echo "edit_tax_zones";} else {echo "del_tax_zones";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_TAX_ZONES_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_zones" || $action == "add_tax_zones"){echo "add_tax_zones";} else {echo "edit_tax_zones";} echo "\" method=\"post\">\n";
	echo "			<strong>"._SHOP_TAX_ZONE_COUNTRY."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">";
 				$res2 = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"zone_country_id\" style=\"width: 150px;\">";
					while($ar2 = mysql_fetch_array($res2)){						
						echo "<option name=\"zone_country_id\" value=\"".$ar2['country_id']."\""; if ($ar['shop_zone_country_id'] == $ar2['country_id']){echo "selected=\"selected\"";} echo ">".$ar2['country_name']."</option>";
					}
				echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_TAX_ZONE_NAME."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"zone_name\" maxlength=\"32\" size=\"50\" value=\""; if ($_GET['action'] == "edit_tax_zones"){echo $ar['shop_zone_name'];} echo "\"><br>\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_tax_zones" || $action == "add_tax_zones"){echo _SHOP_TAX_ZONES_ADD;} else {echo _SHOP_TAX_ZONES_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"200\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_ZONE_CODE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_ZONE_NAME."</span></td>\n";
	echo "	</tr>";
 		$res = mysql_query("SELECT * FROM $db_shop_tax_zones ORDER BY shop_zone_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){
			echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">";
			echo "	<td width=\"80\" align=\"center\">";
	 					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=edit_tax_zones&amp;id=".$ar['shop_zone_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
						if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=del_tax_zones&amp;id=".$ar['shop_zone_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			echo "	</td>\n";
			echo "	<td width=\"20\" align=\"left\">".$ar['shop_zone_id']."</td>\n";
			echo "	<td width=\"200\" align=\"left\">".$ar['shop_zone_code']."</td>\n";
			echo "	<td align=\"left\">".$ar['shop_zone_name']."</td>\n";
			echo "</tr>";
 		}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		DANOVE SAZBY																						
*																											
***********************************************************************************************************/
function TaxRates(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_tax_rates,$db_shop_tax_class,$db_shop_tax_zones;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$tax_rate = strip_tags($_POST['tax_rate'],$allowtags);
		$tax_description = strip_tags($_POST['tax_description'],$allowtags);
		$tax_rate = addslashes($tax_rate);
		$tax_description = addslashes($tax_description);
		if ($_GET['action'] == "add_tax_rates"){
			mysql_query("INSERT 
			INTO $db_shop_tax_rates 
			VALUES('','".(float)$_POST['tax_zone_id']."','".(float)$_POST['tax_class_id']."','".(float)$_POST['tax_priority']."','".(float)$tax_rate."','".mysql_real_escape_string($tax_description)."','',NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_tax_rates"){
			mysql_query("UPDATE $db_shop_tax_rates 
			SET shop_tax_rates_zone_id=".(float)$_POST['tax_zone_id'].", shop_tax_rates_class_id=".(float)$_POST['tax_class_id'].", shop_tax_rates_priority=".(float)$_POST['tax_priority'].", shop_tax_rates_rate=".(float)$tax_rate.", shop_tax_rates_description='".mysql_real_escape_string($tax_description)."', shop_tax_rates_last_modified = NOW() 
			WHERE shop_tax_rates_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_tax_rates";
		}
		if ($_GET['action'] == "del_tax_rates"){
			mysql_query("DELETE FROM $db_shop_tax_rates WHERE shop_tax_rates_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_tax_rates";
		}
	}
	if ($action != "add_tax_rates"){	
		$res = mysql_query("SELECT * FROM $db_shop_tax_rates WHERE shop_tax_rates_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_TAX_RATES."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	if ($_GET['action'] == "del_tax_rates"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_rates" || $action == "add_tax_rates"){echo "add_tax_rates";} elseif ($_GET['action'] == "edit_tax_rates"){echo "edit_tax_rates";} else {echo "del_tax_rates";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_TAX_RATES_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_tax_rates" || $action == "add_tax_rates"){echo "add_tax_rates";} else {echo "edit_tax_rates";} echo "\" method=\"post\">\n";
	echo "			<strong>"._SHOP_TAX_RATE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"tax_rate\" maxlength=\"13\" size=\"8\" "; if ($_GET['action'] == "edit_tax_rates"){echo "value=\"".$ar['shop_tax_rates_rate']."\"";} echo "> "._SHOP_TAX_RATE_HELP."\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\" valign=\"top\"><strong>"._SHOP_TAX_RATES_DESC."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"30\" rows=\"4\" name=\"tax_description\">"; if ($_GET['action'] == "edit_tax_rates"){echo $ar[shop_tax_rates_description];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><strong>"._SHOP_TAX_CLASS."</strong></td>\n";
	echo "		<td align=\"left\">\n";
					$res2 = mysql_query("SELECT * FROM $db_shop_tax_class ORDER BY shop_tax_class_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"tax_class_id\" style=\"width: 150px;\">";
						while($ar2 = mysql_fetch_array($res2)){
							echo "<option name=\"tax_class_id\" value=\"".$ar2['shop_tax_class_id']."\""; if ($ar['shop_tax_rates_class_id'] == $ar2['shop_tax_class_id']){echo "selected=\"selected\"";} echo ">".$ar2['shop_tax_class_title']."</option>";
						}
					echo "</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><strong>"._SHOP_TAX_ZONE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
					$res2 = mysql_query("SELECT * FROM $db_shop_tax_zones ORDER BY shop_zone_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"tax_zone_id\" style=\"width: 150px;\">\n";
					while($ar2 = mysql_fetch_array($res2)){
						echo "<option name=\"tax_zone_id\" value=\"".$ar2['shop_zone_id']."\""; if ($ar['shop_tax_rates_zone_id'] == $ar2['shop_zone_id']){echo "selected=\"selected\"";} echo ">".$ar2['shop_zone_name']."</option>\n";
					}
					echo "</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\" valign=\"top\"><strong>"._SHOP_TAX_RATES_PRIOR."</strong></td>\n";
	echo "		<td align=\"left\">\n";
				echo "<select name=\"tax_priority\" style=\"width: 150px;\">\n";
				for($i=1;$i<10;$i++){
					echo "<option name=\"tax_priority\" value=\"".$i."\""; if ($ar['shop_tax_rates_priority'] == $i){echo "selected=\"selected\"";} echo ">".$i."</option>\n";
				}
				echo "</select>"._SHOP_TAX_RATES_PRIOR_HELP."\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_tax_rates" || $action == "add_tax_rates"){echo _SHOP_TAX_RATES_ADD;} else {echo _SHOP_TAX_RATES_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"200\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_RATE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_RATES_DESC."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_CLASS."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_PRIORITY."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_ZONE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_TAX_ADDDATE."</span></td>\n";
	echo "	</tr>\n";
	$res_tax_rates = mysql_query("SELECT * FROM $db_shop_tax_rates ORDER BY shop_tax_rates_class_id ASC, shop_tax_rates_priority DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar_tax_rates = mysql_fetch_array($res_tax_rates)){
		$res_tax_zone_class = mysql_query("SELECT tc.shop_tax_class_title, tz.shop_zone_name 
		FROM $db_shop_tax_class AS tc 
		LEFT JOIN $db_shop_tax_zones AS tz ON tc.shop_tax_class_id=".$ar_tax_rates['shop_tax_rates_class_id']." AND tz.shop_zone_id=".$ar_tax_rates['shop_tax_rates_zone_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_tax_zone_class = mysql_fetch_array($res_tax_zone_class);
		$add_date = FormatDatetime($ar_tax_rates['shop_tax_rates_date_added']);
		$tax_rate = stripslashes($ar_tax_rates['shop_tax_rates_rate']);
		$tax_description = stripslashes($ar_tax_rates['shop_tax_rates_description']);
		echo "	<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
		echo "		<td width=\"80\" align=\"center\">\n";
		 	  		if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=edit_tax_rates&amp;id=".$ar_tax_rates['shop_tax_rates_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
			  		if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=del_tax_rates&amp;id=".$ar_tax_rates['shop_tax_rates_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "		</td>\n";
		echo "		<td width=\"20\" align=\"left\">".$ar_tax_rates['shop_tax_rates_id']."</td>\n";
		echo "		<td width=\"200\" align=\"right\">".$tax_rate."</td>\n";
		echo "		<td align=\"left\">".$tax_description."</td>\n";
		echo "		<td align=\"left\">".$ar_tax_zone_class['shop_tax_class_title']."</td>\n";
		echo "		<td align=\"left\">".$ar_tax_rates['shop_tax_rates_priority']."</td>\n";
		echo "		<td align=\"left\">".$ar_tax_zone_class['shop_zone_name']."</td>\n";
		echo "		<td align=\"left\">".$add_date."</td>\n";
		echo "	</tr>";
   	}
   	echo "</table>";
}
/***********************************************************************************************************
*																											
*		DOPRAVA - CARRIAGE																					
*																											
***********************************************************************************************************/
function Carriage(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_carriage,$db_shop_carriage_category;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$carriage_title = strip_tags($_POST['carriage_title'],$allowtags);
		$carriage_description = strip_tags($_POST['carriage_description'],$allowtags);
		$carriage_price = strip_tags($_POST['carriage_price'],$allowtags);
		if ($_GET['action'] == "add_carriage"){
				mysql_query("INSERT 
				INTO $db_shop_carriage 
				VALUES('','".mysql_real_escape_string($carriage_title)."','".mysql_real_escape_string($carriage_description)."','".(float)$carriage_price."','".mysql_real_escape_string($_POST['carriage_code'])."','".(integer)$_POST['carriage_wholesale']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_carriage"){
			mysql_query("UPDATE $db_shop_carriage 
			SET shop_carriage_title='".mysql_real_escape_string($carriage_title)."', shop_carriage_description='".mysql_real_escape_string($carriage_description)."', shop_carriage_price=".(float)$carriage_price.", shop_carriage_category='".mysql_real_escape_string($_POST['carriage_category'])."', shop_carriage_wholesale=".(integer)$_POST['carriage_wholesale']." 
			WHERE shop_carriage_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_carriage";
		}
		if ($_GET['action'] == "del_carriage"){
			mysql_query("DELETE FROM $db_shop_carriage WHERE shop_carriage_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_carriage";
		}
	}
	if ($action != "add_carriage"){	
		$res = mysql_query("SELECT * FROM $db_shop_carriage WHERE shop_carriage_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CARRIAGE."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "del_carriage"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_carriage" || $action == "add_carriage"){echo "add_carriage";} elseif ($_GET['action'] == "edit_carriage"){echo "edit_carriage";} else {echo "del_carriage";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CARRIAGE_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_carriage" || $action == "add_carriage"){echo "add_carriage";} else {echo "edit_carriage";} echo "\" method=\"post\">\n";
	echo "			<strong>"._SHOP_CARRIAGE_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"carriage_title\" maxlength=\"50\" size=\"50\" "; if ($_GET['action'] == "edit_carriage"){echo "value=\"".$ar['shop_carriage_title']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CARRIAGE_CATEGORY."</strong></td>\n";
	echo "		<td align=\"left\">";
				$res_cat = mysql_query("SELECT shop_carriage_category_code, shop_carriage_category_title FROM $db_shop_carriage_category") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"carriage_category\">\n";
				while ($ar_cat = mysql_fetch_array($res_cat)){
					echo "<option value=\"".$ar_cat['shop_carriage_category_code']."\" "; if ($ar_cat['shop_carriage_category_code'] == $ar['shop_carriage_category']){ echo "selected=\"selected\"";} echo ">".$ar_cat['shop_carriage_category_title']."</option>";
				}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CARRIAGE_WHOLESALE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"checkbox\" name=\"carriage_wholesale\" value=\"1\" "; if ($ar['shop_carriage_wholesale'] == 1){echo "checked=\"checked\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CARRIAGE_PRICE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"carriage_price\" maxlength=\"15\" size=\"8\" "; if ($_GET['action'] == "edit_carriage"){echo "value=\"".$ar['shop_carriage_price']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\" valign=\"top\"><strong>"._SHOP_CARRIAGE_DESC."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"30\" rows=\"4\" name=\"carriage_description\">"; if ($_GET['action'] == "edit_carriage"){echo $ar['shop_carriage_description'];} echo "</textarea><br>\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_carriage" || $action == "add_carriage"){echo _SHOP_CARRIAGE_ADD;} else {echo _SHOP_CARRIAGE_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_CATEGORY_CODE."</span></td>\n";
	echo "		<td width=\"250\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_TITLE."</span></td>\n";
	echo "		<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_PRICE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_DESC."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT * FROM $db_shop_carriage ORDER BY shop_carriage_price ASC, shop_carriage_category ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar = mysql_fetch_array($res)){
		echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">";
		echo "	<td width=\"80\" align=\"center\">";
					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=edit_carriage&amp;id=".$ar['shop_carriage_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"?action=del_carriage&amp;id=".$ar['shop_carriage_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['shop_carriage_id']."</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['shop_carriage_category']."</td>\n";
		echo "	<td width=\"250\" align=\"left\">".$ar['shop_carriage_title']."</td>\n";
		echo "	<td width=\"30\" align=\"left\">".$ar['shop_carriage_price']."</td>\n";
		echo "	<td align=\"left\">".$ar['shop_carriage_description']."</td>\n";
		echo "</tr>";
		}
   	echo "</table>";
}
/***********************************************************************************************************
*																											
*		DOPRAVA KATEGORIE - CARRIAGE CATEGORY																
*																											
***********************************************************************************************************/
function CarriageCategory(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_carriage_category;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$carriage_category_title = strip_tags($_POST['carriage_category_title'],$allowtags);
		$carriage_category_description = strip_tags($_POST['carriage_category_description'],$allowtags);
		$carriage_category_code = strip_tags($_POST['carriage_category_code'],$allowtags);
		if ($_GET['action'] == "add_carriage_category"){
				mysql_query("INSERT 
				INTO $db_shop_carriage_category 
				VALUES('','".mysql_real_escape_string($carriage_category_title)."','".mysql_real_escape_string($carriage_category_description)."','".mysql_real_escape_string($carriage_category_code)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_carriage_category"){
			mysql_query("UPDATE $db_shop_carriage_category 
			SET shop_carriage_category_title='".mysql_real_escape_string($carriage_category_title)."', shop_carriage_category_description='".mysql_real_escape_string($carriage_category_description)."', shop_carriage_category_code='".mysql_real_escape_string($carriage_category_code)."' 
			WHERE shop_carriage_category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_carriage_category";
		}
		if ($_GET['action'] == "del_carriage_category"){
			mysql_query("DELETE FROM $db_shop_carriage_category WHERE shop_carriage_category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_carriage_category";
		}
	}
	if ($action != "add_carriage_category"){	
		$res = mysql_query("SELECT * FROM $db_shop_carriage_category WHERE shop_carriage_category_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CARRIAGE_CATEGORY."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopSetupMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "del_carriage_category"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_carriage_category" || $action == "add_carriage_category"){echo "add_carriage_category";} elseif ($_GET['action'] == "edit_carriage_category"){echo "edit_carriage_category";} else {echo "del_carriage_category";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color:#FF0000;\">"._SHOP_CARRIAGE_CATEGORY_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\"><form action=\"modul_shop_setup.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_carriage_category" || $action == "add_carriage_category"){echo "add_carriage_category";} else {echo "edit_carriage_category";} echo "\" method=\"post\">\n";
	echo "			<strong>"._SHOP_CARRIAGE_CATEGORY_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"carriage_category_title\" maxlength=\"50\" size=\"50\" "; if ($_GET['action'] == "edit_carriage_category"){echo "value=\"".$ar['shop_carriage_category_title']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\">\n";
	echo "			<strong>"._SHOP_CARRIAGE_CATEGORY_CODE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"carriage_category_code\" maxlength=\"15\" size=\"8\" "; if ($_GET['action'] == "edit_carriage_category"){echo "value=\"".$ar['shop_carriage_category_code']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"left\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CARRIAGE_CATEGORY_DESC."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"30\" rows=\"4\" name=\"carriage_category_description\">"; if ($_GET['action'] == "edit_carriage_category"){echo $ar['shop_carriage_category_description'];} echo "</textarea><br>\n";
	echo "			"._SHOP_CARRIAGE_CATEGORY_HELP."<br>\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_carriage_category" || $action == "add_carriage_category"){echo _SHOP_CARRIAGE_CATEGORY_ADD;} else {echo _SHOP_CARRIAGE_CATEGORY_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"250\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_CATEGORY_TITLE."</span></td>\n";
	echo "		<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_CATEGORY_CODE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CARRIAGE_CATEGORY_DESC."</span></td>\n";
	echo "	</tr>";
 	$res = mysql_query("SELECT * FROM $db_shop_carriage_category ORDER BY shop_carriage_category_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar = mysql_fetch_array($res)){
		echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">";
		echo "	<td width=\"80\" align=\"center\">";
				if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=edit_carriage_category&amp;id=".$ar['shop_carriage_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=del_carriage_category&amp;id=".$ar['shop_carriage_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['shop_carriage_category_id']."</td>\n";
		echo "	<td width=\"250\" align=\"left\">".$ar['shop_carriage_category_title']."</td>\n";
		echo "	<td width=\"30\" align=\"left\">".$ar['shop_carriage_category_code']."</td>\n";
		echo "	<td align=\"left\">".$ar['shop_carriage_category_description']."</td>\n";
		echo "</tr>";
 	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PLATEBNI METODY - PAYMENT 																			
*																											
***********************************************************************************************************/
function PaymentMethods(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_payment_methods,$ftp_path_shop_payments;
	global $eden_cfg;
	global $url_shop_payments;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$payment_title = strip_tags($_POST['payment_title'],$allowtags);
		$payment_description = strip_tags($_POST['payment_description'],$allowtags);
		
		if ($_GET['action'] == "add_payment"){
			mysql_query("INSERT INTO $db_shop_payment_methods VALUES('','".mysql_real_escape_string($payment_title)."','".mysql_real_escape_string($payment_description)."','','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$payment_id_add = mysql_insert_id();
		}
		if ($_GET['action'] == "edit_payment"){
			mysql_query("UPDATE $db_shop_payment_methods 
			SET shop_payment_methods_title='".mysql_real_escape_string($payment_title)."', shop_payment_methods_descriptions='".mysql_real_escape_string($payment_description)."', shop_payment_methods_publish=".(float)$_POST['payment_publish']." 
			WHERE shop_payment_methods_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$action = "add_payment";
		}
		if ($_GET['action'] == "del_payment"){
			mysql_query("DELETE FROM $db_shop_payment_methods WHERE shop_payment_methods_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$action = "add_payment";
		}
		if ($_FILES['payment_picture']['name'] != "") {
			// Spojeni s FTP serverem 
			$conn_id = ftp_connect($eden_cfg['ftp_server']); 
			// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
			$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
			ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
			
			// Zjisteni stavu spojeni
			if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;} 
			
			// ziskam extenzi souboru
		   	$extenze = strtok($_FILES['payment_picture']['name'] ,".");
		   	$extenze = strtok(".");
			// Ulozi jmeno obrazku jako
			$picture = Cislo().".".$extenze;
			// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
			$source_file = $_FILES['payment_picture']['tmp_name'];
			// Vlozi nazev souboru a cestu do konkretniho adresare
			$destination_file = $ftp_path_shop_payments.$picture;
			
			if ($_GET['action'] == "add_payment"){$payment_id = $payment_id_add;} else {$payment_id = $_GET['id'];}
			
			if ($_FILES['payment_picture']['name'] != ""){
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
				if (!$upload) {
					echo _ERROR_UPLOAD;	$picture = "<br>";
				} else {
					echo _OK_FTP_FILE_1.' '.$destination_file.' '._OK_FTP_FILE_2.'<br>';
					mysql_query("UPDATE $db_shop_payment_methods SET shop_payment_methods_picture='".mysql_real_escape_string($picture)."' WHERE shop_payment_methods_id=".(float)$payment_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			}
			
			// Uzavreni komunikace se serverem 
			ftp_close($conn_id); 
		}
	}
if ($action != "add_payment"){	
	$res = mysql_query("SELECT * FROM $db_shop_payment_methods WHERE shop_payment_methods_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _SHOP.' - '._SHOP_PAYMENT;?></td>
		</tr>
		<tr>
			<td><?php echo ShopSetupMenu();?></td>
		</tr>
	</table><?php 
	if ($_GET['action'] == "del_carriage"){
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left"><form action="modul_shop_setup.php?id=<?php echo $_GET['id'];?>&amp;action=<?php if ($_GET['action'] == "add_payment" || $action == "add_payment"){echo 'add_payment';}elseif ($_GET['action'] == "edit_payment"){echo 'edit_payment';} else {echo 'del_payment';} ?>" method="post" >
				<strong><span style="color : #FF0000;"><?php echo _SHOP_PAYMENT_DELCHECK;?></span></strong>
				<input type="submit" value="<?php echo _CMN_YES;?>" class="eden_button">
				<input type="hidden" name="confirm" value="true">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				</form>
			</td>	
		</tr>
	</table><?php 
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td width="200" align="left"><form action="modul_shop_setup.php?id=<?php echo $_GET['id'];?>&amp;action=<?php if ($_GET['action'] == "add_payment" || $action == "add_payment"){echo 'add_payment';} else {echo 'edit_payment';} ?>" method="post" enctype="multipart/form-data" >
				<strong><?php echo _SHOP_PAYMENT_TITLE;?></strong>
			</td>
			<td align="left">
				<input type="text" name="payment_title" maxlength="80" size="50" <?php if ($_GET['action'] == "edit_payment"){echo 'value="'.$ar['shop_payment_methods_title'].'"';}?>>
			</td>	
		</tr>
		<tr>
			<td width="200" align="left">
				<strong><?php echo _SHOP_PAYMENT_PICTURE;?></strong>
			</td>
			<td align="left">
				<input type="file" name="payment_picture" size="30">
			</td>	
		</tr>
		<tr>
			<td width="200" align="left">
				<strong><?php echo _SHOP_PAYMENT_PUBLISH;?></strong>
			</td>
			<td align="left">
				<input type="checkbox" name="payment_publish" value="1" <?php if ($ar['shop_payment_methods_publish'] == 1){echo "checked";}?>>
			</td>	
		</tr>
		<tr>
			<td width="200" align="left" valign="top">
				<strong><?php echo _SHOP_PAYMENT_DESC;?></strong>
			</td>
			<td align="left">
				<textarea cols="30" rows="4" name="payment_description"><?php if ($_GET['action'] == "edit_payment"){echo $ar[shop_payment_methods_descriptions];}?></textarea><br>
				<input type="submit" value="<?php if ($_GET['action'] == "add_payment" || $action == "add_payment"){echo _SHOP_PAYMENT_ADD;} else {echo _SHOP_PAYMENT_EDIT;} ?>" class="eden_button">
				<input type="hidden" name="confirm" value="true">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				</form>
			</td>	
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="80" align="center"><span class="nadpis-boxy"><?php echo _CMN_OPTIONS;?></span></td>
			<td width="20" align="left"><span class="nadpis-boxy"><?php echo _CMN_ID;?></span></td>
			<td width="250" align="left"><span class="nadpis-boxy"><?php echo _SHOP_PAYMENT_TITLE;?></span></td>
			<td width="30" align="left"><span class="nadpis-boxy"><?php echo _SHOP_PAYMENT_PUBLISH;?></span></td>
			<td width="30" align="left"><span class="nadpis-boxy"><?php echo _SHOP_PAYMENT_PICTURE;?></span></td>
			<td align="left"><span class="nadpis-boxy"><?php echo _SHOP_PAYMENT_DESC;?></span></td>
		</tr><?php
	 	$res = mysql_query("SELECT * FROM $db_shop_payment_methods ORDER BY shop_payment_methods_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){?>
				<tr onmouseover="this.style.backgroundColor='FFDEDF'" onmouseout="this.style.backgroundColor='FFFFFF'">
					<td width="80" align="center"><?php
		 				if (CheckPriv("groups_shop_del") == 1){echo '<a href="?action=edit_payment&amp;id='.$ar['shop_payment_methods_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_edit.gif" height="18" width="18" border="0" alt="'._CMN_EDIT.'"></a>';}
						if (CheckPriv("groups_shop_del") == 1){echo '<a href="?action=del_payment&amp;id='.$ar['shop_payment_methods_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';}?>
					</td>
					<td width="20" align="left"><?php echo $ar['shop_payment_methods_id'];?></td>
					<td width="250" align="left"><?php echo $ar['shop_payment_methods_title'];?></td>
					<td width="30" align="left"><?php  if ($ar['shop_payment_methods_publish'] == 1){echo _CMN_YES;} else {echo _CMN_NO;} ?></td>
					<td width="30" align="left"><img src="<?php echo $url_shop_payments.$ar['shop_payment_methods_picture'];?>" alt="" border="0"></td>
					<td align="left"><?php echo $ar['shop_payment_methods_descriptions'];?></td>
				</tr><?php
	 	}
		echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES - GENDER																					
*																											
***********************************************************************************************************/
function Gender(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_gender;
	
	if ($_POST['confirm'] == "true"){
		// Výčet povolených tagů
		$allowtags = ""; 
		$gender_title = strip_tags($_POST['gender_title'],$allowtags);
		$gender_description = strip_tags($_POST['gender_descriptuion'],$allowtags);
		
		if ($_GET['action'] == "add_gender"){
			mysql_query("INSERT INTO $db_shop_gender VALUES('','".mysql_real_escape_string($gender_title)."','".mysql_real_escape_string($gender_description)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		if ($_GET['action'] == "edit_gender"){
			mysql_query("UPDATE $db_shop_gender SET shop_gender_title='".mysql_real_escape_string($gender_title)."', shop_gender_description='".mysql_real_escape_string($gender_description)."' WHERE shop_gender_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$_GET['action']= "add_gender";
		}
		if ($_GET['action'] == "del_gender"){
			mysql_query("DELETE FROM $db_shop_gender WHERE shop_gender_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_GET['action']);
			$_GET['action']= "add_gender";
		}
	}
if ($_GET['action'] != "add_gender"){	
	$res = mysql_query("SELECT * FROM $db_shop_gender WHERE shop_gender_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
}
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _SHOP.' - '._SHOP_GENDER;?></td>
		</tr>
		<tr>
			<td><?php echo ShopSetupMenu();?></td>
		</tr>
	</table><?php 
	if ($_GET['action'] == "del_gender"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left"><form action="modul_shop_setup.php?id=<?php echo $_GET['id'];?>&amp;action=<?php if ($_GET['action'] == "add_gender" || $_GET['action'] == ""){echo 'add_gender';}elseif ($_GET['action'] == "edit_gender"){echo 'edit_gender';} else {echo 'del_gender';} ?>" method="post" >
				<strong><span style="color : #FF0000;"><?php echo _SHOP_GENDER_DELCHECK;?></span></strong>
				<input type="submit" value="<?php echo _CMN_YES;?>" class="eden_button">
				<input type="hidden" name="confirm" value="true">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				</form>
			</td>	
		</tr>
	</table><?php 
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td width="200" align="left"><form action="modul_shop_setup.php?id=<?php echo $_GET['id'];?>&amp;action=<?php if ($_GET['action'] == "add_gender" || $_GET['action'] == ""){echo 'add_gender';} else {echo 'edit_gender';} ?>" method="post" enctype="multipart/form-data" >
				<strong><?php echo _SHOP_GENDER_TITLE;?></strong>
			</td>
			<td align="left">
				<input type="text" name="gender_title" maxlength="20" size="15" <?php if ($_GET['action'] == "edit_gender"){echo 'value="'.$ar['shop_gender_title'].'"';}?>>
			</td>	
		</tr>
		<tr>
			<td width="200" align="left">
				<strong><?php echo _SHOP_GENDER_DESC;?></strong>
			</td>
			<td align="left"><textarea cols="60" rows="5" name="gender_description"></textarea>
			</td>	
		</tr>
		<tr>
			<td width="200" align="left" valign="top">&nbsp;</td>
			<td align="left">
				<input type="submit" value="<?php if ($_GET['action'] == "add_gender" || $_GET['action'] == ""){echo _SHOP_GENDER_ADD;} else {echo _SHOP_GENDER_EDIT;} ?>" class="eden_button">
				<input type="hidden" name="confirm" value="true">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				</form>
			</td>	
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="80" align="center"><span class="nadpis-boxy"><?php echo _CMN_OPTIONS;?></span></td>
			<td width="20" align="left"><span class="nadpis-boxy"><?php echo _CMN_ID;?></span></td>
			<td align="left"><span class="nadpis-boxy"><?php echo _SHOP_GENDER_TITLE;?></span></td>
		</tr><?php
	 	$res = mysql_query("SELECT * FROM $db_shop_gender ORDER BY shop_gender_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while($ar = mysql_fetch_array($res)){?>
				<tr onmouseover="this.style.backgroundColor='FFDEDF'" onmouseout="this.style.backgroundColor='FFFFFF'">
					<td width="80" align="center"><?php
		 				if (CheckPriv("groups_shop_del") == 1){echo '<a href="modul_shop_setup.php?action=edit_gender&amp;id='.$ar['shop_gender_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_edit.gif" height="18" width="18" border="0" alt="'._CMN_EDIT.'"></a>';}
						if (CheckPriv("groups_shop_del") == 1){echo '<a href="modul_shop_setup.php?action=del_gender&amp;id='.$ar['shop_gender_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';}?>
					</td>
					<td width="20" align="left"><?php echo $ar['shop_gender_id'];?></td>
					<td align="left"><?php echo $ar['shop_gender_title'];?></td>
				</tr><?php
	 	}
		echo "</table>";
}
/***********************************************************************************************************
*																											
*		CURRENCY - MENA																						
*																											
***********************************************************************************************************/
function Currency(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_currency;
	
	if ($_GET['action'] != "shop_currency_add"){	
		$res = mysql_query("SELECT * FROM $db_shop_currency WHERE shop_currency_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CURRENCY."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>"; echo ShopSetupMenu(); echo "</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	if ($_GET['action'] == "shop_currency_del"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"modul_sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "currency_add" || $action == "currency_add"){echo "currency_add";} elseif ($_GET['action'] == "currency_edit"){echo "currency_edit";} else {echo "currency_del";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CURRENCY_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "shop_currency_add" || $action == "shop_currency_add"){echo "shop_currency_add";} else {echo "shop_currency_edit";} echo "\" method=\"post\"><strong>"._SHOP_CURRENCY_TITLE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"currency_name\" maxlength=\"50\" size=\"50\" "; if ($_GET['action'] == "shop_currency_edit"){echo "value=\"".$ar['shop_currency_name']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CURRENCY_CODE."</strong><br>"._SHOP_CURRENCY_CODE_HELP."</td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"currency_code\" maxlength=\"3\" size=\"8\" "; if ($_GET['action'] == "shop_currency_edit"){echo "value=\"".$ar['shop_currency_code']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CURRENCY_CODE_LOCAL."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"currency_code_local\" maxlength=\"15\" size=\"10\" "; if ($_GET['action'] == "shop_currency_edit"){echo "value=\"".$ar['shop_currency_code_local']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CURRENCY_CONVERSION."</strong><br>"._SHOP_CURRENCY_CONVERSION_HELP."</td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"currency_conversion\" maxlength=\"15\" size=\"18\" "; if ($_GET['action'] == "shop_currency_edit"){echo "value=\"".$ar['shop_currency_conversion']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\"><strong>"._SHOP_CURRENCY_MAIN."</strong><br>"._SHOP_CURRENCY_MAIN_HELP."</td>\n";
	echo "		<td align=\"left\"><input type=\"checkbox\" name=\"currency_main\" value=\"1\" "; if ($ar['shop_currency_main'] == 1){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" align=\"right\">&nbsp;</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "shop_currency_add" || $action == "shop_currency_add"){echo _SHOP_CURRENCY_ADD;} else {echo _SHOP_CURRENCY_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CURRENCY_CODE."</span></td>\n";
	echo "		<td width=\"120\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CURRENCY_CODE_LOCAL."</span></td>\n";
	echo "		<td width=\"70\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CURRENCY_CONVERSION."</span></td>\n";
	echo "		<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CURRENCY_MAIN."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CURRENCY_TITLE."</span></td>\n";
	echo "	</tr>\n";
	$res = mysql_query("SELECT * FROM $db_shop_currency ORDER BY shop_currency_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar = mysql_fetch_array($res)){
		echo "	<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
		echo "		<td width=\"80\" align=\"center\">\n";
					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=shop_currency_edit&amp;id=".$ar['shop_currency_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop_setup.php?action=shop_currency_del&amp;id=".$ar['shop_currency_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "		</td>\n";
		echo "		<td width=\"20\" align=\"left\">".$ar['shop_currency_id']."</td>\n";
		echo "		<td width=\"20\" align=\"left\">".$ar['shop_currency_code']."</td>\n";
		echo "		<td width=\"120\" align=\"center\">".$ar['shop_currency_code_local']."</td>\n";
		echo "		<td width=\"70\" align=\"left\">".$ar['shop_currency_conversion']."</td>\n";
		echo "		<td width=\"30\" align=\"left\">"; if ($ar['shop_currency_main'] == 1){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "		<td align=\"left\">".$ar['shop_currency_name']."</td>\n";
		echo "	</tr>";
 	}
	echo "</table>";
}

include ("inc.header.php");
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_view") <> 1) { 
		echo _NOTENOUGHPRIV;
		exit;
	} else {
		if ($_GET['action'] == "") {ShopSetup();}
		if ($_GET['action'] == "shop_setup") {ShopSetup();}
		if ($_GET['action'] == "shop_setup_update") {ShopSetup();}
		if ($_GET['action'] == "add_tax_class") {TaxClass();}
		if ($_GET['action'] == "edit_tax_class") {TaxClass();}
		if ($_GET['action'] == "del_tax_class") {TaxClass();}
		if ($_GET['action'] == "add_tax_rates") {TaxRates();}
		if ($_GET['action'] == "edit_tax_rates") {TaxRates();}
		if ($_GET['action'] == "del_tax_rates") {TaxRates();}
		if ($_GET['action'] == "add_tax_zones") {TaxZones();}
		if ($_GET['action'] == "edit_tax_zones") {TaxZones();}
		if ($_GET['action'] == "del_tax_zones") {TaxZones();}
		if ($_GET['action'] == "add_carriage") {Carriage();}
		if ($_GET['action'] == "edit_carriage") {Carriage();}
		if ($_GET['action'] == "del_carriage") {Carriage();}
		if ($_GET['action'] == "add_carriage_category") {CarriageCategory();}
		if ($_GET['action'] == "edit_carriage_category") {CarriageCategory();}
		if ($_GET['action'] == "del_carriage_category") {CarriageCategory();}
		if ($_GET['action'] == "shop_currency_add") {Currency();}
		if ($_GET['action'] == "shop_currency_edit") {Currency();}
		if ($_GET['action'] == "shop_currency_del") {Currency();}
		if ($_GET['action'] == "add_payment") {PaymentMethods();}
		if ($_GET['action'] == "edit_payment") {PaymentMethods();}
		if ($_GET['action'] == "del_payment") {PaymentMethods();}
		if ($_GET['action'] == "add_gender") { Gender(); }
		if ($_GET['action'] == "edit_gender") { Gender(); }
		if ($_GET['action'] == "del_gender") { Gender(); }
	}
include ("inc.footer.php");