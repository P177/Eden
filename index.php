<?php
$eden_cfg['www_dir'] = dirname(__FILE__);
$eden_cfg['ip'] = $_SERVER["REMOTE_ADDR"];
/*
if ($_SERVER["REMOTE_ADDR"] == "92.27.38.30" || $eden_cfg['ip'] == "192.168.1.3" || $eden_cfg['ip'] == "127.0.0.1"){
	$title_maintanance = "Maintanance mode! - ";
} else {
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n";
	echo "    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "  <title>EDEN</title>\n";
	echo "</head>\n";
	echo "<body><div style=\"width:500px;margin:auto;\">\n";
	echo "<h1>We are down for maintanance</h1>";
	echo "<h2>Sorry for the inconvinience. We will be back shortly.</h2>";
	echo "</div></body>\n";
	echo "</html>";
	exit;
}
*/
if (!isset($title_maintanance)){$title_maintanance = "";}

/*
if ($eden_cfg['ip'] == "127.0.0.1" || $eden_cfg['ip'] == "192.168.0.3" || $eden_cfg['ip'] == "192.168.0.4"){
} else {
	$SSL_Port=60006; // port SSL komunikaci, administrátor serveru muže nastavit i jiný
	if ($SERVER_PORT!=$SSL_Port) { // overit, zda jde o protokol https
	  if (empty($mustbesecure)) // je-li prázdná testovací promenná, presmerovat na zabezpecenou stránku s nastavením této promenné
	    header("Location: https://lord.pes.cz:60006$SCRIPT_NAME?$QUERY_STRING&mustbesecure=1");
	  else
	    echo "Zabezpecené spojení nelze navázat"; // ani po presmerování se nezdarilo zabezpecený prenos navázat
	  exit;
	}
}
*/
include_once "./inc.config.php";
if (isset($_GET['lang']) == ""){$_GET['lang'] = "cz";}
include_once dirname(__FILE__)."/lang/lang-".$_GET['lang'].".php";

//********************************************************************************************************
//
//             PRIHLASENI DO SYSTEMU
//
//********************************************************************************************************
function Prihlaseni(){
	Hlavicka();
	echo "<form action=\"index.php?action=login&amp;lang=".$_GET['lang']."\" method=\"post\">\n";
	echo "	<a href=\"index.php?lang=cz\"><img src=\"images/lang/CZ.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" align=\"middle\"></a>&nbsp;<a href=\"index.php?lang=en\"><img src=\"images/lang/GB.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" align=\"middle\"></a>\n";
	echo "	<br><br>"._PROJECT."<br>\n";
	echo "	<input type=\"text\" name=\"project\" value=\"\" size=\"15\"><br>\n";
	echo "	"._ADMIN_USERNAME."<br>\n";
	echo "	<input type=\"text\" name=\"login\" size=\"15\" value=\"\"><br>\n";
	echo "	"._PASSWORD."<br>\n";
	echo "	<input type=\"password\" name=\"pass\" size=\"15\"><br>\n";
	echo "	<input type=\"hidden\" name=\"stranky\" value=\"eden\">\n";
	echo "	<input type=\"submit\" value=\""._CMN_LOGIN."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?action=logout&amp;lang=".$_GET['lang']."&amp;project="; /* toto je nedoresene, potrebuje to prebrat nazev projektu po stisku BACK, jinak funkce logout NEVI z jakeho projektu je to brano*/ echo "\"><img src=\"./images/login_logout.gif\" border=\"0\" alt=\""._CMN_LOGOUT."\"></a>\n";
	echo "</form>";
	Prostredek();
	Paticka();
}

