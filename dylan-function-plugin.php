<?php

// 当评论被回复时触发此函数
function comment_reply_email_notification( $comment_ID, $comment_approved, $commentdata ) {
    // 获取当前回复的评论
    $comment = get_comment( $comment_ID );
    if ( ! $comment ) {
        error_log( '无法获取评论信息，评论 ID: '. $comment_ID );
        return;
    }
    // 如果评论不是回复，则不处理
    if ( $comment->comment_parent == 0 ) {
        return;
    }
    // 获取原评论
    $parent_comment = get_comment( $comment->comment_parent );
    if ( ! $parent_comment ) {
        error_log( '无法获取原评论信息，评论 ID: '. $comment->comment_parent );
        return;
    }
    // 获取原评论者的邮箱
    $parent_comment_author_email = $parent_comment->comment_author_email;
    // 获取文章标题
    $post = get_post( $comment->comment_post_ID );
    if ( ! $post ) {
        error_log( '无法获取文章信息，文章 ID: '. $comment->comment_post_ID );
        return;
    }
    $post_title = $post->post_title;
    // 构建邮件主题
    $subject = '您在 "' . $post_title . '" 的评论有了新回复';

    // 构建 HTML 格式的邮件内容
    $message = '<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
       .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
       .header {
            background-color: #007BFF;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
       .content {
            padding: 20px;
        }
       .comment {
            background-color: #f9f9f9;
            border: 1px solid #eaeaea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
       .footer {
            text-align: center;
            color: #999999;
            font-size: 0.9em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>评论回复通知</h2>
        </div>
        <div class="content">
            <p>您好，' . $parent_comment->comment_author . '！您在文章 "' . $post_title . '" 的评论有了新回复。</p>
            <div class="comment">
                <p><strong>您的评论：</strong></p>
                <p>' . nl2br( $parent_comment->comment_content ) . '</p>
            </div>
            <div class="comment">
                <p><strong>回复内容：</strong></p>
                <p>' . nl2br( $comment->comment_content ) . '</p>
            </div>
            <p>点击下面的链接查看完整评论：</p>
            <p><a href="' . get_comment_link( $parent_comment ) . '">' . get_comment_link( $parent_comment ) . '</a></p>
        </div>
        <div class="footer">
            <p>此邮件由 WordPress 自动发送，请勿直接回复。</p>
        </div>
    </div>
</body>
</html>';

    // 设置邮件头为 HTML 格式
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // 发送邮件
    $mail_sent = wp_mail( $parent_comment_author_email, $subject, $message, $headers );
    if ( ! $mail_sent ) {
        error_log( '邮件发送失败，收件人: '. $parent_comment_author_email );
    }
}

// 绑定到 comment_post 钩子，当新评论发布时触发
add_action('comment_post', 'comment_reply_email_notification', 10, 3 );
add_action('comment_unapproved_to_approved', 'comment_reply_email_notification', 10, 3 );