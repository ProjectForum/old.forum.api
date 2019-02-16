<?php
namespace forum\library;

use forum\App;

class Plugin
{
    /**
     * 自动加载所有插件
     *
     * @param string $path
     * @param string $namespace
     * @return array
     */
    public static function autoload(string $path = '', string $namespace = '\\plugins\\') : array
    {
        // 如果路径为空则从默认位置加载插件
        if (empty($path)) {
            $path = __DIR__ . '/../../plugins/';
        }

        if (is_dir($path)) {
            $plugins = [];

            $files = scandir($path);
            foreach ($files as $file) {
                $pluginPath = "{$path}{$file}";
                $classPath = "{$namespace}{$file}\\Bootstrap";
                // 判断文件、引导类是否存在
                if (is_dir($pluginPath)
                    && file_exists("{$pluginPath}/Bootstrap.php")
                    && class_exists($classPath)) {

                    // 实例化插件
                    $pluginInstance = new $classPath();
                    if (method_exists($pluginInstance, 'start')) {
                        $pluginInstance->start();
                    }
                    $plugins[] = $pluginInstance;
                }
            }

            return $plugins;
        } else {
            throw new \Exception('插件路径不可用');
        }
    }
}
