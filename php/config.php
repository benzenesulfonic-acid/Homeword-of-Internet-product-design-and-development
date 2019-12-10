<?php
function get_config()
{
    $config = array();
    /**
     * 数据库相关
     * host     - 数据库服务器主机名
     * user     - 用户名
     * pass     - 密码
     * dbname   - 数据库名
     * port     - 端口
     * charset  - 连接使用的编码方式
     */
    $config['db'] = array();
    $config['db']['host'] = "127.0.0.1";
    $config['db']['user'] = "root";
    $config['db']['pass'] = "root";
    $config['db']['dbname'] = "film";
    $config['db']['port'] = 3306;
    $config['db']['charset'] = "utf8mb4_general_ci";

    /**
     * 日志相关
     * basepath - 日志存储位置,相对于index.html所在目录(end with /)
     * level    - 日志记录级别 (1 - ERROR, 2 - WARNING, 3 - NOTICE, 4 - DEBUG)
     */
    $config['log']['basepath'] = "./log/";
    $config['log']['level'] = 3;

    /**
     * 查询电影相关
     * maxsize  - 每个查询最多返回的电影数量
     */
    $config['research']['maxsize'] = 100;

    return $config;
}
