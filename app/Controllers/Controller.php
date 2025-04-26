<?php

namespace App\Controllers;

use \Core\Classes\BaseController as BaseController;

class Controller extends BaseController
{

    public function _404($request){
        return $this->response(404, $request->getUri()->getPath() . " doesn't exitst");
    }
}
