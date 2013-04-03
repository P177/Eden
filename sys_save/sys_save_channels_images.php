<?php
// Spojeni s FTP serverem
$conn_id = ftp_connect($eden_cfg['ftp_server']);
// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);

// Zjisteni stavu spojeni
if ((!$conn_id) || (!$login_result)){
	header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=article_channel_upload_img&msg=no_ftp&project=".$_SESSION['project']."");
	exit;
}
// Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
$source_file = $_FILES['filename']['tmp_name'];
// Vlozi nazev souboru a cestu do konkretniho adresare
$destination_file = $ftp_path_article_channels.$_FILES['filename']['name']; //
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
// Zjisteni stavu uploadu
if (!$upload){
      	$msg = "ue";
  	} else {
      	$msg = "article_channel_upload_img_ok";
  	}
// Uzavreni komunikace se serverem
ftp_close($conn_id);
header ("Location: ".$eden_cfg['url_cms']."modul_articles_channel.php?action=article_channel_upload_img&msg=".$msg."&project=".$_SESSION['project']."");
exit;