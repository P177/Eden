<?php
// Provereni opravneni
if ($_GET['action'] == "clanwar_add"){
	if (CheckPriv("groups_clanwars_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
}elseif ($_GET['action'] == "clanwar_edit"){
	if (CheckPriv("groups_clanwars_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
} else {
	echo _NOTENOUGHPRIV;ShowMain();exit;
}

if ($_POST['team1_id'] == "" && $_POST['team2_id'] == "" ){
	/* Z obsahu proměnné body vyjmout nepovolené tagy */
	foreach ($_POST as $key => $val){
		$post[$key] = strip_tags($val,"<a>,<br>,<strong>,<em>");
	}
} else {
	$res8 = mysql_query("SELECT * FROM $db_liga_team WHERE id=".(integer)$_POST['team1_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar8 = mysql_fetch_array($res8);
	$res9 = mysql_query("SELECT * FROM $db_liga_team WHERE id=".(integer)$_POST['team2_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar9 = mysql_fetch_array($res9);
	
	$team1 = $ar8['tag'];
	$team1_name = $ar8['nazev'];
	$team1_country = "";
}
$post['liga'] = str_replace("&amp;","&",$post['liga']);
/* Prevedeme datum z vystupu kalendaroveho skriptu na format yyyyMMdd */
preg_match ("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $_POST['clan_cw_date'], $date);
$cw_date = $date[3]."-".$date[2]."-".$date[1];

if ($_GET['action'] == "clanwar_add"){
	mysql_query("INSERT INTO $db_clan_clanwars VALUES('',
	'".mysql_real_escape_string($post['team1'])."',
	'".(integer)$_POST['team1_id']."',
	'".mysql_real_escape_string($post['team1_name'])."',
	'".(integer)$_POST['team1_country_id']."',
	'".(integer)$_POST['team1_score']."',
	'".(integer)$_POST['team1_score_1']."',
	'".(integer)$_POST['team1_score_2']."',
	'".(integer)$_POST['team1_score_3']."',
	'".(integer)$_POST['team1_score_4']."',
	'".(integer)$_POST['team1_score_5']."',
	'".(integer)$_POST['team1_score_6']."',
	'".(integer)$_POST['team1_score_7']."',
	'".mysql_real_escape_string($post['team1_sestava'])."',
	'".mysql_real_escape_string($post['team1_www'])."',
	'".mysql_real_escape_string($post['team1_irc'])."',
	'".mysql_real_escape_string($post['team2'])."',
	'".(integer)$team2_id."',
	'".mysql_real_escape_string($post['team2_name'])."',
	'".(integer)$_POST['team2_country_id']."',
	'".(integer)$_POST['team2_score']."',
	'".(integer)$_POST['team2_score_1']."',
	'".(integer)$_POST['team2_score_2']."',
	'".(integer)$_POST['team2_score_3']."',
	'".(integer)$_POST['team2_score_4']."',
	'".(integer)$_POST['team2_score_5']."',
	'".(integer)$_POST['team2_score_6']."',
	'".(integer)$_POST['team2_score_7']."',
	'".mysql_real_escape_string($post['team2_sestava'])."',
	'".mysql_real_escape_string($post['team2_www'])."',
	'".mysql_real_escape_string($post['team2_irc'])."',
	'".mysql_real_escape_string($post['map_1'])."',
	'".mysql_real_escape_string($post['map_2'])."',
	'".mysql_real_escape_string($post['map_3'])."',
	'".mysql_real_escape_string($post['map_4'])."',
	'".mysql_real_escape_string($post['map_5'])."',
	'".mysql_real_escape_string($post['map_6'])."',
	'".mysql_real_escape_string($post['map_7'])."',
	'".(integer)$_POST['game_id']."',
	'".(integer)$_POST['gametype_id']."',
	'".mysql_real_escape_string($post['clanwartype'])."',
	'".mysql_real_escape_string($post['liga'])."',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'".mysql_real_escape_string($post['comments_cs'])."',
	'".mysql_real_escape_string($post['comments_en'])."',
	'".mysql_real_escape_string($cw_date)."',
	'".(integer)$_POST['clan_cw_num']."',
	'',
	'',
	'".(integer)$_POST['clan_cw_mode']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "add_ok";
	} else {
		$msg = "add_no";
	}
}
if ($_GET['action'] == "clanwar_edit"){
	
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)){echo _ERROR_FTP; exit;}
	
	if ($_FILES['userfile1']['name'] != ""){
		$extenze1 = strtok($_FILES['userfile1']['name'] ,".");
		$extenze1 = strtok(".");
		$userfile_name1 = (integer)$_GET['id']."_1.".$extenze1;
		$new_name1 = $url_screenshots.$userfile_name1;
		$source_file1 = $_FILES['userfile1']['tmp_name'];
		$destination_file1 = $ftp_path_screenshots.$userfile_name1;
		$upload1 = ftp_put($conn_id, $destination_file1, $source_file1, FTP_BINARY);
		if (!$upload1) { echo _ERROR_UPLOAD;}
		$u_file_name1 = "clan_cw_scr_1='".mysql_real_escape_string($userfile_name1)."',";
	}
	if ($_FILES['userfile2']['name'] != ""){
		$extenze2 = strtok($_FILES['userfile2']['name'] ,".");
		$extenze2 = strtok(".");
		$userfile_name2 = (integer)$_GET['id']."_2.".$extenze2;
		$new_name2 = $url_screenshots.$userfile_name2;
		$source_file2 = $_FILES['userfile2']['tmp_name'];
		$destination_file2 = $ftp_path_screenshots.$userfile_name2;
		$upload2 = ftp_put($conn_id, $destination_file2, $source_file2, FTP_BINARY);
		if (!$upload2) { echo _ERROR_UPLOAD;}
		$u_file_name2 = "clan_cw_scr_2='".mysql_real_escape_string($userfile_name2)."',";
	}
	if ($_FILES['userfile3']['name'] != ""){
		$extenze3 = strtok($_FILES['userfile3']['name'] ,".");
		$extenze3 = strtok(".");
		$userfile_name3 = (integer)$_GET['id']."_3.".$extenze3;
		$new_name3 = $url_screenshots.$userfile_name3;
		$source_file3 = $_FILES['userfile3']['tmp_name'];
		$destination_file3 = $ftp_path_screenshots.$userfile_name3;
		$upload3 = ftp_put($conn_id, $destination_file3, $source_file3, FTP_BINARY);
		if (!$upload3) { echo _ERROR_UPLOAD;}
		$u_file_name3 = "clan_cw_scr_3='".mysql_real_escape_string($userfile_name3)."',";
	}
	if ($_FILES['userfile4']['name'] != ""){
		$extenze4 = strtok($_FILES['userfile4']['name'] ,".");
		$extenze4 = strtok(".");
		$userfile_name4 = (integer)$_GET['id']."_4.".$extenze4;
		$new_name4 = $url_screenshots.$userfile_name4;
		$source_file4 = $_FILES['userfile4']['tmp_name'];
		$destination_file4 = $ftp_path_screenshots.$userfile_name4;
		$upload4 = ftp_put($conn_id, $destination_file4, $source_file4, FTP_BINARY);
		if (!$upload4) { echo _ERROR_UPLOAD;}
		$u_file_name4 = "clan_cw_scr_4='".mysql_real_escape_string($userfile_name4)."',";
	}
	if ($_FILES['userfile5']['name'] != ""){
		$extenze5 = strtok($_FILES['userfile5']['name'] ,".");
		$extenze5 = strtok(".");
		$userfile_name5 = (integer)$_GET['id']."_5.".$extenze5;
		$new_name5 = $url_screenshots.$userfile_name5;
		$source_file5 = $_FILES['userfile5']['tmp_name'];
		$destination_file5 = $ftp_path_screenshots.$userfile_name5;
		$upload5 = ftp_put($conn_id, $destination_file5, $source_file5, FTP_BINARY);
		if (!$upload5) { echo _ERROR_UPLOAD;}
		$u_file_name5 = "clan_cw_scr_5='".mysql_real_escape_string($userfile_name5)."',";
	}
	if ($_FILES['userfile6']['name'] != ""){
		$extenze6 = strtok($_FILES['userfile6']['name'] ,".");
		$extenze6 = strtok(".");
		$userfile_name6 = (integer)$_GET['id']."_6.".$extenze6;
		$new_name6 = $url_screenshots.$userfile_name6;
		$source_file6 = $_FILES['userfile6']['tmp_name'];
		$destination_file6 = $ftp_path_screenshots.$userfile_name6;
		$upload6 = ftp_put($conn_id, $destination_file6, $source_file6, FTP_BINARY);
		if (!$upload6) { echo _ERROR_UPLOAD;}
		$u_file_name6 = "clan_cw_scr_6='".mysql_real_escape_string($userfile_name6)."',";
	}
	if ($_FILES['userfile7']['name'] != ""){
		$extenze7 = strtok($_FILES['userfile7']['name'] ,".");
		$extenze7 = strtok(".");
		$userfile_name7 = (integer)$_GET['id']."_7.".$extenze7;
		$new_name7 = $url_screenshots.$userfile_name7;
		$source_file7 = $_FILES['userfile7']['tmp_name'];
		$destination_file7 = $ftp_path_screenshots.$userfile_name7;
		$upload7 = ftp_put($conn_id, $destination_file7, $source_file7, FTP_BINARY);
		if (!$upload7) { echo _ERROR_UPLOAD;}
		$u_file_name7 = "clan_cw_scr_7='".mysql_real_escape_string($userfile_name7)."',";
	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
	
	if ($del_uf1 == 1){$u_file_name1 = " clan_cw_scr_1='', ";}
	if ($del_uf2 == 1){$u_file_name2 = " clan_cw_scr_2='', ";}
	if ($del_uf3 == 1){$u_file_name3 = " clan_cw_scr_3='', ";}
	if ($del_uf4 == 1){$u_file_name4 = " clan_cw_scr_4='', ";}
	if ($del_uf5 == 1){$u_file_name5 = " clan_cw_scr_5='', ";}
	if ($del_uf6 == 1){$u_file_name6 = " clan_cw_scr_6='', ";}
	if ($del_uf7 == 1){$u_file_name7 = " clan_cw_scr_7='', ";}
	
	$res = mysql_query("UPDATE $db_clan_clanwars SET
	clan_cw_team1='".mysql_real_escape_string($post['team1'])."',
	clan_cw_team1_id=".(integer)$team1_id.",
	clan_cw_team1_name='".mysql_real_escape_string($post['team1_name'])."',
	clan_cw_team1_country_id=".(integer)$_POST['team1_country_id'].",
	clan_cw_team1_score=".(integer)$_POST['team1_score'].",
	clan_cw_team1_score_1=".(integer)$_POST['team1_score_1'].",
	clan_cw_team1_score_2=".(integer)$_POST['team1_score_2'].",
	clan_cw_team1_score_3=".(integer)$_POST['team1_score_3'].",
	clan_cw_team1_score_4=".(integer)$_POST['team1_score_4'].",
	clan_cw_team1_score_5=".(integer)$_POST['team1_score_5'].",
	clan_cw_team1_score_6=".(integer)$_POST['team1_score_6'].",
	clan_cw_team1_score_7=".(integer)$_POST['team1_score_7'].",
	clan_cw_team1_roster='".mysql_real_escape_string($post['team1_sestava'])."',
	clan_cw_team1_www='".mysql_real_escape_string($post['team1_www'])."',
	clan_cw_team1_irc='".mysql_real_escape_string($post['team1_irc'])."',
	clan_cw_team2='".mysql_real_escape_string($post['team2'])."',
	clan_cw_team2_id=".(integer)$team2_id.",
	clan_cw_team2_name='".mysql_real_escape_string($post['team2_name'])."',
	clan_cw_team2_country_id=".(integer)$_POST['team2_country_id'].",
	clan_cw_team2_score='".mysql_real_escape_string($post['team2_score'])."',
	clan_cw_team2_score_1='".mysql_real_escape_string($post['team2_score_1'])."',
	clan_cw_team2_score_2='".mysql_real_escape_string($post['team2_score_2'])."',
	clan_cw_team2_score_3='".mysql_real_escape_string($post['team2_score_3'])."',
	clan_cw_team2_score_4='".mysql_real_escape_string($post['team2_score_4'])."',
	clan_cw_team2_score_5='".mysql_real_escape_string($post['team2_score_5'])."',
	clan_cw_team2_score_6='".mysql_real_escape_string($post['team2_score_6'])."',
	clan_cw_team2_score_7='".mysql_real_escape_string($post['team2_score_7'])."',
	clan_cw_team2_roster='".mysql_real_escape_string($post['team2_sestava'])."',
	clan_cw_team2_www='".mysql_real_escape_string($post['team2_www'])."',
	clan_cw_team2_irc='".mysql_real_escape_string($post['team2_irc'])."',
	clan_cw_map_1='".mysql_real_escape_string($post['map_1'])."',
	clan_cw_map_2='".mysql_real_escape_string($post['map_2'])."',
	clan_cw_map_3='".mysql_real_escape_string($post['map_3'])."',
	clan_cw_map_4='".mysql_real_escape_string($post['map_4'])."',
	clan_cw_map_5='".mysql_real_escape_string($post['map_5'])."',
	clan_cw_map_6='".mysql_real_escape_string($post['map_6'])."',
	clan_cw_map_7='".mysql_real_escape_string($post['map_7'])."',
	clan_cw_game_id=".(integer)$_POST['game_id'].",
	clan_cw_gametype_id=".(integer)$_POST['gametype_id'].",
	clan_cw_clanwartype='".mysql_real_escape_string($post['clanwartype'])."',
	clan_cw_league='".mysql_real_escape_string($post['liga'])."',
	$u_file_name1 $u_file_name2 $u_file_name3 $u_file_name4 $u_file_name5 $u_file_name6 $u_file_name7
	clan_cw_demo_01='".mysql_real_escape_string($post['demo01'])."',
	clan_cw_demo_02='".mysql_real_escape_string($post['demo02'])."',
	clan_cw_demo_03='".mysql_real_escape_string($post['demo03'])."',
	clan_cw_demo_04='".mysql_real_escape_string($post['demo04'])."',
	clan_cw_demo_05='".mysql_real_escape_string($post['demo05'])."',
	clan_cw_demo_06='".mysql_real_escape_string($post['demo06'])."',
	clan_cw_demo_07='".mysql_real_escape_string($post['demo07'])."',
	clan_cw_demo_08='".mysql_real_escape_string($post['demo08'])."',
	clan_cw_demo_09='".mysql_real_escape_string($post['demo09'])."',
	clan_cw_demo_10='".mysql_real_escape_string($post['demo10'])."',
	clan_cw_demo_desc_01='".mysql_real_escape_string($post['demopopis01'])."',
	clan_cw_demo_desc_02='".mysql_real_escape_string($post['demopopis02'])."',
	clan_cw_demo_desc_03='".mysql_real_escape_string($post['demopopis03'])."',
	clan_cw_demo_desc_04='".mysql_real_escape_string($post['demopopis04'])."',
	clan_cw_demo_desc_05='".mysql_real_escape_string($post['demopopis05'])."',
	clan_cw_demo_desc_06='".mysql_real_escape_string($post['demopopis06'])."',
	clan_cw_demo_desc_07='".mysql_real_escape_string($post['demopopis07'])."',
	clan_cw_demo_desc_08='".mysql_real_escape_string($post['demopopis08'])."',
	clan_cw_demo_desc_09='".mysql_real_escape_string($post['demopopis09'])."',
	clan_cw_demo_desc_10='".mysql_real_escape_string($post['demopopis10'])."',
	clan_cw_comments_cs='".mysql_real_escape_string($post['comments_cs'])."',
	clan_cw_comments_en='".mysql_real_escape_string($post['comments_en'])."',
	clan_cw_date='".mysql_real_escape_string($cw_date)."',
	clan_cw_num=".(integer)$_POST['clan_cw_num'].",
	clan_cw_mode=".(integer)$_POST['clan_cw_mode']."
	WHERE clan_cw_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "edit_ok";
	} else {
		$msg = "edit_no";
	}
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_clanwars.php?project=".$_SESSION['project']."&msg=".$msg);
exit;