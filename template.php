<?php

/**
 * Implementing hook_process_page()
 */
function jjamerson_lb_preprocess_page(&$variables) {
  $base_theme = 'jjamerson';

  global $base_url, $base_path, $user;
  global $_bss_section_information;

//pasing the logged in user information to the third party website (Libapps, Classreserves, Catalog?)
/*
	if(user_is_logged_in()){
		$loginUser = user_load($user->uid);
		$displayName = $loginUser->field_display_name['und'][0]['safe_value'];
		$colleagueId = $loginUser->field_berklee_id['und'][0]['safe_value'];
		$roles = implode(",", $loginUser->roles);
		$currentTime = date('m/d/Y g:ia', time());
		//drupal_add_js(array('jj_lb_user' => array('displayname' => $displayName, 'roles' => $roles, 'collegueid' => $colleagueId , 'currenttime' => $currentTime )), array('type' => 'setting'));
		drupal_add_js('
			jQuery(document).ready(function(){
				jQuery("#block-menu-menu-menu-login-menu").append("<ul id=\"login-user-info\" style=\"display:none\"><li>'.$displayName.'</li><li>'.$roles.'</li><li>'.$colleagueId.'</li><li>'.$currentTime.'</li></ul>");
			});
			', 'inline');
	}
*/


  if ( isset($_bss_section_information['tid']) ){
    $site_section_tid = $_bss_section_information['tid'];
  }

  if ( isset($node['language']) ) {
    $lang = $node['language'];
  } else {
    $lang = 'und';
  }

  // Set a current node variable, because otherwise actions that rely on field data will affect
  // both the draft & published version of the page.
  if ( function_exists('workbench_moderation_node_current_load') ) {
    if ( substr($_GET['q'], -strlen('/draft') ) === '/draft' ) {
      $current_node = workbench_moderation_node_current_load($variables['node']);
    } else {
      $current_node = $variables['node'];
    }
  } else {
    // by jsong
    // original $current_node = $variables['node'];
    $current_node = null;
  }

  // Check to see if this is a stand-alone site section. First we fetch the site section ID, if
  // it's available (if the section menu block exists, then it should be available):
  if ( isset($variables['page']['sidebar_first']['berklee_site_section_menu']['#block']->tid) ) {
    $site_section = taxonomy_term_load($site_section_tid);
    $section_parent_link = drupal_get_path_alias('node/'.$_bss_section_information['parent_node_id']);
  }

  /* If this is a development environment, rebuild the minified JS file.
     For the automatic JS minification to occur:
     1. PHP needs write permissions for /js/jjamerson_lb.min.js
     2. The development_environment variable needs to be defined. You can do this
        either in a local settings file (e.g. settings.local.php) or via a drush command:
        drush vset development_environment 1
  */
  $dev_env = variable_get('development_environment');
  if ( $dev_env == 1) {
    //require_once drupal_get_path('theme', $base_theme) . '/third-party/class.JavaScriptPacker.php';

    $source = '';
    if ( $directory_handle = opendir( dirname(__FILE__) . '/js/source') ) {
      while ( ($file = readdir($directory_handle) ) !== false) {
        // Only load JS extensions. This prevents issues with the packer loading .DS_Store files, at the least.
        if ( substr($file, -3) === '.js') {
          $source .= file_get_contents(  dirname(__FILE__) . '/js/source/' . $file) . "\n";
        }
      }
      closedir($directory_handle);
    }
    $packer = new JavaScriptPacker($source, 'None', true, false);
    $packed = $packer->pack();
    file_put_contents( dirname(__FILE__) . '/js/jjamerson_lb.min.js', $packed);
  } elseif ( $dev_env == 2) {
    /* Alternatively, just add each JS file via drupal_add_js. Better for hunting down errors because
       the console will tell you the offending file & line number: */
    if ( $directory_handle = opendir( dirname(__FILE__) . '/js/source') ) {
      while ( ($file = readdir($directory_handle) ) !== false) {
        if ( preg_match('/.js$/', $file) ) {
          drupal_add_js( path_to_theme() . '/js/source/' . $file );
        }
      }
      closedir($directory_handle);
    }
  }

  if (drupal_is_front_page() ) {
    //drupal_add_js('//maps.googleapis.com/maps/api/js?libraries=geometry', array('type'  => 'external'));

    // VideoJS components:
    //drupal_add_js('//vjs.zencdn.net/4.12/video.js', array('type'  => 'external', 'scope' => 'footer'));
    //drupal_add_css('//vjs.zencdn.net/4.12/video-js.css', array('type'  => 'external'));

    /* inline JS for aligning <div> height matches with other divs, compatible with all major browsers, by jsong */
    /* on click attached to the selected blocks to make the whole area is clickable, by jsong */
    /* place Recent Acquisition and Feature blocks before the social buttons on mobile phone (< 479) */
    drupal_add_js('
			jQuery(window).bind("load resize",function(e){
				var $bcmW = jQuery(e.target).width();
				if($bcmW > 767){
					var $t = jQuery(".front-block.top-resources");
					var $a = jQuery(".front-block.services");
					var $th = (($a.outerHeight() + $a.offset().top) - $t.offset().top)+"px";
					$t.height($th);
				}else if(($bcmW < 767) && ($bcmW > 479)){
					var $t = jQuery(".front-block.top-resources");
					var $a = jQuery(".front-block.libguides");
					var $th = (($a.outerHeight() + $a.offset().top) - $t.offset().top)+"px";
					$t.height($th);
				}else if($bcmW < 479){
					jQuery("#block-block-20").after(jQuery("#block-block-27"));
					jQuery("#block-block-27").after(jQuery("#block-views-b3ece016db0169908c14ac469f1bacd4"));
				}
			});

			jQuery(document).ready(function(){
				jQuery("#block-block-12, #block-block-25, #block-block-13, #block-block-11, #block-block-20, #block-block-16, #block-block-10, #block-block-17, #block-block-18, #block-block-26, #block-block-27, #block-block-28").click(function(e){
					window.location.href = jQuery(this).find("a").attr("href");
				});
			});
			', 'inline');
  }

  /* Allows you to use node-type, and node ID base page templates: */
  if (!empty($variables['node'])) {
    $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->type;
    $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->vid;
  } //Adds custom 404 error page template
  elseif (drupal_get_http_header('status')) {
    $variables['theme_hook_suggestions'][] = 'page__404';
  }

  /* Place logo: */
  if ( isset($_GET['render-only']) && isset($_GET['unlink-logo']) ) {
    $logo = "<img src='{$variables['logo']}' alt='Berklee logo' id='logo'>";
  } else {
    $logo = l("<img src='{$variables['logo']}' alt='Berklee Logo' title='Berklee Homepage'>", $base_url . $base_path, array(
      'attributes' => array(
        'id' => 'logo',
        'rel' => 'home',
        'title' => t('Home'),
        'tabindex' => '1',
      ),
      'html'  => TRUE,
    ));
  }

  $logo_array = array(
    'logo' => array(
      '#markup' => $logo,
      '#type' => 'markup',
      '#weight' => -100,
    )
  );

  $share_links = array(
    'share_links' => array(
      '#markup' =>
        '<div class="share-holder">
          <!-- AddThis Button BEGIN -->
          <div class="addthis_toolbox addthis_default_style ">
          <a class="addthis_button_facebook_like at300b" fb:like:layout="button_count"></a>
          <a class="addthis_counter addthis_pill_style"></a>
          </div>
          <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
          <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fbe4da869545ef5"></script>
          <!-- AddThis Button END -->
        </div>',
      '#type' => 'markup',
      '#weight' => 1001,
    )
  );

  if ( isset($variables['page']['header']) && is_array($variables['page']['header']) ) {
    $variables['page']['header'] = $logo_array + $variables['page']['header']; //+ $share_links; // by jsong, it makes the site slow sometimes
  }

  /* Add a pretitle page variable, primarily for the sponsorship field */
  $variables['pretitle'] = '';
  if ($variables['page']['#type'] === 'page' && isset ( $variables['node'] )) {
    if ( isset($variables['node']->field_sponsorship) && count($variables['node']->field_sponsorship) ) {
      $variables['pretitle'] = $variables['node']->field_sponsorship[$lang][0]['safe_value'];
      $variables['pretitle'] = "<span class='pretitle'>{$variables['pretitle']}</span>";
    }
  }

  /* Add a subtitle page variable */
  $variables['subtitle'] = '';
  if ( isset($variables['node']) && $variables['node']->type === 'bt_cover') {
    $variables['pretitle']  = "<span class='pretitle previous-link'>";
    $variables['pretitle'] .= "<a href='/alumni'>Alumni Affairs</a>/";
    $variables['pretitle'] .= "<a href='/berklee-today/archives'>See All BT Issues</a>";
    $variables['pretitle'] .= "</span>";
    $variables['subtitle'] = "<span class='subtitle'>A Magazine for Contemporary Music and Musicians</span>";
  }
  if ( isset($variables['node']) && $variables['node']->type === 'bt_article') {
    if ( isset( $variables['page']['sidebar_first']['berklee_site_section_breadcrumb'] ) ) {
      $new_crumb = '<ul class="breadcrumb"><li><a href="/alumni">Alumni Affairs</a></li><li><a href="/berklee-today/archives">All BT Issues</a></li></ul>';
      $variables['page']['sidebar_first']['berklee_site_section_breadcrumb']['#markup'] = $new_crumb;
    }
  }
  if ( isset($variables['theme_hook_suggestions'][0]) && $variables['theme_hook_suggestions'][0] == 'page__directory') {
    $variables['title'] = 'Berklee Directory';
    $variables['subtitle'] = "<span class='subtitle'>Faculty, Staff, and Departments</span>";
  }

  /* Return unformatted content if notemplate variable is defined */
  if ( isset($_GET['content-only']) ) {
    unset($variables['page']['header']);
    unset($variables['page']['top_nav']);
    unset($variables['page']['offscreen_sidebar']);
    unset($variables['page']['offscreen_overlay']);
    unset($variables['page']['sidebar_first']);
    unset($variables['page']['sidebar_second']);
    unset($variables['page']['content']['berklee_info_block_berklee_info_block']);
    unset($variables['page']['page_top']);
    unset($variables['page']['page_bottom']);
    unset($variables['page']['footer']);
    /* check if this is an ajax request. if so, return just the page content: */
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo drupal_render( $variables['page']['content'] );
      echo drupal_render( $variables['page']['content_bottom'] );
      die;
    }
  }

  // Check to see if this page can be used as a lead page:
  if ( isset($site_section->field_base_url) && isset($site_section->field_banner_image_large[$lang][0]['uri']) && $site_section->description ){
    $site_section_home = $site_section->field_base_url[$lang][0]['value'];
    if ( drupal_get_path_alias() == $site_section_home) {
      $header_image = $site_section->field_banner_image_large[$lang][0]['uri'];
      $header_image_src = file_create_url( $header_image );

      // store the image source in an array element in the header, to be used by marshall_preprocess_region()
      // to set the background image:
      $variables['page']['header']['#lead-page']['header-image'] = $header_image_src;

      $summary = $site_section->description;
      $header_copy = "<div class='lead-page-intro'><h1>{$site_section->name}</h1><div class='lead-page-summary'>{$summary}</div></div>";
      $header_copy_array = array(
        'lead-page-intro' => array(
          '#markup' => $header_copy,
          '#type' => 'markup',
          '#weight' => -100,
        )
      );
      $variables['page']['header'] += $header_copy_array;
    }
  }
}

