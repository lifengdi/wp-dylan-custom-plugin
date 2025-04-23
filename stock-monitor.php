<?php

// 使用腾讯财经 API 获取股票信息
function get_stock_info($stock_code) {
    $api_url = 'http://qt.gtimg.cn/q='. ($stock_code[0] === '6'? "sh{$stock_code}" : "sz{$stock_code}");
    $response = wp_remote_get($api_url);
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $body = wp_remote_retrieve_body($response);
        $data = explode('~', $body);
        if (count($data) > 1) {
            // 确保股票名称进行正确的编码转换
            $name = mb_convert_encoding($data[1], 'UTF-8', 'GBK');
            $price = floatval($data[3]);
            $yesterday_close = floatval($data[4]);
            $change_percentage = round(($price - $yesterday_close) / $yesterday_close * 100, 2);
            return array(
                'name' => $name,
                'price' => $price,
                'change_percentage' => $change_percentage
            );
        }
    }
    return null;
}

// AJAX 处理函数
function stock_monitor_ajax_query() {
    $stock_code = sanitize_text_field($_POST['stock_code']);
    $stock_info = get_stock_info($stock_code);
    if ($stock_info) {
        wp_send_json_success($stock_info);
    } else {
        wp_send_json_error('未查询到该股票信息，请检查股票代码是否正确。');
    }
}
add_action('wp_ajax_stock_monitor_query','stock_monitor_ajax_query');

function stock_monitor_admin_page() {
    $stock_codes = get_option('stock_monitor_selected_codes', array());
    $message = '';

    if (isset($_POST['add_stock_to_list'])) {
        $add_stock_code = sanitize_text_field($_POST['add_stock_code']);
        if (!in_array($add_stock_code, $stock_codes)) {
            $stock_codes[] = $add_stock_code;
            update_option('stock_monitor_selected_codes', $stock_codes);
            $message = '成功添加股票到监控列表！';
        } else {
            $message = '该股票已在监控列表中，无需重复添加。';
        }
    }

    if (isset($_POST['delete_stock'])) {
        $stock_to_delete = sanitize_text_field($_POST['delete_stock']);
        $stock_codes = array_diff($stock_codes, array($stock_to_delete));
        update_option('stock_monitor_selected_codes', $stock_codes);
        $message = '成功从监控列表中删除股票！';
    }

    ?>
    <div class="wrap">
        <h1>A股股票监控</h1>
        <?php if (!empty($message)):?>
            <div class="notice notice-<?php echo strpos($message, '成功')!== false? 'success' : 'error';?>"><?php echo $message;?></div>
        <?php endif;?>
        <h2>查询股票信息</h2>
        <form id="query-stock-form">
            <label for="stock_code">输入要查询的股票短码:</label>
            <input type="text" id="stock_code" name="stock_code" style="width: 200px; margin-bottom: 10px;">
            <input type="submit" value="查询" class="button button-primary">
        </form>
        <div id="query-result"></div>

        <h2>已监控的股票列表</h2>
        <table>
            <thead>
            <tr>
                <th>股票名称</th>
                <th>当前价格</th>
                <th>涨跌幅</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($stock_codes as $stock_code):?>
                <?php
                $stock_info = get_stock_info($stock_code);
                if ($stock_info):
                    ?>
                    <tr>
                        <td><?php echo esc_html($stock_info['name']);?></td>
                        <td><?php echo esc_html($stock_info['price']);?></td>
                        <td style="color: <?php echo $stock_info['change_percentage'] >= 0? 'red' : 'green';?>;"><?php echo esc_html($stock_info['change_percentage']). '%';?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="delete_stock" value="<?php echo esc_attr($stock_code);?>">
                                <input type="submit" value="删除" class="button button-secondary">
                            </form>
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
            </tbody>
        </table>

        <h2>短码使用说明</h2>
        <p>你可以在文章或页面中使用 <code>[stock_monitor]</code> 短码来显示后台添加的已监控股票列表。</p>
        <p>如果你需要自定义标题，可以使用 <code>[stock_monitor title="自定义标题"]</code> 短码，将 “自定义标题” 替换为你想要显示的标题。</p>
        <p>如果你想指定显示的股票代码，可以使用 <code>[stock_monitor codes="600001,000001"]</code> 短码，将 “600001,000001” 替换为你要显示的股票代码，多个代码用逗号分隔。</p>
        <p>你也可以同时使用标题和指定股票代码，例如 <code>[stock_monitor title="我的股票列表" codes="600001,000001"]</code>。</p>
    </div>
    <script>
        document.getElementById('query-stock-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const stockCode = document.getElementById('stock_code').value;
            const data = {
                action: 'stock_monitor_query',
                stock_code: stockCode
            };

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(data)
            })
                .then(response => response.json())
                .then(result => {
                    const resultDiv = document.getElementById('query-result');
                    if (result.success) {
                        const stockInfo = result.data;
                        resultDiv.innerHTML = `
                            <h3>查询到的股票信息</h3>
                            <p>股票名称: ${stockInfo.name}</p>
                            <p>当前价格: ${stockInfo.price}</p>
                            <p style="color: ${stockInfo.change_percentage >= 0? 'red' : 'green'};">涨跌幅: ${stockInfo.change_percentage}%</p>
                            <form method="post" action="">
                                <input type="hidden" name="add_stock_code" value="${stockCode}">
                                <input type="submit" name="add_stock_to_list" value="添加到监控列表" class="button button-secondary">
                            </form>
                        `;
                    } else {
                        resultDiv.innerHTML = '<div class="notice notice-error">'+ result.data + '</div>';
                    }
                })
                .catch(error => {
                    const resultDiv = document.getElementById('query-result');
                    resultDiv.innerHTML = '<div class="notice notice-error">查询出错，请稍后再试。</div>';
                });
        });
    </script>
    <?php
}

// 前端短代码，展示选中的股票数据
function stock_monitor_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title' => '',
            'codes' => '',
        ),
        $atts,
        'stock_monitor'
    );

    if (!empty($atts['codes'])) {
        $stock_codes = explode(',', $atts['codes']);
        $stock_codes = array_map('trim', $stock_codes);
    } else {
        $stock_codes = get_option('stock_monitor_selected_codes', array());
    }

    if (empty($stock_codes)) {
        return '<p>尚未选择任何股票进行监控。</p>';
    }

    $html = '<div class="stock-monitor">';
    if (!empty($atts['title'])) {
        $html.= '<h2>'. esc_html($atts['title']). '</h2>';
    }
    $html.= '<table><thead><tr><th>股票名称</th><th>当前价格</th><th>涨跌幅</th></tr></thead><tbody>';
    foreach ($stock_codes as $stock_code) {
        $stock_info = get_stock_info($stock_code);
        if ($stock_info) {
            $html.= '<tr>';
            $html.= '<td>'. esc_html($stock_info['name']). '</td>';
            $html.= '<td>'. esc_html($stock_info['price']). '</td>';
            $html.= '<td style="color: '. ($stock_info['change_percentage'] >= 0? 'red' : 'green'). ';">'. esc_html($stock_info['change_percentage']). '%</td>';
            $html.= '</tr>';
        }
    }
    $html.= '</tbody></table></div>';
    return $html;
}
add_shortcode('stock_monitor','stock_monitor_shortcode');