<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

class Save_Post_Data {

	protected $settings;
	protected $post_types;
	protected $nonce_name;
	protected $nonce_action;
	protected $save_callback;


	public function __construct( $settings, $post_types = array(), $nonce_name, $nonce_action, $save_setting_callback = false, $add_actions = true ) {

		$this->settings               = $this->fill_settings( $settings );
		$this->post_types             = ( ! is_array( $post_types ) ) ? array( $post_types ) : $post_types;
		$this->nonce_name             = $nonce_name;
		$this->nonce_action           = $nonce_action;
		$this->save_setting_callback  = $save_setting_callback;

		if ( $add_actions ) {

			$this->add_save_actions();

		} // End if

	} // End __construct


	protected function add_save_actions() {

		if ( 1 === count( $this->post_types ) ) {

			$post_type = reset( $this->post_types );

			add_action( 'save_post_' . $post_type, array( $this, 'save_post' ), 10, 3 );

		} else {

			add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );

		} // End if

	} // End add_save_actions


	protected function fill_settings( $settings ) {

		$setting_defaults = array(
			'sanitize_type'      => 'text',
			'default'            => '',
			'check_isset'        => true,
			'ignore_empty'       => false,
			'sanitize_callback'  => false,
		);

		foreach ( $settings as $key => $data ) {

			$settings[ $key ] = array_merge( $setting_defaults, $data );

		} // End foreach

		return $settings;

	} // End fill_settings


	public function save_post( $post_id, $post, $update ) {

		if ( in_array( $post->post_type, $this->post_types, true ) ) {

			if ( ! $update ) {

				return;

			} // End if

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

				return false;

			} // end if

			if ( ! isset( $_POST[ $this->nonce_name ] ) || ! wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) ) {

				return false;

			}

			// Check the user's permissions.
			if ( 'page' === $post->post_type ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {

					return false;

				} // end if
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {

					return false;

				} // end if
			} // end if

			foreach ( $this->settings as $key => $data ) {

				if ( isset( $_REQUEST[ $key ] ) ) {

					$value = $this->sanitize_request_value( $key, $data['sanitize_type'], $data['sanitize_callback'] );

					if ( $this->save_setting_callback ) {

						$callback = $this->save_setting_callback;

						if ( is_callable( $callback ) ) {

							$value = call_user_func( $callback, $key, $value, $post_id, $data, $this->settings );

						} // End if
					} // End if

					if ( $data['ignore_empty'] ) {

						if ( '' !== $value ) {

							update_post_meta( $post_id, $key, $value );

						} // End if
					} else {

						update_post_meta( $post_id, $key, $value );

					} // End if
				} // End if
			} // End foreach
		} // End if

	} // End save_post


	protected function sanitize_request_value( $key, $type, $callback = false ) {

		if ( $callback ) {

			if ( is_callable( $callback ) ) {

				$sent_value = $_REQUEST[ $key ];

				$value = call_user_func( $callback, $key, $sent_value );

			} // End if
		} else {

			switch ( $type ) {

				case 'array':
					$value = $this->sanitize_array( $_REQUEST[ $key ] );
					break;
				default:
					$value = sanitize_text_field( $_REQUEST[ $key ] );
					break;

			} // End switch

		} // End if

		return $value;

	} // End sanitize_request_value


	public function sanitize_assoc_array( $array ) {

		$clean_array = array();

		if ( is_array( $array ) ) {

			foreach ( $array as $key => $value ) {

				if ( ! is_numeric( $key ) ) {

					$key = sanitize_text_field( $key );

				} // End if

				if ( is_array( $value ) ) {

					$value = sanitize_text_field( $value );

				} // End if

				$clean_array[ $key ] = $value;
			} // End foreach
		} // End if

		return $clean_array;

	} // End sanitize_array


	public function sanitize_array( $array ) {

		$clean_array = array();

		if ( is_array( $array ) ) {

			foreach ( $array as $key => $value ) {

				if ( ! is_numeric( $key ) ) {

					$key = sanitize_text_field( $key );

				} // End if

				if ( is_array( $value ) ) {

					$value = $this->sanitize_array( $value );

				} else {

					$value = sanitize_text_field( $value );

				}// End if

				$clean_array[ $key ] = $value;
			} // End foreach
		} // End if

		return $clean_array;

	} // End sanitize_array

}
