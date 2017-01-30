<?php


// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'opencage_flush_rewrite_rules' );

// Flush your rewrite rules
function opencage_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function custom_post_lp() { 
	// creating (registering) the custom type 
	register_post_type( 'post_lp', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'ランディングページ', 'opencagetheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'ランディングページ', 'opencagetheme' ), /* This is the individual type */
			'all_items' => __( 'すべてのランディングページ', 'opencagetheme' ), /* the all items menu item */
			'add_new' => __( '新規作成', 'opencagetheme' ), /* The add new menu item */
			'add_new_item' => __( 'ランディングページをつくる', 'opencagetheme' ), /* Add New Display Title */
			'edit' => __( '編集', 'opencagetheme' ), /* Edit Dialog */
			'edit_item' => __( 'ランディングページを編集', 'opencagetheme' ), /* Edit Display Title */
			'new_item' => __( 'New Post Type', 'opencagetheme' ), /* New Display Title */
			'view_item' => __( 'ランディングページを表示', 'opencagetheme' ), /* View Display Title */
			'search_items' => __( 'ランディングページを検索', 'opencagetheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'opencagetheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'opencagetheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'ランディングページをつくれます。', 'opencagetheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => get_bloginfo('template_directory') . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'post_lp', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'post_lp', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => true,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'page-attributes' , 'revisions', 'sticky')
		) /* end of options */
	); /* end of register post type */
	
	
}

	// adding the function to the Wordpress init
	add_action( 'init', 'custom_post_lp');
	
	

?>
