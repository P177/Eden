<?php

//error_reporting(E_ALL);
/***********************************************************************************************************
*																											
*		NETTE																								
*																											
***********************************************************************************************************/
/*
use Nette\Diagnostics\Debugger;
	
// load libraries
require ("./class/Nette/loader.php");

// enable Debugger
Debugger::$logDirectory = __DIR__ . '/log';
Debugger::$strictMode = false;
Debugger::enable();
*/
require_once("./inc.config.php");
if (isset($_SESSION['project'])){$project = $_SESSION['project'];} elseif ($_GET['project']) {$project = $_GET['project'];} else {$project = $_POST['project'];}
if (isset($project)) {$_SESSION['project'] = $project;}
if (isset($_GET['lang'])) {$_SESSION['lang'] = $_GET['lang'];}
if (isset($_GET['web_lang'])) {$_SESSION['web_lang'] = $_GET['web_lang'];}
if (!empty($_SESSION['project'])) {
	$title = "EDEN";
	require_once("./sessions.php");
	require_once("./functions.php");
	require_once("./class/class.mail.php");
	if (empty($_SESSION['web_lang'])){
		$res = mysql_query("SELECT language_code FROM $db_language") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar = mysql_fetch_array($res);
		$_SESSION['web_lang'] = $ar['language_code'];
	}
	$res_setup = mysql_query("SELECT setup_eden_editor_style FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<title>".$title_maintanance.$eden_title."</title>\n";
	echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/eden.css\">\n";
	echo "	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "	<meta name=\"generator\" content=\"HandMade\">\n";
	echo "	<meta name=\"author\" content=\"BlackFoot - www.blackfoot.cz\">\n";
	echo "	<meta name=\"robots\" content=\"all,follow\">\n";
	echo "	<meta name=\"copyright\" content=\"© 2002, BlackFoot\">\n";
	echo "	<meta http-equiv=\"content-language\" content=\"cs\">";
	$res3 = mysql_query("SELECT forum_pm_date FROM $db_forum_pm WHERE forum_pm_recipient_id=".(integer)$_SESSION['loginid']." ORDER BY forum_pm_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar3 = mysql_fetch_array($res3);
	$res = mysql_query("SELECT forum_posts_log_logtime FROM $db_forum_posts_log WHERE forum_posts_log_admin_id=".(integer)$_SESSION['loginid']." AND forum_posts_log_forum_topic_id=".(integer)$_SESSION['loginid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"./js/spiffyCal/spiffyCal_v2_1.css\">\n";
	echo "<script type=\"text/javascript\" src=\"./js/spiffyCal/spiffyCal_v2_1.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"./js/ajax.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"./js/ajax-dynamic-list.js\"></script>";
	echo "<script type=\"text/javascript\" src=\"./js/jquery.js\"></script>";
	// For TinyMCE
	$tiny_settings_geleral = '
		language : "en",
		mode : "textareas",
		theme : "advanced",
		element_format : "html",
		entities : "160,nbsp,38,amp,34,quot,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8240,permil,60,62,8804,le,8805,ge,176,deg,8722,minus",
		//forced_root_block : "",
		//force_p_newlines : false,
		//remove_linebreaks : false,
		//force_br_newlines : true,
		//remove_trailing_nbsp : true,
		verify_html : false,
		entity_encoding : "raw", 
		convert_urls : false,';
	
	$tiny_settings_theme_options = '
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,';
	
	$tiny_settings_plugins = 'plugins : "style,table,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,noneditable,nonbreaking,xhtmlxtras,template,wordcount,advlist,flags,cards,mtgcards,eimage",';
	
	$tiny_settings_plugin_options = '
		plugin_flags_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",
		plugin_cards_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",
		plugin_mtgcards_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",
		plugin_eimage_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",
		plugin_embed_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",
		plugin_eim_url : "?project='.$_SESSION['project'].'&lang='.$_SESSION['lang'].'",';
	
	$tiny_settings_extended_valid_elements = 'extended_valid_elements : "a[href|target|name|title|rel|class],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],br[class|clear|id|style|title],object[id|classid|codebase|width|height],param[name|value],embed[src|type|wmode|width|height],small",';
	
	$tiny_settings_style_formats = "
		style_formats : [
			{title : 'First line', block : 'p', classes : 'first_line'},
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		]";
	?>
		<!-- TinyMCE -->
		<script type="text/javascript">
			var project = "<?php echo $_SESSION['project'];?>";
			var adminid = "<?php echo $_SESSION['loginid'];?>";
			var lang = "<?php echo $_SESSION['lang'];?>";
			var eden_local_url = "<?php echo $eden_cfg['misc_local'];?>";
			var eden_cms_url = "<?php echo $eden_cfg['url_cms'];?>";
		</script>
		<script type="text/javascript" src="./js/tiny_mce/plugins/eim/eim.js"></script>
		<script type="text/javascript" src="./js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript"><?php
		/* Clanky */
		if (!isset($tinymce_init_mode)){$tinymce_init_mode = "";} // Inicializujeme prommenou pokud jeste inicializovana neni
		
		if ($tinymce_init_mode == "article"){?>
		
			
			// Perex 
			tinyMCE.init({
				// General options
				<?php echo $tiny_settings_geleral; ?>
				editor_selector : "article_perex",

				// Plugins
				<?php echo $tiny_settings_plugins; ?>

				// Plugins options
				<?php echo $tiny_settings_plugin_options; ?>
				
				// EdenImageManager
				file_browser_callback : "openEdenImageManager",
				
				// Extended valid elements
				<?php echo $tiny_settings_extended_valid_elements; ?>
				 
				// Theme options
				theme_advanced_buttons1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,sub,sup,|,nonbreaking,template",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,flags,cards,mtgcards,image,cleanup,help,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,media,advhr|,cite,abbr,acronym,del,ins,attribs",
				<?php echo $tiny_settings_theme_options; ?>
								
				// Example content CSS (should be your site CSS)			
				<?php if ($_SESSION['project'] == "bludr"){ echo "content_css : \"http://www.modrak.cz/css/bludr_tinymce.css\",";} ?>
				
				// Style formats
				<?php echo $tiny_settings_style_formats; ?>
			});
			

			// Body
			tinyMCE.init({
				// General options
				<?php echo $tiny_settings_geleral; ?>
				editor_selector : "article_body",
				
				// Plugins
				<?php echo $tiny_settings_plugins; ?>
				
				// Plugins options
				<?php echo $tiny_settings_plugin_options; ?>
				
				// EdenImageManager
				file_browser_callback : "openEdenImageManager",
				
				// Extended valid elements
				<?php echo $tiny_settings_extended_valid_elements; ?>
				
				// Theme options
				theme_advanced_buttons1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,sub,sup,|,nonbreaking,template",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,flags,cards,mtgcards,image,cleanup,help,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,media,advhr|,cite,abbr,acronym,del,ins,attribs",
				<?php echo $tiny_settings_theme_options; ?>
							
				// Example content CSS (should be your site CSS)
				<?php if ($_SESSION['project'] == "bludr"){ echo "content_css : \"http://www.modrak.cz/css/bludr_tinymce.css\",";} ?>
		 
				//Templates
				template_cdate_classes : "cdate creationdate",
		        template_mdate_classes : "mdate modifieddate",
		        template_selected_content_classes : "selcontent",
		        template_cdate_format : "%m/%d/%Y : %H:%M:%S",
		        template_mdate_format : "%m/%d/%Y : %H:%M:%S",
		        template_replace_values : {
		                username : "Jack Black",
		                staffid : "991234"
		        },
		        template_templates : [
		        	<?php echo $eden_cfg['tinymce_templates'];?>
		        ],
					 
				// Style formats
				<?php echo $tiny_settings_style_formats; ?>
			});

		<?php
		
		
		/* Aktuality */
		} elseif ($tinymce_init_mode == "act"){?>
			// Preview 
			tinyMCE.init({
				// General options
				<?php echo $tiny_settings_geleral; ?>
				editor_selector : "news_text",

				// Plugins
				<?php echo $tiny_settings_plugins; ?>
				
				// Plugins options
				<?php echo $tiny_settings_plugin_options; ?>
				
				// EdenImageManager
				file_browser_callback : "openEdenImageManager",
				
				// Extended valid elements
				<?php echo $tiny_settings_extended_valid_elements; ?>
				 
				// Theme options
				theme_advanced_buttons1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,sub,sup,|,nonbreaking",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,flags,cards,cleanup,help,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,advhr|,cite,abbr,acronym,del,ins,attribs",
				<?php echo $tiny_settings_theme_options; ?>
				
				// Example content CSS (should be your site CSS)
				<?php if ($_SESSION['project'] == "bludr"){ echo "content_css : \"http://www.modrak.cz/css/bludr_tinymce.css\",";} ?>
		 
				// Style formats
				<?php echo $tiny_settings_style_formats; ?>
			});<?php
		
			
		/* Shop */
		} elseif ($tinymce_init_mode == "shop_product"){?>
			// Short 
			tinyMCE.init({
				// General options
				<?php echo $tiny_settings_geleral; ?>
				editor_selector : "prod_short",

				// Plugins
				<?php echo $tiny_settings_plugins; ?>
				
				// Plugins options
				<?php echo $tiny_settings_plugin_options; ?>
				
				// EdenImageManager
				file_browser_callback : "openEdenImageManager",
				
				// Extended valid elements
				<?php echo $tiny_settings_extended_valid_elements; ?>
								 
				// Theme options
				theme_advanced_buttons1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,sub,sup,|,nonbreaking,template",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,flags,cards,image,cleanup,help,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,media,advhr|,cite,abbr,acronym,del,ins,attribs",
				<?php echo $tiny_settings_theme_options; ?>
			
				// Example content CSS (should be your site CSS)
				<?php if ($_SESSION['project'] == "bludr"){ echo "content_css : \"http://www.modrak.cz/css/bludr_tinymce.css\",";} ?>
				
				// Style formats
				<?php echo $tiny_settings_style_formats; ?>
			});
			
			
			// Shop Long
			tinyMCE.init({
				// General options
				<?php echo $tiny_settings_geleral; ?>
				editor_selector : "prod_long",
								
				// Plugins
				<?php echo $tiny_settings_plugins; ?>
				
				// Plugins options
				<?php echo $tiny_settings_plugin_options; ?>
				
				// EdenImageManager
				file_browser_callback : "openEdenImageManager",
				
				// Extended valid elements
				<?php echo $tiny_settings_extended_valid_elements; ?>
							 
				// Theme options
				theme_advanced_buttons1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,sub,sup,|,nonbreaking,template",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,flags,cards,image,cleanup,help,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,media,advhr|,cite,abbr,acronym,del,ins,attribs",
				<?php echo $tiny_settings_theme_options; ?>
				
				// Example content CSS (should be your site CSS)
				<?php if ($_SESSION['project'] == "bludr"){ echo "content_css : \"http://www.modrak.cz/css/bludr_tinymce.css\",";} ?>
		 
				// Style formats
				<?php echo $tiny_settings_style_formats; ?>
			});<?php
		}?>		
		</script>
		<!-- /TinyMCE --><?php
	echo "<script type=\"text/javascript\" src=\"./js/eden.js\"></script>";
	echo "</head>\n";
	echo "<body topmargin=\"0\" leftmargin=\"0\" bgcolor=\"#C0C0C0\">\n";
	echo "<div id=\"spiffycalendar\"></div>\n";
	echo "<div align=\"center\"><table width=\"1000\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" height=\"45\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"141\" height=\"45\" bgcolor=\"#6787BA\"><a href=\"sys_statistics.php?project=".$_SESSION['project']."\">".$eden_logo."</a></td>\n";
	echo "		<td width=\"25\" height=\"45\" bgcolor=\"#80A0D3\" align=\"right\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"193\" height=\"45\" bgcolor=\"#80A0D3\" class=\"sys_nadpis2\">IP<br>".$eden_cfg['ip']."</td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"198\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\"><br></td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_bily.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"198\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\">"._CMN_NEWSDATE."<br>".formattimew(time())."</td>\n";
	echo "		<td width=\"14\" height=\"45\" bgcolor=\"#80A0D3\"><img src=\"images/sys_ramecek_cerny.gif\" width=\"14\" height=\"45\" border=\"0\" alt=\"\"></td>\n";
	echo "		<td width=\"200\" height=\"45\" bgcolor=\"#80A0D3\" align=\"left\" valign=\"middle\" class=\"sys_nadpis2\">"._PROJECT.": <strong>".$_SESSION['project']."</strong><br>\n";
	echo "			"._ADMIN_USERNAME.": <strong>"; if ($eden_cfg['modul_forum'] == true) { echo "<a href=\"modul_forum.php?project=".$_SESSION['project']."&amp;action=posta&amp;user=".$_SESSION['loginid']."\">[".$_SESSION['loginid']."] ".$_SESSION['login']."</a>"; } else {echo "[".$_SESSION['loginid']."] ".$_SESSION['login'];} if ($ar['forum_posts_log_logtime'] < $ar3['forum_pm_date']){ echo "<span class=\"new\">new</span>"; } echo "</strong><br></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<table width=\"1000\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" height=\"27\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"500\" height=\"27\" bgcolor=\"#999999\" align=\"left\" valign=\"middle\" class=\"sys_nadpis1\">"._CMN_ADMINCENTRUM." - ".$_SESSION['lang'];
				$res_lng = mysql_query("SELECT language_code, language_name FROM $db_language") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num_lng = mysql_num_rows($res_lng);
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"._CMN_WEB_LANG;
				if ($num_lng > 1){
					echo "<select  onchange=\"javascript:window.open(this.value,'_self','');\" name=\"setup_basic_lang\" class=\"input\">";
					while ($ar_lng = mysql_fetch_array($res_lng)){
						echo "<option name=\"setup_basic_lang\" value=\"".$_SERVER['SCRIPT_NAME']."?project=".$project."&amp;action=".$action."&web_lang=".$ar_lng['language_code']."\"";
						if ($ar_lng['language_code'] == $_SESSION['web_lang']){echo "selected=\"selected\"";}
						echo ">".$ar_lng['language_name']."</option>";
					}
					echo "</select>";
				} else {
					$ar_lng = mysql_fetch_array($res_lng);
					echo $ar_lng['language_name'];
				}
	echo "		</td>\n";
	echo "		<td width=\"500\" height=\"27\" bgcolor=\"#999999\" align=\"right\" valign=\"middle\" class=\"sys_nadpis1\"><a href=\"sys_statistics.php?lang=cz&amp;project=".$_SESSION['project']."\"><img src=\"images/lang/CZ.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" align=\"middle\"></a>&nbsp;<a href=\"sys_statistics.php?lang=en&amp;project=".$_SESSION['project']."\"><img src=\"images/lang/GB.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" align=\"middle\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"._CMN_LOGOUT."&nbsp;&nbsp;<a href=\"index.php?action=logout\"><img src=\"images/sys_logout.gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" align=\"middle\"></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "\n";
	echo "<table border=\"0\" width=\"1000\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"141\" align=\"left\" valign=\"top\" bgcolor=\"#EBEBEC\">\n";
	echo "			<table width=\"141\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"141\" height=\"36\" class=\"menu_nadpis\">"._MODULS."</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"menu_text\" align=\"left\">\n";
								if ($eden_cfg['modul_shop'] == 1 && CheckPriv("groups_shop_add") == 1) {
									echo "<ul class=\"menu_shop\">\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop.php?action=&amp;project=".$_SESSION['project']."\">"._SHOP_STAT."</a></li>\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop.php?action=prod&amp;project=".$_SESSION['project']."\">"._SHOP_PROD_PRODUCTS."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=add_prod&amp;project=".$_SESSION['project']."\">"._SHOP_PROD_ADD."</a></li>\n";
											if ($eden_cfg['modul_shop_clothes'] == 1){
												echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=clothes_show_designs&amp;project=".$_SESSION['project']."\">"._SHOP_CL_DESIGNS."</a></li>\n";
												echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=clothes_add_design&amp;project=".$_SESSION['project']."\">"._SHOP_CL_DESIGN_ADD_DESIGN."</a></li>\n";
												echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=clothes_add_style_parents&amp;project=".$_SESSION['project']."\">"._SHOP_CL_STYLE."</a></li>\n";
												echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=clothes_add_color&amp;project=".$_SESSION['project']."\">"._SHOP_CL_COLORS."</a></li>\n";
												echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=clothes_add_size&amp;project=".$_SESSION['project']."\">"._SHOP_CL_SIZE_SIZES."</a></li>\n";
											}
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop.php?action=orders&amp;o_mod=1&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=1&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_1."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=2&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_2."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=3&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_3."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=4&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_4."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=5&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_5."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=6&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_6."</a></li>\n";
									echo "	<li class=\"menu_shop_level_1\"><a href=\"modul_shop.php?action=orders&amp;o_mod=7&amp;project=".$_SESSION['project']."\">"._SHOP_ORDERS_7."</a></li>\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop.php?action=add_man&amp;project=".$_SESSION['project']."\">"._SHOP_MAN."</a></li>\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop_sellers.php?action=&amp;project=".$_SESSION['project']."\">"._SHOP_SELLERS."</a></li>\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop_sellers.php?action=discount_cats&&amp;project=".$_SESSION['project']."\">"._SHOP_DISCOUNT_CATS."</a></li>\n";
									echo "	<li class=\"menu_shop_level_0\"><a href=\"modul_shop_setup.php?action=shop_setup&amp;project=".$_SESSION['project']."\">"._SHOP_SETUP."</a></li>\n";
									echo "</ul>\n";
									echo "<br /><br />\n";
								}
	echo "						<ul class=\"menu\">";
						if ($eden_cfg['modul_news'] == true && CheckPriv("groups_news_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_news.php?project=".$_SESSION['project']."\">"._NEWSS."</a></li>"; }
						if ($eden_cfg['modul_articles']== true && CheckPriv("groups_article_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_articles.php?project=".$_SESSION['project']."&act=article\">"._ARTICLES."</a></li>"; }
						if ($eden_cfg['modul_articles_public'] == true && CheckPriv("groups_article_public_add") == 1){ echo "<li class=\"menu_level_0\"><a href=\"modul_articles.php?project=". $_SESSION['project']."&act=articles_public\">"._ARTICLES_PUBLIC."</a></li>";}
						if ($eden_cfg['modul_tags'] == true && CheckPriv("groups_article_add") == 1){ echo "<li class=\"menu_level_0\"><a href=\"modul_tags.php?project=". $_SESSION['project']."\">"._TAGS."</a></li>";}
						if (CheckPriv("groups_cat_add") == 1){ echo "<li class=\"menu_level_0\"><a href=\"sys_category.php?project=".$_SESSION['project']."\">"._CATEGORY."</a></li>";}
						if ($eden_cfg['modul_it'] == true && CheckPriv("groups_it_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_it.php?project=".$_SESSION['project']."\">Modul IDEAL TRADE</a></li>"; }
						if ($eden_cfg['modul_auto'] == true && CheckPriv("groups_auto_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_auto.php?project=".$_SESSION['project']."\">"._AUTO."</a></li>"; }
						if ($eden_cfg['modul_compare'] == true && CheckPriv("groups_compare_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_compare.php?project=".$_SESSION['project']."\">"._COMPARE."</a></li>"; }
						if ($eden_cfg['modul_download'] == true && CheckPriv("groups_download_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_download.php?project=".$_SESSION['project']."\">"._DOWNLOAD."</a></li>"; }
						if ($eden_cfg['modul_clanwars'] == true && CheckPriv("groups_clanwars_add") == 1) {
							echo "<li class=\"menu_level_0\">"._CLAN."</li>";
							echo "<li class=\"menu_level_1\"><a href=\"modul_clan_clanwars.php?project=".$_SESSION['project']."\">"._CLAN_CLANWARS."</a></li>";
							echo "<li class=\"menu_level_1\"><a href=\"modul_clan_games.php?action=clan_game_add&mod=league&project=".$_SESSION['project']."\">"._LEAGUE_GAMES."</a></li>";
							echo "<li class=\"menu_level_1\"><a href=\"modul_clan_maps.php?&project=".$_SESSION['project']."\">"._CLAN_MAPS."</a></li>";
							if ($eden_cfg['modul_clan_awards'] == true && Checkpriv("groups_clan_awards_add") == 1) {echo "<li class=\"menu_level_1\"><a href=\"modul_clan_awards.php?project=".$_SESSION['project']."\">"._CLAN_AWARDS."</a></li>";}
						}
						if ($eden_cfg['modul_gameservers'] == true && CheckPriv("groups_gamesrv_add") == 1) { echo "<li class=\"menu_level_1\"><a href=\"modul_clan_gameservers.php?project=".$_SESSION['project']."\">"._GAMESRV."</a></li>"; }
						if ($eden_cfg['modul_league'] == true && CheckPriv("groups_league_add") == 1) { echo "<li class=\"menu_level_1\"><a href=\"modul_league.php?action=league_add&project=".$_SESSION['project']."\">"._LEAGUE."</a></li>"; }
						if ($eden_cfg['modul_league'] == true && Checkpriv("groups_league_del") == 1) {echo "<li class=\"menu_level_1\"><a href=\"modul_league.php?action=guid_add&project=".$_SESSION['project']."\">"._LEAGUE_GUIDS."</a></li>";}
						if ($eden_cfg['modul_cups'] == true && CheckPriv("groups_cups_add") == 1) { echo "<li class=\"menu_level_1\"><a href=\"modul_cups.php?project=".$_SESSION['project']."\">"._CUPS."</a></li>
							<li class=\"menu_level_1\"><a href=\"modul_cups.php?project=".$_SESSION['project']."\">"._CUPS_BRACKETS."</a></li>
							<li class=\"menu_level_1\"><a href=\"modul_cups_groups.php?project=".$_SESSION['project']."\">"._CUPS_GROUPS."</a></li>";
						}
						if ($eden_cfg['modul_clanwars'] == true && CheckPriv("groups_clanwars_add") == 1) {echo "<li class=\"menu_level_1\"><a href=\"modul_clan_setup.php?project=".$_SESSION['project']."\">"._CLAN_SETUP."</a></li>";}
						if ($eden_cfg['modul_poker'] == true && CheckPriv("groups_poker_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_poker.php?project=".$_SESSION['project']."\">"._POKER."</a></li>
							<li class=\"menu_level_1\"><a href=\"modul_poker.php?action=add_cardroom&project=".$_SESSION['project']."\">"._POKER_CARDROOMS."</a></li>
							<li class=\"menu_level_1\"><a href=\"modul_poker.php?action=add_variant&project=".$_SESSION['project']."\">"._POKER_VARIANTS."</a></li>";
						}
						if ($eden_cfg['modul_links'] == true && CheckPriv("groups_links_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_links.php?project=".$_SESSION['project']."\">"._LINKS."</a></li>"; }
						if ($eden_cfg['modul_adds'] == true && CheckPriv("groups_adds_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_ads.php?project=".$_SESSION['project']."\">"._ADDS_ADDS."</a></li>"; }
						if ($eden_cfg['modul_poll'] == true && CheckPriv("groups_wp_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_poll.php?project=".$_SESSION['project']."\">"._WEEKLYPOLL."</a></li>"; }
						if ($eden_cfg['modul_rss'] == true && CheckPriv("groups_rss_add") == 1 ) { echo "<li class=\"menu_level_0\"><a href=\"modul_rss.php?project=".$_SESSION['project']."\">"._RSS."</a></li>"; }
						if ($eden_cfg['modul_rss_itunes'] == true && CheckPriv("groups_rss_add") == 1 ) { echo "<li class=\"menu_level_0\"><a href=\"modul_rss_itunes.php?project=".$_SESSION['project']."\">"._RSS_ITUNES."</a></li>"; }
						if ($eden_cfg['modul_forum'] == true && CheckPriv("groups_forum_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_forum.php?project=".$_SESSION['project']."\">"._FORUM."</a></li>"; }
						if ($eden_cfg['modul_calendar'] == true && CheckPriv("groups_calendar_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_calendar.php?project=".$_SESSION['project']."\">"._CALENDAR."</a></li>"; }
						if ($eden_cfg['modul_guestbook'] == true && CheckPriv("groups_guestbook_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_guestbook.php?project=".$_SESSION['project']."\">"._GUEST_GUESTBOOK."</a></li>"; }
						if ($eden_cfg['modul_dictionary'] == true && CheckPriv("groups_dictionary_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_dictionary.php?project=".$_SESSION['project']."\">"._DICTIONARY."</a></li>"; }
                        if ($eden_cfg['modul_profile'] == true && CheckPriv("groups_profile_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_profile.php?project=".$_SESSION['project']."\">"._PROFILES."</a></li>"; }
						if ($eden_cfg['modul_smiles'] == true && CheckPriv("groups_smiles_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_smiles.php?project=".$_SESSION['project']."\">"._SMILES."</a></li>"; }
						if ($eden_cfg['modul_ban'] == true && CheckPriv("groups_ban_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_ban.php?project=".$_SESSION['project']."\">"._BAN_MODUL."</a></li>"; }
						if ($eden_cfg['modul_filter'] == true && CheckPriv("groups_filter_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_filter.php?project=".$_SESSION['project']."\">"._FILTER_MODUL."</a></li>"; }
						echo "<a href=\"sys_statistics.php?project=".$_SESSION['project']."\"><li class=\"menu_level_0\">"._STAT_STATISTICS."</a></li>";
						if ($eden_cfg['modul_todo']== true && CheckPriv("groups_todo_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_todo.php?project=".$_SESSION['project']."\">"._TODO."</a></li>"; }
						if ($eden_cfg['modul_streams']== true && CheckPriv("groups_stream_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_streams.php?project=".$_SESSION['project']."\">"._STREAMS."</a></li>"; }
						if ($eden_cfg['modul_video']== true && CheckPriv("groups_video_add") == 1) { echo "<li class=\"menu_level_0\"><a href=\"modul_videos.php?project=".$_SESSION['project']."\">"._VIDEOS."</a></li>"; }
	echo "			</ul>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			<table width=\"141\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"141\" height=\"36\" class=\"menu_nadpis\">"._ADMINISTRATION."</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"menu_text\" align=\"left\">\n";
	echo "						<ul class=\"menu\">";
						if (CheckPriv("groups_admin_add") == 1){ echo "<li class=\"menu_level_0\"><a href=\"sys_setup.php?project=".$_SESSION['project']."\">"._SETUP."</a></li>"; }
						if (CheckPriv("groups_admin_add") == 1){echo "<li class=\"menu_level_0\"><a href=\"sys_language.php?project=".$_SESSION['project']."\">"._LANGS."</a></li>"; }
						if (CheckPriv("groups_group_add") == 1){echo "<li class=\"menu_level_0\"><a href=\"sys_groups.php?project=".$_SESSION['project']."\">"._GROUPS."</a></li>"; }
						if (CheckPriv("groups_admin_add") == 1){echo "<li class=\"menu_level_0\"><a href=\"sys_admin.php?project=".$_SESSION['project']."\">"._ADMINS."</a></li>"; }
						if (CheckPriv("groups_reserved_add") == 1){echo "<li class=\"menu_level_0\"><a href=\"sys_reserved.php?project=".$_SESSION['project']."\">"._RES_WORDS."</a></li>"; }
						if (CheckPriv("groups_admin_add") == 1){echo "<li class=\"menu_level_0\"><a href=\"sys_optimize.php?project=".$_SESSION['project']."\">"._OPTIMIZEDATABASE."</a></li>"; }
	echo "						</ul>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			<table width=\"141\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td width=\"141\" height=\"36\"><img src=\"images/sys_dekor.gif\" width=\"141\" height=\"199\" border=\"0\" alt=\"\"></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		<td width=\"857\" valign=\"top\" bgcolor=\"#FFFFFF\">";
} else {
	header ("Location: index.php?action=msg&msg=badproject");
}