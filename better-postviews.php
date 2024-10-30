<?php
/*
Plugin Name: Better Postviews
Plugin URI: http://rcollier.me/software/
Description: An efficient plugin which tracks all post views for high-volume WordPress sites.
Author: Rich Collier
Version: 1.5
Author URI: http://rcollier.me/
*/

// Deny direct execution
if ( ! defined( 'ABSPATH' ) ) die( 'Cheating, huh?' );

class better_postviews {
	
	var $bpv_defaults = array( 
		'enable_tracking' => true, 
		'enable_tracking_logged_in' => true, 
		'enable_post_column_view' => true, 
	);
	
	private $track_logged_in = true;
	
	// One hour transient lifetime
	const TRANSIENT_LIFETIME = 3600;
	
	// Our constructor which runs functions at the proper times
	function __construct() {
		$options = apply_filters( 'better_postviews_options', $this->bpv_defaults );
		
		$this->track_logged_in = $options['enable_tracking_logged_in'];
		
		if ( true === $options['enable_tracking'] ) {
			add_action( 'wp', array( $this, 'save_postview_data' ) );
		}
			
		if ( true === $options['enable_post_column_view'] ) {
			add_filter( 'manage_edit-post_columns', array( $this, 'add_postviews_col' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'add_postviews_data' ) );
		}
	}
	
	// Updates postmeta accordingly to reflect a single new view
	function save_postview_data() {
		global $post;
		
		if ( ! is_single() )
			return false;
		
		if ( ! is_object( $post ) )
			return false;
		
		if ( is_user_logged_in() && ! $this->track_logged_in )
			return false;
		
		$count = get_post_meta( $post->ID, 'better_postviews', true );
		
		if ( ! isset( $count ) ) 
			$count = 0;
		
		update_post_meta( $post->ID, 'better_postviews', ++$count );
	}
	
	// Adds a column for views in the post grid
	function add_postviews_col( $cols ) {
		$cols['pageviews'] = 'Views';
		
		return $cols;
	}
	
	// Adds data to the post grid column
	function add_postviews_data( $colname ) {
		global $post;
		
		if ( 'pageviews' == $colname ) {
			echo '<strong>';
			
			if ( $count = get_post_meta( $post->ID, 'better_postviews', true ) )
				echo $count;
			else
				echo '0';
				
			echo '</strong>';
		}
	}
	
	// Get the number of post views that a post has
	public static function get_view_count( $post_id = null ) {
		global $post;
		
		if ( ! $post_id )
			$post_id = $post->ID;
		
		if ( $count = get_post_meta( absint( $post_id ), 'better_postviews', true ) )
			return absint( $count );
		else
			return 0;
	}
	
	public static function get_top_one() {
		return better_postviews::get_top_posts( 1 );
	}
	
	public static function get_top_three() {
		return better_postviews::get_top_posts( 3 );
	}
	
	public static function get_top_five() {
		return better_postviews::get_top_posts( 5 );
	}
	
	// Get the top posts and return as array of integer post_ids
	public static function get_top_posts( $post_count = 5 ) {
		$post_count = absint( $post_count );
		
		$args = array( 
			'orderby' => 'meta_value_num', 
			'meta_key' => 'better_postviews', 
			'order' => 'desc', 
			'posts_per_page' => $post_count, 
		);
		
		$args = apply_filters( 'better_postviews_top_posts_query_args', $args );
		
		$transient_key = 'bpv_' . md5( serialize( $args ) );
		
		if ( $cache_response = get_transient( $transient_key ) )
			return $cache_response;
		
		$group_of = new WP_Query( $args );
		
		$top_five = array();
		
		foreach ( $group_of->posts as $single_post )
			array_push( $top_five, $single_post->ID );
		
		$transient_lifetime = apply_filters( 'better_postviews_transient_lifetime', absint( self::TRANSIENT_LIFETIME ) );
		
		set_transient( $transient_key, $top_five, $transient_lifetime );
		
		return $top_five;
	}

}

// Let the fun and games begin ...
$better_postviews = new better_postviews();

// omit