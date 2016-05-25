jQuery(document).ready(function($){
  var soundbreakingStripe = $('.soundbreaking-stripe');
  if (soundbreakingStripe.length) {
    var blurbs = $('.blurb p', soundbreakingStripe);
    blurbs.eq(0).css('opacity', 1);
    var active = 0;
    var fadeEffect = setInterval(function() {
      blurbs.eq(active).css('opacity', 0);
      active++;
      if (active > blurbs.length) { active = 0; }
      blurbs.eq(active).css('opacity', 1);
    }, 6000);
  }
});