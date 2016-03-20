<?php


if (isset($_POST['tag']) && $_POST['tag'] != '') {

    // get tag
    $tag = $_POST['tag'];

    // include db handler
    require_once 'include/class.functions.php';
    $functions = new Functions();

    // response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);

    if ($tag == 'insert')
    {
        $user_id = $_POST['user_id'];
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];


        $imageExt = explode(".", $image)[1];

        //if (strtoupper($imageExt) == 'PNG' || strtoupper($imageExt) == 'JPG') {
        //Will always be JPG for now
        //}

        $imageFile = rand(0, 100000).rand(0, 100000).rand(0, 100000).time().".".$imageExt;

        $id = $functions->storeReport($user_id, $category_id, $title, $description, $latitude, $longitude, $imageFile);

        //echo "ID=".$id;

        if ($id) {


            //echo $tmp_image;
            //echo $imageFile;


            if(move_uploaded_file($tmp_image, "images/$imageFile")){
                // user stored successfully
                $response["success"] = 1;
                $response["id"] = $id;
                $response["image"] = $imageFile;

                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = 14;
                $response["error_msg"] = "Unable to save image";
                echo json_encode($response);
            }

        } else {
            // user failed to store
            $response["error"] = 10;
            $response["error_msg"] = "Error occured while saving report";
            echo json_encode($response);
        }


    }
    elseif ($tag == 'update')
    {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        //$image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];

        $result = $functions->updateReport($id, $user_id, $category_id, $title, $description, $latitude, $longitude);

        if ($result) {
            // user stored successfully
            //$response["success"] = 1;
            //echo json_encode($response);

            $imageFile = $functions->getReportImageFile($id);

            //echo $imageFile;

            if(move_uploaded_file($tmp_image, "images/$imageFile")){
                // user stored successfully
                $response["success"] = 1;
                $response["id"] = $id;
                //$response["image"] = $imageFile;

                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = 14;
                $response["error_msg"] = "Unable to save image, pff";
                echo json_encode($response);
            }


        } else {
            // user failed to store
            $response["error"] = 11;
            $response["error_msg"] = "Error occured while updating report";
            echo json_encode($response);
        }

    }

    elseif ($tag == 'select')
    {
       //$user_id = $_POST['user_id'];

        $result = $functions->getReports();

        if ($result) {
            // user stored successfully
            $response["success"] = 1;
            $response["reports"] = $result;
            echo json_encode($response);

        } else {
            // user failed to store
            $response["error"] = 12;
            $response["error_msg"] = "Error occured while fetching report";
            echo json_encode($response);
        }

    }
    elseif ($tag == 'get_created')
    {
        $local_sync_date = $_POST['local_sync_date'];
        $user_id = $_POST['user_id'];


        $result = $functions->getCreatedReportsSince($local_sync_date, $user_id);

        //echo json_encode($result);
        //echo $result;

        //if ($result) {
            // user stored successfully
            $response["success"] = 1;
            $response["reports"] = $result;
            echo json_encode($response);

        //} else {
            // user failed to store
        //    $response["error"] = 12;
        //    $response["error_msg"] = "Error occured while fetching report";
        //    echo json_encode($response);
        //}

    }
    elseif ($tag == 'get_updated')
    {
        $local_sync_date = $_POST['local_sync_date'];
        $user_id = $_POST['user_id'];

        $result = $functions->getUpdatedReportsSince($local_sync_date);

        //if ($result) {
            // user stored successfully
            $response["success"] = 1;
            $response["reports"] = $result;
            echo json_encode($response);

        //} else {
            // user failed to store
        //    $response["error"] = 12;
        //    $response["error_msg"] = "Error occured while fetching report";
        //    echo json_encode($response);
        //}

    }
    elseif ($tag == 'get_dirty')
    {

        $data = json_decode(file_get_contents('php://input'), true);

        print_r($data);

        //echo json_encode($data);
    }
    elseif ($tag == 'archive') {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];

        $result = $functions->archiveReport($id, $user_id);

        if ($result) {
            // user stored successfully
            $response["success"] = 1;
            echo json_encode($response);

        } else {
            // user failed to store
            $response["error"] = 13;
            $response["error_msg"] = "Error occured while archiving report";
            echo json_encode($response);
        }
    }
    else
    {
        echo "Invalid Request";
    }

} else {
    echo "Access Denied";
}
?>
