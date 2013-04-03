<?php
if ($_GET['lang'] != ""){$lang = $_GET['lang'];} elseif ($_POST['lang'] != ""){$lang = $_POST['lang'];} else {$lang = "cz";}
if ($_GET['project'] != ""){$project = $_GET['project'];}elseif ($_POST['project'] != ""){$project = $_POST['project'];} else {$project = "";}
//Nastaveni nenacitani spatneho jazyka z functions.php
$eden_editor_add_include_lang = true;
$eden_editor_tinymce_plugin_path = "../../../..";
require_once ($eden_editor_tinymce_plugin_path."/functions.php");
require_once ($eden_editor_tinymce_plugin_path."/lang/lang-".$lang.".php");
include ($eden_editor_tinymce_plugin_path."/cfg/db.".$project.".inc.php");

if ($_GET['project'] != ""){$project = $_GET['project'];} else {$project = "";}
include "db.".$project.".inc.php";
if(isset($_GET['getMtGCardByLetters']) && isset($_GET['letters'])){
	$letters = mysql_real_escape_string($_GET['letters']);
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT mtg_card_id, mtg_card_mtg_id, mtg_card_name, mtg_card_set_code FROM "._DB_MTG_CARDS." WHERE mtg_card_name LIKE '".$letters."%'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($inf = mysql_fetch_array($res)){
		echo $inf["mtg_card_id"]."###".$inf["mtg_card_name"]." (".$inf["mtg_card_set_code"].")|";
	}
}