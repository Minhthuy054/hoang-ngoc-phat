<?php

/**
 * File Type: Notifications Submission from frontend
 */
if ( ! class_exists( 'Wp_dp_Activity_Notifications_Submission' ) ) {

    class Wp_dp_Activity_Notifications_Submission {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            $notification_status = apply_filters( 'wp_dp_get_plugin_options', 'wp_dp_activity_notifications_switch' );
            if ( isset( $notification_status ) && $notification_status == 'on' ) {
                add_action( 'wp_dp_add_notification', array( &$this, 'wp_dp_add_notification_callback' ) );
            }
        }

        /**
         * Adding Notification Function
         * @Array contains element id, element type, sender and reciever ids.
         */
        public function wp_dp_add_notification_callback( $data_array = array() ) {
            if ( ! is_array($data_array) ) {
                    $data_array = array();
            }
            wp_enqueue_script('wp-dp-notifications-js');
			
            $reciever_id = $this->reciever_id_by_type( $data_array );
            $notification_icon = $this->notification_icon( $data_array );
            $data_array['submitted_type'] = isset( $data_array['submitted_type'] )? $data_array['submitted_type'] : '';
            if( $data_array['type'] != 'enquiry_message' && $data_array['type'] != 'viewing_status' && $data_array['submitted_type'] != 'true'){
                $data_array['reciever_id'] = $reciever_id;
            }
            if($data_array['submitted_type'] == 'true'){ 
                    $data_array['reciever_id'] = $data_array['reciever_id'];    
            } 
            if( isset( $data_array['reciever_id' ] ) ){
                $current_user_id = get_current_user_id();
				$uesr_company_id	=	get_user_meta($current_user_id, 'wp_dp_company', true);
				$data_array['notif_owner_id']	=	$uesr_company_id;
				$data_array['notif_owner_name']	=	get_the_title($uesr_company_id);
            }
            
            if( isset( $data_array['reciever_id' ] ) ){
                $data_array['reciever_name'] = get_the_title( $data_array['reciever_id' ] );
            }
            $data_array['notification_content'] = $data_array['message'];
            $data_array['notification_icon'] = $notification_icon;
            $this->wp_dp_insert_notification( $data_array );
        }

        /**
         * Notification Type Icon
         * @ Array contains icons for each notification type.
         */
        public function notification_icon( $data_array = array() ) {

			if ( ! is_array($data_array) ) {
				$data_array = array();
			}
            $notification_type = $data_array['type'];
            $icons_array = array(
                'listing' =>  '<i class="icon-heart5"></i>', //'<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/icon-favourite.png">',
                'review' => '<i class="icon-star"></i>', //'<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/icon-reviews.png">',
                'reservation' => '<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/icon-cart.png">',
                'enquiry_message' => '<i class="icon-question_answer"></i>',
                'order_messages' => '<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/icon-msgs.png">',
				'hide_listing' => '<i class="icon-block"></i>',
				'listing_notes' => '<i class="icon-book2"></i>',
				'enquiry' => '<i class="icon-question_answer"></i>',
				'viewing' => '<i class="icon-layers3"></i>',
				'viewing_status' => '<i class="icon-layers3"></i>',
            );
            $notification_icon = '';
            if ( isset( $icons_array[$notification_type] ) ) {
                $notification_icon = $icons_array[$notification_type];
            }
            return $notification_icon;
        }

        /**
         * Notification adding post for notification with referance to the submitted data.
         */
        public function wp_dp_insert_notification( $data_array = array() ) {
			
			if ( ! is_array($data_array) ) {
				$data_array = array();
			}
            $data_array['status'] = 'new';
            $post_title= str_replace( '_', ' ', $data_array['type'] );
            $postArray = array(
                'post_title'    => ucwords($post_title) . ' Notification',
                'post_content'    => $data_array['notification_content'],
                'post_status' => 'publish',
                'post_type' => 'notifications',
            ); 
            $post_id = wp_insert_post( $postArray );  
            foreach ( $data_array as $key => $value ) {
                update_post_meta( $post_id, $key, $value );
            }
        }

        /**
         * Notification Reciever Id based on element type
         */
        public function reciever_id_by_type( $data_array = array() ) {
			
			if ( ! is_array($data_array) ) {
				$data_array = array();
			}
            switch ( $data_array['type'] ) {

                case "listing":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
                case "hide_listing":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
				case "listing_notes":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
				case "enquiry":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
				case "viewing":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
                case "review":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
		case "reservation":
                    return $this->reciever_id_from_listing( $data_array['element_id'] );
                    break;
                
                default :
                    return '';
                    break;
            }
        }

        /**
         * Reciever ID from listing
         * @listing id as parameter
         */
        public function reciever_id_from_listing( $listing_id ) {
            $reciever_id = '';
            if ( isset( $listing_id ) && $listing_id != '' ) {
                $reciever_id = get_post_meta( $listing_id, 'wp_dp_listing_member', true );
            }
            return $reciever_id;
        }

    }

    // Initialize Object
    $wp_dp_activity_notifications_submission_object = new Wp_dp_Activity_Notifications_Submission();
}