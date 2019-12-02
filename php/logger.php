<?php
require_once("config.php");

$config=get_config();
$basepath=$config['log']['basepath'];
$level_limit=$config['log']['level'];

$level2num=array(
    'ERROR'=>1,
    'WARNING'=>2,
    'NOTICE'=>3,
    'DEBUG'=>4
);

final class Logger{
    public static $ERROR='ERROR';
    public static $WARNING='WARNING';
    public static $NOTICE='NOTICE';
    public static $DEBUG='DEBUG';

    public static function error($message){
        self::log(self::$ERROR,$message);
    }

    public static function warning($message){
        self::log(self::$WARNING,$message);
    }

    public static function notice($message){
        slef::log(self::$NOTICE,$message);
    } 

    public static function debug($message){
        self::log(self::$DEBUG,$message);
    }

    private static function log($level,$message){
        global $basepath;
        global $level_limit;
        global $level2num;

        if($level2num[$level]>$level_limit){
            return;
        }

        date_default_timezone_set("PRC");
        $today=date("Ymd");
        $now=date("Y-m-d H:i:s");

        $log_file=dirname(__FILE__).'/../'.$basepath.$today.'.log';
        $log_message="$now | $level | $message\n";

        error_log($log_message,3,$log_file);
    }
}