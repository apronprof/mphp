<?php

namespace Core\Classes;

use Rubix\ML\Regressors\Ridge;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\CrossValidation\Metrics\Accuracy;
use Rubix\ML\CrossValidation\Metrics\FBeta;
use Rubix\ML\CrossValidation\Metrics\MeanSquaredError;


class MLService
{
    protected
        $modelPath,
        $model;

    public function __construct($modelPath){
        $this->modelPath = ML . "models/$modelPath.rbx";
        $this->connect();
    }

    
    protected function connect(){
        $this->model = PersistentModel::load(new Filesystem($this->modelPath), new RBX());
    }

    public function predict($data){
        return $this->model->predict(new Unlabeled($data));
    }

    public function save($newName=''){
        if($newName==''){
            $this->model->save();
            return;
        }

        $newPath = ML."models/$newName.rbx";
        copy($this->modelPath, $newPath);
        return new self($newName);
    }

    public function info(){
        return [
            'class' => get_class($this->model),
            'trained' => $this->model->trained(),
            'params' => method_exists($this->model, 'params') ? $this->model->params() : null,
        ];
    }

    public function mse($samples, $labels){
        $data = new Labeled($samples, $labels);

        $predictions = $this->model->predict($data);

        $metrics = [
            'mse' => (new MeanSquaredError())->score($predictions, $data->labels()),
        ];
        return $metrics;
    }

    public function getModelPath($name){
        return $this->modelPath;
    }
}

?>
