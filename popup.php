<?php
    require_once('functions.php');
    require_once('../../../wp-load.php');

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
        die('no access');

    $categories = array();
    $category = null;
    $images = array();
    $this_cat_id = $_GET['category'] != "" ? $_GET['category'] : 0;
    $this_page = $_GET['cat_page'] != "" ? $_GET['cat_page'] : 0;
    $per_page = get_option('piwigomedia_images_per_page', '30');

    // read site 
    $ws_url = null;
    $ws_urls = array();
    foreach (explode("\n", get_option('piwigomedia_piwigo_urls', '')) as $u) {
        $tu = trim($u);
        if (!empty($tu))
            $ws_urls[] = $tu.'/ws.php';
    }
    $site_idx = $_GET['site'] != "" ? $_GET['site'] : 0;
    $site_idx = abs(intval($site_idx));
    if ($site_idx > count($ws_urls)-1)
        $site_idx = 0;
    if (!empty($ws_urls))
        $ws_url = $ws_urls[$site_idx];
    // end read site

    $cats_res = null;
    $error = null;
    $error_msg = array(
        'not_configured' => __('PiwigoMedia must be configured.', 'piwigomedia'),
        'http_get_failed' => __('Error while reading from', 'piwigomedia').
            ' <span class="highlight">'.$ws_url.'</span>. '.
            __('Please verify PiwigoMedia\'s configuration and try again.', 'piwigomedia'),
        'get_images_failed' => __('Error reading image information, please try again.', 'piwigomedia')
    );

    while (1) {
        if (is_null($ws_url)) {
            $error = 'not_configured';
            break;
        }

        $cats_res = json_decode(
            curl_get(
                $ws_url, 
                array(
                    'format'=>'json', 
                    'method'=>'pwg.categories.getList', 
                    'recursive'=>'true'
                )
            )
        );
        if (is_null($cats_res)) {
            $error = 'http_get_failed';
            break;
        }
        $cats_res = $cats_res->result->categories;

        // build categories array
        foreach ($cats_res as $cat) {
            $parents = array();
            $names = array();

            foreach (explode(',', $cat->uppercats) as $parent_id) {
                $p = null;
                foreach ($cats_res as $c) {
                    if ($c->id == $parent_id) {
                        $p = $c;
                        break;
                    }
                }
                $parents[] = $p;
                $names[] = $p->name;
            }

            $categories[$cat->id] = array(
                'object' => $cat, 
                'parents' => $parents, 
                'breadcrumb_name' => implode('/', $names)
            );
        }
        // --

        // If we're into a Category, set $images and $category
        if ($this_cat_id > 0) {
            $category = $categories[$this_cat_id]['object'];

	        $img_res = json_decode(
                curl_get(
                    $ws_url, 
                    array(
                        'format'=>'json', 
                        'method'=>'pwg.categories.getImages', 
                        'per_page' => $per_page, 
                        'cat_id' => $this_cat_id,
                        'page' => $this_page
                    )
                )
            );

            if (is_null($img_res)) {
                $img_res = $cats_res->result->categories;
                $error = 'get_images_failed';
                break;
            }
            $images = $img_res->result->images;
        }
        // --

        break;
    }
   
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>PiwigoMedia</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <script type='text/javascript' src='<?php echo get_bloginfo('wpurl');?>/wp-includes/js/tinymce/tiny_mce_popup.js'></script>  
        <script type='text/javascript' src='js/jquery-1.5.min.js'></script>  
        <script type='text/javascript' src='js/piwigomedia.js'></script>
        <script type='text/javascript'>
            images = <?php echo json_encode($images); ?>;
            categories = <?php echo json_encode($cats_res); ?>;
            category = <?php echo json_encode($category); ?>;
        </script>
        <link rel='stylesheet' href='css/popup.css' type='text/css' />
    </head>

    <body>
        <h1><span class="piwigo-text">Piwigo</span><span class="media-text">Media</span></h1>
        <?php if (!is_null($error) && $error == 'not_configured') { ?>
            <div class="messages-section section">
                <ul>
                    <li class="error"><?php echo $error_msg[$error]; ?></li>
                </ul>
            </div>
        <?php } else { ?>
            <?php if (!is_null($error)) { ?>
                <div class="messages-section section">
                    <ul>
                        <li class="error"><?php echo $error_msg[$error]; ?></li>
                    </ul>
                </div>
            <?php } ?>
            <div class="site-category-section section">
                <p class="instruction"><?php _e('Choose a site', 'piwigomedia') ?></p>
                <form method="get" class="site-selection" action="">
                    <?php _e('Piwigo site:', 'piwigomedia') ?>
                    <select name="site">
                        <?php
                        foreach($ws_urls as $k=>$url) {
                            $selected = '';
                            if ($site_idx == $k)
                                $selected = 'selected="selected"';
                            echo '<option value="'.$k.'" '.$selected.
                                '>'.$url.'</option>';
                        }
                        ?>
                    </select><input type="submit" class="submit" value="<?php _e('Select', 'piwigomedia') ?>"/>
                </form>
            <?php if (is_null($error)) { ?>
                <p class="instruction"><?php _e('Select a category', 'piwigomedia') ?></p>
                <?php
                echo "<form method=\"get\" class=\"category-selection\"  action=\"\">";
                echo '<input type="hidden" name="cat_page" value="0"/>';
                echo '<input type="hidden" name="site" value="'.$site_idx.'"/>';
                echo __('Category:', 'piwigomedia').' <select name="category">';
                foreach ($categories as $id => $cat) {
                    $selected = ($id == $this_cat_id) ? 'selected="selected"' : '';
                    echo "<option value=\"".$id."\" $selected>".
                            $categories[$id]['breadcrumb_name'].
                        "</option>";
                }
                echo "</select>";
                echo "<input type=\"submit\" class=\"submit\" value=\"".__('Select', 'piwigomedia')."\"/>";
                echo "</form>";
                ?>
            <?php } ?>

            </div>


            <?php if (count($images->_content)) { ?>
            <div class="images-section section">
                <p class="instruction"><?php _e('Select the images', 'piwigomedia') ?></p>

                <?php 
                if (!is_null($this_cat_id) and $this_cat_id > 0) { 
                    echo "<div class='current-category'>";
                    echo "<p class='arrow'>&gt;</p><ol>";
                    foreach($categories[$this_cat_id]['parents'] as $parent) {
                        if ($parent->id != $this_cat_id)
                            echo "<li><a href='".pwm_build_link($parent->id)."'>".
                                $parent->name."</a></li>";
                        else
                            echo "<li class=\"current\">".$parent->name."</li>";
                    }
                    echo "</ol><div style=\"clear: both;\"></div></div>";
                }
                ?>


                <?php
                $pages=ceil(($category->nb_images)/($images->per_page));
                if ($pages > 1) {
                    echo "<div class=\"page-selection\"><ol>";
                    for ($p = 0; $p < $pages; $p++) {
                        $class = array();
                        if ($p == $pages-1)
                            $class[] = "last";
                        if ($p == $this_page)
                            $class[] = "current";
                        if ($p != $this_page) {
                            echo "<li class=\"".implode(' ', $class)."\">".
                                "<a href='".
                                pwm_build_link($this_cat_id, $p, $site_idx).
                                "'>".($p+1)."</a></li>";
                        } else
                            echo "<li class=\"".implode(' ', $class).
                                "\">".($p+1)."</li>";
                    }
                    echo "</ol><div style=\"clear: both;\"></div></div>";
                }
                ?>

                <ul class="image-selector"></ul>
                <div style="clear: both;"></div>
            </div>

            <div class="style-section section">
                <p class="instruction"><?php _e('Customize', 'piwigomedia') ?></p>
                <fieldset>
                    <legend><?php _e('Alignment:', 'piwigomedia') ?></legend>
                    <?php _e('None', 'piwigomedia') ?> <input type="radio" name="alignment" value="none" checked="checked"/>
                    <?php _e('Left', 'piwigomedia') ?> <input type="radio" name="alignment" value="left"/>
                    <?php _e('Center', 'piwigomedia') ?> <input type="radio" name="alignment" value="center"/>
                    <?php _e('Right', 'piwigomedia') ?> <input type="radio" name="alignment" value="right"/>
                </fieldset>
                <fieldset>
                    <legend><?php _e('Link to:', 'piwigomedia') ?></legend>
                    <?php _e('Image page', 'piwigomedia') ?> <input type="radio" name="url" value="page" checked="checked"/>
                    <?php _e('Fullsize image', 'piwigomedia') ?> <input type="radio" name="url" value="fullsize"/>
                </fieldset>
                <fieldset>
                    <legend><?php _e('Link target:', 'piwigomedia') ?></legend>
                    <?php _e('New window', 'piwigomedia') ?> <input type="radio" name="target" value="new" checked="checked"/>
                    <?php _e('Same window', 'piwigomedia') ?> <input type="radio" name="target" value="same"/>
                </fieldset>
            </div>

            <div class="confirmation-section section">
                <div class="confirm-button">
                    <a href="#" onclick="insert_selected();tinyMCEPopup.close();"><?php _e('Insert into post', 'piwigomedia') ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php } ?>
       <?php } ?>
        <div class="footer">PiwigoMedia 2011 - <a href="http://joaoubaldo.com/" target="_blank"><?php _e('author website', 'piwigomedia') ?></a></div>
    </body>
</html>
