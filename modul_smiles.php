<?php
/***********************************************************************************************************
*																											
*		ZOBRAZENI SMAILIKU																					
*																											
***********************************************************************************************************/
function Menu(){
	switch ($_GET['action']){
		case "smile_add":
			$title = _SMILES." - "._SMILES_ADD;
			break;
		case "smile_edit":
			$title = _SMILES." - "._SMILES_EDIT;
			break;
		case "smile_del":
			$title = _SMILES." - "._SMILES_DEL;
			break;
		default:
			$title = _SMILES;
	}
	$menu = "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\" colspan=\"2\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td><img src=\"images/sys_manage.gif\" border=\"0\">\n";
	$menu .= "			<a href=\"modul_smiles.php?project=". $_SESSION['project']."\">"._CMN_MAINMENU."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_smiles.php?action=smiles_add&amp;project=". $_SESSION['project']."\">"._SMILES_ADD."</a>&nbsp;&nbsp;|&nbsp;&nbsp;\n";
	$menu .= "			<a href=\"modul_smiles.php?action=smiles_img_upload&amp;project=". $_SESSION['project']."\">"._SMILES_UPLOAD."</a>\n";
	$menu .= "		</td>\n";
	$menu .= "	</tr>";
	/* Zobrazeni chyb a hlasek systemu */
	if ($_GET['msg']){
		$menu .= "<tr><td class=\"msg\">".SysMsg($_GET['msg'])."</td></tr>";
	}
	$menu .= "</table>";
	
	return $menu;
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SMAILIKU																					
*																											
***********************************************************************************************************/
function ShowMain(){
	
	global $db_smiles;
	global $url_smiles;
	
	if (CheckPriv("groups_smiles_add") <> 1) {
		header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");
	}
	
	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._SMILES_NAME."</span></td>\n";
	echo "		<td><span class=\"nadpis-boxy\">"._SMILES_EMOTION."</span></td>\n";
	echo "	</tr>";
	$res = mysql_query("SELECT * FROM $db_smiles") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		echo "<tr>\n";
		echo "	<td width=\"80\" valign=\"top\"><a href=\"modul_smiles.php?action=smile_edit&amp;id=".$ar['smile_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> <a href=\"modul_smiles.php?action=smile_del&amp;id=".$ar['smile_id']."&amp;project=".$_SESSION['project']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a></td>\n";
		echo "	<td valign=\"top\" width=\"100\"><img src=\"".$url_smiles.$ar['smile_image']."\"></td>\n";
		echo "	<td valign=\"top\">".$ar['smile_code']."</td>\n";
		echo "	<td valign=\"top\">".$ar['smile_emotion']."</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE SMAILIKU																		
*																											
***********************************************************************************************************/
function AddSmile(){
	
	global $db_smiles;
	global $eden_cfg;
	global $ftp_path_smiles;
	global $url_smiles;
	
	// Provereni opravneni
	if ($_GET['action'] == "smiles_add"){
		if (CheckPriv("groups_smiles_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
	}elseif ($_GET['action'] == "smiles_edit"){
		if (CheckPriv("groups_smiles_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");
	}
	
	if ($_GET['action'] == "smile_edit"){
		$res = mysql_query("SELECT * FROM $db_smiles WHERE smile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
	}
	// Spojeni s FTP serverem
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;exit;}
	
	echo Menu();
	
	echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><script type=\"text/javascript\">\n";
	echo "		<!--\n";
	echo "		var _img = new Array();\n";
			$d = ftp_nlist($conn_id, $ftp_path_smiles);
			$x=0;
			while($entry = $d[$x]){
				$x++;
				$entry = str_replace ($ftp_path_smiles, "", $entry);
				if ($entry != "." && $entry != ".."){
					echo "_img[".$x."] = new Image(); _img[".$x."].src=\"".$url_smiles.$entry."\";\n";
				}
			}
	echo "		function doIt(_obj)	{\n";
	echo "		  if (!_obj) return;\n";
	echo "		  var _index = _obj.selectedIndex;\n";
	echo "		  if (!_index) return;\n";
	echo "		  var _item  = _obj[_index].id;\n";
	echo "		  if (!_item) return;\n";
	echo "		  if (_item < 0 || _item >= _img.length) return;\n";
	echo "		  document.images['image'].src=_img[_item].src;\n";
	echo "		}\n";
	echo "		//-->\n";
	echo "		</script>\n";
	echo "		<form action=\"sys_save.php?action="; if ($_GET['action'] == "smile_edit"){ echo "smile_edit&id=".$ar['smile_id'];} else {echo "smile_add";} echo "&project=". $_SESSION['project']."\" method=\"post\" name=\"forma\">\n";
	echo "		<strong>"._SMILES_CODE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"smile_code\" size=\"5\" maxlength=\"10\" "; if ($_GET['action'] == "smile_edit"){echo "value=\"".PrepareFromDB($ar['smile_code'])."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._SMILES_EMOTION."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"text\" name=\"smile_emotion\" size=\"40\" maxlength=\"75\" "; if ($_GET['action'] == "smile_edit"){echo "value=\"".PrepareFromDB($ar['smile_emotion'])."\"";} echo ">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\"><strong>"._CMN_IMAGE."</strong></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select name=\"smile_image\" size=\"8\" onclick=\"doIt(this)\">";
				$x=0;
				echo "<option value=\"0\""; if ($_GET['action'] == "smile_add"){ echo " selected=\"selected\" ";} echo ">"._SMILES_SELECT_SMILE."</option>\n";
				while($entry = $d[$x]){
					$x++;
					$entry = str_replace ($ftp_path_smiles."/", "", $entry); // Odstrani zbytecnou cestu na linuxovem serveru
					if ($entry != "." && $entry != ".." && $entry != "eden.gif"){
						echo "<option id=\"".$x."\" value=\"".$entry."\""; if (PrepareFromDB($ar['smile_image']) == $entry){ echo " selected=\"selected\" ";} echo">".$entry."</option>\n";
					}
				}
				ftp_close($conn_id);
	echo "			</select>\n";
	echo "			<p><img name=\"image\" src=\"". $url_smiles; if ($_GET['action'] == "smile_edit"){echo PrepareFromDB($ar['smile_image']);} else { echo "eden.gif";} echo "\" border=\"0\"></p>\n";
	echo "			<br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"150\" align=\"right\">&nbsp;</td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"submit\" value=\""._CMN_SUBMIT."\" class=\"eden_button\">\n";
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
function DeleteSmile(){
	
	global $url_smiles;
	global $db_smiles;
	
	// CHECK PRIVILEGIES
	if (CheckPriv("groups_smiles_del") <> 1) {
		header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");
	}
	
	if ($confirm == "true") {$res = mysql_query("DELETE FROM $db_smiles WHERE smiles_id=".(float)$id."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); ShowMain();exit();}
	if ($confirm == "false"){ShowMain();}
		
		echo Menu();
		
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr class=\"popisky\">\n";
		echo "		<td><span class=\"nadpis-boxy\">"._CMN_IMAGE."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._SMILES_NAME."</span></td>\n";
		echo "		<td><span class=\"nadpis-boxy\">"._SMILES_EMOTION."</span></td>\n";
		echo "	</tr>";
		$res = mysql_query("SELECT * FROM $db_smiles WHERE smile_id=".(float)$_GET['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"100\"><img src=\"".$url_smiles."/".$ar['smile_image']."\"></td>\n";
		echo "		<td valign=\"top\">".$ar['smile_code']."</td>\n";
		echo "		<td valign=\"top\">".$ar['smile_emotion']."</td>\n";
		echo "	</tr>\n";
		echo "</table>";
		echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><strong><span style=\"color : #FF0000;\">"._SMILES_CHECKDELETE."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" width=\"50\">\n";
		echo "			<form action=\"sys_save.php?action=smile_del\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "				<input type=\"hidden\" name=\"id\" value=\"". $_GET['id']."\">\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"". $_SESSION['project']."\">\n";
		echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"800\">\n";
		echo "			<form action=\"modul_smiles.php\" method=\"post\">\n";
		echo "				<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button_no\"><br>\n";
		echo "				<input type=\"hidden\" name=\"project\" value=\"". $_SESSION['project']."\">\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
}

// MAIN CODE STARTS HERE
include ("inc.header.php");
	if ($_GET['action'] == "showmain") { ShowMain(); }
	if ($_GET['action'] == "smiles_add") { AddSmile(); }
	if ($_GET['action'] == "smiles_del") { DeleteSmile(); }
	if ($_GET['action'] == "smiles_edit") { AddSmile(); }
	if ($_GET['action'] == "smiles_img_upload") { EdenSysImageManager(); }
	if ($_GET['action'] == "smiles_img_del") { EdenSysImageManager(); }
	if ($_GET['action'] == "") { ShowMain(); }
include "inc.footer.php";