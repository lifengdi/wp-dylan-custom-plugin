<?php

// 定义短码处理函数
function countdown_shortcode( $atts ) {
    $default_atts = array(
        'type' => 'all', // 默认全部展示
        'title' => '' // 默认没有标题，虽然保留但不使用
    );
    $atts = shortcode_atts( $default_atts, $atts );

    $types = explode(',', str_replace(' ', '', $atts['type']));
    if (in_array('all', $types)) {
        $types = ['day', 'week','month', 'year'];
    }

    $valid_types = ['day', 'week','month', 'year'];
    $color_map = [
        'day' => '#B5EAD7',  // 明亮的薄荷绿
        'week' => '#FFDAC1', // 柔和的蜜桃粉
        'month' => '#E2F0CB', // 清新的淡黄绿
        'year' => '#FFB7B2'  // 温暖的珊瑚粉
    ];

    $style = '<style>
       .countdown-container {
            background-color: #FAF9F6;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }
       .countdown-item {
            height: 50px;
            position: relative;
            max-height: 80px;
            overflow: hidden;
        }
       .countdown-text {
            color: #868e96;
            font-size: 0.95em;
        }
       .countdown-progress-bar {
            height: 16px;
            background-color: #E8E8E8;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            margin-top: 3px;
        }
       .progress-bar-fill {
            height: 100%;
            border-radius: 8px;
            transition: width 1s ease;
            position: relative;
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(255, 255, 255, 0.2),
                rgba(255, 255, 255, 0.2) 10px,
                transparent 10px,
                transparent 20px
            );
            background-size: 28px 28px;
            animation: spiral-move 1s linear infinite;
        }
       .countdown-percentage {
            font-size: 0.8em;
            color: #fff;
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            z-index: 2;
        }

        @keyframes spiral-move {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 28px 0;
            }
        }
    </style>';

    $script = '<script>
        function updateCountdown() {
            const now = new Date();
            const types = '. json_encode($types). ';
            const colorMap = '. json_encode($color_map). ';
            const validTypes = '. json_encode($valid_types). ';

            types.forEach(type => {
                if (!validTypes.includes(type)) return;

                let elapsed = 0;
                let total = 0;
                let text = "";

                switch (type) {
                    case "day":
                        elapsed = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();
                        total = 24 * 3600;
                        text = `今天已过去 ${Math.floor(elapsed / 3600)} 小时 ${Math.floor((elapsed % 3600) / 60)} 分钟`;
                        break;
                    case "week":
                        elapsed = (now.getDay() * 24 * 3600) + (now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds();
                        total = 7 * 24 * 3600;
                        text = `本周已过去 ${Math.floor(elapsed / (24 * 3600))} 天 ${Math.floor((elapsed % (24 * 3600)) / 3600)} 小时 ${Math.floor((elapsed % 3600) / 60)} 分钟`;
                        break;
                    case "month":
                        const year = now.getFullYear();
                        const month = now.getMonth() + 1;
                        const daysInMonth = new Date(year, month, 0).getDate();
                        elapsed = ((now.getDate() - 1) * 24 * 3600) + (now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds();
                        total = daysInMonth * 24 * 3600;
                        text = `本月已过去 ${Math.floor(elapsed / (24 * 3600))} 天 ${Math.floor((elapsed % (24 * 3600)) / 3600)} 小时 ${Math.floor((elapsed % 3600) / 60)} 分钟`;
                        break;
                    case "year":
                        const startOfYear = new Date(now.getFullYear(), 0, 1);
                        const diff = now - startOfYear;
                        elapsed = Math.floor(diff / 1000);
                        const isLeapYear = (new Date(now.getFullYear(), 1, 29).getDate() === 29);
                        total = (isLeapYear? 366 : 365) * 24 * 3600;
                        text = `今年已过去 ${Math.floor(elapsed / (24 * 3600))} 天 ${Math.floor((elapsed % (24 * 3600)) / 3600)} 小时 ${Math.floor((elapsed % 3600) / 60)} 分钟`;
                        break;
                }

                const percentage = Math.round((elapsed / total) * 100);
                const item = document.getElementById(`countdown-${type}`);
                if (item) {
                    const textElement = item.querySelector(".countdown-text");
                    const percentageElement = item.querySelector(".countdown-percentage");
                    const progressBarElement = item.querySelector(".progress-bar-fill");

                    textElement.textContent = text;
                    percentageElement.textContent = `${percentage}%`;
                    progressBarElement.style.width = `${percentage}%`;
                    progressBarElement.style.backgroundColor = colorMap[type];
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateCountdown();
            setInterval(updateCountdown, 1000 * 60);
        });
    </script>';

    ob_start();
    echo $style;
    echo '<div class="countdown-container">';
    foreach ($types as $type) {
        if (!in_array($type, $valid_types)) continue;
        ?>
        <div class="countdown-item" id="countdown-<?php echo $type;?>">
            <span class="countdown-text"></span>
            <div class="countdown-progress-bar">
                <div class="progress-bar-fill">
                    <span class="countdown-percentage"></span>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';
    echo $script;
    return ob_get_clean();
}

add_shortcode('countdown', 'countdown_shortcode');