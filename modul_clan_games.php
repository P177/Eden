<?php
function Menu(){
	
	global $eden_cfg;
	
	switch ($_GET['action']){
		case "clan_game_add":
			$title = _CLAN_GAMES." - "._CLAN_GAME_ADD;
			break;
		case "clan_game_edit":
			$title = _CLAN_GAMES." - "._CLAN_GAME_EDIT;
			break;
		case "clan_game_del":
			$title = _CLAN_GAMES." - "._CLAN_GAME_DEL;
			break;
		default:
			$title = _CLAN_GAMES;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CLAN_GAME_ADD."\"> ";
	if ($eden_cfg['modul_clanwars'] == 1 && CheckPriv("groups_clanwars_add") == 1) {
		$menu .= "<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a>";
		$separator = "&nbsp;&nbsp;|&nbsp;&nbsp;";
	}
	$menu .= $separator."<a href=\"modul_clan_games_main.php?action=clan_game_main_add&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES_MAIN."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"modul_clan_games.php?action=clan_game_add&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"modul_clan_games.php?action=clan_game_img_upload&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES_MANAGE_IMAGES."</a>";
	$menu .= "		</td>";
	$menu .= "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "clan_game_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "clan_game_del_ch";}
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}	
	$menu .= "</table>";
	
	return $menu;
}
/***********************************************************************************************************
*
*		PRIDANI HRY
*
***********************************************************************************************************/
function Game(){
	
	global $db_clan_games,$db_clan_games_main;
	global $eden_cfg;
	global $url_games;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "clan_game_add"){
		if (CheckPriv("groups_clan_games_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	}elseif ($_GET['action'] == "clan_game_edit"){
		if (CheckPriv("groups_clan_games_edit") <> 1){echo _NOTENOUGHPRIV;exit;}
	}elseif ($_GET['action'] == "clan_game_del"){
		if (CheckPriv("groups_clan_games_del") <> 1){echo _NOTENOUGHPRIV;exit;}
	}
	
	$res_game = mysql_query("SELECT * FROM $db_clan_games WHERE clan_games_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_game = mysql_fetch_array($res_game);
	if ($_GET['mode'] == "league"){
		include ("modul_league.php");
		echo LeagueMenu();
	} else {
		echo Menu();
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><form action=\"sys_save.php?action=".$_GET['action']."&amp;id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<strong>"._CLAN_GAME_SHORTNAME."</strong>&nbsp;<input type=\"text\" name=\"shortname\" maxlength=\"20\" size=\"10\" "; if ($_GET['action'] == "clan_game_edit" || $_GET['action'] == "clan_game_del"){echo "value=\"".$ar_game['clan_games_shortname']."\"";} echo ">&nbsp;&nbsp;\n";
	echo "			<strong>"._CLAN_GAME."</strong>&nbsp;<input type=\"text\" name=\"game\" maxlength=\"80\" size=\"30\" "; if ($_GET['action'] == "clan_game_edit" || $_GET['action'] == "clan_game_del"){echo "value=\"".$ar_game['clan_games_game']."\"";} echo ">\n";
					$res_game_main = mysql_query("SELECT clan_games_main_id, clan_games_main_game FROM $db_clan_games_main ORDER BY clan_games_main_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"clan_game_main_id\">";
					echo "<option value=\"\">"._CUPS_BRACKETS_MAP_CHOOSE_GAME."</option>";
					while($ar_game_main = mysql_fetch_array($res_game_main)){
						echo "<option value=\"".$ar_game_main['clan_games_main_id']."\" "; if ($ar_game['clan_games_game_main_id'] == $ar_game_main['clan_games_main_id']){ echo "selected=\"selected\"";} echo ">".$ar_game_main['clan_games_main_game']."</option>";
					}
	echo "			</select>";
	echo "			<strong>"._CLAN_GAME_ACTIVE."</strong>&nbsp;<input type=\"checkbox\" name=\"clan_game_active\" value=\"1\" "; if ($ar_game['clan_games_active'] == 1){ echo "checked";} echo " />\n";
	echo "			<strong>"._CLAN_GAME_REPRE."</strong>&nbsp;<input type=\"checkbox\" name=\"clan_game_repre\" value=\"1\" "; if ($ar_game['clan_games_repre'] == 1){ echo "checked";} echo " />\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clan_game_edit"){ echo _CLAN_GAME_EDIT;} elseif ($_GET['action'] == "clan_game_del"){echo _CLAN_GAME_DEL;} else {echo _CLAN_GAME_ADD;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">Img</span></td>\n";
	echo "		<td width=\"120\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME_SHORTNAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME_MAIN."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME_ACTIVE."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME_REPRE."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("
	SELECT cg.*, cgm.* 
	FROM $db_clan_games AS cg
	LEFT JOIN $db_clan_games_main AS cgm ON cg.clan_games_game_main_id=cgm.clan_games_main_id 
	ORDER BY cg.clan_games_active DESC, cgm.clan_games_main_game ASC, cg.clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		if ($ar['clan_games_active'] == 0){$cat_class = "cat_over";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">";
 					if (CheckPriv("groups_clanwars_edit") == 1){echo "<a href=\"modul_clan_games.php?action=clan_game_edit&amp;id=".$ar['clan_games_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_clanwars_del") == 1){echo " <a href=\"modul_clan_games.php?action=clan_game_del&amp;id=".$ar['clan_games_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";} 
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['clan_games_id']."</td>\n";
		echo "	<td width=\"20\" align=\"left\"><img src=\"".$url_games.$ar['clan_games_shortname'].".gif\"></td>\n";
		echo "	<td width=\"120\" align=\"left\">".$ar['clan_games_shortname']."</td>\n";
		echo "	<td align=\"left\">".$ar['clan_games_main_shortname']."</td>\n";
		echo "	<td align=\"left\">".$ar['clan_games_game']."</td>\n";
		echo "	<td width=\"50\" align=\"left\"><img src=\"images/sys_"; if ($ar['clan_games_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">"; echo "</td>\n";
		echo "	<td width=\"50\" align=\"left\"><img src=\"images/sys_"; if ($ar['clan_games_repre'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\">"; echo "</td>\n";
		echo "</tr>";
		$i++;
	 }
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "" || $_GET['action'] == "showmain") { Game(); }
	if ($_GET['action'] == "clan_game_add") { Game();}
	if ($_GET['action'] == "clan_game_edit") { Game();}
	if ($_GET['action'] == "clan_game_del") { Game();}
	if ($_GET['action'] == "clan_game_img_upload") { EdenSysImageManager(); }
	if ($_GET['action'] == "clan_game_img_del") { EdenSysImageManager(); }
include ("inc.footer.php");