function jjamerson_lb_process_page(&$variables) {
  if ($variables['title'] == 'Access denied') {
    if ( user_is_logged_in() == false) {
      $variables['title'] = 'Login Required';
    } else {
      $variables['title'] = 'Higher Level Access Required';
    }
  }
  if ($variables['title'] == 'Berklee Directory' && user_is_logged_in() == false) {
    $variables['title'] = '';
    $variables['page']['content']['system_main']['main']['#markup'] = '';
  }
}

function jjamerson_lb_preprocess_region(&$region) {
  if ( isset($node['language']) ) {
    $lang = $node['language'];
  } else {
    $lang = 'und';
  }

  $row_div_prefix = '<div class="row">';
  $row_div_suffix = '</div>';
  switch($region['region']) {
    case 'header';
      global $_bss_section_information;
      if ( isset($_bss_section_information['tid']) ){
        if ( isset($region['elements']['#lead-page']['header-image']) ) {
          $image_src = $region['elements']['#lead-page']['header-image'];
          $region['attributes_array']['style'] .= " background-image: url({$image_src}); ";
        } else {
          global $_bss_section_information;
          $site_section_tid = $_bss_section_information['tid'];
          $site_section = taxonomy_term_load($site_section_tid);
          if ( isset($site_section->field_banner_image[$lang][0]) ) {
            $image_src = file_create_url( $site_section->field_banner_image[$lang][0]['uri'] );

            $region['attributes_array']['style'] .= " background-image: url({$image_src}); ";
          }
        }
      }
      break;
  }
}

