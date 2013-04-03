/***********************************************
*
*		CALENDAR
*
***********************************************/
function forOthers(e) {
	if (document.getElementById('hint').style.visibility == "visible") {
		hor = window.pageXOffset + window.innerWidth - document.getElementById('hint').offsetWidth - 20;
		ver = window.pageYOffset + window.innerHeight - document.getElementById('hint').offsetHeight - 20;
		posHor = window.pageXOffset + e.clientX + 10;
		posVer = window.pageYOffset + e.clientY + 10;
		posHor2 = window.pageXOffset + e.clientX - document.getElementById('hint').offsetWidth - 5;
		posVer2 = window.pageYOffset + e.clientY - document.getElementById('hint').offsetHeight - 5;
		if (posVer<ver){
			document.getElementById('hint').style.top = posVer
		} else {
			document.getElementById('hint').style.top = posVer2;
		}
		if (posHor<hor){
			document.getElementById('hint').style.left = posHor
		} else {
			document.getElementById('hint').style.left = posHor2
		}
	}
}
function ShowHint(x,y,s,e,n,w,z){
	if (z == 1){
		if (x == ''){x = "Bohužel nic";}
		temp = '<table border="1" cellspacing="0" cellpadding="5" width="250" bordercolordark="#000000" bordercolor="#000000" bordercolorlight="#000000"><tr class="hlavicka_sloupcu"><td align="center" height="20" width="250">' + y + '&nbsp;<\/td><\/tr><tr bgcolor="#F5F4ED"><td class="cal_text" valign="top" height="200" width="250">' + s + ' - ' + e + '&nbsp;&nbsp;&nbsp;' + n + '<br>' + x + '<\/td><\/tr><\/table>';
		document.getElementById('hint').innerHTML = temp;
		document.getElementById('hint').style.width = w;
		document.getElementById('hint').style.visibility = "visible";
	}
	if (z == 0){
		document.getElementById('hint').style.visibility = "hidden";
	}
}

function getMouse(e,a){
	var x;
	var y;
	if (navigator.appName=="Microsoft Internet Explorer" || navigator.appName=="Opera"){
		if (a == "right"){
			x = window.event.x+9;
		}
		if (a == "left"){
			x = window.event.x-250;
		}
		y = window.event.y+20;
	}
	if (navigator.appName=="Netscape"){
		if (a == "right"){
			x = e.screenX+11;
		}
		if (a == "left"){
			x = e.screenX-250;
		}
		y = e.screenY-90;
	}
	x =  x + document.documentElement.scrollLeft;
	y =  y + document.documentElement.scrollTop;
	x = x.toString() + "px"
	y = y.toString() + "px"
	document.getElementById('hint').style.left = x;
	document.getElementById('hint').style.top  = y;
}


/* Simple AJAX Code-Kit (SACK) v1.6.1 */
/* ©2005 Gregory Wild-Smith */
/* www.twilightuniverse.com */
/* Software licenced under a modified X11 licence,
   see documentation or authors website for more details */

