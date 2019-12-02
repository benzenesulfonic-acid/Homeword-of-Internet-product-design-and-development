<?php
require_once('../mysqli_connect.php');
require_once('../util.php');
/**
 * 添加新类型
 * @param type_name 新类型名
 * @return 无
 */
if(isset($_POST['type_name'])){
    if($con=getConnent()){
        if($result=stmt_query($con,"INSERT INTO type(type_name) VALUES(?)",'s',$_POST['type_name'])){
            echo (new response(true, ""))->to_json();
            $con->close();
            return;
        }
    }
    echo (new response(false, 102))->to_json();
    return;
}
echo (new response(false,101))->to_json();