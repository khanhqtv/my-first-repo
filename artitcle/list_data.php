<?php
session_start();
include('database.php');

if (!isset($_SESSION['USERID'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra nếu người dùng có quyền 'Author'
if ($_SESSION['ROLE'] === 'Author') {
    // Chuẩn bị truy vấn SQL để lấy bài viết dựa trên ID của tác giả
        $stmt = $conn->prepare('SELECT * FROM ARTICLE WHERE AUTHORID = ?');
        $stmt->bind_param('i', $_SESSION['USERID']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Duyệt qua từng dòng kết quả và hiển thị thông tin bài viết
        while ($row = $result->fetch_assoc()) {
            echo    "<tr>";
            echo        "<td>" . $row['ARTICLENAME'] . "</td>";
            echo        "<td>" . $row['ARTICLESUM'] . "</td>";
            echo        "<td>" . $row['CONTENT'] . "</td>";
            echo        "<td>" . $row['NOTE'] . "</td>";
            echo        "<td><a href='updatearticle.php?ID=" . $row['ID'] . "'>chinh sua</a></td>";
            echo    "</tr>";
        }
        // Đóng kết nối truy vấn
        $stmt->close();
    }
?>

