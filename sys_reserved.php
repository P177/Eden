<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU REZERVOVANYCH SLOV																
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_reserved_words;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho name z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	/* Provereni opravneni */
	if ($_GET['action'] == "res_words_edit"){
		if (CheckPriv("groups_reserved_edit") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} elseif ($_GET['action'] == "res_words_del"){
		if (CheckPriv("groups_reserved_del") <> 1) { echo _NOTENOUGHPRIV;exit;}
	} else {
		if (CheckPriv("groups_reserved_add") <> 1) { echo _NOTENOUGHPRIV;exit;}
	}
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._RES_WORDS." - "; if ($_GET['action'] == "res_words_del"){echo _RES_WORDS_DEL;} elseif ($_GET['action'] == "res_words_edit"){echo _RES_WORDS_EDIT;} else {echo _RES_WORDS_ADD;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._RES_WORDS_ADD_WORD."\">&nbsp;<a href=\"sys_reserved.php?action=res_words_add&amp;project=".$_SESSION['project']."\">"._RES_WORDS_ADD_WORD."</a></td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "res_words_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "res_words_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	if ($_GET['action'] != "res_words_add"){
		$res = mysql_query("SELECT * FROM $db_reserved_words WHERE reserved_words_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<form action=\"sys_save.php?action="; if ($_GET['action'] == "res_words_del"){echo "res_words_del";} elseif ($_GET['action'] == "res_words_edit"){echo "res_words_edit";} else {echo "res_words_add";} echo "&id=".$_GET['id']."\" method=\"post\" name=\"forma\">\n";
	echo "			<strong>"._RES_WORDS_WORD."</strong> <input type=\"text\" name=\"res_word\" "; if($_GET['action'] != "res_words_add"){ echo "value=\"".stripslashes($ar['reserved_words_word'])."\"";} echo "size=\"60\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "res_words_del"){echo _RES_WORDS_BUTTON_DEL;} elseif ($_GET['action'] == "res_words_edit"){echo _RES_WORDS_BUTTON_EDIT;} else {echo _RES_WORDS_BUTTON_ADD;} echo "\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"300\" align=\"left\"><span class=\"nadpis-boxy\">"._RES_WORDS_WORD."</span></td>\n";
	echo "		<td width=\"420\" align=\"left\"><span class=\"nadpis-boxy\">"._RES_WORDS_CAT."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT * FROM $db_reserved_words ORDER BY reserved_words_word ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		$word = stripslashes($ar['reserved_words_word']);
		echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
		echo "	<td width=\"80\" align=\"left\" valign=\"middle\">";
		echo "	<a href=\"sys_reserved.php?action=res_words_edit&amp;name=".$word."&amp;id=".$ar['reserved_words_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a> ";
		echo "	<a href=\"sys_reserved.php?action=res_words_del&amp;name=".$word."&amp;id=".$ar['reserved_words_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a></td>\n";
		echo "	<td width=\"30\" align=\"left\" valign=\"middle\">".$ar['reserved_words_id']."</td>\n";
		echo "	<td width=\"420\" align=\"left\" valign=\"middle\">".$word."</td>\n";
		echo "</tr>";
 	}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "res_words_add") {ShowMain();}
	if ($_GET['action'] == "res_words_edit") {ShowMain();}
	if ($_GET['action'] == "res_words_del") {ShowMain();}
include ("inc.footer.php");