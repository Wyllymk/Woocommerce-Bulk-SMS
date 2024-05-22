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
        add_filter('woocommerce_get_sections_advanced', [self::class, 'wc_bulk_sms']);
        add_filter('woocommerce_get_settings_advanced', [self::class, 'wc_bulk_sms_settings'], 10, 2);
        add_action('admin_menu', [self::class, 'register_bulk_sms_page']);
        add_action('admin_post_send_bulk_sms', [self::class, 'handle_send_bulk_sms']);
        add_action('woocommerce_after_order_notes', [self::class, 'sms_opt_in_checkout']);
        add_action('woocommerce_checkout_update_order_meta', [self::class, 'save_sms_opt_in']);
    }

    public static function wc_bulk_sms($sections): array {
        $sections['wcbulksms'] = __('MOBILE SASA Bulk SMS', 'woocommerce-bulk-sms');
        return $sections;
    }

    public static function wc_bulk_sms_settings($settings, $current_section): array {
        if ($current_section == 'wcbulksms') {
            $bulk_sms_settings = [
                [
                    'name' => __('MOBILE SASA Bulk SMS Settings', 'woocommerce-bulk-sms'),
                    'type' => 'title',
                    'desc' => __('The following options are used to configure Bulk SMS', 'woocommerce-bulk-sms'),
                    'id'   => 'wcbulksms'
                ],
                [
                    'name'    => __('Enable/Disable', 'woocommerce-bulk-sms'),
                    'desc_tip'=> __('This will automatically activate Mobile Sasa Bulk SMS', 'woocommerce-bulk-sms'),
                    'id'      => 'wcbulksms_enable',
                    'type'    => 'checkbox',
                    'desc'    => __('Enable', 'woocommerce-bulk-sms')
                ],
                [
                    'name'       => __('Sender ID', 'woocommerce-bulk-sms'),
                    'desc_tip'   => __('Please use the purchase Sender ID here otherwise input MOBILESASA', 'woocommerce-bulk-sms'),
                    'id'         => 'wcbulksms_senderid',
                    'type'       => 'text',
                    'placeholder'=> 'e.g MOBILESASA'
                ],
                [
                    'name'      => __('Api Token', 'woocommerce-bulk-sms'),
                    'desc_tip'  => __('Please get your API Token from Mobile Sasa API Documentation', 'woocommerce-bulk-sms'),
                    'id'        => 'wcbulksms_apitoken',
                    'type'      => 'text'
                ],
                [
                    'name'       => __('Message', 'woocommerce-bulk-sms'),
                    'id'         => 'wcbulksms_message',
                    'type'       => 'textarea',
                    'css'        => 'min-height:100px;',
                    'placeholder'=> 'e.g Hey, this is a new message'
                ],
                [
                    'type' => 'sectionend',
                    'id'   => 'wcbulksms'
                ]
            ];
            return $bulk_sms_settings;
        } else {
            return $settings;
        }
    }

    public static function register_bulk_sms_page() {
        add_menu_page(
            __('Bulk SMS', 'woocommerce-bulk-sms'),
            __('Bulk SMS', 'woocommerce-bulk-sms'),
            'manage_options',
            'wc_bulk_sms',
            [self::class, 'bulk_sms_page_html']
        );
    }

    public static function bulk_sms_page_html() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $customers = self::get_all_customers();

        ?>
<div class="wrap">
    <h1><?php esc_html_e('Mobile Sasa Bulk SMS', 'woocommerce-bulk-sms'); ?></h1>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="send_bulk_sms">
        <?php wp_nonce_field('send_bulk_sms_nonce'); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Name', 'woocommerce-bulk-sms'); ?></th>
                    <th><?php esc_html_e('Phone', 'woocommerce-bulk-sms'); ?></th>
                    <th><?php esc_html_e('Opt-in', 'woocommerce-bulk-sms'); ?></th>
                    <th><?php esc_html_e('Send SMS', 'woocommerce-bulk-sms'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) : ?>
                <tr>
                    <td><?php echo esc_html($customer['name']); ?></td>
                    <td><?php echo esc_html($customer['phone']); ?></td>
                    <td>
                        <input type="checkbox" disabled <?php checked($customer['opt_in'], 'yes'); ?>>
                    </td>
                    <td>
                        <input type="checkbox" name="send_sms[]" value="<?php echo esc_attr($customer['phone']); ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <label><input type="checkbox"
                id="select_all"><?php esc_html_e('Select All Customers', 'woocommerce-bulk-sms'); ?></label>
        <br><br>
        <textarea name="bulk_sms_message" rows="5"
            style="width:100%;"><?php echo esc_textarea(get_option('wcbulksms_message')); ?></textarea>
        <br><br>
        <button type="submit"
            class="button button-primary"><?php esc_html_e('Send SMS', 'woocommerce-bulk-sms'); ?></button>
        <br><br>
    </form>
</div>
<script>
document.getElementById('select_all').addEventListener('change', function(e) {
    let checkboxes = document.querySelectorAll('input[name="send_sms[]"]');
    for (let checkbox of checkboxes) {
        checkbox.checked = e.target.checked;
    }
});
</script>
<?php
    }

    public static function handle_send_bulk_sms() {
        if (!current_user_can('manage_options') || !check_admin_referer('send_bulk_sms_nonce')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'woocommerce-bulk-sms'));
        }

        $message = sanitize_text_field($_POST['bulk_sms_message']);
        update_option('wcbulksms_message', $message);

        $is_enabled = get_option('wcbulksms_enable');

        if ($is_enabled && $is_enabled == 'yes' && !empty($message)) {
            $senderid = get_option('wcbulksms_senderid');
            $apitoken = get_option('wcbulksms_apitoken');

            MobileSasaSendSMS::init($senderid, $apitoken);

            if (!empty($_POST['send_sms'])) {
                $phones = array_map('sanitize_text_field', $_POST['send_sms']);
                foreach ($phones as $phone) {
                    MobileSasaSendSMS::wc_sendExpressPostSMS(MobileSasaSendSMS::wc_clean_phone($phone), $message);
                }
            }

            wp_redirect(add_query_arg('sent', 'true', wp_get_referer()));
            exit;
        }
    }

    private static function get_all_customers(): array {
        $orders = wc_get_orders([
            'limit' => -1,
            'status' => 'all'
        ]);

        $customers = [];

        foreach ($orders as $order) {
            $phone = $order->get_billing_phone();
            $name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $opt_in = get_post_meta($order->get_id(), '_sms_opt_in', true);
            if (!empty($phone) && !isset($customers[$phone])) {
                $customers[$phone] = [
                    'name'   => $name,
                    'phone'  => $phone,
                    'opt_in' => $opt_in
                ];
            }
        }

        return array_values($customers);
    }

    public static function sms_opt_in_checkout($checkout) {
        woocommerce_form_field('sms_opt_in', [
            'type' => 'checkbox',
            'class' => ['form-row-wide'],
            'label' => __('Opt-in for SMS notifications for new products', 'woocommerce'),
        ], $checkout->get_value('sms_opt_in'));
    }

    public static function save_sms_opt_in($order_id) {
        if (!empty($_POST['sms_opt_in'])) {
            update_post_meta($order_id, '_sms_opt_in', 'yes');
        } else {
            update_post_meta($order_id, '_sms_opt_in', 'no');
        }
    }
}