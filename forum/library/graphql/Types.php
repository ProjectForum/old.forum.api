<?php
namespace forum\library\graphql;

use forum\library\Config;
use \smilecc\think\Support\Types as ParentTypes;

class Types extends ParentTypes
{
    public static function __callStatic($name, $arguments)
    {
        self::$config = Config::get('', 'graph');

        if (count($arguments) > 0) {
            $typeConfig = [];
            $typeName = $name;

            // 判断是否有参数
            if (array_key_exists(1, $arguments)) {
                $typeConfig = $arguments[0];
                // 如果设置了TypeName 则改变TypeName
                var_dump($arguments);
                if (array_key_exists('name', $typeConfig)) {
                    $typeName = $typeConfig['name'];
                }
            }
            
            return self::getType($name, $typeName, $arguments[0], $typeConfig);
        } else {
            return self::getType($name, $name, 'query', []);
        }
    }
}
