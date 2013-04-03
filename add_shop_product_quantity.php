<?php


if ($_GET['lang'] == ""){$lang = "cz";} else {$lang = $_GET['lang'];}
//Nastaveni nenacitani spatneho jazyka z functions.phps
$eden_editor_add_include_lang = "true";
include_once "./functions.php";
require_once (dirname(__FILE__)."/lang/lang-".$lang.".php");
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
if ($_GET['status'] != ""){$status = $_GET['status'];}elseif ($_POST['status'] != ""){$status = $_POST['status'];} else {$status = "";}

if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	include (dirname(__FILE__)."/cfg/db.".$project.".inc.php");
	if ($_POST['confirm'] == "true"){
		if (is_numeric($_POST['shop_prod_quantity'])){$shop_product_quantity = $_POST['shop_prod_quantity'];} else {$shop_product_quantity = 0;}
		if ($status == 1){
			mysql_query("UPDATE $db_shop_product SET shop_product_quantity = shop_product_quantity + ".(float)$shop_product_quantity." WHERE shop_product_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}elseif ($status == 2){
			mysql_query("UPDATE $db_shop_product SET shop_product_quantity = shop_product_quantity - ".(float)$shop_product_quantity." WHERE shop_product_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		$res = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(float)$_POST['una']."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		if ($status == 1){$shop_product_rem_id = "";}elseif ($status == 2){$shop_product_rem_id = $_POST['shop_prod_rem_id'];}
		mysql_query("INSERT INTO $db_shop_product_changes VALUES('','".(float)$_POST['id']."','".(float)$ar['admin_id']."', NOW(),'".(float)$shop_product_quantity."','','".(float)$status."','".(float)$shop_product_rem_id."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());?>
		<script>
			<!--
			window.opener.location.reload();
			window.close();
			//-->
		</script><?php
 	}?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<head>
	<title><?php if ($status == 1){echo _SHOP_PROD_CHANGE_QUANTITY_ADD;} else {echo _SHOP_PROD_CHANGE_QUANTITY_DEL;}?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
	  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt;};
	</style>
	</head>
	<body style="background: threedface; color: windowtext;">
	<div align="center"> <form name="form1" enctype="multipart/form-data" method="post"><?php
	 	if ($_POST['action'] == ""){$prod_id = $_GET['id'];} else {$prod_id = $_POST['id'];}
		$res = mysql_query("SELECT shop_product_quantity FROM $db_shop_product WHERE shop_product_id=".(float)$prod_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$shop_prod_quantity = str_replace("-", "", $_POST['shop_prod_quantity']);
		// Pridani produktu
		if ($status == 1 && is_numeric($_POST['shop_prod_quantity']) && $_POST['shop_prod_quantity'] == 0){
			if ($_POST['action'] == "enter"){echo _SHOP_PROD_ZERO.'<br><br>';}
			echo _CMN_ADD.': <input type="text" name="shop_prod_quantity" size="10" maxlength="11" align="right" /> '._CMN_PCS.'<br>';
			echo '<input type="hidden" name="action" value="enter" />';
		}elseif ($status == 1 && is_numeric($shop_prod_quantity) && $_POST['shop_prod_quantity'] != 0){
			echo _SHOP_PROD_CHECK_QUANTITY_1; echo ' - <strong>'.$shop_prod_quantity.'</strong> '._CMN_PCS.'<br><br>';
			echo _SHOP_PROD_CHECK_QUANTITY_2;
			echo '<input type="hidden" name="shop_prod_quantity" value="'.$shop_prod_quantity.'" />';
			echo '<input type="hidden" name="confirm" value="true" />';
		}elseif ($status == 1 && !is_numeric($shop_prod_quantity)){
			if ($_POST['action'] == "enter"){echo _SHOP_PROD_NO_QUANTITY.'<br><br>';}
			echo _CMN_ADD.': <input type="text" name="shop_prod_quantity" size="10" maxlength="11" align="right" /> '._CMN_PCS.'<br>';
			echo '<input type="hidden" name="action" value="enter" />';
		// Odebrani produktu
		}elseif ($status == 2 && is_numeric($shop_prod_quantity) && $_POST['shop_prod_rem_id'] != 0 && $_POST['shop_prod_quantity'] != 0 && ($shop_prod_quantity <= $ar['shop_product_quantity'])){
			echo _SHOP_PROD_CHECK_QUANTITY_3; echo ' - <strong>'.$shop_prod_quantity.'</strong> '._CMN_PCS.'<br><br>';
			echo _SHOP_PROD_REM_RESON;
			if ($_POST['shop_prod_rem_id'] == 1){echo '<strong>'._SHOP_PROD_REM_1.'</strong>';}
			if ($_POST['shop_prod_rem_id'] == 2){echo '<strong>'._SHOP_PROD_REM_2.'</strong>';}
			echo '<br><br>';
			echo _SHOP_PROD_CHECK_QUANTITY_2;
			echo '<input type="hidden" name="shop_prod_quantity" value="'.$shop_prod_quantity.'" />';
			echo '<input type="hidden" name="shop_prod_rem_id" value="'.$_POST['shop_prod_rem_id'].'" />';
			echo '<input type="hidden" name="confirm" value="true" />';
		}elseif ($status == 2){
			if ($_POST['action'] == "enter"){if (!is_numeric($shop_prod_quantity)){echo _SHOP_PROD_NO_QUANTITY.'<br><br>'; $prod_quantity_num = 0;	} else {$prod_quantity_num = 1 ;}}
			if ($_POST['action'] == "enter"){if ($shop_prod_quantity == 0 && $shop_prod_quantity != ""){echo _SHOP_PROD_ZERO.'<br><br>'; $prod_quantity_zero = 0;} else {$prod_quantity_zero = 1 ;}}
			if ($_POST['action'] == "enter"){if ($shop_prod_quantity > $ar['shop_product_quantity']){echo _SHOP_PROD_REM_3; $prod_quantity_more = 0;} else {$prod_quantity_more = 1;}}

			if ($_POST['action'] == "enter"){
				if ($_POST['shop_prod_rem_id'] == 0)	{
					echo _SHOP_PROD_REM_NO_ID.'<br><br>';
				} else {
					echo '<br><br>'._SHOP_PROD_REM_RESON;
					if ($_POST['shop_prod_rem_id'] == 1){echo '<strong>'._SHOP_PROD_REM_1.'</strong><br><br>';}
					if ($_POST['shop_prod_rem_id'] == 2){echo '<strong>'._SHOP_PROD_REM_2.'</strong><br><br>';}
					echo '<br>'; echo '<input type="hidden" name="shop_prod_rem_id" value="'.$_POST['shop_prod_rem_id'].'" />';
				}
			}

			if ($prod_quantity_num == 1 && $prod_quantity_zero == 1 && $prod_quantity_more == 1){
				echo _SHOP_PROD_REM.' <strong>'.$shop_prod_quantity.'</strong> '._CMN_PCS;
				echo '<input type="hidden" name="shop_prod_quantity" value="'.$shop_prod_quantity .'" />';
			} else {
				echo _SHOP_PROD_REM. '<input type="text" name="shop_prod_quantity" size="10" maxlength="11" align="right" /> '._CMN_PCS;
			}

			echo '<input type="hidden" name="action" value="enter" />';
		}
		if ($status == 2 && ($_POST['shop_prod_rem_id'] == 0 || $_POST['shop_prod_rem_id'] == "")){?><br><br>
			<select name="shop_prod_rem_id">
				<option name="shop_prod_rem_id" <?php  if ($_POST['shop_prod_rem_id'] == 0){echo 'selected';}?> value="0"><?php echo _SHOP_PROD_REM_0;?></option>
				<option name="shop_prod_rem_id" <?php  if ($_POST['shop_prod_rem_id'] == 1){echo 'selected';}?> value="1"><?php echo _SHOP_PROD_REM_1;?></option>
				<option name="shop_prod_rem_id" <?php  if ($_POST['shop_prod_rem_id'] == 2){echo 'selected';}?> value="2"><?php echo _SHOP_PROD_REM_2;?></option>
			</select><?php
	 	}?>
	<br><br>
		<input type="submit" name="send" value="<?php if ($status == 1){echo _CMN_ADD;}elseif ($status == 2){echo _CMN_REM;}?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" name="project" value="<?php echo $project;?>" />
		<input type="hidden" name="id" value="<?php if ($_POST['action'] == ""){echo $_GET['id'];} else {echo $_POST['id'];}?>" />
		<input type="hidden" name="una" value="<?php if ($_POST['action'] == ""){echo $_GET['una'];} else {echo $_POST['una'];}?>" />
		<input type="hidden" name="status" value="<?php echo $status;?>" />
		<input type="hidden" name="lang" value="<?php echo $lang;?>" />
		<button type=reset onClick="window.close();"><?php echo _CMN_CANCEL;?></button>
	</form><?php
}?>
</div>
</body>
</html>
