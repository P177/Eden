<?php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}



//Nastaveni nenacitani spatneho jazyka z functions.php
include_once ($eden_editor_tinymce_plugin_path."/sessions.php");
if ($_GET['adminid'] != ""){$adminid = $_GET['adminid'];} elseif ($_POST['adminid'] != ""){$adminid = $_POST['adminid'];} else {$adminid = $_SESSION['loginid'];}
require_once ($eden_editor_tinymce_plugin_path."/functions.php");
require_once ($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
echo "<head>\n";
echo "	<title>{#advimage_dlg.dialog_title}</title>\n";
echo "	<script type=\"text/javascript\" src=\"../../tiny_mce_popup.js\"></script>\n";
echo "	<script type=\"text/javascript\" src=\"../../utils/mctabs.js\"></script>\n";
echo "	<script type=\"text/javascript\" src=\"../../utils/form_utils.js\"></script>\n";
echo "	<script type=\"text/javascript\" src=\"../../utils/validate.js\"></script>\n";
echo "	<script type=\"text/javascript\" src=\"../../utils/editable_selects.js\"></script>\n";
echo "	<script type=\"text/javascript\" src=\"js/image.js\"></script>\n";
echo "	<link href=\"css/advimage.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "</head>\n";
echo "<body id=\"advimage\" style=\"display: none\" onload=\"changeDivSrc('src0_lib','block');\">";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	include_once ($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");
	// bool ftp_copy  ( resource $ftp_stream  , string $initialpath, string $newpath, string $imagename ) 
	function ftp_copy($conn_distant , $pathftp , $pathftpimg ,$img){ 
		// on recupere l'image puis on la repose dans le nouveau folder 
		if(ftp_get($conn_distant, "/temp/".$img, $pathftp.$img ,FTP_BINARY)){
			if(ftp_put($conn_distant, $pathftpimg.$img ,"/temp/".$img , FTP_BINARY)){ 
				unlink("/temp/".$img);
			} else {
				return false;
			}
		}else{
			return false;
		}
		return true;
	} 
	// Spojeni s FTP serverem
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	
	// Zjisteni stavu spojeni
	if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	// Byl uz formular odeslan?
	if ($_POST['send']){
		$time = Cislo();
		// Obrazky z knihovny
		if ($_POST['src0ch'] == 1 || $_POST['src2ch'] == 1 || $_POST['src3ch'] == 1 || $_POST['src4ch'] == 1){
			$article_over = "";
			$article_out = "";
			if ($_POST['src0ch'] == 1){
				$res_image0 = mysql_query("SELECT aid, admin_image_name, admin_image_mode, admin_image_width, admin_image_height FROM $db_admin_images WHERE admin_image_name='".mysql_real_escape_string($_POST['src0'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_image0 = mysql_fetch_array($res_image0);
				if ($ar_image0['admin_image_mode'] == 2){
					$source_file = $url_admins_images_main.$ar_image0['admin_image_name'];
				} else {
					$source_file = $url_admins_images_usr.$ar_image0['aid']."/".$ar_image0['admin_image_name'];
				}
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				$ftp_path_images_upl = $ftp_path_articles;
				
				$image1 = new SimpleImage();
				$image1->load($source_file);
				
				if ($_POST['constrain'] == 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] == ""){
					$image1->resizeToWidth((integer)$_POST['width']);
				} elseif ($_POST['constrain'] == 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] != ""){
					$image1->resizeToWidth((integer)$_POST['width']);
				} elseif ($_POST['constrain'] == 1 && (integer)$_POST['width'] == "" && (integer)$_POST['height'] != ""){
					$image1->resizeToHeight((integer)$_POST['height']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] != "") {
					$image1->resize((integer)$_POST['width'],(integer)$_POST['height']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] == "") {
					$image1->resize((integer)$_POST['width'],(integer)$_POST['width']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] == "" && (integer)$_POST['height'] != "") {
					$image1->resize((integer)$_POST['height'],(integer)$_POST['height']);
				}
				$img_name = str_ireplace(".gif","",$ar_image0['admin_image_name']);
				$img_name = str_ireplace(".jpg","",$img_name);
				$img_name = str_ireplace(".jpeg","",$img_name);
				$img_name = str_ireplace(".png","",$img_name);
				//echo $source_file; exit;
				$new_name1 = $url_articles.$ar_image0['admin_image_name'];
				$image1->saveByFTP(0,75,$img_name,0);
			}
			if ($_POST['src2ch'] == 1 && $_POST['linkch'] == 1){
				$res_image2 = mysql_query("SELECT aid, admin_image_name, admin_image_mode, admin_image_width, admin_image_height FROM $db_admin_images WHERE admin_image_name='".mysql_real_escape_string($_POST['src2'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_image2 = mysql_fetch_array($res_image2);
				
				if ($ar_image2['admin_image_mode'] == 2){
					$source_file = $url_admins_images_main.$ar_image2['admin_image_name'];
				} else {
					$source_file = $url_admins_images_usr.$ar_image2['aid']."/".$ar_image2['admin_image_name'];
				}
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				$ftp_path_images_upl = $ftp_path_articles;
				
				$image2 = new SimpleImage();
				$image2->load($source_file);
				
				$img_name = str_ireplace(".gif","",$ar_image2['admin_image_name']);
				$img_name = str_ireplace(".jpg","",$img_name);
				$img_name = str_ireplace(".jpeg","",$img_name);
				$img_name = str_ireplace(".png","",$img_name);
				//echo $source_file; exit;
				$new_name3 = _URL_ARTICLES.$ar_image2['admin_image_name'];
				$image2->saveByFTP(0,75,$img_name,0);
			}
			if ($_POST['src3ch'] == 1 && $_POST['onmousemovecheck'] == 0){
				$res_image4 = mysql_query("SELECT aid, admin_image_name, admin_image_mode, admin_image_width, admin_image_height FROM $db_admin_images WHERE admin_image_name='".mysql_real_escape_string($_POST['src4'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_image4 = mysql_fetch_array($res_image4);
				if ($ar_image4['admin_image_mode'] == 2){
					$source_file = $url_admins_images_main.$ar_image4['admin_image_name'];
				} else {
					$source_file = $url_admins_images_usr.$ar_image4['aid']."/".$ar_image4['admin_image_name'];
				}
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				$ftp_path_images_upl = $ftp_path_articles;
				
				$image4 = new SimpleImage();
				$image4->load($source_file);
				
				$img_name = str_ireplace(".gif","",$ar_image4['admin_image_name']);
				$img_name = str_ireplace(".jpg","",$img_name);
				$img_name = str_ireplace(".jpeg","",$img_name);
				$img_name = str_ireplace(".png","",$img_name);
				
				if ($ar_image4['admin_image_name']){
					$new_over = _URL_ARTICLES.$ar_image4['admin_image_name'];
				} else {
					$new_over = "";
				}
				$image4->saveByFTP(0,75,$img_name,0);
			}
			if ($_POST['src4ch'] == 1 && $_POST['onmousemovecheck'] == 0){
				$res_image6 = mysql_query("SELECT aid, admin_image_name, admin_image_mode, admin_image_width, admin_image_height FROM $db_admin_images WHERE admin_image_name='".mysql_real_escape_string($_POST['src6'])."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_image6 = mysql_fetch_array($res_image6);
				
				if ($ar_image6['admin_image_mode'] == 2){
					$source_file = $url_admins_images_main.$ar_image6['admin_image_name'];
				} else {
					$source_file = $url_admins_images_usr.$ar_image6['aid']."/".$ar_image6['admin_image_name'];
				}
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				$ftp_path_images_upl = $ftp_path_articles;
				
				$image6 = new SimpleImage();
				$image6->load($source_file);
				
				$img_name = str_ireplace(".gif","",$ar_image6['admin_image_name']);
				$img_name = str_ireplace(".jpg","",$img_name);
				$img_name = str_ireplace(".jpeg","",$img_name);
				$img_name = str_ireplace(".png","",$img_name);
				
				if ($ar_image6['admin_image_name'] != ""){
					$new_out = _URL_ARTICLES.$ar_image6['admin_image_name'];
				} else {
					$new_out = "";
				}
				$image6->saveByFTP(0,75,$img_name,0);
			}
		}
		// Obrazky primo z disku
		if ($_POST['src0ch'] == 2 || $_POST['src2ch'] == 2 || $_POST['src3ch'] == 2 || $_POST['src4ch'] == 2){
			// ziskam extenzi souboru
			$extenze1 = CheckImgExtension($_FILES['src1']['tmp_name']); // Hlavni obrazek co se zobrazi v clanku
			if ($_POST['linkch'] == 1){  // Obrazek na ktery ukazuje hlavni obrazek (zpravidla vetsi rozliseni hlavniho obrazku)
				$extenze3 = CheckImgExtension($_FILES['src3']['tmp_name']);
			}
			if ($_POST['onmousemovecheck'] == 0){ 
				$extenze5 = CheckImgExtension($_FILES['src5']['tmp_name']); // OnMouseOver obrazek
				$extenze7 = CheckImgExtension($_FILES['src7']['tmp_name']); // OnMouseOut obrazek
			}
			// generuji nazev souboru
			
			if ($extenze1 != false){
				/*
				$file1 = $time.$extenze1;
				$new_name1 = $url_articles.$file1;
				// Nastaveni spravneho adresare pro upload
				// Promenna se pouzije ve tride SimpleImage
				$ftp_path_images_upl = $ftp_path_articles;
				
				$source_file = $_FILES['src1']['tmp_name'];
				
				$image1 = new SimpleImage();
				$image1->load($source_file);
				
				if ($_POST['constrain'] == 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] == ""){
					$image1->resizeToWidth((integer)$_POST['width']);
				} elseif ($_POST['constrain'] == 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] != ""){
					$image1->resizeToWidth((integer)$_POST['width']);
				} elseif ($_POST['constrain'] == 1 && (integer)$_POST['width'] == "" && (integer)$_POST['height'] != ""){
					$image1->resizeToHeight((integer)$_POST['height']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] != "") {
					$image1->resize((integer)$_POST['width'],(integer)$_POST['height']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] != "" && (integer)$_POST['height'] == "") {
					$image1->resize((integer)$_POST['width'],(integer)$_POST['width']);
				} elseif ($_POST['constrain'] != 1 && (integer)$_POST['width'] == "" && (integer)$_POST['height'] != "") {
					$image1->resize((integer)$_POST['height'],(integer)$_POST['height']);
				}
				$image1->saveByFTP(0,75,$time,0);
				*/
				$file1 = Cislo().$extenze1;
				$new_name1 = _URL_ARTICLES.$file1;
				$source_file1 = $_FILES['src1']['tmp_name'];
				$destination_file1 = $ftp_path_articles.$file1;
		   		$upload1 = ftp_put($conn_id, $destination_file1, $source_file1, FTP_BINARY);
				// Zjisteni stavu uploadu
				if (!$upload1) {
	        		echo _ERROR_UPLOAD;
	    		}
			}
			if ($extenze3 != false){
				$file3 = Cislo().$extenze3;
				$new_name3 = _URL_ARTICLES.$file3;
				$source_file3 = $_FILES['src3']['tmp_name'];
				$destination_file3 = $ftp_path_articles.$file3;
		   		$upload3 = ftp_put($conn_id, $destination_file3, $source_file3, FTP_BINARY);
				// Zjisteni stavu uploadu
				if (!$upload3) {
	        		echo _ERROR_UPLOAD;
	    		}
			}
			if ($extenze5 != false){
				$file5 = Cislo().$extenze5;
				$new_over = _URL_ARTICLES.$file5;
				$source_file5 = $_FILES['src5']['tmp_name'];
				$destination_file5 = $ftp_path_articles.$file5;
		   		$upload5 = ftp_put($conn_id, $destination_file5, $source_file5, FTP_BINARY);
				// Zjisteni stavu uploadu
				if (!$upload5) {
	        		echo _ERROR_UPLOAD;
	    		}
			}
			if ($extenze7 != false){
				$file7 = Cislo().$extenze7;
				$new_out = _URL_ARTICLES.$file7;
				$source_file7 = $_FILES['src7']['tmp_name'];
				$destination_file7 = $ftp_path_articles.$file7;
				$upload7 = ftp_put($conn_id, $destination_file7, $source_file7, FTP_BINARY);
				// Zjisteni stavu uploadu
				if (!$upload7) {
	        		echo _ERROR_UPLOAD;
	    		}
			}
		}
		// Uzavreni komunikace se serverem
		ftp_close($conn_id);
		
		// Kdyz je nastaveny Onmouseover
		// Pred ' je treba pridat \ jinak to zablokuje JS
		if ($_POST['onmousemovecheck'] == 0){
			$onmouse_name = "e".Cislo();
			if ($new_over != ""){
				$onmouse_code_over = "onmouseover=\"document.".$onmouse_name.".src=\'".$new_over."\'\"";
			} else {
				$onmouse_code_over = "";
			}
			if ($new_out == "" && $new_over == ""){
				$onmouse_code_out = "";
			} elseif ($new_out != ""){
				$onmouse_code_out = " onmouseout=\"document.".$onmouse_name.".src=\'".$new_out."\'\"";
			} else {
				$onmouse_code_out = " onmouseout=\"document.".$onmouse_name.".src=\'".$new_name1."\'\"";
			}
			$onmouse_alt =  " name=\"".$onmouse_name."\" alt=\"".$onmouse_name."\"";
			$onmouse = $onmouse_code_over.$onmouse_code_out.$onmouse_alt;
		} else {
			$onmouse = "";
		}
		
		// Kdyz neni zadan velky obrazek nebo link vlozi se jen obrazek bez linku
		if ($_POST['linkch'] == 1){
			// IMG
			$ahref1 = "<a href=\"".$new_name3."\" target=\"".$_POST['target']."\">";
			$ahref2 = "</a>";
		} elseif ($_POST['linkch'] == 2) {
			// URL
			$ahref1 = "<a href=\"".$_POST['link']."\" target=\"".$_POST['target']."\">";
			$ahref2 = "</a>";
		} else {
			$ahref1 = "";
			$ahref2 = "";
		}
		
		if ($_POST['width'] != ""){$img_width = "width=\"".(integer)$_POST['width']."\"";} else {$img_width == "";}
		if ($_POST['height'] != ""){$img_height = "height=\"".(integer)$_POST['height']."\"";} else {$img_height == "";}
		if ($_POST['style'] != ""){$img_style = "style=\"".$_POST['style']."\"";} else {$img_style == "";}
		if ($_POST['id'] != ""){$img_id = "id=\"".$_POST['id']."\"";} else {$img_id == "";}
		
		unset($_POST['link']);
		echo "<script>\n
			content ='".$ahref1."<img src=\"".$new_name1."\" ".$img_width." ".$img_height." ".$img_style." ".$img_id." alt=\"".$_POST['alt1']."\" title=\"".$_POST['title1']."\" ".$onmouse." >".$ahref2."';\n
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.restoreSelection();\n
			tinyMCEPopup.execCommand('mceInsertContent', false, content);\n
			tinyMCEPopup.close();\n
			</script>\n
		";
	} else { // pokud nic jeste posnai nebylo zobrazi se dialog
		echo "	<form method=\"POST\" enctype=\"multipart/form-data\" name=\"the_form\"> \n";
		echo "		<div class=\"tabs\">\n";
		echo "			<ul>\n";
		echo "				<li id=\"general_tab\" class=\"current\"><span><a href=\"javascript:mcTabs.displayTab('general_tab','general_panel');\" onmousedown=\"return false;\">{#advimage_dlg.tab_general}</a></span></li>\n";
		echo "				<li id=\"appearance_tab\"><span><a href=\"javascript:mcTabs.displayTab('appearance_tab','appearance_panel');\" onmousedown=\"return false;\">{#advimage_dlg.tab_appearance}</a></span></li>\n";
		echo "				<li id=\"advanced_tab\"><span><a href=\"javascript:mcTabs.displayTab('advanced_tab','advanced_panel');\" onmousedown=\"return false;\">{#advimage_dlg.tab_advanced}</a></span></li>\n";
		/* echo "				<li id=\"help_tab\"><span><a href=\"javascript:mcTabs.displayTab('help_tab','help_panel');\" onmousedown=\"return false;\">{#advimage_dlg.tab_help}</a></span></li>\n"; */
		echo "			</ul>\n";
		echo "		</div>\n";
		echo "		<div class=\"panel_wrapper\">\n";
		echo "			<div id=\"general_panel\" class=\"panel current\">\n";
		echo "				<fieldset>\n";
		echo "						<legend>{#advimage_dlg.general}</legend>\n";
		echo "						<table class=\"properties\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"srclabel\" for=\"src0\">{#advimage_dlg.src_from}</label></td>\n";    
		echo "								<td colspan=\"2\" class=\"nowrap\"><input type=\"radio\" checked=\"checked\" name=\"src0ch\" value=\"1\" class=\"checkbox_img\" onClick=\"hideAllSrc0(); changeDivSrc('src0_lib','block');\"> {#advimage_dlg.src_from_lib} <input type=\"radio\" name=\"src0ch\" value=\"2\" class=\"checkbox_img\" onClick=\"hideAllSrc0(); changeDivSrc('src0_dsk','block');\">{#advimage_dlg.src_from_dsk}</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "						<table class=\"properties\">\n";
		echo "							<tr id=\"src0_lib\" style=\"display:none\">\n";
		echo "								<td class=\"column1\"><label id=\"srclabel\" for=\"src0\">{#advimage_dlg.src0}</label></td>\n";
		echo "								<td>";
		echo "									<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "										<tr> \n";
		echo "									  		<td><input name=\"src0\" type=\"text\" id=\"src0\" value=\"\"/></td>\n";
		echo "									  		<td id=\"srcbrowsercontainer\">&nbsp;</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr id=\"src0_dsk\" style=\"display:none\">\n";
		echo "								<td class=\"column1\"><label id=\"srclabel\" for=\"src_src1\">{#advimage_dlg.src1}</label></td>\n";
		echo "								<td><input name=\"src1\" type=\"file\" id=\"src1\" value=\"\" /></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "						<table class=\"properties\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"altlabel\" for=\"alt\">{#advimage_dlg.alt}</label></td>\n";
		echo "								<td><input id=\"alt1\" name=\"alt1\" type=\"text\" value=\"\" /></td> \n";
		echo "							</tr>\n";
		echo "							<tr> \n";
		echo "								<td class=\"column1\"><label id=\"titlelabel\" for=\"title\">{#advimage_dlg.title}</label></td>\n";
		echo "								<td><input id=\"title1\" name=\"title1\" type=\"text\" value=\"\" /></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "				</fieldset>\n";
		echo "				<fieldset>\n";
		echo "						<legend>{#advimage_dlg.link}</legend>\n";
		echo "						<table class=\"properties\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"linklabel\" for=\"linkch\">{#advimage_dlg.link_to}</label></td>\n";
		echo "								<td colspan=\"2\" class=\"nowrap\">";
		echo "									<input type=\"radio\" name=\"linkch\" value=\"0\" checked=\"checked\" class=\"checkbox_img\" onClick=\"hideAllLinkTo(); changeDivSrc('src2_lib','none'); changeDivSrc('src2_dsk','none'); changeDivSrc('src2_alt','none');\"> {#advimage_dlg.link_to_none}";
		echo "									<input type=\"radio\" value=\"1\" name=\"linkch\" class=\"checkbox_img\" onClick=\"hideAllLinkTo(); changeDivLinkTo('lnk_img','block'); changeDivLinkTo('lnk_target','block'); changeDivLinkTo('src2_alt','block'); changeDivSrcByCh();\"> {#advimage_dlg.link_to_img}";
		echo "									<input type=\"radio\" value=\"2\" name=\"linkch\" class=\"checkbox_img\" onClick=\"hideAllLinkTo(); changeDivLinkTo('lnk_url','block'); changeDivLinkTo('lnk_target','block'); changeDivSrc('src2_lib','none'); changeDivSrc('src2_dsk','none'); changeDivSrc('src2_alt','none');\">{#advimage_dlg.link_to_url}";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "						<table class=\"properties\" id=\"lnk_img\" style=\"display:none\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"srclabel\" for=\"src2\">{#advimage_dlg.src_from}</label></td>\n";
		echo "								<td colspan=\"2\" class=\"nowrap\">";
		echo "									<input type=\"radio\" id=\"lib\" name=\"src2ch\" value=\"1\" checked=\"checked\" class=\"checkbox_img\" onClick=\"hideAllSrc2(); changeDivSrc('src2_lib','block');\"> {#advimage_dlg.src_from_lib}";
		echo "									<input type=\"radio\" id=\"dsk\" name=\"src2ch\" value=\"2\" class=\"checkbox_img\" onClick=\"hideAllSrc2(); changeDivSrc('src2_dsk','block');\">{#advimage_dlg.src_from_dsk}";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		// From library
		echo "						<table class=\"properties\" id=\"src2_lib\" style=\"display:none\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"srclabel\" for=\"src2\">{#advimage_dlg.src2}</label></td>\n";
		echo "								<td colspan=\"2\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "									<tr> \n";
		echo "									  <td><input name=\"src2\" type=\"text\" id=\"src2\" value=\"\"/></td> \n";
		echo "									  <td id=\"srcbrowsercontainer2\">&nbsp;</td>\n";
		echo "									</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		// From disk
		echo "						<table class=\"properties\" id=\"src2_dsk\" style=\"display:none\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label for=\"src_src3\">{#advimage_dlg.src3}</label></td>\n";
		echo "								<td><input name=\"src3\" type=\"file\" id=\"src3\" value=\"\" /></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		/*
		// Alt
		echo "						<table class=\"properties\" id=\"src2_alt\" style=\"display:none\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"altlabel\" for=\"alt2\">{#advimage_dlg.alt}</label></td> \n";
		echo "								<td colspan=\"2\"><input id=\"alt2\" name=\"alt2\" type=\"text\" value=\"\" /></td> \n";
		echo "							</tr> \n";
		echo "							<tr> \n";
		echo "								<td class=\"column1\"><label id=\"titlelabel\" for=\"title2\">{#advimage_dlg.title}</label></td> \n";
		echo "								<td colspan=\"2\"><input id=\"title2\" name=\"title2\" type=\"text\" value=\"\" /></td> \n";
		echo "							</tr>\n";
		echo "						</table>\n";
		*/
		// Url
		echo "						<table class=\"properties\" id=\"lnk_url\" style=\"display:none\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"column1\"><label id=\"linklabel\" for=\"link\">{#advimage_dlg.link}</label></td> \n";
		echo "								<td><input type=\"text\" id=\"link\" name=\"link\" size=\"50\" maxlength=\"250\"></td> \n";
		echo "							</tr>\n";
		echo "						</table>\n";
		
		// Target
		echo "						<table class=\"properties\" id=\"lnk_target\" style=\"display:none\">\n";
	   	echo "							<tr>\n";
		echo "								<td class=\"column1\"><label for=\"target\">{#advimage_dlg.target}</label></td>\n";
		echo "								<td class=\"nowrap\">";
		echo " 									<select name=\"target\" id=\"target\">\n";
		echo " 										<option selected value=\"_blank\">_blank</option>\n";
		echo " 										<option value=\"_self\">_self</option>\n";
		echo " 									</select>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "				</fieldset>\n";
		echo "			</div>\n";
		echo "			<div id=\"appearance_panel\" class=\"panel\">\n";
		echo "				<fieldset>\n";
		echo "					<legend>{#advimage_dlg.tab_appearance}</legend>\n";
		echo "					<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
		echo "						<tr> \n";
		echo "							<td class=\"column1\"><label id=\"alignlabel\" for=\"align\">{#advimage_dlg.align}</label></td> \n";
		echo "							<td><select id=\"align\" name=\"align\" onchange=\"ImageDialog.updateStyle('align');ImageDialog.changeAppearance();\"> \n";
		echo "									<option value=\"\">{#not_set}</option> \n";
		echo "									<option value=\"baseline\">{#advimage_dlg.align_baseline}</option>\n";
		echo "									<option value=\"top\">{#advimage_dlg.align_top}</option>\n";
		echo "									<option value=\"middle\">{#advimage_dlg.align_middle}</option>\n";
		echo "									<option value=\"bottom\">{#advimage_dlg.align_bottom}</option>\n";
		echo "									<option value=\"text-top\">{#advimage_dlg.align_texttop}</option>\n";
		echo "									<option value=\"text-bottom\">{#advimage_dlg.align_textbottom}</option>\n";
		echo "									<option value=\"left\">{#advimage_dlg.align_left}</option>\n";
		echo "									<option value=\"right\">{#advimage_dlg.align_right}</option>\n";
		echo "								</select> \n";
		echo "							</td>\n";
		echo "							<td rowspan=\"6\" valign=\"top\">\n";
		echo "								<div class=\"alignPreview\">\n";
		echo "									<img id=\"alignSampleImg\" src=\"img/sample.gif\" alt=\"{#advimage_dlg.example_img}\" />\n";
		echo "									Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam\n";
		echo "									nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Loreum ipsum\n";
		echo "									edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam\n";
		echo "									erat volutpat.\n";
		echo "								</div>\n";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"widthlabel\" for=\"width\">{#advimage_dlg.dimensions}</label></td>\n";
		echo "							<td class=\"nowrap\">\n";
		echo "								<input name=\"width\" type=\"text\" id=\"width\" value=\"\" size=\"5\" maxlength=\"5\" class=\"size\" onchange=\"ImageDialog.changeHeight();\" /> x \n";
		echo "								<input name=\"height\" type=\"text\" id=\"height\" value=\"\" size=\"5\" maxlength=\"5\" class=\"size\" onchange=\"ImageDialog.changeWidth();\" /> px\n";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\">&nbsp;</td>\n";
		echo "							<td><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "									<tr>\n";
		echo "										<td><input id=\"constrain\" type=\"checkbox\" name=\"constrain\" class=\"checkbox\" value=\"1\" /></td>\n";
		echo "										<td><label id=\"constrainlabel\" for=\"constrain\">{#advimage_dlg.constrain_proportions}</label></td>\n";
		echo "									</tr>\n";
		echo "								</table></td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"vspacelabel\" for=\"vspace\">{#advimage_dlg.vspace}</label></td> \n";
		echo "							<td><input name=\"vspace\" type=\"text\" id=\"vspace\" value=\"\" size=\"3\" maxlength=\"3\" class=\"number\" onchange=\"ImageDialog.updateStyle('vspace');ImageDialog.changeAppearance();\" onblur=\"ImageDialog.updateStyle('vspace');ImageDialog.changeAppearance();\" />\n";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "						<tr> \n";
		echo "							<td class=\"column1\"><label id=\"hspacelabel\" for=\"hspace\">{#advimage_dlg.hspace}</label></td> \n";
		echo "							<td><input name=\"hspace\" type=\"text\" id=\"hspace\" value=\"\" size=\"3\" maxlength=\"3\" class=\"number\" onchange=\"ImageDialog.updateStyle('hspace');ImageDialog.changeAppearance();\" onblur=\"ImageDialog.updateStyle('hspace');ImageDialog.changeAppearance();\" /></td> \n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"borderlabel\" for=\"border\">{#advimage_dlg.border}</label></td> \n";
		echo "							<td><input id=\"border\" name=\"border\" type=\"text\" value=\"\" size=\"3\" maxlength=\"3\" class=\"number\" onchange=\"ImageDialog.updateStyle('border');ImageDialog.changeAppearance();\" onblur=\"ImageDialog.updateStyle('border');ImageDialog.changeAppearance();\" /></td> \n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td><label for=\"class_list\">{#class_name}</label></td>\n";
		echo "							<td colspan=\"2\"><select id=\"class_list\" name=\"class_list\" class=\"mceEditableSelect\"><option value=\"\"></option></select></td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"stylelabel\" for=\"style\">{#advimage_dlg.style}</label></td> \n";
		echo "							<td colspan=\"2\"><input id=\"style\" name=\"style\" type=\"text\" value=\"\" onchange=\"ImageDialog.changeAppearance();\" /></td> \n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</fieldset>\n";
		echo "			</div>\n";
		echo "			<div id=\"advanced_panel\" class=\"panel\">\n";
		echo "				<fieldset>\n";
		echo "					<legend>{#advimage_dlg.swap_image}</legend>\n";
		echo "					<input type=\"checkbox\" value=\"1\" id=\"onmousemovecheck\" name=\"onmousemovecheck\" class=\"checkbox\" onclick=\"changeDivSwap();changeDivSwaps();\"/>\n";
		echo "					<label id=\"onmousemovechecklabel\" for=\"onmousemovecheck\">{#advimage_dlg.alt_image}</label>\n";
		echo "					<table class=\"properties\" id=\"swap_img\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"srclabel\" for=\"src3ch\">{#advimage_dlg.src_from}</label></td>\n";
		echo "							<td colspan=\"2\" class=\"nowrap\">";
		echo "								<input type=\"radio\" name=\"src3ch\" value=\"1\" checked=\"checked\" class=\"checkbox_img\" onClick=\"hideAllSrc3(); changeDivSrc('src3_lib','block');\"> {#advimage_dlg.src_from_lib}";
		echo "								<input type=\"radio\" name=\"src3ch\" value=\"2\" class=\"checkbox_img\" onClick=\"hideAllSrc3(); changeDivSrc('src3_dsk','block');\">{#advimage_dlg.src_from_dsk}";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		// Mouse Over - From library
		echo "					<table class=\"properties\" id=\"src3_lib\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"onmouseoversrclabel\" for=\"src4\">{#advimage_dlg.mouseover}</label></td>\n";
		echo "							<td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
		echo "								<tr> \n";
		echo "								  <td><input id=\"src4\" name=\"src4\" type=\"text\" value=\"\" /></td> \n";
		echo "								  <td id=\"onmouseoversrccontainer\">&nbsp;</td>\n";
		echo "								</tr>\n";
		echo "							  </table></td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		// Mouse Over - From Disk
		echo "					<table class=\"properties\" id=\"src3_dsk\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"onmouseoversrclabel2\" for=\"src5\">{#advimage_dlg.mouseover2}</label></td> \n";
		echo "							<td><input id=\"src5\" name=\"src5\" type=\"file\" value=\"\" /></td> \n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "					<table class=\"properties\" id=\"swap_img2\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"srclabel\" for=\"src4\">{#advimage_dlg.src_from}</label></td>\n";    
		echo "							<td colspan=\"2\" class=\"nowrap\">";
		echo "								<input type=\"radio\" name=\"src4ch\" value=\"1\" checked=\"checked\" class=\"checkbox_img\" onClick=\"hideAllSrc4(); changeDivSrc('src4_lib','block');\"> {#advimage_dlg.src_from_lib}";
		echo "								<input type=\"radio\" name=\"src4ch\" value=\"2\" class=\"checkbox_img\" onClick=\"hideAllSrc4(); changeDivSrc('src4_dsk','block');\">{#advimage_dlg.src_from_dsk}";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		// Mouse Out - From library
		echo "					<table class=\"properties\" id=\"src4_lib\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"onmouseoutsrclabel\" for=\"src6\">{#advimage_dlg.mouseout}</label></td> \n";
		echo "							<td class=\"column2\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
		echo "								<tr> \n";
		echo "								  <td><input id=\"src6\" name=\"src6\" type=\"text\" value=\"\" /></td> \n";
		echo "								  <td id=\"onmouseoutsrccontainer\">&nbsp;</td>\n";
		echo "								</tr> \n";
		echo "							</table></td> \n";
		echo "						</tr>\n";
		echo "					</table>\n";
		// Mouse Out - From Disk
		echo "					<table class=\"properties\" id=\"src4_dsk\" style=\"display:none\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"onmouseoutsrclabel2\" for=\"src7\">{#advimage_dlg.mouseout2}</label></td> \n";
		echo "							<td><input id=\"src7\" name=\"src7\" type=\"file\" value=\"\" /></td> \n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</fieldset>\n";
		echo "				<fieldset>\n";
		echo "					<legend>{#advimage_dlg.misc}</legend>\n";
		echo "					<table class=\"properties\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"idlabel\" for=\"id\">{#advimage_dlg.id}</label></td> \n";
		echo "							<td><input id=\"id\" name=\"id\" type=\"text\" value=\"\" /></td> \n";
		echo "						</tr>\n";
		/*
		Kod jazyka
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"langlabel\" for=\"lang\">{#advimage_dlg.langcode}</label></td> \n";
		echo "							<td><input id=\"lang\" name=\"lang\" type=\"text\" value=\"\" /></td>\n";
		echo "						</tr>\n";
		IMG Mapa
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"usemaplabel\" for=\"usemap\">{#advimage_dlg.map}</label></td> \n";
		echo "							<td><input id=\"usemap\" name=\"usemap\" type=\"text\" value=\"\" /></td> \n";
		echo "						</tr>\n";
		Popis
		echo "						<tr>\n";
		echo "							<td class=\"column1\"><label id=\"longdesclabel\" for=\"longdesc\">{#advimage_dlg.long_desc}</label></td>\n";
		echo "							<td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "									<tr>\n";
		echo "										<td><input id=\"longdesc\" name=\"longdesc\" type=\"text\" value=\"\" /></td>\n";
		echo "										<td id=\"longdesccontainer\">&nbsp;</td>\n";
		echo "									</tr>\n";
		echo "								</table></td> \n";
		echo "						</tr>\n";
		*/
		echo "					</table>\n";
		echo "				</fieldset>\n";
		echo "			</div>\n";
		/*
		Help
		echo "			<div id=\"help_panel\" class=\"panel\">\n";
		echo "				<fieldset>\n";
		echo "				<legend>{#advimage_dlg.help}</legend>\n";
		echo "					<table class=\"properties\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"column1\">{#advimage_dlg.help_text}</td>\n";
	   	echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</fieldset>\n";
		echo "			</div>\n";
		*/
		echo "		</div>\n";
		echo "		<div class=\"mceActionPanel\">\n";
		echo "			<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_GET['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"adminid\" value=\"".$adminid."\">\n";
		echo "			<input type=\"hidden\" name=\"send\" value=\"true\">\n";
		echo "			<input type=\"submit\" id=\"insert\" name=\"insert\" value=\"{#insert}\" />\n";
		echo "			<input type=\"button\" id=\"cancel\" name=\"cancel\" value=\"{#cancel}\" onclick=\"tinyMCEPopup.close();\" />\n";
		echo "		</div>\n";
		echo "	</form>\n";
	}
}
echo "</body> \n";
echo "</html> ";