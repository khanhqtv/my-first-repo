<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sp";
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
}catch (Exception $e) {
    echo " lỗi kết nối cơ sở dữ liệu";
}
?>