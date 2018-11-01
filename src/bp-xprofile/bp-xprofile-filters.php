<?php
/**
 * BuddyPress XProfile Filters.
 *
 * Apply WordPress defined filters.
 *
 * @package BuddyBoss
 * @subpackage XProfileFilters
 * @since BuddyPress 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'bp_get_the_profile_group_name',            'wp_filter_kses',       1 );
add_filter( 'bp_get_the_profile_group_description',     'wp_filter_kses',       1 );
add_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_kses', 1 );
add_filter( 'bp_get_the_profile_field_name',            'wp_filter_kses',       1 );
add_filter( 'bp_get_the_profile_field_edit_value',      'wp_filter_kses',       1 );
add_filter( 'bp_get_the_profile_field_description',     'wp_filter_kses',       1 );

add_filter( 'bp_get_the_profile_field_value',           'wptexturize'        );
add_filter( 'bp_get_the_profile_field_value',           'convert_chars'      );
add_filter( 'bp_get_the_profile_field_value',           'wpautop'            );
add_filter( 'bp_get_the_profile_field_value',           'force_balance_tags' );
add_filter( 'bp_get_the_profile_field_value',           'make_clickable'     );
add_filter( 'bp_get_the_profile_field_value',           'bp_xprofile_escape_field_data', 8, 3 );
add_filter( 'bp_get_the_profile_field_value',           'convert_smilies', 9 );
add_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value',         1, 2 );
add_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_format_field_value_by_type', 8, 3 );
add_filter( 'bp_get_the_profile_field_value',           'xprofile_filter_link_profile_data',          9, 3 );

add_filter( 'bp_get_the_profile_field_edit_value',      'force_balance_tags' );
add_filter( 'bp_get_the_profile_field_edit_value',      'bp_xprofile_escape_field_data', 10, 3 );

add_filter( 'bp_get_the_profile_group_name',            'stripslashes' );
add_filter( 'bp_get_the_profile_group_description',     'stripslashes' );
add_filter( 'bp_get_the_profile_field_value',           'stripslashes' );
add_filter( 'bp_get_the_profile_field_edit_value',      'stripslashes' );
add_filter( 'bp_get_the_profile_field_name',            'stripslashes' );
add_filter( 'bp_get_the_profile_field_description',     'stripslashes' );

add_filter( 'xprofile_get_field_data',                  'xprofile_filter_kses', 1 );
add_filter( 'xprofile_field_name_before_save',          'wp_filter_kses', 1 );
add_filter( 'xprofile_field_description_before_save',   'wp_filter_kses', 1 );

add_filter( 'xprofile_get_field_data',                  'force_balance_tags' );
add_filter( 'xprofile_field_name_before_save',          'force_balance_tags' );
add_filter( 'xprofile_field_description_before_save',   'force_balance_tags' );

add_filter( 'xprofile_get_field_data',                  'stripslashes' );
add_filter( 'xprofile_get_field_data',                  'xprofile_filter_format_field_value_by_field_id', 5, 2 );

add_filter( 'bp_xprofile_set_field_data_pre_validate',  'xprofile_filter_pre_validate_value_by_field_type', 10, 3 );
add_filter( 'xprofile_data_value_before_save',          'xprofile_sanitize_data_value_before_save', 1, 4 );
add_filter( 'xprofile_filtered_data_value_before_save', 'trim', 2 );

// Save field groups.
add_filter( 'xprofile_group_name_before_save',        'wp_filter_kses' );
add_filter( 'xprofile_group_description_before_save', 'wp_filter_kses' );

add_filter( 'xprofile_group_name_before_save',         'stripslashes' );
add_filter( 'xprofile_group_description_before_save',  'stripslashes' );

// Save fields.
add_filter( 'xprofile_field_name_before_save',         'wp_filter_kses' );
add_filter( 'xprofile_field_type_before_save',         'wp_filter_kses' );
add_filter( 'xprofile_field_description_before_save',  'wp_filter_kses' );
add_filter( 'xprofile_field_order_by_before_save',     'wp_filter_kses' );

add_filter( 'xprofile_field_is_required_before_save',  'absint' );
add_filter( 'xprofile_field_field_order_before_save',  'absint' );
add_filter( 'xprofile_field_option_order_before_save', 'absint' );
add_filter( 'xprofile_field_can_delete_before_save',   'absint' );

// Save field options.
add_filter( 'xprofile_field_options_before_save', 'bp_xprofile_sanitize_field_options' );
add_filter( 'xprofile_field_default_before_save', 'bp_xprofile_sanitize_field_default' );

add_filter( 'bp_get_the_profile_field_name', 'xprofile_filter_field_edit_name' );

// Saving field value
add_filter( 'xprofile_validate_field', 'bp_xprofile_validate_nickname_value', 10, 4 );

/**
 * Sanitize each field option name for saving to the database.
 *
 * @since BuddyPress 2.3.0
 *
 * @param mixed $field_options Options to sanitize.
 * @return mixed
 */
