<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate Category object
$category = new Category($connection);

// Get ID from URL
$category->id = isset($_GET['id'])
    ? $_GET['id']
    : die();

// Get category data
$category->readSingle();

// Check if a category was returned for the specified ID (category exists in database)
if ($category->category) {
    // Create results array
    $catArr = [
        'id' => $category->id,
        'category' => $category->category,
    ];

    // Convert to JSON and output
    echo (json_encode($catArr));
} else {
    // Create error message
    echo json_encode(['message' => 'categoryId Not Found']);
}
