<?php
require_once('../config.php');
require_once('../mysqli_connect.php');
require_once('../util.php');
require_once('../logger.php');

session_start();

if(isset($_REQUEST['id']) ){
    if($con=getConnent()){
        if($resilt=stmt_query($con,"DELETE FROM movie WHERE id=?","i",$_REQUEST['id'])){
            echo (new response(true,""))->to_json();
            return;
        }
    }
}
echo (new response(false, 103))->to_json();