function sack(file) {
	this.xmlhttp = null;

	this.resetData = function() {
		this.method = "POST";
  		this.queryStringSeparator = "?";
		this.argumentSeparator = "&";
		this.URLString = "";
		this.encodeURIString = true;
  		this.execute = false;
  		this.element = null;
		this.elementObj = null;
		this.requestFile = file;
		this.vars = new Object();
		this.responseStatus = new Array(2);
  	};

	this.resetFunctions = function() {
  		this.onLoading = function() { };
  		this.onLoaded = function() { };
  		this.onInteractive = function() { };
  		this.onCompletion = function() { };
  		this.onError = function() { };
		this.onFail = function() { };
	};

	this.reset = function() {
		this.resetFunctions();
		this.resetData();
	};

	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e1) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				this.xmlhttp = null;
			}
		}

		if (! this.xmlhttp) {
			if (typeof XMLHttpRequest != "undefined") {
				this.xmlhttp = new XMLHttpRequest();
			} else {
				this.failed = true;
			}
		}
	};

	this.setVar = function(name, value){
		this.vars[name] = Array(value, false);
	};

	this.encVar = function(name, value, returnvars) {
		if (true == returnvars) {
			return Array(encodeURIComponent(name), encodeURIComponent(value));
		} else {
			this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
		}
	}

	this.processURLString = function(string, encode) {
		encoded = encodeURIComponent(this.argumentSeparator);
		regexp = new RegExp(this.argumentSeparator + "|" + encoded);
		varArray = string.split(regexp);
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split("=");
			if (true == encode){
				this.encVar(urlVars[0], urlVars[1]);
			} else {
				this.setVar(urlVars[0], urlVars[1]);
			}
		}
	}

	this.createURLString = function(urlstring) {
		if (this.encodeURIString && this.URLString.length) {
			this.processURLString(this.URLString, true);
		}

		if (urlstring) {
			if (this.URLString.length) {
				this.URLString += this.argumentSeparator + urlstring;
			} else {
				this.URLString = urlstring;
			}
		}

		// prevents caching of URLString
		this.setVar("rndval", new Date().getTime());

		urlstringtemp = new Array();
		for (key in this.vars) {
			if (false == this.vars[key][1] && true == this.encodeURIString) {
				encoded = this.encVar(key, this.vars[key][0], true);
				delete this.vars[key];
				this.vars[encoded[0]] = Array(encoded[1], true);
				key = encoded[0];
			}

			urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key][0];
		}
		if (urlstring){
			this.URLString += this.argumentSeparator + urlstringtemp.join(this.argumentSeparator);
		} else {
			this.URLString += urlstringtemp.join(this.argumentSeparator);
		}
	}

	this.runResponse = function() {
		eval(this.response);
	}

	this.runAJAX = function(urlstring) {
		if (this.failed) {
			this.onFail();
		} else {
			this.createURLString(urlstring);
			if (this.element) {
				this.elementObj = document.getElementById(this.element);
			}
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					totalurlstring = this.requestFile + this.queryStringSeparator + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
					try {
						this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
					} catch (e) { }
				}

				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState) {
						case 1:
							self.onLoading();
							break;
						case 2:
							self.onLoaded();
							break;
						case 3:
							self.onInteractive();
							break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;

							if (self.execute) {
								self.runResponse();
							}

							if (self.elementObj) {
								elemNodeName = self.elementObj.nodeName;
								elemNodeName.toLowerCase();
								if (elemNodeName == "input"
								|| elemNodeName == "select"
								|| elemNodeName == "option"
								|| elemNodeName == "textarea") {
									self.elementObj.value = self.response;
								} else {
									self.elementObj.innerHTML = self.response;
								}
							}
							if (self.responseStatus[0] == "200") {
								self.onCompletion();
							} else {
								self.onError();
							}

							self.URLString = "";
							break;
					}
				};

				this.xmlhttp.send(this.URLString);
			}
		}
	};

	this.reset();
	this.createAJAX();
}


