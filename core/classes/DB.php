<?php

namespace Core\Classes;

class DB
{

    /*
     * Class DB is responsible for connection to database
     */

    private static $link = null;

    public function __construct($db_config = null){
        if($db_config == null){
            $db_config = require(\CONFIG."db.php");
        }
        switch($db_config['db']){
            case null:
                break;
            case 'mysql':
                $mysql = $db_config['mysql'];
                $dsn = 'mysql:host='.$mysql['host'].';dbname='.$mysql['name'].';charset='.$mysql['charset'];
                DB::connect($dsn, $mysql['user'], $mysql['pass']);
                break;
            case 'sqlite3':
                $file = $db_config['sqlite3']['file'];
                $dsn = 'sqlite:'.DB.'sqlite3/'.$file;
                DB::connect($dsn, '', '', true);
                break;
        }
    }

    public static function connect($dsn, $user, $pass, $sqlite3 = false){
        try{
            if(self::$link != null) throw new PDOException('already connected to DB');

            if($sqlite3)
                self::$link = new \PDO($dsn);
            else
                self::$link = new \PDO($dsn, $user, $pass);
        }
        catch(PDOException $e){
            error_log($e->getMessage().'<br/>'.'line: '.$e->getLine());
        }
    }

    public static function getDB(){
        return self::$link;
    }

    public static function close(){
        self::$link = null;
    }

    public function __clone(){

    }
    
    public function __wakeup(){

    }

    
}
