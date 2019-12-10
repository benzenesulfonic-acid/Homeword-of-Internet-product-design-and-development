<?php
require_once('../mysqli_connect.php');
require_once('../util.php');
require_once('../logger.php');
/**
 * 注册用户
 * @param user_name 用户名
 * @param password 密码
 */
/**
 * 错误信息
 * error code - description
 * 401  - 用户已存在
 */
if(isset($_POST['user_name']) && isset($_POST['password'])){
    if($con=getConnent()){
        $response=new response(false,102);
        if(($result=stmt_query($con,"SELECT id FROM `user` WHERE user_name=? ",'s',$_POST['user_name']))!==false){
            if(count($result)>0){
                $response->set_response(false,401,'user name is already exist');
            }
            else{
                $password=password_hash($_POST["password"],PASSWORD_DEFAULT);
                if($result=stmt_query($con,"INSERT INTO user(user_name,password) VALUES(?,?)",'ss',$_POST['user_name'],$password)){
                    $response->set_response(true,'');
                }
                else{
                    $response->set_response(false,102);
                }
            }
        }
        $con->close();
        echo $response->to_json();
        return;
    }
    echo (new response(false, 102))->to_json();
    return;
}
echo (new response(false,101))->to_json();