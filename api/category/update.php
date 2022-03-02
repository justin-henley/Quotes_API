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

// Set ID to update
$category->id = $data->id;

// Update category name property
$category->category = $data->category;

// Update Category entry in database
if ($category->update()) {
    echo json_encode(['message' => 'Category Created']);
} else {
    echo json_encode(['message' => 'Category Not Created']);
}
