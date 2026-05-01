<?php 

if ( !class_exists('SimpleTwitterPlugin') ) {

	class SimpleTwitterPlugin {

		static function init() {

			// Add actions
			add_action('wp_enqueue_scripts',	array('SimpleTwitterPlugin', 'enqueueScripts') );
			add_action('admin_enqueue_scripts',	array('SimpleTwitterPlugin', 'enqueueScriptsAdmin') );
		}

		static function enqueueScriptsAdmin() {

			wp_enqueue_script('jquery');

			// add colorpicker
			wp_enqueue_script('farbtastic');
			wp_enqueue_style('farbtastic');

			// add custom js
			wp_register_script('simple-twitter', SIMPLE_TWITTER_URL . '/lib/js/simple-twitter.js', array('jquery'), '1.0' );
			wp_enqueue_script('simple-twitter');

			// add custom css
			wp_register_style('simple-twitter', SIMPLE_TWITTER_URL . '/lib/css/simple-twitter.css' );
			wp_enqueue_style('simple-twitter');
		}

		static function enqueueScripts() {

			// add custom js
			wp_register_script('simple-twitter', SIMPLE_TWITTER_URL . '/lib/js/twitter.js' );
			wp_enqueue_script('simple-twitter');
		}

		/**
		 * @method registerWidget
		 * @author Ilya K.
		 */
		
		public function registerWidget() {
		
			register_widget('TimelineWidget');
		
		}
	}
}
?>