<?php


use App\App;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require '../../vendor/autoload.php';
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$app = new App();
$app->run();



