<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate quote object
$quote = new Quote($connection);

// Get raw PUT data
$data = json_decode(file_get_contents("php://input"));

// Get ID and quote to update from data
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->authorId = $data->authorId;
$quote->categoryId = $data->categoryId;

// Attempt to update
if (empty($quote->id) || empty($quote->quote) || empty($quote->authorId) || empty($quote->categoryId)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($quote->update()) {
    // Update operation successful
    echo json_encode([
        'id' => $quote->id,
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId,
    ]);
} else {
    // Update operation failed
    echo json_encode([
        'message' => 'Quote Not Created'
    ]);
}
