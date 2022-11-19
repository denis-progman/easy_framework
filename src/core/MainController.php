<?php

namespace DenisPm\EasyFramework\core;

class MainController
{
    const REQUEST_TYPE = null;

    protected string $requestDataType;

    public function __construct(?string $type = null)
    {
        if (isset(${'_' . ($type ?: self::REQUEST_TYPE)}[Constants::REQUEST_DATA_MARK])) {
            $this->requestDataType = ${'_' . self::REQUEST_TYPE}[Constants::REQUEST_DATA_MARK];
            unset(${'_' . self::REQUEST_TYPE}[Constants::REQUEST_DATA_MARK]);
        }
    }
}