//********************************************************************************************************
//
//             OVERENI ADMINISTRACNICH OPRAVNENI
//
//********************************************************************************************************
function Overeni(){
	
	global $db_admin,$db_setup,$db_eden_log;
	global $eden_cfg;
	global $odkaz,$mod,$ftp_path_cfg_adm;
	
	include_once (dirname(__FILE__)."/cfg/db.admin.inc.php");
	$login = strtoupper($_POST['login']);
	
	// Musime se prihlasit na FTP server
	$conn_id = ftp_connect($eden_cfg['ftp_server_adm']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name_adm'], $eden_cfg['ftp_user_pass_adm']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)){echo _ERROR_FTP; exit;}
	// Nacteme do pole seznam souboru v danem umisteni
	$projectcfg = ftp_nlist($conn_id, $ftp_path_cfg_adm);
	$x = count($projectcfg);
	
	$i = 0;
	while($i<$x){
		// Kdyz se shoduje nazev projektu zadany ve formulari s nazvem souboru
		//Pripravime promennou (pouziva se v ni /)
		$preg_ftp_path_cfg_adm = str_replace("/","\/",$ftp_path_cfg_adm);
		if (preg_replace ("/(".$preg_ftp_path_cfg_adm.")(.+)/i","\\2", $projectcfg[$i]) == "db.".$_POST['project'].".inc.php") {
			// Tak se config nacte
			
			include_once (dirname(__FILE__)."/cfg/db.".$_POST['project'].".inc.php");
			mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_select_db($eden_cfg['db_name']);
			
			/*	Odstraneni prebytecnych znaku	*/
			$admin_username = strtoupper($_POST['login']);
			
			$p = MD5(MD5($_POST['pass']).strtoupper($admin_username));
			
			$result = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($login)."' AND admin_password='".mysql_real_escape_string($p)."' AND admin_status='admin'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num = mysql_num_rows($result);
			if ($num == 1){
				// Musim zajistit, aby se vedelo, kdy se overuje heslo
				$ar = mysql_fetch_array($result);
				$loginid = $ar['admin_id'];
				$mod = "overeni";
				$project = $_POST['project'];
				include "./sessions.php";
				exit;
			} else {
				header ("Location: index.php?action=msg&msg=badlogin");
			}
		}
		$i++;
	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
	// Kdyz se neshoduje, nahlasi chybu pro zadani spravneho projectu
	header ("Location: index.php?action=msg&msg=badproject");
}

//********************************************************************************************************
//
//             LOGIN
//
//********************************************************************************************************
function Login(){
	
	global $db_admin,$db_sessions;
	global $eden_cfg;
	global $odkaz;
	
	switch ($_POST['project']) {
		case "admin";
			include_once (dirname(__FILE__)."/cfg/db.admin.inc.php");
			
			// Pri prihlaseni do admin je treba porovnast zadane udaje a korektnost hesla
			$login = strtoupper($_POST['login']);
			$jmeno = "PITTBULL";
			$pass = MD5(MD5($_POST['pass']).$login);
			$heslo = "e3c468cfdcd5ffced1756b3d2b05af28";
				if ($login == $jmeno && $pass == $heslo) {
					$psw = 1;
				}else {
					$psw = 0;
				}
			if ($psw == 1) {
				$sn = "autorizace"; //Nazev sessions
				session_name("$sn");
				session_start();
				$sid = session_id();
				$time = date("U"); // Aktualni cas
				$_SESSION['doba'] = $time; // Zde se zapise doba pripojeni
				$_SESSION['project'] = $_POST['project'];
				$_SESSION['sid'] = $sid;
				$_SESSION['ip'] = $eden_cfg['ip'];
				$_SESSION['login'] = strtoupper($login);
				
				header ("Location: administrace.php?project=admin");
			} else {
				header ("Location: index.php?action=msg");
			}
		break;
		default:
			$odkaz = "sys_statistics.php?project=";
			Overeni();
	}
}
//********************************************************************************************************
//
//             LOGOUT
//
//********************************************************************************************************
function LogOut(){
	
	global $eden_cfg;
	global $db_eden_log;
	
	session_name("autorizace");
	session_start();
	$sid = session_id();
	if (!isset($_SESSION['project'])) {
		header ("Location: index.php?action=msg&msg=logout");
	} else {
		include_once (dirname(__FILE__)."/cfg/db.".$_SESSION['project'].".inc.php");
		mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		mysql_select_db($eden_cfg['db_name']);
		// Do logu ulozime informaci o prihlaseni
		require_once("./class/class.edenlog.php");
		EdenLog(2);
		mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($sid)."' AND sessions_pages='eden'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$_SESSION = array();
		if (isset($_COOKIE[session_name('autorizace')])) {
		    setcookie(session_name('autorizace'), '', time()-42000, '/');
		}
		session_destroy();
		header ("Location: index.php?action=msg&msg=logout");
	}
}
//********************************************************************************************************
//
//             ODSTRANENI PREBYTECNYCH ZNAKU
//
//********************************************************************************************************
function CleanAdminUsername($admin_username){
	$admin_username = strtoupper($admin_username);
	$replacement = '_';
	$pattern = "/ /";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$replacement = '';
	$pattern = "/]/";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = '';
	$pattern = "/\)/";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = '';
	$pattern = "/[ešcržýáíéúunótd`´~?|@#$%^}{&\[*\(=;:'\\\",<>\/§]/i";
	$admin_username = preg_replace($pattern,$replacement, $admin_username);
	$replacement = '_';
	$pattern = "/_{2,}/";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$replacement = '';
	$pattern = "/^_/";
	$admin_username = preg_replace($pattern, $replacement, $admin_username);
	$admin_username = substr($admin_username,0,30);
	return $admin_username;
}
//********************************************************************************************************
//
//             HLAVICKA
//
//********************************************************************************************************
function Hlavicka(){
	
	global $hlaska,$eden_title,$eden_login_logo,$title_maintanance;
	
	switch (isset($_GET['msg'])) {
	case "logout";
		$hlaska = _LOGIN_WAS_LOGOUT;
	break;
	case "badlogin";
		$hlaska = _LOGIN_BADLOGIN;
	break;
		case "badproject";
		$hlaska = _LOGIN_BADPROJECT;
	break;
		case "tryagain1";
		$hlaska = _LOGIN_TRYAGAIN;
	break;
		case "tryagain2";
		$hlaska = _LOGIN_TRYAGAIN;
	break;
	default;
		$hlaska = "";
	}
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<title>".$title_maintanance.$eden_title."</title>\n";
	echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden.css\">\n";
	echo "</head>\n";
	echo "<body topmargin=\"0\" leftmargin=\"0\">\n";
	echo "<div align=\"center\">\n";
	echo "<table width=\"267\" align=\"center\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td height=\"50\" align=\"center\" colspan=\"2\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td height=\"45\" width=\"148\" align=\"left\" bgcolor=\"#80A0D3\">".$eden_login_logo."</td>\n";
	echo "		<td height=\"45\" align=\"left\" bgcolor=\"#80A0D3\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td height=\"27\" align=\"center\" bgcolor=\"#999999\" colspan=\"2\" class=\"sys_nadpis1\">"; if ($hlaska != ""){echo $hlaska;} else {echo _LOGIN_LOGIN;} echo "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td height=\"179\" align=\"left\" bgcolor=\"#EBEBEC\"><img src=\"images/a_bod.gif\" width=\"10\" height=\"179\" border=\"0\" alt=\"\" align=\"left\"><br>\n";
}

function Prostredek(){
	echo "		</td>\n";
	echo "		<td height=\"179\" align=\"left\" bgcolor=\"#EBEBEC\"><img src=\"images/login_dekor.gif\" width=\"115\" height=\"179\" border=\"0\" alt=\"\"></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function Paticka(){
	
	global $eden_start_year,$eden_cms_link;
	
	echo "<table width=\"267\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" class=\"sys_paticka\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"267\" height=\"27\" align=\"center\">&copy; ".$eden_start_year." - ".date('Y')." ".$eden_cms_link."</td>\n";
	echo "	</tr>\n";
	echo "</table></div>\n";
	echo "</body>\n";
	echo "</html>";
}

if ($_GET['action'] == "login") { Login(); }
if ($_GET['action'] == "msg") { Prihlaseni(); }
if (isset($_GET['action']) == "") { Prihlaseni(); }
if ($_GET['action'] == "ukaz") { Ukaz(); }
if ($_GET['action'] == "zapis") { Zapis(); }
if ($_GET['action'] == "vyber") { Vyber(); }
if ($_GET['action'] == "logout") { LogOut(); }