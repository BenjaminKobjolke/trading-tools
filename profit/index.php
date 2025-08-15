<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Calculator;
use App\Controller;
use App\InputValidator;
use App\LanguageManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => false,
    'debug' => true,
    'auto_reload' => true
]);

$calculator = new Calculator();
$validator = new InputValidator();
$language = new LanguageManager(__DIR__ . '/lang');

$controller = new Controller($calculator, $validator, $language, $twig);

$data = $controller->handleRequest($_POST, $_SESSION);

echo $controller->render($data);