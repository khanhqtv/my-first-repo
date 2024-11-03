<!DOCTYPE html>
<html lang="vn">
<head>
    <title>Quan ly NOI DUNG</title>
</head>
<body>
    <h1>Danh sách Nội Dung</h1>
    <table>
        <tr>
            <th>ID_ARTICLE</th> 
            <th>ARTICLENAME</th>
            <th>ARTICLESUM</th>
            <th>CONTENT</th>
            <th>NOTE</th>
            <th>ACTIONS</th> 
        </tr>
    
<?php
// Bắt đầu một phiên làm việc mới hoặc tiếp tục phiên hiện tại
session_start();

// Nhúng tệp kết nối cơ sở dữ liệu
include('database.php');

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['USERID'])) {
    // Chuyển hướng người dùng đến trang đăng nhập nếu chưa đăng nhập
    header("Location: login.php");
    exit();
}

// Kiểm tra nếu người dùng có quyền 'Author'
if ($_SESSION['ROLE'] === 'Author') {
    // Lấy ID của người dùng
    $ID = ($_SESSION['USERID']);

    // Chuẩn bị truy vấn SQL để lấy bài viết dựa trên ID của tác giả
    $stmt = $conn->prepare('SELECT * FROM ARTICLE WHERE AUTHORID = ?');
    $stmt->bind_param('i', $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Duyệt qua từng dòng kết quả và hiển thị thông tin bài viết
    while ($row = $result->fetch_assoc()) {
        echo    "<tr>\n";
        echo        "<td>" . htmlspecialchars($row['ID']) . "</td>\n";
        echo        "<td>" . htmlspecialchars($row['ARTICLENAME']) . "</td>\n";
        echo        "<td>" . htmlspecialchars($row['ARTICLESUM']) . "</td>\n";
        echo        "<td>" . htmlspecialchars($row['CONTENT']) . "</td>\n";
        echo        "<td>" . htmlspecialchars($row['NOTE']) . "</td>\n";
        echo        "<td><a href='updatearticle.php?ID=" . htmlspecialchars($row['ID']) . "'>Update</a></td>\n";
        echo    "</tr>\n";
    }
    // Đóng kết nối truy vấn
    $stmt->close();
}
?>
    </table>
</body>
</html>
