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

// Get ID and author to update from data
$author->id = $data->id;
$author->author = $data->author;

// Attempt to update
if (empty($author->author) || empty($author->id)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($author->update()) {
    // update operation successful
    echo json_encode([
        'id' => $author->id,
        'author' => $author->author,
    ]);
} else {
    // update operation failed
    echo json_encode([
        'message' => 'Author Not updated'
    ]);
}
