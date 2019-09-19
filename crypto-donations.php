<?php
/**
 * Plugin Name: Crypto Donations
 * Plugin URI: https://www.b1ue.tech/crypto-donations/
 * Description: This plugin allows to create Crypto Donations
 * Version: 1.0
 * Author: b1ue
 * Author URI: https://www.b1ue.tech
 */

// Setup

add_action('init', 'crypto_donations_setup_shortcodes');
add_action('init', 'crypto_donations_setup_default_settings');
add_action('wp_enqueue_scripts', 'crypto_donations_add_scripts_and_styles'); 
add_action('admin_enqueue_scripts', 'wptuts_add_color_picker');

function crypto_donations_add_scripts_and_styles () {
        wp_enqueue_script('jquery');
        wp_enqueue_script('crypto-donations-bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap-4.3.1.bundle.min.js');
        wp_enqueue_script('crypto-donations-qrcode', plugin_dir_url(__FILE__) . 'js/jquery-qrcode-0.17.0.min.js');
        wp_enqueue_script('crypto-donations', plugin_dir_url(__FILE__) . 'js/crypto-donations.js');        
        wp_enqueue_style('crypto-donations', plugin_dir_url(__FILE__) . 'css/crypto-donations.css');
        wp_enqueue_style('crypto-donations-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap-4.3.1.min.css');
}

function crypto_donations_setup_shortcodes() {
	add_shortcode('crypto-donations', 'crypto_donations_resolve_shortcode');
}

function crypto_donations_setup_default_settings() {
	if (get_option('crypto_donations_settings_widget_color') == "" || get_option('crypto_donations_settings_widget_color') == null) {
                update_option('crypto_donations_settings_widget_color', '#005bbc');
        }
        if (get_option('crypto_donations_settings_text_color') == "" || get_option('crypto_donations_settings_text_color') == null) {
                update_option('crypto_donations_settings_text_color', '#ffffff');
        }
        if (get_option('crypto_donations_settings_brightness') == "" || get_option('crypto_donations_settings_brightness') == null) {
                update_option('crypto_donations_settings_brightness', 0);
        }
}
// Tools

function crypto_donations_adjust_brightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));
    
        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }
    
        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';
    
        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }
    
        return $return;
}

// Admin
add_action('admin_menu', 'crypto_donations_create_admin_menu');
add_action('admin_init', 'crypto_donations_add_settings');

function crypto_donations_add_settings() {
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_widget_color', array('type' => 'string', 'default' => '#005bbc'));
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_text_color', array('type' => 'string', 'default' => '#ffffff'));
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_brightness', array('type' => 'int', 'default' => 0));
	register_setting('crypto_donations_settings_group', 'crypto_donations_settings_bitcoin', array('type' => 'string', 'default' => ''));
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_ethereum', array('type' => 'string', 'default' => ''));
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_litecoin', array('type' => 'string', 'default' => ''));
        register_setting('crypto_donations_settings_group', 'crypto_donations_settings_etherscan_api', array('type' => 'string'));
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('admin-styles', plugin_dir_url(__FILE__) . 'css/crypto-donations_admin.css');
        wp_enqueue_script('crypto-donations-admin', plugin_dir_url(__FILE__) . 'js/crypto-donations_admin.js');
}

function crypto_donations_create_admin_menu() {
	add_menu_page('Crypto Donations', 'Crypto Donations', 10, __FILE__, 'crypto_donations_page');
	add_submenu_page(__FILE__, __('Settings', 'crypto-donations'), __('Settings', 'crypto-donations'), 10, 'crypto_donations_settings', 'crypto_donations_settings');
}

