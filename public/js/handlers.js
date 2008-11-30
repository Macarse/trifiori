// JavaScript Document

var map = null;

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

function dateToLocaleString(dt, cal, lang)
{
    var dStr = dt.getDate();
    var mStr = dt.getMonth() + 1;
    var yStr = dt.getFullYear();

    if (mStr < 10)
            mStr = "0" + mStr;

    if (dStr < 10)
            dStr = "0" + dStr;

    if (lang == 'es')
        return (dStr + "-" + mStr + "-" + yStr);
    else
        return (mStr + "-" + dStr + "-" + yStr);
}

function handlerCalFechaIngresoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idFechaIngreso', dateToLocaleString(selDate, this, 'es'));

    hide_div('calFechaIngreso');
};

function handlerCalFechaIngresoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idFechaIngreso', dateToLocaleString(selDate, this, 'en'));

    hide_div('calFechaIngreso');
};

function handlerCalDESpresentadoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESPresentado', dateToLocaleString(selDate, this, 'en'));

    hide_div('calDesPresentado');
};

function handlerCalDESpresentadoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESPresentado', dateToLocaleString(selDate, this, 'es'));

    hide_div('calDesPresentado');
};

function handlerCalDESsalidoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESSalido', dateToLocaleString(selDate, this, 'en'));

    hide_div('calDESsalido');
};

function handlerCalDESsalidoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESSalido', dateToLocaleString(selDate, this, 'es'));

    hide_div('calDESsalido');
};


function handlerCalDEScargadoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESCargado', dateToLocaleString(selDate, this, 'en'));

    hide_div('calDEScargado');
};

function handlerCalDEScargadoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESCargado', dateToLocaleString(selDate, this, 'es'));

    hide_div('calDEScargado');
};

function handlerCalDEsfechaFacturaEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESFechaFactura', dateToLocaleString(selDate, this, 'en'));

    hide_div('calDEsfechaFactura');
};

function handlerCalDEsfechaFacturaES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESFechaFactura', dateToLocaleString(selDate, this, 'es'));

    hide_div('calDEsfechaFactura');
};

function handlerCalvencimientoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idVencimiento', dateToLocaleString(selDate, this, 'en'));

    hide_div('calVencimiento');
};

function handlerCalvencimientoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idVencimiento', dateToLocaleString(selDate, this, 'es'));

    hide_div('calVencimiento');
};

function handlerCalIngPuertoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idIngPuerto', dateToLocaleString(selDate, this, 'es'));

    hide_div('calIngPuerto');
};

function handlerCalIngPuertoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idIngPuerto', dateToLocaleString(selDate, this, 'en'));

    hide_div('calIngPuerto');
};

function handlerCalDesVencimientoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESVencimiento', dateToLocaleString(selDate, this, 'en'));

    hide_div('calDesVencimiento');
};

function handlerCalDesVencimientoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idDESVencimiento', dateToLocaleString(selDate, this, 'es'));

    hide_div('calDesVencimiento');
};


function handlerCalingresoPuertoEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idIngPuerto', dateToLocaleString(selDate, this, 'en'));

    hide_div('calIngPuerto');
};

function handlerCalingresoPuertoES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idIngPuerto', dateToLocaleString(selDate, this, 'es'));

    hide_div('calIngPuerto');
};


function handlerCalperpreEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idPerPre', dateToLocaleString(selDate, this, 'en'));

    hide_div('calPerPre');
};

function handlerCalperpreES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idPerPre', dateToLocaleString(selDate, this, 'es'));

    hide_div('calPerPre');
};

function handlerCalfecfacEN(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idPerFecFac', dateToLocaleString(selDate, this, 'en'));

    hide_div('calFecFac');
};

function handlerCalfecfacES(type,args,obj)
{
    var selected = args[0];
    var selDate = this.toDate(selected[0]);

    changeDateInput('idPerFecFac', dateToLocaleString(selDate, this, 'es'));

    hide_div('calFecFac');
};

function errorAjax(xhr)
{
	alert('surgio un error');
}

function showMap(latitud, longitud)
{
	/* cargo el mapa */
	loadMap(latitud,longitud);
	/* agrego las ubicaciones */
	loadAjax("http://localhost/user/puertos/getgeoloc", '', 'GET', loadGkeysMap, null, errorAjax);
}

function loadGkeysMap(xhr)
{
	var data;
	var name, lat, lon;

	total = 0;
	/* armo el objeto y si no puedo mando error */
	try {
		data = (eval("(" + xhr.responseText + ")"));
		data = data.Resultset.Result;
		for (var i = 0; i < data.length; i++) 
		{
		   name = data[i].name;
		   lat = data[i].lat;
		   lon = data[i].long;
 		   addMarkerMap(map, lat, lon, name);
		}
	} catch (e) {
           alert(xhr.message);
	}

}



function loadMap(latitud,longitud)
{
        YAHOO.namespace("map.container");

        YAHOO.map.container.panel = new YAHOO.widget.Panel("divmap",
        {   width:"620px", 
            modal:true,
            visible:true, 
            underlay:"shadow",
            fixedcenter:true, 
            constraintoviewport:true, 
            draggable:false
        } );
	YAHOO.map.container.panel.setHeader("Mapa");
	YAHOO.map.container.panel.setBody("<div id='map' style='width: 600px; height: 400px'></div>");
	YAHOO.map.container.panel.setFooter("Trifiori");

        YAHOO.map.container.panel.render();

	if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
	var point = new GLatLng(latitud,longitud);
	map.setCenter(point, 10);

	map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());

       }
       else
       {
       	    document.getElementById('divmap').innerHTML="Su navegador no soporta mapas";
       }
}

function addtag(point, address) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function() {
	marker.openInfoWindowHtml('<b>' + address + '</b>'); } );
        return marker;
}


function addMarkerMap(map, x, y, label)
{
	var point = new GLatLng(x,y);
	var marker = addtag(point, label);
	map.addOverlay(marker);
}

