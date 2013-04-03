<?php
// Výčet povolených tagů
$allowtags = "";

/* Ulozeni informace z Color do spravneho tvaru pro ulozeni do Databaze */
$i = 1;
while($i <= $_POST['clothes_style_size_num']){
	$clothes_style_size_data = $_POST['clothes_style_size_data'];
	$clothes_style_size = $clothes_style_size_data[$i.'_size'];
	$clothes_style_size_id = $clothes_style_size_data[$i.'_size_id'];
	if ($clothes_style_size == 1){$clothes_style_sizes .= $clothes_style_size_id."#";}
	$i++;
}

if ($_GET['action'] == "clothes_add_style"){
	mysql_query("INSERT INTO $db_shop_clothes_style VALUES('','".(float)$_GET['pid']."','".(float)$_POST['clothes_style_color_id']."','$clothes_style_sizes','','','".(float)$_POST['clothes_style_show']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$style_id_add = mysql_insert_id();
	/* Potvrdime odstraneni parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_ADDED.$_GET['id']."<br>";
}
if ($_GET['action'] == "clothes_edit_style"){
	mysql_query("UPDATE $db_shop_clothes_style SET  shop_clothes_style_color_id=".(float)$_POST['clothes_style_color_id'].", shop_clothes_style_sizes='$clothes_style_sizes', shop_clothes_style_show=".(float)$_POST['clothes_style_show']." WHERE shop_clothes_style_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($_FILES['clothes_style_picture_front']['name'] == "" && $_FILES['clothes_style_picture_back']['name'] == "") {$_GET['action'] = "clothes_add_style";}
	/* Potvrdime odstraneni parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_EDITED.$_GET['id']."<br>";
}
if ($_GET['action'] == "clothes_del_style"){
	mysql_query("DELETE FROM $db_shop_clothes_style WHERE shop_clothes_style_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action']= "clothes_add_style";
	/* Potvrdime odstraneni parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_DELETED.$_GET['id']."<br>";
}
if ($_FILES['clothes_style_picture_front']['name'] != "" || $_FILES['clothes_style_picture_back']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// ziskam extenzi souboru
   	$extenze_f = strtok($_FILES['clothes_style_picture_front']['name'] ,".");
   	$extenze_f = strtok(".");
	$extenze_b = strtok($_FILES['clothes_style_picture_back']['name'] ,".");
   	$extenze_b = strtok(".");
	// Ulozi jmeno obrazku jako
	if ($_GET['action'] == "clothes_add_style"){$style_id = $style_id_add; $sid = $style_id_add; } else {$style_id = (float)$_GET['id']; $sid = (float)$_GET['id'];}
	$picture_f = Zerofill($style_id,1000)."-".Zerofill($_POST['clothes_style_color_id'],1000)."_f.".$extenze_f;
	$picture_b = Zerofill($style_id,1000)."-".Zerofill($_POST['clothes_style_color_id'],1000)."_b.".$extenze_b;
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file_f = $_FILES['clothes_style_picture_front']['tmp_name'];
	$source_file_b = $_FILES['clothes_style_picture_back']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file_f = $ftp_path_shop_clothes_style.$picture_f;
	$destination_file_b = $ftp_path_shop_clothes_style.$picture_b;
	if ($_FILES['clothes_style_picture_front']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_f, $source_file_f, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_f.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_style SET shop_clothes_style_picture_front='".mysql_real_escape_string($picture_f)."' WHERE shop_clothes_style_id=".(float)$sid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_style_picture_back']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_b, $source_file_b, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_b.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_style SET shop_clothes_style_picture_back='".mysql_real_escape_string($picture_b)."' WHERE shop_clothes_style_id=".(float)$sid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
	$_GET['action'] = "clothes_add_style";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&pid=".$_GET['pid']."&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
exit;
?>