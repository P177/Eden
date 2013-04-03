<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU FILTRU																			
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_filters;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "filter_add";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "filter_edit"){
		if (CheckPriv("groups_filter_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=filter_add&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "filter_del"){
		if (CheckPriv("groups_filter_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_filter.php?action=filter_add&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_filter_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	if ($_GET['action'] != "filter_add"){
		$res = mysql_query("SELECT * FROM $db_filters WHERE filter_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._FILTER."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\"> <a href=\"modul_filter.php?action=filter_add&amp;project=".$_SESSION['project']."\">"._FILTER_ADD."</a></td>\n";
	echo "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "clan_awards_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "awards_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	 if ($_GET['action'] == "filter_del"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "filter_add"){echo "filter_add";} elseif ($_GET['action'] == "filter_edit"){echo "filter_edit";} else {echo "filter_del";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._GAMESRV_DELCHECK."</span></strong>\n";
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
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "filter_add"){echo "filter_add";} else {echo "filter_edit";} echo "\" method=\"post\">\n";
	echo "			<strong>"._FILTER_SHORTNAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"filter_shortname\" maxlength=\"20\" size=\"30\" "; if ($_GET['action'] == "filter_edit" || $_GET['action'] == "filter_del"){echo "value=\"".$ar['filter_shortname']."\"";} echo ">\n";
	echo "			"._FILTER_ACTIVE."&nbsp;<input type=\"checkbox\" name=\"filter_active\" value=\"1\" "; if ($_GET['action'] == "" || $_GET['action'] == "filter_add" || $ar['filter_active'] == 1){echo "checked=\"checked\"";} echo ">";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._FILTER_NAME."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"filter_name\" maxlength=\"80\" size=\"60\" "; if ($_GET['action'] == "filter_edit" || $_GET['action'] == "filter_del"){echo "value=\"".$ar['filter_name']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._FILTER_DESC."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"38\" rows=\"4\" name=\"filter_desc\">"; if ($_GET['action'] == "filter_edit" || $_GET['action'] == "filter_del"){echo $ar['filter_description'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "filter_add"){echo _FILTER_ADD;} else {echo _FILTER_EDIT;} echo "\">\n";
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
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._FILTER_SHORTNAME."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._FILTER_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._FILTER_DESC."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._FILTER_ACTIVE."</span></td>\n";
	echo "	</tr>";
 		$res = mysql_query("SELECT * FROM $db_filters ORDER BY filter_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=1;
		while ($ar = mysql_fetch_array($res)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_filter_edit") == 1){ echo "<a href=\"modul_filter.php?action=filter_edit&amp;id=".$ar['filter_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";  }
					if (CheckPriv("groups_filter_del") == 1){ echo " <a href=\"modul_filter.php?action=filter_del&amp;id=".$ar['filter_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td> \n";
			echo "	<td width=\"45\" align=\"left\" valign=\"top\">".$ar['filter_id']."</td>\n";
			echo "	<td width=\"100\" align=\"left\" valign=\"top\">".$ar['filter_shortname']."</td>\n";
			echo "	<td width=\"200\" align=\"left\" valign=\"top\">".$ar['filter_name']."</td>\n";
			echo "	<td align=\"left\" valign=\"top\">".$ar['filter_description']."</td>\n";
			echo "	<td width=\"50\" align=\"left\"><img src=\"images/sys_"; if ($ar['filter_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">"; echo "</td>\n";
			echo "</tr>";
			$i++;
 		}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "filter_add") {ShowMain();}
	if ($_GET['action'] == "filter_edit") {ShowMain();}
	if ($_GET['action'] == "filter_del") {ShowMain();}
	if ($_GET['action'] == "") {$_GET['action'] = "filter_add"; ShowMain(); }
include ("inc.footer.php");