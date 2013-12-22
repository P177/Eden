<?php
/**
 * Based on: http://sachachua.com/blog/2011/08/drupal-html-purifier-embedding-iframes-youtube/
 * Iframe filter that does some primitive whitelisting in a somewhat recognizable and tweakable way
 */
class HTMLPurifier_Filter_MyIframe extends HTMLPurifier_Filter
{
    public $name = 'MyIframe';

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, HTMLPurifier_Config $config, HTMLPurifier_Context $context)
    {
        $html = preg_replace('#<iframe#i', '<img class="MyIframe"', $html);
        $html = preg_replace('#</iframe>#i', '</img>', $html);
        return $html;
    }

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, HTMLPurifier_Config $config, HTMLPurifier_Context $context)
    {
        $post_regex = '#<img class="MyIframe"([^>]+?)>#';
        return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
    }

//<iframe width="560" height="315" src="http://www.youtube.com/embed/G2FMdD9Lahg" frameborder="0" allowfullscreen></iframe>

//<iframe width="480" height="296" src="http://www.ustream.tv/embed/2026412" scrolling="no" frameborder="0" style="border: 0px none transparent;">    </iframe>
//<br /><a href="http://www.ustream.tv/" style="padding: 2px 0px 4px; width: 400px; background: #ffffff; display: block; color: #000000; font-weight: normal; font-size: 10px; text-decoration: underline; text-align: center;" target="_blank">Streaming live video by Ustream</a>

//<iframe src="http://player.vimeo.com/video/40950267" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> <p><a href="http://vimeo.com/40950267">London aerial 1</a> from <a href="http://vimeo.com/jasonhawkes">JasonHawkes</a> on <a href="http://vimeo.com">Vimeo</a>.</p>

//<iframe height="360" width="640" frameborder="0" src="http://www.own3d.tv/liveembed/140914"></iframe>
				
//<iframe src="http://api.new.livestream.com/accounts/376536/events/912382/videos/1387090.html?width=640&height=360&autoPlay=false&mute=false" width="640" height="360" frameborder="0" scrolling="no"></iframe>

//<iframe frameborder="0" scrolling="no" id="chat_embed" src="http://twitch.tv/chat/embed?channel=gomtv_en&amp;popout_chat=true" height="500" width="350"></iframe>

   /**
     *
     * @param array $matches
     * @return string
     */
    protected function postFilterCallback($matches)
    {
        // Domain Whitelist
        $youTubeMatch = preg_match('#src="https?://www.youtube(-nocookie)?.com/#i', $matches[1]);
        $youTubeMatch2 = preg_match('#src="http://www.youtube.com/#i', $matches[1]);
        $vimeoMatch = preg_match('#src="http://player.vimeo.com/#i', $matches[1]);
        $ustreamMatch = preg_match('#src="http://www.ustream.tv/#i', $matches[1]);
        $own3dMatch = preg_match('#src="http://www.own3d.tv/#i', $matches[1]);
		$livestreamMatch = preg_match('#src="http://api.new.livestream.com/#i', $matches[1]);
		$justintvmMatch = preg_match('#src="http://twitch.tv/#i', $matches[1]);
		
        if ($youTubeMatch || $youTubeMatch2 || $vimeoMatch || $ustreamMatch || $own3dMatch || $livestreamMatch || $justintvmMatch) {
            $extra = ' frameborder="0"';
            if ($youTubeMatch) {
                $extra .= ' allowfullscreen';
			} elseif ($youTubeMatch2) {
                $extra .= ' allowfullscreen';
            } elseif ($vimeoMatch) {
                $extra .= ' webkitAllowFullScreen mozallowfullscreen allowFullScreen';
            } elseif ($ustreamMatch){
				$extra .= '';
			} elseif ($own3dMatch){
				$extra .= '';
			} elseif ($livestreamMatch){
				$extra .= '';
			} elseif ($justintvmMatch){
				$extra .= '';
			}
            return '<iframe ' . $matches[1] . $extra . '></iframe>';
        } else {
            return '';
        }
    }
}