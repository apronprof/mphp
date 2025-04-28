<?php

namespace ML\Trainers;

use App\Models\Info;


class LinReg extends MLTrainer
{

    public function train(){
        $this->db();        
        echo Info::get()[0]['info'];
    }

}