function crypto_donations_page() {
        echo '<h1>Crypto Donations</h1>
        <h2>What does this plugin costs? No plugin is for free...</h2>
        <p>This plugin is for free. Every function and every upcoming feature is and will stay free.</p>
        <p>If you like this plugin feel free to donate something:
        <ul style="list-style-type: disc; padding-left: 24px;">
                <li>Bitcoin: 1b1ue12iCdqYqrTrJdV92xWL8PSxj6wq7</li>
                <li>Lighting: Have a look at <a href="https://www.b1ue.tech">b1ue.tech</a>, there is a possibility to donate via Lightning</li>
                <li>You can also ask for other altcoins at <a href="mailto:info@b1ue.tech">info@b1ue.tech</a></li>
        </ul>
        </p>
        <h2>How to use</h2>
        <p>
                <ol>
                        <li>Just go to settings and set your donation addresses.
                                <ul style="list-style-type: disc; padding-left: 24px;">
                                        <li>Ethereum needs an additional API Key for Etherscan.</li>
                                        <li>You can register it <a href="https://etherscan.io/register" target="_BLANK">here</a> for free.</li>
                                </ul>
                        </li>
                        <li>Enter a shortcode wherever you want like this: [crypto-donation type="coin name"]</li>
                        <li>Supported types (coins) are:
                                <ul style="list-style-type: disc; padding-left: 24px;">
                                        <li>bitcoin</li>
                                        <li>ethereum</li>
                                        <li>litecoin</li>
                                </ul>
                        </li>

                </ol>
        </p>
        <h2>Upcoming features</h2>
        <ul style="list-style-type: disc; padding-left: 24px;">
                <li>Lightning Support</li>
                <li>Bitcoin Cash Support</li>
        </ul>
        Feel free to do feature requests at <a href="https://www.b1ue.tech">b1ue.tech</a>.
        ';
}

function crypto_donations_settings() {
?>
<!-- START Settings Form -->
<div class="wrap">
<h1><?php echo __('Crypto Donations - Settings', 'crypto-donations'); ?></h1>
<form action="options.php" method="POST">

<?php settings_fields('crypto_donations_settings_group'); ?>

<div class="settings_box">
<h2><?php echo __("Global Settings", "crypto-donations"); ?></h2>
<table>
        <tr valig="top">
                <th class="label" scope="row" align="right"><label for="crypto_donations_settings_widget_color"><?php echo __('Widget Color', 'crypto-donations'); ?></label></th>
                <td><input class="color-field" type="text" id="crypto_donations_settings_widget_color" name="crypto_donations_settings_widget_color" value="<?php echo get_option('crypto_donations_settings_widget_color'); ?>"></td>
        </tr>
        <tr valig="top">
                <th class="label" scope="row" align="right"><label for="crypto_donations_settings_text_color"><?php echo __('Text Color', 'crypto-donations'); ?></label></th>
                <td><input class="color-field" type="text" id="crypto_donations_settings_text_color" name="crypto_donations_settings_text_color" value="<?php echo get_option('crypto_donations_settings_text_color'); ?>"></td>
        </tr>
        <tr valig="top">
                <th class="label" scope="row" align="right"><label for="crypto_donations_settings_brightness"><?php echo __('Widget Brightness', 'crypto-donations'); ?></label></th>
                <td><input type="number" min="-255" max="255" id="crypto_donations_settings_brightness" name="crypto_donations_settings_brightness" value="<?php echo get_option('crypto_donations_settings_brightness'); ?>"></td>
        </tr>
        <tr valig="top">
                <th class="label" scope="row" align="right"><label for="crypto_donations_settings_etherscan_api"><?php echo __('Etherscan API Key Token', 'crypto-donations'); ?></label></th>
                <td><input class="key" type="text" id="crypto_donations_settings_etherscan_api" name="crypto_donations_settings_etherscan_api" value="<?php echo get_option('crypto_donations_settings_etherscan_api'); ?>"></td>
        </tr>
</table>
</div>
<div class="separator>">&nbsp;</div>
<div class="settings_box">
    <h2><?php echo __("Crypto Coin Addresses", "crypto-donations"); ?></h2>
        <table>
                <tr valig="top">
                        <th class="label" scope="row" align="right"><label for="crypto_donations_settings_bitcoin"><?php echo __('Bitcoin Address', 'crypto-donations'); ?></label></th>
                        <td><input class="address" type="text" id="crypto_donations_settings_bitcoin" name="crypto_donations_settings_bitcoin" value="<?php echo get_option('crypto_donations_settings_bitcoin'); ?>"></td>
                </tr>
                <tr valign="top">
                        <th  class="label" scope="row" align="right"><label for="crypto_donations_settings_ethereum"><?php echo __('Ethereum Address', 'crypto-donations'); ?></label></th>
                        <td><input class="address" type="text" id="crypto_donations_settings_ethereum" name="crypto_donations_settings_ethereum" value="<?php echo get_option('crypto_donations_settings_ethereum'); ?>"</td>
                </tr>
                <tr valign="top">
                        <th  class="label" scope="row" align="right"><label for="crypto_donations_settings_litecoin"><?php echo __('Litecoin Address', 'crypto-donations'); ?></label></th>
                        <td><input class="address" type="text" id="crypto_donations_settings_litecoin" name="crypto_donations_settings_litecoin" value="<?php echo get_option('crypto_donations_settings_litecoin'); ?>"</td>
                </tr>
        </table>
</div>

<?php submit_button(); ?>
</form>
</div>

<!-- END Settings Form -->
<?php
}

