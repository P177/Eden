<?php
/* Provereni opravneni */
if ($_GET['action'] == "add_todo"){
	if (CheckPriv("groups_todo_add") <> 1) {header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_todo"){
	if (CheckPriv("groups_todo_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_todo"){
	if (CheckPriv("groups_todo_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "add_todo_category"){
	if (CheckPriv("groups_todo_category_add") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "edit_todo_category"){
	if (CheckPriv("groups_todo_category_edit") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}elseif ($_GET['action'] == "del_todo_category"){
	if (CheckPriv("groups_todo_category_del") <> 1){header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=nep");}
}else{
	header ("Location: ".$eden_cfg['url_cms']."sys_statistics.php?project=".$_SESSION['project']);
}

/* Výčet povolených tagů */
$allowtags = "";
$todo_name = PrepareForDB($_POST['todo_name']);
$todo_description = PrepareForDB($_POST['todo_description']);
$todo_category_name = PrepareForDB($_POST['todo_category_name']);
$deadline = $_POST['todo_date_deadline'];
$todo_date_deadline = $deadline[6].$deadline[7].$deadline[8].$deadline[9].$deadline[5].$deadline[3].$deadline[4].$deadline[2].$deadline[0].$deadline[1].' '.$_POST['todo_deadline_h'].':'.$_POST['todo_deadline_m'].':00';
if ($_GET['action'] == "add_todo" || $_GET['action'] == "edit_todo" || $_GET['action'] == "del_todo"){
	if ($_GET['action'] == "add_todo"){
		mysql_query("INSERT INTO $db_todo VALUES('','".(integer)$_SESSION['loginid']."','".(integer)$_POST['todo_category']."','',NOW(),'','','','".$todo_date_deadline."','".$todo_name."','".$todo_description."','".(integer)$_POST['todo_priority']."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "add_ok";
	}
	if ($_GET['action'] == "edit_todo"){
		mysql_query("UPDATE $db_todo SET todo_name='".$todo_name."', todo_description='".$todo_description."', todo_date_deadline='".$todo_date_deadline."', todo_priority=".(integer)$_POST['todo_priority']." WHERE todo_id=".(integer)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "edit_ok";
	}
	if ($_GET['action'] == "del_todo"){
		mysql_query("DELETE FROM $db_todo WHERE todo_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$msg = "del_ok";
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=showmain&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}

if ($_GET['action'] == "add_todo_category" || $_GET['action'] == "edit_todo_category" || $_GET['action'] == "del_todo_category"){
	if ($_GET['action'] == "add_todo_category"){
		mysql_query("INSERT INTO $db_todo_category VALUES('','".$todo_category_name."','')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$res_id = mysql_query("SELECT LAST_INSERT_ID()") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_id = mysql_fetch_array($res_id);
		$cat_id = $ar_id[0];
		$msg = "add_ok";
	}
	if ($_GET['action'] == "edit_todo_category"){
		mysql_query("UPDATE $db_todo_category SET todo_category_name='".$todo_category_name."', todo_category_image='".$todo_category_image."' WHERE todo_category_id=".(integer)$_GET['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$cat_id = $_GET['cid'];
		$msg = "edit_ok";
	}
	if ($_GET['action'] == "del_todo_category"){
		mysql_query("DELETE FROM $db_todo_category WHERE todo_category_id=".(integer)$_GET['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$cat_id = $_GET['cid'];
		$msg = "del_ok";
	}
	/* Nahrani obrazku Avatara */
	if ($_FILES['todo_category_image']['name'] != ""){
		/* Spojeni s FTP serverem */
		$conn_id = ftp_connect($eden_cfg['ftp_server']);
		/* Prihlaseni pres uzivatelske jmeno a heslo na FTP server */
		$login_result = ftp_login($conn_id, $eden_cfg['ftp_user_name'], $eden_cfg['ftp_user_pass']);
		ftp_pasv($conn_id, $eden_cfg['ftp_passive_mode']);
		/* Zjisteni stavu spojeni */
		if ((!$conn_id) || (!$login_result)){
			header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=add_todo_category&project=".$_SESSION['project']."&msg=no_ftp");
			exit;
		}
		if (($todo_category_image = getimagesize($_FILES['todo_category_image']['tmp_name'])) != false){
			/* Zjistime zda neni soubor jineho typu nez je povoleno a pokud ne priradime extenzi */
			if ($todo_category_image[2] == 1){
				$extenze = ".gif";
			} elseif ($todo_category_image[2] == 2){
				$extenze = ".jpg";
			} elseif ($todo_category_image[2] == 3){
				$extenze = ".png";
			} else {
				header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=add_todo_category&project=".$_SESSION['project']."&msg=wft");
				exit;
			}
			/* Zjistime zda neni obrazek mensi, nez je povoleno */
			if ($todo_category_image[0] < GetSetupImageInfo("todo","width") || $todo_category_image[1] < GetSetupImageInfo("todo","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=edit_todo_category&project=".$_SESSION['project']."&cid=".$_GET['cid']."&msg=ts");
				exit;
			/* Zjistime zda neni obrazek vetsi, nez je povoleno */
			} elseif ($todo_category_image[0] > GetSetupImageInfo("todo","width") || $todo_category_image[1] > GetSetupImageInfo("todo","height")){
				header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=edit_todo_category&project=".$_SESSION['project']."&cid=".$_GET['cid']."&msg=tb");
				exit;
			/* Zjistime zda neni soubor vetsi nez je povoleno */
			} elseif ($_FILES['todo_category_image']['size'] > GetSetupImageInfo("todo","filesize")){
				header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=edit_todo_category&project=".$_SESSION['project']."&cid=".$_GET['cid']."&msg=ftb");
				exit;
			} else {
				/* Zjisti nazev souboru po ulozeni do docasneho adresare na serveru */
				$source_file = $_FILES['todo_category_image']['tmp_name'];
				$userfile_name = $cat_id.strtolower($extenze);
				
				/* Vlozi nazev souboru a cestu do konkretniho adresare */
				$destination_file = $ftp_path_todo_category.$userfile_name;
				$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);
				/* Zjisteni stavu uploadu */
				if (!$upload){
					header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=add_todo_category&project=".$_SESSION['project']."&msg=ue");
					exit;
				} else {
					mysql_query("UPDATE $db_todo_category SET todo_category_image='".$userfile_name."' WHERE todo_category_id=".(integer)$_GET['cid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					/* Pokud vse probehne jak ma znicime dane promenne a nastavime $checkupload */
					unset($source_file);
					unset($destination_file);
					unset($extenze);
					unset($_FILES['todo_category_image']);
					$checkupload = 1;
				}
			}
		} else {
			header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=add_todo_category&project=".$_SESSION['project']."&msg=fni");
			exit;
		}
		/* Uzavreni komunikace se serverem */
		ftp_close($conn_id);
	}
	header ("Location: ".$eden_cfg['url_cms']."modul_todo.php?action=add_todo_category&project=".$_SESSION['project']."&msg=".$msg);
	exit;
}