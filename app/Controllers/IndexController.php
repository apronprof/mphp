<?php

namespace App\Controllers;

use \Core\Classes\DB;
use \App\MLApi;
//use \Core\Classes\MLService;
use \App\Models\User;
use \App\Models\Info;

class IndexController extends Controller
{
    public function index($request){
        $lr = new MLApi('lr');
        //$lr2 = $lr->save('lr2');
        return $this->view('home', ['prediction' => $lr->mse([1, 2], [2, 4])]);
    }

    public function getJson($request){
        $data = Info::get();
        return $this->json(200, $data);
    }

    public function user($request){
        return $this->response(200, $request->getAttribute('id'), 'text/plain');
    }

    public function _404($request){
        return $this->response(404, 'This page doesn\'nt exist');
    }

    
}
