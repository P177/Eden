<?php

/** Streams
 *	@param array	$eden_cfg
 *	@param integer	Mode (0 = no cron, 1 = run by cron - update only)
 */
class Stream {
	
	/** @var array */
	private $eden_cfg;
	
	/** @var array */
	private $cron;
	
	public function __construct($eden_cfg, $cron = 0){
        $this->eden_cfg = $eden_cfg;
        $this->cron = $cron;
		
		if ($this->cron == 1){
			$this->updateStream();
		}
    }
	
	/**
	 * Check active stream channel
	 * @param	string	Channel ID
	 * @param	string	(justintv, livestream, own3d (API necessary))
	 * @return	bool
	 */
	public function checkActiveChannel($channel,$stream_url = "justintv"){
		$channel = strtolower($channel);
		$exist = false;
		switch (strtolower($stream_url)){
			case "justintv":
				$chan = "http://api.justin.tv/api/stream/list.json?channel=".$channel;
				$json = @file_get_contents($chan);
				$exist = strpos($json, 'name');
				break;
			case "livestream":
				$chan = "http://x".$channel."x.api.channel.livestream.com/2.0/livestatus.json";
				$json = @file_get_contents($chan);
				$json_array = json_decode($json, 1);
				if($json_array['channel']['isLive']) {
				    $exist = true;
				} else {
					$exist = false;
				}
				break;
			case "own3d":
				$chan = "http://api.own3d.tv/liveCheck.php?live_id=".$channel;
				$json = @file_get_contents($chan);
				$exist = strpos($json, 'true');
				break;
			case "twitchtv":
				$json = @file_get_contents("http://api.justin.tv/api/stream/list.json?channel={$channel}", 0, null, null); // care the link i have space between http and api cause i must have 5 posts to post links blah blah blah
				//$json = @file_get_contents("https://api.twitch.tv/kraken/streams/$channel", 0, null, null);
				$json_array = json_decode($json, true);
				if (isset($json_array[0]) && $json_array[0]['name'] == "live_user_{$channel}"){
				//if (isset($json_array[0]) && $json_array[0]['name'] == "live_user_$channel"){
					$exist = true;
				} else {
					$exist = false;
				}
				break;
			default:
		}
		if($exist != false) {
			//echo "+ ".$exist."-".$channel."-".$stream_url."<br>";
			return 1;
			exit;
		} else {
			//echo "- ".$exist."-".$channel."-".$stream_url."<br>";
			return 0;
			exit;
		}
	}
	
	/** 
	 * Update Streams
	 */
	private function updateStream(){
		
		$res_articles = mysql_query("SELECT article_id, article_source, category_id 
		FROM "._DB_ARTICLES."
		JOIN "._DB_CATEGORIES." ON category_id = article_category_id 
		WHERE category_stream = 1 AND article_publish < 2 AND article_parent_id = 0") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		
		while($ar_articles = mysql_fetch_array($res_articles)){
			
			$channel_source = explode("*||*",$ar_articles['article_source']);
			$status = $this->checkActiveChannel($channel_source[1],$channel_source[0]);
			
			
			echo "<table "; if ($status == 1){echo "style=\"color:#ff0000;font-weight:bold;\"";} echo ">";
			echo "<tr><td>".$ar_articles['category_id']."</td><td>".$ar_articles['article_id']."</td><td>".$status."</td><td>".$channel_source[1]."</td><td>".$channel_source[0]."</td></tr>";
			echo "</table>";
			
			
			$res = mysql_query("
			SELECT stream_id 
			FROM "._DB_STREAMS." 
			WHERE stream_category_id = ".(integer)$ar_articles['category_id']." AND stream_article_id = ".(integer)$ar_articles['article_id']."
			") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			$ar = mysql_fetch_array($res);
			$num = mysql_num_rows($res);
			
			if ($num == 1){
				mysql_query("
				UPDATE "._DB_STREAMS." SET 
				stream_date = NOW(), 
				stream_online =  ".(integer)$status." 
				WHERE stream_id = ".(integer)$ar['stream_id']) or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			} elseif ($num > 1){
				mysql_query("
				DELETE FROM "._DB_STREAMS." 
				WHERE stream_category_id = ".(integer)$ar_articles['category_id']." AND stream_article_id = ".(integer)$ar_articles['article_id']."
				") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
				$num = 0;
			}
			
			if ($num == 0){
				mysql_query("INSERT INTO "._DB_STREAMS." VALUES(
				'',
				'".(integer)$ar_articles['category_id']."',
				'".(integer)$ar_articles['article_id']."',
				NOW(), 
				'".(integer)$status."')") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
			}
		}
	}
	
	/** Show online/offline streams
	 * @return	string
	 */
	public function showChannels(){
		
		$res = mysql_query("
		SELECT article_id, article_headline, article_perex, article_text, article_link, article_category_id, 
		category_name, category_image 
		FROM "._DB_STREAMS." 
		JOIN "._DB_ARTICLES." ON article_id = stream_article_id
		JOIN "._DB_ADMINS." ON admin_id = article_author_id 
		JOIN "._DB_CATEGORIES." ON category_id = article_category_id 
		WHERE stream_online = 1 AND article_publish = 1 
		ORDER BY category_id ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
		$num = mysql_num_rows($res);
		
		$output = "";
		// Show only if there is live stream
		if ($num > 0){
			ob_start();
		    include "tpl.streams_list.php"; // no "template/" as the script is run from template folder
		    $output = ob_get_contents();
		    ob_end_clean();
		}
		return $output;
	}
}