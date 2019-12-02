<?php
require_once('../mysqli_connect.php');
require_once('../util.php');
/**
 * 
 */
if(isset($_POST['type_id'])){
    if($con=getConnent()){
        if(stmt_query($con,"DELETE FROM film_type WHERE type_id=?",'i',$_POST['type_id'])){
            if(stmt_query($con,"DELETE FROM type WHERE id=?",'i',$_POST['type_id'])){
                $con->close();
                echo (new response(true,""))->to_json();
                return;
            }
        }
        $con->close();
    }
    echo (new response(false, 102))->to_json();
    return;
}
echo (new response(false,101))->to_json();