function bp_xprofile_sanitize_field_options( $field_options = '' ) {
	if ( is_array( $field_options ) ) {
		return array_map( 'sanitize_text_field', $field_options );
	} else {
		return sanitize_text_field( $field_options );
	}
}

/**
 * Sanitize each field option default for saving to the database.
 *
 * @since BuddyPress 2.3.0
 *
 * @param mixed $field_default Field defaults to sanitize.
 * @return array|int
 */
function bp_xprofile_sanitize_field_default( $field_default = '' ) {
	if ( is_array( $field_default ) ) {
		return array_map( 'intval', $field_default );
	} else {
		return intval( $field_default );
	}
}

/**
 * Run profile field values through kses with filterable allowed tags.
 *
 * @since BuddyPress 1.5.0
 *
 * @param string      $content  Content to filter.
 * @param object|null $data_obj The BP_XProfile_ProfileData object.
 * @return string $content
 */
function xprofile_filter_kses( $content, $data_obj = null ) {
	global $allowedtags;

	$xprofile_allowedtags             = $allowedtags;
	$xprofile_allowedtags['a']['rel'] = array();

	// If the field supports rich text, we must allow tags that appear in wp_editor().
	if ( $data_obj instanceof BP_XProfile_ProfileData && bp_xprofile_is_richtext_enabled_for_field( $data_obj->field_id ) ) {
		$richtext_tags = array(
			'img'  => array( 'id' => 1, 'class' => 1, 'src' => 1, 'alt' => 1, 'width' => 1, 'height' => 1 ),
			'ul'   => array( 'id' => 1, 'class' => 1 ),
			'ol'   => array( 'id' => 1, 'class' => 1 ),
			'li'   => array( 'id' => 1, 'class' => 1 ),
			'span' => array( 'style' => 1 ),
			'p'    => array( 'style' => 1 ),
		);

		$xprofile_allowedtags = array_merge( $allowedtags, $richtext_tags );
	}

	/**
	 * Filters the allowed tags for use within xprofile_filter_kses().
	 *
	 * @since BuddyPress 1.5.0
	 *
	 * @param array                   $xprofile_allowedtags Array of allowed tags for profile field values.
	 * @param BP_XProfile_ProfileData $data_obj             The BP_XProfile_ProfileData object.
	 */
	$xprofile_allowedtags = apply_filters( 'xprofile_allowed_tags', $xprofile_allowedtags, $data_obj );
	return wp_kses( $content, $xprofile_allowedtags );
}

/**
 * Safely runs profile field data through kses and force_balance_tags.
 *
 * @since BuddyPress 1.2.6
 *
 * @param string      $field_value Field value being santized.
 * @param int         $field_id    Field ID being sanitized.
 * @param bool        $reserialize Whether to reserialize arrays before returning. Defaults to true.
 * @param object|null $data_obj    The BP_XProfile_ProfileData object.
 * @return string
 */
