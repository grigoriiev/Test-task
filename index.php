<?php


require_once __DIR__ .'./vendor/autoload.php';


use  Illuminate\Database\Eloquent\Model;

use Illuminate\Events\Dispatcher;

use Illuminate\Container\Container;


$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

ini_set('error_reporting', E_WARNING);

ini_set('display_errors', 'Off');

ini_set('display_startup_errors', 'Off');

$googleAccountKeyFilePath = __DIR__.$_ENV['GOOGLE_ACCOUNT_KEY_FILE_PATCH'];

putenv( 'GOOGLE_APPLICATION_CREDENTIALS='.$googleAccountKeyFilePath );


$client = new Google_Client();

$client->useApplicationDefaultCredentials();

$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );

$service = new Google_Service_Sheets( $client );

$spreadsheetId = $_ENV["SPREADSHEET_ID"];

$range = 'Лист1!A1:B1';

$response = $service->spreadsheets_values->get($spreadsheetId, $range);

$items=[];

foreach ($response['values'] as $keyColumn =>$valueColumn ){

    foreach ($valueColumn  as $keyRow => $valueRow){

        $items[]=$valueRow;

    }
}

use Illuminate\Database\Capsule\Manager as Capsule;

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




class SheetExcel extends Model {

    protected $table='sheets_excel';

    protected $fillable=['value_1','value_2'];

    protected $guarded=[];

}
$users = new SheetExcel;

$users->value_1=(string)$items[0];

$users->value_2=(string)$items[1];

$users->save();

var_dump($users);


