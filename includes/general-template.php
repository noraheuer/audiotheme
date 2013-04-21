<?php
/**
 * General template tags and functions.
 *
 * @package AudioTheme_Framework
 * @subpackage Template
 */

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. Falls back to
 * the built-in template.
 *
 * @since 1.1.0
 * @see locate_template()
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template path if one is located.
 */
function audiotheme_locate_template( $template_names, $load = false, $require_once = true ) {
	$template = '';

	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}

		if ( file_exists( get_stylesheet_directory() . '/audiotheme/' . $template_name ) ) {
			$template = get_stylesheet_directory() . '/audiotheme/' . $template_name;
			break;
		} elseif ( file_exists( get_template_directory() . '/audiotheme/' . $template_name ) ) {
			$template = get_template_directory() . '/audiotheme/' . $template_name;
			break;
		} elseif ( file_exists( AUDIOTHEME_DIR . 'templates/' . $template_name ) ) {
			$template = AUDIOTHEME_DIR . 'templates/' . $template_name;
			break;
		}
	}

	if ( $load && ! empty( $template ) ) {
		load_template( $template, $require_once );
	}

	return $template;
}

/**
 * Display a post type archive title.
 *
 * Just a wrapper to the default post_type_archive_title for the sake of
 * consistency. This should only be used in AudioTheme-specific template files.
 *
 * @since 1.0.0
 */
function the_audiotheme_archive_title() {
	post_type_archive_title();
}

/**
 * Display a post type archive description.
 *
 * @since 1.0.0
 *
 * @param string $before Content to display before the description.
 * @param string $after Content to display after the description.
 */
function the_audiotheme_archive_description( $before = '', $after = '' ) {
	if ( ! is_post_type_archive() ) {
		return;
	}

	$post_type_object = get_queried_object();

	if ( $archive_id = get_audiotheme_post_type_archive( $post_type_object->name ) ) {
		$archive = get_post( $archive_id );
		if ( ! empty( $archive->post_content ) ) {
			echo $before . apply_filters( 'the_content', $archive->post_content ) . $after;
		}
	}
}
