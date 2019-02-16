<?php
namespace plugins\test\types;

use forum\library\graphql\Types;
use forum\library\graphql\ObjectType;

class TestQuery extends ObjectType {
    /**
     * 类型描述
     *
     * @return array
     */
    public function attrs() : array
    {
        return [
            'name' => 'TestQuery',
            'desc' => ''
        ];
    }

    public function fields() : array
    {
        return [
            'test' => Types::string()
        ];
    }

    public function resolveTest()
    {
        return 'Test Field';
    }
}