function xprofile_sanitize_data_value_before_save( $field_value, $field_id = 0, $reserialize = true, $data_obj = null ) {

	// Return if empty.
	if ( empty( $field_value ) ) {
		return $field_value;
	}

	// Force reserialization if serialized (avoids mutation, retains integrity)
	if ( is_serialized( $field_value ) && ( false === $reserialize ) ) {
		$reserialize = true;
	}

	// Value might be a serialized array of options.
	$field_value = maybe_unserialize( $field_value );

	// Sanitize single field value.
	if ( ! is_array( $field_value ) ) {
		$kses_field_value     = xprofile_filter_kses( $field_value, $data_obj );
		$filtered_field_value = wp_rel_nofollow( force_balance_tags( $kses_field_value ) );

		/**
		 * Filters the kses-filtered data before saving to database.
		 *
		 * @since BuddyPress 1.5.0
		 *
		 * @param string                  $filtered_field_value The filtered value.
		 * @param string                  $field_value          The original value before filtering.
		 * @param BP_XProfile_ProfileData $data_obj             The BP_XProfile_ProfileData object.
		 */
		$filtered_field_value = apply_filters( 'xprofile_filtered_data_value_before_save', $filtered_field_value, $field_value, $data_obj );

	// Sanitize multiple individual option values.
	} else {
		$filtered_values = array();
		foreach ( (array) $field_value as $value ) {
			$kses_field_value = xprofile_filter_kses( $value, $data_obj );
			$filtered_value   = wp_rel_nofollow( force_balance_tags( $kses_field_value ) );

			/** This filter is documented in bp-xprofile/bp-xprofile-filters.php */
			$filtered_values[] = apply_filters( 'xprofile_filtered_data_value_before_save', $filtered_value, $value, $data_obj );
		}

		if ( !empty( $reserialize ) ) {
			$filtered_field_value = serialize( $filtered_values );
		} else {
			$filtered_field_value = $filtered_values;
		}
	}

	return $filtered_field_value;
}

/**
 * Runs stripslashes on XProfile fields.
 *
 * @since BuddyPress 1.0.0
 *
 * @param string $field_value XProfile field_value to be filtered.
 * @param string $field_type  XProfile field_type to be filtered.
 * @return false|string $field_value Filtered XProfile field_value. False on failure.
 */
function xprofile_filter_format_field_value( $field_value, $field_type = '' ) {

	// Valid field values of 0 or '0' get caught by empty(), so we have an extra check for these. See #BP5731.
	if ( ! isset( $field_value ) || empty( $field_value ) && ( '0' !== $field_value ) ) {
		return false;
	}

	if ( 'datebox' !== $field_type ) {
		$field_value = str_replace( ']]>', ']]&gt;', $field_value );
	}

	return stripslashes( $field_value );
}

/**
 * Apply display_filter() filters as defined by BP_XProfile_Field_Type classes, when inside a bp_has_profile() loop.
 *
 * @since BuddyPress 2.1.0
 * @since BuddyPress 2.4.0 Added `$field_id` parameter.
 *
 * @param mixed      $field_value Field value.
 * @param string     $field_type  Field type.
 * @param string|int $field_id    Optional. ID of the field.
 * @return mixed
 */
function xprofile_filter_format_field_value_by_type( $field_value, $field_type = '', $field_id = '' ) {
	foreach ( bp_xprofile_get_field_types() as $type => $class ) {
		if ( $type !== $field_type ) {
			continue;
		}

		if ( method_exists( $class, 'display_filter' ) ) {
			$field_value = call_user_func( array( $class, 'display_filter' ), $field_value, $field_id );
		}
	}

	return $field_value;
}

/**
 * Apply display_filter() filters as defined by the BP_XProfile_Field_Type classes, when fetched
 * by xprofile_get_field_data().
 *
 * @since BuddyPress 2.1.0
 *
 * @param mixed $field_value Field value.
 * @param int   $field_id    Field type.
 * @return string
 */
function xprofile_filter_format_field_value_by_field_id( $field_value, $field_id ) {
	$field = xprofile_get_field( $field_id );
	return xprofile_filter_format_field_value_by_type( $field_value, $field->type, $field_id );
}

/**
 * Apply pre_validate_filter() filters as defined by the BP_XProfile_Field_Type classes before validating.
 *
 * @since BuddyPress 2.1.0
 *
 * @param mixed                  $value          Value passed to the bp_xprofile_set_field_data_pre_validate filter.
 * @param BP_XProfile_Field      $field          Field object.
 * @param BP_XProfile_Field_Type $field_type_obj Field type object.
 * @return mixed
 */
