<?php 
session_start();
include('database.php');
    // kiem tra dang nhap
    if (!isset($_SESSION['userid']))
    {
        header('location:login.php');
        exit();
    }
    // kiem tra vai tro
    if ($_SESSION['role']!=='Admin')
    {
        header('location:login.php');
        exit();
    }
    if (isset($_GET['productID']))
    {
        $productID=$_GET['productID'];
        $stmt=$conn->prepare('delete from products where id=?');
        $stmt->bind_param('i',$productID);
        if ($stmt->execute())
        {
            echo "<script>
                    alert('Sản phẩm đã được xóa thành công');
                    window.location.href = document.referrer;
                </script>";
        }
        $stmt->close();
    }else{
        header('location:search.html');
    }
?>    
