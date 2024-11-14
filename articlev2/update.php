<?php
session_start();
include('database.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $Id=$_POST['Up_id'];
    $Name=$_POST['Up_articlename'] ;
    $Sum=$_POST['Up_articlesum'];
    $Content=$_POST['Up_content'];
    $Note=$_POST['Up_note'];
    $Day=date("Y/m/d");
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
    }
    $update->close();
}


?>