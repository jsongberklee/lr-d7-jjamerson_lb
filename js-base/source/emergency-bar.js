jQuery(document).ready(function($){
	// to memorized and keep the position of emergency bar.
	var check_emergency_cookie = function(c){
		var duration = 3600 * 3 * 1000; // 3 hours
		//var duration = 60 * 1000; // for debugging
		var targetStamp = $.cookie('mdev_emergency_duration');
		var now = $.now();
		if(targetStamp == null || targetStamp < now){
			if(c==undefined){
				$.cookie('mdev_emergency_duration', now+duration, {path:'/', domain:'berklee.edu'});
				//$.cookie('mdev_emergency_duration', now+duration, {path:'/', domain:'lb.dev'}); // for debugging
				//console.debug("cookie reset-> "+ $.cookie('mdev_emergency_duration'));
			}
			return false;
		}else{
			return true;
		}
	};
  /* disabled due to compatibility (not available to use global base domain name "berklee.edu"
	if ( $('body').hasClass('emergency') ){
    var classes = $('body').attr('class').split(' ');
		console.log("classes-> "+classes);
    for (i = 0; i < classes.length; i++) {
      if (classes[i].indexOf('emergency-id') > -1) {
        var emergencyId = classes[i];
      }
    }
  }*/
  //rememberCollapsed('remove', emergencyId);
  $('.region-emergency-bar').each(function() {
    var emergencyBar = $(this);
    var emergencyExpand = $('span.expand-button', this);
    $(this).css('marginTop', '0px');
    
		$('.container', this).click(function() {
      emergencyBar.css('marginTop', '-300px');
      emergencyExpand.css('marginTop', '300px');
      emergencyExpand.css('opacity', 0.9);
      //rememberCollapsed('add', '|' + emergencyId);
			check_emergency_cookie();
  	});

		emergencyExpand.click(function() {
	    $(this).css('opacity', 0);
	    emergencyBar.css('marginTop', '0');
	    
			// disabled due to compatibility (not available to use global base domain name "berklee.edu"
		  //rememberCollapsed('remove', emergencyId);
		  
			$.cookie('mdev_emergency_duration', '', {path:'/', domain:'berklee.edu', expires: -1});
			//$.cookie('mdev_emergency_duration', '', {path:'/', domain:'lb.dev', expires: -1});
		});

		// the bar remains as closed
		if(check_emergency_cookie('loaded') == true){
			emergencyBar.css('marginTop', '-300px');
			emergencyExpand.css('marginTop', '300px');
			emergencyExpand.css('opacity', 0.9);
		}

		/* disabled due to compatibility (not available to use global base domain name "berklee.edu"
    if (typeof(Storage) != undefined && localStorage != null && emergencyId != undefined) {
      var isCollapsed = rememberCollapsed('check', emergencyId );
      if (isCollapsed > -1) {
        emergencyBar.css('marginTop', '-300px');
        emergencyExpand.css('marginTop', '300px');
        emergencyExpand.css('opacity', 0.9);
      }
    }*/
  });
});