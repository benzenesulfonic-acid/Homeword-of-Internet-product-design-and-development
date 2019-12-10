<?php
require_once('../config.php');
require_once('../mysqli_connect.php');
require_once('../util.php');
require_once('../logger.php');

/**
 * 获取电影信息
 * @param filters 电影的筛选条件 值应为JSON，形如[{'filter_type':'type','value':'value'}]
 * @param offset 查询偏移
 * @param limit 最大返回数，最大为 $config['research']['maxsize'] 的值
 */
/**
 * 错误信息
 * error code - description
 * 401  - 筛选器参数存在问题
 * 402  - 返回结果可能存在缺失项
 */
$config = get_config();
$response = new response(true);

//获取数据库连接
if ($con = getConnent()) {

    $sql = 'SELECT id,name,name_CN,language,release_time,movie_douban FROM film ';
    $types = '';
    $vars = array();

    //设置WHERE子句
    if (isset($_GET['filters'])) {
        $filters = json_decode($_GET['filters']);
        if (count($filters)  > 0) {
            try {
                $sql_filter = " WHERE true ";
                $types_filter = '';
                $vars_filter = array();

                foreach ($filters as $filter) {
                    if ($filter->filter_type == 'name') {
                        $sql_filter = $sql_filter . ' and (name LIKE ? or name_CN LIKE ?) ';
                        $types_filter .= 'ss';
                        array_push($vars_filter, '%' . $filter->value . '%', '%' . $filter->value . '%');
                    } else if ($filter->filter_type == 'language') {
                        $sql_filter = $sql_filter . ' and language LIKE ? ';
                        $types_filter .= 's';
                        array_push($vars_filter, '%' . $filter->value . '%');
                    } else if ($filter->filter_type == 'release_time') {
                        $sql_filter = $sql_filter . ' and (release_time > ? and release_time < ?) ';
                        $types_filter .= 'ss';
                        array_push($vars_filter, $filter->value->begin_time, $filter->value->end_time);
                    } else if ($filter->filter_type == 'director') {
                        $sql_filter .= ' and id in (SELECT film_id FROM film_director WHERE id=film_id and director_name LIKE ?) ';
                        $types_filter .= 's';
                        array_push($vars_filter, '%' . $filter->value . '%');
                    } else if ($filter->filter_type == 'screenwriter') {
                        $sql_filter .= ' and id in (SELECT film_id FROM film_screenwriter WHERE id=film_id and screenwriter_name LIKE ?) ';
                        $types_filter .= 's';
                        array_push($vars_filter, '%' . $filter->value . '%');
                    } else if ($filter->filter_type == 'starring') {
                        $sql_filter .= ' and id in (SELECT film_id FROM film_starring WHERE id=film_id and starring_name LIKE ?) ';
                        $types_filter .= 's';
                        array_push($vars_filter, '%' . $filter->value . '%');
                    } else if ($filter->filter_type == 'type') {
                        $sql_filter .= ' and id in (SELECT film_id FROM film_type WHERE id=film_id and file_type=? ) ';
                        $types_filter .= 'i';
                        array_push($vars_filter, $filter->value);
                    }
                }
            } catch (Exception $e) {
                Logger::warning(__FILE__ . ':' . $e->getline() . ' \n' . $e->getMessage() . '\n' . $e->getTraceAsString());
                $response->set_response(false, 401, 'illegal parameter: filters');
            }

            if ($response->is_error == false) {
                $sql .= $sql_filter;
                $types .= $types_filter;
                array_push($vars, ...$vars_filter);
            }
        }
    }


    //设置limit
    $limit = $config['research']['maxsize'];
    if (isset($_GET['limit'])) {
        if ($_GET['limit'] < $limit) {
            $limit = $_GET['limit'];
        }
    }
    $sql .= " LIMIT $limit ";

    //设置offset
    $offset = 0;
    if (isset($_GET['offset'])) {
        $offset = $_GET['offset'];
    }
    $sql .= " OFFSET $offset ";

    //发起请求
    if ($films = stmt_query($con, $sql, $types, ...$vars)) {
        $film_length = count($films);
        $query_error = false;
        for ($i = 0; $i < $film_length; $i++) {
            $film = $films[$i];
            //导演
            if ($result = stmt_query($con, "SELECT director_name FROM film_director WHERE film_id=?", 'i', $film['id'])) {
                $films[$i]['director'] = array();
                foreach ($result as $item) {
                    array_push($films[$i]['director'], $item['director_name']);
                }
            } else {
                $query_error = true;
            }
            //编辑
            if ($result = stmt_query($con, "SELECT screenwriter_name FROM film_screenwriter WHERE film_id=?", 'i', $film['id'])) {
                $films[$i]['screenwriter'] = array();
                foreach ($result as $item) {
                    array_push($films[$i]['screenwriter'], $item['screenwriter_name']);
                }
            } else {
                $query_error = true;
            }
            //主演
            if ($result = stmt_query($con, "SELECT starring_name FROM film_starring WHERE film_id=?", 'i', $film['id'])) {
                $films[$i]['starring'] = array();
                foreach ($result as $item) {
                    array_push($films[$i]['starring'], $item['starring_name']);
                }
            } else {
                $query_error = true;
            }
            //类型
            if ($result = stmt_query($con, "SELECT type_id , type_name FROM film_type,type WHERE type_id=id and film_id=?", 'i', $film['id'])) {
                $films[$i]['type'] = $result;
            } else {
                $query_error = true;
            }
        }
        if ($query_error == true) {
            Logger::warning(__FILE__ . ':' . __LINE__ . ' ' . json_encode($stmt->error_list));
            $response->set_response(false, 402, 'incomplete result');
        }

        $is_finish = ($film_length == $limit) ? false : true;
        $result = array(
            'next_offset' => $offset + $film_length,
            'length' => $film_length,
            'is_finish' => $is_finish,
            'films' => $films
        );
        $response->result = $result;
    } else {
        $response->set_response(false, 102);
    }

    $con->close();
    echo $response->to_json();
    return;
}
echo (new response(false, 102))->to_json();
return;

/*
SQL

SELECT id,name,name_CN,language,release_time,movie_douban
FROM film 
WHERE 
    name LIKE '%你%' 
    and language LIKE '%汉%' 
    and id in (
        SELECT film_id
        FROM film_director
        WHERE true and id=film_id and director_name LIKE '%才%'
    )
LIMIT 1
OFFSET 0
*/
