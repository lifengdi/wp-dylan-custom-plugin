<?php

// 注册自定义页面模板
function custom_shuoshuo_template($templates) {
    $templates['shuoshuo-template.php'] = '说说/微语';
    return $templates;
}
add_filter('theme_page_templates', 'custom_shuoshuo_template');

// 加载自定义页面模板
function load_custom_shuoshuo_template($template) {
    global $post;
    if (isset($post) && 'shuoshuo-template.php' === get_post_meta($post->ID, '_wp_page_template', true)) {
        $plugin_dir = plugin_dir_path(__FILE__);
        $new_template = $plugin_dir . 'shuoshuo-template.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_custom_shuoshuo_template');