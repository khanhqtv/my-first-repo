<?php
session_start();
include ('database.php');
include ('validation.php');

    // kiem tra phien lam viec
    if(!isset($_SESSION['userid']))
    {
        header('location:login.php');
        exit();
    }
    // kiem tra vai tro 
    if($_SESSION['role']!=='Admin')
    {
        echo "<script>alert('ban khong co quyen');
              window.location.href='login.php';
              </script>";
              exit();
    }
    $keyword=$_GET['keyword'];
    // truy xuat va hien ket qua 
    $stmt=$conn->prepare('select p.id,p.name,p.price,p.description,c.category_name from products 
                        p join categories c on p.category_id=c.id where p.name like ?');
    $key="%$keyword%";
    $stmt->bind_param('s',$key);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows===0)
    {
        echo "<script>alert('khong co san pham nao');
        window.location.href='search.html';
        </script>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="vn">
<head>
</head>
<body>
    <h1> danh sach san pham </h1>
    <table border="1">

    <tr>
        <th>id</th>
        <th>Ten San Pham</th>
        <th>gia</th>
        <th>mo ta</th>
        <th>loai san pham</th>
        <th></th>
        <th></th>
    </tr>

 
    <?php while($product=$result->fetch_assoc()): ?>
        <tr>
            <td><?php echo remove_xss($product['id']); ?> </td>
            <td><?php echo remove_xss($product['name']); ?></td>
            <td><?php echo remove_xss($product['price']); ?></td>
            <td><?php echo remove_xss($product['description']); ?> </td>
            <td><?php echo remove_xss($product['category_name']); ?></td>
            <td><a href="update.php?ID=<?php echo $product['id']; ?>">Cap Nhat</td>
            <td><a href="delete.php?ID=<?php echo $product['id']; ?>">Xoa</td>
        </tr>
        <?php endwhile; ?>
        
    </table>
    <a href="search.html">Quay lại</a>
</body>
</html>


        
