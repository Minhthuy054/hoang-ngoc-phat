<?php
echo '9999999999999';
die;
require_once plugin_dir_url( __FILE__ ) . 'apiClient.php';
require_once plugin_dir_url( __FILE__ ) . 'contrib/apiOauth2Service.php';
global $cs_theme_options;
//$cs_theme_options = get_option('cs_theme_options');

$client = new apiClient();
$client->setClientId($cs_theme_options['cs_google_client_id']);
$client->setClientSecret($cs_theme_options['cs_google_client_secret']);
$client->setDeveloperKey($cs_theme_options['cs_google_api_key']);
$client->setRedirectUri(cs_google_login_url());
$client->setApprovalPrompt('auto');

$oauth2 = new apiOauth2Service($client);
