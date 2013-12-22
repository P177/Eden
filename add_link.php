<?php


if ($_GET['lang'] == ""){$lang = "cz";} else {$lang = $_GET['lang'];}
//Nastaveni nenacitani spatneho jazyka z functions.phps
$eden_editor_add_include_lang = "true";
include_once "./functions.php";
require_once (dirname(__FILE__)."/lang/lang-".$lang.".php");
if ($_GET['project'] != ""){$project = $_GET['project'];} elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>"._LINK_TITLE."</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<style>\n";
echo "  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };\n";
echo "</style>\n";
echo "</head>\n";
echo "<body style=\"background: threedface; color: windowtext;\">";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	// Byl uz formular odeslan?
	if ($_POST['send']){ // pokud se odesle formular tak se zacne prenaset soubor a ukladat kam ma
		if($_POST['action'] == "clanwars_comment"){
			$link = htmlspecialchars($_POST['link'], ENT_QUOTES);
			$link = StripInetService($link);
			echo "<script laguage=\"JavaScript\">\n";
			echo "<!--\n";
			if (preg_match("/MSIE/i",$_SERVER["HTTP_USER_AGENT"]) && !preg_match("/Mac/i",$_SERVER["HTTP_USER_AGENT"])) {
				echo "		window.opener.document.getElementById('".$_POST['input']."').focus();\n";
				echo "		window.opener.document.selection.createRange().text = '[3".$_POST['typ'].$link."][3".$_POST['target']."][3".$_POST['linkalt']."]';\n";
				echo "		window.close();\n";
			} else {
				echo "	function mozWrap(open, close) {\n";
				echo "		var txtarea = window.opener.document.getElementById('".$_POST['input']."');\n";
				echo "		var selLength = txtarea.textLength;\n";
				echo "		var selStart = txtarea.selectionStart;\n";
				echo "		var selEnd = txtarea.selectionEnd;\n";
				echo "		if (selEnd == 1 || selEnd == 2) {\n";
				echo "			selEnd = selLength;\n";
				echo "		}\n";
				echo "		var s1 = (txtarea.value).substring(0, selStart);\n";
				echo "		var s2 = (txtarea.value).substring(selStart, selEnd)\n";
				echo "		var s3 = (txtarea.value).substring(selEnd, selLength);\n";
				echo "		txtarea.value = s1 + open + s2 + close + s3;\n";
				echo "		return;\n";
				echo "	}\n";
				echo "	mozWrap(\"[3".$_POST['typ'].$link."][3".$_POST['target']."][3".$_POST['linkalt']."]\", \" \");\n";
				echo "	window.close();\n";
			}
			echo "\n";
			echo "//-->\n";
			echo "</script>";
		} else {
			$link = StripInetService($_POST['link']);
			echo "<!-- do prvku textarea odkaz vo formate HTML nikdy nevlozis ...\n";
			echo "jedine mozes atribut value zmenit na pozadovanu hodnotu standardnym\n";
			echo "sposobom element.value = 'my_custom_string'. -->\n";
			echo "<script>\n";
			echo "// aktivuji ramec s editorem\n";
			echo "window.opener.frames.".$_POST['input'].".document.body.focus();\n";
			echo "// do HTML kodu dokumentu v editoru vlozim kod s definici obrazku\n";
			if ($_POST['action'] == "clanek"){ echo "window.opener.".$_POST['input'].".document.selection.createRange().pasteHTML('<a href=\"".$_POST['article_url']."?action=clanek&amp;id=".$_POST['id']."\" target=\"".$_POST['target']."\">".$_POST['linkalt']."</a>')"; }
			if ($_POST['action'] == "hyperlink"){echo "window.opener.".$_POST['input'].".document.selection.createRange().pasteHTML('<a href=\"".$_POST['typ'].$link."\" target=\"".$_POST['target']."\">".$_POST['linkalt']."</a>')"; }
			echo "// zavru formular pro vlozeni obrazku\n";
			echo "window.close();\n";
			echo "</script>\n";
			echo "</body>\n";
			echo "</html>";
		}
 	} else { // pokud nic jeste poslano nebylo zobrazi se dialog 
		echo "<!-- <script>\n";
		echo "var s = window.opener.".$input.".document.selection.createRange();\n";
		echo "document.write(s);\n";
		echo "</script> -->\n";
		echo "	<form name=\"form1\" action=\"add_link.php\" method=\"post\">";
		if ($_GET['action'] == "clanek"){
			echo "<div id=divAltText style=\"left: 1em; top: 1em; width: 8em; height: 1em; \">"._LINK_ID."</div>\n";
			echo "<input type=\"text\" name=\"id\" size=\"20\" maxlength=\"9\" style=\"left: 11em; top: 1em; width: 25em; height: 2em;\">\n";
			echo "<div id=divAltText style=\"left: 1em; top: 4em; width: 8em; height: 1em; \">"._LINK_ALT."</div>\n";
			echo "<input type=\"text\" name=\"linkalt\" size=\"50\" maxlength=\"250\" style=\"left: 11em; top: 4em; width: 25em; height: 2em;\">\n";
			echo "<div id=align style=\"left: 2em; top: 7em; width: 9em; height: 1em; \">"._LINK_TARGET."</div>\n";
			echo "<select name=\"target\" style=\"left: 11em; top: 7em; width: 9em; height: 2em; \">\n";
			echo "	<option selected value=\"_blank\">_blank</option>\n";
			echo "	<option value=\"_self\">_self</option>\n";
			echo "	<option value=\"_self\">_parent</option>\n";
			echo "</select>\n";
			echo "<input type=\"submit\" name=\"send\" value=\""._LINK_INSERT."\" style=\"left:18em; top: 10em; width: 7em; height: 2em;\">\n";
			echo "<button type=\"reset\" id=\"btnCancel\" style=\"left: 28em; top: 10em; width: 7em; height: 2em;\" tabIndex=45 onClick=\"window.close();\">"._CMN_CANCEL."</BUTTON>";
		} else {
			echo "<div id=divAltText style=\"left: 1em; top: 1em; width: 8em; height: 1em; \">"._LINK_TYPE."</div>\n";
			echo "<select name=\"typ\" style=\"left: 11em; top: 1em; width: 15em; height: 2em;\">\n";
			echo "	<option value=\"file://\">file://</option>\n";
			echo "	<option value=\"ftp://\">ftp://</option>\n";
			echo "	<option value=\"http://\" selected>http://</option>\n";
			echo "	<option value=\"https://\">https://</option>\n";
			echo "	<option value=\"irc://\">irc://</option>\n";
			echo "	<option value=\"mailto:\">mailto:</option>\n";
			echo "</select>\n";
			echo "<div id=divLink style=\"left: 1em; top: 4em; width: 8em; height: 1em; \">"._LINK_LINK."</div>\n";
			echo "<input type=\"text\" name=\"link\" size=\"50\" maxlength=\"250\" style=\"left: 11em; top: 4em; width: 25em; height: 2em;\">\n";
			echo "<div id=divAltText style=\"left: 1em; top: 7em; width: 8em; height: 1em; \">"._LINK_ALT."</div>\n";
			echo "<input type=\"text\" name=\"linkalt\" size=\"50\" maxlength=\"250\" style=\"left: 11em; top: 7em; width: 25em; height: 2em;\">\n";
			echo "<div id=align style=\"left: 2em; top: 10em; width: 9em; height: 1em; \">"._LINK_TARGET."</div>\n";
			echo "<select name=\"target\" style=\"left: 11em; top: 10em; width: 9em; height: 2em; \">\n";
			echo "	<option selected value=\"_blank\">_blank</option>\n";
			echo "	<option value=\"_self\">_self</option>\n";
			echo "	<option value=\"_parent\">_parent</option>\n";
			echo "</select>\n";
			echo "<input type=\"submit\" name=\"send\" value=\""._LINK_INSERT."\" style=\"left:18em; top: 14em; width: 7em; height: 2em;\">\n";
			echo "<button type=\"reset\" id=\"btnCancel\" style=\"left: 28em; top: 14em; width: 7em; height: 2em;\" tabIndex=45 onClick=\"window.close();\">"._CMN_CANCEL."</BUTTON>";
		}
		echo "<input type=\"hidden\" name=\"input\" value=\"".$_GET['input']."\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\""; if ($_GET['action'] == "clanek"){echo "clanek";} elseif ($_GET['action'] == "clanwars_comment"){echo "clanwars_comment";} else {echo "hyperlink";} echo "\">\n";
		echo "<input type=\"hidden\" name=\"project\" value=\"".$project."\">\n";
		echo "</form>";
	}
}
echo "</body>\n";
echo "</html>";