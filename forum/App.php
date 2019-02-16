<?php
namespace forum;

use forum\library\Plugin;
use forum\library\Hook;
use forum\library\Config;
use forum\library\GraphQL;

class App
{
    private static $_hook;

    /**
     * 启动应用
     *
     * @return void
     */
    public static function start()
    {
        $plugins = Plugin::autoload();

        self::hook()->trigger('appInited');

        self::hook()->trigger('beforeGraphRespond');

        GraphQL::respond('forum');

        self::hook()->trigger('graphResponded');
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
