<?php
// Výčet povolených tagů
$allowtags = "";
$clothes_color_title = strip_tags($_POST['clothes_color_title'],$allowtags);
$clothes_color_producer = strip_tags($_POST['clothes_color_producer'],$allowtags);

if ($_GET['action'] == "clothes_add_color"){
	mysql_query("INSERT INTO $db_shop_clothes_colors VALUES('','".(float)$_POST['clothes_color_prefix']."','','".mysql_real_escape_string($clothes_color_title)."','".mysql_real_escape_string($clothes_color_producer)."','".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_1']))."','".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_2']))."','".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_3']))."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$color_id_add = mysql_insert_id();
}
if ($_GET['action'] == "clothes_edit_color"){
	mysql_query("UPDATE $db_shop_clothes_colors SET  shop_clothes_colors_prefix=".(float)$_POST['clothes_color_prefix'].", shop_clothes_colors_title='".mysql_real_escape_string($clothes_color_title)."' , shop_clothes_colors_producer='".mysql_real_escape_string($clothes_color_producer)."', shop_clothes_colors_hex_1='".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_1']))."', shop_clothes_colors_hex_2='".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_2']))."', shop_clothes_colors_hex_3='".mysql_real_escape_string(strtoupper($_POST['clothes_color_hex_3']))."' WHERE shop_clothes_colors_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($_FILES['clothes_color_picture']['name'] == "") {$_GET['action'] = "clothes_add_color";}
}
if ($_GET['action'] == "clothes_del_color"){
	mysql_query("DELETE FROM $db_shop_clothes_colors WHERE shop_clothes_colors_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action']= "clothes_add_color";
}
if ($_FILES['clothes_color_picture']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// ziskam extenzi souboru
   	$extenze = strtok($_FILES['clothes_color_picture']['name'] ,".");
   	$extenze = strtok(".");
	// Ulozi jmeno obrazku jako
	if ($_GET['action'] == "clothes_add_color"){$color_id = $color_id_add; $cid = $color_id_add; } else {$color_id = (float)$_GET['id']; $cid = (float)$_GET['id'];}
	$color_id = Zerofill($color_id,100);
	$color_id = $_POST['clothes_color_prefix'].$color_id;
	$picture = (float)$color_id.".".$extenze;
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file = $_FILES['clothes_color_picture']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file = $ftp_path_shop_clothes_colors.$picture;
	if ($_FILES['clothes_color_picture']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
		if (!$upload) {
			$sys_save_message = _ERROR_UPLOAD." ".$picture;
		} else {
			$sys_save_message = _OK_FTP_FILE_1.' '.$destination_file.' '._OK_FTP_FILE_2;
			mysql_query("UPDATE $db_shop_clothes_colors SET shop_clothes_colors_picture='".mysql_real_escape_string($picture)."' WHERE shop_clothes_colors_id=".(float)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}

	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
	$_GET['action'] = "clothes_add_color";
	header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
	exit;
} else {
	header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".$sys_save_message);
	exit;
}