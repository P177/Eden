<?php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}


//Nastaveni nenacitani spatneho jazyka z functions.php
//require_once ($eden_editor_tinymce_plugin_path."/functions.php");
require_once ($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<head>\n";
echo "<title>"._ADD_EMBED_TITLE."</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<script type=\"text/javascript\" src=\"../../tiny_mce_popup.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/eflash.js\"></script>";
echo "<style>\n";
echo "  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };\n";
echo "</style>\n";
echo "</head>\n";
echo "<body>";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	// Byl uz formular odeslan?
	if ($_POST['send']){
		echo "<script>\n
			content ='<div style=\"text-align:".$_POST['embed_align'].";\">".htmlspecialchars_decode($_POST['embed_link'],ENT_QUOTES)."</div>';\n
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.execCommand('mceInsertContent', false, content);\n
			tinyMCEPopup.close();\n
			</script>\n
		";
 	} else { // pokud nic jeste poslano nebylo zobrazi se dialog
		echo "	<form name=\"form1\" method=\"post\">\n";
		echo "	<div style=\"left: 1em; top: 2em; \"><strong>"._ADD_EMBED_LINK."</strong><br>\n";
		echo "	<textarea name=\"embed_link\" cols=\"85\" rows=\"10\"></textarea></div>\n";
		echo "	<div style=\"left: 1em; top: 16em;\"><strong>"._ADD_EMBED_ALIGN."</strong><br>\n";
		echo "	<select name=\"embed_align\">\n";
		echo "		<option selected value=\"left\">left</option>\n";
		echo "		<option value=\"center\">center</option>\n";
		echo "		<option value=\"right\">right</option>\n";
		echo "	</select></div>\n";
		echo "	<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
		echo "	<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
		echo "	<input type=\"hidden\" name=\"send\" value=\"true\">\n";
		echo "<div class=\"mceActionPanel\">\n";
		echo "	<input type=\"submit\" id=\"insert\" name=\"insert\" value=\""._CMN_INSERT."\" style=\"left: 1em; top: 20em; \">\n";
		echo "	<input type=\"button\" id=\"cancel\" name=\"cancel\" value=\""._CMN_CANCEL."\" onclick=\"tinyMCEPopup.close();\" style=\"left: 20em; top: 20em;\" />\n";
		echo "</div>";
		echo "</form>";
	}
}
echo "</body>";
echo "</html>";