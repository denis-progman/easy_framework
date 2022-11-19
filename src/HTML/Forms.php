<?php

namespace DenisPm\EasyFramework\HTML;

use DenisPm\EasyFramework\core\HTML\HTMLConstants;

class Forms
{
    const SUMMIT = [
        HTMLConstants::TAG => "button",
        HTMLConstants::ATTRIBUTES => [
            "type" => "submit",
        ],
        HTMLConstants::INNER_HTML => 'Submit'
    ];

    const AUTHORISATION = [
        HTMLConstants::TAG => "form",
        HTMLConstants::ATTRIBUTES => [
            "method" => "post",
            "class" => 'html_form',
        ],
        HTMLConstants::CHILDREN => [
            Fields::LOGIN,
            Fields::PASSWORD,
            Fields::TEL,
            Fields::EMAIL,
            self::SUMMIT,
        ]
    ];
}