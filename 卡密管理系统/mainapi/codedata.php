<?php
header('Content-Type: application/json');

// 获取GET请求中的卡密代码
$code = isset($_GET['code']) ? $_GET['code'] : '';

// 定义卡密文件的路径
$filePath = "../ids/$code.json";

// 检查卡密文件是否存在
if (!file_exists($filePath)) {
    echo json_encode(['code' => -1, 'ms' => '卡密不存在'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 读取卡密文件内容
$data = json_decode(file_get_contents($filePath), true);

// 添加查询成功的消息
$data['ms'] = '查询成功';

// 返回卡密信息
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
