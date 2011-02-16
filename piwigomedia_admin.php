<?php
function register_my_menu() {
    add_options_page('PiwigoMedia Options', 'PiwigoMedia', 'manage_options', 
        'piwigomedia-plugin', 'render_options_html');
}

function register_my_settings() {
    register_setting('piwigomedia-options', 'piwigomedia_piwigo_url');
    register_setting('piwigomedia-options', 'piwigomedia_piwigo_ws_path');
    register_setting('piwigomedia-options', 'piwigomedia_photos_per_page', 
        'absint');
}

function render_options_html() {
    ?>
    <div class="wrap"> 
	    <div id="icon-options-general" class="icon32"><br />
    </div> 
    <h2>PiwigoMedia settings</h2> 

    <form action="options.php" method="post">
        <?php settings_fields('piwigomedia-options'); ?>
        <h3>Main settings</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Piwigo URL <span class="description">(required)</span></th>
                <td><input type="text" name="piwigomedia_piwigo_url" 
                    value="<?php echo get_option('piwigomedia_piwigo_url', 
                        'http://localhost/piwigo'); ?>" class="regular-text"/>
                    <span class="description">eg. http://localhost/piwigo</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Total photos per page</th>
                <td><input type="text" name="piwigomedia_photos_per_page" 
                    value="<?php echo get_option('piwigomedia_photos_per_page', '30'); ?>" class="small-text"/>
                    <span class="description">number of photos to display, per page, in the selection screen</span>
                </td>
            </tr>
        </table>
        <h3>Extra settings</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Web service script:</th>
                <td><input type="text" name="piwigomedia_piwigo_ws_path" 
                    value="<?php echo get_option('piwigomedia_piwigo_ws_path', 
                        'ws.php'); ?>"/>
                    <span class="description">only change this value if you know what it is. default: ws.php</span>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
    <?php
}


add_action('admin_menu', 'register_my_menu');
add_action('admin_init', 'register_my_settings');
?>
