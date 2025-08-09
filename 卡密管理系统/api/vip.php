<?php
header('Content-Type: application/json; charset=utf-8');

// 获取请求参数并进行类型转换
$code = $_GET['code'] ?? '';
$device = $_GET['device'] ?? '';
$time = (int)($_GET['time'] ?? '');
$sign = $_GET['sign'] ?? '';

// 验证签名
$expected_sign = md5($code . $device . $time . "qiexi666");
if ($sign !== $expected_sign) {
    echo json_encode(['code' => -1, 'time' => time(), 'sign' => md5(time() . "qiexi666")]);
    exit;
}

// // 检查时间戳是否过期（超过一分钟）
// if (abs(time() - $time) > 60) {
//     echo json_encode(['code' => -1, 'time' => time(), 'sign' => md5(time() . "qiexi666")]);
//     exit;
// }

$code_file = "../ids/$code.json";

// 检查卡密是否存在
if (!file_exists($code_file)) {
    echo json_encode(['code' => -100, 'time' => time(), 'sign' => md5(time() . "qiexi666")]);
    exit;
}

// 读取卡密文件
$code_data = json_decode(file_get_contents($code_file), true);

// 检查是否为有效的 JSON 格式
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['code' => -200, 'msg' => 'JSON格式错误', 'time' => time()]);
    exit;
}

// 获取当前时间戳
$currentTime = time();

// 检查卡密是否已激活
if (isset($code_data['device'])) {
    // 检查机器码和到期时间
    if ($code_data['device'] !== $device || $currentTime > $code_data['entime']) {
        $state = $currentTime > $code_data['entime'] ? -5 : -2;
        echo json_encode(['code' => $state, 'time' => $currentTime, 'sign' => md5($currentTime . "qiexi666")]);
        exit;
    }
    
} else {
    if (!$code_data['activation']) {
        $code_data['device'] = $device;
        $code_data['activation'] = true;
        $code_data['starttime'] = $currentTime;
        $code_data['entime'] = $currentTime + ($code_data['time'] * 60); // 计算结束时间，time 是分钟数
        // 更新JSON文件
        file_put_contents($code_file, json_encode($code_data, JSON_UNESCAPED_UNICODE));
    }
}

// 计算剩余时长（天数）
$remainingDays = ($code_data['entime'] - $currentTime) / 86400; // 86400 是一天的秒数
$t = time();
echo json_encode([
    'code' => 0,
    'codetime' => $code_data['entime'],
    'ms' => "卡密剩余时长: " . floor($remainingDays) . " 天",
    'time' => $t,
    'sign' => md5($t . "qiexi666")
], JSON_UNESCAPED_UNICODE);
?>
