<?php
/***********************************************************************************************************
*
*		EdenLog
*
*		Logovani ruznych akci
*		
*		$nid			= Article ID
*		$aid			= News ID
*		$cid			= Category ID
*		$action			= Action
*				  - 1	= Login do Edenu
*				  - 2	= Logout z Edenu
*				  - 3	= Editace Novinky
*				  - 4	= Editace Aktuality
*				  - 5	= Editace Streamu
*
***********************************************************************************************************/
function EdenLog($action = 0,$nid = 0,$aid = 0,$cid = 0) {
	
	global $db_eden_log;
	global $eden_cfg;
	
	if ($action == 1){// Login do Edenu
	} elseif ($action == 2){// Logout z Edenu
	} elseif ($action == 3){// Editace Novinky
	} elseif ($action == 4){// Editace Aktuality
	} elseif ($action == 5){// Editace Streamu
	} else {
		// Nebyla zadana akce
	}
	if ($action != 0){
		$res = mysql_query("INSERT INTO $db_eden_log (log_admin_id, log_article_id, log_news_id, log_category_id, log_action, log_date, log_ip) VALUES(
		'".(integer)$_SESSION['loginid']."',
		'".(integer)$nid."',
		'".(integer)$aid."',
		'".(integer)$cid."',
		'".(integer)$action."',
		NOW(),
		INET_ATON('".mysql_real_escape_string($eden_cfg['ip'])."'))") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
}