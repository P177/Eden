<?php
// Nahrani obrazku, ktery bude zobrazen u clanku
if ($_FILES['clan_map_file']['name'] != "") {
	// Spojeni s FTP serverem
	$conn_id = ftp_connect($eden_cfg['ftp_server']);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) { echo _ERROR_FTP; die;}
	
	// Ulozi jmeno obrazku jako
	$picture = $_FILES['clan_map_file']['name'];
	
	// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
	$source_file = $_FILES['clan_map_file']['tmp_name'];
	// Vlozi nazev souboru a cestu do konkretniho adresare
	$destination_file = $ftp_path_clan_maps.$picture; //
	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
	// Zjisteni stavu uploadu
	if (!$upload) {
       	echo _ERROR_UPLOAD;
   	} else {
       	echo _FTPUPLOADOK1.$destination_file._FTPUPLOADOK2;
   	}
	// Uzavreni komunikace se serverem
	ftp_close($conn_id);
} else {
	$res_map = mysql_query("SELECT * FROM $db_clan_maps WHERE clan_map_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_map = mysql_fetch_array($res_map);
	$picture = $ar_map['clan_map_img'];
}
/* ADD */
if ($_GET['action'] == "clan_map_add"){
	// Zapise nazev obrazku do databaze
	$res = mysql_query("INSERT INTO $db_clan_maps VALUES('', '".(integer)$_POST['clan_map_game']."', '".mysql_real_escape_string($picture)."','".mysql_real_escape_string($_POST['clan_map_name'])."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$lid = $_POST['clan_map_game']; /* Posledne pouzita hra - pro lepsi prehlednost */
	if ($res){
		$msg = "add_ok";
	} else {
		$msg = "add_no";
	}
} 

/* EDIT */
if ($_GET['action'] == "clan_map_edit") {
	$res = mysql_query("UPDATE $db_clan_maps SET
	clan_map_game_id=".(integer)$_POST['clan_map_game'].",
	clan_map_img='".mysql_real_escape_string($picture)."',
	clan_map_name='".mysql_real_escape_string($_POST['clan_map_name'])."' WHERE clan_map_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$lid = $_POST['clan_map_game']; /* Posledne pouzita hra - pro lepsi prehlednost */
	if ($res){
		$msg = "edit_ok";
	} else {
		$msg = "edit_no";
	}
}

/* DEL */
if ($_GET['action'] == "clan_map_del"){
	$res = mysql_query("DELETE FROM $db_clan_maps WHERE clan_map_id=".(float)$_POST['id']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	if ($res){
		$msg = "del_ok";
	} else {
		$msg = "del_no";
	}
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_maps.php?project=".$_SESSION['project']."&lid=".$lid."&msg=".$msg);
exit;