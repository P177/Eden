<?php
// Výčet povolených tagů
$allowtags = "";
// Z obsahu proměnné body vyjmout nepovolené tagy
$dl_name = strip_tags($_POST['dl_name'],$allowtags);
$dl_version = strip_tags($_POST['dl_version'],$allowtags);
$dl_desc = strip_tags($_POST['dl_desc'],"<a>");
$dl_author_web = strip_tags($_POST['dl_author_web'],$allowtags);
$dl_link = strip_tags($_POST['dl_link'],$allowtags);
$dl_link2 = strip_tags($_POST['dl_link2'],$allowtags);
$dl_link3 = strip_tags($_POST['dl_link3'],$allowtags);
$dl_service = strip_tags($_POST['dl_service'],$allowtags);
$dl_service2 = strip_tags($_POST['dl_service2'],$allowtags);
$dl_service3 = strip_tags($_POST['dl_service3'],$allowtags);
$dl_licence = strip_tags($_POST['dl_licence'],$allowtags);
$dl_lang = strip_tags($_POST['dl_lang'],$allowtags);

// Slouceni operacnich systemu
$dl_os = $_POST['dl_os'][0]."||".$_POST['dl_os'][1]."||".$_POST['dl_os'][2]."||".$_POST['dl_os'][3]."||".$_POST['dl_os'][4]."||".$_POST['dl_os'][5]."||".$_POST['dl_os'][6]."||".$_POST['dl_os'][7]."||".$_POST['dl_os'][8];

//Zformatovani datumu
$datum_zarazeni = date("YmdHis");

// Nasteveni jmena pro fotku
if ($_FILES['dl_img_1']['name'] != ""){
	if (($dl_img_1 = getimagesize($_FILES['dl_img_1']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($dl_img_1[2] == 1){
			$extenze = ".gif";
		} elseif ($dl_img_1[2] == 2){
			$extenze = ".jpg";
		} elseif ($dl_img_1[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_download.php?action=dl_edit&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&project=".$_SESSION['project']."&msg=wft");
			exit;
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_download.php?action=dl_edit&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&project=".$_SESSION['project']."&msg=fni");
		exit;
	}

	// Ulozi jmeno obrazku jako
	$dl_img1 = Cislo().$extenze;
} else {
	$res = mysql_query("SELECT download_img FROM $db_download WHERE download_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$dl_img1 = $ar['download_img'];
}

if ($_GET['action'] == "dl_add"){
	// Ulozeni zaznamu do databaze
	$datum = date("YmdHis"); // Aktualni datum pri ukladani novinky
	mysql_query("INSERT INTO $db_download VALUES(
	'',
	'".mysql_real_escape_string($dl_name)."',
	'".mysql_real_escape_string($dl_version)."',
	'".mysql_real_escape_string($dl_desc)."',
	'".mysql_real_escape_string($dl_author_web)."',
	'".mysql_real_escape_string($dl_link)."',
	'".mysql_real_escape_string($dl_link2)."',
	'".mysql_real_escape_string($dl_link3)."',
	'".(float)$dl_category1."',
	'".(float)$dl_category2."',
	'".(float)$dl_category3."',
	'".(float)$dl_category4."',
	'".(float)$dl_category5."',
	'".mysql_real_escape_string($dl_os)."',
	'".mysql_real_escape_string($dl_licence)."',
	'".(float)$_POST['dl_rating']."',
	'',
	".$datum.",
	'0',
	'',
	'".(float)$_POST['dl_size']."',
	'".mysql_real_escape_string($dl_lang)."',
	'',
	'',
	'',
	'".mysql_real_escape_string($dl_service)."',
	'".mysql_real_escape_string($dl_service2)."',
	'".mysql_real_escape_string($dl_service3)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
	$msg = "add_ok";
}

// Ulozeni pri editaci
if ($_GET['action'] == "dl_edit"){
	$res = mysql_query("UPDATE $db_download SET
	download_name='".mysql_real_escape_string($dl_name)."',
	download_version='".mysql_real_escape_string($dl_version)."',
	download_description='".mysql_real_escape_string($dl_desc)."',
	download_author_web='".mysql_real_escape_string($dl_author_web)."',
	download_link='".mysql_real_escape_string($dl_link)."',
	download_link2='".mysql_real_escape_string($dl_link2)."',
	download_link3='".mysql_real_escape_string($dl_link3)."',
	download_category1=".(float)$dl_category1.",
	download_category2=".(float)$dl_category2.",
	download_category3=".(float)$dl_category3.",
	download_category4=".(float)$dl_category4.",
	download_category5=".(float)$dl_category5.",
	download_os='".mysql_real_escape_string($dl_os)."',
	download_licence='".mysql_real_escape_string($dl_licence)."',
	download_rating=".(float)$_POST['dl_rating'].",
	download_size=".(float)$_POST['dl_size'].",
	download_languages='".mysql_real_escape_string($dl_lang)."',
	download_service='".mysql_real_escape_string($dl_service)."',
	download_service2='".mysql_real_escape_string($dl_service2)."',
	download_service3='".mysql_real_escape_string($dl_service3)."' WHERE download_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}

if ($_FILES['dl_img_1']['name'] != ""){
	// Prenaseni obrazku
	$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); // Spojeni s FTP serverem
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); // Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} // Zjisteni stavu spojeni
	$extenze = strtok($_FILES['dl_img_1']['name'] ,".");// ziskam extenzi souboru
	$extenze = strtok(".");
	$userfile_name = $dl_img1;// generuji nazev souboru
	$new_name = $url_download.$userfile_name;
	$source_file =  $_FILES['dl_img_1']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$destination_file = $ftp_path_download.$userfile_name; // Vlozi nazev souboru a cestu do konkretniho adresare
	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
	if ($upload){
		$res = mysql_query("UPDATE $db_download SET download_img='".mysql_real_escape_string($dl_img1)."' WHERE download_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_download.php?action=dl_edit&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&project=".$_SESSION['project']."&msg=feu");
		exit;
	}
	ftp_close($conn_id);// Uzavreni komunikace se serverem
}
$_GET['action'] = "open";
$_GET['action_dw'] = "open";
$id = $id1; // Musi byt uvedeno, jinak funkce ShowMain prebira hodnotu $id z global
header ("Location: ".$eden_cfg['url_cms']."modul_download.php?action_dl=open&action=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_dl=".$_GET['id_dl']."&project=".$_SESSION['project']."&msg=".$msg);
exit;?>