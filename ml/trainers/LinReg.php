<?php

namespace ML\Trainers;

require __DIR__ . '/../../consts.php';
require __DIR__ . '/../../vendor/autoload.php';


use Core\Classes\DB;
use App\Models\Info;
use Rubix\ML\Regressors\Ridge;

$db = new DB();

$trainer = new MLTrainer();

echo Info::get()[0]['info'];

$model = new Ridge();
