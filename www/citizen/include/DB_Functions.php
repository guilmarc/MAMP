<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }


    /**
     * Get report for a specific user or for all ($user_id="")
     */
    public function getReports($user_id) {
        
        if($user_id == "") 
        {
            $result = mysql_query("SELECT * FROM reports") or die(mysql_error());
        } else 
        {
            $result = mysql_query("SELECT * FROM reports WHERE user_id = '$user_id'") or die(mysql_error());
        }
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            return $result;
            
        } else {
            // reports not found
            return false;
        }
    }

    /**
     * Storing new report
     * returns new report ID
     */
    public function updateReport($id, $category_id, $title, $description, $latitude, $longitude) {
        return mysqli_query($connection, "UPDATE reports SET category_id='$category_id', title='$title', description='$description', latitude='$latitude', longitude='$longitude', updated_at=NOW() WHERE id = '$id'");
    }


    /**
     * Deleting report
     * 
     */
    public function deleteReport($id, $user_id) {
        $result = mysql_query("DELETE FROM reports WHERE id = '$id' and user_id = '$user_id'");

        if (mysql_affected_rows() == 1)
        {
            return TRUE;
        } 
        else
        {
            return FALSE;
        }
    }


    /**
     * Storing new report
     * returns new report ID
     */
    public function storeReport($user_id, $category_id, $title, $description, $latitude, $longitude, $image) {

        $image_url = "";

        $result = mysql_query("INSERT INTO reports(user_id, category_id, title, description, latitude, longitude, image_file, created_at) VALUES('$user_id', '$category_id', '$title', '$description', '$latitude', '$longitude', '$image', NOW())");
   
        if ($result) {
            return mysql_insert_id();
        } else {
            return false;
        }
    } 

    public function getReportImageFile($id) {
        $result = mysql_query("SELECT image_file FROM reports WHERE id = '$id'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $image_file = $result['image_file'];
            
            echo $image_file;

            return $image_file;

        } else {
            // user not found
            return false;
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
		$result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");
        // check for successful store
        if ($result) {
            // get user details 
            $result = mysql_query("SELECT * FROM users WHERE unique_id = \"$uuid\"");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
        $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
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
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
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
