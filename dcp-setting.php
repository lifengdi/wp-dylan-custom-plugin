<?php
// 后台菜单页面代码
function setup_admin_menu_pages() {
    // 添加DCP Setting父菜单
    function dcp_setting_parent_menu() {
        add_menu_page(
            '评论扩展',
            'DCP Setting',
            'manage_options',
            'dcp-setting',
            'dcp_setting_page_content',
            'dashicons-admin-generic',
            25
        );
        add_submenu_page(
            'dcp-setting',                  // 父菜单 slug
            '火山引擎ImageX设置',           // 页面标题
            'ImageX 设置',                  // 菜单标题
            'manage_options',               // 权限级别
            'dcp-imagex',               // 子菜单 slug
            'imagex_setting_page'          // 回调函数
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
                        <th scope="row">启用Emoji评论</th>
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

?>