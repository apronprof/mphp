<?php

require __DIR__ .'/../../vendor/autoload.php';


use Rubix\ML\Regressors\Ridge;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\PersistentModel;



// === 1. Подготовка данных ===
$samples = [[1], [2], [3], [4], [5], [6], [7], [8], [9], [10], [11], [12], [13], [14], [15], [16], [17], [18], [19], [20]];
$labels  = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40];

// === 2. Обучение модели ===
$model = new Ridge();
$model->train(Labeled::build($samples, $labels));

// === 3. Сериализация и сохранение модели ===
/*$modelPath = __DIR__ . '/../models/linear_model.rbx';
$persister = new Filesystem(__DIR__ . '/../lr.rbx');
$mod = new PersistentModel($model, $persister);
*/
$modelPath = __DIR__ . '/../models/lr.rbx';
$estimator = new PersistentModel($model, new Filesystem($modelPath), new RBX());
$estimator->save();


//$model->save();

//$persister->save();  // Сохраняем модель с сериализатором


//use Rubix\ML\Persisters\Filesystem;

// Загружаем модель
/*
$modelPath = __DIR__ . '/models/linear_model.rbx';
$persister = new Filesystem($modelPath);
$model = $persister->load();*/

// Предсказание цены для квартиры 75 кв.м
//$prediction = $model->predict([[75]]);

//echo "Предсказанная цена для 75 кв.м: " . $prediction[0] . " тыс\n";

?>
