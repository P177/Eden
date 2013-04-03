<?php
/***********************************************************************************************************
*
*		 ZOBRAZENI CUPU
*
***********************************************************************************************************/
function ShowMain()	{
	
	global $db_cups_bracket;
	
	$res_bracket = mysql_query("SELECT cups_bracket_id, cups_bracket_user_use, cups_bracket_user_open, cups_bracket_cup_game, cups_bracket_cup_type, cups_bracket_publish, cups_bracket_cup_name FROM $db_cups_bracket ORDER BY cups_bracket_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CUPS_BRACKETS_CUPS."&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "	<td><img src=\"images/sys_manage.gif\" width=\"18\" border=\"0\" alt=\"\">"._CUPS_BRACKETS_ADD_CUP.":	<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=8se&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_8SE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=8de&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_8DE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=16se&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_16SE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=16de&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_16DE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=32se&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_32SE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=add&cup_type=32de&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_32DE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a\n";
	echo "		href=\"modul_cups.php?action=addmapimage&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_ADD_MAPIMAGE."</a></td>\n";
	echo "		<td align=\"left\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_GAME."</span></td>\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_TYPE."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_NAME."</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._NUMBERSUBTOPIC."</span></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$i=1;
	while($ar_bracket = mysql_fetch_array($res_bracket)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\"><a href=\"modul_cups.php?action=edit&amp;id=".$ar_bracket['cups_bracket_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>\n";
			  	 if (CheckPriv("groups_cups_del") == 1){
			  	 	if ($ar_bracket['cups_bracket_user_use'] == "0" || $ar_bracket['cups_bracket_user_use'] == $_SESSION['loginid']){
			  			/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/ 
			  			echo " <a href=\"?action=delete&amp;id=".$ar_bracket['cups_bracket_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";
			  		} else {
			  			echo "<a href=\"?action=kill_use_bracket&amp;id=".$ar_bracket['cups_bracket_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_killuse.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._ARTICLES_KILL_USE." - ".$ar_bracket['cups_bracket_user_use']." - ".$ar_bracket['cups_bracket_user_open']."\" title=\""._ARTICLES_KILL_USE." - ".$ar_bracket['cups_bracket_user_use']." - ".$ar_bracket['cups_bracket_user_open']."\"></a>";
			  		}
			  	 } else { 
			  	 	echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">"; 
			  	 } 
		echo "	</td>\n";
		echo "	<td width=\"50\" align=\"center\">".$ar_bracket['cups_bracket_id']."</td>\n";
		echo "	<td width=\"80\" align=\"center\">".$ar_bracket['cups_bracket_cup_game']."</td>\n";
		echo "	<td width=\"80\" align=\"center\">".$ar_bracket['cups_bracket_cup_type']."</td>\n";
		echo "	<td width=\"540\" align=\"left\">"; if ($ar_bracket['cups_bracket_publish'] != 1){echo "<img src=\"images/sys_no.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"\">";}echo $ar_bracket['cups_bracket_cup_name']."</td>\n";
		echo "	<td width=\"100\" align=\"center\"></td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		 PRIDANI CUPU
*
***********************************************************************************************************/
function AddCup(){
	
	global $db_cups_bracket,$db_clan_games,$db_clan_maps;
	global $eden_cfg;
	
	/* Debugovani */
	//$debug = 1;
	
	/* Provereni opravneni */
	if ($_GET['action'] == "add"){
		if (CheckPriv("groups_cups_add") <> 1){ echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "edit"){
		if (CheckPriv("groups_cups_edit") <> 1){ echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	
	$cups_bracket_wb_maps = $_POST['wb1_map']."#".$_POST['wb2_map']."#".$_POST['wb3_map']."#".$_POST['wb4_map']."#".$_POST['wb5_map']."#".$_POST['wb6_map'];
	$cups_bracket_lb_maps = $_POST['lb1_map']."#".$_POST['lb2_map']."#".$_POST['lb3_map']."#".$_POST['lb4_map']."#".$_POST['lb5_map']."#".$_POST['lb6_map']."#".$_POST['lb7_map']."#".$_POST['lb8_map']."#".$_POST['lb9_map'];
	$cups_bracket_fin_maps = $_POST['fin1_map']."#".$_POST['fin2_map']."#";
	
	if ($_GET['action'] == "add" && $_POST['confirm'] == "true"){
		
		$cups_bracket_date = date("Ymd");
		
		$cups_bracket_cup_version = 3;
		
		// Výcet povolených tagu
		$allowtags = "";
		
		// Z obsahu promenné body vyjmout nepovolené tagy
		$category_name = strip_tags($category_name,$allowtags);
		$komentar = strip_tags($komentar,$allowtags);
		
		$category_name = str_ireplace( "\n", "<br>",$category_name);
		$komentar = str_ireplace( "\n", "<br>",$komentar);
		$res = mysql_query("INSERT INTO $db_cups_bracket VALUES(
		'',
		'".(integer)$cups_bracket_date."',
		'".mysql_real_escape_string($_POST['cups_bracket_cup_type'])."',
		'".(integer)$_POST['cups_bracket_cup_type_end']."',
		'".(integer)$_POST['cups_bracket_cup_int_or_ext']."',
		'".mysql_real_escape_string($_POST['cups_bracket_cup_name'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_cup_comment'])."',
		'".(integer)$_POST['cups_bracket_cup_game']."',
		'3',
		'".mysql_real_escape_string($_POST['cups_bracket_wb1_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_wb2_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_wb3_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_wb4_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_wb5_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_wb6_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb1_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb2_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb3_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb4_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb5_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb6_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb7_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb8_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_lb9_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_fi_info'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_01'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_02'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_03'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_04'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_05'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_06'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_07'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_08'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_09'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_10'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_11'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_12'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_13'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_14'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_15'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_16'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_17'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_18'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_19'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_20'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_21'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_22'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_23'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_24'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_25'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_26'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_27'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_28'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_29'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_30'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_31'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_team_32'])."',
 		'".mysql_real_escape_string($_POST['cups_bracket_w1_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_5a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_5b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_6a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_6b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_7a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_7b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_8a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_8b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_9a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_9b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_10a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_10b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_11a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_11b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_12a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_12b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_13a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_13b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_14a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_14b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_15a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_15b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_16a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w1_16b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_5a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_5b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_6a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_6b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_7a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_7b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_8a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w2_8b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w3_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w4_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w4_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w4_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w4_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w5_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_w5_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_4b_score'])."',
 		'".mysql_real_escape_string($_POST['cups_bracket_l1_5a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_5b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_6a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_6b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_7a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_7b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_8a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l1_8b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_5a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_5b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_6a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_6b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_7a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_7b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_8a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l2_8b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l3_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_3a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_3b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_4a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l4_4b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l5_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l5_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l5_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l5_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l6_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l6_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l6_2a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l6_2b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l7_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l7_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l8_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_l8_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_f1_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_f1_1b_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_f2_1a_score'])."',
		'".mysql_real_escape_string($_POST['cups_bracket_f2_1b_score'])."',
		'".(float)$_SESSION['loginid']."',
		'',
		'".mysql_real_escape_string($cups_bracket_wb_maps)."',
		'".mysql_real_escape_string($cups_bracket_lb_maps)."',
		'".mysql_real_escape_string($cups_bracket_fin_maps)."',
		'".(integer)$_POST['cups_bracket_publish']."',
		'".(integer)$_POST['cups_bracket_de_manual']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Kdyz je vybrano jen ulozit */
		if ($_POST['save'] == 1){
			$_GET['id'] = mysql_insert_id();
			$_GET['action'] = "edit";
			unset($_POST['confirm']);
			mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=".(float)$_SESSION['loginid']." WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Kdyz je vybrano odeslat a ulozit */
		} else {
			mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=0 WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			ShowMain();
			exit;
		}
	}
	if ($_GET['action'] == "edit" && $_POST['confirm'] == "true"){
		$res = mysql_query("SELECT * FROM $db_cups_bracket WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		
		$datum = date("YmdHis");
		
		if ($_POST['cups_bracket_cup_type'] != ""){$cups_bracket_cup_type = $_POST['cups_bracket_cup_type'];} else {$cups_bracket_cup_type = $ar['cups_bracket_cup_type'];}
		if ($_POST['cups_bracket_cup_game'] != ""){$cups_bracket_cup_game = $_POST['cups_bracket_cup_game'];} else {$cups_bracket_cup_game = $ar['cups_bracket_cup_game'];}
		if ($_POST['cups_bracket_cup_type_end'] != ""){$cups_bracket_cup_type_end = $_POST['cups_bracket_cup_type_end'];} else {$cups_bracket_cup_type_end = $ar['cups_bracket_cup_type_end'];}
		// Výcet povolených tagu
		$allowtags = "<strong>, <u>, <i>";
		// Z obsahu promenné body vyjmout nepovolené tagy
		$category_name = strip_tags($category_name,$allowtags);
		$category_name = str_replace( "'", "&acute;",$category_name);
		$category_name = str_replace( "\"", "&quot;",$category_name);
		if ($ar['cups_bracket_de_manual'] == 1){
			$cups_bracket_l1_1a_score = $_POST['cups_bracket_l1_1a_score']."###".$_POST['cups_bracket_l1_1a_team'];
			$cups_bracket_l1_1b_score = $_POST['cups_bracket_l1_1b_score']."###".$_POST['cups_bracket_l1_1b_team'];
			$cups_bracket_l1_2a_score = $_POST['cups_bracket_l1_2a_score']."###".$_POST['cups_bracket_l1_2a_team'];
			$cups_bracket_l1_2b_score = $_POST['cups_bracket_l1_2b_score']."###".$_POST['cups_bracket_l1_2b_team'];
			$cups_bracket_l1_3a_score = $_POST['cups_bracket_l1_3a_score']."###".$_POST['cups_bracket_l1_3a_team'];
			$cups_bracket_l1_3b_score = $_POST['cups_bracket_l1_3b_score']."###".$_POST['cups_bracket_l1_3b_team'];
			$cups_bracket_l1_4a_score = $_POST['cups_bracket_l1_4a_score']."###".$_POST['cups_bracket_l1_4a_team'];
			$cups_bracket_l1_4b_score = $_POST['cups_bracket_l1_4b_score']."###".$_POST['cups_bracket_l1_4b_team'];
			$cups_bracket_l1_5a_score = $_POST['cups_bracket_l1_5a_score']."###".$_POST['cups_bracket_l1_5a_team'];
			$cups_bracket_l1_5b_score = $_POST['cups_bracket_l1_5b_score']."###".$_POST['cups_bracket_l1_5b_team'];
			$cups_bracket_l1_6a_score = $_POST['cups_bracket_l1_6a_score']."###".$_POST['cups_bracket_l1_6a_team'];
			$cups_bracket_l1_6b_score = $_POST['cups_bracket_l1_6b_score']."###".$_POST['cups_bracket_l1_6b_team'];
			$cups_bracket_l1_7a_score = $_POST['cups_bracket_l1_7a_score']."###".$_POST['cups_bracket_l1_7a_team'];
			$cups_bracket_l1_7b_score = $_POST['cups_bracket_l1_7b_score']."###".$_POST['cups_bracket_l1_7b_team'];
			$cups_bracket_l1_8a_score = $_POST['cups_bracket_l1_8a_score']."###".$_POST['cups_bracket_l1_8a_team'];
			$cups_bracket_l1_8b_score = $_POST['cups_bracket_l1_8b_score']."###".$_POST['cups_bracket_l1_8b_team'];
			
			$cups_bracket_l2_1a_score = $_POST['cups_bracket_l2_1a_score']."###".$_POST['cups_bracket_l2_1a_team'];
			$cups_bracket_l2_1b_score = $_POST['cups_bracket_l2_1b_score']."###".$_POST['cups_bracket_l2_1b_team'];
			$cups_bracket_l2_2a_score = $_POST['cups_bracket_l2_2a_score']."###".$_POST['cups_bracket_l2_2a_team'];
			$cups_bracket_l2_2b_score = $_POST['cups_bracket_l2_2b_score']."###".$_POST['cups_bracket_l2_2b_team'];
			$cups_bracket_l2_3a_score = $_POST['cups_bracket_l2_3a_score']."###".$_POST['cups_bracket_l2_3a_team'];
			$cups_bracket_l2_3b_score = $_POST['cups_bracket_l2_3b_score']."###".$_POST['cups_bracket_l2_3b_team'];
			$cups_bracket_l2_4a_score = $_POST['cups_bracket_l2_4a_score']."###".$_POST['cups_bracket_l2_4a_team'];
			$cups_bracket_l2_4b_score = $_POST['cups_bracket_l2_4b_score']."###".$_POST['cups_bracket_l2_4b_team'];
			$cups_bracket_l2_5a_score = $_POST['cups_bracket_l2_5a_score']."###".$_POST['cups_bracket_l2_5a_team'];
			$cups_bracket_l2_5b_score = $_POST['cups_bracket_l2_5b_score']."###".$_POST['cups_bracket_l2_5b_team'];
			$cups_bracket_l2_6a_score = $_POST['cups_bracket_l2_6a_score']."###".$_POST['cups_bracket_l2_6a_team'];
			$cups_bracket_l2_6b_score = $_POST['cups_bracket_l2_6b_score']."###".$_POST['cups_bracket_l2_6b_team'];
			$cups_bracket_l2_7a_score = $_POST['cups_bracket_l2_7a_score']."###".$_POST['cups_bracket_l2_7a_team'];
			$cups_bracket_l2_7b_score = $_POST['cups_bracket_l2_7b_score']."###".$_POST['cups_bracket_l2_7b_team'];
			$cups_bracket_l2_8a_score = $_POST['cups_bracket_l2_8a_score']."###".$_POST['cups_bracket_l2_8a_team'];
			$cups_bracket_l2_8b_score = $_POST['cups_bracket_l2_8b_score']."###".$_POST['cups_bracket_l2_8b_team'];
			
			$cups_bracket_l4_1a_score = $_POST['cups_bracket_l4_1a_score']."###".$_POST['cups_bracket_l4_1a_team'];
			$cups_bracket_l4_1b_score = $_POST['cups_bracket_l4_1b_score']."###".$_POST['cups_bracket_l4_1b_team'];
			$cups_bracket_l4_2a_score = $_POST['cups_bracket_l4_2a_score']."###".$_POST['cups_bracket_l4_2a_team'];
			$cups_bracket_l4_2b_score = $_POST['cups_bracket_l4_2b_score']."###".$_POST['cups_bracket_l4_2b_team'];
			$cups_bracket_l4_3a_score = $_POST['cups_bracket_l4_3a_score']."###".$_POST['cups_bracket_l4_3a_team'];
			$cups_bracket_l4_3b_score = $_POST['cups_bracket_l4_3b_score']."###".$_POST['cups_bracket_l4_3b_team'];
			$cups_bracket_l4_4a_score = $_POST['cups_bracket_l4_4a_score']."###".$_POST['cups_bracket_l4_4a_team'];
			$cups_bracket_l4_4b_score = $_POST['cups_bracket_l4_4b_score']."###".$_POST['cups_bracket_l4_4b_team'];
			
			$cups_bracket_l6_1a_score = $_POST['cups_bracket_l6_1a_score']."###".$_POST['cups_bracket_l6_1a_team'];
			//$cups_bracket_l6_1b_score = $_POST['cups_bracket_l6_1b_score']."###".$_POST['cups_bracket_l6_1b_team'];
			$cups_bracket_l6_2a_score = $_POST['cups_bracket_l6_2a_score']."###".$_POST['cups_bracket_l6_2a_team'];
			//$cups_bracket_l6_2b_score = $_POST['cups_bracket_l6_2b_score']."###".$_POST['cups_bracket_l6_2b_team'];
		} else {
			$cups_bracket_l1_1a_score = $_POST['cups_bracket_l1_1a_score'];
			$cups_bracket_l1_1b_score = $_POST['cups_bracket_l1_1b_score'];
			$cups_bracket_l1_2a_score = $_POST['cups_bracket_l1_2a_score'];
			$cups_bracket_l1_2b_score = $_POST['cups_bracket_l1_2b_score'];
			$cups_bracket_l1_3a_score = $_POST['cups_bracket_l1_3a_score'];
			$cups_bracket_l1_3b_score = $_POST['cups_bracket_l1_3b_score'];
			$cups_bracket_l1_4a_score = $_POST['cups_bracket_l1_4a_score'];
			$cups_bracket_l1_4b_score = $_POST['cups_bracket_l1_4b_score'];
			$cups_bracket_l1_5a_score = $_POST['cups_bracket_l1_5a_score'];
			$cups_bracket_l1_5b_score = $_POST['cups_bracket_l1_5b_score'];
			$cups_bracket_l1_6a_score = $_POST['cups_bracket_l1_6a_score'];
			$cups_bracket_l1_6b_score = $_POST['cups_bracket_l1_6b_score'];
			$cups_bracket_l1_7a_score = $_POST['cups_bracket_l1_7a_score'];
			$cups_bracket_l1_7b_score = $_POST['cups_bracket_l1_7b_score'];
			$cups_bracket_l1_8a_score = $_POST['cups_bracket_l1_8a_score'];
			$cups_bracket_l1_8b_score = $_POST['cups_bracket_l1_8b_score'];
			
			$cups_bracket_l2_1a_score = $_POST['cups_bracket_l2_1a_score'];
			$cups_bracket_l2_1b_score = $_POST['cups_bracket_l2_1b_score'];
			$cups_bracket_l2_2a_score = $_POST['cups_bracket_l2_2a_score'];
			$cups_bracket_l2_2b_score = $_POST['cups_bracket_l2_2b_score'];
			$cups_bracket_l2_3a_score = $_POST['cups_bracket_l2_3a_score'];
			$cups_bracket_l2_3b_score = $_POST['cups_bracket_l2_3b_score'];
			$cups_bracket_l2_4a_score = $_POST['cups_bracket_l2_4a_score'];
			$cups_bracket_l2_4b_score = $_POST['cups_bracket_l2_4b_score'];
			$cups_bracket_l2_5a_score = $_POST['cups_bracket_l2_5a_score'];
			$cups_bracket_l2_5b_score = $_POST['cups_bracket_l2_5b_score'];
			$cups_bracket_l2_6a_score = $_POST['cups_bracket_l2_6a_score'];
			$cups_bracket_l2_6b_score = $_POST['cups_bracket_l2_6b_score'];
			$cups_bracket_l2_7a_score = $_POST['cups_bracket_l2_7a_score'];
			$cups_bracket_l2_7b_score = $_POST['cups_bracket_l2_7b_score'];
			$cups_bracket_l2_8a_score = $_POST['cups_bracket_l2_8a_score'];
			$cups_bracket_l2_8b_score = $_POST['cups_bracket_l2_8b_score'];
			
			$cups_bracket_l4_1a_score = $_POST['cups_bracket_l4_1a_score'];
			$cups_bracket_l4_1b_score = $_POST['cups_bracket_l4_1b_score'];
			$cups_bracket_l4_2a_score = $_POST['cups_bracket_l4_2a_score'];
			$cups_bracket_l4_2b_score = $_POST['cups_bracket_l4_2b_score'];
			$cups_bracket_l4_3a_score = $_POST['cups_bracket_l4_3a_score'];
			$cups_bracket_l4_3b_score = $_POST['cups_bracket_l4_3b_score'];
			$cups_bracket_l4_4a_score = $_POST['cups_bracket_l4_4a_score'];
			$cups_bracket_l4_4b_score = $_POST['cups_bracket_l4_4b_score'];
			
			$cups_bracket_l6_1a_score = $_POST['cups_bracket_l6_1a_score'];
			$cups_bracket_l6_1b_score = $_POST['cups_bracket_l6_1b_score'];
			$cups_bracket_l6_2a_score = $_POST['cups_bracket_l6_2a_score'];
			$cups_bracket_l6_2b_score = $_POST['cups_bracket_l6_2b_score'];
		}
		$res = mysql_query("UPDATE $db_cups_bracket SET
		cups_bracket_cup_type='".mysql_real_escape_string($cups_bracket_cup_type)."',
		cups_bracket_cup_type_end=".(integer)$cups_bracket_cup_type_end.",
		cups_bracket_cup_int_or_ext=".(integer)$_POST['$cups_bracket_cup_int_or_ext'].",
		cups_bracket_cup_name='".mysql_real_escape_string($_POST['cups_bracket_cup_name'])."',
		cups_bracket_cup_comment='".mysql_real_escape_string($cups_bracket_cup_comment)."',
		cups_bracket_cup_game=".(integer)$cups_bracket_cup_game.",
		cups_bracket_wb1_info='".mysql_real_escape_string($_POST['cups_bracket_wb1_info'])."',
		cups_bracket_wb2_info='".mysql_real_escape_string($_POST['cups_bracket_wb2_info'])."',
		cups_bracket_wb3_info='".mysql_real_escape_string($_POST['cups_bracket_wb3_info'])."',
		cups_bracket_wb4_info='".mysql_real_escape_string($_POST['cups_bracket_wb4_info'])."',
		cups_bracket_wb5_info='".mysql_real_escape_string($_POST['cups_bracket_wb5_info'])."',
		cups_bracket_wb6_info='".mysql_real_escape_string($_POST['cups_bracket_wb6_info'])."',
		cups_bracket_lb1_info='".mysql_real_escape_string($_POST['cups_bracket_lb1_info'])."',
		cups_bracket_lb2_info='".mysql_real_escape_string($_POST['cups_bracket_lb2_info'])."',
		cups_bracket_lb3_info='".mysql_real_escape_string($_POST['cups_bracket_lb3_info'])."',
		cups_bracket_lb4_info='".mysql_real_escape_string($_POST['cups_bracket_lb4_info'])."',
		cups_bracket_lb5_info='".mysql_real_escape_string($_POST['cups_bracket_lb5_info'])."',
		cups_bracket_lb6_info='".mysql_real_escape_string($_POST['cups_bracket_lb6_info'])."',
		cups_bracket_lb7_info='".mysql_real_escape_string($_POST['cups_bracket_lb7_info'])."',
		cups_bracket_lb8_info='".mysql_real_escape_string($_POST['cups_bracket_lb8_info'])."',
		cups_bracket_lb9_info='".mysql_real_escape_string($_POST['cups_bracket_lb9_info'])."',
		cups_bracket_fi_info='".mysql_real_escape_string($_POST['cups_bracket_fi_info'])."',
		cups_bracket_team_01='".mysql_real_escape_string($_POST['cups_bracket_team_01'])."',
		cups_bracket_team_02='".mysql_real_escape_string($_POST['cups_bracket_team_02'])."',
		cups_bracket_team_03='".mysql_real_escape_string($_POST['cups_bracket_team_03'])."',
		cups_bracket_team_04='".mysql_real_escape_string($_POST['cups_bracket_team_04'])."',
		cups_bracket_team_05='".mysql_real_escape_string($_POST['cups_bracket_team_05'])."',
		cups_bracket_team_06='".mysql_real_escape_string($_POST['cups_bracket_team_06'])."',
		cups_bracket_team_07='".mysql_real_escape_string($_POST['cups_bracket_team_07'])."',
		cups_bracket_team_08='".mysql_real_escape_string($_POST['cups_bracket_team_08'])."',
		cups_bracket_team_09='".mysql_real_escape_string($_POST['cups_bracket_team_09'])."',
		cups_bracket_team_10='".mysql_real_escape_string($_POST['cups_bracket_team_10'])."',
		cups_bracket_team_11='".mysql_real_escape_string($_POST['cups_bracket_team_11'])."',
		cups_bracket_team_12='".mysql_real_escape_string($_POST['cups_bracket_team_12'])."',
		cups_bracket_team_13='".mysql_real_escape_string($_POST['cups_bracket_team_13'])."',
		cups_bracket_team_14='".mysql_real_escape_string($_POST['cups_bracket_team_14'])."',
		cups_bracket_team_15='".mysql_real_escape_string($_POST['cups_bracket_team_15'])."',
		cups_bracket_team_16='".mysql_real_escape_string($_POST['cups_bracket_team_16'])."',
		cups_bracket_team_17='".mysql_real_escape_string($_POST['cups_bracket_team_17'])."',
		cups_bracket_team_18='".mysql_real_escape_string($_POST['cups_bracket_team_18'])."',
		cups_bracket_team_19='".mysql_real_escape_string($_POST['cups_bracket_team_19'])."',
		cups_bracket_team_20='".mysql_real_escape_string($_POST['cups_bracket_team_20'])."',
		cups_bracket_team_21='".mysql_real_escape_string($_POST['cups_bracket_team_21'])."',
		cups_bracket_team_22='".mysql_real_escape_string($_POST['cups_bracket_team_22'])."',
		cups_bracket_team_23='".mysql_real_escape_string($_POST['cups_bracket_team_23'])."',
		cups_bracket_team_24='".mysql_real_escape_string($_POST['cups_bracket_team_24'])."',
		cups_bracket_team_25='".mysql_real_escape_string($_POST['cups_bracket_team_25'])."',
		cups_bracket_team_26='".mysql_real_escape_string($_POST['cups_bracket_team_26'])."',
		cups_bracket_team_27='".mysql_real_escape_string($_POST['cups_bracket_team_27'])."',
		cups_bracket_team_28='".mysql_real_escape_string($_POST['cups_bracket_team_28'])."',
		cups_bracket_team_29='".mysql_real_escape_string($_POST['cups_bracket_team_29'])."',
		cups_bracket_team_30='".mysql_real_escape_string($_POST['cups_bracket_team_30'])."',
		cups_bracket_team_31='".mysql_real_escape_string($_POST['cups_bracket_team_31'])."',
		cups_bracket_team_32='".mysql_real_escape_string($_POST['cups_bracket_team_32'])."',
		cups_bracket_w1_1a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_1a_score'])."',
		cups_bracket_w1_1b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_1b_score'])."',
		cups_bracket_w1_2a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_2a_score'])."',
		cups_bracket_w1_2b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_2b_score'])."',
		cups_bracket_w1_3a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_3a_score'])."',
		cups_bracket_w1_3b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_3b_score'])."',
		cups_bracket_w1_4a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_4a_score'])."',
		cups_bracket_w1_4b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_4b_score'])."',
		cups_bracket_w1_5a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_5a_score'])."',
		cups_bracket_w1_5b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_5b_score'])."',
		cups_bracket_w1_6a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_6a_score'])."',
		cups_bracket_w1_6b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_6b_score'])."',
		cups_bracket_w1_7a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_7a_score'])."',
		cups_bracket_w1_7b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_7b_score'])."',
		cups_bracket_w1_8a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_8a_score'])."',
		cups_bracket_w1_8b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_8b_score'])."',
		cups_bracket_w1_9a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_9a_score'])."',
		cups_bracket_w1_9b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_9b_score'])."',
		cups_bracket_w1_10a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_10a_score'])."',
		cups_bracket_w1_10b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_10b_score'])."',
		cups_bracket_w1_11a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_11a_score'])."',
		cups_bracket_w1_11b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_11b_score'])."',
		cups_bracket_w1_12a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_12a_score'])."',
		cups_bracket_w1_12b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_12b_score'])."',
		cups_bracket_w1_13a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_13a_score'])."',
		cups_bracket_w1_13b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_13b_score'])."',
		cups_bracket_w1_14a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_14a_score'])."',
		cups_bracket_w1_14b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_14b_score'])."',
		cups_bracket_w1_15a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_15a_score'])."',
		cups_bracket_w1_15b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_15b_score'])."',
		cups_bracket_w1_16a_score='".mysql_real_escape_string($_POST['cups_bracket_w1_16a_score'])."',
		cups_bracket_w1_16b_score='".mysql_real_escape_string($_POST['cups_bracket_w1_16b_score'])."',
		cups_bracket_w2_1a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_1a_score'])."',
		cups_bracket_w2_1b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_1b_score'])."',
		cups_bracket_w2_2a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_2a_score'])."',
		cups_bracket_w2_2b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_2b_score'])."',
		cups_bracket_w2_3a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_3a_score'])."',
		cups_bracket_w2_3b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_3b_score'])."',
		cups_bracket_w2_4a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_4a_score'])."',
		cups_bracket_w2_4b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_4b_score'])."',
		cups_bracket_w2_5a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_5a_score'])."',
		cups_bracket_w2_5b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_5b_score'])."',
		cups_bracket_w2_6a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_6a_score'])."',
		cups_bracket_w2_6b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_6b_score'])."',
		cups_bracket_w2_7a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_7a_score'])."',
		cups_bracket_w2_7b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_7b_score'])."',
		cups_bracket_w2_8a_score='".mysql_real_escape_string($_POST['cups_bracket_w2_8a_score'])."',
		cups_bracket_w2_8b_score='".mysql_real_escape_string($_POST['cups_bracket_w2_8b_score'])."',
		cups_bracket_w3_1a_score='".mysql_real_escape_string($_POST['cups_bracket_w3_1a_score'])."',
		cups_bracket_w3_1b_score='".mysql_real_escape_string($_POST['cups_bracket_w3_1b_score'])."',
		cups_bracket_w3_2a_score='".mysql_real_escape_string($_POST['cups_bracket_w3_2a_score'])."',
		cups_bracket_w3_2b_score='".mysql_real_escape_string($_POST['cups_bracket_w3_2b_score'])."',
		cups_bracket_w3_3a_score='".mysql_real_escape_string($_POST['cups_bracket_w3_3a_score'])."',
		cups_bracket_w3_3b_score='".mysql_real_escape_string($_POST['cups_bracket_w3_3b_score'])."',
		cups_bracket_w3_4a_score='".mysql_real_escape_string($_POST['cups_bracket_w3_4a_score'])."',
		cups_bracket_w3_4b_score='".mysql_real_escape_string($_POST['cups_bracket_w3_4b_score'])."',
		cups_bracket_w4_1a_score='".mysql_real_escape_string($_POST['cups_bracket_w4_1a_score'])."',
		cups_bracket_w4_1b_score='".mysql_real_escape_string($_POST['cups_bracket_w4_1b_score'])."',
		cups_bracket_w4_2a_score='".mysql_real_escape_string($_POST['cups_bracket_w4_2a_score'])."',
		cups_bracket_w4_2b_score='".mysql_real_escape_string($_POST['cups_bracket_w4_2b_score'])."',
		cups_bracket_w5_1a_score='".mysql_real_escape_string($_POST['cups_bracket_w5_1a_score'])."',
		cups_bracket_w5_1b_score='".mysql_real_escape_string($_POST['cups_bracket_w5_1b_score'])."',
		cups_bracket_l1_1a_score='".mysql_real_escape_string($cups_bracket_l1_1a_score)."',
		cups_bracket_l1_1b_score='".mysql_real_escape_string($cups_bracket_l1_1b_score)."',
		cups_bracket_l1_2a_score='".mysql_real_escape_string($cups_bracket_l1_2a_score)."',
		cups_bracket_l1_2b_score='".mysql_real_escape_string($cups_bracket_l1_2b_score)."',
		cups_bracket_l1_3a_score='".mysql_real_escape_string($cups_bracket_l1_3a_score)."',
		cups_bracket_l1_3b_score='".mysql_real_escape_string($cups_bracket_l1_3b_score)."',
		cups_bracket_l1_4a_score='".mysql_real_escape_string($cups_bracket_l1_4a_score)."',
		cups_bracket_l1_4b_score='".mysql_real_escape_string($cups_bracket_l1_4b_score)."',
		cups_bracket_l1_5a_score='".mysql_real_escape_string($cups_bracket_l1_5a_score)."',
		cups_bracket_l1_5b_score='".mysql_real_escape_string($cups_bracket_l1_5b_score)."',
		cups_bracket_l1_6a_score='".mysql_real_escape_string($cups_bracket_l1_6a_score)."',
		cups_bracket_l1_6b_score='".mysql_real_escape_string($cups_bracket_l1_6b_score)."',
		cups_bracket_l1_7a_score='".mysql_real_escape_string($cups_bracket_l1_7a_score)."',
		cups_bracket_l1_7b_score='".mysql_real_escape_string($cups_bracket_l1_7b_score)."',
		cups_bracket_l1_8a_score='".mysql_real_escape_string($cups_bracket_l1_8a_score)."',
		cups_bracket_l1_8b_score='".mysql_real_escape_string($cups_bracket_l1_8b_score)."',
		cups_bracket_l2_1a_score='".mysql_real_escape_string($cups_bracket_l2_1a_score)."',
		cups_bracket_l2_1b_score='".mysql_real_escape_string($cups_bracket_l2_1b_score)."',
		cups_bracket_l2_2a_score='".mysql_real_escape_string($cups_bracket_l2_2a_score)."',
		cups_bracket_l2_2b_score='".mysql_real_escape_string($cups_bracket_l2_2b_score)."',
		cups_bracket_l2_3a_score='".mysql_real_escape_string($cups_bracket_l2_3a_score)."',
		cups_bracket_l2_3b_score='".mysql_real_escape_string($cups_bracket_l2_3b_score)."',
		cups_bracket_l2_4a_score='".mysql_real_escape_string($cups_bracket_l2_4a_score)."',
		cups_bracket_l2_4b_score='".mysql_real_escape_string($cups_bracket_l2_4b_score)."',
		cups_bracket_l2_5a_score='".mysql_real_escape_string($cups_bracket_l2_5a_score)."',
		cups_bracket_l2_5b_score='".mysql_real_escape_string($cups_bracket_l2_5b_score)."',
		cups_bracket_l2_6a_score='".mysql_real_escape_string($cups_bracket_l2_6a_score)."',
		cups_bracket_l2_6b_score='".mysql_real_escape_string($cups_bracket_l2_6b_score)."',
		cups_bracket_l2_7a_score='".mysql_real_escape_string($cups_bracket_l2_7a_score)."',
		cups_bracket_l2_7b_score='".mysql_real_escape_string($cups_bracket_l2_7b_score)."',
		cups_bracket_l2_8a_score='".mysql_real_escape_string($cups_bracket_l2_8a_score)."',
		cups_bracket_l2_8b_score='".mysql_real_escape_string($cups_bracket_l2_8b_score)."',
		cups_bracket_l3_1a_score='".mysql_real_escape_string($_POST['cups_bracket_l3_1a_score'])."',
		cups_bracket_l3_1b_score='".mysql_real_escape_string($_POST['cups_bracket_l3_1b_score'])."',
		cups_bracket_l3_2a_score='".mysql_real_escape_string($_POST['cups_bracket_l3_2a_score'])."',
		cups_bracket_l3_2b_score='".mysql_real_escape_string($_POST['cups_bracket_l3_2b_score'])."',
		cups_bracket_l3_3a_score='".mysql_real_escape_string($_POST['cups_bracket_l3_3a_score'])."',
		cups_bracket_l3_3b_score='".mysql_real_escape_string($_POST['cups_bracket_l3_3b_score'])."',
		cups_bracket_l3_4a_score='".mysql_real_escape_string($_POST['cups_bracket_l3_4a_score'])."',
		cups_bracket_l3_4b_score='".mysql_real_escape_string($_POST['cups_bracket_l3_4b_score'])."',
		cups_bracket_l4_1a_score='".mysql_real_escape_string($cups_bracket_l4_1a_score)."',
		cups_bracket_l4_1b_score='".mysql_real_escape_string($cups_bracket_l4_1b_score)."',
		cups_bracket_l4_2a_score='".mysql_real_escape_string($cups_bracket_l4_2a_score)."',
		cups_bracket_l4_2b_score='".mysql_real_escape_string($cups_bracket_l4_2b_score)."',
		cups_bracket_l4_3a_score='".mysql_real_escape_string($cups_bracket_l4_3a_score)."',
		cups_bracket_l4_3b_score='".mysql_real_escape_string($cups_bracket_l4_3b_score)."',
		cups_bracket_l4_4a_score='".mysql_real_escape_string($cups_bracket_l4_4a_score)."',
		cups_bracket_l4_4b_score='".mysql_real_escape_string($cups_bracket_l4_4b_score)."',
		cups_bracket_l5_1a_score='".mysql_real_escape_string($_POST['cups_bracket_l5_1a_score'])."',
		cups_bracket_l5_1b_score='".mysql_real_escape_string($_POST['cups_bracket_l5_1b_score'])."',
		cups_bracket_l5_2a_score='".mysql_real_escape_string($_POST['cups_bracket_l5_2a_score'])."',
		cups_bracket_l5_2b_score='".mysql_real_escape_string($_POST['cups_bracket_l5_2b_score'])."',
		cups_bracket_l6_1a_score='".mysql_real_escape_string($cups_bracket_l6_1a_score)."',
		cups_bracket_l6_1b_score='".mysql_real_escape_string($_POST['cups_bracket_l6_1b_score'])."',
		cups_bracket_l6_2a_score='".mysql_real_escape_string($cups_bracket_l6_2a_score)."',
		cups_bracket_l6_2b_score='".mysql_real_escape_string($_POST['cups_bracket_l6_2b_score'])."',
		cups_bracket_l7_1a_score='".mysql_real_escape_string($_POST['cups_bracket_l7_1a_score'])."',
		cups_bracket_l7_1b_score='".mysql_real_escape_string($_POST['cups_bracket_l7_1b_score'])."',
		cups_bracket_l8_1a_score='".mysql_real_escape_string($_POST['cups_bracket_l8_1a_score'])."',
		cups_bracket_l8_1b_score='".mysql_real_escape_string($_POST['cups_bracket_l8_1b_score'])."',
		cups_bracket_f1_1a_score='".mysql_real_escape_string($_POST['cups_bracket_f1_1a_score'])."',
		cups_bracket_f1_1b_score='".mysql_real_escape_string($_POST['cups_bracket_f1_1b_score'])."',
		cups_bracket_f2_1a_score='".mysql_real_escape_string($_POST['cups_bracket_f2_1a_score'])."',
		cups_bracket_f2_1b_score='".mysql_real_escape_string($_POST['cups_bracket_f2_1b_score'])."',
		cups_bracket_wb_maps='".mysql_real_escape_string($cups_bracket_wb_maps)."',
		cups_bracket_lb_maps='".mysql_real_escape_string($cups_bracket_lb_maps)."',
		cups_bracket_fin_maps='".mysql_real_escape_string($cups_bracket_fin_maps)."',
		cups_bracket_publish=".(integer)$_POST['cups_bracket_publish'].",
		cups_bracket_de_manual='".(integer)$_POST['cups_bracket_de_manual']."'
		WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Kdyz je vybrano jen ulozit */
		if ($_POST['save'] == 1){
			mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=".(float)$_SESSION['loginid']." WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			unset($_POST['confirm']);
		/* Kdyz je vybrano odeslat a ulozit */
		} else {
			mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=0 WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			ShowMain();
			exit;
		}
	}
	
	if ($_POST['confirm'] <> "true"){
		if ($_GET['action'] == "edit"){
			
			$res = mysql_query("SELECT * FROM $db_cups_bracket WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			
			if ($ar['cups_bracket_user_use'] == "0" || $ar['cups_bracket_user_use'] == $_SESSION['loginid']){
				// Zapsani k novince jmeno uzivatele, ktery otevrel novinku
				mysql_query("UPDATE $db_cups_bracket SET cups_bracket_user_use=".(float)$_SESSION['loginid'].", cups_bracket_user_open=".(float)$datum." WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				ShowMain();
				exit;
			}
			
			$wb_map = explode("#", $ar['cups_bracket_wb_maps']);
			$lb_map = explode("#", $ar['cups_bracket_lb_maps']);
			$fin_map = explode("#", $ar['cups_bracket_fin_maps']);
			
			$ar['category_name'] = str_ireplace( "&quot;","\"",$ar['category_name']);
			$ar['category_name'] = str_ireplace( "&acute;","'",$ar['category_name']);
		}
		if ($ar['cups_bracket_cup_type'] == "8se" || $_GET['cup_type'] == "8se"){$cups_bracket_cup_type = "8se";}
		if ($ar['cups_bracket_cup_type'] == "8de" || $_GET['cup_type'] == "8de"){$cups_bracket_cup_type = "8de";}
		if ($ar['cups_bracket_cup_type'] == "16se" || $_GET['cup_type'] == "16se"){$cups_bracket_cup_type = "16se";}
		if ($ar['cups_bracket_cup_type'] == "16de" || $_GET['cup_type'] == "16de"){$cups_bracket_cup_type = "16de";}
		if ($ar['cups_bracket_cup_type'] == "32se" || $_GET['cup_type'] == "32se"){$cups_bracket_cup_type = "32se";}
		if ($ar['cups_bracket_cup_type'] == "32de" || $_GET['cup_type'] == "32de"){$cups_bracket_cup_type = "32de";}
		/* Winner Bracket */
		if ($ar['cups_bracket_w1_1a_score'] > $ar['cups_bracket_w1_1b_score'] && $ar['cups_bracket_w1_1a_score'] != ""){$w1 = stripslashes($ar['cups_bracket_team_01']); $l1 = stripslashes($ar['cups_bracket_team_02']);}elseif ($ar['cups_bracket_w1_1a_score'] == 0 && $ar['cups_bracket_w1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w1 = stripslashes($ar['cups_bracket_team_02']); $l1 = stripslashes($ar['cups_bracket_team_01']);}
		if ($ar['cups_bracket_w1_2a_score'] > $ar['cups_bracket_w1_2b_score'] && $ar['cups_bracket_w1_2a_score'] != ""){$w2 = stripslashes($ar['cups_bracket_team_03']); $l2 = stripslashes($ar['cups_bracket_team_04']);}elseif ($ar['cups_bracket_w1_2a_score'] == 0 && $ar['cups_bracket_w1_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w2 = stripslashes($ar['cups_bracket_team_04']); $l2 = stripslashes($ar['cups_bracket_team_03']);}
		if ($ar['cups_bracket_w1_3a_score'] > $ar['cups_bracket_w1_3b_score'] && $ar['cups_bracket_w1_3a_score'] != ""){$w3 = stripslashes($ar['cups_bracket_team_05']); $l3 = stripslashes($ar['cups_bracket_team_06']);}elseif ($ar['cups_bracket_w1_3a_score'] == 0 && $ar['cups_bracket_w1_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w3 = stripslashes($ar['cups_bracket_team_06']); $l3 = stripslashes($ar['cups_bracket_team_05']);}
		if ($ar['cups_bracket_w1_4a_score'] > $ar['cups_bracket_w1_4b_score'] && $ar['cups_bracket_w1_4a_score'] != ""){$w4 = stripslashes($ar['cups_bracket_team_07']); $l4 = stripslashes($ar['cups_bracket_team_08']);}elseif ($ar['cups_bracket_w1_4a_score'] == 0 && $ar['cups_bracket_w1_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w4 = stripslashes($ar['cups_bracket_team_08']); $l4 = stripslashes($ar['cups_bracket_team_07']);}
		
		/* 16SE, 16DE, 32SE, 32DE */
		if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
			if ($ar['cups_bracket_w1_5a_score'] > $ar['cups_bracket_w1_5b_score'] && $ar['cups_bracket_w1_5a_score'] != ""){$w5 = stripslashes($ar['cups_bracket_team_09']); $l5 = stripslashes($ar['cups_bracket_team_10']);}elseif ($ar['cups_bracket_w1_5a_score'] == 0 && $ar['cups_bracket_w1_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w5 = stripslashes($ar['cups_bracket_team_10']); $l5 = stripslashes($ar['cups_bracket_team_09']);}
			if ($ar['cups_bracket_w1_6a_score'] > $ar['cups_bracket_w1_6b_score'] && $ar['cups_bracket_w1_6a_score'] != ""){$w6 = stripslashes($ar['cups_bracket_team_11']); $l6 = stripslashes($ar['cups_bracket_team_12']);}elseif ($ar['cups_bracket_w1_6a_score'] == 0 && $ar['cups_bracket_w1_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w6 = stripslashes($ar['cups_bracket_team_12']); $l6 = stripslashes($ar['cups_bracket_team_11']);}
			if ($ar['cups_bracket_w1_7a_score'] > $ar['cups_bracket_w1_7b_score'] && $ar['cups_bracket_w1_7a_score'] != ""){$w7 = stripslashes($ar['cups_bracket_team_13']); $l7 = stripslashes($ar['cups_bracket_team_14']);}elseif ($ar['cups_bracket_w1_7a_score'] == 0 && $ar['cups_bracket_w1_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w7 = stripslashes($ar['cups_bracket_team_14']); $l7 = stripslashes($ar['cups_bracket_team_13']);}
			if ($ar['cups_bracket_w1_8a_score'] > $ar['cups_bracket_w1_8b_score'] && $ar['cups_bracket_w1_8a_score'] != ""){$w8 = stripslashes($ar['cups_bracket_team_15']); $l8 = stripslashes($ar['cups_bracket_team_16']);}elseif ($ar['cups_bracket_w1_8a_score'] == 0 && $ar['cups_bracket_w1_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w8 = stripslashes($ar['cups_bracket_team_16']); $l8 = stripslashes($ar['cups_bracket_team_15']);}
			/* 32SE, 32DE */
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
		}
		if ($ar['cups_bracket_w2_1a_score'] > $ar['cups_bracket_w2_1b_score'] && $ar['cups_bracket_w2_1a_score'] != ""){$w17 = $w1; $l17 = $w2;}elseif ($ar['cups_bracket_w2_1a_score'] == 0 && $ar['cups_bracket_w2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w17 = $w2; $l17 = $w1;}
		if ($ar['cups_bracket_w2_2a_score'] > $ar['cups_bracket_w2_2b_score'] && $ar['cups_bracket_w2_2a_score'] != ""){$w18 = $w3; $l18 = $w4;}elseif ($ar['cups_bracket_w2_2a_score'] == 0 && $ar['cups_bracket_w2_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w18 = $w4; $l18 = $w3;}
		if ($ar['cups_bracket_w2_3a_score'] > $ar['cups_bracket_w2_3b_score'] && $ar['cups_bracket_w2_3a_score'] != ""){$w19 = $w5; $l19 = $w6;}elseif ($ar['cups_bracket_w2_3a_score'] == 0 && $ar['cups_bracket_w2_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w19 = $w6; $l19 = $w5;}
		if ($ar['cups_bracket_w2_4a_score'] > $ar['cups_bracket_w2_4b_score'] && $ar['cups_bracket_w2_4a_score'] != ""){$w20 = $w7; $l20 = $w8;}elseif ($ar['cups_bracket_w2_4a_score'] == 0 && $ar['cups_bracket_w2_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w20 = $w8; $l20 = $w7;}
		/* 32SE, 32DE */
		if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
			if ($ar['cups_bracket_w2_5a_score'] > $ar['cups_bracket_w2_5b_score'] && $ar['cups_bracket_w2_5a_score'] != ""){$w21 = $w9; $l21 = $w10;}elseif ($ar['cups_bracket_w2_5a_score'] == 0 && $ar['cups_bracket_w2_5b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w21 = $w10; $l21 = $w9;}
			if ($ar['cups_bracket_w2_6a_score'] > $ar['cups_bracket_w2_6b_score'] && $ar['cups_bracket_w2_6a_score'] != ""){$w22 = $w11; $l22 = $w12;}elseif ($ar['cups_bracket_w2_6a_score'] == 0 && $ar['cups_bracket_w2_6b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w22 = $w12; $l22 = $w11;}
			if ($ar['cups_bracket_w2_7a_score'] > $ar['cups_bracket_w2_7b_score'] && $ar['cups_bracket_w2_7a_score'] != ""){$w23 = $w13; $l23 = $w14;}elseif ($ar['cups_bracket_w2_7a_score'] == 0 && $ar['cups_bracket_w2_7b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w23 = $w14; $l23 = $w13;}
			if ($ar['cups_bracket_w2_8a_score'] > $ar['cups_bracket_w2_8b_score'] && $ar['cups_bracket_w2_8a_score'] != ""){$w24 = $w15; $l24 = $w16;}elseif ($ar['cups_bracket_w2_8a_score'] == 0 && $ar['cups_bracket_w2_8b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w24 = $w16; $l24 = $w15;}
		}
		if ($ar['cups_bracket_w3_1a_score'] > $ar['cups_bracket_w3_1b_score'] && $ar['cups_bracket_w3_1a_score'] != ""){$w25 = $w17; $l25 = $w18;}elseif ($ar['cups_bracket_w3_1a_score'] == 0 && $ar['cups_bracket_w3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w25 = $w18; $l25 = $w17;}
		if ($ar['cups_bracket_w3_2a_score'] > $ar['cups_bracket_w3_2b_score'] && $ar['cups_bracket_w3_2a_score'] != ""){$w26 = $w19; $l26 = $w20;}elseif ($ar['cups_bracket_w3_2a_score'] == 0 && $ar['cups_bracket_w3_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w26 = $w20; $l26 = $w19;}
		
		if ($ar['cups_bracket_w3_1a_score'] > $ar['cups_bracket_w3_1b_score'] && $ar['cups_bracket_w3_1a_score'] != ""){$w25 = $w17; $l25 = $w18; if ($cups_bracket_cup_type == "8se"){$champion = $w17; $second = $w18;}}elseif ($ar['cups_bracket_w3_1a_score'] == 0 && $ar['cups_bracket_w3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w25 = $w18; $l25 = $w17;if ($cups_bracket_cup_type == "8se"){$champion = $w18; $second = $w17;}}
		
		/* 32SE, 32DE */
		if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
			if ($ar['cups_bracket_w3_3a_score'] > $ar['cups_bracket_w3_3b_score'] && $ar['cups_bracket_w3_3a_score'] != ""){$w27 = $w21; $l27 = $w22;}elseif ($ar['cups_bracket_w3_3a_score'] == 0 && $ar['cups_bracket_w3_3b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w27 = $w22; $l27 = $w21;}
			if ($ar['cups_bracket_w3_4a_score'] > $ar['cups_bracket_w3_4b_score'] && $ar['cups_bracket_w3_4a_score'] != ""){$w28 = $w23; $l28 = $w24;}elseif ($ar['cups_bracket_w3_4a_score'] == 0 && $ar['cups_bracket_w3_4b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w28 = $w24; $l28 = $w23;}
		}
		if ($ar['cups_bracket_w4_1a_score'] > $ar['cups_bracket_w4_1b_score'] && $ar['cups_bracket_w4_1a_score'] != ""){$w29 = $w25; $l29 = $w26; if ($cups_bracket_cup_type == "16se"){$champion = $w25; $second = $w26;}}elseif ($ar['cups_bracket_w4_1a_score'] == 0 && $ar['cups_bracket_w4_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w29 = $w26; $l29 = $w25;if ($cups_bracket_cup_type == "16se"){$champion = $w26; $second = $w25;}}
		/* 32SE, 32DE */
		if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
			if ($ar['cups_bracket_w4_2a_score'] > $ar['cups_bracket_w4_2b_score'] && $ar['cups_bracket_w4_2a_score'] != ""){$w30 = $w27; $l30 = $w28;}elseif ($ar['cups_bracket_w4_2a_score'] == 0 && $ar['cups_bracket_w4_2b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w30 = $w28; $l30 = $w27;}
			if ($ar['cups_bracket_w5_1a_score'] > $ar['cups_bracket_w5_1b_score'] && $ar['cups_bracket_w5_1a_score'] != ""){$w31 = $w29; $l31 = $w30; if ($cups_bracket_cup_type == "32se"){$champion = $w29; $second = $w30;}}elseif ($ar['cups_bracket_w5_1a_score'] == 0 && $ar['cups_bracket_w5_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$w31 = $w30; $l31 = $w29; if ($cups_bracket_cup_type == "32se"){$champion = $w30; $second = $w29;}}
		}
		/* Rozhozeni teamu tak aby se nepotkali */
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
				if ($ar['cups_bracket_l3_1a_score'] > $ar['cups_bracket_l3_1b_score'] && $ar['cups_bracket_l3_1a_score'] != ""){$lb17 = $lb9;$fourth = $lb10;}elseif ($ar['cups_bracket_l3_1a_score'] == 0 && $ar['cups_bracket_l3_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb17 = $lb10; $fourth = $lb9;}
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
				if ($ar['cups_bracket_l4_1a_score'] > $ar['cups_bracket_l4_1b_score'] && $ar['cups_bracket_l4_1a_score'] != ""){$lb21 = $lb17; $third = $l25; }elseif ($ar['cups_bracket_l4_1a_score'] == 0 && $ar['cups_bracket_l4_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$lb21 = $l25; $third = $lb17;}
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
			if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$fwin = $w25; $flooser = $lb21; $champion = $w25; $second = $lb21;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$fwin = $lb21; $flooser = $w25;}
			if ($ar['cups_bracket_f2_1a_score'] > $ar['cups_bracket_f2_1b_score'] && $ar['cups_bracket_f2_1a_score'] != ""){$champion = $fwin; $second = $flooser;}elseif ($ar['cups_bracket_f2_1a_score'] == 0 && $ar['cups_bracket_f2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$champion = $flooser; $second = $fwin;}
		}
		// 3-4 misto 16se
		if ($cups_bracket_cup_type == "16se"){
			if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$third = $l25; $fourth = $l26;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$third = $l26; $fourth = $l25;}
		}
		// Finale 16de
		if ($cups_bracket_cup_type == "16de"){
			if ($ar['cups_bracket_f1_1a_score'] > $ar['cups_bracket_f1_1b_score'] && $ar['cups_bracket_f1_1a_score'] != ""){$fwin = $w29; $flooser = $lb27; $champion = $w29; $second = $lb27;}elseif ($ar['cups_bracket_f1_1a_score'] == 0 && $ar['cups_bracket_f1_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$fwin = $lb27; $flooser = $w29;}
			if ($ar['cups_bracket_f2_1a_score'] > $ar['cups_bracket_f2_1b_score'] && $ar['cups_bracket_f2_1a_score'] != ""){$champion = $fwin; $second = $flooser;}elseif ($ar['cups_bracket_f2_1a_score'] == 0 && $ar['cups_bracket_f2_1b_score'] == 0){ /* Pokud je podminka != 0 && != 0 tak to nefunguje */} else {$champion = $flooser; $second = $fwin;}
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
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"; if ($_GET['action'] == "add"){echo _CUPS_BRACKETS_ADD_CUP;} else {echo _CUPS_BRACKETS_EDIT_CUP." - ID ".$ar['cups_bracket_id'];} echo "&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><img src=\"images/sys_manage.gif\" width=\"18\" border=\"0\" alt=\"\">\n";
		echo "			<a href=\"modul_cups.php?action=&amp;project=".$_SESSION['project']."\">"._CUPS_BRACKETS_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"".$eden_cfg['url']."eden_bracket.php?project=".$_SESSION['project']."&amp;id=".$_GET['id']."\" target=\"_blank\">"._CUPS_BRACKETS_PREVIEW."</a></td>\n";
		echo "		<td align=\"left\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"600\" border=\"0\" bordercolor=\"#000000\" cellpadding=\"4\" cellspacing=\"0\">\n";
		echo "	<tbody><form action=\"modul_cups.php?action="; if ($_GET['action'] == "edit"){echo "edit";} else {echo "add";} echo "&amp;id=".$_GET['id']."\" method=\"post\">\n";
		echo "	<tr>\n";
		echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_NAME.": </strong></td>\n";
		echo "		<td> <input type=\"text\" maxlength=\"50\" size=\"40\" name=\"cups_bracket_cup_name\" value=\"".$ar['cups_bracket_cup_name']."\">  CUP Version:".$ar['cups_bracket_cup_version']."</td>\n";
		echo "	</tr>\n";
		if (CheckPriv("groups_cups_edit") == 1){
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_TYPE.": </strong></td>\n";
			echo "		<td> <select name=\"cups_bracket_cup_type\">\n";
			echo "				<option value=\"8se\""; if ($cups_bracket_cup_type == "8se"){echo "selected=\"selected\"";} echo ">8 Single Elimination</option>\n";
			echo "				<option value=\"8de\""; if ($cups_bracket_cup_type == "8de"){echo "selected=\"selected\"";} echo ">8 Double Elimination</option>\n";
			echo "				<option value=\"16se\""; if ($cups_bracket_cup_type == "16se"){echo "selected=\"selected\"";} echo ">16 Single Elimination</option>\n";
			echo "				<option value=\"16de\""; if ($cups_bracket_cup_type == "16de"){echo "selected=\"selected\"";} echo ">16 Double Elimination</option>\n";
			echo "				<option value=\"32se\""; if ($cups_bracket_cup_type == "32se"){echo "selected=\"selected\"";} echo ">32 Single Elimination</option>\n";
			echo "				<option value=\"32de\""; if ($cups_bracket_cup_type == "32de"){echo "selected=\"selected\"";} echo ">32 Double Elimination</option>\n";
			echo "			</select></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_TYPE_END.": </strong></td>\n";
			echo "		<td> <select name=\"cups_bracket_cup_type_end\">\n";
			echo "				<option value=\"1\""; if ($ar['cups_bracket_cup_type_end'] == "1"){echo "selected=\"selected\"";} echo ">1 Match</option>\n";
			echo "				<option value=\"2\""; if ($ar['cups_bracket_cup_type_end'] == "2"){echo "selected=\"selected\"";} echo ">2 Matches</option>\n";
			echo "			</select></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_INT_OR_EXT.": </strong></td>\n";
			echo "		<td> <select name=\"cups_bracket_cup_int_or_ext\">\n";
			echo "				<option value=\"1\""; if ($ar['cups_bracket_cup_int_or_ext'] == "1"){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_INT."</option>\n";
			echo "				<option value=\"2\""; if ($ar['cups_bracket_cup_int_or_ext'] == "2"){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_EXT."</option>\n";
			echo "			</select></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_DE_ALLOC.": </strong></td>\n";
			echo "		<td>"._CUPS_BRACKETS_DE_ALLOC_AUTO."&nbsp;<input name=\"cups_bracket_de_manual\" type=\"radio\" value=\"0\""; if ($ar['cups_bracket_de_manual'] == 0){ echo "checked";} echo ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"._CUPS_BRACKETS_DE_ALLOC_MANUAL."&nbsp; <input name=\"cups_bracket_de_manual\" type=\"radio\" value=\"1\""; if ($ar['cups_bracket_de_manual'] == 1){ echo "checked";} echo "></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_PUBLISH.": </strong></td>\n";
			echo "		<td><input name=\"cups_bracket_publish\" type=\"checkbox\" value=\"1\""; if ($ar['cups_bracket_publish'] == 1 || $_GET['action'] == "add"){ echo "checked";} echo "></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_GAME.": </strong></td>\n";
			echo "		<td><select name=\"cups_bracket_cup_game\">\n";
						$res_cg = mysql_query("SELECT clan_games_id, clan_games_game FROM $db_clan_games ORDER BY clan_games_game") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						echo "<option value=\"0\">"._CUPS_BRACKETS_SELECT_GAME."</option>";
						while($ar_cg = mysql_fetch_array($res_cg)){
							echo "<option value=\"".$ar_cg['clan_games_id']."\""; if ($ar_cg['clan_games_id'] == $ar['cups_bracket_cup_game']){echo "selected=\"selected\"";} echo ">".$ar_cg['clan_games_game']."</option>\n";
				 		}
			echo "		</select></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td width=\"250\" align=\"right\" valign=\"top\"><strong>"._CUPS_BRACKETS_COMMENT.": </strong></td>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"40\" rows=\"6\" name=\"cups_bracket_cup_comment\">".stripslashes($ar['cups_bracket_cup_comment'])."</textarea></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"900\" border=\"0\" bordercolor=\"#000000\" cellpadding=\"4\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" valign=\"top\"><strong>WB Round 1</strong></td>\n";
		echo "		<td class=\"cups_text\" valign=\"top\"><strong>WB Round 2</strong></td>\n";
		echo "		<td class=\"cups_text\" valign=\"top\"><strong>WB Round 3</strong></td>\n";
		echo "		<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "8se"){ echo "<strong>WB Winner</strong>"; } else {echo "<strong>WB Round 4</strong>";} echo "</td>\n";
		echo "		<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se"){ echo "<strong>WB Winner</strong>"; }elseif ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){echo "<strong>WB Round 5</strong>";} echo "</td>\n";
		echo "		<td class=\"cups_text\" valign=\"top\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "<strong>WB Winner</strong>"; } else {echo "&nbsp;";} echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>";
		echo "		<td class=\"cups_text\"><select name=\"wb1_map\">\n";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($wb_map[0] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[0]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
		 	}
		echo "			</select>\n";
		echo "		</td>\n";
		echo "		<td class=\"cups_text\"><select name=\"wb2_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($wb_map[1] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
	   		while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[1]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
		echo "			</select>\n";
		echo "		</td>\n";
		echo "		<td class=\"cups_text\"><select name=\"wb3_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($wb_map[2] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[2]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
		 	}
		echo "			</select>\n";
		echo "		</td>\n";
		echo "		<td class=\"cups_text\">\n";
		if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ 
			echo "<select name=\"wb4_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($wb_map[3] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[3]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
			echo "</select>\n";
		}
		echo "&nbsp;\n";
		echo "		</td>\n";
		echo "		<td class=\"cups_text\">\n";
		if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ 
			echo "<select name=\"wb5_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($wb_map[4] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[4]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
			echo "</select>\n";
		} 
		echo "&nbsp;\n";
		echo "		</td>\n";
		echo "		<td class=\"cups_text\"><!--"; 
		if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){
			echo "<select name=\"wb6_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "				<option value=\"\""; if ($wb_map[5] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $wb_map[5]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
			echo "</select>"; 
		} else {
			echo "&nbsp;";
		}
		echo "-->\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"20\" name=\"cups_bracket_wb1_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb1_info'])."</textarea></td>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"20\" name=\"cups_bracket_wb2_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb2_info'])."</textarea></td>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"20\" name=\"cups_bracket_wb3_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb3_info'])."</textarea></td>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"20\" name=\"cups_bracket_wb4_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb4_info'])."</textarea></td>\n";
		echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "<textarea cols=\"20\" name=\"cups_bracket_wb5_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb5_info'])."</textarea>"; } echo "</td>\n";
		echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "<textarea cols=\"20\" name=\"cups_bracket_wb6_info\" rows=\"5\">".stripslashes($ar['cups_bracket_wb6_info'])."</textarea>"; } else {echo "&nbsp;";} echo "</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_1a_score\" value=\"".$ar['cups_bracket_w1_1a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_01\" value=\"".$ar['cups_bracket_team_01']."\"></td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">ID - 1</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_1a_score\" value=\"".$ar['cups_bracket_w2_1a_score']."\"> ".$w1."</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_1b_score\" value=\"".$ar['cups_bracket_w1_1b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_02\" value=\"".$ar['cups_bracket_team_02']."\"></td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">	&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">ID - 17</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_1a_score\" value=\"".$ar['cups_bracket_w3_1a_score']."\"> ".$w17."</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_2a_score\" value=\"".$ar['cups_bracket_w1_2a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_03\" value=\"".$ar['cups_bracket_team_03']."\"></td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">ID - 2</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_1b_score\" value=\"".$ar['cups_bracket_w2_1b_score']."\"> ".$w2."</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_2b_score\" value=\"".$ar['cups_bracket_w1_2b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_04\" value=\"".$ar['cups_bracket_team_04']."\"></td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">ID - 25</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\">"; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w4_1a_score\" value=\"".$ar['cups_bracket_w4_1a_score']."\">"; } echo $w25."</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_3a_score\" value=\"".$ar['cups_bracket_w1_3a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_05\" value=\"".$ar['cups_bracket_team_05']."\"></td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">ID - 3</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_2a_score\" value=\"".$ar['cups_bracket_w2_2a_score']."\"> ".$w3."</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_3b_score\" value=\"".$ar['cups_bracket_w1_3b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_06\" value=\"".$ar['cups_bracket_team_06']."\"></td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">	&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">ID - 18</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_1b_score\" value=\"".$ar['cups_bracket_w3_1b_score']."\"> ".$w18."</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_4a_score\" value=\"".$ar['cups_bracket_w1_4a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_07\" value=\"".$ar['cups_bracket_team_07']."\"></td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">ID - 4</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_2b_score\" value=\"".$ar['cups_bracket_w2_2b_score']."\"> ".$w4."</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_4b_score\" value=\"".$ar['cups_bracket_w1_4b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_08\" value=\"".$ar['cups_bracket_team_08']."\"></td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 29</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\">"; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w5_1a_score\" value=\"".$ar['cups_bracket_w5_1a_score']."\">"; } echo $w29."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_5a_score\" value=\"".$ar['cups_bracket_w1_5a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_09\" value=\"".$ar['cups_bracket_team_09']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 5</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_3a_score\" value=\"".$ar['cups_bracket_w2_3a_score']."\"> ".$w5."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_5b_score\" value=\"".$ar['cups_bracket_w1_5b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_10\" value=\"".$ar['cups_bracket_team_10']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 19</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_2a_score\" value=\"".$ar['cups_bracket_w3_2a_score']."\"> ".$w19."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_6a_score\" value=\"".$ar['cups_bracket_w1_6a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_11\" value=\"".$ar['cups_bracket_team_11']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 6</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_3b_score\" value=\"".$ar['cups_bracket_w2_3b_score']."\"> ".$w6."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_6b_score\" value=\"".$ar['cups_bracket_w1_6b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_12\" value=\"".$ar['cups_bracket_team_12']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 26</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w4_1b_score\" value=\"".$ar['cups_bracket_w4_1b_score']."\"> ".$w26."</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_7a_score\" value=\"".$ar['cups_bracket_w1_7a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_13\" value=\"".$ar['cups_bracket_team_13']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 7</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_4a_score\" value=\"".$ar['cups_bracket_w2_4a_score']."\"> ".$w7."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_7b_score\" value=\"".$ar['cups_bracket_w1_7b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_14\" value=\"".$ar['cups_bracket_team_14']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 20</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_2b_score\" value=\"".$ar['cups_bracket_w3_2b_score']."\"> ".$w20."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_8a_score\" value=\"".$ar['cups_bracket_w1_8a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_15\" value=\"".$ar['cups_bracket_team_15']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 8</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_4b_score\" value=\"".$ar['cups_bracket_w2_4b_score']."\"> ".$w8."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de" || $cups_bracket_cup_type == "32se"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_8b_score\" value=\"".$ar['cups_bracket_w1_8b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_16\" value=\"".$ar['cups_bracket_team_16']."\"></td>\n";
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
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\">".$w31."</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_9a_score\" value=\"".$ar['cups_bracket_w1_9a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_17\" value=\"".$ar['cups_bracket_team_17']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 9</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_5a_score\" value=\"".$ar['cups_bracket_w2_5a_score']."\"> ".$w9."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_9b_score\" value=\"".$ar['cups_bracket_w1_9b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_18\" value=\"".$ar['cups_bracket_team_18']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 21</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_3a_score\" value=\"".$ar['cups_bracket_w3_3a_score']."\"> ".$w21."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_10a_score\" value=\"".$ar['cups_bracket_w1_10a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_19\" value=\"".$ar['cups_bracket_team_19']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 10</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_5b_score\" value=\"".$ar['cups_bracket_w2_5b_score']."\"> ".$w10."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_10b_score\" value=\"".$ar['cups_bracket_w1_10b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_20\" value=\"".$ar['cups_bracket_team_20']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 27</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w4_2a_score\" value=\"".$ar['cups_bracket_w4_2a_score']."\"> ".$w27."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_11a_score\" value=\"".$ar['cups_bracket_w1_11a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_21\" value=\"".$ar['cups_bracket_team_21']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 11</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_6a_score\" value=\"".$ar['cups_bracket_w2_6a_score']."\"> ".$w11."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_11b_score\" value=\"".$ar['cups_bracket_w1_11b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_22\" value=\"".$ar['cups_bracket_team_22']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 22</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_3b_score\" value=\"".$ar['cups_bracket_w3_3b_score']."\"> ".$w22."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_12a_score\" value=\"".$ar['cups_bracket_w1_12a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_23\" value=\"".$ar['cups_bracket_team_23']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 12</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_6b_score\" value=\"".$ar['cups_bracket_w2_6b_score']."\"> ".$w12."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_12b_score\" value=\"".$ar['cups_bracket_w1_12b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_24\" value=\"".$ar['cups_bracket_team_24']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 30</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w5_1b_score\" value=\"".$ar['cups_bracket_w5_1b_score']."\"> ".$w30."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_13a_score\" value=\"".$ar['cups_bracket_w1_13a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_25\" value=\"".$ar['cups_bracket_team_25']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 13</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_7a_score\" value=\"".$ar['cups_bracket_w2_7a_score']."\"> ".$w13."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_13b_score\" value=\"".$ar['cups_bracket_w1_13b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_26\" value=\"".$ar['cups_bracket_team_26']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 23</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_4a_score\" value=\"".$ar['cups_bracket_w3_4a_score']."\"> ".$w23."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_14a_score\" value=\"".$ar['cups_bracket_w1_14a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_27\" value=\"".$ar['cups_bracket_team_27']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 14</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_7b_score\" value=\"".$ar['cups_bracket_w2_7b_score']."\"> ".$w14."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_14b_score\" value=\"".$ar['cups_bracket_w1_14b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_28\" value=\"".$ar['cups_bracket_team_28']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 28</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w4_2b_score\" value=\"".$ar['cups_bracket_w4_2b_score']."\"> ".$w28."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_15a_score\" value=\"".$ar['cups_bracket_w1_15a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_29\" value=\"".$ar['cups_bracket_team_29']."\"></td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 15</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_8a_score\" value=\"".$ar['cups_bracket_w2_8a_score']."\"> ".$w15."</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_15b_score\" value=\"".$ar['cups_bracket_w1_15b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_30\" value=\"".$ar['cups_bracket_team_30']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">ID - 24</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w3_4b_score\" value=\"".$ar['cups_bracket_w3_4b_score']."\"> ".$w24."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_16a_score\" value=\"".$ar['cups_bracket_w1_16a_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_31\" value=\"".$ar['cups_bracket_team_31']."\"></td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">ID - 16</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w2_8b_score\" value=\"".$ar['cups_bracket_w2_8b_score']."\"> ".$w16."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_w1_16b_score\" value=\"".$ar['cups_bracket_w1_16b_score']."\"><input type=\"cups_text_forms\" maxlength=\"50\" size=\"20\" name=\"cups_bracket_team_32\" value=\"".$ar['cups_bracket_team_32']."\"></td>\n";
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
		if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
			echo "<table bordercolor=\"#000000\" width=\"1200\" border=0 cellpadding=0 cellspacing=0>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\"><strong>LB Round 1</strong></td>\n";
			echo "		<td class=\"cups_text\"><strong>LB Round 2</strong></td>\n";
			echo "		<td class=\"cups_text\"><strong>LB Round 3</strong></td>\n";
			echo "		<td class=\"cups_text\"><strong>LB Round 4</strong></td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "8de"){ echo "<strong>LB Winner</strong>";}elseif ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "<strong>LB Round 5</strong>";} echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){echo "<strong>LB Round 6</strong>";} echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16se" || $cups_bracket_cup_type == "16de"){echo "<strong>LB Winner</strong>"; }elseif ($cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){ echo "<strong>LB Round 7</strong>";} echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){echo "<strong>LB Round 8</strong>"; } echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32se" || $cups_bracket_cup_type == "32de"){echo "<strong>LB Winner</strong>"; } echo "&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\"><select name=\"lb1_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[0] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[0]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
			echo "			</select>\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\"><select name=\"lb2_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[1] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[1]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
			echo "			</select>\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\"><select name=\"lb3_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[2] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[2]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
			echo "			</select>\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\"><select name=\"lb4_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[3] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[3]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
			echo "			</select>\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">\n";
			if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ 
				echo "<select name=\"lb5_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[4] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[4]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
				echo "</select>\n";
			} 
			echo "&nbsp;\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">\n";
			if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ 
				echo "<select name=\"lb6_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[5] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[5]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
				echo "</select>\n";
			} 
			echo "&nbsp;\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">\n";
			if ($cups_bracket_cup_type == "32de"){ 
				echo "<select name=\"lb7_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[6] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[6]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
				echo "</select>\n";
			} 
			echo "&nbsp;\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">\n";
			if ($cups_bracket_cup_type == "32de"){
				echo "<select name=\"lb8_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[7] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[7]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
				echo "</select>\n";
			} 
			echo "&nbsp;\n";
			echo "		</td>\n";
			echo "		<td class=\"cups_text\"><!-- "; /* if ($cups_bracket_cup_type == "32de"){ */ echo "<select name=\"cups_bracket_lb9_map\">";
				$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "<option value=\"\""; if ($lb_map[8] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
				while($ar3 = mysql_fetch_array($res3)){
					echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $lb_map[8]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
				}
			echo "			</select> -->&nbsp;\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\"><textarea cols=\"15\" name=\"cups_bracket_lb1_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb1_info'])."</textarea></td>\n";
			echo "		<td class=\"cups_text\"><textarea cols=\"15\" name=\"cups_bracket_lb2_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb2_info'])."</textarea></td>\n";
			echo "		<td class=\"cups_text\"><textarea cols=\"15\" name=\"cups_bracket_lb3_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb3_info'])."</textarea></td>\n";
			echo "		<td class=\"cups_text\"><textarea cols=\"15\" name=\"cups_bracket_lb4_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb4_info'])."</textarea></td>\n";
			echo "		<td class=\"cups_text\"><textarea cols=\"15\" name=\"cups_bracket_lb5_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb5_info'])."</textarea></td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "<textarea cols=\"15\" name=\"cups_bracket_lb6_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb6_info'])."</textarea>"; } echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "<textarea cols=\"15\" name=\"cups_bracket_lb7_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb7_info'])."</textarea>"; } echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ echo "<textarea cols=\"15\" name=\"cups_bracket_lb8_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb8_info'])."</textarea>"; } echo "&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">"; if ($cups_bracket_cup_type == "32de"){ echo "<textarea cols=\"15\" name=\"cups_bracket_lb9_info\" rows=\"5\">".stripslashes($ar['cups_bracket_lb9_info'])."</textarea>"; } echo "&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_1a_score\" value=\""; $l1_1a_team = explode("###", $ar['cups_bracket_l1_1a_score']); echo $l1_1a_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l1</strong></span> ";}
							echo $l1;
							echo "<input type=\"hidden\" name=\"cups_bracket_l1_1a_team\" value=\"".$l1."\"";
						} else {
							echo "			<select name=\"cups_bracket_l1_1a_team\">\n";
							echo "				<option value=\"".$l1."\""; if ($l1 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
							echo "				<option value=\"".$l2."\""; if ($l2 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
							echo "				<option value=\"".$l3."\""; if ($l3 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
							echo "				<option value=\"".$l4."\""; if ($l4 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
							echo "				<option value=\"".$l5."\""; if ($l5 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
							echo "				<option value=\"".$l6."\""; if ($l6 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
							echo "				<option value=\"".$l7."\""; if ($l7 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
							echo "				<option value=\"".$l8."\""; if ($l8 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
							echo "				<option value=\"".$l9."\""; if ($l9 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
							echo "				<option value=\"".$l10."\""; if ($l10 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
							echo "				<option value=\"".$l11."\""; if ($l11 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
							echo "				<option value=\"".$l12."\""; if ($l12 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
							echo "				<option value=\"".$l13."\""; if ($l13 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
							echo "				<option value=\"".$l14."\""; if ($l14 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
							echo "				<option value=\"".$l15."\""; if ($l15 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
							echo "				<option value=\"".$l16."\""; if ($l16 == $l1_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
							echo "			</select>\n";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" colspan=8>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=8>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 1</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_1a_score\" value=\""; $l2_1a_team = explode("###", $ar['cups_bracket_l2_1a_score']); echo $l2_1a_team[0]."\"> ".$lb1;
							if ($ar['cups_bracket_de_manual'] == 1){
								echo "<input type=\"hidden\" name=\"cups_bracket_l2_1a_team\" value=\"".$lb1."\">";
							}
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"5\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=7	>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_1b_score\" value=\""; $l1_1b_team = explode("###", $ar['cups_bracket_l1_1b_score']); echo $l1_1b_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l2</strong></span> ";}
							echo $l2;
							echo "<input type=\"hidden\" name=\"cups_bracket_l1_1b_team\" value=\"".$l2."\">";
						} else {
							echo "	<select name=\"cups_bracket_l1_1b_team\">\n";
							echo "		<option value=\"".$l1."\""; if ($l1 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
							echo "		<option value=\"".$l2."\""; if ($l2 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
							echo "		<option value=\"".$l3."\""; if ($l3 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
							echo "		<option value=\"".$l4."\""; if ($l4 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
							echo "		<option value=\"".$l5."\""; if ($l5 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
							echo "		<option value=\"".$l6."\""; if ($l6 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
							echo "		<option value=\"".$l7."\""; if ($l7 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
							echo "		<option value=\"".$l8."\""; if ($l8 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
							echo "		<option value=\"".$l9."\""; if ($l9 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
							echo "		<option value=\"".$l10."\""; if ($l10 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
							echo "		<option value=\"".$l11."\""; if ($l11 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
							echo "		<option value=\"".$l12."\""; if ($l12 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
							echo "		<option value=\"".$l13."\""; if ($l13 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
							echo "		<option value=\"".$l14."\""; if ($l14 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
							echo "		<option value=\"".$l15."\""; if ($l15 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
							echo "		<option value=\"".$l16."\""; if ($l16 == $l1_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
							echo "	</select>\n";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 9</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_1a_score\" value=\"".$ar['cups_bracket_l3_1a_score']."\"> ".$lb9."</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_1b_score\" value=\""; $l2_1b_team = explode("###", $ar['cups_bracket_l2_1b_score']); echo $l2_1b_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l17</strong></span> ";}
							echo $l17;
							echo "<input type=\"hidden\" name=\"cups_bracket_l2_1b_team\" value=\"".$l17."\">";
						} else {
							echo "	<select name=\"cups_bracket_l2_1b_team\">\n";
							echo "		<option value=\"".$l17."\""; if ($l17 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
							echo "		<option value=\"".$l18."\""; if ($l18 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
							echo "		<option value=\"".$l19."\""; if ($l19 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
							echo "		<option value=\"".$l20."\""; if ($l20 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
							echo "		<option value=\"".$l21."\""; if ($l21 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
							echo "		<option value=\"".$l22."\""; if ($l22 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
							echo "		<option value=\"".$l23."\""; if ($l23 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
							echo "		<option value=\"".$l24."\""; if ($l24 == $l2_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
							echo "	</select>\n";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=6	>&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_2a_score\" value=\""; $l1_2a_team = explode("###", $ar['cups_bracket_l1_2a_score']); echo $l1_2a_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l3</strong></span> ";}
							echo $l3;
							echo "<input type=\"hidden\" name=\"cups_bracket_l1_2a_team\" value=\"".$l3."\">";
						} else {
							echo "	<select name=\"cups_bracket_l1_2a_team\">\n";
							echo "		<option value=\"".$l1."\""; if ($l1 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
							echo "		<option value=\"".$l2."\""; if ($l2 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
							echo "		<option value=\"".$l3."\""; if ($l3 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
							echo "		<option value=\"".$l4."\""; if ($l4 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
							echo "		<option value=\"".$l5."\""; if ($l5 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
							echo "		<option value=\"".$l6."\""; if ($l6 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
							echo "		<option value=\"".$l7."\""; if ($l7 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
							echo "		<option value=\"".$l8."\""; if ($l8 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
							echo "		<option value=\"".$l9."\""; if ($l9 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
							echo "		<option value=\"".$l10."\""; if ($l10 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
							echo "		<option value=\"".$l11."\""; if ($l11 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
							echo "		<option value=\"".$l12."\""; if ($l12 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
							echo "		<option value=\"".$l13."\""; if ($l13 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
							echo "		<option value=\"".$l14."\""; if ($l14 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
							echo "		<option value=\"".$l15."\""; if ($l15 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
							echo "		<option value=\"".$l16."\""; if ($l16 == $l1_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
							echo "	</select>\n";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 17</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_1a_score\" value=\""; $l4_1a_team = explode("###", $ar['cups_bracket_l4_1a_score']); echo $l4_1a_team[0]."\"> ".$lb17;
						if ($ar['cups_bracket_de_manual'] == 1){
							echo "<input type=\"hidden\" name=\"cups_bracket_l4_1a_team\" value=\"".$lb17."\">";
						}
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"5\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" \n";
						if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ 
							echo "bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l6_1a_score\" value=\""; $l6_1a_team = explode("###", $ar['cups_bracket_l6_1a_score']); echo $l6_1a_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l29</strong></span> ";}
								echo $l29;
								echo "<input type=\"hidden\" name=\"cups_bracket_l6_1a_team\" value=\"".$l29."\">";
							} else {
							echo "	<select name=\"cups_bracket_l6_1a_team\">\n";
							echo "		<option value=\"".$l29."\""; if ($l29 == $l6_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l29."</option>\n";
							echo "		<option value=\"".$l30."\""; if ($l30 == $l6_1a_team[1]){echo "selected=\"selected\"";} echo ">".$l30."</option>\n";
							echo "	</select>\n";
							}
						} else {
							echo ">";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 2</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_2a_score\" value=\""; $l2_2a_team = explode("###", $ar['cups_bracket_l2_2a_score']); echo $l2_2a_team[0]."\"> ".$lb2;
						if ($ar['cups_bracket_de_manual'] == 1){
							echo "<input type=\"hidden\" name=\"cups_bracket_l2_2a_team\" value=\"".$lb2."\">";
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_2b_score\" value=\""; $l1_2b_team = explode("###", $ar['cups_bracket_l1_2b_score']); echo $l1_2b_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l4</strong></span> ";}
							echo $l4;
							echo "<input type=\"hidden\" name=\"cups_bracket_l1_2b_team\" value=\"".$l4."\">";
						} else {
							echo "	<select name=\"cups_bracket_l1_2b_team\">\n";
							echo "		<option value=\"".$l1."\""; if ($l1 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
							echo "		<option value=\"".$l2."\""; if ($l2 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
							echo "		<option value=\"".$l3."\""; if ($l3 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
							echo "		<option value=\"".$l4."\""; if ($l4 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
							echo "		<option value=\"".$l5."\""; if ($l5 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
							echo "		<option value=\"".$l6."\""; if ($l6 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
							echo "		<option value=\"".$l7."\""; if ($l7 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
							echo "		<option value=\"".$l8."\""; if ($l8 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
							echo "		<option value=\"".$l9."\""; if ($l9 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
							echo "		<option value=\"".$l10."\""; if ($l10 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
							echo "		<option value=\"".$l11."\""; if ($l11 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
							echo "		<option value=\"".$l12."\""; if ($l12 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
							echo "		<option value=\"".$l13."\""; if ($l13 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
							echo "		<option value=\"".$l14."\""; if ($l14 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
							echo "		<option value=\"".$l15."\""; if ($l15 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
							echo "		<option value=\"".$l16."\""; if ($l16 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
							echo "	</select>\n";
						}
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 10</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_1b_score\" value=\"".$ar['cups_bracket_l3_1b_score']."\"> ".$lb10."</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;ID - LB 21</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\">"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l5_1a_score\" value=\"".$ar['cups_bracket_l5_1a_score']."\"> "; } echo $lb21."</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l8_1a_score\" value=\"".$ar['cups_bracket_l8_1a_score']."\"> ".$l31.""; } else {echo "&nbsp;";} echo "</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_2b_score\" value=\""; $l2_2b_team = explode("###", $ar['cups_bracket_l2_2b_score']); echo $l2_2b_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l18</strong></span> ";}
							echo $l18;
							echo "<input type=\"hidden\" name=\"cups_bracket_l2_2b_team\" value=\"".$l18."\">";
						} else {
							echo " 	<select name=\"cups_bracket_l2_2b_team\">\n";
							echo " 		<option value=\"".$l17."\""; if ($l17 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
							echo " 		<option value=\"".$l18."\""; if ($l18 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
							echo " 		<option value=\"".$l19."\""; if ($l19 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
							echo " 		<option value=\"".$l20."\""; if ($l20 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
							echo " 		<option value=\"".$l21."\""; if ($l21 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
							echo " 		<option value=\"".$l22."\""; if ($l22 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
							echo " 		<option value=\"".$l23."\""; if ($l23 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
							echo " 		<option value=\"".$l24."\""; if ($l24 == $l2_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
							echo " 	</select>\n";
						}
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;"; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "ID - LB 27"; } echo "</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\""; } echo ">"; if ($cups_bracket_cup_type == "32de"){ echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l7_1a_score\" value=\"".$ar['cups_bracket_l7_1a_score']."\">"; } echo $lb27."</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\""; } echo ">\n";
						if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ 
							echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_3a_score\" value=\""; $l1_3a_team = explode("###", $ar['cups_bracket_l1_3a_score']); echo $l1_3a_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l5</strong></span> ";}
								echo $l5;
								echo "<input type=\"hidden\" name=\"cups_bracket_l1_3a_team\" value=\"".$l5."\">";
							} else {
								echo " 	<select name=\"cups_bracket_l1_3a_team\">\n";
								echo " 		<option value=\"".$l1."\""; if ($l1 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
								echo " 		<option value=\"".$l2."\""; if ($l2 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
								echo " 		<option value=\"".$l3."\""; if ($l3 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
								echo " 		<option value=\"".$l4."\""; if ($l4 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
								echo " 		<option value=\"".$l5."\""; if ($l5 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
								echo " 		<option value=\"".$l6."\""; if ($l6 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
								echo " 		<option value=\"".$l7."\""; if ($l7 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
								echo " 		<option value=\"".$l8."\""; if ($l8 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
								echo " 		<option value=\"".$l9."\""; if ($l9 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
								echo " 		<option value=\"".$l10."\""; if ($l10 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
								echo " 		<option value=\"".$l11."\""; if ($l11 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
								echo " 		<option value=\"".$l12."\""; if ($l12 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
								echo " 		<option value=\"".$l13."\""; if ($l13 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
								echo " 		<option value=\"".$l14."\""; if ($l14 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
								echo " 		<option value=\"".$l15."\""; if ($l15 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
								echo " 		<option value=\"".$l16."\""; if ($l16 == $l1_3a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
								echo " 	</select>\n";
							}
						} 
			echo "		</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_1b_score\" value=\""; $l4_1b_team = explode("###", $ar['cups_bracket_l4_1b_score']); echo $l4_1b_team[0]."\"> \n";
						if ($ar['cups_bracket_de_manual'] == 0){
							if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l25</strong></span> ";}
							echo $l25;
							echo "<input type=\"hidden\" name=\"cups_bracket_l4_1b_team\" value=\"".$l25."\">";
						} else {
							echo "	<select name=\"cups_bracket_l4_1b_team\">\n";
							echo "		<option value=\"".$l25."\""; if ($l25 == $l4_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l25."</option>\n";
							echo "		<option value=\"".$l26."\""; if ($l26 == $l4_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l26."</option>\n";
							echo "		<option value=\"".$l27."\""; if ($l27 == $l4_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l27."</option>\n";
							echo "		<option value=\"".$l28."\""; if ($l28 == $l4_1b_team[1]){echo "selected=\"selected\"";} echo ">".$l28."</option>\n";
							echo "	</select>\n";
						}
			echo "		</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
			echo "		<td class=\"cups_text\">&nbsp;</td>\n";
			echo "	</tr>\n";
			if ($cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 3</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_3a_score\" value=\""; $l2_3a_team = explode("###", $ar['cups_bracket_l2_3a_score']); echo $l2_3a_team[0]."\"> ".$lb3;
							if ($ar['cups_bracket_de_manual'] == 1){
								echo "<input type=\"hidden\" name=\"cups_bracket_l2_3a_team\" value=\"".$lb3."\">";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_3b_score\" value=\""; $l1_3b_team = explode("###", $ar['cups_bracket_l1_3b_score']); echo $l1_3b_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l6</strong></span> ";}
						   		echo $l6;
								echo "<input type=\"hidden\" name=\"cups_bracket_l1_3b_team\" value=\"".$l6."\">";
							} else {
								echo "	<select name=\"cups_bracket_l1_3b_team\">\n";
								echo "		<option value=\"".$l1."\""; if ($l1 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
								echo "		<option value=\"".$l2."\""; if ($l2 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
								echo "		<option value=\"".$l3."\""; if ($l3 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
								echo "		<option value=\"".$l4."\""; if ($l4 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
								echo "		<option value=\"".$l5."\""; if ($l5 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
								echo "		<option value=\"".$l6."\""; if ($l6 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
								echo "		<option value=\"".$l7."\""; if ($l7 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
								echo "		<option value=\"".$l8."\""; if ($l8 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
								echo "		<option value=\"".$l9."\""; if ($l9 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
								echo "		<option value=\"".$l10."\""; if ($l10 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
								echo "		<option value=\"".$l11."\""; if ($l11 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
								echo "		<option value=\"".$l12."\""; if ($l12 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
								echo "		<option value=\"".$l13."\""; if ($l13 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
								echo "		<option value=\"".$l14."\""; if ($l14 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
								echo "		<option value=\"".$l15."\""; if ($l15 == $l1_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
								echo "		<option value=\"".$l16."\""; if ($l16 == $l1_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
								echo "	</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 11</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_2a_score\" value=\"".$ar['cups_bracket_l3_2a_score']."\"> ".$lb11."</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 25</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l6_1b_score\" value=\"".$ar['cups_bracket_l6_1b_score']."\"> ".$lb25."</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;"; if ($cups_bracket_cup_type == "32de"){ echo "ID - LB 30"; } echo "</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\">".$lb30;} else {echo "&nbsp;";} echo "</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_3b_score\" value=\""; $l2_3b_team = explode("###", $ar['cups_bracket_l2_3b_score']); echo $l2_3b_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l19</strong></span> ";}
								echo $l19;
								echo "<input type=\"hidden\" name=\"cups_bracket_l2_3b_team\" value=\"".$l19."\">";
							} else {
								echo "	<select name=\"cups_bracket_l2_3b_team\">\n";
								echo "		<option value=\"".$l17."\""; if ($l17 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
								echo "		<option value=\"".$l18."\""; if ($l18 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
								echo "		<option value=\"".$l19."\""; if ($l19 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
								echo "		<option value=\"".$l20."\""; if ($l20 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
								echo "		<option value=\"".$l21."\""; if ($l21 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
								echo "		<option value=\"".$l22."\""; if ($l22 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
								echo "		<option value=\"".$l23."\""; if ($l23 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
								echo "		<option value=\"".$l24."\""; if ($l24 == $l2_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
								echo "	</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_4a_score\" value=\""; $l1_4a_team = explode("###", $ar['cups_bracket_l1_4a_score']); echo $l1_4a_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l7</strong></span> ";}
								echo $l7;
								echo "<input type=\"hidden\" name=\"cups_bracket_l1_4a_team\" value=\"".$l7."\">";
							} else {
								echo "	<select name=\"cups_bracket_l1_4a_team\">\n";
								echo "		<option value=\"".$l1."\""; if ($l1 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
								echo "		<option value=\"".$l2."\""; if ($l2 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
								echo "		<option value=\"".$l3."\""; if ($l3 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
								echo "		<option value=\"".$l4."\""; if ($l4 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
								echo "		<option value=\"".$l5."\""; if ($l5 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
								echo "		<option value=\"".$l6."\""; if ($l6 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
								echo "		<option value=\"".$l7."\""; if ($l7 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
								echo "		<option value=\"".$l8."\""; if ($l8 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
								echo "		<option value=\"".$l9."\""; if ($l9 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
								echo "		<option value=\"".$l10."\""; if ($l10 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
								echo "		<option value=\"".$l11."\""; if ($l11 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
								echo "		<option value=\"".$l12."\""; if ($l12 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
								echo "		<option value=\"".$l13."\""; if ($l13 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
								echo "		<option value=\"".$l14."\""; if ($l14 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
								echo "		<option value=\"".$l15."\""; if ($l15 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
								echo "		<option value=\"".$l16."\""; if ($l16 == $l1_4a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
								echo "	</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 18</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_2a_score\" value=\""; $l4_2a_team = explode("###", $ar['cups_bracket_l4_2a_score']); echo $l4_2a_team[0]."\"> ".$lb18;
							if ($ar['cups_bracket_de_manual'] == 1){
								echo "<input type=\"hidden\" name=\"cups_bracket_l4_2a_team\" value=\"".$lb18."\">";
							} 
				echo "		</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 4</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_4a_score\" value=\""; $l2_4a_team = explode("###", $ar['cups_bracket_l2_4a_score']); echo $l2_4a_team[0]."\"> ".$lb4;
							if ($ar['cups_bracket_de_manual'] == 1){
								echo "<input type=\"hidden\" name=\"cups_bracket_l2_4a_team\" value=\"".$lb4."\">";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_4b_score\" value=\""; $l1_4b_team = explode("###", $ar['cups_bracket_l1_4b_score']); echo $l1_4b_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l8</strong></span> ";}
								echo $l8;
								echo "<input type=\"hidden\" name=\"cups_bracket_l1_4b_team\" value=\"".$l8."\">";
							} else {
								echo "<select name=\"cups_bracket_l1_4b_team\">\n";
								echo "	<option value=\"".$l1."\""; if ($l1 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
								echo "	<option value=\"".$l2."\""; if ($l2 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
								echo "	<option value=\"".$l3."\""; if ($l3 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
								echo "	<option value=\"".$l4."\""; if ($l4 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
								echo "	<option value=\"".$l5."\""; if ($l5 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
								echo "	<option value=\"".$l6."\""; if ($l6 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
								echo "	<option value=\"".$l7."\""; if ($l7 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
								echo "	<option value=\"".$l8."\""; if ($l8 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
								echo "	<option value=\"".$l9."\""; if ($l9 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
								echo "	<option value=\"".$l10."\""; if ($l10 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
								echo "	<option value=\"".$l11."\""; if ($l11 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
								echo "	<option value=\"".$l12."\""; if ($l12 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
								echo "	<option value=\"".$l13."\""; if ($l13 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
								echo "	<option value=\"".$l14."\""; if ($l14 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
								echo "	<option value=\"".$l15."\""; if ($l15 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
								echo "	<option value=\"".$l16."\""; if ($l16 == $l1_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
								echo "</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 12</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_2b_score\" value=\"".$ar['cups_bracket_l3_2b_score']."\"> ".$lb12."</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;ID - LB 22</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l5_1b_score\" value=\"".$ar['cups_bracket_l5_1b_score']."\"> ".$lb22."</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_4b_score\" value=\""; $l2_4b_team = explode("###", $ar['cups_bracket_l2_4b_score']); echo $l2_4b_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l20</strong></span> ";}
								echo $l20;
								echo "<input type=\"hidden\" name=\"cups_bracket_l2_4b_team\" value=\"".$l20."\">";
							} else {
								echo "<select name=\"cups_bracket_l2_4b_team\">\n";
								echo "	<option value=\"".$l17."\""; if ($l17 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
								echo "	<option value=\"".$l18."\""; if ($l18 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
								echo "	<option value=\"".$l19."\""; if ($l19 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
								echo "	<option value=\"".$l20."\""; if ($l20 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
								echo "	<option value=\"".$l21."\""; if ($l21 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
								echo "	<option value=\"".$l22."\""; if ($l22 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
								echo "	<option value=\"".$l23."\""; if ($l23 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
								echo "		<option value=\"".$l24."\""; if ($l24 == $l2_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
								echo "	</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;"; if ($cups_bracket_cup_type == "32de"){ echo "ID - LB 29"; } echo "</td>\n";
				echo "		<td class=\"cups_text\" "; if ($cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l8_1b_score\" value=\"".$ar['cups_bracket_l8_1b_score']."\"> ".$lb29;} else {echo "&nbsp;";} echo "</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "		<td class=\"cups_text\" "; if ($cups_bracket_cup_type == "32de"){ echo "bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_5a_score\" value=\""; $l1_5a_team = explode("#", $ar['cups_bracket_l1_5a_score']); echo $l1_5a_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l9</strong></span> ";}
								echo $l9;
								echo "<input type=\"hidden\" name=\"cups_bracket_l1_5a_team\" value=\"".$l9."\">";
							} else {
								echo "	<select name=\"cups_bracket_l1_5a_team\">\n";
								echo "		<option value=\"".$l1."\""; if ($l1 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
								echo "		<option value=\"".$l2."\""; if ($l2 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
								echo "		<option value=\"".$l3."\""; if ($l3 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
								echo "		<option value=\"".$l4."\""; if ($l4 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
								echo "		<option value=\"".$l5."\""; if ($l5 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
								echo "		<option value=\"".$l6."\""; if ($l6 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
								echo "		<option value=\"".$l7."\""; if ($l7 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
								echo "		<option value=\"".$l8."\""; if ($l8 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
								echo "		<option value=\"".$l9."\""; if ($l9 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
								echo "		<option value=\"".$l10."\""; if ($l10 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
								echo "		<option value=\"".$l11."\""; if ($l11 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
								echo "		<option value=\"".$l12."\""; if ($l12 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
								echo "		<option value=\"".$l13."\""; if ($l13 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
								echo "		<option value=\"".$l14."\""; if ($l14 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
								echo "		<option value=\"".$l15."\""; if ($l15 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
								echo "		<option value=\"".$l16."\""; if ($l16 == $l1_5a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
								echo "	</select>\n";
							}
						} else {
							echo ">";
						}
				echo "	</td>\n";
				echo "	<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_2b_score\" value=\""; $l4_2b_team = explode("###", $ar['cups_bracket_l4_2b_score']); echo $l4_2b_team[0]."\"> \n";
							if ($ar['cups_bracket_de_manual'] == 0){
								if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l26</strong></span> ";}
								echo $l26;
								echo "<input type=\"hidden\" name=\"cups_bracket_l4_2b_team\" value=\"".$l26."\">";
							} else {
								echo "	<select name=\"cups_bracket_l4_2b_team\">\n";
								echo "		<option value=\"".$l25."\""; if ($l25 == $l4_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l25."</option>\n";
								echo "		<option value=\"".$l26."\""; if ($l26 == $l4_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l26."</option>\n";
								echo "		<option value=\"".$l27."\""; if ($l27 == $l4_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l27."</option>\n";
								echo "		<option value=\"".$l28."\""; if ($l28 == $l4_2b_team[1]){echo "selected=\"selected\"";} echo ">".$l28."</option>\n";
								echo "	</select>\n";
							}
				echo "		</td>\n";
				echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\""; if ($cups_bracket_cup_type == "32de"){ echo "style=\"border-width: 1px; border-right-style: solid;\""; } echo ">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "		<td class=\"cups_text\">&nbsp;</td>\n";
				echo "	</tr>\n";
				}
				if ($cups_bracket_cup_type == "32de"){
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"5\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 5</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_5a_score\" value=\""; $l2_5a_team = explode("###", $ar['cups_bracket_l2_5a_score']); echo $l2_5a_team[0]."\"> ".$lb5;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_5a_team\" value=\"".$lb5."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"4\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"4\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_5b_score\" value=\""; $l1_5b_team = explode("###", $ar['cups_bracket_l1_5b_score']); echo $l1_5b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l10</strong></span> ";}
									echo $l10;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_5b_team\" value=\"".$l10."\">";
								} else {
									echo "	<select name=\"cups_bracket_l1_5b_team\">\n";
									echo "		<option value=\"".$l1."\""; if ($l1 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "		<option value=\"".$l2."\""; if ($l2 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "		<option value=\"".$l3."\""; if ($l3 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "		<option value=\"".$l4."\""; if ($l4 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "		<option value=\"".$l5."\""; if ($l5 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "		<option value=\"".$l6."\""; if ($l6 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "		<option value=\"".$l7."\""; if ($l7 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "		<option value=\"".$l8."\""; if ($l8 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "		<option value=\"".$l9."\""; if ($l9 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "		<option value=\"".$l10."\""; if ($l10 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "		<option value=\"".$l11."\""; if ($l11 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "		<option value=\"".$l12."\""; if ($l12 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "		<option value=\"".$l13."\""; if ($l13 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "		<option value=\"".$l14."\""; if ($l14 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "		<option value=\"".$l15."\""; if ($l15 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "		<option value=\"".$l16."\""; if ($l16 == $l1_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "	</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 13</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_3a_score\" value=\"".$ar['cups_bracket_l3_3a_score']."\"> ".$lb13."</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_5b_score\" value=\""; $l2_5b_team = explode("###", $ar['cups_bracket_l2_5b_score']); echo $l2_5b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l21</strong></span> ";}
									echo $l21;
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_5b_team\" value=\"".$l21."\">";
								} else {
									echo "	<select name=\"cups_bracket_l2_5b_team\">\n";
									echo "		<option value=\"".$l17."\""; if ($l17 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
									echo "		<option value=\"".$l18."\""; if ($l18 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
									echo "		<option value=\"".$l19."\""; if ($l19 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
									echo "		<option value=\"".$l20."\""; if ($l20 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
									echo "		<option value=\"".$l21."\""; if ($l21 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
									echo "		<option value=\"".$l22."\""; if ($l22 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
									echo "		<option value=\"".$l23."\""; if ($l23 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
									echo "		<option value=\"".$l24."\""; if ($l24 == $l2_5b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
									echo "	</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_6a_score\" value=\""; $l1_6a_team = explode("###", $ar['cups_bracket_l1_6a_score']); echo $l1_6a_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l11</strong></span> ";}
									echo $l11;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_6a_team\" value=\"".$l11."\">";
								} else {
									echo "	<select name=\"cups_bracket_l1_6a_team\">\n";
									echo "		<option value=\"".$l1."\""; if ($l1 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "		<option value=\"".$l2."\""; if ($l2 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "		<option value=\"".$l3."\""; if ($l3 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "		<option value=\"".$l4."\""; if ($l4 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "		<option value=\"".$l5."\""; if ($l5 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "		<option value=\"".$l6."\""; if ($l6 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "		<option value=\"".$l7."\""; if ($l7 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "		<option value=\"".$l8."\""; if ($l8 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "		<option value=\"".$l9."\""; if ($l9 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "		<option value=\"".$l10."\""; if ($l10 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "		<option value=\"".$l11."\""; if ($l11 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "		<option value=\"".$l12."\""; if ($l12 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "		<option value=\"".$l13."\""; if ($l13 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "		<option value=\"".$l14."\""; if ($l14 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "		<option value=\"".$l15."\""; if ($l15 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "		<option value=\"".$l16."\""; if ($l16 == $l1_6a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "	</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 19</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_3a_score\" value=\""; $l4_3a_team = explode("###", $ar['cups_bracket_l4_3a_score']); echo $l4_3a_team[0]."\"> ".$lb19;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l4_3a_team\" value=\"".$lb19."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l6_2a_score\" value=\""; $l6_2a_team = explode("###", $ar['cups_bracket_l6_2a_score']); echo $l6_2a_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l30</strong></span> ";}
									echo $l30;
									echo "<input type=\"hidden\" name=\"cups_bracket_l6_2a_team\" value=\"".$l30."\">";
								} else {
									echo "<select name=\"cups_bracket_l6_2a_team\">\n";
									echo "	<option value=\"".$l29."\""; if ($l29 == $l6_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l29."</option>\n";
									echo "	<option value=\"".$l30."\""; if ($l30 == $l6_2a_team[1]){echo "selected=\"selected\"";} echo ">".$l30."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 6</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_6a_score\" value=\""; $l2_6a_team = explode("###", $ar['cups_bracket_l2_6a_score']); echo $l2_6a_team[0]."\"> ".$lb6;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_6a_team\" value=\"".$lb6."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_6b_score\" value=\""; $l1_6b_team = explode("###", $ar['cups_bracket_l1_6b_score']); echo $l1_6b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l12</strong></span> ";}
									echo $l12;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_6b_team\" value=\"".$l12."\">";
								} else {
									echo "<select name=\"cups_bracket_l1_6b_team\">\n";
									echo "	<option value=\"".$l1."\""; if ($l1 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "	<option value=\"".$l2."\""; if ($l2 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "	<option value=\"".$l3."\""; if ($l3 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "	<option value=\"".$l4."\""; if ($l4 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "	<option value=\"".$l5."\""; if ($l5 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "	<option value=\"".$l6."\""; if ($l6 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "	<option value=\"".$l7."\""; if ($l7 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "	<option value=\"".$l8."\""; if ($l8 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "	<option value=\"".$l9."\""; if ($l9 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "	<option value=\"".$l10."\""; if ($l10 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "	<option value=\"".$l11."\""; if ($l11 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "	<option value=\"".$l12."\""; if ($l12 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "	<option value=\"".$l13."\""; if ($l13 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "	<option value=\"".$l14."\""; if ($l14 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "	<option value=\"".$l15."\""; if ($l15 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "	<option value=\"".$l16."\""; if ($l16 == $l1_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 14</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_3b_score\" value=\"".$ar['cups_bracket_l3_3b_score']."\"> ".$lb14."</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 23</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l5_2a_score\" value=\"".$ar['cups_bracket_l5_2a_score']."\"> ".$lb23."</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_6b_score\" value=\""; $l2_6b_team = explode("###", $ar['cups_bracket_l2_6b_score']); echo $l2_6b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l22</strong></span> ";}
									echo $l22;
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_6b_team\" value=\"".$l22."\">";
								} else {
									echo "<select name=\"cups_bracket_l2_6b_team\">\n";
									echo "	<option value=\"".$l17."\""; if ($l17 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
									echo "	<option value=\"".$l18."\""; if ($l18 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
									echo "	<option value=\"".$l19."\""; if ($l19 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
									echo "	<option value=\"".$l20."\""; if ($l20 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
									echo "	<option value=\"".$l21."\""; if ($l21 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
									echo "	<option value=\"".$l22."\""; if ($l22 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
									echo "	<option value=\"".$l23."\""; if ($l23 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
									echo "	<option value=\"".$l24."\""; if ($l24 == $l2_6b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 28</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l7_1b_score\" value=\"".$ar['cups_bracket_l7_1b_score']."\"> ".$lb28."</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_7a_score\" value=\""; $l1_7a_team = explode("###", $ar['cups_bracket_l1_7a_score']); echo $l1_7a_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l13</strong></span> ";}
									echo $l13;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_7a_team\" value=\"".$l13."\">";
								} else {
									echo "<select name=\"cups_bracket_l1_7a_team\">\n";
									echo "	<option value=\"".$l1."\""; if ($l1 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "	<option value=\"".$l2."\""; if ($l2 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "	<option value=\"".$l3."\""; if ($l3 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "	<option value=\"".$l4."\""; if ($l4 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "	<option value=\"".$l5."\""; if ($l5 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "	<option value=\"".$l6."\""; if ($l6 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "	<option value=\"".$l7."\""; if ($l7 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "	<option value=\"".$l8."\""; if ($l8 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "	<option value=\"".$l9."\""; if ($l9 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "	<option value=\"".$l10."\""; if ($l10 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "	<option value=\"".$l11."\""; if ($l11 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "	<option value=\"".$l12."\""; if ($l12 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "	<option value=\"".$l13."\""; if ($l13 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "	<option value=\"".$l14."\""; if ($l14 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "	<option value=\"".$l15."\""; if ($l15 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "	<option value=\"".$l16."\""; if ($l16 == $l1_7a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_3b_score\" value=\""; $l4_3b_team = explode("###", $ar['cups_bracket_l4_3b_score']); echo $l4_3b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l27</strong></span> ";}
									echo $l27;
									echo "<input type=\"hidden\" name=\"cups_bracket_l4_3b_team\" value=\"".$l27."\">";
								} else {
									echo "<select name=\"cups_bracket_l4_3b_team\">\n";
									echo "	<option value=\"".$l25."\""; if ($l25 == $l4_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l25."</option>\n";
									echo "	<option value=\"".$l26."\""; if ($l26 == $l4_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l26."</option>\n";
									echo "	<option value=\"".$l27."\""; if ($l27 == $l4_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l27."</option>\n";
									echo "	<option value=\"".$l28."\""; if ($l28 == $l4_3b_team[1]){echo "selected=\"selected\"";} echo ">".$l28."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 7</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_7a_score\" value=\""; $l2_7a_team = explode("###", $ar['cups_bracket_l2_7a_score']); echo $l2_7a_team[0]."\"> ".$lb7;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_7a_team\" value=\"".$lb7."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_7b_score\" value=\""; $l1_7b_team = explode("###", $ar['cups_bracket_l1_7b_score']); echo $l1_7b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l14</strong></span> ";}
									echo $l14;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_7b_team\" value=\"".$l14."\">";
								} else {
									echo "<select name=\"cups_bracket_l1_7b_team\">\n";
									echo "	<option value=\"".$l1."\""; if ($l1 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "	<option value=\"".$l2."\""; if ($l2 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "	<option value=\"".$l3."\""; if ($l3 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "	<option value=\"".$l4."\""; if ($l4 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "	<option value=\"".$l5."\""; if ($l5 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "	<option value=\"".$l6."\""; if ($l6 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "	<option value=\"".$l7."\""; if ($l7 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "	<option value=\"".$l8."\""; if ($l8 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "	<option value=\"".$l9."\""; if ($l9 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "	<option value=\"".$l10."\""; if ($l10 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "	<option value=\"".$l11."\""; if ($l11 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "	<option value=\"".$l12."\""; if ($l12 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "	<option value=\"".$l13."\""; if ($l13 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "	<option value=\"".$l14."\""; if ($l14 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "	<option value=\"".$l15."\""; if ($l15 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "	<option value=\"".$l16."\""; if ($l16 == $l1_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 15</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_4a_score\" value=\"".$ar['cups_bracket_l3_4a_score']."\"> ".$lb15."</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 26</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l6_2b_score\" value=\"".$ar['cups_bracket_l6_2b_score']."\"> ".$lb26."</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_7b_score\" value=\""; $l2_7b_team = explode("###", $ar['cups_bracket_l2_7b_score']); echo $l2_7b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l23</strong></span> ";}
									echo $l23;
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_7b_team\" value=\"".$l23."\">";
								} else {
									echo "<select name=\"cups_bracket_l2_7b_team\">\n";
									echo "	<option value=\"".$l17."\""; if ($l17 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
									echo "	<option value=\"".$l18."\""; if ($l18 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
									echo "	<option value=\"".$l19."\""; if ($l19 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
									echo "	<option value=\"".$l20."\""; if ($l20 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
									echo "	<option value=\"".$l21."\""; if ($l21 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
									echo "	<option value=\"".$l22."\""; if ($l22 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
									echo "	<option value=\"".$l23."\""; if ($l23 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
									echo "	<option value=\"".$l24."\""; if ($l24 == $l2_7b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_8a_score\" value=\""; $l1_8a_team = explode("###", $ar['cups_bracket_l1_8a_score']); echo $l1_8a_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l15</strong></span> ";}
									echo $l15;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_8a_team\" value=\"".$l15."\">";
								} else {
									echo "<select name=\"cups_bracket_l1_8a_team\">\n";
									echo "	<option value=\"".$l1."\""; if ($l1 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "	<option value=\"".$l2."\""; if ($l2 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "	<option value=\"".$l3."\""; if ($l3 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "	<option value=\"".$l4."\""; if ($l4 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "	<option value=\"".$l5."\""; if ($l5 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "	<option value=\"".$l6."\""; if ($l6 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "	<option value=\"".$l7."\""; if ($l7 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "	<option value=\"".$l8."\""; if ($l8 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "	<option value=\"".$l9."\""; if ($l9 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "	<option value=\"".$l10."\""; if ($l10 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "	<option value=\"".$l11."\""; if ($l11 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "	<option value=\"".$l12."\""; if ($l12 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "	<option value=\"".$l13."\""; if ($l13 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "	<option value=\"".$l14."\""; if ($l14 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "	<option value=\"".$l15."\""; if ($l15 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "	<option value=\"".$l16."\""; if ($l16 == $l1_8a_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 20</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_4a_score\" value=\""; $l4_4a_team = explode("###", $ar['cups_bracket_l4_4a_score']); echo $l4_4a_team[0]."\"> ".$lb20;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l4_4a_team\" value=\"".$lb20."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 8</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_8a_score\" value=\""; $l2_8a_team = explode("###", $ar['cups_bracket_l2_8a_score']); echo $l2_8a_team[0]."\"> ".$lb8;
								if ($ar['cups_bracket_de_manual'] == 1){
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_8a_team\" value=\"".$lb8."\">";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l1_8b_score\" value=\""; $l1_8b_team = explode("###", $ar['cups_bracket_l1_8b_score']); echo $l1_8b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l16</strong></span> ";}
									echo $l16;
									echo "<input type=\"hidden\" name=\"cups_bracket_l1_8b_team\" value=\"".$l16."\">";
								} else {
									echo "<select name=\"cups_bracket_l1_8b_team\">\n";
									echo "	<option value=\"".$l1."\""; if ($l1 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l1."</option>\n";
									echo "	<option value=\"".$l2."\""; if ($l2 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l2."</option>\n";
									echo "	<option value=\"".$l3."\""; if ($l3 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l3."</option>\n";
									echo "	<option value=\"".$l4."\""; if ($l4 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l4."</option>\n";
									echo "	<option value=\"".$l5."\""; if ($l5 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l5."</option>\n";
									echo "	<option value=\"".$l6."\""; if ($l6 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l6."</option>\n";
									echo "	<option value=\"".$l7."\""; if ($l7 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l7."</option>\n";
									echo "	<option value=\"".$l8."\""; if ($l8 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l8."</option>\n";
									echo "	<option value=\"".$l9."\""; if ($l9 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l9."</option>\n";
									echo "	<option value=\"".$l10."\""; if ($l10 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l10."</option>\n";
									echo "	<option value=\"".$l11."\""; if ($l11 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l11."</option>\n";
									echo "	<option value=\"".$l12."\""; if ($l12 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l12."</option>\n";
									echo "	<option value=\"".$l13."\""; if ($l13 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l13."</option>\n";
									echo "	<option value=\"".$l14."\""; if ($l14 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l14."</option>\n";
									echo "	<option value=\"".$l15."\""; if ($l15 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l15."</option>\n";
									echo "	<option value=\"".$l16."\""; if ($l16 == $l1_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l16."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 16</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l3_4b_score\" value=\"".$ar['cups_bracket_l3_4b_score']."\"> ".$lb16."</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;ID - LB 24</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l5_2b_score\" value=\"".$ar['cups_bracket_l5_2b_score']."\"> ".$lb24."</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l2_8b_score\" value=\""; $l2_8b_team = explode("###", $ar['cups_bracket_l2_8b_score']); echo $l2_8b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l24</strong></span> ";}
									echo $l24;
									echo "<input type=\"hidden\" name=\"cups_bracket_l2_8b_team\" value=\"".$l24."\">";
								} else {
									echo "<select name=\"cups_bracket_l2_8b_team\">\n";
									echo "	<option value=\"".$l17."\""; if ($l17 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l17."</option>\n";
									echo "	<option value=\"".$l18."\""; if ($l18 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l18."</option>\n";
									echo "	<option value=\"".$l19."\""; if ($l19 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l19."</option>\n";
									echo "	<option value=\"".$l20."\""; if ($l20 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l20."</option>\n";
									echo "	<option value=\"".$l21."\""; if ($l21 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l21."</option>\n";
									echo "	<option value=\"".$l22."\""; if ($l22 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l22."</option>\n";
									echo "	<option value=\"".$l23."\""; if ($l23 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l23."</option>\n";
									echo "	<option value=\"".$l24."\""; if ($l24 == $l2_8b_team[1]){echo "selected=\"selected\"";} echo ">".$l24."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_l4_4b_score\" value=\""; $l4_4b_team = explode("###", $ar['cups_bracket_l4_4b_score']); echo $l4_4b_team[0]."\"> \n";
								if ($ar['cups_bracket_de_manual'] == 0){
									if ($debug == 1){echo "<span style=\"color:blue;\"><strong>\$l28</strong></span> ";}
									echo $l28;
									echo "<input type=\"hidden\" name=\"cups_bracket_l4_4b_team\" value=\"".$l28."\">";
								} else {
									echo "<select name=\"cups_bracket_l4_4b_team\">\n";
									echo "	<option value=\"".$l25."\""; if ($l25 == $l4_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l25."</option>\n";
									echo "	<option value=\"".$l26."\""; if ($l26 == $l4_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l26."</option>\n";
									echo "	<option value=\"".$l27."\""; if ($l27 == $l4_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l27."</option>\n";
									echo "	<option value=\"".$l28."\""; if ($l28 == $l4_4b_team[1]){echo "selected=\"selected\"";} echo ">".$l28."</option>\n";
									echo "</select>\n";
								}
					echo "		</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"2\">&nbsp;</td>\n";
					echo "		<td class=\"cups_text\" colspan=\"3\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "</table>\n";
			}
		}
		echo "<table width=\"600px\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" bordercolor=\"#000000\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" colspan=\"4\"><strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "Final Match";} else {echo "3-4 Place Match";} echo "</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\"><select name=\"fin1_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($fin_map[0] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
				echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $fin_map[0]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
		echo "			</select></td>\n";
		echo "		<td class=\"cups_text\">\n";
		/* Vyber druhe mapy se zobrazi jen pri DE cupech */
		if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){
			echo "<select name=\"fin2_map\">";
			$res3 = mysql_query("SELECT clan_map_img FROM $db_clan_maps ORDER BY clan_map_game_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			echo "<option value=\"\""; if ($fin_map[1] == ""){echo "selected=\"selected\"";} echo ">"._CUPS_BRACKETS_MAP_CHOOSE_IMG."</option>\n";
			while($ar3 = mysql_fetch_array($res3)){
		 		echo "<option value=\"".$ar3['clan_map_img']."\""; if ($ar3['clan_map_img'] == $fin_map[1]){echo "selected=\"selected\"";} echo ">".$ar3['clan_map_img']."</option>\n";
			}
			echo "</select>\n";
		}
		echo "&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\"><textarea cols=\"25\" name=\"cups_bracket_fi_info\" rows=\"5\">".stripslashes($ar['cups_bracket_fi_info'])."</textarea></td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\"><strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "Winners Bracket Winner:";} echo "</strong></td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_f1_1a_score\" value=\"".$ar['cups_bracket_f1_1a_score']."\">"; if ($cups_bracket_cup_type == "32de"){echo $w31;}elseif ($cups_bracket_cup_type == "32se"){echo $l29;}elseif ($cups_bracket_cup_type == "16de"){echo $w29;}elseif($cups_bracket_cup_type == "16se"){echo $l25;}elseif($cups_bracket_cup_type == "8de"){echo $w25;} else {echo $l17;} echo "</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\">"; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score']){ echo "<input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_f2_1a_score\" value=\"".$ar['cups_bracket_f2_1a_score']."\"> "; } if ($cups_bracket_cup_type == "32de"){ echo $fwin;}elseif ($cups_bracket_cup_type == "32se"){echo $third;}elseif ($cups_bracket_cup_type == "16de"){echo $fwin;}elseif($cups_bracket_cup_type == "8de"){echo $fwin;} else {echo $third;} echo "</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\" style=\"border-width: 1px; border-right-style: solid;\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "style=\"border-width: 1px; border-right-style: solid;\"";} echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\"><strong>"; if ($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de"){echo "Loosers Bracket Winner:";} echo "</strong></td>\n";
		echo "		<td class=\"cups_text\" bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_f1_1b_score\" value=\"".$ar['cups_bracket_f1_1b_score']."\">"; if ($cups_bracket_cup_type == "32de"){echo $lb30;}elseif ($cups_bracket_cup_type == "32se"){echo $l30;}elseif ($cups_bracket_cup_type == "16de"){echo $lb27;}elseif($cups_bracket_cup_type == "16se"){echo $l26;}elseif($cups_bracket_cup_type == "8de"){echo $lb21;} else {echo $l18;} echo "</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "bgcolor=\"#00ffff\">".$champion."</td>";} else {echo "&nbsp;</td>";}
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "style=\"border-width: 1px; border-right-style: solid;\"";} echo ">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "		<td class=\"cups_text\""; if (($cups_bracket_cup_type == "8de" || $cups_bracket_cup_type == "16de" || $cups_bracket_cup_type == "32de") && $ar['cups_bracket_f1_1b_score'] > $ar['cups_bracket_f1_1a_score'] && $ar['cups_bracket_cup_type_end'] == 2){echo "bgcolor=\"#00ffff\"><input type=\"cups_text_forms\" maxlength=\"4\" size=\"2\" name=\"cups_bracket_f2_1b_score\" value=\"".$ar['cups_bracket_f2_1b_score']."\"> ".$flooser."</td>";} else {echo "&nbsp;</td>";}
		echo "		<td class=\"cups_text\">&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"300px\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\" bordercolor=\"#000000\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" width=\"100\">1. Place</td>\n";
		echo "		<td class=\"cups_text\"><strong>".$champion."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" width=\"100\">2. Place</td>\n";
		echo "		<td class=\"cups_text\"><strong>".$second."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" width=\"100\">3. Place</td>\n";
		echo "		<td class=\"cups_text\"><strong>".$third."</strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"cups_text\" width=\"100\">4. Place</td>\n";
		echo "		<td class=\"cups_text\"><strong>".$fourth."</strong></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<span>"._CUPS_BRACKETS_FUNC_SAVE."</span><input type=\"radio\" name=\"save\" value=\"1\">\n";
		echo "			<span>"._CUPS_BRACKETS_FUNC_SAVE_SEND."</span><input type=\"radio\" name=\"save\" value=\"2\" checked>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}

/***********************************************************************************************************
*
*		 MAZANI CUPU
*
***********************************************************************************************************/
function DeleteCup(){
	
	global $db_cups_bracket;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_cups_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	if ($_POST['confirm'] == "true"){
		$res = mysql_query("DELETE FROM $db_cups_bracket WHERE cups_bracket_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		ShowMain();
	}
	
	if ($_POST['confirm'] == "false"){ShowMain();}
	
	if ($_POST['confirm'] == ""){
		$res = mysql_query("SELECT * FROM $db_cups_bracket WHERE cups_bracket_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" class=\"nadpis\">"._CUPS_BRACKETS_CUPS." - "._CUPS_BRACKETS_DEL."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CUPS_BRACKETS_MAINMENU."\">\n";
		echo "		<a href=\"modul_cups.php?project=".$_SESSION['project']."\">"._CUPS_BRACKETS_MAINMENU."</a></td>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_ID."&nbsp;</td>\n";
		echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_GAME."&nbsp;</td>\n";
		echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_TYPE."&nbsp;</td>\n";
		echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._CUPS_BRACKETS_NAME."&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" align=\"center\">".$ar['cups_bracket_id']."</td>\n";
		echo "		<td width=\"80\" align=\"center\">".$ar['cups_bracket_cup_game']."</td>\n";
		echo "		<td width=\"80\" align=\"center\">".$ar['cups_bracket_cup_type']."</td>\n";
		echo "		<td width=\"540\" align=\"left\">".$ar['cups_bracket_cup_name']."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._CUPS_BRACKETS_CHECK_DEL."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"modul_cups.php?action=delete\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\">\n";
		echo "			<form action=\"modul_cups.php?action=delete\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "edit") { AddCup(); }
	if ($_GET['action'] == "delete") { DeleteCup(); }
	if ($_GET['action'] == "add") { AddCup(); }
	if ($_GET['action'] == "kill_use_bracket"){KillUseById($_GET['id'],$_GET['action']);ShowMain();} // Adminum s pravy kill_use umozni odstranit priznak "pouzivano" u novinek a aktualit
include ("inc.footer.php");