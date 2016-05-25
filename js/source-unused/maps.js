jQuery(document).ready(function($){
  var mapElement = document.getElementById('global-map');
  if (mapElement && (window.google !== undefined) ) {
    var allMarkers = [];
    var styles = 
    [
      {
        "featureType": "landscape.natural",
        "elementType": "geometry.fill",
        "stylers": [
          { "color": "#aaaaaa" },
          { "lightness": 34 }
        ]
      },{
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
          { "visibility": "off" }
        ]
      },{
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
          { "color": "#ffffff" }
        ]
      },{
        "featureType": "administrative",
        "stylers": [
          { "visibility": "off" }
        ]
      },{
      }
    ];

    var stylesOnline = 
    [
      {
        "featureType": "landscape.natural",
        "elementType": "geometry.fill",
        "stylers": [
          { "color": "#cc8888" },
          { "lightness": 34 }
        ]
      },{
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
          { "visibility": "off" }
        ]
      },{
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
          { "color": "#ffffff" }
        ]
      },{
        "featureType": "administrative",
        "stylers": [
          { "visibility": "off" }
        ]
      },{
      }
    ];

    locations = [];
    locations.campuses = 
    [
      ['Boston, USA', 42.360082, -71.058880, '/boston-campus'],
      ['Valencia, Spain', 39.469907, -0.376288, ['http://valencia.berklee.edu', '/study-abroad' ] ],
    ];
    locations.bor = 
    [
      ['Bogotá, Colombia', 4.598056, -74.075833, ['/bor', '/latino'] ],
      ['São Paulo, Brazil', -23.550520, -46.633309, '/bor'],
      ['Santo Domingo, Dominican Republic', 18.466667, -69.950000, '/bor'],
      ['Carolina, Puerto Rico', 18.380782, -65.957387, '/bor'],
      ['Mexico City, Mexico', 19.432608, -99.133208, ['/bor', '/latino'] ],
      ['Perugia, Italy', 43.110717, 12.390828, '/bor'],
      ['Los Angeles, USA', 34.052234, -118.243685, '/bor'],
      ['Cape Verde, Africa', 15.120142, -23.605172, '/bor'],
    ];
    locations.bin = 
    [
      ['Baron School of Music, Hong Kong', 22.396428, 114.109497, '/bin'],
      ['Conservatorio de Artes Del Caribe, San Juan, Puerto Rico', 18.466334, -66.105722, '/bin'],
      ['Conservatório Musical Souza Lima, São Paulo, Brazil', -23.567349, -46.658768, '/bin'],
      ['L\'AULA de Música Moderna i Jazz — Barcelona, Spain', 41.385064, 2.173403, '/bin'],
      ['Colegio de Música - USFQ — Quito, Ecuador', -0.196656, -78.435760, '/bin'],
      ['Escuela de Música Medios, Arte y Tecnologia — Bogotá, Colombia', 4.598056, -74.075833, '/bin'],
      ['Escuela de Música Contemporánea — Buenos Aires, Argentina', -34.603723, -58.381593, '/bin'],
      ['Holland College — Charlottetown, Prince Edward Island, Canada', 46.238718, -63.122448, '/bin'],
      ['International College of Music (ICOM) — Kuala Lumpur, Malaysia', 3.179750, 101.699750, '/bin'],
      ['Jazz & Rock Schulen — Freiburg, Germany', 47.992998, 7.832635, '/bin'],
      ['JMC Academy — Sydney, Melbourne and Brisbane, Australia', -27.471011, 153.023449, '/bin'],
      ['Koyo Conservatory — Kobe, Japan', 34.690083, 135.195511, '/bin'],
      ['Metropolia University of Applied Sciences — Helsinki, Finland', 60.258613, 24.845594, '/bin'],
      ['Music Academy International — Nancy, France', 48.692082, 6.184589, '/bin'],
      ['Newpark Music Centre — Dublin, Ireland', 53.349805, -6.260310, '/bin'],
      ['Philippos Nakas Conservatory — Athens, Greece', 37.983917, 23.729360, '/bin'],
      ['Pop & Jazz Conservatory — Helsinki, Finland', 60.173324, 24.941025, '/bin'],
      ['Rimon School of Music — Ramat Hasharon, Israel', 32.130423, 32.130423, '/bin'],
      ['Seoul Jazz Academy — Seoul, Korea', 37.566535, 126.977969, '/bin'],
    ];
    locations.bcm =
    [
      ["Albuquerque, New Mexico",35.110703,-106.609991, '/city-music'],
      ["Anaheim, California",33.835293,-117.914504, '/city-music'],
      ["Aspen, Colorado",39.191098,-106.817539, '/city-music'],
      ["Atlanta, Georgia",33.748995,-84.387982, '/city-music'],
      ["Austin, Texas",30.267153,-97.743061, '/city-music'],
      ["Birmingham, Alabama",33.520661,-86.80249, '/city-music'],
      ["Boston, Massachusetts",42.360083,-71.05888, '/city-music'],
      ["Bronx, New York",40.844782,-73.864827, '/city-music'],
      ["Camden, New Jersey",39.925946,-75.11962, '/city-music'],
      ["Carolina, Puerto Rico",18.380782,-65.957387, '/city-music'],
      ["Carrollton, Texas",32.975642,-96.889964, '/city-music'],
      ["Charlotte, North Carolina",35.227087,-80.843127, '/city-music'],
      ["Chicago, Illinois",41.878114,-87.629798, '/city-music'],
      ["Cleveland, Ohio",41.49932,-81.694361, '/city-music'],
      ["Columbus, Ohio",39.961176,-82.998794, '/city-music'],
      ["Des Moines, Iowa",41.600545,-93.609106, '/city-music'],
      ["Detroit, Michigan",42.331427,-83.045754, '/city-music'],
      ["Estes Park, Colorado",40.377206,-105.521665, '/city-music'],
      ["Houston, Texas",29.760427,-95.369803, '/city-music'],
      ["Key West, Florida",24.555059,-81.779987, '/city-music'],
      ["Knoxville, Tennessee",35.960638,-83.920739, '/city-music'],
      ["Lexington, Kentucky",38.040584,-84.503716, '/city-music'],
      ["Los Angeles, California",34.052234,-118.243685, '/city-music'],
      ["Memphis, Tennessee",35.149534,-90.04898, '/city-music'],
      ["Miami, Florida",25.76168,-80.19179, '/city-music'],
      ["Midland, Pennsylvania",40.632566,-80.446455, '/city-music'],
      ["Milledgeville, Georgia",33.080143,-83.232099, '/city-music'],
      ["New Orleans, Louisiana",29.951066,-90.071532, '/city-music'],
      ["New York, New York",40.712784,-74.005941, '/city-music'],
      ["Newark, New Jersey",40.735657,-74.172367, '/city-music'],
      ["North Hollywood, California",34.187044,-118.381256, '/city-music'],
      ["Oakland, California",37.804364,-122.271114, '/city-music'],
      ["Omaha, Nebraska",41.252363,-95.997988, '/city-music'],
      ["Philadelphia, Pennsylvania",39.952584,-75.165222, '/city-music'],
      ["Phoenix, Arizona",33.448377,-112.074037, '/city-music'],
      ["Raleigh, North Carolina",35.77959,-78.638179, '/city-music'],
      ["Richmond, California",37.935758,-122.347749, '/city-music'],
      ["Richmond, Virginia",37.540725,-77.436048, '/city-music'],
      ["Santa Fe, New Mexico",35.686975,-105.937799, '/city-music'],
      ["St. Louis, Missouri",38.627003,-90.199404, '/city-music'],
      ["Toronto, Ontario (Canada)",43.653226,-79.383184, '/city-music'],
      ["Vancouver, British Columbia (Canada)",49.282729,-123.120738, '/city-music'],
      ["Washington, D.C.",38.907192,-77.036871, '/city-music'],
      ["Wilmington, Deleware",39.739072,-75.539788, '/city-music']
    ];
    /* 
    if ($(window).width() > 1600) {
      zoom = 3;
    } else {
      zoom = 2;
    }
    */
    zoom = 2;
    var options = {  
      mapTypeId: 'ROADMAP',
      center: new google.maps.LatLng( 15, -90 ),
      zoom: zoom,  
      disableDefaultUI: true,   
      mapTypeId: 'Styled',
      scrollwheel: false,
      //navigationControl: false,
      mapTypeControl: false,
      scaleControl: false,
      draggable: false,
      styles: styles
    };  
    
    var map = new google.maps.Map(mapElement, options);  
    
    var styledMapType = new google.maps.StyledMapType(styles, { name: 'Styled' });  
    map.mapTypes.set('Styled', styledMapType);  
    map.setMapTypeId('Styled');

    var styledOnlineMapType = new google.maps.StyledMapType(stylesOnline, { name: 'StyledOnline' });  
    map.mapTypes.set('StyledOnline', styledOnlineMapType);  
    
    var campusMarker = {
      //url: '/sites/all/themes/jjamerson_lb/images/icons/dot-120x120-gray.png',
      //size: new google.maps.Size(120, 120),
      //scaledSize: new google.maps.Size(18,18),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(100,100),
      path: 'M 125,5 155,90 245,90 175,145 200,230 125,180 50,230 75,145 5,90 95,90 z',
      fillColor: '#000',
      fillOpacity: 1,
      scale: 0.125,
      strokeColor: '#fff',
      strokeWeight: 2
    };
    var campusMarkerHighlight = {
      /*url: '/sites/all/themes/jjamerson_lb/images/icons/dot-120x120-red.png',
      size: new google.maps.Size(120, 120),
      scaledSize: new google.maps.Size(18,18),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(6,6)*/
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(100,100),
      path: 'M 125,5 155,90 245,90 175,145 200,230 125,180 50,230 75,145 5,90 95,90 z',
      fillColor: '#d81118',
      fillOpacity: 1,
      scale: 0.125,
      strokeColor: '#fff',
      strokeWeight: 2
    };
    var borMarker = {
      url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-gray.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(12,12),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(4,4)
    };
    var borMarkerHighlight = {
      url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-red.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(12,12),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(4,4)
    };  
    var smallMarker = {
       url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-gray.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(10,10),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(2,2)
    };
    var smallMarkerHighlight = {
      url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-red.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(10,10),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(2,2)
    };
    var smallestMarker = {
      url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-gray.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(8,8),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(2,2)
    };
    var smallestMarkerHighlight = {
      url: '/sites/all/themes/jjamerson_lb/images/icons/dot-100x100-red.png',
      size: new google.maps.Size(100, 100),
      scaledSize: new google.maps.Size(8,8),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(2,2)
    };

    // traverse all locations & add markers. 
    for (var locationGroup = 0; locationGroup < 4; locationGroup++) {
      switch (locationGroup) {
        case 0:
          var thisGroup = 'bcm';
          var theseLocations = locations.bcm;
          var thisIcon = smallestMarker;
          var thisHighlightIcon = smallestMarkerHighlight;
          var z = 10;
          break;
        case 1:
          var thisGroup = 'bin';
          var theseLocations = locations.bin;
          var thisIcon = smallMarker;
          var thisHighlightIcon = smallMarkerHighlight;
          var z = 20;
          break;
        case 2: 
          var thisGroup = 'bor';
          var theseLocations = locations.bor;
          var thisIcon = borMarker;
          var thisHighlightIcon = borMarkerHighlight;
          var z = 30;
          break;
        case 3:
          var thisGroup = 'campuses';
          var theseLocations = locations.campuses;
          var thisIcon = campusMarker;
          var thisHighlightIcon = campusMarkerHighlight;
          var z = 40;
      }
      
      for (var i = 0; i < theseLocations.length; i++) {
        if (theseLocations[i][3]) {
          if ( $.isArray( theseLocations[i][3] ) ) {
            var thisLink = theseLocations[i][3][0];
            var relatedUrls = theseLocations[i][3];
          } else {
            var thisLink = theseLocations[i][3];
            var relatedUrls = false;
          }
        }
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng( theseLocations[i][1], theseLocations[i][2] ),
          map: map,
          title: theseLocations[i][0],
          icon: thisIcon,
          standardIcon: thisIcon,
          highlightIcon: thisHighlightIcon,
          locationGroup: thisGroup,
          linkUrl: thisLink,
          relatedUrls: relatedUrls,
          zIndex: z,
        });
        allMarkers.push(marker);
        // Turn the markers into pseudo-links:
        google.maps.event.addListener(marker, 'click', function() {
          window.location = this.linkUrl;  
        });  
          
        // Add hover behavior to the markers:
        google.maps.event.addListener(marker, 'mouseover', function() {
          if (this.relatedUrls) {
            for( var urlIndex = 0; urlIndex < this.relatedUrls.length; urlIndex++) {
              $('.location-stripe a[href^="' + this.relatedUrls[urlIndex] + '"]').addClass('mapHover');    
            }
          } else {
            $('.location-stripe a[href^="' + this.linkUrl + '"]').addClass('mapHover');  
          }
          this.setIcon( this.highlightIcon );
        });
        google.maps.event.addListener(marker, 'mouseout', function() {
          if (this.relatedUrls) {
            for( var urlIndex = 0; urlIndex < this.relatedUrls.length; urlIndex++) {
              $('.location-stripe a[href^="' + this.relatedUrls[urlIndex] + '"]').removeClass('mapHover');    
            }
          } else {
            $('.location-stripe a[href^="' + this.linkUrl + '"]').removeClass('mapHover');  
          }
          this.setIcon( this.standardIcon );
        });
      }
    }  

    $('.location-stripe a').hover(
      function() {
        var thisUrl = $(this).attr('href').split('?');
        thisUrl = thisUrl[0];
        var panLat = 0;
        var panLng = 0;
        var panPoints = 0;
        for ( var i=0; i < allMarkers.length; i++) {
          if ( allMarkers[i].relatedUrls ) {
            for( var urlIndex = 0; urlIndex < allMarkers[i].relatedUrls.length; urlIndex++) {
              if ( allMarkers[i].relatedUrls[urlIndex].indexOf(thisUrl) == 0) {
                allMarkers[i].setIcon( allMarkers[i].highlightIcon );
                panLat += allMarkers[i].position.lat();
                panLng += allMarkers[i].position.lng();
                panPoints++;
              }
            }
          } else {
            if ( allMarkers[i].linkUrl.indexOf(thisUrl) == 0) {
              allMarkers[i].setIcon( allMarkers[i].highlightIcon );
              panLat += allMarkers[i].position.lat();
              panLng += allMarkers[i].position.lng();
              panPoints++;
            }
          }
        }
        if (panPoints) {
          panLat = panLat / panPoints;
          panLng = panLng / panPoints;
          map.panTo( new google.maps.LatLng(panLat, panLng) );
        }
        if ( thisUrl.indexOf('online.berklee.edu') > -1 ) {
          map.setMapTypeId('StyledOnline');
        }
      }, function() {
        var thisUrl = $(this).attr('href');
        for ( var i=0; i < allMarkers.length; i++) {
          if ( allMarkers[i].relatedUrls ) {
            for( var urlIndex = 0; urlIndex < allMarkers[i].relatedUrls.length; urlIndex++) {
              if ( allMarkers[i].relatedUrls[urlIndex] == thisUrl) {
                allMarkers[i].setIcon( allMarkers[i].standardIcon );
              }
            } 
          } else {
            if ( allMarkers[i].linkUrl == thisUrl) {
              allMarkers[i].setIcon( allMarkers[i].standardIcon );
            }
          }
        }
        if ( thisUrl.indexOf('online.berklee.edu') > -1 ) {
          map.setMapTypeId('Styled');
        }
      }
    );
  } 
});