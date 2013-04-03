<?php
/***********************************************************************************************************
*																											
*		ForumCheckPriv											 											
*																											
*		Zjisteni zda dany uzivatel muze cist / pridavat / mazat prispevky a topicy							
*																											
* 		$check		=	forum_topic_admin_read, forum_topic_admin_add, forum_topic_admin_del				
*		$aid		=	Author ID																			
*																											
*		VRACI																								
*		0 / 1																								
*																											
***********************************************************************************************************/
function ForumCheckPriv($check,$aid){
	if ($check == "f" && IsFriend($aid,$_SESSION['loginid']) == "true" && $_SESSION['loginid'] != ""){
		return 1;
	} elseif ($check == "fo" && $_SESSION['loginid'] != ""){
		return 1;
	} elseif ($check == "fa"){
		return 1;
	} elseif ($check == "foa"){
		return 1;
	} elseif ($check == "o"){
		return 1;
	} elseif ($check == "oa"){
		return 1;
	} elseif ($check == "a"){
		return 1;
	} elseif ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_all") == 1){
		return 1;
	} elseif ($check == "") {
		return 0;
	} else {
		return 0;
	}
}
/***********************************************************************************************************
*		Nezahrnuta funkce																					
*		HLAVICKA S MOZNOSTMI UZIVATELE	 																	
*																											
***********************************************************************************************************/
function ForumUser($image){
	
	global $db_forum_pm,$db_forum_posts_log;
	global $url_admins;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "admin" || $_SESSION['u_status'] == "seller"){
		$res_post = mysql_query("SELECT forum_pm_date FROM $db_forum_pm WHERE forum_pm_recipient_id=".(integer)$_SESSION['loginid']." ORDER BY forum_pm_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_post = mysql_fetch_array($res_post);
		$res_log = mysql_query("SELECT forum_posts_log_logtime FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_log = mysql_fetch_array($res_log);
	}
	echo "<tr>";
	echo "	<td id=\"oddelovace\">";
   	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller"){
		echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm\"><img src=\"".$url_admins.$image."\" align=\"left\" width=\"40\" height=\"50\" border=\"0\"></a><span class=\"forum_jmena\"><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm\">".$_SESSION['loginid']."</a> "; 
		if (($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" ||  $_SESSION['u_status'] == "admin") && $ar_log['forum_posts_log_logtime'] < $ar_post['forum_pm_date']){
			echo "<span class=\"new\">new</span>"; 
		}
		echo "</span>";
	} else {
		echo "<img src=\"".$url_admins."0000000001.gif"."\" align=\"left\" width=\"40\" height=\"50\" border=\"0\"><span class=\"forum_jmena\">".$_SESSION['login']."</span>"; 
	}
	/*if ($_SESSION['u_status'] == "vizitor"){echo "&nbsp;|&nbsp; <a href="index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=registrace">"._REGISTRACE."</a>";}*/
	if ($_SESSION['u_status'] != "user" &&  $_SESSION['u_status'] != "admin" && $_SESSION['u_status'] != "seller"){ echo " &nbsp;|&nbsp; <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=login\">"._LOGIN."</a>"; }
	if (CheckUser() == 1){ /* Pokud je anonymum povolen pristup */ echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=otherusers\">"._FORUM_OTHERUSERS."</a>"; }
	if ($_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller" ||  $_SESSION['u_status'] == "admin"){ echo " &nbsp;|&nbsp; <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=friends\">"._FORUM_FRIENDS."</a>";
	echo " &nbsp;|&nbsp; <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=edituser\">"._FORUM_USEREDIT."</a>";
	echo "	<div style=\"float:right;\"><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=setup\">"._FORUM_SETUP."</a></div>";}
	echo "	</td>";
	echo "</tr>"; 
}
/***********************************************************************************************************
*																											
*		UZIVATELSKE MENU				 																	
*																											
***********************************************************************************************************/
function UserMenu(){
	
	global $admini2;
	
	$_GET['faction'] = AGet($_GET,'faction');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	echo "<tr>\n";
	echo "	<td align=\"left\"><div style=\"float:left;\"><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">"._FORUM."</a>\n";
				if ($_SESSION['loginid'] != "" && (CheckPriv("groups_forum_edit") == 1 || $admini2 == "TRUE")){ echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=add_f&amp;project=".$_SESSION['project']."\">"._FORUM_ADD_FORUM."</a>"; }
				if ($_SESSION['loginid'] != "" && (CheckPriv("groups_forum_add") == 1 && $_GET['faction'] == "topics")){ echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=add_t&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;project=".$_SESSION['project']."\">"._FORUM_ADD_TOPIC."</a>"; }
	echo "		&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm&amp;user=".$_SESSION['loginid']."\">"._FORUM_PM."</a>\n";
	echo "		&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=otherusers&amp;user=".$_SESSION['loginid']."\">"._FORUM_OTHERUSERS."</a>\n";
	echo "		&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=friends&amp;user=".$_SESSION['loginid']."\">"._FORUM_FRIENDS."</a></div>\n";
	echo "		<div style=\"float:right;\"><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=setup\" style=\"float:right;\">"._FORUM_SETUP."</a></div>\n";
	echo "	</td>\n";
	echo "</tr>";
}
/***********************************************************************************************************
*																											
*		ZOBRAZENI SEZNAMU FOR			 																	
*																											
*		Forum	 	-	Nejvyssi kategorie																	
*		Head Topics	-	Prostredni kategorie																
*		Topics		-	Nejnizsi kategorie - v nich jsou uz samotne prispevky								
*																											
***********************************************************************************************************/
function ForumShowMain(){
	
	global $db_admin,$db_forum_posts,$db_forum_posts_log,$db_forum_topic,$db_setup,$db_setup_lang;
	global $url_admins;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$res_forums = mysql_query("SELECT forum_topic_id, forum_topic_name, forum_topic_date, forum_topic_admin, forum_topic_admin_read, forum_topic_admin_add, forum_topic_author_id 
	FROM $db_forum_topic WHERE forum_topic_parent_id=0 ORDER BY forum_topic_importance DESC, forum_topic_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_forums = mysql_num_rows($res_forums);
	
	if ($_GET['lang'] == ""){$setup_lang = "s.setup_basic_lang";} else {$setup_lang = $_GET['lang'];}
	$res_setup = mysql_query("SELECT sl.setup_lang_forum_title FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".$setup_lang."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">";
	UserMenu();
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"3\" class=\"forum-title\"><h1>".$ar_setup['setup_lang_forum_title']."</h1>\n";
	echo "			<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">".$ar_setup['setup_lang_forum_title']."</a>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" class=\"forum\">\n";
	echo "	<tr class=\"forum-legend\">\n";
	echo "		<td valign=\"top\" id=\"forum-options\"><img src=\"images/bod.gif\" height=\"18\" width=\"1\" border=\"0\" align=\"middle\"></td>\n";
	echo "		<td valign=\"middle\" id=\"forum-name\">"._FORUM_NAME."</td>\n";
	echo "		<td valign=\"middle\" id=\"forum-topics\">"._FORUM_TOPICS."</td>\n";
	echo "		<td valign=\"middle\" id=\"forum-posts\">"._FORUM_POSTS."</td>\n";
	echo "		<!-- <td valign=\"top\" id=\"forum-date\">"._FORUM_DATE."</td> -->\n";
	echo "	</tr>";
	$i = 0;
	while ($ar_forums = mysql_fetch_array($res_forums)){
	   	/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
	   	if (ForumCheckPriv($ar_forums['forum_topic_admin_read'],$ar_forums['forum_topic_author_id']) == 1){
	   		$res_head_topic = mysql_query("SELECT forum_topic_id, forum_topic_name, forum_topic_comment, forum_topic_date, forum_topic_admin, forum_topic_admin_read, forum_topic_author_id FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$ar_forums['forum_topic_id']." ORDER BY forum_topic_importance DESC, forum_topic_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	   		$num_head_topic = mysql_num_rows($res_head_topic);
	   		$num_all = 0;
	   		while ($ar_head_topic = mysql_fetch_array($res_head_topic)){
	   			$res_posts = mysql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE forum_posts_pid=".(integer)$ar_forums['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	   			$num_posts = mysql_fetch_array($res_posts);
	   			$num_all = $num_all+$num_posts[0];
	   		}
	   		
	   		/* Do pole si ulozime admine Fora */
	   		$admini_forum = explode (" ", $ar_forums['forum_topic_admin']); // Rozdeli uzivatele do pole $admini1
	   		$admini_forum_num = count($admini_forum);
			echo "<tr style=\"background-color:#999999;\">\n";
			echo "	<td valign=\"top\" id=\"forum-options\">&nbsp;</td> \n";
			echo "	<td valign=\"top\" id=\"forum-name\" colspan=\"6\">\n";
		   				if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_edit") == 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=edit_f&amp;id0=".$ar_forums['forum_topic_id']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a>"; }
						if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_del") == 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=del_f&amp;id0=".$ar_forums['forum_topic_id']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; }
						if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_add") == 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=add_f&amp;id0=".$ar_forums['forum_topic_id']."\"><img src=\"images/sys_dtopic.gif\" border=\"0\" alt=\""._FORUM_ADD_TOPIC."\"></a>"; }
			echo "		<span class=\"forum-forum\">".stripslashes($ar_forums['forum_topic_name'])."</span>\n";
			echo "	</td>\n";
			echo "</tr>";
			@mysql_data_seek($res_head_topic,0);
			while ($ar_head_topic = mysql_fetch_array($res_head_topic)){
				/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
				if (ForumCheckPriv($ar_head_topic['forum_topic_admin_read'],$ar_head_topic['forum_topic_author_id']) == 1){
					
					/* Do pole si ulozime admina Head Topicu */
					$admini_head_topic = explode(" ", $ar_head_topic['forum_topic_admin']); // Rozdeli uzivatele do pole $admini1
					$admini_head_topic_num = count($admini_head_topic);
					
					$num_all1 = 0;
					$res_topics = mysql_query("SELECT forum_topic_id, forum_topic_admin FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$ar_head_topic['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$num_topics = mysql_num_rows($res_topics);
					while ($ar_topics = mysql_fetch_array($res_topics)){
						$res_posts = mysql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE forum_posts_pid=".(integer)$ar_topics['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
						$num_posts = mysql_fetch_array($res_posts);
						$num_all1 = $num_all1 + $num_posts[0];
					}
					$num_log = 0;
					$res_topics = mysql_query("SELECT forum_topic_id, forum_topic_admin FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$ar_head_topic['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while ($ar_topics = mysql_fetch_array($res_topics)){
						if (!empty($_SESSION['loginid'])){
							$res_posts_log = mysql_query("SELECT forum_posts_log_posts FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)$ar_topics['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
							$ar_posts_log = mysql_fetch_array($res_posts_log);
							$num_log = $num_log + $ar_posts_log['forum_posts_log_posts'];
						} else {
							$num_log = 0;
						}
					}
					//if ($_SESSION['loginid'] == ""){$admini = "FALSE";} else {$admini = in_array($_SESSION['loginid'], $admini);}
					echo "<tr style=\"background-color:#E9E9E9;\">\n";
					echo "	<td valign=\"top\" id=\"forum-options\"></td>\n";
					echo "	<td valign=\"top\" id=\"forum-name\">\n";
								if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_edit") == 1 || $admini == "TRUE"){ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=edit_f&amp;id0=".$ar_forums['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\"></a>"; }
								if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_del") == 1){echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=del_f&amp;id0=".$ar_forums['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\"></a>";}
								if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_add") == 1){ echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=add_t&amp;id0=".$ar_forums['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."\"><img src=\"images/sys_manage.gif\" border=\"0\" alt=\""._FORUM_ADD_TOPIC."\"></a>"; }
					echo "<span class=\"forum-topic\"><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=topics&amp;id0=".$ar_forums['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."\" title=\"".$ar_head_topic['forum_topic_comment']."\">".stripslashes($ar_head_topic['forum_topic_name'])."</a></span><br>";
								if (!empty($ar_head_topic['forum_topic_comment'])){echo stripslashes($ar_head_topic['forum_topic_comment'])."<br>";}
					echo "<span style=\"font-weight:bold;\">\n"; if ($admini_head_topic_num > 1){ echo _FORUM_MODERATE_P;}else{echo _FORUM_MODERATE;} echo "</span>\n";
								$y = 0;
								while ($y < $admini_head_topic_num){
									if ($y > 0){echo ", ";}
								 	echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm&amp;user=".$_SESSION['loginid']."&amp;pm_rec=".$admini_head_topic[$y]."\" title=\"".GetNickName($admini_head_topic[$y])."\">".GetNickName($admini_head_topic[$y])."</a>\n";
								 	$y++;
								}
					echo "	</td>\n";
					echo "	<td valign=\"top\" id=\"forum-topics\">".$num_topics."</td>\n";
					echo "	<td valign=\"top\" id=\"forum-posts\">"; $new_posts = $num_all1 - $num_log; echo $num_all1."/<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=topics&amp;id0=".$ar_forums['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."\" title=\""._FORUM_NEW_TOPIC."\">".$new_posts."</a></td>\n";
					/* echo "	<td valign=\"top\" id=\"forum-date\">".formatDatetime($ar_head_topic['forum_topic_date'],"d.m.y - H:i") echo "</td>\n";*/
					echo "</tr>";
				}
			}
			$i++;
		}
	}
	echo "</table>"; 
}
/***********************************************************************************************************
*																											
*		TOPICS							 																	
*																											
***********************************************************************************************************/
function ForumShowTopics($pc_id){
	
	global $db_forum_posts,$db_forum_topic,$db_forum_posts_log,$db_setup,$db_setup_lang;
	global $eden_cfg;
	global $url_admins,$url_forum;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($_GET['lang'] == ""){$setup_lang = "s.setup_basic_lang";} else {$setup_lang = $_GET['lang'];}
	$res_setup = mysql_query("SELECT s.setup_forum_topics_on_page, sl.setup_lang_forum_title FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".$setup_lang."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res_head_topic = mysql_query("SELECT forum_topic_id, forum_topic_name, forum_topic_parent_id FROM $db_forum_topic WHERE forum_topic_id=".(integer)$pc_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_head_topic = mysql_fetch_array($res_head_topic);
	
	$res_forum = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)$ar_head_topic['forum_topic_parent_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_forum = mysql_fetch_array($res_forum);
	
	$res_topic = mysql_query("SELECT forum_topic_id, forum_topic_author_id, forum_topic_name, forum_topic_comment, forum_topic_date, forum_topic_admin, forum_topic_admin_read, forum_topic_admin_add, forum_topic_admin_del, forum_topic_views, forum_topic_importance 
	FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$pc_id." ORDER BY forum_topic_importance DESC, forum_topic_last_update DESC, forum_topic_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">";
	UserMenu();	
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"3\" class=\"forum-title\"><h1>".$ar_head_topic['forum_topic_name']."</h1>\n";
   					if ($_SESSION['loginid'] != ""){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."&amp;faction=add_t\" title=\""._FORUM_NEW_TOPIC."\" target=\"_self\">"._FORUM_NEW_TOPIC."</a>&nbsp;&nbsp;&nbsp;&nbsp;"; }
	echo "		<strong><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">".$ar_setup['setup_lang_forum_title']."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_forum['forum_topic_name'])."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=topics&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_head_topic['forum_topic_name'])."</a></strong></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" class=\"forum\">\n";
	echo "	<tr class=\"forum-legend\">\n";
	echo "		<td id=\"forum-options\"><img src=\"images/bod.gif\" height=\"18\" width=\"1\" border=\"0\" align=\"middle\"></td>\n";
	echo "		<td id=\"forum-name\">"._FORUM_NAME."</td>\n";
	echo "		<td id=\"forum-posts\">"._FORUM_POSTS."</td>\n";
	echo "		<td id=\"forum-views\">"._FORUM_VIEWS."</td>\n";
	echo "		<td id=\"forum-author\">"._FORUM_AUTHOR."</td>\n";
	echo "		<td id=\"forum-last-post\">"._FORUM_LAST_POST."</td>\n";
	echo "	</tr>";
	$i = 0;
	while ($ar_topic = mysql_fetch_array($res_topic)){
		/* Datum zalozeni topicu */
		$date_posted = FormatDatetime($ar_topic['forum_topic_date'],"d.m.Y H:i:s");
		$res_posts = mysql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE forum_posts_pid=".(integer)$ar_topic['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_posts = mysql_fetch_array($res_posts);
		
		if ($_SESSION['loginid'] != ""){
			/* Abychom predesli chybove hlasce musime udelat podminku */
			$res_posts_log = mysql_query("SELECT forum_posts_log_posts FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)$ar_topic['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			if ($ar_posts_log = mysql_fetch_array($res_posts_log)){
				$num_posts_log = $ar_posts_log['forum_posts_log_posts'];
			} else {
				$num_posts_log = 0;
			}
		}
		/* Vypocet kolik stranek je potrebnych pro zobrazeni prispevku */
		$stw2 = ($num_posts[0]/$ar_setup['setup_forum_topics_on_page']);
		$stw2 = (integer) $stw2;
		if ($num_posts[0]%$ar_setup['setup_forum_topics_on_page'] > 0) {$stw2++;}
		
		/* Kdyz je k tematu nejaky prispevek zobrazi se datum a nick posledniho prispivatele */
		if ($num_posts[0] > 0){
			$res_max_last_post = mysql_query("SELECT MAX(forum_posts_id) AS forum_posts_id FROM $db_forum_posts WHERE forum_posts_pid=".(integer)$ar_topic['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_max_last_post = mysql_fetch_array($res_max_last_post);
			
			$res_latest_post = mysql_query("SELECT forum_posts_id, forum_posts_author_id, forum_posts_date FROM $db_forum_posts WHERE forum_posts_id=".(integer)$ar_max_last_post['forum_posts_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_latest_post = mysql_fetch_array($res_latest_post);
			/* Nastaveni zobrazeni informaci o poslednim prispevku a odkaz na autora a dany prispevek */
			$latest_post = formatDatetime($ar_latest_post['forum_posts_date'],"d.m.y - H:i")."<br>".GetNickName($ar_latest_post['forum_posts_author_id'])." <a href=\"".$eden_cfg['url']."index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."&amp;page=".$stw2."#".$ar_latest_post['forum_posts_id']."\"><img src=\"".$url_forum."forum_latest_post.gif\" alt=\""._FORUM_LATEST_POST."\" title=\""._FORUM_LATEST_POST."\" width=\"18\" height=\"9\" border=\"0\"></a>";
		} else {
			$latest_post = "";
		}
		
		$admin_nick = GetNickName($ar_topic['forum_topic_author_id']);
		
		/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
		if (ForumCheckPriv($ar_topic['forum_topic_admin_read'],$ar_topic['forum_topic_author_id']) == 1){
			echo "<tr class=\"forum-topics\">";
			echo "	<td valign=\"top\" id=\"forum-options\">&nbsp;</td>";
			echo "	<td valign=\"middle\" id=\"forum-name\">";
					if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_edit") == 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=edit_f&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."\"><img src=\"images/sys_edit.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_EDIT."\"></a> "; } 
					if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_del") == 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=del_f&amp;id2=".$ar_topic['forum_topic_id']."&amp;user=".$_SESSION['loginid']."\"><img src=\"images/sys_del.gif\" height=\"18\" width=\"18\" border=\"0\" alt=\""._CMN_DEL."\"></a>"; }
					if ($ar_topic['forum_topic_importance'] > 0){echo "<span class=\"forum-important\">"._FORUM_TOPIC_IMPORTANT."</span>";}
					if ($ar_topic['forum_topic_admin_read'] == "f"){echo "<span class=\"forum-only-friends\">F</span>";}
					if ($num_posts[0] > $num_posts_log){ echo "<a href=\"".$eden_cfg['url']."index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."&amp;page=".$stw2."#".$ar_latest_post['forum_posts_id']."\"><img src=\"".$url_forum."forum_newest_reply.gif\" alt=\""._FORUM_SHOW_NEWEST_POSTS."\" title=\""._FORUM_SHOW_NEWEST_POSTS."\"  width=\"18\" height=\"9\" border=\"0\"></a>";} echo " <strong><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."&amp;page=1\" title=\"".$ar_topic['forum_topic_comment']."\n"._FORUM_POSTED.": ".$date_posted."\">".stripslashes($ar_topic['forum_topic_name'])."</a></strong><br>";
						if ($num_posts[0] > $ar_setup['setup_forum_topics_on_page']){
							echo "[<img src=\"".$url_forum."forum_minipost.gif\" alt=\""._FORUM_GO_TO_PAGE."\" title=\""._FORUM_GO_TO_PAGE."\"  width=\"12\" height=\"9\" border=\"0\"> "._FORUM_GO_TO_PAGE." ";
							/* Zobrazeni cisla poctu stranek */
							for ($y=1;$y<=$stw2;$y++) {
								if ($y > 1){echo ", ";}
								echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."&amp;page=".$y."\">".$y."</a>";
							}
						echo "]";
					}
			echo "	</td>\n";
			echo "	<td valign=\"top\" id=\"forum-posts\">"; $new_posts = $num_posts[0] - $num_posts_log; echo $num_posts[0]."/"; echo "<a href=\"".$eden_cfg['url']."index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;id2=".$ar_topic['forum_topic_id']."&amp;page=".$stw2."#".$ar_latest_post['forum_posts_id']."\" title=\""._FORUM_NEW_POSTS."\">".$new_posts."</a></td>\n";
			echo "	<td valign=\"top\" id=\"forum-views\">".$ar_topic['forum_topic_views']."</td>\n";
			echo "	<td valign=\"top\" id=\"forum-author\">"; if ($ar_topic['forum_topic_author_id'] == $_SESSION['loginid']){echo $admin_nick;} else { echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm&amp;user=".$_SESSION['loginid']."&amp;pm_rec=".$ar_topic['forum_topic_author_id']."\" title=\"".$admin_nick."\">".$admin_nick."</a>"; } echo "</td>\n";
			echo "	<td valign=\"top\" id=\"forum-last-post\">".$latest_post."</td>\n";
			echo "</tr>";
			$i++;
		}
	}
	echo "	<tr>\n";
	echo "		<td align=\"left\" colspan=\"6\" class=\"forum-title\">\n";
					if ($_SESSION['loginid'] != ""){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."&amp;faction=add_t\" title=\""._FORUM_NEW_TOPIC."\" target=\"_self\">"._FORUM_NEW_TOPIC."</a>&nbsp;&nbsp;&nbsp;&nbsp;"; }
	echo "			<strong><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">".$ar_setup['setup_lang_forum_title']."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_forum['forum_topic_name'])."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=topics&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_head_topic['forum_topic_name'])."</a></strong>";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRIDAVANI A EDITACE TOPICS A HEAD TOPICS															
*																											
***********************************************************************************************************/
function ForumAddTopic(){
	
	global $db_forum_topic,$db_forum_posts,$db_admin,$db_groups,$db_setup;
	global $eden_cfg;
	
	$_GET['faction'] = AGet($_GET,'faction');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if (!empty($_GET['id0'])){
		$id = AGet($_GET,'id0');
	} elseif (!empty($_GET['id1'])){
		$id = AGet($_GET,'id1');
	} elseif (!empty($_GET['id2'])){
		$id = AGet($_GET,'id2');
	} else {
		$id = FALSE;
	}
	
	$res_setup = mysql_query("SELECT setup_forum_anonym FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$res5 = mysql_query("SELECT forum_topic_admin FROM $db_forum_topic WHERE forum_topic_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar5 = mysql_fetch_array($res5);
	$admini = explode (" ", $ar5['forum_topic_admin']); //rozdeli administratory
	if ($_SESSION['loginid'] == ""){$admini2 = "FALSE";} else {$admini2 = in_array($_SESSION['loginid'], $admini);} // a podle podminky vyhodnoti jestli uzivatel muze dane auditorium spravovat ci nikoliv
	
	if (CheckPriv("groups_forum_edit") <> 1 && $admini2 == "FALSE") { echo _NOTENOUGHPRIV;ShowMain();exit;}
	if (AGet($_POST,'addadmin') == "»»»»»»"){ // >>>>>>>>
		// Pokud je z administrace nejake forum odebran nejaky uzivatel
		$res = mysql_query("SELECT forum_topic_admin FROM $db_forum_topic WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admini = explode (" ", $ar['forum_topic_admin']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$admini2[] = $_POST['forum_admins']; // Do pole o jednom clenu se ulozi odebirany uzivatel
		$result = array_diff ($admini, $admini2); // Do pole $result se ulozi vsichni uzivatele, kteri po odebrani toho jednoho zbyli
		$colon_separated = implode (" ", $result); // Ti se pak ulozi do retezce oddelene mezerami
		// A ulozi do databaze
		$res = mysql_query("UPDATE $db_forum_topic SET forum_topic_admin='".mysql_real_escape_string($colon_separated)."' WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$id = $_POST['id'];
	} elseif (AGet($_POST,'addadmin') == "««««««"){ // <<<<<<<<
		// Pokud je do administrace nejake forum pridan nejaky uzivatel
		$res = mysql_query("SELECT forum_topic_admin FROM $db_forum_topic WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admini = explode (" ", $ar['forum_topic_admin']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$admini2[] = $_POST['forum_users']; // Do pole o jednom clenu se ulozi pridavany uzivatel
		$result = array_merge($admini, $admini2); // Do pole $result se ulozi na konec ten pridavany uzivatel
		$colon_separated = implode (" ", $result); // To vse se pak ulozi do retezce oddelene mezerami
		$res = mysql_query("UPDATE $db_forum_topic SET forum_topic_admin='".mysql_real_escape_string($colon_separated)."' WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$id = $_POST['id'];
	}
	if($_GET['faction'] == "edit_f"){
		$res_topic = mysql_query("SELECT forum_topic_parent_id, forum_topic_name, forum_topic_comment, forum_topic_admin_read, forum_topic_admin_add, forum_topic_admin_del, forum_topic_importance FROM $db_forum_topic WHERE forum_topic_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_topic = mysql_fetch_array($res_topic);
	} else {
		$ar_topic = Array();
	}
	if (empty($_POST['confirm'])) {
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
		UserMenu();
		echo "	<tr>\n";
		echo "		<td align=\"left\"><h2>"._FORUM." - "; if ($_GET['faction'] == "add_f"){echo _FORUM_ADD_FORUM;}elseif($_GET['faction'] == "edit_f"){echo _FORUM_EDIT_FORUM;}elseif($_GET['faction'] == "add_t"){echo _FORUM_ADD_TOPIC;}elseif($_GET['faction'] == "edit_t"){echo _FORUM_EDIT_TOPIC;} echo "</h2></td>\n";
		echo "	<tr>\n";
			  	if (AGet($_GET,'msg')){
					echo "<tr>\n";
					echo "	<td class=\"error\"><span class=\"post-forum-err\">".Msg(AGet($_GET,'msg'))."</span></td>\n";
					echo "</tr>\n";
					}
		echo "</table>\n";
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"post-editor-row1\" align=\"right\" valign=\"top\"><strong>"; if ($_GET['faction'] == "edit_f" || $_GET['faction'] == "add_f"){echo _FORUM_FORUM_NAME;} else {echo _FORUM_TOPIC_NAME;} echo ":</strong>\n";
		echo "			<form name=\"post\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=".$_GET['faction']."&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1'); if (!empty($_GET['id2'])){ echo "&amp;id2=".AGet($_GET,'id2');} echo "&amp;project=".$_SESSION['project']."\" method=\"post\" "; if ($_GET['faction'] == "add_t"){ echo "onsubmit=\"return checkForm(this)\"";} echo ">\n";
		echo "		</td>\n";
		echo "		<td class=\"post-editor-row2\">";
		// Zobrazi se jen pokud je vybrana podforum
		if (!empty($_GET['id1']) && $_GET['faction'] == "edit_f"){
			if($_GET['faction'] == "edit_f"){$parent = $ar_topic['forum_topic_parent_id'];}
			if($_GET['faction'] == "add_t"){$parent = AGet($_GET,'id1');}
			echo "<select name=\"forum_topic_megatopic\">";
			$res_s0 = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_parent_id=0 ORDER BY forum_topic_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_s0 = mysql_fetch_array($res_s0)){
				if ($id != $ar_s0['forum_topic_id']){
					echo "<option name=\"forum_topic_megatopic\" value=\"".$ar_s0['forum_topic_id']."\" ";
					if ($ar_s0['forum_topic_id'] == $parent) {echo "selected=\"selected\"";}
					echo ">".$ar_s0['forum_topic_name']."</option>";
					$res_s1 = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$ar_s0['forum_topic_id']." ORDER BY forum_topic_name ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					while($ar_s1 = mysql_fetch_array($res_s1)){
						if ($id != $ar_s1['forum_topic_id']){
							// Nezobrazovat pro vyber vlastni kategorii - nejde vlozit samu do sebe
							echo "<option name=\"forum_topic_megatopic\" value=\"".$ar_s1['forum_topic_id']."\" ";
							if ($ar_s1['forum_topic_id'] == $parent) {echo "selected=\"selected\"";}
							echo ">&nbsp;&nbsp;&nbsp;".$ar_s1['forum_topic_name']."</option>";
						}
					}
				}
			}
			echo "</select><br>";
		}
		echo "			<input maxlength=\"255\" size=\"40\" name=\"forum_topic_name\" value=\"".AGet($ar_topic,'forum_topic_name')."\">";
		echo "		</td>\n";
		echo "		<td align=\"left\" rowspan=\"2\">\n";
		echo "			<table cellspacing=0 border=0>\n";
		echo "				<tr>\n";
		echo "					<td width=\"50\"><small>&nbsp;</small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small>"._FORUM_FRIENDS."</small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small>"._FORUM_OTHERUSERS."</small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small>"._FORUM_ANONYMS."</small></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td width=\"50\" align=\"right\"><small>"._FORUM_READ."</small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"f\" name=\"friend_r\" "; if(AGet($ar_topic,'forum_topic_admin_read') == "f" || AGet($ar_topic,'forum_topic_admin_read') == "fo" || AGet($ar_topic,'forum_topic_admin_read') == "foa" || AGet($ar_topic,'forum_topic_admin_read') == "fa" || $_GET['faction'] == "add_t" || $_GET['faction'] == "add_f"){echo  "checked";} echo "></small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"o\" name=\"other_r\" "; if(AGet($ar_topic,'forum_topic_admin_read') == "o" || AGet($ar_topic,'forum_topic_admin_read') == "fo" || AGet($ar_topic,'forum_topic_admin_read') == "foa" || AGet($ar_topic,'forum_topic_admin_read') == "od" || $_GET['faction'] == "add_t" || $_GET['faction'] == "add_f"){echo  "checked";} echo "></small></td>\n";
								if ($ar_setup['setup_forum_anonym'] == 1) {
									echo "<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"a\" name=\"anon_r\" "; if(AGet($ar_topic,'forum_topic_admin_read') == "a" || AGet($ar_topic,'forum_topic_admin_read') == "fa" || AGet($ar_topic,'forum_topic_admin_read') == "foa" || AGet($ar_topic,'forum_topic_admin_read') == "od" || $_GET['faction'] == "add_t" || $_GET['faction'] == "add_f"){echo "checked";} echo "></small></td>\n";
								}
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td width=\"50\" align=\"right\"><small>"._FORUM_WRITE."</small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"f\" name=\"friend_w\" "; if(AGet($ar_topic,'forum_topic_admin_add') == "f" || AGet($ar_topic,'forum_topic_admin_add') == "fo" || AGet($ar_topic,'forum_topic_admin_add') == "foa" || AGet($ar_topic,'forum_topic_admin_add') == "fa" || $_GET['faction'] == "add_t" || $_GET['faction'] == "add_f"){echo  "checked";} echo "></small></td>\n";
		echo "					<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"o\" name=\"other_w\" "; if(AGet($ar_topic,'forum_topic_admin_add') == "o" || AGet($ar_topic,'forum_topic_admin_add') == "fo" || AGet($ar_topic,'forum_topic_admin_add') == "foa" || AGet($ar_topic,'forum_topic_admin_add') == "od" || $_GET['faction'] == "add_t" || $_GET['faction'] == "add_f"){echo  "checked";} echo "></small></td>\n";
							/* Zatim neni vyuziti */
							if (1>5){
								echo "<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"a\" name=\"anon_w\" "; if(AGet($ar_topic,'forum_topic_admin_add') == "a" || AGet($ar_topic,'forum_topic_admin_add') == "fa" || AGet($ar_topic,'forum_topic_admin_add') == "foa" || AGet($ar_topic,'forum_topic_admin_add') == "od"){echo "checked";} echo "></small></td>\n";
							}
		echo "				</tr>\n";
							/* Zatim neni vyuziti */
							if (1>5){
								echo " 	<tr>\n";
								echo " 		<td width=\"50\" align=\"right\"><small>"._FORUM_DELETE."</small></td>\n";
								echo "		<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"f\" name=\"friend_d\" "; if(AGet($ar_topic,'forum_topic_admin_del') == "f" || AGet($ar_topic,'forum_topic_admin_del') == "fo" || AGet($ar_topic,'forum_topic_admin_del') == "foa" || AGet($ar_topic,'forum_topic_admin_del') == "fa"){echo  "checked";} echo "></small></td>\n";
								echo "		<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"o\" name=\"other_d\" "; if(AGet($ar_topic,'forum_topic_admin_del') == "o" || AGet($ar_topic,'forum_topic_admin_del') == "fo" || AGet($ar_topic,'forum_topic_admin_del') == "foa" || AGet($ar_topic,'forum_topic_admin_del') == "od"){echo  "checked";} echo "></small></td>\n";
								echo "		<td width=\"50\" align=\"middle\"><small><input type=\"checkbox\" value=\"a\" name=\"anon_d\" "; if(AGet($ar_topic,'forum_topic_admin_del') == "a" || AGet($ar_topic,'forum_topic_admin_del') == "fa" || AGet($ar_topic,'forum_topic_admin_del') == "foa" || AGet($ar_topic,'forum_topic_admin_del') == "od"){echo  "checked";} echo "></small></td>\n";
								echo "	</tr>\n";
							}
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"post-editor-row1\"><strong>"._FORUM_TOPIC_IMPORTANCE."</strong></td>\n";
		echo "		<td class=\"post-editor-row2\"><select name=\"forum_topic_importance\">";
				for ($i=0;$i<10;$i++){
					echo "<option value=\"".$i."\""; if (AGet($ar_topic,'forum_topic_importance') == $i){echo "selected=\"selected\"";} echo ">".$i."</option>";
				}
		echo "		</select></td>";
		echo "	</tr>";
		/* Zobrazi se jen pokud jde o pridani noveho topicu */
		if ($_GET['faction'] == "add_t"){
			/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
			if (ForumCheckPriv(AGet($ar_topic,'forum_topic_admin_add'),AGet($ar_topic,'forum_topic_author_id')) == 1){
				echo "	<tr style=\"background-color:#ccddff;\">\n";
				echo "		<td colspan=\"3\"><a name=\"editor\">&nbsp;</a><h2>"._FORUM_FIRST_POST."</h2></td>\n";
				echo "	</tr>\n";
				echo "	<tr style=\"background-color:#ccddff;\">\n";
				echo "		<td class=\"post-editor-row1\">"._FORUM_POST_SUBJECT."</td>\n";
				echo "		<td class=\"post-editor-row2\" colspan=\"2\"><input class=\"post\" style=\"width: 500px\" tabindex=\"2\" maxlength=\"50\" size=\"45\" name=\"forum_post_subject\" value=\"".urldecode(AGet($_GET,'forum_post_subject'))."\"> </td>\n";
				echo "	</tr>\n";
				echo "	<tr style=\"background-color:#ccddff;\">\n";
				echo "		<td class=\"post-editor-row1\" valign=\"top\">"._FORUM_POST."</td>\n";
				echo "		<td class=\"post-editor-row2\" valign=\"top\" colspan=\"2\">";
							$post_editor = new PostEditor;
							$post_editor->editor_name = "forum_post";
							$post_editor->form_name = "post";
							$post_editor->form_text = urldecode(AGet($_GET,'forum_post'));
							$post_editor->table_width = "500";
							$post_editor->textarea_width = "500";
							$post_editor->textarea_rows = "15";
							$post_editor->textarea_cols = "60";
							
							$post_editor->BBEditor();
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "	<tr style=\"background-color:#ccddff;\">\n";
				echo "		<td class=\"post-editor-row1\" valign=\"top\">"._FORUM_POST_OTHER_SETTINGS."></td>\n";
				echo "		<td class=\"post-editor-row2\" colspan=\"2\">\n";
				echo "			<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">\n";
				echo "				<tr>\n";
				echo "					<td><input type=\"checkbox\" checked value=\"1\" name=\"forum_attach_sig\"> </td>\n";
				echo "					<td>"._FORUM_POST_OTHER_SETTINGS_ATTACH_SIG."</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "	<tr style=\"background-color:#ccddff;\">\n";
				echo "		<td class=\"post-editor-row1\" valign=\"top\">&nbsp;</td>\n";
				echo "		<td class=\"post-editor-row2\" colspan=\"2\" height=\"28\">\n";
				echo "		<input type=\"radio\" name=\"form_send_mode\" value=\"1\" checked>"._FORUM_SEND_SEND."&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"form_send_mode\" value=\"2\">"._FORUM_SEND_PREVIEW."\n";
				echo "		</td>\n";
				echo "	</tr>";
			} else {
				echo "<tr>";
				echo "	<td colspan=\"3\" class=\"red\">"._FORUM_POSTS_ADD_DISALLOW."</td>";
				echo "</tr>";
			}
		}
		echo "	<tr>";
		echo "		<td colspan=\"2\"><input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">";
		echo "			<input type=\"hidden\" name=\"id\" value=\""; if (isset($_GET['id2'])){echo AGet($_GET,'id2');} elseif (isset($_GET['id1'])){echo AGet($_GET,'id1');} else {echo AGet($_GET,'id0');}echo "\">";
					if ($_GET['faction'] == 'add_f'){
						echo "<input type=\"hidden\" name=\"parent_topic\" value=\"".AGet($_GET,'id0')."\">";
					}elseif ($_GET['faction'] == 'add_t' || $_GET['faction'] == 'edit_f'){
						echo "<input type=\"hidden\" name=\"parent_topic\" value=\"".AGet($_GET,'id1')."\">";
					} else {
						echo "<input type=\"hidden\" name=\"parent_topic\" value=\"".$ar_topic['forum_topic_parent_id']."\">";
					}
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">";
		echo "			<input type=\"hidden\" name=\"mode\" value=\""; if ($_GET['faction'] == "add_t"){echo "forum_add_posts";} else {echo "forum";} echo "\">";
		echo "			<input type=\"submit\" value=\""; if ($_GET['faction'] == "edit_f"){echo _FORUM_EDIT_FORUM;}elseif ($_GET['faction'] == "add_f"){echo _FORUM_ADD_FORUM;}elseif($_GET['faction'] == "edit_t"){echo _FORUM_EDIT_TOPIC;}elseif($_GET['faction'] == "add_t"){echo _FORUM_ADD_TOPIC;} echo "\" class=\"eden_button\">";
		echo "			</form>";
		echo "		</td>";
		echo "	</tr>";
 		if ($_GET['faction'] == "edit_f"){
			echo "<tr>\n";
			echo "	<td align=\"center\">\n";
			echo "		<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=edit_f&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1'); if (!empty($_GET['id2'])){ echo "&amp;id2=".AGet($_GET,'id2')."";} echo "\" method=\"post\">\n";
			echo "		<select name=\"forum_admins\" size=\"8\">";
			$res4 = mysql_query("SELECT forum_topic_admin FROM $db_forum_topic WHERE forum_topic_id=".(integer)$id." ORDER BY forum_topic_admin ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar4 = mysql_fetch_array($res4);
			$admini = explode (" ", $ar4['forum_topic_admin']);// Rozdeli se pole $ar4[admin] na jednotlive uzivatele
			$i = count($admini); // Spocita se pocet adminu
			$x = 0;
			while ($i>$x){
				if ($admini[$x] != ""){ // Kdyz neni polozka retezce prazdna tak se zobrazi jako polozka select
					$int_admini = $admini[$x];
					$admin_nick = GetNickName($int_admini);
					echo '<option name="section" value="'.$admini[$x].'" >'.$admin_nick.'</option>';
				}
				$x++;
			}
			echo "	</select>\n";
	   		echo "		</td>\n";
	   		echo "		<td align=\"center\">\n";
			echo "			<select name=\"forum_users\" size=\"8\">";
			$res5 = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_status='admin' ORDER BY admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$num5 = mysql_num_rows($res5);
			// Ulozeni uzivatelu do pole $us
			while ($ar5 = mysql_fetch_array($res5)){
				$us[] = $ar5['admin_id'];
			}
			
			$result = array_diff ($us, $admini); // Do pole $result se ulozi vsichni uzivatele, kteri zbyli
			$x = 0;
			while ($num5 > $x){
				if ($result[$x] != ""){ // Pokud neni pole $result[$x] prazdne tak se zobrazi
					$int_result = $result[$x];
					$admin_nick = GetNickName($int_result);
					echo '<option name="forum_users" value="'.$result[$x].'">'.$admin_nick.'</option>';
				}
				$x++;
			}
			echo "		</select>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td align=\"center\">\n";
			echo "				<input type=\"submit\" name=\"addadmin\" value=\"&raquo;&raquo;&raquo;&raquo;&raquo;&raquo;\" class=\"eden_button\">\n";
			echo "			</td>\n";
			echo "			<td align=\"center\">\n";
			echo "				<input type=\"submit\" name=\"addadmin\" value=\"&laquo;&laquo;&laquo;&laquo;&laquo;&laquo;\" class=\"eden_button\">\n";
			echo "			\n";
			echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
			echo "				<input type=\"hidden\" name=\"id\" value=\""; if (!empty($_GET['id2'])){echo AGet($_GET,'id2');}elseif(!empty($_GET['id1'])){echo AGet($_GET,'id1');} else {echo AGet($_GET,'id0');} echo "\">\n";
			echo "				<input type=\"hidden\" name=\"admins\" value=\"true\">\n";
			echo "				</form>\n";
			echo "		</tr>";
 		}				
		echo "</table>";
 	}
}
/***********************************************************************************************************
*																											
*		SMAZANI OZNACENYCH POLOZEK Z FORA  																	
*																											
***********************************************************************************************************/
function ForumDelForum(){
	
	global $db_forum_topic,$db_forum_posts,$db_admin;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	$id = FALSE;
	if (isset($_GET['id0'])){$id = AGet($_GET,'id0');}
	if (isset($_GET['id1'])){$id = AGet($_GET,'id1');}
	if (isset($_GET['id2'])){$id = AGet($_GET,'id2');}
	
	if (CheckPriv("groups_forum_del") <> 1){ echo _NOTENOUGHPRIV;ForumShowMain();exit;}
	if (AGet($_POST,'confirm') == "false"){ForumShowMain();exit;}
	if (AGet($_POST,'confirm') == "true"){
		// Nastaveni pole pro ziskani id rodice 
		$res2 = mysql_query("SELECT forum_topic_parent_id FROM $db_forum_topic WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 		$ar2 = mysql_fetch_array($res2);
		$id1 = $ar2['forum_topic_parent_id']; //Jako $id bylo prideleno $id rodice
		
		// Odstraneni vsech podfor
		$res3 = mysql_query("SELECT forum_topic_id FROM $db_forum_topic WHERE forum_topic_parent_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
 		while ($ar3 = mysql_fetch_array($res3)){
			mysql_query("DELETE FROM $db_forum_topic WHERE forum_topic_id=".(integer)$ar3['forum_topic_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		mysql_query("DELETE FROM $db_forum_topic WHERE forum_topic_id=".(integer)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		ForumShowMain();
	}
	if (empty($_POST['confirm'])){
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">";
			UserMenu();
		echo "	<tr>\n";
		echo "		<td align=\"left\">"._FORUM_DEL_FORUM."</td>\n";
		echo "	<tr>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table cellspacing=\"1\" cellpadding=\"1\" class=\"forum\">\n";
		echo "	<tr class=\"forum-legend\">\n";
		echo "		<td align=\"center\"><img src=\"images/bod.gif\" height=\"18\" width=\"1\" border=\"0\" align=\"middle\">ID</td>\n";
		echo "		<td>"._FORUM_NAME."</td>\n";
		echo "		<td>"._FORUM_COMMENT."</td>\n";
		echo "		<td>"._FORUM_ADMIN."</td>\n";
		echo "		<td>"._FORUM_DATE."</td>\n";
		echo "	</tr>";
		$res = mysql_query("SELECT forum_topic_id, forum_topic_admin, forum_topic_date, forum_topic_name, forum_topic_comment FROM $db_forum_topic WHERE forum_topic_id=".(integer)$id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$admin = explode (" ", $ar['forum_topic_admin']);
		$int_admin = $admin[0];
		$admin_nick = GetNickName($int_admin);
		
		$forum_topic_date = FormatDatetime($ar['forum_topic_date'],"d.m.Y H:i:s");
		$forum_topic_name = PrepareFromDB($ar['forum_topic_name'], 1);
		$forum_topic_comment = PrepareFromDB($ar['forum_topic_comment'], 1);
		
		echo "	<tr>\n";
		echo "		<td align=\"center\">".$ar['forum_topic_id']."</td>\n";
		echo "		<td>".$forum_topic_name."</td>\n";
		echo "		<td>".$forum_topic_comment."</td>\n";
		echo "		<td>".$admin_nick."</td>\n";
		echo "		<td width=\"150\" align=\"left\" valign=\"top\">".$forum_topic_date."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table cellspacing=\"1\" cellpadding=\"2\" class=\"forum\">\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" colspan=\"2\"><br><br><strong><span style=\"color : #FF0000;\">"._FORUM_CHECKDELETE."</span></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"50\" valign=\"top\">\n";
		echo "		<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=del_f\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_YES."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\""; if (isset($_GET['id2'])){echo AGet($_GET,'id2');}elseif(isset($_GET['id1'])){echo AGet($_GET,'id1');} else {echo AGet($_GET,'id0');} echo "\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "		</form>\n";
		echo "		</td>\n";
		echo "		<td width=\"800\" valign=\"top\">\n";
		echo "		<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=del_f\" method=\"post\">\n";
		echo "			<input type=\"submit\" value=\""._CMN_NO."\" class=\"eden_button\"><br>\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\""; if (isset($_GET['id2'])){echo AGet($_GET,'id2');}elseif(isset($_GET['id1'])){echo AGet($_GET,'id1');} else {echo AGet($_GET,'id0');} echo "\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"confirm\" value=\"false\">\n";
		echo "		</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		POSTS							 																	
*																											
***********************************************************************************************************/
function ForumPosts(){
	
	global $db_forum_posts,$db_admin,$db_admin_info,$db_forum_posts_log,$db_forum_topic,$db_setup,$db_setup_lang,$db_forum_posts_edit_log;
	global $url_admins;
	global $eden_cfg;
	
	$_GET['faction'] = AGet($_GET,'faction');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if ($_GET['lang'] == ""){$setup_lang = "s.setup_basic_lang";} else {$setup_lang = $_GET['lang'];}
	$res_setup = mysql_query("SELECT s.setup_forum_allow_edit, s.setup_forum_allow_del, s.setup_forum_allow_quote, s.setup_forum_allow_sig, sl.setup_lang_forum_title, s.setup_forum_posts_on_page 
	FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".$setup_lang."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	/* Odstraneni prispevku uzivatelem */
	if ($_GET['faction'] == "del_post"){
		mysql_query("DELETE FROM $db_forum_posts WHERE forum_posts_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	
	if (!empty($_SESSION['loginid'])){
		$res_adm = mysql_query("SELECT a.admin_id, a.admin_hits, ai.admin_info_forum_posts_order FROM $db_admin AS a, $db_admin_info AS ai 
		WHERE a.admin_id=".(integer)$_SESSION['loginid']." AND ai.aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_adm = mysql_fetch_array($res_adm);
	}
	/* Pri zobrazeni tematu pricte +1 k poctu zobrazeni */
	mysql_query("UPDATE $db_forum_topic SET forum_topic_views = forum_topic_views + 1  WHERE forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$res_posts = mysql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE forum_posts_pid=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_posts = mysql_fetch_array($res_posts);
	
	$res_topic = mysql_query("SELECT forum_topic_admin, forum_topic_name, forum_topic_admin_read, forum_topic_admin_add, forum_topic_author_id FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_topic = mysql_fetch_array($res_topic);
	$admini = explode (" ", $ar_topic['forum_topic_admin']); // Rozdeli se pole $ar[admin] na jednotlive administratory auditoria
	$int_admini = $admini[0];
	
	$res_head_topic = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id1')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_head_topic = mysql_fetch_array($res_head_topic);
	
	$res_forum = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id0')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_forum = mysql_fetch_array($res_forum);
	
	$res7 = mysql_query("SELECT admin_userimage FROM $db_admin WHERE admin_id=".(integer)$int_admini) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar7 = mysql_fetch_array($res7);
	$int_admini = AGet($admini,1);
	
	$res8 = mysql_query("SELECT admin_id FROM $db_admin WHERE admin_id=".(integer)$int_admini) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar8 = mysql_fetch_array($res8);
	
	if ($_SESSION['loginid'] != ""){
		/* LOG */
		/* Spocitame kolik je logu k danemu topicu od jednoho uzivatele */
		$res_posts_log = mysql_query("SELECT forum_posts_log_posts FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_posts_log = mysql_fetch_array($res_posts_log);
		$num_posts_log = mysql_num_rows($res_posts_log);
		/* Pokud neni zadny zaznam zalozi se novy a ulozi se do neho i aktualni pocet prispevku */
		if ($num_posts_log < 1){
			mysql_query("INSERT INTO $db_forum_posts_log VALUES(".(integer)$_SESSION['loginid'].",".(integer)AGet($_GET,'id2').",".(integer)$num_posts[0].",NOW(),0)") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Pokud je 1 zaznam - updatujeme pocet prispevku v topicu */
		}elseif ($num_posts_log == 1){
			mysql_query("UPDATE $db_forum_posts_log SET forum_posts_log_posts=".$num_posts[0].", forum_posts_log_logtime=NOW() WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		/* Pokud je vic jak jeden zaznam - smazeme prebytecne a updatujeme ten jeden posledni zbyly */
		} else {
			$i=1;
			while ($i < $num_posts_log){
				mysql_query("DELETE FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$i++;
			}
			mysql_query("UPDATE $db_forum_posts_log SET forum_posts_log_posts=".(integer)$num_posts[0].", forum_posts_log_logtime=NOW() WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
	}
	
	/* Pokud chceme nekoho citovat, nacteme to a nechame to zobrazit v textarea */
	if ($_GET['faction'] == "quote"){
		$res_quote = mysql_query("SELECT forum_posts_id, forum_posts_text_bb, forum_posts_author_id FROM $db_forum_posts WHERE forum_posts_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_quote = mysql_fetch_array($res_quote);
		$forum_post = '[h3]'.GetNickName($ar_quote['forum_posts_author_id'])._FORUM_POST_QUOTE_WROTE.':[/h3][quote]'.stripslashes($ar_quote['forum_posts_text_bb']).'[/quote]';
	}
	
	/* Pokud chceme text editovat, nacteme to a nechame to zobrazit v textarea */
	if ($_GET['faction'] == "edit_post"){
		$res_post = mysql_query("SELECT forum_posts_id, forum_posts_subject, forum_posts_text_bb FROM $db_forum_posts WHERE forum_posts_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_post = mysql_fetch_array($res_post);
		$forum_post_subject = stripslashes($ar_post['forum_posts_subject']);
		$forum_post = stripslashes($ar_post['forum_posts_text_bb']);
	} else {
		$ar_post = Array();
	}
	
	$hits = $ar_setup['setup_forum_posts_on_page'];
	
	if (empty($_GET['page'])) {$page = 1;} else {$page = $_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	if ($hits == 0){$hits = 30;}
	$stw2 = ($num_posts[0]/$hits);
	$stw2 = (integer) $stw2;
	if ($num_posts[0]%$hits > 0) {$stw2++;}
	$np = $page + 1;
	$pp = $page - 1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;}
	
	/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
	if (ForumCheckPriv($ar_topic['forum_topic_admin_read'],$ar_topic['forum_topic_author_id']) == 1){
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
			UserMenu();
		echo "	<tr>\n";
		echo "	<td align=\"left\" colspan=\"3\" class=\"forum-title\"><h1>".$ar_topic['forum_topic_name']."</h1>\n";
		echo "		<strong><a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">".$ar_setup['setup_lang_forum_title']."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_forum['forum_topic_name'])."</a> / <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=topics&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_head_topic['forum_topic_name'])."</a></strong></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table cellspacing=\"1\" cellpadding=\"4\" border=\"0\" class=\"forum\">";
		if ($stw2 > 1){ 
			echo "<tr>";
			echo "	<td align=\"left\" valign=\"top\" class=\"forum-main-col\" colspan=\"2\">";
			/* Zobrazeni cisla poctu stranek */
			for ($i=1;$i<=$stw2;$i++) {
				if ($page == $i) {
					echo " <strong>".$i."</strong> ";
				} else {
					echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$i."\">".$i."</a> ";
				}
			}
			/* Zobrazeni sipek s predchozimi a dalsimi strankami novinek */
			echo "<br>";
			if ($page > 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$pp."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a> <--";} 
			if ($page < $stw2){ echo "|--> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$np."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a>";}
			echo "		<hr size=\"1\">";
			echo "	</td>";
			echo "</tr>";
		}
		
		$res9 = mysql_query("SELECT forum_topic_admin FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar9 = mysql_fetch_array($res9);
		$admini = explode (" ", $ar9['forum_topic_admin']); //rozdeli administratory
		if ($_SESSION['loginid'] == ""){$admini2 = "FALSE";} else {$admini2 = in_array($_SESSION['loginid'], $admini);} // a podle podminky vyhodnoti jestli uzivatel muze dane auditorium spravovat ci nikoliv
		if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_del") == 1 || $admini2 == "TRUE"){
			echo "	<form action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."\" method=\"post\">";
		}
		
		/* Nastavime limit pro ryclejsi zobrazeni prispevku */
		$limit_from = ($page * $hits) - $hits; 
		$limit = (integer)$limit_from." , ".(integer)$hits;
		if ($ar_adm['admin_info_forum_posts_order'] == 1){$posts_order = "DESC";}else{$posts_order = "ASC";}
		$res_posts = mysql_query("SELECT forum_posts_id, forum_posts_author_id, forum_posts_subject, forum_posts_text, forum_posts_date, forum_posts_reported 
		FROM $db_forum_posts WHERE forum_posts_pid=".(integer)AGet($_GET,'id2')." ORDER BY forum_posts_id ".$posts_order." LIMIT ".$limit) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num_posts2 = mysql_num_rows($res_posts);
		$i=1;
		while ($ar_posts = mysql_fetch_array($res_posts)){
			$forum_posts_text = str_replace( "&lt;","<",$ar_posts['forum_posts_text']);
			$forum_posts_text = str_replace( "&gt;",">",$forum_posts_text);
			$forum_posts_text = str_replace( "&amp;","&",$forum_posts_text);
			$forum_posts_text = str_replace( "&quot;","'",$forum_posts_text);
			$forum_posts_text = str_replace( "&acute;","'",$forum_posts_text);
			
			$forum_posts_subject = PrepareFromDB($ar_posts['forum_posts_subject'],1);
			
			$forum_posts_text = stripslashes($forum_posts_text);
			$forum_posts_date = formatDatetime($ar_posts['forum_posts_date'],"d.m.y - H:i");
			$res_author = mysql_query("SELECT a.admin_id, a.admin_userimage, a.admin_nick, a.admin_reg_date, a.admin_posts_forum, ai.admin_info_sig_html 
			FROM $db_admin AS a, 
			$db_admin_info AS ai 
			WHERE a.admin_id=".(integer)$ar_posts['forum_posts_author_id']." AND ai.aid=a.admin_id") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_author = mysql_fetch_array($res_author);
			
			$res_posts_edit_log = mysql_query("SELECT forum_posts_edit_log_admin_id, forum_posts_edit_time FROM $db_forum_posts_edit_log WHERE forum_posts_edit_log_post_id=".(integer)$ar_posts['forum_posts_id']." ORDER BY forum_posts_edit_time DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_posts_edit_log = mysql_fetch_array($res_posts_edit_log);
			$num_posts_edit_log = mysql_num_rows($res_posts_edit_log);
			echo "<tr>";
			echo "	<td>";
			echo "		<table cellspacing=\"0\" cellpadding=\"4\" border=\"0\" class=\"forum-posts-"; if ($i % 2 == 0){echo "suda";} else {echo "licha";} echo "\">";
			echo "			<tr>";
			echo "				<td rowspan=\"3\" valign=\"top\" class=\"forum-posts-row1\" "; if ($ar_posts['forum_posts_reported'] == 1 && $_SESSION['loginid'] != "" && (CheckPriv("groups_forum_del") == 1 || $admini2 == "TRUE")){echo "style=\"background-color:#ff0000;\"";} echo "><a name=\"".$ar_posts['forum_posts_id']."\"></a>";
			echo "					<div class=\"forum-names\">"; if ($ar_adm['admin_id'] == $ar_posts['forum_posts_author_id'] || AGet($_SESSION,'status') == "vizitor"){ echo $ar_author['admin_nick'];} else { echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;user=".$ar_author['admin_id']."&amp;pm_rec=".$ar_posts['forum_posts_author_id']."\">".$ar_author['admin_nick']."</a>"; } echo "</div>";
			echo "					<img src=\""; if (!isset($ar_author['admin_userimage'])){echo $url_admins."0000000001.gif";} else {echo $url_admins.$ar_author['admin_userimage'];} echo "\" align=\"left\"><br clear=\"all\">";
									echo _FORUM_ADMIN_FOUND." ".FormatDatetime($ar_author['admin_reg_date'],"d.m.Y")."<br>";
									echo _FORUM_ADMIN_POSTS." ".$ar_author['admin_posts_forum'];
									if ($_SESSION['loginid'] != "" && (CheckPriv("groups_forum_del") == 1 || $admini2 == "TRUE")){
										echo "<br><input type=\"checkbox\" name=\"del[]\" value=\"".$ar_posts['forum_posts_id']."\">";
										if ($ar_posts['forum_posts_reported'] == 1){echo "<br><span class=\"forum-posts-reported\">"._FORUM_POST_REPORTED."</span>";}
									}
			echo "				</td>";
			echo "				<td valign=\"top\" class=\"forum-posts-row2\">";
								echo _FORUM_POSTS_POSTED.": ".$forum_posts_date; 
								/* Vypocitame ktera zprava v poradi se zobrazuje (spolu s temi ktere se zobrazuji na prechzich strankach - i kdyz se tyto nezobrazuji) */
								/* Pokud je toto vetsi nezli pocet prispevku v logu zobrazi se NEW */
								$posts_and_pages = ($hits * $page - $hits) + $i;
								/* Zobrazeni NEW u novych prispevku podle toho jak je nastaveno razeni prispevku */
								if ($ar_adm['admin_info_forum_posts_order'] == 0){
									if($posts_and_pages > $ar_posts_log['forum_posts_log_posts']){echo " <span class=\"red\">NEW</span>";}
								} else {
									if($num_posts[0] - $ar_posts_log['forum_posts_log_posts'] >= $posts_and_pages){echo " <span class=\"red\">NEW</span>";}
								}
			echo "			</td>";
			echo "			<td valign=\"top\" class=\"forum-posts-row3\">&nbsp;";
							/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
							if (ForumCheckPriv($ar_topic['forum_topic_admin_add'],$ar_topic['forum_topic_author_id']) == 1){
								if ($_SESSION['loginid'] == $ar_posts['forum_posts_author_id'] && $ar_setup['setup_forum_allow_edit'] == 1){echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;pid=".$ar_posts['forum_posts_id']."&amp;faction=edit_post&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."#editor\" title=\""._FORUM_POST_EDIT_TITLE."\">"._FORUM_POSTS_EDIT."</a>&nbsp;&nbsp;";}
								if ($_SESSION['loginid'] == $ar_posts['forum_posts_author_id'] && $ar_setup['setup_forum_allow_del'] == 1){echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;pid=".$ar_posts['forum_posts_id']."&amp;faction=del_post&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."\" title=\""._FORUM_POST_DEL_TITLE."\">"._FORUM_POSTS_DEL."</a>&nbsp;&nbsp;";}
								if ($ar_setup['setup_forum_allow_quote'] == 1){echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;pid=".$ar_posts['forum_posts_id']."&amp;faction=quote&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."#editor\" title=\""._FORUM_POST_QUOTE_TITLE."\">"._FORUM_POSTS_QUOTE."</a>&nbsp;&nbsp;";}
								if ($_SESSION['loginid'] != $ar_posts['forum_posts_author_id']){echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;pid=".$ar_posts['forum_posts_id']."&amp;faction=reportit&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."#editor\" title=\""._FORUM_POST_REPORT_IT_TITLE."\">"._FORUM_POST_REPORT_IT."</a>&nbsp;&nbsp;"; }
								echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;pid=".$ar_posts['forum_posts_id']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."#editor\" title=\""._FORUM_POST_NEW."\">"._FORUM_POST_NEW."</a>";
							}
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td colspan=\"2\" valign=\"top\" class=\"forum-posts-row4\">\n";
		   							if ($forum_posts_subject != ""){echo "<h4>".$forum_posts_subject."</h4>";}
			echo 						$forum_posts_text;
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td valign=\"top\" class=\"forum-posts-row5\">"; 
			echo "					<div class=\"forum-posts-signature\">";
										if ($ar_setup['setup_forum_allow_sig'] == 1){echo stripslashes($ar_author['admin_info_sig_html']);}
			echo "					</div>";
									if ($num_posts_edit_log > 0){echo _FORUM_POST_EDITED_1.GetNickName($ar_posts_edit_log['forum_posts_edit_log_admin_id'])._FORUM_POST_EDITED_2.FormatDatetime($ar_posts_edit_log['forum_posts_edit_time'],"d.m.Y H:i:s")._FORUM_POST_EDITED_3.$num_posts_edit_log._FORUM_POST_EDITED_4;} 
									echo "&nbsp;";
			echo "				</td>\n";
			echo "				<td valign=\"top\" class=\"forum-posts-row6\">&nbsp;</td>\n";
			echo "			</tr>\n";
			echo "		</table>\n";
			echo "	</td>\n";
			echo "</tr>";
			$i++;
		}
		if ($stw2 > 1){ 
			echo "<tr>";
			echo "	<td align=\"left\" valign=\"top\" class=\"forum-main-col\" colspan=\"2\">";
			/* Zobrazeni cisla poctu stranek */
			for ($i=1;$i<=$stw2;$i++) {
				if ($page == $i) {
					echo " <strong>".$i."</strong> ";
				} else {
					echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$i."\">".$i."</a> ";
				}
			}
			/* Zobrazeni sipek s predchozimi a dalsimi strankami novinek */
			echo "<br>";
			if ($page > 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$pp."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a> <--";} 
			if ($page < $stw2){ echo "|--> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$np."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a>";}
			echo "	</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		echo "<table class=\"post-editor\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
		if ($_SESSION['loginid'] != "" && CheckPriv("groups_forum_del") == 1 || $admini2 == "TRUE"){
			echo "	<tr>\n";
			echo "		<td class=\"post-editor-row1\" valign=\"top\"><strong>"._FORUM_POSTS_DEL_REASON."</strong></td>\n";
			echo "		<td class=\"post-editor-row2\"><input class=\"post\" style=\"width: 500px\" maxlength=\"150\" size=\"50\" name=\"forum_posts_del_reason\" value=\"\"><br>\n";
			echo "				<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
			echo "				<input type=\"hidden\" name=\"mode\" value=\"forum_del_posts\">\n";
			echo "				<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
			echo "				<input type=\"hidden\" name=\"ppid\" value=\"".AGet($_GET,'id2')."\">\n";
			echo "				<input type=\"submit\" value=\""._FORUM_DELP."\" class=\"eden_button\">\n";
			echo "			</form>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		/* Zajistime ze se veci zobrazi jen opravdu tem pro ktere jsme je zobrazit chteli */
		if (ForumCheckPriv($ar_topic['forum_topic_admin_add'],$ar_topic['forum_topic_author_id']) == 1){
			echo "	<tr>\n";
			echo "		<td colspan=\"2\"><form name=\"post\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."&amp;project=".$_SESSION['project']."\" method=\"post\" onsubmit=\"return checkForm(this)\">\n";
			echo "			<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."#editor\" title=\""._FORUM_POST_NEW."</a>\">"._FORUM_POST_NEW."</a></td>\n";
			echo "	</tr>	\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"2\"><a name=\"editor\">&nbsp;</a></td>\n";
			echo "	</tr>\n";
			if (AGet($_GET,'msg')){
				echo "	<tr>\n";
				echo "		<td class=\"post-editor-row1\" valign=\"top\">&nbsp;</td>\n";
				echo "		<td class=\"post-editor-row2\"><span class=\"post-forum-err\">".Msg(AGet($_GET,'msg'))."</span></td>\n";
				echo "	</tr>";
			}
			if ($_GET['faction'] == "post_preview"){
				$allowtags = "";
				$forum_post = $_GET['forum_post'];
				$forum_post_reason = $_GET['forum_posts_reason'];
				$forum_post_subject = strip_tags(urldecode($_GET['forum_post_subject']),$allowtags);
				$forum_bb = new BB_to_HTML_Code();
				$forum_preview = $forum_bb->parse($forum_post);
				//$forum_preview = strip_tags($forum_preview,$allowtags);
				$forum_preview = '<h4>'.$forum_post_subject.'</h4>'.$forum_preview;
				echo "<tr>\n";
				echo "	<td valign=\"top\" class=\"post-editor-row1\"><strong>"._POST_EDITOR_PREVIEW."</strong></td>\n";
				echo "	<td class=\"post-editor-preview\">\n";
				echo "		".$forum_preview."\n";
				echo "	</td>\n";
				echo "</tr>\n";
			} else {
				$forum_post = FALSE;
			}
			if ($_GET['faction'] == "edit_post" || AGet($_GET,'msg') == "forum_edit_post_without_reason"){
				echo "<tr>\n";
				echo "	<td class=\"post-editor-row1\" valign=\"top\">"._FORUM_POST_EDIT_REASON."</td>\n";
				echo "	<td class=\"post-editor-row2\"><textarea name=\"forum_post_reason\" cols=\"60\" rows=\"4\">"; if($_GET['faction'] == "edit_post" || $_GET['faction'] == "post_preview"){echo $forum_post_reason;} echo "</textarea></td>\n";
				echo "</tr>\n";
			}
			echo "<tr>\n";
			echo "	<td class=\"post-editor-row1\">"._FORUM_POST_SUBJECT."</td>\n";
			echo "	<td class=\"post-editor-row2\"><input class=\"post\" style=\"width: 500px\" tabindex=\"2\" maxlength=\"50\" size=\"45\" name=\"forum_post_subject\" value=\""; if($_GET['faction'] == "edit_post" || $_GET['faction'] == "post_preview"){echo $forum_post_subject;} echo "\"> </td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"post-editor-row1\" valign=\"top\">"._FORUM_POST."</td>\n";
			echo "	<td class=\"post-editor-row2\" valign=\"top\">";
					$post_editor = new PostEditor;
					$post_editor->editor_name = "forum_post";
					$post_editor->form_name = "post";
					$post_editor->form_text = $forum_post;
					$post_editor->table_width = "500";
					$post_editor->textarea_width = "500";
					$post_editor->textarea_rows = "15";
					$post_editor->textarea_cols = "60";
					
					$post_editor->BBEditor();
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td class=\"post-editor-row1\" valign=\"top\">"._FORUM_POST_OTHER_SETTINGS."</td>\n";
			echo "	<td class=\"post-editor-row2\">\n";
			echo "		<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">";
						/*<tr>
							<td><input type="checkbox" value="1" name="disable_bbcode"> </td>
							<td class="gensmall">Zakázat značky v tomto příspěvku</td>
						</tr>
						<tr>
							<td><input type="checkbox" value="1" name="disable_smilies"> </td>
							<td class="gensmall">Zakázat Smajlíky v tomto příspěvku</td>
						</tr>
						<tr>
							<td><input type="checkbox" value="1" name="notify"> </td>
							<td class="gensmall">Upozornit mne, přijde-li odpověď</td>
						</tr> */
			echo "			<tr>\n";
			echo "				<td><input type=\"checkbox\" checked value=\"1\" name=\"forum_attach_sig\"> </td>\n";
			echo "				<td>"._FORUM_POST_OTHER_SETTINGS_ATTACH_SIG."</td>\n";
			echo "			</tr>\n";
			echo "		</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td colspan=\"2\" height=\"28\">\n";
			echo "	<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
			echo "	<input type=\"hidden\" name=\"pid\" value=\""; if (AGet($_GET,'msg') == "forum_edit_post_without_reason"){echo $_GET['pid'];}else{ echo AGet($ar_post,'forum_posts_id');} echo "\">\n";
			echo "	<input type=\"hidden\" name=\"tid\" value=\"".AGet($_GET,'id2')."\">\n";
			echo "	<input type=\"hidden\" name=\"mode\" value=\""; if($_GET['faction'] == "posts" || $_GET['faction'] == "quote" || $_GET['faction'] == "post_preview"){echo "forum_add_posts";} else {echo "forum_edit_posts";} echo "\">\n";
			echo "	<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
			echo "	<input type=\"submit\" value=\""._FORUM_SEND."\" class=\"eden_button\">&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"form_send_mode\" value=\"1\" checked>"._FORUM_SEND_SEND."&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"form_send_mode\" value=\"2\">"._FORUM_SEND_PREVIEW."\n";
			echo "	</form>\n";
			echo "	</td>\n";
			echo "</tr>";
		} else {
			echo "<tr>";
			echo "	<td colspan=\"2\" class=\"red\">"._FORUM_POSTS_ADD_DISALLOW."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		POSTA							 																	
*																											
***********************************************************************************************************/
function ForumPM(){
	
	global $db_forum_pm,$db_admin,$db_forum_pm_log;
	global $url_admins;
	global $eden_cfg;
	
	$_GET['faction'] = AGet($_GET,'faction');
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	/* Nacteme data o uzivateli */
	$res_adm = mysql_query("SELECT admin_id, admin_hits FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_adm = mysql_fetch_array($res_adm);
	
	/* Nacteme data ostatnich uzivatelu */
	$res_admins = mysql_query("SELECT admin_id, admin_nick FROM $db_admin WHERE admin_reg_allow=1 AND admin_id <> ".(integer)$_SESSION['loginid']." ORDER BY admin_nick") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	if (AGet($_POST,'send') == "true"){
		$nums = count($_POST['del']);
		$del = $_POST['del'];
		$z = 0;
		while ($z < $nums) { // Kdyz je $z mensi nes pocet obrazku v danem clanku
			$int_del = $del[$z];
			$res_del = mysql_query("SELECT forum_pm_del FROM $db_forum_pm WHERE forum_pm_id=".(integer)$int_del) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_del = mysql_fetch_array($res_del);
			if ($ar_del['forum_pm_del'] != $ar_adm['admin_id'] && $ar_del['forum_pm_del'] != 0 && $ar_del['forum_pm_del'] != ""){
				// Kdyz je dany prispevek zatrhnuty ke smazani
				mysql_query("DELETE FROM $db_forum_pm WHERE forum_pm_id=".(integer)$int_del."") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); // Odstrani se ze zaznamu v databazi
			} else {
				mysql_query("UPDATE $db_forum_pm SET forum_pm_del=".(integer)$ar_adm['admin_id']." WHERE forum_pm_id=".(integer)$int_del) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
			
		$z++; // Pricte se 1 aby mohlo cele kolo pokracovat
		}
	}
	
	$res_pm = mysql_query("SELECT COUNT(*) FROM $db_forum_pm 
	WHERE forum_pm_recipient_id=".(integer)$_SESSION['loginid']." OR forum_pm_author_id=".(integer)$_SESSION['loginid']." AND forum_pm_del<>".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_pm = mysql_fetch_array($res_pm);
	
	$res_pm_adm = mysql_query("SELECT COUNT(*) FROM $db_forum_pm WHERE forum_pm_recipient_id=".(integer)$_SESSION['loginid']." AND forum_pm_del<>".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$num_pm_adm = mysql_fetch_array($res_pm_adm);
	
	$res_pm_log = mysql_query("SELECT forum_pm_log_posts FROM $db_forum_pm_log WHERE forum_pm_log_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_pm_log = mysql_fetch_array($res_pm_log);
	$num_pm_log = mysql_num_rows($res_pm_log);
	if ($num_pm_log < 1){
		mysql_query("INSERT INTO $db_forum_pm_log VALUES('".(integer)$_SESSION['loginid']."','1',NOW())") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	} else {
		/* Pricteme +1 k poctu zaslanych privatnich zprav */
		mysql_query("UPDATE $db_forum_pm_log SET forum_pm_log_posts=".(integer)$num_pm_adm[0].", forum_pm_log_logtime=NOW() 
		WHERE forum_pm_log_admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
		  	UserMenu();
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\"><h2>"._FORUM_PM."</h2></td>\n";
	echo "	<tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
	echo "	<tr>\n";
	echo "		<td><a name=\"editor\">&nbsp;</a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><script type=\"text/javascript\">\n";
	echo "		<!--\n";
	echo "		function CheckFormPM(formular){if(formular.pm_rec.value==0){alert(\""._ERR_FORUM_NO_PM_REC."\");return false;}else if(formular.pm_post.value==\"\"){alert(\""._ERR_FORUM_NO_PM_POST."\");return false;}else{return true;}}\n";
	echo "		//-->\n";
	echo "		</script>\n";
	echo "		<form name=\"pm\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=pm\" method=\"post\" onsubmit=\"return CheckFormPM(this)\">\n";
	echo "		<select name=\"pm_rec\" size=\"1\">\n"; 
 					echo "<option value=\"0\">"._FORUM_CHOOSE_RECIPIENT."</option>\n";
					while ($ar_admins = mysql_fetch_array($res_admins)){
						echo "<option value=\"".$ar_admins['admin_id']."\" ";
						if (AGet($_GET,'pm_rec') == $ar_admins['admin_id']) { echo "selected=\"selected\"";}
						echo ">".stripslashes($ar_admins['admin_nick'])."</option>\n";
					}
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	<tr>";
	$pm_post = FALSE;
	if ($_GET['faction'] == "pm_preview"){
		$pm_post = $_GET['pm_post'];
		$pm_bb = new BB_to_HTML_Code();
		$pm_preview = $pm_bb->parse($pm_post);
		echo "<tr>\n";
		echo "	<td class=\"post-editor-preview\">\n";
		echo  		$pm_preview;
		echo "	</td>\n";
		echo "</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td class=\"post-editor-row2\" valign=\"top\">\n";
					/* V pripade ze neni vybran prijemce, se napsany text prenese zpet do formulare - nezmizi */
					if(AGet($_GET,'msg') == "forum_no_pm_rec"){$pm_post = $_GET['pm_post'];}
					
					$pm_editor = new PostEditor;
					$pm_editor->editor_name = "pm_post";
					$pm_editor->form_name = "pm";
					$pm_editor->form_text = $pm_post;
					$pm_editor->table_width = "500";
					$pm_editor->textarea_width = "500";
					$pm_editor->textarea_rows = "10";
					$pm_editor->textarea_cols = "80";
					
					$pm_editor->BBEditor();
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "		<input type=\"hidden\" name=\"id\" value=\"".AGet($_GET,'id')."\">\n";
	echo "		<input type=\"hidden\" name=\"user\" value=\"".$ar_adm['admin_id']."\">\n";
	echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "		<input type=\"hidden\" name=\"mode\" value=\"pm_add_post\">\n";
	echo "		<input type=\"submit\" value=\""._FORUM_SEND."\" class=\"eden_button\">&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"form_send_mode\" value=\"1\" checked>"._FORUM_SEND_SEND."&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"form_send_mode\" value=\"2\">"._FORUM_SEND_PREVIEW."\n";
	echo "		</form><br><br>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"4\" cellpadding=\"4\" border=\"0\" class=\"forum\">";
	
	if (empty($_GET['page'])){$page = 1;} else {$page = $_GET['page'];} // i kdyz je promenna $page prazdna nastavi se pocet starnek na 1
	/* Nastavime pocet prispevku na strance */
	$hits = $ar_adm['admin_hits'];
	if ($hits == 0 || $hits == ""){$hits = 30;}
	
	/* Vypocitame celkovy pocet stranek ktery by byl potrebny pro zobrazeni prispevku z poctu prisspevku v databazi a nastavenemu poctu prispevku na strance */
	$stw2 = ($num_pm[0] / $hits);
	$stw2 = (integer) $stw2;
	if ($num_pm[0] % $hits > 0) {$stw2++;}
	/* Pro prechod o stranku vyse ci nize nastavime  promenne*/
	$np = $page + 1;
	$pp = $page - 1;
	if ($page == 1) { $pp = 1; }
	if ($np > $stw2) { $np = $stw2;} 
	
	if ($stw2 > 1){ 
		echo "<tr>";
		echo "	<td width=\"850\" align=\"left\" valign=\"top\" colspan=\"2\">";
			//Zobrazeni cisla poctu stranek
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong> ";
			} else {
				echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$i."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">".$i."</a> ";
			}
		}
		echo "<br>";
		/* Zobrazeni sipek s predchozimi a dalsimi strankami novinek */
		if ($page > 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$pp."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a> <--";} 
		if ($page < $stw2){ echo "|--> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$np."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a>";}
		echo "		<hr size=\"1\">";
		echo "	</td>";
		echo "</tr>";
	}
	echo "<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;id=".AGet($_GET,'id')."\" method=\"post\">";
	/* Nastavime limit pro ryclejsi zobrazeni prispevku */
	$limit_from = ($page * $hits) - $hits; 
	$limit = (integer)$limit_from." , ".(integer)$hits;
	$res_pm = mysql_query("SELECT forum_pm_id, forum_pm_recipient_id, forum_pm_text_html, forum_pm_text_bb, forum_pm_date, forum_pm_author_id, forum_pm_del FROM $db_forum_pm WHERE forum_pm_recipient_id=".(integer)$_SESSION['loginid']." OR forum_pm_author_id=".(integer)$_SESSION['loginid']." AND forum_pm_del<>".(integer)$_SESSION['loginid']." ORDER BY forum_pm_id DESC LIMIT ".$limit) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	
	$i = 0;
	/* Nastavime pocet prispevku, ktere pribyly */
	$num = $num_pm_adm[0] - $ar_pm_log['forum_pm_log_posts'];
	while ($ar_pm = mysql_fetch_array($res_pm)){
		$post_text = stripslashes($ar_pm['forum_pm_text_html']);
		$datum = formatDatetime($ar_pm['forum_pm_date'],"d.m.y - H:i");
		
		$res_pm_author = mysql_query("SELECT admin_id, admin_nick, admin_userimage FROM $db_admin WHERE admin_id=".(integer)$ar_pm['forum_pm_author_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_pm_author = mysql_fetch_array($res_pm_author);
		$res_pm_rec = mysql_query("SELECT admin_id, admin_nick, admin_userimage FROM $db_admin WHERE admin_id=".(integer)$ar_pm['forum_pm_recipient_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_pm_rec = mysql_fetch_array($res_pm_rec);
		echo "<tr class=\"forum-pm-"; if ($ar_pm['forum_pm_author_id'] == $ar_adm['admin_id']){echo "suda";} else {echo "licha";} echo "\">";
		echo "	<td width=\"20\" valign=\"top\">";
		echo "	  	<input type=\"checkbox\" name=\"del[]\" value=\"".$ar_pm['forum_pm_id']."\">";
		echo "	</td>";
		echo "	<td valign=\"top\" class=\"forum-pm-"; if ($ar_pm['forum_pm_author_id'] == $ar_adm['admin_id']){echo "suda";} else {echo "licha";} echo "\"><span class=\"forum-names\">";
					if ($ar_pm['forum_pm_author_id'] == $ar_adm['admin_id']){
						echo $ar_pm_author['admin_nick'];
					} else {
						echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&pm_user=".$ar_adm['admin_id']."&amp;pm_rec=".$ar_pm_author['admin_id']."&amp;project=".$_SESSION['project']."\">".$ar_pm_author['admin_nick']."</a>";
					}
					echo "</span> [".$datum."]&nbsp;&nbsp;&raquo;&raquo;&nbsp;&nbsp;<span class=\"forum-names\">";
					if ($ar_pm_rec['admin_id'] == $ar_adm['admin_id']){
						echo $ar_pm_rec['admin_nick'];
					} else {
						echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang'].">&amp;filter=".$_GET['filter']."&amp;faction=pm&pm_user=".$ar_adm['admin_id']."&amp;pm_rec=".$ar_pm_rec['admin_id']."&amp;project=".$_SESSION['project']."\">".$ar_pm_rec['admin_nick']."</a>";
					}
					echo "</span>"; 
					/* New comments */
					if (($_SESSION['u_status'] == "admin" || $_SESSION['u_status'] == "user" || $_SESSION['u_status'] == "seller") && $i < $num){
						echo " <span class=\"red\">NEW</span>";
					}
			echo "		<br>";
			echo $post_text."<br clear=\"all\">";
			echo "	</td>";  
			echo "</tr>";
		$i++;
	}
	if ($stw2 > 1){ 
		echo "<tr>";
		echo "	<td width=\"850\" align=\"left\" valign=\"top\" class=\"sloupec-hlavni\" colspan=\"2\">";
		/* Zobrazeni cisla poctu stranek */
		for ($i=1;$i<=$stw2;$i++) {
			if ($page == $i) {
				echo " <strong>".$i."</strong> ";
			} else {
				echo " <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$i."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">".$i."</a> ";
			}
		}
		echo "<br>";
		/* Zobrazeni sipek s predchozimi a dalsimi strankami novinek */
		if ($page > 1){ echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$pp."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_PREVIOUS."</a> <--";} 
		if ($page < $stw2){ echo "|--> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;page=".$np."&amp;hits=".$hits."&amp;project=".$_SESSION['project']."\">"._CMN_NEXT."</a>";}
		echo "	</td>";
		echo "</tr>";
	}
	/* Formular pro odstraneni pm zobrazime teprve tehdy, kdyz je zobrazena najaka Soukroma posta */
	if ($num_pm > 0){
		echo "<tr>\n";
		echo "	<td colspan=\"2\">\n";
		echo "			<input type=\"hidden\" name=\"send\" value=\"true\">\n";
		echo "			<input type=\"hidden\" name=\"user\" value=\"".$_SESSION['loginid']."\">\n";
		echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "			<input type=\"hidden\" name=\"id\" value=\"".AGet($_GET,'id')."\">\n";
		echo "			<input type=\"submit\" value=\""._FORUM_DELP."\" class=\"eden_button\">\n";
		echo "		</form>\n";
		echo "	</td>\n";
		echo "</tr>";
	}
	echo "</table>\n"; 
}
/***********************************************************************************************************
*																											
*		OSTATNI UZIVATELE																					
*																											
*		$sp_width		=	Sirka tabulky																	
*																											
***********************************************************************************************************/
function ForumOtherUsers($sp_width){
	
	global $db_admin,$db_setup;
	global $url_admins;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if (AGet($_POST,'confirm') == "true"){
		$res = mysql_query("SELECT admin_friends FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$pratele = explode (" ", $ar['admin_friends']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$result = array_merge($pratele, $_POST['friends']); // Do pole $result se ulozi na konec ten pridavany uzivatel
		$result = array_unique($result);
		$colon_separated = implode (" ", $result); // To vse se pak ulozi do retezce oddelene mezerami
		mysql_query("UPDATE $db_admin SET admin_friends='".mysql_real_escape_string($colon_separated)."' WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	echo "<table cellspacing=\"1\" cellpadding=\"2\" class=\"forum\">\n";
	UserMenu();
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\"><h2>"._FORUM_OTHERUSERS."</h2></td>\n";
	echo "	<tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" class=\"forum\">\n";
	echo "	<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=otherusers\" method=\"post\">";
 	$res = mysql_query("SELECT admin_friends FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$res2 = mysql_query("SELECT admin_id, admin_userimage, admin_nick, admin_userinfo FROM $db_admin WHERE admin_reg_allow=1 ORDER BY admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar2 = mysql_fetch_array($res2)){
		$pratele = explode (" ", $ar['admin_friends']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		if (in_array ($ar2['admin_id'], $pratele)){$pritel = "TRUE";} else {$pritel = "FALSE";}
		echo "<tr align=\"center\" "; if ($pritel == "TRUE"){ echo "bgcolor=\"#FFDEDF\""; } echo ">\n";
		echo "	<td width=\"30\" valign=\"top\">\n";
					if ($ar2['admin_id'] != $_SESSION['loginid'] && $pritel != "TRUE"){ echo "<input type=\"checkbox\" name=\"friends[]\" value=\"".$ar2['admin_id']."\">"; }
		echo "	</td>\n";
		echo "	<td width=\"".GetSetupImageInfo("avatar","width")."\" align=\"left\"><img src=\"".$url_admins.$ar2['admin_userimage']."\" alt=\"".$ar2['admin_nick']."\" align=\"left\"></td>\n";
		echo "	<td align=\"left\" valign=\"top\"><span class=\"forum-names\">\n";
					if ($ar2['admin_id'] == $_SESSION['loginid']){
						echo $ar2['admin_nick'];
					} else {
						echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;project=".$_SESSION['project']."&amp;pm_rec=".$ar2['admin_id']."\">".$ar2['admin_nick']."</a>\n";
	 					if ($pritel == "TRUE"){
							echo "&nbsp;&nbsp;-&nbsp;&nbsp;"._FORUM_FRIEND;
	 					}
					}
		echo "		</span><br>\n";
		echo "		".$ar2['admin_userinfo']."\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
	echo "	<tr align=\"center\">\n";
	echo "		<td width=\"30\" colspan=\"3\">\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	echo "			<input type=\"submit\" value=\""._FORUM_ADD_TO_FRIENDS."\" class=\"eden_button\">\n";
	echo "			</form>	\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		PRATELE																								
*																											
***********************************************************************************************************/
function ForumFriends(){
	
	global $db_admin,$url_admins;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	
	if (AGet($_POST,'confirm') == "TRUE"){
		$res = mysql_query("SELECT admin_id, admin_friends FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$pratele = explode (" ", $ar['admin_friends']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		$result = array_diff ($pratele, $_POST['friends']);
		$colon_separated = implode (" ", $result); // To vse se pak ulozi do retezce oddelene mezerami
		mysql_query("UPDATE $db_admin SET admin_friends='".mysql_real_escape_string($colon_separated)."' WHERE admin_id=".(integer)$ar['admin_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	}
	echo "<table cellspacing=\"3\" cellpadding=\"2\" class=\"forum\">\n";
   	UserMenu();
	echo "	<tr>\n";
	echo "		<td align=\"left\" class=\"nadpis\"><h2>"._FORUM_FRIENDS."</h2></td>\n";
	echo "	<tr>\n";
	echo "</table>\n";
	echo "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" class=\"forum\">\n";
	echo "	<form action=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=friends\" method=\"post\">";
 	$res = mysql_query("SELECT admin_friends FROM $db_admin WHERE admin_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	$res2 = mysql_query("SELECT admin_id, admin_userimage, admin_nick, admin_userinfo FROM $db_admin WHERE admin_reg_allow=1 ORDER BY admin_nick ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while($ar2 = mysql_fetch_array($res2)){
		$pratele = explode (" ", $ar['admin_friends']); // Rozdeli se pole $ar[admin] na jednotlive uzivatele
		if (in_array ($ar2['admin_id'], $pratele)){$pritel = TRUE;} else {$pritel = FALSE;}
		if ($pritel == TRUE){
			echo "<tr align=\"center\">\n";
			echo "	<td width=\"30\">\n";
			echo "		<input type=\"checkbox\" name=\"friends[]\" value=\"".$ar2['admin_id']."\">\n";
			echo "	</td>\n";
			echo "	<td width=\"50\" align=\"left\"><img src=\"".$url_admins.$ar2['admin_userimage']."\" width=\"40\" height=\"50\" border=\"0\" alt=\"".$ar2['admin_nick']."\" align=\"left\"></td>\n";
			echo "	<td width=\"670\" align=\"left\" valign=\"top\"><span class=\"forum-names\">\n";
						if ($ar2['admin_id'] == $_SESSION['loginid']){
							echo $ar2['admin_nick'];
						} else {
							echo "<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=pm&amp;pm_rec=".$ar2['admin_id']."&amp;project=".$_SESSION['project']."\">".$ar2['admin_nick']."</a>\n";
						}
			echo "		</span><br>\n";
			echo "		".$ar2['admin_userinfo']."\n";
			echo "	</td>\n";
			echo "	</tr><\n";
		}
	}
	echo "	<tr align=\"center\">\n";
	echo "		<td width=\"30\" colspan=\"3\">\n";
	echo "			<br>\n";
	echo "			<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
	echo "			<input type=\"hidden\" name=\"confirm\" value=\"TRUE\">\n";
	echo "			<input type=\"submit\" value=\""._FORUM_DEL_FROM_FRIENDS."\" class=\"eden_button\">\n";
	echo "			</form>	\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>";
}
/***********************************************************************************************************
*																											
*		REPORT IT						 																	
*																											
***********************************************************************************************************/
function ForumReportIt(){
	
	global $db_forum_topic,$db_forum_posts,$db_setup,$db_setup_lang;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($_SESSION['loginid'] != ""){
		
		if ($_GET['lang'] == ""){$setup_lang = "s.setup_basic_lang";} else {$setup_lang = $_GET['lang'];}
		$res_setup = mysql_query("SELECT sl.setup_lang_forum_title FROM $db_setup AS s, $db_setup_lang AS sl WHERE sl.setup_lang='".$setup_lang."'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_setup = mysql_fetch_array($res_setup);
		
		$res_topic = mysql_query("SELECT forum_topic_admin, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id2')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_topic = mysql_fetch_array($res_topic);
		
		$res_head_topic = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id1')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_head_topic = mysql_fetch_array($res_head_topic);
		
		$res_forum = mysql_query("SELECT forum_topic_id, forum_topic_name FROM $db_forum_topic WHERE forum_topic_id=".(integer)AGet($_GET,'id0')) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_forum = mysql_fetch_array($res_forum);
		
		$res_post = mysql_query("SELECT forum_posts_id, forum_posts_author_id, forum_posts_text, forum_posts_subject FROM $db_forum_posts WHERE forum_posts_id=".(integer)$_GET['pid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_post = mysql_fetch_array($res_post);
		$forum_posts_text = str_replace( "&lt;","<",$ar_post['forum_posts_text']);
		$forum_posts_text = str_replace( "&gt;",">",$forum_posts_text);
		$forum_posts_text = str_replace( "&amp;","&",$forum_posts_text);
		$forum_posts_text = str_replace( "&quot;","'",$forum_posts_text);
		$forum_posts_text = str_replace( "&acute;","'",$forum_posts_text);
		
		$forum_posts_subject = PrepareFromDB($ar_post['forum_posts_subject'],1);
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">";
		UserMenu();
		echo "	<tr>\n";
		echo "	<td align=\"left\" colspan=\"3\" class=\"forum-title\"><h1>"._FORUM_POST_REPORT_IT_TITLE.": <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."&amp;project=".$_SESSION['project']."\">".$ar_topic['forum_topic_name']."</a></h1>\n";
		echo "		<a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."\">".$ar_setup['setup_lang_forum_title']."</a> >> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_forum['forum_topic_name'])."</a> >> <a href=\"index.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=topics&amp;id0=".$ar_forum['forum_topic_id']."&amp;id1=".$ar_head_topic['forum_topic_id']."&amp;project=".$_SESSION['project']."\">".stripslashes($ar_head_topic['forum_topic_name'])."</a></td>\n";
		echo "	</tr>\n";
		echo "</table> 	\n";
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" valign=\"top\">"._FORUM_POST_REPORT_IT_POST."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" valign=\"top\" class=\"forum-posts-report-text\">\n";
						if ($forum_posts_subject != ""){echo "<h4>".$forum_posts_subject."</h4>";}
						echo $forum_posts_text;
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"200\" align=\"right\" valign=\"top\"><form name=\"reportit\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=posts&amp;id0=".AGet($_GET,'id0')."&amp;id1=".AGet($_GET,'id1')."&amp;id2=".AGet($_GET,'id2')."&amp;page=".$_GET['page']."\" method=\"post\">\n";
		echo "		"._FORUM_POST_REPORT_IT_HELP."</td>\n";
		echo "		<td valign=\"top\"><textarea cols=\"50\" rows=\"10\" name=\"forum_posts_report_it_reason\"></textarea></td>\n";
		echo "	<tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"hidden\" name=\"pid\" value=\"".$_GET['pid']."\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "		<input type=\"hidden\" name=\"mode\" value=\"forum_report_post\">\n";
		echo "		<input type=\"submit\" value=\""._FORUM_SEND."\" class=\"eden_button\">\n";
		echo "		</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		FORUM SETUP						 																	
*																											
***********************************************************************************************************/
function ForumUserSetup(){
	
	global $db_admin_info;
	global $eden_cfg;
	
	$_GET['filter'] = AGet($_GET,'filter');
	$_GET['lang'] = AGet($_GET,'lang');
	$_SESSION['loginid'] = AGet($_SESSION,'loginid');
	
	if ($_SESSION['loginid'] != ""){
		
		$res_admin = mysql_query("SELECT admin_info_forum_posts_order FROM $db_admin_info WHERE aid=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_admin = mysql_fetch_array($res_admin);
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">";
		UserMenu();
		echo "	<tr>\n";
		echo "	<td align=\"left\" colspan=\"3\" class=\"forum-title\"><h1>"._FORUM." - "._FORUM_SETUP."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<table cellspacing=\"0\" cellpadding=\"4\" class=\"forum\">\n";
		echo "	<tr>\n";
		echo "		<td width=\"200\" align=\"right\" valign=\"top\"><form name=\"reportit\" action=\"".$eden_cfg['url_edencms']."eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;project=".$_SESSION['project']."&amp;faction=setup\" method=\"post\">\n";
		echo "		"._FORUM_POSTS_ORDER."</td>\n";
		echo "		<td valign=\"top\"><input type=\"radio\" name=\"admin_info_forum_posts_order\" value=\"0\" "; if ($ar_admin['admin_info_forum_posts_order'] == 0){echo "checked";} echo "> "._FORUM_POSTS_ORDER_ASC."&nbsp;&nbsp;<input type=\"radio\" name=\"admin_info_forum_posts_order\" value=\"1\" "; if ($ar_admin['admin_info_forum_posts_order'] == 1){echo "checked";} echo ">"._FORUM_POSTS_ORDER_DESC."</td>\n";
		echo "	<tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">&nbsp;</td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "		<input type=\"hidden\" name=\"project\" value=\"".$_SESSION['project']."\">\n";
		echo "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
		echo "		<input type=\"hidden\" name=\"mode\" value=\"forum_setup\">\n";
		echo "		<input type=\"submit\" value=\""._FORUM_SEND."\" class=\"eden_button\">\n";
		echo "		</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>";
	}
}
/***********************************************************************************************************
*																											
*		HLAVICKA						 																	
*																											
***********************************************************************************************************/
function Hlavicka($title){
	
	global $hlaska,$msg,$project,$meta,$faction,$confirm,$title;
	
	switch ($msg) {
	case "logout"; 
		$hlaska = _LOGOUT;
	break;
	case "badlogin";
		$hlaska = _BADLOGIN;
	break;
		case "badproject";
		$hlaska = _BADPROJECT;
	break;
		case "tryagain1";
		$hlaska = _TRYAGAIN;
	break;
		case "tryagain2";
		$hlaska = _TRYAGAIN;
	break;
	default;
		$hlaska = "";
	}
}