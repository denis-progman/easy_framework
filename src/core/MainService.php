<?php
namespace BotConstructor\core;


class MainService
{
    const BOT_ANSWER_CLASSES_PATH = '';

    /**
     * @throws \Exception
     */
    protected static function getChildClasses(): array
    {
        if (!static::BOT_ANSWER_CLASSES_PATH) {
            throw new \Exception("Error: child classes not found while service to service!");
        }
        $arrayFiles = scandir(static::BOT_ANSWER_CLASSES_PATH);
        $arrayFiles = array_map(function ($name) {
            return substr($name, 0, -4);
        },
            array_filter($arrayFiles, function ($name) {
                return !in_array($name, ['.', '..']);
            })
        );
        sort($arrayFiles);
        return $arrayFiles;
    }

    private static function getJsonData($json): array
    {
        return json_decode($json, true);
    }

}