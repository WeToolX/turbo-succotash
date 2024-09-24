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

// 删除卡密文件
if (unlink($filePath)) {
    echo json_encode(['code' => 1, 'ms' => '删除成功'], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['code' => -1, 'ms' => '删除失败'], JSON_UNESCAPED_UNICODE);
}
?>
