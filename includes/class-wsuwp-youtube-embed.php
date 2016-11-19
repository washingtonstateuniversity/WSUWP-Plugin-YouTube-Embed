<?php

class WSUWP_YouTube_Embed {
	/**
	 * @var WSUWP_YouTube_Embed
	 */
	private static $instance;

	/**
	 * Tracks the version number of the plugin for script enqueues.
	 *
	 * @var string
	 */
	public $version = '0.0.1';

	/**
	 * Maintain and return the one instance. Initiate hooks when
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
	 * Setup hooks to include.
	 *
	 * @since 0.0.1
	 */
	public function setup_hooks() {
		add_shortcode( 'wsuwp_youtube', array( $this, 'display_wsuwp_youtube_shortcode' ) );
	}

	/**
	 * Display the HTML associated with the `wsuwp_youtube` shortcode.
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
		);

		$atts = shortcode_atts( $defaults, $atts );

		array_walk( $atts, array( $this, 'sanitize_atts' ) );

		ob_start();
		?>
		<!-- Embedded with wsuwp_youtube shortcode. -->
		<div class="wsuwp-youtube-wrap-outer">
			<div class="wuswp-youtube-wrap-inner">
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
				     data-video-start="<?php echo absint( $atts['start'] ); ?>"></div>
			</div>
		</div>
		<!-- End wsuwp_youtube embed. -->
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		wp_enqueue_script( 'wsuwp-youtube-embed', plugins_url( '/js/video.min.js', __FILE__ ), array( 'jquery' ), $this->version, true );

		return $content;
	}

	/**
	 * Sanitize the attributes passed to the shortcode based on the
	 * expectations of each key.
	 *
	 * @param string|int $value
	 * @param string     $key
	 *
	 * @return int|string
	 */
	public function sanitize_atts( $value, $key ) {
		if ( 'video_id' === $key ) {
			return sanitize_text_field( $value );
		} elseif ( in_array( $key, array( 'width', 'height', 'start', 'end' ), true ) ) {
			return absint( $value );
		} else {
			$value = absint( $value );
			if ( 1 < $value ) {
				return 0;
			}
			return $value;
		}
	}
}
