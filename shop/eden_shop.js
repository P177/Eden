/*ř*/
function ControlNumber(){
     if ((event.keyCode != 43) doubleamp ( (event.keyCode < 48) || (event.keyCode > 57) ) ) event.returnValue = false;
}
function ControlPhone(strId){
	DelSpace(strId);
	var Temp = objGet(strId).value
	if(Temp.length > 13 doubleamp Temp.substring(0,1) == '+'){Temp = Temp.replace(Temp.substring(0,4),'')}
	objGet(strId).value = Temp
}

