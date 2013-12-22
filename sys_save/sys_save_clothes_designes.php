<?php
// Výčet povolených tagů
$allowtags = "";
//error_reporting(E_ALL);
$shop_clothes_design_title = strip_tags($_POST['clothes_design_title'],$allowtags);
$shop_clothes_design_description_short = strip_tags($_POST['clothes_design_desc_short'],$allowtags);
$shop_clothes_design_description  = strip_tags($_POST['clothes_design_desc_long'],$allowtags);

$prod_availability = $_POST['prod_date_year'].'-'.$_POST['prod_date_month'].'-'.$_POST['prod_date_day'].' '."00:00:00";

/* Ulozeni informace z Color do spravneho tvaru pro ulozeni do Databaze */
$i = 1;
while($i <= $_POST['clothes_design_color_num']){
	$clothes_design_color_data = $_POST['clothes_design_color_data'];
	$clothes_design_style_id = $clothes_design_color_data[$i.'_style_id'];
	$clothes_design_color_id = $clothes_design_color_data[$i.'_color_id'];
	if ($clothes_design_color_data[$i.'_color'] == 1){$shop_clothes_design_colors .= $clothes_design_style_id."-".$clothes_design_color_id ."#";}
	$i++;
}
if ($_GET['action'] == "clothes_add_design"){
	if (is_numeric($_POST['prod_selling_price'])) {
		$prod_selling_price = str_replace("-", "", $_POST['prod_selling_price']);
		if ($prod_selling_price == 0){
			$shop_error[] = "prod_selling_price_0";
		}
	} else{
		$prod_selling_price = 0; $shop_error[] = "prod_selling_price_0";
	}

	mysql_query("INSERT INTO $db_shop_clothes_design VALUES(
	'',
	'".(float)$_POST['clothes_design_admin_id']."',
	'".mysql_real_escape_string($shop_clothes_design_title)."',
	'',
	'".(float)$prod_selling_price."',
	'".mysql_real_escape_string($shop_clothes_design_description_short)."',
	'".mysql_real_escape_string($shop_clothes_design_description)."',
	'1',
	'".(float)$_POST['clothes_design_status']."',
	'".(float)$_POST['prod_master_category']."',
	'".(float)$_POST['prod_sub_category1']."',
	'".(float)$_POST['prod_sub_category2']."',
	'".(float)$_POST['prod_sub_category3']."',
	'".(float)$_POST['prod_sub_category4']."',
	'".(float)$_POST['prod_sub_category5']."',
	NOW(),
	'$prod_availability',
	NOW(),
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'".(float)$_POST['prod_quantity']."',
	'".(float)$_POST['clothes_design_quantity_limited']."',
	'".(float)$_POST['prod_vat_class_id']."',
	'2',
	'$shop_clothes_design_colors',
	'".(integer)$_POST['clothes_design_show']."',
	'".(integer)$_POST['clothes_design_gender_men']."',
	'".(integer)$_POST['clothes_design_gender_women']."',
	'".(integer)$_POST['clothes_design_gender_kids']."',
	'".(integer)$_POST['clothes_design_for_tshirts']."',
	'".(integer)$_POST['clothes_design_for_bags']."'
	)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$design_id_add = mysql_insert_id();
	$_GET['id'] = $design_id_add;
}
if ($_GET['action'] == "clothes_edit_design"){
	$res_design = mysql_query("SELECT * FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_design = mysql_fetch_array($res_design);
	if (is_numeric($_POST['prod_selling_price'])) {
		$prod_selling_price = $_POST['prod_selling_price'];
		if ($prod_selling_price == 0){
			$shop_error[] = "prod_selling_price_0";
		}
	} else {
		$prod_selling_price = $ar_design['shop_clothes_design_selling_price'];
		if ($prod_selling_price == 0){
			$shop_error[] = "prod_selling_price_0";
		}
	}

	mysql_query("UPDATE $db_shop_clothes_design SET
	shop_clothes_design_author_id=".(float)$_POST['clothes_design_author_id'].",
	shop_clothes_design_title='".mysql_real_escape_string($shop_clothes_design_title)."',
	shop_clothes_design_selling_price=".(float)$prod_selling_price.",
	shop_clothes_design_description_short='".mysql_real_escape_string($shop_clothes_design_description_short)."',
	shop_clothes_design_description='".mysql_real_escape_string($shop_clothes_design_description)."',
	shop_clothes_design_status=".(float)$_POST['clothes_design_status'].",
	shop_clothes_design_master_category=".(float)$_POST['prod_master_category'].",
	shop_clothes_design_subcategory1=".(float)$_POST['prod_sub_category1'].",
	shop_clothes_design_subcategory2=".(float)$_POST['prod_sub_category2'].",
	shop_clothes_design_subcategory3=".(float)$_POST['prod_sub_category3'].",
	shop_clothes_design_subcategory4=".(float)$_POST['prod_sub_category4'].",
	shop_clothes_design_subcategory5=".(float)$_POST['prod_sub_category5'].",
	shop_clothes_design_date_available='$prod_availability',
	shop_clothes_design_date_edit=NOW(),
	shop_clothes_design_quantity=".(float)$_POST['prod_quantity'].",
	shop_clothes_design_quantity_limited=".(integer)$_POST['clothes_design_quantity_limited'].",
	shop_clothes_design_vat_class_id=".(float)$_POST['prod_vat_class_id'].",
	shop_clothes_design_styles_and_colors='$shop_clothes_design_colors',
	shop_clothes_design_show=".(integer)$_POST['clothes_design_show'].",
	shop_clothes_design_gender_men=".(integer)$_POST['clothes_design_gender_men'].",
	shop_clothes_design_gender_women=".(integer)$_POST['clothes_design_gender_women'].",
	shop_clothes_design_gender_kids=".(integer)$_POST['clothes_design_gender_kids'].",
	shop_clothes_design_for_tshirts=".(integer)$_POST['clothes_design_for_tshirts'].",
	shop_clothes_design_for_bags=".(integer)$_POST['clothes_design_for_bags']."  WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	if ($_FILES['clothes_style_picture_front']['name'] == "" && $_FILES['clothes_style_picture_back']['name'] == "") {$_GET['action'] = "clothes_add_style";}
}
if ($_GET['action'] == "clothes_del_design"){
	mysql_query("DELETE FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	unset($_GET['action']);
	$_GET['action'] = "clothes_add_design";
}
if ($_FILES['clothes_design_img_1']['name'] != "" || $_FILES['clothes_design_img_2']['name'] != "" || $_FILES['clothes_design_img_3']['name'] != "" || $_FILES['clothes_design_img_4']['name'] != "" || $_FILES['clothes_design_img_5']['name'] != "" || $_FILES['clothes_design_img_6']['name'] != "" || $_FILES['clothes_design_img_7']['name'] != "" || $_FILES['clothes_design_img_8']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	// ziskam extenzi souboru
   	$extenze_1 = strtok($_FILES['clothes_design_img_1']['name'] ,".");
   	$extenze_1 = strtok(".");
	$extenze_2 = strtok($_FILES['clothes_design_img_2']['name'] ,".");
   	$extenze_2 = strtok(".");
	$extenze_3 = strtok($_FILES['clothes_design_img_3']['name'] ,".");
   	$extenze_3 = strtok(".");
	$extenze_4 = strtok($_FILES['clothes_design_img_4']['name'] ,".");
   	$extenze_4 = strtok(".");
	$extenze_5 = strtok($_FILES['clothes_design_img_5']['name'] ,".");
   	$extenze_5 = strtok(".");
	$extenze_6 = strtok($_FILES['clothes_design_img_6']['name'] ,".");
   	$extenze_6 = strtok(".");
	$extenze_7 = strtok($_FILES['clothes_design_img_7']['name'] ,".");
   	$extenze_7 = strtok(".");
	$extenze_8 = strtok($_FILES['clothes_design_img_8']['name'] ,".");
   	$extenze_8 = strtok(".");
	// Ulozi jmeno obrazku jako
	if ($_GET['action'] == "clothes_add_design"){$design_id = $design_id_add; $did = $design_id_add; } else {$design_id = $_GET['id']; $did = $_GET['id'];}
	$picture_1 = Zerofill($design_id,10000)."_1.".$extenze_1;
	$picture_2 = Zerofill($design_id,10000)."_2.".$extenze_2;
	$picture_3 = Zerofill($design_id,10000)."_3.".$extenze_3;
	$picture_4 = Zerofill($design_id,10000)."_4.".$extenze_4;
	$picture_5 = Zerofill($design_id,10000)."_5.".$extenze_5;
	$picture_6 = Zerofill($design_id,10000)."_6.".$extenze_6;
	$picture_7 = Zerofill($design_id,10000)."_7.".$extenze_7;
	$picture_8 = Zerofill($design_id,10000)."_8.".$extenze_8;
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file_1 = $_FILES['clothes_design_img_1']['tmp_name'];
	$source_file_2 = $_FILES['clothes_design_img_2']['tmp_name'];
	$source_file_3 = $_FILES['clothes_design_img_3']['tmp_name'];
	$source_file_4 = $_FILES['clothes_design_img_4']['tmp_name'];
	$source_file_5 = $_FILES['clothes_design_img_5']['tmp_name'];
	$source_file_6 = $_FILES['clothes_design_img_6']['tmp_name'];
	$source_file_7 = $_FILES['clothes_design_img_7']['tmp_name'];
	$source_file_8 = $_FILES['clothes_design_img_8']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file_1 = $ftp_path_shop_clothes_design.$picture_1;
	$destination_file_2 = $ftp_path_shop_clothes_design.$picture_2;
	$destination_file_3 = $ftp_path_shop_clothes_design.$picture_3;
	$destination_file_4 = $ftp_path_shop_clothes_design.$picture_4;
	$destination_file_5 = $ftp_path_shop_clothes_design.$picture_5;
	$destination_file_6 = $ftp_path_shop_clothes_design.$picture_6;
	$destination_file_7 = $ftp_path_shop_clothes_design.$picture_7;
	$destination_file_8 = $ftp_path_shop_clothes_design.$picture_8;
	if ($_FILES['clothes_design_img_1']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_1, $source_file_1, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_1.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_1='".mysql_real_escape_string($picture_1)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_2']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_2, $source_file_2, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_2.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_2='".mysql_real_escape_string($picture_2)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_3']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_3, $source_file_3, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_3.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_3='".mysql_real_escape_string($picture_3)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_4']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_4, $source_file_4, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_4.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_4='".mysql_real_escape_string($picture_4)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_5']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_5, $source_file_5, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_5.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_5='".mysql_real_escape_string($picture_5)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_6']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_6, $source_file_6, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_6.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_6='".mysql_real_escape_string($picture_6)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_7']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_7, $source_file_7, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_7.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_7='".mysql_real_escape_string($picture_7)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	if ($_FILES['clothes_design_img_8']['name'] != ""){
		$upload = ftp_put($conn_id, $destination_file_8, $source_file_8, FTP_BINARY);
		if (!$upload) {
			$sys_save_message .= _ERROR_UPLOAD."<br>";
		} else {
			$sys_save_message .= _OK_FTP_FILE_1.' '.$destination_file_8.' '._OK_FTP_FILE_2.'<br>';
			mysql_query("UPDATE $db_shop_clothes_design SET shop_clothes_design_img_8='".mysql_real_escape_string($picture_8)."' WHERE shop_clothes_design_id=".(float)$did) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}

