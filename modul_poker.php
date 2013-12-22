<?php
/***********************************************************************************************************
*																											
*		MENU																								
*																											
***********************************************************************************************************/
function PokerMenu(){
	
	global $eden_cfg;
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>";
	echo "		<td align=\"left\" class=\"nadpis\">"._POKER."</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">";
	echo "			<a href=\"modul_poker.php?action=showmain&amp;project=".$_SESSION['project']."\">"._POKER_MAIN."</a>";
	echo "			&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_poker.php?action=add_cardroom&amp;project=".$_SESSION['project']."\">"._POKER_CARDROOM_ADD."</a>";
	echo "			&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_poker.php?action=add_variant&amp;project=".$_SESSION['project']."\">"._POKER_VARIANT_ADD."</a>";
	echo "		</td>";
	echo "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){		
		echo "<tr><td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU NASTAVENI PRO POKER																
*																											
***********************************************************************************************************/
function ShowMain(){
	global $db_poker_cardrooms;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	// Menu
	PokerMenu();
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU NASTAVENI PRO POKER																
*																											
***********************************************************************************************************/
function PokerCardroom(){
	
	global $db_poker_cardrooms;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "site_add";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "site_edit"){
		if (CheckPriv("groups_poker_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "del_cardroom"){
		if (CheckPriv("groups_poker_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_poker_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	if ($_GET['action'] != "add_cardroom"){
		$res = mysql_query("SELECT * FROM $db_poker_cardrooms WHERE poker_cardroom_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	// Menu
	PokerMenu();
	
 	if ($_GET['action'] == "del_cardroom"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_cardroom"){echo "add_cardroom";} elseif ($_GET['action'] == "edit_cardroom"){echo "edit_cardroom";} else {echo "del_cardroom";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._POKER_CARDROOM_DEL_CHECK."</span></strong>\n";
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
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_cardroom"){echo "add_cardroom";} else {echo "edit_cardroom";} echo "\" method=\"post\">\n";
	echo "			<strong>"._POKER_CARDROOM_NAME."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"poker_cardroom_name\" maxlength=\"20\" size=\"30\" "; if ($_GET['action'] == "edit_cardroom" || $_GET['action'] == "del_cardroom"){echo "value=\"".$ar['poker_cardroom_name']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._POKER_CARDROOM_URL."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"poker_cardroom_url\" maxlength=\"80\" size=\"60\" "; if ($_GET['action'] == "edit_cardroom" || $_GET['action'] == "del_cardroom"){echo "value=\"".$ar['poker_cardroom_url']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_cardroom"){echo _POKER_CARDROOM_ADD;} else {echo _POKER_CARDROOM_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._POKER_CARDROOM_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._POKER_CARDROOM_URL."</span></td>\n";
	echo "	</tr>";
		$res = mysql_query("SELECT * FROM $db_poker_cardrooms ORDER BY poker_cardroom_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_poker_edit") == 1){ echo "<a href=\"modul_poker.php?action=edit_cardroom&amp;id=".$ar['poker_cardroom_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
		if (CheckPriv("groups_poker_del") == 1){ echo " <a href=\"modul_poker.php?action=del_cardroom&amp;id=".$ar['poker_cardroom_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td> \n";
		echo "	<td width=\"45\" align=\"left\" valign=\"top\">".$ar['poker_cardroom_id']."</td>\n";
		echo "	<td width=\"200\" align=\"left\" valign=\"top\">".$ar['poker_cardroom_name']."</td>\n";
		echo "	<td align=\"left\" valign=\"top\"><a href=\"".$ar['poker_cardroom_url']."\" target=\"_blank\">".$ar['poker_cardroom_url']."</a></td>\n";
		echo "</tr>";
		$i++;
		}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU NASTAVENI PRO POKER																
*																											
***********************************************************************************************************/
function PokerVariant(){
	
	global $db_poker_variants;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "site_add";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "site_edit"){
		if (CheckPriv("groups_poker_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "del_variant"){
		if (CheckPriv("groups_poker_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_poker.php?action=add_variant&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_poker_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	if ($_GET['action'] != "add_variant"){
		$res = mysql_query("SELECT * FROM $db_poker_variants WHERE poker_variant_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	
	// Menu
	PokerMenu();
	
	 if ($_GET['action'] == "del_variant"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_variant"){echo "add_variant";} elseif ($_GET['action'] == "edit_variant"){echo "edit_variant";} else {echo "del_variant";}  echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._POKER_VARIANT_DEL_CHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_variant"){echo "add_variant";} else {echo "edit_variant";} echo "\" method=\"post\">\n";
	echo "		<strong>"._POKER_VARIANT_NAME."</strong>\n";
	echo "	</td>\n";
	echo "	<td align=\"left\">\n";
	echo "		<input type=\"text\" name=\"poker_variant_name\" maxlength=\"20\" size=\"30\" "; if ($_GET['action'] == "edit_variant" || $_GET['action'] == "del_variant"){echo "value=\"".$ar['poker_variant_name']."\"";} echo ">\n";
	echo "	</td>	\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._POKER_VARIANT_DESC."</strong></td>\n";
	echo "	<td align=\"left\">\n";
	echo "		<textarea name=\"poker_variant_description\" rows=\"6\" cols=\"40\">"; if ($_GET['action'] == "edit_variant" || $_GET['action'] == "del_variant"){echo $ar['poker_variant_description'];} echo "</textarea>\n";
	echo "	</td>	\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">\n";
	echo "		<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_variant"){echo _POKER_VARIANT_ADD;} else {echo _POKER_VARIANT_EDIT;} echo "\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr class=\"popisky\">\n";
	echo "	<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "	<td width=\"45\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "	<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._POKER_VARIANT_NAME."</span></td>\n";
	echo "	<td align=\"left\"><span class=\"nadpis-boxy\">"._POKER_VARIANT_DESC."</span></td>\n";
	echo "</tr>";
	$i=1;
	$res = mysql_query("SELECT * FROM $db_poker_variants ORDER BY poker_variant_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_poker_edit") == 1){ echo "<a href=\"modul_poker.php?action=edit_variant&amp;id=".$ar['poker_variant_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
		if (CheckPriv("groups_poker_del") == 1){ echo " <a href=\"modul_poker.php?action=del_variant&amp;id=".$ar['poker_variant_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td>\n";
		echo "	<td width=\"45\" align=\"left\" valign=\"top\">".$ar['poker_variant_id']."</td>\n";
		echo "	<td width=\"200\" align=\"left\" valign=\"top\">".stripslashes($ar['poker_variant_name'])."</td>\n";
		echo "	<td align=\"left\" valign=\"top\">".stripslashes($ar['poker_variant_description'])."</td>\n";
		echo "</tr>";
		$i++;
	 }
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "add_cardroom") {PokerCardroom();}
	if ($_GET['action'] == "edit_cardroom") {PokerCardroom();}
	if ($_GET['action'] == "del_cardroom") {PokerCardroom();}
	if ($_GET['action'] == "add_variant") {PokerVariant();}
	if ($_GET['action'] == "edit_variant") {PokerVariant();}
	if ($_GET['action'] == "del_variant") {PokerVariant();}
	if ($_GET['action'] == "") {ShowMain(); }
include ("inc.footer.php");