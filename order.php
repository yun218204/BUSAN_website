<?php
session_start();
header('Content-Type: application/json');

// 顯示 PHP 錯誤訊息 
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// 確認使用者已登入 變數存入USER
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'not_logged_in']);
    exit();
}

// 確認接收到產品名稱與價格
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product']) && isset($_POST['price'])) {
    $product = trim($_POST['product']);
    $price = floatval($_POST['price']);  // 將價格轉為數字格式

    // 資料庫連線設定
    $host = 'localhost';
    $dbname = 'member_info';  // 你的資料庫名稱
    $user = 'root';           // 資料庫使用者名稱
    $pass = '';               // 資料庫密碼，沒有的話保持空白
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    try {
        // 建立資料庫連線
        $db = new PDO($dsn, $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 取得登入使用者的帳號
        $username = $_SESSION['user']['username'];

        // 新增訂單資料到 ordersinfo 表格
        $sql = "INSERT INTO ordersinfo (member_uid, order_total, order_date) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username, $price]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => '資料庫錯誤: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '無效的請求']);
}
