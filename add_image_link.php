<?php
if ($_GET['lang'] == ""){$lang = "cz";} else {$lang = $_GET['lang'];}
//Nastaveni nenacitani spatneho jazyka z functions.phps
$eden_editor_add_include_lang = "true";
include_once "./functions.php";
require_once (dirname(__FILE__)."/lang/lang-".$lang.".php");
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<head>\n";
echo "<title>"._PICTITLE."</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<style>\n";
echo "  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };\n";
echo "</style>\n";
if ($_GET['mode'] == "tinymce"){
	echo "<script type=\"text/javascript\" src=\"js/tiny_mce/tiny_mce_popup.js\"></script>";
};
echo "</head>\n";
echo "<body style=\"background: threedface; color: windowtext;\">";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	include (dirname(__FILE__)."/cfg/db.".$project.".inc.php");
	// Spojeni s FTP serverem
	$conn_id = @ftp_connect($eden_cfg['ftp_server']) or die (_ERROR_FTP);
	// Prihlaseni pres uzivatelske jmeno a heslo na FTP server
	$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
	ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
	// Zjisteni stavu spojeni
		if ((!$conn_id) || (!$login_result)) {echo _ERROR_FTP; die;}
	// Byl uz formular odeslan?
	if ($_POST['send'] && $_POST['link'] == ""){
		// ziskam extenzi souboru
		$extenze = strtok($_FILES['userfile']['name'] ,".");
		$extenze = strtok(".");
		$extenze2 = strtok($_FILES['userfile2']['name'] ,".");
	   	$extenze2 = strtok(".");
		// generuji nazev souboru
		$time = Cislo();
		$filea = $time.".".strtolower($extenze);
		$fileb = $time."_m.".strtolower($extenze2);
		$new_name = $url_articles.$filea;
		$new_name2 = $url_articles.$fileb;
	   // Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
		$source_file =  $_FILES['userfile']['tmp_name'];
		$source_file2 =  $_FILES['userfile2']['tmp_name'];
		// Vlozi nazev souboru a cestu do konkretniho adresare
		$size = getimagesize($source_file2);
		$destination_file = $ftp_path_articles.$filea;
		$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
		$destination_file = $ftp_path_articles.$fileb;
		$upload2 = ftp_put($conn_id, $destination_file, $source_file2, FTP_BINARY);
		// Zjisteni stavu uploadu
		if (!$upload && !$upload2) {
        	echo _ERROR_UPLOAD;
    	}
		// Uzavreni komunikace se serverem
		ftp_close($conn_id);
		unset($_POST['link']);
		echo "<script>\n
		content ='<a href=\"".$new_name."\" target=\"".$_POST['target']."\"><img src=\"".$new_name2."\" width=\"".$size[0]."\" height=\"".$size[1]."\" hspace=\"".$_POST['horspace']."\" vspace=\"".$_POST['verspace']."\" border=\"".$_POST['borders']."\" align=\"".$_POST['align']."\" alt=\"".$_POST['altext']."\" title=\"".$_POST['altext']."\"></a>';\n
		";
		if ($_POST['mode'] == "eden_e"){
			echo "
			// aktivuji ramec s editorem
			window.opener.frames.".$_POST['input'].".document.body.focus();\n
			// do HTML kodu dokumentu v editoru vlozim kod s definici obrazku\n
			window.opener.".$_POST['input'].".document.selection.createRange().pasteHTML(content);\n
			// zavru formular pro vlozeni obrazku\n
			window.close();\n";
		} else {
			echo "
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.execCommand('mceInsertContent', false, content);\n
			tinyMCEPopup.close();\n";
		}
		echo "</script>\n";
	 	// pokud se odesle formular tak se zacne prenaset soubor a ukladat kam ma
	} elseif ($_POST['send'] && isset($_POST['link'])) {
		
	   // ziskam extenzi souboru
	   $extenze = strtok($_FILES['userfile']['name'] ,".");
	   $extenze = strtok(".");
	   // generuji nazev souboru
	   $userfile_name = Cislo().".".$extenze;
	   $new_name = $url_articles.$userfile_name;
		
	   // Zjisti nazev souboru po ulozeni do docasneho adresare na serveru
			$source_file =  $_FILES['userfile']['tmp_name'];
			// Vlozi nazev souboru a cestu do konkretniho adresare
			$destination_file = $ftp_path_articles.$userfile_name; //
			$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
			 // zjistim velikost obrazku
			 $size = getimagesize($new_name);
			// Zjisteni stavu uploadu
			if (!$upload) {
	        	echo _ERROR_UPLOAD;
	    	}
			// Uzavreni komunikace se serverem
		ftp_close($conn_id);
		echo "<script>\n
		content ='<a href=\"".$_POST['typ'].$_POST['link']."\" target=\"".$_POST['target']."\"><img src=\"".$new_name."\" width=\"".$size[0]."\" height=\"".$size[1]."\" hspace=\"".$_POST['horspace']."\" vspace=\"".$_POST['verspace']."\" border=\"".$_POST['borders']."\" align=\"".$_POST['align']."\" alt=\"".$_POST['altext']."\"></a>';\n
		";
		if ($_POST['mode'] == "eden_e"){
			echo "
			// aktivuji ramec s editorem\n
			window.opener.frames.".$_POST['input'].".document.body.focus();\n
			// do HTML kodu dokumentu v editoru vlozim kod s definici obrazku\n
			window.opener.".$_POST['input'].".document.selection.createRange().pasteHTML(content);\n
			// zavru formular pro vlozeni obrazku\n
			window.close();\n";
		} else {
			echo "
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.execCommand('mceInsertContent', false, content);\n
			tinyMCEPopup.close();\n";
		}
		echo "</script>\n";
	} else { // pokud nic jeste posnai nebylo zobrazi se dialog
		echo " <form name=\"form1\" enctype=\"multipart/form-data\" method=\"post\">\n";
		echo " <div id=userfile style=\"left: 1em; top: 1em; width: 8em; height: 1em; \">"._PICNAME."</div>\n";
		echo " <input type=\"file\" name=\"userfile\" size=\"20\" style=\"left: 11em; top: 1em; width: 25em; height: 2em;\">\n";
		echo " <div id=altext style=\"left: 1em; top: 4em; width: 8em; height: 1em; \">"._PICALTTEXT."</div>\n";
		echo " <input type=\"text\" name=\"altext\" size=\"50\" maxlength=\"50\" style=\"left: 11em; top: 4em; width: 25em; height: 2em;\">\n";
		echo " <div id=\"link\" style=\"left: 1em; top: 7em; width: 8em; height: 1em; \">"._PICLINK."</div>\n";
		echo " <select name=\"typ\" style=\"left: 11em; top: 7em; width: 7em; height: 2em; \">\n";
		echo " 	<option selected value=\"http://\">http://</option>\n";
		echo " 	<option value=\"mailto:\">mailto:</option>\n";
		echo " </select>\n";
		echo " <input type=\"text\" name=\"link\" size=\"50\" maxlength=\"250\" style=\"left: 19em; top: 7em; width: 17em; height: 2em;\">\n";
		echo " <fieldset id=fldLink style=\"left: 1em; top: 10em; width: 35em; height: 8em;\">\n";
		echo " <legend id=lgdSpacing>"._PICPREVIEW."</legend>\n";
		echo " </fieldset>\n";
		echo " <div id=file2 style=\"left: 2em; top: 12em; width: 8em; height: 1em;\">"._PIC2."</div>\n";
		echo " <input type=\"file\" name=\"userfile2\" size=\"20\" style=\"left: 11em; top: 12em; width: 25em; height: 2em;\">\n";
		echo " 	<div id=align style=\"left: 2em; top: 15em; width: 9em; height: 1em; \">"._PICTARGET."</div>\n";
		echo " 	<select name=\"target\" style=\"left: 11em; top: 15em; width: 9em; height: 2em; \">\n";
		echo " 		<option selected value=\"_blank\">_blank</option>\n";
		echo " 		<option value=\"_self\">_self</option>\n";
		echo " 	</select>\n";
		echo " 	<fieldset id=fldLayout style=\"left: 1em; top: 19em; width: 22em; height: 8em;\">\n";
		echo " 	<legend id=lgdSpacing>"._PICLAYOUT."</legend>\n";
		echo " 	</fieldset>\n";
		echo " 	<fieldset id=fldSpacing style=\"left: 24em; top: 19em; width: 12em; height: 8em;\">\n";
		echo " 	<legend id=lgdSpacing>"._PICSPACING."</legend>\n";
		echo " 	</fieldset>\n";
		echo " 	<div id=divalign style=\"left: 2em; top: 21em; width: 9em; height: 1em; \">"._PICALIGN."</div>\n";
		echo " 	<select name=\"align\" style=\"left: 10em; top: 21em; width: 12em; height: 2em; \">\n";
		echo " 		<option selected value=\"\">"._PICALIGNNONE."</option>\n";
		echo " 		<option value=\"left\">"._PICALIGNLEFT."</option>\n";
		echo " 		<option value=\"right\">"._PICALIGNRIGHT."</option>\n";
		echo " 		<option value=\"bottom\">"._PICALIGNDOWN."</option>\n";
		echo " 		<option value=\"top\">"._PICALIGNTOP."</option>\n";
		echo " 		<option value=\"middle\">"._PICALIGNMIDDLE."</option>\n";
		echo " 		<option value=\"texttop\">"._PICALIGNTOPTEXT."</option>\n";
		echo " 		<option value=\"absmiddle\">"._PICALIGNABSMIDDLE."</option>\n";
		echo " 		<option value=\"baseline\">"._PICALIGNBASELINE."</option>\n";
		echo " 	</select>\n";
		echo " 	<div id=divBorder style=\"left: 2em; top: 24em; width: 8em; height: 1em;\">"._PICBORDER."</div>\n";
		echo " 	<input type=\"text\" name=\"borders\" size=\"2\" maxlength=\"3\" style=\"left: 10em; top: 24em; width: 2em; height: 2em; ime-mode: disabled;\">\n";
		echo " 	<div id=divHoriz style=\"left: 25em; top: 21em; width: 5em; height: 1em;\">"._PICHSPACE."</div>\n";
		echo " 	<input type=\"text\" name=\"horspace\" size=\"3\" maxlength=\"3\" style=\"left: 32em; top: 21em; width: 3em; height: 2em; ime-mode: disabled;\">\n";
		echo " 	<div id=divVert style=\"left: 25em; top: 24em; width: 4em; height: 1em;\">"._PICVSPACE."</div>\n";
		echo " 	<input type=\"text\" name=\"verspace\" size=\"3\" maxlength=\"3\" style=\"left: 32em; top: 24em; width: 3em; height: 2em; ime-mode: disabled;\">\n";
		echo " 	<input type=\"submit\" name=\"send\" value=\""._PICINSERT."\" style=\"left: 9em; top: 28em; width: 7em; height: 2em; \">\n";
		if ($_GET['mode'] == "eden_e"){	
			echo " 	<button ID=btnCancel style=\"left: 25em; top: 28em; width: 7em; height: 2em; \" type=reset tabIndex=45 onClick=\"window.close();\">"._CMN_CANCEL."</button>\n";
			echo " 	<input type=\"hidden\" name=\"input\" value=\"".$_GET['input']."\">\n";
		} else {
			echo "<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
			echo "<input type=\"hidden\" name=\"send\" value=\"true\">\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"".$_GET['mode']."\">\n";
		}
		echo " 	</form>";
	}
}
echo "</body>";
echo "</html>";