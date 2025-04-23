<?php
session_start();
session_unset();  // 清除所有 session 資料
session_destroy(); // 銷毀 session
echo json_encode(['status' => 'logged_out']);
?>
