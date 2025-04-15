<?php
/*
Plugin Name: Dylan Custom Plugin
Plugin URI:
Description: 可自定义展示分类、日期归档、标签列表。新增 “说说” 功能，能创建独特说说文章，用短代码灵活调用，还提供专属页面模板，优化内容呈现，助力打造更丰富有序的网站。
Version: 1.0.1
Author: Dylan Li
Author URI: https://www.lifengdi.com
License: GPL2
*/
// 引入说说相关功能文件
require_once plugin_dir_path( __FILE__ ).'shuoshuo-functions.php';

require_once plugin_dir_path( __FILE__ ).'custom-archive-plugin.php';

require_once plugin_dir_path( __FILE__ ).'imagex.php';