<?php

namespace App\Controllers;

use \Core\Classes\DB;
use \App\MLApi;
use \App\Models\Info;

class IndexController extends Controller
{
    public function index($request){
        return $this->view('index');
    }

    public function predict($request){
        $lr = new MLApi('lr');
        $prediction = $lr->predict([$request->getAttribute('num')]);
        return $this->view('predict', ['prediction' => $prediction]);
    }

    public function getJson($request){
        return $this->json(200, ['data' => [1 => 'test']]);
    }

    public function user($request){
        return $this->response(200, $request->getAttribute('id'), 'text/plain');
    }

    public function _404($request){
        return $this->response(404, 'This page doesn\'nt exist');
    }

    
}
