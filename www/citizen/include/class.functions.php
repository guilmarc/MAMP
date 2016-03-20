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

            //echo $this->pdo->query("SELECT * FROM reports");
            $result = $this->pdo->query("SELECT * FROM reports");

            $array = $result->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }


    function getMissingReportFromList($reports){
        try {

            //$sth = $this->pdo->prepare("SELECT * FROM reports WHERE archived = FALSE AND id NOT IN (1,2,3,4,5)");


            $inClause = empty($reports) ? "0" : implode(",", $reports);
            $sth = $this->pdo->prepare("SELECT * FROM reports WHERE archived = FALSE AND id NOT IN (" . $inClause  . ")");

            $sth->execute();

            //We must not filter by user cause the same user can log into 2 different devices
            //$sth = $this->pdo->prepare("SELECT * FROM reports WHERE created_at > ? AND user_id != ?");
            //$sth->execute(array($local_sync_date, $user_id));

            $array = $sth->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }



    function getCreatedReportsSince($local_sync_date, $user_id){
        try {


            $sth = $this->pdo->prepare("SELECT * FROM reports WHERE created_at > ? AND archived = FALSE");
            $sth->execute(array($local_sync_date));

            //We must not filter by user cause the same user can log into 2 different devices
            //$sth = $this->pdo->prepare("SELECT * FROM reports WHERE created_at > ? AND user_id != ?");
            //$sth->execute(array($local_sync_date, $user_id));

            $array = $sth->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }

    function getUpdatedReportsSince($local_sync_date, $reports){
        try {

            //echo $local_sync_date;

            $inClause = empty($reports) ? "0" : implode(",", $reports);
            //$sth = $this->pdo->prepare("SELECT * FROM reports WHERE updated_at > ?");
            $sth = $this->pdo->prepare("SELECT * FROM reports WHERE updated_at > ? AND id IN (" . $inClause . ")");
            $sth->execute(array($local_sync_date));

            //$sth = $this->pdo->prepare("SELECT * FROM reports WHERE updated_at > ? AND user_id != ?");
            //$sth->execute(array($local_sync_date, $user_id));

            $array = $sth->fetchAll( PDO::FETCH_ASSOC );

            return $array;

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }


    /*
     * Storing new report
     * returns new report ID
     */
    public function storeReport($user_id, $category_id, $title, $description, $latitude, $longitude, $image) {

        try {

            $sth = $this->pdo->prepare("INSERT INTO reports(user_id, category_id, title, description, latitude, longitude, image_file, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
            $sth->execute(array($user_id, $category_id, $title, $description, $latitude, $longitude, $image));

            return $this->pdo->lastInsertId();

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }

    }


    public function getReportImageFile($id) {
        try {
            $sth = $this->pdo->prepare("SELECT image_file FROM reports WHERE id = ?");
            $sth->execute(array($id));

            return  $sth->fetchColumn();

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }
    }

    //Attention, mush be the same user to be able to update
    public function updateReport($id, $user_id, $category_id, $title, $description, $latitude, $longitude) {

        try {

            //echo "ID=".$id;
            //echo "USER=".$user_id;
            //echo "CAT=".$category_id;
            //echo "TITLE=".$title;
            //echo "DESCRIPTION=".$description;
            //echo "LATITUDE=".$latitude;
            //echo "LONGITUDE=".$longitude;

            $sth = $this->pdo->prepare("UPDATE reports SET category_id=?, title=?, description=?, latitude=?, longitude=?, updated_at=NOW() WHERE id = ? AND user_id = ?");
            $sth->execute(array($category_id, $title, $description, $latitude, $longitude, $id, $user_id));


            return $sth->rowCount();

        } catch (PDOException $ex) {
            echo  $ex->getMessage();
        }

    }



    public function archiveReport($id, $user_id) {

        try {

            $sth = $this->pdo->prepare("UPDATE reports SET archived = 1 WHERE id = ? AND user_id = ?");
            $sth->execute(array($id, $user_id));

            return $sth->rowCount();

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