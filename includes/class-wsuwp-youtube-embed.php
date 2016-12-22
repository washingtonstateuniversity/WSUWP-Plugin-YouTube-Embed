<?php

class WSUWP_YouTube_Embed {
	/**
	 * @since 0.1.1
	 *
	 * @var WSUWP_YouTube_Embed
	 */
	private static $instance;

	/**
	 * Tracks the version number of the plugin for script enqueues.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $version = '0.0.1';

	/**
	 * Maintains and returns the one instance. Initiates hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_YouTube_Embed
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_YouTube_Embed();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Adds hooks.
	 *
	 * @since 0.0.1
	 */
	public function setup_hooks() {
		add_shortcode( 'wsuwp_youtube', array( $this, 'display_wsuwp_youtube_shortcode' ) );
	}

	/**
	 * Displays the HTML associated with the `wsuwp_youtube` shortcode.
	 *
	 * @since 0.0.1
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_wsuwp_youtube_shortcode( $atts ) {
		$defaults = array(
			'video_id' => '',
			'width' => '560',
			'height' => '315',
			'autoplay' => 0,
			'controls' => 1,
			'end' => '',
			'loop' => 0,
			'modestbranding' => 1,
			'playsinline' => 0,
			'rel' => 0,
			'showinfo' => 0,
			'start' => 0,
			'volume' => 'default',
		);

		$atts = shortcode_atts( $defaults, $atts );

		array_walk( $atts, array( $this, 'sanitize_atts' ) );

		ob_start();
		?>
		<!-- Embedded with wsuwp_youtube shortcode. -->
		<div class="wsuwp-youtube-wrap-outer">
			<div class="wsuwp-youtube-wrap-inner">
				<div class="wsuwp-youtube-embed"
				     id="wsuwp-youtube-video-<?php echo esc_attr( $atts['video_id'] ); ?>"
				     data-video-id="<?php echo esc_attr( $atts['video_id'] ); ?>"
				     data-video-width="<?php echo absint( $atts['width'] ); ?>"
				     data-video-height="<?php echo absint( $atts['height'] ); ?>"
				     data-video-autoplay="<?php echo absint( $atts['autoplay'] ); ?>"
				     data-video-controls="<?php echo absint( $atts['controls'] ); ?>"
				     data-video-end="<?php echo absint( $atts['end'] ); ?>"
				     data-video-loop="<?php echo absint( $atts['loop'] ); ?>"
				     data-video-modestbranding="<?php echo absint( $atts['modestbranding'] ); ?>"
				     data-video-playsinline="<?php echo absint( $atts['playsinline'] ); ?>"
				     data-video-rel="<?php echo absint( $atts['rel'] ); ?>"
				     data-video-showinfo="<?php echo absint( $atts['showinfo'] ); ?>"
				     data-video-start="<?php echo absint( $atts['start'] ); ?>"
				     data-video-volume="<?php echo esc_attr( $atts['volume'] ); ?>"></div>
			</div>
		</div>
		<!-- End wsuwp_youtube embed. -->
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		wp_enqueue_script( 'wsuwp-youtube-embed', plugins_url( '/js/video.min.js', dirname( __FILE__ ) ), array( 'jquery' ), $this->version, true );

		return $content;
	}

	/**
	 * Sanitizes the attributes passed to the shortcode based on the
	 * expectations of each key.
	 *
	 * @since 0.0.1
	 *
	 * @param string|int $value
	 * @param string     $key
	 */
	public function sanitize_atts( &$value, $key ) {
		if ( 'video_id' === $key ) {
			$value = sanitize_text_field( $value );
		} elseif ( in_array( $key, array( 'width', 'height', 'start', 'end' ), true ) ) {
			$value = absint( $value );
		} elseif ( 'volume' === $key && in_array( $value, array( 'default', 'mute' ), true ) ) {
			return; // value remains the same.
		} else {
			$value = absint( $value );
			if ( 1 < $value ) {
				$value = 0;
			}
		}
	}
}
