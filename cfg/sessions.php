<?php
if ($project != ""){/* Nic se nedeje */}elseif($_SESSION['project'] != ""){$project = $_SESSION['project'];}elseif($_GET['project'] != ""){$project = $_GET['project'];} else {$project = $_POST['project'];}
if ($project == ""){
	header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=tryagain1");
	exit;
} else {
	require (dirname(__FILE__)."/db.".$project.".inc.php");
	$mc = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ms = mysql_select_db($eden_cfg['db_name']);
	if ($eden_cfg['db_collate'] != ""){mysql_query($eden_cfg['db_collate']);}
	if ($eden_cfg['db_collate_connection'] != ""){mysql_query($eden_cfg['db_collate_connection']);}
	if ($eden_cfg['db_encode'] != ""){mysql_query($eden_cfg['db_encode']);}
	
	$login = strtoupper(AGet($_POST,'login'));
	
	/*
	echo dirname(__FILE__)."/db.".$project.".inc.php<br>";
	echo "@mod = ".$mod."<br>";
	echo "@project = ".$project."<br>";
	echo "@db_setup = ".$db_setup."<br><br />";
	*/
	
	// Nacteni hodnoty doba_pripojeni z diskuze_nastaveni do promenne $doba
	$res_setup = mysql_query("SELECT setup_eden_joining_time FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$doba = $ar_setup[0];
	$sn = "autorizace";
	session_name("$sn");
	if( !isset( $_SESSION ) ) { session_start(); }
	$sid = session_id();
	$time = date("U");
	$at = (date("U") - $doba); // Doba pripojeni
	$_SESSION['project'] = $project;
	if (isset($mod) == "overeni"){
		$resu = mysql_query("SELECT * FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numu = mysql_num_rows($resu);
		$aru = mysql_fetch_array($resu);
		// Kdyz v databazi neni nalezen zaznam, zalozi se novy
		if ($numu < 1){
			mysql_query("INSERT INTO $db_sessions VALUES ('".mysql_real_escape_string($_SESSION['sidd'])."', '".(float)$time."', INET_ATON('".$eden_cfg['ip']."'), '".mysql_real_escape_string($login)."', '".mysql_real_escape_string($eden_cfg['misc_web'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("DELETE FROM $db_sessions WHERE sessions_date < ".(float)$at) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("DELETE FROM $db_sessions WHERE sessions_ip=INET_ATON('".$eden_cfg['ip']."') AND sessions_date < ".(float)$at." AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
/*
echo "action = ".$_GET['action']."<br>";
echo "project = ".$project."<br>";
echo "login = ".$_POST['login']."<br>";
echo "pass = ".$_POST['pass']."<br>";
echo "mod = ".$mod."<br>";
echo "login = ".$login."<br>";
echo "ip = ".$eden_cfg['ip']."<br>";
echo "stranky = ".$eden_cfg['misc_web']."<br>";
echo "odkaz = ".$odkaz."<br>";
echo "session_sidd = ".$_SESSION['sidd']."<br>";
echo "lkf;sldkfslkfdj";
exit;
*/

			$_SESSION['stranky'] = $eden_cfg['misc_web'];
			$mod = "";
			header ("Location: ".$odkaz);
			exit;
		// Kdyz zaznam je nalezen a shoduje se $sid se zaznamem v databazi updatuje se cas
		}elseif ($aru['sessions_id'] == $sid && $aru['sessions_pages'] == $eden_cfg['misc_web']){
			mysql_query("UPDATE $db_sessions SET sessions_date=".(float)$time.", sessions_user='".mysql_real_escape_string($login)."', sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'  WHERE sessions_id='".mysql_real_escape_string($aru['sessions_id'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_SESSION['stranky'] = $eden_cfg['misc_web'];
			$mod = "";
/*
echo "action = ".$_GET['action']."<br>";
echo "action_shop = ".$_GET['action_shop']."<br>";
echo "project = ".$project."<br>";
echo "login = ".$_POST['login']."<br>";
echo "pass = ".$_POST['pass']."<br>";
echo "mod = ".$mod."<br>";
echo "login = ".$login."<br>";
echo "ip = ".$eden_cfg['ip']."<br>";
echo "stranky = ".$eden_cfg['misc_web']."<br>";
echo "odkaz = ".$odkaz."<br>";
echo "session_sidd = ".$_SESSION['sidd']."<br>";
echo "session status = ".$_SESSION['u_status']."<br>";
echo "\$numu = ".$numu."<br>";
exit;
*/
			if(AGet($_POST,'action_shop') == "login"){
				/****	EDENSHOP Update kosiku - START	****/
				/*
				*	Kdyz se uzivatel zaloguje zkontrolujeme, co je ulozeno v kosiku
				*/
				$res_admin = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($login)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_admin = mysql_fetch_array($res_admin);
				$res_bask = mysql_query("SELECT shop_basket_products_id, shop_basket_quantity, shop_basket_session_id FROM $db_shop_basket WHERE shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND shop_basket_admin_id=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_bask = mysql_fetch_array($res_bask)){
						
						// Kdyz je to ta sama vec jaka uz v kosiku je, tak pripocteme pocet nove vlozenych veci
						$res_bask2 = mysql_query("SELECT shop_basket_quantity FROM $db_shop_basket WHERE shop_basket_admin_id=".$ar_admin['admin_id']." AND shop_basket_products_id=".$ar_bask['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$ar_bask2 = mysql_fetch_array($res_bask2);
						$num_bask2 = mysql_num_rows($res_bask2);
						if($num_bask2 > 0){
							mysql_query("UPDATE $db_shop_basket SET shop_basket_quantity=".$ar_bask2['shop_basket_quantity']." + ".$ar_bask['shop_basket_quantity']." WHERE shop_basket_admin_id=".$ar_admin['admin_id']." AND shop_basket_products_id=".$ar_bask['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							mysql_query("DELETE FROM $db_shop_basket WHERE  shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND shop_basket_admin_id=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							
						// Kdyz je to vec, ktera v danem kosiku jeste neni, pridame ji jen ID admina
						} else {
							mysql_query("UPDATE $db_shop_basket SET shop_basket_admin_id=".$ar_admin['admin_id']." WHERE shop_basket_session_id='".mysql_real_escape_string($ar_bask['shop_basket_session_id'])."' AND shop_basket_products_id=".$ar_bask['shop_basket_products_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						}
					}
				header ("Location: ".$eden_cfg['url']."index.php?action=vb&lang=".$_GET['lang']."&filter=".$_GET['filter']);
				exit;
				/****	EDENSHOP Update kosiku - END	****/
			} else {
				header ("Location: ".$odkaz);
				exit;
			}
		// Kdyz je nalezen zaznam, ale neco nesedi tak se smaze dana session a vytvori se nova
		} else {
			mysql_query("DELETE FROM $db_sessions WHERE sessions_user='".mysql_real_escape_string($login)."' OR sessions_user='".mysql_real_escape_string($_SESSION['sidd'])."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("INSERT INTO $db_sessions VALUES ('".mysql_real_escape_string($sid)."', '".(float)$time."', INET_ATON('".$eden_cfg['ip']."'), '".mysql_real_escape_string($login)."', '".mysql_real_escape_string($eden_cfg['misc_web'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_SESSION['stranky'] = $eden_cfg['misc_web'];
			$_SESSION['sidd'] = $sid;
			setcookie($project.'_session_id', '', time() - 604800);
			setcookie($project.'_session_id',$_SESSION['sidd'], time() + 604800);
			$mod = "";
			header ("Location: ".$odkaz);
			exit;
		}
	} else {
		// Kdyz je User nezalogovany, nekontroluji se zadne bezpecnostni prvky
		if (AGet($_SESSION,'u_status') == "vizitor"){
			mysql_query("UPDATE $db_sessions SET sessions_date=".(float)$time.", sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."' WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd']).")'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		// Pokud je User zalogovany jako admin nebo jako user
		} elseif (AGet($_SESSION,'u_status') == "user" || AGet($_SESSION,'u_status') == "admin" || AGet($_SESSION,'u_status') == "seller"){
			/* Proverime zda je jeste porad clenem dane skupiny (user/admin/seller - pripadne zmenime zapis v SESSION */
			$res_admin = mysql_query("SELECT admin_status FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_admin = mysql_fetch_array($res_admin);
			$_SESSION['u_status'] = $ar_admin['admin_status'];
			
			$msq = mysql_query("SELECT * FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND (sessions_date > ".(float)$at.") AND sessions_ip=INET_ATON('".$eden_cfg['ip']."') AND sessions_user='".mysql_real_escape_string($_SESSION['login'])."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			// Zjisti se, zda jiz neni uzivatel jednou zalogovan a takova session se odstrani,
			// nebo ze uz je session odstranena z duvodu vyprseni
			// Taky se odstrani vsechny sessions s danou IP pro danou stranku a ktere existuji dele nez je povoleny limit
			if (mysql_num_rows($msq) <> 1) {
				mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("DELETE FROM $db_sessions WHERE sessions_ip=INET_ATON('".$eden_cfg['ip']."') AND sessions_date < ".(float)$time." AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				// V pripade, ze je session kvuli vyprseni smazana a uzivatel neco odeslal (comment, obednavku atd.) a session byla
				// volana z eden_save.php nebo eden_shop_save.php je tato session zachovana a vytvorena znova
				if (isset($edensave) && $edensave == 1){
					session_id($_SESSION['sidd']);
					mysql_query("INSERT INTO $db_sessions VALUES ('".mysql_real_escape_string($_SESSION['sidd'])."', '".(float)$time."', INET_ATON('".$eden_cfg['ip']."'), '".mysql_real_escape_string($_SESSION['login'])."', '".mysql_real_escape_string($eden_cfg['misc_web'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				// Pokud je zapnut autologin, automaticky se nactou potrebne udaje z cookies
				}elseif (($_COOKIE[$project."_autologin"] == 1) AND ($_COOKIE[$project."_name"] != "") AND ($_COOKIE[$project."_pass"] != "")){
					$p = $_COOKIE[$project."_pass"];
					$login = strtoupper($_COOKIE[$project."_name"]);
					$_SESSION['login'] = $login;
					$_SESSION['loginid'] = $_COOKIE[$project."_loginid"];
					$_SESSION['sidd'] = $sid;
					$cookie = $_COOKIE[$project."_session_id"];
					mysql_query("UPDATE $db_sessions SET sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' WHERE sessions_id='".mysql_real_escape_string($cookie)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					if (AGet($_POST,'action_shop') == "login"){
						mysql_query("UPDATE $db_shop_basket SET shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' WHERE shop_basket_session_id='".mysql_real_escape_string($cookie)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					}
					setcookie($project.'_session_id', '', time() - 604800);
					setcookie($project.'_session_id',$_SESSION['sidd'], time() + 604800);
				} else {
					$_SESSION = array();
					if (isset($_COOKIE[session_name('autorizace')])) {
					    setcookie(session_name('autorizace'), '', time()-42000, '/');
					}
					session_destroy();
					header ("Location: ".$eden_cfg['url']."index.php?action=msg&msg=session_expired");
					exit;
				}
			// Pokud je nalezen jen jedna vyhovujici session provede se aktualizace
			} else {
				mysql_query("UPDATE $db_sessions SET sessions_date=".(float)$time." WHERE sessions_id='".mysql_real_escape_string($sid)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		// Pokud to neni ani jeden pripad z vyse uvedenych
		} else {
			$_SESSION['u_status'] = "vizitor";
			$_SESSION['project'] = $project;
			$_SESSION['stranky'] = $eden_cfg['misc_web'];
			$_SESSION['sidd'] = $sid;
			// Pridelime uzivateli co nema cislo v cookies jedinecny identifikator
			if (!isset($_COOKIE[$project."_session_id"])){
				setcookie($project.'_session_id', '', time() - 604800);
				setcookie($project.'_session_id',$_SESSION['sidd'], time() + 604800);
				$session_sid = $_SESSION['sidd'];
			} else {
				$cookie_sid = $_COOKIE[$project."_session_id"];
				// Zapiseme nove session_id do kosiku, pokud takovy zaznam existuje
				mysql_query("UPDATE $db_shop_basket SET shop_basket_session_id='".mysql_real_escape_string($_SESSION['sidd'])."' WHERE shop_basket_session_id='".mysql_real_escape_string($cookie_sid)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$session_sid = $cookie_sid;
			}
			// Odstranime vsechny sessions, ktere maji stejny identifikator a stranku
			mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($session_sid)."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("DELETE FROM $db_sessions WHERE sessions_date < ".(float)$at) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("DELETE FROM $db_sessions WHERE sessions_ip=INET_ATON('".$eden_cfg['ip']."') AND sessions_date < ".(float)$time." AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$res = mysql_query("SELECT COUNT(*) FROM $db_sessions WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."' AND sessions_ip=INET_ATON('".$eden_cfg['ip']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num = mysql_fetch_array($res);
			if($num[0] < 1){
				// Zalozime novvy zaznam se sessions a ostatnimi udaji
				mysql_query("INSERT INTO $db_sessions VALUES ('".mysql_real_escape_string($_SESSION['sidd'])."', '".(float)$time."', INET_ATON('".$eden_cfg['ip']."'), '', '".mysql_real_escape_string($eden_cfg['misc_web'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} else {
				mysql_query("UPDATE $db_sessions SET sessions_date=".(float)$time." WHERE sessions_id='".mysql_real_escape_string($_SESSION['sidd'])."' AND sessions_pages='".mysql_real_escape_string($eden_cfg['misc_web'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
	}
mysql_close($mc);
}