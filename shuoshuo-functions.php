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
    $custom_css = get_option('custom_shuoshuo_css', '.shuo-content-area {
    width: 960px;
    margin: auto;
}');
    echo '<style>
	'. $custom_css. '
    </style>';
}
add_action( 'wp_head', 'custom_shuo_plugin_styles' );

// 添加后台菜单
function custom_shuoshuo_menu() {
    add_submenu_page(
        'edit.php?post_type=shuoshuo',
        '自定义说说 CSS 样式',
        '自定义 CSS',
        'manage_options',
        'custom-shuoshuo-css',
        'custom_shuoshuo_css_page'
    );
}
add_action('admin_menu', 'custom_shuoshuo_menu');

// 后台菜单页面内容
function custom_shuoshuo_css_page() {
    if (isset($_POST['submit'])) {
        $custom_css = sanitize_textarea_field($_POST['custom_css']);
        update_option('custom_shuoshuo_css', $custom_css);
        echo '<div class="updated"><p>自定义 CSS 样式已保存。</p></div>';
    }

    $custom_css = get_option('custom_shuoshuo_css', '.shuo-content-area {
    width: 960px;
    margin: auto;
}');
    ?>
    <div class="wrap">
        <h1>自定义说说 CSS 样式</h1>
        <hr>
        <h3 for="custom_css">输入自定义 CSS 样式：</h3>
        <form method="post">
            <textarea id="custom_css" name="custom_css" rows="10" cols="50"><?php echo esc_textarea($custom_css); ?></textarea>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="保存更改">
            </p>
        </form>
    </div>
    <?php
}