/***********************************************
* Show Hint script- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

var horizontal_offset="9px" //horizontal offset of hint box from anchor link

/////No further editting needed

var vertical_offset="0" //horizontal offset of hint box from anchor link. No need to change.
var ie=document.all
var ns6=document.getElementById&&!document.all

function getposOffset(what, offsettype){
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null){
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}

function iecompattest(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
	var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
	if (whichedge=="rightedge"){
		var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
		dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
		if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
			edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
	} else {
		var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
		dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
		if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
			edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
	}
	return edgeoffset
}

function ShowHintText(menucontents, obj, e, tipwidth){
	if ((ie||ns6) && document.getElementById("eden_hintbox")){
		dropmenuobj=document.getElementById("eden_hintbox")
		dropmenuobj.innerHTML=menucontents
		dropmenuobj.style.left=dropmenuobj.style.top=-500
		if (tipwidth!=""){
			dropmenuobj.widthobj=dropmenuobj.style
			dropmenuobj.widthobj.width=tipwidth
		}
		dropmenuobj.x=getposOffset(obj, "left")
		dropmenuobj.y=getposOffset(obj, "top")
		dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
		dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
		dropmenuobj.style.visibility="visible"
		obj.onmouseout=hidetip
	}
}

function hidetip(e){
	dropmenuobj.style.visibility="hidden"
	dropmenuobj.style.left="-500px"
}

function createhintbox(){
	var divblock=document.createElement("div")
	divblock.setAttribute("id", "eden_hintbox")
	document.body.appendChild(divblock)
}

if (window.addEventListener){
	window.addEventListener("load", createhintbox, false)
} else if (window.attachEvent) {
	window.attachEvent("onload", createhintbox)
} else if (document.getElementById) {
	window.onload=createhintbox
}

/***********************************************
*	s3Capcha
*	
*	Developped By: Boban Karišik -> http://www.serie3.info/
*	Icons and css: Mészáros Róbert -> http://www.perspectived.com/
*	Version: 1.0
*	
*	Copyright: Feel free to redistribute the script/modify it, as
*			   long as you leave my infos at the top.
/**********************************************/

(function($){

    jQuery.fn.extend({
        check: function() {
            return this.each(function() { this.checked = true; });
        },
        uncheck: function() {
            return this.each(function() { this.checked = false; });
        }
    });
    
    
    $.fn.s3Capcha = function(vars) {       
        var element     = this;
        var spans       = $("#" + element[0].id + " div span");
        var radios      = $("#" + element[0].id + " div span input");
        var images      = $("#" + element[0].id + " div .img");
        // hide radios //
        spans.css({'display':'none'});
        // show images //
        images.css({'display':'block'});
        
        images.each(function(i) {
            $(images[i]).click(function() {
               images.css({'background-position':'bottom left'});
               $(images[i]).css({'background-position':'top left'});
               $(radios[i]).check();
            });
        });
        
    }

})(jQuery);


/***********************************************
*
*	EDEN AJAX HINTBOX
*
/**********************************************/
$(function(){
  var hideDelay = 500;  
  var currentID;
  var hideTimer = null;

  // One instance that's reused to show info for the current person
  var container = $('<div id="eden_hintbox_container">'
      + '<table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="eden_hintbox_popup">'
      + '<tr>'
      + '   <td class="corner top_left"></td>'
      + '   <td class="top"></td>'
      + '   <td class="corner top_right"></td>'
      + '</tr>'
      + '<tr>'
      + '   <td class="left">&nbsp;</td>'
      + '   <td><div id="eden_hintbox_content"></div></td>'
      + '   <td class="right">&nbsp;</td>'
      + '</tr>'
      + '<tr>'
      + '   <td class="corner bottom_left">&nbsp;</td>'
      + '   <td class="bottom">&nbsp;</td>'
      + '   <td class="corner bottom_right"></td>'
      + '</tr>'
      + '</table>'
      + '</div>');

  $('body').append(container);

  $('.eden_hintbox_trigger').live('mouseover', function(){
      // format of 'rel' tag: pageid,personguid
	  var settings = $(this).attr('rel').split(',');
      var currentProject = settings[0]; // Project for connecting right DB
      var currentLang = settings[1]; // Lang for any messages
      var currentMode = settings[2]; // Mode allow to use this ajax hintbox to be used for various purpose
      currentID = settings[3];

      // If no guid in url rel tag, don't popup blank
      if (currentID == '')
          return;

      if (hideTimer)
          clearTimeout(hideTimer);

      var pos = $(this).offset();
      var width = $(this).width();
      container.css({
          left: (pos.left + width) + 'px',
          top: pos.top - 5 + 'px'
      });

      $('#eden_hintbox_content').html('&nbsp;');

      $.ajax({
          type: 'GET',
          url: './edencms/eden_ajax.php',
          data: 'id=' + currentID + '&mode=' + currentMode + '&lang=' + currentLang + '&project=' + currentProject,
          success: function(data)
          {
              // Verify that we're pointed to a page that returned the expected results.
              if (data.indexOf('eden_hintbox_result') < 0)
              {
                  $('#eden_hintbox_content').html('<span><?php echo _ERR_HINTBOX_ERROR;?></span>');
              }

              // Verify requested person is this person since we could have multiple ajax
              // requests out if the server is taking a while.
              if (data.indexOf(currentID) > 0)
              {                  
                  var text = $(data).find('.result').html();
                  $('#eden_hintbox_content').html(text);
              }
          }
      });

      container.css('display', 'block');
  });

  $('.eden_hintbox_trigger').live('mouseout', function(){
      if (hideTimer)
          clearTimeout(hideTimer);
      hideTimer = setTimeout(function()
      {
          container.css('display', 'none');
      }, hideDelay);
  });

  // Allow mouse over of details without hiding details
  $('#eden_hintbox_container').mouseover(function(){
      if (hideTimer)
          clearTimeout(hideTimer);
  });

  // Hide after mouseout
  $('#eden_hintbox_container').mouseout(function(){
      if (hideTimer)
          clearTimeout(hideTimer);
      hideTimer = setTimeout(function()
      {
          container.css('display', 'none');
      }, hideDelay);
  });
});

