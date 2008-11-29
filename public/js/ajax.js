var xmlHttp

function ShowDetails(id)
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert ("El browser no soporta ajax :(");
        return;
    }
    
    var url = "/user/exportaciones/details";
    url = url + "?id=" + id;
    xmlHttp.onreadystatechange = showExpo;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function ShowDetailsImp(id)
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert ("El browser no soporta ajax :(");
        return;
    }
    
    var url = "/user/importaciones/details";
    url = url + "?id=" + id;
    xmlHttp.onreadystatechange = showExpo;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function HideDetails()
{
    document.getElementById("divdetalles").style.display = "none";
}

function showExpo() 
{ 
    if (xmlHttp.readyState == 4)
    { 
        YAHOO.namespace("expodetalles.container");

        document.getElementById("divdetalles").style.display = "block";
        document.getElementById("divdetalles").innerHTML = xmlHttp.responseText;

        YAHOO.expodetalles.container.panel1 = new YAHOO.widget.Panel("divdetalles",
        {   width:"400px", 
            modal:true,
            visible:true, 
            underlay:"shadow",
            fixedcenter:true, 
            constraintoviewport:true, 
            draggable:false
        } );
        YAHOO.expodetalles.container.panel1.render();
    }
}

function GetXmlHttpObject()
{
    var xmlHttp = null;
    try
    {
      // Firefox, Opera 8.0+, Safari
      xmlHttp = new XMLHttpRequest();
    }
    catch (e)
    {
      // Internet Explorer
      try
      {
        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
      }
      catch (e)
      {
          xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
    }
    
    return xmlHttp;
}
