<?php
/** 
 *  
 *  WooCommerce Bulk SMS
 * 
 * @package           WooCommerce_Bulk_SMS 
 * @author            Wilson Devops <wilsonkabatha@gmail.com>
 * @copyright         2024 Wilson Devops
 * @license           GPL-2.0-or-later
 * @link              https://github.com/Wyllymk/woocommerce-bulk-sms
 * 
 * @wordpress-plugin
 * 
 * Plugin Name:             MOBILESASA Bulk SMS
 * Plugin URI:              https://github.com/Wyllymk/woocommerce-bulk-sms 
 * Description:             A plugin to handle bulk SMS for WooCommerce. 
 * Version:                 1.0.0 
 * Requires at least:       6.0
 * Requires PHP:            7.2 
 * Tested up to:            6.5
 * WC requires at least:    8.9
 * Author:                  Wilson Devops 
 * Author URI:              https://wilsondevops.com
 * Text Domain:             woocommerce-bulk-sms 
 * License:                 GPL v2 or later 
 * License URI:             http://www.gnu.org/licenses/gpl-2.0.txt 
 * Update URI:              https://github.com/Wyllymk/woocommerce-bulk-sms 
 * Requires Plugins:        woocommerce
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( file_exists( dirname( __FILE__) . '/vendor/autoload.php' ) ){
    require_once __DIR__ . '/vendor/autoload.php';
}

use Wylly\WooCommerce_Bulk_SMS\Plugin;


Plugin::run( entry_point: __FILE__ );