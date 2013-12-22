<?php
/***********************************************************************************************************
*																											
*		MENU																								
*																											
***********************************************************************************************************/
function Menu() {
	
	if ($_GET['action'] == "article_channel_add") {
		$title = " - "._CMN_ADD;
	} elseif ($_GET['action'] == "article_channel_edit") {
		$title = " - "._CMN_EDIT;
	} elseif ($_GET['action'] == "article_channel_del") {
		$title = " - "._CMN_DEL;
	} elseif ($_GET['action'] == "article_channel_del") {
		$title = " - "._ARTICLES_CHANNEL_UPLOAD_IMAGE;
	}
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._ARTICLES_CHANNELS.$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\">";
	$menu .= "<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;<a \n";
	$menu .= "href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=showmain&act=".$act."\">"._ARTICLES_ARTICLES_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a \n";
	$menu .= "href=\"modul_articles.php?action=add&amp;project=".$_SESSION['project']."&act=".$act."\">"._ARTICLES_ADD_ARTICLES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a \n";
	$menu .= "href=\"modul_articles_channel.php?action=showmain&amp;project=".$_SESSION['project']."\">"._ARTICLES_CHANNEL."</a>";
	if (CheckPriv("groups_article_channel_add") == 1 && $_GET['action'] != 'article_channel_add') {
		$menu .= "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_articles_channel.php?action=article_channel_add&amp;project=".$_SESSION['project']."\">"._ARTICLES_CHANNEL_ADD."</a>";
	}
	if (CheckPriv("groups_article_channel_add") == 1 && $_GET['action'] != 'article_channel_upload_img') {
		$menu .= "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_articles_channel.php?action=article_channel_upload_img&amp;project=".$_SESSION['project']."\">"._ARTICLES_CHANNEL_UPLOAD_IMAGE."</a>";
	}
	$menu .= "		</td>\n";
	$menu .= "	</tr>\n";
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>\n";
	
	return $menu;
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU CHANNELS																			
*																											
***********************************************************************************************************/
function ShowMain() {
	global $db_articles, $db_articles_channel;
	global $url_articles_channels;
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	$amount = mysql_query("SELECT COUNT(*) FROM $db_articles_channel") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num = mysql_fetch_array($amount);
	
	$res_channel = mysql_query("SELECT article_channel_id, article_channel_title, article_channel_description, article_channel_date, article_channel_image, 
	article_channel_importance, article_channel_active, language_name 
	FROM $db_articles_channel 
	JOIN "._DB_LANGUAGES." ON language_id = article_channel_lang_id 
	ORDER BY article_channel_active DESC, article_channel_importance DESC, article_channel_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_channel = mysql_num_rows($res_channel);
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"140\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_IMAGE."</span></td>\n";
	echo "		<td width=\"50\" align=\"left\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_IMPORTANCE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_TITLE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_LANG."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_ACTIVE."</span></td>\n";
	echo "	</tr>";
	$i = 0;
	while ($ar_channel = mysql_fetch_array($res_channel)) {
		if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
		echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
		echo "	<td width=\"140\" valign=\"top\">";
		// Kdyz clanek obsahuje vice kapitol zobrazi se tlacitko pro otevreni, jinak se zobrazi prazdne misto
		if (CheckPriv("groups_article_channel_edit") == 1 && $_SESSION['loginid'] !== "") {
			echo " <a href=\"modul_articles_channel.php?action=article_channel_edit&amp;id=".$ar_channel['article_channel_id']."&amp;project=".$_SESSION['project']."&amp;page=".$_GET['page']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
		} else {
			echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
		}
		if (CheckPriv("groups_article_channel_del") == 1 && $_SESSION['loginid'] != "") {
		/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */
			echo " <a href=\"modul_articles_channel.php?action=article_channel_del&amp;id=".$ar_channel['article_channel_id']."&amp;project=".$_SESSION['project']."&amp;page=".$_GET['page']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";
		} else {
			echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
		}
		echo "	</td> \n";
		echo "	<td width=\"45\" valign=\"top\">".$ar_channel['article_channel_id']."</td>\n";
		echo "	<td width=\"100\" valign=\"top\"><img src=\"".$url_articles_channels.$ar_channel['article_channel_image']."\" title=\"".stripslashes($ar_channel['article_channel_title'])."\"></td>\n";
		echo "	<td width=\"50\" valign=\"top\">".$ar_channel['article_channel_importance']."</td>\n";
		echo "	<td valign=\"top\">".stripslashes($ar_channel['article_channel_title'])."</td>\n";
		echo "	<td valign=\"top\">".stripslashes($ar_channel['language_name'])."</td>\n";
		echo "	<td valign=\"top\"><img src=\"images/sys_"; if ($ar_channel['article_channel_active'] == 1){echo "yes";} else {echo "no";} echo ".gif\" alt=\"\" width=\"18\" height=\"18\" border=\"0\"></td>\n";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE CHANNELS																		
*																											
***********************************************************************************************************/
function AddChannel() {
	
	global $db_articles_channel;
	global $eden_cfg;
	global $url_articles_channels;
	global $ftp_path_article_channels;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_article_channel_add") == 1) {
		/* Vstup povolen */
	} else {
		/* Vstup zamitnut */
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_GET['action'] == "article_channel_edit") {
		$res_channel = mysql_query("SELECT article_channel_id, article_channel_lang_id, article_channel_title, article_channel_description, article_channel_date, 
		article_channel_image, article_channel_importance, article_channel_active 
		FROM $db_articles_channel 
		WHERE article_channel_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_channel = mysql_fetch_array($res_channel);
	}
	
	$res_lang = mysql_query("SELECT language_id, language_name 
	FROM "._DB_LANGUAGES." 
	WHERE language_active = 1") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	echo "<SCRIPT type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "var _img = new Array();";
	$d = ftp_nlist($conn_id, $ftp_path_article_channels);
	$x = 0; 
	
	while($entry = $d[$x]) {
		$x++;
		$entry = str_ireplace ($ftp_path_article_channels,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != "..") {
			echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_articles_channels.$entry."\";\n";
		}
	}
	echo "function doIt(_obj) {\n";
	echo "	if (!_obj)return;\n";
	echo "	var _index = _obj.selectedIndex;\n";
	echo "	if (!_index)return;\n";
	echo "	var _item	= _obj[_index].id;\n";
	echo "	if (!_item)return;\n";
	echo "	if (_item<0 || _item >=_img.length)return;\n";
	echo "	document.images[\"image\"].src=_img[_item].src;\n";
	echo "}\n";
	echo "//-->\n";
	echo "</script>\n";
	
	echo Menu();
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "article_channel_edit") {echo "article_channel_edit&amp;id=".$_GET['id']."";}elseif ($_GET['action'] == "article_channel_add") {echo "article_channel_add";} echo "&amp;project=".$_SESSION['project']."\" method=\"post\" name=\"forma\"><strong>"._ARTICLES_CHANNEL_ACTIVE."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"checkbox\" name=\"channel_active\" value=\"1\" "; if ($ar_channel['article_channel_active'] == 1 || $_GET['action'] == "article_channel_add") {echo "checked=\"checked\"";} echo "></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ARTICLES_CHANNEL_TITLE."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"channel_title\" value=\"".$ar_channel['article_channel_title']."\" size=\"60\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_IMAGE."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><select name=\"channel_lang_id\" size=\"1\">";
				echo "<option value=\"0\">"._ARTICLES_CHANNEL_CHOOSE_LANG."</option>\n";
				while($ar_lang = mysql_fetch_array($res_lang)) {
						echo "<option value=\"".$ar_lang['language_id']."\""; if ($_GET['action'] == "article_channel_edit" && $ar_channel['article_channel_lang_id'] == $ar_lang['language_id']) { echo "selected=\"selected\"";} echo ">".$ar_lang['language_name']."</option>\n";
				}
	echo "			</select>&nbsp;&nbsp;\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ARTICLES_CHANNEL_DESC."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><textarea name=\"channel_desc\" rows=\"6\" cols=\"40\">".$ar_channel['article_channel_description']."</textarea></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_IMAGE."</strong></td>\n";
	echo "			<td align=\"left\" valign=\"top\"><select name=\"image\" size=\"5\" onclick=\"doIt(this)\">";
				$d = ftp_nlist($conn_id, $ftp_path_article_channels);
				$x = 0;
				echo "<option value=\"0\">"._CAT_CHOOSE_IMAGE."</option>\n";
				while($entry = $d[$x]) {
					$x++;
					$entry = str_ireplace ($ftp_path_article_channels,"",$entry);//Odstrani cestu k ftp adresari
					if ($entry != "." && $entry != ".." && $entry != "None.gif") {
						echo "<option id=\"".$x."\" value=\"".$entry."\""; if ($entry == $ar_channel['article_channel_image']) { echo "selected=\"selected\"";} echo ">".$entry."</option>\n";
					}
				}
				ftp_close($conn_id);
	echo "			</select>&nbsp;&nbsp;\n";
	echo "			<img name=\"image\" src=\""; if ($ar_channel['article_channel_image'] != "") {echo $url_articles_channels.$ar_channel['article_channel_image'];} else {echo $url_articles_channels."None.gif";} echo "\" border=\"0\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ARTICLES_CHANNEL_IMPORTANCE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"channel_importance\">\n";
				for ($i=0;$i<10;$i++) {
					echo "<option value=\"".$i."\""; if ($ar_channel['article_channel_importance'] == $i) {echo "selected=\"selected\"";} echo ">".$i."</option>\n";
				}
	echo "		</select></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		NAHRAVANI OBRAZKU																					
*																											
***********************************************************************************************************/
function UploadImage() {
	
	global $url_articles_channels;
	global $eden_cfg;
	global $ftp_path_article_channels;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_article_channel_add") == 1) {
		/* Vstup povolen */
	} else {
		/* Vstup zamitnut */
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	$d = ftp_nlist($conn_id, $ftp_path_article_channels);
	
	echo  Menu();
	
	/* Odstraneni vybranych obrazku */
	if ($_POST['confirm'] == "true") {
		/* CHECK PRIVILEGIES */
		if (CheckPriv("groups_article_channel_del") == 1) {
			/* Vstup povolen */
		} else {
			/* Vstup zamitnut */
			echo _NOTENOUGHPRIV;ShowMain();exit;
		}
		$img_num = count($_POST['img_data']);
		$i = 0;
		while ($i < $img_num) {
			$img = $_POST['img_data'][$i];
			echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
			if (ftp_delete($conn_id, $ftp_path_article_channels.$img)) {
				echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">"._ARTICLES_CHANNEL_IMAGE." ".$img." "._ARTICLES_CHANNEL_IMAGE_DEL_OK."</td></tr>";
			} else {
				echo "<tr><td style=\"color:#ff0000;padding:10px 0px 10px 10px;font-weight:bold;\">"._ARTICLES_CHANNEL_IMAGE_DEL_ER." ".$img.".</td></tr>";
			}
			echo "</table>";
			$i++;
		}
	}
	
	echo "	<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action=article_channel_upload_img&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\"><strong>"._CMN_IMAGE."</strong></td>\n";
	echo "		<td><input type=\"file\" name=\"filename\" size=\"50\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
	echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._ARTICLES_CHANNEL_IMAGES."</strong></td>\n";
	echo "		<td>";
	if (CheckPriv("groups_article_channel_del") == 1) { echo "<form action=\"modul_articles_channel.php?action=article_channel_del_img&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\">"; }
	$x = 0;
	while($entry = $d[$x]) {
		$x++;
		$entry = str_ireplace ($ftp_path_article_channels,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != ".." && $entry != "None.gif") {
			echo "<img src=\"".$url_articles_channels.$entry."\" style=\"margin:5px;\" title=\"".$entry."\" align=\"middle\">"; if (CheckPriv("groups_article_channel_del") == 1) {echo "<input type=\"checkbox\" name=\"img_data[]\" value=\"".$entry."\">";} echo "&nbsp;".$entry."<br>";
		}
	}
	if (CheckPriv("groups_article_channel_del") == 1) {
		echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
		echo "<input type=\"submit\" value=\""._CMN_DEL_CHOOSEN."\" class=\"eden_button\">\n";
		echo "</form>";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		MAZANI CHANNELS																						
*																											
***********************************************************************************************************/
function DeleteChannel() {
	
	global $db_articles_channel;
	global $url_articles_channels;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_article_channel_del") <> 1) { 
		echo _NOTENOUGHPRIV;
		ShowMain();
		exit;
	}
	
	$res = mysql_query("SELECT article_channel_title, article_channel_image FROM $db_articles_channel WHERE article_channel_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	echo  Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"40\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"100\"><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_IMAGE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._ARTICLES_CHANNEL_TITLE."</span></td>\n";
	echo "	</tr>";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"40\">".$_GET['id']."</td>\n";
	echo "		<td valign=\"top\" width=\"100\"><img src=\"".$url_articles_channels.$ar['article_channel_image']."\"></td>\n";
	echo "		<td valign=\"top\">".$ar['article_channel_title']."</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._ARTICLES_CHANNEL_CHECK_DEL."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"sys_save.php?action=article_channel_del&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
	echo "			\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_articles_channel.php?action=showmain&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
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
	if ($_GET['action'] == "article_channel_edit") { AddChannel(); }
	if ($_GET['action'] == "article_channel_del") { DeleteChannel(); }
	if ($_GET['action'] == "article_channel_add") { AddChannel(); }
	if ($_GET['action'] == "article_channel_upload_img") { UploadImage(); }
	if ($_GET['action'] == "article_channel_del_img") { UploadImage(); }
include ("inc.footer.php");