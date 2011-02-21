<?php
function curl_post($url, array $post = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => http_build_query($post)
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

function curl_get($url, array $get = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). 
            http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

function pwm_build_link($cat_id=null, $cat_page=null, $site=null) {
    $args = array();
    if (!is_null($cat_id))
        $args[] = 'category='.$cat_id;
    if (!is_null($cat_page))
        $args[] = 'cat_page='.$cat_page;
    if (!is_null($site))
        $args[] = 'site='.$site;
    return $_SERVER['PHP_SELF'].'?'.implode('&amp;', $args);
}


function pwm_get_category($categories, $cat_id) {
    foreach($categories as $cat) {
        if ($cat->id == $cat_id) {
            return $cat;
        }
    }
    return null;
}

?>
