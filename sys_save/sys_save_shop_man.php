<?php
if ($_FILES['shop_man_file']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// Ulozi jmeno obrazku jako
	$picture = $_FILES['shop_man_file']['name'];

	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file = $_FILES['shop_man_file']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file = $ftp_path_shop_man.$picture; //
	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
	// Zjisteni stavu uploadu
	if (!$upload) {
       	$sys_save_message = _ERROR_UPLOAD;
   	} else {
       	$sys_save_message = _OK_FTP_FILE_1.' '.$destination_file.' '._OK_FTP_FILE_2;
   	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
}

$manufacturers_name = PrepareForDB($_POST['man_name']);
$manufacturers_url = PrepareForDB($_POST['man_url']);

if ($_GET['action'] == "add_man"){
	mysql_query("INSERT INTO $db_shop_man VALUES('','".mysql_real_escape_string($manufacturers_name)."','".mysql_real_escape_string($picture)."',NOW(),NOW(),'".(float)$languages_id."','".mysql_real_escape_string($manufacturers_url)."','','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
if ($_GET['action'] == "edit_man"){
	if ($picture != ""){$man_image = ", shop_manufacturers_image='".mysql_real_escape_string($picture)."'";} else {$man_image = "";}
	mysql_query("UPDATE $db_shop_man SET shop_manufacturers_name='".mysql_real_escape_string($manufacturers_name)."' $man_image , shop_manufacturers_last_modified=NOW(), shop_manufacturers_languages_id='".(float)$languages_id."', shop_manufacturers_url='".mysql_real_escape_string($manufacturers_url)."' WHERE shop_manufacturers_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['action'] = "add_man";
}
if ($_GET['action'] == "del_man"){
	mysql_query("DELETE FROM $db_shop_man WHERE shop_manufacturers_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action'] = "add_man";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
exit;