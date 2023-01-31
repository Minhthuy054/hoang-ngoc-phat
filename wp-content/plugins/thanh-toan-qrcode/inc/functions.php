<?php
function ttqr_is_JSON(...$args) {
    if(is_array(...$args)) return true;
    json_decode(...$args);
    return (json_last_error()===JSON_ERROR_NONE);
}
function ttqr_valid_options(&$array) {
	foreach ($array as $key => &$value) {
        if (is_object($value)) {
            unset($array[$key]);
        } elseif (is_array($value)) {
            ttqr_valid_options($value);
        }
    }
}
function ttqr_filter_options( $data) {
	return json_decode(json_encode($data),true);
}

function ttqr_generate_random_string($length = 10, $characters=null) {
	if($characters==null) $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function ttqr_getHeader(){
	$headers = array();

    $copy_server = array(
        'CONTENT_TYPE'   => 'Content-Type',
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_MD5'    => 'Content-Md5',
    );

    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $key = substr($key, 5);
            if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$key] = $value;
            }
        } elseif (isset($copy_server[$key])) {
            $headers[$copy_server[$key]] = $value;
        }
    }

    if (!isset($headers['Authorization'])) {
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = sanitize_text_field($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
        } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $headers['Authorization'] = sanitize_text_field($_SERVER['PHP_AUTH_DIGEST']);
        }
    }

    return $headers;
}

