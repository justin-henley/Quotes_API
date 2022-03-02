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

// Check for missing paramters
if (empty($category->category)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
// Create Category entry in database
else if ($category->create()) {
    echo json_encode([
        'id' => $connection->lastInsertId(),
        'category' => $category->category,
    ]);
} else {
    echo json_encode(['message' => 'Category Not Created']);
}
