<?php
/***********************************************************************************************************
*
*		ZOBRAZENI ITUNES KANALU
*
***********************************************************************************************************/
function ShowMain(){

	global $db_podcast_channel,$db_podcast;

	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _RSS_ITUNES_CHANNELS;?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0">
				<a href="modul_rss_itunes.php?action=add_rss_ch&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_ADD;?></a>
			</td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="65"><span class="nadpis-boxy"><?php echo _CMN_OPTIONS;?></span></td>
			<td width="30" align="center"><span class="nadpis-boxy">ID</span></td>
			<td width="30" align="left"><span class="nadpis-boxy"><?php echo _RSS_ITUNES_CHANNEL_PUBLISH;?></span></td>
			<td width="30" align="left"><span class="nadpis-boxy"><?php echo _RSS_ITUNES_CH_ITEMS;?></span></td>
			<td align="left"><span class="nadpis-boxy"><?php echo _RSS_ITUNES_CHANNEL_TITLE;?></span></td>
		</tr><?php
			$res_rss = mysql_query("SELECT podcast_channel_id, podcast_channel_title, podcast_channel_block FROM $db_podcast_channel ORDER BY podcast_channel_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$i=1;
			while ($ar_rss = mysql_fetch_array($res_rss)){
				$res_rss_items = mysql_query("SELECT COUNT(*) FROM $db_podcast WHERE podcast_channel_id=".(float)$ar_rss['podcast_channel_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num_rss_items = mysql_fetch_array($res_rss_items);
				if ($i % 2 == 0){ $cat_class = "cat_level1_even";} else { $cat_class = "cat_level1_odd";}
				echo "<tr class=\"".$cat_class."\" onmouseover=\"this.className='cat_over'\" onmouseout=\"this.className='".$cat_class."'\">\n";?>
					<td width="65">
						<a href="modul_rss_itunes.php?action=edit_rss_ch&amp;id=<?php echo $ar_rss['podcast_channel_id'];?>&amp;project=<?php echo $_SESSION['project'];?>"><img src="images/sys_edit.gif" height="18" width="18" border="0" alt="<?php echo _CMN_EDIT;?>"></a>
						<a href="modul_rss_itunes.php?action=del_rss_ch&amp;id=<?php echo $ar_rss['podcast_channel_id'];?>&amp;project=<?php echo $_SESSION['project'];?>"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="<?php echo _CMN_DEL;?>"></a>
						<a href="modul_rss_itunes.php?action=add_rss_i&chid=<?php echo $ar_rss['podcast_channel_id'];?>&amp;project=<?php echo $_SESSION['project'];?>"><img src="images/sys_dtopic.gif" height="18" width="18" border="0" alt="<?php echo _PODCAST_ADD;?>"></a></td>
					</td>
					<td width="30" align="right"><?php echo $ar_rss['podcast_channel_id'];?></td>
					<td width="30" align="center"><img src="images/sys_<?php if ($ar_rss['podcast_channel_block'] == 1){echo "no";} else {echo "yes";}?>.gif" alt="" width="18" height="18" border="0"></td>
					<td width="30" align="right"><?php echo $num_rss_items[0];?></td>
					<td><?php echo '<a href="modul_rss_itunes.php?action=showitems&chid='.$ar_rss['podcast_channel_id'],'&amp;project='.$_SESSION['project'].'" title="'._PODCASTS.'">'.$ar_rss['podcast_channel_title'].'</a>';?></td>
				</tr><?php
				$i++;
 			}?>
	</table><?php
}
/***********************************************************************************************************
*
*		ZOBRAZENI ITUNES KANALU
*
***********************************************************************************************************/
function ShowItems(){

	global $db_podcast_channel,$db_podcast;

	// Zabezpeceni, ze kdyz nekdo opusti novinky bez ulozeni, tak se odstrani jeho jmeno z kolonky uzivatele, ktery ma zaznam otevren
	KillUse($_SESSION['loginid']);

	$res_rss_channel = mysql_query("SELECT podcast_channel_id, podcast_channel_title FROM $db_podcast_channel WHERE podcast_channel_id=".(float)$_GET['chid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_rss_channel = mysql_fetch_array($res_rss_channel);?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _PODCASTS.' - '._RSS_ITUNES_CHANNEL.' ID:'.$ar_rss_channel['podcast_channel_id'].' - '.$ar_rss_channel['podcast_channel_title'];?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0">
				<a href="modul_rss_itunes.php?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_MAIN;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="modul_rss_itunes.php?action=add_rss_i&chid=<?php echo $_GET['chid'];?>&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _PODCAST_ADD;?></a>
			</td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="65"><span class="nadpis-boxy"><?php echo _CMN_OPTIONS;?></span></td>
			<td width="30" align="center"><span class="nadpis-boxy">ID</span></td>
			<td width="30" align="left"><span class="nadpis-boxy"><?php echo _PODCAST_PUBLISH;?></span></td>
			<td align="left"><span class="nadpis-boxy"><?php echo _PODCAST_TITLE;?></span></td>
			<td align="left" width="150"><span class="nadpis-boxy"><?php echo _PODCAST_DATE;?></span></td>
			<td align="left" width="80"><span class="nadpis-boxy"><?php echo _PODCAST_DURATION;?></span></td>
		</tr><?php
			$res_rss_item = mysql_query("SELECT podcast_id, podcast_title, podcast_block, podcast_pub_date, podcast_duration FROM $db_podcast WHERE podcast_channel_id=".(float)$_GET['chid']." ORDER BY podcast_id DESC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			while ($ar_rss_item = mysql_fetch_array($res_rss_item)){
				$Y = FormatDatetime($ar_rss_item['podcast_pub_date'],"Y");
				$M = FormatDatetime($ar_rss_item['podcast_pub_date'],"n");
				$D = FormatDatetime($ar_rss_item['podcast_pub_date'],"j");
				$h = FormatDatetime($ar_rss_item['podcast_pub_date'],"G");
				$m = FormatDatetime($ar_rss_item['podcast_pub_date'],"i");
				$s = FormatDatetime($ar_rss_item['podcast_pub_date'],"s");?>
				<tr	 onmouseover="this.style.backgroundColor='FFDEDF'" onmouseout="this.style.backgroundColor='FFFFFF'">
					<td width="65">
						<a href="modul_rss_itunes.php?action=edit_rss_i&amp;id=<?php echo $ar_rss_item['podcast_id'];?>&chid=<?php echo $_GET['chid'];?>&amp;project=<?php echo $_SESSION['project'];?>"><img src="images/sys_edit.gif" height="18" width="18" border="0" alt="<?php echo _CMN_EDIT;?>"></a>
						<a href="modul_rss_itunes.php?action=del_rss_i&amp;id=<?php echo $ar_rss_item['podcast_id'];?>&chid=<?php echo $_GET['chid'];?>&amp;project=<?php echo $_SESSION['project'];?>"><img src="images/sys_del.gif" height="18" width="18" border="0" alt="<?php echo _CMN_DEL;?>"></a>
					</td>
					</td>
					<td width="30" align="right"><?php echo $ar_rss_item['podcast_id'];?></td>
					<td width="30" align="center"><img src="images/sys_<?php if ($ar_rss_item['podcast_block'] == 1){echo "no";} else {echo "yes";}?>.gif" alt="" width="18" height="18" border="0"></td>
					<td><?php echo $ar_rss_item['podcast_title'];?></td>
					<td width="150" <?php  $date = date('Y-m-d H:i:s',mktime($h ,$m ,$s ,$M ,$D ,$Y )); if (date('Y-m-d H:i:s') < $date){echo 'style="color:#ff0000;"';}?>><?php echo FormatDatetime($ar_rss_item['podcast_pub_date'],"d.m.Y H:i:s");?></td>
					<td width="80"><?php echo $ar_rss_item['podcast_duration'];?></td>
				</tr><?php
 			}?>
	</table><?php
}
/***********************************************************************************************************
*
*		PRIDAT iTunes RSS KANAL
*
***********************************************************************************************************/
function AddRSSChannel(){

	global $db_podcast_channel,$db_podcast,$db_rss_lang;
	global $url_rss_itunes;

	/* Provereni opravneni */
	if ($_GET['action'] == "add_rss_ch"){
		if (CheckPriv("groups_rss_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "edit_rss_ch"){
		if (CheckPriv("groups_rss_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		 echo _NOTENOUGHPRIV;ShowMain();exit;
	}

	if ($_GET['action'] == "edit_rss_ch"){
		$res_rss_channel = mysql_query("SELECT * FROM $db_podcast_channel WHERE podcast_channel_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_rss_channel = mysql_fetch_array($res_rss_channel);
	}

	if ($_GET['action'] == "add_rss_ch" || $_GET['action'] == "edit_rss_ch"){?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _RSS_ITUNES_CHANNELS.' - '; if ($_GET['action'] == "add_rss_ch"){echo _RSS_ITUNES_ADD;} else {echo _RSS_ITUNES_EDIT." - ID ".$_GET['id'];}?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0"><a href="modul_rss_itunes.php?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_MAIN;?></a></td>
		</tr><?php
		/* Zobrazeni chyb a hlasek systemu */
		if ($_GET['msg']){
			echo '<tr><td style="color:#ff0000;">'.SysMsg($_GET['msg']).'</td></tr>';
		}?>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr	 align="right" valign="top">
			<td width="200"><form action="sys_save.php?action=<?php if ($_GET['action'] == "add_rss_ch"){echo "add_rss_ch";} else {echo "edit_rss_ch";}?>&amp;id=<?php echo $_GET['id'];?>" enctype="multipart/form-data" method="post"><?php echo _RSS_ITUNES_CHANNEL_TITLE;?></td>
			<td align="left"><input type="text" name="podcast_channel_title" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_title'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_SUBTITLE;?></td>
			<td align="left"><input type="text" name="podcast_channel_subtitle" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_subtitle'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_LINK;?></td>
			<td align="left"><input type="text" name="podcast_channel_link" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_link'];?>">http://www.yourweb.com/index.php?action=podcasts</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_LINK_NEW;?></td>
			<td align="left"><input type="text" name="podcast_channel_link_new" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_link_new'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_AUTHOR;?></td>
			<td align="left"><input type="text" name="podcast_channel_author" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_author'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_COPYRIGHT;?></td>
			<td align="left"><input type="text" name="podcast_channel_copyright" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_copyright'];?>">(C) = ©</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_SUMMARY;?></td>
			<td align="left"><textarea cols="50" rows="8" name="podcast_channel_summary"><?php echo $ar_rss_channel['podcast_channel_summary'];?></textarea></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_OWNER_NAME;?></td>
			<td align="left"><input type="text" name="podcast_channel_owner_name" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_owner_name'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_OWNER_EMAIL;?></td>
			<td align="left"><input type="text" name="podcast_channel_owner_email" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_owner_email'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_CATEGORY;?></td>
			<td align="left"><select name="podcast_channel_category">
					<option value="" <?php if ($ar_rss_channel['podcast_channel_category'] == ""){ echo "selected=\"selected\"";}?> >Vyberte iTunes kategorii</option>
					<option value="Arts" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts"){ echo "selected=\"selected\"";}?> >Arts</option>
					<option value="Arts||Design" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Design"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Design</option>
					<option value="Arts||Fashion & Beauty" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Fashion"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Fashion &amp; Beauty</option>
					<option value="Arts||Food" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Food"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Food</option>
					<option value="Arts||Literature" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Literature"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Literature</option>
					<option value="Arts||Performing Arts" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Performing Arts"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Performing Arts</option>
					<option value="Arts||Visual Arts" <?php if ($ar_rss_channel['podcast_channel_category'] == "Arts||Visual Arts"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Visual Arts</option>
					<option value="Business" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business"){ echo "selected=\"selected\"";}?> v>Business</option>
					<option value="Business||Business News" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business||Business News"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Business News</option>
					<option value="Business||Careers" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business||Careers"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Careers</option>
					<option value="Business||Investing" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business||Investing"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Investing</option>
					<option value="Business||Management & Marketing" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business||Management &amp; Marketing"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Management &amp; Marketing</option>
					<option value="Business||Shopping" <?php if ($ar_rss_channel['podcast_channel_category'] == "Business||Shopping"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Shopping</option>
					<option value="Comedy" <?php if ($ar_rss_channel['podcast_channel_category'] == "Comedy"){ echo "selected=\"selected\"";}?> >Comedy</option>
					<option value="Education" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education"){ echo "selected=\"selected\"";}?> >Education</option>
					<option value="Education||Education Technology" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education||Education Technology"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Education Technology</option>
					<option value="Education||Higher Education" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education||Higher Education"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Higher Education</option>
					<option value="Education||K-12" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education||K-12"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;K-12</option>
					<option value="Education||Language Courses" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education||Language Courses"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Language Courses</option>
					<option value="Education||Training" <?php if ($ar_rss_channel['podcast_channel_category'] == "Education||Training"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Training</option>
					<option value="Games & Hobbies" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies"){ echo "selected=\"selected\"";}?> >Games &amp; Hobbies</option>
					<option value="Games & Hobbies||Automotive" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies||Automotive"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Automotive</option>
					<option value="Games & Hobbies||Aviation" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies||Aviation"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Aviation</option>
					<option value="Games & Hobbies||Hobbies" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies||Hobbies"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Hobbies</option>
					<option value="Games & Hobbies||Other Games" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies||Other Games"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Other Games</option>
					<option value="Games & Hobbies||Video Games" <?php if ($ar_rss_channel['podcast_channel_category'] == "Games &amp; Hobbies||Video Games"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Video Games</option>
					<option value="Government & Organizations" <?php if ($ar_rss_channel['podcast_channel_category'] == "Government &amp; Organizations"){ echo "selected=\"selected\"";}?> >Government &amp; Organizations</option>
					<option value="Government & Organizations||Local" <?php if ($ar_rss_channel['podcast_channel_category'] == "Government &amp; Organizations||Local"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Local</option>
					<option value="Government & Organizations||National" <?php if ($ar_rss_channel['podcast_channel_category'] == "Government &amp; Organizations||National"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;National</option>
					<option value="Government & Organizations||Non-Profit" <?php if ($ar_rss_channel['podcast_channel_category'] == "Government &amp; Organizations||Non-Profit"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Non-Profit</option>
					<option value="Government & Organizations||Regional" <?php if ($ar_rss_channel['podcast_channel_category'] == "Government &amp; Organizations||Regional"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Regional</option>
					<option value="Health" <?php if ($ar_rss_channel['podcast_channel_category'] == "Health"){ echo "selected=\"selected\"";}?> >Health</option>
					<option value="Health||Alternative Health" <?php if ($ar_rss_channel['podcast_channel_category'] == "Health||Alternative Health"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Alternative Health</option>
					<option value="Health||Fitness & Nutrition" <?php if ($ar_rss_channel['podcast_channel_category'] == "Health||Fitness &amp; Nutrition"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Fitness &amp; Nutrition</option>
					<option value="Health||Self-Help" <?php if ($ar_rss_channel['podcast_channel_category'] == "Health||Self-Help"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Self-Help</option>
					<option value="Health||Sexuality" <?php if ($ar_rss_channel['podcast_channel_category'] == "Health||Sexuality"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Sexuality</option>
					<option value="Kids & Family" <?php if ($ar_rss_channel['podcast_channel_category'] == "Kids &amp; Family"){ echo "selected=\"selected\"";}?> >Kids &amp; Family</option>
					<option value="Music" <?php if ($ar_rss_channel['podcast_channel_category'] == "Music"){ echo "selected=\"selected\"";}?> >Music</option>
					<option value="News & Politics" <?php if ($ar_rss_channel['podcast_channel_category'] == "News &amp; Politics"){ echo "selected=\"selected\"";}?> >News &amp; Politics</option>
					<option value="Religion & Spirituality" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality"){ echo "selected=\"selected\"";}?> >Religion &amp; Spirituality</option>
					<option value="Religion & Spirituality||Buddhism" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Buddhism"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Buddhism</option>
					<option value="Religion & Spirituality||Christianity" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Christianity"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Christianity</option>
					<option value="Religion & Spirituality||Hinduism" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Hinduism"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Hinduism</option>
					<option value="Religion & Spirituality||Islam" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Islam"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Islam</option>
					<option value="Religion & Spirituality||Judaism" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Judaism"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Judaism</option>
					<option value="Religion & Spirituality||Other" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Other"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Other</option>
					<option value="Religion & Spirituality||Spirituality" <?php if ($ar_rss_channel['podcast_channel_category'] == "Religion &amp; Spirituality||Spirituality"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Spirituality</option>
					<option value="Science & Medicine" <?php if ($ar_rss_channel['podcast_channel_category'] == "Science &amp; Medicine"){ echo "selected=\"selected\"";}?> >Science &amp; Medicine</option>
					<option value="Science & Medicine||Medicine" <?php if ($ar_rss_channel['podcast_channel_category'] == "Science &amp; Medicine||Medicine"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Medicine</option>
					<option value="Science & Medicine||Natural Sciences" <?php if ($ar_rss_channel['podcast_channel_category'] == "Science &amp; Medicine||Natural Sciences"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Natural Sciences</option>
					<option value="Science & Medicine||Social Sciences" <?php if ($ar_rss_channel['podcast_channel_category'] == "Science &amp; Medicine||Social Sciences"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Social Sciences</option>
					<option value="Society & Culture" <?php if ($ar_rss_channel['podcast_channel_category'] == "Society &amp; Culture"){ echo "selected=\"selected\"";}?> >Society &amp; Culture</option>
					<option value="Society & Culture||History" <?php if ($ar_rss_channel['podcast_channel_category'] == "Society &amp; Culture||History"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;History</option>
					<option value="Society & Culture||Personal Journals" <?php if ($ar_rss_channel['podcast_channel_category'] == "Society &amp; Culture||Personal Journals"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Personal Journals</option>
					<option value="Society & Culture||Philosophy" <?php if ($ar_rss_channel['podcast_channel_category'] == "Society &amp; Culture||Philosophy"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Philosophy</option>
					<option value="Society & Culture||Places & Travel" <?php if ($ar_rss_channel['podcast_channel_category'] == "Society &amp; Culture||Places &amp; Travel"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Places &amp; Travel</option>
					<option value="Sports & Recreation" <?php if ($ar_rss_channel['podcast_channel_category'] == "Sports &amp; Recreation"){ echo "selected=\"selected\"";}?> >Sports &amp; Recreation</option>
					<option value="Sports & Recreation||Amateur" <?php if ($ar_rss_channel['podcast_channel_category'] == "Sports &amp; Recreation||Amateur"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Amateur</option>
					<option value="Sports & Recreation||College & High School" <?php if ($ar_rss_channel['podcast_channel_category'] == "Sports &amp; Recreation||College &amp; High School"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;College &amp; High School</option>
					<option value="Sports & Recreation||Outdoor" <?php if ($ar_rss_channel['podcast_channel_category'] == "Sports &amp; Recreation||Outdoor"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Outdoor</option>
					<option value="Sports & Recreation||Professional" <?php if ($ar_rss_channel['podcast_channel_category'] == "Sports &amp; Recreation||Professional"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Professional</option>
					<option value="Technology" <?php if ($ar_rss_channel['podcast_channel_category'] == "Technology"){ echo "selected=\"selected\"";}?> >Technology</option>
					<option value="Technology||Gadgets" <?php if ($ar_rss_channel['podcast_channel_category'] == "Technology||Gadgets"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Gadgets</option>
					<option value="Technology||Tech News" <?php if ($ar_rss_channel['podcast_channel_category'] == "Technology||Tech News"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Tech News</option>
					<option value="Technology||Podcasting" <?php if ($ar_rss_channel['podcast_channel_category'] == "Technology||Podcasting"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Podcasting</option>
					<option value="Technology||Software How-To" <?php if ($ar_rss_channel['podcast_channel_category'] == "Technology||Software How-To"){ echo "selected=\"selected\"";}?> >&nbsp;&nbsp;&nbsp;&nbsp;Software How-To</option>
					<option value="TV & Film" <?php if ($ar_rss_channel['podcast_channel_category'] == "TV &amp; Film"){ echo "selected=\"selected\"";}?> >TV &amp; Film</option>
				</select>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_KEYWORDS;?></td>
			<td align="left"><input type="text" name="podcast_channel_keywords" size="60" maxlength="255" value="<?php echo $ar_rss_channel['podcast_channel_keywords'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_IMAGE;?></td>
			<td align="left"><input type="file" name="podcast_channel_img" size="50"><br><?php
				if ($ar_rss_channel['podcast_channel_image'] != ""){echo '<img src="'.$url_rss_itunes.$ar_rss_channel['podcast_channel_image'].'" alt="" border="0">';}?>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_LANG;?></td>
			<td align="left"><select name="podcast_channel_lang"><?php
 				$res_lang = mysql_query("SELECT rss_lang_id, rss_lang_name FROM $db_rss_lang ORDER BY rss_lang_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while($ar_lang = mysql_fetch_array($res_lang)){
					echo '<option value="'.$ar_lang['rss_lang_id'].'"'; if ($ar_rss_channel['podcast_channel_lang'] == $ar_lang['rss_lang_id'] || ($_GET['action'] == "add_rss_ch" && $ar_lang['rss_lang_id'] == 10)){ echo "selected=\"selected\"";} echo '>'.$ar_lang['rss_lang_name'].'</option>';
				}?>
			</select>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_BLOCK;?></td>
			<td align="left"><input type="checkbox" name="podcast_channel_block" <?php if ($ar_rss_channel['podcast_channel_block'] == 1){echo "checked";}?> value="1"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_EXPLICIT;?></td>
			<td align="left"><select name="podcast_channel_explicit">
				<option value="0" <?php if ($ar_rss_channel['podcast_channel_explicit'] == 0){ echo "selected=\"selected\"";}?>><?php echo _RSS_ITUNES_CHANNEL_EXPLICIT_NO;?></option>
				<option value="1" <?php if ($ar_rss_channel['podcast_channel_explicit'] == 1){ echo "selected=\"selected\"";}?>><?php echo _RSS_ITUNES_CHANNEL_EXPLICIT_CLEAN;?></option>
				<option value="2" <?php if ($ar_rss_channel['podcast_channel_explicit'] == 2){ echo "selected=\"selected\"";}?>><?php echo _RSS_ITUNES_CHANNEL_EXPLICIT_YES;?></option>
			</select>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _RSS_ITUNES_CHANNEL_ITEMS_NUM;?></td>
			<td align="left"><input type="text" name="podcast_channel_items_num" size="5" maxlength="4" value="<?php if ($_GET['action'] == "add_rss_ch"){ echo "50";} else { echo $ar_rss_channel['podcast_channel_items_num'];}?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td colspan="2" width="200">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="hidden" name="confirm" value="true">
				<input type="submit" value="<?php echo  _CMN_SUBMIT;?>" class="eden_button">
				</form>
			</td>
		</tr>
	</table><?php
 	}
}
/***********************************************************************************************************
*
*		ODSTRANENI iTunes RSS KANALU
*
***********************************************************************************************************/
function DelRSSChannel(){

	global $db_podcast_channel,$db_rss_lang;

	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_rss_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}

	if ($_POST['confirm'] == "true") {$res = mysql_query("DELETE FROM $db_podcast_channel WHERE podcast_channel_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); ShowMain();exit();}
	if ($_POST['confirm'] == "false"){ShowMain();}
	if ($_POST['confirm'] == ""){?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left"><span class="nadpis"><?php echo _RSS_ITUNES_CHANNELS; echo " - "._RSS_DEL;?></span></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0" alt=""><a href="modul_rss_itunes.php?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_MAIN;?></a></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="30"><span class="nadpis-boxy">ID</span></td>
			<td><span class="nadpis-boxy"><?php echo _RSS_ITUNES_CHANNEL_TITLE;?></span></td>
			<td><span class="nadpis-boxy"><?php echo _RSS_LANG;?></span></td>
		</tr><?php
	$res = mysql_query("SELECT r.podcast_channel_id, r.podcast_channel_title, rl.rss_lang_name FROM $db_podcast_channel AS r, $db_rss_lang AS rl WHERE r.podcast_channel_id=".(float)$_GET['id']." AND rl.rss_lang_id=r.podcast_channel_lang") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		echo '<tr	>
				<td width="30" align="right" valign="top">'.$ar['podcast_channel_id'].'</td>
				<td valign="top" align="left">'.$ar['podcast_channel_title'].'</td>
				<td valign="top" align="left">'.$ar['rss_lang_name'].'</td>
			</tr>';
	}
	echo '</table><br><br>';?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td valign="top" colspan="2"><strong><span style="color : #FF0000;"><?php echo _RSS_CHECKDELETE; ?></span></strong></td>
		</tr>
		<tr>
			<td valign="top">
				<form action="modul_rss_itunes.php?action=del_rss_ch" method="post">
					<input type="submit" value="<?php echo _CMN_YES;?>" class="eden_button"><br>
					<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
					<input type="hidden" name="confirm" value="true">
				</form>
			</td>
			<td valign="top">
				<form action="modul_rss.php?action=del_rss_ch" method="post">
					<input type="submit" value="<?php echo _CMN_NO;?>" class="eden_button_no"><br>
					<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
					<input type="hidden" name="confirm" value="false">
				</form>
			</td>
		</tr>
	</table><?php
	}
}
/***********************************************************************************************************
*
*		PRIDAT iTunes RSS ITEM
*
***********************************************************************************************************/
function AddRSSItem(){

	global $db_admin,$db_podcast_channel,$db_podcast,$db_rss_lang,$db_setup;

	/* Provereni opravneni */
	if ($_GET['action'] == "add_rss_i"){
		if (CheckPriv("groups_rss_add") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	}elseif ($_GET['action'] == "edit_rss_i"){
		if (CheckPriv("groups_rss_edit") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}
	} else {
		 echo _NOTENOUGHPRIV;ShowMain();exit;
	}

	if ($_GET['action'] == "edit_rss_i"){
		$res_rss_item = mysql_query("SELECT * FROM $db_podcast WHERE podcast_id=".$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$ar_rss_item = mysql_fetch_array($res_rss_item);
	}

	$res_setup = mysql_query("SELECT setup_reg_admin_nick FROM $db_setup") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_setup = mysql_fetch_array($res_setup);

	$res_rss_channel = mysql_query("SELECT podcast_channel_id, podcast_channel_title FROM $db_podcast_channel WHERE podcast_channel_id=".(float)$_GET['chid']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	$ar_rss_channel = mysql_fetch_array($res_rss_channel);

	if ($_GET['action'] == "add_rss_i" || $_GET['action'] == "edit_rss_i"){?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left" class="nadpis"><?php echo _PODCAST.' - '; if ($_GET['action'] == "add_rss_i"){echo _PODCAST_ADD;} else {echo _PODCAST_EDIT.' - '._RSS_ITUNES_CHANNEL.' ID:'.$ar_rss_channel['podcast_channel_id'].' '.$ar_rss_channel['podcast_channel_title'];}?></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0">
				<a href="modul_rss_itunes.php?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_MAIN;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="modul_rss_itunes.php?action=showitems&chid=<?php echo $_GET['chid'];?>&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _PODCASTS;?></a>
			</td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr	 align="right" valign="top">
			<td width="200"><form action="sys_save.php?action=<?php if ($_GET['action'] == "add_rss_i"){echo "add_rss_i";} else {echo "edit_rss_i";}?>&amp;id=<?php echo $_GET['id'];?>" name="rss_itunes_i" method="post">
				<strong><?php echo _PODCAST_TITLE;?></strong></td>
			<td align="left"><input type="text" name="podcast_title" size="60" maxlength="255" value="<?php echo $ar_rss_item['podcast_title'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_CHANNEL;?></strong></td>
			<td align="left"><select name="chid"><?php
 				$res_rss_channel = mysql_query("SELECT podcast_channel_id, podcast_channel_title FROM $db_podcast_channel ORDER BY podcast_channel_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while($ar_rss_channel = mysql_fetch_array($res_rss_channel)){
					echo '<option value="'.$ar_rss_channel['podcast_channel_id'].'"'; if ($ar_rss_channel['podcast_channel_id'] == $_GET['chid']){ echo "selected=\"selected\"";} echo '>'.$ar_rss_channel['podcast_channel_title'].'</option>';
				}?>
			</select>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_SUBTITLE;?></strong></td>
			<td align="left"><input type="text" name="podcast_subtitle" size="60" maxlength="255" value="<?php echo $ar_rss_item['podcast_subtitle'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_AUTHOR;?></strong></td>
			<td align="left"><select name="podcast_author_id"><?php
				if ($ar_setup['setup_reg_admin_nick'] == 0){ $orderby = "admin_name ASC, admin_firstname ASC";} else {$orderby = "admin_nick ASC";}
				$res_adm = mysql_query("SELECT admin_id, admin_nick, admin_firstname, admin_name FROM $db_admin WHERE admin_status='admin' ORDER BY $orderby") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				while ($ar_adm = mysql_fetch_array($res_adm)){
					if ($ar_setup['setup_reg_admin_nick'] == 0){ $adm_name = $ar_adm['admin_firstname']." ".$ar_adm['admin_name'].' ('.$ar_adm['admin_nick'].')';} else {$adm_name = $ar_adm['admin_nick'].' ('.$ar_adm['admin_firstname']." ".$ar_adm['admin_name'].')';}
					echo '<option value="'.$ar_adm['admin_id'].'"'; if (($ar_rss_item['podcast_author_id'] == $ar_adm['admin_id']) || ($_GET['action'] == "add_rss_i" && $ar_adm['admin_id'] == $_SESSION['loginid'])){ echo "selected=\"selected\"";} echo '>'.$adm_name.'</option>';
				}?>
			</select></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_SUMMARY;?></strong></td>
			<td align="left"><textarea cols="50" rows="8" name="podcast_summary"><?php echo $ar_rss_item['podcast_summary'];?></textarea></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_SUMMARY;?></strong></td>
			<td align="left"><textarea cols="50" rows="3" name="podcast_summary"><?php echo $ar_rss_item['podcast_summary'];?></textarea></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_ENCLOSURE_URL;?></strong></td>
			<td align="left"><input type="text" name="podcast_enclosure_url" size="60" maxlength="255" value="<?php echo stripslashes($ar_rss_item['podcast_enclosure_url']);?>"> http://www.yourweb.com/podcast/podcast_001.mp3</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_ENCLOSURE_LENGHT;?></strong></td>
			<td align="left"><input type="text" name="podcast_enclosure_lenght" size="20" maxlength="20" value="<?php echo $ar_rss_item['podcast_enclosure_lenght'];?>"> <?php echo _PODCAST_ENCLOSURE_LENGHT_B;?></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_ENCLOSURE_TYPE;?></strong></td>
			<td align="left"><select name="podcast_enclosure_type">
				<option value="audio/mpeg" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "audio/mpeg"){ echo "selected=\"selected\"";}?>>.mp3</option>
				<option value="video/mp4" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "video/mp4"){ echo "selected=\"selected\"";}?>>.mp4</option>
				<option value="audio/x-m4a" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "audio/x-m4a"){ echo "selected=\"selected\"";}?>>.m4a</option>
				<option value="video/x-m4v" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "video/x-m4v"){ echo "selected=\"selected\"";}?>>.m4v</option>
				<option value="video/quicktime" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "video/quicktime"){ echo "selected=\"selected\"";}?>>.mov</option>
				<option value="application/pdf" <?php if (stripslashes($ar_rss_item['podcast_enclosure_type']) == "application/pdf"){ echo "selected=\"selected\"";}?>>.pdf</option>
			</select></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_GUID;?></strong></td>
			<td align="left"><input type="text" name="podcast_guid" size="60" maxlength="255" value="<?php echo $ar_rss_item['podcast_guid'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_PUB_DATE;?></strong></td>
			<td align="left"><?php
				if ($_GET['action'] == "add_rss_i"){
					$article_date_on = formatTimeS(time());?>
					<script language="javascript">
					var StartDate = new ctlSpiffyCalendarBox("StartDate", "rss_itunes_i", "podcast_pub_date", "btnDate1","<?php echo $article_date_on[1];?>.<?php echo $article_date_on[2];?>.<?php echo $article_date_on[3];?>",scBTNMODE_CUSTOMBLUE);
					</script>
					<script language="javascript">StartDate.writeControl(); StartDate.dateFormat="dd-MM-yyyy";</script> <select name="podcast_pub_date_h"><?php
						for ($i=0;$i<=23;$i++){
							echo '<option value="'.Zerofill($i,10).'" '; if ($article_date_on[4] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
						}?>
						</select><strong>:</strong><select name="podcast_pub_date_m"><?php
							for ($i=0;$i<=60;$i++){
								echo '<option value="'.Zerofill($i,10).'" '; if ($article_date_on[5] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
							}?>
						</select><strong>:</strong>00<?php
				} else {
					$article_date_on = $ar_rss_item['podcast_pub_date'];?>
					<script language="javascript">
					var StartDate = new ctlSpiffyCalendarBox("StartDate", "rss_itunes_i", "podcast_pub_date", "btnDate1","<?php echo $article_date_on[8].$article_date_on[9];?>.<?php echo $article_date_on[5].$article_date_on[6];?>.<?php echo $article_date_on[0].$article_date_on[1].$article_date_on[2].$article_date_on[3];?>",scBTNMODE_CUSTOMBLUE);
					</script>
					<script language="javascript">StartDate.writeControl(); StartDate.dateFormat="dd-MM-yyyy";</script> <select name="podcast_pub_date_h"><?php
						for ($i=0;$i<=23;$i++){
							echo '<option value="'.Zerofill($i,10).'" '; if ($article_date_on[11].$article_date_on[12] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
						}?>
						</select><strong>:</strong><select name="podcast_pub_date_m"><?php
							for ($i=0;$i<=60;$i++){
								echo '<option value="'.Zerofill($i,10).'" '; if ($article_date_on[14].$article_date_on[15] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
							}?>
						</select><strong>:</strong>00<?php
				}?>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_DURATION;?></strong></td>
			<td align="left"><select name="podcast_duration_h"><?php
			$duration = explode (":", $ar_rss_item['podcast_duration']);
				for ($i=0;$i<=23;$i++){
					echo '<option value="'.Zerofill($i,10).'" '; if ($duration[0] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
				}?>
			</select>:
			<select name="podcast_duration_m"><?php
				for ($i=0;$i<60;$i++){
					echo '<option value="'.Zerofill($i,10).'" '; if ($duration[1] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
				}?>
			</select>:
			<select name="podcast_duration_s"><?php
				for ($i=0;$i<60;$i++){
					echo '<option value="'.Zerofill($i,10).'" '; if ($duration[2] == Zerofill($i,10)){ echo "selected=\"selected\"";} echo '>'.Zerofill($i,10).'</option>';
				}?>
			</select> hh:mm:ss
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><?php echo _PODCAST_KEYWORDS;?></td>
			<td align="left"><input type="text" name="podcast_keywords" size="60" maxlength="255" value="<?php echo $ar_rss_item['podcast_keywords'];?>"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_BLOCK;?></strong></td>
			<td align="left"><input type="checkbox" name="podcast_block" <?php if ($ar_rss_item['podcast_block'] == 1){echo "checked";}?> value="1"></td>
		</tr>
		<tr	 align="right" valign="top">
			<td width="200"><strong><?php echo _PODCAST_EXPLICIT;?></strong></td>
			<td align="left"><select name="podcast_explicit">
				<option value="0" <?php if ($ar_rss_item['podcast_explicit'] == 0){ echo "selected=\"selected\"";}?>><?php echo _PODCAST_EXPLICIT_NO;?></option>
				<option value="1" <?php if ($ar_rss_item['podcast_explicit'] == 1){ echo "selected=\"selected\"";}?>><?php echo _PODCAST_EXPLICIT_CLEAN;?></option>
				<option value="2" <?php if ($ar_rss_item['podcast_explicit'] == 2){ echo "selected=\"selected\"";}?>><?php echo _PODCAST_EXPLICIT_YES;?></option>
			</select>
			</td>
		</tr>
		<tr	 align="right" valign="top">
			<td colspan="2" width="200">
				<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
				<input type="hidden" name="confirm" value="true">
				<input type="submit" value="<?php echo _CMN_SUBMIT;?>" class="eden_button">
				</form>
			</td>
		</tr>

	</table><?php
 	}
}
/***********************************************************************************************************
*
*		ODSTRANENI Podcastu
*
***********************************************************************************************************/
function DelRSSItem(){

	global $db_podcast,$db_rss_lang;

	/* CHECK PRIVILEGIES */
	if (CheckPriv("groups_rss_del") <> 1) { echo _NOTENOUGHPRIV;ShowMain();exit;}

	if ($_POST['confirm'] == "true") {$res = mysql_query("DELETE FROM $db_podcast WHERE podcast_id=".(float)$_POST['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error()); ShowItems();exit();}
	if ($_POST['confirm'] == "false"){ShowItems();}
	if ($_POST['confirm'] == ""){?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td align="left"><span class="nadpis"><?php echo _PODCAST; echo " - "._PODCAST_DEL;?></span></td>
		</tr>
		<tr>
			<td><img src="images/sys_manage.gif" height="18" width="18" border="0" alt=""><a href="modul_rss_itunes.php?action=showmain&amp;project=<?php echo $_SESSION['project'];?>"><?php echo _RSS_ITUNES_MAIN;?></a></td>
		</tr>
	</table>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr class="popisky">
			<td width="30"><span class="nadpis-boxy">ID</span></td>
			<td><span class="nadpis-boxy"><?php echo _PODCAST_TITLE;?></span></td>
			<td><span class="nadpis-boxy"><?php echo _PODCAST_AUTHOR;?></span></td>
		</tr><?php
	$res = mysql_query("SELECT podcast_id, podcast_title, podcast_author FROM $db_podcast WHERE podcast_id=".(float)$_GET['id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
	while ($ar = mysql_fetch_array($res)){
		echo '<tr	>
				<td width="30" align="right" valign="top">'.$ar['podcast_id'].'</td>
				<td valign="top" align="left">'.$ar['podcast_title'].'</td>
				<td valign="top" align="left">'.$ar['podcast_author'].'</td>
			</tr>';
	}
	echo '</table><br><br>';?>
	<?php echo "<table width=\"857\" cellspacing=\"2\" cellpadding=\"1\" class=\"eden_main_table\">\n";?>
		<tr>
			<td valign="top" colspan="2"	><strong><span style="color : #FF0000;"><?php echo _PODCAST_CHECKDEL; ?></span></strong></td>
		</tr>
		<tr>
			<td valign="top">
				<form action="modul_rss_itunes.php?action=del_rss_i" method="post">
					<input type="submit" value="<?php echo _CMN_YES;?>" class="eden_button"><br>
					<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
					<input type="hidden" name="confirm" value="true">
				</form>
			</td>
			<td valign="top">
				<form action="modul_rss.php?action=del_rss_i" method="post">
					<input type="submit" value="<?php echo _CMN_NO;?>" class="eden_button_no"><br>
					<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<input type="hidden" name="project" value="<?php echo $_SESSION['project'];?>">
					<input type="hidden" name="confirm" value="false">
				</form>
			</td>
		</tr>
	</table><?php
	}
}
include "inc.header.php";
	if ($_GET['action'] == "" || $_GET['action'] == "showmain"){ShowMain();}
	if ($_GET['action'] == "add_rss_ch"){AddRSSChannel();}
	if ($_GET['action'] == "edit_rss_ch"){AddRSSChannel();}
	if ($_GET['action'] == "del_rss_ch"){DelRSSChannel();}
	if ($_GET['action'] == "showitems"){ShowItems();}
	if ($_GET['action'] == "add_rss_i"){AddRSSItem();}
	if ($_GET['action'] == "edit_rss_i"){AddRSSItem();}
	if ($_GET['action'] == "del_rss_i"){DelRSSItem();}
include "inc.footer.php";