/**
 * Implementing hook_preprocess_html()
 */
function jjamerson_lb_preprocess_html(&$variables) {

	header('Access-Control-Allow-Origin: *');

  /* Override head title on unique site sections: */
  global $_bss_section_information;
  $site_section = false;
  if (isset($_bss_section_information['tid'])) {
    $site_section_tid = $_bss_section_information['tid'];
    $site_section = taxonomy_term_load($site_section_tid);
  }

  if ( isset($node['language']) ) {
    $lang = $node['language'];
  } else {
    $lang = 'und';
  }

  if ( $site_section && isset($site_section->field_base_url['und'][0]['value']) ) {
    // Check to see if this is the homepage of the site section. If so, just use the site section name.
    // If not, use the current page title + the site section name.
    if ( drupal_get_path_alias() === $site_section->field_base_url['und'][0]['value'] ) {
      $variables['head_title'] = $site_section->name;
      if ( empty($site_section->field_standalone['und'][0]['value']) ) {
        $variables['head_title'] .= ' | Stan Getz Library';
      }
    } else {
      if ( isset($site_section->field_standalone['und'][0]['value']) && $site_section->field_standalone['und'][0]['value'] ) {
        $variables['head_title'] = $variables['head_title_array']['title'] . ' | ' . $site_section->name;
      }
    }
  }

  /* Override title on front page, because for some reason a pipe is being inserted into it */
  if ( $variables['is_front'] ) {
    $variables['head_title'] = 'Stan Getz Library @ Berklee College of Music';
  }

  /* Add classes related to workbench moderation */
  if (isset($variables['page']['content']['system_main']['nodes'])) {
    $nodes = $variables['page']['content']['system_main']['nodes'];
    if (count($nodes) === 2) {
      $not_really_the_node = current($nodes);
      $node = $not_really_the_node['#node'];
      if ( isset($node->workbench_moderation['published']) && $node->workbench_moderation['published']->vid === $node->vid) {
        $workbench_moderation = $node->workbench_moderation['published'];
      } else {
        if ( isset($node->workbench_moderation) ) {
          $workbench_moderation = $node->workbench_moderation['current'];
        }
      }
      if (isset($workbench_moderation) && $workbench_moderation->state) {
        $variables['classes_array'][] = 'workbench-state-'.$workbench_moderation->state;
        if ($workbench_moderation->published) {
          $variables['classes_array'][] = 'workbench-published';
        } else {
          $variables['classes_array'][] = 'workbench-unpublished';
        }
      }
    }
  }

  /* Add tags on node as classes: */
  if (isset($variables['page']['content']['system_main']['nodes'])) {
    $nodes = $variables['page']['content']['system_main']['nodes'];
    if ( count($nodes) === 2) {
      $not_really_the_node = current($nodes);
      if ( isset($not_really_the_node['#node']) ) {
        $node = $not_really_the_node['#node'];
        if ( isset($node->field_isotope_tags[$lang]) && count($node->field_isotope_tags[$lang]) ) {
          $tids = array();
          foreach( $node->field_isotope_tags[$lang] as $tid) {
            $tids[] = $tid['tid'];
          }
          try {
            $tags = taxonomy_term_load_multiple( $tids );

            foreach ($tags as $tag) {
              $tag_name = strtolower($tag->name);
              $tag_name = preg_replace('/[^a-z0-9 -]+/', '', $tag_name);
              $tag_name = preg_replace('/\s/', '-', $tag_name);
              $tag_name = 'tagged-' . $tag_name;
              $variables['classes_array'][] = " {$tag_name}";
            }
          } catch (Exception $e) {
            // do nothing
          }
        }
      }
    }
  }

  if ( isset($site_section->field_base_url) && isset($site_section->field_banner_image_large[$lang][0]['uri']) && $site_section->description ) {
    $site_section_home = $site_section->field_base_url[$lang][0]['value'];
    if ( drupal_get_path_alias() == $site_section_home) {
      $variables['classes_array'][] = "lead-page";
    }
  }

  /* Add a class to landing pages if the call-to-action field is empty: */
  if (isset($node) && $node->type === 'landing' && count($node->field_call_to_action) === 0 ) {
    $variables['classes_array'][] = 'empty-call-to-action';
  }

  /* Add a class to distinguish the Directory's starting page: */
  if ( function_exists('apachesolr_search_page_load') ) {
    $directory_search = apachesolr_search_page_load('directory');
    // Get the path from the page info
    $directory_path = $directory_search['search_path'];
    if ($directory_path === current_path() ) {
      $variables['classes_array'][] = 'directory-front';
    }
  }

  /* Add the site section TID */
  global $_bss_section_information;
  if ( $_bss_section_information ) {
    $variables['classes_array'][] = 'section-' . $_bss_section_information['tid'];
  }

  if ( isset($_GET['content-only']) ) {
    $variables['classes_array'][] = 'content-only-display';
  }

  if ( isset($_GET['render-only']) ) {
    $variables['classes_array'][] = 'render-only render-only-' . $_GET['render-only'];
    unset($variables['classes_array']['logged-in']);
  }

  if ( isset($_GET['iframed']) ) {
    $variables['classes_array'][] = 'iframed';
    $target = array(
      '#type' => 'markup',
      '#markup' => '<base target="_blank" />',
    );
    drupal_add_html_head($target, 'jjamerson_lb-iframe-target');
  }

  /* Check for user testing variables and set a timestamp array variable  */
  $user_testing = false;
  if ( isset($_GET['user-testing']) ) {
    $user_testing = true;
    $_SESSION['berklee-user-testing']['last-used'] = time();
  }

  /* Check to see if the 'berklee-user-testing' variable can be deleted */
  elseif ( isset($_SESSION['berklee-user-testing']['last-used']) ) {
    $user_testing_seconds = 1200; // 20 minutes
    if (time() - $_SESSION['berklee-user-testing']['last-used'] > $user_testing_seconds) {
      unset($_SESSION['berklee-user-testing']);
    }
  }

  if ( isset($_GET['htln']) || isset($_GET['hide-top-left-nav']) || isset($_SESSION['berklee-user-testing']['hide-top-left-nav']) ) {
    $variables['classes_array'][] = 'hide-top-left-nav';
    if ($user_testing) {
      $_SESSION['berklee-user-testing']['hide-top-left-nav'] = true;
    }

  }

  if ( isset($_GET['hct']) || isset($_GET['hide-campus-tools']) || isset($_SESSION['berklee-user-testing']['hide-campus-tools']) ) {
    $variables['classes_array'][] = 'hide-campus-tools';
    if ($user_testing) {
      $_SESSION['berklee-user-testing']['hide-campus-tools'] = true;
    }
  }

  if ( isset($_GET['hs']) || isset($_GET['hide-search']) || isset($_SESSION['berklee-user-testing']['hide-search']) ) {
    $variables['classes_array'][] = 'hide-search';
    if ($user_testing) {
      $_SESSION['berklee-user-testing']['hide-search'] = true;
    }
  }

  if ( isset($_GET['hli']) || isset($_GET['hide-login']) || isset($_SESSION['berklee-user-testing']['hide-login']) ) {
    $variables['classes_array'][] = 'hide-login';
    if ($user_testing) {
      $_SESSION['berklee-user-testing']['hide-login'] = true;
    }
  }

  if ( isset($_GET['hap']) || isset($_GET['hide-apply']) || isset($_SESSION['berklee-user-testing']['hide-apply']) ) {
    $variables['classes_array'][] = 'hide-apply';
    if ($user_testing) {
      $_SESSION['berklee-user-testing']['hide-apply'] = true;
    }
  }

}

