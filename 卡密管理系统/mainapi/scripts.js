document.addEventListener('DOMContentLoaded', function() {
    loadCodes();
});

function loadCodes() {
    fetch('codedatas.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('codesTable').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';
            data.data.forEach(item => {
                const row = tableBody.insertRow();
                row.innerHTML = `
                    <td><button class="delete-btn" onclick="deleteCode('${item.code}')">删除</button></td>
                    <td>${item.code}</td>
                    <td>${Math.floor(item.time / 1440)} 天</td>
                    <td>${item.activation ? '已激活' : '未激活'}</td>
                    <td>${new Date(item.starttime * 1000).toLocaleDateString()}</td>
                    <td>${new Date(item.entime * 1000).toLocaleDateString()}</td>
                `;
            });
        });
}

function showAddForm() {
    const formHtml = `
        <label>生成数量:</label>
        <input type="number" id="num" value="1" min="1" />
        <label>卡密时长:</label>
        <select id="date">
            <option value="1440">天卡</option>
            <option value="10080">周卡</option>
            <option value="43200">月卡</option>
            <option value="525600">年卡</option>
        </select>
        <button class="btn-generate" onclick="addCode()">提交</button>
    `;
    document.getElementById('formContainer').innerHTML = formHtml;
    document.getElementById('formContainer').style.display = 'flex';
}

function showQueryForm() {
    const formHtml = `
        <label>输入卡密以查询:</label>
        <input type="text" id="queryCodeInput" />
        <button class="btn-query" onclick="queryCode()">查询</button>
    `;
    document.getElementById('formContainer').innerHTML = formHtml;
    document.getElementById('formContainer').style.display = 'flex';
}

function queryCode() {
    const code = document.getElementById('queryCodeInput').value;
    fetch(`codedata.php?code=${code}`)
        .then(response => response.json())
        .then(data => {
            if(data.code && data.code === -1) {
                alert(data.ms); // 如果查询不到卡密，显示错误信息
            } else {
                const details = `卡密: ${data.code}\n时长: ${Math.floor(data.time / 1440)} 天\n激活状态: ${data.activation ? '已激活' : '未激活'}\n激活时间: ${new Date(data.starttime * 1000).toLocaleDateString()}\n到期时间: ${new Date(data.entime * 1000).toLocaleDateString()}`;
                alert(details); // 显示卡密详细信息
            }
        });
}

function showDeleteForm() {
    const formHtml = `
        <label>输入卡密以删除:</label>
        <input type="text" id="deleteCodeInput" />
        <button class="btn-delete" onclick="deleteSpecificCode()">删除</button>
    `;
    document.getElementById('formContainer').innerHTML = formHtml;
    document.getElementById('formContainer').style.display = 'flex';
}

function deleteSpecificCode() {
    const code = document.getElementById('deleteCodeInput').value;
    deleteCode(code); // 使用已定义的 deleteCode 函数
}

function deleteCode(code) {
    fetch(`delcode.php?code=${code}`)
        .then(response => response.json())
        .then(data => {
            alert(data.ms);
            loadCodes(); // 刷新页面以更新列表
        });
}

function addCode() {
    const num = document.getElementById('num').value;
    const date = document.getElementById('date').value;
    fetch(`newcode.php?num=${num}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            alert(data.ms);
            loadCodes(); // 重新加载卡密列表
            showDownloadButton(); // 显示下载按钮
        });
}

function showDownloadButton() {
    const downloadButton = document.getElementById('downloadButton');
    downloadButton.style.display = 'block';
}

function downloadFile() {
    const downloadButton = document.getElementById('downloadButton');
    const link = document.createElement('a');
    link.href = 'data.txt';
    link.download = 'data.txt';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    downloadButton.style.display = 'none';
}
