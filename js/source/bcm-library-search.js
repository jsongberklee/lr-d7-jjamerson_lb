jQuery(document).ready(function($){

	// this file is duplicated from base jjamerson theme /js/source/search.js
	// and updated to make it work with the search api's search block
	// note on comment "by jsong"

  // pre-populate the search form:
  var searchInput = $('form#search-api-page-search-form-search-site input[name="keys_2"]');
  searchInput.attr('autocomplete', 'on'); // autocomplete turned on by jsong
  var searchDefaultText = 'SEARCH IN LIBRARY'; // updated by jsong

  // add search/filter to menu:
  var menuFilter = $('<div />').addClass('menu-filter-lb');
  var menuFilterTitle = $('<h3 />').text("Don't see what you're looking for in the menu?").appendTo(menuFilter);
  var menuFilterDefaultText = 'Search in Library';
  var menuFilterSearch = $('<input />').attr('type', 'text').addClass('default-text menu-filter-search');
  var menuFilterSubmit = $('<div />').text('Go').addClass('button menu-filter-submit').appendTo(menuFilter);
  menuFilterSearch.val(menuFilterDefaultText).appendTo(menuFilter);
  var menuFilterResults = $('<div />').addClass('results');
  $(".region-off-screen-overlay > .container").prepend(menuFilter);

  /*
  var menuFilterHelp = $('<div />')
    .addClass('menu-filter-help')
    .text("Help text could go here")
    .appendTo(menuFilter);
  */

  searchInput.each(function() {
    if ( !$(this).val() ) {
      $(this).val(searchDefaultText);
    }
    $(this).focus(function() {
      if ( $(this).val() == searchDefaultText ) {
        $(this).val('');
      }
      menuFilterResults.appendTo( $(this).parent() );
    });
    $(this).blur(function() {
      // if ( $(this).val() == '' ) { // original
      if ( !$(this).val() ) {
        $(this).val(searchDefaultText);
      }
      setTimeout( function() { menuFilterResults.detach(); }, 150 );
    });
  });

  menuFilterSearch.focus(function() {
    if ( $(this).val() == menuFilterDefaultText) {
      $(this).val('');
      $(this).removeClass('default-text');
    }
    menuFilter.addClass('active');
    menuFilterResults.appendTo( $(this).parent() );
  }).blur(function() {
    // if ( $(this).val() == '') { // original
    if ( !$(this).val() ) {
      $(this).val(menuFilterDefaultText);
      $(this).addClass('default-text');
    }
    menuFilter.removeClass('active');
    setTimeout( function() { menuFilterResults.detach(); }, 150 );
  });
  // Connect the pseudo-submit "Go" button to the actual search bar:
  menuFilterSubmit.click(function() {
    $('#search-api-page-search-form-search-site input[type="submit"]').click();
  });

  // load menus to be used for autocomplete terms:
  var potentialMenuLinks = $('#block-system-main-menu .content ul a, .region-off-screen-overlay .block-menu .content ul a');
  // var filteredMenuLinks = new Array(); // original
  var filteredMenuLinks = [];
  // var uniqueList = new Array(); // original
  var uniqueList = [];
  potentialMenuLinks.each(function() {
    // Some automatic curation of likely duplicates:
    if ( ($(this).text() == 'About') || ($(this).text() == 'About Us') ) {
      if ( $(this).attr('href').indexOf('/about/about-berklee') == -1 ) {
        return;
      }
    }
    if ( ($(this).text() == 'Contact') || ($(this).text() == 'Contact Us') ) {
      if ( $(this).attr('href').indexOf('/contact-berklee') == -1 ) {
        return;
      }
    }
    if ( uniqueList.indexOf( $(this).text() ) == -1) {
      uniqueList.push( $(this).text() );
      filteredMenuLinks.push( $(this) );
    }
  });
  menuLinks = $(filteredMenuLinks);

  var inputs = [menuFilterSearch, searchInput];

  $.each( inputs, function() {
    this.keyup(function( event ) {
      if ( $(this).hasClass('menu-filter-search') ) {
        searchInput.val( menuFilterSearch.val() );
        // if the user hits the enter key, populate the real search box and submit it:
        if ( event.which == 13 ) {
          $('#search-block-form input[type="submit"]').click();
        }
      } else {
        menuFilterSearch.val ( searchInput.val() );
      }
      // otherwise, if the user has typed more than 2 characters, autocomplete using
      // main menu links:
      var input = $(this).val();
      if (input.length > 1) {
        // clear the results:
        menuFilterResults.empty();
        var resultCount = 0;
        menuLinks.each(function() {
          $('div', this).remove(); // remove menu descriptions that are inside the anchor link
          if ( $(this).text().toLowerCase().indexOf( input.toLowerCase() ) > -1 ) {
            $(this).clone(false).appendTo(menuFilterResults);
            resultCount++;
          }
        });
        if (resultCount > 0) {
          menuFilterResults.height( (resultCount * 26) + 'px' );
          menuFilter.addClass('results');
          menuFilterResults.addClass('populated');
        } else {
          menuFilter.removeClass('results');
          menuFilterResults.removeClass('populated');
        }

      } else {
        menuFilterResults.empty();
        menuFilterResults.height('0px');
        menuFilter.removeClass('results');
        menuFilterResults.removeClass('populated');
      }
    });
  });
});