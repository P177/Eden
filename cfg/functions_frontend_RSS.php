<?php
/***********************************************************************************************************
*
*		FUNCTION - MAKE RSS
*
*		Vytvori RSS kanal
*
*		Staci dat na stranku odkaz jako:
*		<a href="http://www.esuba.eu/eden_rss.php?id=1&amp;project=esuba" target="_blank">RSS</a>
* encoding	= kodovani stranky s RSS  je vzdy UTF-8
*
* Povinne polozky:
*		id 			= id RSS kanalu
*		category	= kategorie novinek/vyrobku/podcastu, ktere se maji zobrazit
*
*		Nepovinne polozky:
*		rss_version	= verze RSS (1.0, 2.0, ATOM1.0) Vychozi je 2.0
*		mode		= article, shop, podcast, all
*		google		= prida veci specificke pro google
*
*		!!!!!!!!!!!!!! DULEZITE !!!!!!!!!!!
*		Pokud neco nepujde, zkontrolovat jestli je zalozeny KANAL
*
/**********************************************************************************************************/
function MakeRSS($rid, $category = 0, $version = "2.0", $mode = "article", $google = 0){
	
	global $db_admin,$db_articles,$db_rss,$db_rss_lang,$db_setup,$db_podcast,$db_category,$db_podcast_channel,$db_shop_clothes_design;
	global $eden_cfg;
	global $url_shop_clothes_design,$url_articles;
	
	$allowtags = "";
	$res_setup = mysql_query("SELECT setup_basic_timezone, setup_basic_date FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	$res_rss = mysql_query("SELECT 
	r.rss_allow, r.rss_title, r.rss_link, r.rss_managingeditor, r.rss_webmaster, r.rss_copyright, r.rss_description, r.rss_number, r.rss_ttl, r.rss_category, 
	r.rss_category_domain, r.rss_image, r.rss_image_title, r.rss_image_link, rl.rss_lang_shortname 
	FROM $db_rss AS r, 
	$db_rss_lang AS rl 
	WHERE r.rss_id=".(integer)$rid." AND rl.rss_lang_id=r.rss_lang") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_rss = mysql_fetch_array($res_rss);
	$feed_title = strip_tags($ar_rss['rss_title'],$allowtags);
	$feed_description = htmlspecialchars($ar_rss['rss_description'],ENT_QUOTES);
	if ($version == "2.0"){
		$chanel_lastbuilddate = date("D, d M Y H:i:s");
		$timezone = $ar_setup['setup_basic_timezone'];
	} elseif ($version == "1.0"){
		$chanel_lastbuilddate = date("Y-m-d\TH:i:s");
		$timezone = $timezone = $ar_setup['setup_basic_timezone'][0].$ar_setup['setup_basic_timezone'][1].$ar_setup['setup_basic_timezone'][2].":".$ar_setup['setup_basic_timezone'][3].$ar_setup['setup_basic_timezone'][4];
	} else {
		// ATOM 1.0 
		$chanel_lastbuilddate = date("Y-m-d\TH:i:s");
		$id_web_date = $ar_setup['setup_basic_date'][0].$ar_setup['setup_basic_date'][1].$ar_setup['setup_basic_date'][2].$ar_setup['setup_basic_date'][3];
		if ($ar_setup['setup_basic_timezone'] == "GMT"){
			$timezone = "Z";
		} else {
			$timezone = $ar_setup['setup_basic_timezone'][0].$ar_setup['setup_basic_timezone'][1].$ar_setup['setup_basic_timezone'][2].":".$ar_setup['setup_basic_timezone'][3].$ar_setup['setup_basic_timezone'][4];
		}
		$feed_id = str_replace("/", "", $eden_cfg['misc_web']);
	}
	if ($ar_rss['rss_allow'] == 1) {
		// ITEM
		$ret_item = "";
		$ret_channel_items = "";
		$ret = "";
		$ret_bottom = "";
		if ($mode  == "all"){
			/***************************************
			*	ALL
			***************************************/
			$showtime = formatTime(time(),"YmdHis");
			$res = mysql_query("SELECT article_id, article_headline, article_perex, article_date_on, article_author_id, article_date, article_date_edit FROM $db_articles WHERE article_parent_id=0 AND article_public=0 AND article_publish=1 AND $showtime BETWEEN article_date_on AND article_date_off ORDER BY article_date_on DESC LIMIT ".(integer)$ar_rss['rss_number']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} elseif ($mode  == "shop"){
			/***************************************
			*	SHOP
			***************************************/
			$showtime = formatTime(time(),"YmdHis");
			$res = mysql_query("SELECT shop_clothes_design_id, shop_clothes_design_author_id, shop_clothes_design_title, shop_clothes_design_description_short, shop_clothes_design_date_available, shop_clothes_design_date_edit, shop_clothes_design_subcategory1, shop_clothes_design_selling_price, shop_clothes_design_img_1, shop_clothes_design_img_5, shop_clothes_design_img_6, shop_clothes_design_img_7, shop_clothes_design_img_8 FROM $db_shop_clothes_design WHERE shop_clothes_design_show=1 AND $showtime > 'shop_clothes_design_date_available' ORDER BY shop_clothes_design_date_available DESC LIMIT ".(integer)$ar_rss['rss_number']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} elseif ($mode  == "podcast"){
			/***************************************
			*	PODCAST
			***************************************/
			$showtime = formatTime(time(),"YmdHis");
			$res = mysql_query("SELECT podcast_id, podcast_title, podcast_subtitle, podcast_pub_date, podcast_author_id, podcast_channel_id, podcast_enclosure_url, podcast_enclosure_lenght, podcast_enclosure_type FROM $db_podcast WHERE podcast_block=0 AND $showtime > 'podcast_pub_date' ORDER BY podcast_pub_date DESC LIMIT ".(integer)$ar_rss['rss_number']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		} else {
			/***************************************
			*	ARTICLE
			***************************************/
			$showtime = formatTime(time(),"YmdHis");
			$pieces = explode ("-", $category);
			$num1 = count($pieces);
			
			$i=0;
			while($num1 > $i){
				$act_category_pieces = $pieces[$i];
				if ($i>0){$divider = ",";} else {$divider = "";}
				$categories .= $divider.(integer)$act_category_pieces;
				$i++;
			}
			$res = mysql_query("SELECT article_id, article_headline, article_author_id, article_category_id, article_img_1, article_date, article_date_edit, article_perex, article_date_on FROM $db_articles 
			WHERE article_parent_id=0 AND article_category_id IN ($categories) AND article_public=0 AND article_publish=1 $tn AND $showtime BETWEEN article_date_on AND article_date_off 
			ORDER BY article_date_on DESC LIMIT ".(integer)$ar_rss['rss_number']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		}
		while ($ar = mysql_fetch_array($res)){
			if ($mode  == "all"){
				$rss_link = $eden_cfg['url']."index.php?action=clanek&amp;id=".$rss_item_id;
			} elseif ($mode  == "shop"){
				//index.php?action=spec_t&gen=&did=
				$res_cat = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".(integer)$ar['shop_clothes_design_subcategory1']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_cat = mysql_fetch_array($res_cat);
				$rss_title = stripslashes($ar['shop_clothes_design_title']);
				$rss_description = stripslashes($ar['shop_clothes_design_description_short']); $rss_description .= '<br clear="all"><img src="'.$url_shop_clothes_design.$ar['shop_clothes_design_img_1'].'" alt="'.$rss_title.'" title="'.$rss_title.'" />';
				$rss_pubdate = FormatDatetime($ar['shop_clothes_design_date_available'],"D, d M Y H:i:s");
				$rss_item_id = $ar['shop_clothes_design_id'];
				$rss_category_item = stripslashes($ar_cat['category_name']);
				$rss_author_id = $ar['shop_clothes_design_author_id'];
				$rss_google_price = TepRound($ar['shop_clothes_design_selling_price'],2);
				if ($version == "atom1.0"){
					$updated = FormatDatetime($ar['shop_clothes_design_date_edit'],"Y-m-d\TH:i:s").$timezone;
					$published = FormatDatetime($ar['shop_clothes_design_date_available'],"Y-m-d\TH:i:s").$timezone;
					$rss_pubdate = FormatDatetime($ar['shop_clothes_design_date_available'],"Y-m-d\TH:i:s");
				}
				$rss_link = $eden_cfg['url']."index.php?action=spec_t&amp;did=".$rss_item_id;
			} elseif ($mode  == "podcast"){
				$res_cat = mysql_query("SELECT podcast_channel_title FROM $db_podcast_channel WHERE podcast_channel_id=".(integer)$ar['podcast_channel_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_cat = mysql_fetch_array($res_cat);
				$rss_title = stripslashes($ar['podcast_title']);
				$rss_description = stripslashes($ar['podcast_subtitle']);
				$rss_pubdate = FormatDatetime($ar['podcast_pub_date'],"D, d M Y H:i:s");
				$rss_item_id = $ar['podcast_id'];
				$rss_category_item = stripslashes($ar_cat['podcast_channel_title']);
				$rss_author_id = $ar['podcast_author_id'];
				if ($version == "atom1.0"){
					$updated = $ar['article_date_edit'][0].$ar['article_date_edit'][1].$ar['article_date_edit'][2].$ar['article_date'][3]."-".$ar['article_date'][4].$ar['article_date'][5]."-".$ar['article_date'][6].$ar['article_date'][7]."T".$ar['article_date'][8].$ar['article_date'][9].":".$ar['article_date'][10].$ar['article_date'][11].":".$ar['article_date'][12].$ar['article_date'][13].$timezone;
					$published = FormatDatetime($ar['podcast_pub_date'],"Y-m-d\TH:i:s").$timezone;
					$rss_pubdate = FormatDatetime($ar['podcast_pub_date'],"Y-m-d\TH:i:s");
				}
				$rss_link = $eden_cfg['url']."index.php?action=podcasts&amp;id=".$rss_item_id;
			} else {
				$res_cat = mysql_query("SELECT category_name FROM $db_category WHERE category_id=".(integer)$ar['article_category_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$ar_cat = mysql_fetch_array($res_cat);
				$rss_title = stripslashes($ar['article_headline']);
				$rss_description = stripslashes($ar['article_perex']);
				if ($version == "1.0"){
					$rss_pubdate = FormatTimestamp($ar['article_date_on'],"Y-m-d\TH:i:s");
				} else {
					$rss_pubdate = FormatTimestamp($ar['article_date_on'],"D, d M Y H:i:s");
				}
				$rss_item_id = $ar['article_id'];
				$rss_author_id = $ar['article_author_id'];
				$rss_category_item = stripslashes($ar_cat['category_name']);
				if ($version == "atom1.0"){
					$updated = $ar['article_date_edit'][0].$ar['article_date_edit'][1].$ar['article_date_edit'][2].$ar['article_date_edit'][3]."-".$ar['article_date_edit'][4].$ar['article_date_edit'][5]."-".$ar['article_date_edit'][6].$ar['article_date_edit'][7]."T".$ar['article_date_edit'][8].$ar['article_date_edit'][9].":".$ar['article_date_edit'][10].$ar['article_date_edit'][11].":".$ar['article_date_edit'][12].$ar['article_date_edit'][13].$timezone;
					$published = $ar['article_date_on'][0].$ar['article_date_on'][1].$ar['article_date_on'][2].$ar['article_date_on'][3]."-".$ar['article_date_on'][4].$ar['article_date_on'][5]."-".$ar['article_date_on'][6].$ar['article_date_on'][7]."T".$ar['article_date_on'][8].$ar['article_date_on'][9].":".$ar['article_date_on'][10].$ar['article_date_on'][11].":".$ar['article_date_on'][12].$ar['article_date_on'][13].$timezone;
					$rss_pubdate = FormatTimestamp($ar['article_date_on'],"Y-m-d\TH:i:s");
				}
				$rss_link = $eden_cfg['url']."index.php?action=clanek&amp;id=".$rss_item_id;
			}
			
			$res_adm = mysql_query("SELECT admin_id, admin_email FROM $db_admin WHERE admin_id=".$rss_author_id) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar_adm = mysql_fetch_array($res_adm);
			$rss_title = TreatText($rss_title);
			$rss_title = sanitizeRSS($rss_title);
			if ($rss_description == ""){$rss_description = $rss_title;}
			$rss_description = TreatText($rss_description);
			$rss_description = strip_tags($rss_description,"");
			$rss_description = ShortText($rss_description,450);
			$rss_description = sanitizeRSS($rss_description);
			/***************************************
			*	ATOM ENTRY
			***************************************/
			if ($version == "atom1.0"){
				$ret_item .= "<entry>\n";
				$ret_item .= "	<title type=\"html\" >".$rss_title."</title>\n";
				$ret_item .= "	<link href=\"".$rss_link."\" rel=\"alternate\" type=\"text/html\"/>\n";
				if ($mode == "podcast"){$ret_item .= "<link rel=\"enclosure\" type=\"".$ar['podcast_enclosure_type']."\" length=\"".$ar['podcast_enclosure_lenght']."\" href=\"".stripslashes($ar['podcast_enclosure_url'])."\" />\n";}
				$ret_item .= "	<id>tag:".$feed_id.",".$id_web_date.":".$rid.".".$rss_item_id."</id>\n";
				$ret_item .= "	<rights>".stripslashes($ar_rss['rss_copyright'])."</rights>\n";
				$ret_item .=	"	<category term=\"".$rss_category_item."\" label=\"".$rss_category_item."\" />";
				$ret_item .= "	<updated>".$updated."</updated>\n";
				$ret_item .= "	<published>".$published."</published>\n";
				$ret_item .= "	<author>\n";
				$ret_item .= "		<name>".GetNickName($ar_adm['admin_id'],3)."</name>\n";
				$ret_item .= "	</author>\n";
				$ret_item .= "	<content>".$rss_description."</content>\n";
				$ret_item .= "</entry>\n";
			}
			/***************************************
			*	RSS ITEM
			***************************************/
			if ($version == "1.0"){
				$ret_item .= "\n<item rdf:about=\"".$rss_link."\">\n";
			}
			if ($version == "2.0"){
				$ret_item .= "\n<item>\n";
			}
			if ($version == "1.0" || $version == "2.0"){
				$ret_item .=	"	<title>".$rss_title."</title>\n";
				$ret_item .= "	<link>".$rss_link."</link>\n";
				$ret_item .= "	<description>&lt;img src=\"".$url_articles.$ar['article_img_1']."\"/&gt; ".$rss_description."</description>\n";
			}
			if ($version == "1.0"){
				$ret_item .= "	<dc:creator>".GetNickName($ar_adm['admin_id'],3)."</dc:creator>\n";
				$ret_item .= "	<dc:date>".$rss_pubdate.$timezone."</dc:date>\n";
				// Chanel items
				$ret_channel_items .= "<rdf:li resource=\"".$rss_link."\" />\n";
			}
			if ($version == "2.0"){
				$ret_item .= "	<pubDate>".$rss_pubdate." ".$timezone."</pubDate>\n";
				if ($mode == "podcast"){$ret_item .= "	<enclosure url=\"".stripslashes($ar['podcast_enclosure_url'])."\" length=\"".$ar['podcast_enclosure_lenght']."\" type=\"".$ar['podcast_enclosure_type']."\" />\n";}
				$ret_item .= "	<author>".$ar_adm['admin_email']." (".GetNickName($ar_adm['admin_id'],3).")</author>\n";
				$ret_item .= "	<guid>".$rss_link."</guid>\n";
				$ret_item .= "	<source url=\"".$eden_cfg['url']."\">".stripslashes($ar_rss['rss_title'])."</source>\n";
				$ret_item .= "	<category>".$rss_category_item."</category>\n";
			}
			if ($google == 1){
				$ret_item .=	"	<g:price>".$rss_google_price."</g:price>\n";
				$ret_item .=	"	<g:price_type>starting</g:price_type>\n";
				$ret_item .=	"	<g:brand>Cubicoola</g:brand>\n";
				$ret_item .=	"	<g:condition>new</g:condition>\n";
				if ($ar['shop_clothes_design_img_5'] != ""){$ret_item .=	"	<g:image_link>".$url_shop_clothes_design.$ar['shop_clothes_design_img_5']."</g:image_link>\n";}
				if ($ar['shop_clothes_design_img_6'] != ""){$ret_item .=	"	<g:image_link>".$url_shop_clothes_design.$ar['shop_clothes_design_img_6']."</g:image_link>\n";}
				if ($ar['shop_clothes_design_img_7'] != ""){$ret_item .=	"	<g:image_link>".$url_shop_clothes_design.$ar['shop_clothes_design_img_7']."</g:image_link>\n";}
				if ($ar['shop_clothes_design_img_8'] != ""){$ret_item .=	"	<g:image_link>".$url_shop_clothes_design.$ar['shop_clothes_design_img_8']."</g:image_link>\n";}
				$ret_item .=	"	<g:product_type>tshirt</g:product_type>\n";
				$ret_item .=	"	<g:size>any</g:size>\n";
			}
			if ($version == "1.0" || $version == "2.0"){
				$ret_item .= "</item>\n";
			}
		}
		
		// FEED
		$ret = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
		if ($version == "2.0"){
			$ret .= "<rss version=\"".$version."\""; if ($version == "2.0"){$ret .= " xmlns:atom=\"http://www.w3.org/2005/Atom\"";}  if ($google == 1){$ret .= " xmlns:g=\"http://base.google.com/ns/1.0\"";} $ret .= ">\n";
		}
		/***************************************
		*	ATOM CHANNEL
		***************************************/
		if ($version == "atom1.0"){
			$ret .= "<feed xmlns=\"http://www.w3.org/2005/Atom\""; if ($google == 1){ $ret .= " xmlns:g=\"http://base.google.com/ns/1.0\"";} $ret .= ">\n";
			$ret .= "<title type=\"text\">".$feed_title."</title>\n";
			$ret .= "<subtitle type=\"html\">".$feed_description."</subtitle>\n";
			$ret .= "<updated>".$chanel_lastbuilddate.$timezone."</updated>\n";
			$ret .= "<id>tag:".$feed_id.",".$id_web_date.":".$rid."</id>\n";
			$ret .= "<rights>".$ar_rss['rss_copyright']."</rights>\n";
			$ret .= "<link href=\"".$eden_cfg['url_edencms']."eden_rss.php?id=".(integer)$rid."&#x26;project=".$_GET['project']."&#x26;rss_version=2.0&#x26;cat=".$category."\" rel=\"alternate\" type=\"application/rss+xml\" hreflang=\"".$ar_rss['rss_lang_shortname']."\"/>\n";
			$ret .= "<link href=\"".$eden_cfg['url_edencms']."eden_rss.php?id=".(integer)$rid."&#x26;project=".$_GET['project']."&#x26;rss_version=".$version."&#x26;cat=".$category."\" rel=\"self\" type=\"application/atom+xml\"/>\n";
			$ret .=	"<generator uri=\"".$eden_cfg['url']."\" version=\"1.0\">EDEN CMS ATOM Generator</generator>\n";
			$ret .= "<author>\n";
			$ret .= "	<name>".GetNickName($ar_adm['admin_id'],3)."</name>\n";
			$ret .= "	<uri>".$eden_cfg['url']."</uri>\n";
			$ret .= "	<email>".$ar_adm['admin_email']."</email>\n";
			$ret .= "</author>\n";
			$ret .=	"<logo>".$eden_cfg['url']."edencms/rss/".$ar_rss['rss_image']."</logo>\n";
		}
		/***************************************
		*	RSS	CHANNEL
		***************************************/
		if ($version == "1.0"){
			$ret .= "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\" xmlns:admin=\"http://webns.net/mvcb/\" xmlns:cc=\"http://web.resource.org/cc/\" xmlns=\"http://purl.org/rss/1.0/\" xmlns:g=\"http://base.google.com/ns/1.0\">\n";
		}
		if ($version == "1.0" || $version == "2.0"){
			$ret .= "<channel"; if ($version == "1.0"){ $ret .= " rdf:about=\"".$eden_cfg['url_edencms']."eden_rss.php?id=".(integer)$rid."&amp;project=".$_GET['project']."&amp;rss_version=1.0&amp;cat=".$category."\"";} $ret .= ">\n";
			$ret .= "<title>".$feed_title."</title>\n";
			$ret .= "<link>".$ar_rss['rss_link']."</link>\n";
			$ret .= "<"; if ($version == "1.0"){$ret .= "dc:";} $ret .= "language>".$ar_rss['rss_lang_shortname']."</"; if ($version == "1.0"){$ret .= "dc:";} $ret .= "language>\n";
			$ret .= "<description>".$feed_description."</description>\n";
		}
		if ($version == "2.0"){
			$ret .= "<pubDate>".date("D, d M Y")." 00:00:01 ".$timezone."</pubDate>\n";
			$ret .= "<lastBuildDate>".$chanel_lastbuilddate." ".$timezone."</lastBuildDate>\n";
			$ret .= "<managingEditor>".$ar_rss['rss_managingeditor']."</managingEditor>\n";
			$ret .= "<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
			$ret .= "	<atom:link href=\"".sanitizeRSS($eden_cfg['url_edencms']."eden_rss.php?id=".$_GET['id']."&project=".$_GET['project']."&rss_version=".$_GET['rss_version']."&cat=".$_GET['cat'])."\" rel=\"self\" type=\"application/rss+xml\" />\n";
			$ret .= "<category"; if ($ar_rss['rss_category_domain'] != ""){$ret .= ' domain="'.$ar_rss['rss_category_domain'].'"';} $ret .= ">".$ar_rss['rss_category']."</category>\n";
			$ret .= "<generator>EDEN CMS RSS Generator</generator>\n";
			$ret .= "<ttl>".$ar_rss['rss_ttl']."</ttl>\n";
		}
		if ($version == "1.0"){
			$ret .= "<dc:date>".$chanel_lastbuilddate.$timezone."</dc:date>\n";
			if ($ar_rss['rss_image'] != ""){$ret .= "<image rdf:resource=\"".$eden_cfg['url']."edencms/rss/".$ar_rss['rss_image']."\" />\n";}
			$ret .= "<items>\n";
			$ret .= "<rdf:Seq>\n";
			$ret .= $ret_channel_items;
			$ret .= "</rdf:Seq>\n";
			$ret .= "</items>\n";
			$ret .= "</channel>\n";
			if ($ar_rss['rss_image'] != ""){
				$ret .= "<image rdf:about=\"".$eden_cfg['url']."edencms/rss/".$ar_rss['rss_image']."\">\n";
				if ($ar_rss['rss_image_title'] || ""){$ret .= "	<title>".$ar_rss['rss_image_title']."</title>\n";}
				if ($ar_rss['rss_image_link'] || ""){$ret .= "	<link>".$ar_rss['rss_image_link']."</link>\n";}
				$ret .= "	<url>".$eden_cfg['url']."edencms/rss/".$ar_rss['rss_image']."</url>\n";
				$ret .= "</image>\n";
			}
		}
		
		
		if ($version == "2.0"){
			$ret_bottom .= "</channel>\n";
			$ret_bottom .= "</rss>\n";
		}
		if ($version == "1.0"){
			$ret_bottom .= "</rdf:RDF>\n";
		}
		if ($version == "atom1.0"){
			$ret_bottom .= "</feed>\n";
		}
		return $ret.$ret_item.$ret_bottom;
	}
}
/***********************************************************************************************************
*
*		FUNCTION - MAKE RSS	LINK
*
*		Vytvori RSS odkaz do <head></head> stranky
*
*		Staci dat na stranku do hlavicky pod metaznaky toto:
*		MakeRSSLink(id, category, version, encoding, mode);
*		Povinne polozky:
*		$rid			=	id RSS kanalu
*		&category		=	kategorie, ktere chceme zobrazit
*
*		Nepovinne polozky:
*		$version		=	verze RSS (1.0, 2.0, ATOM) Vychozi je 2.0
*		&mode			=	article, podcast, shop, all
*
/**********************************************************************************************************/
function MakeRSSLink($rid, $category, $version = "1.0", $mode = "article"){
	
	global $db_rss;
	global $eden_cfg;
	global $project;
	
	$res = mysql_query("SELECT rss_title FROM $db_rss WHERE rss_id=".(integer)$rid) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar = mysql_fetch_array($res);
	if ($version == "1.0"){
		$link_version = "RSS 1.0 RDF";
		$type = "application/rdf+xml";
	} elseif ($version == "atom1.0"){
		$link_version = "ATOM 1.0";
		$type = "application/atom+xml";
	} else {
		$link_version = "RSS 2.0";
		$type = "application/rss+xml";
	}
	
	if ($mode == "shop"){
		$mode_title = "products";
	} elseif ($mode == "podcast"){
		$mode_title = "podcast";
	} else {
		$mode_title = "articles";
	}
	$ret = "<link rel=\"alternate\" type=\"".$type."\" title=\"".$ar['rss_title']." (".$mode_title.", ".$link_version.")\" href=\"".$eden_cfg['url_edencms']."eden_rss.php?id=".$rid."&project=".$project."&rss_version=".$version."&cat=".$category."\">\n";
	return $ret;
}
/***********************************************************************************************************
*
*		FUNCTION - MAKE RSS
*
*		Vytvori RSS kanal
*
*		Staci dat na stranku odkaz jako:
*		<a href="http://www.esuba.eu/edencms/eden_rss.php?id=1&amp;project=esuba&amp;rss_version=itunes" target="_blank">RSS</a>
*		Povinne polozky:
*		rchid 		= id RSS kanalu
*
*
/**********************************************************************************************************/
function MakeRSSiTunes($rchid){
	
	global $db_podcast_channel,$db_podcast,$db_rss_lang,$db_setup;
	global $eden_cfg;
	global $url_rss_itunes;
	
	mysql_query("SET NAMES utf8") or die('Could not set names');
	
	$res_setup = mysql_query("SELECT setup_basic_timezone, setup_basic_date FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);
	
	$showtime = formatTime(time(),"Y-m-d H:i:s");
	$res_ch = mysql_query("SELECT * FROM $db_podcast_channel WHERE podcast_channel_id=".(integer)$rchid." AND podcast_channel_block=0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_ch = mysql_fetch_array($res_ch);
	$res_lang = mysql_query("SELECT rss_lang_shortname FROM $db_rss_lang WHERE rss_lang_id=".(integer)$ar_ch['podcast_channel_lang']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_lang = mysql_fetch_array($res_lang);
	$chanel_lastbuilddate = date("D, d M Y H:i:s");
	$itunes_category = explode('||',$ar_ch['podcast_channel_category']);
	if ($ar_ch['podcast_channel_explicit'] == 0){$ch_explicit = "no";} elseif ($ar_ch['podcast_channel_explicit'] == 1){$ch_explicit = "clean";}else{$ch_explicit = "yes";}
	$ret = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
	$ret .= "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:itunes=\"http://www.itunes.com/dtds/podcast-1.0.dtd\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
	$ret .= "<channel>\n";
	$ret .= "	<title>".$ar_ch['podcast_channel_title']."</title>\n";
	$ret .= "	<link>".$ar_ch['podcast_channel_link']."</link>\n";
	$ret .= "	<language>".$ar_lang['rss_lang_shortname']."</language>\n";
	$ret .= "	<copyright>".$ar_ch['podcast_channel_copyright']."</copyright>\n";
	$ret .= "	<itunes:subtitle>".$ar_ch['podcast_channel_subtitle']."</itunes:subtitle>\n";
	$ret .= "	<itunes:author>".$ar_ch['podcast_channel_author']."</itunes:author>\n";
	$ret .= "	<itunes:summary>".$ar_ch['podcast_channel_summary']."</itunes:summary>\n";
	$ret .= "	<description>".$ar_ch['podcast_channel_summary']."</description>\n";
	$ret .= "	<itunes:explicit>".$ch_explicit."</itunes:explicit>\n";
	$ret .= "	<itunes:owner>\n";
	$ret .= "		<itunes:name>".$ar_ch['podcast_channel_owner_name']."</itunes:name>\n";
	$ret .= "		<itunes:email>".$ar_ch['podcast_channel_owner_email']."</itunes:email>\n";
	$ret .= "	</itunes:owner>\n";
	$ret .= "	<itunes:image href=\"".$url_rss_itunes.$ar_ch['podcast_channel_image']."\" />\n";
	$ret .= "	<atom:link href=\"".sanitizeRSS($eden_cfg['url_edencms']."eden_rss.php?id=".$_GET['id']."&project=".$_GET['project']."&rss_version=".$_GET['rss_version'])."\" rel=\"self\" type=\"application/rss+xml\" />\n";
	if ($itunes_category[1]){
		$ret .= "	<itunes:category text=\"".$itunes_category[0]."\">\n";
		$ret .= "		<itunes:category text=\"".$itunes_category[1]."\" />\n";
		$ret .= "	</itunes:category>\n";
	}else{
		$ret .= "	<itunes:category text=\"".$itunes_category[0]."\" />\n";
	}
	//AND ($showtime > podcast_date)
	$res_i = mysql_query("SELECT * FROM $db_podcast WHERE podcast_block=0 ORDER BY podcast_pub_date DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar_i = mysql_fetch_array($res_i)){
		$pub_date = FormatDatetime($ar_i['podcast_pub_date'],"D, d M Y H:i:s");
		if ($ar_i['podcast_explicit'] == 0){$i_explicit = "no";} elseif ($ar_i['podcast_explicit'] == 1){$i_explicit = "clean";}else{$i_explicit = "yes";}
		if ($ar_i['podcast_guid'] == ""){$guid = $ar_i['podcast_enclosure_url'];} else {$guid = $ar_i['podcast_guid'];}
		$ret .= "	<item>\n";
		$ret .= "		<title>".$ar_i['podcast_title']."</title>\n";
		$ret .= "		<link>".$ar_ch['podcast_channel_link']."</link>\n";
		$ret .= "		<itunes:author>".$ar_i['podcast_author']."</itunes:author>\n";
		$ret .= "		<itunes:subtitle>".$ar_i['podcast_subtitle']."</itunes:subtitle>\n";
		$ret .= "		<itunes:summary>".$ar_i['podcast_summary']."</itunes:summary>\n";
		$ret .= "		<description>".$ar_i['podcast_summary']."</description>\n";
		$ret .= "		<enclosure url=\"".$ar_i['podcast_enclosure_url']."\" length=\"".$ar_i['podcast_enclosure_lenght']."\" type=\"".$ar_i['podcast_enclosure_type']."\" />\n";
		$ret .= "		<guid>".$guid."</guid>\n";
		$ret .= "		<pubDate>".$pub_date." ".$ar_setup['setup_basic_timezone']."</pubDate>\n";
		$ret .= "		<itunes:duration>".$ar_i['podcast_duration']."</itunes:duration>\n";
		$ret .= "		<itunes:keywords>".$ar_i['podcast_keywords']."</itunes:keywords>\n";
		$ret .= "		<itunes:explicit>".$i_explicit."</itunes:explicit>\n";
		$ret .= "	</item>\n";
	}
	$ret .= "</channel>\n";
	$ret .= "</rss>\n";
	return($ret);
}
/*
 * sanitizeRSS
 * @string
 * return string
*/
 
function sanitizeRSS($item){
	
	$item = str_replace("&acute;", "'", $item);
	$item = str_replace("&nbsp;", " ", $item);
	$item = str_replace("&", "&#x26;", $item);
	$item = str_replace("<", "&#x3C;", $item);
	$item = str_replace(">", "&#x3E;", $item);
	$item = str_replace("'", "&#039;", $item);
	return $item;
}