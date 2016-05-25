jQuery(document).ready(function($){

  var tutorial_filters = $('#views-exposed-form-tutorials-fts');
  var location = $('#edit-location-wrapper');
  var category = $('#edit-category-wrapper');

  if ( $('select', category).val() != '209396' ) {
    location.addClass('visuallyhidden');
  } 
  $('select', category).change(function() {
    if ( $(this).val() == '209396' && location.hasClass('visuallyhidden')) {
      location.removeClass('visuallyhidden');
    } else {
      $('select', location).val('All');
      location.addClass('visuallyhidden');
    }
  });

});