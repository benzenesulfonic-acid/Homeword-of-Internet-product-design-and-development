<?php
require_once('../mysqli_connect.php');
require_once('../util.php');
/**
 * 返回类型列表
 */
if ($con = getConnent()) {
    $result = $con->query("SELECT id as type_id,type_name FROM type")->fetch_all(MYSQLI_ASSOC);
    $con->close();
    echo (new response(true, $result))->to_json();
    return;
}
echo (new response(false, 102))->to_json();