<?php
require_once("../util.php");
/**
 * 返回管理功能列表
 */
$func = [
    [
        "function_name" => "管理电影",
        "function_url" => "./manage_film.html"
    ],
    [
        "function_name" => "管理电影类型",
        "function_url" => "./manage_type.html"
    ]
];
//echo json_encode($func);
echo (new response(true, $func))->to_json();
