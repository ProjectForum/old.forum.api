<?php
namespace forum\library;

class Config
{
    private static $_config = [];

    /**
     * 加载配置项模块
     *
     * @param string $module 模块名称
     * @return array
     */
    public static function loadModule(string $module) : array
    {
        $configPath = __DIR__ . '/../../config';
        if (!array_key_exists($module, self::$_config)) {
            self::$_config[$module] = include "{$configPath}/{$module}.php";
        }

        return self::$_config[$module];
    }

    /**
     * 获取配置项
     *
     * @param string $name 配置项名称 为空则获取所有
     * @param string $module 模块名称
     * @param string $default 默认值
     * @return mixed
     */
    public static function get(string $name, string $module = 'app', string $default = null)
    {
        // 加载配置
        $config = self::loadModule($module);
        if (empty($name)) {
            return $config;
        }

        // 读取配置
        $names = explode('.', $name);
        $value = $config;

        foreach ($names as $key => $name) {
            if (array_key_exists($name, $value)) {
                $value = $value[$name];
            } else {
                return $default;
            }
        }

        return $value;
    }

    /**
     * 设置配置项
     *
     * @param string $key
     * @param mixed $value
     * @param string $module
     * @return mixed
     */
    public static function set(string $key, $value, string $module = 'app')
    {
        self::loadModule($module);
        $names = explode('.', $key, 2);

        if (count($names) == 1) {
            self::$_config[$module][$names[0]] = $value;
        } else {
            self::$_config[$module][$names[0]][$names[1]] = $value;
        }

        return $value;
    }
}
