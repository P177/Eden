<?php
/***********************************************************************************************************
*
*		TRANSLATE BB CODE TO HTML
*
*		$t = stripslashes($text);
*		$bb = new BB_to_HTML_Code();
*		$res = $bb->parse($t);
*		echo "<textarea cols=50 rows=20>$res</textarea><div style='border:1px solid red'>$res</div>";
*
***********************************************************************************************************/
class BB_to_HTML_Code{
	//General Tags
	var $tags = array('b' => 'span style="font-weight:bold;"','i' => 'span style="font-style:italic;"','u' => 'span style="text-decoration:underline"','s' => 'span style="text-decoration: line-through"', 'list' => 'ul','list=' => 'li','h1' => 'h1','h2' => 'h2','h3' => 'h3');
	// Simple tags
	var $simple_tags = array('[quote]' => '<blockquote>', '[/quote]' => '</blockquote>', '[code]' => '<div class="code"><code>', '[/code]' => '</code></div>');
	//Tags that must be mapped to diffierent parts
	var $mapped = array('url' => array('a','href',true),'img' => array('img','src',false));
	//Tags with atributes
	var $tags_with_att = array('url' => array('a','href'));
	//Span tags
	var $span_tags = array('size' => array('span style="font-size:','; line-height:normal;"','span'),'color' => array('span style="color:',';"','span'));
	//Gotta have smilies
	var $smilies = array(':)' => 'smile.gif',':(' => 'frown.gif');
	//Config Variables
	//Convert new line charactes to linebreaks?
	var $convert_newlines = true;
	//Parse For smilies?
	var $parse_smilies = true;
	//auto link urls(http and ftp), and email addresses?
	var $auto_links = false;
	//Internal Storage
	var $_code = '';
	function BB_to_HTML_Code($new=true,$parse=false,$links=false){
		$this->convert_newlines = $new;
		$this->parse_smilies = $parse;
		$this->auto_links = $links;
	}
	function parse($code){
		$this->_code = $code;
		$this->_strip_html();
		$this->_parse_tags();
		$this->_parse_simple_tags();
		$this->_parse_mapped();
		$this->_parse_tags_with_att();
		$this->_parse_span_tags();
		//$this->_parse_smilies();
		$this->_parse_links();
		$this->_convert_nl();
		return $this->_code;
	}
	function _strip_html(){
		$this->_code = strip_tags($this->_code);
	}
	function _convert_nl(){
		if($this->convert_newlines){
			//$this->_code = nl2br($this->_code);
			$this->_code = str_replace("\n","<br>",$this->_code);
		}
	}
	function _parse_tags(){
		foreach($this->tags as $old=>$new){
			$ex = explode(' ',$new);
			$this->_code = preg_replace('/\['.$old.'\](.+?)\[\/'.$old.'\]/is','<'.$new.'>$1</'.$ex[0].'>',$this->_code);
		}
	}
	function _parse_simple_tags(){
		foreach($this->simple_tags as $old=>$new){
			$this->_code = str_replace($old,$new,$this->_code);
		}
	}
	function _parse_mapped(){
		foreach($this->mapped as $tag=>$data){
			$reg = '/\['.$tag.'\](.+?)\[\/'.$tag.'\]/is';
			if($data[2]){
				$this->_code = preg_replace($reg,'<'.$data[0].' '.$data[1].'="$1">$1</'.$data[0].'>',$this->_code);
			}
			else{
				$this->_code = preg_replace($reg,'<'.$data[0].' '.$data[1].'="$1">',$this->_code);
			}
		}
	}
	function _parse_tags_with_att(){
		foreach($this->tags_with_att as $tag=>$data){
			$this->_code = preg_replace('/\['.$tag.'=(.+?)\](.+?)\[\/'.$tag.'\]/is','<'.$data[0].' '.$data[1].'="$1">$2</'.$data[0].'>',$this->_code);
		}
	}
	function _parse_span_tags(){
		foreach($this->span_tags as $tag=>$data){
			$this->_code = preg_replace('/\['.$tag.'=(.+?)\](.+?)\[\/'.$tag.'\]/is','<'.$data[0].'$1'.$data[1].'>$2</'.$data[2].'>',$this->_code);
		}
	}
	function _parse_smilies(){
		if($this->parse_smilies){
			foreach($this->smilies as $s=>$im){
				$this->_code = str_replace($s,'<img src="'.$im.'">',$this->_code);
			}
		}
	}
	function _parse_links(){
		if($this->auto_links){
			$this->_code = preg_replace('/([^"])(http:\/\/|ftp:\/\/)([^\s,]*)/i','$1<a href="$2$3">$2$3</a>',$this->_code);
			$this->_code = preg_replace('/([^"])([A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4})/i','$1<a href="mailto:$2">$2</a>',$this->_code);
		}
	}
	function addTag($old,$new){
		$this->tags[$old] = $new;
	}
	function addMapped($bb,$html,$att,$end=true){
		$this->mapped[$bb] = array($html,$att,$end);
	}
	function addTagWithAttribute($bb,$html,$att){
		$this->tags_with_att[$bb] = array($html,$att);
	}
	function addSmiley($code,$src){
		$this->smilies[$code] = $src;
	}
}
/***********************************************************************************************************
*
*		BB POST EDITOR CLASS - WEB
*
*		Priklad <form> tagu
*		<form name="post" action="eden_save.php?action=forum&amp;lang=".$_GET['lang']."&amp;filter=".$_GET['filter']."&amp;faction=posts&amp;id0=".$_GET['id0']."&amp;id1=".$_GET['id1']."&amp;id2=".$_GET['id2']."&amp;project=".$_SESSION['project']."" method="post" onsubmit="return checkForm(this)">
*
*		Priklad nastaveni
*		$post_editor = new PostEditor;				-	Iniciace Tridy
*		$post_editor->editor_name = "forum_post";	-	Nazev promenne v <textarea>
*		$post_editor->form_name = "post";			-	Nazev formulare
*		$post_editor->form_text = $forum_post;		-	Promenna ktera se vklada pri editaci/quote
*
*		$post_editor->BBEditor();					-	Iniciace samotneho editoru
*
*
***********************************************************************************************************/