function xprofile_filter_pre_validate_value_by_field_type( $value, $field, $field_type_obj ) {
	if ( method_exists( $field_type_obj, 'pre_validate_filter' ) ) {
		$value = call_user_func( array( $field_type_obj, 'pre_validate_filter' ), $value, $field->id );
	}

	return $value;
}

/**
 * Escape field value for display.
 *
 * Most field values are simply run through esc_html(). Those that support rich text (by default, `textarea` only)
 * are sanitized using kses, which allows a whitelist of HTML tags.
 *
 * @since BuddyPress 2.4.0
 *
 * @param string $value      Field value.
 * @param string $field_type Field type.
 * @param int    $field_id   Field ID.
 * @return string
 */
function bp_xprofile_escape_field_data( $value, $field_type, $field_id ) {
	if ( bp_xprofile_is_richtext_enabled_for_field( $field_id ) ) {
		// The xprofile_filter_kses() expects a BP_XProfile_ProfileData object.
		$data_obj = null;
		if ( bp_is_user() ) {
			$data_obj = new BP_XProfile_ProfileData( $field_id, bp_displayed_user_id() );
		}

		$value = xprofile_filter_kses( $value, $data_obj );
	} else {
		$value = esc_html( $value );
	}

	return $value;
}

/**
 * Filter an Extended Profile field value, and attempt to make clickable links
 * to members search results out of them.
 *
 * - Not run on datebox field types.
 * - Not run on values without commas with less than 5 words.
 * - URL's are made clickable.
 *
 * To disable globally:
 *     remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9, 3 );
 *
 * To disable for a single field, use the 'Autolink' settings in Dashboard > Users > Profile Fields.
 *
 * @since BuddyPress 1.1.0
 * @since BuddyBoss 3.1.1 Removed checking autolink, as autolinking is disabled on all fields now.
 * All this function does now is make links clickable.
 *
 * @param string $field_value Profile field data value.
 * @param string $field_type  Profile field type.
 * @return string|array
 */
