<?php if ($content): ?>
	<?php
		//by jsong
		drupal_add_library('system', 'jquery.cookie');
	?>
  <section class="<?php print $classes; ?>" <?php print $attributes ?> title="Click to collapse">
    <div class="container">
      <span class="close-button">X</span>
      <?php print $content; ?>
    </div>
    <span class="expand-button">&#xf078; Announcement</span>
  </section>
<?php endif; ?>
