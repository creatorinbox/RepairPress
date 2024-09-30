/* global Modernizr */

// config
require.config( {
	paths: {
		jquery:              'assets/js/fix.jquery',
		underscore:          'assets/js/fix.underscore',
		bootstrapAffix:      'bower_components/bootstrap-sass/assets/javascripts/bootstrap/affix',
		bootstrapAlert:      'bower_components/bootstrap-sass/assets/javascripts/bootstrap/alert',
		bootstrapButton:     'bower_components/bootstrap-sass/assets/javascripts/bootstrap/button',
		bootstrapCarousel:   'bower_components/bootstrap-sass/assets/javascripts/bootstrap/carousel',
		bootstrapCollapse:   'bower_components/bootstrap-sass/assets/javascripts/bootstrap/collapse',
		bootstrapDropdown:   'bower_components/bootstrap-sass/assets/javascripts/bootstrap/dropdown',
		bootstrapModal:      'bower_components/bootstrap-sass/assets/javascripts/bootstrap/modal',
		bootstrapPopover:    'bower_components/bootstrap-sass/assets/javascripts/bootstrap/popover',
		bootstrapScrollspy:  'bower_components/bootstrap-sass/assets/javascripts/bootstrap/scrollspy',
		bootstrapTab:        'bower_components/bootstrap-sass/assets/javascripts/bootstrap/tab',
		bootstrapTooltip:    'bower_components/bootstrap-sass/assets/javascripts/bootstrap/tooltip',
		bootstrapTransition: 'bower_components/bootstrap-sass/assets/javascripts/bootstrap/transition',
	},
	shim: {
		bootstrapAffix: {
			deps: [
				'jquery'
			]
		},
		bootstrapAlert: {
			deps: [
				'jquery'
			]
		},
		bootstrapButton: {
			deps: [
				'jquery'
			]
		},
		bootstrapCarousel: {
			deps: [
				'jquery'
			]
		},
		bootstrapCollapse: {
			deps: [
				'jquery',
				'bootstrapTransition'
			]
		},
		bootstrapDropdown: {
			deps: [
				'jquery'
			]
		},
		bootstrapPopover: {
			deps: [
				'jquery'
			]
		},
		bootstrapScrollspy: {
			deps: [
				'jquery'
			]
		},
		bootstrapTab: {
			deps: [
				'jquery'
			]
		},
		bootstrapTooltip: {
			deps: [
				'jquery'
			]
		},
		bootstrapTransition: {
			deps: [
				'jquery'
			]
		},
		jqueryVimeoEmbed: {
			deps: [
				'jquery'
			]
		}
	}
} );

require.config( {
	baseUrl: RepairPressVars.pathToTheme
} );

require( [
		'jquery',
		'underscore',
		'assets/js/SimpleMap',
		'assets/js/WidgetLine',
		'vendor/proteusthemes/proteuswidgets/assets/js/NumberCounter',
		'assets/js/StickyNavbar',
		'assets/js/TouchDropdown',
		'bootstrapCarousel',
		'bootstrapCollapse',
		'bootstrapTooltip',
], function ( $, _, SimpleMap, WidgetLine, NumberCounter ) {
	'use strict';

	/**
	 * remove the attributes and classes from collapsible navbar when the window is resized
	 */
	$( window ).on( 'resize', _.debounce( function () {
		if ( Modernizr.mq( '(min-width: 992px)' ) ) {
			$( '#repairpress-navbar-collapse' )
				.removeAttr( 'style' )
				.removeClass( 'in' );
		}
	}, 500 ) );

	/**
	 * Maps
	 */
	$( '.js-where-we-are' ).each( function () {
		new SimpleMap( $( this ), {
			latLng:  $( this ).data( 'latlng' ),
			markers: $( this ).data( 'markers' ),
			zoom:    $( this ).data( 'zoom' ),
			type:    $( this ).data( 'type' ),
			styles:  $( this ).data( 'style' ),
		}).renderMap();
	} );

	/**
	 * Footer widgets fix
	 */
	$( '.col-md-__col-num__' ).removeClass( 'col-md-__col-num__' ).addClass( 'col-md-3' );


	/**
	 * Widget lines, the length is relative to the amount of text
	 */
	$( '.widget-title--big .widget-title' ).each( function () {
		new WidgetLine( $( this ) );
	} );

	// Update widget width for widget: Title with button
	var updateWidgetWidth = function() {
		$( '.widget-title--big .title-with-button .widget-title' ).each( function () {
			$( this ).css( 'width', 'calc(100% - ' + ( parseInt( $( this ).next().outerWidth(), 10 ) + 30 ) + 'px)' );
		} );
	};

	updateWidgetWidth();
	$( window ).on( 'resize.ptwltwb' , _.debounce( updateWidgetWidth, 500 ) );


	/**
	 * Number Counter Widget JS code
	 */
	// Get all number counter widgets
	var $counterWidgets = $( '.number-counters' );

	if ( $counterWidgets.length ) {

		// jQuery easing function: easeInOutQuad, for use in NumberCounter
		$.extend( $.easing, {
			easeInOutQuad: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) {
					return c/2*t*t + b;
				}
				return -c/2 * ((--t)*(t-2) - 1) + b;
			}
		});

		$counterWidgets.each( function () {
			new NumberCounter( $( this ) );
		} );
	}

} );
