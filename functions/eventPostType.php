<?php
// Custom Post Type = Event
	// register post type
	function eventPost() {
		$labels = array(
			'name'               => _x( 'Events', 'post type general name' ),
			'singular_name'      => _x( 'Event', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'Event' ),
			'add_new_item'       => __( 'Add New Event' ),
			'edit_item'          => __( 'Edit Event' ),
			'new_item'           => __( 'New Events' ),
			'all_items'          => __( 'All Events' ),
			'view_item'          => __( 'View Event' ),
			'search_items'       => __( 'Search Events' ),
			'not_found'          => __( 'No Events found' ),
			'not_found_in_trash' => __( 'No Events found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Events'
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds our Events and Event-item-specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
			'has_archive'   => true,
		);
	register_post_type( 'events', $args );
	}
	add_action( 'init', 'eventPost' );
	
	// Event tags:
	function eventTaxonomies() {
		$labels = array(
			'name'              => _x( 'Event Tags', 'taxonomy general name' ),
			'singular_name'     => _x( 'Event Tag', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Event Tags' ),
			'all_items'         => __( 'All Event Tags' ),
			'parent_item'       => __( 'Parent Event Tag' ),
			'parent_item_colon' => __( 'Parent Event Tag:' ),
			'edit_item'         => __( 'Edit Event Tag' ), 
			'update_item'       => __( 'Update Event Tag' ),
			'add_new_item'      => __( 'Add New Event Tag' ),
			'new_item_name'     => __( 'New Event Tag' ),
			'menu_name'         => __( 'Event Tags' ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
		);
		register_taxonomy( 'eventtax_tag', 'event', $args );
	}
	add_action( 'init', 'eventTaxonomies', 0 );

	// custom interaction messages
	function my_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['event'] = array(
			0 => '', 
			1 => sprintf( __('Event updated. <a href="%s">View Event</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Event updated.'),
			5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Event published. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Event saved.'),
			8 => sprintf( __('Event submitted. <a target="_blank" href="%s">Preview Event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Event</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Event draft updated. <a target="_blank" href="%s">Preview Event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
		return $messages;
	}
	add_filter( 'post_updated_messages', 'my_updated_messages' );

	// contextual help
	function my_contextual_help( $contextual_help, $screen_id, $screen ) { 
		if ( 'event' == $screen->id ) {
			$contextual_help = '<h2>Events</h2>
			<p>These are the events that appear in the \'events\' section of the site. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p> 
			<p>You can view/edit the details of each Event by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
		} elseif ( 'edit-event' == $screen->id ) {
			$contextual_help = '<h2>Editing Events</h2>
			<p>This page allows you to view/modify Events\' details.</p>';
		}
		return $contextual_help;
	}
	add_action( 'contextual_help', 'my_contextual_help', 10, 3 );
