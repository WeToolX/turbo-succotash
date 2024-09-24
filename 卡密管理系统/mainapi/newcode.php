<?php
header('Content-Type: application/json');

// 启用错误报告（仅用于调试，生产环境下应禁用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 获取GET请求中的卡密生成数量和时长
$num = isset($_GET['num']) ? intval($_GET['num']) : 1;
$date = isset($_GET['date']) ? intval($_GET['date']) : 1440; // 默认为1天，即1440分钟

// 定义存储卡密的目录
$idsDir = "../ids/";
$dataFile = "data.txt";

// 生成随机字符串作为卡密
function generateCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// 检查卡密是否唯一
function isUnique($code, $idsDir) {
    return !file_exists($idsDir . $code . '.json');
}

// 创建卡密文件
function createCodeFile($code, $date, $idsDir) {
    $data = [
        'code' => $code,
        'time' => $date,
        'activation' => false,
        'starttime' => 0,
        'entime' => 0
    ];
    file_put_contents($idsDir . $code . '.json', json_encode($data, JSON_UNESCAPED_UNICODE));
}

// 存储所有生成的卡密到data.txt
$allCodes = [];
$createdCodes = 0;
while ($createdCodes < $num) {
    $newCode = generateCode();
    if (isUnique($newCode, $idsDir)) {
        createCodeFile($newCode, $date, $idsDir);
        $allCodes[] = $newCode;
        $createdCodes++;
    }
}

// 将生成的卡密写入到data.txt，覆盖旧内容
file_put_contents($dataFile, implode("\n", $allCodes));


echo json_encode(['code' => 1, 'ms' => '成功'], JSON_UNESCAPED_UNICODE);
?>
