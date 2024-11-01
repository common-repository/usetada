<?php
/**
 * Plugin Name: TADA - Membership, Loyalty, Referral & Subscription Platform
 * Plugin URI: https://usetada.com/
 * Description: Retain your customers using loyalty, membership, subscription, referral, & many more!
 * Version: 1.1.3
 * Author: TADA
 * Author URI: https://usetada.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.en.html
 * Domain Path: /languages
 * WC requires at least: 4.0.0
 * WC tested up to: 4.8.0
 */

/*
 	Copyright (C) 2021 TADA

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

define( 'USETADA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'USETADA_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada.php' );
require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada-api.php' );
require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada-woocommerce.php' );
require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada-settings.php' );
require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada-widget.php' );

add_action( 'init', array( 'USETADA', 'init' ) );

register_activation_hook( __FILE__, array( 'USETADA', 'plugin_activation' ) );
register_uninstall_hook( __FILE__, array( 'USETADA', 'plugin_uninstall' ) );

if( is_admin() ){
	require_once( USETADA_PLUGIN_DIR . 'includes/class.usetada-admin.php' );
	add_action( 'init', array( 'USETADA_Admin', 'init' ) );
}