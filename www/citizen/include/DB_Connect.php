<?php
class DB_Connect {


   

    // constructor
    function __construct() {
        
    }

    // destructor
    function __destruct() {
        // $this->close();
    }

    public $connection;

    // Connecting to database
    public function connect() {
        require_once 'include/config.php';
        // connecting to mysql
        //$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
        // selecting database
       // mysqli_select_db($con, DB_DATABASE);

        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // return database handler
        return $this->connection;
    }

    // Closing database connection
    public function close() {
        mysql_close();
    }

}

?>
