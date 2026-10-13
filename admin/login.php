<?php
require_once __DIR__.'/../includes/init.php';
//if alredy logged in, reditrect to admin dashboard
if(isset($_SESSION['admin'])){
    header('Location:index.php');
    exit();
}
    $old_username='';
    //Handle POST login
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username=trim($_POST['username']??'');
        $password=$_POST['password']??'';
        $old_username=htmlspecialchars($username,ENT_QUOTES);
        if($username!=''&& $password!=''){
            $user=DB::selectOne('SELECT * FROM quan_tri_vien WHERE username=:u',['u'=>$username]);
            if($user){
                $stored=$user['password']??'';
                $ok=false;

                if($stored!=''&&password_verify($password,$stored)){
                    $ok=true;
                    //Kiểm tra và nâng cấp hash nếu cần
                    if(password_needs_rehash($stored,PASSWORD_DEFAULT)){
                        $newHash=password_hash($password,PASSWORD_DEFAULT);
                        DB::update('quan_tri_vien',['password' => $newHash],'username=:u',['u' => $username]);
                    }
                }
                if($ok){
                    $_SESSION['user_role']='admin';
                    $_SESSION['admin']=$username;
                    $_SESSION['full_name']=$user['hoten'];
                    unset($_SESSION['err_admin']);
                    //update last login time
            DB::execute('UPDATE quan_tri_vien SET last_login = NOW() WHERE username= :u', ['u' => $username]);
            header('Location:'.INDEX_URL.'admin');
            exit();
                }else{
                    $_SESSION['err_admin']='Tên đăng nhập hoặc mật khẩu không đúng!';

                }
            }else{
                $_SESSION['err_admin']='Tên đăng nhập hoặc mật khẩu không đúng!';
            }
            }else{
                 $_SESSION['err_admin']='Vui lòng nhập tên đăng nhập hoặc mật khẩu.';
            }
    }
    ?>
            