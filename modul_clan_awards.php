<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU OCENENI																			
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_clan_awards,$db_country,$db_clan_games,$db_setup;
	global $url_clan_awards,$url_flags,$url_games;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	/* Provereni opravneni */
	if ($_GET['action'] == "clan_awards_edit"){
		if (CheckPriv("groups_clan_awards_edit") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} elseif ($_GET['action'] == "clan_awards_del"){
		if (CheckPriv("groups_clan_awards_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		if (CheckPriv("groups_clan_awards_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	}
	
	if ($_GET['action'] != "clan_awards_add"){	
		$res = mysql_query("SELECT * FROM $db_clan_awards WHERE clan_award_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	$res_setup = mysql_query("SELECT setup_basic_date FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_AWARDS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\"> <a href=\"modul_clan_awards.php?action=clan_awards_add&amp;project=".$_SESSION['project']."\">"._CLAN_AWARD_ADD."</a></td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "clan_awards_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "awards_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "clan_awards_del"){echo "clan_awards_del";} elseif ($_GET['action'] == "clan_awards_edit") {echo "clan_awards_edit";} else {echo "clan_awards_add";} echo "&amp;id=".$_GET['id']."&amp;y=".$_GET['y']."\" method=\"post\" name=\"forma\">\n";
	echo "			<strong>"._CLAN_AWARD_NAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"clan_award_name\" maxlength=\"80\" size=\"80\" "; if ($_GET['action'] == "clan_awards_edit" || $_GET['action'] == "clan_awards_del"){echo "value=\"".$ar['clan_award_name']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_AWARD_LINK."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"clan_award_link\" maxlength=\"255\" size=\"80\" "; if ($_GET['action'] == "clan_awards_edit" || $_GET['action'] == "clan_awards_del"){echo "value=\"".$ar['clan_award_link']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._CLAN_AWARD_GAME."</strong></td>\n";
	echo "		<td align=\"left\"><select name=\"clan_award_game_id\">";
				$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_game = mysql_fetch_array($res_game)){
					echo "<option value=\"".$ar_game['clan_games_id']."\" "; if ($ar['clan_award_game_id'] == $ar_game['clan_games_id']) {echo " selected";} echo ">".$ar_game['clan_games_game']."</option>";
				}
				echo "		</select>\n";
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._CLAN_AWARD_COUNTRY."</strong></td>\n";
				echo "	<td align=\"left\"><select name=\"clan_award_country_id\">";
				$res_flag = mysql_query("SELECT country_id, country_name, country_shortname FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_flag = mysql_fetch_array($res_flag)){
					echo "<option value=\"".$ar_flag['country_id']."\" "; if ($ar['clan_award_country_id'] == $ar_flag['country_id']) {echo " selected";} echo ">".$ar_flag['country_name']."</option>";
				}
	echo "		</select>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._CLAN_AWARD_PLACE."</strong></td>\n";
	echo "	<td align=\"left\"><select name=\"clan_award_place\">\n";
	echo "			<option value=\"1\" "; if ($ar['clan_award_place'] == "1") {echo " selected";} echo ">1</option>\n";
	echo "			<option value=\"2\" "; if ($ar['clan_award_place'] == "2") {echo " selected";} echo ">2</option>\n";
	echo "			<option value=\"3\" "; if ($ar['clan_award_place'] == "3") {echo " selected";} echo ">3</option>\n";
	echo "			<option value=\"4\" "; if ($ar['clan_award_place'] == "4") {echo " selected";} echo ">4</option>\n";
	echo "			<option value=\"5\" "; if ($ar['clan_award_place'] == "5") {echo " selected";} echo ">5</option>\n";
	echo "			<option value=\"6\" "; if ($ar['clan_award_place'] == "6") {echo " selected";} echo ">6</option>\n";
	echo "			<option value=\"5-6\" "; if ($ar['clan_award_place'] == "5-6") {echo " selected";} echo ">5-6</option>\n";
	echo "			<option value=\"5-8\" "; if ($ar['clan_award_place'] == "5-8") {echo " selected";} echo ">5-8</option>\n";
	echo "			<option value=\"9-16\" "; if ($ar['clan_award_place'] == "9-16") {echo " selected";} echo ">9-16</option>\n";
	echo "		</select>\n";
	echo "	</td>	\n";
	echo "</tr>\n";
	echo "<tr>";
			if ($_GET['action'] == "clan_awards_add"){
				$article_date_on = formatTimeS(time());
				$clan_award_date = $article_date_on[1].'.'.$article_date_on[2].'.'.$article_date_on[3];
			} else {
				$clan_award_date = FormatDatetime($ar['clan_award_date'],"d.m.Y");
			}
	echo "			<td align=\"right\" valign=\"top\" width=\"150\"><strong>"._CLAN_AWARD_DATE."</strong><br></td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "					<script language=\"javascript\">\n";
	echo "					var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"forma\", \"clan_award_date\", \"btnDate1\",\"".$clan_award_date."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "					</script>\n";
	echo "					<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clan_awards_del"){echo _CLAN_AWARD_DEL;} elseif ($_GET['action'] == "clan_award_edit"){echo _CLAN_AWARD_EDIT;} else {echo _CLAN_AWARD_ADD;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"857\" colspan=\"7\">";
				if ($_GET['y'] == ""){$_GET['y'] = date("Y");}
 				for ($i=FormatDatetime($ar_setup['setup_basic_date'],"Y");$i<=date("Y");$i++){
					$res_award_num = mysql_query("SELECT COUNT(*) FROM $db_clan_awards WHERE clan_award_date BETWEEN '".(integer)$i."-01-01 00:00:00' AND '".(integer)$i."-12-31 23:59:59' ORDER BY clan_award_date DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_award_num = mysql_fetch_array($res_award_num);
					if ($ar_award_num[0] > 0){$clan_award_year .= '<a href="modul_clan_awards.php?action=clan_awards_add&amp;y='.$i.'&amp;project='.$_SESSION['project'].'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';}else{$clan_award_year .= $i.'&nbsp;&nbsp;&nbsp;';}
				}
				echo $clan_award_year;
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr class=\"popisky\">\n";
		echo "	<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "	<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
		echo "	<td width=\"20\" align=\"center\"><span class=\"nadpis-boxy\">&nbsp;</span></td>\n";
		echo "	<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_AWARD_DATE."</span></td>\n";
		echo "	<td width=\"200\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_AWARD_GAME."</span></td>\n";
		echo "	<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CLAN_AWARD_PLACE."</span></td>\n";
		echo "	<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_AWARD_NAME."</span></td>\n";
		echo "</tr>";
		$where = "WHERE clan_award_date BETWEEN '".(integer)$_GET['y']."-01-01 00:00:00' AND '".(integer)$_GET['y']."-12-31 23:59:59'";
 		$res_award = mysql_query("SELECT * FROM $db_clan_awards $where ORDER BY clan_award_date DESC, clan_award_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=1;
		while ($ar_award = mysql_fetch_array($res_award)){
			$res_game = mysql_query("SELECT clan_games_id, clan_games_game, clan_games_shortname FROM $db_clan_games WHERE clan_games_id=".$ar_award['clan_award_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_game = mysql_fetch_array($res_game);
			$res_flag = mysql_query("SELECT country_shortname FROM $db_country WHERE country_id=".$ar_award['clan_award_country_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_flag = mysql_fetch_array($res_flag);
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_clan_awards_edit") == 1){ echo "<a href=\"modul_clan_awards.php?action=clan_awards_edit&amp;id=".$ar_award['clan_award_id']."&amp;y=".$_GET['y']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
					if (CheckPriv("groups_clan_awards_del") == 1){ echo "<a href=\"modul_clan_awards.php?action=clan_awards_del&amp;id=".$ar_award['clan_award_id']."&amp;y=".$_GET['y']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td> \n";
			echo "	<td width=\"30\" align=\"left\" valign=\"top\">".$ar_award['clan_award_id']."</td>\n";
			echo "	<td width=\"20\" align=\"left\" valign=\"top\"><img src=\"".$url_clan_awards.$ar_award['clan_award_place'].".gif\" width=\"16\" height=\"13\" alt=\"".$ar_award['clan_award_place'].ClanAwardsPlaceExt($ar_award['clan_award_place'])."\"></td>\n";
			echo "	<td width=\"50\" align=\"left\" valign=\"top\">".FormatDatetime($ar_award['clan_award_date'],"d.m.Y")."</td>\n";
			echo "	<td width=\"200\" align=\"left\" valign=\"top\"><img src=\"".$url_games.$ar_game['clan_games_shortname'].'.gif'."\"> ".$ar_game['clan_games_game']."</td>\n";
			echo "	<td width=\"50\" align=\"left\" valign=\"top\">".$ar_award['clan_award_place'].ClanAwardsPlaceExt($ar_award['clan_award_place'])."</td>\n";
			echo "	<td align=\"left\" valign=\"top\"><img src=\"".$url_flags.$ar_flag['country_shortname'].".gif\" width=\"18\" height=\"12\"> <a href=\"".$ar_award['clan_award_link']."\" target=\"_blank\">".$ar_award['clan_award_name']."</a></td>\n";
			echo "</tr>";
			$i++;
 		}
	echo "	<tr>\n";
	echo "		<td width=\"857\" colspan=\"7\">".$clan_award_year."</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "clan_awards_add") {ShowMain();}
	if ($_GET['action'] == "clan_awards_edit") {ShowMain();}
	if ($_GET['action'] == "clan_awards_del") {ShowMain();}
	if ($_GET['action'] == "") {$_GET['action'] = "clan_awards_add"; ShowMain(); }
include ("inc.footer.php");