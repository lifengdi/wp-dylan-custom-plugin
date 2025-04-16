<?php

// 注册说说自定义文章类型
function create_shuoshuo_post_type() {
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
		'show_in_rest' => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'shuoshuo'),
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'author', 'comments')
    );

    register_post_type('shuoshuo', $args);
}
add_action('init', 'create_shuoshuo_post_type');


require_once plugin_dir_path( __FILE__ ).'custom-shuoshuo-template.php';

// 添加 CSS 样式
function custom_shuo_plugin_styles() {
    echo '<style>
        .shuo-content-area {
            width: 960px;
            margin: auto;
        }
    </style>';
}
add_action( 'wp_head', 'custom_shuo_plugin_styles' );