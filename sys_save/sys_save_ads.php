<?php
/* Provereni opravneni */
if ($_GET['action'] == "adds_add"){
	if (CheckPriv("groups_adds_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=nep");}
}elseif ($_GET['action'] == "adds_edit"){
	if (CheckPriv("groups_adds_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=nep");}
}
// Podle jakych kriterii se bude reklama zobrazovat
if ($_POST['adds_show'] == "unlimited"){$adds_show_unlimited = 1; $adds_show_by_date = 0; $adds_show_by_count = 0;}
if ($_POST['adds_show'] == "by_date"){$adds_show_unlimited = 0; $adds_show_by_date = 1; $adds_show_by_count = 0;
	// Datum pro zobrazeni
	$adds_start = $_POST['adds_start_date'][6].$_POST['adds_start_date'][7].$_POST['adds_start_date'][8].$_POST['adds_start_date'][9].$_POST['adds_start_date'][3].$_POST['adds_start_date'][4].$_POST['adds_start_date'][0].$_POST['adds_start_date'][1].$_POST['adds_start_h'].$_POST['adds_start_m'].'00';
	$adds_end = $_POST['adds_end_date'][6].$_POST['adds_end_date'][7].$_POST['adds_end_date'][8].$_POST['adds_end_date'][9].$_POST['adds_end_date'][3].$_POST['adds_end_date'][4].$_POST['adds_end_date'][0].$_POST['adds_end_date'][1].$_POST['adds_end_h'].$_POST['adds_end_m'].'00';
}
if ($_POST['adds_show'] == "by_count"){$adds_show_unlimited = 0; $adds_show_by_date = 0; $adds_show_by_count = 1;}

// Výčet povolených tagů
$allowtags = "";
// Z obsahu proměnné body vyjmout nepovolené tagy
$adds_description = strip_tags($_POST['adds_description'],$allowtags);
$adds_name = strip_tags($_POST['adds_name'],$allowtags);
$adds_link = strip_tags($_POST['adds_link'],$allowtags);
$adds_link = StripInetService($adds_link);
$adds_link_onclick = strip_tags($_POST['adds_link_onclick'],$allowtags);

