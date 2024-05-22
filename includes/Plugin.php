<?php

/**
* The file that defines the core plugin class
*
* @link http://wilsondevops.com
* @since 1.0.0
*
* @package Plugin_Name
* @subpackage Plugin_Name/includes
*/

/**
* The core plugin class.
*
*
* @since 1.0.0
* @package Plugin_Name
* @subpackage Plugin_Name/includes
* @author Wilson Devops <wilsonkabatha@gmail.com>
*/

namespace Wylly\Plugin_Name;

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

       // Other initialization code...

       return $plugin;
    }

    protected static function activate(): void {
      flush_rewrite_rules();
    }

    protected static function deactivate(): void {
      flush_rewrite_rules();
    }
}