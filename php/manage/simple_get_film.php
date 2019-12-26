<?php
require_once('../config.php');
require_once('../mysqli_connect.php');
require_once('../util.php');
require_once('../logger.php');

session_start();

if(isset($_SESSION['user_id']) || isset($_REQUEST['manage']) ){
    if($con=getConnent()){
        if($result=$con->query("SELECT id,name,year FROM movie ORDER BY year DESC ")){
            echo (new response(true,$result->fetch_all(MYSQLI_ASSOC)))->to_json();
            return;
        }
    }
}
echo (new response(false, 103))->to_json();
