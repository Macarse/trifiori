(function() 
	{
	    function doOnload() 
		{				
		    YAHOO.util.Event.onContentReady("menuadmin", function () {
		        var ua = YAHOO.env.ua, oAnim;  // Animation instance
		        function onSubmenuBeforeShow(p_sType, p_sArgs) {
		            var oBody,oElement,oShadow,oUL;
		            if (this.parent) {
		                oElement = this.element;
		                oShadow = oElement.lastChild;
		                oShadow.style.height = "0px";
		                if (oAnim && oAnim.isAnimated()) {
		                    oAnim.stop();
		                    oAnim = null;}
		                oBody = this.body;
		                if (this.parent && !(this.parent instanceof YAHOO.widget.MenuBarItem)) {
		                    if (ua.gecko || ua.opera) {
		                        oBody.style.width = oBody.clientWidth + "px";
		                    }
		                    if (ua.ie == 7) {
		                        oElement.style.width = oElement.clientWidth + "px";
		                    }
		                
		                }
		                oBody.style.overflow = "hidden";
		                oUL = oBody.getElementsByTagName("ul")[0];
		                oUL.style.marginTop = ("-" + oUL.offsetHeight + "px");
		            }
		        }

		        function onTween(p_sType, p_aArgs, p_oShadow) {
		            if (this.cfg.getProperty("iframe")) {
		                this.syncIframe();
		            }
		        
		            if (p_oShadow) {
		                p_oShadow.style.height = this.element.offsetHeight + "px";
		            }
		        }

		        function onAnimationComplete(p_sType, p_aArgs, p_oShadow) {
		            var oBody = this.body,
		                oUL = oBody.getElementsByTagName("ul")[0];

		            if (p_oShadow) {
		                p_oShadow.style.height = this.element.offsetHeight + "px";
		            }

		            oUL.style.marginTop = "";
		            oBody.style.overflow = "";

		            if (this.parent &&  !(this.parent instanceof YAHOO.widget.MenuBarItem)) {
		                if (ua.gecko || ua.opera) {
		                    oBody.style.width = "";
		                }
		                
		                if (ua.ie == 7) {
		                    this.element.style.width = "";
		                }
		            }
		        }

		        function onSubmenuShow(p_sType, p_sArgs) {
		            var oElement,oShadow,oUL;
		        
		            if (this.parent) {
		                oElement = this.element;
		                oShadow = oElement.lastChild;
		                oUL = this.body.getElementsByTagName("ul")[0];
		            
		                oAnim = new YAHOO.util.Anim(oUL, 
		                    { marginTop: { to: 0 } },
		                    .5, YAHOO.util.Easing.easeOut);

		                oAnim.onStart.subscribe(function () {        
		                    oShadow.style.height = "100%";
		                });
	    
		                oAnim.animate();

		            if (YAHOO.env.ua.ie) {                            
		                    oShadow.style.height = oElement.offsetHeight + "px";
		                    oAnim.onTween.subscribe(onTween, oShadow, this);
		                }
		                oAnim.onComplete.subscribe(onAnimationComplete, oShadow, this);                    
		            }
		        }

		        var oMenuBar = new YAHOO.widget.MenuBar("menuadmin", { 
		                                                    autosubmenudisplay: true, 
		                                                    hidedelay: 750, 
		                                                    lazyload: true });
		        
		        oMenuBar.subscribe("beforeShow", onSubmenuBeforeShow);
		        oMenuBar.subscribe("show", onSubmenuShow);

		        oMenuBar.render();                      
		    });

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
