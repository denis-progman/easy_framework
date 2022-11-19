<?php

namespace DenisPm\EasyFramework\controllers;

use DenisPm\EasyFramework\core\MainController;
use Exception;

class PageController extends MainController
{
    const REQUEST_TYPE = 'POST';

    const GET_ALL_USERS_REQUEST = 'getAllUsers';

    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        switch ($this->requestDataType) {
            case self::GET_ALL_USERS_REQUEST:

                break;
            default:
                throw new Exception("Unknown request to " . __CLASS__);
        }
    }
}