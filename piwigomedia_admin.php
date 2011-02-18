<?php
function register_my_menu() {
    if (!current_user_can('manage_options'))
        return;
    add_options_page('PiwigoMedia', 'PiwigoMedia', 'manage_options', 
        'piwigomedia-plugin', 'render_options_html');
}

function register_my_settings() {
    if (!current_user_can('manage_options'))
        return;
    register_setting('piwigomedia-options', 'piwigomedia_piwigo_url');
    register_setting('piwigomedia-options', 'piwigomedia_images_per_page', 
        'absint');
}

function render_options_html() {
    ?>
    <div class="wrap"> 
	    <div id="icon-options-general" class="icon32"><br />
    </div> 
    <h2><?php _e('PiwigoMedia settings', 'piwigomedia') ?></h2> 

    <form action="options.php" method="post">
        <?php settings_fields('piwigomedia-options'); ?>
        <h3><?php _e('Primary settings', 'piwigomedia') ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Piwigo URL', 'piwigomedia') ?> <span class="description">(<?php _e('Required field', 'piwigomedia') ?>)</span></th>
                <td><input type="text" name="piwigomedia_piwigo_url" 
                    value="<?php echo get_option('piwigomedia_piwigo_url', 
                        'http://localhost/piwigo'); ?>" class="regular-text"/>
                    <span class="description">(<?php _e('Example: http://localhost/piwigo.', 'piwigomedia') ?>)</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Images per page', 'piwigomedia') ?></th>
                <td><input type="text" name="piwigomedia_images_per_page" 
                    value="<?php echo get_option('piwigomedia_images_per_page', '30'); ?>" class="small-text"/>
                    <span class="description">(<?php _e('Number of images to display per page, in the selection screen.', 'piwigomedia') ?>)</span>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save changes', 'piwigomedia') ?>" />
        </p>
    </form>
    <?php
}

add_action('admin_menu', 'register_my_menu');
add_action('admin_init', 'register_my_settings');
?>
