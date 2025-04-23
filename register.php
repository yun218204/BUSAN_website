 <?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'member_info';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $db = new PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "資料庫連線失敗：" . $e->getMessage()
    ]);
    exit();
}

$username = $_POST['reg-username'] ?? '';
$password = $_POST['reg-password'] ?? '';
$phone = $_POST['reg-phone'] ?? '';
$email = $_POST['reg-email'] ?? '';

if (!$username || !$password || !$phone || !$email) {
    echo json_encode([
        "status" => "error",
        "message" => "請填寫所有欄位"
    ]);
    exit();
}

// 檢查帳號是否已存在
$stmt = $db->prepare("SELECT * FROM userinfo WHERE uid = ?");
$stmt->execute([$username]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "exists",
        "message" => "此帳號已存在"
    ]);
    exit();
}

// 寫入資料庫
$stmt = $db->prepare("INSERT INTO userinfo (uid, password, phone, email) VALUES (?, ?, ?, ?)");
$result = $stmt->execute([$username, $password, $phone, $email]);

if ($result) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "註冊失敗，請稍後再試"
    ]);
}
