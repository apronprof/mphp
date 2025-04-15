<?php

require __DIR__ .'/../../vendor/autoload.php';


use Rubix\ML\Regressors\Ridge;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;

$estimator = PersistentModel::load(new Filesystem('../models/lr.rbx'), new RBX());

var_dump($estimator->predict(new Unlabeled([220])));
