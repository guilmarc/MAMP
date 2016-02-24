<?php
    
class Functions {

	private $pdo;

    function __construct() {
        require_once 'class.connection.php';
        // connecting to database
        $this->pdo = (new Connection())->connect();
    }

    // destructor
    function __destruct() {
        
    }


    function getReports(){
        try {
            $result = $this->pdo->query("SELECT * FROM reports");

            $array = $result->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }

    function getUsers(){
        try {
            $result = $this->pdo->query("SELECT * FROM users");

            $array = $result->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }


    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $result = $this->pdo->query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");

        // check for successful store
        if ($result) {
            // get user details

            $statement = $this->pdo->query("SELECT * FROM users WHERE unique_id = \"$uuid\"");

            $result = $statement->fetch( PDO::FETCH_ASSOC );
            // return user details
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $result = $this->pdo->query("SELECT * FROM users WHERE email = '$email'");

        // check for result
        //$no_of_rows = $result->rowCount();
        if ($result->rowCount() > 0) {
            $result = $result->fetch( PDO::FETCH_ASSOC );
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {

        $result = $this->pdo->query("SELECT email from users WHERE email = '$email'");

        return $result->rowCount() == 0 ? FALSE : TRUE;

        //Should be the same as
        //return $this->pdo->query("SELECT email from users WHERE email = '$email'");

    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }


}

?>