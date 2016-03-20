<?php
class Connection {

    // constructor
    function __construct() {
        
    }

    // destructor
    function __destruct() {
        // $this->close();
    }

    // Connecting to database
    public function connect() {
        require_once 'include/config.php';

        try
        {
             $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD, array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //PDO('mysql:host=localhost;dbname=test', $user, $pass, array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        }
        catch(PDOException $e)
        {
             echo $e->getMessage();
        }

        // return database handler
        return $pdo;

    }

    // Closing database connection
  

}

?>