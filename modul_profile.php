<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU PROFILU
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_profiles,$db_clan_games,$db_setup_images,$db_country;
	global $eden_cfg;
	global $url_profiles,$url_flags;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "add_profile";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "edit_profile"){
		if (CheckPriv("groups_profile_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "del_profile"){
		if (CheckPriv("groups_profile_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_profile_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	$res_setup = mysql_query("SELECT eden_setup_image_width, eden_setup_image_height, eden_setup_image_filesize FROM $db_setup_images WHERE eden_setup_image_for='profile_1'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	if ($_GET['action'] != "add_profile"){
		$res = mysql_query("SELECT * FROM $db_profiles WHERE profile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._PROFILE."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">\n";
	echo "			<a href=\"modul_profile.php?action=add_profile&amp;project=".$_SESSION['project']."\">"._PROFILE_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "	   		<a href=\"modul_clan_games.php?action=clan_game_add&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES."</a>\n";
	echo "		</td>\n";
	echo "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){		
		echo "<tr><td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
	 if ($_GET['action'] == "del_profile"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_profile"){echo "add_profile";} elseif ($_GET['action'] == "edit_profile"){ echo "edit_profile";} else { echo "del_profile";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._GAMESRV_DELCHECK."</span></strong>\n";
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
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form name=\"forma\" enctype=\"multipart/form-data\" action=\"sys_save.php?id=".$_GET['id']."&amp;action="; if ($_GET['action'] == "add_profile"){echo "add_profile";} else {echo "edit_profile";} echo "\" method=\"post\">\n";
	echo "			<strong>"._PROFILE_ALLOW."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"checkbox\" name=\"profile_allow\" value=\"1\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){ if ($ar['profile_allow']){echo " checked";}} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_FIRSTNAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"profile_firstname\" maxlength=\"20\" size=\"60\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo "value=\"".$ar['profile_firstname']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._PROFILE_MIDDLENAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"profile_middlename\" maxlength=\"20\" size=\"60\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo "value=\"".$ar['profile_middlename']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._PROFILE_SURNAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"profile_surname\" maxlength=\"30\" size=\"60\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo "value=\"".$ar['profile_surname']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._PROFILE_NICKNAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"profile_nickname\" maxlength=\"40\" size=\"60\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo "value=\"".$ar['profile_nickname']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._PROFILE_BIRTHDAY."</strong></td>\n";
	echo "		<td align=\"left\">\n";
				if ($_GET['action'] == "add_profile"){
					echo "<script language=\"javascript\">\n";
					echo "	var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"profile_birth\", \"btnDate1\",\"01.01.1910\",scBTNMODE_CUSTOMBLUE);\n";
					echo "</script>\n";
					echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
				} else {
					$birth_date = $ar['profile_birth'];
					echo "<script language=\"javascript\">\n";
					echo "	var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"profile_birth\", \"btnDate1\",\"".$birth_date[8].$birth_date[9].".".$birth_date[5].$birth_date[6].".".$birth_date[0].$birth_date[1].$birth_date[2].$birth_date[3]."\",scBTNMODE_CUSTOMBLUE);\n";
					echo "</script>\n";
					echo "<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
				}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_COUNTRY."</strong></td>\n";
	echo "		<td align=\"left\">";
				$res_country = mysql_query("SELECT country_id, country_name FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"profile_country_id\">";
				echo "	<option value=\"0\""; if ($_GET['action'] == "add"){echo " selected ";} echo ">"._PROFILE_COUNTRY_SELECT."</option>";
				while($ar_country = mysql_fetch_array($res_country)){
					echo "<option value=\"".$ar_country['country_id']."\""; if ($ar['profile_country_id'] == $ar_country['country_id']){echo " selected ";} echo ">".$ar_country['country_name']."</option>";
				}
				echo "</select>";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_GAME."</strong></td>\n";
	echo "	<td align=\"left\">";
				$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"profile_game_id\">";
				echo "	<option value=\"0\""; if ($_GET['action'] == "add"){echo " selected ";} echo ">"._PROFILE_GAME_SELECT."</option>";
				while($ar_game = mysql_fetch_array($res_game)){
					echo "<option value=\"".$ar_game['clan_games_id']."\""; if ($ar['profile_game_id'] == $ar_game['clan_games_id']){echo " selected ";} echo ">".$ar_game['clan_games_game']."</option>";
				}
				echo "</select>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_INFO."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"60\" rows=\"8\" name=\"profile_info\">"; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo $ar['profile_info'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_WINDFALLS."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea cols=\"60\" rows=\"8\" name=\"profile_windfalls\">"; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo $ar['profile_windfalls'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._PROFILE_ARTICLES_ID."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"profile_article_id\" maxlength=\"11\" size=\"15\" "; if ($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile"){echo "value=\"".$ar['profile_article_id']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._PROFILE_IMAGE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"file\" name=\"profile_image_1\" size=\"60\"><br>";
				$img_size = $ar_setup['eden_setup_image_filesize']/1024;
				echo _PROFILE_IMAGE_HELP_1.$ar_setup['eden_setup_image_width']._PROFILE_IMAGE_HELP_2.$ar_setup['eden_setup_image_height']._PROFILE_IMAGE_HELP_3.$img_size._PROFILE_IMAGE_HELP_4."<br>";
				if (($_GET['action'] == "edit_profile" || $_GET['action'] == "del_profile") && $ar['profile_image_1'] != ""){echo "<img src=\"".$url_profiles.$ar['profile_image_1']."\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_profile"){echo _PROFILE_ADD;} else {echo _PROFILE_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">&nbsp;</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._PROFILE_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._PROFILE_NICKNAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._PROFILE_GAME."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._PROFILE_IMAGE."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._PROFILE_ALLOW."</span></td>\n";
	echo "	</tr>";
 		$res_profile = mysql_query("SELECT p.*, cg.clan_games_game, c.country_shortname 
		FROM $db_profiles AS p 
		JOIN $db_clan_games AS cg ON cg.clan_games_id = p.profile_game_id 
		JOIN $db_country AS c ON c.country_id = p.profile_country_id 
		ORDER BY p.profile_surname ASC, p.profile_firstname ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar_profile = mysql_fetch_array($res_profile)){
			$profile_middlename = " ".$ar_profile['profile_middlename'];
			echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
			echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_profile_edit") == 1){echo "<a href=\"modul_profile.php?action=edit_profile&amp;id=".$ar_profile['profile_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
					if (CheckPriv("groups_profile_del") == 1){ echo " <a href=\"modul_profile.php?action=del_profile&amp;id=".$ar_profile['profile_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td>\n";
			echo "	<td width=\"20\" align=\"left\" valign=\"top\">".$ar_profile['profile_id']."</td>\n";
			echo "	<td width=\"20\" valign=\"top\"><img src=\"".$url_flags."/".$ar_profile['country_shortname'].".gif\" alt=\"\" width=\"18\" height=\"12\" border=\"0\"></td>\n";
			echo "	<td align=\"left\" valign=\"top\">".$ar_profile['profile_firstname'].$profile_middlename." ".$ar_profile['profile_surname']."</td>\n";
			echo "	<td align=\"left\" valign=\"top\">".$ar_profile['profile_nickname']."</td>\n";
			echo "	<td align=\"left\" valign=\"top\">".stripslashes($ar_profile['clan_games_game'])."</td>\n";
			echo "	<td width=\"20\" align=\"left\" valign=\"top\"><img src=\"images/sys_"; if ($ar_profile['profile_image_1'] != ""){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "	<td width=\"20\" align=\"left\" valign=\"top\"><img src=\"images/sys_"; if ($ar_profile['profile_allow'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "</tr>";
		}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "add_profile") {ShowMain();}
	if ($_GET['action'] == "edit_profile") {ShowMain();}
	if ($_GET['action'] == "del_profile") {ShowMain();}
	if ($_GET['action'] == "") {$_GET['action'] = "add_profile"; ShowMain(); }
include ("inc.footer.php");