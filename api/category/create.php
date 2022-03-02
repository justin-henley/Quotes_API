<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate Category object
$category = new Category($connection);

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

// Get category name from data
$category->category = $data->category;

// Attempt to create
if (empty($category->category)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($category->create()) {
    // Create operation successful
    echo json_encode([
        'id' => $connection->lastInsertId(),
        'category' => $category->category,
    ]);
} else {
    // Create operation failed
    echo json_encode([
        'message' => 'Category Not Created'
    ]);
}
