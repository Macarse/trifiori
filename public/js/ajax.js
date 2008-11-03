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

function showExpo() 
{ 
    if (xmlHttp.readyState == 4)
    { 
        document.getElementById("divdetalles").innerHTML = xmlHttp.responseText;
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

function getDataAjax(url,variables, readyFuncion , method)
{
	var ajax=GetXmlHttpObject();

	/*Creamos y ejecutamos la instancia si el method elegido es POST*/
	if(method.toUpperCase()=='POST')
	{

		ajax.open ('POST', url, true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState==4)
			{
				if(ajax.status==200)
				{
					readyFuncion(ajax.responseText);
				}
				else if(ajax.status==404)
				{
					return "Error: Invalid site";
				}
				else
				{
					return "Error: " + ajax.status;
				}
			}
		}
		
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		ajax.send(variables);
		
		return;
	}
	
	/*Creamos y ejecutamos la instancia si el method elegido es GET*/
	if (method.toUpperCase()=='GET')
	{
		ajax.open ('GET', url, true);

		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState==4)
			{
				if(ajax.status==200)
				{
					return ajax.responseText;
				}
				else if(ajax.status==404)
				{
					return "Error: Invalid site";
				}
				else

				{
					return "Error: " + ajax.status;
				}
			}
		}
		
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);
		
		return;
	}
}

function loadAjax(url,divID,variables,method)
{
	var ajax=GetXmlHttpObject();
	var divIDContenedora = document.getElementById(divID);

	/*Creamos y ejecutamos la instancia si el method elegido es POST*/
	if(method.toUpperCase()=='POST')
	{

		ajax.open ('POST', url, true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState==1) 
			{
				divIDContenedora.innerHTML="Cargando.......";
			}
			else if (ajax.readyState==4)
			{
				if(ajax.status==200)
				{
					document.getElementById(divID).innerHTML=ajax.responseText;
				}
				else if(ajax.status==404)
				{
					divIDContenedora.innerHTML = "La direccion no existe";

				}
				else
				{
					divIDContenedora.innerHTML = "Error: ".ajax.status;
				}
			}
		}
		
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		ajax.send(variables);
		
		return;
	}
	
	/*Creamos y ejecutamos la instancia si el method elegido es GET*/
	if (method.toUpperCase()=='GET')
	{
		ajax.open ('GET', url, true);

		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState==1) 
			{
				divIDContenedora.innerHTML="Cargando.......";
			}
			else if (ajax.readyState==4)
			{
				if(ajax.status==200
)
				{
					document.getElementById(divID).innerHTML=ajax.responseText;
				}
				else if(ajax.status==404)
				{
					divIDContenedora.innerHTML = "La direccion no existe";
				}
				else

				{
					divIDContenedora.innerHTML = "Error: ".ajax.status;
				}
			}
		}
		
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);

		
		return;
	}
} 
