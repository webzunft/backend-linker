<?php
/**
 * Main plugin class
 */
class Webzunft_Backend_Linker {

	private string $version = '1.0.0';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_head', [ $this, 'add_inline_styles' ] );
		add_filter( 'the_content', [ $this, 'replace_backend_links' ], 1000 );
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$url_string_match = get_option( 'wz_backend_linker_url_string_match' );

		if ( ! $url_string_match ) {
			return;
		}

		wp_enqueue_script(
			'wz-backend-linker',
			plugins_url( 'assets/js/script.js', dirname( __FILE__ ) ),
			[],
			$this->version,
			true
		);

		wp_localize_script(
			'wz-backend-linker',
			'BackendLinkerConfig',
			[
				'urlStringMatch' => esc_js( $url_string_match ),
			]
		);
	}

	/**
	 * Add inline styles
	 *
	 * @return void
	 */
	public function add_inline_styles() {
		?>
		<style>
			.wz-backend-link-footnote {
				visibility: hidden;
				opacity: 0;
				transition: opacity 0.3s ease;
			}

			a:hover + .wz-backend-link-footnote,
			.wz-backend-link-footnote:hover {
				visibility: visible;
				opacity: 1;
				background: #fff;
			}
		</style>
		<?php
	}

	/**
	 * Replace the link placeholders with actual links when a user matches the criteria
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function replace_backend_links( $content ): string {
		global $wp_query;

		// If it's not the main query, return the content untouched.
		if ( ! $wp_query->is_main_query() ) {
			return $content;
		}

		// Check if the content has already been processed
		if ( strpos( $content, 'id="wz-backend-linker-footnote"' ) !== false ) {
			return $content;  // Content has already been processed.
		}

		$match_targets_option = get_option( 'wz_backend_linker_match_targets', '' );
		$backend_links = [];
		// Break the option value into individual lines
		$lines = explode( "\n", $match_targets_option );

		foreach ( $lines as $line ) {
			list( $label, $endpoint ) = array_map( 'trim', explode( '|', $line, 2 ) );

			if ( ! empty( $label ) && ! empty( $endpoint ) ) {
				$backend_links[ $label ] = $endpoint;
			}
		}

		if ( ! $backend_links) {
			return $content;
		}

		$found_links = false;
		foreach ( $backend_links as $text => $link ) {
			$link     = '<span class="wz-backend-link-placeholder" data-link-goal="' . esc_attr( $link ) . '">' . $text . '</span>';
			$footnote = '<sup class="wz-backend-link-footnote" style="visibility:hidden; margin-right: -10px;"><a href="#wz-backend-linker-footnote">(?)</a></sup>';

			if ( strpos( $content, $text ) !== false ) {
				$content     = str_replace( $text, $link . $footnote, $content );
				$found_links = true;
			}
		}

		$details_url = get_option( 'wz_backend_linker_details_url' );

		if ( $found_links ) {
			$footnote = '<p id="wz-backend-linker-footnote" style="display:none;">(?) ' . esc_html__( 'Navigation links lead back to the site you came from. No data is stored.', 'webzunft-backend-linker' );
			if ( ! empty( $details_url ) ) {
				$footnote .= ' <a href="' . esc_url( $details_url ) . '" target="_blank">' . esc_html__( 'Privacy Policy', 'webzunft-backend-linker' ) . '</a>';
			}
 			$footnote .= '</p>';

			$content .= $footnote;
		}

		return $content;
	}
}
