<?php

if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
//Nastaveni nenacitani spatneho jazyka z functions.php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
require_once ($eden_editor_tinymce_plugin_path."/functions.php");
require_once ($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<head>\n";
echo "<title>"._EDITOR_ADD_FLAG."</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<script type=\"text/javascript\" src=\"../../tiny_mce_popup.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/flags.js\"></script>";
echo "</head>\n";
echo "<body>";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	include ($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");
	// Byl uz formular odeslan?
	if ($_POST['send'] == "true"){ // pokud se odesle formular tak se zacne prenaset soubor a ukladat kam ma
		$flag = $_POST['flag'];
		$new_name = $url_flags.$flag.".gif";
		$res_flag = mysql_query("SELECT country_name FROM $db_country WHERE country_shortname='".mysql_real_escape_string($flag)."'");
		$ar_flag = mysql_fetch_array($res_flag);
		$flag_title = NazevVlajky($flag,$lang);
		echo "<script language=\"JavaScript\" type=\"text/javascript\">\n
			<!-- \n
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {\n
				src : '".$new_name."',\n
				alt : '".$flag_title."',\n
				title : '".$flag_title."',\n
				border : 0\n
			}));\n
			tinyMCEPopup.close();\n
			//-->\n
		</script>\n";
	} else { // pokud nic jeste posnai nebylo zobrazi se dialog
		echo "<div class=\"panel_wrapper\">";
		echo "<fieldset>";
		echo "<legend>"._EDITOR_ADD_FLAG."</legend>\n";
		echo "<form name=\"form1\" method=\"post\">\n";
		echo "<p><select name=\"flag\">\n";
			$res7 = mysql_query("SELECT country_name, country_shortname FROM $db_country ORDER BY country_name ASC");
			while ($ar7 = mysql_fetch_array($res7)){
				echo "<option value=\"".$ar7['country_shortname']."\" "; if ($ar['team1_country'] == $ar7['country_shortname']) {echo " selected";} echo ">".$ar7['country_name']."</option>\n";
			}
		echo "</select></p>\n";
		echo "<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
		echo "<input type=\"hidden\" name=\"project\" value=\"".$_GET['project']."\">\n";
		echo "<input type=\"hidden\" name=\"send\" value=\"true\">\n";
		echo "</fieldset>";
		echo "</div>";
		echo "<div class=\"mceActionPanel\">\n";
		echo "	<input type=\"submit\" id=\"insert\" name=\"insert\" value=\""._EDITOR_ADD_FLAG."\">\n";
		echo "	<input type=\"button\" id=\"cancel\" name=\"cancel\" value=\""._CMN_CANCEL."\" onclick=\"tinyMCEPopup.close();\" />\n";
		echo "</div>";
		echo "</form>\n";
	}
}
echo "</body>";
echo "</html>";
?>
