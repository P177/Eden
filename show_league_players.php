<?php
if ($_SESSION["lang"] == ""){$_SESSION["lang"] = "cz";}
include_once (dirname(__file__)."/sessions.php");
include_once (dirname(__file__)."/functions.php");
include_once (dirname(__file__)."/lang/lang-".$_SESSION["lang"].".php");
include_once (dirname(__file__)."/modul_league.php");
switch ($_GET['show']){
	case "season_players_all":
		$title = _LEAGUE_LIST_ALLOWED_PLAYERS_ALL_S;
	break;
	case "season_players_all_guid":
		$title = _LEAGUE_LIST_ALLOWED_PLAYERS_ALL_GUID_S;
	break;
	case "season_players_results":
		$title = _LEAGUE_SEASON_RESULTS_PLAYERS_S;
	break;
	case "round_result":
		$title = _LEAGUE_SEASON_ROUND_RESULTS;
	break;
	case "season_teams_results":
		$title = _LEAGUE_SEASON_RESULTS_TEAMS_S;
	break;
	default:
		$title = _LEAGUE_LIST_ALLOWED_PLAYERS_SHOW;
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<head>\n";
echo "<title>".$title."</title>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden.css\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "</head>\n";
echo "<body leftmargin=\"5\" rightmargin=\"0\" marginheight=\"0\" marginwidth=\"0\" topmargin=\"5\" alink=\"#000000\" link=\"#000000\" vlink=\"#000000\" bottommargin=\"0\" bgcolor=\"#FFFFFF\">\n";
if ($_GET['msg']){
	echo "<div class=\"msg\">".SysMsg($_GET['msg'])."</div>";
}
echo "<span class=\"nadpis\">".$title."</span>\n";
switch ($_GET['show']){
	case "season_players_results":
		echo LeagueSeasonPlayersResults();
	break;
	case "round_result":
		AddResults($_GET['rid']);
	break;
	case "season_teams_results":
		echo LeagueSeasonTeamsResults();
	break;
	default:
		ListAllowedPlayers();
}
echo "</body>\n";
echo "</html>\n";