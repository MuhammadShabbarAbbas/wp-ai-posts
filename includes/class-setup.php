<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_AI_POSTS_SETUP', false)) :

    /**
     * Business_Manager_Setup Class.
     */
    class WP_AI_POSTS_SETUP
    {

        public function __construct()
        {
            $this->hooks();
        }

        /**
         * Hook in to actions & filters.
         *
         * @since 1.0.0
         */
        public function hooks()
        {
            add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        }

        public function admin_styles()
        {
            $url = WP_AI_POSTS_URL;
            $v = WP_AI_POSTS_VERSION;
            wp_enqueue_style('wpaiposts-admin', $url . 'assets/admin.css', $v);
//            wp_enqueue_style('wpaiposts-select2', $url . 'assets/lib/select2.min.css', $v);
        }

        public function admin_scripts()
        {
            $url = WP_AI_POSTS_URL;
            $v = WP_AI_POSTS_VERSION;
            wp_enqueue_script('wpaiposts-admin', $url . 'assets/admin.js', array('jquery'), $v, true);
//            wp_enqueue_script('wpaiposts-select2', $url . 'assets/lib/select2.min.js', array('jquery'), $v, true);

            $options = get_site_option('wpaiposts-settings');

            $defaults = array('chat_gpt_token' => false);
            // Parse incoming $args into an array and merge it with $defaults
            $options = wp_parse_args($options, $defaults);

            // js options and i18n
            $l6e = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'chat_gpt_api_key' => $options['chat_gpt_token'],
                'chat_gpt_api_url' => WP_AI_POSTS_CHAT_GPT_API
            );
            wp_localize_script('wpaiposts-admin', 'wpaiposts', $l6e);
        }
    }

endif;

return new WP_AI_POSTS_SETUP();
