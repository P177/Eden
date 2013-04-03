<?php
/***********************************************************************************************************
*
*		 PRIDANI OBRAZKUU MAPY
*
***********************************************************************************************************/
function Map(){
	
	global $db_clan_maps,$db_clan_games_main;
	global $eden_cfg;
	global $url_clan_maps;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_cups_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_GET['action'] != "clan_maps_add"){	
		$res_map = mysql_query("SELECT * FROM $db_clan_maps WHERE clan_map_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_map = mysql_fetch_array($res_map);
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._CLAN." - "; if ($_GET['action'] == "clan_map_edit"){ echo _CLAN_MAPS_EDIT;} elseif ($_GET['action'] == "clan_map_del"){echo _CLAN_MAPS_DEL;} else {echo _CLAN_MAPS_ADD;} echo "</td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "clan_map_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "clan_maps_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"150\" align=\"right\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "clan_map_edit") {echo "clan_map_edit";} elseif ($_GET['action'] == "clan_map_del"){ echo "clan_map_del";} else {echo "clan_map_add";} echo "&project=".$_SESSION['project']."\"  enctype=\"multipart/form-data\" method=\"post\">\n";
	echo "			<strong>"._CLAN_GAME."</strong>";
	echo "		</td>";
	echo "		<td align=\"left\">";
				if ($_GET['lid'] != ""){$game_id = $_GET['lid'];} else {$game_id = $ar_map['clan_map_game_id'];}
				$res_game_main = mysql_query("SELECT clan_games_main_id, clan_games_main_game FROM $db_clan_games_main  ORDER BY clan_games_main_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<select name=\"clan_map_game\">";
				echo "<option value=\"\">"._CUPS_BRACKETS_MAP_CHOOSE_GAME."</option>";
				while($ar_game_main = mysql_fetch_array($res_game_main)){
					echo "<option value=\"".$ar_game_main['clan_games_main_id']."\" "; if ( $game_id == $ar_game_main['clan_games_main_id']){ echo "selected=\"selected\"";} echo ">".$ar_game_main['clan_games_main_game']."</option>";
				}
	echo "			</select>";
	echo "		</td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAPS_NAME."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"clan_map_name\" value=\"".$ar_map['clan_map_name']."\" size=\"35\" maxsize=\"50\"></td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CLAN_MAPS_IMG."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"file\" name=\"clan_map_file\" size=\"20\"><br>";
				if ($_GET['action'] == "clan_map_edit" || $_GET['action'] == "clan_map_del"){ echo "<img src=\"".$url_clan_maps.$ar_map['clan_map_img']."\">";}
	echo "		</td>\n";
	echo "	</tr>";
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td width=\"150\" align=\"right\">&nbsp;</td>\n";
	echo "		<td align=\"left\"><input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "clan_map_edit"){ echo _CLAN_MAPS_EDIT;} elseif ($_GET['action'] == "clan_map_del"){ echo _CLAN_MAPS_DEL;} else {echo _CLAN_MAPS_ADD;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"right\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_MAPS_GAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_MAPS_NAME."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CLAN_MAPS_IMG."</span></td>\n";
	echo "	</tr>";
	if ($_GET['lid'] != ""){$where = " WHERE cm.clan_map_game_id=".(integer)$_GET['lid'];}
	$res_map = mysql_query("SELECT cm.clan_map_id, cm.clan_map_name, cm.clan_map_img, cgm.clan_games_main_game 
	FROM $db_clan_maps AS cm 
	LEFT JOIN $db_clan_games_main AS cgm ON cgm.clan_games_main_id=cm.clan_map_game_id 
	$where 
	ORDER BY cgm.clan_games_main_game ASC, cm.clan_map_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar_map = mysql_fetch_array($res_map)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "		<td width=\"80\" align=\"center\">"; 
			if (CheckPriv("groups_cups_edit") == 1){echo " <a href=\"modul_clan_maps.php?action=clan_map_edit&amp;id=".$ar_map['clan_map_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";} else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">"; } 			
			if (CheckPriv("groups_cups_del") == 1){echo " <a href=\"modul_clan_maps.php?action=clan_map_del&amp;id=".$ar_map['clan_map_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";} else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">"; } 
		echo "		</td>\n";
		echo "		<td width=\"50\" align=\"right\">".$ar_map['clan_map_id']."</td>\n";
		echo "		<td align=\"left\">".$ar_map['clan_games_main_game']."</td>\n";
		echo "		<td align=\"left\">".$ar_map['clan_map_name']."</td>\n";
		echo "		<td align=\"left\">".$ar_map['clan_map_img']."</td>\n";
		echo "	</tr>\n";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		 MAZANI OBRAZKU MAP CUPU
*
***********************************************************************************************************/
function MapDel(){
	
	global $question;
	global $db_clan_maps,$db_clan_games;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_cups_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true"){
		$res = mysql_query("DELETE FROM $db_clan_maps WHERE clan_map_id=".(float)$_POST['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$_POST['confirm'] = false;
		AddMapImg();
		exit;
	}
	
	if ($_POST['confirm'] == "false"){ShowMain();}
	
	if ($_POST['confirm'] == ""){
		$res_cup_image = mysql_query("SELECT cmi.clan_map_id, cmi.clan_map_mapname, cg.clan_games_game 
		FROM $db_clan_maps AS cmi 
		LEFT JOIN $db_clan_games AS cg ON cg.clan_games_id=cmi.clan_map_game_id 
		WHERE cmi.clan_map_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_cup_image = mysql_fetch_array($res_cup_image);
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\">"._CUPS_BRACKETS_CUPS." - "._CUPS_BRACKETS_DEL_MAP_IMG."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CUPS_BRACKETS_MAINMENU."\">\n";
		echo "		<a href=\"modul_cups.php?project=".$_SESSION['project']."\">"._CUPS_BRACKETS_MAINMENU."</a></td>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
		echo "		<td width=\"150\" align=\"left\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_GAME."</span></td>\n";
		echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_MAP_NAME."</span></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" align=\"center\">".$ar_cup_image['clan_map_id']."</td>\n";
		echo "		<td align=\"left\">".$ar_cup_image['clan_games_game']."</td>\n";
		echo "		<td align=\"left\">".$ar_cup_image['clan_map_mapname']."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"3\"><strong><span style=\"color : #FF0000;\">"._CUPS_BRACKETS_CHECK_DEL_IMG."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "			<form action=\"modul_cups.php?action=del_cup_img\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"50\" align=\"left\">\n";
		echo "			<form action=\"modul_cups.php?action=addmapimage\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" align=\"left\" width=\"350\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
include ("inc.header.php");
	if ($_GET['action'] == "") { Map(); }
	if ($_GET['action'] == "clan_map_add") { Map(); }
	if ($_GET['action'] == "clan_map_edit") { Map(); }
	if ($_GET['action'] == "clan_map_del") { Map(); }
include ("inc.footer.php");