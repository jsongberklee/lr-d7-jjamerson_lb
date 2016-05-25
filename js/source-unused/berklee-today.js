jQuery(document).ready(function($){
  if ( $('body').hasClass('node-type-bt-article') ) {
      /* Clean up the 'rel' attr of all lightbox image links: */
    var imageItems = $('.field-name-field-article-images');
    imageItems.hide();
    imageItems.each(function(){

    });

    /* Grab, format, and add the caption for the first image (the one displaying on the page): */
    var imageItem = $('.field-name-field-article-images:first');
    imageItem.show();
  }
});