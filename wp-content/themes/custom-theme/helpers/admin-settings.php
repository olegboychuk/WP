<?php
/**
 * Load site scripts.
 */
function loadScript() {
	$template_url = get_template_directory_uri();
	// jQuery.
	wp_enqueue_script( 'jquery' );
	// // animate
	// wp_enqueue_style( 'animate', $template_url . '/css/animate.css' );
	// fontawesome
	wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.0.13/css/all.css' );
	// icomoon
	wp_enqueue_style( 'icomoon', 'https://cdn.linearicons.com/free/1.0.0/icon-font.min.css' );
	// Oswald
	wp_enqueue_style( 'oswald', '//fonts.googleapis.com/css?family=Oswald:400,600' );
	// sans
	wp_enqueue_style( 'sans', '//fonts.googleapis.com/earlyaccess/opensanshebrew.css' );

	// Swiper
	wp_enqueue_style( 'swiper-css', $template_url . '/css/swiper.min.css' );
	wp_enqueue_script( 'swiper-js', $template_url . '/js/swiper.min.js' );
	// Bootstrap css
	wp_enqueue_style( 'bootstrap-style', $template_url . '/css/bootstrap.4.1.1.min.css' );
	if( is_rtl() ):
		wp_enqueue_style( 'bootstrap-style-rtl', $template_url . '/css/bootstrap-rtl.min.css' );
	endif;
	// popper js
	wp_enqueue_script( 'popper-script', $template_url . '/js/popper.min.js' );
	// Bootstrap js
    wp_enqueue_script( 'bootstrap-script', $template_url . '/js/bootstrap.4.1.1.min.js' );


	// load the local copy of jQuery in the footer
    // wp_enqueue_script('jquery', '/wp-includes/js/jquery/jquery.js', false, '1.3.2', true);

	//script
	wp_enqueue_script( 'script', $template_url . '/js/script.js' );
	//Main Style
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	// Load Thread comments WordPress script.
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// AJAX
	// wp_localize_script( 'script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	// Load AJAX
	if(!is_admin()) {

		wp_localize_script( 'script', 'MyAjax', array(
			// URL to wp-admin/admin-ajax.php to process the request
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'current_page' => 2,
			// generate a nonce with a unique ID "myajax-post-comment-nonce"
			// so that you can check it later when an AJAX request is sent
			'security' => wp_create_nonce( 'my-special-string' )
		));
	}
	if(is_admin()) {
		wp_localize_script( 'script-js-admin', 'AdminAjax', array(
			// URL to wp-admin/admin-ajax.php to process the request
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			// generate a nonce with a unique ID "myajax-post-comment-nonce"
			// so that you can check it later when an AJAX request is sent
			'security' => wp_create_nonce( 'my-special-string' )
		));
	}
}
add_action( 'wp_enqueue_scripts', 'loadScript', 1 );

//AJAX Type
$ajax_type = is_user_logged_in() ? '' : 'nopriv_';

/**
 * LOOPS Registers the Custom post type.
 */
function hotels_post_type() {
	$postTypes = array(
		'regulation' => array(
			'title'     => __('Regulation','hotels'),
			'menu_icon'	=> 'dashicons-universal-access-alt',
			'hierarchical' => true,
		),
	);

	foreach ($postTypes as $key => $value) {
		$args =	array(
			'labels' => array(
				"name"			     =>	__( $value['title'], 'hotels'),
				"singular_name"	     =>	__( $value['title'], 'hotels'),
				"add_new"			 =>	__('Add '. $value['title'] , 'hotels'),
				"add_new_item"	     =>	__('Add New '. $value['title'] , 'hotels'),
				"edit_item"		     =>	__('Edit '. $value['title'] , 'hotels'),
				"new_item"		     =>	__('New '. $value['title'] , 'hotels'),
				"view_item"		     =>	__('Show '. $value['title'] , 'hotels'),
				"search_items"	     =>	__('Search '. $value['title'] , 'hotels'),
				"not_found"		     =>	__('No '. $value['title'].' Found', 'hotels'),
				"not_found_in_trash" =>	__('No '. $value['title']. 'Found in the Trash', 'hotels'),
				"parent_item_colon"  =>	__('Parent '. $value['title'] , 'hotels'),
				"edit"			     =>	__('Edit ', 'hotels'),
				"view"			     =>	__('Show '. $value['title'], 'hotels')
			),
			'menu_icon'	          => $value['menu_icon'],
			'query_var'           => true,
			'public'              => true,
			'capability_type'     => 'post',
			'show_ui'             => true,
			'exclude_from_search' => false,
			'hierarchical'        => $value['hierarchical'],
			'has_archive'         => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'supports'            => array( 'title', 'thumbnail','editor'),
			'rewrite'             => array( 'slug' => $key, 'with_front' => true),
		);
		register_post_type($key, $args);
	}


	register_post_type( 'post-slider',
		array(
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-images-alt',
			'capability_type' => 'page',
			'rewrite'         => array( 'post_tag' ),
			'label'           => __('Gallery Post','hotels'),
			'supports'        => array( 'title', 'page-attributes'),
			'register_meta_box_cb' => 'add_gallery_metaboxes'
		)
	);

}
add_action('init', 'hotels_post_type');

