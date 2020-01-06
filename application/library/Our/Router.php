<?php
/**
 * Created by PhpStorm.
 * User: jinbosc16514965
 * Date: 2020/1/4
 * Time: 17:15
 */

namespace Our;


class Router implements \Yaf\Route_Interface
{
    public function route($req)
    {
        $str = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']) - 9);
        $str = explode('?', $str);
        $url = explode('/', $str[0]);

        $list = array(ADMIN_PATH, APP_PATH);
        $type = HOME_PATH;


        $plugins = C('app.plugins');
        $plugins = ',' . strtolower(implode(',', array_keys($plugins))) . ',';


        if (!empty($url[0])) {
            $url[0] = strtolower($url[0]);
            foreach ($list as $item) {
                if ($item == $url[0]) {
                    $type = $item;
                    break;
                }
            }
        }

        switch ($type) {
            case ADMIN_PATH:
                $module = 'admin';
                break;
            case APP_PATH:
                $module = 'app';
                break;
            default:
                $module = 'home';
                break;
        }

        define('MODULE', $module);

        if ($type == HOME_PATH) {
            if (empty($url[0])) {
                unset($url[0]);
            }
            array_unshift($url, $module);
        }

        //默认模块
        $p_name = !empty($url[1]) ? strtolower($url[1]) : 'base';
        if (strpos($plugins, ',' . $p_name . ',') === false) {
            $p_name = 'base';
            array_unshift($uri, $p_name);
        }

        //默认控制器
        $controller = !empty($url[2]) ? strtolower($url[2]) : 'index';

        //默认方法
        $action = !empty($url[3]) ? strtolower($url[3]) : 'index';

        $root = $p_name . '/' . $controller . '/' . $action;


        $req->module = $module;
        $req->controller = $p_name . '_' . $controller;
        $req->action = $action;

        //请求参数
        $param = [];

        //提交参数
        if (!empty($str[1])) {
            $params = explode('/', $str[1]);
            if (count($params) % 2 == 1) {
                array_pop($params);
            }

            foreach ($params as $key => $value) {
                if($key%2 == 0){
                    $param[$params[$key]] = $params[$key + 1];

                }
            }

        } elseif(!empty($_REQUEST['data'])) {//post提交
            $param = $_REQUEST['data'];
        }

        $req->setParam('s', $root);//设置请求路径
        $req->setParam('data', $param);//设置请求参数


//        var_dump($req);exit;
        return true;
    }

    public function assemble(array $mvc, array $query = NULL)
    {
        return true;
    }

}