<?php
/**
 * Directorist user class.
 *
 * User registration and access management functionalities.
 *
 * @package     Directorist
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'ATBDP_User' ) ) :
	/**
	 * User class.
	 */
	class ATBDP_User {

		public function __construct() {
			add_action( 'wp_loaded', array( $this, 'handle_user_registration' ) );
			//add_action('init', array($this, 'activate_user'));
			add_filter( 'pre_get_posts', array( $this,'restrict_listing_to_the_author' ) );
			// allow contributor upload images for now. @todo; later it will be better to add custom rules and capability
			add_action( 'plugins_loaded', array( $this, 'user_functions_ready_hook' ) );// before we add custom image uploading, lets use WordPress default image uploading by letting subscriber and contributor upload imaging capability

			add_filter( 'manage_users_columns', array( $this,'manage_users_columns' ), 10, 1 );
			add_filter( 'manage_users_custom_column', array( $this,'manage_users_custom_column' ), 10, 3 );

			add_action( 'template_redirect', [ $this, 'registration_redirection' ] );

		}

		public function registration_redirection() {
			
			$registration_page = get_directorist_option( 'custom_registration' );
			if( ! get_directorist_option( 'new_user_registration', true ) && $registration_page && is_page( $registration_page ) ) {
				wp_redirect( home_url( '/' ) );
				exit;
			}
		}

		/**
		 * Display user_type custom column data.
		 *
		 * @param string $column_value
		 * @param string $column_name
		 * @param int $user_id
		 *
		 * @return string
		 */
		public function manage_users_custom_column( $column_value, $column_name, $user_id ) {
			if ( $column_name !== 'user_type' ) {
				return $column_value;
			}

			$user_type = (string) get_user_meta( $user_id, '_user_type', true );

			if ( 'author' === $user_type ) {
				$column_value = esc_html__( 'Author', 'directorist' );
			} elseif ( 'general' === $user_type ) {
				$column_value = esc_html__( 'User', 'directorist' );
			} elseif ( 'become_author' === $user_type ) {
				$author_pending = wp_kses_post( "<p>Author <span style='color:red;'>( Pending )</span></p>" );
				$approve        = wp_kses_post( "<a href='' id='atbdp-user-type-approve' style='color: #388E3C' data-userId={$user_id} data-nonce=". wp_create_nonce( 'atbdp_user_type_approve' ) ."><span>Approve </span></a> | " );
				$deny           = wp_kses_post( "<a href='' id='atbdp-user-type-deny' style='color: red' data-userId={$user_id} data-nonce=". wp_create_nonce( 'atbdp_user_type_deny' ) ."><span>Deny</span></a>" );
				$column_value   = wp_kses_post( "<div class='atbdp-user-type' id='user-type-". $user_id ."'>" .$author_pending . $approve . $deny . "</div>" );
			}

			return $column_value;
		}

		/**
		 * Add user_type custom column in users management table.
		 *
		 * @param array $column
		 *
		 * @return array
		 */
		function manage_users_columns( $columns ) {
			$columns['user_type'] = esc_html__( 'User Type', 'directorist' );
			return $columns;
		}

		public function user_functions_ready_hook() {
			//Allow Contributors/Subscriber/Customer to Add Media
			$roles = (array) wp_get_current_user()->roles;

			if ( ( in_array( 'contributor', $roles ) ||
				in_array( 'subscriber', $roles ) ||
				in_array( 'customer', $roles ) ) &&
				! current_user_can( 'upload_files' ) ) {
				add_action( 'init', array( $this, 'allow_contributor_uploads' ) );
			}
		}

		/**
		 * Add upload_files capability to certain roles.
		 *
		 * @return void
		 */
		public function allow_contributor_uploads() {
			$roles = (array) wp_get_current_user()->roles;

			// contributor
			if ( in_array( 'contributor', $roles ) ){
				$contributor = get_role( 'contributor' );
				$contributor->add_cap( 'upload_files' );
			}

			// subscriber
			if ( in_array( 'subscriber', $roles ) ){
				$subscriber = get_role( 'subscriber' );
				$subscriber->add_cap( 'upload_files' );
			}

			// customer
			if ( in_array( 'customer', $roles ) ){
				$customer = get_role( 'customer' );
				$customer->add_cap( 'upload_files' );
			}
		}

		public function activate_user() {
			$user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
			if ( $user_id ) {
				// get user meta activation hash field
				$code = get_user_meta( $user_id, 'has_to_be_activated', true );
				$key = filter_input( INPUT_GET, 'key' );
				if ( $code == $key ) {
					delete_user_meta( $user_id, 'has_to_be_activated' );
					wp_safe_redirect(ATBDP_Permalink::get_login_page_link());
				}
			}
		}

		public function registration_form( ) {
			ATBDP()->load_template('front-end/user-registration-form');
		}

		/**
		 * It registers a user. It is a private function, All the vars this function uses will be passed into it after proper validation and sanitization
		 * @param $username
		 * @param $password
		 * @param $email
		 * @param $website
		 * @param $first_name
		 * @param $last_name
		 * @param $bio
		 * @return bool|int|WP_Error
		 */
		private function complete_registration($username, $password, $email, $website, $first_name, $last_name, $bio) {
			global $reg_errors, $username, $password, $email, $website, $first_name, $last_name,  $bio;
			$reg_errors = new WP_Error;
			if ( 1 > count( $reg_errors->get_error_messages() ) ) {
				$userdata = array(
					'user_login'  => $username,
					'user_email'  => $email,
					'user_pass'   => $password,
					'user_url'    => $website,
					'first_name'  => $first_name,
					'last_name'   => $last_name,
					'description' => $bio,
					'role'        => 'subscriber', // @since 7.0.6.3
				);

				return wp_insert_user( $userdata ); // return inserted user id or a WP_Error
			}

			return false;
		}

		public function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $bio, $user_type, $privacy_policy, $t_c_check )  {
			global $reg_errors;
			$require_website             = get_directorist_option('require_website_reg',0);
			$display_website             = get_directorist_option('display_website_reg',1);
			$display_fname               = get_directorist_option('display_fname_reg',1);
			$require_fname               = get_directorist_option('require_fname_reg',0);
			$display_lname               = get_directorist_option('display_lname_reg',1);
			$require_lname               = get_directorist_option('require_lname_reg',0);
			$display_user_type           = get_directorist_option('display_user_type',0);
			$display_bio                 = get_directorist_option('display_bio_reg',1);
			$require_bio                 = get_directorist_option('require_bio_reg',0);
			$display_password            = get_directorist_option('display_password_reg',1);
			$require_password            = get_directorist_option('require_password_reg',0);
			$registration_privacy        = get_directorist_option('registration_privacy',1);
			$terms_condition             = get_directorist_option('regi_terms_condition',1);
			//password validation
			if(!empty($require_password) && !empty($display_password) && empty($password)){
				$password_validation = 'yes';
			}
			//website validation
			if(!empty($require_website) && !empty($display_website) && empty($website)){
				$website_validation = 'yes';
			}
			//first name validation
			if(!empty($require_fname) && !empty($display_fname) && empty($first_name)){
				$fname_validation = 'yes';
			}
			//last name validation
			if(!empty($require_lname) && !empty($display_lname) && empty($last_name)){
				$lname_validation = 'yes';
			}
			//user type validation
			if( ! empty( $display_user_type ) && empty( $user_type ) ) {
				$user_type_validation = 'yes';
			}
			//bio validation
			if(!empty($require_bio) && !empty($display_bio) && empty($bio)){
				$bio_validation = 'yes';
			}
			//privacy validation
			if(!empty($registration_privacy) && empty($privacy_policy)){
				$privacy_validation = 'yes';
			}
			//terms & conditions validation
			if(!empty($terms_condition) && empty($t_c_check)){
				$t_c_validation = 'yes';
			}
			$reg_errors = new WP_Error;
			if ( empty( $username ) || !empty( $password_validation ) || empty( $email ) || !empty($website_validation) || !empty($fname_validation) || !empty($lname_validation) || !empty($bio_validation) || !empty($privacy_validation) || !empty($t_c_validation) || ! empty( $user_type_validation ) ) {
				$reg_errors->add('field', esc_html__('Required form field is missing. Please fill all required fields.', 'directorist'));
			}

			if (!empty( $username ) && 4 > strlen( $username ) ) {
				$reg_errors->add( 'username_length', esc_html__('Username too short. At least 4 characters is required', 'directorist') );
			}

			if ( username_exists( $username ) )
				$reg_errors->add('user_name', esc_html__('Sorry, that username already exists!', 'directorist'));

			if ( preg_match('/\s/',$username))
				$reg_errors->add('space_in_username', esc_html__('Sorry, space is not allowed in username!', 'directorist'));

			if ( ! validate_username( $username ) ) {
				$reg_errors->add( 'username_invalid', esc_html__('Sorry, the username you entered is not valid', 'directorist') );
			}

			if ( ! empty( $password ) && 5 > strlen( $password ) ) {
				$reg_errors->add( 'password', esc_html__('Password length must be greater than 5', 'directorist') );
			}

			if ( empty( $privacy_policy ) ) {
				$reg_errors->add( 'empty_privacy', esc_html__('Privacy field is required', 'directorist') );
			}
			if ( empty( $t_c_check ) ) {
				$reg_errors->add( 'empty_terms', esc_html__('Terms and Condition field is required', 'directorist') );
			}

			if ( empty( $user_type_validation ) ) {
				$reg_errors->add( 'empty_terms', esc_html__('Terms and Condition field is required', 'directorist') );
			}

			if ( !is_email( $email ) ) {
				$reg_errors->add( 'email_invalid', esc_html__('Email is not valid', 'directorist') );
			}
			if ( email_exists( $email ) ) {
				$reg_errors->add( 'email', esc_html__('Email Already in use', 'directorist') );
			}
			if ( ! empty( $first_name ) ) {
				if (!is_string($first_name)) {
					$reg_errors->add('First Name', esc_html__('First Name must be letters or combination of letters and number', 'directorist'));
				}
			}
			if ( ! empty( $last_name ) ) {
				if (!is_string($last_name)) {
					$reg_errors->add('Last Name', esc_html__('Last Name must be letters or combination of letters and number', 'directorist'));
				}
			}

			if ( ! empty( $website ) ) {
				if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
					$reg_errors->add( 'website', esc_html__('Website is not a valid URL', 'directorist') );
				}
			}
			// if we have errors then returns a string of error message.
			$e = $reg_errors->get_error_messages(); // save the errors in a placeholder var as we can not use function inside empty() until php 5.5.
			if ( is_wp_error( $reg_errors ) && !empty($e) ) {
				//@todo; errors should be kept in the session using a helper function so that we can get all the errors after redirection
				$err_msg = '';
				foreach ( $reg_errors->get_error_messages() as $error ) {
					$err_msg .= '<div>';
					$err_msg .= '<strong>ERROR</strong>:';
					$err_msg .= esc_html( $error ) . '<br/>';
					$err_msg .= '</div>';

				}
				return apply_filters('atbdp_registration_error', $err_msg);

			}
			return 'passed';

		}

		public function handle_user_registration() {
			$new_user_registration = get_directorist_option( 'new_user_registration', true );
			if ( ! directorist_verify_nonce() || ! isset( $_POST['atbdp_user_submit'] ) || ! $new_user_registration ) {
				return;
			}

			// if the form is submitted then save the form
			$require_website      = get_directorist_option( 'require_website_reg', 0 );
			$display_website      = get_directorist_option( 'display_website_reg', 1 );
			$display_fname        = get_directorist_option( 'display_fname_reg', 1 );
			$require_fname        = get_directorist_option( 'require_fname_reg', 0 );
			$display_lname        = get_directorist_option( 'display_lname_reg', 1 );
			$require_lname        = get_directorist_option( 'require_lname_reg', 0 );
			$display_password     = get_directorist_option( 'display_password_reg', 1 );
			$require_password     = get_directorist_option( 'require_password_reg', 0 );
			$display_user_type    = get_directorist_option(  'display_user_type', 0   );
			$display_bio          = get_directorist_option( 'display_bio_reg', 1 );
			$require_bio          = get_directorist_option( 'require_bio_reg', 0 );
			$registration_privacy = get_directorist_option( 'registration_privacy', 1 );
			$terms_condition      = get_directorist_option( 'regi_terms_condition', 1 );

			/**
			 * It fires before processing a submitted registration from the front end
			 * @param array $_POST the array containing the submitted listing data.
			 * @since 4.4.0
			 * */
			do_action( 'atbdp_before_processing_submitted_user_registration', $_POST ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

			$username       = ! empty( $_POST['username'] ) ? directorist_clean( wp_unslash( $_POST['username'] ) ) : '';
			$password       = ! empty( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$email          = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$website        = ! empty( $_POST['website'] ) ? directorist_clean( wp_unslash( $_POST['website'] ) ) : '';
			$first_name     = ! empty( $_POST['fname'] ) ? directorist_clean( wp_unslash( $_POST['fname'] ) ) : '';
			$last_name      = ! empty( $_POST['lname'] ) ? directorist_clean( wp_unslash( $_POST['lname'] ) ) : '';
			$user_type      = ! empty( $_POST['user_type'] ) ? directorist_clean( wp_unslash( $_POST['user_type'] ) ) : '';
			$bio            = ! empty( $_POST['bio'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bio'] ) ) : '';
			$privacy_policy = ! empty( $_POST['privacy_policy'] ) ? directorist_clean( wp_unslash( $_POST['privacy_policy'] ) ) : '';
			$t_c_check      = ! empty( $_POST['t_c_check'] ) ? directorist_clean( wp_unslash( $_POST['t_c_check'] ) ) : '';

			//password validation
			if ( ! empty( $require_password ) && ! empty( $display_password ) && empty( $password ) ) {
				$password_validation = 'yes';
			}

			//website validation
			if ( ! empty( $require_website ) && ! empty( $display_website ) && empty( $website ) ) {
				$website_validation = 'yes';
			}

			//first name validation
			if ( ! empty( $require_fname ) && ! empty( $display_fname ) && empty( $first_name ) ) {
				$fname_validation = 'yes';
			}

			//last name validation
			if ( ! empty( $require_lname ) && !empty( $display_lname ) && empty( $last_name ) ) {
				$lname_validation = 'yes';
			}

			//bio validation
			if(!empty($require_bio) && !empty($display_bio) && empty($bio)){
				$bio_validation = 'yes';
			}
			if( ! empty( $display_user_type ) && empty( $user_type) ) {
				$user_type_validation = 'yes';
			}
			//privacy validation
			if(!empty($registration_privacy) && empty($privacy_policy)){
				$privacy_validation = 'yes';
			}
			//terms & conditions validation
			if(!empty($terms_condition) && empty($t_c_check)){
				$t_c_validation = 'yes';
			}
			// validate all the inputs
			$validation = $this->registration_validation( $username, $password, $email, $website, $first_name, $last_name, $bio, $user_type, $privacy_policy, $t_c_check );
			if ('passed' !== $validation){
				if (empty( $username ) || !empty( $password_validation ) || empty( $email ) || !empty($website_validation) || !empty($fname_validation) || !empty($lname_validation) || !empty($bio_validation)|| !empty($privacy_validation)|| !empty($t_c_validation)){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 1)));
					exit();
				}elseif(email_exists($email)){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 2)));
					exit();
				}elseif(!empty( $username ) && 4 > strlen( $username ) ){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 3)));
					exit();
				}elseif(!empty( $username ) && preg_match('/\s/',$username) ){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 7)));
					exit();
				}elseif( username_exists( $username )){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 4)));
					exit();
				}elseif(! empty( $password ) && 5 > strlen( $password )){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 5)));
					exit();
				}elseif(!is_email( $email )){
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 6)));
					exit();
				} elseif( ! empty( $user_type_validation ) ) {
					wp_safe_redirect(ATBDP_Permalink::get_registration_page_link(array('errors' => 8)));
					exit();
				}
			}

			// sanitize user form input
			global $username, $password, $email, $website, $first_name, $last_name, $bio;
			$username   =   directorist_clean( wp_unslash( $_POST['username'] ) );
			if (empty($display_password)){
				$password   =   wp_generate_password( 12, false );
			}elseif (empty($_POST['password'])){
				$password   =   wp_generate_password( 12, false );
			}else{
				$password   =  $_POST['password']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			}
			$email            =   !empty($_POST['email']) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$website          =   !empty($_POST['website']) ? directorist_clean( $_POST['website'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$first_name       =   !empty($_POST['fname']) ? directorist_clean( wp_unslash( $_POST['fname'] ) ) : '';
			$last_name        =   !empty($_POST['lname']) ? directorist_clean( wp_unslash( $_POST['lname'] ) ) : '';
			$user_type        =   !empty($_POST['user_type']) ? directorist_clean( wp_unslash( $_POST['user_type'] ) ) : '';
			$bio              =   !empty($_POST['bio']) ? sanitize_textarea_field( wp_unslash( $_POST['bio'] ) ) : '';
			$previous_page    =   !empty($_POST['previous_page']) ? directorist_clean( $_POST['previous_page'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			// call @function complete_registration to create the user
			// only when no WP_error is found
			$user_id = $this->complete_registration($username, $password, $email, $website, $first_name, $last_name, $bio);
			if ($user_id && !is_wp_error( $user_id )) {
				$redirection_after_reg = get_directorist_option( 'redirection_after_reg');
				$auto_login = get_directorist_option( 'auto_login' );
				/*
				* @since 6.3.0
				* If fires after completed user registration
				*/
				do_action('atbdp_user_registration_completed', $user_id);
				update_user_meta($user_id, '_atbdp_generated_password', $password);
				update_user_meta($user_id, '_atbdp_privacy', $privacy_policy);
				update_user_meta($user_id, '_user_type', $user_type);
				update_user_meta($user_id, '_atbdp_terms_and_conditions', $t_c_check);
				// user has been created successfully, now work on activation process
				wp_new_user_notification($user_id, null, 'admin'); // send activation to the admin
				ATBDP()->email->custom_wp_new_user_notification_email($user_id);
				if( ! empty( $auto_login ) ) {
					wp_set_current_user( $user_id, $email );
					wp_set_auth_cookie( $user_id );
				}
			// wp_get_referer();
				if( ! empty( $redirection_after_reg ) ) {
					wp_safe_redirect( esc_url_raw( ATBDP_Permalink::get_reg_redirection_page_link( $previous_page ) ) );
				} else {
					wp_safe_redirect( esc_url_raw( ATBDP_Permalink::get_registration_page_link( array( 'registration_status' => true ) ) ) );
				}
				exit();
			} else {
				wp_safe_redirect( esc_url_raw( ATBDP_Permalink::get_registration_page_link(array('errors' => true ) ) ) );
				exit();
			}
		}

		public function user_dashboard() {
			// load user dashboard on the front end
			ATBDP()->load_template('front-end/user-dashboard');
		}

		/**
		 * It returns all the listing of the current user
		 * @return WP_Query   it returns an object of the WP_Query class with the items/listings on success and false on failure.

		*/
		public function current_user_listings()
		{
			return ATBDP()->listing->db->get_listing_by_user(); // it returns all the listing of the current user.
		}

		/**
		 * It returns all the favraites listing of the current user
		 * @return WP_Query   it returns an object of the WP_Query class with the items/listings on success and false on failure.

		*/
		public function current_user_fav_listings()
		{
			return ATBDP()->listing->db->get_favourites(); // it returns all the listing of the current user.
		}

		/**
		 * It updates the user profile and meta data
		 * @param array $data the user data to update.
		 * @return bool It returns true on success and false on failure
		 */
		public function update_profile($data)
		{
			$userdata = array();
			// we need to sanitize the data and then save it.
			$ID = !empty($data['ID']) ? absint($data['ID']) : get_current_user_id();
			$userdata['ID'] = $ID;
			$userdata['display_name'] = !empty($data['full_name']) ? sanitize_text_field(trim($data['full_name'])) : '';
			$userdata['user_email'] = !empty($data['user_email']) ? sanitize_email($data['user_email'] ): '';
			$userdata['user_url'] = !empty($data['website']) ? sanitize_url(trim($data['website'] )): '';
			$phone = !empty($data['phone']) ? sanitize_text_field(trim($data['phone'] )): '';
			$first_name = !empty($data['first_name']) ? sanitize_text_field(trim($data['first_name'])) : '';
			$last_name = !empty($data['last_name']) ? sanitize_text_field(trim($data['last_name'] )): '';
			$address = !empty($data['address']) ? sanitize_text_field(trim($data['address'] )): '';
			$facebook = !empty($data['facebook']) ? sanitize_url(trim($data['facebook'] )): '';
			$twitter = !empty($data['twitter']) ? sanitize_url(trim($data['twitter'] )): '';
			$linkedIn = !empty($data['linkedIn']) ? sanitize_url(trim($data['linkedIn'] )): '';
			$youtube = !empty($data['youtube']) ? sanitize_url(trim($data['youtube'] )): '';
			$bio = !empty($data['bio']) ? sanitize_textarea_field(trim($data['bio'] )): '';
			$new_pass = !empty($data['new_pass']) ? $data['new_pass'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$confirm_pass = !empty($data['confirm_pass']) ? $data['confirm_pass']: ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			
			// now lets save the data to the db without password
			$uid = wp_update_user($userdata);
			update_user_meta( $ID, 'address', $address );
			update_user_meta( $ID, 'atbdp_facebook', $facebook );
			update_user_meta( $ID, 'atbdp_twitter', $twitter );
			update_user_meta( $ID, 'atbdp_linkedin', $linkedIn );
			update_user_meta( $ID, 'atbdp_youtube', $youtube );
			update_user_meta( $ID, 'description', $bio );
			update_user_meta( $ID, 'first_name', $first_name );
			update_user_meta( $ID, 'last_name', $last_name );
			update_user_meta( $ID, 'atbdp_phone', $phone );

			if (!empty($new_pass || $confirm_pass)){
				// password will be updated here
				if ( ( $new_pass == $confirm_pass ) && ( strlen( $confirm_pass) > 5 ) ){
					wp_set_password($new_pass, $ID); // set the password to the database
				}else{
					$pass_match = esc_html__('Password should be matched and more than five character', 'directorist');
					wp_send_json_error($pass_match, 'directorist');

				}
			}
			if (!is_wp_error($uid)){
				$congz_txt = esc_html__('Congratulations! Your profile updated successfully', 'directorist');
				wp_send_json_success( $congz_txt, 'directorist');
				return true;
			}else{
				$ops_text = esc_html__('Oops! Something wrong.', 'directorist');
				wp_send_json_error($ops_text, 'directorist');
			}


			return false; // failed to save data, so return false

		}

		/**
		 * It prevent the user from showing other posts/listings on dashboard if he is not an admin
		 * @param Object|WP_Query $query
		 * @return Object|WP_Query
		 */
		public function restrict_listing_to_the_author($query)
		{
			global $pagenow, $post_type;


			if( ATBDP_POST_TYPE == $post_type && 'edit.php' == $pagenow && $query->is_admin && !current_user_can( 'edit_others_'.ATBDP_POST_TYPE.'s' ) ){
				global $user_ID;
				$query->set('author', $user_ID );
			}


			return $query;
		}
    } // ends ATBDP_User
endif;
