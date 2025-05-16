<?php
// 后台菜单页面代码
function setup_admin_menu_pages() {
    // 添加DCP Setting父菜单
    function dcp_setting_parent_menu() {
        add_menu_page(
            'DCP Setting',
            'DCP Setting',
            'manage_options',
            'dcp-setting',
            '',
            'dashicons-admin-generic',
            25
        );
        add_submenu_page(
            'dcp-setting',
            '表情包映射管理',
            '表情包映射管理',
            'manage_options',
            'dcp-setting',
            'emoji_plugin_folder_mapping_page'
        );
        add_submenu_page(
            'dcp-setting',
            '股票监控',
            '股票监控',
            'manage_options',
            'dcp-stock-monitor',
            'stock_monitor_admin_page'
        );
        add_submenu_page(
            'dcp-setting',
            '归档短码说明',
            '归档短码说明',
            'manage_options',
            'dcp-custom-archive-shortcodes',
            'custom_archive_shortcode_instructions'
        );
        add_submenu_page(
            'dcp-setting',
            '时间轴设置',
            '时间轴设置',
            'manage_options',
            'dcp-custom_time_line_settings',
            'custom_time_line_display_function'
        );
    }
    add_action('admin_menu', 'dcp_setting_parent_menu');

}

// 初始化插件
function initialize_plugin() {
    setup_admin_menu_pages();
}
add_action('plugins_loaded', 'initialize_plugin');

function custom_archive_shortcode_instructions() {
    ?>
    <div class="wrap">
        <h1>自定义归档短码使用说明</h1>
        <h2>[custom_categories]</h2>
        <p>此短码用于显示自定义分类链接。</p>
        <p>可用参数：</p>
        <ul>
            <li><strong>orderby</strong>：排序依据，默认为 'name'。</li>
            <li><strong>order</strong>：排序顺序，默认为 'ASC'。</li>
            <li><strong>hide_empty</strong>：是否隐藏空分类，默认为 0（不隐藏）。</li>
        </ul>
        <p>使用示例：<code>[custom_categories orderby="count" order="DESC"]</code></p>

        <h2>[custom_date_archive]</h2>
        <p>此短码用于显示自定义日期归档链接。</p>
        <p>可用参数：</p>
        <ul>
            <li><strong>type</strong>：归档类型，默认为 'monthly'。</li>
            <li><strong>format</strong>：日期格式，默认为 'F Y'。</li>
            <li><strong>show_post_count</strong>：是否显示文章数量，默认为 1（显示）。</li>
        </ul>
        <p>使用示例：<code>[custom_date_archive type="yearly" format="Y"]</code></p>

        <h2>[custom_tags]</h2>
        <p>此短码用于显示自定义标签链接。</p>
        <p>可用参数：</p>
        <ul>
            <li><strong>orderby</strong>：排序依据，默认为 'name'。</li>
            <li><strong>order</strong>：排序顺序，默认为 'ASC'。</li>
            <li><strong>hide_empty</strong>：是否隐藏空标签，默认为 0（不隐藏）。</li>
        </ul>
        <p>使用示例：<code>[custom_tags orderby="count" order="DESC"]</code></p>
    </div>
    <?php
}

?>