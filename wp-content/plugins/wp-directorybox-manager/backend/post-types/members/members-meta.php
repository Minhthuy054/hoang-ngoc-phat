<?php
/**
 * @Add Meta Box For Members Post
 * @return
 *
 */
if ( ! class_exists( 'Wp_dp_Members_Meta' ) ) {

	class Wp_dp_Members_Meta {

		var $html_data = '';
		var $post_id = '';

		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'wp_dp_meta_members_add' ) );
			add_action( 'add_meta_boxes', array( $this, 'wp_dp_meta_members_add' ) );
			add_action( 'wp_ajax_wp_dp_removed_favourite_backend', array( $this, 'wp_dp_removed_favourite' ), 11 );
			// Handle AJAX to delete a listing alert.
			add_action( 'wp_ajax_wp_dp_remove_listing_alert', array( $this, 'remove_listing_alert' ) );
			add_action( 'wp_ajax_nopriv_wp_dp_remove_listing_alert', array( $this, 'remove_listing_alert' ) );
			add_action( 'wp_ajax_wp_dp_remove_team_member', array( $this, 'wp_dp_remove_team_member' ), 11 );
			add_action( 'save_post', array( $this, 'wp_dp_member_save_opening_hours' ), 11 );
			add_action( 'save_post', array( $this, 'wp_dp_member_save_off_days' ), 11 );
			add_action( 'save_post', array( $this, 'wp_dp_member_save_member_user_type' ), 11 );
		}

		public function wp_dp_meta_members_add() {
			add_meta_box( 'wp_dp_meta_members', wp_dp_plugin_text_srt( 'wp_dp_company_details' ), array( $this, 'wp_dp_meta_members' ), 'members', 'normal', 'high' );
		}

		/**
		 * Start Function How to Attach mata box with members post type
		 */
		function wp_dp_meta_members( $post ) {
			global $post, $wp_dp_post_type_members, $post_id;
			$post_id = $post->ID;
			$this->post_id = $post_id;
			?>
			<div class="page-wrap page-opts left">
				<div class="option-sec" style="margin-bottom:0;">
					<div class="opt-conts" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>"> 
						<div class="elementhidden">
							<nav class="admin-navigtion">
								<ul id="cs-options-tab">
									<li><a href="javascript:void(0);" name="#tab-general" href="javascript:;"><i class="icon-settings"></i><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_account_settings' ) ?> </a></li>
									<li><a href="javascript:void(0);" name="#tab-user-listings" href="javascript:;"><i class="icon-th-list"></i><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_user_listings' ) ?> </a></li> 
									<li><a href="javascript:void(0);" name="#tab-packages" href="javascript:;"><i class="icon-box"></i> <?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_packages' ) ?></a></li>
									<?php do_action( 'wp_dp_member_admin_tab_menu' ); ?>
									<li><a href="javascript:void(0);" data-url="<?php echo esc_url( admin_url( 'edit.php?post_type=listing_enquiries&member_id=' . $post_id ) ); ?>" class="cs-options-tab-ext"><i class="icon-new-message"></i> <?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_message' ) ?></a></li>
									<li><a href="javascript:void(0);" data-url="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_dp_reviews&member_id=' . $post_id ) ); ?>" class="cs-options-tab-ext"><i class="icon-star-full"></i> <?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_reviews' ) ?></a></li>
								</ul>
							</nav>
							<div id="tabbed-content" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
								<div id="tab-general">
									<?php $this->wp_dp_members_options(); ?>
									<?php echo ($this->member_off_days()); ?>
									<?php echo ($this->member_opening_hours()); ?>

								</div>
								<div id="tab-user-listings">
									<?php $this->wp_dp_user_listing_options(); ?>
								</div> 
								<div id="tab-packages">
									<?php $this->wp_dp_tab_packages(); ?>
								</div>

								<?php do_action( 'wp_dp_member_admin_tab_content' ); ?>
							</div>
							<?php $wp_dp_post_type_members->wp_dp_submit_meta_box( 'members', $args = array() ); ?>

						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php
		}

		/*
		 * Openings Hours Start
		 */

		function member_opening_hours() {
			global $member_add_counter, $wp_dp_html_fields, $wp_dp_form_fields;
			$member_add_counter = rand( 10000000, 99999999 );
			$html = '';
			$time_list = $this->member_time_list();
			$week_days = $this->member_week_days();
			$time_from_html = '';
			$time_to_html = '';
			$opening_time = '';
			$closing_time = '';
			global $post;
			$post_id = $post->ID;
			$get_opening_hours = get_post_meta( $post_id, 'wp_dp_opening_hour', true );
			if ( $get_opening_hours == '' ) {
				if ( is_array( $time_list ) && sizeof( $time_list ) > 0 ) {
					foreach ( $time_list as $time_key => $time_val ) {
						$time_from_html .= '<option value="' . $time_key . '">' . date_i18n( 'g:i a', strtotime( $time_val ) ) . '</option>' . "\n";
						$time_to_html .= '<option value="' . $time_key . '">' . date_i18n( 'g:i a', strtotime( $time_val ) ) . '</option>' . "\n";

						$time_from[$time_key] = date_i18n( 'g:i a', strtotime( $time_val ) );
						$time_to[$time_key] = date_i18n( 'g:i a', strtotime( $time_val ) );
					}
				}
			}

			$days_html = '';
			if ( is_array( $week_days ) && sizeof( $week_days ) > 0 ) {
				foreach ( $week_days as $day_key => $week_day ) {
					$day_status = isset( $get_opening_hours[$day_key]['day_status'] ) ? $get_opening_hours[$day_key]['day_status'] : '';
					if ( isset( $get_opening_hours ) && is_array( $get_opening_hours ) && sizeof( $get_opening_hours ) > 0 ) {
						$opening_time = isset( $get_opening_hours[$day_key]['opening_time'] ) ? $get_opening_hours[$day_key]['opening_time'] : '';
						$closing_time = isset( $get_opening_hours[$day_key]['closing_time'] ) ? $get_opening_hours[$day_key]['closing_time'] : '';
						if ( is_array( $time_list ) && sizeof( $time_list ) > 0 ) {
							$time_from_html = '';
							$time_to_html = '';
							$time_from = $time_to = array();
							foreach ( $time_list as $time_key => $time_val ) {
								$time_from_html .= '<option value="' . $time_key . '"' . ($opening_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n( 'g:i a', strtotime( $time_val ) ) . '</option>' . "\n";
								$time_to_html .= '<option value="' . $time_key . '"' . ($closing_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n( 'g:i a', strtotime( $time_val ) ) . '</option>' . "\n";

								$time_from[$time_key] = date_i18n( 'g:i a', strtotime( $time_val ) );
								$time_to[$time_key] = date_i18n( 'g:i a', strtotime( $time_val ) );
							}
						}
					}
					$days_html .= '
					<li>
					    <div id="open-close-con-' . $day_key . '-' . $member_add_counter . '" class="open-close-time' . (isset( $day_status ) && $day_status == 'on' ? ' opening-time' : '') . '">
						<div class="day-sec">
						    <span>' . $week_day . '</span>
						</div>
					    <div class="time-sec">';
					$wp_dp_opt_array = array(
						'std' => $opening_time,
						'cust_name' => 'wp_dp_opening_hour[' . $day_key . '][opening_time]',
						'classes' => 'chosen-select',
						'options' => $time_from,
						'return' => true,
					);
					$days_html .= $wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );

					$days_html .= ' <span class="option-label">' . wp_dp_plugin_text_srt( 'wp_dp_member_to' ) . '</span> ';
					$wp_dp_opt_array = array(
						'std' => $closing_time,
						'cust_name' => 'wp_dp_opening_hour[' . $day_key . '][closing_time]',
						'classes' => 'chosen-select',
						'options' => $time_to,
						'return' => true,
					);
					$days_html .= $wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );
					$days_html .= '<a id="wp-dp-dev-close-time-' . $day_key . '-' . $member_add_counter . '" href="javascript:void(0);" data-id="' . $member_add_counter . '" data-day="' . $day_key . '" title="' . wp_dp_plugin_text_srt( 'wp_dp_member_close' ) . '"><i class="icon-close2"></i></a>
					    </div>
					    <div class="close-time">
						<a id="wp-dp-dev-open-time-' . $day_key . '-' . $member_add_counter . '" href="javascript:void(0);" data-id="' . $member_add_counter . '" data-day="' . $day_key . '">' . wp_dp_plugin_text_srt( 'wp_dp_member_close' ) . ' <span>(' . wp_dp_plugin_text_srt( 'wp_dp_member_add_opening_hours' ) . ')</span></a>';
					$wp_dp_opt_array = array(
						'std' => (isset( $day_status ) && $day_status == 'on' ? 'on' : ''),
						'cust_id' => 'wp-dp-dev-open-day-' . $day_key . '-' . $member_add_counter,
						'cust_name' => 'wp_dp_opening_hour[' . $day_key . '][day_status]',
						'cust_type' => 'hidden',
						'classes' => '',
						'return' => true,
					);
					$days_html .= $wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
					$days_html .= '</div>
					    </div>
					</li>';
				}
			}
			$html .= $wp_dp_html_fields->wp_dp_heading_render(
					array(
						'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_opening_hours' ),
						'id' => 'opening_hours',
						'classes' => '',
						'std' => '',
						'description' => '',
						'hint' => '',
						'echo' => false
					)
			);
			$html .= '
				<div class="form-elements">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<label>' . wp_dp_plugin_text_srt( 'wp_dp_member_opening_hours' ) . '</label>
					</div>
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<div class="time-list">
							<ul>
								' . $days_html . '
							</ul>
						</div>
					</div>
				</div>';

			return $html;
		}

		public function member_time_list() {
			$lapse = 15;
			$hours = array();
			$date = date( "Y/m/d 12:00" );
			$time = strtotime( '12:00 am' );
			$start_time = strtotime( $date . ' am' );
			$endtime = strtotime( date( "Y/m/d h:i a", strtotime( '1440 minutes', $start_time ) ) );
			while ( $start_time < $endtime ) {
				$time = date( "h:i a", strtotime( '+' . $lapse . ' minutes', $time ) );
				$hours[$time] = $time;
				$time = strtotime( $time );
				$start_time = strtotime( date( "Y/m/d h:i a", strtotime( '+' . $lapse . ' minutes', $start_time ) ) );
			}
			return $hours;
		}

		public function member_week_days() {
			$week_days = array(
				'monday' => wp_dp_plugin_text_srt( 'wp_dp_member_monday' ),
				'tuesday' => wp_dp_plugin_text_srt( 'wp_dp_member_tuesday' ),
				'wednesday' => wp_dp_plugin_text_srt( 'wp_dp_member_wednesday' ),
				'thursday' => wp_dp_plugin_text_srt( 'wp_dp_member_thursday' ),
				'friday' => wp_dp_plugin_text_srt( 'wp_dp_member_friday' ),
				'saturday' => wp_dp_plugin_text_srt( 'wp_dp_member_saturday' ),
				'sunday' => wp_dp_plugin_text_srt( 'wp_dp_member_sunday' )
			);
			return $week_days;
		}

		public function wp_dp_member_save_opening_hours( $member_id = '' ) {
			$wp_dp_opening_hours = wp_dp_get_input( 'wp_dp_opening_hour', '', 'ARRAY' );
			update_post_meta( $member_id, 'wp_dp_opening_hour', $wp_dp_opening_hours );
		}

		/*
		 * Opening hours End
		 */



		/*
		 * Off Days
		 */

		function member_off_days() {
			global $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;

			$html = $wp_dp_html_fields->wp_dp_heading_render(
					array(
						'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_off_days' ),
						'id' => 'off_days',
						'classes' => '',
						'std' => '',
						'description' => '',
						'hint' => '',
						'echo' => false
					)
			);
			$date_js = '';
			if ( isset( $wp_dp_calendar ) && ! empty( $wp_dp_calendar ) ) {
				foreach ( $wp_dp_calendar as $calender_date ) {
					$calender_date = strtotime( $calender_date );
					$dateVal = date( "Y, m, d", strtotime( '-1 month', $calender_date ) );
					$date_js .= '{
						startDate: new Date(' . $dateVal . '),
						endDate: new Date(' . $dateVal . ')
					},';
				}
			}
			$html .= $this->member_book_days_off();
			return $html;
		}

		public function member_book_days_off() {
			global $post;
			$member_add_counter = rand( 10000000, 99999999 );
			$html = '';
			$off_days_list = '';
			$get_member_off_days = get_post_meta( $post->ID, 'wp_dp_calendar', true );

			if ( is_array( $get_member_off_days ) && sizeof( $get_member_off_days ) ) {
				foreach ( $get_member_off_days as $get_off_day ) {
					$off_days_list .= $this->append_to_book_days_off( $get_off_day );
				}
			} else {
				$off_days_list = '<li id="no-book-day-' . $member_add_counter . '" class="no-result-msg">' . wp_dp_plugin_text_srt( 'wp_dp_member_days_added' ) . '</li>';
			}

			$html .= '
			<div class="form-elements">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . wp_dp_plugin_text_srt( 'wp_dp_member_off_days' ) . '</label>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="book-list">
						<ul id="wp-dp-dev-add-off-day-app-' . $member_add_counter . '">
							' . $off_days_list . '
						</ul>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div id="wp-dp-dev-loader-' . absint( $member_add_counter ) . '" class="wp-dp-loader"></div>
					<a class="book-btn" href="javascript:void(0);">' . wp_dp_plugin_text_srt( 'wp_dp_member_off_days' ) . '</a>
					<div id="wp-dp-dev-cal-holder-' . $member_add_counter . '" class="calendar-holder">
						<div data-id="' . $member_add_counter . '" class="wp-dp-dev-insert-off-days-backend responsive-calendar member-responsive-calendar" data-ajax-url="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '" data-plugin-url="' . esc_url( wp_dp::plugin_url() ) . '">
							<span class="availability">' . wp_dp_plugin_text_srt( 'wp_dp_member_availability' ) . '</span>
							<div class="controls">
								<a data-go="prev"><div class="btn btn-primary"><i class="icon-angle-left"></i></div></a>
								<h4><span data-head-month></span> <span data-head-year></span></h4>
								<a data-go="next"><div class="btn btn-primary"><i class="icon-angle-right"></i></div></a>
							</div>
							<div class="day-headers">
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_sun' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_mon' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_tue' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_wed' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_thu' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_fri' ) . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt( 'wp_dp_member_sat' ) . '</div>
							</div>
							<div class="days wp-dp-dev-calendar-days" data-group="days"></div>
						</div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						if (jQuery(".responsive-calendar").length != "") {
							jQuery(".responsive-calendar").responsiveCalendar({
								time: "' . date( 'Y-m' ) . '",
								monthChangeAnimation: false,
								"' . date( 'Y-m-d' ) . '": {
									number: 5,
									url: ""
								}
							});
						}
					});
				</script>
			</div>';
			return force_balance_tags( $html );
		}

		/**
		 * Appending off days to list via Ajax
		 * @return markup
		 */
		public function append_to_book_days_off( $get_off_day = '' ) {
			global $wp_dp_form_fields;
			if ( $get_off_day != '' ) {
				$book_off_date = $get_off_day;
			} else {
				$day = wp_dp_get_input( 'off_day_day', date( 'd' ), 'STRING' );
				$month = wp_dp_get_input( 'off_day_month', date( 'm' ), 'STRING' );
				$year = wp_dp_get_input( 'off_day_year', date( 'Y' ), 'STRING' );
				$book_off_date = $year . '-' . $month . '-' . $day;
			}

			$formated_off_date = date_i18n( get_option( 'date_format' ), strtotime( $book_off_date ) );

			$rand_numb = rand( 100000000, 999999999 );

			$html = '
			<li id="day-dpove-' . $rand_numb . '">
				<div class="open-close-time opening-time">
					<div class="date-sec">
						<span>' . $formated_off_date . '</span>';
			$wp_dp_opt_array = array(
				'std' => $book_off_date,
				'cust_id' => '',
				'cust_name' => 'wp_dp_listing_off_days[]',
				'cust_type' => 'hidden',
				'classes' => '',
				'return' => true,
			);
			$html .= $wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
			$html .= '</div>
					<div class="time-sec">
						<a id="wp-dp-dev-day-off-dp-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);"><i class="icon-close2"></i></a>
					</div>
				</div>
			</li>';

			if ( $get_off_day != '' ) {
				return force_balance_tags( $html );
			} else {
				echo json_encode( array( 'html' => $html ) );
				die;
			}
		}

		public function wp_dp_member_save_off_days( $member_id = '' ) {
			$wp_dp_off_days = wp_dp_get_input( 'wp_dp_listing_off_days', '', 'ARRAY' );
			update_post_meta( $member_id, 'wp_dp_calendar', $wp_dp_off_days );
		}

		/*
		 * Saving Member User Type
		 * @ it will also add the permissions for the user
		 */

		public function wp_dp_member_save_member_user_type( $member_id = '' ) {
			global $buyer_permissions;
			$args = array(
				'role' => 'wp_dp_member',
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'key' => 'wp_dp_company',
						'value' => $member_id,
						'compare' => '='
					),
				)
			);
			$wp_user_query = new WP_User_Query( $args );
			$users_array = $wp_user_query->get_results();

			if ( ! empty( $users_array ) ) {
				foreach ( $users_array as $user_id ) {
					if ( $user_id != '' ) {
						update_user_meta( $user_id, 'wp_dp_permissions', $buyer_permissions );
					}
				}
			}
		}

		/*
		 * Off Days End
		 */

		/**
		 * Start Function for user members
		 */
		public function wp_dp_user_listing_options() {
			global $post;
			$member_id = $post->ID;
			$args = array(
				'posts_per_page' => "-1",
				'post_type' => 'listings',
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'wp_dp_listing_member',
						'value' => $member_id,
						'compare' => '=',
					),
					array(
						'key' => 'wp_dp_listing_expired',
						'value' => strtotime( date( "d-m-Y" ) ),
						'compare' => '>=',
					),
					array(
						'key' => 'wp_dp_listing_status',
						'value' => 'delete',
						'compare' => '!=',
					),
				),
			);
			$custom_query = new WP_Query( $args );

			$all_listings = $custom_query->posts;
			?>
			<div id="wp-dp-dev-user-listing" class="user-list" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">  
				<div class = "element-title">
					<h4><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_user_listings' ); ?></h4>
				</div> 
				<ul class="panel-group">
					<?php
					if ( isset( $all_listings ) && ! empty( $all_listings ) ) {
						?>
						<li> 
							<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_listings' ); ?></span>
							<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_posted' ); ?></span>
							<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_expires' ); ?></span> </li><?php
						foreach ( $all_listings as $listing_data ) {
							global $post, $wp_dp_plugin_options;
							$post = $listing_data;
							setup_postdata( $listing_data );
							$category = get_post_meta( get_the_ID(), 'wp_dp_listing_category', true );
							$listing_category = isset($category['parent']) ? $category['parent'] : '';
							$category = get_term_by( 'slug', $listing_category , 'listing-category' );
							$listing_post_on = get_post_meta( get_the_ID(), 'wp_dp_listing_posted', true );
							$listing_post_expiry = get_post_meta( get_the_ID(), 'wp_dp_listing_expired', true );
							$listing_status = get_post_meta( get_the_ID(), 'wp_dp_listing_status', true );
							$wp_dp_listing_update_url = get_edit_post_link( get_the_ID() );
							?>
							<li id="user-listing-<?php echo absint( get_the_ID() ); ?>" class="alert" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
								<div class="panel panel-default">
									<a href="javascript:void(0);" data-id="<?php echo absint( get_the_ID() ); ?>" class="close-member wp-dp-dev-listing-delete"><i class="icon-close2"></i></a>
									<div class="panel-heading"> 
										<div class="img-holder">
											<?php if ( has_post_thumbnail() ) { ?>
												<figure>
													<?php the_post_thumbnail( 'thumbnail' ); ?>
												</figure>
											<?php } ?>
											<strong><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></strong>
											<?php if ( isset( $category->name ) && $category->name != '' ) { ?>
												<span><?php echo esc_html( $category->name ); ?></span>
											<?php } ?>
										</div>
										<span class="post-date"><?php echo esc_html( $listing_post_on != '' ? date_i18n( get_option( 'date_format' ), $listing_post_on ) : '-'  ) ?></span>
										<?php
										if ( $listing_status == 'active' || $listing_status == 'awaiting-activation' ) {
											?>
											<span class="expire-date"><?php echo esc_html( $listing_post_expiry != '' ? date_i18n( get_option( 'date_format' ), $listing_post_expiry ) : '-'  ) ?></span>
											<?php
										} else {
											?>
											<span class="expire-date">-</span>
											<?php
										}
										?>
										<span class="edit"><a href="<?php echo esc_url_raw( $wp_dp_listing_update_url ) ?>"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_edit' ) ?></a></span>
									</div>
								</div>
							</li>
							<?php
						}
						wp_reset_postdata();
					} else {
						?>
						<li class="no-listing-found">
							<i class="icon-caution"></i>
							<?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_no_ads' ) ?>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}

		/**
		 * Start Function packages
		 */
		public function purchase_package_info_field_show( $value = '', $label = '', $value_plus = '' ) {

			if ( $value != '' && $value != 'on' ) {
				$html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
			} else if ( $value != '' && $value == 'on' ) {
				$html = '<li><label>' . $label . '</label><span><i class="icon-check"></i></span></li>';
			} else {
				$html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
			}

			return $html;
		}

		public function wp_dp_tab_packages() {
			global $post, $post_id, $wp_dp_plugin_options;
			$wp_dp_current_date = strtotime( date( 'd-m-Y' ) );
			$wp_dp_currency_sign = wp_dp_get_currency_sign();
			$args = array(
				'posts_per_page' => "-1",
				'post_type' => 'package-orders',
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'wp_dp_transaction_user',
						'value' => $post_id,
						'compare' => '=',
					),
					array(
						'key' => 'wp_dp_transaction_status',
						'value' => 'approved',
						'compare' => '=',
					),
				),
			);

			$pkg_query = new WP_Query( $args );
			?>
			<div class="user-packages">
				<div class="element-title">
					<h4><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_packages' ); ?></h4>
				</div>
			</div>
			<div class="user-packages-list">
				<?php if ( isset( $pkg_query ) && $pkg_query != '' && $pkg_query->have_posts() ) : ?>
					<div class="all-pckgs-sec">
						<?php
						while ( $pkg_query->have_posts() ) : $pkg_query->the_post();
							$transaction_package = get_post_meta( get_the_ID(), 'wp_dp_transaction_package', true );
							$transaction_listings = get_post_meta( get_the_ID(), 'wp_dp_transaction_listings', true );
							$transaction_feature_list = get_post_meta( get_the_ID(), 'wp_dp_transaction_listing_feature_list', true );
							$transaction_top_cat_list = get_post_meta( get_the_ID(), 'wp_dp_transaction_listing_top_cat_list', true );

							$package_id = get_the_ID();
							$transaction_listings = isset( $transaction_listings ) ? $transaction_listings : 0;
							$transaction_feature_list = isset( $transaction_feature_list ) ? $transaction_feature_list : 0;
							$transaction_top_cat_list = isset( $transaction_top_cat_list ) ? $transaction_top_cat_list : 0;

							$wp_dp_currency_sign = get_post_meta( get_the_ID(), 'wp_dp_currency', true );
							$wp_dp_currency_sign = ( $wp_dp_currency_sign != '' ) ? $wp_dp_currency_sign : '$';

							$currency_position = get_post_meta( get_the_ID(), "wp_dp_currency_position", true );

							$package_price = get_post_meta( $package_id, 'wp_dp_transaction_amount', true );

							$html = '';
							?>
							<div class="wp-dp-pkg-holder">
								<div class="wp-dp-pkg-header">
									<div class="pkg-title-price pull-left">
										<label class="pkg-title"><?php echo get_the_title( $transaction_package ); ?></label>
										<span class="pkg-price"><?php printf( wp_dp_plugin_text_srt( 'wp_dp_member_price' ), wp_dp_get_order_currency( $package_price, $wp_dp_currency_sign, $currency_position ) ) ?></span>
									</div>
									<div class="pkg-detail-btn pull-right">
										<a data-id="<?php echo absint( $package_id ) ?>" class="wp-dp-dev-dash-detail-pkg" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_detail' ) ?></a>
									</div>
								</div>
								<div class="package-info-sec listing-info-sec" style="display:none;" id="package-detail-<?php echo absint( $package_id ) ?>">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<ul class="listing-pkg-points">
												<?php
												$trans_packg_list_num = get_post_meta( $package_id, 'wp_dp_transaction_listings', true );
												$trans_packg_list_expire = get_post_meta( $package_id, 'wp_dp_transaction_listing_expiry', true );
												$wp_dp_listing_ids = get_post_meta( $package_id, 'wp_dp_listing_ids', true );

												if ( empty( $wp_dp_listing_ids ) ) {
													$wp_dp_listing_used = 0;
												} else {
													$wp_dp_listing_used = absint( sizeof( $wp_dp_listing_ids ) );
												}

												$wp_dp_listing_dpain = '0';
												if ( ( int ) $trans_packg_list_num > ( int ) $wp_dp_listing_used ) {
													$wp_dp_listing_dpain = ( int ) $trans_packg_list_num - ( int ) $wp_dp_listing_used;
												}

												$trans_pics_num = get_post_meta( $package_id, 'wp_dp_transaction_listing_pic_num', true );
												$trans_docs_num = get_post_meta( $package_id, 'wp_dp_transaction_listing_doc_num', true );
												$trans_tags_num = get_post_meta( $package_id, 'wp_dp_transaction_listing_tags_num', true );
												$trans_reviews = get_post_meta( $package_id, 'wp_dp_transaction_listing_reviews', true );
                                                                                                
                                                                                                $trans_packg_expiry = get_post_meta($package_id, 'wp_dp_transaction_expiry_date', true);

												$trans_phone = get_post_meta( $package_id, 'wp_dp_transaction_listing_phone_website', true );
												$trans_social = get_post_meta( $package_id, 'wp_dp_transaction_listing_social', true );
												$trans_ror = get_post_meta( $package_id, 'wp_dp_transaction_listing_ror', true );
												$trans_dynamic_f = get_post_meta( $package_id, 'wp_dp_transaction_dynamic', true );

												$pkg_expire_date = date_i18n( get_option( 'date_format' ), $trans_packg_expiry );

												$html .= '<li><label>' . wp_dp_plugin_text_srt( 'wp_dp_member_listings' ) . '</label><span>' . absint( $wp_dp_listing_used ) . '/' . absint( $trans_packg_list_num ) . '</span></li>';
												$html .= $this->purchase_package_info_field_show( $trans_packg_list_expire, wp_dp_plugin_text_srt( 'wp_dp_member_listings_duration' ), wp_dp_plugin_text_srt( 'wp_dp_member_days' ) );

												$html .= $this->purchase_package_info_field_show( $trans_pics_num, wp_dp_plugin_text_srt( 'wp_dp_member_no_pictures' ) );
												$html .= $this->purchase_package_info_field_show( $trans_docs_num, wp_dp_plugin_text_srt( 'wp_dp_member_no_documents' ) );
												$html .= $this->purchase_package_info_field_show( $trans_tags_num, wp_dp_plugin_text_srt( 'wp_dp_member_no_tags' ) );
												$html .= $this->purchase_package_info_field_show( $trans_phone, wp_dp_plugin_text_srt( 'wp_dp_member_phone_number_website' ) );
												$html .= $this->purchase_package_info_field_show( $trans_social, wp_dp_plugin_text_srt( 'wp_dp_member_social_impressions' ) );

												$dyn_fields_html = '';
												if ( is_array( $trans_dynamic_f ) && sizeof( $trans_dynamic_f ) > 0 ) {
													foreach ( $trans_dynamic_f as $trans_dynamic ) {
														if ( isset( $trans_dynamic['field_type'] ) && isset( $trans_dynamic['field_label'] ) && isset( $trans_dynamic['field_value'] ) ) {
															$d_type = $trans_dynamic['field_type'];
															$d_label = $trans_dynamic['field_label'];
															$d_value = $trans_dynamic['field_value'];

															if ( $d_value == 'on' && $d_type == 'single-choice' ) {
																$html .= '<li><label>' . $d_label . '</label><span><i class="icon-check"></i></span></li>';
															} else if ( $d_value != '' && $d_type != 'single-choice' ) {
																$html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
															} else {
																$html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
															}
														}
													}
													// end foreach
												}
												// emd of Dynamic fields
												// other Features
												echo force_balance_tags( $html );
												?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<?php
						endwhile;
						?>

					</div>
					<?php
				else:
					echo wp_dp_plugin_text_srt( 'wp_dp_member_meta_no_package' );
				endif;
				?>
			</div>
			<?php
		}

		/**
		 * Member Removed Favourite
		 * @ removed member favourites based on listing id
		 */
		public function wp_dp_removed_favourite() {
			global $post_id;
			$listing_id = wp_dp_get_input( 'listing_id' );
			$post_id = wp_dp_get_input( 'post_id' );
			$current_user = wp_get_current_user();
			$member_id = get_current_user_id();
			$user_data = get_user_info_array();
			$response = array();
			$response['status'] = false;

			if ( '' != $listing_id ) {
				$member_favourites = get_post_meta( $post_id, 'wp_dp_favourites', true );
				foreach ( $member_favourites as $key => $sub_array ) {
					if ( $sub_array['listing_id'] == $listing_id ) {
						unset( $member_favourites[$key] );
						$response['status'] = true;
					}
				}
				if ( ! empty( $member_favourites ) ) {
					$member_favourites = array_values( $member_favourites );
				}
				update_post_meta( $post_id, 'wp_dp_favourites', $member_favourites );
				$display_name = isset( $user_data['display_name'] ) ? $user_data['display_name'] : '';
				$display_name = '<a href="' . get_the_permalink( $member_id ) . '">' . $display_name . '</a>';
				$notification_array = array(
					'type' => 'listing',
					'element_id' => $listing_id,
					'msg' => $display_name . ' ' . wp_dp_plugin_text_srt( 'wp_dp_member_ad_removed' ),
				);
				do_action( 'wp_dp_add_notification', $notification_array );
			}
			echo json_encode( $response );
			wp_die();
		}

		public function remove_listing_alert() {
			$status = 0;
			$msg = '';
			if ( isset( $_POST['post_id'] ) ) {
				wp_delete_post( $_POST['post_id'] );
				$status = 1;
				$msg = wp_dp_plugin_text_srt( 'wp_dp_member_listing_alert_delete' );
			} else {
				$msg = wp_dp_plugin_text_srt( 'wp_dp_member_data_incomplete' );
				$status = 0;
			}
			echo json_encode( array( "msg" => $msg, 'status' => $status ) );
			wp_die();
		}

		/**
		 * Member Member Form
		 */
		/*
		 * Updating Team Member
		 */

		public function wp_dp_update_team_member() {
			$user_ID = wp_dp_get_input( 'wp_dp_user_id', NULL, 'INT' );
			$wp_dp_user_type = wp_dp_get_input( 'wp_dp_user_type', NULL, 'STRING' );
			$wp_dp_old_user_type = wp_dp_get_input( 'wp_dp_old_user_type', NULL, 'STRING' );
			$count_supper_admin = wp_dp_get_input( 'count_supper_admin', NULL, 'STRING' );
			$wp_dp_public_profile = wp_dp_get_input( 'wp_dp_public_profile', NULL, 'STRING' );

			$update_allow = 1;
			if ( $wp_dp_old_user_type == $wp_dp_user_type ) {
				$update_allow = 1;
			} elseif ( 'supper-admin' == $wp_dp_user_type ) {
				$update_allow = 1;
			} elseif ( $count_supper_admin > 1 ) {
				$update_allow = 1;
			} else {
				$update_allow = 0;
			}
			if ( $update_allow == 1 ) {
				$permissions = wp_dp_get_input( 'permissions', '', 'ARRAY' );

				update_user_meta( $user_ID, 'wp_dp_user_type', $wp_dp_user_type );
				update_user_meta( $user_ID, 'wp_dp_permissions', $permissions );
				update_user_meta( $user_ID, 'wp_dp_public_profile', $wp_dp_public_profile );

				$response_array = array(
					'type' => 'success',
					'msg' => wp_dp_plugin_text_srt( 'wp_dp_member_member_updated' ),
				);
			} else {
				$response_array = array(
					'type' => 'error',
					'msg' => wp_dp_plugin_text_srt( 'wp_dp_member_supper_admin_error' ),
				);
			}
			echo json_encode( $response_array );
		}

		public function wp_dp_remove_team_member() {
			$count_supper_admin = wp_dp_get_input( 'count_supper_admin', NULL, 'STRING' );
			$user_ID = wp_dp_get_input( 'wp_dp_user_id', NULL, 'INT' );
			if ( $count_supper_admin > 1 ) {
				update_user_meta( $user_ID, 'wp_dp_user_status', 'deleted' );
				$response_array = array(
					'type' => 'success',
					'msg' => wp_dp_plugin_text_srt( 'wp_dp_member_member_removed' ),
				);
			} else {
				$response_array = array(
					'type' => 'error',
					'msg' => wp_dp_plugin_text_srt( 'wp_dp_member_supper_admin_error' ),
				);
			}
			echo json_encode( $response_array );
			wp_die();
		}

		public function wp_dp_member_members() {
			global $wp_dp_html_fields_frontend, $post, $wp_dp_form_fields_frontend, $post_id, $wp_dp_html_fields, $wp_dp_form_fields;
			$company_data = get_post( $post_id );
			setup_postdata( $company_data );
			?>
			<div class="team-list-holder">
				<div class = "element-title">
					<h4><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_team_members' ); ?></h4>
				</div>
				<div class="team-list">
					<?php
					$team_args = array(
						'role' => 'wp_dp_member',
						'meta_query' => array(
							array(
								'key' => 'wp_dp_company',
								'value' => $company_data->ID,
								'compare' => '='
							),
							array(
								'relation' => 'OR',
								array(
									'key' => 'wp_dp_user_status',
									'compare' => 'NOT EXISTS',
									'value' => 'completely'
								),
								array(
									'key' => 'wp_dp_user_status',
									'value' => 'deleted',
									'compare' => '!='
								),
							),
						),
					);
					$team_members = get_users( $team_args );
					if ( isset( $team_members ) && ! empty( $team_members ) ) {
						?>
						<ul class = "panel-group" id = "accordion">
							<?php echo '<div class="outerwrapp-layer">
                                <div class="loading_div" id="wp_dp_loading_msg_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
                                    ' . wp_dp_plugin_text_srt( 'wp_dp_member_please_wait' ) . '
                                </div>
                                <div class="form-msg"> <i class="icon-check-circle-o"></i>
                                    <div class="innermsg"></div>
                                </div>
                            </div>'; ?>
							<li> 
								<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_username' ); ?></span>
								<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_email_address' ); ?></span> 
							</li>
							</form>
							<?php
							// count the supper admin in complete team
							$supper_admin_count = 0;
							foreach ( $team_members as $member_data ) {
								$selected_user_type = get_user_meta( $member_data->ID, 'wp_dp_user_type', true );
								if ( $selected_user_type == 'supper-admin' ) {
									$supper_admin_count ++;
								}
							}
							foreach ( $team_members as $member_data ) {
								$selected_user_type = get_user_meta( $member_data->ID, 'wp_dp_user_type', true );
								$selected_user_type = isset( $selected_user_type ) && $selected_user_type != '' ? $selected_user_type : 'team-member';
								$member_permissions = get_user_meta( $member_data->ID, 'wp_dp_permissions', true );
								$wp_dp_member_name = get_user_meta( $member_data->ID, 'member_name', true );
								$wp_dp_member_phone_number = get_user_meta( $member_data->ID, 'member_phone_number', true );
								$member_profile_picture = get_user_meta( $member_data->ID, 'member_thumb', true );
								$wp_dp_public_profile = get_user_meta( $member_data->ID, 'wp_dp_public_profile', true );

								$profile_url = '';
								if ( ! empty( $member_profile_picture ) ) {
									$profile_url = wp_get_attachment_url( $member_profile_picture );
								}
								?>

								<li data-id="<?php echo esc_attr( $member_data->ID ); ?>">    
									<form name="wp_dp_update_team_member" id="wp_dp_update_team_member<?php echo esc_attr( $member_data->ID ); ?>" data-id="<?php echo esc_attr( $member_data->ID ); ?>" method="POST">
										<?php
										// TOTAL SUPPER ADMIN COUNT
										$wp_dp_form_fields->wp_dp_form_hidden_render(
												array(
													'cust_name' => 'count_supper_admin',
													'id' => 'count_supper_admin' . $member_data->ID,
													'std' => $supper_admin_count,
												)
										);
										$wp_dp_form_fields->wp_dp_form_hidden_render(
												array(
													'cust_name' => 'wp_dp_old_user_type',
													'id' => 'wp_dp_old_user_type' . $member_data->ID,
													'std' => $selected_user_type,
												)
										);
										?>
										<script>
											jQuery(document).on('click', '.remove_member_backend', function () {
												var thisObj = jQuery(this);
												var user_id = jQuery(this).closest('li').data('id');
												var count_supper_admin = jQuery("#wp_dp_count_supper_admin" + user_id).val();
												var wp_dp_old_user_type = jQuery("#wp_dp_old_user_type" + user_id).val();
												jQuery.ajax({
													type: 'POST',
													dataType: 'json',
													url: wp_dp_globals.ajax_url,
													data: 'count_supper_admin=' + count_supper_admin + '&wp_dp_old_user_type=' + wp_dp_old_user_type + '&wp_dp_user_id=' + user_id + '&action=wp_dp_remove_team_member',
													success: function (response) {
														if (response.type == 'success') {
															thisObj.closest('li').fadeOut('slow');
														} else {
															jQuery(".wp-dp-error-messages").html(response.msg);
															jQuery(".wp-dp-error-messages").show();
															setTimeout(function () {
																jQuery(".wp-dp-error-messages").hide();
															}, 5000);
														}
													}
												});
											});
										</script>
										<div class = "panel panel-default"> <a href="javascript:;" class="close-member"><i class="icon-close2 remove_member_backend"></i></a>
											<div class = "panel-heading"> 
												<a data-toggle = "collapse" data-parent = "#accordion" href = "#collapse<?php echo esc_attr( $member_data->ID ); ?>" class = "collapsed">
													<div class = "img-holder">
														<strong><?php echo esc_html( $member_data->user_login ); ?> </strong> 
													</div>
													<span class="email"><?php echo esc_html( $member_data->user_email ); ?> </span> 
													<?php if ( $selected_user_type == 'supper-admin' ) { ?><span class="supper-admin"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_super_admin' ); ?></span>
													<?php } ?>
												</a>
											</div>
										</div>

										<div id = "collapse<?php echo esc_attr( $member_data->ID ); ?>" class = "panel-collapse collapse">
											<div class = "panel-body">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_name' ); ?></label>
														<?php
														$wp_dp_opt_array = array(
															'std' => esc_html( $member_data->user_login ),
															'force_std' => true,
															'cust_id' => 'member_name',
															'cust_name' => 'wp_dp_member_name',
														);
														$wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_email_address' ); ?></label>
														<?php
														$wp_dp_opt_array = array(
															'std' => esc_html( $member_data->user_email ),
															'force_std' => true,
															'id' => 'email_address',
														);
														$wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_phone_number' ); ?></label>
														<?php
														$wp_dp_opt_array = array(
															'id' => '',
															'std' => esc_html( $wp_dp_member_phone_number ),
															'cust_id' => 'member_phone_number',
															'force_std' => true,
															'cust_name' => 'wp_dp_member_phone_number',
														);
														$wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
														?>
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="field-holder form-elements">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_profile_image' ); ?></label>
														<?php
														$wp_dp_opt_array = array(
															'id' => 'member_thumb_' . $member_data->ID,
															'cust_name' => 'member_thumb',
															'std' => $profile_url,
															'classes' => '',
															'force_std' => true,
														);

														$wp_dp_form_fields->wp_dp_form_fileupload_render( $wp_dp_opt_array );

														$wp_dp_form_fields->wp_dp_form_hidden_render(
																array(
																	'cust_name' => 'wp_dp_member_thumb_id',
																	'id' => 'wp_dp_member_thumb_id' . $member_data->ID,
																	'std' => $member_profile_picture,
																)
														);

														$wp_dp_form_fields->wp_dp_form_hidden_render(
																array(
																	'cust_name' => 'update_from_admin',
																	'id' => 'update_from_admin' . $member_data->ID,
																	'std' => 1,
																)
														);
														?>
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_public_profile' ); ?></label>

														<?php
														$user_option = array(
															'no' => 'No',
															'yes' => 'Yes',
														);
														$wp_dp_opt_array = array(
															'std' => $wp_dp_public_profile,
															'id' => 'public_profile',
															'classes' => 'chosen-select-no-single',
															'options' => $user_option,
														);
														$wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );
														?>
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_user_type' ); ?></label>

														<?php
														$user_type = array(
															'supper-admin' => wp_dp_plugin_text_srt( 'wp_dp_member_super_admin' ),
															'team-member' => wp_dp_plugin_text_srt( 'wp_dp_member_team_member' ),
														);
														$wp_dp_opt_array = array(
															'std' => $selected_user_type,
															'id' => 'user_type',
															'classes' => 'chosen-select-no-single',
															'options' => $user_type,
															'extra_atr' => 'onchange="wp_dp_user_permission(this, \'add_member_permission' . esc_attr( $member_data->ID ) . '\', \'supper-admin\');"'
														);
														$wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<?php
												$permission_display = '';
												if ( $selected_user_type == 'supper-admin' ) {
													$permission_display = 'display:none';
												}
												?>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add_member_permission<?php echo esc_attr( $member_data->ID ); ?>" style="<?php echo esc_html( $permission_display ); ?>">
													<h6 ><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_roles' ); ?></h6>
													<?php
													global $permissions;
													$permissions_array = $permissions->member_permissions();
													?>
													<ul class = "checkbox-list">
														<?php
														foreach ( $permissions_array as $permission_key => $permission_value ) {
															$value = '';
															if ( isset( $member_permissions[$permission_key] ) && $member_permissions[$permission_key] == 'on' ) {
																$value = $member_permissions[$permission_key];
															} else if ( $selected_user_type == 'supper-admin' ) {  // if user supper admin then show all permission
																$value = 'on';
															}
															$rand = rand( 23445, 99 );
															?>
															<li class = "col-lg-6 col-md-6 col-sm-12 col-xs-12" draggable = "true" style = "display: inline-block;">
																<?php
																$wp_dp_opt_array = array(
																	'name' => $permission_value,
																	'desc' => '',
																	'echo' => true,
																	'simple' => true,
																	'field_params' => array(
																		'std' => $value,
																		'simple' => true,
																		'id' => $permission_key . $rand,
																		'cust_name' => 'permissions[' . $permission_key . ']',
																	),
																);
																$wp_dp_html_fields->wp_dp_custom_checkbox_render( $wp_dp_opt_array );
																?>
															</li>
														<?php } ?>
													</ul>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<button name="button" class="btn-submit btn-update" type="button" id="team_update_form_backend"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_update' ); ?></button>
												</div>
											</div>
										</div>
										<script>
											jQuery(document).ready(function () {
												'use strict'
												jQuery(".chosen-select-no-single").chosen();
											});
										</script>
									</form>
								</li>
							<?php } ?>
						</ul>
						<?php
					} else {
						echo '<div class="cs-no-record">' . wp_dp_info_messages_listing( wp_dp_plugin_text_srt( 'wp_dp_member_no_team_member_found' ) ) . '</div>';
					}
					?>
				</div>
			</div>
			<?php
		}

		public function wp_dp_member_branches() {
			global $wp_dp_html_fields_frontend, $post, $wp_dp_form_fields_frontend, $post_id, $wp_dp_html_fields;

			$company_data = get_post( $post_id );
			setup_postdata( $company_data );
			?>
			<div class="team-list-holder">
				<div class = "element-title">
					<h4><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_braches' ); ?></h4>
				</div>
				<div class="team-list">
					<?php
					// get branches for this agency.
					$args = array(
						'post_type' => 'branches',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'fields' => 'ids',
						'meta_query' =>
						array(
							array(
								'relation' => 'AND',
								array(
									'key' => 'wp_dp_branch_member',
									'value' => $post_id,
									'compare' => '=',
								),
							)
						)
					);
					$branches = get_posts( $args );

					if ( isset( $branches ) && ! empty( $branches ) ) {
						?>
						<ul class = "panel-group" id = "accordion">
							<?php echo '<div class="outerwrapp-layer">
                                <div class="loading_div" id="wp_dp_loading_msg_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
                                    ' . wp_dp_plugin_text_srt( 'wp_dp_member_please_wait' ) . '
                                </div>
                                <div class="form-msg"> <i class="icon-check-circle-o"></i>
                                    <div class="innermsg"></div>
                                </div>
                            </div>'; ?>
							<li> 
								<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_name' ); ?></span>
								<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_address' ); ?></span> 
								<!--
																<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_phone' ); ?></span> 
								<span><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_email_address' ); ?></span>
								-->
							</li>

							<?php
							$args = array(
								'post_type' => 'members',
								'posts_per_page' => -1,
								'post_status' => 'publish',
								'fields' => 'ids',
								'meta_query' =>
								array(
									array(
										'relation' => 'AND',
										array(
											'key' => 'wp_dp_user_status',
											'value' => 'active',
											'compare' => '=',
										),
									)
								)
							);
							$members = get_posts( $args );
							$wp_dp_member_list = array();
							if ( ! empty( $members ) ) {
								foreach ( $members as $member ) {
									$wp_dp_member_list[$member] = esc_html( get_the_title( $member ) );
								}
							}
							wp_reset_postdata();

							foreach ( $branches as $branch ) {
								$branch_member = get_post_meta( $branch, 'wp_dp_branch_member', true );
								$branche_name = get_post_meta( $branch, 'wp_dp_branch_name', true );
								$branche_phone = get_post_meta( $branch, 'wp_dp_branch_phone', true );
								$branche_email = get_post_meta( $branch, 'wp_dp_branch_email', true );
								$branche_adrss = get_post_meta( $branch, 'wp_dp_post_loc_address_branch', true );
								$branche_lat = get_post_meta( $branch, 'wp_dp_post_loc_latitude_branch', true );
								$branche_lng = get_post_meta( $branch, 'wp_dp_post_loc_longitude_branch', true );
								?>

								<li data-id="<?php echo esc_attr( $branch ); ?>">
									<form name="wp_dp_update_branch" id="wp_dp_update_branch<?php echo esc_attr( $branch ); ?>" data-id="<?php echo esc_attr( $branch ); ?>" method="POST">
										<div class = "panel panel-default"> <a href="javascript:;" class="close-member"><i class="icon-close2 remove_branch_backend"></i></a>
											<div class = "panel-heading"> 
												<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo esc_attr( $branch ); ?>" class="collapsed">
													<div class="img-holder">
														<strong><?php echo esc_html( $branche_name ); ?> </strong> 
													</div>
													<span class="email"><?php echo esc_html( $branche_adrss ); ?> </span> 
												</a>
											</div>
										</div>

										<div id="collapse<?php echo esc_attr( $branch ); ?>" class="panel-collapse collapse">
											<div class = "panel-body">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<?php
														$wp_dp_opt_array = array(
															'name' => wp_dp_plugin_text_srt( 'wp_dp_member_member' ),
															'desc' => '',
															'hint_text' => '',
															'echo' => true,
															'field_params' => array(
																'std' => $branch_member,
																'id' => 'branch_member',
																'classes' => 'chosen-select-no-single',
																'options' => $wp_dp_member_list,
																'return' => true,
															),
														);
														$wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<?php
														$wp_dp_opt_array = array(
															'name' => wp_dp_plugin_text_srt( 'wp_dp_member_name' ),
															'desc' => '',
															'echo' => true,
															'field_params' => array(
																'id' => 'branch_name',
																'std' => esc_html( $branche_name ),
																'return' => true,
															),
														);
														$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<?php
														$wp_dp_opt_array = array(
															'name' => wp_dp_plugin_text_srt( 'wp_dp_member_phone' ),
															'desc' => '',
															'echo' => true,
															'field_params' => array(
																'id' => 'phone_number',
																'std' => esc_html( $branche_phone ),
																'return' => true,
															),
														);
														$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
														?>
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<?php
														$wp_dp_opt_array = array(
															'name' => wp_dp_plugin_text_srt( 'wp_dp_member_email' ),
															'desc' => '',
															'echo' => true,
															'field_params' => array(
																'std' => esc_html( $branche_email ),
																'id' => 'email_address',
																'return' => true,
															),
														);
														$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
														?>
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class = "field-holder">
														<?php
														$wp_dp_opt_array = array(
															'name' => wp_dp_plugin_text_srt( 'wp_dp_member_address' ),
															'desc' => '',
															'echo' => true,
															'field_params' => array(
																'std' => esc_html( $branche_adrss ),
																'id' => 'branch_address',
																'return' => true,
															),
														);
														$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
														?>
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<?php
													$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
															array(
																'cust_name' => 'branch_id',
																'id' => 'wp_dp_branch_id' . $branch,
																'std' => $branch,
															)
													);
													?>
													<button name="button" class="btn-submit btn-update" type="button" id="branch_update_form_backend"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_update' ); ?></button>
												</div>
											</div>
										</div>
										<script>
											jQuery(document).ready(function () {
												'use strict'
												jQuery(".chosen-select-no-single").chosen();
											});
										</script>
									</form>
								</li>
							<?php } ?>
						</ul>

						<script type="text/javascript">
							jQuery(document).on('click', '.remove_branch_backend', function () {
								var thisObj = jQuery(this);
								var branch_id = jQuery(this).closest('li').data('id');
								jQuery.ajax({
									type: 'POST',
									dataType: 'json',
									url: wp_dp_globals.ajax_url,
									data: 'wp_dp_branch_id=' + branch_id + '&action=wp_dp_remove_branch',
									success: function (response) {
										if (response.type == 'success') {
											thisObj.closest('li').fadeOut('slow');
										} else {
											jQuery(".wp-dp-error-messages").html(response.msg);
											jQuery(".wp-dp-error-messages").show();
											setTimeout(function () {
												jQuery(".wp-dp-error-messages").hide();
											}, 5000);
										}
									}
								});
							});
							/*
							 * Update Branch
							 */
							jQuery(document).on('click', '#branch_update_form_backend', function () {

								jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
								var branch_id = jQuery(this).closest('li').data('id');
								var serializedValues = jQuery("#wp_dp_update_branch" + branch_id).serialize();
								jQuery.ajax({
									type: 'POST',
									dataType: 'json',
									url: wp_dp_globals.ajax_url,
									data: serializedValues + '&wp_dp_user_id=' + branch_id + '&action=wp_dp_update_branch',
									success: function (response) {
										jQuery(".loading_div").hide();
										jQuery(".form-msg .innermsg").html(response.msg);
										jQuery(".form-msg").show();
										jQuery(".outerwrapp-layer").delay(3000).fadeOut(500);
										slideout();
									}


								});
							});
						</script>
						<?php
					} else {
						echo '<div class="cs-no-record">' . wp_dp_info_messages_listing( wp_dp_plugin_text_srt( 'wp_dp_member_no_braches_found' ) ) . '</div>';
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Start Function How to add form options in  html
		 */
		function wp_dp_members_options() {
			global $post, $wp_dp_form_fields, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options, $display_field;
			$post_id = $post->ID;
			$wp_dp_profile_image = get_post_meta( $post_id, 'wp_dp_profile_image', true );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_profile_image' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_member_profile_image_error' ),
				'echo' => true,
				'id' => 'profile_image',
				'std' => '',
				'field_params' => array(
					'id' => 'profile_image',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_upload_file_field( $wp_dp_opt_array );

			$user_status = array(
				'pending' => wp_dp_plugin_text_srt( 'wp_dp_member_pending' ),
				'active' => wp_dp_plugin_text_srt( 'wp_dp_member_active' ),
				'inactive' => wp_dp_plugin_text_srt( 'wp_dp_member_inactive' ),
			);

			$selected_user_status = get_post_meta( 'wp_dp_user_status', $post_id );
			$selected_user_status = ( $selected_user_status == '' ? 'pending' : $selected_user_status );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_profile_status' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => $selected_user_status,
					'id' => 'user_status',
					'classes' => 'chosen-select-no-single',
					'options' => $user_status,
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_featured' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'id' => 'member_is_featured',
					'classes' => '',
					'std' => '',
					'description' => '',
					'hint' => '',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_checkbox_field( $wp_dp_opt_array );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_trusted_member' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'id' => 'member_is_trusted',
					'classes' => '',
					'std' => '',
					'description' => '',
					'hint' => '',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_checkbox_field( $wp_dp_opt_array );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_num_of_listings' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'num_of_listings',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );



			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_phone' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'phone_number',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_email_address' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'email_address',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_user_meta_facebook' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'facebook',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );

			/*$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_user_meta_google_plus' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'google_plus',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );*/

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_user_meta_twitter' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'twitter',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_user_meta_linkedIn' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'linkedIn',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_biography' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'biography',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_textarea_field( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_total_percentage' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'total_percentage',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_member_total_review_count' ),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => 'total_review_count',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			WP_DP_FUNCTIONS()->wp_dp_location_fields( 'off', '', 'member', '', true );
		}

	}

	global $wp_dp_members_meta;
	$wp_dp_members_meta = new Wp_dp_Members_Meta();
	return $wp_dp_members_meta;
}