function ttqr_parse_order_id($des, $prefix, $insensitive){
	//TODO : Rewrite this function.
	//phân biệt
	if ($insensitive=='yes') {
		$re = '/'.$prefix.'\d+/m';
	}else{
		$re = '/'.$prefix.'\d+/mi';	//$this->get_option( 'transaction_prefix' )
	}

	preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);

	if (count($matches) == 0 )
		return null;
	// Print the entire match result
	$orderCode = $matches[0][0];
	
	$prefixLength = strlen($prefix);

	$orderId = intval(substr($orderCode, $prefixLength ));
	return $orderId ;

}
function ttqr_clean_prefix($string)
{
	$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
	if (strlen($string) > 15) {
		$string = substr($string, 0, 15);
	}
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

//function ttqr
function ttqr_getCurrentDomain()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	$url = sanitize_url($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	return $url; // Outputs: Full URL

	//$query = $_SERVER['QUERY_STRING'];
	//echo $query; // Outputs: Query String
}
//@deprecated
function ttqr_get_secret($reset=false) {
	$token = get_option('secure_token');
	if(!$token || $reset) {
		$token = ttqr_generate_random_string(10);
		update_option('secure_token', $token);
	}
	return $token;
}
function ttqr_reset_token() {
	$opt = TtqrPayment::get_settings();
	if(!empty($opt['bank_transfer']['secure_token'])) {
		unset($opt['bank_transfer']['secure_token']);
		TtqrPayment::update_settings($opt);
	}
}


/*add_filter('option_ttqr', function($value, $option){
	if(file_exists(TTQR_DIR.'/banks.json')) {
		$list = file_get_contents(TTQR_DIR.'/banks.json');
		$list = json_decode($list, true);
		if(is_array($list) && is_array($value)) $value['bank_transfer_accounts'] = $list;
	}
	return $value;
}, 10,2);*/

add_action( 'rest_api_init', 'ttqr_rest' ); 
function ttqr_rest() {
	register_rest_route('ttqr/v1','/qrcode',array(
		'methods' => 'GET',
		'callback' => 'ttqr_rest_qrcode',
    	'permission_callback'=>'__return_true'
	));
}

function ttqr_rest_qrcode() {
	include TTQR_DIR."/lib/phpqrcode/qrlib.php";
	$app = isset($_GET['app'])? sanitize_text_field($_GET['app']): '';
	$phone = isset($_GET['phone']) ?   sanitize_text_field($_GET['phone']) : "";
	$price = isset($_GET['price']) ?   sanitize_text_field($_GET['price']) : "";
	$content = isset($_GET['content'])? sanitize_text_field($_GET['content']): '';
	//filter_var(, FILTER_SANITIZE_STRING)
	if($phone && $price){
		if($app=='momo') {
			$text = sprintf("2|99|%s|||0|0|%d", $phone, $price);
			$img = TTQR_DIR.'/assets/momo.png';
			QRcode::png($text, false, QR_ECLEVEL_Q, 10); 
		}
		if($app=='viettelpay') {
			$text = json_encode([
				"bankCode"=>'VTT',
				"bankcodeList"=>["VTT"],
				"cust_mobile"=>$phone,
				'transAmountList'=>[$price],
				"trans_amount"=> $price,
				'trans_content'=> $content,
				"transfer_type"=>"MYQR",
			]);
			QRcode::png($text,false, QR_ECLEVEL_Q, 10); 
		}
	}else{
		$name = plugin_dir_path( __FILE__ ) . 'assets/qr-fail.png';
		$fp = fopen($name, 'rb');

		header("Content-Type: image/png");
		header("Content-Length: " . filesize($name));

		fpassthru($fp);
	}
	
	
	die();
}

add_filter( 'wp_kses_allowed_html', function($allowed_html){
	$atts = array(
		'class' => array(),
		'href'  => array(),
		'rel'   => array(),
		'title' => array(),
		'onclick'=>array(),'value'=>array(),'src'=>array(),
		'name'=>array(),'id'=>array(),'style'=>array(),'type'=>array(),'class'=>array(),
	);
	$allowed_html['style'] = $atts;
	$allowed_html['script'] = $atts;
	foreach(['button','img'] as $tag) $allowed_html[$tag] = $atts;
	return $allowed_html;
}, 999999);

add_filter( 'safe_style_css', function( $styles ) {
    $styles[] = 'display';
    return $styles;
} );

/**
 * admin columns
*/
add_action('woocommerce_admin_order_data_after_shipping_address', function($order){
    //$order_data = $order->get_data();
    $payment = $order->get_payment_method();
    $content = $order->get_meta('ttqr_ndck');

    $ui = '<div>';
    $ui.= TtqrPayment::get_bank_icon(WC_Base_Ttqr::payment_name($payment),true);
    if($content) $ui.= '<div><code>'.$content.'</code></div>';
    $ui.= '</div>';

    echo wp_kses_post($ui);
});
/*
add_action('woocommerce_order_details_before_order_table', function($order){
    if( $order->get_status()!='completed' ) return;
    $payment = $order->get_payment_method();
    $content = $order->get_meta('ttqr_ndck');	//$content='ttqr123';
    $ui='';
    if($content) {
    	$ui.= '<div class="ttqr-order-detail">';
	    $ui.= '<div class="ttqr-col-left">'.TtqrPayment::get_bank_icon(WC_Base_Ttqr::payment_name($payment),true).'</div>';
	    $ui.= '<div class="ttqr-col-right">'.$content.'</div>';
	    $ui.= '</div>';
    }
	
	echo wp_kses_post( $ui );
});
*/
add_filter('woocommerce_my_account_my_orders_columns', function($columns){
	$new_columns = array();
	$i=0;$n = count($columns);
	foreach($columns as $id=> $text) {
		
		if(++$i==$n) {
			$new_columns['ttqr_bank'] = __('Bank', 'ttqr');
		}
		$new_columns[$id] = $text;
	}
	
	return $new_columns;
	
},20);

add_action('woocommerce_my_account_my_orders_column_ttqr_bank', function( $order ){
	$payment = $order->get_payment_method();
	if($payment) echo TtqrPayment::get_bank_icon(WC_Base_Ttqr::payment_name($payment),true);
}, 20 );

add_filter( 'manage_edit-shop_order_columns', function($columns){
	$new_columns = array();
	$i=0;$n = count($columns);
	foreach($columns as $id=> $text) {
		
		if(++$i==$n-1) {
			$new_columns['ttqr_bank'] = __('Bank', 'ttqr');
		}
		$new_columns[$id] = $text;
	}
	
	return $new_columns;
}, 20 );

add_action( 'manage_shop_order_posts_custom_column' , function($column, $post_id){
	if($column=='ttqr_bank') {
		$order = wc_get_order($post_id);
		$payment = $order->get_payment_method();
		if($payment) {
			printf('<a href="%s" target="_blank">%s</a>', $order->get_checkout_order_received_url(), TtqrPayment::get_bank_icon(WC_Base_Ttqr::payment_name($payment),true));
		}
	}

}, 20, 2 );

add_action( 'admin_notices', function () {
	global $pagenow;
	if($pagenow=='admin.php' && $_GET['page']=='ttqr') {//is-dismissible 
    ?>
    <div class="notice notice-success ttqr-notice">
    	<h3>Thanh Toán Quét Mã QR Code Tự Động - MoMo, ViettelPay, VNPay và 40 ngân hàng Việt Nam</h3>
    	<div style="display: table-cell;width: 65%;">
    	<ul>
    		<li>Không cần giấy phép kinh doanh.</li>
    		<li><b >Không yêu cầu nhập user/pass hay mã OTP, an toàn tuyệt đối !</b><br>
    			<b style="font-style: italic;color: #FFA500;display: none">**Cảnh giác: Không đăng nhập user/pass hay mã OTP cho bất cứ dịch vụ không chính thống. Bạn luôn được các ngân hàng khuyến cáo vì sẽ lộ thông tin và bị chiếm quyền truy cập tài khoản..</b>
    		</li>
    		<li>Hỗ trợ QR code tự nhập tiền và nội dung đơn hàng (API tiêu chuẩn của Napas)</li>
    		<li><b>Xác nhận thanh toán tự động & kích hoạt đơn hàng từ 1~3 giây</b>.</li>
    		<li>Xử lý đa luồng, không giới hạn số lượng giao dịch.</li>
    		
    	</ul>
    	<strong style="text-decoration: underline;font-size: 18px">Yêu cầu:</strong>
    	<ul>
    		<li>Tải app Ttqr trên Google Play để xác nhận thanh toán tự động. <a href="https://bck.haibasoft.com/help.html" target="_blank">Xem hướng dẫn</a></li>
    	</ul>

    	<p>Với 1 điện thoại cá nhân, bạn tích hợp <b style="color:red">KHÔNG GIỚI HẠN</b> website & tài khoản ngân hàng.</p>
    	
    	<p style="font-size: 18px;"><span style="">❤️ Trải nghiệm Miễn phí 1 ngày</span>, <a href="https://bck.haibasoft.com/#banggia" target="_blank"><b>Đăng ký</b></a> để sử dụng 1 năm hoặc vĩnh viễn (Zalo hỗ trợ: 0868.292.303)</p>
    	</div>
    	<div style="display: table-cell;position: relative;">
    	<iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 90%;" width="824" height="464" src="https://www.youtube.com/embed/gWEuOxYW_mk" title="Plugin thanh toán MoMo, ViettelPay, Vietcombank, MB.. dành cho cá nhân" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    	</div>
    </div>
    <?php
	}
}, 9999999);

//test
//if(file_exists(dirname(__DIR__).'/test/test.func.php')) include dirname(__DIR__).'/test/test.func.php';