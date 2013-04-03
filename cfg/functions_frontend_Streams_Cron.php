<?php
if ($_GET['project'] != ""){ // As a link - http://www.magic-live.cz/edencms/functions_frontend_Streams_Cron.php?project=magic
	$project = $_GET['project'];
} elseif ($argv[1] != "") { // As a cron command - edencms/functions_frontend_Streams_Cron.php "magic"
	$project = $argv[1];
} else {
	$project = "";
}

if ($project != ""){ 
	include dirname(__FILE__)."/eden_lang_cz.php";
	include dirname(__FILE__)."/db.".$project.".inc.php";
	include dirname(__FILE__)."/functions_frontend.php";

	new Stream($eden_cfg, 1);
}