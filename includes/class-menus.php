<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_AI_POSTS_MENUS', false)) :

    /**
     * WP_AI_POSTS_MENUS Class.
     */
    class WP_AI_POSTS_MENUS
    {
        public $bm_employee_id;

        public function __construct()
        {
            add_action('admin_menu', [$this, 'admin_menu']);
//            add_action('admin_head', [$this, 'menu_highlight']);
        }


//        public function menu_highlight()
//        {
//            global $parent_file, $submenu_file, $post_type, $post, $pagenow;
//
//            if (isset($_GET['taxonomy']) && sanitize_text_field($_GET['taxonomy']) == 'wp-ai-posts-prompts') {
//                $parent_file = 'learndash-lms';
//                $submenu_file = 'edit-tags.php?taxonomy=wp-ai-posts-prompts';
//            }
//        }

        /**
         * Add menu items.
         */
        public function admin_menu()
        {
            $title = 'WP AI Posts';

            $parent = add_menu_page(
                $title, // page title
                $title, // menu title
                'manage_options', // capability
                'wp-ai-posts', //menu slug
                null,  // callback
                'dashicons-welcome-write-blog', //dashicon class
                55 // position
            );

            $submenu = [];


            $submenu['settings'] = [
                'wp-ai-posts', // parent slug
                __('Settings', 'wpaiposts'), // page title
                __('Settings', 'wpaiposts'), // menu title
                'manage_options', // capability
                'wp-ai-posts', // menu slug
                [wp_ai_posts()->settings, 'plugin_page'],
            ];

            $submenu = apply_filters('wp_ai_posts_menu', $submenu);

            foreach ($submenu as $key => $value) {
                // Remove menu items based on employee access
                add_submenu_page($value[0], $value[1], $value[2], $value[3], $value[4], $value[5]);
            }
        }
    }

endif;

return new WP_AI_POSTS_MENUS();
