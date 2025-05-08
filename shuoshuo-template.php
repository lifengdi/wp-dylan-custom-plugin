<?php

/**
 * Template Name: 说说/微语
 */

get_header();
?>
<!-- 引入 jQuery 库 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* 页面整体背景色，选择莫兰迪色系的浅灰色 */
    body {
        background-color: #f5f5f0;
    }

    body.theme-dark .cbp_tmtimeline::before {
        background: RGBA(255, 255, 255, 0.06)
    }

    ul.cbp_tmtimeline {
        padding: 0
    }

    div.cbp_tmlabel > li .cbp_tmlabel {
        margin-bottom: 0
    }

    .cbp_tmtimeline {
        margin: 30px 0 0 0;
        padding: 0;
        list-style: none;
        position: relative
    }

    .cbp_tmtimeline > li .cbp_tmtime {
        display: block;
        max-width: 70px;
        position: absolute
    }

    .cbp_tmtimeline > li .cbp_tmtime span {
        display: block;
        text-align: right
    }

    .cbp_tmtimeline > li .cbp_tmtime span:first-child {
        font-size: 0.9em;
        color: #888888; /* 莫兰迪色系的深灰色 */
    }

    .cbp_tmtimeline > li .cbp_tmtime span:last-child {
        font-size: 1.2em;
        color: #759375; /* 莫兰迪色系的绿色 */
    }

    .cbp_tmtimeline > li:nth-child(odd) .cbp_tmtime span:last-child {
        color: RGBA(255, 125, 73, 0.75)
    }

    div.cbp_tmlabel > p {
        margin-bottom: 0
    }

    /* 调整列表项与头像的间距 */
   .cbp_tmtimeline > li .cbp_tmlabel {
        margin: 0 0 45px 75px; /* 减少左边距，拉近与头像的距离 */
        padding: 1.2em 1.5em;
        font-weight: 300;
        line-height: 1.6;
        position: relative;
        border-radius: 10px;
        transition: all 0.3s ease 0s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* 更柔和的阴影效果 */
        cursor: pointer;
        display: block;
    }

    .cbp_tmlabel:hover {
        transform: translateY(-3px);
        z-index: 1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* 悬停时更明显的阴影效果 */
    }

    /* 调整箭头的位置 */
   .cbp_tmtimeline > li .cbp_tmlabel:after {
        right: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-width: 10px;
        top: 20px; /* 调整箭头的垂直位置 */
    }

    p.shuoshuo_time {
        margin-top: 15px;
        border-top: 1px dashed #ccc; /* 莫兰迪色系的浅灰色线条 */
        padding-top: 8px;
        font-family: 'Playfair Display', serif; /* 更具艺术感的字体 */
        font-size: 0.9em;
        color: #888888;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media screen and (max-width:65.375em) {
        .cbp_tmtimeline > li .cbp_tmtime span:last-child {
            font-size: 1.2em
        }
    }

    .shuoshuo_author_img img {
        border: 2px solid #fff;
        padding: 2px;
        float: left;
        border-radius: 50%;
        transition: all 1.0s;
        height: 60px;
        margin-right: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .avatar {
        -webkit-border-radius: 100% !important;
        -moz-border-radius: 100% !important;
        box-shadow: inset 0 -1px 0 #3333sf;
        -webkit-box-shadow: inset 0 -1px 0 #3333sf;
        -webkit-transition: 0.4s;
        -webkit-transition: -webkit-transform 0.4s ease-out;
        transition: transform 0.4s ease-out;
        -moz-transition: -moz-transform 0.4s ease-out
    }

    .zhuan {
        transform: rotateZ(720deg);
        -webkit-transform: rotateZ(720deg);
        -moz-transform: rotateZ(720deg)
    }

    /* 定义更多不同的莫兰迪色系背景颜色，增加多样性 */
   .cbp_tmtimeline > li:nth-child(6n + 1) .cbp_tmlabel {
        background-color: #e0c6d2;
    }

   .cbp_tmtimeline > li:nth-child(6n + 2) .cbp_tmlabel {
        background-color: #c8d5e0; /* 浅蓝色 */
    }

   .cbp_tmtimeline > li:nth-child(6n + 3) .cbp_tmlabel {
        background-color: #d2e0c6; /* 浅绿色 */
    }

   .cbp_tmtimeline > li:nth-child(6n + 4) .cbp_tmlabel {
        background-color: #e0d2c6; /* 浅橙色 */
    }

   .cbp_tmtimeline > li:nth-child(6n + 5) .cbp_tmlabel {
        background-color: #d2c6e0; /* 浅紫色 */
    }

   .cbp_tmtimeline > li:nth-child(6n) .cbp_tmlabel {
        background-color: #c6d2e0; /* 浅蓝绿色 */
    }

   .cbp_tmtimeline > li:nth-child(6n + 1) .cbp_tmlabel:after {
        border-right-color: #e0c6d2;
    }

   .cbp_tmtimeline > li:nth-child(6n + 2) .cbp_tmlabel:after {
        border-right-color: #c8d5e0;
    }

   .cbp_tmtimeline > li:nth-child(6n + 3) .cbp_tmlabel:after {
        border-right-color: #d2e0c6;
    }

   .cbp_tmtimeline > li:nth-child(6n + 4) .cbp_tmlabel:after {
        border-right-color: #e0d2c6;
    }

   .cbp_tmtimeline > li:nth-child(6n + 5) .cbp_tmlabel:after {
        border-right-color: #d2c6e0;
    }

   .cbp_tmtimeline > li:nth-child(6n) .cbp_tmlabel:after {
        border-right-color: #c6d2e0;
    }

    /* 调整字体样式 */
   .cbp_tmtimeline > li .cbp_tmlabel p {
        margin-bottom: 8px;
        font-family: 'Playfair Display', serif; /* 更具艺术感的字体 */
    }

    /* 增加间距 */
   .cbp_tmtimeline > li {
        margin-bottom: 30px;
    }

    /* 移动端样式调整 */
    @media screen and (max-width: 768px) {
       .cbp_tmtimeline > li .cbp_tmlabel {
            margin: 0 0 30px 60px; /* 进一步减少左边距 */
            padding: 1em 1.2em; /* 减少内边距 */
        }

       .cbp_tmtimeline > li .cbp_tmlabel:after {
            border-width: 8px; /* 缩小箭头尺寸 */
            top: 15px; /* 调整箭头垂直位置 */
        }

        p.shuoshuo_time {
            font-size: 0.8em; /* 缩小时间字体大小 */
        }
    }

    /* 评论数量样式 */
   .comment-count {
        color: #759375;
        text-decoration: none;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    /* 去除a标签默认样式 */
   .cbp_tmlabel a {
        text-decoration: none;
        color: inherit;
    }
   .pagination {
       text-align: center;
   }
    .pagination .page-numbers {
        padding: 5px 10px;
        text-decoration: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
</style>

<div class="shuo-content-area">
    <main id="main" class="site-main" role="main">
        <div class="cbp_shuoshuo">
            <?php
            $paged = (get_query_var('paged'))? get_query_var('paged') : 1;
            // 使用 WP_Query 代替 query_posts
            $args = array(
                'post_type' =>'shuoshuo',
                'post_status' => 'publish',
                'posts_per_page' => 20,
                'paged' => $paged
            );
            $query = new WP_Query($args);
            $total_pages = $query->max_num_pages;
            if ($query->have_posts()) : ?>
                <ul class="cbp_tmtimeline" id="shuoshuo-list">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        $comment_count = get_comments_number();
                        $post_permalink = get_permalink();
                        ?>
                        <li>
                            <span class="shuoshuo_author_img"><?php echo get_avatar(get_the_author_meta('ID'), 60); ?></span>
                            <a class="cbp_tmlabel" href="javascript:void(0)"  onclick="window.location.href='<?php echo $post_permalink; ?>'">
                                <p><?php the_content(); ?></p>
                                <p class="shuoshuo_time">
                                    <span><?php the_time('Y年n月j日G:i'); ?></span>
                                    <span class="comment-count">
                                        评论: <?php echo $comment_count; ?>
                                    </span>
                                </p>
                            </a>
                        </li>
                    <?php endwhile;
                    wp_reset_postdata(); // 重置查询
                    ?>
                </ul>
                <?php
                if ($total_pages > 1) {
                    $big = 999999999; // 需要一个不太可能的整数
                    echo '<div class="pagination">';
                    echo paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'current' => max(1, $paged),
                        'total' => $total_pages
                    ));
                    echo '</div>';
                }
                ?>
            <?php
            else : ?>
                <h3 style="text-align: center;">你还没有发表说说噢！</h3>
                <p style="text-align: center;">赶快去发表你的第一条说说心情吧！</p>
            <?php
            endif; ?>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->
<script type="text/javascript">
    $(function () {
        var oldClass = "";
        var Obj = "";
        $(".cbp_tmtimeline li").hover(function () {
            Obj = $(this).children(".shuoshuo_author_img");
            Obj = Obj.children("img");
            oldClass = Obj.attr("class");
            var newClass = oldClass + " zhuan";
            Obj.attr("class", newClass);
        }, function () {
            Obj.attr("class", oldClass);
        });
    });
</script>
<?php
get_footer();