<?php
/**
 * @Add Meta Box For Member Profile
 * @return
 *
 */

    add_action( 'show_user_profile', 'extra_user_profile_fields' );
    add_action( 'edit_user_profile', 'extra_user_profile_fields' );
if (!function_exists('extra_user_profile_fields')) {
    function extra_user_profile_fields( $user ) {
    global $post, $wp_dp_form_fields, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options;
    $wp_dp_plugin_options = get_option( 'wp_dp_plugin_options' );
    $roles = $user->roles;
    if ( in_array( 'wp_dp_member', $roles ) || in_array( 'administrator', $roles ) ) {
    ?>
    <table class="form-table">
        <!---<tr>
            <th><label for="wp_dp_user_type"><?php //echo wp_dp_plugin_text_srt( 'wp_dp_meta_user_type' ); ?></label></th>
            <td><?php
                /* $user_type = array(
                    'supper-admin' => wp_dp_plugin_text_srt( 'wp_dp_meta_supper_admin' ),
                    'team-member' => wp_dp_plugin_text_srt( 'wp_dp_meta_team_member' ),
                );
                $selected_user_type = get_the_author_meta( 'wp_dp_user_type', $user->ID );
                $selected_user_type = ( $selected_user_type == '' ? 'team-member' : $selected_user_type );
                $wp_dp_opt_array = array(
                    'std' => $selected_user_type,
                    'id' => 'user_type',
                    'classes' => 'chosen-select-no-single',
                    'options' => $user_type,
                );
                $wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array ); */
                ?></td>
        </tr> --->
		<tr class="user-company">
            <th><label for="wp_dp_company"><?php echo wp_dp_plugin_text_srt( 'wp_dp_meta_user_company' ); ?></label></th>
			<td>
				<?php
                $post_company_args=array('post_type' => 'members', 'posts_per_page' => '-1', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' );
                $loop= new wp_query( $post_company_args);
                $options = array( '' => wp_dp_plugin_text_srt( 'wp_dp_meta_select_user_company' ) );
                while($loop->have_posts()){
                    $loop->the_post();
					$options[get_the_ID()]=get_the_title();
                }
                wp_reset_postdata();
               
                $selected_user_company = get_user_meta(  $user->ID, 'wp_dp_company',true);
                $wp_dp_opt_array = array(
                    'std' => $selected_user_company,
                    'id' => 'company',
                    'classes' => 'chosen-select-no-single',
                    'options' =>  $options,
                );
                $wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );
                ?>
				<span class="compnay-error" style="display: none; color: red; font-style: italic;"><?php echo wp_dp_plugin_text_srt( 'wp_dp_meta_select_user_company_empty' ); ?></span>
			</td>
			<td><span style="color: #0073aa; font-style: italic;"><a href="<?php echo esc_url(admin_url('post-new.php?post_type=members')); ?>"><?php echo wp_dp_plugin_text_srt( 'wp_dp_meta_create_new_company' ); ?></a></span></td>
        </tr>
    </table> 
    <?php
    }
}
}

    add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
    add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
if (!function_exists('save_extra_user_profile_fields')) {
    function save_extra_user_profile_fields( $user_id ) {
    global $wpdb, $buyer_permissions;
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( isset( $_POST['wp_dp_user_type'] ) ) {
        update_user_meta( $user_id, 'wp_dp_user_type', $_POST['wp_dp_user_type'] );
    }
    if ( isset( $_POST['wp_dp_company'] ) ) {
        update_user_meta( $user_id, 'wp_dp_company', $_POST['wp_dp_company'] );
    }
}
}