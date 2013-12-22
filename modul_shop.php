<?php
/***********************************************************************************************************
*																											
*		MENU																								
*																											
***********************************************************************************************************/
function ShopMenu(){
	
	global $eden_cfg;
	
	$menu = "<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">
				<a href=\"modul_shop_setup.php?action=shop_setup&amp;project=".$_SESSION['project']."\">"._SHOP_SETUP."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop.php?action=add_man&amp;project=".$_SESSION['project']."\">"._SHOP_MAN."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop.php?action=add_prod&amp;project=".$_SESSION['project']."\">"._SHOP_PROD_ADD."</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop.php?action=&amp;project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
				if ($_GET['action'] == "clothes_show_designs"){$add_design = "<a href=\"modul_shop.php?action=clothes_add_design&amp;project=".$_SESSION['project']."\">"._SHOP_CL_DESIGN_ADD_DESIGN."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
				if ($eden_cfg['modul_shop_clothes'] == 1){ $menu .= "<br><br><a href=\"modul_shop.php?action=clothes_show_designs&amp;project=".$_SESSION['project']."\">"._SHOP_CL_DESIGNS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;".$add_design."<a href=\"modul_shop.php?action=clothes_add_style_parents&amp;project=".$_SESSION['project']."\">"._SHOP_CL_STYLE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop.php?action=clothes_add_color&amp;project=".$_SESSION['project']."\">"._SHOP_CL_COLORS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_shop.php?action=clothes_add_size&amp;project=".$_SESSION['project']."\">"._SHOP_CL_SIZE_SIZES."</a>"; }
				if ($_GET['sys_save_message'] != ""){ $menu .= "<br><br><span class=\"red\" style=\"font-weight: bold;\">".htmlspecialchars_decode(urldecode($_GET['sys_save_message']),ENT_QUOTES)."</span>";}
	return $menu;
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI STATISTIK OBCHODU																			
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_category,$db_shop_product,$db_shop_product_changes,$db_admin,$db_admin_info,$db_shop_man,$db_shop_orders,$db_shop_clothes_design;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP_STAT."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"99%\" cellspacing=\"1\" cellpadding=\"5\">\n";
	echo "				<tr valign=\"top\">\n";
	echo "					<td width=\"90%\" valign=\"top\" colspan=\"2\"><br>";
							$res = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(float)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar = mysql_fetch_array($res);
							$res1 = mysql_query("SELECT COUNT(*) FROM $db_shop_product") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar1 = mysql_fetch_array($res1);
							$res2 = mysql_query("SELECT COUNT(*) FROM $db_shop_man") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar2 = mysql_fetch_array($res2);
							$res_orders = mysql_query("SELECT COUNT(*) FROM $db_shop_orders") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders = mysql_fetch_array($res_orders);
							$res_orders_in_progres = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_in_progres = mysql_fetch_array($res_orders_in_progres);
							$res_orders_pending = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_pending = mysql_fetch_array($res_orders_pending);
							$res_orders_paid = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=3") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_paid = mysql_fetch_array($res_orders_paid);
							$res_orders_picked = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=4") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_picked = mysql_fetch_array($res_orders_picked);
							$res_orders_despatched = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=5") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_despatched = mysql_fetch_array($res_orders_despatched);
							$res_orders_canceled_before = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=6") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_canceled_before = mysql_fetch_array($res_orders_canceled_before);
							$res_orders_canceled_after = mysql_query("SELECT COUNT(*) FROM $db_shop_orders WHERE shop_orders_orders_status=7") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_orders_canceled_after = mysql_fetch_array($res_orders_canceled_after);
							$res_clothes_designes = mysql_query("SELECT COUNT(*) FROM $db_shop_clothes_design") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_clothes_designes = mysql_fetch_array($res_clothes_designes);
							$res_clothes_designes_active = mysql_query("SELECT COUNT(*) FROM $db_shop_clothes_design WHERE shop_clothes_design_show=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_clothes_designes_active = mysql_fetch_array($res_clothes_designes_active);
							$res_clothes_designes_inactive = mysql_query("SELECT COUNT(*) FROM $db_shop_clothes_design WHERE shop_clothes_design_show=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_clothes_designes_inactive = mysql_fetch_array($res_clothes_designes_inactive);
							
							if (empty($ar1[0])) {$ar1[0]=0;}
							if (empty($ar2[0])) {$ar2[0]=0;}
							if (empty($ar_orders[0])) {$ar_orders[0]=0;}
							if (empty($ar_orders_in_progres[0])) {$ar_orders_in_progres[0]=0;}
							if (empty($ar_orders_pending[0])) {$ar_orders_pending[0]=0;}
							if (empty($ar_orders_paid[0])) {$ar_orders_paid[0]=0;}
							if (empty($ar_orders_picked[0])) {$ar_orders_picked[0]=0;}
							if (empty($ar_orders_despatched[0])) {$ar_orders_despatched[0]=0;}
							if (empty($ar_orders_canceled_before[0])) {$ar_orders_canceled_before[0]=0;}
							if (empty($ar_orders_canceled_after[0])) {$ar_orders_canceled_after[0]=0;}
							if (empty($ar_clothes_designes[0])) {$ar_clothes_designes[0]=0;}
							if (empty($ar_clothes_designes_active[0])) {$ar_clothes_designes_active[0]=0;}
							if (empty($ar_clothes_designes_inactive[0])) {$ar_clothes_designes_inactive[0]=0;}
							
							$nfo = mysql_query("SELECT admin_info_shop FROM $db_admin_info WHERE aid=".$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$inf = mysql_fetch_array($nfo);
							$lst = explode("#", $inf[0]);
	echo "						<br>\n";
	echo "						<table cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#DDDDDD\" align=\"center\">\n";
	echo "							<tr bgcolor=\"#EEEEEE\">\n";
	echo "								<td><strong>"._STAT_STATISTICS."</strong></td>\n";
	echo "								<td><strong>"._STAT_NEWS_STATE."</strong></td>\n";
	echo "								<td><strong>"._STAT_LASTVISIT."</strong></td>\n";
	echo "								<td><strong>"._STAT_NEW."</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_PRODUCTS."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar1[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar1[0]-$lst[0]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_MANUFACTURERS."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar2[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[1]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar2[0]-$lst[1]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[2]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders[0]-$lst[2]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_IN_PROGRES."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_in_progres[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[3]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_in_progres[0]-$lst[3]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_PENDING."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_pending[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[4]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_pending[0]-$lst[4]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_PAID."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_paid[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[5]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_paid[0]-$lst[5]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_PICKED."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_picked[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[6]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_picked[0]-$lst[6]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_DESPATCHED."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_despatched[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[7]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_despatched[0]-$lst[7]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_CANCELED_BEFORE."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_canceled_before[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[8]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_canceled_before[0]-$lst[8]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_ORDERS_CANCELED_AFTER."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_orders_canceled_after[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[9]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_orders_canceled_after[0]-$lst[9]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_CLOTHES_DESIGNES."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_clothes_designes[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[10]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_clothes_designes[0]-$lst[10]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_CLOTHES_DESIGNES_ACTIVE."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_clothes_designes_active[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[11]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_clothes_designes_active[0]-$lst[11]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "							<tr bgcolor=\"#FFFFFF\">\n";
	echo "								<td>"._SHOP_STAT_CLOTHES_DESIGNES_INACTIVE."</td>\n";
	echo "								<td align=\"right\"><strong>".$ar_clothes_designes_inactive[0]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>".$lst[12]."</strong></td>\n";
	echo "								<td align=\"right\"><strong>"; echo $ar_clothes_designes_inactive[0]-$lst[12]; echo "</strong></td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "						<br>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
					mysql_query("UPDATE $db_admin_info SET admin_info_shop='$ar1[0]#$ar2[0]#$ar_orders[0]#$ar_orders_in_progres[0]#$ar_orders_pending[0]#$ar_orders_paid[0]#$ar_orders_picked[0]#$ar_orders_despatched[0]#$ar_orders_canceled_before[0]#$ar_orders_canceled_after[0]#$ar_clothes_designes[0]#$ar_clothes_designes_active[0]#$ar_clothes_designes_inactive[0]' WHERE aid=".$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU PODUKTU																			
*																											
***********************************************************************************************************/
function Products(){
	
	global $db_category,$db_shop_product,$db_shop_product_changes,$db_admin;
	global $eden_cfg;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\"><form action=\"modul_shop.php?action=prod\" method=\"post\">\n";
						// Select category
						echo EdenCategorySelect($_POST['cat'], "shop", "cat", _ARTICLES_SEL_CAT);
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "					<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "					</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td class=\"nadpis-boxy\" width=\"65\">"._CMN_OPTIONS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"45\" align=\"center\" "; if ($podle == "id"){echo "bgcolor=\"#FFDEDF\"";} echo ">ID</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"250\" align=\"left\" "; if ($podle == "code"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_PROD_CODE."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"50\" align=\"left\" "; if ($podle == "quantity"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_PROD_QUANTITY_PCS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"80\" align=\"center\" "; if ($podle == "price"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_PRICE."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"200\" align=\"center\" "; if ($podle == "headline"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_PROD_NAME."</td>\n";
	echo "		<td class=\"nadpis-boxy\" align=\"left\" "; if ($podle == "category"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_CATEGORY."</td>\n";
	echo "	</tr>";
 		if ($_POST['cat'] != ""){$where = "WHERE sp.shop_product_master_category=".(integer)$_POST['cat']." OR sp.shop_product_subcategory1=".(integer)$_POST['cat']." OR sp.shop_product_subcategory2=".(integer)$_POST['cat']." OR sp.shop_product_subcategory3=".(integer)$_POST['cat']." OR sp.shop_product_subcategory4=".(integer)$_POST['cat']." OR sp.shop_product_subcategory5=".(integer)$_POST['cat']."";}
			$res = mysql_query("SELECT sp.shop_product_id, sp.shop_product_name, sp.shop_product_product_code, sp.shop_product_quantity, sp.shop_product_selling_price, sp.shop_product_subcategory1, sp.shop_product_subcategory2, sp.shop_product_subcategory3, sp.shop_product_subcategory4, sp.shop_product_subcategory5, c.category_name 
			FROM $db_shop_product AS sp 
			JOIN $db_category AS c ON sp.shop_product_master_category = c.category_id 
			$where 
			ORDER BY sp.shop_product_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=0;
			while ($ar = mysql_fetch_array($res)){
				$res_subcat = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".$ar['shop_product_subcategory1']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_subcat = mysql_fetch_array($res_subcat);
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"65\" valign=\"top\">";
					if (CheckPriv("groups_shop_edit") == 1){ echo "<a href=\"modul_shop.php?action=edit_prod&amp;id=".$ar['shop_product_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
					if (CheckPriv("groups_shop_del") == 1){ echo "<a href=\"modul_shop.php?action=del_prod&amp;id=".$ar['shop_product_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; }
					if (CheckPriv("groups_shop_changes") == 1){ echo "<a href=\"modul_shop.php?action=changes&amp;id=".$ar['shop_product_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_zmeny.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_ZMENY."\"></a>"; } echo "</td>";
					echo "	<td width=\"45\" align=\"right\" valign=\"top\">".$ar['shop_product_id']."</td>\n";
					echo "	<td width=\"250\" valign=\"top\">".$ar['shop_product_product_code']."</td>\n";
					echo "	<td width=\"50\" align=\"right\" valign=\"top\">".$ar['shop_product_quantity']."</td>\n";
					echo "	<td width=\"80\" valign=\"top\" align=\"right\">".number_format($ar['shop_product_selling_price'], 2, '.', ' ')."</td>\n";
					echo "	<td valign=\"top\">".PrepareFromDB($ar['shop_product_name'])."</td>\n";
					echo "	<td valign=\"top\">".PrepareFromDB($ar['category_name']); if ($ar['shop_product_subcategory1'] != 0){echo "/".PrepareFromDB($ar_subcat['category_name']);} echo "</td>\n";
					echo "</tr>";
 				if ($_GET['action'] == "changes" & $_GET['id'] == $ar['shop_product_id']){
					// Zjisteni poctu obrazku v databazi k jednotlivemu prispevku
					$res2 = mysql_query("SELECT spch.shop_product_changes_date, spch.shop_product_changes_status, spch.shop_product_changes_quantity, spch.shop_product_changes_rem_id, a.admin_id, a.admin_uname FROM $db_shop_product_changes AS spch, $db_admin AS a WHERE spch.shop_product_changes_product_id=".(float)$_GET['id']." AND spch.shop_product_changes_admin_id=a.admin_id  ORDER BY spch.shop_product_changes_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<tr>";
					echo "<td colspan=\"9\">";
					echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
					while ($ar2 = mysql_fetch_array($res2)){
						echo "<tr bgcolor=\"#EEEEEE\">\n";
						echo "	<td width=\"130\">".FormatDatetime($ar2['shop_product_changes_date'])."</td>\n";
						echo "	<td width=\"130\">".$ar2['admin_uname']."</td>\n";
						echo "	<td width=\"80\" align=\"right\">"; if ($ar2['shop_product_changes_status'] == 1){echo _SHOP_PROD_CHANGES_ADD;} elseif ($ar2['shop_product_changes_status'] == 2){echo _SHOP_PROD_CHANGES_REM;} echo "</td>\n";
						echo "	<td width=\"60\" align=\"right\">".$ar2['shop_product_changes_quantity']." "._CMN_PCS."</td>\n";
						echo "	<td>&nbsp;"; if ($ar2['shop_product_changes_rem_id'] == 1){echo _SHOP_PROD_REM_1;}
									if ($ar2['shop_product_changes_rem_id'] == 2){echo _SHOP_PROD_REM_2;} echo "</td>\n";
						echo "</tr>";
					}
					echo "</table>";
					echo "</td>";
					echo "</tr>";
				}
				$i++;
			}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU OBEDNAVEK																			
*																											
***********************************************************************************************************/
function Orders(){
	
	global $db_category,$db_shop_orders_product,$db_shop_orders,$db_admin,$db_shop_product;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if (isset($_GET['o_mod'])){$o_mod = $_GET['o_mod'];} elseif (isset($_POST['o_mod'])){$o_mod = $_POST['o_mod'];} else {$o_mod = 1;}
	
	/****************************************************************/
	/* Autorizace platby 											*/
	/****************************************************************/
	if ($_GET['auth_action'] == "authorize_payment"){
		
		$res = mysql_query("SELECT * FROM $db_shop_orders WHERE shop_orders_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		$nvpStr;
		$nvpStr = "&STARTDATE=2006-12-26T24:00:00Z";
		$nvpStr .= "&INVNUM=".$ar['shop_orders_invoice_id'];
		
		$resArray = HashCall("TransactionSearch",$nvpStr);
		
		if(!isset($resArray["L_TRANSACTIONID0"])){
			echo _SHOP_PAYPAL_TEST_TSR_NTS;
		} else {
			/* Nastaveni IPN ID */
			$shop_orders_paypal_ipn_id = $resArray["L_TRANSACTIONID0"];
			
			/* Nastaveni spravneho formatu datumu */
			$time = $resArray["L_TIMESTAMP0"];
			$timeStamp = FormatPaypalTimestamp($time,"Y-m-d H:i:s");
			
			mysql_query("UPDATE $db_shop_orders SET shop_orders_orders_status=3, shop_orders_ipn_check_status=1, shop_orders_paypal_ipn_id='".mysql_real_escape_string($shop_orders_paypal_ipn_id)."', shop_orders_date_purchased='".mysql_real_escape_string($timeStamp)."', shop_orders_last_modified=NOW() WHERE shop_orders_id=".(float)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if (mysql_affected_rows() == 1){
				// Odeslani emailu zakaznikovi s potvrzenim, ze jeho obednavka byla predana k zaplaceni a nyni se ceka na zaplaceni
				SendEmailToCustomer($_GET['id'],3,"full");
				echo _SHOP_ORDER.": <strong>".$_GET['id']."</strong> - "._SHOP_ORDERS_AUTH_PAYMENT_OK;
			} else {
				echo _SHOP_ORDER.": <strong>".$_GET['id']."</strong> - "._SHOP_ORDERS_AUTH_PAYMENT_FALSE;
			}
		}
	}
	/****************************************************************/
	/* Autorizace zabaleni a odeslani zasilky						*/
	/****************************************************************/
	if ($_GET['auth_action'] == "authorize_packing" || $_GET['auth_action'] == "authorize_despatch" || $_GET['auth_action'] == "cancel_before" || $_GET['auth_action'] == "cancel_after"){
		
		if ($_GET['auth_action'] == "authorize_packing"){$eden_order_status = 4; $eden_orders_auth_query = "shop_orders_orders_status=4, shop_orders_date_picked=NOW(),"; $shop_orders_auth_text_ok = _SHOP_ORDERS_AUTH_PACKING_OK; $shop_orders_auth_text_false = _SHOP_ORDERS_AUTH_PACKING_FALSE;}
		if ($_GET['auth_action'] == "authorize_despatch"){$eden_order_status = 5; $eden_orders_auth_query = "shop_orders_orders_status=5, shop_orders_date_despatched=NOW(),"; $shop_orders_auth_text_ok = _SHOP_ORDERS_AUTH_DESPATCH_OK; $shop_orders_auth_text_false = _SHOP_ORDERS_AUTH_DESPATCH_FALSE;}
		if ($_GET['auth_action'] == "cancel_before"){$eden_order_status = 6; $eden_orders_auth_query = "shop_orders_orders_status=6, shop_orders_date_cancelled=NOW(),"; $shop_orders_auth_text_ok = _SHOP_ORDERS_AUTH_CANCEL_BEFORE_OK; $shop_orders_auth_text_false = _SHOP_ORDERS_AUTH_CANCEL_BEFORE_FALSE;}
		if ($_GET['auth_action'] == "cancel_after"){$eden_order_status = 7; $eden_orders_auth_query = "shop_orders_orders_status=7, shop_orders_date_cancelled=NOW(),"; $shop_orders_auth_text_ok = _SHOP_ORDERS_AUTH_CANCEL_AFTER_OK; $shop_orders_auth_text_false = _SHOP_ORDERS_AUTH_CANCEL_AFTER_FALSE;}
		
	   mysql_query("UPDATE $db_shop_orders SET $eden_orders_auth_query shop_orders_last_modified=NOW() WHERE shop_orders_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if (mysql_affected_rows() == 1){
			// Odeslani emailu zakaznikovi s potvrzenim, ze jeho obednavka byla predana k zaplaceni a nyni se ceka na zaplaceni
			SendEmailToCustomer($_GET['id'],$eden_order_status,"full");
			echo _SHOP_ORDER.": <strong>".$_GET['id']."</strong> - ".$shop_orders_auth_text_ok;
		} else {
			echo _SHOP_ORDER.": <strong>".$_GET['id']."</strong> - ".$shop_orders_auth_text_false;
		}
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "; 
					switch($o_mod){
						case "2":
							echo _SHOP_ORDERS_2;
							break;
						case "3":
							echo _SHOP_ORDERS_3;
							break;
						case "4":
							echo _SHOP_ORDERS_4;
							break;
						case "5":
							echo _SHOP_ORDERS_5;
							break;
						case "6":
							echo _SHOP_ORDERS_6;
							break;
						case "7":
							echo _SHOP_ORDERS_7;
							break;
						default:
							echo _SHOP_ORDERS_1;
					}
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\"><form action=\"sys_save.php?action=orders\" method=\"post\">\n";
	echo "			<select name=\"o_mod\">\n";
	echo "				<option name=\"o_mod\" value=\"1\" "; if ($o_mod == 1){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_1."</option>\n";
	echo "				<option name=\"o_mod\" value=\"2\" "; if ($o_mod == 2){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_2."</option>\n";
	echo "				<option name=\"o_mod\" value=\"3\" "; if ($o_mod == 3){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_3."</option>\n";
	echo "				<option name=\"o_mod\" value=\"4\" "; if ($o_mod == 4){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_4."</option>\n";
	echo "				<option name=\"o_mod\" value=\"5\" "; if ($o_mod == 5){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_5."</option>\n";
	echo "				<option name=\"o_mod\" value=\"6\" "; if ($o_mod == 6){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_6."</option>\n";
	echo "				<option name=\"o_mod\" value=\"7\" "; if ($o_mod == 7){echo "selected=\"selected\"";} echo ">"._SHOP_ORDERS_7."</option>\n";
	echo "			</select>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\" valign=\"top\">\n";
	echo "		<td class=\"nadpis-boxy\" width=\"40\">"._CMN_OPTIONS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"250\" align=\"right\" "; if ($podle == "invoice_id"){echo "bgcolor=\"#FFDEDF\"";} echo ">ID<br>"._SHOP_ORDERS_INVOICE_ID."<br>"._SHOP_ORDERS_PAYPAL_TRANSACTION_ID."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"70\" align=\"center\" "; if ($podle == "total"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_ORDERS_TOTAL."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"145\" align=\"left\" "; if ($podle == "quantity"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_ORDERS_ORDER_DATE."<br>"; if ($o_mod == 3 || $o_mod == 4 || $o_mod == 5 || $o_mod == 7) {echo _SHOP_ORDERS_PURCHASE_DATE."<br>";} if ($o_mod == 4 || $o_mod == 5) {echo _SHOP_ORDERS_PICKED_DATE."<br>";} if ($o_mod == 5){echo _SHOP_ORDERS_DESPATCH_DATE."<br>";} echo _SHOP_ORDERS_LAST_UPDATE."</td>\n";
	echo "		<td class=\"nadpis-boxy\" width=\"40\" align=\"center\" "; if ($podle == "headline"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_ORDERS_ADMIN_ID."</td>\n";
	echo "		<td class=\"nadpis-boxy\" align=\"left\" "; if ($podle == "price"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_ORDERS_ADMIN_DETAILS."</td>\n";
	echo "		<td class=\"nadpis-boxy\" "; if ($podle == "price"){echo "bgcolor=\"#FFDEDF\"";} echo ">"._SHOP_ORDERS_PAYMENT_METHOD."</td>\n";
	echo "		<td class=\"nadpis-boxy\">"._SHOP_ORDERS_AUTH."</td>\n";
	echo "	</tr>";
	 		$podle = "shop_orders_id";
			$ser = "DESC";
			$res = mysql_query("SELECT * FROM $db_shop_orders WHERE shop_orders_orders_status=".(integer)$o_mod." ORDER BY $podle $ser") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=0;
			while ($ar = mysql_fetch_array($res)){
				if ($o_mod == 1) {
					$authorize_mode_no = "cancel_before";
					$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_BEFORE;
				} elseif ($o_mod == 2) {
					$authorize_mode_yes = "authorize_payment";
					$authorize_mode_no = "cancel_before";
					$authorize_title_yes = _SHOP_ORDERS_AUTH_PAYMENT;
					$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_BEFORE;
				} elseif ($o_mod == 3) {
					$authorize_mode_yes = "authorize_packing";
					$authorize_mode_no = "cancel_after";
					$authorize_title_yes = _SHOP_ORDERS_AUTH_PACKING;
					$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_AFTER;
				} elseif ($o_mod == 4) {
					$authorize_mode_yes = "authorize_despatch";
					$authorize_mode_no = "cancel_after";
					$authorize_title_yes = _SHOP_ORDERS_AUTH_DESPATCH;
					$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_AFTER;
				} elseif ($o_mod == 5) {
				 	$authorize_mode_no = "cancel_after";
				 	$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_AFTER;
				} elseif ($o_mod == 6) {
				 	$authorize_mode_no = "cancel_after";
				 	$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_AFTER;
				} elseif ($o_mod == 7) {
				 	$authorize_mode_no = "cancel_after";
				 	$authorize_title_no = _SHOP_ORDERS_AUTH_CANCEL_AFTER;
				}
				
				//echo "<tr "; if ($_GET['action'] == "o_det" & $_GET['id'] == $ar['shop_orders_id']){ echo " style=\"background-color: #FFDEDF;\" ";} else { echo " onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\"";} echo ">\n";
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"40\" valign=\"top\">\n";
							if (CheckPriv("groups_shop_changes") == 1){ echo "<a href=\"modul_shop.php?action=o_det&o_mod=".$o_mod."&amp;id=".$ar['shop_orders_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_zmeny.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._SHOP_ORDER_SHOW."\"></a>"; }
							if (CheckPriv("groups_shop_del") == 1 && strtolower($ar['shop_orders_payment_method']) == "paypal" && $o_mod != 1){ echo "<a href=\"modul_shop.php?action=o_test&o_mod=".$o_mod."&amp;id=".$ar['shop_orders_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_shop_transaction_details.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._SHOP_ORDERS_TRANSACTION_DETAILS."\"></a>"; } echo "</td>\n";
				echo "	<td width=\"250\" align=\"right\" valign=\"top\"><strong>".$ar['shop_orders_id']."</strong><br>".$ar['shop_orders_invoice_id']; if (strtolower($ar['shop_orders_payment_method']) == "paypal"){echo "<br>".$ar['shop_orders_paypal_ipn_id'];} echo "</td>\n";
				echo "	<td width=\"70\" align=\"right\" valign=\"top\"><strong>".PriceFormat($ar['shop_orders_order_total_netto'])."</strong><br>".PriceFormat($ar['shop_orders_order_total'])."<br>".PriceFormat($ar['shop_orders_order_tax'])."</td>\n";
				echo "	<td width=\"145\" align=\"right\" valign=\"top\">".FormatDatetime($ar['shop_orders_date_ordered'],"d.m.y - H:i")."<br>"; if ($o_mod == 3 || $o_mod == 4 || $o_mod == 5 || $o_mod == 7) {echo FormatDatetime($ar['shop_orders_date_purchased'],"d.m.y - H:i")."<br>";} if ($o_mod == 4 || $o_mod == 5) {echo FormatDatetime($ar['shop_orders_date_picked'],"d.m.y - H:i")."<br>";} if ($o_mod == 5){echo FormatDatetime($ar['shop_orders_date_despatched'],"d.m.y - H:i")."<br>";} echo "<span style=\"color:#999999;\">".FormatDatetime($ar['shop_orders_last_modified'],"d.m.y - H:i")."</span></td>\n";
				echo "	<td width=\"40\" align=\"right\" valign=\"top\">".$ar['shop_orders_admin_id']."</td>\n";
				echo "	<td align=\"left\" valign=\"top\">".$ar['shop_orders_admin_firstname']." ".$ar['shop_orders_admin_name']."<br>\n";
				echo "		".$ar['shop_orders_admin_address1']."\n";
							if ($ar['shop_orders_admin_address2'] != ""){echo "<br>".$ar['shop_orders_admin_address2'];}
				echo "		".$ar['shop_orders_admin_city']."\n";
				echo "	</td>\n";
				echo "	<td width=\"100\" valign=\"top\">".$ar['shop_orders_payment_method']."</td>\n";
				echo "	<td width=\"100\" valign=\"top\">"; if (CheckPriv("groups_shop_del") == 1 && strtolower($ar['shop_orders_payment_method']) == "paypal" && ($o_mod != 1 && $o_mod != 5 && $o_mod != 6 && $o_mod != 7)){ echo "<a href=\"modul_shop.php?action=orders&o_mod=".$o_mod."&amp;id=".$ar['shop_orders_id']."&amp;project=".$_SESSION['project']."&auth_action=".$authorize_mode_yes."\" style=\"border:solid 1px #000000; margin-right:10px;\"><img src=\"images/sys_yes.gif\" alt=\"".$authorize_title_yes."\" title=\"".$authorize_title_yes."\" width=\"18\" height=\"18\" border=\"0\"></a>"; }
							if (CheckPriv("groups_shop_del") == 1 && strtolower($ar['shop_orders_payment_method']) == "paypal"){ echo "<a href=\"modul_shop.php?action=orders&o_mod=".$o_mod."&amp;id=".$ar['shop_orders_id']."&amp;project=".$_SESSION['project']."&auth_action=".$authorize_mode_no."\" style=\"border:solid 1px #000000; margin-right:10px;\"><img src=\"images/sys_no.gif\" alt=\"".$authorize_title_no."\" title=\"".$authorize_title_no."\" width=\"18\" height=\"18\" border=\"0\"></a>"; } echo "</td>\n";
				echo "</tr>";
 				if ($_GET['action'] == "o_det" & $_GET['id'] == $ar['shop_orders_id']){
					$res2 = mysql_query("SELECT shop_orders_product_id, shop_orders_shop_product_id, shop_orders_shop_product_quantity, shop_orders_shop_product_name FROM $db_shop_orders_product WHERE shop_orders_orders_id=".$ar['shop_orders_id']." ORDER BY shop_orders_product_id  ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<tr>\n";
					echo "	<td colspan=\"8\">\n";
					echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
					echo "			<tr bgcolor=\"#E1E0E0\">\n";
					echo "				<td width=\"40\" align=\"center\">ID</td>\n";
					echo "				<td width=\"130\" align=\"center\">"._SHOP_PROD_CODE."</td>\n";
					echo "				<td width=\"80\" align=\"center\">"._SHOP_PROD_QUANTITY_PCS."</td>\n";
					echo "				<td align=\"left\">"._SHOP_PROD_NAME."</td>\n";
					echo "			</tr>";
					while ($ar2 = mysql_fetch_array($res2)){
						$res_prod = mysql_query("SELECT shop_product_product_code FROM $db_shop_product WHERE shop_product_id=".$ar2['shop_orders_shop_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar_prod = mysql_fetch_array($res_prod);
						echo "<tr bgcolor=\"#EEEEEE\">\n";
						echo "	<td width=\"40\" align=\"right\">".$ar2['shop_orders_shop_product_id']."/".$ar2['shop_orders_product_id']."</td>\n";
						echo "	<td width=\"130\" align=\"right\"><span onclick=\"window.open('modul_shop_show_clothes.php?project=".$_SESSION['project']."&code=".$ar_prod['shop_product_product_code']."&prod_id=".$ar2['shop_orders_shop_product_id']."','','menubar=no,resizable=no,toolbar=no,status=no,width=700,height=500')\" style=\"cursor:pointer; color:#0000ff;\">".$ar_prod['shop_product_product_code']."</span></td>\n";
						echo "	<td width=\"80\" align=\"right\">".$ar2['shop_orders_shop_product_quantity']." "._CMN_PCS."</td>\n";
						echo "	<td>".$ar2['shop_orders_shop_product_name']."</td>\n";
						echo "</tr>";
					}
					echo "</table>";
					echo "</td>";
					echo "</tr>";
				}
				// PayPal Test API
				if ($_GET['action'] == "o_test" & $_GET['id'] == $ar['shop_orders_id']){
					echo "<tr>\n";
					echo "	<td colspan=\"9\">\n";
					echo "		<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
					echo "			<tr bgcolor=\"#E1E0E0\">\n";
					echo "				<td align=\"center\"><iframe name=\"paypalapi\" width=\"830\" height=\"500\" src=\"modul_shop_paypal_test_api.php?action=tsr&iid=".$ar['shop_orders_invoice_id']."&amp;lang=".$_SESSION['lang']."&amp;project=".$_SESSION['project']."\"></iframe></td>\n";
					echo "			</tr>\n";
					echo "		</table>\n";
					echo "	</td>\n";
					echo "</tr>";
				}
				$i++;
			}
	echo "</table>";
}

/***********************************************************************************************************
*																											
*		ZOBRAZENI VYROBCU																					
*																											
***********************************************************************************************************/
function Manufacturers(){
	
	global $db_shop_man;
	global $eden_cfg;
	global $ftp_path_shop_man;
	global $url_shop_man;
	global $prepare;
	
	// Provereni opravneni
	if ($_GET['action'] == "add_man"){
		if (CheckPriv("groups_shop_add") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "edit_man"){
		if (CheckPriv("groups_shop_edit") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "del_man"){
		if (CheckPriv("groups_shop_del") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	} else {
		 echo _NOTENOUGHPRIV;Products();exit;
	}
	
	$res = mysql_query("SELECT * FROM $db_shop_man WHERE shop_manufacturers_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$man_name = PrepareFromDB($ar['shop_manufacturers_name']);
	$man_url = PrepareFromDB($ar['shop_manufacturers_url']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "; if ($_GET['action'] == "add_man"){echo _SHOP_MAN_ADD;} elseif ($_GET['action'] == "edit_man"){echo _SHOP_MAN_EDIT;} elseif ($_GET['action'] == "del_man"){echo _SHOP_MAN_DEL;} echo"</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "del_man"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td width=\"150px\" align=\"right\">"._CMN_ID.":<br>\n";
		echo "			"._NAME.":<br>\n";
		echo "			"._SHOP_MAN_URL.":<br>\n";
		echo "		</td> \n";
		echo "		<td width=\"607\" align=\"left\">".$ar['shop_manufacturers_id']."<br>\n";
		echo "			".$ar['shop_manufacturers_name']."<br>\n";
		echo "			".$ar['shop_manufacturers_url']."<br>\n";
		echo "		</td> \n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" colspan=\"2\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_man"){echo "add_man";} elseif ($_GET['action'] == "edit_man"){echo "edit_man";} else {echo "del_man";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_MAN_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
 	}
	if ($_GET['action'] != "del_man"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"250\"><form enctype=\"multipart/form-data\" action=\"sys_save.php?action="; if ($_GET['action'] == "add_man"){echo "add_man";} else {echo "edit_man";} echo "\" method=\"post\" name=\"forma\"><strong>"._SHOP_MAN_NAME."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"man_name\" maxlength=\"50\" value=\"".$man_name."\" size=\"60\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_MAN_URL."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"man_url\" maxlength=\"250\" value=\"".$man_url."\" size=\"60\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._CMN_IMAGE."</strong></td>\n";
		echo "		<td align=\"left\" valign=\"top\"><input type=\"file\" name=\"shop_man_file\" size=\"20\"><br>\n";
						if ($ar['shop_manufacturers_image'] != ""){ echo "<img src=\"".$url_shop_man.$ar['shop_manufacturers_image']."\" alt=\"\" border=\"0\">"; } echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_man"){echo _SHOP_MAN_ADD;} else {echo _SHOP_MAN_EDIT;} echo "\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td class=\"nadpis-boxy\" width=\"80\">"._CMN_OPTIONS."</td>\n";
		echo "		<td class=\"nadpis-boxy\" width=\"30\">"._CMN_ID."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._CMN_IMAGE."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._NAME."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._SHOP_MAN_URL."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._SHOP_MAN_URL_CLICKED."</td>\n";
		echo "	</tr>";
		$res = mysql_query("SELECT shop_manufacturers_id, shop_manufacturers_name, shop_manufacturers_url, shop_manufacturers_image, shop_manufacturers_url_clicked  FROM $db_shop_man ORDER BY shop_manufacturers_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=0;
		while ($ar = mysql_fetch_array($res)){
			$man_name = PrepareFromDB($ar['shop_manufacturers_name']);
			$man_url = PrepareFromDB($ar['shop_manufacturers_url']);
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" valign=\"top\"><a href=\"modul_shop.php?action=edit_man&amp;id=".$ar['shop_manufacturers_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_shop.php?action=del_man&amp;id=".$ar['shop_manufacturers_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td> \n";
			echo "	<td width=\"30\" valign=\"top\">".$ar['shop_manufacturers_id']."</td>\n";
			echo "	<td valign=\"top\" width=\"100\">"; if ($ar['shop_manufacturers_image'] != ""){echo "<img src=\"".$url_shop_man.$ar['shop_manufacturers_image']."\">";} echo "</td>";
			echo "	<td valign=\"top\">".$man_name."</td>\n";
			echo "	<td valign=\"top\">".$man_url."</td>\n";
			echo "	<td valign=\"top\">".$ar['shop_manufacturers_url_clicked']."</td>\n";
			echo "</tr>";
			$i++;
		 }
 		echo "</table>";
 	}
}
/***********************************************************************************************************
*																											
*		PRIDANI ZBOZI																						
*																											
***********************************************************************************************************/
function AddProd(){
	
	global $db_shop_product,$db_category,$db_shop_man,$db_shop_tax_class,$db_shop_tax_rates,$db_shop_discount_category;
	global $eden_cfg;
	global $ftp_path_shop_prod;
	global $url_shop_prod;
	global $preview;
	
	// Provereni opravneni
	if ($_GET['action'] == "add_prod"){
		if (CheckPriv("groups_shop_add") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	}elseif ($_GET['action'] == "edit_prod"){
		if (CheckPriv("groups_shop_edit") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	} else {
		 echo _NOTENOUGHPRIV;Products();exit;
	}
	
	if ($_POST['confirm'] != "true"){
		if ($_GET['action'] == "edit_prod"){
			$res = mysql_query("SELECT * FROM $db_shop_product WHERE shop_product_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$prod_name = PrepareFromDB($ar['shop_product_name']);
			$prod_model = PrepareFromDB($ar['shop_product_model']);
			$prod_purchasing_price = PrepareFromDB($ar['shop_product_purchasing_price']);
			$prod_selling_price = PrepareFromDB($ar['shop_product_selling_price']);
			$prod_short = PrepareFromDB($ar['shop_product_description_short']);
			$prod_long = PrepareFromDB($ar['shop_product_description']);
		}
		/*
		*	Nastaveni Datumu
		*/
		if ($ar['shop_product_date_available'] != ""){$prod_date_day = FormatDatetime($ar['shop_product_date_available'],"d");} else {$prod_date_day = date("d");}
		if ($ar['shop_product_date_available'] != ""){$prod_date_month = FormatDatetime($ar['shop_product_date_available'],"m");} else {$prod_date_month = date("m");}
		if ($ar['shop_product_date_available'] != ""){$prod_date_year = FormatDatetime($ar['shop_product_date_available'],"Y");} else {$prod_date_year = date("Y");}
		
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "; if ($_GET['action'] == "add_prod"){ echo _SHOP_PROD_ADD;} else {echo _SHOP_PROD_EDIT." - ID #".$_GET['id'];} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
		if ($shop_error != ""){
			echo "	<tr>\n";
			echo "		<td align=\"left\" class=\"error\" colspan=\"2\">";
			$i=0;
			$shop_error_num = count($shop_error);
			while($i < $shop_error_num){
				if ($shop_error[$i] == "prod_selling_price_0"){echo _SHOP_ERR_1."<br>";}
				if ($shop_error[$i] == "prod_purchasing_price_0"){echo _SHOP_ERR_2."<br>";}
				if ($shop_error[$i] == "prod_weight_0"){echo _SHOP_ERR_3."<br>";}
				if ($shop_error[$i] == "prod_warranty_0"){echo _SHOP_ERR_4."<br>";}
				if ($shop_error[$i] == "prod_quantity_0"){echo _SHOP_ERR_5."<br>";}
				$i++;
			}
			echo "</td>";
			echo "</tr>";
 		}
	echo "</table>";
	echo "<br>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\"><form method=\"post\" name=\"form1\" enctype=\"multipart/form-data\" action=\"sys_save.php?action="; if ($_GET['action'] == "add_prod"){echo "add_prod";} else {echo "edit_prod";} echo "&amp;id=".$_GET['id']."\" onSubmit=\"return SendData".$short->editor_name."(), SendData".$long->editor_name."()\">\n";
	echo "		<strong>"._SHOP_PROD_NAME."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_name\" maxlength=\"250\" value=\"".$prod_name."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_PROD_STATUS."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"prod_status\" style=\"width: 150px;\">\n";
	echo "			<option name=\"prod_status\" value=\"0\" "; if ($ar['shop_product_status'] == 0){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_0."</option>\n";
	echo "			<option name=\"prod_status\" value=\"1\" "; if ($ar['shop_product_status'] == 1 || $_GET['action'] == "add_prod"){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_1."</option>\n";
	echo "			<option name=\"prod_status\" value=\"2\" "; if ($ar['shop_product_status'] == 2){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_2."</option>\n";
	echo "			<option name=\"prod_status\" value=\"3\" "; if ($ar['shop_product_status'] == 3){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_3."</option>\n";
	echo "		</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_PROD_AVAILEBLE_DATE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<select name=\"prod_date_day\" style=\"width: 40px;\">";
						for($i=1;$i<32;$i++){
							echo "<option name=\"prod_date_day\" value=\"".$i."\" ";
							if ($prod_date_day == $i) { echo "selected=\"selected\"";}
							echo ">".$i."</option>";
						}
	echo "			</select>";
	echo "			<select name=\"prod_date_month\" style=\"width: 40px;\">";
						for($i=1;$i<13;$i++){
							echo "<option name=\"prod_date_month\" value=\"".$i."\" ";
							if ($prod_date_month == $i) { echo "selected=\"selected\"";}
							echo ">".$i."</option>";
						}
	echo "			</select>";
	echo "			<select name=\"prod_date_year\" style=\"width: 60px;\">";
						for($i=1990;$i<2050;$i++){
							echo "<option name=\"prod_date_year\" value=\"".$i."\" ";
							if ($prod_date_year == $i) { echo "selected=\"selected\"";}
							echo ">".$i."</option>";
						}
	echo "			</select>";
	echo "			</td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_MAN."</strong></td>";
	echo "			<td align=\"left\" valign=\"top\"><select name=\"prod_man_id\" style=\"width: 150px;\">";
					$res2 = mysql_query("SELECT shop_manufacturers_id, shop_manufacturers_name FROM $db_shop_man ORDER BY shop_manufacturers_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar2 = mysql_fetch_array($res2))	{
							echo "<option name=\"prod_man_id\" value=\"".$ar2['shop_manufacturers_id']."\" ";
							if ($ar2['shop_manufacturers_id'] == $_GET['id']) { echo "selected=\"selected\"";}
							echo ">".PrepareFromDB($ar2['shop_manufacturers_name'])."</option>";
						}
	echo "			</select></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "			<strong>"._SHOP_PROD_MODEL."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_model\" maxlength=\"50\" value=\"".$prod_model."\" size=\"60\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_MASTER_CAT."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\">";
						// Select category
						echo EdenCategorySelect($ar['shop_product_master_category'], "shop", "prod_master_category", 0);
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_SUB_CAT."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\">\n";
						// Select category
						echo EdenCategorySelect($ar['shop_product_subcategory1'], "shop", "prod_sub_category1", _SHOP_PROD_SEL_SUBCAT);
						
						// Select category
						echo EdenCategorySelect($ar['shop_product_subcategory2'], "shop", "prod_sub_category2", _SHOP_PROD_SEL_SUBCAT);
						
						// Select category
						echo EdenCategorySelect($ar['shop_product_subcategory3'], "shop", "prod_sub_category3", _SHOP_PROD_SEL_SUBCAT);
						
						// Select category
						echo EdenCategorySelect($ar['shop_product_subcategory4'], "shop", "prod_sub_category4", _SHOP_PROD_SEL_SUBCAT);
						
						// Select category
						echo EdenCategorySelect($ar['shop_product_subcategory5'], "shop", "prod_sub_category5", _SHOP_PROD_SEL_SUBCAT);
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_PURCH_PRICE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_purchasing_price\" maxlength=\"20\" value=\"".$ar['shop_product_purchasing_price']."\" size=\"20\"> "._SHOP_EX_VAT."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_SELL_PRICE.$ar['shop_product_vat_class_id']."</strong></td>";
					if ($_GET['action'] == "add_prod"){$tax_rates_class_id = 1; /* Vetsinou standard */} else {$tax_rates_class_id = $ar['shop_product_vat_class_id'];}
 					$res_tax_rates = mysql_query("SELECT shop_tax_rates_rate FROM $db_shop_tax_rates WHERE shop_tax_rates_class_id=".$tax_rates_class_id." ORDER BY shop_tax_rates_priority DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_tax_rates = mysql_fetch_array($res_tax_rates);
					echo "<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_selling_price\" maxlength=\"20\" value=\"".$ar['shop_product_selling_price']."\" size=\"20\"> "._SHOP_INC_VAT." ";
					$prod_ex_vat = $ar['shop_product_selling_price'] - (($ar['shop_product_selling_price'] / 100) * $ar_tax_rates['shop_tax_rates_rate']); 
					$prod_vat = ($ar['shop_product_selling_price'] - $prod_ex_vat);
	echo " 			<span class=\"red\">(".PriceFormat($prod_ex_vat)." "._SHOP_EX_VAT.") "._SHOP_VAT." = ".PriceFormat($prod_vat)."</span></td>";
	echo "			</tr>\n";
	echo " 			<tr>";
	echo " 				<td width=\"250\" align=\"right\" valign=\"top\">";
	echo "					<strong>"._SHOP_CL_STYLE_SELLER_DISCOUNT_CATEGORY."</strong>";
	echo "	 			</td>";
	echo "	   			<td align=\"left\" valign=\"top\">";
							/* Pokud je shop_discount_category_type mensi nez 30 je to typ slevove kategorie pro prodejce */
							$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							echo "<select name=\"prod_discount_cat_seller\" size=\"1\">";
							echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_product_discount_cat_seller_id']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
							while ($ar_discount = mysql_fetch_array($res_discount)){
								echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_product_discount_cat_seller_id']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
							}
							echo "</select>";
	echo "				</td>\n";
	echo " 			</tr>\n";
	echo " 			<tr>";
	echo "	  			<td width=\"250\" align=\"right\" valign=\"top\">";
	echo "					<strong>"._SHOP_CL_STYLE_CUSTOMER_DISCOUNT_CATEGORY."</strong>";
	echo "				</td>";
	echo "				<td align=\"left\" valign=\"top\">";
							/* Pokud je shop_discount_category_type mensi nez 30 je to typ slevove kategorie pro prodejce */
							$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type > 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							echo "<select name=\"prod_discount_cat_cust\" size=\"1\">";
							echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_product_discount_cat_cust_id']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
							while ($ar_discount = mysql_fetch_array($res_discount)){
								echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_product_discount_cat_cust_id']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
							}
							echo "</select>";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_PRICE_CHOOSE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><select name=\"prod_price_choose\" style=\"width: 150px;\">\n";
	echo "					<option name=\"prod_price_choose\" value=\"1\" "; if ($ar['shop_product_price_choose'] == 1) { echo "selected=\"selected\"";} echo">"._SHOP_PROD_PRICE_CHOOSE_HAND."</option>\n";
	echo "					<option name=\"prod_price_choose\" value=\"2\" "; if ($ar['shop_product_price_choose'] == 2) { echo "selected=\"selected\"";} echo ">"._SHOP_PROD_PRICE_CHOOSE_MARGIN."</option>\n";
	echo "				</select></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_MARGIN_CHOOSE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><select name=\"prod_margin_choose\" style=\"width: 150px;\">\n";
	echo "					<option name=\"prod_margin_choose\" value=\"1\" "; if ($ar['shop_product_margin_choose'] == 1) { echo "selected=\"selected\"";} echo ">"._SHOP_PROD_MARGIN_CHOOSE_GLOBAL."</option>\n";
	echo "					<option name=\"prod_margin_choose\" value=\"2\" "; if ($ar['shop_product_margin_choose'] == 2) { echo "selected=\"selected\"";} echo ">"._SHOP_PROD_MARGIN_CHOOSE_LOCAL."</option>\n";
	echo "				</select></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_MARGIN."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_margin\" maxlength=\"4\" value=\""; echo $ar['shop_product_margin'] * 100; echo "\" size=\"6\"> %</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_TAX_CLASS."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><select name=\"prod_vat_class_id\" style=\"width: 150px;\">\n";
						$res_tax_class = mysql_query("SELECT shop_tax_class_id, shop_tax_class_title FROM $db_shop_tax_class ORDER BY shop_tax_class_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($ar_tax_class = mysql_fetch_array($res_tax_class)){
							echo "<option name=\"prod_vat_class_id\" value=\"".$ar_tax_class['shop_tax_class_id']."\" ";
							if ($ar_tax_class['shop_tax_class_id'] == $ar['shop_product_vat_class_id']) { echo "selected=\"selected\"";}
							echo ">".PrepareFromDB($ar_tax_class['shop_tax_class_title'])."</option>";
						}
	echo "				</select> ".TepRound($ar_tax_rates['shop_tax_rates_rate'], 2)."%</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_DISCOUNT."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_discount\" maxlength=\"11\" value=\"".$ar['shop_product_discount']."\" size=\"10\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_QUANTITY."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\">"; if ($_GET['action'] == "add_prod"){ echo "<input type=\"text\" name=\"prod_quantity\" maxlength=\"11\" value=\"".$ar['shop_product_quantity']."\" size=\"10\">"; } else {echo  "<span style=\"color: #0000ff; font-weight:bold;\">".$ar['shop_product_quantity']."</span> "._CMN_PCS."\n";
	echo "				 - <a href=\"#\" id=\"ChangeProdQuantity\" style=\"color:#ff0000;\" onclick=\"window.open('add_shop_product_quantity.php?project=".$_SESSION['project']."&amp;id=".$ar['shop_product_id']."&una=".$_SESSION['loginid']."&amp;status=1&amp;lang=".$_SESSION['lang']."','','menubar=no,resizable=yes,toolbar=no,status=no,width=300,height=200')\">"._SHOP_PROD_CHANGE_QUANTITY_ADD."</a>\n";
	echo "				 - <a href=\"#\" id=\"ChangeProdQuantity\" style=\"color:#ff0000;\" onclick=\"window.open('add_shop_product_quantity.php?project=".$_SESSION['project']."&amp;id=".$ar['shop_product_id']."&una=".$_SESSION['loginid']."&amp;status=2&amp;lang=".$_SESSION['lang']."','','menubar=no,resizable=yes,toolbar=no,status=no,width=300,height=200')\">"._SHOP_PROD_CHANGE_QUANTITY_DEL."</a>"; } echo "</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_CODE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_code\" maxlength=\"250\" value=\"".$ar['shop_product_product_code']."\" size=\"40\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_CODE_MAN."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_code_man\" maxlength=\"250\" value=\"".$ar['shop_product_product_code_man']."\" size=\"40\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_WARRANTY."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_warranty\" maxlength=\"11\" value=\"".$ar['shop_product_warranty']."\" size=\"10\"> "._SHOP_PROD_WARRANTY_HELP."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\"><br><strong>"._SHOP_PROD_SHORT_DESC."</strong></td>\n";
	echo "				<td>";
	echo "					<div>";
	echo "						<textarea id=\"prod_short\" name=\"prod_short\" class=\"prod_short\" rows=\"15\" cols=\"60\" style=\"width: 100%\">".$prod_short."</textarea><br>";
	echo "					</div>";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\"><br><strong>"._SHOP_PROD_LONG_DESC."</strong></td>\n";
	echo "				<td>\n";
	echo "					<div>";
	echo "						<textarea id=\"prod_long\" name=\"prod_long\" class=\"prod_long\" rows=\"30\" cols=\"60\" style=\"width: 100%\">".$prod_long."</textarea><br>";
	echo "					</div>";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_IS_FREE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"prod_is_free\" value=\"1\" "; if ($ar['shop_product_is_free'] == 1){echo "checked";} echo "> "._CMN_YES."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"prod_is_free\" value=\"0\" "; if ($ar['shop_product_is_free'] == 0 || empty($ar['shop_product_is_free'])){echo "checked";} echo "> "._CMN_NO."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_IS_VIRTUAL."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"prod_is_virtual\" value=\"1\" "; if ($ar['shop_product_is_virtual'] == 1){echo "checked";} echo "> "._SHOP_PROD_IS_VIRTUAL_YES."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"prod_is_virtual\" value=\"0\" "; if ($ar['shop_product_is_virtual'] == 0 || empty($ar['shop_product_is_virtual'])){echo "checked";} echo "> "._SHOP_PROD_IS_VIRTUAL_NO."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_QTY_BOX."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"prod_qty_box_status\" value=\"1\" "; if ($ar['shop_product_qty_box_status'] == 1){echo "checked";} echo "> "._SHOP_PROD_QTY_BOX_YES."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"prod_qty_box_status\" value=\"0\" "; if ($ar['shop_product_qty_box_status'] == 0 || empty($ar['shop_product_qty_box_status'])){echo "checked";} echo "> "._SHOP_PROD_QTY_BOX_NO."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_ALLOW_SELL_ALWAYS."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"shop_product_allow_order_if_stock_is_0\" value=\"1\" "; if ($ar['shop_product_allow_order_if_stock_is_0'] == 1){echo "checked";} echo "> "._CMN_YES."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shop_product_allow_order_if_stock_is_0\" value=\"0\" "; if ($ar['shop_product_allow_order_if_stock_is_0'] == 0 || empty($ar['shop_product_allow_order_if_stock_is_0'])){echo "checked";} echo "> "._CMN_NO."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_WEIGHT."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_weight\" maxlength=\"20\" value=\"".$ar['shop_product_weight']."\" size=\"20\"> "._SHOP_PROD_WEIGHT_HELP."</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_MIN_ORDER."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_min_order\" maxlength=\"11\" value=\"".$ar['shop_product_quantity_order_min']."\" size=\"10\"> "._SHOP_PROD_MIN_ORDER_PC."</td>\n";
	echo "			</tr>\n";
	echo "			<!-- <tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "				<strong>"._SHOP_PROD_ORDER_UNITS."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_order_units\" maxlength=\"11\" value=\"".$ar['shop_product_quantity_order_units']."\" size=\"10\"> "._SHOP_PROD_MIN_ORDER_PC."</td>\n";
	echo "			</tr> -->\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_IMAGE_PREVIEW."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"file\" name=\"shop_prod_file_a\" size=\"20\"><br>\n";
							 if ($ar['shop_product_picture_a'] != ""){ echo "<strong>"._SHOP_PROD_IMAGE_DEL."</strong> <input type=\"checkbox\" name=\"del_product_picture_a\" value=\"1\"><br><img src=\"".$url_shop_prod.$ar['shop_product_picture_a']."\" alt=\"\" border=\"0\"><br><br>"; } echo "</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_IMAGE."</strong></td>\n";
	echo "				<td align=\"left\" valign=\"top\"><input type=\"file\" name=\"shop_prod_file_b\" size=\"20\"><br>\n";
							if ($ar['shop_product_picture_b'] != ""){ echo "<strong>"._SHOP_PROD_IMAGE_DEL."</strong> <input type=\"checkbox\" name=\"del_product_picture_b\" value=\"1\"><br><img src=\"".$url_shop_prod.$ar['shop_product_picture_b']."\" alt=\"\" border=\"0\">"; } echo "</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "					<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "					<input type=\"radio\" name=\"save\" value=\"1\"> <span>"._ARTICLES_FUNC_SAVE."</span>&nbsp;&nbsp;&nbsp;\n";
	echo "					<input type=\"radio\" name=\"save\" value=\"2\" checked><span>"._ARTICLES_FUNC_SAVE_SEND."</span><br><br>\n";
	echo "					<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "					</form>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>";
 	}
}
/***********************************************************************************************************
*																											
*		ODSTRANENI PRODUKTU 																				
*																											
***********************************************************************************************************/
function DelProd(){
	
	global $db_shop_product,$db_category;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_del") <> 1) { echo _NOTENOUGHPRIV;Products();exit;}
	
	if ($_POST['confirm'] == "true") {
		// Nacteme do pole vsechny podkapitoly a odstranime
		mysql_query("DELETE FROM $db_shop_product WHERE shop_product_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		Products();
		exit();
	}
	if ($_POST['confirm'] == "false"){Products();exit();}
	if ($_POST['confirm'] != "true"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_PROD_DEL."</td>\n";
		echo "	<tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td class=\"nadpis-boxy\">"._CMN_ID."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._SHOP_PROD_CODE."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._SECTION."</td>\n";
		echo "		<td class=\"nadpis-boxy\">"._SHOP_PROD_NAME."</td>\n";
		echo "	</tr>";
		$res = mysql_query("SELECT p.shop_product_id, p.shop_product_name, p.shop_product_product_code, c.category_name FROM $db_shop_product AS p JOIN $db_category AS c ON p.shop_product_master_category = c.category_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$main_category = PrepareFromDB($ar['category_name']);
		$prod_name = PrepareFromDB($ar['shop_product_name']);
		echo "	<tr>";
		echo "		<td>".$ar['shop_product_id']."</td>";
		echo "		<td>".$ar['shop_product_product_code']."</td>";
		echo "		<td>".$main_category."</td>";
		echo " 		<td>".$prod_name."</td>";
		echo "	</tr>";
		echo "</table>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<tr>\n";
		echo "			<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._SHOP_PROD_DEL_CHECK."</span></strong></td>\n";
		echo "		</tr>\n";
		echo "		<td width=\"50\" valign=\"top\">\n";
		echo "			<form action=\"sys_save.php?action=del_prod&amp;id=".$_GET['id']."\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td width=\"800\" valign=\"top\">\n";
		echo "			<form action=\"sys_save.php?action=del_prod\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		T-SHIRTS - BARVY																					
*																											
***********************************************************************************************************/
function ClothesColors(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_clothes_colors,$db_shop_man;
	global $eden_cfg;
	global $ftp_path_shop_clothes_colors;
	global $url_shop_clothes_colors;
	
	if ($_GET['action'] != "clothes_add_color"){	
		$res = mysql_query("SELECT shop_clothes_colors_title, shop_clothes_colors_producer, shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3 FROM $db_shop_clothes_colors WHERE shop_clothes_colors_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CL_COLORS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	if ($_GET['action'] == "clothes_del_color"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_color" || $_GET['action'] == ""){echo "clothes_add_color";} elseif ($_GET['action'] == "clothes_edit_color"){echo "clothes_edit_color";} else {echo "clothes_del_color";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CL_COLORS_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>	\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_color" || $_GET['action'] == ""){echo "clothes_add_color";} else {echo "clothes_edit_color";} echo "\" method=\"post\" enctype=\"multipart/form-data\" >\n";
	echo "			<strong>"._SHOP_CL_COLORS_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"clothes_color_title\" maxlength=\"50\" size=\"50\" "; if ($_GET['action'] == "clothes_edit_color"){echo "value=\"".$ar['shop_clothes_colors_title']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_CL_COLORS_PRODUCER."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select name=\"clothes_color_producer\" style=\"width: 150px;\">";
						$res2 = mysql_query("SELECT shop_manufacturers_name FROM $db_shop_man ORDER BY shop_manufacturers_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						echo "<option value=\"".$ar2['shop_manufacturers_name']."\" selected></option>";
						while ($ar2 = mysql_fetch_array($res2))	{
							echo "<option value=\"".$ar2['shop_manufacturers_name']."\" ";
							if ($ar2['shop_manufacturers_name'] == $ar['shop_clothes_colors_producer']) { echo "selected=\"selected\"";}
							echo ">".PrepareFromDB($ar2['shop_manufacturers_name'])."</option>";
						}
	echo "				</select>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_COLORS_PREFIX."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">";
	echo "			<select name=\"clothes_color_prefix\" style=\"width: 150px;\">\n";
	echo "				<option value=\"1\" "; if ($ar['shop_clothes_colors_prefix'] == 1) { echo "selected=\"selected\"";} echo ">"._SHOP_CL_COLORS_UNICOLOR."</option>\n";
	echo "				<option value=\"2\" "; if ($ar['shop_clothes_colors_prefix'] == 2) { echo "selected=\"selected\"";} echo ">"._SHOP_CL_COLORS_DUOCOLOR."</option>\n";
	echo "				<option value=\"3\" "; if ($ar['shop_clothes_colors_prefix'] == 3) { echo "selected=\"selected\"";} echo ">"._SHOP_CL_COLORS_TRICOLOR."</option>\n";
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_COLORS_HEX."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">";
				if ($_GET['action'] == "clothes_add_color" || $ar['shop_clothes_colors_prefix'] == 1 || $ar['shop_clothes_colors_prefix'] == 2 || $ar['shop_clothes_colors_prefix'] == 3){ echo "#<input type=\"text\" name=\"clothes_color_hex_1\" maxlength=\"6\" size=\"8\" "; if ($_GET['action'] == "clothes_edit_color"){ echo "value=\"".$ar['shop_clothes_colors_hex_1']."\"";} echo ">"; if ($ar['shop_clothes_colors_prefix'] == 1){echo "<br><div style=\"width:40px; height:40px; border: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_1']."\"></div>";}}
				if ($ar['shop_clothes_colors_prefix'] == 2 || $ar['shop_clothes_colors_prefix'] == 3){echo "#<input type=\"text\" name=\"clothes_color_hex_2\" maxlength=\"6\" size=\"8\" "; if ($_GET['action'] == "clothes_edit_color"){echo "value=\"".$ar['shop_clothes_colors_hex_2']."\"";} echo ">"; if ($ar['shop_clothes_colors_prefix'] == 2){ echo "<br><div style=\"width:30px; height:40px; display: inline; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_1']."\"></div><div style=\"width:10px; height:40px; display: inline; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_2']."\"></div>"; }}
				if ($ar['shop_clothes_colors_prefix'] == 3){ echo "#<input type=\"text\" name=\"clothes_color_hex_3\" maxlength=\"6\" size=\"8\" "; if ($_GET['action'] == "clothes_edit_color"){echo "value=\"".$ar['shop_clothes_colors_hex_3']."\"";} echo ">"; if ($ar['shop_clothes_colors_prefix'] == 3){}}
				
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "	<!-- <tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_COLORS_PICTURE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"file\" name=\"clothes_color_picture\" size=\"30\">\n";
	echo "		</td>\n";
	echo "	</tr> -->\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clothes_add_color" || $_GET['action'] == ""){echo _SHOP_CL_COLORS_ADD;} else {echo _SHOP_CL_COLORS_EDIT;} echo "\">\n";
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
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_COLORS."</span></td>\n";
	echo "		<!-- <td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_PICTURE."</span></td> -->\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_HEX."</span></td>\n";
	echo "		<td width=\"80\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_HEX."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_TITLE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_COLORS_PRODUCER."</span></td>\n";
	echo "	</tr>\n";
 	$res = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_prefix, shop_clothes_colors_picture, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3, shop_clothes_colors_title, shop_clothes_colors_producer FROM $db_shop_clothes_colors ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title  ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   	echo "	<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "		<td width=\"80\" align=\"center\">\n";
	  					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_edit_color&amp;id=".$ar['shop_clothes_colors_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
						if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_del_color&amp;id=".$ar['shop_clothes_colors_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "		</td>\n";
		echo "		<td width=\"20\" align=\"left\">".$ar['shop_clothes_colors_id']."</td>\n";
		echo "		<td width=\"20\" align=\"left\">".$ar['shop_clothes_colors_prefix']."</td>\n";
		echo "		<!-- <td width=\"50\" align=\"left\"><img src=\"".$url_shop_clothes_colors.$ar['shop_clothes_colors_picture']."\" alt=\"\" border=\"0\"></td> -->\n";
		echo "		<td width=\"50\" align=\"left\">\n";
						if ($ar['shop_clothes_colors_prefix'] == 1){
							echo "<div style=\"width:40px; height:40px; border: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_1']."\"></div>";
						}elseif ($ar['shop_clothes_colors_prefix'] == 2){
							echo "<div style=\"width:30px; height:40px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_1']."\"></div><div style=\"width:10px; height:40px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_2']."\"></div>";
						}elseif($ar['shop_clothes_colors_prefix'] == 3){
							echo "<div style=\"width:30px; height:40px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_1']."\"></div><div style=\"width:10px; height:40px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar['shop_clothes_colors_hex_2']."\"></div>";
						}
		echo "		</td>\n";
		echo "		<td width=\"80\" align=\"left\">#".$ar['shop_clothes_colors_hex_1']; if ($ar['shop_clothes_colors_prefix'] == 2 || $ar['shop_clothes_colors_prefix'] == 3){ echo "<br>#".$ar['shop_clothes_colors_hex_2'];} if ($ar['shop_clothes_colors_prefix'] == 3){ echo "<br>#".$ar['shop_clothes_colors_hex_3'];} echo "</td>\n";
		echo "		<td align=\"left\">".$ar['shop_clothes_colors_title']."</td>\n";
		echo "		<td align=\"left\">".$ar['shop_clothes_colors_producer']."</td>\n";
		echo "	</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES	- VELIKOSTI																					
*																											
***********************************************************************************************************/
function ClothesSize(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_clothes_size;
	
	if ($_GET['action'] != "clothes_add_size"){	
		$res = mysql_query("SELECT shop_clothes_size_size, shop_clothes_size_description FROM $db_shop_clothes_size WHERE shop_clothes_size_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CL_SIZE_SIZES."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "clothes_del_size"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_size" || $_GET['action'] == ""){echo "clothes_add_size";} elseif ($_GET['action'] == "clothes_edit_size"){echo "clothes_edit_size";} else {echo "clothes_del_size";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CL_SIZE_DELCHECK."</span></strong>\n";
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
	echo "		<td width=\"250\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_size" || $_GET['action'] == ""){echo "clothes_add_size";} else {echo "clothes_edit_size";} echo "\" method=\"post\" enctype=\"multipart/form-data\" >\n";
	echo "			<strong>"._SHOP_CL_SIZE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"clothes_size_size\" maxlength=\"10\" size=\"15\" "; if ($_GET['action'] == "clothes_edit_size"){echo "value=\"".$ar['shop_clothes_size_size']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_SIZE_DESC."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\"><textarea cols=\"50\" rows=\"5\" name=\"clothes_size_description\">"; if ($_GET['action'] == "clothes_edit_size"){echo $ar['shop_clothes_size_description'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clothes_add_size" || $_GET['action'] == ""){echo _SHOP_CL_SIZE_ADD;} else {echo _SHOP_CL_SIZE_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_SIZE."</span></td>\n";
	echo "		<td align=\"left\">&nbsp;</td>\n";
	echo "	</tr>\n";
	$res = mysql_query("SELECT shop_clothes_size_id, shop_clothes_size_size FROM $db_shop_clothes_size ORDER BY shop_clothes_size_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   	echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">\n";
			  		if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_edit_size&amp;id=".$ar['shop_clothes_size_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
			  		if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_del_size&amp;id=".$ar['shop_clothes_size_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['shop_clothes_size_id']."</td>\n";
		echo "	<td width=\"50\" align=\"left\">".$ar['shop_clothes_size_size']."</td>\n";
		echo "	<td align=\"left\">&nbsp;</td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES	- STYLY																						
*																											
***********************************************************************************************************/
function ClothesStyle(){
	
	global $db_shop_clothes_style,$db_shop_clothes_style_parents,$db_shop_clothes_colors,$db_shop_clothes_size,$db_shop_gender;
	global $eden_cfg,$ftp_path_shop_clothes_style;
	global $url_shop_clothes_style;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	$res_style_parent = mysql_query("SELECT shop_clothes_style_parents_id, shop_clothes_style_parents_title, shop_clothes_style_parents_description, shop_clothes_style_parents_colors, shop_clothes_style_parents_sizes FROM $db_shop_clothes_style_parents WHERE shop_clothes_style_parents_id=".(float)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_style_parent = mysql_fetch_array($res_style_parent);
	
	if ($_GET['action'] != "clothes_add_style"){
		$res_style = mysql_query("SELECT shop_clothes_style_color_id, shop_clothes_style_picture_front, shop_clothes_style_picture_back, shop_clothes_style_show, shop_clothes_style_sizes FROM $db_shop_clothes_style WHERE shop_clothes_style_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_style = mysql_fetch_array($res_style);
	}
	
	$res_style_color = mysql_query("SELECT shop_clothes_style_color_id FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".(float)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while ($ar_style_color = mysql_fetch_array($res_style_color)){
		$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
		$i++;
	}
	if (count($style_color_list) == 0){$style_color_list = array();}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CL_STYLE_STYLES."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "clothes_del_style"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;pid=".$_GET['pid']."&amp;action="; if ($_GET['action'] == "clothes_add_style" || $_GET['action'] == ""){echo "clothes_add_style";} elseif ($_GET['action'] == "clothes_edit_style"){ echo "clothes_edit_style";} else {echo "clothes_del_style";} echo "\" method=\"post\" >\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CL_STYLE_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>	\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;pid=".$_GET['pid']."&amp;action="; if ($_GET['action'] == "clothes_add_style" || $_GET['action'] == ""){echo "clothes_add_style";} else {echo "clothes_edit_style";} echo "\" method=\"post\" enctype=\"multipart/form-data\" >\n";
	echo "			<strong>"._SHOP_CL_STYLE_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\"><strong>".$ar_style_parent['shop_clothes_style_parents_title']."</strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_DESC."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">".$ar_style_parent['shop_clothes_style_parents_description']."</td>	\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_CODE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
					$style_code = $ar_style_parent['shop_clothes_style_parents_id'];
					echo Zerofill($style_code,1000)."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_PICTURE_FRONT."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"file\" name=\"clothes_style_picture_front\" size=\"30\"><br>\n";
					if ($ar_style['shop_clothes_style_picture_front'] != ""){ echo "<img src=\"".$url_shop_clothes_style.$ar_style['shop_clothes_style_picture_front']."\" width=\"150px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_PICTURE_BACK."</strong><br><br><br>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<input type=\"file\" name=\"clothes_style_picture_back\" size=\"30\"><br>\n";
					if ($ar_style['shop_clothes_style_picture_back'] != ""){ echo "<img src=\"".$url_shop_clothes_style.$ar_style['shop_clothes_style_picture_back']."\" width=\"150px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_SHOW."</strong><br><br><br>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<input type=\"checkbox\" name=\"clothes_style_show\" value=\"1\" "; if ($ar_style['shop_clothes_style_show'] == 1) {echo "checked";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_COLORS."</strong><br><br>\n";
	echo "			<div style=\"width:100px; height:50px; border: 1px solid #000000; background-color:#FFDEDF\" align=\"center\"><div style=\"margin-top:15px;\">Done</div></div>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<table>";
			$color_list = explode("#", $ar_style_parent['shop_clothes_style_parents_colors']);
			if (count($color_list) == 0){$color_list = array();}
			$res_color = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_title, shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3 
			FROM $db_shop_clothes_colors 
			ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while($ar_color = mysql_fetch_array($res_color)){
				
				/* SIZE */
				$size_number = 1;
				$size_list_parent = explode("#", $ar_style_parent['shop_clothes_style_parents_sizes']);
				$size_list = explode("#", $ar_style['shop_clothes_style_sizes']);
				if (count($size_list) == 0){$size_list = array();}
				$size_count = count($size_list_parent);
				$res_size = mysql_query("SELECT shop_clothes_size_id, shop_clothes_size_size FROM $db_shop_clothes_size ORDER BY shop_clothes_size_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				if (in_array($ar_color['shop_clothes_colors_id'], $color_list)){
					echo "<tr "; if (in_array($ar_color['shop_clothes_colors_id'], $style_color_list)){ if ($ar_color['shop_clothes_colors_id'] == $ar_style['shop_clothes_style_color_id']){echo "bgcolor=\"#B3C3DD\"";} else {echo "bgcolor=\"#FFDEDF\"";}} echo ">";
					echo "	<td width=\"30px\" valign=\"top\" rowspan=\"2\">";
							if ($ar_color['shop_clothes_colors_prefix'] == 1){
								echo "<div style=\"width:30px; height:30px; border: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div>";
							} elseif ($ar_color['shop_clothes_colors_prefix'] == 2){
								echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
							} elseif($ar_color['shop_clothes_colors_prefix'] == 3){
								echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
							}
					echo "	</td>";
					echo "	<td width=\"310px\" colspan=\"".$size_count."\" valign=\"top\">";
					echo "		<input type=\"radio\" name=\"clothes_style_color_id\" "; if ($ar_color['shop_clothes_colors_id'] == $ar_style['shop_clothes_style_color_id']){echo "checked";} echo " value=\"".$ar_color['shop_clothes_colors_id']."\"> ".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar_color['shop_clothes_colors_id'],100)." - ".$ar_color['shop_clothes_colors_title']."<br>";
					echo "	</td>";
					echo "</tr>";
					echo "<tr ";
					if (in_array($ar_color['shop_clothes_colors_id'], $style_color_list)){ if ($ar_color['shop_clothes_colors_id'] == $ar_style['shop_clothes_style_color_id']){echo "bgcolor=\"#B3C3DD\"";} else {echo "bgcolor=\"#FFDEDF\"";}} echo ">";
						while($ar_size = mysql_fetch_array($res_size)){
							if (in_array($ar_size['shop_clothes_size_id'], $size_list_parent)) {
								echo "	<td>\n";
								echo "		<input type=\"hidden\" name=\"clothes_style_size_num\" value=\"".$size_number."\">\n";
								echo "		<input type=\"hidden\" name=\"clothes_style_size_data[".$size_number."_size_id]\" value=\"".$ar_size['shop_clothes_size_id']."\">\n";
								echo "		<input type=\"checkbox\" name=\"clothes_style_size_data[".$size_number."_size]\" value=\"1\" "; if (in_array($ar_size['shop_clothes_size_id'], $size_list) && $ar_style['shop_clothes_style_color_id'] == $ar_color['shop_clothes_colors_id']) {echo "checked";} echo ">".$ar_size['shop_clothes_size_size']."\n";
								echo "		<!-- <input type=\"text\" maxlength=\"6\" size=\"5\" name=\"clothes_style_size_data[".$size_number."_pcs]\" value=\"\"> -->\n";
								echo "	</td>\n";
								$size_number++;
							}
						}
					echo "</tr>\n";
					}
				}
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><br><br><br>\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clothes_add_style" || $_GET['action'] == ""){echo _SHOP_CL_STYLE_ADD;} else {echo _SHOP_CL_STYLE_EDIT;} echo "\">\n";
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
	echo "		<td width=\"80\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_CODE."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_SHOW."</span></td>\n";
	echo "		<td width=\"250\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_COLORS."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_SIZES."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_PICTURE."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_PICTURE."</span></td>\n";
	echo "	</tr>";
	 	$res = mysql_query("SELECT shop_clothes_style_id, shop_clothes_style_color_id, shop_clothes_style_show, shop_clothes_style_sizes, shop_clothes_style_picture_front, shop_clothes_style_picture_back FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']." ORDER BY shop_clothes_style_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=0;
		while($ar = mysql_fetch_array($res)){
			$res_color = mysql_query("SELECT shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3, shop_clothes_colors_title FROM $db_shop_clothes_colors WHERE shop_clothes_colors_id=".$ar['shop_clothes_style_color_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_color = mysql_fetch_array($res_color);
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" align=\"center\" valign=\"top\">";
	 				if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_edit_style&amp;id=".$ar['shop_clothes_style_id']."&amp;pid=".$_GET['pid']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_del_style&amp;id=".$ar['shop_clothes_style_id']."&amp;pid=".$_GET['pid']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			echo "	</td>";
			echo "	<td width=\"20\" align=\"left\" valign=\"top\">".$ar['shop_clothes_style_id']."</td>";
			echo "	<td width=\"80\" align=\"left\" valign=\"top\">";
			echo 		Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar['shop_clothes_style_color_id'],100);
			echo "	</td>";
			echo "	<td width=\"50\" align=\"left\" valign=\"top\" "; if ($ar['shop_clothes_style_show'] == 1){echo "bgcolor=\"#FFDEDF\"";} echo ">"; if ($ar['shop_clothes_style_show'] == 1){echo _CMN_YES;} else {echo _CMN_NO;} echo "</td>";
			echo "	<td width=\"250\" align=\"left\" valign=\"top\">";
						if ($ar_color['shop_clothes_colors_prefix'] == 1){
							echo "<div style=\"width:30px; height:15px; float: left; border: 1px solid #000000; margin-right:5px; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div>";
						}elseif ($ar_color['shop_clothes_colors_prefix'] == 2){
							echo "<div style=\"width:23px; height:15px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:15px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; margin-right:5px; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
						}elseif($ar_color['shop_clothes_colors_prefix'] == 3){
							echo "<div style=\"width:23px; height:15px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:15px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
						} echo "  ".$ar_color['shop_clothes_colors_title'];
			echo "	</td>";
			echo "	<td align=\"left\">"; 
					$sizes_list = explode("#", $ar['shop_clothes_style_sizes']);
				 	$sizes_num = count($sizes_list);
					$z=0;
					$y=1;
					while ($sizes_num > $y){
						$res_sizes = mysql_query("SELECT shop_clothes_size_size FROM $db_shop_clothes_size WHERE shop_clothes_size_id=".$sizes_list[$z]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar_sizes = mysql_fetch_array($res_sizes);
						echo $ar_sizes['shop_clothes_size_size'];
						if ($y+1 < $sizes_num){echo ", ";}
						$z++;
						$y++;
					}
			echo "	</td>";
			echo "	<td width=\"50\" align=\"left\" valign=\"top\">"; if ($ar['shop_clothes_style_picture_front'] != ""){echo _CMN_YES;} else {echo _CMN_NO;} echo "</td>";
			echo "	<td width=\"50\" align=\"left\" valign=\"top\">"; if ($ar['shop_clothes_style_picture_back'] != ""){echo _CMN_YES;} else {echo _CMN_NO;} echo "</td>";
			echo "</tr>";
			$i++;
	 	}
		echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES	- STYLY PARENTS																				
*																											
***********************************************************************************************************/
function ClothesStyleParents(){
	
	global $db_shop_clothes_style_parents,$db_shop_clothes_colors,$db_shop_clothes_size,$db_shop_gender,$db_setup,$db_shop_setup,$db_shop_discount_category;
	global $url_shop_clothes_style_parent;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	if ($_GET['action'] != "clothes_add_style_parents"){	
		$res = mysql_query("SELECT 
		shop_clothes_style_parents_title, 
		shop_clothes_style_parents_description, 
		shop_clothes_style_parents_genders, 
		shop_clothes_style_parents_sizes, 
		shop_clothes_style_parents_extrapay, 
		shop_clothes_style_parents_weight, 
		shop_clothes_style_parents_picture, 
		shop_clothes_style_parents_colors,
		shop_clothes_style_parents_discount_cat_seller_1,
		shop_clothes_style_parents_discount_cat_seller_2,
		shop_clothes_style_parents_discount_cat_cust_1,
		shop_clothes_style_parents_discount_cat_cust_2
		 FROM $db_shop_clothes_style_parents WHERE shop_clothes_style_parents_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	$res_setup = mysql_query("SELECT ss.shop_setup_currency FROM $db_shop_setup AS ss, $db_setup AS s WHERE ss.shop_setup_lang=s.setup_basic_lang") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CL_STYLE_PARENTS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	if ($_GET['action'] == "clothes_del_style_parents"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_style_parents" || $_GET['action'] == ""){echo "clothes_add_style_parents";} elseif ($_GET['action'] == "clothes_edit_style_parents"){echo "clothes_edit_style_parents";} else {echo "clothes_del_style_parents";} echo "\" method=\"post\" >\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CL_STYLE_DELCHECK."</span></strong>\n";
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
	echo "		<td width=\"250\" align=\"right\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "clothes_add_style_parents" || $_GET['action'] == ""){echo "clothes_add_style_parents";} else {echo "clothes_edit_style_parents";} echo "\" method=\"post\" enctype=\"multipart/form-data\" >\n";
	echo "			<strong>"._SHOP_CL_STYLE_TITLE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"clothes_style_parents_title\" maxlength=\"50\" size=\"50\" "; if ($_GET['action'] == "clothes_edit_style_parents"){echo "value=\"".$ar['shop_clothes_style_parents_title']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_DESC."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\"><textarea cols=\"50\" rows=\"5\" name=\"clothes_style_parents_description\">"; if ($_GET['action'] == "clothes_edit_style_parents"){echo $ar['shop_clothes_style_parents_description'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_GENDER."</strong><br><br><br>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">";
			/* Pokud se edituje, zjistime jetsli v DB neni ulozen zaznam o existenci Gender */
			//$gender_list = explode("#", $ar['shop_clothes_style_parents_genders']);
			$res_gender = mysql_query("SELECT shop_gender_id, shop_gender_title FROM $db_shop_gender ORDER BY shop_gender_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i = 1;
			while($ar_gender = mysql_fetch_array($res_gender)){
				echo "<input type=\"radio\" name=\"clothes_style_parents_gender_data[gender]\" value=\"".$ar_gender['shop_gender_id']."\" "; if ($ar_gender['shop_gender_id'] == $ar['shop_clothes_style_parents_genders'] || ($ar['shop_clothes_style_parents_genders'] == "" && $i == 1)) {echo "checked";} echo "> ".$ar_gender['shop_gender_title']; 
				$i++;
			}
	echo "		</td>";
	echo "	</tr>";
		/* V prvnim kroku nechame vybrat velikosti pro dalsi upravy stylu - moznost pridani urcitych barev k urcitym velikostem */
		/* pokud ma vyrobce napriklad v nabidce urcite velikosti jen v urcitych barvach */
		/* Dale take zde vybrane velikosti budou zobrazeny u jednotlivych barev */
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">";
	echo "			<strong>"._SHOP_CL_STYLE_SIZES."</strong><br><br><br>";
	echo "		</td>";
	echo "		<td align=\"left\" valign=\"top\">";
			/* Pokud se edituje, zjistime jetsli v DB neni ulozen zaznam o existenci Size */
			$size_list = explode("#", $ar['shop_clothes_style_parents_sizes']);
			$res_size = mysql_query("SELECT shop_clothes_size_id, shop_clothes_size_size FROM $db_shop_clothes_size ORDER BY shop_clothes_size_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$size_number = 1;
			while($ar_size = mysql_fetch_array($res_size)){
				echo "<input type=\"hidden\" name=\"clothes_style_parents_size_num\" value=\"".$size_number."\">";
				echo "<input type=\"hidden\" name=\"clothes_style_parents_size_data[".$size_number."_size_id]\" value=\"".$ar_size['shop_clothes_size_id']."\">";
				echo "<input type=\"checkbox\" name=\"clothes_style_parents_size_data[".$size_number."_size]\" value=\"1\" "; if (in_array($ar_size['shop_clothes_size_id'], $size_list)) {echo "checked";} echo "> ".$ar_size['shop_clothes_size_size']; 
				$size_number++;
			}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "		<strong>"._SHOP_CL_STYLE_EXTRAPAY."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"clothes_style_extrapay\" maxlength=\"10\" value=\"".$ar['shop_clothes_style_parents_extrapay']."\" size=\"10\"> ".CurrencySymbol($ar_setup['shop_setup_currency'])."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">";
	echo "			<strong>"._SHOP_CL_STYLE_SELLER_DISCOUNT_CATEGORY."</strong>";
	echo "		</td>";
	echo "		<td align=\"left\" valign=\"top\">";
					/* Pokud je shop_discount_category_type mensi nez 30 je to typ slevove kategorie pro prodejce */
					$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"clothes_style_parents_discount_cat_seller_1\" size=\"1\">";
					echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_seller_1']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
					while ($ar_discount = mysql_fetch_array($res_discount)){
						echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_seller_1']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
					}
					echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	/* Zatim se nepouziva
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">&nbsp;</td>";
	echo "		<td align=\"left\" valign=\"top\">";
					$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type < 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"clothes_style_parents_discount_cat_seller_2\" size=\"1\">";
					echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_seller_2']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
					while ($ar_discount = mysql_fetch_array($res_discount)){
						echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_seller_2']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
					}
					echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	*/
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">";
	echo "			<strong>"._SHOP_CL_STYLE_CUSTOMER_DISCOUNT_CATEGORY."</strong>";
	echo "		</td>";
	echo "		<td align=\"left\" valign=\"top\">";
					$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type > 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"clothes_style_parents_discount_cat_cust_1\" size=\"1\">";
					echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_cust_1']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
					while ($ar_discount = mysql_fetch_array($res_discount)){
						echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_cust_1']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
					}
					echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	/* Zatim se nepouziva
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">&nbsp;</td>";
	echo "		<td align=\"left\" valign=\"top\">";
					$res_discount = mysql_query("SELECT shop_discount_category_id, shop_discount_category_name FROM $db_shop_discount_category WHERE shop_discount_category_parent_id=0 AND shop_discount_category_type > 30") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"clothes_style_parents_discount_cat_cust_2\" size=\"1\">";
					echo "<option value=\"0\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_cust_2']){echo " selected";}  echo ">"._SHOP_CL_STYLE_CHOOSE_DISCOUNT_CATEGORY."</option>";
					while ($ar_discount = mysql_fetch_array($res_discount)){
						echo "<option value=\"".$ar_discount['shop_discount_category_id']."\""; if ($ar_discount['shop_discount_category_id'] == $ar['shop_clothes_style_parents_discount_cat_cust_2']){echo " selected";}  echo ">".$ar_discount['shop_discount_category_name']."</option>";
					}
					echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	*/
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "		<strong>"._SHOP_CL_STYLE_WEIGHT."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"clothes_style_weight\" maxlength=\"20\" value=\"".$ar['shop_clothes_style_parents_weight']."\" size=\"20\"> "._SHOP_PROD_WEIGHT_HELP."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_PARENTS_PICTURE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"file\" name=\"clothes_style_parents_picture\" size=\"30\"><br>\n";
					if ($ar['shop_clothes_style_parents_picture'] != ""){ echo "<img src=\"".$url_shop_clothes_style_parent.$ar['shop_clothes_style_parents_picture']."\" width=\"100px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_STYLE_COLORS."</strong>\n";
	echo "		</td>\n";
	echo "		<td>&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<table>";
			/* Pokud se edituje, zjistime jetsli v DB neni ulozen zaznam o existenci Size */
			$color_list = explode("#", $ar['shop_clothes_style_parents_colors']);
			$res_color = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_title, shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3, shop_clothes_colors_producer 
			FROM $db_shop_clothes_colors ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title  ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i = 1;
			$color_number = 1;
			while($ar_color = mysql_fetch_array($res_color)){
				if ($i == 1) { echo "<tr>";}
					echo "<td width=\"70px\">";
						if ($ar_color['shop_clothes_colors_prefix'] == 1){
							echo "<div style=\"width:30px; height:30px; float: left; border: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\" title=\"".$ar_color['shop_clothes_colors_producer']."\"></div>";
						}elseif ($ar_color['shop_clothes_colors_prefix'] == 2){
							echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
						}elseif($ar_color['shop_clothes_colors_prefix'] == 3){
							echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
						}
					echo "</td>\n";
					echo "<td width=\"310px\" valign=\"top\">\n";
					echo "	<input type=\"hidden\" name=\"clothes_style_parents_color_num\" value=\"".$color_number."\">\n";
					echo "	<input type=\"hidden\" name=\"clothes_style_parents_color_data[".$color_number."_color_id]\" value=\"".$ar_color['shop_clothes_colors_id']."\">\n";
					echo "	<input type=\"checkbox\" name=\"clothes_style_parents_color_data[".$color_number."_color]\" value=\"1\" "; if (in_array($ar_color['shop_clothes_colors_id'], $color_list)) {echo "checked";} echo "> ".$ar_color['shop_clothes_colors_title']."<br>".$ar_color['shop_clothes_colors_producer']."\n";
					echo "</td>";
				if ($i == 4) { echo "</tr>"; $i=0;}
				$i++;
				$color_number++;
			}
	echo "			</table>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><br><br><br>\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clothes_add_style_parents" || $_GET['action'] == ""){echo _SHOP_CL_STYLE_ADD;} else {echo _SHOP_CL_STYLE_EDIT;} echo "\">\n";
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
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_STYLE_PARENTS_TITLE."</span></td>\n";
	echo "	</tr>\n";
			$res = mysql_query("SELECT shop_clothes_style_parents_id, shop_clothes_style_parents_title FROM $db_shop_clothes_style_parents ORDER BY shop_clothes_style_parents_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=0;
			while($ar = mysql_fetch_array($res)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" align=\"center\">\n";
			   	if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_edit_style_parents&amp;id=".$ar['shop_clothes_style_parents_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_del_style_parents&amp;id=".$ar['shop_clothes_style_parents_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			echo "	</td>\n";
			echo "	<td width=\"20\" align=\"left\">".$ar['shop_clothes_style_parents_id']."</td>\n";
			echo "	<td align=\"left\"><a href=\"modul_shop.php?action=clothes_add_style&amp;project=".$_SESSION['project']."&amp;pid=".$ar['shop_clothes_style_parents_id']."\">".$ar['shop_clothes_style_parents_title']."</a></td>\n";
			echo "</tr>";
			$i++;
	 	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES	- SHOW DESIGNS																				
*																											
***********************************************************************************************************/
function ClothesShowDesigns(){
	
	global $db_shop_clothes_design,$db_shop_clothes_colors,$db_shop_clothes_size,$db_shop_gender,$db_shop_product_clothes;
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" class=\"nadpis\">"._SHOP." - "._SHOP_CL_DESIGNS."</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td>".ShopMenu()."</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "			<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "			<td width=\"60\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_PRODUCTS."</span></td>\n";
	echo "			<td width=\"80\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_AVAIL."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_SHOW."</span></td>\n";
	echo "			<td align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_TITLE."</span></td>\n";
	echo "			<td width=\"50\" align=\"right\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_BASIC_PRICE."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_GENDER_MEN."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_GENDER_WOMEN."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_GENDER_KIDS."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_IMG."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_IMG."</span></td>\n";
	echo "			<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._SHOP_CL_DESIGN_IMG."</span></td>\n";
	echo "			\n";
	echo "		</tr>";
 	$res_design = mysql_query("SELECT 
	shop_clothes_design_id, 
	shop_clothes_design_title, 
	shop_clothes_design_date_available, 
	shop_clothes_design_img_1, 
	shop_clothes_design_img_3, 
	shop_clothes_design_img_4, 
	shop_clothes_design_selling_price, 
	shop_clothes_design_gender_men, 
	shop_clothes_design_gender_women,
	shop_clothes_design_gender_kids, 
	shop_clothes_design_show FROM $db_shop_clothes_design ORDER BY shop_clothes_design_show DESC, shop_clothes_design_date_available DESC, shop_clothes_design_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while($ar_design = mysql_fetch_array($res_design)){
		/* Spocitame pocet produktu */
		$res_product_clothes = mysql_query("SELECT COUNT(*) FROM $db_shop_product_clothes WHERE shop_product_clothes_design_id=".$ar_design['shop_clothes_design_id']." AND shop_product_clothes_active=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_product_clothes = mysql_fetch_array($res_product_clothes);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">";
					if (CheckPriv("groups_shop_add") == 1){echo "<a href=\"modul_shop.php?action=clothes_generate_products&amp;id=".$ar_design['shop_clothes_design_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_gen_products.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._SHOP_CL_GEN_PRODUCTS."\"></a>";}
	 				if (CheckPriv("groups_shop_edit") == 1){echo "<a href=\"modul_shop.php?action=clothes_edit_design&amp;id=".$ar_design['shop_clothes_design_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_shop_del") == 1){echo "<a href=\"modul_shop.php?action=clothes_del_design&amp;id=".$ar_design['shop_clothes_design_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"right\">".$ar_design['shop_clothes_design_id']."</td>\n";
		echo "	<td width=\"60\" align=\"right\">".$num_product_clothes[0]."</td>\n";
		echo "	<td width=\"80\" align=\"right\">".FormatDatetime($ar_design['shop_clothes_design_date_available'],"d.m.Y")."</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_show'] == "1"){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td align=\"left\">".$ar_design['shop_clothes_design_title']."</td>\n";
		echo "	<td width=\"50\" align=\"right\">".PriceFormat($ar_design['shop_clothes_design_selling_price'])."</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_gender_men'] == 1){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_gender_women'] == 1){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_gender_kids'] == 1){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_img_1'] != ""){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_img_3'] != ""){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "	<td width=\"30\" align=\"center\">"; if ($ar_design['shop_clothes_design_img_4'] != ""){ echo "<img src=\"images/sys_yes.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} else {echo "<img src=\"images/sys_no.gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">";} echo "</td>\n";
		echo "</tr>";
		$i++;
 	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		CLOTHES	- DESIGN																					
*																											
***********************************************************************************************************/
function ClothesAddDesign(){
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_clothes_style,$db_shop_clothes_style_parents,$db_shop_clothes_colors,$db_shop_clothes_size,$db_shop_gender;
	global $db_shop_clothes_design,$db_shop_product,$db_category,$db_shop_man,$db_shop_tax_class,$db_shop_tax_rates,$db_admin;
	global $db_setup,$db_shop_setup;
	global $eden_cfg;
	global $ftp_path_shop_clothes_style,$ftp_path_shop_prod;
	global $url_shop_clothes_design,$url_shop_prod;
	global $preview;
	
	// Provereni opravneni
	if ($_GET['action'] == "clothes_add_design"){
		if (CheckPriv("groups_shop_add") <> 1) { echo _NOTENOUGHPRIV;ClothesShowDesigns();exit;}
	}elseif ($_GET['action'] == "clothes_edit_design"){
		if (CheckPriv("groups_shop_edit") <> 1) { echo _NOTENOUGHPRIV;ClothesShowDesigns();exit;}
	}elseif ($_GET['action'] == "clothes_del_design"){
		if (CheckPriv("groups_shop_del") <> 1) { echo _NOTENOUGHPRIV;ClothesShowDesigns();exit;}
	} else {
		 echo _NOTENOUGHPRIV;ClothesShowDesigns();exit;
	}
	
	$res_style_color = mysql_query("SELECT shop_clothes_style_color_id FROM $db_shop_clothes_style") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while ($ar_style_color = mysql_fetch_array($res_style_color)){
		$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
		$i++;
	}
	
	$res_setup = mysql_query("SELECT ss.shop_setup_clothes_img_1_width, ss.shop_setup_clothes_img_1_height, ss.shop_setup_clothes_img_3_width, ss.shop_setup_clothes_img_3_height, ss.shop_setup_clothes_img_5_width, ss.shop_setup_clothes_img_5_height 
	FROM $db_shop_setup AS ss, $db_setup AS s WHERE ss.shop_setup_lang=s.setup_basic_lang") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($_POST['confirm'] != "true"){
		if ($_GET['action'] == "clothes_edit_design"){
			$res_design = mysql_query("SELECT * FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_design = mysql_fetch_array($res_design);
		}
		$design_list = explode("#", $ar_design['shop_clothes_design_styles_and_colors']);
		if (count($design_list) == 0){$design_list = array();}
		
		$clothes_design_title = PrepareFromDB($ar_design['shop_clothes_design_title']);
		/*
		*	Nastaveni Datumu
		*/
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_day = FormatDatetime($ar_design['shop_clothes_design_date_available'],"d");} else {$prod_date_day = date("d");}
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_month = FormatDatetime($ar_design['shop_clothes_design_date_available'],"m");} else {$prod_date_month = date("m");}
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_year = FormatDatetime($ar_design['shop_clothes_design_date_available'],"Y");} else {$prod_date_year = date("Y");}
			
	if ($_GET['action'] == "clothes_del_design"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;pid=".$_GET['pid']."&amp;action="; if ($_GET['action'] == "clothes_add_style" || $_GET['action'] == ""){echo "clothes_add_style";} elseif ($_GET['action'] == "clothes_edit_style"){ echo "clothes_edit_style";} else { echo "clothes_del_style";} echo "\" method=\"post\" >\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._SHOP_CL_DESIGN_DELCHECK."</span></strong>\n";
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
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "; if ($_GET['action'] == "clothes_add_design"){ echo _SHOP_CL_DESIGN_ADD;} else {echo _SHOP_CL_DESIGN_EDIT." - ID #".$_GET['id'];} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".ShopMenu()."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "clothes_add_design"){echo "clothes_add_design";} else {echo "clothes_edit_design";} echo "&amp;id=".$_GET['id']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\">\n";
	echo "		<strong>"._SHOP_CL_DESIGN_TITLE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"clothes_design_title\" maxlength=\"250\" value=\"".$clothes_design_title."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\"><strong>"._SHOP_CL_DESIGN_AUTHOR."</strong></td>\n";
	echo "		<td align=\"left\"><select name=\"clothes_design_author_id\">";
				$res_adm = mysql_query("SELECT admin_id, admin_firstname, admin_name FROM $db_admin WHERE admin_status='admin' ORDER BY admin_name ASC, admin_firstname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_adm = mysql_fetch_array($res_adm)){
					echo "<option value=\"".$ar_adm['admin_id']."\""; if (($ar_design['shop_clothes_design_author_id'] == $ar_adm['admin_id']) || ($_GET['action'] == "clothes_add_design" && $ar_adm['admin_id'] == $_SESSION['loginid'])){ echo "selected=\"selected\"";} echo ">".$ar_adm['admin_firstname']." ".$ar_adm['admin_name']."</option>";
				}
	echo "		</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_CODE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
					echo Zerofill($_GET['id'],10000)."\n";
	echo "		<input type=\"hidden\" name=\"clothes_design_code\" value=\"".$_GET['id']."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_SHOW."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<input type=\"checkbox\" name=\"clothes_design_show\" value=\"1\" "; if ($ar_design['shop_clothes_design_show'] == 1 || $_GET['action'] == "clothes_add_design") {echo "checked";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_CL_DESIGN_STATUS."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"clothes_design_status\" style=\"width: 150px;\">\n";
	echo "			<option value=\"0\" "; if ($ar_design['shop_clothes_design_status'] == 0){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_0."</option>\n";
	echo "			<option value=\"1\" "; if ($ar_design['shop_clothes_design_status'] == 1 || $_GET['action'] == "clothes_add_design"){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_1."</option>\n";
	echo "			<option value=\"2\" "; if ($ar_design['shop_clothes_design_status'] == 2){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_2."</option>\n";
	echo "			<option value=\"3\" "; if ($ar_design['shop_clothes_design_status'] == 3){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_STATUS_3."</option>\n";
	echo "		</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_CL_DESIGN_AVAILEBLE_DATE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<select name=\"prod_date_day\" style=\"width: 40px;\">";
				for($i=1;$i<32;$i++){
					echo "<option name=\"prod_date_day\" value=\"".$i."\" ";
					if ($prod_date_day == $i) { echo "selected=\"selected\"";}
					echo ">".$i."</option>";
				}
				echo "</select>";
				echo "<select name=\"prod_date_month\" style=\"width: 40px;\">";
				for($i=1;$i<13;$i++){
					echo "<option name=\"prod_date_month\" value=\"".$i."\" ";
					if ($prod_date_month == $i) { echo "selected=\"selected\"";}
					echo ">".$i."</option>";
				}
				echo "</select>";
				echo "<select name=\"prod_date_year\" style=\"width: 60px;\">";
				for($i=1990;$i<2050;$i++){
					echo "<option name=\"prod_date_year\" value=\"".$i."\" ";
					if ($prod_date_year == $i) { echo "selected=\"selected\"";}
					echo ">".$i."</option>";
				}
	echo "		</select>";
	echo "	</td>";
	echo "</tr>";
	echo "<tr>";
	echo "	<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_CL_DESIGN_MASTER_CAT."</strong></td>";
	echo "	<td align=\"left\" valign=\"top\"><select name=\"prod_master_category\">";
				$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar2 = mysql_fetch_array($res2))	{
					$cat = $ar2['category_name'];
					echo "<option name=\"prod_master_category\" value=\"".$ar2['category_id']."\" ";
					if ($ar2['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
					echo ">".$cat."</option>";
					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($arr2 = mysql_fetch_array($ress2)){
						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
						echo "<option name=\"prod_master_category\" value=\"".$arr2['category_id']."\" ";
						if ($arr2['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
						echo ">".$cat2."</option>";
						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while ($arr3 = mysql_fetch_array($ress3)){
							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
							echo "<option name=\"prod_master_category\" value=\"".$arr3['category_id']."\" ";
							if ($arr3['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
							echo ">".$cat3."</option>";
							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while ($arr4 = mysql_fetch_array($ress4)){
								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
								echo "<option name=\"prod_master_category\" value=\"".$arr4['category_id']."\" ";
								if ($arr4['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
								echo ">".$cat4."</option>";
								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								while ($arr5 = mysql_fetch_array($ress5)){
									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
									echo "<option name=\"prod_master_category\" value=\"".$arr5['category_id']."\" ";
									if ($arr5['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
									echo ">".$cat5."</option>";
									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
									while ($arr6 = mysql_fetch_array($ress6)){
										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
										echo "<option name=\"prod_master_category\" value=\"".$arr6['category_id']."\" ";
										if ($arr6['category_id'] == $ar_design['shop_clothes_design_master_category']) { echo "selected=\"selected\"";}
										echo ">".$cat6."</option>";
									}
								}
							}
						}
					}
				}
		echo "	</select></td>";
		echo "</tr>";
		echo "<tr>";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_CL_DESIGN_SUB_CAT."</strong></td>";
		echo "	<td align=\"left\" valign=\"top\">";
		echo "	<select name=\"prod_sub_category1\">";
    			   	$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
                    while ($ar2 = mysql_fetch_array($res2))	{
                    	$cat = $ar2['category_name'];
    					echo "<option name=\"prod_sub_category1\" value=\"".$ar2['category_id']."\" ";
    					if ($ar2['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    					echo ">".$cat."</option>";
    					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    					while ($arr2 = mysql_fetch_array($ress2)){
    						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
    						echo "<option name=\"prod_sub_category1\" value=\"".$arr2['category_id']."\" ";
    						if ($arr2['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    						echo ">".$cat2."</option>";
    						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    						while ($arr3 = mysql_fetch_array($ress3)){
    							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
    							echo "<option name=\"prod_sub_category1\" value=\"".$arr3['category_id']."\" ";
    							if ($arr3['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    							echo ">".$cat3."</option>";
    							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    							while ($arr4 = mysql_fetch_array($ress4)){
    								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
    								echo "<option name=\"prod_sub_category1\" value=\"".$arr4['category_id']."\" ";
    								if ($arr4['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    								echo ">".$cat4."</option>";
    								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    								while ($arr5 = mysql_fetch_array($ress5)){
    									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
    									echo "<option name=\"prod_sub_category1\" value=\"".$arr5['category_id']."\" ";
    									if ($arr5['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    									echo ">".$cat5."</option>";
    									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    									while ($arr6 = mysql_fetch_array($ress6)){
    										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
    										echo "<option name=\"prod_sub_category1\" value=\"".$arr6['category_id']."\" ";
    										if ($arr6['category_id'] == $ar_design['shop_clothes_design_subcategory1']) { echo "selected=\"selected\""; $prod_sub_cat_1_select = 1;}
    										echo ">".$cat6."</option>";
    									}
    								}
    							}
    						}
    					}
    				}
        			echo "<option name=\"prod_sub_category1\" value=\"0\" ";
    				if ($prod_sub_cat_1_select != 1){echo "selected=\"selected\"";}
    				echo ">"._SHOP_PROD_SEL_SUBCAT."</option>";
		echo "	</select>";
		echo "	<select name=\"prod_sub_category2\">";
    				$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    				while ($ar2 = mysql_fetch_array($res2))	{
    					$cat = $ar2['category_name'];
    					echo "<option name=\"prod_sub_category2\" value=\"".$ar2['category_id']."\" ";
    					if ($ar2['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    					echo ">".$cat."</option>";
    					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    					while ($arr2 = mysql_fetch_array($ress2)){
    						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
    						echo "<option name=\"prod_sub_category2\" value=\"".$arr2['category_id']."\" ";
    						if ($arr2['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    						echo ">".$cat2."</option>";
    						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    						while ($arr3 = mysql_fetch_array($ress3)){
    							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
    							echo "<option name=\"prod_sub_category2\" value=\"".$arr3['category_id']."\" ";
    							if ($arr3['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    							echo ">".$cat3."</option>";
    							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    							while ($arr4 = mysql_fetch_array($ress4)){
    								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
    								echo "<option name=\"prod_sub_category2\" value=\"".$arr4['category_id']."\" ";
    								if ($arr4['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    								echo ">".$cat4."</option>";
    								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    								while ($arr5 = mysql_fetch_array($ress5)){
    									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
    									echo "<option name=\"prod_sub_category2\" value=\"".$arr5['category_id']."\" ";
    									if ($arr5['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    									echo ">".$cat5."</option>";
    									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    									while ($arr6 = mysql_fetch_array($ress6)){
    										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
    										echo "<option name=\"prod_sub_category2\" value=\"".$arr6['category_id']."\" ";
    										if ($arr6['category_id'] == $ar_design['shop_clothes_design_subcategory2']) { echo "selected=\"selected\""; $prod_sub_cat_2_select = 1;}
    										echo ">".$cat6."</option>";
    									}
    								}
    							}
    						}
    					}
    				}
                echo "<option name=\"prod_sub_category2\" value=\"0\" ";	if ($prod_sub_cat_2_select != 1){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_SEL_SUBCAT."</option>";
				echo "</select>";
				echo "<select name=\"prod_sub_category3\">";
					$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    				while ($ar2 = mysql_fetch_array($res2))	{
    					$cat = $ar2['category_name'];
    					echo "<option name=\"prod_sub_category3\" value=\"".$ar2['category_id']."\" ";
    					if ($ar2['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    					echo ">".$cat."</option>";
    					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    					while ($arr2 = mysql_fetch_array($ress2)){
    						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
    						echo "<option name=\"prod_sub_category3\" value=\"".$arr2['category_id']."\" ";
    						if ($arr2['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    						echo ">".$cat2."</option>";
    						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    						while ($arr3 = mysql_fetch_array($ress3)){
    							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
    							echo "<option name=\"prod_sub_category3\" value=\"".$arr3['category_id']."\" ";
    							if ($arr3['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    							echo ">".$cat3."</option>";
    							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    							while ($arr4 = mysql_fetch_array($ress4)){
    								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
    								echo "<option name=\"prod_sub_category3\" value=\"".$arr4['category_id']."\" ";
    								if ($arr4['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    								echo ">".$cat4."</option>";
    								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    								while ($arr5 = mysql_fetch_array($ress5)){
    									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
    									echo "<option name=\"prod_sub_category3\" value=\"".$arr5['category_id']."\" ";
    									if ($arr5['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    									echo ">".$cat5."</option>";
    									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    									while ($arr6 = mysql_fetch_array($ress6)){
    										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
    										echo "<option name=\"prod_sub_category3\" value=\"".$arr6['category_id']."\" ";
    										if ($arr6['category_id'] == $ar_design['shop_clothes_design_subcategory3']) { echo "selected=\"selected\""; $prod_sub_cat_3_select = 1;}
    										echo ">".$cat6."</option>";
    									}
    								}
    							}
    						}
    					}
    				}
                echo "<option name=\"prod_sub_category3\" value=\"0\" "; if ($prod_sub_cat_3_select != 1){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_SEL_SUBCAT."</option>";
				echo "</select>";
				echo "<select name=\"prod_sub_category4\">";
					$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    				while ($ar2 = mysql_fetch_array($res2))	{
    					$cat = $ar2['category_name'];
    					echo "<option name=\"prod_sub_category4\" value=\"".$ar2['category_id']."\" ";
    					if ($ar2['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    					echo ">".$cat."</option>";
    					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    					while ($arr2 = mysql_fetch_array($ress2)){
    						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
    						echo "<option name=\"prod_sub_category4\" value=\"".$arr2['category_id']."\" ";
    						if ($arr2['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    						echo ">".$cat2."</option>";
    						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    						while ($arr3 = mysql_fetch_array($ress3)){
    							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
    							echo "<option name=\"prod_sub_category4\" value=\"".$arr3['category_id']."\" ";
    							if ($arr3['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    							echo ">".$cat3."</option>";
    							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    							while ($arr4 = mysql_fetch_array($ress4)){
    								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
    								echo "<option name=\"prod_sub_category4\" value=\"".$arr4['category_id']."\" ";
    								if ($arr4['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    								echo ">".$cat4."</option>";
    								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    								while ($arr5 = mysql_fetch_array($ress5)){
    									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
    									echo "<option name=\"prod_sub_category4\" value=\"".$arr5['category_id']."\" ";
    									if ($arr5['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    									echo ">".$cat5."</option>";
    									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    									while ($arr6 = mysql_fetch_array($ress6)){
    										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
    										echo "<option name=\"prod_sub_category4\" value=\"".$arr6['category_id']."\" ";
    										if ($arr6['category_id'] == $ar_design['shop_clothes_design_subcategory4']) { echo "selected=\"selected\""; $prod_sub_cat_4_select = 1;}
    										echo ">".$cat6."</option>";
    									}
    								}
    							}
    						}
    					}
    				}
                echo "<option name=\"prod_sub_category4\" value=\"0\" "; if ($prod_sub_cat_4_select != 1){echo "selected=\"selected\"";} echo ">"._SHOP_PROD_SEL_SUBCAT."</option>";
				echo "</select>";
				echo "<select name=\"prod_sub_category5\">";
					$res2 = mysql_query("SELECT category_id, category_name FROM $db_category WHERE category_shop=1 AND category_parent=0 ORDER BY category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    				while ($ar2 = mysql_fetch_array($res2))	{
    					$cat = $ar2['category_name'];
    					echo "<option name=\"prod_sub_category5\" value=\"".$ar2['category_id']."\" ";
    					if ($ar2['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    					echo ">".$cat."</option>";
    					$ress2 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$ar2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    					while ($arr2 = mysql_fetch_array($ress2)){
    						if ($arr2['category_parent'] == $ar2['category_id']){$cat2 = "&nbsp;&nbsp;&nbsp;".$arr2['category_name']; }
    						echo "<option name=\"prod_sub_category5\" value=\"".$arr2['category_id']."\" ";
    						if ($arr2['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    						echo ">".$cat2."</option>";
    						$ress3 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr2['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    						while ($arr3 = mysql_fetch_array($ress3)){
    							if ($arr3['category_parent'] == $arr2['category_id']){$cat3 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr3['category_name']; }
    							echo "<option name=\"prod_sub_category5\" value=\"".$arr3['category_id']."\" ";
    							if ($arr3['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    							echo ">".$cat3."</option>";
    							$ress4 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr3['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    							while ($arr4 = mysql_fetch_array($ress4)){
    								if ($arr4['category_parent'] == $arr3['category_id']){$cat4 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr4['category_name']; }
    								echo "<option name=\"prod_sub_category5\" value=\"".$arr4['category_id']."\" ";
    								if ($arr4['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    								echo ">".$cat4."</option>";
    								$ress5 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr4['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    								while ($arr5 = mysql_fetch_array($ress5)){
    									if ($arr5['category_parent'] == $arr4['category_id']){$cat5 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr5['category_name']; }
    									echo "<option name=\"prod_sub_category5\" value=\"".$arr5['category_id']."\" ";
    									if ($arr5['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    									echo ">".$cat5."</option>";
    									$ress6 = mysql_query("SELECT category_id, category_name, category_parent FROM $db_category WHERE category_parent=".$arr5['category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
    									while ($arr6 = mysql_fetch_array($ress6)){
    										if ($arr6['category_parent'] == $arr5['category_id']){$cat6 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr6['category_name']; }
    										echo "<option name=\"prod_sub_category5\" value=\"".$arr6['category_id']."\" ";
    										if ($arr6['category_id'] == $ar_design['shop_clothes_design_subcategory5']) { echo "selected=\"selected\""; $prod_sub_cat_5_select = 1;}
    										echo ">".$cat6."</option>";
    									}
    								}
    							}
    						}
    					}
                    }
                    echo "<option name=\"prod_sub_category5\" value=\"0\" ";
    				if ($prod_sub_cat_5_select != 1){echo "selected=\"selected\"";}
    				echo ">"._SHOP_PROD_SEL_SUBCAT."</option>";
		echo "		</select>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\">\n";
		echo "	<strong>"._SHOP_CL_DESIGN_GENDER."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_gender_men\" value=\"1\" "; if ($ar_design['shop_clothes_design_gender_men'] == 1 || $_GET['action'] == "clothes_add_design") {echo "checked";} echo ">"._SHOP_CL_DESIGN_GENDER_MEN."&nbsp;&nbsp;\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_gender_women\" value=\"1\" "; if ($ar_design['shop_clothes_design_gender_women'] == 1) {echo "checked";} echo ">"._SHOP_CL_DESIGN_GENDER_WOMEN."&nbsp;&nbsp;\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_gender_kids\" value=\"1\" "; if ($ar_design['shop_clothes_design_gender_kids'] == 1) {echo "checked";} echo ">"._SHOP_CL_DESIGN_GENDER_KIDS."&nbsp;&nbsp;\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\">\n";
		echo "	<strong>"._SHOP_CL_DESIGN_FOR."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_for_tshirts\" value=\"1\" "; if ($ar_design['shop_clothes_design_for_tshirts'] == 1 || $_GET['action'] == "clothes_add_design") {echo "checked";} echo ">"._SHOP_CL_DESIGN_FOR_TSHIRTS."&nbsp;&nbsp;\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_for_bags\" value=\"1\" "; if ($ar_design['shop_clothes_design_for_bags'] == 1) {echo "checked";} echo ">"._SHOP_CL_DESIGN_FOR_BAGS."&nbsp;&nbsp;\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\">\n";
		echo "	<strong>"._SHOP_CL_DESIGN_PROD_SELL_PRICE."</strong></td>";
				if ($_GET['action'] == "clothes_edit_design"){
					$res_tax_rates = mysql_query("SELECT shop_tax_rates_rate FROM $db_shop_tax_rates WHERE shop_tax_rates_class_id=".$ar_design['shop_clothes_design_vat_class_id']." ORDER BY shop_tax_rates_priority DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_tax_rates = mysql_fetch_array($res_tax_rates);
				}
		echo "<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_selling_price\" maxlength=\"20\" value=\"".$ar_design['shop_clothes_design_selling_price']."\" size=\"20\"> "._SHOP_INC_VAT."";
			$prod_ex_vat = $ar_design['shop_clothes_design_selling_price'] - (($ar_design['shop_clothes_design_selling_price'] / 100) * $ar_tax_rates['shop_tax_rates_rate']); 
			$prod_vat = ($ar_design['shop_clothes_design_selling_price'] - $prod_ex_vat);
			
		echo "<span class=\"red\">(".PriceFormat($prod_ex_vat)." "._SHOP_EX_VAT.") "._SHOP_VAT." = ".PriceFormat($prod_vat)."</span></td>";
		echo "</tr>";
		echo "<tr>";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\">";
		echo "	<strong>"._SHOP_PROD_TAX_CLASS."</strong></td>";
		echo "	<td align=\"left\" valign=\"top\"><select name=\"prod_vat_class_id\" style=\"width: 150px;\">";
			$res_tax_class = mysql_query("SELECT shop_tax_class_id, shop_tax_class_title FROM $db_shop_tax_class ORDER BY shop_tax_class_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_tax_class = mysql_fetch_array($res_tax_class))	{
					echo "<option name=\"prod_vat_class_id\" value=\"".$ar_tax_class['shop_tax_class_id']."\" ";
					if ($ar_tax_class['shop_tax_class_id'] == $ar_design['shop_clothes_design_vat_class_id']) { echo "selected=\"selected\"";}
					echo ">".PrepareFromDB($ar_tax_class['shop_tax_class_title'])."</option>";
				}
		echo "	</select> ".TepRound($ar_tax_rates['shop_tax_rates_rate'], 2)."%</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\">\n";
		echo "	<strong>"._SHOP_CL_DESIGN_QUANTITY."</strong></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"prod_quantity\" maxlength=\"11\" value=\"".$ar_design['shop_clothes_design_quantity']."\" size=\"10\">\n";
		echo "		<input type=\"checkbox\" name=\"clothes_design_quantity_limited\" value=\"1\" "; if ($ar_design['shop_clothes_design_quantity_limited'] == 1) {echo "checked";} echo "> "._SHOP_CL_DESIGN_QUANTITY_LIMITED."\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_SHORT_DESC."</strong></td>\n";
		echo "	<td><textarea cols=\"80\" rows=\"6\" name=\"clothes_design_desc_short\">".$ar_design['shop_clothes_design_description_short']."</textarea></td>\n";
		echo "</tr>\n";
		echo "	<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_LONG_DESC."</strong></td>\n";
		echo "	<td><textarea cols=\"80\" rows=\"6\" name=\"clothes_design_desc_long\">".$ar_design['shop_clothes_design_description']."</textarea>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_1.$ar_setup['shop_setup_clothes_img_1_width']."x".$ar_setup['shop_setup_clothes_img_1_height']."</td>\n";
		echo "	<td align=\"left\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_1\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_1'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_1']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_1_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL;}
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_2.$ar_setup['shop_setup_clothes_img_1_width']."x".$ar_setup['shop_setup_clothes_img_1_height']."</td>\n";
		echo "	<td align=\"left\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_2\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_2'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_2']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_2_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_3.$ar_setup['shop_setup_clothes_img_3_width']."x".$ar_setup['shop_setup_clothes_img_3_height']."</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_3\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_3'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_3']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_3_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_4.$ar_setup['shop_setup_clothes_img_3_width']."x".$ar_setup['shop_setup_clothes_img_3_height']."\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_4\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_4'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_4']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_4_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>	\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_5.$ar_setup['shop_setup_clothes_img_5_width']."x".$ar_setup['shop_setup_clothes_img_5_height']."\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_5\" size=\"30\"><br>\n";
	   				if ($ar_design['shop_clothes_design_img_5'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_5']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_5_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_6.$ar_setup['shop_setup_clothes_img_5_width']."x".$ar_setup['shop_setup_clothes_img_5_height']."\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_6\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_6'] != ""){ echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_6']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_6_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_7.$ar_setup['shop_setup_clothes_img_5_width']."x".$ar_setup['shop_setup_clothes_img_5_height']."\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_7\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_7'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_7']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_7_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\">"._SHOP_CL_DESIGN_IMG_8.$ar_setup['shop_setup_clothes_img_5_width']."x".$ar_setup['shop_setup_clothes_img_5_height']."\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"file\" name=\"clothes_design_img_8\" size=\"30\"><br>\n";
					if ($ar_design['shop_clothes_design_img_8'] != ""){echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_8']."\" width=\"150px\" alt=\"\" border=\"1\"><input name=\"clothes_design_img_8_del\" type=\"checkbox\" value=\"1\"> "._SHOP_CL_DESIGN_IMG_DEL; }
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"right\" valign=\"top\" valign=\"top\">\n";
		echo "		<strong>"._SHOP_CL_DESIGN_STYLES."</strong><br><br>\n";
		echo "		<div style=\"width:100px; height:50px; border: 1px solid #000000; background-color:#FFDEDF\" align=\"center\"><div style=\"margin-top:15px;\">Done</div></div>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">";
				$res_style_parent = mysql_query("SELECT shop_clothes_style_parents_id, shop_clothes_style_parents_title, shop_clothes_style_parents_colors FROM $db_shop_clothes_style_parents") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$color_number = 1;
				while ($ar_style_parent = mysql_fetch_array($res_style_parent)){
					echo "<h3>".$ar_style_parent['shop_clothes_style_parents_title']."</h3>";
					echo "<table>";
					//$res_style = mysql_query("SELECT * FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					//$ar_style = mysql_fetch_array($res_style);
					
					$res_style_color = mysql_query("SELECT shop_clothes_style_color_id FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i=0;
					while ($ar_style_color = mysql_fetch_array($res_style_color)){
						$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
						$i++;
					}
					if (count($style_color_list) == 0){$style_color_list = array();}
					$color_list = explode("#", $ar_style_parent['shop_clothes_style_parents_colors']);
					if (count($color_list) == 0){$color_list = array();}
					$res_color = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_title, shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3 
					FROM $db_shop_clothes_colors ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title  ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_color = mysql_fetch_array($res_color)){
						$parent_color = $ar_style_parent['shop_clothes_style_parents_id']."-".$ar_color['shop_clothes_colors_id'];
						if (in_array($ar_color['shop_clothes_colors_id'], $color_list)){
							echo "<tr "; if (in_array($parent_color, $design_list)){echo "bgcolor=\"#FFDEDF\"";} echo ">";
							echo "	<td width=\"30px\" valign=\"top\">";
									if ($ar_color['shop_clothes_colors_prefix'] == 1){
										echo "<div style=\"width:30px; height:30px; float: left; border: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div>";
									}elseif ($ar_color['shop_clothes_colors_prefix'] == 2){
										echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
									}elseif($ar_color['shop_clothes_colors_prefix'] == 3){
										echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
									}
							echo "	</td>\n";
							echo "	<td width=\"20px\" valign=\"top\">\n";
							echo "		<input type=\"hidden\" name=\"clothes_design_color_num\" value=\"".$color_number."\">\n";
							echo "		<input type=\"hidden\" name=\"clothes_design_color_data[".$color_number."_style_id]\" value=\"".$ar_style_parent['shop_clothes_style_parents_id']."\">\n";
							echo "		<input type=\"hidden\" name=\"clothes_design_color_data[".$color_number."_color_id]\" value=\"".$ar_color['shop_clothes_colors_id']."\">\n";
							echo "		<input type=\"checkbox\" name=\"clothes_design_color_data[".$color_number."_color]\" "; if (in_array($parent_color, $design_list)){echo "checked";} echo " value=\"1\"></td>\n";
							echo "	<td width=\"80px\" align=\"center\" valign=\"top\"\">".Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".Zerofill($ar_color['shop_clothes_colors_id'],1000)."</td>\n";
							echo "	<td width=\"310px\" valign=\"top\">".$ar_color['shop_clothes_colors_title']."</td>\n";
							echo "</tr>";
							$color_number++;
						}
					}
					echo "</table>";
				}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
		echo "		<td align=\"left\"><br><br><br>\n";
		echo "			<input type=\"radio\" name=\"save\" value=\"1\"><span>"._ARTICLES_FUNC_SAVE."</span>&nbsp;&nbsp;&nbsp;\n";
		echo "			<input type=\"radio\" name=\"save\" value=\"2\" checked><span>"._ARTICLES_FUNC_SAVE_SEND."</span>&nbsp;&nbsp;&nbsp;<br /> <br />\n";
		echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		CLOTHES	- GENERATE PRODUCTS																			
*																											
***********************************************************************************************************/
function ClothesGenerateProducts(){
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_shop_setup_edit") <> 1) {echo _NOTENOUGHPRIV;	exit;}
	
	global $db_shop_clothes_style,$db_shop_clothes_style_parents,$db_shop_clothes_colors,$db_shop_clothes_size,$db_shop_gender;
	global $db_shop_clothes_design;
	global $eden_cfg;
	global $ftp_path_shop_clothes_style;
	global $url_shop_clothes_design;
		
	global $db_shop_product,$db_category,$db_shop_man,$db_shop_tax_class,$db_shop_tax_rates;
	global $ftp_path_shop_prod,$url_shop_prod;
	global $preview;
	
	$res_style_color = mysql_query("SELECT shop_clothes_style_color_id FROM $db_shop_clothes_style") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=0;
	while ($ar_style_color = mysql_fetch_array($res_style_color)){
		$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
		$i++;
	}
	
	if ($_POST['confirm'] != "true"){
		$res_design = mysql_query("SELECT * FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_design = mysql_fetch_array($res_design);
		
		$design_list = explode("#", $ar_design['shop_clothes_design_styles_and_colors']);
		if (count($design_list) == 0){$design_list = array();}
		
		$clothes_design_title = PrepareFromDB($ar_design['shop_clothes_design_title']);
		/*
		*	Nastaveni Datumu
		*/
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_day = FormatDatetime($ar_design['shop_clothes_design_date_available'],"d");} else {$prod_date_day = date("d");}
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_month = FormatDatetime($ar_design['shop_clothes_design_date_available'],"m");} else {$prod_date_month = date("m");}
		if ($ar_design['shop_clothes_design_date_available'] != ""){$prod_date_year = FormatDatetime($ar_design['shop_clothes_design_date_available'],"Y");} else {$prod_date_year = date("Y");}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>";
	echo "		<td align=\"left\" class=\"nadpis\">"._SHOP." - "; if ($_GET['action'] == "clothes_add_design"){ echo _SHOP_CL_DESIGN_ADD;} else {echo _SHOP_CL_DESIGN_EDIT." - ID #".$_GET['id'];} echo "</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>".ShopMenu()."</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo " <tr>";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">";
	echo "		<strong>"._SHOP_CL_DESIGN_TITLE."</strong></td>";
	echo "		<td align=\"left\" valign=\"top\">".$clothes_design_title."</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\">";
	echo " 		<strong>"._SHOP_CL_DESIGN_CODE."</strong>";
	echo "		</td>";
	echo "		<td align=\"left\">".Zerofill($_GET['id'],10000)."</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td width=\"250\" align=\"right\">";
	echo "			<strong>"._SHOP_CL_DESIGN_SHOW."</strong>";
	echo "		</td>";
	echo "		<td align=\"left\" valign=\"top\">"; if ($ar_design['shop_clothes_design_show'] == 1) {echo _CMN_YES;}
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">";
	echo "		<strong>"._SHOP_CL_DESIGN_STATUS."</strong></td>";
	echo "		<td align=\"left\" valign=\"top\">";
					if ($ar_design['shop_clothes_design_status'] == 0){echo _SHOP_PROD_STATUS_0;}
					if ($ar_design['shop_clothes_design_status'] == 1){echo _SHOP_PROD_STATUS_1;}
					if ($ar_design['shop_clothes_design_status'] == 2){echo _SHOP_PROD_STATUS_2;}
					if ($ar_design['shop_clothes_design_status'] == 3){echo _SHOP_PROD_STATUS_3;}
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">";
	echo "		<strong>"._SHOP_CL_DESIGN_AVAILEBLE_DATE."</strong></td>";
	echo "			<td align=\"left\" valign=\"top\">";
	echo 				$prod_date_day.".".$prod_date_month.".".$prod_date_year;
	echo "			</td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "			<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_CL_DESIGN_PROD_SELL_PRICE."</strong></td>";
					$res_tax_rates = mysql_query("SELECT shop_tax_rates_rate 
					FROM $db_shop_tax_rates 
					WHERE shop_tax_rates_class_id=".$ar_design['shop_clothes_design_vat_class_id']." 
					ORDER BY shop_tax_rates_priority DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_tax_rates = mysql_fetch_array($res_tax_rates);
	echo "			<td align=\"left\" valign=\"top\">".$ar_design['shop_clothes_design_selling_price']." "._SHOP_INC_VAT;
					$prod_ex_vat = $ar_design['shop_clothes_design_selling_price'] - (($ar_design['shop_clothes_design_selling_price'] / 100) * $ar_tax_rates['shop_tax_rates_rate']); 
					$prod_vat = ($ar_design['shop_clothes_design_selling_price'] - $prod_ex_vat);
	echo " 			<span class=\"red\">(".PriceFormat($prod_ex_vat)." "._SHOP_EX_VAT.") "._SHOP_VAT." = ".PriceFormat($prod_vat)."</span></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">";
	echo "		<strong>"._SHOP_PROD_TAX_CLASS."</strong></td>";
	echo "		<td align=\"left\" valign=\"top\">";
			$res_tax_class = mysql_query("SELECT shop_tax_class_title FROM $db_shop_tax_class WHERE shop_tax_class_id=".$ar_design['shop_clothes_design_vat_class_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_tax_class = mysql_fetch_array($res_tax_class);
			echo PrepareFromDB($ar_tax_class['shop_tax_class_title']); echo " ".TepRound($ar_tax_rates['shop_tax_rates_rate'], 2)."%";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_CL_DESIGN_QUANTITY."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\">".$ar_design['shop_clothes_design_quantity']."\n";
	echo "			["._SHOP_CL_DESIGN_QUANTITY_LIMITED; if ($ar_design['shop_clothes_design_quantity_limited'] == 1) {echo " - "._CMN_YES;} echo "]";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_SHORT_DESC."</strong></td>\n";
	echo "		<td>".$ar_design['shop_clothes_design_description_short']."</td>\n";
	echo "	</tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\"><strong>"._SHOP_PROD_LONG_DESC."</strong></td>\n";
	echo "		<td>".$ar_design['shop_clothes_design_description']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"250\">\n";
	echo "		<strong>"._SHOP_PROD_WEIGHT."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\">".$ar_design['shop_clothes_design_weight']." "._SHOP_PROD_WEIGHT_HELP."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_IMG_1."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
   					if ($ar_design['shop_clothes_design_img_1'] != ""){ echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_1']."\" width=\"150px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_IMG_2."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
					if ($ar_design['shop_clothes_design_img_3'] != ""){ echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_3']."\" width=\"150px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_IMG_3."</strong><br><br><br>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
					if ($ar_design['shop_clothes_design_img_3'] != ""){ echo "<img src=\"".$url_shop_clothes_design.$ar_design['shop_clothes_design_img_4']."\" width=\"150px\" alt=\"\" border=\"1\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"250\" align=\"right\" valign=\"top\" valign=\"top\">\n";
	echo "			<strong>"._SHOP_CL_DESIGN_STYLES."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">";
				$res_style_parent = mysql_query("SELECT shop_clothes_style_parents_id, shop_clothes_style_parents_title, shop_clothes_style_parents_colors FROM $db_shop_clothes_style_parents") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$color_number = 1;
				while ($ar_style_parent = mysql_fetch_array($res_style_parent)){
					echo "<h3>".$ar_style_parent['shop_clothes_style_parents_title']."</h3>";
					echo "<table>";
					//$res_style = mysql_query("SELECT * FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					//$ar_style = mysql_fetch_array($res_style);
					
					$res_style_color = mysql_query("SELECT shop_clothes_style_color_id FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i=0;
					while ($ar_style_color = mysql_fetch_array($res_style_color)){
						$style_color_list[$i] = $ar_style_color['shop_clothes_style_color_id'];
						$i++;
					}
					if (count($style_color_list) == 0){$style_color_list = array();}
					$color_list = explode("#", $ar_style_parent['shop_clothes_style_parents_colors']);
					if (count($color_list) == 0){$color_list = array();}
					$res_color = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_prefix, shop_clothes_colors_hex_1, shop_clothes_colors_hex_2, shop_clothes_colors_hex_3, shop_clothes_colors_title 
					FROM $db_shop_clothes_colors 
					ORDER BY shop_clothes_colors_prefix, shop_clothes_colors_title  ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_color = mysql_fetch_array($res_color)){
						$parent_color = $ar_style_parent['shop_clothes_style_parents_id']."-".$ar_color['shop_clothes_colors_id'];
						if (in_array($parent_color, $design_list)){
							echo "<tr>";
							echo "	<td width=\"30px\" valign=\"top\">";
									if ($ar_color['shop_clothes_colors_prefix'] == 1){
										echo "<div style=\"width:30px; height:30px; border: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div>"; 
									}elseif ($ar_color['shop_clothes_colors_prefix'] == 2){
										echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
									}elseif($ar_color['shop_clothes_colors_prefix'] == 3){
										echo "<div style=\"width:23px; height:30px; float: left; border-left: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_1']."\"></div><div style=\"width:7px; height:30px; float: left; border-right: 1px solid #000000; border-top: 1px solid #000000; border-bottom: 1px solid #000000; background-color:#".$ar_color['shop_clothes_colors_hex_2']."\"></div>";
									}
							echo "	</td>";
							echo "	<td width=\"80px\" align=\"center\" valign=\"top\">".Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar_color['shop_clothes_colors_id'],100)."</td>";
							echo "	<td width=\"310px\" valign=\"top\">".$ar_color['shop_clothes_colors_title']."</td>";
							echo "</tr>";
							$color_number++;
						}
					}
					echo "</table>";
				}
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td width=\"250\" align=\"left\" valign=\"top\">&nbsp;</td>\n";
		echo "	<td align=\"left\"><br><br><br>\n";
		echo "		<form action=\"sys_save.php?action=generate_products&amp;id=".$_GET['id']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\">\n";
		echo "		<input type=\"submit\" value=\""._SHOP_CL_DESIGN_BUTTON_GENERATE."\" class=\"eden_button\">\n";
		echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		</form>\n";
		echo "	</td>\n";
		echo "</tr>";
	}
}
$tinymce_init_mode = "shop_product"; // pouzije se v inc.header.php pro inicializaci TinyMCE
include "inc.header.php";
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "stat") { ShowMain(); }
	if ($_GET['action'] == "prod") { Products(); }
	if ($_GET['action'] == "changes") { Products(); }
	if ($_GET['action'] == "orders") { Orders(); }
	if ($_GET['action'] == "o_det") { Orders(); }
	if ($_GET['action'] == "o_test") { Orders(); }
	if ($_GET['action'] == "add_man") {Manufacturers();}
	if ($_GET['action'] == "edit_man") {Manufacturers();}
	if ($_GET['action'] == "del_man") {Manufacturers();}
	if ($_GET['action'] == "add_prod") {AddProd();}
	if ($_GET['action'] == "edit_prod") {AddProd();}
	if ($_GET['action'] == "del_prod") {DelProd();}
	if ($_GET['action'] == "clothes_add_color") { ClothesColors(); }
	if ($_GET['action'] == "clothes_edit_color") { ClothesColors(); }
	if ($_GET['action'] == "clothes_del_color") { ClothesColors(); }
	if ($_GET['action'] == "clothes_add_size") { ClothesSize(); }
	if ($_GET['action'] == "clothes_edit_size") { ClothesSize(); }
	if ($_GET['action'] == "clothes_del_size") { ClothesSize(); }
	if ($_GET['action'] == "clothes_add_style") { ClothesStyle(); }
	if ($_GET['action'] == "clothes_edit_style") { ClothesStyle(); }
	if ($_GET['action'] == "clothes_del_style") { ClothesStyle(); }
	if ($_GET['action'] == "clothes_add_style_parents") { ClothesStyleParents(); }
	if ($_GET['action'] == "clothes_edit_style_parents") { ClothesStyleParents(); }
	if ($_GET['action'] == "clothes_del_style_parents") { ClothesStyleParents(); }
	if ($_GET['action'] == "clothes_add_design") { ClothesAddDesign(); }
	if ($_GET['action'] == "clothes_edit_design") { ClothesAddDesign(); }
	if ($_GET['action'] == "clothes_del_design") { ClothesAddDesign(); }
	if ($_GET['action'] == "clothes_show_designs") { ClothesShowDesigns(); }
	if ($_GET['action'] == "clothes_generate_products") { ClothesGenerateProducts(); }
include "inc.footer.php";