// Nasteveni jmena pro fotku
if ($_FILES['adds_picture']['name'] != ""){
	if (($adds_img_1 = getimagesize($_FILES['adds_picture']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($adds_img_1[2] == 1){
			$extenze = ".gif";
		} elseif ($adds_img_1[2] == 2){
			$extenze = ".jpg";
		} elseif ($adds_img_1[2] == 3){
			$extenze = ".png";
		} elseif ($adds_img_1[2] == 4){
			$extenze = ".swf";
		} elseif ($adds_img_1[2] == 13){ /* SWC format */
			$extenze = ".swf";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=wft");
			exit;
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=fni");
		exit;
	}

	// Ulozi jmeno obrazku jako
	$adds_foto1 = Cislo().$extenze;
} else {
	if ($_GET['action'] != "adds_add"){
		$res = mysql_query("SELECT adds_picture FROM $db_adds WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$adds_foto1 = $ar['adds_picture'];
	} else {
		$adds_foto1 = "";
	}
}
if ($_FILES['adds_picture2']['name'] != ""){
	if (($adds_img_2 = getimagesize($_FILES['adds_picture2']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($adds_img_2[2] == 1){
			$extenze = ".gif";
		} elseif ($adds_img_2[2] == 2){
			$extenze = ".jpg";
		} elseif ($adds_img_2[2] == 3){
			$extenze = ".png";
		} elseif ($adds_img_2[2] == 4){
			$extenze = ".swf";
		} elseif ($adds_img_2[2] == 13){ /* SWC format */
			$extenze = ".swf";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=wft");
			exit;
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=fni");
		exit;
	}

	// Ulozi jmeno obrazku jako
	$adds_foto2 = Cislo().$extenze;
} else {
	if ($_GET['action'] != "adds_add"){
		$res = mysql_query("SELECT adds_picture2 FROM $db_adds WHERE adds_id=".(integer)$_GET['id1']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$adds_foto2 = $ar['adds_picture2'];
	} else {
		$adds_foto2 = "";
	}
}

if ($_GET['action'] == "adds_edit"){
	$res = mysql_query("UPDATE $db_adds SET
	adds_name='".mysql_real_escape_string($adds_name)."',
	adds_link='".mysql_real_escape_string($adds_link)."',
	adds_link_onclick='".mysql_real_escape_string($adds_link_onclick)."',
	adds_description='".mysql_real_escape_string($adds_description)."',
	adds_main=".(integer)$_POST['adds_main'].",
	adds_gfx=".(integer)$_POST['adds_gfx'].",
	adds_category=".(integer)$_POST['adds_category'].",
	adds_list=".(integer)$_POST['adds_list'].",
	adds_publish=".(integer)$_POST['adds_publish'].",
	adds_start=".(float)$adds_start.",
	adds_end=".(float)$adds_end.",
	adds_count=".(float)$_POST['adds_count'].",
	adds_show_unlimited=".(integer)$adds_show_unlimited.",
	adds_show_by_date=".(integer)$adds_show_by_date.",
	adds_show_by_count=".(integer)$adds_show_by_count." WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "adds_add"){
	$res = mysql_query("INSERT INTO $db_adds VALUES (
	'',
	'".mysql_real_escape_string($adds_name)."',
	'".mysql_real_escape_string($adds_link)."',
	'".mysql_real_escape_string($adds_link_onclick)."',
	'',
	'',
	'".mysql_real_escape_string($adds_description)."',
	0,
	0,
	'".(integer)$_POST['adds_main']."',
	'".(integer)$_POST['adds_gfx']."',
	'".(integer)$_POST['adds_category']."',
	'".(integer)$_POST['adds_list']."',
	'".(integer)$_POST['adds_publish']."',
	'',
	'".(float)$adds_start."',
	'".(float)$adds_end."',
	'".(float)$_POST['adds_count']."',
	'".(integer)$adds_show_unlimited."',
	'".(integer)$adds_show_by_date."',
	'".(integer)$adds_show_by_count."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	(integer)$_GET['id'] = mysql_insert_id();
	$msg = "add_ok";
}
if ($_FILES['adds_picture']['name'] != "" || $_FILES['adds_picture2']['name'] != ""){
	// Prenaseni obrazku
	$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); // Spojeni s FTP serverem
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); // Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} // Zjisteni stavu spojeni
	if ($_FILES['adds_picture']['name'] != ""){
		$extenze1 = strtok($_FILES['adds_picture']['name'] ,".");// ziskam extenzi souboru
		$extenze1 = strtok(".");
		$userfile_name1 = $adds_foto1;// generuji nazev souboru
		$new_name1 = $url_adds.$userfile_name1;
		$source_file1 =  $_FILES['adds_picture']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$destination_file1 = $ftp_path_adds.$userfile_name1; // Vlozi nazev souboru a cestu do konkretniho adresare
		$upload1 = ftp_put($conn_id, $destination_file1, $source_file1, FTP_BINARY);
		if ($upload1){
			$res = mysql_query("UPDATE $db_adds SET adds_picture='".mysql_real_escape_string($adds_foto1)."' WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=wft");
			exit;
		}
	}
	if ($_FILES['adds_picture2']['name'] != ""){
		$extenze2 = strtok($_FILES['adds_picture2']['name'] ,".");// ziskam extenzi souboru
		$extenze2 = strtok(".");
		$userfile_name2 = $adds_foto2;// generuji nazev souboru
		$new_name2 = $url_adds.$userfile_name2;
		$source_file2 =  $_FILES['adds_picture2']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$destination_file2 = $ftp_path_adds.$userfile_name2; // Vlozi nazev souboru a cestu do konkretniho adresare
		$upload2 = ftp_put($conn_id, $destination_file2, $source_file2, FTP_BINARY);
		if ($upload2){
			$res = mysql_query("UPDATE $db_adds SET adds_picture2='".mysql_real_escape_string($adds_foto2)."' WHERE adds_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action=".$_GET['action']."&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=wft");
			exit;
		}
	}
	ftp_close($conn_id);// Uzavreni komunikace se serverem
}
header ("Location: ".$eden_cfg['url_cms']."modul_ads.php?action_link=open&action=open&id=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=".$msg);
exit;