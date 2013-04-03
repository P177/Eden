<?php
//Nastaveni nenacitani spatneho jazyka z functions.phps
if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = $_SESSION['project'];}
require_once("db.".$project.".inc.php");
require_once("functions_frontend.php");
require_once("eden_lang_".$_GET['lang'].".php");
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	$res_setup = mysql_query("SELECT setup_accessories_popup_logo FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$eden_project_skin = CheckSkin();
	if ($eden_project_skin != ""){ $eden_project_skin .= "/";}
	
	if ($_GET['action'] == "spec_t"){
		$link = $eden_cfg['url']."index.php?action=spec_t&amp;gen=&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;did=".$_GET['id']."&amp;project=".$_GET['project'];
		$err_recommend_to_empty_email = _ERR_RECOMMEND_TO_SHOP_EMPTY_EMAIL;
		$err_recommend_to_bad_email = _ERR_RECOMMEND_TO_SHOP_BAD_EMAIL;
	} elseif ($_GET['action'] == "clanek"){
		$link = $eden_cfg['url']."index.php?action=clanek&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$_GET['id']."&amp;page_mode=".$_GET['page_mode']."&amp;project=".$_GET['project'];
		$err_recommend_to_empty_email = _ERR_RECOMMEND_TO_ARTICLES_EMPTY_EMAIL;
		$err_recommend_to_bad_email = _ERR_RECOMMEND_TO_ARTICLES_BAD_EMAIL;
	} elseif ($_GET['action'] == "komentar"){
		if ($_GET['modul'] == "news"){
			$link = $eden_cfg['url']."index.php?action=komentar&amp;modul=news&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$_GET['id']."&amp;page_mode=".$_GET['page_mode']."&amp;project=".$_GET['project'];
			$err_recommend_to_empty_email = _ERR_RECOMMEND_TO_ACT_EMPTY_EMAIL;
			$err_recommend_to_bad_email = _ERR_RECOMMEND_TO_ACT_BAD_EMAIL;
		} elseif ($_GET['modul'] == "articles"){
			$link = $eden_cfg['url']."index.php?action=komentar&amp;modul=articles&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$_GET['id']."&amp;page_mode=".$_GET['page_mode']."&amp;project=".$_GET['project'];
			$err_recommend_to_empty_email = _ERR_RECOMMEND_TO_ARTICLES_EMPTY_EMAIL;
			$err_recommend_to_bad_email = _ERR_RECOMMEND_TO_ARTICLES_BAD_EMAIL;
		}
	} else {
		echo "<script type=\"text/javascript\">\n";
		echo "window.close();\n";
		echo "</script>\n";
		
	}
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<title>".$eden_cfg['project_name']." - "._RECOMMEND_TO_FRIEND."</title>\n";
	echo "	<link href=\"".$eden_cfg['url'].$eden_cfg['url_skins'].$eden_project_skin."eden-common.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">\n";
	echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "	<meta http-equiv=\"content-language\" content=\"".$_GET['lang']."\">\n";
	echo "</head>\n";
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n";
	echo "	function CheckRecommendForm(formular) {\n";
	echo "		re = new RegExp(\"^[^@\\.]([\\.]?[^@\\.]+)*@([^@\\.]+[\\.]{1}[^@\\.]+)+$\");\n";
	echo "		if (formular.recommend_to_email.value == \"\"){\n";
	echo "			alert (\"".$err_recommend_to_empty_email."\");\n";
	echo "			return false;\n";
	echo "		} else if (!re.test(formular.recommend_to_email.value)){\n";
	echo "			alert (\"".$err_recommend_to_bad_email."\");\n";
	echo "			return false;\n";
	echo "		} else if (formular.recommend_from_name.value == \"\"){\n";
	echo "			alert (\""._ERR_RECOMMEND_EMPTY_NAME."\");\n";
	echo "			return false;\n";
	echo "		} else if (formular.recommend_from_email.value == \"\"){\n";
	echo "			alert (\""._ERR_RECOMMEND_FROM_EMPTY_EMAIL."\");\n";
	echo "			return false;\n";
	echo "		} else if (!re.test(formular.recommend_from_email.value)){\n";
	echo "			alert (\""._ERR_RECOMMEND_FROM_BAD_EMAIL."\");\n";
	echo "			return false;\n";
	echo "		} else {\n";
	echo "			setTimeout('window.close()',200);\n";
	echo "			return true;\n";
	echo "		}\n";
	echo "	}\n";
	echo "	//-->\n";
	echo "</script>\n";
	echo "<body leftmargin=\"0\" rightmargin=\"0\" marginheight=\"0\" marginwidth=\"0\" topmargin=\"0\"  bottommargin=\"0\">\n";
	echo "<style>\n";
	echo "#eden_popup_content,#eden_popup_thanks {margin:-20px 10px 10px 20px;text-align:left;width:400px;}\n";
	echo "#eden_popup_desc{margin-bottom:5px}}\n";
	echo "#eden_popup_link{}\n";
	echo "#eden_popup_form{margin-top:25px}\n";
	echo "#eden_popup_form_table{margin-top:25px}\n";
	echo ".eden_popup_form_label{text-align:right;font-weight:bold;font-size:11px;}\n";
	echo "</style>\n";
	echo "<div id=\"eden_popup_window\">\n";
	echo "	<div id=\"eden_popup_window_head\">"; if($ar_setup['setup_accessories_popup_logo'] != ""){ echo "<img src=\"".$url_project_images.$ar_setup['setup_accessories_popup_logo']."\" alt=\"".$eden_cfg['project_name']."\" title=\"".$eden_cfg['project_name']."\" />"; } else {echo "<h1 style=\"margin:20px 0px 0px 20px;\">".$eden_cfg['project_name']."</h1>";} echo "</div>\n";
	echo "	<div id=\"eden_popup_content\" >\n";
	echo "	  <form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=".$_GET['action']."&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id=".$_GET['id']."&amp;page_mode=".$_GET['page_mode']."&amp;modul=".$_GET['modul']."&amp;project=".$_GET['project']."\" method=\"post\" enctype=\"multipart/form-data\" name=\"formular\" onsubmit=\"return CheckRecommendForm(this);\">\n";
	echo "	  <input type=\"hidden\" name=\"mode\" value=\"rtf\">\n";
	echo "		<div id=\"eden_popup_desc\">"._RECOMMEND_INFO_1."</div>\n";
	echo "		<div id=\"eden_popup_link\"><a href=\"".$link."\" target=\"_blank\">".$link."</a><br><br>"._RECOMMEND_INFO_2."</div>\n";
	echo "		<div id=\"eden_popup_form\">\n";
	echo "		   <table id=\"eden_popup_form_table\" cellspacing=\"5\">\n";
	echo "			   <tr><td class=\"eden_popup_form_label\">"._RECOMMEND_FORM_TO_EMAIL."</td><td><input name=\"recommend_to_email\" type=\"text\" size=\"30\" style=\"width: 250px\"></td></tr>\n";
	echo "			   <tr><td class=\"eden_popup_form_label\">"._RECOMMEND_FORM_FROM_NAME."</td><td><input name=\"recommend_from_name\" type=\"text\" value=\"\" size=\"30\" maxlength=\"49\" style=\"width: 250px\"></td></tr>\n";
	echo "			   <tr><td class=\"eden_popup_form_label\">"._RECOMMEND_FORM_FROM_EMAIL."</td><td><input name=\"recommend_from_email\" type=\"text\" value=\"\" size=\"30\" maxlength=\"49\" style=\"width: 250px\"></td></tr>\n";
	echo "			   <tr><td class=\"eden_popup_form_label\">"._RECOMMEND_FORM_SUBJECT."</td><td><input name=\"recommend_subject\" type=\"text\" size=\"30\" maxlength=\"500\" style=\"width: 250px\"></td></tr>\n";
	echo "	           <tr valign=\"top\"><td class=\"eden_popup_form_label\">"._RECOMMEND_FORM_MESSAGE."</td><td><textarea name=\"recommend_msg\" cols=\"30\" rows=\"5\" style=\"width: 250px\"></textarea></td></tr>\n";
	echo "		   	   <tr ><td></td><td><input class=\"eden_button\" type=\"submit\" value=\""._CMN_SUBMIT."\">&nbsp;&nbsp;<input class=\"eden_button\" type=\"button\" value=\""._CMN_CANCEL."\" onClick=\"window.close();\"></td></tr>\n";
	echo "		   </table>\n";
	echo "		</div>\n";
	echo " 	 </form>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "</body>\n";
	echo "</html>";
}