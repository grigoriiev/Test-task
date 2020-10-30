<?php
require_once __DIR__ .'./vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Events\Dispatcher;

use Illuminate\Container\Container;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DRIVER'],
    'host'      => $_ENV['HOST'],
    'database'  => $_ENV['DATABASE'],
    'username'  => $_ENV['USER'],
    'password'  => $_ENV['PASSWORD'],
    'charset'   => $_ENV['CHARSET'],
    'collation' => $_ENV['COLLATION'],
    'prefix'    => $_ENV['PREFIX'],
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();




Capsule::schema()->create('sheets_excel', function ($table) {

    $table->increments('id');

    $table->string('value_1');

    $table->string('value_2');

    $table->timestamps();

});
