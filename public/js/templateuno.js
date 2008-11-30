(function() 
	{
	    function doOnload() 
		{				

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
