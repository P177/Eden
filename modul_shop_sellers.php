<?php
/***********************************************************************************************************
*																											
*		MENU																								
*																											
***********************************************************************************************************/
function Menu(){
	
	global $eden_cfg;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$menu = "<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">
				<a href=\"modul_shop_setup.php?action=shop_setup&amp;project=".$_SESSION['project']."\">"._SHOP_SETUP."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop_sellers.php?action=sellers_stat&amp;project=".$_SESSION['project']."\">"._SHOP_SELLERS_STAT."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop_sellers.php?action=sellers&amp;project=".$_SESSION['project']."\">"._SHOP_SELLERS."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop_sellers.php?action=sellers_waiting&amp;project=".$_SESSION['project']."\">"._SHOP_SELLERS_WAITING."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop_sellers.php?action=discount_cats&amp;project=".$_SESSION['project']."\">"._SHOP_DISCOUNT_CATS."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop_sellers.php?action=discount_cat_add&amp;project=".$_SESSION['project']."\">"._SHOP_DISCOUNT_CAT_ADD."</a>";
	return $menu;
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI STATISTIK																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_shop_sellers;
	
	$res = mysql_query("SELECT COUNT(*) FROM $db_shop_sellers WHERE shop_seller_active=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$res1 = mysql_query("SELECT COUNT(*) FROM $db_shop_sellers WHERE shop_seller_active=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar1 = mysql_fetch_array($res1);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_SELLERS; if ($_GET['action'] == "sellers_waiting"){echo " "._SHOP_SELLERS_WAITING_ACC;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "<tr valign=\"top\">";
	echo "	<td width=\"90%\" valign=\"top\" colspan=\"2\">";
	echo "		<br>";
			if (empty($ar1[0])) {$ar1[0]=0;}
	echo "			<br>\n";
	echo "			<table cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#DDDDDD\" align=\"center\">\n";
	echo "				<tr bgcolor=\"#EEEEEE\">\n";
	echo "					<td><strong>"._STAT_STATISTICS."</strong></td>\n";
	echo "					<td><strong>"._STAT_NEWS."</strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._SHOP_STAT_SELLERS_SELLERS.":</td>\n";
	echo "					<td align=\"right\"><strong><a href=\"modul_shop_sellers.php?action=sellers&amp;project=".$_SESSION['project']."\">".$ar[0]."</a></strong></td>\n";
	echo "				</tr>\n";
	echo "				<tr bgcolor=\"#FFFFFF\">\n";
	echo "					<td>"._SHOP_STAT_SELLERS_WAITING.":</td>\n";
	echo "					<td align=\"right\"><strong><a href=\"modul_shop_sellers.php?action=sellers_waiting&amp;project=".$_SESSION['project']."\">".$ar1[0]."</a></strong></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		SHOW SELLERS																						
*																											
***********************************************************************************************************/
function Sellers(){
	
	global $db_admin,$db_shop_sellers;
	global $eden_cfg;
	
	if ($_GET['action'] == "") {$_GET['action'] = "sellers_waiting";}
	
	// Provereni opravneni
	if ($_GET['action'] == "sellers_edit" || $_GET['action'] == "sellers_waiting"){
		if (CheckPriv("groups_shop_edit") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "sellers_del"){
		if (CheckPriv("groups_shop_del") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	} elseif ($_GET['action'] == "sellers") {
		
	} else {
		 echo _NOTENOUGHPRIV;Products();exit;
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_SELLERS; if ($_GET['action'] == "sellers_waiting"){echo " "._SHOP_SELLERS_WAITING_ACC;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td class=\"nadpis-boxy\" width=\"80\">"._CMN_OPTIONS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"30\">"._CMN_ID."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._CMN_IMAGE."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._NAME."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_MAN_URL."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_MAN_URL_CLICKED."</td>\n";
	echo "	</tr>";
	
	// Nastaveni WHERE pro zobrazeni neaktivovanych prodejcu
	if ($_GET['action'] == "sellers_waiting"){$where = "WHERE shop_seller_active=0";} else {$where = "WHERE shop_seller_active=1";}
	
	$res_sellers = mysql_query("SELECT * FROM $db_shop_sellers $where ORDER BY shop_seller_company_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while ($ar_sellers = mysql_fetch_array($res_sellers)){
		$seller_name = PrepareFromDB($ar_sellers['shop_seller_company_name']);
		$seller_web = PrepareFromDB($ar_sellers['shop_seller_web']);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\">";
		echo "		<a href=\"modul_shop_sellers.php?action=sellers_edit&amp;sid=".$ar_sellers['shop_seller_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
		echo "		<a href=\"modul_shop_sellers.php?action=sellers_del&amp;sid=".$ar_sellers['shop_seller_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";
		echo "	</td> \n";
		echo "	<td width=\"30\">".$ar_sellers['shop_seller_id']."</td>\n";
		echo "	<td width=\"100\">"; if ($ar['shop_manufacturers_image'] != ""){echo "<img src=\"".$url_shop_man.$ar['shop_manufacturers_image']."\">";} echo "</td>";
		echo "	<td>".$seller_name."</td>\n";
		echo "	<td>".$seller_web."</td>\n";
		echo "	<td><a href=\"sys_save.php?action=shop_seller_act&amp;sid=".$ar_sellers['shop_seller_id']."&amp;project=".$_SESSION['project']."\">"._ADMIN_SUBMIT_ALLOW_ACT_AND_SEND_EMAIL."</a></td>\n";
		echo "</tr>";
		$i++;
	 }
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ADD SELLERS																							
*																											
***********************************************************************************************************/
function SellersAdd(){
	
	global $db_shop_sellers,$db_admin,$db_country;
	global $eden_cfg;
	
	// Provereni opravneni
	if ($_GET['action'] == "sellers_add"){
		if (CheckPriv("groups_shop_add") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "sellers_edit"){
		if (CheckPriv("groups_shop_edit") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "sellers_del"){
		if (CheckPriv("groups_shop_del") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	} elseif ($_GET['action'] == "sellers") {
		
	} else {
		 echo _NOTENOUGHPRIV;Products();exit;
	}
	
	if ($_GET['action'] == "sellers_edit"){
		$res_sellers = mysql_query("SELECT ss.*, a.admin_firstname, a.admin_id, a.admin_name, a.admin_email 
		FROM $db_shop_sellers AS ss 
		JOIN $db_admin AS a ON a.admin_id=ss.shop_seller_admin_id 
		WHERE ss.shop_seller_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_sellers = mysql_fetch_array($res_sellers);
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_SELLERS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><h2>"._SHOP_SELLERS_BASIC_INFO."</h2></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>ID</strong></td>\n";
	echo "		<td align=\"left\">".$ar_sellers['shop_seller_id']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><form enctype=\"multipart/form-data\" action=\"sys_save.php?action="; if ($_GET['action'] == "sellers_add"){echo "sellers_add";} else {echo "sellers_edit";} echo "\" method=\"post\" name=\"forma\"><strong>"._SHOP_SELLERS_COMPANYNAME."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_company_name\" maxlength=\"50\" value=\"".$ar_sellers['shop_seller_company_name']."\" size=\"50\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_NAME."</strong></td>\n";
	echo "		<td align=\"left\"><a href=\"sys_admin.php?action=admins&from=search&show_status=user&search_by=admin_id&search_this=".$ar_sellers["admin_id"]."&project=".$_SESSION['project']."\">".$ar_sellers['admin_firstname']." ".$ar_sellers['admin_name']."</a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_POSITION."</strong></td>\n";
	echo "		<td align=\"left\">".$ar_sellers['shop_seller_position']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_EMAIL."</strong></td>\n";
	echo "		<td align=\"left\">".$ar_sellers['admin_email']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_WEB."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_web\" maxlength=\"40\" value=\"".$ar_sellers['shop_seller_web']."\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_VAT_NUMBER."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_web\" maxlength=\"40\" value=\"".$ar_sellers['shop_seller_vat_number']."\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_PHONE_1."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_phone_1\" maxlength=\"20\" value=\"".$ar_sellers['shop_seller_phone_1']."\" size=\"20\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_PHONE_2."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_phone_2\" maxlength=\"20\" value=\"".$ar_sellers['shop_seller_phone_2']."\" size=\"20\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_MOBILE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_mobile\" maxlength=\"20\" value=\"".$ar_sellers['shop_seller_mobile']."\" size=\"20\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_FAX."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_fax\" maxlength=\"20\" value=\"".$ar_sellers['shop_seller_fax']."\" size=\"20\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><h2>"._SHOP_SELLERS_DELIVERY_ADDRESS."</h2></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_DELIVERY_ADDRESS_1."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_delivery_address_1\" maxlength=\"80\" value=\"".$ar_sellers['shop_seller_delivery_address_1']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_DELIVERY_ADDRESS_2."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_delivery_address_2\" maxlength=\"80\" value=\"".$ar_sellers['shop_seller_delivery_address_2']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_DELIVERY_POSTCODE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_delivery_postcode\" maxlength=\"11\" value=\"".$ar_sellers['shop_seller_delivery_postcode']."\" size=\"10\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_DELIVERY_CITY."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_delivery_city\" maxlength=\"40\" value=\"".$ar_sellers['shop_seller_delivery_city']."\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_DELIVERY_COUNTRY."</strong></td>\n";
	echo "		<td align=\"left\">";
	echo "			<select name=\"seller_delivery_country\" class=\"input\">\n";
						$res_country = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_country = mysql_fetch_array($res_country)){
							echo "<option value=\"".$ar_country['country_id']."\" "; if ($ar_sellers['shop_seller_delivery_country_id'] == $ar_country['country_id']) {echo " selected";} echo ">".$ar_country['country_name']."</option>\n";
						}
	echo "			</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><h2>"._SHOP_SELLERS_INVOICE_ADDRESS."</h2></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_INVOICE_ADDRESS_1."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_invoice_address_1\" maxlength=\"80\" value=\"".$ar_sellers['shop_seller_invoice_address_1']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_INVOICE_ADDRESS_2."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_invoice_address_2\" maxlength=\"80\" value=\"".$ar_sellers['shop_seller_invoice_address_2']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_INVOICE_POSTCODE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_invoice_postcode\" maxlength=\"11\" value=\"".$ar_sellers['shop_seller_invoice_postcode']."\" size=\"10\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_INVOICE_CITY."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"seller_invoice_city\" maxlength=\"40\" value=\"".$ar_sellers['shop_seller_invoice_city']."\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_SELLERS_INVOICE_COUNTRY."</strong></td>\n";
	echo "		<td align=\"left\">";
	echo "			<select name=\"seller_invoice_country\" class=\"input\">\n";
						$res_country = mysql_query("SELECT * FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_country = mysql_fetch_array($res_country)){
							echo "<option value=\"".$ar_country['country_id']."\" "; if ($ar_sellers['shop_seller_invoice_country_id'] == $ar_country['country_id']) {echo " selected";} echo ">".$ar_country['country_name']."</option>\n";
						}
	echo "			</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "sellers_add"){echo _SHOP_SELLERS_ADD;} else {echo _SHOP_SELLERS_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
}
/***********************************************************************************************************
*																											
*		SHOW SELLERS																						
*																											
***********************************************************************************************************/
function DiscountCats(){
	
	global $db_shop_discount_category;
	global $eden_cfg;
	
	// Provereni opravneni
	if ($_GET['action'] == "discount_cats" || $_GET['action'] == "discount_cat_open" || $_GET['action'] == "discount_cat_close"){
		if (CheckPriv("groups_shop_disc_cat_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		 echo _NOTENOUGHPRIV;exit;
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_DISCOUNT_CATS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td class=\"nadpis-boxy\" width=\"80\">"._CMN_OPTIONS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"30\">"._CMN_ID."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_NAME."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_TYPE."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_SUB."</td>\n";
	echo "	</tr>";
	$res_disc = mysql_query("SELECT * FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 ORDER BY shop_discount_category_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;//groups_shop_setup_edit
	while ($ar_disc = mysql_fetch_array($res_disc)){
		$res1 = mysql_query("SELECT COUNT(*) FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=".$ar_disc['shop_discount_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_fetch_row($res1);
		// Nastaveni otvirani podmenu a rozbalovani pomoci znamenek plus a minus
		if ($_GET['action'] == "discount_cat_open" && $_GET['id'] == $ar_disc['shop_discount_category_id']) {$command = "discount_cat_close";} elseif ($_GET['action'] == "discount_cat_close" && $close != 0 && $_GET['id'] == $ar_disc['shop_discount_category_id']){$command = "discount_cat_close";} else {$command = "discount_cat_open";}
		$discount_name = PrepareFromDB($ar_disc['shop_discount_category_name']);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\">";
			if (CheckPriv("groups_shop_disc_cat_edit") == 1){echo " <a href=\"modul_shop_sellers.php?action=discount_cat_edit&amp;id=".$ar_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
			if (CheckPriv("groups_shop_disc_cat_del") == 1){echo " <a href=\"modul_shop_sellers.php?action=discount_cat_del&amp;id=".$ar_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			if (CheckPriv("groups_shop_disc_cat_add") == 1){echo " <a href=\"modul_shop_sellers.php?action=discount_cat_add_sub&amp;pid=".$ar_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_ADD."\"></a>";}
		echo "	</td> \n";
		echo "	<td width=\"30\" align=\"right\">".$ar_disc['shop_discount_category_id']."</td>\n";
	   	echo "	<td>&nbsp;<strong><a href=\"modul_shop_sellers.php?action=".$command."&amp;id=".$ar_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\">".$discount_name."</a></strong></td>\n";
		echo "	<td width=\"150\" align=\"left\">";
				switch ($ar_disc['shop_discount_category_type']){
					case "1":
						echo _SHOP_DISCOUNT_CAT_TYPE_1;
					break;
					case "2":
						echo _SHOP_DISCOUNT_CAT_TYPE_2;
					break;
					case "3":
						echo _SHOP_DISCOUNT_CAT_TYPE_3;
					break;
					case "4":
						echo _SHOP_DISCOUNT_CAT_TYPE_4;
					break;
					case "31":
						echo _SHOP_DISCOUNT_CAT_TYPE_31;
					break;
					case "32":
						echo _SHOP_DISCOUNT_CAT_TYPE_32;
					break;
					case "33":
						echo _SHOP_DISCOUNT_CAT_TYPE_33;
					break;
					case "34":
						echo _SHOP_DISCOUNT_CAT_TYPE_34;
					break;
				}
		echo "	</td>\n";
		echo "	<td width=\"100\" align=\"right\">".$num[0]."</td>\n";
		echo "</tr>";
		echo "<tr>\n";
		echo "	<td colspan=\"4\">\n";
		//***********************************************************************************
		// Podmenu
		//***********************************************************************************
		if ($_GET['action'] == "discount_cat_open" && $_GET['id'] == $ar_disc['shop_discount_category_id']) {
 			$res2_disc = mysql_query("SELECT * FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=".$ar_disc['shop_discount_category_id']." ORDER BY shop_discount_category_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num2_disc = mysql_num_rows($res2_disc);
			$a = 1;
			echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "	<tr bgcolor=\"#DCE3F1\">\n";
			echo "		<td class=\"nadpis-boxy\" width=\"80\">"._CMN_OPTIONS."</td>\n";
			echo "		<td class=\"nadpis-boxy\" width=\"30\">"._CMN_ID."</td>\n";
			echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_NAME."</td>\n";
			echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_TYPE."</td>\n";
			echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_VALUE."</td>\n";
			echo "		<td class=\"nadpis-boxy\">"._SHOP_DISCOUNT_CAT_DISCOUNT_PRICE."</td>\n";
			echo "	</tr>";
			while ($ar2_disc = mysql_fetch_array($res2_disc)){
				$discount_name = PrepareFromDB($ar2_disc['shop_discount_category_name']);
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
				$ar2_disc['shop_discount_category_discounted_date_start'];
				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"80\">";
					if (CheckPriv("groups_shop_disc_cat_edit") == 1){echo "<a href=\"modul_shop_sellers.php?action=discount_cat_edit_sub&amp;id=".$ar2_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_shop_disc_cat_del") == 1){echo "<a href=\"modul_shop_sellers.php?action=discount_cat_del_sub&amp;id=".$ar2_disc['shop_discount_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
				echo "	</td>\n";
				echo "	<td width=\"30\">".$ar2_disc['shop_discount_category_id']."</td>\n";
			   	echo "	<td>&nbsp;<strong>".$discount_name."</strong></td>\n";
				echo "	<td>";
						switch ($ar2_disc['shop_discount_category_type']){
							case "1":
								echo _SHOP_DISCOUNT_CAT_TYPE_1;
								$discount_value = "-";
							break;
							case "2":
								echo _SHOP_DISCOUNT_CAT_TYPE_2;
								$discount_value = $ar2_disc['shop_discount_category_discounted_from_amount']." - ".$ar2_disc['shop_discount_category_discounted_to_amount'];
							break;
							case "3":
								echo _SHOP_DISCOUNT_CAT_TYPE_3;
								$discount_value = $ar2_disc['shop_discount_category_pricerange_from']." - ".$ar2_disc['shop_discount_category_pricerange_to'];
							break;
							case "4":
								echo _SHOP_DISCOUNT_CAT_TYPE_4;
								$discount_value = $ar2_disc['shop_discount_category_discounted_date_start']." - ".$ar2_disc['shop_discount_category_discounted_date_end'];
							break;
							case "31":
								echo _SHOP_DISCOUNT_CAT_TYPE_31;
								$discount_value = "-";
							break;
							case "32":
								echo _SHOP_DISCOUNT_CAT_TYPE_32;
								$discount_value = $ar2_disc['shop_discount_category_discounted_from_amount']." - ".$ar2_disc['shop_discount_category_discounted_to_amount'];
							break;
							case "33":
								echo _SHOP_DISCOUNT_CAT_TYPE_33;
								$discount_value = $ar2_disc['shop_discount_category_pricerange_from']." - ".$ar2_disc['shop_discount_category_pricerange_to'];
							break;
							case "34":
								echo _SHOP_DISCOUNT_CAT_TYPE_34;
								$discount_value = $ar2_disc['shop_discount_category_discounted_date_start']." - ".$ar2_disc['shop_discount_category_discounted_date_end'];
							break;
						}
				echo "	</td>\n";
				echo "	<td align=\"right\">".$discount_value."</td>\n";
				echo "	<td align=\"right\">".PriceFormat($ar2_disc['shop_discount_category_discount_price'])."</td>\n";
				echo "</tr>";
			}
			echo "		</table>";
			echo "	</td>";
			echo "</tr>";
		}
		$i++;
	 }
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ADD DISCOUNT CATEGORY																				
*																											
***********************************************************************************************************/
function DiscountCatAdd(){
	
	global $db_shop_discount_category,$db_shop_setup,$db_shop_currency;
	global $eden_cfg;
	
	$res_shop_setup = mysql_query("SELECT ss.shop_setup_currency, sc.shop_currency_code_local FROM $db_shop_setup AS ss JOIN $db_shop_currency AS sc ON sc.shop_currency_code=ss.shop_setup_currency") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_shop_setup = mysql_fetch_array($res_shop_setup);
	
	if ($_GET['action'] == "discount_cat_edit" || $_GET['action'] == "discount_cat_edit_sub"){
		$res_discount = mysql_query("SELECT * FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_discount = mysql_fetch_array($res_discount);
		$pid = $ar_discount['shop_discount_category_parent_id'];
	} elseif ( $_GET['action'] == "discount_cat_add_sub"){
		$res_discount = mysql_query("SELECT shop_discount_category_type FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_discount = mysql_fetch_array($res_discount);
		$pid = $_GET['pid'];
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_DISCOUNT_CATS." - "; if ($_GET['action'] == "discount_cat_edit") { echo _CMN_EDIT;} else {echo _CMN_ADD;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><form enctype=\"multipart/form-data\" action=\"sys_save.php?action=".$_GET['action']."\" method=\"post\" name=\"forma\"><strong>"._SHOP_DISCOUNT_CAT_NAME."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"discount_cat_name\" maxlength=\"100\" value=\"".$ar_discount['shop_discount_category_name']."\" size=\"30\"></td>\n";
	echo "	</tr>\n";
	/* Zobrazime jen pokud pridavame nebo editiujeme podkategorii */
	if ($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub"){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_PARENT_NAME."</strong></td>\n";
		echo "		<td align=\"left\">";
					$res_discount_parent = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"discount_cat_parent\" size=\"1\">";
					while ($ar_discount_parent = mysql_fetch_array($res_discount_parent)){
						echo "<option value=\"".$ar_discount_parent['shop_discount_category_id']."\""; if ($ar_discount_parent['shop_discount_category_id'] == $pid){echo " selected";}  echo ">".$ar_discount_parent['shop_discount_category_name']."</option>";
					}
					echo "</select>";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
   	echo "	<tr>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_edit"){
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_STATUS."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"checkbox\" name=\"discount_cat_status\" value=\"1\" "; if ($ar_discount['shop_discount_category_status'] == 1) {echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	} else {
		echo "<input type=\"hidden\" name=\"discount_cat_status\" value=\"1\">\n";
	}
	if ($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub"){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNT_PRICE."</strong></td>\n";
		echo "		<td align=\"left\"><input type=\"text\" name=\"discount_cat_discount_price\" maxlength=\"15\" value=\"".$ar_discount['shop_discount_category_discount_price']."\" size=\"5\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>&nbsp;</td>\n";
		echo "		<td>&nbsp;</td>\n";
		echo "	</tr>\n";
	}
	/* Zobrazime jen pokud pridavame nebo editiujeme hlavni kategorii */
	if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_edit"){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_TYPE."</strong></td>\n";
		echo "		<td align=\"left\">";
		echo "			<select name=\"discount_cat_type\" class=\"input\">\n";
		echo "				<option value=\"1\" "; if ($ar_discount['shop_discount_category_type'] == 1) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_1."</option>\n";
		echo "				<option value=\"2\" "; if ($ar_discount['shop_discount_category_type'] == 2) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_2."</option>\n";
		echo "				<option value=\"3\" "; if ($ar_discount['shop_discount_category_type'] == 3) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_3."</option>\n";
		echo "				<option value=\"4\" "; if ($ar_discount['shop_discount_category_type'] == 4) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_4."</option>\n";
		echo "				<option value=\"31\" "; if ($ar_discount['shop_discount_category_type'] == 31) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_31."</option>\n";
		echo "				<option value=\"32\" "; if ($ar_discount['shop_discount_category_type'] == 32) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_32."</option>\n";
		echo "				<option value=\"33\" "; if ($ar_discount['shop_discount_category_type'] == 33) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_33."</option>\n";
		echo "				<option value=\"34\" "; if ($ar_discount['shop_discount_category_type'] == 34) {echo " selected";} echo ">"._SHOP_DISCOUNT_CAT_TYPE_34."</option>\n";
		echo "			</select>";
		echo "		</td>\n";
		echo "	</tr>\n";
	} else {
		echo "<input type=\"hidden\" name=\"discount_cat_type\" value=\"".$ar_discount['shop_discount_category_type']."\">\n";
	}
	/* Zobrazime jen u podkategorii - dany typ slevove kategorie podle vyberu v hlavni kategorii 
		1 a 31 - Standart - Kombinace rozsahu mnozstvi, ceny a data
		2 a 32 - Mnozstvi
		3 a 33 - Cena
		4 a 34 - Datum
	*/
	if (($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub") && ($ar_discount['shop_discount_category_type'] == 1 || $ar_discount['shop_discount_category_type'] == 31 || $ar_discount['shop_discount_category_type'] == 2 || $ar_discount['shop_discount_category_type'] == 32)){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_FROM_AMOUNT."</strong></td>\n";
		echo "		<td align=\"left\"><input type=\"text\" name=\"discount_cat_disc_from_amount\" maxlength=\"15\" value=\"".$ar_discount['shop_discount_category_discounted_from_amount']."\" size=\"10\">"._SHOP_DISCOUNT_CAT_DISCOUNTED_FROM_PCS."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_TO_AMOUNT."</strong></td>\n";
		echo "		<td align=\"left\"><input type=\"text\" name=\"discount_cat_disc_to_amount\" maxlength=\"15\" value=\"".$ar_discount['shop_discount_category_discounted_to_amount']."\" size=\"10\">"._SHOP_DISCOUNT_CAT_DISCOUNTED_TO_PCS."</td>\n";
		echo "	</tr>\n";
	} else {
		echo "<input type=\"hidden\" name=\"discount_cat_disc_from_amount\" value=\"0\">\n";
		echo "<input type=\"hidden\" name=\"discount_cat_disc_to_amount\" value=\"0\">\n";
	}
	if (($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub") && ($ar_discount['shop_discount_category_type'] == 1 || $ar_discount['shop_discount_category_type'] == 31 || $ar_discount['shop_discount_category_type'] == 3 || $ar_discount['shop_discount_category_type'] == 33)){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_FROM_VALUE."</strong></td>\n";
		echo "		<td align=\"left\">";
		echo "		<input type=\"text\" name=\"discount_cat_disc_price_from\" maxlength=\"17\" value=\"".$ar_discount['shop_discount_category_discounted_from_vlue']."\" size=\"10\">".$ar_shop_setup['shop_currency_code_local'];
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_TO_VALUE."</strong></td>\n";
		echo "		<td align=\"left\">";
		echo "		<input type=\"text\" name=\"discount_cat_disc_price_to\" maxlength=\"17\" value=\"".$ar_discount['shop_discount_category_discounted_from_vlue']."\" size=\"10\">".$ar_shop_setup['shop_currency_code_local'];
		echo "		</td>\n";
		echo "	</tr>\n";
	} else {
		echo "<input type=\"hidden\" name=\"discount_cat_disc_price_from\" value=\"0\">\n";
		echo "<input type=\"hidden\" name=\"discount_cat_disc_price_to\" value=\"0\">\n";
	}
	if (($_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub") && ($ar_discount['shop_discount_category_type'] == 1 || $ar_discount['shop_discount_category_type'] == 31 || $ar_discount['shop_discount_category_type'] == 4 || $ar_discount['shop_discount_category_type'] == 34)){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_DATE_START."</strong></td>\n";
		echo "		<td>\n";
		echo "			<script language=\"javascript\">\n";
						if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub"){
							$disc_date_start = formatTimeS(time());
							echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"discount_cat_disc_date_start\", \"btnDate1\",\"".$disc_date_start[1].".".$disc_date_start[2].".".$disc_date_start[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						} else {
							$disc_date_start = $ar_discount['shop_discount_category_discounted_date_start'];
							echo "var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"discount_cat_disc_date_start\", \"btnDate1\",\"".$disc_date_start[8].$disc_date_start[9].".".$disc_date_start[5].$disc_date_start[6].".".$disc_date_start[0].$disc_date_start[1].$disc_date_start[2].$disc_date_start[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						}
		echo "			</script>\n";
		echo "			<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNTED_DATE_END."</strong></td>\n";
		echo "		<td>\n";
		echo "			<script language=\"javascript\">\n";
						if ($_GET['action'] == "discount_cat_add" || $_GET['action'] == "discount_cat_add_sub" || $_GET['action'] == "discount_cat_edit_sub"){
							$disc_date_end = formatTimeS(time());
							echo "var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"forma\", \"discount_cat_disc_date_end\", \"btnDate2\",\"".$disc_date_end[1].".".$disc_date_end[2].".".$disc_date_end[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						} else {
							$disc_date_end = $ar_discount['shop_discount_category_discounted_date_end'];
							echo "var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"forma\", \"discount_cat_disc_date_end\", \"btnDate2\",\"".$disc_date_end[8].$disc_date_end[9].".".$disc_date_end[5].$disc_date_end[6].".".$disc_date_end[0].$disc_date_end[1].$disc_date_end[2].$disc_date_end[3]."\",scBTNMODE_CUSTOMBLUE);\n";
						}
		echo "			</script>\n";
		echo "			<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	} else {
		echo "<input type=\"hidden\" name=\"shop_discount_category_discounted_date_start\" value=\"0000-00-00\">\n";
		echo "<input type=\"hidden\" name=\"shop_discount_category_discounted_date_end\" value=\"0000-00-00\">\n";
	}
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "discount_cat_add"){echo _SHOP_DISCOUNT_CAT_ADD;} elseif ($_GET['action'] == "discount_cat_add_sub") {echo _SHOP_DISCOUNT_CAT_ADD_SUB;} elseif ($_GET['action'] == "discount_cat_edit_sub") {echo _SHOP_DISCOUNT_CAT_EDIT_SUB;} else {echo _SHOP_DISCOUNT_CAT_EDIT;} echo "\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}
/***********************************************************************************************************
*																											
*		ADD DISCOUNT CATEGORY																				
*																											
***********************************************************************************************************/
function DiscountCatDel(){
	
	global $db_shop_discount_category,$db_shop_setup,$db_shop_currency;
	global $eden_cfg;
	
	$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name, shop_discount_category_discount_price, shop_discount_category_name, shop_discount_category_parent_id FROM $db_shop_discount_category WHERE shop_discount_category_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_discount = mysql_fetch_array($res_discount);
	if ($_GET['action'] == "discount_cat_del_sub"){
		$res_discount_parent = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_id=".$ar_discount['shop_discount_category_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_discount_parent = mysql_fetch_array($res_discount_parent);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_DISCOUNT_CATS." - "._CMN_DEL."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".Menu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><form enctype=\"multipart/form-data\" action=\"sys_save.php?action=".$_GET['action']."\" method=\"post\" name=\"forma\"><strong>ID</strong></td>\n";
	echo "		<td align=\"left\" width=\"699\">".$ar_discount['shop_discount_category_id']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_NAME."</strong></td>\n";
	echo "		<td align=\"left\">".$ar_discount['shop_discount_category_name']."</td>\n";
	echo "	</tr>\n";
	if ($_GET['action'] == "discount_cat_del_sub"){
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_PARENT_NAME."</strong></td>\n";
		echo "		<td align=\"left\">".$ar_discount_parent['shop_discount_category_name']."<input type=\"hidden\" name=\"discount_cat_parent\" value=\"".$ar_discount_parent['shop_discount_category_id']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" width=\"150\"><strong>"._SHOP_DISCOUNT_CAT_DISCOUNT_PRICE."</strong></td>\n";
		echo "		<td align=\"left\">".$ar_discount['shop_discount_category_discount_price']."</td>\n";
		echo "	</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\""._SHOP_DISCOUNT_CAT_DEL."\" class=\"eden_button_no\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}
include "inc.header.php";
	if ($_GET['action'] == "") { Showmain(); }
	if ($_GET['action'] == "sellers_stat") { Showmain(); }
	if ($_GET['action'] == "sellers") { Sellers(); }
	if ($_GET['action'] == "sellers_waiting") {Sellers();}
	if ($_GET['action'] == "sellers_edit") {SellersAdd();}
	if ($_GET['action'] == "sellers_del") {SellersDel();}
	if ($_GET['action'] == "discount_cats") {DiscountCats();}
	if ($_GET['action'] == "discount_cat_add") {DiscountCatAdd();}
	if ($_GET['action'] == "discount_cat_add_sub") {DiscountCatAdd();}
	if ($_GET['action'] == "discount_cat_edit") {DiscountCatAdd();}
	if ($_GET['action'] == "discount_cat_edit_sub") {DiscountCatAdd();}
	if ($_GET['action'] == "discount_cat_del") {DiscountCatDel();}
	if ($_GET['action'] == "discount_cat_del_sub") {DiscountCatDel();}
	if ($_GET['action'] == "discount_cat_open") {DiscountCats();}
	if ($_GET['action'] == "discount_cat_close") {DiscountCats();}
include "inc.footer.php";