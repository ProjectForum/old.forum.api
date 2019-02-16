<?php
namespace plugins\test;

use forum\App;
use forum\library\Config;

class Bootstrap
{
    public function start()
    {
        App::hook()->listen('appInited', 'testPlugin', function ($name) {
            // var_dump($name);
        });
        App::registerType('test', 'query', \plugins\test\types\TestQuery::class);
    }
}