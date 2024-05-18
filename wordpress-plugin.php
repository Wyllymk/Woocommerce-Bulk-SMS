<?php
/** 
 *  
 *  Plugin Name
 * 
 * @package           Plugin_Name 
 * @author            Wilson Devops 
 * @copyright         2024 Wilson Devops
 * @license           GPL-2.0-or-later
 * @link              https://github.com/Wyllymk/wordpress-plugin
 * 
 * @wordpress-plugin
 * 
 * Plugin Name:       Plugin Name 
 * Plugin URI:        https://github.com/Wyllymk/wordpress-plugin 
 * Description:       Wordpress Plugin. 
 * Version:           1.0.0 
 * Requires at least: 6.0
 * Requires PHP:      7.2 
 * Author:            Wilson Devops 
 * Author URI:        https://wilsondevops.com
 * Text Domain:       textdomain 
 * License:           GPL v2 or later 
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * Update URI:        https://github.com/Wyllymk/wordpress-plugin 
 * Requires Plugins:  
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( file_exists( dirname( __FILE__) . '/vendor/autoload.php' ) ){
    require_once __DIR__ . '/vendor/autoload.php';
}



use Wylly\Plugin_Name\Plugin;

Plugin::run( entry_point: __FILE__ );