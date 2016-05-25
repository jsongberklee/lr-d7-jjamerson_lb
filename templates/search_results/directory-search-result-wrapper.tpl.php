<?php 
if ($result['bundle'] == 'department') {
  $badge_title = 'Dept Page';
} elseif ($result['bundle'] == 'user') {
  $badge_title = 'Faculty Bio';
} else {
  $badge_title = 'Link';
}
?>
<li class="<?php print $classes; ?>"<?php //print $attributes; ?>>
  <?php print render($title_prefix); ?>
  <h3 class="title"<?php //print $title_attributes; ?>>
  	<?php if(empty($url)): ?>
  		<?php print $title; ?>
  	<?php else: ?>
      <?php print $title; ?><span class="badge <?php print $badge_title ?>"><a href="<?php print $url; ?>"><?php print $badge_title ?></a></span>
	<?php endif; ?>
  </h3>
  <?php print render($title_suffix); ?>
  <div class="search-snippet-info">
    <?php if ($snippet): ?>
      <p class="search-snippet"<?php //print $content_attributes; ?>><?php print $snippet; ?></p>
    <?php endif; ?>
    <?php if ($info): ?>
      <p class="search-info"><?php print $info; ?></p>
    <?php endif; ?>
  </div>
</li>