<?php

$config['db'] = require('config/db.php');

require(__DIR__ . '/core/classes/querybuilder.class.php');
require(__DIR__ . '/core/classes/db.class.php');

use Core\Classes\DB;
use Core\Classes\QueryBuilder;

foreach (glob(__DIR__ . '/db/Migrations/*.php') as $file) {
    require_once $file;
}


$db_config = $config['db'];

switch($db_config['db']){
    case null:
        break;
    case 'mysql':
        $mysql = $db_config['mysql'];
        $dsn = 'mysql:host='.$mysql['host'].';dbname='.$mysql['name'].';charset='.$mysql['charset'];
        DB::connect($dsn, $mysql['user'], $mysql['pass']);
        break;
    case 'sqlite3':
        $file = $db_config['sqlite3']['file'];
        $dsn = 'sqlite:db/sqlite3/'.$file;
        DB::connect($dsn, '', '', true);
        break;
}

require(__DIR__ . '/core/migrate.function.php');

rollback();

?>
