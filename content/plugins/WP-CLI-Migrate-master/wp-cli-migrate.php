<?php
/*
Plugin Name: WP Migrate DB CLI Interface
Description: Adds a migrate command to wp-cli. Depends on WP Migrate DB.
Author: Duncan Brown
Version: 0.1
Author URI: http://duncanjbrown.com
*/

function wp_migrate_db_cli_init() {

	if ( !class_exists( 'WP_Migrate_DB' ) ) {
		return;
	}

	if ( defined('WP_CLI') && WP_CLI ) {
		include dirname(__FILE__) . '/lib/migrate.php';
	}
}

add_action( 'plugins_loaded', 'wp_migrate_db_cli_init' );