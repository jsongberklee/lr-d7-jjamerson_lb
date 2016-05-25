jQuery(document).ready(function($){

  var mainMenu = $('#block-system-main-menu nav > ul.menu');

  // Add a homepage link to the main menu:
  var homeMenuItem = $("<li />").addClass('first expanded');
  var homeLink = $("<a />")
    .text('Library Home')
    .addClass('home-link')
    .attr('href', 'https://library.berklee.edu/')
    .appendTo(homeMenuItem);
  mainMenu.prepend(homeMenuItem);

  // Add a dashboard link to the main menu if the user is logged in:
  if ( $('body').hasClass('logged-in') ) {
    var dashboardMenuItem = $("<li />").addClass('first expanded');
    var dashboardLink = $("<a />")
      .addClass('dashboard-link')
      .attr('href',"https://hub.berklee.edu")
      .text('Dashboard')
      .appendTo(dashboardMenuItem);
    mainMenu.prepend(dashboardMenuItem);
  }


  var layout = false;
  if ( $(window).width() > 0) {
    if ($(window).width() < 768) {
      layout = 'small';
      mobileMenuCollapse();
      mobileDashboard();
    } else if ($(window).width() < 980) {
      layout = 'medium';
      mediumMenuTweak();
    } else {
      layout = 'big';
    }
  }



  // windowReloading is a flag that's used to determine whether or not we've already triggered a reload, so we can
  // avoid repeatedly calling reloads and potentially locking up the browser.
  var windowReloading = false;
  // shrinkage is a flag that indicates whether or not functions have been performed to accommodate for smaller
  // screen sizes. We do this because those functions are potentially destructive.
  var shrinkage = false;
  $(window).resize(function() {
    if (!windowReloading && !shrinkage && ($(window).width() > 0) ) {
      if ($(window).width() >= 768 && layout === 'small') {
        windowReloading = true;
        window.location.href = window.location.href; // slightly faster than location.reload()
      }
      if ( $(window).width() >= 768 && $(window).width() < 980 && layout === 'big') {
        layout = 'medium';
        shrinkage = true;
        mediumMenuTweak();
      }
      if ( $(window).width() >= 980 && layout === 'medium') {
        //window.location.href = window.location.href; // slightly faster than location.reload()
        //windowReloading = true;
        layout = 'big';
        shrinkage = true;
        mediumMenuTweak();
      }
      if ( $(window).width() < 768 && layout !== 'small') {
        layout = 'small';
        shrinkage = true;
        mobileDashboard();
        mobileMenuCollapse();
      }
    }
  });

  function mobileDashboard() {
    if ( $('body').hasClass('page-dashboard') ) {
      $('h1#page-title').prependTo( $('#page') );
    } else {
      $('.region-header a[href*="/dashboard"]').html('Visit Dashboard');
    }
    shrinkage = false;
  }

  function mobileMenuCollapse() {
    var header = $('.region-header');
    var headerExpand = $('<div />').addClass('header-expand').appendTo(header);
    var headerIcon = $('<div />').addClass('header-expand-icon').attr('aria-hidden', 'true').appendTo(headerExpand);
    var mobileMenu = $('<div />').addClass('mobileMenu').attr('aria-hidden', 'true').appendTo( $('body') );
    var menus = $('nav .block-menu, #block-menu-menu-campus-tools').not('.region-off-screen-overlay .block-menu');
    var campusToolsMenu = false;
    var mainMenu = false;

    menus.each(function() {
      id = $(this).attr('id');
      // the main menu may not have an ID, so we'll manually give it one:
      if ( !id && $(this).hasClass('main-menu-block') ) {
        id = 'main-menu-block';
      }
      switch (id) {
        case 'block-menu-menu-follow-berklee-links':
        case 'block-berklee-site-section-breadcrumb':
          break;
        default:
          var clone = $(this).clone(false);
          $(this).addClass('hidden-on-small-screen');
          clone.removeClass('main-menu-block');
          clone.attr('aria-hidden', 'true');
          clone.removeAttr('id').appendTo(mobileMenu);
          if ( id == 'block-menu-menu-campus-tools') {
            campusToolsMenu = clone;
          } else if (id == 'block-system-main-menu') {
            mainMenu = clone;
          }
      };



    });

    campusToolsMenu.detach().insertAfter(mainMenu);

    var sectionMenus = $('.region-sidebar-first .block-menu, .region-sidebar-first .block-dynamic-book-block, .region-sidebar-first .block-berklee-site-section:not(#block-berklee-site-section-breadcrumb)');
    sectionMenus.each(function() {
      var clone = $(this).clone(false);
      $(this).addClass('hidden-on-small-screen');
      clone.attr('aria-hidden', 'true');
      clone.removeAttr('id').prependTo(mobileMenu);
    });

    var breadcrumb = $('#block-berklee-site-section-breadcrumb nav ul li:last-child a');
    var breadcrumbClone = breadcrumb.clone(false);
    breadcrumb.addClass('hidden-on-small-screen');
    breadcrumbClone.attr('aria-hidden', 'true').addClass('mobile-breadcrumb').prependTo(mobileMenu);

    // removed for library.berklee.edu
	//$('<a />').attr('href', 'https://apply.berklee.edu').html('Apply Now').addClass('mobile-apply').prependTo( mobileMenu );

    var searchbox = $('.region-top-nav form#search-api-page-search-form-search-site');
    var searchboxClone = searchbox.clone(true, true);
    searchbox.addClass('hidden-on-small-screen');
    searchboxClone.attr('aria-hidden', 'true').prependTo(mobileMenu);

    var login = $('#block-menu-menu-menu-login-menu');
    var loginClone = login.clone();
    login.addClass('hidden-on-small-screen');
    loginClone.attr('aria-hidden', 'true').prependTo(mobileMenu);
    $('h3', loginClone).click(function() {
      var firstLoginLink = $('a:first-child', loginClone);
      firstLoginLink[0].click();
    });

		var loginjsong = $(loginClone.find('a'));
		// to activate destination parameter on mobile
		//if(loginjsong.text() == 'Login'){
			//var dest = loginjsong.attr('href').split("=").pop();
			//loginjsong.attr('href','/onelogin_saml/sso?destination='+dest);
		//}
		loginjsong.prepend('<i class="icon fa-user"></i>');

    headerExpand.click(function() {
      mobileMenu.toggleClass('expanded');
      if (mobileMenu.hasClass('expanded') ) {
        var topValue = '57px';
      } else {
        var topValue = '100%';
        $('#page').slideDown(200);
      };
      mobileMenu.css('display', 'block');
      $('html,body').animate( {scrollTop: '0px' }, 100);
      mobileMenu.animate({ top: topValue }, 100, function() {
        if ( $(this).hasClass('expanded') == false ) {
          $(this).css('display', 'none');
        } else {
          $('#page').slideUp(200);
        }
      });
    });

    shrinkage = false;
  }; // END: mobileMenuCollapse function

  function mediumMenuTweak() {
    $('#block-system-main-menu.block-menu .content > nav > ul.menu > li a').attr('style', '');
    $('#block-system-main-menu.block-menu .content > nav > ul.menu > li a').each(function() {
      if ( $(this).height() > 20) {
        var newTopMargin = parseInt( ( $(this).height() - 20 ) / 2 );
        $(this).css('marginTop',  '-' + newTopMargin + 'px');
      }
    });
    shrinkage = false;
  }

  // Collapse sub-menus
  $('.region-sidebar-first nav li.expanded, .mobileMenu nav li.expanded').each(function() {
    if ( $('a.active', this).length === 0 ) {
      //$(this).toggleClass('collapsed expanded');
    }
    $('span', this).click(function() {
      $(this).parent('li').toggleClass('collapsed expanded');
    });
  });
});