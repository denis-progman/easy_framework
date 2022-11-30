<?php

namespace DenisPm\EasyFramework\core\HTML;

class Forms
{
    const AVAILABLE_LIST = [
        self::AUTHORISATION[HTMLConstants::FORM_NAME_KEY] => self::AUTHORISATION,
        self::LOGIN[HTMLConstants::FORM_NAME_KEY] => self::LOGIN,
    ];
    const LOGIN = [
        HTMLConstants::TAG => "form",
        HTMLConstants::FORM_NAME_KEY => "login",
        HTMLConstants::ATTRIBUTES => [
            "class" => 'html_form',
        ],
        HTMLConstants::CHILDREN => [
            Fields::LOGIN,
            Fields::PASSWORD,
            Fields::SUBMIT,
        ]
    ];

    const AUTHORISATION = [
        HTMLConstants::TAG => "form",
        HTMLConstants::FORM_NAME_KEY => "authorisation",
        HTMLConstants::ATTRIBUTES => [
            "class" => 'html_form',
        ],
        HTMLConstants::CHILDREN => [
            Fields::LOGIN,
            Fields::PASSWORD,
            Fields::TEL,
            Fields::EMAIL,
            Fields::SUBMIT,
        ]
    ];
}