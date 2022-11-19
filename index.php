<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="src/css/HTMLFields.css">
    <title>Document</title>
</head>
<body>
<?php

use DenisPm\EasyFramework\core\HTML\HTMLElement;
use DenisPm\EasyFramework\core\MainRepository;
use DenisPm\EasyFramework\HTML\Forms;

ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
$request = new MainRepository('htmltags');
echo "<pre>";
print_r($request->getOneDataIntelligent('id', '=', 4));
print_r(Forms::AUTHORISATION);


try {
    $template = new HTMLElement(Forms::AUTHORISATION);
    echo $template->getHTML();
} catch (Throwable $e) {
    echo '<pre>';
    print_r($e->getMessage() . ' | File: ' . $e->getFile() . '(' . $e->getLine() . ')');
}
?>
</body>
</html>
