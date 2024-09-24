<?php
header('Content-Type: application/json');

// 获取GET请求中的卡密
$code = isset($_GET['code']) ? $_GET['code'] : '';

// 卡密文件的路径
$filePath = "../ids/$code.json";

// 检查卡密文件是否存在
if (!file_exists($filePath)) {
    echo json_encode(['code' => -1, 'ms' => '卡密不存在'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 读取卡密文件内容
$data = json_decode(file_get_contents($filePath), true);

// 获取当前时间戳
$currentTime = time();

// 如果卡密未激活，则自动激活
if (!$data['activation']) {
    $data['activation'] = true;
    $data['starttime'] = $currentTime;
    $data['entime'] = $currentTime + ($data['time'] * 60); // 计算结束时间，time 是分钟数
    // 更新JSON文件
    file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE));
}

// 检查卡密是否到期
if ($currentTime > $data['entime']) {
    echo json_encode(['code' => -1, 'ms' => '卡密到期'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 计算剩余时长（天数）
$remainingDays = ($data['entime'] - $currentTime) / 86400; // 86400 是一天的秒数

// 返回卡密的剩余时长
echo json_encode(['code' => 1, 'ms' => "卡密剩余时长: " . floor($remainingDays) . " 天"], JSON_UNESCAPED_UNICODE);
?>
