<?php
// Dnesni datum
$today = date("Y-m-d")." 00:00:01";

/* LEAGUE */
if ($_GET['action'] == "league_add" || $_GET['action'] == "league_edit"){
	if ($_GET['action'] == "league_add"){
		mysql_query("INSERT INTO $db_league_leagues VALUES(
		'',
		'".(integer)$_POST['league_game']."',
		'".PrepareForDB($_POST['league_name'])."',
		'".PrepareForDB($_POST['league_description'])."', 
		'".(integer)$_POST['league_league_team_sub_min_players']."',
		'".(integer)$_POST['league_league_team_sub_max_players']."',
		NOW(),
		".(integer)$_POST['league_active'].",
		".(integer)$_POST['league_lock'].",
		".(integer)$_POST['league_mode'].")") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "add_ok";
	}
	if ($_GET['action'] == "league_edit"){
		mysql_query("UPDATE $db_league_leagues SET 
		league_league_game_id=".(integer)$_POST['league_game'].", 
		league_league_name='".PrepareForDB($_POST['league_name'])."', 
		league_league_description='".PrepareForDB($_POST['league_description'])."', 
		league_league_team_sub_min_players=".(integer)$_POST['league_team_sub_min_players'].", 
		league_league_team_sub_max_players=".(integer)$_POST['league_team_sub_max_players'].", 
		league_league_active=".(integer)$_POST['league_active'].", 
		league_league_lock=".(integer)$_POST['league_lock'].",
		league_league_mode=".(integer)$_POST['league_mode']." 
		WHERE league_league_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "edit_ok";
		$_GET['action'] = "league_add";
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_add&id=".(integer)$_GET['id']."&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* SEASON */
if ($_GET['action'] == "league_season_add" || $_GET['action'] == "league_season_edit"){
	$league_season_start =  PrepareDateForSpiffyCalendar($_POST['league_season_start'],"00:00:01");
	$league_season_end =  PrepareDateForSpiffyCalendar($_POST['league_season_end'],"00:00:01");
	$league_season_playoff_start = PrepareDateForSpiffyCalendar($_POST['league_season_playoff_start'],"00:00:01");
	$league_season_playoff_end = PrepareDateForSpiffyCalendar($_POST['league_season_playoff_end'],"00:00:01");
	
	/* Datum za mesic */
	$threemonths = date("Y-m-d",strtotime("+1 month",time()))." 19:00:00";
	
	/* Pokud neni playoff - pocet kol play off je 0 */
	if ($_POST['league_season_playoff'] == 1){$league_playoff_rounds = $_POST['league_season_playoff_rounds'];} else {$league_playoff_rounds = 0;}
	
	if ($_GET['action'] == "league_season_add"){
		/* Pridame sezonu */
		mysql_query("INSERT INTO $db_league_seasons VALUES(
		'',
		'".(integer)$_POST['league_id']."',
		'".PrepareForDB($_POST['league_season_name'])."',
		'".$league_season_start."',
		'".$league_season_end."',
		'".(integer)$_POST['league_season_playoff']."',
		'".$league_season_playoff_start."',
		'".$league_season_playoff_end."',
		'".(integer)$league_playoff_rounds."',
		'".(integer)$_POST['league_season_rounds']."',
		'".(integer)$_POST['league_season_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_id = mysql_fetch_array($res_id);
		/* Vygenerujeme pocet kol podle zadani */
		$i=1;
		while ($i <= (integer)$_POST['league_season_rounds']){
			mysql_query("INSERT INTO $db_league_seasons_rounds VALUES(
			'',
			'".(integer)$ar_id[0]."',
			'".(integer)$i."',
			'".$threemonths."',
			'0',
			'0',
			'0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i++;
		}
		/* Vygenerujeme pocet play off kol podle zadani */
		$i=1;
		while ($i <= (integer)$_POST['league_season_playoff_rounds'] && $_POST['league_season_playoff'] == 1){
			mysql_query("INSERT INTO $db_league_seasons_rounds VALUES(
			'',
			'".(integer)$ar_id[0]."',
			'".(integer)$i."',
			'".$threemonths."',
			'1',
			'0',
			'0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i++;
		}
		$msg = "add_ok";
	}
	
	if ($_GET['action'] == "league_season_edit"){
		$res_season = mysql_query("SELECT league_season_end, league_season_playoff_end FROM $db_league_seasons WHERE league_season_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_season = mysql_fetch_array($res_season);
		// Pokud je sezona jiz odehrana, nejde menit!
		if ($ar_season['league_season_end'] < $today){
			header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_add&project=".$_SESSION['project']."&msg=league_season_ended");
			exit;
		}
		$res_rounds = mysql_query("SELECT league_season_round_date FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=0 ORDER BY league_season_round_num DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_rounds = mysql_num_rows($res_rounds);
		$res_playoff_rounds = mysql_query("SELECT league_season_round_date FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=1 ORDER BY league_season_round_num DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_playoff_rounds = mysql_num_rows($res_playoff_rounds);
		
		// Pokud je pocet jiz ulozenych kol vetsi nez kol zadanych pri editaci
		if ($num_rounds > $_POST['league_season_rounds']){
			/* Smazeme prebyvajici kola */
			while ($ar_rounds = mysql_fetch_array($res_rounds)){
				/* Smazeme ovsem jen ty, ktere jeste neprobehly, pripadne v nich jiz nejsou zaznamy */
				mysql_query("DELETE FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=0 AND league_season_round_num > ".(integer)$_POST['league_season_rounds']." AND league_season_round_date>'".$today."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		} elseif ($num_rounds < $_POST['league_season_rounds']){
			/* Pridame kola, ktera chybi */
			$i=$num_rounds;
			while ($i < (integer)$_POST['league_season_rounds']){
				$y = $i+1;
				mysql_query("INSERT INTO $db_league_seasons_rounds VALUES(
				'',
				'".(integer)$_GET['sid']."',
				'".(integer)$y."',
				'".$threemonths."',
				'0',
				'0',
				'0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
		}
		// Pokud je play off aktivni
		if ($_POST['league_season_playoff'] == 1){
			// Pokud je pocet jiz ulozenych playoff kol vetsi nez kol zadanych pri editaci
			if ($num_playoff_rounds > $_POST['league_season_playoff_rounds']){
				/* Smazeme prebyvajici playoff kola */
				while ($ar_playoff_rounds = mysql_fetch_array($res_playoff_rounds)){
					/* Smazeme ovsem jen ty, ktere jeste neprobehly, pripadne v nich jiz nejsou zaznamy */
					mysql_query("DELETE FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=1 AND league_season_round_num > ".(integer)$_POST['league_season_playoff_rounds']." AND league_season_round_date>'".$today."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				}
			} elseif ($num_playoff_rounds < $_POST['league_season_playoff_rounds']){
				/* Pridame kola play off, ktera chybi */
				$i=$num_playoff_rounds;
				while ($i < (integer)$_POST['league_season_playoff_rounds']){
					$y = $i+1;
					mysql_query("INSERT INTO $db_league_seasons_rounds VALUES(
					'',
					'".(integer)$_GET['sid']."',
					'".(integer)$y."',
					'".$threemonths."',
					'1',
					'".(integer)$_POST['league_season_round_classified']."',
					'0')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i++;
				}
			}
		}
		$res_rounds = mysql_query("SELECT MAX(league_season_round_num) FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_rounds = mysql_fetch_array($res_rounds);
		$res_playoff_rounds = mysql_query("SELECT MAX(league_season_round_num) FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_GET['sid']." AND league_season_round_playoff=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_playoff_rounds = mysql_fetch_array($res_playoff_rounds);
		
		mysql_query("UPDATE $db_league_seasons SET 
		league_season_name='".PrepareForDB($_POST['league_season_name'])."',
		league_season_start='".$league_season_start."',
		league_season_end='".$league_season_end."',
		league_season_playoff=".(integer)$_POST['league_season_playoff'].",
		league_season_playoff_start='".$league_season_playoff_start."',
		league_season_playoff_end='".$league_season_playoff_end."',
		league_season_playoff_rounds=".(integer)$num_playoff_rounds[0].",
		league_season_rounds=".(integer)$num_rounds[0].",
		league_season_active=".(integer)$_POST['league_season_active']." WHERE league_season_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "edit_ok";
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_add&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* SEASON ROUNDS */
if ($_GET['action'] == "league_season_rounds_edit"){
	// Tady je treba spocitat kolik a ktere data byly zmeneny
	$res_rounds = mysql_query("SELECT league_season_round_date FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$_POST['sid']." ORDER BY league_season_round_playoff ASC, league_season_round_num ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1; // Toto je iterace pro pocet zaznamu z formulare
	$y=0; // Toto je iterace pro pocet zaznamu z pole
	while ($ar_rounds = mysql_fetch_array($res_rounds)){
		// Nejdrive prevedeme datumy na spravny format
		$league_round = $_POST['league_round_'.$i];
		$league_season_date = PrepareDateForSpiffyCalendar($league_round,"19:00:00");
		$league_season_round_date = FormatDatetime($ar_rounds['league_season_round_date'],"d-m-Y")." 19:00:00";
		// Z pole lsrid vytahneme ID kola
		$lsrid = $_POST['lsrid'][$y];
		// Pomoci strtotime prevedeme datumy na cisla a pak je mezi sebou porovname aby nedoslo ke zmene datumu u kol, ktera uz byla odehrana
		if (strtotime($league_season_round_date) > strtotime($today)){
			// Ulozime data do databaze
			mysql_query("UPDATE $db_league_seasons_rounds SET league_season_round_date='".$league_season_date."', league_season_round_classified=".(integer)$_POST['league_season_round_classified_'.$i]." WHERE league_season_round_id=".(integer)$lsrid." AND league_season_round_date>'".$today."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		$i++;
		$y++;
	}
	mysql_query("UPDATE $db_league_leagues SET league_league_lock=".(integer)$_POST['league_lock']." WHERE league_league_id=".(integer)$_POST['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=rounds&lid=".$_POST['lid']."&sid=".$_POST['sid']."&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* GUID */
if ($_GET['action'] == "guid_add" || $_GET['action'] == "guid_edit"){
	if ($_GET['action'] == "guid_add"){
		mysql_query("INSERT INTO $db_league_guids VALUES(
		'',
		'".(integer)$_POST['league_guid_game']."',
		'".PrepareForDB($_POST['league_guid_name'])."',
		'".PrepareForDB($_POST['league_guid_sample'])."',
		'".PrepareForDB($_POST['league_guid_help'])."',
		'".(integer)$_POST['league_guid_active']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "add_ok";
	}
	if ($_GET['action'] == "guid_edit"){
		mysql_query("UPDATE $db_league_guids SET 
		league_guid_name='".PrepareForDB($_POST['league_guid_name'])."',
		league_guid_sample='".PrepareForDB($_POST['league_guid_sample'])."',
		league_guid_help='".PrepareForDB($_POST['league_guid_help'])."',
		league_guid_active=".(integer)$_POST['league_guid_active']." WHERE league_guid_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "edit_ok";
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=guid_add&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* TEAM */
if ($_GET['action'] == "league_team_add" || $_GET['action'] == "league_team_edit" || $_GET['action'] == "league_team_del"){
	/*
	if ($_GET['action'] == "league_team_add"){
		mysql_query("INSERT INTO $db_league_teams VALUES(
		'',
		'$nazev',
		'$tag',
		'$web',
		'$irc',
		'$server1',
		'$server2',
		'$server3',
		'$server4',
		'$oceneni',
		'$kapitan',
		'$zastupce',
		'',
		'$liga',
		'$hra',
		'$heslo',
		'$comment',
		'$country')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "edit_ok";
	}
	*/
	if ($_GET['action'] == "league_team_edit"){
		$res = mysql_query("UPDATE $db_league_teams SET 
		league_team_country_id=".(integer)$_POST['league_team_country'].", 
		league_team_name='".PrepareForDB($_POST['league_team_name'],1,"",1)."', 
		league_team_tag='".PrepareForDB($_POST['league_team_tag'],1,"",1)."', 
		league_team_web='".PrepareForDB($_POST['league_team_web'],1,"",1)."', 
		league_team_irc='".PrepareForDB($_POST['league_team_irc'],1,"",1)."', 
		league_team_motto='".PrepareForDB($_POST['league_team_motto'],1,"",1)."', 
		league_team_server1='".PrepareForDB($_POST['league_team_server1'],1,"",1)."', 
		league_team_server2='".PrepareForDB($_POST['league_team_server2'],1,"",1)."', 
		league_team_server3='".PrepareForDB($_POST['league_team_server3'],1,"",1)."', 
		league_team_server4='".PrepareForDB($_POST['league_team_server4'],1,"",1)."', 
		league_team_pass='".PrepareForDB($_POST['league_team_pass'],1,"",1)."', 
		league_team_comment='".PrepareForDB($_POST['league_team_comment'],1,"",1)."' 
		WHERE league_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if ($res){
			$msg = "league_team_edit_ok";
			$action = "league_team_show";
		} else {
			$msg = "league_team_edit_er";
			$action = "league_team_edit&tid=".$_POST['ltid'];
		}
	}
	
	if ($_GET['action'] == "league_team_del"){
		// Check how many player are in sub teams
		$players = LeagueCheckHowManyPlayersInSubTeam((integer)$_POST['ltid']);
		if ($players > 0){
			$msg = "league_hibernate_players_in_team";
			$action = "league_team_del&tid=".$_POST['ltid'];
		} else {
			// Team will be not deleted but hibernated
			$res1 = mysql_query("UPDATE $db_admin SET admin_team_own_id=0 WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			$res2 = mysql_query("UPDATE $db_league_teams SET league_team_owner_id=0, league_team_hibernate=1, league_team_date_last_modified=NOW() WHERE league_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			LeagueAddToLOG (0,(integer)$_POST['ltid'],0,(integer)$_SESSION['loginid'],0,15,"","",""); 					// Player left ownership
			LeagueAddToLOG (0,(integer)$_POST['ltid'],0,0,0,69,"","",""); 	// Team went to hibernation
			// Delete all requests for players/ from players
			$res_sub = mysql_query("SELECT league_team_sub_id FROM $db_league_teams_sub WHERE league_team_sub_team_id=".(integer)$_POST['ltid']) or die ("<strong>File:</strong> ".__FILE__."<br><strong>Line:</strong>".__LINE__."<br>".mysql_error());
			while ($ar_sub = mysql_fetch_array($res_sub)){
				mysql_query("DELETE FROM $db_league_requests WHERE league_request_team_sub_id=".(integer)$ar_sub['league_team_sub_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			if ($res1 && $res2){
				$msg = "league_team_hibernate_ok";
				$action = "league_team_show";
			} else {
				$msg = "league_team_hibernate_er";
				$action = "league_team_del&tid=".$_POST['ltid'];
			}
		}
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=".$action."&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* GENERATE PLAYERS ALLOWED TO PLAY LEAGUE */
if ($_GET['action'] == "generate_players" && (integer)$_GET['lid'] != 0 && (integer)$_GET['sid'] != 0 && (integer)$_GET['rid'] != 0){
	// Vygenerujeme povolene hrace - funkce vrati hodnotu podle toho zda se ulozeni zdarilo ci nikoliv
	$msg = LeagueGenerateListAllowedPlayers((integer)$_GET['lid'],(integer)$_GET['sid'],(integer)$_GET['rid']);	
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=rounds&sid=".$_GET['sid']."&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* Zadani vysledku kola */
if ($_GET['action'] == "results_add"){
	$i=1; // Toto je iterace pro pocet zaznamu z formulare
	$y=0; // Toto je iterace pro pocet zaznamu z pole
	while ($y < $_POST['round_player_num']){
	   	$player_data = $_POST['round_player_data'];
		
		// Ziskame PID z promenne
		preg_match ("/\(PID ([0-9]{1,})\)$/i", $player_data[$i.'_player_guid'], $regs);
    	$player_id = $regs[1];
		
		// Zkonvertujeme cislo zadave ve formatu 10,22 na float 10.22
		$points = str_replace(",", ".", $player_data[$i.'_points']);
		
		// Ulozime jen v pripade ze <input> byl vyplnen
		if (!empty($player_id) && !empty($points) && !empty($player_data[$i.'_place'])){
			$res_player = mysql_query("SELECT league_season_round_result_player_id FROM $db_league_seasons_rounds_results_players WHERE league_season_round_result_player_round_id=".(integer)$_POST['rid']." AND league_season_round_result_player_player_id=".(integer)$player_id."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($_POST['results_mode'] == "edit" || $ar_player = mysql_fetch_array($res_player)){
				$query = mysql_query("UPDATE $db_league_seasons_rounds_results_players SET 
				league_season_round_result_player_points=".(float)$points.",  
				league_season_round_result_player_player_id=".(integer)$player_id.",  
				league_season_round_result_player_date=NOW() 
				WHERE league_season_round_result_player_round_id=".(integer)$_POST['rid']." AND league_season_round_result_player_place=".(integer)$player_data[$i.'_place']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$save_to_team = 1;
			} else {
				$query = mysql_query("INSERT INTO $db_league_seasons_rounds_results_players VALUES(
				'',
				'".(integer)$player_id."',
				'".(integer)$_POST['sid']."',
				'".(integer)$_POST['rid']."',
				'".(float)$points."',
				'".(integer)$player_data[$i.'_place']."',
				NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$save_to_team = 1;
			}
			
			// Ulozime vysledky hrace pro danou sezonu
			$res_player_season_rounds = mysql_query("SELECT league_season_round_result_player_points FROM $db_league_seasons_rounds_results_players WHERE league_season_round_result_player_season_id=".(integer)$_POST['sid']." AND league_season_round_result_player_player_id=".(integer)$player_id."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$player_season_points = 0;
			while ($ar_player_season_rounds = mysql_fetch_array($res_player_season_rounds)){
				$player_season_points += $ar_player_season_rounds['league_season_round_result_player_points'];
			}
			$res_player_season = mysql_query("SELECT league_season_result_player_id FROM $db_league_seasons_results_players WHERE league_season_result_player_season_id=".(integer)$_POST['sid']." AND league_season_result_player_player_id=".(integer)$player_id."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($ar_player_season = mysql_fetch_array($res_player_season)){
				$query_season_player = mysql_query("UPDATE $db_league_seasons_results_players SET 
				league_season_result_player_points=".(float)$player_season_points.",  
				league_season_result_player_date=NOW() 
				WHERE league_season_result_player_id=".$ar_player_season['league_season_result_player_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				$query_season_player = mysql_query("INSERT INTO $db_league_seasons_results_players VALUES(
				'',
				'".(integer)$player_id."',
				'".(integer)$_POST['sid']."',
				'".(float)$player_season_points."',
				NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
		$i++;
		$y++;
	}
	
	// Ulozime vysledky pro dane kolo do tymove tabulky
	if ($save_to_team == 1){
		$res_results = mysql_query("SELECT lsrrp.league_season_round_result_player_points, lp.league_player_team_id, lp.league_player_team_sub_id 
		FROM $db_league_seasons_rounds_results_players AS lsrrp 
		JOIN $db_league_players AS lp ON lp.league_player_id=lsrrp.league_season_round_result_player_player_id 
		JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_id=lp.league_player_team_sub_id 
		WHERE lsrrp.league_season_round_result_player_round_id=".(integer)$_POST['rid']." 
		ORDER BY lts.league_team_sub_id ASC, lsrrp.league_season_round_result_player_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$i=1;
		while ($ar_results = mysql_fetch_array($res_results)){
			// Nejdrive nastavime do promene ID noveho tymu
			if ($i == 1){
				$team_id = $ar_results['league_player_team_sub_id'];
				$points = 0;
				$data = 0;
			}
			// Pokud vse souhlasi ulozi se do promenne soucet bodu az 3 nejlepe umistenych hracu v tomto kole
			if ($team_id == $ar_results['league_player_team_sub_id'] && $i <= 3){
				//$data = $i."* - ".$ar_results['league_player_team_sub_id']." - ".$ar_results['league_season_round_result_player_points'];
				$team_id = $ar_results['league_player_team_id'];
				$team_sub_id = $ar_results['league_player_team_sub_id'];
				$points += $ar_results['league_season_round_result_player_points'];
				$i++;
			}
			// Pokud nesouhlasi ID teamu z nasi promenne s ID teamu z databaze jedna se o tym, ktery jeste nema pro toto kolo zadny zaznam.
			// Musime tedy ulozit do promenne $i ze je to prvni zaznam, do promenne $team_id ID noveho tymu a ulozit do promenne pocet bodu
			// Pak iterujeme $i o jedno vyse
			if ($team_id != $ar_results['league_player_team_sub_id'] && $i > 1){
				$i=1;
				$team_id = $ar_results['league_player_team_sub_id'];
				//$data = $i."& - ".$ar_results['league_player_team_sub_id']." - ".$ar_results['league_season_round_result_player_points'];
				$team_id = $ar_results['league_player_team_id'];
				$team_sub_id = $ar_results['league_player_team_sub_id'];
				$points = $ar_results['league_season_round_result_player_points'];
				$i++;
			}
			// Ulozime vysledky tymu pro dane kolo
			$res_team = mysql_query("SELECT league_season_round_result_team_id FROM $db_league_seasons_rounds_results_teams WHERE league_season_round_result_team_round_id=".(integer)$_POST['rid']." AND league_season_round_result_team_team_sub_id=".(integer)$team_sub_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($ar_team = mysql_fetch_array($res_team)){
				$query = mysql_query("UPDATE $db_league_seasons_rounds_results_teams SET 
				league_season_round_result_team_points=".(float)$points.",  
				league_season_round_result_team_date=NOW() 
				WHERE league_season_round_result_team_round_id=".(integer)$_POST['rid']." AND league_season_round_result_team_team_sub_id=".(integer)$team_sub_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				$query = mysql_query("INSERT INTO $db_league_seasons_rounds_results_teams VALUES(
				'',
				'".(integer)$team_id."',
				'".(integer)$team_sub_id."',
				'".(integer)$_POST['sid']."',
				'".(integer)$_POST['rid']."',
				'".(float)$points."',
				NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			
			// Ulozime vysledky tymu pro danou sezonu
			$res_team_season_rounds = mysql_query("SELECT league_season_round_result_team_points FROM $db_league_seasons_rounds_results_teams WHERE league_season_round_result_team_season_id=".(integer)$_POST['sid']." AND league_season_round_result_team_team_sub_id=".(integer)$team_sub_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$team_season_points = 0;
			while ($ar_team_season_rounds = mysql_fetch_array($res_team_season_rounds)){
				$team_season_points += $ar_team_season_rounds['league_season_round_result_team_points'];
			}
			$res_team_season = mysql_query("SELECT league_season_result_team_id FROM $db_league_seasons_results_teams WHERE league_season_result_team_season_id=".(integer)$_POST['sid']." AND league_season_result_team_team_sub_id=".(integer)$team_sub_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($ar_team_season = mysql_fetch_array($res_team_season)){
				$query_season = mysql_query("UPDATE $db_league_seasons_results_teams SET 
				league_season_result_team_points=".(float)$team_season_points.",  
				league_season_result_team_date=NOW() 
				WHERE league_season_result_team_id=".$ar_team_season['league_season_result_team_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				$query_season = mysql_query("INSERT INTO $db_league_seasons_results_teams VALUES(
				'',
				'".(integer)$team_id."',
				'".(integer)$team_sub_id."',
				'".(integer)$_POST['sid']."',
				'".(float)$team_season_points."',
				NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			//echo "DATA: ".$data." POINTS: ".$points."<br>";
		}
	}
	
	// Ulozime zda vsechno probehlo jak melo
	if ($query){
		mysql_query("UPDATE $db_league_seasons_rounds SET league_season_round_lock=1 WHERE league_season_round_id=".(integer)$_POST['rid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "league_round_results_add_ok";
	} else {
		$msg = "league_round_results_add_err";
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=rounds&sid=".$_POST['sid']."&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

/* PLAYERS */
if ($_GET['action'] == "league_player_del"){
	/* Nic se mazat nebude - to je rucni prace, jinak by mohlo dojit k nekonzistenci dat!!! */
}

/* BAN ADD */
if ($_GET['action'] == "league_player_ban_add" || $_GET['action'] == "league_player_ban_edit"){
	
	$res_player = mysql_query("SELECT league_player_admin_id FROM $db_league_players WHERE league_player_id=".(integer)$_POST['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_player = mysql_fetch_array($res_player);
	include_once "./cfg/eden_league.php"; /* Include file with function LeagueAddToLOG */
	
	/* Nastaveni datumu ze SpiffyCaledar */
	$player_ban_from = PrepareDateForSpiffyCalendar($_POST['player_ban_from'],"actual");
	$player_ban_to = PrepareDateForSpiffyCalendar($_POST['player_ban_to'],"actual");
	
	if ($_GET['action'] == "league_player_ban_add"){
		/* BAN do vsech lig */
		if ($_POST['league'] == "all"){
			$res_league = mysql_query("SELECT league_league_id, league_league_game_id FROM $db_league_leagues") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_league = mysql_fetch_array($res_league)){
				$res_ban = mysql_query("SELECT league_player_ban_id, league_player_ban_date_to FROM $db_league_players_bans WHERE league_player_ban_player_id=".(integer)$_POST['pid']." AND league_player_ban_admin_id=".(integer)$ar_player['league_player_admin_id']." AND league_player_ban_league_id=".(integer)$ar_league['league_league_id']." AND league_player_ban_status<>0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_ban = mysql_fetch_array($res_ban);
				/* Pokud uz ma hrac v nektere lize ban - podivame se na datum kdy mu ban skoncit a v pripade, ze ma trvat kratsi dobu nez ban ktery hodlame udelit, toto se zmeni */
				if ($ar_ban['league_player_ban_date_to'] != "" && $ar_ban['league_player_ban_date_to'] <  $player_ban_to){
					$query = mysql_query("UPDATE $db_league_players_bans SET 
						league_player_ban_date_to='".$player_ban_to."' 
						WHERE league_player_ban_id=".(integer)$ar_ban['league_player_ban_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				} elseif ($ar_ban['league_player_ban_date_to'] != "" && $ar_ban['league_player_ban_date_to'] >  $player_ban_to){
					/* Nestane se nic, jelikoz uz ma hrac pro danou ligu BAN do doby ktera je delsi nez prave udelena */
				} else {
					$query = mysql_query("INSERT INTO $db_league_players_bans VALUES(
						'',
						'".(integer)$_POST['pid']."',
						'".(integer)$ar_player['league_player_admin_id']."',
						'".(integer)$ar_league['league_league_id']."',
						'',
						'".(integer)$_SESSION['loginid']."',
						'',
			   			'".PrepareForDB($_POST['player_ban_reason'])."',
						NOW(),
						'',
						'".$player_ban_from."',
						'".$player_ban_to."',
						'1')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					LeagueAddToLOG ((integer)$ar_league['league_league_id'],0,0,(integer)$ar_player['league_player_admin_id'],(integer)$ar_league['league_league_game_id'],23,"","",$_POST['player_ban_reason']);			// BAN granted
				}
			}
			$msg = "league_player_ban_add_all_ok";
			
		/* BAN jen do konkretni ligy */
		} else {
			$res_league = mysql_query("SELECT league_league_game_id FROM $db_league_leagues WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_league = mysql_fetch_array($res_league);
			$query = mysql_query("INSERT INTO $db_league_players_bans VALUES(
			'',
			'".(integer)$_POST['pid']."',
			'".(integer)$ar_player['league_player_admin_id']."',
			'".(integer)$_GET['lid']."',
			'',
			'".(integer)$_SESSION['loginid']."',
			'',
			'".PrepareForDB($_POST['player_ban_reason'])."',
			NOW(),
			'',
			'".$player_ban_from."',
			'".$player_ban_to."',
			'1')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			LeagueAddToLOG ((integer)$_GET['lid'],0,0,(integer)$ar_player['league_player_admin_id'],(integer)$ar_league['league_league_game_id'],23,"","",$_POST['player_ban_reason']);			// BAN granted
			$msg = "league_player_ban_ok";
		}
		
		if (!$query){$msg = "league_player_ban_er";}
		
		header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&msg=".$msg);
		exit;
	}
	/* BAN EDIT */
	if ($_GET['action'] == "league_player_ban_edit"){
		
		/* Paklize je vybran nektery z un-BANu nemeni se nic jineho nez status BANu */
		
		/* Unban jen do konkretni ligy */
		if ($_POST['league'] == "unban"){
			$res_league = mysql_query("SELECT league_league_game_id FROM $db_league_leagues WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_league = mysql_fetch_array($res_league);
			
			$query = mysql_query("UPDATE $db_league_players_bans SET 
			league_player_ban_status=0, 
			league_player_ban_removed_by_admin_id=".$_SESSION['loginid'].", 
			league_player_ban_date_removed=NOW() 
			WHERE league_player_ban_id=".(integer)$_POST['bid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			LeagueAddToLOG ((integer)$_GET['lid'],0,0,(integer)$ar_player['league_player_admin_id'],(integer)$ar_league['league_league_game_id'],24,"","","");			// BAN removed
			$msg = "league_player_ban_unban_ok";
			
		/* Unban do vsech lig */
		} elseif ($_POST['league'] == "unban_all"){
			$res_league = mysql_query("SELECT league_league_id, league_league_game_id FROM $db_league_leagues") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_league = mysql_fetch_array($res_league)){
				$query = mysql_query("UPDATE $db_league_players_bans SET 
				league_player_ban_status=0, 
				league_player_ban_removed_by_admin_id=".$_SESSION['loginid'].", 
				league_player_ban_date_removed=NOW() 
				WHERE league_player_ban_player_id=".(integer)$_POST['pid']." AND league_player_ban_admin_id=".(integer)$ar_player['league_player_admin_id']." AND league_player_ban_league_id=".(integer)$ar_league['league_league_id']." AND league_player_ban_status<>0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				LeagueAddToLOG ((integer)$ar_league['league_league_id'],0,0,(integer)$ar_player['league_player_admin_id'],(integer)$ar_league['league_league_game_id'],24,"","","");			// BAN removed
			}
			$msg = "league_player_ban_ubnan_all_ok";
			
		/* Normalni Edit */
		} else {
			$query = mysql_query("UPDATE $db_league_players_bans SET 
			league_player_ban_reason='".mysql_real_escape_string($_POST['player_ban_reason'])."', 
			league_player_ban_date_from='".$player_ban_from."', 
			league_player_ban_date_to='".$player_ban_to."' 
			WHERE league_player_ban_id=".(integer)$_POST['bid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$msg = "league_player_ban_edit_ok";
		}
		
		if (!$query){$msg = "league_player_ban_edit_er";}
		
		header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_players_show&lid=".$_GET['lid']."&project=".$_SESSION['project']."&msg=".$msg);
		exit;
	}
}
/* AWARDS */
if ($_GET['action'] == "league_award_add" || $_GET['action'] == "league_award_edit"){
	$league_name = PrepareForDB($_POST['league_name']);
	$league_description = PrepareForDB($_POST['league_description']);
	
	$gls = explode(":",$_POST['league_award_gls']); // Explode this variable to games_id:league_id:season_id
	if ($_GET['action'] == "league_award_add"){
		$res = mysql_query("INSERT INTO $db_league_awards VALUES(
		'',
		'',
		'',
		'',
		'',
		'".(integer)$gls[1]."',
		'".(integer)$gls[2]."',
		'".(integer)$gls[0]."',
		'".(integer)$_POST['league_award_place']."',
		'".PrepareForDB($_POST['league_award_name'])."',
		'".PrepareForDB($_POST['league_award_img'])."',
		'',
		'".(integer)$_POST['league_award_mode']."',
		".(integer)$_POST['league_award_active'].")") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if($res){
			$msg = "league_award_add_ok";
		} else {
			$msg = "league_award_add_er";
		}
	}
	
	if ($_GET['action'] == "league_award_edit"){
		$res = mysql_query("UPDATE $db_league_awards SET 
		league_award_league_id = ".(integer)$gls[1].", 
		league_award_season_id = ".(integer)$gls[2].", 
		league_award_game_id = ".(integer)$gls[0].", 
		league_award_place = ".(integer)$_POST['league_award_place'].", 
		league_award_name = '".PrepareForDB($_POST['league_award_name'])."', 
		league_award_img = '".PrepareForDB($_POST['league_award_img'])."', 
		league_award_mode = ".(integer)$_POST['league_award_mode'].",
		league_award_active = ".(integer)$_POST['league_award_active']." 
		WHERE league_award_id = ".(integer)$_GET['laid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		if($res){
			$msg = "league_award_edit_ok";
		} else {
			$msg = "league_award_edit_er";
		}
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_league.php?action=league_award_add&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}
if ($_GET['action'] == "league_awards_give_to_teams" || $_GET['action'] == "league_awards_give_to_players"){
	if ($_GET['action'] == "league_awards_give_to_teams"){
		$res_results_teams = mysql_query("
		SELECT lt.league_team_id, ls.league_season_league_id, ls.league_season_end, lts.league_team_sub_id, lts.league_team_sub_game_id, lsrt.league_season_result_team_season_id 
		FROM $db_league_seasons_results_teams AS lsrt 
		JOIN $db_league_seasons AS ls ON ls.league_season_id = lsrt.league_season_result_team_season_id 
		JOIN $db_league_teams AS lt ON lt.league_team_id = lsrt.league_season_result_team_team_id 
		JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_team_id = lsrt.league_season_result_team_team_id 
		JOIN $db_country AS c ON c.country_id = lt.league_team_country_id 
		WHERE lsrt.league_season_result_team_season_id = ".(integer)$_GET['sid']." 
		ORDER BY lsrt.league_season_result_team_points DESC LIMIT 3") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$x = 1;
		while ($ar_results_teams = mysql_fetch_array($res_results_teams)){
			$res = mysql_query("UPDATE $db_league_awards SET 
			league_award_team_id = ".(integer)$ar_results_teams['league_team_id'].", 
			league_award_team_sub_id = ".(integer)$ar_results_teams['league_team_sub_id'].", 
			league_award_date = '".$ar_results_teams['league_season_end']."' 
			WHERE league_award_league_id = ".(integer)$ar_results_teams['league_season_league_id']." 
			AND league_award_season_id = ".(integer)$_POST['sid']." 
			AND league_award_game_id = ".(integer)$ar_results_teams['league_team_sub_game_id']."
			AND league_award_place = ".(integer)$x." 
			AND league_award_mode = 2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$x++;
		}
		if($res){
			$msg = "league_award_give_to_teams_ok";
		} else {
			$msg = "league_award_give_to_teams_er";
		}
		$show = "season_teams_results";
	}
	
	if ($_GET['action'] == "league_awards_give_to_players"){
		$res_results_players = mysql_query("
		SELECT lt.league_team_id, ls.league_season_league_id, ls.league_season_end,  lts.league_team_sub_id, lts.league_team_sub_game_id, lp.league_player_admin_id, lsrp.league_season_result_player_season_id, lsrp.league_season_result_player_player_id 
		FROM $db_league_seasons_results_players AS lsrp 
		JOIN $db_league_seasons AS ls ON ls.league_season_id = lsrp.league_season_result_player_season_id 
		JOIN $db_league_players AS lp ON lp.league_player_id = lsrp.league_season_result_player_player_id 
		JOIN $db_league_teams AS lt ON lt.league_team_id = lp.league_player_team_id 
		JOIN $db_league_teams_sub AS lts ON lts.league_team_sub_team_id = lp.league_player_team_sub_id 
		JOIN $db_country AS c ON c.country_id = lt.league_team_country_id 
		WHERE lsrp.league_season_result_player_season_id = ".(integer)$_GET['sid']." 
		ORDER BY lsrp.league_season_result_player_points DESC LIMIT 3") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$x = 1;
		while ($ar_results_players = mysql_fetch_array($res_results_players)){
			$res = mysql_query("UPDATE $db_league_awards SET 
			league_award_admin_id = ".(integer)$ar_results_players['league_player_admin_id'].", 
			league_award_player_id = ".(integer)$ar_results_players['league_season_result_player_player_id'].", 
			league_award_team_id = ".(integer)$ar_results_players['league_team_id'].", 
			league_award_team_sub_id = ".(integer)$ar_results_players['league_team_sub_id'].", 
			league_award_date = '".$ar_results_players['league_season_end']."' 
			WHERE league_award_league_id = ".(integer)$ar_results_players['league_season_league_id']." 
			AND league_award_season_id = ".(integer)$_POST['sid']." 
			AND league_award_game_id = ".(integer)$ar_results_players['league_team_sub_game_id']."
			AND league_award_place = ".(integer)$x." 
			AND league_award_mode = 1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$x++;
		}
		if($res){
			$msg = "league_award_give_to_players_ok";
		} else {
			$msg = "league_award_give_to_players_er";
		}
		$show = "season_players_results";
	}
	header ("Location: ".$eden_cfg['url_cms']."show_league_players.php?action=results_show&show=".$show."&sid=".$_POST['sid']."&mode=league&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}