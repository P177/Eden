<?php
/***********************************************************************************************************
*																											
*		MENU																								
*																											
***********************************************************************************************************/
function Menu() {
	echo "<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">&nbsp;\n";
	echo "<a href=\"modul_articles.php?project=".$_SESSION['project']."&amp;action=showmain&act=".$act."\">"._CUPS_ARTICLES_LIST."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "<a href=\"modul_articles.php?action=add&amp;project=".$_SESSION['project']."&act=".$act."\">"._CUPS_ADD_ARTICLES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "<a href=\"modul_cups_group.php?action=&amp;project=".$_SESSION['project']."\">"._CUPS_GROUP."</a>";
	if (CheckPriv("groups_cups_group_add") == 1 && $_GET['action'] != 'add_group') {
		echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_cups_group.php?action=add_group&amp;project=".$_SESSION['project']."\">"._CUPS_GROUP_ADD."</a>";
	}
	if (CheckPriv("groups_cups_group_add") == 1 && $_GET['action'] != 'upload_image') {
		echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"modul_cups_group.php?action=upload_image&amp;project=".$_SESSION['project']."\">"._CUPS_GROUP_UPLOAD_IMAGE."</a>";
	}
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU GROUPS																			
*																											
***********************************************************************************************************/
function ShowMain() {
	global $db_articles, $db_cups_group;
	global $article_groups_url;
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	if ($hits < 1) {
		$hits = 30;
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CUPS_GROUPS."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">";
	Menu();
	echo "</td>\n";
	echo "		<td align=\"right\">";
	//$amount = mysql_query("SELECT COUNT(*) FROM $db_cups_group") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	//$num = mysql_fetch_array($amount);
	//Timto nastavime pocet prispevku na strance
	$m = 0;
	// nastaveni iterace
	if (empty ($page)) {
		$page = 1;
	}
	// i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	//$hits=20; //Zde se nastavuje pocet prispevku
	$stw2 = ($num[0] / $hits);
	$stw2 = (integer) $stw2;
	if ($num[0] % $hits > 0) {
		$stw2++;
	}
	$np = $page + 1;
	$pp = $page - 1;
	if ($page == 1) {
		$pp = 1;
	}
	if ($np > $stw2) {
		$np = $stw2;
	}
	$sp = ($page - 1) * $hits;
	$ep = ($page - 1) * $hits + $hits;
	echo $sp;
	echo "-";
	echo $ep;
	$limit = "LIMIT ".(integer) $sp.", ".(integer) $hits."";
	$res_group = mysql_query("SELECT article_group_id, article_group_title, article_group_description, article_group_date, article_group_image FROM $db_cups_group ORDER BY article_group_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_group = mysql_num_rows($res_group);
	echo " "._CUPS_GROUP_GROUPS.": ".$num[0];
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"140\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._CUPS_GROUP_IMAGE."</span></td>\n";
	echo "		<td align=\"left\"><span class=\"nadpis-boxy\">"._CUPS_GROUP_TITLE."</span></td>\n";
	echo "	</tr>";
	while ($ar_group = mysql_fetch_array($res_group)) {
		$m++;
		echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
		echo "	<td width=\"140\" valign=\"top\">";
			// Kdyz clanek obsahuje vice kapitol zobrazi se tlacitko pro otevreni, jinak se zobrazi prazdne misto
		if (CheckPriv("groups_CUPS_group_edit") == 1 && $_SESSION['loginid'] !== "") {
			echo " <a href=\"modul_cups_group.php?action=edit_group&amp;id=".$ar_group['article_group_id']."&amp;project=".$_SESSION['project']."&amp;page=".$_GET['page']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>";
		}
		else {
			echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
		}
		if (CheckPriv("groups_CUPS_group_del") == 1 && $_SESSION['loginid'] != "") {
			/* Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani */
			echo " <a href=\"modul_cups_group.php?action=del_group&amp;id=".$ar_group['article_group_id']."&amp;project=".$_SESSION['project']."&amp;page=".$_GET['page']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>";
		}
		else {
			echo "<img src=\"images/a_bod.gif\" height=\"18\" width=\"22\" border=\"0\" alt=\"\">";
		}
		echo "	</td> \n";
		echo "	<td width=\"45\" valign=\"top\">".$ar_group['article_group_id']."</td>\n";
		echo "	<td width=\"100\" valign=\"top\"><img src=\"".$article_groups_url.$ar_group['article_group_image']."\" title=\"".$ar_group['article_group_title']."\"></td>\n";
		echo "	<td valign=\"top\">".$ar_group['article_group_title']."</td>\n";
		echo "</tr>";
		if ($_GET['action'] == "open" & $_GET['id'] == $ar_group['article_group_id']) {
			$res_group_article = mysql_query("SELECT article_id, article_date, article_date_edit, article_category_id FROM $db_articles WHERE article_cups_group_id=".(float) $ar_group['article_group_id']." AND article_publish=1 ORDER BY article_date_edit DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_group_article = mysql_fetch_array($res_group_article)) {
				$hlavicka = wordwrap($hlavicka, 40, "\n", 1);// Zalomi text
				$hlavicka = substr("$hlavicka", 0, 80);// Zobrazeni jen 80 znaku
				
				echo "<tr bgcolor=\"#EEEEEE\">\n";
				echo "	<td width=120\"><img src=\"images/a_bod.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"\">\n";
						if (CheckPriv($groups_priv_cups_edit) == 1 || $admini02 == "TRUE" || $_SESSION['loginid'] == $ar['article_author_id']) {
							/*Pokud nekdo edituje, nebo maze tak se nezobrazi ikona pro editaci a mazani*/
							if ($ar5['article_cups_bracket_user_use'] == "0" || $ar5['article_cups_bracket_user_use'] == $_SESSION['loginid']) {
								echo " <a href=\"modul_articles.php?action=edit&amp;id=".$ar5['article_id']."&amp;id2=".$ar5['article_category_id']."&parent_chap=".$ar5['article_parent_id']."&amp;project=".$_SESSION['project']."&kat=".$kat."&sa=".$sa."&act=".$act."&amp;page=".$_GET['page']."&podle=".$podle."&ser=".$ser."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>\n";
							}
						}
				echo "	</td>\n";
				echo "	<td>".$ar5['article_id']."</td>\n";
				echo "	<td>"; if ($ar5['article_cups_bracket_user_use'] != "0" && $ar5['article_cups_bracket_user_use'] != $_SESSION['loginid']) {echo "<img src=\"images/sys_use.gif\" width=\"18\" height=\"18\" border=\"0\" alt=\"".$ar5['article_cups_bracket_user_use']."\">";} echo $ar5['article_chapter_name']."</td>\n";
				echo "	<td align=\"center\" valign=\"top\" width=\"50\">".$picture."&nbsp;&nbsp;".$num7[0]."&nbsp;&nbsp;</td>\n";
				echo "	<td align=\"center\" width=\"70\" valign=\"top\">".$datumed."</td>\n";
				echo "	<td align=\"left\" width=\"150\" valign=\"top\">ID - ".$ar2['category_id']."<br><strong>".$name1."&raquo;</strong> ".$name2."</td>\n";
				echo "</tr>";
			}
		}
	}
	echo "</table>";
	// Pokud je novinek vice, nezli se vejde na 1 stranku zobrazi se jejich pocitani a prechazeni mezi nima 
	if ($stw2 > 1) { 
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "<tr><td height=\"30\">";
		echo _CMN_SELECTPAGE; 
		//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong>";
			} else {
				echo " <a href=\"modul_articles.php?page=".$i."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">".$i."</a> ";
			}
		}
		//Zobrazeni sipek s predchozimi a dalsimi strankami novinek
		if ($page > 1) { echo "<center><a href=\"modul_articles.php?page=".$pp."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_PREVIOUS."</a>";} else { echo "<br>"._CMN_PREVIOUS;} echo " <--|--> "; if ($page == $stw2) {echo _CMN_NEXT;} else {echo "<a href=\"modul_articles.php?page=".$np."&amp;project=".$_SESSION['project']."&amp;hits=".$hits."\">"._CMN_NEXT."</a></center>";}
		echo "</td></tr></table>";
	}
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE GROUPS																		
*																											
***********************************************************************************************************/
function AddGroup() {
	
	global $db_cups_group;
	global $article_groups_url;
	global $eden_cfg;
	global $ftp_path_CUPS_groups;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_cups_group_add") == 1) {
		/* Vstup povolen */
	} else {
		/* Vstup zamitnut */
		echo _NOTENOUGHPRIV;ShowMain();exit;
	}
	if ($_GET['action'] == "edit_group") {
		$res_group = mysql_query("SELECT article_group_id, article_group_title, article_group_description, article_group_date, article_group_image FROM $db_cups_group WHERE article_group_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_group = mysql_fetch_array($res_group);
	}
	
	/* Spojeni s FTP serverem */
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); 
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "var _img = new Array();";
	$d = ftp_nlist($conn_id, $ftp_path_cups_groups);
	$x = 0; 
	while($entry = $d[$x]) {
		$x++;
		$entry = str_ireplace ($ftp_path_cups_groups,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." && $entry != "..") {
			echo "_img[$x] = new Image(); _img[$x].src=\"$article_groups_url$entry\";\n";
		}
	}
	
	echo "function doIt(_obj) {\n";
	echo "	if (!_obj)return;\n";
	echo "	var _index = _obj.selectedIndex;\n";
	echo "	if (!_index) {return;}\n";
	echo "	var _item	= _obj[_index].id;\n";
	echo "	if (!_item) {return;}\n";
	echo "	if (_item<0 || _item >=_img.length) {return;}\n";
	echo "	document.images[\"image\"].src=_img[_item].src;\n";
	echo "}\n";
	echo "//-->\n";
	echo "</script>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CUPS_GROUPS." - "; if ($_GET['action'] == "add") {echo _CMN_ADD;} else {echo _CMN_EDIT;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">"; Menu(); echo "</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action="; if ($_GET['action'] == "edit_group") {echo "edit_group&amp;id=".$_GET['id']."";}elseif ($_GET['action'] == "add_group") {echo "add_group";} echo "\" method=\"post\" name=\"forma\"><strong>"._CUPS_GROUP_TITLE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><input type=\"text\" name=\"group_title\" value=\"".$ar_group['article_group_title']."\" size=\"60\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CUPS_GROUP_DESC."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><textarea name=\"group_desc\" rows=\"6\" cols=\"40\">".$ar_group['article_group_description']."</textarea></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CAT_IMAGE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\"><select name=\"image\" size=\"5\" onclick=\"doIt(this)\">";
				$d = ftp_nlist($conn_id, $ftp_path_CUPS_groups);
				$x = 0;
				echo "<option value=\"0\">"._CAT_CHOOSE_IMAGE."</option>\n";
				while($entry = $d[$x]) {
					$x++;
					$entry = str_ireplace ($ftp_path_cups_groups,"",$entry);//Odstrani cestu k ftp adresari
					if ($entry != "." && $entry != ".." && $entry != "None.gif") {
						echo "<option id=\"$x\" value=\"$entry\""; if ($entry == $ar_group['article_group_image']) { echo "selected=\"selected\"";} echo ">$entry</option>\n";
					}
				}
				ftp_close($conn_id);
	echo "			</select>&nbsp;&nbsp;\n";
	echo "			<img name=\"image\" src=\""; if ($ar_group['article_group_image'] != "") {echo $article_groups_url.$ar_group['article_group_image'];} else {echo $article_groups_url."None.gif";} echo "\" border=\"0\"></td>\n";
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
	
	global $article_groups_url;
	global $eden_cfg;
	global $ftp_path_CUPS_groups;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_cups_group_add") == 1) {
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
	
	/* Odstraneni vybranych obrazku */
	if ($_POST['confirm'] == "true") {
		/* CHECK PRIVILEGIES */
		if (CheckPriv("groups_CUPS_group_del") == 1) {
			/* Vstup povolen */
		} else {
			/* Vstup zamitnut */
			echo _NOTENOUGHPRIV;ShowMain();exit;
		}
		$img_num = count($_POST['img_data']);
		$i = 0;
		while ($i < $img_num) {
			$img = $_POST['img_data'][$i];
			if (ftp_delete($conn_id, $ftp_path_CUPS_groups.$img)) {
				echo _CUPS_GROUP_IMAGE." ".$img." "._CUPS_GROUP_IMAGE_DEL_OK."<br>\n";
			} else {
				echo _CUPS_GROUP_IMAGE_DEL_ER." ".$img.".<br>\n";
			}
			$i++;
		}
	}
	
	$d = ftp_nlist($conn_id, $ftp_path_CUPS_groups);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CUPS_GROUP_UPLOAD_IMAGE."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>"; Menu(); echo "</td>\n";
	echo "		<td align=\"left\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">".urldecode($_GET['msg'])."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><form action=\"sys_save.php?action=upload_group_img&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\"><strong>"._CMN_IMAGE."</strong></td>\n";
	echo "		<td><input type=\"file\" name=\"filename\" size=\"50\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
	echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" valign=\"top\" width=\"200\"><strong>"._CUPS_GROUP_IMAGES."</strong></td>\n";
	echo "		<td> ";
				if (CheckPriv("groups_CUPS_group_del") == 1) { echo "<form action=\"modul_cups_group.php?action=delete_group_img&amp;project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\">"; }
				$x = 0;
				while($entry = $d[$x]) {
					$x++;
					$entry = str_ireplace ($ftp_path_cups_groups,"",$entry);//Odstrani cestu k ftp adresari
					if ($entry != "." && $entry != ".." && $entry != "None.gif") {
						echo "<img src=\"".$article_groups_url.$entry."\" style=\"margin:5px;\" title=\"".$entry."\" align=\"middle\">"; if (CheckPriv("groups_CUPS_group_del") == 1) {echo "<input type=\"checkbox\" name=\"img_data[]\" value=\"".$entry."\">";} echo "&nbsp;".$entry."<br>";
					}
				}
				if (CheckPriv("groups_CUPS_group_del") == 1) {
					echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
					echo "<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\"><br clear=\"all\"><br>\n";
					echo "<input type=\"submit\" value=\""._CMN_DEL_CHOOSEN."\" class=\"eden_button_no\">\n";
					echo "</form>";
				}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		MAZANI GROUPS																						
*																											
***********************************************************************************************************/
function DeleteGroup() {
	
	global $db_cups_group;
	global $article_groups_url;
	
	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_CUPS_group_del") <> 1) { 
		echo _NOTENOUGHPRIV;
		ShowMain();
		exit;
	}
	/* Provede se pokud byl vybran souhlas s odstranenim kanalu */
	if ($_POST['confirm'] == "true") {
		mysql_query("DELETE FROM $db_cups_group WHERE article_group_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
		ShowMain();
		exit;
	}
	/* Provede se pokud byl vybran nesouhlas s odstranenim kanalu */
	if ($_POST['confirm'] == "false") {
		ShowMain();
		exit;
	}
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">"._CUPS_GROUP_DEL."</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>"; Menu(); echo "</td>\n";
	echo "		<td align=\"left\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"40\"><span class=\"nadpis-boxy\">"._CMN_ID."</span></td>\n";
	echo "		<td width=\"100\"><span class=\"nadpis-boxy\">"._CUPS_GROUP_IMAGE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CUPS_GROUP_TITLE."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT article_group_title, article_group_image FROM $db_cups_group WHERE article_group_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"40\">".$_GET['id']."</td>\n";
	echo "		<td valign=\"top\" width=\"100\"><img src=\"".$article_groups_url.$ar['article_group_image']."\"></td>\n";
	echo "		<td valign=\"top\">".$ar['article_group_title']."</td>\n";
	echo "	</tr>\";";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._CUPS_GROUP_CHECK_DEL."</span></strong></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" width=\"50\">\n";
	echo "		<form action=\"modul_cups_group.php?action=del_group&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			</form>\n";
 	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"800\">\n";
	echo "			<form action=\"modul_cups_group.php?action=del_group&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
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
	if ($_GET['action'] == "edit_group") { AddGroup(); }
	if ($_GET['action'] == "del_group") { DeleteGroup(); }
	if ($_GET['action'] == "add_group") { AddGroup(); }
	if ($_GET['action'] == "open") { ShowMain(); }
	if ($_GET['action'] == "close") { ShowMain(); }
	if ($_GET['action'] == "upload_image") { UploadImage(); }
	if ($_GET['action'] == "delete_group_img") { UploadImage(); }
include ("inc.footer.php");