class PostEditor{
	
	/* Nazev textarea pole */
	var $editor_name = "forum_post";
	/* Nazev formulare */
	var $form_name = "post";
	/* Text v textarea pri Editaci nebo Quote */
	var $form_text;
	var $table_width = "500";
	var $textarea_width = "500";
	var $textarea_rows = "15";
	var $textarea_cols = "50";
	
	function BBEditor(){
		
		$output = "		<SCRIPT language=javascript type=text/javascript>\n";
		$output .= "		<!--\n";
		$output .= "		// Startup variables\n";
		$output .= "		var imageTag = false;\n";
		$output .= "		var theSelection = false;\n";
		
		$output .= "		// Check for Browser & Platform for PC & IE specific bits\n";
		$output .= "		// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html\n";
		$output .= "		var clientPC = navigator.userAgent.toLowerCase(); // Get client info\n";
		$output .= "		var clientVer = parseInt(navigator.appVersion); // Get browser version\n";
		
		$output .= "		var is_ie = ((clientPC.indexOf(\"msie\") != -1) && (clientPC.indexOf(\"opera\") == -1));\n";
		$output .= "		var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)\n";
		$output .= "		&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)\n";
		$output .= "		&& (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));\n";
		$output .= "		var is_moz = 0;\n";
		
		$output .= "		var is_win = ((clientPC.indexOf(\"win\")!=-1) || (clientPC.indexOf(\"16bit\") != -1));\n";
		$output .= "		var is_mac = (clientPC.indexOf(\"mac\")!=-1);\n";
		
		$output .= "		// Helpline messages\n";
		$output .= "		b_help = \""._POST_EDITOR_TAGS_HELP_BOLD."\";\n";
		$output .= "		i_help = \""._POST_EDITOR_TAGS_HELP_ITALIC."\";\n";
		$output .= "		u_help = \""._POST_EDITOR_TAGS_HELP_UNDERLINE."\";\n";
		$output .= "		q_help = \""._POST_EDITOR_TAGS_HELP_QUOTE."\";\n";
		$output .= "		c_help = \""._POST_EDITOR_TAGS_HELP_CODE."\";\n";
		$output .= "		l_help = \""._POST_EDITOR_TAGS_HELP_LIST."\";\n";
		$output .= "		o_help = \""._POST_EDITOR_TAGS_HELP_OLIST."\";\n";
		$output .= "		p_help = \""._POST_EDITOR_TAGS_HELP_IMAGE."\";\n";
		$output .= "		w_help = \""._POST_EDITOR_TAGS_HELP_URL."\";\n";
		$output .= "		a_help = \""._POST_EDITOR_TAGS_HELP_CLOSE_TAGS."\";\n";
		$output .= "		s_help = \""._POST_EDITOR_TAGS_HELP_FONT_COLOR."\";\n";
		$output .= "		f_help = \""._POST_EDITOR_TAGS_HELP_FONT_SIZE."\";\n";
		$output .= "		h_help = \""._POST_EDITOR_TAGS_HELP_HIDE."\";\n";
		$output .= "		up_help = \"\";\n";
		
		$output .= "		// Define the bbCode tags\n";
		$output .= "		bbcode = new Array();\n";
		$output .= "		bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list=]','[img]','[/img]','[url]','[/url]','[hide]','[/hide]');\n";
		$output .= "		imageTag = false;\n";
		
		$output .= "		// Shows the help messages in the helpline window\n";
		$output .= "		function helpline(help) {\n";
		$output .= "			document.".$this->form_name.".helpbox.value = eval(help + \"_help\");\n";
		$output .= "		}\n";
		
		$output .= "		// Replacement for arrayname.length property\n";
		$output .= "		function getarraysize(thearray) {\n";
		$output .= "			for (i = 0; i < thearray.length; i++) {\n";
		$output .= "				if ((thearray[i] == \"undefined\") || (thearray[i] == \"\") || (thearray[i] == null))\n";
		$output .= "					return i;\n";
		$output .= "				}\n";
		$output .= "			return thearray.length;\n";
		$output .= "		}\n";
		
		$output .= "		// Replacement for arrayname.push(value) not implemented in IE until version 5.5\n";
		$output .= "		// Appends element to the array\n";
		$output .= "		function arraypush(thearray,value) {\n";
		$output .= "			thearray[ getarraysize(thearray) ] = value;\n";
		$output .= "		}\n";
		
		$output .= "		// Replacement for arrayname.pop() not implemented in IE until version 5.5\n";
		$output .= "		// Removes and returns the last element of an array\n";
		$output .= "		function arraypop(thearray) {\n";
		$output .= "			thearraysize = getarraysize(thearray);\n";
		$output .= "			retval = thearray[thearraysize - 1];\n";
		$output .= "			delete thearray[thearraysize - 1];\n";
		$output .= "			return retval;\n";
		$output .= "		}\n";
		
		$output .= "		function checkForm() {\n";
		
		$output .= "			formErrors = false;\n";
		
		$output .= "			if (document.".$this->form_name.".".$this->editor_name.".value.length < 2) {\n";
		$output .= "				formErrors = \""._POST_EDITOR_ERR_TYPE_POST."\";\n";
		$output .= "			}\n";
		
		$output .= "			if (formErrors) {\n";
		$output .= "				alert(formErrors);\n";
		$output .= "				return false;\n";
		$output .= "			} else {\n";
		$output .= "				bbstyle(-1);\n";
		$output .= "				//formObj.preview.disabled = true;\n";
		$output .= "				//formObj.submit.disabled = true;\n";
		$output .= "				return true;\n";
		$output .= "			}\n";
		$output .= "		}\n";
		
		$output .= "		function emoticon(text) {\n";
		$output .= "			var txtarea = document.".$this->form_name.".".$this->editor_name.";\n";
		$output .= "			text = ' ' + text + ' ';\n";
		$output .= "			if (txtarea.createTextRange && txtarea.caretPos) {\n";
		$output .= "				var caretPos = txtarea.caretPos;\n";
		$output .= "				caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;\n";
		$output .= "				txtarea.focus();\n";
		$output .= "			} else {\n";
		$output .= "				txtarea.value+= text;\n";
		$output .= "				txtarea.focus();\n";
		$output .= "			}\n";
		$output .= "		}\n";
		
		$output .= "		function bbfontstyle(bbopen, bbclose) {\n";
		$output .= "			var txtarea = document.".$this->form_name.".".$this->editor_name.";\n";
		
		$output .= "			if ((clientVer >= 4) && is_ie && is_win) {\n";
		$output .= "				theSelection = document.selection.createRange().text;\n";
		$output .= "				if (!theSelection) {\n";
		$output .= "					txtarea.value += bbopen + bbclose;\n";
		$output .= "					txtarea.focus();\n";
		$output .= "					return;\n";
		$output .= "				}\n";
		$output .= "				document.selection.createRange().text = bbopen + theSelection + bbclose;\n";
		$output .= "				txtarea.focus();\n";
		$output .= "				return;\n";
		$output .= "			}\n";
		$output .= "			else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){\n";
		$output .= "				mozWrap(txtarea, bbopen, bbclose);\n";
		$output .= "				return;\n";
		$output .= "			} else {\n";
		$output .= "				txtarea.value += bbopen + bbclose;\n";
		$output .= "				txtarea.focus();\n";
		$output .= "			}\n";
		$output .= "			storeCaret(txtarea);\n";
		$output .= "		}\n";
		
		$output .= "		function bbstyle(bbnumber) {\n";
		$output .= "			var txtarea = document.".$this->form_name.".".$this->editor_name.";\n";
		
		$output .= "			txtarea.focus();\n";
		$output .= "		 	donotinsert = false;\n";
		$output .= "			theSelection = false;\n";
		$output .= "			bblast = 0;\n";
		
		$output .= "			if (bbnumber == -1) { // Close all open tags & default button names\n";
		$output .= "				while (bbcode[0]) {\n";
		$output .= "					butnumber = arraypop(bbcode) - 1;\n";
		$output .= "					txtarea.value += bbtags[butnumber + 1];\n";
		$output .= "					buttext = eval('document.".$this->form_name.".addbbcode' + butnumber + '.value');\n";
		$output .= "					eval('document.".$this->form_name.".addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');\n";
		$output .= "				}\n";
		$output .= "				imageTag = false; // All tags are closed including image tags :D\n";
		$output .= "				txtarea.focus();\n";
		$output .= "				return;\n";
		$output .= "			}\n";
		
		$output .= "			if ((clientVer >= 4) && is_ie && is_win){\n";
		$output .= "				theSelection = document.selection.createRange().text; // Get text selection\n";
		$output .= "				if (theSelection) {\n";
		$output .= "					// Add tags around selection\n";
		$output .= "					document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];\n";
		$output .= "					txtarea.focus();\n";
		$output .= "					theSelection = '';\n";
		$output .= "					return;\n";
		$output .= "				}\n";
		$output .= "			} else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))	{\n";
		$output .= "				mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);\n";
		$output .= "				return;\n";
		$output .= "			}\n";
		
		$output .= "			// Find last occurance of an open tag the same as the one just clicked\n";
		$output .= "			for (i = 0; i < bbcode.length; i++) {\n";
		$output .= "				if (bbcode[i] == bbnumber+1) {\n";
		$output .= "					bblast = i;\n";
		$output .= "					donotinsert = true;\n";
		$output .= "				}\n";
		$output .= "			}\n";
		
		$output .= "			if (donotinsert) {		// Close all open tags up to the one just clicked & default button names\n";
		$output .= "				while (bbcode[bblast]) {\n";
		$output .= "						butnumber = arraypop(bbcode) - 1;\n";
		$output .= "						txtarea.value += bbtags[butnumber + 1];\n";
		$output .= "						buttext = eval('document.".$this->form_name.".addbbcode' + butnumber + '.value');\n";
		$output .= "						eval('document.".$this->form_name.".addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');\n";
		$output .= "						imageTag = false;\n";
		$output .= "					}\n";
		$output .= "					txtarea.focus();\n";
		$output .= "					return;\n";
		$output .= "			} else { // Open tags\n";
		
		$output .= "				if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another\n";
		$output .= "					txtarea.value += bbtags[15];\n";
		$output .= "					lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list\n";
		$output .= "					document.".$this->form_name.".addbbcode14.value = \"Img\";	// Return button back to normal state\n";
		$output .= "					imageTag = false;\n";
		$output .= "				}\n";
		
		$output .= "				// Open tag\n";
		$output .= "				txtarea.value += bbtags[bbnumber];\n";
		$output .= "				if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag\n";
		$output .= "				arraypush(bbcode,bbnumber+1);\n";
		$output .= "				eval('document.".$this->form_name.".addbbcode'+bbnumber+'.value += \"*\"');\n";
		$output .= "				txtarea.focus();\n";
		$output .= "				return;\n";
		$output .= "			}\n";
		$output .= "			storeCaret(txtarea);\n";
		$output .= "		}\n";
		
		$output .= "		// From http://www.massless.org/mozedit/\n";
		$output .= "		function mozWrap(txtarea, open, close){\n";
		$output .= "			var selLength = txtarea.textLength;\n";
		$output .= "			var selStart = txtarea.selectionStart;\n";
		$output .= "			var selEnd = txtarea.selectionEnd;\n";
		$output .= "			if (selEnd == 1 || selEnd == 2)\n";
		$output .= "				selEnd = selLength;\n";
		
		$output .= "			var s1 = (txtarea.value).substring(0,selStart);\n";
		$output .= "			var s2 = (txtarea.value).substring(selStart, selEnd)\n";
		$output .= "			var s3 = (txtarea.value).substring(selEnd, selLength);\n";
		$output .= "			txtarea.value = s1 + open + s2 + close + s3;\n";
		$output .= "			return;\n";
		$output .= "		}\n";
		
		$output .= "		// Insert at Claret position. Code from\n";
		$output .= "		// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130\n";
		$output .= "		function storeCaret(textEl) {\n";
		$output .= "			if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();\n";
		$output .= "		}\n";
		
		$output .= "		//-->\n";
		$output .= "		</SCRIPT>\n";
		$output .= "		<table cellspacing=\"0\" cellpadding=\"2\" width=\"".$this->table_width."\" border=\"0\">\n";
		$output .= "			<tr>\n";
		$output .= "				<td><input class=\"button\" onmouseover=\"helpline('b')\" style=\"font-weight: bold; width: 30px\" accessKey=\"b\" onclick=\"bbstyle(0)\" type=\"button\" value=\"B\" name=\"addbbcode0\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('i')\" style=\"width: 30px; font-style: italic\" accessKey=\"i\" onclick=\"bbstyle(2)\" type=\"button\" value=\"i\" name=\"addbbcode2\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('u')\" style=\"width: 30px; text-decoration: underline\" accessKey=\"u\" onclick=\"bbstyle(4)\" type=\"button\" value=\"u\" name=\"addbbcode4\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('q')\" style=\"width: 50px\" accessKey=\"q\" onclick=\"bbstyle(6)\" type=\"button\" value=\"Quote\" name=\"addbbcode6\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('c')\" style=\"width: 40px\" accessKey=\"c\" onclick=\"bbstyle(8)\" type=button value=\"Code\" name=\"addbbcode8\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('l')\" style=\"width: 40px\" accessKey=\"l\" onclick=\"bbstyle(10)\" type=\"button\" value=\"List\" name=\"addbbcode10\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('o')\" style=\"width: 40px\" accessKey=\"o\" onclick=\"bbstyle(12)\" type=\"button\" value=\"List=\" name=\"addbbcode12\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('p')\" style=\"width: 40px\" accessKey=\"p\" onclick=\"bbstyle(14)\" type=\"button\" value=\"Img\" name=\"addbbcode14\"> \n";
		$output .= "				<input class=\"button\" onmouseover=\"helpline('w')\" style=\"width: 40px\" accessKey=\"w\" onclick=\"bbstyle(16)\" type=\"button\" value=\"URL\" name=\"addbbcode16\"> </td>\n";
		$output .= "			</tr>\n";
		$output .= "			<tr>\n";
		$output .= "				<td>\n";
		$output .= "					<table cellspacing=\"0\" cellpadding=\"0\" width=\"".$this->table_width."\" border=\"0\">\n";
		$output .= "						<tr>\n";
		$output .= "							<td noWrap>&nbsp;"._POST_EDITOR_FONT_COLOR."\n";
		$output .= "								<select onmouseover=\"helpline('s')\" onchange=\"bbfontstyle('[color=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\" name=\"addbbcode20\">\n";
		$output .= "									<option value=\"#\" selected>"._POST_EDITOR_FONT_COLOR_BASIC."</option>\n";
		$output .= "									<option style=\"color: darkred; background-color: transparent\" value=\"darkred\">"._POST_EDITOR_FONT_COLOR_DARKRED."</option>\n";
		$output .= "									<option style=\"color: red; background-color: transparent\" value=\"red\">"._POST_EDITOR_FONT_COLOR_RED."</option>\n";
		$output .= "									<option style=\"color: orange; background-color: transparent\" value=\"orange\">"._POST_EDITOR_FONT_COLOR_ORANGE."</option>\n";
		$output .= "									<option style=\"color: brown; background-color: transparent\" value=\"brown\">"._POST_EDITOR_FONT_COLOR_BROWN."</option>\n";
		$output .= "									<option style=\"color: yellow; background-color: transparent\" value=\"yellow\">"._POST_EDITOR_FONT_COLOR_YELLOW."</option>\n";
		$output .= "									<option style=\"color: green; background-color: transparent\" value=\"green\">"._POST_EDITOR_FONT_COLOR_GREEN."</option>\n";
		$output .= "									<option style=\"color: olive; background-color: transparent\" value=\"olive\">"._POST_EDITOR_FONT_COLOR_OLIVE."</option>\n";
		$output .= "									<option style=\"color: cyan; background-color: transparent\" value=\"cyan\">"._POST_EDITOR_FONT_COLOR_CYAN."</option>\n";
		$output .= "									<option style=\"color: blue; background-color: transparent\" value=\"blue\">"._POST_EDITOR_FONT_COLOR_BLUE."</option>\n";
		$output .= "									<option style=\"color: darkblue; background-color: transparent\" value=\"darkblue\">"._POST_EDITOR_FONT_COLOR_DARKBLUE."</option>\n";
		$output .= "									<option style=\"color: indigo; background-color: transparent\" value=\"indigo\">"._POST_EDITOR_FONT_COLOR_INDIGO."</option>\n";
		$output .= "									<option style=\"color: violet; background-color: transparent\" value=\"violet\">"._POST_EDITOR_FONT_COLOR_VIOLET."</option>\n";
		$output .= "									<option style=\"color: white; background-color: transparent\" value=\"white\">"._POST_EDITOR_FONT_COLOR_WHITE."</option>\n";
		$output .= "									<option style=\"color: black; background-color: transparent\" value=\"black\">"._POST_EDITOR_FONT_COLOR_BLACK."</option>\n";
		$output .= "								</select>\n";
		$output .= "								&nbsp;"._POST_EDITOR_FONT_SIZE."\n";
		$output .= "								<select onmouseover=\"helpline('f')\" onchange=\"bbfontstyle('[size=' + this.form.addbbcode22.options[this.form.addbbcode22.selectedIndex].value + ']', '[/size]')\" name=\"addbbcode22\">\n";
		$output .= "									<option value=\"7\">"._POST_EDITOR_FONT_SIZE_SMALLEST."</option>\n";
		$output .= "									<option value=\"9\">"._POST_EDITOR_FONT_SIZE_SMALL."</option>\n";
		$output .= "									<option value=\"12\" selected>"._POST_EDITOR_FONT_SIZE_BASIC."</option>\n";
		$output .= "									<option value=\"18\">"._POST_EDITOR_FONT_SIZE_BIG."</option>\n";
		$output .= "									<option value=\"24\">"._POST_EDITOR_FONT_SIZE_BIGGEST."</option>\n";
		$output .= "								</select>\n";
		$output .= "							</td>\n";
		$output .= "						</tr>\n";
		$output .= "						<tr>\n";
		$output .= "							<td> <a onmouseover=\"helpline('a')\" href=\"javascript:bbstyle(-1)\">"._POST_EDITOR_CLOSE_TAGS."</a>&nbsp;</td>\n";
		$output .= "						</tr>\n";
		$output .= "					</table>\n";
		$output .= "				</td>\n";
		$output .= "			</tr>\n";
		$output .= "			<tr>\n";
		$output .= "				<td>\n";
		$output .= "					<input class=\"forum-helpline\" style=\"font-size: 10px; width:".$this->textarea_width."\" size=\"70\" maxlength=\"100\" value=\""._POST_EDITOR_TAGS_HELP_TIP."\" name=\"helpbox\">\n";
		$output .= "				</td>\n";
		$output .= "			</tr>\n";
		$output .= "			<tr>\n";
		$output .= "				<td>\n";
		$output .= "					<textarea class=\"post\" onkeyup=\"storeCaret(this);\" style=\"width:".$this->textarea_width."\" onclick=\"storeCaret(this);\" tabindex=\"3\" wrap=\"virtual\" name=\"".$this->editor_name."\" rows=\"".$this->textarea_rows."\" cols=\"".$this->textarea_cols."\" onselect=\"storeCaret(this);\">".$this->form_text."</textarea>\n";
		$output .= "				</td>\n";
		$output .= "			</tr>\n";
		$output .= "		</table>";
		
		return $output;
	}
}