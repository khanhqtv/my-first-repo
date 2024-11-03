<?php
session_start();
include('database.php');


// kiểm tra vai trò 
if ($_SESSION['ROLE'] !== 'Stocker') {
    echo '<script>alert("bạn không có quyền thực hiện chức năng");</script>';
    header("Location: login.php");
    exit();
}

// Lấy danh sách danh mục sản phẩm từ cơ sở dữ liệu
$stmt = $conn->prepare('SELECT * FROM categories ');
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm mới</title>
</head>
<body>
<h1>Thêm sản phẩm mới</h1>
<form method="POST" action="">
    <label for="product_name">Tên sản phẩm:</label><br>
    <input type="text" id="product_name" name="product_name"><br><br>
    
    <label for="product_price">Giá sản phẩm:</label><br>
    <input type="text" id="product_price" name="product_price"><br><br>
    
    <label for="product_description">Mô tả sản phẩm:</label><br>
    <textarea id="product_description" name="product_description"></textarea><br><br>
    
    <label for="category_id">Danh mục sản phẩm:</label><br>
    <select id="category_id" name="category_id">
        <option value="">Chọn danh mục</option>

        <?php while ($row = $result->fetch_assoc()): ?> 
            <option value="<?php echo $row['id']; ?>">
                <?php echo $row['category_name']; ?>
            </option>
        <?php endwhile; ?>

    </select><br>
    <input type="submit" value="Thêm sản phẩm">
</form>
<?php
// lấy dữ liệu từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {
    $Name = ($_POST['product_name']);
    $Price = ($_POST['product_price']);
    $Description = ($_POST['product_description']);
    $CategoryId = $_POST['category_id'];

    $insert = $conn->prepare('INSERT INTO products (name, price, description, category_id) VALUES (?, ?, ?, ?)');
    $insert->bind_param('sdsi', $Name, $Price, $Description, $CategoryId);
    if ($insert->execute())
    {
        echo '<script>
                  alert("Cập nhật thành công");
                  window.location.href = "new.php";
              </script>';
        exit();
    }else{
        echo '<script>alert("bị lỗi, cần nhập lại")</script>';;
    }
   
} 


?>
</body>
</html>
