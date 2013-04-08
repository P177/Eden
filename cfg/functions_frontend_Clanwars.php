<?php
/***********************************************************************************************************
*
*		CLANWARS
*
*		$mod (clan, repre)
*
***********************************************************************************************************/
function ClanWars($mod = "clan"){
	
	global $db_clan_clanwars,$db_comments,$db_clan_games,$db_clan_gametype,$db_country;
	global $url_flags,$url_games,$url_screenshots;
	
	$_GET['lang'] = AGet($_GET,'lang');
	
	$where = "";
	$cw_game_id = "";
	
	if ($mod == "clan"){
		$qry = " clan_games_repre=0";
		$qry2 = "clan_games_repre=0";
	} elseif ($mod == "repre") {
		$qry = " clan_games_repre=1";
		$qry2 = "clan_games_repre=1";
	}
	//clan_games_active=1
	$res_game = mysql_query("SELECT clan_games_id, clan_games_shortname FROM $db_clan_games WHERE $qry") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if (AGet($_GET,'cw_game2') != ""){
		while ($ar_game = mysql_fetch_array($res_game)){
			if (strtolower($_GET['cw_game2']) == strtolower($ar_game['clan_games_shortname'])){
				$cw_game_id = $ar_game['clan_games_id'];
			}
		}
		if ($cw_game_id == ""){$cw_game_id = 0;}
		$where = $cw_game_id;
	} else {
		$i=0;
		while ($ar_game = mysql_fetch_array($res_game)){
			if ($i > 0){$divider = ",";} else {$divider = "";}
			$where .= $divider.$ar_game['clan_games_id'];
			$i++;
		}
	}
	
	$res_num = mysql_query("SELECT COUNT(*) FROM $db_clan_clanwars WHERE clan_cw_mode=1 AND clan_cw_game_id IN ($where)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res_num);
	
	 echo "<table id=\"eden_clanwars\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
	
	$hits = 30; // Nastaveni poctu radku na strankach
	$m=0;// nastaveni iterace
	if (empty($_GET['page'])) {$_GET['page'] = 1;} // i kdyz je promenna $_GET['page'] prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	//$hits=3; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num[0]%$hits > 0) {$stw2++;}
	$np = $_GET['page']+1;
	$pp = $_GET['page']-1;
	if ($_GET['page'] == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($_GET['page']-1)*$hits;
	$ep = ($_GET['page']-1)*$hits+$hits;
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	if (AGet($_GET,'stav') == "open"){
		$query = "cw.*, cg.clan_games_shortname, cg.clan_games_game, c.country_shortname, gt.clan_gametype_game_type";
	} else { 
		$query = " cw.clan_cw_id, cw.clan_cw_team1_score, cw.clan_cw_team1_score_1, cw.clan_cw_team1_score_2, cw.clan_cw_team1_score_3, cw.clan_cw_team1_score_4, cw.clan_cw_team1_score_5, cw.clan_cw_team1_score_6, 
		cw.clan_cw_team1_score_7, cw.clan_cw_team2_score, cw.clan_cw_team2_score_1, cw.clan_cw_team2_score_2, cw.clan_cw_team2_score_3, cw.clan_cw_team2_score_4, cw.clan_cw_team2_score_5, cw.clan_cw_team2_score_6, 
		cw.clan_cw_team2_score_7, cw.clan_cw_team1, cw.clan_cw_team2, cw.clan_cw_date, cw.clan_cw_comments_cs, cw.clan_cw_comments_en, cg.clan_games_shortname, cg.clan_games_game, c.country_shortname, gt.clan_gametype_game_type";
	}
	$res = mysql_query("SELECT $query 
	FROM $db_clan_clanwars AS cw 
	JOIN $db_clan_games AS cg ON cg.clan_games_id=cw.clan_cw_game_id 
	JOIN $db_clan_gametype AS gt ON gt.clan_gametype_id=cw.clan_cw_gametype_id 
	JOIN $db_country AS c ON c.country_id=cw.clan_cw_team2_country_id 
	WHERE ($qry2) AND clan_cw_game_id IN ($where) ORDER BY cw.clan_cw_date DESC, cw.clan_cw_num DESC $limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$cislo = 0;
	while($ar = mysql_fetch_array($res)){
		$m++;
		// Spocitame komentare
		$res2 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['clan_cw_id']." AND comment_modul='clanwars'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num2 = mysql_fetch_array($res2);
		
		$score = GetScore($ar['clan_cw_team1_score'],$ar['clan_cw_team2_score']);
		$score1 = GetScore($ar['clan_cw_team1_score_1'],$ar['clan_cw_team2_score_1']);
		$score2 = GetScore($ar['clan_cw_team1_score_2'],$ar['clan_cw_team2_score_2']);
		$score3 = GetScore($ar['clan_cw_team1_score_3'],$ar['clan_cw_team2_score_3']);
		$score4 = GetScore($ar['clan_cw_team1_score_4'],$ar['clan_cw_team2_score_4']);
		$score5 = GetScore($ar['clan_cw_team1_score_5'],$ar['clan_cw_team2_score_5']);
		$score6 = GetScore($ar['clan_cw_team1_score_6'],$ar['clan_cw_team2_score_6']);
		$score7 = GetScore($ar['clan_cw_team1_score_7'],$ar['clan_cw_team2_score_7']);
		
		$comments_cs = TreatText($ar['clan_cw_comments_cs'],"70");
		$comments_en = TreatText($ar['clan_cw_comments_en'],"70");
		$team1 = TreatText($ar['clan_cw_team1'],"70");
		$team2 = TreatText($ar['clan_cw_team2'],"70");
		
		$gametype = $ar['clan_gametype_game_type'];
		$flag = NazevVlajky($ar['country_shortname'],$_GET['lang']);
		
		//Uprava datumu
		preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $ar['clan_cw_date'], $datum);
		$datum = $datum[3].'.'.$datum[2].'.'.$datum[1];
		// Nacteni sablony
		include "templates/tpl.clanwars.php";
		$cislo++;
	}
	// Nacteni sablony
	include "templates/tpl.clanwars.dalsi.php";
}
/***********************************************************************************************************
*
*		CLANWARS SMALL
*
*		Pro zobrazeni zapasu v male tabulce v pruhu
*
*		$mod (clan, repre)
*
***********************************************************************************************************/
function ClanWarsSmall($mod = "clan"){
	
	global $db_clan_clanwars,$db_comments,$db_clan_games,$db_clan_gametype,$db_country;
	global $url_flags,$url_games;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$where = "";
	$cw_game_id = "";
	
	if ($mod == "clan"){
		$qry = " AND clan_games_repre=0";
	} elseif ($mod == "repre") {
		$qry = " AND clan_games_repre=1";
	}
	
	$res_game = mysql_query("SELECT clan_games_id, clan_games_shortname FROM $db_clan_games WHERE clan_games_active=1 $qry") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if (AGet($_GET,'cw_game') != ""){
		while ($ar_game = mysql_fetch_array($res_game)){
			if (strtolower($_GET['cw_game']) == strtolower($ar_game['clan_games_shortname'])){
				$cw_game_id = $ar_game['clan_games_id'];
			}
		}
		if ($cw_game_id == ""){$cw_game_id = 0;}
		$where = $cw_game_id;
	} else {
		$i=1;
		while ($ar_game = mysql_fetch_array($res_game)){
			if($i == 1){
				$where = $ar_game['clan_games_id'];
			} else {
				$where .= ",".$ar_game['clan_games_id'];
			}
			$i++;
		}
	}
	$res_num = mysql_query("SELECT COUNT(*) FROM $db_clan_clanwars WHERE clan_cw_mode=1 AND clan_cw_game_id IN ($where)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($res_num);
	/* Neodehrane zapasy */
	$res_u = mysql_query("SELECT cw.clan_cw_id, cw.clan_cw_date, cw.clan_cw_team2, cg.clan_games_shortname, cg.clan_games_game, c.country_shortname 
	FROM $db_clan_clanwars AS cw 
	JOIN $db_clan_games AS cg ON cg.clan_games_id=cw.clan_cw_game_id 
	JOIN $db_country AS c ON c.country_id=cw.clan_cw_team2_country_id 
	WHERE clan_cw_mode=0 AND clan_cw_game_id IN ($where) ORDER BY cw.clan_cw_date ASC, cw.clan_cw_num DESC LIMIT 4") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_u = mysql_num_rows($res_u);
	if ($num_u > 0){
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"3\">"._CW_UPCOMMING."</td>\n";
		echo "	</tr>\n";
	}
	while($ar_u = mysql_fetch_array($res_u)){
		$team2 = TreatText($ar_u['clan_cw_team2'],"70");
		$flag_name = NazevVlajky($ar_u['country_shortname'],$_GET['lang']);
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"12\"><img src=\"".$url_games.$ar_u['clan_games_shortname'].".gif\" alt=\"".$ar_u['clan_games_game']."\" title=\"".$ar_u['clan_games_game']."\" width=\"12\" height=\"12\" border=\"0\"></td>\n";
		echo "		<td valign=\"top\" width=\"100\" style=\"font-size:9px;\"><img src=\"".$url_flags.$ar_u['country_shortname'].".gif\" width=\"18\" height=\"12\" alt=\"".$flag_name."\" title=\"".$flag_name."\">&nbsp;<strong><span class=\"cw_link\"><a href=\"index.php?lang=".$_GET['lang']."&amp;action=clanwars&amp;stav=open&amp;id_cw=".$ar_u['clan_cw_id']."&amp;filter=".$_GET['filter']."\">".$team2."</a></span></strong></td>\n";
		echo "		<td valign=\"top\" width=\"45\" style=\"font-size:10px;\"><strong>".FormatDate($ar_u['clan_cw_date'],"d.m.y")."</strong></td>\n";
		echo "	</tr>\n";
	}
	/* Odehrane zapasy */
	$res_p = mysql_query("SELECT cw.clan_cw_team1_score, cw.clan_cw_team2_score, cw.clan_cw_id, cw.clan_cw_date, cw.clan_cw_team2, cg.clan_games_shortname, cg.clan_games_game, c.country_shortname 
	FROM $db_clan_clanwars AS cw 
	JOIN $db_clan_games AS cg ON cg.clan_games_id=cw.clan_cw_game_id 
	JOIN $db_country AS c ON c.country_id=cw.clan_cw_team2_country_id 
	WHERE cw.clan_cw_mode=1 AND clan_cw_game_id IN ($where) ORDER BY cw.clan_cw_date DESC, cw.clan_cw_num DESC LIMIT 10") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($num_u > 0){
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"3\">"._CW_PLAYED."</td>\n";
		echo "	</tr>\n";
	}
	$i = 0;
	while($ar_p = mysql_fetch_array($res_p)){
		$score = GetScore($ar_p['clan_cw_team1_score'],$ar_p['clan_cw_team2_score']);
		$team2 = TreatText($ar_p['clan_cw_team2'],"70");
		$flag_name = NazevVlajky($ar_p['country_shortname'],$_GET['lang']);
		if ($i < 10){
			echo "	<tr>\n";
			echo "		<td valign=\"top\" width=\"12\"><img src=\"".$url_games.$ar_p['clan_games_shortname'].".gif\" alt=\"".$ar_p['clan_games_game']."\" title=\"".$ar_p['clan_games_game']."\" width=\"12\" height=\"12\" border=\"0\"></td>\n";
			echo "		<td valign=\"top\" width=\"100\" style=\"font-size:9px;\"><img src=\"".$url_flags.$ar_p['country_shortname'].".gif\" width=\"18\" height=\"12\" alt=\"".$flag_name."\" title=\"".$flag_name."\">&nbsp;<strong><span class=\"cw_link\"><a href=\"index.php?lang=".$_GET['lang']."&amp;action=clanwars&amp;stav=open&amp;id_cw=".$ar_p['clan_cw_id']."&amp;filter=".$_GET['filter']."\">".$team2."</a></span></strong></td>\n";
			echo "		<td valign=\"top\" width=\"45\" style=\"font-size:10px;\"><strong>".$score."</strong></td>\n";
			echo "	</tr>\n";
		 }
		$i++;
	}
}
/***********************************************************************************************************
*
*		GET SCORE
*
*		$score1 - score prvniho tymu
*		$score2 - score druheho tymu
*
***********************************************************************************************************/
function GetScore ($score1 = 0,$score2 = 0){
	if ($score1 < 10){$team1_score = "0".$score1;} else {$team1_score = $score1;}
	if ($score2 < 10){$team2_score = "0".$score2;} else {$team2_score = $score2;}
	if ($score1 > $score2){
		$team1_score = "<span class=\"green\">".$team1_score."</span>";
		$team2_score = "<span class=\"green\">".$team2_score."</span>";
	} elseif ($score1 < $score2){
		$team1_score = "<span class=\"red\">".$team1_score."</span>";
		$team2_score = "<span class=\"red\">".$team2_score."</span>";
	} else {
		$team1_score = "<span class=\"dblue\">".$team1_score."</span>";
		$team2_score = "<span class=\"dblue\">".$team2_score."</span>";
	}
	return $team1_score.":".$team2_score;
}
/***********************************************************************************************************
*
*		SHOW ICON
*
*		$mode	- clan, repre
*		$form	- all, limited (zobrazi bud vsechny hry, nebo jen hry ktere jsou aktivni)
*		$size	- small, big (small je maly bocni, big je velky prehled)
*		$limit	- pocet zobrazenych ikon v rade
*
***********************************************************************************************************/
function ClanShowGameIcon ($mode = "clan", $form = "all", $size = "small", $limit = 0){
	
	global $db_clan_games;
	global $url_games;
	
	$_GET['action'] = AGet($_GET,'action');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($mode == "clan"){
		$qry1 = "clan_games_repre=0";
	} elseif ($mode == "repre") {
		$qry1 = "clan_games_repre=1";
	}
	
	if ($form == "all"){
		$qry2 = " AND (clan_games_active=1 OR clan_games_active=0)";
	} elseif ($form == "limited") {
		$qry2 = " AND clan_games_active=1";
	}
	
	if ($size == "small"){
		$cw_game = "cw_game";
	} elseif ($size == "big"){
		$cw_game = "cw_game2";
	}
	
	$i = 1;
	$res_game = mysql_query("SELECT clan_games_id, clan_games_game, clan_games_shortname FROM $db_clan_games WHERE $qry1 $qry2") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$outcome = FALSE;
	while ($ar_game = mysql_fetch_array($res_game)){
		$outcome .= "<a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;".$cw_game."=".$ar_game['clan_games_shortname']."\" target=\"_self\"><img src=\"".$url_games.$ar_game['clan_games_shortname'].".gif\" alt=\"".$ar_game['clan_games_game']."\" title=\"".$ar_game['clan_games_game']."\" width=\"12\" height=\"12\" border=\"0\"></a> &nbsp;&nbsp;";
		if ($limit == $i){$outcome .= "<br>"; $i = 0;}
		$i++;
	}
	return $outcome;
}