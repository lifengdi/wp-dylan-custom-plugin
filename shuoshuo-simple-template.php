<?php

/**
 * Template Name: 简约风说说
 */

get_header();
?>
<style>
    body {
        background-color: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

   .shuoshuo-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

   .shuoshuo-item {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 20px;
        transition: box-shadow 0.3s ease;
    }

   .shuoshuo-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

   .shuoshuo-author {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

   .shuoshuo-author img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

   .shuoshuo-author-name {
        font-weight: bold;
    }

   .shuoshuo-content {
        line-height: 1.6;
        margin-bottom: 10px;
    }

   .shuoshuo-time {
        font-size: 0.9em;
        color: #888;
    }

   .shuoshuo-comment-count {
        color: #007BFF;
        text-decoration: none;
        margin-left: 10px;
    }

   .shuoshuo-comment-count:hover {
        text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
       .shuoshuo-container {
            padding: 10px;
        }
    }
</style>

<div id="primary" class="content-area" style="width:100%">
    <main id="main" class="site-main" role="main">
        <div class="shuoshuo-container">
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

                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        $comment_count = get_comments_number();
                        $post_permalink = get_permalink();
                        ?>
                        <div class="shuoshuo-item">
                <div class="shuoshuo-author">
                    <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                    <span class="shuoshuo-author-name"><?php the_author(); ?></span>
                </div>
                <div class="shuoshuo-content">
                    <?php the_content(); ?>
                </div>
                <div class="shuoshuo-time">
                    <?php the_time('Y年n月j日G:i'); ?>
                    <a href="<?php echo $post_permalink; ?>" class="shuoshuo-comment-count">
                        评论: <?php echo $comment_count; ?>
                    </a>
                </div>
            </div>
                    <?php endwhile;
                    wp_reset_postdata(); // 重置查询
                    ?>
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