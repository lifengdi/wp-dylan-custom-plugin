<?php

$comment_title = '条评论';
add_shortcode('dcp_custom_timeline', 'custom_timeline');

function custom_timeline($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '',
            'per_page' => get_option('custom_time_line_showposts', 20),
        ),
        $atts,
        'custom_timeline'
    );

    $category = $atts['category'];
    $per_page = $atts['per_page'];

    $css = get_option('custom_time_line_css');
    $html = <<<CUSTOM
    <style>
    {$css}
    </style>
    CUSTOM;
    $html .= '<div class="custom-archive">';
    $paged = get_query_var('paged');
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $per_page,
        'ignore_sticky_posts' => 1,
        'paged' => $paged? $paged : 1,
    ];

    if (!empty($category)) {
        if (is_numeric($category)) {
            $args['cat'] = $category;
        } else {
            $args['category_name'] = $category;
        }
    }

    $category_not_in = get_option('custom_time_line_category_notin');
    if (!empty($category_not_in)) {
        $args['category__not_in'] = explode(',', $category_not_in);
    }

    $the_query = new WP_Query($args);
    $output = '';
    $posts_rebuild = array();
    while ($the_query->have_posts()) : $the_query->the_post();
        $post_year = get_the_time('Y');
        $post_mon = get_the_time('m');
        $posts_rebuild[$post_year][$post_mon][] = '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> <span class="custom-sub-title">' . get_the_time('m-d') . ' / ' . get_comments_number('0', '1', '%') . $GLOBALS['comment_title'] . '</span></li>';
    endwhile;
    wp_reset_postdata();

    foreach ($posts_rebuild as $key => $value) {
        $output .= '<h3 class="custom-year">' . $key . '</h3>';
        $year = $key;
        foreach ($value as $key_m => $value_m) {
            $output .= '<h3 class="custom-month">' . $year . ' - ' . $key_m . '</h3><ul class="custom-post-list">';
            foreach ($value_m as $key => $value_d) {
                $output .= $value_d;
            }
            $output .= '</ul>';
        }
    }
    $html .= $output;
    $html .= '</div>';
    $html .= custom_paged_nav($the_query, $paged, 2);
    return custom_compress_html($html);
}


function custom_paged_nav($query, $paged, $p = 2) {
    $html = '';
    $max_page = $query->max_num_pages;
    if ($max_page > 1) {
        if (empty($paged)) {
            $paged = 1;
        }
        $html .= '<div class="custom-pagination">';
        if ($paged > $p + 1) {
            $html .= custom_p_link(1, '最前页', '«');
        }

        if ($paged > 1) {
            $html .= custom_p_link($paged - 1, '上一页', '‹');
        }
        if ($paged > $p + 2) {
            $html .= '... ';
        }
        for ($i = $paged - $p; $i <= $paged + $p; $i++) {
            if ($i > 0 && $i <= $max_page) {
                if ($i == $paged) {
                    $html .= "<span class='page-numbers current'>{$i}</span>";
                } else {
                    $html .= custom_p_link($i);
                }
            }
        }
        if ($paged < $max_page - $p - 1) {
            $html .= '<span class="page-numbers">...</span>';
        }
        if ($paged < $max_page) {
            $html .= custom_p_link($paged + 1, '下一页', '›');
        }
        if ($paged < $max_page - $p) {
            $html .= custom_p_link($max_page, '最后页', '»');
        }
        $html .= '</div>';
    }
    return $html;
}

function custom_p_link($i, $title = '', $linktype = '') {
    if ($title == '') {
        $title = "第 {$i} 页";
    }

    if ($linktype == '') {
        $linktext = $i;
    } else {
        $linktext = $linktype;
    }
    return "<a class='page-numbers' href='" . esc_html(get_pagenum_link($i)) . "' title='{$title}'>{$linktext}</a> ";
}

function custom_compress_html($string) {
    // Remove newline characters
    $string = str_replace(array("\r\n", "\r", "\n"), ' ', $string);
    // Remove tab characters
    $string = str_replace("\t", ' ', $string);

    // Remove spaces around HTML comments
    $string = preg_replace('/<!--\s*([^>]*)\s*-->/s', '<!--$1-->', $string);

    // Remove spaces before closing tags
    $string = preg_replace('/>\s+</', '><', $string);

    // Remove spaces around attributes
    $string = preg_replace('/"\s+/', '"', $string);
    $string = preg_replace('/\s+"/', '"', $string);

    // Remove spaces around single quotes attributes
    $string = preg_replace("/'\s+/", "'", $string);
    $string = preg_replace("/\s+'/", "'", $string);

    // Remove spaces inside comment blocks
    $string = preg_replace('/\s+/', ' ', $string);

    // Remove empty spaces
    $string = trim($string);

    return $string;
}

