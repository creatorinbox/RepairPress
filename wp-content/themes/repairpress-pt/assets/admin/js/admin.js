/**
 * Utilities for the admin dashboard
 */

jQuery( document ).ready( function( $ ) {
	'use strict';

	// Select Icon on Click
	$( 'body' ).on( 'click', '.js-selectable-icon', function ( ev ) {
		ev.preventDefault();
		var $this = $( this );
		$this.siblings( '.js-icon-input' ).val( $this.data( 'iconname' ) ).change();
	} );

} );


/********************************************************
 			Backbone code for repeating fields in widgets
********************************************************/

// Namespace for Backbone elements
window.RepairPress = {
	Models:    {},
	ListViews: {},
	Views:     {},
	Utils:     {},
};


/**
 ******************** Backbone Models *******************
 */

_.extend( RepairPress.Models, {
	Counter: Backbone.Model.extend( {
		defaults: {
			'title': '',
			'number': '',
		}
	} ),
	AccordionItem: Backbone.Model.extend( {
		defaults: {
			'title': '',
			'content': '',
		}
	} ),
	IconMenuItem: Backbone.Model.extend( {
		defaults: {
			'title': '',
			'link': '',
			'icon': 'fa-laptop',
			'new_tab': '',
		}
	} ),
	Step: Backbone.Model.extend( {
		defaults: {
			'title': '',
			'icon': 'fa-mobile',
			'content': '',
			'step': '',
		}
	} )
} );



/**
 ******************** Backbone Views *******************
 */

// Generic single view that others can extend from
RepairPress.Views.Abstract = Backbone.View.extend( {
	initialize: function ( params ) {
		this.templateHTML = params.templateHTML;

		return this;
	},

	render: function () {
		this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );

		return this;
	},

	destroy: function ( ev ) {
		ev.preventDefault();

		// Fix for triggering the widget "update" state in WP widgets area (otherwise the save button is disabled).
		this.$el.trigger( 'change' );

		this.remove();
		this.model.trigger( 'destroy' );
	},
} );

_.extend( RepairPress.Views, {
	// View of a single counter
	Counter: RepairPress.Views.Abstract.extend( {
		className: 'pt-widget-single-counter',

		events: {
			'click .js-pt-remove-counter': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );
			return this;
		},
	} ),

	// View of a single accordion item
	AccordionItem: RepairPress.Views.Abstract.extend( {
		className: 'pt-widget-single-accordion-item',

		events: {
			'click .js-pt-remove-accordion-item': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );
			return this;
		},
	} ),

	// View of a single icon menu item
	IconMenuItem: RepairPress.Views.Abstract.extend( {
		className: 'pt-widget-single-icon-menu-item',

		events: {
			'click .js-pt-remove-icon-menu-item': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );

			this.$( 'input.js-icon-input' ).val( this.model.get( 'icon' ) );

			// check the checkbox for new tab setting if it's saved in the widget
			if ( this.model.get( 'new_tab' ) ) {
				this.$( 'input.js-new-tab-checkbox' ).prop( 'checked', true );
			}

			return this;
		},
	} ),

	// View of a single step
	Step: RepairPress.Views.Abstract.extend( {
		className: 'pt-widget-single-step',

		events: {
			'click .js-pt-remove-step-item': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );

			this.$( 'input.js-icon-input' ).val( this.model.get( 'icon' ) );

			return this;
		},
	} ),

} );



/**
 ******************** Backbone ListViews *******************
 *
 * Parent container for multiple view nodes.
 */

