<?php


if (!class_exists('WP_AI_POSTS_Settings')):

    class WP_AI_POSTS_Settings
    {
        private $settings_api;

        public function __construct()
        {
            // var_dump( get_site_option('wpaiposts-settings')['chat_gpt_ai']);
            $this->settings_api = new WP_AI_POSTS_SETTINGS_API;
            /**
             * @see admin_init
             */
            add_action('admin_init', [$this, 'admin_init']);

            add_filter('submit_button_text_wpaiposts-create-post', [$this, 'change_submit_text_for_create_posts']);
        }

        public function change_submit_text_for_create_posts($value)
        {
            return __('Create Posts', 'wpaiposts');
        }

        public function admin_init()
        {
            //set the settings
            $this->settings_api->set_sections($this->get_settings_tabs());
            $this->settings_api->set_fields($this->get_settings_fields());
            //initialize settings
            $this->settings_api->admin_init();
        }

        /**
         * @return mixed
         * todo :
         * 1. FIX AND RUN THIS CODE
         * 2. PUT SOME TEXTBOX IN GENERAL TAB
         */
        public function get_settings_tabs()
        {
            $tabs = [
                [
                    'id' => 'wpaiposts-create-post',
                    'title' => __('Create Posts', 'wpaiposts'),
                ],
                [
                    'id' => 'wpaiposts-settings',
                    'title' => __('Settings', 'wpaiposts'),
                ],
            ];
            return apply_filters('wpaiposts_settings_tabs', $tabs);
        }

        public function get_create_post_fields()
        {
            $id = 'create-post';
            $create_post_fields = [
                "wpaiposts-{$id}" => [
                    [
                        'name' => 'prompt0',
                        'label' => __('Prompt', 'wpaiposts'),
                        'desc' => __('Write your prompt and let chat gpt 3.5 to create posts for you | Max 4095 characters, this is limitation of chat gpt turbo 3.5 | Leave empty to avoid this prompt', 'wpaiposts'),
                        'type' => 'textarea',
                    ],
                    [
                        'name' => 'prompt1',
                        'label' => __('Prompt', 'wpaiposts'),
                        'desc' => __('Write your prompt and let chat gpt 3.5 to create posts for you | Max 4095 characters, this is limitation of chat gpt turbo 3.5 | Leave empty to avoid this prompt', 'wpaiposts'),
                        'type' => 'textarea',
                    ],
                    [
                        'name' => 'prompt2',
                        'label' => __('Prompt', 'wpaiposts'),
                        'desc' => __('Write your prompt and let chat gpt 3.5 to create posts for you | Max 4095 characters, this is limitation of chat gpt turbo 3.5 | Leave empty to avoid this prompt', 'wpaiposts'),
                        'type' => 'textarea',
                    ],
                    [
                        'name' => 'prompt3',
                        'label' => __('Prompt', 'wpaiposts'),
                        'desc' => __('Write your prompt and let chat gpt 3.5 to create posts for you | Max 4095 characters, this is limitation of chat gpt turbo 3.5 | Leave empty to avoid this prompt', 'wpaiposts'),
                        'type' => 'textarea',
                    ],
                    [
                        'name' => 'prompt4',
                        'label' => __('Prompt', 'wpaiposts'),
                        'desc' => __('Write your prompt and let chat gpt 3.5 to create posts for you | Max 4095 characters, this is limitation of chat gpt turbo 3.5 | Leave empty to avoid this prompt', 'wpaiposts'),
                        'type' => 'textarea',
                    ],
                ],
            ];
            return apply_filters("wpaiposts_{$id}_fields", $create_post_fields);
        }

        public function get_ai_settings_fields()
        {
            $id = 'settings';
            $settings_fields = [
                "wpaiposts-{$id}" => [
                    [
                        'name' => 'chat_gpt_token',
                        'label' => __('Chat GPT API KEY', 'wpaiposts'),
                        'desc' => __('Please login to chat gpt and paste api key here.', 'wpaiposts'),
                        'type' => 'text',
                    ],
                ],
            ];
            return apply_filters("wpaiposts_{$id}_fields", $settings_fields);
        }

        public function plugin_page()
        {
            echo '<div class="wrap">';
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            echo '</div>';
        }

        /**
         * Merge all the settings fields.
         *
         * @return array settings fields
         */
        public function get_settings_fields()
        {
            return apply_filters('wpaiposts_settings_fields', array_merge(
                $this->get_create_post_fields(),
                $this->get_ai_settings_fields()
            ));
        }

    }
endif;