/************************************************************************************************************
(C) www.dhtmlgoodies.com, April 2006

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

************************************************************************************************************/	

var ajaxBox_offsetX = 0;
var ajaxBox_offsetY = 0;
var ajax_list_externalFile = './edencms/eden_ajax.php';	// Path to external file
var minimumLettersBeforeLookup = 1;	// Number of letters entered before a lookup is performed.

var ajax_list_objects = new Array();
var ajax_list_cachedLists = new Array();
var ajax_list_activeInput = false;
var ajax_list_activeItem;
var ajax_list_optionDivFirstItem = false;
var ajax_list_currentLetters = new Array();
var ajax_optionDiv = false;
var ajax_optionDiv_iframe = false;

var ajax_list_MSIE = false;
if(navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('Opera')<0)ajax_list_MSIE=true;

var currentListIndex = 0;

function ajax_getTopPos(inputObj){
  var returnValue = inputObj.offsetTop;
  while((inputObj = inputObj.offsetParent) != null){
  	returnValue += inputObj.offsetTop;
  }
  return returnValue;
}

function ajax_list_cancelEvent(){
	return false;
}

function ajax_getLeftPos(inputObj){
  var returnValue = inputObj.offsetLeft;
  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
  
  return returnValue;
}

function ajax_option_setValue(e,inputObj){
	if(!inputObj)inputObj=this;
	var tmpValue = inputObj.innerHTML;
	if(ajax_list_MSIE)tmpValue = inputObj.innerText;else tmpValue = inputObj.textContent;
	if(!tmpValue)tmpValue = inputObj.innerHTML;
	ajax_list_activeInput.value = tmpValue;
	if(document.getElementById(ajax_list_activeInput.name + '_hidden'))document.getElementById(ajax_list_activeInput.name + '_hidden').value = inputObj.id; 
	ajax_options_hide();
}

function ajax_options_hide(){
	if(ajax_optionDiv)ajax_optionDiv.style.display='none';	
	if(ajax_optionDiv_iframe)ajax_optionDiv_iframe.style.display='none';
}

function ajax_options_rollOverActiveItem(item,fromKeyBoard){
	if(ajax_list_activeItem)ajax_list_activeItem.className='eden_optionDiv';
	item.className='eden_optionDivSelected';
	ajax_list_activeItem = item;
	
	if(fromKeyBoard){
		if(ajax_list_activeItem.offsetTop>ajax_optionDiv.offsetHeight){
			ajax_optionDiv.scrollTop = ajax_list_activeItem.offsetTop - ajax_optionDiv.offsetHeight + ajax_list_activeItem.offsetHeight + 2 ;
		}
		if(ajax_list_activeItem.offsetTop<ajax_optionDiv.scrollTop)
		{
			ajax_optionDiv.scrollTop = 0;	
		}
	}
}

