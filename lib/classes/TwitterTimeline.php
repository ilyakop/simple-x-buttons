<?php 

if ( !class_exists('TwitterTimeline') ) {

	class TwitterTimeline {

		public function __construct( $instance ) {
			
			extract( shortcode_atts( array(
					'id' 			=> '',
					'width' 		=> '520',
					'height' 		=> '600',
					'theme' 		=> 'light',
					'link_color' 	=> '#333333',
					'border_color' 	=> '#e8e8e8',
					'header' 		=> 'true',
					'footer' 		=> 'true',
					'border' 		=> 'true',
					'scrollbar' 	=> 'true',
					'transparent' 	=> 'false',
					'tweet_limit' 	=> '10',
			), $instance ) );

			$this->id 			= $id;
			$this->width 		= $width;
			$this->height 		= $height;
			$this->theme 		= $theme;
			$this->linkColor 	= $link_color;
			$this->borderColor 	= $border_color;
			$this->tweetLimit 	= $tweet_limit;
			$this->chrome 		= "";
			$this->chrome 		.= ( $header == 'false') 		? 'noheader ' : '';
			$this->chrome 		.= ( $footer == 'false') 		? 'nofooter ' : '';
			$this->chrome 		.= ( $borders == 'false') 		? 'noborders ' : '';
			$this->chrome 		.= ( $scrollbar == 'false') 	? 'noscrollbar ' : '';
			$this->chrome 		.= ( $transparent == 'true') 	? 'transparent ' : '';
			
		}

		public function show() {

			require SIMPLE_TWITTER_PATH . 'lib/views/twitter_timeline.php';

		}
	}
}
?>