<?php
/*
Plugin Name: Simple Twitter Plugin
Plugin URI: http://plugins.topdevs.net/simple-twitter-plugin/
Description: Display timeline for Tweets from an individual user, a user’s favorites, Twitter lists, or any search query or hashtag as a widget in your sidebar.
Version: 1.2
Author: Ilya K.
Author URI: http://codecanyon.net/user/topdevs/portfolio?ref=topdevs
License: GPL
*/

define ("SIMPLE_TWITTER_PATH", plugin_dir_path(__FILE__) );
define ("SIMPLE_TWITTER_URL", plugins_url('', __FILE__) );

require_once 'lib/classes/TwitterTimeline.php';
require_once 'lib/classes/TimelineWidget.php';
require_once 'lib/classes/SimpleTwitterPlugin.php';

add_action('init', array('SimpleTwitterPlugin', 'init') );
// Add widget
add_action('widgets_init', array('SimpleTwitterPlugin', 'registerWidget') );
?>