function ajax_option_list_buildList(letters,paramToExternalFile){
	ajax_optionDiv.innerHTML = '';
	ajax_list_activeItem = false;
	if(ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()].length<=1){
		ajax_options_hide();
		return;			
	}

	ajax_list_optionDivFirstItem = false;
	var optionsAdded = false;
	for(var no=0;no<ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()].length;no++){
		if(ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()][no].length==0)continue;
		optionsAdded = true;
		var div = document.createElement('DIV');
		var items = ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()][no].split(/###/gi);
		
		if(ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()].length==1 && ajax_list_activeInput.value == items[0]){
			ajax_options_hide();
			return;						
		}
		
		div.innerHTML = items[items.length-1];
		div.id = items[0];
		div.className='eden_optionDiv';
		div.onmouseover = function(){ ajax_options_rollOverActiveItem(this,false) }
		div.onclick = ajax_option_setValue;
		if(!ajax_list_optionDivFirstItem)ajax_list_optionDivFirstItem = div;
		ajax_optionDiv.appendChild(div);
	}	
	if(optionsAdded){
		ajax_optionDiv.style.display='block';
		if(ajax_optionDiv_iframe)ajax_optionDiv_iframe.style.display='';
		ajax_options_rollOverActiveItem(ajax_list_optionDivFirstItem,true);
	}
}

function ajax_option_list_showContent(ajaxIndex,inputObj,paramToExternalFile,whichIndex){
	if(whichIndex!=currentListIndex)return;
	var letters = inputObj.value;
	var content = ajax_list_objects[ajaxIndex].response;
	var elements = content.split('|');
	ajax_list_cachedLists[paramToExternalFile][letters.toLowerCase()] = elements;
	ajax_option_list_buildList(letters,paramToExternalFile);
}

function ajax_option_resize(inputObj){
	ajax_optionDiv.style.top = (ajax_getTopPos(inputObj) + inputObj.offsetHeight + ajaxBox_offsetY) + 'px';
	ajax_optionDiv.style.left = (ajax_getLeftPos(inputObj) + ajaxBox_offsetX) + 'px';
	if(ajax_optionDiv_iframe){
		ajax_optionDiv_iframe.style.left = ajax_optionDiv.style.left;
		ajax_optionDiv_iframe.style.top = ajax_optionDiv.style.top;			
	}		
}

