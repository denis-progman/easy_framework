<?php

namespace DenisPm\EasyFramework\core\HTML;

class Fields
{
    const SUBMIT = [
        HTMLConstants::TAG => "button",
        HTMLConstants::ATTRIBUTES => [
            "type" => "submit",
        ],
        HTMLConstants::INNER_HTML => 'Submit'
    ];

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
        HTMLConstants::PATTERN => '[\w\D\-\_]{2,50}@[\w\D\-\_]{2,50}\.\w{2,5}',
        HTMLConstants::HUMAN_NAME => "e-mail",
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
        HTMLConstants::PATTERN => '[\w\D\-\_]{2,50}@[\w\D\-\_]{2,50}\.\w{2,5}',
        HTMLConstants::HUMAN_NAME => "name",
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
        HTMLConstants::PATTERN => "[\w\d]{2,100}",
        HTMLConstants::HUMAN_NAME => "login",
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
        HTMLConstants::PATTERN => ".{8,100}",
    ];
}