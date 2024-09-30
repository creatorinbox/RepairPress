<?php
/**
 * Icon Menu Widget
 */

if ( ! class_exists( 'PW_Icon_Menu' ) ) {
	class PW_Icon_Menu extends WP_Widget {

		// Basic widget settings
		function widget_id_base() { return 'icon_menu'; }
		function widget_name() { return esc_html__( 'Icon Menu', 'repairpress-pt' ); }
		function widget_description() { return esc_html__( 'Icon Menu widget for Page Builder.', 'repairpress-pt' ); }
		function widget_class() { return 'widget-icon-menu'; }

		// A list of icons to choose from in the widget backend
		private $font_awesome_icons_list = array(
			'fa-laptop',
			'fa-mobile',
			'fa-gamepad',
			'fa-television',
			'fa-music',
			'fa-battery-full',
			'fa-ellipsis-v',
			'fa-apple',
			'fa-linux',
			'fa-windows',
			'fa-android',
			'fa-cogs',
			'fa-plug',
			'fa-volume-up',
		);

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ), // Name
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			$items = isset( $instance['items'] ) ? array_values( $instance['items'] ) : array();

			echo $args['before_widget'];
			?>

			<div class="icon-menu">

			<?php
			foreach ( $items as $item ) :
			?>
				<a class="icon-menu__link" href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo ! empty( $item['new_tab'] ) ? '_blank' : '_self'; ?>">
					<i class="fa  <?php echo esc_attr( $item['icon'] ); ?>"></i> <?php echo esc_html( $item['title'] ); ?>
				</a>
			<?php
			endforeach;
			?>

			</div>

			<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			foreach ( $new_instance['items'] as $key => $item ) {
				$instance['items'][ $key ]['id']      = sanitize_key( $item['id'] );
				$instance['items'][ $key ]['title']   = sanitize_text_field( $item['title'] );
				$instance['items'][ $key ]['link']    = esc_url_raw( $item['link'] );
				$instance['items'][ $key ]['icon']    = sanitize_html_class( $item['icon'] );
				$instance['items'][ $key ]['new_tab'] = sanitize_key( $item['new_tab'] );
			}

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			if ( ! isset( $instance['items'] ) ) {
				$instance['items'] = array(
					array(
						'id'      => 1,
						'title'   => '',
						'link'    => '',
						'icon'    => '',
						'new_tab' => '',
					),
				);
			}

			// Page Builder fix when using repeating fields
			if ( 'temp' === $this->id ) {
				$this->current_widget_id = $this->number;
			}
			else {
				$this->current_widget_id = $this->id;
			}
			$this->current_widget_id = esc_attr( $this->current_widget_id );
		?>

			<h4><?php _ex( 'Menu items:', 'backend', 'repairpress-pt' ); ?></h4>

			<script type="text/template" id="js-pt-icon-menu-item-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-title"><?php _ex( 'Title', 'backend', 'repairpress-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-title" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][title]" type="text" value="{{title}}" />
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-link"><?php _ex( 'Link URL', 'backend', 'repairpress-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-link" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][link]" type="text" value="{{link}}" />
				</p>
				<p>
					<input class="checkbox  js-new-tab-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-new_tab" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][new_tab]" value="on" />
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-new_tab"><?php _ex( 'Open Link in New Tab', 'backend', 'repairpress-pt' ); ?></label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-icon"><?php _ex( 'Select icon:', 'backend', 'repairpress-pt' ); ?></label> <br />
					<small><?php printf( esc_html__( 'Click on the icon below or manually input icon class from the %s website', 'repairpress-pt' ), '<a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">FontAwesome</a>' ); ?>.</small>
					<input id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-icon" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][icon]" type="text" value="{{icon}}" class="widefat  js-icon-input" /> <br><br>
					<?php foreach ( $this->font_awesome_icons_list as $icon ) : ?>
						<a class="js-selectable-icon  icon-widget" href="#" data-iconname="<?php echo esc_attr( $icon ); ?>"><i class="fa fa-lg <?php echo esc_attr( $icon ); ?>"></i></a>
					<?php endforeach; ?>
				</p>
				<p>
					<input name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][id]" type="hidden" value="{{id}}" />
					<a href="#" class="pt-remove-icon-menu-item  js-pt-remove-icon-menu-item"><span class="dashicons dashicons-dismiss"></span> <?php _ex( 'Remove menu item', 'backend', 'repairpress-pt' ); ?></a>
				</p>
			</script>
			<div class="pt-widget-icon-menu-items" id="icon-menu-items-<?php echo $this->current_widget_id; ?>">
				<div class="icon-menu-items"></div>
				<p>
					<a href="#" class="button  js-pt-add-icon-menu-item"><?php _ex( 'Add New Item', 'backend', 'repairpress-pt' ); ?></a>
				</p>
			</div>
			<script type="text/javascript">
				(function() {
					// repopulate the form
					var iconMenuItemsJSON = <?php echo wp_json_encode( $instance['items'] ) ?>;

					// get the right widget id and remove the added < > characters at the start and at the end.
					var widgetId = '<<?php echo esc_js( $this->current_widget_id ); ?>>'.slice( 1, -1 );

					if ( _.isFunction( RepairPress.Utils.repopulateIconMenuItems ) ) {
						RepairPress.Utils.repopulateIconMenuItems( iconMenuItemsJSON, widgetId );
					}
				})();
			</script>

			<?php
		}

	}
	register_widget( 'PW_Icon_Menu' );
}