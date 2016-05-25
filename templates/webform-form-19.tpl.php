<?php

/**
 * @file
 * Customize the display of a complete webform.
 *
 * This file may be renamed "webform-form-[nid].tpl.php" to target a specific
 * webform on your site. Or you can leave it "webform-form.tpl.php" to affect
 * all webforms on your site.
 *
 * Available variables:
 * - $form: The complete form array.
 * - $nid: The node ID of the Webform.
 *
 * The $form array contains two main pieces:
 * - $form['submitted']: The main content of the user-created form.
 * - $form['details']: Internal information stored by Webform.
 *
 * If a preview is enabled, these keys will be available on the preview page:
 * - $form['preview_message']: The preview message renderable.
 * - $form['preview']: A renderable representing the entire submission preview.
 */
drupal_add_js('

	jQuery(window).load(function(){

		var book = jQuery("fieldset.webform-component--request-book"); book.hide();
		var score = jQuery("fieldset.webform-component--request-score"); score.hide();
		var journal = jQuery("fieldset.webform-component--request-journal"); journal.hide();
		var other = jQuery("fieldset.webform-component--request-other"); other.hide();
		var aboutYou = jQuery("fieldset.webform-component--personnel-info"); aboutYou.hide();

		var returnDate = jQuery(".webform-component--admin-fields--date-return");
		var lost = jQuery("#edit-submitted-admin-fields-return-status-2");
		var returned = jQuery("#edit-submitted-admin-fields-return-status-1");
		if(lost.is(":checked")) returnDate.hide();

		function hide_all(){
			book.hide();
			score.hide();
			journal.hide();
			other.hide();
		}

		function hide_return_date(){
			returnDate.hide();
		}

		jQuery.each(jQuery("form.webform-client-form-19 fieldset.webform-component--what-type-of-item-are-you-looking-for input"), function() {

        if(jQuery(this).is(":checked")){
        	eval(jQuery(this).val()).show();
	        	aboutYou.show();
        }

    }).click(function (){

    	hide_all();
    	eval(jQuery(this).val()).show();
    	aboutYou.show();

    });

    returned.click(function (){

    	if(returned.is(":checked")){returnDate.show();}

    });
    lost.click(function (){

    	if(lost.is(":checked")){returnDate.hide();}

    });

	});

','inline');

?>
<?php
  // Print out the progress bar at the top of the page
  print drupal_render($form['progressbar']);

  // Print out the preview message if on the preview page.
  if (isset($form['preview_message'])) {
    print '<div class="messages warning">';
    print drupal_render($form['preview_message']);
    print '</div>';
  }

  // Print out the main part of the form.
  // Feel free to break this up and move the pieces within the array.
  print drupal_render($form['submitted']);

  // Always print out the entire $form. This renders the remaining pieces of the
  // form that haven't yet been rendered above (buttons, hidden elements, etc).
  print drupal_render_children($form);
