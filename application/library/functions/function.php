<?php
/**
 * Created by PhpStorm.
 * User: jinbosc16514965
 * Date: 2020/1/4
 * Time: 16:35
 */

function DI(){
    return \Our\DI::one();
}


/**
 * 捕获错误异常
 * @throws Exception
 */
function fatal_handler()
{
    $error = error_get_last();
    if ($error && ($error["type"] === ($error["type"] & (E_ERROR | E_USER_ERROR | E_CORE_ERROR |
                    E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_PARSE)))) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

//        if (!\medoo() instanceOf \Closure) {
//            if (\medoo()->inTransaction()) {
//                \DI()->logger->info('数据库事务中断');
//                \medoo()->rollback();
//            }
//        }
        error_handler($errno, $errstr, $errfile, $errline, 'ERROR_FATAL|');
    }
}


/**
 * 捕获警告
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 * @throws Exception
 */
function error_handler($errno, $errstr, $errfile, $errline, $type = 'STRICT_REEOR|')
{
    if (is_array($type)) {
        $type = 'STRICT_REEOR|';
    }

    \DI()->logger->error($type . $errno . '   ' . $errstr . "\n Error on line   " . $errfile . '   ' . $errline . "\n" . (\Yaf\Registry::get('request')->getParam('s') . "\r\n"));

//    if (stripos($errstr, '\predis\predis') !== false) {
//        throw new Predis\Connection\ConnectionException($errstr);
//    } else if (stripos($errstr, 'Redis::') !== false) {
//        throw new RedisException($errstr);
//    }

}

/**
 * 获取配置文件
 * @param      $key
 * @param null $value
 * @return mixed
 */
function C($key, $value = null)
{
    return \Yaconf::get(APP_NAME . '_' . $key, $value);
}

/**
 * 数据库连接
 * @return \Our\Medoo
 */
function medoo()
{
    return \DI()->medoo;
}