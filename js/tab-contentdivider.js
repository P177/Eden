// ř
//	<div id="cyclelinks"></div> - Je treba vlozit tam, kde chceme mit dane menu
//	<p class="dropcontent"> -  To co je uvnitr se zobrazuje a skryva podle toho co vybereme v menu
//	<span id="tab_1">Nadpis</span> - Zde napiseme nazev daneho Tabu (bude se zobrazovat v menu pro vyber daneho Tabu
//	Text ktery chceme zobrazit a skryt
//	</p> 
if (window.addEventListener || window.attachEvent){
document.write('<style type="text/css">\n')
document.write('.dropcontent{display:none;}\n')
document.write('</style>\n')
}

// Content Tabs script- By JavaScriptKit.com (http://www.javascriptkit.com)
// Last updated: July 25th, 05'

var showrecords=1 //specify number of contents to show per tab
var tabhighlightcolor="silver" //specify tab color when selected
var taboriginalcolor="white" //specify default tab color. Should echo your CSS file definition.

////Stop editing here//////////////////////////////////////

document.getElementsByClass=function(tag, classname){
var tagcollect=document.all? document.all.tags(tag): document.getElementsByTagName(tag) //IE5 workaround
var filteredcollect=new Array()
var inc=0
for (i=0;i<tagcollect.length;i++){
if (tagcollect[i].className==classname)
filteredcollect[inc++]=tagcollect[i]
}
return filteredcollect
}


function contractall(){
var inc=0
while (contentcollect[inc]){
contentcollect[inc].style.display="none"
inc++
}
}

function expandone(whichpage){
var lowerbound=(whichpage-1)*showrecords
var upperbound=(tabstocreate==whichpage)? contentcollect.length-1 : lowerbound+showrecords-1
contractall()
for (i=lowerbound;i<=upperbound;i++)
contentcollect[i].style.display="block"
}

function highlightone(whichtab){
for (i=0;i<tabscollect.length;i++){
tabscollect[i].style.backgroundColor=taboriginalcolor
tabscollect[i].style.borderRightColor="white"
}
tabscollect[whichtab].style.backgroundColor=tabhighlightcolor
tabscollect[whichtab].style.borderRightColor="gray"
}

function generatetab(){
contentcollect=document.getElementsByClass("p", "dropcontent")
tabstocreate=Math.ceil(contentcollect.length/showrecords)
linkshtml=""
for (i=1;i<=tabstocreate;i++){
tab_name_collect=document.getElementById('tab_'+i).innerHTML
tab_name=tab_name_collect
linkshtml+='<span class="tabstyle" onClick="expandone('+i+');highlightone('+(i-1)+')">'+tab_name+'</span> &nbsp;|&nbsp; '
}
document.getElementById("cyclelinks").innerHTML=linkshtml
tabscollect=document.getElementsByClass("span", "tabstyle")
highlightone(0)
expandone(1)
}

if (window.addEventListener)
window.addEventListener("load", generatetab, false)
else if (window.attachEvent)
window.attachEvent("onload", generatetab)