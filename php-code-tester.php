<?php
/**
 * Plugin Name: PHP Code Tester
 * Plugin URI: https://ironikus.com/downloads/php-code-tester/
 * Description: Temporarily execute PHP code on any kind of page
 * Version: 1.0.0
 * Author: Ironikus
 * Author URI: https://ironikus.com/
 * License: GPL2
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Plugin Name
define( 'IRNKS_PHPCT_PLUGIN_NAME',    'PHP Code Tester' );

// Plugin Version
define( 'IRNKS_PHPCT_PLUGIN_VERSION',    '1.0.0' );

// Plugin Root File.
define( 'IRNKS_PHPCT_PLUGIN_FILE',    __FILE__ );

// Plugin base.
define( 'IRNKS_PHPCT_PLUGIN_BASE',    plugin_basename( IRNKS_PHPCT_PLUGIN_FILE ) );

// Plugin Folder Path.
define( 'IRNKS_PHPCT_PLUGIN_DIR',     plugin_dir_path( IRNKS_PHPCT_PLUGIN_FILE ) );

// Plugin Folder URL.
define( 'IRNKS_PHPCT_PLUGIN_URL',     plugin_dir_url( IRNKS_PHPCT_PLUGIN_FILE ) );

function irnks_phpct_load(){

	require_once IRNKS_PHPCT_PLUGIN_DIR . 'core/class-code-tester.php';

}

irnks_phpct_load();