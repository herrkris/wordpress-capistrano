<?php

/**
 * Run database migrations using the search and replace powers of wp-migrate-db
 *
 * @package wp-cli
 * @subpackage commands/community
 * @author Duncan Brown <duncanjbrown@gmail.com>
 */
class WP_Migrate_DB_Command extends WP_CLI_Command {

	/**
	 * @synopsis output_folder output_host filename
	 */
	function to( $args = array() ) {

		$wpmdb = new CLI_Migrate( $args );

		$wpmdb->migrate();
		
		WP_CLI::success( $wpmdb->get_filename() );
	}


	/**
	 * Help function 
	 */
	public static function help() {
		WP_CLI::line( <<<EOB
usage: wp migrate to [output folder] [output host] [filename]

For example, to migrate your db to example.com, where the files are stored in /var/www/example.com:
wp migrate to /var/www/example.com http://example.com filename.sql

To write to STDOUT pass that as the filename, eg:
wp migrate run /var/www/example.com http://example.com STDOUT
EOB
	);
	}
}

/**
 * WP-CLI API Adapter for WP_Migrate_DB
 */
class CLI_Migrate extends WP_Migrate_DB {

	private $new_url;
	private $new_path;
	private $old_path;
	private $old_url;
	public $filename;

	function __construct( $args ) {
		
		parent::__construct();

		if ( ! isset( $args[0] ) || ! isset( $args[1] ) ) {
			WP_Migrate_DB_Command::help();
			die();
		}

		$this->new_path = trailingslashit( $args[0] );
		$this->new_url = $args[1];

		$this->old_path = ABSPATH;
		$this->old_url = get_bloginfo( 'url' );

		// WP-Migrate-DB relies on the $_POST global for its settings
		// so we have to spoof it here. 	
		$_POST = array(
			'old_path' => $this->old_path,
			'new_path' => $this->new_path,
			'old_url' => $this->old_url,
			'new_url' => $this->new_url
		);

		if ( isset( $args[2] ) ) {
			$this->filename = $args[2];
		}
	}

	function migrate() {

		if ( $this->filename == 'STDOUT' ) 
			$this->fp = fopen( 'php://stdout', 'w' );
		else 
			$this->fp = $this->open( $this->filename );

		$this->db_backup_header();
		$this->db_backup();
		$this->close( $this->fp );
	}

	function get_filename() {
		return $this->filename;
	}

	function db_backup() {
	
		global $table_prefix, $wpdb;

		$tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
		$tables = array_map(create_function('$a', 'return $a[0];'), $tables);

		foreach ($tables as $table) {

			WP_CLI::line( "Migrating ${table}..." );
			// Increase script execution time-limit to 15 min for every table.
			if ( !ini_get('safe_mode')) @set_time_limit(15*60);
			// Create the SQL statements
			$this->stow("# --------------------------------------------------------\n");
			$this->stow("# " . sprintf(__('Table: %s','wp-migrate-db'),$this->backquote($table)) . "\n");
			$this->stow("# --------------------------------------------------------\n");
			$this->backup_table($table);
		}

		if (count($this->errors)) {
			return false;
		} else {
			return true;
		}

	} 
	
	function stow($query_line, $replace = true) {

        if ($this->gzip()) {
            if(! @gzwrite($this->fp, $query_line))
                $this->errors['file_write'] = __('There was an error writing a line to the backup script:','wp-db-backup') . '  ' . $query_line . '  ' . $php_errormsg;
        } else {
            if(false === @fwrite($this->fp, $query_line))
                $this->error['file_write'] = __('There was an error writing a line to the backup script:','wp-db-backup') . '  ' . $query_line . '  ' . $php_errormsg;
        }
	}

	function db_backup_header() {
		$this->stow("# " . __('WordPress MySQL database migration','wp-migrate-db') . "\n", false);
		$this->stow("# " . sprintf(__('From %s to %s','wp-migrate-db'), $this->old_url, $this->new_url) . "\n", false);
		$this->stow("#\n", false);
		$this->stow("# " . sprintf(__('Generated: %s','wp-migrate-db'),date("l j. F Y H:i T")) . "\n", false);
		$this->stow("# " . sprintf(__('Hostname: %s','wp-migrate-db'),DB_HOST) . "\n", false);
		$this->stow("# " . sprintf(__('Database: %s','wp-migrate-db'),$this->backquote(DB_NAME)) . "\n", false);
		$this->stow("# --------------------------------------------------------\n\n", false);
	}

}

WP_CLI::add_command( 'migrate', 'WP_Migrate_DB_Command' );