function jjamerson_lb_process_html(&$variables) {
  if (isset($variables['head_title_array']['title']) && $variables['head_title_array']['title'] == 'Access denied') {
    if ( user_is_logged_in() == false) {
      $variables['head_title'] = 'Login Required';
    } else {
      $variables['head_title'] = 'Higher Level Access Required';
    }
  }
}

function jjamerson_lb_process_views_view_field(&$variables) {
  global $user; // Access to Drupal's global for currently logged-in user
  $field =& $variables['field'];
  switch($field->field) {
    case 'field_date':
    case 'field_dates':
      $before = array(' am', ' pm');
      $after  = array(' a.m.', ' p.m.');
      $variables['output'] = str_replace($before, $after, $variables['output']);
      break;
    case 'field_admission':
      $variables['output'] = str_replace('Admission -', '', $variables['output']);
      break;
    case 'field_admission_discount':
      if($user->uid == 0) {
        // unset as much as possible to hide this
        // output seems to suffice, but we're being super-sure here
        unset($variables['output']);
        unset($variables['row']->field_field_admission_discount);
        unset($variables['field']);
      }
      break;
  }
}

function jjamerson_lb_process_field(&$field) {
  global $user;
  // if $field['classes_array'] includes item with value 'field-label-inline', add semicolon
  if(in_array('field-label-inline', $field['classes_array'])) {
    $field['punctuation'] = ':&nbsp;';
  }
  $field_name = $field['element']['#field_name'];
  switch($field_name) {
    case 'field_date':
    case 'field_dates':
      if(isset($field['items'][0]['date']['#markup'])) {
        $before = array(' am', ' pm');
        $after  = array(' a.m.', ' p.m.');
        $field['items'][0]['date']['#markup'] = str_replace($before, $after, $field['items'][0]['date']['#markup']) ;
      }
      break;
    case 'field_country':
    case 'field_zip':
    case 'field_state':
      if (!$field['items'][0]['#markup']) {
        $field['classes'] = 'hidden';
      }
      break;
    case 'field_admission_discount':
      // just to be safe, attempt to hide this on the field level
      if($user->uid == 0) {
        $field = array();
      }
      break;
    case 'field_volume_no_':
    case 'field_issue_no_':
    case 'field_author':
    case 'field_bt_author':
      if ($field['element']['#bundle'] === 'bt_cover' ||
          $field['element']['#bundle'] === 'bt_article') {
            $field['punctuation'] = '&nbsp;';
      }
      break;
    case 'field_news_author':
    case 'field_related_links':
    case 'field_year':
      $field['punctuation'] = '&nbsp;';
      break;
  }
}

