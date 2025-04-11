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

// 说说页面模板
function custom_shuoshuo_page_template($template) {
    global $post;
    if ($post &&'shuoshuo' === get_post_meta($post->ID, '_wp_page_template', true)) {
        ob_start();
        // 引入 jQuery 库（如果需要，可根据实际情况调整，这里假设保持原需求）
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        // 样式部分保持原代码，这里省略以简化展示，实际使用时需保留完整样式代码
        //...原样式代码...
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main">';
        if (is_single() && get_post_type() ==='shuoshuo') {
            while (have_posts()) {
                the_post();
                get_template_part('template-parts/content-single', get_post_format());
                $args = array(
                    'prev_text' => '<span class="nav-links__label">' . esc_html__('Previous Article', 'cenote') . '</span> %title',
                    'next_text' => '<span class="nav-links__label">' . esc_html__('Next Article', 'cenote') . '</span> %title',
                );
                the_post_navigation($args);
                // 如果评论开启或有评论，加载评论模板
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
            }
        } else {
            $paged = (get_query_var('paged'))? get_query_var('paged') : 1;
            $args = array(
                'post_type' =>'shuoshuo',
                'post_status' => 'publish',
                'posts_per_page' => 20,
                'paged' => $paged
            );
            $query = new WP_Query($args);
            $total_pages = $query->max_num_pages;
            if ($query->have_posts()) {
                echo '<ul class="cbp_tmtimeline" id="shuoshuo-list">';
                while ($query->have_posts()) {
                    $query->the_post();
                    $comment_count = get_comments_number();
                    $post_permalink = get_permalink();
                    echo '<li>';
                    echo '<span class="shuoshuo_author_img">' . get_avatar(get_the_author_meta('ID'), 60) . '</span>';
                    echo '<a class="cbp_tmlabel" href="javascript:void(0)" onclick="window.location.href=\'' . $post_permalink . '\'">';
                    echo '<p>' . the_content() . '</p>';
                    echo '<p class="shuoshuo_time">';
                    echo '<span><i class="fa fa-clock-o"></i>' . the_time('Y年n月j日G:i') . '</span>';
                    echo '<span class="comment-count">评论: ' . $comment_count . '</span>';
                    echo '</p>';
                    echo '</a>';
                    echo '</li>';
                }
                wp_reset_postdata();
                echo '</ul>';
                if ($total_pages > 1) {
                    $big = 999999999;
                    echo '<div class="pagination">';
                    echo paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'current' => max(1, $paged),
                        'total' => $total_pages
                    ));
                    echo '</div>';
                }
            } else {
                echo '<h3 style="text-align: center;">你还没有发表说说噢！</h3>';
                echo '<p style="text-align: center;">赶快去发表你的第一条说说心情吧！</p>';
            }
        }
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
        // 脚本部分保持原代码，这里省略以简化展示，实际使用时需保留完整脚本代码
        //...原脚本代码...
        $content = ob_get_clean();
        return $content;
    }
    return $template;
}
add_filter('template_include', 'custom_shuoshuo_page_template');