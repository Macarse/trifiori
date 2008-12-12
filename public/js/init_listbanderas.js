(function() 
	{
	    function doOnload() 
		{				
		    YAHOO.example.EnhanceFromMarkup = new function() {
			this.myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("tablelist"));
			this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
			this.myDataSource.responseSchema = {
			    fields:
				[
				    {key:"name"},
				    {key:"mod"},
				    {key:"elim"}
				]
			    };

			this.myDataTable = new YAHOO.widget.DataTable("divlistado", myColumnDefs, this.myDataSource,
				{}
			);
            
            function get_url_param(name)
            { 
                name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]"); 
                var regexS = "[\\?&]"+name+"=([^&#]*)"; 
                var regex = new RegExp( regexS ); 
                var results = regex.exec( window.location.href ); 
                if( results == null )    return ""; 
                else return results[1];
            }
            
            function cambiaBusqueda(e) {
                var x = location.protocol + '//' + location.host + location.pathname;
                var y = "";
                var sortby_prev = "";
                var sort_prev = "";
                var sort_act;
                
                y = get_url_param("consulta");
                sortby_prev = get_url_param("sortby");
                sort_prev = get_url_param("sort");
                            
                if (this.getColumn(e.target) == "Column instance 0")
                {
                    if (sortby_prev == "name") 
                    {
                        if (sort_prev == "asc")
                            sort_act = "desc";
                        else
                            sort_act = "asc";
                    }
                    else
                    {
                        sort_act = "desc";
                    }
                    window.location = x + "?consulta=" + y + "&sortby=name" + "&sort=" + sort_act;
                }
            }
            
            this.myDataTable.subscribe("theadCellClickEvent", cambiaBusqueda);
        }
        };
		//si es ff
		if(window.addEventListener) 
		{
			window.addEventListener("load", doOnload, false);
		} 
		//si es ie
		else if(window.attachEvent) 
		{
			window.attachEvent("onload", doOnload);
		}
})();
