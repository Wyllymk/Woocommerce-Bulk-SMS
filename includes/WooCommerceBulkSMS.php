<?php

/**
 * The file that defines the functionality plugin class
 *
 * @link http://wilsondevops.com
 * @since 1.0.0
 *
 * @package WooCommerce_Bulk_SMS
 * @subpackage WooCommerce_Bulk_SMS/includes
 *
 * @author Wilson Devops <wilsonkabatha@gmail.com>
 */

namespace Wylly\WooCommerce_Bulk_SMS;

class WooCommerceBulkSMS {

    public static function init(): void {
        add_filter( 'woocommerce_get_sections_advanced', [self::class, 'wc_bulk_sms'] );
        add_filter( 'woocommerce_get_settings_advanced', [self::class, 'wc_bulk_sms_settings'],10,2);
    }

    /**
    * Create the section beneath the products tab
    **/

    public static function wc_bulk_sms( $sections ) {

        $sections['wcbulksms'] = __( 'MOBILE SASA Bulk SMS', 'woocommerce-bulk-sms' );

        return $sections;

    }

    public static function wc_bulk_sms_settings( $settings, $current_section ) {

        if ($current_section == 'wcbulksms'){
            $bulk_sms_settings = array();
            // Add Title to the Settings
            $bulk_sms_settings[] = array(
                'name'=>__('MOBILE SASA Bulk SMS Settings','woocommerce-bulk-sms'),
                'type'=>'title',
                'desc' => __( 'The following options are used to configure Bulk SMS', 'woocommerce-bulk-sms' ), 
                'id'=>'wcbulksms'
            );

            // Add enable option
            $bulk_sms_settings[] = array(
                'name'=>__('Enable/Disable','woocommerce-bulk-sms'),
                'desc_tip' => __( 'This will automatically activate Mobile Sasa Bulk SMS', 'woocommerce-bulk-sms' ),
                'id'=>'wcbulksms_enable',
                'type'=>'checkbox',
                'desc'=>__('Enable','woocommerce-bulk-sms')
            );

            // Add Sender ID option
            $bulk_sms_settings[] = array(
                'name'=>__('Sender ID','woocommerce-bulk-sms'),
                'desc_tip' => __( 'Please use the purchase Sender ID here otherwise input MOBILESASA', 'woocommerce-bulk-sms' ),
                'id'=>'wcbulksms_senderid',
                'type'=>'text',
                'placeholder'=>'e.g MOBILESASA'
            );

            // Add API Token option
            $bulk_sms_settings[] = array(
                'name'=>__('Api Token','woocommerce-bulk-sms'),
                'id'=>'wcbulksms_apitoken',
                'type'=>'text'
            );
            
           
            $bulk_sms_settings[] = array( 'type' => 'sectionend', 'id' => 'wcbulksms' );
            
            return $bulk_sms_settings;
		} else {
            return $settings;
        }

    }

	
}