<?php
// redirect all link to right url address
// update_option('siteurl', 'http://beaver-global.com/clients/hotels');
// update_option('home', 'http://beaver-global.com/clients/hotels');

// require
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/wilson/helpers/admin-settings.php');

//Helpers
include_once(get_template_directory() . '/helpers/filters.php');
// include_once(get_template_directory() . '/helpers/acf-custom-fields.php');
//lang-switcher
function lang_switcher(){
	if (function_exists('icl_get_languages')) {
		$langs = icl_get_languages('skip_missing=0');
		foreach ($langs as $lang) {
			if ($lang['active'] == 0) {
				$flag = preg_replace(' ', '', $lang['country_flag_url']);
				$display_lang = ($lang['native_name'] == 'עברית') ? "עבר" : ucfirst($lang['native_name']);
			    $strlang =  mb_substr($display_lang,0,3);
				echo '<a class="lang-switcher lang-switch" href="'. $lang['url'] .'">'.'<span class="dropdown-item d-inline-block"></span><img src="'. $lang['country_flag_url'] . '"></a>';
			}
		}
	}
}

/**
 * GET Src and ALT IMG FROM POST THUMBNAIL
 */
function getSrcAltImage($postID,$size=false){
	$imgID  = get_post_thumbnail_id($postID);
	$img    = wp_get_attachment_image_src($imgID,$size, false, '');
	$imgAlt = get_post_meta($imgID,'_wp_attachment_image_alt', true);
	$imgAttr = array(
		'url' => $img,
		'alt' => $imgAlt
	);
	if(is_shop()){
		echo '<section class="shop-image">';
		echo '<img class="img-max" src="' . $imgAttr['url'][0] . '" alt="" />';
		echo '</section>';
	}else{
		return $imgAttr;
	}
}

//get term attached to the post from custom post type by post
function get_term_attach_post($post){
	$taxonomy = get_post_taxonomies( $post );
	$terms    = get_the_terms( $post, $taxonomy );
	return $terms;
}
