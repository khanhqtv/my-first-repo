<?php
session_start();
include('database.php');
include('validation.php');

// Tạo CSRF token nếu chưa có - token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// kiem tra login- session
if (!isset($_SESSION['user_id']))
{
    echo "ban chua dang nhap";
    header('location: login.php');
    exit();
}

// check bai viet co ton tai

    if (!isset($_GET['ID'])){
        echo "bài viết không tồn tại";
        //header('location: list.php');
        exit();
    }

    $article_id= $_GET['ID'];
    $author_id = $_SESSION['user_id'];

    $check=$conn->prepare('select ID from article where ID= ?');
    $check->bind_param('i',$article_id);
    $check->execute();
    $result=$check->get_result();
    $row=$result->fetch_assoc();
    if($row === null)
    {
        echo " bai viet khong ton tai";
        header('location: list.php');
    }
    $check ->close();
    
    
/// check xem co la tac gia 
    $stmt=$conn->prepare('select * from article where ID= ? and AUTHORID= ?');
    $stmt->bind_param('ii',$article_id,$author_id);
    $stmt->execute();
    $result=$stmt->get_result();
    $article=$result->fetch_assoc();
    if(!$article)
    {
        echo "<script>alert('ban khong phai la tac giac cua bai viet');
              window.location.href='list.php';
              </script>";
        exit();
    }

    //kiem tra an toan 
   

  
?>
<!DOCTYPE html>
<html lang="vn">
<head>
</head>
<body>
    <h1>Cập nhật bài viết</h1>
    <form method="POST">
         <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="Up_id" value="<?php echo ($article['ID']); ?>">
        
        <label>articlename</label>
        <input type="text" name="Up_articlename" value="<?php echo remove_xss($article['ARTICLENAME']); ?>">

        <label>articlesum</label>
        <input type="text" name="Up_articlesum" value="<?php echo remove_xss($article['ARTICLESUM']); ?>">

        <label>content</label>
        <input type="text" name="Up_content" value="<?php echo remove_xss($article['CONTENT']); ?>">

        <label>note</label>
        <input type="text" name="Up_note" value="<?php echo remove_xss($article['NOTE']); ?>">

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
        // Kiểm tra CSRF token
    if ( $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo '<script>alert("Token không hợp lệ. Vui lòng thử lại.");</script>';
        exit();
    }
    
    $Id=$_POST['Up_id'];
    $Name=$_POST['Up_articlename'] ;
    $Sum=$_POST['Up_articlesum'];
    $Content=$_POST['Up_content'];
    $Note=$_POST['Up_note'];
    $Day=date("Y/m/d");

    // kiểm tra xem có bị dính lỗi. ? thảo luận
    if (detectSQLii($Id) || detectSQLii($Name) || detectSQLii($Sum) || detectSQLii($Content) || detectSQLii($Note)) {
        echo '<script>alert("bài viết có khả năng bị lỗi sql. Vui lòng nhập lại");</script>';
        exit();
    }
    
    //thuc hien sql thi khong can
    $update = $conn->prepare("update article 
    set ARTICLENAME = ?, ARTICLESUM = ?, CONTENT = ?, NOTE = ?, DAYMODIFIED = ? 
    where ID = ?");
    $update->bind_param('sssssi',$Name,$Sum,$Content,$Note,$Day,$Id);
    if ($update->execute()){
        echo "<script>
                alert('Sucessfull!');
                window.location.href='updatearticle.php?ID=$Id';
              </script>";
        exit();
    }else
    {
        echo '<script>alert("bị lỗi, cần nhập lại")</script>';
    }
    $update->close();
}

?>
