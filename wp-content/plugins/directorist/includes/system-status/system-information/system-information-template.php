<?php
global $wpdb;
$environment          = $this->get_environment_info();
$database             = $this->get_database_info();
$post_type_counts     = $this->get_post_type_counts();
$security             = $this->get_security_info();
$active_plugins       = $this->get_active_plugins();
$theme                = $this->get_Theme_info();
$php_information      = $this->php_information();

$atbdp_option       = get_option( 'atbdp_option' );
?>
<div class="tab-pane active show" id="atbds_system-info">
    <div class="card atbds_card">
        <div class="card-head">
            <h4><?php esc_html_e( 'System Information', 'directorist' ); ?></h4>
        </div>
        <div class="card-body">
            <div class="atbds_content__tab">
                <div class="atbds_c-t-menu">
                    <ul class="nav" id="atbds_ststus-tab" role="tablist">
                        <li class="nav-item">
                            <a href="#atbds_system-wp" class="nav-link active" id="atbds_system-info-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Wordpress Environment', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_php" class="nav-link" id="atbds_php-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'PHP', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_server" class="nav-link" id="atbds_server-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Server Environment', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_platform" class="nav-link" id="atbds_platform-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'User Platform', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_setting" class="nav-link" id="atbds_setting-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Settings', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_database" class="nav-link" id="atbds_database-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Database', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_p-count" class="nav-link" id="atbds_p-count-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Post Type Counts', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_sequrity" class="nav-link" id="atbds_sequrity-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Security', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_plugins" class="nav-link" id="atbds_plugins-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Active Plugins', 'directorist' ); ?> <span class="atbds__pl-count">(<?php echo count( $active_plugins ); ?>)</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_theme" class="nav-link" id="atbds_theme-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Theme', 'directorist' ); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#atbds_template" class="nav-link" id="atbds_theme-tab" data-tabArea="atbds_system-info-tab"><?php esc_html_e( 'Templates', 'directorist' ); ?></a>
                        </li>
                    </ul>
                </div><!-- ends: .atbds_c-t-menu -->
                <div class="atbds_c-t__details">
                    <div class="tab-content" data-tabArea="atbds_system-info-tab">
                        <div class="tab-pane active show" id="atbds_system-wp">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Home URL', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The homepage URL of your site.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span class="atbds_url"><?php echo esc_html( $environment['home_url'] ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Site URL', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The root URL of your site.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span class="atbds_url"><?php echo esc_html( $environment['site_url'] ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Directorist version', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The version of GeoDirectory installed on your site.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( $environment['version'] ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'WP version', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The version of WordPress installed on your site.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( $environment['wp_version'] ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'WP Multisite', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Whether or not you have WordPress Multisite enabled.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'WP Memory Limit', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The maximum amount of memory (RAM) that your site can use at one time.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php
                                                if ( $environment['wp_memory_limit'] < 67108864 ) {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'directorist' ), esc_html( size_format( $environment['wp_memory_limit'] ) ), '<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'directorist' ) . '</a>' ) . '</mark>';
                                                } else {
                                                    echo '<span class="atbds_color-success">' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</span>';
                                                }
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'WP debug mode', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Displays whether or not WordPress is in Debug Mode.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php if ( $environment['wp_debug_mode'] ) : ?>
                                                    <mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
                                                <?php else : ?>
                                                    <span class="no">-</span>
                                                <?php endif; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'WP cron', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Displays whether or not WP Cron Jobs are enabled.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ( $environment['wp_cron'] ) : ?>
                                                    <span class="atbds_color-success"><i class="fa fa-check"></i></span>
                                                <?php else : ?>
                                                    <mark class="no">&ndash;</mark>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'New User Default Role', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The default role of new user.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html( $environment['default_role'] ) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Language', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The current language used by WordPress. Default = English', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html( $environment['language'] ) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_php">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <?php foreach ( $php_information as $item => $value ) { ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php echo esc_html( $item ); ?>:</td>
                                                <td><?php echo wp_kses_post( $value ); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="atbds_server">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Server environment', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Information about the web server that is currently hosting your site.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html( $environment['server_info'] ); ?></td>
                                        </tr>
                                        <?php
                                        $ver = $wpdb->db_server_info();
                                        if ( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) : ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php esc_html_e( 'MySQL version', 'directorist' ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The version of MySQL installed on your hosting server.', 'directorist' ); ?>">
                                                        <i class="fa fa-question-circle"></i>
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ( version_compare( $environment['mysql_version'], '5.6', '<' ) ) {
                                                        echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf(
                                                            /* translators: %s: version */
                                                            esc_html__( '%1$s - We recommend a minimum MySQL version of 5.6. See: %2$s', 'directorist' ),
                                                            esc_html( $environment['mysql_version'] ),
                                                            '<a href="https://wordpress.org/about/requirements/" target="_blank">' . esc_html__( 'WordPress requirements', 'directorist' ) . '</a>'
                                                            ) . '</mark>';
                                                    } else {
                                                        echo '<span>' . esc_html( $environment['mysql_version'] ) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Max upload size', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The largest filesize that can be uploaded to your WordPress installation.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( size_format( $environment['max_upload_size'] ) ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Default timezone is UTC', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The default timezone for your server.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ( 'UTC' !== $environment['default_timezone'] ) {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'directorist' ), esc_html( $environment['default_timezone'] ) ) . '</mark>';
                                                } else {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'fsockopen/cURL', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php
                                                if ( $environment['fsockopen_or_curl_enabled'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'directorist' ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'SoapClient', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php
                                                if ( $environment['soapclient_enabled'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - some gateway plugins which use SOAP may not work as expected.', 'directorist' ), '<a href="https://php.net/manual/en/class.soapclient.php">SoapClient</a>' ) . '</mark>';
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'DOMDocument', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $environment['domdocument_enabled'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'directorist' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'GZip', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php
                                                if ( $environment['gzip_enabled'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'directorist' ), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>' ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Multibyte string', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $environment['mbstring_enabled'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'directorist' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Remote post', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'PayPal uses this method of communicating when sending back transaction information.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $environment['remote_post_successful'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_post()' ) . ' ' . esc_html( $environment['remote_post_response'] ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Remote get', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Directorist plugin may use this method of communication when checking for plugin updates.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $environment['remote_get_successful'] ) {
                                                    echo '<span class="atbds_color-success"><i class="fa fa-check"></i></span>';
                                                } else {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_get()' ) . ' ' . esc_html( $environment['remote_get_response'] ) . '</mark>';
                                                } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_platform">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Platform', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html__( $environment['platform'] ); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Browser name', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo esc_html( $environment['browser_name'] ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Browser version', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( $environment['browser_version'] ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'User agent', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( $environment['user_agent'] ); ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_setting">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <?php
                                        if ( ! empty( $atbdp_option ) ) :
                                            foreach ( $atbdp_option as $name => $value ) {
                                        ?>
                                                <tr>
                                                    <td class="atbds_table-title"><?php echo esc_html( ! empty( $name ) ? $name : '' ); ?>:</td>
                                                    <td class="atbds_table-pointer">
                                                        <span class="atbd_tooltip">
                                                            <span class="atbd_tooltip__text"></span>
                                                        </span>
                                                    </td>
                                                    <td><?php print_r( $value ); ?></td>
                                                </tr>
                                        <?php
                                            }
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_database">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Database prefix', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php
                                                if ( strlen( $database['database_prefix'] ) > 20 ) {
                                                    echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend using a prefix with less than 20 characters.', 'directorist' ), esc_html( $database['database_prefix'] ) ) . '</mark>';
                                                } else {
                                                    echo '<span class="atbds_color-success">' . esc_html( $database['database_prefix'] ) . '</span>';
                                                }
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Total Database Size', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php printf( '%.2fMB', esc_attr( $database['database_size']['data'] ) + esc_attr( $database['database_size']['index'] ) ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Database Data Size', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php printf( '%.2fMB', esc_attr( $database['database_size']['data'] ) ); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e('Database Index Size', 'directorist'); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip">
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php printf( '%.2fMB', esc_attr( $database['database_size']['index'] ) ); ?></span></td>
                                        </tr>
                                        <?php foreach ( $database['database_tables']['directorist'] as $table => $table_data ) { ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php echo esc_html( $table ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip">
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ( ! $table_data ) {
                                                        echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Table does not exist', 'directorist' ) . '</mark>';
                                                    } else {
                                                        printf( esc_html__( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), wp_kses_post( $this->directorist_help_tip( $table_data['data'], 2 ) ), wp_kses_post( $this->directorist_help_tip( $table_data['index'], 2 ) ) );
                                                    } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php foreach ( $database['database_tables']['other'] as $table => $table_data ) { ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php echo esc_html( $table ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip">
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td><?php printf( esc_html__( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), wp_kses_post( $this->directorist_help_tip( $table_data['data'], 2 ) ), wp_kses_post( $this->directorist_help_tip( $table_data['index'], 2 ) )  ); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_p-count">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <?php
                                        foreach ( $post_type_counts as $post_type ) {
                                        ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php echo esc_html( $post_type->type ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip">
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td><?php echo esc_html( absint( $post_type->count ) ); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_sequrity">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e('Secure connection (HTTPS)', 'directorist'); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Is the connection to your site secure?', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ( $security['secure_connection'] ) : ?>
                                                    <mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
                                                <?php else : ?>
                                                    <mark class="error"><span class="dashicons dashicons-warning"></span><?php echo esc_html__( 'HTTPS is not enabled on your site.', 'directorist' ); ?></mark>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Hide errors from visitors', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Error messages can contain sensitive information about your site environment. These should be hidden from untrusted visitors.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ( $security['hide_errors'] ) : ?>
                                                    <span class="atbds_color-success"><i class="fa fa-check"></i></span>
                                                <?php else : ?>
                                                    <mark class="error"><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'Error messages should not be shown to visitors.', 'directorist' ); ?></mark>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_plugins">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <?php
                                        foreach ( $active_plugins as $plugin ) {
                                            if ( ! empty( $plugin['name'] ) ) {
                                                $dirname = dirname( $plugin['plugin'] );

                                                // Link the plugin name to the plugin url if available.
                                                $plugin_name = esc_html( $plugin['name'] );
                                                if ( ! empty( $plugin['url'] ) ) {
                                                    $plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'directorist' ) . '" target="_blank">' . $plugin_name . '</a>';
                                                }

                                                $version_string = '';
                                                $network_string = '';
                                                if ( ! empty( $plugin['latest_verison'] ) && version_compare( $plugin['latest_verison'], $plugin['version'], '>' ) ) {
                                                    /* translators: %s: plugin latest version */
                                                    $version_string = ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), $plugin['latest_verison'] ) . '</strong>';
                                                }

                                                if ( false != $plugin['network_activated'] ) {
                                                    $network_string = ' &ndash; <strong style="color:black;">' . esc_html_e( 'Network enabled', 'directorist' ) . '</strong>';
                                                }
                                        ?>
                                                <tr>
                                                    <td class="atbds_table-title"><?php echo wp_kses_post( $plugin_name ); ?>:</td>
                                                    <td class="atbds_table-pointer">
                                                        <span class="atbd_tooltip">
                                                            <span class="atbd_tooltip__text"></span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        /* translators: %s: plugin author */
                                                        printf( esc_html__('by %s', 'directorist'), wp_kses_post( $plugin['author_name'] ) );
                                                        echo ' &ndash; ' . wp_kses_post( $plugin['version'] ) . wp_kses_post( $version_string ) . wp_kses_post( $network_string );
                                                        ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->
                        <div class="tab-pane" id="atbds_theme">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Name', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The name of the current active theme.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html( $theme['name'] ) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Version', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The installed version of the current active theme.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                echo esc_html( $theme['version'] );
                                                if ( version_compare( $theme['version'], $theme['version_latest'], '<' ) ) {
                                                    /* translators: %s: theme latest version */
                                                    echo ' &ndash; <strong style="color:red;">' . sprintf( esc_html__('%s is available', 'directorist'), esc_html( $theme['version_latest'] ) ) . '</strong>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e('Author URL', 'directorist'); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e('The theme developers URL.', 'directorist'); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td><span><?php echo esc_html( $theme['author_url'] ) ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="atbds_table-title"><?php esc_html_e( 'Child theme', 'directorist' ); ?>:</td>
                                            <td class="atbds_table-pointer">
                                                <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'Displays whether or not the current theme is a child theme.', 'directorist' ); ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="atbd_tooltip__text"></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $_child_theme =  $theme['is_child_theme'] ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . sprintf( __( 'If you are modifying Directorist on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'directorist' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' );

                                                echo wp_kses_post( $_child_theme );
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        if ($theme['is_child_theme']) :
                                        ?>
                                            <tr>
                                                <td class="atbds_table-title"><?php esc_html_e( 'Parent theme name', 'directorist' ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The name of the parent theme.', 'directorist' ); ?>">
                                                        <i class="fa fa-question-circle"></i>
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td><?php echo esc_html( $theme['parent_name'] ); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="atbds_table-title"><?php esc_html_e( 'Parent theme version', 'directorist' ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The installed version of the parent theme.', 'directorist' ); ?>">
                                                        <i class="fa fa-question-circle"></i>
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo esc_html( $theme['parent_version'] );
                                                    if ( version_compare( $theme['parent_version'], $theme['parent_latest_verison'], '<' ) ) {
                                                        /* translators: %s: parant theme latest version */
                                                        echo ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), esc_html( $theme['parent_latest_verison'] ) ) . '</strong>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="atbds_table-title"><?php esc_html_e( 'Parent theme author URL', 'directorist' ); ?>:</td>
                                                <td class="atbds_table-pointer">
                                                    <span class="atbd_tooltip" aria-label="<?php esc_attr_e( 'The parent theme developers URL.', 'directorist' ); ?>">
                                                        <i class="fa fa-question-circle"></i>
                                                        <span class="atbd_tooltip__text"></span>
                                                    </span>
                                                </td>
                                                <td><?php echo esc_html( $theme['parent_author_url'] ) ?></td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->


                        <div class="tab-pane" id="atbds_template">
                            <div class="atbds_system-table-wrap">
                                <table class="atbds_system-table table-responsive">
                                    <tbody>
                                        <?php 		
                                            if ( ! empty( $theme['overrides'] ) ) { ?>
                                                    <tr>
                                                        <td class="atbds_table-title"><?php esc_html_e( 'Overrides', 'directorist' ); ?></td>
                                                        <td class="atbds_table-pointer">
                                                            <span class="atbd_tooltip" aria-label="<?php esc_attr_e('This section shows any files that are overriding the default Directorist template pages.', 'directorist'); ?>">
                                                                <i class="fa fa-question-circle"></i>
                                                                <span class="atbd_tooltip__text"></span>
                                                            </span>
                                                        </td>
                                                        <td class="diretorist-table-text">
                                                            <?php
                                                            $total_overrides = count( $theme['overrides'] );
                                                            
                                                            for ( $i = 0; $i < $total_overrides; $i++ ) { ?>
                                                                <p>
                                                                <?php
                                                                    $override = $theme['overrides'][ $i ];
                                                                    if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
                                                                        $current_version = $override['version'] ? $override['version'] : '-';
                                                                        $_core_version = sprintf(
                                                                            __( '%1$s version %2$s is out of date. The core version is %3$s', 'directorist' ),
                                                                            '<code>' . esc_html( $override['file'] ) . '</code>',
                                                                            '<strong style="color:red">' . esc_html( $current_version ) . '</strong>',
                                                                            esc_attr( $override['core_version'] )
                                                                        );
                                                                        echo wp_kses_post( $_core_version );
                                                                    } else {
                                                                        echo esc_html( $override['file'] );
                                                                    }
                                                                    if ( ( count( $theme['overrides'] ) - 1 ) !== $i ) {
                                                                        echo ', ';
                                                                    }
                                                            }
                                                                ?>
                                                                </p>
                                                        </td>
                                                    </tr>
                                                    <?php
                                            } else {
                                                ?>
                                                <tr>
                                                    <td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'directorist' ); ?>:</td>
                                                    <td class="help">&nbsp;</td>
                                                    <td>&ndash;</td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- ends: .tab-pane -->




                    </div>
                </div><!-- ends: .atbds_c-t__details -->
            </div>
        </div>
    </div>
</div>