<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI UKOLU V TODO																				
*																											
***********************************************************************************************************/
function Menu(){
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" class=\"nadpis\" colspan=\"2\">"; if ($_GET['action'] == "add_todo_category" || $_GET['action'] == "edit_todo_category" || $_GET['action'] == "del_todo_category"){ echo _TODO_CATEGORY;} else {echo _TODO; } echo " - "; if ($_GET['action'] == "add_todo"){echo _TODO_ADD;} elseif ($_GET['action'] == "edit_todo"){echo _TODO_EDIT;} elseif ($_GET['action'] == "del_todo"){ echo _TODO_DEL;} else {echo _TODO_SHOW_TASKS; } echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td><img src=\"images/sys_manage.gif\" border=0>\n";
	echo "		<a href=\"modul_todo.php?project=".$_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "		<a href=\"modul_todo.php?action=add_todo&amp;project=".$_SESSION['project']."\">"._TODO_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	echo "		<a href=\"modul_todo.php?action=add_todo_category&amp;project=".$_SESSION['project']."\">"._TODO_CATEGORY."</a>\n";
	echo "	</td>\n";
	echo "</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		echo "<tr><td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SMAILIKU																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_todo,$db_todo_category;
	global $url_todo_category;
	
	if (CheckPriv("groups_todo_add") <> 1) {NotEnoughPriv();exit;}
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	Menu();
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._TODO_IMG."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._TODO_DATE_DEADLINE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._TODO_NAME."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._TODO_EMOTION."</span></td>\n";
	echo "	</tr>";
			$res = mysql_query("SELECT * FROM $db_todo") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=1;
			while ($ar = mysql_fetch_array($res)){
				$res_cat = mysql_query("SELECT todo_category_id, todo_category_name, todo_category_image FROM $db_todo_category WHERE todo_category_id=".$ar['todo_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_cat = mysql_fetch_array($res_cat);
				if ($ar_cat['todo_category_image'] != ""){$todo_category_image = $ar_cat['todo_category_image'];} else {$todo_category_image = "0.gif";}
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";
				echo "	<td width=\"80\" valign=\"top\"><a href=\"modul_todo.php?action=edit_todo&amp;id=".$ar['todo_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_todo.php?action=del_todo&amp;id=".$ar['todo_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td>\n";
				echo "	<td valign=\"top\" width=\"20\"><img src=\"".$url_todo_category.$todo_category_image."\" alt=\"".stripslashes($ar_cat['todo_category_name'])."\" title=\"".stripslashes($ar_cat['todo_category_name'])."\"></td>\n";
				echo "	<td valign=\"top\" width=\"110\">".FormatDatetime($ar['todo_date_deadline'],"d.m.Y H:i")."</td>\n";
				echo "	<td valign=\"top\">".$ar['todo_name']."</td>\n";
				echo "	<td valign=\"top\">".$ar['todo_emotion']."</td>";
				echo "	</tr>";
				$i++;
			}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE SMAILIKU																		
*																											
***********************************************************************************************************/
function AddTodo(){
	
	global $db_todo,$db_todo_category;
	global $eden_cfg;
	global $ftp_path_todo;
	global $url_todo;
	
	// Provereni opravneni
	if ($_GET['action'] == "add_todo"){
		if (CheckPriv("groups_todo_add") <> 1) {NotEnoughPriv();exit;}
	}elseif ($_GET['action'] == "edit_todo"){
		if (CheckPriv("groups_todo_edit") <> 1){NotEnoughPriv();exit;}
	} else {
		NotEnoughPriv();
	}
	
	if ($_GET['action'] == "edit_todo"){
		$res = mysql_query("SELECT * FROM $db_todo WHERE todo_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	// Spojeni s FTP serverem
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;exit;}
	
	Menu();
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\">\n";
	echo "	<form action=\"sys_save.php?action="; if ($_GET['action'] == "edit_todo"){ echo "edit_todo&id=".$ar['todo_id'];} else {echo "add_todo"; } echo "&project=".$_SESSION['project']."\" method=\"post\" name=\"forma\">\n";
	echo "	<strong>"._TODO_NAME."</strong></td>\n";
	echo "	<td align=\"left\">\n";
	echo "		<input type=\"text\" name=\"todo_name\" size=\"80\" maxlength=\"255\" "; if ($_GET['action'] == "edit_todo"){echo "value=\"".PrepareFromDB($ar['todo_name'])."\""; } echo ">\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._TODO_DESCRIPTION."</strong></td>\n";
	echo "	<td align=\"left\"><textarea name=\"todo_description\" cols=\"50\" rows=\"7\">"; if ($_GET['action'] == "edit_todo"){echo PrepareFromDB($ar['todo_description']); } echo "</textarea></td>\n";
	echo "</tr>\n";
	echo "<tr>";
			if ($_GET['action'] == "add_todo"){
				$article_date_on = formatTimeS(time() + (24 * 60 * 60));
				$todo_date_deadline = $article_date_on[1].".".$article_date_on[2].".".$article_date_on[3];
				$todo_deadline_h = "00";
				$todo_deadline_m = "00";
			} else {
				$todo_date_deadline = FormatDatetime($ar['todo_date_deadline'],"d.m.Y");
				$todo_deadline_h = $ar['todo_date_deadline'][11].$ar['todo_date_deadline'][12];
				$todo_deadline_m = $ar['todo_date_deadline'][14].$ar['todo_date_deadline'][15];
			}
	echo "		<td width=\"150\" align=\"right\"><strong>"._TODO_DATE_DEADLINE."</strong></td>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<script language=\"javascript\">\n";
	echo "			var DateDeadline = new ctlSpiffyCalendarBox(\"DateDeadline\", \"forma\", \"todo_date_deadline\", \"btnDate1\",\"".$todo_date_deadline."\",scBTNMODE_CUSTOMBLUE);\n";
	echo "			</script>\n";
	echo "			<script language=\"javascript\">DateDeadline.writeControl(); DateDeadline.dateFormat=\"dd.MM.yyyy\";</script> <strong>-</strong><select name=\"todo_deadline_h\">";
					for ($i=0;$i<=23;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($todo_deadline_h == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>";
					}
	echo "			</select><strong>:</strong><select name=\"todo_deadline_m\">\n";
					for ($i=0;$i<=59;$i++){
						echo "<option value=\"".Zerofill($i,10)."\" "; if ($todo_deadline_m == $i){ echo "selected=\"selected\"";} echo ">".Zerofill($i,10)."</option>\n";
					}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._TODO_PRIORITY."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select name=\"todo_priority\">\n";
						for ($i=1;$i<=99;$i++){
							echo "<option value=\"".$i."\" "; if ($ar['todo_priority'] == $i){ echo "selected=\"selected\"";} echo ">".$i."</option>\n";
						}
	echo "			</select> "._TODO_PRIORITY_HELP."\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._TODO_CATEGORY."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select name=\"todo_category\">\n";
				  		$res_todo_cat = mysql_query("SELECT todo_category_id, todo_category_name FROM $db_todo_category ORDER BY todo_category_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		  				while ($ar_todo_cat = mysql_fetch_array($res_todo_cat)){
							echo "<option value=\"".$ar_todo_cat['todo_category_id']."\" "; if ($ar_todo_cat['todo_category_id'] == $ar['todo_category_id']){ echo "selected=\"selected\"";} echo ">".$ar_todo_cat['todo_category_name']."</option>\n";
						}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\">&nbsp;</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
//********************************************************************************************************
//
//             MAZANI SMAILIKU
//
//********************************************************************************************************
function DeleteTodo(){
	
	global $url_todo;
	global $db_todo;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_todo_del") <> 1) {NotEnoughPriv();exit;}
	
	if ($confirm == "true") {$res = mysql_query("DELETE FROM $db_todo WHERE todo_id=".(integer)$id."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); ShowMain();exit;}
	if ($confirm == "false"){ShowMain();}
	if ($confirm == ""){
		
		Menu();
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._TODO_NAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._TODO_EMOTION."</span></td>\n";
		echo "	</tr>";
			$res = mysql_query("SELECT * FROM $db_todo WHERE todo_id=".(integer)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"100\"><img src=\"".$url_todo."/".$ar['todo_image']."\"></td>\n";
		echo "		<td valign=\"top\">".$ar['todo_code']."</td>\n";
		echo "		<td valign=\"top\">".$ar['todo_emotion']."</td>\n";
		echo "	</tr>\n";
		echo "</table>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._TODO_CHECKDELETE."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "			<form action=\"sys_save.php?action=del_todo\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"800\">\n";
		echo "			<form action=\"modul_todo.php\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		UPLOAD SMAJLIKU																						
*																											
***********************************************************************************************************/
function UploadImage(){
	
	global $eden_cfg;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_todo_ul") <> 1) {NotEnoughPriv();exit;}
	Menu();
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?action=upload_todo&project=".$_SESSION['project']."\" enctype=\"multipart/form-data\" method=\"post\">\n";
	echo "			<strong>"._CMN_IMAGE."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"file\" name=\"todo_image\" size=\"50\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\"". _CMN_SUBMIT."\" class=\"eden_button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU FILTRU																			
*																											
***********************************************************************************************************/
function ToDoCategories(){
	
	global $db_todo_category;
	global $url_todo_category;
	
	/* Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren */
	KillUse($_SESSION['loginid']);
	
	if ($_GET['action'] == ""){$_GET['action'] = "add_todo_category";}
	
	/* Provereni opravneni */
	if ($_GET['action'] == "edit_todo_category"){
		if (CheckPriv("groups_todo_category_edit") <> 1) {NotEnoughPriv();exit;}
	} elseif ($_GET['action'] == "del_todo_category"){
		if (CheckPriv("groups_todo_category_del") <> 1) {NotEnoughPriv();exit;}
	} else {
		if (CheckPriv("groups_todo_category_add") <> 1) {NotEnoughPriv();exit;}
	}
	
	if ($_GET['action'] != "add_todo_category"){
		$res = mysql_query("SELECT * FROM $db_todo_category WHERE todo_category_id=".(integer)$_GET['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	Menu();
 	if ($_GET['action'] == "del_todo_category"){
 		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\"><form action=\"sys_save.php?id=".$_GET['cid']."&amp;action="; if ($_GET['action'] == "add_todo_category"){echo "add_todo_category";}elseif ($_GET['action'] == "edit_todo_category"){echo "edit_todo_category";} else {echo "del_todo_category";} echo "\" method=\"post\">\n";
		echo "			<strong><span style=\"color : #FF0000;\">"._GAMESRV_DELCHECK."</span></strong>\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	 }
 	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><form action=\"sys_save.php?cid=".$_GET['cid']."&amp;action="; if ($_GET['action'] == "add_todo_category"){echo "add_todo_category";} else {echo "edit_todo_category";} echo "\" method=\"post\"  enctype=\"multipart/form-data\">\n";
	echo "			<strong>"._TODO_CATEGORY_NAME."</strong>\n";
	echo "		</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"todo_category_name\" maxlength=\"80\" size=\"60\" "; if ($_GET['action'] == "edit_todo_category" || $_GET['action'] == "del_todo_category"){echo "value=\"".$ar['todo_category_name']."\""; } echo ">\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\" valign=\"top\"><strong>"._TODO_CATEGORY_IMAGE."</strong></td>\n";
	echo "		<td align=\"left\"><input type=\"file\" name=\"todo_category_image\" size=\"60\"></td>	\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"submit\" value=\""; if ($_GET['action'] == "add_todo_category"){echo _TODO_CATEGORY_ADD;} else {echo _TODO_CATEGORY_EDIT;} echo "\" class=\"eden_button\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			</form>\n";
	echo "		</td>	\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"65\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"45\" align=\"center\"><span class=\"nadpis-boxy\">ID</span></td>\n";
	echo "		<td width=\"100\" align=\"center\"><span class=\"nadpis-boxy\">"._TODO_CATEGORY_IMAGE."</span></td>\n";
	echo "		<td align=\"center\"><span class=\"nadpis-boxy\">"._TODO_CATEGORY_NAME."</span></td>\n";
	echo "	</tr>";
 		$res = mysql_query("SELECT * FROM $db_todo_category ORDER BY todo_category_name") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar = mysql_fetch_array($res)){
			echo "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">\n";
			echo "	<td width=\"65\" valign=\"top\">"; 
			if (CheckPriv("groups_todo_category_edit") == 1){echo "<a href=\"modul_todo.php?action=edit_todo_category&amp;cid=".$ar['todo_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
			if (CheckPriv("groups_todo_category_del") == 1){echo " <a href=\"modul_todo.php?action=del_todo_category&amp;cid=".$ar['todo_category_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; } 
			echo "</td>\n";
			echo "	<td width=\"45\" align=\"left\" valign=\"top\">".$ar['todo_category_id']."</td>\n";
			echo "	<td width=\"100\" align=\"left\" valign=\"top\"><img src=\""; if ($ar['todo_category_image'] != ""){$todo_category_image = $ar['todo_category_image'];} else {$todo_category_image = "0.gif";} echo $url_todo_category.$todo_category_image."\"></td>\n";
			echo "	<td align=\"left\" valign=\"top\">".$ar['todo_category_name']."</td>\n";
			echo "</tr>";
 		}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SMAILIKU																					
*																											
***********************************************************************************************************/
function NotEnoughPriv(){
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n
			<td style=\"color:#ff0000;\">".SysMsg($_GET['msg'])."</td>\n
		</tr>\n
	</table>\n";
}
// MAIN CODE STARTS HERE
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "add_todo") { AddTodo(); }
	if ($_GET['action'] == "del_todo") { DeleteTodo(); }
	if ($_GET['action'] == "edit_todo") { AddTodo(); }
	if ($_GET['action'] == "upload_todo") { UploadImage(); }
	if ($_GET['action'] == "add_todo_category") {ToDoCategories();}
	if ($_GET['action'] == "edit_todo_category") {ToDoCategories();}
	if ($_GET['action'] == "del_todo_category") {ToDoCategories();}
	if ($_GET['action'] == "") { ShowMain(); }
	if ($_GET['action'] == "") { NotEnoughPriv(); }
include "inc.footer.php";