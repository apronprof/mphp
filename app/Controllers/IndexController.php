<?php

namespace App\Controllers;

use \Core\Classes\DB;
use \Core\CLasses\MLService;
use \App\Models\User;

class IndexController extends Controller
{
    public function index($request){
        return $this->view('home', ['hi' => 'he']);
    }

    public function user($request){
        echo $request->getAttribute('id');
        return $this->response(200);
    }

    
}
