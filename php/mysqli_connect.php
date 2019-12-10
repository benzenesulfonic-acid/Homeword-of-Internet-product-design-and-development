<?php
require_once('config.php');
require_once('logger.php');

/**
 * 获取mysqli连接对象
 * @return mysqli对象
 * @return false 出现问题时
 */
function getConnent()
{
    $config = get_config();
    $host = $config['db']['host'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $dbname = $config['db']['dbname'];
    $port = $config['db']['port'];
    if (!($con = new mysqli($host, $user, $pass, $dbname, $port)) /*|| !$con->set_charset($config['db']['charset'])*/) {
        Logger::error(__FILE__ . ':' . __LINE__ . ' ' . json_encode($con->error_list));
    }
    return $con;
}

/**
 * 执行含参数查询
 * @param $con 使用的mysqli对象
 * @param $sql 执行的sql语句
 * @param $types 参数类型字符串
 * @param ..$vars 参数
 * @return 结果的对象数组 
 * @return true 执行成功的非查询语句
 * @return false 出现错误
 */
function stmt_query($con, $sql, $types, ...$vars)
{
    Logger::debug("\n".$sql."\n".$types."\n".json_encode($vars));
    if ($stmt = $con->prepare($sql)) {
        if (count($vars)==0 || $stmt->bind_param($types, ...$vars)) {
            if ($stmt->execute()) {
                //如果出现错误
                if ($stmt->error != "") {
                    //do some log
                    Logger::warning(__FILE__ . ':' . __LINE__ . ' ' . json_encode($stmt->error_list));
                    $stmt->close();
                    return false;
                }
                //如果是select查询
                else if ($result = $stmt->get_result()) {
                    $stmt->close();
                    return $result->fetch_all(MYSQLI_ASSOC);
                }
                //非查询语句
                else {
                    $stmt->close();
                    return true;
                }
            }
        }
        Logger::error(__FILE__ . ':' . __LINE__ . ' ' . json_encode($stmt->error_list));
        $stmt->close();
        return;
    }
    Logger::error(__FILE__ . ':' . __LINE__ . ' ' . json_encode($con->error_list));
    return false;
}
