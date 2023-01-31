<?php

/**
 * File Type: Member Permissions
 */
if ( ! class_exists( 'Wp_dp_Member_Permissions' ) ) {

	class Wp_dp_Member_Permissions {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			global $buyer_permissions;
			add_action( 'init', array( $this, 'member_permissions' ) );
			add_filter( 'member_permissions', array( $this, 'member_permissions' ), 10 );
			add_filter( 'check_permissions', array( $this, 'check_permissions' ), 10, 2 );

			$buyer_permissions = array(
				'company_profile' => 'on',
				'enquiries' => 'on',
				'alerts' => 'on',
				'favourites' => 'on',
				'arrange_viewings' => 'on',
			);
		}

		public function member_permissions() {
			global $permissions_array;
			$permissions = array();
			$permissions['company_profile'] = wp_dp_plugin_text_srt( 'company_profile_manage' );
			$permissions['listings'] = wp_dp_plugin_text_srt( 'listings_manage' );
			$permissions['orders'] = wp_dp_plugin_text_srt( 'orders_manage' );
			$permissions['enquiries'] = wp_dp_plugin_text_srt( 'enquiries_manage' );
			$permissions['arrange_viewings'] = wp_dp_plugin_text_srt( 'arrange_viewings_manage' );
			$permissions['hidden_listings'] = wp_dp_plugin_text_srt( 'hidden_listings_manage' );
			$permissions['listing_notes'] = wp_dp_plugin_text_srt( 'listing_notes_manage' );
			$permissions['packages'] = wp_dp_plugin_text_srt( 'packages_manage' );
			$permissions = apply_filters( 'wp_dp_member_permissions', $permissions );
			$permissions_array = $permissions;
			return $permissions;
		}

		static function check_permissions( $module = 'profile', $user_ID = '' ) {

			if ( ! isset( $user_ID ) || $user_ID == '' ) {
				$user_ID = get_current_user_id();
			}

			$permissions = get_user_meta( $user_ID, 'wp_dp_permissions', true );
			$user_status = get_user_meta( $user_ID, 'wp_dp_user_type', true );
			$member_id = get_user_meta( $user_ID, 'wp_dp_company', true );
			if ( isset( $user_status ) && $user_status == 'supper-admin' ) {
				return true;
			}

			if ( isset( $permissions[$module] ) && $permissions[$module] == 'on' ) {
				return true;
			}
			return true;
		}

		static function package_buy_permission( $user_id, $package_id ) {
			$current_user_obj = wp_get_current_user( $user_id );
			$current_user_role = $current_user_obj->roles[0];
			$user_role = false;
			$package = false;
			if ( $current_user_role == 'wp_dp_member' ) {
				$user_role = true;
			}

			$package_status = get_post_status( $package_id );
			if ( $package_status == 'publish' ) {
				$package = true;
			}
			if ( $user_role && $package ) {
				return true;
			} else {
				return false;
			}
		}

	}

	global $permissions;
	$permissions = new Wp_dp_Member_Permissions();
}