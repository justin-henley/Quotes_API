<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';




// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate quote object
$quote = new Quote($connection);

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

// Get quote name from data
$quote->quote = $data->quote;
$quote->authorId = $data->authorId;
$quote->categoryId = $data->categoryId;

// Attempt to create the quote
// Check all parameters are non-null, and category and author exist in the database

if (empty($quote->quote) || empty($quote->authorId) || empty($quote->categoryId)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if (!$quote->authorExists()) {
    // Author not found in database
    echo json_encode([
        'message' => 'authorId Not Found'
    ]);
} else if (!$quote->categoryExists()) {
    // Category not found in database
    echo json_encode([
        'message' => 'categoryId Not Found'
    ]);
} else if ($quote->create()) {
    // Category and Author both exist
    // Create operation successful
    echo json_encode([
        'id' => $connection->lastInsertId(),
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId,
    ]);
} else {
    // Create operation failed
    echo json_encode([
        'message' => 'Quote Not Created'
    ]);
}
