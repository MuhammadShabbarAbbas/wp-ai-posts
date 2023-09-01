<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_AI_POSTS_METABOXES', false)) :

    /**
     * Business_Manager_Setup Class.
     */
    class WP_AI_POSTS_METABOXES
    {
        /**
         * Post type.
         * @var string
         */
        public $post_types = ['post'];

        /**
         * Metabox prefix.
         *
         * @since 1.0.0
         */
        private $pre = '_wpaiposts_';

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
//            add_action("save_post", [$this, "save_custom_meta_box"], 10, 3);
//            add_action("add_meta_boxes", [$this, "add_custom_meta_box"]);
            add_filter('mce_external_plugins', [$this, 'add_chatgpt_plugin']);
            add_filter('mce_buttons', [$this, 'register_chatgpt_button']);
        }

        function add_chatgpt_plugin($plugins_array)
        {
            $url = WP_AI_POSTS_URL;
            $plugins_array['chatgpt'] = $url . '/assets/chatgpt-tinymce.js';
            return $plugins_array;
        }

        function register_chatgpt_button($buttons)
        {
            array_push($buttons, 'chatgpt');
            return $buttons;
        }

        public function add_mce_button($mce_buttons, $editor_id)
        {
            $b = $mce_buttons;
            return [];
        }

        function custom_meta_box_markup($post)
        {
            wp_nonce_field(basename(__FILE__), $this->pre . "meta-box-nonce");
            ?>
            <div>
                <label for="<?php $this->pre ?>ai-prompt">Prompt</label>
                <input name="<?php $this->pre ?>ai-prompt" type="text"/>
            </div>
            <div style="text-align: right;padding: 8px;">
                <input type="button" value="Update Description" class="button"/>
            </div>
            <?php
            /*value="<?php echo get_post_meta($post->ID, "custom-meta-box", true); ?>"*/
        }

        function add_custom_meta_box()
        {
            add_meta_box($this->pre . "meta-box", "Chat GPT", [$this, "custom_meta_box_markup"], "post", "side", "high", null);
        }


        function save_custom_meta_box($post_id, $post, $update)
        {
            if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
                return $post_id;

            if (!current_user_can("edit_post", $post_id))
                return $post_id;

            if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

            $slug = "post";
            if ($slug != $post->post_type)
                return $post_id;

            $custom_meta_box = "";

            if (isset($_POST["custom-meta-box"])) {
                $custom_meta_box = $_POST["custom-meta-box"];
            }
            update_post_meta($post_id, "custom-meta-box", $custom_meta_box);
        }
    }

endif;

return new WP_AI_POSTS_METABOXES();
