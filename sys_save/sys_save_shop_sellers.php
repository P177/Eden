<?php
$res_admin = mysql_query("SELECT a.admin_id, a.admin_uname, a.admin_firstname, a.admin_name, a.admin_nick, a.admin_email 
FROM $db_shop_sellers AS ss 
JOIN $db_admin AS a ON a.admin_id=ss.shop_seller_admin_id 
WHERE ss.shop_seller_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); 
$ar_admin = mysql_fetch_array($res_admin);
$res_setup = mysql_query("SELECT setup_reg_mailer FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_setup = mysql_fetch_array($res_setup);
$res_shop_setup = mysql_query("SELECT shop_setup_wholesale_email_from, shop_setup_wholesale_email_from_name, shop_setup_wholesale_email_subject_act, shop_setup_wholesale_email_act FROM $db_shop_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_shop_setup = mysql_fetch_array($res_shop_setup);

$res_upd = mysql_query("UPDATE $db_shop_sellers SET shop_seller_active=1 WHERE shop_seller_id=".(integer)$_GET['sid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$res_upd2 = mysql_query("UPDATE $db_admin SET admin_status='seller' WHERE admin_id=".(integer)$ar_admin['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
if (!$res_upd && !$res_upd2){
	header ("Location: ".$eden_cfg['url_cms']."modul_shop_sellers.php?action=sellers_waiting&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$_SESSION['project']."&msg=shop_seller_act_db_er");
	exit;
}
// Email informujici o novem prodejci odesleme spravci obchodu 
$mail = new PHPMailer();
$mail->From = $ar_shop_setup['shop_setup_wholesale_email_from'];
$mail->FromName = $ar_shop_setup['shop_setup_wholesale_email_from_name'];
$mail->AddAddress($ar_admin["admin_email"]);
$mail->CharSet = "utf-8";
//$mail->IsHTML(false);
$mail->Mailer = $ar_setup['setup_reg_mailer'];
$mail->Subject = $ar_shop_setup['shop_setup_wholesale_email_subject_act'];

/*
$mail->Body .= "<html><head title=\""._SHOP_SELLERS_ACC_ACT."\"/><body>";
$mail->Body .= "<p>".$ar_shop_setup['shop_setup_wholesale_email_act']."</p>";
$mail->Body .= "</body></html>";
*/
$mail->Body = "\n";
$mail->Body .= $ar_shop_setup['shop_setup_wholesale_email_act']."\n";
$mail->Body .= "\n";

if (!$mail->Send()){
	header ("Location: ".$eden_cfg['url_cms']."modul_shop_sellers.php?action=sellers_waiting&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$_SESSION['project']."&msg=shop_seller_act_er");
	exit;
} else {
	header ("Location: ".$eden_cfg['url_cms']."modul_shop_sellers.php?action=sellers_waiting&lang=".$_GET['lang']."&filter=".$_GET['filter']."&project=".$_SESSION['project']."&msg=shop_seller_act_ok");
	exit;
}