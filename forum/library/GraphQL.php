<?php
namespace forum\library;

use forum\library\graphql\Types;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL as BaseGraphQL;

class GraphQL
{
    /**
     * 对请求进行响应
     *
     * @param string $action
     * @return void
     */
    public static function respond(string $action)
    {
        // 获取schema
        $config = Config::get('', 'graph');
        $types = $config['types'];
        $schemaTypes = [];

        if (in_array($action, $config['schema']) || array_key_exists($action, $config['schema'])) {
            // 如果为key则使用其value作为action
            if (array_key_exists($action, $config['schema'])) {
                $action = $config['schema'][$action];
            }
        } else {
            // throw new \think\exception\HttpException(404, "[$action] 未在 schema 中定义");
            return self::jsonResponse([
                'code' => 404,
                'message' => "[$action] 未在 schema 中定义"
            ], 404);
        }

        // 判断action是否在types中
        if (!array_key_exists($action, $types)) {
            // throw new \think\exception\HttpException(404, "Type [$action] 未在 types 中定义");
            return self::jsonResponse([
                'code' => 404,
                'message' => "Type [$action] 未在 types 中定义"
            ], 404);
        }
        
        // 构建当前action对应的获取schema
        if (gettype($types[$action]) == 'array') {
            foreach ($types[$action] as $key => $typeClass) {
                $schemaTypes[$key] = Types::{$action}($key);
            }
        } else {
            $schemaTypes['query'] = Types::{$action}('query');
        }

        $schema = new Schema($schemaTypes);

        // 从请求中获取数据
        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'];
        $variables = !empty($input) && array_key_exists('variables', $input) ? $input['variables'] : null;
        $rootValue = [];

        if (empty($query)) {
            return self::jsonResponse([
                'code' => 403,
                'message' => 'query is empty!'
            ], 403);
        }

        $isDebug = Config::get('debug', 'app');
        $output = BaseGraphQL::executeQuery($schema, $query, $rootValue, [], $variables)->toArray($isDebug);
        return self::jsonResponse($output);
    }

    /**
     * 返回json数据
     *
     * @param mixed $data
     * @param integer $code
     * @return void
     */
    protected static function jsonResponse($data, $code = 200)
    {
        if (!headers_sent()) {
            http_response_code($code);
            header('Content-Type:application/json; charset=utf-8');
        }
        $jsonResponse = json_encode($data, JSON_UNESCAPED_UNICODE);
        self::send($jsonResponse);
    }

    /**
     * 发送数据
     *
     * @param string $data
     * @return void
     */
    protected static function send(string $data)
    {
        echo $data;
    }
}
