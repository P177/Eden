<?php
function Menu(){
	switch ($_GET['action']){
		case "game_add":
			$headline = _LEAGUE_GAME_ADD;
		break;
		case "game_edit":
			$headline = _LEAGUE_GAME_EDIT;
		break;
		case "game_del":
			$headline = _LEAGUE_GAME_DEL;
		break;
		case "guid_add":
			$headline = _LEAGUE_GUID_ADD; 
		break;
		case "guid_edit":
			$headline = _LEAGUE_GUID_EDIT;
		break;
		case "league_add":
			$headline = _LEAGUE_LEAGUE_ADD; 
		break;
		case "league_edit":
			$headline = _LEAGUE_LEAGUE_EDIT;
		break;
		case "league_del":
			$headline = _LEAGUE_LEAGUE_DEL;
		break;
		case "league_award_add":
			$headline = _LEAGUE_AWARD_ADD; 
		break;
		case "league_award_edit":
			$headline = _LEAGUE_AWARD_EDIT;
		break;
		case "league_award_del":
			$headline = _LEAGUE_AWARD_DEL;
		break;
		case "league_player_add":
			$headline = _LEAGUE_PLAYER_ADD;
		break;
		case "league_player_edit":
			$headline = _LEAGUE_PLAYER_EDIT;
		break;
		case "league_player_del":
			$headline = _LEAGUE_PLAYER_DEL;
		break;
		case "league_player_ban_add":
			$headline = _LEAGUE_PLAYER_BAN_ADD;
		break;
		case "league_player_ban_edit":
			$headline = _LEAGUE_PLAYER_BAN_EDIT;
		break;
		case "league_players_show":
			if ($_GET['lid'] != ""){$headline_2 = _LEAGUE_TEAM_LIST_LEAGUE.stripslashes($_GET['lname']);} else {$headline_2 = "";}
			$headline = _LEAGUE_PLAYERS_SHOW.$headline_2;
		break;
		case "league_season_add":
			$headline = _LEAGUE_SEASON_ADD;
		break;
		case "league_season_edit":
			$headline = _LEAGUE_SEASON_EDIT;
		break;
		case "league_season_del":
			$headline = _LEAGUE_SEASON_DEL;
		break;
		case "league_team_add":
			$headline = _LEAGUE_TEAM_ADD;
		break;
		case "league_team_edit":
			$headline = _LEAGUE_TEAM_EDIT;
		break;
		case "league_team_del":
			$headline = _LEAGUE_TEAM_DEL;
		break;
		case "list_allowed_players":
			$headline = _LEAGUE_LIST_ALLOWED_PLAYERS;
		break;
		case "league_team_show":
			if ($_GET['lid'] != ""){$headline_2 = _LEAGUE_TEAM_LIST_LEAGUE.stripslashes($_GET['lname']);} else {$headline_2 = "";}
			$headline = _LEAGUE_TEAM_LIST.$headline_2;
		break;
		case "rounds":
			$headline = _LEAGUE_SEASON_ROUNDS;
		break;
		case "results_add":
   			$headline = _LEAGUE_SEASON_ROUND_RESULTS_ADD;
		break;
		case "results_show":
   			$headline = _LEAGUE_SEASON_ROUND_RESULTS;
		break;
		default:
			$headline = _LEAGUE_LEAGUE_ADD;
	}	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">"._LEAGUE." - ".$headline."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\">\n";
	$menu .= "			<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" />&nbsp;&nbsp;";
	if (CheckPriv("groups_league_add") == 1){$menu .= "<a href=\"modul_league.php?action=league_add&amp;project=".$_SESSION['project']."\">"._LEAGUE_LEAGUES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
	/*if (CheckPriv("groups_league_season_add") == 1){$menu .= "<a href=\"modul_league.php?action=season_add&amp;project=".$_SESSION['project']."\">"._LEAGUE_SEASONS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}*/
	if (CheckPriv("groups_league_team_add") == 1){$menu .= "<a href=\"modul_league.php?action=league_team_show&amp;project=".$_SESSION['project']."\">"._LEAGUE_TEAMS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
	if (CheckPriv("groups_league_player_add") == 1){$menu .= "<a href=\"modul_league.php?action=league_players_show&amp;project=".$_SESSION['project']."\">"._LEAGUE_PLAYERS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
	if (CheckPriv("groups_league_add") == 1){$menu .= "<a href=\"modul_clan_games.php?action=clan_game_add&amp;mod=league&amp;project=".$_SESSION['project']."\">"._LEAGUE_GAMES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
	if (CheckPriv("groups_league_add") == 1){$menu .= "<a href=\"modul_league.php?action=guid_add&amp;project=".$_SESSION['project']."\">"._LEAGUE_GUIDS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";}
	if (CheckPriv("groups_league_add") == 1){$menu .= "<a href=\"modul_league.php?action=league_award_add&amp;project=".$_SESSION['project']."\">"._LEAGUE_AWARDS."</a>";
		if ($_GET['action'] == "league_award_add" || $_GET['action'] == "league_award_edit" || $_GET['action'] == "league_award_del" || $_GET['action'] == "league_award_img_upload"){$menu .= "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_league.php?action=league_award_img_upload&amp;project=".$_SESSION['project']."\">"._LEAGUE_AWARDS_MANAGE_IMAGES."</a>";}
	}
	$menu .= "		</td>\n";
	$menu .= "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>\n";
	
	return $menu;
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU TEAMU
*
***********************************************************************************************************/
function ShowTeam(){
	
	global $db_admin,$db_admin_guids,$db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_league_leagues,$db_league_players,$db_clan_games;
	
	$ser	= AGet($_GET,'ser');
	$by		= AGet($_GET,'by');
	switch ($by){
		case "id":
			$order_by = " ORDER BY lt.league_team_id ";
		break;
		case "tag":
			$order_by = " ORDER BY lt.league_team_tag ";
		break;
		case "name":
			$order_by = " ORDER BY lt.league_team_name ";
		break;
		default:
			$order_by = " ORDER BY lt.league_team_name ";
			$by = "name";
			$ser = "asc";
		break;
	}
	
	switch ($ser){
		case "asc":
			$order = " ASC ";
		break;
		case "desc":
			$order = " DESC ";
		break;
	}
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	// Pokud si nechame zobrazit teamy v dane lize $_GET['lid'] nesmi byt prazdne 
	// Paklize je prazdne zobrazi se jen teamy obecne bez prislusnosti k lize
	if (!empty($_GET['lid'])){
		//$league_where = "ltsl.league_teams_sub_league_league_id=".(integer)$_GET['lid']." ";
		$res_team = mysql_query("
		SELECT lt.league_team_id, lt.league_team_name, lt.league_team_tag, ll.league_league_id, ll.league_league_name, ll.league_league_game_id, 
		ll.league_league_team_sub_max_players, ll.league_league_team_sub_min_players, ltsl.league_teams_sub_league_team_sub_id, ltsl.league_teams_sub_league_players  
		FROM $db_league_teams_sub_leagues AS ltsl 
		JOIN $db_league_leagues AS ll ON ll.league_league_id=ltsl.league_teams_sub_league_league_id 
		JOIN $db_league_teams AS lt ON lt.league_team_id=ltsl.league_teams_sub_league_team_id 
		WHERE ltsl.league_teams_sub_league_league_id=".(integer)$_GET['lid']." 
		$order_by $order") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		$res_team = mysql_query("
		SELECT lt.league_team_id, lt.league_team_name, lt.league_team_tag 
		FROM $db_league_teams AS lt 
		WHERE lt.league_team_hibernate=".(integer)$_GET['hib']." 
		$order_by $order") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$ar_team = mysql_fetch_array($res_team);
	$_GET['lname'] = $ar_team['league_league_name'];
	mysql_data_seek($res_team, 0);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	//echo "			<td colspan=\"5\"><a href=\"modul_league.php?action=list_allowed_players&lid=".$_GET['lid']."&project=".$_SESSION['project']."\" target=\"_self\">"._LEAGUE_LIST_ALLOWED_PLAYERS."</a></td>\n";
	if (!empty($_GET['lid'])){
		$link = "&nbsp;";
	} else {
		if ($_GET['hib'] == "" || $_GET['hib'] == 0){
			$link = "<a href=\"modul_league.php?action=league_team_show&hib=1&project=".$_SESSION['project']."\" target=\"_self\">"._LEAGUE_TEAM_LIST_HIBERNATED."</a>";
		} else {
			$link = "<a href=\"modul_league.php?action=league_team_show&hib=0&project=".$_SESSION['project']."\" target=\"_self\">"._LEAGUE_TEAM_LIST_ACTIVE."</a>";
		}
	}
	echo "			<td colspan=\"5\">".$link."</td>\n";
	echo "		</tr>\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"120\">"._CMN_OPTIONS."</td>\n";
	echo "			<td width=\"45\" align=\"center\">ID</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_TEAM_NAME."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_TEAM_TAG."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_TEAM_STATUS."</td>\n";
	echo "		</tr>\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"120\">&nbsp;</td>\n";
	echo "			<td width=\"45\" align=\"center\""; if ($by == "id"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=id&ser=asc\"><img src=\"images/asc_"; if ($by == "id" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=id&ser=desc\"><img src=\"images/des_"; if ($by == "id" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo "			<td width=\"45\" align=\"center\""; if ($by == "name"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=name&ser=asc\"><img src=\"images/asc_"; if ($by == "name" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=name&ser=desc\"><img src=\"images/des_"; if ($by == "name" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo "			<td width=\"45\" align=\"center\""; if ($by == "tag"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=tag&ser=asc\"><img src=\"images/asc_"; if ($by == "tag" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_team_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=tag&ser=desc\"><img src=\"images/des_"; if ($by == "tag" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo "			<td align=\"left\">&nbsp;</td>\n";
	echo "		</tr>\n";
	$i=1;
	while ($ar_team = mysql_fetch_array($res_team)){
		// Color changes
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		
		// Open/Close
		if ($_GET['command'] == "open" && $_GET['tid'] == $ar_team['league_team_id']) {$_GET['cmd'] = "close";} else {$_GET['cmd'] = "open";}
		echo "	<tr "; if ($_GET['command'] == "open" && $_GET['tid'] == $ar_team['league_team_id']){ echo "style=\"background: #b3c3dd;\""; } else { echo " class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\""; } echo ">\n";
		echo "		<td width=\"120\" valign=\"middle\"><a name=\"".$i."\"></a>\n";
		echo "			<a href=\"modul_league.php?action=".$_GET['action']."&amp;command=".$_GET['cmd']."&amp;lid=".$_GET['lid']."&amp;tid=".$ar_team['league_team_id']."&amp;hib=".$_GET['hib']."&amp;project=".$_SESSION['project']."#".$i."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_OPEN."\" title=\""._CMN_OPEN."\"></a>\n";
						if (CheckPriv("groups_league_team_edit") == 1){echo "<a href=\"modul_league.php?action=league_team_edit&amp;lid=".$_GET['lid']."&amp;tid=".$ar_team['league_team_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\" title\""._CMN_EDIT."\"></a>";} else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";}
						if (CheckPriv("groups_league_team_del") == 1 && $_GET['hib'] == 0){echo "<a href=\"modul_league.php?action=league_team_del&amp;tid=".$ar_team['league_team_id']."&amp;msg=league_team_hibernate_check&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\" title=\""._CMN_DEL."\"></a>";}
		echo "		</td>\n";
		echo " 		<td align=\"left\" valign=\"top\">".$ar_team['league_team_id']."</td>\n";
		echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_team['league_team_name'])."</td>\n";
		echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_team['league_team_tag'])."</td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
						if (!empty($_GET['lid'])){
							// Pokud je nastavena nula u obou atributu tak se nemusi vybirat hraci, kteri se zucastni ligy
							if ($ar_team['league_league_team_sub_max_players'] == 0 && $ar_team['league_league_team_sub_min_players'] == 0){
								$team_status = "<span class=\"green\">"._LEAGUE_TEAM_STATUS_OK."</span>";
								$show_chosen_players = 0;
							// Pokud je v podtymu vice hracu nez kolik dovoluji pravidla ligy zobrazi se varovani
							} elseif ($ar_team['league_league_team_sub_max_players'] != 0 && $league_allowed_player_num > $ar_team['league_league_team_sub_max_players']){
								$team_status = "<span class=\"red\">"._LEAGUE_TEAM_STATUS_OVER."</span>";
								$show_chosen_players = 1;
							// Pokud je v podtymu mene hracu nez kolik dovoluji pravidla ligy zobrazi se varovani
							} elseif ($ar_team['league_league_team_sub_min_players'] != 0 && $league_allowed_player_num < $ar_team['league_league_team_sub_min_players']){
								$team_status = "<span class=\"red\">"._LEAGUE_TEAM_STATUS_LESS."</span>";
								$show_chosen_players = 1;
							// Pokud je nastaveno v lize pocet min a max hracu a podtym podminku splnuje zobrazi se OK
							} else {
								$team_status = "<span class=\"green\">"._LEAGUE_TEAM_STATUS_OK."</span>";
								$show_chosen_players = 1;
							}
							echo $team_status;
						}
		echo "		</td>\n";
		echo "	</tr>\n";
		if ($_GET['command'] == "open" & $_GET['tid'] == $ar_team['league_team_id']){
			if (!empty($_GET['lid'])){
				// Spocitame kolik je aktivnich hracu v teamu pro danou ligu 
				$league_allowed_player_id = explode("#",$ar_team['league_teams_sub_league_players'],-1);
				$league_allowed_player_num = count($league_allowed_player_id);
				echo ShowTeamShowPlayers($ar_team['league_team_id'],$ar_team['league_teams_sub_league_team_sub_id'],$ar_team['league_league_game_id'],2,$league_allowed_player_id);
			} else {
				// Pri zobrazeni vsech tymu nezavazne na tom v jake lize jsou prihlaseni ci jakou hru hraji
				$res_team_sub = mysql_query("SELECT league_team_sub_id, league_team_sub_game_id 
				FROM $db_league_teams_sub 
				WHERE league_team_sub_team_id = ".(integer)$ar_team['league_team_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				while($ar_team_sub = mysql_fetch_array($res_team_sub)){
					echo ShowTeamShowPlayers($ar_team['league_team_id'],$ar_team_sub['league_team_sub_id'],$ar_team_sub['league_team_sub_game_id'],1,0);
				}
			}
		}
		$i++;
	}
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		SHOW TEAM LIST - Show players in sub team
*		$game_id
*		$team_id
*		$team_sub_id
*		$league_id
*		$allowed_player_id
*		$mode	= 1 - league ID isn't known
*				= 2 - league ID is known
*
***********************************************************************************************************/
function ShowTeamShowPlayers($team_id,$team_sub_id,$game_id,$mode = 1,$allowed_player_id = 0){
	
	global $db_league_players,$db_admin,$db_admin_guids,$db_clan_games;
	
	if ($mode == 2){
		$admin_guids = "";
		$sub_team = "";
	} else {
		$res_game = mysql_query("SELECT clan_games_game FROM $db_clan_games WHERE clan_games_id = ".(integer)$game_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_game = mysql_fetch_array($res_game);
		$sub_team = "<tr style=\"background: #DCE3F1;\">\n";
		$sub_team .= "	<td colspan=\"5\" class=\"eden_title_middle\">Sub Team - ".$ar_game['clan_games_game']."</td>\n";
	 	$sub_team .= "</tr>\n";
	}
	$output = $sub_team;
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, a.admin_team_own_id, ag.admin_guid_guid, lp.league_player_id, lp.league_player_position_captain, lp.league_player_position_assistant, lp.league_player_position_player 
	FROM $db_league_players AS lp 
	JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_admin_guids AS ag ON ag.aid=lp.league_player_admin_id AND ag.admin_guid_game_id=".(integer)$game_id." 
	WHERE lp.league_player_team_id=".(integer)$team_id." AND lp.league_player_game_id=".(integer)$game_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res_player);
	$output .= "<tr style=\"background: #ff8080;\">\n";
	if ($num > 0){
		$output .= "	<td width=\"120\" valign=\"middle\" class=\"eden_title_middle\">ID</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\" class=\"eden_title_middle\">"._LEAGUE_PLAYER_NICK."</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\" class=\"eden_title_middle\">"._LEAGUE_PLAYER_POSITION."</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\" class=\"eden_title_middle\">"._LEAGUE_GUID."</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\" class=\"eden_title_middle\"></td>\n";
	} else {
		$output .= "	<td colspan=\"5\" class=\"eden_title_middle\">"._LEAGUE_TEAM_NO_PLAYER_IN_SUB."</td>\n";
	}
	$output .= "</tr>\n";
	$cislo = 0;
	while ($ar_player = mysql_fetch_array($res_player)){
		if ($cislo % 2 == 0){ $cat_class = "cat_level2_even";} else { $cat_class = "cat_level2_odd";}
		$output .= "<tr class=\"".$cat_class."\">\n";
		$output .= "	<td align=\"left\" valign=\"top\">".$ar_player['admin_id']."</td>\n";
		$output .= "	<td width=\"120\" valign=\"middle\">";
						if ($mode == 2){
							$output .= "<img src=\"./images/sys_"; 
							if (in_array($ar_player['league_player_id'],$allowed_player_id)){
								$output .= "yes"; $alt = _LEAGUE_PLAYER_PLAY;
							} else {
								$output .= "no"; $alt = _LEAGUE_PLAYER_NO_PLAY;
							} 
							$output .= ".gif\" width=\"15\" height=\"15\" alt=\"".$alt."\" title=\"".$alt."\"> "; 
						} 
						$output .= "<strong>".stripslashes($ar_player['admin_nick'])."</strong>";
		$output .= "	</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\">"; 
					if (LeagueCheckPrivileges("O",$ar_player['admin_id'],$team_id,"") == $team_id){$output .= _LEAGUE_PLAYER_POSITION_O; $comma = ", ";} else {$comma = "";}
					if (LeagueCheckPrivileges("C",$ar_player['admin_id'],$team_id,$team_sub_id) == 1){$output .= $comma._LEAGUE_PLAYER_POSITION_C;} 
					if (LeagueCheckPrivileges("A",$ar_player['admin_id'],$team_id,$team_sub_id) == 1){$output .= $comma._LEAGUE_PLAYER_POSITION_A;} 
					if (LeagueCheckPrivileges("P",$ar_player['admin_id'],$team_id,$team_sub_id) == 1){$output .= $comma._LEAGUE_PLAYER_POSITION_P;} 
		$output .= "	</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\">"; if ($ar_player['admin_guid_guid'] != ""){ $output .= stripslashes($ar_player['admin_guid_guid']);} else {$output .= "<span class=\"red\">"._LEAGUE_PLAYER_NO_GUID."</span>";} $output .= "</td>\n";
		$output .= "	<td align=\"left\" valign=\"top\"></td>\n";
		$output .= "</tr>\n";
		$cislo++;
	}
	
	return $output;
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU HRACU SPLNUJICICH PODMINKY LIGY
*		$_GET['id'] = Mod zobrazeni
*				prazdna - normalni (zobrazi se jen kapitanem povoleni hraci a jejich giudy atd)
*				id - normalni (zobrazi se jen kapitanem povoleni hraci -jen jejich giudy)
*				all - Zobrazi se i kapitanem nepovoleni hraci, jejich nicky atd
*				allid - Zobrazi se i kapitanem nepovoleni hraci - jen jejich GUIDY
*
***********************************************************************************************************/
function ListAllowedPlayers(){
	
	global $db_admin,$db_admin_guids,$db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_league_leagues,$db_league_players,$db_clan_games,$db_league_seasons_round_allowed_players;
	
	if ($_GET['mode'] != "league"){
		
		echo Menu();
		
		KillUse($_SESSION['loginid']);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	if ($_GET['lid'] == 0){
		echo "	<tr>\n";
		echo "		<td>"._LEAGUE_NO_LEAGUE_ID."</td>\n";
   		echo "	</tr>\n";
	} else {
		echo "	<tr>\n";
		if ($_GET['show'] != "id" && $_GET['show'] != "season_players_all_guid"){
			echo "		<td width=\"30\" valign=\"middle\" class=\"eden_title\">ID</td>\n";
			echo "		<td align=\"left\" valign=\"top\" class=\"eden_title\">"._LEAGUE_TEAM."</td>\n";
			echo "		<td align=\"left\" valign=\"top\" class=\"eden_title\">"._LEAGUE_PLAYER_NICK."</td>\n";
		}
		echo "		<td align=\"left\" valign=\"top\" class=\"eden_title\">"._LEAGUE_GUID."</td>\n";
		echo "	</tr>\n";
		//$msg = LeagueGenerateListAllowedPlayers((float)$_GET['lid'],(float)$_GET['sid'],(float)$_GET['rid']);
		switch ($_GET['show']){
	   		case "id":
	   			$colspan = 1;
				$res_round = mysql_query("
				SELECT league_season_round_allowed_player_guid 
				FROM $db_league_seasons_round_allowed_players 
				WHERE league_season_round_allowed_player_season_round_id=".(float)$_GET['rid']." 
				ORDER BY league_season_round_allowed_player_guid") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	   		break;
			case "season_players_all":
				$colspan = 4;
	   		break;
			case "season_players_all_guid":
	   			$colspan = 1;
	  		break;
			default:
	   			$colspan = 4;
				$res_round = mysql_query("
				SELECT a.admin_id, a.admin_nick, lt.league_team_id, lt.league_team_name, ll.league_league_id, ll.league_league_game_id, lsrap.league_season_round_allowed_player_guid 
				FROM $db_league_seasons_round_allowed_players AS lsrap 
				JOIN $db_league_leagues AS ll ON ll.league_league_id=lsrap.league_season_round_allowed_player_league_id 
				JOIN $db_league_teams AS lt ON lt.league_team_id=lsrap.league_season_round_allowed_player_team_id 
				JOIN $db_admin AS a ON a.admin_id=lsrap.league_season_round_allowed_player_admin_id 
				WHERE lsrap.league_season_round_allowed_player_season_round_id=".(float)$_GET['rid']." 
				ORDER BY lsrap.league_season_round_allowed_player_team_sub_id ASC, a.admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$_GET['show'] = "all";
		}
		
		$cislo = 0;
		// pro zobrazeni povolenych hracu
		if ($_GET['show'] == "id" || $_GET['show'] == "all"){
			while ($ar_round = mysql_fetch_array($res_round)){
				if ($cislo % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				if ($_GET['show'] != "id"){
					echo "		<td width=\"30\" align=\"right\" valign=\"top\">".$ar_round['admin_id']."</td>\n";
			   		echo "		<td width=\"150\" align=\"left\" valign=\"top\">".stripslashes($ar_round['league_team_name'])."</td>\n";
			   		echo "		<td valign=\"middle\"><strong>".stripslashes($ar_round['admin_nick'])."</strong></td>\n";
				}
				echo "		<td align=\"left\" valign=\"top\">"; if (empty($ar_round['league_season_round_allowed_player_guid'])){echo "<span class=\"red\">"._LEAGUE_PLAYER_NO_GUID."</span>";} else {echo stripslashes($ar_round['league_season_round_allowed_player_guid']);} echo "</td>\n";
				echo "	</tr>\n";
				$cislo++;
			}
			unset($ar_round);
		}
		// pro zobrazeni vsech hracu
		if ($_GET['show'] == "season_players_all" || $_GET['show'] == "season_players_all_guid"){
			$res_team = mysql_query("
			SELECT lt.league_team_id, lt.league_team_name, ltsl.league_teams_sub_league_team_sub_id, ltsl.league_teams_sub_league_league_id 
			FROM $db_league_teams_sub_leagues AS ltsl 
			JOIN $db_league_teams AS lt ON lt.league_team_id=ltsl.league_teams_sub_league_team_id 
			WHERE ltsl.league_teams_sub_league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_team = mysql_fetch_array($res_team)){
				$res_player = mysql_query("
				SELECT a.admin_id, a.admin_nick, ag.admin_guid_guid, lp.league_player_id 
				FROM $db_league_players AS lp 
				JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
				JOIN $db_admin_guids AS ag ON ag.aid=lp.league_player_admin_id AND ag.admin_guid_league_guid_id=".(integer)$ar_team['league_teams_sub_league_league_id']." AND ag.admin_guid_guid != '' 
				WHERE lp.league_player_team_sub_id=".(integer)$ar_team['league_teams_sub_league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i=1;
				while ($ar_player = mysql_fetch_array($res_player)){
					if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
					echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
					if ($_GET['show'] != "season_players_all_guid"){
						echo "		<td width=\"30\" align=\"right\" valign=\"top\">".$ar_player['admin_id']."</td>\n";
				   		echo "		<td width=\"150\" align=\"left\" valign=\"top\">".stripslashes($ar_team['league_team_name'])."</td>\n";
				   		echo "		<td valign=\"middle\"><strong>".stripslashes($ar_player['admin_nick'])."</strong></td>\n";
					}
					echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_player['admin_guid_guid'])."</td>\n";
					echo "	</tr>\n";
					$i++;
				}
			}
		}
	}
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		EDITACE TEAMU
*
***********************************************************************************************************/
function AddTeam(){
	
	global $db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_league_leagues,$db_league_players;
	global $db_admin,$db_admin_contact,$db_clan_games,$db_country;
	global $eden_cfg;
	global $url_flags;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_team_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_GET['action'] == "league_team_edit" || $_GET['action'] == "league_team_del"){
		$res_team = mysql_query("SELECT * FROM $db_league_teams WHERE league_team_id=".(integer)$_GET['tid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_team = mysql_fetch_array($res_team);
	}
	
	if ($_GET['action'] == "league_team_edit"){
		$button_submit = _CMN_EDIT;
	} elseif ($_GET['action'] == "league_team_del"){
		$button_submit = _CMN_HIBERNATE;
	} else {
		$button_submit = _CMN_ADD;
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action=".$_GET['action']."&tid=".$_GET['tid']."\" method=\"post\" name=\"forma\" enctype=\"multipart/form-data\"><strong>"._LEAGUE_TEAM_NAME."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_name\" size=\"60\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_name'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_TAG."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_tag\" size=\"30\" maxlength=\"20\" value=\"".stripslashes($ar_team['league_team_tag'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_COUNTRY."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<select name=\"league_team_country\">\n";
							$res_country = mysql_query("SELECT country_id, country_name, country_shortname FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while ($ar_country = mysql_fetch_array($res_country)){
								echo "<option value=\"".$ar_country['country_id']."\" "; if ($ar_team['league_team_country_id'] == $ar_country['country_id']) {echo "selected=\"selected\"";}  echo ">".$ar_country['country_name']."</option>\n";
							}
	echo "				</select>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_WEB."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_web\" size=\"60\" maxlength=\"250\" value=\"".stripslashes($ar_team['league_team_web'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_IRC."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_irc\" size=\"20\" maxlength=\"40\" value=\"".stripslashes($ar_team['league_team_irc'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_MOTTO."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_motto\" size=\"60\" maxlength=\"255\" value=\"".stripslashes($ar_team['league_team_motto'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_SRV1."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_server1\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server1'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_SRV2."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_server2\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server2'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_SRV3."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_server3\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server3'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_SRV4."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_server4\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server4'])."\"></td>\n";
	echo "		</tr>";
   	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_PASS."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"league_team_pass\" size=\"10\" maxlength=\"8\" value=\"".$ar_team['league_team_pass']."\"></td>\n";
	echo "		</tr>\n";
	/*
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_AWARDS."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><textarea cols=\"50\" rows=\"5\" name=\"oceneni\">".$ar['oceneni']."</textarea></td>\n";
	echo "		</tr>\n";
	*/
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_COMM."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><textarea cols=\"50\" rows=\"5\" name=\"league_team_comment\">".$ar_team['league_team_comment']."</textarea></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._LEAGUE_TEAM_SUBS."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\">&nbsp;</td>\n";
	echo "		</tr>\n";
	$res_team_sub = mysql_query("
	SELECT g.clan_games_game, lts.league_team_sub_id 
	FROM $db_league_teams_sub AS lts 
	JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
	WHERE league_team_sub_team_id=".(integer)$ar_team['league_team_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar_team_sub = mysql_fetch_array($res_team_sub)){
		echo "		<tr>\n";
		echo "			<td align=\"right\" valign=\"top\" width=\"200\"><span style=\"color:#009900;font-weight:bold;\">".stripslashes($ar_team_sub['clan_games_game'])."</span></td>\n";
		echo "			<td align=\"left\" valign=\"top\" style=\"background-color:#EBEBEC\">\n";
		echo "				<strong>"._LEAGUE_TEAM_PLAYERS."</strong>\n";
		echo "				<table width=\"638\" cellspacing=\"2\" cellpadding=\"2\">\n";
   		echo "					<tr class=\"popisky\">\n";
   	   	echo "						<td width=\"30\" class=\"eden_title\">ID</td>\n";
		echo "						<td width=\"30\" class=\"eden_title\">"._LEAGUE_TEAM_COUNTRY."</td>\n";
		echo "						<td width=\"200\" class=\"eden_title\">"._LEAGUE_TEAM_PLAYER_NICK."</td>\n";
		echo "						<td width=\"200\" class=\"eden_title\">"._LEAGUE_TEAM_PLAYER_POSITION."</td>\n";
   		echo "						<td class=\"eden_title\">"._LEAGUE_TEAM_PLAYERS_OPTIONS."</td>\n";
		echo "					</tr>\n";
								$res_player = mysql_query("
								SELECT a.admin_team_own_id, lp.league_player_position_captain, lp.league_player_position_assistant, lp.league_player_position_player, a.admin_id, a.admin_nick, c.country_shortname 
								FROM $db_league_players AS lp 
								JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
								JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
								JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
								WHERE league_player_team_sub_id=".(float)$ar_team_sub['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								while ($ar_player = mysql_fetch_array($res_player)){
									if ($ar_player['league_team_own_id'] == $ar_team['league_team_id']){$player_position_owner = _LEAGUE_PLAYER_POSITION_O.", ";} else {$player_position_owner = "";}
									if ($ar_player['league_player_position_captain'] == 1){$player_position = $player_position_owner._LEAGUE_PLAYER_POSITION_C;}
									if ($ar_player['league_player_position_assistant'] == 1){$player_position = $player_position_owner._LEAGUE_PLAYER_POSITION_A;}
									if ($ar_player['league_player_position_player'] == 1){$player_position = $player_position_owner._LEAGUE_PLAYER_POSITION_P;}
									echo "	<tr>\n";
									echo "		<td width=\"30\" align=\"right\">".$ar_player['admin_id']."</td>\n";
									echo "		<td width=\"30\" align=\"center\"><img src=\"".$url_flags."".$ar_player['country_shortname'].".gif\"></td>\n";
									echo "		<td width=\"200\">".stripslashes($ar_player['admin_nick'])."</td>\n";
									echo "		<td width=\"200\">".$player_position."</td>\n";
									echo "		<td>&nbsp;</td>\n";
									echo "	</tr>\n";
								}
		echo "					<tr>\n";
   	   	echo "						<td colspan=\"4\">&nbsp;</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "				<strong>"._LEAGUE_TEAM_LEAGUES."</strong>\n";
		echo "				<table width=\"638\" cellspacing=\"2\" cellpadding=\"2\">\n";
   		echo "					<tr class=\"popisky\">\n";
   	   	echo "						<td width=\"30\" class=\"eden_title\">ID</td>\n";
		echo "						<td width=\"200\" class=\"eden_title\">"._LEAGUE_LEAGUE_NAME."</td>\n";
   		echo "						<td class=\"eden_title\">"._LEAGUE_TEAM_PLAYERS_OPTIONS."</td>\n";
		echo "					</tr>\n";
								$res_league = mysql_query("
								SELECT l.league_league_id, l.league_league_name 
								FROM $db_league_teams_sub_leagues AS ltsl 
								JOIN $db_league_leagues AS l ON l.league_league_id=ltsl.league_teams_sub_league_league_id 
								WHERE league_teams_sub_league_team_sub_id=".(integer)$ar_team_sub['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								while ($ar_league = mysql_fetch_array($res_league)){
									echo "	<tr>\n";
									echo "		<td width=\"30\" align=\"right\">".$ar_league['league_league_id']."</td>\n";
									echo "		<td width=\"200\">".stripslashes($ar_league['league_league_name'])."</td>\n";
									echo "		<td>&nbsp;</td>\n";
									echo "	</tr>\n";
								}
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
	}
	echo "			<tr>\n";
	echo "				<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"".$_GET['action']."\">\n";
	echo "					<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "					<input type=\"hidden\" name=\"ltid\" value=\"".$_GET['tid']."\">\n";
	echo "					<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "					<input type=\"submit\" value=\"".$button_submit."\" class=\"eden_button\">\n";
	echo "					</form>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}
/***********************************************************************************************************
*
*		MAZANI TEAMU
*
***********************************************************************************************************/
function DeleteTeam(){
	
	global $db_league_teams;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_team_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	$res = mysql_query("SELECT * FROM $db_league_teams WHERE id=".(integer)$_GET['tid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td class=\"eden_title\">ID</td>\n";
	echo "		<td class=\"eden_title\">"._LEAGUE_TEAM_TAG."</td>\n";
	echo "		<td class=\"eden_title\">"._LEAGUE_TEAM_NAZEV."</td>\n";
	echo "	</tr>\n";
	echo "		<tr>\n";
	echo "				<td>".$ar['id']."</td>\n";
	echo "				<td>".$ar['tag']."</td>\n";
	echo "				<td>".$ar['nazev']."</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._LEAGUE_DEL_TQ."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<td width=\"50\" valign=\"top\">\n";
	echo "		<form action=\"modul_league.php\" method=\"post\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "		<input type=\"hidden\" name=\"action\" value=\"team_del\">\n";
	echo "		<input type=\"hidden\" name=\"id\" value=\"".$_GET['tid']."\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "	<td width=\"800\" valign=\"top\">\n";
	echo "		<form action=\"modul_league.php\" method=\"post\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "		<input type=\"hidden\" name=\"action\" value=\"team_del\">\n";
	echo "		<input type=\"hidden\" name=\"id\" value=\"".$_GET['tid']."\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "		<input type=\"hidden\" name=\"info\" value=\"".$info."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU HRACU
*
***********************************************************************************************************/
function ShowPlayers(){
	
	global $db_admin,$db_admin_guids,$db_league_leagues,$db_league_players,$db_league_players_leagues,$db_league_players_bans,$db_clan_games,$db_league_teams_sub_leagues;
	
	$ser	= $_GET['ser'];
	$by		= $_GET['by'];
	$page	= $_GET['page'];
	$search_by_league = $_POST['search_by_league'];
	$search_banned = $_POST['search_banned'];
	
	/* Nasteveni promennych pro dalsi pouziti ve skriptu */
	if ($search_by_league != 0){$_GET['lid'] = (integer)$search_by_league;} elseif ($ser == "asc" || $ser == "desc"){$search_by_league = $_GET['lid'];}
	if ($ser == "asc" || $ser == "desc"){$search_banned = $_GET['sb'];}
	if ($search_banned == 1){$search_banned_left = "";} else {$search_banned_left = " LEFT ";}
	
	switch ($by){
		case "aid":
			$order_by = " ORDER BY a.admin_id ";
		break;
		case "pid":
			$order_by = " ORDER BY lp.league_player_id ";
		break;
		case "guid":
			$order_by = " ORDER BY ag.admin_guid_guid ";
		break;
		case "game":
			$order_by = " ORDER BY lp.league_player_game_id ";
		break;
		case "name":
			$order_by = " ORDER BY a.admin_nick ";
		break;
		default:
			$order_by = " ORDER BY a.admin_nick ";
			$by = "name";
			$ser = "asc";
		break;
	}
	
	switch ($ser){
		case "asc":
			$order = " ASC ";
		break;
		case "desc":
			$order = " DESC ";
		break;
	}
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	/* Pokud si nechame zobrazit hrace v dane lize $_GET['lid'] nesmi byt prazdne */
	/* Paklize je prazdne zobrazi se vsechni hraci obecne bez prislusnosti k lize - pokud neni vybrano jinak pomoci nabidky */
	
	/****************************************/
	/*	POCITANI STRANEK LOGIKA - START	1/3	*/
	/****************************************/
	//Timto nastavime pocet prispevku na strance
	if (empty($page)) {$page = 1;} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	$hits = 50; //Zde se nastavuje pocet prispevku
	
	$sp = ($page-1)*$hits;
	$ep = ($page-1)*$hits+$hits;
	
	echo $sp."-".$ep;
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	
	/****************************************/
	/*	POCITANI STRANEK LOGIKA - END 1/3	*/
	/****************************************/
	if (!empty($_GET['lid'])){
		/* Pokud je vybrano zobrazeni podle danych kriterii, zobrazi se hraci v dane lize */
		if ($search_by_league != 0){
			$res_league = mysql_query("SELECT league_league_mode FROM $db_league_leagues WHERE league_league_id=".(integer)$search_by_league) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_league = mysql_fetch_array($res_league);
			/* Pro teamove ligy a ligy jednotlivcu musime upravit dotaz */
			if ($ar_league['league_league_mode'] == 1){
				/* TEAM */
				$sub_query = "	JOIN $db_league_teams_sub_leagues AS ltsl ON ltsl.league_teams_sub_league_team_sub_id=lp.league_player_team_sub_id 
								JOIN $db_admin_guids AS ag ON ag.aid=a.admin_id AND ag.admin_guid_game_id=lp.league_player_game_id 
								JOIN $db_league_leagues AS l ON l.league_league_id=ltsl.league_teams_sub_league_league_id ";
				$sub_where = "	WHERE ltsl.league_teams_sub_league_league_id=".(integer)$_GET['lid']." ";
			} else {
				/* 1on1 */
				$sub_query = "	JOIN $db_league_players_leagues AS lpl ON lpl.league_player_league_player_id=lp.league_player_id 
								JOIN $db_admin_guids AS ag ON ag.aid=a.admin_id AND ag.admin_guid_game_id=lpl.league_player_league_game_id 
								JOIN $db_league_leagues AS l ON l.league_league_id=lpl.league_player_league_league_id ";
				$sub_where = "	WHERE lpl.league_player_league_league_id=".(integer)$_GET['lid']." ";
			}
			$query = "SELECT SQL_CALC_FOUND_ROWS a.admin_id, a.admin_nick, lp.league_player_id, lp.league_player_game_id, lp.league_player_team_sub_id, ag.admin_guid_guid, l.league_league_id, l.league_league_name, l.league_league_game_id, lpb.league_player_ban_status 
			FROM $db_league_players AS lp 
			JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
			$sub_query 
			$search_banned_left JOIN $db_league_players_bans AS lpb ON lpb.league_player_ban_player_id=lp.league_player_id AND lpb.league_player_ban_league_id=l.league_league_id AND lpb.league_player_ban_status=1
			$sub_where
			GROUP BY lp.league_player_id 
			$order_by $order $limit";
		} else {
			$query = "SELECT SQL_CALC_FOUND_ROWS a.admin_id, a.admin_nick, lp.league_player_id, lp.league_player_game_id, lp.league_player_team_sub_id, ag.admin_guid_guid, l.league_league_id, l.league_league_name, l.league_league_game_id, lpb.league_player_ban_status 
			FROM $db_league_players AS lp 
			JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
			JOIN $db_league_players_leagues AS lpl ON lpl.league_player_league_player_id=lp.league_player_id 
			JOIN $db_admin_guids AS ag ON ag.aid=a.admin_id AND ag.admin_guid_game_id=lpl.league_player_league_game_id 
			JOIN $db_league_leagues AS l ON l.league_league_id=lpl.league_player_league_league_id 
			$search_banned_left JOIN $db_league_players_bans AS lpb ON lpb.league_player_ban_player_id=lp.league_player_id AND lpb.league_player_ban_league_id=l.league_league_id AND lpb.league_player_ban_status=1 
			WHERE lpl.league_player_league_league_id=".(integer)$_GET['lid']." 
			GROUP BY lp.league_player_id 
			$order_by $order $limit";
		}
		$player_column_game_1 = "";
		$player_column_game_2 = "";
		$player_column_leagues_1 = "";
		$player_column_leagues_2 = "";
		$player_column_status_1 = "<td align=\"left\">"._LEAGUE_PLAYER_STATUS."</td>\n";
		$player_column_status_2 = "<td align=\"left\">&nbsp;</td>\n";
	} else {
		$query = "SELECT SQL_CALC_FOUND_ROWS a.admin_id, a.admin_nick, lp.league_player_id, lp.league_player_game_id, lp.league_player_team_sub_id 
		FROM $db_league_players AS lp 
		JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
		$search_banned_left JOIN $db_league_players_bans AS lpb ON lpb.league_player_ban_player_id=lp.league_player_id AND lpb.league_player_ban_status=1 
		GROUP BY lp.league_player_id 
		$order_by $order $limit";
		$player_column_game_1 = "<td align=\"left\">"._LEAGUE_PLAYER_GAME."</td>\n";
		$player_column_game_2 = "<td align=\"center\""; if ($by == "game"){$player_column_game_2 .= "bgcolor=\"#FFDEDF\"";} $player_column_game_2 .= "><a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=game&ser=asc\"><img src=\"images/asc_"; if ($by == "game" && $ser == "asc"){$player_column_game_2 .= "1";} else {$player_column_game_2 .= "0";} $player_column_game_2 .= ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=game&ser=desc\"><img src=\"images/des_"; if ($by == "game" && $ser == "desc"){$player_column_game_2 .= "1";} else {$player_column_game_2 .= "0";} $player_column_game_2 .= ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
		$player_column_leagues_1 = "<td align=\"left\">"._LEAGUE_PLAYER_LEAGUES."</td>\n";
		$player_column_leagues_2 = "<td align=\"left\">&nbsp;</td>\n";
		$player_column_status_1 = "";
		$player_column_status_2 = "";
	}
	/* Dotaz na hrace */
	$res_players = mysql_query("$query") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_players = mysql_fetch_array($res_players);
	
	/* Pomoci SQL_CALC_FOUND_ROWS v dotazu jsme si vypocitali kolik radku bylo v dotazu vybrano a pomoci MySQL funkce FOUND_ROWS() si pocet nechame zobrazit */
	$res_num = mysql_query("SELECT FOUND_ROWS()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_num = mysql_fetch_array($res_num);
		
	/****************************************/
	/*	POCITANI STRANEK LOGIKA - START	2/3	*/
	/****************************************/
	$stw2 = ($ar_num[0]/$hits);
	$stw2 = (integer)$stw2;
	if ($ar_num[0]%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	/****************************************/
	/*	POCITANI STRANEK LOGIKA - END 2/3	*/
	/****************************************/
	
	/* Pomoci teto promenne se nam v menu zobrazi spravny nazev ligy */
	$_GET['lname'] = $ar_players['league_league_name'];
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\"><form action=\"modul_league.php?action=league_players_show&amp;from=search&amp;show_status=user\" enctype=\"multipart/form-data\" method=\"post\">\n";
	echo "	<strong>"._LEAGUE_PLAYER_SHOW_BY_LEAGUE.":</strong>\n";
	echo "	<select name=\"search_by_league\">\n";
	$res_league = mysql_query("SELECT league_league_id, league_league_name FROM $db_league_leagues ORDER BY league_league_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "		<option value=\"0\">"._LEAGUE_PLAYER_SHOW_BY_LEAGUE_SELECT."</option>\n";
	while ($ar_league = mysql_fetch_array($res_league)){
		echo "		<option value=\"".$ar_league['league_league_id']."\" "; if ($search_by_league == $ar_league['league_league_id']){ echo "selected=\"selected\"";} echo ">".$ar_league['league_league_name']."</option>\n";
	}
	echo "	</select>\n";
	echo "	<strong>"._LEAGUE_PLAYER_SHOW_ONLY_BANNED.":</strong>\n";
	echo "	<input type=\"checkbox\" name=\"search_banned\" value=\"1\" "; if ($search_banned == 1){ echo "checked=\"checked\"";} echo ">";
	echo "	<input type=\"submit\" value=\""._LEAGUE_PLAYER_SHOW."\" class=\"eden_button\">\n";
	echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "	</form></td>\n";
	echo "</tr>";
	echo "</table>";
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"60\">"._CMN_OPTIONS."</td>\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_ADMIN_ID."</td>\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_PLAYER_ID."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_NICK."</td>\n";
	echo 			$player_column_game_1;
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_GUID."</td>\n";
	echo			$player_column_leagues_1;
	echo 			$player_column_status_1;
	echo "		</tr>\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"60\">&nbsp;</td>\n";
	echo "			<td width=\"45\" align=\"center\""; if ($by == "aid"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=aid&ser=asc\"><img src=\"images/asc_"; if ($by == "aid" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=aid&ser=desc\"><img src=\"images/des_"; if ($by == "aid" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo "			<td width=\"45\" align=\"center\""; if ($by == "pid"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=pid&ser=asc\"><img src=\"images/asc_"; if ($by == "pid" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=pid&ser=desc\"><img src=\"images/des_"; if ($by == "pid" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo "			<td align=\"center\""; if ($by == "name"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=name&ser=asc\"><img src=\"images/asc_"; if ($by == "name" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=name&ser=desc\"><img src=\"images/des_"; if ($by == "name" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo 			$player_column_game_2;
	echo "			<td width=\"45\" align=\"center\""; if ($by == "guid"){echo "bgcolor=\"#FFDEDF\"";} echo "><a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=guid&ser=asc\"><img src=\"images/asc_"; if ($by == "guid" && $ser == "asc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_ASC."\" title=\""._CMN_ORDER_ASC."\"></a>&nbsp;&nbsp;<a href=\"modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&by=guid&ser=desc\"><img src=\"images/des_"; if ($by == "guid" && $ser == "desc"){echo "1";} else {echo "0";} echo ".gif\" width=\"15\" height=\"12\" border=\"0\" alt=\""._CMN_ORDER_DESC."\" title=\""._CMN_ORDER_DESC."\"></a></td>\n";
	echo			$player_column_leagues_2;
	echo			$player_column_status_2;
	echo "		</tr>\n";
	if ($ar_num[0] >= 1){ /* Zamezime chybove hlasce u mysql_data_seek */ 
		$i=1;
		mysql_data_seek($res_players, 0);
		while ($ar_players = mysql_fetch_array($res_players)){
			
			/* Pokud je zobrazen seznam hracu v dane lize */
			if (!empty($_GET['lid'])){
				$player_guid = $ar_players['admin_guid_guid'];
				/* Pokud ma hrac BAN v dane lize zobrazi se vystraha */
				if ($ar_players['league_player_ban_status'] == 1 || $ar_players['league_player_ban_status'] == 2){
					$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_BAN."</span>";
				/* Pokud ma hrac guid k dane hre je vse OK */
				} elseif ($ar_players['admin_guid_guid'] != ""){
					$player_status = "<span class=\"green\">"._LEAGUE_PLAYER_STATUS_OK."</span>";
				/* Pokud hrac nema nastaven GUID k dane hre - zobrazi se ze neni zpusobily hru hrat */
				} else {
					$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_ER."</span>";
				}
				$player_status_all = "<td align=\"left\" valign=\"top\">".$player_status."</td>\n";
			/* Pokud je zobrazen jen seznam hracu bez prislusnosti k lize */
			} else {
				/* Nacteni hry */
				$res_player_game = mysql_query("SELECT clan_games_game FROM $db_clan_games WHERE clan_games_id=".(integer)$ar_players['league_player_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_player_game = mysql_fetch_array($res_player_game);
				$player_game = "<td align=\"left\" valign=\"top\">".$ar_player_game['clan_games_game']."</td>\n";
				
				/* Nacteni GUIDu ke kazde hre */
				$res_player_guid = mysql_query("SELECT admin_guid_guid FROM $db_admin_guids WHERE aid=".(integer)$ar_players['admin_id']." AND admin_guid_game_id=".(integer)$ar_players['league_player_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_player_guid = mysql_fetch_array($res_player_guid);
				$player_guid = $ar_player_guid['admin_guid_guid']."<br>";
				
				/* Nacteni lig jednotlivcu ke kazde hre */
				$res_player_league = mysql_query("SELECT l.league_league_id, l.league_league_name, l.league_league_mode, lpb.league_player_ban_status 
				FROM $db_league_players_leagues as lpl 
				JOIN $db_league_leagues AS l ON l.league_league_id=lpl.league_player_league_league_id 
				$search_banned_left JOIN $db_league_players_bans as lpb ON lpb.league_player_ban_league_id=l.league_league_id AND lpb.league_player_ban_player_id=".(integer)$ar_players['league_player_id']." AND lpb.league_player_ban_status=1 
				WHERE lpl.league_player_league_player_id=".(integer)$ar_players['league_player_id']." 
				GROUP BY l.league_league_id 
				ORDER BY l.league_league_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				/* Nacteni tymovych lig ke kazde hre */
				$res_player_team_league = mysql_query("
				SELECT l.league_league_id, l.league_league_name, lpb.league_player_ban_status 
				FROM $db_league_teams_sub_leagues AS ltsl 
				JOIN $db_league_leagues AS l ON l.league_league_id=ltsl.league_teams_sub_league_league_id 
				$search_banned_left JOIN $db_league_players_bans as lpb ON lpb.league_player_ban_league_id=l.league_league_id AND lpb.league_player_ban_player_id=".(integer)$ar_players['league_player_id']." AND lpb.league_player_ban_status=1 
				WHERE ltsl.league_teams_sub_league_team_sub_id=".(integer)$ar_players['league_player_team_sub_id']." 
				GROUP BY l.league_league_id 
				ORDER BY l.league_league_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				$player_league = "<td align=\"left\" valign=\"top\">";
				while ($ar_player_league = mysql_fetch_array($res_player_league)){
					/* Pokud ma hrac BAN v dane lize zobrazi se vystraha */
					if ($ar_player_league['league_player_ban_status'] == 1){
						$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_BAN_SHORT."</span>";
					/* Pokud ma hrac guid k dane hre je vse OK */
					} elseif ($ar_player_guid['admin_guid_guid'] != ""){
						$player_status = "<span class=\"green\">"._LEAGUE_PLAYER_STATUS_OK_SHORT."</span>";
					/* Pokud hrac nema nastaven GUID k dane hre - zobrazi se ze neni zpusobily hru hrat */
					} else {
						$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_ER_SHORT."</span>";
					}
					$player_league .= "<img src=\"./images/sys_player.gif\" alt=\""._LEAGUE_MODE_SINGLE."\" title=\""._LEAGUE_MODE_SINGLE."\" width=\"12\" height=\"12\"> ";
					$player_league .= "ID: ".$ar_player_league['league_league_id']." - ".$ar_player_league['league_league_name']." - ".$player_status."<br>";
				}
				while ($ar_player_team_league = mysql_fetch_array($res_player_team_league)){
					/* Pokud ma hrac BAN v dane lize zobrazi se vystraha */
					if ($ar_player_team_league['league_player_ban_status'] == 1){
						$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_BAN_SHORT."</span>";
					/* Pokud ma hrac guid k dane hre je vse OK */
					} elseif ($ar_player_guid['admin_guid_guid'] != ""){
						$player_status = "<span class=\"green\">"._LEAGUE_PLAYER_STATUS_OK_SHORT."</span>";
					/* Pokud hrac nema nastaven GUID k dane hre - zobrazi se ze neni zpusobily hru hrat */
					} else {
						$player_status = "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_ER_SHORT."</span>";
					}
					$player_league .= "<img src=\"./images/sys_team.gif\" alt=\""._LEAGUE_MODE_TEAM."\" title=\""._LEAGUE_MODE_TEAM."\" width=\"12\" height=\"12\"> ";
					$player_league .= "ID: ".$ar_player_team_league['league_league_id']." - ".$ar_player_team_league['league_league_name']." - ".$player_status."<br>";
				}
				$player_league .= "</td>\n";
				
				$player_status_all = "";
			}
			
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			
			/* Zkontrolujeme zda ma hrac v dane lize BAN a by vysledku priradime spravnou hodnotu promenne */
			if ($ar_players['league_player_ban_status'] == 0 || $ar_players['league_player_ban_status'] == ""){
				$player_ban_action = "league_player_ban_add"; 
				$ban_title = _CMN_BAN_ADD;
			} else {
				$player_ban_action = "league_player_ban_edit";
				$ban_title = _CMN_BAN_EDIT;
			}
			echo "	<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "		<td width=\"60\" valign=\"middle\"><a name=\"".$i."\"></a>\n";
		   				if (CheckPriv("groups_league_team_edit") == 1){echo "<a href=\"modul_league.php?action=league_player_edit&amp;lid=".$_GET['lid']."&amp;pid=".$ar_players['league_player_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\" title=\""._CMN_EDIT."\"></a>";} else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";}
		   				if (CheckPriv("groups_league_team_del") == 1){echo "<a href=\"modul_league.php?action=".$player_ban_action."&amp;lid=".$_GET['lid']."&amp;pid=".$ar_players['league_player_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_ban.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"".$ban_title."\" title=\"".$ban_title."\"></a>";} else { echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";}
		   	echo "		</td>\n";
			echo " 		<td align=\"left\" valign=\"top\">".$ar_players['admin_id']."</td>\n";
			echo " 		<td align=\"left\" valign=\"top\">".$ar_players['league_player_id']."</td>\n";
			echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_players['admin_nick'])."</td>\n";
			echo 		$player_game;
			echo "		<td align=\"left\" valign=\"top\">".$player_guid."</td>\n";
			echo		$player_league;
			echo 		$player_status_all;
			echo "	</tr>\n";
			$i++;
		}
	}
	echo "</table>\n";
	/************************************************************************************************************/
	/*	POCITANI STRANEK - START 3/3																			*/
	/*	Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima	*/
	/************************************************************************************************************/
	if ($stw2 > 1){ 
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr><td height=\"30\">";
		echo _CMN_SELECTPAGE; 
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong>";
			} else {
				echo " <a href=\"modul_league.php?action=league_players_show&amp;lid=".$_GET['lid']."&amp;by=".$_GET['by']."&amp;ser=".$ser."&amp;page=".$i."&amp;sb=".$search_banned."&amp;project=".$_SESSION['project']."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1){echo "<center><a href=\"modul_league.php?action=league_players_show&amp;lid=".$_GET['lid']."&amp;by=".$_GET['by']."&amp;ser=".$ser."&amp;page=".$pp."&amp;sb=".$search_banned."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a>";} else { echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2){echo _CMN_NEXT;} else {echo "<a href=\"modul_league.php?action=league_players_show&amp;lid=".$_GET['lid']."&amp;by=".$_GET['by']."&amp;ser=".$ser."&amp;page=".$np."&amp;sb=".$search_banned."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a></center>";}
		echo "</td></tr></table>";
	}
	/************************************************************************************************************/
	/*	POCITANI STRANEK - END 3/3																				*/
	/************************************************************************************************************/
}
/***********************************************************************************************************
*
*		MAZANI HRACU
*
***********************************************************************************************************/
function DeletePlayer(){
	
	global $db_admin,$db_admin_guids,$db_league_leagues,$db_league_players,$db_league_players_leagues,$db_clan_games;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_player_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, ag.admin_guid_guid, lp.league_player_id  
	FROM $db_league_players AS lp 
	LEFT JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_league_players_leagues AS lpl ON lpl.league_player_league_player_id=lp.league_player_id 
	LEFT JOIN $db_admin_guids AS ag ON ag.aid=a.admin_id AND ag.admin_guid_game_id=lpl.league_player_league_game_id 
	WHERE lp.league_player_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_ADMIN_ID."</td>\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_PLAYER_ID."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_NICK."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_GUID."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_LEAGUES."</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo " 		<td align=\"left\" valign=\"top\">".$ar_player['admin_id']."</td>\n";
	echo " 		<td align=\"left\" valign=\"top\">".$ar_player['league_player_id']."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_player['admin_nick'])."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">".$ar_player['admin_guid_guid']."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	$res_player_league = mysql_query("SELECT ll.league_league_name 
	FROM $db_league_players_leagues AS lpl 
	JOIN $db_league_leagues AS ll ON ll.league_league_id=lpl.league_player_league_league_id 
	WHERE lpl.league_player_league_player_id=".(integer)$ar_player['league_player_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar_player_league = mysql_fetch_array($res_player_league)){
		echo $ar_player_league['league_league_name']."<br>";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._LEAGUE_PLAYER_DEL_CHECK."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<td width=\"50\" valign=\"top\">\n";
	echo "		<form action=\"sys_save.php?action=league_player_del&amp;lid=".$_GET['lid']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "		<input type=\"hidden\" name=\"pid\" value=\"".$ar_player['league_player_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "	<td width=\"800\" valign=\"top\">\n";
	echo "		<form action=\"modul_league.php?action=league_players_show&amp;lid=".$_GET['lid']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		ZABANOVANI HRACE
*
***********************************************************************************************************/
function BanPlayer(){
	
	global $db_admin,$db_admin_guids,$db_league_leagues,$db_league_players,$db_league_players_leagues,$db_clan_games,$db_league_players_bans;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_player_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, ag.admin_guid_guid, lp.league_player_id, lpb.league_player_ban_id, lpb.league_player_ban_date_from, lpb.league_player_ban_date_to, lpb.league_player_ban_reason  
	FROM $db_league_players AS lp 
	JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_league_players_bans AS lpb ON lpb.league_player_ban_player_id=lp.league_player_id AND lpb.league_player_ban_status=1 
	LEFT JOIN $db_league_players_leagues AS lpl ON lpl.league_player_league_player_id=lp.league_player_id 
	LEFT JOIN $db_admin_guids AS ag ON ag.aid=a.admin_id AND ag.admin_guid_game_id=lpl.league_player_league_game_id 
	WHERE lp.league_player_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	
	echo Menu();
	
	if ($_GET['action'] == "league_player_ban_add"){
		$dateon = formatTimeS(time());
		$ban_from = $dateon[1].".".$dateon[2].".".$dateon[3];
		$ban_to = $dateon[1].".".$dateon[2].".".$dateon[3];
		$player_ban_check = _LEAGUE_PLAYER_BAN_CHECK_ADD;
		$ban_reason = "";
	} else {
		$dateon = $ar_player['league_player_ban_date_from'];
		$ban_from = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
		$dateon = $ar_player['league_player_ban_date_to'];
		$ban_to = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
		$player_ban_check = _LEAGUE_PLAYER_BAN_CHECK_EDIT;
		$ban_reason = $ar_player['league_player_ban_reason'];
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr class=\"popisky\">\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_ADMIN_ID."</td>\n";
	echo "			<td width=\"45\" align=\"center\">"._LEAGUE_PLAYER_ID."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_NICK."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_GUID."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_LEAGUES."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_BAN_DATE_FROM."</td>\n";
	echo "			<td align=\"left\">"._LEAGUE_PLAYER_BAN_DATE_TO."</td>\n";
	echo "		</tr>\n";
	echo "	<tr>\n";
	echo " 		<td align=\"left\" valign=\"top\">".$ar_player['admin_id']."</td>\n";
	echo " 		<td align=\"left\" valign=\"top\">".$ar_player['league_player_id']."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">".stripslashes($ar_player['admin_nick'])."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">".$ar_player['admin_guid_guid']."</td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "		<form action=\"sys_save.php?action=".$_GET['action']."&amp;lid=".$_GET['lid']."&amp;project=".$_SESSION['project']."\" method=\"post\" name=\"form1\">\n";
					$res_player_league = mysql_query("SELECT ll.league_league_id, ll.league_league_name 
					FROM $db_league_players_leagues AS lpl 
					LEFT JOIN $db_league_leagues AS ll ON ll.league_league_id=lpl.league_player_league_league_id 
					WHERE lpl.league_player_league_player_id=".(integer)$ar_player['league_player_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					echo "<select name=\"league\">";
					/* Zobrazi se jen pri EDIT */
					if ($_GET['action'] == "league_player_ban_edit"){
						echo "<option value=\"unban\">"._LEAGUE_PLAYER_BAN_NO."</option>";
						echo "<option value=\"unban_all\">"._LEAGUE_PLAYER_BAN_NO_ALL."</option>";
					/* Zobrazi se jen pro ADD */
					} else {
						echo "<option value=\"all\">"._LEAGUE_PLAYER_BAN_ALL."</option>";
					}
					while ($ar_player_league = mysql_fetch_array($res_player_league)){
						if ($ar_player_league['league_league_id'] == $_GET['lid'] && $_GET['action'] == "league_player_ban_edit"){
							echo "<option value=\"".$ar_player_league['league_league_id']."\" selected=\"selected\">".$ar_player_league['league_league_name']."</option>\n";
						} elseif ($_GET['action'] == "league_player_ban_add") {
							echo "<option value=\"".$ar_player_league['league_league_id']."\" "; if ($ar_player_league['league_league_id'] == $_GET['lid']){echo "selected=\"selected\"";} echo ">".$ar_player_league['league_league_name']."</option>\n";
						}
					}
					echo "</select>";
	echo "		</td>\n";
	echo "		<td>";
	echo "			<script language=\"javascript\">\n";
	echo "			var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"player_ban_from\", \"btnDate1\",\"".$ban_from."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "			</script>\n";
	echo "			<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "		</td>";
	echo "		<td>";
	echo "			<script language=\"javascript\">\n";
	echo "			var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"player_ban_to\", \"btnDate2\",\"".$ban_to."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "			</script>\n";
	echo "			<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "		</td>";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
   	echo "	<tr>\n";
	echo "		<td valign=\"top\"><strong>"._LEAGUE_PLAYER_BAN_REASON."</strong></td>\n";
	echo "		<td valign=\"top\"><textarea name=\"player_ban_reason\" rows=\"4\" cols=\"50\">".$ban_reason."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">".$player_ban_check."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<td width=\"50\" valign=\"top\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "		<input type=\"hidden\" name=\"pid\" value=\"".$ar_player['league_player_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"bid\" value=\"".$ar_player['league_player_ban_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "	<td width=\"800\" valign=\"top\">\n";
	echo "		<form action=\"modul_league.php?action=league_players_show&amp;lid=".$_GET['lid']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "		<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU LIGY
*
***********************************************************************************************************/
function AddLeague(){
	
	global $db_league_leagues,$db_clan_games,$db_league_seasons,$db_league_awards;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "league_add"){
		if (CheckPriv("groups_league_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} elseif ($_GET['action'] == "league_edit"){
		if (CheckPriv("groups_league_edit") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} elseif ($_GET['action'] == "league_del"){
		if (CheckPriv("groups_league_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		echo _NOTENOUGHPRIV;exit;
	}
	
	if ($_GET['action'] == "league_edit"){
		$res_league = mysql_query("
		SELECT league_league_id, league_league_game_id, league_league_name,league_league_description, league_league_team_sub_min_players, league_league_team_sub_max_players, league_league_active, league_league_lock, league_league_mode 
		FROM $db_league_leagues 
		WHERE league_league_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_league = mysql_fetch_array($res_league);
	} else {
		$ar_league = array();
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<span style=\"font-weight:bold;\">"._LEAGUE_NAME."</span>\n";
	echo "				<span style=\"font-weight:bold;margin-left:150px;\">"._LEAGUE_GAME."</span>\n";
	echo "				<span style=\"font-weight:bold;margin-left:80px;\">"._LEAGUE_MODE."</span>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" valign=\"top\"><form action=\"sys_save.php?action=".$_GET['action']."&id=".$_GET['id']."\" method=\"post\">\n";
	echo "				<input type=\"text\" name=\"league_name\" maxlength=\"255\" size=\"30\" "; if ($_GET['action'] == "league_edit"){echo "value=\"".$ar_league['league_league_name']."\"";} echo ">&nbsp;&nbsp;\n";
	echo "				<select  name=\"league_game\">\n";
	echo "				 	<option value=\"\" "; if ($ar_league['league_league_game_id'] == ""){echo "selected=\"selected\"";} echo ">"._LEAGUE_SELECT_GAME."</option>\n";
		 				 	$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_repre=0 ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while($ar_game = mysql_fetch_array($res_game)){
								echo "<option value=\"".$ar_game['clan_games_id']."\""; if ($ar_game['clan_games_id'] == $ar_league['league_league_game_id']){echo "selected=\"selected\"";} echo ">".$ar_game['clan_games_game']."</option>\n";
		 					}
	echo "				</select>&nbsp;&nbsp;\n";
	echo "				<select name=\"league_mode\">\n";
	echo "				 	<option value=\"1\" "; if ($ar_league['league_league_mode'] == 1){echo "selected=\"selected\"";} echo ">"._LEAGUE_MODE_TEAM."</option>\n";
	echo "				 	<option value=\"2\" "; if ($ar_league['league_league_mode'] == 2){echo "selected=\"selected\"";} echo ">"._LEAGUE_MODE_SINGLE."</option>\n";
	echo "				</select>&nbsp;&nbsp;\n";
	echo "				<input type=\"checkbox\" name=\"league_active\" value=\"1\" "; if ($ar_league['league_league_active'] == 1){echo "checked=\"checked\"";} echo "/>&nbsp;<strong>"._LEAGUE_ACTIVE."</strong>\n";
	echo "				<input type=\"checkbox\" name=\"league_lock\" value=\"1\" "; if ($ar_league['league_league_lock'] == 1){echo "checked=\"checked\"";} echo "/>&nbsp;<strong>"._LEAGUE_LOCK."</strong>\n";
	echo "				<br /><br />\n";
	echo "				<input type=\"text\" value=\"".$ar_league['league_league_team_sub_min_players']."\" name=\"league_team_sub_min_players\" size=\"2\" maxlength=\"3\"> "._LEAGUE_TEAM_SUB_MIN_PLAYERS."<br /><br />\n";
	echo "				<input type=\"text\" value=\"".$ar_league['league_league_team_sub_max_players']."\" name=\"league_team_sub_max_players\" size=\"2\" maxlength=\"3\"> "._LEAGUE_TEAM_SUB_MAX_PLAYERS."\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\"><strong>"._LEAGUE_DESC."</strong><br />\n";
	echo "				<textarea name=\"league_description\" rows=\"10\" cols=\"100\">".stripslashes($ar_league['league_league_description'])."</textarea><br />\n";
	echo "				<input type=\"submit\" value=\""; if ($_GET['action'] == "league_add"){echo _LEAGUE_LEAGUE_ADD;} else {echo _LEAGUE_LEAGUE_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"40\" align=\"center\" class=\"eden_title_top\">"._CMN_OPTIONS."</td>\n";
	echo "			<td width=\"20\" align=\"left\" class=\"eden_title_top\">"._CMN_ID."</td>\n";
	echo "			<td width=\"20\" align=\"left\" class=\"eden_title_top\">"._LEAGUE_ACTIVE."</td>\n";
	echo "			<td align=\"left\" class=\"eden_title_top\">"._LEAGUE."</td>\n";
	echo "			<td width=\"150\" align=\"left\" class=\"eden_title_top\">"._LEAGUE_GAME."</td>\n";
	echo "			<td width=\"170\" align=\"left\" class=\"eden_title_top\">"._LEAGUE_TEAMS."</td>\n";
	echo "			<td width=\"170\" align=\"left\" class=\"eden_title_top\">"._LEAGUE_LOCK."</td>\n";
	echo "		</tr>\n";
	$res_league = mysql_query("SELECT league_league_id, league_league_game_id, league_league_name, league_league_active, league_league_lock, league_league_mode 
	FROM $db_league_leagues 
	ORDER BY league_league_game_id ASC, league_league_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar_league = mysql_fetch_array($res_league)){
		$res_game = mysql_query("SELECT clan_games_game FROM $db_clan_games WHERE clan_games_id=".(integer)$ar_league['league_league_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_game = mysql_fetch_array($res_game);
		echo "	<tr onmouseover=\"this.style.backgroundColor='ffdedf'\" onmouseout=\"this.style.backgroundColor='dce3f1'\" style=\"background-color: #dce3f1;\">\n";
		echo "		<td width=\"40\" align=\"center\">\n";
 					if (CheckPriv("groups_league_edit") == 1){echo "<a href=\"modul_league.php?action=league_edit&amp;id=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
					if (CheckPriv("groups_league_season_edit") == 1){echo "<a href=\"modul_league.php?action=league_season_add&amp;lid=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_dtopic.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._LEAGUE_SEASON_ADD."\"></a>";}
				   /*	if (CheckPriv("groups_league_del") == 1){echo ' <a href="modul_league.php?action=league_del&amp;id='.$ar_league['league_league_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';}*/
		echo "		</td>\n";
		echo "		<td width=\"20\" align=\"left\"><strong>".$ar_league['league_league_id']."</strong></td>\n";
		echo "		<td width=\"20\" align=\"left\"><img src=\"images/sys_"; if ($ar_league['league_league_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "		<td align=\"left\"><strong>"; if ($ar_league['league_league_mode'] == 1){echo "<img src=\"./images/sys_team.gif\" alt=\""._LEAGUE_MODE_TEAM."\" title=\""._LEAGUE_MODE_TEAM."\" width=\"12\" height=\"12\"> ";} elseif ($ar_league['league_league_mode'] == 2){echo "<img src=\"./images/sys_player.gif\" alt=\""._LEAGUE_MODE_SINGLE."\" title=\""._LEAGUE_MODE_SINGLE."\" width=\"12\" height=\"12\"> ";} echo stripslashes($ar_league['league_league_name'])."</strong></td>\n";
		echo "		<td width=\"150\" align=\"left\"><strong>".stripslashes($ar_game['clan_games_game'])."</strong></td>\n";
		echo "		<td width=\"170\" align=\"left\">"; if ($ar_league['league_league_mode'] == 1){echo "<a href=\"modul_league.php?action=league_team_show&amp;lid=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."\">"._LEAGUE_TEAM_SHOW."</a>";} elseif ($ar_league['league_league_mode'] == 2){echo "<a href=\"modul_league.php?action=league_players_show&amp;lid=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."\">"._LEAGUE_PLAYERS_SHOW."</a>";} echo "</td>\n";
		echo "		<td width=\"150\" align=\"left\" "; if ($ar_league['league_league_lock'] == 1){ echo "style=\"background-color:#ff0000;\""; $locked = _LEAGUE_LOCKED;} else {$locked = "";} echo "><span style=\"color:#0000ff;font-weight:bold;\">".$locked."</span></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"7\">\n";
		echo "			<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "			   <tr bgcolor=\"#DCE3F1\">\n";
		echo "					<td width=\"80\" align=\"center\" class=\"eden_title_middle\">"._CMN_OPTIONS."</td>\n";
		echo "					<td width=\"20\" align=\"center\" class=\"eden_title_middle\">"._CMN_ID."</td>\n";
		echo "					<td align=\"left\" class=\"eden_title_middle\">"._LEAGUE_SEASON_ACTIVE."</td>\n";
		echo "					<td align=\"left\" class=\"eden_title_middle\">"._LEAGUE_SEASON."</td>\n";
		echo "					<td align=\"center\" class=\"eden_title_middle\">"._LEAGUE_SEASON_ROUNDS."</td>\n";
		echo "					<td align=\"center\" class=\"eden_title_middle\">"._LEAGUE_SEASON_START."</td>\n";
		echo "					<td align=\"center\" class=\"eden_title_middle\">"._LEAGUE_SEASON_END."</td>\n";
		echo "					<td align=\"center\" class=\"eden_title_middle\">"._LEAGUE_SEASON_PLAYOFF_START."</td>\n";
		echo "					<td align=\"center\" class=\"eden_title_middle\">"._LEAGUE_SEASON_PLAYOFF_END."</td>\n";
		echo "				</tr>";
				   		$res_season = mysql_query("SELECT * FROM $db_league_seasons WHERE league_season_league_id=".(integer)$ar_league['league_league_id']." ORDER BY league_season_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				   		$y=0;
						while($ar_season = mysql_fetch_array($res_season)){
							$awards_teams_img = "sys_step_show.gif";
							$awards_teams_alt = _LEAGUE_SEASON_RESULTS_TEAMS;
							
							
							
							
							
							
							$res_awards_players = mysql_query("
							SELECT COUNT(*) 
							FROM $db_league_awards 
							WHERE league_award_player_id != 0 
							AND league_award_league_id=".(integer)$ar_league['league_league_id']." 
							AND league_award_season_id=".(integer)$ar_season['league_season_id']." 
							AND league_award_mode=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num_awards_players = mysql_fetch_array($res_awards_players);
							if ($num_awards_players[0] == 3){
								$awards_players_img = "sys_step_show_done.gif";
								$awards_players_alt = _LEAGUE_SEASON_RESULTS_PLAYERS_A;
							} else {
								$awards_players_img = "sys_step_show.gif";
								$awards_players_alt = _LEAGUE_SEASON_RESULTS_PLAYERS;
							}
							
							$res_awards_players = mysql_query("
							SELECT COUNT(*) 
							FROM $db_league_awards 
							WHERE league_award_team_id != 0 
							AND league_award_team_sub_id != 0 
							AND league_award_league_id=".(integer)$ar_league['league_league_id']." 
							AND league_award_season_id=".(integer)$ar_season['league_season_id']." 
							AND league_award_mode=2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$num_awards_players = mysql_fetch_array($res_awards_players);
							if ($num_awards_players[0] == 3){
								$awards_teams_img = "sys_step_show_done.gif";
						   		$awards_teams_alt = _LEAGUE_SEASON_RESULTS_TEAMS_A;
							} else {
								$awards_teams_img = "sys_step_show.gif";
								$awards_teams_alt = _LEAGUE_SEASON_RESULTS_TEAMS;
							}
							
							
							
							if ($y % 2 == 0){ $cat_class = "cat_level2_even";} else { $cat_class = "cat_level2_odd";}
						   	echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
							echo "	<td width=\"80\" align=\"center\">\n";
								  		if (CheckPriv("groups_league_edit") == 1){echo "<a href=\"modul_league.php?action=league_season_edit&amp;lid=".$ar_league['league_league_id']."&amp;sid=".$ar_season['league_season_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
							echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=season_teams_results&action=results_show&sid=".$ar_season['league_season_id']."&project=".$_SESSION['project']."&amp;mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/".$awards_teams_img."\" width=\"18\" height=\"18\" border=\"0\" alt=\"".$awards_teams_alt."\" title=\"".$awards_teams_alt."\"></a>";
							echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=season_players_results&action=results_show&sid=".$ar_season['league_season_id']."&project=".$_SESSION['project']."&amp;mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/".$awards_players_img."\" width=\"18\" height=\"18\" border=\"0\" alt=\"".$awards_players_alt."\" title=\"".$awards_players_alt."\"></a>";
								  		/*	if (CheckPriv("groups_league_del") == 1){echo ' <a href="modul_league.php?action=league_del&amp;id='.$ar_league['league_league_id'].'&amp;project='.$_SESSION['project'].'"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="'._CMN_DEL.'"></a>';}*/
							echo "	</td>\n";
							echo "	<td width=\"20\" align=\"right\">".$ar_season['league_season_id']."</td>\n";
							echo "	<td width=\"20\" align=\"left\"><img src=\"images/sys_"; if ($ar_season['league_season_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
							echo "	<td align=\"left\">";
							echo "		<a href=\"modul_league.php?action=rounds&sid=".$ar_season['league_season_id']."&project=".$_SESSION['project']."\" target=\"_self\">".$ar_season['league_season_name']."</a>";
							echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=season_players_all&amp;lid=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."&mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/sys_shop_transaction_details.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_LIST_ALLOWED_PLAYERS_ALL."\" title=\""._LEAGUE_LIST_ALLOWED_PLAYERS_ALL."\"></a>";
							echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=season_players_all_guid&amp;lid=".$ar_league['league_league_id']."&amp;project=".$_SESSION['project']."&mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/sys_show_id.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_LIST_ALLOWED_PLAYERS_ALL_GUID."\" title=\""._LEAGUE_LIST_ALLOWED_PLAYERS_ALL_GUID."\"></a>";
						   	echo "	</td>\n";
							echo "	<td width=\"50\" align=\"center\">".$ar_season['league_season_rounds']."/".$ar_season['league_season_playoff_rounds']."</td>\n";
						   	echo "	<td width=\"70\" align=\"center\">".FormatDatetime($ar_season['league_season_start'],"d.m.Y")."</td>\n";
							echo "	<td width=\"70\" align=\"center\">".FormatDatetime($ar_season['league_season_end'],"d.m.Y")."</td>\n";
							echo "	<td width=\"70\" align=\"center\">"; if ($ar_season['league_season_playoff'] == 1){ echo FormatDatetime($ar_season['league_season_playoff_start'],"d.m.Y");} else { echo _LEAGUE_SEASON_NO_PLAYOFF;} echo "</td>\n";
							echo "	<td width=\"70\" align=\"center\">"; if ($ar_season['league_season_playoff'] == 1){echo FormatDatetime($ar_season['league_season_playoff_end'],"d.m.Y");} echo "</td>\n";
							echo "</tr>\n";
							$y++;
						}
		echo "				<tr><td colspan=\"9\"><br><br></td></tr>";
		echo " 			</table>\n";
		echo " 		</td>\n";
		echo " 	</tr>\n";
 	}
	echo "	</table>\n";
}
/***********************************************************************************************************
*
*		PRIDANI SEZONY LIGY
*
***********************************************************************************************************/
function AddSeason(){
	
	global $db_league_leagues,$db_league_seasons,$db_league_seasons_rounds,$db_clan_games;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "league_season_add"){
		if (CheckPriv("groups_league_season_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} elseif ($_GET['action'] == "league_season_edit"){
		if (CheckPriv("groups_league_season_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} elseif ($_GET['action'] == "league_season_del"){
		if (CheckPriv("groups_league_season_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	$res_league = mysql_query("
	SELECT l.league_league_id, l.league_league_name, l.league_league_game_id, g.clan_games_id, g.clan_games_game 
	FROM $db_league_leagues AS l 
	JOIN $db_clan_games AS g ON g.clan_games_id=l.league_league_game_id 
	WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_league = mysql_fetch_array($res_league);
	
	// Dnesni datum
	$today = date("Y-m-d H:i:s");
	
	$res_rounds = mysql_query("SELECT COUNT(*) FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=0 AND league_season_round_date<'".$today."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_rounds = mysql_fetch_array($res_rounds);
	$res_playoff_rounds = mysql_query("SELECT COUNT(*) FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=1 AND league_season_round_date<'".$today."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_playoff_rounds = mysql_fetch_array($res_playoff_rounds);
	
	if ($_GET['action'] == "league_season_edit"){
		$res_season = mysql_query("SELECT * FROM $db_league_seasons WHERE league_season_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_season = mysql_fetch_array($res_season);
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><form action=\"sys_save.php?action=".$_GET['action']."&lid=".$_GET['lid']."&sid=".$_GET['sid']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\"><strong>"._LEAGUE."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\"><strong>".stripslashes($ar_league['league_league_name'])."</strong></td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_GAME."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\"><strong>".stripslashes($ar_league['clan_games_game'])."</strong></td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_NAME."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\"><input type=\"text\" name=\"league_season_name\" size=\"60\" maxlength=\"80\" value=\"".stripslashes($ar_season['league_season_name'])."\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_ACTIVE."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"league_season_active\" value=\"1\" "; if ($ar_season['league_season_active'] == 1){echo "checked";} echo "/></td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_ROUNDS."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
	echo "				<select name=\"league_season_rounds\" size=\"1\">";
							// Zobrazime jen pocet jeste neodehranych kol
							for ($i=$num_rounds[0];$i<21;$i++) {
								if ($i>0){
									echo "<option value=\"".$i."\" "; if ($i == $ar_season['league_season_rounds']){echo "selected=\"selected\"";} echo ">".$i."</option>\n";
								}
							}
	echo "				</select> "._LEAGUE_SEASON_ROUND_HELP."\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_START."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
						if ($_GET['action'] == "league_season_add"){
							$dateon = formatTimeS(time());
							$season_start = $dateon[1].".".$dateon[2].".".$dateon[3];
						} else {
							$dateon = $ar_season['league_season_start'];
							$season_start = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
						}
	echo "				<script language=\"javascript\">\n";
	echo "				var StartDate = new ctlSpiffyCalendarBox(\"StartDate\", \"form1\", \"league_season_start\", \"btnDate1\",\"".$season_start."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "				</script>\n";
	echo "				<script language=\"javascript\">StartDate.writeControl(); StartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_END."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
						if ($_GET['action'] == "league_season_add"){
							$dateon = formatTimeS(time());
							$season_end = $dateon[1].".".$dateon[2].".".$dateon[3];
						} else {
							$dateon = $ar_season['league_season_end'];
							$season_end = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
						}
	echo "				<script language=\"javascript\">\n";
	echo "				var EndDate = new ctlSpiffyCalendarBox(\"EndDate\", \"form1\", \"league_season_end\", \"btnDate2\",\"".$season_end."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "				</script>\n";
	echo "				<script language=\"javascript\">EndDate.writeControl(); EndDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_PLAYOFF."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"league_season_playoff\" value=\"1\" "; if ($ar_season['league_season_playoff'] == 1){echo "checked";} echo "/></td>\n";
	echo "		</tr>\n";
   	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_PLAYOFF_ROUNDS."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
	echo "				<select name=\"league_season_playoff_rounds\" size=\"1\">";
							// Zobrazime jen pocet jeste neodehranych kol
							for ($i=$num_playoff_rounds[0];$i<17;$i++) {
								if ($i>0){
									echo "<option value=\"".$i."\" "; if ($i == $ar_season['league_season_playoff_rounds']){echo "selected=\"selected\"";} echo ">".$i."</option>\n";
								}
							}
	echo "				</select> "._LEAGUE_SEASON_ROUND_HELP."\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_PLAYOFF_START."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
						if ($_GET['action'] == "league_season_add"){
							$dateon = formatTimeS(time());
							$season_playoff_start = $dateon[1].".".$dateon[2].".".$dateon[3];
						} else {
							$dateon = $ar_season['league_season_playoff_start'];
							$season_playoff_start = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
						}
	echo "				<script language=\"javascript\">\n";
	echo "				var PlayoffStartDate = new ctlSpiffyCalendarBox(\"PlayoffStartDate\", \"form1\", \"league_season_playoff_start\", \"btnDate3\",\"".$season_playoff_start."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "				</script>\n";
	echo "				<script language=\"javascript\">PlayoffStartDate.writeControl(); PlayoffStartDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"><strong>"._LEAGUE_SEASON_PLAYOFF_END."</strong></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
						if ($_GET['action'] == "league_season_add"){
							$dateon = formatTimeS(time());
							$season_playoff_end = $dateon[1].".".$dateon[2].".".$dateon[3];
						} else {
							$dateon = $ar_season['league_season_playoff_end'];
							$season_playoff_end = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
						}
	echo "				<script language=\"javascript\">\n";
	echo "				var PlayoffEndDate = new ctlSpiffyCalendarBox(\"PlayoffEndDate\", \"form1\", \"league_season_playoff_end\", \"btnDate4\",\"".$season_playoff_end."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "				</script>\n";
	echo "				<script language=\"javascript\">PlayoffEndDate.writeControl(); PlayoffEndDate.dateFormat=\"dd.MM.yyyy\";</script>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr align=\"left\" valign=\"top\">\n";
	echo "			<td width=\"250\" align=\"right\"></td>\n";
	echo "			<td width=\"500\" align=\"left\">\n";
	echo "				<input type=\"hidden\" name=\"league_id\" value=\"".$_GET['lid']."\">\n";
	echo "				<input type=\"submit\" value=\""; if ($_GET['action'] == "league_season_add"){echo _LEAGUE_SEASON_ADD;} else {echo _LEAGUE_SEASON_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
}
/***********************************************************************************************************
*
*		SEASONROUNDS - Kola sezony
*
***********************************************************************************************************/
function SeasonRounds(){
	
	global $db_league_leagues,$db_league_seasons_rounds,$db_league_seasons,$db_clan_games;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "rounds"){
		if (CheckPriv("groups_league_season_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	$res_rounds = mysql_query("
	SELECT lsr.league_season_round_id, lsr.league_season_round_num, lsr.league_season_round_date, lsr.league_season_round_playoff, lsr.league_season_round_classified, lsr.league_season_round_lock, ls.league_season_name, ll.league_league_id, ll.league_league_name, ll.league_league_lock, g.clan_games_game 
	FROM $db_league_seasons_rounds AS lsr 
	JOIN $db_league_seasons AS ls ON ls.league_season_id=lsr.league_season_round_season_id 
	JOIN $db_league_leagues AS ll ON ll.league_league_id=ls.league_season_league_id 
	JOIN $db_clan_games AS g ON g.clan_games_id=ll.league_league_game_id 
	WHERE lsr.league_season_round_season_id=".(float)$_GET['sid']." ORDER BY lsr.league_season_round_playoff ASC, lsr.league_season_round_num ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_rounds = mysql_fetch_array($res_rounds);
	$lock = $ar_rounds['league_league_lock'];
	$lid = $ar_rounds['league_league_id'];
	// Dnesni datum
	$today = date("Y-m-d H:i:s");
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr "; if ($lock == 1){echo "style=\"background-color:#ff0000;\"";} echo ">\n";
	echo "		<td colspan=\"8\" align=\"left\"><br /><h5>".stripslashes($ar_rounds['league_league_name'])." > ".stripslashes($ar_rounds['league_season_name'])." "; if ($lock == 1){echo "<span style=\"color:#0000ff;\">> "._LEAGUE_LOCKED."</span>";} echo "</h5></td>\n";
	echo "	</tr>\n";
	echo "	<tr style=\"background-color:#dce3f1;\">\n";
	echo "		<td width=\"50\" align=\"center\" class=\"eden_title\"><form action=\"sys_save.php?action=league_season_rounds_edit&lid=".$lid."&sid=".$_GET['sid']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\">ID</td>\n";
	echo "		<td width=\"50\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND."</td>\n";
	echo "		<td width=\"80\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_TYPE."</td>\n";
	echo "		<td width=\"90\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_STATUS."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_DATE."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_CLASSIFIED."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">&nbsp;</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\"></td>\n";
	echo "	</tr>\n";
	$i=1;
	mysql_data_seek ($res_rounds,0);
	while ($ar_rounds = mysql_fetch_array($res_rounds)){
		$dateon = $ar_rounds['league_season_round_date'];
		$season_start = $dateon[8].$dateon[9].".".$dateon[5].$dateon[6].".".$dateon[0].$dateon[1].$dateon[2].$dateon[3];
		// Rozliseni zda je to kolo zakladni, nebo play off
		if ($ar_rounds['league_season_round_playoff'] == 0){
			$league_season_round_kind = _LEAGUE_SEASON_ROUND_TYPE_BASIC;
		} else {
			$league_season_round_kind =  _LEAGUE_SEASON_ROUND_TYPE_PLAYOFF;
		}
		// V pripade, ze jiz je po odehrani kola, nezobrazi se moznost editace datumu
		if ($today < $ar_rounds['league_season_round_date']){
			$league_season_round_date =  "
				<script language=\"javascript\">\n
	   				var RoundDate".$i." = new ctlSpiffyCalendarBox(\"RoundDate".$i."\", \"form1\", \"league_round_".$i."\", \"btnDate".$i."\",\"".$season_start."\",scBTNMODE_CUSTOMBLUE);\n
					RoundDate".$i.".writeControl(); RoundDate".$i.".dateFormat=\"dd.MM.yyyy\";\n
				</script>\n";
			$league_season_round_status = _LEAGUE_SEASON_ROUND_STATUS_DUE;
		} else {
			$league_season_round_date =  FormatDatetime($ar_rounds['league_season_round_date'],"d.m.Y")."<input type=\"hidden\" name=\"league_round_".$i."\" value=\"".FormatDatetime($ar_rounds['league_season_round_date'],"d.m.Y")."\">\n";
			$league_season_round_status = _LEAGUE_SEASON_ROUND_STATUS_DONE;
		}
		// Pokud jeste nebyly zadany vysledky muze se menit pocet bodovanych mist
		if ($ar_rounds['league_season_round_lock'] == 1){
			$league_season_round_classified = $ar_rounds['league_season_round_classified']."<input type=\"hidden\" name=\"league_season_round_classified_".$i."\" value=\"".$ar_rounds['league_season_round_classified']."\">";
		} else {
			$league_season_round_classified = "<input type=\"text\" name=\"league_season_round_classified_".$i."\" value=\"".$ar_rounds['league_season_round_classified']."\" maxlength=\"3\" size=\"4\">";
		}
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"50\" align=\"right\">".$ar_rounds['league_season_round_id']."</td>\n";
		echo "	<td width=\"50\" align=\"right\">".$ar_rounds['league_season_round_num']."</td>\n";
		echo "	<td width=\"80\" align=\"left\">".$league_season_round_kind."</td>\n";
		echo "	<td width=\"90\" align=\"left\">".$league_season_round_status."</td>\n";
		echo "	<td align=\"left\">".$league_season_round_date."<input type=\"hidden\" name=\"lsrid[]\" value=\"".$ar_rounds['league_season_round_id']."\"></td>\n";
		echo "	<td align=\"left\">".$league_season_round_classified."</td>\n";
		echo "	<td align=\"left\">";
		echo "		<a href=\"modul_league.php?action=results_show&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."\" target=\"_self\"><img src=\"images/sys_step.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_SEASON_ROUND_RESULTS_SHOW."\" title=\""._LEAGUE_SEASON_ROUND_RESULTS_SHOW."\"></a>";
		echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=round_result&action=results_show&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."&mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/sys_step_show.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_SEASON_ROUND_RESULTS_SHOW."\" title=\""._LEAGUE_SEASON_ROUND_RESULTS_SHOW."\"></a>";
					if ($today > $ar_rounds['league_season_round_date'] && CheckPriv("groups_league_season_gen") == 1){ echo "		<a href=\"modul_league.php?action=results_add&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."\" target=\"_self\"><img src=\"images/sys_step_add.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_SEASON_ROUND_RESULTS_ADD."\" title=\""._LEAGUE_SEASON_ROUND_RESULTS_ADD."\"></a>";}
		echo "	</td>\n";
		echo "	<td align=\"left\">";
		echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=all&lid=".$lid."&sid=".(float)$_GET['sid']."&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."&mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/sys_shop_transaction_details.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_LIST_ALLOWED_PLAYERS."\" title=\""._LEAGUE_LIST_ALLOWED_PLAYERS."\"></a>";
		echo "		<a href=\"#\" onclick=\"window.open('show_league_players.php?show=id&lid=".$lid."&sid=".(float)$_GET['sid']."&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."&mode=league','','menubar=no,resizable=yes,toolbar=no,status=no,width=600,height=600,scrollbars=yes')\" target=\"_self\"><img src=\"images/sys_show_id.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_LIST_ALLOWED_PLAYERS_GUID."\" title=\""._LEAGUE_LIST_ALLOWED_PLAYERS_GUID."\"></a>";
					if ($today < $ar_rounds['league_season_round_date'] && CheckPriv("groups_league_season_gen") == 1){ echo "		<a href=\"sys_save.php?action=generate_players&lid=".$lid."&sid=".(float)$_GET['sid']."&rid=".$ar_rounds['league_season_round_id']."&project=".$_SESSION['project']."&mode=league\" target=\"_self\"><img src=\"images/sys_save.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\""._LEAGUE_LIST_ALLOWED_PLAYERS_SAVE."\" title=\""._LEAGUE_LIST_ALLOWED_PLAYERS_SAVE."\"></a>";}
		echo "	</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "	<tr align=\"left\" valign=\"top\">\n";
	echo "		<td align=\"left\" colspan=\"8\"><br />\n";
	echo "			<input type=\"hidden\" name=\"sid\" value=\"".$_GET['sid']."\">\n";
	echo "			<input type=\"hidden\" name=\"lid\" value=\"".$lid."\">\n";
	echo "			<input type=\"checkbox\" name=\"league_lock\" value=\"1\" "; if ($lock == 1){echo "checked";} echo "/> "._LEAGUE_LOCK_LEAGUE."<br /><br />\n";
	echo "			<input type=\"submit\" value=\""._LEAGUE_SEASON_ROUNDS_SAVE."\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		LeagueSeasonTeamsResults - Je i v /cfg/eden_league.php
*
*		Zobrazeni vysledku tymu v sezone
*
***********************************************************************************************************/
function LeagueSeasonTeamsResults(){
	
	global $db_league_leagues,$db_league_teams,$db_league_teams_sub,$db_league_seasons,$db_league_seasons_results_teams,$db_league_awards;
	global $db_country,$db_league_seasons_rounds;
	global $eden_cfg;
	global $url_flags,$url_league_awards;
	
	$result = "";
	$result .= "<table style=\"width:400px;\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$result .= "	<tr>\n";
	$result .= "		<td class=\"eden_title\" style=\"width:40px;\">"._LEAGUE_SEASON_ROUND_POSITION."</td>\n";
	$result .= "		<td class=\"eden_title\" style=\"width:60px;text-align:center;\">"._LEAGUE_SEASON_ROUND_POINTS."</td>\n";
	$result .= "		<td class=\"eden_title\" style=\"width:50px;\">"._CMN_COUNTRY."</td>\n";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_TEAM."</td>\n";
	$result .= "	</tr>\n";
   	$res_results_teams = mysql_query("
	SELECT lt.league_team_id, lt.league_team_name, lsrt.league_season_result_team_team_sub_id, lsrt.league_season_result_team_points, c.country_shortname, c.country_name, ls.league_season_end 
	FROM $db_league_seasons_results_teams AS lsrt 
	JOIN $db_league_teams AS lt ON lt.league_team_id = lsrt.league_season_result_team_team_id 
	JOIN $db_country AS c ON c.country_id = lt.league_team_country_id 
	JOIN $db_league_seasons AS ls ON ls.league_season_id = ".(integer)$_GET['sid']." 
	WHERE lsrt.league_season_result_team_season_id = ".(integer)$_GET['sid']." 
	ORDER BY lsrt.league_season_result_team_points DESC LIMIT 10") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar_results_teams = mysql_fetch_array($res_results_teams)){
		// Call function
		$ar_award = LeagueCheckAwards(2,(integer)$_GET['sid'],0,(integer)$ar_results_teams['league_season_result_team_team_sub_id']);
		
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		$result .= "	<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		$result .= "		<td align=\"right\" valign=\"top\">"; if($ar_award['league_award_place']){$result .= "<img src=\"".$url_league_awards.$ar_award['league_award_img']."\" alt=\"".stripslashes($ar_award['league_award_name'])."\" title=\"".stripslashes($ar_award['league_award_name'])."\" />";} else { $result .= "<strong>".$i."</strong>";} $result .= "</td>\n";
		$result .= "		<td valign=\"top\" align=\"right\">".$ar_results_teams['league_season_result_team_points']."</td>\n";
		$result .= "		<td valign=\"top\" align=\"center\"><img src=\"".$url_flags.$ar_results_teams['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_teams['country_name'])."\" title=\"".stripslashes($ar_results_teams['country_name'])."\" /></td>\n";
	   	$result .= "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_results_teams['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_teams['league_team_name'])."</a></td>\n";
		$result .= "	</tr>\n";
		$i++;
	}
	// Show button for setting awards only if season is over
	if ($ar_results_teams['league_season_end'] < date("Y-m-d H:i:s")){
		$result .= "	<tr>\n";
		$result .= "		<td colspan=\"5\"><br /><form action=\"sys_save.php?action=league_awards_give_to_teams&sid=".$_GET['sid']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\">\n";
		$result .= "			<input type=\"hidden\" name=\"sid\" value=\"".$_GET['sid']."\">\n";
		$result .= "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		$result .= "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		$result .= "			<input type=\"submit\" value=\""._LEAGUE_AWARD_SUBMIT_TEAM."\" class=\"eden_button\">\n";
		$result .= "		</form>\n";
		$result .= "		</td>\n";
		$result .= "	</tr>\n";
	}
	$result .= "</table>\n";
	
	return $result;
}
/***********************************************************************************************************
*
*		LeagueSeasonPlayersResults - Je i v /cfg/eden_league.php
*
*		Zobrazeni vysledku hracu v sezone
*
***********************************************************************************************************/
function LeagueSeasonPlayersResults(){
	
	global $db_admin,$db_admin_contact,$db_admin_guids,$db_league_teams,$db_league_teams_sub,$db_league_seasons,$db_league_seasons_results_players;
	global $db_country,$db_league_seasons_rounds,$db_league_players,$db_league_awards;
	global $eden_cfg;
	global $url_flags,$url_league_awards;
	
	$result = "";
	$result .= "<table style=\"width:500px;\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$result .= "	<tr>";
	$result .= "		<td class=\"eden_title\" style=\"width:40px;\">"._LEAGUE_SEASON_ROUND_POSITION."</td>";
	$result .= "		<td class=\"eden_title\" style=\"width:60px;\">"._LEAGUE_SEASON_ROUND_POINTS."</td>";
	$result .= "		<td class=\"eden_title\">"._CMN_COUNTRY."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_PLAYER_NICK."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_GUID."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_TEAM."</td>";
	$result .= "	</tr>";
   	$res_results_players = mysql_query("
	SELECT lsrp.league_season_result_player_player_id, a.admin_id, a.admin_nick, lt.league_team_id, lt.league_team_name, 
	lsrp.league_season_result_player_points, c.country_shortname, c.country_name, agid.admin_guid_guid  
	FROM $db_league_seasons_results_players AS lsrp 
	JOIN $db_league_players AS lp ON lp.league_player_id = lsrp.league_season_result_player_player_id 
	JOIN $db_admin AS a ON a.admin_id = lp.league_player_admin_id 
	JOIN $db_admin_contact AS ac ON ac.aid = a.admin_id 
	JOIN $db_admin_guids AS agid ON agid.aid = a.admin_id AND agid.admin_guid_game_id = lp.league_player_game_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id = lp.league_player_team_id 
	JOIN $db_country AS c ON c.country_id = ac.admin_contact_country 
	WHERE lsrp.league_season_result_player_season_id = ".(integer)$_GET['sid']." 
	ORDER BY lsrp.league_season_result_player_points DESC LIMIT 10") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while ($ar_results_players = mysql_fetch_array($res_results_players)){
		// Call function
		$ar_award = LeagueCheckAwards(1,(integer)$_GET['sid'],(integer)$ar_results_players['league_season_result_player_player_id'],0);
		
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		$result .= "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		$result .= "	<td align=\"right\" valign=\"top\">"; if($ar_award['league_award_place']){$result .= "<img src=\"".$url_league_awards.$ar_award['league_award_img']."\" alt=\"".stripslashes($ar_award['league_award_name'])."\" title=\"".stripslashes($ar_award['league_award_name'])."\" />";} else {$result .= "<strong>".$i."</strong>";} $result .= "</td>";
		$result .= "	<td align=\"left\" valign=\"top\">".$ar_results_players['league_season_result_player_points']."</td>";
		$result .= "	<td valign=\"top\"><img src=\"".$url_flags.$ar_results_players['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_players['country_name'])."\" title=\"".stripslashes($ar_results_players['country_name'])."\" /></td>";
	   	$result .= "	<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_results_players['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['admin_nick'])."</a></td>";
		$result .= "	<td valign=\"top\">".$ar_results_players['admin_guid_guid']."</td>";
		$result .= "	<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_results_players['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['league_team_name'])."</a></td>";
		$result .= "</tr>";
		$i++;
	}
	$result .= "	<tr>\n";
	$result .= "		<td colspan=\"5\"><br /><form action=\"sys_save.php?action=league_awards_give_to_players&sid=".$_GET['sid']."\" method=\"post\" name=\"form1\" enctype=\"multipart/form-data\">\n";
	$result .= "			<input type=\"hidden\" name=\"sid\" value=\"".$_GET['sid']."\">\n";
	$result .= "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	$result .= "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	$result .= "			<input type=\"submit\" value=\""._LEAGUE_AWARD_SUBMIT_PLAYER."\" class=\"eden_button\">\n";
	$result .= "		</form>\n";
	$result .= "		</td>\n";
	$result .= "	</tr>\n";
	$result .= "</table>\n";
	
	return $result;
}
/***********************************************************************************************************
*
*		Add Results - Zadavani vysledku kola
*
*		$rid	= Round ID
*		$mode	= add/edit
*
***********************************************************************************************************/
function AddResults($rid){
	
	global $db_league_leagues,$db_league_seasons_rounds,$db_league_seasons,$db_clan_games,$db_league_seasons_round_allowed_players;
	global $db_admin,$db_admin_contact,$db_country,$db_league_seasons_rounds_results_players,$db_league_teams;
	global $url_flags;
	
   	// CHECK PRIVILEGIES
	if ($_GET['action'] == "results_add" || $_GET['action'] == "results_show"){
		if (CheckPriv("groups_league_season_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		echo _NOTENOUGHPRIV;exit;
	}
	
	$res_rounds = mysql_query("SELECT a.admin_nick, lt.league_team_name, lsrrp.league_season_round_result_player_points, lsrap.league_season_round_allowed_player_guid, lsrrp.league_season_round_result_player_player_id, c.country_shortname,c.country_name 
	FROM $db_league_seasons_rounds_results_players AS lsrrp 
	JOIN $db_league_seasons_round_allowed_players AS lsrap ON lsrap.league_season_round_allowed_player_player_id=lsrrp.league_season_round_result_player_player_id AND lsrap.league_season_round_allowed_player_season_round_id=".(float)$rid." 
	JOIN $db_admin AS a ON a.admin_id=lsrap.league_season_round_allowed_player_admin_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lsrap.league_season_round_allowed_player_team_id 
	LEFT JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
	LEFT JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	WHERE lsrrp.league_season_round_result_player_round_id=".(float)$rid." 
	ORDER BY lsrrp.league_season_round_result_player_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	switch ($_GET['action']){
		case "results_add":
			$res_round = mysql_query("SELECT lsr.league_season_round_id, lsr.league_season_round_classified, lsr.league_season_round_season_id, lsr.league_season_round_date, lsr.league_season_round_num,  l.league_league_id, l.league_league_name, ls.league_season_name 
			FROM $db_league_seasons_rounds AS lsr 
			JOIN $db_league_seasons AS ls ON ls.league_season_id=lsr.league_season_round_season_id 
			JOIN $db_league_leagues AS l ON l.league_league_id=ls.league_season_league_id 
			WHERE lsr.league_season_round_id=".(float)$rid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$nick = "";
			$team = "";
			$table_width = "857";
			
			echo Menu();
			
		break;
		case "results_show":
			$res_round = mysql_query("SELECT lsr.league_season_round_id, lsr.league_season_round_classified, lsr.league_season_round_season_id, lsr.league_season_round_date, lsr.league_season_round_num, l.league_league_id, l.league_league_name, ls.league_season_name 
			FROM $db_league_seasons_rounds AS lsr 
			JOIN $db_league_seasons AS ls ON ls.league_season_id=lsr.league_season_round_season_id 
			JOIN $db_league_leagues AS l ON l.league_league_id=ls.league_season_league_id 
			WHERE lsr.league_season_round_id=".(float)$rid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$flag = "		<td width=\"20\" align=\"left\" class=\"eden_title\">"._CMN_COUNTRY."</td>\n";
			$nick = "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_PLAYER_NICK."</td>\n";
			$team = "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_TEAM."</td>\n";
			$table_width = "600";
		break;
		default:
			echo "";
	}
	$ar_round = mysql_fetch_array($res_round);
	$league_name = stripslashes($ar_round['league_league_name']);
	$season_id = $ar_round['league_season_round_season_id'];
	$season_name = stripslashes($ar_round['league_season_name']);
	$round_num = $ar_round['league_season_round_num'];
	
	echo "<table width=\"".$table_width."\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\">";
					if ($_GET['action'] == "results_add"){echo "<h5 style=\"margin:20px 0px 0px 0px;\">".$league_name." > <a href=\"modul_league.php?action=rounds&sid=".$season_id."&project=".$_SESSION['project']."\" target=\"_self\">".$season_name."</a> > "._LEAGUE_SEASON_ROUND." ".$round_num."</h5>";}
					if ($_GET['action'] == "results_add"){echo "<form action=\"sys_save.php?action=results_add&rid=".$rid."sid=".$season_id."\" method=\"post\" enctype=\"multipart/form-data\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr style=\"background-color:#dce3f1;\">\n";
	echo "		<td width=\"50\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_POSITION."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_POINTS."</td>\n";
	echo $flag;
	echo $nick;
	echo "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_GUID."</td>\n";
	echo $team;
	echo "	</tr>\n";
	switch ($_GET['action']){
		case "results_add":
			$i=1;
			while ($ar_round['league_season_round_classified'] >= $i){
				$ar_rounds = mysql_fetch_array($res_rounds);
				if ($ar_rounds['league_season_round_result_player_player_id']){ $guid = $ar_rounds['league_season_round_allowed_player_guid']." (PID ".$ar_rounds['league_season_round_result_player_player_id'].")";} else {$guid ="";}
				echo "<tr align=\"left\" valign=\"top\" onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\" style=\"background-color: #FFFFFF;\">\n";
				echo "	<td width=\"50\" align=\"right\" valign=\"middle\">";
				echo "		<input type=\"hidden\" name=\"round_player_num\" value=\"".$i."\">";
				echo "		<input type=\"hidden\" name=\"round_player_data[".$i."_place]\" value=\"".$i."\">";
				echo "		".$i."";
				echo "	</td>\n";
				echo "	<td align=\"left\">";
				echo "		<input name=\"round_player_data[".$i."_points]\" size=\"5\" value=\"".$ar_rounds['league_season_round_result_player_points']."\">";
				echo "	</td>\n";
				echo "	<td width=\"200\" align=\"left\">";
				echo "		<input type=\"text\" name=\"round_player_data[".$i."_player_guid]\" size=\"25\" value=\"".$guid."\" autocomplete=\"off\" onkeyup=\"ajax_showOptions(this,'getAllowedPlayerGuidByLetters=1&rid=".$rid."&project=".$_SESSION['project']."',event)\">";
				echo "	</td>\n";
				echo "</tr>\n";
				$i++;
			}
			echo "	<tr align=\"left\" valign=\"top\">\n";
			echo "		<td align=\"left\" colspan=\"3\"><br />\n";
			echo "			<input type=\"hidden\" name=\"round\" value=\"".$round_num."\">\n";
			echo "			<input type=\"hidden\" name=\"rid\" value=\"".$rid."\">\n";
			echo "			<input type=\"hidden\" name=\"sid\" value=\"".$season_id."\">\n";
			echo "			<input type=\"submit\" value=\""._LEAGUE_SEASON_ROUND_RESULTS_SAVE."\" class=\"eden_button\">\n";
			echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
			echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		   	if ($ar_rounds){echo "			<input type=\"hidden\" name=\"results_mode\" value=\"edit\">\n";}
			echo "			</form>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		break;
		case "results_show":
			$i=1;
			while ($ar_rounds = mysql_fetch_array($res_rounds)){
				echo "	<tr "; if ($i % 2 == 0){echo "class=\"suda\"";} else {echo "class=\"licha\"";} echo ">\n";
				echo "	<td width=\"50\" align=\"right\" valign=\"middle\">".$i."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_season_round_result_player_points']."</td>\n";
	   			echo "	<td valign=\"top\"><img src=\"".$url_flags.$ar_rounds['country_shortname'].".gif\" alt=\"".stripslashes($ar_rounds['country_name'])."\" title=\"".stripslashes($ar_rounds['country_name'])."\" /></td>";
	   			echo "	<td align=\"left\">".$ar_rounds['admin_nick']."</td>\n";
				echo "	<td width=\"200\" align=\"left\">".$ar_rounds['league_season_round_allowed_player_guid']."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_team_name']."</td>\n";
				echo "</tr>\n";
				$i++;
			}
		break;
		default;
			echo "";
	}
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		Season Results - Zobrazeni vysledku sezony
*
*		$sid	= Season ID
*		$mode	= p (players)
*				  t (teams)
*
***********************************************************************************************************/
function SeasonResults($sid = 0,$mode = "p"){
	
	global $db_league_leagues,$db_league_seasons_rounds,$db_league_seasons,$db_clan_games,$db_league_seasons_round_allowed_players;
	global $db_admin,$db_league_seasons_rounds_results_players,$db_league_teams;
	
	$res_rounds = mysql_query("SELECT a.admin_nick, lt.league_team_name, lsrrp.league_season_round_result_player_points, lsrap.league_season_round_allowed_player_guid, lsrrp.league_season_round_result_player_player_id 
	FROM $db_league_seasons_rounds_results_players AS lsrrp 
	JOIN $db_league_seasons_round_allowed_players AS lsrap ON lsrap.league_season_round_allowed_player_player_id=lsrrp.league_season_round_result_player_player_id AND lsrap.league_season_round_allowed_player_season_round_id=".(float)$rid." 
	JOIN $db_admin AS a ON a.admin_id=lsrap.league_season_round_allowed_player_admin_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lsrap.league_season_round_allowed_player_team_id 
	WHERE lsrrp.league_season_round_result_player_round_id=".(float)$rid." 
	ORDER BY lsrrp.league_season_round_result_player_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	switch ($mode){
		case "p":
			$res_round = mysql_query("SELECT lsr.league_season_round_id, lsr.league_season_round_classified, lsr.league_season_round_season_id, lsr.league_season_round_date, lsr.league_season_round_num,  l.league_league_id, l.league_league_name, ls.league_season_name 
			FROM $db_league_seasons_rounds AS lsr 
			JOIN $db_league_seasons AS ls ON ls.league_season_id=lsr.league_season_round_season_id 
			JOIN $db_league_leagues AS l ON l.league_league_id=ls.league_season_league_id 
			WHERE lsr.league_season_round_id=".(float)$rid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$nick = "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_PLAYER_NICK."</td>\n";
			$guid = "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_ROUND_GUID."</td>\n";
			$table_width = "600";
			
			echo Menu();
			
		break;
		case "t":
			$res_round = mysql_query("SELECT lsr.league_season_round_id, lsr.league_season_round_classified, lsr.league_season_round_season_id, lsr.league_season_round_date, lsr.league_season_round_num, l.league_league_id, l.league_league_name, ls.league_season_name 
			FROM $db_league_seasons_rounds AS lsr 
			JOIN $db_league_seasons AS ls ON ls.league_season_id=lsr.league_season_round_season_id 
			JOIN $db_league_leagues AS l ON l.league_league_id=ls.league_season_league_id 
			WHERE lsr.league_season_round_id=".(float)$rid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$nick = "";
			$guid = "";
			$table_width = "400";
		break;
		default:
			echo "";
	}
	$ar_round = mysql_fetch_array($res_round);
	$league_name = stripslashes($ar_round['league_league_name']);
	$season_id = $ar_round['league_season_round_season_id'];
	$season_name = stripslashes($ar_round['league_season_name']);
	$round_num = $ar_round['league_season_round_num'];
	
	echo "<table width=\"".$table_wifth."\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\">";
					if ($_GET['action'] == "results_add"){echo "<h5 style=\"margin:20px 0px 0px 0px;\">".$league_name." > <a href=\"modul_league.php?action=rounds&sid=".$season_id."&project=".$_SESSION['project']."\" target=\"_self\">".$season_name."</a> > "._LEAGUE_SEASON_ROUND." ".$round_num."</h5>";}
					if ($_GET['action'] == "results_add"){echo "<form action=\"sys_save.php?action=results_add&rid=".$rid."sid=".$season_id."\" method=\"post\" enctype=\"multipart/form-data\">";}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr style=\"background-color:#dce3f1;\">\n";
	echo "		<td width=\"50\" align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_POSITION."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_SEASON_POINTS."</td>\n";
	echo $nick;
	echo $guid;
	echo "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_TEAM."</td>\n";
	echo "	</tr>\n";
	switch ($mode){
		case "p":
			$i=1;
			while ($ar_rounds = mysql_fetch_array($res_rounds)){
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"50\" align=\"right\" valign=\"middle\">".$i."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_season_round_result_player_points']."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_team_name']."</td>\n";
				echo "</tr>\n";
				$i++;
			}
		break;
		case "t":
			$i=1;
			while ($ar_rounds = mysql_fetch_array($res_rounds)){
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
	   			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"50\" align=\"right\" valign=\"middle\">".$i."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_season_round_result_player_points']."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['admin_nick']."</td>\n";
				echo "	<td width=\"200\" align=\"left\">".$ar_rounds['league_season_round_allowed_player_guid']."</td>\n";
				echo "	<td align=\"left\">".$ar_rounds['league_team_name']."</td>\n";
				echo "</tr>\n";
				$i++;
			}
		break;
		default;
			echo "";
	}
	echo "</table>\n";
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU GUIDu
*
***********************************************************************************************************/
function AddGuid(){
	
	global $db_league_guids,$db_clan_games;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_GET['action'] == "guid_edit"){
		$res_guid = mysql_query("SELECT league_guid_id, league_guid_name, league_guid_game_id, league_guid_sample, league_guid_help, league_guid_active FROM $db_league_guids WHERE league_guid_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_guid = mysql_fetch_array($res_guid);
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\"><strong>"._LEAGUE_GUID_NAME."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><strong>"._LEAGUE_GAME."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><strong>"._LEAGUE_GUID_ACTIVE."</strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\"><form action=\"sys_save.php?action=".$_GET['action']."&id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<input type=\"text\" name=\"league_guid_name\" maxlength=\"255\" size=\"30\" "; if ($_GET['action'] == "guid_edit"){echo "value=\"".$ar_guid['league_guid_name']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select  name=\"league_guid_game\">\n";
				 	$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_repre=0 ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_game = mysql_fetch_array($res_game)){
						echo "<option value=\"".$ar_game['clan_games_id']."\""; if ($ar_game['clan_games_id'] == $ar_guid['league_guid_game_id']){echo "selected=\"selected\"";} echo ">".$ar_game['clan_games_game']."</option>\n";
					}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\" valign=\"top\" width=\"500\"><input type=\"checkbox\" name=\"league_guid_active\" value=\"1\" "; if ($ar_guid['league_guid_active'] == 1){echo "checked";} echo " /></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\"><strong>"._LEAGUE_GUID_SAMPLE."</strong><br />\n";
	echo "			<input type=\"text\" name=\"league_guid_sample\" maxlength=\"255\" size=\"60\" "; if ($_GET['action'] == "guid_edit"){echo "value=\"".$ar_guid['league_guid_sample']."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\"><strong>"._LEAGUE_GUID_HELP."</strong><br />\n";
	echo "			<textarea name=\"league_guid_help\" rows=\"4\" cols=\"60\">".stripslashes($ar_guid['league_guid_help'])."</textarea><br />\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "guid_add"){echo _LEAGUE_GUID_ADD;} else {echo _LEAGUE_GUID_EDIT;}  echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"40\" align=\"center\" class=\"eden_title\">"._CMN_OPTIONS."</td>\n";
	echo "		<td width=\"20\" align=\"left\" class=\"eden_title\">"._CMN_ID."</td>\n";
	echo "		<td width=\"250\" align=\"left\" class=\"eden_title\">"._LEAGUE_GUID_NAME."</td>\n";
	echo "		<td width=\"200\" align=\"left\" class=\"eden_title\">"._LEAGUE_GUID_SAMPLE."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_GAME."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_GUID_ACTIVE."</td>\n";
	echo "	</tr>";
	$res_guids = mysql_query("SELECT lg.league_guid_id, lg.league_guid_name, lg.league_guid_sample, lg.league_guid_active, g.clan_games_game 
	FROM $db_league_guids AS lg 
	JOIN ($db_clan_games AS g) 
	ON (lg.league_guid_game_id=g.clan_games_id) 
	ORDER BY league_guid_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while($ar_guids = mysql_fetch_array($res_guids)){
		//$res_game = mysql_query("SELECT clan_games_game FROM $db_clan_games WHERE clan_games_id=".$ar_guids['league_guid_game_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		//$ar_game = mysql_fetch_array($res_game);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"40\" align=\"center\">";
 		if (CheckPriv("groups_league_edit") == 1){echo "<a href=\"modul_league.php?action=guid_edit&amp;id=".$ar_guids['league_guid_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"right\">".$ar_guids['league_guid_id']."</td>\n";
		echo "	<td width=\"250\" align=\"left\">".stripslashes($ar_guids['league_guid_name'])."</td>\n";
		echo "	<td width=\"200\" align=\"left\">".stripslashes($ar_guids['league_guid_sample'])."</td>\n";
		echo "	<td align=\"left\">".stripslashes($ar_guids['clan_games_game'])."</td>\n";
		echo "	<td align=\"left\"><img src=\"images/sys_"; if ($ar_guids['league_guid_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		AWARDS
*
***********************************************************************************************************/
function AddAward(){
	
	global $db_league_awards,$db_clan_games,$db_league_leagues,$db_league_seasons;
	global $ftp_path_league_awards;
	global $eden_cfg;
	global $url_league_awards;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_league_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_GET['action'] == "league_award_edit"){
		$res_award = mysql_query("SELECT league_award_id, league_award_league_id, league_award_season_id, league_award_game_id, league_award_place, league_award_name, league_award_img, league_award_mode, league_award_active 
		FROM $db_league_awards WHERE league_award_id=".(integer)$_GET['laid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_award = mysql_fetch_array($res_award);
	} else {
		$ar_award = array();
	}
	
	// Connection to FTP server
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "var _img = new Array();";
	$d = ftp_nlist($conn_id, $ftp_path_league_awards);
	$x = 0; 
	
	while($entry = $d[$x]) {
		$x++;
    	$entry = str_ireplace ($ftp_path_league_awards,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != "..") {
			echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_league_awards.$entry."\";\n";
		}
	}
	echo "function doIt(_obj){\n";
	echo "  if (!_obj)return;\n";
	echo "  var _index = _obj.selectedIndex;\n";
	echo "  if (!_index)return;\n";
	echo "  var _item  = _obj[_index].id;\n";
	echo "  if (!_item)return;\n";
	echo "  if (_item<0 || _item >=_img.length)return;\n";
	echo "  document.images[\"award_image\"].src=_img[_item].src;\n";
	echo "}\n";
	echo "//-->\n";
	echo "</script>";
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\"><form action=\"sys_save.php?action=".$_GET['action']."&laid=".$_GET['laid']."\" method=\"post\">\n";
	echo "			<div class=\"eden_inputs\" style=\"width:350;\">";
	echo "				<strong>"._LEAGUE_AWARD_NAME."</strong><br />";
	echo "				<input type=\"text\" name=\"league_award_name\" maxlength=\"255\" size=\"60\" "; if ($_GET['action'] == "league_award_edit"){echo "value=\"".$ar_award['league_award_name']."\"";} echo ">\n";
	echo "			</div>\n";
	echo "			<div class=\"eden_inputs\" style=\"width:310;\">";
	echo "				<strong>"._LEAGUE_AWARD_GAME_LEAGUE_SEASON."</strong><br />";
	echo "				<select name=\"league_award_gls\">\n"; // GameLeagueSeason
						$res_league = mysql_query("SELECT ll.league_league_id, ll.league_league_game_id, ll.league_league_name, ll.league_league_active, ll.league_league_lock, ll.league_league_mode, g.clan_games_id, g.clan_games_game 
						FROM $db_league_leagues AS ll 
						JOIN $db_clan_games AS g ON g.clan_games_id=ll.league_league_game_id 
						ORDER BY ll.league_league_game_id ASC, ll.league_league_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar_league = mysql_fetch_array($res_league)){
							echo "<optgroup label=\"".$ar_league['clan_games_game']." - ".$ar_league['league_league_name']."\">\n";
					   		$res_season = mysql_query("SELECT league_season_id, league_season_name FROM $db_league_seasons WHERE league_season_league_id=".(integer)$ar_league['league_league_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							while($ar_season = mysql_fetch_array($res_season)){
								echo "<option value=\"".$ar_league['clan_games_id'].":".$ar_league['league_league_id'].":".$ar_season['league_season_id']."\""; if ($ar_league['clan_games_id'].":".$ar_league['league_league_id'].":".$ar_season['league_season_id'] == $ar_award['league_award_game_id'].":".$ar_award['league_award_league_id'].":".$ar_award['league_award_season_id']){echo "selected=\"selected\"";} echo ">".$ar_season['league_season_name']."</option>\n";
							}
							echo "</optgroup>\n";
					 	}
	echo "		  		</select>\n";
	echo "			</div>\n";
	echo "			<div class=\"eden_inputs\" style=\"width:60;\">";
	echo "				<strong>"._LEAGUE_AWARD_ACTIVE."</strong><br />";
	echo "				<input type=\"checkbox\" name=\"league_award_active\" value=\"1\" "; if ($ar_award['league_award_active'] == 1){echo "checked=\"checked\"";} echo " />\n";
	echo "			</div>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\">";
	echo "			<div class=\"eden_inputs\" style=\"width:200;\">";
	echo "				<strong>"._LEAGUE_AWARD_IMG."</strong><br />
						<select name=\"league_award_img\" size=\"5\" onclick=\"doIt(this)\">";
						$d = ftp_nlist($conn_id, $ftp_path_league_awards);
						$x = 0;
						echo "<option value=\"0\">"._LEAGUE_AWARD_CHOOSE_IMAGE."</option>\n";
						while($entry = $d[$x]) {
							$x++;
							$entry = str_ireplace ($ftp_path_league_awards,"",$entry);//Odstrani cestu k ftp adresari
							if ($entry != "." && $entry != ".." && $entry != "AllTopics.gif") {
								echo "<option id=\"".$x."\" value=\"".$entry."\""; if ($entry == $ar_award['league_award_img']){ echo "selected=\"selected\"";} echo ">".$entry."</option>\n";
							}
						}
						ftp_close($conn_id);
	echo "				</select>&nbsp;&nbsp;";
	echo "				<img name=\"award_image\" src=\""; if (!empty($ar_award['league_award_img'])){echo $url_league_awards.$ar_award['league_award_img'];} else {echo $url_league_awards."AllTopics.gif";} echo "\" border=\"0\">";
	echo "			</div>";
	echo "			<div class=\"eden_inputs\" style=\"width:200;\">";
	echo "				<strong>"._LEAGUE_AWARD_MODE."</strong><br />";
	echo "				<select name=\"league_award_mode\">\n";
							echo "<option value=\"1\""; if ($ar_award['league_award_mode'] == 1){echo "selected=\"selected\"";} echo ">"._LEAGUE_AWARD_MODE_PLAYER."</option>\n";
							echo "<option value=\"2\""; if ($ar_award['league_award_mode'] == 2){echo "selected=\"selected\"";} echo ">"._LEAGUE_AWARD_MODE_TEAM."</option>\n";
	echo "		  		</select>\n";
	echo "			</div>";
	echo "			<div class=\"eden_inputs\" style=\"width:200;\">";
	echo "				<strong>"._LEAGUE_AWARD_PLACE."</strong><br />";
	echo "				<select name=\"league_award_place\">\n";
							echo "<option value=\"1\""; if ($ar_award['league_award_place'] == 1){echo "selected=\"selected\"";} echo ">1</option>\n";
							echo "<option value=\"2\""; if ($ar_award['league_award_place'] == 2){echo "selected=\"selected\"";} echo ">2</option>\n";
							echo "<option value=\"3\""; if ($ar_award['league_award_place'] == 3){echo "selected=\"selected\"";} echo ">3</option>\n";
							echo "<option value=\"99\""; if ($ar_award['league_award_place'] == 99){echo "selected=\"selected\"";} echo ">"._LEAGUE_AWARD_PLACE_SPECIAL."</option>\n";
	echo "		  		</select>\n";
	echo "			</div>";
	echo "	</td>";
	echo "</tr>";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "league_award_add"){echo _LEAGUE_AWARD_ADD;} else {echo _LEAGUE_AWARD_EDIT;}  echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"40\" align=\"center\" class=\"eden_title\">"._CMN_OPTIONS."</td>\n";
	echo "		<td width=\"20\" align=\"left\" class=\"eden_title\">"._CMN_ID."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_GAME."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_TYPE_IMG."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_LEAGUE."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_SEASON."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_NAME."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_IMG."</td>\n";
	echo "		<td align=\"left\" class=\"eden_title\">"._LEAGUE_AWARD_ACTIVE."</td>\n";
	echo "	</tr>";
	$res_awards = mysql_query("SELECT la.league_award_id, la.league_award_name, la.league_award_img, la.league_award_mode, la.league_award_active, g.clan_games_game, ll.league_league_name, ls.league_season_name 
	FROM $db_league_awards AS la 
	JOIN $db_clan_games AS g ON g.clan_games_id=la.league_award_game_id 
	JOIN $db_league_leagues AS ll ON ll.league_league_id=la.league_award_league_id 
	JOIN $db_league_seasons AS ls ON ls.league_season_id=la.league_award_season_id 
	ORDER BY g.clan_games_game, ll.league_league_id, ls.league_season_id, la.league_award_mode, la.league_award_place") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i = 1;
	while($ar_awards = mysql_fetch_array($res_awards)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"40\" align=\"center\">";
 		if (CheckPriv("groups_league_edit") == 1){echo "<a href=\"modul_league.php?action=league_award_edit&amp;laid=".$ar_awards['league_award_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"20\" align=\"right\">".$ar_awards['league_award_id']."</td>\n";
		echo "	<td align=\"left\">".stripslashes($ar_awards['clan_games_game'])."</td>\n";
		echo "	<td align=\"left\"><img src=\"images/sys_"; if ($ar_awards['league_award_mode'] == 1){echo "18_player"; $alt = _LEAGUE_PLAYER;} else {echo "18_team"; $alt = _LEAGUE_TEAM;} echo ".gif\" alt=\"".$alt."\" title=\"".$alt."\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "	<td align=\"left\">".stripslashes($ar_awards['league_league_name'])."</td>\n";
		echo "	<td align=\"left\">".stripslashes($ar_awards['league_season_name'])."</td>\n";
		echo "	<td align=\"left\">".stripslashes($ar_awards['league_award_name'])."</td>\n";
		echo "	<td align=\"left\"><img src=\"".$url_league_awards.$ar_awards['league_award_img']."\" alt=\"\" border=\"0\"></td>\n";
		echo "	<td align=\"left\"><img src=\"images/sys_"; if ($ar_awards['league_award_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}

if ($_GET['mode'] != "league"){ // Nezobrazi se v pripade ze je volano z modul_clan_games.php
	include ("inc.header.php");
		switch ($_GET['action']){
			case "league_award_add":
				AddAward();
			break;
			case "league_award_edit":
   				AddAward();
			break;
			case "league_award_img_upload":
   				 EdenSysImageManager();
			break;
			case "league_award_img_del":
   				 EdenSysImageManager();
			break;
			case "guid_add":
				AddGuid();
			break;
			case "guid_edit":
   				AddGuid();
			break;
			case "showmain":
				AddLeague();
			break;
			case "league_add":
				AddLeague();
			break;
			case "league_edit":
				AddLeague();
			break;
			case "league_del":
				AddLeague();
			break;
			case "league_season_add":
				AddSeason();
			break;
			case "league_season_edit":
   				AddSeason();
			break;
			case "league_season_del":
				AddSeason(); 
			break;
			case "league_team_show":
				ShowTeam();
			break;
			case "league_team_edit":
				AddTeam();
			break;
			case "league_team_del":
				AddTeam();
			break;
			case "league_player_add":
   				AddPlayer();
			break;
			case "league_player_edit":
				AddPlayer(); 
			break;
			case "league_player_del":
				DeletePlayer();
			break;
			case "league_player_ban_add":
				BanPlayer();
			break;
			case "league_player_ban_edit":
				BanPlayer();
			break;
			case "list_allowed_players":
				ListAllowedPlayers($_GET['lid']);
			break;
			case "league_players_show":
				ShowPlayers();
			break;
			case "rounds":
   				SeasonRounds();
			break;
			case "results_add":
   				AddResults((float)$_GET['rid']);
			break;
			case "results_show":
   				AddResults((float)$_GET['rid']);
			break;
			default:
				AddLeague();
		}
	include ("inc.footer.php");
}