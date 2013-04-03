<?php


if ($_GET['lang'] == ""){$lang = "cz";} else {$lang = $_GET['lang'];}
//Nastaveni nenacitani spatneho jazyka z functions.phps
$eden_editor_add_include_lang = "true";
include_once "./functions.php";
require_once (dirname(__FILE__)."/lang/lang-".$lang.".php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
<title><?php echo _SHOP_SHOW_CL;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
  html, body, button, div, input, select, fieldset, td { font-family: MS Shell Dlg; font-size: 8pt;}
  img, div { behavior: url(./css/iepngfix.htc); }
</style>
</head>
<body><?php
if ($_GET['project'] == "") {
	echo _ERRORPRISTUP;
} else {
	include (dirname(__FILE__)."/cfg/db.".$_GET['project'].".inc.php");
   //	echo $_GET['code'].'<br><br>';
	$code = explode("-",$_GET['code']);
	/*
	$num = count($code);
	for($i=0;$i<$num;$i++){
		echo $code[$i]."<br>";
	}
	*/
	
	$res_product = mysql_query("SELECT shop_product_name FROM $db_shop_product WHERE shop_product_id=".(float)$_GET['prod_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_product = mysql_fetch_array($res_product);
	
	$res_style = mysql_query("SELECT shop_clothes_style_parent_id, shop_clothes_style_picture_front, shop_clothes_style_picture_back FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".(float)$code[1]." AND shop_clothes_style_color_id=".substr((float)$code[2], 1)." ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_style = mysql_fetch_array($res_style);
	
	$res_style_parents = mysql_query("SELECT shop_clothes_style_parents_title FROM $db_shop_clothes_style_parents WHERE shop_clothes_style_parents_id=".$ar_style['shop_clothes_style_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_style_parents = mysql_fetch_array($res_style_parents);
	
	$res_design = mysql_query("SELECT shop_clothes_design_img_3, shop_clothes_design_img_4 FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".(float)$code[0]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_design = mysql_fetch_array($res_design);
	
	$res_color = mysql_query("SELECT shop_clothes_colors_title, shop_clothes_colors_producer FROM $db_shop_clothes_colors WHERE shop_clothes_colors_id=".substr((float)$code[2], 1)) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_color = mysql_fetch_array($res_color);
	
}
echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
<tr>
	<td rowspan="11" width="300"><?php 
	echo "<div id=\"big\" name=\"big\" style=\"width:300px; min-height:400px; text-align:right; border: 1px solid #000000; background: #000000 url(".$url_shop_clothes_style.$ar_style['shop_clothes_style_picture_front'].") repeat-y top right;\">\n";
	echo '<img src="'.$url_shop_clothes_design.$ar_design['shop_clothes_design_img_3'].'"  width="300" height="400" alt="'.$clothes_design_title.'">';
	echo "</div>";?></td>
	<td width="100" height="25" valign="top" align="left"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_NAME;?></strong></td>
	<td width="200" height="25" valign="top" align="left"><strong style="color:#ff0000;"><?php echo $ar_product['shop_product_name'];?></strong></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_ID;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $_GET['prod_id'];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_CODE;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $_GET['code'];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_DESIGN_ID;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo (float)$code[0];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_STYLE_ID;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo (float)$code[1];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_STYLE_NAME;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $ar_style_parents['shop_clothes_style_parents_title'];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_COLOR_ID;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo substr((float)$code[2], 1);?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_COLOR_NAME;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $ar_color['shop_clothes_colors_title'];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_COLOR_PRODUCER;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $ar_color['shop_clothes_colors_producer'];?></td>
</tr>
<tr>
	<td width="100" height="25" valign="top"><strong><?php echo _SHOP_SHOW_CL_PRODUCT_SIZE;?></strong></td>
	<td width="200" height="25" valign="top"><?php echo $code[3];?></td>
</tr>
<tr>
	<td width="100">&nbsp;</td>
	<td width="200">&nbsp;</td>
</tr>
</table>
</body>
</html>