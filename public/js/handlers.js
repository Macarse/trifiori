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
