<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate Category object
$category = new Category($connection);

// Get raw DELETE data
$data = json_decode(file_get_contents("php://input"));

// Set ID to delete
$category->id = $data->id;

// Attempt to delete
if (empty($category->id)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($category->delete()) {
    // Delete operation successful
    echo json_encode([
        'id' => $category->id
    ]);
} else {
    // Delete operation failed
    echo json_encode([
        'message' => 'Category Not Deleted'
    ]);
}
