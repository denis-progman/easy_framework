<?php

namespace DenisPm\EasyFramework\HTML;

use DenisPm\EasyFramework\core\HTML\HTMLConstants;

class Fields
{
    const EMAIL = [
        HTMLConstants::TAG => "input",
        HTMLConstants::ATTRIBUTES => [
            "type" => "text",
            "name" => "email",
            "required" => true,
            "placeholder" => "email",
            "style" => [],
            "class" => [],
            "id" => '',
        ],
        HTMLConstants::VALIDATION => [
            HTMLConstants::VALIDATION_CONDITION => '/^[\w\D\-\_]{2,50}@[\w\D\-\_]{2,50}\.\w{2,5}$/ui',
            HTMLConstants::HUMAN_NAME => "e-mail",
        ],
    ];
    const TEL = [
        HTMLConstants::TAG => "input",
        HTMLConstants::ATTRIBUTES => [
            "type" => "tel",
            "name" => "phone",
            "required" => true,
            "placeholder" => "phone",
            "style" => [],
            "class" => [],
            "id" => '',
        ],
        HTMLConstants::VALIDATION => [
            HTMLConstants::VALIDATION_CONDITION => '/^[\w\D\-\_]{2,50}@[\w\D\-\_]{2,50}\.\w{2,5}$/ui',
            HTMLConstants::HUMAN_NAME => "name",
        ],
    ];
    const LOGIN = [
        HTMLConstants::TAG => "input",
        HTMLConstants::ATTRIBUTES => [
            "type" => "text",
            "name" => "login",
            "required" => true,
            "placeholder" => "login",
            "style" => [],
            "class" => [],
            "id" => '',
        ],
        HTMLConstants::VALIDATION => [
            HTMLConstants::VALIDATION_CONDITION => "/^[\w\s]{2,100}$/ui",
            HTMLConstants::HUMAN_NAME => "login",
        ],
    ];

    const PASSWORD = [
        HTMLConstants::TAG => "input",
        HTMLConstants::ATTRIBUTES => [
            "type" => "password",
            "name" => "login",
            "placeholder" => "password",
            "required" => true,
            "style" => [],
            "class" => [],
            "id" => '',
        ],
        HTMLConstants::HUMAN_NAME => "password",
        HTMLConstants::VALIDATION => "/^[\w\s]{2,100}$/ui",
    ];
}