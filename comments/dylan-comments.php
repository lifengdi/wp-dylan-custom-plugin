<?php
// 引入Parsedown库
require_once( plugin_dir_path( __FILE__ ) . '../sdk/parsedown/Parsedown.php' );


function setup_comment_features() {
    // 根据开关状态处理评论
    function markdownify_comment_text( $comment_text ) {
        global $markdown_parser;
        $markdown_enabled = get_option('dcp_markdown_comments_enabled', 'yes');
        if ($markdown_enabled === 'yes') {
            try {
                // 创建Parsedown实例，避免重复创建
                $markdown_parser = new Parsedown();
                $markdown_parser->setSafeMode( true );
                $comment_text = $markdown_parser->text( $comment_text );
            } catch (Exception $e) {
                // 捕获并记录异常，保证程序稳定运行
                error_log( 'Markdown Comment Plugin: Error converting Markdown: ' . $e->getMessage() );
            }
        }
        return $comment_text;
    }
    add_filter( 'comment_text', 'markdownify_comment_text' );

    function markdown_comment_text( $field ) {
        $emoji_enabled = get_option('dcp_emoji_comments_enabled', 'yes');
        $emoji_output = '';
        if ($emoji_enabled === 'yes') {
            $emoji_groups = array(
                '😀' => array(
                    '😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚',
                    '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😳',
                    '🥵', '🥶', '😱', '😨', '😰', '😥', '😢', '😭', '😓', '🤤', '😪', '😴', '🤯', '😵', '🥳', '🥸', '🤠', '😎', '🥺', '🤧',
                    '😮', '😲', '😴', '😷', '🤢', '🤮', '🤧', '😵‍💫', '😇', '😌', '😛', '😜', '😝'
                ),
                '⚽' => array(
                    '⚽', '🏀', '🏈', '⚾', '🥎', '🎾', '🏐', '🏉', '🥏', '🎱', '🏓', '🏸', '🥊', '🥋', '🏹', '🛷', '🥌', '⛸️', '🏂', '🏌️',
                    '🏊', '🚴', '🚵', '🤸', '🤼', '🤽', '🎣', '🎽', '🎿', '🏇', '🏎️', '🚗', '🚕', '🚙', '🚌', '🚎', '🏍️', '🛵', '🚲', '🛴',
                    '🚤', '🛶', '🚣‍♂️', '🚣‍♀️', '🚁', '✈️', '🚢', '🚀', '🛸', '🚜', '🚛', '🚐', '🚟', '🚠', '🚡'
                ),
                '🍔' => array(
                    '🍇', '🍈', '🍉', '🍊', '🍋', '🍌', '🍍', '🥭', '🍎', '🍏', '🍐', '🍑', '🍒', '🍓', '🥝', '🍅', '🥥', '🥑', '🍆', '🥔',
                    '🥕', '🌽', '🌶️', '🥒', '🥬', '🥦', '🧄', '🧅', '🍄', '🥜', '🌰', '🍞', '🥐', '🥖', '🥨', '🥯', '🍳', '🧇', '🥞', '🧈',
                    '🍕', '🍝', '🍟', '🍔', '🍦', '🍧', '🍰', '🎂', '🍮', '🍭', '🍬', '🍿', '🌰', '🌯', '🥪', '🥙', '🌮', '🌭'
                ),
                '🐶' => array(
                    '🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔', '🐧', '🐦', '🐤', '🐣',
                    '🦆', '🦅', '🦉', '🦇', '🐺', '🐗', '🐴', '🦄', '🐝', '🐛', '🦋', '🐌', '🐚', '🐞', '🦗', '🕷️', '🦂', '🐢', '🐍', '🦎',
                    '🐉', '🌲', '🌳', '🌴', '🌵', '🌱', '🌿', '🍀', '🍃', '🍂', '🍁', '🌾', '🌻', '🌼', '🌸', '🌹', '🥀', '🌺', '🌷', '💐',
                    '🐘', '🦛', '🦏', '🦒', '🦘', '🐫', '🦕', '🦖', '🐙', '🦑', '🦐', '🦞', '🦀', '🐡', '🐠', '🐟', '🐬', '🐳', '🐋'
                ),
                '🏝️' => array(
                    '🏠', '🏡', '🏢', '🏣', '🏥', '🏦', '🏨', '🏪', '🏫', '🏬', '🏭', '🏯', '🏰', '🗼', '🗽', '⛪', '🕌', '🕍', '🛕', '🕋',
                    '⛰️', '🌋', '🏞️', '🏝️', '🏜️', '🌅', '🌄', '🌠', '🌙', '🌕', '🌖', '🌗', '🌘', '🌑', '🌒', '🌓', '🌔', '🌛', '🌜', '⭐',
                    '☁️', '🌤️', '⛅', '🌥️', '🌦️', '🌧️', '⛈️', '🌩️', '🌨️', '❄️', '💨', '💧', '🌊', '🌀', '🌈', '🌫️', '🌪️', '🌬️', '🌌', '☄️',
                    '🏝', '🌃', '🌆', '🌇', '🌉', '🌌', '🌏', '🌍', '🌎', '🗺️', '🏔️', '🌁', '🌃', '🌆', '🌇', '🌉', '🌌', '🌏', '🌍', '🌎'
                ),
                '📱' => array(
                    '📱', '💻', '🖥️', '🖨️', '🖱️', '🖲️', '💽', '💾', '💿', '📀', '🎥', '📷', '📸', '📹', '🎞️', '📽️', '🔍', '🔎', '📡', '📺',
                    '📻', '💡', '🔦', '🪔', '🕯️', '🗄️', '📦', '📁', '📂', '🗃️', '🗳️', '🔒', '🔓', '🔏', '🔐', '🔑', '🔨', '🪓', '⚒️', '🛠️', '🗜️',
                    '⌨️', '🖱', '🖨', '💾', '💿', '📀', '🎞', '📽', '📷', '📸', '📹', '🎥', '📺', '📻', '📼', '💽', '💻', '🖥', '🖨'
                ),
                '❤️' => array(
                    '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '❣️', '💌',
                    '🌟', '✨', '💫', '⭐', '⚡', '❄️', '☃️', '⛄', '🔥', '💧', '💨', '💥', '💢', '💦', '💣', '💊', '💉', '🚬', '⚰️', '⚱️',
                    '☠️', '♻️', '🚮', '🚰', '🔰', '⚠️', '🚸', '📢', '📣', '📯', '🔔', '🔕', '🎵', '🎶', '🔊', '🔉', '🔈', '📴', '📵', '🈚', '🈲',
                    '💯', '✔️', '❌', '❗', '❓', '❔', '⚠', '🚫', '✅', '❎', '🔞', '📛', '📜', '📚', '📃', '📄', '📑', '📊', '📈', '📉'
                ),
                '🦘' => array(
                    '🦘', '🦒', '🦛', '🦏', '🦢', '🦩', '🦚', '🦜', '🦘', '🦔', '🦇', '🐊', '🐡', '🐠', '🐟', '🦈', '🐬', '🐋', '🐳', '🌊',
                    '🌋', '🌌', '🌠', '🌍', '🌎', '🌏', '🪐', '🛸', '🌌', '🪨', '🌋', '🌄', '🌅', '🌆', '🌇', '🌉', '🌌', '🌌', '🌌'
                ),
                '🚢' => array(
                    '🚢', '🛳️', '⛴️', '🚤', '🛥️', '🚁', '🚂', '🚃', '🚄', '🚅', '🚇', '🚈', '🚊', '🚋', '🚍', '🚐', '🚕', '🚖', '🚗', '🚘'
                ),
                '🍰' => array(
                    '🍰', '🎂', '🍮', '🍭', '🍬', '🍫', '🍿', '🧁', '🍯', '🍼', '☕', '🍵', '🍾', '🍷', '🍸', '🍹', '🥂', '🍺', '🍻', '🍶'
                ),
                '🎭' => array(
                    '🎭', '🎨', '🎬', '🎤', '🎧', '🎼', '🎹', '🎺', '🎻', '🥁', '🎯', '🎳', '🎰', '🎲', '🃏', '🀄', '🎮', '🕹️', '🎬', '🎥'
                )
            );
            ob_start();
            ?>
            <div class="emoji-picker">
                <button id="open-emoji-picker" class="emoji-button" type="button">😀</button>
                <div id="emoji-container" style="display: none;">
                    <div class="emoji-tabs">
                        <?php
                        $tabIndex = 0;
                        foreach ( $emoji_groups as $group_name => $emojis ) {
                            $activeClass = $tabIndex === 0 ? 'active' : '';
                            echo '<button class="emoji-tab '. $activeClass .'" data-tab="tab-'. $tabIndex .'" type="button">'. esc_html( $group_name ) .'</button>';
                            $tabIndex++;
                        }
                        ?>
                    </div>
                    <div class="emoji-tab-content">
                        <?php
                        $tabIndex = 0;
                        foreach ( $emoji_groups as $group_name => $emojis ) {
                            $activeClass = $tabIndex === 0 ? 'active' : '';
                            echo '<div id="tab-'. $tabIndex .'" class="emoji-group-tab '. $activeClass .'">';
                            foreach ( $emojis as $emoji ) {
                                echo '<span class="emoji" data-emoji="'. esc_attr( $emoji ) .'">'. $emoji .'</span>';
                            }
                            echo '</div>';
                            $tabIndex++;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const openEmojiPickerButton = document.getElementById('open-emoji-picker');
                    const emojiContainer = document.getElementById('emoji-container');
                    const commentField = document.getElementById('comment');

                    openEmojiPickerButton.addEventListener('click', function(event) {
                        event.stopPropagation();
                        if (emojiContainer.style.display === 'none' || emojiContainer.style.display === '') {
                            emojiContainer.style.display = 'block';
                            openEmojiPickerButton.style.display = 'none'; // 显示表情容器时隐藏按钮
                        } else {
                            emojiContainer.style.display = 'none';
                            openEmojiPickerButton.style.display = 'block'; // 隐藏表情容器时显示按钮
                        }
                    });

                    const tabs = document.querySelectorAll('.emoji-tab');
                    const tabContents = document.querySelectorAll('.emoji-group-tab');

                    tabs.forEach((tab, index) => {
                        tab.addEventListener('click', function(event) {
                            event.stopPropagation();
                            tabs.forEach(t => t.classList.remove('active'));
                            tabContents.forEach(content => content.classList.remove('active'));

                            tab.classList.add('active');
                            tabContents[index].classList.add('active');
                        });
                    });

                    const emojis = document.querySelectorAll('.emoji');
                    emojis.forEach(function(emoji) {
                        emoji.addEventListener('click', function(event) {
                            event.stopPropagation();
                            if (commentField) {
                                commentField.value += this.dataset.emoji;
                            }
                        });
                    });

                    document.addEventListener('click', function(event) {
                        if (!emojiContainer.contains(event.target) && event.target!== openEmojiPickerButton) {
                            emojiContainer.style.display = 'none';
                            openEmojiPickerButton.style.display = 'block'; // 点击外部时显示按钮
                        }
                    });
                });
            </script>
            <style>
                .emoji-picker {
                    position: relative;
                }
                .emoji-button {
                    background: none;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    font-size: 1.5em;
                    cursor: pointer;
                    padding: 8px 12px;
                }
                .emoji-container {
                    position: absolute;
                    background: white;
                    border: 2px solid #999;
                    border-radius: 8px;
                    padding: 15px;
                    z-index: 10;
                    max-height: 350px;
                    width: 300px;
                    display: flex;
                    flex-direction: column;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    /* 移动端适配 */
                    @media (max-width: 768px) {
                        width: 280px;
                    }
                }
                .emoji-tabs {
                    display: flex;
                    flex-wrap: nowrap; /* 允许tab页换行 */
                    border-bottom: 1px solid #ccc;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
                .emoji-tabs::-webkit-scrollbar {
                    display: none;
                }
                .emoji-tab {
                    background: none;
                    border: 1px solid transparent;
                    border-bottom: none;
                    border-radius: 4px 4px 0 0;
                    padding: 8px 12px; /* 减少tab页内边距 */
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    font-size: 1.5em;
                }
                .emoji-tab.active {
                    background-color: #f0f0f0;
                    border-color: #ccc;
                }
                .emoji-tab-content {
                    padding-top: 10px;
                    flex: 1;
                    overflow-y: auto;
                }
                .emoji-group-tab {
                    display: none;
                }
                .emoji-group-tab.active {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(30px, 1fr)); /* 根据屏幕宽度自动调整列数 */
                    gap: 4px; /* 减少表情间的间距 */
                }
                .emoji {
                    font-size: 1.5em;
                    cursor: pointer;
                    padding: 4px; /* 减少表情内边距 */
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }
                .emoji:hover {
                    background-color: #f0f0f0;
                }
            </style>
            <?php
            $emoji_output = ob_get_clean();
        }

        $notice = '<p><small class="markdown-comment-notice">' . esc_html__( 'You can use the Markdown in the comment form.',
                'markdown-comment' ) . '</small></p>';
        return $field . $emoji_output . $notice;
    }

    add_filter( 'comment_form_field_comment', 'markdown_comment_text' );

    // 处理评论中的Emoji
    function handle_emoji_in_comments( $comment_text ) {
        $emoji_enabled = get_option('dcp_emoji_comments_enabled', 'yes');
        if ($emoji_enabled === 'yes') {
            // 将Unicode编码的Emoji转换为HTML实体
//            $comment_text = mb_convert_encoding( $comment_text, 'HTML-ENTITIES', 'UTF-8' );
        }
        return $comment_text;
    }
    add_filter( 'pre_comment_content', 'handle_emoji_in_comments' );
    add_filter( 'comment_text', 'handle_emoji_in_comments' );

}
?>