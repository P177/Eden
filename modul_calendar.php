<?php
/***********************************************************************************************************
*
*		ZOBRAZENI KALENDARE
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_calendar,$db_admin;
	global $cal_lang;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	if ($_GET['day']){$day = $_GET['day'];}else{$day = $_POST['d'];}
	if ($_GET['month']){$month = $_GET['month'];}else{$month = $_POST['m'];}
	if ($_GET['year']){$year = $_GET['year'];}else{$year = $_POST['y'];}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CALENDAR."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td>";
 				// set month and year to present if month
				// and year not received from query string
				$m = (!$month) ? date("n") : $month;
				$y = (!$year) ? date("Y") : $year;
				if (!$month){ $month = $m;}
				if (!$year){ $year = $y;}
				//$auth 			= auth();
				// set variables for month scrolling
				$nextyear = ($m != 12) ? $y : $y + 1;
				$prevyear = ($m != 1) ? $y : $y - 1;
				$prevmonth = ($m == 1) ? 12 : $m - 1;
				$nextmonth = ($m == 12) ? 1 : $m + 1;
	echo "				<a href=\"modul_calendar.php?month=".$prevmonth."&amp;year=".$prevyear."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_vlevo.gif\" border=\"0\"></a>\n";
	echo "				<a href=\"modul_calendar.php?month=".$nextmonth."&amp;year=".$nextyear."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_vpravo.gif\" border=\"0\"></a>\n";
	echo "				<span class=\"date_header\">&nbsp;".$cal_lang['months'][$m-1]."&nbsp;".$y."</span>\n";
	echo "			</td>\n";
	echo "			<!-- form tags must be outside of <td> tags -->\n";
	echo "			<form name=\"forma\" action=\"modul_calendar.php\" method=\"post\">\n";
	echo "			<td align=\"right\">\n";
		 				VyberMesice($m, $cal_lang['months']);
						VyberRoku($y);
	echo "				<input type=\"submit\" value=\""._CAL_SEARCH."\" class=\"eden_button\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</td>\n";
	echo "			</form>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<script type=\"text/javascript\" src=\"./js/calendar.js\"></script>\n";
	echo "<div id=hint style=\"position: absolute\"></div>\n";
	echo "	<table width=\"665\"  bgcolor=\"#000000\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" align=\"center\">\n";
	echo "		<tr>";
  	foreach($cal_lang['abrvdays'] AS $day) {
		echo "\t<td class=\"hlavicka_sloupcu\">&nbsp;".$day."</td>\n";
	}
	echo "</tr>\n\n";
	
	// Vraci pozici prvniho dne v mesici.
	$weekpos = date("w",mktime(0,0,0,$month,1,$year)); // "w" je den v týdnu, číselně, "0" (neděle) až "6" (sobota)
	if ($weekpos == 0){$weekpos = 6;} else {$weekpos = $weekpos - 1;} // Kdyz se odecte 1 nastavi se nase cislovani dne
	
	// Provereni opravneni
	//if (CheckPriv("groups_article_add") == 1) {$auth = "TRUE";}
	
	// get number of days in month
	$days = 31-((($month-(($month<8)?1:0))%2)+(($month==2)?((!($year%((!($year%100))?400:4)))?1:2):0));
	
	// initialize day variable to zero, unless $weekpos is zero
	if ($weekpos == 0){$day = 1;} else {$day = 0;}
	
	// initialize today's date variables for color change
	$timestamp = mktime() + 0 * 3600;
	$d = date(j, $timestamp); $m = date("n", $timestamp); $y = date(Y, $timestamp);
	// loop writes empty cells until it reaches position of 1st day of month ($wPos)
	// it writes the days, then fills the last row with empty cells after last day
	while($day <= $days) {
		echo "<tr>\n";
		for($i=0;$i < 7; $i++) {
			if ($day > 0 && $day <= $days) {
				echo "	<td class=\"";
				if (($day == $d) && ($month == $m) && ($year == $y)){
					echo "today";
				} else {
					echo "day";
				}
				$res = mysql_query("SELECT calendar_msg_id, calendar_msg_uid, calendar_msg_d, calendar_msg_text, calendar_msg_title, calendar_msg_start_time, calendar_msg_end_time, calendar_msg_category, TIME_FORMAT(calendar_msg_start_time, '%H:%i') AS calendar_msg_stime, TIME_FORMAT(calendar_msg_end_time, '%H:%i') AS calendar_msg_etime FROM $db_calendar WHERE calendar_msg_m = ".(float)$month." AND calendar_msg_y = ".(float)$year." AND calendar_msg_d = ".(float)$day." ORDER BY calendar_msg_start_time") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "_cell\" valign=\"top\"><span class=\"day_number\">";
				echo "<img alt=\"".$day."\" style=\"cursor: hand;\" id=\"imageElink\" onclick=\"window.open('modul_calendar_add.php?project=".$_SESSION['project']."&lid=".$_SESSION['loginid']."&amp;d=".$day."&amp;m=".$month."&amp;y=".$year."&amp;action=add&amp;mode=eden', 'postScreen', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=440,height=500')\" img src=\"images/sys_".$day.".gif\" width=\"14\" height=\"9\" border=\"0\">";
				echo "</span><br>";
				
				// write title link if posting exists for day
				while($ar = mysql_fetch_array($res)) {
					$res2 = mysql_query("SELECT admin_nick FROM $db_admin WHERE admin_id=".(integer)$ar['calendar_msg_uid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar2 = mysql_fetch_array($res2);
					$text = wordwrap( $ar['calendar_msg_text'], 34, "<br>", 1);
					$title = wordwrap( $ar['calendar_msg_title'], 27, "<br>", 1);
					echo "<span class=\"title_txt\"><a href=\"modul_calendar.php?project=".$_SESSION['project']."\" onclick=\"window.open('modul_calendar_show.php?id=".$ar['calendar_msg_id']."&amp;project=".$_SESSION['project']."&amp;lid=".$_SESSION['loginid']."&amp;mode=eden', '', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=440,height=500')\"><img src=\"images/calendar/".$ar['calendar_msg_category'].".gif\" alt=\"\" width=\"30\" height=\"15\" border=\"0\" onmousemove=getMouse(event); onmouseover=\"ShowHint('".$text."','".$title."','".$ar['calendar_msg_stime']."','".$ar['calendar_msg_etime']."','".$ar2['admin_nick']."',250,1)\" onmouseout=\"ShowHint('','','','','',0,0)\"></a></span>";
					}
				echo "</td>\n";
				$day++;
			} elseif ($day == 0)  {
     			echo "	<td class=\"empty_day_cell\" valign=\"top\">&nbsp;</td>\n";
				$weekpos--;
				if ($weekpos == 0) {$day++;}
	 		} else {
				echo "	<td class=\"empty_day_cell\" valign=\"top\">&nbsp;</td>\n";
			}
     	}
		echo "</tr>\n\n";
	}
	echo "</table>";
}

function javaScript(){
	echo "<script language=\"JavaScript\">";
	echo "	function loginPop(month, year) {\n";
	echo "		eval(\"logpage = window.open('login.php?month=\" + month + \"&amp;year=\" + year + \"', 'mssgDisplay', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=340,height=500');\");\n";
	echo "	}\n";
	echo "</script>\n";
}
include ("inc.header.php");
	if ($_GET['action'] == "") {ShowMain();}
	if ($_GET['action'] == "addmsg") {AddMsg();}
include ("inc.footer.php");