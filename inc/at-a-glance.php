<?php
/**
 * Adds post-type info to 'At a Glance'-dashboard widget.
 *
 * @package kebbet-cpt-presskit
 */

namespace cpt\kebbet\presskit\at_a_glance;

use const cpt\kebbet\presskit\POSTTYPE;

/**
 * Adds post-type info to 'At a Glance'-dashboard widget.
 *
 * @param array $items The items to display in the `At a Glance-dashboard`.
 * @return array $items All existing plus the new items.
 */
function at_a_glance_items( $items = array() ) {

	$post_types = array( POSTTYPE );

	foreach ( $post_types as $type ) {

		if ( ! post_type_exists( $type ) ) {
			continue;
		}
		$num_posts = wp_count_posts( $type );

		if ( $num_posts ) {

			$published = intval( $num_posts->publish );
			$post_type = get_post_type_object( $type );
			/* translators: %s: counter of how many posts. */
			$text     = _n( '%s presskit post', '%s presskit posts', $published, 'kebbet-cpt-presskit' );
			$text     = sprintf( $text, number_format_i18n( $published ) );
			$editlink = 'edit.php?post_type=' . $type;

			if ( current_user_can( $post_type->cap->edit_posts ) ) {
				$items[] = sprintf( '<a href="%3$s">%2$s</a>', $type, $text, $editlink ) . "\n";
			} else {
				$items[] = sprintf( '<span class="%1$s-count">%2$s</span>', $type, $text ) . "\n";
			}
		}
	}
	return $items;
}
add_filter( 'dashboard_glance_items', __NAMESPACE__ . '\at_a_glance_items', 10, 1 );