function ajax_showOptions(inputObj,paramToExternalFile,e){
	if(e.keyCode==13 || e.keyCode==9)return;
	if(ajax_list_currentLetters[inputObj.name]==inputObj.value)return;
	if(!ajax_list_cachedLists[paramToExternalFile])ajax_list_cachedLists[paramToExternalFile] = new Array();
	ajax_list_currentLetters[inputObj.name] = inputObj.value;
	if(!ajax_optionDiv){
		ajax_optionDiv = document.createElement('DIV');
		ajax_optionDiv.id = 'eden_ajax_listOfOptions';	
		document.body.appendChild(ajax_optionDiv);
		
		if(ajax_list_MSIE){
			ajax_optionDiv_iframe = document.createElement('IFRAME');
			ajax_optionDiv_iframe.border='0';
			ajax_optionDiv_iframe.style.width = ajax_optionDiv.clientWidth + 'px';
			ajax_optionDiv_iframe.style.height = ajax_optionDiv.clientHeight + 'px';
			ajax_optionDiv_iframe.id = 'eden_ajax_listOfOptions_iframe';
			
			document.body.appendChild(ajax_optionDiv_iframe);
		}
		
		var allInputs = document.getElementsByTagName('INPUT');
		for(var no=0;no<allInputs.length;no++){
			if(!allInputs[no].onkeyup)allInputs[no].onfocus = ajax_options_hide;
		}			
		var allSelects = document.getElementsByTagName('SELECT');
		for(var no=0;no<allSelects.length;no++){
			allSelects[no].onfocus = ajax_options_hide;
		}

		var oldonkeydown=document.body.onkeydown;
		if(typeof oldonkeydown!='function'){
			document.body.onkeydown=ajax_option_keyNavigation;
		}else{
			document.body.onkeydown=function(){
				oldonkeydown();
			ajax_option_keyNavigation() ;}
		}
		var oldonresize=document.body.onresize;
		if(typeof oldonresize!='function'){
			document.body.onresize=function() {ajax_option_resize(inputObj); };
		}else{
			document.body.onresize=function(){oldonresize();
			ajax_option_resize(inputObj) ;}
		}
	}
	
	if(inputObj.value.length<minimumLettersBeforeLookup){
		ajax_options_hide();
		return;
	}
			

	ajax_optionDiv.style.top = (ajax_getTopPos(inputObj) + inputObj.offsetHeight + ajaxBox_offsetY) + 'px';
	ajax_optionDiv.style.left = (ajax_getLeftPos(inputObj) + ajaxBox_offsetX) + 'px';
	if(ajax_optionDiv_iframe){
		ajax_optionDiv_iframe.style.left = ajax_optionDiv.style.left;
		ajax_optionDiv_iframe.style.top = ajax_optionDiv.style.top;			
	}
	
	ajax_list_activeInput = inputObj;
	ajax_optionDiv.onselectstart =  ajax_list_cancelEvent;
	currentListIndex++;
	if(ajax_list_cachedLists[paramToExternalFile][inputObj.value.toLowerCase()]){
		ajax_option_list_buildList(inputObj.value,paramToExternalFile,currentListIndex);			
	} else {
		var tmpIndex=currentListIndex/1;
		ajax_optionDiv.innerHTML = '';
		var ajaxIndex = ajax_list_objects.length;
		ajax_list_objects[ajaxIndex] = new sack();
		var url = ajax_list_externalFile + '?' + paramToExternalFile + '&letters=' + inputObj.value.replace(" ","+");
		ajax_list_objects[ajaxIndex].requestFile = url;	// Specifying which file to get
		ajax_list_objects[ajaxIndex].onCompletion = function(){ ajax_option_list_showContent(ajaxIndex,inputObj,paramToExternalFile,tmpIndex); };	// Specify function that will be executed after file has been found
		ajax_list_objects[ajaxIndex].runAJAX();		// Execute AJAX function		
	}
}

function ajax_option_keyNavigation(e){
	if(document.all)e = event;
	if(!ajax_optionDiv)return;
	if(ajax_optionDiv.style.display=='none')return;
	
	if(e.keyCode==38){	// Up arrow
		if(!ajax_list_activeItem)return;
		if(ajax_list_activeItem && !ajax_list_activeItem.previousSibling)return;
		ajax_options_rollOverActiveItem(ajax_list_activeItem.previousSibling,true);
	}
	
	if(e.keyCode==40){	// Down arrow
		if(!ajax_list_activeItem){
			ajax_options_rollOverActiveItem(ajax_list_optionDivFirstItem,true);
		}else{
			if(!ajax_list_activeItem.nextSibling)return;
			ajax_options_rollOverActiveItem(ajax_list_activeItem.nextSibling,true);
		}
	}
	
	if(e.keyCode==13 || e.keyCode==9){	// Enter key or tab key
		if(ajax_list_activeItem && ajax_list_activeItem.className=='eden_optionDivSelected')ajax_option_setValue(false,ajax_list_activeItem);
		if(e.keyCode==13)return false; else return true;
	}
	if(e.keyCode==27){	// Escape key
		ajax_options_hide();			
	}
}


document.documentElement.onclick = autoHideList;

function autoHideList(e){
	if(document.all)e = event;
	if (e.target) source = e.target;
		else if (e.srcElement) source = e.srcElement;
		if (source.nodeType == 3) // defeat Safari bug
			source = source.parentNode;		
	if(source.tagName.toLowerCase()!='input' && source.tagName.toLowerCase()!='textarea')ajax_options_hide();
}