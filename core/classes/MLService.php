<?php

namespace Core\Classes;

use Rubix\ML\Regressors\Ridge;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;


class MLService
{
    private 
        $modelPath,
        $model;

    public function __construct($modelPath){
        $this->modelPath = ML . "models/$modelPath.rbx";
        $this->connect();
    }

    
    private function connect(){
        $this->model = PersistentModel::load(new Filesystem($this->modelPath), new RBX());
    }

    public function predict($args){
        return $this->model->predict(new Unlabeled($args));
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

    public function getModelPath($name){
        return $this->modelPath;
    }
}

?>
