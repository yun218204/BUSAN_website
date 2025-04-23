<?php
// fetch_image.php (儲存為 PHP 檔案)

header('Content-Type: application/json');

// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "addressbook";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    echo json_encode(['error' => '資料庫連線失敗']);
    exit;
}

// 查詢圖片網址
$sql = "SELECT img_url FROM images WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['img_url' => $row['img_url']]);
} else {
    echo json_encode(['img_url' => 'img/default.jpg']); // 預設圖片
}

$conn->close();
