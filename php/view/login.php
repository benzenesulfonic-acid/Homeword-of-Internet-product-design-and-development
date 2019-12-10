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
 * 401  - 用户名或密码错误
 */
if(isset($_POST['user_name']) && isset($_POST['password'])){
    if($con=getConnent()){
        $response=new response(false,102);
        if(($result=stmt_query($con,"SELECT id FROM `user` WHERE user_name=? ",'s',$_POST['user_name']))!==false){
            if(count($result)!=1){
                $response->set_response(false,401,'wrong user name or password');
            }
            else{
                if($result=stmt_query($con,"SELECT password FROM `user` WHERE user_name=? ",'s',$_POST['user_name'])){
                    $password=$result[0]['password'];
                    if(password_verify($_POST['password'],$password)){
                        $response->set_response(true,'');
                    }
                    else{
                        $response->set_response(false,401,'wrong user name or password');
                    }
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