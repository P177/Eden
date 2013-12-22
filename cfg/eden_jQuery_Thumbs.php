<?php
//Nastaveni nenacitani spatneho jazyka z functions.phps

require_once("eden_init.php");
$cid = AGet($_POST,'id');
$_POST['action'] = AGet($_POST,'action');
$_POST['lang'] = AGet($_POST,'lang');
$project = AGet($_POST,'project');
$eden_editor_add_include_lang = "true";

include_once("db.".$project.".inc.php");
include_once("sessions.php");
$_SESSION['loginid'] = AGet($_SESSION,'loginid');
include_once("functions_frontend.php");
include_once("eden_lang_".$_POST['lang'].".php");
/***********************************************************************************************************
*
*		getAllThumbs
*
*		Returns an array whose first element is thumb_up and the second one is thumb_down
*
***********************************************************************************************************/
function getAllThumbs($id) {
	
	global $db_comments;
	
	$thumbs = array();
	$res = mysql_query("SELECT comment_thumbs_up, comment_thumbs_down FROM $db_comments WHERE comment_id = ".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* id found in the table */
	if(mysql_num_rows($res)==1){
		$row = mysql_fetch_array($res);
		$thumbs[0] = $row['comment_thumbs_up'];
		$thumbs[1] = $row['comment_thumbs_down'];
	}
	return $thumbs;
}

function getEffectiveThumbs($id){
	/*	Returns an integer */
	$thumbs = getAllThumbs($id);
	$effectiveThumb = $thumbs[0] - $thumbs[1];
	return $effectiveThumb;
}

/* Get the current votes */
$cur_votes = getAllThumbs($cid);

/* Ok, now update the thumbs */

/* Thumbing up */
$res_comm = mysql_query("SELECT comment_reg_user_comm FROM $db_comments WHERE comment_id = ".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar_comm = mysql_fetch_array($res_comm);
$res_thumbs = mysql_query("SELECT * FROM $db_thumbs WHERE thumb_admin_id=".(integer)$_SESSION['loginid']." AND thumb_comment_id=".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
if(mysql_num_rows($res_thumbs)==0){
	if($_POST['action'] == "thumb_up"){
		$res_t = mysql_query("INSERT INTO $db_thumbs VALUES ('','".(integer)$_SESSION['loginid']."','".(integer)$cid."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_c = mysql_query("UPDATE $db_comments SET comment_thumbs_up = comment_thumbs_up + 1 WHERE comment_id = ".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_a = mysql_query("UPDATE $db_admin SET admin_thumbs_up_given = admin_thumbs_up_given + 1 WHERE admin_id = ".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_a2 = mysql_query("UPDATE $db_admin SET admin_thumbs_up_received = admin_thumbs_up_received + 1 WHERE admin_id = ".(integer)$ar_comm['comment_reg_user_comm']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	/* Thumbing down */
	} elseif ($_POST['action'] == "thumb_down"){
		$res_t = mysql_query("INSERT INTO $db_thumbs VALUES ('','".(integer)$_SESSION['loginid']."','".(integer)$cid."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_c = mysql_query("UPDATE $db_comments SET comment_thumbs_down = comment_thumbs_down + 1 WHERE comment_id = ".(integer)$cid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_a = mysql_query("UPDATE $db_admin SET admin_thumbs_down_given = admin_thumbs_down_given + 1 WHERE admin_id = ".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_a2 = mysql_query("UPDATE $db_admin SET admin_thumbs_down_received = admin_thumbs_down_received + 1 WHERE admin_id = ".(integer)$ar_comm['comment_reg_user_comm']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}
/* Thumbing done */
if($res_t && $res_c && $res_a && $res_a2){
	$thumbs = getAllThumbs($cid);
	if($_POST['action'] == "thumb_up"){
		echo "<div class=\"thumbs_count_up\">(+".$thumbs[0].")</div>";
	} else {
		echo "<div class=\"thumbs_count_down\">(-".$thumbs[1].")</div>";
	}
/* Thumbing failed */
} elseif(!$res){
	echo "Failed! ";
}