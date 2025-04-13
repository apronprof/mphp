<?php

use Core\Classes\DB;

function migrate(){
    $files = scandir('db/Migrations/');
    foreach($files as $file){
        if($file == '.' || $file == '..' || explode('.', $file)[0] == '') continue;
        $class = 'DB\\Migrations\\'.str_replace(['_', '.php'], '', $file);
        $obj = new $class;

        $obj->migrate();
    } 
}

function rollback(){
    $files = scandir('db/Migrations/');
        foreach($files as $file){
            if($file == '.' || $file == '..' || explode('.', $file)[0] == '') continue;
            $class = 'DB\\Migrations\\'.str_replace(['_', '.php'], '', $file);
            $obj = new $class;

            $obj->rollback();
        }
}
