<?php


namespace DenisPm\EasyFramework\core;

/**
 * Class Loger
 * @package BotConstructor\core
 */
class Loger extends ModelFather
{
    const ERROR_LEVEL = 'error';
    const WARNING_LEVEL = 'warning';
    const LOG_LEVEL = 'log';
    const CHECK_LEVEL = 'CHECK';

    public static ?array $config = null;

    public static function builder() {
        self::$config = (include 'app_config.php')['loger'];
    }

    /**
     * @param $data
     * @param string $level
     * @return bool
     */
    public static function log($data, string $level = self::LOG_LEVEL, bool $andOnes = false): bool
    {
        if ($andOnes) {
            file_put_contents(
                "logs/once/" . date('d-m-Y') . "_". self::$config['file'] . "_" . $level .".txt",
                print_r($data, true)
            );
        }
        return (bool) file_put_contents(
            "logs/" . self::$config['file'] . "_$level.txt",
            '# ' . date('d-m-Y H:i:s') . ' ==> ' . print_r($data, true) . "\n",
            FILE_APPEND
        );
    }

}