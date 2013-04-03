<?php
$project = $_GET['project'];
require_once($eden_cfg['www_dir_cms']."db.".$_GET['project'].".inc.php");
require_once($eden_cfg['www_dir_cms']."functions_frontend.php");
switch ($_GET['imode']){
	case "adds":
		Reklama($_GET['rid'],1);
		break;
	case "league_team_league_reg":
		$res_league = mysql_query("SELECT league_league_description FROM $db_league_leagues WHERE league_league_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_league = mysql_fetch_array($res_league);
		$league_league_description = PrepareFromDB($ar_league['league_league_description'],1);
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
    	echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
		echo "	<link rel=\"stylesheet\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/".$_GET['project'].".css\" type=\"text/css\">\n";
		echo "	<link rel=\"stylesheet\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/eden-common.css\" type=\"text/css\">\n";
		echo "	</head>\n";
		echo "<html>\n";
		echo "<body bottommargin=\"10\" leftmargin=\"10\" marginheight=\"10\" marginwidth=\"10\" rightmargin=\"10\" topmargin=\"10\">\n";
		echo "<div style=\"margin:10px;\">".$league_league_description."</div>\n";
		echo "</body>\n";
		echo "</html>\n";
	break;
	case "video":
		$res_video = mysql_query("SELECT video_code FROM "._DB_VIDEOS." WHERE video_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_video = mysql_fetch_array($res_video);
		$video = htmlspecialchars_decode($ar_video['video_code'],ENT_QUOTES);
		//$video = addslashes(htmlspecialchars(ENT_QUOTES));
		
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
    	echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
		echo "	</head>\n";
		echo "<html>\n";
		echo "<body style=\"padding:0px;margin:0px;\">\n";
		echo "<div>".$video."</div>\n";
		echo "</body>\n";
		echo "</html>\n";
	break;
	case "":
		if ($_GET['lang'] == "en"){
			include_once($eden_cfg['www_dir_lang']."lang_en.php"); 
			require_once($eden_cfg['www_dir_cms']."eden_lang_en.php");
		} else {
			include_once($eden_cfg['www_dir_lang']."lang_cz.php");
			require_once($eden_cfg['www_dir_cms']."eden_lang_cz.php");
			$_GET['lang'] = "cz"; 
		}
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
    	echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
		echo "<html lang=\"en\">\n";
		echo "<head>\n";
		echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
		echo "	<link rel=\"stylesheet\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/".$_GET['project'].".css\" type=\"text/css\">\n";
		echo "	<link rel=\"stylesheet\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/eden-common.css\" type=\"text/css\">\n";
		echo "<style type=\"text/css\">\n";
		echo "body {background:#ffffff url(".$eden_cfg['url']."images/sys_white.gif);}";
		echo "</style>\n";
		echo "	</head>\n";
		echo "<html>\n";
		echo "<body bottommargin=\"5\" leftmargin=\"5\" marginheight=\"5\" marginwidth=\"5\" rightmargin=\"5\" topmargin=\"5\" class=\"clanekiframe\">\n";
		// Hlavni zobrazeni
		if ($_GET['action'] == "clanekiframe"){ClanekIframe($_GET['id'],$_GET['par']);}
		if ($_GET['action'] == "komentar"){Clanek($_GET['id'],$_GET['par']);}
		echo "</body>\n";
		echo "</html>\n";
	break;
}