<?php
session_start();
include('database.php');

    $stmt = $conn->prepare('SELECT * FROM article   WHERE id = ? AND authorid = ?');
    $stmt->bind_param('ii', $_GET['ID'], $_SESSION['USERID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if( $row === null ) {
        echo "<script>
                alert('ban khong la tac gia');
                window.location.href= 'list.php';
             </script>";
        exit();
    }
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Cập nhật nội dung bài viết</title>
    </head>
    <body>
        <h1>Cập nhật nội dung bài viết </h1>
        <form action="update.php" method="POST">
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
    




