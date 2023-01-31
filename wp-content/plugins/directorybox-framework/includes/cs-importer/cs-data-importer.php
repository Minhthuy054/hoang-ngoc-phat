<?php
/**
 * Contains wp_dp_cs_Data_Importer class
 *
 * @since	1.2
 * @package	WordPress
 */

if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
	define( 'WP_LOAD_IMPORTERS', true );
}

/**
 * Class wp_dp_cs_Data_Importer class
 *
 * @since	1.2
 */
if ( ! class_exists('wp_dp_cs_Data_Importer') ) {
    class wp_dp_cs_Data_Importer {
	/**
	 * Demo data name
	 *
	 * @var string demo data name
	 */
	public $demo_data_name = null;

	/**
	 * Check if content is to be importered
	 *
	 * @var string Check if content is to be importered
	 */
	public $is_content = false;
	
	/**
	 * Check if navitems is to be importered
	 *
	 * @var string Check if navitems is to be importered
	 */
	public $is_navitems = false;
	
	/**
	 * Check if media_attachments is to be importered
	 *
	 * @var string Check if media_attachments is to be importered
	 */
	public $is_media_attachments = false;
	
	/**
	 * Check if widgets is to be importered
	 *
	 * @var Boolean	Check if widgets is to be importered
	 */
	public $is_widgets = false;

	/**
	 * Check if theme options is to be importered
	 *
	 * @var Boolean Check if theme options is to be importered
	 */
	public $is_theme_options = false;
	
	/**
	 * Check if fonts is to be importered
	 *
	 * @var Boolean Check if fonts is to be importered
	 */
	public $is_fonts = false;
	
	/**
	 * Check if Icons is to be importered
	 *
	 * @var Boolean Check if Icons is to be importered
	 */
	public $is_icons = false;

	/**
	 * Check if users is to be importered
	 *
	 * @var Boolean Check if users is to be importered
	 */
	public $is_users = false;

	/**
	 * Check if plugins is to be importered
	 *
	 * @var Boolean Check if plugins is to be importered
	 */
	public $is_plugins = false;

	/**
	 * Check if attachments is going to be processed, fetched as one zip and
	 * then processed on own end.
	 *
	 * @var Boolean Check if content attachments is going to be processed
	 */
	public $is_attachments_zip = false;

	/**
	 * WP content XML path on remote server
	 *
	 * @var	string	WP content XML path on remote server
	 */
	public $wp_data_path = '';

	/**
	 * Theme options path on remote server
	 *
	 * @var	string	Theme options path on remote server
	 */
	public $theme_options_data_path	= '';
	
	/**
	 * Fonts path on remote server
	 *
	 * @var	string	Fonts path on remote server
	 */
	public $fonts_data_path	= '';
	
	/**
	 * Icons path on remote server
	 *
	 * @var	string	Icons path on remote server
	 */
	public $icons_data_path	= '';

	/**
	 * Widgets path on remote server
	 *
	 * @var string	Widgets path on remote server
	 */
	public $widget_data_path = '';

	/**
	 * Users path on remote server
	 *
	 * @var	string	Users path on remote server
	 */
	public $users_data_path = '';

	/**
	 * Plugins path on remote server
	 *
	 * @var	string	Plugins path on remote server
	 */
	public $plugins_data_path = '';

	/**
	 * Sliders path on remote server
	 *
	 * @var	string	Sliders path on remote server
	 */
	public $sliders_data_path = '';

	/**
	 * Slider Options
	 *
	 * @var	string	Slider options
	 */
	public $sliders_options = '';

	/**
	 * CS Importer Class path
	 *
	 * @var	string	CS Importer Class Path
	 */
	public $wp_dp_cs_importer_class_path = '';

	/**
	 * WP uploades URL path
	 *
	 * @var string	WP uploads URL path
	 */
	public $wp_upload_url_path = '';
	
	/**
	 * WP uploades Base URL path
	 *
	 * @var string	WP uploads Base URL path
	 */
	public $wp_upload_base_url_path = '';

	/**
	 * WP uploads absolute path
	 *
	 * @var string	WP uploads absolute path
	 */
	public $wp_upload_dir_path = '';
	
	/**
	 * WP uploads base absolute path
	 *
	 * @var string	WP uploads base absolute path
	 */
	public $wp_upload_base_dir_path = '';

	/**
	 * This will keep return value of any action
	 *
	 * @var Boolean	This will keep return value of any action
	 */
	public $action_return = false;

	/**
	 * Constructor
	 */
	function __construct() {
		set_time_limit( 0 );
		$paths = wp_upload_dir();
		$this->wp_upload_url_path = trailingslashit( $paths['url'] );
		$this->wp_upload_base_url_path = trailingslashit( $paths['baseurl'] );
		$this->wp_upload_dir_path = trailingslashit( $paths['path'] );
		$this->wp_upload_base_dir_path = trailingslashit( $paths['basedir'] );
		$this->home_url = CS_HOME_BASE;
		$this->home_replace_url = DEMO_DATA_HOME_URL;

		$this->wp_dp_cs_importer_class_path 	= wp_dp_framework::plugin_dir() . 'includes/cs-importer/wordpress-importer.php';
	}

	/**
	 * Import configured Type of contents like (WP content, Users, widgets, etc.)
	 */
	function import() {
		$this->action_return = false;
		if ( $this->is_content ) {
            ob_start();
			$this->import_wp_data();
			ob_end_clean();
 			if ( $this->action_return ) {
				ob_start();
				$this->set_up_pages();
				$this->delete_default_content();
				$this->update_db();
				ob_end_clean();
				$this->make_output( true, __( 'WP data successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry importer class missing.', 'wp-dp-frame' ) );
			}
		}
		
		if ( $this->is_navitems ) {
            ob_start();
			$this->import_wp_navitems();
			ob_end_clean();
 			if ( $this->action_return ) {
				$this->make_output( true, __( 'WP Navigation Menu Items successfully got imported.', 'wp-rem-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry navigation menu items class missing.', 'wp-rem-frame' ) );
			}
		}
		
		if ( $this->is_media_attachments ) {
            ob_start();
			$this->import_wp_media_attachments();
			ob_end_clean();
 			if ( $this->action_return ) {
				$this->make_output( true, __( 'WP Media Attachments successfully got imported.', 'wp-rem-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry media attachments class missing.', 'wp-rem-frame' ) );
			}
		}

		$this->action_return = false;
		if ( $this->is_widgets ) {
			ob_start();
			$this->import_widgets();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Widgets successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry widgets class missing.', 'wp-dp-frame' ) );
			}
		}

		$this->action_return = false;
		if ( $this->is_theme_options ) {
			ob_start();
			$this->import_theme_options();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Theme options successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry theme options file not readable.', 'wp-dp-frame' ) );
			}
		}
		
		$this->action_return = false;
		if ( $this->is_fonts ) {
			ob_start();
			$this->import_fonts();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Fonts successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry fonts file not readable.', 'wp-dp-frame' ) );
			}
		}
		
		$this->action_return = false;
		if ( $this->is_icons ) {
			ob_start();
			$this->import_icons();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Icons successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry icons file not readable.', 'wp-dp-frame' ) );
			}
		}

		$this->action_return = false;
		if ( $this->is_users ) {
			ob_start();
			$this->import_users();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Users successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry users file not readable.', 'wp-dp-frame' ) );
			}
		}

		$this->action_return = false;
		if ( isset( $this->is_menus ) ) {
			ob_start();
			$this->import_menus_and_locations();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Menus successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry menus was not imported.', 'wp-dp-frame' ) );
			}
		}

		$this->action_return = false;
		if ( $this->is_plugins ) {
			ob_start();
			$this->import_plugin_options();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Plugin Options successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry plugin options was not imported.', 'wp-dp-frame' ) );
			}
		}

		$this->action_return = false;
		if ( isset( $this->is_sliders ) ) {
			ob_start();
			$this->import_sliders();
			ob_end_clean();
			if ( $this->action_return ) {
				$this->make_output( true, __( 'Sliders successfully got imported.', 'wp-dp-frame' ) );
			} else {
				$this->make_output( false, __( 'Sorry sliders was not imported.', 'wp-dp-frame' ) );
			}
		}
	}
	
	/**
	 * Import WP data and also process attachments if asked to process them first
	 * invoke wp_rem_cs_import_wp_data action
	 */
	function import_wp_data() {
		global $wp_filesystem;
		
		// Fetch XML contents from remote server.
		$demo_data_str = $wp_filesystem->get_contents( $this->wp_data_path );
		// Replace paths
		$this->attachments_replace_url = DEFAULT_DEMO_DATA_URL;
		if ( $this->demo_data_name == DEFAULT_DEMO_DATA_NAME ) {
			$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
		} else {
			// Replace Upload URL
			$this->attachments_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, DEMO_DATA_URL );
			$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
			// Replace Home URL
			$this->home_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, $this->home_replace_url );
			$demo_data_str = str_replace( $this->home_replace_url, $this->home_url, $demo_data_str );
		}
		$this->wp_data_path = $this->wp_upload_dir_path . $this->demo_data_name . '_' . time() . '.xml';
		$wp_filesystem->put_contents( $this->wp_data_path, $demo_data_str );
		do_action( 'wp_dp_cs_import_wp_data', $this );

		// Delete files after processing.
		unlink( $this->wp_data_path );
	}
	
	/**
	 * Import Navigation Menu Items
	 * invoke wp_rem_cs_import_wp_data action
	 */
	function import_wp_navitems() {
		global $wp_filesystem;
		
		// Fetch XML contents from remote server.
		$demo_data_str = $wp_filesystem->get_contents( $this->wp_data_path );
		// Replace paths
		$this->attachments_replace_url = DEFAULT_DEMO_DATA_URL;
		if ( $this->demo_data_name == DEFAULT_DEMO_DATA_NAME ) {
			$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
		} else {
			// Replace Upload URL
			$this->attachments_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, DEMO_DATA_URL );
			$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
			// Replace Home URL
			$this->home_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, $this->home_replace_url );
			$demo_data_str = str_replace( $this->home_replace_url, $this->home_url, $demo_data_str );
		}

		$this->wp_data_path = $this->wp_upload_dir_path . $this->demo_data_name . '_navitems_' . time() . '.xml';
		$wp_filesystem->put_contents( $this->wp_data_path, $demo_data_str );

		do_action( 'wp_dp_cs_import_wp_data', $this );

		// Delete files after processing.
		unlink( $this->wp_data_path );
	}
	
	/**
	 * Import Media Attachments
	 * invoke wp_rem_cs_import_wp_data action
	 */
	function import_wp_media_attachments() {
		global $wp_filesystem;
		
		require_once ABSPATH . '/wp-admin/includes/file.php';

		// Fetch XML contents from remote server.
		$demo_data_str = $wp_filesystem->get_contents( $this->wp_data_path );

		/**
		 * If we have to fetch attachments from remote server as a single zip.
		 * Then we also have to modify file paths as attachments will be already
		 * fetched to uploads
		 */
		if ( $this->is_attachments_zip ) {
			// If we have to fetch attachments from remote server as a single zip.
			$is_zip_extracted = $this->process_attachments();

			// If zip extracted then replace paths.
			if ( $is_zip_extracted ) {
				// If we need to process attachments separately then replace URLs in XML, download as a zip
				// else only save content XML locally with new name.
				if ( $this->demo_data_name == DEFAULT_DEMO_DATA_NAME ) {
					$this->attachments_replace_url = DEFAULT_DEMO_DATA_URL;
					$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
				} else {
					// Replace Upload URL
					$this->attachments_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, DEMO_DATA_URL );
					$demo_data_str = str_replace( $this->attachments_replace_url, $this->wp_upload_url_path, $demo_data_str );
					// Replace Home URL
					$this->home_replace_url = str_replace( '{{{demo_data_name}}}', $this->demo_data_name, $this->home_replace_url );
					$demo_data_str = str_replace( $this->home_replace_url, $this->home_url, $demo_data_str );
				}
			}
		}
		
		$this->wp_data_path = $this->wp_upload_dir_path . $this->demo_data_name . '_media_' . time() . '.xml';
		$wp_filesystem->put_contents( $this->wp_data_path, $demo_data_str );
		
		do_action( 'wp_dp_cs_import_wp_data', $this );

		// Delete files after processing.
		unlink( $this->wp_data_path );
	}


	/**
	 * Process attachments, zip into uploads wp_dp_cs
	 *
	 * @return Boolean Whether zip was successfully extracted or not.
	 */
	function process_attachments() {
		
		require_once ABSPATH . '/wp-admin/includes/file.php';
		
		// Download attachments zip and extract it to local wp_dp_cs.
		$first_str_filename = '';
		$filename = $this->wp_upload_dir_path . 'temp-' . $this->demo_data_name . '-attachments.zip';
		if ( copy( $this->attachments_path, $filename ) ) {
			WP_Filesystem();
			$unzipfile = unzip_file( $filename, $this->wp_upload_dir_path . '/' . $first_str_filename . '/');

			// Delete zip after completion.
			unlink( $filename );

			// Return whether zip was extracted successfully or not.
			return $unzipfile;
		}
	}

	/**
	 * Import Widgets
	 * invoke wp_dp_cs_import_widgets
	 */
	function import_widgets() {
		do_action( 'wp_dp_cs_import_widgets', $this );
	}

	/**
	 * Import Theme Options
	 * invoke wp_dp_cs_import_theme_options
	 */
	function import_theme_options() {
		
		do_action( 'wp_dp_cs_import_theme_options', $this );
	}
	
	/**
	 * Import Fonts
	 * invoke wp_dp_import_fonts
	 */
	function import_fonts() {
		do_action( 'wp_dp_import_fonts', $this );
	}
	
	
	/**
	 * Import Icons
	 * invoke cs_import_icons
	 */
	function import_icons() {
		do_action( 'cs_import_icons', $this );
	}

	/**
	 * Import Users
	 * invoke wp_dp_cs_import_users
	 */
	function import_users() {
		do_action( 'wp_dp_import_users', $this );
	}

	/**
	 * Import Menus and Locations
	 * invoke wp_dp_cs_import_menus_and_locations
	 */
	function import_menus_and_locations() {
		do_action( 'wp_dp_cs_import_menus_and_locations', $this );
	}

	/**
	 * Import Plugin Options
	 * invoke wp_dp_cs_import_plugin_options hook
	 */
	function import_plugin_options() {
		do_action( 'wp_dp_import_plugin_options', $this );
	}

	/**
	 * Import Sliders
	 * invoke wp_dp_cs_import_rev_sliders
	 */
	function import_sliders() {
		do_action( 'wp_dp_cs_import_rev_sliders', $this );
	}

	/**
	 * Set up Pages, set home page for the WP site
	 * invoke wp_dp_cs_import_setup_pages
	 */
	function set_up_pages() {
		do_action( 'wp_dp_cs_import_setup_pages', $this );
	}
	
	
	/**
	 * Delete Default Content
	 * invoke wp_dp_delete_default_content
	 */
	function delete_default_content() {
		do_action( 'wp_dp_delete_default_content' );
	}
	
	/**
	 * Update Db Fields
	 * invoke wp_dp_importer_db_update
	 */
	function update_db() {
		do_action( 'wp_dp_importer_db_update', $this );
	}

	/**
	 * Ouput JSON result to browser
	 *
	 * @param	Boolean	$status		Define status of a request result.
	 * @param	String	$message	Description of a request result.
	 */
	function make_output( $status, $message ) {
		echo json_encode( array( 'status' => $status, 'message' => $message ) );
	}
}
}