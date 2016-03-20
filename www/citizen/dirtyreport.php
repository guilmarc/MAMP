<?php
/**
 * Created by PhpStorm.
 * User: guilmarc
 * Date: 2016-03-17
 * Time: 9:19 PM
 */

// response Array
$response = array("success" => 0, "error" => 0);

// include db handler
require_once 'include/class.functions.php';
$functions = new Functions();

$data = json_decode(file_get_contents('php://input'), true);

$local_sync_date = $data['local_sync_date'];
$reports = $data['reports'];


$created = $functions->getMissingReportFromList($reports);
$updated = $functions->getUpdatedReportsSince($local_sync_date, $reports);

$response["success"] = 1;
$response["created"] = $created;
$response["updated"] = $updated;
echo json_encode($response);