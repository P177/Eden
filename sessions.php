<?php
if ($project != ""){
	/* Nic se nedeje */
} elseif($_SESSION['project'] != ""){
	$project = $_SESSION['project'];
} elseif($_GET['project'] != ""){
	$project = $_GET['project'];
} else {
	$project = $_POST['project'];
}

if (!isset($project)) {
	header ("Location: index.php?action=msg&amp;msg=tryagain1");
	exit;
} else {
	require_once(dirname(__FILE__)."/cfg/db.".$project.".inc.php");
	$mc = mysql_connect($eden_cfg['db_server'], $eden_cfg['db_uname'], $eden_cfg['db_pass']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ms = mysql_select_db($eden_cfg['db_name']);
	/* Nacteni hodnoty doba_pripojeni z diskuze_nastaveni do promenne $doba */
	$res_setup = mysql_query("SELECT setup_basic_lang, setup_eden_joining_time FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$doba = $ar_setup['setup_eden_joining_time'];
	if(empty($_SESSION['web_lang'])){$web_lang = $ar_setup['setup_basic_lang'];}
	$eproject = $project;
	session_name("autorizace");
	session_start();
	$sid = session_id();
	$project = $eproject;
	$time = date("U");
	$at = (date("U") - $doba); // Doba pripojeni
	if (!isset($mod)){$mod = "";} // Inicializujeme prommenou pokud jeste inicializovana neni
	if ($mod == "overeni"){
		$resu = mysql_query("SELECT sessions_id FROM $db_sessions WHERE sessions_user='".mysql_real_escape_string($login)."' AND sessions_id='".$sid."' AND sessions_pages='eden'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$numu = mysql_num_rows($resu);
		$aru = mysql_fetch_array($resu);
		if ($numu < 1){ // Kdyz v databazi neni nalezen zaznam, zalozi se novy
			mysql_query("INSERT INTO $db_sessions VALUES ('".$sid."', '".$time."', INET_ATON('".$eden_cfg['ip']."'), '".mysql_real_escape_string($login)."', 'eden')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("DELETE FROM $db_sessions WHERE sessions_date < ".$at) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
			$_SESSION['project'] = $project;
			$_SESSION['sid'] = $sid;
			$_SESSION['login'] = strtoupper($login);
			$_SESSION['loginid'] = $loginid;
			$_SESSION['lang'] = $lang;
			// Do logu ulozime informaci o prihlaseni
			require_once("./class/class.edenlog.php");
			EdenLog(1);
			header ("Location: ".$odkaz.$_SESSION['project']);
		}elseif ($aru['sessions_id'] == $sid){ // Kdyz zaznam je nalezen a shoduje se $sid se zaznamem v databazi updatuje se cas
			mysql_query("UPDATE $db_sessions SET sessions_date='".$time."' WHERE sessions_id='".$aru['sessions_id']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$_SESSION['project'] = $project;
			$_SESSION['sid'] = $sid;
			$_SESSION['login'] = strtoupper($login);
			$_SESSION['loginid'] = $loginid;
			$_SESSION['lang'] = $lang;
			header ("Location: ".$odkaz.$_SESSION['project']."");
		} else { // Kdyz je nalezen zaznam, ale neco nesedi tak se smaze dana session a vytvori se nova
			mysql_query("DELETE FROM $db_sessions WHERE sessions_user='".mysql_real_escape_string($login)."' AND sessions_id='".$sid."' AND sessions_pages='eden'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("INSERT INTO $db_sessions VALUES ('".$sid."', '".$time."', INET_ATON('".$eden_cfg['ip']."'), '".mysql_real_escape_string($login)."', 'eden')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
			$_SESSION['project'] = $project;
			$_SESSION['sid'] = $sid;
			$_SESSION['stranky'] = "eden";
			$_SESSION['login'] = strtoupper($login);
			$_SESSION['loginid'] = $loginid;
			$_SESSION['lang'] = $lang;
			header ("Location: ".$odkaz.$_SESSION['project']."");
		}
	} else { //AND date >= '$at'
	   	$msq = mysql_query("SELECT COUNT(*) FROM $db_sessions WHERE sessions_id='".$sid."' AND sessions_ip=INET_ATON('".$eden_cfg['ip']."') AND sessions_user='".mysql_real_escape_string($_SESSION['login'])."' AND sessions_pages='eden' ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_msq = mysql_fetch_array($msq);
		if ($num_msq[0] <> 1) {
			//mysql_query("DELETE FROM $db_sessions WHERE sessions_id='".$sid."'") or die ("<strong>"._ERROR_DB." </strong>".mysql_error());
			$lgn = $_SESSION['login']; // Jen pro testovaci ucely
			$_SESSION = array();
			if (isset($_COOKIE[session_name('autorizace')])) {
			    setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
			header ("Location: index.php?action=msg&msg=tryagain2&sid=".$sid."&ip=".$eden_cfg['ip']."&login=".$lgn."&num_msg=".$num_msq[0]."");
		} else {
			$_SESSION['articles_public'] = $_GET['articles_public'];
			if (isset($_GET['web_lang'])){$_SESSION['web_lang'] = $_GET['web_lang'];}
			if (isset($_GET['lang'])){$_SESSION['lang'] = $_GET['lang'];}
			mysql_query("UPDATE $db_sessions SET sessions_date='".$time."', sessions_pages='eden' WHERE sessions_id='".$sid."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	mysql_close($mc);
}