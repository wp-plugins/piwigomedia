<?php

require_once("functions.php");

function pwg_category( $atts ) {
        extract( shortcode_atts( array(
                'site'=>NULL, 'id'=>NULL, 'images'=>10, 'page'=>0
        ), $atts ) );

	$params = array(
		"format" => "json", 
		"method" => "pwg.categories.getImages",
		"cat_id" => $id, 
		"page" => $page, 
		"per_page" => $images);
        $res = pwm_curl_get($site."/ws.php", $params);
        $res = json_decode($res);
        if ($res->stat != "ok")
		return;
	$out = "";
	if ($res->result->images->count > 0) {
		$out .= "<ul class=\"piwigomedia-category-preview\">";
		foreach($res->result->images->_content as $img) {
			$out .= "<li><a class=\"piwigomedia-single-image\" href=\"".$img->element_url."\"><img src=\"".$img->derivatives->thumb->url."\"></a></li>";
		}
		$out .= "</ul>";
	}
        return "$out";
}


function pwg_image( $atts ) {
        extract( shortcode_atts( array(
                'site'=>NULL, 'id'=>NULL, 
        ), $atts ) );

        $params = array(
                "format" => "json", 
                "method" => "pwg.images.getInfo",
                "image_id" => $id, 
                "comments_page" => 0);
        $res = pwm_curl_get($site."/ws.php", $params);
        $res = json_decode($res);
        if ($res->stat != "ok")
                return;
        $out = "<a class=\"piwigomedia-single-image\" href=\"".$res->result->element_url."\"><img src=\"".$res->result->derivatives->thumb->url."\"></a>";
        return "$out";
}


?>
