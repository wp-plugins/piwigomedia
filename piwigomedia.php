<?php
/*
Plugin Name: PiwigoMedia
Plugin URI: http://joaoubaldo.com
Description: This plugins allows media from a Piwigo site to be inserted into WordPress posts.
Version: 1.1.0
Author: João C.
Author URI: http://joaoubaldo.com
License: GPL2 (see attached LICENSE file)
*/

require_once("shortcode.php");
require_once("widget.php");


function register_piwigomedia_tinymce_button($buttons) {
    array_push($buttons, 'separator', 'piwigomedia');
    return $buttons;
}

function register_piwigomedia_tinymce_plugin($plugin_array) {
    $plugin_array['piwigomedia'] = WP_PLUGIN_URL . 
        '/piwigomedia/tinymce/editor_plugin.js';
    return $plugin_array;
}

function register_piwigomedia_plugin() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
        return;
    if (get_user_option('rich_editing') != 'true')
        return;

    load_plugin_textdomain('piwigomedia', null, 'piwigomedia/languages/');

    add_filter('mce_buttons', 'register_piwigomedia_tinymce_button');
    add_filter('mce_external_plugins', 'register_piwigomedia_tinymce_plugin');

    add_shortcode('pwg-image', 'pwg_image');
    add_shortcode('pwg-category', 'pwg_category');

    wp_register_sidebar_widget("piwigomedia-images", "Piwigo Images", "piwigomedia_widget", 
	array("description" => __("Display Piwigo media on a sidebar widget", "piwigomedia"),
	"site" => "s", "category" => "cate")); 

    wp_enqueue_style('piwigomedia', WP_PLUGIN_URL.'/piwigomedia/css/piwigomedia.css', false, '1.0', 'all');
}

function load_piwigomedia_headers() {
    echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".
    WP_PLUGIN_URL."/piwigomedia/css/piwigomedia.css\"/>";
}

add_action('init', 'register_piwigomedia_plugin');
add_action('widgets_init', function() { return register_widget( "PiwigoMediaWidget" ); });

require_once('piwigomedia_admin.php');
?>
