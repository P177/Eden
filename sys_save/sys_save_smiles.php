<?php
/* Provereni opravneni */
if ($_GET['action'] == "smiles_add"){
	if (CheckPriv("groups_smiles_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "smiles_edit"){
	if (CheckPriv("groups_smiles_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "smiles_del"){
	if (CheckPriv("groups_smiles_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
/* Výčet povolených tagů */
$allowtags = "";
$smile_code = PrepareForDB($_POST['smile_code']);
$smile_image = PrepareForDB($_POST['smile_image']);
$smile_emotion = PrepareForDB($_POST['smile_emotion']);

if ($_GET['action'] == "smiles_add"){
	$res = mysql_query("SELECT COUNT(*) FROM $db_smiles WHERE smile_code='".$smile_code."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($ar[0] == 0) {
   		mysql_query("INSERT INTO $db_smiles VALUES('','".$smile_code."','".$smile_image."','".$smile_emotion."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	$msg = "add_ok";
}
if ($_GET['action'] == "smiles_edit"){
	mysql_query("UPDATE $db_smiles SET smile_code='".$smile_code."', smile_image='".$smile_image."', smile_emotion='".$smile_emotion."' WHERE smile_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "edit_ok";
}
if ($_GET['action'] == "smiles_del"){
	mysql_query("DELETE FROM $db_smiles WHERE smile_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_smiles.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg);
exit;