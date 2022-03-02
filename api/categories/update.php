<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate Category object
$category = new Category($connection);

// Get raw PUT data
$data = json_decode(file_get_contents("php://input"));

// Get ID and category to update from data
$category->id = $data->id;
$category->category = $data->category;

// Attempt to update
if (empty($category->category) || empty($category->id)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($category->update()) {
    // Update operation successful
    echo json_encode([
        'id' => $category->id,
        'category' => $category->category,
    ]);
} else {
    // Update operation failed
    echo json_encode([
        'message' => 'Category Not Created'
    ]);
}
