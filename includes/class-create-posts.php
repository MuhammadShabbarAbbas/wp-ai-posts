<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_AI_POSTS_AJAX_HANDLER', false)) :
    class WP_AI_POSTS_AJAX_HANDLER
    {
        public function __construct()
        {
            $this->hooks();
        }

        public function hooks()
        {
            add_action('wp_ajax_wp_ai_create_post', [$this, 'handle_ajax']);
        }

        public function handle_ajax()
        {

            $curl = curl_init();

            $options = get_site_option('wpaiposts-settings');

            $defaults = array('chat_gpt_token' => false);
            // Parse incoming $args into an array and merge it with $defaults
            $options = wp_parse_args($options, $defaults);

            if (empty($options['chat_gpt_token'])) {
                curl_close($curl);
                return wpaiposts_ajax_return(false, 'Chat gpt API Key not found');
            }
            $prompt = $_POST['data']['prompt'];

            curl_setopt_array($curl, array(
                CURLOPT_URL => WP_AI_POSTS_CHAT_GPT_API . 'completions',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(
                    [
                        'model' => "gpt-3.5-turbo",
                        "messages" => [
                            [
                                "role" => "user",
                                "content" => $prompt
                            ]
                        ]
                    ]),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$options['chat_gpt_token']}",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            // Handle the response
            if ($response === false) {
                // Error occurred
                $error = curl_error($curl);
                curl_close($curl);
                return wpaiposts_ajax_return(false, $error);
            } else {
                // Success
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $result = json_decode($response, true);
                if (200 == $httpcode) {
                    $postarr = array(
                        'post_title' => wp_strip_all_tags(substr($prompt, 0, 50)),
                        'post_content' => $result['choices'][0]["message"]["content"],
                        'post_author' => 1,
                    );
                    $result = wp_insert_post($postarr);
                    if ($result instanceof WP_Error) {
                        curl_close($curl);
                        return wpaiposts_ajax_return(false, 'Error in post creation');
                    } else {
                        curl_close($curl);
                        return wpaiposts_ajax_return(true, 'Post created successfully. Click <a href="' . get_edit_post_link($result) . '" target="_blank">here</a> to edit post');
                    }
                } else {
                    curl_close($curl);
                    return wpaiposts_ajax_return(false, $result['error']['message']);
                }
            }
        }

    }
endif;

return new WP_AI_POSTS_AJAX_HANDLER();
