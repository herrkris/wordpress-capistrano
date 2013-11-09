<?php
// ===================================================
// Load database info and local development parameters
// ===================================================

if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
    include( dirname( __FILE__ ) . '/local-config.php' );
} else {
    define( 'DB_NAME', '%%DB_NAME%%' );
    define( 'DB_USER', '%%DB_USER%%' );
    define( 'DB_PASSWORD', '%%DB_PASSWORD%%' );
    define( 'DB_HOST', '%%DB_HOST%%' ); // Probably 'localhost'
}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'wp_';

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', 'de_DE' );

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
    define( 'ABSPATH', dirname( __FILE__ ) . '/wordpress/' );

require_once(ABSPATH . 'wp-settings.php');