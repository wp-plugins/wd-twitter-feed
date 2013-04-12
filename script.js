var popUpWidth = 450;
var popUpHeight = 450;

window.onload = function() {	
	
	// Get all anchors
	var links = document.getElementsByTagName("a"); 
	
	// Loop through anchors
	for (var i = 0; i < links.length; i++) { 
		
		// Get class names separated by a space
		var eleClass = links[i].className.split(/\s/);
		
		// Loop through the object css classes
		for(j = 0; j < eleClass.length; j++){
			if ( eleClass[j] == "web-intent" ) { 
				
				// Onclick event to open popup
				links[i].onclick = function() {
					newwindow=window.open(this.getAttribute("href"),this.getAttribute("title"),'height=' + popUpHeight + ',width=' + popUpWidth);
					if (window.focus) {newwindow.focus()}
					
					// Centralize the popup window
					newwindow.moveTo((screen.width-popUpWidth)/2,(screen.height-popUpHeight)/2);
					return false;
				};
			}
		}
	}
}