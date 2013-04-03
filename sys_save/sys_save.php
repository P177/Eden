<?php
include_once "../inc.config.php";
if (isset($_POST['project'])) {$_SESSION['project'] = $_POST['project'];} else {$_SESSION['project'] = $_GET['project'];}
if (isset($_GET['lang'])) {$_SESSION['lang'] = $_GET['lang'];}
if (isset($_GET['web_lang'])) {$_SESSION['web_lang'] = $_GET['web_lang'];}
if (!empty($_SESSION['project'])) {
	include_once "../sessions.php";
	include_once "../functions.php";
	include_once "../class/class.mail.php";
	if (empty($_SESSION['web_lang'])){
		$res = mysql_query("SELECT language_code FROM $db_language") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$_SESSION['web_lang'] = $ar['language_code'];
	}
} /* !empty($_SESSION['project']*/