<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate author object
$author = new Author($connection);

// Get raw DELETE data
$data = json_decode(file_get_contents("php://input"));

// Set ID to delete
$author->id = $data->id;

// Attempt to delete
if (empty($author->id)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else {
    $result = $author->delete();

    if ($result === true) {
        // Delete operation successful
        echo json_encode([
            'id' => $author->id
        ]);
    } else if ($result === false) {
        // Delete operation found no author with that id
        echo json_encode([
            'message' => 'No Authors Found'
        ]);
    } else {
        // Delete opreation called on an author that is used in a quote
        echo json_encode([
            'message' => 'Author cannot be deleted'
        ]);
    }
}
