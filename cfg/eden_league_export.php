<?php //ř
// Nastaveni spravne timezone
date_default_timezone_set('Europe/Prague');
if (!$_GET['project']){exit;}
if (!$_GET['df']){exit;}

preg_match ("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $_GET['df'], $datetime);
$start_date = date("Y-m-d H:i:s", mktime(0,0,0,$datetime[2],$datetime[3],$datetime[1]));

include_once "db.".$_GET['project'].".inc.php";
include_once "functions_frontend.php";

/* season = season */
/* tournament = round */

$ret = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
$ret .= "<pokerfun>\n";
$ret .= "	<seasons>\n";
/* Zobrazeni sezon */
$res_season = mysql_query("SELECT league_season_id, league_season_name, league_season_start, league_season_end FROM $db_league_seasons WHERE (league_season_start >= '".mysql_real_escape_string($start_date)."' AND league_season_end<NOW()) AND league_season_league_id=1 AND league_season_active=1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
while ($ar_season = mysql_fetch_array($res_season)){
	$ret .= "		<season>\n";
	$ret .= "			<id>".$ar_season['league_season_id']."</id>\n";
	$ret .= "			<title>".stripslashes($ar_season['league_season_name'])."</title>\n";
	$ret .= "			<startDate>".$ar_season['league_season_start']."</startDate>\n";
	$ret .= "			<endDate>".$ar_season['league_season_start']."</endDate>\n";
	$ret .= "		<tournaments>\n";
	/* Zobrazeni kol */
	$res_rounds = mysql_query("SELECT league_season_round_id, league_season_round_date FROM $db_league_seasons_rounds WHERE league_season_round_season_id=".(integer)$ar_season['league_season_id']." ORDER BY league_season_round_num ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar_rounds = mysql_fetch_array($res_rounds)){
		$ret .= "			<tournament>\n";
		$ret .= "				<id>".$ar_rounds['league_season_round_id']."</id>\n";
		$ret .= "				<startDate>".$ar_rounds['league_season_round_date']."</startDate>\n";
		//				<endDate>2011-01-02		(datum konce turnaje)</endDate>
		//				<title>Název turnaje</title>
		$ret .= "				<players>\n";
		/* Zobrazeni hracu a vysledku v jednotlivych kolech sezony */
		$res_player = mysql_query("SELECT a.admin_nick, lsrrp.league_season_round_result_player_player_id, lsrrp.league_season_round_result_player_points, lsrrp.league_season_round_result_player_place 
		FROM $db_league_seasons_rounds_results_players AS lsrrp 
		JOIN $db_league_players AS lp ON lp.league_player_id = lsrrp.league_season_round_result_player_player_id 
		JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
		WHERE lsrrp.league_season_round_result_player_season_id=".(integer)$ar_season['league_season_id']." AND lsrrp.league_season_round_result_player_round_id=".(integer)$ar_rounds['league_season_round_id']." 
		ORDER BY lsrrp.league_season_round_result_player_place ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar_player = mysql_fetch_array($res_player)){
			$ret .= "					<player>\n";
			$ret .= "						<id>".$ar_player['league_season_round_result_player_player_id']."</id>\n";
			$ret .= "						<rank>".$ar_player['league_season_round_result_player_place']."</rank>\n";
			$ret .= "						<name>".stripslashes($ar_player['admin_nick'])."</name>\n";
			$ret .= "						<points>".$ar_player['league_season_round_result_player_points']."</points>\n";
			$ret .= "					</player>\n";
		}
		$ret .= "				</players>\n";
		$ret .= "			</tournament>\n";
	}
	$ret .= "		</tournaments>\n";
	$ret .= "		<players>\n";
	/* Zobrazeni celkovych vysledku hracu v sezone */
	$res_player_s = mysql_query("SELECT a.admin_nick, lsrp.league_season_result_player_player_id, lsrp.league_season_result_player_points 
		FROM $db_league_seasons_results_players AS lsrp 
		JOIN $db_league_players AS lp ON lp.league_player_id=lsrp.league_season_result_player_player_id 
		JOIN $db_admin AS a ON a.admin_id=lp.league_player_admin_id 
		WHERE lsrp.league_season_result_player_season_id=".(integer)$ar_season['league_season_id']." 
		ORDER BY lsrp.league_season_result_player_points DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while ($ar_player_s = mysql_fetch_array($res_player_s)){
		$ret .= "			<player>\n";
		$ret .= "				<id>".$ar_player_s['league_season_result_player_player_id']."</id>\n";
		$ret .= "				<rank>".$i."</rank>\n";/* Poradi je nejiste, nebot pri shodnem poctu bodu by meli mit hraci stejne umisteni */
		$ret .= "				<name>".stripslashes($ar_player_s['admin_nick'])."</name>\n";
		$ret .= "				<points>".$ar_player_s['league_season_result_player_points']."</points>\n";
		$ret .= "			</player>\n";
		$i++;
	}
	$ret .= "		</players>\n";
	$ret .= "		</season>\n";
}
$ret .= "	</seasons>\n";
$ret .= "</pokerfun>\n";
echo $ret;