RepairPress.ListViews.Abstract = Backbone.View.extend( {

	initialize: function ( params ) {
		this.widgetId     = params.widgetId;
		this.itemsModel   = params.itemsModel;
		this.itemView     = params.itemView;
		this.itemTemplate = params.itemTemplate;

		// Cached reference to the element in the DOM
		this.$items = this.$( params.itemsClass );

		// Collection of items(locations, people, testimonials,...),
		this.items = new Backbone.Collection( [], {
			model: this.itemsModel
		} );

		// Listen to adding of the new items
		this.listenTo( this.items, 'add', this.appendOne );

		return this;
	},

	addNew: function ( ev ) {
		ev.preventDefault();

		var currentMaxId = this.getMaxId();

		this.items.add( new this.itemsModel( {
			id: (currentMaxId + 1)
		} ) );

		return this;
	},

	getMaxId: function () {
		if ( this.items.isEmpty() ) {
			return -1;
		}
		else {
			var itemWithMaxId = this.items.max( function ( item ) {
				return parseInt( item.id, 10 );
			} );

			return parseInt( itemWithMaxId.id, 10 );
		}
	},

	appendOne: function ( item ) {
		var renderedItem = new this.itemView( {
			model:        item,
			templateHTML: jQuery( this.itemTemplate + this.widgetId ).html()
		} ).render();

		var currentWidgetId = this.widgetId;

		// If the widget is in the initialize state (hidden), then do not append a new item
		if ( '__i__' !== currentWidgetId.slice( -5 ) ) {
			this.$items.append( renderedItem.el );
		}

		return this;
	}
} );

// Collection of all locations, but associated with each individual widget
_.extend( RepairPress.ListViews, {
	// Collection of all counters, but associated with each individual widget
	Counters: RepairPress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-counter': 'addNew'
		}
	} ),

	// Collection of all accordion items, but associated with each individual widget
	AccordionItems: RepairPress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-accordion-item': 'addNew'
		}
	} ),

	// Collection of all icon menu items, but associated with each individual widget
	IconMenuItems: RepairPress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-icon-menu-item': 'addNew'
		}
	} ),

	// Collection of all steps, but associated with each individual widget
	Steps: RepairPress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-step-item': 'addNew'
		}
	} ),
} );



/**
 ******************** Repopulate Functions *******************
 */


_.extend( RepairPress.Utils, {
	// Generic repopulation function used in all repopulate functions
	repopulateGeneric: function ( collectionType, parameters, json, widgetId ) {
		var collection = new collectionType( parameters );

		// Convert to array if needed
		if ( _( json ).isObject() ) {
			json = _( json ).values();
		}

		// Add all items to collection of newly created view
		collection.items.add( json, { parse: true } );
	},

	/**
	 * Function which adds the existing counters to the DOM
	 * @param  {json} countersJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateCounters: function ( countersJSON, widgetId ) {
		var parameters = {
			el:           '#counters-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.counters',
			itemTemplate: '#js-pt-counter-',
			itemsModel:   RepairPress.Models.Counter,
			itemView:     RepairPress.Views.Counter,
		};

		this.repopulateGeneric( RepairPress.ListViews.Counters, parameters, countersJSON, widgetId );
	},

	/**
	 * Function which adds the existing accordion items to the DOM
	 * @param  {json} accordionItemsJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateAccordionItems: function ( accordionItemsJSON, widgetId ) {
		var parameters = {
			el:           '#accordion-items-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.accordion-items',
			itemTemplate: '#js-pt-accordion-item-',
			itemsModel:   RepairPress.Models.AccordionItem,
			itemView:     RepairPress.Views.AccordionItem,
		};

		this.repopulateGeneric( RepairPress.ListViews.AccordionItems, parameters, accordionItemsJSON, widgetId );
	},

	/**
	 * Function which adds the existing icon menu items to the DOM
	 * @param  {json} iconMenuItemsJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateIconMenuItems: function ( iconMenuItemsJSON, widgetId ) {
		var parameters = {
			el:           '#icon-menu-items-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.icon-menu-items',
			itemTemplate: '#js-pt-icon-menu-item-',
			itemsModel:   RepairPress.Models.IconMenuItem,
			itemView:     RepairPress.Views.IconMenuItem,
		};

		this.repopulateGeneric( RepairPress.ListViews.IconMenuItems, parameters, iconMenuItemsJSON, widgetId );
	},

	/**
	 * Function which adds the existing steps to the DOM
	 * @param  {json} stepItemsJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateStepItems: function ( stepItemsJSON, widgetId ) {
		var parameters = {
			el:           '#step-items-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.step-items',
			itemTemplate: '#js-pt-step-item-',
			itemsModel:   RepairPress.Models.Step,
			itemView:     RepairPress.Views.Step,
		};

		this.repopulateGeneric( RepairPress.ListViews.Steps, parameters, stepItemsJSON, widgetId );
	},
} );