// JavaScript Document

function onMenuItemClick(p_sType, p_aArgs, p_Data) 
{
    /*
    Handlers del menu
            Index = this.index 
            Text = this.cfg.getProperty("text")
            Value = p_oValue
    */

    window.location = p_Data.url;
} 

function hide_div(divID)
{
	document.getElementById(divID).style.display = "none";
}

function show_div(divID)
{
	document.getElementById(divID).style.display = "";
}

function show_hide_div(divID)
{
	if (document.getElementById(divID).style.display != "none")
		hide_div(divID);
	else
		show_div(divID);
}

function keyPress(e)
{
      var kC  = (window.event) ?    // MSIE or Firefox?
                 event.keyCode : e.keyCode;
      
	  return kC;
} 

function keyCalendar(e, divCalendar)
{
	if (keyPress(e) == 40)
	{
		show_hide_div(divCalendar);
	}
}

function changeDateInput(idText, msg) {
	document.getElementById(idText).value = msg;
}

function dateToLocaleString(dt, cal) {
			var dStr = dt.getDate();
			var mStr = dt.getMonth() + 1;
			var yStr = dt.getFullYear();
			return (dStr + "-" + mStr + "-" + yStr);
}

function handlerCalFechaIngreso(type,args,obj) {
	var selected = args[0];
	var selDate = this.toDate(selected[0]);
	 
	changeDateInput('idFechaIngreso', dateToLocaleString(selDate, this));
	
	hide_div('calFechaIngreso');
};

function handlerCalVencimiento(type,args,obj) {
	var selected = args[0];
	var selDate = this.toDate(selected[0]);
	 
	changeDateInput('idVencimiento', dateToLocaleString(selDate, this));
	
	hide_div('calVencimiento');
};

function handlerCalIngPuerto(type,args,obj) {
	var selected = args[0];
	var selDate = this.toDate(selected[0]);
	 
	changeDateInput('idIngPuerto', dateToLocaleString(selDate, this));
	
	hide_div('calIngPuerto');
};

function handlerCalPerPre(type,args,obj) {
	var selected = args[0];
	var selDate = this.toDate(selected[0]);
	 
	changeDateInput('idPerPre', dateToLocaleString(selDate, this));
	
	hide_div('calPerPre');
};

function handlerCalPerFecFac(type,args,obj) {
	var selected = args[0];
	var selDate = this.toDate(selected[0]);
	 
	changeDateInput('idPerFecFac', dateToLocaleString(selDate, this));
	
	hide_div('calFecFac');
};