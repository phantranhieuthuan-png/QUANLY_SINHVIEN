<?php
require_once __DIR__.'/../includes/init.php';

//kiểm tra xem có phải admin không 
if(!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit();
}

//xóa sạch session admin
unset($_SESSION['admin']);

//nếu không có session nào khác cần giữ lại,có thể xóa toàn bộ session
session_destroy();

//clean cookies session
if(isset($_COOKIE[session_name()])){
    setcookie(session_name(),'',time()-3600,'/');
}
//chuyển hướng về trang login
header('Location:login.php');
exit();