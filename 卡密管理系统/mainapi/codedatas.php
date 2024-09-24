<?php
header('Content-Type: application/json');

// 定义卡密存储目录
$idsDir = "../ids/";

// 初始化数据数组
$data = [];

// 检查目录是否存在并读取目录下的所有文件
if (is_dir($idsDir)) {
    $files = scandir($idsDir);
    
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) == 'json') {
            $filePath = $idsDir . $file;
            $fileContent = json_decode(file_get_contents($filePath), true);
            $data[] = $fileContent;
        }
    }
    
    // 返回所有卡密信息
    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
} else {
    // 如果目录不存在，返回错误信息
    echo json_encode(['data' => [], 'ms' => '卡密目录不存在'], JSON_UNESCAPED_UNICODE);
}
?>
