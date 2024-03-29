<?php
namespace forum;

use forum\library\Plugin;
use forum\library\Hook;
use forum\library\Config;
use forum\library\GraphQL;
use forum\library\Router;
use forum\user\Session as UserSession;
use forum\user\Secret as UserSecret;

class App
{
    private static $_hook;
    private static $_plugins = [];
    private static $_userSession;

    /**
     * 启动应用
     *
     * @return void
     */
    public static function start()
    {
        // 自动加载所有插件
        self::$_plugins = Plugin::autoload();

        // 触发 appInited 事件
        self::hook()->trigger('appInited');

        // 初始化用户会话
        self::userSession();

        // 初始化路由
        $routePrefix = Config::get('routePrefix', 'graph', '/api/graph');
        // 注册 GraphQL 路由
        Router::addRoute(['GET', 'POST'], "$routePrefix/{action}", function ($vars) {
            self::hook()->trigger('beforeGraphRespond');
            GraphQL::respond($vars['action']);
            self::hook()->trigger('graphResponded');
        });

        // 执行响应
        Router::respond();
    }

    /**
     * 获取Hook管理器
     *
     * @return Hook
     */
    public static function hook() : Hook
    {
        if (empty(self::$_hook)) {
            self::$_hook = new Hook;
        }

        return self::$_hook;
    }

    /**
     * 获取当前用户的会话
     *
     * @return UserSession
     */
    public static function userSession() : UserSession
    {
        if (empty(self::$_userSession)) {
            $secretKey = (new UserSecret)->getSecretKey();
            self::$_userSession = new UserSession($secretKey);
        }

        return self::$_userSession;
    }

    /**
     * 获取应用跟目录的路径
     *
     * @param string $extraPath 帮助拼接路径
     * @return string
     */
    public static function getAppPath($extraPath = '') : string
    {
        return __DIR__ . '/../' . $extraPath;
    }

    /**
     * 获取所有的插件
     *
     * @return array
     */
    public static function plugins()
    {
        return self::$_plugins;
    }

    /**
     * 批量注册 Type
     *
     * @param string $typeName
     * @param array $directives
     * @return void
     */
    public static function registerTypes(string $typeName, array $directives)
    {
        foreach ($directives as $key => $classPath) {
            self::registerType($typeName, $key, $classPath);
        }
    }

    /**
     * 注册 Type
     *
     * @param string $typeName Type 名称
     * @param string $directive 指令名称
     * @param string $classPath class 路径
     * @return void
     */
    public static function registerType(string $typeName, string $directive, string $classPath)
    {
        $types = Config::get('types', 'graph');
        if (!array_key_exists($typeName, $types)) {
            $types[$typeName] = [];
        }

        $types[$typeName][$directive] = $classPath;
        Config::set('types', $types, 'graph');
    }
    
    // TODO: 合并类型
    public static function mixType(string $typeName, string $directive)
    {
    }
}
