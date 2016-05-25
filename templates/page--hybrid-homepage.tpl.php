<?php
global $language;
if (!$language_prefix = $language->prefix) {
  $language_prefix = 'und';
}

if ( function_exists('workbench_moderation_node_current_load') ) {
  if ( substr($_GET['q'], -strlen('/draft') ) === '/draft' ) {
    $current_node = workbench_moderation_node_current_load($node);
  } else {
    $current_node = $node;
  }  
}

$nid = $node->nid;  


// Setup the stripes region manually, if it doesn't already exist
if ( count($page['stripes']) == 0 ) {
  $page['stripes'] = array(
    '#sorted' => TRUE,
    '#theme_wrappers' => array(0 => 'region'),
    '#region' => 'stripes',
  );
}

// Wrap HH "block" field content with their respective CSS classes (from the "Block Class" field) and
// move blocks with the class 'width-full' to the stripes region
for ($i = 0; $i < count($current_node->field_block_class[$language_prefix]); $i++ ) {
  if ( isset($current_node->field_block_class[$language_prefix][$i]) ) {
    $block_classes = $current_node->field_block_class[$language_prefix][$i]['safe_value'];
    if ( strpos($block_classes, 'width-full') > -1) {
      $page['stripes']["field_block_{$i}"] = $page['content']['system_main']['nodes'][$nid]['field_block'][$i];
      $page['stripes']["field_block_{$i}"]['#markup'] = "<div class='{$block_classes}'>" . $page['content']['system_main']['nodes'][$nid]['field_block'][$i]['#markup'] . "</div>";
      unset($page['content']['system_main']['nodes'][$nid]['field_block'][$i]);  
    } else {
      $page['content']['system_main']['nodes'][$nid]['field_block'][$i]['#markup'] = "<div class='{$block_classes}'>" . $page['content']['system_main']['nodes'][$nid]['field_block'][$i]['#markup'] . "</div>";      
    }
    
  } else {
    break;
  }
} 

$content = $page['content'];

$date_format = variable_get('date_format_event_date');

// Stitch the slides together:
$slider_images = $current_node->field_hero_image[$language_prefix];
$slider_titles = $current_node->field_title[$language_prefix];
$slider_dates = $current_node->field_slider_date[$language_prefix];
$slider_teasers = $current_node->field_slider_teaser[$language_prefix];
$slider_links = $current_node->field_link[$language_prefix];

$slider = "<div class='banner slider'>";
$sliders = '';
$slider_nav = "<div class='slider-nav'><div class='container'>";
$slide_pos = 0;

$rewrite_node = false;
foreach ($slider_images as $index => $slider_image) {
  // do the date stuff first, so we can ignore slides with an expiration date:
  $slider_date_formatted = false;
  if ( isset( $slider_dates[$index] )) {
    $date = $slider_dates[$index];
    // Check if it's expired:
    if ( isset( $date['value2'] ) ) {
    $expiration_date = strtotime($date['value2']);
      if ( time() > $expiration_date) {
        unset( $current_node->field_hero_image[$language_prefix][$index] );
        unset( $current_node->field_title[$language_prefix][$index] );
        unset( $current_node->field_slider_date[$language_prefix][$index] );
        unset( $current_node->field_slider_teaser[$language_prefix][$index] );
        unset( $current_node->field_link[$language_prefix][$index] );
        $rewrite_node = true;
        continue;
      }
    }
    // Convert from the database time zone to the user's time zone.
    $date_object = new DateObject($date['value'], new DateTimeZone($date['timezone_db']));
    $date_object->setTimezone(new DateTimeZone($date['timezone']));
    
    $slider_date_formatted = date_format_date($date_object, 'custom', $date_format); //date($date_format, strtotime($slider_dates[$index]['value']) );  
  } 
  $slider_title_formatted = $slider_titles[$index]['value'];

  $slide_pos += 1;
  if ($slide_pos === 1) {
    $navClass = 'active';
    $slideClass = 'first active';
  } else {
    $navClass = '';
    $slideClass = '';
  } 

  $slide = "<div class='slide {$slideClass}' number='{$slide_pos}'>";
  $image = theme_image_formatter(
          array('item' => $slider_image,
              'image-style' => 'original'
          ));
  $slide .= "<div class='slide-image image-cover'>{$image}</div>";
  $slide .= "<div class='slide-info-container'>";
  $slide .= "  <h2 class='slide-title'>{$slider_title_formatted}</h2>";
  if ($slider_date_formatted) {
    $slide .= "  <p class='slide-date'>{$slider_date_formatted}</p>";
  }
  $slide .= "  <p class='slide-teaser'>{$slider_teasers[$index]['safe_value']}</p>";
  if ($slider_links[$index]['url']) {
    $slide .= "  <a href='{$slider_links[$index]['url']}' class='slide-link button'>{$slider_links[$index]['title']}</a>";
  }
  $slide .= '</div>'; //closes slide-info-container
  $slide  .= '</div>'; // closes slide
  $sliders .= $slide;

  
  $slider_nav .= "<div class='sbutton {$navClass}' target-slide='{$slide_pos}' title='{$slider_titles[$index]['safe_value']}'>{$slide_pos}</div>";
}
$slider_nav .= "</div></div>";
if( count($slider_images) < 2) {
  $slider_nav = '';
}
$slider .= $slider_nav;
$slider .= $sliders;
$slider .= "</div>";

if ($rewrite_node && user_access('Hybrid Homepage: Edit any content')) {
  $current_node->field_hero_image[$language_prefix] = array_values($current_node->field_hero_image[$language_prefix]);
  $current_node->field_title[$language_prefix] = array_values($current_node->field_title[$language_prefix]);
  $current_node->field_slider_date[$language_prefix] = array_values($current_node->field_slider_date[$language_prefix]);
  $current_node->field_slider_teaser[$language_prefix] = array_values($current_node->field_slider_teaser[$language_prefix]);
  $current_node->field_link[$language_prefix] = array_values($current_node->field_link[$language_prefix]);

  node_save($current_node);
}


include('keyboard_quicklinks.inc.php');
echo render($page['page_top']);  
echo render($page['emergency_bar']);
echo render($page['top_nav']); 
echo render($page['header']); 
echo render($page['help']);

echo '<section id="off-screen" class="hidden">';
echo   '<nav id="off-screen-sidebar" class="closed">';
echo     render($page['off_screen_sidebar']);
echo   '</nav>';
echo   render($page['off_screen_overlay']);
echo '</section>';

echo '<section id="page" class="open">';
echo $slider;
echo ($tabs ? '<div class="container tabs-container">'.render($tabs).'</div>' : '');
echo    $messages;
echo    '<div class="row">';
echo      render($page['sidebar_first']);
echo      '<article id="page-content" role="main" class="article">';
echo        ($title ? "<h1 id='page-title'>{$pretitle}{$title}</h1>{$subtitle}" : '');
echo        render($page['content_top']);    
echo        render($page['content']);        
echo        render($page['content_bottom']);   
echo      '</article>';
echo      render($page['sidebar_second']); 
echo    '</div>';
echo   render($page['stripes']);
echo   render($page['footer']);
echo '</section>';

