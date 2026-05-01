<?php

if ( !class_exists('TimelineWidget') ) {

	class TimelineWidget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 * 
		 */
		public function __construct() {
			parent::__construct(
		 		'TwitterTimeline', // Base ID
				'STP - Twitter Timeline', // Name
				array( 'description' => __( 'Show timeline for Tweets from an individual user, a user\'s favorites, Twitter lists, or any search query or hashtag.', 'simple-twitter' ), ) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			
			$timeline = new TwitterTimeline( $instance );
			$timeline->show();
			
			echo $after_widget;
			
			}

		/** @see WP_Widget::update */
		function update( $new_instance, $old_instance ) {
			
			$instance = $old_instance;
			$instance['title']			= strip_tags( $new_instance['title'] );
			$instance['id']				= strip_tags( $new_instance['id'] );
			$instance['width']			= strip_tags( $new_instance['width'] );
			$instance['height']			= strip_tags( $new_instance['height'] );
			$instance['theme']			= strip_tags( $new_instance['theme'] );
			$instance['link_color']		= strip_tags( $new_instance['link_color'] );
			$instance['border_color']	= strip_tags( $new_instance['border_color'] );
			$instance['tweet_limit']	= strip_tags( $new_instance['tweet_limit'] );
			$instance['header']			= ( strip_tags( $new_instance['header'] ) 		== "true" ) ? "true" : "false";
			$instance['footer']			= ( strip_tags( $new_instance['footer'] ) 		== "true" ) ? "true" : "false";
			$instance['border']			= ( strip_tags( $new_instance['border'] ) 		== "true" ) ? "true" : "false";
			$instance['scrollbar']		= ( strip_tags( $new_instance['scrollbar'] ) 	== "true" ) ? "true" : "false";
			$instance['transparent']	= ( strip_tags( $new_instance['transparent'] ) 	== "true" ) ? "true" : "false";

			return $instance;
		}

		/** @see WP_Widget::form */
		function form( $instance ) {
			
			extract( shortcode_atts( array(
					'title' 		=> 'Our Twitter',
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
					'tweet_limit' 	=> '',
			), $instance ) );

			?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					colorPickerInit();
					});
			</script>
			<div class="simple-twitter-widget">
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('id'); ?>"><span class="icon help">
						<span class="tooltip">
							You can read instractions of how to find widget ID <a target="_blank" href="http://blog.topdevs.net/?p=68">here</a>
						</span>
					</span><?php _e('Widget ID'); ?></label>
					<input class="widefat" size="10" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" />
				</p>
				<table>
					<tr><td>
						<label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Color Theme'); ?></label> 
						</td><td>
						<input type="radio" name="<?php echo $this->get_field_name('theme'); ?>" value="light" <?php checked( $theme, 'light'); ?>/> Light<br />
						<input type="radio" name="<?php echo $this->get_field_name('theme'); ?>" value="dark" <?php checked( $theme, 'dark'); ?>/> Dark
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('link_color'); ?>"><?php _e('Link Color'); ?></label>
						</td><td>
						<div class="stp-colorpicker">
							<input class="stp-colorholder" onclick="colorPickerShow(this)" id="<?php echo $this->get_field_id('link_color'); ?>" name="<?php echo $this->get_field_name('link_color'); ?>" class="color"  class="color" value="<?php echo $link_color; ?>"/>
							<div class="stp-colorwheel"></div>
						</div>
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('border_color'); ?>"><?php _e('Border Color'); ?></label> 
						</td><td> 
						<div class="stp-colorpicker">
							<input class="stp-colorholder" onclick="colorPickerShow(this)" id="<?php echo $this->get_field_id('border_color'); ?>" name="<?php echo $this->get_field_name('border_color'); ?>" class="color"  class="color" value="<?php echo $border_color; ?>"/>
							<div class="stp-colorwheel"></div>
						</div>
					</td></tr>
					<tr><td>
						&nbsp;
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Block Width'); ?></label> 
						</td><td>
						<input class="widefat" size="8" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Block Height'); ?></label> 
						</td><td>
						<input class="widefat" size="8" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('tweet_limit'); ?>"><?php _e('Tweet Limit'); ?></label> 
						</td><td>
						<input class="widefat" size="8" id="<?php echo $this->get_field_id('tweet_limit'); ?>" name="<?php echo $this->get_field_name('tweet_limit'); ?>" type="text" value="<?php echo $tweet_limit; ?>" />
					</td></tr>
				</table>
				<table>
					<tr><td>
						&nbsp;
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('header'); ?>"><?php _e('Show Header'); ?></label> 
						</td><td>
						<input id="<?php echo $this->get_field_id('header'); ?>" type="checkbox" value="true" name="<?php echo $this->get_field_name('header'); ?>" <?php checked('true', esc_attr( $header )); ?>/>
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('footer'); ?>"><?php _e('Show Footer'); ?></label> 
						</td><td>
						<input id="<?php echo $this->get_field_id('footer'); ?>" type="checkbox" value="true" name="<?php echo $this->get_field_name('footer'); ?>" <?php checked('true', esc_attr( $footer )); ?>/>
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('border'); ?>"><?php _e('Show Border'); ?></label> 
						</td><td>
						<input id="<?php echo $this->get_field_id('border'); ?>" type="checkbox" value="true" name="<?php echo $this->get_field_name('border'); ?>" <?php checked('true', esc_attr( $border )); ?>/> 
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('scrollbar'); ?>"><?php _e('Show Scrollbar'); ?></label> 
						</td><td>
						<input id="<?php echo $this->get_field_id('scrollbar'); ?>" type="checkbox" value="true" name="<?php echo $this->get_field_name('scrollbar'); ?>" <?php checked('true', esc_attr( $scrollbar )); ?>/> 
					</td></tr>
					<tr><td>
						<label for="<?php echo $this->get_field_id('transparent'); ?>"><?php _e('Transparent Background'); ?></label> 
						</td><td>
						<input id="<?php echo $this->get_field_id('transparent'); ?>" type="checkbox" value="true" name="<?php echo $this->get_field_name('transparent'); ?>" <?php checked('true', esc_attr( $transparent )); ?>/> 
					</td></tr>
				</table>
			</div>
			<?php 
		}
	} // class TimelineWidget
}
?>