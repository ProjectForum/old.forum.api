<?php
namespace plugins\test;

use forum\App;
use forum\library\Config;
use forum\library\Router;

class Bootstrap
{
    public function start()
    {
        App::hook()->listen('appInited', 'testPlugin', function ($name) {
            // var_dump($name);
        });
        App::registerType('test', 'query', \plugins\test\types\TestQuery::class);
        Router::addRoute('GET', '/plugin/test/array', function () {
            return [
                'code' => 1,
                'message' => 'successed'
            ];
        });
        Router::addRoute('GET', '/plugin/test/string', function () {
            return 'hello world';
        });
    }
}