
<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo json_encode([
        "status" => "logged_in",
        "username" => $_SESSION['member_uid']
    ]);
} else {
    echo json_encode(["status" => "not_logged_in"]);
}






