<?php
/***********************************************************************************************************
*
*		ZOBRAZENI BANU
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_ban,$db_articles,$db_comments;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	$res = mysql_query("SELECT ban_id, ban_date, INET_NTOA(ban_ip) AS ban_ip, ban_user, ban_comment FROM $db_ban ORDER BY ban_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_num_rows($res);
	echo "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._BAN_MODUL."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
					if ($_GET['action'] == "ban_search"){ echo "<a href=\"modul_ban.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;"; } echo "<a href=\"modul_ban.php?action=ban_add&amp;project=".$_SESSION['project']."\">"._BAN_ADD."</a>\n";
	echo "			<form action=\"modul_ban.php?action=ban_search\" method=\"post\">\n";
	echo "			<input type=\"text\" class=\"search\" name=\"ip_address\" size=\"30\" maxlength=\"40\" value=\"".$_POST['ip_address']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" class=\"eden_button\" value=\""._BAN_SEARCH."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['action'] == "ban_del" && $_POST['confirm'] != "true"){$_GET['msg'] = "ban_del_ch";}
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\">";
	if ($_GET['action'] == "ban_search"){
		$res2 = mysql_query("SELECT comment_pid, comment_author, comment_subject, comment_text, comment_date FROM $db_comments WHERE comment_ip=INET_ATON('".mysql_real_escape_string($_POST['ip_address'])."') ORDER BY comment_date DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		echo "<tr class=\"popisky\">\n";
		echo "		<td width=\"80\"><span class=\"nadpis-boxy\">"._BAN_NICK."</span></td>\n";
		echo "		<td width=\"350\"><span class=\"nadpis-boxy\">"._BAN_TOPIC."</span></td>\n";
		echo "		<td width=\"300\"><span class=\"nadpis-boxy\">"._BAN_COM_TOPIC."</span></td>\n";
		echo "		<td width=\"160\"><span class=\"nadpis-boxy\">"._BAN_DATE."</span></td>\n";
		echo "	</tr>";
 		$i=1;
		while ($ar2 = mysql_fetch_array($res2)){
			$res3 = mysql_query("SELECT article_headline FROM $db_articles WHERE article_id=".(integer)$ar2['comment_pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar3 = mysql_fetch_array($res3);
			$cislo = $i;
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\">"; /* stridani barev prispevku podle sudeho nebo licheho radku */
			echo "	<td width=\"80\">".$ar2['comment_author']."</td>\n";
			echo "	<td width=\"350\"><strong>".$ar3['article_headline']."</strong></td>\n";
			echo "	<td width=\"300\">".$ar2['comment_subject']."</td>\n";
			echo "	<td width=\"160\" align=\"left\" valign=\"top\">".FormatDatetime($ar2['comment_date'],"d.m.Y H:i:s")."</td>\n";
			echo "</tr>\n";
			echo "<tr class=\"".$cat_class."\">"; /* stridani barev prispevku podle sudeho nebo licheho radku */
			echo "	<td width=\"857\" colspan=\"4\">".$ar2['comment_text']."<br><br></td>\n";
			echo "</tr>";
 			$i++;
		}
	} else {
		echo "<tr class=\"popisky\">\n";
		echo "	<td width=\"90\"><span class=\"nadpis-boxy\">"._BAN_OPTIONS."</span></td>\n";
		echo "	<td width=\"67\"><span class=\"nadpis-boxy\">"._BAN_ID."</span></td>\n";
		echo "	<td width=\"350\"><span class=\"nadpis-boxy\">"._BAN_IP."</span></td>\n";
		echo "	<td width=\"150\"><span class=\"nadpis-boxy\">"._BAN_USER_NAME."</span></td>\n";
		echo "	<td width=\"200\"><span class=\"nadpis-boxy\">"._BAN_DATUM."</span></td>\n";
		echo "</tr>";
		$i=1;
 		while ($ar = mysql_fetch_array($res)){
			if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
			echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
			echo "<td width=\"90\"><a href=\"modul_ban.php?action=ban_open&amp;id=".$ar['ban_id']."&amp;project=".$_SESSION['project']."\"><a href=\"modul_ban.php?action=ban_edit&amp;id=".$ar['ban_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\" title=\""._CMN_EDIT."\"></a> <a href=\"modul_ban.php?action=ban_del&amp;id=".$ar['ban_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._DELETE."\" title=\""._DELETE."\"></a></td>\n";
			echo "	<td width=\"67\">".$ar['ban_id']."</td>\n";
			echo "	<td width=\"350\">".$ar['ban_ip']."</td>\n";
			echo "	<td width=\"150\" align=\"left\" valign=\"top\">".stripslashes($ar['ban_user'])."</td>\n";
			echo "	<td width=\"200\" align=\"left\" valign=\"top\">".FormatDate($ar['ban_date'],'d.m.Y')."</td>\n";
			echo "</tr>";
			$i++;
		}
	}
	echo "</table>";
}

