<?php
    require_once('../functions.php');
    require_once('../../../../wp-load.php');

    $categories = array();
    $category = null;
    $images = array();
    $this_cat_id = $_GET['category'] != "" ? $_GET['category'] : 0;
    $this_page = $_GET['cat_page'] != "" ? $_GET['cat_page'] : 0;
    $per_page = get_option('piwigomedia_photos_per_page', '30');
    $ws_url = get_option('piwigomedia_piwigo_url').'/'.
        get_option('piwigomedia_piwigo_ws_path');
    $cats_res = null;
    $error = null;
    $error_msg = array(
        'not_configured' => 'Failed to read from <span class="highlight">'.
            $ws_url.'</span>. Please verify PiwigoMedia settings (<span '.
            'class="highlight">Settings/PiwigoMedia</span>).',
        'get_images_failed' => 'Failed to retrieve images. Try again.'
    );

    while (1) {
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
            $error = 'not_configured';
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
        <?php if (!is_null($error)) { ?>
            <div class="messages-section">
                <ul>
                    <li class="error"><?php echo $error_msg[$error]; ?></li>
                </ul>
            </div>
       <?php } else { ?>
            <div class="category-section">
                <p class="instruction">1. Category</p>
                <?php 
                if (!is_null($this_cat_id) and $this_cat_id > 0) { 
                    echo "<div class='current-category'><ol>";
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
                echo "<form method=\"GET\" class=\"category-selection\">";
                echo "Change category: ";
                echo "<select name='category'>";
                foreach ($categories as $id => $cat) {
                    $selected = ($id == $this_cat_id) ? 'selected' : '';
                    echo "<option value=".$id." $selected>".
                            $categories[$id]['breadcrumb_name'].
                        "</option>";
                }
                echo "</select>";
                echo "<input type=\"submit\" value=\"Change\">";
                echo "</form>";
                ?>
            </div>


            <?php if (count($images->_content)) { ?>
            <div class="images-section">
                <p class="instruction">2. Choose images</p>
                <?php
                $pages=ceil(($category->total_nb_images)/($images->per_page));
                if ($pages > 1) {
                    echo "<div class=\"page-selection\"><ol>";
                    for ($p = 0; $p < $pages; $p++) {
                        if ($p != $this_page)
                            echo "<li><a href='".
                                pwm_build_link($this_cat_id, $p).
                                "'>".($p+1)."</a></li>";
                        else
                            echo "<li class=\"current\">".($p+1)."</li>";
                    }
                    echo "</ol></div>";
                }
                ?>

                <div class="image-nav">
                    <?php
                    if (!is_null($prev_page))
                        echo "<a href=".pwm_build_link($this_cat_id, $prev_page).
                            " class=\"previous\">&lt;&lt;</a>";
                    else
                        echo "<a class=\"previous\">&nbsp;</a>";
                    if (!is_null($next_page))
                        echo "<a href=".pwm_build_link($this_cat_id, $next_page).
                            " class=\"next\">&gt;&gt;</a>";
                    else
                        echo "<a class=\"next\">&nbsp;</a>";
                    ?>
                    <div style="clear: both;"></div>
                </div>

                <ul class="image-selector"></ul>
                <div style="clear: both;"></div>
            </div>

            <div class="style-section">
                <p class="instruction">3. Style</p>
                <fieldset>
                    <legend>Alignment</legend>
                    None <input type="radio" name="alignment" value="none" checked>
                    Left <input type="radio" name="alignment" value="left">
                    Center <input type="radio" name="alignment" value="center">
                    Right <input type="radio" name="alignment" value="right">
                </fieldset>
                <fieldset>
                    <legend>Link target</legend>
                    New window <input type="radio" name="target" value="new" checked>
                    Same window <input type="radio" name="target" value="same">
                </fieldset>
                <fieldset>
                    <legend>Link URL</legend>
                    Image page <input type="radio" name="url" value="page" checked>
                    Fullsize image <input type="radio" name="url" value="fullsize">
                </fieldset>
            </div>

            <div class="confirmation-section">
                <div class="confirm-button">
                    <a href="#" onclick="insert_selected();tinyMCEPopup.close();">Insert into Post</a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php } ?>
       <?php } ?>
        <div class="footer">PiwigoMedia - <a href="http://joaoubaldo.com/" target="_blank">author website</a></div>
    </body>
</html>
