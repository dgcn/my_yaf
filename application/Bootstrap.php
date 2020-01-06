<?php

/**
 * @name Bootstrap
 * @author desktop-v6pi47j\jinbosc16514965
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{

    public function _initConfig(Yaf\Dispatcher $dispatcher)
    {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        if ($arrConfig['debug']) {
            // 启动追踪器
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('display_errors', 'Off');
        }
        ini_set('expose_php', 'Off');

        Yaf\Registry::set('config', $arrConfig);
        Yaf\Registry::set('request', $dispatcher->getRequest());
    }

    /**
     * 加载公共函数库
     */
    public function _initFunction()
    {
        Yaf\Loader::import('functions/constant.php');
        Yaf\Loader::import('functions/function.php');

    }

    public function _initDi()
    {
        $config = Yaf\Registry::get('config');

        //初始化日志
        \DI()->logger = function () use ($config) {
            return new \Our\Logger\File(
                $config->get('log_path'),
                \Our\Logger::LOG_LEVEL_DEBUG | \Our\Logger::LOG_LEVEL_INFO | \Our\Logger::LOG_LEVEL_ERROR
            );
        };

        //连接数据库
        \DI()->medoo = function()use($config){
            $config = C('app.db');
            return new \Our\Medoo($config);
        };

        //捕获致命异常
        register_shutdown_function('fatal_handler');
        //捕获E_STRICT异常
        set_error_handler('error_handler');
    }

    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        //注册一个插件
//        $objSamplePlugin = new SamplePlugin();
//        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
        $router = $dispatcher::getInstance()->getRouter();
        $router->addRoute('myrouter', new \Our\Router());
    }

    public function _initView(Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin

        //取消自动加载模板
        $dispatcher::getInstance()->autoRender(FALSE);
    }
}