function jjamerson_lb_preprocess_node(&$node) {
  global $user; // Access to Drupal's global for currently logged-in user

  if ( isset($node['language']) ) {
    $lang = $node['language'];
  } else {
    $lang = 'und';
  }

  /* Hide image, if option is checked */
  if ( isset($node['field_hide_image'][$lang][0]['value']) ) {
    $hide_image = $node['field_hide_image'][$lang][0]['value'];
    if ($hide_image) {
      $node['content']['field_image'] = array();
    }
  }

  if ( isset($node['body']['0']['format']) ) {
    $format = $node['body']['0']['format'];
    if ($format !== 'filtered_html' && $format !== 'full_html') {
      $node['classes_array'][] = 'preserve-styles';
    }
  }

  if ( isset($node['field_admission_discount']) && $user->uid == 0) {
    $node['content']['field_admission_discount'] = array();
  }

  /* Set captions & credit fields to images */
  // Note: field_image_caption is used by news. field_caption is used by events
  if ( isset($node['field_image_caption']) ) {
    $caption_field = $node['field_image_caption'];
  } elseif ( isset($node['field_caption']) ) {
    $caption_field = $node['field_caption'];
  }
  if ( isset($caption_field) ) {
    $counter = 0;
    foreach ($caption_field as $caption_array) {
      $caption = $caption_array['safe_value'];
      if ( isset($node['content']['field_image'][$counter]['#item']) ) {
        $node['content']['field_image'][$counter]['#item']['title'] = $caption;
        $node['content']['field_image'][$counter]['#item']['caption'] = $caption;
      }
      $counter++;
    }
  }

  // Note: field_credit is used by news. field_image_credit is used by events
  if ( isset($node['field_image_credit']) ) {
    $credit_field = $node['field_image_credit'];
  } elseif ( isset($node['field_credit']) ) {
    $credit_field = $node['field_credit'];
  }
  if ( isset($credit_field) ) {
    $counter = 0;
    foreach ($credit_field as $credit_array) {
      $credit = $credit_array['safe_value'];
      //$node['content']['field_image'][$counter]['#item']['credit'] = $credit;
      if ( isset($node['content']['field_image'][$counter]['#item']) ) {
        $node['content']['field_image'][$counter]['#item']['title'] .= "&nbsp; <br /> {$credit}";
        if(isset($node['content']['field_image'][$counter]['#item']['credit'])) {
          $node['content']['field_image'][$counter]['#item']['credit'] .= $credit;
        } else {
          $node['content']['field_image'][$counter]['#item']['credit'] = $credit;
        }
      }
      $counter++;
    }
  }
  /* Adds an image count to the title attribute. Removed because it becomes redundant in the lightbox2 overlay:
  if ($node['type'] == 'news' || $node['type'] == 'event') {
    $image_count = count($node['field_image']);
    $counter = 0;
    foreach ($node['field_image'] as $image) {
      $display_count = $counter + 1;
      $node['content']['field_image'][$counter]['#item']['title'] .= "<br />Image {$display_count} of {$image_count}";
      $counter++;
    }
  }
  */
  if (isset($node['workbench_moderation']['current'])) {
    $workbench_moderation = $node['workbench_moderation']['current'];
    $node['classes_array'][] = 'workbench-moderation-'.$workbench_moderation->state;
    if ($workbench_moderation->published) {
      $node['classes_array'][] = 'workbench-published';
    }
  }
}

