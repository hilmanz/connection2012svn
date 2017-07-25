// JS scrollcApp - Beta Version
// jquery for scrolling content
// Author: CendekiApp 

		var content_count;
 		var content_interval;
 		var old_content = 0;
 		var current_content = 0;
		var container_height;
		var container_content;
		var round_count;
		var page_count;
		var control = 1;
		var height_control;
		
		var currHeight = 0;
 
 $(document).ready(function(){
   
   container_height = $("#scrollcApp").height();
   //alert(container_height);
   container_content = $(".scrollcApp").height();
   //alert(container_content);
   content_count = container_content/container_height;
   round_count = Math.round(content_count);
   content_plus = round_count + 1;
   height_control = container_height;
   //alert(round_count);
   
   if (content_count == round_count){
		page_count = content_count;
	}else if(content_count > round_count){
	  	page_count = content_plus;
	}else{
		page_count = round_count;	
	}	
  
    $('input#up').attr("disabled","disabled");
	$('input#down').removeAttr("disabled");
   
   $('#down').click(function(){		
	   
	   $('input#up').removeAttr("disabled");
		//alert(page_count);
		if(control < page_count){		
			$("div.scrollcApp").animate({"top": "-="+height_control+"px"}, "slow")
			control++;
			if (control == page_count){
				$('input#down').attr("disabled","disabled");
			}
		}
		
				
	});
   $('#up').click(function(){		
	  	
	   $('input#down').removeAttr("disabled");
		
		if(control > 1){
			
			$("div.scrollcApp").animate({"top": "+="+height_control+"px"}, "slow")
			
			control--;
			if (control == 1){
				$('input#up').attr("disabled","disabled");
			}
		}
			
	});

   $('#scrollcApp').mousewheel(function(event, delta) {
	   this.scrollTop -= (delta * 30);
		//event.stopPropagation();
	    event.preventDefault();

	});

 });