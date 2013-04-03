<?php
include "modul_comments.php";
/***********************************************************************************************************
*
*		ZOBRAZENI ANKET
*
***********************************************************************************************************/
function Menu(){
	
	global $db_poll_questions;
	
	$res = mysql_query("SELECT poll_questions_id FROM $db_poll_questions") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	
	switch ($_GET['action']){
		case "poll_add":
			$title = _POLL_ADD;
			$link = "<a href=\"modul_poll.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		case "poll_edit":
			$title = _POLL_EDIT;
			$link = "<a href=\"modul_poll.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		case "poll_del":
			$title = _POLL_DEL;
			$link = "<a href=\"modul_poll.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		case "poll_del_data":
			$title = _POLL_DEL_DATA;
			$link = "<a href=\"modul_poll.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>";
			break;
		default:
			$title = _POLL;
			$link = "<a href=\"modul_poll.php?action=poll_add&amp;project=".$_SESSION['project']."\">"._POLL_ADD."</a>";
	}
	
	$menu =  "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .=  "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">".$title."</td>\n";
	$menu .=  "	</tr>\n";
	$menu .=  "	<tr>\n";
	$menu .=  "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"".$title."\" alt=\"".$title."\">".$link."</td>\n";
	$menu .=  "		<td align=\"right\">"._POLL_SUBMITED.": ".$num."</td>\n";
	$menu .=  "	</tr>\n";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .=  "</table>\n";
	
	return $menu;
	
}
/***********************************************************************************************************
*
*		ZOBRAZENI ANKET
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_poll_questions,$db_poll_answers,$db_admin,$db_comments;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$res_num = mysql_query("SELECT COUNT(*) AS suma FROM $db_poll_questions") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_num = mysql_fetch_array($res_num);
	
	$m = 0;
	if (empty($_GET['page'])) {$page = 1;} else {$page = $_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	$hits = 50;
	$stw2 = ($ar_num['suma']/$hits);
	$stw2 = (integer) $stw2;
	if ($ar_num['suma']%$hits > 0) {$stw2++;}
	$np = $page+1;
	$pp = $page-1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	$sp = ($page-1)*$hits;
	$ep = ($page-1)*$hits+$hits;
	
	$limit = "LIMIT ".(integer)$sp.", ".(integer)$hits."";
	
	$res = mysql_query("SELECT pq.*, a.admin_nick 
	FROM $db_poll_questions AS pq 
	LEFT JOIN $db_admin AS a ON pq.poll_questions_author=a.admin_id 
	ORDER BY pq.poll_questions_id DESC 
	$limit") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"125\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"30\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._POLL_FOR."</span></td>\n";
	echo "		<td width=\"90\"><span class=\"nadpis-boxy\">"._POLL_DATE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._POLL_QUESTION."</span></td>\n";
	echo "		<td width=\"100\"><span class=\"nadpis-boxy\">"._POLL_AUTHOR."</span></td>\n";
	echo "	</tr>";
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		$m++;
		// Zjisteni poctu komentářů
		$res4 = mysql_query("SELECT COUNT(*) FROM $db_comments WHERE comment_pid=".(integer)$ar['poll_questions_id']." AND comment_modul='poll'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num4 = mysql_fetch_array($res4);
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"125\">\n";
		echo "		<a href=\"modul_poll.php?action=open&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_open.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._POLL_OPENPOLL."\"></a>\n";
		   			if (CheckPriv("groups_wp_edit") == 1){ echo "<a href=\"modul_poll.php?action=poll_edit&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._POLL_EDITPOLL."\"></a> ";}
					if (CheckPriv("groups_wp_del") == 1){ echo "<a href=\"modul_poll.php?action=poll_del&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._POLL_DELETEPOLL."\"></a> ";}
					if (CheckPriv("groups_wp_del") == 1){ echo "<a href=\"modul_poll.php?action=poll_del_data&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."&amp;page=".$page."&amp;hits=".$hits."\"><img src=\"images/sys_datadel.gif\" border=\"0\" alt=\""._POLL_NULLPOLL."\"></a>"; }
					if (CheckPriv("groups_wp_del") == 1){ echo "<a href=\"modul_poll.php?action=komentar&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."&amp;page=".$page."&amp;hits=".$hits."\" title=\""._NUMCOM."\"><span style=\"font-size:12px; font-weight: bold;\">".$num4[0]."</span></a>";}
		echo "	</td>\n";
		echo "	<td width=\"30\" align=\"right\">".$ar['poll_questions_id']."</td>\n";
		echo "	<td width=\"90\" align=\"center\">"; if ($ar['poll_questions_for'] == 0){echo _POLL_FOR_POLL;}elseif($ar['poll_questions_for'] == 1){echo _POLL_FOR_ARTICLES;}elseif($ar['poll_questions_for'] == 2){echo _POLL_FOR_USERS;} echo "</td>\n";
		echo "	<td>".FormatDatetime($ar['poll_questions_date'],"d.m.Y")."</td>\n";
		echo "	<td><a href=\"modul_poll.php?action=open&amp;project=".$_SESSION['project']."&amp;id=".$ar['poll_questions_id']."\">".ShortText($ar['poll_questions_question'],65)."</a></td>\n";
		echo "	<td width=\"100\" align=\"left\" valign=\"top\">".$ar['admin_nick']."</td>\n";
		echo "</tr>";
 		if ($_GET['id'] == $ar['poll_questions_id'])	{
			echo "<tr><td colspan=\"5\" style=\"padding-left:90px;\">";
			$myvote = mysql_query("SELECT COUNT(*) AS suma FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$myvres = mysql_fetch_array($myvote);
			echo "<strong>"._POLL_ANSWERS.":</strong><br>";
			$res_questions = mysql_query("SELECT poll_questions_answers FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_questions = mysql_fetch_array($res_questions);
			$odg = explode ("||", $ar_questions['poll_questions_answers']);
			while ( list ($key,$values)= each($odg)){
				$myres = mysql_query("SELECT COUNT(*) AS vote FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$_GET['id']." AND poll_answers_answer=".(integer)$key) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$myar  = mysql_fetch_array($myres);
				echo $values.": ".$myar['vote']." ";
				if ($myar['vote'] <> 0 and $myvres['suma'] <> 0) {$procent = $myar['vote'] / $myvres['suma'] * 100;} else {$procent = 0;}
				printf (" [%.2f",$procent);
				echo "%]<br>";
			}
			echo "</td></tr>";
		}
		$i++;
	}
	echo "</table>";
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima
		if ($stw2 > 1){
			echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			echo "<tr><td height=\"30\">";
			echo _CMN_SELECTPAGE;
			//Zobrazeni cisla poctu stranek
			for ($i=1;$i<=$stw2;$i++) {
				if ($page == $i) {
		   			echo " <strong>".$i."</strong>";
		   		} else {
			 		echo " <a href=\"modul_poll.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">".$i."</a> ";
				}
			}
			//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
			echo "<center><a href=\"modul_poll.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_PREVIOUS."</a> <--|--> <a href=\"modul_poll.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_NEXT."</a></center>";
			echo "</td></tr></table>";
		}
}

/***********************************************************************************************************
*
*		PRIDAVANI A EDITACE ANKET
*
***********************************************************************************************************/
function AddPoll(){
	
	global $db_admin,$db_poll_questions;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "poll_add"){
		if (CheckPriv("groups_wp_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "poll_edit"){
		if (CheckPriv("groups_wp_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_GET['action'] == "poll_edit"){
		$res = mysql_query("SELECT * FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$poll_questions_question = $ar['poll_questions_question'];
		$poll_questions_answers = explode ("||", $ar['poll_questions_answers']);
	}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\"></td><form action=\"sys_save.php?action=".$_GET['action']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._POLL_QUESTION.":</strong> <br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"question\" size=\"100\" value=\"".$poll_questions_question."\"><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._POLL_ANSWERS.": 1)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_1\" size=\"50\" value=\"".$poll_questions_answers[0]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>2)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_2\" size=\"50\" value=\"".$poll_questions_answers[1]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>3)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_3\" size=\"50\" value=\"".$poll_questions_answers[2]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>4)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_4\" size=\"50\" value=\"".$poll_questions_answers[3]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>5)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_5\" size=\"50\" value=\"".$poll_questions_answers[4]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>6)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_6\" size=\"50\" value=\"".$poll_questions_answers[5]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>7)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_7\" size=\"50\" value=\"".$poll_questions_answers[6]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>8)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_8\" size=\"50\" value=\"".$poll_questions_answers[7]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>9)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_9\" size=\"50\" value=\"".$poll_questions_answers[8]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>10)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_10\" size=\"50\" value=\"".$poll_questions_answers[9]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>11)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_11\" size=\"50\" value=\"".$poll_questions_answers[10]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>12)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_12\" size=\"50\" value=\"".$poll_questions_answers[11]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>13)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_13\" size=\"50\" value=\"".$poll_questions_answers[12]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>14)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_14\" size=\"50\" value=\"".$poll_questions_answers[13]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>15)</strong><br><br></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"answer_15\" size=\"50\" value=\"".$poll_questions_answers[14]."\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._POLL_LANG.":</strong> </td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"language\">\n";
	echo "				<option name=\"language\" value=\"cz\" "; if ($ar['poll_questions_lang'] == "cz"){echo "selected=\"selected\"";} echo ">cz</option>\n";
	echo "				<option name=\"language\" value=\"de\" "; if ($ar['poll_questions_lang'] == "de"){echo "selected=\"selected\"";} echo ">de</option>\n";
	echo "				<option name=\"language\" value=\"en\" "; if ($ar['poll_questions_lang'] == "en"){echo "selected=\"selected\"";} echo ">en</option>\n";
	echo "				<option name=\"language\" value=\"pl\" "; if ($ar['poll_questions_lang'] == "pl"){echo "selected=\"selected\"";} echo ">pl</option>\n";
	echo "			</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._POLL_FOR.":</strong> </td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"poll_for\">\n";
	echo "				<option value=\"0\" "; if ($ar['poll_questions_for'] == 0){echo "selected=\"selected\"";} echo ">"._POLL_FOR_POLL."</option>\n";
	echo "				<option value=\"1\" "; if ($ar['poll_questions_for'] == 1){echo "selected=\"selected\"";} echo ">"._POLL_FOR_ARTICLES."</option>\n";
	echo "			</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\"><br><br>\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