function custom_time_line_display_function() {
    $update = false;
    // 初始化默认的CSS样式
    $default_css = <<<DEFAULT_CSS
   .custom-archive {
        position: relative;
        font-size: 16px;
        color: rgba(0, 0, 0, 0.6);
    }

   .custom-archive:before {
        content: "";
        width: 3px;
        background-color: rgba(0, 0, 0, 0.05);
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100px;
    }
   .custom-archive h3{
        border:0 !important;
    }
    h3.custom-year {
        display: inline-block;
        background-color: #fafafa;
        border: 1px solid rgba(0, 0, 0, 0.05);
        color: rgba(0, 0, 0, 0.44);
        padding: 1px 0;
        width: 120px;
        margin-left: 40px;
        text-align: center;
        position: relative;
        border-radius: 3px;
        margin-top: 30px;
        margin-bottom: 10px;
        font-weight: bold;
    }
    h3.custom-month {
        position: relative;
        font-weight: 700;
        margin:0 0 15px;
        padding:0;
        font-size: 18px;
        line-height: 25px;
        color: #999;
    }
   .custom-month:before,
   .custom-month:after {
        content: "";
        background-color: #fff;
        height: 19px;
        width: 19px;
        border-radius: 100%;
        position: absolute;
        left: 92px;
        top: 3px;
    }
   .custom-month:after {
        height: 15px;
        width: 15px;
        background-color: #eee;
        left: 94px;
        top: 5px;
    }
   .custom-month:hover:after {
        background-color: #73B66B;
    }
   .custom-post-list {
        margin:0 0 30px 100px !important;
        margin-left: 100px !important;
        margin-bottom: 30px !important;
    }
   .custom-post-list .date {
        margin-left: -80px;
        width: 80px;
        display: inline-block;
    }
   .custom-post-list li {
        margin-top:10px !important;
        position: relative;
        padding-left: 20px;
        list-style: none !important;
    }
   .custom-post-list li:before,
   .custom-post-list li:after {
        content: "";
        background-color: #fff;
        height: 13px;
        width: 13px;
        border-radius: 100%;
        position: absolute;
        left: -5px;
        top: 7px;
    }
   .custom-post-list li:after {
        height: 9px;
        width: 9px;
        background-color: #eee;
        left: -3px;
        top: 9px;
    }
   .custom-post-list li:hover:after {
        background-color: #73B66B;
    }
   .custom-pagination{
        margin: 0;
        padding: 0;
        text-align: center;
    }
   .custom-pagination a{
        display: inline-block;
        margin: 0 5px;
        font-size: 14px;
        color: #555;
        line-height: 20px;
        padding: 3px 10px;
        background: #fff;
        border: 1px #ddd solid;
    }
   .custom-pagination a:hover{
        background: #eee;
    }
   .custom-pagination span{
        display: inline-block;
        margin: 0 5px;
        font-size: 14px;
        line-height: 20px;
        color: #555;
        padding: 0px;
    }
   .custom-sub-title{
        color:#b0c4ca;
        margin-left:30px;
    }
   .custom-archive a:hover {
        text-decoration: none;
    }
DEFAULT_CSS;

    if (!get_option('custom_time_line_css')) {
        update_option('custom_time_line_css', $default_css);
    }

    if ($_POST['custom_time_line_showposts']) {
        update_option('custom_time_line_showposts', trim($_POST['custom_time_line_showposts']));
        update_option('custom_time_line_category_notin', trim($_POST['custom_time_line_category_notin']));
        update_option('custom_time_line_css', trim($_POST['custom_time_line_css']));
        $update = true;
    }
    ?>
    <div class="wrap" id="profile-page">
        <?php if ($update) {
            echo '<div class="notice notice-info"><p>更新成功</p></div>';
        }
        ?>
        <form method="post" name="custom_seting" id="custom_seting">
            <h2>时间轴设置</h2>
            <table class="form-table">
                <tbody>
                <tr class="form-field">
                    <th scope="row"><label for="custom_time_line_showposts">每页显示条数</label></th>
                    <td>
                        <input name="custom_time_line_showposts" type="text" id="custom_time_line_showposts" value="<?php echo get_option('custom_time_line_showposts'); ?>" style="max-width: 500px;" />
                        <p>不设置默认显示20条，设置为 -1 显示全部。</p>
                    </td>
                </tr>

                <tr class="form-field">
                    <th scope="row"><label for="custom_time_line_category_notin">要排除的分类</label></th>
                    <td>
                        <input name="custom_time_line_category_notin" type="text" id="custom_time_line_category_notin" value="<?php echo get_option('custom_time_line_category_notin'); ?>" style="max-width: 500px;" />
                        <p>多个分类ID请用英文逗号分开，请对照下面分类对照表进行设置。</p>
                    </td>
                </tr>

                <tr class="form-field">
                    <th scope="row"><label for="custom_time_line_css">额外的CSS样式</label></th>
                    <td>
                        <textarea name="custom_time_line_css" id="custom_time_line_css" rows="15" cols="30"><?php echo get_option('custom_time_line_css'); ?></textarea>
                        <p>自定义CSS区域，会在时间轴上方的CSS区域调用，前后不用添加style标签。默认样式已填充，可直接修改。</p>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php submit_button('保存设置'); ?>
        </form>
        <h3>插件使用方法：</h3>
        <p>新建一个页面，在内容区域输入简码： <code>[dcp_custom_timeline category="分类ID" per_page="每页显示数量"]</code></p>
        <h3>分类对照表：<small style="color: #e00">分类 - id</small></h3>
        <style>.custom-cat {
                margin-right: 20px;
                background: #d2e9f3;
                padding: 5px
            }</style>
        <p><?php
            foreach (get_categories() as $cat) {
                echo '<span class="custom-cat">' . $cat->cat_name . ' <code>' . $cat->cat_ID . '</code></span>';
            }
            ?></p>
    </div>
    <?php
}