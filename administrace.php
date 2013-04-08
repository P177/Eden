<?php
// Nastaveni spravne timezone
date_default_timezone_set('Europe/London');
/***********************************************************************************************************
*
*		SESSIONS
*
***********************************************************************************************************/
if (isset($_SESSION["lang"]) == ""){$_SESSION["lang"] = "cz";}
require_once("./lang/lang-".$_SESSION["lang"].".php");
require_once("./cfg/db.admin.inc.php");
require_once("./cfg/functions_Common.php");	/*	Common functions for FrontEnd and Back End */

// Zde nesmi byt nacteno ani functions.php ani session - jinak nebude fungovat predavani parametru

	//Nazev sessions
	session_name("autorizace");
	session_start();
	$sid = session_id();
	$time = date("U"); // Aktualni cas
	$at = (date("U") - 3600); // Doba pripojeni
	if ($_SESSION['doba'] < $at){
		header ("Location: index.php?action=msg");
	} else {
		$_SESSION['doba'] = $time; // Zde se zapise doba pripojeni
	}
	if ($_SESSION['project'] != $_GET['project'] || $_SESSION['ip'] != $eden_cfg['ip'] || $_SESSION['sid'] != $sid){
		header ("Location: index.php?action=msg&".$eden_cfg['ip']);
	}
/***********************************************************************************************************
*
*		HLAVICKA
*
***********************************************************************************************************/
function Hlavicka(){
	
	global $eden_cfg;
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<title>Administrace cfg a SQL</title>\n";
	echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden.css\">\n";
	echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "</head>\n";
	echo "<body topmargin=\"0\" leftmargin=\"0\" bgcolor=\"#C0C0C0\" ".$load.">\n";
	echo "<div align=\"center\"><table width=\"1000\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" height=\"45\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"141\" height=\"45\" bgcolor=\"#6787BA\"><img src=\"images/sys_logo.gif\" width=\"141\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"25\" height=\"45\" bgcolor=\"#80A0D3\" align=\"right\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"193\" height=\"45\" bgcolor=\"#80A0D3\">".$eden_cfg['ip']."</td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"198\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\"><br></td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"198\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\">"._CMN_NEWSDATE."<br>\n";
	echo "			".date("d.m.Y")."</td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_cerny.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\">&nbsp;</td>\n";
	echo "		<td width=\"200\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\">"._PROJECT.": <strong>".$_SESSION['project']."</strong><br>\n";
	echo "			"._ADMIN_USERNAME.": <strong>".$_SESSION['login']."<br /></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"1000\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" height=\"27\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"200\" height=\"27\" bgcolor=\"#999999\" align=\"left\" valign=\"middle\" class=\"sys_nadpis1\">"._CMN_ADMINCENTRUM."</td>\n";
	echo "		<td width=\"800\" height=\"27\" bgcolor=\"#999999\" align=\"right\" valign=\"middle\" class=\"sys_nadpis1\">"._CMN_LOGOUT."&nbsp;&nbsp;<a href=\"administrace.php?action=logout\"><img src=\"images/sys_logout.gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" align=\"middle\"></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "\n";
	echo "<table border=\"0\" width=\"1000\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"141\" align=\"center\" valign=\"top\" bgcolor=\"#EBEBEC\">\n";
	echo "			<table width=\"141\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"141\" height=\"36\" class=\"menu_nadpis\">Možnosti</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"menu_text\"><br>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			<table width=\"141\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"141\" height=\"36\"><img src=\"images/sys_dekor.gif\" width=\"141\" height=\"199\" border=\"0\" alt=\"\"></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		<td width=\"857\" valign=\"top\" bgcolor=\"#FFFFFF\">";
}
/***********************************************************************************************************
*
*		PATICKA
*
***********************************************************************************************************/
function Paticka(){
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<table width=\"1000\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"1000\" height=\"20\" bgcolor=\"#999999\" class=\"sys_nadpis2\" align=\"right\">Copyright &copy; 1998 - ".date("Y")." The <a href=\"http://www.blackfoot.cz\">BlackFoot</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table></div>\n";
	echo "</body>\n";
	echo "</html>";
}

