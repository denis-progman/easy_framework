<?php

namespace DenisPm\EasyFramework\core;

use DenisPm\EasyFramework\core\HTML\Forms;
use DenisPm\EasyFramework\core\HTML\HTMLConstants;
use Exception;

class MainController
{
    const REQUEST_TYPE = null;

    protected array $requestData;

    protected string $formName;

    /**
     * @throws Exception
     */
    public function __construct(?array $request = null)
    {
        print_r($request[HTMLConstants::FORM_NAME_KEY]);
        if (isset(Forms::AVAILABLE_LIST[$request[HTMLConstants::FORM_NAME_KEY]])) {
            $this->formName = $request[HTMLConstants::FORM_NAME_KEY];
            unset($request[HTMLConstants::FORM_NAME_KEY]);
            Validation::FormValidate($this->formName, $request);
            $this->requestData = $request;
        } else {
            throw new Exception("Unknown form");
        }
    }
}