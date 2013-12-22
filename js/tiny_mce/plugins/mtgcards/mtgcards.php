<?php

if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
//Nastaveni nenacitani spatneho jazyka z functions.php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
require_once ($eden_editor_tinymce_plugin_path."/functions.php");
require_once ($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
include ($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<head>\n";
echo "<title>"._EDITOR_MTG_ADD."</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<script type=\"text/javascript\" src=\"../../tiny_mce_popup.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/mtgcards.js\"></script>";
echo "<script type=\"text/javascript\" src=\"js/ajax.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"js/ajax-dynamic-list.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"../../../../css/eden.css\" type=\"text/css\">";?>
<style type="text/css">
<!--
/**************************************************************
AJAX NAPOVEDA
**************************************************************/
/* Big box with list of options */
#eden_ajax_listOfOptions{
  position:absolute;  /* Never change this one */
  width:250px;  /* Width of box */
  height:150px;  /* Height of box */
  overflow:auto;  /* Scrolling features */
  border:1px solid #000000;  /* Dark green border */
  background-color:#FFFFFF;  /* White background color */
  text-align:left;
  font-size:1em;
  z-index:100;
}
#eden_ajax_listOfOptions div{  /* General rule for both .optionDiv and .optionDivSelected */
  margin:1px;
  padding:1px;
  cursor:pointer;
  font-size:12px;
}
#eden_ajax_listOfOptions .eden_optionDiv{  /* Div for each item in list */
  
}
#eden_ajax_listOfOptions .eden_optionDivSelected{ /* Selected item in the list */
  background-color:#006fbf;
  color:#FFFFFF;
}
#eden_ajax_listOfOptions_iframe{
  background-color:#F00;
  position:absolute;
  z-index:5;
}
-->
</style>
<?php
echo "</head>\n";
echo "<body>";
if ($project == "") {
	echo _ERRORPRISTUP;
} else {
	// Byl uz formular odeslan?
	if ($_POST['send'] == "true"){ // pokud se odesle formular tak se zacne prenaset soubor a ukladat kam ma
		$card_id = $_POST['id'];
		$card_name = str_ireplace("'", "\'", $_POST['word']);
		
		echo "<script language=\"JavaScript\" type=\"text/javascript\">\n
			<!-- \n
			var ed = tinyMCEPopup.editor, dom = ed.dom;\n
			tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('a', {href : '".$eden_cfg['url']."index.php?action=mtg_show_card&card_id=".$card_id."', class : 'eden_hintbox_trigger', rel : '".$project.",".$lang.",mtgcard,".$card_id."'}, '".$card_name."'));\n
			tinyMCEPopup.close();\n
			//-->\n
		</script>\n";
	} else { // pokud nic jeste posnai nebylo zobrazi se dialog
	   	echo "<form name=\"form1\" method=\"post\">\n";
		echo "<div class=\"panel_wrapper\">";
		echo "	<fieldset>";
		echo "	<legend>"._EDITOR_MTG_ADD."</legend>\n";
	   	echo "	<p>";
		echo "		<input type=\"text\" id=\"word\" name=\"word\" value=\"\" size=\"40\" autocomplete=\"off\" onkeyup=\"ajax_showOptions(this,'getMtGCardByLetters=1&amp;project=".$_SESSION['project']."',event)\">\n";
		echo "		<input type=\"hidden\" id=\"word_hidden\" name=\"id\">";
		echo "	</p>\n";
		echo "	<input type=\"hidden\" name=\"lang\" value=\"".$_GET['lang']."\">\n";
		echo "	<input type=\"hidden\" name=\"project\" value=\"".$_GET['project']."\">\n";
		echo "	<input type=\"hidden\" name=\"send\" value=\"true\">\n";
		echo "	</fieldset>";
		echo "</div>";
		echo "<div class=\"mceActionPanel\">\n";
		echo "	<input type=\"submit\" class=\"eden_button\" name=\"insert\" value=\""._EDITOR_MTG_ADD."\">\n";
		echo "	<input type=\"button\" class=\"eden_button_no\" name=\"cancel\" value=\""._CMN_CANCEL."\" onclick=\"tinyMCEPopup.close();\" />\n";
		echo "</div>";
		echo "</form>\n";
	}
}
echo "</body>";
echo "</html>";
