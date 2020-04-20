<?php
/*
 * Plugin Name: BuddyForms Limit User Submissions by Role
 * Plugin URI: http://buddyforms.com/
 * Description: BuddyForms Hook Fields
 * Version: 1.0.0
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/buddyforms/
 * Licence: GPLv3
 * Network: false
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

function buddyforms_lubr_user_can_edit( $user_can_edit, $form_slug, $post_id ) {
	global $buddyforms, $bf_form_error;

	if ( isset( $buddyforms[ $form_slug ]['limit_user_submissions_by_roles'] ) ) {

		$current_user = wp_get_current_user();
		$user_roles   = $current_user->roles;

		if ( isset( $buddyforms[ $form_slug ]['limit_user_submissions_by_roles'] ) ) {
			foreach ( $buddyforms[ $form_slug ]['limit_user_submissions_by_roles'] as $role_name => $post_limit ) {
				if ( $post_limit >= 0 ) {
					if ( in_array( $role_name, (array) $user_roles ) ) {
						$user_post_count = count_user_posts( $current_user->ID, $buddyforms[ $form_slug ]['post_type'] );
						if ( $user_post_count >= $post_limit ) {
							add_filter( 'buddyforms_user_can_edit_error_message', function ( $post_limit ) {

								return __( 'You have reached your post limit for this form.', 'buddyforms' );

							} );
							$user_can_edit = false;
						}
					}
				}
			}
		}
	}

	return $user_can_edit;
}

add_action( 'buddyforms_user_can_edit', 'buddyforms_lubr_user_can_edit', 10, 3 );

function buddyforms_lusbr_admin_settings_sidebar_metabox_html() {
	global $post, $buddyforms;

	if ( $post->post_type != 'buddyforms' ) {
		return;
	}

	$buddyform = get_post_meta( get_the_ID(), '_buddyforms_options', true );

	$form_setup = array();

	$roles = Array();
	foreach ( get_editable_roles() as $role_name => $role_info ) {

		$limit_user_submissions_by_roles = isset( $buddyform['limit_user_submissions_by_roles'][ $role_name ] ) ? $buddyform['limit_user_submissions_by_roles'][ $role_name ] : 0;
		$form_setup[]                    = new Element_Number( $role_name, "buddyforms_options[limit_user_submissions_by_roles][$role_name]", array( 'value' => $limit_user_submissions_by_roles ) );

	}

	$form_setup[] = new Element_Checkbox( "<b>" . __( 'Add this form as Profile Tab', 'buddyforms' ) . "</b>", "buddyforms_options[profiles_integration]", $roles, array( 'value' => $limit_user_submissions_by_roles, 'shortDesc' => __( 'Check das', 'buddyforms' ) ) );

	buddyforms_display_field_group_table( $form_setup );
}

function buddyforms_lusbr_admin_settings_sidebar_metabox() {
	add_meta_box( 'buddyforms_lusbr', __( "Limit Submissions", 'buddyforms' ), 'buddyforms_lusbr_admin_settings_sidebar_metabox_html', 'buddyforms', 'normal', 'low' );
	add_filter( 'postbox_classes_buddyforms_buddyforms_lusbr', 'buddyforms_metabox_class' );
	add_filter( 'postbox_classes_buddyforms_buddyforms_lusbr', 'buddyforms_metabox_hide_if_form_type_register' );
}

add_filter( 'add_meta_boxes', 'buddyforms_lusbr_admin_settings_sidebar_metabox' );

function buddyforms_lusbr_tgmpa_register() {
	// Create the required plugins array
	if ( ! defined( 'BUDDYFORMS_PRO_VERSION' ) ) {
		$plugins['buddyforms'] = array(
			'name'     => 'BuddyForms',
			'slug'     => 'buddyforms',
			'required' => true,
		);


		$config = array(
			'id'           => 'buddyforms-tgmpa',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => false,
			'is_automatic' => true,
		);

		// Call the tgmpa function to register the required plugins
		tgmpa( $plugins, $config );
	}
}

function buddyforms_lusbr_init() {
	// Only Check for requirements in the admin
	if ( ! is_admin() ) {
		return;
	}

	// Require TGM
	require( dirname( __FILE__ ) . '/includes/resources/tgm/class-tgm-plugin-activation.php' );

	add_action( 'tgmpa_register', 'buddyforms_lusbr_tgmpa_register');
}

//
// Check the plugin dependencies
//
add_action( 'init', 'buddyforms_lusbr_init', 1, 1 );
