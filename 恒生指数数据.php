<?php
// 获取恒生指数数据
function get_hsi_data($symbol = "HSI", $region = "HK") {
    $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?region={$region}&range=5d&interval=1d";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die('Error fetching HSI data.');
    }

    $data = json_decode($response, true);
    if (!isset($data['chart']['result'][0]['indicators']['quote'][0]['close'])) {
        die('Error decoding HSI data.');
    }

    return $data['chart']['result'][0]['indicators']['quote'][0]['close'];
}

// 计算移动平均线
function calculate_moving_average($data) {
    $sum = array_sum($data);
    return $sum / count($data);
}

// 判断恒生指数趋势
function estimate_trend($hsi_data) {
    $latest_price = end($hsi_data); // 最近一天的价格
    $moving_average = calculate_moving_average($hsi_data);

    if ($latest_price > $moving_average) {
        return "恒生指数可能在上升趋势";
    } else {
        return "恒生指数可能在下降趋势";
    }
}

// 主程序逻辑
$hsi_data = get_hsi_data();
echo "最近5天的恒生指数收盘价: " . implode(", ", $hsi_data) . "\n";
echo estimate_trend($hsi_data);
?>
