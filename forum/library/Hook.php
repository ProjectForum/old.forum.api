<?php
namespace forum\library;

class Hook
{
    protected $hooks = [];

    /**
     * 监听某个事件的触发
     *
     * @param string $eventName
     * @param string $hookName
     * @param callable $callback
     * @return void
     */
    public function listen(string $eventName, string $hookName, callable $callback)
    {
        // 初始化该事件的钩子列表
        if (array_key_exists($eventName, $this->hooks)) {
            $this->hooks[$eventName] = [];
        }
        // 推入事件列表
        $this->hooks[$eventName][] = [
            'name' => $hookName,
            'callback' => $callback,
        ];
    }

    /**
     * 触发某个事件
     *
     * @param string $eventName
     * @param array $args
     * @return void
     */
    public function trigger(string $eventName, array $args = []) : void
    {
        if (array_key_exists($eventName, $this->hooks)) {
            foreach ($this->hooks[$eventName] as $key => $hook) {
                call_user_func($hook['callback'], $hook['name'], ...$args);
            }
        }
    }
}
