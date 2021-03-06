<?php
/*
Plugin Name: GeoDirectory - X Theme Compatibility
Plugin URI: http://wpgeodirectory.com
Description: This plugin lets the GeoDirectory Plugin use the X theme HTML wrappers to fit and work perfectly.
Version: 1.0.3
Author: GeoDirectory
Author URI: http://wpgeodirectory.com

*/

// BECAUSE THIS PLUGIN IS CALLED BEFORE GD WE MUST CALL THIS PLUGIN ONCE GD LOADS
add_action( 'plugins_loaded', 'geodir_x_action_calls', 10 );
function geodir_x_action_calls(){
	
	/* ACTIONS
	****************************************************************************************/
	// LOAD STYLESHEET
	add_action( 'wp_enqueue_scripts', 'geodir_x_styles' );
	
	// Add body class for styling purposes
	add_filter('body_class','geodir_x_body_class');
	
	// HOME TOP SIDEBAR
	remove_action( 'geodir_location_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10 );
	remove_action( 'geodir_home_before_main_content', 'geodir_action_geodir_sidebar_home_top', 10 );
	add_action( 'geodir_wrapper_open', 'geodir_x_home_sidebar', 5 );
	add_action( 'geodir_before_search_form', 'geodir_x_search_container_open' );
	add_action( 'geodir_after_search_form', 'geodir_x_search_container_close' );
	
	// WRAPPER OPEN ACTIONS
	remove_action( 'geodir_wrapper_open', 'geodir_action_wrapper_open', 10 );
	add_action( 'geodir_wrapper_open', 'geodir_x_action_wrapper_open', 9 );
	
	// WRAPPER CLOSE ACTIONS
	remove_action( 'geodir_wrapper_close', 'geodir_action_wrapper_close', 10);
	add_action( 'geodir_wrapper_close', 'geodir_x_action_wrapper_close', 11);	
	
	// WRAPPER CONTENT OPEN ACTIONS
	remove_action( 'geodir_wrapper_content_open', 'geodir_action_wrapper_content_open', 10 );
	add_action( 'geodir_wrapper_content_open', 'geodir_x_action_wrapper_content_open', 9, 3 );
	
	// WRAPPER CONTENT CLOSE ACTIONS
	remove_action( 'geodir_wrapper_content_close', 'geodir_action_wrapper_content_close', 10);
	add_action( 'geodir_wrapper_content_close', 'geodir_x_action_wrapper_content_close', 11);
	
	// SIDEBAR RIGHT OPEN ACTIONS
	remove_action( 'geodir_sidebar_right_open', 'geodir_action_sidebar_right_open', 10 );
	add_action( 'geodir_sidebar_right_open', 'geodir_x_action_sidebar_right_open', 10, 4 );
	
	// SIDEBAR RIGHT CLOSE ACTIONS
	remove_action( 'geodir_sidebar_right_close', 'geodir_action_sidebar_right_close', 10);
	add_action( 'geodir_sidebar_right_close', 'geodir_x_action_sidebar_right_close', 10,1);
	
	// REMOVE BREADCRUMBS
	remove_action( 'geodir_listings_before_main_content', 'geodir_breadcrumb', 20 );
	remove_action( 'geodir_detail_before_main_content', 'geodir_breadcrumb', 20 );
	remove_action( 'geodir_search_before_main_content', 'geodir_breadcrumb', 20 );
	remove_action( 'geodir_author_before_main_content', 'geodir_breadcrumb', 20 );
	remove_action( 'geodir_home_before_main_content', 'geodir_breadcrumb', 20 );
	remove_action( 'geodir_location_before_main_content', 'geodir_breadcrumb', 20 );
	
	
} // Close geodir_x_action_calls

/* FUNCTIONS
****************************************************************************************/

// ENQUEUE STYLESHEET & ADD BODY CLASS
function geodir_x_styles() {
    // Register the plugin stylesheet
    wp_register_style( 'geodir-x-style', plugins_url( '/css/plugin.css', __FILE__ ), array(), 'all' );
    wp_enqueue_style( 'geodir-x-style' );
}

function geodir_x_body_class($classes) {
	$classes[] = 'geodir-x';
	return $classes;
}

// REPLACE GD HOME TOP SIDEBAR AFTER HEADER
function geodir_x_home_sidebar() {
	//if ( geodir_is_geodir_page() ) {
		global $wp;
		if ( $wp->query_vars['page_id'] == get_option( 'geodir_location_page' ) || is_home() && !$_GET['geodir_signup'] ) {
			echo '<div class="x-main full">';
        	dynamic_sidebar('geodir_home_top');
			echo '</div>';
		}
	//}
}

// ADD OPENING WRAP TO SEARCHBAR
function geodir_x_search_container_open() {
	echo '<div class="x-container-fluid max">';
}

// ADD CLOSING WRAP TO SEARCHBAR
function geodir_x_search_container_close() {
	echo '</div>';
}

// WRAPPER OPEN FUNCTIONS
function geodir_x_action_wrapper_open(){
	global $stack;
	if ( $stack == 'integrity' ) { echo '<div class="x-container-fluid max width offset cf">'; }
	elseif ( $stack == 'renew' ) { echo '<div class="x-container-fluid max width offset cf">'; }
	elseif ( $stack == 'icon' ) { echo '<div class="x-main full" role="main">'; }
	elseif ( $stack == 'ethos' ) { echo '<div class="x-container-fluid max width main"><div class="offset cf">'; }
}

// WRAPPER CLOSE FUNCTIONS
function geodir_x_action_wrapper_close(){
	global $stack;
	if ( $stack == 'ethos' ) { echo '</div></div>'; } else { echo '</div>'; }
}

// WRAPPER CONTENT OPEN FUNCTIONS
function geodir_x_action_wrapper_content_open($type='',$id='',$class=''){
	echo '<div class="x-main left ' . $class . '" role="main">';
}

// WRAPPER CONTENT CLOSE FUNCTIONS
function geodir_x_action_wrapper_content_close(){
	echo '</div>';
}

// SIDEBAR RIGHT OPEN FUNCTIONS
function geodir_x_action_sidebar_right_open($type='',$id='',$class='',$itemtype=''){
	echo '<aside class="x-sidebar right" role="complementary" itemscope itemtype="'.$itemtype.'">';
}

// SIDEBAR RIGHT CLOSE FUNCTIONS
function geodir_x_action_sidebar_right_close($type=''){
	echo '</aside>';
}

// MODIFY BREADCRUMB
add_filter( 'geodir_breadcrumb', 'geodir_x_breadcrumb' );
function geodir_x_breadcrumb( $breadcrumb ) {
	$breadcrumb = str_replace( '<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs">', '', $breadcrumb );
	$breadcrumb = str_replace( '<li>', '', $breadcrumb );
	$breadcrumb = str_replace( '</li>', '', $breadcrumb );
	$breadcrumb = str_replace( 'Home', '<span class="home"><i class="x-icon-home"></i></span>', $breadcrumb );
	$breadcrumb = str_replace( '</ul></div>', '', $breadcrumb );
	return $breadcrumb;
}

// MODIFY BREADCRUMB SEPARATOR
add_filter( 'geodir_breadcrumb_separator', 'geodir_x_breadcrumb_separator' );
function geodir_x_breadcrumb_separator( $separator ) {
	$separator = str_replace( ' > ', ' <span class="delimiter"><i class="x-icon-angle-right"></i></span> ', $separator );
	return $separator;
}

// BREADCRUMBS
if ( ! function_exists( 'x_breadcrumbs' ) ) :
  function x_breadcrumbs() {

    if ( get_theme_mod( 'x_breadcrumb_display' ) ) {

      //
      // 1. Delimiter between crumbs.
      // 2. Output text for the "Home" link.
      // 3. Link to the home page.
      // 4. Tag before the current crumb.
      // 5. Tag after the current crumb.
      // 6. Get page title.
      // 7. Get blog title.
      // 8. Get shop title.
      //

      GLOBAL $post;
	  
	  if ( geodir_is_page('detail') || geodir_is_page('listing') || $wp->query_vars['page_id'] == get_option( 'geodir_location_page' ) ) {
		  geodir_breadcrumb();
	  } else {

      $stack          = x_get_stack();
      $delimiter      = ' <span class="delimiter"><i class="x-icon-angle-right"></i></span> '; // 1
      $home_text      = '<span class="home"><i class="x-icon-home"></i></span>';               // 2
      $home_link      = home_url();                                                            // 3
      $current_before = '<span class="current">';                                              // 4
      $current_after  = '</span>';                                                             // 5
      $page_title     = get_the_title();                                                       // 6
      $blog_title     = get_the_title( get_option( 'page_for_posts', true ) );                 // 7
      $shop_title     = get_theme_mod( 'x_' . $stack . '_shop_title' );                        // 8

      if ( function_exists( 'woocommerce_get_page_id' ) ) {
        $shop_url  = x_get_shop_link();
        $shop_link = '<a href="'. $shop_url .'">' . $shop_title . '</a>';
      }
     
      if ( is_front_page() ) {
        echo '<div class="x-breadcrumbs">' . $current_before . $home_text . $current_after . '</div>';
      } elseif ( is_home() ) {
        echo '<div class="x-breadcrumbs"><a href="' . $home_link . '">' . $home_text . '</a>' . $delimiter . $current_before . $blog_title . $current_after . '</div>';
      } else {
        echo '<div class="x-breadcrumbs"><a href="' . $home_link . '">' . $home_text . '</a>' . $delimiter;
        if ( is_category() ) {
          $the_cat = get_category( get_query_var( 'cat' ), false );
          if ( $the_cat->parent != 0 ) echo get_category_parents( $the_cat->parent, TRUE, $delimiter );
          echo $current_before . single_cat_title( '', false ) . $current_after;
        } elseif ( x_is_product_category() ) {
          echo $shop_link . $delimiter . $current_before . single_cat_title( '', false ) . $current_after;
        } elseif ( x_is_product_tag() ) {
          echo $shop_link . $delimiter . $current_before . single_tag_title( '', false ) . $current_after;
        } elseif ( is_search() ) {
          echo $current_before . __( 'Search Results for ', '__x__' ) . '&#8220;' . get_search_query() . '&#8221;' . $current_after;
        } elseif ( is_singular( 'post' ) ) {
          if ( get_option( 'page_for_posts' ) == is_front_page() ) {
            echo $current_before . $page_title . $current_after;
          } else {
            echo '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '" title="' . esc_attr( __( 'See All Posts', '__x__' ) ) . '">' . $blog_title . '</a>' . $delimiter . $current_before . $page_title . $current_after;
          }
        } elseif ( x_is_portfolio() ) {
          echo $current_before . get_the_title() . $current_after;
        } elseif ( x_is_portfolio_item() ) {
          $link  = x_get_parent_portfolio_link();
          $title = x_get_parent_portfolio_title();
          echo '<a href="' . $link . '" title="' . esc_attr( __( 'See All Posts', '__x__' ) ) . '">' . $title . '</a>' . $delimiter . $current_before . $page_title . $current_after;
        } elseif ( x_is_product() ) {
          echo $shop_link . $delimiter . $current_before . $page_title . $current_after;
        } elseif ( is_page() && ! $post->post_parent ) {
          echo $current_before . $page_title . $current_after;
        } elseif ( is_page() && $post->post_parent ) {
          $parent_id   = $post->post_parent;
          $breadcrumbs = array();
          while ( $parent_id ) {
            $page          = get_page( $parent_id );
            $breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
            $parent_id     = $page->post_parent;
          }
          $breadcrumbs = array_reverse( $breadcrumbs );
          for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
            echo $breadcrumbs[$i];
            if ( $i != count( $breadcrumbs ) -1 ) echo $delimiter;
          }
          echo $delimiter . $current_before . $page_title . $current_after;
        } elseif ( is_tag() ) {
          echo $current_before . single_tag_title( '', false ) . $current_after;
        } elseif ( is_author() ) {
          GLOBAL $author;
          $userdata = get_userdata( $author );
          echo $current_before . __( 'Posts by ', '__x__' ) . '&#8220;' . $userdata->display_name . $current_after . '&#8221;';
        } elseif ( is_404() ) {
          echo $current_before . __( '404 (Page Not Found)', '__x__' ) . $current_after;
        } elseif ( is_archive() ) {
          if ( x_is_shop() ) {
            echo $current_before . $shop_title . $current_after;
          } else {
            echo $current_before . __( 'Archives ', '__x__' ) . $current_after;
          }
        }
        if ( get_query_var( 'paged' ) ) {
          echo ' <span class="current" style="white-space: nowrap;">(' . __( 'Page', '__x__' ) . ' ' . get_query_var( 'paged' ) . ')</span>';
        }
        echo '</div>';
      }

    }

  }
  } // ends my geodir check
endif;


// ADD CLASS TO GD MENU ITEMS
add_filter('geodir_location_switcher_menu_li_class','geodir_x_location_switcher_menu_li_class',10,1);
function geodir_x_location_switcher_menu_li_class($class){
	$class .= " menu-item-has-children ";
	return $class;
}

add_filter('geodir_sub_menu_li_class','geodir_x_sub_menu_li_class',10,1);
function geodir_x_sub_menu_li_class($class){
	$class .= " menu-item-has-children ";
	return $class;
}