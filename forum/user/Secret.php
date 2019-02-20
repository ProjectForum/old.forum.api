<?php
namespace forum\user;

use forum\library\Config;
use forum\App;

class Secret
{
    private static $_instantiated = false;

    public function __construct()
    {
        // 控制该类只能被实例化一次
        if (self::$_instantiated) {
            throw new \Exception('Instantiation cannot be completed');
        } else {
            self::$_instantiated = true;
        }
    }

    protected function createSecretKey() : string
    {
        $key = base64_encode(uniqid('forum_', true));
        $configPath = App::getAppPath('config/secret.php');
        file_put_contents($configPath, "<?php\nreturn ['secretKey' => '{$key}'];");

        return $key;
    }

    public function getSecretKey() : string
    {
        $key = Config::get('secretKey', 'secret');
        if ($key === null) {
            $key = $this->createSecretKey();
        }

        return $key;
    }
}
