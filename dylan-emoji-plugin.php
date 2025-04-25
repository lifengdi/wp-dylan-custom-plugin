<?php
// 引入必要的WordPress函数
if (!defined('ABSPATH')) {
    exit;
}

// 定义表情包文件夹路径
define('EMOJI_FOLDER', plugin_dir_path(__FILE__) . 'emojis/');

// 获取文件夹名称映射和启用状态
function get_folder_mapping()
{
    return get_option('emoji_folder_mapping', array());
}

function get_folder_enabled_status()
{
    return get_option('emoji_folder_enabled_status', array());
}

// 获取文件夹表情包宽度和高度配置
function get_folder_emoji_width()
{
    return get_option('emoji_folder_emoji_width', array());
}

function get_folder_emoji_height()
{
    return get_option('emoji_folder_emoji_height', array());
}

// 获取表情包全局开关状态
function get_emoji_global_switch()
{
    return get_option('emoji_global_switch', 'on');
}

// 添加表情选择器到评论表单
function add_emoji_picker_to_comment_form($comment_field)
{
    $emoji_global_switch = get_emoji_global_switch();
    if ($emoji_global_switch!== 'on') {
        return $comment_field;
    }

    $folder_mapping = get_folder_mapping();
    $folder_enabled_status = get_folder_enabled_status();
    $folder_emoji_width = get_folder_emoji_width();
    $folder_emoji_height = get_folder_emoji_height();
    $emoji_groups = array();
    $emoji_folders = glob(EMOJI_FOLDER . '*', GLOB_ONLYDIR);
    foreach ($emoji_folders as $folder) {
        $folder_name = basename($folder);
        if (isset($folder_enabled_status[$folder_name]) &&!$folder_enabled_status[$folder_name]) {
            continue;
        }
        $display_name = isset($folder_mapping[$folder_name]) ? $folder_mapping[$folder_name] : $folder_name;
        $emoji_files = glob($folder . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $emojis = array();
        foreach ($emoji_files as $file) {
            $emoji_name = basename($file);
            $emoji_url = plugins_url('emojis/' . $folder_name . '/' . $emoji_name, __FILE__);
            $emojis[] = array(
                'name' => $emoji_name,
                'url' => $emoji_url
            );
        }
        $emoji_groups[$display_name] = $emojis;
    }

    $emoji_html = '<div class="emoji-picker">';
    $emoji_html .= '<button id="emoji-toggle" class="button emoji-tab active">OwO</button>';
    $emoji_html .= '<div class="emoji-container" style="display: none;">';
    $emoji_html .= '<div class="emoji-tabs">';
    $tab_index = 0;
    foreach ($emoji_groups as $group_name => $emojis) {
        $active_class = $tab_index === 0 ? 'active' : '';
        $emoji_html .= '<button class="emoji-tab ' . $active_class . '" data-tab="tab-' . $tab_index . '" type="button">' . esc_html($group_name) . '</button>';
        $tab_index++;
    }
    $emoji_html .= '</div>';

    $emoji_html .= '<div class="emoji-tab-content">';
    $tab_index = 0;
    foreach ($emoji_groups as $group_name => $emojis) {
        $active_class = $tab_index === 0 ? 'active' : '';
        $emoji_html .= '<div id="tab-' . $tab_index . '" class="emoji-group-tab ' . $active_class . '">';
        foreach ($emojis as $emoji) {
            $folder_key = array_search($group_name, $folder_mapping) ?: $group_name;
            $width = isset($folder_emoji_width[$folder_key])? $folder_emoji_width[$folder_key] : '1em';
            $height = isset($folder_emoji_height[$folder_key])? $folder_emoji_height[$folder_key] : '1em';
            $emoji_html .= '<img class="emoji" src="' . esc_url($emoji['url']) . '" data-emoji="[' . $emoji['name'] . ']" alt="' . $emoji['name'] . '" style="width: ' . $width . '; height: ' . $height . ';" />';
        }
        $emoji_html .= '</div>';
        $tab_index++;
    }
    $emoji_html .= '</div>';
    $emoji_html .= '</div>';
    $emoji_html .= '</div>';

    // 添加JavaScript代码来处理表情点击事件、tab切换、弹窗显示隐藏和点击空白关闭
    $emoji_html .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("emoji-toggle");
            const emojiPicker = document.querySelector(".emoji-container");
            const tabs = document.querySelectorAll(".emoji-tab:not(#emoji-toggle)");
            const tabContents = document.querySelectorAll(".emoji-group-tab");
            const emojis = document.querySelectorAll(".emoji");
            const commentField = document.getElementById("comment");

            toggleButton.addEventListener("click", function(event) {
                event.preventDefault(); // 阻止默认跳转行为
                event.stopPropagation();
                if (emojiPicker.style.display === "none") {
                    emojiPicker.style.display = "block";
                    toggleButton.style.display = "none";
                    // 确保第一个分类为激活状态
                    tabs[0].classList.add("active");
                    tabContents[0].classList.add("active");
                } else {
                    emojiPicker.style.display = "none";
                    toggleButton.style.display = "block";
                }
            });

            tabs.forEach((tab, index) => {
                tab.addEventListener("click", function(event) {
                    event.stopPropagation();
                    tabs.forEach(t => t.classList.remove("active"));
                    tabContents.forEach(content => content.classList.remove("active"));

                    tab.classList.add("active");
                    tabContents[index].classList.add("active");
                });
            });

            emojis.forEach(function(emoji) {
                emoji.addEventListener("click", function(event) {
                    event.preventDefault(); // 阻止默认跳转行为
                    event.stopPropagation();
                    if (commentField) {
                        commentField.value += this.dataset.emoji;
                    }
                });
            });

            document.addEventListener("click", function(event) {
                if (!emojiPicker.contains(event.target) && event.target!== toggleButton) {
                    emojiPicker.style.display = "none";
                    toggleButton.style.display = "block";
                }
            });
        });
    </script>';

    return $comment_field . $emoji_html;
}

