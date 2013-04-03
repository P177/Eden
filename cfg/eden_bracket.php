<?php
include "./edencms/db.".$_GET['project'].".inc.php";
include "./edencms/functions_frontend.php";

$res = mysql_query("SELECT * FROM $db_cups_bracket WHERE cups_bracket_id=".(integer)$_GET['id']) or die ("<strong>Error4:</strong>".mysql_error());
$ar = mysql_fetch_array($res);

$wb_map = explode("#", $ar['cups_bracket_wb_maps']);
$lb_map = explode("#", $ar['cups_bracket_lb_maps']);
$fin_map = explode("#", $ar['cups_bracket_fin_maps']);
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "	<title>".stripslashes($ar['cups_bracket_cup_name'])."</title>\n";
echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/".$_GET['project'].".css\">\n";
echo "	<link rel=\"stylesheet\" href=\"".$eden_cfg['url_skins'].$eden_cfg['misc_skins_basic']."/eden-common.css\" type=text/css>\n";
echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "	<meta name=\"description\" lang=\"en\" content=\"\">\n";
echo "	<meta name=\"description\" lang=\"cs\" content=\"\">\n";
echo "	<meta name=\"generator\" content=\"HandMade\">\n";
echo "	<meta name=\"author\" content=\"BlackFoot\">\n";
echo "	<meta name=\"robots\" content=\"all,follow\">\n";
echo "	<meta name=\"copyright\" content=\"©1998 - ".date("Y").", BlackFoot\">\n";
echo "	<meta http-equiv=\"content-language\" content=\"cs\">\n";
echo "</head>\n";
echo "<body class=\"cup_page\" leftmargin=\"0\" topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
if ($ar['cups_bracket_cup_type'] == "8se"){$cups_bracket_cup_type = "8se";}
if ($ar['cups_bracket_cup_type'] == "8de"){$cups_bracket_cup_type = "8de";}
if ($ar['cups_bracket_cup_type'] == "16se"){$cups_bracket_cup_type = "16se";}
if ($ar['cups_bracket_cup_type'] == "16de"){$cups_bracket_cup_type = "16de";}
if ($ar['cups_bracket_cup_type'] == "32se"){$cups_bracket_cup_type = "32se";}
if ($ar['cups_bracket_cup_type'] == "32de"){$cups_bracket_cup_type = "32de";}
// Winner Bracket
if ($ar['cups_bracket_w1_1a_score'] > $ar['cups_bracket_w1_1b_score'] && $ar['cups_bracket_w1_1a_score'] != ""){$w1 = stripslashes($ar['cups_bracket_team_01']); $l1 = stripslashes($ar['cups_bracket_team_02']);}elseif ($ar['cups_bracket_w1_1a_score'] == 0 && $ar['cups_bracket_w1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w1 = stripslashes($ar['cups_bracket_team_02']); $l1 = stripslashes($ar['cups_bracket_team_01']);}
if ($ar['cups_bracket_w1_2a_score'] > $ar['cups_bracket_w1_2b_score'] && $ar['cups_bracket_w1_2a_score'] != ""){$w2 = stripslashes($ar['cups_bracket_team_03']); $l2 = stripslashes($ar['cups_bracket_team_04']);}elseif ($ar['cups_bracket_w1_2a_score'] == 0 && $ar['cups_bracket_w1_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w2 = stripslashes($ar['cups_bracket_team_04']); $l2 = stripslashes($ar['cups_bracket_team_03']);}
if ($ar['cups_bracket_w1_3a_score'] > $ar['cups_bracket_w1_3b_score'] && $ar['cups_bracket_w1_3a_score'] != ""){$w3 = stripslashes($ar['cups_bracket_team_05']); $l3 = stripslashes($ar['cups_bracket_team_06']);}elseif ($ar['cups_bracket_w1_3a_score'] == 0 && $ar['cups_bracket_w1_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w3 = stripslashes($ar['cups_bracket_team_06']); $l3 = stripslashes($ar['cups_bracket_team_05']);}
if ($ar['cups_bracket_w1_4a_score'] > $ar['cups_bracket_w1_4b_score'] && $ar['cups_bracket_w1_4a_score'] != ""){$w4 = stripslashes($ar['cups_bracket_team_07']); $l4 = stripslashes($ar['cups_bracket_team_08']);}elseif ($ar['cups_bracket_w1_4a_score'] == 0 && $ar['cups_bracket_w1_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w4 = stripslashes($ar['cups_bracket_team_08']); $l4 = stripslashes($ar['cups_bracket_team_07']);}
if ($ar['cups_bracket_w1_5a_score'] > $ar['cups_bracket_w1_5b_score'] && $ar['cups_bracket_w1_5a_score'] != ""){$w5 = stripslashes($ar['cups_bracket_team_09']); $l5 = stripslashes($ar['cups_bracket_team_10']);}elseif ($ar['cups_bracket_w1_5a_score'] == 0 && $ar['cups_bracket_w1_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w5 = stripslashes($ar['cups_bracket_team_10']); $l5 = stripslashes($ar['cups_bracket_team_09']);}
if ($ar['cups_bracket_w1_6a_score'] > $ar['cups_bracket_w1_6b_score'] && $ar['cups_bracket_w1_6a_score'] != ""){$w6 = stripslashes($ar['cups_bracket_team_11']); $l6 = stripslashes($ar['cups_bracket_team_12']);}elseif ($ar['cups_bracket_w1_6a_score'] == 0 && $ar['cups_bracket_w1_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w6 = stripslashes($ar['cups_bracket_team_12']); $l6 = stripslashes($ar['cups_bracket_team_11']);}
if ($ar['cups_bracket_w1_7a_score'] > $ar['cups_bracket_w1_7b_score'] && $ar['cups_bracket_w1_7a_score'] != ""){$w7 = stripslashes($ar['cups_bracket_team_13']); $l7 = stripslashes($ar['cups_bracket_team_14']);}elseif ($ar['cups_bracket_w1_7a_score'] == 0 && $ar['cups_bracket_w1_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w7 = stripslashes($ar['cups_bracket_team_14']); $l7 = stripslashes($ar['cups_bracket_team_13']);}
if ($ar['cups_bracket_w1_8a_score'] > $ar['cups_bracket_w1_8b_score'] && $ar['cups_bracket_w1_8a_score'] != ""){$w8 = stripslashes($ar['cups_bracket_team_15']); $l8 = stripslashes($ar['cups_bracket_team_16']);}elseif ($ar['cups_bracket_w1_8a_score'] == 0 && $ar['cups_bracket_w1_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w8 = stripslashes($ar['cups_bracket_team_16']); $l8 = stripslashes($ar['cups_bracket_team_15']);}
if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
	if ($ar['cups_bracket_w1_9a_score'] > $ar['cups_bracket_w1_9b_score'] && $ar['cups_bracket_w1_9a_score'] != ""){$w9 = stripslashes($ar['cups_bracket_team_17']); $l9 = stripslashes($ar['cups_bracket_team_18']);}elseif ($ar['cups_bracket_w1_9a_score'] == 0 && $ar['cups_bracket_w1_9b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w9 = stripslashes($ar['cups_bracket_team_18']); $l9 = stripslashes($ar['cups_bracket_team_17']);}
	if ($ar['cups_bracket_w1_10a_score'] > $ar['cups_bracket_w1_10b_score'] && $ar['cups_bracket_w1_10a_score'] != ""){$w10 = $ar['cups_bracket_team_19']; $l10 = $ar['cups_bracket_team_20'];}elseif ($ar['cups_bracket_w1_10a_score'] == 0 && $ar['cups_bracket_w1_10b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w10 = $ar['cups_bracket_team_20']; $l10 = $ar['cups_bracket_team_19'];}
	if ($ar['cups_bracket_w1_11a_score'] > $ar['cups_bracket_w1_11b_score'] && $ar['cups_bracket_w1_11a_score'] != ""){$w11 = $ar['cups_bracket_team_21']; $l11 = $ar['cups_bracket_team_22'];}elseif ($ar['cups_bracket_w1_11a_score'] == 0 && $ar['cups_bracket_w1_11b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w11 = $ar['cups_bracket_team_22']; $l11 = $ar['cups_bracket_team_21'];}
	if ($ar['cups_bracket_w1_12a_score'] > $ar['cups_bracket_w1_12b_score'] && $ar['cups_bracket_w1_12a_score'] != ""){$w12 = $ar['cups_bracket_team_23']; $l12 = $ar['cups_bracket_team_24'];}elseif ($ar['cups_bracket_w1_12a_score'] == 0 && $ar['cups_bracket_w1_12b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w12 = $ar['cups_bracket_team_24']; $l12 = $ar['cups_bracket_team_23'];}
	if ($ar['cups_bracket_w1_13a_score'] > $ar['cups_bracket_w1_13b_score'] && $ar['cups_bracket_w1_13a_score'] != ""){$w13 = $ar['cups_bracket_team_25']; $l13 = $ar['cups_bracket_team_26'];}elseif ($ar['cups_bracket_w1_13a_score'] == 0 && $ar['cups_bracket_w1_13b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w13 = $ar['cups_bracket_team_26']; $l13 = $ar['cups_bracket_team_25'];}
	if ($ar['cups_bracket_w1_14a_score'] > $ar['cups_bracket_w1_14b_score'] && $ar['cups_bracket_w1_14a_score'] != ""){$w14 = $ar['cups_bracket_team_27']; $l14 = $ar['cups_bracket_team_28'];}elseif ($ar['cups_bracket_w1_14a_score'] == 0 && $ar['cups_bracket_w1_14b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w14 = $ar['cups_bracket_team_28']; $l14 = $ar['cups_bracket_team_27'];}
	if ($ar['cups_bracket_w1_15a_score'] > $ar['cups_bracket_w1_15b_score'] && $ar['cups_bracket_w1_15a_score'] != ""){$w15 = $ar['cups_bracket_team_29']; $l15 = $ar['cups_bracket_team_30'];}elseif ($ar['cups_bracket_w1_15a_score'] == 0 && $ar['cups_bracket_w1_15b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w15 = $ar['cups_bracket_team_30']; $l15 = $ar['cups_bracket_team_29'];}
	if ($ar['cups_bracket_w1_16a_score'] > $ar['cups_bracket_w1_16b_score'] && $ar['cups_bracket_w1_16a_score'] != ""){$w16 = $ar['cups_bracket_team_31']; $l16 = $ar['cups_bracket_team_32'];}elseif ($ar['cups_bracket_w1_16a_score'] == 0 && $ar['cups_bracket_w1_16b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w16 = $ar['cups_bracket_team_32']; $l16 = $ar['cups_bracket_team_31'];}
}
if ($ar['cups_bracket_w2_1a_score'] > $ar['cups_bracket_w2_1b_score'] && $ar['cups_bracket_w2_1a_score'] != ""){$w17 = $w1; $l17 = $w2;}elseif ($ar['cups_bracket_w2_1a_score'] == 0 && $ar['cups_bracket_w2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w17 = $w2; $l17 = $w1;}
if ($ar['cups_bracket_w2_2a_score'] > $ar['cups_bracket_w2_2b_score'] && $ar['cups_bracket_w2_2a_score'] != ""){$w18 = $w3; $l18 = $w4;}elseif ($ar['cups_bracket_w2_2a_score'] == 0 && $ar['cups_bracket_w2_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w18 = $w4; $l18 = $w3;}
if ($ar['cups_bracket_w2_3a_score'] > $ar['cups_bracket_w2_3b_score'] && $ar['cups_bracket_w2_3a_score'] != ""){$w19 = $w5; $l19 = $w6;}elseif ($ar['cups_bracket_w2_3a_score'] == 0 && $ar['cups_bracket_w2_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w19 = $w6; $l19 = $w5;}
if ($ar['cups_bracket_w2_4a_score'] > $ar['cups_bracket_w2_4b_score'] && $ar['cups_bracket_w2_4a_score'] != ""){$w20 = $w7; $l20 = $w8;}elseif ($ar['cups_bracket_w2_4a_score'] == 0 && $ar['cups_bracket_w2_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w20 = $w8; $l20 = $w7;}
if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
	if ($ar['cups_bracket_w2_5a_score'] > $ar['cups_bracket_w2_5b_score'] && $ar['cups_bracket_w2_5a_score'] != ""){$w21 = $w9; $l21 = $w10;}elseif ($ar['cups_bracket_w2_5b_score'] == 0 && $ar['cups_bracket_w2_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w21 = $w10; $l21 = $w9;}
	if ($ar['cups_bracket_w2_6a_score'] > $ar['cups_bracket_w2_6b_score'] && $ar['cups_bracket_w2_6a_score'] != ""){$w22 = $w11; $l22 = $w12;}elseif ($ar['cups_bracket_w2_6a_score'] == 0 && $ar['cups_bracket_w2_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w22 = $w12; $l22 = $w11;}
	if ($ar['cups_bracket_w2_7a_score'] > $ar['cups_bracket_w2_7b_score'] && $ar['cups_bracket_w2_7a_score'] != ""){$w23 = $w13; $l23 = $w14;}elseif ($ar['cups_bracket_w2_7a_score'] == 0 && $ar['cups_bracket_w2_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w23 = $w14; $l23 = $w13;}
	if ($ar['cups_bracket_w2_8a_score'] > $ar['cups_bracket_w2_8b_score'] && $ar['cups_bracket_w2_8a_score'] != ""){$w24 = $w15; $l24 = $w16;}elseif ($ar['cups_bracket_w2_8a_score'] == 0 && $ar['cups_bracket_w2_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w24 = $w16; $l24 = $w15;}
}
if ($ar['cups_bracket_w3_1a_score'] > $ar['cups_bracket_w3_1b_score'] && $ar['cups_bracket_w3_1a_score'] != ""){$w25 = $w17; $l25 = $w18;}elseif ($ar['cups_bracket_w3_1a_score'] == 0 && $ar['cups_bracket_w3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w25 = $w18; $l25 = $w17;}
if ($ar['cups_bracket_w3_2a_score'] > $ar['cups_bracket_w3_2b_score'] && $ar['cups_bracket_w3_2a_score'] != ""){$w26 = $w19; $l26 = $w20;}elseif ($ar['cups_bracket_w3_2a_score'] == 0 && $ar['cups_bracket_w3_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w26 = $w20; $l26 = $w19;}
if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
	if ($ar['cups_bracket_w3_3a_score'] > $ar['cups_bracket_w3_3b_score'] && $ar['cups_bracket_w3_3a_score'] != ""){$w27 = $w21; $l27 = $w22;}elseif ($ar['cups_bracket_w3_3a_score'] == 0 && $ar['cups_bracket_w3_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w27 = $w22; $l27 = $w21;}
	if ($ar['cups_bracket_w3_4a_score'] > $ar['cups_bracket_w3_4b_score'] && $ar['cups_bracket_w3_4a_score'] != ""){$w28 = $w23; $l28 = $w24;}elseif ($ar['cups_bracket_w3_4a_score'] == 0 && $ar['cups_bracket_w3_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w28 = $w24; $l28 = $w23;}
}
if ($ar['cups_bracket_w4_1a_score'] > $ar['cups_bracket_w4_1b_score'] && $ar['cups_bracket_w4_1a_score'] != ""){$w29 = $w25; $l29 = $w26; if ($cups_bracket_cup_type == "16se"){$champion = $w25; $second = $w26;}}elseif ($ar['cups_bracket_w4_1a_score'] == 0 && $ar['cups_bracket_w4_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w29 = $w26; $l29 = $w25;if ($cups_bracket_cup_type == "16se"){$champion = $w26; $second = $w25;}}

if ($ar['cups_bracket_w3_1a_score'] > $ar['cups_bracket_w3_1b_score'] && $ar['cups_bracket_w3_1a_score'] != ""){$w25 = $w17; $l25 = $w18; if ($cups_bracket_cup_type == "8se"){$champion = $w17; $second = $w18;}}elseif ($ar['cups_bracket_w3_1a_score'] == 0 && $ar['cups_bracket_w3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w25 = $w18; $l25 = $w17;if ($cups_bracket_cup_type == "8se"){$champion = $w18; $second = $w17;}}

if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
	if ($ar['cups_bracket_w4_2a_score'] > $ar['cups_bracket_w4_2b_score'] && $ar['cups_bracket_w4_2a_score'] != ""){$w30 = $w27; $l30 = $w28;}elseif ($ar['cups_bracket_w4_2a_score'] == 0 && $ar['cups_bracket_w4_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w30 = $w28; $l30 = $w27;}

	if ($ar['cups_bracket_w5_1a_score'] > $ar['cups_bracket_w5_1b_score'] && $ar['cups_bracket_w5_1a_score'] != ""){$w31 = $w29; $l31 = $w30; if ($cups_bracket_cup_type == "32se"){$champion = $w29; $second = $w30;}}elseif ($ar['cups_bracket_w5_1a_score'] == 0 && $ar['cups_bracket_w5_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w31 = $w30; $l31 = $w29; if ($cups_bracket_cup_type == "32se"){$champion = $w30; $second = $w29;}}
}
// Rozhozeni teamu tak aby se nepotkali
if ($ar['cups_bracket_cup_version'] == 2){
	$lt1 = $l1; $lt2 = $l2; $lt3 = $l3; $lt4 = $l4; $lt5 = $l5; $lt6 = $l6; $lt7 = $l7; $lt8 = $l8; $lt9 = $l9;
	$lt10 = $l10; $lt11 = $l11; $lt12 = $l12; $lt13 = $l13; $lt14 = $l14; $lt15 = $l15; $lt16 = $l16;
	$lt17 = $l17; $lt18 = $l18; $lt19 = $l19; $lt20 = $l20; $lt21 = $l21; $lt22 = $l22; $lt23 = $l23;
	$lt24 = $l24; $lt25 = $l25;	$lt26 = $l26; $lt27 = $l27; $lt28 = $l28; $lt29 = $l29; $lt30 = $l30; $lt31 = $l31;
	if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
		$l17 = $lt18;
		$l18 = $lt17;
		$l19 = $lt20;
		$l20 = $lt19;
		
		$l25 = $lt26;
		$l26 = $lt25;
	}
	if ($cups_bracket_cup_type == "32de"){
		$l21 = $lt22;
		$l22 = $lt21;
		$l23 = $lt24;
		$l24 = $lt23;
		
		$l27 = $lt28;
		$l28 = $lt27;
		
		$l29 = $lt30;
		$l30 = $lt29;
	}
}

/* Rozhozeni teamu tak aby se nepotkali */
if ($ar['cups_bracket_cup_version'] == 3){
	$lt1 = $l1; $lt2 = $l2; $lt3 = $l3; $lt4 = $l4; $lt5 = $l5; $lt6 = $l6; $lt7 = $l7; $lt8 = $l8; $lt9 = $l9;
	$lt10 = $l10; $lt11 = $l11; $lt12 = $l12; $lt13 = $l13; $lt14 = $l14; $lt15 = $l15; $lt16 = $l16;
	$lt17 = $l17; $lt18 = $l18; $lt19 = $l19; $lt20 = $l20; $lt21 = $l21; $lt22 = $l22; $lt23 = $l23;
	$lt24 = $l24; $lt25 = $l25;	$lt26 = $l26; $lt27 = $l27; $lt28 = $l28; $lt29 = $l29; $lt30 = $l30; $lt31 = $l31;
	if ($cups_bracket_cup_type == "16de"){
		$l17 = $lt20;
		$l18 = $lt19;
		$l19 = $lt18;
		$l20 = $lt17;
		
		$l25 = $lt26;
		$l26 = $lt25;
	}
	if ($cups_bracket_cup_type == "32de"){
		$l17 = $lt24;
		$l18 = $lt23;
		$l19 = $lt22;
		$l20 = $lt21;
		$l21 = $lt20;
		$l22 = $lt19;
		$l23 = $lt18;
		$l24 = $lt17;
		
		$l27 = $lt28;
		$l28 = $lt27;
		
		$l29 = $lt30;
		$l30 = $lt29;
	}
}
// Looser Bracket
if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l1_1a_score'] > $ar['cups_bracket_l1_1b_score'] && $ar['cups_bracket_l1_1a_score'] != ""){$lb1 = $l1;}elseif ($ar['cups_bracket_l1_1a_score'] == 0 && $ar['cups_bracket_l1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb1 = $l2;}
		if ($ar['cups_bracket_l1_2a_score'] > $ar['cups_bracket_l1_2b_score'] && $ar['cups_bracket_l1_2a_score'] != ""){$lb2 = $l3;}elseif ($ar['cups_bracket_l1_2a_score'] == 0 && $ar['cups_bracket_l1_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb2 = $l4;}
		if ($ar['cups_bracket_l1_3a_score'] > $ar['cups_bracket_l1_3b_score'] && $ar['cups_bracket_l1_3a_score'] != ""){$lb3 = $l5;}elseif ($ar['cups_bracket_l1_3a_score'] == 0 && $ar['cups_bracket_l1_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb3 = $l6;}
		if ($ar['cups_bracket_l1_4a_score'] > $ar['cups_bracket_l1_4b_score'] && $ar['cups_bracket_l1_4a_score'] != ""){$lb4 = $l7;}elseif ($ar['cups_bracket_l1_4a_score'] == 0 && $ar['cups_bracket_l1_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb4 = $l8;}
		$l1_1a_team[1] = $l1;
		$l1_2a_team[1] = $l3;
		$l1_3a_team[1] = $l5;
		$l1_4a_team[1] = $l7;
		$l1_1b_team[1] = $l2;
		$l1_2b_team[1] = $l4;
		$l1_3b_team[1] = $l6;
		$l1_4b_team[1] = $l8;
	} else {
		$l1_1a_team = explode("###", $ar['cups_bracket_l1_1a_score']);
		$l1_2a_team = explode("###", $ar['cups_bracket_l1_2a_score']);
		$l1_3a_team = explode("###", $ar['cups_bracket_l1_3a_score']);
		$l1_4a_team = explode("###", $ar['cups_bracket_l1_4a_score']);
		$l1_1b_team = explode("###", $ar['cups_bracket_l1_1b_score']);
		$l1_2b_team = explode("###", $ar['cups_bracket_l1_2b_score']);
		$l1_3b_team = explode("###", $ar['cups_bracket_l1_3b_score']);
		$l1_4b_team = explode("###", $ar['cups_bracket_l1_4b_score']);
		if ($l1_1a_team[0] > $l1_1b_team[0] && $l1_1a_team[0] != ""){$lb1 = $l1_1a_team[1];}elseif ($l1_1b_team[0] != ""){$lb1 = $l1_1b_team[1];}
		if ($l1_2a_team[0] > $l1_2b_team[0] && $l1_2a_team[0] != ""){$lb2 = $l1_2a_team[1];}elseif ($l1_2b_team[0] != ""){$lb2 = $l1_2b_team[1];}
		if ($l1_3a_team[0] > $l1_3b_team[0] && $l1_3a_team[0] != ""){$lb3 = $l1_3a_team[1];}elseif ($l1_3b_team[0] != ""){$lb3 = $l1_3b_team[1];}
		if ($l1_4a_team[0] > $l1_4b_team[0] && $l1_4a_team[0] != ""){$lb4 = $l1_4a_team[1];}elseif ($l1_4b_team[0] != ""){$lb4 = $l1_4b_team[1];}
	}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l1_5a_score'] > $ar['cups_bracket_l1_5b_score'] && $ar['cups_bracket_l1_5a_score'] != ""){$lb5 = $l9;}elseif ($ar['cups_bracket_l1_5a_score'] == 0 && $ar['cups_bracket_l1_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb5 = $l10;}
		if ($ar['cups_bracket_l1_6a_score'] > $ar['cups_bracket_l1_6b_score'] && $ar['cups_bracket_l1_6a_score'] != ""){$lb6 = $l11;}elseif ($ar['cups_bracket_l1_6a_score'] == 0 && $ar['cups_bracket_l1_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb6 = $l12;}
		if ($ar['cups_bracket_l1_7a_score'] > $ar['cups_bracket_l1_7b_score'] && $ar['cups_bracket_l1_7a_score'] != ""){$lb7 = $l13;}elseif ($ar['cups_bracket_l1_7a_score'] == 0 && $ar['cups_bracket_l1_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb7 = $l14;}
		if ($ar['cups_bracket_l1_8a_score'] > $ar['cups_bracket_l1_8b_score'] && $ar['cups_bracket_l1_8a_score'] != ""){$lb8 = $l15;}elseif ($ar['cups_bracket_l1_8a_score'] == 0 && $ar['cups_bracket_l1_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb8 = $l16;}
		$l1_5a_team[1] = $l9;
		$l1_6a_team[1] = $l11;
		$l1_7a_team[1] = $l13;
		$l1_8a_team[1] = $l15;
		$l1_5b_team[1] = $l10;
		$l1_6b_team[1] = $l12;
		$l1_7b_team[1] = $l14;
		$l1_8b_team[1] = $l16;
	} else {
		$l1_5a_team = explode("###", $ar['cups_bracket_l1_5a_score']);
		$l1_6a_team = explode("###", $ar['cups_bracket_l1_6a_score']);
		$l1_7a_team = explode("###", $ar['cups_bracket_l1_7a_score']);
		$l1_8a_team = explode("###", $ar['cups_bracket_l1_8a_score']);
		$l1_5b_team = explode("###", $ar['cups_bracket_l1_5b_score']);
		$l1_6b_team = explode("###", $ar['cups_bracket_l1_6b_score']);
		$l1_7b_team = explode("###", $ar['cups_bracket_l1_7b_score']);
		$l1_8b_team = explode("###", $ar['cups_bracket_l1_8b_score']);
		if ($l1_5a_team[0] > $l1_5b_team[0] && $l1_5a_team[0] != ""){$lb5 = $l1_5a_team[1];}elseif ($l1_5b_team[0] != ""){$lb5 = $l1_5b_team[1];}
		if ($l1_6a_team[0] > $l1_6b_team[0] && $l1_6a_team[0] != ""){$lb6 = $l1_6a_team[1];}elseif ($l1_6b_team[0] != ""){$lb6 = $l1_6b_team[1];}
		if ($l1_7a_team[0] > $l1_7b_team[0] && $l1_7a_team[0] != ""){$lb7 = $l1_7a_team[1];}elseif ($l1_7b_team[0] != ""){$lb7 = $l1_7b_team[1];}
		if ($l1_8a_team[0] > $l1_8b_team[0] && $l1_8a_team[0] != ""){$lb8 = $l1_8a_team[1];}elseif ($l1_8b_team[0] != ""){$lb8 = $l1_8b_team[1];}
	}
}
if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l2_1a_score'] > $ar['cups_bracket_l2_1b_score'] && $ar['cups_bracket_l2_1a_score'] != ""){$lb9 = $lb1;}elseif ($ar['cups_bracket_l2_1a_score'] == 0 && $ar['cups_bracket_l2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb9 = $l17;}
		if ($ar['cups_bracket_l2_2a_score'] > $ar['cups_bracket_l2_2b_score'] && $ar['cups_bracket_l2_2a_score'] != ""){$lb10 = $lb2;}elseif ($ar['cups_bracket_l2_2a_score'] == 0 && $ar['cups_bracket_l2_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb10 = $l18;}
		if ($ar['cups_bracket_l2_3a_score'] > $ar['cups_bracket_l2_3b_score'] && $ar['cups_bracket_l2_3a_score'] != ""){$lb11 = $lb3;}elseif ($ar['cups_bracket_l2_3a_score'] == 0 && $ar['cups_bracket_l2_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb11 = $l19;}
		if ($ar['cups_bracket_l2_4a_score'] > $ar['cups_bracket_l2_4b_score'] && $ar['cups_bracket_l2_4a_score'] != ""){$lb12 = $lb4;}elseif ($ar['cups_bracket_l2_4a_score'] == 0 && $ar['cups_bracket_l2_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb12 = $l20;}
	} else {
		$l2_1a_team = explode("###", $ar['cups_bracket_l2_1a_score']);
		$l2_2a_team = explode("###", $ar['cups_bracket_l2_2a_score']);
		$l2_3a_team = explode("###", $ar['cups_bracket_l2_3a_score']);
		$l2_4a_team = explode("###", $ar['cups_bracket_l2_4a_score']);
		$l2_1b_team = explode("###", $ar['cups_bracket_l2_1b_score']);
		$l2_2b_team = explode("###", $ar['cups_bracket_l2_2b_score']);
		$l2_3b_team = explode("###", $ar['cups_bracket_l2_3b_score']);
		$l2_4b_team = explode("###", $ar['cups_bracket_l2_4b_score']);
		if ($l2_1a_team[0] > $l2_1b_team[0] && $l2_1a_team[0] != ""){$lb9 = $l2_1a_team[1];}elseif ($l2_1b_team[0] != ""){$lb9 = $l2_1b_team[1];}
		if ($l2_2a_team[0] > $l2_2b_team[0] && $l2_2a_team[0] != ""){$lb10 = $l2_2a_team[1];}elseif ($l2_2b_team[0] != ""){$lb10 = $l2_2b_team[1];}
		if ($l2_3a_team[0] > $l2_3b_team[0] && $l2_3a_team[0] != ""){$lb11 = $l2_3a_team[1];}elseif ($l2_3b_team[0] != ""){$lb11 = $l2_3b_team[1];}
		if ($l2_4a_team[0] > $l2_4b_team[0] && $l2_4a_team[0] != ""){$lb12 = $l2_4a_team[1];}elseif ($l2_4b_team[0] != ""){$lb12 = $l2_4b_team[1];}
	}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l2_5a_score'] > $ar['cups_bracket_l2_5b_score'] && $ar['cups_bracket_l2_5a_score'] != ""){$lb13 = $lb5;}elseif ($ar['cups_bracket_l2_5a_score'] == 0 && $ar['cups_bracket_l2_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb13 = $l21;}
		if ($ar['cups_bracket_l2_6a_score'] > $ar['cups_bracket_l2_6b_score'] && $ar['cups_bracket_l2_6a_score'] != ""){$lb14 = $lb6;}elseif ($ar['cups_bracket_l2_6a_score'] == 0 && $ar['cups_bracket_l2_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb14 = $l22;}
		if ($ar['cups_bracket_l2_7a_score'] > $ar['cups_bracket_l2_7b_score'] && $ar['cups_bracket_l2_7a_score'] != ""){$lb15 = $lb7;}elseif ($ar['cups_bracket_l2_7a_score'] == 0 && $ar['cups_bracket_l2_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb15 = $l23;}
		if ($ar['cups_bracket_l2_8a_score'] > $ar['cups_bracket_l2_8b_score'] && $ar['cups_bracket_l2_8a_score'] != ""){$lb16 = $lb8;}elseif ($ar['cups_bracket_l2_8a_score'] == 0 && $ar['cups_bracket_l2_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb16 = $l24;}
	} else {
		$l2_5a_team = explode("###", $ar['cups_bracket_l2_5a_score']);
		$l2_6a_team = explode("###", $ar['cups_bracket_l2_6a_score']);
		$l2_7a_team = explode("###", $ar['cups_bracket_l2_7a_score']);
		$l2_8a_team = explode("###", $ar['cups_bracket_l2_8a_score']);
		$l2_5b_team = explode("###", $ar['cups_bracket_l2_5b_score']);
		$l2_6b_team = explode("###", $ar['cups_bracket_l2_6b_score']);
		$l2_7b_team = explode("###", $ar['cups_bracket_l2_7b_score']);
		$l2_8b_team = explode("###", $ar['cups_bracket_l2_8b_score']);
		if ($l2_5a_team[0] > $l2_5b_team[0] && $l2_5a_team[0] != ""){$lb13 = $l2_5a_team[1];}elseif ($l2_5b_team[0] != ""){$lb13 = $l2_5b_team[1];}
		if ($l2_6a_team[0] > $l2_6b_team[0] && $l2_6a_team[0] != ""){$lb14 = $l2_6a_team[1];}elseif ($l2_6b_team[0] != ""){$lb14 = $l2_6b_team[1];}
		if ($l2_7a_team[0] > $l2_7b_team[0] && $l2_7a_team[0] != ""){$lb15 = $l2_7a_team[1];}elseif ($l2_7b_team[0] != ""){$lb15 = $l2_7b_team[1];}
		if ($l2_8a_team[0] > $l2_8b_team[0] && $l2_8a_team[0] != ""){$lb16 = $l2_8a_team[1];}elseif ($l2_8b_team[0] != ""){$lb16 = $l2_8b_team[1];}
	}
}
// Ctvrte misto 8de
if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l3_1a_score'] > $ar['cups_bracket_l3_1b_score'] && $ar['cups_bracket_l3_1a_score'] != ""){$lb17 = $lb9;$fourth = $lb10;}elseif ($ar['cups_bracket_l3_1a_score'] == 0 && $ar['cups_bracket_l3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb17 = $lb10;$fourth = $lb9;}
		if ($ar['cups_bracket_l3_2a_score'] > $ar['cups_bracket_l3_2b_score'] && $ar['cups_bracket_l3_2a_score'] != ""){$lb18 = $lb11;}elseif ($ar['cups_bracket_l3_2a_score'] == 0 && $ar['cups_bracket_l3_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb18 = $lb12;}
	} else {
		$l3_1a_team = explode("###", $ar['cups_bracket_l3_1a_score']);
		$l3_2a_team = explode("###", $ar['cups_bracket_l3_2a_score']);
		$l3_1b_team = explode("###", $ar['cups_bracket_l3_1b_score']);
		$l3_2b_team = explode("###", $ar['cups_bracket_l3_2b_score']);
		if ($l3_1a_team[0] > $l3_1b_team[0] && $l3_1a_team[0] != ""){$lb17 = $lb9;}elseif ($l3_1b_team[0] != ""){$lb17 = $lb10;}
		if ($l3_2a_team[0] > $l3_2b_team[0] && $l3_2a_team[0] != ""){$lb18 = $lb11;}elseif ($l3_2b_team[0] != ""){$lb18 = $lb12;}
	}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l3_3a_score'] > $ar['cups_bracket_l3_3b_score'] && $ar['cups_bracket_l3_3a_score'] != ""){$lb19 = $lb13;}elseif ($ar['cups_bracket_l3_3a_score'] == 0 && $ar['cups_bracket_l3_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb19 = $lb14;}
		if ($ar['cups_bracket_l3_4a_score'] > $ar['cups_bracket_l3_4b_score'] && $ar['cups_bracket_l3_4a_score'] != ""){$lb20 = $lb15;}elseif ($ar['cups_bracket_l3_4a_score'] == 0 && $ar['cups_bracket_l3_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb20 = $lb16;}
	} else {
		$l3_3a_team = explode("###", $ar['cups_bracket_l3_3a_score']);
		$l3_4a_team = explode("###", $ar['cups_bracket_l3_4a_score']);
		$l3_3b_team = explode("###", $ar['cups_bracket_l3_3b_score']);
		$l3_4b_team = explode("###", $ar['cups_bracket_l3_4b_score']);
		if ($l3_3a_team[0] > $l3_3b_team[0] && $l3_1a_team[0] != ""){$lb19 = $lb13;}elseif ($l3_3b_team[0] != ""){$lb19 = $lb14;}
		if ($l3_4a_team[0] > $l3_4b_team[0] && $l3_2a_team[0] != ""){$lb20 = $lb15;}elseif ($l3_4b_team[0] != ""){$lb20 = $lb16;}
	}
}
// Treti misto 8de
if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l4_1a_score'] > $ar['cups_bracket_l4_1b_score'] && $ar['cups_bracket_l4_1a_score'] != ""){$lb21 = $lb17; $third = $l25;}elseif ($ar['cups_bracket_l4_1a_score'] == 0 && $ar['cups_bracket_l4_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb21 = $l25; $third = $lb17;}
		if ($ar['cups_bracket_l4_2a_score'] > $ar['cups_bracket_l4_2b_score'] && $ar['cups_bracket_l4_2a_score'] != ""){$lb22 = $lb18;}elseif ($ar['cups_bracket_l4_2a_score'] == 0 && $ar['cups_bracket_l4_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb22 = $l26;}
	} else {
		$l4_1a_team = explode("###", $ar['cups_bracket_l4_1a_score']);
		$l4_2a_team = explode("###", $ar['cups_bracket_l4_2a_score']);
		$l4_1b_team = explode("###", $ar['cups_bracket_l4_1b_score']);
		$l4_2b_team = explode("###", $ar['cups_bracket_l4_2b_score']);
		if ($l4_1a_team[0] > $l4_1b_team[0] && $l4_1a_team[0] != ""){$lb21 = $l4_1a_team[1];}elseif ($l4_1b_team[0] != ""){$lb21 = $l4_1b_team[1];}
		if ($l4_2a_team[0] > $l4_2b_team[0] && $l4_2a_team[0] != ""){$lb22 = $l4_2a_team[1];}elseif ($l4_2b_team[0] != ""){$lb22 = $l4_2b_team[1];}
	}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l4_3a_score'] > $ar['cups_bracket_l4_3b_score'] && $ar['cups_bracket_l4_3a_score'] != ""){$lb23 = $lb19;}elseif ($ar['cups_bracket_l4_3a_score'] == 0 && $ar['cups_bracket_l4_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb23 = $l27;}
		if ($ar['cups_bracket_l4_4a_score'] > $ar['cups_bracket_l4_4b_score'] && $ar['cups_bracket_l4_4a_score'] != ""){$lb24 = $lb20;}elseif ($ar['cups_bracket_l4_4a_score'] == 0 && $ar['cups_bracket_l4_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb24 = $l28;}
	} else {
		$l4_3a_team = explode("###", $ar['cups_bracket_l4_3a_score']);
		$l4_4a_team = explode("###", $ar['cups_bracket_l4_4a_score']);
		$l4_3b_team = explode("###", $ar['cups_bracket_l4_3b_score']);
		$l4_4b_team = explode("###", $ar['cups_bracket_l4_4b_score']);
		if ($l4_3a_team[0] > $l4_3b_team[0] && $l4_3a_team[0] != ""){$lb23 = $lb19;}elseif ($l4_3b_team[0] != ""){$lb23 = $l4_3b_team[1];}
		if ($l4_4a_team[0] > $l4_4b_team[0] && $l4_4a_team[0] != ""){$lb24 = $lb20;}elseif ($l4_4b_team[0] != ""){$lb24 = $l4_4b_team[1];}
	}
}
// Ctvrte misto 16de
if ($cups_bracket_cup_type == "16de"){
	if ($ar['cups_bracket_l5_1a_score'] > $ar['cups_bracket_l5_1b_score'] && $ar['cups_bracket_l5_1a_score'] != ""){$lb25 = $lb21; $fourth = $lb22;}elseif ($ar['cups_bracket_l5_1a_score'] == 0 && $ar['cups_bracket_l5_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb25 = $lb22; $fourth = $lb21;}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_l5_1a_score'] > $ar['cups_bracket_l5_1b_score'] && $ar['cups_bracket_l5_1a_score'] != ""){$lb25 = $lb21;}elseif ($ar['cups_bracket_l5_1a_score'] == 0 && $ar['cups_bracket_l5_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb25 = $lb22;}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_l5_2a_score'] > $ar['cups_bracket_l5_2b_score'] && $ar['cups_bracket_l5_2a_score'] != ""){$lb26 = $lb23;}elseif ($ar['cups_bracket_l5_2a_score'] == 0 && $ar['cups_bracket_l5_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb26 = $lb24;}
}
// Treti misto 16de
if ($cups_bracket_cup_type == "16de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l6_1a_score'] > $ar['cups_bracket_l6_1b_score'] && $ar['cups_bracket_l6_1a_score'] != ""){$lb27 = $l29; $third = $lb25;}elseif ($ar['cups_bracket_l6_1a_score'] == 0 && $ar['cups_bracket_l6_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb27 = $lb25; $third = $l29;}
	} else {
		$l6_1a_team = explode("###", $ar['cups_bracket_l6_1a_score']);
		$l6_1b_team = explode("###", $ar['cups_bracket_l6_1b_score']);
		if ($l6_1a_team[0] > $l6_1b_team[0] && $l6_1a_team[0] != ""){$lb27 = $l6_1a_team[1]; $third = $lb25;}elseif ($l6_1b_team[0] != ""){$lb27 = $lb25; $third = $l6_1b_team[0];}
	}
}
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l6_1a_score'] > $ar['cups_bracket_l6_1b_score'] && $ar['cups_bracket_l6_1a_score'] != ""){$lb27 = $l29;}elseif ($ar['cups_bracket_l6_1a_score'] == 0 && $ar['cups_bracket_l6_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb27 = $lb25;}
	} else {
		$l6_1a_team = explode("###", $ar['cups_bracket_l6_1a_score']);
		$l6_1b_team = explode("###", $ar['cups_bracket_l6_1b_score']);
		if ($l6_1a_team[0] > $l6_1b_team[0] && $l6_1a_team[0] != ""){$lb27 = $l6_1a_team[1];}elseif ($l6_1b_team[0] != ""){$lb27 = $lb25;}
	}
}

if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_de_manual'] == 0){
		if ($ar['cups_bracket_l6_2a_score'] > $ar['cups_bracket_l6_2b_score'] && $ar['cups_bracket_l6_2a_score'] != ""){$lb28 = $l30;}elseif ($ar['cups_bracket_l6_2a_score'] == 0 && $ar['cups_bracket_l6_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb28 = $lb26;}
	} else {
		$l6_2a_team = explode("###", $ar['cups_bracket_l6_2a_score']);
		$l6_2b_team = explode("###", $ar['cups_bracket_l6_2b_score']);
		if ($l6_2a_team[0] > $l6_2b_team[0] && $l6_2a_team[0] != ""){$lb27 = $l6_2a_team[1];}elseif ($l6_2b_team[0] != ""){$lb28 = $lb26;}
	}
}
// Ctvrte misto 32de
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_l7_1a_score'] > $ar['cups_bracket_l7_1b_score'] && $ar['cups_bracket_l7_1a_score'] != ""){$lb29 = $lb27; $fourth = $lb28;}elseif ($ar['cups_bracket_l7_1a_score'] == 0 && $ar['cups_bracket_l7_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb29 = $lb28; $fourth = $lb27;}
}
// Treti misto 32de
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_l8_1a_score'] > $ar['cups_bracket_l8_1b_score'] && $ar['cups_bracket_l8_1a_score'] != ""){$lb30 = $l31; $third = $lb29;}elseif ($ar['cups_bracket_l8_1a_score'] == 0 && $ar['cups_bracket_l8_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb30 = $lb29; $third = $l31;}
}
// 3-4 misto 8se
if ($cups_bracket_cup_type == "8se"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$third = $l17; $fourth = $l18;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$third = $l18; $fourth = $l17;}
}
// Finale 8de
if ($cups_bracket_cup_type == "8de"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$fwin = $w25; $flooser = $lb21; $champion = $w25; $second = $lb21;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$fwin = $lb21; $flooser = $w25; if ($ar['cups_bracket_cup_type_end'] == 1){$champion = $lb21; $second = $w25;}}
	if ($ar['cups_bracket_f2_1a_score'] > $ar['cups_bracket_f2_1b_score'] && $ar['cups_bracket_f2_1a_score'] != "" && $ar['cups_bracket_cup_type_end'] == 2){$champion = $fwin; $second = $flooser;}elseif ($ar['cups_bracket_f2_1a_score'] == 0 && $ar['cups_bracket_f2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$champion = $fwin; $second = $flooser;}
}
// 3-4 misto 16se
if ($cups_bracket_cup_type == "16se"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$third = $l25; $fourth = $l26;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$third = $l26; $fourth = $l25;}
}
// Finale 16de
if ($cups_bracket_cup_type == "16de"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$fwin = $w29; $flooser = $lb27; $champion = $w29; $second = $lb27;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$fwin = $lb27; $flooser = $w29; if ($ar['cups_bracket_cup_type_end'] == 1){$champion = $lb27; $second = $w29;}}
	if ($ar['cups_bracket_f2_1a_score'] > $ar['cups_bracket_f2_1b_score'] && $ar['cups_bracket_f2_1a_score'] != "" && $ar['cups_bracket_cup_type_end'] == 2){$champion = $fwin; $second = $flooser;}elseif ($ar['cups_bracket_f2_1a_score'] == 0 && $ar['cups_bracket_f2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$champion = $fwin; $second = $flooser;}
}
// 3-4 misto 32se
if ($cups_bracket_cup_type == "32se"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$third = $l29; $fourth = $l30;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$third = $l30; $fourth = $l29;}
}
// Finale 32de
if ($cups_bracket_cup_type == "32de"){
	if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$fwin = $w31; $flooser = $lb30; $champion = $w31; $second = $lb30;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$fwin = $lb30; $flooser = $w31; if ($ar['cups_bracket_cup_type_end'] == 1){$champion = $lb30; $second = $w31;}}
	if ($ar['cups_bracket_f2_1a_score'] > $ar['cups_bracket_f2_1b_score'] && $ar['cups_bracket_f2_1a_score'] != "" && $ar['cups_bracket_cup_type_end'] == 2){$champion = $fwin; $second = $flooser;}elseif ($ar['cups_bracket_f2_1a_score'] == 0 && $ar['cups_bracket_f2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$champion = $fwin; $second = $flooser;}
}
echo "<table border=\"0\" bordercolor=\"#000000\" cellpadding=\"\" cellspacing=\"0\">\n";
echo "	<tr>\n";
echo "		<td width=\"50\">&nbsp;</td>\n";
echo "			<td align=\"left\">\n";
echo "				<table border=\"0\" bordercolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\">\n";
echo "					<tbody>\n";
							if ($ar['cups_bracket_cup_int_or_ext'] == 1){
								echo "	<tr>\n";
								echo "		<td><a href=\"http://www.esuba.net/cup/\" target=\"_blank\"><img src=\"images/cup_logo.gif\" alt=\"\" width=\"208\" height=\"141\" border=\"0\"></a></td>\n";
								echo "	</tr>\n";
							}
echo "					<tr>\n";
echo "						<td class=\"cup_nadpis\">".stripslashes($ar['cups_bracket_cup_name'])."</td>\n";
echo "					</tr>\n";
echo "					<tr>\n";
echo "						<td class=\"cup_info\">".stripslashes($ar['cups_bracket_cup_comment'])."</td>\n";
echo "					</tr>\n";
echo "				</table>\n";
echo "				<table border=\"0\" bordercolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\">\n";
echo "					<tr>\n";
echo "						<td class=\"cups_text\" id=\"cup_wb_nadpis\" colspan=\"6\">WINNERS BRACKET</td>\n";
echo "					</tr>\n";
echo "					<tr id=\"cup_rounds\">\n";
echo "						<td class=\"cups_text\" valign=\"top\"><strong>WB Round 1</strong></td>\n";
echo "						<td class=\"cups_text\" valign=\"top\"><strong>WB Round 2</strong></td>\n";
echo "						<td class=\"cups_text\" valign=\"top\"><strong>WB Round 3</strong></td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "8se" ){ echo "<strong>WB Winner</strong>"; } else {echo "<strong>WB Round 4</strong>";} echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" ){ echo "<strong>WB Winner</strong>"; }elseif ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se" ){echo "<strong>WB Round 5</strong>";} echo "&nbsp;</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se" ){ echo "<strong>WB Winner</strong>"; } echo "&nbsp;</td>\n";
echo "					</tr>\n";
echo "					<tr id=\"cup_rounds\">\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($wb_map[0] != ""){ echo "<img src=\"".$url_clan_maps.$wb_map[0]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($wb_map[1] != ""){ echo "<img src=\"".$url_clan_maps.$wb_map[1]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($wb_map[2] != ""){ echo "<img src=\"".$url_clan_maps.$wb_map[2]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ if ($wb_map[3] != ""){ echo "<img src=\"".$url_clan_maps.$wb_map[3]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; }} echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ if ($wb_map[4] != ""){ echo "<img src=\"".$url_clan_maps.$wb_map[4]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; }} echo "</td>\n";
echo "						<td class=\"cups_text\" valign=\"top\">"; /* if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se\" ){ if ($wb_map[5] != ""){ echo "<img src=\"".echo $url_clan_maps.$wb_map[5]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\"> -->"; } } else {echo "&nbsp;";} */ echo "</td>\n";
echo "					</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td class=\"cups_text\" valign=\"top\">".stripslashes($ar['cups_bracket_wb1_info'])."</td>\n";
echo "		<td class=\"cups_text\" valign=\"top\">".stripslashes($ar['cups_bracket_wb2_info'])."</td>\n";
echo "		<td class=\"cups_text\" valign=\"top\">".stripslashes($ar['cups_bracket_wb3_info'])."</td>\n";
echo "		<td class=\"cups_text\" valign=\"top\">".stripslashes($ar['cups_bracket_wb4_info'])."</td>\n";
echo "		<td class=\"cups_text\" valign=\"top\">".stripslashes($ar['cups_bracket_wb5_info'])."</td>\n";
echo "		<td class=\"cups_text\" valign=\"top\">"; /* if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se\" ){echo stripslashes($ar['cups_bracket_wb6_info']); } else {echo "&nbsp;";} */ echo "</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"5\"><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_1a_score']."</span>&nbsp;".$ar['cups_bracket_team_01']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 1</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_1a_score']."</span>&nbsp;".$w1."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_1b_score']."</span>&nbsp;".$ar['cups_bracket_team_02']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 17</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_1a_score']."</span>&nbsp;".$w17."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_2a_score']."</span>&nbsp;".$ar['cups_bracket_team_03']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 2</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_1b_score']."</span>&nbsp;".$w2."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_2b_score']."</span>&nbsp;".$ar['cups_bracket_team_04']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 25</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w4_1a_score']."</span>&nbsp;".$w25."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_3a_score']."</span>&nbsp;".$ar['cups_bracket_team_05']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 3</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_2a_score']."</span>&nbsp;".$w3."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_3b_score']."</span>&nbsp;".$ar['cups_bracket_team_06']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">	&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 18</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_1b_score']."</span>&nbsp;".$w18."</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_4a_score']."</span>&nbsp;".$ar['cups_bracket_team_07']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 4</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_2b_score']."</span>&nbsp;".$w4."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_4b_score']."</span>&nbsp;".$ar['cups_bracket_team_08']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 29</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo $ar['cups_bracket_w5_1a_score']; } echo "</span>&nbsp;"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se"){ echo "<strong>".$w29."</strong>"; } else {echo $w29;} echo "</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_5a_score']."</span>&nbsp;".$ar['cups_bracket_team_09']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 5</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_3a_score']."</span>&nbsp;".$w5."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_5b_score']."</span>&nbsp;".$ar['cups_bracket_team_10']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 19</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_2a_score']."</span>&nbsp;".$w19."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_6a_score']."</span>&nbsp;".$ar['cups_bracket_team_11']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 6</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_3b_score']."</span>&nbsp;".$w6."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_6b_score']."</span>&nbsp;".$ar['cups_bracket_team_12']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 26</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w4_1b_score']."</span>&nbsp;".$w26."</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_7a_score']."</span>&nbsp;".$ar['cups_bracket_team_13']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 7</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_4a_score']."</span>&nbsp;".$w7."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_7b_score']."</span>&nbsp;".$ar['cups_bracket_team_14']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 20</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_2b_score']."</span>&nbsp;".$w20."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_8a_score']."</span>&nbsp;".$ar['cups_bracket_team_15']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 8</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_4b_score']."</span>&nbsp;".$w8."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_8b_score']."</span>&nbsp;".$ar['cups_bracket_team_16']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
}
if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 31</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><strong>".$w31."</strong>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_9a_score']."</span>&nbsp;".$ar['cups_bracket_team_17']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 9</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_5a_score']."</span>&nbsp;".$w9."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_9b_score']."</span>&nbsp;".$ar['cups_bracket_team_18']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 21</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_3a_score']."</span>&nbsp;".$w21."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_10a_score']."</span>&nbsp;".$ar['cups_bracket_team_19']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 10</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_5b_score']."</span>&nbsp;".$w10."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_10b_score']."</span>&nbsp;".$ar['cups_bracket_team_20']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 27</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w4_2a_score']."</span>&nbsp;".$w27."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_11a_score']."</span>&nbsp;".$ar['cups_bracket_team_21']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 11</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_6a_score']."</span>&nbsp;".$w11."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_11b_score']."</span>&nbsp;".$ar['cups_bracket_team_22']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 22</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_3b_score']."</span>&nbsp;".$w22."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_12a_score']."</span>&nbsp;".$ar['cups_bracket_team_23']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 12</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_6b_score']."</span>&nbsp;".$w12."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_12b_score']."</span>&nbsp;".$ar['cups_bracket_team_24']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 30</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w5_1b_score']."</span>&nbsp;".$w30."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_13a_score']."</span>&nbsp;".$ar['cups_bracket_team_25']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 13</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_7a_score']."</span>&nbsp;".$w13."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_13b_score']."</span>&nbsp;".$ar['cups_bracket_team_26']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 23</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_4a_score']."</span>&nbsp;".$w23."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_14a_score']."</span>&nbsp;".$ar['cups_bracket_team_27']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 14</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_7b_score']."</span>&nbsp;".$w14."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_14b_score']."</span>&nbsp;".$ar['cups_bracket_team_28']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 28</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w4_2b_score']."</span>&nbsp;".$w28."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_15a_score']."</span>&nbsp;".$ar['cups_bracket_team_29']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 15</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_8a_score']."</span>&nbsp;".$w15."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_15b_score']."</span>&nbsp;".$ar['cups_bracket_team_30']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">ID - 24</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w3_4b_score']."</span>&nbsp;".$w24."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_16a_score']."</span>&nbsp;".$ar['cups_bracket_team_31']."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">ID - 16</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w2_8b_score']."</span>&nbsp;".$w16."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_w1_16b_score']."</span>&nbsp;".$ar['cups_bracket_team_32']."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
}
echo "	</tbody>\n";
echo "</table>\n";
if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de" ){
echo "<table bordercolor=\"#000000\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n";
echo "	<tr>\n";
echo "		<td align=\"left\" colspan=\"9\"><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td id=\"cup_lb_nadpis\" colspan=\"9\">LOOSERS BRACKET</td>\n";
echo "	</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td class=\"cups_text\"><strong>LB Round 1</strong></td>\n";
echo "		<td class=\"cups_text\"><strong>LB Round 2</strong></td>\n";
echo "		<td class=\"cups_text\"><strong>LB Round 3</strong></td>\n";
echo "		<td class=\"cups_text\"><strong>LB Round 4</strong></td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "8de"){echo "<strong>LB Winner</strong>";} else { echo "<strong>LB Round 5</strong>";} echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "<strong>LB Round 6</strong>";} echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16de"){ echo "<strong>LB Winner</strong>";}elseif($cups_bracket_cup_type == "32de"){ echo "<strong>LB Round 7</strong>";} echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){echo "<strong>LB Round 8</strong>";} echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){echo "<strong>LB Winner</strong>";} echo "</td>\n";
echo "	</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[0] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[0]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[1] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[1]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[2] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[2]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[3] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[3]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[4] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[4]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[5] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[5]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($lb_map[6] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[6]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ if ($lb_map[7] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[7]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; }} echo "&nbsp;</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ if ($lb_map[8] != ""){ echo "<img src=\"".$url_clan_maps.$lb_map[8]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; }} echo "&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb1_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb2_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb3_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb4_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb5_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb6_info'])."</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_lb7_info'])."</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ echo stripslashes($ar['cups_bracket_lb8_info']); } echo "&nbsp;</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ echo stripslashes($ar['cups_bracket_lb9_info']); } echo "&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"9\"><br><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_1a_score = explode("###", $ar['cups_bracket_l1_1a_score']); echo $cups_bracket_l1_1a_score[0]."</span>&nbsp;".$l1_1a_team[1]."</td>\n";
echo "		<td class=\"cups_text\" colspan=8>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=8>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 1</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_1a_score = explode("###", $ar['cups_bracket_l2_1a_score']); echo $cups_bracket_l2_1a_score[0]."</span>&nbsp;".$lb1."</td>\n";
echo "		<td class=\"cups_text\" colspan=5>&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=7	>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_1b_score = explode("###", $ar['cups_bracket_l1_1b_score']); echo $cups_bracket_l1_1b_score[0]."</span>&nbsp;".$l1_1b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 9</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_1a_score']."</span>&nbsp;".$lb9."</td>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_1b_score = explode("###", $ar['cups_bracket_l2_1b_score']); echo $cups_bracket_l2_1b_score[0]."</span>&nbsp;".$l17."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_2a_score = explode("###", $ar['cups_bracket_l1_2a_score']); echo $cups_bracket_l1_2a_score[0]."</span>&nbsp;".$l1_2a_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 17</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_1a_score = explode("###", $ar['cups_bracket_l4_1a_score']); echo $cups_bracket_l4_1a_score[0]."</span>&nbsp;".$lb17."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"5\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo " id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l6_1a_score = explode("###", $ar['cups_bracket_l6_1a_score']); echo $cups_bracket_l6_1a_score[0]."</span>&nbsp;".$l29; } else {echo ">";} echo "</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 2</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_2a_score = explode("###", $ar['cups_bracket_l2_2a_score']); echo $cups_bracket_l2_2a_score[0]."</span>&nbsp;".$lb2."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo " id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_2b_score = explode("###", $ar['cups_bracket_l1_2b_score']); echo $cups_bracket_l1_2b_score[0]."</span>&nbsp;".$l1_2b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 10</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_1b_score']."</span>&nbsp;".$lb10."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 21</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l5_1a_score']."</span>&nbsp;".$lb21."</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l8_1a_score']."</span>&nbsp;".$l31;} echo "&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_2b_score = explode("###", $ar['cups_bracket_l2_2b_score']); echo $cups_bracket_l2_2b_score[0]."</span>&nbsp;".$l18."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "ID - LB 27"; } echo "</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cup_cell_team\">"; if ($cups_bracket_cup_type == "32de"){ echo "<span id=\"number\">".$ar['cups_bracket_l7_1a_score']."</span>"; } echo "&nbsp;"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se"){ echo "<strong>"; } echo $lb27; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se"){ echo "</strong>"; }} else {echo ">";} echo "</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo " id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_3a_score = explode("###", $ar['cups_bracket_l1_3a_score']); echo $cups_bracket_l1_3a_score[0]."</span>&nbsp;".$l1_3a_team[1]; } else {echo ">";} echo "</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_1b_score = explode("###", $ar['cups_bracket_l4_1b_score']); echo $cups_bracket_l4_1b_score[0]."</span>&nbsp;".$l25."</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "id=\"cell_line\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 3</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_3a_score = explode("###", $ar['cups_bracket_l2_3a_score']); echo $cups_bracket_l2_3a_score[0]."</span>&nbsp;".$lb3."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_3b_score = explode("###", $ar['cups_bracket_l1_3b_score']); echo $cups_bracket_l1_3b_score[0]."</span>&nbsp;".$l1_3b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 11</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_2a_score']."</span>&nbsp;".$lb11."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 25</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l6_1b_score']."</span>&nbsp;".$lb25."</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ echo "&nbsp;ID - LB 30"; } echo "</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "id=\"cup_cell_team\"><strong>".$lb30."</strong>"; } echo "&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_3b_score = explode("###", $ar['cups_bracket_l2_3b_score']); echo $cups_bracket_l2_3b_score[0]."</span>&nbsp;".$l19."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_4a_score = explode("###", $ar['cups_bracket_l1_4a_score']); echo $cups_bracket_l1_4a_score[0]."</span>&nbsp;".$l1_4a_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 18</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_2a_score = explode("###", $ar['cups_bracket_l4_2a_score']); echo $cups_bracket_l4_2a_score[0]."</span>&nbsp;".$lb18."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 4</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_4a_score = explode("###", $ar['cups_bracket_l2_4a_score']); echo $cups_bracket_l2_4a_score[0]."</span>&nbsp;".$lb4."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_4b_score = explode("###", $ar['cups_bracket_l1_4b_score']); echo $cups_bracket_l1_4b_score[0]."</span>&nbsp;".$l1_4b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 12</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_2b_score']."</span>&nbsp;".$lb12."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 22</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l5_1b_score']."</span>&nbsp;".$lb22."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_4b_score = explode("###", $ar['cups_bracket_l2_4b_score']); echo $cups_bracket_l2_4b_score[0]."</span>&nbsp;".$l20."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;"; if ($cups_bracket_cup_type == "32de"){ echo "ID - LB 29"; } echo "</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo " id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l8_1b_score']."</span>&nbsp;".$lb29;} else {echo "&nbsp;";} echo "</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo " id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_5a_score = explode("###", $ar['cups_bracket_l1_5a_score']); echo $cups_bracket_l1_5a_score[0]."</span>&nbsp;".$l1_5a_team[1];} else {echo ">";} echo "</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_2b_score = explode("###", $ar['cups_bracket_l4_2b_score']); echo $cups_bracket_l4_2b_score[0]."</span>&nbsp;".$l26."</td>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
}
if ($cups_bracket_cup_type == "32de"){
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=5>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 5</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_5a_score = explode("###", $ar['cups_bracket_l2_5a_score']); echo $cups_bracket_l2_5a_score[0]."</span>&nbsp;".$lb5."</td>\n";
echo "		<td class=\"cups_text\" colspan=4	>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=4>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_5b_score = explode("###", $ar['cups_bracket_l1_5b_score']); echo $cups_bracket_l1_5b_score[0]."</span>&nbsp;".$l1_5b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 13</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_3a_score']."</span>&nbsp;".$lb13."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_5b_score = explode("###", $ar['cups_bracket_l2_5b_score']); echo $cups_bracket_l2_5b_score[0]."</span>&nbsp;".$l21."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_6a_score = explode("###", $ar['cups_bracket_l1_6a_score']); echo $cups_bracket_l1_6a_score[0]."</span>&nbsp;".$l1_6a_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 19</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_3a_score = explode("###", $ar['cups_bracket_l4_3a_score']); echo $cups_bracket_l4_3a_score[0]."</span>&nbsp;".$lb19."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l6_2a_score = explode("###", $ar['cups_bracket_l6_2a_score']); echo $cups_bracket_l6_2a_score[0]."</span>&nbsp;".$l30."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 6</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_6a_score = explode("###", $ar['cups_bracket_l2_6a_score']); echo $cups_bracket_l2_6a_score[0]."</span>&nbsp;".$lb6."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_6b_score = explode("###", $ar['cups_bracket_l1_6b_score']); echo $cups_bracket_l1_6b_score[0]."</span>&nbsp;".$l1_6b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 14</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_3b_score']."</span>&nbsp;".$lb14."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 23</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l5_2a_score']."</span>&nbsp;".$lb23."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_6b_score = explode("###", $ar['cups_bracket_l2_6b_score']); echo $cups_bracket_l2_6b_score[0]."</span>&nbsp;".$l22."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 28</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l7_1b_score']."</span>&nbsp;".$lb28."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_7a_score = explode("###", $ar['cups_bracket_l1_7a_score']); echo $cups_bracket_l1_7a_score[0]."</span>&nbsp;".$l1_7a_team[1]."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_3b_score = explode("###", $ar['cups_bracket_l4_3b_score']); echo $cups_bracket_l4_3b_score[0]."</span>&nbsp;".$l27."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 7</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_7a_score = explode("###", $ar['cups_bracket_l2_7a_score']); echo $cups_bracket_l2_7a_score[0]."</span>&nbsp;".$lb7."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_7b_score = explode("###", $ar['cups_bracket_l1_7b_score']); echo $cups_bracket_l1_7b_score[0]."</span>&nbsp;".$l1_7b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 15</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_4a_score']."</span>&nbsp;".$lb15."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 26</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l6_2b_score']."</span>&nbsp;".$lb26."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_7b_score = explode("###", $ar['cups_bracket_l2_7b_score']); echo $cups_bracket_l2_7b_score[0]."</span>&nbsp;".$l23."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=2>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_8a_score = explode("###", $ar['cups_bracket_l1_8a_score']); echo $cups_bracket_l1_8a_score[0]."</span>&nbsp;".$l1_8a_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 20</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_4a_score = explode("###", $ar['cups_bracket_l4_4a_score']); echo $cups_bracket_l4_4a_score[0]."</span>&nbsp;".$lb20."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 8</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_8a_score = explode("###", $ar['cups_bracket_l2_8a_score']); echo $cups_bracket_l2_8a_score[0]."</span>&nbsp;".$lb8."</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l1_8b_score = explode("###", $ar['cups_bracket_l1_8b_score']); echo $cups_bracket_l1_8b_score[0]."</span>&nbsp;".$l1_8b_team[1]."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 16</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l3_4b_score']."</span>&nbsp;".$lb16."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;ID - LB 24</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_l5_2b_score']."</span>&nbsp;".$lb24."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l2_8b_score = explode("###", $ar['cups_bracket_l2_8b_score']); echo $cups_bracket_l2_8b_score[0]."</span>&nbsp;".$l24."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=3>&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">"; $cups_bracket_l4_4b_score = explode("###", $ar['cups_bracket_l4_4b_score']); echo $cups_bracket_l4_4b_score[0]."</span>&nbsp;".$l28."</td>\n";
echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"9\">&nbsp;<br><br><br></td>\n";
echo "	</tr>\n";
echo "</table>\n";
	}
}
echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bordercolor=\"#000000\">\n";
echo "	<tr>\n";
echo "		<td align=\"left\" colspan=\"5\"><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td align=\"left\"  id=\"cup_final_nadpis\" colspan=\"5\"><strong>"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "FINAL MATCH";} else {echo "3-4 PLACE MATCH";} echo "</td>\n";
echo "	</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td width=\"200\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">"; if ($fin_map[0] != ""){ echo "<img src=\"".$url_clan_maps.$fin_map[0]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; } echo "</td>\n";
echo "		<td class=\"cups_text\">\n";
				if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
					if ($fin_map[1] != ""){ echo "<img src=\"".$url_clan_maps.$fin_map[1]."\" alt=\"\" width=\"120\" height=\"90\" border=\"0\">"; }
				}
echo "		</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr id=\"cup_rounds\">\n";
echo "		<td width=\"200\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">".stripslashes($ar['cups_bracket_fi_info'])."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr id=\"cup_rounds\">\n";
echo "	<tr>\n";
echo "		<td align=\"left\" colspan=\"5\"><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td width=\"200\"><strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "Winners Bracket Winner:";} echo "</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_f1_1a_score']."</span>&nbsp;"; if ($cups_bracket_cup_type == "32de"){echo $w31;}elseif ($cups_bracket_cup_type == "32se"){echo $l29;}elseif ($cups_bracket_cup_type == "16de"){echo $w29;}elseif($cups_bracket_cup_type == "16se"){echo $l25;}elseif($cups_bracket_cup_type == "8de"){echo $w25;} else {echo $l17;} echo "</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\">"; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score']){ echo "<span id=\"number\">".$ar['cups_bracket_f2_1a_score']."</span>"; } echo " <strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo $fwin;}elseif ($cups_bracket_cup_type == "8se" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32se"){echo $third;} echo "</strong>&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\" id=\"cell_line\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "style=\"border-width: 1px; border-right-style: solid;\"";} echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td width=\"200\"><strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "Loosers Bracket Winner:";} echo "</td>\n";
echo "		<td class=\"cups_text\" id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_f1_1b_score']."</span>&nbsp;"; if ($cups_bracket_cup_type == "32de"){echo $lb30;}elseif ($cups_bracket_cup_type == "32se"){echo $l30;}elseif ($cups_bracket_cup_type == "16de"){echo $lb27;}elseif ($cups_bracket_cup_type == "16se"){echo $l26;}elseif($cups_bracket_cup_type == "8de"){echo $lb21;} else {echo $l18;} echo "</strong>&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "id=\"cup_cell_team\"><strong>".$champion."</strong>&nbsp;</td>";} else {echo "&nbsp;</td>";}
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "style=\"border-width: 1px; border-right-style: solid;\"";} echo ">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td>&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){ echo " id=\"cup_cell_team\"><span id=\"number\">".$ar['cups_bracket_f2_1b_score']."</span>&nbsp;".$flooser."</td>"; } else {echo "&nbsp;</td>";}
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"5\">&nbsp;<br><br></td>\n";
echo "	</tr>\n";
echo "</table>\n";
echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bordercolor=\"#000000\">\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" id=\"cup_result\" colspan=\"3\">CUP RESULT</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"3\"><br></td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" width=\"100\"><strong>1. Place</strong></td>\n";
echo "		<td class=\"cups_text\" id=\"cup_bedna\" width=\"200\"><strong id=\"first\">".$champion."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" width=\"100\"><strong>2. Place</strong></td>\n";
echo "		<td class=\"cups_text\" id=\"cup_bedna\" width=\"200\"><strong id=\"second\">".$second."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" width=\"100\"><strong>3. Place</strong></td>\n";
echo "		<td class=\"cups_text\" id=\"cup_bedna\" width=\"200\"><strong id=\"third\">".$third."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" width=\"100\"><strong>4. Place</strong></td>\n";
echo "		<td class=\"cups_text\" id=\"cup_bedna\" width=\"200\"><strong id=\"fourth\">".$fourth."</td>\n";
echo "		<td class=\"cups_text\">&nbsp;</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td class=\"cups_text\" colspan=\"3\"><br><br><br></td>\n";
echo "	</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</body>\n";
echo "</html>";