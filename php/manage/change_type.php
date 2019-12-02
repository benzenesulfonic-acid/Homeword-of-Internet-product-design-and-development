<?php
require_once("../mysqli_connect.php");
require_once("../util.php");
/**
 * 修改类型名
 * @param type_id 待修改类型ID
 * @param type_name 新类型名
 * @return 无
 */
if (isset($_POST['type_id'], $_POST['type_name'])) {
    $con = getConnent();
    if ($result = stmt_query($con, "UPDATE type SET type_name=? WHERE id=?", "si", $_POST['type_name'], $_POST['type_id'])) {
        echo (new response(true, ""))->to_json();
    } else {
        echo (new response(false, 102))->to_json();
    }
    $con->close();
    return;
}
echo (new response(false, 101))->to_json();
