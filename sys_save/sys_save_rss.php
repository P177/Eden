<?php
/* Prevedeme znak copyrightu na znak akceptovatelny v XML */
$_POST['rss_copyright'] = str_ireplace("(c)", "&#xA9;", $_POST['rss_copyright']);

if ($_GET['action'] == "rss_add"){
	mysql_query("INSERT INTO $db_rss VALUES (
	'',
	'".mysql_real_escape_string($_POST['rss_webmaster'])."',
	'".mysql_real_escape_string($_POST['rss_managingeditor'])."',
	'".(integer)$_POST['rss_number']."',
	'".mysql_real_escape_string($_POST['rss_copyright'])."',
	'".mysql_real_escape_string($_POST['rss_title'])."',
	'".mysql_real_escape_string($_POST['rss_link'])."',
	'".mysql_real_escape_string($_POST['rss_description'])."',
	'".(integer)$_POST['rss_allow']."',
	'".(integer)$_POST['rss_lang']."',
	'',
	'".mysql_real_escape_string($_POST['rss_image_title'])."',
	'',
	'',
	'".mysql_real_escape_string($_POST['rss_image_link'])."',
	'".mysql_real_escape_string($_POST['rss_image_description'])."',
	'".(integer)$_POST['rss_ttl']."',
	'".mysql_real_escape_string($_POST['rss_category'])."',
	'".mysql_real_escape_string($_POST['rss_category_domain'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	$rss_channel_id = mysql_insert_id();
}

if ($_GET['action'] == "rss_edit"){
	mysql_query("UPDATE $db_rss SET
	rss_webmaster='".mysql_real_escape_string($_POST['rss_webmaster'])."',
	rss_managingeditor='".mysql_real_escape_string($_POST['rss_managingeditor'])."',
	rss_number=".(integer)$_POST['rss_number'].",
	rss_copyright='".mysql_real_escape_string($_POST['rss_copyright'])."',
	rss_title='".mysql_real_escape_string($_POST['rss_title'])."',
	rss_link='".mysql_real_escape_string($_POST['rss_link'])."',
	rss_description='".mysql_real_escape_string($_POST['rss_description'])."',
	rss_allow=".(integer)$_POST['rss_allow'].",
	rss_lang=".(integer)$_POST['rss_lang'].",
	rss_image_title='".mysql_real_escape_string($_POST['rss_image_title'])."',
	rss_image_link='".mysql_real_escape_string($_POST['rss_image_link'])."',
	rss_image_description='".mysql_real_escape_string($_POST['rss_image_description'])."',
	rss_ttl=".(integer)$_POST['rss_ttl'].",
	rss_category='".mysql_real_escape_string($_POST['rss_category'])."',
	rss_category_domain='".mysql_real_escape_string($_POST['rss_category_domain'])."'
	WHERE rss_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
/* Zjistime si cislo kanalu */
if ($_GET['action'] == "rss_add"){$rss_ch_id = $rss_channel_id;}else{$rss_ch_id = $_GET['id'];}

/* Nahrani obrazku Avatara */
if ($_FILES['rss_channel_img']['name'] != ""){
	/* Spojeni s FTP serverem */
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)){
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=no_ftp");
		exit;
	}

	if (($rss_channel_img = getimagesize($_FILES['rss_channel_img']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($rss_channel_img[2] == 1){
			$extenze = ".gif";
		} elseif ($rss_channel_img[2] == 2){
			$extenze = ".jpg";
		} elseif ($rss_channel_img[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=wft");
			exit;
		}
		/* Zjistime zda neni obrazek sirsi ci uzsi, nez je povoleno */
		if ($rss_channel_img[0] != GetSetupImageInfo("rss","width")){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=img_too_big");
			exit;
		/* Zjistime zda neni obrazek mensi ci vyssi, nez je povoleno */
		} elseif ($rss_channel_img[1] != GetSetupImageInfo("rss","height")){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=img_too_small");
			exit;
		/* Zjistime zda neni soubor vetsi nez je povoleno */
		} elseif ($_FILES['rss_channel_img']['size'] > GetSetupImageInfo("rss","filesize")){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=ftb");
			exit;
		} else {
			/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
			$source_file = $_FILES['rss_channel_img']['tmp_name'];
			$userfile_name = Cislo().strtolower($extenze);
			/* Vlozi nazev souboru a cestu do konkretniho adresare */
			$destination_file = $ftp_path_rss.$userfile_name;
			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

			/* Zjisteni stavu uploadu */
			if (!$upload){
				header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=ue");
				exit;
			} else {
				/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
				unset($source_file);
				unset($destination_file);
				unset($extenze);
				unset($rss_channel_img);
				unset($_FILES['rss_itunes_img']);
				$checkupload = 1;
			}
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=fni");
		exit;
	}
	
	/* Nahrani nazvu obrazku do databaze */
	mysql_query("UPDATE $db_rss SET
	rss_image='".mysql_real_escape_string($userfile_name)."',
	rss_image_width=".(integer)$rss_channel_img[0].",
	rss_image_height=".(integer)$rss_channel_img[1]."
	WHERE rss_id=".$rss_ch_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}
/* DELETE RSS */
if ($_GET['action'] == "rss_del"){
	$res = mysql_query("SELECT rss_image FROM $db_rss WHERE rss_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	
	/* Spojeni s FTP serverem */
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)){
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=rss_edit&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=no_ftp");
		exit;
	}
	if (ftp_delete($conn_id, $ftp_path_rss.$ar['rss_image'])) {
		/* vsechno v pohode */
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=showmain&project=".$_SESSION['project']."&msg=img_del_er");
		exit;
	}
	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
	
	$res_del = mysql_query("DELETE FROM $db_rss WHERE rss_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res_del){
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=showmain&project=".$_SESSION['project']."&msg=rss_del_ok");
		exit;
	}else {
		header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=showmain&project=".$_SESSION['project']."&msg=rss_del_er");
		exit;
	}
}
header ("Location: ".$eden_cfg['url_cms']."modul_rss.php?action=showmain&project=".$_SESSION['project']);
exit;