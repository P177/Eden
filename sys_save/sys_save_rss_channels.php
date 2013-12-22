<?php
/* Prevedeme znak copyrightu na znak akceptovatelny v XML */
$_POST['podcast_channel_copyright'] = str_ireplace("(c)", "&#xA9;", $_POST['podcast_channel_copyright']);

if ($_GET['action'] == "add_rss_ch"){
	mysql_query("INSERT INTO $db_podcast_channel VALUES (
	'',
	'".mysql_real_escape_string($_POST['podcast_channel_title'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_link'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_link_new'])."',
	'".(float)$_POST['podcast_channel_lang']."',
	'".mysql_real_escape_string($_POST['podcast_channel_copyright'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_subtitle'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_author'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_summary'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_owner_name'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_owner_email'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_image'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_category'])."',
	'".mysql_real_escape_string($_POST['podcast_channel_keywords'])."',
	'".(integer)$_POST['podcast_channel_block']."',
	'".(integer)$_POST['podcast_channel_explicit']."',
	'".(integer)$_POST['podcast_channel_items_num']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$podcast_channel_id = mysql_insert_id();
}

if ($_GET['action'] == "edit_rss_ch"){
	mysql_query("UPDATE $db_podcast_channel SET
	podcast_channel_title='".mysql_real_escape_string($_POST['podcast_channel_title'])."',
	podcast_channel_link='".mysql_real_escape_string($_POST['podcast_channel_link'])."',
	podcast_channel_link_new='".mysql_real_escape_string($_POST['podcast_channel_link_new'])."',
	podcast_channel_lang=".(float)$_POST['podcast_channel_lang'].",
	podcast_channel_copyright='".mysql_real_escape_string($_POST['podcast_channel_copyright'])."',
	podcast_channel_subtitle='".mysql_real_escape_string($_POST['podcast_channel_subtitle'])."',
	podcast_channel_author='".mysql_real_escape_string($_POST['podcast_channel_author'])."',
	podcast_channel_summary='".mysql_real_escape_string($_POST['podcast_channel_summary'])."',
	podcast_channel_owner_name='".mysql_real_escape_string($_POST['podcast_channel_owner_name'])."',
	podcast_channel_owner_email='".mysql_real_escape_string($_POST['podcast_channel_owner_email'])."',
	podcast_channel_image='".mysql_real_escape_string($_POST['podcast_channel_image'])."',
	podcast_channel_category='".mysql_real_escape_string($_POST['podcast_channel_category'])."',
	podcast_channel_keywords='".mysql_real_escape_string($_POST['podcast_channel_keywords'])."',
	podcast_channel_block=".(integer)$_POST['podcast_channel_block'].",
	podcast_channel_explicit=".(integer)$_POST['podcast_channel_explicit'].",
	podcast_channel_items_num=".(integer)$_POST['podcast_channel_items_num']." WHERE podcast_channel_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}
/* Zjistime si cislo kanalu */
if ($_GET['action'] == "add_rss_ch"){$rss_ch_id = $podcast_channel_id;}else{$rss_ch_id = $_GET['id'];}

/* Nahrani obrazku Avatara */
if ($_FILES['podcast_channel_img']['name'] != ""){
	/* Spojeni s FTP serverem */
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)){
		header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=no_ftp");
		exit;
	}

	if (($rss_channel_img = getimagesize($_FILES['podcast_channel_img']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($rss_channel_img[2] == 2){
			$extenze = ".jpg";
		} elseif ($rss_channel_img[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=wft");
			exit;
		}
		/* Zjistime zda neni obrazek mensi, nez je povoleno */
		if ($rss_channel_img[0] < 600 /*width*/ || $rss_channel_img[1] < 600 /*height*/){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=img_too_small");
			exit;
		/* Zjistime zda neni obrazek vetsi, nez je povoleno */
		} elseif ($rss_channel_img[0] > 600 /*width*/ || $rss_channel_img[1] > 600 /*height*/){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=img_too_big");
			exit;
		/* Zjistime zda neni soubor vetsi nez je povoleno */
		} elseif ($_FILES['podcast_channel_img']['size'] > 150000){
			header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=ftb");
			exit;
		} else {
			/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
			$source_file = $_FILES['podcast_channel_img']['tmp_name'];
			$userfile_name = Cislo().strtolower($extenze);
			/* Vlozi nazev souboru a cestu do konkretniho adresare */
			$destination_file = $ftp_path_rss_itunes.$userfile_name;
			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

			/* Zjisteni stavu uploadu */
			if (!$upload){
				header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=ue");
				exit;
			} else {
				/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
				unset($source_file);
				unset($destination_file);
				unset($extenze);
				unset($rss_channel_img);
				unset($_FILES['podcast_channel_img']);
				$checkupload = 1;
			}
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=edit_rss_ch&project=".$_SESSION['project']."&id=".$rss_ch_id."&msg=fni");
		exit;
	}

	/* Nahrani nazvu obrazku do databaze */
	mysql_query("UPDATE $db_podcast_channel SET podcast_channel_image='".mysql_real_escape_string($userfile_name)."' WHERE podcast_channel_id=".$rss_ch_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}

header ("Location: ".$eden_cfg['url_cms']."modul_rss_itunes.php?action=showmain&project=".$_SESSION['project']);
exit;
?>