<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI VIDEII																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_videos,$db_setup,$db_clan_games;
	global $url_games;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	/* Provereni opravneni */
	if ($_GET['action'] == "video_edit"){
		if (CheckPriv("groups_video_edit") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} elseif ($_GET['action'] == "video_del"){
		if (CheckPriv("groups_video_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		if (CheckPriv("groups_video_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	}
	
	if ($_GET['action'] != "video_add"){	
		$res = mysql_query("SELECT * FROM $db_videos WHERE video_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	$res_setup = mysql_query("SELECT setup_basic_date FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._VIDEOS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\"> <a href=\"modul_videos.php?action=video_add&amp;project=".$_SESSION['project']."\">"._VIDEO_ADD."</a></td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "video_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "awards_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "video_del"){echo "video_del";} elseif ($_GET['action'] == "video_edit") {echo "video_edit";} else {echo "video_add";} echo "&amp;id=".$_GET['id']."&amp;y=".$_GET['y']."\" method=\"post\" name=\"forma\">\n";
	echo "			<strong>"._VIDEO_NAME."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"video_name\" maxlength=\"80\" size=\"80\" "; if ($_GET['action'] == "video_edit" || $_GET['action'] == "video_del"){echo "value=\"".$ar['video_name']."\"";} echo ">";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>";
	echo "		<td width=\"150\" align=\"right\"><strong>"._VIDEO_SHOW."</strong></td>";
	echo "		<td align=\"left\"><input type=\"checkbox\" name=\"video_show\" value=\"1\""; if ($ar['video_show'] == 1) {echo " checked=\"checked\"";} echo "></td>\n";
	echo "	</tr>";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._VIDEO_DESC."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea name=\"video_desc\" rows=\"5\" cols=\"60\">"; if ($_GET['action'] == "video_edit" || $_GET['action'] == "video_del"){echo $ar['video_description'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._VIDEO_CODE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea name=\"video_code\" rows=\"10\" cols=\"80\">"; if ($_GET['action'] == "video_edit" || $_GET['action'] == "video_del"){echo $ar['video_code'];} echo "</textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._VIDEO_GAME."</strong></td>\n";
	echo "		<td align=\"left\"><select name=\"video_game_id\">";
				echo "<option value=\"0\" "; if ($ar['video_game_id'] == $ar_game['clan_games_id']) {echo " selected";} echo ">"._VIDEO_GAME_SELECT."</option>";
				$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_game = mysql_fetch_array($res_game)){
					echo "<option value=\"".$ar_game['clan_games_id']."\" "; if ($ar['video_game_id'] == $ar_game['clan_games_id']) {echo " selected";} echo ">".$ar_game['clan_games_game']."</option>";
				}
	echo "		</select>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>";
			if ($_GET['action'] == "video_add"){
				$date_from_h = date("H",time());
				$date_from_m = date("i",time());
				$video_date_from = date("d.m.Y",time());
			} else {
				$df = $ar['video_date_from'];
				$date_from_h = $df[11].$df[12];
				$date_from_m = $df[14].$df[15];
				$video_date_from = FormatDatetime($ar['video_date_from'],"d.m.Y");
			}
	echo "			<td align=\"right\" valign=\"top\" width=\"150\"><strong>"._VIDEO_DATE_FROM."</strong><br></td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "					<script language=\"javascript\">\n";
	echo "					var FromDate = new ctlSpiffyCalendarBox(\"FromDate\", \"forma\", \"video_date_from\", \"btnDate1\",\"".$video_date_from."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "					</script>\n";
	echo "					<script language=\"javascript\">FromDate.writeControl(); FromDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> <select name=\"video_date_from_h\">";
						for ($i=0;$i<=23;$i++){
							echo "<option value=\"".$i."\" "; if ($date_from_h == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
						}
						echo "</select><strong>:</strong><select name=\"video_date_from_m\">";
						for ($i=0;$i<=59;$i++){
							echo "<option value=\"".$i."\" "; if ($date_from_m == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
						}
						echo "</select><strong>:</strong>00<br><br>";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo "<tr>";
			if ($_GET['action'] == "video_add"){
				$date_to_h = date("H",time() + 3600);
				$date_to_m = date("i",time());
				$video_date_to = date("d.m.Y",time());
			} else {
				$dt = $ar['video_date_to'];
				$date_to_h = $dt[11].$dt[12];
				$date_to_m = $dt[14].$dt[15];
				$video_date_to = FormatDatetime($ar['video_date_to'],"d.m.Y");
			}
	echo "			<td align=\"right\" valign=\"top\" width=\"150\"><strong>"._VIDEO_DATE_TO."</strong><br></td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "					<script language=\"javascript\">\n";
	echo "					var ToDate = new ctlSpiffyCalendarBox(\"ToDate\", \"forma\", \"video_date_to\", \"btnDate2\",\"".$video_date_to."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "					</script>\n";
	echo "					<script language=\"javascript\">ToDate.writeControl(); ToDate.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong> <select name=\"video_date_to_h\">";
						for ($i=0;$i<=23;$i++){
							echo "<option value=\"".$i."\" "; if ($date_to_h == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
						}
						echo "</select><strong>:</strong><select name=\"video_date_to_m\">";
						for ($i=0;$i<=59;$i++){
							echo "<option value=\"".$i."\" "; if ($date_to_m == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
						}
						echo "</select><strong>:</strong>00<br><br>";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "video_del"){echo _VIDEO_DEL;} elseif ($_GET['action'] == "video_edit"){echo _VIDEO_EDIT;} else {echo _VIDEO_ADD;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr class=\"popisky\">\n";
		echo "	<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
		echo "	<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
		echo "	<td width=\"150\" align=\"center\"><span class=\"nadpis-boxy\">"._VIDEO_DATE_FROM." - "._VIDEO_DATE_TO."</span></td>\n";
		echo "	<td align=\"center\"><span class=\"nadpis-boxy\">"._VIDEO_NAME."</span></td>\n";
		echo "	<td width=\"25\" align=\"center\"><span class=\"nadpis-boxy\">"._VIDEO_GAME."</span></td>\n";
		echo "	<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._VIDEO_SHOW."</span></td>\n";
		echo "</tr>";
		$res_video = mysql_query("SELECT * FROM $db_videos ORDER BY video_date_from DESC, video_show ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=1;
		while ($ar_video = mysql_fetch_array($res_video)){
			$res_game = mysql_query("SELECT clan_games_id, clan_games_game, clan_games_shortname FROM $db_clan_games WHERE clan_games_id=".$ar_video['video_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_game = mysql_fetch_array($res_game);
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "	<td width=\"65\" valign=\"top\">"; if (CheckPriv("groups_video_edit") == 1){ echo "<a href=\"modul_videos.php?action=video_edit&amp;id=".$ar_video['video_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
					if (CheckPriv("groups_video_del") == 1){ echo "<a href=\"modul_videos.php?action=video_del&amp;id=".$ar_video['video_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } echo "</td> \n";
			echo "	<td width=\"30\" align=\"left\" valign=\"top\">".$ar_video['video_id']."</td>\n";
			echo "	<td width=\"150\" align=\"left\" valign=\"top\">".FormatDatetime($ar_video['video_date_from'],"d.m.Y H:i:s")."<br>".FormatDatetime($ar_video['video_date_to'],"d.m.Y H:i:s")."</td>\n";
			echo "	<td align=\"left\" valign=\"top\">".$ar_video['video_name']."</td>\n";
			echo "	<td width=\"25\" align=\"center\" valign=\"middle\"><img src=\"".$url_games.$ar_game['clan_games_shortname'].'.gif'."\"></td>\n";
			echo "	<td align=\"center\" valign=\"top\"><img src=\"images/sys_"; if ($ar_video['video_show'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
			echo "</tr>";
			$i++;
 		}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "video_add") {ShowMain();}
	if ($_GET['action'] == "video_edit") {ShowMain();}
	if ($_GET['action'] == "video_del") {ShowMain();}
	if ($_GET['action'] == "") {$_GET['action'] = "video_add"; ShowMain(); }
include ("inc.footer.php");