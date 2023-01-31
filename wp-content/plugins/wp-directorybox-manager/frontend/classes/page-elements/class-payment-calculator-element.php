<?php
/**
 * File Type: Payment Calculator Listing Page Element
 */
if ( ! class_exists('wp_dp_payment_calculator_element') ) {

    class wp_dp_payment_calculator_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_payment_calculator_html', array( $this, 'wp_dp_payment_calculator_html_callback' ), 11, 1);
        }

        public function wp_dp_payment_calculator_html_callback($listing_id = '') {
            global $post, $wp_dp_form_fields_frontend, $wp_dp_plugin_options;
			
            wp_enqueue_script('wp_dp_piechart_frontend');
			
			
            $sidebar_mortgage_calculator = wp_dp_element_hide_show($listing_id, 'sidebar_mortgage_calculator');
            if( $sidebar_mortgage_calculator != 'on' ){
                    return;
            }
            
            $wp_dp_mortgage_static_text_block = isset($wp_dp_plugin_options['wp_dp_mortgage_static_text_block']) ? $wp_dp_plugin_options['wp_dp_mortgage_static_text_block'] : '';

            $wp_dp_mortgage_min_year = isset($wp_dp_plugin_options['wp_dp_mortgage_min_year']) && ! empty($wp_dp_plugin_options['wp_dp_mortgage_min_year']) ? $wp_dp_plugin_options['wp_dp_mortgage_min_year'] : '2';
            $wp_dp_mortgage_max_year = isset($wp_dp_plugin_options['wp_dp_mortgage_max_year']) && ! empty($wp_dp_plugin_options['wp_dp_mortgage_max_year']) ? $wp_dp_plugin_options['wp_dp_mortgage_max_year'] : '10';
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {
                $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                $wp_dp_listing_price = '';
                if ( $wp_dp_listing_price_options == 'price' ) {
                    $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                    return;
                    $wp_dp_listing_price = 0;
                } else if ( $wp_dp_listing_price_options == 'none' ) {
                    return;
                    $wp_dp_listing_price = 0;
                }
                if ( ! is_numeric($wp_dp_listing_price) ) {
                    $wp_dp_listing_price = 0;
                }
                ?>
                <div class="widget widget-payment-sec pd0">
                    <?php
                    $default_deposit_price = '';
                    if ( isset($wp_dp_listing_price) && $wp_dp_listing_price >= 0 ) {
                        $default_deposit_price = $wp_dp_listing_price / 2;
                    }
                    $default_listing_price = 0;
                    $default_deposit_pricee = 0;
                    $default_annual_int = 0;
                    $default_annual_min_year = 0;
                    $default_annual_max_year = 0;
                    if ( isset($wp_dp_listing_price) && $wp_dp_listing_price != '' && $wp_dp_listing_price > 0 ) {
                        $default_listing_price = $wp_dp_listing_price;
                        $default_deposit_pricee = $default_deposit_price;
                        $default_annual_int = 10;
                        $default_annual_min_year = $wp_dp_mortgage_min_year;
                        $default_annual_max_year = $wp_dp_mortgage_max_year;
                    }
                    
                     $currency_sign = wp_dp_get_currency_sign();
                    
                    if ( $wp_dp_listing_price_options == 'price' ) {
                    
                    
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            var price_pie = <?php echo esc_html($wp_dp_listing_price); ?>;
                            var deposit_value = <?php echo esc_html($default_deposit_pricee); ?>;
                            var year_pie = <?php echo esc_html($default_annual_min_year); ?>;
                            var annual_interest = (price_pie * year_pie) / 100;
                            var total_interest = price_pie + annual_interest + deposit_value;
                            var price_percentage = ((price_pie / total_interest) * 100);
                            var deposite_percentage = ((deposit_value / total_interest) * 100);
                            var interest_percentage = ((annual_interest / total_interest) * 100);
                            function get_data() {
                                var out = [
                                    {"name": "price", "hvalue": price_percentage, "color": "#5a2e8a"},
                                    {"name": "deposit", "hvalue": deposite_percentage, "color": "#d64521"},
                                    {"name": "interest", "hvalue": interest_percentage, "color": "#555555"},
                                ]
                                return out;
                            }
                            doCalc();
                            jQuery("#chartContainer").donutpie({
                                radius: 140,
                                tooltip: true,
                            });
                            jQuery("#chartContainer").donutpie('update', get_data());
                        });
                    </script>
                    <?php } ?>
                    <div class="widget-payment-holder">
                        <script type="text/javascript">
                            function doCalc(button_loader)
                            {
                                var thisObj = jQuery('.get-btn');
                                if (button_loader != '' && button_loader != 'undefined' && button_loader != undefined && button_loader == 'show-loader-btn') {
                                    wp_dp_show_loader('.get-btn', '', 'button_loader', thisObj);
                                }
                                zeroBlanks(document.mortform);
                                var down_payment = numval(document.mortform.down_payment.value);
                                var p = numval(document.mortform.p.value);
                                p = p - down_payment;
                                var rate_of_interest = numval(document.mortform.r.value);
                                var r = (numval(document.mortform.r.value) / 100);
                                var y = numval(document.mortform.y.value);

                                setTimeout(function () {
                                    var total = formatNumber(mortgagePayment(p, r / 12, y * 12), 2);
                                    var currency_sign = '<?php  echo esc_html($currency_sign); ?>';
                                    jQuery(".totoal_price").html(currency_sign+total + ' / <small><?php echo wp_dp_plugin_text_srt('wp_dp_mortgage_calculator_month'); ?></small>');
                                    if (total > 0) {
                                        jQuery("#demo-pie-1").attr("data-percent", parseInt(total));
                                        var price_pie = p;
                                        var deposit_value = down_payment;
                                        var annual_interest = (price_pie * y) / 100;
                                        var total_interest = price_pie + annual_interest + deposit_value;
                                        var price_percentage = ((price_pie / total_interest) * 100);
                                        var deposite_percentage = ((deposit_value / total_interest) * 100);
                                        var interest_percentage = ((annual_interest / total_interest) * 100);
                                        function get_data() {
                                            var out = [
                                                {"name": "price", "hvalue": price_percentage, "color": "#5a2e8a"},
                                                {"name": "deposit", "hvalue": deposite_percentage, "color": "#d64521"},
                                                {"name": "interest", "hvalue": interest_percentage, "color": "#555555"},
                                            ]
                                            return out;
                                        }
                                        $("#chartContainer").donutpie('update', get_data());
                                    }

                                }, 1000);
                                setTimeout(function () {
                                    wp_dp_show_response('', '', thisObj);
                                }, 500);
                            }
                            function zeroBlanks(formname)
                            {
                                var i, ctrl;
                                for (i = 0; i < formname.elements.length; i++)
                                {
                                    ctrl = formname.elements[i];
                                    if (ctrl.type == "text")
                                    {
                                        if (makeNumeric(ctrl.value) == "")
                                            ctrl.value = "0";
                                    }
                                }
                            }
                            function filterChars(s, charList)
                            {
                                var s1 = "" + s; // force s1 to be a string data type
                                var i;
                                for (i = 0; i < s1.length; )
                                {
                                    if (charList.indexOf(s1.charAt(i)) < 0)
                                        s1 = s1.substring(0, i) + s1.substring(i + 1, s1.length);
                                    else
                                        i++;
                                }
                                return s1;
                            }
                            function makeNumeric(s)
                            {
                                return filterChars(s, "1234567890.-");
                            }
                            function numval(val, digits, minval, maxval)
                            {
                                val = makeNumeric(val);
                                if (val == "" || isNaN(val))
                                    val = 0;
                                val = parseFloat(val);
                                if (digits != null)
                                {
                                    var dec = Math.pow(10, digits);
                                    val = (Math.round(val * dec)) / dec;
                                }
                                if (minval != null && val < minval)
                                    val = minval;
                                if (maxval != null && val > maxval)
                                    val = maxval;
                                return parseFloat(val);
                            }
                            function formatNumber(val, digits, minval, maxval)
                            {
                                var sval = "" + numval(val, digits, minval, maxval);
                                var i;
                                var iDecpt = sval.indexOf(".");
                                if (iDecpt < 0)
                                    iDecpt = sval.length;
                                if (digits != null && digits > 0)
                                {
                                    if (iDecpt == sval.length)
                                        sval = sval + ".";
                                    var places = sval.length - sval.indexOf(".") - 1;
                                    for (i = 0; i < digits - places; i++)
                                        sval = sval + "0";
                                }
                                var firstNumchar = 0;
                                if (sval.charAt(0) == "-")
                                    firstNumchar = 1;
                                for (i = iDecpt - 3; i > firstNumchar; i -= 3)
                                    sval = sval.substring(0, i) + "," + sval.substring(i);

                                return sval;
                            }
                            function mortgagePayment(p, r, y)
                            {
                                return futureValue(p, r, y) / geomSeries(1 + r, 0, y - 1);
                            }
                            function futureValue(p, r, y)
                            {
                                return p * Math.pow(1 + r, y);
                            }
                            function geomSeries(z, m, n)
                            {
                                var amt;
                                if (z == 1.0)
                                    amt = n + 1;
                                else
                                    amt = (Math.pow(z, n + 1) - 1) / (z - 1);
                                if (m >= 1)
                                    amt -= geomSeries(z, 0, m - 1);
                                return amt;
                            }
                        </script>
                        <form name="mortform" action="#" method="post">
                            <div class="progress-holder">
                                <div class="chartContainer-wrp">
                                    <div id="chartContainer" style="height: 102px; width: 100%;"></div>
                                </div>
                                <div class="text-holder">
                                    
                                    
                                    <span><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_your_payment'); ?></span>
                                    <span class="price totoal_price"><?php echo force_balance_tags(wp_dp_get_currency($wp_dp_listing_price, true)); ?> / <small><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_mo'); ?></small></span>
                                    <ul>
                                        <li><span style="background-color:#67237a;"></span><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_your_price'); ?></li>
                                        <li><span style="background-color:#d64521;"></span><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_your_deposit'); ?></li>
                                        <li><span style="background-color:#555555;"></span><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_your_interest'); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="range-slider-holder">
                                <div class="range-slider">
                                    <label>
                                        <span class="title"><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_listing_price'); ?></span>
                                    </label>
                                    <span id="#ex5SliderVal" class="price"><?php echo wp_dp_get_currency_sign(); ?><small class="slider-value"> <?php echo esc_html($default_listing_price); ?></small></span>
                                    <?php
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                            array(
                                                'cust_name' => 'p',
                                                'cust_id' => 'ex2',
                                                'classes' => 'slider-field',
                                                'extra_atr' => ' data-slider-id="ex2Slider" data-slider-min="0" data-slider-step="1" data-slider-max="' . wp_dp_get_currency($wp_dp_listing_price, false) . '"  data-slider-value="' . esc_html($wp_dp_listing_price) . '" style="display:none;"',
                                            )
                                    );
                                    ?>  
                                </div>
                                <div class="range-slider">
                                    <label>
                                        <span class="title"><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_deposit'); ?></span>
                                    </label>
                                    <span id="#ex6SliderVal" class="price"><?php echo wp_dp_get_currency_sign(); ?><small class="slider-value"><?php echo esc_html($default_deposit_pricee); ?></small></span>
                                    <?php
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                            array(
                                                'cust_name' => 'down_payment',
                                                'cust_id' => 'ex3',
                                                'classes' => 'slider-field',
                                                'extra_atr' => ' data-slider-id="ex2Slider" data-slider-min="0" data-slider-step="1" style="display:none;" data-slider-max="' . wp_dp_get_currency($wp_dp_listing_price, false) . '"  data-slider-value="' . esc_html($default_deposit_price) . '"',
                                            )
                                    );
                                    ?>  
                                </div>
                                <div class="range-slider">
                                    <label>
                                        <span class="title"><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_annual_interest'); ?></span>
                                    </label>
                                    <span id="#ex7SliderVal" class="price"><small class="slider-value"><?php echo esc_html($default_annual_int); ?></small>%</span>
                                    <?php
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                            array(
                                                'cust_name' => 'r',
                                                'cust_id' => 'ex4',
                                                'classes' => 'slider-field',
                                                'extra_atr' => ' data-slider-id="ex2Slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" style="display:none;" data-slider-value="' . esc_html($default_annual_int) . '"',
                                            )
                                    );
                                    ?>  
                                </div>
                                <div class="range-slider">
                                    <label>
                                        <span class="title"><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_year'); ?></span>
                                    </label>
                                    <span id="#ex8SliderVal" class="price"><small class="slider-value"><?php echo esc_html($default_annual_min_year); ?></small></span>
                                    <?php
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                            array(
                                                'cust_name' => 'y',
                                                'cust_id' => 'ex5',
                                                'classes' => 'slider-field',
                                                'extra_atr' => ' data-slider-id="ex2Slider" data-slider-min="' . esc_html($default_annual_min_year) . '" data-slider-max="' . esc_html($default_annual_max_year) . '" data-slider-step="1" data-slider-value="' . esc_html($default_annual_min_year) . '" style="display:none;"',
                                            )
                                    );
                                    ?>  
                                </div>
                            </div>
                            <a class="get-btn slider-field" onClick="javascript:doCalc('show-loader-btn');" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_get_loan_btn'); ?></a>
                            <span class="price totoal_price"><?php echo force_balance_tags(wp_dp_get_currency($wp_dp_listing_price, true)); ?>/<small><?php echo wp_dp_plugin_text_srt('wp_dp_payment_calculator_mo'); ?></small></span>
                        </form>
                        <?php
                        if ( isset($wp_dp_mortgage_static_text_block) && ! empty($wp_dp_mortgage_static_text_block) ) {
                            ?>
                            <p><?php echo htmlspecialchars_decode($wp_dp_mortgage_static_text_block); ?></p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }

    }

    global $wp_dp_payment_calculator;
    $wp_dp_payment_calculator = new wp_dp_payment_calculator_element();
}