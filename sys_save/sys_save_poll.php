<?php
/* Provereni opravneni */
if ($_GET['action'] == "poll_add"){
	if (CheckPriv("groups_wp_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "poll_edit"){
	if (CheckPriv("groups_wp_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}elseif ($_GET['action'] == "poll_del"){
	if (CheckPriv("groups_wp_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=nep&page=".$_GET['page']."&hits=".$_GET['hits']);}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}
// Z obsahu promenné body vyjmout nepovolené tagy
$question = PrepareForDB($_POST['question'],1,"",1);
if ($_POST['answer_1'] != ""){
	$answers = PrepareForDB($_POST['answer_1'],1,"",1);
	if ($_POST['answer_2'] != ""){$answers .= "||".PrepareForDB($_POST['answer_2'],1,"",1);}
	if ($_POST['answer_3'] != ""){$answers .= "||".PrepareForDB($_POST['answer_3'],1,"",1);}
	if ($_POST['answer_4'] != ""){$answers .= "||".PrepareForDB($_POST['answer_4'],1,"",1);}
	if ($_POST['answer_5'] != ""){$answers .= "||".PrepareForDB($_POST['answer_5'],1,"",1);}
	if ($_POST['answer_6'] != ""){$answers .= "||".PrepareForDB($_POST['answer_6'],1,"",1);}
	if ($_POST['answer_7'] != ""){$answers .= "||".PrepareForDB($_POST['answer_7'],1,"",1);}
	if ($_POST['answer_8'] != ""){$answers .= "||".PrepareForDB($_POST['answer_8'],1,"",1);}
	if ($_POST['answer_9'] != ""){$answers .= "||".PrepareForDB($_POST['answer_9'],1,"",1);}
	if ($_POST['answer_10'] != ""){$answers .= "||".PrepareForDB($_POST['answer_10'],1,"",1);}
	if ($_POST['answer_11'] != ""){$answers .= "||".PrepareForDB($_POST['answer_11'],1,"",1);}
	if ($_POST['answer_12'] != ""){$answers .= "||".PrepareForDB($_POST['answer_12'],1,"",1);}
	if ($_POST['answer_13'] != ""){$answers .= "||".PrepareForDB($_POST['answer_13'],1,"",1);}
	if ($_POST['answer_14'] != ""){$answers .= "||".PrepareForDB($_POST['answer_14'],1,"",1);}
	if ($_POST['answer_15'] != ""){$answers .= "||".PrepareForDB($_POST['answer_15'],1,"",1);}
}
$language = PrepareForDB($_POST['language'],1,"",1);
if ($_GET['action'] == "poll_add"){
	$res = mysql_query("INSERT INTO $db_poll_questions VALUES ('','$question','$answers','".$_SESSION['loginid']."','$language',".(integer)$_POST['poll_for'].",NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "poll_add_ok";
}
if ($_GET['action'] == "poll_edit"){
	$res = mysql_query("UPDATE $db_poll_questions SET poll_questions_question='$question', poll_questions_answers='$answers', poll_questions_lang='".$language."', poll_questions_for=".(integer)$_POST['poll_for']." WHERE poll_questions_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "poll_edit_ok";
}
if ($_GET['action'] == "poll_del"){
	$res = mysql_query("DELETE FROM $db_poll_questions WHERE poll_questions_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "poll_del_ok";
}
if ($_GET['action'] == "poll_del_data"){
	$myres = mysql_query("DELETE FROM $db_poll_answers WHERE poll_answers_pid=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$msg = "poll_del_ok";
}
header ("Location: ".$eden_cfg['url_cms']."modul_poll.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg."&page=".$_GET['page']."&hits=".$_GET['hits']);
exit;