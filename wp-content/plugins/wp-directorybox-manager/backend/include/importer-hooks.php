<?php
if ( ! function_exists( 'wp_dp_import_users_handle' ) ) {
add_action( 'wp_dp_import_users', 'wp_dp_import_users_handle' );
	function wp_dp_import_users_handle( $obj ) {
		if (class_exists('wp_dp_user_import')) {
			ob_start();
			$wp_dp_user_import = new wp_dp_user_import();
            $wp_dp_user_import->wp_dp_import_user_demodata( false, false, false, $obj->users_data_path );
			ob_end_clean();
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}
if ( ! function_exists( 'wp_dp_import_plugin_options_handle' ) ) {
add_action( 'wp_dp_import_plugin_options', 'wp_dp_import_plugin_options_handle' );
	function wp_dp_import_plugin_options_handle( $obj ) {
		if ( function_exists( 'wp_dp_demo_plugin_data' ) ) {
			wp_dp_demo_plugin_data( $obj->plugins_data_path, $obj );
			$obj->action_return = true;
		} else {
			$obj->action_return = false;
		}
	}
}