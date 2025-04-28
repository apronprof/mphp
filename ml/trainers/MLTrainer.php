<?php

namespace ML\Trainers;

require __DIR__ . '/../../consts.php';
require __DIR__ . '/../../vendor/autoload.php';
use Core\Classes\QueryBuilder;
use Core\Classes\DB;


class MLTrainer
{

    protected function db($params = __DIR__ . '/../../config/db.php'){
        $db = new DB(require($params));
    }

}

?>
