jQuery(document).ready(function($){
  if ( $('body').hasClass('node-type-event') || $('body').hasClass('node-type-news') || $('body').hasClass('node-type-bt-article')) {
    /* Clean up the 'rel' attr of all lightbox image links: */
    var imageItems = $('.field-type-image .field-items .field-item');
    imageItems.each(function(){
      var imageLink = $('a', $(this) );
      var rel = imageLink.attr('rel');
      if (rel) {
        rel = rel.replace(/&quot;/g, '"')
          .replace(/&#39;/g, "'")
          .replace(/&lt;/g, '<')
          .replace(/&gt;/g, '>')
          .replace(/&amp;/g, '&');
        imageLink.attr('rel', rel);
      }
    });

    /* Grab, format, and add the caption for the first image (the one displaying on the page): */
    var imageItem = $('.field-type-image .field-items .field-item:first');
    $('img', imageItem).css('opacity', 0);
    $('img', imageItem).one('load', function() {
      var imageWidth = imageItem.width();
      var imageLink = $('a', imageItem);
      var title = imageLink.attr('title');
      if (title) {
        title = title.replace(/&quot;/g, '"')
          .replace(/&#39;/g, "'")
          .replace(/&lt;/g, '<')
          .replace(/&gt;/g, '>')
          .replace(/&amp;/g, '&');
        var info = title.split('&nbsp;');
        var caption = "<div class='image-caption'>" + info[0] + "</div>";
        if (info.length > 1) {
          caption += "<div class='image-credit'>" + info[1] + "</div>";
        }
        var caption_block = $('<div />').addClass('image-info-block').html(caption).appendTo(imageItem);
        /* Set the info block's width to match the image's width: */
        if (imageWidth < 50) { imageWidth = 300; }
        caption_block.attr('style', 'opacity:0; width:' + imageWidth + 'px;');
        caption_block.animate({ opacity: 1 }, 200);
      }
    }).each(function() {
      $(this).animate({ opacity: 1 }, 400);
    });

    /* Add map link */

    var venue   = $('.region-content .field-name-field-event-venue');
    if ( $('.field-item', venue).length ) {
      venue = $('.field-item', venue);
    }

    var address = $('.region-content .field-name-field-event-address .field-item');
    var numberRegex = /\d/g;
    var hasNumber = numberRegex.test( address.text() );

    if (venue.length && address.length && hasNumber) {
      var city    = $('.region-content .field-name-field-city .field-item');
      var state   = $('.region-content .field-name-field-state .field-item');
      var zip     = $('.region-content .field-name-field-zip .field-item');
      var country = $('.region-content .field-name-field-country .field-item');

      var placeArray = [address.text(), city.text(), state.text(), zip.text().replace(/ /g,''), country.text()];
      if (typeof Array.prototype.filter == 'function') {
        //var placeArray = placeArray.filter( function(n) {return n} ); //original
        placeArray = placeArray.filter( function(n) {return n;} ) ;
      }
      var placeString = placeArray.join(', ');


      //var addressWrapper = $('<div />').addClass('address-container');
      //addressWrapper.append(city, state, zip, country);
      city.parents('.field').remove();
      state.parents('.field').remove();
      zip.parents('.field').remove();
      country.parents('.field').remove();

      var searchPrefix = 'https://maps.google.com/maps?q=';
      var placeLink = $('<a />')
        .addClass('map-link no-external-link-icon')
        .attr('href', searchPrefix + placeString.replace(/ /g, '+') )
        .html(' (view map)');
      placeLink.appendTo(address.parents('.field'));

      address.replaceWith(placeString);
    }

  } // END: if body has event or news class
});