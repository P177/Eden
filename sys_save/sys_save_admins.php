<?php
$res_admin = mysql_query("SELECT * FROM $db_admin WHERE admin_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_admin = mysql_fetch_array($res_admin);
/* Provereni opravneni */
if ($_GET['action'] == "admins_add"){
	if (CheckPriv("groups_admin_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "admins_edit"){
	if (CheckPriv("groups_admin_del") <> 1){
		if (CheckPriv("groups_admin_edit") <> 1 && $ar_admin['admin_uname'] != $_SESSION['login']) {
			header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?project=".$_SESSION['project']."&msg=nep");
		}
	}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

// Spojeni s FTP serverem 
$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);

// Zjisteni stavu spojeni
if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP;die;}

if ($_FILES['userfile']['name'] != ""){
	//if ($_GET['action'] == "admins_edit"){@ftp_delete($conn_id, $ftp_path_admins.$ar_admin[admin_userimage]);} // Odstrani se z FTP serveru
	// ziskam extenzi souboru
	$extenze = strtok($_FILES['userfile']['name'] ,".");
	$extenze = strtok(".");
	// generuji nazev souboru
	$userfile_name = Cislo().".".$extenze;
	$new_name = $url_admins.$userfile_name;
	if ($_FILES['userfile']['size']==0){
		$msg = "nulovavelikost";
	}elseif ($_FILES['userfile']['size']>10000){
		$_GET['id'] = $_POST['id'];
		$msg = "vetsivkb";
	} else {
		// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$source_file =  $_FILES['userfile']['tmp_name'];
		// Vlozi nazev souboru a cestu do konkretniho adresare
		$destination_file = $ftp_path_admins.$userfile_name; // 
		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
		// Zjisteni stavu uploadu
		if (!$upload) { echo _ERROR_UPLOAD;}
	}
	$checkupload = 1;
}
if ($_FILES['userfile2']['name'] != ""){
	//if ($_GET['action'] == "admins_edit"){@ftp_delete($conn_id, $ftp_path_admins.$ar_admin[admin_userimage2]);} // Odstrani se z FTP serveru
	// ziskam extenzi souboru
	$extenze=strtok($_FILES['userfile2']['name'] ,".");
	$extenze=strtok(".");
	// generuji nazev souboru
	$userfile_name2 = Cislo().".".$extenze;
	$new_name = $url_admins.$userfile_name;
	if ($_FILES['userfile2']['size']==0){
		$msg = "nulovavelikost";
	}elseif ($_FILES['userfile2']['size']>100000){
		$msg = "vetsivkb";
	} else {
		// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$source_file =  $_FILES['userfile2']['tmp_name'];
		// Vlozi nazev souboru a cestu do konkretniho adresare
		$destination_file = $ftp_path_admins.$userfile_name2; // 
		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
		// Zjisteni stavu uploadu
		if (!$upload) { echo _ERROR_UPLOAD;}
		// Uzavreni komunikace se serverem 
		ftp_close($conn_id);
	}
	$checkupload2 = 1;
}

if (!isset($msg)){ // Pokud obrazek nesplnuje nejake parametry tak se nebude odesilat
	// Výčet povolených tagů
	$allowtags = "<a>"; 
	// Z obsahu proměnné body vyjmout nepovolené tagy
	$admin_name = PrepareForDB($_POST['admin_name'],1,"",1);
	$admin_firstname = PrepareForDB($_POST['admin_firstname'],1,"",1);
	$admin_nick = PrepareForDB($_POST['admin_nick'],1,"",1);
	$admin_gender = PrepareForDB($_POST['admin_gender'],1,"",1);
	$admin_email = PrepareForDB($_POST['admin_email'],1,"",1);
	$admin_userinfo = PrepareForDB($_POST['admin_userinfo'],1,"",1);
	$admin_title = PrepareForDB($_POST['admin_title'],1,"",1);
	$admin_lang = PrepareForDB($_POST['admin_lang'],1,"",1);
	
	$admin_contact_telefon = PrepareForDB($_POST['admin_contact_telefon'],1,"",1);
	$admin_contact_mobil = PrepareForDB($_POST['admin_contact_mobil'],1,"",1);
	$admin_contact_icq = PrepareForDB($_POST['admin_contact_icq'],1,"",1);
	$admin_contact_msn = PrepareForDB($_POST['admin_contact_msn'],1,"",1);
	$admin_contact_aol = PrepareForDB($_POST['admin_contact_aol'],1,"",1);
	$admin_contact_skype = PrepareForDB($_POST['admin_contact_skype'],1,"",1);
	$admin_contact_xfire = PrepareForDB($_POST['admin_contact_xfire'],1,"",1);
	$admin_contact_web = PrepareForDB($_POST['admin_contact_web'],1,"",1);
	$admin_contact_web2 = PrepareForDB($_POST['admin_contact_web2'],1,"",1);
	$admin_contact_web3 = PrepareForDB($_POST['admin_contact_web3'],1,"",1);
	$admin_contact_web4 = PrepareForDB($_POST['admin_contact_web4'],1,"",1);
	$admin_contact_city = PrepareForDB($_POST['admin_contact_city'],1,"",1);
	$admin_contact_companyname = PrepareForDB($_POST['admin_contact_companyname'],1,"",1);
	$admin_contact_address_1 = PrepareForDB($_POST['admin_contact_address_1'],1,"",1);
	$admin_contact_address_2 = PrepareForDB($_POST['admin_contact_address_2'],1,"",1);
	$admin_contact_postcode = PrepareForDB($_POST['admin_contact_postcode'],1,"",1);
	$admin_contact_birth_day = $_POST['year'].$_POST['month'].$_POST['day'];
	
	$admin_contact_shop_companyname = PrepareForDB($_POST['admin_contact_shop_companyname'],1,"",1);
	$admin_contact_shop_name = PrepareForDB($_POST['admin_contact_shop_name'],1,"",1);
	$admin_contact_shop_firstname = PrepareForDB($_POST['admin_contact_shop_firstname'],1,"",1);
	$admin_contact_shop_city = PrepareForDB($_POST['admin_contact_shop_city'],1,"",1);
	$admin_contact_shop_address_1 = PrepareForDB($_POST['admin_contact_shop_address_1'],1,"",1);
	$admin_contact_shop_address_2 = PrepareForDB($_POST['admin_contact_shop_address_2'],1,"",1);
	$admin_contact_shop_postcode = PrepareForDB($_POST['admin_contact_shop_postcode'],1,"",1);
	
	$admin_clan_tag = PrepareForDB($_POST['admin_clan_tag'],1,"",1);
	$admin_clan_name = PrepareForDB($_POST['admin_clan_name'],1,"",1);
	$admin_clan_www = PrepareForDB($_POST['admin_clan_www'],1,"",1);
	$admin_clan_irc = PrepareForDB($_POST['admin_clan_irc'],1,"",1);
	
	$admin_hw_cpu = PrepareForDB($_POST['admin_hw_cpu'],1,"<a>",1);
	$admin_hw_ram = PrepareForDB($_POST['admin_hw_ram'],1,"<a>",1);
	$admin_hw_mb = PrepareForDB($_POST['admin_hw_mb'],1,"<a>",1);
	$admin_hw_hdd = PrepareForDB($_POST['admin_hw_hdd'],1,"<a>",1);
	$admin_hw_cd = PrepareForDB($_POST['admin_hw_cd'],1,"<a>",1);
	$admin_hw_vga = PrepareForDB($_POST['admin_hw_vga'],1,"<a>",1);
	$admin_hw_soundcard = PrepareForDB($_POST['admin_hw_soundcard'],1,"<a>",1);
	$admin_hw_monitor = PrepareForDB($_POST['admin_hw_monitor'],1,"<a>",1);
	$admin_hw_mouse = PrepareForDB($_POST['admin_hw_mouse'],1,"<a>",1);
	$admin_hw_mousepad = PrepareForDB($_POST['admin_hw_mousepad'],1,"<a>",1);
	$admin_hw_headset = PrepareForDB($_POST['admin_hw_headset'],1,"<a>",1);
	$admin_hw_repro = PrepareForDB($_POST['admin_hw_repro'],1,"<a>",1);
	$admin_hw_key = PrepareForDB($_POST['admin_hw_key'],1,"<a>",1);
	$admin_hw_gamepad = PrepareForDB($_POST['admin_hw_gamepad'],1,"<a>",1);
	$admin_hw_os = PrepareForDB($_POST['admin_hw_os'],1,"<a>",1);
	$admin_hw_conection = PrepareForDB($_POST['admin_hw_conection'],1,"<a>",1);
	$admin_hw_brand_pc = PrepareForDB($_POST['admin_hw_brand_pc'],1,"<a>",1);
	
	$admin_game_mouse_sens = PrepareForDB($_POST['admin_game_mouse_sens'],1,"",1);
	$admin_game_fav_weapon = PrepareForDB($_POST['admin_game_fav_weapon'],1,"",1);
	$admin_game_fav_team = PrepareForDB($_POST['admin_game_fav_team'],1,"",1);
	$admin_game_fav_map = PrepareForDB($_POST['admin_game_fav_map'],1,"",1);
	
	$admin_poker_fav_variants = $_POST['admin_poker_fav_variants_1']."||".$_POST['admin_poker_fav_variants_2']."||".$_POST['admin_poker_fav_variants_3'];
	$admin_poker_fav_player = PrepareForDB($_POST['admin_poker_fav_player']);
	$admin_poker_fav_cardrooms = $_POST['admin_poker_fav_cardroom_1']."||".$_POST['admin_poker_fav_cardroom_2']."||".$_POST['admin_poker_fav_cardroom_3'];
	/* Odstraneni prebytecnych znaku */
	$admin_username = CleanAdminUsername($_POST['admin_username']);
	
	$admin_contact_postcode = strtoupper($admin_contact_postcode);
	
	if ($_POST['admin_password1'] == $_POST['admin_password2'] || ($_POST['admin_password1'] == "" && $_POST['admin_password2'] == "")){
		if ($_GET['action'] == "admins_edit"){
			if ($_POST['admin_password1'] == ""){$p = $ar_admin['admin_password'];} else {$p = MD5(MD5($_POST['admin_password1']).$ar_admin['admin_uname']);}
			if ($ar_admin['admin_userimage'] == ""){$image = "0000000001.gif";} else {$image = $ar_admin['admin_userimage'];} // Pokud jeste neni nastaven obrazek tak se nastavi
			if ($ar_admin['admin_userimage2'] == ""){$image2 = "0000000002.gif";} else {$image2 = $ar_admin['admin_userimage2'];}
			if ($admin_username == ""){$admin_username = $ar_admin['admin_uname'];}
			if ($admin_nick == ""){$admin_nick = $ar_admin['admin_nick'];}
			if ($checkupload == 1){$admin_userimage = $userfile_name;}elseif ($ar_admin['admin_userimage'] == ""){$admin_userimage = "0000000001.gif";} else {$admin_userimage = $image;} // Pokud byl obrazek nasatven zapise se jeho jmeno, jinak se pouzije jmeno z databaze
			if ($checkupload2 == 1){$admin_userimage2 = $userfile_name2;}elseif ($ar_admin['admin_userimage2'] == ""){$admin_userimage2 = "0000000002.gif";} else {$admin_userimage2 = $image2;}
			if ($admin_info_shop == ""){$admin_info_shop = $ar_admin['admin_info_shop'];}
			if ($_POST['admin_filter'] == ""){$admin_info_filter = "";} else {$admin_info_filter = implode (",", $_POST['admin_filter']);} // Do retezce oddelene carkami ulozime zvolene filtry
			
			mysql_query("UPDATE $db_admin SET admin_password='".mysql_real_escape_string($p)."', admin_firstname='$admin_firstname', admin_name='$admin_name', admin_gender='$admin_gender', admin_nick='$admin_nick', admin_email='$admin_email', admin_autologin=".(float)$_POST['admin_autologin'].", admin_userimage='".mysql_real_escape_string($admin_userimage)."', admin_userimage2='".mysql_real_escape_string($admin_userimage2)."', admin_userinfo='$admin_userinfo', admin_hits=".(float)$_POST['admin_hits'].", admin_lang='$admin_lang', admin_title='$admin_title', admin_agree_email=".(integer)$_POST['admin_agree_email']." WHERE admin_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_clan SET admin_clan_tag='$admin_clan_tag', admin_clan_name='$admin_clan_name', admin_clan_www='$admin_clan_www', admin_clan_irc='$admin_clan_irc', admin_clan_player_status='$admin_clan_player_status'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_contact SET admin_contact_telefon='$admin_contact_telefon', admin_contact_mobil='$admin_contact_mobil', admin_contact_icq='$admin_contact_icq', admin_contact_msn='$admin_contact_msn', admin_contact_aol='$admin_contact_aol', admin_contact_skype='$admin_contact_skype', admin_contact_xfire='$admin_contact_xfire', admin_contact_web='$admin_contact_web', admin_contact_web2='$admin_contact_web2', admin_contact_web3='$admin_contact_web3', admin_contact_web4='$admin_contact_web4', admin_contact_birth_day=".(float)$admin_contact_birth_day.", admin_contact_city='$admin_contact_city', admin_contact_companyname='$admin_contact_companyname', admin_contact_address_1='$admin_contact_address_1', admin_contact_address_2='$admin_contact_address_2', admin_contact_postcode='$admin_contact_postcode', admin_contact_country=".(float)$_POST['admin_contact_country']." WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_contact_shop SET admin_contact_shop_companyname='$admin_contact_shop_companyname', admin_contact_shop_firstname='$admin_contact_shop_firstname', admin_contact_shop_name='$admin_contact_shop_name', admin_contact_shop_city='$admin_contact_shop_city', admin_contact_shop_address_1='$admin_contact_shop_address_1', admin_contact_shop_address_2='$admin_contact_shop_address_2', admin_contact_shop_postcode='$admin_contact_shop_postcode', admin_contact_shop_country=".(float)$_POST['admin_contact_shop_country'].", admin_contact_shop_title='$admin_contact_shop_title', admin_contact_shop_use=".(float)$admin_contact_shop_use." WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_game SET admin_game_resolution='".mysql_real_escape_string($_POST['admin_game_resolution'])."', admin_game_mouse_sens='$admin_game_mouse_sens', admin_game_mouse_accel=".(float)$_POST['admin_game_mouse_accel'].", admin_game_mouse_invert=".(float)$_POST['admin_game_mouse_invert'].", admin_game_fav_weapon='$admin_game_fav_weapon', admin_game_fav_team='$admin_game_fav_team', admin_game_fav_map='$admin_game_fav_map'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_hw SET admin_hw_cpu='$admin_hw_cpu', admin_hw_ram='$admin_hw_ram', admin_hw_mb='$admin_hw_mb', admin_hw_hdd='$admin_hw_hdd', admin_hw_cd='$admin_hw_cd', admin_hw_vga='$admin_hw_vga', admin_hw_soundcard='$admin_hw_soundcard', admin_hw_monitor='$admin_hw_monitor', admin_hw_mouse='$admin_hw_mouse', admin_hw_mousepad='$admin_hw_mousepad', admin_hw_headset='$admin_hw_headset', admin_hw_repro='$admin_hw_repro', admin_hw_key='$admin_hw_key', admin_hw_gamepad='$admin_hw_gamepad', admin_hw_os='$admin_hw_os', admin_hw_conection='$admin_hw_conection', admin_hw_brand_pc='$admin_hw_brand_pc' WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_info SET admin_info_shop='$admin_info_shop', admin_info_filter='$admin_info_filter' WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			mysql_query("UPDATE $db_admin_poker SET admin_poker_fav_cardrooms='".mysql_real_escape_string($admin_poker_fav_cardrooms)."', admin_poker_fav_variants='".mysql_real_escape_string($admin_poker_fav_variants)."', admin_poker_fav_player='".mysql_real_escape_string($admin_poker_fav_player)."'  WHERE aid=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			
			$msg = "ae_ok";
			
			header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=edit_priv&from=groups&id=".$_POST['id']."&show_status=admin&project=".$_SESSION['project']."&msg=".$msg);
			exit;
		}
		if ($_GET['action'] == "admins_add"){
			$p = MD5(MD5($_POST['admin_password1']).$admin_username);
			$res = mysql_query("SELECT * FROM $db_admin WHERE admin_uname='".mysql_real_escape_string($admin_username)."' OR admin_nick='".mysql_real_escape_string($admin_nick)."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num = mysql_num_rows($res);
			if ($num > 0){ // Zjisteni jestli jmeno jiz existuje
				$_POST['confirm'] = "no";
				$msg = "replynames"; // Pokud ano odesle se chybove hlaseni
			}elseif ($admin_username == "" || $p == ""){//Zjisteni jestli bylo zadano uzivatelske jmeno a jmeno
				$_POST['confirm'] = "no";
				$msg = "noname"; // Pokud ne odesle se chybove hlaseni
			} else {
				$admin_reg_code = GeneratePass(15);
				if ($checkupload == 1){$admin_userimage = $userfile_name;} else {$admin_userimage = "0000000001.gif";} // Pokud byl obrazek nasatven zapise se jeho jmeno, jinak se pouzije jmeno z databaze
				if ($checkupload2 == 1){$admin_userimage2 = $userfile_name2;} else {$admin_userimage2 = "0000000002.gif";}
				//echo $admin_username." - ".$p;
				//exit;
				mysql_query("INSERT INTO $db_admin VALUES('','$admin_username','".mysql_real_escape_string($p)."','$admin_firstname','$admin_name','$admin_gender','$admin_nick','$admin_email','','user','','','','','','','".(float)$_POST['admin_autologin']."','','".mysql_real_escape_string($admin_userimage)."','".mysql_real_escape_string($admin_userimage2)."','$admin_userinfo','0','','".(float)$_POST['admin_hits']."','0','0','0','0','0','0','0',NOW(),'$admin_reg_code','1','$admin_lang','','$admin_title','','','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_id = mysql_fetch_array($res_id);
				$adm_id = $ar_id[0];
				mysql_query("INSERT INTO $db_admin_clan VALUES('".(float)$adm_id."','$admin_clan_tag', '$admin_clan_name', '$admin_clan_www', '$admin_clan_irc', '$admin_clan_player_status')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_contact VALUES('".(float)$adm_id."', '$admin_contact_telefon', '$admin_contact_mobil', '$admin_contact_icq', '$admin_contact_msn', '$admin_contact_aol', '$admin_contact_skype', '$admin_contact_xfire', '$admin_contact_web', '$admin_contact_web2', '$admin_contact_web3', '$admin_contact_web4', '".(float)$admin_contact_birth_day."', '$admin_contact_city', '$admin_contact_companyname', '$admin_contact_address_1', '$admin_contact_address_2', '$admin_contact_postcode', '".(float)$_POST['admin_contact_country']."', '')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_contact_shop VALUES('".(float)$adm_id."', '$admin_contact_shop_companyname', '$admin_contact_shop_firstname', '$admin_contact_shop_name', '$admin_contact_shop_city', '$admin_contact_shop_address_1', '$admin_contact_shop_address_2', '$admin_contact_shop_postcode', '".(float)$_POST['admin_contact_shop_country']."','$admin_contact_shop_title', '')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_game VALUES('".(float)$adm_id."', '".mysql_real_escape_string($_POST['admin_game_resolution'])."', '$admin_game_mouse_sens', '".(float)$_POST['admin_game_mouse_accel']."', '".(float)$_POST['admin_game_mouse_invert']."', '$admin_game_fav_weapon', '$admin_game_fav_team', '$admin_game_fav_map')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_hw VALUES('".(float)$adm_id."', '$admin_hw_cpu', '$admin_hw_ram', '$admin_hw_mb', '$admin_hw_hdd', '$admin_hw_cd', '$admin_hw_vga', '$admin_hw_soundcard', '$admin_hw_monitor', '$admin_hw_mouse', '$admin_hw_mousepad', '$admin_hw_headset', '$admin_hw_repro', '$admin_hw_key', '$admin_hw_gamepad', '$admin_hw_os', '$admin_hw_conection', '$admin_hw_brand_pc')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_info VALUES('".(float)$adm_id."', '', '', '', '', '', '', '', '')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				mysql_query("INSERT INTO $db_admin_poker VALUES('".(float)$adm_id."', '".mysql_real_escape_string($admin_poker_fav_cardrooms)."', '".mysql_real_escape_string($admin_poker_fav_variants)."', '".mysql_real_escape_string($admin_poker_fav_player)."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				
				$msg = "aa_ok";
				
				header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?action=edit_priv&from=groups&id=".$adm_id."&show_status=admin&project=".$_SESSION['project']."&msg=".$msg);
				exit;
			}
		}
	} else {
		$msg = "spatnehesla"; 
	}
}
header ("Location: ".$eden_cfg['url_cms']."sys_admin.php?project=".$_SESSION['project']."&msg=".$msg);
exit;