/***********************************************************************************************************
*
*		PRIDAVANI A EDITACE BANU
*
***********************************************************************************************************/
function AddBan(){
	
	global $db_ban;
	
	// CHECK PRIVILEGIES
	if ($_GET['action'] == "ban_add"){
		if (CheckPriv("groups_ban_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET[action] == "ban_edit"){
		if (CheckPriv("groups_ban_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	$res = mysql_query("SELECT  ban_id, ban_date, INET_NTOA(ban_ip) AS ban_ip, ban_user, ban_comment  FROM $db_ban WHERE ban_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($_GET['action'] == "ban_add"){
		$date_to = date("d.m.Y",time());
	} else {
		$date_to = FormatDate($ar['ban_date'],"d.m.Y");
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._BAN_MODUL." - "; if ($_GET['action'] == "ban_add"){echo _CMN_ADD;} else {echo _CMN_EDIT;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "			<a href=\"modul_ban.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a><br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\"></td><form action=\"sys_save.php?action=".$_GET['action']."\" method=\"post\" name=\"forma\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_IP."</strong>&nbsp;</td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"ban_ip\" size=\"30\" maxlength=\"40\" value=\"".$ar['ban_ip']."\"><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_USER_NAME."</strong>&nbsp;</td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"ban_user\" size=\"60\"  maxlength=\"80\" value=\"".$ar['ban_user']."\"><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_DATUM."</strong>&nbsp;</td>\n";
	echo "		<td align=\"left\" valign=\"top\">";
	echo "					<script language=\"javascript\">\n";
	echo "					var ToDate = new ctlSpiffyCalendarBox(\"ToDate\", \"forma\", \"ban_date\", \"btnDate1\",\"".$date_to."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "					</script>\n";
	echo "					<script language=\"javascript\">ToDate.writeControl(); ToDate.dateFormat=\"dd.MM.yyyy\";</script>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_COMMENT."</strong>&nbsp;</td>\n";
	echo "		<td align=\"left\" valign=\"top\"><textarea cols=\"45\" rows=\"5\" name=\"ban_comment\">".$ar['ban_comment']."</textarea><br><br></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}

/***********************************************************************************************************
*
*		MAZANI BANU
*
***********************************************************************************************************/
function DeleteBan(){
	
	global $db_ban;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_ban_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	$res = mysql_query("SELECT  ban_id, INET_NTOA(ban_ip) AS ban_ip, ban_user FROM $db_ban WHERE ban_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\">"._BAN_DEL."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td><img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
	echo "			<a href=\"modul_ban.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_IP.":</strong></td>";
	echo "		<td align=\"left\" valign=\"top\">".$ar['ban_ip']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._BAN_USER_NAME.":</strong></td>";
	echo "		<td align=\"left\" valign=\"top\">".$ar['ban_user']."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._BAN_CHECK_DELETE."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
   	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_save.php?action=ban_del\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\" >\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_ban.php?action=showmain\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\"></form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include ("inc.header.php");
		if ($_GET['action'] == "") { ShowMain(); }
		if ($_GET['action'] == "showmain") { ShowMain(); }
		if ($_GET['action'] == "ban_edit") { AddBan(); }
		if ($_GET['action'] == "ban_add") { AddBan(); }
		if ($_GET['action'] == "ban_del") { DeleteBan(); }
		if ($_GET['action'] == "ban_open") { ShowMain(); }
		if ($_GET['action'] == "ban_search") {ShowMain();}
include ("inc.footer.php");