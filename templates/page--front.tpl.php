<?php
include('keyboard_quicklinks.inc.php');
echo render($page['page_top']);
echo render($page['emergency_bar']);
echo render($page['top_nav']);
echo render($page['header']);
echo render($page['help']);
echo ($tabs ? '<div class="container tabs-container">'.render($tabs).'</div>' : '');

echo '<section id="off-screen" class="hidden">';
echo   '<nav id="off-screen-sidebar" class="closed">';
echo     render($page['off_screen_sidebar']);
echo   '</nav>';
echo   render($page['off_screen_overlay']);
echo '</section>';

echo '<section id="page" class="open">';
echo    $messages;
echo    '<div class="row">';
echo      render($page['sidebar_first']);
echo      '<article id="page-content" role="main" class="article">';
echo        ($title ? "<h1 id='page-title'>{$pretitle}{$title}</h1>{$subtitle}" : '');

/*****************************************************************
 * removing the front video for library.berklee.edu
 *****************************************************************
$videos = false;
$video_cache = cache_get('front_video');
if ($video_cache && ( ($video_cache->expire - time()) > 0 ) ) {
  $videos = unserialize($video_cache->data);
}
// If the attempt to fetch the cache didn't work out, grab the video data from vimeo and set the cache
if ( !$videos ) {
  // get video album data and store relevant information in the videos object
  // vimeo access token: 8ac7a186ab517608f3ad972de26f4eda
  // our homepage album id: 3299795

  // Initialize session and set URL.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://api.vimeo.com/users/38204101/albums/3299795/videos');

  // Set so curl_exec returns the result instead of outputting it.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: bearer 8ac7a186ab517608f3ad972de26f4eda'
  ));

  // Get the response and close the channel.
  $response = curl_exec($ch);
  if ($response) {
    $videos = array();
    $response_json = json_decode($response);
    curl_close($ch);

    foreach( array_reverse($response_json->data) as $i => $video_data) {
      $videos[$i] = array();

      $sources = $video_data->files;
      foreach($sources as $source) {
        if ($source->width == 1920) {
          $videos[$i]['src_hd'] = str_replace('http://', 'https://', $source->link);
        }
        if ($source->width == 1280) {
          $videos[$i]['src_sd'] =  str_replace('http://', 'https://', $source->link);
        }
        if ($source->width == 640) {
          $videos[$i]['src_low'] =  str_replace('http://', 'https://', $source->link);
        }
        if ($source->width == 640) {
          $videos[$i]['src_low'] =  str_replace('http://', 'https://', $source->link);
        }
        if ($source->quality == 'hls') {
          $videos[$i]['src_hls'] =  str_replace('http://', 'https://', $source->link);
        }
      }
      $videos[$i]['title'] = $video_data->name;

      $picture_sizes = $video_data->pictures->sizes;
      $picture = end($picture_sizes);
      $videos[$i]['poster'] = $picture->link;

      // Randomize the videos. Note that the array_reverse() in the foreach statement is unnecessary, but
      // I've left it there so that randomizing the videos can be enabled/disabled by uncommenting/commenting
      // this line:
      shuffle($videos);

      // cache for 24 hours
      cache_set('front_video', serialize($videos), 'cache', time() + (60 * 60 * 24) );
    }
  } else {
    // Fall back on the cachd data
    $videos = $video_cache->data;
  }
}
echo "<script type='application/json' id='video-data'>".json_encode($videos)."</script>";
?>

<div id="front-video-container">
  <div id="front-video">
    <video id="front-video-player" class="video-js vjs-default-skin"
      preload="auto"
      width="100%"
      height="100%"
      poster="<?php echo $videos[0]['poster']; ?>"
      data-setup='{"children":{"loadingSpinner": false}}'>
      <source src="<?php echo $videos[0]['src_hd']; ?>" type='video/mp4' />
      <p class="vjs-no-js">
        <img src="/sites/all/themes/jjamerson_lb/images/backgrounds/berklee--160-banner.jpg" />
      </p>
    </video>

  </div>
  <div id="video-next-button" class="video-controls"><i class="fa fa-fast-forward"></i></div>
  <div id="video-previous-button" class="video-controls"><i class="fa fa-fast-backward"></i></div>
  <div id="front-video-chrome">
    <div id="video-info">
      <h3></h3>
      <p></p>
    </div>
    <div id="front-video-controls" class="video-controls">
      <button type="button" id="video-sound-button"><i class="fa fa-volume-off"></i><span class="text">Unmute</span></button>
      <button type="button" id="video-play-pause-button"><i  class="fa fa-pause"></i><span class="text">Pause</span></button>
      <button type="button" id="video-info-button"><i  class="fa fa-info-circle"></i></button>
    </div>
  </div>
</div>

<?php /**/ ?>

<?php
echo        render($page['content_top']);
echo        render($page['content']);
echo        render($page['content_bottom']);
echo      '</article>';
echo      render($page['sidebar_second']);
echo    '</div>';
echo   render($page['stripes']);
echo   render($page['footer']);
echo '</section>';


echo render($page['page_bottom']);