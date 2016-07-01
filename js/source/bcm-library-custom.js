jQuery(document).ready(function(){
// display each front block description on mouseover
/*
	jQuery('.front-block .each-block-description').mouseenter(function(){
		var $this = jQuery(this);
		$this.stop();
		$this.fadeOut(0);
		$this.fadeIn(1000);
		console.log('mounseenter');
	});
	jQuery('.front-block .each-block-description').mouseleave(function(){
		var $this = jQuery(this);
		$this.stop();
		$this.fadeIn(0);
		$this.fadeOut(1000);
		console.log('mounseleave');
	});
*/
	jQuery('.front-block').hover(
		function(){
			var $this = jQuery(this);
			//$this.find('.each-block-description').stop().fadeOut(0).delay(1000).fadeIn(300);
			$this.find('.each-block-description').stop().fadeOut(0).fadeIn(300).css('display','flex');
		},
		function(){
			var $this = jQuery(this);
			$this.find('.each-block-description').stop().fadeIn(0).fadeOut(50);
		}
	);
});
/*
Swap the logo to the mobile version 
*/
jQuery(window).bind("ready resize",function(e){
	var $bcmW = jQuery(e.target).width();
	if($bcmW < 768) jQuery('#logo>img').attr('src', '//lrweb.berklee.edu/sites/all/themes/jjamerson_lb/logo-lb-mobile.png');
	else jQuery('#logo>img').attr('src', '//lrweb.berklee.edu/sites/all/themes/jjamerson_lb/logo.png');
});