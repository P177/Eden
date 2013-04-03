<?php


if ($_GET['lang'] == ""){$lang = "cz";} else {$lang = $_GET['lang'];}
//Nastaveni nenacitani spatneho jazyka z functions.phps
$eden_editor_add_include_lang = "true";
include_once "./functions.php";
require_once (dirname(__FILE__)."/lang/lang-".$lang.".php");
if ($_SESSION['project'] == "") {
	echo _ERRORPRISTUP;
} else {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"dialog.css\">\n";
	echo "<title>Insert table cup</title>\n";
	echo "</head>\n";
	echo "<body>";
	if ($_POST['send']){
		$table = "<table cellpadding=\"2\" cellspacing=\"2\" width=\"550\" align=\"left\" border=\"0\"><tr><td align=\"middle\" width=\"20\" id=\"eden_cup_table_name\" height=\"17\">&nbsp;</td><td align=\"middle\" id=\"eden_cup_table_name\" height=\"17\">clan name</td><td align=\"middle\" width=\"40\" id=\"eden_cup_table_name\" height=\"17\">win</td><td align=\"middle\" width=\"40\" id=\"eden_cup_table_name\" height=\"17\">draw</td><td align=\"middle\" width=\"40\" id=\"eden_cup_table_name\" height=\"17\">loss</td><td align=\"middle\" width=\"40\" id=\"eden_cup_table_name\" height=\"17\">rounds</td><td align=\"middle\" width=\"40\" id=\"eden_cup_table_name\" height=\"17\">points</td></tr>";
		$i=1;
		while ($i <= $_POST['rows']){
			$table .= "<tr><td align=\"middle\" width=\"20\" id=\"eden_cup_table\" height=\"17\"></td><td align=\"left\" id=\"eden_cup_table\" height=\"17\"></td><td align=\"middle\" width=\"40\" id=\"eden_cup_table\" height=\"17\"></td><td align=\"middle\" width=\"40\" id=\"eden_cup_table\" height=\"17\"></td><td align=\"middle\" width=\"40\" id=\"eden_cup_table\" height=\"17\"></td><td align=\"middle\" width=\"40\" id=\"eden_cup_table\" height=\"17\"><span id=\"eden_cup_table_win\">0</span>/<span id=\"eden_cup_table_lose\">0</span></td><td align=\"middle\" width=\"40\" id=\"eden_cup_table\" height=\"17\"></td></tr>";
			$i++;
		}
		$table .= "</table><br clear=\"all\" \><br \>";
		echo "<script language=\"JavaScript\">\n";
		echo "	// aktivuji ramec s editorem\n";
		echo "	window.opener.frames.".$_POST['input'].".document.body.focus();\n";
		echo "	// do HTML kodu dokumentu v editoru vlozim kod s definici obrazku\n";
		echo "	window.opener.".$_POST['input'].".document.selection.createRange().pasteHTML('".$table."');\n";
		echo "	// zavru formular pro vlozeni obrazku\n";
		echo "	window.close();\n";
		echo "</script>\n";
	} else {
		echo "<style>\n";
		echo "  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };\n";
		echo "</style>\n";
		echo "</head>\n";
		echo "<body topmargin=\"0\" leftmargin=\"0\" style=\"background: threedface; color: windowtext; margin: 10px; border-style: none\" scroll=\"no\">\n";
		echo "<form action=\"add_table_cup.php?project=".$_GET['project']."\" name=\"form1\" enctype=\"multipart/form-data\" method=\"post\">\n";
		echo "<fieldset id=\"fldLayout\" style=\"left: 1em; top: 1em; width: 30em; height: 7em;\">\n";
		echo "<legend id=\"lgdLayout\">"._TABLELOOK."</legend>\n";
		echo "</fieldset>\n";
		echo "<div id=\"divalign\" style=\"left: 4em; top: 4em; width: 9em; height: 1em;\">"._TABLEROWS."</div>\n";
		echo "<select name=\"rows\" style=\"left: 12em; top: 4em; width: 3em; height: 2em;\">\n";
		echo "	<option value=\"1\">1</option>\n";
		echo "	<option value=\"2\">2</option>\n";
		echo "	<option value=\"3\">3</option>\n";
		echo "	<option value=\"4\">4</option>\n";
		echo "	<option value=\"5\">5</option>\n";
		echo "	<option value=\"6\">6</option>\n";
		echo "	<option value=\"7\">7</option>\n";
		echo "	<option value=\"8\">8</option>\n";
		echo "	<option value=\"9\">9</option>\n";
		echo "	<option value=\"10\">10</option>\n";
		echo "	<option value=\"11\">11</option>\n";
		echo "	<option value=\"12\">12</option>\n";
		echo "	<option value=\"13\">13</option>\n";
		echo "	<option value=\"14\">14</option>\n";
		echo "	<option value=\"15\">15</option>\n";
		echo "</select>\n";
		echo "<input type=\"submit\" name=\"send\"  value=\"Insert\" style=\"left: 8em; top: 9em; width: 6em; height: 2em;\">\n";
		echo "<button id=\"btnCancel\" style=\"left: 18em; top: 9em; width: 6em; height: 2em; \" type=\"reset\" tabIndex=\"45\" onClick=\"window.close();\">"._CMN_CANCEL."</button>\n";
		echo "<input type=\"hidden\" name=\"input\" value=\"".$_GET['input']."\">\n";
		echo "</body>\n";
		echo "</html>\n";
	}
}