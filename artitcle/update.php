<?php
session_start();
include('database.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $ID =$_POST['ID'] ;
    $ARTICLENAME = $_POST['ARTICLENAME'];
    $DAYMODIFIED = date("Y/m/d");
    $ARTICLESUM = $_POST['ARTICLESUM'];
    $CONTENT = $_POST['CONTENT'];
    $NOTE = $_POST['NOTE'];
    
    // Cập nhật bài viết
    $update = $conn->prepare('UPDATE ARTICLE SET ARTICLENAME = ?, ARTICLESUM = ?, CONTENT = ?, NOTE = ?, DAYMODIFIED = ? WHERE ID = ?');
    $update->bind_param('sssssi', $ARTICLENAME, $ARTICLESUM, $CONTENT, $NOTE, $DAYMODIFIED, $ID);
   
    // Thông báo cập nhật thành công và chuyển hướng
    if( $update->execute())
    {
        echo "<script>
                    alert('Cập nhật thành công');
                    window.location.href = 'list.php';
            </script>";
        exit();
    }else{
        echo '<script>alert("bị lỗi, cần nhập lại")</script>';
    }
   
}
?>