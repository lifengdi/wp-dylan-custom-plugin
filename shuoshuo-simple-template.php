<?php
/**
 * Template Name: 简约风说说
 */

get_header();
?>

<!-- 引入 Font Awesome 图标库 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 30px;
        padding: 24px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

   .shuoshuo-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

   .shuoshuo-author {
        display: flex;
        align-items: flex-start;
        margin-bottom: 16px;
    }

   .shuoshuo-author img {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        margin-right: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

   .author-info {
        display: flex;
        flex-direction: column;
    }

   .shuoshuo-author-name {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }

   .shuoshuo-time {
        font-size: 14px;
        color: #606770;
        margin-top: 6px;
    }

   .shuoshuo-content {
        font-size: 16px;
        line-height: 1.75;
        color: #333;
        margin-bottom: 20px;
    }

   .interaction-bar {
        padding-top: 20px;
        border-top: 1px solid #f0f2f5;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        font-size: 14px;
    }

   .comment-action {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #606770;
        transition: color 0.2s ease;
    }

   .comment-count {
        font-weight: 500;
        color: #333;
    }

    @media screen and (max-width: 768px) {
       .shuoshuo-container {
            padding: 16px;
        }
       .shuoshuo-author img {
            width: 48px;
            height: 48px;
        }
       .shuoshuo-author-name {
            font-size: 16px;
        }
    }
</style>

<div id="primary" class="content-area" style="width:100%">
    <main id="main" class="site-main" role="main">
        <div class="shuoshuo-container">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type'      => 'shuoshuo',
                'post_status'    => 'publish',
                'posts_per_page' => 20,
                'paged'          => $paged
            );
            $query = new WP_Query($args);
            $total_pages = $query->max_num_pages;
            ?>

            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="shuoshuo-item">
                    <div class="shuoshuo-author">
                        <?php echo get_avatar(get_the_author_meta('ID'), 56); ?>
                        <div class="author-info">
                            <span class="shuoshuo-author-name"><?php the_author(); ?></span>
                            <span class="shuoshuo-time"><?php the_time('Y年n月j日 G:i'); ?></span>
                        </div>
                    </div>

                    <div class="shuoshuo-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="interaction-bar">
                        <!-- 评论数量显示区 -->
                        <a href="<?php echo get_permalink(); ?>" class="comment-action">
                            <i class="fa-regular fa-comment"></i>
                            <span class="comment-count"><?php echo get_comments_number() ?: '0'; ?></span> 条评论
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>

                <?php if ($total_pages > 1) : ?>
                <div class="pagination" style="text-align: center; margin-top: 30px;">
                    <?php echo paginate_links(array(
                        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format'    => '?paged=%#%',
                        'current'   => max(1, $paged),
                        'total'     => $total_pages
                    )); ?>
                </div>
                <?php endif; ?>

            <?php else : ?>
                <h3 style="text-align: center; margin-top: 50px;">你还没有发表说说噢！</h3>
                <p style="text-align: center; color: #666;">赶快去发表你的第一条说说心情吧！</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>