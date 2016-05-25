jQuery(function($) {
  if ( $(window).width() < 767 && $('body').hasClass('front') ) {
    videojs("front-video-player").dispose();
  } else if ( $('body').hasClass('front') ) {

    var aspectRatio = 0.5625;
    var inverseAspectRatio = 1.77;
    var videoHeight = 720;
    var maxVideoHeight = 500;
    var bufferedLength = {'previousIteration': 0, 'currentIteration': 0, 'total': 0};
    var playVideo = false;
    var disableVideo = false;
    var bufferingThreshold = false; // seconds that the video should buffer before playing. Overridden by the looping checkbuffering() function
    var bufferChances = 0;
    var startTime = 0.632;

    function resizeAdjust() {
      var width = $(window).width();
      var height = width * aspectRatio;
      var frontVideoPlayer = $('#front-video-player, #front-video-player_html5_api');
      if (height > videoHeight) {
        width = height * inverseAspectRatio;
        frontVideoPlayer.width(width);
        frontVideoPlayer.height(height);
      } else {
        frontVideoPlayer.width('100%');
        frontVideoPlayer.height(height);
      }
      if (height < maxVideoHeight) {
        $('#front-video-container').height(height);
      } else {
        var crop = (height - maxVideoHeight) * 0.2;
        frontVideoPlayer.css('marginTop', '-'+crop+'px');
      }
    }
    $(window).resize(function() { resizeAdjust() });
    resizeAdjust();

    var playlistLow = [];
    var playlistSD = [];
    var playlistHD = [];
    var playlistHLS = [];
    var captions = [];

    var videoData = $('#video-data').html();
    videoData = $.parseJSON(videoData);
    for (var i=0; i<videoData.length; i++) {
      playlistLow.push( videoData[i].src_low );
      playlistSD.push( videoData[i].src_sd );
      playlistHD.push( videoData[i].src_hd );
      playlistHLS.push( videoData[i].src_hls );
      captions.push( videoData[i].title );
    };

    var playlist = playlistSD;

    var playlistPos = 0;

    videojs("front-video-player", { children: { loadingSpinner: false }, preload: 'metadata' }).ready(function() {
      $('#video-info').text(captions[0]);
      resizeAdjust();
      var player = this;

      player.currentTime( startTime );
      //player.preload = 'auto'; // possibly not necessary anymore. Uncomment if some browsers have issues;

      // Once the intiial meta data has loaded (for the first video):
      player.on('loadedmetadata', function(){
        // Start the video after the fade in. currentTime() is for seconds, so 0.65 seconds gets us to
        // the 7th frame of a video with a frame rate of 24 or 25, or the 8th frame of a video at 29fps
        player.currentTime( startTime );
        /* Commenting out because it's causing confusion during pre-launch testing:
        // If the user is staff, check localStorage to see if the user has previously paused the video and left the page:
        if ( $('body').hasClass('role-1') || $('body').hasClass('role-13') || $('body').hasClass('role-8') ) {
          var isPaused = rememberCollapsed('check', 'front-video' );
        } else {
          isPaused = -1;
        }
        */
        isPaused = -1; // Always play. Remove and uncomment code block above to remember pause state for certain roles.
        if ( isPaused == -1 ) {
          playVideo = true;
        } else {
          updatePlayIcon();
        }
      });
      player.muted(true);

      $('#front-video-player_html5_api').width('100%');
      $('#front-video-player_html5_api').height('100%');

      $('#video-play-pause-button').click(function() {
        if ( playVideo ) {
          playVideo = false;
          rememberCollapsed('add', 'front-video' );
        } else {
          playVideo = true;
          rememberCollapsed('remove', 'front-video' );
        }
        updatePlayIcon();
      });

      $('#video-sound-button').click(function() {
        if ( player.muted() ) {
          player.muted(false);
          $('.text', this).text('Mute');
        } else {
          player.muted(true);
          $('.text', this).text('Unmute');
        }
        $('i', this).toggleClass('fa-volume-off fa-volume-up');
      });

      $('#video-next-button').click(function() {
        playlistPos = (playlistPos+1 > playlist.length-1) ? 0 : playlistPos+1;
        bufferingThreshold = true;
        playVideo = true;
        changeVideo();
        updatePlayIcon();
      });

      $('#video-previous-button').click(function() {
        playlistPos = (playlistPos-1 >= 0) ? playlistPos-1 : playlist.length-1;
        bufferingThreshold = true;
        playVideo = true;
        changeVideo();
        updatePlayIcon();
      });

      var playButton = $('#video-play-pause-button');
      var playIcon = $('i', playButton);
      var playText = $('.text', playButton);
      function updatePlayIcon() {
        if (playVideo) {
          if ( !playIcon.hasClass('fa-pause') ) {
            playIcon.addClass('fa-pause');
            playIcon.removeClass('fa-play');
            playText.text('Pause');
          }
        } else {
          if ( playIcon.hasClass('fa-pause') ) {
            playIcon.removeClass('fa-pause');
            playIcon.addClass('fa-play');
            playText.text('Play');
          }
        }
      }

      player.on('ended', function() {
        playlistPos = (playlistPos+1 > playlist.length-1) ? 0 : playlistPos+1;
        changeVideo();
      });

      function changeVideo() {
        $('video', '#front-video-player').attr('poster', '');
        player.src([{ type: 'video/mp4', src: playlist[playlistPos] }]);
        player.load().play();
        $('#video-info').text( captions[playlistPos] );
        bufferedLength.total = 0;
        bufferedLength.prevousIteration = 0;
        bufferedLength.currentIteration = 0;
      }

      var checkBufferingInterval = 40;
      if ( player.buffered() ) {
        checkBufferingInterval = window.setInterval(checkBuffering, checkBufferingInterval);
        checkBuffering();
      } else {
        //player.play();
      }

      function checkBuffering() {
        if (playVideo === true) {
          bufferedLength.total = player.buffered().end(0) - player.currentTime();
          bufferedLength.previousIteration = bufferedLength.currentIteration;
          bufferedLength.currentIteration = bufferedLength.total - bufferedLength.previousIteration;
          var bufferingRatePerInterval = bufferedLength.currentIteration + bufferedLength.previousIteration / 2;
          var bufferingRatePerSecond = bufferingRatePerInterval / (1000 / checkBufferingInterval);
          var timeLeft = player.duration() - player.currentTime();
          if (bufferingThreshold === false) {
            if (bufferedLength.currentIteration > 0.8) {
              //playlist = playlistHD;
              //player.src([{ type: 'video/mp4', src: playlist[playlistPos] }]);
              //player.currentTime( startTime ).load().play();
              bufferingThreshold = Math.min(10, (1 / bufferedLength.currentIteration) * 4);
            } else if (bufferedLength.currentIteration > 0.3) {
              bufferingThreshold = Math.min(10, (1 / bufferedLength.currentIteration) * 4);
            } else {
              bufferChances++;
              if (bufferChances > 4) {
                bufferingThreshold = Math.min(10, (1 / bufferedLength.currentIteration) * 5);
                playlist = playlistLow;
                player.src([{ type: 'video/mp4', src: playlist[playlistPos] }]);
                player.currentTime( startTime ).load().play();
              }
            }
          } else {
            $('.video-controls').addClass('enabled');
          }

          if ( bufferingThreshold && player.paused() ){
            if ( (bufferedLength.total > bufferingThreshold) || (bufferedLength.total >= timeLeft) ) {
              player.currentTime( startTime ).play();
              updatePlayIcon();
            }
          }
        } else {
          if ( !player.paused() ) {
            player.pause();
            updatePlayIcon();
          }
        }
      }
    });
  }
});