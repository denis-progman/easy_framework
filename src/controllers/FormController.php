<?php

namespace DenisPm\EasyFramework\controllers;

use DenisPm\EasyFramework\core\MainController;
use Exception;

class FormController extends MainController
{
    const REQUEST_TYPE = 'POST';

    const GET_ALL_USERS_REQUEST = 'getAllUsers';

    public function __construct()
    {
        parent::__construct($_REQUEST);
    }

    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        switch ($this->formName) {
            case self::GET_ALL_USERS_REQUEST:
                break;
            default:
                throw new Exception("Unknown request to " . __CLASS__);
        }
    }
}