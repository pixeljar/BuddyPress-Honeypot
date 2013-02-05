<?php
/*
Plugin Name: Pixel Jar BuddyPress Honeypot
Plugin URI: http://pixeljar.net/buddypress-honeypot
Description: Simple plugin to add a honeypot to the BuddyPress registration form to prevent spam registrations.
Version: 1.1
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

// INTERNATIONALIZATION
load_plugin_textdomain( 'pj-buddypress-honeypot', null, basename( dirname( __FILE__ ) ) );

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
		add_filter( 'bp_signup_validate', array( &$this, 'check_honeypot' ) );
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
		echo '<input type="text" name="'.apply_filters( 'bppj_honeypot_name', self::BPPJ_HONEYPOT_NAME ).'" id="'.apply_filters( 'bppj_honeypot_id', self::BPPJ_HONEYPOT_ID ).'" />';
		echo '</div>';
	}

	/**
	 * Check to see if the honeypot field has a value.
	 * If it does, return an error
	 *
	 * @filter bppj_honeypot_name
	 * @filter bppj_honeypot_fail_message
	 */
	function check_honeypot() {
		global $bp;

		$bppj_honeypot_name = apply_filters( 'bppj_honeypot_name', self::BPPJ_HONEYPOT_NAME );

		if( isset( $_POST[$bppj_honeypot_name] ) && !empty( $_POST[$bppj_honeypot_name] ) ) 
			$bp->signup->errors['pjbp_honeypot'] = __('Sorry, something went wrong with your registration','buddypress');

	}

}
new pjbp_honeypot;