/***********************************************************************************************************
*
*		MAZANI ANKET
*
***********************************************************************************************************/
function DeletePoll(){
	
	global $db_poll_questions;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_wp_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	echo Menu();
	
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">\n";
					$res = mysql_query("SELECT poll_questions_question, poll_questions_answers FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar = mysql_fetch_array($res);
					echo "	<strong>"._POLL_QPOLL." :</strong> ".$ar['poll_questions_question']."<br>\n";
					echo "	<strong>"._POLL_APOLL." :</strong> ".$ar['poll_questions_answers']."<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._POLL_CHECK_DELETE."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_save.php?action=poll_del&amp;id=".$_GET['id']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\" >\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_poll.php?action=showmain&amp;id=".$_GET['id']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		MAZANI DAT V ANKETACH
*
*		Odstraneni vsech zaznamu v databazi tykajicich se anket
*
***********************************************************************************************************/
function DeletePollData(){
	
	global $db_poll_answers,$db_poll_questions;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_wp_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	echo Menu();
	
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">\n";
					$res = mysql_query("SELECT poll_questions_question, poll_questions_answers FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar = mysql_fetch_array($res);
					echo "	<strong>"._POLL_QPOLL." :</strong> ".$ar['poll_questions_question']."<br>\n";
					echo "	<strong>"._POLL_APOLL." :</strong> ".$ar['poll_questions_answers']."<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._POLL_CHECK_DEL_DATA."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_save.php?action=poll_del_data&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\" >\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_poll.php?action=showmain&amp;id=".$_GET['id']."&amp;page=".$_GET['page']."&amp;hits=".$_GET['hits']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include ("inc.header.php");
		if ($_GET['action'] == "") { ShowMain(); }
		if ($_GET['action'] == "showmain") { ShowMain(); }
		if ($_GET['action'] == "poll_edit") { AddPoll(); }
		if ($_GET['action'] == "poll_add") { AddPoll(); }
		if ($_GET['action'] == "poll_del") { DeletePoll(); }
		if ($_GET['action'] == "poll_del_data") { DeletePollData(); }
		if ($_GET['action'] == "open") { ShowMain(); }
		if ($_GET['action'] == "logout") { Logout(); }
		if ($_GET['action'] == "komentar"){Comments($_GET['id'],"poll");}
		if ($_GET['action'] == "send"){Save("poll");}	// Ulozi komentar
		if ($_GET['action'] == "delete_comments"){DeleteComm();}
		if ($_GET['action'] == "replace_comments"){ReplaceComm($_POST['r_topic'],$_POST['r_tekst'],$_POST['r_modul']);}
include ("inc.footer.php");