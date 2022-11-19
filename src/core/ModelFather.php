<?php


namespace DenisPm\EasyFramework\core;


class ModelFather
{
    const CONFIG_FILE = 'src/app_config.php';
    protected static ?array $config = null;

    public static function builder(): void
    {
        $setting = include self::CONFIG_FILE;
        if (isset($setting[self::getClassName()])) {
            static::$config = $setting[self::getClassName()];
        }
    }

    protected static function getClassName(): string
    {
        $class = explode('\\', static::class);
        return $class[count($class)-1];
    }
}