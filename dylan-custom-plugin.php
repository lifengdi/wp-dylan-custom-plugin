<?php
/*
Plugin Name: Dylan Custom Plugin
Plugin URI:
Description: 可自定义展示分类、日期归档、标签列表。能创建独特说说文章，还提供专属页面模板，优化内容呈现。支持火山引擎图片服务（ImageX）作为附件存储空间，助力打造更丰富有序的网站。
Version: 1.0.4
Author: Dylan Li
Author URI: https://www.lifengdi.com
License: GPL2
*/

define('DCLYN_CUSTOM_PLUGIN_VERSION', '1.0.4');

// 引入说说相关功能文件
require_once plugin_dir_path( __FILE__ ).'shuoshuo-functions.php';

require_once plugin_dir_path( __FILE__ ).'custom-archive-plugin.php';

require_once plugin_dir_path( __FILE__ ).'imagex.php';

require_once plugin_dir_path( __FILE__ ).'stock-monitor.php';

require_once plugin_dir_path( __FILE__ ).'dcp-setting.php';

require_once plugin_dir_path(__FILE__) . 'comments/dylan-comments.php';

require_once plugin_dir_path(__FILE__) . 'dylan-emoji-plugin.php';

require_once plugin_dir_path( __FILE__ ).'timeline.php';

require_once plugin_dir_path( __FILE__ ).'custom-countdown-plugin.php';
