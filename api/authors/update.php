<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate author object
$author = new Author($connection);

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

// Get author name from data
$author->author = $data->author;

// Attempt to create
if (empty($author->author)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($author->create()) {
    // Create operation successful
    echo json_encode([
        'id' => $connection->lastInsertId(),
        'author' => $author->author,
    ]);
} else {
    // Create operation failed
    echo json_encode([
        'message' => 'Author Not Created'
    ]);
}
