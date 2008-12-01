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
				{sortedBy:{key:"name",dir:"desc"},draggableColumns:true}
			);
		    };
	        }
		
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
