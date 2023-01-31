<?php
// Transactions start
// Adding columns start

/**
 * Start Function  how to Create colume in transactions 
 */
if (!function_exists('transactions_columns_add')) {
    add_filter('manage_wp-dp-trans_posts_columns', 'transactions_columns_add');

    function transactions_columns_add($columns) {
	$new_columns = array();
	unset($columns['date']);
	unset($columns['title']);
	$new_columns['p_title'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_transaction_id');
	$new_columns['order_type'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_order_type');
	$new_columns['listing_owner'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_order_owner');
	$new_columns['gateway'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_gateway');
	$new_columns['p_date'] = wp_dp_plugin_text_srt('wp_dp_trans_date_col_title');
	$new_columns['amount'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_amount');
	$new_columns['status'] = wp_dp_plugin_text_srt('wp_dp_transaction_column_status');

	return $new_columns;
    }

}

/**
 * Start Function  how to Show data in columns
 */
if (!function_exists('transactions_columns')) {
    add_action('manage_wp-dp-trans_posts_custom_column', 'transactions_columns', 10, 2);

    function transactions_columns($name) {
	global $post, $gateways, $wp_dp_plugin_options;
	$general_settings = new WP_DP_PAYMENTS();
	$currency_sign = wp_dp_get_currency_sign();
	$transaction_user = get_post_meta($post->ID, 'wp_dp_transaction_user', true);
	$transaction_amount = get_post_meta($post->ID, 'wp_dp_transaction_amount', true);
	$transaction_fee = get_post_meta($post->ID, 'transaction_fee', true);
	$transaction_status = get_post_meta($post->ID, 'wp_dp_transaction_status', true);
	$order_type = get_post_meta($post->ID, 'wp_dp_transaction_order_type', true);

	$transaction_order_id = get_post_meta($post->ID, 'wp_dp_transaction_order_id', true);

	// return payment gateway name
	switch ($name) {
	    case 'p_title':
		echo get_the_title($post->ID);
		break;
	    case 'p_date':
		echo str_replace('Published', ' ', get_the_date());
		break;
	    case 'listing_owner':
		echo force_balance_tags($transaction_user != '' ? '<a href="' . esc_url(get_edit_post_link($transaction_user)) . '">' . get_the_title($transaction_user) . '</a>' : '');
		break;
	    case 'order_type':
		if ($order_type == 'package-order') {
		    echo esc_html__('Package Order', 'wp-dp');
		} else if ($order_type == 'promotion-order') {
		    echo esc_html__('Promotion Order', 'wp-dp');
		} else {
		    echo esc_html__('Order', 'wp-dp');
		}
		break;
	    case 'gateway':
		$wp_dp_trans_gate = get_post_meta(get_the_id(), "wp_dp_transaction_pay_method", true);

		if ($wp_dp_trans_gate != '') {
		    $wp_dp_trans_gate = isset($gateways[strtoupper($wp_dp_trans_gate)]) ? $gateways[strtoupper($wp_dp_trans_gate)] : $wp_dp_trans_gate;

		    $wp_dp_trans_gate = ( isset($wp_dp_trans_gate) && $wp_dp_trans_gate == 'WP_DP_WOOCOMMERCE_GATEWAY' ) ? 'payment cancelled' : $wp_dp_trans_gate;
		    $wp_dp_trans_gate = isset($wp_dp_trans_gate) ? $wp_dp_trans_gate : wp_dp_plugin_text_srt('wp_dp_transaction_gateway_nill');
		    if (in_array($wp_dp_trans_gate, $gateways)) {
			$gateway_logo_id = '';
			if ($wp_dp_trans_gate == 'Paypal') {
			    $gateway_logo_id = $wp_dp_plugin_options['wp_dp_paypal_gateway_logo'];
			}
			if ($wp_dp_trans_gate == 'Authorize.net') {
			    $gateway_logo_id = $wp_dp_plugin_options['wp_dp_authorizedotnet_gateway_logo'];
			}
			if ($wp_dp_trans_gate == 'Pre Bank Transfer') {
			    $gateway_logo_id = $wp_dp_plugin_options['wp_dp_pre_bank_transfer_logo'];
			}
			if ($wp_dp_trans_gate == 'Skrill-MoneyBooker') {
			    $gateway_logo_id = $wp_dp_plugin_options['wp_dp_skrill_gateway_logo'];
			}
		    }
		    if (!empty($gateway_logo_id)) {
			$gateway_logo_url = wp_get_attachment_url($gateway_logo_id);
			$gateway_logo_img = '<img src="' . $gateway_logo_url . '" alt="' . $wp_dp_trans_gate . '" class="wp-dp-trans-gateway-logo">';
			echo $gateway_logo_img;
		    }
		    echo $wp_dp_trans_gate;
		} else {
		    echo '-';
		}
		$order_with = get_post_meta($post->ID, 'wp_dp_order_with', true);
		if (isset($order_with) && $order_with == 'woocommerce') {
		    $order_id = get_post_meta($post->ID, 'woocommerce_order_id', true);
		    if (isset($order_id) && $order_id != '') {
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . get_edit_post_link($order_id) . '">' . wp_dp_plugin_text_srt('wp_dp_transaction_gateway_deatil_order') . '</a>';
		    }
		}
		break;
	    case 'amount':
		$order_with = get_post_meta($post->ID, 'wp_dp_order_with', true);
		$currency_sign = get_post_meta($post->ID, 'wp_dp_currency', true);
		$currency_position = get_post_meta($post->ID, 'wp_dp_currency_position', true);
		if (isset($order_with) && $order_with == 'woocommerce') {
		    $currency_symbol = get_post_meta($post->ID, 'wp_dp_currency', true);
		    if (isset($currency_symbol) && $currency_symbol != '') {
			$currency_sign = $currency_symbol;
		    }
		}
		$currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
		$wp_dp_trans_amount = get_post_meta($post->ID, "wp_dp_transaction_amount", true);
		if ($wp_dp_trans_amount != '') {
		    echo wp_dp_get_order_currency($wp_dp_trans_amount, $currency_sign, $currency_position);
		} else {
		    echo '-';
		}
		break;
	    case 'status':
		global $wp_dp_form_fields;
		$wp_dp_notification_status = get_post_meta($post->ID, 'wp_dp_transaction_status', true);
		$actions = array(
		    'pending' => wp_dp_plugin_text_srt('wp_dp_notif_status_pending'),
		    'in-process' => wp_dp_plugin_text_srt('wp_dp_notif_status_process'),
		    'approved' => wp_dp_plugin_text_srt('wp_dp_notif_status_approved'),
		    'cancelled' => wp_dp_plugin_text_srt('wp_dp_notif_status_cancelled'),
		);

		$wp_dp_opt_array = array(
		    'std' => $wp_dp_notification_status,
		    'id' => 'notif_status_' . $post->ID,
		    'classes' => '',
		    'options' => $actions,
		    'extra_atr' => 'onchange="wp_dp_transaction_status_change(this.value, \'' . $post->ID . '\')"'
		);
		$wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
		echo '<div class="wp_dp_trans_action_' . $post->ID . ' enquiry-status-loader"></div>';

		break;
	}
    }

}

if (!function_exists('wp_dp_transactions_sortable')) {
    add_filter('manage_edit-wp-dp-trans_sortable_columns', 'wp_dp_transactions_sortable');

    function wp_dp_transactions_sortable($columns) {
	$columns['listing_owner'] = 'transaction_listing_owner';
	$columns['order_type'] = 'transaction_order_type';
	$columns['gateway'] = 'transaction_gateway';
	$columns['amount'] = 'transaction_amount';
	$columns['p_date'] = 'p_date';
	return $columns;
    }

}
if (!function_exists('wp_dp_admin_transactions_column_orderby')) {
    add_filter('request', 'wp_dp_admin_transactions_column_orderby');

    function wp_dp_admin_transactions_column_orderby($vars) {
	if (isset($vars['orderby']) && 'transaction_listing_owner' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
		'meta_key' => 'wp_dp_transaction_user',
		'orderby' => 'meta_value',
	    ));
	}
	if (isset($vars['orderby']) && 'transaction_order_type' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
		'meta_key' => 'wp_dp_transaction_order_type',
		'orderby' => 'meta_value',
	    ));
	}
	if (isset($vars['orderby']) && 'transaction_gateway' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
		'meta_key' => 'wp_dp_transaction_pay_method',
		'orderby' => 'meta_value',
	    ));
	}
	if (isset($vars['orderby']) && 'transaction_amount' == $vars['orderby']) {
	    $vars = array_merge($vars, array(
		'meta_key' => 'wp_dp_transaction_amount',
		'orderby' => 'meta_value_num',
	    ));
	}
	return $vars;
    }

}

