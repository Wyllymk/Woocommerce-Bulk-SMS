<?php

/**
* The file that defines the core plugin class
*
* @link http://wilsondevops.com
* @since 1.0.0
*
* @package WooCommerce_Bulk_SMS
* @subpackage WooCommerce_Bulk_SMS/includes
*/

/**
* The core plugin class.
*
*
* @since 1.0.0
* @package WooCommerce_Bulk_SMS
* @subpackage WooCommerce_Bulk_SMS/includes
* @author Wilson Devops <wilsonkabatha@gmail.com>
*/

namespace Wylly\WooCommerce_Bulk_SMS;

final class Plugin {

    protected static ?self $instance = null;

    protected ?string $entry_point = null;

    public static function get_instance(): self {
       if ( is_null( self::$instance ) ) {
          self::$instance = new self();
       }

       return self::$instance;
    }

    public static function run( string $entry_point ): self {
       $plugin = self::get_instance();

       $plugin->entry_point = $entry_point;

       register_activation_hook( $entry_point, function () {
          self::activate();
       } );

       register_deactivation_hook( $entry_point, function () {
          self::deactivate();
       } );

       // Initialize WooCommerce functionality
       add_action('woocommerce_loaded', [WooCommerceBulkSMS::class, 'init']);

       return $plugin;
    }

    protected static function activate(): void {
      flush_rewrite_rules();
    }

    protected static function deactivate(): void {
      flush_rewrite_rules();
    }
}