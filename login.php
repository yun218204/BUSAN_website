<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        'status' => 'error',
        'message' => '資料庫連線失敗：' . $e->getMessage()
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo json_encode([
            'status' => 'error',
            'message' => '請輸入帳號與密碼'
        ]);
        exit();
    }

    $sql = "SELECT * FROM userinfo WHERE uid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] === $password) {
            // 設定 session
            $_SESSION['user'] = $user;
            $_SESSION['logged_in'] = true;
            $_SESSION['member_uid'] = $user['uid'];

            echo json_encode([
                'status' => 'success',
                'member_uid' => $user['uid'],
                'username' => $user['uid']  // 用帳號當名字
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => '密碼錯誤'
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '帳號不存在'
        ]);
        exit();
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => '不允許的請求方式'
    ]);
    exit();
}
?>
