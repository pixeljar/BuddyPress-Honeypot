<?php
/*
Plugin Name: Pixel Jar BuddyPress Honeypot
Plugin URI: http://pixeljar.net/buddypress-honeypot
Description: Simple plugin to add a honeypot to the BuddyPress registration form to prevent spam registrations.
Version: 1.0
Author: Pixel Jar
Author URI: http://pixeljar.net
*/

/**
 * Copyright (c) 2012 Pixel Jar. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

class pjbp_honeypot {

	/**
	 * default values for the honeypot
	 * change these via filters if you
	 * start getting spam registrations
	 */
	CONST BPPJ_HONEYPOT_NAME	= 'oh_no_you_dint';
	CONST BPPJ_HONEYPOT_ID		= 'sucka';

	function __construct() {
		add_action( 'bp_after_signup_profile_fields', array( &$this, 'add_honeypot' ) );
		add_filter( 'bp_core_validate_user_signup', array( &$this, 'check_honeypot' ) );
	}

	/**
	 * Add a hidden text input that users won't see
	 * so it should always be empty. If it's filled out
	 * we know it's a spambot or some other hooligan
	 *
	 * @filter bppj_honeypot_name
	 * @filter bppj_honeypot_id
	 */
	function add_honeypot() {
		
		echo '<div style="display: none;">';
		echo '<input type="text" name="'.apply_filters( 'bppj_honeypot_name', BPPJ_HONEYPOT_NAME ).'" id="'.apply_filters( 'bppj_honeypot_id', BPPJ_HONEYPOT_ID ).'" />';
		echo '</div>';
	}

	/**
	 * Check to see if the honeypot field has a value.
	 * If it does, return an error
	 *
	 * @filter bppj_honeypot_name
	 * @filter bppj_honeypot_fail_message
	 */
	function check_honeypot( $result = array() ) {
		global $bp;

		$bppj_honeypot_name = apply_filters( 'bppj_honeypot_name', BPPJ_HONEYPOT_NAME );

		if( isset( $_POST[$bppj_honeypot_name] ) && !empty( $_POST[$bppj_honeypot_name] ) )
			$result['errors']->add( 'pjbp_honeypot', apply_filters( 'bppj_honeypot_fail_message', __( "You're totally a spammer. Go somewhere else with your spammy ways." ) ) );
		
		return $result;
	}

}
new pjbp_honeypot;
