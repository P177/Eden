<?php
/* Nacteme design pro ktery chceme generovat produkty */
$res_design = mysql_query("SELECT * FROM $db_shop_clothes_design WHERE shop_clothes_design_id=".$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_design = mysql_fetch_array($res_design);

/* Do pole $design_list ulozime ID Parent Stylu a Jeho barvu v syntaxi: 1-6 */
$design_list = explode("#", $ar_design['shop_clothes_design_styles_and_colors']);
/* Odstranime posledni bunku z tohoto pole, protoze je prazdna */
array_pop ($design_list);
/* Pokud je pole $design_list prazdne musime z nej udelat pole abychom predesli chybove hlasce */
if (count($design_list) == 0){$design_list = array();}
/* Spocitame pocet zaznamu v poli $design_list */
$styles_num = count($design_list);
$i = 0;
$num_prod_add = 0;
$num_prod_edit = 0;
echo "ID - ".$_GET['id']."<br>";
echo "\$styles_num - ".$styles_num."<br>";
/* Iniciace promenny pro nastaveni spravneho poctu aktivnich stylu */
$style_parent_id_nonexist = "";
$style_parent_id_nonexist_list = array();
/* Zobrazime vsechny Parent styly uvedene pro dany design */
while($i < $styles_num){
	/* Do pole $style_parent_id ulozime ID Parent Stylu a Jeho barvu: 0 = ID a 1 = ID Barvy */
	$style_parent_id = explode("-", $design_list[$i]);
	$res_style_parent = mysql_query("SELECT * FROM $db_shop_clothes_style_parents WHERE shop_clothes_style_parents_id=".$style_parent_id[0]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

	/* Do promenne ulozime podminku pro zobrazeni vsech stylu tricek, ktere nechceme zobrazit */
	if ($i == 0){
		$style_parent_id_nonexist_list = array($style_parent_id[0]);
		$style_parent_id_nonexist = "AND shop_product_clothes_style_id != ".$style_parent_id[0]." ";
	}
	if (!in_array($style_parent_id[0],$style_parent_id_nonexist_list)){
		array_push($style_parent_id_nonexist_list, $style_parent_id[0]);
		$style_parent_id_nonexist .= "AND shop_product_clothes_style_id!= ".$style_parent_id[0]." ";
	}

	while ($ar_style_parent = mysql_fetch_array($res_style_parent)){
		$res_style = mysql_query("SELECT shop_clothes_style_sizes FROM $db_shop_clothes_style WHERE shop_clothes_style_parent_id=".$ar_style_parent['shop_clothes_style_parents_id']." AND shop_clothes_style_color_id=".$style_parent_id[1]." ") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		while ($ar_style = mysql_fetch_array($res_style)){
			$res_color = mysql_query("SELECT shop_clothes_colors_id, shop_clothes_colors_prefix FROM $db_shop_clothes_colors WHERE shop_clothes_colors_id=".$style_parent_id[1]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_color = mysql_fetch_array($res_color);
			/* Do pole $sizes_list ulozime velikosti pro dny Styl */
			$sizes_list = explode("#", $ar_style['shop_clothes_style_sizes']);
			/* Odstranime posledni bunku z tohoto pole, protoze je prazdna */
			array_pop ($sizes_list);
			/* Pokud je pole $sizes_list prazdne musime z nej udelat pole abychom predesli chybove hlasce */
			if (count($sizes_list) == 0){$sizes_list = array();}
			/* Spocitame pocet zaznamu v poli $design_list */
			$sizes_num = count($sizes_list);
			$y = 0;
			while ($y < $sizes_num){
				$res_sizes = mysql_query("SELECT shop_clothes_size_size FROM $db_shop_clothes_size WHERE shop_clothes_size_id=".$sizes_list[$y]) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_sizes = mysql_fetch_array($res_sizes)){
					$res_prod_cl = mysql_query("SELECT shop_product_clothes_product_id FROM $db_shop_product_clothes WHERE shop_product_clothes_design_id=".$ar_design['shop_clothes_design_id']." AND shop_product_clothes_style_id=".$ar_style_parent['shop_clothes_style_parents_id']." AND shop_product_clothes_color_id=".$ar_color['shop_clothes_colors_id']." AND shop_product_clothes_size='".$ar_sizes['shop_clothes_size_size']."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$ar_prod_cl = mysql_fetch_array($res_prod_cl);
					$num_prod_cl = mysql_num_rows($res_prod_cl);
					if ($num_prod_cl < 1){
						$shop_clothes_design_selling_price = $ar_design['shop_clothes_design_selling_price'] + $ar_style_parent['shop_clothes_style_parents_extrapay'];
						mysql_query("INSERT INTO $db_shop_product
						VALUES('',
						'".$ar_design['shop_clothes_design_title']."',
						'".$ar_design['shop_clothes_design_model']."',
						'',
						'',
						'".$shop_clothes_design_selling_price."',
						'',
						'1',
						'1',
						'',
						'".$ar_style_parent['shop_clothes_style_parents_discount_cat_seller_1']."',
						'".$ar_style_parent['shop_clothes_style_parents_discount_cat_cust_1']."',
						'".$ar_design['shop_clothes_design_description_short']."',
						'".$ar_design['shop_clothes_design_description']."',
						'1',
						'".$ar_style_parent['shop_clothes_style_parents_weight']."',
						'".$ar_design['shop_clothes_design_status']."',
						'".$ar_design['shop_clothes_design_master_category']."',
						'".$ar_design['shop_clothes_design_subcategory1']."',
						'".$ar_design['shop_clothes_design_subcategory2']."',
						'".$ar_design['shop_clothes_design_subcategory3']."',
						'".$ar_design['shop_clothes_design_subcategory4']."',
						'".$ar_design['shop_clothes_design_subcategory5']."',
						NOW(),
						NOW(),
						'".$ar_design['shop_clothes_design_date_available']."',
						'1',
						'1',
						'',
						'',
						'',
						'0',
						'',
						'',
						'".$ar_design['shop_clothes_design_vat_class_id']."',
						'',
						'',
						'',
						'".mysql_real_escape_string(Zerofill($ar_design['shop_clothes_design_id'],10000)."-".Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar_color['shop_clothes_colors_id'],100)."-".$ar_sizes['shop_clothes_size_size'])."',
						'".mysql_real_escape_string(Zerofill($ar_design['shop_clothes_design_id'],10000)."-".Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar_color['shop_clothes_colors_id'],100)."-".$ar_sizes['shop_clothes_size_size'])."',
						'12',
						'0',
						'0',
						'1',
						'1',
						'1')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$product_id_add = mysql_insert_id();
						mysql_query("INSERT INTO $db_shop_product_clothes
						VALUES('".$product_id_add."',
						'".$ar_design['shop_clothes_design_id']."',
						'".$ar_style_parent['shop_clothes_style_parents_id']."',
						'".$ar_color['shop_clothes_colors_id']."',
						'".$ar_sizes['shop_clothes_size_size']."',
						'',
						'1')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$num_prod_add++;
					} else {
						//$res = mysql_query("SELECT * FROM $db_shop_product WHERE shop_product_id=".$ar_prod_cl['shop_product_clothes_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						//$ar = mysql_fetch_array($res);
						$shop_clothes_design_selling_price = $ar_design['shop_clothes_design_selling_price'] + $ar_style_parent['shop_clothes_style_parents_extrapay'];
						mysql_query("UPDATE $db_shop_product SET
						shop_product_name='".$ar_design['shop_clothes_design_title']."',
						shop_product_model='".$ar_design['shop_clothes_design_model']."',
						shop_product_selling_price=".$shop_clothes_design_selling_price.",
						shop_product_discount_cat_seller_id=".$ar_style_parent['shop_clothes_style_parents_discount_cat_seller_1'].",
						shop_product_discount_cat_cust_id=".$ar_style_parent['shop_clothes_style_parents_discount_cat_cust_1'].",
						shop_product_description_short='".$ar_design['shop_clothes_design_description_short']."',
						shop_product_description='".$ar_design['shop_clothes_design_description']."',
						shop_product_weight=".$ar_style_parent['shop_clothes_style_parents_weight'].",
						shop_product_master_category=".$ar_design['shop_clothes_design_master_category'].",
						shop_product_subcategory1=".$ar_design['shop_clothes_design_subcategory1'].",
						shop_product_subcategory2=".$ar_design['shop_clothes_design_subcategory2'].",
						shop_product_subcategory3=".$ar_design['shop_clothes_design_subcategory3'].",
						shop_product_subcategory4=".$ar_design['shop_clothes_design_subcategory4'].",
						shop_product_subcategory5=".$ar_design['shop_clothes_design_subcategory5'].",
						shop_product_last_modified=NOW(),
						shop_product_date_available='".$ar_design['shop_clothes_design_date_available']."',
						shop_product_vat_class_id=".$ar_design['shop_clothes_design_vat_class_id']."
						WHERE shop_product_id=".$ar_prod_cl['shop_product_clothes_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

						/* Vybranym produktum nastavime ze jsou aktivni */
						mysql_query("UPDATE $db_shop_product_clothes SET shop_product_clothes_active=1 WHERE shop_product_clothes_product_id=".$ar_prod_cl['shop_product_clothes_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());

						$num_prod_edit++;
					}
					//echo Zerofill($ar_design['shop_clothes_design_id'],10000)."-".Zerofill($ar_style_parent['shop_clothes_style_parents_id'],1000)."-".$ar_color['shop_clothes_colors_prefix'].Zerofill($ar_color['shop_clothes_colors_id'],100)."-".$ar_sizes['shop_clothes_size_size']."<br>";
				}
				$y++;
			}
		}
	}
	$i++;
}

/* Produktum ktere nebyly vybrany nastavime ze nejsou aktivni */
$res_prod_cl_noactive = mysql_query("SELECT shop_product_clothes_product_id FROM $db_shop_product_clothes WHERE shop_product_clothes_design_id=".$ar_design['shop_clothes_design_id']." $style_parent_id_nonexist") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
while ($ar_prod_cl_noactive = mysql_fetch_array($res_prod_cl_noactive)){
	mysql_query("UPDATE $db_shop_product_clothes SET shop_product_clothes_active=0 WHERE shop_product_clothes_product_id=".$ar_prod_cl_noactive['shop_product_clothes_product_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
}

if ($num_prod_add != 0){$sys_save_message = urlencode(_SHOP_CL_DESIGN_PRODUCTS_ADD.$num_prod_add."<br>");} else {$sys_save_message = urlencode(_SHOP_CL_DESIGN_PRODUCTS_EDIT.$num_prod_edit."<br>");}

$style_parent_id_nonexist;

header ("Location: ".$eden_cfg['url_cms']."modul_shop.php?action=clothes_show_designs&project=".$_SESSION['project']."&sys_save_message=".urlencode($sys_save_message));
exit;
?>