/**
 * Implementing hook_css_alter()
 * Turning off as many unneeded CSS files as possible
 */
function jjamerson_lb_css_alter(&$css) {
  unset($css[drupal_get_path('module', 'book')          . '/book.css']);
  unset($css[drupal_get_path('module', 'comment')       . '/comment.css']);
  unset($css[drupal_get_path('module', 'ldap_servers')  . '/ldap_servers.admin.css']);
  unset($css[drupal_get_path('module', 'node')          . '/node.css']);
  unset($css[drupal_get_path('module', 'search')        . '/search.css']);
  //unset($css[drupal_get_path('module', 'system')        . '/system.base.css']);
  unset($css[drupal_get_path('module', 'system')        . '/system.messages.css']);
  unset($css[drupal_get_path('module', 'system')        . '/system.menus.css']);
  unset($css[drupal_get_path('module', 'system')        . '/system.theme.css']);
  unset($css[drupal_get_path('module', 'user')          . '/user.css']);
  unset($css[drupal_get_path('module', 'views')         . '/css/views.css']);

  //unset($css[libraries_get_path('superfish')        . '/style/white.css']);
}

/**
 * Implementing hook_js_alter()
 * Turning off as many script files as possible
 */
function jjamerson_lb_js_alter(&$js) {
  //unset($js[ path_to_theme() . '/js/jjamerson_lb.min.js' ])
  if ( isset($_GET['render-only']) || isset($_GET['content-only'])) {
    unset($js[ 'sites/all/modules/patched/admin_menu/admin_menu.js' ]);
  }
}

