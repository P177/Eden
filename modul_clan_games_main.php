<?php
/***********************************************************************************************************
*
*		PRIDANI HRY
*
***********************************************************************************************************/
function GameMain(){
	
	global $db_clan_games_main;
	global $eden_cfg;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "clan_game_main_add"){
		if (CheckPriv("groups_clan_games_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	}elseif ($_GET['action'] == "clan_game_main_edit"){
		if (CheckPriv("groups_clan_games_edit") <> 1){echo _NOTENOUGHPRIV;exit;}
	}elseif ($_GET['action'] == "clan_game_main_del"){
		if (CheckPriv("groups_clan_games_del") <> 1){echo _NOTENOUGHPRIV;exit;}
	}
	if ($_GET['action'] == "clan_game_edit" || $_GET['action'] == "clan_game_del"){
		$res_game_main = mysql_query("SELECT * FROM $db_clan_games_main WHERE clan_games_main_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_game_main = mysql_fetch_array($res_game_main);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN_GAMES_MAIN." - "; if ($_GET['action'] == "clan_game_main_edit"){ echo _CLAN_GAME_EDIT;} elseif ($_GET['action'] == "clan_game_main_del"){ echo _CLAN_GAME_DEL;} else {echo _CLAN_GAME_ADD;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CLAN_GAME_ADD."\">";
				if ($eden_cfg['modul_clanwars'] == 1 && CheckPriv("groups_clanwars_add") == 1) { echo "<a href=\"modul_clan_clanwars.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._CLAN_MAIN."</a>"; $separator = "&nbsp;&nbsp;|&nbsp;&nbsp;";}
				echo $separator."<a href=\"modul_clan_games.php?action=clan_game_add&amp;project=".$_SESSION['project']."\">"._CLAN_GAMES."</a>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "clan_game_main_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "clan_game_main_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "	<tr>\n";
	echo "		<td align=\"left\"><form action=\"sys_save.php?action=".$_GET['action']."&amp;id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<strong>"._CLAN_GAME_SHORTNAME."</strong>&nbsp;<input type=\"text\" name=\"main_shortname\" maxlength=\"20\" size=\"10\" "; if ($_GET['action'] == "clan_game_main_edit" || $_GET['action'] == "clan_game_main_del"){echo "value=\"".$ar2['clan_games_shortname']."\"";} echo ">&nbsp;&nbsp;\n";
	echo "			<strong>"._CLAN_GAME."</strong>&nbsp;<input type=\"text\" name=\"main_game\" maxlength=\"80\" size=\"50\" "; if ($_GET['action'] == "clan_game_main_edit" || $_GET['action'] == "clan_game_main_del"){echo "value=\"".$ar2['clan_games_main_game']."\"";} echo ">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clan_game_main_edit"){ echo _CLAN_GAME_EDIT;} elseif ($_GET['action'] == "clan_game_main_del"){echo _CLAN_GAME_DEL;} else {echo _CLAN_GAME_ADD;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"150\" align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME_SHORTNAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_GAME."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT * FROM $db_clan_games_main ORDER BY clan_games_main_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">";
 					if (CheckPriv("groups_clanwars_edit") == 1){echo "<a href=\"modul_clan_games.php?action=clan_game_main_edit&amp;id=".$ar['clan_games_main_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_clanwars_del") == 1){echo " <a href=\"modul_clan_games.php?action=clan_game_main_del&amp;id=".$ar['clan_games_main_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";} 
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"left\">".$ar['clan_games_main_id']."</td>\n";
		echo "	<td width=\"150\" align=\"left\">".$ar['clan_games_main_shortname']."</td>\n";
		echo "	<td align=\"left\">".$ar['clan_games_main_game']."</td>\n";
		echo "</tr>";
		$i++;
	 }
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "") { GameMain(); }
	if ($_GET['action'] == "clan_game_main_add") { GameMain(); }
	if ($_GET['action'] == "clan_game_main_edit") { GameMain(); }
	if ($_GET['action'] == "clan_game_main_del") { GameMain(); }
include ("inc.footer.php");