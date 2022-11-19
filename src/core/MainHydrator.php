<?php

namespace DenisPm\EasyFramework\core;

use Exception;

class MainHydrator
{
    const NAMESPACE_CLASSES = "";

    const FULL_NAMES_CLASSES = [];

    const PARAMS_MAPPER = [];

    protected static array $hashModels = [];

    /**
     * @throws Exception
     */
    public static function hydrate(array $params, object &$model): object {
        $tmpHashName = md5(__CLASS__ . print_r($params, true));
        if (isset(self::$hashModels[$tmpHashName])) {
            return $model = self::$hashModels[$tmpHashName];
        }
        foreach ($params as $paramKey => $paramValue){
            $className = Helper::toCamelCase($paramKey, true);
            if (isset(static::PARAMS_MAPPER[$className])) { // field mapping
                $className = static::PARAMS_MAPPER[$className];
            }

            if (
                self::checkModelClassExist($className)
                && is_array($paramValue)
                && !is_numeric($paramKey)
            ) {

                $paramObject = static::reHydrate($paramValue, $className);
                self::writeProperty($model, $className, $paramObject);
            }
            elseif (
                str_ends_with($className, 's')
                && self::checkModelClassExist($childClassName = substr($className,0,-1))
                && is_array($paramValue)
            ) {
                $arrayObjects = null;
                foreach ($paramValue as $oneValParam) {
                    $paramObject = static::reHydrate($oneValParam, $childClassName);
                    $arrayObjects[] = $paramObject;
                }
                self::writeProperty($model, $className, $arrayObjects);
            }
            else {
                self::writeProperty($model, $className, $paramValue);

            }
        }
        if (!empty($model)) {
            self::$hashModels[$tmpHashName] = $model;
        }
        return $model;
    }

    /**
     * @throws Exception
     */
    protected static function reHydrate(array $param, string $className): object
    {
        $fieldClass = null;
        if (!$fullClassName = static::checkModelClassExist($className)){
            throw new Exception("Error: While reHydrate not found class for '$fieldClass'");
        }
        $newObject = new $fullClassName;
        return self::hydrate($param, $newObject);
    }

    protected static function writeProperty(object $model, string $property, $propertyValue): void
    {
        $checkParamToRemap = substr(strrchr($model::class, "\\"), 1) . "::$property";
        if (isset(static::PARAMS_MAPPER[$checkParamToRemap]) /*&& !property_exists($model, lcfirst($property))*/) { // field mapping
            $property = static::PARAMS_MAPPER[$checkParamToRemap];
        }
        if (property_exists($model, lcfirst($property))) {
            $model->{'set' . ucfirst($property)}($propertyValue);
        }
    }

    public static function checkModelClassExist(string $className): ?string
    {
        if (static::NAMESPACE_CLASSES && class_exists(static::NAMESPACE_CLASSES . $className)){
            return static::NAMESPACE_CLASSES . $className;
        }
        foreach (static::FULL_NAMES_CLASSES as $oneFulNameClass) {
            if (substr(strrchr($oneFulNameClass, "\\"), 1) == $className) {
                return $oneFulNameClass;
            }
        }
        return null;
    }

}