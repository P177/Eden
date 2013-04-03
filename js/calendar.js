/*ř*/
function forOthers(e)
{
 if (document.getElementById('hint').style.visibility == "visible")
 {
  hor = window.pageXOffset + window.innerWidth - document.getElementById('hint').offsetWidth - 20;
  ver = window.pageYOffset + window.innerHeight - document.getElementById('hint').offsetHeight - 20;
  posHor = window.pageXOffset + e.clientX + 10;
  posVer = window.pageYOffset + e.clientY + 10;
  posHor2 = window.pageXOffset + e.clientX - document.getElementById('hint').offsetWidth - 5;
  posVer2 = window.pageYOffset + e.clientY - document.getElementById('hint').offsetHeight - 5;

  if (posVer<ver)
   document.getElementById('hint').style.top = posVer
  else
   document.getElementById('hint').style.top = posVer2;

  if (posHor<hor)
   document.getElementById('hint').style.left = posHor
  else
   document.getElementById('hint').style.left = posHor2
 }
}
function ShowHint(x,y,s,e,n,w,z){
	if (z == 1){
		if (x == ''){x = "Bohužel nic";}
		temp = '<table border="1" cellspacing="0" cellpadding="5" width="250" bordercolordark="#000000" bordercolor="#000000" bordercolorlight="#000000"><tr class="hlavicka_sloupcu"><td align="center" height="20" width="250">' + y + '</td></tr><tr bgcolor="#F5F4ED"><td class="cal_text" valign="top" height="200" width="250">' + s + ' - ' + e + '&nbsp;&nbsp;&nbsp;' + n + '<br>' + x + '</td></tr></table>';
	    document.getElementById('hint').innerHTML = temp;
		document.getElementById('hint').style.width = w;
    	document.getElementById('hint').style.visibility = "visible";
	}
	if (z == 0){
		document.getElementById('hint').style.visibility = "hidden";
	}
}

function getMouse(e){
	var x;
	var y;
	if (navigator.appName=="Microsoft Internet Explorer" || navigator.appName=="Opera"){
		x = window.event.x+9;
		y = window.event.y+20;
    }
	if (navigator.appName=="Netscape"){
		x = e.screenX+11;
		y = e.screenY-115;
    }
    x =  x + document.documentElement.scrollLeft;
    y =  y + document.documentElement.scrollTop;
    x = x.toString() + "px"
    y = y.toString() + "px"
	document.getElementById('hint').style.left = x;
    document.getElementById('hint').style.top  = y;
}