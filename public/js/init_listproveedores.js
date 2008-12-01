(function() 
	{
	    function doOnload() 
		{				
		    YAHOO.example.EnhanceFromMarkup = new function() {
			this.myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("tablelist"));
			this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
			this.myDataSource.responseSchema = {
			    fields: [
				    {key:"name"},
				    {key:"adress"},
				    {key:"tel"},
				    {key:"fax"},
				    {key:"mail"},
				    {key:"mod"},
				    {key:"elim"}
				    ]
				};

			var myDT = this.myDataTable = new YAHOO.widget.DataTable("divlistado", myColumnDefs, this.myDataSource,
				{sortedBy:{key:"name",dir:"desc"},draggableColumns:true});
		        // Shows dialog, creating one when necessary
		        this.newCols = true;
		        this.showDlg = function(e) {
		            YAHOO.util.Event.stopEvent(e);

		            if(this.newCols) {
				// Populate Dialog
				// Using a template to create elements for the SimpleDialog
				var allColumns = myDT.getColumnSet().keys;
				var elPicker = YAHOO.util.Dom.get("dt-dlg-picker");
				var elTemplateCol = document.createElement("div");
				YAHOO.util.Dom.addClass(elTemplateCol, "dt-dlg-pickercol");
				var elTemplateKey = elTemplateCol.appendChild(document.createElement("span"));
				YAHOO.util.Dom.addClass(elTemplateKey, "dt-dlg-pickerkey");
				var elTemplateBtns = elTemplateCol.appendChild(document.createElement("span"));
				YAHOO.util.Dom.addClass(elTemplateBtns, "dt-dlg-pickerbtns");
				var onclickObj = {fn:this.handleButtonClick, obj:this, scope:false };
				
				// Create one section in the SimpleDialog for each Column
				var elColumn, elKey, elButton, oButtonGrp;
				for(var i=0,l=allColumns.length;i<l;i++) {
				    var oColumn = allColumns[i];
				    
				    // Use the template
				    elColumn = elTemplateCol.cloneNode(true);
				    
				    // Write the Column key
				    elKey = elColumn.firstChild;
				    elKey.innerHTML = oColumn.label;
				    
				    // Create a ButtonGroup
				    oButtonGrp = new YAHOO.widget.ButtonGroup({ 
				                    id: "buttongrp"+i, 
				                    name: oColumn.getKey(), 
				                    container: elKey.nextSibling
				    });
				   
				    oButtonGrp.addButtons([
				        { label: showLabel, value: "Show", checked: ((!oColumn.hidden)), onclick: onclickObj},
				        { label: hideLabel, value: "Hide", checked: ((oColumn.hidden)), onclick: onclickObj}
				    ]);
				                    
				    elPicker.appendChild(elColumn);
				}
				this.newCols = false;
				}
			    this.myDlg.show();
			    this.myDlg.center();
			};
		this.hideDlg = function(e) {
		    this.hide();
		};
		this.handleButtonClick = function(e, oSelf) {
		    var sKey = this.get("name");
		    if(this.get("value") === "Hide") {
		        // Hides a Column
		        oSelf.myDataTable.hideColumn(sKey);
		    }
		    else {
		        // Shows a Column
		        oSelf.myDataTable.showColumn(sKey);
		    }
		};
        
			// Create the SimpleDialog
			YAHOO.util.Dom.removeClass("dt-dlg", "inprogress");
			this.myDlg = new YAHOO.widget.SimpleDialog("dt-dlg", {
				width: "30em",
					    visible: false,
					    modal: true,
					    buttons: [ 
							{ text:closeLabel,  handler:this.hideDlg }
				]
				});
				this.myDlg.render();

			// Nulls out myDlg to force a new one to be created
			myDT.subscribe("columnReorderEvent", function(){
			    this.newCols = true;
			    YAHOO.util.Event.purgeElement("dt-dlg-picker", true);
			    YAHOO.util.Dom.get("dt-dlg-picker").innerHTML = "";
			}, this, true);
		
			// Hook up the SimpleDialog to the link
			YAHOO.util.Event.addListener("dt-options-link", "click", this.showDlg, this, true);
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
