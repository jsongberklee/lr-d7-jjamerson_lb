<?php

/**
 * @file
 * Customize the navigation shown when editing or viewing submissions.
 *
 * Available variables:
 * - $node: The node object for this webform.
 * - $mode: Either "form" or "display". May be other modes provided by other
 *          modules, such as "print" or "pdf".
 * - $submission: The Webform submission array.
 * - $submission_content: The contents of the webform submission.
 * - $submission_navigation: The previous submission ID.
 * - $submission_information: The next submission ID.
 */

//dpm($node->nid);
if($node->nid == 19){

drupal_add_js('

	jQuery(window).load(function(){
		var other = jQuery("fieldset.webform-component--request-other"); other.hide();
		var book = jQuery("fieldset.webform-component--request-book"); book.hide();
		var score = jQuery("fieldset.webform-component--request-score"); score.hide();
		var journal = jQuery("fieldset.webform-component--request-journal"); journal.hide();
		var itemType = jQuery("fieldset.webform-component--what-type-of-item-are-you-looking-for div.webform-component--what-type-of-item-are-you-looking-for--item-type");
		itemType.find("label").remove();
		var itemTypeText = itemType.text().trim();
		switch(itemTypeText){
			case "Journal Article":
				journal.show();
				break;
			case "Book":
				book.show();
				break;
			case "Score":
				score.show();
				break;
			case "Other":
				other.show();
				break;
		}
		jQuery("fieldset.webform-component--what-type-of-item-are-you-looking-for").hide();
	});
','inline');

}

?>

<?php if ($mode == 'display' || $mode == 'form'): ?>
  <div class="clearfix">
    <?php print $submission_actions; ?>
    <?php print $submission_navigation; ?>
  </div>
<?php endif; ?>

<?php print $submission_information; ?>

<div class="webform-submission">
  <?php print render($submission_content); ?>
</div>

<?php if ($mode == 'display' || $mode == 'form'): ?>
  <div class="clearfix">
    <?php print $submission_navigation; ?>
  </div>
<?php endif; ?>
