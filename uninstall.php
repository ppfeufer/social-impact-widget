<?php
/**
 * Uninstall
 *
 * Cleaning up the Database
 */

global $wpdb;

if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}

delete_option('widget_social_impact_widget');
delete_transient('twitter-count');
delete_transient('googleplus-count');
delete_transient('feedburner-count');
delete_transient('fanpage-count');
?>