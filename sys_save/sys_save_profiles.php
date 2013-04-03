<?php
/* Provereni opravneni */
if ($_GET['action'] == "add_profile"){
	if (CheckPriv("groups_profile_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_profile"){
	if (CheckPriv("groups_profile_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_profile"){
	if (CheckPriv("groups_profile_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=nep");}
}

$res_setup = mysql_query("SELECT eden_setup_image_width, eden_setup_image_height, eden_setup_image_filesize FROM "._DB_SETUP_IMAGES." WHERE eden_setup_image_for = 'profile_1'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_setup = mysql_fetch_array($res_setup);

/* Priprava data */
$profile_birth = PrepareDateForSpiffyCalendar($_POST['profile_birth'],"date_only");

if ($_GET['action'] == "add_profile"){
	/* Pokud ma nekdo prava jen pro pridavani, musi to nekdo s pravy pro editaci povolit */
	if (CheckPriv("groups_profile_edit") <> 1){$dictionary_allow = 0;}else{$dictionary_allow = (integer)$_POST['dictionary_allow'];}
	mysql_query("INSERT INTO $db_profiles VALUES(
	'',
	'".(integer)$_POST['profile_game_id']."',
	'".(integer)$_POST['profile_country_id']."',
	'".(integer)$_POST['profile_article_id']."',
	'".mysql_real_escape_string($_POST['profile_firstname'])."',
	'".mysql_real_escape_string($_POST['profile_middlename'])."',
	'".mysql_real_escape_string($_POST['profile_surname'])."',
	'".mysql_real_escape_string($_POST['profile_nickname'])."',
	'".mysql_real_escape_string($profile_birth)."',
	'".mysql_real_escape_string($_POST['profile_info'])."',
	'".mysql_real_escape_string($_POST['profile_windfall'])."',
	'',
	'".(integer)$_POST['profile_allow']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
	$msg = "add_ok";
}
if ($_GET['action'] == "edit_profile"){
	mysql_query("UPDATE $db_profiles SET 
	profile_game_id=".(integer)$_POST['profile_game_id'].", 
	profile_country_id=".(integer)$_POST['profile_country_id'].", 
	profile_article_id=".(integer)$_POST['profile_article_id'].", 
	profile_firstname='".mysql_real_escape_string($_POST['profile_firstname'])."', 
	profile_middlename='".mysql_real_escape_string($_POST['profile_middlename'])."', 
	profile_surname='".mysql_real_escape_string($_POST['profile_surname'])."', 
	profile_nickname='".mysql_real_escape_string($_POST['profile_nickname'])."', 
	profile_birth='".mysql_real_escape_string($profile_birth)."', 
	profile_info='".mysql_real_escape_string($_POST['profile_info'])."', 
	profile_windfalls='".mysql_real_escape_string($_POST['profile_windfalls'])."', 
	profile_allow=".(integer)$_POST['profile_allow']." 
	WHERE profile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "del_profile"){
	mysql_query("DELETE FROM $db_profiles WHERE profile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "del_ok";
}

/* Nahrani obrazku Avatara */
if ($_FILES['profile_image_1']['name'] != ""){
	/* Spojeni s FTP serverem */
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	/* Zjisteni stavu spojeni */
	if ((!$conn_id) || (!$login_result)){
		header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=no_ftp");
		exit;
	}

	if (($profile_image_1 = getimagesize($_FILES['profile_image_1']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($profile_image_1[2] == 1){
			$extenze = ".gif";
		} elseif ($profile_image_1[2] == 2){
			$extenze = ".jpg";
		} elseif ($profile_image_1[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=wft");
			exit;
		}
		/* Zjistime zda neni obrazek mensi, nez je povoleno */
		if ($profile_image_1[0] < $ar_setup['eden_setup_image_width'] /*width*/ || $profile_image_1[1] < $ar_setup['eden_setup_image_height'] /*height*/){
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=ts");
			exit;
		/* Zjistime zda neni obrazek vetsi, nez je povoleno */
		} elseif ($profile_image_1[0] > $ar_setup['eden_setup_image_width'] /*width*/ || $profile_image_1[1] > $ar_setup['eden_setup_image_height'] /*height*/){
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=tb");
			exit;
		/* Zjistime zda neni soubor vetsi nez je povoleno */
		} elseif ($_FILES['profile_image_1']['size'] > $ar_setup['eden_setup_image_filesize']){
			header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=ftb");
			exit;
		} else {
			/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
			$source_file = $_FILES['profile_image_1']['tmp_name'];
			$userfile_name = $_GET['id'].strtolower($extenze);
			/* Vlozi nazev souboru a cestu do konkretniho adresare */
			$destination_file = $ftp_path_profiles.$userfile_name;
			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

			/* Zjisteni stavu uploadu */
			if (!$upload){
				header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=ue");
				exit;
			} else {
				/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
				unset($source_file);
				unset($destination_file);
				unset($extenze);
				unset($profile_image_1);
				unset($_FILES['profile_image_1']);
				$checkupload = 1;
			}
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=edit_profile&project=".$_SESSION['project']."&id=".$_GET['id']."&msg=fni");
		exit;
	}

	/* Nahrani nazvu obrazku do databaze */
	mysql_query("UPDATE $db_profiles SET profile_image_1='".mysql_real_escape_string($userfile_name)."' WHERE profile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	/* Uzavreni komunikace se serverem */
	ftp_close($conn_id);
}

header ("Location: ".$eden_cfg['url_cms']."modul_profile.php?action=add_profile&project=".$_SESSION['project']."&msg=".$msg."&letter=".$_GET['letter']);
exit;
?>