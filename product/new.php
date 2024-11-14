<?php
session_start();
include('database.php');
include('validation.php');

// check session hien tai 
if (!isset($_SESSION['userid']))
{
    header('location: login.php');
    exit();
}

// check token  - neu chua co tao token
if (!isset($_SESSION['csrf_token']))
{
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
}

// kiem tra co phai la tac gia 
if($_SESSION['role'] !== 'Stocker')
{
    echo "<script>
            alert('ban khong the thuc hien chuc nang nay');
            window.location.href= 'login.php';
          </script>";
    exit();
}

// truy xuat du lieu categories
$stmt=$conn-> prepare('select * from categories ');
$stmt->execute();
$result= $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vn">
<head>
    <meta charset="UTF-8">
    <title> thêm sản phẩm mới </title>
</head>
<body>
    <h1> thêm sản phẩm mới</h1>
    <form method = "POST" action=""  >
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label> Tên sản phẩm </label> <br>
        <input type = "text" name="product_name"><br><br>

        <label> Gia san pham </label><br>
        <input type ="text" name= "product_price"> <br><br>

        <label> mo ta san pham </label><br>
        <textarea  name="product_description"></textarea><br><br>
        
        <label> danh muc san pham</label><br>
        <select name="category_id">
            <option value="">Chon danh muc </option>
            <?php while($category=$result->fetch_assoc()): ?>
                <option value="<?php echo remove_xss($category['id']) ?>">
                <?php echo remove_xss($category['category_name']); ?>
            </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <input type="submit" id="new" value ="them san pham">
            </form>
            </body>
</html>

<?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {   
        /// kiem tra token
        if ($_SESSION['csrf_token']!==$_POST['csrf_token'])
        {
            echo "<script>
            alert('Token CSRF không hợp lệ. Vui lòng thử lại!');
                 </script>";
            exit();
        }

        // them
        $name=$_POST['product_name'];
        $price=$_POST['product_price'];
        $description=$_POST['product_description'];
        $categoryid= $_POST['category_id'];

        // kiem tra sql 
        if (detect_sql($name) || detect_sql($price) || detect_sql($description) || detect_sql($categoryid))
        {
            echo "<script>
                    alert('co kha nang bi loi sql. vui long nhap lai');
                 </script>";
            exit();
        } 
        $insert=$conn->prepare('insert into products(name,price,description,category_id) values(?,?,?,?)');
        $insert->bind_param('sdsi',$name,$price,$description,$categoryid);
        if ($insert->execute())
        {
            echo "<script>
                  alert('Successful!');
                  window.location.href='list.php';
                  </script>";
            exit();
        }

    }
?>
    


        