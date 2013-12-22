<?php
/* Provereni opravneni */
if (CheckPriv("groups_setup_edit") <> 1) {header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']."&msg=nep");}

$clan_setup_update = mysql_query("UPDATE $db_clan_setup SET
clan_setup_name='".mysql_real_escape_string($_POST['clan_setup_name'])."',
clan_setup_tag='".mysql_real_escape_string($_POST['clan_setup_tag'])."', 
clan_setup_web='".mysql_real_escape_string($_POST['clan_setup_web'])."',
clan_setup_irc='".mysql_real_escape_string($_POST['clan_setup_irc'])."', 
clan_setup_country_id=".(integer)$_POST['clan_setup_country']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
if ($clan_setup_update){
	$msg = "clan_setup_update_ok";
} else {
	$msg = "clan_setup_update_er";
}

header ("Location: ".$eden_cfg['url_cms']."modul_clan_setup.php?project=".$_SESSION['project']."&msg=".$msg);
exit;