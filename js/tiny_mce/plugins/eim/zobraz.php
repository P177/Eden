<?php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";


if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
if ($_GET['adminid'] != ""){$adminid = $_GET['adminid'];} elseif ($_POST['adminid'] != ""){$adminid = $_POST['adminid'];} else {$adminid = "";}

include_once($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
include_once($eden_editor_tinymce_plugin_path."/sessions.php");
//include_once($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");
include_once($eden_editor_tinymce_plugin_path."/functions.php");

/* Spojeni s FTP serverem */
$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP); 
/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);

/* Zjisteni stavu spojeni */
if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
switch ($_GET['mode']){
	case "show_list":
		echo ShowImgList();
	break;
	case "img_upl":
		$i = 0;
		foreach ($_FILES["ImgFile"]["error"] as $key => $error) {
			 if ($error == UPLOAD_ERR_OK) {
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				if ($_POST['mode'] == "main"){
					$ftp_path_images_upl = $ftp_path_images_main;
				} else {
					$ftp_path_images_upl = $ftp_path_images_usr.$adminid."/";
				}
				$source_file = $_FILES["ImgFile"]["tmp_name"][$key];
				$image = new SimpleImage();
				$image->load($source_file);
				/*
				echo "<script type=\"text/javascript\">";
				echo "alert('a".$image->image_info[0]."');";
				echo "</script>";
				*/
				if ($_POST['eim_img_size']){
					$width = 250;
					$height = 250;
					if ($image->image_info[0] < $_POST['eim_max_width'] && $image->image_info[1] < $_POST['eim_max_height']){
						/*
						echo "<script type=\"text/javascript\">";
						echo "alert('aaa');";
						echo "</script>";
						*/
						$image->resizeToHeight($_POST['eim_max_height']);
					} elseif ($image->image_info[0] > $_POST['eim_max_width']) {
						/*
						echo "<script type=\"text/javascript\">";
						echo "alert('bbb');";
						echo "</script>";
						*/
						$image->resizeToWidth($_POST['eim_max_width']);
					}
				}
				//$image->resizeToWidth($_POST['eim_max_width']);
				$image->saveByFTP(1,75,0,1);
			 }
			$i++;
		}
		echo ShowImgList();
	break;
	case "img_del":
	   echo "AAA";
	   echo "<br>";
	   echo $_POST['img_to_del'][0];
	   echo "<br>";
	   echo $_POST['mode'];
	   echo "<script type=\"text/javascript\">";
	   echo "alert('aaa');";
	   echo "</script>";
	   //ShowImgList();
	break;
	default:
	/* Nestane se nic */
}
/***********************************************************************************************************
*
*		ZOBRAZENI SEZNAMU OBRAZKU
*
***********************************************************************************************************/
function ShowImgList(){
	
	global $db_admin_images;
	global $eden_cfg;
	global $project,$adminid,$lang;
	
	$imgList = "";
	$imgList .= "<script language=\"JavaScript\" type=\"text/javascript\">\n";
	if ($_GET['img_mode'] == "main" || $_POST['img_mode'] == "main"){
		$url_images = $eden_cfg['url_images_main'];
		$url_images_thumb = $eden_cfg['url_images_main']."_thumb/";
		$image_mode = 2;
		$imgList .= "img_mode = \"main\";\n";
	} else {
		//$ftp_path_images_thumb = $ftp_path_images_usr.$_SESSION['loginid']."/_thumb/";
		$url_images = $eden_cfg['url_images_usr'].$_GET['img_usr']."/";
		$url_images_thumb = $eden_cfg['url_images_usr'].$_GET['img_usr']."/_thumb/";
		$image_mode = 1;
		$aid = " aid=".(integer)$adminid." AND ";
		$imgList .= "img_mode = \"usr\";\n";
	}
	$imgList .= "
	function insertFile(file){\n
		window.opener.insertURL(file);\n
		top.window.close();\n
	}\n";
	$imgList .= "</script>";
	
	$res_images = mysql_query("SELECT * FROM $db_admin_images WHERE $aid admin_image_mode=".$image_mode) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_images_num = mysql_num_rows($res_images);
	
	$imgs_in_row = 5; // Nastaveni poctu obrazku v rade
	$rows = ceil($ar_images_num / $imgs_in_row); // Spocitame pocet potrebnych radku
	$imgList .= "<div><form action=\"index.php?mode=img_del\" method=\"post\" enctype=\"multipart/form-data\">";
	$imgList .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"0\">\n";
	$x = 1; // Iterace radku tabulky
	$y = 0; // Iterace pro formular
	while ($ar_images = mysql_fetch_array($res_images)){
		$width = 150;
		$height = 150;
		/* Temito dvemi if dosahneme toho ze pokud je obrazek mensi nez sirka, nebo vyska tak se zobrazi jeho originalni vyska/sirka */
		if ($ar_images['admin_image_width'] <= $width){
			$width = $ar_images['admin_image_width'];
		}
		if ($ar_images['admin_image_height'] <= $height){
			$height = $ar_images['admin_image_height'];
		}
		if ($ar_images['admin_image_width'] > $ar_images['admin_image_height']){
			$img_size = "width=\"".$width."\"";
		} else {
			$img_size = "height=\"".$height."\"";
		}
		$image_size = GetFilesizeInKB($ar_images['admin_image_size']);
		if ($x == 1){
			$imgList .= "<tr valign=\"top\">\n";
		}
		$imgList .= "<td width=\"150px\" height=\"230\"><div style=\"width:150px;height:150px;background-color:#f0f0f0;text-align:center;\"><img src=\"".$url_images_thumb.$ar_images['admin_image_name']."\" border=\"0\"></div>";
		$imgList .= "	<div style=\"width:144px;height:74px;background-color:#f0f0f0;padding:3px;\">";
		/* $imgList .= "		".$ar_images['admin_image_name']."<br>";*/
		
		$imgList .= "	<a href=\"javascript:insertFile('".$ar_images['admin_image_name']."');\">"._EIM_INSERT_IMAGE."</a><br>";
		$imgList .= 		"<strong>".$ar_images['admin_image_width']."</strong>x<strong>".$ar_images['admin_image_height']."</strong> px<br>";
		$imgList .= 		$image_size."<br>";
		if ($ar_images['aid'] == $adminid || CheckPriv("groups_article_all_del") == 1){
			$imgList .= "	<input type=\"checkbox\" name=\"img_to_del[]\" value=\"".$ar_images['admin_image_id']."\">";
		}
		$imgList .= "			<br>";
		$imgList .= "		</div>";
		$imgList .= "	</td>";
		
		if ($x == $imgs_in_row){
			$imgList .= "</tr>\n";
			$x = 1;
		} else {
			$x++;
		}
		$y++;
	}
	$imgList .= "		</td>";
	$imgList .= "	</tr>";
	$imgList .= "</table>";
	$imgList .= "<input type=\"hidden\" name=\"confirm\" value=\"1\">\n";
	$imgList .= "<input type=\"submit\" value=\""._EIM_DEL_IMAGES."\" class=\"fmButton\">\n";
	$imgList .= "<input type=\"hidden\" name=\"project\" value=\"".$project."\">";
	$imgList .= "<input type=\"hidden\" name=\"adminid\" value=\"".$adminid."\">";
	$imgList .= "<input type=\"hidden\" name=\"img_mode\" value=\"".$_GET['img_mode'] ."\">";
	$imgList .= "<input type=\"hidden\" name=\"lang\" value=\"".$lang."\">";
	$imgList .= "</form></div>";
	
	return $imgList;
}