// Create Widget

function crypto_donations_resolve_shortcode($atts = [], $content = null, $tag = '') {
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	switch($atts["type"]) {
		case "bitcoin":
                        return crypto_donations_create_bitcoin_widget();
                case "ethereum":
                        return crypto_donations_create_ethereum_widget();
                case "litecoin":
                        return crypto_donations_create_litecoin_widget();
		default:
			return "";
	}
}

function crypto_donations_create_bitcoin_widget() {
        if (get_option('crypto_donations_settings_bitcoin') == null || get_option('crypto_donations_settings_bitcoin') == "") {
                return __("[Error] Crypto Donations: No Bitcoin Address was defined!");
        }
        return '
                <script type="text/javascript">
                        processBitcoinWidget(\''.get_option('crypto_donations_settings_bitcoin').'\');
                </script>
                <div class="crypto_donations_widget" style="background-image: linear-gradient('.get_option('crypto_donations_settings_widget_color').', '.crypto_donations_adjust_brightness(get_option('crypto_donations_settings_widget_color'), get_option('crypto_donations_settings_brightness')).', '.get_option('crypto_donations_settings_widget_color').'); border: 1px solid '.get_option('crypto_donations_settings_text_color').'; color: '.get_option('crypto_donations_settings_text_color').';" onclick="processBitcoinWidget(\''.get_option('crypto_donations_settings_bitcoin').'\');"
                        data-toggle="popover" title="'.get_option('crypto_donations_settings_bitcoin').'" data-html="true" 
                        data-content="
                                '.__('Donations', 'crypto-donations').': <span id=\'crypto_donations_info_bitcoin_amount\'></span><br>
                                '.__('Explorer', 'crypto-donations').': <a href=\'#\' target=\'_BLANK\' id=\'crypto_donations_info_bitcoin_explorer\'>Blockstream</a><br>
                                <div id=\'crypto_donations_info_bitcoin_qr\'></div>
                        " tabindex="0"
                >
                <img class="crypto_donations_icon" src="'.plugin_dir_url(__FILE__).'/icons/icon_bitcoin.png"><span class="crypto_donations_text">'.__('Bitcoin', 'crypto-donations').'</span>
                </div>
                <div id="crypto_donations_bubble" class="crypto_donations_bubble" style="border: 1px solid '.get_option('crypto_donations_settings_widget_color').'; border-right-color: '.get_option('crypto_donations_settings_widget_color').';"><span id="crypto_donations_info_bitcoin_bubble"></span></div>   
                <div class="separator">&nbsp;</div>     
        ';

}

function crypto_donations_create_ethereum_widget() {
        if (get_option('crypto_donations_settings_ethereum') == null || get_option('crypto_donations_settings_ethereum') == "") {
                return __("[Error] Crypto Donations: No Ethereum Address was defined!");
        }
        if (get_option('crypto_donations_settings_etherscan_api') == null || get_option('crypto_donations_settings_etherscan_api') == "") {
                return __("[Error] Crypto Donations: No Etherscan API Key was defined.");
        }
        return '
                <script type="text/javascript">
                        processEthereumWidget(\''.get_option('crypto_donations_settings_ethereum').'\');
                </script>
                <div class="crypto_donations_widget" style="background-image: linear-gradient('.get_option('crypto_donations_settings_widget_color').', '.crypto_donations_adjust_brightness(get_option('crypto_donations_settings_widget_color'), get_option('crypto_donations_settings_brightness')).', '.get_option('crypto_donations_settings_widget_color').');  border: 1px solid '.get_option('crypto_donations_settings_text_color').'; color: '.get_option('crypto_donations_settings_text_color').';" onclick="processEthereumWidget(\''.get_option('crypto_donations_settings_ethereum').'\');"
                        data-toggle="popover" title="'.get_option('crypto_donations_settings_ethereum').'" data-html="true" 
                        data-content="
                                '.__('Donations', 'crypto-donations').': <span id=\'crypto_donations_info_ethereum_amount\'></span><br>
                                '.__('Explorer', 'crypto-donations').': <a href=\'#\' target=\'_BLANK\' id=\'crypto_donations_info_ethereum_explorer\'>Etherscan</a><br>
                                <div id=\'crypto_donations_info_ethereum_qr\'></div>
                        "
                >
                <img class="crypto_donations_icon" src="'.plugin_dir_url(__FILE__).'/icons/icon_ethereum.png"><span class="crypto_donations_text">'.__('Ethereum', 'crypto-donations').'</span>
                </div>
                <div id="crypto_donations_bubble" class="crypto_donations_bubble" style="border: 1px solid '.get_option('crypto_donations_settings_widget_color').'; border-right-color: '.get_option('crypto_donations_settings_widget_color').';"><span id="crypto_donations_info_ethereum_bubble"></span></div>
                <div class="separator">&nbsp;</div>         
        ';
}

function crypto_donations_create_litecoin_widget() {
        if (get_option('crypto_donations_settings_litecoin') == null || get_option('crypto_donations_settings_litecoin') == "") {
                return __("[Error] Crypto Donations: No Litecoin Address was defined!");
        }
        return '
                <script type="text/javascript">
                        processLitecoinWidget(\''.get_option('crypto_donations_settings_litecoin').'\');
                </script>
                <div class="crypto_donations_widget" style="background-image: linear-gradient('.get_option('crypto_donations_settings_widget_color').', '.crypto_donations_adjust_brightness(get_option('crypto_donations_settings_widget_color'), get_option('crypto_donations_settings_brightness')).', '.get_option('crypto_donations_settings_widget_color').');  border: 1px solid '.get_option('crypto_donations_settings_text_color').'; color: '.get_option('crypto_donations_settings_text_color').';" onclick="processLitecoinWidget(\''.get_option('crypto_donations_settings_litecoin').'\');"
                        data-toggle="popover" title="'.get_option('crypto_donations_settings_litecoin').'" data-html="true" 
                        data-content="
                                '.__('Donations', 'crypto-donations').': <span id=\'crypto_donations_info_litecoin_amount\'></span><br>
                                '.__('Explorer', 'crypto-donations').': <a href=\'#\' target=\'_BLANK\' id=\'crypto_donations_info_litecoin_explorer\'>Blockchair</a></span><br>
                                <div id=\'crypto_donations_info_litecoin_qr\'></div>
                        "
                >
                <img class="crypto_donations_icon" src="'.plugin_dir_url(__FILE__).'/icons/icon_litecoin.png"><span class="crypto_donations_text">'.__('Litecoin', 'crypto-donations').'</span>
                </div>
                <div id="crypto_donations_bubble" class="crypto_donations_bubble" style="border: 1px solid '.get_option('crypto_donations_settings_widget_color').'; border-right-color: '.get_option('crypto_donations_settings_widget_color').';"><span id="crypto_donations_info_litecoin_bubble"></span></div>
                <div class="separator">&nbsp;</div>         
        ';
}