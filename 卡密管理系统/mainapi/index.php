<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>卡密管理系统</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
    <h1>卡密管理系统</h1>
    <div class="button-container">
        <button id="addButton" onclick="showAddForm()">添加卡密</button>
        <button id="queryButton" onclick="showQueryForm()">查询卡密</button>
        <button id="deleteButton" onclick="showDeleteForm()">删除卡密</button>
        <button id="downloadButton" style="display: none;" onclick="downloadFile()">下载卡密</button>
    </div>
    <div id="formContainer" style="display: none;">
        <!-- 动态表单将在这里生成 -->
    </div>
    <table id="codesTable">
        <thead>
            <tr>
                <th>操作</th>
                <th>卡密</th>
                <th>时长（天）</th>
                <th>激活状态</th>
                <th>激活时间</th>
                <th>到期时间</th>
            </tr>
        </thead>
        <tbody>
            <!-- 卡密数据将通过JavaScript动态加载 -->
        </tbody>
    </table>
</body>
</html>