function jjamerson_lb_menu_link($variables) {
  $element = $variables['element'];
  $options = $element['#localized_options'];
  $href = $element['#href'];
  $link_text = $element['#title'];

  if ( $element['#original_link']['hidden'] == TRUE) {
    return;
  }

  $sub_menu = '';
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
    $element['#attributes']['class'][] = 'has-submenu';
  } else {
    $element['#attributes']['class'][] = 'no-submenu';
  }

  /* Add an icon, if set: */
  if ( function_exists('menu_fields_get_value') ) {
    $mlid = $element['#original_link']['mlid'];
    if ( menu_fields_get_value($mlid, 'Icon') ) {
      $icon = menu_fields_get_value($mlid, 'Icon');
      $link_text = "<i class='icon {$icon}' aria-hidden='true'></i><span class='text'>{$link_text}</span>";
      $options['html'] = TRUE;
    }
  }

  /* Add classes, if set: */
  if ( function_exists('menu_fields_get_value') ) {
    $mlid = $element['#original_link']['mlid'];
    if ( menu_fields_get_value($mlid, 'Classes') ) {
      $class = menu_fields_get_value($mlid, 'Classes');
      $options['attributes'] = array('class' => array($class));
    }
  }

  /* Mess with the main menu: */
  if ($element['#theme'] === 'menu_link__main_menu') {
    if ($element['#title'] === 'Faculty/Staff Services') {
      global $user;
      if ( is_object($user) && is_array($user->roles) && count($user->roles) ) {
        if ( isset($user->roles[13]) ) {
          $link_text = 'Staff Services';
        } elseif (isset($user->roles[14]) ) {
          $link_text = 'Faculty Services';
        }
      }
    }
    /* Add the search block to the dropdown: */
    if ( $href == 'search/google') {
      $search_block = module_invoke('search', 'block_view', 'search');
      $sub_menu = "<ul class='menu'>".render($search_block)."</ul>";
    }
  }

  /* Print out the description, which is left up to the CSS to display/hide. */
  if ( isset($element['#localized_options']['attributes']['title']) ) {
    $description = '<div class="menu-item-description">' . $element['#localized_options']['attributes']['title'] . '</div>';
    $options['html'] = TRUE;
  } else {
    $description = '';
  }

  if ( isset($_GET['absolute-links']) ) {
    if ( strpos($href, 'node/') > -1 || strpos($href, '.') == false ) {
      $real_base_url = 'http://www.berklee.edu/';
      $href = $real_base_url . drupal_get_path_alias($href);
    }
  }

  /* attach destination to onelogin link */
  if ($element['#theme'] == 'menu_link__menu_menu_login_menu' && !user_is_logged_in()){
	  $dest = drupal_get_destination();
	  $href = $element['#href'].'?destination='.$dest['destination'];
	  $link = '<a href="/'.$href.'">'.$link_text.'</a>';
	  return '<li' . drupal_attributes($element['#attributes']) . '>' . $link . $sub_menu . "</li>\n";
	}
	/**/

  $link = l($link_text . $description, $href, $options);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $link . $sub_menu . "</li>\n";
}

function jjamerson_lb_apachesolr_search_mlt_recommendation_block($vars) {
  if(isset($vars['delta']) && isset($vars['docs'])) {
    switch($vars['delta']) {
      case 'mlt-002':
        $renderable = array(
          '#theme' => 'berklee_solr_related_content_related_news',
          '#docs' => $vars['docs'],
        );
        return drupal_render($renderable);
      case 'mlt-003':
        $renderable = array(
          '#theme' => 'berklee_solr_related_content_related_events',
          '#docs' => $vars['docs'],
        );
        return drupal_render($renderable);
    }
  }
}

/**
 * Implements hook_form_views_exposed_form_alter().
 *
 */
function jjamerson_lb_form_views_exposed_form_alter(&$form, &$form_state) {

	// about-us/services/ill-submissions page,
	// auto focus to "by Request Identifier filter field
	// taget to _blank to the form
	if($form['#id'] == 'views-exposed-form-submission-ill-data-page'){
		$form['#attributes']['target'] = '_blank';
		drupal_add_js('
			jQuery(document).ready(function(){
				jQuery("#edit-data").focus();
			});
		','inline');

	}

// /dsm($form);
}
