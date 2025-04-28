<?php

namespace App\Controllers;

use \Core\Classes\DB;
use \Core\Classes\MLService;
use \App\Models\User;

class IndexController extends Controller
{
    public function index($request){
        $lr = new MLService('lr');
        return $this->view('home', ['prediction' => $lr->predict([220])]);
    }

    public function user($request){
        return $this->response(200, $request->getAttribute('id'), 'text/plain');
    }

    public function _404($request){
        return $this->response(404, 'This page doesn\'nt exist');
    }

    
}