/**
 * Start Function  how to Row in columns
 */
if (!function_exists('remove_row_actions')) {
    add_filter('post_row_actions', 'remove_row_actions', 10, 1);

    function remove_row_actions($actions) {
	global $post;
	if (get_post_type() == 'wp-dp-trans') {
	    $actions = array(
		'content' => '<a onClick="javascript:wp_dp_transaction_detail_content(' . $post->ID . ');" href="#TB_inline,?width=600&height=content&inlineId=transacton-content-popup-' . $post->ID . '" class="thickbox">' . wp_dp_plugin_text_srt('wp_dp_trans_row_action_content') . '</a> ',
		'delete' => '<a href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a>',
	    );
	}
	?>
	<div style="display:none;" id="transacton-content-popup-<?php echo $post->ID ?>">
	    <div class="transaction-content-wrapper-<?php echo $post->ID ?> popup-loader user-fancy-modal-box"></div>
	    <?php
	    ?>
	</div>
	<?php
	return $actions;
    }

}


/**
 * Start Function  how create post type of transactions
 */
if (!class_exists('post_type_transactions')) {

    class post_type_transactions {

	// The Constructor
	public function __construct() {
	    add_action('init', array(&$this, 'transactions_init'));
	    add_action('admin_init', array(&$this, 'transactions_admin_init'));
	    add_action('admin_menu', array($this, 'wp_dp_remove_post_boxes'));
	    add_action('do_meta_boxes', array($this, 'wp_dp_remove_post_boxes'));
	    add_action('admin_head', array($this, 'disable_new_posts_capability_callback'), 11);
	    add_action('views_edit-wp-dp-trans', array($this, 'wp_dp_remove_views'));

	    add_action('restrict_manage_posts', array($this, 'wp_dp_admin_filters'), 11);
	    add_filter('parse_query', array($this, 'wp_dp_transactions_filter'), 11, 1);
	    add_filter('bulk_actions-edit-wp-dp-trans', array($this, 'wp_dp_remove_edit'));
	    add_action('wp_ajax_wp_dp_trans_action_change', array($this, 'wp_dp_trans_action_change'));
	    add_action('wp_ajax_nopriv_wp_dp_trans_action_change', array($this, 'wp_dp_trans_action_change'));

	    add_action('wp_ajax_wp_dp_transaction_detail_content', array($this, 'wp_dp_transaction_detail_content_callback'));
	    add_action('wp_ajax_nopriv_wp_dp_transaction_detail_content', array($this, 'wp_dp_transaction_detail_content_callback'));
	    add_filter('views_edit-wp-dp-trans', array($this, 'show_transaction_status_dashboard_list'));

	    add_filter('views_edit-wp-dp-trans', array($this, 'remove_total_views'));
	}

	function remove_total_views($views) {
	    $remove_views = ['all', 'publish', 'future', 'sticky', 'draft', 'pending', 'trash'];
	    foreach ((array) $remove_views as $view) {
		if (isset($views[$view]))
		    unset($views[$view]);
	    }
	    return $views;
	}

	public function show_transaction_status_dashboard_list($views) {
	    $total_transaction = wp_count_posts('wp-dp-trans');
	    remove_filter('parse_query', array(&$this, 'wp_dp_transactions_filter'), 11, 1);
	    $args_approved = array(
		'post_type' => 'wp-dp-trans',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
		    'relation' => 'AND',
		    array(
			'key' => 'wp_dp_transaction_status',
			'value' => 'approved',
			'compare' => 'LIKE',
		    ),
		),
	    );
	    $query_approved = new WP_Query($args_approved);
	    $count_transaction_approved = $query_approved->found_posts;
	    // end transaction approved count

	    $args_cancelled = array(
		'post_type' => 'wp-dp-trans',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
		    'relation' => 'AND',
		    array(
			'key' => 'wp_dp_transaction_status',
			'value' => 'cancelled',
			'compare' => 'LIKE',
		    ),
		),
	    );
	    $query_cancelled = new WP_Query($args_cancelled);
	    $count_transaction_cancelled = $query_cancelled->found_posts;
	    // end transaction cancelled count

	    $args_pending = array(
		'post_type' => 'wp-dp-trans',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
		    'relation' => 'OR',
		    array(
			'key' => 'wp_dp_transaction_status',
			'value' => 'pending',
			'compare' => 'LIKE',
		    ),
		    array(
			'key' => 'wp_dp_transaction_status',
			'value' => '',
			'compare' => 'NOT EXISTS',
		    ),
		),
	    );
	    $query_pending = new WP_Query($args_pending);
	    $count_transaction_pending = $query_pending->found_posts;
	    // end transaction pending count

	    $args_process = array(
		'post_type' => 'wp-dp-trans',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
		    'relation' => 'AND',
		    array(
			'key' => 'wp_dp_transaction_status',
			'value' => 'in-process',
			'compare' => 'LIKE',
		    ),
		),
	    );
	    $query_process = new WP_Query($args_process);
	    $count_transaction_process = $query_process->found_posts;
	    // end transaction in-process count

	    wp_reset_postdata();

	    echo '
            <ul class="total-wp-dp-listing row">
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_transaction_total') . ' </strong><em>' . $total_transaction->publish . '</em><i class="icon-coins"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_transaction_processed') . ' </strong><em>' . $count_transaction_process . '</em><i class="icon-back-in-time"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_transaction_approved') . ' </strong><em>' . $count_transaction_approved . '</em><i class="icon-check_circle"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_transaction_pending') . '</strong><em>' . $count_transaction_pending . '</em><i class="icon-clock-o"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_transaction_cancelled') . '</strong><em>' . $count_transaction_cancelled . '</em><i class="icon-money_off"></i></div></li>
            </ul>
            ';
	    return $views;
	}

	public function wp_dp_remove_edit($actions) {
	    unset($actions['edit']);
	    unset($actions['trash']);

	    return $actions;
	}

	public function wp_dp_transaction_detail_content_callback() {

	    global $gateways;
	    $object = new WP_DP_PAYMENTS();

	    $transaction_id = $_POST['transaction_id'];
	    $wp_dp_promotions = get_post_meta($transaction_id, 'wp_dp_promotions', true);
	    $wp_dp_currency = get_post_meta($transaction_id, 'wp_dp_currency', true);
	    $wp_dp_currency = ( $wp_dp_currency != '' ) ? $wp_dp_currency : '$';
	    $wp_dp_currency_position = get_post_meta($transaction_id, 'wp_dp_currency_position', true);
	    $wp_dp_transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);

	    $publish_date = get_the_date('d/M/Y', $transaction_id);

	    $trans_first_name = get_post_meta($transaction_id, 'wp_dp_trans_first_name', true);
	    $trans_last_name = get_post_meta($transaction_id, 'wp_dp_trans_last_name', true);
	    $trans_email = get_post_meta($transaction_id, 'wp_dp_trans_email', true);
	    $trans_phone_number = get_post_meta($transaction_id, 'wp_dp_trans_phone_number', true);
	    $trans_address = get_post_meta($transaction_id, 'wp_dp_trans_address', true);

	    $wp_dp_transaction_pay_method = get_post_meta($transaction_id, 'wp_dp_transaction_pay_method', true);
	    $wp_dp_transaction_amount = get_post_meta($transaction_id, 'wp_dp_transaction_amount', true);

	    $wp_dp_transaction_user = get_the_title(get_post_meta($transaction_id, 'wp_dp_transaction_user', true));
	    $wp_dp_listing_id = get_the_title(get_post_meta($transaction_id, 'wp_dp_listing_id', true));

	    $wp_dp_transaction_order_type = get_post_meta($transaction_id, 'wp_dp_transaction_order_type', true);
	    if ($wp_dp_transaction_order_type == 'package-order') {
		$order_type = 'Package Order';
	    }
	    if ($wp_dp_transaction_order_type == 'promotion-order') {
		$order_type = 'Promotion Order';
	    }

	    $wp_dp_transaction_status = ucfirst(str_replace('-', ' ', get_post_meta($transaction_id, 'wp_dp_transaction_status', true)));

	    $output = '';

	    $output .= '<div class="modelbox-wrapper">';
	    $output .= '	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	    $output .= '		<div class="popup-title"><h2>' . wp_dp_plugin_text_srt('wp_dp_trans_detail_pop_title') . '</h2></div>';
	    $output .= '	</div>';
	    $output .= '	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_listing') . '</strong><span>' . $wp_dp_listing_id . '</span></h3>';
	    $output .= '    	</div>';
	    $output .= '		<div class="popup-list-holder">';
	    $output .= '			<div class="list-title"><h3>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_promotions') . '</h3></div>';
	    $output .= '			<ul>';
	    foreach ($wp_dp_promotions as $promotion_arr) {
		if (isset($promotion_arr['title']) && $promotion_arr['title']) {
		    $output .= '		<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_promotion_title') . '</strong><span> ' . $promotion_arr['title'] . ' </span></h4></li>';
		}
		if (isset($promotion_arr['price']) && $promotion_arr != '') {
		    $price = wp_dp_get_order_currency($promotion_arr['price'], $wp_dp_currency, $wp_dp_currency_position);
		} else {
		    $price = wp_dp_plugin_text_srt('wp_dp_trans_pop_promotion_price_free');
		}
		$output .= '			<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_promotion_price') . '</strong><span> ' . $price . ' </span></h4></li>';

		$expiry_date = isset($promotion_arr['expiry']) ? $promotion_arr['expiry'] : 'unlimitted';
		if ($expiry_date != 'unlimitted') {
		    $expiry_date = date("d/M/Y", strtotime($expiry_date));
		    $expiry_date = $publish_date . ' - ' . $expiry_date;
		}
		$output .= '			<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_promotion_duration') . '</strong><span> ' . $promotion_arr['duration'] . ' Days (' . $expiry_date . ') </span></h4></li>';
	    }
	    $output .= '			</ul>';
	    $output .= '		</div>';
	    $output .= '		<div class="popup-list-holder">';
	    $output .= '        	<div class="list-title"><h3>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_summary_title') . '</h3></div>';
	    $output .= '			<ul>';
	    $output .= '				<li><h4><span>' . $trans_first_name . '<br>' . $trans_last_name . '<br>' . $trans_email . '<br>' . $trans_phone_number . '<br>' . $trans_address . '<span></h4></li>';
	    $output .= '			</ul>';
	    $output .= '    	</div>';
	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_order_type') . '</strong><span>' . $order_type . '</span></h3>';
	    $output .= '    	</div>';
	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_order_owner') . '</strong><span>' . $wp_dp_transaction_user . '</span></h3>';
	    $output .= '    	</div>';

	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_total_amount') . '</strong><span>' . wp_dp_get_order_currency($wp_dp_transaction_amount, $wp_dp_currency, $wp_dp_currency_position) . '</span></h3>';
	    $output .= '    	</div>';
	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_payment_gateway') . '</strong><span>' . $gateways[$wp_dp_transaction_pay_method] . '</span></h3>';
	    $output .= '    	</div>';
	    $output .= '		<div class="popup-value-holder">';
	    $output .= '        	<h3><strong>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_status') . '</strong><span>' . $wp_dp_transaction_status . '</span></h3>';
	    $output .= '    	</div>';
	    $output .= '	</div>';
	    $output .= '</div>';

	    echo force_balance_tags($output);
	    wp_die();
	}

	public function wp_dp_trans_action_change() {

	    if (isset($_REQUEST)) {
		$trans_action = $_REQUEST['trans_action'];
		$post_id = $_REQUEST['post_id'];

            if (!empty($post_id) && !empty($trans_action)) {
                update_post_meta($post_id, 'wp_dp_transaction_status', $trans_action);
                $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_success');
            } else {
                $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_error');
            }
	    }

	    /*
	     * Promotion update
	     */
	    $wp_dp_trans_id = $post_id;
	    $order_type = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_type', true);

	    if ($order_type == 'promotion-order') {
		//$this->wp_dp_update_promotion_order($wp_dp_trans_id, $trans_action);
	    }
	    echo json_encode(array('msg' => $msg));
	    wp_die();
	}

	public function wp_dp_update_promotion_order($wp_dp_trans_id, $trans_action) {

	    update_post_meta($wp_dp_trans_id, 'wp_dp_transaction_status', $trans_action);
	    $order_id = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_id', true);
	    $wp_dp_promotions = get_post_meta($order_id, 'wp_dp_promotions', true);
	    $listing_id = get_post_meta($order_id, 'wp_dp_listing_id', true);
	   

	    $promotions_saved = get_post_meta($listing_id, 'wp_dp_promotions', true);
	    if (!empty($promotions_saved)) {
		$wp_dp_promotions = array_merge($promotions_saved, $wp_dp_promotions);
	    }

	    update_post_meta($listing_id, 'wp_dp_promotions', $wp_dp_promotions);
	    update_post_meta($order_id, 'wp_dp_order_status', $trans_action);

	    if (!empty($wp_dp_promotions)) {
		foreach ($wp_dp_promotions as $promotion_key => $promotion_array) {
		    update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key, 'on');
		    update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key . '_expiry', $promotion_array['expiry']);
		}
	    }
	}

	public function wp_dp_admin_filters() {
	    global $wp_dp_form_fields, $post_type;
	    if ($post_type == 'wp-dp-trans') {
		$order_owner = isset($_GET['order_owner']) ? $_GET['order_owner'] : '';
		$wp_dp_opt_array = array(
		    'id' => 'order_owner',
		    'cust_name' => 'order_owner',
		    'std' => $order_owner,
		    'classes' => 'filter-order-owner',
		    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_transaction_order_owner_filter') . '"',
		    'return' => false,
		    'force_std' => true,
		);
		$wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

		$selected_status = isset($_GET['tansaction_status']) ? $_GET['tansaction_status'] : '';
		$status_options = array(
		    '' => wp_dp_plugin_text_srt('wp_dp_notif_select_status'),
		    'pending' => wp_dp_plugin_text_srt('wp_dp_notif_status_pending'),
		    'in-process' => wp_dp_plugin_text_srt('wp_dp_notif_status_process'),
		    'approved' => wp_dp_plugin_text_srt('wp_dp_notif_status_approved'),
		    'cancelled' => wp_dp_plugin_text_srt('wp_dp_notif_status_cancelled'),
		);
		$wp_dp_opt_array = array(
		    'std' => $selected_status,
		    'id' => 'tansaction_status',
		    'cust_id' => 'tansaction_status',
		    'cust_name' => 'tansaction_status',
		    'extra_atr' => '',
		    'classes' => '',
		    'options' => $status_options,
		    'return' => false,
		);
		$wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
	    }
	}

	public function wp_dp_transactions_filter($query) {
	    global $pagenow;
	    $custom_filters_arr = array();
	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-trans' && isset($_GET['order_owner']) && $_GET['order_owner'] != '') {
		remove_filter('parse_query', array(&$this, 'wp_dp_transactions_filter'), 11, 1);
		$member_args = array(
		    'post_type' => 'members',
		    'posts_per_page' => -1,
		    's' => $_GET['order_owner'],
		    'fields' => 'ids'
		);
		$member_ids = get_posts($member_args);
		wp_reset_postdata();
		add_filter('parse_query', array(&$this, 'wp_dp_transactions_filter'), 11, 1);

		if (empty($member_ids)) {
		    $member_ids = array(0);
		}
		$custom_filters_arr[] = array(
		    'key' => 'wp_dp_transaction_user',
		    'value' => $member_ids,
		    'compare' => 'IN'
		);
	    }

	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-trans' && isset($_GET['tansaction_status']) && $_GET['tansaction_status'] != '') {
		if ($_GET['tansaction_status'] == 'pending') {
		    $custom_filters_arr['meta_query'] = array(
			'relation' => 'OR',
			array(
			    'key' => 'wp_dp_transaction_status',
			    'value' => '',
			    'compare' => 'NOT EXISTS'
			),
			array(
			    'key' => 'wp_dp_transaction_status',
			    'value' => $_GET['tansaction_status'],
			    'compare' => '='
			)
		    );
		} else {
		    $custom_filters_arr[] = array(
			'key' => 'wp_dp_transaction_status',
			'value' => $_GET['tansaction_status'],
			'compare' => '='
		    );
		}
	    }
	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-trans' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
		$date_query = [];
		$date_query[] = array(
		    'year' => substr($_GET['m'], 0, 4),
		    'month' => substr($_GET['m'], 4, 5),
		);
		$query->set('date_query', $date_query);
	    }
	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-trans' && !empty($custom_filters_arr)) {
		$query->set('meta_query', $custom_filters_arr);
	    }
	}

	public function transactions_init() {
	    // Initialize Post Type
	    $this->transactions_register();
	}

	public function transactions_register() {
	    $labels = array(
		'name' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_name'),
		'menu_name' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_menu_name'),
		'edit_item' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_edit_item'),
		'add_new' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_add_new'),
		'view_item' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_view_item'),
		'search_items' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_search_item'),
		'not_found' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_not_found'),
		'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_not_found_trash'),
		'parent_item_colon' => ''
	    );
	    $args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true,
		'query_var' => false,
		'menu_icon' => 'dashicons-admin-post',
		'show_in_menu' => 'edit.php?post_type=packages',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array(''),
		'capabilities' => array(
		    'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
		),
		'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
	    );
	    register_post_type('wp-dp-trans', $args);
	}

	public function disable_new_posts_capability_callback() {
	    global $post;

	    // Hide link on listing page.
	    if (get_post_type() == 'wp-dp-trans') {
		?>
		<style type="text/css">
		    .wrap .page-title-action, 
		    #edit-slug-box, 
		    .submitbox .preview.button,
		    .submitbox .misc-pub-visibility,
		    .submitbox .edit-timestamp,
		    .metabox-prefs:first-child{
			display:none;
		    }
		    .wp-dp-trans-gateway-logo{
			max-width: 20px;
			margin-right: 10px;
		    }
		    /**.subsubsub .publish */
		</style>
		<?php
	    }
	}

	public function wp_dp_remove_views($views) {
	    unset($views['publish']);
	    unset($views['mine']);


	    return $views;
	}

	/**
	 * Start Function  how create add meta boxes of transactions
	 */
	public function transactions_admin_init() {
	    // Add metaboxes
	    add_action('add_meta_boxes', array(&$this, 'wp_dp_meta_transactions_add'));
	}

	public function wp_dp_meta_transactions_add() {
	    add_meta_box('wp_dp_meta_transactions', wp_dp_plugin_text_srt('wp_dp_transaction_post_type_trans_options'), array(&$this, 'wp_dp_meta_transactions'), 'wp-dp-trans', 'normal', 'high');
	}

	public function wp_dp_meta_transactions($post) {
	    global $gateways, $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options, $post;
	    $post_id = $post->ID;
	    $wp_dp_users_list = array('' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_slct_pblisher'));
	    $args = array('posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC');
	    $cust_query = get_posts($args);
	    if (is_array($cust_query) && sizeof($cust_query) > 0) {
		foreach ($cust_query as $package_post) {
		    if (isset($package_post->ID)) {
			$package_id = $package_post->ID;
			$package_title = $package_post->post_title;
			$wp_dp_users_list[$package_id] = $package_title;
		    }
		}
	    }

	    $object = new WP_DP_PAYMENTS();
	    $payment_geteways = array();
	    $payment_geteways[''] = wp_dp_plugin_text_srt('wp_dp_transaction_post_type_slct_pay_gateway');
	    $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
	    $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options);


	    foreach ($gateways as $key => $value) {
		$status = $wp_dp_gateway_options[strtolower($key) . '_status'];
		if (isset($status) && $status == 'on') {
		    $payment_geteways[$key] = $value;
		}
	    }

	    if (isset($wp_dp_gateway_options['wp_dp_use_woocommerce_gateway']) && $wp_dp_gateway_options['wp_dp_use_woocommerce_gateway'] == 'on') {
		if (class_exists('WooCommerce')) {
		    unset($payment_geteways);
		    $payment_geteways[''] = wp_dp_plugin_text_srt('wp_dp_transaction_slct_paymnt_gateway');
		    $gateways = WC()->payment_gateways->get_available_payment_gateways();
		    foreach ($gateways as $key => $gateway_data) {
			$payment_geteways[$key] = $gateway_data->method_title;
		    }
		}
	    }

	    $order_type = get_post_meta($post_id, 'wp_dp_transaction_order_type', true);
	    $promotions = get_post_meta($post_id, 'wp_dp_promotions', true);


	    $transaction_meta = array();
	    $transaction_meta['transaction_id'] = array(
		'name' => 'transaction_id',
		'type' => 'hidden_label',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_slct_paymnt_gateway'),
		'description' => '',
	    );
	    if ($order_type != 'promotion-order') {
		$transaction_meta['transaction_order_id'] = array(
		    'name' => 'transaction_order_id',
		    'type' => 'hidden_label',
		    'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_order_id'),
		    'description' => '',
		);
	    } else {
		$transaction_meta['listing_id'] = array(
		    'name' => 'listing_id',
		    'type' => 'hidden_label',
		    'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_listing_id'),
		    'description' => '',
		);


		$transaction_meta['transaction_promotions'] = array(
		    'name' => 'transaction_promotions',
		    'type' => 'promotion_summary',
		    'title' => wp_dp_plugin_text_srt('wp_dp_transaction_promotions'),
		    'description' => '',
		);
	    }
	    $transaction_meta['transaction_summary'] = array(
		'name' => 'transaction_summary',
		'type' => 'summary',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_summary'),
		'description' => '',
	    );

	    $transaction_meta['transaction_order_type'] = array(
		'name' => 'transaction_order_type',
		'type' => 'hidden_label',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_order_type'),
		'description' => '',
	    );


	    $transaction_meta['transaction_user'] = array(
		'name' => 'transaction_user',
		'type' => 'hidden_label',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_user'),
		'description' => '',
	    );


	    $transaction_meta['transaction_amount'] = array(
		'name' => 'transaction_amount',
		'type' => 'hidden_label',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_amount'),
		'description' => '',
	    );

	    $transaction_meta['transaction_pay_method'] = array(
		'name' => 'transaction_pay_method',
		'type' => 'hidden_label',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_payment_gateway'),
		'description' => '',
	    );

	    $transaction_meta['transaction_status'] = array(
		'name' => 'transaction_status',
		'type' => 'select',
		'classes' => 'chosen-select-no-single',
		'title' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status'),
		'options' => array(
		    'pending' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_pending'),
		    'in-process' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_in_process'),
		    'approved' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_approved'),
		    'cancelled' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_cancelled')
		),
		'description' => '',
	    );

	    $html = '
			<div class="page-wrap">
			<div class="option-sec" style="margin-bottom:0;">
			<div class="opt-conts">
			<div class="wp-dp-review-wrap">';

	    foreach ($transaction_meta as $key => $params) {
		$html .= wp_dp_create_transactions_fields($key, $params);
	    }

	    $html .= '
			</div>
			</div>
			</div>';
	    $wp_dp_opt_array = array(
		'std' => '1',
		'id' => 'transactions_form',
		'cust_name' => 'transactions_form',
		'cust_type' => 'hidden',
		'return' => true,
	    );
	    $html .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
	    $html .= '
		          <div class="clear"></div>
			</div>';
	    echo force_balance_tags($html);
	}

	public function wp_dp_remove_post_boxes() {
	    remove_meta_box('mymetabox_revslider_0', 'wp-dp-trans', 'normal');
	}

    }

    /**
     * End Function  how create add meta boxes of transactions
     */
    return new post_type_transactions();
}
