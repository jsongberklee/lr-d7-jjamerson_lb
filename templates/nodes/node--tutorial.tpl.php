<?php
global $language;
if (!$language_prefix = $language->prefix) {
  $language_prefix = 'und';
}

$body = check_markup($node->body[$language_prefix][0]['value'], 'full_html');

// Generate an iframe from the URL in the video field:
if ( isset ($node->field_video_url[$language_prefix][0]['safe_value']) ) {
  $video_url = $node->field_video_url[$language_prefix][0]['safe_value'];
}

if ($video_url) {
  $url_pattern = '@http(s)?://(?:.+)\.([^.]+)\.[^.]{3}/@';
  $matches = array();

  if (preg_match($url_pattern, $video_url, $matches)) {
    switch ($matches[2]) {
      case 'youtube' :
      default:
        $youtube_id_pattern = '@/watch\?v=([^&]+)@';
        $matches = array();
      
        if (preg_match($youtube_id_pattern, $video_url, $matches)) {
          $id = $matches[1];
          $url = 'https://www.youtube.com/embed/' . $id . '?rel=0&autohide=2';
        }
      
        if (isset($id)) {
          $video = '<iframe class="tutorial-video" width="570" height="325" src="' . $url  . '" frameborder="0" allowfullscreen></iframe>';
        }
        break;
    }
  }
}

$steps = '<div class="tutorial-steps">';
for ($i = 0; $i < count($node->field_step_title[$language_prefix]); $i++) {
  $steps .= '<div class="tutorial-step">';
  $steps .= '  <h3>' . $node->field_step_title[$language_prefix][$i]['safe_value'] . '</h3>';
  if ( isset( $node->field_step_image[$language_prefix][$i] ) ) {
    $steps .= '  <figure class="tutorial-step-image">' . 
              '  <img src="' . file_create_url( $node->field_step_image[$language_prefix][$i]['uri'] ) . '" overlay="true" />' .
              '  <i class="tutorial-zoom-icon fa fa-search-plus"></i>' .
              '</figure>';  
  }
  if ( isset( $node->field_step_text[$language_prefix][$i] ) ) {
    $steps .= '  <p>' . $node->field_step_text[$language_prefix][$i]['safe_value'] . '</p>';  
  }
  $steps .= '<div class="clearfix"></div>';
  $steps .= '</div>';
}
$steps .= '</div>';

$next = '';
if (count($node->field_related_tutorials) > 0) {
  $next .= '<div class="related-tutorials">';
  $next .= "<h3>What's Next?</h3>";
  foreach($node->field_related_tutorials[$language_prefix] as $related_tutorial) {
    $link = '/node/' . $related_tutorial['entity']->nid;
    //$link = drupal_get_path_alias($link);
    $next .= "<div class='related-tutorial'>";
    $next .= '  <figure class="related-tutorial-image">' . 
              "  <a href='{$link}'>" . 
              '    <img src="' . file_create_url( $related_tutorial['entity']->field_image[$language_prefix][0]['uri'] ) .  '" />' . 
              '  </a>' .
              '</figure>';  
    $next .= "<h4> <a href='{$link}'>" . $related_tutorial['entity']->title . '</a></h4>';
    if ($related_tutorial['entity']->body[$language_prefix][0]['summary']) {
      $next .= '<p>' . $related_tutorial['entity']->body[$language_prefix][0]['summary'] . '</p>';
    }
    $next .= '</div>';
  }
  $next .= '</div>';
}

?><div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
    <div class="content"<?php print $content_attributes; ?>>
    <?php
      echo $body;
      echo $video;
      echo $steps;
      echo $next;
    ?>
  </div>
</div>