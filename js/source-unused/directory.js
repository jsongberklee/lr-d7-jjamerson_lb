jQuery(document).ready(function($){
  if ( !$('body').hasClass('page-directory') ) {
    return false;
  }
  $('body.page-directory .search-results > li').hover(function(){
    //$(this).find('.actions').show();
  }, function(){
    //$(this).find('.actions').hide();
  });
  // wait for click on a person's "report discrepancy" link
  $('body.page-directory .search-results .user a.report-discrepancy').click(function(e){
    // store uid of person (or modify urls within interstitial box)
    var uid = $(this).attr('data-discrepancy-id');
    var parentResult = $(this).closest('li');
    parentResult.addClass('selected-for-report');
    // move interstitial box into position (center on link, keep on screen) and show it
    $('#directory-discrepancy-router').dialog({
      modal: true,
      dialogClass: 'discrepancy-router-dialog',
      position: {my: 'top', at: 'bottom', of: parentResult, collision: 'flip'},
      close: function( event, ui ) {
        parentResult.removeClass('selected-for-report');
      }
    });
    $('#directory-discrepancy-router .action-links a').click(function(){
      $(this).attr('href',$(this).attr('href') + '?uid=' + uid);
      return true;
    });
    return false;
  });

  // Advanced Search button stuff
  // Add 'clear' button:
  var clearButton = $('<div />')
    .addClass('clear button')
    .html('Clear Form')
    .click(function(){
      //$('#block-system-main #search-form')[0].reset();
      $("#block-system-main #edit-advanced :input").each(function() {
        $(this).val('');
      });
      $('#edit-basic #edit-keys').val('');
    });
  var pseudoSubmit = $('<div />')
    .addClass('pseudo-submit button')
    .html('Search')
    .click(function() {
      $('#edit-basic #edit-keys').val( keywordsInput.val() );
      $('#edit-submit').click();
    });
  var keywords = $('<div />').addClass('pseudo-form-item form-type-textfield form-item-keywords');

  keywordsInput = $('<input />')
    .addClass('form-text')
    .attr('type', 'text')
    .attr('id', 'edit-keywords')
    .attr('name', 'advanced_keywords')
    .attr('size', '60').attr('maxlength', '128')
    .keypress(function() {
      $('#edit-basic #edit-keys').val( $(this).val() );
    });
  keywordsLabel = $('<label />').attr('for', 'advanced_keywords').html('Keywords');
  keywords.append(keywordsLabel);
  keywords.append(keywordsInput);
  keywords.insertAfter( $('.form-type-select.form-item-type') );
  $('body.page-directory #edit-advanced .fieldset-wrapper').append(pseudoSubmit).append(clearButton);

  // alter the text
  var titleA = 'Advanced Search';
  var titleB = 'Standard Search';
  $('a.fieldset-title').click(function(){
    if ( $(this).html() == titleB ) {
      $(this).html(titleA);
      $('#edit-basic .form-item-keys, #edit-basic .directory-tooltip').fadeIn(100);
      $('#edit-submit').removeClass('advanced-open');
      var keywords = keywordsInput.val();
      clearButton.click();
      $('#edit-basic #edit-keys').val( keywords );
    } else {
      hideBasic();
    }
  });

  var basicFormVal = '';
  function hideBasic() {
    $('body.page-directory a.fieldset-title').html(titleB);
    //$('#edit-basic').fadeOut(100);
    $('body.page-directory #edit-basic .form-item-keys, #edit-basic .directory-tooltip').fadeOut(100);
    $('body.page-directory #edit-submit').addClass('advanced-open');
    basicFormVal = $('body.page-directory #edit-basic #edit-keys').val();
    keywordsInput.val( $('body.page-directory #edit-basic #edit-keys').val() );
    $('body.page-directory #edit-basic #edit-keys').val();
  }


  // Check whether Advanced Search fields are in play:
  var advancedOpen = false;
  // Reset the form first, to account for back button voodoo:
  if ( $("body.page-directory form#search-form").length > 0 ) {
    $("body.page-directory form#search-form")[0].reset();

  }
  $("body.page-directory #edit-advanced :input").each(function() {
    if ($(this).is('select')) {
      // Compare the current value against the first (default) value:
      if ( $(this).val() != $(this).find("option:first").val() ) {
        advancedOpen = true;
      }
    } else {
      //if($(this).val() != "") { //original
      if($(this).val()) {
        advancedOpen = true;
      }
    }
  });
  if (advancedOpen) {
    hideBasic();
    //location.href = "#search-results"; // original
    location.href = "#search-results"; // by jsong
    //$('html,body').animate({scrollTop: $('#search-results').offset().top});
  }

});