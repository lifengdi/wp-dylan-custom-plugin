<?php
// 注册说说自定义文章类型
function register_shuoshuo_post_type() {
    $labels = array(
        'name'               => '说说',
        'singular_name'      => '说说',
        'menu_name'          => '说说',
        'name_admin_bar'     => '说说',
        'add_new'            => '新增说说',
        'add_new_item'       => '新增一条说说',
        'new_item'           => '新说说',
        'edit_item'          => '编辑说说',
        'view_item'          => '查看说说',
        'all_items'          => '所有说说',
        'search_items'       => '搜索说说',
        'not_found'          => '未找到说说',
        'not_found_in_trash' => '回收站中未找到说说'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' =>'shuoshuo'),
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'author', 'comments')
    );

    register_post_type('shuoshuo', $args);
}
add_action('init','register_shuoshuo_post_type');

register_activation_hook(__FILE__, 'create_shuoshuo_template_file');