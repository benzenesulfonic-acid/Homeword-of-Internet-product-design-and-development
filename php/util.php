<?php
require_once('logger.php');
//返回数据包装类
class response
{
    public $is_error = true;
    public $error_type = "";
    public $error_message = "";
    public $result = "";

    function __construct($response_true = false, $error_type = null, $error_message = null, $result = null)
    {
        if ($response_true) {
            $this->is_error = false;
            if ($error_type != null) {
                $this->result = $error_type;
            }
        } else {
            $this->is_error = true;
            if ($error_type != null) {
                if (!set_error_type($error_type)) {
                    $this->error_type = $error_type;
                    if ($error_type != null) {
                        $this->error_message = $error_message;
                    }
                }
            }
            if ($result != null) {
                $this->result = $result;
            }
        }
    }

    //设定返回的错误类型，即限于通用错误类型
    public function set_error_type($error_type)
    {
        if ($error_type == 101) {
            $this->error_type = 101;
            $this->error_message = "implete parameters";
        } else if ($error_type == 102) {
            $this->error_type = 102;
            $this->error_message = "database error";
        } else {
            return false;
        }
    }

    public function to_json()
    {
        $json = json_encode(array(
            'is_error' => $this->is_error,
            'error_type' => $this->error_type,
            'error_message' => $this->error_message,
            'result' => $this->result
        ));

        if ($this->is_error) {
            Logger::warning($json);
        } else {
            Logger::debug($json);
        }

        return $json;
    }
}
