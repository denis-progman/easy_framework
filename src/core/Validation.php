<?php

namespace DenisPm\EasyFramework\core;

use DenisPm\EasyFramework\core\HTML\Forms;
use Exception;

class Validation
{
    /**
     * @throws Exception
     */
    public static function FormValidate(string $formName, array $formData): void {
        $statusMessage = [];
        if (count($formData) !== count(Forms::AVAILABLE_LIST[$formName])) {
            $statusMessage[] = "Не соответствие количества полей";
        }

        foreach (Forms::AVAILABLE_LIST[$formName] as $fieldName => $fieldValue) {
            if (!isset($formData[$fieldName])) {
                $statusMessage[] = "Не найдено поле $fieldName в форме $formName";
            }
            if (isset($fieldValue["required"]) && !$formData[$fieldName]){
                $statusMessage[] = "Поле $fieldName не заполнено";
            }
            if (
                @$fieldValue["pattern"]
                && @$formData[$fieldName]
                && !preg_match($fieldValue["pattern"], $formData[$fieldName])
            ) {
                $statusMessage[] = "Поле $fieldName не корректно заполнено";
            }
        }
        if (!empty($statusMessage)) {
            throw new Exception(implode("<br>", $statusMessage));
        }
    }
}