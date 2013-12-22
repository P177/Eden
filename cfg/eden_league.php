<?php //ř
/***********************************************************************************************************
*
*		LeagueCheck24
*
*		Proverime zda se hrac nachazi v 24 hodinove ochranne lhute
*
*		$old_date		= Datum odchodu/prestupu hrace
*
***********************************************************************************************************/
function LeagueCheck24($old_date = "1990-01-01 00:00:00"){
	
	global $db_setup;
	
	$res_setup = mysql_query("SELECT setup_league_check_24 FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	if ($ar_setup['setup_league_check_24'] == 1){
		$date_old = strtotime($old_date);
		$date_estimate = strtotime("+1 day",$date_old)."<br>";
		if (date('Y-m-d H:i:s', $date_estimate) <  date('Y-m-d H:i:s')){
			return True;
		} else {
			return False;
		}
	} else {
		return True;
	}
}
/***********************************************************************************************************
*
*		LeagueTeam
*
*		Zobrazi registraci / editaci teamu
*
*		$mode		= Mod zobrazeni (reg/edit)
*		$game		= Hra pro kterou se team registruje
*		$league		= Liga pro kterou se team registruje
*
***********************************************************************************************************/
function LeagueTeam($mode = "reg", $game = 0, $league = 0){
	
	global $db_league_teams,$db_league_teams_sub,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_league_players,$db_clan_games,$db_country;
	global $eden_cfg;
	global $url_league_team;
	global $url_league_team;
	
	$_GET['action'] = AGet($_GET,'action');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	// Pokud najdeme id uzivatele, ktery ma nejaky team jiz zaregistrovany odesle chybova hlaska
	$res = mysql_query("SELECT COUNT(*) FROM $db_league_teams WHERE league_team_owner_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($ar[0] > 0){
		$team_owner = 1;
	}
	
	if ($game != 0){
		// Pokud najdeme id uzivatele, ktery je registrovany v nejakem teamu jiz zaregistrovany odesle chybova hlaska
		$res = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_game_id=".(integer)$game." AND league_player_team_sub_id<>0") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($ar[0] > 0){
			$team_same_game = 1;
		}
	}
	
	// Pokud je hrac registrovany v nejakem teamu, zobrazi se mu upozorneni o moznostio prestupu a naslednych volbach
	$res_player = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_team_sub_id<>0") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_player = mysql_fetch_array($res_player);
	
	echo "<div class=\"eden_league\">";
	$size = GetSetupImageInfo("league_team_logo","filesize") / 1024;
	if ($mode == "reg" && $game == 0){
		/* Pokud neni zadana hra ve funkci na strance, zobrazi se varovani */
		echo "<table class=\"eden_league_table\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"eden_users_td_message\"><p>"._LEAGUE_TEAM_NO_GAME."</p></td></tr></table>";
	} elseif ($mode == "reg" && $league == 0){
		/* Pokud neni zadana hra ve funkci na strance, zobrazi se varovani */
		echo "<table class=\"eden_league_table\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"eden_users_td_message\"><p>"._LEAGUE_TEAM_NO_LEAGUE."</p></td></tr></table>"; 
	} elseif ($mode == "reg" && $team_owner == 1){
		/* Pokud uz uzivatel vlastni jeden team objevi se o tom info */
		echo "<table class=\"eden_league_table\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"eden_users_td_message\"><p>"._LEAGUE_TEAM_PLAYER_OWN_TEAM."</p></td></tr></table>"; 
	} elseif ($mode == "reg" && $team_same_game == 1){
		/* Pokud uz hrac hraje stejnou hru v jinem tymum objevi se hlaska */
		echo "<table class=\"eden_league_table\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"eden_users_td_message\"><p>"._LEAGUE_TEAM_PLAYER_PLAY_SAME_GAME."</p></td></tr></table>"; 
	} else {
		if ($mode == "reg") {
			$form_mode = "league_team_reg";
			$form_submit = _LEAGUE_TEAM_SUBMIT_REG;
			$array_team_game = array();
			$array_team_league = array();
			$array_team_num = 0;
			$team_name = "<input type=\"text\" name=\"league_team_name\" size=\"40\" maxlength=\"80\">";
		} else {
			$form_mode = "league_team_edit";
			$form_submit = _LEAGUE_TEAM_SUBMIT_EDIT;
			$res_team = mysql_query("SELECT * FROM $db_league_teams WHERE league_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_team = mysql_fetch_array($res_team);
			$res_team_game = mysql_query("
			SELECT lts.league_team_sub_game_id, ltsl.league_teams_sub_league_league_id 
			FROM $db_league_teams_sub AS lts 
			LEFT JOIN $db_league_teams_sub_leagues AS ltsl ON ltsl.league_teams_sub_league_team_sub_id=lts.league_team_sub_id 
			WHERE lts.league_team_sub_team_id=".(integer)$_GET['ltid']
			) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while ($ar_team_game = mysql_fetch_array($res_team_game)){
				$array_team_game[] = $ar_team_game['league_team_sub_game_id'];
				$array_team_league[] = AGet($ar_team_game,'league_teams_sub_league_league_id');
				$array_team_num = count($array_team_game);
			}
			$team_name = "<strong>".stripslashes($ar_team['league_team_name'])."</strong>";
		}
		
		echo "		<script type=\"text/javascript\">";
		echo "		<!--";
		echo "		function CheckTeam(formular) {";
		echo "			if (formular.league_team_name.value == \"\"){";
		echo "				alert (\""._ERR_LEAGUE_TEAM_NO_NAME."\");";
		echo "				return false;";
		echo "			} else if (formular.league_team_pass.value == \"\"){";
		echo "				alert (\""._ERR_LEAGUE_TEAM_NO_PASS."\");";
		echo "				return false;";
		echo "			} else if (formular.league_team_pass.value.length < 4){";
		echo "				alert (\""._ERR_LEAGUE_TEAM_NO_PASS_SHORT."\");";
		echo "				return false;";
		echo "			} else {";
		echo "				return true;";
		echo "			}";
		echo "		}";
		echo "		//-->";
		echo "		</script>";
		echo "<table class=\"eden_league_table\" cellspacing=\"0\" cellpadding=\"3\">";
		echo "	<tr>";
		echo "		<td colspan=\"2\" valign=\"top\"><form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=".$form_mode."&ltid=".$ar_team['league_team_id']."&project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return CheckTeam(this)\">";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_NAME."</strong></td>";
		echo "		<td class=\"eden_users_td_data\">".$team_name."</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_TAG."</strong></td>";
		echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_tag\" size=\"20\" maxlength=\"40\" value=\"".stripslashes($ar_team['league_team_tag'])."\"></td>";
		echo "	</tr>";
		/*
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_PASS."</strong><br>"._LEAGUE_TEAM_PASS_HELP."</td>";
		echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_pass\" size=\"8\" maxlength=\"8\" value=\"".stripslashes($ar_team['league_team_pass'])."\"></td>";
		echo "	</tr>";
		*/
		if ($mode == "reg") {
			echo "	<tr>";
			echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_GAME."</strong></td>";
			echo "		<td class=\"eden_users_td_data\">";
			echo "			<select name=\"league_team_game\" class=\"input\">";
				 			$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				 			while ($ar_game = mysql_fetch_array($res_game)){
				 				echo "<option value=\"".$ar_game['clan_games_id']."\""; if (in_array($ar_game['clan_games_id'], $array_team_game)) {echo " selected";}  echo ">".stripslashes($ar_game['clan_games_game'])."</option>";
				 			}
			echo "			</select>";
			echo "		</td>";
			echo "	</tr>";
		}
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_WEB."</strong></td>";
		echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_web\" size=\"40\" maxlength=\"255\" value=\"".stripslashes($ar_team['league_team_web'])."\"></td>";
		echo "	</tr>";
		if ($eden_cfg['modul_league_team_irc'] == 1){
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_IRC."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_irc\" size=\"20\" maxlength=\"40\" value=\"".stripslashes($ar_team['league_team_irc'])."\"></td>";
			echo "</tr>";
		}
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_MOTTO."</strong></td>";
		echo "		<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_motto\" size=\"40\" maxlength=\"255\" value=\"".stripslashes($ar_team['league_team_motto'])."\"></td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_COUNTRY."</strong></td>";
		echo "		<td class=\"eden_users_td_data\">";
		echo "			<select name=\"league_team_country\" class=\"input\">";
						$iplook = new ip2country($eden_cfg['ip']);
						if ($iplook->LookUp()){
							$team_country = $iplook->Prefix1;
						} else {
							$team_country = "CZ";
						}
						$res_country = mysql_query("SELECT country_id, country_name, country_shortname FROM $db_country ORDER BY country_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
						while ($ar_country = mysql_fetch_array($res_country)){
							echo "<option value=\"".$ar_country['country_id']."\""; if ($_GET['action'] == "league_team_reg" && $ar_country['country_shortname'] == $team_country ) {echo " selected";} elseif ($ar_team['league_team_country_id'] == $ar_country['country_id']) {echo " selected";}  echo ">".$ar_country['country_name']."</option>";
						}
		echo "			</select>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_LOGO."</strong></td>";
		echo "		<td class=\"eden_users_td_data\"><input type=\"file\" name=\"league_team_logo\" size=\"40\">"; if ($ar_team['league_team_logo'] != ""){echo "<br><img src=\"".$url_league_team.$ar_team['league_team_logo']."\">";} echo "<br>"._LEAGUE_TEAM_LOGO_WIDTH.GetSetupImageInfo("league_team_logo","width")." px<br>"._LEAGUE_TEAM_LOGO_HEIGHT.GetSetupImageInfo("league_team_logo","height")." px<br>"._LEAGUE_TEAM_LOGO_SIZE.$size." kB</td>";
		echo "	</tr>";
		if ($eden_cfg['modul_league_team_servers'] == 1){
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_SERVER_1."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_server1\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server1'])."\"></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_SERVER_2."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_server2\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server2'])."\"></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_SERVER_3."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_server3\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server3'])."\"></td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_SERVER_4."</strong></td>";
			echo "	<td class=\"eden_users_td_data\"><input type=\"text\" name=\"league_team_server4\" size=\"40\" maxlength=\"80\" value=\"".stripslashes($ar_team['league_team_server4'])."\"></td>";
			echo "</tr>";
		}
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\"><strong>"._LEAGUE_TEAM_COMMENT."</strong></td>";
		echo "		<td class=\"eden_users_td_data\"><textarea name=\"league_team_comment\" cols=\"40\" rows=\"4\">".stripslashes($ar_team['league_team_comment'])."</textarea></td>";
		echo "	</tr>";
		if ($num_player[0] > 0){
			echo "	<tr>";
			echo "		<td class=\"eden_users_td_description\" valign=\"top\">&nbsp;</td>";
			echo "		<td class=\"eden_users_td_data\">";
			echo "			"._LEAGUE_TEAM_PLAYER_CHANGE_OPTIONS_HELP."<br>";
			echo "		</td>";
			echo "	</tr>";
		}
		echo "	<tr>";
		echo "		<td class=\"eden_users_td_description\" valign=\"top\">&nbsp;</td>";
		echo "		<td class=\"eden_users_td_data\">";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">";
		echo "			<input type=\"hidden\" name=\"mode\" value=\"".$form_mode."\">";
		echo "			<input type=\"submit\" value=\"".$form_submit."\" class=\"eden_button\">";
		echo "			</form>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueCheckPlayerStatus
*
*		Provereni jakou ma hrac pozici v teamu a zda je opravnen provadet zmeny
*		Funkce vraci - 	OC	= owner captain
*						OA	= owner assistant
*						OP	= owner player
*						O	= owner
*						C	= captain
*						A	= assistant
*						P	= player
*						False
*		$mode		= short (vraci jen zkratky), full (vraci cely status hrace v tymu)
*		$aid		= Admin ID hrace (vetsinou brane z $_SESSION['loginid']
*		$ltid		= League Team ID
*
***********************************************************************************************************/
function LeagueCheckPlayerStatus($mode = "short", $aid = 0,$ltid = 0){
	
	global $db_admin,$db_league_players,$db_league_teams;
	
	/* Zkontrolujeme zda je zadan Admin ID, ID teamu */
	if ($aid == 0){return false; exit;}
	if ($ltid == 0){return false; exit;}
	
	$res = mysql_query("SELECT lp.league_player_position_captain, lp.league_player_position_assistant, lp.league_player_position_player, lt.league_team_id 
	FROM $db_league_players AS lp 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=".(integer)$ltid." AND lt.league_team_owner_id=".(integer)$aid." 
	WHERE lp.league_player_admin_id=".(integer)$aid." AND lp.league_player_team_id=".(integer)$ltid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	/* Pokud funkce vrati vysledek */
	if ($res){
		$ar = mysql_fetch_array($res);
		
		/* Proverime zda je hrac vlastnikem tymu - to muze byt nezavisle na tom jestli je hracem */
		if ($ar['league_team_id']){	$result_short = "O"; $result_full = _LEAGUE_PLAYER_STATUS_OWNER;} else {$result_short = false;$result_full = false;}
		if ($result_full != false){$divider = "/";} else {$divider = "";}
		
		/* Proverime zda je hrac kapitanem tymu */
		if ($ar['league_player_position_captain'] == 1){
			$result_short .= "C";
			$result_full .= $divider._LEAGUE_PLAYER_STATUS_CAPTAIN;
		/* Proverime zda je hrac asistentem tymu */
		} elseif ($ar['league_player_position_assistant'] == 1){
			$result_short .= "A";
			$result_full .= $divider._LEAGUE_PLAYER_STATUS_ASSISTANT;
		/* Proverime zda je hrac hracem v tymu */
		} elseif ($ar['league_player_position_player'] == 1){
			$result_short .= "P";
			$result_full .= $divider._LEAGUE_PLAYER_STATUS_PLAYER;
		} else {
			$result_short = false;
			$result_full = false;
		}
	} else {
		return false; exit;
	}
	
	/* Vratime data podle modu */
	if ($mode == "short"){
		return $result_short;
	} else {
		return $result_full;
	}
	exit;
}
/***********************************************************************************************************
*
*		LeagueCheckIfLocked
*
*		Provereni zda je liga locked nebo ne
*		Funkce vraci 	0 = Neni locked
*						1 = Je locked
*
*		$mode			-	Mod ID (toto ID pouzijeme abychom se podivali zda je sub team (team) registrovany v dane lize
*						L = Liga
*						P = Player
*						S = Sub team
*		$id				-	League ID
*
***********************************************************************************************************/
function LeagueCheckIfLocked($mode = "", $id = 0){
	
	global $db_league_leagues,$db_league_players,$db_league_teams_sub_leagues,$db_league_teams_sub;
	
	//if ((integer)$id == 0){return "1"; exit;}
	
	//$res_leagues = mysql_query("SELECT league_league_id FROM $db_league_league WHERE league_league_lock=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	//$ar_leagues = mysql_num_rows($res_leagues);
	
	switch ($mode){
	 	case "L":
	 		$res = mysql_query("SELECT league_league_lock FROM $db_league_leagues WHERE league_league_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar = mysql_fetch_array($res);
			if ($ar['league_league_lock'] == 0){return False;} else {return True;}
	 	break;
		case "P":
			$res_p = mysql_query("
			SELECT ltsl.league_teams_sub_league_league_id 
			FROM $db_league_players AS lp 
			JOIN $db_league_teams_sub_leagues AS ltsl ON ltsl.league_teams_sub_league_team_sub_id=lp.league_player_team_sub_id 
			WHERE league_player_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while ($ar_p = mysql_fetch_array($res_p)){
				$res = mysql_query("SELECT COUNT(*) FROM $db_league_leagues WHERE league_league_id=".(integer)$ar_p['league_teams_sub_league_league_id']." AND league_league_lock=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar = mysql_fetch_array($res);
				$num += $ar[0];
			}
			if ($num == 0){return False;} else {return True;}
	 	break;
		case "S":
			$res_s = mysql_query("SELECT league_teams_sub_league_league_id FROM $db_league_teams_sub_leagues WHERE league_teams_sub_league_team_sub_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while ($ar_s = mysql_fetch_array($res_s)){
				$res = mysql_query("SELECT COUNT(*) FROM $db_league_leagues WHERE league_league_id=".(integer)$ar_s['league_teams_sub_league_league_id']." AND league_league_lock=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar = mysql_fetch_array($res);
				$num += $ar[0];
			}
			if ($num == 0){return False;} else {return True;}
	 	break;
	}
}
/***********************************************************************************************************
*
*		LeagueDelPlayerFromAllowed
*
*		Odstranime hrace ze soupisky povolenych hracu v ligach, ktere maji nastavene limity pro pocet hracu
*
*		$ltsid			-	Team Sub ID
*		$pid			-	Player ID
*
***********************************************************************************************************/
function LeagueDelPlayerFromAllowed ($ltsid = 0, $pid = 0){
	
	global $db_league_teams_sub_leagues;
	
	if ($ltsid == 0 || $pid == 0){
		// Nic se nedeje
		return false;
	} else {
		// Odstranime hrace ze vsech lig do kterych je dany podtym registrovany
		$res = mysql_query("SELECT league_teams_sub_league_id, league_teams_sub_league_players FROM $db_league_teams_sub_leagues WHERE league_teams_sub_league_team_sub_id=".(integer)$ltsid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		while ($ar = mysql_fetch_array($res)){
			$allowed_players = str_replace($pid."#", "", $ar['league_teams_sub_league_players']);
			mysql_query("UPDATE $db_league_teams_sub_leagues SET league_teams_sub_league_players='".$allowed_players."' WHERE league_teams_sub_league_id=".(integer)$ar['league_teams_sub_league_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		}
		return True;
	}
}
/***********************************************************************************************************
*
*		LeagueMenuPersonality
*
*		Zobrazi uzivatelskeho menu po prihlaseni
*
***********************************************************************************************************/
function LeagueMenuPersonality(){
	
	global $db_admin,$db_admin_contact,$db_country,$db_league_players,$db_league_teams,$db_league_teams_sub,$db_clan_games;
	global $db_league_requests;
	global $eden_cfg;
	global $url_admins,$url_flags,$url_games;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	// Pomoci LEFT JOIN docilime toho ze jednim dotazem do DB ziskame data
	// a pokud nektera z nich nejsou dostupna (uzivatel neni clenem zadneho teamu) tak se zobrazi jen ty co dostupna jsou 
	
	$res_player = mysql_query("SELECT lp.league_player_id, lp.league_player_team_id, lp.league_player_team_sub_id, lp.league_player_position_captain, a.admin_nick, a.admin_userimage, a.admin_team_own_id, c.country_shortname 
	FROM $db_admin AS a 
	JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
	JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	LEFT JOIN $db_league_players AS lp ON lp.league_player_admin_id=a.admin_id 
	WHERE admin_id=".(integer)$_SESSION['loginid']."
	GROUP BY lp.league_player_team_sub_id") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player_a = mysql_fetch_array($res_player);
	if ($ar_player_a['admin_userimage']){$player_img = $ar_player_a['admin_userimage'];} else {$player_img = "0000000001.gif";}
	
	echo "<img src=\"".$url_admins.$player_img."\" class=\"eden_personality_menu_img\" alt=\"".stripslashes($ar_player_a['admin_nick'])."\">";
	echo "<div class=\"eden_personality_menu_player\"><img src=\"".$url_flags.$ar_player_a['country_shortname'].".gif\"> <a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".(integer)$_SESSION['loginid']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">".stripslashes($ar_player_a['admin_nick'])."</a></div>";
	echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=user_edit&amp;mode=edit_user&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._USER_EDIT."</a></div>";
	
	// *********************
	// PLAYER ACCOUNT   	
	// *********************
	echo "<br><div class=\"eden_personality_menu_headline\">"._LEAGUE_PLAYER_ACC_PLAYER."</div>";
	echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=league_team&mode=player_home&lpid=".$ar_player_a['league_player_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\"><strong>".stripslashes($ar_player_a['admin_nick'])."</strong></a></div>";
	if ($ar_player_a['league_player_id']) {echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=user_edit&amp;mode=guids&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_GUIDS."</a></div>";}
	echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=league_team&amp;mode=team_log_player&amp;pid=".$_SESSION['loginid']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_LOG."</a></div>";
	// Look if there any active league which to can team join
	echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=player_league_reg&amp;lpid=".$ar_player_a['league_player_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_LEAGUE_REG_TO."</a></div><br>";
	
	// *********************
	// TEAM ACCOUNT			
	// *********************
	echo "<div class=\"eden_personality_menu_headline\">"._LEAGUE_PLAYER_ACC_TEAMS."</div>";
	// Calculate if player is already owner of some team, if not, it show him menu for adding team
	$res_owner = mysql_query("SELECT COUNT(*) FROM $db_league_teams WHERE league_team_owner_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_owner = mysql_fetch_array($res_owner);
	if ($num_owner[0] == 0){
		echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team_reg&amp;gid=1&amp;lid=1&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_ADD_TEAM."</a></div>";
	}
	echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=league_team&amp;mode=team_player_join&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_JOIN_TEAM."</a></div>";
	echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=league_team&amp;mode=team_draft&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_DRAFT."</a></div>";
	
	mysql_data_seek($res_player,0);
	$i=1;
	$y=0;
	$x=1;
	$teams = array();
	while ($ar_player = mysql_fetch_array($res_player)){
		// If player is no member of any team - it doesn't show to him
  		if ($ar_player['league_player_team_id'] > 0 || $ar_player['admin_team_own_id'] > 0){
			
			// Calculate number of games
			$res_games = mysql_query("SELECT COUNT(*) FROM $db_clan_games WHERE clan_games_active=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_games = mysql_fetch_array($res_games);
			
			$res_team = mysql_query("SELECT lt.league_team_name, lt.league_team_id 
			FROM $db_league_teams AS lt 
			WHERE league_team_id=".(integer)$ar_player['league_player_team_id']." OR league_team_id=".(integer)$ar_player['admin_team_own_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_team = mysql_fetch_array($res_team);
			
			if ((in_array($ar_team['league_team_id'],$teams) || $ar_player['league_player_team_id'] == 0) && $ar_player['admin_team_own_id'] == 0){
				// Show nothing
				echo $ar_team['league_team_id']." - ".$teams[0];
			} else {
				$teams[$y] = $ar_team['league_team_id'];
				
				echo "<div class=\"eden_personality_menu_team\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_team['league_team_id']."&amp;pid=".(integer)$ar_player['league_player_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">".$ar_team['league_team_name']."</a></div>";
			   	echo "<div class=\"eden_personality_menu_list\">".LeagueCheckPlayerStatus("full",$_SESSION['loginid'],$ar_player['league_player_team_id'])."</div>";
				if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ar_player['league_player_team_id'],0) == $ar_team['league_team_id'] && $num_games[0] > 1){echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_game_add&amp;ltid=".$ar_player['league_player_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_GAME_ADD."</a></div>";}
				echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_guids&amp;ltid=".$ar_team['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_GUIDS."</a></div>";
				echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_log_team&amp;ltid=".$ar_team['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_LOG."</a></div>";
				// Edit of team setup allowed only for Owner
				if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ar_player['league_player_team_id'],0) == $ar_team['league_team_id']){
					echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_edit&amp;ltid=".$ar_team['league_team_id']."&amp;ltsid=".$ar_player['league_player_team_sub_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_EDIT_TEAM."</a></div>";
				}
				$res_sub_team = mysql_query("SELECT lts.league_team_sub_id, g.clan_games_shortname, g.clan_games_game 
				FROM $db_league_teams_sub AS lts 
				JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
				WHERE lts.league_team_sub_team_id=".(integer)$ar_team['league_team_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_sub_team = mysql_num_rows($res_sub_team);
				
				// Show singular or plural
				if ($num_sub_team == 1){
					echo "<div class=\"eden_personality_menu_game\">"._LEAGUE_PLAYER_GAME.": </div>";
				} else {
					echo "<div class=\"eden_personality_menu_game\">"._LEAGUE_PLAYER_GAMES.": </div>";
				}
				
				// Show team games 
				while ($ar_sub_team = mysql_fetch_array($res_sub_team)){
					$res_player_sub = mysql_query("SELECT league_player_id, league_player_team_id, league_player_team_sub_id 
					FROM $db_league_players 
					WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_team_sub_id=".(integer)$ar_sub_team['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					$ar_player_sub = mysql_fetch_array($res_player_sub);
					echo "<div class=\"eden_personality_menu_game_2\">";
						// Link is active for games/subteams where player is active
						if ($ar_player_sub['league_player_team_sub_id'] == $ar_sub_team['league_team_sub_id']){
							echo "<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_admin&amp;ltid=".$ar_team['league_team_id']."&amp;ltsid=".$ar_player_sub['league_player_team_sub_id']."&amp;pid=".(integer)$ar_player_sub['league_player_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\"><img src=\"".$url_games.$ar_sub_team['clan_games_shortname'].".gif\" alt=\"".stripslashes($ar_sub_team['clan_games_game'])."\" title=\"".stripslashes($ar_sub_team['clan_games_game'])."\"> ".stripslashes($ar_sub_team['clan_games_game'])."</a>";
						} else {
							echo "<img src=\"".$url_games.$ar_sub_team['clan_games_shortname'].".gif\" alt=\"".stripslashes($ar_sub_team['clan_games_game'])."\" title=\"".stripslashes($ar_sub_team['clan_games_game'])."\"> ".stripslashes($ar_sub_team['clan_games_game']);
						}
					echo "	</div>";
					if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ar_player_sub['league_player_team_id'],0) == $ar_team['league_team_id'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ar_player_sub['league_player_team_id'],$ar_sub_team['league_team_sub_id']) == 1){
						echo "<div class=\"eden_personality_menu_list\"><a href=\"index.php?action=league_team&amp;mode=team_player_check&amp;ltid=".$ar_team['league_team_id']."&amp;ltsid=".$ar_sub_team['league_team_sub_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_PLAYER_ADD."</a></div>";
					}
					// Check if there is any league to which team can join - Show to Owners and Captains only
					if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ar_player_sub['league_player_team_id'],0) == $ar_team['league_team_id'] || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ar_player_sub['league_player_team_id'],$ar_sub_team['league_team_sub_id']) == 1){
						echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_league_reg&amp;ltid=".$ar_team['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_LEAGUE_REG_TO."</a></div><br>";
					}
				}
				$i++;
				$y++;
			}
			$x++;
		}
	}
	/* Zobrazeni cekajicich prihlasek do teamu */
	$res_pending = mysql_query("SELECT lr.league_request_id, lt.league_team_name 
	FROM $db_league_requests AS lr 
	JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lr.league_request_team_sub_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
	WHERE league_request_admin_id=".(integer)$_SESSION['loginid']." AND league_request_action=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$i=0;
	while ($ar_pending = mysql_fetch_array($res_pending)){
		if ($i == 0){echo "<div class=\"eden_personality_menu_headline\">"._LEAGUE_TEAM_PENDING."</div>";}
		echo "<div class=\"eden_personality_menu_list\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_player_confirm&amp;lrid=".$ar_pending['league_request_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">".$ar_pending['league_team_name']."</a></div>";
		$i++;
	}
}
/***********************************************************************************************************
*
*		LeaguePlayerAcc
*
*		Zobrazeni uctu hrace
*
*		$pid		= Player/Admin ID
*		$td_width	= Sirka radku s komentari
*		$tbl_width	= Sirka tabulky s komentari
*
***********************************************************************************************************/
function LeaguePlayerAcc($pid = 0, $td_width = 500, $tbl_width = 505){
	
	global $db_admin,$db_admin_poker,$db_league_players,$db_league_teams,$db_admin_contact,$db_country,$db_poker_variants,$db_poker_cardrooms;
	global $url_admins,$url_flags;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($pid == 0){$player_id = $_SESSION['loginid'];} else {$player_id = $pid;}
	
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, a.admin_reg_date, a.admin_userimage, ap.admin_poker_fav_cardrooms, ap.admin_poker_fav_variants, ap.admin_poker_fav_player, lp.league_player_admin_id, lp.league_player_team_id, lt.league_team_name, ac.admin_contact_icq, ac.admin_contact_xfire, ac.admin_contact_skype, ac.admin_contact_msn, ac.admin_contact_birth_day, c.country_name, c.country_shortname 
	FROM $db_admin AS a 
	JOIN $db_admin_poker AS ap ON ap.aid=a.admin_id
	LEFT JOIN ($db_league_players AS lp) ON (lp.league_player_admin_id=a.admin_id) 
	LEFT JOIN ($db_league_teams AS lt) ON (lt.league_team_id=lp.league_player_team_id) 
	LEFT JOIN ($db_admin_contact AS ac) ON (ac.aid=a.admin_id) 
	LEFT JOIN ($db_country AS c) ON (c.country_id=ac.admin_contact_country) 
	WHERE a.admin_id=".(integer)$player_id) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	if ($ar_player['admin_contact_birth_day'] == 0){
		$birthday = "Nebyl zadán den narození";
	} else {
		$datetime = $ar_player['admin_contact_birth_day'];
		$birthday =	$datetime[6].$datetime[7].".".$datetime[4].$datetime[5].".".$datetime[0].$datetime[1].$datetime[2].$datetime[3];
	}
	$reg_date = FormatDatetime($ar_player['admin_reg_date'],"d.m.Y H:i:s");
	if ($ar_player['league_team_name'] == ""){
		$team_name = "Nejste členem žádného teamu";
	} else {
		$team_name = stripslashes($ar_player['league_team_name']);
	}
	if ($ar_player['admin_userimage']){$player_img = $ar_player['admin_userimage'];} else {$player_img = "0000000001.gif";}
	
	// Oblibene herny
	$poker_cardrooms = explode("||",$ar_player['admin_poker_fav_cardrooms']);
	$poker_cardrooms_num = count($poker_cardrooms);
	$poker_cardrooms_ok = array();
	for ($i=0;$poker_cardrooms_num > $i;$i++){
		if($poker_cardrooms[$i] != ""){$poker_cardrooms_ok[$i] = $poker_cardrooms[$i];}
	}
	$poker_cardrooms_num_ok = count($poker_cardrooms_ok);
	// Oblibene varianty pokeru
	$poker_variants = explode("||",$ar_player['admin_poker_fav_variants']);
	$poker_variants_num = count($poker_variants);
	$poker_variants_ok = array();
	for ($i=0;$poker_variants_num > $i;$i++){
		if($poker_variants[$i] != ""){$poker_variants_ok[$i] = $poker_variants[$i];}
	}
	$poker_variants_num_ok = count($poker_variants_ok);
	
	// Oblibeni hraci
	//admin_poker_fav_player 
	echo "<div class=\"eden_league\">";
	echo "	<div id=\"eden_league_player_personal_1\">";
	echo "		<div style=\"width:21px;height:30px;float:left;vertical-align:bottom;\"><img src=\"".$url_flags.$ar_player['country_shortname'].".gif\" width=\"18\" height=\"12\" style=\"margin-top:2px;\" alt=\"".stripslashes($ar_player['country_shortname'])."\" title=\"".stripslashes($ar_player['country_shortname'])."\"></div><div style=\"width:179px;height:30px;float:left;\"><h2 style=\"margin:0px 0px 0px 0px;\">".stripslashes($ar_player['admin_nick'])."</h2></div>";
	echo "		<div style=\"width:175px;text-align:center\"><img src=\"".$url_admins.$player_img."\" class=\"eden_personality_menu_img\" alt=\"".stripslashes($ar_player['admin_nick'])."\"></div>";
	echo "	</div>";
	echo "	<div id=\"eden_league_player_personal_2\">";
	echo "		<table id=\"eden_league_player_personal_profile\" cellpadding=\"3\" cellspacing=\"2\" border=\"0\">";
	echo "			<tr>";
	echo "				<td><h3>Profil</h3></td>";
	echo "				<td>"; if ($_SESSION['u_status'] == "admin") {echo "<a href=\"index.php?action=league_team&amp;mode=team_log_player&amp;pid=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_PLAYER_LOG."</a>";} echo "</td>";
	echo "			</tr>";
	$player_data = array(
		array ("ID",$ar_player['admin_id']),
   		array ("Nick",stripslashes($ar_player['admin_nick'])),
		array ("Team",$team_name),
		array ("Země",stripslashes($ar_player['country_name'])),
		array ("ICQ",stripslashes($ar_player['admin_contact_icq'])),
		array ("Xfire",stripslashes($ar_player['admin_contact_xfire'])),
		array ("MSN",stripslashes($ar_player['admin_contact_msn'])),
		array ("Skype",stripslashes($ar_player['admin_contact_skype'])),
		array ("Narozeniny",$birthday),
		array ("Účet založen",$reg_date),
	);
	$player_data_num = count($player_data);
	$i=0;
	while ($player_data_num > $i){
		if (!empty($player_data[$i][1])){
			if ($i % 2 == 0){$td_class = "eden_league_player_personal_names_odd";} else {$td_class =  "eden_league_player_personal_names_even";}
			echo " 	<tr>";
			echo " 		<td class=\"".$td_class."\">".$player_data[$i][0]."</td>";
			echo "		<td class=\"".$td_class."\">".$player_data[$i][1]."</td>";
			echo "	</tr>";
		}
		$i++;
	}
	if ($poker_variants_num_ok > 0 && $poker_variants_num_ok != ""){
		echo "	 	<tr>";
		echo "	 		<td colspan=\"2\">&nbsp;</td>";
		echo "	 	</tr>";
		echo "	 	<tr>";
		echo "	 		<td colspan=\"2\"><h3>Oblíbené varianty</h3></td>";
		echo "	 	</tr>";
		for ($i=0;$poker_variants_num_ok > $i;$i++){
			$res_variants = mysql_query("SELECT poker_variant_name FROM $db_poker_variants WHERE poker_variant_id=".(integer)$poker_variants_ok[$i]) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_variants = mysql_fetch_array($res_variants);
			if ($i % 2 == 0){$suda = "odd";} else {$suda = "even";}
			echo "	  	<tr>";
			echo "	  		<td class=\"eden_league_player_personal_names_".$suda."\">&nbsp;</td>";
			echo "	  		<td class=\"eden_league_player_personal_datas_".$suda."\">".$ar_variants['poker_variant_name']."</td>";
			echo "	  	</tr>";
		}
	}
	if ($poker_cardrooms_num_ok > 0 && $poker_cardrooms_num_ok != ""){
		echo "		<tr>";
		echo "			<td colspan=\"2\">&nbsp;</td>";
		echo "		</tr>";
		echo "		<tr>";
		echo "			<td colspan=\"2\"><h3>Oblíbené herny</h3></td>";
		echo "		</tr>";
		for ($i=0;$poker_cardrooms_num_ok > $i;$i++){
			$res_cardrooms = mysql_query("SELECT poker_cardroom_name FROM $db_poker_cardrooms WHERE poker_cardroom_id=".(integer)$poker_cardrooms_ok[$i]) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_cardrooms = mysql_fetch_array($res_cardrooms);
			if ($i % 2 == 0){$suda = "odd";} else {$suda = "even";}
			echo "		<tr>";
			echo "			<td class=\"eden_league_player_personal_names_".$suda."\">&nbsp;</td>";
			echo "			<td class=\"eden_league_player_personal_datas_".$suda."\">".$ar_cardrooms['poker_cardroom_name']."</td>";
			echo "		</tr>";
		}
	}
	if ($ar_player['admin_poker_fav_player'] != ""){
		echo "		<tr>";
		echo "			<td colspan=\"2\">&nbsp;</td>";
		echo "		</tr>";
		echo "		<tr>";
		echo "			<td colspan=\"2\"><h3>Oblíbený hráč</h3></td>";
		echo "		</tr>";
		echo "		<tr>";
		echo "			<td class=\"eden_league_player_personal_names_odd\">&nbsp;</td>";
		echo "			<td class=\"eden_league_player_personal_datas_odd\">".stripslashes($ar_player['admin_poker_fav_player'])."</td>";
		echo "		</tr>";
	}
	echo "			</table>";
   	echo "		</div>";
	echo "		<div id=\"eden_league_player_personal_comm\">";
	echo "			<a href=\"index.php?action=player&amp;mode=player_awards&amp;id=".$player_id."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_PLAYER_AWARDS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	echo "			<a href=\"index.php?action=forum&faction=pm&pm_rec=".$player_id."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><img src=\"./images/sys_message.gif\" width=\"15\" height=\"10\" alt=\""._CMN_PM_SEND_PM."\" title=\""._CMN_PM_SEND_PM."\"> "._CMN_PM_SEND_PM."</a>";
	echo "		</div>";
	echo "		<div id=\"eden_league_player_personal_comm\">";
			Comments($player_id,"user",90,0,0,$td_width,$tbl_width);
	echo "		</div>";
	echo "	</div>";
}
/***********************************************************************************************************
*
*		UserEditGuids
*
*		Zobrazi GUIDs / pridani a editace GUIDu
*		$mode		= guids (zobrazeni seznamu GUIDu)
*					= guid_add (pridani noveho GUIDu)
*					= guid_edit (editace stavajiciho GUIDu)
*
***********************************************************************************************************/
function UserEditGuid($mode){
	
	global $db_admin,$db_admin_guids,$db_league_guids;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	switch ($mode){
		case "guids":
			$res_guids = mysql_query("SELECT ag.admin_guid_guid, ag.admin_guid_league_guid_id, ag.admin_guid_id, lg.league_guid_name 
			FROM $db_admin_guids AS ag 
			JOIN $db_league_guids AS lg ON lg.league_guid_id=ag.admin_guid_league_guid_id 
			WHERE ag.aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_guids = mysql_num_rows($res_guids);
			if ($num_guids > 0){
				echo "<div>";
				echo "	<h2>"._LEAGUE_PLAYER_GUID_CURRENT_TITLE."<span style=\"font-weight:normal;\"></span></h2>";
				echo "	<table class=\"eden_personality_guids\" cellspacing=\"2\" cellpadding=\"3\" border=\"0\">";
				echo "		<tr class=\"eden_personality_guids_title\">";
				echo "			<td class=\"eden_personality_guids_title\">"._LEAGUE_PLAYER_GUID_TYPE."</td>";
				echo "			<td class=\"eden_personality_guids_title\">"._LEAGUE_PLAYER_GUID_CURRENT."</td>";
				echo "		</tr>";
				$i=1;
				$y=0;
				$guids_array = array();
				while($ar_guids = mysql_fetch_array($res_guids)){
					/* Do pole ulozime ID jiz registrovanych GUIDu k urcitym hram */
					$guids_array[$y] = $ar_guids['admin_guid_league_guid_id'];
					if ($i % 2 == 0){$odd_even = "eden_personality_guids_odd";} else {$odd_even = "eden_personality_guids_even";}
				   		echo "	<tr class=\"".$odd_even."\">";
						echo "		<td>".stripslashes($ar_guids['league_guid_name'])."</td>";
						echo "		<td><a href=\"index.php?action=user_edit&amp;mode=guid_edit&amp;agid=".$ar_guids['admin_guid_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">".stripslashes($ar_guids['admin_guid_guid'])."</a></td>";
						echo "	</tr>";
					$i++;
					$y++;
				}
				echo "	</table>";
				echo "</div>";
			}
			echo "<div class=\"eden_personality\">
				<h2>"._LEAGUE_PLAYER_GUID_REG_NEW."</h2>";
				/* Spocitame pocet aktivnich typu GUIDu a pocet hracem registrovanych a zobrazime ty zbyvajici */
				$res_guids_num = mysql_query("SELECT aid FROM $db_admin_guids WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_guids_num = mysql_num_rows($res_guids_num);
				$res_league_guids_num = mysql_query("SELECT league_guid_id FROM $db_league_guids WHERE league_guid_active=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_league_guids_num = mysql_num_rows($res_league_guids_num);
				if ($ar_league_guids_num > $ar_guids_num){
					echo "<p><strong>"._LEAGUE_PLAYER_GUID_REG_SELECT."</strong><br></p>";
					echo "<form action=\"index.php?action=user_edit&amp;mode=guid_add&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
					echo "	<select name=\"guid\" size=\"1\">";
							$res_league_guids = mysql_query("SELECT league_guid_id, league_guid_name FROM $db_league_guids WHERE league_guid_active=1 ORDER BY league_guid_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
							while($ar_league_guids = mysql_fetch_array($res_league_guids)){
								/* Zpbrazeni jen typu GUIDu ktere jsete nema hrac registrovany */
								if (!in_array($ar_league_guids['league_guid_id'],$guids_array)){
									echo "<option value=\"".$ar_league_guids['league_guid_id']."\">".$ar_league_guids['league_guid_name']."</option>";
								}
							}
					echo "	</select>";
					echo "	<input type=\"submit\" value=\""._LEAGUE_PLAYER_GUID_REG."\" class=\"eden_button\">";
					echo "</form>";
				} else {
					/* V pripade ze ,a hrac jiz registrovany vsechny dostupne hry zobrazi se o tomto upozorneni */
					echo _LEAGUE_PLAYER_GUID_NO_GAME_TO_REG;
				}
			echo "</div>";
		break;
		case "guid_add":
			$res_league_guids = mysql_query("SELECT league_guid_id, league_guid_game_id, league_guid_name, league_guid_sample, league_guid_help FROM $db_league_guids WHERE league_guid_id=".(integer)$_POST['guid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_league_guids = mysql_fetch_array($res_league_guids);
			echo "<div class=\"eden_personality\">";
			echo "	<h2>"._LEAGUE_PLAYER_GUID_REG_NEW."</h2>";
			echo "	"._LEAGUE_PLAYER_GUID_NOT_YET_1.stripslashes($ar_league_guids['league_guid_name'])._LEAGUE_PLAYER_GUID_NOT_YET_2."<br><br>";
			echo "	"._LEAGUE_PLAYER_GUID_SAMPLE_1.stripslashes($ar_league_guids['league_guid_name'])._LEAGUE_PLAYER_GUID_SAMPLE_2."<br><br>";
			echo "	<span class=\"eden_personality_guid_sample\">".stripslashes($ar_league_guids['league_guid_sample'])."</span><br><br>";
			echo "	".stripslashes($ar_league_guids['league_guid_help'])."<br><br>";
			echo "		<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=user_edit&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "		<strong>"._LEAGUE_PLAYER_GUID_VALUE."</strong><br>";
			echo "		<input name=\"league_guid\" size=\"60\" maxlength=\"80\"><br><br>";
			echo "		<input type=\"hidden\" name=\"league_guid_id\" value=\"".(integer)$ar_league_guids['league_guid_id']."\">";
			echo "		<input type=\"hidden\" name=\"league_guid_game_id\" value=\"".(integer)$ar_league_guids['league_guid_game_id']."\">";
			echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "		<input type=\"hidden\" name=\"mode\" value=\"league_guid_add\">";
			echo "		<input type=\"submit\" value=\""._LEAGUE_PLAYER_GUID_REG."\" class=\"eden_button\">";
			echo "		</form>";
			echo "</div>";
		break;
		case "guid_edit":
			echo "<script type=\"text/javascript\">\n";
			echo "<!--\n";
			echo "function CheckGuid(formular) {\n";
			echo "	if (formular.league_guid.value == \"\"){\n";
			echo "		alert (\""._ERR_LEAGUE_GUID_NO_GUID."\");\n";
			echo "		return false;\n";
			echo "	} else if (formular.league_guid.value.length < 4){\n";
			echo "		alert (\""._ERR_LEAGUE_GUID_GUID_SHORT."\");\n";
			echo "		return false;\n";
			echo "	} else if (formular.league_guid_reason.value == \"\"){\n";
			echo "		alert (\""._ERR_LEAGUE_GUID_NO_REASON."\");\n";
			echo "		return false;\n";
			echo "	} else if (formular.league_guid_reason.value.length < 4){\n";
			echo "		alert (\""._ERR_LEAGUE_GUID_REASON_SHORT."\");\n";
			echo "		return false;\n";
			echo "	} else {\n";
			echo "		return true;\n";
			echo "	}\n";
			echo "}\n";
			echo "//-->\n";
			echo "</script>";
			$res_league_guids = mysql_query("SELECT lg.league_guid_id, lg.league_guid_game_id, lg.league_guid_name, lg.league_guid_sample, lg.league_guid_help, ag.admin_guid_guid, ag.admin_guid_id 
			FROM $db_league_guids AS lg 
			JOIN ($db_admin_guids AS ag) 
			ON (lg.league_guid_id=ag.admin_guid_league_guid_id) 
			WHERE admin_guid_id=".(integer)$_GET['agid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_league_guids = mysql_fetch_array($res_league_guids);
			echo "<div class=\"eden_personality\">";
			echo "	<h2>"._LEAGUE_PLAYER_GUID_EDIT."</h2>";
			echo "	"._LEAGUE_PLAYER_GUID_CURRENT_EDIT."<strong>".stripslashes($ar_league_guids['admin_guid_guid'])."</strong><br><br>";
			echo "	"._LEAGUE_PLAYER_GUID_SAMPLE_1.stripslashes($ar_league_guids['league_guid_name'])._LEAGUE_PLAYER_GUID_SAMPLE_2."<br><br>";
			echo "	<span class=\"eden_personality_guid_sample\">".stripslashes($ar_league_guids['league_guid_sample'])."</span><br><br>";
			echo "	".stripslashes($ar_league_guids['league_guid_help'])."<br><br>";
			echo "		<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=user_edit&project=".$_SESSION['project']."&amp;agid=".$_GET['agid']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return CheckGuid(this)\">";
			echo "		<strong>"._LEAGUE_PLAYER_GUID_VALUE."</strong><br>";
			echo "		<input name=\"league_guid\" size=\"60\" maxlength=\"80\" value=\"".stripslashes($ar_league_guids['admin_guid_guid'])."\"><br><br>";
			echo "		<strong>"._LEAGUE_PLAYER_GUID_REASON."</strong><br>";
			echo "		<textarea name=\"league_guid_reason\" cols=\"55\" rows=\"4\"></textarea><br><br>";
			echo "		<input type=\"hidden\" name=\"league_guid_old\" value=\"".stripslashes($ar_league_guids['admin_guid_guid'])."\">";
			echo "		<input type=\"hidden\" name=\"league_guid_id\" value=\"".(integer)$ar_league_guids['league_guid_id']."\">";
			echo "		<input type=\"hidden\" name=\"league_guid_game_id\" value=\"".(integer)$ar_league_guids['league_guid_game_id']."\">";
			echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "		<input type=\"hidden\" name=\"mode\" value=\"league_guid_edit\">";
			echo "		<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">";
			echo "		</form>";
			echo "</div>";
		break;
	}
}
/***********************************************************************************************************
*
*		LeaguePlayerAdd
*
*		Pridani hrace do teamu
*
***********************************************************************************************************/
function LeaguePlayerAdd(){
	
	global $db_league_players,$db_league_teams,$db_league_teams_sub,$db_league_requests;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_GET['ltsid'] = AGet($_GET,'ltsid');
	$_GET['mode'] = AGet($_GET,'mode');
	$_GET['pid'] = AGet($_GET,'pid');
	
	/* POZOR DATA PRICHAZEJI Z eden.save.php*/
	/* Kontrola zda uz je hrac v sub teamu registrovan */
	$res_player = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_admin_id=".(integer)$_GET['pid']." AND league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_player = mysql_fetch_array($res_player);
	
	/* Spocitame kolik je jiz v sub teamu registrovano hracu */
	$res_team_sub_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_team_sub_players = mysql_fetch_array($res_team_sub_players);
	
	$res_team = mysql_query("SELECT lt.league_team_name 
	FROM $db_league_teams_sub AS lts 
	JOIN $db_league_teams AS lt ON lts.league_team_sub_team_id=lt.league_team_id 
	WHERE league_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_team = mysql_fetch_array($res_team);
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_TEAM_PLAYER_ADD."</h2>";
	if ($_GET['mode'] == "team_player_check"){
		echo _LEAGUE_TEAM_PLAYER_ADD_HELP_1."<strong>".stripslashes($ar_team['league_team_name'])."</strong><br>"._LEAGUE_TEAM_PLAYER_ADD_HELP_2;
	}
	if ($_GET['mode'] == "team_player_add"){
		$res_team_old = mysql_query("SELECT lp.league_player_join_date 
		FROM $db_league_teams_sub AS lts 
		JOIN $db_league_players AS lp ON lp.league_player_game_id=lts.league_team_sub_game_id AND lp.league_player_admin_id=".(integer)$_GET['pid']." 
		WHERE league_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_team_old = mysql_fetch_array($res_team_old);
		
		$res_request = mysql_query("SELECT COUNT(*) FROM $db_league_requests WHERE league_request_admin_id=".(integer)$_GET['pid']." AND league_request_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_request = mysql_fetch_array($res_request);
		
		/* Kontrola lhuty od prestupu - 24h */
		$limit = LeagueCheck24($ar_team_old['league_player_join_date']);
		/* Proverime hrace zda neni v 24 hodinove lhute od posledniho prestupu */
		if ($limit == False){
			echo "<p>"._LEAGUE_TEAM_PLAYER_CHECK_24.date('d.m.Y H:i:s', $date_estimate).".</p>";
		/* Pokud hrac neni jiz v tymu zobrazi se otazka zda ho chceme pridat do teamu */
		} elseif ($num_player[0] == 0){
			/* Pokud jiz byla pozvanka hraci jednou odeslana, zobrazi se upozorneni */
			if ($num_request[0] != 0) {
				echo "<p>"._LEAGUE_TEAM_PLAYER_ALREADY_REQUESTED."</p>";
			} else {
				echo "<p>"._LEAGUE_TEAM_PLAYER_CHECK_HELP_1."<strong>".$_GET['pn']."</strong>"._LEAGUE_TEAM_PLAYER_CHECK_HELP_2."</p>";
			}
		/* Pokud jiz hrac v teamu je zobrazi se upozorneni */
		} else {
			echo "<p>"._LEAGUE_TEAM_PLAYER_ALREADY_IN."</p>";
		}
		echo "<table cellspacing=\"2\" cellpadding=\"3\" class=\"eden_league_table\">";
		echo "	<tr class=\"suda\">";
		echo "		<td>"._LEAGUE_PLAYER_ID."</td>";
		echo "		<td>".$_GET['pid']."</td>";
		echo "	</tr>";
		echo "	<tr class=\"licha\">";
		echo "		<td>"._LEAGUE_PLAYER_NICK."</td>";
		echo "		<td>".$_GET['pn']."</td>";
		echo "	</tr>";
		echo "	<tr class=\"suda\">";
		echo "		<td>"._LEAGUE_PLAYER_EMAIL."</td>";
		echo "		<td>".$_GET['pe']."</td>";
		echo "	</tr>";
		echo "</table><br clear=\"all\">";
		/* Pokud jiz hrac v teamu je, nezobrazi se tlacitko pro pridani do teamu */
		if ($num_player[0] == 0 && $num_request[0] == 0){
			echo "<div style=\"margin-top:1em;\">";
			echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "		<input type=\"hidden\" name=\"pid\" value=\"".(integer)$_GET['pid']."\">";
			echo "		<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$_GET['ltid']."\">";
			echo "		<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$_GET['ltsid']."\">";
			echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "		<input type=\"hidden\" name=\"mode\" value=\"team_player_add\">";
			echo "		<input type=\"submit\" value=\""._LEAGUE_TEAM_PLAYER_ADD."\" class=\"eden_button\">";
			echo "	</form>";
			echo "</div>";
		}
	}
	echo "	<br clear=\"both\"><br>";
	if ($_GET['mode'] == "team_player_add"){echo "<h2>"._LEAGUE_TEAM_PLAYER_SEARCH_AGAIN."</h2><br>";}
	echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
	echo "		<strong>"._LEAGUE_TEAM_PLAYER_ID."</strong><br>";
	echo "		<p><input type=\"text\" id=\"league_player_id\" name=\"league_player_id\" value=\"\" size=\"50\" autocomplete=\"off\" onkeyup=\"ajax_showOptions(this,'getPlayerNickByLetters=1&project=".$_SESSION['project']."',event)\"></p>";
	echo "		"._LEAGUE_TEAM_PLAYER_OR."<br><br>";
	echo "		<strong>"._LEAGUE_TEAM_PLAYER_EMAIL."</strong><br>";
	echo "		<p><input type=\"text\" id=\"league_player_email\" name=\"league_player_email\" value=\"\" size=\"50\" autocomplete=\"off\" onkeyup=\"ajax_showOptions(this,'getPlayerEmailByLetters=1&project=".$_SESSION['project']."',event)\"></p>";
	echo "		<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$_GET['ltid']."\">";
	echo "		<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$_GET['ltsid']."\">";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">";
	echo "		<input type=\"hidden\" name=\"mode\" value=\"team_player_check\">";
	echo "		<input type=\"submit\" value=\""._LEAGUE_TEAM_PLAYER_SEARCH."\" class=\"eden_button\">";
	echo "	</form>";
	echo "</div>";
}
/***********************************************************************************************************
*
*		TeamPlayerConfirm
*
*		Zobrazi vyber pro prijmuti/zamitnuti vstupu do teamu
*
***********************************************************************************************************/
function TeamPlayerConfirm (){
	
	global $db_admin,$db_league_teams,$db_league_teams_sub,$db_league_requests,$db_league_players,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_player = mysql_query("
	SELECT a.admin_nick, lr.league_request_action, lt.league_team_name, lt.league_team_id, lts.league_team_sub_id, lts.league_team_sub_game_id, g.clan_games_game 
	FROM $db_league_requests AS lr 
	JOIN $db_admin AS a ON a.admin_id=lr.league_request_admin_id 
	JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lr.league_request_team_sub_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
	JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
	WHERE lr.league_request_id=".(integer)$_GET['lrid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	
	$res_player_teams = mysql_query("
	SELECT lp.league_player_id, lp.league_player_team_id, lp.league_player_join_date, lt.league_team_id, lt.league_team_name, lts.league_team_sub_id, lt.league_team_owner_id 
	FROM $db_league_players AS lp 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
	LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lp.league_player_team_sub_id 
	WHERE lp.league_player_game_id=".(integer)$ar_player['league_team_sub_game_id']." AND lp.league_player_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	
	echo "<div class=\"eden_league\">";
	// Pokud hrac neni opravnen pro danou akci (cizi utok) zobrazi se zprava o neopravnenem uziti
	if ($ar_player['league_request_action'] != 1) {
		echo "<p>".Msg("league_no_privilege")."</p>";
	} else {
		echo "	<p>"._LEAGUE_TEAM_GAME.": ".$ar_player['clan_games_game']."</p>";
		// Pokud hrac ma ucet pro tuto hru, zkontroluje se zda neni v 24 hodinove ochranne lhute
		if ($ar_player_teams = mysql_fetch_array($res_player_teams)){
			$player_id = $ar_player_teams['league_player_id'];
			/* Kontrola lhuty od prestupu - 24h */
			$limit = LeagueCheck24($ar_player_teams['league_player_join_date']);
			
			if ($ar_player_teams['league_player_team_id'] == 0){
				echo "	<p>"._LEAGUE_PLAYER_JOIN_NO_TEAM_MEMBER."</p>";
			} elseif ($limit == True) {
				echo "	<p>"._LEAGUE_PLAYER_JOIN_MEMBER_OF." <strong>".$ar_player_teams['league_team_name']."</strong></p>";
				echo "	<p>"._LEAGUE_PLAYER_JOIN_MEMBER_OF_HELP."</p>";
			} elseif ($limit == False) {
		   		echo "	<p>"._LEAGUE_PLAYER_JOIN_SAFE.date('d.m.Y H:i:s', $date_estimate).".</p>";
			}
		// Pokud hrac jeste nema zalozen ucet pro tuto hru, ucet zalozime
		} else {
			echo "	<p>"._LEAGUE_PLAYER_JOIN_NO_TEAM_MEMBER."</p>";
			mysql_query("INSERT INTO $db_league_players VALUES(
			'',
			'".(integer)$_SESSION['loginid']."',
			'".(integer)$ar_player['league_team_sub_game_id']."',
			'0',
			'0',
			'0',
			'0',
			'1',
			'0',
			'0',
			'1000-01-01 00:00:00',
			'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res_player_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_player_id = mysql_fetch_array($res_player_id);
			$player_id = $ar_player_id[0];
			LeagueAddToLOG (0,0,0,(integer)$_SESSION['loginid'],(integer)$ar_player['league_team_sub_game_id'],1,"","",""); 		// Zalozili jsme novy hracsky ucet
			$limit = True;
		}
			if ($limit == True){
				echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
				echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">";
				echo "	<input type=\"hidden\" name=\"pid\" value=\"".(integer)$player_id."\">";
				echo "	<input type=\"hidden\" name=\"oltid\" value=\"".(integer)$ar_player_teams['league_team_id']."\">";
				echo "	<input type=\"hidden\" name=\"oltsid\" value=\"".(integer)$ar_player_teams['league_team_sub_id']."\">";
				echo "	<input type=\"hidden\" name=\"nltid\" value=\"".(integer)$ar_player['league_team_id']."\">";
				echo "	<input type=\"hidden\" name=\"nltsid\" value=\"".(integer)$ar_player['league_team_sub_id']."\">";
				echo "	<input type=\"hidden\" name=\"lrid\" value=\"".(integer)$_GET['lrid']."\">";
				echo "	<input type=\"hidden\" name=\"mode\" value=\"team_player_agreed\"><br>"; 
				echo "	<input type=\"submit\" value=\""._LEAGUE_PLAYER_JOIN_BUTTON_YES.stripslashes($ar_player['league_team_name'])."\" class=\"eden_button\">";
				echo "	</form>";
				
				echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;mode=team_home&amp;pid=".$player_id."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
				echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">";
				echo "	<input type=\"hidden\" name=\"lrid\" value=\"".(integer)$_GET['lrid']."\">";
				echo "	<input type=\"hidden\" name=\"nltid\" value=\"".(integer)$ar_player['league_team_id']."\">";
				echo "	<input type=\"hidden\" name=\"nltsid\" value=\"".(integer)$ar_player['league_team_sub_id']."\">";
				echo "	<input type=\"hidden\" name=\"mode\" value=\"team_player_disagreed\"><br>";
				echo "	<input type=\"submit\" value=\""._LEAGUE_PLAYER_JOIN_BUTTON_NO.stripslashes($ar_player['league_team_name'])."\" class=\"eden_button\">";
				echo "	</form>";
			}
		}
	echo "</div>";
}
/***********************************************************************************************************
*
*		TeamTeamConfirm
*
*		Zobrazi vyber pro prijmuti/zamitnuti vstupu do teamu
*
***********************************************************************************************************/
function TeamTeamConfirm (){
	
	global $db_admin,$db_league_teams,$db_league_teams_sub,$db_league_requests,$db_league_players,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, a.admin_email, lr.league_request_action, lt.league_team_name, lt.league_team_id, lts.league_team_sub_id, lts.league_team_sub_game_id 
	FROM $db_league_requests AS lr 
	JOIN $db_admin AS a ON a.admin_id=lr.league_request_admin_id 
	JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lr.league_request_team_sub_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
	WHERE lr.league_request_id=".(integer)$_GET['lrid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	
	$res_player_teams = mysql_query("
	SELECT lp.league_player_id, lp.league_player_team_id, lp.league_player_join_date, lt.league_team_id, lt.league_team_name, lts.league_team_sub_id, lt.league_team_owner_id 
	FROM $db_league_players AS lp 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
	LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lp.league_player_team_sub_id 
	WHERE lp.league_player_game_id=".(integer)$ar_player['league_team_sub_game_id']." AND lp.league_player_admin_id=".(integer)$ar_player['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	
	echo "<div class=\"eden_league\">";
	// Pokud hrac neni opravnen pro danou akci (cizi utok) zobrazi se zprava o neopravnenem uziti
	if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ar_player['league_team_id'],0) == True || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ar_player['league_team_id'],$ar_player['league_team_sub_id']) == True) {
		echo "	<table cellspacing=\"2\" cellpadding=\"3\" class=\"eden_league_table\">";
		echo "		<tr class=\"suda\">";
		echo "			<td>ID</td>";
		echo "			<td>".$ar_player['admin_id']."</td>";
		echo "		</tr>";
		echo "		<tr class=\"licha\">";
		echo "			<td>"._LEAGUE_TEAM_PLAYER_NICK."</td>";
		echo "			<td>".$ar_player['admin_nick']."</td>";
		echo "		</tr>";
		echo "		<tr class=\"suda\">";
		echo "			<td>"._LEAGUE_TEAM_PLAYER_EMAIL."</td>";
		echo "			<td>".$ar_player['admin_email']."</td>";
		echo "		</tr>";
		echo "	</table><br clear=\"both\">";
		// Pokud hrac ma ucet pro tuto hru, zkontroluje se zda neni v 24 hodinove ochranne lhute
		if ($ar_player_teams = mysql_fetch_array($res_player_teams)){
			$player_id = $ar_player_teams['league_player_id'];
			/* Kontrola lhuty od prestupu - 24h */
			$limit = LeagueCheck24($ar_player_teams['league_player_join_date']);
			if ($limit == False){
				$date_old = strtotime($ar_player_teams['league_player_join_date']);
				$date_estimate = strtotime("+1 day",$date_old)."<br>";
				echo "	<p>"._LEAGUE_PLAYER_JOIN_SAFE.date('d.m.Y H:i:s', $date_estimate).".</p>";
			}
		// Pokud hrac jeste nema zalozen ucet pro tuto hru, ucet zalozime
		} else {
			echo "	<p>"._LEAGUE_PLAYER_JOIN_NO_TEAM_MEMBER."</p>";
			mysql_query("INSERT INTO $db_league_players VALUES(
			'', 
			'".(integer)$_SESSION['loginid']."',
			'".(integer)$ar_player['league_team_sub_game_id']."',
			'0',
			'0',
			'0',
			'0',
			'1',
			'0',
			'0',
			NOW(),
			'0')") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res_player_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_player_id = mysql_fetch_array($res_player_id);
			$player_id = $ar_player_id[0];
			LeagueAddToLOG (0,0,0,(integer)$_SESSION['loginid'],(integer)$ar_player['league_team_sub_game_id'],1,"","",""); 		// Zalozili jsme novy hracsky ucet
			$limit = True;
		}
		if ($limit == True){
			echo "	<p><form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "	<input type=\"hidden\" name=\"pid\" value=\"".(integer)$player_id."\">";
			echo "	<input type=\"hidden\" name=\"oltid\" value=\"".(integer)$ar_player_teams['league_team_id']."\">";
			echo "	<input type=\"hidden\" name=\"oltsid\" value=\"".(integer)$ar_player_teams['league_team_sub_id']."\">";
			echo "	<input type=\"hidden\" name=\"nltid\" value=\"".(integer)$ar_player['league_team_id']."\">";
			echo "	<input type=\"hidden\" name=\"nltsid\" value=\"".(integer)$ar_player['league_team_sub_id']."\">";
			echo "	<input type=\"hidden\" name=\"lrid\" value=\"".(integer)$_GET['lrid']."\">";
			echo "	<input type=\"hidden\" name=\"mode\" value=\"team_team_agreed\"><br>"; 
			echo "	<input type=\"submit\" value=\""._LEAGUE_TEAM_JOIN_BUTTON_YES.stripslashes($ar_player['admin_nick'])."\" class=\"eden_button\">";
			echo "	</form>";
			
			echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;mode=team_home&amp;pid=".$ar_player['league_player_id']."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "	<input type=\"hidden\" name=\"lrid\" value=\"".(integer)$_GET['lrid']."\">";
			echo "	<input type=\"hidden\" name=\"nltid\" value=\"".(integer)$ar_player['league_team_id']."\">";
			echo "	<input type=\"hidden\" name=\"nltsid\" value=\"".(integer)$ar_player['league_team_sub_id']."\">";
			echo "	<input type=\"hidden\" name=\"mode\" value=\"team_team_disagreed\"><br>";
			echo "	<input type=\"submit\" value=\""._LEAGUE_TEAM_JOIN_BUTTON_NO.stripslashes($ar_player['admin_nick'])."\" class=\"eden_button\">";
			echo "	</form></p>";
		}
	} else {
		echo "<p>".Msg("league_no_privilege")."</p>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeaguePlayerJoin
*
*		Pridani hrace do teamu
*
***********************************************************************************************************/
function TeamPlayerJoin(){
	
	global $db_clan_games,$db_league_teams,$db_league_teams_sub;
	global $eden_cfg;
	
	$_GET['mode'] = AGet($_GET,'mode');
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_PLAYER_JOIN_TEAM."</h2>";
	if ($_GET['mode'] == "team_player_join"){
		echo "		<p><form action=\"index.php?action=league_team&amp;mode=team_player_join_confirm&amp;project=".$_SESSION['project']."\" method=\"post\"><strong>"._LEAGUE_PLAYER_JOIN_TEAM_SELECT."</strong></p>";
		echo "		<p><input type=\"text\" id=\"team_name\" name=\"team_name\" value=\"\" size=\"27\" autocomplete=\"off\" onkeyup=\"ajax_showOptions(this,'getTeamNameByLetters=1&project=".$_SESSION['project']."',event)\">";
		echo "		<input type=\"hidden\" id=\"team_name_hidden\" name=\"ltid\"></p>";
		echo "		<input type=\"submit\" value=\""._LEAGUE_PLAYER_JOIN_TEAM_CONTINUE."\" class=\"eden_button\"></form></p>";
	}
	if ($_GET['mode'] == "team_player_join_confirm"){
		$res_team_sub = mysql_query("
		SELECT lt.league_team_name, lts.league_team_sub_id, g.clan_games_game 
		FROM $db_league_teams_sub AS lts 
		JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
		JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
		WHERE league_team_sub_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_team_sub = mysql_fetch_array($res_team_sub);
		$num_team_sub = mysql_num_rows($res_team_sub);
		echo "		<p><form action=\"".$eden_cfg['url_edencms']."eden_save.php?project=".$_SESSION['project']."\" method=\"post\">"._LEAGUE_PLAYER_JOIN_TEAM_CONFIRM."<strong>".stripslashes($ar_team_sub['league_team_name'])."</strong></p>";
		if ($num_team_sub > 1){
			echo "		<p><strong>"._LEAGUE_PLAYER_JOIN_TEAM_SELECT_GAME."</strong><br>";
			echo "		<select name=\"ltsid\" size=\"1\">";
						mysql_data_seek($res_team_sub, 0); 
						while($ar_team_sub = mysql_fetch_array($res_team_sub)){
							echo "<option value=\"".$ar_team_sub['league_team_sub_id']."\">".stripslashes($ar_team_sub['clan_games_game'])."</option>";
						}
			echo "		</select>";
		} else {
			echo "		<p>"._LEAGUE_PLAYER_JOIN_TEAM_IN_GAME."<strong>".stripslashes($ar_team_sub['clan_games_game'])."</strong></p>";
			echo "		<input type=\"hidden\" name=\"ltsid\" value=\"".$ar_team_sub['league_team_sub_id']."\">";
		}
		echo "		<input type=\"hidden\" name=\"ltid\" value=\"".$_POST['ltid']."\">";
		echo "		<input type=\"hidden\" name=\"mode\" value=\"team_player_join_request\"></p>";
		echo "		<p><input type=\"submit\" value=\""._LEAGUE_PLAYER_JOIN_TEAM_REQUEST."\" class=\"eden_button\"></form></p>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		TeamPlayerLeave
*
*		Zobrazi souhas s vystupem z teamu
*
***********************************************************************************************************/
function TeamPlayerLeave(){
	
	global $db_admin,$db_league_teams,$db_league_teams_sub,$db_league_players,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_GET['ltsid'] = AGet($_GET,'ltsid');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	$res_player = mysql_query("
	SELECT a.admin_team_own_id, lp.league_player_id, lp.league_player_position_captain, lp.league_player_join_date, lt.league_team_id, lt.league_team_name, lts.league_team_sub_id, lts.league_team_sub_game_id, g.clan_games_game 
	FROM $db_league_players AS lp
	JOIN $db_admin AS a	ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lp.league_player_team_sub_id 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
	LEFT JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
	WHERE lp.league_player_admin_id=".(integer)$_SESSION['loginid']." AND lp.league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	$res_team_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_team_players = mysql_fetch_array($res_team_players);
	$res_team_sub_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_team_sub_players = mysql_fetch_array($res_team_sub_players);
	
	/* Kontrola lhuty od prestupu - 24h */
	$limit = LeagueCheck24($ar_player['league_player_join_date']);
	
	echo "<div class=\"eden_league\">";
		echo "<strong>"._LEAGUE_TEAM_GAME.":</strong> ".$ar_player['clan_games_game']."<br>";
		echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		// Majitel je posledni hrac v teamu
		if ($ar_player['admin_team_own_id'] == $ar_player['league_team_id'] && $num_team_players[0] == 1){
			echo "<p>"._LEAGUE_PLAYER_LEAVE_OWNER_ONLY."</p>";
			$hibernate = "<input type=\"hidden\" name=\"hit\" value=\"1\">";
			$limit = True;
		} else {
			if ($limit == True){
				echo "<p>"._LEAGUE_PLAYER_LEAVE_QUEST." <strong>".stripslashes($ar_player['league_team_name'])."</strong>?</p>";
			} else {
				echo "<p>"._LEAGUE_PLAYER_SAFE_TIME.date('d.m.Y H:i:s', $date_estimate).".</p>";
			}
		}
		// Pokud majitel neni poslednim hracem v tymu, musi urcit majitele
		if ($ar_player['admin_team_own_id'] == $ar_player['admin_team_id'] && $num_team_players[0] > 1){
			echo "<p>"._LEAGUE_PLAYER_LEAVE_OWNER."</p>";
			$res_new_o = mysql_query("
			SELECT a.admin_nick, a.admin_team_own_id, lp.league_player_id 
			FROM $db_league_players AS lp 
			JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
			WHERE lp.league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			echo "<strong>"._LEAGUE_PLAYER_LEAVE_NEW_OWNER."</strong><br>";
			echo "<select name=\"league_team_new_o\" size=\"1\">";
			while($ar_new_o = mysql_fetch_array($res_new_o)){
				if ($ar_new_o['admin_team_own_id'] == 0){
					echo "<option value=\"".$ar_new_o['league_player_id']."\">".stripslashes($ar_new_o['admin_nick'])."</option>";
				}
			}
			echo "</select>";
		}
		
		if ($ar_player['league_player_position_captain'] == 1 && $num_team_sub_players[0] > 1){ 
			echo "<p>"._LEAGUE_PLAYER_LEAVE_NEW_OWNER."</p>";
			$res_new_c = mysql_query("
			SELECT a.admin_nick, lp.league_player_id, lp.league_player_position_captain 
			FROM $db_league_players AS lp 
			LEFT JOIN ($db_admin AS a) 
			ON (a.admin_id=lp.league_player_admin_id) 
			WHERE lp.league_player_team_sub_id=".(integer)$_GET['ltsid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			echo "<strong>"._LEAGUE_PLAYER_LEAVE_NEW_CAPTAIN."</strong><br>";
			echo "<select name=\"league_team_new_c\" size=\"1\">";
			while($ar_new_c = mysql_fetch_array($res_new_c)){
				if ($ar_new_c['league_player_position_captain'] == 0){
					echo "<option value=\"".$ar_new_c['league_player_id']."\">".stripslashes($ar_new_c['admin_nick'])."</option>";
				}
			}
			echo "</select>";
		}
		if ($limit == True){
			echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "<input type=\"hidden\" name=\"lpid\" value=\"".(integer)$ar_player['league_player_id']."\">";
			echo "<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$ar_player['league_team_id']."\">";
			echo "<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$ar_player['league_team_sub_id']."\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"team_player_leave\"><br><br>"; 
			echo "<input type=\"submit\" value=\""._LEAGUE_PLAYER_LEAVE_BUTTON_YES."\" class=\"eden_button\">";
			// Pokud je majitel zaroven poslednim hracem v tymu, a preje si team opustit, hibernujeme team
			if ($ar_player['admin_team_own_id'] == $ar_player['league_team_id'] && $num_team_players[0] == 1){ echo $hibernate;}
			echo "</form>";
			
			echo "<form action=\"index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"team_player_leave\"><br>";
			echo "<input type=\"submit\" value=\""._LEAGUE_PLAYER_LEAVE_BUTTON_NO."\" class=\"eden_button\">";
			echo "</form>";
		}
	echo "</div>";
}
/***********************************************************************************************************
*
*		TeamPlayerKick
*
*		Kick hrace z teamu
*
***********************************************************************************************************/
function TeamPlayerKick($pid){
	
	global $db_admin,$db_league_players,$db_league_teams;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$res_player = mysql_query("
	SELECT a.admin_id, a.admin_nick, lp.league_player_id, lp.league_player_position_captain, lp.league_player_team_id, lp.league_player_team_sub_id, lp.league_player_game_id, lt.league_team_owner_id 
	FROM $db_league_players AS lp 
	JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
	WHERE lp.league_player_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	$res_team_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_id=".(integer)$ar_player['league_player_team_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_team_players = mysql_fetch_array($res_team_players);
	$res_team_sub_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_sub_id=".(integer)$ar_player['league_player_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$num_team_sub_players = mysql_fetch_array($res_team_sub_players);
	
	echo "<div class=\"eden_league\">";
		if ($pid == 0 || $pid == ""){
			echo "<p>"._LEAGUE_PLAYER_KICK_NO_ID."</p>";
		} elseif ($ar_player['league_team_owner_id'] == $ar_player['admin_id']){
			echo "<p>"._LEAGUE_PLAYER_KICK_NO_IS_OWNER."</p>";
		} else {
			echo "<p>"._LEAGUE_PLAYER_KICK_QUEST."<strong>".stripslashes($ar_player['admin_nick'])."</strong></p>";
			echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			if ($ar_player['league_player_position_captain'] == 1){
				echo "<p>".stripslashes($ar_player['admin_nick'])._LEAGUE_PLAYER_KICK_QUEST_CAPTAIN."</p>";
				$res_new_c = mysql_query("
				SELECT a.admin_nick, lp.league_player_id, lp.league_player_position_captain 
				FROM $db_league_players AS lp 
				LEFT JOIN ($db_admin AS a) 
				ON (a.admin_id=lp.league_player_admin_id) 
				WHERE lp.league_player_team_sub_id=".(integer)$ar_player['league_player_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				echo "<strong>"._LEAGUE_PLAYER_KICK_NEW_CAPTAIN."</strong><br>";
				echo "<select name=\"league_team_new_c\" size=\"1\">";
				while($ar_new_c = mysql_fetch_array($res_new_c)){
					if ($ar_new_c['league_player_position_captain'] == 0){
						echo "<option value=\"".$ar_new_c['league_player_id']."\">".stripslashes($ar_new_c['admin_nick'])."</option>";
					}
				}
				echo "</select>";
			}
			echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "<input type=\"hidden\" name=\"pid\" value=\"".(integer)$ar_player['league_player_id']."\">";
			echo "<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$ar_player['league_player_team_id']."\">";
			echo "<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$ar_player['league_player_team_sub_id']."\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"team_player_kick\"><br><br>"; 
			echo "<input type=\"submit\" value=\""._LEAGUE_PLAYER_KICK_BUTTON_YES."\" class=\"eden_button\">";
			echo "</form>";
			
			echo "<form action=\"index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_player_team_id']."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"team_player_kick\"><br>";
			echo "<input type=\"submit\" value=\""._LEAGUE_PLAYER_KICK_BUTTON_NO."\" class=\"eden_button\">";
			echo "</form>";
		}
	echo "</div>";
}
/***********************************************************************************************************
*
*		TeamPlayerMake
*
*		Prideleni pozice v teamu
*		0	= Owner
*		C	= Captain
*		A	= Assistant
*		P	= Player
*
***********************************************************************************************************/
function TeamPlayerMake($mode = "", $pid = 0){
	
	global $db_admin,$db_league_players;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<div class=\"eden_league\">";
	if ($mode == ""){
		echo "<p>"._LEAGUE_PLAYER_MAKE_NO_MODE."</p>";
	} elseif ($pid == 0){
		echo "<p>"._LEAGUE_PLAYER_MAKE_NO_PLAYER."</p>";
	} else {
		$res_player = mysql_query("
		SELECT a.admin_id, a.admin_nick, lp.league_player_id, lp.league_player_position_captain, lp.league_player_team_id, lp.league_player_team_sub_id, lp.league_player_game_id 
		FROM $db_league_players AS lp 
		JOIN ($db_admin AS a) ON (a.admin_id=lp.league_player_admin_id) 
		WHERE lp.league_player_id=".(integer)$pid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		switch ($mode){
		 	case "O":
		 		echo "<p>"._LEAGUE_PLAYER_MAKE_Q_OWNER_1."<strong>".stripslashes($ar_player['admin_nick'])."</strong>"._LEAGUE_PLAYER_MAKE_Q_OWNER_2."</p>";
				$form_mode = "team_player_make_o";
				$form_button = _LEAGUE_PLAYER_MAKE_Q_OWNER_3;
		 	break;
			case "C":
				echo "<p>"._LEAGUE_PLAYER_MAKE_Q_CAPTAIN_1."<strong>".stripslashes($ar_player['admin_nick'])."</strong>"._LEAGUE_PLAYER_MAKE_Q_CAPTAIN_2."</p>";
				$form_mode = "team_player_make_c";
				$form_button = _LEAGUE_PLAYER_MAKE_Q_CAPTAIN_3;
			break;
			case "A":
				echo "<p>"._LEAGUE_PLAYER_MAKE_Q_ASSISTANT_1."<strong>".stripslashes($ar_player['admin_nick'])."</strong>"._LEAGUE_PLAYER_MAKE_Q_ASSISTANT_2."</p>";
				$form_mode = "team_player_make_a";
				$form_button = _LEAGUE_PLAYER_MAKE_Q_ASSISTANT_3;
			break;
			case "P":
				echo "<p>"._LEAGUE_PLAYER_MAKE_Q_PLAYER_1."<strong>".stripslashes($ar_player['admin_nick'])."</strong>"._LEAGUE_PLAYER_MAKE_Q_PLAYER_2."</p>";
				$form_mode = "team_player_make_p";
				$form_button = _LEAGUE_PLAYER_MAKE_Q_PLAYER_3;
			break;
		}
		echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".(integer)$ar_player['league_player_id']."\">";
		echo "<input type=\"hidden\" name=\"aid\" value=\"".(integer)$ar_player['admin_id']."\">";
		echo "<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$ar_player['league_player_team_id']."\">";
		echo "<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$ar_player['league_player_team_sub_id']."\">";
		echo "<input type=\"hidden\" name=\"lpgid\" value=\"".(integer)$ar_player['league_player_game_id']."\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"".$form_mode."\"><br><br>"; 
		echo "<input type=\"submit\" value=\"".$form_button."\" class=\"eden_button\">";
		echo "</form><br>";
		
		echo "<form action=\"index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_player_team_id']."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		echo "<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button\">";
		echo "</form>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueAwards
*
*		Show what awards gets player or team
*		return - Array(league_award_place, league_award_name, league_award_img)
*		$mode		= Award mode (1 = player awards, 2 = team awards)
*		$aid		= Player ID
*		$ltid		= Team Sub ID
*
***********************************************************************************************************/
function LeagueAwards($mode = 0, $aid = 0, $ltid = 0){
	
	global $db_admin,$db_league_players,$db_league_teams,$db_league_leagues,$db_league_awards,$db_league_seasons,$db_clan_games;
	global $url_league_awards;
	global $eden_cfg;
	
	// Check if $mode is correct
	if ($mode == 1){
		// $pid must be more then 0
		if ($aid == 0){return false; exit;}
		$res_player = mysql_query("SELECT a.admin_nick, lp.league_player_id 
		FROM $db_league_players AS lp 
		JOIN $db_admin AS a ON a.admin_id = lp.league_player_admin_id 
		WHERE lp.league_player_admin_id = ".(integer)$aid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		$link = "<a href=\"".$eden_cfg['url']."index.php?action=player&mode=player_acc&id=".$aid."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\">".stripslashes($ar_player['admin_nick'])."</a>";
		$where = "league_award_admin_id = ".(integer)$aid;
	} elseif ($mode == 2){
		// $tsid must be more then 0
		if ($ltid == 0){return false; exit;}
		$res_team = mysql_query("SELECT league_team_name  
		FROM $db_league_teams 
		WHERE league_team_id = ".(integer)$ltid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_team = mysql_fetch_array($res_team);
		$link = "<a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ltid."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\">".stripslashes($ar_team['league_team_name'])."</a>";
		$where = "league_award_team_id = ".(integer)$ltid;
	} else {
		return false; exit;
	}
	
	$i = 1;
	$result = "";
	$result .= "<div class=\"eden_league\">";
	$result .= "<h2>".$link."</h2>";
	$result .= "<table cellspacing=\"3\" cellpadding=\"2\" border=\"0\" class=\"eden_league_table\">\n";
	$result .= "	<tr>";
	$result .= "		<td class=\"eden_title\" style=\"width:40px;\">"._LEAGUE_AWARD_PLACE."</td>";
	$result .= "		<td class=\"eden_title\" style=\"width:60px;\">"._LEAGUE_AWARD_GAME."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_AWARD_LEAGUE."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_AWARD_SEASON."</td>";
	$result .= "		<td class=\"eden_title\">"._LEAGUE_AWARD_DATE."</td>";
	$result .= "	</tr>";
	
	$res_award = mysql_query("SELECT league_award_league_id, la.league_award_place, la.league_award_name, la.league_award_img, la.league_award_date, g.clan_games_game, ls.league_season_name, ll.league_league_name 
	FROM $db_league_awards AS la 
	JOIN $db_clan_games AS g ON g.clan_games_id = la.league_award_game_id 
	JOIN $db_league_seasons AS ls ON ls.league_season_id = la.league_award_season_id 
	JOIN $db_league_leagues AS ll ON ll.league_league_id = la.league_award_league_id 
	WHERE $where 
	AND league_award_mode = ".(integer)$mode." 
	ORDER BY la.league_award_date") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$i = 1;
   	while ($ar_award = mysql_fetch_array($res_award)){
		
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		$result .= "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		$result .= "	<td align=\"right\" valign=\"top\">"; if($ar_award['league_award_place']){$result .= "<img src=\"".$url_league_awards.$ar_award['league_award_img']."\" alt=\"".stripslashes($ar_award['league_award_name'])."\" title=\"".stripslashes($ar_award['league_award_name'])."\" />";} else {$result .= "<strong>".$i."</strong>";} $result .= "</td>";
		$result .= "	<td align=\"left\" valign=\"top\">".stripslashes($ar_award['clan_games_game'])."</td>";
		$result .= "	<td valign=\"top\">".stripslashes($ar_award['league_league_name'])."</td>";
	   	$result .= "	<td valign=\"top\">".stripslashes($ar_award['league_season_name'])."</td>";
	   	$result .= "	<td valign=\"top\">".FormatDatetime($ar_award['league_award_date'],"d.m.Y")."</td>";
		$result .= "</tr>";
		$i++;
	}
	$result .= "</table>\n";
	$result .= "</div>";
	
	return $result;
}
/***********************************************************************************************************
*
*		TeamLog
*
*		Prideleni pozice v teamu
*		$mode	- player	- $id = Admin ID
*				- team		- $id = Team ID
*
***********************************************************************************************************/
function LeagueLog($mode = "player", $id = 0){
	
	global $db_admin,$db_league_leagues,$db_league_log,$db_league_teams,$db_league_teams_sub,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<div class=\"eden_league\">";
	if ($mode == ""){
		echo "<p>"._LEAGUE_LOG_NO_MODE."</p>";
	} elseif ($id == 0 || $id == ""){
		echo "<p>"._LEAGUE_LOG_NO_SUBJECT."</p>";
	} else {
		echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		switch ($mode){
		 	case "player":
				$res_player = mysql_query("
				SELECT a.admin_id, a.admin_nick, ll.league_log_id, ll.league_log_date, ll.league_log_action, ll.league_log_new_value, ll.league_log_old_value, ll.league_log_reason,  lt.league_team_id, lt.league_team_name, g.clan_games_game, l.league_league_id, l.league_league_name 
				FROM $db_league_log AS ll 
				JOIN $db_admin AS a ON a.admin_id=ll.league_log_admin_id 
				LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=ll.league_log_team_sub_id 
				LEFT JOIN $db_clan_games AS g ON g.clan_games_id=ll.league_log_game_id 
				LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=ll.league_log_team_id 
				LEFT JOIN $db_league_leagues AS l ON l.league_league_id=ll.league_log_league_id 
				WHERE ll.league_log_admin_id=".(integer)$id." AND ll.league_log_action<50 ORDER BY ll.league_log_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player = mysql_fetch_array($res_player);
				$name = stripslashes($ar_player['admin_nick']);
			break;
			case "team":
				$res_player = mysql_query("
				SELECT a.admin_id, a.admin_nick, ll.league_log_id, ll.league_log_date, ll.league_log_action, ll.league_log_new_value, ll.league_log_old_value, ll.league_log_reason, lt.league_team_id, lt.league_team_name, g.clan_games_game, l.league_league_id, l.league_league_name 
				FROM $db_league_log AS ll 
				LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=ll.league_log_team_id 
				LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=ll.league_log_team_sub_id AND lts.league_team_sub_team_id=lt.league_team_id 
				LEFT JOIN $db_clan_games AS g ON g.clan_games_id=ll.league_log_game_id 
				LEFT JOIN $db_admin AS a ON a.admin_id=ll.league_log_admin_id 
				LEFT JOIN $db_league_leagues AS l ON l.league_league_id=ll.league_log_league_id 
				WHERE ll.league_log_team_id=".(integer)$id." AND ll.league_log_action>49 ORDER BY ll.league_log_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player = mysql_fetch_array($res_player);
				$name = stripslashes($ar_player['league_team_name']);
			break;
		}
		echo "		<tr>";
		echo "			<td colspan=\"2\"><h3>".$name."</h3></td>";
		echo "		</tr>";
		echo "		<tr>";
	  	/* echo "			<td>ID</td>"; */
		echo "			<td class=\"eden_league_title\">"._LEAGUE_LOG_DATE."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_LOG_ACTION."</td>";
		echo "		</tr>";
		$cislo = 0;
		@mysql_data_seek($res_player,0);
		while ($ar_player = mysql_fetch_array($res_player)){
			switch ($ar_player['league_log_action']){
				case "1":
					$log_action = _LEAGUE_LOG_PLAYER_ACC_CREATED."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "2":
					$log_action = _LEAGUE_LOG_PLAYER_JOIN_TEAM."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "3":
					$log_action = _LEAGUE_LOG_PLAYER_LEFT_TEAM."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "4":
					$log_action = _LEAGUE_LOG_PLAYER_KICKED_FROM_TEAM."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "5":
					$log_action = _LEAGUE_LOG_PLAYER_ACC_BLOCKED;
				break;
				case "6":
					$log_action = _LEAGUE_LOG_PLAYER_CHANGE_NICK."<strong>".stripslashes($ar_player['league_log_old_value'])."</strong>"._LEAGUE_LOG_PLAYER_CHANGE_NICK_2."<strong>".stripslashes($ar_player['league_log_new_value'])."</strong>";
				break;
				case "7":
					$log_action = _LEAGUE_LOG_PLAYER_ADD_GUID."<strong>".$ar_player['league_log_new_value']."</strong>";
				break;
				case "8":
					$log_action = _LEAGUE_LOG_PLAYER_CHANGE_GUID."<strong>".stripslashes($ar_player['league_log_old_value'])."</strong>"._LEAGUE_LOG_PLAYER_CHANGE_GUID_2."<strong>".stripslashes($ar_player['league_log_new_value'])."</strong>"._LEAGUE_LOG_PLAYER_CHANGE_GUID_3.stripslashes($ar_player['league_log_reason']);
				break;
				case "9":
					$log_action = _LEAGUE_LOG_PLAYER_ADD_PERSONALITY."<strong>".$ar_player['league_log_new_value']."</strong>";
				break;
				case "10":
					$log_action = _LEAGUE_LOG_PLAYER_CHANGE_PERSONALITY."<strong>".stripslashes($ar_player['league_log_old_value'])."</strong>"._LEAGUE_LOG_PLAYER_CHANGE_PERSONALITY_2."<strong>".stripslashes($ar_player['league_log_new_value'])."</strong>";
				break;
				case "11":
					$log_action = _LEAGUE_LOG_PLAYER_CHANGE_TEAM."<strong>".stripslashes($ar_player['league_log_old_value'])." (".$ar_player['clan_games_game'].")</strong>"._LEAGUE_LOG_PLAYER_CHANGE_TEAM_2."<strong>".stripslashes($ar_player['league_log_new_value'])." (".$ar_player['clan_games_game'].")</strong>";
				break;
				case "12":
					$log_action = _LEAGUE_LOG_PLAYER_BECOME_OWNER."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])."</strong></a>";
				break;
				case "13":
					$log_action = _LEAGUE_LOG_PLAYER_BECOME_CAPTAIN."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "14":
					$log_action = _LEAGUE_LOG_PLAYER_BECOME_ASSISTANT."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "15":
					$log_action = _LEAGUE_LOG_PLAYER_LEFT_OWNERSHIP."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])."</strong></a>";
				break;
				case "16":
					$log_action = _LEAGUE_LOG_PLAYER_TAKEN_POSITION_CAPTAIN."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "17":
					$log_action = _LEAGUE_LOG_PLAYER_GAVE_POSITION_CAPTAIN_TO_1."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>"._LEAGUE_LOG_PLAYER_GAVE_POSITION_CAPTAIN_TO_2."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_log_new_value'])."</strong></a>";
				break;
				case "18":
					$log_action = _LEAGUE_LOG_PLAYER_TAKEN_POSITION_ASSISTANT."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "19":
					$log_action = _LEAGUE_LOG_PLAYER_REFUSE_JOIN_TEAM."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "20":
					$log_action = _LEAGUE_LOG_PLAYER_JOIN_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "21":
					$log_action = _LEAGUE_LOG_PLAYER_LEFT_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "22":
					$log_action = _LEAGUE_LOG_PLAYER_KICKED_FROM_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "23":
					$log_action = _LEAGUE_LOG_PLAYER_BANNED_FROM_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "24":
					$log_action = _LEAGUE_LOG_PLAYER_UNBANNED_FROM_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				/* TEAM */
				case "50":
					$log_action = _LEAGUE_LOG_TEAM_CREATED."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_player['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_team_name'])."</strong></a>";
				break;
				case "51":
					$log_action = _LEAGUE_LOG_TEAM_JOIN_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "52":
					$log_action = _LEAGUE_LOG_TEAM_LEFT_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "53":
					$log_action = _LEAGUE_LOG_TEAM_KICKED_FROM_LEAGUE."<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_player['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "54":
					$log_action = _LEAGUE_LOG_TEAM_ACCOUNT_BLOCKED;
				break;
				case "55":
					$log_action = _LEAGUE_LOG_TEAM_CHANGE_NAME."<strong>".stripslashes($ar_player['league_log_old_value'])."</strong>"._LEAGUE_LOG_TEAM_CHANGE_NAME."<strong>".stripslashes($ar_player['league_log_new_value'])."</strong>";
				break;
				case "56":
					$log_action = _LEAGUE_LOG_TEAM_ADD_GAME."<strong>".stripslashes($ar_player['clan_games_game'])."</strong>";
				break;
				case "57":
					$log_action = _LEAGUE_LOG_TEAM_WAS_JOINED_BY_PLAYER."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "58":
					$log_action = _LEAGUE_LOG_TEAM_WAS_LEFT_BY_PLAYER."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "59":
					$log_action = _LEAGUE_LOG_TEAM_KICKED_PLAYER."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "60":
					$log_action = _LEAGUE_LOG_TEAM_PLAYER_CHANGED_NICK."<strong>".stripslashes($ar_player['league_log_old_value'])."</strong>"._LEAGUE_LOG_TEAM_PLAYER_CHANGED_NICK_2."<strong>".stripslashes($ar_player['league_log_new_value'])."</strong>";
				break;
				case "61":
					$log_action = _LEAGUE_LOG_TEAM_NEW_OWNER_BECOME."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])."</strong></a>";
				break;
				case "62":
					$log_action = _LEAGUE_LOG_TEAM_NEW_CAPTAIN_BECOME."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "63":
					$log_action = _LEAGUE_LOG_TEAM_NEW_ASSISTANT_BECOME."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "64":
					$log_action = _LEAGUE_LOG_TEAM_OWNERSHIP_WAS_GIVEN_TO."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])."</strong></a>";
				break;
				case "65":
					$log_action = _LEAGUE_LOG_TEAM_CAPTAIN_POSITION_WAS_TAKEN."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "66":
					$log_action = _LEAGUE_LOG_TEAM_ASSISTANT_POSITION_WAS_TAKEN."<a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "67":
					$log_action = _LEAGUE_LOG_TEAM_HAS_BEEN_ACCEPTED_TO_LEAGUE."<strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong>";
				break;
				case "68":
					$log_action = _LEAGUE_LOG_TEAM_REFUSED_ADD_PLAYER."<strong><a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_player['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_player['admin_nick'])." (".$ar_player['clan_games_game'].")</strong></a>";
				break;
				case "69":
					$log_action = _LEAGUE_LOG_TEAM_HAS_BEEN_HIBERNATED."<strong>".stripslashes($ar_player['league_league_name'])." (".$ar_player['clan_games_game'].")</strong>";
				break;
				default:
					$log_action = _LEAGUE_LOG_NO_ACTION;
			}
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
	   		echo "		<tr class=\"".$class."\">";
			/* echo "			<td valign=\"top\">".$ar_player['league_log_id']."</td>"; */
			echo "			<td valign=\"top\">".FormatDatetime($ar_player['league_log_date'],"d.m.Y H:i:s")."</td>";
			echo "			<td valign=\"top\">".$log_action."</td>";
			echo "		</tr>";
			$cislo++;
		}
		echo "	</table>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamGuids
*
*		Zobrazeni GUIDu v teamu
*		$ltid	= Team ID
*
***********************************************************************************************************/
function LeagueTeamGuids($ltid = 0){
	
	global $db_admin,$db_admin_contact,$db_admin_guids,$db_league_players,$db_league_guids,$db_country,$db_clan_games;
	global $url_flags;
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_TEAM_GUIDS."</h1>";
	if ($ltid == 0 || $ltid == ""){
		echo "<p>"._LEAGUE_TEAM_GUIDS_TEAM_WASNT_SELECTED."</p>";
	} else {
		$res_team = mysql_query("
		SELECT a.admin_nick, a.admin_id, g.clan_games_game, c.country_shortname, c.country_name 
		FROM $db_league_players AS lp 
		JOIN ($db_admin AS a) ON (a.admin_id=lp.league_player_admin_id) 
		JOIN ($db_admin_contact AS ac) ON (ac.aid=lp.league_player_admin_id) 
		JOIN ($db_country AS c) ON (c.country_id=ac.admin_contact_country) 
		JOIN ($db_clan_games AS g) ON (g.clan_games_id=lp.league_player_game_id) 
		WHERE lp.league_player_team_id=".(integer)$ltid." ORDER BY g.clan_games_game ASC, lp.league_player_position_captain DESC, lp.league_player_position_assistant DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		echo "		<tr>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_GUIDS_PLAYERS_NICK."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_GUIDS_PLAYERS_COUNTRY."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_GUIDS_PLAYERS_GAME."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_GUIDS_PLAYERS_GUIDS."</td>";
		echo "		</tr>";
		$cislo = 0;
		while ($ar_team = mysql_fetch_array($res_team)){
			/* Ulozeni vsech hracobych GUIDu do prommene jako retezec */
			$res_guids = mysql_query("
			SELECT ag.admin_guid_guid, lg.league_guid_name 
			FROM $db_admin_guids AS ag 
			LEFT JOIN ($db_league_guids AS lg) ON (lg.league_guid_id=ag.admin_guid_league_guid_id) 
			WHERE aid=".(integer)$ar_team['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$guids = "";
			while ($ar_guids = mysql_fetch_array($res_guids)){
				$guids .= "<strong>".stripslashes($ar_guids['league_guid_name']).":</strong> ".$ar_guids['admin_guid_guid']."<br>";
			}
			
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
	   		echo "	<tr class=\"".$class."\">";
			echo "		<td valign=\"top\">".stripslashes($ar_team['admin_nick'])."</td>";
			echo "		<td valign=\"top\"><img src=\"".$url_flags.$ar_team['country_shortname'].".gif\" alt=\"".stripslashes($ar_team['country_name'])."\" title=\"".stripslashes($ar_team['country_name'])."\"></td>";
			echo "		<td valign=\"top\">".stripslashes($ar_team['clan_games_game'])."</td>";
			echo "		<td valign=\"top\">".$guids."</td>";
			echo "	</tr>";
			$cislo++;
		}
		echo "	</table>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamHibernateCheck
*
*		Hibernate team
*		$ltid	= Team ID
*
***********************************************************************************************************/
function LeagueTeamHibernateCheck($ltid = 0){
	
	global $db_league_teams_sub,$db_league_players;
	global $eden_cfg;
	// Initialize variable
	$players = 0;
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_TEAM_HIBERNATE."</h1>";
	if ($ltid == 0 || $ltid == ""){
		echo "<p>"._LEAGUE_TEAM_HIBERNATE_TEAM_WASNT_SELECTED."</p>";
	} else {
		// We look how many sub teams is in our team
		$players = LeagueCheckHowManyPlayersInSubTeam($ltid);
	}
	
	if ($players > 0){
		// If there is any player in the sub teams - show warning and link to the team
		echo "<p>"._LEAGUE_TEAM_HIBERNATE_PLAYERS_IN_SUB_TEAM."</p>";
		echo "<p><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ltid."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\">"._LEAGUE_TEAM_HIBERNATE_BACK_TO_TEAM."</a></p>";
	} else {
		// If there is no player in the sub team - show link for hibernate team
		echo "<p>"._LEAGUE_TEAM_HIBERNATE_CHECK."</p>";
			echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		   	echo "<input type=\"hidden\" name=\"ltid\" value=\"".$ltid."\">";
		   	echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"team_hibernate\"><br>"; 
			echo "<input type=\"submit\" value=\""._LEAGUE_TEAM_HIBERNATE_PROCEED_YES."\" class=\"eden_button\">";
			echo "</form><br>";
			
			echo "<form action=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ltid."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "<input type=\"submit\" value=\""._LEAGUE_TEAM_HIBERNATE_PROCEED_NO."\" class=\"eden_button\">";
			echo "</form>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueLeagueList
*
*		Zobrazeni seznamu teamu
*
***********************************************************************************************************/
function LeagueLeagueList(){
	
	global $db_league_leagues,$db_clan_games;
	global $eden_cfg;
	
	$_GET['lid'] = AGet($_GET,'lid');
	
	if ((integer)$_GET['lid'] != ""){ $where = " WHERE l.league_league_id=".(integer)$_GET['lid'];} else {$where = " ORDER BY l.league_league_game_id ASC";}
	$res_league = mysql_query("
	SELECT l.league_league_id, l.league_league_name, l.league_league_description, l.league_league_team_sub_min_players, l.league_league_team_sub_max_players, l.league_league_mode, g.clan_games_game 
	FROM $db_league_leagues AS l 
	JOIN $db_clan_games AS g ON g.clan_games_id=l.league_league_game_id 
	$where ") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	echo "<div class=\"eden_league\">";
	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
	echo "		<tr>";
	echo "			<td class=\"eden_league_title_id\">"._LEAGUE_LEAGUE_LIST_ID."</td>";
	echo "			<td class=\"eden_league_title\">"._LEAGUE_LEAGUE_LIST_NAME."</td>";
	echo "			<td class=\"eden_league_title_game\">"._LEAGUE_LEAGUE_LIST_GAME."</td>";
	echo "			<td class=\"eden_league_title\">"._LEAGUE_LEAGUE_LIST_MODE."</td>";
	if ($ar_league['league_league_mode'] == 1){
		echo "			<td class=\"eden_league_title\">"._LEAGUE_LEAGUE_LIST_MIN."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_LEAGUE_LIST_MAX."</td>";
	}
	echo "		</tr>";
	while ($ar_league = mysql_fetch_array($res_league)){
		if ($ar_league['league_league_team_sub_min_players'] == 0){$min_players = _LEAGUE_LEAGUE_LIST_NO_LIMIT;} else {$min_players = $ar_league['league_league_team_sub_min_players'];}
		if ($ar_league['league_league_team_sub_max_players'] == 0){$max_players = _LEAGUE_LEAGUE_LIST_NO_LIMIT;} else {$max_players = $ar_league['league_league_team_sub_max_players'];}
		echo "	<tr class=\"suda\">";
		echo "		<td align=\"right\" class=\"eden_league_list\" valign=\"top\">".$ar_league['league_league_id']."</td>";
	   	echo "		<td valign=\"top\" class=\"eden_league_list\">".stripslashes($ar_league['league_league_name'])."</td>";
		echo "		<td valign=\"top\" class=\"eden_league_list\">".stripslashes($ar_league['clan_games_game'])."</td>";
		echo "		<td valign=\"top\" class=\"eden_league_list\">"; if ($ar_league['league_league_mode'] == 1){echo _LEAGUE_LEAGUE_LIST_MODE_TEAM;} elseif ($ar_league['league_league_mode'] == 2){echo _LEAGUE_LEAGUE_LIST_MODE_PLAYER;} echo "</td>";
		if ($ar_league['league_league_mode'] == 1){
			echo "	<td valign=\"top\" class=\"eden_league_list\">".$min_players."</td>";
			echo "	<td valign=\"top\" class=\"eden_league_list\">".$max_players."</td>";
		}
		echo "	</tr>";
		echo "	<tr class=\"suda\">";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"5\"><p><strong>"._LEAGUE_LEAGUE_LIST_RULES."</strong></p>";
		echo "			<iframe src=\"".$eden_cfg['url_edencms']."eden_iframe.php?imode=league_team_league_reg&project=".$_SESSION['project']."&lid=".$ar_league['league_league_id']."\" width=\"550\" height=\"150\" frameborder=\"1\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" noresize=\"noresize\" style=\"margin-left:10px;\"></iframe><br><br>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"5\"><br></td>";
		echo "	</tr>";
	}
	echo "	</table>";
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamList
*
*		Zobrazeni seznamu teamu
*
***********************************************************************************************************/
function LeagueTeamList(){
	
	global $db_admin,$db_league_teams,$db_league_teams_sub,$db_country,$db_clan_games;
	global $eden_cfg;
	global $url_flags;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$res_team = mysql_query("
	SELECT lt.league_team_id, lt.league_team_name, c.country_name, c.country_shortname 
	FROM $db_league_teams AS lt 
	JOIN $db_country AS c ON c.country_id=lt.league_team_country_id 
	WHERE lt.league_team_hibernate=0 
	ORDER BY lt.league_team_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	echo "<div class=\"eden_league\">";
	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
	echo "		<tr>";
	echo "			<td class=\"eden_league_title_id\">"._LEAGUE_TEAM_LIST_ID."</td>";
	echo "			<td class=\"eden_league_title_country\">"._LEAGUE_TEAM_LIST_COUNTRY."</td>";
	echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_LIST_NAME."</td>";
	echo "			<td class=\"eden_league_title_game\">"._LEAGUE_TEAM_LIST_GAME."</td>";
	echo "		</tr>";
	$cislo = 0;
	while ($ar_team = mysql_fetch_array($res_team)){
		$res_games = mysql_query("
		SELECT g.clan_games_game 
		FROM $db_league_teams_sub AS lts 
		JOIN $db_clan_games AS g ON g.clan_games_id=lts.league_team_sub_game_id 
		WHERE lts.league_team_sub_team_id=".(integer)$ar_team['league_team_id']." ORDER BY g.clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$i=0;
		$games = "";
		while ($ar_games = mysql_fetch_array($res_games)){
			if ($i>0){$comma = ", ";} else {$comma = "";}
			$games .= $comma.stripslashes($ar_games['clan_games_game']);
		}
		if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
		echo "	<tr class=\"".$class."\">";
		echo "		<td align=\"right\" valign=\"top\">".$ar_team['league_team_id']."</td>";
		echo "		<td valign=\"top\"><img src=\"".$url_flags.$ar_team['country_shortname'].".gif\" alt=\"".stripslashes($ar_team['country_name'])."\" title=\"".stripslashes($ar_team['country_name'])."\"></td>";
		echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_team['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_team['league_team_name'])."</a></td>";
		echo "		<td valign=\"top\">".$games."</td>";
		echo "	</tr>";
		$cislo++;
	}
	echo "	</table>";
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueGetLastActiveLid
*
*		Vrati ID posledni aktivni ligy
*
***********************************************************************************************************/
function LeagueGetLastActiveLid($game = 0){
	
	global $db_league_leagues;
	
	// Pokud neni specifikovana hra - vybere se ze vsech dostupnych lig
	if ($game != 0){$where = "AND league_league_game_id=".(integer)$game."";} else {$where = "";}	
	$res = mysql_query("SELECT MAX(league_league_id) AS lid FROM $db_league_leagues WHERE league_league_active=1 ".$where) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar = mysql_fetch_array($res);
	return $ar['lid'];
}
/***********************************************************************************************************
*
*		LeagueGetLastActiveSid
*
*		Vrati ID posledni aktivni sezony
*
***********************************************************************************************************/
function LeagueGetLastActiveSid($lid = 0){
	
	global $db_league_seasons;
	
	if ($lid != 0){	
		$res = mysql_query("SELECT MAX(league_season_id) AS sid FROM $db_league_seasons WHERE league_season_league_id=".(integer)$lid." AND league_season_active=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar = mysql_fetch_array($res);
		return $ar['sid'];
	}
}
/***********************************************************************************************************
*
*		LeagueSetSeasonRound
*
*		Vybrani sezony/kola
*		$mode	-	all/teams/players - U
*
***********************************************************************************************************/
function LeagueSetSeasonRound($mode = "all"){
	
	global $db_league_seasons,$db_league_seasons_rounds;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lid'] = AGet($_GET,'lid');
	
	switch ($mode){
		case "players";
			$action = "league_results_players";
		break;
		case "teams";
			$action = "league_results_teams";
		break;
		default:
			$action = "league_results_rounds";
	}
	$output = "<h2>"._LEAGUE_RESULTS_SEASON."</h2>";
	$res_seasons = mysql_query("
	SELECT league_season_id, league_season_name, league_season_league_id 
	FROM $db_league_seasons 
	WHERE league_season_league_id=".(integer)$_GET['lid']." AND league_season_active=1 
	ORDER BY league_season_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$output .= "<p>";
	while ($ar_seasons = mysql_fetch_array($res_seasons)){
		if($ar_seasons['league_season_id'] == $_GET['sid']){$class = "eden_a_button_current";} else {$class = "eden_a_button";}
		$output .= "<span style=\"display: inline-block; min-width: 50px;margin: 0px 0px 20px 0px;\" class=\"".$class."\"><a href=\"index.php?action=".$action."&amp;lid=".$ar_seasons['league_season_league_id']."&amp;sid=".$ar_seasons['league_season_id']."&amp;filter=".$_GET['filter']."\" class=\"eden_a_button\">".stripslashes($ar_seasons['league_season_name'])."</a></span>";
	}
	$output .= "</p>";
	
	if ($_GET['sid'] && $mode == "all"){ $output .= "<h2>"._LEAGUE_RESULTS_ROUND."</h2>";
		$res_rounds = mysql_query("
		SELECT league_season_round_id, league_season_round_season_id, league_season_round_num, league_season_round_lock 
		FROM $db_league_seasons_rounds 
		WHERE league_season_round_season_id=".(integer)$_GET['sid']." 
		ORDER BY league_season_round_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$output .= "<p>";
		while ($ar_rounds = mysql_fetch_array($res_rounds)){
			if($ar_rounds['league_season_round_id'] == $_GET['rid']){$class = "eden_a_button_current";} elseif ($ar_rounds['league_season_round_lock'] == 0){$class = "eden_a_button_nonactive";} else {$class = "eden_a_button";}
			$output .= "<span style=\"display: inline-block; min-width: 20px;margin: 0px 0px 20px 0px;\" class=\"".$class."\"><a href=\"index.php?action=".$action."&amp;lid=".$_GET['lid']."&amp;sid=".$ar_rounds['league_season_round_season_id']."&amp;rid=".$ar_rounds['league_season_round_id']."&amp;filter=".$_GET['filter']."\" class=\"eden_a_button\">".$ar_rounds['league_season_round_num']."</a></span>";
		}
		$output .= "</p>";
	}
	return $output;
}
/***********************************************************************************************************
*
*		LeagueRoundsResults
*
*		Zobrazeni vysledku vybraneho kola
*
***********************************************************************************************************/
function LeagueRoundsResults(){
	
	global $db_admin,$db_admin_contact,$db_league_players,$db_league_teams,$db_league_teams_sub,$db_league_seasons,$db_league_seasons_rounds_results_teams;
	global $db_league_seasons_rounds_results_players,$db_country,$db_clan_games,$db_league_seasons_rounds;
	global $eden_cfg;
	global $url_flags;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<div class=\"eden_league\">";
	echo LeagueSetSeasonRound();
	if ($_GET['rid']){
		echo "<h2>"._LEAGUE_RESULTS_PLAYERS."</h2>";
	   	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		echo "		<tr>";
		echo "			<td class=\"eden_league_title\" style=\"width:40px;\">"._LEAGUE_RESULTS_PLACE."</td>";
		echo "			<td class=\"eden_league_title\" style=\"width:30px;\">"._LEAGUE_RESULTS_POINTS."</td>";
		echo "			<td class=\"eden_league_title_country\">"._LEAGUE_RESULTS_COUNTRY."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_NICK."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_TEAM."</td>";
		echo "		</tr>";
		$res_results_players = mysql_query("
		SELECT a.admin_id, a.admin_nick, c.country_shortname, c.country_name, lt.league_team_id, lt.league_team_name, lsrrp.league_season_round_result_player_points, lsrrp.league_season_round_result_player_place 
		FROM $db_league_seasons_rounds_results_players AS lsrrp 
		JOIN $db_league_players AS lp ON lp.league_player_id=lsrrp.league_season_round_result_player_player_id 
		LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
		JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
		JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
		JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
		WHERE lsrrp.league_season_round_result_player_round_id=".(integer)$_GET['rid']." 
		ORDER BY lsrrp.league_season_round_result_player_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$cislo=1;
	   	while ($ar_results_players = mysql_fetch_array($res_results_players)){
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
			echo "	<tr class=\"".$class."\">";
			echo "		<td align=\"right\" valign=\"top\"><strong>".$ar_results_players['league_season_round_result_player_place']."</strong></td>";
			echo "		<td valign=\"top\">".$ar_results_players['league_season_round_result_player_points']."</td>";
			echo "		<td valign=\"top\"><img src=\"".$url_flags.$ar_results_players['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_players['country_name'])."\" title=\"".stripslashes($ar_results_players['country_name'])."\"></td>";
			echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=player&mode=player_acc&id=".$ar_results_players['admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['admin_nick'])."</a></td>";
			echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_results_players['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['league_team_name'])."</a></td>";
			echo "	</tr>";
			$cislo++;
		}
		echo "	</table>";
		
		$res_results_teams = mysql_query("
		SELECT lt.league_team_id, lt.league_team_name, lsrrt.league_season_round_result_team_points, c.country_shortname, c.country_name 
		FROM $db_league_seasons_rounds_results_teams AS lsrrt 
		JOIN $db_league_teams AS lt ON lt.league_team_id=lsrrt.league_season_round_result_team_team_id 
		JOIN $db_country AS c ON c.country_id=lt.league_team_country_id 
		WHERE lsrrt.league_season_round_result_team_round_id=".(integer)$_GET['rid']." 
		ORDER BY lsrrt.league_season_round_result_team_points DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$cislo=1;
	   	while ($ar_results_teams = mysql_fetch_array($res_results_teams)){
			if ($cislo == 1){
				echo "<h2>"._LEAGUE_RESULTS_TEAMS."</h2>";
			   	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
				echo "		<tr>";
				echo "			<td class=\"eden_league_title\" style=\"width:40px;\">"._LEAGUE_RESULTS_PLACE."</td>";
				echo "			<td class=\"eden_league_title\" style=\"width:30px;\">"._LEAGUE_RESULTS_POINTS."</td>";
				echo "			<td class=\"eden_league_title_country\">"._LEAGUE_RESULTS_COUNTRY."</td>";
				echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_TEAM."</td>";
				echo "		</tr>";
			}
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
			echo "	<tr class=\"".$class."\">";
			echo "		<td align=\"right\" valign=\"top\"><strong>".$cislo."</strong></td>";		
			echo "		<td valign=\"top\">".$ar_results_teams['league_season_round_result_team_points']."</td>";
			echo "		<td valign=\"top\"><img src=\"".$url_flags.$ar_results_teams['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_teams['country_name'])."\" title=\"".stripslashes($ar_results_teams['country_name'])."\"></td>";
		   	echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_results_teams['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_teams['league_team_name'])."</a></td>";
			echo "	</tr>";
			$cislo++;
		}
		echo "	</table>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueSeasonTeamsResults - Je i v modul_leaggue.php
*
*		Zobrazeni vysledku tymu v sezone
*		$sid	- ID Sezony
*		$mode	- mod zobrazeni
*				1 - normalni v sirokem okne
*				2 - zuzeny do postranniho pruhu
*
***********************************************************************************************************/
function LeagueSeasonTeamsResults($sid = 0, $mode = 1){
	
	global $db_league_teams,$db_league_teams_sub,$db_league_seasons,$db_league_seasons_results_teams;
	global $db_country,$db_league_seasons_rounds,$db_league_awards;
	global $eden_cfg;
	global $url_flags,$url_league_awards;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($mode == 1){
		$mode_data = ", c.country_shortname, c.country_name";
		$mode_join = "JOIN $db_country AS c ON c.country_id=lt.league_team_country_id ";
		$mode_limit = "";
		$mode_class_table = "eden_league_table";
		$mode_place = _LEAGUE_RESULTS_PLACE;
	} else {
		$mode_data = "";
		$mode_join = "";
		$mode_limit = " LIMIT 10";
		$mode_class_table = "eden_league_table_side";
		$mode_place = "";
	}
	
	$res_results_teams = mysql_query("
	SELECT lt.league_team_id, lt.league_team_name, lsrt.league_season_result_team_points, lsrt.league_season_result_team_team_sub_id ".$mode_data." 
	FROM $db_league_seasons_results_teams AS lsrt 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lsrt.league_season_result_team_team_id 
	".$mode_join." 
	WHERE lsrt.league_season_result_team_season_id=".(integer)$sid." 
	ORDER BY lsrt.league_season_result_team_points DESC $mode_limit") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	
	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"".$mode_class_table."\">\n";
	$i = 1;
   	while ($ar_results_teams = mysql_fetch_array($res_results_teams)){
		
		// Call function
		$ar_award = LeagueCheckAwards(2,(integer)$sid,0,(integer)$ar_results_teams['league_season_result_team_team_sub_id']);
		
		if ($mode == 1){
			$mode_show_country_1 = "<td class=\"eden_league_title_country\" style=\"width:20px;\">"._LEAGUE_RESULTS_COUNTRY."</td>\n";
			$mode_show_country_2 = "<td valign=\"top\" align=\"left\"><img src=\"".$url_flags.$ar_results_teams['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_teams['country_name'])."\" title=\"".stripslashes($ar_results_teams['country_name'])."\"></td>\n";
		} else {
			$mode_show_country_1 = "";
			$mode_show_country_2 = "";
		}
		if ($i == 1){
		   	echo "		<tr>\n";
			echo "			<td class=\"eden_league_title\" style=\"width:20px;\">".$mode_place."</td>\n";
			echo "			<td class=\"eden_league_title\" style=\"width:30px;\">"._LEAGUE_RESULTS_POINTS."</td>\n";
			echo 			$mode_show_country_1;
			echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_TEAM."</td>\n";
			echo "		</tr>";
		}
		if ($i % 2 == 0){$class = "suda";} else { $class = "licha";}
		echo "	<tr class=\"".$class."\">\n";
		echo "		<td valign=\"top\" align=\"right\" valign=\"top\">"; if($ar_award['league_award_place']){echo "<img src=\"".$url_league_awards.$ar_award['league_award_img']."\" alt=\"".stripslashes($ar_award['league_award_name'])."\" title=\"".stripslashes($ar_award['league_award_name'])."\" />";} else { echo "<strong>".$i."</strong>"; } echo "</td>\n";
		echo "		<td valign=\"top\" align=\"left\">".$ar_results_teams['league_season_result_team_points']."</td>\n";
		echo 		$mode_show_country_2;
	   	echo "		<td valign=\"top\" align=\"left\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_home&ltid=".$ar_results_teams['league_team_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_teams['league_team_name'])."</a></td>\n";
		echo "	</tr>\n";
		$i++;
	}
	echo "	</table>\n";
}
/***********************************************************************************************************
*
*		LeagueSeasonPlayersResults - Je i v modul_league.php
*
*		Zobrazeni vysledku hracu v sezone
*
***********************************************************************************************************/
function LeagueSeasonPlayersResults(){
	
	global $db_admin,$db_admin_contact,$db_admin_guids,$db_league_teams,$db_league_teams_sub,$db_league_seasons,$db_league_seasons_results_players;
	global $db_country,$db_league_seasons_rounds,$db_league_players;
	global $eden_cfg;
	global $url_flags,$url_league_awards;
	
	echo "<div class=\"eden_league\">";
	echo LeagueSetSeasonRound("players");
	$res_results_players = mysql_query("
	SELECT a.admin_id, a.admin_nick, lt.league_team_id, lt.league_team_name, lsrp.league_season_result_player_player_id, lsrp.league_season_result_player_points, c.country_shortname, c.country_name, agid.admin_guid_guid  
	FROM $db_league_seasons_results_players AS lsrp 
	JOIN $db_league_players AS lp ON lp.league_player_id=lsrp.league_season_result_player_player_id 
	JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
	JOIN $db_admin_guids AS agid ON agid.aid=a.admin_id AND agid.admin_guid_game_id=lp.league_player_game_id 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
	JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
	WHERE lsrp.league_season_result_player_season_id=".(integer)$_GET['sid']." 
	ORDER BY lsrp.league_season_result_player_points DESC
	") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$i=1;
   	while ($ar_results_players = mysql_fetch_array($res_results_players)){
		
		// Call function
		$ar_award = LeagueCheckAwards(1,(integer)$_GET['sid'],(integer)$ar_results_players['league_season_result_player_player_id'],0);
		
		if ($i == 1){
			echo "<h2>"._LEAGUE_RESULTS_TEAMS."</h2>";
		   	echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
			echo "		<tr>";
			echo "			<td class=\"eden_league_title\" style=\"width:40px;\">"._LEAGUE_RESULTS_PLACE."</td>";
			echo "			<td class=\"eden_league_title\" style=\"width:30px;\">"._LEAGUE_RESULTS_POINTS."</td>";
			echo "			<td class=\"eden_league_title_country\">"._LEAGUE_RESULTS_COUNTRY."</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_NICK."</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_PLAYER_GUID."</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_RESULTS_TEAM."</td>";
			echo "		</tr>";
		}
		if ($i % 2 == 0){$class = "suda";} else { $class = "licha";}
		echo "	<tr class=\"".$class."\">";
		echo "		<td align=\"right\" valign=\"top\">"; if($ar_award['league_award_place']){echo "<img src=\"".$url_league_awards.$ar_award['league_award_img']."\" alt=\"".stripslashes($ar_award['league_award_name'])."\" title=\"".stripslashes($ar_award['league_award_name'])."\" />";} else { echo "<strong>".$i."</strong>"; } echo "</td>";		
		echo "		<td valign=\"top\">".$ar_results_players['league_season_result_player_points']."</td>";
		echo "		<td valign=\"top\"><img src=\"".$url_flags.$ar_results_players['country_shortname'].".gif\" alt=\"".stripslashes($ar_results_players['country_name'])."\" title=\"".stripslashes($ar_results_players['country_name'])."\"></td>";
		echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=player&amp;mode=player_acc&amp;id=".$ar_results_players['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['admin_nick'])."</a></td>";
		echo "		<td valign=\"top\">".$ar_results_players['admin_guid_guid']."</td>";
		echo "		<td valign=\"top\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_home&amp;ltid=".$ar_results_players['league_team_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_results_players['league_team_name'])."</a></td>";
		echo "	</tr>";
		$i++;
	}
	echo "	</table>";
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamGameAdd
*
*		Pridani nove hry
*
*		$ltid		= League Team ID
*
***********************************************************************************************************/
function LeagueTeamGameAdd($ltid = ""){
	
	global $db_league_teams,$db_league_teams_sub,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_TEAM_GAME_ADD."</h2>";
	/* Vyber z dostupnych her */
	$res_games = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_active=1 ORDER BY clan_games_game ASC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());	
	$i=0;
	$games = "";
	while ($ar_games = mysql_fetch_array($res_games)){
		$res_sub_teams = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".(integer)$ltid." AND league_team_sub_game_id=".(integer)$ar_games['clan_games_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_sub_teams = mysql_num_rows($res_sub_teams);
		// Nezobrazime hry ktere jsou jiz v teamu registrovany
		if ($num_sub_teams == 0){
			$games .= "		<option value=\"".$ar_games['clan_games_id']."\">".$ar_games['clan_games_game']."</option>";
			$i++;
		}
	}
	
	/* Pokud jiz je team registrovan ve vsech dostupnych ligach zobrazi se upozorneni */
	if ($i == 0){
		echo _LEAGUE_TEAM_GAME_REG_IN_ALL;
	} else {
		echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		echo "	<select name=\"league_game\" size=\"1\">";
		echo 		$games;
		echo "	</select>";
		echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
		echo "<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$ltid."\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"team_game_add\"><br><br>"; 
		echo "<input type=\"submit\" value=\""._LEAGUE_TEAM_GAME_ADD."\" class=\"eden_button\">";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamLeagueReg
*
*		Registrace teamu nebo hrace do ligy
*
***********************************************************************************************************/
function LeagueTeamLeagueReg($mode = ""){
	
	global $db_league_leagues,$db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_clan_games,$db_league_players_leagues,$db_league_players_bans;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['lid'] = AGet($_GET,'lid');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_GET['ltsid'] = AGet($_GET,'ltsid');
	
	if ($mode == "team_league_reg" || $mode == "team_league_reg_confirm"){
		$league_mode = 1; 
		$already_registered = _LEAGUE_TEAM_LEAGUE_REG_ALREADY;
		$query_conf = "$db_league_teams_sub_leagues WHERE league_teams_sub_league_league_id=".(integer)$_GET['lid']." AND league_teams_sub_league_team_id=".(integer)$_GET['ltid'];
		$input = "<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$_GET['ltid']."\"><input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$_GET['ltsid']."\">";
		$get_id = "ltid=".$_GET['ltid'];
		$mode_mode = "team_league_reg";
		$conf_reg_q = _LEAGUE_TEAM_LEAGUE_REG_Q;
	}
	if ($mode == "player_league_reg" || $mode == "player_league_reg_confirm"){
		$league_mode = 2;
		$already_registered = _LEAGUE_PLAYER_LEAGUE_REG_ALREADY;
		$query_conf = "$db_league_players_leagues WHERE league_player_league_league_id=".(integer)$_GET['lid']." AND league_player_league_player_id=".(integer)$_GET['lpid'];
		$input = "<input type=\"hidden\" name=\"lpid\" value=\"".(integer)$_GET['lpid']."\">";
		$get_id = "lpid=".$_GET['lpid'];
		$mode_mode = "player_league_reg";
		$conf_reg_q = _LEAGUE_PLAYER_LEAGUE_REG_Q;
	}
	
	echo "<div class=\"eden_league\">";
	echo "	<h2>"._LEAGUE_TEAM_LEAGUE_REG."</h2>";
	/* Vyber z dostupnych tymovych lig */
	if ($mode == "team_league_reg" || $mode == "player_league_reg"){
		$res_leagues = mysql_query("SELECT ll.league_league_id, ll.league_league_name, ll.league_league_active, ll.league_league_lock, g.clan_games_game, g.clan_games_id 
		FROM $db_league_leagues AS ll 
		JOIN $db_clan_games AS g ON g.clan_games_id=ll.league_league_game_id 
		WHERE league_league_active=1 AND league_league_mode=".$league_mode) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		
		echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		echo "	<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" class=\"eden_league_table\">";
		echo "		<tr>";
		echo "			<td class=\"eden_league_title_game\">"._CMN_GAME."</td>";
		echo "			<td class=\"eden_league_title\">"._CMN_NAME."</td>";
		echo "			<td class=\"eden_league_title\">&nbsp;</td>";
		echo "		</tr>";
		$i=0;
		$cislo = 0;
		while ($ar_leagues = mysql_fetch_array($res_leagues)){
			/********************************************/
			/*	TEAM
			/********************************************/
			if ($mode == "team_league_reg"){
				/* Zkontrolujeme zda-li uz je team registrovany v dane lize */
				$res_team = mysql_query("SELECT COUNT(*) FROM $db_league_teams_sub_leagues WHERE league_teams_sub_league_league_id=".(integer)$ar_leagues['league_league_id']." AND league_teams_sub_league_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_team = mysql_fetch_array($res_team);
				$res_team_sub = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".$_GET['ltid']." AND league_team_sub_game_id=".(integer)$ar_leagues['clan_games_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_team_sub = mysql_fetch_array($res_team_sub);
				if (!empty($ar_team_sub['league_team_sub_id'])){
					if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
		   			echo "	<tr class=\"".$class."\">";
					echo "		<td valign=\"top\">".stripslashes($ar_leagues['clan_games_game'])."</td>";
					echo "		<td valign=\"top\">".stripslashes($ar_leagues['league_league_name'])."</td>";
					echo "		<td valign=\"top\">"; if ($ar_leagues['league_league_lock'] == 1){ echo _LEAGUE_TEAM_LEAGUE_LOCKED;} elseif ($num_team[0] > 0){echo _LEAGUE_TEAM_LEAGUE_REG_ALREADY;} else { echo "<a href=\"index.php?action=league_team&amp;mode=team_league_reg_confirm&amp;ltid=".$_GET['ltid']."&amp;ltsid=".$ar_team_sub['league_team_sub_id']."&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_LEAGUE_REGISTER."</a>"; } echo "</td>";
					echo "	</tr>";
					$i++;
					$cislo++;
				}
			}
			/********************************************/
			/*	PLAYER
			/********************************************/
			if ($mode == "player_league_reg"){
				/* Zkontrolujeme zda-li uz je team registrovany v dane lize */
				$res_player = mysql_query("SELECT COUNT(*) 
				FROM $db_league_players_leagues 
				WHERE league_player_league_league_id=".(integer)$ar_leagues['league_league_id']." AND league_player_league_player_id=".(integer)$_GET['lpid']
				) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_player = mysql_fetch_array($res_player);
				$res_player_ban = mysql_query("
				SELECT league_player_ban_status 
				FROM $db_league_players_bans 
				WHERE league_player_ban_league_id=".(integer)$ar_leagues['league_league_id']." AND league_player_ban_player_id=".(integer)$_GET['lpid']." AND league_player_ban_removed_by_admin_id=0"
				) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$ar_player_ban = mysql_fetch_array($res_player_ban);
				if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
	   			echo "	<tr class=\"".$class."\">";
				echo "		<td valign=\"top\">".stripslashes($ar_leagues['clan_games_game'])."</td>";
				echo "		<td valign=\"top\">".stripslashes($ar_leagues['league_league_name'])."</td>";
				echo "		<td valign=\"top\">"; if ($ar_leagues['league_league_lock'] == 1){ echo _LEAGUE_TEAM_LEAGUE_LOCKED;} elseif ($num_player[0] > 0){echo _LEAGUE_PLAYER_LEAGUE_REG_ALREADY;} elseif ($ar_player_ban['league_player_ban_status'] == 1){ echo "<span class=\"red\">"._LEAGUE_PLAYER_LEAGUE_REG_BANNED."</span>";} else { echo "<a href=\"index.php?action=league_team&amp;mode=player_league_reg_confirm&amp;lpid=".$_GET['lpid']."&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_LEAGUE_REGISTER."</a>"; } echo "</td>";
				echo "	</tr>";
				$i++;
				$cislo++;
			}
		}
		/* Pokud jiz je team registrovan ve vsech dostupnych ligach zobrazi se upozorneni */
		if ($i == 0){
			echo "	<tr>";
			echo "		<td colspan=\"3\">"._LEAGUE_TEAM_LEAGUE_REG_IN_ALL."</td>";
			echo "	</tr>";
		}
		echo "	</table><br>";
	/* Potvrzeni registrace */
	} elseif ($mode == "team_league_reg_confirm" || $mode == "player_league_reg_confirm"){
		/* Zkontrolujeme zda-li uz je team registrovany v dane lize */
		$res = mysql_query("SELECT COUNT(*) FROM ".$query_conf."") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num = mysql_fetch_array($res);
		if ($num[0] > 0){
			echo "<p>".$already_registered."</p>";
		} else {
			$res_league = mysql_query("SELECT league_league_id, league_league_name, league_league_game_id FROM $db_league_leagues WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_league = mysql_fetch_array($res_league);
			echo "<p><strong>".stripslashes($ar_league['league_league_name'])."</strong> ".$conf_reg_q."</p>";
			echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "<iframe src=\"".$eden_cfg['url_edencms']."eden_iframe.php?imode=league_team_league_reg&project=".$_SESSION['project']."&lid=".$ar_league['league_league_id']."\" width=\"580\" height=\"300\" frameborder=\"1\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" noresize=\"noresize\"></iframe><br><br>";
			echo "<input type=\"checkbox\" name=\"league_reg_agree\" value=\"1\"> "._ADMIN_AGREEMENT."<br>";
			echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">";
			echo $input;
			echo "<input type=\"hidden\" name=\"lid\" value=\"".(integer)$_GET['lid']."\">";
			echo "<input type=\"hidden\" name=\"gid\" value=\"".(integer)$ar_league['league_league_game_id']."\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"".$mode_mode."\"><br>"; 
			echo "<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">";
			echo "</form><br>";
			
			echo "<form action=\"index.php?action=league_team&amp;mode=team_home&amp;".$get_id."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
			echo "<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button\">";
			echo "</form>";
		}
	} else {
		echo "<p>"._LEAGUE_TEAM_LEAGUE_REG_NO_MODE."</p>";
	}
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamLeagueLeave
*
*		Vystoupeni teamu z ligy
*
***********************************************************************************************************/
function LeagueTeamLeagueLeave(){
	
	global $db_league_leagues,$db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues,$db_clan_games;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['lid'] = AGet($_GET,'lid');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_GET['ltsid'] = AGet($_GET,'ltsid');
	$_GET['mode'] = AGet($_GET,'mode');
	
	$res_league = mysql_query("SELECT league_league_id, league_league_name FROM $db_league_leagues WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_league = mysql_fetch_array($res_league);
	
	if ($_GET['mode'] == "player_league_leave"){
		$title = _LEAGUE_PLAYER_LEAGUE_LEAVE_TITLE;
		$question = _LEAGUE_PLAYER_LEAGUE_LEAVE_Q;
		$reason_0 = _LEAGUE_PLAYER_LEAGUE_LEAVE_R_0;
		$reason_1 = _LEAGUE_PLAYER_LEAGUE_LEAVE_R_1;
		$reason_2 = _LEAGUE_PLAYER_LEAGUE_LEAVE_R_2;
		$reason_3 = _LEAGUE_PLAYER_LEAGUE_LEAVE_R_3;
		$link_mode = "mode=player_home&amp;lpid=".$_GET['lpid'];
	} elseif ($_GET['mode'] == "team_league_leave") {
		$title = _LEAGUE_TEAM_LEAGUE_LEAVE_TITLE;
		$question = _LEAGUE_TEAM_LEAGUE_LEAVE_Q;
		$reason_0 = _LEAGUE_TEAM_LEAGUE_LEAVE_R_0;
		$reason_1 = _LEAGUE_TEAM_LEAGUE_LEAVE_R_1;
		$reason_2 = _LEAGUE_TEAM_LEAGUE_LEAVE_R_2;
		$reason_3 = _LEAGUE_TEAM_LEAGUE_LEAVE_R_3;
		$link_mode = "mode=team_home&amp;ltid=".$_GET['ltid'];
	}
	echo "<div class=\"eden_league\">";
	echo "	<h2>".$title."</h2>";
	echo "	<p><strong>".stripslashes($ar_league['league_league_name'])."</strong>".$question."</p>";
	echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
	echo "		<select name=\"league_team_leave_reason\" size=\"1\">";
	echo "			<option value=\"".$reason_1."\">".$reason_1."</option>";
	echo "	   		<option value=\"".$reason_2."\">".$reason_2."</option>";
	echo "	   		<option value=\"".$reason_3."\">".$reason_3."</option>";
	echo "	   		<option value=\"".$reason_0."\">".$reason_0."</option>";
	echo "		</select><br>";
   	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">";
	echo "		<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$_GET['ltid']."\">";
	echo "		<input type=\"hidden\" name=\"lpid\" value=\"".(integer)$_GET['lpid']."\">";
	echo "		<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$_GET['ltsid']."\">";
	echo "		<input type=\"hidden\" name=\"lid\" value=\"".(integer)$_GET['lid']."\">";
	echo "		<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\"><br>";
	echo "		<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">";
	echo "	</form><br>";
	
	echo "	<form action=\"index.php?action=league_team&amp;".$link_mode."&amp;project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
	echo "		<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button\">";
	echo "	</form>";
	echo "</div>";
}
/***********************************************************************************************************
*
*		LeagueTeamDraft
*
*		Vyhledavani volnych hracu/tymu
*
*		$mode = small/big
*
***********************************************************************************************************/
function LeagueTeamDraft($mode = "big"){
	
	global $db_league_teams,$db_league_teams_sub,$db_league_players,$db_clan_games,$db_admin,$db_league_draft;
	global $eden_cfg;
	global $url_games;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($mode == "small"){ $limit = " LIMIT 10 "; } else { $limit = "";}
	
	// Delete all drafts older than 14 days
	$date_14 =  time() - 60 * 60 * 24 * 14;
	$date_del = date('Y-m-d H:i:s',$date_14);
	mysql_query("DELETE FROM $db_league_draft WHERE league_draft_date < '".$date_del."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	// Zobrazeni vsech aktualnich pohledavek
	$res_draft = mysql_query("
	SELECT ld.league_draft_id, ld.league_draft_admin_id, ld.league_draft_mode, ld.league_draft_player_id, ld.league_draft_positions, ld.league_draft_date, a.admin_nick, a.admin_id, lt.league_team_id, lt.league_team_name, g.clan_games_shortname, g.clan_games_game 
	FROM $db_league_draft AS ld 
	LEFT JOIN $db_league_players AS lp ON lp.league_player_id=ld.league_draft_player_id 
	LEFT JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
	LEFT JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=ld.league_draft_team_sub_id 
	LEFT JOIN $db_league_teams AS lt ON lt.league_team_id=lts.league_team_sub_team_id 
	LEFT JOIN $db_clan_games AS g ON g.clan_games_id=ld.league_draft_game_id 
	ORDER BY league_draft_id DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	
	// Overeni zda je uzivatel vlastnik tymu
	$res_owner = mysql_query("
	SELECT a.admin_team_own_id, lt.league_team_id, lt.league_team_name, lp.league_player_game_id, lp.league_player_team_sub_id 
	FROM $db_admin AS a 
	JOIN $db_league_teams AS lt ON lt.league_team_id=a.admin_team_own_id 
	JOIN $db_league_players AS lp ON lp.league_player_admin_id=a.admin_id AND lp.league_player_team_id=a.admin_team_own_id 
	WHERE a.admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_owner = mysql_fetch_array($res_owner);
	
	// Overeni zda je uzivatel kapitan nejakeho/nejakych tymu
	$res_captain = mysql_query("
	SELECT lp.league_player_position_captain, lp.league_player_team_sub_id, lp.league_player_game_id, lt.league_team_id, lt.league_team_name 
	FROM $db_league_players AS lp 
	JOIN $db_league_teams AS lt ON lt.league_team_id=lp.league_player_team_id 
	WHERE lp.league_player_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
	$ar_captain = mysql_fetch_array($res_captain);
	
	if ($ar_owner['admin_team_own_id'] > 0){
		$ltid = $ar_owner['league_team_id'];
		$ltsid = $ar_owner['league_player_team_sub_id'];
	} elseif ($ar_captain['league_player_position_captain'] == 1){
		$ltid = $ar_captain['league_team_id'];
		$ltsid = $ar_captain['league_player_team_sub_id'];
	} else {
		$ltsid = 0;
	}
	
	/* BIG - FOR CENRAL PANEL */
	if ($mode == "big"){
		// Nacteni aktivnich her
		$res_game = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games WHERE clan_games_active=1") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_game = mysql_num_rows($res_game);
		
		echo "<div class=\"eden_league\">";
		echo "	<h2>"._LEAGUE_DRAFT."</h2>";
		// Zalozime ucet pro pozadovanou hru
		LeagueAddPlayer(0,1,0,0,0,0,0);
		echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
		// Platne pro majitele nebo kapitana tymu
		if ($ar_owner['admin_team_own_id'] > 0 || $ar_captain['league_player_position_captain'] == 1){
			echo "	<p>"._LEAGUE_DRAFT_CHOSE_TEAM."</p>";
		} else {
			echo "	<p>"._LEAGUE_DRAFT_CHOSE_GAME."</p>";
		}
		
		// Zobrazi se vyber hry, pro kterou hleda hrac team
		// V pripade ze hrace hleda kapitan podtymu, zobrazi se mu na vyber hry, ve kterych je kapitan
		// V pripade ze hrace do tymu hleda majitel tymu, zobrazi se mu vsechny hry, ve kterych ma jeho tym registrovane podtymy
	  	echo "	 <select name=\"league_draft_game\" size=\"1\">";
		while ($ar_game = mysql_fetch_array($res_game)){
			// Platne pro majitele nebo kapitana tymu
			if ($ar_owner['admin_team_own_id'] > 0 || $ar_captain['league_player_position_captain'] == 1){
				// Provereni podtymu a jejich her
				$res_team_sub = mysql_query("SELECT COUNT(*) FROM $db_league_teams_sub WHERE league_team_sub_game_id=".(integer)$ar_game['clan_games_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_team_sub = mysql_fetch_array($res_team_sub);
				if (($ar_owner['admin_team_own_id'] > 0 && $num_team_sub[0] == 1) || ($ar_captain['league_player_position_captain'] == 1 && $ar_captain['league_player_game_id'] == $ar_game['clan_games_id'])){
					echo "		 <option value=\"".$ar_game['clan_games_id']."\">".stripslashes($ar_owner['league_team_name'])." - ".$ar_game['clan_games_game']."</option>";
				}
			// Platne pro hrace bez tymu
			} else {
				echo "		  <option value=\"".$ar_game['clan_games_id']."\">".$ar_game['clan_games_game']."</option>";
			}
		} 
		echo "	 </select><br>";
		// Platne pro majitele nebo kapitana tymu
		if ($ar_owner['admin_team_own_id'] > 0 || $ar_captain['league_player_position_captain'] == 1){
	   		echo "	 <p>"._LEAGUE_DRAFT_CHOSE_TEAM_PLAYERS."</p>";
			echo "	 <select name=\"league_draft_players\" size=\"1\">";
			echo "	 	<option value=\"1\">1</option>";
			echo "	 	<option value=\"2\">2</option>";
			echo "	 	<option value=\"3\">3</option>";
			echo "	 	<option value=\"4\">4</option>";
			echo "	 	<option value=\"5\">5</option>";
			echo "	 </select><br>";
			echo "		<input type=\"hidden\" name=\"team\" value=\"1\">";
			echo "		<input type=\"hidden\" name=\"ltid\" value=\"".$ltid."\">";
		}
		echo "		<input type=\"hidden\" name=\"mode\" value=\"team_draft\">";
	   	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\"><br>";
		echo "		<input type=\"submit\" value=\""; if ($ar_owner['admin_team_own_id'] == 0){ echo _LEAGUE_DRAFT_SEARCHING_TEAM;} else {echo _LEAGUE_DRAFT_SEARCHING_PLAYERS;} echo "\" class=\"eden_button\">";
		echo "	</form><br>";
		
		echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		echo "		<tr>";
		echo "			<td class=\"eden_league_title_draft_b_team\">&nbsp;</td>";
		echo "			<td class=\"eden_league_title_draft_b_game\">"._LEAGUE_DRAFT_GAME."</td>";
		echo "			<td class=\"eden_league_title_draft_b_id\">ID</td>";
		echo "			<td class=\"eden_league_title_draft_b_date\">"._CMN_DATE."</td>";
		echo "			<td class=\"eden_league_title_draft_b_name\">"._LEAGUE_DRAFT_NAME."</td>";
		echo "			<td class=\"eden_league_title_draft_b_msg\">"._LEAGUE_DRAFT_MSG."<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\"></td>";
		echo "			<td class=\"eden_league_title_draft_b_pm\">&nbsp;</td>";
		echo "		</tr>";
		$cislo = 1;
		while ($ar_draft = mysql_fetch_array($res_draft)){
			if ($ar_draft['league_draft_mode'] == 1){
				$img = "player";
				$alt = _LEAGUE_DRAFT_PLAYER_LOOKING;
				$id = $ar_draft['league_draft_player_id'];
				$name = $ar_draft['admin_nick'];
				$link = "player&mode=player_acc&id=".$ar_draft['admin_id'];
				$message = _LEAGUE_DRAFT_MSG_PLAYER;
			} else {
				$img = "team";
				$alt = _LEAGUE_DRAFT_TEAM_LOOKING;
				$id = $ar_draft['league_team_id'];
				$name = $ar_draft['league_team_name'];
				$link = "league_team&mode=team_home&ltid=".$ar_draft['league_team_id']."";
				$message = _LEAGUE_DRAFT_MSG_TEAM." - ".$ar_draft['league_draft_positions'];
			}
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
			echo "		<tr class=\"".$class."\">";
			echo "			<td><img src=\"./images/eden_league_".$img.".gif\" width=\"12\" height=\"12\" alt=\"".$alt."\" title=\"".$alt."\"></td>";
			echo "			<td><img src=\"".$url_games.$ar_draft['clan_games_shortname'].".gif\" width=\"12\" height=\"12\" alt=\"".stripslashes($ar_draft['clan_games_game'])."\" title=\"".stripslashes($ar_draft['clan_games_game'])."\"></td>";
			echo "			<td>".$id."</td>";
			echo "			<td>".FormatDatetime($ar_draft['league_draft_date'],"d.m.Y H:i:s")."</td>";
			echo "			<td><a href=\"".$eden_cfg['url']."index.php?action=".$link."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($name)."</a></td>";
			echo "			<td>".$message."</td>";
			echo "			<td>"; if ($_SESSION['loginid'] == $ar_draft['league_draft_admin_id']){echo "<input type=\"checkbox\" name=\"league_draft_del\" value=\"".$ar_draft['league_draft_id']."\">";} else {echo "<a href=\"index.php?action=forum&faction=pm&pm_rec=".$ar_draft['league_draft_admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><img src=\"./images/sys_message.gif\" width=\"15\" height=\"10\" alt=\""._CMN_PM_SEND_PM."\" title=\""._CMN_PM_SEND_PM."\"></a>";} echo "</td>";
			echo "		<tr>";
			$cislo++;
		}
		echo "		<tr>";
		echo "			<td colspan=\"6\">";
		echo "	   			<input type=\"hidden\" name=\"mode\" value=\"team_draft_del\">";
	   	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\"><br>";
		echo "				<input type=\"submit\" value=\""._LEAGUE_DRAFT_BUTTON_DEL."\" class=\"eden_button\">";
		echo "				</form>";
		echo "			</td>";
		echo "		</tr>";
		echo "</table>";
		echo "</div>";
	}
	
	/* SMALL - FOR SIDE PANEL */
	if ($mode == "small"){
		$cislo = 1;
		echo "<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\">";
		while ($ar_draft = mysql_fetch_array($res_draft)){
			$message = FALSE;
			if ($ar_draft['league_draft_mode'] == 1){
				$img = "player";
				$alt = _LEAGUE_DRAFT_PLAYER_LOOKING;
				$name = $ar_draft['admin_nick'];
				$link = "player&mode=player_acc&id=".$ar_draft['admin_id'];
			} else {
				$img = "team";
				$alt = _LEAGUE_DRAFT_TEAM_LOOKING;
				$name = $ar_draft['league_team_name'];
				$link = "league_team&mode=team_home&ltid=".$ar_draft['league_team_id']."";
				$message = $ar_draft['league_draft_positions'];
			}
			if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
			echo "	<tr class=\"".$class."\">";
			echo "		<td class=\"eden_league_draft_s_team\"><img src=\"./images/eden_league_".$img.".gif\" width=\"12\" height=\"12\" alt=\"".$alt."\" title=\"".$alt."\"></td>";
			echo "		<td><a href=\"".$eden_cfg['url']."index.php?action=".$link."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">"; echo ShortText(stripslashes($name),12); echo "</a></td>";
			echo "		<td class=\"eden_league_draft_s_msg\">".$message."</td>";
			echo "		<td class=\"eden_league_draft_s_pm\"><a href=\"index.php?action=forum&faction=pm&pm_rec=".$ar_draft['league_draft_admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><img src=\"./images/sys_message.gif\" width=\"15\" height=\"10\" alt=\""._CMN_PM_SEND_PM."\" title=\""._CMN_PM_SEND_PM."\"></a></td>";
			echo "	<tr>";
			$cislo++;
		}
		echo "	<tr>";
		echo "		<td colspan=\"4\" style=\"width:100%;text-align:middle;\"><a href=\"index.php?action=league_team&amp;mode=team_draft&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_DRAFT."</a></td>";
		echo "	<tr>";
		echo "</table>";
	}
}
/***********************************************************************************************************
*
*		LeagueTeamHome
*
*		Zobrazi informace o teamu
*
*		$ltid	- League Team ID
*
***********************************************************************************************************/
function LeagueTeamHome($ltid = 0){
	
	global $db_admin,$db_league_players,$db_league_requests,$db_league_teams,$db_league_teams_sub,$db_league_teams_sub_leagues;
	global $db_league_leagues,$db_admin_contact,$db_admin_guids,$db_country,$db_clan_games;
	global $url_flags,$url_league_team;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_GET['ltid'] = AGet($_GET,'ltid');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($ltid != 0) {
		$res_team = mysql_query("
		SELECT a.admin_id, a.admin_nick,lt.league_team_owner_id, lt.league_team_name, lt.league_team_web, lt.league_team_date_last_modified, lt.league_team_logo, c.country_name 
		FROM $db_league_teams AS lt 
		JOIN $db_country AS c ON c.country_id=lt.league_team_country_id 
		JOIN $db_admin AS a ON a.admin_id=lt.league_team_owner_id 
		WHERE lt.league_team_id=".(integer)$ltid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_team = mysql_fetch_array($res_team);
		if ($ar_team['league_team_logo'] != ""){$team_logo = $ar_team['league_team_logo'];} else {$team_logo = "0.jpg";}
		echo "	<div class=\"eden_league\">";
		echo "		<h2>"._LEAGUE_TEAM_HOME_GENERAL."</h2>";
		echo "		<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		echo "			<tr>";
		echo "				<td class=\"suda\" rowspan=\"6\" width=\"150\"><img src=\"".$url_league_team.$team_logo."\" alt=\"".stripslashes($ar_team['league_team_name'])."\" title=\"".stripslashes($ar_team['league_team_name'])."\"></td>";
		echo "				<td class=\"suda\">ID</td>";
		echo "				<td class=\"suda\"><h3>".$ltid."</h3></td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"licha\">"._LEAGUE_TEAM_HOME_TEAM_NAME."</td>";
		echo "				<td class=\"licha\"><h3>".stripslashes($ar_team['league_team_name'])."</h3></td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"suda\">"._LEAGUE_TEAM_HOME_WWW."</td>";
		echo "				<td class=\"suda\">".stripslashes($ar_team['league_team_web'])."</td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"licha\">"._LEAGUE_TEAM_HOME_COUNTRY."</td>";
		echo "				<td class=\"licha\">".stripslashes($ar_team['country_name'])."</td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"suda\">"._LEAGUE_TEAM_HOME_OWNER."</td>";
		echo "				<td class=\"suda\"><a href=\"index.php?action=player&amp;mode=player_acc&amp;id=".$ar_team['admin_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_team['admin_nick'])."</a> "; if ($ar_team['admin_id'] != $_SESSION['loginid']){echo "<a href=\"index.php?action=forum&faction=pm&pm_rec=".$ar_team['admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><img src=\"./images/sys_message.gif\" width=\"15\" height=\"10\" alt=\""._CMN_PM_SEND_PM."\" title=\""._CMN_PM_SEND_PM."\"></a>";} echo "</td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"licha\">"._LEAGUE_TEAM_HOME_LAST_CHANGES."</td>";
		echo "				<td class=\"licha\">".FormatDatetime($ar_team['league_team_date_last_modified'],"d.m.Y H:i:s")."</td>";
		echo "			</tr>";
		echo "				<td colspan=\"2\">";
		echo "					<a href=\"index.php?action=league_team&amp;mode=team_awards&amp;ltid=".$ltid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_AWARDS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
		echo "					<a href=\"index.php?action=league_team&amp;mode=team_log_team&amp;ltid=".$ltid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_LOG."</a>";
								if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid) {echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=league_team&amp;mode=team_hibernate_check&amp;ltid=".$ltid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_HIBERNATE."</a>";}
		echo "				</td>";
		echo "			</tr>";
		echo "		</table><br>";
		$res_sub_teams = mysql_query("
		SELECT lts.league_team_sub_id, g.clan_games_game
		FROM $db_league_teams_sub AS lts 
		JOIN ($db_clan_games AS g) ON (g.clan_games_id=lts.league_team_sub_game_id) 
		WHERE league_team_sub_team_id=".(integer)$ltid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$num_sub_teams = mysql_num_rows($res_sub_teams);
		/* V pripade kdy je vice podteamu zobrazi se nazev Podteamy */
		if ($num_sub_teams > 1){
		 	echo "		<h2>"._LEAGUE_TEAM_HOME_SUB_TEAMS."</h2>";
		}
		while ($ar_sub_teams = mysql_fetch_array($res_sub_teams)){
			/* Pokud je nekdo vlastnik teamu, muze pozvat dalsi hrace */
			$res_captain = mysql_query("
			SELECT a.admin_team_own_id, lp.league_player_position_captain, lp.league_player_position_assistant, lp.league_player_position_player, lp.league_player_team_id, lp.league_player_team_sub_id 
			FROM $db_league_players AS lp 
			JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
			WHERE league_player_admin_id=".(integer)$_SESSION['loginid']." AND league_player_team_sub_id=".(integer)$ar_sub_teams['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$ar_captain = mysql_fetch_array($res_captain);
			$res_players = mysql_query("
			SELECT a.admin_id, a.admin_team_own_id, lp.league_player_admin_id, lp.league_player_id, lp.league_player_position_captain, lp.league_player_position_assistant, 
			lp.league_player_position_player, lp.league_player_team_confirm, lp.league_player_player_confirm, c.country_shortname, c.country_name, a.admin_id, a.admin_nick, a.admin_lang, ag.admin_guid_guid 
			FROM $db_league_players AS lp 
			JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id
			JOIN $db_admin_contact AS ac ON ac.aid=a.admin_id 
			LEFT JOIN $db_admin_guids AS ag ON ag.aid=lp.league_player_admin_id AND ag.admin_guid_game_id=lp.league_player_game_id 
			JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
			WHERE league_player_team_sub_id=".(integer)$ar_sub_teams['league_team_sub_id']." 
			ORDER BY league_player_position_captain DESC, league_player_position_assistant DESC, league_player_position_player DESC") or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_players = mysql_num_rows($res_players);
			$cislo = 0;
			echo "<p>&nbsp;</p>";
			echo" <h2>".$ar_sub_teams['clan_games_game']."</h2>";
			/*********************************************************************************/
			/*	Hraci */
			/*********************************************************************************/
			echo "<h3>"._LEAGUE_TEAM_HOME_PLAYERS."</h3> ";
			if (LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid){
				echo "<a href=\"index.php?action=league_team&amp;mode=team_player_check&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_PLAYER_ADD."</a>";
			}
			echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
			echo "		<tr>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_COUNTRY."</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_NICK."</td>";
			echo "			<td class=\"eden_league_title\">PM</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_POSITION."</td>";
			/* Zobrazime jen povolanym */
			if ($ar_captain['admin_team_own_id'] == $ltid || $ar_captain['league_player_position_captain'] == 1 || $ar_captain['league_player_position_assistant'] == 1 || $ar_captain['league_player_position_player'] == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid){
				echo "		<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_OPTIONS."</td>";
			}
			echo "		</tr>";
			while ($ar_players = mysql_fetch_array($res_players)){
				// In case where player is also team owner - add owner position to the list
				if ($ar_team['league_team_owner_id'] == $ar_players['admin_id']){$team_owner = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_OWNER."/";} else {$team_owner = FALSE;}
				if ($ar_players['league_player_player_confirm'] == 0){
					$player_position = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PENDING_PLAYER;
				} elseif ($ar_players['league_player_team_confirm'] == 0){
					$player_position = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PENDING_TEAM;
				} elseif ($ar_players['league_player_position_captain'] == 1){
					$player_position = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_CAPTAIN;
				} elseif ($ar_players['league_player_position_assistant'] == 1){
					$player_position = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_ASSISTANT;
				} else {
					$player_position = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PLAYER;
				}
				$res_team_players = mysql_query("SELECT COUNT(*) FROM $db_league_players WHERE league_player_team_id=".(integer)$_GET['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
				$num_team_players = mysql_fetch_array($res_team_players);
				if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
				echo "	<tr class=\"".$class."\">";
				echo "		<td><img src=\"".$url_flags.$ar_players['country_shortname'].".gif\" alt=\"".stripslashes($ar_players['country_name'])."\" title=\"".stripslashes($ar_players['country_name'])."\"></td>";
				echo "		<td><a href=\"index.php?action=player&mode=player_acc&id=".$ar_players['admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_players['admin_nick'])."</a></td>";
				echo "		<td>"; if ($ar_players['admin_id'] != $_SESSION['loginid']){echo "<a href=\"index.php?action=forum&faction=pm&pm_rec=".$ar_players['admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><img src=\"./images/sys_message.gif\" width=\"15\" height=\"10\" alt=\""._CMN_PM_SEND_PM."\" title=\""._CMN_PM_SEND_PM."\"></a>";} echo "</td>";
				echo "		<td>".$team_owner.$player_position.""; if (empty($ar_players['admin_guid_guid'])){ if ($_SESSION['loginid'] == $ar_players['admin_id']){echo "<a href=\"index.php?action=user_edit&mode=guids&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><span class=\"red\"> ("._LEAGUE_PLAYER_GUID_NO_2.")</span></a>";} else { echo "<span class=\"red\"> ("._LEAGUE_PLAYER_GUID_NO_2.")</span>";}} echo "</td>";
				/* Zobrazime jen povolanym */
				if ($ar_captain['admin_team_own_id'] == $ltid || $ar_captain['league_player_position_captain'] == 1 || $ar_captain['league_player_position_assistant'] == 1 || $ar_captain['league_player_position_player'] == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid){
					echo "	<td>";
								if ($_SESSION['loginid'] == $ar_players['league_player_admin_id']){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_player_leave&ltid=".$ltid."&ltsid=".$ar_sub_teams['league_team_sub_id']."&lang=".$ar_players['admin_lang']."\" target=\"_self\">"._LEAGUE_PLAYER_LEAVE_TEAM."</a> &nbsp; ";}
								if (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid && $_SESSION['loginid'] != $ar_players['league_player_admin_id'] && $ar_players['admin_team_own_id'] == 0){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_player_make_o&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;pid=".(integer)$ar_players['league_player_id']."&amp;lang=".$ar_players['admin_lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_PLAYER_MAKE_OWNER."</a> &nbsp; ";}
								if ((($ar_captain['league_player_position_captain'] == 1 && $ar_captain['league_player_team_sub_id'] == $ar_sub_teams['league_team_sub_id']) || (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid)) && $ar_players['league_player_position_captain'] == 0){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_player_make_c&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;pid=".(integer)$ar_players['league_player_id']."&amp;lang=".$ar_players['admin_lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_PLAYER_MAKE_CAPTAIN."</a> &nbsp; ";}
								/*if (($ar_captain['league_player_position_captain'] == 1 && $_SESSION['loginid'] != $ar_players['league_player_admin_id'] && $ar_players['league_player_position_assistant'] == 0) || (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid && $ar_players['league_player_position_assistant'] == 0)){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&mode=team_player_make_a&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;pid=".(integer)$ar_players['league_player_id']."&amp;lang=".$ar_admin['admin_lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_PLAYER_MAKE_ASSISTANT."</a> &nbsp; ";}*/
								if (($ar_captain['league_player_position_captain'] == 1 && $_SESSION['loginid'] != $ar_players['league_player_admin_id'] && $ar_players['league_player_position_player'] == 0) || (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid && $ar_players['league_player_position_player'] == 0)){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_player_make_p&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;pid=".(integer)$ar_players['league_player_id']."&amp;lang=".$ar_players['admin_lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_PLAYER_MAKE_PLAYER."</a> &nbsp; ";}
								if (($ar_captain['league_player_position_captain'] == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid)&& $_SESSION['loginid'] != $ar_players['league_player_admin_id'] && $ar_players['league_player_admin_id'] != $ar_team['admin_id']){ echo "<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_player_kick&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;pid=".(integer)$ar_players['league_player_id']."&amp;lang=".$ar_players['admin_lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_PLAYER_KICK."</a> &nbsp; ";}
					echo "	</td>";
				}
				echo "	</tr>";
				$cislo++;
			}
			echo "	</table><br>";
			
			/*********************************************************************************/
			/* Ligy ve kterých je tým přihlášen */
			/*********************************************************************************/
			echo "<p>&nbsp;</p>";
			echo "	<h3>"._LEAGUE_TEAM_HOME_TEAM_LEAGUES."</h3>";
			if (LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == 1 || LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid){
				echo "	<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=team_league_reg&amp;ltid=".$ltid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_TEAM_LEAGUE_REG_TO."</a>";
			}
			echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		   	echo "		<tr>";
		   	echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_NAME."</td>";
		   	echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_STATUS."</td>";
			echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_OPTIONS."</td>";
		   	echo "		</tr>";
			$res_leagues = mysql_query("
			SELECT l.league_league_team_sub_min_players, l.league_league_team_sub_max_players, l.league_league_name, l.league_league_id, ltsl.league_teams_sub_league_players 
			FROM $db_league_teams_sub_leagues AS ltsl 
			JOIN $db_league_leagues AS l ON l.league_league_id=ltsl.league_teams_sub_league_league_id 
			WHERE league_teams_sub_league_team_sub_id=".(integer)$ar_sub_teams['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while ($ar_leagues = mysql_fetch_array($res_leagues)){
				/* Spocitame kolik je aktivnich hracu v teamu pro danou ligu */
				$league_allowed_player_id = explode("#",$ar_leagues['league_teams_sub_league_players'],-1);
				$league_allowed_player_num = count($league_allowed_player_id);
				// Pokud je nastavena nula u obou atributu tak se nemusi vybirat hraci, kteri se zucastni ligy
				if ($ar_leagues['league_league_team_sub_max_players'] == 0 && $ar_leagues['league_league_team_sub_min_players'] == 0){
					$team_status = "<span class=\"green\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_STATUS_OK."</span>";
					$show_chosen_players = 0;
				// Pokud je v podtymu vice hracu nez kolik dovoluji pravidla ligy zobrazi se varovani
				} elseif ($ar_leagues['league_league_team_sub_max_players'] != 0 && $league_allowed_player_num > $ar_leagues['league_league_team_sub_max_players']){
					$team_status = "<span class=\"red\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_STATUS_OVER."</span>";
					$show_chosen_players = 1;
				// Pokud je v podtymu mene hracu nez kolik dovoluji pravidla ligy zobrazi se varovani
				} elseif ($ar_leagues['league_league_team_sub_min_players'] != 0 && $league_allowed_player_num < $ar_leagues['league_league_team_sub_min_players']){
					$team_status = "<span class=\"red\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_STATUS_LESS."</span>";
					$show_chosen_players = 1;
				// Pokud je nastaveno v lize pocet min a max hracu a podtym podminku splnuje zobrazi se OK
				} else {
					$team_status = "<span class=\"green\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_STATUS_OK."</span>";
					$show_chosen_players = 1;
				}
				echo "	<tr class=\"eden_sub_title\">";
				echo "		<td><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_leagues['league_league_name'])."</strong></a></td>";
				echo "		<td>".$team_status."</td>";
				echo "		<td>"; if ($ar_captain['admin_team_own_id'] == $ltid || $ar_captain['league_player_position_captain'] == 1){ echo "<a href=\"index.php?action=league_team&mode=team_league_leave&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_LEAVE."</a>";} echo "</td>";
				echo "	</tr>";
				if ($show_chosen_players == 1){
					$res_league_players = mysql_query("
					SELECT a.admin_id, a.admin_nick, lp.league_player_id, ag.admin_guid_guid 
					FROM $db_league_players AS lp 
					JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
					LEFT JOIN $db_admin_guids AS ag ON ag.aid=lp.league_player_admin_id AND ag.admin_guid_game_id=lp.league_player_game_id 
					WHERE league_player_team_sub_id=".(integer)$ar_sub_teams['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
					echo "	<tr class=\"eden_sub_title\">";
					echo "		<td>"._LEAGUE_TEAM_LEAGUE_ALLOWED_PLAYERS."";
									// Majiteli nebo kapitanovi zobrazime moznost urcovat kdo bude hrat ligu v pripade, ze je v teamu vice clenu, nez umoznuje dana liga
									if ((LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == True)){
										echo "<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=league_team&project=".$_SESSION['project']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" enctype=\"multipart/form-data\" method=\"post\">";
									}
					echo "		</td>";
					echo "		<td colspan=\"2\">"._LEAGUE_PLAYER_GUID_STATUS."</td>";
					echo "	</tr>";
					$i=1;
					$cislo=0;
					while ($ar_league_players = mysql_fetch_array($res_league_players)){
						if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
						echo "	<tr class=\"".$class."\">";
						echo "		<td>";
						// Pokud ma dany hrac zadany GUID k teto hre, zobrazime checkbox
						if ($ar_league_players['admin_guid_guid'] && (LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == True)){
							echo "			<input type=\"hidden\" name=\"allowed_player_num\" value=\"".$i."\">";
					   		echo "	   		<input type=\"hidden\" name=\"allowed_player_data[".$i."_player_id]\" value=\"".$ar_league_players['league_player_id']."\">";
							echo "			<input type=\"checkbox\" name=\"allowed_player_data[".$i."_player_allowed]\" value=\"1\" "; if (in_array($ar_league_players['league_player_id'],$league_allowed_player_id)){echo "checked=\"checked\"";} echo "/> ";
						} else {
							echo "<img src=\"./images/"; if (in_array($ar_league_players['league_player_id'],$league_allowed_player_id)){echo "yes"; $alt = _LEAGUE_PLAYER_PLAY;} else {echo "no"; $alt = _LEAGUE_PLAYER_NO_PLAY;} echo ".gif\" width=\"15\" height=\"15\" alt=\"".$alt."\" title=\"".$alt."\">";
						}
						echo "			<a href=\"index.php?action=player&mode=player_acc&id=".$ar_league_players['admin_id']."&lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\">".stripslashes($ar_league_players['admin_nick'])."</a><br>";
						echo "		</td>";
						echo "		<td colspan=\"2\">"; if ($ar_league_players['admin_guid_guid']){echo "<span class=\"green\">".$ar_league_players['admin_guid_guid']."</span>";} else {echo "<span class=\"red\">"._LEAGUE_PLAYER_GUID_NO."</span>";} echo "</td>";
						echo "	</tr>";
						$i++;
						$cislo++;
					}
					echo "	<tr>";
					echo "		<td colspan=\"3\">";
					if ((LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == True)){
				   		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">";
				   		echo "			<input type=\"hidden\" name=\"ltid\" value=\"".(integer)$ltid."\">";
				   		echo "			<input type=\"hidden\" name=\"ltsid\" value=\"".(integer)$ar_sub_teams['league_team_sub_id']."\">";
				   		echo "			<input type=\"hidden\" name=\"lid\" value=\"".(integer)$ar_leagues['league_league_id']."\">";
				   		echo "			<input type=\"hidden\" name=\"mode\" value=\"team_league_allow_players\"><br>"; 
				   		echo "			<input type=\"submit\" value=\""._LEAGUE_BUTTON_UPDATE_TEAM."\" class=\"eden_button\">";
				   		echo "			</form>";
					}
					echo "		</td>";
					echo "	</tr>";
				}
			}
			echo "	</table><br>";
			
			/*********************************************************************************/
			/* Cekajici hraci */
			/*********************************************************************************/
			$res_pending = mysql_query("
			SELECT lr.league_request_id, lr.league_request_admin_id, lr.league_request_action, a.admin_nick, c.country_name, c.country_shortname 
			FROM $db_league_requests AS lr 
			JOIN ($db_admin AS a, $db_admin_contact AS ac, $db_country AS c) 
			ON (a.admin_id=lr.league_request_admin_id AND ac.aid=a.admin_id AND c.country_id=ac.admin_contact_country) 
			WHERE league_request_team_sub_id=".(integer)$ar_sub_teams['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$num_pending = mysql_num_rows($res_pending);
			if ($num_pending > 0){
				echo "<p>&nbsp;</p>";
				echo "	<h2>"._LEAGUE_TEAM_HOME_PLAYERS_PENDING."</h2>";
				echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
				echo "		<tr>";
				echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_NICK."</td>";
				echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_COUNTRY."</td>";
				echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_POSITION."</td>";
				echo "			<td class=\"eden_league_title\">"._LEAGUE_TEAM_HOME_PLAYERS_OPTIONS."</td>";
				echo "		</tr>";
				$cislo = 0;
				while ($ar_pending = mysql_fetch_array($res_pending)){
					if ($ar_pending['league_request_action'] == 1){
						$player_status = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PENDING_PLAYER;
					} elseif ($ar_pending['league_request_action'] == 2){
						$player_status = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PENDING_TEAM;
					} else {
						$player_status = _LEAGUE_TEAM_HOME_PLAYERS_POSITION_PENDING_NONE;
					}
					if ($cislo % 2 == 0){$class = "suda";} else { $class = "licha";}
					echo "	<tr class=\"".$class."\">";
					echo "		<td>".stripslashes($ar_pending['admin_nick'])."</td>";
					echo "		<td><img src=\"".$url_flags.$ar_pending['country_shortname'].".gif\" alt=\"".stripslashes($ar_pending['country_name'])."\" title=\"".stripslashes($ar_pending['country_name'])."\"></td>";
					echo "		<td>".$player_status."</td>";
					echo "		<td>"; if ((LeagueCheckPrivileges("O",$_SESSION['loginid'],$ltid,0) == $ltid || LeagueCheckPrivileges("C",$_SESSION['loginid'],$ltid,$ar_sub_teams['league_team_sub_id']) == True) && $ar_pending['league_request_action'] == 2){ echo "<a href=\"index.php?action=league_team&mode=team_team_confirm&amp;lrid=".$ar_pending['league_request_id']."&amp;ltid=".$ltid."&amp;ltsid=".$ar_sub_teams['league_team_sub_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_HOME_PLAYER_JOIN_ACCEPT."</a>";} echo "</td>";
					echo "	</tr>";
					$cislo++;
				}
				echo "</table><br>";
			}
		}
		echo "</div>";
	}
}
/***********************************************************************************************************
*
*		LeagueTeamHome
*
*		Zobrazi informace o teamu
*
*		$lpid	- League Team ID
*
***********************************************************************************************************/
function LeaguePlayerHome($lpid = 0){
	
	global $db_admin,$db_league_players,$db_league_requests,$db_league_players_leagues,$db_league_players_bans;
	global $db_league_leagues,$db_admin_contact,$db_admin_guids,$db_country,$db_clan_games,$db_admin_contact;
	global $url_flags,$url_admins;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($lpid != 0) {
		$res_player = mysql_query("
		SELECT a.admin_id, a.admin_nick, lp.league_player_id, a.admin_userimage, c.country_name 
		FROM $db_league_players AS lp 
		JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
		JOIN $db_admin_contact AS ac ON ac.aid=lp.league_player_admin_id 
		JOIN $db_country AS c ON c.country_id=ac.admin_contact_country 
		WHERE lp.league_player_id=".(integer)$lpid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$ar_player = mysql_fetch_array($res_player);
		echo "	<div class=\"eden_league\">";
		echo "		<h2>"._LEAGUE_TEAM_HOME_GENERAL."</h2>";
		echo "		<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
		echo "			<tr>";
		echo "				<td rowspan=\"3\" width=\"50\"><img src=\"".$url_admins.$ar_player['admin_userimage']."\" alt=\"".stripslashes($ar_player['admin_nick'])."\" title=\"".stripslashes($ar_player['admin_nick'])."\"></td>";
		echo "				<td class=\"suda\">ID</td>";
		echo "				<td class=\"suda\"><h3>".$lpid."</h3></td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"licha\">"._LEAGUE_TEAM_HOME_PLAYERS_NICK."</td>";
		echo "				<td class=\"licha\"><h3>".stripslashes($ar_player['admin_nick'])."</h3></td>";
		echo "			</tr>";
		echo "			<tr>";
		echo "				<td class=\"suda\">"._LEAGUE_TEAM_HOME_COUNTRY."</td>";
		echo "				<td class=\"suda\">".stripslashes($ar_player['country_name'])."</td>";
		echo "			</tr>";
		echo "				<td colspan=\"2\"><a href=\"index.php?action=league_team&mode=team_log_player&pid=".$_SESSION['loginid']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_PLAYER_LOG."</a></td>";
		echo "			</tr>";
		echo "		</table><br>";
		
		/*********************************************************************************/
		/* Ligy ve kterych je hrac prihlasen */
		/*********************************************************************************/
		echo "<p>&nbsp;</p>";
		echo "	<h2>"._LEAGUE_TEAM_HOME_TEAM_LEAGUES."</h2>";
		echo "	<a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=player_league_reg&amp;lpid=".$lpid."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\">"._LEAGUE_PLAYER_LEAGUE_REG_TO."</a>";
		echo "	<table cellpadding=\"3\" cellspacing=\"2\" border=\"0\" class=\"eden_league_table\">";
	   	echo "		<tr>";
	   	echo "			<td class=\"eden_league_title\">"._LEAGUE_PLAYER_HOME_PLAYER_LEAGUES_NAME."</td>";
	   	echo "			<td class=\"eden_league_title\">"._LEAGUE_PLAYER_HOME_PLAYER_LEAGUES_STATUS."</td>";
		echo "			<td class=\"eden_league_title\">"._LEAGUE_PLAYER_HOME_PLAYER_LEAGUES_OPTIONS."</td>";
	   	echo "		</tr>";
		$res_leagues = mysql_query("
		SELECT l.league_league_name, l.league_league_id, ag.admin_guid_guid, lpb.league_player_ban_status 
		FROM $db_league_players_leagues AS lpl 
		JOIN $db_league_leagues AS l ON l.league_league_id=lpl.league_player_league_league_id 
		LEFT JOIN $db_admin_guids AS ag ON ag.aid=".(integer)$_SESSION['loginid']." AND ag.admin_guid_game_id=lpl.league_player_league_game_id 
		LEFT JOIN $db_league_players_bans AS lpb ON lpb.league_player_ban_player_id=".(integer)$lpid." AND lpb.league_player_ban_league_id=l.league_league_id AND lpb.league_player_ban_status=1 
		WHERE lpl.league_player_league_player_id=".(integer)$lpid) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
		$i=0;
		while ($ar_leagues = mysql_fetch_array($res_leagues)){
			if ($i % 2 == 0){$class = "suda";} else {$class = "licha";}
	   		echo "	<tr>";
			echo "		<td class=\"".$class."\"><a href=\"".$eden_cfg['url']."index.php?action=league_team&amp;mode=league_list&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&filter=".$_GET['filter']."\" target=\"_self\"><strong>".stripslashes($ar_leagues['league_league_name'])."</strong></a></td>";
			echo "		<td class=\"".$class."\">";
				/* Pokud ma hrac BAN v dane lize zobrazi se vystraha */
				$comma = "";
				if ($ar_leagues['league_player_ban_status'] == 1 || $ar_leagues['league_player_ban_status'] == 2){
					echo "<span class=\"red\">"._LEAGUE_PLAYER_STATUS_BAN_YOU."</span>";
					$comma = ", ";
				}
				if ($ar_leagues['admin_guid_guid']){
					echo $comma."<span class=\"green\">"._LEAGUE_PLAYER_GUID_STATUS_YES."</span>";
				} else {
					echo $comma."<span class=\"red\">"._LEAGUE_PLAYER_GUID_NO."</span>";
				} 
			echo "		</td>";
			echo "		<td class=\"".$class."\"><a href=\"index.php?action=league_team&mode=player_league_leave&amp;lpid=".$lpid."&amp;lid=".$ar_leagues['league_league_id']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" target=\"_self\">"._LEAGUE_TEAM_HOME_TEAM_LEAGUES_LEAVE."</a></td>";
			echo "	</tr>";
			$i++;
		}
		echo "	</table><br>";
		echo "</div>";
	}
}