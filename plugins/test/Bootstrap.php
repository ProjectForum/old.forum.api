<?php
namespace plugins\test;

use forum\App;
use forum\library\Config;
use forum\library\Router;

class Bootstrap
{
    /**
     * 对插件进行描述
     *
     * @return array
     */
    public function info()
    {
        return [
            'name' => '测试插件',
            'desc' => '测试插件的描述',
        ];
    }

    /**
     * 启动插件
     *
     * @return void
     */
    public function start()
    {
        App::hook()->listen('appInited', 'testPlugin', function ($name) {
            // var_dump($name);
        });
        App::registerType('test', 'query', \plugins\test\types\TestQuery::class);
        Router::addRoute('GET', '/plugin/test/array', function () {
            return [
                'code' => 1,
                'message' => 'successed',
                'plugins' => App::plugins()
            ];
        });
        Router::addRoute('GET', '/plugin/test/string', function () {
            return 'hello world';
        });
    }
}