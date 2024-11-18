<?php
session_start();
include('database.php');
include('validation.php');
//check session
if (!isset($_SESSION['userid']))
{
    header('location: login.php');
    exit();
}
// check token
if (!isset($_SESSION['csrf_token']))
{
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    
}

// truy van thong tin san pham
$product_id= $_POST['productID'];

$stmt=$conn->prepare('select * from products where id = ?');
$stmt->bind_param('i',$product_id);
$stmt->execute();
$result=$stmt->get_result();
$product=$result->fetch_assoc();



// truy van category de hien select
$stmt=$conn->prepare('select * from categories');
$stmt->execute();
$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title> Sua san pham</title>
</head>
<body>
    <h1>Sua San Pham</h1>
    
    <form method="POST" action="" >
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label> ten san pham</label><br>
        <input type="text" name="product_name" value="<?php echo remove_xss($product['name']); ?>"><br><br>

        <label>gia san pham</label><br>
        <input type="text" name="product_price" value="<?php echo remove_xss($product['price']); ?>"><br><br>

        <label>mo ta san phan</label><br>
        <textarea name="product_description" ><?php echo remove_xss($product['description']); ?></textarea><br><br>
        
        <label>danh muc san pham </label><br>
        <select name="category_id">
            <?php while ($category=$result->fetch_assoc()): ?>
             <option value="<?php echo $category['id']; ?>" <?php if($category['id']===$product['category_id']) {echo "selected";}  ?> >
                <?php echo remove_xss($category['category_name']) ?>
            </option>
            <?php endwhile ?>
            </select><br><br>

        <input type="submit" value="sua san pham">
            </form>
            </body>
            </html>
<?php
    // kiem tra form
    if($_SERVER['REQUEST_METHOD']==='POST')
    {
        //kiem tra token
        if($_SESSION['csrf_token']!==$_POST['csrf_token'])
        {
            echo "<script>alert('token khong hop le');</script>";
            exit();
        }

        $product_id=$_POST['product_id'];
        $name=$_POST['product_name'];
        $price=$_POST['product_price'];
        $description=$_POST['product_description'];
        $category_id=$_POST['category_id'];

        if( detect_sql($name)|| detect_sql($price)|| detect_sql($description))
        {
            echo "<script>alert('ban co kha nang bi loi sql. vui long nhap lai');</script>";
            exit();
        }
        // update
        $update=$conn->prepare('update products set name = ?,price =?, description =?, category_id=? where id=?  ');
        $update->bind_param('sdsii',$name,$price,$description,$category_id,$product_id);
        if($update->execute())
        {
            echo "<script>
                    alert('Successful!');
                    window.location.href='search.html';
                  </script>";
            exit();
        }

    }
?>
        