add_filter('comment_form_field_comment', 'add_emoji_picker_to_comment_form');

// 处理评论中的表情包代码
function process_emoji_in_comment($comment_text)
{
    $emoji_global_switch = get_emoji_global_switch();
    if ($emoji_global_switch!== 'on') {
        return $comment_text;
    }

    $folder_enabled_status = get_folder_enabled_status();
    $folder_emoji_width = get_folder_emoji_width();
    $folder_emoji_height = get_folder_emoji_height();
    $emoji_folders = glob(EMOJI_FOLDER . '*', GLOB_ONLYDIR);
    foreach ($emoji_folders as $folder) {
        $folder_name = basename($folder);
        if (isset($folder_enabled_status[$folder_name]) &&!$folder_enabled_status[$folder_name]) {
            continue;
        }
        $emoji_files = glob($folder . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        foreach ($emoji_files as $file) {
            $emoji_name = basename($file);
            $emoji_url = plugins_url('emojis/' . basename($folder) . '/' . $emoji_name, __FILE__);
            $width = isset($folder_emoji_width[$folder_name])? $folder_emoji_width[$folder_name] : '1em';
            $height = isset($folder_emoji_height[$folder_name])? $folder_emoji_height[$folder_name] : '1em';
            $comment_text = str_replace('[' . $emoji_name . ']', '<img class="comment-emoji" src="' . esc_url($emoji_url) . '" alt="' . $emoji_name . '" style="width: ' . $width . '; height: ' . $height . ';" />', $comment_text);
        }
    }
    return $comment_text;
}

add_filter('comment_text', 'process_emoji_in_comment');

// 添加CSS样式
function add_emoji_css()
{
    echo '<style>
       .emoji-picker {
            position: relative;
            width: 100%;
            margin-top: 10px;
        }
       .emoji-container {
            max-height: 300px; /* 设置表情弹窗的最大高度 */
            overflow-y: auto; /* 超出高度时显示垂直滚动条 */
       }
       .emoji-tabs {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 2px solid #000; /* 底部边框颜色为黑色 */
        }
       .emoji-tab {
            background: #fff; /* 非激活状态背景色为白色 */
            border: none;
            border-radius: 5px 5px 0 0;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 5px;
            color: #000;
            font-weight: 600;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }
       .emoji-tab:hover {
            background: #e0e0e0; /* 悬停时背景色变灰 */
            transform: translateY(-3px); /* 悬停时向上移动 */
        }
       .emoji-tab.active {
            background: #000; /* 激活状态背景色为黑色 */
            color: #fff;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }
       #emoji-toggle {
            background: #000; /* owo按钮默认黑色背景 */
            color: #fff;
            margin-bottom: 10px;
            width: 80px;
        }
       .emoji-tab-content {
            padding-top: 0; /* 去掉顶部内边距，实现 0 间隔 */
            display: flex;
            flex-wrap: wrap;
        }
       .emoji-group-tab {
            display: none;
            width: 100%;
        }
       .emoji-group-tab.active {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
       .emoji {
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
       .emoji:hover {
            background-color: #f0f0f0;
        }
       .comment-emoji {
            width: 20px;
            height: 20px;
        }
       /* 设置滚动条样式 */
       .emoji-picker::-webkit-scrollbar {
            width: 6px;
        }
       .emoji-picker::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
       .emoji-picker::-webkit-scrollbar-thumb {
            background: #000;
            border-radius: 3px;
        }
       .emoji-picker::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
       /* 兼容 Firefox */
       .emoji-picker {
            scrollbar-width: thin;
            scrollbar-color: #000 #f1f1f1;
        }
       /* 后台管理页面样式 */
       .admin-wrap {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
       .admin-wrap h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #2c3e50;
            text-align: center;
        }
       .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
       .admin-table th,
       .admin-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #eaeaea;
            text-align: left;
            font-size: 16px;
        }
       .admin-table th {
            background-color: #f5f7fa;
            font-weight: 600;
            color: #34495e;
        }
       .admin-table input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
       .admin-table input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }
       .admin-table input[type="checkbox"] {
            margin-top: 5px;
        }
       .updated-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>';
}

add_action('wp_head', 'add_emoji_css');

// 生成表情包文件夹映射管理页面
function emoji_plugin_folder_mapping_page()
{
    // 处理表单提交
    if (isset($_POST['submit'])) {
        $folder_mapping = array();
        $folder_enabled_status = array();
        $folder_emoji_width = array();
        $folder_emoji_height = array();
        $emoji_global_switch = isset($_POST['emoji_global_switch'])? 'on' : 'off';
        foreach ($_POST['folder_name'] as $key => $folder) {
            if (!empty($folder)) {
                if (!empty($_POST['display_name'][$key])) {
                    $folder_mapping[$folder] = $_POST['display_name'][$key];
                }
                $folder_enabled_status[$folder] = isset($_POST['folder_enabled'][$key]) && $_POST['folder_enabled'][$key] === 'on';
                $folder_emoji_width[$folder] = $_POST['folder_emoji_width'][$key];
                $folder_emoji_height[$folder] = $_POST['folder_emoji_height'][$key];
            }
        }
        // 保存映射关系到 WordPress 选项中
        update_option('emoji_folder_mapping', $folder_mapping);
        update_option('emoji_folder_enabled_status', $folder_enabled_status);
        update_option('emoji_folder_emoji_width', $folder_emoji_width);
        update_option('emoji_folder_emoji_height', $folder_emoji_height);
        update_option('emoji_global_switch', $emoji_global_switch);
        echo '<div class="updated-message"><p>映射关系、启用状态、表情包宽度和高度配置以及全局开关状态已保存。</p></div>';
    }

    // 获取当前的映射关系和启用状态
    $folder_mapping = get_folder_mapping();
    $folder_enabled_status = get_folder_enabled_status();
    $folder_emoji_width = get_folder_emoji_width();
    $folder_emoji_height = get_folder_emoji_height();
    $emoji_global_switch = get_emoji_global_switch();
    $emoji_folders = glob(EMOJI_FOLDER . '*', GLOB_ONLYDIR);

    // 生成表单
    echo '<div class="admin-wrap">';
    echo '<h1>表情包文件夹映射管理</h1>';
    echo '<form method="post">';
    echo '<table class="admin-table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>全局表情包开关</th>';
    echo '<th><input type="checkbox" name="emoji_global_switch" ' . ($emoji_global_switch === 'on'? 'checked' : '') . '></th>';
    echo '<th>(不兼容markdown)</th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>文件夹名称</th>';
    echo '<th>显示名称</th>';
    echo '<th>是否启用</th>';
    echo '<th>表情包宽度</th>';
    echo '<th>表情包高度</th>';
    echo '</tr>';
    foreach ($emoji_folders as $folder) {
        $folder_name = basename($folder);
        $display_name = isset($folder_mapping[$folder_name]) ? $folder_mapping[$folder_name] : '';
        $enabled = isset($folder_enabled_status[$folder_name])? $folder_enabled_status[$folder_name] : true;
        $width = isset($folder_emoji_width[$folder_name])? $folder_emoji_width[$folder_name] : '1em';
        $height = isset($folder_emoji_height[$folder_name])? $folder_emoji_height[$folder_name] : '1em';
        echo '<tr>';
        echo '<td><input style="width: 150px;" type="text" name="folder_name[]" value="' . esc_attr($folder_name) . '" readonly></td>';
        echo '<td><input style="width: 150px;" type="text" name="display_name[]" value="' . esc_attr($display_name) . '"></td>';
        echo '<td><input type="checkbox" name="folder_enabled[]" ' . ($enabled? 'checked' : '') . '></td>';
        echo '<td><input style="width: 100px;" type="text" name="folder_emoji_width[]" value="' . esc_attr($width) . '"></td>';
        echo '<td><input style="width: 100px;" type="text" name="folder_emoji_height[]" value="' . esc_attr($height) . '"></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<input type="submit" name="submit" value="保存">';
    echo '</form>';
    echo '</div>';
}