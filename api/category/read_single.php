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
    : null;

// TODO this is ugly
// Return early if parameters missing;
if (empty($category->id)) {
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
    return;
}

// Get category data
$category->readSingle();

// Check if a category was returned for the specified ID (category exists in database)
if ($category->category) {
    // Read operation found a category
    // Create results array
    $catArr = [
        'id' => $category->id,
        'category' => $category->category,
    ];

    // Convert to JSON and output 
    echo (json_encode($catArr));
} else {
    // Read operation did not find a category
    echo json_encode([
        'message' => 'categoryId Not Found'
    ]);
}
