<?php
/*
Plugin Name: Multi-Column Taxonomy List
Description: List your categories, tags, or custom taxonomies into multiple, customizable, columns.
Author: Matthew Muro
Author URI: http://matthewmuro.com
Version: 1.5
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Template tag function
 *
 * @since 1.0
 * @echo class function Unordered lists to taxonomies
 */
function multi_column_tax_list( $args = '' ){
	// Create new class instance
	$template_tag = new MCTL();

	// Parse the arguments into an array
	$args = wp_parse_args( $args );

	// Print the output
	echo $template_tag->shortcode( $args );
}

// Instantiate new class
$mctl = new MCTL();

// Multi-Column Taxonomy List class
class MCTL{

	public function __construct(){
		// Create the shortcode
		add_shortcode( 'mctl', array( &$this, 'shortcode' ) );

		// Make sure our CSS gets added via wp_head
		add_action( 'wp_head', array( &$this, 'css' ) );
	}

	/**
	 * Add the CSS
	 *
	 * @since 1.0
	 */
	public function css(){
		echo apply_filters( 'mctl_css', '<link rel="stylesheet" href="' . plugins_url( 'css/multi-column-taxonomy-link.css', __FILE__ ) . '" type="text/css" />' );
	}

	/**
	 * Get all taxonomies that have been registered and are set to public
	 *
	 * @since 1.0
	 * @uses get_taxonomies() Returns an array of taxonomy objects matching the query parameters.
	 * @return $tax_terms array All taxonomies and associated metadata.
	 */
	public function get_tax( $tax_name = 'category', $terms_args = array() ){
		// Only use public taxonomies, the default being 'category'
		$tax_args = array(
			'public' => true,
			'name'   => $tax_name
		);

		// Get the taxonomy data as objects
		$taxonomies = get_taxonomies( $tax_args, 'objects' );

		// Setup our $tax_terms array
		$tax_terms = array();

		// If any result for the taxonomy, loop through and load our $tax_terms array with the terms
		if ( $taxonomies ) :
			foreach ( $taxonomies as $taxonomy ) {
				$tax_terms[] = get_terms ( $taxonomy->name, $terms_args );
			}
		endif;

		return $tax_terms;
	}

	/**
	 * Get all categories that will be used as options.
	 *
	 * @since 1.0
	 * @uses get_categories() Returns an array of category objects matching the query parameters.
	 * @return $cat array All category slugs.
	 */
	public function shortcode( $atts ){

		$output = $feed = $feed_img = $first_letter = $last_letter = '';

		// Extract shortcode attributes, set defaults
		extract( shortcode_atts( array(
			'taxonomy'           => 'category',
			'title'              => 'Categories',
			'title_container'    => 'h3',
			'columns'            => '3',
			'orderby'            => 'name',
			'order'              => 'ASC',
			'alpha_grouping'     => '0',
			'show_count'         => '0',
			'exclude'            => '',
			'parent'             => '',
			'child_of'			 => '0',
			'rss'                => '0',
			'rss_image'          => '',
			'number'             => '',
			'like'               => '',
			'search'			 => '',
			), $atts )
		);

		// Build an array of arguments for the get_terms parameters
		$args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'show_count' => $show_count,
			'exclude'    => $exclude,
			'parent'     => $parent,
			'child_of'	 => $child_of,
			'number'     => $number,
			'name__like' => $like,
			'search'	 => $search,
		);

		// Get the terms, based on taxonomy name
		$taxonomies = $this->get_tax( $taxonomy, $args );

		$output .= '<div class="multi-column-taxonomy-list">';

		foreach ( $taxonomies as $tax ) :
			// If the user has set a title, add it to the output
			$output .= ( $title ) ? "<$title_container>$title</$title_container>" : '';

			// Count the terms
			$count = count( $tax );

			// Round up to determine how many terms per column
			$per_column = ceil( $count / $columns );

			// Will print out our first <ul>
			$open_ul = true;

			// Set the column index for the CSS class
			$col_index = 1;

			// Set the tax index to find the last item
			$tax_index = 1;

			// Loop through the $tax objects and print out our columns
			foreach ( $tax as $val ) :

				// If true, print out the opening <ul> tag and reset our counter
				if ( $open_ul == true ) :
					$output .= sprintf( '<ul class="multi-column-%d">', $col_index );

					// Set this to prevent the open <ul> from printing until ready for it
					$open_ul = false;

					// Resets our counter
					$i = 1;

					// Increase the column index for the CSS class
					$col_index++;
				endif;

				// Add alphabetical grouping
				if ( $alpha_grouping ) :
					// Get first letter
					$first_letter = substr( $val->name, 0, 1 );

					// If current letter does not match last one, it's new
					if ( $first_letter !== $last_letter ) :

						$output .= sprintf( '<li class="multi-column-alpha-group">%s</li>', $first_letter );

						// Save current letter for next comparison
						$last_letter = $first_letter;
					endif;
				endif;

				// Get the term link
				$link = get_term_link( $val->slug, $taxonomy );

				// Always check if it's an error before continuing. get_term_link() can be finicky sometimes
				if( is_wp_error( $link ) )
					continue;

				// If $rss is true, make the link point to the feed and add the RSS image
				if ( $rss == 1 ) :
					$feed = 'feed';

					$feed_img_src = ( $rss_image ) ? $rss_image : includes_url() . 'images/rss.png';
					$feed_img = sprintf( '<span class="rss"><img alt="RSS" src="%s" style="border:0"></span>', $feed_img_src );
				endif;

				// If $show_count is true, display the count
				$display_count = ( $show_count == 1 ) ? sprintf( ' <span class="multi-column-count">(%d)</span>', $val->count ) : '';

				// The taxonomy output
				$output .= sprintf( '<li><a href="%1$s%2$s" rel="tag">%3$s%4$s%5$s</a></li>', $link, $feed, $val->name, $display_count, $feed_img );

				// If our counter is at our limit and not the last item, output the closing </ul>
				if ( $i == $per_column && $tax_index !== $count ) :
					$output .= '</ul>';

					// Set this to true so the next opening <ul> can print
					$open_ul = true;
				endif;

				// Increase the counters for each term
				$i++;
				$tax_index++;
			endforeach;

			$output .= '</ul></div>';
		endforeach;

		return $output;
	}
}
?>