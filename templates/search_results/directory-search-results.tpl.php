<?php if ($search_results): ?>
  <h2 id="search-results"><?php print t('Search results');?></h2>
  <ol class="search-results berklee-directory-results">
    <?php print $search_results; ?>
    <?php print $requeue_form; ?>
  </ol>
  <?php if (isset($pager)) { print $pager; } ?>
<?php elseif($searched) : ?>
  <h2><?php print t('Your search yielded no results');?></h2>
  <?php print search_help('search#noresults', drupal_help_arg()); ?>
<?php endif; ?>
