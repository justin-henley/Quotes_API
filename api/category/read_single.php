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

// Create results array
$catArr = [
    'id' => $category->id,
    'category' => $category->category,
];

// Convert to JSON and output
print_r(json_encode($catArr));

// TODO error handling