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
            '评论扩展',
            '评论扩展',
            'manage_options',
            'dcp-setting',
            'dcp_setting_page_content'
        );
        add_submenu_page(
            'dcp-setting',                  // 父菜单 slug
            '火山引擎ImageX设置',           // 页面标题
            'ImageX 设置',                  // 菜单标题
            'manage_options',               // 权限级别
            'dcp-imagex',               // 子菜单 slug
            'imagex_setting_page'          // 回调函数
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
    }
    add_action('admin_menu', 'dcp_setting_parent_menu');

    // DCP Setting页面内容
    function dcp_setting_page_content() {
        $dcp_option = get_option('dcp_general_option', 'default_value');
        $emoji_enabled = get_option('dcp_emoji_comments_enabled', 'yes');
        $markdown_enabled = get_option('dcp_markdown_comments_enabled', 'yes');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('dcp_settings_options_group');
                do_settings_sections('dcp-setting');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">启用Markdown评论</th>
                        <td>
                            <input type="radio" id="markdown_enabled_yes" name="dcp_markdown_comments_enabled" value="yes" <?php checked($markdown_enabled, 'yes'); ?>>
                            <label for="markdown_enabled_yes">是</label><br>
                            <input type="radio" id="markdown_enabled_no" name="dcp_markdown_comments_enabled" value="no" <?php checked($markdown_enabled, 'no'); ?>>
                            <label for="markdown_enabled_no">否</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">评论开启Emoji</th>
                        <td>
                            <input type="radio" id="emoji_enabled_yes" name="dcp_emoji_comments_enabled" value="yes" <?php checked($emoji_enabled, 'yes'); ?>>
                            <label for="emoji_enabled_yes">是</label><br>
                            <input type="radio" id="emoji_enabled_no" name="dcp_emoji_comments_enabled" value="no" <?php checked($emoji_enabled, 'no'); ?>>
                            <label for="emoji_enabled_no">否</label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // 注册DCP Setting设置选项
    function dcp_setting_register_settings() {
        register_setting('dcp_settings_options_group', 'dcp_markdown_comments_enabled');
        register_setting('dcp_settings_options_group', 'dcp_emoji_comments_enabled');
    }
    add_action('admin_init', 'dcp_setting_register_settings');
}

// 初始化插件
function initialize_plugin() {
    setup_comment_features();
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