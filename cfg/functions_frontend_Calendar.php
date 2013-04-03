<?php
/***********************************************************************************************************
*
*		CALENDAR - Vyber roku
*
***********************************************************************************************************/
function VyberRoku($year){
	
	echo "<select name=\"year\">\n";
		$z = 3;
		for($i=1;$i < 8; $i++) {
			if ($z == 0){
				echo "<option value=\"".($year - $z)."\" selected>".($year - $z)."</option>\n";
			} else {
				echo "<option value=\"".($year - $z)."\">".($year - $z)."</option>";
			}
			$z--;
		}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber mesice
*
***********************************************************************************************************/
function VyberMesice($month, $montharray){
	
	echo "\n<select name=\"month\">\n";
	for($i=0;$i < 12; $i++) {
		if ($i != ($month - 1)){
			echo "<option value=\"".($i + 1)."\">".$montharray[$i]."</option>";
		} else {
			echo "<option value=\"".($i + 1)."\" selected>".$montharray[$i]."</option>";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber dne
*
***********************************************************************************************************/
function VyberDne($day){
	
	echo "<select name=\"day\">\n";
	for($i=1;$i <= 31; $i++) {
		if ($i == $day){
			echo "	<option value=\"".$i."\" selected>".$i."</option>\n";
		} else {
			echo "	<option value=\"".$i."\">".$i."</option>\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR - Vyber hodiny
*
***********************************************************************************************************/
function VyberHodiny($hour, $namepre){
	
	echo "\n<select name=\"".$namepre."_hour\">\n";
	for($i=0;$i <= 23; $i++) {
		if ($i == $hour){
			echo "	<option value=\"".$i."\" selected>".$i."</option>\n";
		} else {
			echo "	<option value=\"".$i."\">".$i."</option>\n";
		}
	}
	echo "</select>\n\n";
}
/***********************************************************************************************************
*
*		CALENDAR
*
*		$td_width	=	Sirka tabulky v px
*		$hint_align	=	Zarovnani napovedy (right, left)
*		$cal_lang	=	Nazvy dnu a mesicu z eden_lang.cz
*
***********************************************************************************************************/
function Calendar($td_width,$hint_align = "right"){
	
	global $db_calendar,$db_admin;
	global $cal_lang;
	global $eden_cfg;
	global $url_calendar,$url_games;
	
	$_GET['action'] = AGet($_GET,'action');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<table width=\"".$td_width."\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" align=\"center\">";
	echo "	<tr>";
	echo "		<td>";
	// set month and year to present if month
	// and year not received from query string
	if (!AGet($_GET,'month')){$m = date("n");} else {$m = $_GET['month'];}
	if (!AGet($_GET,'year')){$y = date("Y");} else {$y = $_GET['year'];}
	if (!AGet($_GET,'month')){ $_GET['month'] = $m;}
	if (!AGet($_GET,'year')){ $_GET['year'] = $y;}
	// set variables for month scrolling
	if ($m != 12){$nextyear = $y;} else {$nextyear = $y + 1;}
	if ($m != 1){$prevyear = $y;} else {$prevyear = $y - 1;}
	if ($m == 1){$prevmonth = 12;} else {$prevmonth =	$m - 1;}
	if ($m == 12){$nextmonth = 1;} else {$nextmonth = $m + 1;}
	echo "			<a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;month=".$prevmonth."&amp;year=".$prevyear."\"><img src=\"images/sys_vlevo.gif\" border=\"0\"></a>\n";
	echo "			<a href=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;month=".$nextmonth."&amp;year=".$nextyear."\"><img src=\"images/sys_vpravo.gif\" border=\"0\"></a>\n";
	echo "			<span class=\"date_header\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>\n";
	echo "		</td>\n";
	echo "		<!-- form tags must be outside of <td> tags -->\n";
	echo "		<form name=\"forma\" action=\"index.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."\" method=\"get\">\n";
	echo "		<td align=\"right\">"; VyberMesice($m, $cal_lang['months']); echo "\n";
					VyberRoku($y);
	echo "			<input type=\"submit\" value=\""._CALSEARCH."\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"".$_GET['action']."\">\n";
	echo "			<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
	echo "			<input type=\"hidden\" name=\"filter\" value=\"".$_GET['filter']."\">\n";
	echo "		</td>\n";
	echo "		</form>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<div id=\"hint\" style=\"position: absolute; z-index: 99;\"></div>\n";
	echo "<table width=\"".$td_width."\" id=\"eden_calendar_table\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" align=\"center\">\n";
	echo "	<tr>";
	foreach($cal_lang['abrvdays'] as $day) {
		echo "\t<td class=\"hlavicka_sloupcu\">&nbsp;".$day."</td>\n";
	}
	echo "</tr>\n\n";
	// Vraci pozici prvniho dne v mesici.
	$weekpos = date("w",mktime(0,0,0,$_GET['month'],1,$_GET['year'])); // "w" je den v týdnu, číselně, "0" (neděle) až "6" (sobota)
	if ($weekpos == 0){$weekpos = 6;} else {$weekpos = $weekpos - 1;} // Kdyz se odecte 1 nastavi se nase cislovani dne
	
	// Provereni opravneni
	if (CheckPriv("groups_article_add") == 1) {$auth = "TRUE";}
	
	// get number of days in month
	$days = 31-((($_GET['month']-(($_GET['month']<8)?1:0))%2)+(($_GET['month']==2)?((!($_GET['year']%((!($_GET['year']%100))?400:4)))?1:2):0));
	
	// initialize day variable to zero, unless $weekpos is zero
	if ($weekpos == 0){ $day = 1;} else {$day = 0;}
	
	// initialize today's date variables for color change
	$timestamp = time() + 0 * 3600;
	$d = date("j", $timestamp); $m = date("n", $timestamp); $y = date("Y", $timestamp);
	
	// loop writes empty cells until it reaches position of 1st day of month ($wPos)
	// it writes the days, then fills the last row with empty cells after last day
	while($day <= $days) {
		echo "<tr>\n";
		for($i=0;$i < 7; $i++) {
			if ($day > 0 && $day <= $days) {
				echo "	<td valign=\"top\" class=\"";
				if (($day == $d) && ($_GET['month'] == $m) && ($_GET['year'] == $y)){
					echo "today";
				} else {
					echo "day";
				}
				$res = mysql_query("SELECT calendar_msg_id, calendar_msg_uid, calendar_msg_d, calendar_msg_text, calendar_msg_title, calendar_msg_start_time, calendar_msg_end_time, calendar_msg_category, calendar_msg_group, TIME_FORMAT(calendar_msg_start_time, '%H:%i') AS calendar_msg_stime, TIME_FORMAT(calendar_msg_end_time, '%H:%i') AS calendar_msg_etime FROM $db_calendar WHERE calendar_msg_m=".(integer)$_GET['month']." AND calendar_msg_y=".(integer)$_GET['year']." AND calendar_msg_d=".(integer)$day." ORDER BY calendar_msg_start_time ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				echo "_cell\" align=\"center\"><span class=\"day_number\">";
				echo "<img alt=\"".$day."\" style=\"cursor: pointer;\" id=\"imageElink\" "; if ($auth){ echo "onclick=\"window.open('".$eden_cfg['url_cms']."modul_calendar_add.php?project=".$_SESSION['project']."&amp;lid=".$_SESSION['loginid']."&amp;d=".$day."&amp;m=".$_GET['month']."&amp;y=".$_GET['year']."&amp;action=add&amp;mode=web&amp;url=http://".$_SERVER['HTTP_HOST']."', 'postScreen', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=440,height=600')\""; } echo " img src=\"images/sys_".$day.".gif\" width=\"14\" height=\"9\" border=\"0\"></span><br>";
				// write title link if posting exists for day
				while($ar = mysql_fetch_array($res)) {
					$res2 = mysql_query("SELECT admin_nick FROM $db_admin WHERE admin_id=".$ar['calendar_msg_uid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar2 = mysql_fetch_array($res2);
					$text = wordwrap( $ar['calendar_msg_text'], 34, "<br>", 1);
					$title = wordwrap( $ar['calendar_msg_title'], 27, "<br>", 1);
					echo "<span class=\"title_txt\"><a href=\"#\" onclick=\"window.open('".$eden_cfg['url_cms']."modul_calendar_show.php?id=".$ar['calendar_msg_id']."&amp;project=".$_SESSION['project']."&amp;lid=".$_SESSION['loginid']."&amp;mode=web&amp;url=http://".$_SERVER['HTTP_HOST']."', '', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=440,height=600')\">"; if ($ar['calendar_msg_group'] != ""){ echo "<img src=\"".$url_games.$ar['calendar_msg_group']."\" alt=\"\" width=\"12\" height=\"12\" border=\"0\"><br>"; } echo "<img src=\"".$url_calendar.$ar['calendar_msg_category'].".gif\" alt=\"\" style=\"margin-bottom: 2px;\" width=\"15\" height=\"12\" border=\"0\" onmousemove=\"getMouse(event,'".$hint_align."')\" onmouseover=\"ShowHint('".$text."','".$title."','".$ar['calendar_msg_stime']."','".$ar['calendar_msg_etime']."','".$ar2['admin_nick']."',250,1)\" onmouseout=\"ShowHint('','','','','',0,0)\"></a></span>";
				}
				echo "</td>\n";
				$day++;
			} elseif ($day == 0)	{
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