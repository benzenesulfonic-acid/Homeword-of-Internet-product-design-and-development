<?php
require_once('../config.php');
require_once('../mysqli_connect.php');
require_once('../util.php');
require_once('../logger.php');

session_start();

if(isset($_REQUEST['id']) && isset($_REQUEST['name']) && isset($_REQUEST['year'])){
    if($con=getConnent()){
        if($_REQUEST['id']=='' || $count=stmt_query($con,"SELECT id as count FROM movie WHERE id=?",'i',$_REQUEST['id'])){
            if($_REQUEST['id']==''){
                //新加
                if(stmt_query($con,"INSERT INTO movie(name,year) VALUES (?,?)",'si',$_REQUEST['name'],$_REQUEST['year'])){
                    echo (new response(true, ""))->to_json();
                    return;
                }
            }
            else if(count($count)>0){
                //修改
                if(stmt_query($con,"UPDATE movie SET name=?,year=? WHERE id=?",'sii',$_REQUEST['name'],$_REQUEST['year'],$_REQUEST['id'])){
                    echo (new response(true, ""))->to_json();
                    return;
                }
            }
            else{
                //新加
                if(stmt_query($con,"INSERT INTO movie(name,year) VALUES (?,?)",'si',$_REQUEST['name'],$_REQUEST['year'])){
                    echo (new response(true, ""))->to_json();
                    return;
                }
            }
        }
    }
}
echo (new response(false, 103))->to_json();
