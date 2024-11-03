<?php
session_start();
include('database.php');

if (!isset($_SESSION['USERID'])) {
    header("Location: login.php");
    exit();
}

// Xử lý khi người dùng submit form cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $ID =($_POST['ID']) ;
    $ARTICLENAME = ($_POST['ARTICLENAME']);
    $DAYMODIFIED = date("Y/m/d");
    $ARTICLESUM = ($_POST['ARTICLESUM']);
    $CONTENT = ($_POST['CONTENT']);
    $NOTE = ($_POST['NOTE']);

    // Cập nhật bài viết
    $update = $conn->prepare('UPDATE ARTICLE SET ARTICLENAME = ?, ARTICLESUM = ?, CONTENT = ?, NOTE = ?, DAYMODIFIED = ? WHERE ID = ?');
    $update->bind_param('sssssi', $ARTICLENAME, $ARTICLESUM, $CONTENT, $NOTE, $DAYMODIFIED, $ID);
   
    
    // Thông báo cập nhật thành công và chuyển hướng
    if( $update->execute())
    {
        echo '<script>
                    alert("Cập nhật thành công");
                    window.location.href = "list.php";
            </script>';
        exit();
    }else{
        echo '<script>alert("bị lỗi, cần nhập lại")</script>';;
    }
   
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cập nhật nội dung bài viết</title>
</head>
<body>
    <h1>Cập nhật nội dung bài viết </h1>
    <form action="updatearticle.php" method="POST">
        <input type="text" name="ID" value="<?php echo ($_GET['ID']); ?>" hidden>
        <label >ARTICLENAME</label>
        <input type="text" name="ARTICLENAME" >
        <label >ARTICLESUM</label>
        <input type="text" name="ARTICLESUM" >
        <label >CONTENT</label>
        <input type="text" name="CONTENT" >
        <label >NOTE</label>
        <input type="text" name="NOTE" >
        <input type="submit" value="Submit">
    </form>
</body>
</html>
