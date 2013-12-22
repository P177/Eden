<?php
$_SESSION['loginid'] = $_GET['lid'];
include (dirname(__FILE__)."/cfg/db.".$_GET['project'].".inc.php");
include "functions.php";

$res = mysql_query("SELECT calendar_msg_d, calendar_msg_m, calendar_msg_y FROM $db_calendar WHERE calendar_msg_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar = mysql_fetch_array($res);

$d = $ar['calendar_msg_d'];
$m = $ar['calendar_msg_m'];
$y = $ar['calendar_msg_y'];
$dateline = $d." ".$cal_lang['months'][$m-1]." ".$y;
$wday = date("w", mktime(0,0,0,$m,$d,$y));
if ($_POST['confirm'] == "TRUE" && $_POST['action'] == "delete"){
	$res = mysql_query("DELETE FROM $db_calendar WHERE calendar_msg_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "</head>\n";
	echo "<body onload=CloseFunc()>\n";
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "	<!--\n";
	echo "	function CloseFunc(){\n";
	echo "		setTimeout('window.close()',1000);\n";
	echo "	}\n";
	if ($_POST['mode'] == "web"){
		echo "	window.opener.location.href = '".$_POST['url']."';\n";
		echo "	window.opener.location.href.reload();\n";
		echo "	window.close();\n";
	} else {
		echo "	window.opener.location.reload();\n";
	}
	echo "	window.close();\n";
	echo "	//-->\n";
	echo "</script>\n";
	echo "</body>\n";
	echo "</html>";
	exit;
} elseif ($_POST['confirm'] == "FALSE"){
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "	<!--\n";
	echo "	window.close();\n";
	echo "	//-->\n";
	echo "</script>";
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "	<title>"._CALENDAR."</title>\n";
echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden_pop.css\">\n";
echo "	</head>\n";
echo "<body>";
if ($_GET['action'] == "delete"){
	echo "<table cellspadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"400\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._CAL_DEL_CHECK."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"100\" valign=\"top\">\n";
	echo "			<form action=\"".$eden_cfg['url_cms']."modul_calendar_show.php?id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"delete\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"url\" value=\"".$_GET['url']."\">\n";
	echo "			<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
	echo "			</form>\n";
	echo "			\n";
	echo "		</td>\n";
	echo "		<td width=\"100\" valign=\"top\">\n";
	echo "			<form action=\"".$eden_cfg['url_cms']."modul_calendar_show.php?id=".$_GET['id']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"delete\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"url\" value=\"".$_GET['url']."\">\n";
	echo "			<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"FALSE\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
} else {
	echo "<!-- selected date -->\n";
	echo "<table cellspadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"400\">\n";
	echo "<tr>\n";
	echo "	<td><span class=\"display_header\">".$dateline."</span></td>\n";
	echo "	<td align=\"right\"><span class=\"display_header\">".$cal_lang['days'][$wday]."</span></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<br clear=\"all\">";
	// display selected posting first
	WritePosting($_GET['id']);
	
	// give some space
	echo "<img src=\"images/clear.gif\" width=\"1\" height=\"25\" border=\"0\"><br clear=\"all\">";
	
	$res = mysql_query("SELECT calendar_msg_id, calendar_msg_start_time 
	FROM $db_calendar 
	WHERE calendar_msg_y='".mysql_real_escape_string($y)."' AND calendar_msg_m='".mysql_real_escape_string($m)."' AND calendar_msg_d='".mysql_real_escape_string($d)."' AND calendar_msg_id != ".(integer)$_GET['id']." 
	ORDER BY calendar_msg_start_time ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if (mysql_num_rows($res)) {
		echo "<span class=\"display_header\">"._CAL_OTHER_ITEMS."</span>";
		echo "<br clear=\"all\"><img src=\"/images/clear.gif\" width=\"1\" height=\"3\" border=\"0\"><br clear=\"all\">";
		
		// display rest of this day's postings
		while ($ar = mysql_fetch_array($res)) {
			WritePosting($ar['calendar_msg_id']);
			echo "<img src=\"images/clear.gif\" width=\"1\" height=\"12\" border=\"0\"><br clear=\"all\">";
		}
	}
	
	echo "</body></html>";
}

//********************************************************************************************************
//                                                                                                        
//             ZAPSANI PRISPEVKU                                                                          
//                                                                                                        
//********************************************************************************************************
function WritePosting($wpid){
	
	global $db_admin,$db_calendar;
	global $url_games;
	global $eden_cfg;
	
	$res = mysql_query("SELECT c.*, a.admin_nick 
	FROM $db_calendar AS c 
	LEFT JOIN $db_admin AS a ON c.calendar_msg_uid = a.admin_id 
	WHERE c.calendar_msg_id=".(integer)$wpid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	$title = stripslashes($ar['calendar_msg_title']);
	$title = wordwrap( $title, 35, "<br>", 1);
	//$body = stripslashes(str_replace("\n", "<br>", $ar["text"]));
	//$body = wordwrap( $body, 55, "<br>", 1);
	$body = $ar['calendar_msg_text'];
	$postedby = _CAL_POSTED_BY." : ".$ar['admin_nick'];
	
	if (!($ar['calendar_msg_start_time'] == "00:00:00" && $ar['calendar_msg_end_time'] == "00:00:00")) {
		if ($ar['calendar_msg_start_time'] == "00:00:00"){
			$starttime = "- -";
		} else {
			$starttime = $ar['calendar_msg_start_time'];
		}	
		if ($ar['calendar_msg_end_time'] == "00:00:00"){
			$endtime = "";
		} else {
			$endtime = $ar['calendar_msg_end_time'];
		}
		$timestr = "$starttime - $endtime";
	} else {
		$timestr = "";
	}
	
	if ($ar['calendar_msg_group'] != "") { echo "<img src=\"".$url_games.$ar['calendar_msg_group']."\" alt=\"\"  width=\"12\" height=\"12\" border=\"0\">&nbsp;&nbsp;"; }
	if (CheckPriv("groups_calendar_edit") == 1) { echo "<span><a href=\"".$eden_cfg['url_cms']."modul_calendar_add.php?id=".$wpid."&amp;project=".$_SESSION['project']."&amp;action=edit&amp;lid=".$_SESSION['loginid']."&amp;mode=".$_GET['mode']."&amp;url=".$_GET['url']."\">"._CAL_EDIT."</a></span>"; }
	if (CheckPriv("groups_calendar_del") == 1) { echo "&nbsp;&nbsp;|&nbsp;&nbsp;<span><a href=\"".$eden_cfg['url_cms']."modul_calendar_show.php?id=".$wpid."&amp;project=".$_SESSION['project']."&amp;action=delete&amp;lid=".$_SESSION['loginid']."&amp;mode=".$_GET['mode']."&amp;url=".$_GET['url']."\">"._CAL_DEL."</a></span>"; }
	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"400\">\n";
	echo "	<tr>\n";
	echo "		<td bgcolor=\"#000000\">\n";
	echo "			<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"display_title_bg\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"><tr>\n";
	echo "							<td width=\"100%\"><span class=\"display_title\">&nbsp;".$title."</span></td>\n";
	echo "							<td><img src=\"images/clear.gif\" width=\"20\" height=\"1\" border=\"0\"></td>\n";
	echo "							<td align=\"right\" nowrap=\"yes\"><span class=\"display_title\">".$timestr."&nbsp;</span></td>\n";
	echo "					</tr></table></td>\n";
	echo "				</tr>\n";
	echo "				<tr><td class=\"display_txt_bg\">\n";
	echo "					<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td><span class=\"display_txt\">".$body."</span></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td align=\"right\"><span class=\"display_user\">".$postedby."</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td align=\"right\">".$editstr."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td></tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
}