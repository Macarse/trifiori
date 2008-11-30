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

/* loadAjax
* 
* url = direccion del server
* variables = variables que recibe el servidor
* method = [POST | GET]
* cbReady = funcion(xhr) que ejecuta cuando llega al estado ready
* cbLoading = funcion(xhr) que ejecuta cuando esta en loading
* cbError = funcion(xhr) que ejecuta cuando esta en error
*/
function loadAjax(url, variables, method, cbReady, cbLoading, cbError)
{
	var ajax=GetXmlHttpObject();

	//Abro el ajax segun el tipo
	if(method.toUpperCase()=='POST')
		ajax.open ('POST', url, true);
	else if (method.toUpperCase()=='GET')
		ajax.open ('GET', url, true);

	ajax.onreadystatechange = function() 
	{
		if (ajax.readyState==1) 
		{
			//Esta recibiendo los datos
			if (cbLoading != null)
			{
				cbLoading(ajax);
			}
		}
		else if (ajax.readyState==4)
		{
			if(ajax.status==200)
			{
				//Todo ok
				if (cbReady != null)
				{
					cbReady(ajax);
				}
			}
			else if(ajax.status==404)
			{
				//No encontro la pagina
				if (cbError != null)
				{
					cbError(ajax);
				}
			}
			else
			{
				//Error externo
				if (cbError != null)
				{
					cbError(ajax);
				}
			}
		}
	}
		
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	//Segun el tipo mando parametros o no
	if(method.toUpperCase()=='POST')
		ajax.send(variables);
	else if (method.toUpperCase()=='GET')
		ajax.send(null);

	return;
}

