<?php
/* Výčet povolených tagů */
$allowtags = "";
$clothes_style_parents_title = strip_tags($_POST['clothes_style_parents_title'],$allowtags);
$clothes_style_parents_description = strip_tags($_POST['clothes_style_parents_description'],$allowtags);
$clothes_style_parents_weight = strip_tags($_POST['clothes_style_weight'],$allowtags);
$clothes_style_parents_colors = "";
$clothes_style_parents_genders = "";
$clothes_style_parents_sizes = "";

/* Ulozeni informace z Size do spravneho tvaru pro ulozeni do Databaze */
$i = 1;
while($i <= $_POST['clothes_style_parents_size_num']){
	$clothes_style_parents_size_data = $_POST['clothes_style_parents_size_data'];
	$clothes_style_parents_size = $clothes_style_parents_size_data[$i.'_size'];
	$clothes_style_parents_size_id = $clothes_style_parents_size_data[$i.'_size_id'];
	if ($clothes_style_parents_size == 1){$clothes_style_parents_sizes .= $clothes_style_parents_size_id."#";}
	$i++;
}

/* Ulozeni informace z Color do spravneho tvaru pro ulozeni do Databaze */
$i = 1;
while($i <= $_POST['clothes_style_parents_color_num']){
	$clothes_style_parents_color_data = $_POST['clothes_style_parents_color_data'];
	$clothes_style_parents_color = $clothes_style_parents_color_data[$i.'_color'];
	$clothes_style_parents_color_id = $clothes_style_parents_color_data[$i.'_color_id'];
	if ($clothes_style_parents_color == 1){$clothes_style_parents_colors .= $clothes_style_parents_color_id."#";}
	$i++;
}

if ($_GET['action'] == "clothes_add_style_parents"){
	mysql_query("INSERT INTO $db_shop_clothes_style_parents VALUES(
	'',
	'$clothes_style_parents_colors',
	'".(integer)$_POST['clothes_style_parents_gender']."',
	'$clothes_style_parents_sizes',
	'".mysql_real_escape_string($clothes_style_parents_title)."',
	'".mysql_real_escape_string($clothes_style_parents_description)."',
	'".mysql_real_escape_string($clothes_style_parents_weight)."',
	'',
	'".(float)$_POST['clothes_style_extrapay']."',
	'".(integer)$_POST['clothes_style_parents_discount_cat_seller_1']."',
	'".(integer)$_POST['clothes_style_parents_discount_cat_seller_2']."',
	'".(integer)$_POST['clothes_style_parents_discount_cat_cust_1']."',
	'".(integer)$_POST['clothes_style_parents_discount_cat_cust_1']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$style_id_add = mysql_insert_id();
	/* Potvrdime pridani parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_PARENTS_ADDED.$clothes_style_parents_title."<br>";
}
if ($_GET['action'] == "clothes_edit_style_parents"){
	mysql_query("UPDATE $db_shop_clothes_style_parents SET 
	shop_clothes_style_parents_colors='$clothes_style_parents_colors', 
	shop_clothes_style_parents_genders='$clothes_style_parents_genders', 
	shop_clothes_style_parents_sizes='$clothes_style_parents_sizes', 
	shop_clothes_style_parents_title='".mysql_real_escape_string($clothes_style_parents_title)."', 
	shop_clothes_style_parents_description='".mysql_real_escape_string($clothes_style_parents_description)."', 
	shop_clothes_style_parents_weight='".mysql_real_escape_string($clothes_style_parents_weight)."', 
	shop_clothes_style_parents_extrapay=".(float)$_POST['clothes_style_extrapay'].", 
	shop_clothes_style_parents_discount_cat_seller_1=".(integer)$_POST['clothes_style_parents_discount_cat_seller_1'].", 
	shop_clothes_style_parents_discount_cat_seller_2=".(integer)$_POST['clothes_style_parents_discount_cat_seller_2'].", 
	shop_clothes_style_parents_discount_cat_cust_1=".(integer)$_POST['clothes_style_parents_discount_cat_cust_1'].", 
	shop_clothes_style_parents_discount_cat_cust_2=".(integer)$_POST['clothes_style_parents_discount_cat_cust_2']." 
	WHERE shop_clothes_style_parents_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* Potvrdime editaci parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_PARENTS_EDITED.$clothes_style_parents_title."<br>";
}
if ($_GET['action'] == "clothes_del_style_parents"){
	mysql_query("DELETE FROM $db_shop_clothes_style_parents WHERE shop_clothes_style_parents_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action']= "clothes_add_style_parents";
	/* Potvrdime odstraneni parent stylu */
	$sys_save_message = _SHOP_CL_SAVE_STYLE_PARENTS_DELETED.$_GET['id']."<br>";
}
if ($_FILES['clothes_style_parents_picture']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// ziskam extenzi souboru
   	$extenze = strtok($_FILES['clothes_style_parents_picture']['name'] ,".");
   	$extenze = strtok(".");
	// Ulozi jmeno obrazku jako
	if ($_GET['action'] == "clothes_add_style_parents"){$style_id = $style_id_add; $sid = $style_id_add; } else {$style_id = (float)$_GET['id']; $sid = (float)$_GET['id'];}
	$picture = Zerofill($style_id,1000000000).".".$extenze;
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file = $_FILES['clothes_style_parents_picture']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file = $ftp_path_shop_clothes_style_parent.$picture;
	if ($_FILES['clothes_style_parents_picture']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_style_parents SET shop_clothes_style_parents_picture='".mysql_real_escape_string($picture)."' WHERE shop_clothes_style_parents_id=".(float)$sid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
	$_GET['action'] = "clothes_add_style_parents";
}
header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=".$_GET['action']."&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
exit;