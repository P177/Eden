<?php
/***********************************************************************************************************
*
*		RSS - MENU
*
***********************************************************************************************************/
function Menu(){
	
	switch ($_GET['action']){
		case "rss_add":
			$title = _RSS_CHANNELS." - "._RSS_ADD;
			break;
		case "rss_edit":
			$title = _RSS_CHANNELS." - "._RSS_EDIT;
			break;
		case "rss_del":
			$title = _RSS_CHANNELS." - "._RSS_DEL;
			break;
		default:
			$title = _RSS_CHANNELS;
	}
	
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "<tr>\n";
	$menu .= "	<td align=\"left\" class=\"nadpis\">"._RSS_CHANNELS."</td>\n";
	$menu .= "</tr>\n";
	$menu .= "<tr>\n";
	$menu .= "	<td>";
	$menu .= "	<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\">";
	$menu .= "	<a href=\"modul_rss.php?action=showmain&amp;project=".$_SESSION['project']."\">"._RSS_MAIN."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "	<a href=\"modul_rss.php?action=rss_add&amp;project=".$_SESSION['project']."\">"._RSS_ADD."</a>";
	$menu .= "	</td>\n";
	$menu .= "</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>";
	
	return $menu;
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU RSS KANALU
*
***********************************************************************************************************/
function ShowMain(){
	
	global $db_rss,$db_rss_lang;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"30\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"30\" align=\"left\"><span class=\"nadpis-boxy\">"._RSS_ALLOWED."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._RSS_ARTICLES_NUMBER."</span></td>\n";
	echo "		<td width=\"300\" align=\"left\"><span class=\"nadpis-boxy\">"._RSS_TITLE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._RSS_LANG."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT r.*, rl.* FROM $db_rss AS r LEFT JOIN $db_rss_lang AS rl ON r.rss_lang = rl.rss_lang_id ORDER BY r.rss_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$i=1;
	while ($ar = mysql_fetch_array($res)){
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"65\"><a href=\"modul_rss.php?action=rss_edit&amp;id=".$ar['rss_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_rss.php?action=rss_del&amp;id=".$ar['rss_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td>\n";
		echo "	<td width=\"30\" align=\"right\">".$ar['rss_id']."</td>\n";
		echo "	<td width=\"30\" align=\"center\"><img src=\"images/sys_"; if ($ar['rss_allow'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "	<td width=\"50\" align=\"right\">".$ar['rss_number']."</td>\n";
		echo "	<td width=\"300\">".$ar['rss_title']."</td>\n";
		echo "	<td>".$ar['rss_lang_name']."</td>\n";
		echo "</tr>";
		$i++;
   	}
	echo "</table>";
}
/***********************************************************************************************************
*
*		PRIDANI RSS KANALU
*
***********************************************************************************************************/
function AddRSS(){
	
	global $db_rss,$db_rss_lang;
	global $url_rss;
	
	/* Provereni opravneni */
	if ($_GET['action'] == "rss_add"){
		if (CheckPriv("groups_rss_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "rss_edit"){
		if (CheckPriv("groups_rss_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		 echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_GET['action'] == "rss_edit"){
		$res = mysql_query("SELECT * FROM $db_rss WHERE rss_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	
	echo Menu();
	
	if ($_GET['action'] == "rss_add" || $_GET['action'] == "rss_edit"){
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "rss_add"){echo "rss_add";} else {echo "rss_edit";} echo "&amp;id=".$_GET['id']."\" method=\"post\" enctype=\"multipart/form-data\" method=\"post\">\n";
		echo "			"._RSS_ALLOW_RSS."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"rss_allow\" "; if ($ar['rss_allow'] == 1){echo "checked";} echo " value=\"1\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_TITLE."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_title\" size=\"60\" maxlength=\"255\" value=\"".$ar['rss_title']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_LINK."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_link\" size=\"60\" maxlength=\"255\" value=\"".$ar['rss_link']."\"> http://www.yourweb.com/</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_DESCRIPTION."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_description\" size=\"100\" maxlength=\"255\" value=\"".$ar['rss_description']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_WEBMASTER."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_webmaster\" size=\"60\" value=\"".$ar['rss_webmaster']."\"> name@yourweb.com (Nick/Name)</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_MANAGINGEDITOR."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_managingeditor\" size=\"60\" value=\"".$ar['rss_managingeditor']."\"> name@yourweb.com (Nick/Name)</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_COPYRIGHT."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_copyright\" size=\"60\" maxlength=\"255\" value=\"".$ar['rss_copyright']."\"> (C) = ©</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CATEGORY."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_category\" size=\"60\" maxlength=\"255\" value=\"".$ar['rss_category']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CATEGORY_DOMAIN."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_category_domain\" size=\"60\" value=\"".$ar['rss_category_domain']."\"> http://www.yourweb.com/something</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_NUMBER_OF_ARTICLES."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><select name=\"rss_number\">\n";
					$i = 1;
					while($i < 101){
						echo "<option value=\"".$i."\""; if ($ar['rss_number'] == $i || ($_GET['action'] == "rss_add" && $i == 10)){ echo "selected=\"selected\"";} echo ">".$i."</option>";
						$i++;
					}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_TTL."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><select name=\"rss_ttl\">\n";
					$i = 10;
					while($i < 1000){
						echo "<option value=\"".$i."\""; if ($ar['rss_ttl'] == $i || ($_GET['action'] == "rss_add" && $i == 60)){ echo "selected=\"selected\"";} echo ">".$i."</option>";
						$i = $i + 10;
					}
		echo "		</select> min.</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CHANNEL_IMAGE."</td>\n";
		echo "		<td align=\"left\"><input type=\"file\" name=\"rss_channel_img\" size=\"50\"><br>\n";
						if ($ar['rss_image'] != ""){echo "<img src=\"".$url_rss.$ar['rss_image']."\" alt=\"".$ar['rss_image_title']."\" border=\"0\">";}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CHANNEL_IMAGE_TITLE."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_image_title\" size=\"60\" maxlength=\"255\" value=\"".$ar['rss_image_title']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CHANNEL_IMAGE_LINK."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_image_link\" size=\"100\" maxlength=\"255\" value=\"".$ar['rss_image_link']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_CHANNEL_IMAGE_DESC."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><input type=\"text\" name=\"rss_image_description\" size=\"100\" maxlength=\"255\" value=\"".$ar['rss_image_description']."\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td width=\"250\">"._RSS_LANG."</td>\n";
		echo "		<td width=\"500\" align=\"left\"><select name=\"rss_lang\">\n";
						$res2 = mysql_query("SELECT rss_lang_id, rss_lang_name FROM $db_rss_lang ORDER BY rss_lang_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar2 = mysql_fetch_array($res2)){
						echo "<option name=\"rss_lang\" value=\"".$ar2['rss_lang_id']."\""; if ($ar['rss_lang'] == $ar2['rss_lang_id'] || ($_GET['action'] == "rss_add" && $ar2['rss_lang_id'] == 10)){ echo "selected=\"selected\"";} echo ">".$ar2['rss_lang_name']."</option>";
					}
		echo "		</select></td>\n";
		echo "	</tr>\n";
		echo "	<tr align=\"right\" valign=\"top\">\n";
		echo "		<td colspan=\"2\" width=\"250\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
 	}
}
/***********************************************************************************************************
*
*		ODSTRANENI RSS KANALU
*
***********************************************************************************************************/
function DelRSS(){
	
	global $db_rss,$db_rss_lang;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_rss_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"30\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._RSS_TITLE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._RSS_LANG."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT r.rss_id, r.rss_title, rl.rss_lang_name FROM $db_rss AS r, $db_rss_lang AS rl WHERE r.rss_id=".(integer)$_GET['id']." AND rl.rss_lang_id=r.rss_lang") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	echo "<tr>\n";
	echo "	<td width=\"30\" align=\"right\" valign=\"top\">".$ar['rss_id']."</td>\n";
	echo "	<td valign=\"top\" align=\"left\">".$ar['rss_title']."</td>\n";
	echo "	<td valign=\"top\" align=\"left\">".$ar['rss_lang_name']."</td>\n";
	echo "</tr>";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._RSS_CHECKDELETE."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<form action=\"sys_save.php?action=rss_del\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<form action=\"modul_rss.php?action=showmain\" method=\"post\">\n";
	echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
	echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
include "inc.header.php";
	if ($_GET['action'] == "" || $_GET['action'] == "showmain"){ShowMain();}
	if ($_GET['action'] == "rss_add"){AddRSS();}
	if ($_GET['action'] == "rss_edit"){AddRSS();}
	if ($_GET['action'] == "rss_del"){DelRSS();}
include "inc.footer.php";