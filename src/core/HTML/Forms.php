<?php

namespace DenisPm\EasyFramework\core\HTML;

class Forms
{
    const LOGIN = [
        HTMLConstants::TAG => "login_form",
        HTMLConstants::ATTRIBUTES => [
            "method" => "post",
            "class" => 'html_form',
        ],
        HTMLConstants::CHILDREN => [
            Fields::LOGIN,
            Fields::PASSWORD,
            Fields::SUBMIT,
        ]
    ];

    const AUTHORISATION = [
        HTMLConstants::TAG => "authorisation_form",
        HTMLConstants::ATTRIBUTES => [
            "method" => "post",
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