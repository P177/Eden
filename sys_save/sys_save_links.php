<?php

/* Provereni opravneni */
if ($_GET['action'] == "links_add"){
	if (CheckPriv("groups_links_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "links_edit"){
	if (CheckPriv("groups_links_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "links_del"){
	if (CheckPriv("groups_links_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

/* DELETE */
if ($_GET['action'] == "links_del"){
	$res = mysql_query("DELETE FROM $db_links WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
}

// Výčet povolených tagů
$allowtags = "";
// Z obsahu proměnné body vyjmout nepovolené tagy
$links_description = $_POST['links_description'];
$links_name = strip_tags($_POST['links_name'],$allowtags);
$links_link = strip_tags($_POST['links_link'],$allowtags);
$links_link = StripInetService($links_link);

$res_links = mysql_query("SELECT links_picture, links_picture2 FROM $db_links WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_links = mysql_fetch_array($res_links);

// Nasteveni jmena pro fotku
if ($_FILES['links_picture']['name'] != ""){
	if (($links_img_1 = getimagesize($_FILES['links_picture']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($links_img_1[2] == 1){
			$extenze = ".gif";
		} elseif ($links_img_1[2] == 2){
			$extenze = ".jpg";
		} elseif ($links_img_1[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=wft");
			exit;
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=fni");
		exit;
	}

	// Ulozi jmeno obrazku jako
	$links_img1 = Cislo().$extenze;
} else {
	if ($_GET['action'] != "links_add"){

		$links_img1 = $ar_links['links_picture'];
	} else {
		$links_img1 = "";
	}
}
if ($_FILES['links_picture2']['name'] != ""){
	if (($links_img_2 = getimagesize($_FILES['links_picture2']['tmp_name'])) != false){
		/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
		if ($links_img_2[2] == 1){
			$extenze = ".gif";
		} elseif ($links_img_2[2] == 2){
			$extenze = ".jpg";
		} elseif ($links_img_2[2] == 3){
			$extenze = ".png";
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=wft");
			exit;
		}
	} else {
		header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=fni");
		exit;
	}

	// Ulozi jmeno obrazku jako
	$links_img2 = Cislo().$extenze;
} else {
	if ($_GET['action'] != "links_add"){
		$links_img2 = $ar_links['links_picture2'];
	} else {
		$links_img2 = "";
	}
}

if ($_GET['action'] == "links_edit"){
	$res = mysql_query("UPDATE $db_links SET
	links_name='".mysql_real_escape_string($links_name)."',
	links_link='".mysql_real_escape_string($links_link)."',
	links_description='".mysql_real_escape_string($links_description)."',
	links_main=".(integer)$_POST['links_main'].",
	links_gfx=".(integer)$_POST['links_gfx'].",
	links_category_id=".(integer)$_POST['links_category_id'].",
	links_list=".(integer)$_POST['links_list'].",
	links_publish=".(integer)$_POST['links_publish']." WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "links_add"){
	$res = mysql_query("INSERT INTO $db_links VALUES ('',
	'".mysql_real_escape_string($links_name)."',
	'".mysql_real_escape_string($links_link)."',
	'',
	'',
	'".mysql_real_escape_string($links_description)."',
	0,
	0,
	'".(integer)$_POST['links_main']."',
	'".(integer)$_POST['links_gfx']."',
	'".(integer)$_POST['links_category_id']."',
	'".(integer)$_POST['links_list']."',
	'".(integer)$_POST['links_publish']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$_GET['id'] = mysql_insert_id();
	$msg = "add_ok";
}
if ($_FILES['links_picture']['name'] != "" || $_FILES['links_picture2']['name'] != ""){
	// Prenaseni obrazku
	$conn_id = ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); // Spojeni s FTP serverem
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']); // Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP;die;} // Zjisteni stavu spojeni
	
	if ($_FILES['links_picture']['name'] != ""){
		/* Zjistime zda neni obrazek mensi, nez je povoleno */
		if (GetSetupImageInfo("link_1","width") != 0 && $links_img_1[0] < GetSetupImageInfo("link_1","width")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_small");
			exit;
		}
		if (GetSetupImageInfo("link_1","height") != 0 && $links_img_1[1] < GetSetupImageInfo("link_1","height")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_small");
			exit;
		}
		/* Zjistime zda neni obrazek vetsi, nez je povoleno */
		if (GetSetupImageInfo("link_1","width") != 0 && $links_img_1[0] > GetSetupImageInfo("link_1","width")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_big");
			exit;
		}
		if (GetSetupImageInfo("link_1","height") != 0 && $links_img_1[1] > GetSetupImageInfo("link_1","height")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_big");
			exit;
		}
		/* Zjistime zda neni soubor vetsi nez je povoleno */
		if ($_FILES['links_picture']['size'] > GetSetupImageInfo("link_1","filesize")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=ftb");
			exit;
		}
		$extenze1 = strtok($_FILES['links_picture']['name'] ,".");// ziskam extenzi souboru
		$extenze1 = strtok(".");
		$userfile_name1 = $links_img1;// generuji nazev souboru
		$new_name1 = $url_links.$userfile_name1;
		$source_file1 =  $_FILES['links_picture']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$destination_file1 = $ftp_path_links.$userfile_name1; // Vlozi nazev souboru a cestu do konkretniho adresare
		$upload1 = ftp_put($conn_id, $destination_file1, $source_file1, FTP_BINARY);
		if ($upload1){
			$res = mysql_query("UPDATE $db_links SET links_picture='".mysql_real_escape_string($links_img1)."' WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=feu");
			exit;
		}
	}
	if ($_FILES['links_picture2']['name'] != ""){
		/* Zjistime zda neni obrazek mensi, nez je povoleno */
		if (GetSetupImageInfo("link_2","width") != 0 && $links_img_2[0] < GetSetupImageInfo("link_2","width")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_small");
			exit;
		}
		if (GetSetupImageInfo("link_2","height") != 0 && $links_img_2[1] < GetSetupImageInfo("link_2","height")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_small");
			exit;
		}
		/* Zjistime zda neni obrazek vetsi, nez je povoleno */
		if (GetSetupImageInfo("link_2","width") != 0 && $links_img_2[0] > GetSetupImageInfo("link_2","width")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_");
			exit;
		}
		if (GetSetupImageInfo("link_2","height") != 0 && $links_img_2[1] > GetSetupImageInfo("link_2","height")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=img_too_big");
			exit;
		}
		/* Zjistime zda neni soubor vetsi nez je povoleno */
		if ($_FILES['links_picture']['size'] > GetSetupImageInfo("link_2","filesize")){
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=ftb");
			exit;
		}
		$extenze2 = strtok($_FILES['links_picture2']['name'] ,".");// ziskam extenzi souboru
		$extenze2 = strtok(".");
		$userfile_name2 = $links_img2;// generuji nazev souboru
		$new_name2 = $url_links.$userfile_name2;
		$source_file2 =  $_FILES['links_picture2']['tmp_name'];// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$destination_file2 = $ftp_path_links.$userfile_name2; // Vlozi nazev souboru a cestu do konkretniho adresare
		$upload2 = ftp_put($conn_id, $destination_file2, $source_file2, FTP_BINARY);
		if ($upload2){
			$res = mysql_query("UPDATE $db_links SET links_picture2='".mysql_real_escape_string($links_img2)."' WHERE links_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action=links_edit&action_link=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=feu");
			exit;
		}
	}
	ftp_close($conn_id);// Uzavreni komunikace se serverem
}
header ("Location: ".$eden_cfg['url_cms']."modul_links.php?action_link=open&action=open&id=".$_GET['id']."&id1=".$_GET['id1']."&id2=".$_GET['id2']."&id3=".$_GET['id3']."&id4=".$_GET['id4']."&id5=".$_GET['id5']."&id6=".$_GET['id6']."&id_link=".$_GET['id_link']."&project=".$_SESSION['project']."&msg=".$msg);
exit;