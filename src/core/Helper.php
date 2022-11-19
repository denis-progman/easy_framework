<?php


namespace DenisPm\EasyFramework\core;


class Helper
{
    public static function toCamelCase(string $name, bool $firstUpper = false): string {
        $words = explode('_', $name);

        if (count($words) <= 1) {
            if (!$firstUpper) {
                return $name;
            }
            return ucfirst($name);
        }

        foreach ($words as &$word){
            if (!$firstUpper) {
                $firstUpper = true;
                continue;
            }
            $word = ucfirst($word);
        }
        return implode('', $words);
    }

    public static function toSnakeCase(string $name): string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $name, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function tryFieldType(object $object, string $fieldName): ?string{
        try {
            $object->{'set' . ucfirst($fieldName)}(function () {});
        } catch (\Throwable $e) {
            preg_match_all(
                "/instance of\s([A-z\\]*\\[A-z]*)[A-z\s]*,/i",
                $e->getMessage(),
                $name
            );
            return $name[1][0];
        }
        return null;
    }

    public static function trimFields(array &$fields): void {
        foreach($fields as &$field){
            if(is_string($field)){
                $field = trim($field);
            }
        }
    }

    public static function isAssoc($array): bool
    {
        return array_keys($array) !== range(0, count($array));
    }

}