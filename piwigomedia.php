<?php
/*
Plugin Name: PiwigoMedia
Plugin URI: http://joaoubaldo.com
Description: This plugins allows media from a Piwigo site to be inserted into WordPress posts.
Version: 0.9.1
Author: João C.
Author URI: http://joaoubaldo.com
License: GPL2 (see attached LICENSE file)
*/


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
    add_action('wp_head', 'load_piwigomedia_headers');
    add_action('admin_head', 'load_piwigomedia_headers');

}

function load_piwigomedia_headers() {
    echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".
    WP_PLUGIN_URL."/piwigomedia/css/piwigomedia.css\"/>";
}

add_action('init', 'register_piwigomedia_plugin');

require_once('piwigomedia_admin.php');
?>
