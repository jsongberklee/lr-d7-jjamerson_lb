jQuery(document).ready(function($){
  $('.highlights-stripe').each(function() {
    var thisStripe = $(this);
    var leftArrow = $('<div />').addClass('highlights-stripe-arrow left fa fa-angle-left').attr('aria-hidden', 'true').appendTo( $(this) );
    var rightArrow = $('<div />').addClass('highlights-stripe-arrow right fa fa-angle-right').attr('aria-hidden', 'true').appendTo( $(this) );
    var position = 0;
    var maxPosition = $('.views-row', this).length - 3;
    leftArrow.click(function() {
      if (position > 0) {
        position--;
      } else {
        position = maxPosition;
      }
      updatePosition();
    });
    rightArrow.click(function() {
      if (position < maxPosition) {
        position++;
      } else {
        position = 0;
      }
      updatePosition();
    });
    function updatePosition() {
      var offset = 33.333 * position;
      console.log(thisStripe);
      console.log(maxPosition);
      $('.view', thisStripe).css('marginLeft', '-' + offset + '%');
    }
  });
  $('.highlights-stripe .views-row').each(function() {
    
    // Check to see if this item is a news story or event, and if so, add the appropriate 'all' link:
    var link = $('a.hero-image-button', this);
    if (link.attr('href').indexOf('.edu/news/') > -1) {
      var newsLink = $('<a />').addClass('news-link news-events-link').attr('href', '/news').text('See all news');
      newsLink.appendTo( $(this) );
    } else if (link.attr('href').indexOf('.edu/events/') > -1) {
      var eventsLink = $('<a />').addClass('events-link news-events-link').attr('href', '/events').text('See all events');
      eventsLink.appendTo( $(this) );
    }
  });
});