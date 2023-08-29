<?php
/**
 * Admin class for the Backend Linker plugin
 */
class Webzunft_Backend_Linker_Admin {

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'add_settings_fields' ] );
	}

	public function register_settings() {
		register_setting( 'reading', 'wz_backend_linker_url_string_match', [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field'
		] );

		register_setting( 'reading', 'wz_backend_linker_match_targets', [
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'sanitize_match_targets' ]
		] );

		register_setting( 'reading', 'wz_backend_linker_details_url', [
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw'
		] );
	}

	public function add_settings_fields() {
		add_settings_field(
			'wz_backend_linker_url_string_match',
			__( 'Backend Linker URL String Match', 'webzunft-backend-linker' ),
			[ $this, 'settings_callback' ],
			'reading'
		);

		add_settings_field(
			'wz_backend_linker_match_targets',
			__( 'Backend Linker Match & Targets', 'webzunft-backend-linker' ),
			[ $this, 'match_targets_callback' ],
			'reading'
		);

		add_settings_field(
			'wz_backend_linker_details_url',
			__( 'Backend Linker URL to more details', 'webzunft-backend-linker' ),
			[ $this, 'details_url_callback' ],
			'reading'
		);
	}

	public function settings_callback() {
		$url_string_match = get_option( 'wz_backend_linker_url_string_match' );
		echo '<input type="text" class="regular-text" name="wz_backend_linker_url_string_match" value="' . esc_attr( $url_string_match ) . '" />';
	}

	public function match_targets_callback() {
		$match_targets = get_option( 'wz_backend_linker_match_targets' );
		echo '<textarea class="large-text" name="wz_backend_linker_match_targets" rows="5" cols="50">' . esc_textarea( $match_targets ) . '</textarea>';
	}

	public function details_url_callback() {
		$details_url = get_option( 'wz_backend_linker_details_url' );
		echo '<input type="text" class="large-text" name="wz_backend_linker_details_url" value="' . esc_url( $details_url ) . '" placeholder="https://your-privacy-policy-url.com" />';
	}

	// Custom sanitization callback
	public function sanitize_match_targets( $input ): string {
		// Break string into lines
		$lines = explode( "\n", $input );

		// Trim and sanitize each line
		$cleaned = array_map( function( $line ) {
			return sanitize_text_field( trim( $line ) );
		}, $lines );

		// Reassemble and return
		return implode( "\n", $cleaned );
	}
}