/***********************************************************************************************************
*
*		FtpCheckDirArray
*
*		Proveruje zda je adresar vytvoren, pokud ne vytvori se. Pri problemech vraci chybovou hlasku
*
***********************************************************************************************************/
function FtpCheckDirArray($ftp_array = false){
	
	global $eden_cfg;
	
	if ($ftp_array == false || is_array($ftp_array) == false){ echo _FTP_CHECK_MISSING_DIRS;exit;}
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { _ERROR_FTP;}
	
	$ftp_array_num = count($ftp_array);
	$i=0;
	while ($i < $ftp_array_num){
		//echo "<br><br>".$ftp_array[$i][0]." - ".$ftp_array[$i][1]."<br>";
		if ($ftp_array[$i][0] == "ftp_up"){
			ftp_cdup($conn_id);
		} elseif ($ftp_array[$i][0] != "ftp_up"){
			@ftp_chdir($conn_id, $ftp_array[$i][0]);
			//echo "Current directory: " . ftp_pwd($conn_id) . "\n<br />";
			// try to change the directory to somedir
			if (@ftp_chdir($conn_id, $ftp_array[$i][1])) {
				//echo "Current directory is now: " . ftp_pwd($conn_id) . "\n<br />";
				ftp_cdup($conn_id);
			} else { 
				//echo "Couldn't change directory\n<br />";
				// try to create the directory $dir
				if (ftp_mkdir($conn_id, $ftp_array[$i][1])) {
					echo _FTP_CHECK_DIR.$ftp_array[$i][1]._FTP_CHECK_DIR_CREATED."<br><br>";
				} else {
					echo _FTP_CHECK_DIR_PROB.$ftp_array[$i][1]."<br><br>";
				}
			}
		}
		$i++;
	}
	ftp_close($conn_id);
}
/***********************************************************************************************************
*
*		LOGOUT
*
***********************************************************************************************************/
function LogOut(){

	session_name("autorizace");
	session_start();
	$sid = session_id();
	if (!isset($_SESSION['project'])) {
		header ("Location: index.php?action=msg&msg=logout");
	} else {
		$_SESSION = array();
		if (isset($_COOKIE[session_name('autorizace')])) {
		    setcookie(session_name('autorizace'), '', time()-42000, '/');
		}
		session_destroy();
		header ("Location: index.php?action=msg&msg=logout");
	}
}
/***********************************************************************************************************
*
*		MENU
*
***********************************************************************************************************/
function Menu(){
	switch ($_GET['action']){
		case "sql":
			$title = "Administrace SQL";
			break;
		case "sqlmulti":
			$title = "Administrace SQL Multi";
			break;
		case "folders":
			$title = "Administrace Adresaru";
			break;
		case "js":
			$title = "Administrace JS";
			break;
		case "transcript":
			$title = "Transcript";
			break;
		default:
			$title = "Administrace CFG";
	}
	$menu = "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td align=\"left\" class=\"nadpis\">".$title."</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "	<tr>\n";
	$menu .= "		<td>";
	$menu .= "<img src=\"images/sys_manage.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\"Přidat kategorii\">";
	$menu .= "<a href=\"administrace.php?action=cfg&amp;project=".$_SESSION['project']."\">Administrace CFG</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"administrace.php?action=sql&amp;project=".$_SESSION['project']."\">Administrace SQL</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"administrace.php?action=sqlmulti&amp;project=".$_SESSION['project']."\">Administrace SQL Multi</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"administrace.php?action=js&amp;project=".$_SESSION['project']."\">Administrace JS</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	//$menu .= "<a href=\"administrace.php?action=folders&amp;project=".$_SESSION['project']."\">Administrace Adresaru</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	$menu .= "<a href=\"administrace.php?action=transscript&amp;project=".$_SESSION['project']."\">Transscript</a>";
	$menu .= "		</td>\n";
	$menu .= "	</tr>\n";
	$menu .= "</table>\n";
	$menu .= "<br>\n";
	
	return $menu;
}
/***********************************************************************************************************
*
*		CFG LIST
*
***********************************************************************************************************/
function CfgList(){
	
	global $eden_cfg;
	global $ftp_path_cfg_adm;
	
	// Spojeni s FTP serverem
	$conn_id_adm = ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) { echo _ERROR_FTP;}
	// Set the network timeout to 10 seconds
	ftp_set_option($conn_id_adm, FTP_TIMEOUT_SEC, 10);
	
	$d = ftp_nlist($conn_id_adm, $ftp_path_cfg_adm);
	$buff = ftp_rawlist($conn_id_adm, '/');
	$x = 0;
	$i = 1;
	$cfglist = "";
	while($entry = $d[$x]) {
		$entry = str_ireplace ($ftp_path_cfg_adm,"",$entry);//Odstrani cestu k ftp adresari
		if ($entry != "." &&
			$entry != ".." &&
			$entry != "db.admin.inc.php" &&
			$entry != "config.php" &&
			$entry != "functions_frontend.php" &&
			$entry != "functions_Common.php" &&
			$entry != "functions_frontend_Calendar.php" &&
			$entry != "functions_frontend_Clanwars.php" &&
			$entry != "functions_frontend_Download.php" &&
			$entry != "functions_frontend_Dictionary.php" &&
			$entry != "functions_frontend_FlagName.php" &&
			$entry != "functions_frontend_GuestBook.php" &&
			$entry != "functions_frontend_Msg.php" &&
			$entry != "functions_frontend_MtG.php" &&
			$entry != "functions_frontend_Comments.php" &&
			$entry != "functions_frontend_Poll.php" &&
			$entry != "functions_frontend_PostEditor.php" &&
			$entry != "functions_frontend_RSS.php" &&
			$entry != "functions_frontend_Search.php" &&
			$entry != "functions_frontend_Streams.php" &&
			$entry != "functions_frontend_Streams_Cron.php" &&
			$entry != "functions_frontend_Tournament.php" &&
			$entry != "functions_frontend_UserEdit.php" &&
			$entry != "main_shop.php" &&
			$entry != "eden_lang_cz.php" &&
			$entry != "eden_lang_en.php" &&
			$entry != "sessions.php" &&
			$entry != "eden_download.php" &&
			$entry != "eden_iframe.php" &&
			$entry != "eden_jump.php" &&
			$entry != "eden_save.php" &&
			$entry != "eden_sec.php" &&
			$entry != "eden_show_images.php" &&
			$entry != "eden_ban.php" &&
			$entry != "class.mail.php" &&
			$entry != "class.smtp.php" &&
			$entry != "eden_bracket.php" &&
			$entry != "eden_add_link.php" &&
			$entry != "eden_rss.php" &&
			$entry != "eden_shop_save.php" &&
			$entry != "eden_forum.php" &&
			$entry != "eden_jQuery_Thumbs.php" &&
			$entry != "eden_js.php" &&
			$entry != "eden_ajax.php" &&
			$entry != "eden_recommend.php" &&
			$entry != "eden_captcha.php" &&
			$entry != "eden_init.php" &&
			$entry != "eden_league.php" &&
			$entry != "eden_league_export.php" &&
			$entry != "eden.js") {/* Zobrazi se vsechnu .cfg krome tohoto */
			$cfglist .= "<tr onmouseover=\"this.style.backgroundColor='FFDEDF'\" onmouseout=\"this.style.backgroundColor='FFFFFF'\">";
			$cfglist .= "	<td width=\"80\" align=\"center\">".$i.". <input type=\"checkbox\" name=\"povoleni[]\" value=\"".$entry."\" checked></td>";
			$cfglist .= "	<td>".$entry."<br></td>";
			$cfglist .= "</tr>";
			$i++;
		}
		$x++;
	}
	ftp_close($conn_id_adm);
	return $cfglist;
}
/***********************************************************************************************************
*
*		CFG
*
***********************************************************************************************************/
function Cfg(){
	
	global $eden_cfg;
	global $ftp_path_cfg_adm;
	
	// Spojeni s FTP serverem
	$conn_id_adm = ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) { echo _ERROR_FTP;}
	// Set the network timeout to 10 seconds
	ftp_set_option($conn_id_adm, FTP_TIMEOUT_SEC, 10);
	
	echo Menu();
	
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=cfg&amp;lang=".$_GET['lang']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
	echo "	</tr>";
	echo CfgList();
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		<input type=\"submit\" value=\""._ADM_UPDATE_CFG."\" class=\"button\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">";
	if ($_POST['confirm'] = "true"){
		$pocet = count($_POST['povoleni']); //Spocita pocet nazvu souboru v poli
		$i = 0;
		while($pocet >= $i){
			if (isset($_POST['povoleni'][$i]) != ""){
				include (dirname(__FILE__)."/cfg/".$_POST['povoleni'][$i]."");
				// Spojeni s FTP serverem
				$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
				// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
				$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']) or die (_ERROR_FTP_LOGIN);
				ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
				
				// Zjisteni stavu spojeni
				if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP;}
				
				// Zkontrolujeme zda je dany adresar vytvoren
				$ftp_array = array(
					array($eden_cfg['ftp_path_main'],"js/"),
					array("","edencms/")
				);
				//FtpCheckDirArray($ftp_array);
				
				// Vlozi nazev souboru a cestu do konkretniho adresare
				$source_array = array(
					array ("./cfg/".$_POST['povoleni'][$i], $eden_cfg['ftp_path'].$_POST['povoleni'][$i]),
					array ("./cfg/class.mail.php", $eden_cfg['ftp_path']."class.mail.php"),
					array ("./cfg/class.smtp.php", $eden_cfg['ftp_path']."class.smtp.php"),
					array ("./cfg/config.php", $eden_cfg['ftp_path']."config.php"),
					array ("./cfg/eden_add_link.php", $eden_cfg['ftp_path']."eden_add_link.php"),
					array ("./cfg/eden_ajax.php", $eden_cfg['ftp_path']."eden_ajax.php"),
					array ("./cfg/eden_ban.php", $eden_cfg['ftp_path']."eden_ban.php"),
					array ("./cfg/eden.js", $eden_cfg['ftp_path_main']."js/eden.js"),
					array ("./cfg/eden_bracket.php", $eden_cfg['ftp_path_main']."eden_bracket.php"),
					array ("./cfg/eden_captcha.php", $eden_cfg['ftp_path']."eden_captcha.php"),
					array ("./cfg/eden_download.php", $eden_cfg['ftp_path']."eden_download.php"),
					array ("./cfg/eden_forum.php", $eden_cfg['ftp_path']."eden_forum.php"),
					array ("./cfg/eden_iframe.php", $eden_cfg['ftp_path']."eden_iframe.php"),
					array ("./cfg/eden_init.php", $eden_cfg['ftp_path']."eden_init.php"),
					array ("./cfg/eden_jQuery_Thumbs.php", $eden_cfg['ftp_path']."eden_jQuery_Thumbs.php"),
					array ("./cfg/eden_js.php", $eden_cfg['ftp_path']."eden_js.php"),
					array ("./cfg/eden_jump.php", $eden_cfg['ftp_path']."eden_jump.php"),
					array ("./cfg/eden_lang_cz.php", $eden_cfg['ftp_path']."eden_lang_cz.php"),
					array ("./cfg/eden_lang_en.php", $eden_cfg['ftp_path']."eden_lang_en.php"),
					array ("./cfg/eden_league.php", $eden_cfg['ftp_path']."eden_league.php"),
					array ("./cfg/eden_league_export.php", $eden_cfg['ftp_path']."eden_league_export.php"),
					array ("./cfg/eden_recommend.php", $eden_cfg['ftp_path']."eden_recommend.php"),
					array ("./cfg/eden_rss.php", $eden_cfg['ftp_path']."eden_rss.php"),
					array ("./cfg/eden_save.php", $eden_cfg['ftp_path']."eden_save.php"),
					array ("./cfg/eden_shop_save.php", $eden_cfg['ftp_path']."eden_shop_save.php"),
					array ("./cfg/eden_show_images.php", $eden_cfg['ftp_path']."eden_show_images.php"),
					array ("./cfg/functions_frontend.php", $eden_cfg['ftp_path']."functions_frontend.php"),
					array ("./cfg/functions_Common.php", $eden_cfg['ftp_path']."functions_Common.php"),
					array ("./cfg/functions_frontend_Calendar.php", $eden_cfg['ftp_path']."functions_frontend_Calendar.php"),
					array ("./cfg/functions_frontend_Clanwars.php", $eden_cfg['ftp_path']."functions_frontend_Clanwars.php"),
					array ("./cfg/functions_frontend_Download.php", $eden_cfg['ftp_path']."functions_frontend_Download.php"),
					array ("./cfg/functions_frontend_Dictionary.php", $eden_cfg['ftp_path']."functions_frontend_Dictionary.php"),
					array ("./cfg/functions_frontend_FlagName.php", $eden_cfg['ftp_path']."functions_frontend_FlagName.php"),
					array ("./cfg/functions_frontend_GuestBook.php", $eden_cfg['ftp_path']."functions_frontend_GuestBook.php"),
					array ("./cfg/functions_frontend_Msg.php", $eden_cfg['ftp_path']."functions_frontend_Msg.php"),
					array ("./cfg/functions_frontend_MtG.php", $eden_cfg['ftp_path']."functions_frontend_MtG.php"),
					array ("./cfg/functions_frontend_Comments.php", $eden_cfg['ftp_path']."functions_frontend_Comments.php"),
					array ("./cfg/functions_frontend_Poll.php", $eden_cfg['ftp_path']."functions_frontend_Poll.php"),
					array ("./cfg/functions_frontend_PostEditor.php", $eden_cfg['ftp_path']."functions_frontend_PostEditor.php"),
					array ("./cfg/functions_frontend_RSS.php", $eden_cfg['ftp_path']."functions_frontend_RSS.php"),
					array ("./cfg/functions_frontend_Search.php", $eden_cfg['ftp_path']."functions_frontend_Search.php"),
					array ("./cfg/functions_frontend_Streams.php", $eden_cfg['ftp_path']."functions_frontend_Streams.php"),
					array ("./cfg/functions_frontend_Streams_Cron.php", $eden_cfg['ftp_path']."functions_frontend_Streams_Cron.php"),
					array ("./cfg/functions_frontend_Tournament.php", $eden_cfg['ftp_path']."functions_frontend_Tournament.php"),
					array ("./cfg/functions_frontend_UserEdit.php", $eden_cfg['ftp_path']."functions_frontend_UserEdit.php"),
					array ("./cfg/main_shop.php", $eden_cfg['ftp_path']."main_shop.php"),
					array ("./cfg/sessions.php", $eden_cfg['ftp_path']."sessions.php")
				);
				echo "<strong>Projekt: ".$eden_cfg['project']."</strong><br><hr width=\"250\" size=\"1\" noshade align=\"left\">";
				
				$num = count($source_array);
				for($y = 0; $y < $num; $y++){
					$source_file = $source_array[$y][0];
					$destination_file = $source_array[$y][1];
					$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
					if (!$upload){ echo _ERROR_UPLOAD." - <strong>Co:</strong> ".$source_file."<br>";} else {echo "<strong>Co:</strong> ".$source_file."<br>"; echo "<strong>Kam:</strong> ".$destination_file."<br>";}
				}
				// Uzavreni komunikace se serverem
				ftp_close($conn_id);
				echo "<br><br>";
			}
			$i++;
		}
	}
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	ftp_close($conn_id_adm);
}
/***********************************************************************************************************
*
*		FOLDER
*
***********************************************************************************************************/
function Folders(){

	global $eden_cfg;
	global $ftp_path_cfg_adm;


	// Spojeni s FTP serverem
	$conn_id_adm = ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) { echo _ERROR_FTP;}
	// Set the network timeout to 10 seconds
	ftp_set_option($conn_id_adm, FTP_TIMEOUT_SEC, 10);
	
	echo Menu();
	
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=folders&amp;lang=<?php echo \$_GET['lang'];?>&amp;project=<?php echo \$_SESSION['project'];?>\" method=\"post\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\"><?php echo _CMN_OPTIONS;?></span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\"><?php echo _NAME;?></span></td>\n";
	echo "	</tr>";
 			echo CfgList();
			echo "	<tr>\n";
			echo "		<td align=\"left\" colspan=\"2\">\n";
			echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
			echo "			<input type=\"submit\" value=\""._ADM_UPDATE_FOLDERS."\" class=\"button\">\n";
			echo "			</form>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\" colspan=\"2\">";
			if ($_POST['confirm'] = "true"){
				$pocet = count($_POST['povoleni']); //Spocita pocet nazvu souboru v poli
				$i = 0;
				while($pocet >= $i){
					if (isset($_POST['povoleni'][$i]) != ""){
						include (dirname(__FILE__)."/cfg/".$_POST['povoleni'][$i]."");
						// Spojeni s FTP serverem
						$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
						// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
						$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']) or die (_ERROR_FTP_LOGIN);
						ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
						
						// Zjisteni stavu spojeni
						if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP;}
						
						echo "<strong>Projekt: ".$eden_cfg['project']."</strong><br><hr width=\"250\" size=\"1\" noshade align=\"left\">";
						// Zkontrolujeme zda je dany adresar vytvoren
						$ftp_array = array(
							array($eden_cfg['ftp_path_main'],"js/"),
							array("","edencms_files/"),
							array("edencms_files/","img_adds/"),
							array("","img_admins/"),
							array("","img_admins_category/"),
							array("","img_admins_images/"),
							array("img_admins_images/","admins/"),
							array("","main/"),
							array("main/","_thumb/"),
							array("ftp_up",""),
							array("ftp_up",""),
							array("","img_auto/"),
							array("","img_category/"),
							array("","img_clan_awards/"),
							array("","img_cups_maps/"),
							array("","img_download/"),
							array("","img_eden_images/"),
							array("","eden_js/"),
							array("","img_files/"),
							array("","img_forum/"),
							array("","img_links/"),
							array("","img_articles/"),
							array("","img_articles_channels/"),
							array("","img_ntb/"),
							array("","img_poker_cards/"),
							array("img_poker_cards/","big/"),
							array("","small/"),
							array("ftp_up",""),
							array("","img_rss/"),
							array("","img_rss_itunes/"),
							array("","img_screenshots/"),
							array("","img_shop/"),
							array("img_shop/","clothes_colors/"),
							array("","clothes_design/"),
							array("","clothes_style/"),
							array("","clothes_style_parent/"),
							array("","images/"),
							array("","manufacturers/"),
							array("","payments/"),
							array("","products/"),
							array("ftp_up",""),
							array("","img_smiles/"),
							array("","img_todo_category/"),
							array("","img_flags/"),
						);
						FtpCheckDirArray($ftp_array);
						echo "<br><br>";
					}
					$i++;
				}
			}
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>";
	ftp_close($conn_id_adm);
}
/***********************************************************************************************************
*
*		SQL
*
***********************************************************************************************************/
function Sql(){
	
	global $eden_cfg;
	global $ftp_path_cfg_adm;
	
	// Spojeni s FTP serverem
	$conn_id_adm = @ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) {
  		echo _ERROR_FTP;
	}
	
	echo Menu();
	
	echo "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td>";
 				if ($_POST['confirm'] = "true"){
					
					/* Spocita pocet nazvu souboru v poli */
					$pocet = count($_POST['povoleni']);
					
					/* Prevedeme uvozovky a apostrofy zpatky do pozadovaneho tvaru */
					$_POST['sql_dotaz'] = htmlspecialchars_decode($_POST['sql_dotaz'], ENT_QUOTES);
					
					$i = 0;
					while($pocet >= $i){
						if ($_POST['povoleni'][$i] != ""){
							include (dirname(__FILE__)."/cfg/".$_POST['povoleni'][$i]);
							$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
							mysql_select_db($eden_cfg['db_name']);
							$sql_dotaz_upraveny = str_ireplace ('---dbprefix---', $eden_cfg['db_prefix'], $_POST['sql_dotaz']); // Prevede prefix databaze na spravny
							$sql_dotaz_upraveny = stripslashes($sql_dotaz_upraveny); // Odstrani zpetna lomitka
							echo "<strong>Projekt: ".$eden_cfg['project']."</strong><br>";
							echo $sql_dotaz_upraveny."<br>";
							mysql_query("$sql_dotaz_upraveny") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_close($link);
						}
						$i++;
					}
				}
				ftp_close($conn_id_adm);
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<br>\n";
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=sql&amp;lang=".$_GET['lang']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<textarea cols=\"80\" rows=\"10\" name=\"sql_dotaz\"></textarea><br>---dbprefix---\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
	echo "	</tr>";
 	echo CfgList();
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\"Odeslat dotaz\" class=\"button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*
*		SQL MULTI
*
***********************************************************************************************************/
function SqlMulti(){

	global $eden_cfg;
	global $ftp_path_cfg_adm;

	// Spojeni s FTP serverem
	$conn_id_adm = @ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) {
  		echo _ERROR_FTP;
	}
	
	echo Menu();
	
	echo "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td>";
 				if ($_POST['confirm'] = "true"){
					/* Spocita pocet nazvu souboru v poli */
					$pocet = count($_POST['povoleni']);
					/* Prevedeme uvozovky a apostrofy zpatky do pozadovaneho tvaru */
					$_POST['sql_dotaz'] = htmlspecialchars_decode($_POST['sql_dotaz'], ENT_QUOTES);
					$i = 0;
					while($pocet >= $i){
						if ($_POST['povoleni'][$i] != ""){
							include (dirname(__FILE__)."/cfg/".$_POST['povoleni'][$i]."");
							$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
							mysql_select_db($eden_cfg['db_name']);
							$sql_dotaz_exploded = explode("###", $_POST['sql_dotaz']);
							$num = count($sql_dotaz_exploded);
							$y=0;
							echo "<strong>Projekt: ".$eden_cfg['project']."</strong><br>";
							while($y<$num){
								$sql_dotaz_upraveny = str_ireplace ('---dbprefix---', $eden_cfg['db_prefix'], $sql_dotaz_exploded[$y]); // Prevede prefix databaze na spravny
								$sql_dotaz_upraveny = stripslashes($sql_dotaz_upraveny); // Odstrani spetna lomitka
								echo $sql_dotaz_upraveny."<br>";
								mysql_query("$sql_dotaz_upraveny") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
								$y++;
							}
							mysql_close($link);
						}
						$i++;
					}
				}
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<br>\n";
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=sqlmulti&amp;lang=".$_GET['lang']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<textarea cols=\"80\" rows=\"10\" name=\"sql_dotaz\"></textarea><br>---dbprefix---&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;###\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
	echo "	</tr>";
	echo CfgList();
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\"Odeslat dotaz\" class=\"button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	ftp_close($conn_id_adm);
}
/***********************************************************************************************************
*
*		CFG
*
***********************************************************************************************************/
function JS(){
	
	global $eden_cfg;
	global $ftp_path_cfg_adm;
	
	// Spojeni s FTP serverem
	$conn_id_adm = ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) { echo _ERROR_FTP;}
	// Set the network timeout to 10 seconds
	ftp_set_option($conn_id_adm, FTP_TIMEOUT_SEC, 10);
	
	echo Menu();
	
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=cfg&amp;lang=".$_GET['lang']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
	echo "	</tr>";
	echo CfgList();
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		<input type=\"submit\" value=\""._ADM_UPDATE_CFG."\" class=\"button\">\n";
	echo "		</form>\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\" colspan=\"2\">";
	if ($_POST['confirm'] = "true"){
		$pocet = count($_POST['povoleni']); //Spocita pocet nazvu souboru v poli
		$i = 0;
		while($pocet >= $i){
			if (isset($_POST['povoleni'][$i]) != ""){
				include (dirname(__FILE__)."/cfg/".$_POST['povoleni'][$i]."");
				// Spojeni s FTP serverem
				$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
				// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
				$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']) or die (_ERROR_FTP_LOGIN);
				ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
				
				// Zjisteni stavu spojeni
				if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP;}
				
				// Zkontrolujeme zda je dany adresar vytvoren
				$ftp_array = array(
					array($eden_cfg['ftp_path_main'],"js/")
				);
				//FtpCheckDirArray($ftp_array);
				
				// Vlozi nazev souboru a cestu do konkretniho adresare
				$source_array = array(
					array ("./js/ajax.js", $eden_cfg['ftp_path_main']."js/ajax.js"),
					array ("./js/ajax-dynamic-list.js", $eden_cfg['ftp_path_main']."js/ajax-dynamic-list.js"),
					array ("./js/jquery.js", $eden_cfg['ftp_path_main']."js/jquery.js"),
					array ("./js/jquery.form.js", $eden_cfg['ftp_path_main']."js/jquery.form.js"),
					array ("./js/jquery.tools.min.js", $eden_cfg['ftp_path_main']."js/jquery.tools.min.js"),
					array ("./js/jquery.validate.js", $eden_cfg['ftp_path_main']."js/jquery.validate.js"),
					array ("./js/jquery-ui.js", $eden_cfg['ftp_path_main']."js/jquery-ui.js"),
				);
				echo "<strong>Projekt: ".$eden_cfg['project']."</strong><br><hr width=\"250\" size=\"1\" noshade align=\"left\">";
				
				$num = count($source_array);
				for($y = 0; $y < $num; $y++){
					$source_file = $source_array[$y][0];
					$destination_file = $source_array[$y][1];
					$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
					if (!$upload){ echo _ERROR_UPLOAD." - <strong>Co:</strong> ".$source_file."<br>";} else {echo "<strong>Co:</strong> ".$source_file."<br>"; echo "<strong>Kam:</strong> ".$destination_file."<br>";}
				}
				// Uzavreni komunikace se serverem
				ftp_close($conn_id);
				echo "<br><br>";
			}
			$i++;
		}
	}
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	ftp_close($conn_id_adm);
}
/***********************************************************************************************************
*
*		Zobrazeni dostupnych konfiguracnich souboru
*
***********************************************************************************************************/
function Transcript(){
	global $db_setup,$db_setup_images,$db_articles,$db_comments,$db_comments_log;
	global $eden_cfg;
	global $ftp_path_cfg_adm;
	
	// Spojeni s FTP serverem
	$conn_id_adm = @ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result_adm = ftp_login($conn_id_adm, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id_adm, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id_adm) || (!$login_result_adm)) {
  		echo _ERROR_FTP;
	}
	
	echo Menu();
	
	echo "<table width=\"849\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td>";
 			if ($_POST['confirm'] = "true"){
				$povoleni = $_POST['povoleni'];
				$pocet = count($_POST['povoleni']); //Spocita pocet nazvu souboru v poli
				$i = 0;
				while($pocet >= $i){
					if ($povoleni[$i] != ""){
						include (dirname(__FILE__)."/cfg/".$povoleni[$i]."");
						echo $povoleni[$i]."<br><br><br>";
						$link = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']);
						mysql_select_db($eden_cfg['db_name']);
						if ($eden_cfg['db_collate'] != ""){mysql_query($eden_cfg['db_collate']);}
						if ($eden_cfg['db_collate_connection'] != ""){mysql_query($eden_cfg['db_collate_connection']);}
						if ($eden_cfg['db_encode'] != ""){mysql_query($eden_cfg['db_encode']);}
			  			/* Zacatek Scriptu */
						
						/* BAN 
						$res = mysql_query("SELECT ban_id, ban_date2, ban_ip3 FROM $db_ban WHERE ban_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['ban_id']."<br>";
							//preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/i", $ar['admin_reg_date2'], $datetime);
							//$datetime = date('Y-m-d H:i:s', mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
							preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})/i", $ar['ban_date2'], $datetime);
							$datetime = date('Y-m-d', mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
							
							echo $datetime."<br>".InetAton($ar['ban_id'])."<br>";
							
							mysql_query("UPDATE $db_ban SET ban_date='".$datetime."', ban_ip=INET_ATON('".$ar['ban_ip3']."'), ban_updatecheck=1 WHERE ban_id=".$ar['ban_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						 BAN END */
						
						/* COMMENT 
						$res = mysql_query("SELECT comment_id, comments_modul2, comments_date2, comments_ip2 FROM $db_comments WHERE comments_checkupdate=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['comment_id']."<br>";
							preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/i", $ar['comments_date2'], $datetime);
							$datetime = date('Y-m-d H:i:s', mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
							mysql_query("UPDATE $db_comments SET comment_date='".$datetime."', comment_ip=INET_ATON('".$ar['comments_ip2']."'), comment_modul='".$ar['comments_modul2']."', comments_checkupdate=1 WHERE comment_id=".$ar['comment_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						COMMENT END*/
						
						
						
						/* EDEN LOG 
						$res = mysql_query("SELECT log_id, log_ip2 FROM $db_eden_log WHERE log_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['log_id']."<br>";
							mysql_query("UPDATE $db_eden_log SET log_ip=INET_ATON('".$ar['log_ip2']."'), log_updatecheck=1 WHERE log_id=".$ar['log_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						EDEN LOG END */
						
						/* EDEN POLL 
						$res = mysql_query("SELECT poll_answers_id, poll_answers_ip2, poll_answers_date2 FROM $db_poll_answers WHERE poll_answers_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['poll_answers_id']."<br>";
							// Ulozeni IP adres spravne (jen prvni cast)
							$pieces = explode("|", $ar['poll_answers_ip2']);
							mysql_query("UPDATE $db_poll_answers SET poll_answers_ip=INET_ATON('".$pieces[0]."'), poll_answers_date='".date("Y-m-d H:i:s",$ar['poll_answers_date2'])."', poll_answers_updatecheck=1 WHERE poll_answers_id=".$ar['poll_answers_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
  						}
						EDEN POLL END */
						
						/* EDEN FORUM LOG 
						$res = mysql_query("SELECT forum_posts_edit_log_id, forum_posts_edit_log_ip2 FROM $db_forum_posts_edit_log WHERE forum_posts_edit_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['forum_posts_edit_log_id']."<br>";
							// Ulozeni IP adres spravne (jen prvni cast)
							$pieces = explode("|", $ar['forum_posts_edit_log_ip2']);
							mysql_query("UPDATE $db_forum_posts_edit_log SET forum_posts_edit_log_ip=INET_ATON('".$pieces[0]."'), forum_posts_edit_updatecheck=1 WHERE forum_posts_edit_log_id=".$ar['forum_posts_edit_log_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					   	 EDEN FORUM LOG END */
						 
						/* EDEN FORUM POST LOG
						$res = mysql_query("SELECT forum_posts_report_log_id, forum_posts_report_log_ip2 FROM $db_forum_posts_report_log WHERE forum_posts_report_log_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['forum_posts_report_log_id']."<br>";
							// Ulozeni IP adres spravne (jen prvni cast)
							$pieces = explode("|", $ar['forum_posts_report_log_ip2']);
							mysql_query("UPDATE $db_forum_posts_report_log SET forum_posts_report_log_ip=INET_ATON('".$pieces[0]."'), forum_posts_report_log_updatecheck=1 WHERE forum_posts_report_log_id=".$ar['forum_posts_report_log_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					   	EDEN FORUM POST LOG END */
						
						/* EDEN ADMIN 
						$res = mysql_query("SELECT admin_id, admin_status2 FROM $db_admin WHERE admin_updatecheck=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['admin_id']."<br>";
							if ($ar['admin_status2'] == ""){$status = "user";} else {$status = $ar['admin_status2'];}
							mysql_query("UPDATE $db_admin SET admin_status='".$status."', admin_updatecheck=1 WHERE admin_id=".$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					    EDEN ADMIN END */
						
						
						
						
						
						
						
						
						
						
						
						
						
						/* EDEN NEWS 
						ALTER TABLE `---dbprefix---_articles` ADD `article_updatecheck` TINYINT UNSIGNED NOT NULL DEFAULT '0'
						*/
						/*
						$res = mysql_query("SELECT article_id, article_perex, article_text FROM $db_articles WHERE article_updatecheck = 0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['article_id']."<br>";
							$article_perex = TreatText($ar['article_perex'],0,1);
							$article_text = TreatText($ar['article_text'],0,1);
							
							mysql_query("UPDATE $db_articles SET article_perex='".mysql_real_escape_string($article_perex)."', article_text='".mysql_real_escape_string($article_text)."', article_updatecheck = 1 WHERE article_id=".$ar['article_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					   	
						ALTER TABLE `---dbprefix---_articles` DROP `article_updatecheck` 
						*/
						
						
						
						
						/* EDEN NEWS 
						ALTER TABLE `---dbprefix---_news` ADD `news_updatecheck` TINYINT UNSIGNED NOT NULL DEFAULT '0'
						*/
						 /* 
						$res = mysql_query("SELECT news_id, news_text FROM "._DB_NEWS." WHERE news_updatecheck = 0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['news_id']."<br>";
							$news_text = TreatText($ar['news_text'],0,1);
							
							mysql_query("UPDATE "._DB_NEWS." SET news_text='".mysql_real_escape_string($news_text)."', news_updatecheck = 1 WHERE news_id=".$ar['news_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					   	  
						ALTER TABLE `---dbprefix---_news` DROP `news_updatecheck` 
						*/
						
						
						
						
						/* COMMENTS ARTICLES
						ALTER TABLE `---dbprefix---_comments` ADD `comment_updatecheck` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'
						*/
						/*
						$res = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_updatecheck = 0 AND comment_modul = '' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['comment_id']."<br>";
							mysql_query("UPDATE $db_comments SET comment_modul='article', comment_updatecheck = 1 WHERE comment_id=".$ar['comment_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						
						ALTER TABLE `---dbprefix---_comments` DROP `comment_updatecheck` 
						*/
						
						
						
						/* COMMENTS NEWS
						ALTER TABLE `---dbprefix---_comments` CHANGE `comment_modul` `comment_modul` ENUM( '', 'news', 'article', 'download', 'clanwars', 'poll', 'poll_users' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ###
						ALTER TABLE `---dbprefix---_comments` ADD `comment_updatecheck` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'
						*/
						/*
						$res = mysql_query("SELECT comment_id FROM $db_comments WHERE comment_updatecheck = 0 AND comment_modul = '' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['comment_id']."<br>";
							mysql_query("UPDATE $db_comments SET comment_modul='news', comment_updatecheck = 1 WHERE comment_id=".$ar['comment_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						
						ALTER TABLE `---dbprefix---_comments` DROP `comment_updatecheck` 
						*/
						
						
						
						
						/* COMMENTS_LOG ARTICLES
						ALTER TABLE `---dbprefix---_comments_log` CHANGE `comments_log_modul` `comments_log_modul` ENUM( '', 'actuality', 'article', 'download', 'clanwars', 'poll', 'poll_users', 'user' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ###
						ALTER TABLE `---dbprefix---_comments_log` ADD `comment_updatecheck` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'
						*/
						/*
						$res = mysql_query("SELECT comments_log_id FROM $db_comments_log WHERE comment_updatecheck = 0 AND comments_log_modul = '' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['comments_log_id']."<br>";
							mysql_query("UPDATE $db_comments_log SET comments_log_modul='article', comment_updatecheck = 1 WHERE comments_log_id=".$ar['comments_log_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					   
						ALTER TABLE `---dbprefix---_comments_log` DROP `comment_updatecheck` 
						*/
						
						
						
						
						
						/* COMMENTS_LOG ARTICLES
						ALTER TABLE `---dbprefix---_comments_log` CHANGE `comments_log_modul` `comments_log_modul` ENUM( '', 'news', 'article', 'download', 'clanwars', 'poll', 'poll_users', 'user' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ###
						ALTER TABLE `---dbprefix---_comments_log` ADD `comment_updatecheck` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'
						*/
						/*
						$res = mysql_query("SELECT comments_log_id FROM $db_comments_log WHERE comment_updatecheck = 0 AND comments_log_modul = '' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						while($ar = mysql_fetch_array($res)){
							echo $ar['comments_log_id']."<br>";
							mysql_query("UPDATE $db_comments_log SET comments_log_modul='news', comment_updatecheck = 1 WHERE comments_log_id=".$ar['comments_log_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
						
						ALTER TABLE `---dbprefix---_comments_log` DROP `comment_updatecheck` 
						*/
						
						
						
						
						
						
						
						
						
						
						
						/* Konec Scriptu */
					}
					$i++;
				}
			}
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "<br>\n";
	echo "<table border=\"0\" width=\"849\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\"><form action=\"administrace.php?action=transscript&amp;lang=".$_GET['lang']."&amp;project=".$_SESSION['project']."\" method=\"post\">\n";
	echo "	<tr class=\"popisky\">\n";
	echo "		<td width=\"80\" align=\"center\"><span class=\"nadpis-boxy\">"._CMN_OPTIONS."</span></td>\n";
	echo "		<td width=\"540\" align=\"left\"><span class=\"nadpis-boxy\">"._NAME."</span></td>\n";
	echo "	</tr>";
	echo CfgList();
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"2\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\"Odeslat dotaz\" class=\"button\">\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
	ftp_close($conn_id_adm);
}


Hlavicka();
if ($_GET['action'] == ""){Cfg();}
if ($_GET['action'] == "cfg"){Cfg();}
if ($_GET['action'] == "sql"){Sql();}
if ($_GET['action'] == "sqlmulti"){SqlMulti();}
if ($_GET['action'] == "js"){JS();}
if ($_GET['action'] == "logout"){Logout();}
if ($_GET['action'] == "folders"){Folders();}
if ($_GET['action'] == "transscript"){Transcript();}
Paticka();