//<!------------------------------------------------ TAXONOMY ---------------------------------------------->
/**
 * Adds a taxonomy(world for categories)
 */
function create_custom_tax() {
	$custTaxs= array(
		'regulation-category' => array(
			'title'     => 'Regulation Category',
			'post-type' => array('regulation')
		),
	);

	foreach ($custTaxs as $key => $value) {
		$labels = array(
			'name'              => __( $value['title'], 'hotels' ),
			'singular_name'     => __( $value['title'], 'hotels' ),
			'search_items'      => __( 'Search ' . $value['title'], 'hotels' ),
			'all_items'         => __( 'All ' . $value['title'], 'hotels' ),
			'parent_item'       => __( 'Parent '.$value['title'], 'hotels' ),
			'parent_item_colon' => __( 'Parent : '.$value['title'], 'hotels' ),
			'edit_item'         => __( 'Edit ' . $value['title'], 'hotels' ),
			'update_item'       => __( 'Update ' . $value['title'], 'hotels' ),
			'add_new_item'      => __( 'Add ' . $value['title'], 'hotels' ),
			'new_item_name'     => __( 'New ' . $value['title'], 'hotels' ),
			'menu_name'         => __(  $value['title'], 'hotels' ),
		);
		// $capabilities = array(
		// 	'manage_terms'       => $key,
		// 	// 'edit_terms'         => $key,
		// 	// 'delete_post'        => $key,
		// 	// 'delete_terms'       => $key,
		// 	'assign_terms'       => 'edit_posts',
		// 	// 'delete_posts'       => 'edit_pages',
		// 	// 'publish_posts'      => 'edit_pages',
		// 	// 'read_private_posts' => 'edit_pages'
		// );
		$args =	array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'	        => true,
			//'capabilities'      => $capabilities,
		);
		register_taxonomy($key, $value['post-type'],$args);
	}
}
add_action( 'init', 'create_custom_tax' );

/**
 * Hide editor on specific pages.
 *
 */
add_action( 'admin_init', 'hide_editor' );
function hide_editor() {
	// Get the Post ID.
	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	if( !isset( $post_id ) ) return;

	// Hide the editor on a page with a specific page template
	// Get the name of the Page Template file.
	$template_file = get_post_meta($post_id, '_wp_page_template', true);
	//var_dump($template_file);
	if( $template_file == 'front-page.php' ){ // the filename of the page template
		remove_post_type_support('page','editor');
	}
}

/**
 * Create the option page.
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(
		array(
			'page_title' => 'Default settings',
			'menu_title' => 'Default settings',
			'menu_slug'  => 'default-settings',
			'capability' => 'edit_posts',
			'redirect'	 =>  false
		)
	);
}


/**
 * Add menu support and register main menu.
 */
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
			'main_menu'	        => 'Main Menu',
			'main_menu_1'		=> 'Main Menu 1',
			'main_menu_2'		=> 'Main Menu 2',
			'main_menu_3'		=> 'Main Menu 3',
			'main_menu_4'		=> 'Main Menu 4',
			'main_menu_mobile'	=> 'Main Menu Mobile',
			'account_side_menu' => 'Account Side Menu',
			'about_side_menu'   => 'About Side Menu',
		)
	);
}

/**
 * Add thumbnail, automatic feed links and title tag support.
 */
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );

// add my_theme_setup
add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup() {
	load_theme_textdomain('wilson', get_template_directory() . '/languages');
}

/* Thumbnails */
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails', array( 'page', 'post', 'post-type' ) );
	add_image_size( 'crop', 400, 400,  array( 'right', 'top' )  );
	add_image_size( 'crop-racket-team', 640, 480,  array( 'right', 'top' )  );
}

//Remove admin bar
//add_filter('show_admin_bar', '__return_false');

/* Allow vcf Files */
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
	$existing_mimes['vcf'] = 'text/x-vcard';
	return $existing_mimes;
}

/* Allow text/html */
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
function set_html_content_type() {
	return 'text/html';
}

//Duplicate Posts
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	$post = get_post( $post_id );
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
	if (isset( $post ) && $post != null) {
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
		$new_post_id = wp_insert_post( $args );
		$taxonomies = get_object_taxonomies($post->post_type);
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">שכפול</a>';
	}
	return $actions;
}
add_filter('post_row_actions', 'rd_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);
