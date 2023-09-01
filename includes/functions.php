<?php

if (!defined('ABSPATH')) {
    exit;
}

function wpaiposts_ajax_return($success, $msg, $data = null)
{
    $return = [
        'success' => $success,
        'message' => $msg,
        'data' => $data,
    ];

    echo wp_send_json($return);
}


function wpaiposts_post_exists($post, $type)
{
    if (!is_admin()) {
        require_once(ABSPATH . 'wp-admin/includes/post.php');
    }
    return post_exists($post, '', '', $type);
}
