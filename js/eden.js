function MM_swapimgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.osrc;i++) x.src=x.osrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if (d.images){ if (!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if (!d) d=document; if ((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if (!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if (!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if (!x.osrc) x.osrc=x.src; x.src=a[i+2];}
}
function kontrola(formular)
{
	re = new RegExp("^[^@\.]([\.]?[^@\.]+)*@([^@\.]+[\.]{1}[^@\.]+)+$");
	if (formular.jmeno.value=="")
	{
		alert ("Napište prosím své jméno.");
		return false;
	}
	else if (!re.test(formular.email.value))
	{
		alert ("Vaše emailová adresa je neplatná ! \n\ Zadejte ji, prosím, správně.");
		return false;
	}
	else
	return true;
}

function kontrola2(formular)
{
	re = new RegExp("^[^@\.]([\.]?[^@\.]+)*@([^@\.]+[\.]{1}[^@\.]+)+$");
	if (formular.nick.value=="")
	{
		alert ("Napište prosím svůj nick.");
		return false;
	}
	else if (formular.jmeno.value=="")
	{
		alert ("Napište prosím své jméno.");
		return false;
	}
	else if (formular.prijmeni.value=="")
	{
		alert ("Napište prosím své příjmení.");
		return false;
	}
	else if (formular.icq.value=="")
	{
		alert ("Napište prosím své ICQ.");
		return false;
	}
	else if (!re.test(formular.email.value))
	{
		alert ("Vaše emailová adresa je neplatná ! \n\ Zadejte ji, prosím, správne.");
		return false;
	}
	else if (formular.pass.value !== formular.pass2.value)
	{
		alert ("Hesla si neodpovídají");
		return false;
	}
	else
	return true;
}

function Go(x) {
	if (x == "nothing") {
		document.forms[0].reset();
		document.forms[0].elements[0].blur();
		return;
	} else {
		parent.location.href = x;
	}
}
// EXPAND MENU - START
 function showHide(elementID) {
    var desc = null;

    if (document.getElementById) {
      desc = document.getElementById("cnt_desc_" + elementID);
    } else if (document.all) {
      desc = document.all["cnt_desc_" + elementID];
    } else if (document.layers) {
      desc = document.layers["cnt_desc_" + elementID];
    }

    if (desc) {
      if (desc.style.display == 'none') {
        expand(elementID);
      } else {
        collapse(elementID);
      }
    }
  }

  function expand(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display == 'none') {
      cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'block';
      icon.src = "images/sys_icon_minus.gif"
    }
  }

  function collapse(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display != 'none') {
      cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'none';
      icon.src = "images/sys_icon_plus.gif"
    }
  }

  function expandAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            expand(cnt[i].id.substring(4));
          }
        }
      }
    }
  }

  function collapseAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            collapse(cnt[i].id.substring(4));
          }
        }
      }
    }
  }
// EXPAND MENU - END

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

if (window.addEventListener)
window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
window.attachEvent("onload", createhintbox)
else if (document.getElementById)
window.onload=createhintbox

//**************************************************
// jQuery
//**************************************************
$(document).ready(function(){
	
	//*** modul_articles.php - SEARCH ***
	
	// do your checks of the radio buttons here and show/hide what you want to
	function searchHideAll(){
		$("#search_id").hide();
		$("#search_c").hide();
		$("#search_date").hide();
	}
	
	// Hide all form imputs
	searchHideAll();
	
	if ($("#Category").is(':checked') === true){
		searchHideAll();
		$("#Category").attr('checked', true);
		$("#search_c").show(); 
	}
	
	if ($("#Date").is(':checked') === true){
		searchHideAll();
		$("#Date").attr('checked', true);
		$("#search_c").show(); 
		$("#search_date").show(); 
	}
	if ($("#ID").is(':checked') === true){
		searchHideAll();
		$("#ID").attr('checked', true);
		$("#search_id").show(); 
	}

	// add functionality for the onclicks here
	$("#Category").click(function() {
		searchHideAll();
		$("#search_c").show();
	});

	$("#Date").click(function() {
		searchHideAll();
		$("#search_c").show();
		$("#search_date").show();
	});
	
	$("#ID").click(function() {
		searchHideAll();
		$("#search_id").show();
	});
	
	
});