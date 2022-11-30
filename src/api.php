<?php

use DenisPm\EasyFramework\core\HTML\HTMLConstants;
use DenisPm\EasyFramework\controllers\FormController;
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

echo "<pre>";

if ($_SERVER["HTTP_CONTENT_TYPE"] == HTMLConstants::FORM_CONTENT_TYPE && $_SERVER["REQUEST_METHOD"]) {
    $formController  = new FormController;
} else {
    echo 404;
}