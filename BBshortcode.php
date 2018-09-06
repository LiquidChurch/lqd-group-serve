<?php/* Button shortcode */

	/* GC 9/5/2018 */
class bb_SC {

// $attributes is an array of passed-in attributes. It would look like
// [ 'url' => 'liquidchurch.com' ].
// $content is a string of passed-in shortcode content. It would look like
// 'This is the text that should go inside my button'.
	function bb_init() {
		function bb( $attributes, $content = null ) {
// Save each attribute's value to its own variable.
// This creates a variable $align with a value of 'left'.
//extract( shortcode_atts( array(
//	'url' => ''
//), $attributes ) );
			$args = shortcode_atts(
				array(
					'url' => '',
				),
				$attributes
			);
			$url  = $args['url'];

// Return a string to display on the page

			return '<a class="blue_btn" style="width: 45%;" href="https://' . $url . '" target="_blank" rel="noopener">' . $content . '</a> </center>';
		}

		add_shortcode( 'bb', 'bb' );
	}add_action( 'init', 'bb_init' );// Register lqd-group-serve-cpt
}
register_activation_hook(( __FILE__, array( &$bb_SC, 'plugin_activation' ) );
register_deactivation_hook(( __FILE__, array( &$bb_SC, 'plugin_deactivation' ) );