function xprofile_filter_link_profile_data( $field_value, $field_type = 'textbox' ) {
	global $field;

	if ( 'datebox' === $field_type ) {
		return $field_value;
	}

	if ( strpos( $field_value, ',' ) === false && strpos( $field_value, ';' ) === false && ( count( explode( ' ', $field_value ) ) > 5 ) ) {
		return $field_value;
	}

    // If the value is a URL, make it clickable.
    if ( preg_match( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', $field_value ) ) {
        $field_value = make_clickable( $field_value );
    }

    return $field_value;
}

/**
 * Ensures that BP data appears in comments array.
 *
 * This filter loops through the comments return by a normal WordPress request
 * and swaps out user data with BP xprofile data, where available.
 *
 * @since BuddyPress 1.2.0
 *
 * @param array $comments Comments to filter in.
 * @param int   $post_id  Post ID the comments are for.
 * @return array $comments
 */
function xprofile_filter_comments( $comments, $post_id = 0 ) {

	// Locate comment authors with WP accounts.
	foreach( (array) $comments as $comment ) {
		if ( $comment->user_id ) {
			$user_ids[] = $comment->user_id;
		}
	}

	// If none are found, just return the comments array.
	if ( empty( $user_ids ) ) {
		return $comments;
	}

	// Pull up the xprofile fullname of each commenter.
	if ( $fullnames = bp_core_get_user_displaynames( $user_ids ) ) {
		foreach( (array) $fullnames as $user_id => $user_fullname ) {
			$users[ $user_id ] = trim( stripslashes( $user_fullname ) );
		}
	}

	// Loop through and match xprofile fullname with commenters.
	foreach( (array) $comments as $i => $comment ) {
		if ( ! empty( $comment->user_id ) ) {
			if ( ! empty( $users[ $comment->user_id ] ) ) {
				$comments[ $i ]->comment_author = $users[ $comment->user_id ];
			}
		}
	}

	return $comments;
}
add_filter( 'comments_array', 'xprofile_filter_comments', 10, 2 );

/**
 * Filter BP_User_Query::populate_extras to override each queries users fullname.
 *
 * @since BuddyPress 1.7.0
 *
 * @param BP_User_Query $user_query   User query to filter.
 * @param string        $user_ids_sql SQL statement to use.
 */
function bp_xprofile_filter_user_query_populate_extras( BP_User_Query $user_query, $user_ids_sql = '' ) {

	if ( ! bp_is_active( 'xprofile' ) ) {
		return;
	}

	$user_id_names = bp_core_get_user_displaynames( $user_query->user_ids );

	// Loop through names and override each user's fullname.
	foreach ( $user_id_names as $user_id => $user_fullname ) {
		if ( isset( $user_query->results[ $user_id ] ) ) {
			$user_query->results[ $user_id ]->fullname = $user_fullname;
		}
	}
}
add_filter( 'bp_user_query_populate_extras', 'bp_xprofile_filter_user_query_populate_extras', 2, 2 );

/**
 * Parse 'xprofile_query' argument passed to BP_User_Query.
 *
 * @since BuddyPress 2.2.0
 *
 * @param BP_User_Query $q User query object.
 */
function bp_xprofile_add_xprofile_query_to_user_query( BP_User_Query $q ) {

	// Bail if no `xprofile_query` clause.
	if ( empty( $q->query_vars['xprofile_query'] ) ) {
		return;
	}

	$xprofile_query = new BP_XProfile_Query( $q->query_vars['xprofile_query'] );
	$sql            = $xprofile_query->get_sql( 'u', $q->uid_name );

	if ( ! empty( $sql['join'] ) ) {
		$q->uid_clauses['select'] .= $sql['join'];
		$q->uid_clauses['where'] .= $sql['where'];
	}
}
add_action( 'bp_pre_user_query', 'bp_xprofile_add_xprofile_query_to_user_query' );

/**
 * Filter meta queries to modify for the xprofile data schema.
 *
 * @since BuddyPress 2.0.0
 *
 * @access private Do not use.
 *
 * @param string $q SQL query.
 * @return string
 */
function bp_xprofile_filter_meta_query( $q ) {
	global $wpdb;

	$raw_q = $q;

	/*
	 * Replace quoted content with __QUOTE__ to avoid false positives.
	 * This regular expression will match nested quotes.
	 */
	$quoted_regex = "/'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'/s";
	preg_match_all( $quoted_regex, $q, $quoted_matches );
	$q = preg_replace( $quoted_regex, '__QUOTE__', $q );

	// Get the first word of the command.
	preg_match( '/^(\S+)/', $q, $first_word_matches );

	if ( empty( $first_word_matches[0] ) ) {
		return $raw_q;
	}

	// Get the field type.
	preg_match( '/xprofile_(group|field|data)_id/', $q, $matches );

	if ( empty( $matches[0] ) || empty( $matches[1] ) ) {
		return $raw_q;
	}

	switch ( $first_word_matches[0] ) {

		/**
		 * SELECT:
		 * - replace 'xprofile_{fieldtype}_id' with 'object_id'
		 * - ensure that 'object_id' is aliased to 'xprofile_{fieldtype}_id',
		 *   because update_meta_cache() needs the column name to parse
		 *   the query results
		 * - append the 'object type' WHERE clause
		 */
		case 'SELECT' :
			$q = str_replace(
				array(
					$matches[0],
					'SELECT object_id',
					'WHERE ',
				),
				array(
					'object_id',
					'SELECT object_id AS ' . $matches[0],
					$wpdb->prepare( 'WHERE object_type = %s AND ', $matches[1] ),
				),
				$q
			);
			break;

		/**
		 * UPDATE and DELETE:
		 * - replace 'xprofile_{fieldtype}_id' with 'object_id'
		 * - append the 'object type' WHERE clause
		 */
		case 'UPDATE' :
		case 'DELETE' :
			$q = str_replace(
				array(
					$matches[0],
					'WHERE ',
				),
				array(
					'object_id',
					$wpdb->prepare( 'WHERE object_type = %s AND ', $matches[1] ),
				),
				$q
			);
			break;

		/**
		 * UPDATE and DELETE:
		 * - replace 'xprofile_{fieldtype}_id' with 'object_id'
		 * - ensure that the object_type field gets filled in
		 */
		case 'INSERT' :
			$q = str_replace(
				array(
					'`' . $matches[0] . '`',
					'VALUES (',
				),
				array(
					'`object_type`,`object_id`',
					$wpdb->prepare( "VALUES (%s,", $matches[1] ),
				),
				$q
			);
			break;
	}

	// Put quoted content back into the string.
	if ( ! empty( $quoted_matches[0] ) ) {
		for ( $i = 0; $i < count( $quoted_matches[0] ); $i++ ) {
			$quote_pos = strpos( $q, '__QUOTE__' );
			$q = substr_replace( $q, $quoted_matches[0][ $i ], $quote_pos, 9 );
		}
	}

	return $q;
}

/**
 * Conditionally filters 'bp_get_the_profile_field_name' to return alternate name if available.
 * Filter is only applied if:
 *  1. we are on profile > edit screens
 *  2. we are on registration page
 *
 * @since BuddyBoss 3.1.1
 *
 * @global \BP_XProfile_Field_Type $field
 *
 * @param string $field_name
 * @return string
 */
function xprofile_filter_field_edit_name ( $field_name ) {
    $is_field_edit_mode = false;

    $current_field = false;

    if ( bp_is_profile_component() && 'edit' == bp_current_action() ) {
        //we are on profile > edit screens, we should display alternate name, if available, instead of main name.
        $is_field_edit_mode = true;

        //we can use global $field variable here
        global $field;
        $current_field = $field;
    }

    if ( !$is_field_edit_mode && bp_is_register_page() ) {
        //We are on registration page/form. We should display alternate name, if available, instead of main name.
        $is_field_edit_mode = true;

        //we can use global $field variable here
        global $field;
        $current_field = $field;
    }

    //@todo : Should we do it if an admin is editing user profiles in backend ( wp-admin/edit-user.php... ) ?

    if ( $is_field_edit_mode ) {
        $alternate_name = bp_get_the_profile_field_alternate_name( $current_field );

        if ( !empty( $alternate_name ) ) {
            $field_name = $alternate_name;
        }
    }

    return $field_name;
}

function bp_xprofile_validate_nickname_value( $retval, $field_id, $vlaue, $user_id = null ) {
	if ( $field_id != bp_xprofile_nickname_field_id() ) {
		return $retval;
	}

	if ( $retval ) {
		return $retval;
	}

	$value = strtolower( $vlaue );

	// only alpha numeric, underscore, dash
	if ( ! preg_match( '/^([A-Za-z0-9-_\.]+)$/', $vlaue ) ) {
		return __( 'Invalid nickname. Only "a-z", "0-9", "-", "_" and "." are allowed.', 'buddyboss' );
	}

	// cannot have 2 continued special characters
	if ( preg_match( '/([-_\.]{2})/', $vlaue ) ) {
		return __( '"-", "_" and "." cannot be repeat twice in nickname.', 'buddyboss' );
	}

	// must be longer then 6 characters
	// if ( strlen( $value ) < 6 ) {
	// 	return __( 'Nickname must be at least 6 characters.', 'buddyboss' );
	// }

	// must be shorter then 20 characters
	$nickname_length = apply_filters( 'xprofile_nickname_max_length', 32 );
	if ( strlen( $value ) > $nickname_length ) {
		return sprintf( __( 'Nickname must be shorter than %d characters.', 'buddyboss' ), $nickname_length );
	}

	global $wpdb;
	$where = [
		'meta_key = "nickname"',
		'meta_value = "' . $value . '"'
	];

	if ( $user_id ) {
		$where[] = 'user_id != ' . $user_id;
	}

	$sql = sprintf(
		'SELECT count(*) FROM %s WHERE %s',
		$wpdb->usermeta,
		implode( ' AND ', $where )
	);

	if ( $asdf = $wpdb->get_var( $sql ) > 0 ) {
		return __( 'Nickname has already been taken.', 'buddyboss' );
	}

	return $retval;
}
