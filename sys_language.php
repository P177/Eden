<?php
//********************************************************************************************************
//
//             JAZYKY
//
//********************************************************************************************************
function ShowMain(){
	
	global $db_language,$db_setup_lang,$db_shop_setup,$url_flags,$db_country;
	
	if ($_GET['action'] != "lang_add"){
		$res = mysql_query("SELECT * FROM $db_language WHERE language_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._LANGS." - "; if ($_GET['action'] == "lang_del"){echo _LANG_DEL;} elseif ($_GET['action'] == "lang_edit") {echo _LANG_EDIT;} else {echo _LANG_ADD;}  echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._LANG_ADD."\">&nbsp;<a href=\"sys_language.php?action=lang_add&amp;project=".$_SESSION['project']."\">"._LANG_ADD."</a></td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "lang_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "lang_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "lang_del"){echo "lang_del";} elseif ($_GET['action'] == "lang_edit") {echo "lang_edit";} else {echo "lang_add";} echo "\" method=\"post\">\n";
	echo "			<strong>"._LANG_ACTIVE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\"><input type=\"checkbox\" name=\"lang_active\" value=\"1\" "; if ($ar['language_active'] == 1){ echo "checked=\"checked\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "		<td width=\"150\" align=\"left\"><strong>"._LANG_NAME."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"lang_name\" maxlength=\"32\" size=\"50\" "; if ($_GET['action'] != "lang_add"){echo "value=\"".$ar['language_name']."\"";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"left\" valign=\"top\"><strong>"._LANG_CODE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"lang_code\" maxlength=\"2\" size=\"4\" "; if ($_GET['action'] != "lang_add"){echo "value=\"".$ar['language_code']."\"";} echo "><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"left\" valign=\"top\">\n";
	echo "			<strong>"._LANG_IMAGE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select name=\"lang_image\" class=\"input\">\n";
					$res7 = mysql_query("SELECT country_shortname, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar7 = mysql_fetch_array($res7)){
						echo "<option name=\"lang_image\" value=\"".$ar7['country_shortname']."\" "; if ($ar['language_image'] == $ar7['country_shortname']) {echo " selected";} echo ">".$ar7['country_name']."</option>\n";
					}
	echo "		</select><br><br>\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "lang_del"){echo _LANG_DEL;} elseif ($_GET['action'] == "lang_edit") {echo _LANG_EDIT;} else {echo _LANG_ADD;} echo "\" class=\"eden_button\">\n";
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
	echo "		<td width=\"25\" align=\"left\"><span class=\"nadpis-boxy\">"._LANG_IMAGE."</span></td>\n";
	echo "		<td width=\"25\" align=\"left\"><span class=\"nadpis-boxy\">"._LANG_CODE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._LANG_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._LANG_ACTIVE."</span></td>\n";
	echo "	</tr>";
 		$res = mysql_query("SELECT * FROM $db_language ORDER BY language_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=1;
		while($ar = mysql_fetch_array($res)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"80\" align=\"center\">";
 					if (CheckPriv("groups_admin_del") == 1){echo "<a href=\"?action=lang_edit&amp;id=".$ar['language_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_admin_del") == 1){echo "<a href=\"?action=lang_del&amp;id=".$ar['language_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
			echo "	</td>\n";
			echo "	<td width=\"20\" align=\"left\">".$ar['language_id']."</td>\n";
			echo "	<td width=\"150\" align=\"left\"><img src=\"".$url_flags.$ar['language_image'].".gif\" alt=\"\" border=\"0\"></td>\n";
			echo "	<td align=\"left\">".$ar['language_code']."</td>\n";
			echo "	<td align=\"left\">".$ar['language_name']."</td>\n";
			echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($ar['language_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "</tr>";
			$i++;
 		}
		echo "</table>";

}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "lang_add") {ShowMain();}
	if ($_GET['action'] == "lang_edit") {ShowMain();}
	if ($_GET['action'] == "lang_del") {ShowMain();}
include ("inc.footer.php");