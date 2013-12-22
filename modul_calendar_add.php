<?php
$_SESSION['loginid'] = $_GET['lid'];

include (dirname(__FILE__)."/cfg/db.".$_GET['project'].".inc.php");
include "functions.php";

$res = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(integer)$_GET['lid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$uid = mysql_fetch_array($res);

// Provereni opravneni
if (CheckPriv("groups_calendar_add") <> 1) {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "</head>\n";
	echo "<body>\n";
	echo _NOTENOUGHPRIV;
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "window.close();\n";
	echo "//-->\n";
	echo "</script>\n";
	echo "</body>\n";
	echo "</html>";
}
if (CheckPriv("groups_calendar_edit") <> 1) {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "</head>\n";
	echo "<body>\n";
	_NOTENOUGHPRIV;
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "window.close();\n";
	echo "//-->\n";
	echo "</script>\n";
	echo "</body>\n";
	echo "</html>";
}
// Výčet povolených tagů
$allowtags = "<embed>, <marquee>, <blink>, <hr>, <ul>, <li>, <ol>, <p>, <br>, <font>, <b>, <u>, <i>, <small>, <big>, <strong>, <em>, <a>, <img>";
$text = str_replace("\r\n", ' <br>', $_POST['text']);
if ($_POST['confirm'] == true){
	if ($_POST['action'] == "add"){
			$start_time = (integer)$_POST['start_hour'].":".(integer)$_POST['start_min'].":"."00";
			$end_time = (integer)$_POST['end_hour'].":".(integer)$_POST['end_min'].":"."00";
			mysql_query("INSERT 
			INTO $db_calendar 
			VALUES('','".(integer)$_POST['uid']."','".(integer)$_POST['m']."','".(integer)$_POST['d']."','".(integer)$_POST['y']."','$start_time','$end_time','".mysql_real_escape_string($_POST['title'])."',
			'".mysql_real_escape_string($text)."','".mysql_real_escape_string($_POST['category'])."','".mysql_real_escape_string($_POST['cal_group'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$konec = true;
	}elseif ($_POST['action'] == "edit"){
			$start_time = "calendar_msg_start_time='".(integer)$_POST['start_hour'].":".(integer)$_POST['start_min'].":"."00',";
			$end_time = "calendar_msg_end_time='".(integer)$_POST['end_hour'].":".(integer)$_POST['end_min'].":"."00',";
			mysql_query("UPDATE $db_calendar 
			SET $start_time $end_time calendar_msg_title='".mysql_real_escape_string($_POST['title'])."', calendar_msg_text='".mysql_real_escape_string($text)."', calendar_msg_category='".mysql_real_escape_string($_POST['category'])."', 
			calendar_msg_d='".(integer)$_POST['d']."', calendar_msg_m='".(integer)$_POST['m']."', calendar_msg_y='".(integer)$_POST['y']."', calendar_msg_group='".mysql_real_escape_string($_POST['cal_group'])."' 
			WHERE calendar_msg_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$konec = true;
	}
}
if ($konec == true) {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "</head>\n";
	echo "<body onload=CloseFunc()>\n";
	echo "	<script type=\"text/javascript\" language=\"JavaScript\">\n";
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
	echo "	</script>\n";
	echo "</body>\n";
	echo "</html>";
		exit;
}

if (empty($_GET['id'])) {
	DisplayEditForm('Add', $uid['admin_id']);
} else {
	if (CheckPriv("groups_calendar_edit") == 1) {
		DisplayEditForm('Edit', $uid['admin_id'], $_GET['id']);
	} else {
		echo _CAL_ACCESS_DENIED;
	}
}

function DisplayEditForm($mode, $uid, $def_id=""){
	
	global $db_calendar,$db_admin;
	global $eden_cfg;
	global $ftp_path_calendar;
	global $url_calendar;
	global $cal_lang;
	
	$res_admin = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_id=".(integer)$uid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_admin = mysql_fetch_array($res_admin);
	
	if ($mode == "Add") {
		$text 		= $title = "";
		$shour 		= $sminute = 0;
		$ehour 		= $eminute = 0;
		$headerstr 	= _CAL_ADD_NEW_EVENT;
		$buttonstr 	= _CAL_ADD;
		$pgtitle 	= _CAL_ADD_NEW_TITLE;
		$qstr 		= "?flag=add";
		$d			= $_GET['d'];
		$m			= $_GET['m'];
		$y			= $_GET['y'];
	} elseif ($mode == "Edit") {
		$res = mysql_query("SELECT calendar_msg_start_time, calendar_msg_end_time, calendar_msg_title, calendar_msg_text, calendar_msg_category, calendar_msg_group, calendar_msg_m, calendar_msg_d, calendar_msg_y 
		FROM $db_calendar 
		WHERE calendar_msg_id=".(integer)$def_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if (!empty($ar)) {
			$starttime 	= explode (":", $ar['calendar_msg_start_time']);
			$endtime 	= explode (":", $ar['calendar_msg_end_time']);
			$qstr 		= "?flag=edit&amp;id=".$def_id."";
			$headerstr 	= _CAL_EDIT_EVENT;
			$buttonstr	= _CAL_EDIT_BUT;
			$pgtitle 	= _CAL_EDIT_TITLE;
			$title 		= stripslashes($ar['calendar_msg_title']);
			$text 		= stripslashes($ar['calendar_msg_text']);
			$m 			= $ar['calendar_msg_m'];
			$d 			= $ar['calendar_msg_d'];
			$y 			= $ar['calendar_msg_y'];
		}
	} else {
		_CAL_ACCESS_WARNING;
	}
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<title>".$pgtitle."</title>\n";
	echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden_pop.css\">\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<span class=\"add_new_header\">".$headerstr." - ".$ar_admin['admin_nick']."</span>\n";
	echo "<br><img src=\"images/clear.gif\" width=\"1\" height=\"5\"><br>\n";
	echo "	<table border=0 cellspacing=7 cellpadding=0>\n";
	echo "	<form name=\"forma\" action=\"".$eden_cfg['url_cms']."modul_calendar_add.php?project=".$_GET['project']."&lid=".$_GET['lid']."\" method=\"post\">\n";
	echo "	<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap><span class=\"form_labels\">"._CAL_DATE."</span></td>\n";
	echo "			<td>";  VyberMesice($m, $cal_lang['months']); VyberDne($d); VyberRoku($y); echo "</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap>\n";
	echo "			<span class=\"form_labels\">"._CAL_TITLE."</span></td>\n";
	echo "			<td><input type=\"text\" name=\"title\" size=\"45\" value=\"".$title."\" maxlength=\"50\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap>\n";
	echo "			<span class=\"form_labels\">"._CAL_TEXT."</span></td>\n";
	echo "			<td><textarea cols=\"35\" rows=\"6\" name=\"text\">".stripslashes($text)."</textarea></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap>\n";
	echo "			<span class=\"form_labels\">"._CAL_CAT."</span></td>\n";
	echo "			<td><input type=\"radio\" name=\"category\" value=\"fw\" "; if ($ar['calendar_msg_category'] == "fw"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/fw.gif\" title=\"Friendly War\" alt=\"Friendly War\" width=\"30\" height=\"15\" border=\"0\">\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"cw\" "; if ($ar['calendar_msg_category'] == "cw"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/cw.gif\" title=\"Clan War\" alt=\"Clan War\" width=\"30\" height=\"15\" border=\"0\">\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"trenal\" "; if ($ar['calendar_msg_category'] == "trenal"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/trenal.gif\" title=\"Trenal\" alt=\"Trenal\" width=\"30\" height=\"15\" border=\"0\">\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"lanka\" "; if ($ar['calendar_msg_category'] == "lanka"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/lanka.gif\" title=\"Lan Party\" alt=\"Lan Party\" width=\"30\" height=\"15\" border=\"0\"><br>\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"work\" "; if ($ar['calendar_msg_category'] == "work"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/work.gif\" title=\"Work\" alt=\"Work\" width=\"30\" height=\"15\" border=\"0\">\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"narozeniny\" "; if ($ar['calendar_msg_category'] == "narozeniny"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/narozeniny.gif\" title=\"Narozeniny\" alt=\"Narozeniny\" width=\"30\" height=\"15\" border=\"0\">\n";
	echo "			<input type=\"radio\" name=\"category\" value=\"dalsi\" "; if ($ar['calendar_msg_category'] == "dalsi" || $_POST['action'] == "add"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/dalsi.gif\" title=\"Další\" alt=\"Další\" width=\"30\" height=\"15\" border=\"0\">\n";
				if ($_GET['project'] == "esuba"){
					echo "<input type=\"radio\" name=\"category\" value=\"fortuna\" "; if ($ar['calendar_msg_category'] == "fortuna" || $_POST['action'] == "add"){echo "checked=\"checked\"";} echo "><img src=\"images/calendar/fortuna.gif\" title=\"Fortuna\" alt=\"Fortuna\" width=\"30\" height=\"15\" border=\"0\">\n";
				}
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	// Spojeni s FTP serverem
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "var _img = new Array();\n";
	$d = ftp_nlist($conn_id, $ftp_path_calendar);
	$x = 0;
	while($entry = $d[$x]) {
		$x++;
		$entry = str_ireplace ($ftp_path_calendar,"",$entry); //Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != "..") {
			echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_calendar.$entry."\";\n";
		}
	}
	echo "	function doIt(_obj){\n";
	echo "  if (!_obj) return;\n";
	echo "	var _index = _obj.selectedIndex;\n";
	echo "	if (!_index) return;\n";
	echo "	var _item  = _obj[_index].id;\n";
	echo "	if (!_item) return;\n";
	echo "	if (_item < 0 || _item >= _img.length) return;\n";
	echo "		document.images['image'].src=_img[_item].src;\n";
	echo "	}\n";
	echo "//-->\n";
	echo "</script>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap><span class=\"form_labels\">"._CAL_GROUP."</span></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><select name=\"cal_group\" size=\"5\" onclick=\"doIt(this)\">\n";
						$x = 0;
						echo "<option value=\"0\">"._CAL_CHOOSE_GROUP."</option>\n";
						while($entry = $d[$x]) {
							$x++;
	           				$entry = str_ireplace ($ftp_path_calendar,"",$entry);//Odstrani cestu k ftp adresari
							if ($entry != "." && $entry != "..") {
								echo "<option id=\"".$x."\" value=\"".$entry."\""; if ($entry == $ar['calendar_msg_group']){ echo "selected=\"selected\"";} echo ">".$entry."</option>\n";
							}
						}
						ftp_close($conn_id);
	echo "					</select>&nbsp;&nbsp;\n";
	echo "					<img name=\"image\" src=\""; if ($ar['calendar_msg_group'] != ""){echo $url_calendar.$ar['calendar_msg_group'];} else {echo $url_calendar."empty.gif";} echo "\" border=\"0\"></td>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap><span class=\"form_labels\">"._CAL_START."</span></td>\n";
	echo "			<td>"; VyberHodiny($starttime[0], "start"); echo "<strong>:</strong>"; VyberMinuty($starttime[1], "start"); echo "</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td nowrap valign=\"top\" align=\"right\" nowrap><span class=\"form_labels\">"._CAL_END."</span></td>\n";
	echo "			<td>"; VyberHodiny($endtime[0], "end"); echo "<strong>:</strong>"; VyberMinuty($endtime[1], "end"); echo "</td>\n";
	echo "		</tr>\n";
	echo "		<tr><td></td><td><br><input type=\"submit\" value=\"".$buttonstr."\" class=\"eden_button\">&nbsp;<input type=\"button\" value=\""._CAL_CANCEL."\" onClick=\"window.close();\"></td></tr>\n";
	echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "	<input type=\"hidden\" name=\"url\" value=\"".$_GET['url']."\">\n";
	echo "	<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"".$_GET['action']."\">\n";
	echo "	<input type=\"hidden\" name=\"id\" value=\"".$def_id."\">\n";
	echo "	</form>\n";
	echo "	</table>\n";
	echo "</body>\n";
	echo "</html>";
}