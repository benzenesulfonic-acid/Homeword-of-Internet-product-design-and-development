<?php
function fun ($a,...$vars){
    echo('vars '.json_encode($vars)."\n");
    echo('vars '.json_encode(...$vars)."\n");
}
$arr=array(5,6);
fun(1,$arr);
fun(1,...$arr);
echo json_encode(5,6);