<?php
// Spojeni s FTP serverem
$conn_id = ftp_connect($eden_cfg['ftp_server']);
// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);

// Zjisteni stavu spojeni
if ((!$conn_id) || (!$login_result)){
	$error =  urlencode(_ERROR_DB);
	header ("Location: ".$eden_cfg['url_cms']."sys_category.php?action=".$_GET['action']."&msg=".$error."&project=".$_SESSION['project']."");
	exit;
}
// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
$source_file = $_FILES['filename']['tmp_name'];
// Vlozi nazev souboru a cestu do konkretniho adresare
$destination_file = $ftp_path_category.$_FILES['filename']['name']; //
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
// Zjisteni stavu uploadu
if (!$upload){
      	$error = urlencode(_ERROR_UPLOAD);
  	} else {
      	$error = urlencode(_OK_FTP_FILE_1.$destination_file._OK_FTP_FILE_2);
  	}
// Uzavreni komunikace se serverem
ftp_close($conn_id);
header ("Location: ".$eden_cfg['url_cms']."sys_category.php?action=".$_GET['action']."&msg=".$error."&project=".$_SESSION['project']."");
exit;