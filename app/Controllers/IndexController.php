<?php

namespace App\Controllers;

use \Core\Classes\DB;
use \Core\CLasses\MLService;
use \App\Models\User;

class IndexController extends Controller
{
    public function index($request){
        /*$user = $_SESSION['user'];

        $stmt = User::prepare("SELECT * FROM users WHERE user = ?");
        $stmt->execute([$user]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);


        if(!isset($data['user'])){

            $stmt = User::prepare("INSERT INTO users(user) VALUES(?)");
            $stmt->execute([$user]);

            $stmt = User::prepare("SELECT * FROM users WHERE user = ?");
            $stmt->execute([$user]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        }

        $this->view('index', ['user' => $user, 'data' => $data]);
         */

//        $lr = new MLService('lr');
//        echo $lr->predict([220])[0];


        $this->view('home');
    }

    public function user($request){
        echo $request->getAttribute('id');
    }

    public function _404($params){
        echo 'url ' . $params['url'] . ' doesn\'t exist';
    }
}
