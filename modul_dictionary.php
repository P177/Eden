<?php
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE CHANNELS																		
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_dictionary;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "add_word";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "edit_word"){
		if (CheckPriv("groups_dictionary_edit") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=nep");
		}
	} elseif ($_GET['action'] == "del_word"){
		if (CheckPriv("groups_dictionary_del") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."modul_dictionary.php?action=add_word&project=".$_SESSION['project']."&msg=nep");
		}
	} else {
		if (CheckPriv("groups_dictionary_add") <> 1) {
			header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
	
	if ($_GET['action'] != "add_word"){
		$res_dict = mysql_query("SELECT * FROM $db_dictionary WHERE dictionary_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_dict = mysql_fetch_array($res_dict);
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._DICTIONARY." - "; if ($_GET['action'] == "add_word"){ echo _DICTIONARY_ADD;} elseif ($_GET['action'] == "del_word"){echo _DICTIONARY_DEL;} else {echo _DICTIONARY_EDIT;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""; if ($_GET['action'] == "add_word"){ echo _DICTIONARY_ADD;} elseif ($_GET['action'] == "del_word"){echo _DICTIONARY_DEL;} else {echo _DICTIONARY_EDIT;} echo "\">\n";
	echo "			<a href=\"modul_dictionary.php?action=showmain&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."\">"._DICTIONARY."</a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">"; Alphabeth('modul_dictionary.php?action=add_word&amp;project='.$_SESSION['project'].'&amp;letter=',''); echo "&nbsp;&nbsp;<a href=\"modul_dictionary.php?action=add_word&amp;project=".$_SESSION['project']."&amp;letter=&amp;mode=notallowed\">"._DICTIONARY_NOT_ALLOWED."</a></td>\n";
	echo "	</tr>";
		/* Zobrazeni chyb a hlasek systemu */
		if ($_GET['action'] == "del_word" && $_POST['confirm'] != "true"){$_GET['msg'] = "dict_del_ch";}
		if ($_GET['msg']){
	  		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
		}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\"><form action=\"sys_save.php?action=".$_GET['action']."&amp;id=".$_GET['id']."&amp;hits=".$_GET['hits']."&amp;letter=".$_GET['letter']."\" method=\"post\">\n";
	echo "			<strong>"._DICTIONARY_WORD."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"text\" name=\"dictionary_word\" maxlength=\"255\" size=\"60\" "; if ($_GET['action'] != "add_word"){echo "value=\"".$ar_dict['dictionary_word']."\"";} echo "><strong style=\"margin-left:20px;\">"._DICTIONARY_ALLOW."</strong>&nbsp;<input type=\"checkbox\" name=\"dictionary_allow\" "; if (CheckPriv("groups_dictionary_edit") <> 1) {echo "disabled";} echo " value=\"1\" "; if ($ar_dict['dictionary_allow'] == 1 || $_GET['action'] == "add_word"){echo "checked";} echo "></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\"><strong>"._DICTIONARY_WORD_DESC."</strong></td>\n";
	echo "		<td align=\"left\"><textarea name=\"dictionary_word_description\" rows=\"8\" cols=\"60\">"; if ($_GET['action'] != "add_word"){echo $ar_dict['dictionary_word_description'];} echo "</textarea><br>\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""; if ($_GET['action'] == "add_word"){echo _DICTIONARY_ADD;} elseif ($_GET['action'] == "del_word"){echo _DICTIONARY_DEL;} else {echo _DICTIONARY_EDIT;} echo "\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"20\" align=\"left\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"40\" align=\"left\"><span class=\"nadpis-boxy\">"._DICTIONARY_ALLOWED."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._DICTIONARY_WORD."</span></td>\n";
	echo "		<td width=\"130\" align=\"left\"><span class=\"nadpis-boxy\">"._DICTIONARY_AUTHOR."</span></td>\n";
	echo "		<td width=\"130\" align=\"left\"><span class=\"nadpis-boxy\">"._DICTIONARY_WORD_LAST_UPDATE."</span></td>\n";
	echo "	</tr>";
	if ($_GET['letter'] == "Other"){$like2 = "REGEXP";}elseif ($_GET['letter'] != ""){$like2 = "LIKE";}
	if ($_GET['letter'] != "All"){ $like = "AND dictionary_word ".$like2." ".AlphabethSelect(mysql_real_escape_string($_GET['letter']), "dictionary_word");}
	if ($_GET['mode'] == "notallowed"){$like = "AND dictionary_allow=0";}
		$res = mysql_query("SELECT dictionary_id, dictionary_author_id, dictionary_word, dictionary_word_description, dictionary_date, dictionary_allow FROM $db_dictionary WHERE dictionary_parent_id=0 $like ORDER BY dictionary_allow ASC, dictionary_word ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
   		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"80\" align=\"center\">";
					if (CheckPriv("groups_dictionary_edit") == 1){echo "<a href=\"modul_dictionary.php?action=edit_word&amp;id=".$ar['dictionary_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."&amp;letter=".$_GET['letter']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";}
				if (CheckPriv("groups_dictionary_del") == 1){echo " <a href=\"modul_dictionary.php?action=del_word&amp;id=".$ar['dictionary_id']."&amp;project=".$_SESSION['project']."&amp;hits=".$_GET['hits']."&amp;letter=".$_GET['letter']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";}
		echo "	</td>\n";
		echo "	<td width=\"40\" align=\"left\">".$ar['dictionary_id']."</td>\n";
		echo "	<td width=\"20\" align=\"left\"><img src=\"images/sys_"; if ($ar['dictionary_allow'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "	<td align=\"left\"><a href=\"#\" class=\"eden_hintanchor\" onMouseover=\"ShowHintText('"; $description = str_replace("\r\n", '<br>', $ar['dictionary_word_description']);$description = str_replace("&#039;", '`', $description); echo $description."', this, event, '450px')\">".$ar['dictionary_word']."</a></td>\n";
		echo "	<td width=\"130\" align=\"left\">".GetUserName($ar['dictionary_author_id'])."</td>\n";
		echo "	<td width=\"130\" align=\"left\">".FormatDatetime($ar['dictionary_date'],"d.m.Y H:i:s")."</td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "edit_word") { ShowMain(); }
	if ($_GET['action'] == "del_word") { ShowMain(); }
	if ($_GET['action'] == "add_word") { ShowMain(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "") { ShowMain(); }
include ("inc.footer.php");