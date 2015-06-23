<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


/**
 * Class Tribe__Events__Tickets__Orders_Table
 *
 * See documentation for WP_List_Table
 */
class Tribe__Events__Tickets__Orders_Table extends WP_List_Table {
	/**
	 * Class constructor
	 */
	public function __construct() {
		$args = array(
			'singular' => 'order',
			'plural' => 'orders',
			'ajax' => true,
		);

		parent::__construct( $args );
	}//end __construct

	/**
	 * Display the search box.
	 * We don't want Core's search box, because we implemented our own jQuery based filter,
	 * so this function overrides the parent's one and returns empty.
	 *
	 * @access public
	 *
	 * @param string $text     The search button text
	 * @param string $input_id The search input id
	 */
	public function search_box( $text, $input_id ) {
		return;
	}//end search_box

	/**
	 * Display the pagination.
	 * We are not paginating the order list, so it returns empty.
	 *
	 * @access protected
	 */
	public function pagination( $which ) {
		return '';
	}//end pagination

	/**
	 * Checks the current user's permissions
	 *
	 * @access public
	 */
	public function ajax_user_can() {
		return current_user_can( get_post_type_object( $this->screen->post_type )->cap->edit_posts );
	}//end ajax_user_can

	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'order'           => __( 'Order', 'tribe-events-calendar' ),
			'order_status'    => __( 'Order Status', 'tribe-events-calendar' ),
			'purchased'       => __( 'Purchased', 'tribe-events-calendar' ),
			'ship_to'         => __( 'Ship to', 'tribe-events-calendar' ),
			'purchaser_email' => __( 'Purchaser email', 'tribe-events-calendar' ),
			'message'         => __( 'Customer message', 'tribe-events-calendar' ),
			'notes'           => __( 'Order notes', 'tribe-events-calendar' ),
			'date'            => __( 'Date', 'tribe-events-calendar' ),
			'total'           => __( 'Total', 'tribe-events-calendar' ),
			'actions'         => __( 'Actions', 'tribe-events-calendar' ),
		);

		return $columns;
	}//end get_columns

	/**
	 * Handler for the columns that don't have a specific column_{name} handler function.
	 *
	 * @param $item
	 * @param $column
	 *
	 * @return string
	 */
	public function column_default( $item, $column ) {
		$value = empty( $item[ $column ] ) ? '' : $item[ $column ];

		return apply_filters( 'tribe_events_tickets_orders_table_column', $value, $item, $column );
	}//end column_default

	/**
	 * Handler for the checkbox column
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', esc_attr( $this->_args['singular'] ), esc_attr( "{$item['order_id']}|{$item['provider']}" ) );
	}//end column_cb

	/**
	 * Handler for the order id column
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_order( $item ) {

		//back compat
		if ( empty( $item['order_id_link'] ) ) {
			$id = sprintf( '<a class="row-title" href="%s">%s</a>', esc_url( get_edit_post_link( $item['order_id'], true ) ), esc_html( $item['order_id'] ) );
		} else {
			$id = $item['order_id_link'];
		}

		return $id;
	}//end column_order

	/**
	 * Handler for the order status column
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_order_status( $item ) {
		$icon    = '';
		$warning = false;

		// Check if the order_warning flag has been set (to indicate the order has been cancelled, refunded etc)
		if ( isset( $item['order_warning'] ) && $item['order_warning'] ) {
			$warning = true;
		}

		// If the warning flag is set, add the appropriate icon
		if ( $warning ) {
			$tec  = Tribe__Events__Main::instance();
			$icon = sprintf( "<span class='warning'><img src='%s'/></span> ", trailingslashit( $tec->pluginUrl ) . 'resources/images/warning.png' );
		}

		// Look for an order_status_label, fall back on the actual order_status string @todo remove fallback in 3.4.3
		$label = isset( $item['order_status_label'] ) ? $item['order_status_label'] : ucwords( $item['order_status'] );

		return $icon . $label;
	}//end column_order_status

	/**
	 * Generates content for a single row of the table
	 *
	 * @param object $item The current item
	 */
	public function single_row( $item ) {
		static $row_class = '';
		$row_class = ( $row_class == '' ? ' alternate ' : '' );

		$checked = '';
		if ( intval( $item['check_in'] ) === 1 ) {
			$checked = ' tickets_checked ';
		}

		echo '<tr class="' . sanitize_html_class( $row_class ) . $checked . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}//end single_row

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * Used for the Print, Email and Export buttons, and for the jQuery based search.
	 *
	 */
	public function extra_tablenav( $which ) {

		echo '<div class="alignleft actions">';

		echo sprintf( '<input type="button" name="print" class="print button action" value="%s">', __( 'Print', 'tribe-events-calendar' ) );
		echo sprintf( '<input type="button" name="email" class="email button action" value="%s">', __( 'Email', 'tribe-events-calendar' ) );
		echo sprintf(
			'<a href="%s" class="export button action">%s</a>', esc_url(
				add_query_arg(
					array(
						'attendees_csv'       => true,
						'attendees_csv_nonce' => wp_create_nonce( 'attendees_csv_nonce' ),
					)
				)
			), __( 'Export', 'tribe-events-calendar' )
		);

		echo '</div>';

		if ( 'top' == $which ) {
			echo '<div class="alignright">';
			echo sprintf( '%s: <input type="text" name="filter_attendee" id="filter_attendee" value="">', __( 'Filter by purchaser name, ticket #, order # or security code', 'tribe-events-calendar' ) );
			echo '</div>';
		}
	}//end extra_tablenav

	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'check_in'        => __( 'Check in', 'tribe-events-calendar' ),
			'uncheck_in'      => __( 'Undo Check in', 'tribe-events-calendar' ),
			'delete_attendee' => __( 'Delete', 'tribe-events-calendar' ),
		);

		return (array) apply_filters( 'tribe_events_tickets_attendees_table_bulk_actions', $actions );
	}//end get_bulk_actions

	/**
	 * Handler for the different bulk actions
	 */
	public function process_bulk_action() {
		switch ( $this->current_action() ) {
			case 'check_in':
				$this->bulk_check_in();
				break;
			case 'uncheck_in':
				$this->bulk_uncheck_in();
				break;
			case 'delete_attendee':
				$this->bulk_delete();
				break;
			default:
				do_action( 'tribe_events_tickets_attendees_table_process_bulk_action', $this->current_action() );
				break;
		}
	}//end process_bulk_action

	protected function bulk_check_in() {
		if ( ! isset( $_GET['attendee'] ) ) {
			return;
		}

		foreach ( (array) $_GET['attendee'] as $attendee ) {
			list( $id, $addon ) = $this->attendee_reference( $attendee );
			if ( false === $id ) {
				continue;
			}
			$addon->checkin( $id );
		}
	}

	protected function bulk_uncheck_in() {
		if ( ! isset( $_GET['attendee'] ) ) {
			return;
		}

		foreach ( (array) $_GET['attendee'] as $attendee ) {
			list( $id, $addon ) = $this->attendee_reference( $attendee );
			if ( false === $id ) {
				continue;
			}
			$addon->uncheckin( $id );
		}
	}

	protected function bulk_delete() {
		if ( ! isset( $_GET['attendee'] ) ) {
			return;
		}

		foreach ( (array) $_GET['attendee'] as $attendee ) {
			list( $id, $addon ) = $this->attendee_reference( $attendee );
			if ( false === $id ) {
				continue;
			}
			$addon->delete_ticket( null, $id );
		}
	}//end bulk_delete

	/**
	 * Returns the attendee ID and instance of the specific ticketing solution or "addon" used
	 * to handle it.
	 *
	 * This is used in the context of bulk actions where each attendee table entry is identified
	 * by a string of the pattern {id}|{ticket_class} - where possible this method turns that into
	 * an array consisting of the attendee object ID and the relevant ticketing object.
	 *
	 * If this cannot be determined, both array elements will be set to false.
	 *
	 * @param $reference
	 *
	 * @return array
	 */
	protected function attendee_reference( $reference ) {
		$failed = array( false, false );
		if ( false === strpos( $reference, '|' ) ) {
			return $failed;
		}

		$parts = explode( '|', $reference );
		if ( count( $parts ) < 2 ) {
			return $failed;
		}

		$id = absint( $parts[0] );
		if ( $id <= 0 ) {
			return $failed;
		}

		$addon = call_user_func( array( $parts[1], 'get_instance' ) );
		if ( ! is_subclass_of( $addon, 'Tribe__Events__Tickets__Tickets' ) ) {
			return $failed;
		}

		return array( $id, $addon );
	}//end attendee_reference

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {

		$this->process_bulk_action();

		$event_id = isset( $_GET['event_id'] ) ? $_GET['event_id'] : 0;

		$items = Tribe__Events__Tickets__Tickets::get_event_attendees( $event_id );

		$this->items = $items;
		$total_items = count( $this->items );
		$per_page    = $total_items;

		$this->set_pagination_args(
			 array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => 1,
			 )
		);
	}//end prepare_items
}//end class