/* Smazani obrazku designu pokud je zatrhnuto jeho odstraneni */
if ($_POST['clothes_design_img_1_del'] == 1 || $_POST['clothes_design_img_2_del'] == 1 || $_POST['clothes_design_img_3_del'] == 1 || $_POST['clothes_design_img_4_del'] == 1 || $_POST['clothes_design_img_5_del'] == 1 || $_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}

	if ($_POST['clothes_design_img_1_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_1']); if ($_POST['clothes_design_img_2_del'] == 1 || $_POST['clothes_design_img_3_del'] == 1 || $_POST['clothes_design_img_4_del'] == 1 || $_POST['clothes_design_img_5_del'] == 1 || $_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete = "shop_clothes_design_img_1 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_1."<br>";}
	if ($_POST['clothes_design_img_2_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_2']); if ($_POST['clothes_design_img_3_del'] == 1 || $_POST['clothes_design_img_4_del'] == 1 || $_POST['clothes_design_img_5_del'] == 1 || $_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_2 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_2."<br>";}
	if ($_POST['clothes_design_img_3_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_3']); if ($_POST['clothes_design_img_4_del'] == 1 || $_POST['clothes_design_img_5_del'] == 1 || $_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_3 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_3."<br>";}
	if ($_POST['clothes_design_img_4_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_4']); if ($_POST['clothes_design_img_5_del'] == 1 || $_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_4 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_4."<br>";}
	if ($_POST['clothes_design_img_5_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_5']); if ($_POST['clothes_design_img_6_del'] == 1 || $_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_5 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_5."<br>";}
	if ($_POST['clothes_design_img_6_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_6']); if ($_POST['clothes_design_img_7_del'] == 1 || $_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_6 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_6."<br>";}
	if ($_POST['clothes_design_img_7_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_7']); if ($_POST['clothes_design_img_8_del'] == 1){$next = ", ";} else {$next = " ";} $clothes_design_img_delete .= "shop_clothes_design_img_7 = ''".$next; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_7."<br>";}
	if ($_POST['clothes_design_img_8_del'] == 1){ @ftp_delete($conn_id, $ftp_path_shop_clothes_design.$ar_design['shop_clothes_design_img_8']); $clothes_design_img_delete .= "shop_clothes_design_img_8 = '' "; $sys_save_message .= _SHOP_CL_DESIGN_IMG_DEL_8."<br>";}
	echo $clothes_design_img_delete;
	mysql_query("UPDATE $db_shop_clothes_design SET $clothes_design_img_delete WHERE shop_clothes_design_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}

/* Prepnuti na spravnou stranku, podle modu ulozeni */
if ($_POST['save'] == 1){
	header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=clothes_edit_design&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
	exit;
} else {
	header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=clothes_show_designs&id=".$_GET['id']."&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
	exit;
}
?>