jQuery(document).ready(function($){

  // originally this function was decleared inside of the IF statement below.
  function adjustSidebarHeight() {
    var contentPaddingTop = parseInt( $('#page-content').css('paddingTop').replace('px', '') );
    var contentPaddingBottom = parseInt( $('#page-content').css('paddingBottom').replace('px', '') );
    var contentPadding = contentPaddingTop + contentPaddingBottom;

    var sidebarPaddingTop = parseInt( $('.region-sidebar-first').css('paddingTop').replace('px', '') );
    var sidebarPaddingBottom = parseInt( $('.region-sidebar-first').css('paddingBottom').replace('px', '') );
    var sidebarPadding = sidebarPaddingTop + sidebarPaddingBottom;

    var contentTotalHeight = $('#page-content').height() + contentPadding;
    var sidebarTotalHeight = $('.region-sidebar-first').height() + sidebarPadding;

		var height; // added for avoiding duplicate declarations.
    if ( contentTotalHeight > sidebarTotalHeight ) {
      // var height = contentTotalHeight - sidebarPadding; // original
      height = contentTotalHeight - sidebarPadding;
      $('.region-sidebar-first').height(height);
    } else {
      height = sidebarTotalHeight - contentPadding;
      $('#page-content').height(height);
    }

  }

  if ( $('body').hasClass('node-type-hybrid-homepage') ) {
    // line up the bottom of the menu with the bottom of the page content region
    adjustSidebarHeight();
    $(window).resize(function() {
      adjustSidebarHeight();
    });
  }

});