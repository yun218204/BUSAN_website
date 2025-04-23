<?php
// signup.php
date_default_timezone_set('Asia/Taipei');

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("請先登入再進行報名。");
}

$member_uid = $_SESSION['member_uid'];

// 從表單接收資料

$order_total = isset($_POST['order_total']) ? trim($_POST['order_total']) : '';

// 驗證資料完整性
if ( $order_total === '') {
    die("資料不完整，請檢查報名資訊。");
}

// 進一步檢查 order_total 是否為數字
if (!is_numeric($order_total)) {
    die("訂單金額格式錯誤，請輸入正確的數字。");
}

// 設定訂單日期
$order_date = date("Y-m-d H:i:s");

// 資料庫連線設定
$host = 'localhost';
$dbname = 'member_info';
$db_user = 'root';
$db_pass = '';  // 若有密碼請填入
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $db = new PDO($dsn, $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

// 插入訂單資料 (假設 ordersinfo 的欄位為 order_id, member_uid, order_date, order_total)
$sql = "INSERT INTO ordersinfo (member_uid, order_date, order_total) VALUES (?, ?, ? )";
$stmt = $db->prepare($sql);

if ($stmt->execute([$member_uid, $order_date, $order_total])) {
    echo "<script>alert('報名成功！');window.location.href='探索.html';</script>";
} else {
    echo "<script>alert('報名失敗，請稍後再試。');history.back();</script>";
}
?>
